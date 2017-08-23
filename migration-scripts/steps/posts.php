<?php

$this->log('Limpando tabelas de posts e postmeta');
$wpdb->query("TRUNCATE TABLE $wpdb->posts;");
$wpdb->query("TRUNCATE TABLE $wpdb->postmeta;");

$this->log('Importando posts...');
$this->query('posts');

$this->log('Importando informação das redes sociais de facebook dos posts...');
$this->query('posts-redes-sociais-facebook');

$this->log('Importando informação das redes sociais de twitter dos posts...');
$this->query('posts-redes-sociais-twitter');

$this->log('Importando carrossel...');
$this->query('posts-carrossel');

