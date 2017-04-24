<?php


$query = file_get_contents('posts.sql');

// TODO: find & replace db prefix

$this->log('Limpando tabelas de posts e postmeta');
$wpdb->query("TRUNCATE TABLE $wpdb->posts;");
$wpdb->query("TRUNCATE TABLE $wpdb->postmeta;");

$this->log('Iniciando Importação');
$wpdb->query($query);


