<?php
/** 
 * The settings form is a bunch of tabs and accordion gui elements which display options for configuring
 * the theme.
 * 
 * settings can be things like text, dropdown, checkbox, radio
 * settings are grouped into things like
 * typography, layout, background, effects
 * 
 * the selected options in turn dictate what css is generated
 * 
 * default value
 * 
 * */
$dir = get_template_directory() . '/';
require_once $dir . 'html-gen.php';
require_once $dir . 'heart-theme-utils.php';

interface Render_As_HTML {
     //static function is_valid($value);
     function get_html();
}

interface Skippable {}

/**
 * 
 * This class maintains a notion of elements in a hierarchy.
 * This will allow information about specific settings to be infered 
 * from where they are in the hierarchy, precluding the need to store 
 * all the information in every setting.
 * @author jsd
 *
 */
abstract class Hierarchy {
    private $id;    // non unique
    private $name;
    private $parent;
    private $children;
    
    function __construct($id, $name, $children=array()) {
        $this->id     = $id;
        $this->name   = $name;
        if ($children != null) {
            if ( is_array($children) ) {
                foreach ( $children as $child ) {
                    $this->add_child($child);
                }
            } else $this->add_child($children);
        } 
    }
    
    // GETTERS
    final function get_name()     { return $this->name;     }
    final function get_children() { return $this->children; }
    final function get_parent()   { return $this->parent;   }
    final function get_id()       { return $this->id;       }
    
    function get_ancestory() {
        $p = $this->parent;
        $ancestors = array();
        while ($p != null) {
            if (!($p instanceof Skippable))
                array_push($ancestors, $p);
            $p = $p->get_parent();
        }
        return $ancestors;
    }

    function construct_id() {
        $id = '';
        foreach ( get_ancestory() as $ancestor ) {
            $id .= '_' . $ancestor;
        }
        return $id;
    }
    
    // SETTERS
    function add_child($child)   { 
        $child->set_parent($this);
        $this->children[]   = $child;  
    }
    function set_parent($parent) { $this->parent = $parent; }
}

/** 
 * 
 * This class is used to store a group of setting objects
 * 
 * Although it is a hierarchy object, 
 * it is merely used to loosely group a bunch of objects together.
 * This grouping may change and it shouldn't be a fixed part of the hierarchy 
 * Consequently the parent objects of its members are actually the parent
 * of this group. 
 * So when the ancestory of an element is calculated, this group won't show up,
 * it will have been skipped.
 *
 */
class Group extends Hierarchy implements Render_As_HTML, Skippable {
    function __construct($id, $name, $members=array()) {
        parent::__construct($id, $name, $members);
 
    }
    function get_html() {
        $o = '';
        $children = $this->get_children();
        if ( null != $children) {
            foreach($children as $child) {
                $o .= $child->get_html();    
            }
        }
        return $o;   
    }
}
class Option_Group extends Group {
    /**
     * Constructs an options array on the fly from its constituent settings
     * The settings names are the keys of the array 
     * and the settings values are the values of the array. 
     */
    // TODO ^ is this still true?
    function __construct($id, $name, $members=array()) {
        parent::__construct($id, $name, $members);
    }  
    function get_options() { 
        $options = array();
        foreach ($this->get_children() as $member) {
            $options[] = $member->get_value();
        }
        return $options;
    }
    function get_html() {
        $children_html = '';
        $children = $this->get_children();
        if ( null != $children) {
            foreach($children as $child) {
                $child_name = $child->get_name();
                $child_html = $child->get_html();  
                $children_html .= row($child_name, $child_html);
            }
        }
        return table($children_html, attr_class('form-table'));
    }
}
class Option_Row_Group extends Group {
    function __construct($id, $name, $members=array()) {
        parent::__construct($id, $name, $members);
    } 
    function get_html() {
        $children_html = '';
        $children = $this->get_children();
        if ( null != $children) {
            foreach($children as $child) {
                $children_html .= $child->get_html();  
            }
        }
        return $children_html;
    }    
}
class Lookup_Option_Group extends Option_Group {
    /**
     * specifically created for font options
     */
    function __construct($id, $name, $members=array()) {
        parent::__construct($id, $name, $members);
    } 
    function get_options() { 
        $options = array();
        foreach ($this->get_children() as $member) {
            $member_options = $member->get_options();
            $n = $member->get_value();
            $options[$n] = $member_options[$n];
        }
        return $options;
    }
}
abstract class Sub_Tab extends Group  { // TODO change to Sub_Tab
    private $html_id;
    function __construct($id, $name, $members=null, $html_id=null) {
        parent::__construct($id, $name, $members);
        $this->id = $id;
    }
    function get_html() {

        $o = '';
        $children_html = '';
        $children = $this->get_children();
        if ( null != $children) {
            foreach($children as $child) {
                $child_name = $child->get_name();
                $child_html = $child->get_html();  
                $children_html .= row($child_name, $child_html);
            }
        }
 
        if ($children[0] instanceof Setting) {
            $children_html = table( $children_html, attr_class('form-table') );
        }
        $o = div( $children_html, attr_id( $this->get_html_id() ) );
        return $o;
    }
    function get_html_id()       { return $this->html_id;   }
    function set_html_id($value) { $this->html_id = $value; }
}
class Typography_Tab extends Sub_Tab {
    function __construct($id, $name, $members=null, $html_id=null) {
        if ( null == $members ) { 
            $members[] = new Section_Color('bodyh2sc');
            $members[] = new Section_Background_Color('bodyh2sbc');        
            $members[] = new Section_Font('bodyh2ff');
            $members[] = new Font_Style('bodyh2fs');
            $members[] = new Font_Weight('bodyh2fs');
            $members[] = new Text_Align('bodyh2ta');
            $members[] = new Text_Decoration('bodyh2td');
            $members[] = new Text_Transform('bodyh2tt');
        }
        parent::__construct($id, $name, $members);
        $this->id = $id;
    }    
}

