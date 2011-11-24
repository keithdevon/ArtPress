<?php

function get_configuration_values( $options, $config_type, $config_name ) {
    return $options['configurations'][$config_type][$config_name];
}
function set_configuration_values( $options, $config_type, $config_name, $config_values ) {
    $options['configurations'][$config_type][$config_name] = $config_values;
    return $options;
}

// current configuration functions

function get_current_config($options) {
    return $options['current-save-id'];
}
function get_current_config_values($options) {
    return $options['configurations'][get_current_config_type($options)][get_current_config_name($options)];
}
function set_current_config(&$options, $config_type, $config_name) {
    $options['current-save-id'] = array($config_type, $config_name);
}
function get_current_config_name($options) {
    return $options['current-save-id'][1];
}
function get_current_config_type($options) {
    return $options['current-save-id'][0];
}
function is_current_config_user_type($options) {
    return ( get_current_config_type($options) == 'user' );
}
function is_current_config_default_type($options) {
    return ( get_current_config_type($options) == 'default' );
}
function is_current_config($options, $candidate_type, $candidate_name) {
    return ( ( $candidate_type == get_current_config_type($options) ) 
          && ( $candidate_name == get_current_config_name($options) ) );
}
function set_current_config_values($options, $config_values) {
    return set_configuration_values($options, get_current_config_type($options), get_current_config_name($options), $config_values);
}

// live configuration functions

function get_live_config($options) {
    return $options['live_config_id'];
}
function get_live_config_values($options) {
    return $options['configurations'][get_live_config_type($options)][get_live_config_name($options)];
}
function set_live_config(&$options, $config_type, $config_name) {
    $options['live_config_id'] = array($config_type, $config_name);
}
function get_live_config_name($options) {
    return $options['live_config_id'][1];
}
function get_live_config_type($options) {
    return $options['live_config_id'][0];
}
function is_live_config_user_type($options) {
    return ( get_live_config_type($options) == 'user' );
}
function is_live_config_default_type($options) {
    return ( get_live_config_type($options) == 'default' );
}
function is_live_config_name($options, $candidate_name) {
    return $candidate_name == get_live_config_name($options);
}
function is_live_config($options, $candidate_type, $candidate_name) {
    return ( ( $candidate_type == get_live_config_type($options) ) 
          && ( $candidate_name == get_live_config_name($options) ) );
}
function is_current_config_live ( $options ) {
    return ( get_live_config($options) == get_current_config($options) );
}



// default configurations

function get_default_configurations($options) {
    return $options['configurations']['default'];
}
function get_default_configuration_names($options) {
    return array_keys(get_default_configurations($options));
}
function set_default_configurations_values($options, $default_configs) {
    foreach(array_keys($default_configs) as $config_name) {
        $options = set_configuration_values($options, 'default', $config_name, $default_configs[$config_name]);
    }
    return $options;
}

// user configurations

function get_user_configurations($options) {
    return $options['configurations']['user'];
}
function get_user_configuration_names($options) {
    return array_keys(get_user_configurations($options));
}
function user_configuration_name_exists($options, $user_config_name_candidate) {
    if($names = get_user_configuration_names($options) ) {
        return isset($names[$user_config_name_candidate]);
    }
}

// message

function set_message($options, $type, $message) {
    $options['message'] = $message;
    $options['message_type'] = $type;
    return $options;
}

// css

function get_config_css($options, $config_type, $config_name) {
    return $options['css'][$config_type][$config_name];
}
function get_current_config_css($options) {
    return get_config_css($options, get_current_config_type($options), get_current_config_name($options));
}
function get_live_config_css($options) {
    return get_config_css($options, get_live_config_type($options), get_live_config_name($options));
}

function get_config_custom_css($config_values) {
    if(isset($config_values['custom-css'])) return $config_values['custom-css'];
}
function get_current_config_custom_css($options) {
    return get_config_custom_css( get_current_config_values($options) );
}
function get_live_config_custom_css($options) {
    return get_config_custom_css( get_live_config_values($options) );
}

