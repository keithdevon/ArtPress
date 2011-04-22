<?php
require_once 'html-gen.php';

/** Heart Theme specific functions */
function ht_label($class, $for, $text) {
        return ot('label', attr_class($class)   . attribute('for', 'artpress_theme_options' . $for)) . $text . ct('label');
}
function ht_th($value, $scope = "")       {
        return ot('th', attribute('scope', $scope)) . $value . ct('th');
}
function ht_input($id, $type, $attributes) {
    return bt('input', attr_type($type) . attr_id('artpress_theme_options' . $id) . attr_name('artpress_theme_options' . $id) . $attributes );
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
        . ht_input_text($id, $class, $value, $size)
        );
}
function ht_form_cell_radio ( $id, $value, $is_checked, $field_blurb) {
    return td(  ht_label('description', $id, $field_blurb)
        . ht_input_radio($id, $is_checked, $value)
        );
}
function ht_create_radio_row($options, $settings, $group, $css_field, $row_label, $field_blurb_prefix) {
    $id = '[' . $group . '][' . $css_field . '][value]';
    $field_blurb_prefix = __($field_blurb_prefix);
    $checked = esc_attr( $settings[$group][$css_field]['value'] );
    $cells = '';
    for ($i = 1; $i <= count($options); $i++) {
        $cells .= ht_form_cell_radio($id, $i, ($i == $checked) ? $i : false, $field_blurb_prefix . ' ' . $i );
    }  
	return ht_form_field($row_label, 
        table(
            tr($cells),    
            attr_valign('top')
        )
    ); 
}
function ht_create_form_group($settings, $group) {
    $output = '';
    foreach (array_keys($settings[$group]) as $css_attr) {
        $css_attr_arr = $settings[$group][$css_attr];
        switch($css_attr) {
            case 'font-family':
                $output .= ht_create_radio_row($settings['fonts'], 
                                                $settings, 
                                                $group, 
                                                $css_attr, 
                                                $css_attr_arr['row_label'], 
                                                $css_attr_arr['field_blurb_prefix']);
                break;
            case 'color':
            case 'background':
                $output .= ht_create_radio_row($settings['colors'], 
                                                $settings, 
                                                $group, 
                                                $css_attr, 
                                                $css_attr_arr['row_label'], 
                                                $css_attr_arr['field_blurb_prefix']);
                break;
            case 'font-size':
            case 'padding':
            case 'margin':
                $output .= ht_form_text_field($settings[$group][$css_attr]['row_label'], 
                							  '[' . $group . '][' . $css_attr . '][value]', 
                                               esc_attr( $settings[$group][$css_attr]['value']),
                                               __( $settings[$group][$css_attr]['field_blurb_suffix'] ), 
                                               '5');                
                break;
        }
    }
    return $output;
}
function ht_create_form($settings) {
    $output = '';
    foreach (array_keys($settings) as $group) {
        switch ($group) { // eg body or page
            case 'body':
            case 'page':
                $output .= ot('h3') . ucfirst($group) . ' settings' . ct('h3');
                $table_contents = ht_create_form_group($settings, $group);
                $table = ot('table', attr_class('form-table'));
                $table .= $table_contents;
                $table .= ct('table');
                $output .= $table; 
                break;
            case 'header':
                break;
            case 'content':
                break;
        }
    }
    return $output;
}