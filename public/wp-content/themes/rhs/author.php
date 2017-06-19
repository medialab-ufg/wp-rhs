<?php get_header(); ?>
<?php

$curauth = get_queried_object(); //(isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
?>
    <div class="row">
        <!-- Container -->
        <div class="col-xs-12 col-md-9">
            <div class="row">
                <!-- Button Publicar e Ver Fila de Votação -->
                <?php get_template_part('partes-templates/buttons-top' ); ?>
            </div>
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="verDados">
                    <div class="jumbotron">
                        <?php if($curauth){ ?>
                            <?php
                            global $RHSUser;
                            $RHSUser = new RHSUser($curauth->ID);
                            $votos   = new RHSVote();
                            ?>
                        <div class="avatar-user">
                            <?php echo get_avatar($RHSUser->getUserId()); ?>
                        </div>
                        <div class="info-user">
                            <p class="nome-author">
                                <?php echo $RHSUser->get_user_data('display_name'); ?>
                                <?php if( is_user_logged_in() && is_author(get_current_user_id())) : ?>
                                    <span class="btn-editar-user"><a class="btn btn-default" href="<?php echo home_url(RHSRewriteRules::PROFILE_URL ); ?>">EDITAR</a></span>
                                <?php endif; ?>
                            </p>
                            <p class="localidade"><?php echo the_user_ufmun($RHSUser->getUserId()); ?></p>
                            <div class="contagem">
                                <span class="contagem-valor-author"><?php echo count_user_posts( $curauth->ID ); ?></span>
                                <span class="contagem-desc-author">POSTS</span>
                            </div>
                            <div class="contagem">
                                <span class="contagem-valor-author"><?php echo $votos->get_total_votes_by_author( $curauth->ID ); ?></span>
                                <span class="contagem-desc-author">VOTOS</span>
                            </div>
                        </div>  
                        <span class="seguir-mensagem">
                            <button class="btn btn-default">SEGUIR</button>
                            <button class="btn btn-default">ENVIAR MENSAGEM</button>
                        </span>
                        <div class="clearfix"></div>
                    <?php } else { ?>
                            <div class="user-unknown">Esse usúario não existe !</div>
                    <?php } ?>
                    </div>
                </div>
            </div>
            <?php if($curauth){ ?>
                <!--Informações Pessoais-->
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="InfoPessoais">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse"
                                           data-parent="#accordionInfo" href="#info_pessoais" aria-expanded="false"
                                           aria-controls="info_pessoais">
                                            Informações Pessoais</a>
                                    </h4>
                                </div>
                                <div id="info_pessoais" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="InfoPessoais">
                                    <div class="panel-body">
                                        <p class="hide">Grupos: </p>
                                        <span class="hide">-Privado-</span>
                                        <?php if ( $RHSUser->getLinks() ) { ?>
                                            <p>Links: </p>
                                            <?php foreach ( $RHSUser->getLinks() as $key => $link ) { ?>
                                                <span><a href="<?php echo $link['url'] ?>"><?php echo $link['title'] ?></a></span>
                                                <?php echo ( count( $RHSUser->getLinks() ) != ( $key + 1 ) ) ? ',' : ''; ?>
                                            <?php } ?>
                                        <?php } else { ?>
                                            Sem Informação.
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!--Fim Informações Pessoais-->

                    <!--Sobre e Interesses-->
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="SobreInteresses">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse"
                                           data-parent="#accordionSobre" href="#sobre_interesses" aria-expanded="false"
                                           aria-controls="sobre_interesses">
                                            Sobre e Interesses</a>
                                    </h4>
                                </div>
                                <div id="sobre_interesses" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="SobreInteresses">
                                    <div class="panel-body">
                                        <?php if ( $RHSUser->getSobre() ) { ?>
                                            <p>Sobre: </p>
                                            <span><?php echo change_p_for_br($RHSUser->getSobre()); ?></span>
                                        <?php } ?>
                                        <?php if ( $RHSUser->getInteresses() ) { ?>
                                            <p>Interesses: </p>
                                            <span><?php echo change_p_for_br($RHSUser->getInteresses()); ?></span>
                                        <?php } ?>
                                        <?php if ( $RHSUser->getFormacao() ) { ?>
                                            <p>Formação: </p>
                                            <span><?php echo change_p_for_br($RHSUser->getFormacao()); ?></span>
                                        <?php } ?>
                                        <?php if (!($RHSUser->getSobre()) && $RHSUser->getInteresses() && $RHSUser->getFormacao()) { ?>
                                            Sem informção.
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!--Fim Sobre e Interesses-->
                </div>

                <?php get_template_part( 'partes-templates/loop-posts' ); ?>
            <?php } ?>
        </div>
        <!-- Sidebar -->
        <div class="col-xs-12 col-md-3"><?php get_sidebar(); ?></div>
    </div>

<?php get_footer();