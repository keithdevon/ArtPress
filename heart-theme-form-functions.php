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
    return ht_input($id, 'text', attr_size($size) . attr_class($class) . attr_value($value) );
}
function ht_input_hidden ($id, $value) { // TODO not great having to do this. DB interaction needs re-written
    return ht_input($id, 'hidden', attr_value($value) );
}
function ht_input_radio ($id, $is_checked, $value) {
        return ht_input( $id, 'radio',  attr_value($value) . attr_checked($is_checked ) );
}
function ht_input_checkbox ($id, $value, $is_checked) {
        return ht_input( $id, 'checkbox', attr_value($value) . attr_checked($is_checked ) );
}
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
    $output = ot( 'tr', attr_valign('top') )
        . ht_th( $field_name, "row" )
        . td( ht_input_checkbox( $id, $value, $is_checked )
        . ht_label ('description', $id, $field_blurb) )
        . ct( 'tr' );
    return $output;
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
function ht_create_radio_row($options, $settings, $group, $css_field, $row_label, $field_blurb_prefix, $misc_cell='') {
    $id = '[section_settings][' . $group . '][' . $css_field . '][value]';
    $field_blurb_prefix = __($field_blurb_prefix);
    $checked = esc_attr( $settings['section_settings'][$group][$css_field]['value'] );
    $cells = '';
    for ($i = 0; $i < count($options); $i++) {
        $cells .= ht_form_cell_radio($id, $i, ($i == $checked) ? true : false, $field_blurb_prefix . ' ' . $i );
    }  
	return ht_form_field($row_label, 
        table(
            tr($cells . $misc_cell),    
            attr_valign('top')
        )
    ); 
}
function ht_create_form_group($settings, $group) {
    $output = '';
    foreach (array_keys($settings['section_settings'][$group]) as $css_attr) {
        $misc_cell = '';
        $css_attr_arr = $settings['section_settings'][$group][$css_attr];
        switch($css_attr) {
            case 'css_selector':
                $output .= ht_input_hidden('[section_settings]['. $group . '][css_selector]', 
                                            $settings['section_settings'][$group]['css_selector']);
                break;
            case 'font-family':
                $output .= ht_create_radio_row($settings['fonts'], 
                                                $settings, 
                                                $group, 
                                                $css_attr, 
                                                $css_attr_arr['row_label'], 
                                                $css_attr_arr['field_blurb_prefix']);
                break;
                
            case 'background-image':
                $output .= ht_form_checkbox($css_attr_arr['row_label'],
                                            "[section_settings][{$group}][{$css_attr}][value]",
                                            $css_attr_arr['value'],
                                            $css_attr_arr['checked'], 
                                            __( $css_attr_arr['field_blurb_suffix'] ));
                break;
                
            case 'background-color':
                $misc_cell .= ht_form_cell_radio("[section_settings][{$group}][{$css_attr}][value]",
                                                 'transparent', 
                                                 ($css_attr_arr['value'] == 'transparent') ? true : false, 
                                                 'Transparent');
            case 'color':
                $output .= ht_create_radio_row($settings['colors'], 
                                                $settings, 
                                                $group, 
                                                $css_attr, 
                                                $css_attr_arr['row_label'], 
                                                $css_attr_arr['field_blurb_prefix'],
                                                $misc_cell);
                break;
            case 'font-size':
            case 'padding':
            case 'margin':
                $output .= ht_form_text_field($css_attr_arr['row_label'], 
                							  "[section_settings][{$group}][{$css_attr}][value]", 
                                               esc_attr( $css_attr_arr['value'] ),
                                               __( $css_attr_arr['field_blurb_suffix'] ), 
                                               '5');                
                break;
        }
    }
    return $output;
}
function ht_create_form($settings) {
    $output = '';
    foreach (array_keys($settings['section_settings']) as $section) {
        $output .= ot('h3') . ucfirst($section) . ' settings' . ct('h3');
        $table_contents = ht_create_form_group($settings, $section);
        $table = ot('table', attr_class('form-table'));
        $table .= $table_contents;
        $table .= ct('table');
        $output .= $table;
        $output .= '<p class="submit"><input type="submit" class="button-primary" value="' . __( 'Save Options' ) . '" /></p>';
    }
    return $output;
}