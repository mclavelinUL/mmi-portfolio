<?php
/*
 * Plugin Name: MMI Portfolio
 * Description: Plugin pour la gestion du portfolio en BUT MMI
 * Version: 1.2.0
 * Author: Mathias Clavelin
 * Text Domain: mmi-portfolio
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

// Security
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

define( 'MMI_PORTFOLIO_VERSION', '1.2.0' );
define( 'MMI_PORTFOLIO_PATH', plugin_dir_path( __FILE__ ) );
define( 'MMI_PORTFOLIO_URL', plugin_dir_url( __FILE__ ) );

// Load dependencies
require_once MMI_PORTFOLIO_PATH . 'includes/mmi-portfolio-loader.php';

// Init data (CPT, taxonomies, champs ACF, import)
require_once MMI_PORTFOLIO_PATH . 'data/mmi-portfolio-data.php';

// Logic
require_once MMI_PORTFOLIO_PATH . 'includes/mmi-portfolio-helpers.php';
require_once MMI_PORTFOLIO_PATH . 'includes/mmi-portfolio.php';

/**
 * Activation : enregistre les règles de réécriture puis les flushe une seule fois.
 * L'import XML est seulement *marqué* comme à faire : les CPT et taxonomies ne sont
 * pas encore enregistrés pendant le hook d'activation, l'import réel a lieu au
 * prochain admin_init (cf. data/mmi-portfolio-data.php).
 */
register_activation_hook( __FILE__, 'mmi_portfolio_activate' );
function mmi_portfolio_activate() {
    mmi_portfolio_register_rewrite_rules();
    flush_rewrite_rules();

    if ( ! get_option( 'mmi_portfolio_import_done' ) ) {
        update_option( 'mmi_portfolio_pending_import', 1 );
    }
}

/**
 * Désactivation : nettoie les règles de réécriture laissées par le plugin.
 */
register_deactivation_hook( __FILE__, 'mmi_portfolio_deactivate' );
function mmi_portfolio_deactivate() {
    flush_rewrite_rules();
}
