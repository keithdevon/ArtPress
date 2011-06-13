<?php
require_once 'form.php';

class Text_Shadow_Horizontal extends CSS_Size_Text_Input {
    function __construct($id, $value) {
        parent::__construct($id, 'text-shadow', 'horizontal text shadow', $value);
    }
}
class Text_Shadow_Vertical extends CSS_Size_Text_Input {
    function __construct($id, $value) {
        parent::__construct($id, 'text-shadow', 'vertical text shadow', $value);
    }
}
class Text_Shadow_Blur_Radius extends CSS_Size_Text_Input {
    function __construct($id, $value) {
        parent::__construct($id, 'text-shadow', 'text shadow blur radius', $value);
    }
}
class Text_Shadow_Color extends CSS_Dropdown_Input {
    static $options;
    function __construct($id, $value) {
        parent::__construct($id, 'color', 'text shadow color select', $value); 
        self::set_options( Global_Color::get_dropdown_color_options() );
    }    
}

class Box_Shadow_Horizontal extends CSS_Size_Text_Input {
    function __construct($id, $value) {
        parent::__construct($id, 'box-shadow', 'horizontal box shadow', $value);
    }
}
class Box_Shadow_Vertical extends CSS_Size_Text_Input {
    function __construct($id, $value) {
        parent::__construct($id, 'box-shadow', 'vertical box shadow', $value);
    }
}
class Box_Shadow_Blur_Radius extends CSS_Size_Text_Input {
    function __construct($id, $value) {
        parent::__construct($id, 'box-shadow', 'box shadow blur radius', $value);
    }
}
class Box_Shadow_Color extends CSS_Dropdown_Input {
    static $options;
    function __construct($id, $value) {
        parent::__construct($id, 'color', 'box shadow color select', $value); 
        self::set_options( Global_Color::get_dropdown_color_options() );
    }    
}

class Border_Radius extends CSS_Size_Text_Input {
    function __construct($id, $value) {
        parent::__construct($id, 'border-radius', 'border-radius', $value);
    }
}