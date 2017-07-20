<?php
/**
 * Template name: Base-Comunidade
 */
?>
<?php get_header( 'full' ); ?>
<?php global $RHSComunities; ?>
    <div class="row comunidades">
        <div class="col-xs-12">
            <h1 class="titulo-page">Comunidades</h1>
            <div class="wrapper wrapper-content animated fadeInRight">
                <?php if ( $RHSComunities->can_see_comunities() ) { ?>
                    <div class="ibox-content forum-container">
                        <div class="forum-item">
                            <?php if ( $RHSComunities->get_comunities_by_user( get_current_user_id() ) ) { ?>
                                <?php foreach ( $RHSComunities->get_comunities_by_user( get_current_user_id() ) as $comunidade ) { ?>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <a href="<?php echo $comunidade->get_url(); ?>"
                                               class="forum-item-link">
                                                <div class="forum-item-title">
                                                    <div class="forum-item-image">
                                                        <img src="<?php echo $comunidade->get_image(); ?>"/>
                                                    </div>
                                                    <span>
                                                    <?php echo $comunidade->get_name() ?>
                                                        <?php if ( $comunidade->is_lock() ) { ?>
                                                            <i title="Esse grupo é privado" class="fa fa-lock"></i>
                                                        <?php } ?>
                                                        <?php if ( $comunidade->is_member() ) { ?>
                                                            <i title="Você faz parte desta comunidade"
                                                               class="fa fa-check"></i>
                                                        <?php } ?>
                                                </span>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="forum-info">
                                                <ul>
                                                    <li>
                                                        <span class="views-number"><?php echo $comunidade->get_members_number(); ?></span>
                                                        <small>Membros</small>
                                                    </li>
                                                    <li>
                                                        <span class="views-number"><?php echo $comunidade->get_posts_number(); ?></span>
                                                        <small>Posts</small>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="forum-info" data-id="<?php echo $comunidade->get_id(); ?>"
                                                 data-userid="<?php echo get_current_user_id(); ?>">
                                                <ul>
                                                    <li>
                                                        <a data-type="members" <?php echo ! $comunidade->can_see_members() ? 'style="display: none;"' : ''; ?>
                                                           title="Ver membros"
                                                           href="<?php echo $comunidade->get_url_members(); ?>">
                                                            <i class="fa fa-users"></i>
                                                        </a>
                                                        <a data-type="follow" <?php echo ! $comunidade->can_follow() ? 'style="display: none;"' : ''; ?>
                                                           title="Seguir a comunidade" href="javascript:;">
                                                            <i class="fa fa-rss"></i>
                                                        </a>
                                                        <a data-type="not_follow" <?php echo ! $comunidade->can_not_follow() ? 'style="display: none;"' : ''; ?>
                                                           title="Deixar de seguir a comunidade" href="javascript:;">
                                                        <span class="fa-stack fa-lg">
                                                          <i class="fa fa-rss fa-stack-1x"></i>
                                                          <i class="fa fa-remove fa-stack-2x text-danger"></i>
                                                        </span>
                                                        </a>
                                                        <a data-type="enter" <?php echo ! $comunidade->can_enter() ? 'style="display: none;"' : ''; ?>
                                                           title="Participar da comunidade" href="javascript:;">
                                                            <i class="fa fa-sign-in"></i>
                                                        </a>
                                                        <a data-type="leave" <?php echo ! $comunidade->can_leave() ? 'style="display: none;"' : ''; ?>
                                                           title="Sair da comunidade" href="javascript:;">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                        <a data-type="request" <?php echo ! $comunidade->can_request() ? 'style="display: none;"' : ''; ?>
                                                           title="Pedir para fazer parte da comunidade"
                                                           href="javascript:;">
                                                            <i class="fa fa-external-link"></i>
                                                        </a>
                                                        <a data-type="wait_request" <?php echo ! $comunidade->can_wait_request() ? 'style="display: none;"' : ''; ?>
                                                           title="Seu pedido foi enviado, aguarde"
                                                           href="javascript:void(0);">
                                                            <i class="fa fa-send"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } else { ?>
                                <div class="row">
                                    <h4 class="text-center">Nenhuma comunidade encontrada</h4>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                <?php } else { ?>
                    <div class="ibox-content forum-container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="min-height">
                                    <h4 class="text-center">Faça um login para poder ver essa área.</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php get_footer( 'full' );