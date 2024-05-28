<?php

namespace UnitlyWoo\Admin;

/**
 * Class that generates Unit Price tab and it's content within product data
 */
class Woo_Settings
{
    function __construct()
    {
        add_filter('woocommerce_settings_tabs_array', [$this, 'add_woo_settings_tab'], 50);
    }

    function add_woo_settings_tab($settings_tabs)
    {
        $settings_tabs['unitly-woo'] = _x('Unitly', 'unitly-woocommerce');
        return $settings_tabs;
    }
}
