<?php

class RHSRewriteRules {

    private static $instance;

    const BASE = 'index.php?';
    const TPL_QUERY   = 'rhs_login_tpl';
    const LOGIN_QUERY = 'rhs_custom_login';
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
    const STATISTICS = 'statistics';
    const COMUNIDADES = 'comunidades';
    const NOTIFICACOES = 'notificacoes';
    const FOLLOW_URL = 'seguindo';
    const FOLLOWED_URL = 'seguidores';
    const FOLLOWED_POSTS_URL = 'posts_seguidos';

    function __construct() {
        add_action('generate_rewrite_rules', array( &$this, 'rewrite_rules'), 10, 1);
        add_filter('query_vars', array( &$this, 'rewrite_rules_query_vars'));
        add_filter('template_include', array( &$this, 'rewrite_rule_template_include'));
    }

    function rewrite_rules( &$wp_rewrite ) {
        $tpl = self::TPL_QUERY;
        $login = self::LOGIN_QUERY;
        $base = self::BASE;
        $search = RHSSearch::SEARCH_PARAM;
        $search_post = RHSSearch::BASE_URL;
        $search_user = RHSSearch::BASE_USERS_URL;
        $user_base = 'usuario/([^/]+)/';

        $new_rules = array(
            self::LOGIN_URL . "/?$"             => $base . "$login=1&$tpl=" . self::LOGIN_URL,
            self::REGISTER_URL . "/?$"          => $base . "$login=1&$tpl=" . self::REGISTER_URL,
            self::LOST_PASSWORD_URL . "/?$"     => $base . "$login=1&$tpl=" . self::LOST_PASSWORD_URL,
            self::RETRIEVE_PASSWORD_URL . "/?$" => $base . "$login=1&$tpl=" . self::RETRIEVE_PASSWORD_URL,
            self::RESET_PASS_URL . "/?$"        => $base . "$login=1&$tpl=" . self::RESET_PASS_URL,
            self::RP_URL . "/?$"                => $base . "$login=1&$tpl=" . self::RP_URL,
            self::VOTING_QUEUE_URL . "/?$"      => $base . "$login=1&$tpl=" . self::VOTING_QUEUE_URL,
            self::PROFILE_URL . "/?$"           => $base . "$login=1&$tpl=" . self::PROFILE_URL,
            self::PROFILE_URL . "/([^/]+)/?$"   => $base . "$login=1&$tpl=" . self::PROFILE_URL . "&rhs_user=" . $wp_rewrite->preg_index(1),
            self::POST_URL . "/?$"              => $base . "$login=1&$tpl=" . self::POST_URL,
            self::POST_URL . "/([^/]+)/?$"      => $base . "$login=1&$tpl=" . self::POST_URL . "&rhs_edit_post=" . $wp_rewrite->preg_index(1),
            self::POSTAGENS_URL . "/?$"         => $base . "$login=1&$tpl=" . self::POSTAGENS_URL,
            self::COMUNIDADES . "/?$"           => $base . "$login=1&$tpl=" . self::COMUNIDADES,
            self::STATISTICS . "/?$"            => $base . "$login=1&$tpl=" . self::STATISTICS,
            self::NOTIFICACOES . "/?$"          => $base . "$login=1&$tpl=" . self::NOTIFICACOES,
            self::NOTIFICACOES."/page/([0-9]+)/?$" => $base . "$login=1&$tpl=". self::NOTIFICACOES . '&rhs_paged=$matches[1]',

            /* Busca */
            $search_post . '/?$'                                  => $base . "$search=posts&$tpl=search",
            $search_post . '/page/([0-9]+)/?$'                    => $base . $search . '=posts&'.$tpl.'=search&paged=$matches[1]',
            $search_post . '/([A-Z]{2})/?$'                       => $base . $search . '=posts&'.$tpl.'=search&uf=$matches[1]',
            $search_post . '/([A-Z]{2})/page/([0-9]+)/?$'         => $base . $search . '=posts&'.$tpl.'=search&uf=$matches[1]&paged=$matches[2]',
            $search_post . '/([A-Z]{2})/([^/]+)/?$'               => $base . $search . '=posts&'.$tpl.'=search&uf=$matches[1]&municipio=$matches[2]',
            $search_post . '/([A-Z]{2})/([^/]+)/page/([0-9]+)/?$' => $base . $search . '=posts&'.$tpl.'=search&uf=$matches[1]&municipio=$matches[2]&paged=$matches[3]',
            $search_user . '/?$'                                  => $base . "$search=users&$tpl=search-users",
            $search_user . '/page/([0-9]+)/?$'                    => $base . $search . '=users&'.$tpl.'=search-users&paged=$matches[1]',
            $search_user . '/([A-Z]{2})/?$'                       => $base . $search . '=users&'.$tpl.'=search-users&uf=$matches[1]',
            $search_user . '/([A-Z]{2})/page/([0-9]+)/?$'         => $base . $search . '=users&'.$tpl.'=search-users&uf=$matches[1]&paged=$matches[2]',
            $search_user . '/([A-Z]{2})/([^/]+)/?$'               => $base . $search . '=users&'.$tpl.'=search-users&uf=$matches[1]&municipio=$matches[2]',
            $search_user . '/([A-Z]{2})/([^/]+)/page/([0-9]+)/?$' => $base . $search . '=users&'.$tpl.'=search-users&uf=$matches[1]&municipio=$matches[2]&paged=$matches[3]',

            /* Seguidores e seguidos */
            $user_base . self::FOLLOWED_URL . '/?$'                   => $base . 'author_name=$matches[1]&'.$tpl.'=' . self::FOLLOWED_URL,
            $user_base . self::FOLLOWED_URL . '/page/?([0-9]{1,})/?$' => $base . 'author_name=$matches[1]&rhs_paged=$matches[2]&'.$tpl.'=' . self::FOLLOWED_URL,
            $user_base . self::FOLLOW_URL . '/?$'                     => $base . 'author_name=$matches[1]&'.$tpl.'=' . self::FOLLOW_URL,
            $user_base . self::FOLLOW_URL . '/page/?([0-9]{1,})/?$'   => $base . 'author_name=$matches[1]&rhs_paged=$matches[2]&'.$tpl.'=' . self::FOLLOW_URL,
            $user_base . self::FOLLOWED_POSTS_URL . '/?$'             => $base . 'author_name=$matches[1]&'.$tpl.'=' . self::FOLLOWED_POSTS_URL,
            $user_base . self::FOLLOWED_POSTS_URL.'/page/?([0-9]{1,})/?$'   => $base . 'author_name=$matches[1]&rhs_paged=$matches[2]&'.$tpl.'=' . self::FOLLOWED_POSTS_URL,

            /* Páginas padrões antigas */
            'login' . "/?$"         => $base . "$login=1&$tpl=" . self::LOGIN_URL,
            'user' . "/?$"          => $base . "$login=1&$tpl=" . self::LOGIN_URL,
            'users/([^/]+)/?'       => $base . 'author_name=$matches[1]',
            'user/login' . "/?$"    => $base . "$login=1&$tpl=" . self::LOGIN_URL,
            'user/register' . "/?$" => $base . "$login=1&$tpl=" . self::REGISTER_URL,
            'user/me/edit' . "/?$"  => $base . "$login=1&$tpl=" . self::PROFILE_URL,
            'node/add/blog' . "/?$" => $base . "$login=1&$tpl=" . self::POST_URL,
            'tags/([^/]+)/?'        => $base . 'tag=$matches[1]',
            'category/tags/([^/]+)/?' => $base . 'tag=$matches[1]',
            'grupos/([^/]+)/?'        => $base . RHSComunities::TAXONOMY.'=$matches[1]'
        );
        

        $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
    }

    function rewrite_rules_query_vars( $public_query_vars ) {
        $public_query_vars[] = self::LOGIN_QUERY;
        $public_query_vars[] = self::TPL_QUERY;
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

        if ($wp_query->get(self::TPL_QUERY)) {

            $this->rewrite_permitions($wp_query->get(self::TPL_QUERY));

            if (file_exists( STYLESHEETPATH . '/' . $wp_query->get(self::TPL_QUERY) . '.php') ) {
                return STYLESHEETPATH . '/' . $wp_query->get(self::TPL_QUERY) . '.php';
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
