<?php

$this->log('Limpando metadados de usuários com ID maior que 1');
$wpdb->query("DELETE FROM $wpdb->usermeta WHERE user_id > 1 AND meta_key = 'rhs_capabilities';");

$this->log('Importando administradores...');
$this->query('users-roles-1');

$this->log('Importando editores (excluindo-se os que já são administradores)...');
$this->query('users-roles-2');

$this->log('Importando votantes (excluindo-se os que já tem algum papel)...');
$this->query('users-roles-3');

$this->log('Importando o restante como autores...');
$this->query('users-roles-4');