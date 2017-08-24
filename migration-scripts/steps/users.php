<?php

$this->log('Limpando usuários com ID maior que 1');
$wpdb->query("DELETE FROM $wpdb->users WHERE ID > 1;");

$this->log('Importando usuarios...');
$this->query('users');