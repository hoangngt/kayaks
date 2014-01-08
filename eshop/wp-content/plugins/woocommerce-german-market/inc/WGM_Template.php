<?php
/**
 * Class with Template Snippet functions, Template Helper Functions
 * Output filtering Funktions of WooCommerce hooks
 *
 * @author jj, ap
 */

class WGM_Template {


	static private $template_directory = NULL;

	/**
	 * Overloading Woocommerce with German Market templates
	 *
	 * @author ap
	 * @since 1.1.1
	 * @return string template path
	 */
	public static function add_woocommerce_de_templates( $template, $template_name, $template_path ){
		global $woocommerce;

		$path = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '../templates' . DIRECTORY_SEPARATOR;

		// Only load our templates if they are nonexistent in the theme
		if( file_exists( $path . $template_name ) && ! locate_template( array( $woocommerce->template_url . $template_name ) ) ) {
			$template = $path . $template_name;
		}

		return $template;
	}


	/**
	 * adds german mwst tax rate to every product in line
	 *
	 * @since	1.1.5beta
	 * @access	public
	 * @hook 	woocommerce_checkout_item_subtotal
	 * @param 	float $amount
	 * @param 	array $values
	 * @param 	int $item_id
	 * @return	string item
	 */
	public static function add_mwst_rate_to_product_item( $amount, $item, $item_id ) {

		global $woocommerce;

		$_product = get_product( $item['variation_id'] ? $item['variation_id'] : $item['product_id'] );

		if( ! $_product->is_taxable() )
			return $amount;

		$_tax = new WC_Tax();

		$t = $_tax->find_rates( array(
			'country' 	=>  $woocommerce->customer->get_country(),
			'state' 	=> $woocommerce->customer->get_state(),
			'tax_class' => $_product->tax_class
		) );

		$tax = array_shift( $t );


		if( get_option('woocommerce_tax_display_cart') == 'excl' )
			$incl_excl = __( 'zusätzlich MwSt.', Woocommerce_German_Market::get_textdomain() );

		if( get_option('woocommerce_tax_display_cart') == 'incl' )
			$incl_excl = __( 'enthaltene MwSt.', Woocommerce_German_Market::get_textdomain() );


			$tax_string = apply_filters(
				'wgm_format_vat_output',
				woocommerce_price( $item[ 'line_tax' ] ) . ' (' . round( $tax[ 'rate' ], 1 ). '%) ' . $incl_excl,
				$item[ 'line_tax' ],
				$tax[ 'rate' ],
				$incl_excl
			);


		$template = '%s <span class="product-tax"> %s </span>';

		$item = sprintf( $template, $amount, $tax_string );

		$item = apply_filters( 'wgm_additional_tax_notice', $item, $amount, $tax );

		return $item;
	}

	/**
	 * adds german mwst tax rate to every product in line in order-details.php
	 *
	 * @since	1.1.5beta
	 * @access	public
	 * @hook 	woocommerce_checkout_item_subtotal
	 * @param 	float $amount
	 * @param 	array $values
	 * @param 	object $this
	 * @return	string item
	 */
	public static function add_mwst_rate_to_product_order_item( $subtotal, $item, $order_obj ) {

		global $woocommerce;

		// Little hack for WGM_Email (see WGM_Email::email_de_footer)

		if( ! defined( 'WGM_MAIL' ) )
			define( 'WGM_MAIL', true );

		$_product = $order_obj->get_product_from_item( $item );

		if( empty( $_product ) || ! $_product->is_taxable() )
			return $subtotal;

		$_tax = new WC_Tax();

		if( @isset( $woocommerce->customer ) ) {
			$t = $_tax->find_rates( array(
				'country' 	=>  $woocommerce->customer->get_country(),
				'state' 	=> $woocommerce->customer->get_state(),
				'tax_class' => $_product->tax_class
			) );
		} else {
			$t = $_tax->get_shop_base_rate( $_product->tax_class );
		}

		$tax = array_shift( $t );


		if( get_option('woocommerce_tax_display_cart') == 'excl' )
			$incl_excl = __( 'zusätzlich MwSt.', Woocommerce_German_Market::get_textdomain() );

		if( get_option('woocommerce_tax_display_cart') == 'incl' )
			$incl_excl = __( 'enthaltene MwSt.', Woocommerce_German_Market::get_textdomain() );

		$tax_string = apply_filters(
				'wgm_format_vat_output',
				woocommerce_price( $item[ 'line_tax' ] ) . ' (' . round( $tax[ 'rate' ], 1 ) . '%) ',
				$item[ 'line_tax' ],
				$tax[ 'rate' ],
				$incl_excl
			);

		$template = '%s <span class="product-tax"> %s %s </span>';

		$item = apply_filters( 'wgm_additional_tax_notice', $item, $subtotal, $tax, $incl_excl );

		$item = sprintf( $template, $subtotal, $tax_string ,$incl_excl );

		return $item;
	}

