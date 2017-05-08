<?php


$query = $this->get_sql('terms');

$this->log('Taxonomias: Limpando termos...');
$wpdb->query("TRUNCATE TABLE {$wpdb->terms};");
$wpdb->query("TRUNCATE TABLE {$wpdb->term_taxonomy};");
$wpdb->query("TRUNCATE TABLE {$wpdb->term_relationships};");

$this->log('Taxonomias: Importando tags e categorias (passo 1)...');
$wpdb->query($query);

$this->log('Taxonomias: Importando tags e categorias (passo 2)...');
$query = $this->get_sql('term_taxonomy');
$wpdb->query($query);

$this->log('Taxonomias: Relacionando termos aos posts');
$query = $this->get_sql('term_relationships');
$wpdb->query($query);

$this->log('Taxonomias: Atualizando contagem');
$query = $this->get_sql('term_count');
$wpdb->query($query);
