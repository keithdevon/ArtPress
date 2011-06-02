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
        return ot( 'option',  attr_value((string)$value) . attr_selected($is_selected ) . $attr)
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
function ht_options_styled ($potential_options, $selected, $form_style_attrs=null) {    
    $html_options = '';        
    $is_optgroup = false;
    $content = '';
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
        if ($form_style_attrs) {
            foreach (array_keys($form_style_attrs) as $css_attr) {
                $attr .= dec($css_attr, $form_style_attrs[$css_attr][$opt]);  
            } 
        }
        $html_options .= ht_option( ((string)$opt == $selected) ? true : false, 
                        (string)$opt, 
                        $content, 
                        attr_style( $attr ));
        }
        if ($is_optgroup) $html_options .= ct('optgroup');
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
/*function ht_create_select_grouped($potential_grouped_options, $id, $row_label, $field_blurb_prefix, $selected, $form_style_attrs=null) {
    $field_blurb_prefix = __($field_blurb_prefix);
    $html_optgroups = '';

    foreach (array_keys($potential_grouped_options) as $optgroup) {
        $style_group = null;
        if ( isset($form_style_attrs) ) $style_group = $form_style_attrs($optgroup);
        $options = ht_options_styled($potential_grouped_options[$optgroup], $selected, $style_group);
        $html_optgroups .= optgroup($optgroup, $options);
    }
    return ht_form_field($row_label, ht_select($id, $html_optgroups));
}*/
/*function ht_create_form_group($settings, $group) {
    global $ht_css_repeat;
    global $ht_css_attachment;
    global $ht_css_font_style;
    global $ht_css_text_transform;
    global $ht_css_text_align;
    global $ht_css_text_decoration;
    global $ht_css_border_style; 
    global $ht_css_list_style_position;
    global $ht_css_list_style_type;
    global $ht_css_font_family;
    global $ht_css_font_weight;
    
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
                $select_fonts = array();
                foreach (array_keys($settings['fonts']) as $font_num) {
                     if( is_array( $ht_css_font_family[$settings['fonts'][$font_num]] ) ) {
                         $font = $ht_css_font_family[$settings['fonts'][$font_num]][0];
                         $select_fonts[$font_num] = "font {$font_num} -- {$font}";
                     }
                     else {
                         $font = $ht_css_font_family[$settings['fonts'][$font_num]];
                         $select_fonts[$font_num] = "font {$font_num} -- {$font}";
                     }       
                }
                $output .= ht_create_select($select_fonts, 
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
            case 'font-weight':
                $output.= ht_create_select($ht_css_font_weight,
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
            case 'logo-image-use':
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
            case 'padding-top': case 'padding-bottom': case 'padding-left': case 'padding-right':
            case 'margin-top': case 'margin-bottom': case 'margin-left': case 'margin-right':
                $output .= ht_form_text_field($css_attr_arr['row_label'], 
                							  "[section_settings][{$group}][{$css_attr}][value]", 
                                               esc_attr( $css_attr_arr['value'] ),
                                               __( $css_attr_arr['field_blurb_suffix'] ), 
                                               '5');                
                break;    
            case 'margin':
            case 'padding':
                $cells = ht_form_cell("[section_settings][{$group}][{$css_attr}][value][0]", 
                                        '', 
                                        $css_attr_arr['value'][0], 
                                        6, 
                                        'top');
                $cells .= ht_form_cell("[section_settings][{$group}][{$css_attr}][value][1]", 
                                        '', 
                                        $css_attr_arr['value'][1], 
                                        6, 
                                        'right');
                $cells .= ht_form_cell("[section_settings][{$group}][{$css_attr}][value][2]", 
                                        '', 
                                        $css_attr_arr['value'][2], 
                                        6, 
                                        'bottom');
                $cells .= ht_form_cell("[section_settings][{$group}][{$css_attr}][value][3]", 
                                        '', 
                                        $css_attr_arr['value'][3], 
                                        6, 
                                        'left');    
                $output .= ht_form_field($css_attr_arr['row_label'], table(tr($cells)));                                                                                                                       
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
}*/


