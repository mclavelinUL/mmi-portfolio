<?php
/**
 * Dépendances du plugin.
 *
 * ACF Pro n'est plus embarqué dans vendor/ : c'est une extension commerciale,
 * la redistribuer dans les releases d'un dépôt public poserait un problème de
 * licence, et une copie figée ne reçoit jamais ses correctifs de sécurité.
 * Le plugin la déclare donc comme dépendance et le signale clairement quand
 * elle manque.
 */

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

/**
 * ACF Pro est-il disponible ?
 *
 * On teste les fonctions réellement utilisées plutôt que la présence d'un
 * fichier : ACF peut être fourni comme extension, embarqué par un thème ou
 * chargé par Composer.
 *
 * @return bool
 */
function mmi_portfolio_has_acf() {
    return function_exists( 'acf_add_local_field_group' ) && function_exists( 'get_field' );
}

/**
 * Notice d'administration quand la dépendance manque.
 */
function mmi_portfolio_missing_acf_notice() {
    if ( mmi_portfolio_has_acf() || ! current_user_can( 'activate_plugins' ) ) {
        return;
    }

    echo '<div class="notice notice-error"><p>';
    echo '<strong>MMI Portfolio</strong> — ';
    echo 'ce plugin nécessite <strong>Advanced Custom Fields Pro</strong>, qui n\'est pas actif. ';
    echo 'Les champs du portfolio et les fiches de réalisation ne s\'afficheront pas tant qu\'il ne sera pas installé et activé.';
    echo '</p></div>';
}
add_action( 'admin_notices', 'mmi_portfolio_missing_acf_notice' );

/**
 * Signale la dépendance sur la ligne du plugin, dans la liste des extensions.
 *
 * @param string[] $links Liens d'action.
 * @return string[]
 */
function mmi_portfolio_plugin_row_meta( $links, $file ) {
    if ( plugin_basename( MMI_PORTFOLIO_FILE ) !== $file ) {
        return $links;
    }

    $links[] = mmi_portfolio_has_acf()
        ? 'Nécessite ACF Pro — <span style="color:#008a20;">actif</span>'
        : 'Nécessite ACF Pro — <strong style="color:#b32d2e;">manquant</strong>';

    return $links;
}
add_filter( 'plugin_row_meta', 'mmi_portfolio_plugin_row_meta', 10, 2 );
