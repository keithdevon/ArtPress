<?php
class Custom_CSS extends Setting_Textarea {

    function __construct($value='') {
        parent::__construct('custom-css', 'Custom CSS', $value);
    }
    
    function validate($value) {
        // TODO 
        return true;
    }
    function get_html(){
        $html = 
            table(
                row(
                    td($this->get_display_name() )
                    . td( parent::get_html( attr_class('globalSetting') ) )
                ),
                attr_class('form-table')
            );
        return $html;
    }
}
class Advanced_Tab extends Main_Tab {
    function __construct($children=null) {
        if( null == $children ) {
            $children[] = new Custom_CSS();
        }
        parent::__construct('advanced', 'artpress_options', $children);
    }
}