<div id="branding" role="banner">
				<?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div'; ?>
				<<?php echo $heading_tag; ?> id="site-title"
				
<?php 
$centre_logo = 1;
if( $centre_logo == 1 ) echo ' style="text-align:center;" '; ?>
                >
					<span>
						<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
					</span>
				</<?php echo $heading_tag; ?>>
				
				<div id="site-description"
				
<?php
if( $centre_logo == 1 ) echo ' style="width:100%; text-align:center;" '; ?>
                
				
				><?php bloginfo( 'description' ); ?></div>
				
</div><!-- #branding -->