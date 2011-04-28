<?php header('Content-type: text/css'); ?>

<?php

/* 
Keith's Layout Option Blocks 
*/

//Centered Logo with description beneath

/*echo '
    #site-title {
        float: left;
        text-align: center;
        margin: 0 0 18px 0;
        width: 100%;
        }
        
    #site-description {
        clear: both;
        float: left;
        text-align: center;
        margin: 15px 0 18px 0;
        width: 100%;
        }';*/
        
//Menu at top of page - uncomment this block to enable top navs - TODO - this still needs work.

/*echo '
    #access {
        display: block;
        position:absolute;
        top:24px;
        margin: 0 auto;
        width: 940px;
        }
    #header {
        padding-top:96px;
        }
    #wrapper {
        margin-top:0px;
        }';
*/

/* Add vertical navigation. Copy and paste this into it's own file */

/*#header {
    width:160px;
    }
    
#access {
	display: block;
	float: left;
	margin: 0 auto;
	width: 160px;
}
#access .menu-header,
div.menu {
	margin-left: 12px;
	width: 140px;
}
#access .menu-header ul,
div.menu ul {
	list-style: none;
	margin: 0;
}
#access .menu-header li,
div.menu li {
	float: none;
	position: relative;
}
#access a {
	color: #aaa;
	display: block;
	line-height: 38px;
	padding: 0 10px;
	text-decoration: none;
}
#access ul ul {
	box-shadow: 0px 3px 3px rgba(0,0,0,0.2);
	-moz-box-shadow: 0px 3px 3px rgba(0,0,0,0.2);
	-webkit-box-shadow: 0px 3px 3px rgba(0,0,0,0.2);
	display: none;
	position: absolute;
	top: 0px;
	left: 140px;
	float: left;
	width: 180px;
	z-index: 99999;
}
#access ul ul li {
	min-width: 140px;
}
#access ul ul ul {
	left: 100%;
	top: 0;
}
#access ul ul a {
	background: #333;
	line-height: 1em;
	padding: 10px;
	width: 160px;
	height: auto;
}*/

/*
end Keith's Layout Option Blocks
*/

/** css declaration */
function dec($property, $value) { // TODO include validation
    return $property . ': ' . $value . ';\n';
}
/** css declaration block */
function decblock($declarations) {
    return '{' . $declarations . '}';
}
/** css rule */
function rule($selectors, $declaration_block) { // TODO validate selector
    return $selectors . ' ' . $declaration_block;
}

// TODO investigate alternatives to wp-load.php

require_once('../../../wp-load.php');

    $options = get_option('artpress_theme_options');

    $output = '';
        // SECTION CORRECTION
    foreach(array_keys($options['section_settings']) as $section) { // body, page etc
        $section_arr = $options['section_settings'][$section];
        foreach(array_keys($section_arr) as $css_group) { // css-selector, font-family, color etc 
            $css_group_arr = $section_arr[$css_group];
            switch ($css_group) {
                case 'font-family':
                    $output .= rule(
                        $section_arr['css_selector'], 
                        decblock( dec($css_group, $options['fonts'][$css_group_arr['value']]) )
                    );       
                    break;
                case 'color':
                    $output .= rule(
                        $section_arr['css_selector'], 
                        decblock( dec($css_group, $options['colors'][$css_group_arr['value']]) )
                    );                
                    break;
                case 'background':
                    if ( ! isset( $input['section_settings'][$section][$css_group]['row_label'] ) )          $input['section_settings'][$section][$css_group]['row_label'] = 'background color';
                    if ( ! isset( $input['section_settings'][$section][$css_group]['field_blurb_prefix'] ) ) $input['section_settings'][$section][$css_group]['field_blurb_prefix'] = 'Color';
                    if ( ! isset( $input['section_settings'][$section][$css_group]['value'] ) )              $input['section_settings'][$section][$css_group]['value'] = '0';                
                    break; 
                case 'padding':
                    if ( ! isset( $input['section_settings'][$section][$css_group]['row_label'] ) )          $input['section_settings'][$section][$css_group]['row_label'] = 'padding';
                    if ( ! isset( $input['section_settings'][$section][$css_group]['field_blurb_suffix'] ) ) $input['section_settings'][$section][$css_group]['field_blurb_suffix'] = 'Internal space between the element\'s content and its border';
                    if ( ! isset( $input['section_settings'][$section][$css_group]['value'] ) )              $input['section_settings'][$section][$css_group]['value'] = '0.5em';                
                    break;
                case 'margin':
                    if ( ! isset( $input['section_settings'][$section][$css_group]['row_label'] ) )          $input['section_settings'][$section][$css_group]['row_label'] = 'margin';
                    if ( ! isset( $input['section_settings'][$section][$css_group]['field_blurb_suffix'] ) ) $input['section_settings'][$section][$css_group]['field_blurb_suffix'] = 'External space between the element\'s border and other elements';
                    if ( ! isset( $input['section_settings'][$section][$css_group]['value'] ) )              $input['section_settings'][$section][$css_group]['value'] = '1em';                
                    break;                                                             
            }
        }
    }
        
/*    // LINK COLOR
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
 
/*$site_elements = array(
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
*/ 


               
?>