	/**
	* print checkout button below the cart contents
	*
	* @author jj
	* @uses globals $woocommerce
	* @access public
	* @hook woocommerce_after_cart_totals
	* @static
	* @return void
	*/
	public static function woocommerce_after_cart_totals() {

		global $woocommerce;
		?>
		<div class="second-checkout-button-container">
			<a href="<?php echo esc_url( $woocommerce->cart->get_checkout_url() ); ?>" class="checkout-button button second-checkout-button alt"><?php _e( 'Proceed to Checkout &rarr;', 'woocommerce' ); ?></a>
		</div>
		<?php
	}


	public static function get_price_per_unit_data( $_product ){

		if( $_product->is_on_sale() ) {
			$price_per_unit = get_post_meta( get_the_ID(), '_sale_price_per_unit', TRUE );
			$unit = get_post_meta( get_the_ID(), '_unit_sale_price_per_unit', TRUE );
			$mult = get_post_meta( get_the_ID(), '_unit_sale_price_per_unit_mult', TRUE );
		} else {
			$price_per_unit = get_post_meta( get_the_ID(), '_regular_price_per_unit', TRUE );
			$unit = get_post_meta( get_the_ID(), '_unit_regular_price_per_unit', TRUE );
			$mult = get_post_meta( get_the_ID(), '_unit_regular_price_per_unit_mult', TRUE );
		}

		if ( $price_per_unit && $unit && $mult ) {
			return compact( 'price_per_unit', 'unit', 'mult' );
		} else {
			return Array();
		}

	}

	/**
	* print tax hint after prices in loop
	*
	* @uses globals $product, remove_action
	* @access public
	* @hook woocommerce_after_shop_loop_item_title
	* @static
	* @author jj
	* @return void
	*/
	public static function woocommerce_de_price_with_tax_hint_loop() {

		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price' );

		global $product;

		$price_per_unit_data = WGM_Template::get_price_per_unit_data( $product );

		$show_shipping_fee = ( 'on' == get_option( WGM_Helper::get_wgm_option( 'woocommerce_de_show_shipping_fee_overview' ) ) );
		$suppress_data_shipping = maybe_unserialize( get_post_meta( get_the_ID(), '_suppress_shipping_notice', TRUE ) );

		if( ! empty( $suppress_data_shipping ) && 'on' === $suppress_data_shipping )
			$show_shipping_fee = FALSE;

		if ($price_html = $product->get_price_html() ) : ?>
			<div class="price">
				<?php echo $price_html;

				if( ! empty( $price_per_unit_data ) && ( get_option( WGM_Helper::get_wgm_option( 'woocommerce_de_show_price_per_unit' ) ) == 'on' ) ) {
						
						do_action( 'wgm_before_price_per_unit_loop' );
						
						echo apply_filters( 'wmg_price_per_unit_loop', sprintf( '<span class="price-per-unit price-per-unit-loop">%s %s / %s %s</span>', 
																				$price_per_unit_data[ 'price_per_unit' ],
																				get_woocommerce_currency_symbol(),
																				$price_per_unit_data[ 'mult'],
																				$price_per_unit_data[ 'unit' ] ),

																				$price_per_unit_data[ 'price_per_unit' ],
																				get_woocommerce_currency_symbol(),
																				$price_per_unit_data[ 'mult'],
																				$price_per_unit_data[ 'unit' ] );

						do_action( 'wgm_after_price_per_unit_loop' );
					}

					if( get_option( WGM_Helper::get_wgm_option( 'woocommerce_de_kleinunternehmerregelung' ) ) == 'on' ) {
						echo '<div class="wgm-kleinunternehmerregelung">';
						echo apply_filters( 'woocommerce_de_small_business_regulation_text', __( 'Umsatzsteuerbefreit nach §19 UstG', Woocommerce_German_Market::get_textdomain() ) );
						echo '</div>';
					}

					if( $show_shipping_fee ) : ?>
						<div class="woocommerce_de_versandkosten">

							<?php if ( get_option( 'woocommerce_de_show_free_shipping' ) == 'on' ):
								_e( 'versandkostenfrei', Woocommerce_German_Market::get_textdomain() );
							else: ?>
								<a class="versandkosten" href="<?php echo get_permalink( get_option( WGM_Helper::get_wgm_option( 'versandkosten' ) ) ); ?>">
									<?php _e( 'zzgl.', Woocommerce_German_Market::get_textdomain() ); ?>
									<?php _e( 'Versand', Woocommerce_German_Market::get_textdomain() ); ?>
								</a>
							<?php endif; ?>

						</div>
					<?php endif; ?>

					<?php
					WGM_Template::text_including_tax( $product );
				?>
			</div>

		<?php endif;

	}


