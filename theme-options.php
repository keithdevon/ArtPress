<?php
require_once 'heart-theme-form-functions.php';

add_action( 'admin_init', 'theme_options_init' );
add_action( 'admin_menu', 'theme_options_add_page' );
add_action( 'admin_init', 'artpress_options_load_scripts' );

// Load our scripts
function artpress_options_load_scripts() {
    wp_enqueue_script('farbtastic', get_bloginfo('template_url') . '/scripts/farbtastic/farbtastic.js', array('jquery'));
    wp_register_style( 'ArtPressOptionsStylesheet', get_bloginfo('template_url') . '/scripts/farbtastic/farbtastic.css' );
    wp_enqueue_style( 'ArtPressOptionsStylesheet' );
   
 
add_action('init', 'ht_init_method');
}
/**
 * Init plugin options to white list our options
 */
$background_image_prefix = 'ap_bi_';

function theme_options_init(){
    global $background_image_prefix;
    register_setting( 'artpress_options', 'artpress_theme_options', 'artpress_options_validate' );
    
    register_setting( 'artpress_options_bi', 'ap_background_image_settings', 'ap_bi_validate' );
    add_settings_section( 'ap_bi_section', 'Background Images', 'ap_bi_section_html', 'theme_options_slug' );
    add_settings_field( $background_image_prefix . '1', 'Background Image 1', 'ap_bi_html', 'theme_options_slug', 'ap_bi_section', '1');
    add_settings_field( $background_image_prefix . '2', 'Background Image 2', 'ap_bi_html', 'theme_options_slug', 'ap_bi_section', '2');
    add_settings_field( $background_image_prefix . '3', 'Background Image 3', 'ap_bi_html', 'theme_options_slug', 'ap_bi_section', '3');
            
    $options = get_option('artpress_theme_options');
    $padded_with_default_options = artpress_options_validate($options);
    update_option('artpress_theme_options', $padded_with_default_options);   
}
/**
 * Load up the menu page
 */
function theme_options_add_page() {
        add_menu_page( __( 'ArtPress Options' ), __( 'ArtPress Options' ), 'edit_theme_options', 'theme_options_slug', 'artpress_options_do_page' );
}

function ap_bi_section_html() {
    //echo "<h3>Background Image Section</h3>";
}

function ap_bi_html($number) {
    global $background_image_prefix;
    $file_id = $background_image_prefix . $number;
    $file_paths = get_option('ap_background_image_settings');
    $label = "<p>not set</p>";
    $path = ''; $image = '';
    if (isset($file_paths[$file_id]['url'])) {
        $url = $file_paths[$file_id]['url'];
        $path = $file_paths[$file_id]['file'];
        $image = "<img src='{$url}' height='40' />";
        $label = "<p>{$path}</p>";
    }
    $input = "<input type='file' name='{$file_id}' size='40' value='{$path}'/>";
    echo $image. $label . $input;
}
/**
 * Create the options page
 */
