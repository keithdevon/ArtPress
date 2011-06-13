<?php
require_once 'form.php';

// BORDER
class Border_Style extends CSS_Dropdown_Input {
    static $options = array('none', 'hidden', 'dotted', 'dashed', 'solid', 'double', 'groove', 'ridge', 'inset', 'outset', 'inherit');
    function __construct($id, $value) { 
        parent::__construct($id, 'border-style', 'border style', $value);
        self::set_options( self::$options );
    }    
}
class Border_Width extends CSS_Size_Text_Input {
    function __construct($id, $value) {
        parent::__construct($id, 'border-width', 'border width', $value);
    }
}
// MARGIN
class Margin_Top extends CSS_Size_Text_Input {
    function __construct($id, $value) {
        parent::__construct($id, 'margin-top', 'top margin width', $value);
    }
}
class Margin_Bottom extends CSS_Size_Text_Input {
    function __construct($id, $value) {
        parent::__construct($id, 'margin-bottom', 'bottom margin width', $value);
    }
}
class Margin_Right extends CSS_Size_Text_Input {
    function __construct($id, $value) {
        parent::__construct($id, 'margin-right', 'right margin width', $value);
    }
}
class Margin_Left extends CSS_Size_Text_Input {
    function __construct($id, $value) {
        parent::__construct($id, 'margin-top', 'left margin width', $value);
    }
}


// PADDING
class Padding_Top extends CSS_Size_Text_Input {
    function __construct($id, $value) {
        parent::__construct($id, 'padding-top', 'top padding width', $value);
    }
}
class Padding_Bottom extends CSS_Size_Text_Input {
    function __construct($id, $value) {
        parent::__construct($id, 'padding-bottom', 'bottom padding width', $value);
    }
}
class Padding_Right extends CSS_Size_Text_Input {
    function __construct($id, $value) {
        parent::__construct($id, 'padding-right', 'right padding width', $value);
    }
}
class Padding_Left extends CSS_Size_Text_Input {
    function __construct($id, $value) {
        parent::__construct($id, 'padding-top', 'left padding width', $value);
    }
}

// DISPLAY
class Display extends CSS_Dropdown_Input {
    static $options = array('inherit', 'none', 'block', 'inline', 'inline-block', 'inline-table', 'list-item', 
        						'run-in', 'table', 'table-caption', 'table-cell', 'table-column', 
        						'table-column-group', 'table-footer-group', 'table-header-group', 
        						'table-row', 'table-row-group');
    function __construct($id, $value) { 
        parent::__construct($id, 'display', 'display mode', $value);
        self::set_options( self::$options );
    }    
}

class Layout_Tab extends Sub_Tab {
        function __construct($id, $name, $members=null, $html_id=null) {
        if ( null == $members ) { 
            $members[] = new Border_Style('bodyh2bs', 0);                                               
            $members[] = new Border_Width('bodyh2bw', '');                                              
                                                                                                 
            $display = new Display('bodyh2display', 0);                                          
                                                                                                 
            $mt = new Margin_Top(    'bodyh2mt', '');                                            
            $mb = new Margin_Bottom( 'bodyh2mb', '');                                            
            $mr = new Margin_Right(  'bodyh2mr', '');                                            
            $ml = new Margin_Left(   'bodyh2ml', '');                                            
            $members[] = new Option_Row_Group( 'bodyh2mgrp', 'margin', array($mt, $mb, $mr, $ml) );   
                                                                                                 
            $pt = new Padding_Top(    'bodyh2pt', '');                                           
            $pb = new Padding_Bottom( 'bodyh2pb', '');                                           
            $pr = new Padding_Right(  'bodyh2pr', '');                                           
            $pl = new Padding_Left(   'bodyh2pl', '');                                           
            $members[] = new Option_Row_Group( 'bodyh2pgrp', 'padding', array($pt, $pb, $pr, $pl) );  
                                                                                                 
            //$t2 = new Sub_Tab('bodyh2layout', 'layout', array( $bs, $bw, $display, $mgrp, $pgrp));
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
