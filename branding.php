<?php
    require_once('html-gen.php');
    global $background_image_prefix;
    $name = esc_attr( get_bloginfo( 'name', 'display' ) );
?>
<div id="branding" role="banner">
    <?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div'; ?>
    <<?php echo $heading_tag; ?> id="site-title">
    	
    		<a href="<?php echo home_url( '/' ); ?>"
    		   title="<?php echo $name ?>"
    		   rel="home"><?php
                $logo = $name;
                $current_config = Configuration::get_current_configuration_settings();
                if( $current_config ) {
                    if( isset($current_config['background-image:url'] ) ) {
                        if( $image_options = get_option('ap_images') ) {
                            if( $logo_image = $current_config['background-image:url'] ) {
                                $logo_val = intval($logo_image);
                                if(isset($image_options['images'][$logo_val])) {
                                   if($img_url = $image_options['images'][$logo_val]) {
                                       $logo = bt('image', attr_src($img_url) . attr_alt($name));
                                   }
                               }
                            }
                        }
                    }
                }
                echo $logo;
    		       ?>

    		</a>
    	
    </<?php echo $heading_tag; ?>>

    <div id="site-description"><?php bloginfo( 'description' ); ?></div>

</div><!-- #branding -->
