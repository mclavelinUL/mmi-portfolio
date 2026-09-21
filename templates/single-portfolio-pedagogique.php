<?php
/**
 * Fiche détail d'une réalisation — vue pédagogique.
 */

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

$mmi_slug = mmi_portfolio_get_item_slug();
$mmi_post = $mmi_slug ? get_page_by_path( $mmi_slug, OBJECT, 'realisation-mmi' ) : null;

$archive_url = site_url( '/portfolio-pedagogique/realisation-mmi/' );

// Réalisation introuvable ou non publiée : vrai 404.
if ( ! $mmi_post instanceof WP_Post || 'publish' !== $mmi_post->post_status ) {
    global $wp_query;
    $wp_query->set_404();
    status_header( 404 );
    nocache_headers();

    get_header();
    ?>
    <main class="mmi-portfolio mmi-single">
        <div class="mmi-container">
            <div class="mmi-empty" style="margin-top: var(--mmi-space-8);">
                <p class="mmi-empty__title">Réalisation introuvable</p>
                <p class="mmi-empty__text">Cette réalisation n'existe pas ou n'est plus publiée.</p>
                <a class="mmi-button mmi-button--primary" href="<?php echo esc_url( $archive_url ); ?>">Retour au portfolio pédagogique</a>
            </div>
        </div>
    </main>
    <?php
    get_footer();
    return;
}

$post_id   = $mmi_post->ID;
$pedagogie = get_field( 'pedagogie', $post_id );
$pedagogie = is_array( $pedagogie ) ? $pedagogie : array();
$image_id  = get_post_thumbnail_id( $post_id );

$acs = ( ! empty( $pedagogie['apprentissages_critiques_engages'] ) && is_array( $pedagogie['apprentissages_critiques_engages'] ) )
    ? $pedagogie['apprentissages_critiques_engages']
    : array();

// Ne garde que les lignes dont la relation est réellement renseignée, une fois
// pour toutes : le sommaire et le corps parcourent ensuite la même liste.
$rows = array();
foreach ( $acs as $ac ) {
    $ac_post = ( ! empty( $ac['apprentissage_critique'][0] ) && $ac['apprentissage_critique'][0] instanceof WP_Post )
        ? $ac['apprentissage_critique'][0]
        : null;

    if ( ! $ac_post ) {
        continue;
    }

    $rows[] = array(
        'post'       => $ac_post,
        'anchor'     => 'ac-' . $ac_post->ID,
        'competence' => mmi_portfolio_first_term_name( $ac_post->ID, 'competence' ),
        'niveau'     => isset( $ac['niveau_estime'] ) ? max( 0, min( 5, (int) $ac['niveau_estime'] ) ) : 0,
        'lien'       => ! empty( $ac['traces_hypertexte'] ) ? $ac['traces_hypertexte'] : '',
        'images'     => ( ! empty( $ac['traces_images'] ) && is_array( $ac['traces_images'] ) ) ? $ac['traces_images'] : array(),
        'analyse'    => ! empty( $ac['analyse'] ) ? $ac['analyse'] : '',
    );
}

$competences = mmi_portfolio_term_names( $post_id, 'competence' );
$niveaux     = mmi_portfolio_term_names( $post_id, 'niveau' );
$periode     = mmi_portfolio_format_period( get_field( 'date_de_debut', $post_id ), get_field( 'date_de_fin', $post_id ) );
$temps       = ! empty( $pedagogie['temps_de_travail_estime'] ) ? $pedagogie['temps_de_travail_estime'] : '';
$cadre       = ! empty( $pedagogie['cadre'] ) ? $pedagogie['cadre'] : '';
$modalites   = ! empty( $pedagogie['modalite'] ) ? implode( ' et ', (array) $pedagogie['modalite'] ) : '';

