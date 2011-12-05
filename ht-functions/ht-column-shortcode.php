<?php

//------Columns

//One third

add_shortcode( '1third', 'ht_1third_shortcode' );//add 2/3rds column

function ht_1third_shortcode( $atts, $content = null ) {
   extract( shortcode_atts( array(
      'p' => ''
      ), $atts ) );
    if($p == 'first' ) $start = '<div style="width:100%; clear:both;"></div>';
    else $start = '';
    if($p == 'last' ) {
        $end = '<div style="clear:both;"></div>';
        $class = ' last';
    }
    else {
        $end = '';
        $class = '';
    }
    
    return $start . '<div class="fourcol internal-col '. $class .'">' . do_shortcode( $content ) . '</div>' . $end;
}


//Two thirds

add_shortcode( '2thirds', 'ht_2thirds_shortcode' );//add 2/3rds column

function ht_2thirds_shortcode( $atts, $content = null ) {
   extract( shortcode_atts( array(
      'p' => 'first'
      ), $atts ) );
    if($p == 'first' ) $start = '<div style="width:100%; clear:both;"></div>';
    else $start = '';
    if($p == 'last' ) {
        $end = '<div style="clear:both;"></div>';
        $class = ' last';
    }
    else {
        $end = '';
        $class = '';
    }
    
    return $start . '<div class="eightcol internal-col '. $class .'">' . do_shortcode( $content ) . '</div>' . $end;
}


//one half

add_shortcode( '1half', 'ht_1half_shortcode' );//add 2/3rds column

function ht_1half_shortcode( $atts, $content = null ) {
   extract( shortcode_atts( array(
      'p' => 'first'
      ), $atts ) );
    if($p == 'first' ) $start = '<div style="width:100%; clear:both;"></div>';
    else $start = '';
    if($p == 'last' ) {
        $end = '<div style="clear:both;"></div>';
        $class = ' last';
    }
    else {
        $end = '';
        $class = '';
    }
    
    return $start . '<div class="sixcol internal-col '. $class .'">' . do_shortcode( $content ) . '</div>' . $end;
}


//one quarter

add_shortcode( '1quart', 'ht_1quart_shortcode' );//add 2/3rds column

function ht_1quart_shortcode( $atts, $content = null ) {
   extract( shortcode_atts( array(
      'p' => ''
      ), $atts ) );
    if($p == 'first' ) $start = '<div style="width:100%; clear:both;"></div>';
    else $start = '';
    if($p == 'last' ) {
        $end = '<div style="clear:both;"></div>';
        $class = ' last';
    }
    else {
        $end = '';
        $class = '';
    }
    
    return $start . '<div class="threecol internal-col '. $class .'">' . do_shortcode( $content ) . '</div>' . $end;
}