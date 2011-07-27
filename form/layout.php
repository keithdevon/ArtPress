<?php
require_once 'form.php';


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

$display_options = array('', 'inherit', 'none', 'block', 'inline', 'inline-block', 'inline-table', 'list-item', 
        						'run-in', 'table', 'table-caption', 'table-cell', 'table-column', 
        						'table-column-group', 'table-footer-group', 'table-header-group', 
        						'table-row', 'table-row-group');
// DISPLAY
class Display extends CSS_Dropdown_Input {
    function __construct($value=0) { 
        global $display_options;
        parent::__construct('display', 'display mode', $display_options, $value);
    }    
}

class Layout_Tab extends Sub_Tab {
    function __construct($display_name, $members=null) {
        if ( null == $members ) {                                             
            $og = new Option_Group('', array(
                new Option_Row_Group('margin',  
                    array(
                        new Margin_Top(), 
                        new Margin_Right(), 
                        new Margin_Bottom(), 
                        new Margin_Left()) ),
                new Option_Row_Group('padding', 
                    array(
                        new Padding_Top(), 
                        new Padding_Right(), 
                        new Padding_Bottom(), 
                        new Padding_Left()) ),
                new Display()
                ));
            $members[] = $og;
        }
        parent::__construct($display_name, $members);
    }
}