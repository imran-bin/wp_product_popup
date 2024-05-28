<?php
if (!defined('ABSPATH')) {
	exit;
}
global $wp_roles;
global $post;
global $woocommerce;
$Fme_args = array(
	'post_type'=> 'fme_qifw_woocomerce',
	'orderby'    => 'ID',
	'post_status' => 'publish',
	'order'    => 'ASC',
	'posts_per_page' => -1, // this will retrive all the post that is published 
);
$Fme_QIFW_rules = new WP_Query( $Fme_args );
$fme_Qifw_rule_key = 1;
?>
<style type="text/css">
table.dataTable tbody tr {
  background-color: #f1f1f1 !important ;
}

.woocommerce table.form-table .select2-container
{
	width: 240px !important;
}
.wp-core-ui select
{
	max-width: unset;
}

</style>
<h2 style="margin-top: -4px; margin-bottom: 30px; margin-right: 5px;"><?php echo esc_html__('Manage rules', 'FME_QIFW'); ?><a href="#" data-title="Add" data-toggle="modal"  data-target="#fme_add_new_rule" class="page-title-action"><?php echo esc_html__('Add new rule', 'FME_QIFW'); ?></a></h2>

<div class="container-fluid">
	<table class="ui celled table" style="width:100%;" id="fme_datatable">
		<thead style="font-size: 13px;">
			<tr>
				<th class="col-md-2"><?php echo esc_html__('Rules', 'FME_QIFW'); ?></th>
				<th class="col-md-2"><?php echo esc_html__('Rules Status', 'FME_QIFW'); ?></th>
				<th class="col-md-2"><?php echo esc_html__('Rules Priority', 'FME_QIFW'); ?></th>
				<th class="col-md-2"><?php echo esc_html__('Display On', 'FME_QIFW'); ?> </th>
				<th class="col-md-5"><?php echo esc_html__('Product/Category', 'FME_QIFW'); ?></th>
				<th class="col-md-2"><?php echo esc_html__('Minimum Quantity', 'FME_QIFW'); ?></th>
				<th class="col-md-2"><?php echo esc_html__('Maximum Quantity', 'FME_QIFW'); ?></th>
				<th class="col-md-2"><?php echo esc_html__('Steps', 'FME_QIFW'); ?></th>
				<th class="col-md-2"><?php echo esc_html__('Readonly', 'FME_QIFW'); ?></th>
				<th class="col-md-5"><?php echo esc_html__('User Roles', 'FME_QIFW'); ?></th>
				<th class="col-md-2"><?php echo esc_html__('Action', 'FME_QIFW'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			if ( $Fme_QIFW_rules->have_posts() ) {    
				while ( $Fme_QIFW_rules->have_posts() ) {
					$Fme_QIFW_rules->the_post(); 
					$fme_postid = get_the_ID(); 
					?>
					<tr id="fme_row<?php echo esc_attr($fme_postid); ?>">
						<td><strong><?php echo 'Rule' . esc_attr($fme_Qifw_rule_key) . ''; ?></strong></td>
						<td>
							<?php 
							$fme_enable_disable_key =  get_post_meta($fme_postid, 'fme_enable_disable_key', true); 
							echo esc_attr(ucfirst(str_replace('fme_', '', $fme_enable_disable_key)));
							?>
						</td>
						<td>
							<?php echo filter_var(get_post_meta($fme_postid, 'fme_qifw_priority', true)); ?>
						</td>
						<td>	
							<?php 
							$fme_product_category_selected_key =  get_post_meta($fme_postid, 'fme_product_category_selected_key', true);
							if ('' != $fme_product_category_selected_key) {
								$fme_selected_pc = esc_attr(str_replace('fme_qifw_', '', $fme_product_category_selected_key));
								echo esc_attr(ucfirst($fme_selected_pc));
							} else {
								echo 'All Products';
							}
							?>
						</td>
						<td>
							<?php 
							$fme_upload_files_selected_product = get_post_meta($fme_postid, 'fme_selected_items_key', true);
							if ('fme_qifw_product' == $fme_product_category_selected_key ) {
								if (!empty($fme_upload_files_selected_product)) {
									foreach ($fme_upload_files_selected_product as $key => $value) {
										?>
										<span class="badge badge-primary">
											<?php echo esc_attr(get_the_title($value)) . ','; ?>
										</span>
										<?php
									} 
								}
							} elseif (!empty($fme_upload_files_selected_product) && is_array($fme_upload_files_selected_product)) {
								foreach ($fme_upload_files_selected_product as $key => $value) {
									$fme_upload_file_category = get_term($value);
									?>
										<span class="badge badge-primary">
										<?php echo esc_attr($fme_upload_file_category->name) . ','; ?>
										</span>
										<?php
								}
							} 
							?>

						</td>
						<td>
							<?php 
							$fme_qifw_minimum_qty =  get_post_meta($fme_postid, 'fme_qifw_minimum_key', true);
							if ('' != $fme_qifw_minimum_qty) {
								echo esc_attr($fme_qifw_minimum_qty);
							}
							?>
						</td>
						<td>	
							<?php 
							$fme_qifw_maximum_qty =  get_post_meta($fme_postid, 'fme_qifw_maxmium_key', true);
							if ('' != $fme_qifw_maximum_qty) {
								echo esc_attr($fme_qifw_maximum_qty);
							}
							?>

						</td>
						<td>	
							<?php 
							$fme_qifw_steps =  get_post_meta($fme_postid, 'fme_qifw_step', true);
							if ('' != $fme_qifw_steps) {
								echo esc_attr($fme_qifw_steps);
							}
							?>
						</td>
						<td>	
							<?php 
							$fme_qifw_readonly =  get_post_meta($fme_postid, 'fme_qifw_readonly', true);
							
							if ('on' == $fme_qifw_readonly) {
								echo 'Yes';
							} elseif ('' == $fme_qifw_readonly) {
								echo 'No';
							}

							?>
						</td>
						<td>
							<?php 
							$fme_qifw_user_roles = get_post_meta($fme_postid, 'fme_selected_user_role_key', true); 
							if (!empty($fme_qifw_user_roles)) {
								$fme_roles = implode(',', $fme_qifw_user_roles);
								echo esc_attr( ucfirst( str_replace(',', "\n", $fme_roles )) );         
							}
							?>
						</td>
							<td >
								<div style="display: inline-flex; flex-direction: row;">
									<p style="margin-bottom: 0; margin-right: 3px;" data-placement="top" data-toggle="tooltip" title="Edit"><button onclick="fme_edit_rules(<?php echo esc_attr($fme_postid); ?>)" type="button" class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal"  data-target="#edit" id="fme_btn"><span><?php echo esc_html__('Edit', 'FME_QIFW'); ?></span></button></p>
									<p style="margin-bottom: 0; margin-right: 3px;" data-placement="top" data-toggle="tooltip" title="Delete"><button onclick="fme_delete_rules(<?php echo esc_attr($fme_postid); ?>)" type="button" class="btn btn-danger btn-xs" data-title="Delete" data-target="#delete" id="fme_btn"><span><?php echo esc_html__('Delete', 'FME_QIFW'); ?></span></button></p>
								</div>
							</td>
					</tr>
					<?php 
					$fme_Qifw_rule_key++;
				}
			}
			?>
		</tbody>
	</table>
	<div class="col-sm-5">
		<div class="alert alert-success" id="fme_qifw_delete_general_settings" role="alert">
			<button type="button" class="close" data-dismiss="alert">×</button>  
			<strong><?php echo esc_html__('Succuss', 'FME_QIFW'); ?>!</strong> <?php echo esc_html__('Delete Rule  Successfully..', 'FME_QIFW'); ?>
		</div>
	</div>
</div>


<!-- Modal -->
<div id="edit" class="modal fade" role="dialog" style="margin-top: 2%;">
	<div class="modal-dialog modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?php echo esc_html__('Update Settings', 'FME_QIFW'); ?></h4>
			</div>
			<div class="modal-body" id="fme_update_content">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo esc_html__('Close', 'FME_QIFW'); ?></button>
			</div>
		</div>

	</div>
</div>	


<!-- Modal -->
<div id="fme_add_new_rule" class="modal fade" role="dialog" style="margin-top: 2%;">
	<div class="modal-dialog modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?php echo esc_html__('Add New Rule', 'FME_QIFW'); ?></h4>
			</div>

			<div class="modal-body" id="fme_add_rule">
				<div class="row">
					<div class="form-group">		
						<div class="col-sm-4">
							<label for="woocommerce_placeholder_image"> <?php echo esc_html__('Rule Priority:', 'FME_QIFW'); ?><span class="tip"><?php echo wc_help_tip('SET Rules Priority.', 'FME_QIFW'); ?></span></label>
						</div>
						<div class="col-sm-4">
							<input type="number" class="form-control" min="0" id="fme_qifw_priority"> 
						</div>
					</div>
				</div><br>

				<div class="row">
					<div class="form-group">		
						<div class="col-sm-4">
							<label for="woocommerce_placeholder_image"> <?php echo esc_html__('Enable / Disable Rule:', 'FME_QIFW'); ?> <span class="tip"><?php echo wc_help_tip('Enable / Disable Rule.', 'FME_QIFW'); ?></span></label>
						</div>
						<div class="col-sm-4">
							<select class="form-control" name="sort" id="fme_enable_disable">
								<option value="fme_enable"><?php echo esc_html__('Enable', 'FME_QIFW'); ?></option>
								<option value="fme_disable" selected="selected"><?php echo esc_html__('Disable ', 'FME_QIFW'); ?></option>
							</select> 	
						</div>
					</div>
				</div><br>

				<div class="row">
					<div class="form-group">		
						<div class="col-sm-4">	
							<label for="woocommerce_placeholder_image"> <?php echo esc_html__('Minimum Quantity:', 'FME_QIFW'); ?><span class="tip"><?php echo wc_help_tip('The Minimum Quantity a customer has to order for this product.', 'FME_QIFW'); ?></span></label>
						</div>
						<div class="col-sm-4">
							<input type="number" class="form-control" min="1" id="fme_qifw_minimum">
						</div>
					</div>
				</div><br>

				<div class="row">
					<div class="form-group">		
						<div class="col-sm-4">	
							<label for="woocommerce_placeholder_image"> <?php echo esc_html__('Maximum Quantity:', 'FME_QIFW'); ?><span class="tip"><?php echo wc_help_tip('The Maximum Quantity a customer has to order for this product.', 'FME_QIFW'); ?></span></label>
						</div>
						<div class="col-sm-4">
							<input type="number" class="form-control" min="0" id="fme_qifw_maxmium">
						</div>
					</div>
				</div><br>


				<div class="row">
					<div class="form-group">		
						<div class="col-sm-4">	
							<label for="woocommerce_placeholder_image"> <?php echo esc_html__('Step:', 'FME_QIFW'); ?><span class="tip"><?php echo wc_help_tip('The step between allowed quantity.', 'FME_QIFW'); ?></span></label>
						</div>
						<div class="col-sm-4">
							<input type="number" class="form-control" min="0" id="fme_qifw_step">
						</div>
					</div>
				</div><br>

				<div class="row">
					<div class="form-group">		
						<div class="col-sm-4">	
							<label for="woocommerce_placeholder_image"> <?php echo esc_html__('Readonly:', 'FME_QIFW'); ?><span class="tip"><?php echo wc_help_tip('A Readonly input field cannot be modified', 'FME_QIFW'); ?></span></label>
						</div>
						<div class="col-sm-4">
							<input type="checkbox" name="fme_qifw_readonly" class="form-control" id="fme_qifw_readonly">
						</div>
					</div>
				</div><br>
	
				<div class="row">
					<div class="form-group">		
						<div class="col-sm-4">	
							<label for="woocommerce_placeholder_image"> <?php echo esc_html__('Product / Category Restriction:', 'FME_QIFW'); ?><span class="tip"><?php echo wc_help_tip('Quantity Increment can optionally visible/hidden only if the selected products are in cart/order.', 'FME_QIFW'); ?></span></label>
						</div>
						<div class="col-sm-4">
							<select class="form-control fmeproductcategory" id="fmeproductcategory" name="selectpc[]" onchange="Fme_QIFW_choosen_product_cateory('fme_create');">
								<option value="all_products"><?php echo esc_html__('All Products:', 'FME_QIFW'); ?></option>
								<option value="fme_qifw_product"><?php echo esc_html__('Products', 'FME_QIFW'); ?></option>
								<option value="fme_qifw_category"><?php echo esc_html__('Categories', 'FME_QIFW'); ?></option>
							</select>					
						</div>
					</div>
				</div><br>

				<div class="row" id="fme_qifw_Products" style="display:none;">
					<div class="form-group">		
						<div class="col-sm-4">	
							<label for="woocommerce_placeholder_image"> <?php echo esc_html__('Select Product.', 'FME_QIFW'); ?></label>
						</div>
						<div class="col-sm-4">
							<?php 

							global $post;
							$fme_QIFW_files_product = array(
								'post_status' => 'publish',
								'ignore_sticky_posts' => 1,
								'posts_per_page' => 250,
								'orderby' => 'title',
								'order' => 'ASC',
								'post_type' => array( 'product' ),
							);
							$fme_QIFW_files_woo_product = get_posts($fme_QIFW_files_product);
							if (!empty($fme_QIFW_files_woo_product)) { 

								?>
								<input type="hidden" id="fme_qifw_store_product_count" value="<?php echo esc_attr(count($fme_QIFW_files_woo_product)); ?>" style="display:none;">
								<select style="width: 100%;" class="Fme_choosen" id="Fme_Qifw-product" multiple="multiple" name="">
									<?php
									foreach ($fme_QIFW_files_woo_product as $products) {
										?>
										<option value="<?php echo esc_attr($products->ID); ?>"><?php echo filter_var($products->post_title); ?></option>
										<?php
									}

									?>
								</select>
							<?php }; ?>
						</div>
					</div>
				</div><br>

				<div class="row" id="fme_qifw_category" style="display:none;">
					<div class="form-group">		
						<div class="col-sm-4">	
							<label for="woocommerce_placeholder_image"> <?php echo esc_html__('Select category.', 'FME_QIFW'); ?></label>
						</div>
						<div class="col-sm-4" >
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
						</div>
					</div>
				</div><br>


				<div class="row">
					<div class="form-group">		
						<div class="col-sm-4">	
							<label for="user"> <?php echo esc_html__('User Role.', 'FME_QIFW'); ?><span class="tip"><?php echo wc_help_tip('Selecting at least one role will make the Quantity field to be visible/unvisible to that role.', 'FME_QIFW'); ?></span></label>
						</div>
						<div class="col-sm-4">
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
							</div>
						</div>
					</div><br>

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
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo esc_html__('Close', 'FME_QIFW'); ?></button>
			</div>
		</div>
	</div>
</div>
<style type="text/css">
		.select2-container{
  width: 100% !important;
}
</style>


