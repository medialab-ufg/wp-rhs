<?php
/*
 * Plugin Name: Debug Bar Rewrite rules
 * Depends: Debug Bar
 * Plugin URI: https://wordpress.org/plugins/fg-debug-bar-rewrite-rules/
 * Description: Displays the current rewrite rules for the site. Requires the debug bar plugin.
 * Author: Frédéric GILLES
 * Version: 1.1.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_filter( 'debug_bar_panels', 'debug_bar_rewrite_rules_panel' );
if ( ! function_exists( 'debug_bar_rewrite_rules_panel' ) ) {
    function debug_bar_rewrite_rules_panel( $panels ) {
        require_once 'class-debug-bar-rewrite-rules.php';
        $panels[] = new Debug_Bar_Rewrite_Rules();
        return $panels;
    }
}

add_action('debug_bar_enqueue_scripts', 'debug_bar_rewrite_rules_scripts');
function debug_bar_rewrite_rules_scripts() {
	wp_enqueue_style( 'debug-bar-rewrite_rules', plugins_url( "css/debug-bar-rewrite-rules.css", __FILE__ ) );
}
