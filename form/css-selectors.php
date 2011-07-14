<?php

class Page_Title extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('.page-title, h1', 'page title', $children=null);
    }
}
class Entry_Title extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('.entry-title a:link, .entry-title a:visited', 'entry title', $children=null);
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
        parent::__construct('a:link, a:visited', 'link', $children=null);
    }
}
class Link_Hover extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('a:hover, a:active', 'link hover', $children=null);
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
        parent::__construct('.entry-meta, .entry-meta a:link, .entry-meta a:visited, .entry-utility, .entry-utility a:link, .entry-utility a:visited', 'entry meta', $children=null);
    }
}



// MENU

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


// SIDEBAR

class Widget_Title extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('.widget-title', 'widget title', $children=null);
    }
}