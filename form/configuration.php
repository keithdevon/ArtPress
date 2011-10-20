<?php

function get_configuration_values( $options, $config_type, $config_name ) {
    return $options['configurations'][$config_type][$config_name];
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

// user configurations

function get_user_configurations($options) {
    return $options['configurations']['user'];
}
function get_user_configuration_names($options) {
    return array_keys(get_user_configurations($options));
}

