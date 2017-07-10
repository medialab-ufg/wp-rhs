<?php

Class RHSApi  {

    var $apinamespace = 'rhs/v1';

    function __construct() {

        add_action( 'generate_rewrite_rules', array( &$this, 'rewrite_rules' ), 10, 1 );
        add_filter( 'query_vars', array( &$this, 'rewrite_rules_query_vars' ) );
        add_filter( 'template_include', array( &$this, 'rewrite_rule_template_include' ) );
        
        add_action( 'rest_api_init', array( &$this, 'register_rest_route' ) );
     
    }
    
    function register_rest_route() {
        register_rest_route( $this->apinamespace, '/teste/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => array( &$this, 'get_teste' ),
            'args' => array(
                'id' => array(
                    'validate_callback' => function($param, $request, $key) {
                        return is_numeric( $param );
                        }
                )
            ),
            'permission_callback' => function ( $request ) {
                  return current_user_can( 'edit_others_posts' );
                }
        ));
    }
    
    
    
    
    
    ////// Endpoints
    
    function get_teste(WP_REST_Request $request) {
        
        $user = wp_get_current_user();
        $name = $user->display_name;
        
        return array(
            'current_user' => $name, 
            'notification' => 'Você é demais!'
        );
    }
    
    
    
    
    
    
    ////// Callback de login
    
    function rewrite_rules( &$wp_rewrite ) {

        $new_rules = array(
            'api-login-callback/?' => "index.php?rhs_api_callback=1",
        );

        $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;

    }

    function rewrite_rules_query_vars( $public_query_vars ) {

        $public_query_vars[] = "rhs_api_callback";

        return $public_query_vars;

    }

    function rewrite_rule_template_include( $template ) {
        global $wp_query;

        if ( $wp_query->get( 'rhs_api_callback' ) ) {

            // Retorno após fazer autenticação via oauth utilizando a API
            // ver método handle_callback_redirect() da classe WP_REST_OAuth1_UI do plaugin Rest Oauth
            wp_logout();
            die;

        }

        return $template;


    }


}

global $RHSApi;
$RHSApi = new RHSApi();
