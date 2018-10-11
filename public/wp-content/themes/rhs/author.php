<?php
get_header();

get_edit_user_link();

$curauth = get_queried_object(); //(isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
if ($curauth) {
    ?>

    <!--Informações Pessoais-->
    <div class="col-xs-12 no-padding" style="background: white; padding-bottom: 20px !important;">

        <?php get_template_part('partes-templates/user-header-info'); ?>

        <div class="col-sm-6 col-md-6" id="accordion" role="tablist" aria-multiselectable="true">

            <div class="panel-heading" role="tab" id="InfoPessoais" style="border-top: 1px solid #e3e3e3; padding-top: 20px;">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse"
                       data-parent="#accordionInfo" href="#info_pessoais" aria-expanded="true" aria-controls="info_pessoais">
                        Informações Pessoais</a>
                </h4>
            </div>

            <div id="info_pessoais" class="panel-collapse collapse in" role="tabpanel" aria-expanded="true" aria-labelledby="InfoPessoais">
                <div class="panel-body">
                    <?php
                    $is_author = is_author(get_current_user_id());
                    $has_link = get_the_author_meta($RHSUsers::LINKS_USERMETA, $curauth->ID);

                    if( $is_author || $has_link ) {
                        global $RHSComunities;
                        if( $RHSComunities->get_communities_by_member( $curauth->ID ) && $is_author ) { ?>
                            <p>Comunidades: </p>
                            <?php foreach ( $RHSComunities->get_comunities_objects_by_user( $curauth->ID ) as $key => $comunidade ) :
                                if( !$comunidade->is_member() ) {
                                    continue;
                                } ?>
                                <div>
                                    <?php echo '<a href="'. $comunidade->get_url() . '" class="link_comunidade">' . $comunidade->get_name() . '</a>'; ?>
                                </div>
                            <?php endforeach; //end foreach

                        } //end grupos

                        if ($has_link && count($has_link) > 0) {
                            $RHSUsers->show_author_links($curauth->ID);
                        } //end links

                    } else {
                        echo 'Sem Informações';
                    } //end is author and has link
                    ?>
                </div>
            </div>

        </div> <!--Fim Informações Pessoais-->

        <!--Sobre e Interesses-->
        <div class="col-sm-6 col-md-6">
            <div style="border-top: 1px solid #e3e3e3; padding-top: 10px;" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel-heading" role="tab" id="SobreInteresses">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse"
                           data-parent="#accordionSobre" href="#sobre_interesses" aria-expanded="true" aria-controls="sobre_interesses">
                            Sobre e Interesses</a>
                    </h4>
                </div>
                <div id="sobre_interesses" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="SobreInteresses">
                    <div class="panel-body">
                        <?php if ( $RHSUsers->getSobre() ) { ?>
                            <p>Sobre: </p>
                            <span><?php echo change_p_for_br($RHSUsers->getSobre()); ?></span>
                        <?php } ?>
                        <?php if ( $RHSUsers->getInteresses() ) { ?>
                            <p>Interesses: </p>
                            <span><?php echo change_p_for_br($RHSUsers->getInteresses()); ?></span>
                        <?php } ?>
                        <?php if ( $RHSUsers->getFormacao() ) { ?>
                            <p>Formação: </p>
                            <span><?php echo change_p_for_br($RHSUsers->getFormacao()); ?></span>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div> <!--Fim Sobre e Interesses-->

        <?php if (have_posts()): ?>
            <div class="col-md-12 classificar dropdown" style="padding-top: 20px">
                <button class="btn btn-default dropdown-toggle pull-right" type="button" id="busca_filtro" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Ordenar posts deste autor por <?php echo RHSSearch::get_search_order_label(); ?> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu pull-right" aria-labelledby="busca_filtro">
                    <li><a href="<?php echo RHSSearch::get_search_in_users_posts_url('date', $curauth->ID); ?>">Data</a></li>
                    <li><a href="<?php echo RHSSearch::get_search_in_users_posts_url('comments', $curauth->ID); ?>">Comentários</a></li>
                    <li><a href="<?php echo RHSSearch::get_search_in_users_posts_url('votes', $curauth->ID); ?>">Votos</a></li>
                    <li><a href="<?php echo RHSSearch::get_search_in_users_posts_url('views', $curauth->ID); ?>">Visualizações</a></li>
                    <li><a href="<?php echo RHSSearch::get_search_in_users_posts_url('shares', $curauth->ID); ?>">Compartilhamentos</a></li>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <div class="clearfix"></div>

    <?php get_template_part( 'partes-templates/loop-posts' );
}

get_footer();