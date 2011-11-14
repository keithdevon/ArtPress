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
require_once $dir . 'form/configuration.php';
require_once $dir . 'form/default-configurations.php';
require_once $dir . 'form/image-form.php';
//    require_once $dir . 'form/download-config.php';

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
        
    wp_register_script(
    	'form',                                                          // handle
        $template_dir . '/form/form.js',                                 // src   
        null,                                                            // deps  
        '1.0',                                                           // version
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
    wp_enqueue_script('form');

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

function get_config_form($values) {
    $configuration = new Configuration('main tab group');
    $configuration->inject_values( $values );
    $config_html = $configuration->get_html();
    $setting_fields = get_settings_fields('artpress_options');
    return form('post', 'options.php', $setting_fields . $config_html, null);
}
abstract class Config_Button {
    private $value, $on_click, $class, $attributes;
    function __construct($value, $on_click, $class, $attributes='') {
        $this->value = $value;
        $this->on_click = $on_click; 
        $this->class = $class;
        $this->attributes = $attributes;
    }
    function get_html() { return input( 'button', attr_value($this->value) . attr_on_click($this->on_click) . attr_class($this->class) . ToolTips::get($this) . $this->attributes );}
}
class Save_Button    extends Config_Button { function __construct() { parent::__construct('save',    'save_config()',     'button-primary');   } }
class Save_As_Button extends Config_Button { function __construct() { parent::__construct('save as', 'save_as_config()',  'button-secondary'); } }
class Delete_Button  extends Config_Button { function __construct() { parent::__construct('delete',  'delete_config()',   'button-secondary'); } }
class New_Button     extends Config_Button { function __construct() { parent::__construct('new',     'new_config()',      'button-secondary'); } }
class Live_Button    extends Config_Button { 
    function __construct($options) { 
        $is_live = is_current_config_live($options);
        $attributes = attr_id('live_switch') . attr_disabled($is_live);
        parent::__construct('live',    'set_live_config()', 'button-secondary', $attributes );
    } 
}

function page_edit_config() {
    if ( ! isset( $_REQUEST['updated'] ) )
        $_REQUEST['updated'] = false;
    
    $options = get_option('ap_options');

    // page title stuff
    //screen_icon();
    echo ot('div', attr_class('wrap'));
    echo h2( __( 'Artpress Options' ) ); 
    $notifications = div('', attr_id('themeNotifications'));
    
    // create buttons
    $delete = new Delete_Button(); 
    $live = new Live_Button($options);
    $new = new New_Button();
    $save = new Save_Button();
    $save_as = new Save_As_Button();

    // select config to edit
    $config_select = select("", get_config_select_contents($options), attr_id('change_edit_config') . attr_on_change('change_edit_config(this)') );
    
    $controls = div( $delete->get_html() 
                . $live->get_html() 
                . $new->get_html() 
                . $save_as->get_html() 
                . $save->get_html() 
                . $config_select
            , attr_id('config-controls') );
            
    echo div( $controls . $notifications, attr_id('form-header') );
    // config type & name
    echo input('hidden', attr_name('current_config_type') . attr_value(get_current_config_type($options)) )
        . input('hidden', attr_name('current_config_name') . attr_value(get_current_config_name($options)) );
    
    // form content
    $values = Configuration::get_current_configuration_settings($options);
    echo div(get_config_form($values), attr_id('ap_options_form'));
    echo ct('div');
    
    // download all configs form
    $setting_fields = get_settings_fields('artpress_options');
    $hidden = input('hidden', attr_name('ap_options[command]') . attr_value('download_user_configs'));
    $submit = input('submit', attr_value('download all user configs'));
    echo form('post', 'options.php', $setting_fields . $hidden . $submit, null);
    
    // download current config form
    $setting_fields = get_settings_fields('artpress_options');
    $hidden = input('hidden', attr_name('ap_options[command]') . attr_value('download_current_config'));
    $submit = input('submit', attr_value('download current config'));
    echo form('post', 'options.php', $setting_fields . $hidden . $submit, null);

}
/** 
 * If the candidate config type and name match the current live config id,
 * then this function will return a string symbolising that this candidate config
 * is the live config
 * */
function add_live_tag( $options, $candidate_config_type, $candidate_config_name ) {
    if(is_live_config($options, $candidate_config_type, $candidate_config_name)) return " ( LIVE )";
    else return '';
}
function add_selected_attr( $options, $candidate_config_type, $candidate_config_name ) {
    if(is_current_config($options, $candidate_config_type, $candidate_config_name)) return attr_selected(true);
    else return '';
}
function get_config_select_contents($options) {
    $default_opts = '';
    $user_opts = '';

    // create default select options
    foreach ( get_default_configuration_names($options) as $config_name) {
        $default_opts .= option('default__' . $config_name, 
                                    $config_name 
                                        . add_live_tag($options, 'default', $config_name)
                                    , add_selected_attr($options, 'default', $config_name));
    }
    $default_group = optgroup('default configurations', $default_opts);
            
    // create user select options
    foreach ( get_user_configuration_names($options) as $config_name) {
        $user_opts .= option('user__' . $config_name, 
                                $config_name 
                                    . add_live_tag($options, 'user', $config_name)
                                , add_selected_attr($options, 'user', $config_name));
    }
    $user_group = optgroup('user configurations', $user_opts);
    
    return $default_group . $user_group;
}

/**
 * Creates a valid options array of stub, empty values for ap_options
 * */
function get_defaults() {
    global $ap_configuration_defaults;
    
    $options['command'] = 'create_default_options';

    $options['configurations']['user'] = array();
    $options['configurations']['default'] = $ap_configuration_defaults;
    
    set_current_config($options, 'default', reset(array_keys($ap_configuration_defaults)));
    set_live_config($options, get_current_config_type($options), get_current_config_name($options));
      
    $options['cs'] = get_current_config_values($options);
    
    $options['css'] = array('user' => array(), 'default' => array());// TODO create css here?
    

    return $options;
}
/**
 * Fills the options array with default or stub values.
 * */
function pad_options($options) {
    global $ap_configuration_defaults;
    
    if(!is_array($options)) {
        $options = array();
    }
    if( !isset($options['command']) ) {
        $options['command'] = 'padding_options';
    }
    if( !isset($options['configurations']) ) {
        $options['configurations'] = array();    
    }
    if( !isset($options['configurations']['user']) ) {
        $options['configurations']['user'] = array();
    }
    if( !isset($options['configurations']['default']) ) {
        $options['configurations']['default'] = $ap_configuration_defaults;
    } 
    if( !isset($options['current-save-id']) ) {
        set_current_config($options, 'default', reset(array_keys($ap_configuration_defaults)));
    }
    if( !isset($options['live_config_id']) ) {
        set_live_config($options, get_current_config_type($options), get_current_config_name($options));
    }
    if( !isset($options['cs'])) { 
        $options['cs'] = get_current_config_values($options); // TODO could be set to null, is this ok?
    }
    if( !isset($options['css']) ) {
        $options['css'] = array('user' => array(), 'default' => array());
		// TODO create css here?
    }
    // create missing default config css
    foreach( get_default_configuration_names( $options ) as $name ) {
        if( !isset($options['css']['default'][$name])) {
            $options['css']['default'][$name] = get_css($options['configurations']['default'][$name]);
        }
    }
    // create missing user config css
    foreach( get_user_configuration_names( $options ) as $name ) {
        if( !isset($options['css']['default'][$name])) {
            $options['css']['user'][$name] = get_css($options['configurations']['user'][$name]);
        }
    }
    
    if( !isset($options['message']) ) {
        $options['message'] = 'Installed Wordpress';
    }
    return $options;
}


function set_action(&$options, $action) {
   $options['command'] = $action;
}

/**
 * Function to create the options array in the db if not already created.
 */
function init_ap_options() {
    $previous_options = get_option('ap_options');

    if(!$previous_options) {
        $success = add_option('ap_options', null);
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
function handle_configuration_management_options($options, $new_settings) {
    
    // change edit config
    if( $new_settings['command'] == 'change_config_to_edit' ) {
        $edit_arr = explode('__', $new_settings['change_current-save-id'], 2);
        $options['current-save-id'] = array( $edit_arr[0], $edit_arr[1] ); // TODO use helper functions
        $options['message'] = "Now editing {$edit_arr[0]} configuration '{$edit_arr[1]}'.";
        $options['message_type'] = 'success';
        return $options;
    }
    
    // change live/public config
    if( $new_settings['command'] == 'change_live_config' ) {
        $options['live_config_id'] = array($new_settings['change_live_config_id'][0], $new_settings['change_live_config_id'][1]);
        $options['message'] = "The {$options['live_config_id'][0]} configuration '{$options['live_config_id'][1]}' is now live.";
        $options['message_type'] = 'success';
        return $options;
    }
    
    // create new config
    if( $new_settings['command'] == 'create_new_configuration' ) {
        $new_config_name = $new_settings['new_configuration_name'];
        
        // handle case where name already exists
        if( isset( $options['configurations']['user'][$new_config_name] ) ) {
            $options['message'] = "A configuration called {$new_config_name} already exists";
            $options['message_type'] = 'fail';
            return $options;
        } else {
            $options['current-save-id'] = array('user', $new_config_name);
            
            // fire up the global settings ...
            //$global_settings = new Global_Settings();
            //$settings = Setting::get_registered_settings();
            // ... and store these as the defaults for the new config
            $options['configurations']['user'][$new_config_name] = array(); //$settings;
            // TODO must create css at some point - or abstract out common css
            $options['message'] = "Successfully created new user configuration";
            $options['message_type'] = 'success';            
            return $options;
        }
    }
    
    // delete config
    if( $new_settings['command'] == 'delete_configuration' ) {
        $dead_save = $new_settings['delete-id'];
        if($dead_save[0] == 'default') {
            $options['message'] = "Cannot delete default configurations";
            $options['message_type'] = 'fail';
            return $options;
        }
        if($dead_save == $options['live_config_id']) {
            //don't do anything if the first save is being shown to the public
            $options['message'] = "Cannot delete this configuration as it is currently live.";
            $options['message_type'] = 'fail';
            return $options;
        }
        unset($options['configurations']['user'][$dead_save[1]]);
        unset($options['css']['user'][$dead_save[1]]);
        $first_save_name = key($options['configurations']['default']);

        if($dead_save == $options['current-save-id']) {
            $options['current-save-id'] = array('default', $first_save_name );
        }

        $options['message'] = "Deleted user configuration '{$dead_save[1]}'";
        $options['message_type'] = 'success';
        return $options;
    }
    
    return $options;
}
/** 
 * Simple method to encode the ajax response in a JSON format.
 * The result is then echoed. */
function send_ajax_response($response){
    $json = json_encode( $response );
    echo $json;
}
/**
 * 
 * This method reduces the boilerplate that is required to
 * create a response that switches the current config being edited.
 * This happens on a change, delete or new command.
 */
function create_change_config_response() {
    $updated_options = get_option('ap_options');
    $config_values = get_current_config_values($updated_options);
    
    // send results back to the client in the correct format
    $response = array(
            'formHTML'     => get_config_form( $config_values )
            , 'configID'         => get_current_config($updated_options)
            , 'message'          => $updated_options['message']
            , 'message_type'     => $updated_options['message_type']
            , 'isLive'	         => is_current_config_live($updated_options)
            , 'configSelectHTML' => get_config_select_contents($updated_options)
    );
    return $response;    
}

add_action('wp_ajax_save_config', 'ajax_handle_save_config');
function ajax_handle_save_config() {
    $inputs = $_POST['inputs'];
    $options = get_option('ap_options');
    $no_slash = str_replace("\\" , "", $inputs['cs']);
    $cs = json_decode($no_slash, true);
    $values = array();
    foreach ( array_keys($cs) as $key ) {
        $new_key = str_replace(']', '', str_replace('ap_options[cs][', '', $key));
        $values[$new_key] = $cs[$key];
    }
    $new_settings = array( 
    	'command'          => 'save_configuration',
        'message'         => 'saving configuration',   
    	'cs'              => $values,
        'current-save-id' => array( $inputs['configType'], $inputs['configName'] )
    ); 
    update_option('ap_options', $new_settings);
    
    // send results back to the client in the correct format
    //$config_type = $options['current-save-id'][0];
    //$config_name = $options['current-save-id'][1];
    $updated_options = get_option('ap_options');
    $response = array(
    	  'configID'         => get_current_config($updated_options)
    	, 'message'          => $updated_options['message'] 
    	, 'message_type'     => $updated_options['message_type'] 
    	, 'configSelectHTML' => get_config_select_contents($updated_options)
    	);
    send_ajax_response($response);
}
add_action('wp_ajax_get_config', 'ajax_handle_get_config');
function ajax_handle_get_config() {
    //$options = get_option('ap_options');
    if($inputs = $_POST['inputs'] ) {

        $new_settings = array( 
        	'change_current-save-id' => $inputs['config'],
        	'command'  => 'change_config_to_edit',
            'message' => 'changing current configuration',   
            //'current-save-id' => $options['current-save-id'][1]
        ); 
        
        update_option('ap_options', $new_settings);
        $response = create_change_config_response();
        send_ajax_response($response);
    }
}

add_action('wp_ajax_delete_config', 'ajax_handle_delete_config');
function ajax_handle_delete_config() {
    if($inputs = $_POST['inputs'] ) {

        $new_settings = 
            array( 
            	'delete-id'       => array( $inputs['configType'], $inputs['configName'] ),
            	'command'          => 'delete_configuration',
                'message'         => 'deleting current configuration',   
            ); 
        
        update_option('ap_options', $new_settings);
        $response = create_change_config_response();
        send_ajax_response($response);
    }
}

add_action('wp_ajax_set_live_config', 'ajax_handle_set_live_config');
function ajax_handle_set_live_config() {
    if($inputs = $_POST['inputs'] ) {
        $new_settings = 
            array( 
            	'change_live_config_id'       => array( $inputs['configType'], $inputs['configName'] ),
            	'command'          => 'change_live_config',
                'message'         => 'changing live configuration',   
            ); 
        
        update_option('ap_options', $new_settings);
        $updated_options = get_option('ap_options');
        $config_values = get_current_config_values($updated_options);
        
        // send results back to the client in the correct format
        $response = 
            array(
                 'message'           => $updated_options['message']
                , 'message_type'     => $updated_options['message_type'] 
                , 'configID'         => get_current_config($updated_options)
                , 'configSelectHTML' => get_config_select_contents($updated_options)
                , 'isLive'	         => is_current_config_live($updated_options)
                );
        send_ajax_response($response);
    }
}

add_action('wp_ajax_new_config', 'ajax_handle_new_config');
function ajax_handle_new_config() {

    if($inputs = $_POST['inputs'] ) {

        $new_settings = array( 
        	'new_configuration_name' => $inputs['config'],
        	'command'  => 'create_new_configuration',
            'message' => 'changing current configuration',   
        ); 
        
        update_option('ap_options', $new_settings);
        $response = create_change_config_response();
        send_ajax_response($response);
    }
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

    $options =  pad_options( get_option('ap_options') );
    
    if( $new_settings ) {
            
        $options = handle_configuration_management_options($options, $new_settings);
                  
        if ( $new_settings['command'] == 'create_default_options') {
            $current_save_id = $new_settings['current-save-id'];
            // create css
            $css = get_css($new_settings['configurations'][$current_save_id[0]][$current_save_id[1]]);
            $new_settings['css'][$current_save_id[0]][$current_save_id[1]] = $css;
            $new_settings['message'] = 'created default options';
                $new_settings['message_type'] = 'success';
            return $new_settings; 
            
        } else if ( $new_settings['command'] == 'save_configuration' ) {
        
            $previous_save = $options['configurations'][$options['current-save-id'][0]][$options['current-save-id'][1]]; //TODO use helper function
            $merged_save = array_merge_recursive_distinct($previous_save, $new_settings['cs']);
        
            // filter out default values
            $merged_save = array_filter($merged_save);
        
            // set the current-save-id
            // TODO check if the name already exists
            // create a new save name if the current save if a default configuration ...
            if( $new_settings['current-save-id'][0] == 'default') {
                $options['current-save-id'] = array('user', $new_settings['current-save-id'][1]);// . " [${date}]");
                $options['message'] = "Saved default configuration as \"{$options['current-save-id'][1]}\"";
                $options['message_type'] = 'success';
            
                // ... or if the supplied save name is blank 
            } elseif ( $new_settings['current-save-id'][1] == '' ) {
                $d = getdate();
                $date= "{$d['year']}/{$d['mon']}/{$d['mday']} {$d['hours']}:{$d['minutes']}:{$d['seconds']}";
                $options['current-save-id'] = array('user', $date);
                $options['message'] = "Saved user configuration as \"{$date}\"";
                    $options['message_type'] = 'success';
            
            } else {
                $options['current-save-id'] = array('user', $new_settings['current-save-id'][1]);
                $options['message'] = "Saved user configuration \"{$options['current-save-id'][1]}\"";
            }
        
            // store as user configuration
            $current_config_name = $options['current-save-id'][1];
            $options['configurations']['user'][$current_config_name] = array(); 
            $options['configurations']['user'][$current_config_name] = $merged_save;
        
            // create css
            $css = get_css($merged_save);
            $options['css'][$options['current-save-id'][0]][$options['current-save-id'][1]] = $css;
        
        } else if($new_settings['command'] == 'download_current_config') {
            // get the configs
            $config_name = get_current_config_name($options);
            $config = get_current_config_values($options);
    
            // get rid of any existing stuff in the buffer, and create new header
            ob_clean();
            header( "Content-type: text/plain" );
            header('Content-Disposition: attachment; filename="current-config.txt"');
            
            // format the output
            $new_lines_for_commas = str_replace(',',",\n",json_encode(array($config_name => $config)));
            $new_lines_for_open_brackets = str_replace('{', "{\n", $new_lines_for_commas);
            $new_lines_for_close_brackets = str_replace('}', "}\n", $new_lines_for_open_brackets);
            
            echo $new_lines_for_close_brackets;
            exit;
        } else if($new_settings['command'] == 'download_user_configs') {
            // get the configs
            $user_configs = get_user_configurations($options);
    
            // get rid of any existing stuff in the buffer, and create new header
            ob_clean();
            header( "Content-type: text/plain" );
            header('Content-Disposition: attachment; filename="user-configs.txt"');
            
            // format the output
            $new_lines_for_commas = str_replace(',',",\n",json_encode($user_configs));
            $new_lines_for_open_brackets = str_replace('{', "{\n", $new_lines_for_commas);
            $new_lines_for_close_brackets = str_replace('}', "}\n", $new_lines_for_open_brackets);
            
            echo $new_lines_for_close_brackets;
            exit;
        }
    }
    return $options;
}
class CSS_Setting_Visitor implements Visitor {
    function recurse($hierarchy) {
        return $hierarchy->has_children();
    }
    function valid_child($hierarchy) {
        if ( ( $hierarchy instanceof CSS_Setting ) &&
             !( $hierarchy instanceof IComposite_Part ) ) {
            $parent = $hierarchy->get_parent();
            if ( $parent instanceof IToggle_Group ) {
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