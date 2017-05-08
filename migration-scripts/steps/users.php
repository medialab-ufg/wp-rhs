<?php

$this->log('Limpando usuários com ID maior que 1');
$wpdb->query("DELETE FROM $wpdb->users WHERE ID > 1;");

$query = $this->get_sql('users');
$this->log('Importando usuarios...');
$wpdb->query($query);



