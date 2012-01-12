<?php
/**
 * Template Name: Three columns (sidebar, content, sidebar)
 *
 * A custom page template without sidebar.
 *
 */

get_header(); ?>
    <div id="content" class="container">
		<div class="three-columns row">
		  <div class="threecol"><?php get_template_part('sidebar');?></div>
			<div id="content" class="sixcol" role="main">

			<?php get_template_part( 'ht-crumbs' );?>

			<?php
			/* Run the loop to output the page.
			 * If you want to overload this in a child theme then include a file
			 * called loop-page.php and that will be used instead.
			 */
			 get_template_part( 'loop', 'page' );
			?>

			</div><!-- #content -->

			<div class="threecol last"><?php get_template_part( 'sidebars/second-sidebar' );?></div>

			<div class="clear"></div>

		</div><!--  -->
    </div><!-- #content -->
<?php get_footer(); ?>
