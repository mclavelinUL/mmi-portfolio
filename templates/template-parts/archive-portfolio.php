<?php get_header(); ?>

<h1>Portfolio</h1>
<?php
    // loop trough posts
    if (have_posts()):
        while (have_posts()):
            the_post();
            $pro = get_field('pro');
            ?>
            <article class="post">
                <h2>
                    <a href="<?php echo site_url() . '/portfolio-item/' . $post->post_name; ?>">
                        <?php echo $pro['titre']; ?>
                    </a>
                </h2>
                <?php the_post_thumbnail(); ?>
            </article>
            <?php
        endwhile;
    endif;
?>
<?php get_footer(); ?>