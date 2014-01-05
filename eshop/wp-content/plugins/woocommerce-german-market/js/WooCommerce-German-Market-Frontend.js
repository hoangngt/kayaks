jQuery.noConflict();

( function( $ ) {

	woocommerce_de = {

		init: function () {

			this.remove_totals();
			this.register_payment_update();
			this.remove_first_checkout_button();
		},

		remove_totals: function () {

			if ( woocommerce_remove_updated_totals == 1 )
				$( '.woocommerce_message' ).remove();
		},

		register_payment_update: function () {

			$( "input[name='payment_method']" ).on( 'change', function () {
				$( 'body' ).trigger( 'update_checkout' );
			});
		},

		remove_first_checkout_button: function () {

			$( '.checkout-button' ).each( function( index, value ) {

				var $value = $( value );

				if ( ! $value.hasClass( 'second-checkout-button' ) )
					$value.hide();
			});
		}
	};

	$( document ).ready( function( $ ) { woocommerce_de.init(); } );

} )( jQuery );