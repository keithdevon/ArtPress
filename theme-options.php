<?php
$dir1 = get_bloginfo('template_directory') . '/';
$dir2 = $_SERVER['DOCUMENT_ROOT'];
$dir3 = get_theme_root();
$dir = get_template_directory() . '/';
$dir5 = get_template_directory_uri();
require_once 'heart-theme-utils.php';
$full_dir = $dir . 'form/heart-theme-form-functions.php';
include_once $full_dir;
require_once $dir . 'form/form.php';
require_once $dir . 'form/css-selectors.php';
require_once $dir . 'form/global.php';
require_once $dir . 'form/images.php';
require_once $dir . 'form/color.php';
require_once $dir . 'form/border.php';
require_once $dir . 'form/header-form.php';
require_once $dir . 'form/menu-form.php';
require_once $dir . 'form/body.php';
require_once $dir . 'form/sidebar-form.php';
require_once $dir . 'form/footer-form.php';
require_once $dir . 'form/gallery-form.php';
require_once $dir . 'form/typography.php';
require_once $dir . 'form/layout.php';
require_once $dir . 'form/effect.php';
require_once $dir . 'form/background-image.php';

add_action( 'admin_init', 'artpress_options_load_scripts' );
add_action( 'admin_init', 'artpress_theme_init' );
add_action( 'admin_menu', 'theme_options_add_page' );

// Load our scripts
function artpress_options_load_scripts() {
    
    wp_register_script('jqueryui1814',
    //js/ui1814/css/ui-lightness/images/ui-bg_diagonals-thick_18_b81900_40x40.png
    
           get_bloginfo('template_directory') . '/js/ui1814/js/jquery-ui-1.8.14.custom.min.js',
           array('jquery')
           //'1.0' 
           );       
    
    wp_register_style( 'jqueryui1814css', 
                        get_bloginfo('template_directory') . 
                        	'/js/ui1814/css/ui-lightness/jquery-ui-1.8.14.custom.css' );  
                                              
    wp_enqueue_script('jqueryui1814');
    wp_enqueue_style( 'jqueryui1814css' );  

    wp_enqueue_script('farbtastic', get_bloginfo('template_url') . '/scripts/farbtastic/farbtastic.js', array('jquery'));
    wp_register_style( 'ArtPressOptionsStylesheet', get_bloginfo('template_url') . '/scripts/farbtastic/farbtastic.css' );
    wp_enqueue_style( 'ArtPressOptionsStylesheet' );
    
    add_action('init', 'ht_init_method');
}
/**
 * Init plugin options to white list our options
 */
$background_image_prefix = 'ap_image_';

function artpress_theme_init() {
    global $background_image_prefix;
    register_setting( 'artpress_options',       'ap_options', 'ap_options_validate' );
    register_setting( 'artpress_image_options', 'ap_images',  'ap_image_validate' );
    
    add_settings_section( 'ap_bi_section', '', 'ap_bi_section_html', 'manage_images' );
    
    add_settings_field( $background_image_prefix . '0', 'Logo image',         'ap_image_html', 'manage_images', 'ap_bi_section', '0');
    add_settings_field( $background_image_prefix . '1', 'Background image 1', 'ap_image_html', 'manage_images', 'ap_bi_section', '1');
    add_settings_field( $background_image_prefix . '2', 'Background image 2', 'ap_image_html', 'manage_images', 'ap_bi_section', '2');
    add_settings_field( $background_image_prefix . '3', 'Background image 3', 'ap_image_html', 'manage_images', 'ap_bi_section', '3');
    add_settings_field( $background_image_prefix . '4', 'Background image 4', 'ap_image_html', 'manage_images', 'ap_bi_section', '4');
    add_settings_field( $background_image_prefix . '5', 'Background image 5', 'ap_image_html', 'manage_images', 'ap_bi_section', '5');
    add_settings_field( $background_image_prefix . '6', 'Background image 6', 'ap_image_html', 'manage_images', 'ap_bi_section', '6');
        
    init_ap_options();
}

/**
 * Load up the menu page
 */
