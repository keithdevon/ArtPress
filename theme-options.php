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

add_action( 'admin_init', 'init_register_scripts' );
add_action( 'admin_init', 'init_artpress_theme' );
add_action( 'admin_menu', 'init_add_theme_pages' );

// Load scripts
function init_register_scripts() {

    $template_dir = get_bloginfo('template_directory');
    $template_url = get_bloginfo('template_url');

    // register scripts
    wp_register_script(
    	'jqueryui1814',                                                  // handle
        $template_dir . '/js/ui1814/js/jquery-ui-1.8.14.custom.min.js',  // src
        array('jquery'));                                                // deps
        
    wp_register_script(
    	'jQuery.form',                                                   // handle
        $template_dir . '/js/jquery.form.js',                            // src   
        null,                                                            // deps  
        '2.83',                                                          // version
        true);                                                           // in footer?

    // register styles
    wp_register_style(                                                               
    	'ArtPressOptionsStylesheet',                                                 // handle
        $template_url . '/form/form.css');                                           // src   
        
    wp_register_style(                                                               
    	'farbtasticStylesheet',                                                      // handle
        $template_url . '/scripts/farbtastic/farbtastic.css');                       // src   
        
    wp_register_style(
    	'jqueryui1814css',                                                           // handle
        $template_dir . '/js/ui1814/css/ui-lightness/jquery-ui-1.8.14.custom.css');  // src   
        
    wp_register_style(
    	'image_form',                                                                // handle
        $template_url . '/form/image-form.css');                                     // src   

    // enqueue scripts
    wp_enqueue_script('jqueryui1814');
    wp_enqueue_script(
    	'farbtastic', 
        $template_dir . '/scripts/farbtastic/farbtastic.js', 
        array('jquery'));
    wp_enqueue_script('jQuery.form');

    // enqueue styles
    wp_enqueue_style( 'ArtPressOptionsStylesheet' );
    wp_enqueue_style( 'jqueryui1814css' );
    wp_enqueue_style( 'farbtasticStylesheet' );
	wp_enqueue_style( 'image_form' );

    add_action('init', 'ht_init_method');
}
/**
 * Init plugin options to white list our options
 */
function init_artpress_theme() {
    register_setting( 'artpress_options',       'ap_options', 'handle_ap_options' );
    register_setting( 'artpress_image_options', 'ap_images',  'ap_image_validate' );
    init_ap_options();
}

$page_edit_config = null;
/**
 * Load up the menu page
 */
function init_add_theme_pages() {

    // set up main settings page
    global $page_edit_config;
    $page_edit_config = add_menu_page( 
        __( 'ArtPress Options' ),    // page title
        __( 'Artpress' ),            // menu title
        'edit_theme_options',        // capability
        'artpress',                  // menu slug                      
        'page_edit_config',          // page rendering function
        '',                          // icon URL
        0                            // menu position
        );

    // set up configurations page
    add_submenu_page(
    	'artpress',                  // parent slug
        __('Configurations'),        // page title  
        __('Configurations'),        // menu title
        'edit_theme_options',        // capability
        'manage_configurations',     // menu slug
        'page_configs'            // page rendering function
        );

    // set up images page
    add_submenu_page(
    	'artpress',                  // parent slug            
        __('Images'),                // page title             
        __('Images'),                // menu title             
        'edit_theme_options',        // capability             
        'manage_images',             // menu slug              
        'ap_image_upload_page'       // page rendering function
        );
}

/**
 * HACK ALERT! creating my own 'settings_fields' that doesn't echo but returns its contents.
 * Seems to work pretty well though!
 * */
function get_settings_fields($option_group) {
    $o = input('hidden', attr_name('option_page') . attr_value(esc_attr($option_group)));
    $o .= input('hidden', attr_name('action') . attr_value('update'));
	$o .= wp_nonce_field("$option_group-options", "_wpnonce", true, false);
	return $o;
}

function page_edit_config() {
    if ( ! isset( $_REQUEST['updated'] ) )
        $_REQUEST['updated'] = false;

    $configuration = new Configuration('main tab group');
    $options = get_option('ap_options');

    // page title stuff
    //screen_icon();
    echo ot('div', attr_class('wrap'));
    echo h2( get_current_theme() . __( ' Options' ) ); 
    // TODO ^ source of why k & j see different stuff

    // populate configuration with its settings
    if ($options != null) {
        
        $configuration->inject_values(
            array_merge(array('current-save-id'=>$options['current-save-id']),
                        Configuration::get_current_configuration_settings($options))
        );
    
    }
    
    // output the configuration html
    echo $configuration->get_html();
    echo ct('div');
}
function get_config_select($options, $setting_name) {
    $setting_arr = $options[$setting_name];
    
    // create default select options
    $default_opts = '';
    foreach (array_keys($options['configurations']['default']) as $config_name) {
        if( $setting_arr[0] == 'user' || $config_name != $setting_arr[1] ) 
            $default_opts .= option('default__' . $config_name, $config_name);
    }
    $default_group = optgroup('default configurations', $default_opts);
            
    // create user select options
    $user_opts = '';
    foreach (array_keys($options['configurations']['user']) as $config_name) {
        // omit the currently selected configuration from select choice
        if( $setting_arr[0] == 'default' || $config_name != $setting_arr[1] ) 
            $user_opts .= option('user__' . $config_name, $config_name);
    }
    $user_group = optgroup('user configurations', $user_opts);
    return select("ap_options[{$setting_name}]", $default_group . $user_group);
}

