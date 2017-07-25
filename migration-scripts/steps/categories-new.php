<?php

$this->log('Limpando categorias do ticket com ID maior que 1');

$categories = $wpdb->get_results("SELECT * FROM $wpdb->term_taxonomy WHERE taxonomy = '".RHSTicket::TAXONOMY."';");

foreach ($categories as $category){

    $wpdb->query("DELETE FROM $wpdb->term_taxonomy WHERE term_taxonomy_id = '$category->term_taxonomy_id';");
    $wpdb->query("DELETE FROM $wpdb->terms WHERE term_id = '$category->term_id';");
    $wpdb->query("DELETE FROM $wpdb->options WHERE option_name = 'tickets-category_children';");
}


$categories = array(
    'Política Nacional de Humanização (PNH)' => array(
        'Apoio',
        'Informação',
        'Material de referência',
        'Produção de conhecimento',
        'Reportagem',
        'Convite',
        'Outros'
    ),
    'Rede HumanizaSUS (RHS)' => array(
        'Colaboração',
        'Elogio',
        'Informação',
        'Críticas e sugestões',
        'Convite',
        'Outros'
    ),
    'Suporte Técnico' => array(
        'Acesso negado',
        'Busca de conteúdo',
        'Cadastro',
        'Cadastro de ações',
        'Comentário',
        'Comunidade',
        'Desativar blog',
        'Fila de votação',
        'Login',
        'Perfil',
        'Post',
        'Sugestão de envio de post',
        'Transmissões online',
        'Outros'
    ),
    'Sistema Único de Saúde (SUS)' => array(
        'Atendimento',
        'Informação',
        'Material de referência',
        'Ouvidoria',
        'Produção de conhecimento',
        'SUS que dá certo',
        'Outros'
    ),
    'Cursos/formação' => array(
        'Certificado',
        'Contato com formador',
        'Divulgação',
        'Elogio',
        'Informação',
        'Inscrição',
        'Logística',
        'Material de referência',
        'Outros'
    ),
    'Outros' => array(
        'Pedido de ajuda',
        'Spam',
        'Outros'
    )
);

$categories_ids = array();

// Insere as categorias pais
foreach ($categories as $first_name => $seconds){

    $data =  array( 'name' => $first_name, 'slug' => $first_name );
    $wpdb->insert($wpdb->terms, $data);

    $parent = $wpdb->insert_id;
    $categories_ids[$parent] = array();

    $data = array('term_id' => $wpdb->insert_id, 'taxonomy' => RHSTicket::TAXONOMY);
    $wpdb->insert($wpdb->term_taxonomy, $data);

    // Insere as categorias filhas
    foreach ($seconds as $second){

        $data =  array( 'name' => $second, 'slug' => $second );
        $wpdb->insert($wpdb->terms, $data);

        $children = $wpdb->insert_id;
        $categories_ids[$parent][] = $children;

        $data = array('term_id' => $wpdb->insert_id, 'taxonomy' => RHSTicket::TAXONOMY, 'parent' => $parent);
        $wpdb->insert($wpdb->term_taxonomy, $data);
    }
}

// Insere o controle de categorias pais e filhas por post type
$data =  array( 'option_name' => 'tickets-category_children', 'option_value' => serialize($categories_ids));
$wpdb->insert($wpdb->options, $data);