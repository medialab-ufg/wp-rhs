<?php get_header(); ?>

    <div class="row">
        <!-- Container -->
        <div class="col-xs-12 col-md-9">
            <div class="row">
                <!-- Button Publicar e Ver Fila de Votação -->
                <?php get_template_part('partes-templates/buttons-top' ); ?>
            </div>
			<?php
			$curauth = get_queried_object(); //(isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
			$votos   = new RHSVote();
			$user    = new RHSUser($curauth->ID);
			?>
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="verDados">
                    <div class="jumbotron">
                        <div class="row">
                            <div class="col-xs-5 col-sm-2 col-md-2">
                                <img src="<?php echo $user->getAvatarImage(); ?>"
                                     alt="<?php echo $curauth->display_name; ?>" class="img-circle avatar-user">
                            </div>
                            <div class="col-xs-7 col-md-6">
                                <div class="col-xs-12">
                                    <p class="nome-author"><?php echo $curauth->display_name; ?></p>
                                    <small class="localidade">Goiânia, Goias</small>
                                </div>
                                <div class="col-xs-3 media-left">
                                    <span class="contagem-valor-author"><?php echo count_user_posts( $curauth->ID ); ?></span>
                                    <span class="contagem-desc-author">POSTS</span>
                                </div>
                                <div class="col-xs-3 media-left">
                                    <span class="contagem-valor-author"><?php echo $votos->get_total_votes_by_author( $curauth->ID ); ?></span>
                                    <span class="contagem-desc-author">VOTOS</span>
                                </div>
                            </div>
                            <div class="col-xs-5 col-sm-3 col-md-4">
							<span class="seguir-mensagem">
								<button class="btn btn-default">SEGUIR</button>
								<button class="btn btn-default">ENVIAR MENSAGEM</button>
							</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
									<?php if ( $user->getLinks() ) { ?>
                                        <p>Links: </p>
										<?php foreach ( $user->getLinks() as $key => $link ) { ?>
                                            <span><a href="<?php echo $link['url'] ?>"><?php echo $link['title'] ?></a></span>
											<?php if ( count( $user->getLinks() ) == ( $key + 1 ) ) { ?>
                                                ,
											<?php } ?>
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
									<?php if ( $user->getSobre() ) { ?>
                                        <p>Sobre: </p>
                                        <span><?php echo $user->getSobre(); ?></span>
									<?php } ?>
									<?php if ( $user->getInteresses() ) { ?>
                                        <p>Interesses: </p>
                                        <span><?php echo $user->getInteresses(); ?></span>
									<?php } ?>
									<?php if ( $user->getFormacao() ) { ?>
                                        <p>Formação: </p>
                                        <span><?php echo $user->getFormacao(); ?></span>
									<?php } ?>
									<?php if (!($user->getSobre()) && $user->getInteresses() && $user->getFormacao()) { ?>
                                        Sem informção.
									<?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--Fim Sobre e Interesses-->
            </div>

			<?php get_template_part( 'partes-templates/loop-posts' ); ?>
        </div>
        <!-- Sidebar -->
        <div class="col-xs-12 col-md-3"><?php get_sidebar(); ?></div>
    </div>

<?php get_footer();