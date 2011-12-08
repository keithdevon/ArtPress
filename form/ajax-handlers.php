<?php
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
function common_save_function( $command, $message ) {
    /*
    * Format the new settings and update options
    * */
    
    $options = get_option('ap_options');
    $inputs = $_POST['inputs'];
    $current_settings = $inputs['cs'];
    
    // replace \" with "
    $no_backslash_quote = str_replace("\\\"" , "\"", $current_settings);
    
    // replace \\ with \
    $no_double_backslash = str_replace("\\\\", "\\", $no_backslash_quote);
    
    // decode the json format
    $cs = json_decode($no_double_backslash, true);
    
    $values = array();
    foreach ( array_keys($cs) as $key ) {
        // strip off the setting name prefix of ap_options etc
        $new_key = str_replace(']', '', str_replace('ap_options[cs][', '', $key));
        $values[$new_key] = $cs[$key];
    }
    
    $new_settings['command'] = $command;
    $new_settings['message'] = $message;
    $new_settings['cs'] = $values;
    $new_settings['current-save-id'] = array( $inputs['configType'], $inputs['configName'] );
    
    update_option('ap_options', $new_settings);
    
    // send results back to the client in the correct format
    $updated_options = get_option('ap_options');
    $response = array(
            'configID'         => get_current_config($updated_options)
    , 'message'          => $updated_options['message']
    , 'message_type'     => $updated_options['message_type']
    , 'configSelectHTML' => get_config_select_contents($updated_options)
    );
    send_ajax_response($response);
}

add_action('wp_ajax_save_as_config', 'ajax_handle_save_as_config');
function ajax_handle_save_as_config() {
    common_save_function('save_as_configuration', 'saving as configuration');
}

add_action('wp_ajax_save_config', 'ajax_handle_save_config');
function ajax_handle_save_config() {  
    common_save_function('save_configuration', 'saving configuration');
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