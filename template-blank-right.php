<?php
/**
 * Template Name: Two columns (content, blank)
 *
 * A custom page template without sidebar.
 *
 */

get_header(); ?>
    <div id="content" class="container">
		<div class="blank-left row">
            <div class="eightcol" role="main">
			 <?php// TODO: make this conditional --> get_template_part( 'ht-crumbs' );?>

			<?php
			/* Run the loop to output the page.
			 * If you want to overload this in a child theme then include a file
			 * called loop-page.php and that will be used instead.
			 */
			 get_template_part( 'loop', 'page' );
			?>

            </div><!-- .eightcol last -->
            <div class="fourcol last"></div>
			<div class="clear"></div>
		</div><!-- .row -->
    </div><!-- content -->
<?php get_footer(); ?>
