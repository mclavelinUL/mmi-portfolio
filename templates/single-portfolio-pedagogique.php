<?php get_header(); ?>
<?php
// global $wp_query;
// echo "<pre>";
// var_dump($wp_query);
// echo "</pre>";
function get_custom_post_slug() {
    if (get_query_var('portfolio_item')) {
        return get_query_var('portfolio_item');
    } elseif (get_query_var('pedagogie_item')) {
        return get_query_var('pedagogie_item');
    }
    return null;
}
?>
<main class="portfolio-pedagogique-single">
    <?php
    
    $post_name = get_custom_post_slug();
    $post = get_page_by_path($post_name, OBJECT, 'realisation-mmi');
    if(!$post) {
        // return to the archive page
        wp_redirect(site_url('/portfolio-pedagogique/realisation-mmi'));
    }
    $post_id = $post->ID;
    
    
    // Get the post thumbnail url
    $thumbnail = get_the_post_thumbnail_url($post_id);
    if($thumbnail):
    ?>
    <div class="hero" style="background-image:url('<?php echo $thumbnail; ?>')">
        <h1><?php echo get_the_title($post_id); ?></h1>
    </div>
    <?php
    else:
    ?>
    <h1><?php echo get_the_title($post_id); ?></h1>
    <?php
    endif;
    $pedagogie = get_field('pedagogie', $post_id);
    
    ?>
    <section class="header">
        <div class="meta">
            <p class="niveau">
            <svg height="32" id="icon" viewBox="0 0 32 32" width="32" xmlns="http://www.w3.org/2000/svg"><defs><style>.cls-1{fill:none;}</style></defs><title/><polygon points="23 4 18 7.75 18 14.25 15 12 10 15.75 10 22.25 7 20 2 23.75 2 30 4 30 4 24.75 7 22.5 10 24.75 10 30 12 30 12 16.75 15 14.5 18 16.75 18 30 20 30 20 8.75 23 6.5 26 8.75 26 30 28 30 28 7.75 23 4"/><rect class="cls-1" data-name="&lt;Transparent Rectangle&gt;" height="32" id="_Transparent_Rectangle_" width="32"/></svg>
                <?php
                $niveaux = get_the_terms($post_id, 'niveau');
                if($niveaux):
                    $niveau = array_shift($niveaux);
                    echo "Niveau " . $niveau->name;
                endif;
                ?>
            </p>
            <p class="competence">
                <svg height="32px" version="1.1" viewBox="0 0 48 60" width="48px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><title/><desc/><defs/><g fill="none" fill-rule="evenodd" id="People" stroke="none" stroke-width="1"><g fill="#000000" id="Icon-22" transform="translate(-6.000000, 0.000000)"><path d="M45,48 C44.448,48 44,48.448 44,49 L44,51 C44,51.552 44.448,52 45,52 C45.552,52 46,51.552 46,51 L46,49 C46,48.448 45.552,48 45,48 L45,48 Z M46,37 C46,36.448 45.552,36 45,36 C44.448,36 44,36.448 44,37 L44,45 C44,45.552 44.448,46 45,46 C45.552,46 46,45.552 46,45 L46,37 Z M52,26 L8,26 C6.897,26 6,26.897 6,28 L6,32 C6,33.103 6.897,34 8,34 L8,55 C8,57.757 10.243,60 13,60 L47,60 C49.757,60 52,57.757 52,55 L52,37 C52,36.448 51.552,36 51,36 C50.448,36 50,36.448 50,37 L50,55 C50,56.654 48.654,58 47,58 L13,58 C11.346,58 10,56.654 10,55 L10,33 C10,32.448 9.552,32 9,32 L8,32 L8,28 L52,28 L52,32 L13,32 C12.448,32 12,32.448 12,33 C12,33.552 12.448,34 13,34 L52,34 C53.103,34 54,33.103 54,32 L54,28 C54,26.897 53.103,26 52,26 L52,26 Z M28,16.466 L28,23 C28,23.552 28.448,24 29,24 C29.552,24 30,23.552 30,23 L30,16 C30,15.686 29.85,15.388 29.599,15.199 C28.583,14.435 28,13.269 28,12 C28,9.484 30.421,5.216 32,2.783 C33.579,5.216 36,9.484 36,12 C36,13.901 34.65,15.55 32.791,15.922 C32.249,16.03 31.897,16.557 32.006,17.098 C32.114,17.64 32.641,17.988 33.182,17.883 C35.974,17.326 38,14.852 38,12 C38,7.729 33.025,0.715 32.814,0.419 C32.438,-0.107 31.562,-0.107 31.186,0.419 C30.975,0.715 26,7.729 26,12 C26,13.714 26.709,15.304 28,16.466 L28,16.466 Z M9,24 L11,24 C11.552,24 12,23.552 12,23 C12,22.448 11.552,22 11,22 L10,22 L10,20 L15,20 C15.552,20 16,19.552 16,19 C16,18.448 15.552,18 15,18 L10,18 L10,16 L11,16 C11.552,16 12,15.552 12,15 C12,14.448 11.552,14 11,14 L10,14 L10,12 L11,12 C11.552,12 12,11.552 12,11 C12,10.448 11.552,10 11,10 L10,10 L10,8 L15,8 C15.552,8 16,7.552 16,7 C16,6.448 15.552,6 15,6 L10,6 L10,2 L22,2 L22,23 C22,23.552 22.448,24 23,24 C23.552,24 24,23.552 24,23 L24,1 C24,0.448 23.552,0 23,0 L9,0 C8.448,0 8,0.448 8,1 L8,23 C8,23.552 8.448,24 9,24 L9,24 Z M45,13 L45,23 C45,23.552 45.448,24 46,24 C46.552,24 47,23.552 47,23 L47,13 C47,12.448 46.552,12 46,12 C45.448,12 45,12.448 45,13 L45,13 Z M51,12 C50.448,12 50,12.448 50,13 L50,23 C50,23.552 50.448,24 51,24 C51.552,24 52,23.552 52,23 L52,13 C52,12.448 51.552,12 51,12 L51,12 Z M41,24 C41.552,24 42,23.552 42,23 L42,9.287 L46,2.887 L49.196,8 L45,8 C44.448,8 44,8.448 44,9 C44,9.552 44.448,10 45,10 L51,10 C51.364,10 51.698,9.803 51.875,9.485 C52.051,9.167 52.041,8.778 51.848,8.47 L46.848,0.47 C46.482,-0.115 45.518,-0.115 45.152,0.47 L40.152,8.47 C40.053,8.629 40,8.812 40,9 L40,23 C40,23.552 40.448,24 41,24 L41,24 Z M36,20 L36,23 C36,23.552 35.552,24 35,24 C34.448,24 34,23.552 34,23 L34,20 C34,19.448 34.448,19 35,19 C35.552,19 36,19.448 36,20 L36,20 Z" id="art-tools"/></g></g></svg>
                <?php
                $competences = get_the_terms($post_id, 'competence');
                if($competences):
                    foreach($competences as $competence):
                        echo $competence->name . ' ';
                    endforeach;
                endif;
                ?>
            </p>
            <p class="date"><svg height="32px" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><defs><style>.cls-12{fill:#101820;}</style></defs><title/><g data-name="Layer 15" id="Layer_15"><path class="cls-12" d="M16,31A15,15,0,1,1,31,16,15,15,0,0,1,16,31ZM16,3A13,13,0,1,0,29,16,13,13,0,0,0,16,3Z"/><path class="cls-12" d="M20.24,21.66l-4.95-4.95A1,1,0,0,1,15,16V8h2v7.59l4.66,4.65Z"/></g></svg> <?php echo $pedagogie['temps_de_travail_estime'] ?>h du <?php echo get_field('date_de_debut'); ?> au <?php echo get_field('date_de_fin'); ?></p>
            <p class="modalite"><svg height="32px" id="Layer_1_1_" style="enable-background:new 0 0 16 16;" version="1.1" viewBox="0 0 16 16" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M3.5,9h9C14.43,9,16,7.43,16,5.5S14.43,2,12.5,2H4c0-1.105-0.895-2-2-2S0,0.895,0,2c0,1.105,0.895,2,2,2  c0.738,0,1.376-0.405,1.723-1H12.5C13.878,3,15,4.122,15,5.5S13.878,8,12.5,8h-9C1.57,8,0,9.57,0,11.5S1.57,15,3.5,15h8.777  c0.346,0.595,0.984,1,1.723,1c1.105,0,2-0.895,2-2c0-1.105-0.895-2-2-2s-2,0.895-2,2H3.5C2.122,14,1,12.878,1,11.5S2.122,9,3.5,9z"/></svg> Modalité : <?php echo $pedagogie['cadre'] ?> / <?php echo $pedagogie['modalite'][0] ?></p>
        </div>
        
    </section>
    <section class="main">
        <div class="content">
            <?php echo apply_filters('the_content', $post->post_content); ?>
        </div>
        
    </section>
    <section class="traces">
        <h2>Traces</h2>
        <?php
        $acs = $pedagogie['apprentissages_critiques_engages'];
        ?>
        <div class="submenu">
            <div class="bt"><span></span><span></span><span></span></div>
            <h2>ACs de la réalisation</h2>
            <ul>
                <?php
                foreach($acs as $ac):
                    ?>
                    <li>
                        <a href="#ac<?php echo $ac['apprentissage_critique'][0]->ID; ?>">
                            <?php echo $ac['apprentissage_critique'][0]->post_title; ?>
                            <?php $competence = get_the_terms($ac['apprentissage_critique'][0]->ID, 'competence'); ?>
                            <?php if($competence): ?>
                            -&nbsp;<span><?php echo $competence[0]->name; ?></span>

                            <?php endif; ?>
                            -&nbsp;<?php echo strip_tags($ac['apprentissage_critique'][0]->post_content); ?>
                        </a>
                    </li>
                    <?php
                endforeach;
                ?>
            </ul>
        </div>
        <?php foreach($acs as $ac): ?>
        <article>
            <header>
                <h3 id="ac<?php echo $ac['apprentissage_critique'][0]->ID; ?>">
                    <?php echo $ac['apprentissage_critique'][0]->post_title; ?> - <span><?php echo strip_tags(apply_filters('the_content', $ac['apprentissage_critique'][0]->post_content)); ?></span>
                </h3>
                <span class="niveau_estime">
                    <?php
                    $niveau = (int) $ac['niveau_estime'];
                    for($i=1; $i<=5; $i++){
                        if($i <= $niveau){
                            echo '<span class="fa fa-star checked"></span>';
                        }
                        else {
                            echo '<span class="fa fa-star"></span>';}
                    }
                        
                    ?>
                    
                </span>
            </header>
            <div class="content">
                
                
                <?php if(!empty($ac['traces_hypertexte'])): ?>
                    <a href="<?php echo $ac['traces_hypertexte']; ?>" target="_blank" class="external_link"><svg height="18px" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><title/><g data-name="1" id="_1"><path d="M149.67,393A95.49,95.49,0,0,1,82.11,230l64.7-64.7a95.65,95.65,0,0,1,135.1,0h0a95.65,95.65,0,0,1,0,135.11l-64.69,64.69A95.23,95.23,0,0,1,149.67,393Zm64.69-225.69A65.29,65.29,0,0,0,168,186.49l-64.7,64.7A65.53,65.53,0,0,0,196,343.86l64.7-64.7a65.51,65.51,0,0,0-46.34-111.84Z"/><path d="M295.9,360.71a95,95,0,0,1-24.13-3.1,15,15,0,0,1,7.6-29,65.52,65.52,0,0,0,62.87-17.08l64.7-64.69a65.53,65.53,0,0,0-92.68-92.68l-64.69,64.7a65.84,65.84,0,0,0-17.08,62.89,15,15,0,0,1-29,7.55,95.92,95.92,0,0,1,24.9-91.65l64.69-64.7A95.53,95.53,0,1,1,428.15,268l-64.69,64.69A94.92,94.92,0,0,1,295.9,360.71Z"/></g></svg>Lien externe</a>
                <?php endif; ?>
                <?php if(count($ac['traces_images'])>0) : ?>
                    <div class="swiper">
                        <div class="swiper-wrapper">
                        <?php foreach($ac['traces_images'] as $image): ?>

                            <div class="swiper-slide">
                                <img src="<?php echo $image['sizes']['large']; ?>" alt="<?php echo $image['alt']; ?>">
                            </div>
                        <?php endforeach; ?>
                        </div>
                        <!-- If we need pagination -->
                        <div class="swiper-pagination"></div>

                        <!-- If we need navigation buttons -->
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>

                        <!-- If we need scrollbar -->
                        <div class="swiper-scrollbar"></div>
                    </div>
                <?php endif; ?>
                <div class="analyse">
                    <h4>Analyse</h4>
                    <?php echo apply_filters('the_content', $ac['analyse']); ?>
                </div>
            </div>
        </article>
        <?php endforeach; ?>    
        <?php
        // echo "<pre>";
        // var_dump($pedagogie);
        // echo "</pre>";
        ?>
    </section>
</main>
<?php get_footer(); ?>