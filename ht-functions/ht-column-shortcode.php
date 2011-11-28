<?php

//------Columns

add_shortcode( '3col_first', 'ht_col1of3_shortcode' );//add 3 column shortcode (for columns 1 and 2)

function ht_col1of3_shortcode( $atts, $content = null ) {
   return '<div style="width:100%; clear:both;"></div><div class="fourcol internal-col">' . do_shortcode( $content ) . '</div>';
}

add_shortcode( '3col', 'ht_col2of3_shortcode' );//add 3 column shortcode (for column 2)

function ht_col2of3_shortcode( $atts, $content = null ) {
   return '<div class="fourcol internal-col" >' . do_shortcode( $content ) . '</div>';
}

add_shortcode( '3col_last', 'ht_col3of3_shortcode' );//add 3rd of 3 columns

function ht_col3of3_shortcode( $atts, $content = null ) {
   return '<div class="fourcol internal-col last">' . do_shortcode( $content ) . '</div><div style="clear:both;"></div>';
}

add_shortcode( '2col_first', 'ht_col1of2_shortcode' );// add 2 column shotcode

function ht_col1of2_shortcode( $atts, $content = null ) {
   return '<div style="width:100%; clear:both;"></div><div class="sixcol internal-col">' . do_shortcode( $content ) . '</div>';
}

add_shortcode( '2col_last', 'ht_col2of2_shortcode' );// 2nd of 2 columns

function ht_col2of2_shortcode( $atts, $content = null ) {
   return '<div class="sixcol internal-col last" >' . do_shortcode( $content ) . '</div><div style="clear:both;"></div>';
}

add_shortcode( '4col_first', 'ht_col1of4_shortcode' );// add 4 column shotcode

function ht_col1of4_shortcode( $atts, $content = null ) {
   return '<div style="width:100%; clear:both;"></div><div class="threecol internal-col">' . do_shortcode( $content ) . '</div>';
}

add_shortcode( '4col', 'ht_colof4_shortcode' );// add 4 column shotcode

function ht_colof4_shortcode( $atts, $content = null ) {
   return '<div class="threecol internal-col">' . do_shortcode( $content ) . '</div>';
}

add_shortcode( '4col_last', 'ht_col4of4_shortcode' );// 4th of 4 columns

function ht_col4of4_shortcode( $atts, $content = null ) {
   return '<div class="threecol internal-col last" >' . do_shortcode( $content ) . '</div><div style="clear:both;"></div>';
}