class Main_Tab extends Hierarchy implements Render_As_HTML {
    private $html_id;
    function __construct($id, $name, $members=null, $html_id=null) {
        parent::__construct($id, $name, $members);
        $this->id = $id;
    }  
    function get_html() {
        $children_html = '';
        $children = $this->get_children();
        if ( null != $children) {
            foreach($children as $child) {
                $children_html .= $child->get_html();    
            }
        }
        $o = ot( 'div', attr_id( $this->get_html_id() ) );
            $o .= '<form method="post" action="options.php">';
                $o .= $children_html;
                $save = __( 'save' );
                $o .= "<p class='submit'><input type='submit' class='button-primary' value='{$save}' /></p>";      
            $o .= ct('form');
        $o .= ct('div');
        return $o;
    }
    function get_html_id()       { return $this->html_id;   }
    function set_html_id($value) { $this->html_id = $value; }
}         
class Tab_Group extends Group {

    function __construct($id, $name, $members=array()) {
        parent::__construct($id, $name, $members);
    }
    
    function get_html() {
        $child_tabs_html = '';
        $links = '';
        $tabs = '';
        
        $id = $this->get_id();
        $children = $this->get_children();
        
        $count = 1;
        if (null != $children) {
            foreach($children as $child) {
                $n = $count++;
                //<div id="tabs-1">
                $child->set_html_id("{$id}-tabs-" . $n);
                //<a href="#tabs-1">
                $links .= li( alink('#' . $child->get_html_id(), $child->get_name() ) );
                $tabs  .= $child->get_html();
            }
        }
        
        $ul = ul($links);
        $script = "<script> $( function() { $( '#{$id}-tabs' ).tabs(); } ); </script>";
        
        $o = $script;
        $o .= div($ul . 
                 $tabs,
                  attr_id("{$id}-tabs")
                );
        return $o;
    }
}
class Main_Tab_Group extends Tab_Group {
    function __construct($id, $name, $members=array()) {
        parent::__construct($id, $name, $members);
    
        $gc1 = new Global_Color('gsgc1', 'Color 1', '#222222');
        $gc2 = new Global_Color('gsgc2', 'Color 2', '#444444');
        $gc3 = new Global_Color('gsgc3', 'Color 3', '#666666');
        $gc4 = new Global_Color('gsgc4', 'Color 4', '#888888');
        $gcgrp = new Option_Group('gcgrp', 'Global Colors', array($gc1, $gc2, $gc3, $gc4));
        
        $gf1 = new Global_Font_Family('gsgf1', 'font family 1', 0);
        $gf2 = new Global_Font_Family('gsgf2', 'font family 2', 2);
        $gf3 = new Global_Font_Family('gsgf3', 'font family 3', 4);
        $gfgrp = new Lookup_Option_Group('gfgrp', 'Global Fonts', array($gf1, $gf2, $gf3));
        
        $globalsettings = new Main_Tab('gstab', 'global settings', array($gcgrp, 
            $gfgrp) );
        
        // ----------------
        
        //$lst = new List_Style_Type('bodyh2lst', 0);
        
        //$sc = new Section_Color('bodyh2sc');
        //$sbc = new Section_Background_Color('bodyh2sbc');
        //
        //$ff = new Section_Font('bodyh2ff');
        //$fs = new Font_Style('bodyh2fs');
        //$fw = new Font_Weight('bodyh2fs');
        //$ta = new Text_Align('bodyh2ta');
        //$td = new Text_Decoration('bodyh2td');
        //$tt = new Text_Transform('bodyh2tt');
        //$t1 = new Sub_Tab('bodyh2typography', 'typography', array($sc, $sbc, $fs, $fw, $ff, $ta, $td, $tt
        ));
        
        //$bs = new Border_Style('bodyh2bs', 0);
        //$bw = new Border_Width('bodyh2bw', '');
        //
        //$display = new Display('bodyh2display', 0);
        //
        //$mt = new Margin_Top(    'bodyh2mt', '');
        //$mb = new Margin_Bottom( 'bodyh2mb', '');
        //$mr = new Margin_Right(  'bodyh2mr', '');
        //$ml = new Margin_Left(   'bodyh2ml', '');
        //$mgrp = new Option_Row_Group( 'bodyh2mgrp', 'margin', array($mt, $mb, $mr, $ml) );
        //
        //$pt = new Padding_Top(    'bodyh2pt', '');
        //$pb = new Padding_Bottom( 'bodyh2pb', '');
        //$pr = new Padding_Right(  'bodyh2pr', '');
        //$pl = new Padding_Left(   'bodyh2pl', '');
        //$pgrp = new Option_Row_Group( 'bodyh2pgrp', 'padding', array($pt, $pb, $pr, $pl) );
        //
        //$t2 = new Sub_Tab('bodyh2layout', 'layout', array( $bs, $bw, $display, $mgrp, $pgrp));
        
        $br = new Background_Repeat(null, 0);
        $ba = new Background_Attachment(null, 0);
        $bhp = new Background_Horizontal_Position('bodyh2bhp', '');
        $bvp = new Background_Vertical_Position('bodyh2bvp', '');        
        $t3 = new Sub_Tab('bodyh2background', 'background', array($br, $ba, $bhp, $bvp));
        
        $tsh   = new Text_Shadow_Horizontal(  'bodyh2tsh', '');
        $tsv   = new Text_Shadow_Vertical(    'bodyh2tsv', '');
        $tsbr  = new Text_Shadow_Blur_Radius( 'bodyh2tsbr', '');
        $tsc   = new Text_Shadow_Color(       'bodyh2tsc', 0);
        $tsgrp = new Option_Row_Group('bodyh2tsgrp', 'text shadow', array( $tsh, $tsv, $tsbr, $tsc ));

        $bsh   = new Box_Shadow_Horizontal(  'bodyh2bsh', '');
        $bsv   = new Box_Shadow_Vertical(    'bodyh2bsv', '');
        $bsbr  = new Box_Shadow_Blur_Radius( 'bodyh2bsbr', '');
        $bsc   = new Box_Shadow_Color(       'bodyh2bsc', 0);
        $bsgrp = new Option_Row_Group('bodyh2tsgrp', 'box shadow', array( $bsh, $bsv, $bsbr, $bsc ));
        
        $brad  = new Border_Radius('bodyh2brad', '');
        
        //$effectgrp = new Option_Group('bodyh2effectgrp', 'effects', array($tsgrp, $bsgrp));
        
        $t4 = new Sub_Tab('bodyh2effect', 'effects', array(//$effectgrp
            $brad, $tsgrp, $bsgrp
            ));
        
        $h2tg = new Tab_Group('bodyh2typographytabgroup', 'typography tab group', array($t1, $t2, $t3, $t4
        ));
        $h2 = new CSS_Selector('bodyh2', 'h2', 'header 2', array($h2tg
        ));    
        //echo $h2tg->get_html();
        //echo $h2->get_html();
        
        // ----------------
        
        $ta = new Text_Align('bodyh3ta', 0);
        $td = new Text_Decoration('bodyh3td', 0);
        $tt = new Text_Transform('bodyh3tt', 0);
        $t1 = new Sub_Tab('bodyh3typography', 'typography', array($ta, $td, $tt));
        
        $bs = new Border_Style('bodyh3bs', 0);
        $bw = new Border_Width('bodyh3bw', '');
        
        $t2 = new Sub_Tab('bodyh3layout', 'layout', array($bs, $bw));
        
        $br = new Background_Repeat(null, 0);
        $ba = new Background_Attachment(null, 0);
        $t3 = new Sub_Tab('bodyh3background', 'background', array($br, $ba));
        
        $tsh = new Text_Shadow_Horizontal(null, '');
        $tsv = new Text_Shadow_Vertical(null, '');
        $t4 = new Sub_Tab('bodyh3effect', 'effects', array($tsh, $tsv));
        
        $h3tg = new Tab_Group('bodyh3typographytabgroup', 'typography tab group', array($t1, $t2, $t3, $t4
        ));
        $h3 = new CSS_Selector('bodyh3', 'h3', 'header 3', array($h3tg
        ));    
        
        $bodycsg = new CSS_Selector_Group('bodycsg', 'body', 'body', array($h2, $h3));
        
        //echo $h2->get_html();
        //echo $h3->get_html();
        //echo $csg->get_html();
        //echo $bodycsg->get_html();
        
        $bodytab = new Main_Tab('bodytab', 'body tab', $bodycsg);
        //echo $bodytab->get_html();
    
        $headertab = new Main_Tab('headertab', 'header tab');
        
        $this->add_child($bodytab);
        $this->add_child($globalsettings);
        $this->add_child($headertab);
    }
}
class CSS_Selector extends Hierarchy implements Render_As_HTML {
    private $css_selector;
    function __construct($id, $css_selector, $name, $children) {
        parent::__construct($id, $name, $children);
        $this->css_selector = $css_selector;
    }
    function get_css_selector() {
        $parent = $this->parent;
        $parent_css = $parent->get_css_selector();
        return $parent_css . ' ' . $css_selector;
    }
    function get_html() {
        $o = '';
        $children = $this->get_children();
        if ( null != $children ) {
            foreach($children as $child) {
                $o .= $child->get_html();    
            }
        }
        return div($o);   
    }
}
class CSS_Selector_Group extends CSS_Selector {
    function __construct($id, $css_selector, $name, $children) {
        parent::__construct($id, $css_selector, $name, $children);
    }
    function get_html() {
        $o = '<script> $(function() { $( "#' . $this->get_id() . '-accordion" ).accordion(); }); </script>';
        $o .= ot('div', attr_id($this->get_id() . '-accordion'));
       
        $children = $this->get_children();
        foreach ($children as $child) {
            $name = $child->get_name();
            $link = h4( alink('#', $name) );
            $child_html = $child->get_html();
            $o .= $link;
            $o .= div(
                $child_html
            );
        }
        $o .= ct('div');
        return $o;
    }
}
/**
 * 
 * This class represents a setting. It only introduces one new attribute: value.
 * This will be the current value of the setting.
 * @author jsd
 *
 */
