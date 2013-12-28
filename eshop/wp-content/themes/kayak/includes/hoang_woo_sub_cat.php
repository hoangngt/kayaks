<?php
add_shortcode("hoang_woo_sub_cat", "hoang_woo_sub_cat");
function hoang_woo_sub_cat($atts) {
        $product_cat = "product_cat";
        extract(shortcode_atts(array(  
        'cat_id' => '33',  
        'columns' => '1',
                'numbers' => '4'
    ), $atts)); 
    $catpage = get_query_var( 'page' ) ? get_query_var( 'page' ) : 1;
    $offset = ($catpage - 1) * $numbers;
 // get list of subcategories of $cat_id
         $args = array(
          'child_of'     => $cat_id,
          'menu_order'   => 'ASC',
          'hide_empty'   => 1,
          'hierarchical' => 1,
          'number'                 => $numbers,
          'offset'                 => $offset,
          'pad_counts'   => 1
        );
        $product_categories = get_terms($product_cat,$args);
        if (count($product_categories)<1) return;
// make html output for shortcode
        $container_id = 1;
        $extraClass                 = 'first';
        $grid                                 = 'one_fourth';
        if($preview_mode == 'auto') $image_size = 'portfolio';
        $post_loop_count         = 1;
        $loop_counter                = 1;
        $output                                = '';
        $style_class                = empty($style) ? 'no_margin' : $style;
        $total                                = count($product_categories) % 2 ? "odd" : "even";
        switch($columns)
        {
                case "1": $grid = 'av_fullwidth';  if($preview_mode == 'auto') $image_size = 'featured'; break;
                case "2": $grid = 'av_one_half';   break;
                case "3": $grid = 'av_one_third';  break;
                case "4": $grid = 'av_one_fourth'; if($preview_mode == 'auto') $image_size = 'portfolio_small'; break;
                case "5": $grid = 'av_one_fifth';  if($preview_mode == 'auto') $image_size = 'portfolio_small'; break;
        }
        $output .= "<img width='100' height='119' src='http://kayakstogo.de/eshop/wp-content/uploads/2013/12/made_usa_100.png' class='' alt='Made in USA' style='margin:0 0 20px 80px'>";
        $output .= "<div class='grid-sort-container isotope {$style_class}-container with-excerpt-container grid-total-{$total} grid-col-{$columns} grid-links-{$linking}' data-portfolio-id='{$container_id}'>";
        foreach ($product_categories as $cat) {
                $the_id         = $cat->term_id;
                $parity                = $post_loop_count % 2 ? 'odd' : 'even';
                $last       = count($product_categories) == $post_loop_count ? " post-entry-last " : "";
                $post_class = "post-entry post-entry-{$the_id} grid-entry-overview grid-loop-{$post_loop_count} grid-parity-{$parity} {$last}";
                $title_link = $link = home_url()."/?product_cat=".$cat->slug;
                $title                 = $cat->name;
                $custom_overlay = apply_filters('avf_portfolio_custom_overlay', "", $cat);
                $link_markup         = apply_filters('avf_portfolio_custom_image_container', array("a href='{$link}' title='".esc_attr(strip_tags($title))."' ",'a'), $cat);

                $title                         = apply_filters('avf_portfolio_title', $title, $cat);
                $title_link     = apply_filters('avf_portfolio_title_link', $title_link, $cat);
                if($columns == "1" && $one_column_template == 'special')
        {
            $extraClass .= ' special_av_fullwidth ';

            $output .= "<div data-ajax-id='{$the_id}' class=' grid-entry flex_column isotope-item all_sort {$style_class} {$post_class} {$sort_class} {$grid} {$extraClass}'>";
            $output .= "<article class='main_color inner-entry' ".avia_markup_helper(array('context' => 'entry','echo'=>false)).">";
            $output .= apply_filters('avf_portfolio_extra', "", $cat);

            $output .= "<div class='av_table_col first portfolio-entry grid-content'>";

            if(!empty($title))
            {
                $markup = avia_markup_helper(array('context' => 'entry_title','echo'=>false));
                $output .= '<header class="entry-content-header">';
                $output .= "<h2 class='portfolio-grid-title entry-title' $markup><a href='{$title_link}'>".$title."</a></h2>";
                $output .= '</header>';
            }

            if(!empty($excerpt))
            {
                $markup = avia_markup_helper(array('context' => 'entry_content','echo'=>false));

                $output .= "<div class='entry-content-wrapper'>";
                $output .= "<div class='grid-entry-excerpt entry-content' $markup>".$excerpt."</div>";
                $output .= "</div>";
            }
            $output .= '<div class="avia-arrow"></div>';
            $output .= "</div>";
            $thumbnail_id = get_woocommerce_term_meta($the_id , 'thumbnail_id', true);
                 $img_src = wp_get_attachment_image_src($thumbnail_id, $image_size );
            $img_src = $img_src[0];
            $image = "<img width='260' height='185' src='".$img_src."' class='attachment-portfolio_small wp-post-image' alt='013'>";
            if(!empty($image))
            {
                $output .= "<div class='av_table_col portfolio-grid-image'>";
                $output .= "<".$link_markup[0]." data-rel='grid-1' class='grid-image avia-hover-fx'>".$custom_overlay.$image."</".$link_markup[1].">";
                $output .= "</div>";
            }
            $output .= '<footer class="entry-footer"></footer>';
            $output .= "</article>";
            $output .= "</div>";
        }
        else
        {
            $extraClass .= ' default_av_fullwidth ';
            $thumbnail_id = get_woocommerce_term_meta($the_id , 'thumbnail_id', true);
            $img_src = wp_get_attachment_image_src($thumbnail_id, $image_size );
            $img_src = $img_src[0];
            $image = "<img width='260' height='185' src='".$img_src."' class='attachment-portfolio_small wp-post-image' alt='013'>";
            $output .= "<div data-ajax-id='{$the_id}' class=' grid-entry flex_column isotope-item all_sort {$style_class} {$post_class} {$sort_class} {$grid} {$extraClass}'>";
            $output .= "<article class='main_color inner-entry' ".avia_markup_helper(array('context' => 'entry','echo'=>false)).">";
            $output .= apply_filters('avf_portfolio_extra', "", $cat);
            $output .= "<".$link_markup[0]." data-rel='grid-".avia_post_grid::$grid."' class='grid-image avia-hover-fx'>".$custom_overlay. $image."</".$link_markup[1].">";
            $output .= !empty($title) || !empty($excerpt) ? "<div class='grid-content'><div class='avia-arrow'></div>" : '';

            if(!empty($title) && ($title != 'Emotion Kayaks'))
            {
            if($title != 'Santa Cruz Kayaks')
            {
            if($title != 'Saturn Kayaks')
            {
                $markup = avia_markup_helper(array('context' => 'entry_title','echo'=>false));
                $output .= '<header class="entry-content-header">';
                $output .= "<h3 class='grid-entry-title entry-title' $markup><a href='{$title_link}' title='".esc_attr(strip_tags($title))."'>".$title."</a></h3>";
                $output .= '</header>';
            }}}
            $output .= !empty($excerpt) ? "<div class='grid-entry-excerpt entry-content' ".avia_markup_helper(array('context'=>'entry_content','echo'=>false)).">".$excerpt."</div>" : '';
            $output .= !empty($title) || !empty($excerpt) ? "</div>" : '';
            $output .= '<footer class="entry-footer"></footer>';
            $output .= "</article>";
            $output .= "</div>";
        }


                $loop_counter ++;
                $post_loop_count ++;
                $extraClass = "";

                if($loop_counter > $columns)
                {
                        $loop_counter = 1;
                        $extraClass = 'first';
                }
        }

        $output .= "</div>";

                //append pagination
        $args = array(
          'child_of'     => $cat_id,
          'menu_order'   => 'ASC',
          'hide_empty'   => 1,
          'hierarchical' => 1,
          'pad_counts'   => 1
        );
        $count = count (get_terms($product_cat,$args));                // get total amount of subcat
        $avia_pagination = hoang_pagination(ceil($count/$numbers), 'nav');
        $output .= $avia_pagination;
        return $output;
}
function hoang_pagination($pages = '', $wrapper = 'div')
	{
		global $paged;

		if(get_query_var('paged')) {
		     $paged = get_query_var('paged');
		} elseif(get_query_var('page')) {
		     $paged = get_query_var('page');
		} else {
		     $paged = 1;
		}

		$output = "";
		$prev = $paged - 1;
		$next = $paged + 1;
		$range = 2; // only edit this if you want to show more page-links
		$showitems = ($range * 2)+1;



		if($pages == '')
		{
			global $wp_query;
			//$pages = ceil(wp_count_posts($post_type)->publish / $per_page);
			$pages = $wp_query->max_num_pages;
			if(!$pages)
			{
				$pages = 1;
			}
		}

		$method = "get_pagenum_link";
	



		if(1 != $pages)
		{
			$output .= "<$wrapper class='pagination'>";
			$output .= ($paged > 2 && $paged > $range+1 && $showitems < $pages)? "<a href='".$method(1)."'>&laquo;</a>":"";
			$output .= ($paged > 1 && $showitems < $pages)? "<a href='".$method($prev)."'>&lsaquo;</a>":"";

			$output .= "<span class='pagination-meta'>";
			for ($i=1; $i <= $pages; $i++)
			{
				if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
				{
					$output .= ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".$method($i)."' class='inactive' >".$i."</a>";
				}
			}
			$output .= "</span>";
			$output .= ($paged < $pages && $showitems < $pages) ? "<a href='".$method($next)."'>&rsaquo;</a>" :"";
			$output .= ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) ? "<a href='".$method($pages)."'>&raquo;</a>":"";
			$output .= "</$wrapper>\n";
		}

		return $output;
	}