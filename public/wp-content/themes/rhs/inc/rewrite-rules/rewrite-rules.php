<?php

class RHSRewriteRules {

    private static $instance;
    const LOGIN_URL = 'login';
    const LOST_PASSWORD_URL = 'lostpassword';
    const RETRIEVE_PASSWORD_URL = 'retrievepassword';
    const RESET_PASS = 'resetpass';
    const REGISTER_URL = 'register';
    const RP = 'rp';

    function __construct() {
        if(is_user_logged_in())
            return home_url();

        if ( empty ( self::$instance ) ) {
            add_action( 'generate_rewrite_rules', array( &$this, 'rewrite_rules' ), 10, 1 );
            add_filter( 'query_vars', array( &$this, 'rewrite_rules_query_vars' ) );
            add_filter( 'template_include', array( &$this, 'rewrite_rule_template_include' ) );
        }

        self::$instance = true;
    }

    function rewrite_rules( &$wp_rewrite ) {
        $new_rules         = array(
            self::LOGIN_URL . "/?$"             => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::LOGIN_URL,
            self::REGISTER_URL . "/?$"          => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::REGISTER_URL,
            self::LOST_PASSWORD_URL . "/?$"     => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::LOST_PASSWORD_URL,
            self::RETRIEVE_PASSWORD_URL . "/?$" => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::RETRIEVE_PASSWORD_URL,
            self::RESET_PASS . "/?$"            => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::RESET_PASS,
            self::RP . "/?$"            => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::RP

        );
        $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;

    }

    function rewrite_rules_query_vars( $public_query_vars ) {

        $public_query_vars[] = "rhs_custom_login";
        $public_query_vars[] = "rhs_login_tpl";

        return $public_query_vars;

    }

    function rewrite_rule_template_include( $template ) {
        global $wp_query;

        if ( $wp_query->get( 'rhs_login_tpl' ) ) {

            if ( file_exists( STYLESHEETPATH . '/' . $wp_query->get( 'rhs_login_tpl' ) . '.php' ) ) {
                return STYLESHEETPATH . '/' . $wp_query->get( 'rhs_login_tpl' ) . '.php';
            }

        }

        return $template;


    }

}

global $RHSRewriteRules;
$RHSRewriteRules = new RHSRewriteRules();