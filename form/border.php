<?php
// BORDER

class Border_Color extends Section_Color {
    function __construct($value=0) {
        parent::__construct('border-color', 'border color', $value);
    }
}
$border_style_options = array('', 'none', 'hidden', 'dotted', 'dashed', 'solid', 'double', 'groove', 'ridge', 'inset', 'outset', 'inherit');
class Border_Style extends CSS_Dropdown_Input {
    function __construct($value=0) {
        global $border_style_options;
        parent::__construct('border-style', 'border style', $border_style_options, $value);
    }
}
class Border_Width extends CSS_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('border-width', 'border width', $value);
    }
}
// TOP
class Border_Top_Color extends Section_Color {
    function __construct($value=0) {
        parent::__construct('border-top-color', 'top border color', $value);
    }
}
$border_style_options = array('', 'none', 'hidden', 'dotted', 'dashed', 'solid', 'double', 'groove', 'ridge', 'inset', 'outset', 'inherit');
class Border_Top_Style extends CSS_Dropdown_Input {
    function __construct($value=0) {
        global $border_style_options;
        parent::__construct('border-top-style', 'top border style', $border_style_options, $value);
    }
}
class Border_Top_Width extends CSS_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('border-top-width', 'top border width', $value);
    }
}

// BOTTOM
class Border_Bottom_Color extends Section_Color {
    function __construct($value=0) {
        parent::__construct('border-bottom-color', 'bottom border color', $value);
    }
}
class Border_Bottom_Style extends CSS_Dropdown_Input {
    function __construct($value=0) {
        global $border_style_options;
        parent::__construct('border-bottom-style', 'bottom border style', $border_style_options, $value);
    }
}
class Border_Bottom_Width extends CSS_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('border-bottom-width', 'bottom border width', $value);
    }
}

// LEFT
class Border_Left_Color extends Section_Color {
    function __construct($value=0) {
        parent::__construct('border-left-color', 'left border color', $value);
    }
}
class Border_Left_Style extends CSS_Dropdown_Input {
    static $options = array('', 'none', 'hidden', 'dotted', 'dashed', 'solid', 'double', 'groove', 'ridge', 'inset', 'outset', 'inherit');
    function __construct($value=0) {
        global $border_style_options;
        parent::__construct('border-left-style', 'left border style', $border_style_options, $value);
    }
}
class Border_Left_Width extends CSS_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('border-left-width', 'left border width', $value);
    }
}

// RIGHT
class Border_Right_Color extends Section_Color {
    function __construct($value=0) {
        parent::__construct('border-right-color', 'right border color', $value);
    }
}
class Border_Right_Style extends CSS_Dropdown_Input {
    static $options = array('', 'none', 'hidden', 'dotted', 'dashed', 'solid', 'double', 'groove', 'ridge', 'inset', 'outset', 'inherit');
    function __construct($value=0) {
        global $border_style_options;
        parent::__construct('border-right-style', 'right border style', $border_style_options, $value);
    }
}
class Border_Right_Width extends CSS_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('border-right-width', 'right border width', $value);
    }
}

class Border_Tab extends Sub_Tab {
    function __construct($display_name, $members=null) {
        if ( null == $members ) {
            $members = array(
                new Option_Group('',
                    array(
                        new Option_Row_Group('all borders',
                            array(
                                new Border_Color(),
                                new Border_Style(),
                                new Border_Width()
                            )
                        ),
                        new Option_Row_Group('top border',
                            array(
                                new Border_Top_Color(),
                                new Border_Top_Style(),
                                new Border_Top_Width()
                            )
                        ),
                        new Option_Row_Group('bottom border',
                            array(
                                new Border_Bottom_Color(),
                                new Border_Bottom_Style(),
                                new Border_Bottom_Width()
                            )
                        ),
                        new Option_Row_Group('left border',
                            array(
                                new Border_Left_Color(),
                                new Border_Left_Style(),
                                new Border_Left_Width()
                            )
                        ),
                        new Option_Row_Group('right border',
                            array(
                                new Border_Right_Color(),
                                new Border_Right_Style(),
                                new Border_Right_Width()
                            )
                        )
                    )
                )
            );
        }
        parent::__construct('border', $members);
    }
}