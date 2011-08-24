<?php
class Global_Color_Group extends Option_Group implements IHas_Dependents {
    static $singleton;
    private $dependents = array();

    function __construct($display_name, $members=null) {
        if(!self::$singleton) {
            parent::__construct($display_name, $members);
            self::$singleton = $this;
        }
    }

    function get_dependents()              { return $this->dependents; }
    function add_dependent($section_color) { $this->dependents[] = $section_color; }

    function get_html() {
        $children_html = get_html_dependents($this);
        $children = $this->get_children();
        $first = true;
        if ( null != $children) {
            foreach($children as $child) {
                $child_name = $child->get_display_name();
                $child_html = $child->get_html();
                $row = ot('tr');
                $row .= td($child_name, " style='width:200px;'");
                $row .= td($child_html);

                if($first) {
                    $row .= td(div('',
                                    attr_id('picker')),
                               attribute('rowspan', '5') . attr_valign('top'));
                    $first = false;
                }
                $row .= ct('tr');
                $children_html .= $row;
            }
        }
        $the_class = get_class($this);
        $scripts =
"<script>
	function updateDependentsOf_{$the_class}() {
		var colors = jQuery('.globalColor');

    	// add the null option to the array
		colors.splice(0,0,'');

		// create the new options
		var spaces = \"\u00A0\u00A0\u00A0\"
		var options = new Option('', 0).outerHTML;
		for ( i = 1; i < colors.size(); i++ ) {
			var colorVal = colors[i].value;
			var opt = new Option(i + spaces + colorVal, i);
			options += opt.outerHTML;
    	}

    	// inject the options into the dependent selects
    	var deps = dependentsOf_{$the_class};
    	var depsSize = deps.length;
    	for ( i = 0; i < depsSize; i++ ) {
    		var val = deps[i];
    		// get a hold of the select
    		var selectString = 'select[name=\"ap_options[cs][' + val + ']\"]';
    		var select = jQuery(selectString);

    		// find out what option it is currently selected
    		var cur = select.find('option[selected]').val();

    		// replace the existing options with the new options
    		select.html(options);

    		// set the selected value
    		select.val(cur);
    	}
    }

	// farbtastic
    jQuery(document).ready(function() {
        var f = jQuery.farbtastic('#picker');
        var p = jQuery('#picker').css('opacity', 0.25);
        var selected;
        jQuery('.colorwell')
          .each(function () {
          						f.linkTo(this);
          						jQuery(this).css('opacity', 0.75); })
          .focus(function() {
                            if (selected) {
                              jQuery(selected).css('opacity', 0.75).removeClass('colorwell-selected');
                            }
                            f.linkTo(this);
                            p.css('opacity', 1);
                            jQuery(selected = this).css('opacity', 1).addClass('colorwell-selected');
          });" .
		// the user will not have selected the last global color text input field
		// yet it remains linked to the color picker
		// therefore unlink the last color text box by supplying an empty function
		"f.linkTo(function(){});" .
        // add a callback to the color picker to update the dependent section color dropdowns
        "p.bind('mouseleave', updateDependentsOf_{$the_class});
      });

 </script>";
        return table($scripts . $children_html, attr_class('form-table'));
    }
}
class Global_Font_Group extends Option_Group {
    static $singleton;
    private $dependents = array();

    function __construct($display_name, $members=array()) {
        if(!self::$singleton) {
            parent::__construct($display_name, $members);
            self::$singleton = $this;
        }
    }

    function get_dependents()                { return $this->dependents; }
    function add_dependent($section_font)    { $this->dependents[] = $section_font; }

    function get_options() {
        $options = array();
        foreach ($this->get_children() as $member) {
            $member_options = $member->get_options();
            $n = $member->get_value();
            $options[$n] = $member_options[$n];
        }
        return $options;
    }
    function get_html() {
        $children_html = get_html_dependents($this);
        $children = $this->get_children();
        if ( null != $children) {
            foreach($children as $child) {
                if( $child instanceof Setting && !($child instanceof IComposite ) ) {
                    $child_html = get_setting_row( $child );
                } else {
                    $child_html = $child->get_html();
                }
                $children_html .= $child_html;
            }
        }
        $the_class = get_class($this);
        $scripts =
"<script type='text/javascript'>
	function updateDependentsOf_{$the_class}() {
		// get the global fonts
		var fonts = jQuery('.globalFont');

		// add the null option to the array
		fonts.splice(0,0,'');

		// create the new options
		var spaces = \"\u00A0\u00A0\u00A0\"
		var options = new Option('', 0).outerHTML;
		for ( i = 1; i < fonts.size(); i++ ) {
			var num = fonts[i].value;
    		var selectString = \"option[value='\" + num + \"']\";
    		var optHTMLVal = i + spaces + jQuery(fonts[i]).find(selectString).html();
			var opt = new Option(optHTMLVal, i);
			options += opt.outerHTML;
    	}

    	// inject the options into the dependent selects
    	var deps = dependentsOf_{$the_class};
    	var depsSize = deps.length;
    	for ( i = 0; i < depsSize; i++ ) {
    		var val = deps[i];
    		// get a hold of the select
    		var selectString = 'select[name=\"ap_options[cs][' + val + ']\"]';
    		var select = jQuery(selectString);

    		// find out what option it is currently selected
    		var cur = select.find('option[selected]').val();

    		// replace the existing options with the new options
    		select.html(options);

    		// set the selected value
    		select.val(cur);


    	}
    }
</script>";
        return table($scripts . $children_html, attr_class('form-table'));
    }
}
class Global_Font_Size_Group extends Option_Group {
    static $singleton;
    private $dependents = array();

