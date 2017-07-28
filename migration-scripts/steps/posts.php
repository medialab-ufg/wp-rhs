<?php


$query = $this->get_sql('posts');

$this->log('Limpando tabelas de posts e postmeta');
$wpdb->query("TRUNCATE TABLE $wpdb->posts;");
$wpdb->query("TRUNCATE TABLE $wpdb->postmeta;");

$this->log('Importando posts...');
$wpdb->query($query);