abstract class Setting extends Hierarchy implements Render_As_HTML {

    private $value;

    function __construct($id, $name, $v) {
        $this->value = $v;
        parent::__construct($id, $name, null);
        //set_value($v);
    }

    function get_value()       { return $this->value; }
    function set_value($value) { $this->value = $value; }
    
    private function get_qualifier() {
        $qualifier = '';
        foreach($this::get_ancestory() as $part) $qualifier = "[{$part->get_id()}]" . $qualifier;
        return $qualifier;
    }
    
    function get_html_name() {
        //TODO add option group name eg artpress_theme_options
        return attr_name($this->get_qualifier() . '[' . $this->get_id() . ']');
    }
    
}
/**
 * 
 * This class represents settings whose values will ultimately be rendered to css
 * @author jsd
 *
 */
abstract class CSS_Setting extends Setting {
    private $css_property;
    
    function __construct($id, $css_property, $name, $value) {
       // echo var_dump(func_get_args());
       $this->css_property = $css_property;
       parent::__construct($id, $name, $value);
    }
    function get_css_declaration() {
        $name = $this->get_name();
        $value = $this->get_value();
        if ($value) return "\n" . $name . ": " . $value . ";";
        else return '';
    }
}


abstract class CSS_Text_Input extends CSS_Setting {
    function __construct($id, $css_property, $name, $value) {
        parent::__construct($id, $css_property, $name, $value);        
    }
    
