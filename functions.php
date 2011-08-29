<?php

if ( ! isset( $content_width ) )
	$content_width = 1140;

/** Tell WordPress to run twentyten_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'twentyten_setup' );

if ( ! function_exists( 'twentyten_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override twentyten_setup() in a child theme, add your own twentyten_setup to your child theme's
 * functions.php file.
 *
 * @uses add_theme_support() To add support for post thumbnails and automatic feed links.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_custom_background() To add support for a custom background.
 * @uses add_editor_style() To style the visual editor.
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_custom_image_header() To add support for a custom header.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_setup() {

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Post Format support. You can also use the legacy "gallery" or "asides" (note the plural) categories.
	add_theme_support( 'post-formats', array( 'aside', 'gallery' ) );

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'twentyten', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'twentyten' ),
		'secondary' => 'Secondary Menu',
	) );

	// This theme allows users to set a custom background
	add_custom_background();

	// Your changeable header business starts here
	if ( ! defined( 'HEADER_TEXTCOLOR' ) )
		define( 'HEADER_TEXTCOLOR', '' );

	// No CSS, just IMG call. The %s is a placeholder for the theme template directory URI.
	if ( ! defined( 'HEADER_IMAGE' ) )
		define( 'HEADER_IMAGE', '%s/images/headers/path.jpg' );

	// The height and width of your custom header. You can hook into the theme's own filters to change these values.
	// Add a filter to twentyten_header_image_width and twentyten_header_image_height to change these values.
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'twentyten_header_image_width', 1140 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'twentyten_header_image_height', 400 ) );

	// We'll be using post thumbnails for custom header images on posts and pages.
	// We want them to be 940 pixels wide by 198 pixels tall.
	// Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
	set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );

	// Don't support text inside the header image.
	if ( ! defined( 'NO_HEADER_TEXT' ) )
		define( 'NO_HEADER_TEXT', true );

	// Add a way for the custom header to be styled in the admin panel that controls
	// custom headers. See twentyten_admin_header_style(), below.
	add_custom_image_header( '', 'twentyten_admin_header_style' );

	// ... and thus ends the changeable header business.

	// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
	register_default_headers( array(
		'berries' => array(
			'url' => '%s/images/headers/berries.jpg',
			'thumbnail_url' => '%s/images/headers/berries-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Berries', 'twentyten' )
		),
		'cherryblossom' => array(
			'url' => '%s/images/headers/cherryblossoms.jpg',
			'thumbnail_url' => '%s/images/headers/cherryblossoms-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Cherry Blossoms', 'twentyten' )
		),
		'concave' => array(
			'url' => '%s/images/headers/concave.jpg',
			'thumbnail_url' => '%s/images/headers/concave-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Concave', 'twentyten' )
		),
		'fern' => array(
			'url' => '%s/images/headers/fern.jpg',
			'thumbnail_url' => '%s/images/headers/fern-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Fern', 'twentyten' )
		),
		'forestfloor' => array(
			'url' => '%s/images/headers/forestfloor.jpg',
			'thumbnail_url' => '%s/images/headers/forestfloor-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Forest Floor', 'twentyten' )
		),
		'inkwell' => array(
			'url' => '%s/images/headers/inkwell.jpg',
			'thumbnail_url' => '%s/images/headers/inkwell-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Inkwell', 'twentyten' )
		),
		'path' => array(
			'url' => '%s/images/headers/path.jpg',
			'thumbnail_url' => '%s/images/headers/path-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Path', 'twentyten' )
		),
		'sunset' => array(
			'url' => '%s/images/headers/sunset.jpg',
			'thumbnail_url' => '%s/images/headers/sunset-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Sunset', 'twentyten' )
		)
	) );
}
endif;

if ( ! function_exists( 'twentyten_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in twentyten_setup().
 *
 * @since Twenty Ten 1.0
 */
