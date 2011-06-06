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
    private $name;
    private $parent;
    private $children;
    
    function __construct($name, $parent=null, $children=array()) {
        $this->parent = $parent;
        $this->name   = $name;
        //$this->children = $children;
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
    //protected $members = array(); // TODO should this just be $children?
    
    function __construct($name, $parent, $members=array()) {
        parent::__construct($name, $parent, $members);
//       foreach ($members as $mem) {
//           $this->add_child($mem);
//       }    
    }
//   function add_child($member) {
//       //echo "Group add_member";
//       $member_name = $member->get_name();
//       $this->children[$member_name] = $member;
//       
//       // the group's parent and the group's members' parents are the same object
//       // (see the class documentation)
//       $member->set_parent($this->get_parent());
//   }
//   function get_members() {
//       return $this->get_children();
//   }
    function get_html() {
        $o = '';
        $children = $this->get_children();
        foreach($children as $child) {
            $o .= $child->get_html();    
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
    function get_options() {
        $options = array();
        foreach ($this->get_children() as $member) {
            //$member_name = $member->get_name();
            $options[] = $member->get_value();
        }
        return $options;
    }   
}
class Tab extends Group  {
    private $id;
    function __construct($name, $parent, $members=null, $id=null) {
        parent::__construct($name, $parent, $members);
        $this->id = $id;
    }
    function get_html() {
        $table = table( parent::get_html(), attr_class('form-table') );
        $o     = div( $table, attr_id( $this->get_id() ) );
        return $o;
    }
    function get_id()       { return $this->id;   }
    function set_id($value) { $this->id = $value; }
}
class Tab_Group extends Group {

    function __construct($name, $parent, $members=array()) {
        parent::__construct($name, $parent, $members);
    }
    
    function get_html() {
        $child_tabs_html = '';
        $links = '';
        $tabs = '';
        
        $parent_name = $this->get_parent()->get_name();
        $children = $this->get_children();
        
        $count = 1;
        foreach($children as $child) {
            $child->set_id("tabs-" . $count++);
            //$child_tabs_html = parent::get_html();
            $links .= li( alink('#' . $child->get_id(), $child->get_name() ) );
            $tabs  .= $child->get_html();
        }
        
        $ul = ul($links);
        $script = "<script> $( function() { $( '#{$parent_name}-tabs' ).tabs(); } ); </script>";
        
        $o = $script;
        $o .= div($ul . 
                 $tabs,
                  attr_id("{$parent_name}-tabs")
                );
        return $o;
    }
}
class CSS_Selector extends Hierarchy implements Render_As_HTML {
    private $css_selector;
    function __construct($css_selector, $name, $parent, $children) {
        parent::__construct($name, $parent, $children);
        $this->css_selector = $css_selector;
    }
    function get_css_selector() {
        $parent = $this->parent;
        $parent_css = $parent->get_css_selector();
        return $parent_css . ' ' . $css_selector;
    }
    function get_html() {
        $o = h4($this->get_name());
        foreach($this->get_children() as $child) {
            $o .= $child->get_html();    
        }
        return div($o);   
    }
}
class CSS_Selector_Group extends CSS_Selector {
    function __construct($css_selector, $name, $parent, $children) {
        parent::__construct($css_selector, $name, $parent, $children);
    }
    function get_html() {
        $o = '<script> $(function() { $( "#accordion" ).accordion(); }); </script>';
        $o .= ot('div', attr_id('accordion'));
        
        $children = $this->get_children();
        foreach ($children as $child) {
            $name = $child->get_name();
            $link = h4( alink('#', $name) );
            $child_html = $child->get_html();
            $o .= $link;
            $o .= div($child_html);
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

    function __construct($name, $parent, $v) {
        $this->value = $v;
        parent::__construct($name, $parent, null);
        //set_value($v);
    }

    function get_value()       { return $this->value; }
    function set_value($value) { $this->value = $value; }
    
    private function get_qualifier() {
        $qualifier = '';
        foreach($this::get_ancestory() as $part) $qualifier = "[{$part->get_name()}]" . $qualifier;
        return $qualifier;
    }
    
    function get_html_name() {
        //TODO add option group name eg artpress_theme_options
        return attr_name($this->get_qualifier() . '[' . $this->get_name() . ']');
    }
    
    function create_form_row( $input ) {
        $o = ot('tr');
        $o .= td($this->get_name());
        $o .= td($input);
        $o .= ct('tr');
        return $o;
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
    
    function __construct($css_property, $name, $parent, $value) {
       // echo var_dump(func_get_args());
       $this->css_property = $css_property;
       parent::__construct($name, $parent, $value);
    }
    function get_css_declaration() {
        $name = $this->get_name();
        $value = $this->get_value();
        if ($value) return "\n" . $name . ": " . $value . ";";
        else return '';
    }
}


abstract class CSS_Text_Input extends CSS_Setting {
    function __construct($css_property, $name, $parent, $value) {
        parent::__construct($css_property, $name, $parent, $value);        
    }
    
    function get_html() {
        $input = input('text', $this->get_html_name() . attr_value($this->get_value()));
        $o = parent::create_form_row( $input );
        return $o;
    }
}
abstract class CSS_Size_Text_Input extends CSS_Text_Input {
    function __construct($css_property, $name, $parent, $value) {
        parent::__construct($css_property, $name, $parent, $value);        
    }
    
    static function is_valid($value) {
         if(is_valid_size_string($value)) return true; 
         else return false;
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
    
    function __construct($css_property, $name, $parent, $value) { 
        parent::__construct($css_property, $name, $parent, $value);
    }
    
    function get_html() {
        $select = ot('select', $this->get_html_name());
        $select .= $this->get_html_options();
        $select .= ct('select');
        $o = parent::create_form_row( $select );
        return $o;
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
        return $this->options; 
    }
    function set_options( &$options ) { 
        $this->options = $options;
        //$opt1 = $this->options
    }  
      
}


// LIST
class List_Style_Type extends CSS_Dropdown_Input {
    static private $options = array('circle', 'decimal', 'decimal-leading-zero', 'disc', 'lower-alpha', 'lower-roman', 'none', 'square', 'upper-alpha', 'upper-roman');
    function __construct($parent, $value) { 
        parent::__construct('list-style-type', 'list style type', $parent, $value);
        $this->set_options( self::$options );
    }    
}
class List_Style_Position extends CSS_Dropdown_Input {
    static $options = array('inherit', 'inside', 'outside');
    function __construct($parent, $value) { 
        parent::__construct('list-style-position', 'list style position', $parent, $value);
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



