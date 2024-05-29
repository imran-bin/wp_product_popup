<?php

namespace UnitlyWoo\Frontend;

/**
 * This class modifies cart items
 */

class Cart_Item
{
    function __construct()
    {
        add_filter('woocommerce_add_to_cart_validation', [$this, 'add_the_input_validation'], 10, 3);

        add_filter('woocommerce_add_cart_item_data', [$this, 'add_cart_item_data'], 10, 3);

        add_filter('woocommerce_cart_item_price', [$this, 'set_cart_items_price'], 10, 2);

        add_action('woocommerce_before_calculate_totals', [$this, 'before_calculate_totals'], 10, 1);

        add_filter('woocommerce_get_item_data', [$this, 'get_item_data'], 10, 2);

        add_action('woocommerce_add_order_item_meta', [$this, 'add_order_item_meta'], 10, 3);
    }

    // Add order item meta.
    function add_order_item_meta($item_id, $cart_item, $cart_item_key)
    {
        if (isset($cart_item['unitly_unit_amount'])) {
            $value = wc_clean($cart_item['unitly_unit_amount']) . ' ' . wc_clean($cart_item['unitly_unit']);
        }

        wc_add_order_item_meta($item_id, __("Amount", "unitly-woocommerce"), $value);
    }

    /**
     * Display custom item data in the cart
     */
    function get_item_data($item_data, $cart_item_data)
    {
        if (isset($cart_item_data['unitly_unit_amount'])) {
            if (is_cart()) {
                $item_data[] = array(
                    'key'   => __('Amount', 'unitly-woocommerce'),
                    'value' => wc_clean($cart_item_data['unitly_unit_amount']) . ' ' . wc_clean($cart_item_data['unitly_unit']),
                );
            } else {
                $item_data[] = array(
                    'key'   => __('Amount', 'unitly-woocommerce'),
                    'value' => wc_clean($cart_item_data['unitly_unit_amount']) . ' ' . wc_clean($cart_item_data['unitly_unit']) . ' x ' . $cart_item_data['quantity'],
                );
            }
        }
        return $item_data;
    }

    /**
     * Add to cart validation (prevent if empty width and height)
     */
    public function add_the_input_validation($passed, $product_id, $quantity)
    {
        $product = wc_get_product($product_id);
        $unit = get_post_meta($product_id, 'product_unit', true);

        $min_units = get_post_meta($product_id, 'min_purchase_units', true);
        $max_units = get_post_meta($product_id, 'max_purchase_units', true);

        if ($product->get_type() != "unit_product") {
            return $passed;
        } else {

            if ('sq-feet' == $unit && (empty($_POST['door_width']) || empty($_POST['door_height']))) {
                $notice_text = 'Please enter product width and height.';
                wc_add_notice(__($notice_text, 'unitly-woocommerce'), 'error');
                return false;
            }

            if (isset($_POST['unitly_unit_amount']) && ($_POST['unitly_unit_amount'] < $min_units || $_POST['unitly_unit_amount'] > $max_units)) {
                $notice_text = "Unit amount should be between " . $min_units . " and " . $max_units . ".";
                wc_add_notice(__($notice_text, 'unitly-woocommerce'), 'error');
                return false;
            }

            return $passed;
        }
    }

    /**
     * Add our calculated price to Cart Item Data
     */
    public function add_cart_item_data($cart_item_data, $product_id, $variation_id)
    {
        $product = wc_get_product($product_id);

        if ($product->get_type() != "unit_product") {
            return $cart_item_data;
        }

        $unit = get_post_meta($product_id, 'product_unit', true);
        $unit_price = get_post_meta($product_id, 'price_per_unit', true);

        if ('sq-feet' == $unit) {
            $area = ($_POST['door_width'] / 12) * ($_POST['door_height'] / 12);
            $area = number_format($area, 2);

            $painting_cost = get_post_meta($product_id, 'painting_cost', true);

            if (isset($_POST['door_painting']) && !empty($_POST['door_painting'])) {
                $cart_item_data['unitly_unit_price'] = $unit_price * $area + $painting_cost;
            } else {
                $cart_item_data['unitly_unit_price'] = $area * $unit_price;
            }
        } else {
            $cart_item_data['unitly_unit'] = $unit;
            $cart_item_data['unitly_unit_amount'] = $_POST['unitly_unit_amount'];
            $cart_item_data['unitly_unit_price'] = $unit_price * $_POST['unitly_unit_amount'];
        }

        $cart_item_data['unitly_unit_price'] = number_format($cart_item_data['unitly_unit_price'], 2);

        return $cart_item_data;
    }

    public function set_cart_items_price($price, $item)
    {
        if (isset($item['unitly_unit_price'])) {
            $price = $item['unitly_unit_price'];
            $price = wc_price($price);
        }
        return $price;
    }

    /**
     * Set our price to each cart item before calculate total cost
     */
    public function before_calculate_totals($cart_obj)
    {
        if (is_admin() && !defined('DOING_AJAX')) {
            return;
        }

        foreach ($cart_obj->get_cart() as $key => $value) {
            if (isset($value['unitly_unit_price'])) {
                $price = $value['unitly_unit_price'];
                $value['data']->set_price(($price));
            }
        }
    }
}