function twentyten_admin_header_style() {
?>
<style type="text/css">
/* Shows the same border as on front end */
#headimg {
	border-bottom: 1px solid #000;
	border-top: 4px solid #000;
}
/* If NO_HEADER_TEXT is false, you would style the text with these selectors:
	#headimg #name { }
	#headimg #desc { }
*/
</style>
<?php
}
endif;

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * To override this in a child theme, remove the filter and optionally add
 * your own function tied to the wp_page_menu_args filter hook.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'twentyten_page_menu_args' );

/**
 * Sets the post excerpt length to 40 characters.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 *
 * @since Twenty Ten 1.0
 * @return int
 */
function twentyten_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'twentyten_excerpt_length' );

/**
 * Returns a "Continue Reading" link for excerpts
 *
 * @since Twenty Ten 1.0
 * @return string "Continue Reading" link
 */
function twentyten_continue_reading_link() {
	return ' <a href="'. get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentyten' ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and twentyten_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @since Twenty Ten 1.0
 * @return string An ellipsis
 */
function twentyten_auto_excerpt_more( $more ) {
	return ' &hellip;' . twentyten_continue_reading_link();
}
add_filter( 'excerpt_more', 'twentyten_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @since Twenty Ten 1.0
 * @return string Excerpt with a pretty "Continue Reading" link
 */
function twentyten_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= twentyten_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'twentyten_custom_excerpt_more' );

/**
 * Remove inline styles printed when the gallery shortcode is used.
 *
 * Galleries are styled by the theme in Twenty Ten's style.css. This is just
 * a simple filter call that tells WordPress to not use the default styles.
 *
 * @since Twenty Ten 1.2
 */
add_filter( 'use_default_gallery_style', '__return_false' );

/**
 * Deprecated way to remove inline styles printed when the gallery shortcode is used.
 *
 * This function is no longer needed or used. Use the use_default_gallery_style
 * filter instead, as seen above.
 *
 * @since Twenty Ten 1.0
 * @deprecated Deprecated in Twenty Ten 1.2 for WordPress 3.1
 *
 * @return string The gallery style filter, with the styles themselves removed.
 */
function twentyten_remove_gallery_css( $css ) {
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}
// Backwards compatibility with WordPress 3.0.
if ( version_compare( $GLOBALS['wp_version'], '3.1', '<' ) )
	add_filter( 'gallery_style', 'twentyten_remove_gallery_css' );

if ( ! function_exists( 'twentyten_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentyten_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, 40 ); ?>
			<?php printf( __( '%s <span class="says">says:</span>', 'twentyten' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
		</div><!-- .comment-author .vcard -->
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'twentyten' ); ?></em>
			<br />
		<?php endif; ?>

		<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
			<?php
				/* translators: 1: date, 2: time */
				printf( __( '%1$s at %2$s', 'twentyten' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'twentyten' ), ' ' );
			?>
		</div><!-- .comment-meta .commentmetadata -->

		<div class="comment-body"><?php comment_text(); ?></div>

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div><!-- .reply -->
	</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'twentyten' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'twentyten' ), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif;

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
	register_sidebar( array(
		'name' => __( 'Header Widget Area', 'twentyten' ),
		'id' => 'header-widget-area',
		'description' => __( 'The header widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

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

/**
 * Removes the default styles that are packaged with the Recent Comments widget.
 *
 * To override this in a child theme, remove the filter and optionally add your own
 * function tied to the widgets_init action hook.
 *
 * This function uses a filter (show_recent_comments_widget_style) new in WordPress 3.1
 * to remove the default style. Using Twenty Ten 1.2 in WordPress 3.0 will show the styles,
 * but they won't have any effect on the widget in default Twenty Ten styling.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_remove_recent_comments_style() {
	add_filter( 'show_recent_comments_widget_style', '__return_false' );
}
add_action( 'widgets_init', 'twentyten_remove_recent_comments_style' );

if ( ! function_exists( 'twentyten_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_posted_on() {
	printf( __( '<span class="%1$s"></span> %2$s <span class="meta-sep">by</span> %3$s', 'twentyten' ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			sprintf( esc_attr__( 'View all posts by %s', 'twentyten' ), get_the_author() ),
			get_the_author()
		)
	);
}
endif;

if ( ! function_exists( 'twentyten_posted_in' ) ) :
/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 *
 * @since Twenty Ten 1.0
 */
function twentyten_posted_in() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'twentyten' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'twentyten' );
	} else {
		$posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'twentyten' );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}
endif;



//ArtPress Functions

// ------ Add settings pages

require_once ( get_template_directory() . '/theme-options.php' );
//require_once ( get_template_directory() . '/background-images.php' );


/* For adding custom field to gallery popup */

// ----- Height
function artpress_attachment_height($image_height_form_fields, $post) {
	// $form_fields is a an array of fields to include in the attachment form
	// $post is nothing but attachment record in the database
	//     $post->post_type == 'attachment'
	// attachments are considered as posts in WordPress. So value of post_type in wp_posts table will be attachment
	// now add our custom field to the $form_fields array
	// input type="text" name/id="attachments[$attachment->ID][custom1]"
	$image_height_form_fields["artpress_image_height"] = array(
		"label" => __("Real Image Height (cm)"),
		"input" => "text", // this is default if "input" is omitted
		"value" => get_post_meta($post->ID, "_artpress_image_height", true),
                "helps" => __("Enter the height of your original piece in cms."),
	);
   return $image_height_form_fields;
}
// now attach our function to the hook
add_filter("attachment_fields_to_edit", "artpress_attachment_height", null, 2);

function artpress_attachment_height_to_save($post, $attachment) {
	// $attachment part of the form $_POST ($_POST[attachments][postID])
        // $post['post_type'] == 'attachment'
	if( isset($attachment['artpress_image_height']) ){
		// update_post_meta(postID, meta_key, meta_value);
		update_post_meta($post['ID'], '_artpress_image_height', $attachment['artpress_image_height']);
	}
	return $post;
}
// now attach our function to the hook.
add_filter("attachment_fields_to_save", "artpress_attachment_height_to_save", null , 2);



// ----- width
function artpress_attachment_width($image_width_form_fields, $post) {
	$image_width_form_fields["artpress_image_width"] = array(
		"label" => __("Real Image Width (cm)"),
		"input" => "text", // this is default if "input" is omitted
		"value" => get_post_meta($post->ID, "_artpress_image_width", true),
                "helps" => __("Enter the width of your original piece in cms."),
	);
   return $image_width_form_fields;
}
// now attach our function to the hook
add_filter("attachment_fields_to_edit", "artpress_attachment_width", null, 2);

function artpress_attachment_width_to_save($post, $attachment) {
	// $attachment part of the form $_POST ($_POST[attachments][postID])
        // $post['post_type'] == 'attachment'
	if( isset($attachment['artpress_image_width']) ){
		// update_post_meta(postID, meta_key, meta_value);
		update_post_meta($post['ID'], '_artpress_image_width', $attachment['artpress_image_width']);
	}
	return $post;
}
// now attach our function to the hook.
add_filter("attachment_fields_to_save", "artpress_attachment_width_to_save", null , 2);



// Image handling

function attachment_toolbox($size = thumbnail) {

	if($images = get_children(array(
		'post_parent'    => get_the_ID(),
		'post_type'      => 'attachment',
		'numberposts'    => -1, // show all
		'post_status'    => null,
		'post_mime_type' => 'image',
	))) {
		foreach($images as $image) {
			$attimg   = wp_get_attachment_image($image->ID,$size);
			$atturl   = wp_get_attachment_url($image->ID);
			$attlink  = get_attachment_link($image->ID);
			$postlink = get_permalink($image->post_parent);
			$atttitle = apply_filters('the_title',$image->post_title);

			echo '<p><strong>wp_get_attachment_image()</strong><br />'.$attimg.'</p>';
			echo '<p><strong>wp_get_attachment_url()</strong><br />'.$atturl.'</p>';
			echo '<p><strong>get_attachment_link()</strong><br />'.$attlink.'</p>';
			echo '<p><strong>get_permalink()</strong><br />'.$postlink.'</p>';
			echo '<p><strong>Title of attachment</strong><br />'.$atttitle.'</p>';
			echo '<p><strong>Image link to attachment page</strong><br /><a href="'.$attlink.'">'.$attimg.'</a></p>';
			echo '<p><strong>Image link to attachment post</strong><br /><a href="'.$postlink.'">'.$attimg.'</a></p>';
			echo '<p><strong>Image link to attachment file</strong><br /><a href="'.$atturl.'">'.$attimg.'</a></p>';
		}
	}
}


// Shortcodes

//------Columns

add_shortcode( '1of3', 'ht_col1of3_shortcode' );//add 3 column shortcode (for columns 1 and 2)

function ht_col1of3_shortcode( $atts, $content = null ) {
   return '<div style="width:100%; clear:both;"></div><div class="fourcol internal-col">' . $content . '</div>';
}

add_shortcode( '2of3', 'ht_col2of3_shortcode' );//add 3 column shortcode (for column 2)

function ht_col2of3_shortcode( $atts, $content = null ) {
   return '<div class="fourcol internal-col" >' . $content . '</div>';
}

add_shortcode( '3of3', 'ht_col3of3_shortcode' );//add 3rd of 3 columns

function ht_col3of3_shortcode( $atts, $content = null ) {
   return '<div class="fourcol internal-col last">' . $content . '</div><div style="clear:both;"></div>';
}

add_shortcode( '1of2', 'ht_col1of2_shortcode' );// add 2 column shotcode

function ht_col1of2_shortcode( $atts, $content = null ) {
   return '<div class="sixcol internal-col">' . $content . '</div>';
}

add_shortcode( '2of2', 'ht_col2of2_shortcode' );// 2nd of 2 columns

function ht_col2of2_shortcode( $atts, $content = null ) {
   return '<div class="sixcol internal-col last" >' . $content . '</div><div style="clear:both;"></div>';
}

add_shortcode( '1of4', 'ht_col1of4_shortcode' );// add 4 column shotcode

function ht_col1of4_shortcode( $atts, $content = null ) {
   return '<div class="threecol internal-col">' . $content . '</div>';
}

add_shortcode( '4of4', 'ht_col4of4_shortcode' );// 4th of 4 columns

function ht_col4of4_shortcode( $atts, $content = null ) {
   return '<div class="threecol internal-col last" >' . $content . '</div><div style="clear:both;"></div>';
}

//------Box outs

add_shortcode( 'boxout', 'ht_boxout_shortcode' );

function ht_boxout_shortcode( $atts, $content = null ) {

$options = get_option('artpress_theme_options');//extract this from the functions file before launch
    extract( shortcode_atts( array(
      'float' => 'boxout',
      ), $atts ) );
      $ht_opening = '<div class="box-out" style="width:30%; background-color: #eee; margin-bottom:1.5em; padding:1.5em; font-size:1.2em; line-height:1.5em; font-style:italic;';
      if(esc_attr($float) == 'right') $ht_middle = 'margin-left:1em;  float:' . esc_attr($float) . ';">';
        else $ht_middle = 'margin-right:1.5em; float:left;">';
        $ht_end =  $content . '</div>';
        return $ht_opening . $ht_middle . $ht_end;
}







/*add_filter('wp_get_attachment_image', 'ht_get_attachment_image', 1, 2);

function ht_get_attachment_image($attachment_id, $size = 'thumbnail', $icon = false, $attr = '') {

	$html = '';
	$image = wp_get_attachment_image_src($attachment_id, $size, $icon);
	if ( $image ) {
		list($src) = $image;
		//$hwstring = image_hwstring($width, $height);
		if ( is_array($size) )
			$size = join('x', $size);
		$attachment =& get_post($attachment_id);
		$default_attr = array(
			'src'	=> $src,
			'class'	=> "attachment-$size",
			'alt'	=> trim(strip_tags( get_post_meta($attachment_id, '_wp_attachment_image_alt', true) )), // Use Alt field first
			'title'	=> trim(strip_tags( $attachment->post_title )),
		);
		if ( empty($default_attr['alt']) )
			$default_attr['alt'] = trim(strip_tags( $attachment->post_excerpt )); // If not, Use the Caption
		if ( empty($default_attr['alt']) )
			$default_attr['alt'] = trim(strip_tags( $attachment->post_title )); // Finally, use the title

		$attr = wp_parse_args($attr, $default_attr);
		$attr = apply_filters( 'wp_get_attachment_image_attributes', $attr, $attachment );
		$attr = array_map( 'esc_attr', $attr );
		$html = rtrim("<img $hwstring");
		foreach ( $attr as $name => $value ) {
			$html .= " $name=" . '"' . $value . '"';
		}
		$html .= ' />';
	}

	return $html;
}*/

// ADD new image sizes

add_image_size( 'Gallery list', 350, 200, true );
add_image_size( 'full-width', 1140, '', false );
add_image_size( 'six-col', 548, '', false );
add_image_size( 'four-col', 300, '', false );
add_image_size( 'three-col', 252, '', false );
add_image_size( 'two-col', 154, '', false );


remove_shortcode( 'gallery' );
add_shortcode('gallery', 'ht_gallery_shortcode');

/**
 * The Gallery shortcode.
 *
 * This overwrites the core WP gallery shortcode and spits out all the images in rows and columns. Yum!
 *
 * @since 2.5.0
 *
 * @param array $attr Attributes attributed to the shortcode.
 * @return string HTML content to display gallery.
 */
function ht_gallery_shortcode($attr) {
	global $post, $wp_locale;

	static $instance = 0;
	$instance++;

	// Allow plugins/themes to override the default gallery template.
	$output = apply_filters('post_gallery', '', $attr);
	if ( $output != '' )
		return $output;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemcol'    => 'div',
		'icontag'    => 'div',
		'captiontag' => 'div',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => ''
	), $attr));

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$include = preg_replace( '/[^0-9,]+/', '', $include );
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}

	$captiontag = tag_escape($captiontag);
	$columns = intval($columns);
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$gallery_style = $gallery_div = '';
	if ( apply_filters( 'use_default_gallery_style', true ) )
		$gallery_style = "
		<style type='text/css'>
			#{$selector} {
				margin: auto;
			}
			#{$selector} .gallery-item {
				float: {$float};
				margin-top: 10px;
				text-align: center;
				width: {$itemwidth}%;
			}
			#{$selector} img {
				border: 2px solid #cfcfcf;
			}
			#{$selector} .gallery-caption {
				margin-left: 0;
			}
		</style>
		<!-- see gallery_shortcode() in wp-includes/media.php -->";
	$size_class = sanitize_html_class( $size );
	$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
	$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );
	$output .= "<div class='row gallery-row'>";

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
	   switch ($columns) {
        case 1:
            $ht_thumnail_size = 'full-width';
            break;
        case 2:
            $ht_thumnail_size = 'six-col';
            break;
        case 3:
            $ht_thumnail_size = 'four-col';
            break;
        case 4:
            $ht_thumnail_size = 'four-col';
            break;
        case 6:
            $ht_thumnail_size = 'four-col';
            break;
        }
		$link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $ht_thumnail_size, false) : wp_get_attachment_link($id, $size, true, false);
	   $break = '';
        $i++;
        $output .= "<{$itemcol} class='gallery-item ";
        switch ($columns) {
        case 1:
            $col_class = "twelvecol ";
            $break = "</div><!-- .row --><div class='row gallery-row'>";
            break;
        case 2:
            $col_class = "sixcol ";
            if(( $i % 2 )==0) {
                $col_class .= "last";
                $break = "</div><!-- .row --><div class='row gallery-row'>";
                }
            break;
        case 3:
            $col_class = "fourcol ";
            if(( $i % 3 )==0) {
                $col_class .= "last";
                $break = "</div><!-- .row --><div class='row gallery-row'>";
                }
            break;
        case 4:
            $col_class = "threecol ";
            if(( $i % 4 )==0) {
                $col_class .= "last";
                $break = "</div><!-- .row --><div class='row gallery-row'>";
                }
            break;
        case 6:
            $col_class = "twocol ";
            if(( $i % 6 )==0) {
                $col_class .= "last";
                $break = "</div><!-- .row --><div class='row gallery-row'>";
                }
            break;
        }
        $output .= $col_class . " '>";
		$output .= "
			<{$icontag} class='gallery-icon'>
				$link
			</{$icontag}>";
		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= "
				<{$captiontag} class='wp-caption-text gallery-caption'>
				" . wptexturize($attachment->post_excerpt) . "
				</{$captiontag}>";
		}
		$output .= "</{$itemcol}>";
		$output .= $break;
		/*if ( $columns > 0 && ++$i % $columns == 0 )
			$output .= '<br style="clear: both" />';removed to allow the columns to do their thing! kdev */

	}

	$output .= "
			<br style='clear: both;' />
		</div></div>\n";

	// for testing TODO remove before launch
    // $output .= 'Number of columns: '.$columns.'<br />';
    // $output .= 'Image size : '.$ht_thumnail_size;

	return $output;
}

