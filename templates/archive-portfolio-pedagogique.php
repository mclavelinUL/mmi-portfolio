<?php
/**
 * Archive du portfolio pédagogique.
 */

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

get_header();

$selected = array(
    'niveau'     => isset( $_GET['niveau'] ) ? sanitize_title( wp_unslash( $_GET['niveau'] ) ) : '',
    'competence' => isset( $_GET['competence'] ) ? sanitize_title( wp_unslash( $_GET['competence'] ) ) : '',
);
$has_filters = (bool) array_filter( $selected );

$niveaux     = mmi_portfolio_get_terms( 'niveau' );
$competences = mmi_portfolio_get_terms( 'competence' );

$total     = (int) $GLOBALS['wp_query']->found_posts;
$archive_url = site_url( '/portfolio-pedagogique/realisation-mmi/' );
?>
<main class="mmi-portfolio mmi-archive">
    <div class="mmi-container">

        <header class="mmi-archive__header">
            <p class="mmi-eyebrow">BUT Métiers du multimédia et de l'internet</p>
            <h1 class="mmi-archive__title">Portfolio pédagogique</h1>
            <p class="mmi-lede">Les réalisations et les traces d'apprentissage, classées par compétence et par niveau.</p>
        </header>

        <form class="mmi-archive__filters" action="<?php echo esc_url( $archive_url ); ?>" method="get" id="mmi-filtres">
            <fieldset class="mmi-archive__field">
                <label class="mmi-archive__label" for="mmi-niveau">Niveau</label>
                <select class="mmi-archive__select" name="niveau" id="mmi-niveau">
                    <option value="">Tous les niveaux</option>
                    <?php foreach ( $niveaux as $term ) : ?>
                        <option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $selected['niveau'], $term->slug ); ?>>
                            <?php echo esc_html( $term->name ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </fieldset>

            <fieldset class="mmi-archive__field">
                <label class="mmi-archive__label" for="mmi-competence">Compétence</label>
                <select class="mmi-archive__select" name="competence" id="mmi-competence">
                    <option value="">Toutes les compétences</option>
                    <?php foreach ( $competences as $term ) : ?>
                        <option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $selected['competence'], $term->slug ); ?>>
                            <?php echo esc_html( $term->name ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </fieldset>

            <?php // Sans JS le bouton est le seul moyen de filtrer ; avec JS il est masqué. ?>
            <button type="submit" class="mmi-button mmi-button--primary" data-mmi-submit>Filtrer</button>
        </form>

        <div class="mmi-archive__results">
            <p class="mmi-archive__count" role="status">
                <strong><?php echo esc_html( number_format_i18n( $total ) ); ?></strong>
                <?php echo esc_html( _n( 'réalisation', 'réalisations', $total, 'mmi-portfolio' ) ); ?>
                <?php echo $has_filters ? 'pour ces filtres' : 'au total'; ?>
            </p>

            <?php if ( $has_filters ) : ?>
                <a class="mmi-button mmi-button--quiet" href="<?php echo esc_url( $archive_url ); ?>">Réinitialiser les filtres</a>
            <?php endif; ?>
        </div>

        <?php if ( have_posts() ) : ?>
            <ul class="mmi-archive__grid">
                <?php
                while ( have_posts() ) :
                    the_post();

                    $post_id     = get_the_ID();
                    $image_id    = get_post_thumbnail_id( $post_id );
                    $date_debut  = get_field( 'date_de_debut', $post_id );
                    $date_fin    = get_field( 'date_de_fin', $post_id );
                    $competences_post = mmi_portfolio_term_names( $post_id, 'competence' );
                    $niveaux_post     = mmi_portfolio_term_names( $post_id, 'niveau' );
                    ?>
                    <li>
                        <article class="mmi-card">
                            <div class="mmi-card__media">
                                <?php if ( $image_id ) : ?>
                                    <?php
                                    echo wp_get_attachment_image(
                                        $image_id,
                                        'large',
                                        false,
                                        array(
                                            'class'   => 'mmi-card__image',
                                            'loading' => 'lazy',
                                            'alt'     => '',
                                        )
                                    );
                                    ?>
                                <?php else : ?>
                                    <div class="mmi-card__placeholder" aria-hidden="true"></div>
                                <?php endif; ?>
                            </div>

                            <div class="mmi-card__body">
                                <h2 class="mmi-card__title">
                                    <a class="mmi-card__link" href="<?php echo esc_url( site_url( '/pedagogie-item/' . get_post_field( 'post_name', $post_id ) ) ); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>

                                <?php if ( $date_debut || $date_fin ) : ?>
                                    <p class="mmi-card__dates">
                                        <?php mmi_portfolio_icon( 'duree', 16 ); ?>
                                        <?php echo esc_html( mmi_portfolio_format_period( $date_debut, $date_fin ) ); ?>
                                    </p>
                                <?php endif; ?>

                                <?php if ( $competences_post || $niveaux_post ) : ?>
                                    <ul class="mmi-badges mmi-card__badges">
                                        <?php foreach ( $competences_post as $name ) : ?>
                                            <li><span class="mmi-badge"><?php echo esc_html( $name ); ?></span></li>
                                        <?php endforeach; ?>
                                        <?php foreach ( $niveaux_post as $name ) : ?>
                                            <li><span class="mmi-badge mmi-badge--outline">Niveau <?php echo esc_html( $name ); ?></span></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </article>
                    </li>
                    <?php
                endwhile;
                ?>
            </ul>

            <?php
            $pagination = paginate_links(
                array(
                    'type'      => 'list',
                    'prev_text' => 'Précédent',
                    'next_text' => 'Suivant',
                )
            );

            if ( $pagination ) :
                ?>
                <nav class="mmi-pagination" aria-label="Pagination des réalisations">
                    <?php echo wp_kses_post( $pagination ); ?>
                </nav>
                <?php
            endif;
            ?>

        <?php else : ?>
            <div class="mmi-empty">
                <p class="mmi-empty__title">Aucune réalisation</p>
                <p class="mmi-empty__text">
                    <?php
                    echo $has_filters
                        ? 'Aucune réalisation ne correspond à ces critères.'
                        : 'Aucune réalisation n\'a encore été publiée.';
                    ?>
                </p>
                <?php if ( $has_filters ) : ?>
                    <a class="mmi-button" href="<?php echo esc_url( $archive_url ); ?>">Voir toutes les réalisations</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</main>
<?php get_footer(); ?>
