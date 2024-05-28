function updateCartQuantities() {
    jQuery('.woocommerce-cart-form').find('.fme_quantity .qty').each(function() {
        var cartItemKey = jQuery(this).attr('name').match(/cart\[(.*?)\]/)[1];
        var quantityInput = jQuery(this);
        var cartQuantity = jQuery('input[name="cart[' + cartItemKey + '][qty]"]').val();
        quantityInput.val(cartQuantity);
    });
}

jQuery(document).ready(function() {
    "use strict";
    var fme_qifw_ptype = jQuery('#fme_qifw_ptype').val();
    var min_qty = parseFloat(jQuery('.fme_qifw_min_quantity').val());
    var qifw_step = parseFloat(jQuery('.fme_qifw_min_quantity').attr('step'));
    if (fme_qifw_ptype == 'variable') {
        jQuery('.qty').attr('min', min_qty);
        jQuery('.qty').attr('step', qifw_step);
        jQuery(".single_variation_wrap").on("show_variation", function(event, variation) {
            var minval = variation.min_qty = min_qty;
            jQuery('.qty').val(minval);
            jQuery('.qty').attr('min', minval);
            jQuery('.qty').attr('step', qifw_step);
            jQuery('.fmequantitys').next().hide();
        });
    }

    jQuery('input.minus').hide();
    jQuery('input.plus').hide();

    jQuery(document).on('change keyup', '.fme_quantity .qty', function() {
        updateProductPriceArchive();
    });

    updateProductPriceArchive(); // Initial call to set the price on archives

    updateCartQuantities(); // Initial call to set the quantity on cart page load
});

jQuery(function(jQuery) {
    jQuery('.woocommerce').on('click', '[name="update_cart"]', function() {
        setInterval(function() {
            jQuery('.qty').attr('readonly', 'readonly');
        }, 1000);
    });
});

function fme_upd_qty(product_id, min, max, steps) {
    var quantityval = jQuery('#pq-plus' + product_id).parent().siblings('.fme_quantity').find('.qty');
    var stockquantity = jQuery("#stockquantitypq" + product_id).val();
    var value = parseFloat(quantityval.val());
    var maxmiumerrormsg = jQuery('#fme_qifw_maxmium_error_msg' + product_id).val();
    if (isNaN(value)) {
        value = '';
    }
    if (value < min) {
        var minimumerrormsg = jQuery('#fme_qifw_minimum_error_msg' + product_id).val();
        jQuery('#errormsgminimum' + product_id).html(minimumerrormsg).show().delay(3000).fadeOut(); //also show a success message 
    } else {
        if (max != "") {
            fme_steps_interval(product_id, value, quantityval, max, stockquantity);
        } else {
            if (parseFloat(quantityval.val()) > parseFloat(stockquantity)) {
                jQuery('#errormsg' + product_id).html(maxmiumerrormsg).show().delay(3000).fadeOut(); //also show a success message 
            }
        }
    }
    updateProductPriceArchive();
    syncCartQuantity(product_id, value); // Sync with cart
}

function fme_pq_shop_plus(product_id, min, max, steps) {
    "use strict";
    var stepsinterval = jQuery('#pq-stepintervals' + product_id).val();
    var quantityval = jQuery('#pq-plus' + product_id).parent().siblings('.fme_quantity').find('.qty');
    var stockquantity = jQuery("#stockquantitypq" + product_id).val();
    var maxmiumerrormsg = jQuery('#fme_qifw_maxmium_error_msg' + product_id).val();
    if (max != "") {
        if (steps != "") {
            var value = (parseFloat(quantityval.val()) + parseFloat(steps));
            fme_steps_interval(product_id, value, quantityval, max, stockquantity);
        } else {
            var value = (parseFloat(quantityval.val()) + 1);
            fme_steps_interval(product_id, value, quantityval, max, stockquantity);
        }
    } else {
        if (parseFloat(quantityval.val()) > parseFloat(stockquantity)) {
            jQuery('#errormsg' + product_id).html(maxmiumerrormsg).show().delay(3000).fadeOut(); //also show a success message 
        } else {
            quantityval.val(parseFloat(quantityval.val()) + 1);
        }
    }

    jQuery("[name='update_cart']").removeAttr('disabled');
    jQuery('body').trigger('update_checkout');
    updateProductPriceArchive();
    syncCartQuantity(product_id, quantityval.val()); // Sync with cart
}

