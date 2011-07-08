<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

        <div id="content" class="container" role="main">
        
            <div class="row">
            
                <div class="eightcol">
                    <?php get_template_part( 'ht-crumbs' );?>

			       <?php
			       /* Run the loop to output the page.
			        * If you want to overload this in a child theme then include a file
			        * called loop-page.php and that will be used instead.
			        */
			       get_template_part( 'loop', 'page' );
			       ?>
                   
                </div>
		        
                
                <div class="fourcol last"> 
                    <?php get_template_part('second-sidebar'); ?>
                </div>
                
                <div class="clear"></div>
            
            </div>
            
        </div><!-- .container -->
        
<?php get_footer(); ?>
