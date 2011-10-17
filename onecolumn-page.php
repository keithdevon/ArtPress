<?php
/**
 * Template Name: One column, no sidebar
 *
 * A custom page template without sidebar.
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

		<div id="content" class="one-column container">
            <div class="row">
                <div class="twelvecol last" role="main">
                    <?php get_template_part( 'ht-crumbs' );

			 ?>

			<?php
			/* Run the loop to output the page.
			 * If you want to overload this in a child theme then include a file
			 * called loop-page.php and that will be used instead.
			 */
			 get_template_part( 'loop', 'page' );
			?>

                </div><!-- #content -->
                <div class="clear"></div>
            </div><!-- row -->

		<div class="row">
		  <div class="sixcol"><?php get_sidebar();?></div>
		  <div class="sixcol last"><?php get_template_part( 'second-sidebar' );?></div>
		  <div class="clear"></div>
		</div>


		</div>


<?php get_footer(); ?>
