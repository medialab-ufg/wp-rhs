<?php

class RHSStats {

    /**
     * Actions
     */
    const ACTION_LOGIN = 'user_login';
    const ACTION_REGISTER = 'user_register';
    const ACTION_FOLLOW_USER = 'user_follow';
    const ACTION_UNFOLLOW_USER = 'user_unfollow';
    const ACTION_FOLLOW_POST = 'post_follow';
    const ACTION_UNFOLLOW_POST = 'post_unfollow';
    const ACTION_POST_PROMOTED = 'post_promoted';
    const ACTION_USER_PROMOTED = 'user_promoted';
    const ACTION_SHARE = 'share';
    
    private $table;

    /**
     * RHSNotifications constructor.
     */
    function __construct() {
        
        global $wpdb;
        $this->table = $wpdb->prefix . 'stats_events';
        
        $this->verify_database();
        
        
        // Hooks que geram eventos
        add_action( 'wp_login', array( &$this, 'login'));
        add_action( 'rhs_register', array( &$this, 'register'));
        add_action( 'rhs_add_user_follow_author', array( &$this, 'user_follow'));
        add_action( 'rhs_delete_user_follow_author', array( &$this, 'user_unfollow'));
        add_action( 'rhs_post_promoted', array( &$this, 'post_promoted'));
        add_action( 'rhs_user_promoted', array( &$this, 'user_promoted'));
        add_action( 'rhs_add_network_data', array( &$this, 'network_data'), 10, 2);
        add_action( 'rhs_add_user_follow_post', array( &$this, 'post_follow'));
        add_action( 'rhs_delete_user_follow_post', array( &$this, 'post_unfollow'));
        
        
        
        
    }
    
    function login($user_login) {
        global $user_ID;
        $this->add_event(self::ACTION_LOGIN, $user_ID, $user_ID);
    }
    
    function register($user_id) {
        $this->add_event(self::ACTION_REGISTER, $user_id, $user_id);
    }
    
    function user_follow($args) {
        $this->add_event(self::ACTION_FOLLOW_USER, $args['author_id'], $args['user_id']);
    }
    
    function user_unfollow($args) {
        $this->add_event(self::ACTION_UNFOLLOW_USER, $args['author_id'], $args['user_id']);
    }
    
    function post_follow($args) {
        $this->add_event(self::ACTION_FOLLOW_POST, $args['post_id'], $args['user_id']);
    }
    
    function post_unfollow($args) {
        $this->add_event(self::ACTION_UNFOLLOW_POST, $args['post_id'], $args['user_id']);
    }
    
    function user_promoted($user_id) {
        $this->add_event(self::ACTION_USER_PROMOTED, $user_id);
    }
    
    function post_promoted($post_id) {
        $this->add_event(self::ACTION_POST_PROMOTED, $post_id);
    }
    
    function network_data($post_id, $type) {
        if ($type == RHSNetwork::META_KEY_VIEW) // não queremos gerar eventos para views
            return;
        $this->add_event(self::ACTION_SHARE, $post_id);
        $this->add_event(self::ACTION_SHARE . '_' . $type, $post_id);
    }
    
    /**
     * Adiciona evento
     *
     * @param string $action
     * @param int $object_id
     * @param int (optional) $user_id
     * @param string (optional) $datetime
     */
    function add_event( $action, $object_id, $user_id = 0, $datetime = null ) {

        if ( $datetime == null ) {
            $datetime = current_time( 'mysql' );
        }

        global $wpdb;

        $query = "
            INSERT INTO {$this->table} (`action`, `object_id`, `user_id`, `datetime`)
            VALUES ('$action', $object_id, $user_id, '$datetime')";

        $wpdb->query( $query );

    }
    
    function get_total_events_by_action($action) {
        global $wpdb;
        return $wpdb->get_var( $wpdb->prepare("SELECT COUNT(ID) FROM $this->table WHERE action = %s", $action) );
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
                    `action` VARCHAR(250) NOT NULL,
                    `object_id` INT(11) NOT NULL default '0',
                    `user_id` INT(11) NOT NULL default '0',
                    `datetime` DATETIME NOT NULL default '0000-00-00 00:00:00'
                );
            ";
            $wpdb->query( $createQ );
        }
    }

}

global $RHSStats;
$RHSStats = new RHSStats();