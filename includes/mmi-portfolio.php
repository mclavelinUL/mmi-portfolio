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
 * Assets du portfolio.
 */
function mmi_portfolio_enqueue_assets() {
    $base = plugin_dir_path( __FILE__ ) . '../templates/';
    $url  = plugin_dir_url( __FILE__ ) . '../templates/';

    $css_version = file_exists( $base . 'portfolio.css' ) ? filemtime( $base . 'portfolio.css' ) : MMI_PORTFOLIO_VERSION;
    $js_version  = file_exists( $base . 'portfolio.js' ) ? filemtime( $base . 'portfolio.js' ) : MMI_PORTFOLIO_VERSION;

    wp_enqueue_style( 'swiper', 'https://unpkg.com/swiper@11/swiper-bundle.min.css', array(), '11' );
    wp_enqueue_script( 'swiper', 'https://unpkg.com/swiper@11/swiper-bundle.min.js', array(), '11', true );
    wp_enqueue_style( 'fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', array(), '4.7.0' );
    wp_enqueue_style( 'mmi-portfolio', $url . 'portfolio.css', array(), $css_version );
    wp_enqueue_script( 'mmi-portfolio', $url . 'portfolio.js', array( 'swiper' ), $js_version, true );
}
add_action( 'wp_enqueue_scripts', 'mmi_portfolio_enqueue_assets' );

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
