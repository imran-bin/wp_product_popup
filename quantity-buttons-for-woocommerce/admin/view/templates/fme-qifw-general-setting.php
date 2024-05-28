<?php 
$fme_save_general_settings = get_option('fme_save_general_settings');

$fme_qifw_button_label = isset($fme_save_general_settings['fme_qifw_button_label']) ? filter_var($fme_save_general_settings['fme_qifw_button_label']) : '';

$fme_qifw_button_style = isset($fme_save_general_settings['fme_qifw_button_style']) ? filter_var($fme_save_general_settings['fme_qifw_button_style']) : '';

$fme_qifw_button_font_color = isset($fme_save_general_settings['fme_qifw_button_font_color']) ? filter_var($fme_save_general_settings['fme_qifw_button_font_color']) : '';

$fme_qifw_button_height = isset($fme_save_general_settings['fme_qifw_button_height']) ? filter_var($fme_save_general_settings['fme_qifw_button_height']) : '';

$fme_qifw_button_width = isset($fme_save_general_settings['fme_qifw_button_width']) ? filter_var($fme_save_general_settings['fme_qifw_button_width']) : '';

$fme_qifw_button_font_size = isset($fme_save_general_settings['fme_qifw_button_font_size']) ? filter_var($fme_save_general_settings['fme_qifw_button_font_size']) : '';

$fme_qifw_Quantity_field_width = isset($fme_save_general_settings['fme_qifw_Quantity_field_width']) ? filter_var($fme_save_general_settings['fme_qifw_Quantity_field_width']) : '';

$fme_qifw_limit_minimum_error = isset($fme_save_general_settings['fme_qifw_limit_minimum_error']) ? filter_var($fme_save_general_settings['fme_qifw_limit_minimum_error']) : '';

$fme_qifw_limit_maximum_error = isset($fme_save_general_settings['fme_qifw_limit_maximum_error']) ? filter_var($fme_save_general_settings['fme_qifw_limit_maximum_error']) : '';

?>

<h2 style="margin-top: -4px;"><?php echo esc_html__('General rule', 'FME_QIFW'); ?></h2>
<div class="wrap woocommerce">
	<table class="form-table">
		<tbody>
			 <tr valign="top" id="fme_row">
				<th scope="row" class="titledesc">
					<label for="woocommerce_placeholder_image"> <?php echo esc_html__('Button Label:', 'FME_QIFW'); ?><span class="tip"><?php echo wc_help_tip('Set Quantity input field Label..', 'FME_QIFW'); ?></span></label>
				</th>
				<td class="forminp forminp-text">
					<input type="text" class="form-control" id="fme_qifw_button_label" name="fme_qifw_button_label" value="<?php echo esc_attr($fme_qifw_button_label , 'FME_QIFW'); ?>">						</td>
				</tr>

				<tr valign="top" id="fme_row">
					<th scope="row" class="titledesc">
						<label for="woocommerce_placeholder_image"> <?php echo esc_html__('Button Color:', 'FME_QIFW'); ?><span class="tip"><?php echo wc_help_tip('Select the button background color style.', 'FME_QIFW'); ?></span></label>
					</th>
					<td class="forminp forminp-text">
					<input type="color" class="jscolor" name="fme_qifw_button_style" id="fme_qifw_button_style" value="<?php echo esc_attr($fme_qifw_button_style); ?>">
				  </td>
				</tr>

				<tr valign="top" id="fme_row">
					<th scope="row" class="titledesc">
						<label for="woocommerce_placeholder_image"> <?php echo esc_html__('Button Font Color:', 'FME_QIFW'); ?><span class="tip"><?php echo wc_help_tip('Select the button Font color style.', 'FME_QIFW'); ?></span></label>
					</th>
					<td class="forminp forminp-text">
						<input type="color" class="jscolor" name="fme_qifw_button_font_color" id="fme_qifw_button_font_color" value="<?php echo esc_attr($fme_qifw_button_font_color); ?>">
				  </td>
				</tr>


				<tr valign="top" id="fme_row">
					<th scope="row" class="titledesc">
						<label for="woocommerce_placeholder_image"> <?php echo esc_html__('Button Font Size:', 'FME_QIFW'); ?><span class="tip"><?php echo wc_help_tip('Recommended 12-25 pixels...', 'FME_QIFW'); ?></span></label>
					</th>
					<td class="forminp forminp-text">
						<input type="number" class="form-control" id="fme_qifw_button_font_size" name="fme_qifw_button_font_size" value="<?php echo esc_attr($fme_qifw_button_font_size); ?>">
				  </td>
				</tr>

				<tr valign="top" id="fme_row">
					<th scope="row" class="titledesc">
						<label for="woocommerce_placeholder_image"> <?php echo esc_html__('Quantity Field Width:', 'FME_QIFW'); ?><span class="tip"><?php echo wc_help_tip('Recommended 35-50 pixels...', 'FME_QIFW'); ?></span></label>
					</th>
					<td class="forminp forminp-text">
						<input type="number" class="form-control" id="fme_qifw_Quantity_field_width" name="fme_qifw_Quantity_field_width" value="<?php echo esc_attr($fme_qifw_Quantity_field_width); ?>">
				  </td>
				</tr>

					<tr valign="top" id="fme_row">
					<th scope="row" class="titledesc">
						<label for="woocommerce_placeholder_image"> <?php echo esc_html__('Quantity Minimum Limit Error Message:', 'FME_QIFW'); ?><span class="tip"><?php echo wc_help_tip('PLease Enter Error Message Minimum Limit...', 'FME_QIFW'); ?></span></label>
					</th>
					<td class="forminp forminp-text">
						<input type="Text" class="form-control" id="fme_qifw_limit_minimum_error" name="fme_qifw_limit_minimum_error" value="<?php echo esc_attr($fme_qifw_limit_minimum_error); ?>">
				  </td>
				</tr>

					<tr valign="top" id="fme_row">
					<th scope="row" class="titledesc">
						<label for="woocommerce_placeholder_image"> <?php echo esc_html__('Quantity Maximum Limit Error Message:', 'FME_QIFW'); ?><span class="tip"><?php echo wc_help_tip('PLease Enter Error Message Maximum Limit...', 'FME_QIFW'); ?></span></label>
					</th>
					<td class="forminp forminp-text">
						<input type="Text" class="form-control" id="fme_qifw_limit_maximum_error" name="fme_qifw_limit_maximum_error" value="<?php echo esc_attr($fme_qifw_limit_maximum_error); ?>">
					</td>
				</tr>

		</tbody>
		</table>
	</div>



<div class="row" id="fme_row">
	<div class="col-sm-4">
		<span id="fme_settings_loader"></span>
		<input type="button" name="Fme_save_settings" onclick="fme_general_settings();" value="<?php echo esc_html__('Save Settings', 'FME_QIFW'); ?>" class="btn btn-primary">
	</div>
	<div class="col-sm-5">
		<div class="alert alert-success" id="fme_qifw_success_general_settings" role="alert">
			<button type="button" class="close" data-dismiss="alert">×</button>  
			<strong><?php echo esc_html__('Succuss', 'fme_shopify_to_woo'); ?>!</strong> <?php echo esc_html__('Save General Settings Successfully..', 'fme_shopify_to_woo'); ?>
		</div>
	</div>
</div>
