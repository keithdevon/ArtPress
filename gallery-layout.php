<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?><div id="post-<?php the_ID(); ?>" <?php post_class('container gallery-layout-php'); ?>>
                        <div class="row">
                            <div class=" twelvecol last">
                                <?php get_template_part( 'ht-crumbs' );?>
                                <h1 class="page-title"><?php the_title(); ?></h1>
                        
                                <div class="entry-meta">
                                   <?php twentyten_posted_on(); ?>
                                </div><!-- .entry-meta -->
                            </div><!-- twelvecol last -->
                            <div class="clear"></div>
                            <div class="twelvecol">
                                <?php the_content(); ?>
                            </div>
                        </div><!-- row -->
                        
                    
					       	
                        
					       	
					       	<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
                           
                        <div class="row">
                            <div class="twelvecol">
<?php if ( get_the_author_meta( 'description' ) ) : // If a user has filled out their description, show a bio on their entries  ?>
					       <div id="entry-author-info">
					           <h2>About the author</h2>

					       	<div id="author-avatar">
					       		<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'twentyten_author_bio_avatar_size', 60 ) ); ?>
					       	</div><!-- #author-avatar -->
					       	<div id="author-description">
					       							       		<?php the_author_meta( 'description' ); ?>
					       		<div id="author-link">
					       			<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
					       				<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'twentyten' ), get_the_author() ); ?>
					       			</a>
					       		</div><!-- #author-link	-->
					       	</div><!-- #author-description -->
					       </div><!-- #entry-author-info -->
<?php endif; ?>

					       <div class="entry-utility">
					       	<?php twentyten_posted_in(); ?>
					       	<?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>
					       </div><!-- .entry-utility -->
				        

				        <div id="nav-below" class="navigation">
				            <div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'twentyten' ) . '</span> %title' ); ?></div>
				            <div class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'twentyten' ) . '</span>' ); ?></div>
				        </div><!-- #nav-below -->
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
            </div><!-- conatiner -->
<?php endwhile; // end of the loop. ?>