<div id="access" role="navigation">
    <?php /*  Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff */ ?>
        <div class="skip-link screen-reader-text">
            <a href="#content" title="<?php esc_attr_e( 'Skip to content', 'twentyten' ); ?>"><?php _e( 'Skip to content', 'twentyten' ); ?></a>
        </div>
        
    <?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu.  The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.  */ ?>
    <?php 
    
    if (has_nav_menu('primary-logged-out')) {
    
        if ( is_user_logged_in() ) {
            wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) );
        } else {
            wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary-logged-out' ) );
        }    
    }
    
    else { 
        wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) );
    } 
    ?>
    
    <?php //TODO: make this conditional get_template_part('searchform'); ?>
</div><!-- #access -->