    function get_html() {
        $input = input('text', $this->get_html_name() . attr_value($this->get_value()));
        //$o = $this->create_form_row( $input );
        return $input;
    }
}
abstract class CSS_Size_Text_Input extends CSS_Text_Input {
    function __construct($id, $css_property, $name, $value='') {
        parent::__construct($id, $css_property, $name, $value);        
    }
    
    static function is_valid($value) {
         if(is_valid_size_string($value)) return true; 
         else return false;
    }
}
abstract class CSS_Horizontal_Position_Text_Input extends CSS_Size_Text_Input {
    function __construct($id, $css_property, $name, $value='') {
        parent::__construct($id, $css_property, $name, $value);        
    }
    
    static function is_valid($value) {
         if(parent::is_valid($value) || in_array($value, array('left', 'center', 'right'))) {
             return true; 
         } else {
             return false;
         }
    }
}
abstract class CSS_Vertical_Position_Text_Input extends CSS_Size_Text_Input {
    function __construct($id, $css_property, $name, $value='') {
        parent::__construct($id, $css_property, $name, $value);        
    }
    
    static function is_valid($value) {
         if(parent::is_valid($value) || in_array($value, array('top', 'center', 'bottom'))) {
             return true; 
         } else {
             return false;
         }
    }
}
/** 
 * 
 * This class represents an HTML select element
 * @author jsd
 *
 */
