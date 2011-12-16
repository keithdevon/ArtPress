<?php

// Add Fancybox

function fancybox() {
?>
<script type="text/javascript">
	jQuery(document).ready(function($){
		var select = $('a[href$=".bmp"],a[href$=".gif"],a[href$=".jpg"],a[href$=".jpeg"],a[href$=".png"],a[href$=".BMP"],a[href$=".GIF"],a[href$=".JPG"],a[href$=".JPEG"],a[href$=".PNG"]');
		select.attr('rel', 'fancybox');
		select.fancybox();
	});
</script>
<?php
}
function enqueue_fancybox_scripts() {
    	wp_enqueue_script('jquery');
    	wp_enqueue_style('jquery.fancybox', get_stylesheet_directory_uri().'/fancybox/jquery.fancybox.css', false, '1.2.6');
    	wp_enqueue_script('jquery.fancybox', get_stylesheet_directory_uri().'/fancybox/jquery.fancybox.js', array('jquery'), '1.2.6');
    	wp_enqueue_script('jquery.easing', get_stylesheet_directory_uri().'/js/jquery.easing.js', array('jquery'), '1.3');
    	add_action('wp_head', 'fancybox');
}

add_action('wp_enqueue_scripts', 'enqueue_fancybox_scripts');