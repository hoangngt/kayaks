<?php
/**
 * Deprecated functions & classes.
 *
 * @package    WooCommerce German (de_DE)
 * @subpackage Deprecated
 * @author     David Decker - DECKERWEB
 * @copyright  Copyright (c) 2012-2013, David Decker - DECKERWEB
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL-2.0+
 * @link       http://genesisthemes.de/en/wp-plugins/woocommerce-de/
 * @link       http://deckerweb.de/twitter
 *
 * @since      1.0.0
 */

/**
 * Exit if accessed directly
 *
 * @since 3.0.0
 */
if ( ! defined( 'WPINC' ) ) {
	exit( 'Sorry, you are not allowed to access this file directly.' );
}


/**
 * Deprecated main plugin class.
 *   (only used up to plugin version 2.5.3)
 *
 * @since 1.0.0
 */
class WooCommerce_de_DE {

	/**
	 * Bootstrap
	 *
	 * @since 1.0.0
	 */
	public static function bootstrap() {

		return array();

	}

}  // end class


/**
 * Deprecated.
 * Search for 'I have read and accept the' Gettext string and add changed text.
 *
 * @since  3.0.1
 *
 * @param  string $translation
 * @param  string $text
 * @param  string $domain
 */
function ddw_wcde_gettext_read_accept_string( $translation, $text, $domain ) {

	_deprecated_function( __FUNCTION__, '3.0.20' );

}  // end function


/**
 * Deprecated.
 * Search for 'terms &amp; conditions' Gettext string and add changed text.
 *
 * @since  3.0.1
 *
 * @param  string $translation
 * @param  string $text
 * @param  string $domain
 */
function ddw_wcde_gettext_terms_string( $translation, $text, $domain ) {

	_deprecated_function( __FUNCTION__, '3.0.20' );

}  // end function