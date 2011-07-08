<?php

class Page_Title extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('.page-title', 'page title', $children=null);
    }
}
class Entry_Title extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('.entry-title, .entry-title a', 'entry title', $children=null);
    }
}
class H1 extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('h1', 'header 1', $children=null);
    }
}
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
class Crumbs extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('#breadcrumbs li a, #breadcrumbs li', 'breadcrumbs', $children=null);
    }
}
class Crumb_Hover extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('#breadcrumbs li a:hover, #breadcrumbs li a:active', 'breadcrumb hover', $children=null);
    }
}
class Entry_Meta extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('.entry-meta', 'entry meta', $children=null);
    }
}

class Body_Group extends CSS_Selector_Group {
    function __construct($children=null) {
        if( null == $children ) {
            $children[] = new Page_Title();
            $children[] = new Entry_Title();
            $children[] = new H1();
            $children[] = new H2();
            $children[] = new H3();
            $children[] = new H4();
            $children[] = new P();
            $children[] = new UL();
            $children[] = new OL();
            $children[] = new Link();
            $children[] = new Crumbs();  
            $children[] = new Crumb_Hover(); 
            $children[] = new Entry_Meta();
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