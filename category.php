<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

		<div id="container" class="row">
			<div id="content" class="eightcol" role="main">

				<h1 class="page-title"><?php
					printf( __( '%s', 'twentyten' ), single_cat_title( '', false ) );
				?></h1>
				<?php
					$category_description = category_description();
					if ( ! empty( $category_description ) )
						echo '<div class="archive-meta">' . $category_description . '</div>';

				/* Run the loop for the category page to output the posts.
				 * If you want to overload this in a child theme then include a file
				 * called loop-category.php and that will be used instead.
				 */
				get_template_part( 'loop', 'category' );
				?>

			</div><!-- #content -->
			
			<div class="fourcol last">
			 <?php get_sidebar(); ?>
            </div>
			
		</div><!-- #container -->


<?php get_footer(); ?>
