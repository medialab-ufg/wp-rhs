<?php

global $RHSComunities;

$this->log( 'Comunidades - Limpando informacoes...' );

$comunities = $RHSComunities->get_comunities(true);
$qtd = count( $comunities );
$this->log( 'Comunidades - '.$qtd . ' no total para serem excluidas' );

foreach ( $comunities as $key => $comunity ) {
    $this->log( 'Comunidades - (' . ( $key + 1 ) . ' de ' . $qtd .') Limpando...' );
    wp_delete_term( $comunity->term_id, RHSComunities::TAXONOMY );
}

$this->log( 'Comunidades - Limpeza finalizada...' );

$this->log( '====================================================' );

$this->log( 'Comunidades - Importando comunidades, membros e posts...' );

$query      = $this->get_sql( 'comunities' );
$comunities = $wpdb->get_results( $query );

$this->log( 'Comunidades - '.count( $comunities ) . ' no total para serem incluidas' );

$qtd = count( $comunities );
foreach ( $comunities as $key => $comunity ) {

    $term = wp_insert_term( $comunity->name, RHSComunities::TAXONOMY );

    if($term instanceof WP_Error){
        continue;
    }

    $this->log( 'Comunidades - (' . ( $key + 1 ) . ' de ' . $qtd .') Incluindo '.$comunity->name );

    $term = get_term( $term['term_id'], RHSComunities::TAXONOMY );

    add_term_meta( $term->term_id, RHSComunities::TYPE, 'private', true );

    $query   = $this->get_sql( 'comunities-members', array( '{{comunity_id}}' => $comunity->term_id ) );
    $members = $wpdb->get_results( $query );

    $this->log( 'Comunidades - (' . ( $key + 1 ) . ' de ' . $qtd .') Incluindo '.count($members).' membros.' );

    foreach ( $members as $member ) {
        RHSComunities::add_user_comunity($term->term_id, $member->user_id);
        RHSComunities::add_user_comunity_follow($term->term_id, $member->user_id);
        // Há um hook nessa função que já adiciona o usuário ao canal de notificações da comunidade

        if($member->moderate){
            RHSComunities::add_user_comunity_moderate($term->term_id, $member->user_id);
        }
    }

    $query = $this->get_sql( 'comunities-posts', array( '{{comunity_id}}' => $comunity->term_id ) );
    $posts = $wpdb->get_results( $query );

    $this->log( 'Comunidades - (' . ( $key + 1 ) . ' de ' . $qtd .') Incluindo '.count($posts).' posts.' );

    foreach ( $posts as $post ) {
        wp_set_post_terms( $post->post_id, $term->name, 'comunity-category' );

        if($post->access){
            wp_update_post( array('ID' => $post->post_id, 'post_status' => 'private'), true );
        }

    }



    $this->log( 'Comunidades - (' . ( $key + 1 ) . ' de ' . $qtd .') Finalizado.' );

}

$this->log( 'Comunidades - Finalizado...');