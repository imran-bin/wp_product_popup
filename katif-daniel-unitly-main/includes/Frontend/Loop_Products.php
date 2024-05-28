<?php 

namespace UnitlyWoo\Frontend;

/**
 * This class modifies loop products
 */

 class Loop_Products
 {
     function __construct()
     {
        add_filter( 'woocommerce_get_price_html', [$this, 'change_product_price_display'], 100, 2 );
        
         add_filter('woocommerce_loop_add_to_cart_link',  [ $this,'change_loop_add_to_cart_link'], 10, 2 );
       
        
     }

     /**
      * Change "Add to cart" link
      */
     public function change_loop_add_to_cart_link($link, $product)
     {
        if( $product->is_type( 'unit_product' ) ) {
         $link = sprintf('<a href="%s">Calculate Price</a>', get_permalink( get_the_ID()) );

         return $link;
        }
     }

     /**
      * Change all product price display
      */
     public function change_product_price_display( $price, $product ) {
        if( $product->is_type( 'unit_product' ) ) {
            $price = get_post_meta( $product->get_id(), 'price_per_unit', true );

            $unit = get_post_meta($product->get_id() ,'product_unit',true);

            $price .= ' /' . $unit;
            
            return get_woocommerce_currency_symbol() . $price;
        }
        return $price;   
    }




 





 }
 
