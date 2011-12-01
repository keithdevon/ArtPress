<?php

class Body_Base extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('', 'general page/post settings', $children=null);
    }
}


class Body_Group extends CSS_Selector_Group {
    function __construct($children=null) {
        if( null == $children ) {
            $children[] = new Body_Base();
            $children[] = new Page_Title();
            $children[] = new Entry_Title();
            $children[] = new Widget_Title();
            $children[] = new H2();
            $children[] = new H3();
            $children[] = new H4();
            $children[] = new P();
            $children[] = new UL();
            $children[] = new OL();
            $children[] = new Link();
            $children[] = new Link_Hover();
            //TODO: Make these conditional $children[] = new Crumbs();
            //$children[] = new Crumb_Hover();
            $children[] = new Entry_Meta();
        }
        parent::__construct('body', 'general', $children);
    }
}

class Body_Tab extends Main_Tab {
    function __construct($children=null) {
        if( null == $children ) {
             $children[] = new Body_Group();
        }
        parent::__construct('general', 'artpress_options', $children);
    }
}
