<?php

/**
 * 
 * No ambiente de testes, mudamos todos os emails dos usuários comuns adicionando um "rhsteste-" no começo
 * para evitar que enviemos email para eles acidentalmente
 */ 

if (defined('WP_DEBUG') && true === WP_DEBUG) {

    $query = $this->get_sql('users-change-emails');
    $this->log('Modificando email dos usuários comuns no ambiente de desenvolvimento...');
    $wpdb->query($query);

} else {
    $this->log('WP DEBUG desligado, pulando este passo');
}