function theme_options_add_page() {
        add_menu_page(                 __( 'ArtPress Options' ),    __( 'Artpress' ),            'edit_theme_options', 'artpress',    'ap_settings_page', '', 0 ); // TODO stop Artpress Options from being displayed on the form
        add_submenu_page('artpress',   __('Configurations'), __('Configurations'), 'edit_theme_options', 'manage_configurations', 'ap_configs_page');
        add_submenu_page('artpress',   __('Images'),         __('Images'),         'edit_theme_options', 'manage_images',         'ap_image_upload_page');
}

function ap_bi_section_html() {
    echo "<p>Upload images here</p>";
}

/** Displays the html form for selecting background images */
function ap_reset_html() { ?>    
    <form method="post" action="options.php"> <?php 
        settings_fields( 'artpress_options' ); 
        $settings = get_option( 'artpress_theme_options' );
    ?> </form>
<?php }

/** Displays the html form elements for selecting a background image */
function ap_image_html($number) {
        //wp_nonce_url
    ///wp/wp-admin/upload.php?deleted=1
    //http://localhost/wp/wp-admin/post.php?action=delete&post=78&_wpnonce=b0cb2fedda
    global $background_image_prefix;
    $file_id = $background_image_prefix . $number;
    $file_paths = get_option('ap_images');
    $path = ''; $image = ''; $url_label =''; $cb = '';
    if (isset($file_paths[$file_id]['url'])) {
        $url = $file_paths[$file_id]['url'];
        $path = $file_paths[$file_id]['file'];
        $image = "<img src='{$url}' height='50' />";
        $url_label = p(alink($url, $path));
        $path_label = "<p>({$path})</p>";
        
        $cb_id = 'ap_images[delete][' . $file_id . ']';
        $cb_label = label($cb_id, 'delete image');
        $cb = $cb_label . checkbox($cb_id, false);
    }
    $input = "<input type='file' name='{$file_id}' size='40' value=''/>";
    echo $image . $url_label . $cb . $input;
}
/** 
 * HACK ALERT! creating my own 'settings_fields' that doesn't echo but returns its contents.
 * */
