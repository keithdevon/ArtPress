<?php
require_once 'form.php';

// TEXT
$text_transform_options = array('', 'none', 'uppercase', 'lowercase', 'capitalize');
class Text_Transform extends CSS_Dropdown_Input {
    function __construct($value=0) { 
        global $text_transform_options;
        parent::__construct('text-transform', 'text transform', $text_transform_options, $value);
    }    
}
$text_align_options = array('', 'left', 'right', 'center', 'justify'); 
class Text_Align extends CSS_Dropdown_Input {
    function __construct($value=0) {
        global $text_align_options;
        parent::__construct('text-align', 'text align', $text_align_options, $value);
    }    
}
$text_decoration_options = array('', 'none', 'underline', 'overline', 'line-through', 'blink');
class Text_Decoration extends CSS_Dropdown_Input {
    function __construct($value=0) { 
        global $text_decoration_options; 
        parent::__construct('text-decoration', 'text decoration', $text_decoration_options, $value);
    }   
}
class Letter_Spacing extends CSS_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('letter-spacing', 'letter spacing', $value);
    }
}
class Global_Font_Size extends Setting_Number {
    static $global_font_size_instance;
    function __construct($value) {
        parent::__construct('base-font-size', 'Base font size', $value);
        self::$global_font_size_instance = $this;
    }
    static function get_global_font_size() {
        $instance = self::$global_font_size_instance;
        $size = $instance->get_value();
        return $size;
    }
    function get_html() {
        return parent::get_html(attr_class('globalFontSize') . attr_on_change("updateDependentsOf_Global_Font_Size_Group()"));
    }
    static function get_global_font_size_instance() {
        return self::$global_font_size_instance;   
    }
}
$font_size_ratio_options = array(array('golden', 1.618), array('musical fifths', 1.5), array('musical forths', 1.4));
class Global_Font_Size_Ratio extends Setting_Dropdown {
    static $start = -1;
    static $size   = 8;   
    static $global_font_size_ratio_instance;
   
    function __construct($value=0) { 
        global $font_size_ratio_options;
        parent::__construct('font-size-ratio', 'Font size ratio', $font_size_ratio_options, $value);
        self::$global_font_size_ratio_instance = $this;
    }

    static function get_global_font_size_ratio() {
        $instance = self::$global_font_size_ratio_instance;
        $value = $instance->get_value();
        $options = $instance->get_opts();
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
    function get_html() {
        return parent::get_html(attr_class('globalFontSizeRatio') . attr_on_change("updateDependentsOf_Global_Font_Size_Group()"));
    }
}

class Section_Font_Size extends CSS_Dropdown_Input {
    function __construct($value=0) {
        parent::__construct('font-size', 'font size', $value);
        Global_Font_Size_Group::$singleton->add_dependent($this); 
    }
    function get_opts() {
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
$font_family_options = array('', 
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
// FONT
class Global_Font_Family extends Setting_Dropdown {
    private static $global_font_family_instances = array();

    function __construct($display_name, $value=0) {
        global $font_family_options;
        parent::__construct('font-family', $display_name, $font_family_options, $value); 
        self::$global_font_family_instances[] = $this;
    }

    static function get_global_font_family_options() {
        $list = array('');
        foreach (self::$global_font_family_instances as $global_font) {
           $v = $global_font->get_value();
           $global_options = $global_font->get_opts();
           $font = $global_options[$v];
           if (is_array( $font ) ) {
               $list[] = $font[0];
           } else {
               $list[] = $font;
           }
        }
        return $list;    
    }
    function get_html() {
        return parent::get_html(attr_class('globalFont') . attr_on_change("updateDependentsOf_Global_Font_Group()"));
    }
}
/**
 * Class to represent a selector of one of the preselected font
 * @author jsd
 *
 */
class Section_Font extends CSS_Dropdown_Input implements ISetting_Depends_On_Global_Setting {

    function __construct($value=0) {
        parent::__construct('font-family', 'font family', null, $value);
        Global_Font_Group::$singleton->add_dependent($this);
    }    

    function get_opts() {
        return Global_Font_Family::get_global_font_family_options();
    }
    function get_html() {
        return parent::get_html();
    }
}
$font_style_options = array('', 'normal', 'italic', 'oblique');
class Font_Style extends CSS_Dropdown_Input {
    function __construct($value=0) {
        global $font_style_options;
        parent::__construct('font-style', 'font style', $font_style_options, $value); 
    }    
}
$font_weight_options = array('', 'normal', 'bold', 'bolder', 'lighter', '100', '200', '300', '400', '500', '600', '700', '800', '900');
class Font_Weight extends CSS_Dropdown_Input {
    function __construct($value=0) { 
        global $font_weight_options;
        parent::__construct('font-weight', 'font weight', $font_weight_options, $value); 
    }    
}
class Typography_Tab extends Sub_Tab {
    function __construct($display_name, $members=null, $html_id=null) {
        if ( null == $members ) { 
            $members[] = new Section_Foreground_Color();        
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
