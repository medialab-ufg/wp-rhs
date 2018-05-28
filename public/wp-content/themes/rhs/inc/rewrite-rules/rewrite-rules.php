<?php

class RHSRewriteRules {

    private static $instance;
    const LOGIN_URL = 'logar';
    const LOST_PASSWORD_URL = 'esqueceu-a-senha';
    const RETRIEVE_PASSWORD_URL = 'recuperar-senha';
    const RESET_PASS_URL = 'resetar-senha';
    const REGISTER_URL = 'registrar';
    const RP_URL = 'rp';
    const VOTING_QUEUE_URL = 'fila-de-votacao';
    const PROFILE_URL = 'perfil';
    const POST_URL = 'publicar-postagem';
    const POSTAGENS_URL = 'minhas-postagens';
    const COMUNIDADES = 'comunidades';
    const NOTIFICACOES = 'notificacoes';
    const FOLLOW_URL = 'seguindo';
    const FOLLOWED_URL = 'seguidores';

    function __construct() {
            add_action( 'generate_rewrite_rules', array( &$this, 'rewrite_rules' ), 10, 1 );
            add_filter( 'query_vars', array( &$this, 'rewrite_rules_query_vars' ) );
            add_filter( 'template_include', array( &$this, 'rewrite_rule_template_include' ) );
    }


    function rewrite_rules( &$wp_rewrite ) {
        
        $new_rules = array(
            self::LOGIN_URL . "/?$"             => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::LOGIN_URL,
            self::REGISTER_URL . "/?$"          => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::REGISTER_URL,
            self::LOST_PASSWORD_URL . "/?$"     => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::LOST_PASSWORD_URL,
            self::RETRIEVE_PASSWORD_URL . "/?$" => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::RETRIEVE_PASSWORD_URL,
            self::RESET_PASS_URL . "/?$"        => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::RESET_PASS_URL,
            self::RP_URL . "/?$"                => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::RP_URL,
            self::VOTING_QUEUE_URL . "/?$"      => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::VOTING_QUEUE_URL,
            self::PROFILE_URL . "/?$"           => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::PROFILE_URL,
            self::PROFILE_URL . "/([^/]+)/?$"   => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::PROFILE_URL . "&rhs_user=" . $wp_rewrite->preg_index(1),
            self::POST_URL . "/?$"              => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::POST_URL,
            self::POST_URL . "/([^/]+)/?$"      => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::POST_URL . "&rhs_edit_post=" . $wp_rewrite->preg_index(1),
            self::POSTAGENS_URL . "/?$"         => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::POSTAGENS_URL,
            self::COMUNIDADES . "/?$"           => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::COMUNIDADES,
            self::NOTIFICACOES . "/?$"           => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::NOTIFICACOES,
            self::NOTIFICACOES . "/page/([0-9]+)/?$"           => 'index.php?rhs_custom_login=1&rhs_login_tpl='. self::NOTIFICACOES . '&rhs_paged=$matches[1]',

            /* Busca */
            RHSSearch::BASE_URL . '/?$'                                          => "index.php?rhs_busca=posts&rhs_login_tpl=search",
            RHSSearch::BASE_URL . '/page/([0-9]+)/?$'                            => 'index.php?rhs_busca=posts&rhs_login_tpl=search&paged=$matches[1]',
            RHSSearch::BASE_URL . '/([A-Z]{2})/?$'                               => 'index.php?rhs_busca=posts&rhs_login_tpl=search&uf=$matches[1]',
            RHSSearch::BASE_URL . '/([A-Z]{2})/page/([0-9]+)/?$'                 => 'index.php?rhs_busca=posts&rhs_login_tpl=search&uf=$matches[1]&paged=$matches[2]',
            RHSSearch::BASE_URL . '/([A-Z]{2})/([^/]+)/?$'                       => 'index.php?rhs_busca=posts&rhs_login_tpl=search&uf=$matches[1]&municipio=$matches[2]',
            RHSSearch::BASE_URL . '/([A-Z]{2})/([^/]+)/page/([0-9]+)/?$'         => 'index.php?rhs_busca=posts&rhs_login_tpl=search&uf=$matches[1]&municipio=$matches[2]&paged=$matches[3]',
            RHSSearch::BASE_USERS_URL . '/?$'                                    => "index.php?rhs_busca=users&rhs_login_tpl=search-users",
            RHSSearch::BASE_USERS_URL . '/page/([0-9]+)/?$'                      => 'index.php?rhs_busca=users&rhs_login_tpl=search-users&paged=$matches[1]',
            RHSSearch::BASE_USERS_URL . '/([A-Z]{2})/?$'                         => 'index.php?rhs_busca=users&rhs_login_tpl=search-users&uf=$matches[1]',
            RHSSearch::BASE_USERS_URL . '/([A-Z]{2})/page/([0-9]+)/?$'           => 'index.php?rhs_busca=users&rhs_login_tpl=search-users&uf=$matches[1]&paged=$matches[2]',
            RHSSearch::BASE_USERS_URL . '/([A-Z]{2})/([^/]+)/?$'                 => 'index.php?rhs_busca=users&rhs_login_tpl=search-users&uf=$matches[1]&municipio=$matches[2]',
            RHSSearch::BASE_USERS_URL . '/([A-Z]{2})/([^/]+)/page/([0-9]+)/?$'   => 'index.php?rhs_busca=users&rhs_login_tpl=search-users&uf=$matches[1]&municipio=$matches[2]&paged=$matches[3]',


            /* Seguidores e seguidos */
            'usuario/([^/]+)/' . self::FOLLOWED_URL . '/?$'                         => 'index.php?author_name=$matches[1]&rhs_login_tpl=' . self::FOLLOWED_URL,
            'usuario/([^/]+)/'. self::FOLLOWED_URL . '/page/?([0-9]{1,})/?$' => 'index.php?author_name=$matches[1]&rhs_paged=$matches[2]&rhs_login_tpl=' . self::FOLLOWED_URL,
            'usuario/([^/]+)/' . self::FOLLOW_URL . '/?$'                         => 'index.php?author_name=$matches[1]&rhs_login_tpl=' . self::FOLLOW_URL,
            'usuario/([^/]+)/'. self::FOLLOW_URL . '/page/?([0-9]{1,})/?$'   => 'index.php?author_name=$matches[1]&rhs_paged=$matches[2]&rhs_login_tpl=' . self::FOLLOW_URL,
            
            /* Páginas padrões antigas */
            'login' . "/?$"         => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::LOGIN_URL,
            'user' . "/?$"          => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::LOGIN_URL,
            'users/([^/]+)/?'        => 'index.php?author_name=$matches[1]',
            'user/login' . "/?$"    => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::LOGIN_URL,
            'user/register' . "/?$" => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::REGISTER_URL,
            'user/me/edit' . "/?$"  => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::PROFILE_URL,
            'node/add/blog' . "/?$" => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::POST_URL,  
            'tags/([^/]+)/?'        => 'index.php?tag=$matches[1]',
            'category/tags/([^/]+)/?'        => 'index.php?tag=$matches[1]',
            'grupos/([^/]+)/?'        => 'index.php?'.RHSComunities::TAXONOMY.'=$matches[1]'
        );
        

        $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
    }

