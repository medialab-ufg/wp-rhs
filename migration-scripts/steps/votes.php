<?php

global $RHSVote;

$substitutions = [
    '{{votes}}' => $RHSVote->tablename,
    '{{total_meta_key}}' => $RHSVote->total_meta_key,
    '{{order_meta_key}}' => $RHSPosts->META_DATE_ORDER
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

$this->log('Zerando data de último voto dos posts...');
$wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key = '{$RHSPosts->META_DATE_ORDER}';");

$query = $this->get_sql('posts-meta-date', $substitutions);
$this->log('Importando informação de data do último voto...');
$wpdb->query($query);

