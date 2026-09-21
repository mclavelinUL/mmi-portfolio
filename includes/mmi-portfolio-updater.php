<?php
/**
 * Mises à jour depuis les releases GitHub.
 *
 * Le plugin n'est pas sur le répertoire officiel de WordPress (il dépend d'une
 * extension commerciale) : il déclare donc lui-même ses mises à jour auprès de
 * WordPress, qui les propose ensuite dans Extensions comme n'importe quelle
 * autre.
 *
 * Publier une version = pousser un tag `v1.3.0` sur GitHub. Le workflow de
 * release compile le CSS et attache `mmi-portfolio.zip` à la release.
 *
 * Dépôt privé : définir MMI_PORTFOLIO_GITHUB_TOKEN dans wp-config.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

const MMI_PORTFOLIO_REPO          = 'mclavelinUL/mmi-portfolio';
const MMI_PORTFOLIO_CACHE_KEY     = 'mmi_portfolio_latest_release';
const MMI_PORTFOLIO_CACHE_TTL     = 12 * HOUR_IN_SECONDS;
const MMI_PORTFOLIO_CACHE_TTL_ERR = 1 * HOUR_IN_SECONDS;

/**
 * Identifiant du plugin tel que WordPress le connaît (mmi-portfolio/app.php).
 *
 * @return string
 */
function mmi_portfolio_basename() {
    return plugin_basename( MMI_PORTFOLIO_FILE );
}

/**
 * Jeton d'accès GitHub, s'il y en a un.
 *
 * @return string
 */
function mmi_portfolio_github_token() {
    $token = defined( 'MMI_PORTFOLIO_GITHUB_TOKEN' ) ? MMI_PORTFOLIO_GITHUB_TOKEN : '';

    /**
     * Permet de fournir le jeton autrement (variable d'environnement, coffre…).
     *
     * @param string $token
     */
    return (string) apply_filters( 'mmi_portfolio_github_token', $token );
}

/**
 * Dernière release publiée, mise en cache.
 *
 * @param bool $force Ignore le cache.
 * @return array|null { version, package, url, published, body, requires, requires_php, tested }
 */
function mmi_portfolio_get_latest_release( $force = false ) {
    if ( ! $force ) {
        $cached = get_site_transient( MMI_PORTFOLIO_CACHE_KEY );

        if ( is_array( $cached ) ) {
            return $cached;
        }

        // Un échec est mis en cache aussi : sans cela, chaque chargement de
        // l'administration relancerait un appel réseau vers GitHub.
        if ( 'error' === $cached ) {
            return null;
        }
    }

    $args = array(
        'timeout' => 10,
        'headers' => array(
            'Accept'     => 'application/vnd.github+json',
            'User-Agent' => 'mmi-portfolio/' . MMI_PORTFOLIO_VERSION,
        ),
    );

    $token = mmi_portfolio_github_token();
    if ( $token ) {
        $args['headers']['Authorization'] = 'Bearer ' . $token;
    }

    $response = wp_remote_get(
        'https://api.github.com/repos/' . MMI_PORTFOLIO_REPO . '/releases/latest',
        $args
    );

    if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
        set_site_transient( MMI_PORTFOLIO_CACHE_KEY, 'error', MMI_PORTFOLIO_CACHE_TTL_ERR );
        return null;
    }

    $release = json_decode( wp_remote_retrieve_body( $response ), true );
    $parsed  = mmi_portfolio_parse_release( $release );

    if ( ! $parsed ) {
        set_site_transient( MMI_PORTFOLIO_CACHE_KEY, 'error', MMI_PORTFOLIO_CACHE_TTL_ERR );
        return null;
    }

    set_site_transient( MMI_PORTFOLIO_CACHE_KEY, $parsed, MMI_PORTFOLIO_CACHE_TTL );

    return $parsed;
}

/**
 * Normalise la réponse de l'API GitHub.
 *
 * Isolée de l'appel réseau pour rester testable.
 *
 * @param mixed $release Réponse décodée.
 * @return array|null
 */
