<?php
show_admin_bar( false );
// Register Custom Navigation Walker
require_once('inc/wp-bootstrap-navwalker.php');
register_nav_menus( array(
    'MenuTopo' => __( 'MenuTopo', 'rhs' ),
    'SegundoMenu' => __( 'SegundoMenu', 'rhs' ),
    'MenuDropdDown' => __( 'MenuDropdDown', 'rhs' ),
    'MenuFundo' => __( 'MenuFundo', 'rhs' ),
) );


// Incluir scripts necessários no tema
function RHS_scripts() {
   wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/bootstrap/js/bootstrap.min.js', array('jquery'), '3.3.7', true);
   wp_enqueue_script('bootstrap-hover-dropdown', get_template_directory_uri() . '/assets/js/bootstrap-hover-dropdown.min.js', array('jquery'), '2.2.1', true);
}
add_action('wp_enqueue_scripts', 'RHS_scripts');

// Incluir estilos necessários no tema
function RHS_styles() {
   wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/bootstrap/css/bootstrap.min.css');
   wp_enqueue_style('style', get_stylesheet_uri(), array('bootstrap'));
}
add_action('wp_enqueue_scripts', 'RHS_styles');