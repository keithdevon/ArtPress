<?php
//require_once('./admin.php');
header( "Content-type: text/plain" );
header('Content-Disposition: attachment; filename="somefile.txt"');
echo "hello there";
//var_dump($_GET); // Element 'foo' is string(1) "a"
//echo "<br/>";
//var_dump($_POST); // Element 'bar' is string(1) "b"
//echo "<br/>";
//var_dump($_REQUEST); // Does not contain elements 'foo' or 'bar'
//echo "<br/>";
//
//$bi = get_bloginfo( 'template_url' );
//echo $bi;

//$options = get_option('ap_options');
//$current_config = get_current_config($options);
//echo( $current_config );
