<?php get_header(); ?>
<main class="portfolio-pedagogique-archive">
    <h1>Portfolio pédagogique</h1>
    <section class="filtres">
        <?php

        $base_url = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 'https' : 'http' ) . '://' .  $_SERVER['HTTP_HOST'];
        $url = $base_url . $_SERVER["REQUEST_URI"];
        ?>
        <form action="<?php echo $url; ?>" method="get" id="filtres">
            <fieldset>
                <!-- <label for="niveau">Niveau</label> -->
                <div class="level select">
                    <select name="niveau" id="niveau">
                        <option value="">Tous les niveaux</option>
                        <?php
                            $niveaux = get_terms('niveau');
                            foreach ($niveaux as $niveau) {
                                ?>
                                <option value="<?php echo $niveau->slug; ?>" <?php echo (isset($_GET['niveau']) && $niveau->slug == $_GET['niveau']) ? "selected" : ""; ?>>
                                    <?php echo $niveau->name; ?>
                                </option>
                                <?php
                            }
                        ?>
                    </select>
                </div>
            </fieldset>
            <fieldset>
                <!-- <label for="competence">Compétence</label> -->
                <div class="skill select">
                    <select name="competence" id="competence">
                        <option value="">Toutes les compétences</option>
                        <?php
                            $competences = get_terms('competence');
                            foreach ($competences as $competence) {
                                ?>
                                <option value="<?php echo $competence->slug; ?>" <?php echo (isset($_GET['competence']) && $competence->slug == $_GET['competence']) ? "selected" : ""; ?>>
                                    <?php echo $competence->name; ?>
                                </option>
                                <?php
                            }
                        ?>
                    </select>
                </div>
            </fieldset>
        </form>
    </section>
    <section class="realisations">
    <?php
        // loop trough posts
        if (have_posts()):
            while (have_posts()):
                the_post();
                $pedago = get_field('pedagogie');
                ?>
                <a href="<?php echo site_url() . '/pedagogie-item/' . $post->post_name; ?>">
                <article class="post" style="background-image: url(<?php echo get_the_post_thumbnail_url(); ?>)">
                    <h2><?php the_title(); ?><span>Du <?php the_field('date_de_debut'); ?> au <?php the_field('date_de_fin'); ?></span></h2>
                    <!-- <figure>
                        <?php the_post_thumbnail(); ?>
                    </figure> -->
                </article>
                </a>
                <?php
            endwhile;
        endif;
    ?>
    </section>
</main>
<?php get_footer(); ?>