	/**
	*  print tax hint after prices in single
	*
	* @author jj
	* @hook woocommerce_single_product_summary
	* @uses remove_action, get_post_meta, get_the_ID, get_woocommerce_currency_symbol
	* @access public
	* @static
	* @return void
	*/
	public static function woocommerce_de_price_with_tax_hint_single() {
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price' );
		global $product;

		$price_per_unit_data = WGM_Template::get_price_per_unit_data( $product );

		$show_shipping_fee = ('on' == get_option( WGM_Helper::get_wgm_option( 'woocommerce_de_show_shipping_fee_overview_single' ) ) );

		$suppress_data_shipping = maybe_unserialize( get_post_meta( get_the_ID(), '_suppress_shipping_notice', TRUE ) );

		if( ! empty( $suppress_data_shipping ) && 'on' === $suppress_data_shipping )
			$show_shipping_fee = FALSE;

		?>
		<p itemprop="price" class="price">
			<?php

			echo $product->get_price_html();

			if( ! empty( $price_per_unit_data ) && ( get_option( WGM_Helper::get_wgm_option( 'woocommerce_de_show_price_per_unit' ) ) == 'on' ) ) {
						
						do_action( 'wgm_before_price_per_unit_single' );
						
						echo apply_filters( 'wmg_price_per_unit_single', sprintf( '<span class="price-per-unit price-per-unit-single">%s %s / %s %s</span>', 
																				$price_per_unit_data[ 'price_per_unit' ],
																				get_woocommerce_currency_symbol(),
																				$price_per_unit_data[ 'mult'],
																				$price_per_unit_data[ 'unit' ] ),

																				$price_per_unit_data[ 'price_per_unit' ],
																				get_woocommerce_currency_symbol(),
																				$price_per_unit_data[ 'mult'],
																				$price_per_unit_data[ 'unit' ] );

						do_action( 'wgm_after_price_per_unit_single' );
					}

			if( get_option( WGM_Helper::get_wgm_option( 'woocommerce_de_kleinunternehmerregelung' ) ) == 'on' ) {
				echo '<div class="wgm-kleinunternehmerregelung">';
				echo apply_filters( 'woocommerce_de_small_business_regulation_text', __( 'Umsatzsteuerbefreit nach §19 UstG', Woocommerce_German_Market::get_textdomain() ) );
				echo '</div>';
			}

			if( $show_shipping_fee ) :
						?>
						<div class="woocommerce_de_versandkosten">

							<?php if ( get_option( 'woocommerce_de_show_free_shipping' ) == 'on' ):
								_e( 'versandkostenfrei', Woocommerce_German_Market::get_textdomain() );
							else: ?>
								<?php _e( 'zzgl.', Woocommerce_German_Market::get_textdomain() ); ?>
								<a class="versandkosten" href="<?php echo get_permalink( get_option( WGM_Helper::get_wgm_option( 'versandkosten' ) ) ); ?>">
									<?php _e( 'Versand', Woocommerce_German_Market::get_textdomain() ); ?>
								</a>
							<?php endif; ?>

						</div>
					<?php endif;
			$price_per_unit_data = WGM_Template::get_price_per_unit_data( $product );
			WGM_Template::text_including_tax( $product );


	  		?>
		</p>
		<?php
	}

	/**
	* template for fee item
	*
	* @access public
	* @static
	* @param string $label
	* @param float $fee
	* @return string $html
	*/
	private static function get_additional_fee_item ( $label, $fee ) {

		return '
			<tr class="cash_on_delivery_fee">
				<th><strong>' . $label . '</th>
				<td>' . woocommerce_price( $fee )  . '</td>
			</tr>';
	}


	/**
	* Add the COD on review order into the list of items to pay for
	*
	* @author jj
	* @access public
	* @hook woocommerce_before_order_total
	* @static
	* @return void
	*/
	public static function add_items_on_review_order() {

		if( ! WGM_Gateways::gateway_fee_exists( 'cash_on_delivery', 'cash_on_delivery' ) )
			return;

		$fee = WGM_Gateways::get_gateway_fee( 'cash_on_delivery' );

		echo WGM_Template::get_additional_fee_item( __( 'Nachnahmegebühr', Woocommerce_German_Market::get_textdomain() ) , $fee );
	}


	/**
	*
	* Add the COD on review order into the list of items to pay for (tables function used in mail, etc)
	*
	* @access public
	* @static
	* @hook woocommerce_get_order_item_totals
	* @author jj
	* @param array $total_rows
	* @param class $order_class_instance
	* @return array items
	*/
	public static function add_items_order_table( $total_rows, $order_class_instance ) {

		unset( $total_rows[ 'tax' ] );
		unset( $total_rows[ 'mwst' ] );

		if( ! WGM_Gateways::gateway_fee_exists( 'cash_on_delivery', 'cash_on_delivery' ) )

			return $total_rows;

		?>
		<th scope="row" colspan="<?php echo apply_filters( 'wgm_colspan_add_items_order_table', 2 ); ?>" class="wgm-order-table-tr <?php if ( $i == 1 ) echo 'wgm-order-table-tr-cond'; ?>"><?php echo 'Nachnahmegebühr'; ?></th>
		<td class="wgm-order-table-tr <?php if ( $i == 1 ) echo 'wgm-order-table-tr-cond'; ?>"><?php echo woocommerce_price( WGM_Gateways::get_gateway_fee( 'cash_on_delivery' ) ); ?></td>

		<?php
		return $total_rows;
	}


