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

    /**
     * Tipos
     */
    //const NEW_POST = 'new_post_from_user';
    //const POST_PROMOTED = 'post_promoted';
    //const COMMUNITY_POST = 'community_post';

    static $news;
    static $news_num;

    static $instance;

    /**
     * RHSNotifications constructor.
     */
    function __construct() {
        $this->verify_database();
        $this->events_wordpress();

        if ( ! self::$instance ) {
            $this->events_wordpress();
            self::$instance = true;
        }
    }
    
    private function text_per_type() {

        return array(
            self::NEW_POST       => '<a href="%s"><strong>%s</strong></a> criou um novo post <a href="%s"><strong>%s</strong></a>',
            self::POST_PROMOTED  => 'Seu post <a href="%s"><strong>%s</strong></a> foi promovido.',
            self::COMMUNITY_POST => 'Foi criado um novo post <a href="%s"><strong>%s</strong></a>, na comunidade <a href="%s"><strong>%s</strong></a>',
        );

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
     * @param $comment_id
     * @param $post_id
     */
    function add_notification_comments_in_post( $comment_id, $post_id ) {
        $this->add_notification( self::CHANNEL_COMMENTS, $post_id, self::COMMUNITY_POST, $comment_id );
    }

    /**
     * @param $community_id
     * @param $post_id
     */
    function add_notification_community_post( $community_id, $post_id ) {
        $this->add_notification( self::CHANNEL_COMMUNITY, $community_id, self::COMMUNITY_POST, $post_id );
    }

    /**
     * Adiciona notificação
     *
     * @param int $type
     * @param string $channel
     * @param string $object_id
     * @param null $datetime
     */
    function add_notification( $channel, $channel_id = null, $type, $object_id, $datetime = null ) {

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
            INSERT INTO " . $wpdb->prefix . "notifications  (`type`, `channel`, `object_id`, `datetime`)
            VALUES ('$type', '$channel', '$object_id', '$datetime')";

        $wpdb->query( $query );

    }

    /**
     * @return RHSNotification[]
     */
    static function get_news($user_id) {

        if ( self::$news[$user_id] ) {
            return self::$news[$user_id];
        }

        global $wpdb;

        $last_check = self::get_last_check($user_id);
        $channels   = self::get_user_channels($user_id);
        $channels   = implode( "', '", $channels );

        $query = "SELECT * FROM " . $wpdb->prefix . "notifications WHERE datetime > '$last_check' AND channel IN ('$channels')";

        $notifications = array();

        foreach ( $wpdb->get_results($query) as $results ) {

            $notificationsObj = new RHSNotification( $results->ID);
            $notificationsObj->setType( $results->type );
            $notificationsObj->setChannel( $results->channel );
            $notificationsObj->setObjectId( $results->object_id );
            $notificationsObj->setDatetime( $results->datetime );
            $notificationsObj->setObject(true);

            $notifications[] = $notificationsObj;
        }

        return self::$news[$user_id] = $notifications;
    }

    static function get_news_number($user_id) {

        if ( self::$news[$user_id] ) {
            return count( self::$news[$user_id] );
        }

        if ( self::$news_num[$user_id] ) {
            return self::$news_num[$user_id];
        }

        global $wpdb;

        $last_check = self::get_last_check($user_id);
        $channels   = self::get_user_channels($user_id);
        $channels   = implode( "', '", $channels );

        $query = "SELECT COUNT(*) AS num FROM " . $wpdb->prefix . "notifications WHERE `datetime` > '$last_check' AND `channel` IN ('$channels')";

        return self::$news_num[$user_id] = current( $wpdb->get_results( $query ) )->num;

    }

    /**
     * @return array
     */
    private static function get_user_channels($user_id) {

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

    function add_user_to_channel( $type, $id_for_channel = 0, $user_id ) {

        add_action( 'rhs_notify_add_user_to_channel', array( &$this, 'add_user_to_channel' ), 10, 3 );

        $value = sprintf( $type, $id_for_channel );

        return add_user_meta( $user_id, self::META, $value );
    }

    private function delete_user_to_channel( $channel, $id_for_channel = 0, $user_id ) {

        $value = sprintf( $type, $id_for_channel );

        return delete_user_meta( $user_id, self::META, $value );
    }

    /**
     * Verifica se existe tabela, se não, á insere
     */
    private function verify_database() {
        $option_name = 'database_' . get_class();
        if ( ! get_option( $option_name ) ) {
            add_option( $option_name, true );

            $createQ = "
                CREATE TABLE IF NOT EXISTS `rhs_notification` (
                    `ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `type` VARCHAR(250) NOT NULL,
                    `channel` VARCHAR(250) NOT NULL,
                    `object_id` INT(11) NOT NULL default '0',
                    `datetime` DATETIME NOT NULL default '0000-00-00 00:00:00'
                );
            ";
            global $wpdb;
            $wpdb->query( $createQ );

        }
    }

}

global $RHSNotifications;
$RHSNotifications = new RHSNotifications();