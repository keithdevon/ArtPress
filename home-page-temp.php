<?php
/*
Template Name: Home Page
*/

get_header(); ?>

		<div id="container" class="row">
			<div id="content" class="twelvecol" role="main">

			<?php
			/* Run the loop to output the page.
			 * If you want to overload this in a child theme then include a file
			 * called loop-page.php and that will be used instead.
			 */
			get_template_part( 'loop', 'page' );
			?>

			</div><!-- #content -->
            
            <div class="row">
            
                <?php if ( is_active_sidebar( '1-2-home-widget-area' ) ) : ?>
				    <?php dynamic_sidebar( '1-2-home-widget-area' ); ?>
				<?php endif; ?>

                <?php if ( is_active_sidebar( '2-2-home-widget-area' ) ) : ?>
                    <?php dynamic_sidebar( '2-2-home-widget-area' ); ?>
                <?php endif; ?>
                
            </div>
                
            <div class="row">
                
                <?php if ( is_active_sidebar( '1-3-home-widget-area' ) ) : ?>
                	<?php dynamic_sidebar( '1-3-home-widget-area' ); ?>
                <?php endif; ?>
                
                
                <?php if ( is_active_sidebar( '2-3-home-widget-area' ) ) : ?>
                	<?php dynamic_sidebar( '2-3-home-widget-area' ); ?>
                <?php endif; ?>
                
                
                <?php if ( is_active_sidebar( '3-3-home-widget-area' ) ) : ?>
                	<?php dynamic_sidebar( '3-3-home-widget-area' ); ?>
                <?php endif; ?>
                             
            </div>
            
            
        </div><!-- #container -->
<?php get_footer(); ?>