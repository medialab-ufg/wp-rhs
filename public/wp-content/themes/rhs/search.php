<?php get_header('full'); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default busca-page" style="padding: 10px;">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#posts" aria-controls="posts" role="tab" data-toggle="tab">Posts</a></li>
                <li role="presentation"><a href="<?php echo RHSSearch::get_users_search_url(); ?>">Usuários</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="posts">
                    <div class="jumbotron formulario">
                        <?php get_template_part("partes-templates/header_search_post"); ?>
                    </div>
                    <div class="row resultado">
                        <div class="row">
                            <div class="col-xs-6 retorno">
                                <?php if(count($_GET)) {?>
                                    <div class="label-rhs">
                                        <?php
                                            //Mostra o resultado da busca dos posts
                                            exibir_resultado_post();
                                        ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="col-xs-6 classificar">
                                <div class="pull-right">
                                    <div class="dropdown">
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
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        <?php get_template_part( 'partes-templates/loop-posts'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer('full');
