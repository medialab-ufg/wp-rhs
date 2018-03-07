<?php
$substitutions = [
    '{{META_KEY_FACEBOOK}}' =>      RHSNetwork::META_KEY_FACEBOOK,
    '{{META_KEY_TWITTER}}' =>       RHSNetwork::META_KEY_TWITTER,
    '{{META_KEY_WHATSAPP}}' =>      RHSNetwork::META_KEY_WHATSAPP,
    '{{META_KEY_VIEW}}' =>          RHSNetwork::META_KEY_VIEW,
    '{{META_KEY_PRINT}}' =>         RHSNetwork::META_KEY_PRINT,
    '{{META_KEY_TOTAL_SHARES}}' =>  RHSNetwork::META_KEY_TOTAL_SHARES
];

$this->log('Limpando tabelas de posts e postmeta');
$wpdb->query("TRUNCATE TABLE $wpdb->posts;");
$wpdb->query("TRUNCATE TABLE $wpdb->postmeta;");

$this->log('Importando posts...');
$this->query('posts');

$this->log('Importando informação das redes sociais de facebook dos posts...');
$this->query('posts-redes-sociais-facebook', $substitutions);

$this->log('Importando informação das redes sociais de twitter dos posts...');
$this->query('posts-redes-sociais-twitter', $substitutions);

$this->log('Importando informação do total de compartilhamento dos posts...');
$this->log('NOTA: Como não estamos importando gplus e linkedin, é possível que esse total não bata com a soma dos demais');
$this->query('posts-redes-sociais-total', $substitutions);

$this->log('Importando informação de visualização dos posts...');
$this->query('posts-views', $substitutions);

$query = $this->get_sql('posts-carrossel');
$this->log('Importando carrossel...');
$this->query('posts-carrossel', $substitutions);