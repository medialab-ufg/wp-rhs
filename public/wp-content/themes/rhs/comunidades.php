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
            <div class="content-table">
                <div class="table-responsive">
                    <table class="table table-hover">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Status</th>
                        <th>Info</th>
                        <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ( $RHSComunities->can_see_comunities() ) { ?>
                        <?php foreach ( $RHSComunities->get_comunities_by_user( get_current_user_id() ) as $comunidade ) { ?>
                            <tr data-id="<?php echo $comunidade->get_id(); ?>"
                                data-userid="<?php echo get_current_user_id(); ?>">
                                <td>
                                    <a class="comunity-image" href="<?php echo $comunidade->get_url(); ?>">
                                        <img class="img-responsive img-circle"
                                             src="<?php echo $comunidade->get_image(); ?>"/>
                                        <span><?php echo $comunidade->get_name() ?></span>
                                    </a>
                                </td>
                                <td>
                                    <i data-toggle="tooltip" data-placement="top" title="Essa comunidade é privada" class="fa fa-lock" <?php echo !$comunidade->is_lock() ? 'style="display:none"' : '' ?>></i>
                                    <i data-toggle="tooltip" data-placement="top" title="Esse comunidade é aberta" class="fa fa-check" <?php echo $comunidade->is_lock() ? 'style="display:none"' : '' ?>></i>
                                    <i data-toggle="tooltip" data-placement="top" title="Você faz parte desta comunidade" class="fa fa-user" <?php echo !$comunidade->is_member() ? 'style="display:none"' : '' ?>></i>
                                </td>
                                <td>
                                    <ul>
                                        <li>
                                            <span class="views-number"><?php echo $comunidade->get_members_number(); ?></span>
                                            <small>Membro<?php if($comunidade->get_members_number() > 1){ ?>s <?php } ?></small>
                                        </li>
                                        <li>
                                            <span class="views-number"><?php echo $comunidade->get_posts_number(); ?></span>
                                            <small>Post<?php if($comunidade->get_posts_number() > 1){ ?>s <?php } ?></small>
                                        </li>
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        <li>
                                            <?php echo $comunidade->get_button_members(); ?>
                                            <?php echo $comunidade->get_button_follow(); ?>
                                            <?php echo $comunidade->get_button_not_follow(); ?>
                                            <?php echo $comunidade->get_button_enter(); ?>
                                            <?php echo $comunidade->get_button_leave(); ?>
                                            <?php echo $comunidade->get_button_request(); ?>
                                            <?php echo $comunidade->get_button_wait_request(); ?>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5" style="text-align: center">Faça um login para poder ver essa área.</td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
<?php get_footer( 'full' );