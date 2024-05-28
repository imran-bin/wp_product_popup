<?php 
/**
 * Plugin Name: Unitly WooCommerce
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

/**
 * Kick-off the plugin
 */
kickoff_unitly_woocommerce();