<?php
/**
 * Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
 *
 * To override twentyten_widgets_init() in a child theme, remove the action hook and add your own
 * function tied to the init hook.
 *
 * @since Twenty Ten 1.0
 * @uses register_sidebar
 */
function twentyten_widgets_init() {

    // Area 7, located in the header. Empty by default.
	/* TODO:
    register_sidebar( array(
		'name' => __( 'Header Widget Area', 'twentyten' ),
		'id' => 'header-widget-area',
		'description' => __( 'The header widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	*/

	// Sidebar A
	register_sidebar( array(
		'name' => __( 'Post Sidebar', 'twentyten' ),
		'id' => 'primary-widget-area',
		'description' => __( 'The primary sidebar widget area and the default post sidebar', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Sidebar B
	register_sidebar( array(
		'name' => __( 'Page Sidebar', 'twentyten' ),
		'id' => 'secondary-widget-area',
		'description' => __( 'The secondary sidebar widget area and the default page sidebar', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 7, located on the home page. Empty by default.
	register_sidebar( array(
		'name' => __( 'Home Widget Area 1/2', 'twentyten' ),
		'id' => '1-2-home-widget-area',
		'description' => __( '1 of 2 Home Widget Area', 'twentyten' ),
		'before_widget' => '<div class="sixcol">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 8, located on the home page. Empty by default.
	register_sidebar( array(
		'name' => __( 'Home Widget Area 2/2', 'twentyten' ),
		'id' => '2-2-home-widget-area',
		'description' => __( '2 of 2 Home Widget Area', 'twentyten' ),
		'before_widget' => '<div class="sixcol last">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 9, located on the home page. Empty by default.
	register_sidebar( array(
		'name' => __( 'Home Widget Area 1/3', 'twentyten' ),
		'id' => '1-3-home-widget-area',
		'description' => __( '1 of 3 Home Widget Area', 'twentyten' ),
		'before_widget' => '<div class="fourcol">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 10, located on the home page. Empty by default.
	register_sidebar( array(
		'name' => __( 'Home Widget Area 2/3', 'twentyten' ),
		'id' => '2-3-home-widget-area',
		'description' => __( '2 of 3 Home Widget Area', 'twentyten' ),
		'before_widget' => '<div class="fourcol">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 11, located on the home page. Empty by default.
	register_sidebar( array(
		'name' => __( 'Home Widget Area 3/3', 'twentyten' ),
		'id' => '3-3-home-widget-area',
		'description' => __( '3 of 3 Home Widget Area', 'twentyten' ),
		'before_widget' => '<div class="fourcol last">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 3, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'First Footer Widget Area', 'twentyten' ),
		'id' => 'first-footer-widget-area',
		'description' => __( 'The first footer widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 4, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Second Footer Widget Area', 'twentyten' ),
		'id' => 'second-footer-widget-area',
		'description' => __( 'The second footer widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 5, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Third Footer Widget Area', 'twentyten' ),
		'id' => 'third-footer-widget-area',
		'description' => __( 'The third footer widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 6, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Fourth Footer Widget Area', 'twentyten' ),
		'id' => 'fourth-footer-widget-area',
		'description' => __( 'The fourth footer widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

}
/** Register sidebars by running twentyten_widgets_init() on the widgets_init hook. */
add_action( 'widgets_init', 'twentyten_widgets_init' );