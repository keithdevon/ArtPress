<?php
require_once 'form.php';

/**
 *
 * Represents a global color setting
 * @author jsd
 *
 */
abstract class Global_Color extends Setting_Text {
    private static $global_color_instances = array();

    function __construct($name, $display_name, $value='') {
        parent::__construct($name, $display_name, $value);
        self::$global_color_instances[] = $this;
    }
    function validate($value) {
        return preg_match('/^#[a-f0-9]{6}$/i', $value);
    }
    static function get_dropdown_color_options() {
        $options = array('');
        foreach (self::$global_color_instances as $color) {
            $v = $color->get_value();
            $options[] = $v;
        }
        return $options;
    }
    function get_html() {
        return parent::get_html( attr_class('colorwell globalColor globalSetting') );
    }
}

class Global_Color_1 extends Global_Color { 
    function __construct( $value='#000000' ) { 
        parent::__construct('color', 'color 1', $value); 
    } 
}

class Global_Color_2 extends Global_Color { 
    function __construct( $value='#444444' ) {
        parent::__construct('color__2', 'color 2', $value);
    }
}

class Global_Color_3 extends Global_Color { 
    function __construct( $value='#888888' ) {
        parent::__construct('color__3', 'color 3', $value);
    }
}

class Global_Color_4 extends Global_Color { 
    function __construct( $value='#bbbbbb' ) {
        parent::__construct('color__4', 'color 4', $value);
    }
}

class Global_Color_5 extends Global_Color { 
    function __construct( $value='#ffffff' ) {
        parent::__construct('color__5', 'color 5', $value);
    }
}


function get_css_dropdown_value($obj) {
    $options = $obj->get_opts();
    $value = $obj->get_value();
    $css_value = $options[$value];
    return $css_value;
}
abstract class Section_Color extends CSS_Dropdown_Input implements ISetting_Depends_On_Global_Setting {
    function __construct($css_property, $display_name, $value=0) {
        parent::__construct($css_property, $display_name, null, $value);
        Global_Color_Group::$singleton->add_dependent($this);
    }
    function get_opts() {
        return Global_Color::get_dropdown_color_options();
    }
    function get_css_value() {
        return get_css_dropdown_value($this);
    }
    function get_html() {
        return parent::get_html(attr_class('section_color'));
    }
    /** 
     * Hacky method to allow me to call a super super method.
     * */
    function get_parent_html($attributes=null) {
        return parent::get_html($attributes);
    }
}
class Setting_Color extends Setting_Dropdown implements ISetting_Depends_On_Global_Setting {
    function __construct($value=0) {
        parent::__construct('color', 'color', null, $value);
        Global_Color_Group::$singleton->add_dependent($this);
    }
    function get_opts() {
        return Global_Color::get_dropdown_color_options();
    }
    function get_css_value() {
        return get_css_dropdown_value($this);
    }
}
class Section_Foreground_Color extends Section_Color {
    function __construct($value=0) {
        parent::__construct('color', 'color', $value);
    }
}
/**
 * Class to chose one of the preselected colors as the background color
 * @author jsd
 *
 */
class Section_Background_Color extends Section_Color {
    function __construct($value=0) {
        parent::__construct('background-color', 'background color', $value);
    }
    function get_opts() {
        $options = parent::get_opts();
        $options[] = 'transparent';
        return $options;
    }
    function get_html() {
        return parent::get_parent_html(attr_class('section_color section_background_color'));
    }
}


// TEST DATA TODO remove!

//$c1 = new Global_Color('color 1', null, "#111111");
//echo $c1->get_html();
//$c2 = new Global_Color('color 2', null, "#222222");
//$c3 = new Global_Color('color 3', null, "#333333");
//$gr = new Option_Group('Colors', null, $c1, $c2, $c3);
//echo $c1->get_html();
//echo "\n"; var_dump($gr);
//echo "\n"; var_dump($gr->get_options());
//$cs = new Section_Color(null, 0, $gr->get_options());
//echo  "\n" . $cs->get_html();
//$br = new Background_Repeat(null, 2);
//echo "\n" . $br->get_html();