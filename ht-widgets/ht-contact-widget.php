<?php

// Address widget

/**
 * HTAddressWidget Class
 */
class HTAddressWidget extends WP_Widget {
    /** constructor */
    function HTAddressWidget() {
        parent::WP_Widget(false, $name = 'ArtPress | Contact');
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
       
        global $wp_query;
        //$thePostID = $wp_query->post->ID;
        //$theParentID = $wp_query->post->post_parent; 

        <?php echo $before_widget; ?>
        <?php if ( $title )
                echo $before_title . $title . $after_title;
            else echo '<h3 class="widget-title">' . get_the_title() . ' Menu</h3>'; ?>
               
            <?php echo $after_widget; ?>
        <?php
        }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
	$instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        $title = esc_attr($instance['title']);
        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <?php 
    }

} // class HTAddressWidget

// register Child pages widget
add_action('widgets_init', create_function('', 'return register_widget("HTAddressWidget");'));