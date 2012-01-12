<?php
/**
 * Template Name: One column, no sidebar
 *
 * A custom page template without sidebar.
 *
 */

get_header(); ?>

		<div id="content" class="one-column container">
            <div class="row">
                <div class="twelvecol last" role="main">
                    <?php get_template_part( 'ht-crumbs' ); ?>

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


		</div>


<?php get_footer(); ?>
