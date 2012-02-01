<?php
class Global_Color_Group extends Option_Group implements IHas_Dependents {
    static $singleton;
    private $dependents = array();

    function __construct($display_name, $members=null) {
        //$self_singleton = self::$singleton;
        //if( !$self_singleton ) {
        Global_Color::reset_static_instances();
        if($members == null) {
            $members = array(
                new Global_Color_1(),
                new Global_Color_2(),
                new Global_Color_3(),
                new Global_Color_4(),
                new Global_Color_5());
        }
        parent::__construct($display_name, $members);
        self::$singleton = $this;
        //}
    }
    function get_dependents() {
        return $this->dependents;
    }
    function add_dependent($section_color) {
        $this->dependents[] = $section_color;
    }
    function get_html() {
        global $page_edit_config;

        $children_html = colgroup(3);        
        //get_html_dependents($this);
        $children = self::$singleton->get_children();
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
}
class Global_Font_Group extends Option_Group {
    static $singleton;
    private $dependents = array();

    function __construct($display_name, $members=array()) {
        Global_Font_Family::reset_static_instances();
        $gf1 = new Global_Font_Family_1(0);
        $gf2 = new Global_Font_Family_2(0);
        $gf3 = new Global_Font_Family_3(0);
        parent::__construct($display_name, array($gf1, $gf2, $gf3));
        self::$singleton = $this;
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

        $children_html = colgroup(3); 
        //get_html_dependents($this);
        $children = self::$singleton->get_children();
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
}



class Global_Font_Size_Group extends Option_Group {
    static $singleton;
    private $dependents = array();

    function __construct($display_name, $members=array()) {
        $self_singleton = self::$singleton; 
        if( !$self_singleton ) { // FIXME
            parent::__construct($display_name, $members);
            self::$singleton = $this;

        }
    }

    function get_dependents()                     { return $this->dependents; }
    function add_dependent($section_font_size)    { $this->dependents[] = $section_font_size; }
    function get_html() {
        global $page_edit_config;
        add_action('admin_footer-' . $page_edit_config, __CLASS__ . "::script");
        $children_html = colgroup(3);
        //get_html_dependents($this);
        $children = self::$singleton->get_children();
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
			jQuery(sfs).html(optionsHTML);
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
            $members[] = new Global_Color_Group('Global Colors');

            $gfs = new Global_Font_Size(10);
            $gfss = new Global_Font_Size_Ratio();
            $members[] = new Global_Font_Size_Group('Global Font Size', array($gfs, $gfss));

            $members[] = new Global_Font_Group('Global Fonts', null);

            $members[] = new Logo_Image_Dropdown();
        }
        parent::__construct('palette', 'artpress_options', $members);
    }
    function get_html($pre=null, $post=null) {
        global $page_edit_config;
        return parent::get_html();
    }
}

