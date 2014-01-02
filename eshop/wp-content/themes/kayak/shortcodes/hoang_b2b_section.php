<?php
add_shortcode("hoang_b2b_section", "hoang_b2b_section");
function hoang_b2b_section($atts) {
    extract(shortcode_atts(array(  
        'columns' => '2',
        'items' => '10'
    ), $atts));
    if (is_b2b()) {
       echo do_shortcode("[av_productgrid columns='".$columns."2' items='".$items."' offset='0' sort='dropdown' paginate='yes']");
    }
    else echo "Dieses Bereich ist nur für Geschäftskunden sichtbar";
}

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
add_action( 'pre_get_posts', 'custom_pre_get_posts_query' );
add_filter( 'product_type_selector', 'hoang_add_product_type',10,2 );
add_role("B2B Kunde", "B2B Kunde");
function hoang_add_product_type( $types ){
       $types[ 'b2b_product' ] = __( 'B2B Product' );
       return $types;
}