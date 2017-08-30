<?php

class RHSNotifications {

    const META = '_channels';
    const LASTCHECK = '_notifications_lastcheck';

    /**
     * Canais
     */
    const CHANNEL_EVERYONE = 'everyone';
    const CHANNEL_PRIVATE = 'private_for_%s';
    const CHANNEL_COMMENTS = 'comments_in_post_%s';
    const CHANNEL_USER = 'user_%s';
    const CHANNEL_COMMUNITY = 'community_%s';
    
    const NOTIFICATION_CLASS_PREFIX = 'RHSNotification_';
    /**
     * Tipos
     */
    //const NEW_POST = 'new_post_from_user';
    //const POST_PROMOTED = 'post_promoted';
    //const COMMUNITY_POST = 'community_post';

    static $news;
    static $news_num;

    static $instance;
    
    private $table;

    /**
     * RHSNotifications constructor.
     */
    function __construct() {
        
        global $wpdb;
        $this->table = $wpdb->prefix . 'notifications';
        
        $this->verify_database();
        $this->events_wordpress();
        
        $this->register_notifications();

        if ( ! self::$instance ) {
            $this->events_wordpress();
            self::$instance = true;
        }
    }
    
    private function register_notifications() {
        $notifications = apply_filters('rhs_registered_notifications', include('registered-notifications.php'));
        foreach ($notifications as $hook => $type) {
            add_action($hook, array(self::NOTIFICATION_CLASS_PREFIX . $type, 'notify'));
        }
    }
    
    private function events_wordpress() {
        add_action( 'wp_ajax_rhs_clear_notification', array( &$this, 'ajax_clear_notification' ) );
    }

    function ajax_clear_notification() {
        $this->set_last_check();

        echo json_encode( true );
        exit;
    }

    /**
     * Adiciona notificação
     *
     * @param int $type
     * @param string $channel
     * @param string $object_id
     * @param null $datetime
     */
    function add_notification( $channel, $channel_id = null, $type, $object_id, $user_id = 0, $datetime = null ) {

        if ( $datetime == null ) {
            $datetime = current_time( 'mysql' );
        }

        if ( ! $channel_id ) {
            $channel = self::CHANNEL_EVERYONE;
        } else {
            $channel = sprintf( $channel, $channel_id );
        }

        global $wpdb;

        $query = "
            INSERT INTO {$this->table} (`type`, `channel`, `object_id`, `user_id`, `datetime`)
            VALUES ('$type', '$channel', '$object_id', $user_id, '$datetime')";

        $wpdb->query( $query );

    }

    /**
     * @return RHSNotification[]
     */
    public function get_news($user_id) {

        $last_check = self::get_last_check($user_id);
        
        return $this->get_notifications($user_id, $last_check);
        
    }
    
    public function get_notifications($user_id, $from_datetime = null) {
        
        global $wpdb;
        
        $channels   = self::get_user_channels($user_id);
        $channels   = implode( "', '", $channels );
        
        $query = "SELECT * FROM {$this->table} WHERE channel IN ('$channels') AND `user_id` <> $user_id";
        
        if (!is_null($from_datetime))
            $query .= " AND datetime >= '$from_datetime'";
        
        $query .= " ORDER BY datetime DESC";
        
        $notifications = array();

        foreach ( $wpdb->get_results($query) as $result ) {

            $className = self::NOTIFICATION_CLASS_PREFIX . $result->type;
            if (class_exists($className)) {
                $notificationsObj = new $className($result);
            }

            $notifications[] = $notificationsObj;
        }

        return $notifications;
    }

    public function get_news_number($user_id) {

        global $wpdb;

        $last_check = self::get_last_check($user_id);
        $channels   = self::get_user_channels($user_id);
        $channels   = implode( "', '", $channels );

        $query = "SELECT COUNT(*) AS num FROM {$this->table} WHERE `datetime` >= '$last_check' AND `channel` IN ('$channels') AND `user_id` <> $user_id";

        return current( $wpdb->get_results( $query ) )->num;

    }

    /**
     * @return array
     */
    static function get_user_channels($user_id) {

        $channels_meta = get_user_meta( $user_id, self::META );
        $channels      = [ self::CHANNEL_EVERYONE, sprintf( self::CHANNEL_PRIVATE, $user_id ) ];

        if ( $channels_meta ) {
            $channels = array_merge( $channels, $channels_meta );
        }

        return $channels;
    }

    static function get_last_check($user_id = null) {

        if (is_null($user_id)) {
            $u = wp_get_current_user();
            if (is_object($u) && isset($u->ID))
                $user_id = $u->ID;
        }
        
        if (!$user_id)
            return false;
        
        $last_check = get_user_meta( $user_id, self::LASTCHECK, true );

        if ( ! $last_check ) {
            return get_userdata( $user_id )->user_registered;
        }

        return $last_check;
    }

    function set_last_check($user_id = null) {
        if (is_null($user_id)) {
            $u = wp_get_current_user();
            if (is_object($u) && isset($u->ID))
                $user_id = $u->ID;
        }
        
        if (!$user_id)
            return false;
            
        if ( ! add_user_meta( $user_id, self::LASTCHECK, current_time( 'mysql' ), true ) ) {
            update_user_meta( $user_id, self::LASTCHECK, current_time( 'mysql' ) );
        }
    }

    function add_user_to_channel( $channel, $channel_id = 0, $user_id ) {

        $value = sprintf( $channel, $channel_id );

        return add_user_meta( $user_id, self::META, $value );
    }

    function delete_user_from_channel( $channel, $channel_id = 0, $user_id ) {

        $value = sprintf( $channel, $channel_id );

        return delete_user_meta( $user_id, self::META, $value );
    }

    /**
     * Verifica se existe tabela, se não, á insere
     */
    private function verify_database() {
        $option_name = 'rhs_database_' . get_class($this);
        if ( ! get_option( $option_name ) ) {
            add_option( $option_name, true );
            global $wpdb;
            $createQ = "
                CREATE TABLE IF NOT EXISTS `{$this->table}` (
                    `ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `type` VARCHAR(250) NOT NULL,
                    `channel` VARCHAR(250) NOT NULL,
                    `object_id` INT(11) NOT NULL default '0',
                    `user_id` INT(11) NOT NULL default '0',
                    `datetime` DATETIME NOT NULL default '0000-00-00 00:00:00'
                );
            ";
            $wpdb->query( $createQ );
        }
    }

}

global $RHSNotifications;
$RHSNotifications = new RHSNotifications();