<?php

class RHSMessage {

    protected $key;

    function getKey(){

        if($this->key){
            return $this->key;
        }

        return $this->key = sha1($_SERVER['REMOTE_ADDR']);
    }

    function set_alert($alert){

        $_SESSION["alert_{$this->getKey()}"] = $alert;
    }

    function get_alert(){

        if(!empty($_SESSION["alert_{$this->getKey()}"])){
            return $_SESSION["alert_{$this->getKey()}"];
        }

        return '';
    }

    function clear_alert(){

        unset($_SESSION["alert_{$this->getKey()}"]);
    }

    function set_messages($messages, $clear = false, $type = ''){

        $class_name = get_class($this);

        if($clear){
            $_SESSION["messages_{$class_name}_{$this->getKey()}"] = $messages;
            return;
        }

        if(!isset($_SESSION["messages_{$class_name}_{$this->getKey()}"])){

            if($type){
                $_SESSION["messages_{$class_name}_{$this->getKey()}"][$type][] = $messages;
                return;
            }


            $_SESSION["messages_{$class_name}_{$this->getKey()}"] = array($messages);
            return;
        }

        $_SESSION["messages_{$class_name}_{$this->getKey()}"][] = $messages;
    }

    function messages(){

        $class_name = get_class($this);

        if(empty($_SESSION["messages_{$class_name}_{$this->getKey()}"])){
            return array();
        }

        return $_SESSION["messages_{$class_name}_{$this->getKey()}"];

    }

    function clear_messages() {
        $class_name = get_class($this);

        if (isset($_SESSION)) {
            unset($_SESSION["messages_{$class_name}_{$this->getKey()}"]);
        }
    }


}