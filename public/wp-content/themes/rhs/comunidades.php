<?php
/**
 * Template name: Base-Comunidade
 */
?>
<?php get_header( 'full' ); ?>
<?php global $RHSComunity; ?>
    <div class="row comunidades">
        <div class="col-xs-12">
            <h1 class="titulo-page">Comunidades</h1>
            <div class="wrapper wrapper-content animated fadeInRight">
                <div class="ibox-content forum-container">
                    <form class="form-inline">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="select-sort_order">Ordernar por:</label>
                                    <select class="form-control" name="sort_order" id="select-sort_order">
                                        <option value="">-- Selecione --</option>
                                        <?php foreach (
                                            $RHSComunity->filter_value( 'sort_order', 'search' ) as $value => $name
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
                            <?php if ($RHSComunity->get_comunities( get_the_ID() ) ) { ?>
                                <?php foreach ( $RHSComunity->get_comunities( get_the_ID() ) as $comunidade ) { ?>
                                    <div class="col-md-12">
                                        <a href="<?php echo home_url( 'comunidade/?comunidade_id=' . $comunidade['id'] ) ?>"
                                           class="forum-item-link">
                                            <div class="forum-item-title">
                                                <div class="forum-item-image">
                                                    <img src="http://www.teleios.com.br/wp-content/uploads/2006/08/indios1.jpg"/>
                                                </div>
                                                <span>
                                                    <?php echo $comunidade['name'] ?>
                                                    <?php echo ( $comunidade['type'] && $comunidade['type'] != RHSComunity::TYPE_OPEN ) ? '<i title="Esse grupo é privado" class="fa fa-lock"></i>' : ''; ?>
                                                    <?php echo ( $comunidade['user_inside'] ) ? '<i title="Você faz parte desta comunidade" class="fa fa-check"></i>' : ''; ?>
                                                </span>
                                            </div>
                                        </a>
                                        <div class="forum-info">
                                            <ul>
                                                <li>
                                                    <span class="views-number"><?php echo $comunidade['members']; ?></span>
                                                    <small>Membros</small>
                                                </li>
                                                <li>
                                                    <span class="views-number"><?php echo $comunidade['posts']; ?></span>
                                                    <small>Posts</small>
                                                </li>
                                                <li>
                                                    <?php if ( $comunidade['can_edit'] ) { ?>
                                                        <a title="Editar a comunidade"
                                                           href="<?php echo home_url( 'comunidade/' . $comunidade['id'] . '/editar' ) ?>">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if ( $comunidade['can_members'] ) { ?>
                                                        <a title="Ver membros"
                                                           href="<?php echo home_url( 'comunidade/' . $comunidade['id'] . '/membros' ) ?>">
                                                            <i class="fa fa-users"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if ( $comunidade['can_follow'] ) { ?>
                                                        <a title="Seguir a comunidade"
                                                           href="<?php echo home_url( 'comunidade/' . $comunidade['id'] . '/seguir' ) ?>">
                                                            <i class="fa fa-rss"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if ( $comunidade['can_not_follow'] ) { ?>
                                                        <a title="Deixar de seguir a comunidade"
                                                           href="<?php echo home_url( 'comunidade/' . $comunidade['id'] . '/deixar-seguir' ) ?>">
                                                <span class="fa-stack fa-lg">
                                                  <i class="fa fa-rss fa-stack-1x"></i>
                                                  <i class="fa fa-remove fa-stack-2x text-danger"></i>
                                                </span>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if ( $comunidade['can_enter'] ) { ?>
                                                        <a title="Participar da comunidade"
                                                           href="<?php echo home_url( 'comunidade/' . $comunidade['id'] . '/entrar' ) ?>">
                                                            <i class="fa fa-sign-in"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if ( $comunidade['can_leave'] ) { ?>
                                                        <a title="Sair da comunidade"
                                                           href="<?php echo home_url( 'comunidade/' . $comunidade['id'] . '/sair' ) ?>">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                    <?php } ?>
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
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
<?php get_footer( 'full' );