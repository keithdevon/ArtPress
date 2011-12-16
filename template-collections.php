<?php
/**
 * Template Name: Collections archive
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

		<div id="content" class="container" role="main">
            <div class="row">
                <div class="twelvecol last">
                <?php // TODO: make this conditional get_template_part( 'ht-crumbs' );?>
				<h1 class="page-title">Collections</h1>
				</div>
            <div class="clear"></div>
            
            <?php
                $args=array(
                  'orderby' => 'name',
                  'order' => 'ASC',
                  'taxonomy'                 => 'collections',
                  );
                $categories=get_categories($args);
                  foreach($categories as $category) { 
                    echo '<p>Category: <a href="' . get_bloginfo('url') . '/collection/' . $category->slug . '" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>' . $category->name.'</a> </p> ';
                    if($category->description) echo '<p> Description:'. $category->description . '</p>';
                    echo '<p> Post Count: '. $category->count . '</p>';  } 
                ?>


		</div><!-- #container -->


<?php get_footer(); ?>
