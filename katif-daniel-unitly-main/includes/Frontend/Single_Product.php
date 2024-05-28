<?php 

namespace UnitlyWoo\Frontend;

/**
 * This class modifies single product page
 */

class Single_Product  
{
    public $error = '';
    function __construct()
    {
        add_action('woocommerce_before_add_to_cart_button', array($this, 'insert_single_product_custom_fields'));
    }

    /**
     * Insert extra fields for the single product
     */
    public function insert_single_product_custom_fields()
    {
        global $product;

        if( $product->get_type() != "unit_product" ) {
            return;
        }

        $product_id = $product->get_id();

        $price_per_unit = get_post_meta( get_the_ID(), 'price_per_unit', true );

        $unit = get_post_meta( $product_id,'product_unit',true);

        if ( 'sq-feet' == $unit && isset($_POST['door_width']) ) {
            if ( empty( $_POST['door_width'] ) || empty( $_POST['door_height'] ) ) {
                $this->error = '<span class="error">*This field is required.</span>';
            }
        }

        if('sq-feet' == $unit ){
            require_once __DIR__ . '/templates/square_feet.php';
        } else {
            $min_units = get_post_meta($product_id, 'min_purchase_units', true);
            $max_units = get_post_meta($product_id, 'max_purchase_units', true);

            echo '<div class="unitly_woo_fields form-group">';

            echo '<label for="unitly_unit_amount">How much you want?' . $this->error . '</label><br>';

            $default_units = get_post_meta($product_id, 'default_purchase_units', true);

            echo '<input type="number" id="unitly_unit_amount" name="unitly_unit_amount" value="'.$default_units.'" min="'.$min_units.'" max="'.$max_units.'" step="1"> <span>'.$unit.'</span>';

            echo '<p class="error unit_amount_error"></p>';

            echo '<input type="hidden" id="price_per_unit" name="price_per_unit" value="'.$price_per_unit.'">';

            echo '</div>';
        }
        ?>

        <div class="form-group">
            <h2 id="unitly_total_cost" class="entry-title">Total Cost: <?php echo get_woocommerce_currency_symbol(); ?><span class="amount">0</span> </h2>
        </div>
    <?php   
    }
}