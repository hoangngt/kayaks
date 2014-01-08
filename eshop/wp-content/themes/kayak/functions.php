<?php
/* checkout page */

remove_filter( 'woocommerce_checkout_item_subtotal', array( 'WGM_Template', 'add_mwst_rate_to_product_item' ), 10 ,3 );
add_filter( 'woocommerce_checkout_item_subtotal', 'hoang_checkout', 10, 3 );
function hoang_checkout( $amount, $item, $item_id  ) {
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


		$tax_string = woocommerce_price( $item[ 'line_tax' ] );


		$template = '%s <span class="product-tax">'.__( '(Enthält %s %s)', Woocommerce_German_Market::get_textdomain() ).' </span>';

		$item = sprintf( $template, $amount, $tax_string, $tax['label'] );

		return $item;
}
/* redirect b2b kunden to b2b Katalog after login */
add_filter('woocommerce_login_redirect', 'hoang_login_redirect', 1, 2);

function hoang_login_redirect( $redirect_to, $user ) {
	foreach ($user->roles as $role) {
		if ($role=='B2B Kunde') $redirect_to = home_url("/shop-b2b" );
	}
    return $redirect_to;
}
function hoang_include_js($scriptname) {
	$script_link = get_stylesheet_directory_uri().'/js/'.$scriptname.'.js';
	wp_enqueue_script($scriptname, $script_link ); 
}
function hoang_added_to_cart_message ($message) {
	global $post;
	$added_text = "Produkt wurde Ihrem Warenkorb erfolgreich hinzugefügt.";
	// Output success messages
	if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) :

		$return_to 	= apply_filters( 'woocommerce_continue_shopping_redirect', wp_get_referer() ? wp_get_referer() : home_url() );

		$message 	= sprintf('<a href="%s" class="button">%s</a> %s', $return_to, __( 'Continue Shopping &rarr;', 'woocommerce' ), $added_text );

	else :

		$message 	= sprintf('<a href="%s" class="button">%s</a> %s', get_permalink( woocommerce_get_page_id( 'cart' ) ), __( 'View Cart &rarr;', 'woocommerce' ), $added_text );

	endif;
	return $message ;
}
add_filter ('woocommerce_add_to_cart_message', 'hoang_added_to_cart_message', 999);
// overide MegaMenu of avia
class avia_megamenu {}			

// include all custom shortcodes (if any)
add_filter('avia_load_shortcodes', 'avia_include_shortcode_template', 15, 1);
function avia_include_shortcode_template($paths)
{
	$template_url = get_stylesheet_directory();
    	array_unshift($paths, $template_url.'/shortcodes/avia/');

	return $paths;
}

/* Include all custom shortcodes */
require_once('shortcodes/hoang_b2b_login.php');
require_once('shortcodes/hoang_b2b_section.php');
require_once('shortcodes/hoang_woo_sub_cat.php');
require_once('shortcodes/hoang_product_gallery.php');

##################################################################
# helper functions for B2B Page
##################################################################

require_once ("includes/B2B_product.php");

##################################################################
# helper functions for Shop page
##################################################################

require_once('includes/hoang_show_filter_options.php');
require_once('includes/product_sorting.php');

##################################################################
# helper functions for product pages
##################################################################

/* hide Lieferzeit on product pages */
remove_filter( 'woocommerce_single_product_summary',array( 'WGM_Template', 'add_template_loop_shop' ), 11 ); 
/* Change default product gallery to Enfold gallery */
require_once('includes/product_gallery.php');
/* Add "Technische Daten - Tab" to product page */
require_once('includes/technik_tab.php');

/* Add Color-Swatches for Variable products */
require_once('includes/variable_product.php');
##################################################################
# helper functions for Homepage
##################################################################



##################################################################
# helper functions for tabs rename in single product
##################################################################
add_filter( 'woocommerce_product_tabs', 'agentwp_woo_rename_reviews_tab', 98);
function agentwp_woo_rename_reviews_tab($tabs) {
$tabs['reviews']['title'] = 'Bewertungen';
return $tabs;
}