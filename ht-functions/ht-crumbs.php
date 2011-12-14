<?php    

function ht_crumbs() {

    $post = get_the_ID();
    
        if ((is_page() && !is_front_page()) || is_category() || is_single()) {
            $content = '<ul id="breadcrumbs">';
            $content .= '<li class="front_page"><a href="'.get_bloginfo('url').'" title="'.get_bloginfo('name').'">'.get_bloginfo('title').'</a></li>';
            $post_ancestors = get_post_ancestors($post);
            if ($post_ancestors) {
                $post_ancestors = array_reverse($post_ancestors);
                foreach ($post_ancestors as $crumb)
                    $content .= '<li><span class=crumb-divider>&nbsp;/ </span><a href="'.get_permalink($crumb).'" title="'.get_the_title($crumb).'">'.get_the_title($crumb).'</a></li>';

            }
        
        
         if (is_category() || is_single() && !is_attachment()) {
            $category = get_the_category();
            if($category) $content .= '<li><span class=crumb-divider>&nbsp;/ </span><a href="'.get_category_link($category[0]->cat_ID).'"  title="'.$category[0]->cat_name.'">'.$category[0]->cat_name.'</a></li>';
          }
    
       if (!is_category())
          $content .= '<li class="current"><span class=crumb-divider>&nbsp;/ </span><a href="'.get_permalink().'"  title="'.get_the_title().'">'.get_the_title().'</a></li>';
       $content .= '</ul>';
    }
    
    elseif (is_home()) {
        $content = '<ul id="breadcrumbs">';
        $content .= '<li class="front_page"><a href="'.get_bloginfo('url').'" title="'.get_bloginfo('name').'">'.get_bloginfo('title').'</a></li>';
        $content .= '<li class="current"><span class=crumb-divider>&nbsp;/ </span><a href="#">Blog</a></li>';
        $content .= '</ul>';
    }

    // Returns the content.
    echo $content;
}  
    
?>