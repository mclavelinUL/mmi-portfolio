<?php get_header(); ?>
<?php if (have_posts()):
    while (have_posts()):
        the_post(); ?>

        <article class="post">
            <?php switch($custom_template) {
                case 'portfolio-item':
                    get_template_part('template-parts/content', 'pro');
                    break;
                case 'pedagogie-item':
                    get_template_part('template-parts/content', 'pedago');
                    break;
                default:
                    the_post_thumbnail();
                    ?>
                    <h1>
                        <?php the_title(); ?>
                    </h1>
                    <?php
                    break;
            }
            ?>
        </article>

    <?php endwhile; endif; ?>
<?php get_footer(); ?>