<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
} 
$pro_id = $product_id;
$viewdata = $fme_qifw_rule_to_be_applied;
$fme_save_general_settings = get_option('fme_save_general_settings'); 

if (!$product->managing_stock() && !$product->is_in_stock()) { ?>
	<p><?php echo esc_html__('This product is out of stock. It can be purchased by custom made order.', 'FME_QIFW'); ?></p>
	<?php
} else {
	if ( '' != $fme_save_general_settings['fme_qifw_button_style']) {
		$fme_style_button_color = 'background-color: ' . esc_attr($fme_save_general_settings['fme_qifw_button_style']) . '';
	} else {
		$fme_style_button_color = '';
	}
	if ( '' != $fme_save_general_settings['fme_qifw_button_font_color'] && isset($fme_save_general_settings['fme_qifw_button_font_color']) ) {
		$fme_qifw_button_font_color = 'color: ' . esc_attr($fme_save_general_settings['fme_qifw_button_font_color']) . '';
	} else {
		$fme_qifw_button_font_color = 'white';
	}
	$fme_style_button_height = 'height: inherit';
	$fme_style_button_width = 'font-family: monospace';
	if ( '' != $fme_save_general_settings['fme_qifw_Quantity_field_width']) {
		$fme_style_button_fieldwidth = 'width: ' . esc_attr($fme_save_general_settings['fme_qifw_Quantity_field_width']) . 'px';
	} else {
		$fme_style_button_fieldwidth = '';
	}
	if ( '' != $fme_save_general_settings['fme_qifw_button_font_size']) {
		$fme_qifw_button_font_size = 'font-size: ' . esc_attr($fme_save_general_settings['fme_qifw_button_font_size']) . 'px';
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

	if (is_singular('product')) {
		if ('' != $product->get_price() && 'instock' == $product->get_stock_status()) {
			if ('simple' == $product->get_type() || 'variable' == $product->get_type()) {  
echo '<div id="fme-quantity-btns-container" style="display:flex; flex-direction: column; ">';
				if ('' != $fme_save_general_settings['fme_qifw_button_label']) {
					echo '<div id="fme-quantity-btns-name"><center><strong>' . esc_attr($fme_save_general_settings['fme_qifw_button_label'], 'FME_QIFW') . '</strong></center></div>';
				} 
				?>
				<div>
					<style type="text/css">
						.errormsg{
							text-align: start;
						}
						div.quantity{
							display: none !important;
						}
					</style>
					<script type="text/javascript">
						jQuery(document).ready(function() {
							jQuery('div.quantity').hide();
							jQuery('.fmequantitys').next().hide();
							var min_qty = jQuery('#quantityvals<?php echo esc_attr($pro_id); ?>').val();
							jQuery('.qty').attr('value', min_qty);

							var quantityBtnsContainer = jQuery('.single_add_to_cart_button').parent().find('#fme-quantity-btns-container');
							quantityBtnsContainer.css('margin-right', '10px');

							quantityBtnsContainer.find('#fme-quantity-btns-name').css('width', '11rem');
							quantityBtnsContainer.find('#fme-quantity-btns-wrapper').css('justify-content', 'flex-start');
						});
					</script>
					<div id="fme-quantity-btns-wrapper" style="display: flex; flex-direction: row; justify-content: center;">
						<input type="hidden" name="stock_quantity" id="stockquantitypq<?php echo intval($product->get_id()); ?>"
						value="<?php echo filter_var($product->get_stock_quantity()); ?>">
						<input type="hidden" name="stepintervalpq" id="pq-stepintervals<?php echo intval($product->get_id()); ?>"
						value="<?php echo esc_attr($viewdata['fme_qifw_step']); ?>">
						<input type="hidden" name="fme_qifw_ptype" id="fme_qifw_ptype" value="<?php echo esc_attr($product->get_type()); ?>">
						<div style="margin-right: 2px;">
							<input class="pq-minus fme_qbtns" style="<?php echo esc_attr($fme_qifw_button_font_size); ?>;<?php echo esc_attr($fme_style_button_color); ?>;<?php echo esc_attr($fme_style_button_height); ?>;<?php echo esc_attr($fme_style_button_width); ?>;<?php echo esc_attr($fme_qifw_button_font_color); ?>" id="pq-minus<?php echo esc_attr($pro_id); ?>" type="button" value="-"
							onclick="fme_shop_pq_minus(<?php echo esc_attr($pro_id); ?>,<?php echo esc_attr($fme_minimum_qty); ?>,<?php echo esc_attr($fme_maxmium_qty); ?>,<?php echo esc_attr($fme_step); ?>);">
						</div>
						<div class="fme_quantity">       
							<input type="text" <?php echo esc_attr($fme_readonly); ?> style="<?php echo esc_attr($fme_style_button_fieldwidth); ?>; text-align: center;" id="quantityvals<?php echo intval($pro_id); ?>"
							class="input-text qty fme_qifw_min_quantity"
							step="<?php echo esc_attr($viewdata['fme_qifw_step']); ?>" min="<?php echo esc_attr($viewdata['fme_qifw_minimum']); ?>" max="<?php echo esc_attr($viewdata['fme_qifw_maxmium']); ?>" name="quantity"
							value="<?php echo esc_attr($fme_minimum_qty); ?>" title="Qty" size="4"
							inputmode="numeric" 
							onkeyup="fme_upd_qty( <?php echo esc_attr($pro_id); ?>,<?php echo esc_attr($fme_minimum_qty); ?>,<?php echo esc_attr($fme_maxmium_qty); ?>,<?php echo esc_attr($fme_step); ?>);"
							>
						</div>
						<div >     
							<input class="pq-plus fme_qbtns" style="<?php echo esc_attr($fme_qifw_button_font_size); ?>;<?php echo esc_attr($fme_style_button_color); ?>;<?php echo esc_attr($fme_style_button_height); ?>;<?php echo esc_attr($fme_style_button_width); ?>;<?php echo esc_attr($fme_qifw_button_font_color); ?>" type="button" value="+" id="pq-plus<?php echo esc_attr($pro_id); ?>"
							onclick="fme_pq_shop_plus(<?php echo esc_attr($pro_id); ?>,<?php echo esc_attr($fme_minimum_qty); ?>,<?php echo esc_attr($fme_maxmium_qty); ?>,<?php echo esc_attr($fme_step); ?>);">
						</div>
					</div>
					<?php 
					if ('' == $fme_save_general_settings['fme_qifw_limit_maximum_error']) {
						?>
						<div id="errormsg<?php echo intval($product->get_id()); ?>" class="errormsg">
							<input type="hidden" id="fme_qifw_maxmium_error_msg<?php echo intval($product->get_id()); ?>" name="fme_qifw_maxmium_error_msg" value="<?php echo esc_html__('Quantity can not add more than maximum value!', 'FME_QIFW'); ?>">
						</div> 
						<?php
					} else {
						?>
						<div id="errormsg<?php echo intval($product->get_id()); ?>" class="errormsg">
							<input type="hidden" id="fme_qifw_maxmium_error_msg<?php echo intval($product->get_id()); ?>" name="fme_qifw_maxmium_error_msg" value="<?php echo esc_attr($fme_save_general_settings['fme_qifw_limit_maximum_error']); ?>">
						</div> 
						<?php 
					}
					if ('' == $fme_save_general_settings['fme_qifw_limit_minimum_error']) {
						?>
						<div id="errormsgminimum<?php echo intval($product->get_id()); ?>" class="errormsg">
							<input type="hidden" id="fme_qifw_minimum_error_msg<?php echo intval($product->get_id()); ?>" name="fme_qifw_minimum_error_msg" value="<?php echo esc_html__('Quantity can not Less than minimum value!', 'FME_QIFW'); ?>">
						</div> 
						<?php
					} else {
						?>
						<div id="errormsgminimum<?php echo intval($product->get_id()); ?>" class="errormsg">
							<input type="hidden" id="fme_qifw_minimum_error_msg<?php echo intval($product->get_id()); ?>" name="fme_qifw_minimum_error_msg" value="<?php echo esc_attr($fme_save_general_settings['fme_qifw_limit_minimum_error']); ?>">
						</div> 
						<?php 
					}
					?>
					<span class="fmequantitys" id="fmequantitys<?php echo intval($product->get_id()); ?>"></span>
				</div>
			</div>
				<?php

			}
		} 
	} else if (is_cart()) {
			
		if ('' != $product->get_price() && 'instock' == $product->get_stock_status()) {
			if ('simple' == $product->get_type() || 'variable' == $product->get_type()) {  
				?>
				<style type="text/css">
				.quantity input::-webkit-outer-spin-button,
				.quantity input::-webkit-inner-spin-button {
					display: none;
					margin: 0;
				}
				.quantity input.qty {
					appearance: textfield;
					-webkit-appearance: none;
					-moz-appearance: textfield;
				}
				.fme_quantity .minus, .fme_quantity .plus{
					display: none;
				}
				</style>
				<script type="text/javascript">
					jQuery(document).ready( function() {
						var heightOfQuatityField = jQuery('.fme_quantity').height();
						jQuery('.fme_qbtns').css('height', heightOfQuatityField);
						jQuery('.fme_qifw_min_quantity').attr('readonly', 'readonly');
					});
				</script>
				<div style="display: flex; flex-direction: column;">
					<div style="display: flex; flex-direction: row; justify-content: center;">
						<input type="hidden" name="stock_quantity" id="stockquantitypq<?php echo intval($product->get_id()); ?>"
						value="<?php echo filter_var($product->get_stock_quantity()); ?>">
						<input type="hidden" name="stepintervalpq" id="pq-stepintervals<?php echo intval($product->get_id()); ?>"
						value="<?php echo esc_attr($viewdata['fme_qifw_step']); ?>">
						<div>
							<input class="pq-minus fme_qbtns" style="<?php echo esc_attr($fme_qifw_button_font_size); ?>;<?php echo esc_attr($fme_style_button_color); ?>;<?php echo esc_attr($fme_style_button_height); ?>;<?php echo esc_attr($fme_style_button_width); ?>;<?php echo esc_attr($fme_qifw_button_font_color); ?>" id="pq-minus<?php echo esc_attr($pro_id); ?>" type="button" value="-"
							onclick="fme_shop_pq_minus(<?php echo esc_attr($pro_id); ?>,<?php echo esc_attr($fme_minimum_qty); ?>,<?php echo esc_attr($fme_maxmium_qty); ?>,<?php echo esc_attr($fme_step); ?>);">
						</div>
						<?php echo filter_var($product_quantity); ?>
						<div>
							<input class="pq-plus fme_qbtns" style="<?php echo esc_attr($fme_qifw_button_font_size); ?>;<?php echo esc_attr($fme_style_button_color); ?>;<?php echo esc_attr($fme_style_button_height); ?>;<?php echo esc_attr($fme_style_button_width); ?>;<?php echo esc_attr($fme_qifw_button_font_color); ?>" type="button" value="+" id="pq-plus<?php echo esc_attr($pro_id); ?>"
							onclick="fme_pq_shop_plus(<?php echo esc_attr($pro_id); ?>,<?php echo esc_attr($fme_minimum_qty); ?>,<?php echo esc_attr($fme_maxmium_qty); ?>,<?php echo esc_attr($fme_step); ?>);">
						</div>					
					</div>
					<?php 
					if ('' == $fme_save_general_settings['fme_qifw_limit_maximum_error']) {
						?>
						<div id="errormsg<?php echo intval($product->get_id()); ?>" class="errormsgcart">
							<input type="hidden" id="fme_qifw_maxmium_error_msg<?php echo intval($product->get_id()); ?>" name="fme_qifw_maxmium_error_msg" value="<?php echo esc_html__('Quantity can not add more than maximum value!', 'FME_QIFW'); ?>">
						</div> 
						<?php
					} else {
						?>
						<div id="errormsg<?php echo intval($product->get_id()); ?>" class="errormsgcart">
							<input type="hidden" id="fme_qifw_maxmium_error_msg<?php echo intval($product->get_id()); ?>" name="fme_qifw_maxmium_error_msg" value="<?php echo esc_attr($fme_save_general_settings['fme_qifw_limit_maximum_error']); ?>">
						</div> 
						<?php 
					}
					if ('' == $fme_save_general_settings['fme_qifw_limit_minimum_error']) {
						?>
						<div id="errormsgminimum<?php echo intval($product->get_id()); ?>" class="errormsgcart">
							<input type="hidden" id="fme_qifw_minimum_error_msg<?php echo intval($product->get_id()); ?>" name="fme_qifw_minimum_error_msg" value="<?php echo esc_html__('Quantity can not Less than minimum value!' , 'FME_QIFW'); ?>">
						</div> 
						<?php
					} else {
						?>
						<div id="errormsgminimum<?php echo intval($product->get_id()); ?>" class="errormsgcart">
							<input type="hidden" id="fme_qifw_minimum_error_msg<?php echo intval($product->get_id()); ?>" name="fme_qifw_minimum_error_msg" value="<?php echo esc_attr($fme_save_general_settings['fme_qifw_limit_minimum_error']); ?>">
						</div> 
						<?php 
					}
					?>
				</div>
				<?php
			}
		} 
	} elseif ('' != $product->get_price() && 'instock' == $product->get_stock_status()) {





		if ('simple' == $product->get_type()) {  
			if ('' != $fme_save_general_settings['fme_qifw_button_label']) {
				echo '<div style="display:flex; flex-direction: column; margin-right:10px;"><div style="width: 100%;"><center><strong>' . esc_attr($fme_save_general_settings['fme_qifw_button_label'], 'FME_QIFW') . '</center></strong></div></div>';
			}   
			?>
				<script type="text/javascript">
					jQuery(document).ready(function(){
						var min_qty = jQuery('#quantityvals<?php echo esc_attr($pro_id); ?>').val();
						jQuery('a[data-product_id="<?php echo esc_attr($pro_id); ?>"]').attr('data-quantity', min_qty);
					});
				</script>
					<div style="display: flex; flex-direction: row; justify-content: center;">
						<input type="hidden" name="stock_quantity" id="stockquantitypq<?php echo intval($product->get_id()); ?>"
						value="<?php echo filter_var($product->get_stock_quantity()); ?>">
						<input type="hidden" name="stepintervalpq" id="pq-stepintervals<?php echo intval($product->get_id()); ?>"
						value="<?php echo esc_attr($viewdata['fme_qifw_step']); ?>">
						<input type="hidden" name="fme_qifw_ptype" id="fme_qifw_ptype" value="<?php echo esc_attr($product->get_type()); ?>">
						<div>
							<input class="pq-minus fme_qbtns" style="<?php echo esc_attr($fme_qifw_button_font_size); ?>;<?php echo esc_attr($fme_style_button_color); ?>;<?php echo esc_attr($fme_style_button_height); ?>;<?php echo esc_attr($fme_style_button_width); ?>;<?php echo esc_attr($fme_qifw_button_font_color); ?>" id="pq-minus<?php echo esc_attr($pro_id); ?>" type="button" value="-"
							onclick="fme_shop_pq_minus(<?php echo esc_attr($pro_id); ?>,<?php echo esc_attr($fme_minimum_qty); ?>,<?php echo esc_attr($fme_maxmium_qty); ?>,<?php echo esc_attr($fme_step); ?>);">
						</div>
						<div class="fme_quantity">       
							<input type="text" <?php echo esc_attr($fme_readonly); ?> style="<?php echo esc_attr($fme_style_button_fieldwidth); ?>" id="quantityvals<?php echo intval($pro_id); ?>"
							class="input-text qty fme_qifw_min_quantity"
							step="" min="" max="" name="quantity"
							value="<?php echo esc_attr($fme_minimum_qty); ?>" title="Qty" size="4"
							inputmode="numeric"
							onkeyup="fme_upd_qty( <?php echo esc_attr($pro_id); ?>,<?php echo esc_attr($fme_minimum_qty); ?>,<?php echo esc_attr($fme_maxmium_qty); ?>,<?php echo esc_attr($fme_step); ?>);"
							>
						</div>
						<div>
							<input class="pq-plus fme_qbtns" style="<?php echo esc_attr($fme_qifw_button_font_size); ?>;<?php echo esc_attr($fme_style_button_color); ?>;<?php echo esc_attr($fme_style_button_height); ?>;<?php echo esc_attr($fme_style_button_width); ?>;<?php echo esc_attr($fme_qifw_button_font_color); ?>" type="button" value="+" id="pq-plus<?php echo esc_attr($pro_id); ?>"
							onclick="fme_pq_shop_plus(<?php echo esc_attr($pro_id); ?>,<?php echo esc_attr($fme_minimum_qty); ?>,<?php echo esc_attr($fme_maxmium_qty); ?>,<?php echo esc_attr($fme_step); ?>);">
						</div>
					</div>

				<?php 
				if ('' == $fme_save_general_settings['fme_qifw_limit_maximum_error']) {
					?>
						<div id="errormsg<?php echo intval($product->get_id()); ?>" class="errormsg" >
							<input type="hidden" id="fme_qifw_maxmium_error_msg<?php echo intval($product->get_id()); ?>" name="fme_qifw_maxmium_error_msg" value="<?php echo esc_html__('Quantity can not add more than maximum value!', 'FME_QIFW'); ?>">
						</div> 
						<?php
				} else {
					?>
						<div id="errormsg<?php echo intval($product->get_id()); ?>" class="errormsg" >
							<input type="hidden" id="fme_qifw_maxmium_error_msg<?php echo intval($product->get_id()); ?>" name="fme_qifw_maxmium_error_msg" value="<?php echo esc_attr($fme_save_general_settings['fme_qifw_limit_maximum_error']); ?>">
						</div> 
						<?php 
				}
				if ('' == $fme_save_general_settings['fme_qifw_limit_minimum_error']) {
					?>
						<div id="errormsgminimum<?php echo intval($product->get_id()); ?>" class="errormsg" >
							<input type="hidden" id="fme_qifw_minimum_error_msg<?php echo intval($product->get_id()); ?>" name="fme_qifw_minimum_error_msg" value="<?php echo esc_html__('Quantity can not Less than minimum value!', 'FME_QIFW'); ?>">
						</div> 
						<?php
				} else {
					?>
						<div id="errormsgminimum<?php echo intval($product->get_id()); ?>" class="errormsg" >
							<input type="hidden" id="fme_qifw_minimum_error_msg<?php echo intval($product->get_id()); ?>" name="fme_qifw_minimum_error_msg" value="<?php echo esc_attr($fme_save_general_settings['fme_qifw_limit_minimum_error']); ?>">
						</div> 
						<?php 
				}
				?>
				<?php
		} 
	}
}
?>
