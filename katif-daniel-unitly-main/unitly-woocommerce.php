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
// AJAX handler for fetching product details
 
function get_product_details() {
    // Check the AJAX nonce for security
    check_ajax_referer('ajax-nonce', 'security');
    
    // Get the product ID from the AJAX request
    $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : '';
    
    // Fetch product details based on $product_id
    $product = wc_get_product($product_id);
    
    if ($product) {
        // Generate the add to cart button
        $button_html = '<button class="wc-add-to-cart" data-post-id="' . esc_attr($product_id) . '">Add to Cart</button>';
        
        // Retrieve custom fields for price per unit and product unit
        $price_per_unit = get_post_meta($product_id, 'price_per_unit', true);
        $product_unit = get_post_meta($product_id, 'product_unit', true);
        $custom_unit = get_post_meta($product_id,'product_custom_unit',true);
         
        
        // Return product details along with button HTML as JSON
        wp_send_json_success(array(
            'name' => $product->get_name(),
            'price' => $product->get_price(),
            'unit_size' => $product_unit, // Assuming product unit is stored in product_unit meta
            'price_per_unit' => $price_per_unit,
            'custom_unit' => $ $custom_unit, // Assuming price per unit is stored in price_per_unit meta
            'button_html' => $button_html,
        ));
    } else {
        // Product not found, send error response
        wp_send_json_error('Product not found');
    }
}




add_action('wp_ajax_get_product_details', 'get_product_details');
add_action('wp_ajax_nopriv_get_product_details', 'get_product_details');  

function add_to_cart() {
    check_ajax_referer('ajax-nonce', 'security');
    
    $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : '';
    
    // Add product to cart based on $product_id
    $result = WC()->cart->add_to_cart($product_id);
    
    if ($result) {
        // Get cart count
        $cart_count = WC()->cart->get_cart_contents_count();
        
        // Return response with cart count
        wp_send_json_success(array(
            'cart_count' => $cart_count
        ));
    } else {
        // Error adding product to cart
        wp_send_json_error('Failed to add product to cart');
    }
}
add_action('wp_ajax_add_to_cart', 'add_to_cart');
add_action('wp_ajax_nopriv_add_to_cart', 'add_to_cart'); // For non-logged-in users

function get_cart_count() {
    // Perform necessary operations to get the cart count
    $cart_count = WC()->cart->get_cart_contents_count(); // Assuming WooCommerce is used

    // Return the cart count as JSON
    wp_send_json_success(array('cart_count' => $cart_count));
    
    // Always exit to avoid further execution
    wp_die();
}
add_action('wp_ajax_get_cart_count', 'get_cart_count');
add_action('wp_ajax_nopriv_get_cart_count', 'get_cart_count'); // For non-log


/**
 * Kick-off the plugin
 */
kickoff_unitly_woocommerce();