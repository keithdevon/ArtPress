<?php
require_once 'form.php';

/** 
 * 
 * Represents a global color setting 
 * @author jsd
 *
 */
class Global_Color extends CSS_Text_Input {
    private static $global_color_instances = array();
    
    function __construct($display_name, $value) {
        parent::__construct('color', $display_name, $value);
        self::$global_color_instances[] = $this;   
        $this_class = get_class($this);
        $this->set_name($this_class . '__' . sizeof(self::$global_color_instances));
    }
    static function is_valid($value) {
        return preg_match('/^#[a-f0-9]{6}$/i', $value ); 
    }
    static function get_dropdown_color_options() {
        $options = array();
        foreach (self::$global_color_instances as $color) {
            $v = $color->get_value();
            $options[] = $v;
        }
        return $options;       
    }
}
class Section_Color extends CSS_Dropdown_Input {
    static $options;
    function __construct($value=0) {
        parent::__construct('color', 'color select', $value); 
        self::set_options( Global_Color::get_dropdown_color_options() );
    }    
}
/**
 * Class to chose one of the preselected colors as the background color
 * @author jsd
 *
 */
class Section_Background_Color extends CSS_Dropdown_Input {
    static $options;
    function __construct($value=0) {
        parent::__construct('background-color', 'background color select', $value); 
        self::set_options(Global_Color::get_dropdown_color_options());
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