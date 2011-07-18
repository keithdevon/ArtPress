<?php

class Sidebar_Base extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('', 'sidebar base', $children=null);
    }
}


class Sidebar_Group extends CSS_Selector_Group {
    function __construct($children=null) {
        if( null == $children ) {
            $children[] = new Sidebar_Base();
            $children[] = new Widget_Title();
            $children[] = new Link();
            $children[] = new Link_Hover();
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