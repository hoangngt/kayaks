<?php
add_shortcode('hoang_b2b_login','hoang_b2b_login');
function hoang_b2b_login($atts) { 
	global $woocommerce;
	hoang_include_js("b2b_login");
	extract(shortcode_atts(array(
								'show_form_text' => 'Click here to show B2B Registration Form',
								'close_form_text' => 'Click here to close B2B Registration Form'
								), $atts));
	$login = __( 'Login', 'woocommerce' );
	$password = __( 'Password', 'woocommerce' ); 

	$nonce_field = $woocommerce->nonce_field('login', 'login');
	$username_email = __( 'Username or email', 'woocommerce' );
	$lost_password_page_id = woocommerce_get_page_id( 'lost_password' );

	if ( $lost_password_page_id )
		$lost_pass_link = esc_url( get_permalink( $lost_password_page_id ) );
	else
		$lost_pass_link = esc_url( wp_lostpassword_url( home_url() ) );
	$lost_pass = __( 'Lost Password?', 'woocommerce' );
	$output ="<h2>".$login."</h2>
		<form method='post' class='login'>
			<p class='form-row form-row-first'>
				<label for='username'>".$username_email."<span class='required'>*</span></label>
				<input type='text' class='input-text' name='username' id='username' />
			</p>
			<p class='form-row form-row-last'>
				<label for='password'>".$password."<span class='required'>*</span></label>
				<input class='input-text' type='password' name='password' id='password' />
			</p>
			<div class='clear'></div>

			<p class='form-row'>
				".$nonce_field."
				<input type='submit' class='button' name='login' value='".$login."' />
				<a class='lost_password' href='".$lost_pass_link."'>".$lost_pass."</a>
			</p>
		</form>";
	$output .= "<a href='#' id='show_registration_form'>".$show_form_text."</a>";
	$output .= "<input type=hidden id='show_form_text' value='".$show_form_text."'>";
	$output .= "<input type=hidden id='close_form_text' value='".$close_form_text."'>";
	return $output;
}
