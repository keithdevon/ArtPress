<?php
require_once 'form.php';

// BORDER
class Border_Style extends CSS_Dropdown_Input {
    static $options = array('', 'none', 'hidden', 'dotted', 'dashed', 'solid', 'double', 'groove', 'ridge', 'inset', 'outset', 'inherit');
    function __construct($value=0) { 
        parent::__construct('border-style', 'border style', $value);
    }    
}
class Border_Width extends CSS_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('border-width', 'border width', $value);
    }
}
// MARGIN
class Margin_Top extends CSS_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('margin-top', 'top margin width', $value);
    }
}
class Margin_Bottom extends CSS_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('margin-bottom', 'bottom margin width', $value);
    }
}
class Margin_Right extends CSS_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('margin-right', 'right margin width', $value);
    }
}
class Margin_Left extends CSS_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('margin-left', 'left margin width', $value);
    }
}


// PADDING
class Padding_Top extends CSS_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('padding-top', 'top padding width', $value);
    }
}
class Padding_Bottom extends CSS_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('padding-bottom', 'bottom padding width', $value);
    }
}
class Padding_Right extends CSS_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('padding-right', 'right padding width', $value);
    }
}
class Padding_Left extends CSS_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('padding-left', 'left padding width', $value);
    }
}

// DISPLAY
class Display extends CSS_Dropdown_Input {
    static $options = array('', 'inherit', 'none', 'block', 'inline', 'inline-block', 'inline-table', 'list-item', 
        						'run-in', 'table', 'table-caption', 'table-cell', 'table-column', 
        						'table-column-group', 'table-footer-group', 'table-header-group', 
        						'table-row', 'table-row-group');
    function __construct($value=0) { 
        parent::__construct('display', 'display mode', $value);
    }    
}

class Layout_Tab extends Sub_Tab {
    function __construct($display_name, $members=null) {
        if ( null == $members ) { 
            $members[] = new Border_Style();                                               
            $members[] = new Border_Width();                                              
                                                                                                 
            $display = new Display();                                          
                                                                                                 
            $mt = new Margin_Top();                                            
            $mb = new Margin_Bottom();                                            
            $mr = new Margin_Right();                                            
            $ml = new Margin_Left();                                            
            $members[] = new Option_Row_Group('margin', array($mt, $mb, $mr, $ml) );   
                                                                                                 
            $pt = new Padding_Top();                                           
            $pb = new Padding_Bottom();                                           
            $pr = new Padding_Right();                                           
            $pl = new Padding_Left();                                           
            $members[] = new Option_Row_Group('padding', array($pt, $pb, $pr, $pl) );
        }
        parent::__construct($display_name, $members);
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
