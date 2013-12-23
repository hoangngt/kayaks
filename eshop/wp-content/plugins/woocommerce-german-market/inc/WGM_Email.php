<?php
/**
 * Email Functions
 * 
 * @author jj, ap
 */
Class WGM_Email {

/**
	* Add legal Text to emails
	*
	* @author jj, et
	* @access public
	* @static
	* @uses get_option, get_post, do_shortcode
	* @return void
	*
	*/
	public static function email_de_footer() {

		// Impressum
		if ( 'on' === get_option( WGM_Helper::get_wgm_option( 'woocommerce_de_append_imprint_to_mail' ) ) ) {
			if( 'yes' == get_option( WGM_Helper::get_wgm_option( 'woocommerce_de_use_backend_footer_text_for_imprint_enabled' ) ) ) {
				$imprint_text = get_option( 'woocommerce_email_footer_text' );
			} else {
				$imprint_page_id = get_option( 'woocommerce_impressum_page_id' );
				$imprint_page = get_post( $imprint_page_id );
				$imprint_text = $imprint_page->post_content;
			}

			WGM_Email::the_mail_footer_section(
				__( 'Impressum', Woocommerce_German_Market::get_textdomain() ),
				$imprint_text
			);
		}

		// IF we're not sending a order mail don't include the following
		if( ! defined( 'WGM_MAIL' ) ) return;


		// Widerrufsrecht
		if ( 'on' === get_option( WGM_Helper::get_wgm_option( 'woocommerce_de_append_withdraw_to_mail' ) ) ) {
			$withdrawal_page_id = get_option( 'woocommerce_widerruf_page_id' );
			$withdrawal_page	= get_post( $withdrawal_page_id );

			WGM_Email::the_mail_footer_section(
				__( 'Widerrufsrecht', Woocommerce_German_Market::get_textdomain() ),
				$withdrawal_page->post_content
			);
		}

		// Allgemeine Geschäftsbedingungen
		if ( 'on' === get_option( WGM_Helper::get_wgm_option( 'woocommerce_de_append_terms_to_mail' ) ) ) {
			$terms_page_id = get_option( 'woocommerce_terms_page_id' );
			$terms_page	= get_post( $terms_page_id );

			WGM_Email::the_mail_footer_section(
				__( 'Allgemeine Geschäftsbedingungen', Woocommerce_German_Market::get_textdomain() ),
				$terms_page->post_content
			);
		}
	}

	/**
	 * Print Mail Footer Section HTML
	 * @param  string $title
	 * @param  string $content
	 * @return void
	 */
	private static function the_mail_footer_section( $title, $content ) {
		?>
		<div style="<?php echo apply_filters( 'wgm_email_footer_style', 'float:left; width: 100%;' ); ?>">
			<h3><?php echo $title; ?></h3>
			<p><?php echo apply_filters( 'the_content', $content ); ?></p>
		</div>
		<?php
	}
}
?>