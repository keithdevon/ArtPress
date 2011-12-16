<?php

// Collections Custom Post Type

add_action( 'init', 'create_my_post_types' );
 
function create_my_post_types() {
  /* register_post_type( 'ap_collections',

        array(
            'labels' => array(
                'name' => _x('Collections', 'post type general name'),
                'singular_name' => _x('Collection', 'post type singular name'),
                'add_new' => _x('Add New', 'book'),
                'add_new_item' => __('Add New Collection'),
                'edit_item' => __('Edit Collection'),
                'new_item' => __('New Collection'),
                'all_items' => __('All Collections'),
                'view_item' => __('View Collection'),
                'search_items' => __('Search Collections'),
                'not_found' =>  __('No collections found'),
                'not_found_in_trash' => __('No collections found in Trash'), 
                'parent_item_colon' => '',
                'menu_name' => 'Collections'
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title','editor', 'thumbnail'),
            'rewrite' => array( 'slug' => 'collections' ),
            'register_meta_box_cb' => 'add_collection_metaboxes',
 
        )
    ); */
    
    register_post_type( 'ap_galleries',
        array(
            'labels' => array(
                'name' => _x('Galleries', 'post type general name'),
                'singular_name' => _x('Gallery', 'post type singular name'),
                'add_new' => _x('Add New', 'book'),
                'add_new_item' => __('Add New Gallery'),
                'edit_item' => __('Edit Gallery'),
                'new_item' => __('New Gallery'),
                'all_items' => __('All Galleries'),
                'view_item' => __('View Gallery'),
                'search_items' => __('Search Galleries'),
                'not_found' =>  __('No galleries found'),
                'not_found_in_trash' => __('No galleries found in Trash'), 
                'parent_item_colon' => '',
                'menu_name' => 'Galleries'
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title','editor'),
            'rewrite' => array( 'slug' => 'galleries' ),
        )
    );
    
    if (get_option('artpress_rewrite_flush') == 'yes') {
        flush_rewrite_rules();
        update_option('artpress_rewrite_flush', 'no');
    }
}


// Add collections taxonomy

function ap_register_taxonomies() {
 
    register_taxonomy(
        'collections',
        array( 'ap_galleries' ),
        array(
            'public' => true,
            'labels' => array( 
                'name' => _x( 'Collections', 'taxonomy general name' ),
                'singular_name' => _x( 'Collection', 'taxonomy singular name' ),
                'search_items' =>  __( 'Search Collections' ),
                'all_items' => __( 'All Collections' ),
                'parent_item' => __( 'Parent Collection' ),
                'parent_item_colon' => __( 'Parent Collection:' ),
                'edit_item' => __( 'Edit Collection' ), 
                'update_item' => __( 'Update Collection' ),
                'add_new_item' => __( 'Add New Collection' ),
                'new_item_name' => __( 'New Collection Name' ),
                'menu_name' => __( 'Collections' ), ),
            'hierarchical' => true,
            'rewrite' => array( 'slug' => 'collection' ),
        )
    );
}
 
add_action( 'init', 'ap_register_taxonomies' );



//Add custom meta box
 
// Add the Collections Meta Boxes
 
function add_collection_metaboxes() {
    add_meta_box('ap_collections_categories', 'Collection Category', 'ap_collections_categories', 'ap_collections', 'side', 'default');
}
 
// Output the Collections metaboxes
 
function ap_collections_categories() {
    global $post;
    $collection_category = get_post_meta($post->ID, '_ap_coll_cat', true);
    $values = get_post_custom( $post->ID );
    $selected = isset( $values['_ap_coll_cat'] ) ? esc_attr( $values['_ap_coll_cat'][0] ) : "" ?>
        <select name="_ap_coll_cat" > 
            <option value=""><?php echo esc_attr(__('Collection Category')); ?></option> 
            <?php 
                $categories=  get_categories('taxonomy=collections'); 
            
                foreach ($categories as $category) {
                    $option = '<option value="'.$category->category_nicename.'" '.selected( $selected, $category->category_nicename ).' >';
                    $option .= $category->cat_name;
                    $option .= ' ('.$category->category_count.')';
                    $option .= '</option>';
                    echo $option;
                } ?>
        </select>

  <?php  
}
 
// Save the Metabox Data
 
function ap_save_collections_meta($post_id, $post) {
    if ( 'ap_collections' == get_post_type() ) {
 
        if ( !current_user_can( 'edit_post' , $post ->ID )) return $post ->ID;
 
        $collections_meta['_ap_coll_cat'] = $_POST['_ap_coll_cat'];
 
        foreach ($collections_meta as $key => $value) { // Cycle through the $collections_meta array!
            if( $post->post_type == 'revision' ) return; // Don't store custom data twice
            $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
            if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
                update_post_meta($post->ID, $key, $value);
            } else { // If the custom field doesn't have a value
                add_post_meta($post->ID, $key, $value);
            }
            if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
        }
    }
}
 
add_action('save_post', 'ap_save_collections_meta', 1, 2); // save the custom fields