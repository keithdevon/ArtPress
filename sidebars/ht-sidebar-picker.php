<?php // ADD A SIDEBAR PICKER TO PAGES AND POSTS

// Include the following code in single.php and any other template files.

/*

$sidebar_choice = get_post_meta($post_id, '_ht_sidebar_picker', TRUE); ?>
			             <?php switch ($sidebar_choice) {
			                 case FALSE:
                                get_sidebar();
                                break;
                            case 'A':
                                get_sidebar();
                                break;
                            case 'B':
                                 get_template_part( 'second-sidebar' );
                                break;
                            case 'C':
                                get_template_part( 'third-sidebar' );
                                break;
                            case 'D':
                                get_template_part( 'fourth-sidebar' );
                                break;
                        } 
                        
*/
                        

/* Use the admin_menu action to define the custom boxes */
add_action('admin_menu', 'ht_add_sidebar_picker');

/* Adds a custom section to the "side" of the post edit screen */
function ht_add_sidebar_picker() {
     add_meta_box('ht_sidebar_picker', 'Choose your sidebar', 'ht_sidebar_picker_box', 'post', 'side', 'low');
}

/* prints the custom field in the new custom post section */
function ht_sidebar_picker_box() {
     //get post meta value
     global $post;
     $custom = get_post_meta($post->ID,'_ht_sidebar_picker',true);

     // use nonce for verification
     echo '<input type="hidden" name="ht_sidebar_picker_noncename" id="ht_sidebar_picker_noncename" value="'.wp_create_nonce('ht-sidebar-picker').'" />';

     // The actual fields for data entry
     echo '<label for="sidebar-picker">Sidebar</label>';
     echo '<select name="sidebar-picker" id="sidebar-picker" size="1">';

      //lets create an array of sidebars to loop through
      $sidebars = array('Default', 'A','B','C','D');
      foreach ($sidebars as $sidebar) {
            echo '<option value="'.$sidebar.'"';
            if ($custom == $sidebar) echo ' selected="selected"';
            echo '> '.$sidebar.' </option>';
      }

     echo "</select>";
}

/* use save_post action to handle data entered */
add_action('save_post', 'ht_sidebar_picker_save_postdata');

/* when the post is saved, save the custom data */
function ht_sidebar_picker_save_postdata($post_id) {
     // verify this with nonce because save_post can be triggered at other times
     if (!wp_verify_nonce($_POST['ht_sidebar_picker_noncename'], 'ht-sidebar-picker')) return $post_id;

     // do not save if this is an auto save routine
     if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;

     $sidebar_picker = $_POST['sidebar-picker'];
     update_post_meta($post_id, '_ht_sidebar_picker', $sidebar_picker);
}


// Register extra sidebars

// Sidebar C
	register_sidebar( array(
		'name' => __( 'Sidebar C', 'twentyten' ),
		'id' => 'sidebar-C',
		'description' => __( 'The third sidebar widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

// Sidebar D
	register_sidebar( array(
		'name' => __( 'Sidebar D', 'twentyten' ),
		'id' => 'sidebar-D',
		'description' => __( 'The fourth sidebar widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );