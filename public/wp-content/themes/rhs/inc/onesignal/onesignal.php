<?php
/**
 * Classe de integração das notificações com o serviço One Signal para envio de Push notifications para celulares
 */

class RHSOneSignal {
    
    const DEVICE_ID_META = '_device_id';
    
    function __construct() {
        
    }
    
    private function get_app_id() {
        if (defined(ONESIGNAL_APP_ID) && !empty(ONESIGNAL_APP_ID))
            return ONESIGNAL_APP_ID;
        return null;
    }
    
    private function get_auth_key() {
        if (defined(ONESIGNAL_AUTH_KEY) && !empty(ONESIGNAL_AUTH_KEY))
            return ONESIGNAL_AUTH_KEY;
        return null;
    }
    
    public function get_user_device_id($user_id) {
        return get_user_meta($user_id, self::DEVICE_ID_META, true);
    }
    
    public function add_user_device_id($user_id, $device_id) {
        return update_user_meta($user_id, self::DEVICE_ID_META, $device_id);
    }
    
    public function delete_user_device_id($user_id, $device_id) {
        return delete_user_meta($user_id, self::DEVICE_ID_META, $device_id);
    }
    
    private function send_request($request, $endpoint, $method = 'POST') {
        
        $app_id = $this->get_app_id();
        $auth_key = $this->get_auth_key();
        
        if (empty($app_id) || empty($auth_key))
            return false;
        
        $request = array_merge($request, ['app_id' => $app_id]);
        
        return wp_remote_post('https://onesignal.com/api/v1/' . $endpoint, [
            'method' => $method,
            'headers' => ['Content-Type' => 'application/json; charset=utf-8', 'Authorization' => 'Basic ' . $auth_key],
            'body' => json_encode($request)
            
        ]);
        
    }
    
} 

global $RHSOneSignal;
$RHSOneSignal = new RHSOneSignal();