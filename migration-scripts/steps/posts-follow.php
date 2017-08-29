<?php

$substitutions = [
    '{{follow_post_meta}}' => RHSFollowPost::FOLLOW_POST_KEY,
];

$this->log('Limpando metadados de seguir usuários');
$wpdb->query("DELETE FROM $wpdb->usermeta
	WHERE meta_key = '" . RHSFollowPost::FOLLOW_POST_KEY . "';");

$this->log('Importando informação de seguidores de post');
$this->query('posts-follow', $substitutions);
