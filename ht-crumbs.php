<?php
if ((is_page() && !is_front_page()) || is_home() || is_category() || is_single()) {
   echo '<ul id="breadcrumbs">';
   echo '<li class="front_page"><a href="'.get_bloginfo('url').'" title="'.get_bloginfo('name').'">Home</a></li>';
   $post_ancestors = get_post_ancestors($post);
   if ($post_ancestors) {
      $post_ancestors = array_reverse($post_ancestors);
      foreach ($post_ancestors as $crumb)
          echo '<li>&nbsp;/ <a href="'.get_permalink($crumb).'" title="'.get_the_title($crumb).'">'.get_the_title($crumb).'</a></li>';
   }
   if (is_category() || is_single()) {
      $category = get_the_category();
      echo '<li>&nbsp;/ <a href="'.get_category_link($category[0]->cat_ID).'"  title="'.$category[0]->cat_name.'">'.$category[0]->cat_name.'</a></li>';
   }
   if (!is_category())
      echo '<li class="current">&nbsp;/ <a href="'.get_permalink().'"  title="'.get_the_title().'">'.get_the_title().'</a></li>';
   echo '</ul>';
} 
?> 