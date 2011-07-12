<?php

class Global_Settings extends Main_Tab {
    function __construct($members=null) {
        if ( null == $members ) {       
            $gc1 = new Global_Color('Color 1', '#222222');
            $gc2 = new Global_Color('Color 2', '#444444');
            $gc3 = new Global_Color('Color 3', '#666666');
            $gc4 = new Global_Color('Color 4', '#888888');
            $gc5 = new Global_Color('Color 5', '#AAAAAA');
            $members[] = new Option_Group('Global Colors', array($gc1, $gc2, $gc3, $gc4, $gc5));
            
            $gf1 = new Global_Font_Family('font family 1', 1);
            $gf2 = new Global_Font_Family('font family 2', 2);
            $gf3 = new Global_Font_Family('font family 3', 4);
            $members[] = new Lookup_Option_Group('Global Fonts', array($gf1, $gf2, $gf3));
        }
        parent::__construct('global settings', 'artpress_options', $members);
    }
}