<?php 
    require_once('html-gen.php');
    global $background_image_prefix; 
    $name = esc_attr( get_bloginfo( 'name', 'display' ) );
?>
<div id="branding" role="banner">
    <?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div'; ?>
    <<?php echo $heading_tag; ?> id="site-title">
    	<span>
    		<a href="<?php echo home_url( '/' ); ?>" 
    		   title="<?php echo $name ?>" 
    		   rel="home"><?php  
    		       $settings = get_option('artpress_theme_options');
    		       if( $settings['section_settings']['site title']['logo-image-use']['value'] == 'on' ) {
    		           $bg_images = get_option('ap_background_image_settings');
    		           echo bt('image', attr_src($bg_images[$background_image_prefix . '0']['url']) . attr_alt($name)); // FIXME using ugly hardcoded reference
    		       } else echo $name;
    		       ?>
    		   
    		</a>
    	</span>
    </<?php echo $heading_tag; ?>>
    
    <div id="site-description"><?php bloginfo( 'description' ); ?></div>

</div><!-- #branding -->