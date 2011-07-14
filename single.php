<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>
		
		<?php 
		
		  global $post;
            $post_id = $post->ID;
		  if(get_post_format( $post_id ) != 'gallery') { ?>

			<?php
			/* Run the loop to output the post.
			 * If you want to overload this in a child theme then include a file
			 * called loop-single.php and that will be used instead.
			 */
			get_template_part( 'loop', 'single' );
			?>
						
			         <div class="fourcol last">

                        <?php get_template_part('sidebars/sidebar'); ?>
			     
			         </div>
			         
			         <div class="clear"></div>
			
                </div><!-- row -->
                </div><!-- container -->
                
            <?php }
            else { get_template_part( 'gallery-layout' );} ?>
            
            

<?php get_footer(); ?>