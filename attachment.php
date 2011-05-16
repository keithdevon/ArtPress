<?php
/**
 * The template for displaying attachments.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

		<div id="container" class="single-attachment container">
		  <div class="row">
			<div id="content" class="twelvecol last" role="main">
			<?php get_template_part( 'ht-crumbs' );?>

			<?php
			/* Run the loop to output the attachment.
			 * If you want to overload this in a child theme then include a file
			 * called loop-attachment.php and that will be used instead.
			 */
			get_template_part( 'loop', 'attachment' );
			?>

			</div><!-- #content -->
			<div class="clear"></div>
			</div><!-- row -->
		</div><!-- #container -->

<?php get_footer(); ?>
