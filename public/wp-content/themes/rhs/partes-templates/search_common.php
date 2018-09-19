<div class="col-md-12 search-header-common">

    <div class="col-md-6 retorno">

        <?php if(count($_GET)) { ?>
            <div class="label-rhs">
            <?php
            if (isset($users) && $users instanceof WP_User_Query):
                exibir_resultado_user();
            else:
                exibir_resultado_post();
            endif;
            ?>
            </div>
        <?php } ?>

    </div>

    <div class="col-md-6 classificar">
        <div class="pull-right">
            <div class="dropdown">
                <?php RHSSearch::show_button_download_report(); ?>

                <?php if (isset($users) && $users instanceof WP_User_Query): ?>
                    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Ordenar por
                        <?php
                        if(RHSSearch::get_param('rhs_order') == 'name')
                            echo 'Nome';
                        elseif(RHSSearch::get_param('rhs_order') == 'register_date')
                            echo 'Data de Cadastro';
                        elseif(RHSSearch::get_param('rhs_order') == 'posts')
                            echo 'Número de Posts';
                        elseif(RHSSearch::get_param('rhs_order') == 'votes')
                            echo 'Número de Votos';
                        else
                            echo '';
                        ?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><a href="<?php echo RHSSearch::get_search_neworder_urls('name'); ?>">Nome</a></li>
                        <li><a href="<?php echo RHSSearch::get_search_neworder_urls('register_date'); ?>">Data de Cadastro</a></li>
                        <li><a href="<?php echo RHSSearch::get_search_neworder_urls('posts'); ?>">Número de Posts</a></li>
                        <li><a href="<?php echo RHSSearch::get_search_neworder_urls('votes'); ?>">Número de Votos</a></li>
                        <li><a href="<?php echo RHSSearch::get_search_neworder_urls('last_login'); ?>">Último Login</a></li>
                    </ul>
                <?php else: ?>

                    <button class="btn btn-default dropdown-toggle" type="button" id="busca_filtro" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Classificar por
                        <?php
                        if(RHSSearch::get_param('rhs_order') == 'date')
                            echo 'Data';
                        elseif(RHSSearch::get_param('rhs_order') == 'comments')
                            echo 'Comentários';
                        elseif(RHSSearch::get_param('rhs_order') == 'votes')
                            echo 'Votos';
                        elseif(RHSSearch::get_param('rhs_order') == 'views')
                            echo 'Visualizações';
                        elseif(RHSSearch::get_param('rhs_order') == 'shares')
                            echo 'Compartilhamentos';
                        else
                            echo '';
                        ?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><a href="<?php echo RHSSearch::get_search_neworder_urls('date'); ?>">Data</a></li>
                        <li><a href="<?php echo RHSSearch::get_search_neworder_urls('comments'); ?>">Comentários</a></li>
                        <li><a href="<?php echo RHSSearch::get_search_neworder_urls('votes'); ?>">Votos</a></li>
                        <li><a href="<?php echo RHSSearch::get_search_neworder_urls('views'); ?>">Visualizações</a></li>
                        <li><a href="<?php echo RHSSearch::get_search_neworder_urls('shares'); ?>">Compartilhamentos</a></li>
                    </ul>
                    <br>

                <?php endif; ?>

            </div>
        </div>
    </div>

</div>