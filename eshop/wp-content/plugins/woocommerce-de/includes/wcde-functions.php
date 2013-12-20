<?php
/**
 * Global needed helper functions.
 *
 * @package    WooCommerce German (de_DE)
 * @subpackage Helper functions
 * @author     David Decker - DECKERWEB
 * @copyright  Copyright (c) 2013, David Decker - DECKERWEB
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL-2.0+
 * @link       http://genesisthemes.de/en/wp-plugins/woocommerce-de/
 * @link       http://deckerweb.de/twitter
 *
 * @since      3.0.20
 */

/**
 * Exit if accessed directly
 *
 * @since 3.0.20
 */
if ( ! defined( 'WPINC' ) ) {
	exit( 'Sorry, you are not allowed to access this file directly.' );
}


/**
 * Helper function to determine if in a German locale based environment.
 *
 * @since 3.0.20
 *
 * @uses  get_option()
 * @uses  get_site_option()
 * @uses  get_locale()
 * @uses  ICL_LANGUAGE_CODE Constant of WPML premium plugin, if defined.
 *
 * @return bool If German-based locale, return TRUE, otherwise FALSE.
 */
function wcde_is_german() {

	/** Set array of German-based locale codes */
	$german_locales = array( 'de_DE', 'de_AT', 'de_CH', 'de_LU', 'gsw' );

	/** Get possible WPLANG option values */
	$option_wplang      = get_option( 'WPLANG' );
	$site_option_wplang = get_site_option( 'WPLANG' );

	/**
	 * Check for German-based environment/ context in locale/ WPLANG setting
	 *    and/ or within WPML (premium plugin).
	 *
	 * NOTE: This is very important for multilingual sites and/or Multisite
	 *       installs.
	 */
	if ( ( in_array( get_locale(), $german_locales )
					|| ( $option_wplang && in_array( $option_wplang, $german_locales ) )
					|| ( $site_option_wplang && in_array( $site_option_wplang, $german_locales ) )
			)
			|| ( defined( 'ICL_LANGUAGE_CODE' ) && ( ICL_LANGUAGE_CODE == 'de' ) )
	) {

		/** Yes, we are in German-based environmet */
		return TRUE;

	} else {

		/** Non-German! */
		return FALSE;

	}  // end if

}  // end of function wcde_is_german