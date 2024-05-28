jQuery(document).ready(function() {
	"use strict";
	window.onbeforeunload = null;
	jQuery('#Fme_Qifw-product').select2();

	if(200 > jQuery('#fme_qifw_store_product_count').val()){
		
				jQuery('#Fme_Qifw-product').select2();
			}else{
				jQuery('#Fme_Qifw-product').select2({
					ajax: {
						url: ewcpm_php_vars.admin_url,
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


	jQuery('#Fme_Qifw-category').select2();
	jQuery('#Fme_choosen-user-role').select2();
	jQuery('#fme_qifw_delete_general_settings').hide();
});
jQuery(document).ready(function() {
	
    jQuery('#fme_datatable').DataTable();
   
} );

function Fme_QIFW_choosen_product_cateory(fme_qifw_status) {
	"use strict";
	if('fme_create' == fme_qifw_status) {
		var fme_product_category = jQuery('#fmeproductcategory').val();
		if('fme_qifw_category' == fme_product_category) {
			jQuery('#fme_qifw_Products').hide();
			jQuery('#fme_qifw_category').show();
		} else if('fme_qifw_product' == fme_product_category) {
			jQuery('#fme_qifw_Products').show();
			jQuery('#fme_qifw_category').hide();
		} else {
			jQuery('#fme_qifw_Products').hide();
			jQuery('#fme_qifw_category').hide();
		}
	} else {

		var fme_product_category = jQuery('#fmeupdateproductcategory').val();
		if('fme_qifw_category' == fme_product_category) {
			jQuery('#fme_qifw_update_Products').hide();
			jQuery('#fme_qifw_update_category').show();
		} else if('fme_qifw_product' == fme_product_category) {
			jQuery('#fme_qifw_update_Products').show();
			jQuery('#fme_qifw_update_category').hide();
		} else {
			jQuery('#fme_qifw_update_Products').hide();
			jQuery('#fme_qifw_update_category').hide();
		}
	}
}


function fme_save_settings(fme_qifw_status) {
	"use strict";
	if ('fme_create' == fme_qifw_status) {

		var fme_enable_disable = jQuery('#fme_enable_disable').val();
		var fme_qifw_minimum = jQuery('#fme_qifw_minimum').val();
		var fme_qifw_maxmium = jQuery('#fme_qifw_maxmium').val();
		var fme_qifw_step = jQuery('#fme_qifw_step').val();
		var fme_qifw_readonly = jQuery('#fme_qifw_readonly:checked').val() || "";
		var fmeproductcategory = jQuery('#fmeproductcategory').val();
		var fme_qifw_priority = jQuery('#fme_qifw_priority').val();

		if('fme_qifw_product' == fmeproductcategory) {
			var fme_selected_pro_cat = jQuery('#Fme_Qifw-product').val(); 
		} else if('fme_qifw_category'==fmeproductcategory) {
			var fme_selected_pro_cat = jQuery('#Fme_Qifw-category').val(); 
		} else if('all_products' == fmeproductcategory) {
			var fme_selected_pro_cat = 'all_products';
		}
		var fme_selected_user_role = jQuery('#Fme_choosen-user-role').val();

		if(parseInt(fme_qifw_maxmium) < parseInt(fme_qifw_minimum)){

			alert('Minimum quantity should be less than maximum quantity');

		} else if(fme_qifw_priority=='') {
			alert('Rule Priority is manadatory!');
		} else {
			jQuery.ajax({
				url: ewcpm_php_vars.admin_url,
				type: 'post',
				data: {
					action: 'fme_qifw_save_settings',
					fme_enable_disable:fme_enable_disable,
					fme_qifw_minimum:fme_qifw_minimum,
					fme_qifw_maxmium:fme_qifw_maxmium,
					fme_qifw_step:fme_qifw_step,
					fme_qifw_readonly:fme_qifw_readonly,
					fme_qifw_priority:fme_qifw_priority,
					fmeproductcategory:fmeproductcategory,
					fme_selected_pro_cat:fme_selected_pro_cat,
					fme_selected_user_role:fme_selected_user_role,
					fme_qifw_nonce : ewcpm_php_vars.admin_ajax_nonce
				},
				success: function (data) {
					jQuery('#fme_qifw_success_update').show();	
					setTimeout(function(){
						jQuery('#fme_qifw_success_update').slideToggle();
						location.reload();
					}, 2000);
					
					window.onbeforeunload = null;
				}   
			});
		}

	} else if ('fme_edit' == fme_qifw_status) {

		var fme_enable_disable = jQuery('#fme_update_enable_disable').val();
		var fme_qifw_minimum = jQuery('#fme_update_qifw_minimum').val();
		var fme_qifw_maxmium = jQuery('#fme_update_qifw_maxmium').val();
		var fme_qifw_step = jQuery('#fme_update_qifw_step').val();
		var fme_qifw_readonly = jQuery('#fme_update_qifw_readonly:checked').val() || "";
		var fmeproductcategory = jQuery('#fmeupdateproductcategory').val();
		var fme_qifw_update_priority = jQuery('#fme_qifw_update_priority').val();
		if('fme_qifw_product' == fmeproductcategory) {
			var fme_selected_pro_cat = jQuery('#Fme_Qifw-update-product').val(); 
		} else if('fme_qifw_category'==fmeproductcategory) {
			var fme_selected_pro_cat = jQuery('#Fme_Qifw-update-category').val(); 
		} else if('all_products' == fmeproductcategory) {
			var fme_selected_pro_cat = 'all_products';
		}
		var fme_selected_user_role = jQuery('#Fme_choosen-user-update-role').val();
		var fme_update_post_id = jQuery('#fme_update_post_id').val();
		if(parseInt(fme_qifw_maxmium) < parseInt(fme_qifw_minimum)){

			alert('Minimum quantity should be less than maximum quantity');

		} else if(fme_qifw_update_priority=='') {
			
			alert('Rule Priority is manadatory!');

		} else {
			jQuery.ajax({
				url: ewcpm_php_vars.admin_url,
				type: 'post',
				data: {
					action: 'fme_qifw_update_settings',
					fme_enable_disable:fme_enable_disable,
					fme_qifw_minimum:fme_qifw_minimum,
					fme_qifw_maxmium:fme_qifw_maxmium,
					fme_qifw_step:fme_qifw_step,
					fme_qifw_readonly:fme_qifw_readonly,
					fme_qifw_update_priority:fme_qifw_update_priority,
					fmeproductcategory:fmeproductcategory,
					fme_selected_pro_cat:fme_selected_pro_cat,
					fme_selected_user_role:fme_selected_user_role,
					fme_update_post_id:fme_update_post_id,
					fme_qifw_nonce : ewcpm_php_vars.admin_ajax_nonce
				},
				success: function (data) {
					
						location.reload();
					window.onbeforeunload = null;
				}   
			});	
		}
	}
}

function fme_delete_rules(fme_del_id){
	"use strict";
	if(confirm('Are you sure you want to delete this item?')){
		jQuery.ajax({
			url: ewcpm_php_vars.admin_url,
			type: 'post',
			data: {
				action: 'fme_qifw_delete_general_settings',
				fme_del_id:fme_del_id,
				fme_qifw_nonce : ewcpm_php_vars.admin_ajax_nonce
			},
			success: function (data) {
				jQuery('#fme_row'+fme_del_id).remove();
				jQuery('#fme_qifw_delete_general_settings').show();	
				setTimeout(function(){
					jQuery('#fme_qifw_delete_general_settings').slideToggle();
				}, 3000);
				window.onbeforeunload = null;

			}   
		});
	}	

	
}

function fme_general_settings() {
	"use strict";
	var fme_qifw_button_style = jQuery('#fme_qifw_button_style').val();
	var fme_qifw_button_label = jQuery('#fme_qifw_button_label').val();
	var fme_qifw_Quantity_field_width = jQuery('#fme_qifw_Quantity_field_width').val();
	var fme_qifw_button_font_size = jQuery('#fme_qifw_button_font_size').val();
	var fme_qifw_button_font_color = jQuery('#fme_qifw_button_font_color').val();
	var fme_qifw_limit_minimum_error= jQuery('#fme_qifw_limit_minimum_error').val();
	var fme_qifw_limit_maximum_error= jQuery('#fme_qifw_limit_maximum_error').val();

	jQuery.ajax({
		url: ewcpm_php_vars.admin_url,
		type: 'post',
		data: {
			action: 'fme_qifw_save_general_settings',
			fme_qifw_button_style:fme_qifw_button_style,
			fme_qifw_button_label:fme_qifw_button_label,
			fme_qifw_Quantity_field_width:fme_qifw_Quantity_field_width,
			fme_qifw_button_font_size:fme_qifw_button_font_size,
			fme_qifw_button_font_color:fme_qifw_button_font_color,
			fme_qifw_limit_maximum_error:fme_qifw_limit_maximum_error,
			fme_qifw_limit_minimum_error:fme_qifw_limit_minimum_error,
			fme_qifw_nonce : ewcpm_php_vars.admin_ajax_nonce
		},
		success: function (data) {
			jQuery('#fme_qifw_success_general_settings').show();	
			setTimeout(function(){
				jQuery('#fme_qifw_success_general_settings').slideToggle();
				location.reload();
			}, 2000);
			window.onbeforeunload = null;
			
		}   
	});

}

function fme_edit_rules(fme_post_id) {
	"use strict";
	jQuery.ajax({
		url: ewcpm_php_vars.admin_url,
		type: 'post',
		data: {
			action: 'fme_qifw_update_general_settings',
			fme_post_id: fme_post_id,
			fme_qifw_nonce : ewcpm_php_vars.admin_ajax_nonce
		},
		success: function (data) {
			jQuery('#fme_update_content').html(data);
			window.onbeforeunload = null;
		}   
	});
}