	/**
	 * Output the Shippingform
	 * @access public
	 * @static
	 * @author jj
	 * @return string the Shipping Form
	 */
	public static function second_checkout_form_shipping() {

		global $woocommerce;
		$woocommerce_checkout = $woocommerce->checkout();
		if ( WGM_Template::should_be_shipping_to_shippingadress() ) :

			echo '<h3>' . __( 'Shipping Address', 'woocommerce' ) . '</h3>';

			echo'<table class="review_order_shipping">';
			$hidden_fields = array();

			foreach ( $woocommerce_checkout->checkout_fields[ 'shipping' ] as $key => $field ) :
				$out = WGM_Template::checkout_readonly_field( $key, $field );
				if ( is_array( $out ) ) {
					echo $out[0];
					$hidden_fields[] = $out[1];
				}
			endforeach;

			echo'</table>';
		endif;
	}

	/**
	 * @access public
	 * @static
	 * @author ap
	 * @return bool
	 */
	public static function should_be_shipping_to_shippingadress(){
		global $woocommerce;

		if ( $woocommerce->cart->needs_shipping() && ! WGM_Helper::ship_to_billing() || get_option('woocommerce_require_shipping_address') == 'yes' )
			return true;
		return false;
	}

	/**
	* Remove shipping from standard checkout form
	* @access public
	* @static
	* @author jj
	* @return void
	* @hook woocommerce_checkout_shipping
	*/
	public static function second_checkout_form_shipping_remove() {

		global $woocommerce;
		$woocommerce_checkout = $woocommerce->checkout();

		if ( isset( $_POST )  && isset( $_POST[ 'login' ] ) ) {
			// remove the normal checkout form
			remove_action( 'woocommerce_checkout_shipping', array ( $woocommerce_checkout, 'checkout_form_shipping' ) );
		}
	}


	/**
	* Output the billing information form
	* @access public
	* @static
	* @author jj
	* @return string The billing information
	*/
	public static function second_checkout_form_billing() {

		global $woocommerce;
		// Get checkout object
		$checkout = $woocommerce->checkout();

		if ( WGM_Helper::ship_to_billing() )
			echo '<h3>'. __( 'Billing &amp; Shipping', 'woocommerce' ). '</h3>';
		else
			echo '<h3>'. __( 'Billing Address', 'woocommerce' ).'</h3>';

		echo '<table class="review_order_billing">';
		$hidden_fields = array();

		// Billing Details
		foreach ( $checkout->checkout_fields[ 'billing' ]  as $key => $field ) {
			$out = WGM_Template::checkout_readonly_field( $key, $field );
			if ( is_array( $out ) ) {
				echo $out[ 0 ];
				$hidden_fields[] = $out[ 1 ];
			}
		}

		echo '</table>';

		// print the hidden fields
		echo implode( '', $hidden_fields );
	}


	/**
	* remove the billing in second checkout
	* @access public
	* @static
	* @author jj
	* @return void
	* @hook woocommerce_checkout_billing
	*/
	public static function second_checkout_form_billing_remove() {

		global $woocommerce;

		// Get checkout object
		$checkout = $woocommerce->checkout();

		if ( isset( $_POST )  && isset( $_POST[ 'login' ] ) ) {
			// remove the normal checkout form
			remove_action( 'woocommerce_checkout_billing', array( $checkout , 'checkout_form_billing' ) );
		}

	}

	/**
	* print hidden fields for given post array
	* determined by given field array
	*
	* @param array $post_array
	* @param array $fields_array
	* @static
	* @author jj
	* @return void
	*/
	public static function print_hidden_fields( $post_array, $fields_array ) {
		foreach( $fields_array as $field )
			echo '<input type="hidden" name="'. $field .'" value="'. $post_array[ $field ] .'" />';
	}


	/**
	* Change the button text, if on checkout page
	*
	* @param string $button_text
	* @static
	* @author jj
	* @return void
	* @hook woocommerce_order_button_text
	*/
 	public static function change_order_button_text( $button_text ) {

 		// @todo do not touch button, when on pay for order page
 		// @todo when refreshing payments,  session is expired, because cart is empty, see woocommerce-ajax.php
 		if ( isset( $_SESSION[ 'woocommerce_de_in_first_checkout' ] ) )
 			return __( 'weiter', Woocommerce_German_Market::get_textdomain() );

 		return $button_text;
	}


	/**
	* Adds the Shipping Time to the Product title
	*
	* @param string $item_name
	* @param string $product
	* @static
	* @author jj, ap
	* @return void
	* @hook woocommerce_order_product_title
	*/
	public static function add_shipping_time_to_product_title( $item_name, $product ) {

		$shipping_time = get_post_meta( $product->id, '_lieferzeit', TRUE );

		if ( is_numeric( $shipping_time ) && (int) $shipping_time !== -1 )
			$lieferzeit = ( int ) $shipping_time;
		else
			$lieferzeit = ( int ) get_option( WGM_Helper::get_wgm_option( 'global_lieferzeit' ) );

		$deliverytime_term = get_term( $lieferzeit, 'product_delivery_times' );
		$lieferzeit_string = $deliverytime_term->name;

		if( $lieferzeit_string == __( 'Nutze den Standard', Woocommerce_German_Market::get_textdomain() ) ) {
			$deliverytime_term = get_term( get_option( WGM_Helper::get_wgm_option( 'global_lieferzeit' ) ), 'product_delivery_times' );
			$lieferzeit_string = $deliverytime_term->name;
		}
		
		if( empty( $lieferzeit_string ) )
			$shipping_time_output = '';
		else
			$shipping_time_output = ', ' . __( 'Lieferzeit:', Woocommerce_German_Market::get_textdomain() ) . ' ' . $lieferzeit_string;

		$shipping_time_output = apply_filters( 'wgm_shipping_time_product_string', $shipping_time_output, $lieferzeit_string, $product );

		return $item_name . ' ( ' . __( 'jeweils', Woocommerce_German_Market::get_textdomain() ) .  ' ' . woocommerce_price( $product->price ) . $shipping_time_output . ' ) ';
	}


