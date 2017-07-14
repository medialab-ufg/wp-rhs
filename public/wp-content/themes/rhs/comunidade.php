<?php 
/**
* Template name: Comunidade
*/
?>
<?php get_header('full'); ?>
<?php global $RHSComunities;?>
<?php if($comunity = $RHSComunities->get_comunity_by_request()){ ?>
    <div class="col-xs-12 comunidade">
        <div class="card hovercard">
            <div class="card-background">
                <img class="card-bkimg" alt="" src="<?php echo $comunity->get_image(); ?>">
            </div>
            <div class="card-buttons left">
                <a class="<?php echo !$comunity->can_follow() ? 'hide' : ''; ?>" href="javascript:;">Seguir <i class="fa fa-rss"></i></a>
                <a class="<?php echo !$comunity->can_not_follow() ? 'hide' : ''; ?>" href="javascript:;">Deixar de seguir <i class="fa fa-rss"></i></a>
            </div>
            <div class="card-buttons right">
                <a class="<?php echo !$comunity->can_leave() ? 'hide' : ''; ?>" href="javascript:;">Sair <i class="fa fa-remove"></i></a>
                <a class="<?php echo !$comunity->can_enter() ? 'hide' : ''; ?>" href="javascript:;">Entrar <i class="fa fa fa-sign-in"></i></a>
            </div>
            <div class="useravatar">
                <div class="row">
                    <div class="col-xs-12">
                        <img src="<?php echo $comunity->get_image(); ?>" />
                    </div>
                </div>
            </div>
            <div class="card-info">
                <div class="row">
                    <div class="col-md-12 col-sm-7 col-xs-12 col-sm-pull-0">
                        <div class="card-title">
                            <?php echo $comunity->get_name(); ?>
                            <?php if($comunity->is_lock()){ ?>
                                <i title="Esse grupo é privado" class="fa fa-lock"></i>
                            <?php } ?>
                            <?php if($comunity->is_member()){ ?>
                                <i title="Você faz parte desta comunidade" class="fa fa-check"></i>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-5 col-xs-12 col-sm-pull-0">
                        <div class="espace">
                            <ul>
                                <li>
                                    <span class="views-number"><?php echo $comunity->get_members_number(); ?></span>
                                    <small>Membros</small>
                                </li>
                                <li>
                                    <span class="views-number"><?php echo $comunity->get_posts_number(); ?></span>
                                    <small>Posts</small>
                                </li>
                                <li>
                                    <span class="views-number"><?php echo $comunity->get_follows_number(); ?></span>
                                    <small>Seguidores</small>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-pref btn-group btn-group-justified btn-group-lg" role="group" aria-label="...">
            <div class="btn-group" role="group">
                <button type="button" id="stars" class="btn btn-primary" href="#tab1" data-toggle="tab">
                    <span class="glyphicon glyphicon-th" aria-hidden="true"></span>
                    <div class="hidden-xs">Posts</div>
                </button>
            </div>
            <div class="btn-group" role="group">
                <button type="button" id="membros" class="btn btn-default" href="#tab2" data-toggle="tab">
                    <span class="fa fa-user" aria-hidden="true"></span>
                    <div class="hidden-xs">Membros</div>
                </button>
            </div>
        </div>

        <div class="well">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab1">
                    <?php $args = array(
                        'tax_query' => array(
                            array(
                                'taxonomy' => RHSComunities::TAXONOMY,
                                'field' => 'term_id',
                                'terms' => $_GET['comunidade_id']
                            )
                        )
                    );

                    query_posts($args); ?>
                    <?php get_template_part( 'partes-templates/loop-posts'); ?>
                </div>
                <div class="tab-pane fade in" id="tab2">
                    <?php get_template_part('membro'); ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php get_footer('full');