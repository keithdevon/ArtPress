<?php
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
    if (ends_with($str, $suffix)) {
        $prefix = get_prefix($str, $suffix);
        return is_numeric($prefix);
    }
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
    return is_em_string($str) || is_px_string($str) || is_percent_string($str);
}
function is_valid_color_string($str) {
    return (strlen($str) == 7) && starts_with($str, '#') && ctype_xdigit(substr($str, 1));
}
?>