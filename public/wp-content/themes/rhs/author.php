<?php get_header(); ?>
<?php
get_edit_user_link();
$curauth = get_queried_object(); //(isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
?>
            <!-- Tab panes -->
            <?php include(locate_template('partes-templates/user-header-info.php')); ?>
            
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
                                        <?php 
                                        $is_author = is_author( get_current_user_id() );
                                        $has_link = get_the_author_meta($RHSUsers::LINKS_USERMETA, $curauth->ID);

                                        if( $is_author || $has_link ) {
                                            global $RHSComunities;
                                            if( $RHSComunities->get_communities_by_member( $curauth->ID ) && $is_author ) { ?>
                                                <p>Grupos: </p>
                                                
                                                <?php foreach ( $RHSComunities->get_comunities_objects_by_user( $curauth->ID ) as $key => $comunidade ) :
                                                    if( !$comunidade->is_member() ) {
                                                        continue;
                                                    } ?>
                                                    <div>
                                                        <?php echo '<a href="'. $comunidade->get_url() . '" class="link_comunidade">' . $comunidade->get_name() . '</a>'; ?>
                                                    </div>
                                                <?php endforeach; //end foreach

                                            } //end grupos

                                            if ($has_link) { ?>
                                                <p>Links: </p>
                                                <?php $RHSUsers->show_author_links($curauth->ID); ?>
                                            <?php } //end links

                                        } else {
                                            echo 'Sem Informações';
                                        } //end is author and has link 
                                        ?>
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
                                        <?php if (!($RHSUsers->getSobre()) && $RHSUsers->getInteresses() && $RHSUsers->getFormacao()) { ?>
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
<?php get_footer();
