<?php
/**
 * Template Name: Three columns, content, sidebar, sidebar
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
		<div class="row">
		  
			<div class="sixcol" role="main">
			
			<?php get_template_part( 'ht-crumbs' );?>

			<?php
			/* Run the loop to output the page.
			 * If you want to overload this in a child theme then include a file
			 * called loop-page.php and that will be used instead.
			 */
			 get_template_part( 'loop', 'page' );
			?>

			</div><!-- #content -->
			<div class="threecol"><?php get_template_part('sidebars/sidebar');?></div>
			<div class="threecol last"><?php get_template_part( 'sidebars/second-sidebar' );?></div>
			
			<div class="clear"></div>
			
		</div><!-- #container -->
    </div>
<?php get_footer(); ?>
