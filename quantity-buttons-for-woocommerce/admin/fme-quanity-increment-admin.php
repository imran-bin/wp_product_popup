<?php 
if ( ! defined( 'WPINC' ) ) {
	wp_die();
}
if ( !class_exists( 'Fme_QIFW_Admin' ) ) { 

	class Fme_QIFW_Admin extends Fme_QIFW_Quanity_Increment {
		
		public function __construct() {

			add_filter('woocommerce_settings_tabs_array', array( $this, 'fme_QIFW_woocommerce_settings_tabs_array' ), 50 );//admin     
			add_action( 'woocommerce_settings_fme_qifw_tab', array( $this, 'fme_QIFW_admin_settings' )); //admin
			add_action( 'admin_enqueue_scripts', array( $this, 'FME_QIFW_admin_scripts' ) );
			add_action( 'wp_loaded', array( $this, 'fme_qifw_custom_posttype' )); 
			
			add_action('wp_ajax_fme_qifw_save_settings', array( $this, 'fme_qifw_save_settings' ));

			add_action('wp_ajax_fme_qifw_get_products_array', array( $this, 'fme_qifw_get_products_array' ));
					
			add_action('wp_ajax_fme_qifw_save_general_settings', array( $this, 'fme_qifw_save_general_settings' ));

			add_action('wp_ajax_fme_qifw_update_settings', array( $this, 'fme_qifw_update_settings' ));
			
			add_action('wp_ajax_fme_qifw_update_general_settings', array( $this, 'fme_qifw_update_general_settings' ));
			
			add_action('wp_ajax_fme_qifw_delete_general_settings', array( $this, 'fme_qifw_delete_general_settings' ));
		}
		
		public function fme_qifw_get_products_array() {
			global $wpdb;
			$json = array();

			$serch_term  = isset($_REQUEST['term']) ? filter_var($_REQUEST['term']) : '';
			if (!$serch_term) {
				echo json_encode($json);
				wp_die();
			} else {
				$result = $wpdb->get_results($wpdb->prepare('SELECT ID, post_title FROM ' . $wpdb->posts . " WHERE post_title LIKE %s AND post_type = 'product' AND post_status = 'publish' LIMIT 100  ", '%' . $serch_term . '%'));
				foreach ($result as $row) {
					$json[] = array( 'id'=>$row->ID, 'text'=>$row->post_title );
				}
			}
			echo json_encode($json);
			wp_die();
		}
		public function fme_qifw_save_settings() {
			check_ajax_referer('fme_qifw_ajax_nonce', 'fme_qifw_nonce');
			if (! current_user_can('manage_woocommerce')) {
				wp_die();
			}

			$fme_enable_disable = isset($_REQUEST['fme_enable_disable']) ? filter_var($_REQUEST['fme_enable_disable']) : '';
			$fme_qifw_minimum = isset($_REQUEST['fme_qifw_minimum']) ? filter_var($_REQUEST['fme_qifw_minimum']) : '';
			$fme_qifw_maxmium = isset($_REQUEST['fme_qifw_maxmium']) ? filter_var($_REQUEST['fme_qifw_maxmium']) : '';
			$fme_qifw_step = isset($_REQUEST['fme_qifw_step']) ? filter_var($_REQUEST['fme_qifw_step']) : '';
			$fme_qifw_readonly = isset($_REQUEST['fme_qifw_readonly']) ? filter_var($_REQUEST['fme_qifw_readonly']) : '';
			$fme_qifw_priority = isset($_REQUEST['fme_qifw_priority']) ? filter_var($_REQUEST['fme_qifw_priority']) : '';
			$fme_product_category_selected = isset($_REQUEST['fmeproductcategory']) ? filter_var($_REQUEST['fmeproductcategory']) : '';
			if (isset($_REQUEST['fme_selected_pro_cat']) && is_array($_REQUEST['fme_selected_pro_cat'])) {
				// if its not an array then array_map function will through an error in php version 8+
				$fme_selected_items = isset($_REQUEST['fme_selected_pro_cat']) ? array_map('filter_var', $_REQUEST['fme_selected_pro_cat']) : '';
			} else { 
			
				$fme_selected_items = isset($_REQUEST['fme_selected_pro_cat']) ? filter_var($_REQUEST['fme_selected_pro_cat']) : '';
			}
			$fme_selected_user_role = isset($_REQUEST['fme_selected_user_role']) ? array_map('filter_var', $_REQUEST['fme_selected_user_role']) : '';


			$fme_post_id = wp_insert_post(
				array(
					'comment_status'    =>  'closed',
					'ping_status'       =>  'closed',
					'post_author'       =>  'fme_qifw_woocomerce',
					'post_name'         =>  'fme_qifw_woocomerce',
					'post_title'        =>  'fme_qifw_woocomerce',
					'post_status'       =>  'publish',
					'post_type'         =>  'fme_qifw_woocomerce',
				)
			);

			if ($fme_post_id) {

				update_post_meta($fme_post_id, 'fme_enable_disable_key', $fme_enable_disable);

				update_post_meta($fme_post_id, 'fme_qifw_priority', $fme_qifw_priority);

				update_post_meta($fme_post_id, 'fme_qifw_minimum_key', $fme_qifw_minimum);

				update_post_meta($fme_post_id, 'fme_qifw_maxmium_key', $fme_qifw_maxmium);

				update_post_meta($fme_post_id, 'fme_qifw_step', $fme_qifw_step);
				update_post_meta($fme_post_id, 'fme_qifw_readonly', $fme_qifw_readonly);

				
				update_post_meta($fme_post_id, 'fme_product_category_selected_key', $fme_product_category_selected);

				update_post_meta($fme_post_id, 'fme_selected_items_key', $fme_selected_items);

				update_post_meta($fme_post_id, 'fme_selected_user_role_key', $fme_selected_user_role);

			}

			wp_die();
		}

		public function fme_qifw_save_general_settings() {

			check_ajax_referer('fme_qifw_ajax_nonce', 'fme_qifw_nonce');
			if (! current_user_can('manage_woocommerce')) {
				wp_die();
			}
			$fme_qifw_button_style = isset($_REQUEST['fme_qifw_button_style']) ? filter_var($_REQUEST['fme_qifw_button_style']) : '';
			
			$fme_qifw_button_label = isset($_REQUEST['fme_qifw_button_label']) ? filter_var($_REQUEST['fme_qifw_button_label']) : '';

			$fme_qifw_Quantity_field_width = isset($_REQUEST['fme_qifw_Quantity_field_width']) ? filter_var($_REQUEST['fme_qifw_Quantity_field_width']) : '';

			$fme_qifw_button_font_size = isset($_REQUEST['fme_qifw_button_font_size']) ? filter_var($_REQUEST['fme_qifw_button_font_size']) : '';

			$fme_qifw_button_font_color = isset($_REQUEST['fme_qifw_button_font_color']) ? filter_var($_REQUEST['fme_qifw_button_font_color']) : '';

			$fme_qifw_limit_minimum_error = isset($_REQUEST['fme_qifw_limit_minimum_error']) ? filter_var($_REQUEST['fme_qifw_limit_minimum_error']) : '';

			$fme_qifw_limit_maximum_error = isset($_REQUEST['fme_qifw_limit_maximum_error']) ? filter_var($_REQUEST['fme_qifw_limit_maximum_error']) : '';

			$fme_qifw_save_general_settings_array = array(

				'fme_qifw_button_style'=> $fme_qifw_button_style,
				'fme_qifw_button_label' => $fme_qifw_button_label,
				'fme_qifw_Quantity_field_width'=> $fme_qifw_Quantity_field_width,
				'fme_qifw_button_font_size' => $fme_qifw_button_font_size,
				'fme_qifw_button_font_color'=> $fme_qifw_button_font_color,
				'fme_qifw_limit_maximum_error'=> $fme_qifw_limit_maximum_error,
				'fme_qifw_limit_minimum_error' => $fme_qifw_limit_minimum_error,
			);
			update_option('fme_save_general_settings', $fme_qifw_save_general_settings_array);
		}

		public function fme_qifw_update_settings() {
			check_ajax_referer('fme_qifw_ajax_nonce', 'fme_qifw_nonce');
			if (! current_user_can('manage_woocommerce')) {
				wp_die();
			}
			$fme_post_id = isset($_REQUEST['fme_update_post_id']) ? filter_var($_REQUEST['fme_update_post_id']) : '';
			$fme_enable_disable = isset($_REQUEST['fme_enable_disable']) ? filter_var($_REQUEST['fme_enable_disable']) : '';
			$fme_qifw_minimum = isset($_REQUEST['fme_qifw_minimum']) ? filter_var($_REQUEST['fme_qifw_minimum']) : '';
			$fme_qifw_maxmium = isset($_REQUEST['fme_qifw_maxmium']) ? filter_var($_REQUEST['fme_qifw_maxmium']) : '';
			$fme_qifw_step = isset($_REQUEST['fme_qifw_step']) ? filter_var($_REQUEST['fme_qifw_step']) : '';
			$fme_qifw_readonly = isset($_REQUEST['fme_qifw_readonly']) ? filter_var($_REQUEST['fme_qifw_readonly']) : '';

			$fme_qifw_update_priority = isset($_REQUEST['fme_qifw_update_priority']) ? filter_var($_REQUEST['fme_qifw_update_priority']) : '';

			$fme_product_category_selected = isset($_REQUEST['fmeproductcategory']) ? filter_var($_REQUEST['fmeproductcategory']) : '';

			if (isset($_REQUEST['fme_selected_pro_cat']) && is_array($_REQUEST['fme_selected_pro_cat'])) {
				// if its not an array then array_map function will through an error in php version 8+
				$fme_selected_items = isset($_REQUEST['fme_selected_pro_cat']) ? array_map('filter_var', $_REQUEST['fme_selected_pro_cat']) : '';
			} else {
			
				$fme_selected_items = isset($_REQUEST['fme_selected_pro_cat']) ? filter_var($_REQUEST['fme_selected_pro_cat']) : '';
			}


			$fme_selected_user_role = isset($_REQUEST['fme_selected_user_role']) ? array_map('filter_var', $_REQUEST['fme_selected_user_role']) : '';

			if ('' != $fme_post_id) {

				update_post_meta($fme_post_id, 'fme_enable_disable_key', $fme_enable_disable);

				update_post_meta($fme_post_id, 'fme_qifw_minimum_key', $fme_qifw_minimum);

				update_post_meta($fme_post_id, 'fme_qifw_maxmium_key', $fme_qifw_maxmium);

				update_post_meta($fme_post_id, 'fme_qifw_step', $fme_qifw_step);
				update_post_meta($fme_post_id, 'fme_qifw_readonly', $fme_qifw_readonly);

				update_post_meta($fme_post_id, 'fme_qifw_priority', $fme_qifw_update_priority);

				update_post_meta($fme_post_id, 'fme_product_category_selected_key', $fme_product_category_selected);

				update_post_meta($fme_post_id, 'fme_selected_items_key', $fme_selected_items);

				update_post_meta($fme_post_id, 'fme_selected_user_role_key', $fme_selected_user_role);

			}
			wp_die();
		}

		public function fme_qifw_update_general_settings() {
			check_ajax_referer('fme_qifw_ajax_nonce', 'fme_qifw_nonce');
			if (! current_user_can('manage_woocommerce')) {
				wp_die();
			}
			$fme_post_id = isset($_REQUEST['fme_post_id']) ? filter_var($_REQUEST['fme_post_id']) : '';
			require_once FMEQIFW_PLUGIN_DIR . 'admin/view/templates/fme-qifw-update-page.php' ;
			wp_die();
		}

		public function fme_qifw_delete_general_settings() {

			check_ajax_referer('fme_qifw_ajax_nonce', 'fme_qifw_nonce');
			if (! current_user_can('manage_woocommerce')) {
				wp_die();
			}

			global $wpdb;
			$fme_delete_post_id = isset($_REQUEST['fme_del_id']) ? filter_var($_REQUEST['fme_del_id']) : '';
			if ( 'fme_qifw_woocomerce' === get_post_type( $fme_delete_post_id ) ) {
				wp_delete_post($fme_delete_post_id);
			}
			wp_die();
		}

		public function fme_QIFW_woocommerce_settings_tabs_array( $tabs ) {
			$tabs['fme_qifw_tab'] = __('Quantity Increment', 'FME_QIFW');
			return $tabs;
		}
		public function fme_QIFW_admin_settings() {

			require_once FMEQIFW_PLUGIN_DIR . 'admin/view/fme-qifw-settings-page.php' ;
		}

		public function fme_qifw_custom_posttype() {

			register_post_type( 'fme_qifw_woocomerce',
				array(
					'labels' => array(
						'name' => esc_html__( 'fme_quantity_increment' , 'FME_QIFW'),
						'singular_name' => esc_html__( 'fme_quantity_increment' , 'FME_QIFW'),
					),
					'public' => true,
					'has_archive' => true,
					'rewrite' => array( 'slug' => 'fme_quantity_increment' ),
					'show_in_rest' => true,
					'show_ui' => true,
					'show_in_menu'  => false,

				)
			);
		}

		public function FME_QIFW_admin_scripts() {  

			if (isset($_GET['tab'])) {

				if (is_admin() && 'fme_qifw_tab'== $_GET['tab']) {
					wp_enqueue_script('jquery');
					wp_enqueue_style( 'bootstrap-min-css', plugins_url( 'assets/css/bootstrap.min.css', __FILE__ ), false , 1.0 );
					wp_enqueue_style( 'fme_QIFW_setting_css', plugins_url( 'assets/css/FME_QIFW_Admin.css', __FILE__ ), false , 1.0 );
					wp_enqueue_script( 'bootstrap-min-js', plugins_url( 'assets/js/bootstrap.min.js', __FILE__ ), false, 1.0 );

					wp_enqueue_script( 'jquery-datatable-js', plugins_url( 'assets/js/jquery.dataTables.min.js', __FILE__), false, 1.0 );
					wp_enqueue_script( 'jquery-datatable-semanticui-js', plugins_url( 'assets/js/dataTables.semanticui.min.js', __FILE__), false, 1.0 );
					wp_enqueue_script( 'jquery-datatable-semantic-js', plugins_url( 'assets/js/semantic.min.js', __FILE__ ), false, 1.0 );
					wp_enqueue_style( 'fme_QIFW_semanticui_css', plugins_url( 'assets/css/dataTables.semanticui.min.css', __FILE__), false , 1.0 );
					wp_enqueue_style( 'fme_QIFW_semantic_css', plugins_url('assets/css/semantic.min.css', __FILE__), false , 1.0 );
					wp_enqueue_script( 'fme_QIFW_setting_js', plugins_url( 'assets/js/FME_QIFW_admin.js', __FILE__ ), false, '1.1.4' );
					wp_enqueue_script( 'select2-min-js', plugins_url( 'assets/js/select2.min.js', __FILE__ ), false, 1.0 );
					wp_enqueue_style( 'select2-min-css', plugins_url( 'assets/css/select2.min.css', __FILE__ ), false , 1.0 );
					
					$fme_QIFW_data = array(
						'admin_url' => admin_url('admin-ajax.php'),
						'admin_ajax_nonce' => wp_create_nonce('fme_qifw_ajax_nonce'),
					);
					wp_localize_script('fme_QIFW_setting_js', 'ewcpm_php_vars', $fme_QIFW_data);
					wp_localize_script('fme_QIFW_setting_js', 'ajax_url_add_pq', array( 'ajax_url_add_pq_data' => admin_url('admin-ajax.php') ));
				}
			}
		}
	}

	new Fme_QIFW_Admin();
}
