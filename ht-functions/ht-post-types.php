<?php

// Collections Custom Post Type

add_action( 'init', 'create_my_post_types' );
 
function create_my_post_types() {
    register_post_type( 'ap_collections',
        array(
            'labels' => array(
                'name' => __( 'Collections' ),
                'singular_name' => __( 'Collection' )
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title','editor','thumbnail'),
            'rewrite' => array( 'slug' => 'collection' ),
            'register_meta_box_cb' => 'add_collection_metaboxes',
 
        )
    );
    
    register_post_type( 'ap_galleries',
        array(
            'labels' => array(
                'name' => __( 'Galleries' ),
                'singular_name' => __( 'Gallery' )
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title','editor','thumbnail'),
            'rewrite' => array( 'slug' => 'gallery' ),
            'taxonomies' => array( 'category' ),
 
        )
    );
}


//

//Add custom meta box
 
// Add the Collections Meta Boxes
 
function add_collection_metaboxes() {
    add_meta_box('ap_collections_categories', 'Collection Category', 'ap_collections_categories', 'ap_collections', 'side', 'default');
}
 
// Output the Property metaboxes
 
function ap_collections_categories() {
    global $post;
    $collection_category = get_post_meta($post->ID, '_ap_coll_cat', true);
    $values = get_post_custom( $post->ID );
    $selected = isset( $values['_ap_coll_cat'] ) ? esc_attr( $values['_ap_coll_cat'][0] ) : Ó; ?>
        <select name="_ap_coll_cat" > 
            <option value=""><?php echo esc_attr(__('Collection Category')); ?></option> 
            <?php 
                $categories=  get_categories(); 
            
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