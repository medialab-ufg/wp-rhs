<?php


$query = $this->get_sql('terms');

$this->log('Limpando comentários...');
$wpdb->query("TRUNCATE TABLE {$wpdb->comments};");

$this->log('Importando comentários...');
$wpdb->query($query);

$this->log('Atualizando contagem dos comentários...');
$query = $this->get_sql('comments-totals');
$wpdb->query($query);


