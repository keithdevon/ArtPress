<?php
require_once 'form.php';

// BACKGROUND IMAGE
$background_repeat_options = array('', 'no-repeat', 'repeat', 'repeat-x', 'repeat-y', 'inherit');
class Background_Repeat extends CSS_Dropdown_Input {
    function __construct($value=0) {
        global $background_repeat_options;
        parent::__construct('background-repeat', 'background repeat', $background_repeat_options, $value);
    }
}
$background_attachment_options= array('', 'scroll', 'fixed', 'inherit' );
class Background_Attachment extends CSS_Dropdown_Input {
    function __construct($value=0) {
        global $background_attachment_options;
        parent::__construct('background-attachment', 'background attachment', $background_attachment_options, $value);
    }
}
class Background_Horizontal_Position extends Setting_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('horizontal', 'horizontal background position', $value );
    }
    function validate($value) {
        if(parent::validate($value) || in_array($value, array('left', 'center', 'right'))) {
            return true;
        } else {
            return false;
        }
    }
    function get_html(  ) {
        return parent::get_html( attr_on_change('checkValidHorizontalPosition(this)') );
    }
    function get_css_value() {
        return $this->get_value();
    }
}
class Background_Vertical_Position extends Setting_Size_Text_Input {
    function __construct($value='') {
        parent::__construct('vertical', 'vertical background position', $value );
    }
    function validate($value) {
         if(parent::validate($value) || in_array($value, array('top', 'center', 'bottom'))) {
             return true;
         } else {
             return false;
         }
    }
    function get_html(  ) {
        return parent::get_html( attr_on_change('checkValidVerticalPosition(this)') );
    }
    function set_name($name) {
        parent::set_name($name);
    }
    function get_name() {
        return parent::get_name();
    }
    function get_css_value() {
        return $this->get_value();
    }
}
class Background_Position extends CSS_Composite {
    function __construct() {
        parent::__construct('background position', 'background-position',
            array(
                new Background_Horizontal_Position(),
                new Background_Vertical_Position()
                )
        );
    }
    function validate($value) {
        return is_valid_size_string($value);
    }
}
class Background_Image_Tab extends Sub_Tab {
    function __construct($display_name, $members=null) {
        if ( null == $members ) {
            $members = array(
                new Section_Background_Color(),
                new Background_Image_Dropdown( 
                    array(
                        new Background_Repeat(),
                        new Background_Attachment(),
                        new Option_Row_Group('',
                            array(
                            new Column_Header('horizontal'),
                            new Column_Header('vertical')
                            )
                        ),
                        new Background_Position()
                        ) 
                 ) 
             );
        }
        parent::__construct($display_name, $members);
    }
    function get_html() {
        return parent::get_html();
    }
}
