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
    $field_blurb_prefix = __($field_blurb_prefix);
    $cells = '';
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
/*function ht_options_styled ($is_selected, $value, $content, $style_attrs_arr=null) {
        $attr = '';
        // add any existing style attributes to the option group
        if ($style_attrs_arr) {
            foreach (array_keys($style_attrs_arr) as $css_attr) {
                $attr .= dec($css_attr, $style_attrs_arr[$css_attr]);  
            } 
        }
        return ht_option( $is_selected, $value, $content, attr_style( $attr ));
}*/
function ht_options_styled ($potential_options, $selected, $option_group_name, $form_style_attrs=null) {    
    $html_options = '';        
    foreach (array_keys($potential_options) as $opt) {
        $attr = '';
        
        // add any existing style attributes to the option group
        if ($form_style_attrs) {
            foreach (array_keys($form_style_attrs) as $css_attr) {
                $attr .= dec($css_attr, $form_style_attrs[$css_attr][$opt]);  
            } 
        }
        $html_options .= ht_option( ((string)$opt == $selected) ? true : false, 
                        "k[]={$option_group_name}&k[]={$opt}", 
                        $potential_options[$opt], 
                        attr_style( $attr ));
        }
    return $html_options;
}
/** 
 * @var $potential_options should be a one dimensional array<br>
 * where the key is the value of the option<br>
 * and the value is the visible text for that option.<br>
 * @var $form_style_arr is a two dimensional array where the the first key<br>
 * is a css attribute e.g. <i>background-color</i><br>
 * and the second key denotes which value to use for the aforementioned css attribute
 * */
function ht_create_select($potential_options, $id, $row_label, $field_blurb_prefix, $selected, $form_style_attrs=null) {
    $field_blurb_prefix = __($field_blurb_prefix);
    $html_options = ht_options_styled($potential_options, $selected, null, $form_style_attrs);
    return ht_form_field( $row_label, ht_select( $id, $html_options ) );   
}
/** 
 * @var $potential_grouped_options should be a two dimensional array<br>
 * where the first key is the optgroup name,<br> 
 * the second key is the value of the option<br>
 * and the value is the visible text for that option.<br>
 * @var $form_style_arr is a three dimensional array<br>
 * where the first key is the optgroup name,<br> 
 * the second key is a css attribute e.g. <i>background-color</i><br>
 * and the third key denotes which value to use for the aforementioned css attribute
 * */
