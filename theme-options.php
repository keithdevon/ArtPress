<?php
$dir = get_template_directory() . '/';

require_once 'heart-theme-utils.php';
include_once $dir . 'form/heart-theme-form-functions.php';
require_once $dir . 'form/tooltips.php';
require_once $dir . 'form/form.php';
require_once $dir . 'form/setting.php';
require_once $dir . 'form/css-selectors.php';
require_once $dir . 'form/images.php';
require_once $dir . 'form/global.php';
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
require_once $dir . 'form/default-configurations.php';
require_once $dir . 'form/image-form.php';

add_action( 'admin_init', 'artpress_options_load_scripts' );
add_action( 'admin_init', 'artpress_theme_init' );
add_action( 'admin_menu', 'theme_options_add_page' );


// Load our scripts
function artpress_options_load_scripts() {

    $template_dir = get_bloginfo('template_directory');
    $template_url = get_bloginfo('template_url');

    // register scripts
    wp_register_script('jqueryui1814', $template_dir . '/js/ui1814/js/jquery-ui-1.8.14.custom.min.js', array('jquery') );
    wp_register_script('jQuery.form',  $template_dir . '/js/jquery.form.js', null, '2.83', true);

    // register styles
    wp_register_style( 'ArtPressOptionsStylesheet', $template_url . '/scripts/farbtastic/farbtastic.css' );
    wp_register_style( 'jqueryui1814css',           $template_dir . '/js/ui1814/css/ui-lightness/jquery-ui-1.8.14.custom.css' );
    wp_register_style('image_form',                 $template_url . '/form/image-form.css');

    // enqueue script
    wp_enqueue_script('jqueryui1814');
    wp_enqueue_script('farbtastic', $template_dir . '/scripts/farbtastic/farbtastic.js', array('jquery'));
    wp_enqueue_script('jQuery.form');

    // enqueue style
    wp_enqueue_style( 'jqueryui1814css' );
    wp_enqueue_style( 'ArtPressOptionsStylesheet' );
	wp_enqueue_style('image_form');

    add_action('init', 'ht_init_method');
}
/**
 * Init plugin options to white list our options
 */
function artpress_theme_init() {
    register_setting( 'artpress_options',       'ap_options', 'ap_options_validate' );
    register_setting( 'artpress_image_options', 'ap_images',  'ap_image_validate' );
    init_ap_options();
}

$ap_settings_page = null;
/**
 * Load up the menu page
 */
function theme_options_add_page() {
    // set up main settings page
    global $ap_settings_page;
    $ap_settings_page = add_menu_page( __( 'ArtPress Options' ), __( 'Artpress' ),     'edit_theme_options', 'artpress',              'ap_settings_page', '', 0 );

    //add_action( 'admin_footer-'. $ap_settings_page, 'myplugin_admin_footer' );

    // set up configurations page
    add_submenu_page('artpress', __('Configurations'),     __('Configurations'), 'edit_theme_options', 'manage_configurations', 'ap_configs_page');

    // set up images page
    add_submenu_page('artpress', __('Images'),             __('Images'),         'edit_theme_options', 'manage_images',         'ap_image_upload_page');
}


