<?php

// Image uploader

/*
Plugin Name: Upload Demo
Description: Demonstrate a plugin that lets you upload an image
Author: Otto
Author URI: http://ottodestruct.com
License: GPL2

    Copyright 2010  Samuel Wood  (email : otto@ottodestruct.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 2, 
    as published by the Free Software Foundation. 
    
    You may NOT assume that you can use any other version of the GPL.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    
    The license for this software can likely be found here: 
    http://www.gnu.org/licenses/gpl-2.0.html
    
*/

// add the admin page and such
add_action('admin_init', 'ud_admin_init');
function ud_admin_init() {
    register_setting( 'ud_options', 'ud_options', 'ud_options_validate' );
    add_settings_section('ud_main', 'Background Image', 'ud_section_text', 'ud');
    add_settings_field('ud_filename', 'File:', 'ud_setting_filename', 'ud', 'ud_main');
}

// add the admin options page
add_action('admin_menu', 'ud_admin_add_page');
function ud_admin_add_page() {
    $mypage = add_options_page('Upload Demo', 'Upload Demo', 'manage_options', 'ud', 'ud_options_page');
}

// display the admin options page
function ud_options_page() {
?>
    <div class="wrap">
    <h2>Upload Demo</h2>
    <p>You can upload a file. It'll go in the uploads directory.</p>
    <form method="post" enctype="multipart/form-data" action="options.php">
    <?php settings_fields('ud_options'); ?>
    <?php do_settings_sections('ud'); ?>
    <p class="submit">
    <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
    </p>
    </form>

    </div>
    
<?php
}

function ud_section_text() {
    $options = get_option('ud_options');
    echo '<p>Upload your file here:</p>';
    if ($file = $options['file']) {
        // var_dump($file);
        echo "<img src='{$file['url']}' />";
    }
}

function ud_setting_filename() {
    echo '<input type="file" name="ud_filename" size="40" />';
}

function ud_options_validate($input) {
    $newinput = array();
    if ($_FILES['ud_filename']) {
        $overrides = array('test_form' => false); 
        $file = wp_handle_upload($_FILES['ud_filename'], $overrides);
        $newinput['file'] = $file;
    }
    return $newinput;
}