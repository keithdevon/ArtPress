<?php
/**
 * The loop that displays a single post.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop-single.php.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.2
 */
?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
				<div id="post-<?php the_ID(); ?>" <?php post_class('container'); ?>>
				    <div class="row">
				        <div class="eightcol">
				            <?php get_template_part( 'ht-crumbs' );?>
					       <h1 class="page-title"><?php the_title(); ?></h1>

					       <div class="entry-meta twelvecol last">
					    	  <?php twentyten_posted_on(); ?>
					       </div><!-- .entry-meta -->

					       <div class="entry-content">
					       	<?php the_content(); ?>
					       	<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
					       </div><!-- .entry-content -->

<?php if ( get_the_author_meta( 'description' ) ) : // If a user has filled out their description, show a bio on their entries  ?>
					       <div id="entry-author-info">
					           <h2>About the author</h2>

					       	<div id="author-avatar">
					       		<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'twentyten_author_bio_avatar_size', 60 ) ); ?>
					       	</div><!-- #author-avatar -->
					       	<div id="author-description">
					       							       		<?php the_author_meta( 'description' ); ?>
					       		<div id="author-link">
					       			<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
					       				<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'twentyten' ), get_the_author() ); ?>
					       			</a>
					       		</div><!-- #author-link	-->
					       	</div><!-- #author-description -->
					       </div><!-- #entry-author-info -->
<?php endif; ?>


				        <div id="nav-below" class="navigation">
				            <div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'twentyten' ) . '</span> %title' ); ?></div>
				            <div class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'twentyten' ) . '</span>' ); ?></div>
				        </div><!-- #nav-below -->
				    <?php comments_template( '', true ); ?>
				    <div class="clear"></div>
				</div><!-- #post-## -->
<?php endwhile; // end of the loop. ?>