<?php

$this->log('Limpando metadados de usuários com ID maior que 1');
$wpdb->query("DELETE FROM $wpdb->usermeta WHERE user_id > 1 AND meta_key = 'rhs_capabilities';");

$query = $this->get_sql('users-roles-1');
$this->log('Importando administradores...');
$wpdb->query($query);

$query = $this->get_sql('users-roles-2');
$this->log('Importando editores (excluindo-se os que já são administradores)...');
$wpdb->query($query);

$query = $this->get_sql('users-roles-3');
$this->log('Importando votantes (excluindo-se os que já tem algum papel)...');
$wpdb->query($query);

$query = $this->get_sql('users-roles-4');
$this->log('Importando o restante como autores...');
$wpdb->query($query);
