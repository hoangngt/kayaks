<?php
add_shortcode('hoang_b2b_login','hoang_b2b_login');
function hoang_b2b_login($atts) { 
	global $woocommerce;
	hoang_include_js("b2b_login");
	extract(shortcode_atts(array(
								'show_form_text' => 'Click here to show B2B Registration Form',
								'close_form_text' => 'Click here to close B2B Registration Form'
								), $atts));
	$output = do_shortcode("[woocommerce_my_account]");
	$output .= "<a href='#' id='show_registration_form'>".$show_form_text."</a>";
	$output .= "<input type=hidden id='show_form_text' value='".$show_form_text."'>";
	$output .= "<input type=hidden id='close_form_text' value='".$close_form_text."'>";
	return $output;
}
