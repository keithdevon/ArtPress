<?php 
/**
 *
 * This class represents settings whose values will ultimately be rendered to css
 * @author jsd
 *
 */
abstract class CSS_Setting extends Setting implements CSS {

    function __construct($css_property, $display_name, $value) {
        $this->css_property = $css_property; // FIXME does this do anything
        parent::__construct($css_property, $display_name, $value);
    }
    function get_css_declaration() {
        return create_css_declaration($this);
    }
    function get_css() {
        return $this->get_css_property();
    }
    function get_css_value() {
        return $this->get_value();
    }
    function get_differentiator() {
        return $this->get_css_property();
    }
    function get_css_property() {
        return $this->name;
    }
}

abstract class CSS_Composite extends CSS_Setting implements IComposite {
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
            $css_value = $child->get_value();
            if($css_value) {
                $values .= ' ' . $css_value;
            }
        }
        if($values) {
            return "\n{$property}:{$values};";
        }
    }
}
function get_text_input_html($setting, $attributes='') {
    $html_name = $setting->get_html_name();
    $value = attr_value($setting->get_value());
    $i = input('text', ToolTips::get($setting) . $html_name . $value . $attributes);
    return $i;
}
function get_size_text_input_html($setting, $attributes=''){
    return get_text_input_html($setting, $attributes . attr_class('size') .
    attr_on_change('checkValidSize(this);'));
}
abstract class Setting_Text extends Setting {
    function __construct($name, $display_name, $value='') {
        parent::__construct($name, $display_name, $value);
    }
    function get_html($attributes='') {
        return get_text_input_html($this, $attributes);
    }
}
abstract class CSS_Text_Input extends CSS_Setting {
    function __construct($css_property, $display_name, $value) {
        parent::__construct($css_property, $display_name, $value);
    }
    function get_html($attributes='') {
        return get_text_input_html($this, $attributes);
    }
}
abstract class Setting_Size_Text_Input extends Setting_Text {
    function __construct($name, $display_name, $value='') {
        parent::__construct($name, $display_name, $value);
    }
    function validate($value) {
        return is_valid_size_string($value);
    }
    function get_html($attributes='') {
        return get_size_text_input_html($this, $attributes);
    }
}
abstract class CSS_Size_Text_Input extends CSS_Text_Input {
    function __construct($css_property, $display_name, $value='') {
        parent::__construct($css_property, $display_name, $value);
    }
    function validate($value) {
        return is_valid_size_string($value);
    }
    function get_html($attributes='') {
        return get_size_text_input_html($this, $attributes='');
    }
}
abstract class CSS_Horizontal_Position_Text_Input extends CSS_Size_Text_Input {
    function __construct($css_property, $display_name, $value='') {
        parent::__construct($css_property, $display_name, $value);
    }

    function validate($value) {
        if(parent::validate($value) || in_array($value, array('left', 'center', 'right'))) {
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

    function validate($value) {
        if(parent::validate($value) || in_array($value, array('top', 'center', 'bottom'))) {
            return true;
        } else {
            return false;
        }
    }
}
function dropdown_get_options_html($dropdown) {
    $html_options = '';
    $is_optgroup = false;
    $content = '';
    $potential_options = $dropdown->get_opts();
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
        attr_selected( ((string)$opt == $dropdown->get_value()) ? true : false ) .
        attr_value((string)$opt));
        if($content && ($dropdown instanceof ISetting_Depends_On_Global_Setting) ) {
            $html_options .= $i++ . '&nbsp;&nbsp;&nbsp;';
        }
        $html_options .= $content;
        $html_options .= ct('option') . "\n";
    }
    if ($is_optgroup) $html_options .= ct('optgroup') . "\n";
    return $html_options;
}
function get_select_html($dropdown, $attributes='') {
    $select = ot('select', $dropdown->get_html_name() . ToolTips::get($dropdown) . $attributes);
    $select .= dropdown_get_options_html($dropdown);
    $select .= ct('select');
    return $select;
}
function dropdown_validate($dropdown, $value) {
    $options = $dropdown->get_opts();
    $size = sizeof($options);
    if( ($value < $size) && ($value >= 0) ) {
        return true;
    } else {
        return false;
    }
}
abstract class Setting_Dropdown extends Setting {
    function __construct($name, $display_name, $opts, $value=0) {
        $this->opts = &$opts;
        parent::__construct($name, $display_name, $value);
    }
    function get_html($attributes='') {
        return get_select_html($this, $attributes);
    }
    function validate($value) {
        return dropdown_validate($this, $value);
    }
    function get_opts() {
        $opts = $this->opts;
        return $opts;
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
    protected $opts;

    function __construct($css_property, $display_name, $opts, $value=0) {
        $this->opts = &$opts;
        parent::__construct($css_property, $display_name, $value);
    }

    function get_html($attributes='') {
        return get_select_html($this, $attributes);
    }

    function validate($value) {
        return dropdown_validate($this, $value);
    }
    function get_opts() {
        $opts = $this->opts;
        return $opts;
    }

    function get_css_value() {
        $value = $this->get_value();
        $options = $this->get_opts();
        return $options[$value];
    }
}

abstract class Setting_Number extends Setting {
    function __construct($name, $display_name, $value='') {
        parent::__construct($name, $display_name, $value);
    }
    function validate($value) {
        return is_numeric($value);
    }
    function get_html($attributes='') {
        $input = input('text', $this->get_html_name() . attr_value($this->get_value()) . $attributes);
        return $input;
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


