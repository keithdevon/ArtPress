<?php header('Content-type: text/css'); ?>

<?php

// TODO investigate alternatives to wp-load.php

require_once('../../../wp-load.php');
require_once('css-gen.php');
$output = "";

$maintabgroup = new Main_Tab_Group('main tab group');
$options = get_option('ap_options');
$current_save = $options['saves'][$options['Current_Save_ID']];
if ($options != null) {
    $maintabgroup->inject_values($current_save);
}

// customized functionality for Global Settings css
// headers
$font_size = Global_Font_Size_Ratio::get_font_size(1);
$selector_string = 'h1, h2, h3, h4, h5, h6';
$declarations = dec('margin-top', 2 * $font_size);
$declarations .= dec('margin-bottom', $font_size);
$output .= rule($selector_string, decblock($declarations));

// paragraph
$selector_string = 'p';
$declarations = dec('margin-bottom', $font_size);
$output .= rule($selector_string, decblock($declarations));

// standard functionality for all other settings 
//$selectors = $maintabgroup->get_css_selectors();
$selectors = CSS_Selector::get_css_selectors(); 

foreach ( $selectors as $selector ) {
    $selector_string = get_full_selector_string( get_full_selector_array($selector) );
        $recurse_test = function($child) {
            if( $child->has_children() ) {
                return true;
            } else {
                return false;
            }
        };
        $valid_child_test = function($child) {
            if ( $child instanceof CSS_Setting ) {
                $parent = $child->get_parent();
                if ( $parent instanceof Toggle_Group ) {
                    return $parent->is_on();
                } else {
                    return true;
                }
            } else {
                return false;
            }
        };
    $settings = $selector->get_children($recurse_test, $valid_child_test);
    $declarations = '';
    foreach( $settings as $setting ) {
        $declarations .= $setting->get_css_declaration();
    }
    if($declarations) {
        $output .= rule( $selector_string, decblock($declarations) );
    }
}
echo $output;  

function get_css_instances($hierarchy_obj, $unpack_composites, $settings_array=null) {
    if( !is_array($settings_array) ) {
        return get_setting_instances($hierarchy_obj, $unpack_composites, array());
    } 
    // the following test must come before the Setting test because,
    // CSS_Composite is a 'Setting' object but has children
    // therefore if we do this test first, we descend into its children
    // and get all their names
    // instead of just returning the name of the CSS_Composite object
    else if( ( $hierarchy_obj instanceof CSS_Setting ) && ! ($hierarchy_obj instanceof IComposite) ) {
        $name = $hierarchy_obj->get_name();
        $settings_array[$name] = $hierarchy_obj;
        return $settings_array;
    } 
    // GET CHILDREN
    else if ( $hierarchy_obj instanceof Hierarchy ) {
        $children = $hierarchy_obj->get_children();
        foreach ( $children as $child ) {
            $settings_array = get_setting_instances($child, $unpack_composites, $settings_array);
        }
        return $settings_array;
    } 
}
?>


