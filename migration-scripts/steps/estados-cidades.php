<?php

// Criamos uma tabela para fazer a transição

$this->log('Limpando metadados');
$wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key = '_uf'");
$wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key = '_municipio'");

$this->log('Criando tabela temporária');
$wpdb->query("DROP TABLE IF EXISTS `migra_cidades_p`");
$wpdb->query(
    
    "
    CREATE TABLE migra_cidades_p (
    
        tid BIGINT,
        parent BIGINT,
        name VARCHAR(255),
        target_id BIGINT,
        parent_ibge BIGINT default 0,
        cod_ibge BIGINT default 0,
        parent_name VARCHAR(255)
    
    )
    "
    

);

// Pega cidades relacionadas com posts no drupal
$this->log('Importando lista de cidades associadas a posts');
$query = $this->get_sql('cidades-posts-get');
$wpdb->query($query);

$this->log('Identificando ID IBGE dos estados');
$query = $this->get_sql('cidades-set-state-ibge', ['{{table}}' => 'migra_cidades_p']);
$wpdb->query($query);

$this->log('Atualizando ID IBGE dos estados para os municipios');
$query = $this->get_sql('cidades-set-parent-ibge', ['{{table}}' => 'migra_cidades_p']);
$wpdb->query($query);




///// LIMPEZA MANUAL

// Remover referencia a posts que não existem na nossa base
$wpdb->query("DELETE FROM migra_cidades_p WHERE target_id NOT IN (SELECT ID FROM $wpdb->posts WHERE post_type = 'post')");






$this->log('Inserindo metados de uf para posts');
$query = $this->get_sql('cidades-add-metadata-uf', ['{{table}}' => 'migra_cidades_p', '{{target}}' => $wpdb->postmeta]);
$wpdb->query($query);

$this->log('Inserindo metados de municipios para posts');
$query = $this->get_sql('cidades-add-metadata-municipios', ['{{table}}' => 'migra_cidades_p', '{{target}}' => $wpdb->postmeta]);
$wpdb->query($query);


$this->log('Cidade e estado dos posts importado. ');
$this->log('IMPORTANTE: Os itens da tabela migra_cidades_p com a coluna cod_ibge = NULL trazem os municíios que não foram encontrados na tabela do IBGE. É preciso corrigir e rodar essa migração de novo');



