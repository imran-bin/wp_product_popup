<?php 
if ( ! defined( 'WPINC' ) ) {
	wp_die();
}
if ( !class_exists( 'FME_QIFW_Front_Main' ) ) { 

	class FME_QIFW_Front_Main { 

		public function __construct() {
		 
			add_action('woocommerce_after_shop_loop_item', array( $this, 'action_woocommerce_after_shop_loop_item_title' ), 10, 0);
			add_action('woocommerce_before_add_to_cart_button', array( $this, 'action_woocommerce_after_shop_loop_item_titles' ), 10, 0);
			add_filter('woocommerce_cart_item_quantity', array( $this, 'wc_cart_item_quantity' ), 10, 3);
			add_action('wp_loaded', array( $this, 'fme_enqueue_style_script_front' ));
			add_filter( 'woocommerce_blocks_product_grid_item_html', array( $this, 'wc_add_date_to_gutenberg_block' ), 10, 3);
			add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'wc_validate_add_cart_item' ), 10, 5 );
			// Add the price display under the add-to-cart button on product archive pages
            add_action('woocommerce_before_add_to_cart_button', array($this, 'add_price_under_add_to_cart_archive'), 20);
    		// Add script to update quantity inputs on cart page load
            add_action('wp_footer', array($this, 'add_update_cart_quantities_script'), 100);
		}
        
        public function add_update_cart_quantities_script() {
            if (is_cart()) {
                echo "<script type='text/javascript'>
                    jQuery(document).ready(function() {
                        updateCartQuantities();
                    });
                </script>";
            }
        }
        
        public function add_price_under_add_to_cart_archive() {
            global $product;
            $unit_price = $product->get_price();
            echo '<div class="product-price-container">';
            echo '<input type="hidden" class="product_unit_price" value="' . esc_attr($unit_price) . '">';
            echo '<p>' . __('Total Price:', 'FME_QIFW') . ' <span class="product_total_price">' . esc_html($unit_price) . '</span></p>';
            echo '</div>';
        }
		

		public function wc_validate_add_cart_item( $passed, $product_id, $quantity, $variation_id = '', $variations = '' ) {

			$terms = get_the_terms ( $product_id, 'product_cat' );
			$category_ids=array();
			foreach ($terms as $key => $term) {
				
				array_push($category_ids, $term->term_id);
			}
			$category_id = $terms[0]->term_id;
			$fme_save_general_settings = get_option('fme_save_general_settings'); 
			$fme_qifw_rules = $this->fme_qifw_get_rules();
			$fme_qifw_user_role = wp_get_current_user()->roles;
			$fme_qifw_user_role = implode(',', $fme_qifw_user_role);
			$fme_qifw_valid = false;
			$priority = -1;
			$all_products=array();
			
			/***********************Fme_qifw_rules*****************************/
			
			foreach ($fme_qifw_rules as $key => $rule) {

				if ('fme_enable'==$rule['fme_enable_disable']) {
					$fme_valid = false;
					if ('fme_qifw_product' == $rule['fme_product_category_selected']) {
						$fme_qifw_needle_id = array( $product_id );
					} else if ('fme_qifw_category' ==$rule['fme_product_category_selected']) {
						$fme_qifw_needle_id = $category_ids;
					} else if ('all_products' ==$rule['fme_product_category_selected']) {
						global $post;
						// $fme_QIFW_files_product = array(
						//  'post_status' => 'publish',
						//  'ignore_sticky_posts' => 1,
						//  'posts_per_page' => -1,
						//  'orderby' => 'title',
						//  'order' => 'ASC',
						//  'post_type' => array( 'product' ),
						// );
						// $fme_QIFW_files_woo_product = get_posts($fme_QIFW_files_product);
						// foreach ($fme_QIFW_files_woo_product as $value) {
						//  array_push($all_products, $value->ID);
						// }
						$fme_qifw_needle_id = array( $product_id );
						// $rule['fme_selected_items'] = $all_products;

					} 

					if ( ( 'all_products' ==$rule['fme_product_category_selected'] ) || ( is_array($rule['fme_selected_items']) && !empty(array_intersect($fme_qifw_needle_id, $rule['fme_selected_items'])) )) {

						if (-1 == $priority) {
							
							$priority = $key+1;

						} else {
							$final_rule_priority=$fme_qifw_rules[$priority-1]['fme_qifw_priority'];
							if ($final_rule_priority>$rule['fme_qifw_priority']) {
								$priority = $key+1;
							}
						}
					}
				}

			}
			if (-1 != $priority ) {

				if ('' == $fme_qifw_rules[$priority-1]['fme_selected_user_role']) {
					$fme_qifw_rules[$priority-1]['fme_selected_user_role']=array();

				}
				if ( ( is_array($fme_qifw_rules[$priority-1]['fme_selected_user_role']) && in_array($fme_qifw_user_role, $fme_qifw_rules[$priority-1]['fme_selected_user_role']) ) || empty($fme_qifw_rules[$priority-1]['fme_selected_user_role'])) {
					
					$fme_qifw_rule_to_be_applied = $fme_qifw_rules[$priority-1];
					if ( ( is_front_page() && 'true' == $fme_qifw_rules[$priority-1]['fme_is_home'] )  || ( is_singular('product') &&  'true' ==  $fme_qifw_rule[$priority-1]['fme_is_single'] )  || ( is_shop()  && 'true' ==  $fme_qifw_rules[$priority-1]['fme_is_shop'] )  || ( is_cart()  && 'true' ==  $fme_qifw_rules[$priority-1]['fme_is_cart'] ) ) {  
						$fme_valid = true;
						
					} 
				}
			}
			$viewdata = $fme_qifw_rule_to_be_applied;

					
			if ('' != $viewdata['fme_qifw_minimum']) {
				$fme_minimum_qty = $viewdata['fme_qifw_minimum'];
			} else {
				$fme_minimum_qty = '1';
			}
			if ('' != $viewdata['fme_qifw_maxmium']) {
				$fme_maxmium_qty = $viewdata['fme_qifw_maxmium'];
			} else {
				$fme_maxmium_qty = '9999999999';
			}
			if ('' != $viewdata['fme_qifw_step']) {
				$fme_step = $viewdata['fme_qifw_step'];
			} else {
				$fme_step = '1';
			}
				
					$curr_quantity=0;
			foreach ( WC()->cart->get_cart() as $cart_item ) { 
				if ( in_array( $product_id, array( $cart_item['product_id'], $cart_item['variation_id'] ) )) {
					$curr_quantity =  $cart_item['quantity'];
					break; // stop the loop if product is found
				}
			}
			$total_qty=$quantity+$curr_quantity;
			if ( $fme_maxmium_qty == $curr_quantity || ( $total_qty > $fme_maxmium_qty )) {
				$passed = false;
				if ($fme_maxmium_qty == $curr_quantity) {
					$fme_note='Maximum quantity reached';   
				} else {
					$fme_note='Maximum allowed quantity is <b>' . $fme_maxmium_qty . '</b>';
				}
				wc_add_notice( __( $fme_note, 'textdomain' ), 'error' );
			}
			return $passed;
		}
		
		public function wc_add_date_to_gutenberg_block( $html, $data, $product ) {

			$output = "
			<li class=\"wc-block-grid__product\">
			<a href=\"{$data->permalink}\" class=\"wc-block-grid__product-link\">
			{$data->image}
			{$data->title}
			</a>";
			$output2= "
			{$data->price}
			{$data->rating}
			{$data->badge}";
			
			$output3=$this->quantity_buttons($product) . "
			{$data->button}
			</li>
			";
			$total = $output . $output2 . $output3;
			return $total;
		}

		private function quantity_buttons( $product ) { 

			$html = '';
			$product_id = $product->get_id();
			$pro_id=$product->get_id();
			$fme_save_general_settings = get_option('fme_save_general_settings'); 
			$fme_qifw_rules = $this->fme_qifw_get_rules();
			$fme_qifw_user_role = wp_get_current_user()->roles;
			$fme_qifw_user_role = implode(',', $fme_qifw_user_role);
			$fme_qifw_valid = false;
			$priority = -1;
			$all_products=array();
			
			/***********************Fme_qifw_rules*****************************/
			
			foreach ($fme_qifw_rules as $key => $rule) {

				if ('fme_enable'==$rule['fme_enable_disable']) {
					$fme_valid = false;
					if ('fme_qifw_product' == $rule['fme_product_category_selected']) {
						$fme_qifw_needle_id = $product_id;
					} else if ('fme_qifw_category' ==$rule['fme_product_category_selected']) {
						$fme_qifw_needle_id = $category_id;
					} else if ('all_products' ==$rule['fme_product_category_selected']) {
						global $post;
						$fme_QIFW_files_product = array(
							'post_status' => 'publish',
							'ignore_sticky_posts' => 1,
							'posts_per_page' => -1,
							'orderby' => 'title',
							'order' => 'ASC',
							'post_type' => array( 'product' ),
						);
						$fme_QIFW_files_woo_product = get_posts($fme_QIFW_files_product);
						foreach ($fme_QIFW_files_woo_product as $value) {
							array_push($all_products, $value->ID);
						}
						$fme_qifw_needle_id = $product_id;
						$rule['fme_selected_items'] = $all_products;
					} 

					if ( is_array($rule['fme_selected_items']) && in_array($fme_qifw_needle_id, $rule['fme_selected_items'])) {
						if (-1 == $priority) {
							$priority = $key+1;
						} else {
							$final_rule_priority=$fme_qifw_rules[$priority-1]['fme_qifw_priority'];
							if ($final_rule_priority>$rule['fme_qifw_priority']) {
								$priority = $key+1;
							}
						}
					}
				}

			}
			if (-1 != $priority ) {
				if ('' == $fme_qifw_rules[$priority-1]['fme_selected_user_role']) {
					$fme_qifw_rules[$priority-1]['fme_selected_user_role']=array();
				}
				if ( ( is_array($fme_qifw_rules[$priority-1]['fme_selected_user_role']) && in_array($fme_qifw_user_role, $fme_qifw_rules[$priority-1]['fme_selected_user_role']) ) || empty($fme_qifw_rules[$priority-1]['fme_selected_user_role'])) {
					$fme_qifw_rule_to_be_applied = $fme_qifw_rules[$priority-1];
					if ( ( is_front_page() && 'true' == $fme_qifw_rules[$priority-1]['fme_is_home'] )  || ( is_singular('product') &&  'true' ==  $fme_qifw_rule[$priority-1]['fme_is_single'] )  || ( is_shop()  && 'true' ==  $fme_qifw_rules[$priority-1]['fme_is_shop'] )  || ( is_cart()  && 'true' ==  $fme_qifw_rules[$priority-1]['fme_is_cart'] ) ) {  
						include 'fme-quantity-plus-minus.php'; 
						$fme_valid = true;
					} 
				}
			}
			$viewdata = $fme_qifw_rule_to_be_applied;

			/****************************Wc_cart_item_quantity****************************************/
			if ($fme_valid) {
				if (!$product->managing_stock() && !$product->is_in_stock()) { ?>
					<p><?php echo esc_html__('This product is out of stock. It can be purchased by custom made order.', 'FME_QIFW'); ?></p>
					<?php
				} else {
					if ( '' != $fme_save_general_settings['fme_qifw_button_style']) {
						$fme_style_button_color = 'background-color: ' . esc_attr($fme_save_general_settings['fme_qifw_button_style']) . ';';
					} else {
						$fme_style_button_color = '';
					}
					if ( '' != $fme_save_general_settings['fme_qifw_button_font_color'] && isset($fme_save_general_settings['fme_qifw_button_font_color']) ) {
						$fme_qifw_button_font_color = 'color: ' . esc_attr($fme_save_general_settings['fme_qifw_button_font_color']) . ';';
					} else {
						$fme_qifw_button_font_color = 'white;';
					}
					$fme_style_button_height = 'height: inherit;';
					$fme_style_button_width = 'font-family: monospace;';
					if ( '' != $fme_save_general_settings['fme_qifw_Quantity_field_width']) {
						$fme_style_button_fieldwidth = 'width: ' . esc_attr($fme_save_general_settings['fme_qifw_Quantity_field_width']) . 'px;';
					} else {
						$fme_style_button_fieldwidth = '';
					}
					if ( '' != $fme_save_general_settings['fme_qifw_button_font_size']) {
						$fme_qifw_button_font_size = 'font-size:' . esc_attr($fme_save_general_settings['fme_qifw_button_font_size']) . 'px;';
					} else {
						$fme_qifw_button_font_size = '';
					}
					if ('' != $viewdata['fme_qifw_minimum']) {
						$fme_minimum_qty = $viewdata['fme_qifw_minimum'];
					} else {
						$fme_minimum_qty = '1';
					}
					if ('' != $viewdata['fme_qifw_maxmium']) {
						$fme_maxmium_qty = $viewdata['fme_qifw_maxmium'];
					} else {
						$fme_maxmium_qty = '9999999999';
					}
					if ('' != $viewdata['fme_qifw_step']) {
						$fme_step = $viewdata['fme_qifw_step'];
					} else {
						$fme_step = '1';
					}
					if ('' != $viewdata['fme_qifw_readonly'] && 'on' == $viewdata['fme_qifw_readonly']) {
						$fme_readonly = 'readonly';
					} else {
						$fme_readonly = '';
					}

				}

				/********************************Html to return****************************/
				
				if ('' != $product->get_price() && 'instock' == $product->get_stock_status()) {

					if ('simple' == $product->get_type()) {  
						if ('' != $fme_save_general_settings['fme_qifw_button_label']) {
							$html .='<div style="display:flex; flex-direction: column; margin-right:10px;"><div style="width: 100%;"><center><strong>' . esc_attr($fme_save_general_settings['fme_qifw_button_label'], 'FME_QIFW') . '</center></strong></div>';
						}   
						?>
						<script type="text/javascript">
							jQuery(document).ready(function(){
								var min_qty = jQuery('#quantityvals<?php echo esc_attr($pro_id); ?>').val();
								jQuery('a[data-product_id="<?php echo esc_attr($pro_id); ?>"]').attr('data-quantity', min_qty);
							});
						</script>
						<?php

						$html .= '<div style="display: flex; flex-direction: row; justify-content: center;">';
						$html .= '<input type="hidden" name="stock_quantity" id="stockquantitypq' . intval($product->get_id()) . '
						value=" ' . filter_var($product->get_stock_quantity()) . ' ">';

						$html .='<input type="hidden" name="stepintervalpq" id="pq-stepintervals' . intval($product->get_id()) . '
						value=" ' . esc_attr($viewdata['fme_qifw_step']) . '">';

						$html .='<input type="hidden" name="fme_qifw_ptype" id="fme_qifw_ptype" value="' . esc_attr($product->get_type()) . '">';

						$html .='<div>
						<input class="pq-minus fme_qbtns" style="' . esc_attr($fme_qifw_button_font_size) . '' . esc_attr($fme_style_button_color) . ' ' . esc_attr($fme_style_button_height) . ' ' . esc_attr($fme_style_button_width) . ' ' . esc_attr($fme_qifw_button_font_color) . '" id="pq-minus' . esc_attr($pro_id) . '" type="button" value="-"
						onclick="fme_shop_pq_minus(' . esc_attr($pro_id) . ',' . esc_attr($fme_minimum_qty) . ',' . esc_attr($fme_maxmium_qty) . ',' . esc_attr($fme_step) . ');">
						</div>';

						$html .='<div class="fme_quantity">       
						<input type="text" ' . esc_attr($fme_readonly) . ' style=" ' . esc_attr($fme_style_button_fieldwidth) . ' " id="quantityval' . intval($pro_id) . '"
						class="input-text qty fme_qifw_min_quantity"
						step="" min="" max="" name="quantity"
						value=" ' . esc_attr($fme_minimum_qty) . '" title="Qty" size="4"
						inputmode="numeric"
						onkeyup="fme_upd_qty(' . esc_attr($pro_id) . ',' . esc_attr($fme_minimum_qty) . ',' . esc_attr($fme_maxmium_qty) . ',' . esc_attr($fme_step) . ');"
						>
						</div>';
						$html .='<div>
						<input class="pq-plus fme_qbtns" style="' . esc_attr($fme_qifw_button_font_size) . ' ' . esc_attr($fme_style_button_color) . '' . esc_attr($fme_style_button_height) . '' . esc_attr($fme_style_button_width) . '' . esc_attr($fme_qifw_button_font_color) . '" type="button" value="+" id="pq-plus' . esc_attr($pro_id) . '" onclick="fme_pq_shop_plus(' . esc_attr($pro_id) . ',' . esc_attr($fme_minimum_qty) . ',' . esc_attr($fme_maxmium_qty) . ',' . esc_attr($fme_step) . ');">
						</div>
						</div>'; 

						if ('' == $fme_save_general_settings['fme_qifw_limit_maximum_error']) {

							$html .='<div id="errormsg ' . intval($product->get_id()) . '" class="errormsg" style="display:none;">
							' . esc_html__('Quantity can not add more than maximum value!', 'FME_QIFW') . '
							<input type="show" id="fme_qifw_maxmium_error_msg' . intval($product->get_id()) . '" name="fme_qifw_maxmium_error_msg" value="">
							</div>'; 

						} else {

							$html .='<div id="errormsg' . intval($product->get_id()) . '" class="errormsg" style="display:none;">
							' . esc_attr($fme_save_general_settings['fme_qifw_limit_maximum_error']) . '
							<input type="hidden" id=fme_qifw_maxmium_error_msg' . intval($product->get_id()) . '" name="fme_qifw_maxmium_error_msg" value="">
							</div>' ;

						}
						if ('' == $fme_save_general_settings['fme_qifw_limit_minimum_error']) {

							$html .='<div id="errormsgminimum' . intval($product->get_id()) . '" class="errormsg" style="display:none;">
							' . esc_html__('Quantity can not Less than minimum value!', 'FME_QIFW') . '
							<input type="hidden" id="fme_qifw_minimum_error_msg' . intval($product->get_id()) . '" name="fme_qifw_minimum_error_msg" value="">
							</div>'; 

						} else {

							$html .='<div id="errormsgminimum' . intval($product->get_id()) . '" class="errormsg" style="display:none;">
								' . esc_attr($fme_save_general_settings['fme_qifw_limit_minimum_error']) . '
								<input type="hidden" id=fme_qifw_minimum_error_msg' . intval($product->get_id()) . '" name="fme_qifw_minimum_error_msg" value="">
								</div>' ;
						}

						$html .= '</div>';
						
						return $html;
					}
				}
			}
		}


		public function wc_cart_item_quantity( $product_quantity, $cart_item_key, $cart_item ) {

			$qty_position=strpos($product_quantity, 'quantity');
			$start_of_html = substr($product_quantity, 0, $qty_position);
			$end_of_html = substr($product_quantity, $qty_position);
			if ('"quantity"' == substr($end_of_html, 0, 10)) { // if condition for woodmart theme
				$end_of_html=substr_replace($end_of_html, 'quantity', 0, 10);
			}
			$product_quantity = $start_of_html . 'fme_' . $end_of_html;

			$insert_custom_class_start_index = strpos($product_quantity, 'qty');
			$html_custom_class_start = substr($product_quantity, 0, $insert_custom_class_start_index + 3);
			$html_custom_class_end = substr($product_quantity, $insert_custom_class_start_index + 3);
			$product_quantity = $html_custom_class_start . ' fme_qifw_min_quantity' . $html_custom_class_end;

			$input_type_start_index = strpos($product_quantity, 'type="number"');
			$html_start_input_type = substr($product_quantity, 0, $input_type_start_index + 6);
			$html_end_input_type = substr($product_quantity, $input_type_start_index + 12);
			$product_quantity = $html_start_input_type . 'text' . $html_end_input_type;

			$product_id = $cart_item['product_id'];
			$terms = get_the_terms ( $product_id, 'product_cat' );
			$category_ids=array();
			foreach ($terms as $key => $term) {
				
				array_push($category_ids, $term->term_id);
			}
			$category_id = $terms[0]->term_id;
			$fme_qifw_rules = $this->fme_qifw_get_rules();
			$fme_qifw_user_role = wp_get_current_user()->roles;
			$fme_qifw_user_role = implode(',', $fme_qifw_user_role);
			$fme_qifw_valid = false;
			$priority = -1;
			$all_products=array();

			foreach ($fme_qifw_rules as $key => $rule) {

				if ('fme_enable'==$rule['fme_enable_disable']) {
					if ('fme_qifw_product' == $rule['fme_product_category_selected']) {
						$fme_qifw_needle_id = array( $product_id );
					} else if ('fme_qifw_category' ==$rule['fme_product_category_selected']) {
						$fme_qifw_needle_id = $category_ids;
					} else if ('all_products' ==$rule['fme_product_category_selected']) {
						global $post;
						// $fme_QIFW_files_product = array(
						//  'post_status' => 'publish',
						//  'ignore_sticky_posts' => 1,
						//  'posts_per_page' => -1,
						//  'orderby' => 'title',
						//  'order' => 'ASC',
						//  'post_type' => array( 'product' ),
						// );
						// $fme_QIFW_files_woo_product = get_posts($fme_QIFW_files_product);
						// foreach ($fme_QIFW_files_woo_product as $value) {
						//  array_push($all_products, $value->ID);
						// }
						$fme_qifw_needle_id = array( $product_id );
						// $rule['fme_selected_items'] = $all_products;
					} 

					if ( ( 'all_products' ==$rule['fme_product_category_selected'] ) || ( is_array($rule['fme_selected_items']) && !empty(array_intersect($fme_qifw_needle_id, $rule['fme_selected_items'])) )) {
						if (-1 == $priority) {
							$priority = $key+1;
						} else {
							$final_rule_priority = $fme_qifw_rules[$priority-1]['fme_qifw_priority'];
							if ($final_rule_priority > $rule['fme_qifw_priority']) {
								$priority = $key+1;
							}
						}
					}
				}
					
			}
			if (-1 != $priority ) {
				if ('' == $fme_qifw_rules[$priority-1]['fme_selected_user_role']) {
					$fme_qifw_rules[$priority-1]['fme_selected_user_role']=array();
				}
				if ( ( is_array($fme_qifw_rules[$priority-1]['fme_selected_user_role']) && in_array($fme_qifw_user_role, $fme_qifw_rules[$priority-1]['fme_selected_user_role']) ) || empty($fme_qifw_rules[$priority-1]['fme_selected_user_role'])) {
					$fme_qifw_rule_to_be_applied = $fme_qifw_rules[$priority-1];
					if (is_cart()) {
						$product = wc_get_product( $cart_item['product_id'] );
						include 'fme-quantity-plus-minus.php'; 
					} 
				}
			} else {
				
				return $product_quantity;   
			}
		}

		public function fme_enqueue_style_script_front() {

			wp_enqueue_script('jquery');
			wp_enqueue_style( 'fme-template-css', plugins_url( 'assets/css/fme_template.css', __FILE__ ), false , 1.0 );
			wp_enqueue_script( 'colorpickerjs', plugins_url( 'assets/js/jscolor.js', __FILE__ ), false, 1.0 );
			wp_register_script( 'fme_qifw_front_js', plugins_url( 'assets/js/fme_quantity.js', __FILE__ ), false, '1.1.3' );
			wp_localize_script('fme_qifw_front_js', 'ajax_url', array( 'ajax_url' => admin_url('admin-ajax.php') ));
			wp_enqueue_script( 'fme_qifw_front_js');
		}

		public function action_woocommerce_after_shop_loop_item_title() {
			
				$this->main_fun('plus-minus');
		}
		public function action_woocommerce_after_shop_loop_item_titles() {

			$this->main_fun('plus-minus');
		}
		public function main_fun( $name_list ) {

			global $product;
			$product_id = $product->get_id();
			$terms = get_the_terms ( $product_id, 'product_cat' );
			$category_ids=array();
			foreach ($terms as $key => $term) {
				
				array_push($category_ids, $term->term_id);
			}
			
			$category_id = $terms[0]->term_id;
			$fme_qifw_rules = $this->fme_qifw_get_rules();
			$fme_qifw_user_role = wp_get_current_user()->roles;
			$fme_qifw_user_role = implode(',', $fme_qifw_user_role);
			$fme_qifw_valid = false;
			$priority = -1;
			$all_products=array();

			foreach ($fme_qifw_rules as $key => $rule) {

				if ('fme_enable'==$rule['fme_enable_disable']) {

					if ('fme_qifw_product' == $rule['fme_product_category_selected']) {
						$fme_qifw_needle_id = array( $product_id );
					} else if ('fme_qifw_category' ==$rule['fme_product_category_selected']) {
						$fme_qifw_needle_id = $category_ids;
					} else if ('all_products' ==$rule['fme_product_category_selected']) {
						global $post;
						$fme_QIFW_files_product = array(
							'post_status' => 'publish',
							'ignore_sticky_posts' => 1,
							'posts_per_page' => -1,
							'orderby' => 'title',
							'order' => 'ASC',
							'post_type' => array( 'product' ),
						);
						// $fme_QIFW_files_woo_product = get_posts($fme_QIFW_files_product);
						// foreach ($fme_QIFW_files_woo_product as $value) {
						//  array_push($all_products, $value->ID);
						// }
						$fme_qifw_needle_id = array( $product_id );
						// $rule['fme_selected_items'] = $all_products;
					} 
						
					if ( ( 'all_products' ==$rule['fme_product_category_selected'] ) || ( is_array($rule['fme_selected_items']) && !empty(array_intersect($fme_qifw_needle_id, $rule['fme_selected_items'])) )) {
					
						if (-1 == $priority) {
							$priority = $key+1;
						} else {
							$final_rule_priority=$fme_qifw_rules[$priority-1]['fme_qifw_priority'];
							if ($final_rule_priority>$rule['fme_qifw_priority']) {
								$priority = $key+1;
							}
						}
					}
				}
					
			}
			
			if (-1 != $priority ) {
				if ('' == $fme_qifw_rules[$priority-1]['fme_selected_user_role']) {
					$fme_qifw_rules[$priority-1]['fme_selected_user_role']=array();
				}
				if ( ( is_array($fme_qifw_rules[$priority-1]['fme_selected_user_role']) && in_array($fme_qifw_user_role, $fme_qifw_rules[$priority-1]['fme_selected_user_role']) ) || empty($fme_qifw_rules[$priority-1]['fme_selected_user_role'])) {
					$fme_qifw_rule_to_be_applied = $fme_qifw_rules[$priority-1];
					$theme = wp_get_theme();
					
						include 'fme-quantity-plus-minus.php'; 
				}
			}
		}


		public function fme_qifw_get_rules() {
			global $post;
			global $woocommerce;
			$fme_qifw_query_args = array(
				'post_type'=> 'fme_qifw_woocomerce',
				'orderby'    => 'ID',
				'post_status' => 'publish',
				'order'    => 'ASC',
				'fields'    => 'ids',
				'posts_per_page' => -1, // this will retrive all the post that is published 
			);
			$fme_qifw_get_all_rules = new WP_Query( $fme_qifw_query_args );
			$fme_qifw_rules_array = array();
			foreach ($fme_qifw_get_all_rules->get_posts() as $key => $fme_post_id) {
				$fme_enable_disable = get_post_meta($fme_post_id, 'fme_enable_disable_key', true);
				$fme_qifw_minimum = get_post_meta($fme_post_id, 'fme_qifw_minimum_key', true);
				$fme_qifw_maxmium = get_post_meta($fme_post_id, 'fme_qifw_maxmium_key', true);
				$fme_qifw_step = get_post_meta($fme_post_id, 'fme_qifw_step', true);
				$fme_qifw_readonly = get_post_meta($fme_post_id, 'fme_qifw_readonly', true);
				$fme_product_category_selected= get_post_meta($fme_post_id, 'fme_product_category_selected_key', true);
				$fme_selected_items = get_post_meta($fme_post_id, 'fme_selected_items_key', true);
				$fme_selected_user_role = get_post_meta($fme_post_id, 'fme_selected_user_role_key', true);
				$fme_qifw_priority = get_post_meta($fme_post_id, 'fme_qifw_priority', true);

				$fme_qifw_rule = array(
					'fme_rule_id' => $key+1,
					'fme_enable_disable' => $fme_enable_disable,
					'fme_qifw_minimum' => $fme_qifw_minimum,
					'fme_qifw_maxmium' => $fme_qifw_maxmium,
					'fme_qifw_step' => $fme_qifw_step,
					'fme_qifw_readonly' => $fme_qifw_readonly,
					'fme_qifw_priority' => $fme_qifw_priority,
					'fme_product_category_selected' => $fme_product_category_selected,
					'fme_selected_items'=> $fme_selected_items,
					'fme_selected_user_role'=> $fme_selected_user_role,
				);
				array_push($fme_qifw_rules_array, $fme_qifw_rule);
			}   

			return $fme_qifw_rules_array;
		}
	}

	$GLOBALS['Fme_QIFW_Front_Main'] = new FME_QIFW_Front_Main();
}

