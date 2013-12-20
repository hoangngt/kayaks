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
	

##################################################################
# helper functions for product pages
##################################################################

/* Add "Technische Daten - Tab" to product page */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
remove_action( 'woocommerce_before_single_product_summary', 'avia_add_image_div', 2);
remove_action( 'woocommerce_before_single_product_summary',  'avia_close_image_div', 20);
remove_action( 'woocommerce_before_single_product_summary', 'avia_add_summary_div', 25);
remove_action( 'woocommerce_after_single_product_summary',  'avia_close_div', 3);
remove_action( 'woocommerce_after_single_product_summary','woocommerce_output_product_data_tabs',1);
add_action( 'woocommerce_after_single_product_summary', 'woo_add_technik_tab', 1);
add_action( 'woocommerce_after_single_product_summary',  'avia_close_div', 3);

function woo_add_technik_tab()
{
	global $product;
	$tabs = apply_filters( 'woocommerce_product_tabs', array() );

	if ( ! empty( $tabs ) ) : ?>
	<div class='six units single-product-main-image alpha'>
		<div class="woocommerce-tabs">
			<ul class="tabs">
				<li class="technik_tab">
					<a href="#tab-technik"><?php echo apply_filters( 'woocommerce_product_additional_information_tab_title', "Technische Daten") ?></a>
				</li>

			</ul>


				<div class="panel entry-content" id="tab-technik">
					<?php 
					$heading = apply_filters( 'woocommerce_product_additional_information_heading', __( 'Technische Daten', 'woocommerce' ) );
					echo '<h2>'.$heading.'</h2>';
					$product->list_attributes();
					?>
				</div>


		</div>	
	</div>
	<div class='six units single-product-summary'>
	<div class="woocommerce-tabs">
		<ul class="tabs">
			<?php foreach ( $tabs as $key => $tab ) {
					if ($key!="additional_information") {
			?>

				<li class="<?php echo $key ?>_tab">
					<a href="#tab-<?php echo $key ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', $tab['title'], $key ) ?></a>
				</li>

			<?php } } ?>
		</ul>
		<?php foreach ( $tabs as $key => $tab ) { if ($key!="additional_information") {?>

			<div class="panel entry-content" id="tab-<?php echo $key ?>">
				<?php call_user_func( $tab['callback'], $key, $tab ) ?>
			</div>

		<?php } } ?>
	</div>
<?php
	endif;
}
/* dont need this anymore
add_action( 'woocommerce_product_write_panel_tabs','add_technik_tab_admin');
add_action( 'woocommerce_product_write_panels','product_write_technik_panel');
add_action( 'woocommerce_process_product_meta','product_save_data', 10, 2 );
function add_technik_tab_admin() {
		echo "<li class=\"product_tabs_technik\"><a href=\"#woocommerce_product_tabs_technik\">" . __( 'Technische Daten' ) . "</a></li>";
}
function product_write_technik_panel() {
		global $post;
		// the product

		$tab_data =  get_post_meta( $post->ID, 'hoang_woo_product_technik_tab', true );
		echo '<div id="woocommerce_product_tabs_technik" class="panel wc-metaboxes-wrapper woocommerce_options_panel">';
		if ( empty($tab_data) ) {
			$tab_data[0]['content']='Es liegt noch keine technische Daten für dieses Produkt vor';
		}
		wp_textarea_input (array( 'id' => '_wc_technik_product_tabs_tab_content', 'label' => __( 'Technische Daten' ), 'placeholder' => __( 'HTML and text to display.' ), 'value' => $tab_data[0]['content'], 'style' => 'width:70%;height:21.5em;' ));
		echo '</div>';
	}
function wp_textarea_input( $field ) {
		global $thepostid, $post;

		if ( ! $thepostid ) $thepostid = $post->ID;
		if ( ! isset( $field['placeholder'] ) ) $field['placeholder'] = '';
		if ( ! isset( $field['class'] ) ) $field['class'] = 'short';
		if ( ! isset( $field['value'] ) ) $field['value'] = get_post_meta( $thepostid, $field['id'], true );

		echo '<p class="form-field ' . $field['id'] . '_field"><label style="display:block;" for="' . $field['id'] . '">' . $field['label'] . '</label><textarea class="' . $field['class'] . '" name="' . $field['id'] . '" id="' . $field['id'] . '" placeholder="' . $field['placeholder'] . '" rows="2" cols="20"' . (isset( $field['style'] ) ? ' style="' . $field['style'] . '"' : '') . '>' . esc_textarea( $field['value'] ) . '</textarea> ';

		if ( isset( $field['description'] ) && $field['description'] )
			echo '<span class="description">' . $field['description'] . '</span>';

		echo '</p>';
	}
function product_save_data( $post_id, $post ) {
		$tab_content = stripslashes( $_POST['_wc_technik_product_tabs_tab_content'] );

		if (empty( $tab_content )) {
			// clean up if the custom tabs are removed
			$tab_content = "Es liegt noch keine technische Daten für dieses Produkt vor";
		}
			$tab_data = array();
			$tab_title = "Technische Daten";
			$tab_id = '';
			if ( $tab_title ) {
				if ( strlen( $tab_title ) != strlen( utf8_encode( $tab_title ) ) ) {
					// can't have titles with utf8 characters as it breaks the tab-switching javascript
					$tab_id = "tab-custom";
				} else {
					// convert the tab title into an id string
					$tab_id = strtolower( $tab_title );
					$tab_id = preg_replace( "/[^\w\s]/", '', $tab_id );
					// remove non-alphas, numbers, underscores or whitespace
					$tab_id = preg_replace( "/_+/", ' ', $tab_id );
					// replace all underscores with single spaces
					$tab_id = preg_replace( "/\s+/", '-', $tab_id );
					// replace all multiple spaces with single dashes
					$tab_id = 'tab-' . $tab_id;
					// prepend with 'tab-' string
				}
			}

			// save the data to the database
			$tab_data[] = array( 'title' => $tab_title, 'id' => $tab_id, 'content' => $tab_content );
			update_post_meta( $post_id, 'hoang_woo_product_technik_tab', $tab_data );
}
*/
/* Change default product gallery to Enfold gallery */
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
add_action('woocommerce_before_single_product_summary', 'hoang_show_enfold_product_images', 20);
function hoang_show_enfold_product_images() {
	global $product;
	$attachment_ids = $product->get_gallery_attachment_ids();
	$attachment_ids = implode(',',$attachment_ids);
	$featured_img_id = get_post_thumbnail_id($product->id);
	$shortcode = "[av_gallery ids='".$featured_img_id.",".$attachment_ids."' style='big_thumb' preview_size='portfolio' thumb_size='portfolio' columns='5' imagelink='avianolink noLightbox' lazyload='avia_lazyload']";
	echo do_shortcode($shortcode);
}
function header_slider() {
	layerslider(4);
}

