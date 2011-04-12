<?php
require_once 'html-gen.php';

/** Heart Theme specific functions */
function ht_label($class, $for, $text) {
        return ot('label', attr_class($class)   . attribute('for', $for)) . $text . ct('label');
}
function ht_th($value, $scope = "")       {
        return ot('th', attribute('scope', $scope)) . $value . ct('th');
}
function ht_input($id, $type, $attributes) {
    return bt('input', attr_type($type) . attr_id('artpress_theme_options[' . $id . ']') . attr_name('artpress_theme_options[' . $id . ']') . $attributes );
}
function ht_input_text ($id, $class, $value, $size='') {
    return ht_input($id, 'text', attr_size($size) . attr_class($class) . attr_value($value)  );
}
//function ht_input_text ($id, $class, $value, $size='') {
//        return bt('input', attr_id($id) . attr_size($size) . attr_class($class) . attr_type('text') . attr_name($id) . attr_value($value)  );
//}
//function ht_input_checkbox ($id, $is_checked, $value, $field_blurb) {
//        return bt('input', attr_id($id) . attr_type('checkbox') . attr_name($id) . attr_value($value) . attr_checked($is_checked ) );
//}
function ht_input_radio ($id, $is_checked, $value) {
        return ht_input( $id, 'radio',  attr_value($value) . attr_checked($is_checked ) );
}
//function ht_input_radio ($id, $name, $is_checked, $value) {
//        return bt('input', attr_id($id) . attr_type('radio') . attr_name($name) . attr_value($value) . attr_checked($is_checked ) );
//}
function ht_form_field($field_name, $content) {
    return ot( 'tr', attr_valign('top') )
        . ht_th(__($field_name), "row")
        . td($content)
        . ct('tr');
}
function ht_form_text_field($field_name, $id, $value, $field_blurb, $size="5") {
    return ht_form_field($field_name,   ht_input_text($id, 'ht-regular-text', $value, $size) // TODO work out what css to use
                                    . ht_label('description', $id, $field_blurb), 
                       $field_blurb);
}
function ht_form_checkbox($field_name, $id, $value, $is_checked, $field_blurb) {
    return ot( 'tr', attr_valign('top') )
        . ht_th( $field_name, "row" )
        . td( ht_input_checkbox($id, $is_checked, $value, $field_blurb)
        . ht_label ('description', $id, $field_blurb) )
        . ct( 'tr' );
}
function ht_form_cell ( $id, $class, $value, $size, $field_blurb) {
    return td(  ht_label('description', $id, $field_blurb)
        //. bt('br')
        . ht_input_text($id, $class, $value, $size)
        );
}
function ht_form_cell_radio ( $id, $value, $is_checked, $size, $field_blurb) {
    return td(  ht_label('description', $id, $field_blurb)
        . ht_input_radio($id, $is_checked, $value)
        );
}