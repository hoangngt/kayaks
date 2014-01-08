<?php
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
add_action( 'hoang_woo_after_single_product_summary', 'woo_add_technik_tab', 1);
add_action( 'hoang_woo_after_single_product_summary',  'avia_close_div', 3);
add_action( 'hoang_woo_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'hoang_woo_after_single_product_summary', 'woocommerce_output_related_products', 20 );
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
					$heading = apply_filters( 'woocommerce_product_additional_information_heading', __( '', 'woocommerce' ) );
					echo '<h2>'.$heading.'</h2>';
					$product->list_attributes();
					?>
				</div>


		</div>	
	</div>
	<div class='six units single-product-summary'>
		<div class="woocommerce-tabs">
		<?php if(is_b2b_product()) unset($tabs['reviews']);		//don't show reviews tab for b2b Products
		?>
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

function header_slider() {
	layerslider(4);
}