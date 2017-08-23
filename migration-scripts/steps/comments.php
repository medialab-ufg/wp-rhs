<?php

$this->log('Limpando comentários...');
$wpdb->query("TRUNCATE TABLE {$wpdb->comments};");

$this->log('Importando comentários...');
$this->query('comments');

$this->log('Atualizando contagem dos comentários...');
$this->query('comments-totals');