// Remove height and width from images

add_filter( 'post_thumbnail_html', 'remove_thumbnail_dimensions', 10 );
add_filter( 'image_send_to_editor', 'remove_thumbnail_dimensions', 10 );
add_filter( 'the_content', 'remove_thumbnail_dimensions', 10 );
add_filter( 'wp_get_attachment_link', 'remove_thumbnail_dimensions', 10 );
add_filter( 'wp_get_attachment_image', 'remove_thumbnail_dimensions', 10 );



function remove_thumbnail_dimensions( $html ) {
    $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
    return $html;
}


// Add Fancybox

function fancybox() {
?>
<script type="text/javascript">
	jQuery(document).ready(function($){
		var select = $('a[href$=".bmp"],a[href$=".gif"],a[href$=".jpg"],a[href$=".jpeg"],a[href$=".png"],a[href$=".BMP"],a[href$=".GIF"],a[href$=".JPG"],a[href$=".JPEG"],a[href$=".PNG"]');
		select.attr('rel', 'fancybox');
		select.fancybox();
	});
</script>
<?php
}

if (!is_admin()) {
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery.fancybox', get_stylesheet_directory_uri().'/fancybox/jquery.fancybox.js', array('jquery'), '1.2.6');
	wp_enqueue_script('jquery.easing', get_stylesheet_directory_uri().'/js/jquery.easing.js', array('jquery'), '1.3');
	wp_enqueue_style('jquery.fancybox', get_stylesheet_directory_uri().'/fancybox/jquery.fancybox.css', false, '1.2.6');
	add_action('wp_head', 'fancybox');
}

