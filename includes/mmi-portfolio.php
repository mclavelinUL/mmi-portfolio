<?php

function mmi_portfolio_template_include($template) {
    if (get_query_var('custom_template') == 'portfolio') {
        //$new_template = plugin_dir_path(__FILE__) . 'templates/archive-portfolio.php';
        $new_template = plugin_dir_path(__FILE__) . '../templates/template-parts/archive-portfolio.php';
        // echo $new_template;
        // die();
        if (file_exists($new_template)) {
            return $new_template;
        }
    } elseif (get_query_var('custom_template') == 'portfolio_pedagogique') {
        //$new_template = plugin_dir_path(__FILE__) . 'templates/archive-portfolio-pedagogique.php';
        $new_template = plugin_dir_path(__FILE__) . '../templates/template-parts/archive-portfolio-pedagogique.php';
        if (file_exists($new_template)) {
            return $new_template;
        }
    }
    return $template;
}
add_filter('template_include', 'mmi_portfolio_template_include');
/*
*   Custom url for réalisation items - single
*/
function custom_rewrite_rules() {
    add_rewrite_rule(
        '^portfolio-item/([^/]*)/?$',  // URL like portfolio-item/foo
        'index.php?realisation==$matches[1]&custom_template=portfolio-item', // Param custom_template = portfolio-item
        'top'
    );
    
    add_rewrite_rule(
        '^pedagogie-item/([^/]*)/?$', // URL like portfolio-item/foo
        'index.php?realisation=$matches[1]&custom_template=pedagogie-item', // Param custom_template = pedagogie-item
        'top'
    );
}
add_action('init', 'custom_rewrite_rules');

function add_custom_query_var($vars) {
    $vars[] = 'custom_template';
    return $vars;
}
add_filter('query_vars', 'add_custom_query_var');

/*
*   Custom url for réalisation items - archive
*/
function custom_archive_rewrite_rules_for_realisation() {
    // Rewrite for portfolio/realisation (archive)
    add_rewrite_rule(
        '^portfolio/realisation/?$',  // URL pour l'archive portfolio/realisation
        'index.php?post_type=realisation&custom_template=portfolio',  // Param custom_template=portfolio for archive
        'top'
    );
    
    // Rewrite for portfolio-pedagogique/realisation (archive)
    add_rewrite_rule(
        '^portfolio-pedagogique/realisation/?$',  // URL for archive portfolio-pedagogique/realisation
        'index.php?post_type=realisation&custom_template=portfolio_pedagogique',  // Param custom_template=portfolio_pedagogique for the archive
        'top'
    );
}
add_action('init', 'custom_archive_rewrite_rules_for_realisation');

function add_custom_query_var_for_archive_realisation($vars) {
    $vars[] = 'custom_template';
    return $vars;
}
add_filter('query_vars', 'add_custom_query_var_for_archive_realisation');

flush_rewrite_rules();