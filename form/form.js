/**
 * 
 */
changedEls = {};

successColor = '#AFA';
successColorDark = '#8D8';

waitingColor = '#FF9';
waitingColorDark = '#DD7';

warningColor = waitingColor;
warningColorDark = waitingColorDark;

failColor    = '#FAA';
failColorDark = '#D88';

function inputHasChanged(obj) {
	changedEls[obj.name] = obj.value;
}
function inputHasFocus(obj) {
	alert(obj.name + " has focus");
	obj.focus = null;
}
function getMessageColor( valuesMap ) {
	if (valuesMap['message_type'] == 'success') {
		return successColor;
	} else if (valuesMap['message_type'] == 'warning') {
		return warningColor;
	} else if (valuesMap['message_type'] == 'fail') {
		return failColor;
	}
}
function getMessageDarkerColor( valuesMap ) {
	if (valuesMap['message_type'] == 'success') {
		return successColorDark;
	} else if (valuesMap['message_type'] == 'warning') {
		return warningColorDark;
	} else if (valuesMap['message_type'] == 'fail') {
		return failColorDark;
	}
}
function setMessageColor(obj, valuesMap) {
	// set the background color
	obj.css('background-color', getMessageColor(valuesMap) );
	obj.css('border-color', getMessageDarkerColor(valuesMap) );
}
function updateFormInputs( valuesMap ) {
	// update current config information
	jQuery("[name='current_config_type']").attr('value', valuesMap['configID'][0] );
	jQuery("[name='current_config_name']").attr('value', valuesMap['configID'][1] );
	
	var notes = jQuery('#themeNotifications');
	notes.html( valuesMap['message'] );

	setMessageColor(notes, valuesMap);
	notes.show();
	notes.delay(3000).fadeOut(1500);
	
	var liveSwitch = jQuery('#live_switch');
	if (valuesMap['isLive']) {
		liveSwitch.attr('disabled', 'disabled');
	} else {
		liveSwitch.removeAttr('disabled');
	}
	if (valuesMap['configSelectHTML']) {
		updateConfigSelect(valuesMap['configSelectHTML']);
	}
	for(var k in valuesMap) {
		//alert(k + ' : ' + valuesMap[k]);
	}
}
function isOutstandingChanges() {
	var changed = false;
	for(var x in changedEls) {
		changed = true;
		break;
	}
	if(changed) return true;
	else return false;
}
function promptOutstandingChanges() {
	if( isOutstandingChanges() ) {
		if(confirm('Discard unsaved changes to this configuration?') ) return true;
		else return false;
	} else return true;
}
function isValidSize(val) {
    if( val == '' ) {
		return true;
    } else {
    	var num = parseFloat(val);
    	if( num || num == 0) {
    		return val.match('px$|em$|%$');
	}  else {
			return false;
		}
	}
}
function checkValidSize(sizeInputEl) {
	var val = jQuery.trim(sizeInputEl.value);
	sizeInputEl.value = val;
	if(isValidSize(val)) {
		inputHasChanged(sizeInputEl);
		sizeInputEl.style.background = successColor;
	} else {
		jQuery(sizeInputEl).css('background-color', failColor).animate({'background-color': '#FFF'}, 'slow');
		sizeInputEl.value = '';
	}
}
openAccordions = [];
function setOpenAccordion(tabName, accordionLink) { 
    openAccordions[tabName] = accordionLink;
}
function getOpenAccordion(tabName) {
    var oa = openAccordions[tabName];  
	return oa;
}
function mainTabClick(tab) {
	var oa = getOpenAccordion();
	if (oa) {
		updateDependents();
	}
}
function accordionClick(accordionLink) {
	var tabName = jQuery('.ui-tabs-selected').first().children().html();
	setOpenAccordion(tabName, accordionLink);
	updateDependents(accordionLink);
}
jQuery(document).ready(
	function() {
    	jQuery('div[id="-tabs"]').bind(  
    			'tabsshow', 
	        	function(event, ui) { 
	    			// get the open accordion for this tab
	    			var tabName = ui.tab.innerHTML;
	    			var oa = getOpenAccordion(tabName);
					// call updateDependents on the accordion
					if (oa) {
						updateDependents(oa);
						}
	    			}
		);
    	jQuery.each(
    			jQuery('a[href^="#-tabs-"'), 
    			function(tabLink) {
    				tabLink.bind('click', 
					function(tabLink) {
    					// get the open accordion for this tab
    					var name = tabLink.html;
    					updateDependents(name);    				
    				});
    			}
		);
	}
);

