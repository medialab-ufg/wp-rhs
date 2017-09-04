<?php

$substitutions = [
    '{{META_KEY}}' =>               RHSNotifications::META,
    '{{CHANNEL_COMMENTS}}' =>       RHSNotifications::CHANNEL_COMMENTS,
    '{{CHANNEL_USER}}' =>           RHSNotifications::CHANNEL_USER,
    '{{CHANNEL_COMMUNITY}}' =>      RHSNotifications::CHANNEL_COMMUNITY,
    '{{follow_meta}}' =>            RHSFollow::FOLLOW_KEY,
    '{{follow_post_meta}}' =>       RHSFollowPost::FOLLOW_POST_KEY,
];

$this->log('Limpando canais de notificação dos usuários');
$wpdb->query("DELETE FROM $wpdb->usermeta WHERE meta_key = '".RHSNotifications::META."' AND meta_value NOT LIKE 'community_%';");

$this->log('Adicionando os autores dos posts aos canais de seus posts...');
$this->query('channels-authors', $substitutions);

$this->log('Adicionando os seguidores de usuários aos canais dos usuários que seguem');
$this->query('channels-user-followers', $substitutions);

$this->log('Adicionando os seguidores de posts aos canais dos posts que seguem');
$this->query('channels-posts-followers', $substitutions);

$this->log('Adicionando os comentaristas de posts aos canais dos posts que comentaram');
$this->query('channels-posts-commenters', $substitutions);