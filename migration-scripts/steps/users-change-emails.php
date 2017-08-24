<?php

/**
 * 
 * No ambiente de testes, mudamos todos os emails dos usuários comuns adicionando um "rhsteste-" no começo
 * para evitar que enviemos email para eles acidentalmente
 */ 

if (defined('WP_DEBUG') && true === WP_DEBUG) {
    $this->log('Modificando email dos usuários comuns no ambiente de desenvolvimento...');
    $this->query('users-change-emails');
} else {
    $this->log('WP DEBUG desligado, pulando este passo');
}