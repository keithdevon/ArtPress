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
    echo 'body {
    	font-size:' .$options['base_text_size']. 'em;
    	line-height: 150%;
    	}';
    	
    // BODY FONT SIZE
    echo '#content, #content input, #content textarea {
    	font-size:1em;
    	line-height:150%;
    	}';
    
    //BASE FONT-FAMILY
    echo 'body {font-family:' .$options['sometext']. ';}';
    
    //TITLE FONT-FAMILY
    echo 'h3#comments-title, h3#reply-title, #access .menu, #access div.menu ul, #cancel-comment-reply-link, .form-allowed-tags, #site-info, #site-title, #wp-calendar, .comment-meta, .comment-body tr th, .comment-body thead th, .entry-content label, .entry-content tr th, .entry-content thead th, .entry-meta, .entry-title, .entry-utility, #respond label, .navigation, .page-title, .pingback p, .reply, .widget-title, .wp-caption-text {
font-family:' .$options['title-font']. ';}';
    
        
    // BODY BACKGROUND
      echo 'body {background:'
        .$options['backgroundcolor'].
        ';}';
        
// SET SITE-WIDE COLORS
 
// Text colors
 
$site_elements = array(
        'logo' => "#site-title a",
        'title' => "#content .entry-title"
        );
 
        function css_color( $css_el, $color ) {
        global $options;
    
                echo $css_el . '{color:' ;
                
                $element_color = $color.'-color';
                switch ($options[$element_color]) {
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

foreach ($site_elements as $key => $value)
        css_color($value , $key);   
        

// Background colors
        
$background_elements = array(
        'page-bg' => "#wrapper",
        );
 
        function css_bg_color( $css_el, $color ) {
        global $options;
    
                echo $css_el . '{background-color:' ;
                
                $element_color = $color.'-color';
                switch ($options[$element_color]) {
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

foreach ($background_elements as $key => $value)
        css_bg_color($value , $key);  
                
?>
