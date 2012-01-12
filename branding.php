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
                // TODO this currently doesn't take into consideration
                // whether the live config is being viewed 
                $has_capability = current_user_can('edit_theme_options'); // TODO is this the correct access right?
                
                if ( $has_capability ) {
                    $config = Configuration::get_current_configuration_settings();
                }
                else {
                    $config = Configuration::get_live_configuration_settings();
                }
                if( $config ) {
                    if( isset($config['background-image:url'] ) ) {
                        if( $image_options = get_option('ap_images') ) {
                            if( $logo_image = $config['background-image:url'] ) {
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

    <?php if(bloginfo( 'description' )) { ?> 
        <div id="site-description"><?php bloginfo( 'description' ); ?></div>
    <?php } ?>

</div><!-- #branding -->
