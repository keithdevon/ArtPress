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
require_once '../html-gen.php';
require_once '../heart-theme-utils.php';

interface Render_As_HTML {
     public static function is_valid($value);
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
    private $name;
    private $parent;
    private $children;
    
    function __construct($name, $parent=null, $children=array()) {
        $this->parent = $parent;
        $this->name   = $name;
        $this->children = $children; 
    }
    
    // GETTERS
    public final function get_name()     { return $this->name;     }
    public final function get_children() { return $this->children; }
    public final function get_parent()   { return $this->parent;   }
    public function get_ancestory() {
        $p = $this->parent;
        $ancestors = array();
        while ($p != null) {
            array_push($ancestors, $p);
            $p = $p->get_parent();
        }
        return $ancestors;
    }    
    
    // SETTERS
    public function add_child($child)   { $children[]   = $child;  }
    public function set_parent($parent) { $this->parent = $parent; }
    

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
class Group  extends Hierarchy {
    //protected $members = array(); // TODO should this just be $children?
    
    function __construct($name, $parent) {
        $args = func_get_args();
        parent::__construct($name, $parent, null);
        $this->name = $name;
        foreach (array_slice($args, 2) as $arg) {
            $this->add_member($arg);
        }    
    }
    public function add_member($member) {
        //echo "Group add_member";
        $member_name = $member->get_name();
        $this->children[$member_name] = $member;
        
        // the group's parent and the group's members' parents are the same object
        // (see the class documentation)
        $member->set_parent($this->get_parent());
    }
    public function get_members() {
        return $this->get_children();
    }
}
class Option_Group extends Group {
    /**
     * Constructs an options array on the fly from its constituent settings
     * The settings names are the keys of the array 
     * and the settings values are the values of the array.
     */
    public function get_options() {
        $options = array();
        foreach ($this->members as $member) {
            //$member_name = $member->get_name();
            $options[] = $member->get_value();
        }
        return $options;
    }   
}
class Tab_Group extends Group implements Render_As_HTML {
    function get_html() {
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

    public function get_value()       { return $this->value; }
    public function set_value($value) { $this->value = $value; }
    
    private function get_qualifier() {
        $qualifier = '';
        foreach($this::get_ancestory() as $part) $qualifier = "[{$part}]" . $qualifier;
        return $qualifier;
    }
    
    public function get_html_name() {
        //TODO add option group name eg artpress_theme_options
        return attr_name($this->get_qualifier() . '[' . $this->get_name() . ']');
    }
    
    //public abstract function get_html();

    
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
    public function get_css_declaration() {
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
    
    public function get_html() {
        return input('text', $this->get_html_name() . attr_value($this->get_value()));
    }
}
abstract class CSS_Size_Text_Input extends CSS_Text_Input {
    function __construct($css_property, $name, $parent, $value) {
        parent::__construct($css_property, $name, $parent, $value);        
    }
    
    public static function is_valid($value) {
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
    
    private static $options;
    
    function __construct($css_property, $name, $parent, $value) { 
        parent::__construct($css_property, $name, $parent, $value);
    }
    
    public function get_html() {
        $o = ot('select', $this->get_html_name());
        $o .= $this->get_html_options();
        $o .= ct('select');
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
    
    public static function is_valid($value){
        return (in_array($value, static::get_options())) ? true : false;
    }
    
    public static function get_options() { return self::$options; }
    
    public static function set_options($options) {
        if(!self::$options) self::$options = $options;        
    }
}


// LIST
class List_Style_Type extends CSS_Dropdown_Input { 
    function __construct($parent, $value) { 
        parent::__construct('list-style-type', 'list style type', $parent, $value);
        self::set_options(array('circle', 'decimal', 'decimal-leading-zero', 'disc', 'lower-alpha', 'lower-roman', 'none', 'square', 'upper-alpha', 'upper-roman'));
    }    
}
class List_Style_Position extends CSS_Dropdown_Input { 
    function __construct($parent, $value) { 
        parent::__construct('list-style-position', 'list style position', $parent, $value);
        self::set_options(array('inherit', 'inside', 'outside'));
    }    
}

// EXPERIMENTAL
class CSS_Selector_Group {
    private $css_selector;
    function get_css_selector() { return $this->css_selector; }
}
abstract class Global_Setting {}
class CSS_Setting_Group {}
class Tab {}
class Form {
    
    function add_tab() {
    
    }
    function render_form() {
    
    }
}



