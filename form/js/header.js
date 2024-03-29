/** Contants ----------------------------------------------------------------*/
changedEls = {};

successColor = '#AFA';
successColorDark = '#8D8';

waitingColor = '#FF9';
waitingColorDark = '#DD7';

warningColor = waitingColor;
warningColorDark = waitingColorDark;

failColor    = '#FAA';
failColorDark = '#D88';

/** Spinner -----------------------------------------------------------------*/
spinOpts = {
		  lines: 12, // The number of lines to draw
		  length: 7, // The length of each line
		  width: 4, // The line thickness
		  radius: 10, // The radius of the inner circle
		  color: '#000', // #rgb or #rrggbb
		  speed: 1, // Rounds per second
		  trail: 60, // Afterglow percentage
		  shadow: false // Whether to render a shadow
		};

openAccordions = [];

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
function setMessage( valuesMap, delayTime, fadeOutTime ) {
	var obj = getThemeNotifications()
	obj.hide();
	obj.html( valuesMap['message'] );
	setMessageColor( obj, valuesMap );
	obj.show();
	obj.delay( delayTime );
	if (fadeOutTime > 0 ) obj.fadeOut( fadeOutTime );
}
function setMessageColor(obj, valuesMap) {
	// set the background color
	obj.css('background-color', getMessageColor(valuesMap) );
	obj.css('border-color', getMessageDarkerColor(valuesMap) );
}
function getThemeNotifications() {
	return jQuery('#themeNotifications');	
}
function updateFormInputs( valuesMap ) {
	// update current config information
	jQuery("[name='current_config_type']").attr('value', valuesMap['configID'][0] );
	jQuery("[name='current_config_name']").attr('value', valuesMap['configID'][1] );
	
	setMessage( valuesMap, 3000, 1500 );
	
	var liveSwitch = jQuery('#live_switch');
	if (valuesMap['isLive']) {
		liveSwitch.attr('disabled', 'disabled');
	} else {
		liveSwitch.removeAttr('disabled');
	}
	if (valuesMap['configSelectHTML']) {
		updateConfigSelect(valuesMap['configSelectHTML']);
	}
	//for(var k in valuesMap) {
	//	//alert(k + ' : ' + valuesMap[k]);
	//}
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
	
	// dependent dropdowns need to be updated
	var tabName = jQuery('.ui-tabs-selected').first().children().html();
	setOpenAccordion(tabName, accordionLink);
	updateDependents(accordionLink);
}


function handleChangeStyleResponse(response) {
	if( sessionExpired(response) ) {
		
		handleExpiredSession();
	
	} else {
	
		// reset the elements that have been marked as changed
		changedEls = {};
		
		// insert new form
		response = jQuery.parseJSON(response.slice(0, -1));
		var form = jQuery('#ap_options_form');
		var formHTML = response['formHTML'];
		form.html(formHTML);
		
		// update controls etc
		updateFormInputs(response);
		
		
		// bring back to life
		form.fadeIn('fast');
		jQuery('#config_up_download').fadeIn('fast');
	
		// add event handlers
		initForm();
		
		spinner.stop();
		initColorPicker();                     
		
		// re-enable controls
		jQuery('#config-controls input').removeAttr('disabled');
	}
}

function sessionExpired( response ) {
	 return ( response == "-1" ) ? true : false;
}
function handleExpiredSession() {
	setMessage(
			{'message' : 'WordPress session has expired. Please <a href="' 
				+ bloginfo.url + '/wp-login.php">login</a> in again.', 
			 'message_type' : 'fail'}, 0, 0);
}

function changeStyle(data) {
	// disable controls
	jQuery('#config-controls input').attr('disabled', '');
	
	// hide things that are going to chang
	spinner.spin(target);
	jQuery('#ap_options_form').fadeOut('fast');
	jQuery('#config_up_download').fadeOut('fast');
    
	// handle response from server
	jQuery.post(ajaxurl, data, function(response) {
    	handleChangeStyleResponse(response);
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
		changeStyle(data);
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
		        
			changeStyle(data);
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
	changeStyle(data);
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
	        
		changeStyle(data);
	}
}
function ajaxSave(configType, configName, actionString) {
	// set save message
	setMessage( {'message' : 'saving ...', 'message_type' : 'warning'}, 0, 0);	
	
	// make a deep copy of all changed input elements
	var modifiedInputs = jQuery.extend(true, {}, changedEls);
	
	// add all global elements
	var globalSettings = jQuery('.globalSetting[name^="ap_options[cs]"]');
	for(var index = 0; index < globalSettings.length; index++ ) {
		var gs = globalSettings[index];
		modifiedInputs[gs.name] = gs.value;
	}
	
	var inputString = JSON.stringify(modifiedInputs);

    var data = {
        action: actionString,
        inputs: {
			'configType' : configType,
			'configName' : configName,
			'cs'         : inputString
		}
    };
    jQuery.post(ajaxurl, data, function(response) {
    	if( sessionExpired(response) ) {
    		
    		handleExpiredSession();
    	
    	} else {

			response = jQuery.parseJSON(response.slice(0, -1));
		    updateFormInputs(response);
		    updateConfigSelect(response['configSelectHTML']);
		    for (var name in changedEls ) {
				var el = jQuery('[name="' + name + '"]');
				jQuery(el).animate({backgroundColor: '#FFF'}, 'slow');
		    }
		    // reset changedEls
		    changedEls = {};
    	}
	});	
}
function save_config() {
	// TODO check for outstanding changes	
	
	var currentConfigType = jQuery("input[name=current_config_type]").val();
	var currentConfigName = jQuery("input[name=current_config_name]").val();

	ajaxSave(currentConfigType, currentConfigName, 'save_config');
}

function save_as_config() {
	var candidate_config_name = jQuery.trim(prompt('enter a name', ''));
	
	if(candidate_config_name) {
		// TODO check for outstanding changes	
		ajaxSave('user', candidate_config_name, 'save_as_config');
	}
}

function initForm() {
	jQuery('#ap_options_form').css('visibility', 'visible');
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
	selectMenuizeForm();
	jQuery.each(
			jQuery('a[href^="#-tabs-"'), 
			function(tabLink) {
			    jQuery(tabLink).bind('click', 
				function(tabLink) {
					// get the open accordion for this tab
					var name = tabLink.html;
					updateDependents(name);    				
				});
			}
	);
	spinner.stop();
	jQuery('#initial_load_spinner').remove();
}


