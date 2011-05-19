<?php header('Content-type: text/css'); ?>

<?php

// TODO investigate alternatives to wp-load.php

require_once('../../../wp-load.php');
require_once('css-gen.php');

$options = get_option('artpress_theme_options');
$background_images = get_option('ap_background_image_settings');

$output = "";

foreach(array_keys($options['section_settings']) as $section) { // body, page etc
    global $ht_css_repeat;
    global $ht_css_font_style;
    global $ht_text_transform;
    global $ht_text_align;
    global $ht_text_decoration;
    global $ht_css_border_style; 
    
    $section_arr = $options['section_settings'][$section];
    
    $use_border = false;
    if( isset($section_arr['border-use']['value']) &&
        $section_arr['border-use']['value'] == 'on') $use_border = true;
            
    $use_text_shadow = false;
    if( isset($section_arr['text-shadow-use']['value']) &&
        $section_arr['text-shadow-use']['value'] == 'on') $use_text_shadow = true;

    $use_box_shadow = false;
    if( isset($section_arr['box-shadow-use']['value']) &&
        $section_arr['box-shadow-use']['value'] == 'on') $use_box_shadow = true;        
        
    $declarations = '';
    foreach(array_keys($section_arr) as $css_group) { // css-selector, font-family, color etc 
        $css_group_arr = $section_arr[$css_group];
        switch ($css_group) {
            case 'font-size':
                $declarations .=  dec($css_group, $css_group_arr['value']);       
                break;
            case 'font-family':
                $declarations .=  dec($css_group, $options['fonts'][$css_group_arr['value']]);       
                break;
            case 'font-style':
                $declarations .=  dec($css_group, $ht_css_font_style[$css_group_arr['value']]);       
                break;
            case 'text-transform':
                $declarations .=  dec($css_group, $ht_css_text_transform[$css_group_arr['value']]);       
                break;            
            case 'text-align':
                $declarations .=  dec($css_group, $ht_css_text_align[$css_group_arr['value']]);       
                break;
            case 'text-decoration':
                $declarations .=  dec($css_group, $ht_css_text_decoration[$css_group_arr['value']]);       
                break;
            case 'color':
            case 'background-color':
                $declarations .= dec($css_group, $options['colors'][$css_group_arr['value']]);
                break;
            case 'padding': case 'padding-top': case 'padding-bottom': case 'padding-left': case 'padding-right':
            case 'margin': case 'margin-top': case 'margin-bottom': case 'margin-left': case 'margin-right':
                $declarations .= dec($css_group, $css_group_arr['value']);
                break;   
            case 'background-image:url':
                if($section_arr['background-image']['value'] == 'on') {
                    $declarations .= "{$css_group}('{$background_images[$css_group_arr['value']]['url']}');";
                }
                break;
            case 'background-attachment':
                if($section_arr['background-image']['value'] == 'on') {
                    $declarations .= dec($css_group, $ht_css_attachment[$css_group_arr['value']]);
                }                                                
                break;
            case 'background-repeat':
                if($section_arr['background-image']['value'] == 'on') {
                    $declarations .= dec($css_group, $ht_css_repeat[$css_group_arr['value']]);
                }                                               
                break;
            case 'background-position':
                if($section_arr['background-image']['value'] == 'on') {
                    $declarations .= dec($css_group, $css_group_arr['value'][0] . ' ' . 
                                                      $css_group_arr['value'][1] ); 
                }                                               
                break;
            case 'box-shadow':
                if ($use_box_shadow) {
                    $declarations .= dec('box-shadow',         $css_group_arr['value'][0] . ' ' . $css_group_arr['value'][1] . ' ' . $css_group_arr['value'][2] . ' ' . $css_group_arr['value'][3] );
                    $declarations .= dec('-moz-box-shadow',    $css_group_arr['value'][0] . ' ' . $css_group_arr['value'][1] . ' ' . $css_group_arr['value'][2] . ' ' . $css_group_arr['value'][3] ); 
                    $declarations .= dec('-webkit-box-shadow', $css_group_arr['value'][0] . ' ' . $css_group_arr['value'][1] . ' ' . $css_group_arr['value'][2] . ' ' . $css_group_arr['value'][3] );
                } 
                break;
            case 'text-shadow':
                if($use_text_shadow) $declarations .= dec('text-shadow', $css_group_arr['value'][0] . ' ' . $css_group_arr['value'][1] . ' ' . $css_group_arr['value'][2] . ' ' . $css_group_arr['value'][3] );
                break;
            case 'border-style':
                if($use_border) $declarations .= dec($css_group, $ht_css_border_style[$css_group_arr['value']]);
                break;
            case 'border-width':
                if($use_border) $declarations .= dec($css_group, $css_group_arr['value']);
                break;                  
            case 'border-color':
                if($use_border) $declarations .= dec($css_group, $options['colors'][$css_group_arr['value']]);
                break;
            case 'list-style-position':
                $declarations .= dec($css_group, $ht_css_list_style_position[$css_group_arr['value']]);
                break;                
            case 'list-style-type':
                $declarations .= dec($css_group, $ht_css_list_style_type[$css_group_arr['value']]);
                break;  
         }
    }
    $output .= rule($section_arr['css_selector'],decblock( $declarations ));
}

echo $output;
                       
?>


