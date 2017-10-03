<?php

/**
 * Created by PhpStorm.
 * User: MediaLab01
 * Date: 14/08/2017
 * Time: 13:34
 */
class RHSNotification {

    public $notificationId;
    public $type;
    public $channel;
    public $object_id;
    public $datetime;
    public $object;
    public $text;
    public $textdate;
    public $image;
    public $object_type;

    /**
     * RHSNotification constructor.
     *
     * Pode receber o ID da notificação ou um objeto já com a notificação completa
     * 
     * @param int|object $notificationId
     */
    function __construct($notification = null) {
        
        if (is_int($notification)) {
            
            global $wpdb, $RHSNotifications;
            $this->setNotificationId($notification);
            $query = "SELECT * FROM {$RHSNotifications->table} WHERE ID = $notification";
            $notification = $wpdb->get_row($query);
            
        }
        
        if (is_object($notification) && isset($notification->type)) {
            $this->setNotificationId($notification->ID);
            $this->setType( $notification->type );
            $this->setChannel( $notification->channel );
            $this->setObjectId( $notification->object_id );
            $this->setDatetime( $notification->datetime );
            $this->setUserId( $notification->user_id );
        } 
        
    }
    
    static public function get_name() {
        return str_replace(RHSNotifications::NOTIFICATION_CLASS_PREFIX, '', get_called_class());
    }
    
    /**
     * @return mixed
     */
    public function getNotificationId() {
        return $this->notificationId;
    }

    /**
     * @param mixed $notificationId
     */
    public function setNotificationId( $notificationId ) {
        $this->notificationId = $notificationId;
    }

    public function getButtons() {
        $type = $this->type;

        switch ($type){
            case 'comments_in_post':
                $buttons[] = (object) array('id' => 'open_' . $type, 'text' => 'Ver Comentário');
                break;
            case 'new_community_post':
                $buttons[] = (object) array('id' => 'open' . $type, 'text' => 'Ver Post');
                break;
            case 'new_post_from_user':
                $buttons[] = (object) array('id' => 'open_post_' . $type, 'text' => 'Ver Post');
                $buttons[] = (object) array('id' => 'open_user_' . $type, 'text' => 'Ver Usuário');
                break;
            case 'post_followed':
                $buttons[] = (object) array('id' => 'open_' . $type, 'text' => 'Ver Post');
                break;
            case 'replied_ticket':
                $buttons[] = (object) array('id' => 'open_' . $type, 'text' => 'Ver Resposta');
                break;
            case 'user_follow_author':
                $buttons[] = (object) array('id' => 'open_' . $type, 'text' => 'Ver Usuário');
                break;
            default:
                $buttons[] = (object) array('id' => 'open_' . $type, 'text' => 'Ver');
                break;
        }
        
        return $buttons;
    }

    /**
     * @return mixed
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType( $type ) {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getChannel() {
        return $this->channel;
    }

    /**
     * @param mixed $channel
     */
    public function setChannel( $channel ) {
        $this->channel = $channel;
    }

    /**
     * @return mixed
     */
    public function getObjectId() {
        return $this->object_id;
    }
    
    function getObjectAsPost() {
        $post_id = $this->getObjectId();
        $post = get_post($post_id);
        if ($post instanceof WP_Post)
            return $post;
        return false;
    }

    /**
     * @param mixed $object_id
     */
    public function setObjectId( $object_id ) {
        $this->object_id = $object_id;
    }

    /**
     * @return mixed
     */
    public function getDatetime() {
        return $this->datetime;
    }

    /**
     * @param mixed $datetime
     */
    public function setDatetime( $datetime ) {
        $this->datetime = $datetime;
    }

    /**
     * @return mixed
     */
    public function getUserId() {
        return $this->user_id;
    }
    
    public function getUser() {
        $user_id = $this->getUserId();
        if (is_numeric($user_id)) {
            $user = get_userdata($user_id);
            if ($user instanceof WP_User) {
                return new RHSUser($user);
            }
        }
        
        return false;
        
    }

    /**
     * @param mixed $datetime
     */
    public function setUserId( $user_id ) {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getText() {

        if($this->text){
            return $this->text;
        }

        return $this->text = $this->text(); // método da classe filha do tipo de notificação
    }

    /**
     * @param mixed $text
     */
    public function setText( $text ) {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getTextdate() {
        if($this->textdate){
            return $this->textdate;
        }

        return $this->textdate = tempoDecorido($this->datetime);
    }

    /**
     * @param mixed $textdate
     */
    public function setTextdate( $textdate ) {
        $this->textdate = $textdate;
    }

    /**
     * @return mixed
     */
    public function getImage() {
        if($this->image){
            return $this->image;
        }

        $this->image = $this->image(); // método da classe filha do tipo de notificacao
        
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage( $image ) {
        $this->image = $image;
    }
    
    public function getTextPush() {        
        if($this->text){
            return $this->text;
        }

        return $this->text = $this->textPush(); // método da classe filha do tipo de notificação
    }
    
    /**
     * Métodos padrões que devem ser sobrescritos pelas classes filhas de tipos de notificação
     */

     public function textPush() {
         return 'Novidades para você na RHS';
     }

}