	/**
	* add fax to billing fields
	*
	* @access public
	* @static
	* @author jj
	* @return void
	* @param array	 $billing_fields
	* @return array
	* @hook woocommerce_billing_fields
	*/
	public static function billing_fields( $billing_fields ) {

		$billing_fields[ 'billing_fax' ] = array(
			'type'=> 'phone',
			'name'=>'fax',
			'label' => __( 'Fax', 'woocommerce' ),
			'required' => false,
			'class' => array( 'form-row-last' )
		);

		return WGM_Template::set_field_type( $billing_fields, 'state', 'required' ) ;
	}



	/**
	* add fax number to shipping fields
	* @access public
	* @static
	* @author jj
	* @return void
	* @param array $shipping_fields
	* @return array
	* @hook woocommerce_shipping_fields
	*/
	public static function shipping_fields( $shipping_fields ) {

		$shipping_fields[ 'shipping_fax' ] = array(
			'type'=> 'phone',
			'name'=>'fax',
			'label' => __( 'Fax', 'woocommerce' ),
			'required' => false,
			'class' => array( 'form-row-last' )
		);

		return WGM_Template::set_field_type( $shipping_fields, 'state', 'required' ) ;
	}


	/**
	* show the delivery time in overview
	* @access public
	* @static
	* @author
	* @return void
	* @return mixed
	* @hook woocommerce_after_shop_loop_item
	*/
	public static function woocommerce_de_after_shop_loop_item() {
		// if this function is defined in template, use it (like in the original woocommerce)
		if ( ! function_exists( 'woocommerce_de_after_shop_loop_item' ) )
			WGM_Template::add_template_loop_shop(  'on' == get_option( WGM_Helper::get_wgm_option( 'woocommerce_de_show_delivery_time_overview' ) ) );
		else
			woocommerce_de_after_shop_loop_item();
	}


	/**
	* Interupt checkout process after validation, to use the checkout site again, to fullfill
	* the german second checkout verify obligation
	*
	* @access public
	* @static
	* @author
	* @return void
	* @param array $posted $_POST array at hook position
	* @hook woocommerce_after_checkout_validation
	*/
	public static function do_de_checkout_after_validation ( $posted ) {

		global $woocommerce;

		$error_count = $woocommerce->error_count();
		// check widerruf
		if ( !isset( $_POST[ 'woocommerce_checkout_update_totals' ] ) && empty( $_POST[ 'widerruf' ] ) && get_option( 'woocommerce_widerruf_page_id' ) > 0 && get_option( WGM_Helper::get_wgm_option( 'woocommerce_de_show_Widerrufsbelehrung' ) ) == 'on' ) {
			$woocommerce->add_error( __( 'Sie müssen die Widerrufsbelehrung akzeptieren', Woocommerce_German_Market::get_textdomain() ) );
			$error_count ++;
		}

		if ( $error_count != 0 )
			return;

		if ( isset( $_SESSION[ 'woocommerce_de_in_first_checkout' ] ) ) {

			// reset woocommerce_de_in_first_checkout
			unset ( $_SESSION[ 'woocommerce_de_in_first_checkout' ] );

			// save the $_POST variables into session, to save them during redirect
			$_SESSION[ 'first_checkout_post_array' ] = $_POST;

			if ( is_ajax() ) {
					ob_clean();

					echo json_encode( array(
								'result'   => 'success',
								'redirect' => WGM_Helper::get_check_url()
								) );
					exit;
			} else {
				wp_safe_redirect( WGM_Helper::get_check_url() );
				exit;
			}

		} else {
			if( isset( $_SESSION[ 'first_checkout_post_array' ] ) )
				unset( $_SESSION[ 'first_checkout_post_array' ] );
		}
	}


	/**
	* add the german disclaimer to checkout
	* @access public
	* @static
	* @author
	* @return string review order
	* @hook woocommerce_review_order_after_submit
	*/
	public static function add_review_order() {

		$review_order = '';
		if( get_option( WGM_Helper::get_wgm_option(  'woocommerce_de_show_Widerrufsbelehrung' ) ) == 'on' ) {
			$review_order = '
				<p class="form-row terms">
					<label for="widerruf" class="checkbox">'
						. __( 'Gelesen und zur Kenntnis genommen: ', Woocommerce_German_Market::get_textdomain() )
						. ' <a href="' . get_permalink( get_option( WGM_Helper::get_wgm_option( 'widerruf' ) ) ) . '" target="_blank">' .
							__( 'Widerrufsbelehrung', Woocommerce_German_Market::get_textdomain() ) .
						'</a>
					</label>
					<input type="checkbox" class="input-checkbox" name="widerruf" id="widerruf" />
				</p>';
		}

		// set update_totals, to generate the second checkout site
		if ( ! isset( $_SESSION[ 'woocommerce_de_in_first_checkout' ] ) ) {
			echo '<input type="hidden" name="woocommerce_checkout_update_totals" value="1" />';
			$_SESSION[ 'woocommerce_de_in_first_checkout' ] = TRUE;
		}

		echo apply_filters( 'woocommerce_de_review_order_after_submit' , $review_order );
	}


