<?php

$custom_template = get_query_var('custom_template');
// var_dump($custom_template);
// die();
if ($custom_template == 'portfolio') {
    // Afficher l'archive avec la version 'portfolio'
    get_template_part('template-parts/archive', 'portfolio');
} elseif ($custom_template == 'portfolio_pedagogique') {
    // Afficher l'archive avec la version 'portfolio-pedagogique'
    get_template_part('template-parts/archive', 'portfolio-pedagogique');
} else {
    // Affichage par défaut de l'archive
    get_template_part('archive', 'default');
}
