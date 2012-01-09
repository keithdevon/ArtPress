<?php
/**
 * Template Name: Two columns, blank, content
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
    <div id="content" class="container">
		<div class="blank-left row">
		  <div class="fourcol"></div>
			<div class="eightcol last" role="main">
			 <?php get_template_part( 'ht-crumbs' );?>

			<?php
			/* Run the loop to output the page.
			 * If you want to overload this in a child theme then include a file
			 * called loop-page.php and that will be used instead.
			 */
			 get_template_part( 'loop', 'page' );
			?>

			</div><!-- .eightcol last -->
			<div class="clear"></div>
		</div><!-- .row -->
    </div><!-- content -->
<?php get_footer(); ?>
