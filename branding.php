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
                    $options = get_option('ap_options');
                    $images = get_option('ap_images');
                    if( isset($options['saves'][$options['current-save-id']]['logo-toggle']) && $images && isset($images['ap_image_0']['url']) ) {
                       $img_url = $images['ap_image_0']['url'];
                       echo bt('image', attr_src($img_url) . attr_alt($name));
                    } else echo $name;
    		       ?>
    		   
    		</a>
    	</span>
    </<?php echo $heading_tag; ?>>
    
    <div id="site-description"><?php bloginfo( 'description' ); ?></div>

</div><!-- #branding -->
