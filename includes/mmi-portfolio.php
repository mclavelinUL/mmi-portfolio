<?php
/**
 * Routing, templates et assets du portfolio MMI.
 */

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

/**
 * Associe une query var custom_template à un template du plugin.
 */
function mmi_portfolio_template_include( $template ) {
    $custom_template = get_query_var( 'custom_template' );

    if ( empty( $custom_template ) ) {
        return $template;
    }

    $templates = array(
        'portfolio'             => 'archive-portfolio.php',
        'portfolio_pedagogique' => 'archive-portfolio-pedagogique.php',
        'pedagogie_item'        => 'single-portfolio-pedagogique.php',
    );

    if ( isset( $templates[ $custom_template ] ) ) {
        $new_template = plugin_dir_path( __FILE__ ) . '../templates/' . $templates[ $custom_template ];
        if ( file_exists( $new_template ) ) {
            return $new_template;
        }
    }

    return $template;
}
add_filter( 'template_include', 'mmi_portfolio_template_include' );

/**
 * Retourne le slug de l'élément demandé par les URL personnalisées.
 *
 * @return string
 */
function mmi_portfolio_get_item_slug() {
    foreach ( array( 'pedagogie_item', 'portfolio_item' ) as $var ) {
        $value = get_query_var( $var );
        if ( ! empty( $value ) ) {
            return sanitize_title( $value );
        }
    }

    return '';
}

/**
 * URL personnalisées des réalisations (archives et fiche détail).
 */
function mmi_portfolio_register_rewrite_rules() {
    // Archive portfolio pro.
    add_rewrite_rule(
        '^portfolio/realisation/?$',
        'index.php?post_type=realisation&custom_template=portfolio',
        'top'
    );

    // Archive portfolio pédagogique.
    add_rewrite_rule(
        '^portfolio-pedagogique/realisation-mmi/?$',
        'index.php?post_type=realisation-mmi&custom_template=portfolio_pedagogique',
        'top'
    );

    // Archive portfolio pédagogique paginée (/page/2/...).
    add_rewrite_rule(
        '^portfolio-pedagogique/realisation-mmi/page/([0-9]{1,})/?$',
        'index.php?post_type=realisation-mmi&custom_template=portfolio_pedagogique&paged=$matches[1]',
        'top'
    );

    // Fiche détail pédagogique.
    add_rewrite_rule(
        '^pedagogie-item/([^/]*)/?$',
        'index.php?pedagogie_item=$matches[1]&custom_template=pedagogie_item',
        'top'
    );
}
add_action( 'init', 'mmi_portfolio_register_rewrite_rules' );

/**
 * Query vars utilisées par les règles ci-dessus.
 */
function mmi_portfolio_register_query_vars( $vars ) {
    $vars[] = 'custom_template';
    $vars[] = 'pedagogie_item';

    return $vars;
}
add_filter( 'query_vars', 'mmi_portfolio_register_query_vars' );

/**
 * Vrai ou faux : la requête courante est-elle une page du plugin ?
 */
function mmi_portfolio_is_plugin_view() {
    return (bool) get_query_var( 'custom_template' );
}

/**
 * Assets du portfolio.
 *
 * Chargés uniquement sur les vues du plugin : l'ancienne version poussait
 * Swiper et Font Awesome sur toutes les pages du site.
 */
function mmi_portfolio_enqueue_assets() {
    if ( ! mmi_portfolio_is_plugin_view() ) {
        return;
    }

    $base = plugin_dir_path( __FILE__ ) . '../templates/';
    $url  = plugin_dir_url( __FILE__ ) . '../templates/';

    $css_version = file_exists( $base . 'portfolio.css' ) ? filemtime( $base . 'portfolio.css' ) : MMI_PORTFOLIO_VERSION;
    $js_version  = file_exists( $base . 'portfolio.js' ) ? filemtime( $base . 'portfolio.js' ) : MMI_PORTFOLIO_VERSION;

    // Swiper n'est utile que sur la fiche détail.
    if ( 'pedagogie_item' === get_query_var( 'custom_template' ) ) {
        wp_enqueue_style( 'swiper', 'https://unpkg.com/swiper@11/swiper-bundle.min.css', array(), '11' );
        wp_enqueue_script( 'swiper', 'https://unpkg.com/swiper@11/swiper-bundle.min.js', array(), '11', true );
    }

    wp_enqueue_style( 'mmi-portfolio', $url . 'portfolio.css', array(), $css_version );
    wp_enqueue_script( 'mmi-portfolio', $url . 'portfolio.js', array(), $js_version, true );
}
add_action( 'wp_enqueue_scripts', 'mmi_portfolio_enqueue_assets' );