function fme_steps_interval(product_id, value, quantityval, max, stockquantity) {
    "use strict";
    var maxmiumerrormsg = jQuery('#fme_qifw_maxmium_error_msg' + product_id).val();
    if (parseFloat(value) > parseFloat(stockquantity)) {
        jQuery('#errormsg' + product_id).html(maxmiumerrormsg).show().delay(3000).fadeOut(); //also show a success message ;
    } else if (parseFloat(value) > parseFloat(max)) {
        var maxmiumerrormsg = jQuery('#fme_qifw_maxmium_error_msg' + product_id).val();
        jQuery('#errormsg' + product_id).html(maxmiumerrormsg).show().delay(3000).fadeOut(); //also show a success message 
    } else {
        quantityval.val(parseFloat(value.toFixed(2)));
        jQuery('a[data-product_id=' + product_id + ']').attr('data-quantity', parseFloat(value.toFixed(2)));
        jQuery('#pq-plus' + product_id).parent().siblings('.fme_quantity').find('.qty').attr('value', parseFloat(value.toFixed(2)));
        jQuery('#fmequantitys' + product_id).siblings('.fme_quantity').find('.qty').val(parseFloat(value.toFixed(2)));
        jQuery('div.quantity').find('.qty').val(parseFloat(value.toFixed(2)));
    }
    syncCartQuantity(product_id, value); // Sync with cart
}

function fme_shop_pq_minus(product_id, min, max, steps) {
    "use strict";
    var stepsinterval = jQuery('#pq-stepintervals' + product_id).val();
    var quantityval = jQuery('#pq-minus' + product_id).parent().siblings('.fme_quantity').find('.qty');
    if (min != "") {
        if (steps != "") {
            var value = (parseFloat(quantityval.val()) - parseFloat(steps));
            fme_steps_intervalminus(product_id, value, quantityval, min);
        } else {
            var value = (parseFloat(quantityval.val()) - 1);
            fme_steps_intervalminus(product_id, value, quantityval, min);
        }
    } else {
        if (quantityval.val() <= min) {

        } else {
            quantityval.val(parseFloat(quantityval.val()) - 1);
        }
    }

    jQuery("[name='update_cart']").removeAttr('disabled');
    jQuery('body').trigger('update_checkout');
    updateProductPriceArchive();
    syncCartQuantity(product_id, quantityval.val()); // Sync with cart
}

function fme_steps_intervalminus(product_id, value, quantityval, min) {
    "use strict";
    if (parseFloat(value) < parseFloat(min)) {
        var minimumerrormsg = jQuery('#fme_qifw_minimum_error_msg' + product_id).val();
        jQuery('#errormsgminimum' + product_id).html(minimumerrormsg).show().delay(3000).fadeOut(); //also show a success message 
    } else {
        quantityval.val(parseFloat(value.toFixed(2)));
        jQuery('a[data-product_id=' + product_id + ']').attr('data-quantity', parseFloat(value.toFixed(2)));
        jQuery('#pq-minus' + product_id).parent().siblings('.fme_quantity').find('.qty').attr('value', parseFloat(value.toFixed(2)));
        jQuery('#fmequantitys' + product_id).siblings('.fme_quantity').find('.qty').val(parseFloat(value.toFixed(2)));
        jQuery('div.quantity').find('.qty').val(parseFloat(value.toFixed(2)));
    }
    syncCartQuantity(product_id, value); // Sync with cart
}

function syncCartQuantity(product_id, quantity) {
    jQuery('input[name="cart[' + product_id + '][qty]"]').val(quantity);
}
