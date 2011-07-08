<?php

class Footer_Widget_Title extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('.widget-title', 'widget title', $children=null);
    }
}


class Footer_Group extends CSS_Selector_Group {
    function __construct($children=null) {
        if( null == $children ) {
            $children[] = new Footer_Widget_Title();
        }
        parent::__construct('#footer', 'footer', $children);
    }
}

class Footer_Tab extends Main_Tab {
    function __construct($children=null) {
        if( null == $children ) {
             $children[] = new Footer_Group();   
        }
        parent::__construct('footer', 'artpress_options', $children);
    }
}