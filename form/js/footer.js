target = document.getElementById('style-manager');
spinner = new Spinner(spinOpts).spin(target);
spinner.spin(target);

// -----------------------------------------------------------------------------

function updateSectionColors(section) {
	// get global colors
	var colors = getGlobalOptionsHTML(jQuery('.globalColor'));
	var colorOptions = colors.join(''); // TODO possibly supply "\n" as arg

	// decide if update needs to happen
	
	// get ahold of one section color
	var sectionDiv = section.nextSibling;
	// get all section colors
	var section_colors = jQuery(sectionDiv).find('.section_color');

	// compare the global color options with a section color's options
	// to see if they're inconsistent and therefore need updating
	var first = section_colors[0];
	if (colorOptions != first.innerHTML) { // FIXME currently doesn't give
											// positive result due to whitespace
		for ( var i = 0; i < section_colors.length; i++) {
			var sc = section_colors[i];
			// store currently selected option
			var select_value = sc.value;

			// replace options
			sc.innerHTML = colorOptions;
			if (jQuery(sc).hasClass('section_background_color')) {
				sc.innerHTML += outerHTML(new Option('transparent', colors.length));
			}
			// reset selected option
			sc.value = select_value;
		}
	}
}

// farbtastic
jQuery(document).ready(initColorPicker);
function initColorPicker() {
	var f = jQuery.farbtastic('#picker');
	var p = jQuery('#picker').css('opacity', 0.25);
	var selected;
	jQuery('.colorwell').each(function() {
		f.linkTo(this);
		jQuery(this).css('opacity', 0.75);
	}).focus(
			function() {
				if (selected) {
					jQuery(selected).css('opacity', 0.75).removeClass(
							'colorwell-selected');
				}
				f.linkTo(this);
				p.css('opacity', 1);
				jQuery(selected = this).css('opacity', 1).addClass(
						'colorwell-selected');
			});
	/*
	 * the user will not have selected the last global color text input field
	 * yet it remains linked to the color picker therefore unlink the last color
	 * text box by supplying an empty function
	 */
	f.linkTo(function() {
	});
	// add a callback to the color picker to update the dependent section color
	// dropdowns ?>
	// p.bind('mouseleave', updateDependentsOf_<?php echo __CLASS__ ?>);
}

// -----------------------------------------------------------------------------

function outerHTML(node) {
	return node.outerHTML || new XMLSerializer().serializeToString(node);
}
function getGlobalOptionsHTML(globalOptions) {
	// add the null option to the array
	globalOptions.splice(0, 0, '');

	// create the new options
	// var spaces = "\u00A0\u00A0\u00A0";
	var options = [ outerHTML(new Option('', 0)) ];
	for ( var i = 1; i < globalOptions.size(); i++) {
		var val = globalOptions[i].value;
		var opt = new Option(// i + spaces +
		val, i);
		options.push(outerHTML(opt));
	}
	return options;
}
function updateDependents(section) {
	if (section) {
		updateSectionColors(section);

		// update font families
		updateSectionFontFamilies(section);

		// update font sizes
		updateSectionFontSizes(section);
	}
	return;
}

// -----------------------------------------------------------------------------
//jQuery(document).ready(convertGlobalFontsToSelectmenus);
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
		//newText +=" title=''";
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
jQuery(document).ready(function() {
	jQuery('.ui-accordion').bind('accordionchange', function(event, ui) {
		//convertSectionFontsToSelectmenus(ui.newContent);
		//convertSectionColorsToSelectmenus(ui.newContent);
	});
});


function updateSectionFontFamilies(section) {

	// get the global fonts
	var fonts = jQuery('select.globalFont');

	// create the new options
	// var spaces = "\u00A0\u00A0\u00A0";
	// add the null option to the array
	var options = outerHTML(new Option('', 0));
	for ( var i = 0; i < fonts.size(); i++) {
		var num = fonts[i].value;
		var selectString = "option[value='" + num + "']";
		var optHTMLVal = // i + spaces +
		jQuery(fonts[i]).find(selectString).html();
		var opt = new Option(optHTMLVal, i + 1);
		options += outerHTML(opt);
	}

	// decide if update needs to happen
	var sectionDiv = section.nextSibling;
	// get all section fonts
	var section_fonts = jQuery(sectionDiv).find('.section_font');

	// compare the global font options with a section font's options
	// to see if they are consistent
	var first = section_fonts[0];
	if (options != first.innerHTML) {
		for ( var i = 0; i < section_fonts.length; i++) {
			var sf = section_fonts[i];
			// store currently selected option
			var select_value = sf.value;

			// replace options
			sf.innerHTML = options;

			// reset selected option
			sf.value = select_value;
		}
	}
}