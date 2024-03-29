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

<?php get_template_part( 'cssgrid' );           // CSS Grid files (cssgrid.php)
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
<style type='text/css' media='screen'>
<?php
// TODO http://core.trac.wordpress.org/ticket/14673
// http://core.trac.wordpress.org/ticket/14365

$has_capability = false;

if($options = get_option('ap_options')) {
    $has_capability = current_user_can('edit_theme_options'); // TODO is this the correct access right?
    
    if ( $has_capability ) {

        echo get_current_config_css($options);
        echo get_current_config_custom_css($options);
        
    } else {
        
        echo get_live_config_css($options);
        echo get_live_config_custom_css($options);
        
    }
}
?>
</style>

</head>

<body <?php body_class(); ?>>
	<?php
	    $live_type = get_live_config_type($options);
	    $live_name = get_live_config_name($options);
	    $current_type = get_current_config_type($options);
	    $current_name = get_current_config_name($options);
	    if( $has_capability && ( $live_type != $current_type || $live_name != $current_name ) ) {
    	    $theme_name = get_theme_name();
    	    $url = get_bloginfo('url') . "/wp-admin/admin.php?page=artpress";
    	    $link = alink( $url, "settings");
    	    $content = "This style (<em>{$current_name}</em>) is not live. The live style can be changed in {$theme_name} {$link}";
    	    echo div($content, attr_class('non-live-config-banner')); 
	    }
	 ?>

    <div id="header" class="container hfeed">
		<div id="masthead" class="row">
            <div class="twelvecol">
                <?php get_template_part( 'branding' ); // Logo file (branding.php) ?>
            </div>
        </div><!-- #masthead -->

        <div class="row">
            <div id="main-nav" class="twelvecol">
                <?php get_template_part( 'main-nav' );   // Main Nav file (main-nav.php) ?>
            </div>
        </div>


        <div class="clear"></div>
	</div><!-- #header -->