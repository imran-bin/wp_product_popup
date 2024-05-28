<?php 
/**
 * Plugin Name: Unitly WooCommerce katif denial
 * Description: An WooCommerce addon plugin that helps you sell products based on per unit price such as per sqft, per gm, per kg, per cm etc.
 * Plugin URI: https://
 * Author: Mak Alamin
 * Author URI: https://
 * Version: 1.0.0
 * Text Domain: unitly-woocommerce
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if( ! defined('ABSPATH') ){
    exit;
}

/**
 * Check if WooCommerce is active
 **/
$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );

if ( ! in_array( 'woocommerce/woocommerce.php', $active_plugins ) ) {
    return;
} 

/**
 * Load "Unit Product Type" Class and autoload necessary files from includes folder
 */
if(file_exists(__DIR__ . '/WC_Product_Unit_Product.php')){
    require_once __DIR__ . '/WC_Product_Unit_Product.php';
}

if(file_exists(__DIR__ . '/vendor/autoload.php')){
    require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * The Main plugin class
 */
final class Unitly_WooCommerce  
{
    /**
     * Plugin version
     * @var string
     */
    const version = '1.0';

    /**
     * Class constructor
     */
    private function __construct()
    {
        $this->define_constants();
        
        add_action( 'plugins_loaded', [ $this, 'init_plugin_classes' ] );
        
    }
       

    /**
    * Initializes a singleton instance of this class
    *
    * @return \Unitly_WooCommerce
    */
    public static function init()
    {
       static $instance = false;

       if ( ! $instance ) {
           $instance = new self();
       }
    }
    
    /**
     * Define necessary plugin constants
     */
    public function define_constants()
    {
        define( 'UnitlyWoo_VERSION', self::version );
        define( 'UnitlyWoo_FILE', __FILE__ );
        define( 'UnitlyWoo_PATH', __DIR__ );
        define( 'UnitlyWoo_URL', plugins_url( '', UnitlyWoo_FILE ) );
        define( 'UnitlyWoo_ASSETS', UnitlyWoo_URL . '/assets' );
    }

    /**
     * Initializes the required plugin classes
     * 
     * @return void
     */
    public function init_plugin_classes()
    {
        if ( is_admin() ) {
            new UnitlyWoo\Admin();
        } else {
            new UnitlyWoo\Frontend();
        }
    }
}

/**
 * Initializes the main plugin
 * 
 * @return \Unitly_WooCommerce
 */
function kickoff_unitly_woocommerce()
{
    return Unitly_WooCommerce::init();
}

// AJAX handler for fetching product details
function get_product_details() {
     
    check_ajax_referer('ajax-nonce', 'security');
    
    $post_id = $_POST['post_id'];
    
    // Fetch product details based on $post_id
    // Example: $product = get_product_by_id($post_id);
    
    // For demonstration, let's assume $product is an array with product details
    $product = array(
        'name' => 'Sample Product',
        'price' => '10.00',
        'id' => $post_id // You can replace this with the actual product ID
    );
    
    // Return product details as JSON
    wp_send_json($product);
}


// AJAX handler for adding product to cart
function add_to_cart() {
    check_ajax_referer('ajax-nonce', 'security');
    
    $product_id = $_POST['product_id'];
    
    // Add product to cart based on $product_id
    // Example: $cart->add_product($product_id);
    
    // For demonstration, let's assume product is added successfully
    $response = array(
        'status' => 'success',
        'message' => 'Product added to cart successfully'
    );
    
    // Return response as JSON
    wp_send_json($response);
}

add_action('wp_ajax_nopriv_get_product_details',  'get_product_details');  
add_action('wp_ajax_add_to_cart', 'add_to_cart');
add_action('wp_ajax_nopriv_add_to_cart', 'add_to_cart'); 
add_action('wp_ajax_get_product_details', 'get_product_details');

/**
 * Kick-off the plugin
 */
kickoff_unitly_woocommerce();