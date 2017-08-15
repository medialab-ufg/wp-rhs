<?php

interface INotificationType {

    public function text(RHSNotification $news);

    public function notify($args);

    public function image(RHSNotification $news);

}