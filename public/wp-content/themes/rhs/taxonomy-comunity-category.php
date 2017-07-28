<?php get_header('full'); ?>
<?php global $RHSComunities;?>
<?php if($comunity = $RHSComunities->get_comunity_by_request()){ ?>
    <div class="col-xs-12 comunidade" id="comunidade" data-id="<?php echo $comunity->get_id(); ?>" data-userid="<?php echo get_current_user_id(); ?>">
        <div class="card hovercard">
            <div class="card-background">
                <img class="card-bkimg" alt="" src="<?php echo $comunity->get_image(); ?>">
            </div>
            <div class="card-buttons left">
                <?php echo $comunity->get_button_follow(); ?>
                <?php echo $comunity->get_button_not_follow(); ?>
            </div>
            <div class="card-buttons right">
                <?php echo $comunity->get_button_leave(); ?>
                <?php echo $comunity->get_button_enter(); ?>
                <?php echo $comunity->get_button_request(); ?>
              </div>
            <div class="useravatar">
                <div class="row">
                    <div class="col-xs-12">
                        <form enctype="multipart/form-data" action="" method="post">
                            <div class="image-comunity form-image">
                                <img <?php if($comunity->can_edit()){ ?> style="cursor: pointer" onclick="document.getElementById('file-avatar_comunity').click();" <?php } ?> src="<?php echo $comunity->get_image(); ?>" />
                                <?php if($comunity->can_edit()){ ?>
                                    <input type="file" id="file-avatar_comunity" name="avatar_comunity" class="form-control" />
                                    <input type="hidden" name="edit_image_comunity_wp" value="<?php echo $RHSComunities->getKey(); ?>">
                                    <input type="hidden" name="comunity_id" value="<?php echo $comunity->get_id(); ?>">
                                    <div class="button-end" >
                                        <button onclick="document.getElementById('file-avatar_comunity').click();" type="button" class="btn btn-default" >
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </div>
                                    <div class="btn-group">
                                        <button type="submit" class="btn btn-success hide save">
                                            Salvar
                                        </button>
                                        <button onclick="location.reload();" type="button" class="btn btn-danger hide save">
                                            Cancelar
                                        </button>
                                    </div>
                                <?php } ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-info">
                <div class="row">
                    <div class="col-md-12 col-sm-7 col-xs-12 col-sm-pull-0">
                        <div class="card-title">
                            <?php echo $comunity->get_name(); ?>
                            <?php if($comunity->is_lock()){ ?>
                                <i data-toggle="tooltip" data-placement="top" title="Essa comunidade é privada" class="fa fa-lock"></i>
                            <?php } else { ?>
                                <i data-toggle="tooltip" data-placement="top" title="Essa comunidade é aberta" class="fa fa-check"></i>
                            <?php } ?>
                            <?php if($comunity->is_member()){ ?>
                                <i data-toggle="tooltip" data-placement="top" title="Você faz parte desta comunidade" class="fa fa-user"></i>
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
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-pref btn-group btn-group-justified btn-group-lg" role="group" aria-label="...">
            <div class="btn-group" role="group">
                <button type="button" id="stars" class="btn <?php echo (!$comunity->is_to_see_members()) ? 'btn-primary' : 'btn-default';  ?>" href="#tab1" data-toggle="tab">
                    <span class="glyphicon glyphicon-th" aria-hidden="true"></span>
                    <div class="hidden-xs">Posts</div>
                </button>
            </div>
            <div class="btn-group" role="group">
                <button type="button" id="membros" class="btn <?php echo ($comunity->is_to_see_members()) ? 'btn-primary' : 'btn-default';  ?>" href="#tab2" data-toggle="tab">
                    <span class="fa fa-user" aria-hidden="true"></span>
                    <div class="hidden-xs">Membros</div>
                </button>
            </div>
        </div>
        <div class="well">
            <div class="tab-content">
                <div class="tab-pane fade in <?php echo (!$comunity->is_to_see_members()) ? 'active' : '';  ?>" id="tab1">
                <?php if($comunity->can_see()){ ?>
                        <?php $args = array(
                            'post_status' => array('publish', 'private', RHSVote::VOTING_QUEUE),
                            'tax_query' => array(
                                array(
                                    'taxonomy' => RHSComunities::TAXONOMY,
                                    'field' => 'term_id',
                                    'terms' => $comunity->get_id()
                                )
                            )
                        );

                        query_posts($args); ?>
                        <?php include(locate_template( 'partes-templates/loop-posts.php')); ?>
                    <?php } else { ?>
                        <h4 class="text-center">Você não tem permissão para ver os posts desta comunidade</h4>
                    <?php } ?>
                </div>
                <div class="tab-pane fade in <?php echo ($comunity->is_to_see_members()) ? 'active' : '';  ?>" id="tab2">
                    <?php if($comunity->can_members()){ ?>
                        <?php include(locate_template('comunidade-membros.php')); ?>
                    <?php } else { ?>
                        <h4 class="text-center">Você não tem permissão para ver os membros desta comunidade</h4>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php } else { ?>
<script> window.location.href = '<?php echo home_url('comunidades'); ?>';</script>
<?php } ?>
<?php get_footer('full');