<?php

$categories = array(
    'Formação/cursos' => array(
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
    ),
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
    )
);

foreach ($categories as $category){




}

$my_cat = array(
    'cat_name' => 'My Category',
    'category_parent' => '',
    'taxonomy' => RHSTicket::TAXONOMY);

// Create the category
$my_cat_id = wp_insert_category($my_cat);


$query = $this->get_sql('comments');

$this->log('Limpando comentários...');
$wpdb->query("TRUNCATE TABLE {$wpdb->comments};");

$this->log('Importando comentários...');
$wpdb->query($query);

$this->log('Atualizando contagem dos comentários...');
$query = $this->get_sql('comments-totals');
$wpdb->query($query);


