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
class Letter_Spacing extends CSS_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('letter-spacing', 'letter spacing', $value);
    }
}

class Global_Font_Size extends Number_Setting {
    static $global_font_size_instance;
    function __construct($value) {
        parent::__construct('Base font size', $value);
        self::$global_font_size_instance = $this;
    }
    static function get_global_font_size() {
        $instance = self::$global_font_size_instance;
        $size = $instance->get_value();
        return $size;
    }
    function get_html() {
        return parent::get_html();
    }
    static function get_global_font_size_instance() {
        return self::$global_font_size_instance;   
    }
}
class Global_Font_Size_Ratio extends CSS_Dropdown_Input {
    static $start = -1;
    static $size   = 8;   
    static $global_font_size_ratio_instance;
    static $options = array(array('golden', 1.618), array('musical fifths', 1.5), array('musical forths', 1.4));
    
    function __construct($value=0) { 
        parent::__construct('font-size-ratio', 'Font size ratio', $value);
        self::$global_font_size_ratio_instance = $this;
    }

    static function get_global_font_size_ratio() {
        $instance = self::$global_font_size_ratio_instance;
        $value = $instance->get_value();
        $options = self::$options;
        $ratio = $options[$value][1];
        return $ratio;
    }
    static function create_scale() {
        $global_size = Global_Font_Size::get_global_font_size();
        $ratio = Global_Font_Size_Ratio::get_global_font_size_ratio();
        
        $options[0] = '';
        $end = self::$start + self::$size;
        
        for($i = self::$start; $i < $end; $i++) {
            $options[] = round($global_size * pow($ratio, $i), 0) . 'px';  
        }
        
        return $options;
    }
    /** 
     * @example to return size below base font size, set $plus_n equal to -1
     * @example to return the base font size, set $plus_n equal to 0
     * @example to return the size two above the base font size, set $plus_n equal to 2
     * */
    static function get_font_size($plus_n) {
        $n = $plus_n - self::$start;
        $scale = self::create_scale();
        return $scale[$n];
    }
}

class Section_Font_Size extends CSS_Dropdown_Input {
 
    function __construct($value=0) {
        parent::__construct('font-size', 'font size', $value);
    }
    static function get_options() {
        $scale = Global_Font_Size_Ratio::create_scale();
        $scale_size = sizeof($scale);
        return array_slice($scale, 0, $scale_size -1 );
    }

    function get_css_value() {
        return parent::get_css_value();
    }
    function get_css_declaration() {
        $value = $this->get_value();
        if ($value) {
            $decs  = dec($this->get_css_property(), $this->get_css_value());
            
            $scale = Global_Font_Size_Ratio::create_scale();
            $decs .= dec('line-height', $scale[$value + 1]);
            return $decs;
        } else return '';        
    }
}

// FONT
class Global_Font_Family extends CSS_Dropdown_Input  {
    private static $global_font_family_instances = array();
    static $options = array('', 
                            array('Arial, “Helvetica Neue”, Helvetica, sans-serif','paragraph or title fonts'),
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
                        	array('Baskerville, “Times New Roman”, Times, serif','paragraph fonts'),
                        	'Garamond, “Hoefler Text”, Times New Roman, Times, serif',
                        	'Geneva, “Lucida Sans”, “Lucida Grande”, “Lucida Sans Unicode”, Verdana, sans-serif',
                        	'Georgia, Palatino,” Palatino Linotype”, Times, “Times New Roman”, serif',
                        	'“Gill Sans”, Calibri, “Trebuchet MS”, sans-serif',
                        	'“Helvetica Neue”, Arial, Helvetica, sans-serif',
                        	'Palatino, “Palatino Linotype”, Georgia, Times, “Times New Roman”, serif',
                        	'Tahoma, Geneva, Verdana',
                        	'“Trebuchet MS”, “Lucida Sans Unicode”, “Lucida Grande”,” Lucida Sans”, Arial, sans-serif',
                        	'Verdana, Geneva, Tahoma, sans-serif',
                        	array('Baskerville, Times, “Times New Roman”, serif','title fonts'),
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
    }
    static function get_global_font_family_instances() {
        $instances = static::$global_font_family_instances;
        return $instances;
    }
}
/**
 * Class to represent a selector of one of the preselected font
 * @author jsd
 *
 */
class Section_Font extends CSS_Dropdown_Input implements ISetting_Depends_On_Global_Setting {
    static $options;

    function __construct($value=0) {
        parent::__construct('font-family', 'font family', $value); 
        self::$options = Global_Font_Family::get_global_font_family_instances();
    }    
    static function get_options() {
        $list = array('');
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
            $members[] = new Section_Font_Size();
            $members[] = new Font_Style();
            $members[] = new Font_Weight();
            $members[] = new Text_Align();
            $members[] = new Text_Decoration();
            $members[] = new Text_Transform();
            $members[] = new Letter_Spacing();
        }
        parent::__construct($display_name, new Option_Group('', $members));
    }    
}
