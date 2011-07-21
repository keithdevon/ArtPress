<?php
class Color_Picker_Group extends Option_Group {
    function __construct($display_name, $members=null) {
        parent::__construct($display_name, $members);
    }
    function get_html() {
        $children_html = '';
        $children = $this->get_children();
        $first = true;
        if ( null != $children) {
            foreach($children as $child) {
                $child_name = $child->get_display_name();
                $child_html = $child->get_html();  
                $row = ot('tr');
                $row .= td($child_name, " style='width:200px;'");
                $row .= td($child_html);
                
                if($first) {
                    $row .= td(div('', 
                                    attr_id('picker')),
                                    attribute('rowspan', '6') . attr_valign('top'));
                    $first = false;
                }
                $row .= ct('tr');
                $children_html .= $row;
            }
        }
        $children_html .=                            
            "<script>
                jQuery(document).ready(function() {
                    var f = jQuery.farbtastic('#picker');
                    var p = jQuery('#picker').css('opacity', 0.25);
                    var selected;
                    jQuery('.colorwell')
                      .each(function () { f.linkTo(this); jQuery(this).css('opacity', 0.75); })
                      .focus(function() {
                        if (selected) {
                          jQuery(selected).css('opacity', 0.75).removeClass('colorwell-selected');
                        }
                        f.linkTo(this);
                        p.css('opacity', 1);
                        jQuery(selected = this).css('opacity', 1).addClass('colorwell-selected');
                      });
                  });
             </script>";
        return table($children_html, attr_class('form-table'));
    }
}
class Global_Settings extends Main_Tab {
    function __construct($members=null) {
        if ( null == $members ) {       
            $gc1 = new Global_Color('Color 1', '#000000');
            $gc2 = new Global_Color('Color 2', '#444444');
            $gc3 = new Global_Color('Color 3', '#888888');
            $gc4 = new Global_Color('Color 4', '#bbbbbb');
            $gc5 = new Global_Color('Color 5', '#ffffff');
            $members[] = new Color_Picker_Group('Global Colors', array($gc1, $gc2, $gc3, $gc4, $gc5));
            
            $gfs = new Global_Font_Size(10);
            $gfss = new Global_Font_Size_Ratio();
            $members[] = new Option_Group('Global Font Size', array($gfs, $gfss));
            $gf1 = new Global_Font_Family('Font family 1', 0);
            $gf2 = new Global_Font_Family('Font family 2', 0);
            $gf3 = new Global_Font_Family('Font family 3', 0);
            $members[] = new Lookup_Option_Group('Global Fonts', array($gf1, $gf2, $gf3));
        }
        parent::__construct('global settings', 'artpress_options', $members);
    }
}

