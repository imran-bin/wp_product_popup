<?php 

namespace UnitlyWoo\Admin;

/**
 * This class modifies product list on admin panel
 */
class Admin_Product_List
{

    public function __construct()
    { 
        add_filter( 'manage_edit-product_columns', [$this, 'admin_products_unit_price_column'] );
        
        add_action( 'manage_product_posts_custom_column', [$this, 'admin_products_unit_price_column_content'], 10, 2 );
    }
    
    public function admin_products_unit_price_column( $columns ){
      $columns = array(
          'cb' => '<input type="checkbox" />',
          'thumb' => '<span class="wc-image tips" data-tip="Image">Image</span>',
          'name' => 'Name',
          'sku' => 'SKU',
          'unit_price' => 'Price',
          'is_in_stock' => 'Stock',
          'product_cat' => 'Categories',
          'product_tag' => 'Tags',
          'featured' => '<span class="wc-featured parent-tips" data-tip="Featured">Featured</span>',
          'date' => 'Date',
      );

      return $columns;
    }
    
    public function admin_products_unit_price_column_content( $column, $product_id ){
        $product = wc_get_product( $product_id );
        
        if ( $column == 'unit_price' ) {
          if( $product->is_type( 'unit_product' ) ) {
           
            $price = get_post_meta($product_id, 'price_per_unit', true);

            $unit = get_post_meta($product_id, 'product_unit', true);
           
            echo get_woocommerce_currency_symbol() . $price . ' /' . $unit;
        
          } else {
           
            $price = $product->get_price();
            
            echo get_woocommerce_currency_symbol() . $price;
          }
        }
    }
}

 