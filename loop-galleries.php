<?php
/**
 * The loop that displays posts.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop.php or
 * loop-template.php, where 'template' is the loop context
 * requested by a template. For example, loop-index.php would
 * be used if it exists and we ask for the loop with:
 * <code>get_template_part( 'loop', 'index' );</code>
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if ( $wp_query->max_num_pages > 1 ) : ?>

</div><!-- row -->

<?php endif; ?>

<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if ( ! have_posts() ) : ?>
	<div id="post-0" class="post error404 not-found">
		<h1 class="entry-title"><?php _e( 'Not Found', 'twentyten' ); ?></h1>
		<div class="entry-content">
			<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyten' ); ?></p>
			<?php get_search_form(); ?>
		</div><!-- .entry-content -->
	</div><!-- #post-0 -->
<?php endif; ?>


<?php
// THE GRID LOOP
?>

<?php
$g_cat_layout = 'grid';
if( $g_cat_layout == 'grid' ) {?>

<?php $post_count = 0;
        $numItems = ($wp_query->post_count);
        $postnumber = 0;
?>
<?php while ( have_posts() ) : the_post(); ?>
<?php   $post_count ++;
        $postnumber ++;
        if($post_count == 4) $post_count = 1; ?>


<?php /* How to display posts of the Gallery format. The gallery category is the old way. */ ?>

	<?php if ( ( function_exists( 'get_post_format' ) && 'gallery' == get_post_format( $post->ID ) ) || in_category( _x( 'gallery', 'gallery category slug', 'twentyten' ) ) ) : ?>
	<?php if($post_count == 1) echo '<div class="row">';?>
	<div class="fourcol <?php if($post_count == 3) echo ' last';?>">
		<div id="post-<?php the_ID(); ?>" <?php post_class('grid-single'); ?>>



			<div class="entry-content">
<?php if ( post_password_required() ) : ?>
				<?php the_content(); ?>
<?php else : ?>
                				<?php
					$images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
					if ( $images ) :
						$total_images = count( $images );
						$image = array_shift( $images );
						$image_img_tag = wp_get_attachment_image( $image->ID, 'Gallery list' );
						$image_img_tag = preg_replace( '/(width|height)=\"\d*\"\s/', "", $image_img_tag );
				?>
						<div class="gallery-icon">
							<a class="size-thumbnail" href="<?php the_permalink(); ?>"><?php echo $image_img_tag; ?></a>
						</div><!-- .gallery-thumb -->

<h2 class="gallery-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

<div class="entry-meta">
    <?php twentyten_posted_on(); ?>
   <?php printf( _n( '<a %1$s>%2$s photo</a>.', '| <a %1$s>%2$s photos</a>.', $total_images, 'twentyten' ),
								'href="' . get_permalink() . '" title="' . sprintf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ) . '" rel="bookmark"',
								number_format_i18n( $total_images )
							); ?>
</div>
				<?php endif; ?>
						<?php the_excerpt();?>

<?php endif; ?>

			</div><!-- .entry-content -->

		</div><!-- #post-## -->
    </div><!-- .twocol -->
    <?php if($post_count == 3 || $postnumber == $numItems) echo ' <div class="clear"></div></div>';?>



		<?php comments_template( '', true ); ?>

	<?php endif; // This was the if statement that broke the loop into three parts based on categories. ?>

<?php endwhile; // End the loop. Whew. ?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if (  $wp_query->max_num_pages > 1 ) : ?>

				<div id="nav-below" class="navigation row">
				    <div class="twelvecol last">
					   <div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older galleries', 'twentyten' ) ); ?></div>
					   <div class="nav-next"><?php previous_posts_link( __( 'Newer galleries <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); ?></div>
				    </div>
				</div><!-- #nav-below -->

            <div class="clear"></div>
</div>
<?php endif; ?>
<?php }


// LIST LAYOUT

elseif($g_cat_layout == 'list') {
?>

<div class="row">

<?php while ( have_posts() ) : the_post(); ?>

	<?php if ( ( function_exists( 'get_post_format' ) && 'gallery' == get_post_format( $post->ID ) ) || in_category( _x( 'gallery', 'gallery category slug', 'twentyten' ) ) ) : ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class('list'); ?>>
            <h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

			<div class="entry-content">
<?php if ( post_password_required() ) : ?>
				<?php the_content(); ?>
<?php else : ?>
                				<?php
					$images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
					if ( $images ) :
						$total_images = count( $images );
						$image = array_shift( $images );
						$image_img_tag = wp_get_attachment_image( $image->ID, 'Gallery list' );
						$image_img_tag = preg_replace( '/(width|height)=\"\d*\"\s/', "", $image_img_tag );
				?>
						<div class="gallery-thumb threecol">
							<a class="size-thumbnail" href="<?php the_permalink(); ?>"><?php echo $image_img_tag; ?></a>
						</div><!-- .gallery-thumb -->

						<div class="entry-meta">
				            <?php twentyten_posted_on(); ?>
						  <p><?php printf( _n( 'This gallery contains <a %1$s>%2$s photo</a>.', 'This gallery contains <a %1$s>%2$s photos</a>.', $total_images, 'twentyten' ),
								'href="' . get_permalink() . '" title="' . sprintf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ) . '" rel="bookmark"',
								number_format_i18n( $total_images )
							); ?></p>
                        </div>
				<?php endif; ?>
						<?php the_excerpt(); ?>
<?php endif; ?>

		</div><!-- .entry-content -->
    </div><!-- #post-## -->



		<?php comments_template( '', true ); ?>

	<?php endif; ?>

<?php endwhile; // End the loop. Whew. ?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if (  $wp_query->max_num_pages > 1 ) : ?>

				<div id="nav-below" class="navigation row">
				    <div class="twelvecol last">
					   <div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older galleries', 'twentyten' ) ); ?></div>
					   <div class="nav-next"><?php previous_posts_link( __( 'Newer galleries <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); ?></div>
				    </div>
				</div><!-- #nav-below -->

            <div class="clear"></div>
</div>
<?php endif; ?>




<?php } ?>


</div><!-- row -->