function mmi_portfolio_parse_release( $release ) {
    if ( ! is_array( $release ) || empty( $release['tag_name'] ) ) {
        return null;
    }

    if ( ! empty( $release['draft'] ) || ! empty( $release['prerelease'] ) ) {
        return null;
    }

    // Les tags sont de la forme v1.3.0 ; WordPress compare des 1.3.0.
    $version = ltrim( (string) $release['tag_name'], 'vV' );

    if ( ! preg_match( '/^\d+(\.\d+)*/', $version ) ) {
        return null;
    }

    // On privilégie le zip attaché à la release (construit par le workflow,
    // sans sources ni outillage). Le zip source de GitHub sert de repli.
    $package = '';

    if ( ! empty( $release['assets'] ) && is_array( $release['assets'] ) ) {
        foreach ( $release['assets'] as $asset ) {
            if ( empty( $asset['name'] ) || empty( $asset['browser_download_url'] ) ) {
                continue;
            }

            if ( 'mmi-portfolio.zip' === $asset['name'] ) {
                $package = $asset['browser_download_url'];
                break;
            }

            if ( ! $package && '.zip' === substr( $asset['name'], -4 ) ) {
                $package = $asset['browser_download_url'];
            }
        }
    }

    if ( ! $package && ! empty( $release['zipball_url'] ) ) {
        $package = $release['zipball_url'];
    }

    if ( ! $package ) {
        return null;
    }

    $body = isset( $release['body'] ) ? (string) $release['body'] : '';

    return array(
        'version'      => $version,
        'package'      => $package,
        'url'          => ! empty( $release['html_url'] ) ? $release['html_url'] : 'https://github.com/' . MMI_PORTFOLIO_REPO,
        'published'    => ! empty( $release['published_at'] ) ? $release['published_at'] : '',
        'body'         => $body,
        // Métadonnées facultatives, lues dans le corps de la release :
        //   Requires at least: 6.0
        'requires'     => mmi_portfolio_release_meta( $body, 'Requires at least' ),
        'requires_php' => mmi_portfolio_release_meta( $body, 'Requires PHP' ),
        'tested'       => mmi_portfolio_release_meta( $body, 'Tested up to' ),
    );
}

/**
 * Extrait une métadonnée « Clé: valeur » du corps d'une release.
 *
 * @param string $body Corps de la release.
 * @param string $key  Clé recherchée.
 * @return string
 */
function mmi_portfolio_release_meta( $body, $key ) {
    if ( ! $body ) {
        return '';
    }

    $pattern = '/^\s*' . preg_quote( $key, '/' ) . '\s*:\s*(.+)$/mi';

    return preg_match( $pattern, $body, $matches ) ? trim( $matches[1] ) : '';
}

/**
 * Déclare la mise à jour auprès de WordPress.
 *
 * @param mixed $transient Transient update_plugins.
 * @return mixed
 */
function mmi_portfolio_check_for_update( $transient ) {
    if ( ! is_object( $transient ) ) {
        return $transient;
    }

    $release  = mmi_portfolio_get_latest_release();
    $basename = mmi_portfolio_basename();

    if ( ! $release ) {
        return $transient;
    }

    $item = (object) array(
        'id'            => 'github.com/' . MMI_PORTFOLIO_REPO,
        'slug'          => 'mmi-portfolio',
        'plugin'        => $basename,
        'new_version'   => $release['version'],
        'url'           => $release['url'],
        'package'       => $release['package'],
        'icons'         => array(),
        'banners'       => array(),
        'banners_rtl'   => array(),
        'tested'        => $release['tested'],
        'requires'      => $release['requires'],
        'requires_php'  => $release['requires_php'],
        'compatibility' => new stdClass(),
    );

    if ( version_compare( $release['version'], MMI_PORTFOLIO_VERSION, '>' ) ) {
        $transient->response[ $basename ] = $item;
        unset( $transient->no_update[ $basename ] );
    } else {
        // Renseigner no_update évite que l'écran des extensions affiche le
        // plugin comme « sans information de mise à jour ».
        $transient->no_update[ $basename ] = $item;
        unset( $transient->response[ $basename ] );
    }

    return $transient;
}
add_filter( 'pre_set_site_transient_update_plugins', 'mmi_portfolio_check_for_update' );

/**
 * Fiche « Voir les détails » du plugin.
 *
 * @param mixed  $result
 * @param string $action
 * @param object $args
 * @return mixed
 */