function ap_create_global_settings_form($qualifier, $global_settings) {
    global $ht_css_font_family;
    
    echo ot('form', attr_method('post') . attr_action('options.php'));
        $font_rows = '';
        $color_rows = '';
        echo settings_fields( 'artpress_options' );
        // colors
        ?><script>
            /*jQuery(document).ready(function() {
                var f = jQuery.farbtastic('#picker');
                var p = jQuery('#picker').css('opacity', 0.25);
                var selected;
                jQuery('.colorwell')
                  .each(function () { f.linkTo(this); jQuery(this).css('opacity', 0.75); })
                  .focus(function() {
                    if (selected) {
                      jQuery(selected).css('opacity', 0.75).removeClass('colorwell-selected');
                    }
                    f.linkTo(this);
                    p.css('opacity', 1);
                    jQuery(selected = this).css('opacity', 1).addClass('colorwell-selected');
                  });
              });*/
        </script><?php
        foreach (array_keys(array_slice($global_settings['colors'], 1)) as $color ) {
            $output = '';
            $output = ht_th(__('Color ' . ($color + 1)), "row");
            $output .= td(ht_input_text(get_qualifier($qualifier) . "[colors][{$color}]",
            							'colorwell', esc_attr( $global_settings['colors'][$color] ), '7'));
            if ($color == '0') {
                $output .= td( div('', attr_id('picker')),
                               attribute('rowspan', '6') . attr_valign('top'));
            }
            $color_rows .= tr($output);
        }
        
        // fonts
        foreach (array_keys($global_settings['fonts']) as $font) {
            $font_rows .= ht_create_select($ht_css_font_family, 
                                            get_qualifier($qualifier) . "[fonts][{$font}]", 'Font ' .  ($font + 1), 'blurb', $global_settings['fonts'][$font]);
        }
        echo table($color_rows, attr_class('form-table'));
        echo table($font_rows, attr_class('form-table'));
                
        //<p class="submit"><input type="submit" class="button-primary" value="<?php _e( 'Save global settings ' ); ?/>" /></p>
        echo p(
            input('submit', attr_class('button-primary') . attr_value(__( 'Save global settings ' ))),
            attr_class('submit')
        );
    echo ct('form');
}
function ap_create_section_settings_form($qualifier, $section_settings, $tab_count){
    global $ht_css_repeat;
    global $ht_css_attachment;
    global $ht_css_font_style;
    global $ht_css_text_transform;
    global $ht_css_text_align;
    global $ht_css_text_decoration;
    global $ht_css_border_style; 
    global $ht_css_list_style_position;
    global $ht_css_list_style_type;
    global $ht_css_font_family;
    global $ht_css_font_weight;
        
    $section = end($qualifier);
    echo ot('div', attr_id('tabs-' . $tab_count ) . attr_style("border: solid grey 1px; padding: 0.5em;"));
        echo h3($section);
        
        foreach(array_keys($section_settings[$section]['children']) as $sub) { // page title, h2 etc
            
            $ssq = am($qualifier, 'children', $sub); // sub section qualifier
            echo ot('div',  attr_style("border: dashed grey 1px;padding: 0.5em;"));
                echo h4($sub);
                echo ot('div', attr_style("border: dotted grey 1px;padding: 0.5em;"));
                    $sssq_string = get_qualifier(am($ssq, 'typography')); 
                    echo h5('typography');
                    $rows = '';
                        echo ot('div', attr_style("border: dotted #999 1px;padding: 0.25em;"));
                            
                            $rows .= ht_create_select(
                                        $ht_css_font_style,
                                        $sssq_string . "[font-style]" . "[value]",
                                        'font style',
                                        'blurb suffix',
                                        'value');
                            $rows .= ht_create_select(
                                        $ht_css_font_weight,
                                        $sssq_string . "[font-weight]" . "[value]",
                                        'font weight',
                                        'blurb suffix',
                                        'value');
                            $rows .= ht_create_select(
                                        $ht_css_text_align,
                                        $sssq_string . "[text-align]" . "[value]",
                                        'text align',
                                        'blurb suffix',
                                        'value');

                            $rows .= ht_create_select(
                                        $ht_css_text_transform,
                                        $sssq_string . "[text-transform]" . "[value]",
                                        'text transform',
                                        'blurb suffix',
                                        'value');

                            $rows .= ht_create_select(
                                        $ht_css_text_decoration,
                                        $sssq_string . "[text-decoration]" . "[value]",
                                        'text decoration',
                                        'blurb suffix',
                                        'value');                                      
                            echo table($rows, attr_class('form-table'));
                                                                              
                        echo ct('div');
                    
                    echo h5('background');
                        echo ot('div', attr_style("border: dotted #999 1px;padding: 0.25em;"));
                        echo ct('div');                    
                    
                    echo h5('layout');
                        echo ot('div', attr_style("border: dotted #999 1px;padding: 0.25em;"));
                        echo ct('div');                    
                        
                    echo h5('effects');
                        echo ot('div', attr_style("border: dotted #999 1px;padding: 0.25em;"));
                        echo ct('div');
                                           
                echo ct('div');
            echo ct('div');
        }
    echo ct('div'); 
}
function ap_create_form($qualifier, $save) {
    $tab_count = 1;
    ?>
    <!-- do this properly 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.0/jquery.min.js"></script> 
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js"></script> 
	-->
	<script>
    	$(function() {
    		$( "#tabs" ).tabs();
    	});
	</script>
    <?php
    echo ot('div', attr_id('tabs'));
    // create the tab links
    $lis  = li(alink('#tabs-' . $tab_count++, 'Global'));
    $lis .= li(alink('#tabs-' . $tab_count++, 'Images'));
    foreach (array_keys($save['sections']) as $section) {
        $lis .= li(alink("#tabs-" . $tab_count++, $section));
    }
    echo ul($lis);
    
    $tab_count = 1;
    // global div
    echo ot('div', attr_id('tabs-' . $tab_count++) . attr_style("border: solid grey 1px; padding: 0.5em;"));
        echo h3('global settings');
        ap_create_global_settings_form(am($qualifier, 'global-settings'), 
                                       $save['global-settings']);
    echo ct('div'); 
    
    // image div
    echo ot('div', attr_id('tabs-' . $tab_count++) . attr_style("border: solid grey 1px; padding: 0.5em;"));
        echo h3('image settings');
    echo ct('div');
    
    // section divs
    foreach (array_keys($save['sections']) as $section) {
        ap_create_section_settings_form(am($qualifier, 'sections', $section), 
                                        $save['sections'], $tab_count++);
    }
    echo ct('div');
}
/*function ht_create_form($settings) {
    foreach (array_keys($settings['section_settings']) as $section) {
        echo ot('h3') . '<a href="#">' .ucfirst($section) . '</a>' . ct('h3');
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
}*/