<section class="buttons_top hidden-print">
        <div class="col-xs-12 col-sm-12 col-md-12 full-width">
            <div class="btn-group btn-group-justified" role="group">
                <div class="btn-group" role="group">
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
                <a class="btn btn-default" href="<?php echo $url_publica; ?>">Publicar POST</a>
                </div>
                <div class="btn-group" role="group">
                <a class="btn btn-default" href="<?php echo $url_vote; ?>">Ver Fila de Votação</a>
                </div>
            </div>
        </div>
</section>