function artpress_options_do_page() {
    global $global_options;
    $num_colors = $global_options['num_colors'];
    $num_fonts = $global_options['num_fonts'];
    global $select_options, $radio_options;

    if ( ! isset( $_REQUEST['updated'] ) )
        $_REQUEST['updated'] = false;

    ?>
    <div class="wrap">
        <form method="post" enctype="multipart/form-data" action="options.php">
   	        <?php settings_fields('artpress_options_bi'); ?>
            <?php do_settings_sections('theme_options_slug'); ?>
            <p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Upload Images') ?>" /></p>
        </form>
    </div>
    
    <div class="wrap">
        <?php 
        screen_icon(); echo "<h2>" . get_current_theme() . __( ' Options' ) . "</h2>"; 
        if ( false !== $_REQUEST['updated'] ) : ?>
        	<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
        <?php endif; ?>
        
        

        <form method="post" action="options.php">
            <?php 
            settings_fields( 'artpress_options' ); 
            $settings = get_option( 'artpress_theme_options' );
            ?>
            <h3>Global settings</h3>
            <table class="form-table">
                <?php 
                echo ht_form_text_field('Base text size', '[base_text_size]', esc_attr( $settings['base_text_size']), __( "example options: '16px', '1em' or '100%'" ), '5');
                
                // output the first color field and the td containing the color picker
                // $output = ht_th(__('Color 0'), "row");
                // $output .= td(ht_input_text('[colors][0]','colorwell', esc_attr( $settings['colors'][0] ), '7'));

                //echo tr($output);
                
                // output the rest of the color fields
                foreach (array_keys($settings['colors']) as $color ) {
                    $output = '';
                    $id = '[colors][' . $color . ']';
                    $output = ht_th(__('Color ' . $color), "row");
                    $output .= td(ht_input_text('[colors][' . $color . ']','colorwell', esc_attr( $settings['colors'][$color] ), '7'));
                    if ($color == '0') {
                        $output .= td( div('', attr_id('picker')),
                                       attribute('rowspan', '6') . attr_valign('top'));
                    }
                    echo tr($output);
                }
                
                // output the font selectors
                $cells = '';
                foreach (array_keys($settings['fonts']) as $font) {
                    $id = '[fonts][' . $font . ']';
                    $cells .= ht_form_cell($id,    'fontfamily', esc_attr( $settings['fonts'][$font] ), '7', __( 'Font ' . $font ));
                }              
                echo ht_form_field('Fonts',
                    table(
                        tr($cells),    
                        attr_valign('top')
                    )
                );
           
                // output the page width selector
                echo ht_form_text_field('Page Width', '[page_width]', esc_attr( $settings['page_width']), __( "example options: '1000px', '30em' or '100%'" ), '6');
                                /* Color Pickers */ ?>
                        <script>
                    jQuery(document).ready(function() {
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
                      });
                 </script>
                 
                <?php 
                // thumbnail shadow
                //  left offset
                //  top offset - 
                //  size - px
                //  color - rgb(0,0,0);
                /*echo ht_form_text_field($settings['section_settings'][$group][$css_attr]['row_label'], 
                							  '[section_settings][' . $group . '][' . $css_attr . '][value]', 
                                               esc_attr( $settings['section_settings'][$group][$css_attr]['value']),
                                               __( $settings['section_settings'][$group][$css_attr]['field_blurb_suffix'] ), 
                                               '5');*/ 
                ?>
                </table>
                <p class="submit"><input type="submit" class="button-primary" value="<?php _e( 'Save Options' ); ?>" /></p>

                <?php echo ht_create_form($settings); ?>

            </form>
        </div>
        <?php
}
function ends_with($str, $suffix) {
    $suffix_start = strlen($str) - strlen($suffix);
    return (substr($str, $suffix_start) == $suffix);
}
function starts_with($str, $prefix) {
    return (substr($str, 0, strlen($prefix)) == $prefix);
}
function get_prefix($str, $suffix) {
    return substr($str, 0, strlen($str) - strlen($suffix));
}
function is_suffix_string($str, $suffix) {
    if (ends_with($str, $suffix)) {
        $prefix = get_prefix($str, $suffix);
        return is_numeric($prefix);
    }
}
function is_em_string($str) {
    return is_suffix_string($str, 'em');
}
function is_px_string($str) {
    return is_suffix_string($str, 'px');
}
function is_percent_string($str) {
    return is_suffix_string($str, '%');
}
function is_valid_size_string($str) {
    return is_em_string($str) || is_px_string($str) || is_percent_string($str);
}
function is_valid_color_string($str) {
    return (strlen($str) == 7) && starts_with($str, '#') && ctype_xdigit(substr($str, 1));
}
/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */
function artpress_options_validate( $input ) {
    global $select_options, $radio_options, $artpress_colors, $num_colors;
    
    // GLOBAL SETTINGS CORRECTION
    
    // correct the base text size
    if (!(isset( $input['base_text_size']) 
          && is_valid_size_string($input['base_text_size']))) {   
        $input['base_text_size'] = '1em'; 
    }
    
    // correct the page width
    if (!(isset( $input['page_width']) 
          && is_valid_size_string($input['page_width']))) {   
        $input['page_width'] = '1024px'; 
    }
    
    // correct the colors
    if ( ! isset( $input['colors'] ) ) $input['colors'] = array(null, null, null, null);
    
    foreach(array_keys($input['colors']) as $key) { // TODO need to properly validate the color value       
        if (!(isset( $input['colors'][$key]))) {
            $input['colors'][$key] = '#999999';        
        }
    } 
    
    // correct the fonts
    if ( ! isset( $input['fonts'] ) ) $input['fonts'] = array(null, null, null);
    
    foreach(array_keys($input['fonts']) as $key) {        
        if (!(isset( $input['fonts'][$key]))) {  // TODO need to properly validate the font 
            $input['fonts'][$key] = 'Arial';        
        }
    }
    
    // create sections and itialize css selectors         
    if( ! isset($input['section_settings']['body']) ) 
        $input['section_settings']['body'] = array(
        	'css_selector'=>'body',
            'font-family'     => array( 'row_label'=>'font' , 'field_blurb_prefix'=>'Font' , 'value'=>'1' ),
            'color'           => array( 'row_label'=>'color' , 'field_blurb_prefix'=>'Color' , 'value'=>'1'),
            'background-color'=> array( 'row_label'=>'background color' , 'field_blurb_prefix'=>'Color' , 'value'=>'3'),
            'background-image'=> array( 'row_label'=>'background image', 'field_blurb_suffix'=>'tick to use a background image' ),
            'padding'         => array( 'row_label'=>'padding' , 'value'=>'0.5em' , 'field_blurb_suffix'=>'internal space between the element\'s content and its border' ),
            'margin'          => array( 'row_label'=>'margin' , 'value'=>'0.5em' , 'field_blurb_suffix'=>'external space between the element\'s border and other elements' ));
            
    if( ! isset($input['section_settings']['header']) ) 
        $input['section_settings']['header'] = array(
        	'css_selector'=>'#header',
            'background-color'=> array( 'row_label'=>'background color' , 'field_blurb_prefix'=>'Color' , 'value'=>'3'),
            'padding'         => array( 'row_label'=>'padding' , 'value'=>'0.5em' , 'field_blurb_suffix'=>'internal space between the element\'s content and its border' ),
            'margin'          => array( 'row_label'=>'margin' , 'value'=>'0.5em' , 'field_blurb_suffix'=>'external space between the element\'s border and other elements' ));
    
    if( ! isset($input['section_settings']['site title']) ) 
        $input['section_settings']['site title'] = array(	
            'css_selector'    => '#site-title' , 
            'font-family'     => array( 'row_label'=>'font' , 'field_blurb_prefix'=>'Font' , 'value'=>'2' ), 
            'color'           => array( 'row_label'=>'color' , 'field_blurb_prefix'=>'Color' , 'value' => '2' ),
            'background-color'=> array( 'row_label'=>'background color' , 'field_blurb_prefix'=>'Color' , 'value' => '3' ),
            'padding'         => array( 'row_label'=>'padding' , 'value'=>'0.5em' , 'field_blurb_suffix'=>'internal space between the element\'s content and its border' ),
            'margin'          => array( 'row_label'=>'margin' , 'value'=>'0.5em' , 'field_blurb_suffix'=>'external space between the element\'s border and other elements' ));
        
    if( ! isset($input['section_settings']['widget title']) ) 
        $input['section_settings']['widget title'] = array(	
            'css_selector'    => '.widget-title' , 
            'font-family'     => array( 'row_label'=>'font' , 'field_blurb_prefix'=>'Font' , 'value'=>'2' ), 
            'color'           => array( 'row_label'=>'color' , 'field_blurb_prefix'=>'Color' , 'value' => '2' ),
            'background-color'=> array( 'row_label'=>'background color' , 'field_blurb_prefix'=>'Color' , 'value' => '3' ),
            'padding'         => array( 'row_label'=>'padding' , 'value'=>'0.5em' , 'field_blurb_suffix'=>'internal space between the element\'s content and its border' ),
            'margin'          => array( 'row_label'=>'margin' , 'value'=>'0.5em' , 'field_blurb_suffix'=>'external space between the element\'s border and other elements' ));
            
    if( ! isset($input['section_settings']['widget links']) ) 
        $input['section_settings']['widget links'] = array(	
            'css_selector'    => '.xoxo a:link, .xoxo a:visited' , 
            'color'           => array( 'row_label'=>'color' , 'field_blurb_prefix'=>'Color' , 'value' => '2' ),
            'background-color'=> array( 'row_label'=>'background color' , 'field_blurb_prefix'=>'Color' , 'value' => '3' ));
            
    if( ! isset($input['section_settings']['links']) ) 
        $input['section_settings']['links'] = array(	
            'css_selector'    => 'a:link, a:visited' , 
            'color'           => array( 'row_label'=>'color' , 'field_blurb_prefix'=>'Color' , 'value' => '2' ),
            'background-color'=> array( 'row_label'=>'background color' , 'field_blurb_prefix'=>'Color' , 'value' => '3' ));
            
                        
    if( ! isset($input['section_settings']['link hover']) ) 
        $input['section_settings']['link hover'] = array(	
            'css_selector'    => 'a:hover, a:active' , 
            'color'           => array( 'row_label'=>'color' , 'field_blurb_prefix'=>'Color' , 'value' => '3' ),
            'background-color'=> array( 'row_label'=>'background color' , 'field_blurb_prefix'=>'Color' , 'value' => '1' ));
            
    if( ! isset($input['section_settings']['top menu']) ) 
        $input['section_settings']['top menu'] = array(	
            'css_selector'    => '#top-menu' , 
            'color'           => array( 'row_label'=>'color' , 'field_blurb_prefix'=>'Color' , 'value' => '3' ),
            'background-color'=> array( 'row_label'=>'background color' , 'field_blurb_prefix'=>'Color' , 'value' => '1' ));
            
   
    // SECTION CORRECTION
    foreach(array_keys($input['section_settings']) as $section) {
        foreach(array_keys($input['section_settings'][$section]) as $css_attr) {
            switch ($css_attr) {
                case 'font-family':
                    if ( ! isset( $input['section_settings'][$section][$css_attr]['row_label'] ) )       
                                  $input['section_settings'][$section][$css_attr]['row_label'] = 'font';
                    if ( ! isset( $input['section_settings'][$section][$css_attr]['field_blurb_prefix'] ) ) 
                                  $input['section_settings'][$section][$css_attr]['field_blurb_prefix'] = 'Font';
                    if ( ! isset( $input['section_settings'][$section][$css_attr]['value'] ) )              
                                  $input['section_settings'][$section][$css_attr]['value'] = '0';                
                    break;              
                case 'color':           
                    if ( ! isset( $input['section_settings'][$section][$css_attr]['row_label'] ) )          
                                  $input['section_settings'][$section][$css_attr]['row_label'] = 'color';
                    if ( ! isset( $input['section_settings'][$section][$css_attr]['field_blurb_prefix'] ) ) 
                                  $input['section_settings'][$section][$css_attr]['field_blurb_prefix'] = 'Color';
                    if ( ! isset( $input['section_settings'][$section][$css_attr]['value'] ) )              
                                  $input['section_settings'][$section][$css_attr]['value'] = '0';                
                    break;              
                case 'background-color':      
                    if ( ! isset( $input['section_settings'][$section][$css_attr]['row_label'] ) )          
                                  $input['section_settings'][$section][$css_attr]['row_label'] = 'background color';
                    if ( ! isset( $input['section_settings'][$section][$css_attr]['field_blurb_prefix'] ) ) 
                                  $input['section_settings'][$section][$css_attr]['field_blurb_prefix'] = 'Color';
                    if ( ! isset( $input['section_settings'][$section][$css_attr]['value'] ) )              
                                  $input['section_settings'][$section][$css_attr]['value'] = '0';                
                    break;              
                case 'padding':         
                    if ( ! isset( $input['section_settings'][$section][$css_attr]['row_label'] ) )          
                                  $input['section_settings'][$section][$css_attr]['row_label'] = 'padding';
                    if ( ! isset( $input['section_settings'][$section][$css_attr]['field_blurb_suffix'] ) ) 
                                  $input['section_settings'][$section][$css_attr]['field_blurb_suffix'] = 'Internal space between the element\'s content and its border';
                    if ( ! isset( $input['section_settings'][$section][$css_attr]['value'] ) )              
                                  $input['section_settings'][$section][$css_attr]['value'] = '0.5em';                
                    break;              
                case 'margin':          
                    if ( ! isset( $input['section_settings'][$section][$css_attr]['row_label'] ) )          
                                  $input['section_settings'][$section][$css_attr]['row_label'] = 'margin';
                    if ( ! isset( $input['section_settings'][$section][$css_attr]['field_blurb_suffix'] ) ) 
                                  $input['section_settings'][$section][$css_attr]['field_blurb_suffix'] = 'External space between the element\'s border and other elements';
                    if ( ! isset( $input['section_settings'][$section][$css_attr]['value'] ) )              
                                  $input['section_settings'][$section][$css_attr]['value'] = '1em';                
                    break;                                                             
            }
        }
    }
    
    
    // Say our text option must be safe text with no HTML tags
    //$input['sometext'] = wp_filter_nohtml_kses( $input['sometext'] );
    
    // Our select option must actually be in our array of select options
    //if ( ! array_key_exists( $input['selectinput'], $select_options ) )
       // $input['selectinput'] = null;
    
    // Our radio option must actually be in our array of radio options
    /*if ( ! isset( $input['radioinput'] ) )
        $input['radioinput'] = null;
    if ( ! array_key_exists( $input['radioinput'], $radio_options ) )
        $input['radioinput'] = null;*/
    
    // Say our textarea option must be safe text with the allowed tags for posts
    //$input['sometextarea'] = wp_filter_post_kses( $input['sometextarea'] );
    
    return $input;
}

function ap_bi_validate($input) {
    global $background_image_prefix;
    $override_defaults = array('test_form' => false); 
    $options = get_option('ap_background_image_settings');
    
    foreach(array_keys($_FILES) as $file_name) {
        $prefix = substr($file_name, 0, strlen($background_image_prefix)); 
        if ( $prefix == $background_image_prefix // make sure the file is named correctly 
             && $_FILES[$file_name]['name'] != "" ) {    // make sure that it isn't empty TODO how do we handle deleting a file?
            $file = wp_handle_upload($_FILES[$file_name], $override_defaults); // store the file in the database
            $options[$file_name] = $file;
        }
    }
    return $options;
}

