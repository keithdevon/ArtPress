<?php
require_once 'form.php';


//class Text_Align extends CSS_Dropdown_Input {
//    static private $options = array('left', 'right', 'center', 'justify');
//    function __construct($id, $value) { 
//        parent::__construct($id, 'text-align', 'text align', $value);
//        self::set_options( self::$options );
//    }    
//}
// FONT
class Global_Font_Family extends CSS_Dropdown_Input {
    private static $global_font_family_instances = array();
    private static $options = array(
                            array('Arial, “Helvetica Neue”, Helvetica, sans-serif','paragraph or title'),
                        	'Cambria, Georgia, Times, “Times New Roman”, serif',
                        	'“Century Gothic”, “Apple Gothic”, sans-serif',
                        	'Consolas, “Lucida Console”, Monaco, monospace',
                        	'“Copperplate Light”, “Copperplate Gothic Light”, serif',
                        	'“Courier New”, Courier, monospace',
                        	'“Franklin Gothic Medium”, “Arial Narrow Bold”, Arial, sans-serif',
                        	'Futura, “Century Gothic”, AppleGothic, sans-serif',
                        	'Impact, Haettenschweiler, “Arial Narrow Bold”, sans-serif',
                        	'“Lucida Sans”, “Lucida Grande”, “Lucida Sans Unicode”, sans-serif',
                        	'Times, “Times New Roman”, Georgia, serif', 
                        	array('Baskerville, “Times New Roman”, Times, serif','paragraph'),
                        	'Garamond, “Hoefler Text”, Times New Roman, Times, serif',
                        	'Geneva, “Lucida Sans”, “Lucida Grande”, “Lucida Sans Unicode”, Verdana, sans-serif',
                        	'Georgia, Palatino,” Palatino Linotype”, Times, “Times New Roman”, serif',
                        	'“Gill Sans”, Calibri, “Trebuchet MS”, sans-serif',
                        	'“Helvetica Neue”, Arial, Helvetica, sans-serif',
                        	'Palatino, “Palatino Linotype”, Georgia, Times, “Times New Roman”, serif',
                        	'Tahoma, Geneva, Verdana',
                        	'“Trebuchet MS”, “Lucida Sans Unicode”, “Lucida Grande”,” Lucida Sans”, Arial, sans-serif',
                        	'Verdana, Geneva, Tahoma, sans-serif',
                        	array('Baskerville, Times, “Times New Roman”, serif','title'),
                        	'Garamond, “Hoefler Text”, Palatino, “Palatino Linotype”, serif',
                        	'Geneva, Verdana, “Lucida Sans”, “Lucida Grande”, “Lucida Sans Unicode”, sans-serif',
                        	'Georgia, Times, “Times New Roman”, serif',
                        	'“Gill Sans”, “Trebuchet MS”, Calibri, sans-serif',
                        	'Helvetica, “Helvetica Neue”, Arial, sans-serif',
                        	'Palatino, “Palatino Linotype”, “Hoefler Text”, Times, “Times New Roman”, serif',
                        	'Tahoma, Verdana, Geneva',
                        	'“Trebuchet MS”, Tahoma, Arial, sans-serif',
                        	'Verdana, Tahoma, Geneva, sans-serif'
                    	); 
    function __construct($id, $name, $value=0) { 
        parent::__construct($id, 'font-family', $name, $value); 
        self::set_options( self::$options );
        self::$global_font_family_instances[] = $this;
    }
    //static function get_static_options() { return self::$options; }
    static function get_dropdown_font_family_options() {
        $list = array();
        foreach (self::$global_font_family_instances as $global_font) {
           $v = $global_font->get_value();
           $font = self::$options[$v];
           if (is_array( $font ) ) {
               $list[] = $font[0];
           } else {
               $list[] = $font;
           }
        }
        return $list;
    } 
}
//class Global_Font_Family_Settings {
//    static $gf1;
//    static $gf2;
//    static $gf3;
//
//    static $gfgrp;
//    
//    static function initialize() {
//        self::$gf1 = new Global_Font_Family('gsgff1', 'Font 1', 0);
//        self::$gf2 = new Global_Font_Family('gsgff2', 'Font 2', 1);
//        self::$gf3 = new Global_Font_Family('gsgff3', 'Font 3', 2);
//        self::$gfgrp = new Option_Group('gfgrp', 'Global Fonts', array(self::$gf1, self::$gf2, self::$gf3));
//    }
//}
//Global_Font_Family_Settings::initialize();
/**
 * Class to represent a selector of one of the preselected font
 * @author jsd
 *
 */
class Section_Font extends CSS_Dropdown_Input {
    static $options;
    function get_value() { // TODO not sure if this is correct
        $fonts = Global_Font_Family::get_options();
        $value = parent::get_value();
        $font = $fonts[$value];
        return $font;
    }
    function __construct($id, $value=0) {
        parent::__construct($id, 'font-family', 'font select', $value); 
        self::set_options( Global_Font_Family::get_dropdown_font_family_options());
    }    
}
class Font_Style extends CSS_Dropdown_Input {
    static $options = array('normal', 'italic', 'oblique');
    function __construct($id, $value=0) { 
        parent::__construct($id, 'font-style', 'font style', $value); 
        self::set_options( self::$options );
    }    
}
class Font_Weight extends CSS_Dropdown_Input {
    static $options = array('normal', 'bold', 'bolder', 'lighter', '100', '200', '300', '400', '500', '600', '700', '800', '900');  
    function __construct($id, $value=0) { 
        parent::__construct($id, 'font-weight', 'font weight', $value); 
        self::set_options( self::$options );
    }    
}