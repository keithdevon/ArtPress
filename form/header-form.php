<?php

class Header_Base extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('', 'header base', $children=null);
    }
}
class Site_Title extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('#site-title', 'site title', $children=null);
    }
}
class Site_Description extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('#site-description', 'site description', $children=null);
    }
}

class Header_Group extends CSS_Selector_Group {
    function __construct($children=null) {
        if( null == $children ) {
            $children[] = new Header_Base(); 
            $children[] = new Site_Title();  
            $children[] = new Site_Description();
        }
        parent::__construct('#header', 'header', $children);
    }
}

class Header_Tab extends Main_Tab {
    function __construct($children=null) {
        if( null == $children ) {
             $children[] = new Header_Group();   
        }
        parent::__construct('header', 'artpress_options', $children);
    }
}