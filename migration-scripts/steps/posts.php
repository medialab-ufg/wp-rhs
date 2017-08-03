<?php

$this->log('Limpando tabelas de posts e postmeta');
$wpdb->query("TRUNCATE TABLE $wpdb->posts;");
$wpdb->query("TRUNCATE TABLE $wpdb->postmeta;");

$query = $this->get_sql('posts');
$this->log('Importando posts...');
$wpdb->query($query);

$query = $this->get_sql('posts-redes-sociais-facebook');
$this->log('Importando informação das redes sociais de facebook dos posts...');
$wpdb->query($query);

$query = $this->get_sql('posts-redes-sociais-twitter');
$this->log('Importando informação das redes sociais de twitter dos posts...');
$wpdb->query($query);