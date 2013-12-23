<?php
global $woocommerce;

// define as checkout process, to ensure correct price calculation
if ( !defined('WOOCOMMERCE_CHECKOUT') ) define( 'WOOCOMMERCE_CHECKOUT', TRUE );

$woocommerce_checkout = $woocommerce->checkout();

if ( ! isset( $_SESSION[ 'first_checkout_post_array' ] ) || ! $_SESSION[ 'first_checkout_post_array' ] ) {

	$message = __( 'Es wurden keine Daten übergeben. Prüfen Sie den Warenkorb.', Woocommerce_German_Market::get_textdomain() );
	echo '<p class="error">'. $message .'</a></p>';
	return;

} else {

	$message = __( 'Bitte prüfen Sie alle Daten. Schließen Sie den Vorgang dann ab.', Woocommerce_German_Market::get_textdomain() );
}

$woocommerce->add_message( $message );

?>
<form name="checkout" method="post" class="checkout" action="<?php echo $woocommerce->cart->get_checkout_url(); ?>">

	<div class="col2-set" id="customer_details">
		<div class="col-1">

			<?php WGM_Template::second_checkout_form_billing(); ?>

		</div>

		<?php if( WGM_Template::should_be_shipping_to_shippingadress() ): ?>
		<div class="col-2">
			<?php WGM_Template::second_checkout_form_shipping();
			 ?>
		</div>

		<?php endif; ?>
	</div>

	<?php

	$out = WGM_Template::checkout_readonly_field(
			'order_comments',
			array(
				'type'        => 'textarea',
				'class'       => array( 'notes' ),
				'name'        => 'order_comments',
				'label'       => __( 'Order Notes', 'woocommerce' ),
				'placeholder' => __( 'Notes about your order.', 'woocommerce' )
			)
		);

		if( $out ) {
			echo '<h3>' . __( 'Notes/Comments', Woocommerce_German_Market::get_textdomain() ) . '</h3>';

			if ( is_array( $out ) ) {
				echo $out[0];
				$hidden_fields[] = $out[1];
			}
			if ( is_array( $out ) ) {
				// print the hidden fields
				echo implode( '', $hidden_fields );
			}
		}

	?>

	<?php if ( isset( $_SESSION[ 'first_checkout_post_array' ][ 'payment_method' ] )  ) : ?>
	<h3><?php _e( 'Zahlungsmethode', Woocommerce_German_Market::get_textdomain() ) ?></h3>
	<?php
	$available_gateways = $woocommerce->payment_gateways->get_available_payment_gateways();
	$gateway = $available_gateways[ $_SESSION[ 'first_checkout_post_array' ][ 'payment_method' ] ];
	?>
	<label for="payment_method"> <?php echo apply_filters( 'woocommerce_gateway_icon', $gateway->get_icon(), $gateway->id ); ?></label>
		<h4 id="payment_method">
		<?php echo $gateway->title; ?>
		</h4>
		<span class="wgm-break"></span>
	<?php endif; ?>

	<?php
		$last_hint = get_option( 'woocommerce_de_last_checkout_hints' );
		if( $last_hint && trim( $last_hint ) != '' ):
	?>

	<div class="checkout_hints">
		<h3>
			<?php echo __( 'Hinweis', Woocommerce_German_Market::get_textdomain() ); ?>
		</h3>

		<?php echo $last_hint; ?>
	</div>
	<span class="wgm-break"></span>

	<?php endif; ?>


	<h3 id="order_review_heading"><?php _e('Your order', 'woocommerce' ); ?></h3>

<?php
// copied from woocommerce core ( woocommerce-ajax.php ), important to update values
if ( isset( $_SESSION[ 'first_checkout_post_array' ]['shipping_method']) ) $_SESSION['_chosen_shipping_method'] = $_SESSION[ 'first_checkout_post_array' ][ 'shipping_method' ];
if ( isset( $_SESSION[ 'first_checkout_post_array' ]['country'] ) ) $woocommerce->customer->set_country( $_SESSION[ 'first_checkout_post_array' ]['country'] );
if ( isset( $_SESSION[ 'first_checkout_post_array' ]['state']) ) $woocommerce->customer->set_state( $_SESSION[ 'first_checkout_post_array' ]['state'] );
if ( isset( $_SESSION[ 'first_checkout_post_array' ]['postcode'] ) ) $woocommerce->customer->set_postcode( $_SESSION[ 'first_checkout_post_array' ]['postcode'] );