function form_config_action($options, $flag, $setting_name, $setting_label, $submit_button_text) {
    $o = '<form method="post" action="options.php">';
    $o .= get_settings_fields('artpress_options');
    $o .= input('hidden', attr_name("ap_options[action]") . attr_value( $flag ) );
            
    $select = get_config_select($options, $setting_name);
    
    // create the rest of the form elements
    $o .=  tr(td($setting_label)
            . td($select)
            . td("<span class='submit'><input type='submit' class='button-primary' value='" . __( $submit_button_text ) . "' /></span>")
            );
    
    $o .= ct('form');
    return $o;
}

function page_configs() {
    $options = get_option('ap_options');
    $o = '';
    $o .= h2('Theme configurations');
    $o .= ot('table');
    
    // notify the user which configuration is currently live
    $live = $options['live_config_id'];
    $o .= tr( td(label( 'live_config_id', __('The current public facing configuration') ) ) .
              td(input( 'text', attr_readonly() . attr_value( $live[0] . ' : ' . $live[1] ) ) ) );
    
    // notify the user configuration they're currently editing
    $editing = $options['current-save-id'];          
    $o .= tr( td(label( 'current-save-id', __('The configuration currently being edited') ) ) .
              td(input( 'text', attr_readonly() . attr_value( $editing[0] . ' : ' . $editing[1] ) ) ) );

    // 'live configuration' selector
    $o .= form_config_action($options, 'change_live_config', 'live_config_id', 'Select a different public configuration', 'live');

    // 'configuration to edit' selector
    $o .= form_config_action($options, 'change_config_to_edit', 'current-save-id', 'Select a different configuration to edit', 'edit');

    // delete a configuration selector
    $o .= '<form method="post" action="options.php">';
    $o .= get_settings_fields('artpress_options');
    $o .= input('hidden', attr_name('ap_options[action]') . attr_value('delete_configuration') );
    // user can only delete user configurations
    if( $options && isset($options['configurations']['user'])) {
       $opts = '';
       foreach (array_keys($options['configurations']['user']) as $save_name) {
           // don't give option to delete a user config if it's the current live config
           if($options['live_config_id'][0] == 'default' || $save_name != $options['live_config_id'][1]) {
               $opts .= option($save_name, $save_name);
           }
       }
       if( $opts ) {
           $delete_input = select("ap_options[delete-id]", $opts);
       } else {
           $delete_input = input('text', attr_readonly() . attr_value('no user configurations'));
       }
       $o .=  tr(td('Select a user configuration to delete')
               . td( $delete_input )
               . td("<span class='submit'><input type='submit' class='button-primary' value='" . __( 'delete' ) . "' /></span>")
               );
    }
    $o .= ct('form');


    // create new configuration
    $o .= '<form method="post" action="options.php">';
    $o .= get_settings_fields('artpress_options');
    $o .= input('hidden', attr_name('ap_options[action]') . attr_value('create_new_configuration') );

    $o .= ot('tr');
    $o .= td(label('new_configuration',__('Create new configuration')));
    $o .= td(input('text', attr_name('ap_options[new_configuration_name]'). attr_id('new_configuration')));
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
function get_defaults() {
    global $ap_configuration_defaults;
    
    $options['action'] = 'create_default_options';

    $options['configurations']['user'] = array();
    $options['configurations']['default'] = $ap_configuration_defaults;
    
    $options['current-save-id'] = array('default', reset(array_keys($ap_configuration_defaults)));
    $options['live_config_id'] = $options['current-save-id'];
      
    $options['cs'] = $options['configurations'][$options['current-save-id'][0]][$options['current-save-id'][1]];
    
    $options['css'] = array('user' => array(), 'default' => array());// TODO create css here?
    

    return $options;
}

/**
 * Function to create the options array in the db if not already created.
 */
function init_ap_options() {
    $opt1 = get_option('ap_options');

    if ( $opt1 == null ) {
        $opt1 = get_defaults();
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
    
    // change edit config
    if( $new_settings['action'] == 'change_config_to_edit' ) {
        $edit_arr = explode('__', $new_settings['current-save-id'], 2);
        $options['current-save-id'] = array( $edit_arr[0], $edit_arr[1] );
        $options['message'] = "Now editing {$edit_arr[0]} configuration \"{$edit_arr[1]}.";
        return $options;
    }
    
    // change live/public config
    if( $new_settings['action'] == 'change_live_config' ) {
        $live_arr = explode('__', $new_settings['live_config_id'], 2);
        $options['live_config_id'] = array($live_arr[0], $live_arr[1]);
        $options['message'] = "The {$live_arr[0]} configuration, {$live_arr[1]}, is now live.";
        return $options;
    }
    
    // create new config
    if( $new_settings['action'] == 'create_new_configuration' ) {
        $new_config_name = $new_settings['new_configuration_name'];
        
        // handle case where name already exists
        if( isset( $options['configurations']['user'][$new_config_name] ) ) {
            $options['message'] = "A configuration called {$new_config_name} already exists";
            return $options;
        } else {
            $options['current-save-id'] = array('user', $new_config_name);
            $options['configurations']['user'][$new_config_name] = array();
            $options['message'] = "Successfully created new user configuration";
            return $options;
        }
        // TODO must create css at some point - or abstract out common css
    }
    
    // delete config
    if( $new_settings['action'] == 'delete_configuration' ) {
        $dead_save = $new_settings['delete-id'];
        if($dead_save == $options['live_config_id']) {
            //don't do anything if the first save is being shown to the public
            $options['message'] = "Cannot delete this configuration as it is currently live.";
            return $options;
        }
        unset($options['configurations']['user'][$dead_save]);
        unset($options['css'][$dead_save]);
        $first_save = key($options['configurations']['user']);

        if($dead_save == $options['current-save-id']) {
            $options['current-save-id'] = $first_save;
        }

        $options['message'] = "Deleted configuration {$dead_save}.";
        return $options;
    }
    
    return false;
}
/**
 * All user interactions with theme settings are routed through this function.
 * ie everytime the user saves some settings, this function is invoked.
 * 
 * @var new_settings will either be what is passed to update_option
 * or what is returned from the options form
 *
 * we need to merge the new settings with the old settings.
 * if we were to populate our new form using only the new settings
 * provided by the client's browser, then checkboxes would disappear
 * as no record of unticked checkboxes are returned to the server
 * from the web browser.
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
function handle_ap_options( $new_settings ) {

    if($options = handle_configuration_management_options($new_settings)) {

        return $options;
        
    } else {
        
        if ( $new_settings['action'] == 'create_default_options') {
            $current_save_id = $new_settings['current-save-id'];
            // create css
            $css = get_css($new_settings['configurations'][$current_save_id[0]][$current_save_id[1]]);
            $new_settings['css'][$current_save_id[0]][$current_save_id[1]] = $css;
            $new_settings['message'] = 'created default options';
            return $new_settings; 
            
        } else if ( $new_settings['action'] == 'save_configuration' ) {
            $options = get_option('ap_options');
        
            // if options have never been set before create some default options
            if( $options == null) $options = get_defaults(); 
        
            $previous_save = $options['configurations'][$options['current-save-id'][0]][$options['current-save-id'][1]];
            if ($new_settings == null ) {
                $new_settings = array('cs'=>array());
            }
            $merged_save = array_merge_recursive_distinct($previous_save, $new_settings['cs']);
        
            // filter out default values
            $merged_save = array_filter($merged_save);
        
            // set the current-save-id
            // TODO check if the name already exists
            // create a new save name if the current save if a default configuration ...
            if( $options['current-save-id'][0] == 'default') {
                $d = getdate();
                $date= "{$d['year']} {$d['month']} {$d['mday']} {$d['weekday']} {$d['hours']}:{$d['minutes']}:{$d['seconds']}";
                $options['current-save-id'] = array('user', $new_settings['current-save-id'] . " (${date})");
                $options['message'] = "Saved default configuration as \"{$options['current-save-id'][1]}\"";
            // ... or if the supplied save name is blank 
            } elseif ( $new_settings['current-save-id'][1] == '' ) {
                $d = getdate();
                $date= "{$d['year']} {$d['month']} {$d['mday']} {$d['weekday']} {$d['hours']}:{$d['minutes']}:{$d['seconds']}";
                $options['current-save-id'] = array('user', $date);
                $options['message'] = "Saved user configuration as \"{$date}\"";
            } else {
                $options['current-save-id'] = array('user', $new_settings['current-save-id']);
                $options['message'] = "Saved user configuration \"{$options['current-save-id']}\"";
            }
        
            // store as user configuration
            $options['configurations']['user'][$options['current-save-id'][1]] = $merged_save;
        
            // create css
            $css = get_css($merged_save);
            $options['css'][$options['current-save-id'][0]][$options['current-save-id'][1]] = $css;
        
        }
        return $options;
    }
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
function get_css($save) {
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