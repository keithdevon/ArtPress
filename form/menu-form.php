<?php

class Menu_Base extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('#access', 'Menu Styling', $children=null);
    }
}

class Current_Menu_Item extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('li.current-menu-item a', 'Current Menu Item', $children=null);
    }
}

class Menu_Group extends CSS_Selector_Group {
    function __construct($children=null) {
        if( null == $children ) {
            $children[] = new Menu_Base();
            $children[] = new Link();
            $children[] = new Link_Hover();
            $children[] = new Sub_Menu();
            $children[] = new Sub_Menu_Hover();
            $children[] = new Current_Menu_Item();
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