function ht_create_select_grouped($potential_grouped_options, $id, $row_label, $field_blurb_prefix, $selected, $form_style_attrs=null) {
    $field_blurb_prefix = __($field_blurb_prefix);
    $html_optgroups = '';

    foreach (array_keys($potential_grouped_options) as $optgroup) {
        $style_group = null;
        if ( isset($form_style_attrs) ) $style_group = $form_style_attrs($optgroup);
        $options = ht_options_styled($potential_grouped_options[$optgroup], $selected, $style_group);
        $html_optgroups .= optgroup($optgroup, $options);
    }
    return ht_form_field($row_label, ht_select($id, $html_optgroups));
}
function ht_create_form_group($settings, $group) {
    global $ht_css_repeat;
    global $ht_css_attachment;
    global $ht_css_font_style;
    global $ht_css_text_transform;
    global $ht_css_text_align;
    global $ht_css_border_style; 
    global $ht_css_list_style_position;
    global $ht_css_list_style_type;
    global $ht_css_font_family;
    
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
                $output .= ht_create_select_grouped($ht_css_font_family, 
                                                "[section_settings][{$group}][{$css_attr}][value]",
                                                $css_attr_arr['row_label'], 
                                                $css_attr_arr['field_blurb_prefix'],
                                                $css_attr_arr['value']);
                break;
                
            case 'font-style':
                $output.= ht_create_select($ht_css_font_style,
                                            "[section_settings][{$group}][{$css_attr}][value]",
                                            $css_attr_arr['row_label'],
                                            $css_attr_arr['field_blurb_suffix'],
                                            $css_attr_arr['value']);
                break;
            case 'text-align':
                $output.= ht_create_select($ht_css_text_align,
                                            "[section_settings][{$group}][{$css_attr}][value]",
                                            $css_attr_arr['row_label'],
                                            $css_attr_arr['field_blurb_suffix'],
                                            $css_attr_arr['value']);
                break;
            case 'text-transform':
                $output.= ht_create_select($ht_css_text_transform,
                                            "[section_settings][{$group}][{$css_attr}][value]",
                                            $css_attr_arr['row_label'],
                                            $css_attr_arr['field_blurb_suffix'],
                                            $css_attr_arr['value']);
                break;
            case 'text-decoration':
                $output.= ht_create_select($ht_css_text_decoration,
                                            "[section_settings][{$group}][{$css_attr}][value]",
                                            $css_attr_arr['row_label'],
                                            $css_attr_arr['field_blurb_suffix'],
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
                
                $form_style_attrs['background-color'] = $color_array;
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
                $output.= ht_form_text_field('horizontal background position', 
                                                "[section_settings][{$group}][{$css_attr}][value][0]", 
                                                $css_attr_arr['value'][0], 
                                                "blah");
                
                $output.= ht_form_text_field('vertical background position', 
                                                "[section_settings][{$group}][{$css_attr}][value][1]", 
                                                $css_attr_arr['value'][1], 
                                                "blah");
                break;
                
            case 'box-shadow-use':
                $output .= ht_form_checkbox($css_attr_arr['row_label'],
                                            "[section_settings][{$group}][{$css_attr}][value]",
                                            ( $css_attr_arr['value'] == 'on' ) ? true : false, 
                                            __( $css_attr_arr['field_blurb_suffix']));   
                break;             
            case 'box-shadow':
                $output.= ht_form_text_field('box shadow horizontal', 
                                                "[section_settings][{$group}][{$css_attr}][value][0]", 
                                                $css_attr_arr['value'][0], 
                                                "blah");
                
                $output.= ht_form_text_field('box shadow vertical', 
                                                "[section_settings][{$group}][{$css_attr}][value][1]", 
                                                $css_attr_arr['value'][1], 
                                                "blah");
                $output.= ht_form_text_field('box shadow blur', 
                                                "[section_settings][{$group}][{$css_attr}][value][2]", 
                                                $css_attr_arr['value'][2], 
                                                "blah");
                
                $output.= ht_form_text_field('box shadow color', 
                                                "[section_settings][{$group}][{$css_attr}][value][3]", 
                                                $css_attr_arr['value'][3], 
                                                "blah", 24);
                break;
            case 'text-shadow-use':
                $output .= ht_form_checkbox($css_attr_arr['row_label'],
                                            "[section_settings][{$group}][{$css_attr}][value]",
                                            ( $css_attr_arr['value'] == 'on' ) ? true : false, 
                                            __( $css_attr_arr['field_blurb_suffix']));
                break;
            case 'text-shadow':
                $output.= ht_form_text_field('text shadow horizontal', 
                                                "[section_settings][{$group}][{$css_attr}][value][0]", 
                                                $css_attr_arr['value'][0], 
                                                "blah");
                
                $output.= ht_form_text_field('text shadow vertical', 
                                                "[section_settings][{$group}][{$css_attr}][value][1]", 
                                                $css_attr_arr['value'][1], 
                                                "blah");
                $output.= ht_form_text_field('text shadow blur', 
                                                "[section_settings][{$group}][{$css_attr}][value][2]", 
                                                $css_attr_arr['value'][2], 
                                                "blah");
                
                $output.= ht_form_text_field('text shadow color', 
                                                "[section_settings][{$group}][{$css_attr}][value][3]", 
                                                $css_attr_arr['value'][3], 
                                                "blah");
                break;    
            case 'font-size':
            case 'padding': case 'padding-top': case 'padding-bottom': case 'padding-left': case 'padding-right':
            case 'margin': case 'margin-top': case 'margin-bottom': case 'margin-left': case 'margin-right':
                $output .= ht_form_text_field($css_attr_arr['row_label'], 
                							  "[section_settings][{$group}][{$css_attr}][value]", 
                                               esc_attr( $css_attr_arr['value'] ),
                                               __( $css_attr_arr['field_blurb_suffix'] ), 
                                               '5');                
                break;    
            case 'border-use':
                $output .= ht_form_checkbox($css_attr_arr['row_label'],
                                            "[section_settings][{$group}][{$css_attr}][value]",
                                            ( $css_attr_arr['value'] == 'on' ) ? true : false, 
                                            __( $css_attr_arr['field_blurb_suffix']));
                break;
            case 'border-style':
                $output.= ht_create_select($ht_css_border_style,
                                            "[section_settings][{$group}][{$css_attr}][value]",
                                            $css_attr_arr['row_label'],
                                            $css_attr_arr['field_blurb_suffix'],
                                            $css_attr_arr['value']);
                break;
            case 'border-width':
                $output .= ht_form_text_field($css_attr_arr['row_label'], 
                							  "[section_settings][{$group}][{$css_attr}][value]", 
                                               esc_attr( $css_attr_arr['value'] ),
                                               __( $css_attr_arr['field_blurb_suffix'] ), 
                                               '5');                
                break; 
            case 'border-color':
                $output .= ht_create_select(array_slice($settings['colors'] ,1),
                                                "[section_settings][{$group}][{$css_attr}][value]", 
                                                $css_attr_arr['row_label'], 
                                                $css_attr_arr['field_blurb_prefix'],
                                                $css_attr_arr['value'],
                                                $form_style_attrs
                                                );
                break;                                                
            case 'list-style-position':
                $output.= ht_create_select($ht_css_list_style_position,
                                            "[section_settings][{$group}][{$css_attr}][value]",
                                            $css_attr_arr['row_label'],
                                            $css_attr_arr['field_blurb_suffix'],
                                            $css_attr_arr['value']);                                                
                break;
            case 'list-style-type':
                $output.= ht_create_select($ht_css_list_style_type,
                                            "[section_settings][{$group}][{$css_attr}][value]",
                                            $css_attr_arr['row_label'],
                                            $css_attr_arr['field_blurb_suffix'],
                                            $css_attr_arr['value']);                                                
                break;                                 
        }
    }
    return $output;
}
function ht_create_form($settings) {
    foreach (array_keys($settings['section_settings']) as $section) {
        echo ot('h3') . '<a href="#">' .ucfirst($section) . ' settings' . '</a>' . ct('h3');
        echo ot('div');
        echo '<form method="post" action="options.php">';
        settings_fields( 'artpress_options' ); 
        $table_contents = ht_create_form_group($settings, $section);
        $table = ot('table', attr_class('form-table'));
        $table .= $table_contents;
        $table .= ct('table');
        echo $table;
        echo '<p class="submit"><input type="submit" class="button-primary" value="' . __( "Save {$section} options" ) . '" /></p>';
        echo ct('form');
        echo  ct('div');
    }
}