/**
 * Requête de l'archive pédagogique : pagination et filtres explicites.
 *
 * Le filtrage reposait jusqu'ici sur un effet de bord (les query vars de
 * taxonomie interprétées par WordPress), ce qui rendait le comptage des
 * résultats et la pagination incohérents.
 */
function mmi_portfolio_pre_get_posts( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    if ( 'portfolio_pedagogique' !== $query->get( 'custom_template' ) ) {
        return;
    }

    $query->set( 'posts_per_page', 12 );
    $query->set( 'orderby', 'date' );
    $query->set( 'order', 'DESC' );

    $tax_query = array();

    foreach ( array( 'niveau', 'competence' ) as $taxonomy ) {
        if ( empty( $_GET[ $taxonomy ] ) ) {
            continue;
        }

        $tax_query[] = array(
            'taxonomy' => $taxonomy,
            'field'    => 'slug',
            'terms'    => sanitize_title( wp_unslash( $_GET[ $taxonomy ] ) ),
        );
    }

    if ( $tax_query ) {
        $tax_query['relation'] = 'AND';
        $query->set( 'tax_query', $tax_query );
    }
}
add_action( 'pre_get_posts', 'mmi_portfolio_pre_get_posts' );

/**
 * Flush unique des règles de réécriture au changement de version du plugin,
 * pour éviter d'avoir à réenregistrer les permaliens à la main.
 */
function mmi_portfolio_maybe_flush_rewrites() {
    if ( get_option( 'mmi_portfolio_rewrite_version' ) === MMI_PORTFOLIO_VERSION ) {
        return;
    }

    flush_rewrite_rules();
    update_option( 'mmi_portfolio_rewrite_version', MMI_PORTFOLIO_VERSION );
}
add_action( 'init', 'mmi_portfolio_maybe_flush_rewrites', 20 );

/**
 * Affiche une icône SVG du plugin (templates/assets/<name>.svg).
 *
 * Le fichier est un asset local du plugin (contenu de confiance) : on retire
 * seulement le prologue XML et on normalise la taille + l'accessibilité.
 *
 * @param string $name Nom du fichier, sans extension.
 * @param int    $size Taille en pixels.
 */
function mmi_portfolio_icon( $name, $size = 32 ) {
    $name = sanitize_file_name( $name );
    $path = plugin_dir_path( __FILE__ ) . '../templates/assets/' . $name . '.svg';

    if ( ! file_exists( $path ) ) {
        return;
    }

    $svg = file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions -- asset local du plugin.
    if ( false === $svg ) {
        return;
    }

    // Les icônes déclarent toutes des classes génériques (.cls-1, .cls-2...) dans
    // un <style> inline : sans préfixe, la dernière icône chargée écrase les
    // règles des précédentes. On isole chaque icône.
    $svg = str_replace( 'cls-', 'cls-' . $name . '-', $svg );

    // Retire prologue XML et doctype : invalides en HTML inline.
    $svg = preg_replace( '/<\?xml.*?\?>/s', '', $svg );
    $svg = preg_replace( '/<!DOCTYPE.*?>/s', '', $svg );

    // Normalise la balise ouvrante <svg> : taille fixe + masquée aux lecteurs d'écran.
    $size = (int) $size;
    $svg  = preg_replace_callback(
        '/<svg\b[^>]*>/i',
        function ( $matches ) use ( $size ) {
            $tag = preg_replace( '/\s(height|width|aria-hidden|focusable|class)="[^"]*"/i', '', $matches[0] );

            return str_replace(
                '<svg',
                sprintf( '<svg height="%d" width="%d" class="mmi-icon" aria-hidden="true" focusable="false"', $size, $size ),
                $tag
            );
        },
        $svg,
        1
    );

    echo trim( $svg ); // phpcs:ignore WordPress.Security.EscapeOutput -- SVG local normalisé ci-dessus.
}
