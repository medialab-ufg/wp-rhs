<?php
/**
* Classe usada nos menus.
* A mesma facilita o uso das classes do bootstrap com o wordpress.
**/
require_once('inc/wp-bootstrap-navwalker.php');

/**
* Não aparecer o menu do administrador na pagina do site quando estiver logado.
**/
show_admin_bar( false );

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


/**
*
*Registro de navegação personalizado com o painel admin
* 
**/
register_nav_menus( array(
    'MenuTopo' => __( 'MenuTopo', 'rhs' ), //Não está sendo usado por ainda não ter adicionado o sistema de login.
    'SegundoMenu' => __( 'SegundoMenu', 'rhs' ),
    'MenuDropdDown' => __( 'MenuDropdDown', 'rhs' ),
    'MenuFundo' => __( 'MenuFundo', 'rhs' ),
) );

/**
*
* Menu que fica no segundo nav da página.
*
*@param 'menu' => 'SegundoMenu' Seleciona o menu com este nome no painel admin.
*@param 'theme_location' => 'SegundoMenu' pega o menu que está setado em SegundoMenu
**/
function segundoMenu(){
	wp_nav_menu( array(
        'menu'              => 'SegundoMenu',
        'theme_location'    => 'SegundoMenu',
        'depth'             => 0,
        'menu_class'        => 'nav navbar-nav navbar-right',
        'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
        'walker'            => new WP_Bootstrap_Navwalker()) // Classe usada para compor o menu bootstrap com o WP
    );
}

/**
*
* Menu dropdown que fica no segundo nav da página.
*
*@param 'menu' => 'MenuDropdDown' Seleciona o menu com este nome no painel admin.
*@param 'theme_location' => 'MenuDropdDown' pega o menu que está setado em MenuDropDown
*
**/
function menuDropDown(){
	wp_nav_menu( array(
        'menu'              => 'MenuDropdDown',
        'theme_location'    => 'MenuDropdDown',
        'depth'             => 1,
        'container'         => false,
        'menu_class'        => 'dropdown-menu',
        'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
        'walker'            => new WP_Bootstrap_Navwalker()) // Classe usada para compor o menu bootstrap com o WP
    );
}

/**
*
* Menu que fica no footer da página.
*
*@param 'menu' => 'MenuFundo' Seleciona o menu com este nome no painel admin.
*@param 'theme_location' => 'MenuFundo' pega o menu que está setado em MenuFundo
*
**/
function menuFundo(){
	wp_nav_menu( array(
	    'menu'              => 'MenuFundo',
	    'theme_location'    => 'MenuFundo',
	    'depth'             => 0,
	    'menu_class'        => 'nav navbar-nav',
	    'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
	    'walker'            => new WP_Bootstrap_Navwalker()) // Classe usada para compor o menu bootstrap com o WP
	);
}