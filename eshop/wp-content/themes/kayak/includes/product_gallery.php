<?php

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