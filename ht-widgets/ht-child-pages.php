<?php

// Child pages widget

/**
 * HTChildMenu Class
 */
class HTChildMenu extends WP_Widget {
    /** constructor */
    function HTChildMenu() {
        parent::WP_Widget(false, $name = 'ArtPress | Child Pages');
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);

        global $wp_query;
        $thePostID = $wp_query->post->ID;
        $theParentID = $wp_query->post->post_parent;

        $children = wp_list_pages('title_li=&child_of='.$thePostID.'&echo=0'.'&depth=2');
        if ($children) { ?>
            <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title;
                        else echo '<h3 class="widget-title">' . get_the_title() . ' Menu</h3>'; ?>

                        <ul class="sub-pages">
                        <?php echo $children; ?>
                        </ul>
                        <?php if ($wp_query->post->post_parent == TRUE) { ?>
                        <br />
                        <a style="font-size:.8em" href="<?php echo get_permalink($theParentID); ?>">Back up to <?php echo get_the_title($theParentID); ?></a>
                        <?php } ?>
        <?php }
        elseif ($wp_query->post->post_parent == TRUE) { ?>
        <?php $children = wp_list_pages('title_li=&child_of='.$theParentID.'&echo=0'.'&depth=2'); ?>
             <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title;
                        else echo '<h3 class="widget-title">' . get_the_title($theParentID) . ' Menu</h3>'; ?>

                        <ul class="sub-pages">
                        <?php echo $children; ?>
                        </ul>
                        <br />
                        <a style="font-size:.8em" href="<?php echo get_permalink($theParentID); ?>">Back up to <?php echo get_the_title($theParentID); ?></a>
          <?php  }?>

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

} // class HTChildMenu

// register Child pages widget
add_action('widgets_init', create_function('', 'return register_widget("HTChildMenu");'));