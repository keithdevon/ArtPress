<?php
require_once 'form.php';

// TEXT
class Text_Transform extends CSS_Dropdown_Input {
    static private $options = array('none', 'uppercase', 'lowercase', 'capitalize');
    function __construct($id, $parent, $value) { 
        parent::__construct($id, 'text-transform', 'text transform', $parent, $value);
        self::set_options( self::$options );
    }    
}
class Text_Align extends CSS_Dropdown_Input {
    static private $options = array('left', 'right', 'center', 'justify');
    function __construct($id, $parent, $value) { 
        parent::__construct($id, 'text-align', 'text align', $parent, $value);
        self::set_options( self::$options );
    }    
}
class Text_Decoration extends CSS_Dropdown_Input {
    static private $options = array('none', 'underline', 'overline', 'line-through', 'blink');
    function __construct($id, $parent, $value) { 
        parent::__construct($id, 'text-decoration', 'text decoration', $parent, $value);
        self::set_options( self::$options );
    }   
}