	/**
	* Show shipping time and shipping costs
	* @access public
	* @static
	* @author jj, ap
	* @return void
	* @param bool $show_delivery_time default TRUE
	* @hook woocommerce_single_product_summary
	*/
	public static function add_template_loop_shop ( $show_delivery_time = TRUE ) {
		// fix wp problem, add_filter passes empty string as first argument as default


		if( '' === $show_delivery_time )
			$show_delivery_time = TRUE;

		$data = get_post_meta( get_the_ID(), '_lieferzeit', TRUE );
		$data_delivery_time = get_post_meta( get_the_ID(), '_no_delivery_time_string', TRUE );


		$lieferzeit_string_array = WGM_Defaults::get_lieferzeit_strings();

		// If data = default value or "use the default" or is not set
		if ( (int) $data == -1 || empty( $data ) )
			$lieferzeit = get_option( WGM_Helper::get_wgm_option( 'global_lieferzeit' ) );
		else
			$lieferzeit = $data;
		
		$deliverytime_term = get_term( $lieferzeit, 'product_delivery_times' );
			

		if( is_wp_error( $deliverytime_term ) || ! isset( $deliverytime_term ) )
			$lieferzeit_string = __( 'Keine Angabe', Woocommerce_German_Market::get_textdomain() );
		else
			$lieferzeit_string = $deliverytime_term->name;

		if( $lieferzeit_string == __( 'Nutze den Standard', Woocommerce_German_Market::get_textdomain() ) ) {
			$deliverytime_term = get_term( get_option( WGM_Helper::get_wgm_option( 'global_lieferzeit' ) ), 'product_delivery_times' );
			$lieferzeit_string = $deliverytime_term->name;
		}


		if( $show_delivery_time || ! empty( $data_delivery_time ) && get_option( WGM_Helper::get_wgm_option( 'woocommerce_de_show_delivery_time_overview' ) ) == 'on' ) {
			?>
			<div class="shipping_de shipping_de_string">
				<small>
					<?php if( $show_delivery_time || ! empty( $data_delivery_time ) ) : 

						$lieferzeit_output = apply_filters( 'wgm_deliverytime_loop', __( 'Lieferzeit:', Woocommerce_German_Market::get_textdomain() ) . ' ' . $lieferzeit_string,  $lieferzeit_string );
					?>
						<span><?php echo $lieferzeit_output ?></span>

					<?php endif; ?>
				</small>
			</div>
		<?php
		}
	} // end function


	/**
	*  add shipping costs and and discalmer to cart before the buttons
	* @access public
	* @static
	* @author jj
	* @return void
	* @hook woocommerce_widget_shopping_cart_before_buttons
	*/
	public static function add_shopping_cart() {

		if( get_option( WGM_Helper::get_wgm_option( 'woocommerce_de_disclaimer_cart' ) ) == 'off' )
			return;
		?>

		<p class="jde_hint">
			<?php echo WGM_Template::disclaimer_line(); ?>
		</p>
		<?php
	}


	/**
	* add shipping costs and and discalmer to cart
	* @access public
	* @static
	* @author jj
	* @return void
	* @hook woocommerce_cart_contents
	*/
	public static function add_shop_table_cart() {

		if( get_option( WGM_Helper::get_wgm_option( 'woocommerce_de_disclaimer_cart' ) ) == 'off' )
			return;

		?>
		<tr class="jde_hint">
			<td colspan="<?php echo apply_filters( 'wgm_colspan_add_shop_table_cart', 7 ); ?>" class="actions">
				<?php echo WGM_Template::disclaimer_line(); ?>
			</td>
		</tr>
		<?php
	}


	/**
	* admin field string template
	*
	* @access public
	* @static
	* @param string $value
	* @return void
	* @hook woocommerce_admin_field_string
	*/
	public static function add_admin_field_string_template( $value ) {
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<?php echo $value[ 'name' ]; ?>
			</th>
			<td class="forminp">
				<?php echo esc_attr( $value[ 'desc' ] ); ?>
			</td>
		</tr>
		<?php
	}


