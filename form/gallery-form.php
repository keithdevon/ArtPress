<?php

class Gallery_Base extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('', 'gallery base', $children=null);
    }
}

class Gallery_Title extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('h2.gallery-title a:link, h2.gallery-title a:visited', 'gallery titles', $children=null);
    }
}

class Gallery_Image_Links extends CSS_Selector {
    function __construct($children=null) {
        parent::__construct('div.category-galleries div.gallery-thumb', 'gallery listings image', $children=null);
    }
}

class Gallery_Group extends CSS_Selector_Group {
    function __construct($children=null) {
        if( null == $children ) {
            $children[] = new Gallery_Base();
            $children[] = new Gallery_Title();
            $children[] = new Entry_Meta();
            $children[] = new Gallery_Image_Links();
        }
        parent::__construct('.category-galleries', 'gallery', $children);
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