<?php
/*
 * Plugin Name:       MMI Portfolio
 * Plugin URI:        https://github.com/mclavelinUL/mmi-portfolio
 * Description:       Gestion du portfolio pédagogique des étudiants en BUT MMI : réalisations, apprentissages critiques et traces.
 * Version:           1.3.0
 * Author:            Mathias Clavelin
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       mmi-portfolio
 * Domain Path:       /languages
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Update URI:        https://github.com/mclavelinUL/mmi-portfolio
 */

// Security
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

define( 'MMI_PORTFOLIO_VERSION', '1.3.0' );
define( 'MMI_PORTFOLIO_FILE', __FILE__ );
define( 'MMI_PORTFOLIO_PATH', plugin_dir_path( __FILE__ ) );
define( 'MMI_PORTFOLIO_URL', plugin_dir_url( __FILE__ ) );

// Dépendances (ACF Pro) et messages associés.
require_once MMI_PORTFOLIO_PATH . 'includes/mmi-portfolio-requirements.php';

// Mises à jour depuis les releases GitHub.
require_once MMI_PORTFOLIO_PATH . 'includes/mmi-portfolio-updater.php';

// Migrations de données entre versions.
require_once MMI_PORTFOLIO_PATH . 'includes/mmi-portfolio-upgrade.php';

// Types de contenu, taxonomies, champs ACF et import.
require_once MMI_PORTFOLIO_PATH . 'data/mmi-portfolio-data.php';

// Helpers de présentation.
require_once MMI_PORTFOLIO_PATH . 'includes/mmi-portfolio-helpers.php';

// Routing, templates et assets.
require_once MMI_PORTFOLIO_PATH . 'includes/mmi-portfolio.php';

/**
 * Activation : enregistre les règles de réécriture puis les flushe une seule fois.
 *
 * L'import XML est seulement *marqué* comme à faire : les CPT et taxonomies ne
 * sont pas encore enregistrés pendant le hook d'activation, l'import réel a lieu
 * au prochain admin_init (cf. data/mmi-portfolio-data.php).
 */
register_activation_hook( __FILE__, 'mmi_portfolio_activate' );
function mmi_portfolio_activate() {
    mmi_portfolio_register_rewrite_rules();
    flush_rewrite_rules();

    if ( ! get_option( 'mmi_portfolio_import_done' ) ) {
        update_option( 'mmi_portfolio_pending_import', 1 );
    }

    // Une installation neuve n'a aucune migration à rejouer.
    if ( ! get_option( 'mmi_portfolio_version' ) && ! get_option( 'mmi_portfolio_import_done' ) ) {
        update_option( 'mmi_portfolio_version', MMI_PORTFOLIO_VERSION );
    }
}

/**
 * Désactivation : nettoie les règles de réécriture et le cache de mise à jour.
 */
register_deactivation_hook( __FILE__, 'mmi_portfolio_deactivate' );
function mmi_portfolio_deactivate() {
    flush_rewrite_rules();
    delete_site_transient( MMI_PORTFOLIO_CACHE_KEY );
}
