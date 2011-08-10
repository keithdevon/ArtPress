<?php
/** converts a list to an nested array key in string form
 * eg ('foo','bar','baz') -> [foo][bar][baz] 
 */
function am($array, $thing, $second_thing=null) {
    if ($second_thing) {
        return array_merge($array, array($thing), array($second_thing));
    } else {
        return array_merge($array, array($thing));
    }
}
function get_qualifier($list) {
    $qualifier = '';
    foreach($list as $part) $qualifier .= "[{$part}]";
    return $qualifier;
}
function array_merge_recursive_distinct ( array &$array1, array &$array2 )
{
  $merged = $array1;

  foreach ( $array2 as $key => &$value )
  {
    if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) )
    {
      $merged [$key] = array_merge_recursive_distinct ( $merged [$key], $value );
    }
    else
    {
      $merged [$key] = $value;
    }
  }

  return $merged;
}

function ends_with($str, $suffix) {
    $suffix_start = strlen($str) - strlen($suffix);
    return (substr($str, $suffix_start) == $suffix);
}
function starts_with($str, $prefix) {
    return (substr($str, 0, strlen($prefix)) == $prefix);
}
function get_prefix($str, $suffix) {
    return substr($str, 0, strlen($str) - strlen($suffix));
}
function is_suffix_string($str, $suffix) {
    $return = false;
    if (ends_with($str, $suffix)) {
        $prefix = get_prefix($str, $suffix);
        $return = is_numeric($prefix);
    } 
    return $return;
}
function is_em_string($str) {
    return is_suffix_string($str, 'em');
}
function is_px_string($str) {
    return is_suffix_string($str, 'px');
}
function is_percent_string($str) {
    return is_suffix_string($str, '%');
}
function is_valid_size_string($str) {
    $return = false;
    if (is_em_string($str) || is_px_string($str) || is_percent_string($str)) {
        $return = true;
    }
    return $return;
}
function is_valid_color_string($str) {
    return (strlen($str) == 7) && starts_with($str, '#') && ctype_xdigit(substr($str, 1));
}
function row($content) {
    $o = ot('tr');
    $o .= $content;
    $o .= ct('tr');
    return $o;    
}
function button_submit($button_text) {
    return input('submit', 
        attr_name('Submit') . 
        attr_class('button-primary') . 
        attr_type('submit') . 
        attr_value(esc_attr($button_text)));
}

?>