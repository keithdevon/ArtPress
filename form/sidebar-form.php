<?php

class Sidebar_Base extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('', 'sidebar base', $children=null);
    }
}
class Widget_Title extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('.widget-title', 'widget title', $children=null);
    }
}
class Sidebar_Links extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('a:link, a:visited', 'links', $children=null);
    }
}


class Sidebar_Group extends CSS_Selector_Group {
    function __construct($children=null) {
        if( null == $children ) {
            $children[] = new Sidebar_Base();
            $children[] = new Widget_Title();
            $children[] = new Sidebar_Links();
        }
        parent::__construct('.sidebar', 'sidebar', $children);
    }
}

class Sidebar_Tab extends Main_Tab {
    function __construct($children=null) {
        if( null == $children ) {
             $children[] = new Sidebar_Group();   
        }
        parent::__construct('sidebar', 'artpress_options', $children);
    }
}