	/**
	* print including tax for products
	*
	* @access public
	* @static
	* @author jj
	* @param class $product
	* @return void
	*/
	public static function text_including_tax( $product ) {

		$tax_print_include_enabled = apply_filters( 'woocommerce_de_print_including_tax', TRUE );


		if ( $product->is_taxable()) :
			$_tax = new WC_Tax();
			$tax_rates = $_tax->get_shop_base_rate( $product->tax_class );
			$include_string = (  'yes' == get_option( 'woocommerce_prices_include_tax' ) ) ? __( 'inkl.', Woocommerce_German_Market::get_textdomain() ) : __( 'zzgl.', Woocommerce_German_Market::get_textdomain() );
			?>
			<span class="woocommerce-de_price_taxrate">
				<?php
				foreach( $tax_rates as $rate )

					if( get_option( WGM_Helper::get_wgm_option( 'woocommerce_de_kleinunternehmerregelung' ) ) == 'on' ) {
						echo apply_filters( 'woocommerce_de_small_business_regulation_text', __( 'Umsatzsteuerbefreit nach §19 UstG', Woocommerce_German_Market::get_textdomain() ) );
					} else {
						if ( $tax_print_include_enabled ) {
							printf( '%s %s%% %s', $include_string, round( $rate[ 'rate' ], 1 ), $rate[ 'label' ] );
						} else {
							_e( 'MwSt. entfällt', Woocommerce_German_Market::get_textdomain() );
						}
					}
				?>
			</span>
			<?php
		endif;
	}


	/**
	* Outputs readonly checkout fields
	*
	* @author jj
	* @access public
	* @static
	* @param string key key of field
	* @param array args	contains a list of args for showing the field, merged with defaults (below
	* @return void
	*/
	public static function checkout_readonly_field( $key, $args ) {

		global $woocommerce;
		$woocommerce_checkout = $woocommerce->checkout();

		$defaults = array(
			'type' => 'input',
			'name' => '',
			'label' => '',
			'placeholder' => '',
			'required' => false,
			'class' => array(),
			'label_class' => array(),
			'rel' => '',
			'return' => TRUE
		);

		$args = wp_parse_args( $args, $defaults );

		$field = '';

		if( ! isset( $_SESSION[ 'first_checkout_post_array' ][ $key ] ) )
			return FALSE;

		$value =  $_SESSION[ 'first_checkout_post_array' ][ $key ];

		if( empty( $value ) )
			return FALSE;

		switch ( $args[ 'type' ] ) {
			case "textarea" :
				$field = $args[ 'label' ] . '<span class="wgm-break"></span>' . $value;
				$hidden = sprintf( '<input type="hidden" name="%s" value="%s" /> ', $key, $value );
			break;
			default :
				$field = '<tr><td>'. $args[ 'label' ] . '</td><td>' . $value  . '</td></tr>';
				$hidden = sprintf( '<input type="hidden" name="%s" value="%s" /> ', $key, $value );
			break;
		}

		if ( $args[ 'return' ] )
			return array( $field, $hidden );
		else
			printf( '%s,%s\n', $field, $hidden );
	}

	/**
	 * Get last checkout Hints
	 *
	 * Likely not used anymore!
	 * @static
	 * @author jj
	 * @return string
	 */
	public static function get_last_checkout_hints( ){
		return WGM_Template::checkout_readonly_field( 'woocommerce_de_last_checkout_hints' );
	}



	/**
	* Set a field of an fields array to a specific value, by passing the fieldtype
	*
	* @static
	* @param array $fields
	* @param string $field_type
	* @param string $changefield
	* @param string $value
	* @return array $fields
	*
	*/
	public static function set_field_type( $fields, $field_type, $changefield, $value = FALSE ) {

		foreach ( $fields as $key => $field ) {
			if ( isset( $field[ 'type' ] ) && $field_type == $field[ 'type' ] )
				$fields[ $key ][ $changefield ] = $value;
		}

		return $fields;
	}


	/**
	* returns shipping costs and withdraw disclaimer as html with links
	*
	* @access public
	* @static
	* @author jj
	* @uses get_option
	* @return string html
	*/
	public static function disclaimer_line(){
		$return = '';

		$versandkosten  = '<a class="versandkosten" href="' . get_permalink( get_option( WGM_Helper::get_wgm_option( 'versandkosten' ) ) ) . '">' . __( 'Versandkosten', Woocommerce_German_Market::get_textdomain() ) . '</a>';
		$widerrufsrecht = '<a class="widerruf" href="' . get_permalink( get_option( WGM_Helper::get_wgm_option( 'widerruf' ) ) ) . '">' . __( 'Widerrufsrecht', Woocommerce_German_Market::get_textdomain() ) . '</a>';

		$return.= __( 'Hier finden Sie' , Woocommerce_German_Market::get_textdomain() );

		if ( 'on' === get_option(  WGM_Helper::get_wgm_option( 'woocommerce_de_show_shipping_fee_overview' ) ) )
			$return .= sprintf( ' ' . __( 'Informationen zu den %1$s und', Woocommerce_German_Market::get_textdomain() ), $versandkosten );

		$return .= sprintf( ' ' . __( 'Einzelheiten zum %1$s', Woocommerce_German_Market::get_textdomain() ), $widerrufsrecht );

		return $return;
	}


	/**
	* get string from texttemplate directory, if filename is given, else it returns the parameter
	*
	* @access	public
	* @static
	* @author	et
	* @param	string name template filename
	* @param	array params
	* @return	void
	*/
	public static function include_template( $name, $args = array() ) {

		if ( ! empty( $args ) && is_array($args) )
			extract( $args );

		$path = dirname( __FILE__ ) . '/../templates/' . $name;

		if ( file_exists( $path )  )
			include( $path );
	}


