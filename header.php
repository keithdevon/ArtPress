<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

	?></title>
	

	
<link rel="profile" href="http://gmpg.org/xfn/11" />

<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php get_template_part( 'cssgrid' );           // CSS Grid files (cssgrid.php) ?>

<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
</head>

<body <?php body_class(); ?>>

<?php $kd = 2;  ?>
		  
		  
            <?php if($kd == 1) { ?>	
            
	<div id="header" class="container hfeed">
		<div id="masthead" class="row">	
            <div class="fourcol">
                <?php get_template_part( 'branding' ); // Logo file (branding.php) ?>
            </div><!-- fourcol -->
            
				<div class="eightcol last" style="position:relative;">
				<?php
	               // A second sidebar for widgets, just because.
	               if ( is_active_sidebar( 'header-widget-area' ) ) : ?>

		              <div id="header-widgets" class="widget-area" role="complementary">
			             <ul class="xoxo">
				            <?php dynamic_sidebar( 'header-widget-area' ); ?>
			             </ul>
		              </div><!-- #header-widgets .widget-area -->

                    <?php endif; ?>
    
                <?php get_template_part( 'main-nav' );   // Main Nav file (main-nav.php) ?>
       
                </div><!-- eightcol  --> 
            <?php } 
            
            elseif($kd == 2) { ?>	
    <div id="header" class="container hfeed">
		<div id="masthead" class="row">	
            <div class="twelvecol">
                <?php get_template_part( 'branding' ); // Logo file (branding.php) ?>
            </div> 
            
            <div class="twelvecol">
                <?php get_template_part( 'main-nav' );   // Main Nav file (main-nav.php) ?>
            </div> 
            <?php } 
            
             elseif($kd == 3) { ?>
            <div id="top-menu" class="container" style="padding-bottom:0.5em;">
	        	<div  class="row" style="padding-top:0px;">	
                    	<div class="twelvecol" style="">
                            <?php get_template_part( 'main-nav' );   // Main Nav file (main-nav.php) ?>
                        </div> 
                        <div class="clear"></div>
                        
                </div>
            </div>
            
            <div id="header" class="container hfeed">
                <div id="masthead" class="row" style="">
                    <div class="twelvecol" style="padding-top:0em;">
                        <?php get_template_part( 'branding' ); // Logo file (branding.php) ?>
                    </div> 
                    <div class="clear"></div>
                </div>
            </div>
            
            <?php } ?> 
            
            
            
            
            
            
			<div class="clear"></div>
		</div><!-- #masthead -->
	</div><!-- #header -->