// Child pages widget

/**
 * HTChildMenu Class
 */
class HTChildMenu extends WP_Widget {
    /** constructor */
    function HTChildMenu() {
        parent::WP_Widget(false, $name = 'ArtPress | Child Pages');
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);

        global $wp_query;
        $thePostID = $wp_query->post->ID;
        $theParentID = $wp_query->post->post_parent;

        $children = wp_list_pages('title_li=&child_of='.$thePostID.'&echo=0'.'&depth=2');
        if ($children) { ?>
            <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title;
                        else echo '<h3 class="widget-title">' . get_the_title() . ' Menu</h3>'; ?>

                        <ul class="sub-pages">
                        <?php echo $children; ?>
                        </ul>
                        <?php if ($wp_query->post->post_parent == TRUE) { ?>
                        <br />
                        <a style="font-size:.8em" href="<?php echo get_permalink($theParentID); ?>">Back up to <?php echo get_the_title($theParentID); ?></a>
                        <?php } ?>
        <?php }
        elseif ($wp_query->post->post_parent == TRUE) { ?>
        <?php $children = wp_list_pages('title_li=&child_of='.$theParentID.'&echo=0'.'&depth=2'); ?>
             <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title;
                        else echo '<h3 class="widget-title">' . get_the_title($theParentID) . ' Menu</h3>'; ?>

                        <ul class="sub-pages">
                        <?php echo $children; ?>
                        </ul>
                        <br />
                        <a style="font-size:.8em" href="<?php echo get_permalink($theParentID); ?>">Back up to <?php echo get_the_title($theParentID); ?></a>
          <?php  }?>