abstract class CSS_Dropdown_Input extends CSS_Setting {
    private $options;
    
    function __construct($id, $css_property, $name, $value=0) { 
        parent::__construct($id, $css_property, $name, $value);
    }
    
    function get_html() {
        $select = ot('select', $this->get_html_name());
        $select .= $this->get_html_options();
        $select .= ct('select');
        //$o = $this->create_form_row( $select );
        return $select;
    }
    private function get_html_options() {
        $html_options = '';        
        $is_optgroup = false;
        $content = '';
        $potential_options = static::get_options();
        foreach (array_keys($potential_options) as $opt) {
            if (is_array($potential_options[$opt])) {
                if($is_optgroup) { // if this has already been set ... 
                    // ... then we need to close the previous opt group
                    $html_options .= ct('optgroup');          
                }
                // set to true as we have encountered an array which denotes
                // an new optgroup
                $is_optgroup = true; 
                // create the new optgroup    
                $html_options .= ot('optgroup', attr_label($potential_options[$opt][1]));
                $content = $potential_options[$opt][0];
            } else $content = $potential_options[$opt];
            $attr = '';
            // add any existing style attributes to the option group
            //if ($form_style_attrs) {
            //    foreach (array_keys($form_style_attrs) as $css_attr) {
            //        $attr .= dec($css_attr, $form_style_attrs[$css_attr][$opt]);  
            //    } 
            //}        
            //return ot( 'option',  attr_value((string)$value) . attr_selected($is_selected ) . $attr)
            //. $content
            //. ct( 'option' );
            $html_options .= ot('option', 
                            attr_selected( ((string)$opt == $this->get_value()) ? true : false) . 
                            attr_value((string)$opt));
            $html_options .= $content;
            $html_options .= ct('option');
        }
        if ($is_optgroup) $html_options .= ct('optgroup');
        return $html_options;    
    }
    
    static function is_valid($value){
        return (in_array($value, static::get_options())) ? true : false;
    }
    
    //abstract static function get_options(); 
    //abstract static function set_options($options);         
    function get_options() { 
        $options = $this->options; 
        return $options;
    }
    function set_options( &$options ) { 
        $this->options = $options;
        //$opt1 = $this->options
    }  
      
}


// LIST
class List_Style_Type extends CSS_Dropdown_Input {
    static private $options = array('circle', 'decimal', 'decimal-leading-zero', 'disc', 'lower-alpha', 'lower-roman', 'none', 'square', 'upper-alpha', 'upper-roman');
    function __construct($id, $value) { 
        parent::__construct($id, 'list-style-type', 'list style type', $value);
        $this->set_options( self::$options );
    }    
}
class List_Style_Position extends CSS_Dropdown_Input {
    static $options = array('inherit', 'inside', 'outside');
    function __construct($id, $value) { 
        parent::__construct($id, 'list-style-position', 'list style position', $value);
        self::set_options( self::$options );
    }    
}

// EXPERIMENTAL

abstract class Global_Setting {}
class CSS_Setting_Group {}

class Form {
    
    function add_tab() {
    
    }
    function render_form() {
    
    }
}



