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
     function get_html();
}
interface CSS {
    function get_css();
}
interface ICSS_Selector extends CSS {
    function get_css_selector();
}
interface IValid_Input {
    function is_valid();
}
interface IValidate { static function validate($value); }
interface Tab {}
interface IComposite {}

/** 
 * Settings that depends on other settings being set,
 * eg Section_Font_Family depends on Global_Font_Family being set,
 * have slightly different dropdown menus. 
 * The options in the drop down menu also include the name of the Global setting
 * which is where the option's value originates.
 * 
 * This interface is used to identify these particular settings. 
 * */
interface ISetting_Depends_On_Global_Setting {}
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
    private $display_name;
    private $parent;
    private $children;
    
    function __construct($display_name, $children=array()) {
        $this->display_name   = $display_name;
        if ($children != null) {
            if ( is_array($children) ) {
                foreach ( $children as $child ) {
                    $this->add_child($child);
                }
            } else $this->add_child($children);
        } 
    }
    
    // GETTERS
    function get_display_name() { return $this->display_name; }
    function get_children($recurse_test=null, $valid_child_test=null) {
        $children = $this->children;
        $valid_children = array();
        if ( $recurse_test == null && $valid_child_test == null ) {
            return $children;
        }
        if( $recurse_test && $recurse_test($this) ) {
            foreach ( $children as $child ) {
                $grand_children = $child->get_children($recurse_test, $valid_child_test);
                if( $grand_children ) {
                    $valid_children = array_merge($valid_children, $grand_children);
                }
            }
        } 
        
        if( $valid_child_test && $valid_child_test($this) ) {
            $valid_children[$this->get_name()] = $this;
        }
        return $valid_children;
    }
    function has_children() {
        if($this->children) {
            return true;        
        } else {
            return false;
        }
    }
    function get_child($n)      { return $this->children[$n]; }
    function get_parent()       { return $this->parent;       }
    
    function get_parentage_array() {
        $p = $this->get_parent();
        $parents = array();
        while ($p != null) {
            array_push($parents, $p);
            $p = $p->get_parent();
        }
        return $parents;
    }
    function get_parentage_string() {
        $pa = $this->get_parentage_array();
        $str = '';
        foreach( $pa as $parent ) {
            $str = get_class($parent) . '__' . $str;
        }
        return $str;
    }
    // SETTERS
    function add_child($child)   { 
        $child->set_parent($this);
        $this->children[]   = $child;
    }
    function set_parent($parent) { $this->parent = $parent; }
    
    function to_array() {
        $contents = array();
        $children = $this->get_children();
        foreach ( $children as $child ) {
            $child_type = get_class($child);
            $key = $this->get_name();
            $contents[$key] = $child->to_array();
        }
        return $contents;
    }
    
    function get_name() {
        return get_class($this);
    }
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
class Group extends Hierarchy implements Render_As_HTML {
    function __construct($display_name, $members=array()) {
        parent::__construct($display_name, $members);
 
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
    function to_array() {
        $contents = array();
        $children = $this->get_children();
        foreach ( $children as $child ) {
            $i = 2; // used to 'uniquify' $contents's keys
            $child_type = get_class($child);
            $key = $child_type;
            while( array_key_exists($key, $contents) ) {
                $key = $child_type . '__' . $i++;
            }
            $contents[$key] = $child->to_array();
        }
        return $contents;
    }
}
class Option_Group extends Group {
    function __construct($display_name, $members=array()) {
        parent::__construct($display_name, $members);
    }  
    /**
     * Constructs an options array at runtime from its constituent settings
     */
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
                if( $child instanceof Setting && !($child instanceof IComposite ) ) {
                    $child_html = get_setting_row( $child );
                } else {
                    $child_html = $child->get_html();
                }
                $children_html .= $child_html;
            }
        }
        return table($children_html, attr_class('form-table'));
    }
}
/** 
 * This accepts different settings and can display them as one group,
 * one display name with multiple values.
 * 
 * @example the border settings has one label 'border' but multiple
 * text boxes to enter the color, style or width.
 * */