function handleResponse(response) {
	//// remove trailing 0
	//var sliced = response.slice(0, -1);
	//// convert string to js object
	//var parsed = jQuery.parseJSON(sliced);
	//
	//// handle new form
	//
	//// handle new config select
    //var form = jQuery('#ap_options_form');
    //form.hide();
    //form.html(parsed['formHTML']); 
    //updateFormInputs(parsed);
    //updateConfigSelect(parsed['configSelectHTML']);
    //
    //// restore form
    //initColorPicker();                     
    //form.fadeIn('fast');
	changedEls = null;
    
	response = jQuery.parseJSON(response.slice(0, -1));
    var form = jQuery('#ap_options_form');
    //form.hide();
    var formHTML = response['formHTML'];
    form.html(formHTML); 
    updateFormInputs(response);
    form.fadeIn('fast');
    initColorPicker();                     
}

function changeConfig(data) {
	//jQuery('#-tabs').fadeOut('fast');
	jQuery('#ap_options_form').fadeOut('fast');
    jQuery.post(ajaxurl, data, function(response) {
    	handleResponse(response);
    });
}

function delete_config() {
	// TODO check for outstanding changes	
	var currentConfigType = jQuery("input[name=current_config_type]").val();
	var currentConfigName = jQuery("input[name=current_config_name]").val();
	var proceed = confirm('Delete user configuration: "' + currentConfigName + '"?');

	if(proceed) {
        var data = {
            action: 'delete_config',
            inputs: {
				'configType' : currentConfigType,
				'configName' : currentConfigName
			}
        };
		changeConfig(data);
	}
}

function new_config() {
	// TODO check for outstanding changes
	if(promptOutstandingChanges()) {
		
		var name_candidate = prompt("name the new configuration", "");
		if (name_candidate) {
		    var data = {
		        action: 'new_config',
		        inputs: {
					'config' : name_candidate
				}
		    };
		        
			changeConfig(data);
		}
	}

}

function set_live_config() {
	// TODO check for outstanding changes	
	
	var currentConfigType = jQuery("input[name=current_config_type]").val();
	var currentConfigName = jQuery("input[name=current_config_name]").val();

    var data = {
        action: 'set_live_config',
        inputs: {
			'configType' : currentConfigType,
			'configName' : currentConfigName
		}
    };
	changeConfig(data);
}

function updateConfigSelect(html) {
	var select = jQuery('#change_edit_config');
	select.html(html);
}

function change_edit_config(selectObj) {
	// TODO check for outstanding changes	
	if(promptOutstandingChanges()) {

	    var data = {
	        action: 'get_config',
	        inputs: {
				'config' : selectObj.value
			}
	    };
	        
		changeConfig(data);
	}
}
function save(configType, configName) {
	// make a deep copy of all changed input elements
	var modifiedInputs = jQuery.extend(true, {}, changedEls);
	
	// add all global elements
	var globalSettings = jQuery('.globalSetting');
	for(var index = 0; index < globalSettings.length; index++ ) {
		var gs = globalSettings[index];
		modifiedInputs[gs.name] = gs.value;
	}
	
	var inputString = JSON.stringify(modifiedInputs);
    var data = {
        action: 'save_config',
        inputs: {
			'configType' : configType,
			'configName' : configName,
			'cs'         : inputString
		}
    };
    jQuery.post(ajaxurl, data, function(response) {
		response = jQuery.parseJSON(response.slice(0, -1));
        updateFormInputs(response);
        updateConfigSelect(response['configSelectHTML']);
        for (var name in changedEls ) {
			var el = jQuery('[name="' + name + '"]');
			jQuery(el).animate({backgroundColor: '#FFF'}, 'slow');
        }
        // reset changedEls
        changedEls = {};
    });		
}
function save_config() {
	// TODO check for outstanding changes	
	
	var currentConfigType = jQuery("input[name=current_config_type]").val();
	var currentConfigName = jQuery("input[name=current_config_name]").val();

	save(currentConfigType, currentConfigName);
}

function save_as_config() {
	var candidate_config_name = jQuery.trim(prompt('enter a name', ''));
	
	if(candidate_config_name) {
		// TODO check for outstanding changes	
		save('user', candidate_config_name);
	}
}