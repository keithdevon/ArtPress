<?php
require_once 'form.php';

/** 
 * 
 * Represents a global color setting 
 * @author jsd
 *
 */
class Global_Color extends CSS_Text_Input {
    function __construct($id, $name, $parent, $value) {
        parent::__construct($id, 'color', $name, $parent, $value);        
    }

    static function is_valid($value) {
        return preg_match('/^#[a-f0-9]{6}$/i', $value ); 
    }
}
/**
 * Class to chose one of the preselected colors as the foreground color
 * @author jsd
 *
 */
class Section_Color extends CSS_Dropdown_Input {
    static $options;
    function __construct($id, $parent, $value, $group_arr) {
        parent::__construct($id, 'color', 'color select', $parent, $value); 
        self::set_options($group_arr);
    }    
}
/**
 * Class to chose one of the preselected colors as the background color
 * @author jsd
 *
 */
class Section_Background_Color extends CSS_Dropdown_Input {
    static $options;
    function __construct($id, $parent, $value, $group_arr) {
        parent::__construct($id, 'background-color', 'background color select', $parent, $value); 
        self::set_options($group_arr);
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