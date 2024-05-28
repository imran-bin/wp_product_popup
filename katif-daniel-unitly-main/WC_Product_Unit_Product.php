<?php 

function load_functions_after_plugins_loaded(){
    add_action( 'init', 'register_unit_price_product_type');
    add_filter( 'product_type_selector', 'add_unit_price_product_type' ); 
    add_filter( 'woocommerce_product_class', 'unitly_woo_unit_product_class', 10, 2 );
   
    add_action('woocommerce_unit_product_add_to_cart', function(){
        do_action('woocommerce_simple_add_to_cart');
    } );
}
add_action('plugins_loaded', 'load_functions_after_plugins_loaded');


function register_unit_price_product_type() {
    /**
     * Class that generates Unit Price Product Type
     */
    class WC_Product_Unit_Product extends WC_Product {
                        
        public function __construct( $product ) {
            $this->product_type = 'unit_product';
            parent::__construct( $product );
        }

        /**
         * Get internal type.
         * @return string
         */
        public function get_type() {
            return  $this->product_type;
        }
    }
}

 /**
 * Add "unit price product type" to product data tab
 */
function add_unit_price_product_type( $types ){
    $types[ 'unit_product' ] = __( 'Unit Price (Unitly)', 'unitly-woocommerce' );
    // print_r($types);
    // die();
    return $types;
}


function unitly_woo_unit_product_class( $classname, $product_type ) {
    if ( $product_type == 'unit_product' ) { // notice the checking here.
        $classname = 'WC_Product_Unit_Product';
    }
    return $classname;
}
   
