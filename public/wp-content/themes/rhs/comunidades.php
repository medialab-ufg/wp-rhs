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
            <div class="wrapper wrapper-content">

                <?php if($RHSComunities->can_see_comunities()){ ?>
                <div class="ibox-content forum-container">
                    <form autocomplete="off" class="form-inline">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="select-sort_order">Ordernar por:</label>
                                    <select class="form-control" name="sort_order" id="select-sort_order">
                                        <option value="">-- Selecione --</option>
                                        <?php foreach (
                                            $RHSComunities->filter_value( 'sort_order', 'search' ) as $value => $name
                                        ) { ?>
                                            <option <?php echo ( $name['selected'] ) ? 'selected' : ''; ?>
                                                    value="<?php echo $value ?>"><?php echo $name['nome'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <script>
                                    jQuery(function ($) {

                                        $('#select-sort_order').on('change', function () {
                                            var url = $(this).val();
                                            if (url) {
                                                window.location = url;
                                            }
                                            return false;
                                        });
                                    });
                                </script>
                            </div>
                            <div class="col-md-offset-5 col-md-3">
                                <div class="pull-right">
                                    <div class="input-group">
                                        <input type="text"
                                               value="<?php echo ! empty( $_REQUEST['search'] ) ? $_REQUEST['search'] : ''; ?>"
                                               class="form-control" name="search" placeholder="Procurar...">
                                        <span class="input-group-btn">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="forum-item">
                        <div class="row">
                            <?php if ($RHSComunities->get_comunities_by_user( get_current_user_id() ) ) { ?>
                                <?php foreach ( $RHSComunities->get_comunities_by_user(  get_current_user_id() ) as $comunidade ) { ?>
                                    <div class="col-md-12">
                                        <a href="<?php echo $comunidade->get_url(); ?>"
                                           class="forum-item-link"  >
                                            <div class="forum-item-title">
                                                <div class="forum-item-image">
                                                    <img src="<?php echo $comunidade->get_image(); ?>"/>
                                                </div>
                                                <span>
                                                    <?php echo $comunidade->get_name() ?>
                                                    <?php if($comunidade->is_lock()){ ?>
                                                        <i title="Esse grupo é privado" class="fa fa-lock"></i>
                                                    <?php } ?>
                                                    <?php if($comunidade->is_member()){ ?>
                                                    <i title="Você faz parte desta comunidade" class="fa fa-check"></i>
                                                    <?php } ?>
                                                </span>
                                            </div>
                                        </a>
                                        <div class="forum-info" data-id="<?php echo $comunidade->get_id(); ?>">
                                            <ul>
                                                <li>
                                                    <span class="views-number"><?php echo $comunidade->get_members_number(); ?></span>
                                                    <small>Membros</small>
                                                </li>
                                                <li>
                                                    <span class="views-number"><?php echo $comunidade->get_posts_number(); ?></span>
                                                    <small>Posts</small>
                                                </li>
                                                <li>
                                                    <a class="<?php echo (!$comunidade->can_edit()) ? 'hide' : '';?>" title="Editar a comunidade" id="comunity-edit"
                                                       href="<?php echo $comunidade->get_url_edit(); ?>">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <a class="<?php echo (!$comunidade->can_see_members()) ? 'hide' : '';?>" title="Ver membros" id="comunity-see-members"
                                                       href="<?php echo $comunidade->get_url_members(); ?>">
                                                        <i class="fa fa-users"></i>
                                                    </a>
                                                    <a class="<?php echo (!$comunidade->can_follow()) ? 'hide' : '';?>" title="Seguir a comunidade" id="comunity-follow"
                                                       href="<?php echo $comunidade->get_url_follow(); ?>">
                                                        <i class="fa fa-rss"></i>
                                                    </a>
                                                    <a class="<?php echo (!$comunidade->can_not_follow()) ? 'hide' : '';?>" title="Deixar de seguir a comunidade" id="comunity-not-follow"
                                                       href="<?php echo $comunidade->get_url_not_follow(); ?>">
                                                        <span class="fa-stack fa-lg">
                                                          <i class="fa fa-rss fa-stack-1x"></i>
                                                          <i class="fa fa-remove fa-stack-2x text-danger"></i>
                                                        </span>
                                                    </a>
                                                    <a class="<?php echo (!$comunidade->can_enter()) ? 'hide' : '';?>" title="Participar da comunidade" id="comunity-enter"
                                                       href="<?php echo $comunidade->get_url_enter(); ?>">
                                                        <i class="fa fa-sign-in"></i>
                                                    </a>
                                                    <a class="<?php echo (!$comunidade->can_leave()) ? 'hide' : '';?>" title="Sair da comunidade"
                                                       href="<?php echo $comunidade->get_url_leave(); ?>" id="comunity-leave">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } else { ?>
                                <h4 class="text-center">Nenhuma comunidade encontrada</h4>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="pull-right hide">
                        <nav>
                            <ul class="pagination">
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <?php } else { ?>
                <div class="ibox-content forum-container">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="text-center">Você não tem permissão de ver essa área</h4>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php get_footer( 'full' );