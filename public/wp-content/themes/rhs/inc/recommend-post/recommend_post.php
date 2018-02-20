<?php

class RHSRecommendPost extends RHSMessage {
    const RECOMMEND_POST_TO_KEY = '_rhs_recommend_post_to';
    const RECOMMEND_POST_FROM_KEY = '_rhs_recommend_post_from';

    function __construct() {
        add_action('wp_enqueue_scripts', array(&$this, 'addJS'));
        add_action('wp_ajax_show_people_to_recommend', array($this, 'show_people_to_recommend'));
        add_action('wp_ajax_recommend_the_post', array($this, 'recommend_the_post'));        
    }
    
    function addJS() {
        wp_enqueue_script('recommend_post', get_template_directory_uri() . '/inc/recommend-post/recommend_post.js', array('jquery'));
        wp_localize_script('recommend_post', 'recommend_post', array('ajaxurl' => admin_url('admin-ajax.php')));
    }

    /**
     * Busca de usuários para indicar post
     */
    function show_people_to_recommend() {

        $data = array('suggestions' => array());

        $users = new WP_User_Query(array(
            'search'         => '*' . esc_attr( $_POST['string'] ) . '*',
            'search_columns' => array( 'user_nicename', 'user_email' ),
            'number'         => -1,
            'orderby'        => 'display_name',
        ) );

        foreach ($users->results as $user) {

            $data['suggestions'][] = array(
                'data'  => $user->ID,
                'value' => $user->display_name
            );
        }

        echo json_encode($data);
        exit;

    }

    /**
     * Envia indicação de post para usuário
     */
    function recommend_the_post() {

        $this->clear_messages();

        $current_user = wp_get_current_user();

        $user_id = $_POST['user_id'];
        $user = new RHSUser(get_userdata($user_id));
        if($user instanceof RHSUser) {        
            $post_id = $_POST['post_id'];
            $_user_name = $user->get_name();
            $data['user'] = array(
                'user_id' => $user_id,
                'post_id' => $post_id,
                'recommend_from' => $current_user->ID,
                'value' => $user->display_name,
                'sent_name' => $_user_name
            );
            $this->set_messages($_user_name . ' recebeu a indicação de leitura', false, 'success');
            $data['messages'] = $this->messages();
            $this->add_recomment_post($post_id, $user_id, $current_user, $data);
        } else {
            $data['msgErr'] = "Usuário não encontrado. Tente novamente mais tarde!";
        }

        echo json_encode($data);
        exit;
    }

    function add_recomment_post($post_id, $user_id, $current_user, $data) {
        add_user_meta($user_id, self::RECOMMEND_POST_TO_KEY, $data['user']);
        $return = add_user_meta($current_user->ID, self::RECOMMEND_POST_FROM_KEY, $data['user']);
    
        if ($return)
            do_action('rhs_add_recommend_post', ['post_id' => $post_id, 'user_id' => $user_id]);

        return $return;
    }
}

add_action('init', function() {
    global $RHSRecommendPost;
    $RHSRecommendPost = new RHSRecommendPost();
});


