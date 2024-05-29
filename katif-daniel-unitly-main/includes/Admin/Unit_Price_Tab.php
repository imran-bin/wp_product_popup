<?php 

namespace UnitlyWoo\Admin;

/**
 * Class that generates Unit Price tab and it's content within product data
 */
class Unit_Price_Tab  
{
    function __construct()
    {
        add_filter( 'woocommerce_product_data_tabs', [$this, 'add_unitly_product_tab'] );
        
        add_filter( 'woocommerce_product_data_panels', [$this, 'unit_price_tab_content'] );

        add_action( 'woocommerce_process_product_meta', [$this, 'save_unit_price_fields'] );
    }

    /**
     * Add a Unit Price product data tab
     */
    public function add_unitly_product_tab( $tabs )
    {
        $_product = wc_get_product( get_the_ID() );

        if( $_product->is_type( 'unit_product' ) ) {

        }

        $new_tabs['unitly_unit_price'] = array(
            'label' 	=> __( 'Unit Price (Unitly)', 'unitly-woocommerce' ),
            'target' 	=> 'unit_price_options',
            'class'     => array('show_if_unit_product')
        );
       
        // $insert_at_position = 2; // Change this for desire position
        // $tabs = array_slice( $tabs, 0, $insert_at_position, true ); // First part of original tabs
        // $tabs = array_merge( $tabs, $new_tabs ); // Add new
        // $tabs = array_merge( $tabs, array_slice( $tabs, $insert_at_position, null, true ) ); // Glue the second part of original
       
        $tabs = array_merge($tabs, $new_tabs);
        // print_r($tabs);
        // die();

        return $tabs;      
    }


    /**
     * Generate Unit Price product data tab content
     */
    public function unit_price_tab_content()
    {
        global $post;

        echo  '<div id="unit_price_options"  class="panel woocommerce_options_panel">';

        echo '<div class="options_group">';
        $product_unit = get_post_meta( $post->ID, 'product_unit', true );

        $options = array(
            'feet' => 'Feet',
            'sq-feet' => 'Square Feet',
            'meter' => 'Meter',
            'liter' => 'Liter',
            'gram' => 'Gram', 
            'kg' => 'Kilo Gram (KG)',
            'custom'=>'custom '
        ); 

        woocommerce_wp_select( array(
            'id'    =>  'product_unit',
            'label'   => __( 'Select Product Unit', 'unitly-woocommerce' ),
            'options' =>  $options,
            'value'   => $product_unit,
        ) );
        woocommerce_wp_text_input( array(
            
            'label' => 'Custom Unit (' . get_woocommerce_currency_symbol() . ')',
            'class' => 'short wc_input_price',
            'id' => 'product_custom_unit',
            'type' => 'text',
        ) );

        echo '</div>';
        

        echo '<div class="options_group">';
        $price_per_unit = get_post_meta( $post->ID, 'price_per_unit', true );
        
        woocommerce_wp_text_input( array(
            'label' => 'Price Per Unit (' . get_woocommerce_currency_symbol() . ')',
            'class' => 'short wc_input_price',
            'id' => 'price_per_unit',
            'type' => 'text',
        ) );

       

        woocommerce_wp_text_input( array(
            'label' => 'Min. Units to Purchase',
            'class' => 'short wc_input_price',
            'id' => 'min_purchase_units',
            'type' => 'text',
        ) );

        woocommerce_wp_text_input( array(
            'label' => 'Max. Units to Purchase',
            'class' => 'short wc_input_price',
            'id' => 'max_purchase_units',
            'type' => 'text',
        ) );

        
        woocommerce_wp_text_input( array(
            'label' => 'Default Units for Input',
            'class' => 'short wc_input_price',
            'id' => 'default_purchase_units',
            'type' => 'text',
        ) );

        echo '</div>';

        echo '<div class="options_group">';

        $painting_cost = get_post_meta( $post->ID, 'painting_cost', true );

        echo '</div>';

        echo '</div>';
    }

    /**
     * Save fields value
     */
    public function save_unit_price_fields($post_id)
    {
        if (isset($_POST['product_unit'])) {
            update_post_meta( $post_id, 'product_unit', $_POST['product_unit'] );
        }

        if (isset($_POST['price_per_unit'])) {
            update_post_meta( $post_id, 'price_per_unit', $_POST['price_per_unit'] );
        }

        if (isset($_POST['min_purchase_units'])) {
            update_post_meta( $post_id, 'min_purchase_units', $_POST['min_purchase_units'] );
        }

        if (isset($_POST['max_purchase_units'])) {
            update_post_meta( $post_id, 'max_purchase_units', $_POST['max_purchase_units'] );
        }

        if (isset($_POST['default_purchase_units'])) {
            update_post_meta( $post_id, 'default_purchase_units', $_POST['default_purchase_units'] );
        }
    }
}





