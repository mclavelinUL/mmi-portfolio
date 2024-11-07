<?php
include_once plugin_dir_path( __FILE__ ) . '/../vendor/advanced-custom-fields-pro/acf.php';

if ( ! function_exists( 'is_plugin_active' ) ) {
    include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

// Check if ACF PRO is active
if ( is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
    // Abort all bundling, ACF PRO plugin takes priority
    return;
}

// Check if another plugin or theme has bundled ACF
if ( defined( 'MY_ACF_PATH' ) ) {
    return;
}

define( 'MY_ACF_PATH', __DIR__ . '/../vendor/advanced-custom-fields-pro/' );
define( 'MY_ACF_URL', plugins_url() . '/mmi-portfolio/vendor/advanced-custom-fields-pro/' );


include_once( MY_ACF_PATH . 'acf.php' );

// Customize the URL setting to fix incorrect asset URLs.
add_filter('acf/settings/url', 'my_acf_settings_url');
function my_acf_settings_url( $url ) {
    return MY_ACF_URL;
}