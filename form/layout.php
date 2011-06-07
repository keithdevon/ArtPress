<?php
require_once 'form.php';

// BORDER
class Border_Style extends CSS_Dropdown_Input {
    static $options = array('none', 'hidden', 'dotted', 'dashed', 'solid', 'double', 'groove', 'ridge', 'inset', 'outset', 'inherit');
    function __construct($id, $parent, $value) { 
        parent::__construct($id, 'border-style', 'border style', $parent, $value);
        self::set_options( self::$options );
    }    
}
class Border_Width extends CSS_Size_Text_Input {
    function __construct($id, $parent, $value) {
        parent::__construct($id, 'border-width', 'border width', $parent, $value);
    }
}
// MARGIN
class Margin_Top extends CSS_Size_Text_Input {
    function __construct($id, $parent, $value) {
        parent::__construct($id, 'margin-top', 'top margin width', $parent, $value);
    }
}
class Margin_Bottom extends CSS_Size_Text_Input {
    function __construct($id, $parent, $value) {
        parent::__construct($id, 'margin-bottom', 'bottom margin width', $parent, $value);
    }
}
class Margin_Right extends CSS_Size_Text_Input {
    function __construct($id, $name, $parent, $value) {
        parent::__construct($id, 'margin-right', 'right margin width', $parent, $value);
    }
}
class Margin_Left extends CSS_Size_Text_Input {
    function __construct($id, $parent, $value) {
        parent::__construct($id, 'margin-top', 'left margin width', $parent, $value);
    }
}


// PADDING
class Padding_Top extends CSS_Size_Text_Input {
    function __construct($id, $parent, $value) {
        parent::__construct($id, 'padding-top', 'top padding width', $parent, $value);
    }
}
class Padding_Bottom extends CSS_Size_Text_Input {
    function __construct($id, $parent, $value) {
        parent::__construct($id, 'padding-bottom', 'bottom padding width', $parent, $value);
    }
}
class Padding_Right extends CSS_Size_Text_Input {
    function __construct($id, $name, $parent, $value) {
        parent::__construct($id, 'padding-right', 'right padding width', $parent, $value);
    }
}
class Padding_Left extends CSS_Size_Text_Input {
    function __construct($id, $parent, $value) {
        parent::__construct($id, 'padding-top', 'left padding width', $parent, $value);
    }
}

// DISPLAY
class Display extends CSS_Dropdown_Input {
    static $options = array('inherit', 'none', 'block', 'inline', 'inline-block', 'inline-table', 'list-item', 
        						'run-in', 'table', 'table-caption', 'table-cell', 'table-column', 
        						'table-column-group', 'table-footer-group', 'table-header-group', 
        						'table-row', 'table-row-group');
    function __construct($id, $parent, $value) { 
        parent::__construct($id, 'display', 'display mode', $parent, $value);
        self::set_options( self::$options );
    }    
}

//$mt = new Margin_Top(null, "1em");
//echo "\n" . $mt->get_html();
//echo "\n" . $mt::is_valid($mt->get_value());
//$mt = new Margin_Top(null, "3px");
//echo "\n" . $mt->get_html();
//echo "\n" . $mt::is_valid($mt->get_value()); 
//$mt = new Margin_Top(null, "4");
//echo "\n" . $mt->get_html();
//$v = $mt::is_valid($mt->get_value());
//echo "\n" .  $v;
