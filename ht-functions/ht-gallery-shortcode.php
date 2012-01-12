<?php
remove_shortcode( 'gallery' );
add_shortcode('gallery', 'ht_gallery_shortcode');

/**
 * The Gallery shortcode.
 *
 */
function ht_gallery_shortcode($attr) {
	global $post, $wp_locale;

	static $instance = 0;
	$instance++;

	// Allow plugins/themes to override the default gallery template.
	$output = apply_filters('post_gallery', '', $attr);
	if ( $output != '' )
		return $output;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemcol'    => 'div',
		'icontag'    => 'div',
		'captiontag' => 'div',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => ''
	), $attr));

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$include = preg_replace( '/[^0-9,]+/', '', $include );
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}

	$captiontag = tag_escape($captiontag);
	$columns = intval($columns);
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$gallery_style = $gallery_div = '';
	if ( apply_filters( 'use_default_gallery_style', true ) )
		$gallery_style = "
		<style type='text/css'>
			#{$selector} {
				margin: auto;
			}
			#{$selector} .gallery-item {
				float: {$float};
				margin-top: 10px;
				text-align: center;
				width: {$itemwidth}%;
			}
			#{$selector} img {
				border: 2px solid #cfcfcf;
			}
			#{$selector} .gallery-caption {
				margin-left: 0;
			}
		</style>
		<!-- see gallery_shortcode() in wp-includes/media.php -->";
	$size_class = sanitize_html_class( $size );
	$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
	$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );
	$output .= "<div class='row gallery-row'>";

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
	   switch ($columns) {
        case 1:
            $ht_thumnail_size = 'full-width';
            break;
        case 2:
            $ht_thumnail_size = 'six-col';
            break;
        case 3:
            $ht_thumnail_size = 'four-col';
            break;
        case 4:
            $ht_thumnail_size = 'four-col';
            break;
        case 6:
            $ht_thumnail_size = 'four-col';
            break;
        }
		$link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $ht_thumnail_size, false) : wp_get_attachment_link($id, $size, true, false);
	   $break = '';
        $i++;
        $output .= "<{$itemcol} class='gallery-item internal-col  ";
        switch ($columns) {
        case 1:
            $col_class = "twelvecol ";
            $break = "</div><!-- .row --><div class='row gallery-row'>";
            break;
        case 2:
            $col_class = "sixcol ";
            if(( $i % 2 )==0) {
                $col_class .= "last";
                $break = "</div><!-- .row --><div class='row gallery-row'>";
                }
            break;
        case 3:
            $col_class = "fourcol ";
            if(( $i % 3 )==0) {
                $col_class .= "last";
                $break = "</div><!-- .row --><div class='row gallery-row'>";
                }
            break;
        case 4:
            $col_class = "threecol ";
            if(( $i % 4 )==0) {
                $col_class .= "last";
                $break = "</div><!-- .row --><div class='row gallery-row'>";
                }
            break;
        case 6:
            $col_class = "twocol ";
            if(( $i % 6 )==0) {
                $col_class .= "last";
                $break = "</div><!-- .row --><div class='row gallery-row'>";
                }
            break;
        }
        $output .= $col_class . " '>";
		$output .= "
			<{$icontag} class='gallery-icon'>
				$link
			</{$icontag}>";
		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= "
				<{$captiontag} class='wp-caption-text gallery-caption'>
				" . wptexturize($attachment->post_excerpt) . "
				</{$captiontag}>";
		}
		$output .= "</{$itemcol}>";
		$output .= $break;
		/*if ( $columns > 0 && ++$i % $columns == 0 )
			$output .= '<br style="clear: both" />';removed to allow the columns to do their thing! kdev */

	}

	$output .= "
			<br style='clear: both;' />
		</div></div>\n";

	// for testing TODO remove before launch
    // $output .= 'Number of columns: '.$columns.'<br />';
    // $output .= 'Image size : '.$ht_thumnail_size;

	return $output;
}