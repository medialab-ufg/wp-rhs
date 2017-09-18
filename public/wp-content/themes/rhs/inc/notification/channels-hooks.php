<?php 
/**
 * Classe com hooks para adicionar usuários a canais dependendo das ações que fizerem
 * 
 */


class RHSNotifications_Channel_Hooks {
    
    
    function __construct() {
        
        // Hooks que adicionam usuários aos canais dependendo das ações
        add_action('rhs_add_user_comunity_follow', array(&$this, 'rhs_add_user_comunity_follow'), 10, 2);
        add_action('rhs_delete_user_comunity_follow', array(&$this, 'rhs_delete_user_comunity_follow'), 10, 2);
        
        add_action('rhs_add_user_follow_author', array(&$this, 'rhs_add_user_follow_author'));
        add_action('rhs_delete_user_follow_author', array(&$this, 'rhs_delete_user_follow_author'));
        
        add_action('rhs_new_post_from_user', array(&$this, 'rhs_new_post_from_user'));
        add_action('comment_post', array(&$this, 'comment_post'));

        add_action('rhs_add_user_follow_post', array(&$this, 'rhs_add_user_follow_post'));
        add_action('rhs_delete_user_follow_post', array(&$this, 'rhs_delete_user_follow_post'));
        
    }
    
    /**
     * Quando usuário entra em uma comunidade
     */
    function rhs_add_user_comunity_follow($community_id, $user_id) {
        global $RHSNotifications;
        $RHSNotifications->add_user_to_channel(RHSNotifications::CHANNEL_COMMUNITY, $community_id, $user_id );
    }
    
    /**
     * Quando usuário sai de uma comunidade
     */
    function rhs_delete_user_comunity_follow($community_id, $user_id) {
        global $RHSNotifications;
        $RHSNotifications->delete_user_from_channel(RHSNotifications::CHANNEL_COMMUNITY, $community_id, $user_id);
    }
    
    
    /**
     * Quando usuário começa a seguir um autor
     */
    function rhs_add_user_follow_author($args) {
        global $RHSNotifications;
        $RHSNotifications->add_user_to_channel(RHSNotifications::CHANNEL_USER, $args['author_id'], $args['user_id'] );
    }
    
    /**
     * Quando usuário deixa de seguir um autor
     */
    function rhs_delete_user_follow_author($args) {
        global $RHSNotifications;
        $RHSNotifications->delete_user_from_channel(RHSNotifications::CHANNEL_USER, $args['author_id'], $args['user_id']);
    }
    
    /**
     * Quando um novo post é publicado, o autor do post entra no canal deste post
     */
    function rhs_new_post_from_user($args) {
        global $RHSNotifications;
        $RHSNotifications->add_user_to_channel(RHSNotifications::CHANNEL_COMMENTS, $args['post_id'], $args['user_id']);
    }
    
    /**
     * Quando um novo comentário é feito, o autor do comentário entra no canal do post
     */
    function comment_post($comment_id) {
        $c = get_comment($comment_id);
        global $RHSNotifications;
        $RHSNotifications->add_user_to_channel(RHSNotifications::CHANNEL_COMMENTS, $c->comment_post_ID, $c->user_id);
    }
    
    /**
     * Quando um usuário começa a seguir um post
     */
    function rhs_add_user_follow_post($args) {
        global $RHSNotifications;
        $RHSNotifications->add_user_to_channel(RHSNotifications::CHANNEL_COMMENTS, $args['post_id'], $args['user_id']);
    }

    /**
     * Quando um usuário parar de seguir um post
     */
    function rhs_delete_user_follow_post($args) {
        global $RHSNotifications;
        $RHSNotifications->delete_user_from_channel(RHSNotifications::CHANNEL_COMMENTS, $args['post_id'], $args['user_id']);
    }
    
}

global $RHSNotificationsChannelsHooks;
$RHSNotificationsChannelsHooks = new RHSNotifications_Channel_Hooks();