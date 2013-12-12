<?php
##################################################################
# helper functions to show filter menus
##################################################################

if(!function_exists('hoang_show_filter_options'))
{
	add_action( 'woocommerce_before_shop_loop', 'hoang_show_filter_options', 20);

	function hoang_show_filter_options()
	{
		global $avia_config;
		$preis_interval = array("500-800", "800-1000", "1000-1500", "1500-2000");
		parse_str($_SERVER['QUERY_STRING'], $params);
		if ($_GET['filter_farben']!='')
			$pa_farben = get_term($_GET['filter_farben'],"pa_farben")->name;
		else
			$pa_farben = __("Alle",'avia_framework');
			
		if ($_GET['filter_hersteller']!='')
			$pa_hersteller = get_term($_GET['filter_hersteller'],"pa_hersteller")->name;
		else
			$pa_hersteller = __("Alle",'avia_framework');

		if ($_GET['filter_nutzung']!='')
			$pa_nutzung = get_term($_GET['filter_nutzung'],"pa_nutzung")->name;
		else
			$pa_nutzung = __("Alle",'avia_framework');
			
		if ($_GET['filter_art']!='')
			$pa_art = get_term($_GET['filter_art'],"pa_art")->name;
		else
			$pa_art = __("Alle",'avia_framework');
			
		if ($_GET['filter_preis']!='')
			$pa_preis = $_GET['filter_preis'];		
		else
			$pa_preis = __("Alle",'avia_framework');
		$output  = "";
		$output .= "<div class='product-sorting'>";

// Shop menu
		$output .= " 	<ul class='sort-param sort-param-shop'>";
		$output .= "		<li><span class='shop-yellow'><strong>".__("SHOP",'avia_framework')."</strong></span></li>";
		$output .= "	</ul>";
		
// add dropdown menu for filter
		$term_options = array("hide_empty" => 1);
		$terms_farben = get_terms("pa_farben",$term_options);
		$terms_hersteller = get_terms("pa_hersteller",$term_options);
		$terms_nutzung = get_terms("pa_nutzung",$term_options);
		$terms_art = get_terms("pa_art",$term_options);
		
		// Filter by Color	
		$output .= "    <ul class='sort-param sort-param-filter-farben'>";
		$output .= "    	<li><span class='currently-selected'> ".__("Farben: ",'avia_framework')."<strong>".$pa_farben."</strong></span>";
		$output .= "			<ul>";
		$output .= "				<li".avia_woo_active_class($pa_farben, 'Alle')."><span class='avia-bullet'></span><a href='".filter_build_query_string($params, 'filter_farben', '')."'>".__("Alle",'avia_framework')."</a></li>";
		foreach ($terms_farben as $term) {
			$output .= "				<li".avia_woo_active_class($pa_farben, $term->name)."><span class='avia-bullet'></span><a href='".filter_build_query_string($params, 'filter_farben', $term->term_id)."'>".__($term->name,'avia_framework')."</a></li>";		
		}
		$output .= 				"</ul>";
		$output .= "    	</li>";
		$output .= "	</ul>";
		
		// Filter by Hersteller
		$output .= "    <ul class='sort-param sort-param-filter-hersteller'>";
		$output .= "    	<li><span class='currently-selected'> ".__("Hersteller: ",'avia_framework')."<strong>".$pa_hersteller."</strong></span>";
		$output .= "			<ul>";
		$output .= "				<li".avia_woo_active_class($pa_hersteller, 'Alle')."><span class='avia-bullet'></span><a href='".filter_build_query_string($params, 'filter_hersteller', '')."'>".__("Alle",'avia_framework')."</a></li>";
		foreach ($terms_hersteller as $term) {
			$output .= "				<li".avia_woo_active_class($pa_hersteller, $term->name)."><span class='avia-bullet'></span><a href='".filter_build_query_string($params, 'filter_hersteller', $term->term_id)."'>".__($term->name,'avia_framework')."</a></li>";		
		}
		$output .= 				"</ul>";
		$output .= "    	</li>";
		$output .= "	</ul>";
	
		// Filter by Preis
		$output .= "    <ul class='sort-param sort-param-filter-preis'>";
		$output .= "    	<li><span class='currently-selected'> ".__("Preis: ",'avia_framework')."<strong>".$pa_preis."</strong></span>";
		$output .= "			<ul>";
		$output .= "				<li".avia_woo_active_class($pa_preis, 'Alle')."><span class='avia-bullet'></span><a href='".filter_build_query_string($params, 'filter_preis', '')."'>".__("Alle",'avia_framework')."</a></li>";
		foreach ($preis_interval as $preis) {
			$output .= "				<li".avia_woo_active_class($pa_preis, $preis)."><span class='avia-bullet'></span><a href='".filter_build_query_string($params, 'filter_preis', $preis)."'>".__($preis,'avia_framework')."</a></li>";		
		}
		$output .= 				"</ul>";
		$output .= "    	</li>";
		$output .= "	</ul>";

		// Filter by Nutzung
		$output .= "    <ul class='sort-param sort-param-filter-nutzung'>";
		$output .= "    	<li><span class='currently-selected'> ".__("Nutzung: ",'avia_framework')."<strong>".$pa_nutzung."</strong></span>";
		$output .= "			<ul>";
		$output .= "				<li".avia_woo_active_class($pa_nutzung, 'Alle')."><span class='avia-bullet'></span><a href='".filter_build_query_string($params, 'filter_nutzung', '')."'>".__("Alle",'avia_framework')."</a></li>";
		foreach ($terms_nutzung as $term) {
			$output .= "				<li".avia_woo_active_class($pa_nutzung, $term-> name)."><span class='avia-bullet'></span><a href='".filter_build_query_string($params, 'filter_nutzung', $term->term_id)."'>".__($term->name,'avia_framework')."</a></li>";		
		}
		$output .= 				"</ul>";
		$output .= "    	</li>";
		$output .= "	</ul>";
		
		// Filter by Art
		$output .= "    <ul class='sort-param sort-param-filter-art'>";
		$output .= "    	<li><span class='currently-selected'> ".__("Art: ",'avia_framework')."<strong>".$pa_art."</strong></span>";
		$output .= "			<ul>";
		$output .= "				<li".avia_woo_active_class($pa_art, 'Alle')."><span class='avia-bullet'></span><a href='".filter_build_query_string($params, 'filter_art', '')."'>".__("Alle",'avia_framework')."</a></li>";
		foreach ($terms_art as $term) {
			$output .= "				<li".avia_woo_active_class($pa_art, $term->name)."><span class='avia-bullet'></span><a href='".filter_build_query_string($params, 'filter_art', $term->term_id)."'>".__($term->name,'avia_framework')."</a></li>";		
		}
		$output .= 				"</ul>";
		$output .= "    	</li>";
		$output .= "	</ul>";
		
		$output .= "</div>";
		echo $output;
	}
}

function filter_build_query_string($params = array(), $overwrite_key, $overwrite_value)
	{
		$params[$overwrite_key] = $overwrite_value;
		if ($overwrite_key=="filter_preis") {	
			list($min_price, $max_price) = explode("-", $overwrite_value);
			$params['min_price'] = $min_price;
			$params['max_price'] = $max_price;
			}
		return "?" . http_build_query($params);
	}