/**
 * HACK ALERT! creating my own 'settings_fields' that doesn't echo but returns its contents.
 * Seems to work pretty well though!
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


    $configuration = new Configuration('main tab group');
    $options = get_option('ap_options');
    if ($options != null) {
        if (isset($options['configurations']['user'][$options['current-save-id']])) {
            $configuration->inject_values(array_merge(array('current-save-id'=>$options['current-save-id']),
                                                     $options['configurations']['user'][$options['current-save-id']]));
            }
    }
    echo $configuration->get_html();
    echo ct('div');
}
function create_config_form($options, $flag, $setting_name, $setting_label, $submit_button_text) {
    $o = '<form method="post" action="options.php">';
    $o .= get_settings_fields('artpress_options');
    $o .= input('hidden', attr_name("ap_options[{$flag}]") . attr_value('true') );
    if( $options && isset($options['configurations']['user'])) {
        $opts = '';
        foreach (array_keys($options['defaults']) as $default_name) {
            $opts .= option($default_name, $default_name);
        }
        $optgroups = optgroup('default configurations', $opts);
        $opts = '';
        foreach (array_keys($options['configurations']['user']) as $save_name) {
            if($save_name != $options[$setting_name])
                $opts .= option($save_name, $save_name);
        }
        $optgroups .= optgroup('user configurations', $opts);
        $o .=  tr(td($setting_label)
                . td(select("ap_options[{$setting_name}]", $optgroups) )
                . td("<span class='submit'><input type='submit' class='button-primary' value='" . __( $submit_button_text ) . "' /></span>")
                );
    }
    $o .= ct('form');
    return $o;
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

    // create 'live configuration' selector
    $o .= create_config_form($options, 'change_live-id', 'live-id', 'new live configuration', 'live');

    // create 'configuration to edit' selector
    $o .= create_config_form($options, 'change_current-save-id', 'current-save-id', 'configuration to edit', 'edit');

    // delete a configuration
    $o .= '<form method="post" action="options.php">';
    $o .= get_settings_fields('artpress_options');
    $o .= input('hidden', attr_name('ap_options[delete_configuration]') . attr_value('true') );
    if( $options && isset($options['configurations']['user'])) {
       $opts = '';
       foreach (array_keys($options['configurations']['user']) as $save_name) {
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
 * Creates a valid options array of stub, empty values for ap_options
 * */
function get_ap_options_defaults() {
    global $ap_configuration_defaults;
    
    $options = array( 'cs'=>array() );

    $options['configurations']['user'] = array();
    $options['current-save-id'] = 'default';
    $options['live-id'] = 'default';
    $options['configurations']['user'][$options['current-save-id']] = $options['cs'];

    $options['defaults'] = $ap_configuration_defaults;

    return $options;
}
/**
 * Creates a valid options array of stub, empty values for ap_images
 */
function get_ap_image_defaults() {
    $options = array();
    $options['images'] = array();
    $options['logo-image'] = null;
    return $options;
}
/**
 * Function to create the options array in the db if not already created.
 */
function init_ap_options() {
    $opt1 = get_option('ap_options');

    if ( $opt1 == null ) {
        $opt1 = get_ap_options_defaults();
        add_option('ap_options', $opt1);
    }

    $opt2 = get_option('ap_images');

    if ( $opt2 == null ) {
        $opt2 = get_ap_image_defaults();
        add_option('ap_images', $opt2);
    }

}
/** 
 * This function handles macro changes to the configurations ie
 * - creating a new configuration
 * - choosing a new live configuration
 * - choosing which configuration to edit
 * - deleting a configuration
 * 
 * If none of these operations have been requested, the function returns false.
 * */
function handle_configuration_management_options($new_settings) {
    
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
        $options['configurations']['user'][$options['current-save-id']] = array();
        return $options;
    }
    if( isset( $new_settings['delete_configuration'] ) ) {
        $dead_save = $new_settings['delete-id'];
        unset($options['configurations']['user'][$dead_save]);
        unset($options['css'][$dead_save]);
        $first_save = key($options['configurations']['user']);

        if($dead_save == $options['current-save-id']) {
            $options['current-save-id'] = $first_save;
        }
        if($dead_save == $options['live-id']) {
            $options['live-id'] = $first_save;
        }

        return $options;
    }
    return false;
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

    if($options = handle_configuration_management_options($new_settings)) return $options;
    
    // if options have never been set before create some default options
    if( $options == null) $options = get_ap_options_defaults(); 

    $previous_save = $options['configurations']['user'][$options['current-save-id']];
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
    $options['configurations']['user'][$options['current-save-id']] = $merged_save;

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

    $configuration = new Configuration('main tab group');
    $configuration->inject_values($save);

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