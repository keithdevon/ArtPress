<?php

class H2 extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('h2', 'header 2', $children=null);
    }
}
class H3 extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('h3', 'header 3', $children=null);
    }
}
class H4 extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('h4', 'header 4', $children=null);
    }
}
class P extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('p', 'paragraph', $children=null);
    }
}
class UL extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('ul', 'unordered list', $children=null);
    }
}
class OL extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('ol', 'ordered list', $children=null);
    }
}
class Link extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('a', 'link', $children=null);
    }
}

class Body_Group extends CSS_Selector_Group {
    function __construct($children=null) {
        if( null == $children ) {
            $children[] = new H2();
            $children[] = new H3();
            $children[] = new H4();   
        }
        parent::__construct('body', 'body', $children);
    }
}

class Body_Tab extends Main_Tab {
    function __construct($children=null) {
        if( null == $children ) {
             $children[] = new Body_Group();   
        }
        parent::__construct('body', 'artpress_options', $children);
    }
}