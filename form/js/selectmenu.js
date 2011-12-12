styleFontDropdown = function(text) {
	var newText = "<div><div class='font-family-select' style='font-family:" + text + ";'>" + text + "</div></div>";
	return newText;
};

styleColorDropdown = function(text) {
	var title = 'color not specified';
	var newText = "<div class='selectmenu-mod";
	// don't add a border to the blank option
	if(text) { 
		newText +="' style='background: " + text + ";'";
		title = text ;
	} else {
		newText += " selectmenu-blank'";
	}
	newText += ">";
	if(text=='transparent') {
		newText += 'transparent';
	}
	newText += "</div>";
	newText = "<div " + " title='" + title + "'" + ">" + newText + "</div>"; 
	return newText;
};

function convertGlobalFontsToSelectmenus() {
	// get the global fonts
	var fonts = jQuery('select.globalFont');

	fonts.selectmenu({
		format : styleFontDropdown
	});
}

function convertSectionFontsToSelectmenus( accordionSection ) {
	var sectionFonts = jQuery(accordionSection).find('select.section_font');
	sectionFonts.selectmenu({
		format : styleFontDropdown
	});
}
function convertSectionColorsToSelectmenus( accordionSection ) {
	var sectionColors = jQuery(accordionSection).find('select.section_color');
	sectionColors.selectmenu({
		format : styleColorDropdown,
		width : 200
	});
}
function selectmenuAccordionEventHandlers(event, ui) {
	// get the open accordion for this tab
	var tabName = ui.tab.innerHTML;
	var oa = getOpenAccordion(tabName);
	// call updateDependents on the accordion
	if (oa) {
		
		convertSectionFontsToSelectmenus(jQuery(oa).next());
		convertSectionColorsToSelectmenus(jQuery(oa).next());
	}
}
function selectMenuizeForm() {
	
	// selectmenuize the global font selectors
	convertGlobalFontsToSelectmenus();
	
	// ensure that dropdowns are selectmenuized when accordions are opened
	jQuery('.ui-accordion').bind('accordionchange', function(event, ui) {
		convertSectionFontsToSelectmenus(ui.newContent);
		convertSectionColorsToSelectmenus(ui.newContent);
	});

	// also ensure that dropdowns are re-selectmenuized [sic] when 
	jQuery('div[id="-tabs"]').bind(	'tabsshow', selectmenuAccordionEventHandlers );
	
}
