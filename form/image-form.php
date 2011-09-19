<?php
/**
 * Creates a valid options array of stub, empty values for ap_images
 */
function get_ap_image_defaults() {
    $options = array();
    $options['images'] = array();
    $options['logo-image'] = null;
    return $options;
}

function ap_image_upload_page() {
    global $post;
    $settings = get_option('ap_images');
    $o = get_settings_fields('artpress_image_options');

    // display existing artpress background images
    $rows = row(
        th('image name') .
        th('description') .
        th('thumbnail') .
        th('delete images'));

    // display table of images
    if(isset($settings['images']) && $images = $settings['images']) {
        foreach( array_keys($images) as $image_id ) {
            if($image = get_post($image_id) ) {
                $aid = $image->ID;
                $title = $image->post_title;
                $desc = $image->post_content;
                $img = wp_get_attachment_image($aid, 'thumbnail');

                // display delete checkbox
                $checkbox = input('checkbox', attr_name("ap_images[delete-image][{$aid}]")
                );
                $rows .= row( td($title) .
                    td( $desc ) .
                    td( $img ) .
                    td( $checkbox )
                );
            }
        }
        $o .= h2('Background images');
        $o .= table($rows);
    } else {
        $o .= p("Uploaded images will be displayed here.");
    }

    // display new image selector
    $o .= h2('Upload new image');
    $rows = row(td('select image') . td(input('file', attr_name('uploaded-image') . attr_size('40') )));
    $rows .= row(td('optional description') . td(input('text', attr_name('ap_images[image-description]') . attr_size(30) )));
    $o .= table($rows);

    // create rest of page
	$o .= button_submit('submit');
	$form = form( 'post', 'options.php', $o, 'multipart/form-data' );
    $div = div($form, attr_class('wrap, imageupload') );
    echo $div;
}
function ap_image_validate($new_settings) {

    // get previous settings
    $previous_settings = get_option('ap_images');
    if($previous_settings == null) $previous_settings = array();

    // delete images
    if( isset($new_settings['delete-image']) && $delete = $new_settings['delete-image'] ) {
        foreach( array_keys($delete) as $aid ) {
            unset($previous_settings['images'][$aid]);
            wp_delete_attachment($aid);

            // delete logo-image from settings if the logo image is being deleted
            if ( $logo = $new_settings['logo-image'] ) {
                if( $logo == $aid ) {
                    unset( $new_settings['logo-image'] );
                }
            }
        }
    }

    // handle any uploaded files
    if ( isset($_FILES['uploaded-image']) ){
        if( $file = $_FILES['uploaded-image'] ) {
            if( $file['name'] ) {

                // upload the file the uploads folder
                $override_defaults = array('test_form' => false);
                $uploaded_file = wp_handle_upload($file, $override_defaults);

                //create attachment
                $attachment_arr = array(
        			'post_mime_type' => $uploaded_file['type'],
        			'post_title' => preg_replace('/\.[^.]+$/', '', basename($uploaded_file['file'])),
        			'post_content' => '' . $new_settings['image-description'],
                    'tags_input'=>'artpress',
        			'post_status' => 'inherit');
                $filename = $uploaded_file['file'];
                $attach_id = wp_insert_attachment($attachment_arr, $filename);;

                // create thumbnails etc
                // http://stackoverflow.com/questions/2674069/adding-posts-with-thumbnail-programatically-in-wordpress
                $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
                wp_update_attachment_metadata( $attach_id,  $attach_data );

                // make a record of the post id of this new attachment in our settings
                $new_settings['images'][$attach_id] = wp_get_attachment_url($attach_id);
            }
        }
    }

    // merge the new settings with the old
    $new_settings = array_merge_recursive_distinct($previous_settings, $new_settings);
    return $new_settings;
}