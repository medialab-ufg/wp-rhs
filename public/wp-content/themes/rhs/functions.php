<?php

/**
* Classe usada nos menus.
* A mesma facilita o uso das classes usadas na tag nav do bootstrap com o wordpress.
**/
require_once('inc/wp-bootstrap-navwalker.php');

/**
* Não aparecer o menu do administrador na pagina do site quando estiver logado.
**/
show_admin_bar( false );


// Incluir JavaScripts necessários no tema
function RHS_scripts() {
   wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/bootstrap/js/bootstrap.min.js', array('jquery'), '3.3.7', true);
   wp_enqueue_script('bootstrap-hover-dropdown', get_template_directory_uri() . '/assets/js/bootstrap-hover-dropdown.min.js', array('jquery'), '2.2.1', true);
}
add_action('wp_enqueue_scripts', 'RHS_scripts');

// Incluir Styles CSS necessários no tema
function RHS_styles() {
   wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/bootstrap/css/bootstrap.min.css');
   wp_enqueue_style('style', get_stylesheet_uri(), array('bootstrap'));
}
add_action('wp_enqueue_scripts', 'RHS_styles');


/**
*
* Registro de navegação personalizado com o painel admin
* 
**/
register_nav_menus( array(
    'menuTopo' => __( 'menuTopo', 'rhs' ),
    'menuTopoDrodDown' => __( 'menuTopoDrodDown', 'rhs' ),
    'menuRodape' => __( 'menuRodape', 'rhs' ),
) );

/**
*
* Menu que fica no segundo nav da página.
*
* @param 'menu' => 'SegundoMenu' Seleciona o menu com este nome no painel admin.
* @param 'theme_location' => 'SegundoMenu' pega o menu que está setado em SegundoMenu
**/
function menuTopo(){
	wp_nav_menu( array(
        'menu'              => 'menuTopo',
        'theme_location'    => 'menuTopo',
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
* @param 'menu' => 'MenuDropdDown' Seleciona o menu com este nome no painel admin.
* @param 'theme_location' => 'MenuDropdDown' pega o menu que está setado em MenuDropDown
*
**/
function menuTopoDrodDown(){
	wp_nav_menu( array(
        'menu'              => 'menuTopoDrodDown',
        'theme_location'    => 'menuTopoDrodDown',
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
* @param 'menu' => 'MenuFundo' Seleciona o menu com este nome no painel admin.
* @param 'theme_location' => 'MenuFundo' pega o menu que está setado em MenuFundo
*
**/
function menuRodape(){
	wp_nav_menu( array(
	    'menu'              => 'menuRodape',
	    'theme_location'    => 'menuRodape',
	    'depth'             => 0,
	    'menu_class'        => 'nav navbar-nav',
	    'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
	    'walker'            => new WP_Bootstrap_Navwalker()) // Classe usada para compor o menu bootstrap com o WP
	);
}

/**
*
* Libera o uso de imagem no post para paginas como a Front-Page
*
**/
function improved_trim_excerpt($text) {
        global $post;
        if ( '' == $text ) {
                $text = get_the_content('');
                $text = apply_filters('the_content', $text);
                $text = str_replace('\]\]\>', ']]&gt;', $text);
                $text = preg_replace('@<script[^>]*?>.*?</script>@si', '', $text);
                $text = strip_tags($text, '<img>');
                $excerpt_length = 80;
                $words = explode(' ', $text, $excerpt_length + 1);
                if (count($words)> $excerpt_length) {
                        array_pop($words);
                        array_push($words, '[...]');
                        $text = implode(' ', $words);
                }
        }
        return $text;
}
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'improved_trim_excerpt');