    function __construct($display_name, $members=array()) {
        if(!self::$singleton) {
            parent::__construct($display_name, $members);
            self::$singleton = $this;
        }
    }

    function get_dependents()                     { return $this->dependents; }
    function add_dependent($section_font_size)    { $this->dependents[] = $section_font_size; }
    function get_html() {
        global $font_size_ratio_options;
        $children_html = get_html_dependents($this);
        $children = $this->get_children();
        if ( null != $children) {
            foreach($children as $child) {
                if( $child instanceof Setting && !($child instanceof IComposite ) ) {
                    $child_html = get_setting_row( $child );
                } else {
                    $child_html = $child->get_html();
                }
                $children_html .= $child_html;
            }
        }
        // create an array of ratios to be used by the javascript functions
        $ratio_arr = "var ratioArr = [";
        foreach ($font_size_ratio_options as $ratio) {
            $ratio_arr .= $ratio[1] . ", ";
        }
        $ratio_arr = substr($ratio_arr, 0, -2);
        $ratio_arr .= "];";

        $start = Global_Font_Size_Ratio::$start;
        $size = Global_Font_Size_Ratio::$size;
        $end = $start + $size;

        $the_class = get_class($this);
        $scripts =
"<script type='text/javascript'>
	function updateDependentsOf_{$the_class}() {
		${ratio_arr}

		// get the global font size
		var fontSize = jQuery('.globalFontSize')[0].value;

		// get the global font size ratio
		var fontSizeRatio = jQuery('.globalFontSizeRatio option:selected')[0].value;
		var ratio = ratioArr[fontSizeRatio];

		// calculate the new font sizes and create the new html options
		var options = [''];
        for(i = {$start}; i < {$end}; i++) {
            options.push( Math.round(fontSize * Math.pow(ratio, i)) + 'px' );
        }

		// create the new HTML options
		var optionsHTML = new Option('', 0).outerHTML;
		for (var i = 1; i < options.length; i++ ) {
			var opt = new Option(options[i], i);
			optionsHTML += opt.outerHTML;
    	}

    	// inject the options into the dependent selects
    	var deps = dependentsOf_{$the_class};
    	var depsSize = deps.length;
    	for ( i = 0; i < depsSize; i++ ) {
    		var val = deps[i];
    		// get a hold of the select
    		var selectString = 'select[name=\"ap_options[cs][' + val + ']\"]';
    		var select = jQuery(selectString);

    		// find out what option it is currently selected
    		var cur = select.find('option[selected]').val();

    		// replace the existing options with the new options
    		select.html(optionsHTML);

    		// set the selected value
    		select.val(cur);
    	}
    }
</script>";
        return table($scripts . $children_html, attr_class('form-table'));
    }
}
class Global_Settings extends Main_Tab  {
    function __construct($members=null) {
        if ( null == $members ) {
            $gc1 = new Global_Color('Color 1', '#000000');
            $gc2 = new Global_Color('Color 2', '#444444');
            $gc3 = new Global_Color('Color 3', '#888888');
            $gc4 = new Global_Color('Color 4', '#bbbbbb');
            $gc5 = new Global_Color('Color 5', '#ffffff');
            $members[] = new Global_Color_Group('Global Colors', array($gc1, $gc2, $gc3, $gc4, $gc5));

            $gfs = new Global_Font_Size(10);
            $gfss = new Global_Font_Size_Ratio();
            $members[] = new Global_Font_Size_Group('Global Font Size', array($gfs, $gfss));

            $gf1 = new Global_Font_Family('Font family 1', 0);
            $gf2 = new Global_Font_Family('Font family 2', 0);
            $gf3 = new Global_Font_Family('Font family 3', 0);
            $members[] = new Global_Font_Group('Global Fonts', array($gf1, $gf2, $gf3));

            $members[] = new Option_Group('Logo settings', new Logo_Image_Dropdown());
        }
        parent::__construct('global settings', 'artpress_options', $members);
    }
}

