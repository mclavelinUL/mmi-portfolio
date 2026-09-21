<?php
/**
 * Archive du portfolio pro.
 */

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

get_header();
?>
<main class="portfolio-archive">
    <h1>Portfolio</h1>
    <?php
    if ( have_posts() ) :
        while ( have_posts() ) :
            the_post();

            $pro   = get_field( 'pro' );
            $titre = ( is_array( $pro ) && ! empty( $pro['titre'] ) ) ? $pro['titre'] : get_the_title();
            ?>
            <article class="post">
                <h2>
                    <a href="<?php echo esc_url( site_url( '/portfolio-item/' . get_post_field( 'post_name', get_the_ID() ) ) ); ?>">
                        <?php echo esc_html( $titre ); ?>
                    </a>
                </h2>
                <?php the_post_thumbnail(); ?>
            </article>
            <?php
        endwhile;
    else :
        ?>
        <p>Aucune réalisation pour le moment.</p>
        <?php
    endif;
    ?>
</main>
<?php get_footer(); ?>
