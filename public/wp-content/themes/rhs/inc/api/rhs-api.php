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

        register_rest_route( $this->apinamespace, '/follow/(?P<id>[\d]+)', array(
            'methods'  => 'POST, DELETE',
            'callback' => array(&$this, 'USER_follow'),
            'args' => array(
                'id' => array(
                    'validate_callback' => function($param, $request, $key) {
                        return is_numeric( $param );
                    }
                )
            ),
            'permission_callback' => function ( $request ) {
                return is_user_logged_in();   
            }
        ) );

        register_rest_route( $this->apinamespace, '/user/(?P<id>[\d]+)', array(
            'methods' => 'GET',
            'callback' => array(&$this, 'USER_show'),
			'args' => array(
				'id' => array(
					'validate_callback' => function($param, $request, $key) {
                        return is_numeric( $param );

                        }
                ),
			)
        ));

        register_rest_route( $this->apinamespace, '/user/include=(?P<include>[a-z0-9 ,\-]+)&page=(?P<page>[0-9]+)&per_page=(?P<per_page>[0-9]+)/', array(
            'methods' => 'GET',
            'callback' => array(&$this, 'USER_show_specific_users'),
			'args' => array(
                'page' => array(
                    'validate_callback' => function($param, $request, $key) {
                        return is_numeric( $param );
                        }
                ),
                'per_page' => array(
                    'validate_callback' => function($param, $request, $key) {
                        return is_numeric( $param );
                        }
                ),
			)
        ));
        

        register_rest_route( $this->apinamespace, '/user-device/(?P<device_or_user_id>[a-zA-Z0-9-]+)', array(
            'methods' => 'POST, GET, DELETE',
            'callback' => array(&$this, 'USER_DEVICE_manipulate'),
            'args' => array(
                'device_or_user_id' => array(
					'validate_callback' => function($param, $request, $key) {
                        return is_string($param);
                    }
                ),     
            ),
            'permission_callback' => function ( $request ) {
                return is_user_logged_in();   
            }
        ));

        register_rest_route( $this->apinamespace, '/notifications/mark-all-read/(?P<id>[\d]+)', array(
            'methods'  => 'POST',
            'callback' => array(&$this, 'USER_read_all_notifications'),
            'args' => array(
                'id' => array(
                    'validate_callback' => function($param, $request, $key) {
                        return is_numeric( $param );
                    }
                )
            ),
            'permission_callback' => function ( $request ) {
                return current_user_can( 'edit_user', $request['id'] );
            }
        ) );
        
        register_rest_route( $this->apinamespace, '/notifications/unread-number/(?P<id>[\d]+)', array(
            'methods' => 'GET',
            'callback' => array(&$this, 'USER_notify_count'),
            'args' => array(
				'id' => array(
					'validate_callback' => function($param, $request, $key) {
                        return is_numeric( $param );
                    }
                ),
            ),
            'permission_callback' => function ( $request ) {
                return current_user_can('edit_user', $request['id']);
            }
        ));
        
        register_rest_route( $this->apinamespace, '/notifications/(?P<id>[\d]+)/page=(?P<page>[0-9]+)/', array(
            'methods' => 'GET',
            'callback' => array(&$this, 'USER_notification_list'),
            'args' => array(
				'id' => array(
					'validate_callback' => function($param, $request, $key) {
                        return is_numeric( $param );
                    }
                ),
                'page' => array(
                    'validate_callback' => function($param, $request, $key) {
                        return is_numeric( $param );
                    }
                ),
            ),
            'permission_callback' => function ( $request ) {
                return current_user_can('edit_user', $request['id']);
            }
        ));
        
        register_rest_route( $this->apinamespace, '/notifications/types/', array(
            'methods' => 'GET',
            'callback' => array(&$this, 'NOTIFICATIONS_types')
        ));

    }

    function USER_follow($request) {
        global $RHSFollow;
        
        if ($request->get_method() == 'POST') {
            $data = $RHSFollow->add_follow($request->get_params()['id'], get_current_user_id());
        } elseif ($request->get_method() == 'DELETE') {
            $data = $RHSFollow->remove_follow($request->get_params()['id'], get_current_user_id());
        }

        $dataR = [
            'response' => $data,
            'user_id' => get_current_user_id(),
            'follow_id' => $request->get_params()['id']
        ];

        $response = new WP_REST_Response( $dataR );
        $response->set_status( 200 );

        return $response;
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
        global $RHSVote, $RHSFollow, $RHSFollowPost;
        
        // Se é uma requisição no endpoint /me ou estamos retornando user logado
        // Vamos trazer informações privadas e mais detalhadas
        if (get_current_user_id() == $user->ID) {
            $data->data['posts_followed'] = $RHSFollowPost->get_posts_followed_by_user($user->ID);
        } 
        
        $data->data['followers'] = $RHSFollow->get_user_followers($user->ID);
        $data->data['follows'] = $RHSFollow->get_user_follows($user->ID);
        
        $total_votes = $RHSVote->get_total_votes_by_author($user->ID);
        $data->data['total_votes'] = $total_votes ? $total_votes : 0;
        
        $total_posts = count_user_posts($user->ID);
        $data->data['total_posts'] = $total_posts ? $total_posts : 0;

        $userObj = new RHSUser($user);
        $data->data['formation'] = $userObj->get_formation();
        $data->data['interest'] = $userObj->get_interest();
        $data->data['state'] = $userObj->get_state();
        $data->data['city'] = $userObj->get_city();
        $data->data['links'] = $userObj->get_links();
        
        return $data;
    }
    
    function USER_show($request) {
        $user = $request['id'];
        if (is_wp_error($user)) {
            return $user;
        }

        $user_obj = get_userdata($request['id']) ;
        $userController = new \WP_REST_Users_Controller($user_obj->ID);
        $response = $userController->prepare_item_for_response( $user_obj, $request );
        return rest_ensure_response($response);
    
    }

    function USER_show_specific_users($request) {
        global $RHSUsers;
        $user = $request['include'];
        $page = $request['page'];
        $per_page = $request['per_page'];
        
        if (is_wp_error($user)) {
            return $user;
        }

        // tratando array
        $user = preg_replace('/\.$/', '', $user);
        $array = explode(',', $user);
        $size = count($array);
        
        // paginação
        $totalPages = ceil($size/$per_page);
        $page = max($page, 1);
        $page = min($page, $totalPages);
        $offset = ($page - 1) * $per_page;
        if ($offset < 0) $offset = 0;
     
        // coleção de resultados
        foreach ($array as $key => $user_id) {
            $user_obj = get_userdata($user_id);

            if ($user_obj !== false) {
                $userController = new WP_REST_Users_Controller($user_id);
                $response[$key] = $userController->prepare_item_for_response($user_obj, $request);
            } else {
                $response[$key] = '';
            }
        }

        $response = array_filter(array_slice($response, $offset, $per_page));

        return rest_ensure_response($response);

    }


    function USER_read_all_notifications($request) {
        global $RHSNotifications;
        $user = $request['id'];
        if (is_wp_error($user)) {
            return $user;
        }
        
        return $RHSNotifications->mark_all_read($user);

    }

    function USER_notify_count($request) {
        $user = $request['id'];
        if (is_wp_error($user)) {
            return $user;
        }
        
        global $RHSNotifications;
        $news_count = $RHSNotifications->get_news_number($user);
        return $news_count;
    }

    function USER_notification_list($request) {
        global $RHSNotifications;
        
        $user = $request['id'];
        $page = $request['page'];
        
        $notifications = $RHSNotifications->get_notifications($user, ['paged' => $page]);
        $total_notifications = $RHSNotifications->get_notifications($user, ['onlyCount' => true]);;

        if (is_wp_error($user)) {
            return $user;
        }

        $max_pages = ceil($total_notifications / RHSNotifications::RESULTS_PER_PAGE);
        
        // Construção de header
        $response = rest_ensure_response($notifications);
        $response->header('X-WP-Total', (int) $total_notifications);
        $response->header('X-WP-TotalPages', (int) $max_pages);

        // Cria url base para paginação
        $request_params = $request->get_query_params();
        if (!empty($request_params['filter'])) {
            unset($request_params['filter']['page']);
        }
        
        $rest_base = 'notification-list';

        $base = add_query_arg($request_params, rest_url(sprintf('/%s/%s', $this->apinamespace, $rest_base)));
 
        // Link para página anterior
        if ($page > 1) {
            $prev_page = $page - 1;
            if ($prev_page > $max_pages) {
                $prev_page = $max_pages;
            }
            $prev_link = add_query_arg('page', $prev_page, $base);
            $response->link_header('prev', $prev_link);
        }

        // Cria link para nova página
        if ($max_pages > $page) {
            $next_page = $page + 1;
            $next_link = add_query_arg('page', $next_page, $base);
            $response->link_header('next', $next_link);
        }
        
        return $response;

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

    // USER DEVICE Callbacks

    function USER_DEVICE_manipulate($request){
        switch($request->get_method()){
            case 'POST':
                return $this->USER_DEVICE_add($request);
            break;
            case 'GET':
                return $this->USER_DEVICE_get($request);
            break;
            case 'DELETE':
                return $this->USER_DEVICE_delete($request);
            break;
        }
    }

    function USER_DEVICE_add($request){
        $current_user = wp_get_current_user();
        $device_push_id = $request->get_params()['device_or_user_id'];
        
        global $RHSOneSignal;
        
        $success = $RHSOneSignal->add_user_device_id($current_user->ID, $device_push_id);
        $RHSOneSignal->sync_user_channels($current_user->ID, $device_push_id);
        $RHSOneSignal->add_user_profile_tags($current_user->ID, $device_push_id);
        
        if($success === true){
            $message = [
                'info' => 'Device ID adicionado com sucesso!', 
                'device_id' => $device_push_id,
                'usermeta_id' => $success
            ];
        }
        else{
            $message = [
                'info' => 'Ooops! Erro ao adicionar Device ID! É possível que esse Device ID já exista para esse usuário.',
                'device_id' => $device_push_id,
                'status' => $success
            ];
        }

        $response = new WP_REST_Response($message);
        $response->set_status(201);

        return $response;
    }

    function USER_DEVICE_get($request){        
        $user_id = $request->get_params()['device_or_user_id'];

        global $RHSOneSignal;

        $device_id = $RHSOneSignal->get_user_device_id($user_id);

        if(empty($device_id)){
           $message = [
               'info' => 'Device ID não existe para esse usuário!',
               'status' => false
            ];
        }
        else{
            $message = $device_id;
        }

        $response = new WP_REST_Response($message);
        $response->set_status(200);

        return $response;
    }

    function USER_DEVICE_delete($request){
        $device_id = $request->get_params()['device_or_user_id'];
        $user_id = wp_get_current_user()->ID;

        global $RHSOneSignal;

        $success = $RHSOneSignal->delete_user_device_id($user_id, $device_id);
        $RHSOneSignal->delete_user_channels($user_id, $device_id);

        if($success){
            $message = [
                'info' => 'Device ID excluído com sucesso!',
                'status' => $success
            ];
        }
        else{
            $message = [
                'info' => 'Ooops! Erro ao excluir Device ID!',
                'status' => $success
            ];
        }

        $response = new WP_REST_Response($message);
        $response->set_status(200);

        return $response;
    }
    
    function NOTIFICATIONS_types($request) {
        
        $types = RHSNotifications::get_notification_types();
        
        $message = ['types' => $types];
        
        $response = new WP_REST_Response($message);
        $response->set_status(200);

        return $response;
        
        
    }

}

global $RHSApi;
$RHSApi = new RHSApi();