get_header();
?>
<main class="mmi-portfolio mmi-single">
    <div class="mmi-container">

        <nav class="mmi-single__breadcrumb" aria-label="Fil d'Ariane">
            <a href="<?php echo esc_url( $archive_url ); ?>">
                <span aria-hidden="true">&larr;</span> Portfolio pédagogique
            </a>
        </nav>

        <?php if ( $image_id ) : ?>
            <header class="mmi-single__hero">
                <?php
                echo wp_get_attachment_image(
                    $image_id,
                    'full',
                    false,
                    array(
                        'class'         => 'mmi-single__hero-image',
                        'alt'           => '',
                        'fetchpriority' => 'high',
                    )
                );
                ?>
                <div class="mmi-single__hero-scrim" aria-hidden="true"></div>
                <div class="mmi-single__hero-inner">
                    <h1 class="mmi-single__title"><?php echo esc_html( get_the_title( $post_id ) ); ?></h1>
                </div>
            </header>
        <?php else : ?>
            <header class="mmi-single__hero mmi-single__hero--plain">
                <div class="mmi-single__hero-inner" style="padding-inline: 0;">
                    <h1 class="mmi-single__title"><?php echo esc_html( get_the_title( $post_id ) ); ?></h1>
                </div>
            </header>
        <?php endif; ?>

        <div class="mmi-single__meta">
            <ul class="mmi-meta">
                <?php if ( $niveaux ) : ?>
                    <li>
                        <?php mmi_portfolio_icon( 'niveau', 18 ); ?>
                        <span><span class="mmi-sr-only">Niveau : </span>Niveau <?php echo esc_html( implode( ', ', $niveaux ) ); ?></span>
                    </li>
                <?php endif; ?>

                <?php if ( $competences ) : ?>
                    <li>
                        <?php mmi_portfolio_icon( 'competences', 18 ); ?>
                        <span><span class="mmi-sr-only">Compétences : </span><?php echo esc_html( implode( ', ', $competences ) ); ?></span>
                    </li>
                <?php endif; ?>

                <?php if ( $periode || $temps ) : ?>
                    <li>
                        <?php mmi_portfolio_icon( 'duree', 18 ); ?>
                        <span>
                            <?php
                            $parts = array();
                            if ( $temps ) {
                                $parts[] = $temps . ' h de travail';
                            }
                            if ( $periode ) {
                                $parts[] = $periode;
                            }
                            echo esc_html( implode( ' · ', $parts ) );
                            ?>
                        </span>
                    </li>
                <?php endif; ?>

                <?php if ( $cadre || $modalites ) : ?>
                    <li>
                        <?php mmi_portfolio_icon( 'modalite', 18 ); ?>
                        <span>
                            <?php
                            $parts = array_filter( array( $cadre, $modalites ) );
                            echo esc_html( implode( ' · ', $parts ) );
                            ?>
                        </span>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <?php if ( trim( $mmi_post->post_content ) ) : ?>
            <div class="mmi-single__intro mmi-prose">
                <?php echo wp_kses_post( apply_filters( 'the_content', $mmi_post->post_content ) ); ?>
            </div>
        <?php endif; ?>

        <?php if ( $rows ) : ?>
            <div class="mmi-single__layout">

                <?php // <details> ouvert : repli sur mobile, colonne collante sur desktop. ?>
                <details class="mmi-toc mmi-single__toc" open>
                    <summary class="mmi-toc__summary">
                        Apprentissages critiques
                        <span class="mmi-badge mmi-badge--outline"><?php echo esc_html( count( $rows ) ); ?></span>
                    </summary>
                    <ul class="mmi-toc__list">
                        <?php foreach ( $rows as $row ) : ?>
                            <li>
                                <a class="mmi-toc__link" href="#<?php echo esc_attr( $row['anchor'] ); ?>">
                                    <span class="mmi-toc__label"><?php echo esc_html( $row['post']->post_title ); ?></span>
                                    <?php if ( $row['competence'] ) : ?>
                                        <span class="mmi-toc__competence"><?php echo esc_html( $row['competence'] ); ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </details>

                <section class="mmi-single__traces" aria-labelledby="mmi-traces-title">
                    <h2 class="mmi-single__section-title" id="mmi-traces-title">Traces</h2>

                    <?php foreach ( $rows as $row ) : ?>
                        <article class="mmi-ac" id="<?php echo esc_attr( $row['anchor'] ); ?>">
                            <header class="mmi-ac__header">
                                <div class="mmi-ac__heading">
                                    <h3 class="mmi-ac__title"><?php echo esc_html( $row['post']->post_title ); ?></h3>
                                    <?php if ( trim( wp_strip_all_tags( $row['post']->post_content ) ) ) : ?>
                                        <p class="mmi-ac__description"><?php echo esc_html( wp_strip_all_tags( $row['post']->post_content ) ); ?></p>
                                    <?php endif; ?>
                                </div>

                                <div class="mmi-ac__aside">
                                    <?php if ( $row['competence'] ) : ?>
                                        <span class="mmi-badge"><?php echo esc_html( $row['competence'] ); ?></span>
                                    <?php endif; ?>
                                    <?php mmi_portfolio_gauge( $row['niveau'] ); ?>
                                </div>
                            </header>

                            <?php if ( $row['lien'] ) : ?>
                                <a class="mmi-ac__link" href="<?php echo esc_url( $row['lien'] ); ?>" target="_blank" rel="noopener noreferrer">
                                    <?php mmi_portfolio_icon( 'lien-externe', 16 ); ?>
                                    Voir la trace en ligne
                                    <span class="mmi-sr-only">(nouvel onglet)</span>
                                </a>
                            <?php endif; ?>

                            <?php if ( $row['images'] ) : ?>
                                <div class="mmi-gallery swiper" data-slides="<?php echo esc_attr( count( $row['images'] ) ); ?>">
                                    <div class="swiper-wrapper">
                                        <?php
                                        foreach ( $row['images'] as $image ) :
                                            if ( empty( $image['ID'] ) ) {
                                                continue;
                                            }
                                            ?>
                                            <div class="swiper-slide">
                                                <figure class="mmi-gallery__figure">
                                                    <?php
                                                    echo wp_get_attachment_image(
                                                        (int) $image['ID'],
                                                        'large',
                                                        false,
                                                        array(
                                                            'class'   => 'mmi-gallery__image',
                                                            'loading' => 'lazy',
                                                            'alt'     => isset( $image['alt'] ) ? $image['alt'] : '',
                                                        )
                                                    );
                                                    ?>
                                                </figure>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="swiper-pagination"></div>
                                    <div class="swiper-button-prev"></div>
                                    <div class="swiper-button-next"></div>
                                </div>
                            <?php endif; ?>

                            <?php if ( $row['analyse'] ) : ?>
                                <div class="mmi-ac__analyse">
                                    <h4 class="mmi-ac__analyse-title">Analyse</h4>
                                    <div class="mmi-prose">
                                        <?php echo wp_kses_post( apply_filters( 'the_content', $row['analyse'] ) ); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </section>
            </div>
        <?php else : ?>
            <div class="mmi-empty">
                <p class="mmi-empty__title">Aucune trace</p>
                <p class="mmi-empty__text">Aucun apprentissage critique n'est encore rattaché à cette réalisation.</p>
            </div>
        <?php endif; ?>

    </div>
</main>
<?php get_footer(); ?>
