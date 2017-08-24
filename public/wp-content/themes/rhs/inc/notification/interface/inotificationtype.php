<?php

interface INotificationType {

    public function get_name() {
        return get_class($this);
    }
    
    public function text(RHSNotification $news);

    public function notify($args);

    public function image(RHSNotification $news);

}