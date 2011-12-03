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

function get_latest_version_number($bypass_transient_cache=false) {
    if(!$bypass_transient_cache && $latest_version = get_transient('latest-ArtPress-version-number') ) {
        return parse_version_number($latest_version);
    } else {      
        $response = wp_remote_get('http://wordpress-for-artists.com/latest-ArtPress-version-number.txt');
        if( is_wp_error( $response ) ) {
            return -1;
        } else {
            $the_body = wp_remote_retrieve_body( $response );
            set_transient('latest-ArtPress-version-number', $the_body, 60*60*12);
            return parse_version_number($the_body);
        }
    }
}

function newer_version_available( $bypass_transient_cache=false ) {
    $latest = get_latest_version_number( $bypass_transient_cache );
    $current = explode('.', get_theme_version_number() );
    return greater_version($latest, $current);
}

function update_theme() {
    if ( $new_version = newer_version_available() ) {
        $version_string = version_number_array_to_string( $new_version );
        
        echo div("Version {$version_string} available. " . alink("themes.php?page=upgrade_artpress", "Click here to upgrade.") );
    } else {

    }
    
}