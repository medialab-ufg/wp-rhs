<?php

$this->log('Taxonomias: Limpando termos...');
$wpdb->query("TRUNCATE TABLE {$wpdb->terms};");
$wpdb->query("TRUNCATE TABLE {$wpdb->term_taxonomy};");
$wpdb->query("TRUNCATE TABLE {$wpdb->term_relationships};");

$this->log('Taxonomias: Importando tags e categorias (passo 1)...');
$this->query('terms');

$this->log('Taxonomias: Importando tags e categorias (passo 2)...');
$this->query('term_taxonomy');

$this->log('Taxonomias: Relacionando termos aos posts');
$this->query('term_relationships');

$this->log('Taxonomias: Atualizando contagem');
$this->query('term_count');
