<?php 
if ( have_posts() ) while ( have_posts() ) : the_post(); 
     ?>
        <div id="post-<?php the_ID(); ?>" <?php post_class('container gallery-layout-php'); ?>>
            <div class="row">
                <div class=" twelvecol last">
                    <?php get_template_part( 'ht-crumbs' );?>
                    <h1 class="page-title"><?php the_title(); ?></h1>

                    <!-- TODO: make this conditional 
                    <div class="entry-meta">
                       <?php // twentyten_posted_on(); ?>
                    </div><!-- .entry-meta -->
                
                </div><!-- twelvecol last -->
                <div class="clear"></div>
                <div class="twelvecol">
                    <?php the_content(); ?>
                </div>
            </div><!-- row -->

                <?php 
                $ap_cat = get_post_meta(get_the_ID(), '_ap_coll_cat', true); 
                $args = array(
                    'post_type' => 'ap_galleries',
                	'tax_query' => array(
                		array(
                			'taxonomy' => 'collections',
                			'field' => 'slug',
                			'terms' => $ap_cat
                		)
                	)
                );
                $ap_query = new WP_Query($args);
                $post_count = 0;
                $numItems = ($ap_query->post_count);
                $postnumber = 0;

                while ($ap_query->have_posts()) : $ap_query->the_post();
                    $post_count ++;
                    $postnumber ++;
                    if($post_count == 4) $post_count = 1;
                    
                    if($post_count == 1) echo '<div class="row">';?>
                    <div class="fourcol <?php if($post_count == 3) echo ' last';?>">
                        <div id="post-<?php the_ID(); ?>" <?php post_class('grid-single'); ?>>
                            <div class="entry-content">
                                <?php if ( post_password_required() ) : ?>
				                    <?php the_content(); ?>
                                <?php else :
                                    $images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
                                    if ( $images ) :
                                        $total_images = count( $images );
                                        $image = array_shift( $images );
                                        $image_img_tag = wp_get_attachment_image( $image->ID, 'Gallery list' );
                                        $image_img_tag = preg_replace( '/(width|height)=\"\d*\"\s/', "", $image_img_tag );
				                ?>
				                <div class="gallery-icon">
                                    <a class="size-thumbnail" href="<?php the_permalink(); ?>"><?php echo $image_img_tag; ?></a>
                                </div><!-- .gallery-thumb -->

                                <h2 class="gallery-title">
                                    <a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
                                </h2>

                                <!-- TODO: Make this conditional
                                <div class="entry-meta">
                                    <?php /* twentyten_posted_on(); 
                                    printf( _n( '<a %1$s>%2$s photo</a>.', '| <a %1$s>%2$s photos</a>.', $total_images, 'twentyten' ),
								    'href="' . get_permalink() . '" title="' . sprintf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ) . '" rel="bookmark"',
                                    number_format_i18n( $total_images )
                                    ); */?>
                                </div>
                                -->
                                
                                <?php endif; ?>
                                <?php // TODO make this conditional the_excerpt();?>
                                <?php endif; ?>

                            </div><!-- .entry-content -->

                        </div><!-- #post-## -->
                    </div><!-- .twocol -->
                    <?php if($post_count == 3 || $postnumber == $numItems) echo ' <div class="clear"></div></div>';?>
   
                <?php endwhile; ?>
            </div><!-- row -->

				        <div class="row">
				            <div class="sixcol">
				                <?php comments_template( '', true ); ?>
				            </div>
				        </div><!-- row -->
				        </div>
				        <div class="clear"></div>
				    </div><!-- row -->
				</div>
            </div><!-- container -->
<?php endwhile; // end of the loop. ?>