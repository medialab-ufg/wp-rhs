<?php

class RHSNotifications {

    const LASTCHECK_META = '_notifications_LASTCHECK_META';
    const CHANNELS_META = '_channels';
    
    /**
     * Canais
     */
    const CHANNEL_EVERYONE = 'everyone';
    const CHANNEL_PRIVATE = 'private_for_%s';
    const CHANNEL_COMMENTS = 'comments_in_post_%s';
    const CHANNEL_USER = 'user_%s';
    const CHANNEL_COMMUNITY = 'community_%s'; // se alterar esse valor, alterar no script de importação add-users-to-channels.php a linha onde são limpadas as notificações
    
    const NOTIFICATION_CLASS_PREFIX = 'RHSNotification_';
    const RESULTS_PER_PAGE = 50;

    static $news;
    static $news_num;

    static $instance;
    
    public $table;

    /**
     * RHSNotifications constructor.
     */
    function __construct() {
        
        global $wpdb;
        $this->table = $wpdb->prefix . 'notifications';
        $this->table_notifications_users = $wpdb->prefix . 'notifications_users';
        
        $this->verify_database();
        $this->events_wordpress();
        
        $this->register_notifications();

        if ( ! self::$instance ) {
            $this->events_wordpress();
            self::$instance = true;
        }
    }
    