function mmi_portfolio_plugin_information( $result, $action, $args ) {
    if ( 'plugin_information' !== $action || empty( $args->slug ) || 'mmi-portfolio' !== $args->slug ) {
        return $result;
    }

    $release = mmi_portfolio_get_latest_release();

    if ( ! $release ) {
        return $result;
    }

    if ( ! function_exists( 'get_plugin_data' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    $data = get_plugin_data( MMI_PORTFOLIO_FILE, false, false );

    return (object) array(
        'name'          => $data['Name'],
        'slug'          => 'mmi-portfolio',
        'version'       => $release['version'],
        'author'        => $data['Author'],
        'homepage'      => 'https://github.com/' . MMI_PORTFOLIO_REPO,
        'download_link' => $release['package'],
        'requires'      => $release['requires'],
        'requires_php'  => $release['requires_php'],
        'tested'        => $release['tested'],
        'last_updated'  => $release['published'],
        'sections'      => array(
            'description' => wpautop( esc_html( $data['Description'] ) ),
            'changelog'   => $release['body'] ? wpautop( esc_html( $release['body'] ) ) : '<p>Voir les releases sur GitHub.</p>',
        ),
    );
}
add_filter( 'plugins_api', 'mmi_portfolio_plugin_information', 10, 3 );

/**
 * Renomme le dossier extrait.
 *
 * Une archive GitHub se décompresse en « mmi-portfolio-1.3.0 » ou
 * « mclavelinUL-mmi-portfolio-a1b2c3d » ; sans ce renommage, WordPress
 * installerait une seconde extension à côté de l'existante.
 *
 * @param string $source
 * @param string $remote_source
 * @param object $upgrader
 * @param array  $args
 * @return string|WP_Error
 */
function mmi_portfolio_fix_source_dir( $source, $remote_source, $upgrader, $args = array() ) {
    global $wp_filesystem;

    if ( empty( $args['plugin'] ) || mmi_portfolio_basename() !== $args['plugin'] ) {
        return $source;
    }

    if ( ! $wp_filesystem ) {
        return $source;
    }

    $desired = trailingslashit( $remote_source ) . 'mmi-portfolio';

    if ( untrailingslashit( $source ) === $desired ) {
        return $source;
    }

    if ( $wp_filesystem->exists( $desired ) ) {
        $wp_filesystem->delete( $desired, true );
    }

    if ( ! $wp_filesystem->move( untrailingslashit( $source ), $desired ) ) {
        return new WP_Error(
            'mmi_portfolio_rename_failed',
            'Impossible de renommer le dossier du plugin après extraction.'
        );
    }

    return trailingslashit( $desired );
}
add_filter( 'upgrader_source_selection', 'mmi_portfolio_fix_source_dir', 10, 4 );

/**
 * Vide le cache après une mise à jour, pour que l'écran des extensions
 * n'affiche pas une mise à jour déjà appliquée.
 *
 * @param object $upgrader
 * @param array  $options
 */
function mmi_portfolio_clear_update_cache( $upgrader, $options ) {
    if ( 'update' !== ( $options['action'] ?? '' ) || 'plugin' !== ( $options['type'] ?? '' ) ) {
        return;
    }

    $plugins = $options['plugins'] ?? array();

    if ( in_array( mmi_portfolio_basename(), (array) $plugins, true ) ) {
        delete_site_transient( MMI_PORTFOLIO_CACHE_KEY );
    }
}
add_action( 'upgrader_process_complete', 'mmi_portfolio_clear_update_cache', 10, 2 );

/**
 * « Vérifier les mises à jour » depuis la ligne du plugin.
 *
 * @param string[] $links
 * @return string[]
 */
function mmi_portfolio_check_update_link( $links ) {
    $url = wp_nonce_url(
        add_query_arg( 'mmi_portfolio_check_update', '1', admin_url( 'plugins.php' ) ),
        'mmi_portfolio_check_update'
    );

    $links[] = '<a href="' . esc_url( $url ) . '">Vérifier les mises à jour</a>';

    return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( MMI_PORTFOLIO_FILE ), 'mmi_portfolio_check_update_link' );

/**
 * Traite la demande de vérification manuelle.
 */
function mmi_portfolio_handle_check_update() {
    if ( empty( $_GET['mmi_portfolio_check_update'] ) ) {
        return;
    }

    if ( ! current_user_can( 'update_plugins' ) ) {
        return;
    }

    check_admin_referer( 'mmi_portfolio_check_update' );

    delete_site_transient( MMI_PORTFOLIO_CACHE_KEY );
    delete_site_transient( 'update_plugins' );
    wp_update_plugins();

    wp_safe_redirect( admin_url( 'plugins.php' ) );
    exit;
}
add_action( 'load-plugins.php', 'mmi_portfolio_handle_check_update' );
