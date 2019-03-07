<?php
if(is_user_logged_in()) {
    //se estiver logado, irá para as páginas corretas
    $url_publica = get_home_url() . '/' . RHSRewriteRules::POST_URL;
    $url_vote = get_home_url() . '/' . RHSRewriteRules::VOTING_QUEUE_URL;
} else {
    //se não estiver logado, irá para a pagina definida pelo admin
    $url_publica = show_buttons_header_Pub();
    $url_vote = show_buttons_header_Vote();
}
?>

<section class="buttons_top hidden-print">
    <a href="<?php echo $url_publica; ?>">Publicar na rede</a>
    <a href="<?php echo $url_vote; ?>">Ver Fila de Votação</a>
</section>