<?php
/**
 * Migrations de données entre versions.
 *
 * Toute version qui a besoin de toucher à la base ajoute une entrée dans
 * mmi_portfolio_migrations(). Les migrations manquantes sont rejouées dans
 * l'ordre des versions, une seule fois, quelle que soit la version depuis
 * laquelle le site est mis à jour — y compris s'il saute plusieurs versions.
 */

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

/**
 * Liste ordonnée des migrations.
 *
 * Clé   : version qui introduit la migration.
 * Valeur: fonction appelée avec la version précédemment installée.
 *
 * @return array<string, callable-string>
 */
function mmi_portfolio_migrations() {
    return array(
        '1.2.0' => 'mmi_portfolio_migrate_120',
    );
}

/**
 * Exécute les migrations en attente puis enregistre la version courante.
 */
function mmi_portfolio_maybe_upgrade() {
    $installed = get_option( 'mmi_portfolio_version', '' );

    // Premier passage sur une installation antérieure au suivi de version :
    // on part de 0.0.0 pour rejouer toutes les migrations.
    if ( '' === $installed ) {
        $installed = '0.0.0';
    }

    if ( version_compare( $installed, MMI_PORTFOLIO_VERSION, '>=' ) ) {
        return;
    }

    // Verrou court : deux requêtes simultanées ne doivent pas migrer en même temps.
    if ( get_transient( 'mmi_portfolio_upgrading' ) ) {
        return;
    }
    set_transient( 'mmi_portfolio_upgrading', 1, 5 * MINUTE_IN_SECONDS );

    foreach ( mmi_portfolio_migrations() as $version => $callback ) {
        // Déjà jouée sur ce site.
        if ( version_compare( $installed, $version, '>=' ) ) {
            continue;
        }

        // Postérieure au code installé : ne peut pas arriver en temps normal,
        // mais on ne rejoue jamais une migration qu'on ne sait pas honorer.
        if ( version_compare( $version, MMI_PORTFOLIO_VERSION, '>' ) ) {
            continue;
        }

        if ( is_callable( $callback ) ) {
            call_user_func( $callback, $installed );
        }
    }

    update_option( 'mmi_portfolio_version', MMI_PORTFOLIO_VERSION );
    delete_transient( 'mmi_portfolio_upgrading' );

    /**
     * Permet de brancher un traitement après une mise à jour.
     *
     * @param string $installed Version précédemment installée.
     * @param string $current   Version désormais installée.
     */
    do_action( 'mmi_portfolio_upgraded', $installed, MMI_PORTFOLIO_VERSION );
}
add_action( 'admin_init', 'mmi_portfolio_maybe_upgrade', 5 );

/**
 * Migration vers 1.2.0.
 *
 * @param string $from Version précédemment installée.
 */
function mmi_portfolio_migrate_120( $from ) {
    // L'ancien hook d'activation posait une option qui n'a jamais servi.
    delete_option( 'Activated_Plugin' );

    // Les règles de réécriture changent (pagination de l'archive).
    delete_option( 'mmi_portfolio_rewrite_version' );

    // Sur un site qui tourne déjà, l'import a forcément eu lieu : on le marque
    // comme fait pour qu'il ne soit pas reprogrammé par une réactivation.
    if ( '0.0.0' !== $from && ! get_option( 'mmi_portfolio_import_done' ) ) {
        $existing = get_posts(
            array(
                'post_type'      => 'ac',
                'post_status'    => 'any',
                'posts_per_page' => 1,
                'fields'         => 'ids',
            )
        );

        if ( $existing ) {
            update_option( 'mmi_portfolio_import_done', 'migration-1.2.0' );
            delete_option( 'mmi_portfolio_pending_import' );
        }
    }
}
