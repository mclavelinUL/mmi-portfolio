<?php
/**
 * Désinstallation : nettoie ce que le plugin a créé dans les options.
 *
 * Les contenus (réalisations, apprentissages critiques, termes) sont
 * volontairement conservés : ce sont les données des étudiants, elles ne
 * doivent pas disparaître parce qu'on supprime l'extension.
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

$mmi_portfolio_options = array(
    'mmi_portfolio_version',
    'mmi_portfolio_rewrite_version',
    'mmi_portfolio_import_done',
    'mmi_portfolio_pending_import',
    'mmi_portfolio_niveaux_converted',
    'mmi_portfolio_niveaux_backup',
    'Activated_Plugin', // legacy
);

foreach ( $mmi_portfolio_options as $option ) {
    delete_option( $option );
    delete_site_option( $option );
}

delete_site_transient( 'mmi_portfolio_latest_release' );
delete_transient( 'mmi_portfolio_import_notice' );
delete_transient( 'mmi_portfolio_upgrading' );
delete_transient( 'mmi_portfolio_niveaux_notice' );

flush_rewrite_rules();
