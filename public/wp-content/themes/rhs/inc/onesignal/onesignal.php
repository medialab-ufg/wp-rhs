<?php
/**
 * Classe de integração das notificações com o serviço One Signal para envio de Push notifications para celulares
 */

class RHSOneSignal {
    
    const DEVICE_ID_META = '_device_id';
    const CHANNEL_TAG_PREFIX = 'ch_';
    const NOTF_TYPE_TAG_PREFIX = 'notf_type_';
    
    function __construct() {
        add_action('rhs_add_notification', array(&$this, 'create_push_notification'));
        add_action('rhs_add_user_to_channel', array(&$this, 'add_user_to_channel'), 10, 2);
        add_action('rhs_delete_user_from_channel', array(&$this, 'delete_user_from_channel'), 10, 2);
        add_filter('rhs_notifications_types', array(&$this, 'rhs_notifications_types'), 10, 2);
        add_action('profile_update', array(&$this,'add_user_profile_tags'), 10, 1);
        
    }
    
    private function get_app_id() {
        if (defined('ONESIGNAL_APP_ID') && !empty(ONESIGNAL_APP_ID))
            return ONESIGNAL_APP_ID;
        return null;
    }
    
    private function get_auth_key() {
        if (defined('ONESIGNAL_AUTH_KEY') && !empty(ONESIGNAL_AUTH_KEY))
            return ONESIGNAL_AUTH_KEY;
        return null;
    }
    
    function rhs_notifications_types($types) {
        foreach ($types as $slug => $type) {
            $types[$slug]['onesginal_tag'] = self::NOTF_TYPE_TAG_PREFIX . $slug;
        }
        return $types;
    }
    
    public function get_user_device_id($user_id) {
        return get_user_meta($user_id, self::DEVICE_ID_META, false);
    }
    
    public function add_user_device_id($user_id, $device_id) {
        return rhs_add_user_meta_unique($user_id, self::DEVICE_ID_META, $device_id);
    }
    
    public function delete_user_device_id($user_id, $device_id) {
        return delete_user_meta($user_id, self::DEVICE_ID_META, $device_id);
    }
    
    function create_push_notification($notification){
        
        $endpoint = 'notifications';
        $method = 'POST';
        
        $channel = $notification->getChannel();
        $type = $notification->getType();
        $text = $notification->getTextPush();
        
        
        $request = [
            'included_segments' => ['All'],
            'filters' => [
                [
                    'field' => 'tag',
                    'key' => self::CHANNEL_TAG_PREFIX . $channel,
                    'relation' => 'exists'
                ],
                [
                    'field' => 'tag',
                    'key' => self::NOTF_TYPE_TAG_PREFIX . $type,
                    'relation' => 'not_exists'
                ],
                [
                    'operator' => 'OR'
                ],
                [
                    'field' => 'tag',
                    'key' => self::CHANNEL_TAG_PREFIX . $channel,
                    'relation' => 'exists'
                ],
                [
                    'field' => 'tag',
                    'key' => self::NOTF_TYPE_TAG_PREFIX . $type,
                    'relation' => '=',
                    'value' => '1'
                ]
            ],
            'contents' => [
                'en' => $text,
                'pt' => $text
            ],
            'data' => [
                'object_id' => $notification->getObjectId(),
                'user_id' => $notification->getUserId(),
                'type' => $notification->getType(),
                'channel' => $notification->getChannel(),
                'datetime' => $notification->getDatetime()
            ]
        ];
        
        return $this->send_request($request, $endpoint, $method);
    }
    
    /**
     * sync channels with tags
     *
     * Sincroniza os canais que o usuário assina com as tags no OneSignal. 
     * Pega todas as tags, remove as dos canais que ele não participa mais
     * 
     * @param  int $user_id  ID do usuário
     * @return array retorno da API
     */
    function sync_user_channels($user_id, $device_push_id = false) {
        
        if (false !== $device_push_id) {
            $device_ids = [$device_push_id];
        } else {
            $device_ids = $this->get_user_device_id($user_id);
            
            if (!$device_ids || empty($device_ids))
                return false;
        }
            
        global $RHSNotifications;
        $user_channels = $RHSNotifications->get_user_channels($user_id);
        // Antes de comparar o array de user_channels com o de remote_tags,
        // vamos adicionar o prefixo para eles ficarem no mesmo formation
        foreach ($user_channels as $k => $ch)
            $user_channels[$k] = self::CHANNEL_TAG_PREFIX . $ch;
            
        foreach ($device_ids as $device_id) {
            // Primeiro pegamos as tags todas que o device já tem cadastradas no OneSignal
            $endpoint = 'players/' . $device_id;
            $method = 'GET';
            $device = $this->send_request([], $endpoint, $method);
            $remote_tags = [];
            
            // Checamos se a resposta veio com sucesso e se existem tags
            if (is_array($device) && isset($device['body'])) {
                $body = json_decode($device['body']);
                
                if (is_object($body) && isset($body->tags)) {
                    if (is_object($body->tags)) {
                        // Pegamos as tags que começam com o prefixo que usamos e colocamos em um Array
                        // que vamos usar para comparar com os canais que eles tem aqui no site
                        foreach ($body->tags as $tag => $v) {
                            if (0 === strpos($tag, self::CHANNEL_TAG_PREFIX))
                                array_push($remote_tags, $tag);
                        }
                    }
                }
            }
            
            // tags que estão no One Signal mas não estão nos canais locais e precisam ser excluídas:
            $to_exclude = array_diff($remote_tags, $user_channels);
            
            // tags que estão em user channels mas não estão no One Signal e precisam ser adicionadas
            $to_include = array_diff($user_channels, $remote_tags);
            
            // Montamos o array que vamos enviar no request
            $tagsToEdit = [];
            foreach ($to_exclude as $tag)
                $tagsToEdit[$tag] = '';
            foreach ($to_include as $tag)
                $tagsToEdit[$tag] = 1;
                
            // Montamos e enviamos o request
            $endpoint = 'players/' . $device_id;
            $method = 'PUT';
            $request = [
                'tags' => $tagsToEdit
            ];

            $this->send_request($request, $endpoint, $method);
        }
    }
    
