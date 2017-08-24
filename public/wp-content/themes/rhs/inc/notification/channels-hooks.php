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
    
}

global $RHSNotificationsChannelsHooks;
$RHSNotificationsChannelsHooks = new RHSNotifications_Channel_Hooks();