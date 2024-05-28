<?php 
namespace UnitlyWoo;

/**
 * Frontend handler class
 */
class Frontend
{
    function __construct()
    {
        new Frontend\Loop_Products();
        new Frontend\Single_Product();
        new Frontend\Cart_Item();

        add_action( 'wp_enqueue_scripts', [ $this, 'load_frontend_scripts' ] );
    }

    /**
     * Load Frontend scripts
     */
    public function load_frontend_scripts()
    {
        wp_enqueue_style('unitly_woo_css', UnitlyWoo_ASSETS . '/css/unitly-frontend.css', array(), null, 'all');

        wp_enqueue_script( 'unitly_woo_js', UnitlyWoo_ASSETS . '/js/unitly-frontend.js', array('jquery'), null, true );

    //    if (is_product()) {
    //     $painting_cost = get_post_meta(get_the_ID(), 'painting_cost', true);
    //     wp_localize_script('UnitlyWoo_main', 'UnitlyWoo_ajax_obj', [
    //         'ajaxurl' => admin_url('admin-ajax.php'),
    //         'painting_cost' => $painting_cost
    //     ]);
    //    }
    }
}
