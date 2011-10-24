/**
 * 
 */
changedEls = {};
successColor = '#AFA';
waitingColor = '#FF9';
failColor    = '#FAA';

function inputHasChanged(obj) {
	changedEls[obj.name] = obj.value;
}
function inputHasFocus(obj) {
	alert(obj.name + " has focus");
	obj.focus = null;
}

function updateFormInputs( valuesMap ) {
	// update current config information
	jQuery("[name='current_config_type']").attr('value', valuesMap['configID'][0] );
	jQuery("[name='current_config_name']").attr('value', valuesMap['configID'][1] );

	jQuery('#themeNotifications').attr('value', valuesMap['message']);
	var liveSwitch = jQuery('#live_switch');
	if (valuesMap['isLive']) {
		liveSwitch.attr('disabled', 'disabled');
	} else {
		liveSwitch.removeAttr('disabled');
	}
	for(var k in valuesMap) {
		//alert(k + ' : ' + valuesMap[k]);
		
	}
}

function isValidSize(val) {
    if( val == '' ) {
		return true;
    } else if(parseInt(val)) {
		return val.match('px$|em$|%$');
	} else {
		if (parseFloat(val)) {
			return val.match('em$|%$');
		} else {
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
    	jQuery('div[id="-tabs"]').bind(  'tabsshow', 
        	function(event, ui) { 
    			// get the open accordion for this tab
    			var tabName = ui.tab.innerHTML;
    			var oa = getOpenAccordion(tabName);
				// call updateDependents on the accordion
				if (oa) {
					updateDependents(oa);
					}
    			});
	});
function handleResponse(response) {
	// remove trailing 0
	var sliced = response.slice(0, -1);
	// convert string to js object
	var parsed = jQuery.parseJSON(sliced);
	
	// handle new form
	
	// handle new config select
    var form = jQuery('#ap_options_form');
    form.hide();
    form.html(parsed['formHTML']); 
    updateFormInputs(parsed);
    update_config_select(parsed['configSelectHTML']);
    
    // restore form
    initColorPicker();                     
    form.fadeIn('fast');
}

function delete_config() {
	// TODO check for outstanding changes	
		
	var currentConfigType = jQuery("input[name=current_config_type]").val();
	var currentConfigName = jQuery("input[name=current_config_name]").val();

	jQuery('#-tabs').fadeOut('fast');
        var data = {
            action: 'delete_config',
            inputs: {
				'configType' : currentConfigType,
				'configName' : currentConfigName
			}
        };
        jQuery.post(ajaxurl, data, function(response) {
			response = jQuery.parseJSON(response.slice(0, -1));
            var form = jQuery('#ap_options_form');
            form.hide();
            form.html(response['formHTML']); 
            updateFormInputs(response);
	        update_config_select(response['configSelectHTML']);
            initColorPicker();                     
            form.fadeIn('fast');
        });
	
}

function new_config() {
	// TODO check for outstanding changes
	var name_candidate = prompt("name the new configuration", "");
	if (name_candidate) {
		jQuery('#-tabs').fadeOut('fast');
	    var data = {
	        action: 'new_config',
	        inputs: {
				'config' : name_candidate
			}
	    };
	        
	    jQuery.post(ajaxurl, data, function(response) {
			response = jQuery.parseJSON(response.slice(0, -1));
	        var form = jQuery('#ap_options_form');
	        form.hide();
	        form.html(response['formHTML']); 
	        updateFormInputs(response);
	        update_config_select(response['configSelectHTML']);
	        initColorPicker();                     
	        form.fadeIn('fast');
	    });
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
    jQuery.post(ajaxurl, data, function(response) {
		response = jQuery.parseJSON(response.slice(0, -1));
        var form = jQuery('#ap_options_form');
        updateFormInputs(response);
        update_config_select(response['configSelectHTML']);
    });
}

function update_config_select(html) {
	var select = jQuery('#change_edit_config');
	select.html(html);
}

function change_edit_config(selectObj) {
	// TODO check for outstanding changes	
	
	jQuery('#-tabs').fadeOut('fast');
    var data = {
        action: 'get_config',
        inputs: {
			'config' : selectObj.value
		}
    };
        
    jQuery.post(ajaxurl, data, function(response) {
    	var sliced = response.slice(0, -1);
		var parsed = jQuery.parseJSON(sliced);
        var form = jQuery('#ap_options_form');
        form.hide();
        form.html(parsed['formHTML']); 
        updateFormInputs(parsed);
        update_config_select(parsed['configSelectHTML']);
        initColorPicker();                     
        form.fadeIn('fast');
    });
}
function save(configType, configName) {
	// make a deep copy of all changed input elements
	var modifiedInputs = jQuery.extend(true, {}, changedEls);
	
	// add all global elements
	var globalSettings = jQuery('.globalSetting');
	for(index = 0; index < globalSettings.length; index++ ) {
		var gs = globalSettings[index];
		modifiedInputs[gs.name] = gs.value;
	}
	
	var inputString = JSON.stringify(modifiedInputs);
    var data = {
        action: 'save_config',
        inputs: {
			'configType' : 'user',
			'configName' : configName,
			'cs'         : inputString
		}
    };
    jQuery.post(ajaxurl, data, function(response) {
		response = jQuery.parseJSON(response.slice(0, -1));
        updateFormInputs(response);
        update_config_select(response['configSelectHTML']);
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