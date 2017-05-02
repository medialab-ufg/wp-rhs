<?php

global $RHSVote;

$substitutions = [
    '{{votes}}' => $RHSVote->tablename,
    '{{total_meta_key}}' => $RHSVote->total_meta_key
];


$query = $this->get_sql('votes', $substitutions);

$this->log('Limpando votos...');
$wpdb->query("TRUNCATE TABLE {$RHSVote->tablename};");

$this->log('Importando votos...');
$wpdb->query($query);

$this->log('Zerando totais...');
$wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key = '{$RHSVote->total_meta_key}';");

$this->log('Atualizando totais...');
$query = $this->get_sql('votes-totals', $substitutions);
$wpdb->query($query);


