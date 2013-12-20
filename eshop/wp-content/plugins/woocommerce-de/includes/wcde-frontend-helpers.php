<?php
/**
 * Helper functions for the frontend - text strings.
 *
 * @package    WooCommerce German (de_DE)
 * @subpackage Frontend
 * @author     David Decker - DECKERWEB
 * @copyright  Copyright (c) 2012-2013, David Decker - DECKERWEB
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL-2.0+
 * @link       http://genesisthemes.de/en/wp-plugins/woocommerce-de/
 * @link       http://deckerweb.de/twitter
 *
 * @since      2.1.0
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
 * Helper function for returning order button string: "Zahlungspflichtig bestellen"
 *
 * @since  2.1.0
 *
 * @uses   wcde_is_german()
 *
 * @return string Order button string 'Zahlungspflichtig bestellen'.
 */
function __wcde_order_button_zahlungspflichtig_bestellen() {

	/** Only in German locale based environment */
	if ( ! wcde_is_german() ) {

		return;

	}  // end if

	/** Button text: */
	return 'Zahlungspflichtig bestellen';

}  // end function


/**
 * Helper function for returning order button string: "Zahlungspflichtigen Vertrag schließen"
 *
 * @since  2.1.0
 *
 * @uses   wcde_is_german()
 *
 * @return string Order button string 'Zahlungspflichtigen Vertrag schließen'.
 */
function __wcde_order_button_zahlungspflichtigen_vertrag() {

	/** Only in German locale based environment */
	if ( ! wcde_is_german() ) {

		return;

	}  // end if

	/** Button text: */
	return 'Zahlungspflichtigen Vertrag schließen';

}  // end function


/**
 * Helper function for returning order button string: "Kaufen"
 *
 * @since  2.1.0
 *
 * @uses   wcde_is_german()
 *
 * @return string Order button string 'Kaufen'.
 */
function __wcde_order_button_kaufen() {

	/** Only in German locale based environment */
	if ( ! wcde_is_german() ) {

		return;

	}  // end if

	/** Button text: */
	return 'Kaufen';
	
}  // end function