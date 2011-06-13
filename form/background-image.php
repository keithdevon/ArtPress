<?php
require_once 'form.php';

// BACKGROUND IMAGE
class Background_Repeat extends CSS_Dropdown_Input {
    static $options = array('no-repeat', 'repeat', 'repeat-x', 'repeat-y', 'inherit'); 
    function __construct($id, $value) { 
        parent::__construct($id, 'background-repeat', 'background repeat', $value); 
        self::set_options( self::$options );
    }    
}
class Background_Attachment extends CSS_Dropdown_Input {
    static $options = array('scroll', 'fixed', 'inherit' ); 
    function __construct($id, $value) { 
        parent::__construct($id, 'background-attachment', 'background attachment', $value); 
        self::set_options( self::$options );
    }    
}
class Background_Horizontal_Position extends CSS_Horizontal_Position_Text_Input {
    function __construct( $id, $value ) {
        parent::__construct( $id, 'background-position', 'horizontal background position', $value );
    }
}
class Background_Vertical_Position extends CSS_Horizontal_Position_Text_Input {
    function __construct( $id, $value ) {
        parent::__construct( $id, 'background-position', 'vertical background position', $value );
    }
}