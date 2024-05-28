<style type="text/css">

.woocommerce table.form-table .select2-container
{
	width: 240px !important;
}
.wp-core-ui select
{
	max-width: unset;
}
</style>
<div class="wrap woocommerce">
	<table class="form-table">

		<tbody>
			  <tr valign="top">
				<th scope="row" class="titledesc">
					<label for="woocommerce_placeholder_image"> <?php echo esc_html__('Rule Priority:', 'FME_QIFW'); ?><span class="tip"><?php echo wc_help_tip('SET Rules Priority.', 'FME_QIFW'); ?></span></label>
				</th>
				<td class="forminp forminp-text">
					<input type="number" class="form-control" min="0" id="fme_qifw_priority"> 							</td>
				</tr>

				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="woocommerce_placeholder_image"> <?php echo esc_html__('Enable / Disable Rule:', 'FME_QIFW'); ?> <span class="tip"><?php echo wc_help_tip('Enable / Disable Rule.', 'FME_QIFW'); ?></span></label>
					</th>
					<td class="forminp forminp-text">
						<select class="form-control" name="sort" id="fme_enable_disable">
							<option value="fme_enable"><?php echo esc_html__('Enable', 'FME_QIFW'); ?></option>
							<option value="fme_disable" selected="selected"><?php echo esc_html__('Disable ', 'FME_QIFW'); ?></option>
						</select> 	
					</td>
				</tr>

				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="woocommerce_placeholder_image"> <?php echo esc_html__('Minimum Quantity:', 'FME_QIFW'); ?><span class="tip"><?php echo wc_help_tip('The Minimum Quantity a customer has to order for this product.', 'FME_QIFW'); ?></span></label>
					</th>
					<td class="forminp forminp-text">
						<input type="number" class="form-control" min="1" id="fme_qifw_minimum"> 							</td>
					</tr>

					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="woocommerce_placeholder_image"> <?php echo esc_html__('Maximum Quantity:', 'FME_QIFW'); ?><span class="tip"><?php echo wc_help_tip('The Maximum Quantity a customer has to order for this product.', 'FME_QIFW'); ?></span></label>
						</th>
						<td class="forminp forminp-text">
							<input type="number" class="form-control" min="0" id="fme_qifw_maxmium"> 							</td>
						</tr>

						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="woocommerce_placeholder_image"> <?php echo esc_html__('Step:', 'FME_QIFW'); ?><span class="tip"><?php echo wc_help_tip('The step between allowed quantity.', 'FME_QIFW'); ?></span></label>
							</th>
							<td class="forminp forminp-text">
								<input type="number" class="form-control" min="0" id="fme_qifw_step">						</td>
							</tr>

							<tr valign="top">
								<th scope="row" class="titledesc">
									<label for="woocommerce_placeholder_image"> <?php echo esc_html__('Product / Category Restriction:', 'FME_QIFW'); ?><span class="tip"><?php echo wc_help_tip('Quantity Increment can optionally visible/hidden only if the selected products are in cart/order.', 'FME_QIFW'); ?></span></label>
								</th>
								<td class="forminp forminp-text">
									<select class="form-control fmeproductcategory" id="fmeproductcategory" name="selectpc[]" onchange="Fme_QIFW_choosen_product_cateory('fme_create');">
										<option value="all_products"><?php echo esc_html__('All Products:', 'FME_QIFW'); ?></option>
										<option value="fme_qifw_product"><?php echo esc_html__('Products', 'FME_QIFW'); ?></option>
										<option value="fme_qifw_category"><?php echo esc_html__('Categories', 'FME_QIFW'); ?></option>
									</select>					</td>
								</tr>

								<tr valign="top" id="fme_qifw_Products" style="display:none;">
									<th scope="row" class="titledesc">
										<label for="woocommerce_placeholder_image"> <?php echo esc_html__('Select Product.', 'FME_QIFW'); ?></label>
									</th>
									<td class="forminp forminp-text">
										<?php 
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
										if (!empty($fme_QIFW_files_woo_product)) { 
											?>
											<select class="Fme_choosen" id="Fme_Qifw-product" multiple="multiple" name="">
												<?php
												foreach ($fme_QIFW_files_woo_product as $products) {
													?>
													<option value="<?php echo esc_attr($products->ID); ?>"><?php echo filter_var($products->post_title); ?></option>
													<?php
												}

												?>
											</select>
										<?php }; ?>
									</td>
								</tr>

								<tr valign="top" id="fme_qifw_category" style="display:none;">
									<th scope="row" class="titledesc">
										<label for="woocommerce_placeholder_image"> <?php echo esc_html__('Select category.', 'FME_QIFW'); ?></label>
									</th>
									<td class="forminp forminp-text">
										<?php 
										$fme_QIFW_woo_category = array(
											'taxonomy' => 'product_cat',
										);
										$fme_QIFW_products_categories = get_terms($fme_QIFW_woo_category);
										if (!empty($fme_QIFW_products_categories)) { 
											?>
											<select class="Fme_choosen" id="Fme_Qifw-category" multiple="multiple" name="">
												<?php
												foreach ($fme_QIFW_products_categories as $category) {
													?>
													<option value="<?php echo esc_attr($category->term_id); ?>"><?php echo esc_attr($category->name); ?></option>
													<?php
												}

												?>
											</select>

										<?php } ?>
									</td>
								</tr>


								<tr valign="top" id="fme_qifw_user_role">
									<th scope="row" class="titledesc">
										<label for="user"> <?php echo esc_html__('User Role.', 'FME_QIFW'); ?><span class="tip"><?php echo wc_help_tip('Selecting at least one role will make the Quantity field to be visible/unvisible to that role.', 'FME_QIFW'); ?></span></label>
									</th>
									<td class="forminp forminp-text">
										<?php
										global $wp_roles;
										$fme_qifw_default_roles = $wp_roles->get_names();
										if (!empty($fme_qifw_default_roles)) {
											?>
											<select class="Fme_choosen" id="Fme_choosen-user-role" multiple="multiple" name="">
												<?php
												foreach ($fme_qifw_default_roles as $key => $value) {
													?>
													<option value="<?php echo filter_var(strtolower($value)); ?>">
														<?php echo filter_var($value); ?>
													</option>
													<?php
												}
												?>
											</select>
											<?php
										}
										?>
									</td>
								</tr>

							</tbody>
						</table>

					</div> 


					<div class="row" id="fme_row">
						<div class="col-sm-4"> 
							<span id="fme_settings_loader"></span>
							<input type="button" name="Fme_save_settings" onclick="fme_save_settings('fme_create')" value="<?php echo esc_html__('Save Settings', 'FME_QIFW'); ?>" class="btn btn-primary">
						</div>

						<div class="col-sm-5">
							<div class="alert alert-success" id="fme_qifw_success_update" role="alert">
								<button type="button" class="close" data-dismiss="alert">×</button>  
								<strong><?php echo esc_html__('Succuss', 'fme_shopify_to_woo'); ?>!</strong> <?php echo esc_html__('Save Settings Successfully..', 'fme_shopify_to_woo'); ?>
							</div>
						</div>
					</div>

