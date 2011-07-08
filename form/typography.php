<?php
require_once 'form.php';

// TEXT
class Text_Transform extends CSS_Dropdown_Input {
    static $options = array('', 'none', 'uppercase', 'lowercase', 'capitalize');
    function __construct($value=0) { 
        parent::__construct('text-transform', 'text transform', $value);
    }    
}
class Text_Align extends CSS_Dropdown_Input {
    static $options = array('', 'left', 'right', 'center', 'justify');
    function __construct($value=0) { 
        parent::__construct('text-align', 'text align', $value);
    }    
}
class Text_Decoration extends CSS_Dropdown_Input {
    static $options = array('', 'none', 'underline', 'overline', 'line-through', 'blink');
    function __construct($value=0) { 
        parent::__construct('text-decoration', 'text decoration', $value);
    }   
}

// FONT
class Global_Font_Family extends CSS_Dropdown_Input {
    private static $global_font_family_instances = array();
    static $options = array('', 
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
    function __construct($display_name, $value=0) { 
        parent::__construct('font-family', $display_name, $value); 
        self::$global_font_family_instances[] = $this;
        $this_class = get_class($this);
        $number_of_global_font_family_instances = sizeof(self::$global_font_family_instances);
        $name = $this_class . '__' . $number_of_global_font_family_instances;
        $this->set_name( $name );
    }
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
    static function get_global_font_family_instances() {
        $instances = static::$global_font_family_instances;
        return $instances;
    }
    function get_name() { return $this->name; }
}
/**
 * Class to represent a selector of one of the preselected font
 * @author jsd
 *
 */
class Section_Font extends CSS_Dropdown_Input {
    static $options;

    function __construct($value=0) {
        parent::__construct('font-family', 'font select', $value); 
        self::$options = Global_Font_Family::get_global_font_family_instances();
    }    
    static function get_options() {
        $list = array();
        foreach (parent::get_options() as $global_font) {
           $v = $global_font->get_value();
           $global_options = Global_Font_Family::get_options();
           $font = $global_options[$v];
           if (is_array( $font ) ) {
               $list[] = $font[0];
           } else {
               $list[] = $font;
           }
        }
        return $list;
    } 
}
class Font_Size extends CSS_Dropdown_Input {
    static $options = array('', '0.8em', '1em', '1.2em', '1.5em', '2em', '3em', '4em');
    function __construct($value=0) { 
        parent::__construct('font-size', 'font size', $value); 
    }    
}
class Font_Style extends CSS_Dropdown_Input {
    static $options = array('', 'normal', 'italic', 'oblique');
    function __construct($value=0) { 
        parent::__construct('font-style', 'font style', $value); 
    }    
}
class Font_Weight extends CSS_Dropdown_Input {
    static $options = array('', 'normal', 'bold', 'bolder', 'lighter', '100', '200', '300', '400', '500', '600', '700', '800', '900');  
    function __construct($value=0) { 
        parent::__construct('font-weight', 'font weight', $value); 
    }    
}
class Typography_Tab extends Sub_Tab {
    function __construct($display_name, $members=null, $html_id=null) {
        if ( null == $members ) { 
            $members[] = new Section_Foreground_Color();
            $members[] = new Section_Background_Color();        
            $members[] = new Section_Font();
            $members[] = new Font_Size();
            $members[] = new Font_Style();
            $members[] = new Font_Weight();
            $members[] = new Text_Align();
            $members[] = new Text_Decoration();
            $members[] = new Text_Transform();
        }
        parent::__construct($display_name, $members);
    }    
}
