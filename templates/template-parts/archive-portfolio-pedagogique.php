<?php get_header(); ?>

<h1>Portfolio pédagogique</h1>
<?php
    // loop trough posts
    if (have_posts()):
        while (have_posts()):
            the_post();
            $pedago = get_field('pedagogie');
            ?>
            <article class="post">
                <h2>
                    <a href="<?php echo site_url() . '/pedagogie-item/' . $post->post_name; ?>">
                        <?php the_title(); ?>
                    </a>
                </h2>
                <?php the_post_thumbnail(); ?>
            </article>
            <?php
        endwhile;
    endif;
?>
<?php get_footer(); ?>