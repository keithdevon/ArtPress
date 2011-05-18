<?php
    include_once 'wp/wp-includes/functions.php';
    echo 'success: ';
    $success = update_option('artpress_theme_options', $null);
    echo "-";
    echo $success;
?>