function get_settings_fields($option_group) {
    $o =  "<input type='hidden' name='option_page' value='" . esc_attr($option_group) . "' />";
    $o .= '<input type="hidden" name="action" value="update" />';
	$o .= wp_nonce_field("$option_group-options", "_wpnonce", true, false);
	return $o;
}
function ap_settings_page() {
    if ( ! isset( $_REQUEST['updated'] ) )
        $_REQUEST['updated'] = false;
        
    // page title stuff
    screen_icon(); 
    echo ot('div', attr_class('wrap'));
    echo h2( get_current_theme() . __( ' Options' ) ); // TODO source of why k & j see differenet stuff
    if ( false !== $_REQUEST['updated'] ) : ?>
        <div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
    <?php endif;
    //if ( ! isset( $_REQUEST['updated'] ) ) $_REQUEST['updated'] = false;    
    //if ( false !== $_REQUEST['updated'] ) echo div( p(_e( 'Options saved' )), attr_class('updated fade') );

    
    $maintabgroup = new Main_Tab_Group('main tab group');
    $options = get_option('ap_options');
    if ($options != null) {
        if (isset($options['saves'][$options['current-save-id']])) {
            $maintabgroup->inject_values(array_merge(array('current-save-id'=>$options['current-save-id']), 
                                                     $options['saves'][$options['current-save-id']]));
            }
    }
    echo $maintabgroup->get_html();
    echo ct('div');
}
function ap_image_upload_page() {
    ?><div class="wrap">
            <form method="post" enctype="multipart/form-data" action="options.php">
       	        <?php settings_fields('artpress_image_options'); ?>
                <?php do_settings_sections('manage_images'); ?>
                <p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Upload Images') ?>" /></p>
            </form>
	</div><?php   
}
function ap_configs_page() {
    $options = get_option('ap_options');
    $o = '';
    $o .= h2('configurations');
    $o .= ot('table');
    $o .= tr( td(label( 'current-save-id', __('current configuration') ) ) .
              td(input( 'text', attr_readonly() . attr_value( $options['current-save-id'] ) ) ) );
              
    // select a configuration  
    $o .= '<form method="post" action="options.php">';
    $o .= get_settings_fields('artpress_options');
    $o .= input('hidden', attr_name('ap_options[change_current-save-id]') . attr_value('true') );
    if( $options && isset($options['saves'])) {
        $first = true;
        $first_col = 'load configuration';
        foreach (array_keys($options['saves']) as $save_name) {
            $o .= tr( td($first_col)
                        . td($save_name)
                        . td( input( 'radio', attr_name("ap_options[current-save-id]") .attr_value($save_name))));
            if($first) {
                $first = false;
                $first_col = '';
            }
        }   
    }
    $load = __( 'load' );  
    $o .= td(''). td(''). td("<span class='submit'><input type='submit' class='button-primary' value='{$load}' /></span>");
    
    $o .= ct('form');
    
    // delete a configuration  
    $o .= '<form method="post" action="options.php">';
    $o .= get_settings_fields('artpress_options');
    $o .= input('hidden', attr_name('ap_options[delete_configuration]') . attr_value('true') );
    if( $options && isset($options['saves'])) {
        $first = true;
        $first_col = 'delete configuration';
        foreach (array_keys($options['saves']) as $save_name) {
            $o .= tr( td($first_col)
                        . td($save_name)
                        . td( input( 'checkbox', attr_name("ap_options[dead_saves][${save_name}]") .attr_value($save_name))));
            if($first) {
                $first = false;
                $first_col = '';
            }
        }   
    }
    $delete = __( 'delete' );  
    $o .= td(''). td(''). td("<span class='submit'><input type='submit' class='button-primary' value='{$delete}' /></span>");
    
    $o .= ct('form');
    
    // create new configuration
    $o .= '<form method="post" action="options.php">';
    $o .= get_settings_fields('artpress_options');
    $o .= input('hidden', attr_name('ap_options[create_new_configuration]') . attr_value('true') );

    $o .= ot('tr');
    $o .= td(label('new_configuration',__('create new configuration')));
    $o .= td(input('text', attr_name('ap_options[current-save-id]'), attr_id('new_configuration')));
    $create = __( 'create' );  
    $o .= td("<span class='submit'><input type='submit' class='button-primary' value='{$create}' /></span>");
    $o .= ct('tr');      
    $o .= ct('form');
    
    $o .= ct('table');
    $div = div($o, attr_class('wrap'));
    
    // select default configurations
    
    // upload/download configuration?
    echo $div;
}
/** 
 * Creates a valid options array of stub, empty values.
 * */
function get_ap_options_defaults() {
    $options = array( 'cs'=>array() );

    $options['saves'] = array();
    $options['current-save-id'] = 'default';
    $options['saves'][$options['current-save-id']] = $options['cs'];

    $options['defaults'] = array();
    return $options;
}
/**
 * Function to create the options array in the db if not already created.
 */
function init_ap_options() {
    $options = get_option('ap_options');
    
    if ($options == null) {
        $options = get_ap_options_defaults();
        add_option('ap_options', $options);
    }
}
/** 
 * @var new_settings will either be what is passed to update_option
 * or what is returned from the options form
 * 
 * we need to merge the new settings with the old settings.
 * if we were to populate our new form using only the new settings 
 * provided by the client's browser, then checkboxes would disappear 
 * as no record of unticked checkboxes are returned to the server    
 * 
 * Call scenarios:
 * 1st time (return from form):
 * 	- nothing in db, get_option will return null
 *  - values in $new_setting will be returned
 * 
 * 2nd time (validation before save?)
 *  - still nothing in db, get_option will return null
 *  - new_settings contains everything previously set
 * 
 * */
