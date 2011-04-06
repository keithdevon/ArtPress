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
    echo 'body {font-size:' .$options['base_text_size']. 'em;}';
    	
    // BODY BACKGROUND
      echo 'body {background:'
    	.$options['backgroundcolor'].
    	';}';
    	
// SITE COLORS
 
$site_elements = array(
	'logo' => "#site-title a",
	'title' => ".entry-title a:link, .entry-title:visited"
	);
 
css_color("#site-title a", $options['logo-color']);
	
	function css_color( $css_el, $color ) {
    	global $options;
    
		echo $css_el . '{color:' ;
		switch ($color) {
	        case "primary": echo $options['primarycolor'];
				break;
			case "secondary": echo $options['secondarycolor'];
				break;
			case "tertiary": echo $options['tertiarycolor'];
				break;
			case "background": echo $options['backgroundcolor'];
				break;
		}
		echo ';}';
    	}
    
    	
	// PAGE TITLE

?>	
	.entry-title a:link, .entry-title a:visited {color:
	<?php
	
	switch ($options['title-color'])
	{
		case "primary": echo $options['primarycolor'];
			break;
		case "secondary": echo $options['secondarycolor'];
			break;
		case "tertiary": echo $options['tertiarycolor'];
			break;
		case "background": echo $options['backgroundcolor'];
			break;
	}?>
	
	;}
	<?php

	
	

    	
?>

