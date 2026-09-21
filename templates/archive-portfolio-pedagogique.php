<?php
/**
 * Archive du portfolio pédagogique.
 */

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

get_header();

$selected_niveau     = isset( $_GET['niveau'] ) ? sanitize_title( wp_unslash( $_GET['niveau'] ) ) : '';
$selected_competence = isset( $_GET['competence'] ) ? sanitize_title( wp_unslash( $_GET['competence'] ) ) : '';

$niveaux = get_terms(
    array(
        'taxonomy'   => 'niveau',
        'hide_empty' => false,
    )
);
if ( is_wp_error( $niveaux ) ) {
    $niveaux = array();
}

$competences = get_terms(
    array(
        'taxonomy'   => 'competence',
        'hide_empty' => false,
    )
);
if ( is_wp_error( $competences ) ) {
    $competences = array();
}
?>
<main class="portfolio-pedagogique-archive">
    <h1>Portfolio pédagogique</h1>

    <section class="filtres">
        <?php // action="" : le navigateur soumet sur l'URL courante, sans réinjecter $_SERVER. ?>
        <form action="" method="get" id="filtres">
            <fieldset>
                <div class="level select">
                    <label class="screen-reader-text" for="niveau">Filtrer par niveau</label>
                    <select name="niveau" id="niveau">
                        <option value="">Tous les niveaux</option>
                        <?php foreach ( $niveaux as $niveau ) : ?>
                            <option value="<?php echo esc_attr( $niveau->slug ); ?>" <?php selected( $selected_niveau, $niveau->slug ); ?>>
                                <?php echo esc_html( $niveau->name ); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </fieldset>
            <fieldset>
                <div class="skill select">
                    <label class="screen-reader-text" for="competence">Filtrer par compétence</label>
                    <select name="competence" id="competence">
                        <option value="">Toutes les compétences</option>
                        <?php foreach ( $competences as $competence ) : ?>
                            <option value="<?php echo esc_attr( $competence->slug ); ?>" <?php selected( $selected_competence, $competence->slug ); ?>>
                                <?php echo esc_html( $competence->name ); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </fieldset>
            <noscript><button type="submit">Filtrer</button></noscript>
        </form>
    </section>

    <section class="realisations">
        <?php
        if ( have_posts() ) :
            while ( have_posts() ) :
                the_post();

                $thumbnail  = get_the_post_thumbnail_url( get_the_ID(), 'large' );
                $date_debut = get_field( 'date_de_debut' );
                $date_fin   = get_field( 'date_de_fin' );
                $style      = $thumbnail ? ' style="background-image: url(' . esc_url( $thumbnail ) . ')"' : '';
                ?>
                <a href="<?php echo esc_url( site_url( '/pedagogie-item/' . get_post_field( 'post_name', get_the_ID() ) ) ); ?>">
                    <article class="post"<?php echo $style; // phpcs:ignore WordPress.Security.EscapeOutput -- déjà échappé ci-dessus. ?>>
                        <h2>
                            <?php the_title(); ?>
                            <?php if ( $date_debut || $date_fin ) : ?>
                                <span>Du <?php echo esc_html( $date_debut ? $date_debut : 'ND' ); ?> au <?php echo esc_html( $date_fin ? $date_fin : 'ND' ); ?></span>
                            <?php endif; ?>
                        </h2>
                    </article>
                </a>
                <?php
            endwhile;
        else :
            ?>
            <p class="aucune-realisation">Aucune réalisation ne correspond à ces filtres.</p>
            <?php
        endif;
        ?>
    </section>
</main>
<?php get_footer(); ?>
