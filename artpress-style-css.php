<?php header('Content-type: text/css'); ?>

<?php

	require_once('../../../wp-load.php');

    $options = get_option('artpress_theme_options');
    
    // LINK COLOR
    echo 'a:link, a:visited {color:'
    	.$options['radioinput'].
    	';}';
    	
    // FONT SIZE
    echo '#content {font-size:'
    	.$options['sometext'].
    	';}';
    	
?>