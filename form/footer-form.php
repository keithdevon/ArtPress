<?php

class Footer_Base extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('', 'Footer Base', $children=null);
    }
}
class Footer_Widget_Title extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('.widget-title', 'widget title', $children=null);
    }
}
class Footer_Links extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('a:link, a:visited', 'links', $children=null);
    }
}


class Footer_Group extends CSS_Selector_Group {
    function __construct($children=null) {
        if( null == $children ) {
            $children[] = new Footer_Base();
            $children[] = new Footer_Widget_Title();
            $children[] = new Footer_Links();

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