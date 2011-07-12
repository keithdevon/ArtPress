<?php

class Menu_Base extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('', 'Menu Styling', $children=null);
    }
}
class Menu_Links extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('a:link, a:visited', 'menu link', $children=null);
    }
}
class Sub_Menu extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('ul.sub-menu, ul.sub-menu a:link, ul.sub-menu a:visited', 'drop downs', $children=null);
    }
}
class Sub_Menu_Hover extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('ul.sub-menu a:hover, ul.sub-menu a:active', 'drop down hover', $children=null);
    }
}


class Menu_Group extends CSS_Selector_Group {
    function __construct($children=null) {
        if( null == $children ) {
            $children[] = new Menu_Base();
            $children[] = new Menu_Links();
            $children[] = new Sub_Menu();
            $children[] = new Sub_Menu_Hover();
        }
        parent::__construct('#access', 'main menu', $children);
    }
}

class Menu_Tab extends Main_Tab {
    function __construct($children=null) {
        if( null == $children ) {
             $children[] = new Menu_Group();   
        }
        parent::__construct('menu', 'artpress_options', $children);
    }
}