<?php header('Content-type: text/css'); ?>

<?php

    // TODO investigate alternatives to wp-load.php
    
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
    	
    // BODY BACKGROUND
      echo 'body {background:'
    	.$options['backgroundcolor'].
    	';}';
    	
     // SITE TITLE
      if($options['logo-color'] == 'primary' ) {
      	echo '#site-title a {color:'
    		.$options['primarycolor'].
    		';}';
    	}
    	elseif($options['logo-color'] == 'secondary' ) {
      	echo '#site-title a {color:'
    		.$options['secondarycolor'].
    		';}';
    	}
    	elseif($options['logo-color'] == 'tertiary' ) {
      	echo '#site-title a {color:'
    		.$options['tertiarycolor'].
    		';}';
    	}
    	elseif($options['logo-color'] == 'background' ) {
      	echo '#site-title a {color:'
    		.$options['backgroundcolor'].
    		';}';
    	}
    	
	// PAGE TITLE
	 if($options['title-color'] == 'primary' ) {
      	echo '#site-title a {color:'
    		.$options['primarycolor'].
    		';}';
    	}
    	elseif($options['title-color'] == 'secondary' ) {
      	echo '#site-title a {color:'
    		.$options['secondarycolor'].
    		';}';
    	}
    	elseif($options['title-color'] == 'tertiary' ) {
      	echo '#site-title a {color:'
    		.$options['tertiarycolor'].
    		';}';
    	}
    	elseif($options['title-color'] == 'background' ) {
      	echo '#site-title a {color:'
    		.$options['backgroundcolor'].
    		';}';
    	}
	
	
	

    	
?>

