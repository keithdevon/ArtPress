<?php
require_once 'form.php';

// BACKGROUND IMAGE
class Background_Repeat extends CSS_Dropdown_Input {
    static $options = array('', 'no-repeat', 'repeat', 'repeat-x', 'repeat-y', 'inherit'); 
    function __construct($value=0) { 
        parent::__construct('background-repeat', 'background repeat', $value); 
    }    
}
class Background_Attachment extends CSS_Dropdown_Input {
    static $options = array('', 'scroll', 'fixed', 'inherit' ); 
    function __construct($value=0) { 
        parent::__construct('background-attachment', 'background attachment', $value); 
    }    
}
class Background_Horizontal_Position extends CSS_Horizontal_Position_Text_Input {
    function __construct($value='') {
        parent::__construct('background-position', 'horizontal background position', $value );
    }
}
class Background_Vertical_Position extends CSS_Horizontal_Position_Text_Input {
    function __construct($value='') {
        parent::__construct('background-position', 'vertical background position', $value );
    }
}
class Background_Image_Tab extends Sub_Tab {
    function __construct($display_name, $members=null) {
        if ( null == $members ) { 
            $members[] = new Background_Repeat();
            $members[] = new Background_Attachment();
            $members[] = new Background_Horizontal_Position();
            $members[] = new Background_Vertical_Position();        
        }
        parent::__construct($display_name, $members);
    }
}