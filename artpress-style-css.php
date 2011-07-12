<?php header('Content-type: text/css'); ?>

<?php

// TODO investigate alternatives to wp-load.php

require_once('../../../wp-load.php');
require_once('css-gen.php');

//$options = get_option('artpress_theme_options');
$background_images = get_option('ap_background_image_settings');

$maintabgroup = new Main_Tab_Group('main tab group', 'ap_options');
$options = get_option('ap_options');
if ($options != null) {
    $maintabgroup->inject_values($options['saves'][$options['current-save-id']]);
}

$selectors = $maintabgroup->get_css_selectors();

$output = "";
foreach ( $selectors as $selector ) {
    $selector_string = get_full_selector_string( get_full_selector_array($selector) );
    $settings = get_setting_instances($selector);
    $declarations = '';
    foreach( $settings as $setting ) {
        $declarations .= $setting->get_css_declaration();
    }
    if($declarations) {
        $output .= rule( $selector_string, decblock($declarations) );
    }
}
echo $output;                      
?>


