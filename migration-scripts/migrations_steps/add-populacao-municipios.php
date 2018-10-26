<?php
/*
 * Fonte: https://concla.ibge.gov.br/classificacoes/por-tema/codigo-de-areas/codigo-de-areas
 * */
$this->log('Importando informação do IBGE sobre população dos municípios');

shell_exec('mysql -u ' . DB_USER . ' -h ' . DB_HOST . ' -p' . DB_PASSWORD . ' ' . DB_NAME . ' < sql/populacao_cidades.sql');