function ap_options_validate( $new_settings ) {
    
    $options = get_option('ap_options');
    
    if( isset($new_settings['change_current-save-id'] ) ) {
        $options['current-save-id'] = $new_settings['current-save-id'];
        return $options;
    }
    if( isset($new_settings['create_new_configuration'] ) ) {
        $new_config_name = $new_settings['current-save-id'];
        $options['current-save-id'] = $new_config_name;
        $options['saves'][$options['current-save-id']] = array();
        return $options;
    }
    if( isset( $new_settings['delete_configuration'] ) ) {
        // TODO also delete css
        $dead_saves = $new_settings['dead_saves'];
        foreach( array_keys($dead_saves) as $save ) {
            unset($options['saves'][$save]);
        }
        $first_save = key($options['saves']);
        if($first_save) {
            $options['current-save-id'] = $first_save;
        } else {
            $options['current-save-id'] = 'default';
            $options['saves'][$options['current-save-id']] = array();
        }
               
        return $options;
    }
    if( $options == null) $options = get_ap_options_defaults();

    $previous_save = $options['saves'][$options['current-save-id']];
    if ($new_settings == null ) {
        $new_settings = array('cs'=>array());
    }
    $merged_save = array_merge_recursive_distinct($previous_save, $new_settings['cs']);
    
    // filter out default values
    $merged_save = array_filter($merged_save); 
    
    // validate save TODO 

    // set the current-save-id
    // create save name if none supplied
    if( $new_settings['current-save-id'] == '' || $new_settings['current-save-id'] == 'default' ) {
        $d = getdate();
        $date= "{$d['year']} {$d['month']} {$d['mday']} {$d['weekday']} {$d['hours']}:{$d['minutes']}:{$d['seconds']}";
        $options['current-save-id'] = $date;
    } else {
        $options['current-save-id'] = $new_settings['current-save-id'];
    }
    // create css
    $css = create_css();
    
    // store save
    $options['saves'][$options['current-save-id']] = $merged_save;
    $options['css'][$options['current-save-id']] = $css;
    
    return $options;
}
function ap_image_validate($input) {
    global $background_image_prefix;
    $override_defaults = array('test_form' => false); 
    $options = get_option('ap_images');
    
    foreach(array_keys($_FILES) as $file_name) {
        $prefix = substr($file_name, 0, strlen($background_image_prefix)); 
        if ( $prefix == $background_image_prefix // make sure the file is named correctly 
             && $_FILES[$file_name]['name'] != "" ) {    // make sure that it isn't empty TODO how do we handle deleting a file?
            $file = wp_handle_upload($_FILES[$file_name], $override_defaults); // store the file in the database
            $options[$file_name] = $file;
        }
    }
    
    if(isset($input['delete'])) {
    
    }
    return $options;
}

