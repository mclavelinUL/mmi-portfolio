<?php
/**
 * Chargement conditionnel d'ACF Pro embarqué dans vendor/.
 *
 * L'include doit rester APRES les garde-fous : si ACF Pro est déjà actif comme
 * extension (ou embarqué par un autre plugin/thème), on ne charge rien, sous
 * peine de double déclaration et d'erreur fatale.
 */

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

if ( ! function_exists( 'is_plugin_active' ) ) {
    include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

// ACF PRO est déjà actif comme extension : il a la priorité.
if ( is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) || class_exists( 'ACF' ) ) {
    return;
}

// Un autre plugin ou thème a déjà embarqué ACF.
if ( defined( 'MY_ACF_PATH' ) ) {
    return;
}

define( 'MY_ACF_PATH', __DIR__ . '/../vendor/advanced-custom-fields-pro/' );
define( 'MY_ACF_URL', plugins_url() . '/mmi-portfolio/vendor/advanced-custom-fields-pro/' );

if ( ! file_exists( MY_ACF_PATH . 'acf.php' ) ) {
    add_action( 'admin_notices', function () {
        echo '<div class="notice notice-error"><p><strong>MMI Portfolio</strong> : ACF Pro est introuvable dans <code>vendor/advanced-custom-fields-pro/</code>. Le plugin ne peut pas fonctionner.</p></div>';
    } );
    return;
}

include_once MY_ACF_PATH . 'acf.php';

// Corrige les URL des assets d'ACF embarqué.
add_filter( 'acf/settings/url', 'my_acf_settings_url' );
function my_acf_settings_url( $url ) {
    return MY_ACF_URL;
}
