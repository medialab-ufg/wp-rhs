<?php

global $RHSVote;
global $RHSPosts;

$substitutions = [
    '{{votes}}' => $RHSVote->tablename,
    '{{total_meta_key}}' => $RHSVote->total_meta_key,
    '{{order_meta_key}}' => RHSPosts::META_DATE_ORDER,
    '{{meta_publish_key}}' => RHSVote::META_PUBLISH
];



$this->log('Limpando votos...');
$wpdb->query("TRUNCATE TABLE {$RHSVote->tablename};");

$this->log('Zerando totais...');
$wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key = '{$RHSVote->total_meta_key}';");

$this->log('Zerando data de último voto dos posts...');
$wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key = '" . RHSPosts::META_DATE_ORDER . "';");

$this->log('Zerando informação de posts promovidos...');
$wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key = '" . RHSVote::META_PUBLISH . "';");

$this->log('Importando votos...');
$this->query('votes', $substitutions);

$this->log('Atualizando totais...');
$this->query('votes-totals', $substitutions);

$this->log('Atualizando totais por usuários...');
$this->query('votes-totals-users', $substitutions);

$this->log('Importando informação de data do último voto...');
$this->query('posts-meta-date', $substitutions);

$this->log('Atualizando status dos posts pelo voto...');
$this->query('votes-posts-status', $substitutions);

$this->log('Atualizando meta dos posts publicados...');
$this->get_sql('votes-posts-meta', $substitutions);
