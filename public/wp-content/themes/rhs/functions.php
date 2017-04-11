<?php
// Register Custom Navigation Walker
require_once('inc/wp-bootstrap-navwalker.php');
register_nav_menus( array(
    'MenuTopo' => __( 'MenuTopo', 'rhs' ),
    'SegundoMenu' => __( 'SegundoMenu', 'rhs' ),
    'SegundoMenu' => __( 'MenuFundo', 'rhs' ),
) );