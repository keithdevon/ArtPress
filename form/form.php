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
 * IDENTITY PROBLEM
 * 
 * I need a way to uniquely identify certain form elements for reasons of backwards compatibility
 * Also I would like a way to identify each form element so that 
 * I can use this information to store the form hierarchy in the db,
 * which will allow me to quickly recreate the form from the saved settings.
 * 
 * For the purposes of backwards compatability, uniques arises from a combination of the 
 * css selectors and css property 
 * 
 * For the form hierarchy ..
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

interface CSS {
    function get_css();
}

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
    
    function __construct($name, $children=array()) {
        $this->name   = $name;
        if ($children != null) {
            if ( is_array($children) ) {
                foreach ( $children as $child ) {
                    $this->add_child($child);
                }
            } else $this->add_child($children);
        } 
        $this->id     = $this->construct_id();
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
        $ancestory = $this->get_ancestory(); 
        foreach ( $ancestory as $ancestor ) {
            $id .= '_' . get_class($ancestor);
        }
        $this->id = $id;
    }
    
    // SETTERS
    function add_child($child)   { 
        $child->set_parent($this);
        $child->construct_id();
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
    function __construct($name, $members=array()) {
        parent::__construct($name, $members);
 
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
    function __construct($name, $members=array()) {
        parent::__construct($name, $members);
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
    function __construct($name, $members=array()) {
        parent::__construct($name, $members);
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
    function __construct($name, $members=array()) {
        parent::__construct($name, $members);
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
    function __construct($name, $members=null) {
        parent::__construct($name, $members);
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
class Main_Tab extends Hierarchy implements Render_As_HTML {
    private $html_id;
    function __construct($name, $members=null//,  $html_id=null
                            ) {
        parent::__construct($name, $members);
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

    function __construct($name, $members=array()) {
        parent::__construct($name, $members);
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
    function __construct($name, $members=array()) {
    
        $globalsettings = new Global_Settings();
   
        //$h2 = new CSS_Selector('h2', 'header 2');
        $h2 = new H2();
        $h3 = new H3();
        $h4 = new H4();   
        $body = new CSS_Selector_Group('body', 'body', array($h2, $h3, $h4));
        $bodytab = new Main_Tab('body tab', $body);
        
        parent::__construct($name, array($bodytab, $globalsettings));
    }
}
class CSS_Selector extends Hierarchy implements Render_As_HTML, CSS {
    private $css_selector;
    function __construct($css_selector, $name, $children=null) {
        $this->css_selector = $css_selector;
        if ( null == $children ) {
            $t1 = new Typography_Tab('typography');
            $t2 = new Layout_Tab('layout'); 
            $t3 = new Background_Image_Tab('background');
            $t4 = new Effect_Tab('effects');
            $children[] = new Tab_Group('sub tab group', array($t1, $t2, $t3, $t4));
        }
        parent::__construct($name, $children);
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
    function get_css() {
        return $this->css_selector;
    }
}
class CSS_Selector_Group extends CSS_Selector {
    function __construct($css_selector, $name, $children) {
        parent::__construct($css_selector, $name, $children);
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

    function __construct($name, $v) {
        $this->value = $v;
        parent::__construct($name, null);
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
abstract class CSS_Setting extends Setting implements CSS {
    private $css_property;
    
    function __construct($css_property, $name, $value) {
       // echo var_dump(func_get_args());
       $this->css_property = $css_property;
       parent::__construct($name, $value);
    }
    function get_css_declaration() {
        $name = $this->get_name();
        $value = $this->get_value();
        if ($value) return "\n" . $name . ": " . $value . ";";
        else return '';
    }
    function get_css() {
        return $css_property;
    }
}
abstract class CSS_Text_Input extends CSS_Setting {
    function __construct($css_property, $name, $value) {
        parent::__construct($css_property, $name, $value);        
    }
    function get_html() {
        $input = input('text', $this->get_html_name() . attr_value($this->get_value()));
        //$o = $this->create_form_row( $input );
        return $input;
    }
}
abstract class CSS_Size_Text_Input extends CSS_Text_Input {
    function __construct($css_property, $name, $value='') {
        parent::__construct($css_property, $name, $value);        
    }
    
    static function is_valid($value) {
         if(is_valid_size_string($value)) return true; 
         else return false;
    }
}
abstract class CSS_Horizontal_Position_Text_Input extends CSS_Size_Text_Input {
    function __construct($css_property, $name, $value='') {
        parent::__construct($css_property, $name, $value);        
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
    function __construct($css_property, $name, $value='') {
        parent::__construct($css_property, $name, $value);        
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
    
    function __construct($css_property, $name, $value=0) { 
        parent::__construct($css_property, $name, $value);
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
    function __construct($value) { 
        parent::__construct('list-style-type', 'list style type', $value);
        $this->set_options( self::$options );
    }    
}
class List_Style_Position extends CSS_Dropdown_Input {
    static $options = array('inherit', 'inside', 'outside');
    function __construct($value) { 
        parent::__construct('list-style-position', 'list style position', $value);
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



