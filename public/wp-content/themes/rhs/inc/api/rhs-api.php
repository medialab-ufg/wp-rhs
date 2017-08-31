<?php

Class RHSApi  {

    var $apinamespace = 'rhs/v1';

    function __construct() {

        add_action( 'generate_rewrite_rules', array( &$this, 'rewrite_rules' ), 10, 1 );
        add_filter( 'query_vars', array( &$this, 'rewrite_rules_query_vars' ) );
        add_filter( 'template_include', array( &$this, 'rewrite_rule_template_include' ) );
        
        add_action( 'rest_api_init', array( &$this, 'register_rest_route' ) );
        
        // Modifica endpoints nativos
        add_filter( 'rest_prepare_post', array(&$this, 'prepare_post'), 10, 3 );
        add_filter( 'rest_prepare_user', array(&$this, 'prepare_user'), 10, 3 );
     
    }
    
    function register_rest_route() {
        register_rest_route( $this->apinamespace, '/votes/(?P<id>[\d]+)', array(
            'methods' => 'POST',
            'callback' => array( &$this, 'POST_vote' ),
            'args' => array(
                'id' => array(
                    'validate_callback' => function($param, $request, $key) {
                        return is_numeric( $param );
                        }
                )
            ),
            'permission_callback' => function ( $request ) {
                $user_can = current_user_can( 'vote_post', $request['id'] );
                if ($user_can)
                    return true;
                
                global $RHSVote;
                return new WP_Error( $RHSVote->votes_to_text_code, $RHSVote->getTextHelp(), array( 'status' => rest_authorization_required_code() ) );

            }
        ));
    }
    
    function POST_vote($request) {
        // Já passamos pela autenticação e permission_callback
        global $RHSVote;
        $data = $RHSVote->add_vote( $request['id'], get_current_user_id() );
        
        $dataR = [
            'response' => $data,
            'post_id' => $request['id'],
            'total_votes' => $RHSVote->get_total_votes($request['id'])
        ];
        
        $response = new WP_REST_Response( $dataR );
        $response->set_status( 200 );

        return $response;
    }
    
    function prepare_post( $data, $post, $context ) {
        global $RHSVote, $RHSNetwork;
        $total_votes = $RHSVote->get_total_votes($post->ID);
        $total_shares = $RHSNetwork->get_post_total_shares($post->ID);
        $data->data['total_votes'] = $total_votes ? $total_votes : 0;
        $data->data['comment_count'] = $post->comment_count;
        $data->data['total_shares'] = $total_shares ? $total_shares : 0;
        return $data;
    }
    
    function prepare_user( $data, $user, $context ) {
        global $RHSVote, $RHSFollow;
        
        $data->data['followers'] = $RHSFollow->get_user_followers($user->ID);
        $data->data['follows'] = $RHSFollow->get_user_follows($user->ID);
        
        $total_votes = $RHSVote->get_total_votes_by_author($user->ID);
        $data->data['total_votes'] = $total_votes ? $total_votes : 0;
        
        $userObj = new RHSUser($user);
        $data->data['formation'] = $userObj->get_formation();
        $data->data['interst'] = $userObj->get_interest();
        $data->data['state'] = $userObj->get_state();
        $data->data['city'] = $userObj->get_city();
        $data->data['links'] = $userObj->get_links();
        
        return $data;
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
