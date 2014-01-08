<?php
/**
 * Installation
 * 
 * @author jj,ap
 */
Class WGM_Installation {
	
	/**
	* Activation of the plugin, and create the default pages and Prefereces
	* @access	public
	* @static
	* @uses		version_compare, deactivate_plugins, wp_sprintf, update_option
	* @todo		remove db-select
	* @return	void
	*/
	public static function on_activate() {
		global $wpdb;

		// check wp version
		if ( ! version_compare( $GLOBALS[ 'wp_version' ], '3.0', '>=' ) ) {
			deactivate_plugins( Woocommerce_German_Market::$plugin_filename );
			die(
				wp_sprintf(
					'<strong>%s:</strong> ' .
					__( 'Entschuldigung, dieses Plugin benötigt Wordpress 3.0+ um zu funktionieren', Woocommerce_German_Market::get_textdomain() )
					, Woocommerce_German_Market::get_plugin_data( 'Name' )
				)
			);
		}


		// check php version
		if ( version_compare( PHP_VERSION, '5.2.0', '<' ) ) {
			deactivate_plugins( Woocommerce_German_Market::$plugin_filename ); // Deactivate ourself
			die(
				wp_sprintf(
					'<strong>%1s:</strong> ' .
					__( 'Entschuldigung, dieses Plugin benötigt PHP 5.0+ um ordnungsgemäß zu funktionieren. Ihre aktuelle PHP Version ist %1s, bitte fragen beten Sie ihren Hoster eine aktuellere, nicht so fehleranfällige PHP Version zu installieren. <strong>Über 80% der Wordpress installationen benutzen PHP 5.2+</strong>.', Woocommerce_German_Market::get_textdomain() )
					, Woocommerce_German_Market::get_plugin_data( 'Name' ), PHP_VERSION
				)
			);
		}

		if ( is_plugin_inactive( 'woocommerce/woocommerce.php' ) ) {
			
			deactivate_plugins( Woocommerce_German_Market::$plugin_filename ); // Deactivate ourself
			die(
				__( 'Das Plugin <strong>Woocommerce</strong> ist nicht aktiv; ist aber notwendig für die Nutzung dieses Plugins.', Woocommerce_German_Market::get_textdomain() )
			);
		}

		if ( Woocommerce_German_Market::is_wc_2() !== true ) {
			
			deactivate_plugins( Woocommerce_German_Market::$plugin_filename ); // Deactivate ourself
			die(
				__( 'Es wird <strong>Woocommerce 2.0+</strong> ben&ouml;tigt um <strong>German Market 2.0+</strong> verwenden zu k&ouml;nnen. Weitere Informationen im <a href="http://marketpress.de/2013/woocommerce-german-market-2-0-als-download-verfuegbar/">Supportforum</a>', Woocommerce_German_Market::get_textdomain() )
			);
		}

		// set the status to installed
		update_option( WGM_Helper::get_wgm_option( 'woocommerce_options_installed' ), 0 );

		// install the default options
		WGM_Installation::install_default_options();
	}
	
	/**
	* Handle install notice
	* 
	* @access	public
	* @static
	* @uses		is_plugin_inactive, deactivate_plugins, get_option, wp_verify_nonce, update_option
	* @return	void
	*/
	public static function install_notice() {
        
		if ( is_plugin_inactive( 'woocommerce/woocommerce.php' ) ) {
			deactivate_plugins(  Woocommerce_German_Market::$plugin_filename );
			die(
				__( 'Das Plugin <strong>Woocommerce</strong> ist nicht aktiv; ist aber notwendig für die Nutzung dieses Plugins.', Woocommerce_German_Market::get_textdomain() )
			);
		}

		if( 1 == get_option( WGM_Helper::get_wgm_option( 'woocommerce_options_installed' ) ) )
			return;

		if ( get_option( 'woocommerce_de_previous_installed' ) == 1 )
			return;

		if( isset( $_GET[ 'woocommerce_de_install' ] ) && wp_verify_nonce( $_GET[ '_wpnonce' ] ) ) {

			$overwrite = isset( $_GET[ 'woocommerce_de_install_de_pages_overwrite' ] );

			if ( isset( $_GET[ 'woocommerce_de_install_de_options' ] ) )
				WGM_Installation::install_de_options();

			if ( isset( $_GET[ 'woocommerce_de_install_de_pages' ] ) )
				WGM_Installation::install_default_pages( $overwrite );

			// set woocommerce de installed
			update_option( WGM_Helper::get_wgm_option( 'woocommerce_options_installed' ), 1 );

			?>
			<div class="updated woocommerce-de-message single-line">
                <div class="logo"></div>
				<h5 class="narrow"><?php _e( 'Herzlichen Glückwunsch, WooCommerce für den deutschsprachigen Raum ist fertig eingerichtet.', Woocommerce_German_Market::get_textdomain() ); ?></h5>
			</div>
			<?php

			WGM_Helper::update_option_if_not_exist( 'woocommerce_de_previous_installed', 1 );

			return;
		}

		WGM_Template::include_template( 'install_notice.php' );
	}
	
	/**
	* Install default options
	*
	* @uses update_option
	* @static
	* @author jj
	* @return void
	*/
	public static function install_default_options() {
		
		WGM_Helper::update_option_if_not_exist( WGM_Helper::get_wgm_option( 'woocommerce_de_append_imprint_to_mail' ), 'on' );
		WGM_Helper::update_option_if_not_exist( WGM_Helper::get_wgm_option( 'woocommerce_de_append_withdraw_to_mail' ), 'on' );
		WGM_Helper::update_option_if_not_exist( WGM_Helper::get_wgm_option( 'woocommerce_de_append_terms_to_mail' ), 'on' );
		WGM_Helper::update_option_if_not_exist( WGM_Helper::get_wgm_option( 'load_woocommerce-de_standard_css' ), 'on' );
		WGM_Helper::update_option_if_not_exist( WGM_Helper::get_wgm_option( 'woocommerce_de_show_Widerrufsbelehrung' ), 'on' ); 
		
		update_option( 'woocommerce_price_thousand_sep', '.' );
		update_option( 'woocommerce_price_decimal_sep', ',' );
	}
	
	/**
	* Install german specific options
	*
	* @access public
	* @author jj
	* @static
	* @return void
	*/
	public static function install_de_options() {

		// set currency and country to EUR and germany
		update_option( 'woocommerce_currency', apply_filters( 'woocommerce_de_currency', WGM_Defaults::$woocommerce_de_currency ) );
		update_option( 'woocommerce_default_country', apply_filters( 'woocommerce_de_default_country', WGM_Defaults::$woocommerce_de_default_country ) );
		
		// When having a fresh woocommerce2 installation, we should copy into the new database
		if( Woocommerce_German_Market::is_wc_2() ) {
			WGM_Installation::set_default_woocommerce2_tax_rates( WGM_Defaults::get_default_tax_rates() );
		} else {
			update_option( 'woocommerce_tax_rates', apply_filters( 'woocommerce_de_default_tax_rate', WGM_Defaults::get_default_tax_rates() ) );
		}
		
		update_option( 'woocommerce_prices_include_tax', apply_filters( 'woocommerce_de_prices_include_tax', 'yes' ) );

		// tax default settings
		// make tax calculation default
		update_option( 'woocommerce_calc_taxes', 'yes' );
		// was deleted in woocommerce2 see: https://github.com/woothemes/woocommerce/commit/9eb63a8518448fe0e99820ba924f2bee850e9ddc#L0R1031
		//update_option( 'woocommerce_display_cart_prices_excluding_tax', apply_filters( 'woocommerce_de_display_cart_prices_excluding_tax', 'no' ) );
		update_option( 'woocommerce_tax_display_cart', apply_filters( 'woocommerce_de_woocommerce_tax_display_cart', 'incl' ) );
		// was also deleted and not used by WC German Market
		//update_option( 'woocommerce_display_totals_excluding_tax', apply_filters( 'woocommerce_de_display_totals_excluding_tax', 'no' ) );

		// install product attribues
		WGM_Installation::install_default_attributes();
	}
	
	/**
	 * Set the default tax rates for Woocommerce
	 * Copied from Woocommerce2 Core file admin/includes/updates/woocommerce-update-2.0.php
	 * 
	 * @access	public
	 * @static
	 * @global	$wpdb
	 * @uses	Woocommerce_German_Market::get_textdomain()
	 * @since	1.1.5
	 * @param	$tax_rates 
	 * @return	array
	 */
	public static function set_default_woocommerce2_tax_rates( $tax_rates ) {
		
		global $wpdb;
		// Update tax rates
		$loop = 0;
		
		if ( $tax_rates )
			foreach ( $tax_rates as $tax_rate ) {
				
				foreach ( $tax_rate['countries'] as $country => $states ) {
		
					$states = array_reverse( $states );
		
					foreach ( $states as $state ) {
		
						if ( $state == '*' )
							$state = '';

						$find = array(
							'tax_class'	=> $tax_rate[ 'class' ],
							'country'	=> $country
						);
						
						// check if tax already exists
						$tax_class = new WC_Tax();
						$r =  $tax_class->find_rates( $find ); 
						// update only, if current tax_class is not present
						if( empty( $r ) )								
							$wpdb->insert(
								$wpdb->prefix . "woocommerce_tax_rates",
								array(
									'tax_rate_country'  => $country,
									'tax_rate_state'    => $state,
									'tax_rate'          => $tax_rate['rate'],
									'tax_rate_name'     => $tax_rate['label'],
									'tax_rate_priority' => 1,
									'tax_rate_compound' => $tax_rate['compound'] == 'yes' ? 1 : 0,
									'tax_rate_shipping' => $tax_rate['shipping'] == 'yes' ? 1 : 0,
									'tax_rate_order'    => $loop,
									'tax_rate_class'    => $tax_rate['class']
								)
							);
		
						$loop++;
					}
				}
			}
	}
	
	/**
	* install default product attributes
	*
	* @author jj
	* @access public
	* @static
	* @uses globals $woocommerce, $wpdb, register_taxonomy, taxonomy_exists, get_option, sanitize_title, term_exists, wp_insert_term
	* @return void
	*/
	public static function install_default_attributes() {

		global $woocommerce, $wpdb;

		foreach( WGM_Defaults::get_default_procuct_attributes() as $attr )
			$new_tax_name = $woocommerce->attribute_taxonomy_name( $attr[ 'attribute_name' ] );
			if ( !taxonomy_exists( $new_tax_name ) ) {
				// insert attributes
				$wpdb->insert(
					$wpdb->prefix . "woocommerce_attribute_taxonomies",
					array(
						'attribute_name'  => $attr[ 'attribute_name' ],
						'attribute_label' => $attr[ 'attribute_label' ],
						'attribute_type'  => $attr[ 'attribute_type' ]
					),
					array( '%s', '%s', '%s' )
				);

				$category_base = ( 'yes' == get_option( 'woocommerce_prepend_shop_page_to_urls' ) ) ? trailingslashit( $base_slug ) : '';
				$label = $attr[ 'attribute_label' ];
				$hierarchical = true;

				register_taxonomy( $new_tax_name,
						array( 'product' ),
						array(
							'hierarchical' 				=> $hierarchical,
							'labels' => array(
								'name' 				=> $label,
								'singular_name' 	=> $label,
								'search_items' 		=> __( 'Search', 'woocommerce' )  . ' ' . $label,
								'all_items' 		=> __( 'All', 'woocommerce' )     . ' ' . $label,
								'parent_item' 		=> __( 'Parent', 'woocommerce' )  . ' ' . $label,
								'parent_item_colon' => __( 'Parent', 'woocommerce' )  . ' ' . $label . ':',
								'edit_item' 		=> __( 'Edit', 'woocommerce' )    . ' ' . $label,
								'update_item' 		=> __( 'Update', 'woocommerce' )  . ' ' . $label,
								'add_new_item' 		=> __( 'Add New', 'woocommerce' ) . ' ' . $label,
								'new_item_name' 	=> __( 'New', 'woocommerce' )     . ' ' . $label
								),
							'show_ui' 					=> false,
							'query_var' 				=> true,
							'show_in_nav_menus' 		=> false,
							'rewrite' 					=> array( 'slug' => $category_base . strtolower( sanitize_title( $attr[ 'attribute_name' ] ) ), 'with_front' => false, 'hierarchical' => $hierarchical ),
						)
				);
			}

			foreach( $attr[ 'elements' ] as $element ) {

				$params = array_merge( $element , array( 'post_type' => 'product' ) );
				if( ! term_exists( $element[ 'tag-name' ], $new_tax_name ) )
					wp_insert_term( $element[ 'tag-name' ], $new_tax_name , $params );
			}
	}
	
	/**
	* insert the default pages, and overwrite existing pages, if wanted.
	*
	* @author jj
	* @access public
	* @static
	* @uses globals $wpdb, apply_filters, wp_insert_post, wp_update_post, update_option
	* @param bool $overwrite overwrite existing pages
	* @return void
	*/
	public static function install_default_pages( $overwrite = FALSE ) {
		global $wpdb;
		// filter for change/add pages on auto insert on activation
		$pages = apply_filters( 'woocommerce_de_insert_pages', WGM_Helper::get_default_pages() );
       
		foreach ( $pages as $page ) {
			$check_sql = "SELECT ID, post_name FROM $wpdb->posts WHERE post_name = %s LIMIT 1";

			$post_name_check = $wpdb->get_row( $wpdb->prepare( $check_sql, $page[ 'post_name' ] ), ARRAY_A );
 
			$post_id = NULL;
            
			// only if not page exist, add page
			if ( $page[ 'post_name' ] !== $post_name_check[ 'post_name' ] ) {
                $post_id = wp_insert_post( $page );
			} else {
               // overwrite the content of the old pages
               $post_id = $post_name_check[ 'ID' ];
				if( $overwrite ) {
					$page[ 'ID' ] = $post_id;
					wp_update_post( $page );
				}
			}
			
			// insert default option
			if( $post_id && in_array( WGM_Defaults::get_german_option_name( $page[ 'post_name' ] ), array_keys( WGM_Defaults::get_options() ) ) ) {
				update_option( WGM_Helper::get_wgm_option( WGM_Defaults::get_german_option_name(  $page[ 'post_name' ] ) ), $post_id );
			}
		}
	}
	
	/**
	* delete options on uninstall
	*
	* @author fb
	* @static
	* @access public
	* @uses delete_option
	* @todo check deletion
	*/
	public static function on_uninstall() {
		foreach ( WGM_Defaults::get_options() as $key => $option )
			delete_option( $option );
	}


	public static function upgrade_deliverytimes(){
				
		$option = 'wgm_upgrade_deliverytimes';

		if( !get_option( $option, false ) ) {

			add_option( $option, true );

			$terms = get_terms( 'product_delivery_times', array( 'orderby' => 'id', 'hide_empty' => 0 ) );
			$old_terms = WGM_Defaults::get_lieferzeit_strings();

			if( count( $old_terms ) > count( $terms ) ) {
				$missing = new stdClass();
				$missing->term_id = -1;
				array_unshift( $terms, $missing );
			}
				
			$products = get_posts( array( 'post_type' => 'product', 'posts_per_page' => -1 ) );

			foreach( $products as $product ) {
				
				$deliverytime_index = get_post_meta( $product->ID, '_lieferzeit', TRUE );
				
				// Don't change the default delivery time
				if( (int) $deliverytime_index == -1 ) continue;

				if( ! array_key_exists( $deliverytime_index, $terms ) )
					$term_id = -1;
				else
					$term_id = $terms[ $deliverytime_index ]->term_id;

				update_post_meta( $product->ID, '_lieferzeit', $term_id );
			}	

			$global_delivery = get_option( WGM_Helper::get_wgm_option( 'global_lieferzeit' ) );
			$term_id = $terms[ $global_delivery ]->term_id;
			
			update_option( WGM_Helper::get_wgm_option( 'global_lieferzeit' ), $term_id );
		}
	}

	public static function upgrade_deliverytimes_notice() {

		if ( array_key_exists( 'woocommerce_de_upgrade_deliverytimes' , $_POST ) )
			update_option( 'wgm_upgrade_deliverytimes_notice_off', 1 );

		if ( get_option( 'woocommerce_de_previous_installed' ) === FALSE ) {
			update_option( 'wgm_upgrade_deliverytimes_notice_off', 1 );	
			return false;
		}

		if( get_option( 'wgm_upgrade_deliverytimes_notice_off' ) )
			return false;

		
		$screen = get_current_screen();

		if( $screen->id != 'woocommerce_page_woocommerce_settings' )
			WGM_Template::include_template( 'deliverytimes_upgrade_notice.php' );
	}

}

?>