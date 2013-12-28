<?php
function woo_add_technik_tab()
{
	global $product;
	$tabs = apply_filters( 'woocommerce_product_tabs', array() );

	if ( ! empty( $tabs ) ) : ?>
	<div class='six units single-product-main-image alpha'>
		<div class="woocommerce-tabs">
			<ul class="tabs">
				<li class="technik_tab">
					<a href="#tab-technik"><?php echo apply_filters( 'woocommerce_product_additional_information_tab_title', "Technische Daten") ?></a>
				</li>

			</ul>


				<div class="panel entry-content" id="tab-technik">
					<?php 
					$heading = apply_filters( 'woocommerce_product_additional_information_heading', __( 'Technische Daten', 'woocommerce' ) );
					echo '<h2>'.$heading.'</h2>';
					$product->list_attributes();
					?>
				</div>


		</div>	
	</div>
	<div class='six units single-product-summary'>
	<div class="woocommerce-tabs">
		<ul class="tabs">
			<?php foreach ( $tabs as $key => $tab ) {
					if ($key!="additional_information") {
			?>

				<li class="<?php echo $key ?>_tab">
					<a href="#tab-<?php echo $key ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', $tab['title'], $key ) ?></a>
				</li>

			<?php } } ?>
		</ul>
		<?php foreach ( $tabs as $key => $tab ) { if ($key!="additional_information") {?>

			<div class="panel entry-content" id="tab-<?php echo $key ?>">
				<?php call_user_func( $tab['callback'], $key, $tab ) ?>
			</div>

		<?php } } ?>
	</div>
<?php
	endif;
}

/* Change default product gallery to Enfold gallery */
add_action('hoang_woo_before_single_product_summary', 'hoang_show_enfold_product_images', 20);
function hoang_show_enfold_product_images() {
	global $product;
	$attachment_ids = $product->get_gallery_attachment_ids();
	$attachment_ids = implode(',',$attachment_ids);
	$featured_img_id = get_post_thumbnail_id($product->id);
	$shortcode = "[av_gallery ids='".$featured_img_id.",".$attachment_ids."' style='big_thumb' preview_size='shop_single' crop_big_preview_thumbnail='avia-gallery-big-crop-thumb' thumb_size='shop_single' columns='5' imagelink='avianolink noLightbox' lazyload='avia_lazyload']";
	echo do_shortcode($shortcode);
}
function header_slider() {
	layerslider(4);
}