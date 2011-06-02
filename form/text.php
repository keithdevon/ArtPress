<?php
require_once 'form.php';

// TEXT
class Text_Transform extends CSS_Dropdown_Input { 
    function __construct($parent, $value) { 
        parent::__construct('text-transform', 'text transform', $parent, $value);
        self::set_options(array('none', 'uppercase', 'lowercase', 'capitalize'));
    }    
}
class Text_Align extends CSS_Dropdown_Input { 
    function __construct($parent, $value) { 
        parent::__construct('text-align', 'text align', $parent, $value);
        self::set_options(array('left', 'right', 'center', 'justify'));
    }    
}
class Text_Decoration extends CSS_Dropdown_Input { 
    function __construct($parent, $value) { 
        parent::__construct('text-decoration', 'text decoration', $parent, $value);
        self::set_options(array('none', 'underline', 'overline', 'line-through', 'blink'));
    }    
}