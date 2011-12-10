<?php
$dir = get_template_directory() . '/';

require_once 'heart-theme-utils.php'; // FIXME
require_once $dir . 'update.php';
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
require_once $dir . 'form/advanced-form.php';
require_once $dir . 'form/typography.php';
require_once $dir . 'form/layout.php';
require_once $dir . 'form/effect.php';
require_once $dir . 'form/background-image.php';
require_once $dir . 'form/configuration.php';
require_once $dir . 'form/default-configurations.php';
require_once $dir . 'form/image-form.php';
require_once $dir . 'form/ajax-handlers.php';

add_action( 'admin_enqueue_scripts', 'init_register_scripts' );
add_action( 'admin_init', 'init_artpress_theme' );
add_action( 'admin_menu', 'init_add_theme_pages' );

// Load scripts
function init_register_scripts() {

    $td = get_bloginfo('template_directory');
    $ghjq = 'http://ajax.googleapis.com/ajax/libs/jqueryui/'; // Google hosted jQuery

    // register styles
    wp_register_style( 'jquery-ui-theme-ui-lightness' , $ghjq . '1.8.16/themes/ui-lightness/jquery-ui.css' );
    wp_register_style( 'farbtasticStylesheet',          $td . '/scripts/farbtastic/farbtastic.css');
    wp_register_style( 'theme-modifications',           $td . '/form/css/theme-modifications.css');
    wp_register_style( 'image-form',                    $td . '/form/css/image-form.css');
    wp_register_style( 'ArtPressOptionsStylesheet',     $td . '/form/css/form.css');
    wp_register_style( 'selectmenu',                    $td . '/form/css/jquery.ui.selectmenu.css');
    wp_register_style( 'selectmenu-modifications',       $td . '/form/css/selectmenu-modifications.css');

    // register scripts
    wp_register_script( 'jquery-ui-cdn' ,          $ghjq .'1.8.5/jquery-ui.min.js' , array('jquery') , '1.8.14.custom.min' );                                                
    wp_register_script( 'jQuery.form' ,            $td . '/form/js/jquery.form.js'          , null , '2.83' , true  );                                                          
    wp_register_script( 'farbtastic',              $td . '/scripts/farbtastic/farbtastic.js', array('jquery'));
    wp_register_script( 'spin' ,                   $td . '/form/js/spin.min.js'             , null , '1.0'  , false );                                                         
    wp_register_script( 'header' ,                 $td . '/form/js/header.js'               , null , '1.0'  , false );
    wp_register_script( 'footer' ,                 $td . '/form/js/footer.js'               , null , '1.0'  , true  );
    wp_register_script( 'selectmenu' ,             $td . '/form/js/jquery.ui.selectmenu.js' , null , '1.0'  , false );
    wp_register_script( 'selectmenu-artpress' ,    $td . '/form/js/selectmenu.js'           , null , '1.0'  , false );


    // enqueue styles
    wp_enqueue_style( 'jquery-ui-theme-ui-lightness' );
    wp_enqueue_style( 'theme-modifications' );
    wp_enqueue_style( 'farbtasticStylesheet' );
	wp_enqueue_style( 'image-form' );
    wp_enqueue_style( 'ArtPressOptionsStylesheet' );
    wp_enqueue_style( 'selectmenu' );
    wp_enqueue_style( 'selectmenu-modifications' );
    
    // enqueue scripts
    wp_enqueue_script( 'jquery-ui-cdn' );
    wp_enqueue_script( 'jQuery.form' );
    wp_enqueue_script( 'farbtastic');
    wp_enqueue_script( 'spin' );
    wp_enqueue_script( 'selectmenu' );
    wp_enqueue_script( 'selectmenu-artpress' );
    wp_enqueue_script( 'header' );
    wp_enqueue_script( 'footer' );


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

    add_submenu_page( 'artpress', 'Upgrade ArtPress', 'Upgrade', 'edit_theme_options', 'upgrade_artpress', 'page_upgrade_artpress');
    
}

add_filter('contextual_help', 'artpress_help', 10, 3);

function artpress_help( $contextual_help, $screen_id, $screen ) {
    global $page_edit_config;
    
    if( $screen_id == $page_edit_config ) {
        $o = 
             div( alink(trailingslashit(get_theme_uri()) . 'support/tutorials/', 'ArtPress Tutorials')   )
           . div( alink(trailingslashit(get_theme_uri()) . 'forums/'          , 'ArtPress Forums')      )
           . div( alink(trailingslashit(get_theme_uri()) . 'contact-us/'      , 'Contact ArtPress')  )
        ;
        
        $contextual_help .= $o; 
    }
    return $contextual_help;
}


