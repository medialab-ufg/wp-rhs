<?php

$this->log('Limpando metadados de usuários com ID maior que 1');
$wpdb->query("DELETE FROM $wpdb->usermeta 
	WHERE user_id > 1 
		AND meta_key IN ('nickname','first_name','last_name','description','rhs_formation','rhs_interest','rhs_links','rhs_avatar', 'rich_editing');");

$this->log('Adicionando metadado de rich editing...');
$this->query('users-meta-rich-editing');

$this->log('Importando informação de apelido dos usuarios...');
$this->query('users-meta-nickname');

$this->log('Importando informação de primeiro nome dos usuarios...');
$this->query('users-meta-first_name');

$this->log('Importando informação de último dos usuarios...');
$this->query('users-meta-last_name');

$this->log('Importando informação de descrição dos usuarios...');
$this->query('users-meta-description');

$this->log('Importando informação de formação dos usuarios...');
$this->query('users-meta-formation');

$this->log('Importando informação de interesse dos usuarios...');
$this->query('users-meta-interest');

$this->log('Importando informação de links dos usuarios...');
$this->query('users-meta-links');

$this->log('Importando informação de avatar dos usuarios...');
$this->query('users-meta-avatar');
