<?php
require_once 'html-gen.php';
require_once 'css-gen.php';

/** Heart Theme specific functions */
function ht_label($class, $for, $text) {
        return ot('label', attr_class($class)   . attribute('for', 'artpress_theme_options' . $for)) .  $text . ct('label');
}
function ht_th($value, $scope = "")       {
        return ot('th', attribute('scope', $scope)) . $value . ct('th');
}
function ht_input($id, $type, $attributes) {
    return bt('input', attr_type($type) . attr_id('artpress_theme_options' . $id) . attr_name('artpress_theme_options' . $id) . $attributes );
}
function ht_select($id, $options, $attributes='') {
    return ot('select', attr_id('artpress_theme_options' . $id) . attr_name('artpress_theme_options' . $id) . $attributes) 
            . $options 
            . ct('select');
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
function ht_option ($is_selected, $value, $content, $attr='') {
        return ot( 'option',  attr_value($value) . attr_selected($is_selected ) . $attr)
            . $content
            . ct( 'option' );
}
function ht_input_checkbox ($id, $is_checked, $value='') {
        return ht_input( $id, 'checkbox', attr_value($value) . attr_checked($is_checked ) );
}
function ht_form_field($field_name, $content) {
    $o =  ot( 'tr', attr_valign('top') );
    $o .= ht_th(__($field_name), "row");
    $o .= td($content);
    $o .= ct('tr');
    return $o;
}
function ht_form_text_field($field_name, $id, $value, $field_blurb, $size="5") {
    $it = ht_input_text($id, 'ht-regular-text', $value, $size); // TODO work out what css to use
    $l  = ht_label('description', $id, $field_blurb);
    $ff = ht_form_field($field_name,   $it . $l, $field_blurb);
    return $ff;
    
}
function ht_form_checkbox($field_name, $id, $is_checked, $field_blurb, $value='') {
    $output = ht_input_hidden($id, 'off')
        . ot( 'tr', attr_valign('top') )
        . ht_th( $field_name, "row" )
        . td( ht_input_checkbox( $id, $is_checked, 'on')
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

function ht_create_radio_row($potential_options, $id, $row_label, $field_blurb_prefix, $checked, $misc_cell='') {
    //$id = '[section_settings][' . $group . '][' . $css_field . '][value]';
    $field_blurb_prefix = __($field_blurb_prefix);
    //$checked = esc_attr( $settings['section_settings'][$group][$css_field]['value'] );
    //$checked = esc_attr( $id );
    $cells = '';
    //for ($i = 0; $i < count($potential_options); $i++) {
    foreach (array_keys($potential_options) as $opt) {
        $cells .= ht_form_cell_radio($id, (string)$opt, ($opt == $checked) ? true : false, $field_blurb_prefix . ' ' . $opt );
    }  
	return ht_form_field($row_label, 
        table(
            tr($cells . $misc_cell),    
            attr_valign('top')
        )
    ); 
}
/** 
 * @var $potential_options should be a one dimensional array<br>
 * where the key is the value of the option<br>
 * and the value is the visible text for that option.
 * */
function ht_create_select($potential_options, $id, $row_label, $field_blurb_prefix, $selected, $form_style_attrs=null) {
    /*$id = '[section_settings][' . $group . '][' . $css_field . '][value]';
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
    );*/
    //$id = '[section_settings][' . $group . '][' . $css_field . '][value]';
    $field_blurb_prefix = __($field_blurb_prefix);
    //$selected = "";//$selected = esc_attr( $settings['section_settings'][$group][$css_field]['value'] );
    
    $html_options = '';

    foreach (array_keys($potential_options) as $opt) {
        $attr = '';
        if ($form_style_attrs) {
            foreach (array_keys($form_style_attrs) as $css_attr) {
                //foreach ($form_style_attrs[$css_attr] as $value ) {
                    $attr .= dec($css_attr, $form_style_attrs[$css_attr][$opt]);
                
            } 
        }
        $html_options .= ht_option( ($opt == $selected) ? true : false, 
                        (string)$opt, $potential_options[$opt], attr_style( $attr ));
    }
    return ht_form_field($row_label, 
                         //ot('select', attr_name($id))
                         //. $html_options
                         //. ct('select')
                        ht_select($id, $html_options));
    
}
function ht_create_form_group($settings, $group) {
    global $ht_css_repeat;
    global $ht_css_attachment;
    
    $output = '';
    foreach (array_keys($settings['section_settings'][$group]) as $css_attr) {
        $background = false;
        $css_attr_arr = $settings['section_settings'][$group][$css_attr];
        switch($css_attr) {
            case 'css_selector':
                $output .= ht_input_hidden('[section_settings]['. $group . '][css_selector]', 
                                            $settings['section_settings'][$group]['css_selector']);
                break;
            case 'font-family':
                $output .= ht_create_select($settings['fonts'], 
                                                "[section_settings][{$group}][{$css_attr}][value]",
                                                $css_attr_arr['row_label'], 
                                                $css_attr_arr['field_blurb_prefix'],
                                                $css_attr_arr['value']);
                break;
                
            case 'background-image':
                $output .= ht_form_checkbox($css_attr_arr['row_label'],
                                            "[section_settings][{$group}][{$css_attr}][value]",
                                            ( $css_attr_arr['value'] == 'on' ) ? true : false, 
                                            __( $css_attr_arr['field_blurb_suffix']));
                break;
                
            case 'background-color':
                $background = true;
            case 'color':
                if ($background) 
                    $color_array = $settings['colors'];
                else 
                    $color_array = array_slice($settings['colors'] ,1);
                
                $form_style_attrs['background'] = $color_array;
                $output .= ht_create_select($color_array,
                                                "[section_settings][{$group}][{$css_attr}][value]", 
                                                $css_attr_arr['row_label'], 
                                                $css_attr_arr['field_blurb_prefix'],
                                                $css_attr_arr['value'],
                                                $form_style_attrs
                                                );
                break;
            case 'background-image:url':
                $background_images = array();
                foreach ($settings['background_images'] as $k=>$v) { 
                    $background_images[$k] = $v['url']; 
                }
                $output .= ht_create_select($background_images, 
                                            "[section_settings][{$group}][{$css_attr}][value]",
                                            $css_attr_arr['row_label'], 
                                            $css_attr_arr['field_blurb_prefix'],
                                            $css_attr_arr['value']);
                break;
            case 'background-attachment':
                $output.= ht_create_select($ht_css_attachment,
                                            "[section_settings][{$group}][{$css_attr}][value]",
                                            $css_attr_arr['row_label'],
                                            $css_attr_arr['field_blurb_suffix'],
                                            $css_attr_arr['value']);
                break;
            case 'background-repeat':
                $output.= ht_create_select($ht_css_repeat,
                                            "[section_settings][{$group}][{$css_attr}][value]",
                                            $css_attr_arr['row_label'],
                                            $css_attr_arr['field_blurb_suffix'],
                                            $css_attr_arr['value']);
                break;
            case 'background-position':
                $output.= ht_form_text_field('vertical background position', 
                                                "[section_settings][{$group}][{$css_attr}][value][0]", 
                                                $css_attr_arr['value'][0], 
                                                "blah");
                
                $output.= ht_form_text_field('horizontal background position', 
                                                "[section_settings][{$group}][{$css_attr}][value][1]", 
                                                $css_attr_arr['value'][1], 
                                                "blah");
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
     $output .= '<script>
	jQuery(function() {
		jQuery( "#accordion" ).accordion({
			collapsible: true,
			active: false
		});
	});
	</script>';
	$output .= '<div id="accordion">';
    foreach (array_keys($settings['section_settings']) as $section) {
        $output .= ot('h3') . '<a href="#">' .ucfirst($section) . ' settings' . '</a>' . ct('h3');
        $output .= ot('div');
        $table_contents = ht_create_form_group($settings, $section);
        $table = ot('table', attr_class('form-table'));
        $table .= $table_contents;
        $table .= ct('table');
        $output .= $table;
        $output .= '<p class="submit"><input type="submit" class="button-primary" value="' . __( 'Save Options' ) . '" /></p>';
        $output .=  ct('div');
    }
    $output .= '</div>';
    return $output;
}