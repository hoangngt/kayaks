<?php
// change "Add to Cart"-form for Variable Product to normal form for simple Product 
// overide MegaMenu of avia
class avia_megamenu {}			

// include all custom shortcodes (if any)
add_filter('avia_load_shortcodes', 'avia_include_shortcode_template', 15, 1);
function avia_include_shortcode_template($paths)
{
	$template_url = get_stylesheet_directory();
    	array_unshift($paths, $template_url.'/shortcodes/');

	return $paths;
}

##################################################################
# helper functions for B2B Page
##################################################################

include ("includes/B2B_product.php");

##################################################################
# helper functions to show filter menus
##################################################################

include('includes/hoang_show_filter_options.php');

##################################################################
# helper functions for product pages
##################################################################

/* hide Lieferzeit on product pages */
remove_filter( 'woocommerce_single_product_summary',array( 'WGM_Template', 'add_template_loop_shop' ), 11 ); 

/* Add "Technische Daten - Tab" to product page */
include('includes/technik_tab.php');

/* Add Color-Swatches for Variable products */
include('includes/variable_product.php');
##################################################################
# helper functions for Homepage
##################################################################

// create a shortcode to show all Subcategories of Kayak-Cat

include('includes/hoang_woo_sub_cat.php');

##################################################################
# helper functions for tabs rename in single product
##################################################################
add_filter( 'woocommerce_product_tabs', 'agentwp_woo_rename_reviews_tab', 98);
function agentwp_woo_rename_reviews_tab($tabs) {
$tabs['reviews']['title'] = 'Bewertungen';
return $tabs;
}