<?php
/*
Template Name: Home Page
*/

get_header(); ?>

    <div id="content" class="container">
        <div class="row">
            <div class="twelvecol" role="main">

                <?php
                /* Run the loop to output the page.
                 * If you want to overload this in a child theme then include a file
                 * called loop-page.php and that will be used instead.
                 */
                get_template_part( 'loop', 'page' );
                ?>

			</div><!-- row -->
            
            <?php if ( is_active_sidebar( '1-2-home-widget-area' ) || is_active_sidebar( '2-2-home-widget-area' ) ) : ?>
            
                <div class="row">
		        <?php dynamic_sidebar( '1-2-home-widget-area' ); ?>
		   
                <?php dynamic_sidebar( '2-2-home-widget-area' ); ?>
                <div class="clear"></div>
        </div>
            <?php endif; ?>
            
                
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
            <div class="clear"></div>           
        </div>
        
        <div class="clear"></div>
    </div><!-- #container -->
<?php get_footer(); ?>