class Option_Row_Group extends Group {
    function __construct($display_name, $members=array()) {
        parent::__construct($display_name, $members);
    } 
    function get_html() {
        $children_html = '';
        $children = $this->get_children();
        if ( null != $children) {
            foreach($children as $child) {
                $children_html .= $child->get_html();  
            }
        }
        return get_row($this->get_display_name(), $children_html);
    }    
}
class Lookup_Option_Group extends Option_Group {
    /**
     * specifically created for font options
     */
    function __construct($display_name, $members=array()) {
        parent::__construct($display_name, $members);
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
abstract class Sub_Tab extends Group implements Tab {
    private $html_id;
    function __construct($display_name, $members=null) {
        parent::__construct($display_name, $members);
    }
    function get_html() {

        $o = '';
        $children_html = '';
        $children = $this->get_children();
        if ( null != $children) {
            foreach($children as $child) {
                $child_name = $child->get_display_name();
                $child_html = $child->get_html();  
                $children_html .= get_row($child_name, $child_html);
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
class Main_Tab extends Hierarchy implements Tab, Render_As_HTML {
    private $html_id;
    private $opt_group;
    protected $form_enctype = null;
    function __construct($display_name, $opt_group, $members=null) {
        parent::__construct($display_name, $members);
        $this->opt_group = $opt_group;
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
        $o .= $children_html;
        $o .= ct('div');
        return $o;
    }
    function get_html_id()       { return $this->html_id;   }
    function set_html_id($value) { $this->html_id = $value; }
} 
      
class Tab_Group extends Group {

    function __construct($display_name, $members=array()) {
        parent::__construct($display_name, $members);
    }
    
    function get_html() {
        $child_tabs_html = '';
        $links = '';
        $tabs = '';
        
        $id = $this->get_parentage_string();
        $children = $this->get_children();
        
        $count = 1;
        if (null != $children) {
            foreach($children as $child) {
                if($child instanceof Tab) {
                    $n = $count++;
                    $child->set_html_id("{$id}-tabs-" . $n);
                    $links .= li( alink('#' . $child->get_html_id(), $child->get_display_name() ) );
                    $tabs  .= $child->get_html();
                }
            }
        }
        
        $ul = ul($links);
        $script = "<script> jQuery( function() { jQuery( '#{$id}-tabs' ).tabs(); } ); </script>";
        
        $o = $script;
        $o .= div($ul . 
                 $tabs,
                  attr_id("{$id}-tabs")
                );
        return $o;
    }
}
class Main_Tab_Group extends Tab_Group {
    function __construct($display_name, $members=array()) {
        if( $members == null ) {
            $members = array(
                new Current_Save_ID(),
                new Global_Settings(), 
                new Header_Tab(), 
                new Menu_Tab(), 
                new Body_Tab(), 
                new Sidebar_Tab(),
                new Footer_Tab()
            );
        }
        parent::__construct('main tab group', $members); 
    }
    function to_array() {
        $children = parent::to_array();
        return array('cs'=>$children);
    }

//    function get_setting_values_array() {
//        $setting_values = array();
//        $settings = get_setting_instances($this);
//        foreach( $settings as $setting ) {
//            $name = $setting->get_name();
//            $value = $setting->get_value();
//            $setting_values[$name] = $value;
//        }
//        return $setting_values;
//    }
    /** 
     * Recursive function to get CSS Selector objects
     * 
     */
    function get_css_selectors($thing=null, $selectors=null) {
        if( $thing==null ) {
            return $this->get_css_selectors($this, array());
        } else if( $thing instanceof CSS_Selector) {
            $selectors[] = $thing;
            return $selectors;
        } else if ( $thing instanceof Hierarchy ) {
            $children = $thing->get_children();
            if( $children!= null ) {
                foreach ( $children as $child ) {
                    $new_selectors = $this->get_css_selectors($child, $selectors);
                    $selectors = $new_selectors;
                }
            }
            return $selectors;            
        }  
        
    }
    
    function inject_values($values) {
        //$settings = get_setting_instances($this, true);
        $recurse_test = function($child) {
            if( $child->has_children() ) {
                return true;
            } else {
                return false;
            }
        };
        $valid_child_test = function($child) {
            if ( $child instanceof Setting ) { 
                return true;
            } else {
                return false;
            }
        };
        $settings = $this->get_children($recurse_test, $valid_child_test);
        foreach( array_keys($settings) as $setting_key ) { // TODO iterate over $values instead? much smaller
            // Do a check first to see that setting key exists in the existing supplied $values
            // which will be false for new settings in new versions
            if( key_exists($setting_key, $values)) {
                $setting = $settings[$setting_key];
                $value = $values[$setting_key];
                $setting->set_value($value);
            }
        }
    }
    function get_html() {
        $o = '';
        $o .= '<form method="post" ';
        $o .= 'action="options.php">';
            $o .= get_settings_fields('artpress_options');
            
            $csi = $this->get_child(0);
            $o .= $csi->get_html();
            $save = __( 'Save Settings' );
            
            $o .= "<span class='submit'><input type='submit' class='button-primary' value='{$save}' /></span>";      
            $child_html = parent::get_html();
            $o .= $child_html;
        $o .= ct('form');
        $o .= '</p>';
        return $o;
    }
}
/**
 * Recursive function to return an array of all the setting objects.
 */
function get_setting_instances($hierarchy_obj, $unpack_composites, $settings_array=null) {
    if( !is_array($settings_array) ) {
        return get_setting_instances($hierarchy_obj, $unpack_composites, array());
    } 
    // the following test must come before the Setting test because,
    // CSS_Composite is a 'Setting' object but has children
    // therefore if we do this test first, we descend into its children
    // and get all their names
    // instead of just returning the name of the CSS_Composite object
    else if( ( $hierarchy_obj instanceof Setting ) && 
             !($hierarchy_obj instanceof IComposite) ) {
        $name = $hierarchy_obj->get_name();
        $settings_array[$name] = $hierarchy_obj;
        return $settings_array;
    } 
    // GET CHILDREN
    else if ( $hierarchy_obj instanceof Hierarchy ) {
        $children = $hierarchy_obj->get_children();
        foreach ( $children as $child ) {
            $settings_array = get_setting_instances($child, $unpack_composites, $settings_array);
        }
        return $settings_array;
    } 
}
class CSS_Selector extends Hierarchy implements Render_As_HTML, ICSS_Selector {
    private $css_selector;
    function __construct($css_selector, $display_name, $children=null) {
        $this->css_selector = $css_selector;
        if ( null == $children ) {
            $children[] = new Tab_Group('sub tab group', array(
                new Typography_Tab('typography'), 
                new Layout_Tab('layout'), 
                new Border_Tab('border'),
                new Background_Image_Tab('background'),
                new Effect_Tab('effects')
            ));
        }
        parent::__construct($display_name, $children);
    }
    function get_css_selector() { 
        return $this->get_css();
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
    function create_css_rule() {
        $full_selector = get_full_selector($this);
    }
}
/** 
 * constructs the full selector based on this elements css selector 
 * and its parents css selectors
 * 
 * this now handles multiple css selectors in one selector string
 * @param ICSS_Selector
 * 
 * @example the input with selector ".link, a" may return an array that var_dumps:
<pre>array(2) {
  [0]=>
  array(2) {
    [0]=>
    string(4) "body"
    [1]=>
    string(5) ".link"
  }
  [1]=>
  array(2) {
    [0]=>
    string(4) "body"
    [1]=>
    string(1) "a"
  }
}</pre>
 */
function get_full_selector_array($icss_selector) {
    
    // first retrieve all parent css selectors
    $parent_selectors = array();
    $parent = $icss_selector->get_parent();
    while( $parent != null ) {
        if( $parent instanceof ICSS_Selector ) {
            array_push( $parent_selectors, $parent->get_css_selector() );
        }
        $parent = $parent->get_parent();
    }
    
    // for each of the comma separated selectors
    // create a full css selector array 
    $selectors = explode(', ', $icss_selector->get_css());
    foreach( $selectors as $selector ) {
        $full_selector[] = array_merge($parent_selectors, array($selector));
    }
    return $full_selector;
    
}
/**
 * @see get_full_selector_array($icss_selector) 
 * @param array generated by the function  (above)
 */
function get_full_selector_string($selectors_array) {
    $output = '';
    $first = true;
    foreach($selectors_array as $selector_arr) {
        // intersperse with commas
        if($first) {
            $first = false;
        } else {
            $output .= ', ';
        }
        
        // build selector from array
        foreach ($selector_arr as $selector) {
            if($selector) { // don't include empty selectors
                $output .= $selector . ' ';
            }
        }
        $output = rtrim($output);
    }        
    return $output;
}


class CSS_Selector_Group extends Group implements ICSS_Selector {
    private $css_selector;
    
    function __construct($css_selector, $display_name, $children) {
        $this->css_selector = $css_selector;
        parent::__construct($display_name, $children);
    }
    function get_html() {
        $o = '<script> 
            jQuery(function() { 
                jQuery( "#' . $this->get_parentage_string() . '-accordion" ).accordion({
                    autoHeight: false,
                    collapsible: true,
                     active: false,
                    });
            });
	    </script>';
        $o .= ot('div', attr_id($this->get_parentage_string() . '-accordion'));
       
        $children = $this->get_children();
        foreach ($children as $child) {
            $child_name = $child->get_display_name();
            $link = h4( alink('#', $child_name) );
            $child_html = $child->get_html();
            $o .= $link;
            $o .= div(
                $child_html
            );
        }
        $o .= ct('div');
        return $o;
    }
    function get_css() {
        return $this->css_selector;
    }
    function get_css_selector() {
        return $this->css_selector;
    }
}
function get_setting_row( $setting ) {
    $child_name = $setting->get_display_name();
    $child_html = $setting->get_html();  
    return get_row($child_name, $child_html);
}
function get_row( $label, $content ) {
    $row = ot('tr');
    $row .= td($label, attr_style("width:200px;") );
    $row .= td($content);
    $row .= ct('tr');
    return $row;   
}
/**
 * 
 * This class represents a setting. It only introduces one new attribute: value.
 * This will be the current value of the setting.
 * @author jsd
 *
 */
abstract class Setting extends Hierarchy implements Render_As_HTML, IValidate {
    //private $identifier;
    protected $name;
    private $value;

    function __construct($display_name, $v) {
        $this->value = $v;
        parent::__construct($display_name, null);
        $this->name = get_class($this);
    }

    function get_value()       { 
        $v = $this->value;     
        return $v;
    }
    function set_value($value) {
        $class = get_called_class();
        if($class::validate($value)) {
            $this->value = $value; 
        }
    }
    
    function get_full_name() {
        $local_name = $this->get_name();
        $full_name = '[' . $local_name . ']';
        return $full_name;        
    }
    function get_html_name() {
        // TODO I don't like artpress_options being hard coded, not important for now though
        return attr_name('ap_options[cs]' . $this->get_full_name());
    }
    function get_name() { 
        return $this->name; 
    }
    function set_name($name) { $this->name = $name; }
    
    function to_array() {
        $key = $this->get_name();
        $value = $this->get_value();
        return array($key=>$value);
    }
    static function validate($value) {
        return true;
    }
}
class Current_Save_ID extends Setting {
    function __construct($value='') {
        parent::__construct('save name', $value);
    }
    function get_html() {
        $o = '';
        $name = $this->get_name();
        $o .= label($name, 'Configuration name ');
        $attrs =  attr_id($name) .
                    attr_value($this->get_value());
        $o .= input('text',  attr_name("ap_options[{$name}]") . $attrs);
        $o = p($o);
        return $o;        
    }
}
abstract class Number_Setting extends Setting {
    function __construct($display_name, $value='') {
        parent::__construct($display_name, $value);        
    }
    static function validate($value) {
         return is_numeric($value);
    }
    function get_html($attributes='') {
        $input = input('text', $this->get_html_name() . attr_value($this->get_value()) . $attributes);
        return $input;        
    }     
}
/** 
 * This setting contains a bunch of different sub settings.
 * If this setting is on, the sub settings will be used, 
 * otherwise the sub setting's values will be ignored,
 * eg the won't be use to generate CSS
 * */
abstract class Toggle_Group extends Setting implements IComposite {
    static $toggles;
    
    static function get_toggles() {
        return self::$toggles;
    }
    
    function __construct($display_name, $on, $members=array()) {
        foreach ($members as $child) {
            $this->add_child($child);
        }
        parent::__construct($display_name, $on);
        $toggles[$this->get_name()] = $this;
    }
    function get_html() {
        $html_name = $this->get_html_name();
        $value = $this->get_value();
        $html = '';
        $html = 
                input('hidden', $html_name . attr_value('0')) .
                input('checkbox', $html_name . attr_value($value) . attr_checked($value));
            //. td( ht_input_checkbox( $id, $value , 'on')
            //. ht_label ('description', $id, $field_blurb) );
        $children = parent::get_children();
        if ( null != $children ) {
            foreach($children as $child) {
                $html .= get_setting_row( $child );  
            }
        }
        return $html;
    } 
    function get_name() { 
        $name = '';
        $parents = $this->get_parentage_array();    
        foreach ( $parents as $parent ) {
            if( $parent instanceof CSS ) {
                $name =  $parent->get_css() . '__' . $name;
            }
        }
        $name .= parent::get_name();
        return $name; 
    }
    function set_value($value) {
        parent::set_value($value);
    }
    function is_on() {
        if( $this->get_value() ) { 
            return true;
        } else { 
            return false;
        }
    }
    
}
function create_css_declaration($css_setting) {
    $value = $css_setting->get_css_value();
    if ($value) {
        return dec($css_setting->get_css_property(), $value);
    } else return '';
}
/**
 * 
 * This class represents settings whose values will ultimately be rendered to css
 * @author jsd
 *
 */
abstract class CSS_Setting extends Setting implements CSS {
    private $css_property;
    
    function __construct($css_property, $display_name, $value) {
        $this->css_property = $css_property;
        parent::__construct($display_name, $value);
    }
    function get_css_declaration() {
        return create_css_declaration($this);
    }
    function get_css() {
        return $this->css_property;
    }
    function get_css_value() {
        return $this->get_value();
    }
    function get_name() { 
        $name = '';
        $parents = $this->get_parentage_array();    
        foreach ( $parents as $parent ) {
            if( $parent instanceof CSS ) {
                $name =  $parent->get_css() . '__' . $name;
            }
        }
        $name .= $this->get_css();
        return $name; 
    }
    function get_css_property() {
        return $this->css_property;
    }
    function set_css_property($property_name) {
        $this->css_property = $property_name;
    }
}

class CSS_Composite extends CSS_Setting implements IComposite {  
    /** 
     * @param $members 
     * array The css_property of each supplied member is merely used as a unique identifer.
     * It needn't be an actual css property. Indeed this class was created because for 
     * certain css properties like 'box shadow blur radius' or 'text box color' there is no
     * unique css property.
     * 
     * */
    function __construct($display_name, $common_css_property, $members=array()) {
        foreach ($members as $child) {
            //$this_css_property = $common_css_property;
            //$child_css_property = $child->get_css_property();
            //$new_property = $this_css_property . '-' . $child_css_property;
            //$child->set_css_property($new_property);
            $this->add_child($child);
        }
        parent::__construct($common_css_property, $display_name, null);
    }
    function get_css_value() {
        $value = '';
        foreach($this->get_children() as $child) {
            $value .= ' ' . $child->get_css_value();
        }
        return $value;
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
    function get_css_declaration() {
        $property = $this->get_css_property();
        
        $values = '';
        foreach( $this->get_children() as $child ){
            $css_value = $child->get_css_value();
            if($css_value) {
                $values .= ' ' . $css_value;            
            }
        }
        if($values) {
            return "\n{$property}:{$values};";
        }
    }
}

abstract class CSS_Text_Input extends CSS_Setting {
    function __construct($css_property, $display_name, $value) {
        parent::__construct($css_property, $display_name, $value);        
    }
    function get_html($attributes = '') {
        $input = input('text', $this->get_html_name() . attr_value($this->get_value()) . $attributes);
        return $input;
    }
}
abstract class CSS_Size_Text_Input extends CSS_Text_Input {
    function __construct($css_property, $display_name, $value='') {
        parent::__construct($css_property, $display_name, $value);        
    }
    
    static function validate($value) {
         if(is_valid_size_string($value)) return true; 
         else return false;
    }
}
abstract class CSS_Horizontal_Position_Text_Input extends CSS_Size_Text_Input {
    function __construct($css_property, $display_name, $value='') {
        parent::__construct($css_property, $display_name, $value);        
    }
    
    function is_valid() {
        $value = $this->get_value();

        if(parent::is_valid() || in_array($value, array('left', 'center', 'right'))) {
            return true; 
        } else {
            return false;
        }
    }
}
abstract class CSS_Vertical_Position_Text_Input extends CSS_Size_Text_Input {
    function __construct($css_property, $display_name, $value='') {
        parent::__construct($css_property, $display_name, $value);        
    }
    
    function is_valid() {
        $value = $this->get_value();
         if(parent::is_valid() || in_array($value, array('top', 'center', 'bottom'))) {
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
    private static $options;
    
    function __construct($css_property, $display_name, $value=0) { 
        parent::__construct($css_property, $display_name, $value);
    }
    
    function get_html() {
        $select = ot('select', $this->get_html_name());
        $select .= $this->get_html_options();
        $select .= ct('select');
        return $select;
    }
    private function get_html_options() {
        $html_options = '';        
        $is_optgroup = false;
        $content = '';
        $potential_options = static::get_options();
        $i = 1;
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
                            attr_selected( ((string)$opt == $this->get_value()) ? true : false ) . 
                            attr_value((string)$opt));
            if($content && ($this instanceof ISetting_Depends_On_Global_Setting) ) {                            
                $html_options .= $i++ . '&nbsp; &nbsp; ';
            }
            $html_options .= $content;
            $html_options .= ct('option');
        }
        if ($is_optgroup) $html_options .= ct('optgroup');
        return $html_options;    
    }
    
    static function validate($value) {
        $options = static::get_options();
        $size = sizeof($options);
        if( ($value < $size) && ($value >= 0) ) {
            return true;         
        } else {
            return false;
        }
    }
       
    static function get_options() { 
        return static::$options;
    }
    
    function get_css_value() {
       $value = $this->get_value();
       $options = static::get_options();
       return $options[$value];
   }
}

// LIST
class List_Style_Type extends CSS_Dropdown_Input {
    static $options = array('circle', 'decimal', 'decimal-leading-zero', 'disc', 'lower-alpha', 'lower-roman', 'none', 'square', 'upper-alpha', 'upper-roman');
    function __construct($value) { 
        parent::__construct('list-style-type', 'list style type', $value);
        $this->set_options( self::$options );
    }    
}
class List_Style_Position extends CSS_Dropdown_Input {
    static $options = array('inherit', 'inside', 'outside');
    function __construct($value) { 
        parent::__construct('list-style-position', 'list style position', $value);
    }    
}
