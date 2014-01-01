<?php
add_shortcode("hoang_product_gallery","hoang_product_gallery");
function hoang_product_gallery($atts) {
	$output  = "";
	$first   = true;
	global $product;
	$variant = "";
	if ($product->is_type("variable")) { 
		$variant = hoang_color_variable();
	}
	extract(shortcode_atts(array(
								'order'      	=> 'ASC',
								'thumb_size' 	=> 'thumbnail',
								'lightbox_size' => 'large',
								'preview_size'	=> 'portfolio',
								'ids'    	 	=> '',
								'imagelink'     => 'lightbox',
								'style'			=> 'thumbnails',
								'columns'		=> 5,
				                'lazyload'      => 'avia_lazyload',
				                'crop_big_preview_thumbnail' => 'avia-gallery-big-crop-thumb'
								), $atts));
	$attachments = get_posts(array(
				'include' => $ids,
				'post_status' => 'inherit',
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'order' => $order,
				'orderby' => 'post__in')
				);
		if(!empty($attachments) && is_array($attachments))
				{
					$gallery=1;
					$thumb_width = round(100 / $columns, 4);
		
                    $markup = avia_markup_helper(array('context' => 'image','echo'=>false));
					$output .= "<div class='avia-gallery avia-gallery-".$gallery." ".$lazyload." avia_animate_when_visible ".$meta['el_class']."' $markup>";
					$thumbs = "";
					$counter = 0;

					foreach($attachments as $attachment)
					{
						$class	 = $counter++ % $columns ? "class='$imagelink'" : "class='first_thumb $imagelink'";

						$img  	 = wp_get_attachment_image_src($attachment->ID, $thumb_size);
						$link	 =  apply_filters('avf_avia_builder_gallery_image_link', wp_get_attachment_image_src($attachment->ID, $lightbox_size), $attachment, $atts, $meta);
						$prev	 = wp_get_attachment_image_src($attachment->ID, $preview_size);

						$caption = trim($attachment->post_excerpt) ? wptexturize($attachment->post_excerpt) : "";
						$tooltip = $caption ? "data-avia-tooltip='".$caption."'" : "";

                        $alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
                        $alt = !empty($alt) ? esc_attr($alt) : '';
                        $title = trim($attachment->post_title) ? esc_attr($attachment->post_title) : "";
                        $description = trim($attachment->post_content) ? esc_attr($attachment->post_content) : "";

                        $markup_url = avia_markup_helper(array('context' => 'image_url','echo'=>false));

						if($style == "big_thumb" && $first)
						{
							$output .= "<a class='avia-gallery-big fakeLightbox $imagelink $crop_big_preview_thumbnail' href='".$link[0]."' data-onclick='1' title='".$description."' ><span class='avia-gallery-big-inner' $markup_url>";
							$output .= "	<img src='".$prev[0]."' title='".$title."' alt='".$alt."' />";
			  				if($caption) $output .= "	<span class='avia-gallery-caption'>{$caption}</span>";
							$output .= "</span></a>";
						}

						$thumbs .= " <a href='".$link[0]."' data-rel='gallery-".$gallery."' data-prev-img='".$prev[0]."' {$class} data-onclick='{$counter}' title='".$description."' $markup_url><img {$tooltip} src='".$img[0]."' title='".$title."' alt='".$alt."' /></a>";
						$first = false;
					}

					$output .= "<div class='avia-gallery-thumb'>{$thumbs}</div>";
					$output .= $variant;
					$output .= "</div>";

					//generate thumb width based on columns
					$extra_style .= "<style type='text/css'>";
					$extra_style .= "#top #wrap_all .avia-gallery-".$gallery." .avia-gallery-thumb a{width:{$thumb_width}%;}";
					$extra_style .= "</style>";
		
					add_action('wp_footer', 'hoang_footer_style');
					

				}
	if ($product->is_type("variable")) {
			$output = "<div id='hoang_wrapper'>". $output . "</div>";
	}
	return $output;
}
function hoang_footer_style()
			{
				$extra_style .= "<style type='text/css'>";
				$extra_style .= "#top #wrap_all .avia-gallery-1 .avia-gallery-thumb a{width:33%;}";
				$extra_style .= "</style>";
				echo $extra_style;
			}


add_action('hoang_woo_before_single_product_summary', 'hoang_show_enfold_product_images', 20);
function hoang_show_enfold_product_images() {
	global $product;
	$attachment_ids = $product->get_gallery_attachment_ids();
	$attachment_ids = implode(',',$attachment_ids);
	$featured_img_id = get_post_thumbnail_id($product->id);
	$shortcode = "[hoang_product_gallery ids='".$featured_img_id.",".$attachment_ids."' columns=3 style='big_thumb' preview_size='shop_single' crop_big_preview_thumbnail='avia-gallery-big-crop-thumb' thumb_size='shop_single' imagelink='avianolink noLightbox' lazyload='avia_lazyload']";
	echo do_shortcode($shortcode);	
}
?>