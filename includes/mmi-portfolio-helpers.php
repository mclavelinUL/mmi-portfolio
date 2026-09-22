<?php
/**
 * Helpers de présentation partagés par les templates.
 */

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

/**
 * Termes d'une taxonomie, triés par nom, sans erreur à gérer côté template.
 *
 * @param string $taxonomy Taxonomie.
 * @return WP_Term[]
 */
function mmi_portfolio_get_terms( $taxonomy ) {
    $terms = get_terms(
        array(
            'taxonomy'   => $taxonomy,
            'hide_empty' => false,
            'orderby'    => 'name',
            'order'      => 'ASC',
        )
    );

    return is_wp_error( $terms ) ? array() : $terms;
}

/**
 * Noms des termes d'un article pour une taxonomie.
 *
 * @param int    $post_id  Article.
 * @param string $taxonomy Taxonomie.
 * @return string[]
 */
function mmi_portfolio_term_names( $post_id, $taxonomy ) {
    $terms = get_the_terms( $post_id, $taxonomy );

    if ( ! $terms || is_wp_error( $terms ) ) {
        return array();
    }

    return wp_list_pluck( $terms, 'name' );
}

/**
 * Premier nom de terme, ou chaîne vide.
 *
 * @param int    $post_id  Article.
 * @param string $taxonomy Taxonomie.
 * @return string
 */
function mmi_portfolio_first_term_name( $post_id, $taxonomy ) {
    $names = mmi_portfolio_term_names( $post_id, $taxonomy );

    return $names ? reset( $names ) : '';
}

/**
 * Formate une période à partir de deux dates ACF (format d/m/Y).
 *
 * Gère les cas partiels : l'ancienne version affichait « du ND au ND ».
 *
 * @param string $start Date de début.
 * @param string $end   Date de fin.
 * @return string
 */
function mmi_portfolio_format_period( $start, $end ) {
    $start = trim( (string) $start );
    $end   = trim( (string) $end );

    if ( $start && $end ) {
        return $start === $end
            ? sprintf( 'Le %s', $start )
            : sprintf( 'Du %s au %s', $start, $end );
    }

    if ( $start ) {
        return sprintf( 'À partir du %s', $start );
    }

    if ( $end ) {
        return sprintf( "Jusqu'au %s", $end );
    }

    return '';
}

/**
 * Jauge de niveau estimé, en remplacement des cinq étoiles Font Awesome.
 *
 * @param int $value Niveau de 0 à MMI_PORTFOLIO_NIVEAU_MAX. 0 n'affiche rien.
 * @param int $max   Nombre de segments.
 */
function mmi_portfolio_gauge( $value, $max = MMI_PORTFOLIO_NIVEAU_MAX ) {
    $value = max( 0, min( (int) $max, (int) $value ) );

    if ( ! $value ) {
        return;
    }

    $label = sprintf( 'Niveau estimé : %d sur %d', $value, $max );
    ?>
    <span class="mmi-gauge">
        <span class="mmi-gauge__track" role="img" aria-label="<?php echo esc_attr( $label ); ?>">
            <?php for ( $i = 1; $i <= $max; $i++ ) : ?>
                <span class="mmi-gauge__segment<?php echo $i <= $value ? ' mmi-gauge__segment--on' : ''; ?>"></span>
            <?php endfor; ?>
        </span>
        <span aria-hidden="true"><?php echo esc_html( $value . '/' . $max ); ?></span>
    </span>
    <?php
}
