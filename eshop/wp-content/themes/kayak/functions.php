<?php
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
add_action( 'pre_get_posts', 'custom_pre_get_posts_query' );
add_filter( 'product_type_selector', 'hoang_add_product_type',10,2 );
add_role("B2B Kunde", "B2B Kunde");
function hoang_add_product_type( $types ){
       $types[ 'b2b_product' ] = __( 'B2B Product' );
       return $types;
}

##################################################################
# helper functions to show filter menus
##################################################################
add_action( 'woocommerce_before_shop_loop', 'avia_woocommerce_frontend_search_params', 20);
include('includes/hoang_show_filter_options.php');

##################################################################
# helper functions for product pages
##################################################################

/* hide Lieferzeit on product pages */
remove_filter( 'woocommerce_single_product_summary',array( 'WGM_Template', 'add_template_loop_shop' ), 11 ); 

/* Add "Technische Daten - Tab" to product page */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
add_action( 'hoang_woo_after_single_product_summary', 'woo_add_technik_tab', 1);
add_action( 'hoang_woo_after_single_product_summary',  'avia_close_div', 3);
add_action( 'hoang_woo_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'hoang_woo_after_single_product_summary', 'woocommerce_output_related_products', 20 );
include('includes/technik_tab.php');
##################################################################
# helper functions for Homepage
##################################################################

// create a shortcode to show all Subcategories of Kayak-Cat

include('includes/hoang_woo_sub_cat.php');
