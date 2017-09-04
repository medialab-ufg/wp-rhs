<?php

$this->log('Limpando metadados de usuários com ID maior que 1');
$wpdb->query("DELETE FROM $wpdb->usermeta 
	WHERE user_id > 1 
		AND meta_key IN ('nickname','first_name','last_name','description','rhs_formation','rhs_interest','_rhs_links','rhs_avatar', 'rich_editing');");

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
$q = "SELECT * FROM `rhs_drupal`.`field_data_field_profile_links` ORDER BY entity_id";

$links = $wpdb->get_results($q);
$cur_user = 0;
$ll = [];
foreach ($links as $link) {
    if ($cur_user != $link->entity_id) {
        if (sizeof($ll) > 0) {
            update_user_meta($cur_user, '_rhs_links', $ll);
            $ll = [];
        }
    }
    $cur_user = $link->entity_id;
    $ll[] = [
        'titulo' => $link->field_profile_links_title,
        'url' => $link->field_profile_links_url
	];
    
}

$this->log('Importando informação de links dos usuarios...');
$this->query('users-meta-links');

$this->log('Importando informação de avatar dos usuarios...');
$this->query('users-meta-avatar');