    /**
     * delete channels from device
     *
     * Deleta todos os canais associados a um device no One Signal
     * 
     * @param  int $user_id  ID do usuário
     * @return array retorno da API
     */
    function delete_user_channels($user_id, $device_push_id = false) {
        
        if (false !== $device_push_id) {
            $device_ids = [$device_push_id];
        } else {
            $device_ids = $this->get_user_device_id($user_id);
            
            if (!$device_ids || empty($device_ids))
                return false;
        }
            
        foreach ($device_ids as $device_id) {
            // Primeiro pegamos as tags todas que o device já tem cadastradas no OneSignal
            $endpoint = 'players/' . $device_id;
            $method = 'GET';
            $device = $this->send_request([], $endpoint, $method);
            $remote_tags = [];
            
            // Checamos se a resposta veio com sucesso e se existem tags
            if (is_array($device) && isset($device['body'])) {
                $body = json_decode($device['body']);
                
                if (is_object($body) && isset($body->tags)) {
                    if (is_object($body->tags)) {
                        // Pegamos as tags que começam com o prefixo que usamos e colocamos em um Array
                        // que vamos usar para comparar com os canais que eles tem aqui no site
                        foreach ($body->tags as $tag => $v) {
                            if (0 === strpos($tag, self::CHANNEL_TAG_PREFIX))
                                array_push($remote_tags, $tag);
                        }
                    }
                }
                
            }
            
            // Montamos o array que vamos enviar no request
            $tagsToEdit = [];
            foreach ($remote_tags as $tag)
                $tagsToEdit[$tag] = '';
                
            // Montamos e enviamos o request
            $endpoint = 'players/' . $device_id;
            $method = 'PUT';
            $request = [
                'tags' => $tagsToEdit
            ];

            $this->send_request($request, $endpoint, $method);
        
        }
        
    }
    
    function add_user_profile_tags($user_id, $device_push_id = false) {
        
        if (false !== $device_push_id) {
            $device_ids = [$device_push_id];
        } else {
            $device_ids = $this->get_user_device_id($user_id);
            
            if (!$device_ids || empty($device_ids))
                return false;
        }
        
        $wp_user = get_userdata($user_id);
        
        if (!$wp_user)
            return false;
        
        $user = new RHSUser($wp_user);

        $id = $user->get_id();
        $first_name = $user->get_first_name();
        $display_name = $user->get_name();
        $uf_id = $user->get_state_id();
        $uf_name = $user->get_state();
        $city_id = $user->get_city_id();
        $city_name = $user->get_city();
        $user_registered = $user->get_date_registered();

        foreach ($device_ids as $device_id) {
            // Montamos e enviamos o request
            $endpoint = 'players/' . $device_id;
            $method = 'PUT';
            $request = [
                'tags' => [
                    'id' => $id,
                    'first_name' => $first_name,
                    'name' => $display_name,
                    'uf_id' => $uf_id,
                    'uf_name' => $uf_name,
                    'city_id' => $city_id,
                    'city_name' => $city_name,
                    'member_since' => $user_registered

                ]
            ];

            $this->send_request($request, $endpoint, $method);
        }
    }
    
    
    function add_user_to_channel($channel, $user_id) {
        return $this->add_remove_user_channel($channel, $user_id);
    }
    
    function delete_user_from_channel($channel, $user_id) {
        return $this->add_remove_user_channel($channel, $user_id, false);
    }
    
    private function add_remove_user_channel($channel, $user_id, $add = true) {
        
        $device_ids = $this->get_user_device_id($user_id);
        
        if (!$device_ids || empty($device_ids))
            return false;
        
        $value = $add ? 1 : '';
        
        foreach ($device_ids as $device_id) {
            $endpoint = 'players/' . $device_id;
            $method = 'PUT';
            $request = [
                'tags' => [self::CHANNEL_TAG_PREFIX . $channel => $value]
            ];
            
            $this->send_request($request, $endpoint, $method);
        }
        
        return true; 
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