function page_upgrade_artpress() {
    if ( upgrade_artpress() ) return;
    
    echo h2('Upgrade ArtPress');
    
    if( newer_version_available( true ) ) {
        $latest_version = version_number_array_to_string( get_latest_version_number(true) );
        $form_content = wp_nonce_field('upgrade_artpress', 'nf');
        $form_content .= input('submit', attr_name('upgrade') . attr_value('upgrade to version ' . $latest_version));
        echo form('post', '', $form_content, null);
    } else {
        echo "You have the latest version.";
    }
}

function upgrade_artpress() {
	if (empty($_POST)) return false; 
	
	
	$form_fields = array ('upgrade'); // this is a list of the form field contents I want passed along between page views
	$method = ''; // Normally you leave this an empty string and it figures it out by itself, but you can override the filesystem method here
	
	// check to see if we are trying to upgrade a file
	if (isset($_POST['upgrade'])) {
		// okay, let's see about getting credentials
		$url = wp_nonce_url('admin.php?page=upgrade_artpress', 'upgrade_artpress');
		if (false === ($creds = request_filesystem_credentials($url, $method, false, false, $form_fields) ) ) {
        	check_admin_referer('upgrade_artpress', 'nf'); // dies if haven't come from an admin page
			// if we have to get the credentials from the user, then we'll always get here
			// if we get here, then we don't have credentials yet,
			// but have just produced a form for the user to fill in, 
			// so stop processing for now
			
			return true; // stop the normal page form from displaying 
		}
			
		// now we have some credentials, try to get the wp_filesystem running
		if ( ! WP_Filesystem($creds) ) {
			// our credentials were no good, ask the user for them again
			request_filesystem_credentials($url, $method, true, false, $form_fields);
			return true;
		}

		// by this point, the $wp_filesystem global should be working, so let's use it to create a file
		global $wp_filesystem;
		
		// create zip name
		$name = get_theme_name();
		$latest_version = version_number_array_to_string( get_latest_version_number() );
		$name_ver = $name . $latest_version;
		$zip_name = $name_ver . '.zip';
		
		// create the download file location
		$local_upload_dir = wp_upload_dir();
		$zip_destination = $local_upload_dir['basedir'];
		$zip_download_path = $zip_destination . '/' . $zip_name;
		
		// download the zip
		$fh = fopen($zip_download_path, 'w');
		$upload_directory = '/wp-content/uploads/downloads/ArtPress'; // CANONICAL
		$uri = substr( get_theme_uri(), 7); // trim off http://
		$full_uri = $uri . $upload_directory . '/' . $zip_name;
		$ch = curl_init( $full_uri );
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		curl_setopt( $ch, CURLOPT_FILE, $fh );
		curl_exec($ch);
		curl_close($ch);
		fclose($fh);	
		
		$theme_dir = WP_CONTENT_DIR . '/themes/';
		
		// move zip from uploads/ to themes/
		$theme_zip_path = $theme_dir . $zip_name;
		if ( ! $wp_filesystem->copy( $zip_download_path, $theme_zip_path, true )){
		    echo h2('error moving zip to theme directory');
		    return false;
		}
		
		// rename current ArtPress
		$artpress_path = $theme_dir . $name;
		$current_artpress = $theme_dir . $name . get_theme_version_number();
		if ( ! $wp_filesystem->move( $artpress_path, $current_artpress, true ) ) {
	    echo "<h2>error renaming current theme</h2>";
	        return false;
	    }
		
		// unzip theme
		if ( ! unzip_file( $theme_dir . $zip_name, $theme_dir ) ) {
		    echo h2('failed to unzip the theme');
		    return false;
		}
		
		// register the new theme
        register_theme_directory( $artpress_path );
		
		// delete old directory
		if ( ! $wp_filesystem->delete( $current_artpress, true) ) {
		    echo "<h2>error copying zip to themes folder</h2>";
		    return false;
		}
		// delete zip
		if ( ! $wp_filesystem->delete( $theme_zip_path, false) ) {
		    echo "<h2>error copying zip to themes folder</h2>";
		    return false;
		}		
    	echo $name . " successfully updated to version " . $latest_version;
	}
	return true;
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
    
    update_theme();
    
    $options = get_option('ap_options');

    // page title stuff
    //screen_icon();
    echo ot('div', attr_class('wrap') . attr_id('style-manager'));
    echo h2( __( 'ArtPress Style Manager' ) );
    
    $init_load_script = script(
        "
        initSpinner = document.getElementById('initial_load_spinner');
        spinner = new Spinner(spinOpts).spin(initSpinner);
        spinner.spin(initSpinner);
    ");
    echo div( '', attr_id('initial_load_spinner'));
    echo $init_load_script;
    
    $notifications = div('', attr_id('themeNotifications'));
    
    // create buttons
    $delete = new Delete_Button(); 
    $live = new Live_Button($options);
    $new = new New_Button();
    $save = new Save_Button();
    $save_as = new Save_As_Button();

    // select config to edit
    $config_select = select("", get_config_select_contents($options), attr_id('change_edit_config') . attr_on_change('change_edit_config(this)') );
    
    $controls = div( 
                    h3( 
                        span('Styles') , attr_class('hndle') )
                    . div(
                         $delete->get_html() 
                        . $live->get_html() 
                        . $new->get_html() 
                        . $save_as->get_html() 
                        . $save->get_html() 
                        . $config_select
                        , attr_class('inside') )
                , attr_id('config-controls') . attr_class('postbox') );
            
    echo div( $controls . $notifications, attr_id('form-header') );
    // config type & name
    echo input('hidden', attr_name('current_config_type') . attr_value(get_current_config_type($options)) )
        . input('hidden', attr_name('current_config_name') . attr_value(get_current_config_name($options)) );
    
    
    // download all configs form
    $setting_fields = get_settings_fields('artpress_options');
    $hidden = input('hidden', attr_name('ap_options[command]') . attr_value('download_user_configs'));
    $submit = input('submit', attr_value('download all user configs'));
    $download_all_controls = form('post', 'options.php', $setting_fields . $hidden . $submit, null);
    
    // download current config form
    $setting_fields = get_settings_fields('artpress_options');
    $hidden = input('hidden', attr_name('ap_options[command]') . attr_value('download_current_config'));
    $submit = input('submit', attr_value('download current config'));
    $download_current_controls = form('post', 'options.php', $setting_fields . $hidden . $submit, null);
    
    // upload configs
    $setting_fields = get_settings_fields('artpress_options');
    $hidden = input('hidden', attr_name('ap_options[command]') . attr_value('upload_configs'));
    $file   = input('file', attr_name('config_file'));
    $submit = input('submit', attr_value('upload configs'));
    $upload_controls = form('post', 'options.php', $setting_fields . $hidden . $file . $submit, "multipart/form-data");

    // form content
    $values = Configuration::get_current_configuration_settings($options);
    echo div( get_config_form($values), attr_id('ap_options_form') );
    
    echo div(          
        $download_all_controls . " | "
        . $download_current_controls . " | "
        . $upload_controls
        , 
        attr_id('config_up_download')
     );
    
    echo ct('div');
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
    // import the default configs if defaults hasn't been set OR if the defaults are different
    if( !isset($options['configurations']['default']) || ( get_default_configurations($options) != $ap_configuration_defaults )  ) {
        $options = set_default_configurations_values($options, $ap_configuration_defaults);
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
        if( !isset($options['css']['user'][$name])) {
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

function create_date_string() {
    $d = getdate();
    $date= "{$d['year']}/{$d['mon']}/{$d['mday']} {$d['hours']}:{$d['minutes']}:{$d['seconds']}";
    return $date;    
}

function update_config($options, $new_settings) {
    $previous_save = get_current_config_values($options);
    $merged_save = array_merge_recursive_distinct($previous_save, $new_settings['cs']);
    
    // filter out default values
    $merged_save = array_filter($merged_save);
    $options = save_config(true, $options, get_current_config_type($new_settings), get_current_config_name($new_settings), $merged_save );
    return $options;
}
/** 
 * 
 * */
function save_config( $overwrite, $options, $config_type, $config_name, $config_values) {
  
    // check if the name already exists
    if( !$overwrite && user_configuration_name_exists($options, $config_name) ) {
        return handle_user_config_name_exists($options, $config_name);
    }
    
    // create a new save name if the current save if a default configuration ...
    if( $config_type == 'default') {
        $options['current-save-id'] = array('user', $config_name . ' ' . create_date_string() );
        $options = set_message($options, 'success', "Saved default configuration as \"{$options['current-save-id'][1]}\"");
    
    // ... or if the supplied save name is blank
    } elseif ( $config_name == '' ) {
        $date = create_date_string();
        $options['current-save-id'] = array('user', $date);
        $options = set_message($options, 'success', "Saved user configuration as \"{$date}\"" );
    
    } else {
        $options['current-save-id'] = array('user', $config_name);
        $options = set_message($options, 'success', "Saved user configuration \"{$options['current-save-id'][1]}\"");
    }

    
    // store as user configuration
    $options = set_current_config_values($options, $config_values);
    
    // create css
    $css = get_css($config_values);
    $options['css'][$options['current-save-id'][0]][$options['current-save-id'][1]] = $css;
    
    return $options;
}
function handle_user_config_name_exists($options, $new_config_name) {
    $options['message'] = "A configuration called {$new_config_name} already exists";
    $options['message_type'] = 'fail';
    return $options;    
}
function import_configs($options, $configs_array) {
    foreach(array_keys($configs_array) as $config_name) {
        $options = save_config( true, $options, 'user', $config_name, $configs_array[$config_name] ); // TODO currently overwrites, is this desirable?
        if($options['message_type'] == 'fail') return $options;
    }
    $options = set_message($options, 'success', 'Successfully uploaded config file');
    return $options;
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
        
        // change edit config
        if( $new_settings['command'] == 'change_config_to_edit' ) {
            $edit_arr = explode('__', $new_settings['change_current-save-id'], 2);
            $options['current-save-id'] = array( $edit_arr[0], $edit_arr[1] ); // TODO use helper functions
            $options['message'] = "Now editing {$edit_arr[0]} configuration '{$edit_arr[1]}'.";
            $options['message_type'] = 'success';
            return $options;
        }
        
        // change live/public config
        elseif( $new_settings['command'] == 'change_live_config' ) {
            $options['live_config_id'] = array($new_settings['change_live_config_id'][0], $new_settings['change_live_config_id'][1]);
            $options['message'] = "The {$options['live_config_id'][0]} configuration '{$options['live_config_id'][1]}' is now live.";
            $options['message_type'] = 'success';
            return $options;
        }
        
        // create new config
        elseif( $new_settings['command'] == 'create_new_configuration' ) {
            
            // TODO should use save configuration function instead of this!!
            
            $new_config_name = $new_settings['new_configuration_name'];
        
            // handle case where user config name already in use
            if( user_configuration_name_exists($options, $new_config_name ) ) {
                return handle_user_config_name_exists($options, $new_config_name);
            } else {
                $options['current-save-id'] = array('user', $new_config_name);
                $options['configurations']['user'][$new_config_name] = array(); //$settings;
                $options['message'] = "Successfully created new user configuration";
                $options['message_type'] = 'success';
                return $options;
            }
        }
        
        // delete config
        elseif( $new_settings['command'] == 'delete_configuration' ) {
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

        // create default options
        elseif ( $new_settings['command'] == 'create_default_options') {
            
            $current_save_id = $new_settings['current-save-id'];
            // create css
            $css = get_css($new_settings['configurations'][$current_save_id[0]][$current_save_id[1]]);
            $new_settings['css'][$current_save_id[0]][$current_save_id[1]] = $css;
            $new_settings['message'] = 'created default options';
                $new_settings['message_type'] = 'success';
            return $new_settings; 
            
        } 
        
        // save as 
        elseif ( $new_settings['command'] == 'save_as_configuration' ) {
            $new_config_name = $new_settings['current-save-id'][1];
            // handle case where user config name already in use
            if( user_configuration_name_exists($options, $new_config_name ) ) {
                return handle_user_config_name_exists($options, $new_config_name);
            } else {
                $options = save_config(false, $options, 'user', $new_config_name, $new_settings['cs']);
                return $options;
            }            
        } 
        
        // save
        elseif ( $new_settings['command'] == 'save_configuration' ) {
            
            $options = update_config($options, $new_settings);
               
        } 
        
        // download current config
        elseif($new_settings['command'] == 'download_current_config') {
            
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
            
        } 
        
        // download all user configs
        elseif($new_settings['command'] == 'download_user_configs') {
            
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
        
        // upload configs
        elseif($new_settings['command'] == 'upload_configs') {
            
            // report file upload failure
            if ($_FILES["config_file"]["error"] > 0) {
                
                $options['message'] = "Error: " . $_FILES["file"]["error"];
                $options['message_type'] = "fail";
            
            } else {
                
                $file_name = $_FILES["config_file"]["tmp_name"];
                $file_contents = file_get_contents($file_name);

                if($configs_array = json_decode($file_contents, true) ) {
                    $options = import_configs($options, $configs_array);
                } else {
                    $options['message'] = 'Cannot upload, config file is not well formed JSON';
                    $options['message_type'] = 'fail';
                }
                
            }
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