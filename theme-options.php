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
require_once $dir . 'form/global.php';
require_once $dir . 'form/images.php';
require_once $dir . 'form/color.php';
require_once $dir . 'form/border.php';
require_once $dir . 'form/header-form.php';
require_once $dir . 'form/menu-form.php';
require_once $dir . 'form/body.php';
require_once $dir . 'form/sidebar-form.php';
require_once $dir . 'form/footer-form.php';
require_once $dir . 'form/typography.php';
require_once $dir . 'form/layout.php';
require_once $dir . 'form/effect.php';
require_once $dir . 'form/background-image.php';

add_action( 'admin_init', 'artpress_options_load_scripts' );
add_action( 'admin_init', 'artpress_theme_init' );
add_action( 'admin_menu', 'theme_options_add_page' );

// Load our scripts
function artpress_options_load_scripts() {
    wp_register_script('jquery151',
       get_bloginfo('template_directory') . '/js/ui1814/js/jquery-1.5.1.min.js'
       //,array('jquery'),
       //'1.0' 
       );      
    
    wp_register_script('jqueryui1814',
    //js/ui1814/css/ui-lightness/images/ui-bg_diagonals-thick_18_b81900_40x40.png
    
           get_bloginfo('template_directory') . '/js/ui1814/js/jquery-ui-1.8.14.custom.min.js',
           array('jquery151')
           //'1.0' 
           );       
    
    wp_register_style( 'jqueryui1814css', 
                        get_bloginfo('template_directory') . 
                        	'/js/ui1814/css/ui-lightness/jquery-ui-1.8.14.custom.css' );  
                                             
    wp_enqueue_script('jquery151');  
    wp_enqueue_script('jqueryui1814');
    wp_enqueue_style( 'jqueryui1814css' );  

    wp_enqueue_script('farbtastic', get_bloginfo('template_url') . '/scripts/farbtastic/farbtastic.js', array('jquery151'));
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
    register_setting( 'artpress_options',     'ap_options', 'ap_options_validate' );
    register_setting( 'artpress_image_options', 'ap_images',   'ap_image_validate' );
    
    add_settings_section( 'ap_bi_section', '', 'ap_bi_section_html', 'image_upload_slug' );
    
    add_settings_field( 'logo-image',                   'Logo image',         'ap_bi_html', 'image_upload_slug', 'ap_bi_section', '0');
    add_settings_field( $background_image_prefix . '1', 'Background image 1', 'ap_bi_html', 'image_upload_slug', 'ap_bi_section', '1');
    add_settings_field( $background_image_prefix . '2', 'Background image 2', 'ap_bi_html', 'image_upload_slug', 'ap_bi_section', '2');
    add_settings_field( $background_image_prefix . '3', 'Background image 3', 'ap_bi_html', 'image_upload_slug', 'ap_bi_section', '3');
    
    init_ap_options();
}

/**
 * Load up the menu page
 */
function theme_options_add_page() {
        add_menu_page(                           __( 'ArtPress Options' ),    __( 'ArtPress' ),            'edit_theme_options', 'theme_options_slug', 'ap_settings_page', '', 0 ); // TODO stop Artpress Options from being displayed on the form
        add_submenu_page('theme_options_slug',   __('manage configurations'), __('manage configurations'), 'edit_theme_options', 'configs_slug',       'ap_configs_page');
        add_submenu_page('theme_options_slug',   __('image upload'),          __('image upload'),          'edit_theme_options', 'image_upload_slug',  'ap_image_upload_page');
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
function ap_bi_html($number) {
    global $background_image_prefix;
    $file_id = $background_image_prefix . $number;
    $file_paths = get_option('ap_images');
    $path = ''; $image = ''; $url_label ='';
    if (isset($file_paths[$file_id]['url'])) {
        $url = $file_paths[$file_id]['url'];
        $path = $file_paths[$file_id]['file'];
        $image = "<img src='{$url}' height='40' />";
        $url_label = p(alink($url, $path));
        $path_label = "<p>({$path})</p>";
    }
    $input = "<input type='file' name='{$file_id}' size='40' value=''/>";
    echo $image . $url_label . $input;
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
        $maintabgroup->inject_values(array_merge(array('Current_Save_ID'=>$options['Current_Save_ID']), 
                                                 $options['saves'][$options['Current_Save_ID']]));
    }
    echo $maintabgroup->get_html();
    echo ct('div');
}
function ap_image_upload_page() {
    ?><div class="wrap">
            <form method="post" enctype="multipart/form-data" action="options.php">
       	        <?php settings_fields('artpress_image_options'); ?>
                <?php do_settings_sections('image_upload_slug'); ?>
                <p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Upload Images') ?>" /></p>
            </form>
	</div><?php   
}
function ap_configs_page() {
    echo h2('configurations');
    // select a configuration
    $options = get_option('ap_options');
    if( $options && isset($options['saves'])) {
        foreach (array_keys($options['saves']) as $save_name) {
            echo $save_name;
        }
    }
    
    // create new configuration
    
    // select default configurations
    
    // upload/download configuration?
}
function get_ap_options_defaults() {
    $options = array( 'cs'=>array() );

    $options['saves'] = array();
    $options['Current_Save_ID'] = 'default';
    $options['saves'][$options['Current_Save_ID']] = $options['cs'];

    $options['defaults'] = array();
    return $options;
}
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
    if( $options == null) $options = get_ap_options_defaults();

    $previous_save = $options['saves'][$options['Current_Save_ID']];
    if ($new_settings == null ) {
        $new_settings = array('cs'=>array());
    }
    $merged_save = array_merge_recursive_distinct($previous_save, $new_settings['cs']);
    
    // filter out default values
    $merged_save = array_filter($merged_save); 
    
    // validate save

    // create save name if none supplied
    if( $new_settings['Current_Save_ID'] == '' ) {
        $d = getdate();
        $date= "{$d['year']} {$d['month']} {$d['mday']} {$d['weekday']} {$d['hours']}:{$d['minutes']}:{$d['seconds']}";
        $options['Current_Save_ID'] = $date;
    } else {
        $options['Current_Save_ID'] = $new_settings['Current_Save_ID'];
    }
    
    // store save
    $options['saves'][$options['Current_Save_ID']] = $merged_save;
    
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
    return $options;
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
                <?php do_settings_sections('theme_options_slug'); ?>
                <p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Upload Images') ?>" /></p>
            </form>
    	</div>
        <?php //$settings = get_option( 'artpress_theme_options' );  
        ht_create_form($settings); ?>

	</div>
<?php }*/