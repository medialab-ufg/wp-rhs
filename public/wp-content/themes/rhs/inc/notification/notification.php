<?php

/**
 * Created by PhpStorm.
 * User: MediaLab01
 * Date: 14/08/2017
 * Time: 13:34
 */
class RHSNotification {

    private $notificationId;
    private $type;
    private $channel;
    private $object_id;
    private $datetime;
    private $object;
    private $text;
    private $textdate;
    private $image;
    private $object_type;

    /**
     * RHSNotification constructor.
     *
     * @param $notificationId
     */
    function __construct($notificationId) {
        $this->notificationId = $notificationId;
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

    /**
     * @return mixed
     */
    public function getType() {
        $this->getObject();

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
        $this->getObject();

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
        $this->getObject();
        return $this->object_id;
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
        $this->getObject();

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
    public function getText() {

        if($this->text){
            return $this->text;
        }

        $type = $this->getObjectType();

        if(is_object($type)){
            return $this->text = $type->text($this);
        }

        return $this->text;
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
    private function getObject() {
        if($this->object){
            return;
        }

        global $wpdb;

        $query = "SELECT * FROM ".$wpdb->prefix."notifications WHERE ID = ".$this->notificationId;

        $object = $wpdb->get_results($query);

        $this->object = true;

        if(!$object){
            return;
        }

        $this->type = $object['type'];
        $this->channel = $object['channel'];
        $this->objectId = $object['object_id'];
        $this->datetime = $object['datetime'];
    }

    /**
     * @param mixed $object
     */
    public function setObject( $object ) {
        $this->object = $object;
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

        $type = $this->getObjectType();

        if(is_object($type)){
            return $this->image = $type->image($this);
        }

        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage( $image ) {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getObjectType() {

        if($this->object_type){
            return $this->object_type;
        }

        if(class_exists($this->type)){
            $this->object_type = new $this->type();
        }

        return $this->object_type;
    }

}