<?php 
 $fme_enable_disable = get_post_meta($fme_post_id, 'fme_enable_disable_key', true);
 $fme_qifw_minimum = get_post_meta($fme_post_id, 'fme_qifw_minimum_key', true);
 $fme_qifw_maxmium = get_post_meta($fme_post_id, 'fme_qifw_maxmium_key', true);
 $fme_qifw_step = get_post_meta($fme_post_id, 'fme_qifw_step', true);
 $fme_qifw_readonly =  get_post_meta($fme_post_id, 'fme_qifw_readonly', true);
 $fme_product_category_selected= get_post_meta($fme_post_id, 'fme_product_category_selected_key', true);
 $fme_selected_items = get_post_meta($fme_post_id, 'fme_selected_items_key', true);
 $fme_selected_user_role = get_post_meta($fme_post_id, 'fme_selected_user_role_key', true);
 $fme_qifw_priority = get_post_meta($fme_post_id, 'fme_qifw_priority', true);
?>
<div class="row">
	<div class="form-group">		
		<div class="col-sm-4">
			<label for="sort" class="control-label"> <?php echo esc_html__('Rule Priority:', 'FME_QIFW'); ?> </label><br/>
			<span class="fme_qifw_description"><?php echo esc_html__('SET Rules Priority.', 'FME_QIFW'); ?>
		</div>
		<div class="col-sm-4">
			<input type="number" class="form-control" min="0" id="fme_qifw_update_priority" value="<?php echo esc_attr($fme_qifw_priority); ?>">
		</div>
	</div>
</div><br/>
<div class="row">
	<div class="form-group">		
		<div class="col-sm-4">
			<label for="sort" class="control-label"> <?php echo esc_html__('Enable / Disable Rule:', 'FME_QIFW'); ?> </label><br/>
			<span class="fme_qifw_description"><?php echo esc_html__('Enable / Disable Rules.', 'FME_QIFW'); ?>
		</div>
		<div class="col-sm-4">
			<select class="form-control" name="sort" id="fme_update_enable_disable">
				<option <?php selected('fme_enable', $fme_enable_disable); ?> value="fme_enable"><?php echo esc_html__('Enable', 'FME_QIFW'); ?></option>
				<option <?php selected('fme_disable', $fme_enable_disable); ?> value="fme_disable"><?php echo esc_html__('Disable ', 'FME_QIFW'); ?></option>
			</select>
		</div>
	</div>
</div>

<div class="row" id="fme_row">
	<div class="form-group">
		<div class="col-sm-4">
			<label for="sort" class="control-label"> <?php echo esc_html__('Minimum Quantity:', 'FME_QIFW'); ?> </label><br/>
			<span class="fme_qifw_description"><?php echo esc_html__('The Minimum Quantity a customer has to order for this product.', 'FME_QIFW'); ?>
		</span>
	</div>
	<div class="col-sm-4">
		<input type="number" value="<?php echo esc_attr($fme_qifw_minimum); ?>" class="form-control" min="0" id="fme_update_qifw_minimum">
	</div>

</div>
</div>

<div class="row" id="fme_row">
	<div class="form-group">
		<div class="col-sm-4">
			<label for="sort" class="control-label"> <?php echo esc_html__('Maximum Quantity:', 'FME_QIFW'); ?> </label><br/>
			<span class="fme_qifw_description"><?php echo esc_html__('The Maximum Quantity a customer can add to order for this product.', 'FME_QIFW'); ?>
		</span>
	</div>
	<div class="col-sm-4">
		<input type="number" value="<?php echo esc_attr($fme_qifw_maxmium); ?>" class="form-control" min="0" id="fme_update_qifw_maxmium">
	</div>
</div>
</div>

<div class="row" id="fme_row">
	<div class="form-group">
		<div class="col-sm-4">
			<label for="sort" class="control-label"> <?php echo esc_html__('Step:', 'FME_QIFW'); ?> </label><br/>
			<span class="fme_qifw_description"><?php echo esc_html__('The step between allowed quantities.', 'FME_QIFW'); ?>    		
		</div>
		<div class="col-sm-4">
			<input type="number" value="<?php echo esc_attr($fme_qifw_step); ?>" class="form-control" min="0" id="fme_update_qifw_step">
		</span>
	</div>
