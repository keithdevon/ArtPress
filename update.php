<?php
function parse_version_number( $version_string ) {
    return explode('.', $version_string, 3);
}
function version_number_array_to_string( $array ) {
    return implode('.', $array);
}
function greater_version_number($part, $array1, $array2) {
    $diff = $array1[$part] - $array2[$part];
    return ($diff > 0);    
}
function greater_major_version_number( $array1, $array2 ) {
    return greater_version_number(0, $array1, $array2);
}
function greater_minor_version_number( $array1, $array2 ) {
    return greater_version_number(1, $array1, $array2);
}
function greater_patch_version_number( $array1, $array2 ) {
    return greater_version_number(2, $array1, $array2);
}
/** 
 * returns true if array1 has a higher version number than array2
 * */
function greater_version( $array1, $array2 ) {
    if(greater_major_version_number($array1, $array2) ) return $array1;
    else if (greater_minor_version_number($array1, $array2)) return $array1;
    else if (greater_patch_version_number($array1, $array2)) return $array1;
    else return false;
}

function get_latest_version_number() {
    $response = wp_remote_get('http://wordpress-for-artists.com/latest-artpress-version-number.txt');
    if( is_wp_error( $response ) ) {
        return -1;
    } else {
        $theBody = wp_remote_retrieve_body( $response );
        return parse_version_number($theBody);
    }
}

function newer_version_available() {
    $latest = get_latest_version_number();
    $current = get_theme_version_number();
    return greater_version($latest, $current);
}

function update_theme() {
    if ( $new_version = newer_version_available() ) {
        $version_string = version_number_array_to_string( $new_version );
        
        $setting_fields = get_settings_fields('artpress_options');
        $hidden = input('hidden', attr_name('ap_options[command]') . attr_value('upgrade_theme'));
        $submit = input('submit', attr_value('upgrade ' . get_theme_name() . ' to version ' . $version_string ));
        $upgrade_form = form('post', 'options.php', $setting_fields . $hidden . $submit, null);
        
        echo div("Version {$version_string} available." . $upgrade_form );
    } else {
        echo "You have the latest version";
    }
    
}