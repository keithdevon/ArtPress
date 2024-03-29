<?php

class Gallery_Base extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('', 'general gallery settings', $children=null);
    }
}

class Gallery_Title extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('h2.gallery-title a', 'gallery titles', $children=null);
    }
}

class Galleries_Entry_Meta extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('.grid-single .entry-meta, .grid-single .entry-meta a:link', 'entry meta', $children=null);
    }
}

class Gallery_Image_Links extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('div.gallery-icon', 'images', $children=null);
    }
}

class Gallery_Group extends CSS_Selector_Group {
    function __construct($children=null) {
        if( null == $children ) {
            $children[] = new Gallery_Base();
            $children[] = new Gallery_Title();
            // TODO: Make this conditional $children[] = new Galleries_Entry_Meta();
            $children[] = new Gallery_Image_Links();
        }
        parent::__construct('', 'gallery', $children);
    }
}

class Gallery_Tab extends Main_Tab {
    function __construct($children=null) {
        if( null == $children ) {
             $children[] = new Gallery_Group();
        }
        parent::__construct('gallery', 'artpress_options', $children);
    }
}