// create a shortcode to show all Subcategories of Kayak-Cat
add_shortcode("hoang_woo_sub_cat", "hoang_woo_sub_cat");
function hoang_woo_sub_cat($atts) {
	$product_cat = "product_cat";
	extract(shortcode_atts(array(  
        'cat_id' => '33',  
        'columns' => '4'
    ), $atts)); 
    $catpage = get_query_var( 'page' ) ? get_query_var( 'page' ) : 1;
    $offset = ($catpage - 1) * $columns;
 // get list of subcategories of $cat_id
 	$args = array(
	  'child_of'     => $cat_id,
	  'menu_order'   => 'ASC',
	  'hide_empty'   => 1,
	  'hierarchical' => 1,
	  'number'		 => $columns,
	  'offset'		 => $offset,
	  'pad_counts'   => 1
	);
	$product_categories = get_terms($product_cat,$args);
	if (count($product_categories)<1) return;
// make html output for shortcode
	$container_id = 1;
	$extraClass 		= 'first';
	$grid 				= 'one_fourth';
	if($preview_mode == 'auto') $image_size = 'portfolio';
	$post_loop_count 	= 1;
	$loop_counter		= 1;
	$output				= '';
	$style_class		= empty($style) ? 'no_margin' : $style;
	$total				= count($product_categories) % 2 ? "odd" : "even";
	switch($columns)
	{
		case "1": $grid = 'av_fullwidth';  if($preview_mode == 'auto') $image_size = 'featured'; break;
		case "2": $grid = 'av_one_half';   break;
		case "3": $grid = 'av_one_third';  break;
		case "4": $grid = 'av_one_fourth'; if($preview_mode == 'auto') $image_size = 'portfolio_small'; break;
		case "5": $grid = 'av_one_fifth';  if($preview_mode == 'auto') $image_size = 'portfolio_small'; break;
	}
	$output .= "<div class='grid-sort-container isotope {$style_class}-container with-excerpt-container grid-total-{$total} grid-col-{$columns} grid-links-{$linking}' data-portfolio-id='{$container_id}'>";
	foreach ($product_categories as $cat) {
		$the_id 	= $cat->term_id;
		$parity		= $post_loop_count % 2 ? 'odd' : 'even';
		$last       = count($product_categories) == $post_loop_count ? " post-entry-last " : "";
		$post_class = "post-entry post-entry-{$the_id} grid-entry-overview grid-loop-{$post_loop_count} grid-parity-{$parity} {$last}";
		$title_link = $link = home_url()."/?product_cat=".$cat->slug;
		$title 		= $cat->name;
		$custom_overlay = apply_filters('avf_portfolio_custom_overlay', "", $cat);
		$link_markup 	= apply_filters('avf_portfolio_custom_image_container', array("a href='{$link}' title='".esc_attr(strip_tags($title))."' ",'a'), $cat);

		$title 			= apply_filters('avf_portfolio_title', $title, $cat);
		$title_link     = apply_filters('avf_portfolio_title_link', $title_link, $cat);
		if($columns == "1" && $one_column_template == 'special')
        {
            $extraClass .= ' special_av_fullwidth ';

            $output .= "<div data-ajax-id='{$the_id}' class=' grid-entry flex_column isotope-item all_sort {$style_class} {$post_class} {$sort_class} {$grid} {$extraClass}'>";
            $output .= "<article class='main_color inner-entry' ".avia_markup_helper(array('context' => 'entry','echo'=>false)).">";
            $output .= apply_filters('avf_portfolio_extra', "", $cat);

            $output .= "<div class='av_table_col first portfolio-entry grid-content'>";

            if(!empty($title))
            {
                $markup = avia_markup_helper(array('context' => 'entry_title','echo'=>false));
                $output .= '<header class="entry-content-header">';
                $output .= "<h2 class='portfolio-grid-title entry-title' $markup><a href='{$title_link}'>".$title."</a></h2>";
                $output .= '</header>';
            }

            if(!empty($excerpt))
            {
                $markup = avia_markup_helper(array('context' => 'entry_content','echo'=>false));

                $output .= "<div class='entry-content-wrapper'>";
                $output .= "<div class='grid-entry-excerpt entry-content' $markup>".$excerpt."</div>";
                $output .= "</div>";
            }
            $output .= '<div class="avia-arrow"></div>';
            $output .= "</div>";
            $thumbnail_id = get_woocommerce_term_meta($the_id , 'thumbnail_id', true);
         	$img_src = wp_get_attachment_image_src($thumbnail_id, $image_size );
            $img_src = $img_src[0];
            $image = "<img width='260' height='185' src='".$img_src."' class='attachment-portfolio_small wp-post-image' alt='013'>";
            if(!empty($image))
            {
                $output .= "<div class='av_table_col portfolio-grid-image'>";
                $output .= "<".$link_markup[0]." data-rel='grid-1' class='grid-image avia-hover-fx'>".$custom_overlay.$image."</".$link_markup[1].">";
                $output .= "</div>";
            }
            $output .= '<footer class="entry-footer"></footer>';
            $output .= "</article>";
            $output .= "</div>";
        }
        else
        {
            $extraClass .= ' default_av_fullwidth ';
            $thumbnail_id = get_woocommerce_term_meta($the_id , 'thumbnail_id', true);
            $img_src = wp_get_attachment_image_src($thumbnail_id, $image_size );
            $img_src = $img_src[0];
            $image = "<img width='260' height='185' src='".$img_src."' class='attachment-portfolio_small wp-post-image' alt='013'>";
            $output .= "<div data-ajax-id='{$the_id}' class=' grid-entry flex_column isotope-item all_sort {$style_class} {$post_class} {$sort_class} {$grid} {$extraClass}'>";
            $output .= "<article class='main_color inner-entry' ".avia_markup_helper(array('context' => 'entry','echo'=>false)).">";
            $output .= apply_filters('avf_portfolio_extra', "", $cat);
            $output .= "<".$link_markup[0]." data-rel='grid-".avia_post_grid::$grid."' class='grid-image avia-hover-fx'>".$custom_overlay. $image."</".$link_markup[1].">";
            $output .= !empty($title) || !empty($excerpt) ? "<div class='grid-content'><div class='avia-arrow'></div>" : '';

            if(!empty($title))
            {
                $markup = avia_markup_helper(array('context' => 'entry_title','echo'=>false));
                $output .= '<header class="entry-content-header">';
                $output .= "<h3 class='grid-entry-title entry-title' $markup><a href='{$title_link}' title='".esc_attr(strip_tags($title))."'>".$title."</a></h3>";
                $output .= '</header>';
            }
            $output .= !empty($excerpt) ? "<div class='grid-entry-excerpt entry-content' ".avia_markup_helper(array('context'=>'entry_content','echo'=>false)).">".$excerpt."</div>" : '';
            $output .= !empty($title) || !empty($excerpt) ? "</div>" : '';
            $output .= '<footer class="entry-footer"></footer>';
            $output .= "</article>";
            $output .= "</div>";
        }


		$loop_counter ++;
		$post_loop_count ++;
		$extraClass = "";

		if($loop_counter > $columns)
		{
			$loop_counter = 1;
			$extraClass = 'first';
		}
	}

	$output .= "</div>";

		//append pagination
	$args = array(
	  'child_of'     => $cat_id,
	  'menu_order'   => 'ASC',
	  'hide_empty'   => 1,
	  'hierarchical' => 1,
	  'pad_counts'   => 1
	);
	$count = count (get_terms($product_cat,$args));		// get total amount of subcat
	$avia_pagination = hoang_pagination(ceil($count/$columns), 'nav');
	$output .= $avia_pagination;
	return $output;
}
function hoang_pagination($pages = '', $wrapper = 'div')
	{
		global $paged;

		if(get_query_var('paged')) {
		     $paged = get_query_var('paged');
		} elseif(get_query_var('page')) {
		     $paged = get_query_var('page');
		} else {
		     $paged = 1;
		}

		$output = "";
		$prev = $paged - 1;
		$next = $paged + 1;
		$range = 2; // only edit this if you want to show more page-links
		$showitems = ($range * 2)+1;



		if($pages == '')
		{
			global $wp_query;
			//$pages = ceil(wp_count_posts($post_type)->publish / $per_page);
			$pages = $wp_query->max_num_pages;
			if(!$pages)
			{
				$pages = 1;
			}
		}

		$method = "get_pagenum_link";
		if(is_single())
		{
			$method = "avia_post_pagination_link";
		}



		if(1 != $pages)
		{
			$output .= "<$wrapper class='pagination'>";
			$output .= ($paged > 2 && $paged > $range+1 && $showitems < $pages)? "<a href='".$method(1)."'>&laquo;</a>":"";
			$output .= ($paged > 1 && $showitems < $pages)? "<a href='".$method($prev)."'>&lsaquo;</a>":"";

			$output .= "<span class='pagination-meta'>";
			for ($i=1; $i <= $pages; $i++)
			{
				if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
				{
					$output .= ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".$method($i)."' class='inactive' >".$i."</a>";
				}
			}
			$output .= "</span>";
			$output .= ($paged < $pages && $showitems < $pages) ? "<a href='".$method($next)."'>&rsaquo;</a>" :"";
			$output .= ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) ? "<a href='".$method($pages)."'>&raquo;</a>":"";
			$output .= "</$wrapper>\n";
		}

		return $output;
	}
