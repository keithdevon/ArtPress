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

interface Render_As_HTML            { function get_html(); }
interface CSS                       { function get_css(); }
interface IHas_CSS_Value            { function get_css_value(); }
interface ICSS_Selector extends CSS { function get_css_selector(); }
interface IValid_Input              { function is_valid(); }
interface IValidate                 { function validate($value); }
interface Tab                       { function get_link_html($attributes=null); }
interface IComposite                {}
interface IComposite_Part           {}
interface IToggle_Group             { function is_on(); }
interface Visitor {
    function recurse($hierarchy);
    function valid_child($hierarchy);
}

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

/** Some settings like (and perhaps only) the Global settings have dependents
 * insomuch that if the global setting values change, the displayed html values of the
 * dependents also have to change
 * eg section colors and fonts are dependent on global colors and fonts.
 *
 *  These kinds of settings should implement this interface, to ensure that these
 *  settings have knowledge of their dependents so that the dependents can be updated
 *  if the */
interface IHas_Dependents{
    function add_dependent($setting);
    function get_dependents();
}
function get_html_dependents($setting) {
    $the_class = get_class($setting);
    $o = "\n<script type=\"text/javascript\">\n";
    $o .= "dependentsOf_{$the_class} = new Array(";
    $arr = '';
    foreach($setting->get_dependents() as $dep) {
        //$o .= input('hidden', attr_class($setting->get_name()'') . attr_value($dep->get_name()));
        $name = $dep->get_name();
        $arr .= "'${name}',";
    }
    //$size = count($arr);
    $names  = substr($arr, 0, strlen($arr) -1);
    
    $o .= "{$names});</script>";
    return $o;
}
//class Attributes {
//    private $attributes;
//    function __construct() {
//        $attributes = func_get_args();
//    }
//    function addAttributes() {
//        $attributes = array_merge($attributes, func_get_args());
//    }
//}
//abstract class Attribute {
//    private $values;
//    function __construct() {
//        $values = func_get_args();
//    }
//    function addValues() {
//         $values = array_merge($values, func_get_args());
//    }
//    function getValues() { return $values; }
//    function getValuesAsString() { return join(' ', $values); }
//    abstract function getHTML();
//}
//class ClassAttr extends Attribute {
//    function getHTML() { return attr_class($this->getValuesAsString() ); }
//}
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
    //private $attributes;

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
    //function __construct() {
    //    $attributes = func_get_args();
    //}
    function addAttributes() {
        $attributes = array_merge($attributes, func_get_args());
    }
    function get_display_name() { return $this->display_name; }
    function set_tool_tip    () { return $this->tool_tip; }

    function get_children($visitor=null) {
        $children = $this->children;
        $valid_children = array();
        if ( $visitor == null ) {
            return $children;
        }
        if( $visitor && $visitor->recurse($this) ) {
            foreach ( $children as $child ) {
                $grand_children = $child->get_children($visitor);
                if( $grand_children ) {
                    $valid_children = array_merge($valid_children, $grand_children);
                }
            }
        }

        if( $visitor && $visitor->valid_child($this) ) {
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
    function get_parent()       {
        $parent = $this->parent;
        return $parent;
    }

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
        $children_html = colgroup(5);
        $children = $this->get_children();
        if ( null != $children) {
            foreach($children as $child) {
                // don't create a complete form row if this is child is one setting of many
                if( $child instanceof Setting && !($child instanceof IComposite ) ) {
                    $child_html = get_setting_row( $child );
                } else {
                    $child_html = $child->get_html();
                }
                $children_html .= $child_html;
            }
        }
        //return table($children_html, attr_class('form-table'));
        return $children_html;
        
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
                $children_html .= td($child->get_html());
            }
        }
        return tr( td($this->get_display_name()) . $children_html );
    }
}
function get_link_html($tab, $attributes=null) {
    $link_attrs = ToolTips::get($tab) . $attributes;
    return "\n" . li( alink('#' . $tab->get_html_id(), $tab->get_display_name() ) , $link_attrs );
}
abstract class Sub_Tab extends Group implements Tab {
    private $html_id;
    function __construct($display_name, $members=null) {
        parent::__construct($display_name, $members);
    }
    function get_html() {
        //global $page_edit_config;
        //add_action('admin_footer-' . $page_edit_config, __CLASS__ . "::script");
        $o = '';
        $children_html = '';
        $children = $this->get_children();
        if ( null != $children) {
            foreach($children as $child) {
                $child_name = $child->get_display_name();
                $child_html = $child->get_html();
                $children_html .= row( td($child_name) . $child_html);
            }
        }

        if ($children[0] instanceof Setting) {
            $children_html = table( colgroup(5) . $children_html, attr_class('form-table') ); 
        }
        $id = $this->get_html_id();
        $o = div( table($children_html, attr_class('form-table')), attr_id( $id ) . attr_class('sub-tab') // TODO =, not .= ??
        );
        return $o;
    }
    function get_html_id()       { return $this->html_id;   }
    function set_html_id($value) { $this->html_id = $value; }
    function get_link_html($attributes=null) { return get_link_html($this, $attributes //. attr_class("sub-tab") 
    ); }
    //function script() {}
}
abstract class Main_Tab extends Hierarchy implements Tab, Render_As_HTML {
    private $html_id;
    private $opt_group;
    protected $form_enctype = null;
    function __construct($display_name, $opt_group, $members=null) {
        parent::__construct($display_name, $members);
        $this->opt_group = $opt_group;
    }
    function get_html($pre=null, $post=null) {
        $children_html = '';
        $children = $this->get_children();
        if ( null != $children) {
            foreach($children as $child) {
                $children_html .= $child->get_html();
            }
        }
        $o = ot( 'div', attr_id( $this->get_html_id() ) . attr_class("main-tab"));
        $o .= $pre;
        $o .= $children_html;
        $o .= $post;
        $o .= ct('div');
        return $o;
    }
    function get_html_id()       { return $this->html_id;   }
    function set_html_id($value) { $this->html_id = $value; }
    function get_link_html($attributes=null) { 
        $name = $this->get_display_name();
        return get_link_html($this, //attr_on_mouse_down("alert('updating dependents');updateDependents(getOpenAccordion('{$name}')); return;") .
         $attributes);
    }
}

