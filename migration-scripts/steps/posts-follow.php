<?php

$substitutions = [
    '{{follow_post_meta}}' => RHSFollowPost::FOLLOW_POST_KEY,
    '{{followed_post_meta}}' => RHSFollowPost::FOLLOWED_POST_KEY
];

$this->log('Limpando metadados de posts que usuários seguem');
$wpdb->query("DELETE FROM $wpdb->postmeta
    WHERE meta_key = '" . RHSFollowPost::FOLLOWED_POST_KEY . "';");
    
$this->log('Limpando metadados de usuários que seguem post');
$wpdb->query("DELETE FROM $wpdb->usermeta
    WHERE meta_key = '" . RHSFollowPost::FOLLOW_POST_KEY . "';");

$this->log('Importando metadados de posts seguidos por usuários');
$this->query('posts-follow', $substitutions);

$this->log('Importando metadados de usuários que seguem post');
$this->query('posts-followed', $substitutions);