        <?php echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
	$instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        $title = esc_attr($instance['title']);
        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <?php
    }

} // class HTChildMenu

// register Child pages widget
add_action('widgets_init', create_function('', 'return register_widget("HTChildMenu");'));


// Add excerpts to pages

add_action( 'init', 'my_add_excerpts_to_pages' );
function my_add_excerpts_to_pages() {
add_post_type_support( 'page', 'excerpt' );
}


// Add sidebar picker
//require_once 'sidebars/ht-sidebar-picker.php';

// Add social info
require_once 'ht-functions/ht-socials.php';

// Add address widget
//require_once 'ht-widgets/ht-contact-widget.php';


// Change the editor font

add_action( 'admin_head-post.php', 'cwc_fix_html_editor_font' );
add_action( 'admin_head-post-new.php', 'cwc_fix_html_editor_font' );

function cwc_fix_html_editor_font() { ?>

<style type="text/css">#editorcontainer #content, #wp_mce_fullscreen { font-family: Georgia, "Times New Roman", "Bitstream Charter", Times, serif; }</style>
<?php }


// TYPOGRAPHY GENERATOR

function kd_type_gen() {
    $scale = 'musical fourth'; //type scale, golden, musical fifths or musical thirds
    $base = 16; //base text size in pixels

    $ht_font_sizes = array();// create font size array

    switch($scale) { // change the multiplier based on the selected modular scale
        case 'golden':
            $multiplier = 1.618;
            break;
        case 'musical fifths':
            $multiplier = 1.5;
            break;
        case 'musical fourth':
            $multiplier = 1.33;
            break;
    }

    $count = -3;
    $size = $base / $multiplier / $multiplier / $multiplier ;
    while($count < 5):
        $count ++;
        $key = $count + 3;
        $size = $size * $multiplier;
        $font_info = array(
            'font-size'=>round($size,0),
            'line-height'=>''
            );
        $ht_font_sizes['Font '.$key] = $font_info;
    endwhile;

    // set line heights

    $base_line_height = $ht_font_sizes['Font 4']['font-size'];

    foreach($ht_font_sizes as $key=>$font_info) {
        if( ($ht_font_sizes[$key]['font-size'] <= $base_line_height) )
            $ht_font_sizes[$key]['line-height'] = $base_line_height;
        elseif( ($ht_font_sizes[$key]['font-size'] > $base_line_height) && ($ht_font_sizes[$key]['font-size'] < $base_line_height*2) )
            $ht_font_sizes[$key]['line-height'] = $base_line_height*2;
        elseif( ($ht_font_sizes[$key]['font-size'] > $base_line_height*2) && ($ht_font_sizes[$key]['font-size'] < $base_line_height*3) )
            $ht_font_sizes[$key]['line-height'] = $base_line_height*3;
        elseif( ($ht_font_sizes[$key]['font-size'] > $base_line_height*3) && ($ht_font_sizes[$key]['font-size'] < $base_line_height*4) )
            $ht_font_sizes[$key]['line-height'] = $base_line_height*4;

    }

    echo '<br />Value = '.$font_info['line-height'];

    // echo out the details
    echo '<br />';
    echo '<br />Baseline size = '.$base_line_height;
    echo '<br />';

    foreach($ht_font_sizes as $key=>$font_info) {
        echo '<br />Key:'.$key;
        echo '<br />Font Size:'.$font_info['font-size'];
        echo '<br />Line Height:'.$font_info['line-height'];
        echo '<br />';
    }

    //print_r($ht_font_sizes);

}

// Set sub-menu offset to height of menu items

function detect_menu_height() {
    echo '<script>
        jQuery(document).ready(function () {
            var kdev = jQuery("#menu-main-menu li").height();
            jQuery("#menu-main-menu li .sub-menu").css("top", kdev);
        });
        </script>';
}

add_action( 'wp_footer', 'detect_menu_height' );