abstract class Tab_Group extends Group {

    private $onmousedown;

    function __construct($display_name, $members=array()) {
        parent::__construct($display_name, $members);
    }

    //function set_on_mouse_down($fun) { $this->onmousedown = $fun; }
    //function get_on_mouse_down()     { 
    //    if ($md = $this->onmousedown) {
    //        $attr = attr_on_mouse_down($md); 
    //         return $attr;
    //    }
    //}

    function get_html() {
        //global $page_edit_config;
        //add_action('admin_footer-' . $page_edit_config, __CLASS__ . "::script");

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
                    //$link_attrs = ToolTips::get($child) . attr_class(get_class($this)) /*. attr_on_mouse_down("jQuery(this).mousedown(function() { alert('tab'); } );")*/;
                    $links .= $child->get_link_html(); //$this->get_on_mouse_down());//li( alink('#' . $child->get_html_id(), $child->get_display_name() ) , $link_attrs );
                    $tabs  .= $child->get_html();
                }
            }
        }

        $ul = ul($links);
        $script = "\n<script type='text/javascript'> jQuery( function() { jQuery( '#{$id}-tabs' ).tabs(); } ); </script>";

        $o = $script;
        $o .= div($ul .
            $tabs,
            attr_id("{$id}-tabs")
        );
        return $o;
    }

}
class Sub_Tab_Group extends Tab_Group {
    function __construct($display_name, $members=array()) {
        if( $members == null ) {
            $members = array(
                new Typography_Tab('typography'),
                new Layout_Tab('layout'),
                new Border_Tab('border'),
                new Background_Image_Tab('background'),
                new Effect_Tab('effects')
            );
        }
        parent::__construct('main tab group', $members);
    }
}
class Configuration extends Tab_Group {
    function __construct($display_name, $members=array()) {
        if( $members == null ) {
            $members = array(
            new Global_Settings(),
            new Header_Tab(),
            new Menu_Tab(),
            new Body_Tab(),
            new Sidebar_Tab(),
            new Footer_Tab(),
            new Gallery_Tab()
            );
        }
        //$this->set_on_mouse_down("mainTabClick(this)");
        parent::__construct('main tab group', $members);
    }
    function to_array() {
        $children = parent::to_array();
        return array('cs'=>$children);
    }

