<?php


$query = $this->get_sql('posts');

$this->log('Limpando tabelas de posts e postmeta');
$wpdb->query("TRUNCATE TABLE $wpdb->posts;");
$wpdb->query("TRUNCATE TABLE $wpdb->postmeta;");

$this->log('Importando posts...');
$wpdb->query($query);

$query = $this->get_sql('posts-meta-date');
$this->log('Importando informação de data do post...');
$wpdb->query($query);
