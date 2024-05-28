<?php
/*
 * Plugin Name: Quantity Buttons for WooCommerce 
   Description: Display quantity increment buttons on shop, product, and cart page and set minimum / maximum order limits. 
   Author: FME Addons
   TextDomain: FME_QIFW
   Version: 1.1.4
   Woo: 7338084:9828e8d8c475464f245e088462c5cb4c
*/
if ( ! defined( 'WPINC' ) ) {
	wp_die();
}

if (! function_exists('is_plugin_active')) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php' ;
}


if ( !class_exists( 'Fme_QIFW_Quanity_Increment' ) ) {

	class Fme_QIFW_Quanity_Increment {
		
		public function __construct() {

			/**
 * Check if WooCommerce is active
 * if wooCommerce is not active ext Tabs module will not work.
 **/
			if ( !is_plugin_active( 'woocommerce/woocommerce.php') ) {

	
				add_action('admin_notices', array( $this, 'FME_QIFW_admin_notice' ));
			}

			add_action( 'init', array( $this, 'fme_quantity_increment_load_text_domain' ) );
			
			add_action('plugins_loaded', array( $this, 'Fme_QIFW_module_quantity_int_disable' ));

			$this->Fme_QIFW_module_constants();
			if (is_admin()) {
				require_once FMEQIFW_PLUGIN_DIR . 'admin/fme-quanity-increment-admin.php' ;
			} else {
				require_once FMEQIFW_PLUGIN_DIR . 'front/fme-quanity-increment-front.php' ;
			}
		}
		/**
	* Check WooCommerce is installed and active
	*
	* This function will check that woocommerce is installed and active
	* if not it will deactivate the current plugin and show an admin notice
	*
	* @return true or false
	*/
		public function FME_QIFW_admin_notice() {

			// Deactivate the plugin
				deactivate_plugins(__FILE__);

				$allowed_tags = array(
				'a' => array(
					'class' => array(),
					'href'  => array(),
					'rel'   => array(),
					'title' => array(),
				),
				'abbr' => array(
					'title' => array(),
				),
				'b' => array(),
				'blockquote' => array(
					'cite'  => array(),
				),
				'cite' => array(
					'title' => array(),
				),
				'code' => array(),
				'del' => array(
					'datetime' => array(),
					'title' => array(),
				),
				'dd' => array(),
				'div' => array(
					'class' => array(),
					'title' => array(),
					'style' => array(),
				),
				'dl' => array(),
				'dt' => array(),
				'em' => array(),
				'h1' => array(),
				'h2' => array(),
				'h3' => array(),
				'h4' => array(),
				'h5' => array(),
				'h6' => array(),
				'i' => array(),
				'img' => array(
					'alt'    => array(),
					'class'  => array(),
					'height' => array(),
					'src'    => array(),
					'width'  => array(),
				),
				'li' => array(
					'class' => array(),
				),
				'ol' => array(
					'class' => array(),
				),
				'p' => array(
					'class' => array(),
				),
				'q' => array(
					'cite' => array(),
					'title' => array(),
				),
				'span' => array(
					'class' => array(),
					'title' => array(),
					'style' => array(),
				),
				'strike' => array(),
				'strong' => array(),
				'ul' => array(
					'class' => array(),
				),
				);
				
				$wooextmm_message = '<div id="message" class="error">
				<p><strong> Quantity Buttons for WooCommerce Plugin is inactive.</strong> The <a href="http://wordpress.org/extend/plugins/woocommerce/">WooCommerce plugin</a> must be active for this plugin to work. Please install &amp; activate WooCommerce »</p></div>';

				echo wp_kses(__($wooextmm_message, 'exthwsm'), $allowed_tags);
		}
		public function fme_quantity_increment_load_text_domain() {
			load_plugin_textdomain('FME_QIFW', false, dirname(plugin_basename(__FILE__)) . '/languages/');
		}

		public function Fme_QIFW_module_constants() {
		
			if ( !defined( 'FMEQIFW_URL' ) ) {
				define( 'FMEQIFW_URL', plugin_dir_url( __FILE__ ) );
			}

			if ( !defined( 'FMEQIFW_BASENAME' ) ) {
				define( 'FMEQIFW_BASENAME', plugin_basename( __FILE__ ) );
			}

			if ( ! defined( 'FMEQIFW_PLUGIN_DIR' ) ) {
				define( 'FMEQIFW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}
		}

		public function Fme_QIFW_module_quantity_int_disable() {

			// Add min value to the quantity field (default = 1)
			add_filter('woocommerce_quantity_input_min', array( $this, 'fme_qifw_min_decimal' ));
			
			// Add step value to the quantity field (default = 1)
			add_filter('woocommerce_quantity_input_step', array( $this, 'fme_qifw_allow_decimal' ));
		

			// Removes the WooCommerce filter, that is validating the quantity to be an int
			remove_filter('woocommerce_stock_amount', 'intval');

			// Add a filter, that validates the quantity to be a float
			add_filter('woocommerce_stock_amount', 'floatval');

			// Add unit price fix when showing the unit price on processed orders
			add_filter('woocommerce_order_amount_item_total', 'fme_qifw_unit_price_fix', 10, 5);
		}

		public function fme_qifw_min_decimal( $val ) {
			return 0.01;
		}
		
		public function fme_qifw_allow_decimal( $val ) {
			return 0.01;
		}

		public function fme_qifw_unit_price_fix( $price, $order, $item, $inc_tax = false, $round = true ) {
			$qty = ( !empty( $item['qty'] ) && 0 != $item['qty'] ) ? $item['qty'] : 1;
			if ($inc_tax) {
				$price = ( $item['line_total'] + $item['line_tax'] ) / $qty;
			} else {
				$price = $item['line_total'] / $qty;
			}
			$price = $round ? round( $price, 2 ) : $price;
			return $price;
		}
	} 

	new Fme_QIFW_Quanity_Increment();
}
