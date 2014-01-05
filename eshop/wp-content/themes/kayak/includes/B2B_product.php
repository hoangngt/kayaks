<?php

/**
 * Simple Product Class
 *
 * The default product type kinda product.
 *
 * @class       WC_Product_B2B
 * @version     1.0.0
 * @category    Class
 * @author      Ta Hoang
 */


add_action( 'pre_get_posts', 'custom_pre_get_posts_query' );
add_filter( 'product_type_selector', 'hoang_add_product_type',10,2 );
add_role("B2B Kunde", "B2B Kunde");
function hoang_add_product_type( $types ){
       $types[ 'b2b_product' ] = __( 'B2B Product' );
       return $types;
}

class WC_Product_B2b_product extends WC_Product {

    /**
     * __construct function.
     *
     * @access public
     * @param mixed $product
     */
    public function __construct( $product ) {
        $this->product_type = 'b2b_product';
        parent::__construct( $product );
    }

    /**
     * Get the title of the post.
     *
     * @access public
     * @return string
     */
    public function get_title() {

        $title = $this->post->post_title;

        if ( $this->get_parent() > 0 ) {
            $title = get_the_title( $this->get_parent() ) . ' &rarr; ' . $title;
        }

        return apply_filters( 'woocommerce_product_title', apply_filters( 'the_title', $title, $this->id ), $this );
    }

    /**
     * Returns false if the product cannot be bought.
     *
     * @access public
     * @return cool
     */
    public function is_purchasable() {
        return apply_filters( 'woocommerce_is_purchasable', false, $this );
    }
    public function is_virtual() {
        return true;
    }
}

function is_b2b() {
    if (current_user_can( 'manage_options' )) return true;
    global $current_user;
    foreach ($current_user->roles as $role) {
        if ($role=='B2B Kunde') return true;
    }
    return false;
}
function is_b2b_shop() {
    global $post;
    if ($post->post_name=='shop-b2b') return true;
    return false;
}
function is_b2b_product() {
	global $product;
	if ($product->is_type('b2b_product')) return true;
	
	return false;
}
function hoang_filter() {
   global $woocommerce;
   $filtered = false;
   $filtered_posts = array();
  
   $tax_queries = array();
   $attribute_taxonomies = $woocommerce->get_attribute_taxonomies();
   if ( $attribute_taxonomies ) {
       foreach ( $attribute_taxonomies as $tax ) {
           $attribute = sanitize_title( $tax->attribute_name );
           $taxonomy = $woocommerce->attribute_taxonomy_name( $attribute );
               // create an array of product attribute taxonomies
           $_attributes_array[] = $taxonomy;
           $name = 'filter_' . $attribute;
           if ( ! empty( $_GET[ $name ] ) && taxonomy_exists( $taxonomy ) ) {
               $filtered = true;
               $tax_query = array(
                    'taxonomy' => 'pa_' . $attribute,
                    'field' => 'id',
                    'terms' => array($_GET[ $name ]),
                    'operator' => 'IN'
                );
               array_push($tax_queries, $tax_query);                 
           }
       }
   }     
    array_push($tax_queries, array(
            'taxonomy' => 'product_type',
            'field' => 'slug',
            'terms' => array( 'b2b_product' ), // Display only b2b-products on the B2B page
            'operator' => 'IN'
        )) ;
   return $tax_queries;
}

function custom_pre_get_posts_query( $q ) {
    if ( ! $q->is_post_type_archive() ) return;
    if ( !$q->is_main_query() && !is_b2b_shop()) return;
    if (is_admin()) return;
    if (!is_b2b_shop()) {
        $q->set( 'tax_query', array(array(
            'taxonomy' => 'product_type',
            'field' => 'slug',
            'terms' => array( 'b2b_product' ), // Don't display b2b-products on the shop page
            'operator' => 'NOT IN'
        )));    
    }
    if (is_b2b_shop()) {
        $tax_queries = hoang_filter();
        $q->set( 'tax_query', $tax_queries);  
    }
 
}