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
    
    wp_register_style('image_form', get_bloginfo('template_url') . '/form/image.css');
	wp_enqueue_style('image_form');
	
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

function ap_image_upload_page() {
    global $post;
    $settings = get_option('ap_images');
    $o = get_settings_fields('artpress_image_options');  
   
    // display existing artpress background images
    $rows = row(
        th('image name') . 
        th('description') .
        th('thumbnail') . 
        th('use as logo') .
        th('delete images'));

    // display table of images
    if(isset($settings['images']) && $images = $settings['images']) {
        foreach( array_keys($images) as $image_id ) {
            if($image = get_post($image_id) ) {
                $aid = $image->ID;     
                $title = $image->post_title;
                $desc = $image->post_content;
                $img = wp_get_attachment_image($aid, 'thumbnail');
                // display logo radio selector
                $radio = input('radio', 
                                attr_name("ap_images[logo-image]") 
                                . attr_value($aid)
                                // ensure the correct radio is checked
                                . attr_checked(( isset($settings['logo-image']) && $aid == $settings['logo-image'])));
                // display delete checkbox
                $checkbox = input('checkbox', attr_name("ap_images[delete-image][{$aid}]") 
                );
                $rows .= row( td($title) .
                    td( $desc ) .
                    td( $img ) . 
                    td( $radio ) .
                    td( $checkbox ) 
                ); 
            }
        }
        $o .= h3('Background images');
        $o .= table($rows);
    } else {
        $o .= p("Uploaded images will be displayed here.");
    }
    
    // display new image selector
    $o .= h3('Upload new image'); 
    $rows = row(td('select image') . td(input('file', attr_name('uploaded-image') . attr_size('40') )));
    $rows .= row(td('optional description') . td(input('text', attr_name('ap_images[image-description]') . attr_size(30) )));
    $o .= table($rows);

	$o .= button_submit('submit');

	$form = form( 'post', 'options.php', $o, 'multipart/form-data' );
    $div = div($form, attr_class('wrap') );
    echo $div;
}
function ap_image_validate($input) {
    $new_settings = array();
    if( $input ) {
        // set the logo image value 
        if( $logo = $input['logo-image'] ) {
            $new_settings['logo-image'] = $logo;
        }
        // delete images
        if( $delete = $input['delete-image']) {
            foreach( array_keys($delete) as $aid ) {

                wp_delete_attachment($aid);
            
                // delete logo-image from settings if the logo image is being deleted
                if ( $logo == $aid ) unset( $new_settings['logo-image'] );
                // TODO delete parent post as well

            }
        }
    }
    // first upload the file
    $file = $_FILES['uploaded-image'];
    // make sure the file is named correctly
    if ($file && $file['name'] != "" ) {    
        
        // create new post attributes
        //$postarr = array(
        //    'post_title' => 'artpress background image',
        //    'post_content' => 'This is an artpress background image.',
        //    'tags_input'=>'artpress',
        //    'post_status' => 'private'
        //);
        //// create new post
        //$new_post_id = wp_insert_post($postarr);
        
        // upload the file the uploads folder
        $override_defaults = array('test_form' => false); 
        $uploaded_file = wp_handle_upload($file, $override_defaults); 
        $attachment_arr = array(
			'post_mime_type' => $uploaded_file['type'],
			'post_title' => preg_replace('/\.[^.]+$/', '', basename($uploaded_file['file'])),
			'post_content' => '' . $input['image-description'],
            'tags_input'=>'artpress',//$tag_id,
			'post_status' => 'inherit');
        $filename = $uploaded_file['file'];
        $attach_id = wp_insert_attachment($attachment_arr, $filename); //, $new_post_id);
        
        // http://stackoverflow.com/questions/2674069/adding-posts-with-thumbnail-programatically-in-wordpress
        $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
        wp_update_attachment_metadata( $attach_id,  $attach_data );
        
        //$new_attachment = get_post($attach_id);
        //if(!isset($new_settings['images'])) $new_settings['images'] = array();
        $previous_settings = get_option('ap_images');
        $new_settings = array_merge_recursive_distinct($previous_settings, $new_settings);
        $new_settings['images'][$attach_id] = wp_get_attachment_url($attach_id);
    }
    return $new_settings;
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

function ap_configs_page() {
    $options = get_option('ap_options');
    $o = '';
    $o .= h2('configurations');
    $o .= ot('table');
    $o .= tr( td(label( 'live-id', __('current live configuration') ) ) .
              td(input( 'text', attr_readonly() . attr_value( $options['live-id'] ) ) ) );
                         
    $o .= tr( td(label( 'current-save-id', __('current editable configuration') ) ) .
              td(input( 'text', attr_readonly() . attr_value( $options['current-save-id'] ) ) ) );


    // select live configuration
    $o .= '<form method="post" action="options.php">';
    $o .= get_settings_fields('artpress_options');
    $o .= input('hidden', attr_name('ap_options[change_live-id]') . attr_value('true') );
    if( $options && isset($options['saves'])) {
        $opts = '';
        foreach (array_keys($options['saves']) as $save_name) {
            if($save_name != $options['live-id'])
                $opts .= option($save_name, $save_name);          
        }  
        $o .=  tr(td('new live configuration') 
                . td(select("ap_options[live-id]", $opts) )
                . td("<span class='submit'><input type='submit' class='button-primary' value='" . __( 'live' ) . "' /></span>")
                );
    }
    $o .= ct('form');

    
    // select a configuration to edit
    $o .= '<form method="post" action="options.php">';
    $o .= get_settings_fields('artpress_options');
    $o .= input('hidden', attr_name('ap_options[change_current-save-id]') . attr_value('true') );
    if( $options && isset($options['saves'])) {
        $opts = '';
        foreach (array_keys($options['saves']) as $save_name) {
            if($save_name != $options['current-save-id'])
                $opts .= option($save_name, $save_name);          
        }  
        $o .=  tr(td('new configuration to edit') 
                . td(select("ap_options[current-save-id]", $opts) )
                . td("<span class='submit'><input type='submit' class='button-primary' value='" . __( 'edit' ) . "' /></span>")
                );
    }
    $o .= ct('form');

    
    // delete a configuration  
    $o .= '<form method="post" action="options.php">';
    $o .= get_settings_fields('artpress_options');
    $o .= input('hidden', attr_name('ap_options[delete_configuration]') . attr_value('true') );
    if( $options && isset($options['saves'])) {
       $opts = '';
       foreach (array_keys($options['saves']) as $save_name) {
           if($save_name != 'default')
               $opts .= option($save_name, $save_name);          
       }  
       $o .=  tr(td('delete configuration') 
               . td(select("ap_options[delete-id]", $opts) )
               . td("<span class='submit'><input type='submit' class='button-primary' value='" . __( 'delete' ) . "' /></span>")
               );
    }
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
    $options['live-id'] = 'default';
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
    if( isset($new_settings['change_live-id'] ) ) {
        $options['live-id'] = $new_settings['live-id'];
        return $options;
    }
    if( isset($new_settings['create_new_configuration'] ) ) {
        $new_config_name = $new_settings['current-save-id'];
        $options['current-save-id'] = $new_config_name;
        $options['saves'][$options['current-save-id']] = array();
        return $options;
    }
    if( isset( $new_settings['delete_configuration'] ) ) {
        $dead_save = $new_settings['delete-id'];
        unset($options['saves'][$dead_save]);
        unset($options['css'][$dead_save]);
        $first_save = key($options['saves']);
        
        if($dead_save == $options['current-save-id']) {
            $options['current-save-id'] = $first_save;
        }
        if($dead_save == $options['live-id']) {
            $options['live-id'] = $first_save;
        }
               
        return $options;
    }
    if( $options == null) $options = get_ap_options_defaults(); // if options have never been set before create some default options

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
    
    // store save
    $options['saves'][$options['current-save-id']] = $merged_save;
    
    // create css
    $css = create_css($merged_save);
    $options['css'][$options['current-save-id']] = $css;
    
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
function create_css($save) {
    $output = "";
    
    $maintabgroup = new Main_Tab_Group('main tab group');
    $maintabgroup->inject_values($save);

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