<?php

class RHSNotification {

    const CHANNEL_EVERYONE = 'everyone';
    const CHANNEL_PRIVATE = 'private_for_%s';
    const CHANNEL_COMMENTS = 'comments_in_post_%s';
    const CHANNEL_USER = 'user_%s';
    const CHANNEL_COMMUNITY = 'community_%s';
    const META = '_channels';
    const LASTCHECK = '_notifications_lastcheck';

    private $user_id;
    private $user;
    private $news;

    function __construct( $user_id ) {
        $this->user_id = $user_id;
        $this->verify_database();
    }

    function get_news() {

        if($this->news){
            return $this->news;
        }

        global $wpdb;

        $last_check = $this->get_last_check();
        $channels = $this->get_user_channels();
        $channels = implode(', ',$channels);

        $query = "SELECT * from $wpdb->notifications WHERE datetime > '$lastcheck' AND channel IN ($channels)";

        return $wpdb->get_results();
    }

    function get_news_number(){

        if($this->news){
            return count($this->news);
        }

        global $wpdb;

        $last_check = $this->get_last_check();
        $channels = $this->get_user_channels();
        $channels = implode(', ',$channels);

        $query = "SELECT COUNT(*) from $wpdb->notifications WHERE datetime > '$lastcheck' AND channel IN ($channels)";

        return $wpdb->get_results();

    }

    private function get_user_channels(){

        $channels = get_user_meta( $user_id, self::META );
        $channels = array_merge( $channels, [ self::CHANNEL_EVERYONE, sprintf(self::CHANNEL_PRIVATE, $this->user_id)] );

    }

    /**
     * @return WP_User
     */
    private function get_user_obeject(){

        if($this->user){
            return $this->user;
        }

        return get_userdata($this->user_id);
    }

    function get_last_check(){

        $last_check = get_user_meta( $this->user_id, self::LASTCHECK, true );

        if(!$last_check){
            $this->get_user_obeject()->user_registered;
        }

        return $last_check;
    }

    function set_last_check() {
        if ( ! add_user_meta( $this->user_id, self::LASTCHECK, current_time( 'mysql' ), true ) ) {
            update_post_meta( $this->user_id, self::LASTCHECK, current_time( 'mysql' ) );
        }
    }

    function delete_channel_comunity( $comunity_id ) {
        return $this->delete_channel( self::CHANNEL_COMMUNITY, $user_id );
    }

    function add_channel_comunity( $comunity_id ) {
        return $this->add_channel( self::CHANNEL_COMMUNITY, $user_id );
    }

    function delete_channel_user( $user_id ) {
        return $this->delete_channel( self::CHANNEL_USER, $user_id );
    }

    function add_channel_user( $user_id ) {
        return $this->add_channel( self::CHANNEL_USER, $user_id );
    }

    function delete_channel_comments( $post_id ) {
        return $this->delete_channel( self::CHANNEL_COMMENTS, $post_id );
    }

    function add_channel_comments( $post_id ) {
        return $this->add_channel( self::CHANNEL_COMMENTS, $post_id );
    }

    private function add_channel( $type, $id_for_channel = 0 ) {

        $value = sprintf( $type, $id_for_channel );

        return add_user_meta( $this->user_id, self::META, $value );
    }

    private function delete_channel($type, $id_for_channel = 0 ) {

        $value = sprintf( $type, $id_for_channel );

        return delete_user_meta( $this->user_id, self::META, $value );
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