</div>
</div>
<div class="row" id="fme_row">
	<div class="form-group">
		<div class="col-sm-4">
			<label for="sort" class="control-label"> <?php echo esc_html__('Readonly:', 'FME_QIFW'); ?> </label><br/>
			<span class="fme_qifw_description"><?php echo esc_html__('A Readonly input field cannot be modified', 'FME_QIFW'); ?>    		
		</div>
		<div class="col-sm-4">
			<input type="checkbox" <?php checked( $fme_qifw_readonly, 'on' ); ?>  class="form-control" id="fme_update_qifw_readonly">
		</span>
	</div>
</div>
</div>
<div class="row" id="fme_row">
	<div class="col-sm-4">
		<label for="sort"><?php echo esc_html__('Product / Category Restriction', 'FME_QIFW'); ?></label><br/>
		<span class="fme_qifw_description">
			<?php 
			echo esc_html__('Quantity Increment can optionally visible/hidden only if the selected products are in cart/order.', 'FME_QIFW'); 
			?>
		</span>
	</div>
	<div class="col-sm-4">
		<select class="form-control fmeupdateproductcategory" id="fmeupdateproductcategory" name="selectpc[]" onchange="Fme_QIFW_choosen_product_cateory('fme_edit');">
			<option <?php selected('all_products', $fme_product_category_selected); ?> value="all_products"><?php echo esc_html__('All Products:', 'FME_QIFW'); ?></option>
			<option <?php selected('fme_qifw_product', $fme_product_category_selected); ?> value="fme_qifw_product"><?php echo esc_html__('Products', 'FME_QIFW'); ?></option>
			<option <?php selected('fme_qifw_category', $fme_product_category_selected); ?> value="fme_qifw_category"><?php echo esc_html__('Categories', 'FME_QIFW'); ?></option>
		</select>
	</div>
</div>

<div class="row" id="fme_qifw_update_Products" 
	<?php 
	if ('fme_qifw_product' == $fme_product_category_selected) {
		echo 'style="display:block"';
	} else {
		echo 'style="display:none"';
	}
	?>
	>
	<div class="col-sm-4">
		<label id="fme_qifw_label"><?php echo esc_html__('Select Product', 'FME_QIFW'); ?></label>
	</div>
	<div class="col-sm-4">
		<?php 
		if (!empty($fme_selected_items) && 'all_products' != $fme_selected_items) {
			$fme_selected_pro_items = $fme_selected_items;
		} else {
			$fme_selected_pro_items = array();
		}

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
			<select class="Fme_update_choosen_product" id="Fme_Qifw-update-product" multiple="multiple" name="">
				<?php 
				if ('fme_qifw_product' == $fme_product_category_selected) {
					foreach ($fme_selected_pro_items as $key => $value) {
						$product = wc_get_product( $value );

						$selected_pro_title= $product->get_title();

						?>
					
					<option value="<?php echo esc_attr($value); ?>" selected><?php echo esc_html__($selected_pro_title); ?></option>
					<?php
					}}
				foreach ($fme_QIFW_files_woo_product as $products) {
					if (is_array($fme_selected_pro_items) && in_array($products->ID, $fme_selected_pro_items)) {
						continue;
					}
					?>
					<option value="<?php echo esc_attr($products->ID); ?>"><?php echo filter_var($products->post_title); ?></option>
					<?php
				}

				?>
			</select>
		<?php }; ?>
	</div>
