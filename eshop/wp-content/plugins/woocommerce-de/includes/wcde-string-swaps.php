<?php
/**
 * Helper functions for some string swapts (via $l10n global).
 *
 * @package    WooCommerce German (de_DE)
 * @subpackage String Swap
 * @author     David Decker - DECKERWEB
 * @copyright  Copyright (c) 2012-2013, David Decker - DECKERWEB
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL-2.0+
 * @link       http://genesisthemes.de/en/wp-plugins/woocommerce-de/
 * @link       http://deckerweb.de/twitter
 *
 * @since      3.0.19
 */

/**
 * Exit if accessed directly
 *
 * @since 3.0.19
 */
if ( ! defined( 'WPINC' ) ) {
	exit( 'Sorry, you are not allowed to access this file directly.' );
}


/**
 * Helper function:
 * Search for a specific translation string and add changed text.
 *    NOTE: Provides fallback to default value if option is empty.
 *          Also works for default installs ('en_US' locale).
 *
 * @since  3.0.19
 *
 * @uses   get_option()
 *
 * @param  string $option_key Option key of our own options array.
 * @param  array  $strings Array of original text strings used by WooCommerce.
 * @param  string $default_translation A fallback default translation if the
 *                                     user setting may be empty.
 *
 * @global $l10n
 *
 * @return string Changed string for display. Merged to global object $l10n/MO.
 */
function ddw_wcde_custom_strings_via_l10n_global( $option_key, $strings, $default_translation ) {

	global $l10n;

	/** Get our option (upcoming) */
	//$wcde_options = get_option( 'wcde-options' );

	/** Perform string swap for each string of our array with keys/strings */
	foreach ( (array) $strings as $string ) {

		/** Set label logic */
		$custom_label = $default_translation;

		/** Tweak/add translation/custom label within 'woocommerce' textdomain */
		if ( isset( $l10n[ 'woocommerce' ] )
				&& isset( $l10n[ 'woocommerce' ]->entries[ $string ] )
		) {

			$l10n[ 'woocommerce' ]->entries[ $string ]->translations[0] = $custom_label;

		} else {

			$mo = new MO();

			$mo->add_entry(
				array(
					'singular'     => $string,
					'translations' => array( $custom_label )
				)
			);

			if ( isset( $l10n[ 'woocommerce' ] ) ) {

				$mo->merge_with( $l10n[ 'woocommerce' ] );

			}  // end if
		 
			$l10n[ 'woocommerce' ] = &$mo;

		}  // end if

	}  // end foreach
	
}  // end of function ddw_wcde_custom_strings_via_l10n_global


add_action( 'init', 'ddw_wcde_do_string_swaps' );
/**
 * Passing an array of labels to our helper function to do string swaps.
 *    NOTE: Most of the used strings are currently not filterable by itself.
 *          So using translations global variable and merging to the "MO" object
 *          is the only way. AND, we avoid the 'gettext' filter with that, which
 *          is by intention, and a must performance-wise!
 *
 * @since 3.0.19
 *
 * @uses  wcde_is_german() To determine if in German-locale based environment.
 * @uses  ddw_wcde_custom_strings_via_l10n_global() Our own helper function, see above.
 */
function ddw_wcde_do_string_swaps() {

	/**
	 * Helper filter, allows for custom disabling of string swaps.
	 *
	 * Usage: add_filter( 'wcde_filter_do_string_swaps', '__return_false' );
	 */
	$wcde_do_string_swaps = (bool) apply_filters( 'wcde_filter_do_string_swaps', '__return_true' );

	/**
	 * Bail early if our helper filter returns false, if we are not in German
	 *    context within WPML (premium plugin), or, if no German-based locale is
	 *    to be found for 'WPLANG'.
	 *
	 * NOTE: This is very important for multilingual sites and/or Multisite
	 *       installs.
	 */
	if ( ! $wcde_do_string_swaps || ! wcde_is_german() ) {

		return;

	}  // end if


	/**
	 * New string labels: backwards compatible with our former 'gettext' approach.
	 *
	 * NOTE: Kept filter names from v3.0.0+ to ensure backwards compatibility!
	 */
	$wcde_read_accept_string = apply_filters(
		'wcde_filter_gettext_read_accept_string',
		'Bedingungen gelesen und zur Kenntnis genommen:'
	);

	$wcde_terms_string = apply_filters(
		'wcde_filter_gettext_terms_string',
		'Liefer- und Zahlungsbedingungen (AGB)'
	);


	/** Set up our array of planned string swap keys/ strings */
	$wcde_labels = array(

		/** Read/accept string */
		'read_accept_string' => array(
			'option_key'  => 'read_accept_string',
			'strings'     => array( 'I have read and accept the', 'I accept the' ),
			'translation' => esc_attr__( $wcde_read_accept_string ),
		),

		/** Terms string */
		'terms_string' => array(
			'option_key'  => 'term_string',
			'strings'     => array( 'terms &amp; conditions' ),
			'translation' => esc_attr__( $wcde_terms_string ),
		)

	);  // end of array

	/** Apply our string swapper for each string or our array */
	foreach ( $wcde_labels as $wcde_label => $label_id ) {

		/** Actually load the various new label strings for display */
		ddw_wcde_custom_strings_via_l10n_global(
			$label_id[ 'option_key' ],
			(array) $label_id[ 'strings' ],
			$label_id[ 'translation' ]
		);

	}  // end foreach

}  // end of function ddw_wcde_do_string_swaps