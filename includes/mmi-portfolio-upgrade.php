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
        '1.4.0' => 'mmi_portfolio_migrate_140',
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

/**
 * Migration vers 1.4.0 : auto-évaluation sur 4 niveaux au lieu de 5.
 *
 * Conversion proportionnelle, round( 1 + ( v - 1 ) * 3 / 4 ) :
 *
 *     1 → 1     2 → 2     3 → 3     4 → 3     5 → 4
 *
 * Elle préserve le classement relatif : une réalisation auto-évaluée plus haut
 * qu'une autre sur l'ancienne échelle le reste sur la nouvelle. Un simple
 * écrêtage aurait au contraire ramené tous les 4 et tous les 5 sur la même
 * valeur, effaçant une distinction que les étudiants avaient exprimée.
 *
 * Les valeurs d'origine sont sauvegardées avant écriture : la conversion est
 * réversible via `wp mmi-portfolio restaurer-niveaux`.
 *
 * @param string $from Version précédemment installée.
 */
function mmi_portfolio_migrate_140( $from ) {
    global $wpdb;

    // Garde-fou : cette migration réécrit des données saisies par les
    // étudiants, elle ne doit jamais s'appliquer deux fois.
    if ( get_option( 'mmi_portfolio_niveaux_converted' ) ) {
        return;
    }

    $like = $wpdb->esc_like( 'apprentissages_critiques_engages_' ) . '%' . $wpdb->esc_like( '_niveau_estime' );

    $rows = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT meta_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key LIKE %s",
            $like
        )
    );

    if ( ! $rows ) {
        update_option( 'mmi_portfolio_niveaux_converted', gmdate( 'c' ), false );
        return;
    }

    $map    = mmi_portfolio_niveau_conversion_map();
    $backup = array();
    $done   = 0;

    foreach ( $rows as $row ) {
        $value = trim( (string) $row->meta_value );

        // Les lignes « _niveau_estime » jumelles stockent la clé du champ ACF.
        if ( '' === $value || ! ctype_digit( $value ) ) {
            continue;
        }

        $old = (int) $value;

        if ( ! isset( $map[ $old ] ) || $map[ $old ] === $old ) {
            continue;
        }

        $backup[ (int) $row->meta_id ] = $old;

        $wpdb->update(
            $wpdb->postmeta,
            array( 'meta_value' => (string) $map[ $old ] ),
            array( 'meta_id' => (int) $row->meta_id ),
            array( '%s' ),
            array( '%d' )
        );

        $done++;
    }

    if ( $backup ) {
        update_option( 'mmi_portfolio_niveaux_backup', $backup, false );
    }

    update_option( 'mmi_portfolio_niveaux_converted', gmdate( 'c' ), false );

    // Les valeurs viennent d'être modifiées directement en base.
    wp_cache_flush();

    set_transient(
        'mmi_portfolio_niveaux_notice',
        array( 'converted' => $done, 'scanned' => count( $rows ) ),
        5 * MINUTE_IN_SECONDS
    );
}

/**
 * Table de conversion de l'ancienne échelle vers la nouvelle.
 *
 * Isolée pour rester testable et relisible sans exécuter la migration.
 *
 * @return array<int, int>
 */
function mmi_portfolio_niveau_conversion_map() {
    $map = array();

    for ( $old = 1; $old <= 5; $old++ ) {
        $map[ $old ] = (int) round( 1 + ( $old - 1 ) * ( MMI_PORTFOLIO_NIVEAU_MAX - 1 ) / 4 );
    }

    return $map;
}

/**
 * Compte rendu de la conversion dans l'administration.
 */
function mmi_portfolio_niveaux_notice() {
    $data = get_transient( 'mmi_portfolio_niveaux_notice' );

    if ( ! is_array( $data ) ) {
        return;
    }

    delete_transient( 'mmi_portfolio_niveaux_notice' );

    printf(
        '<div class="notice notice-info is-dismissible"><p><strong>MMI Portfolio</strong> — auto-évaluation passée à %d niveaux : %d valeur(s) converties sur %d examinées. Les valeurs d\'origine sont sauvegardées (restauration possible via WP-CLI).</p></div>',
        (int) MMI_PORTFOLIO_NIVEAU_MAX,
        (int) $data['converted'],
        (int) $data['scanned']
    );
}
add_action( 'admin_notices', 'mmi_portfolio_niveaux_notice' );

/**
 * Restauration des auto-évaluations d'origine.
 *
 * @return int Nombre de valeurs restaurées.
 */
function mmi_portfolio_restore_niveaux() {
    global $wpdb;

    $backup = get_option( 'mmi_portfolio_niveaux_backup' );

    if ( ! is_array( $backup ) || ! $backup ) {
        return 0;
    }

    $restored = 0;

    foreach ( $backup as $meta_id => $value ) {
        $wpdb->update(
            $wpdb->postmeta,
            array( 'meta_value' => (string) (int) $value ),
            array( 'meta_id' => (int) $meta_id ),
            array( '%s' ),
            array( '%d' )
        );
        $restored++;
    }

    delete_option( 'mmi_portfolio_niveaux_converted' );
    wp_cache_flush();

    return $restored;
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
    WP_CLI::add_command(
        'mmi-portfolio restaurer-niveaux',
        function () {
            $n = mmi_portfolio_restore_niveaux();

            if ( ! $n ) {
                WP_CLI::warning( 'Aucune sauvegarde d\'auto-évaluation à restaurer.' );
                return;
            }

            WP_CLI::success( sprintf( '%d auto-évaluation(s) restaurées sur l\'échelle d\'origine.', $n ) );
        }
    );
}
