<?php
/*
 * Plugin Name: MMI Portfolio
 * Description: Plugin pour la gestion du portfolio en BUT MMI
 * Version: 1.0
 * Author: Mathias Clavelin 
 */

 // Security
if (!defined('ABSPATH')) {
    die;
}

if ( ! is_admin() ) {
    require_once( ABSPATH . 'wp-admin/includes/post.php' );
}

// Load dependencies
require_once plugin_dir_path(__FILE__) . 'includes/mmi-portfolio-loader.php';

// Init data 
require_once plugin_dir_path(__FILE__) . 'data/mmi-portfolio-data.php';

// Logic
require_once plugin_dir_path(__FILE__) . 'includes/mmi-portfolio.php';