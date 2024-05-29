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
    check_ajax_referer('ajax-nonce', 'security');
    
    $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : '';
    
    // Fetch product details based on $product_id
    $product = wc_get_product($product_id);
    
    if ($product) {
        // Generate the add to cart button
        $button_html = '<a href="' . esc_url($product->add_to_cart_url()) . '" class="button">Add to Cart</a>';
        
        // Return product details along with button HTML as JSON
        wp_send_json_success(array(
            'name' => $product->get_name(),
            'price' => $product->get_price(),
            'button_html' => $button_html,
        ));
    } else {
        // Product not found
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
add_action('wp_ajax_nopriv_get_cart_count', 'get_cart_count'); // For non-logged-in users


/**
 * Kick-off the plugin
 */
kickoff_unitly_woocommerce();


?>


<script>
    jQuery(document).ready(function($) {
    var previousQuantity = 1; // Default quantity
    var previousSubtotal = 0; // Default subtotal
  
    // Function to update subtotal when quantity changes
    function updateSubtotal() {
        var quantity = parseInt($('#quantity').val());
      
        var price = parseFloat($('#product-price').data('price'));
        var subtotal = quantity * price;
        $('#subtotal').text('Subtotal: $' + subtotal.toFixed(2));
  
        // Update previous subtotal
        previousSubtotal = subtotal;
    }
  
    // Update subtotal when quantity changes
    $(document).on('input', '#quantity', function() {
        updateSubtotal();
    });
  
    // Function to show popup with product details
    function showPopup(productName, productPrice, productWeight, productId) {
        // Clear previous popup content
        $('#popup').remove();
  
        // Create new popup HTML with the product data
        var popupHtml = '<div id="popup" style="display: none;">' +
                            '<h2>' + productName + '</h2>' +
                            '<p id="product-price" data-price="' + productPrice + '">Price: $' + productPrice + '</p>' +
                            '<p>Weight: ' + productWeight + '</p>' +
                            '<div class="product-options">' +
                                '<label for="quantity">Quantity:</label>' +
                                '<input type="number" id="quantity" name="quantity" value="' + previousQuantity + '" min="1">' +
                                '<p id="subtotal">Subtotal: $' + previousSubtotal.toFixed(2) + '</p>' +
                            '</div>' +
                            '<button class="add-to-cart" data-post-id="' + productId + '">Add to Cart</button>' +
                            '<button class="close-popup">Close</button>' +
                        '</div>';
        // Append the new popup HTML to the body
        $('body').append(popupHtml);
  
        // Show the pop-up
        $('#popup').addClass('active').show();
    }
  
    // Listen for clicks on elements with data-product-id attribute
    $(document).on('click', '[data-post-id]', function(e) {
        e.preventDefault(); // Prevent default action
  
        var productId = $(this).data('post-id');
        
        // AJAX request to fetch product details
        $.ajax({
            url: my_ajax_object.ajax_url,
            type: 'post',
            data: {
                action: 'get_product_details',
                product_id: productId,
                security: my_ajax_object.ajax_nonce
            },
            success: function(response) {
                // Call function to show popup with product details
                showPopup(response.data.name, response.data.price, response.data.weight, response.data.id);
            },
            error: function(xhr, status, error) {
                // Handle errors
                console.error(xhr.responseText);
            }
        });
    });
  
    // Close the pop-up when the close button is clicked
    $('body').on('click', '.close-popup', function() {
        $('#popup').removeClass('active').hide();
    });
  
    // Add to Cart functionality
    $('body').on('click', '.add-to-cart', function() {
        var productId = $(this).data('post-id');
        var quantity = $('#quantity').val();
       
        var subtotal = parseFloat($('#subtotal').text().replace('Subtotal: $', ''));
        console.log(subtotal);
        
        // Update previous quantity and subtotal
        previousQuantity = quantity;
        previousSubtotal = subtotal;
  
        // AJAX request to add product to cart
        $.ajax({
            url: my_ajax_object.ajax_url,
            type: 'post',
            data: {
                action: 'add_to_cart',
                product_id: productId,
                quantity: quantity,
                subtotal: subtotal, // Include subtotal in the data
                security: my_ajax_object.ajax_nonce
            },
            success: function(response) {
                // Update data-counter attribute
                var currentCount = parseInt($('[data-counter]').attr('data-counter'));
                $('[data-counter]').attr('data-counter', currentCount + 1);
                $('[data-counter]').text(currentCount + 1); // Update text content to reflect the new value
            },
            error: function(xhr, status, error) {
                // Handle errors
                console.error(xhr.responseText);
            }
        });
    });
  
    // Functionality for the "Shop More" button
    $('#shopmore').click(function() {
        // Your code to load more products
        // After loading the products, ensure that they have the appropriate attributes and classes
        // For example, if they have data-post-id attributes, ensure that the above click event listener can handle them
    });
  
    // When cart accordion is opened, set quantity to 3 and subtotal to 140
    $('#cart-accordion').on('shown.bs.collapse', function () {
        // Set quantity to 3
        $('#quantity').val(3);
        // Calculate subtotal
        var price = parseFloat($('#product-price').data('price'));
        var subtotal = 3 * price;
        $('#subtotal').text('Subtotal: $' + subtotal.toFixed(2));
        // Update previous subtotal
        previousSubtotal = subtotal;
    });
  });
  
</script>