    function inject_values($values) {
        if($values) {
            $settings = Setting::get_registered_settings();
            foreach( array_keys($settings) as $setting_key ) { 
                // FIXME ^ iterate over $values instead? much smaller
                // Do a check first to see that setting key exists in the existing supplied $values
                // which will be false for new settings in new versions
                $exists = key_exists($setting_key, $values);
                if( $exists ) {
                    $setting = $settings[$setting_key];
                    $value = $values[$setting_key];
                    $setting->set_value($value);
                }
            }
        }
    }

    function get_html() {
        global $page_edit_config;
        //add_action('admin_footer-' . $page_edit_config, __CLASS__ . "::script");

        // create tabs
        $tab_links = '';
        $form_tabs_content = '';
        $id = $this->get_parentage_string(); //TODO remove $id
        $children = $this->get_children();

        $count = 1;
        if (null != $children) {
            foreach($children as $child) {
                if($child instanceof Tab) {
                    $n = $count++;
                    $child->set_html_id("{$id}-tabs-" . $n);
                    $tab_links .= $child->get_link_html();
                    $form_tabs_content  .= $child->get_html();
                }
            }
        }
        
        // create form
        
        $form_tabs = ul( $tab_links );
        $form_tab_bodies = div($form_tabs . $form_tabs_content, attr_id("{$id}-tabs") );
        $script = "\n<script type='text/javascript'> jQuery( function() { jQuery( '#{$id}-tabs' ).tabs(); } ); </script>"; 
        return $script . $form_tab_bodies;
    }
    
    static function script() {?><script type='text/javascript'>
 
        </script><?php
    }
    static function get_current_configuration_settings($options=null) {
        if($options == null) $options = get_option('ap_options');
        if( $current_save_id = $options['current-save-id'] )  {
            //if( $config = $options['configurations'][$current_save_id[0]][$current_save_id[1]] ) {
            //    return $config;              
            //}
            return $options['configurations'][$current_save_id[0]][$current_save_id[1]];
        }
    }
}
/**
 * Recursive function to return an array of all the setting objects.
 */
function get_setting_instances($hierarchy_obj, $unpack_composites, $settings_array=null) {
    if( !is_array($settings_array) ) {
        return get_setting_instances($hierarchy_obj, $unpack_composites, array());
    }
    // the following test must come before the Setting test because
    // Settings are normally leaf nodes in this tree however
    // CSS_Composite is a 'Setting' object and has children
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
    static private $css_selectors = array();
    private $css_selector;
    function __construct($css_selector, $display_name, $children=null) {
        $this->css_selector = $css_selector;
        if ( null == $children ) {
            $children[] = new Sub_Tab_Group('Sub Tab Group', null);
        }
        parent::__construct($display_name, $children);
        self::$css_selectors[] = $this;
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
    static function get_css_selectors() {
        $selectors = self::$css_selectors;
        return $selectors;
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
        $the_class = get_class($this);
        $parentage_string = $this->get_parentage_string();
        $o = "\n<script type='text/javascript'>\n" .
        /* The following functions are used to record which accordion is currently open.
         * This is necessary as updates to the contents of accordions are normally performed
         * only when clicking the accordion's title to open it.
         * However if the accordion is already open and its contents visible
         * we need to ensure they're still updated */
        "jQuery(function() {
                jQuery( \"#{$parentage_string}-accordion\" ).accordion({
                    autoHeight: false,
                    collapsible: true,
                     active: false,
                    });
            });
	    </script>";
        $o .= ot('div', attr_id($this->get_parentage_string() . '-accordion'));

        $children = $this->get_children();
        foreach ($children as $child) {
            $child_name = $child->get_display_name();
            $onclick = attr_on_click("accordionClick(this)");
            $link = h4( alink('#', $child_name), ToolTips::get($child) . $onclick
            );
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
    //return get_row($child_name, $child_html);
    return row( td($child_name) . td($child_html) );
}
function get_row( $label, $content ) {
    $row = ot('tr');
    $row .= td($label );
    $row .= td($content);
    $row .= ct('tr');
    return $row;
}

/** 
 * Hacky function to include column headers for settings that are displayed in a tabular form.
 * */
class Column_Header extends Hierarchy {
    private $column_header;
    function __construct($column_header) {
        $this->column_header = $column_header;
    }
    function get_html() {
        return $this->column_header;
    }
}