	/**
	* get string from texttemplate directory, if filename is given, else it returns the parameter
	*
	* @access public
	* @param string name template filename
	* @return string
	*/
	public static function get_text_template( $name ) {

		$path = dirname( __FILE__ ) . '/../text-templates/' . $name;
		if ( file_exists( $path )  ) {
			return file_get_contents( $path );
		} else {
			return $name;
		}
	}

	/**
	 * Adds payment information to the mails
	 * @param WC_Order $order The Woocommerce order object
	 * @access public
	 * @author ap
	 * @since 2.0
	 * @hook woocommerce_email_after_order_table
	 */
	public static function add_paymentmethod_to_mails( $order ){
		echo  '<h3>' . __( 'Zahlungsart', Woocommerce_German_Market::get_textdomain() ) . ': ' . $order->payment_method_title . '</h3>';
	}


	/**
	* adds the product short description to the checkout
	*
	* @access public
	* @static
	* @param string $title, obj $item
	* @return string title
	* @author ap
	* @hook woocommerce_checkout_item_quantity
	*/
	public static function add_product_short_desc_to_checkout_title( $title, $item ){
		if ( get_option( 'woocommerce_de_show_show_short_desc' ) == 'on' ) {
			$title .= '<span class="wgm-break"></span> <span class="product-desc">'  . $item[ 'data' ]->post->post_excerpt . '</span>';
			return $title;
		} else {
			return false;
		}
	}

	/**
	* adds the product short description to the oder listing
	*
	* @access public
	* @static
	* @param string $title, obj $item
	* @return string title
	* @author ap
	* @hook woocommerce_checkout_item_quantity
	*/
	public static function add_product_short_desc_to_order_title( $title, $item ){
		if ( get_option( 'woocommerce_de_show_show_short_desc' ) == 'on' ) {
			$_product = get_product( $item[ 'item_meta'][ '_product_id' ][0] );
			$title .= '<span class="wgm-break"></span> <span class="product-desc">'  . $_product->post->post_excerpt . '</span>';
			return $title;
		} else {
			return false;
		}
	}


	/**
	* add the extra cost for non eu countries to the product description
	*
	* @access public
	* @static
	* @return void
	* @author ap
	* @hook woocommerce_single_product_summary
	*/
	public static function show_extra_costs_eu(){
		if ( get_option( WGM_Helper::get_wgm_option( 'woocommerce_de_show_extra_cost_hint_eu' ) ) === 'on' ) {
			_e( '<small>Bei Lieferungen in das Nicht-EU-Ausland fallen zusätzliche Zölle, Steuern und Gebühren an</small>', Woocommerce_German_Market::get_textdomain() );
		}
	}

	/**
	* hides the shipping fee if the free shipping limit is reached
	*
	* @access public
	* @static
	* @return void
	* @author ap
	* @hook woocommerce_available_shipping_methods
	*/
	public static function hide_standard_shipping_when_free_is_available( $available_methods ) {
		if( isset( $available_methods['free_shipping'] ) AND isset( $available_methods['flat_rate'] ) ) {
			unset( $available_methods['flat_rate'] );
		}

		return $available_methods;
	}

	/**
	* removes the deliverytime metabox from the products edit view
	*
	* @access public
	* @static
	* @return void
	* @author ap
	* @hook admin_menu
	*/
	public static function remove_lieferzeit_taxonomy_metabox(){
		remove_meta_box( 'product_delivery_timesdiv', 'product', 'side' );
	}


	public static function kur_notice() {
		if( get_option( WGM_Helper::get_wgm_option( 'woocommerce_de_kleinunternehmerregelung' ) ) == 'on' ){
			echo '<tr><div class="wgm-kur-notice">';
			echo apply_filters( 'woocommerce_de_small_business_regulation_text', __( 'Umsatzsteuerbefreit nach §19 UstG', Woocommerce_German_Market::get_textdomain() ) );
			echo '</div>';
		}
	}

	public static function kur_review_order_notice() {
		if( get_option( WGM_Helper::get_wgm_option( 'woocommerce_de_kleinunternehmerregelung' ) ) == 'on' ){
			echo '<tr>
					<td></td>
					<td>
					<div class="wgm-kur-notice-review">';
			echo apply_filters( 'woocommerce_de_small_business_regulation_text', __( 'Umsatzsteuerbefreit nach §19 UstG', Woocommerce_German_Market::get_textdomain() ) );
			echo '</div>
					</td>
					</tr>';
		}
	}

	public static function kur_review_order_item( $total_rows ){
		if( get_option( WGM_Helper::get_wgm_option( 'woocommerce_de_kleinunternehmerregelung' ) ) == 'on' ){
			$total_rows['order_total']['value'] .= ' <small>' .
				apply_filters( 'woocommerce_de_small_business_regulation_text', __( 'Umsatzsteuerbefreit nach §19 UstG', Woocommerce_German_Market::get_textdomain() ) ) .
				'</small>';
		}

		return $total_rows;
	}
}

?>