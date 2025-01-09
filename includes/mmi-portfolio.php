<?php

function mmi_portfolio_template_include($template) {
    if (get_query_var('custom_template') == 'portfolio') {
        //$new_template = plugin_dir_path(__FILE__) . 'templates/archive-portfolio.php';
        $new_template = plugin_dir_path(__FILE__) . '../templates/archive-portfolio.php';
        // echo $new_template;
        // die();
        if (file_exists($new_template)) {
            return $new_template;
        }
    } elseif (get_query_var('custom_template') == 'portfolio_pedagogique') {
        //$new_template = plugin_dir_path(__FILE__) . 'templates/archive-portfolio-pedagogique.php';
        $new_template = plugin_dir_path(__FILE__) . '../templates/archive-portfolio-pedagogique.php';
        if (file_exists($new_template)) {
            return $new_template;
        }
    } elseif (get_query_var('custom_template') == 'pedagogie_item') {
        $new_template = plugin_dir_path(__FILE__) . '../templates/single-portfolio-pedagogique.php';
        if (file_exists($new_template)) {
            return $new_template;
        }
    }
    echo get_query_var('custom_template');
    return $template;
}
add_filter('template_include', 'mmi_portfolio_template_include');

/*
*   Custom url for réalisation items - archive
*/
function custom_rewrite_rules_for_realisation() {
    // Rewrite for portfolio/realisation (archive)
    add_rewrite_rule(
        '^portfolio/realisation/?$',  // URL pour l'archive portfolio/realisation
        'index.php?post_type=realisation&custom_template=portfolio',  // Param custom_template=portfolio for archive
        'top'
    );
    
    // Rewrite for portfolio-pedagogique/realisation (archive)
    add_rewrite_rule(
        '^portfolio-pedagogique/realisation-mmi/?$',  // URL for archive portfolio-pedagogique/realisation
        'index.php?post_type=realisation-mmi&custom_template=portfolio_pedagogique',  // Param custom_template=portfolio_pedagogique for the archive
        'top'
    );
    add_rewrite_rule(
        '^pedagogie-item/([^/]*)/?$', // URL like portfolio-item/foo
        'index.php?pedagogie_item=$matches[1]&custom_template=pedagogie_item', // Param custom_template = pedagogie-item
        'top'
    );
    // // Rewrite for portfolio item (single)
    // add_rewrite_rule(
    //     '^pedagogie-item/([^/]*)/?$',  // URL like portfolio/realisation/foo
    //     'index.php?realisation=$matches[1]&custom_template=portfolio-item',  // Param custom_template=portfolio-item
    //     'top'
    // );
}
add_action('init', 'custom_rewrite_rules_for_realisation');



function add_custom_query_var_for_archive_realisation($vars) {
    $vars[] = 'custom_template';
    $vars[] = 'pedagogie_item';
    return $vars;
}
add_filter('query_vars', 'add_custom_query_var_for_archive_realisation');

flush_rewrite_rules();

// Add custom styles for portfolio
function mmi_portfolio_enqueue_assets() {
    // Load swiper
    wp_enqueue_style('swiper', 'https://unpkg.com/swiper/swiper-bundle.min.css');
    wp_enqueue_script('swiper', 'https://unpkg.com/swiper/swiper-bundle.min.js', array(), '1.0', true);
    wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('mmi-portfolio', plugin_dir_url(__FILE__) . '../templates/portfolio.css');
    wp_enqueue_script('mmi-portfolio', plugin_dir_url(__FILE__) . '../templates/portfolio.js', array('jquery', 'swiper'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'mmi_portfolio_enqueue_assets');