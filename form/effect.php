<?php
require_once 'form.php';
/*case 'text-shadow':
    $output.= ht_form_text_field('text shadow horizontal', 
                                    "[section_settings][{$group}][{$css_attr}][value][0]", 
                                    $css_attr_arr['value'][0], 
                                    "blah");
    
    $output.= ht_form_text_field('text shadow vertical', 
                                    "[section_settings][{$group}][{$css_attr}][value][1]", 
                                    $css_attr_arr['value'][1], 
                                    "blah");
    $output.= ht_form_text_field('text shadow blur', 
                                    "[section_settings][{$group}][{$css_attr}][value][2]", 
                                    $css_attr_arr['value'][2], 
                                    "blah");
    
    $output.= ht_form_text_field('text shadow color', 
                                    "[section_settings][{$group}][{$css_attr}][value][3]", 
                                    $css_attr_arr['value'][3], 
                                    "blah");
                                    */


class Text_Shadow_Horizontal extends CSS_Size_Text_Input {
    function __construct($parent, $value) {
        parent::__construct('text-shadow', 'horizontal text shadow', $parent, $value);
    }
}

class Text_Shadow_Vertical extends CSS_Size_Text_Input {
    function __construct($parent, $value) {
        parent::__construct('text-shadow', 'vertical text shadow', $parent, $value);
    }
}

class Text_Shadow_Color extends Section_Color {


}