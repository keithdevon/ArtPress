<?php
require_once 'form.php';
require_once 'color.php';

class Text_Shadow_Horizontal extends Setting_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('horizontal', 'horizontal text shadow', $value);
    }
    function get_css() {
        return parent::get_css();
    }
}
class Text_Shadow_Vertical extends Setting_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('vertical', 'vertical text shadow', $value);
    }
}
class Text_Shadow_Blur_Radius extends Setting_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('blur_radius', 'text shadow blur radius', $value);
    }
}
class Text_Shadow_Color extends Setting_Color {
    function __construct($value=0) {
        parent::__construct($value);
    }
}
class Text_Shadow extends CSS_Composite {
    function __construct() {
        parent::__construct('text shadow', 'text-shadow',
            array(
                new Text_Shadow_Horizontal(),
                new Text_Shadow_Vertical(),
                new Text_Shadow_Blur_Radius(),
                new Text_Shadow_Color()
            )
        );
    }
    function validate($value) {
        // TODO validate this
    }
    function get_css() {
        return parent::get_css();
    }
}
class Box_Shadow_Horizontal extends Setting_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('horizontal', 'horizontal box shadow', $value);
    }
}
class Box_Shadow_Vertical extends Setting_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('vertical', 'vertical box shadow', $value);
    }
}
class Box_Shadow_Blur_Radius extends Setting_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('blur-radius', 'box shadow blur radius', $value);
    }
}
class Box_Shadow_Color extends Setting_Color {
    function __construct($value=0) {
        parent::__construct($value);
    }
}
class Box_Shadow extends CSS_Composite {
    function __construct() {
        parent::__construct('box shadow', 'box-shadow',
            array(
                new Box_Shadow_Horizontal(),
                new Box_Shadow_Vertical(),
                new Box_Shadow_Blur_Radius(),
                new Box_Shadow_Color()
            )
        );
    }
    function validate($value) {

    }
}
class Border_Radius extends CSS_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('border-radius', 'border radius', $value);
    }
    function get_css_declaration() {
        return parent::get_css_declaration();
    }
}


class Effect_Tab extends Sub_Tab {
    function __construct($display_name, $members=null) {
        if ( null == $members ) {
            $members[]  = new Border_Radius();
            $members[] = new Text_Shadow();
            $members[] = new Box_Shadow();
        }
        parent::__construct($display_name, $members);
    }
}