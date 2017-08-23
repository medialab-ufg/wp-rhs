<?php

$substitutions = [
    '{{follow_meta}}' => RHSFollow::FOLLOW_KEY,
    '{{followed_meta}}' => RHSFollow::FOLLOWED_KEY,
];

$this->log('Limpando metadados de seguir usuários');
$wpdb->query("DELETE FROM $wpdb->usermeta
	WHERE meta_key IN ('" . RHSFollow::FOLLOW_KEY . "', '" . RHSFollow::FOLLOWED_KEY . "');");

$this->log('Importando informação de seguir dos usuarios...');
$this->query('users-follow', $substitutions);


$this->log('Importando informação de seguidos dos usuarios...');
$this->query('users-followed', $substitutions);