if ( isset( $_SESSION[ 'first_checkout_post_array' ]['s_country'] ) ) $woocommerce->customer->set_shipping_country( $_SESSION[ 'first_checkout_post_array' ]['s_country'] );
if ( isset( $_SESSION[ 'first_checkout_post_array' ]['s_state']) ) $woocommerce->customer->set_shipping_state( $_SESSION[ 'first_checkout_post_array' ]['s_state'] );
if ( isset( $_SESSION[ 'first_checkout_post_array' ]['s_postcode'] ) ) $woocommerce->customer->set_shipping_postcode( $_SESSION[ 'first_checkout_post_array' ]['s_postcode'] );

$woocommerce->cart->calculate_totals();
$available_methods = $woocommerce->shipping->get_available_shipping_methods();
?>
<!-- Begin Order Review Template -->
<div id="order_review">

	<table class="shop_table">
		<thead>
			<tr>
				<th class="product-name"><?php _e('Product', 'woocommerce'); ?></th>
				<th class="product-price"><?php _e('Price', 'woocommerce'); ?></th>
				<th class="product-quantity"><?php _e('Qty', 'woocommerce'); ?></th>
				<th class="product-total"><?php _e('Totals', 'woocommerce'); ?></th>
				<?php
				if( get_option('woocommerce_tax_display_cart') == 'excl' ) {
					?><th class="product-tax"><?php _e('zusätzlich MwSt.', 'WooCommerce-German-Market') ?></th>	<?php
				} else {
					?><th class="product-tax"><?php _e('enthaltene MwSt.', 'WooCommerce-German-Market') ?></th>	<?php
				}
			?>
			</tr>
		</thead>
		<tfoot>

			<tr class="cart-subtotal">
				<th colspan="<?php echo apply_filters( 'wgm_colspan_checkout_cart_subtotal', 2 ); ?>"><strong><?php _e( 'Cart Subtotal', 'woocommerce' ); ?></strong></th>
				<td></td>
				<td><?php echo $woocommerce->cart->get_cart_subtotal(); ?></td>
				<td></td>
			</tr>

			<?php if ( $woocommerce->cart->get_discounts_before_tax() ) : ?>

			<tr class="discount">
				<th colspan="<?php echo apply_filters( 'wgm_colspan_checkout_discount', 2 ); ?>"><?php _e( 'Cart Discount', 'woocommerce'); ?></th>
				<td></td>
				<td>-<?php echo $woocommerce->cart->get_discounts_before_tax(); ?></td>
				<td></td>
			</tr>

			<?php endif; ?>

			<?php if ($woocommerce->cart->needs_shipping()) : ?>

			<tr class="shipping">
				<th colspan="<?php echo apply_filters( 'wgm_colspan_checkout_shipping', 2 ); ?>"><?php _e( 'Shipping', 'woocommerce'); ?></th>
				<td>
				<?php
					// If at least one shipping method is available
					if ( $available_methods ) {

						// Prepare text labels with price for each shipping method
						foreach ( $available_methods as $method ) {
							$method->full_label = esc_html( $method->label );

							if ( $method->cost > 0 ) {
								$method->full_label .= ' &mdash; ';

								// Append price to label using the correct tax settings
								if ( $woocommerce->cart->display_totals_ex_tax || ! $woocommerce->cart->prices_include_tax ) {
									$method->full_label .= woocommerce_price( $method->cost );
									if ( $method->get_shipping_tax() > 0 && $woocommerce->cart->prices_include_tax ) {
										$method->full_label .= ' '.$woocommerce->countries->ex_tax_or_vat();
									}
								} else {
									$method->full_label .= woocommerce_price( $method->cost + $method->get_shipping_tax() );
									if ( $method->get_shipping_tax() > 0 && ! $woocommerce->cart->prices_include_tax ) {
										$method->full_label .= ' '.$woocommerce->countries->inc_tax_or_vat();
									}
								}
							}
						}

						$method = $available_methods[ $_SESSION['_chosen_shipping_method'] ];

						echo $method->label;
						echo '<input type="hidden" name="shipping_method" id="shipping_method" value="'.esc_attr( $method->id ).'">';


					// No shipping methods are available
					} else {

						if ( ! $woocommerce->customer->get_shipping_country() || ! $woocommerce->customer->get_shipping_state() || ! $woocommerce->customer->get_shipping_postcode() ) {
							echo '<p>'.__('Please fill in your details above to see available shipping methods.', 'woocommerce').'</p>';
						} else {
							echo '<p>'.__('Sorry, it seems that there are no available shipping methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce').'</p>';
						}

					}
				?></td>

				<?php
					$shipping_taxes = ( count( $method->taxes ) > 0 ) ? round( array_shift( array_values( $method->taxes ) ), 2 ) : "0";
					$shipping_total = $method->cost + $shipping_taxes;
				?>

				<td><?php echo get_woocommerce_currency_symbol() . number_format( $shipping_total, 2, ',', '.' ) ;?></td>
				<td><?php echo get_woocommerce_currency_symbol(); ?><?php echo number_format( $shipping_taxes, 2, ',', '.' ) ; ?></td>
			</tr>

			<?php endif; ?>

			<?php if ($woocommerce->cart->get_discounts_after_tax() ) : ?>

			<tr class="discount">
				<th colspan="<?php echo apply_filters( 'wgm_colspan_checkout_order_discount', 2 ); ?>"><?php _e( 'Order Discount', 'woocommerce' ); ?></th>
				<td></td>
				<td>-<?php echo $woocommerce->cart->get_discounts_after_tax(); ?></td>
				<td></td>
			</tr>

			<?php endif; ?>

			<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

			<tr class="total">
				<th colspan="<?php echo apply_filters( 'wgm_colspan_checkout_total', 2 ); ?>"><strong><?php _e( 'Order Total', 'woocommerce' ); ?></strong></th>
				<td></td>
				<td><strong><?php echo $woocommerce->cart->get_total(); ?></strong></td>
				<td></td>
			</tr>

			<?php do_action( 'woocommerce_after_order_total' ); ?>

		</tfoot>
		<tbody>
			<?php
				if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) :
					foreach ( $woocommerce->cart->get_cart() as $item_id => $values ) :
						$_product = $values['data'];
						$tax = array_shift( array_values( $woocommerce->cart->tax->get_rates( $_product->tax_class ) ) );

						if ($_product->exists() && $values['quantity']>0) :
							echo '
								<tr class = "' . esc_attr( apply_filters('woocommerce_checkout_table_item_class', 'checkout_table_item', $values, $item_id ) ) . '">
									<td class="product-name">'.$_product->get_title(). $woocommerce->cart->get_item_data( $values ) .'</td>
									<td class="product-price">' . $woocommerce->cart->get_product_subtotal( $_product, 1 ) . '</td>
									<td class="product-quantity">'.$values['quantity'].'</td>
									<td class="product-total">' . apply_filters( 'woocommerce_checkout_item_subtotal', $woocommerce->cart->get_product_subtotal( $_product, $values['quantity'] ), $values, $item_id ) . '</td>
									<td class="product-tax">'. get_woocommerce_currency_symbol() . number_format( $values['line_tax'], 2, ',', '.' ) . ' (' . round( $tax[ 'rate' ], 1 ). '%)</td>
								</tr>';
						endif;
					endforeach;
				endif;

				do_action( 'woocommerce_cart_contents_review_order' );
			?>
		</tbody>
	</table>

<!-- End Order Review Template -->

<input type="submit" class="button alt" name="woocommerce_checkout_update_totals" id="place_order" value="<?php _e( 'zurück', Woocommerce_German_Market::get_textdomain() ) ?>" />
<input type="submit" class="button alt woocommerce_de_buy_button_text" name="woocommerce_checkout_place_order" id="place_order" value="<?php echo apply_filters('woocommerce_de_buy_button_text', __( 'Kaufen', Woocommerce_German_Market::get_textdomain() ); ?>" />
<?php
	//$woocommerce->nonce_field( 'process_checkout' );
	// correct the referer
	$_SESSION[ 'first_checkout_post_array' ][ '_wp_http_referer' ] = $_SERVER['REQUEST_URI'];
	WGM_Template::print_hidden_fields( $_SESSION[ 'first_checkout_post_array' ], array_keys($_SESSION[ 'first_checkout_post_array' ] ) );
?>
</div>
</form>
