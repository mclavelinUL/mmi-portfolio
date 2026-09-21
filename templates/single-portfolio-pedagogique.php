<?php
/**
 * Fiche détail d'une réalisation — vue pédagogique.
 */

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

$mmi_slug = mmi_portfolio_get_item_slug();
$mmi_post = $mmi_slug ? get_page_by_path( $mmi_slug, OBJECT, 'realisation-mmi' ) : null;

// Réalisation introuvable ou non publiée : vrai 404, pas de redirection impossible.
if ( ! $mmi_post instanceof WP_Post || 'publish' !== $mmi_post->post_status ) {
    global $wp_query;
    $wp_query->set_404();
    status_header( 404 );
    nocache_headers();

    get_header();
    ?>
    <main class="portfolio-pedagogique-single is-404">
        <h1>Réalisation introuvable</h1>
        <p>Cette réalisation n'existe pas ou n'est plus publiée.</p>
        <p><a href="<?php echo esc_url( site_url( '/portfolio-pedagogique/realisation-mmi/' ) ); ?>">Retour au portfolio pédagogique</a></p>
    </main>
    <?php
    get_footer();
    return;
}

$post_id   = $mmi_post->ID;
$pedagogie = get_field( 'pedagogie', $post_id );
$pedagogie = is_array( $pedagogie ) ? $pedagogie : array();
$thumbnail = get_the_post_thumbnail_url( $post_id, 'full' );

$acs = ( ! empty( $pedagogie['apprentissages_critiques_engages'] ) && is_array( $pedagogie['apprentissages_critiques_engages'] ) )
    ? $pedagogie['apprentissages_critiques_engages']
    : array();

/**
 * Retourne le WP_Post de l'apprentissage critique d'une ligne du répéteur,
 * ou null si la relation n'est pas renseignée.
 */
$mmi_get_ac_post = static function ( $row ) {
    if ( empty( $row['apprentissage_critique'][0] ) ) {
        return null;
    }

    $ac_post = $row['apprentissage_critique'][0];

    return $ac_post instanceof WP_Post ? $ac_post : null;
};

