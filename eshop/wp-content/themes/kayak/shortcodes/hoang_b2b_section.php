<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
add_shortcode("hoang_b2b_section", "hoang_b2b_section");
function hoang_b2b_section($atts) {
    extract(shortcode_atts(array(  
        'columns' => '2',
        'items' => '10'
    ), $atts));
    if (is_b2b()) {
    	echo "<div id='b2b_shop_wrapper'>";
    	echo do_shortcode("[av_productgrid columns='".$columns."' items='".$items."' offset='0' sort='dropdown' paginate='yes']");
    	echo "</div>";
    }
    else echo "Dieses Bereich ist nur für Geschäftskunden sichtbar";
}