class CSS_Setting_Visitor implements Visitor {
    function recurse($hierarchy) {
        return $hierarchy->has_children();
    }
    function valid_child($hierarchy) {
        if ( $hierarchy instanceof CSS_Setting ) {
            $parent = $hierarchy->get_parent();
            if ( $parent instanceof Toggle_Group ) {
                return $parent->is_on();
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
}
function create_css() {
    $output = "";
    
    $maintabgroup = new Main_Tab_Group('main tab group');
    $options = get_option('ap_options');
    if ($options != null) {
        if (isset($options['saves'][$options['current-save-id']])) {
            $current_save = $options['saves'][$options['current-save-id']];
            $maintabgroup->inject_values($current_save);
        }
    }
    
    // customized functionality for Global Settings css
    // headers
    $font_size = Global_Font_Size_Ratio::get_font_size(1);
    $selector_string = 'h1, h2, h3, h4, h5, h6';
    $declarations = dec('margin-top', 2 * $font_size . 'px'); // TODO hacky
    $declarations .= dec('margin-bottom', $font_size);
    $output .= rule($selector_string, decblock($declarations));
    
    // paragraph
    $selector_string = 'p';
    $declarations = dec('margin-bottom', $font_size);
    $output .= rule($selector_string, decblock($declarations));
    
    // standard functionality for all other settings 
    $selectors = CSS_Selector::get_css_selectors(); 
    
    
    foreach ( $selectors as $selector ) {
        $selector_string = get_full_selector_string( get_full_selector_array($selector) );
        $settings = $selector->get_children(new CSS_Setting_Visitor());
        $declarations = '';
        foreach( $settings as $setting ) {
            $declarations .= $setting->get_css_declaration();
        }
        if($declarations) {
            $output .= rule( $selector_string, decblock($declarations) );
        }
    }
    return $output;  
}
//function ap_image_validate($input) {
//    // IMAGES
//    if( !isset($options['images']) ) {
//        $options['images'] = array();
//    }
//    
//    // TODO un hard code
//    $images= $_FILES['ap_options']['name']['images'];
//    //foreach(array_keys($images) as $image_id) {
//    //    $overrides = array('test_form' => false); 
//    //    $file = '';
//    //    if( $_FILES['ap_options']['name']['images'][$image_id] ) {
//    //        $file = wp_handle_upload($_FILES['ap_options']['name']['images'][$image_id], $overrides);
//    //    }
//    //    $options['images'][$image_id] = $file;
//    //}
//    
//    // PREVIOUS IMPLEMENTATION
//    //foreach(array_keys($_FILES) as $file_name) {
//    //    $prefix = substr($file_name, 0, strlen($background_image_prefix)); 
//    //    if ( $prefix == $background_image_prefix // make sure the file is named correctly 
//    //         && $_FILES[$file_name]['name'] != "" ) {    // make sure that it isn't empty TODO how do we handle deleting a file?
//    //        $file = wp_handle_upload($_FILES[$file_name], $override_defaults); // store the file in the database
//    //        $options[$file_name] = $file;
//    //    }
//    //}
//    $file = wp_handle_upload($_FILES['ap_options'], $overrides);
//    $options['images'][$image_id] = $file;    
//}

/** Create the options page */
/*function artpress_options_do_page() {
    global $global_options;
    $num_colors = $global_options['num_colors'];
    $num_fonts = $global_options['num_fonts'];
    global $select_options, $radio_options;
    global $ht_css_font_family;
    if ( ! isset( $_REQUEST['updated'] ) )
        $_REQUEST['updated'] = false;

    ?>
	<h3>Artpress Options</h3>
    <script>
	jQuery(function() {
		jQuery( "#accordion" ).accordion({
			collapsible: true,
			active: false,
			autoHeight: false,
		});
	});
	</script>    
    <div class="wrap" id="accordion">
        <h3><a href="#">Global settings</a></h3>
        <div>

        <?php 
        screen_icon(); echo "<h2>" . get_current_theme() . __( ' Options' ) . "</h2>"; 
        if ( false !== $_REQUEST['updated'] ) : ?>
        	<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
        <?php endif; ?>
          
        <form method="post" action="options.php">
            <?php      
            settings_fields( 'artpress_options' ); 
            $settings = get_option( 'artpress_theme_options' );           
            $settings[ 'background_images' ] = get_option( 'ap_background_image_settings' );
            ?>

                <table class="form-table">
                    <?php 

                    echo ht_form_checkbox("Reset", '[ap_reset]', false, "check this box and hit reset to reset all options", 'reset');

                    // output the rest of the color fields
                    foreach (array_keys(array_slice($settings['colors'], 1)) as $color ) {
                        $output = '';
                        $output = ht_th(__('Color ' . $color), "row");
                        $output .= td(ht_input_text('[colors][' . $color . ']','colorwell', esc_attr( $settings['colors'][$color] ), '7'));
                        if ($color == '0') {
                            $output .= td( div('', attr_id('picker')),
                                           attribute('rowspan', '6') . attr_valign('top'));
                        }
                        echo tr($output);
                    }

                    foreach (array_keys($settings['fonts']) as $font) {
                        //echo ht_form_text_field('Font ' .  $font, '[fonts][' . $font . ']', esc_attr( $settings['fonts'][$font] ), 'blurb');
                        echo ht_create_select($ht_css_font_family, '[fonts][' . $font . ']', 'Font ' .  $font, 'blurb', $settings['fonts'][$font]);
                    }
                    // Color Pickers  
                    ?>
                                    
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

                    </table>
                <p class="submit"><input type="submit" class="button-primary" value="<?php _e( 'Save global settings ' ); ?>" /></p>
            </form>
        </div>
        <h3><a href="#">Image upload</a></h3>
        <div class="wrap">
            <form method="post" enctype="multipart/form-data" action="options.php">
       	        <?php settings_fields('artpress_options_bi'); ?>
                <?php do_settings_sections('artpress'); ?>
                <p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Upload Images') ?>" /></p>
            </form>
    	</div>
        <?php //$settings = get_option( 'artpress_theme_options' );  
        ht_create_form($settings); ?>

	</div>
<?php }*/