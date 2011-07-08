<?php
require_once 'form.php';
require_once 'color.php';

class Text_Shadow_Horizontal extends CSS_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('text-shadow', 'horizontal text shadow', $value);
    }
}
class Text_Shadow_Vertical extends CSS_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('text-shadow', 'vertical text shadow', $value);
    }
}
class Text_Shadow_Blur_Radius extends CSS_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('text-shadow', 'text shadow blur radius', $value);
    }
}
class Text_Shadow_Color extends Section_Color {
    function __construct($value=0) {
        parent::__construct('text-shadow', 'text shadow color select', $value); 
    }
}

class Box_Shadow_Horizontal extends CSS_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('box-shadow', 'horizontal box shadow', $value);
    }
}
class Box_Shadow_Vertical extends CSS_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('box-shadow', 'vertical box shadow', $value);
    }
}
class Box_Shadow_Blur_Radius extends CSS_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('box-shadow', 'box shadow blur radius', $value);
    }
}
class Box_Shadow_Color extends Section_Color {
    function __construct($value=0) {
        parent::__construct('box-shadow', 'box shadow color select', $value); 
    }
}

class Border_Radius extends CSS_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('border-radius', 'border radius', $value);
    }
}

class Effect_Tab extends Sub_Tab {
    function __construct($display_name, $members=null) {
        if ( null == $members ) { 
            $members[]  = new Border_Radius();
            $tsh   = new Text_Shadow_Horizontal();
            $tsv   = new Text_Shadow_Vertical();
            $tsbr  = new Text_Shadow_Blur_Radius();
            $tsc   = new Text_Shadow_Color();
            $members[] = new CSS_Option_Row_Group('text shadow', 'text-shadow', array( $tsh, $tsv, $tsbr, $tsc ));
    
            $bsh   = new Box_Shadow_Horizontal();
            $bsv   = new Box_Shadow_Vertical();
            $bsbr  = new Box_Shadow_Blur_Radius();
            $bsc   = new Box_Shadow_Color();
            $members[] = new CSS_Option_Row_Group('box shadow', 'box-shadow', array( $bsh, $bsv, $bsbr, $bsc ));
            
        }
        parent::__construct($display_name, $members);
    }
}