<?php

if(!function_exists('rhs_setup')) : 

    function rhs_setup() {

        /**
        * Não aparecer o menu do administrador na pagina do site. Mesmo quando estiver logado!
        **/
        show_admin_bar( false );

        /**
        * Classe usada nos menus.
        * A mesma facilita o uso das classes usadas na tag nav do bootstrap com o wordpress.
        **/
        require_once('inc/wp-bootstrap-navwalker.php');

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

        add_theme_support( 'post-thumbnails' );

        add_theme_support( 'html5', array( 'comment-list', 'comment-form' ) );

    }

endif;

add_action( 'after_setup_theme', 'rhs_setup' );

// Incluir JavaScripts necessários no tema
function RHS_scripts() {
   wp_enqueue_script('bootstrap', get_template_directory_uri() . '/vendor/bootstrap/js/bootstrap.min.js', array('jquery'), '3.3.7', true);
   wp_enqueue_script('bootstrap-hover-dropdown', get_template_directory_uri() . '/vendor/js/bootstrap-hover-dropdown.min.js', array('jquery'), '2.2.1', true);
   if(is_singular()) wp_enqueue_script('comment-reply');
}
add_action('wp_enqueue_scripts', 'RHS_scripts');

// Incluir Styles CSS necessários no tema
function RHS_styles() {
   wp_enqueue_style('bootstrap', get_template_directory_uri() . '/vendor/bootstrap/css/bootstrap.min.css');

   wp_enqueue_style('style', get_stylesheet_uri(), array('bootstrap'));
}
add_action('wp_enqueue_scripts', 'RHS_styles');

/**
 * Exibir template para comentarios.
 *
 */
if (!function_exists('RHS_Comentarios')) :
    function RHS_Comentarios($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;  
    ?>
    <section id="comment-<?php comment_ID(); ?>">
    <!-- First Comment -->
        <article>

            <figure class="comment-avatar">
              <?php echo get_avatar($comment, 66,'', '', array( 'class' => array( 'img-responsive', 'img-circle' ) ) ); ?>
            </figure>

            <header class="comment-box">
                <div class="comment-head">
                    <h6 class="comment-name by-author"><?php printf( __('Por %s.', 'rhs'), get_comment_author_link()); ?></h6>
                    <time class="comment-date"><?php printf( __('%s às %s.', 'rhs'), get_comment_date(), get_comment_time()); ?></time>
                    <?php comment_reply_link(array('depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => '<i class="fa fa-reply"></i>', 'login_text' => '<i class="fa fa-block"></i>')) ?>
                </div>
                <div class="comment-content">
                    <?php comment_text(); ?>
                </div>
            </header>

        </article>
    </section>
    <?php
}

endif;

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
        'container_class'   => 'col-xs-12',
	    'menu_class'        => 'nav navbar-nav',
	    'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
	    'walker'            => new WP_Bootstrap_Navwalker()) // Classe usada para compor o menu bootstrap com o WP
	);
}

/*
* Função personalizada da paginação.
* A mesma está com as classes do bootstrap
*/
function paginacao_personalizada() {
    global $wp_query;
    $big = 999999999;
    $pages = paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?page=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages,
        'mid_size' => 8,
        'prev_next' => false,
        'type' => 'array',
        'prev_next' => TRUE,
        'prev_text' => '&larr; Anterior',
        'next_text' => 'Próxima &rarr;',
    ));
    if (is_array($pages)) {
        $current_page = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
        echo '<ul class="pagination">';
        foreach ($pages as $i => $page) {
            if ($current_page == 1 && $i == 0) {
                echo "<li class='active'>$page</li>";
            } else {
                if ($current_page != 1 && $current_page == $i) {
                    echo "<li class='active'>$page</li>";
                } else {
                    echo "<li>$page</li>";
                }
            }
        }
        echo '</ul>';
    }
}


/*
* Testando SideBar com Widgets do Wordpress.
*/
function rhs_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Primary Sidebar', 'rhs' ),
        'id'            => 'sidebar-1',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h1 class="widget-title">',
        'after_title'   => '</h1>',
    ) );
}
add_action( 'widgets_init', 'rhs_widgets_init' );