get_header();
?>
<main class="portfolio-pedagogique-single">
    <?php if ( $thumbnail ) : ?>
        <div class="hero" style="background-image:url('<?php echo esc_url( $thumbnail ); ?>')">
            <h1><?php echo esc_html( get_the_title( $post_id ) ); ?></h1>
        </div>
    <?php else : ?>
        <h1><?php echo esc_html( get_the_title( $post_id ) ); ?></h1>
    <?php endif; ?>

    <section class="header">
        <div class="meta">
            <p class="niveau">
                <?php mmi_portfolio_icon( 'niveau' ); ?>
                <?php
                $niveaux = get_the_terms( $post_id, 'niveau' );
                if ( $niveaux && ! is_wp_error( $niveaux ) ) {
                    $niveau = array_shift( $niveaux );
                    echo esc_html( 'Niveau ' . $niveau->name );
                }
                ?>
            </p>

            <p class="competence">
                <?php mmi_portfolio_icon( 'competences' ); ?>
                <?php
                $competences = get_the_terms( $post_id, 'competence' );
                if ( $competences && ! is_wp_error( $competences ) ) {
                    echo esc_html( implode( ', ', wp_list_pluck( $competences, 'name' ) ) );
                }
                ?>
            </p>

            <p class="date">
                <?php mmi_portfolio_icon( 'duree' ); ?>
                <?php
                $temps      = ! empty( $pedagogie['temps_de_travail_estime'] ) ? $pedagogie['temps_de_travail_estime'] : 'ND';
                $date_debut = get_field( 'date_de_debut', $post_id );
                $date_fin   = get_field( 'date_de_fin', $post_id );

                printf(
                    '%sh du %s au %s',
                    esc_html( $temps ),
                    esc_html( $date_debut ? $date_debut : 'ND' ),
                    esc_html( $date_fin ? $date_fin : 'ND' )
                );
                ?>
            </p>

            <p class="modalite">
                <?php mmi_portfolio_icon( 'modalite' ); ?>
                <?php
                $cadre     = ! empty( $pedagogie['cadre'] ) ? $pedagogie['cadre'] : 'ND';
                $modalites = ! empty( $pedagogie['modalite'] ) ? (array) $pedagogie['modalite'] : array();
                $modalite  = $modalites ? implode( ' / ', $modalites ) : 'ND';

                printf( 'Modalité : %s / %s', esc_html( $cadre ), esc_html( $modalite ) );
                ?>
            </p>
        </div>
    </section>

    <section class="main">
        <div class="content">
            <?php echo wp_kses_post( apply_filters( 'the_content', $mmi_post->post_content ) ); ?>
        </div>
    </section>

    <section class="traces">
        <h2>Traces</h2>

        <?php if ( $acs ) : ?>
            <div class="submenu">
                <div class="bt"><span></span><span></span><span></span></div>
                <h2>ACs de la réalisation</h2>
                <ul>
                    <?php
                    foreach ( $acs as $ac ) :
                        $ac_post = $mmi_get_ac_post( $ac );
                        if ( ! $ac_post ) {
                            continue;
                        }
                        $ac_competences = get_the_terms( $ac_post->ID, 'competence' );
                        ?>
                        <li>
                            <a href="#ac<?php echo esc_attr( $ac_post->ID ); ?>">
                                <?php echo esc_html( $ac_post->post_title ); ?>
                                <?php if ( $ac_competences && ! is_wp_error( $ac_competences ) ) : ?>
                                    -&nbsp;<span><?php echo esc_html( $ac_competences[0]->name ); ?></span>
                                <?php endif; ?>
                                -&nbsp;<?php echo esc_html( wp_strip_all_tags( $ac_post->post_content ) ); ?>
                            </a>
                        </li>
                        <?php
                    endforeach;
                    ?>
                </ul>
            </div>

            <?php
            foreach ( $acs as $ac ) :
                $ac_post = $mmi_get_ac_post( $ac );
                if ( ! $ac_post ) {
                    continue;
                }

                $niveau_estime = isset( $ac['niveau_estime'] ) ? (int) $ac['niveau_estime'] : 0;
                $images        = ( ! empty( $ac['traces_images'] ) && is_array( $ac['traces_images'] ) ) ? $ac['traces_images'] : array();
                ?>
                <article>
                    <header>
                        <h3 id="ac<?php echo esc_attr( $ac_post->ID ); ?>">
                            <?php echo esc_html( $ac_post->post_title ); ?>
                            <span><?php echo esc_html( wp_strip_all_tags( $ac_post->post_content ) ); ?></span>
                        </h3>
                        <span class="niveau_estime" role="img" aria-label="<?php echo esc_attr( sprintf( 'Niveau estimé : %d sur 5', $niveau_estime ) ); ?>">
                            <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                <span class="fa fa-star<?php echo $i <= $niveau_estime ? ' checked' : ''; ?>" aria-hidden="true"></span>
                            <?php endfor; ?>
                        </span>
                    </header>

                    <div class="content">
                        <?php if ( ! empty( $ac['traces_hypertexte'] ) ) : ?>
                            <a href="<?php echo esc_url( $ac['traces_hypertexte'] ); ?>" target="_blank" rel="noopener noreferrer" class="external_link">
                                <?php mmi_portfolio_icon( 'lien-externe', 18 ); ?>Lien externe
                            </a>
                        <?php endif; ?>

                        <?php if ( $images ) : ?>
                            <div class="swiper" data-slides="<?php echo esc_attr( count( $images ) ); ?>">
                                <div class="swiper-wrapper">
                                    <?php
                                    foreach ( $images as $image ) :
                                        if ( empty( $image['sizes']['large'] ) && empty( $image['url'] ) ) {
                                            continue;
                                        }
                                        $src = ! empty( $image['sizes']['large'] ) ? $image['sizes']['large'] : $image['url'];
                                        ?>
                                        <div class="swiper-slide">
                                            <img src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_attr( isset( $image['alt'] ) ? $image['alt'] : '' ); ?>" loading="lazy">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="swiper-pagination"></div>
                                <div class="swiper-button-prev"></div>
                                <div class="swiper-button-next"></div>
                                <div class="swiper-scrollbar"></div>
                            </div>
                        <?php endif; ?>

                        <?php if ( ! empty( $ac['analyse'] ) ) : ?>
                            <div class="analyse">
                                <h4>Analyse</h4>
                                <?php echo wp_kses_post( apply_filters( 'the_content', $ac['analyse'] ) ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
                <?php
            endforeach;
            ?>
        <?php else : ?>
            <p>Aucun apprentissage critique n'est encore rattaché à cette réalisation.</p>
        <?php endif; ?>
    </section>
</main>
<?php get_footer(); ?>
