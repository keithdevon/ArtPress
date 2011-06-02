<?php
require_once 'form.php';

// BACKGROUND IMAGE
class Background_Repeat extends CSS_Dropdown_Input { 
    function __construct($parent, $value) { 
        parent::__construct('background-repeat', 'background repeat', $parent, $value); 
        self::set_options(array('no-repeat', 'repeat', 'repeat-x', 'repeat-y', 'inherit'));
    }    
}
class Background_Attachment extends CSS_Dropdown_Input { 
    function __construct($parent, $value) { 
        parent::__construct('background-attachment', 'background attachment', $parent, $value); 
        self::set_options(array('scroll', 'fixed', 'inherit' ));
    }    
}