    function rewrite_rules_query_vars( $public_query_vars ) {

        $public_query_vars[] = "rhs_custom_login";
        $public_query_vars[] = "rhs_login_tpl";
        $public_query_vars[] = "rhs_edit_post";
        $public_query_vars[] = "rhs_user";
        $public_query_vars[] = "uf";
        $public_query_vars[] = "municipio";
        $public_query_vars[] = "rhs_busca";
        $public_query_vars[] = "rhs_paged";

        return $public_query_vars;

    }

    function rewrite_rule_template_include( $template ) {
        global $wp_query;

        if ( $wp_query->get( 'rhs_login_tpl' ) ) {

            $this->rewrite_permitions($wp_query->get( 'rhs_login_tpl' ));

            if ( file_exists( STYLESHEETPATH . '/' . $wp_query->get( 'rhs_login_tpl' ) . '.php' ) ) {
                return STYLESHEETPATH . '/' . $wp_query->get( 'rhs_login_tpl' ) . '.php';
            }

        }

        return $template;


    }

    private function rewrite_permitions($url){

        $pages_not_login = array(
            self::LOGIN_URL,
            self::REGISTER_URL,
            self::LOST_PASSWORD_URL,
            self::RETRIEVE_PASSWORD_URL,
            self::RESET_PASS_URL,
        );

        $pages_for_login = array(
            self::PROFILE_URL,
            self::POST_URL
        );

        if(is_user_logged_in() && in_array($url, $pages_not_login)){
            wp_redirect(home_url());
            exit;
        }

        if(!is_user_logged_in() && in_array($url, $pages_for_login)){
            wp_redirect(home_url());
            exit;
        }

    }

}

global $RHSRewriteRules;
$RHSRewriteRules = new RHSRewriteRules();
