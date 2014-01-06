<?php
# Type: Kayak 33, Paddle 55, Zubehoere 54 (category id)
$hoang_type_order = array ('33' => 1, '55' => 2, '54' => 3);
# Brand: Emotion her-emotion, Santa Cruz santa-cruz, Lifetime lifetime (attribute slug)
$hoang_brand_order = array ('her-emotion' => 1 ,'santa-cruz' => 2, 'lifetime' => 3);
$hoang_custom_order = array(
							'type' 	=> $hoang_type_order,
							'brand'	=> $hoang_brand_order
					);
add_option ('hoang_custom_order', $hoang_custom_order);

function check_cats($slug) {
	$check_cats = array('kayaks', 'kayaks_zubehoer', 'paddle_boards');
	return in_array($slug, $check_cats);
}
function hoang_reupdate_post() {
	$custom_order = get_option("hoang_custom_order" );
	$args = array (
		'post_type' 	=> 'product',
		'post_status'	=> 'publish',
		'posts_per_page'=> -1
		);
	$products = get_posts($args);
	foreach ($products as $product) {
		$cats = get_the_terms($product->ID, 'product_cat');
		if (!$cats) continue;
		$cat_id = 0;
		$brand_slug = 0;
		$lenght = 0;
		foreach ($cats as $cat) {
			if (check_cats($cat->slug)) {$cat_id = $cat->term_id; break; }
		}
		if ($brand = get_the_terms($product->ID, 'pa_hersteller', true)) 
			$brand_slug = array_shift($brand)->slug;
		if ($lange = get_the_terms($product->ID, 'pa_laenge', true)) 
			$lenght = explode('-', array_shift($lange)->slug)[0];
		if ($brand_slug!='') $brand_slug = $custom_order['brand'][$brand_slug];
		else $brand_slug = 1000;
		if ($cat_id>0) $cat_id = $custom_order['type'][$cat_id];
		else $cat_id = 1000;
		if ($lenght==0) $lenght = 1000;
		update_post_meta($product->ID, "pa_custom_sort", $cat_id*$brand_slug*$lenght);
	}
}
add_action('admin_menu', 'hoang_add_custom_sort_menu');
function hoang_add_custom_sort_menu() {
	add_submenu_page( 'edit.php?post_type=product','Custom Sort','Custom Sort', 'manage_product_terms', 'hoang_custom_sort_page', 'hoang_show_custom_sort_menu');
}
function hoang_show_custom_sort_menu() {
	$submiturl = str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);
	$hoang_custom_order = get_option( "hoang_custom_order" );
	if ($_POST!=null) {
		if ($_POST['reset']) hoang_reupdate_post();
		update_option('hoang_custom_order',array(
												'type' 	=> $_POST['type'],
												'brand'	=> $_POST['brand']
		));
	}
	echo "<div class='wrap'>";
	echo '	<h2>Set Custom Sort</h2>';
	echo "<form action='".$submiturl."' method='post'>";
	echo "	<h3 class='title'>Type</h3>";
	echo "	<table class='form-table'>";
	$product_cats = get_terms('product_cat', 'hide_empty=0');
	foreach ($product_cats as $term) {
		if (check_cats($term->slug)) {
			echo '<tr valign="top">';
			echo '	<th scope="row"><label>'.$term->name."</label></th>";
			echo '	<td><input type="text" name="type['.$term->term_id.']" value="'.$hoang_custom_order["type"][$term->term_id].'" class="regular-text"></td>';
			echo '</tr>';
		}
	}
	echo "	</table>";

	echo "	<h3 class='title'>Marke</h3>";
	echo "	<table class='form-table'>";
	$product_cats = get_terms('pa_hersteller', 'hide_empty=0');
	foreach ($product_cats as $term) {
		echo '<tr valign="top">';
		echo '	<th scope="row"><label>'.$term->name."</label></th>";
		echo '	<td><input type="text" name="brand['.$term->slug.']" value="'.$hoang_custom_order["brand"][$term->slug].'"  class="regular-text"></td>';
		echo '</tr>';
	}
	echo "	</table>";
	echo "	<input type='radio' name='reset' value='true'>Re-update all Posts<br>";
	echo "	<p class='submit'>";
	echo " 		<input type='submit' class='button button-primary' value='Save Changes'>";
	echo "	</p>";
	echo "</form>";
	echo "</div>";
}
	/************* Add sorting by attributes **************/
 
/**
 *  Defines the criteria for sorting with options defined in the method below
 */
function avia_woocommerce_overwrite_catalog_ordering() {}
add_action('woocommerce_get_catalog_ordering_args', 'custom_woocommerce_get_catalog_ordering_args', 20);
 
function custom_woocommerce_get_catalog_ordering_args( $args ) {

    $args['order'] = 'asc';
    $args['meta_key'] = 'pa_custom_sort';
    $args['orderby'] = 'meta_value_num';
    return $args;
}
 
/**
 *  Adds the sorting options to dropdown list .. The logic/criteria is in the method above
 */
add_filter( 'woocommerce_default_catalog_orderby_options', 'custom_woocommerce_catalog_orderby' );
add_filter( 'woocommerce_catalog_orderby', 'custom_woocommerce_catalog_orderby' );
function custom_woocommerce_catalog_orderby( $sortby ) {
    $sortby['pa_custom_sort'] = 'Custom Sort';
    return $sortby;
}
 
/**
 *  Save custom attributes as post's meta data as well so that we can use in sorting and searching
 */
add_action('woocommerce_process_product_meta', 'save_woocommerce_attr_to_meta', 10, 2);
function save_woocommerce_attr_to_meta( $post_id , $post) {
	$hoang_custom_order = get_option('hoang_custom_order');
        // Get the attribute_names .. For each element get the index and the name of the attribute
        // Then use the index to get the corresponding submitted value from the attribute_values array.
    $product_cat = $_REQUEST['tax_input']['product_cat'];
    $product_cat = $product_cat[0]!=0 ? $product_cat[0] : $product_cat[1];
    $type_order = $hoang_custom_order['type'][$product_cat];
    $lenght = 0;
    $brand_order = 0;
   foreach( $_REQUEST['attribute_names'] as $index => $value ) {
    	if ($value=="pa_laenge") {
    		$lenght = explode('-', $_REQUEST['attribute_values'][$index][0])[0]; // remove cm from att slug
    	}
    	elseif ($value=="pa_hersteller") {
    		$brand_order = $hoang_custom_order['brand'][$_REQUEST['attribute_values'][$index][0]];
    	}
    }  
    if ($type_order==0) $type_order = 1000;		#dummy value
    if ($brand_order==0) $brand_order = 1000;		#dummy value
    if ($lenght==0) $lenght = 1000;		#dummy value
    update_post_meta( $post_id, "pa_custom_sort", $type_order*$brand_order*$lenght);
}
/************ End of Sorting ***************************/
?>