</div>
<div class="row" id="fme_qifw_update_category"
	<?php 
	if ('fme_qifw_category' == $fme_product_category_selected) {
		echo 'style="display:block"';
	} else {
		echo 'style="display:none"';
	}
	?>
	>
	<div class="col-sm-4">
		<label id="fme_qifw_label"><?php echo esc_html__('Select category', 'FME_QIFW'); ?></label>
	</div>
	<div class="col-sm-4">
		<?php 
		if (!empty($fme_selected_items) && 'all_products' != $fme_selected_items) {
			$fme_selected_cat_items = $fme_selected_items;
		} else {
			$fme_selected_cat_items = array();
		}
		$fme_QIFW_woo_category = array(
			'taxonomy' => 'product_cat',
		);
		$fme_QIFW_products_categories = get_terms($fme_QIFW_woo_category);
		if (!empty($fme_QIFW_products_categories)) { 
			?>
			<select class="Fme_update_choosen " id="Fme_Qifw-update-category" multiple="multiple" name="">
				<?php
				foreach ($fme_QIFW_products_categories as $category) {
					?>
					<option  <?php selected(is_array($fme_selected_cat_items) && in_array($category->term_id, $fme_selected_cat_items), true); ?> value="<?php echo esc_attr($category->term_id); ?>"><?php echo esc_attr($category->name); ?></option>
					<?php
				}

				?>
			</select>

		<?php } ?>
	</div>
</div>


<div class="row" id="fme_qifw_user_role">
	<div class="col-sm-4">
		<label id="fme_upload_files_label"><?php echo esc_html__('User role', 'Fme_Upload_Files'); ?></label>
		<br/>
		<span class="fme_qifw_description">
			<?php 
			echo esc_html__('Selecting at least one role will make the Quantity field to be visible/unvisible to that role..', 'FME_QIFW'); 
			?>
		</span>
	</div>
	<div class="col-sm-4">
		<?php
		if (!empty($fme_selected_user_role)) {
			$fme_selected_user_roles = $fme_selected_user_role;
		} else {
			$fme_selected_user_roles = array();
		}
		global $wp_roles;
		$fme_qifw_default_roles = $wp_roles->get_names();
		if (!empty($fme_qifw_default_roles)) {
			?>
			<select class="Fme_update_choosen" id="Fme_choosen-user-update-role" multiple="multiple" name="">
				<?php
				foreach ($fme_qifw_default_roles as $key => $value) {
					?>
					<option <?php selected( is_array($fme_selected_user_roles) && in_array(strtolower($value), $fme_selected_user_roles), true); ?> value="<?php echo filter_var(strtolower($value)); ?>">
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

<div class="row" id="fme_row">
	<div class="col-sm-4">
		<span id="fme_settings_loader"></span>
		<input type="hidden" name="fme_update_post_id" id="fme_update_post_id" value="<?php echo esc_attr($fme_post_id); ?>">
		<input type="button" name="Fme_save_settings" onclick="fme_save_settings('fme_edit')" value="<?php echo esc_html__('Update Settings', 'FME_QIFW'); ?>" class="btn btn-primary">
	</div>
	<div class="col-sm-5">
		<div class="alert alert-success" id="fme_qifw_success_updated" role="alert">
			<button type="button" class="close" data-dismiss="alert">×</button>  
			<strong><?php echo esc_html__('Succuss', 'fme_shopify_to_woo'); ?>!</strong> <?php echo esc_html__('Update Settings Successfully..', 'fme_shopify_to_woo'); ?>
		</div>
	</div>
</div>
<style type="text/css">
	.select2-container{
  width: 100% !important;
}
</style>
<script type="text/javascript">
jQuery(document).ready(function() {
	"use strict";
	window.onbeforeunload = null;
	jQuery('.Fme_update_choosen').select2();
	if(200 > jQuery('#fme_qifw_store_product_count').val()){
	jQuery('.Fme_update_choosen_product').select2();
	
} else{
	jQuery('.Fme_update_choosen_product').select2({
					ajax: {
						url: "<?php echo filter_var(admin_url('admin-ajax.php')); ?>",
						dataType: 'json',
						delay: 250,
						data: function (params, page) {
							return {
								action: 'fme_qifw_get_products_array',
								term: params.term, 
								page: 10
							};
						},
						processResults: function (response) {
							return {
								results:response
							};
						},
						cache: true
					},
					minimumInputLength: 1,
				});
}

});
</script>
