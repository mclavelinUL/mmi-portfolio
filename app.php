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

// Load dependencies
require_once plugin_dir_path(__FILE__) . 'includes/mmi-portfolio-loader.php';
//echo plugin_dir_path(__FILE__) . 'includes/mmi-portfolio-loader.php';