<?php
/**
 * The template for displaying Gallery Archive pages.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

		<div id="content" class="container" role="main">
            <div class="row">
                <div class="twelvecol last">
                <?php get_template_part( 'ht-crumbs' );?>
				<h1 class="page-title"><?php
					printf( __( '%s', 'twentyten' ), single_cat_title( '', false ) );
				?></h1>
				</div>
            <div class="clear"></div>
				<?php
					$category_description = category_description();
					if ( ! empty( $category_description ) )
						echo '<div class="archive-meta row">' . $category_description . '</div>';

				/* Run the loop for the category page to output the posts.
				 * If you want to overload this in a child theme then include a file
				 * called loop-category.php and that will be used instead.
				 */
				get_template_part( 'loop', 'galleries' );
				?>


		</div><!-- #container -->


<?php get_footer(); ?>
