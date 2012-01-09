<?php
/**
 * Template Name: Two columns (sidebar, content)
 *
 * A custom page template with left sidebar.
 *
 */

get_header(); ?>

        <div id="content" class="container" role="main">

            <div class="row">
            
                <div class="fourcol">
                    <?php get_template_part('sidebars/second-sidebar'); ?>
                </div>

                <div class="eightcol last">
                    <?php get_template_part( 'ht-crumbs' );?>

			       <?php
			       /* Run the loop to output the page.
			        * If you want to overload this in a child theme then include a file
			        * called loop-page.php and that will be used instead.
			        */
			       get_template_part( 'loop', 'page' );
			       ?>

                </div>

                <div class="clear"></div>

            </div>

        </div><!-- .container -->

<?php get_footer(); ?>
