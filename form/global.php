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
    function get_dependents() {
        return $this->dependents;
    }
    function add_dependent($section_color) {
        $this->dependents[] = $section_color;
    }
    function get_html() {
        global $page_edit_config;
        add_action('admin_footer-' . $page_edit_config, __CLASS__ . "::script");

        $children_html = colgroup(3);        
        get_html_dependents($this);
        $children = $this->get_children();
        $first = true;
        if ( null != $children) {
            foreach($children as $child) {
                $child_name = $child->get_display_name();
                $child_html = $child->get_html();
                $row = ot('tr');
                $row .= td($child_name);
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
        
        return table($children_html, attr_class('form-table'));
    }
    static function script() { ?><script type='text/javascript'>

      function updateSectionColors(section) {
            // get global colors
          var colors = getGlobalOptionsHTML(jQuery('.globalColor'));
          var colorOptions = colors.join();

         // decide if update needs to happen
            // get ahold of one section color
           var sectionDiv = section.nextSibling;
            // get all section colors
           var section_colors = jQuery(sectionDiv).find('.section_color');

           // compare the global color options with a section color's options
           // to see if they are consistent
           var first= section_colors[0];
           if (colorOptions != first.innerHTML) {
               for (var i = 0; i < section_colors.length; i++) {
                   var sc = section_colors[i];
                    // store currently selected option
                   var select_value = sc.value;

                    // replace options
                    sc.innerHTML = colors;
                   if( jQuery(sc).hasClass('section_background_color') ) {
                  sc.innerHTML += outerHTML(new Option(i + '\u00A0\u00A0\u00A0transparent', colors.length));
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
              });
              <?php /* the user will not have selected the last global color text input field
               yet it remains linked to the color picker
               therefore unlink the last color text box by supplying an empty function*/ ?>
        f.linkTo(function(){});
        <?php // add a callback to the color picker to update the dependent section color dropdowns ?>
        //p.bind('mouseleave', updateDependentsOf_<?php echo __CLASS__ ?>);
        }</script><?php
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
    function get_dependents() {
        return $this->dependents;
    }
    function add_dependent($section_font) {
        $this->dependents[] = $section_font;
    }
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
        global $page_edit_config;
        add_action('admin_footer-' . $page_edit_config, __CLASS__ . "::script");

        $children_html = colgroup(2); 
        get_html_dependents($this);
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
        return table($children_html, attr_class('form-table'));
    }

    static function script() { ?><script type='text/javascript'>
        function updateSectionFontFamilies(section) {

           // get the global fonts
           var fonts = jQuery('.globalFont');

           // create the new options
               var spaces = "\u00A0\u00A0\u00A0";
               // add the null option to the array
               var options = outerHTML(new Option('', 0));
               for ( i = 0; i < fonts.size(); i++ ) {
                  var num = fonts[i].value;
                  var selectString = "option[value='" + num + "']";
                  var optHTMLVal = i + spaces + jQuery(fonts[i]).find(selectString).html();
                  var opt = new Option(optHTMLVal, i);
                  options += outerHTML(opt);
               }

            // decide if update needs to happen
                var sectionDiv = section.nextSibling;
                // get all section fonts
                var section_fonts = jQuery(sectionDiv).find('.section_font');

                // compare the global font options with a section font's options
                // to see if they are consistent
                var first= section_fonts[0];
                if (options != first.innerHTML) {
                   for (var i = 0; i < section_fonts.length; i++) {
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
        </script><?php
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
        global $page_edit_config;
        add_action('admin_footer-' . $page_edit_config, __CLASS__ . "::script");
        $children_html = colgroup(2);
        get_html_dependents($this);
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
        return table( $children_html, attr_class('form-table'));
    }

    static function script() {
        global $font_size_ratio_options;
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
        ?><script type='text/javascript'>
            function updateSectionFontSizes(section) {
               <?php echo $ratio_arr ?>

               // get the global font size
               var fontSize = jQuery('.globalFontSize')[0].value;

               // get the global font size ratio
               var fontSizeRatio = jQuery('.globalFontSizeRatio option:selected')[0].value;
               var ratio = ratioArr[fontSizeRatio];

               // calculate the new font sizes and create the new html options
               var options = [''];
                for(i = <?php echo $start ?>; i < <?php echo $end ?>; i++) {
                    options.push( Math.round(fontSize * Math.pow(ratio, i)) + 'px' );
                }

               // create the new HTML options
               var optionsHTML = outerHTML(new Option('', 0));
               for (var i = 1; i < options.length - 1; i++ ) {
                  var opt = new Option(options[i], i);
                  optionsHTML += outerHTML(opt);
               }

               // inject the options into the dependent selects
                // get all section fonts
                var sectionDiv = section.nextSibling;
                var section_font_sizes = jQuery(sectionDiv).find('.section_font_size');

                // compare the global font options with a section font's options
                // to see if they are consistent
                var first= section_font_sizes[0];
                if (optionsHTML != first.innerHTML) {
                   for (var i = 0; i < section_font_sizes.length; i++) {
                       var sfs = section_font_sizes[i];
                        // store currently selected option
                       var select_value = sfs.value;

                        // replace options
                        sfs.innerHTML = optionsHTML;

                        // reset selected option
                        sfs.value = select_value;
					}
                }

            }
		</script><?php
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

            $members[] = new Logo_Image_Dropdown();
        }
        parent::__construct('global settings', 'artpress_options', $members);
    }
    function get_html($pre=null, $post=null) {
        global $page_edit_config;
        add_action('admin_footer-' . $page_edit_config, __CLASS__ . "::script");
        return parent::get_html();
    }
    static function script() {
        ?><script type='text/javascript'>
        function outerHTML(node){
            return node.outerHTML || new XMLSerializer().serializeToString(node);
        }
        function getGlobalOptionsHTML(globalOptions) {
           // add the null option to the array
           globalOptions.splice(0,0,'');
        
           // create the new options
           var spaces = "\u00A0\u00A0\u00A0";
           var options = [outerHTML(new Option('', 0))];
           for ( i = 1; i < globalOptions.size(); i++ ) {
              var val = globalOptions[i].value;
              var opt = new Option(i + spaces + val, i);
              options.push(outerHTML(opt));
           }
           return options;
        }
        function updateDependents(section) {
           if(section) {
               updateSectionColors(section);
        
                // update font families
                updateSectionFontFamilies(section);
        
                // update font sizes
                updateSectionFontSizes(section);
           }
           return;
        }</script><?php
    }
}