    static function get_notification_types() {
        
        $types_dir = dirname(__FILE__) . '/types/';
        
        $types = [];
        
        $dir = new DirectoryIterator($types_dir);
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot()) {
                $info = get_file_data($types_dir . $fileinfo->getFilename(), ['description' => 'Description', 'short_description' => 'Short description']);
                
                
                $slug = str_replace('.php', '', $fileinfo->getFilename());
                
                $types[$slug] = [
                    'className' => self::NOTIFICATION_CLASS_PREFIX . $slug,
                    'description' => $info['description'],
                    'short_description' => $info['short_description']
                ];
            }
        }
        
        return apply_filters('rhs_notifications_types', $types);
        
    }
    
    private function register_notifications() {
        $notifications = apply_filters('rhs_registered_notifications', include('registered-notifications.php'));
        foreach ($notifications as $hook => $type) {
            $classname = $type[0];
            $prority = isset($type[1]) ? $type[1] : 10;
            $number_of_arguments = isset($type[2]) ? $type[2] : 1;
            add_action($hook, array(self::NOTIFICATION_CLASS_PREFIX . $classname, 'notify'), $prority, $number_of_arguments);
        }
    }
    
    private function events_wordpress() {
        add_action( 'wp_ajax_rhs_clear_notification', array( &$this, 'ajax_clear_notification' ) );
        add_action( 'user_register', array( &$this, 'add_last_check_for_new_users' ) );
    }
    
    function add_last_check_for_new_users($user_id) {
        $this->set_last_check($user_id);
    }

    function ajax_clear_notification() {
        $this->mark_all_read();

        echo json_encode( true );
        exit;
    }

    /**
     * Adiciona notificação
     *
     * @param int $type
     * @param string $channel
     * @param string $object_id
     * @param int $user_id
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

        $wpdb->insert($this->table, 
            [
                'type' => $type,
                'channel' => $channel,
                'object_id' => $object_id,
                'user_id' => $user_id,
                'datetime' => $datetime
            ]
        );
        
        $className = self::NOTIFICATION_CLASS_PREFIX . $type;
        if (class_exists($className)) {
            $notificationObj = new $className($wpdb->insert_id);
            do_action('rhs_add_notification', $notificationObj);
        }
        
    }
    
    private function update_user_notifications($user_id) {
        
        $channels = $this->get_user_channels($user_id);
        $channels = implode("','", $channels);
        
        $last = $this->get_last_check($user_id);
        
        $query = "INSERT INTO $this->table_notifications_users (user_id, notf_id) 
            SELECT '$user_id', ID FROM $this->table 
            WHERE channel IN ('$channels')
                AND `user_id` <> $user_id
                AND ID > $last
            ";
            
        global $wpdb;
        $wpdb->query($query);
        
        $this->set_last_check($user_id);
        
        
    }

    /**
     * @return RHSNotification[]
     */
    public function get_news($user_id) {

        return $this->get_notifications($user_id, ['onlyUnread' => true]);
        
    }
    
    /**
     * Retorna as notificações de um determinado usuário
     *
     * @param int $user_id
     * @param array $args
     */
    public function get_notifications($user_id, $args = []) {
        global $wpdb;
        
        $args = wp_parse_args( $args );
        $defaults = [
            'from_datetime' => null,
            'paged' => null,
            'onlyCount' => false,
            'onlyUnread' => false,
            'onlyRead' => false
        ];
        $args = array_merge( $defaults, $args );
        
        $this->update_user_notifications($user_id);
        
        $results_per_page = self::RESULTS_PER_PAGE;
        
        if($args['paged'] > 1) {
            $offset = ($args['paged'] - 1) * $results_per_page;
         } else {
            $offset = 0 ;
         }
        
        $query = "FROM {$this->table_notifications_users} u JOIN 
            $this->table n ON n.ID = u.notf_id 
            WHERE u.user_id = $user_id";
        
        if (true === $args['onlyUnread']) {
            $query .= " AND u.read = false";     
        }
        
        if (true === $args['onlyRead']) {
            $query .= " AND u.read = true";     
        }
        
        if (!is_null($args['from_datetime']))
            $query .= " AND datetime >= '".$args['from_datetime']."'";       
        
        if (true === $args['onlyCount']) {
            $query = "SELECT COUNT(*) " . $query;
            return $wpdb->get_var($query);
        }
        
        $query = "SELECT n.* " . $query;
        
        $query .= " ORDER BY datetime DESC";
        $query .= " LIMIT $offset, $results_per_page";
        
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

    function show_notification_pagination($user_id, $paged) {
        $results_per_page = self::RESULTS_PER_PAGE;
        
        $total_results = $this->get_notifications($user_id, ['onlyCount' => true]);
        
        $total_pages = 1;
        $total_pages = ceil($total_results / $results_per_page);

        $big = 999999999;
        $content = paginate_links( array(
            'base'         => str_replace($big, '%#%', get_pagenum_link($big)),
            'format'       => 'page/%#%',
            'prev_text'    => __('&laquo; Anterior'),
            'next_text'    => __('Próxima &raquo;'), 
            'total'        => $total_pages,
            'current'      => $paged,
            'end_size'     => 1,
            'type'         => 'array',
            'mid_size'     => 8,
            'prev_next'    => true,
        ));
        
        if (is_array($content)) {
            $current_page = (get_query_var('rhs_paged') == 0) ? 1 : get_query_var('rhs_paged');
            echo '<ul class="pagination">';
            foreach ($content as $i => $page) {
                echo "<li>$page</li>";
            }
            echo '</ul>';
        }
    }

    public function get_news_number($user_id) {

        global $wpdb;
        
        return $this->get_notifications($user_id, ['onlyUnread' => true, 'onlyCount' => true]);
        
    }

    /**
     * @return array
     */
    public function get_user_channels($user_id) {

        $channels_meta = get_user_meta($user_id, self::CHANNELS_META);
        $channels      = $this->get_user_default_channels($user_id);

        if ( $channels_meta ) {
            $channels = array_merge( $channels, $channels_meta );
        }

        return $channels;
    }
    
    private function get_user_default_channels($user_id) {
        return [ self::CHANNEL_EVERYONE, sprintf( self::CHANNEL_PRIVATE, $user_id ) ];
    }

    function get_last_check($user_id = null) {

        if (is_null($user_id)) {
            $u = wp_get_current_user();
            if (is_object($u) && isset($u->ID))
                $user_id = $u->ID;
        }
        
        if (!$user_id)
            return false;
        
        $last_check = get_user_meta( $user_id, self::LASTCHECK_META, true );

        if ( empty($last_check) && $last_check !== "0" ) {
            // se ainda não tem registro de last check. vamos pegar o valor atual da tab de notificações
            return $this->get_last_notification_id();
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
        
        $last = $this->get_last_notification_id();
        
        if ( ! add_user_meta( $user_id, self::LASTCHECK_META, $last, true ) ) {
            update_user_meta( $user_id, self::LASTCHECK_META, $last );
        }
    }
    
    function mark_all_read($user_id = null) {
        if (is_null($user_id)) {
            $u = wp_get_current_user();
            if (is_object($u) && isset($u->ID))
                $user_id = $u->ID;
        }
        
        if (!$user_id)
            return false;
        
        global $wpdb;
        
        return $wpdb->update($this->table_notifications_users, ['read' => true], ['user_id' => $user_id]);
    }
    
    function get_last_notification_id() {
        global $wpdb;
        $lastID = $wpdb->get_var("SELECT MAX(ID) FROM $this->table");
        return $lastID ? $lastID : 0;
    }

    function add_user_to_channel( $channel, $channel_id = 0, $user_id ) {

        $value = sprintf( $channel, $channel_id );
        $datetime = current_time( 'mysql' );
               
        $add_user = add_user_meta($user_id, self::CHANNELS_META, $value);
        
        if($add_user) {
            do_action('rhs_add_user_to_channel', $value, $user_id);
        }
        return $add_user;
        
    }

    function delete_user_from_channel( $channel, $channel_id = 0, $user_id ) {

        $value = sprintf( $channel, $channel_id );

        $delete_var = delete_user_meta($user_id, self::CHANNELS_META, $value);

        if ($delete_var)
            do_action('rhs_delete_user_from_channel', $value, $user_id);
            
        return $delete_var;
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
        
        $option_name = 'rhs_databssssssase_2_' . get_class($this);
        if ( ! get_option( $option_name ) ) {
            add_option( $option_name, true );
            global $wpdb;
            $createQ = "
                CREATE TABLE IF NOT EXISTS `{$this->table_notifications_users}` (
                    `user_id` INT NOT NULL,
                    `notf_id` INT NOT NULL,
                    `read` BOOL NOT NULL DEFAULT false,
                    PRIMARY KEY(user_id, notf_id)
                );
            ";
            $wpdb->query( $createQ );
            
            // Adicionamos a info de last check para usuários existentes
            
            $wpdb->query("INSERT INTO $wpdb->usermeta (user_id, meta_key, meta_value) SELECT ID, '" . self::LASTCHECK_META . "', 0 FROM $wpdb->users");
            
        }
        
    }

}

global $RHSNotifications;
$RHSNotifications = new RHSNotifications();