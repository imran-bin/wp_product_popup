<?php 
namespace UnitlyWoo;

/**
 * Admin handler class
 */
class Admin
{
    function __construct()
    {
        new Admin\Unit_Price_Tab();
        new Admin\Admin_Product_List();
        new Admin\Woo_Settings();

        add_action( 'admin_enqueue_scripts', [ $this, 'unitly_woo_load_admin_script' ] );
    }

    public function unitly_woo_load_admin_script($hook)
    {
    //    if ( is_singular( 'product' ) ) {
            wp_enqueue_script( 'single-product-admin', UnitlyWoo_ASSETS. '/js/single-product-admin.js', array('jquery'), null, true );
        // }
    }
}
