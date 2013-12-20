<?php
/**
 * Translations loader for: WooCommerce 2.0+ - general/ frontend only.
 *
 * @package    WooCommerce German (de_DE)
 * @subpackage Translations Loader
 * @author     David Decker - DECKERWEB
 * @copyright  Copyright (c) 2012-2013, David Decker - DECKERWEB
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL-2.0+
 * @link       http://genesisthemes.de/en/wp-plugins/woocommerce-de/
 * @link       http://deckerweb.de/twitter
 *
 * @since      3.0.0
 */

/**
 * Exit if accessed directly
 *
 * @since 3.0.0
 */
if ( ! defined( 'WPINC' ) ) {
	exit( 'Sorry, you are not allowed to access this file directly.' );
}


add_action( 'after_setup_theme', 'ddw_wcde_general_load_textdomain', 0 );
//add_action( 'woocommerce_loaded', 'ddw_wcde_general_load_textdomain', 0 );
/**
 * Load the WooCommerce 2.0+ General/ Frontend translations by DECKERWEB.
 *
 * @since  3.0.0
 *
 * @uses   ddw_wcde_woocommerce_is_informal()
 * @uses   get_locale()
 * @uses   load_textdomain()
 *
 * @return file/ strings Load custom translation files.
 */
function ddw_wcde_general_load_textdomain() {

	/** Load formal/ informal translations depending on WooCommerce settings */
	if ( ! ddw_wcde_woocommerce_is_informal() ) {

		/** Check for custom version in WP_LANG_DIR */
		if ( is_readable( WP_LANG_DIR . '/woocommerce-de/custom/woocommerce-de_DE.mo' ) ) {

			$mofile = WP_LANG_DIR . '/woocommerce-de/custom/' . apply_filters( 'wcde_woocommerce_custom_locale', 'woocommerce-' . get_locale() ) . '.mo';

		}

		/** Check for formal version - via plugin */
		else {

			$mofile = WP_PLUGIN_DIR . '/woocommerce-de/wc-pomo/sie-version/' . apply_filters( 'wcde_woocommerce_formal_plugin_locale', 'woocommerce-' . get_locale() ) . '.mo';

		}  // end-if formal custom/ plugin file check

	} elseif ( ddw_wcde_woocommerce_is_informal() ) {

		/** Check for custom version in WP_LANG_DIR */
		if ( is_readable( WP_LANG_DIR . '/woocommerce-de/custom/woocommerce-de_DE.mo' ) ) {

			$mofile = WP_LANG_DIR . '/woocommerce-de/custom/' . apply_filters( 'wcde_woocommerce_custom_locale', 'woocommerce-' . get_locale() ) . '.mo';

		}

		/** Check for informal version - via plugin */
		else {

			$mofile = WP_PLUGIN_DIR . '/woocommerce-de/wc-pomo/du-version/' . apply_filters( 'wcde_woocommerce_informal_plugin_locale', 'woocommerce-' . get_locale() ) . '.mo';

		}  // end-if informal custom/ plugin file check

	}  // end-if settings check


	/** Finally, load the translations */
	if ( file_exists( $mofile ) ) {

		return load_textdomain( 'woocommerce', $mofile );

	}  // end-if $mofile check

}  // end of function ddw_wcde_general_load_textdomain


add_action( 'after_setup_theme', 'ddw_wcde_general_load_textdomain_fixes', 0 );
//add_action( 'woocommerce_loaded', 'ddw_wcde_general_load_textdomain_fixes', 0 );
/**
 * Load the WooCommerce 2.0+ General/ Frontend translations FIXES by DECKERWEB.
 *
 * @since  3.0.1
 *
 * @uses   ddw_wcde_woocommerce_is_informal()
 * @uses   get_locale()
 * @uses   load_textdomain()
 *
 * @return file/ strings Load translation files with fixes.
 */
function ddw_wcde_general_load_textdomain_fixes() {

	/** Load formal/ informal translations depending on WooCommerce settings */
	if ( ! ddw_wcde_woocommerce_is_informal() ) {

		/** Textdomain: 'default' (none) */
		$mofile_default = WP_PLUGIN_DIR . '/woocommerce-de/wc-pomo/wc-fixes/sie-version/default-general-formal-' . get_locale() . '.mo';

	} elseif ( ddw_wcde_woocommerce_is_informal() ) {

		/** Textdomain: 'default' (none) */
		$mofile_default = WP_PLUGIN_DIR . '/woocommerce-de/wc-pomo/wc-fixes/du-version/default-general-informal-' . get_locale() . '.mo';

	}  // end-if settings check


	/** Finally, load the translations */
	if ( file_exists( $mofile_default ) ) {

		return load_textdomain( 'default', $mofile_default );

	}  // end-if $mofile_default check

}  // end of function ddw_wcde_general_load_textdomain_fixes