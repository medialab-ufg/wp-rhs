<?php get_header( 'full' ); ?>
<?php global $RHSUsers; ?>
<?php

$current_user = new RHSUser( wp_get_current_user() );

if ( $current_user->is_admin() && get_query_var( 'rhs_user' ) ) {
    $RHSUser   = new RHSUser( get_userdata( get_query_var( 'rhs_user' ) ) );
    $RHSPerfil = new RHSPerfil( get_query_var( 'rhs_user' ) );
} else {
    $RHSUser   = $current_user;
    $RHSPerfil = new RHSPerfil( $current_user->get_id() );
}

?>
    <div class="row">
        <!-- Container -->
        <div class="col-xs-12 col-md-12">
            <form autocomplete="off" role="form" id="perfil" method="post" action="" enctype="multipart/form-data">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="verDados">
                        <div class="jumbotron perfil">
                            <h3 class="perfil-title">Editar Perfil</h3>
                            <?php foreach ( $RHSPerfil->messages() as $type => $messages ) { ?>
                                <div class="alert alert-<?php echo $type == 'error' ? 'danger' : 'success'; ?>">
                                    <?php foreach ( $messages as $message ) { ?>
                                        <p><?php echo $message ?></p>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <?php $RHSPerfil->clear_messages(); ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="perfil-title">Configurações de Segurança da Conta</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="mail">Endereço de email</label>
                                                <input value="<?php echo $RHSUser->get_login(); ?>" disabled
                                                       class="form-control" type="text" id="mail" name="mail" size="60"
                                                       maxlength="254">
                                                <input value="<?php echo $RHSUsers->getKey(); ?>" name="edit_user_wp"
                                                       type="hidden"/>
                                                <input value="<?php echo $RHSUser->get_id(); ?>" name="user_id"
                                                       type="hidden"/>
                                                <p class="help-block">Um email válido. Todos os emails do sistema são
                                                    enviados para este endereço. O email não é visível para o público e
                                                    será usado apenas se você precisar recuperar a sua senha ou desejar
                                                    receber notícias ou notificações por email.</p>
                                            </div>
                                            <label for="">Receber Notificação por E-mail</label>
                                            <div class="col-sm-12">
                                                <div class="form-group col-sm-6">
                                                    <p>
                                                        <input type="checkbox" name="promoted_post" id="promoted_post" value="true" <?php echo ($RHSUsers->getPromoted_post() == '') ? "checked" : ""; ?>>
                                                        <label for="promoted_post" style="margin-left: 3%; font-weight: 300;">
                                                            Meus Posts Promovidos
                                                        </label>
                                                    </p>
                                                    <p>
                                                        <input type="checkbox" name="comment_post" id="comment_post" value="true" <?php echo ($RHSUsers->getComment_post() == '') ? "checked" : ""; ?>>
                                                        <label for="comment_post" style="margin-left: 3%; font-weight: 300;">
                                                            Comentários em meus Posts
                                                        </label>
                                                    </p>
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <p>
                                                        <input type="checkbox" name="comment_post_follow" id="comment_post_follow" value="true" <?php echo ($RHSUsers->getComment_post_follow() == '') ? "checked" : ""; ?>>
                                                        <label for="comment_post_follow" style="margin-left: 3%; font-weight: 300;">
                                                            Comentários em Posts Seguidos
                                                        </label>
                                                    </p>
                                                    <p style="display: inline-flex;">
                                                        <input type="checkbox" name="new_post_from_user" id="new_post_from_user" value="true" <?php echo ($RHSUsers->getNew_post_from_user() == '') ? "checked" : ""; ?>>
                                                        <label for="new_post_from_user" style="margin-left: 3%; font-weight: 300;">
                                                            Novos Posts de Pessoas que estou Seguindo
                                                        </label>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="hidden-sm hidden-md hidden-lg">
                                        <div class="col-sm-6">
                                            <label for="">Alterar Senha</label>
                                            <div class="form-group">
                                                <label for="pass_old">Senha atual</label>
                                                <input class="perfil-pass form-control" type="password" id="pass_old"
                                                       name="pass_old" size="25" maxlength="128">
                                            </div>
                                            <div class="form-group">
                                                <label for="edit-pass">Senha nova</label>
                                                <input class="perfil-pass form-control" type="password" id="pass"
                                                       name="pass" size="25" maxlength="128">
                                            </div>
                                            <div class="form-group">
                                                <label for="edit-pass2">Confirmar senha nova</label>
                                                <input class="perfil-pass form-control" type="password" id="pass2"
                                                       name="pass2" size="25" maxlength="128">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearix"></div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="perfil-title">Configurações de Perfil</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <?php ?>
                                            <div class="form-image <?php echo ! $RHSUser->get_avatar() ? 'hide' : ''; ?>">
                                                <?php echo $RHSUser->get_avatar(); ?>
                                                <input type="hidden" name="avatar" size="60"
                                                       value="<?php echo $RHSUser->get_avatar_url(); ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="edit-avatar">Enviar Foto</label>
                                                <input class="form-control" type="file" id="edit-avatar" name="avatar">
                                                <p class="help-block">Sua imagem ou foto. As dimensões máximas são
                                                    100x100 e o tamanho máximo é 5MB</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="first_name">Primeiro nome</label>
                                                <input value="<?php echo $RHSUser->get_first_name(); ?>"
                                                       class="form-control" type="text" id="first_name"
                                                       name="first_name" size="60" maxlength="254">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="last_name">Segundo nome</label>
                                                <input class="form-control"
                                                       value="<?php echo $RHSUser->get_last_name(); ?>" type="text"
                                                       id="last_name" name="last_name" size="60" maxlength="254">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="edit-sobre">Sobre mim</label>
                                                <textarea class="form-control form-textarea" id="description"
                                                          name="description"
                                                          rows="4"><?php echo $RHSUser->get_description(); ?></textarea>
                                            </div>
                                        </div>
                                        <?php

                                        UFMunicipio::form( array(
                                            'content_before'       => '<div class="row">',
                                            'content_after'        => '</div>',
                                            'content_before_field' => '<div class="col-md-6"><div class="form-group float-label-control">',
                                            'content_after_field'  => '<div class="clearfix"></div></div></div>',
                                            'state_label'          => 'Estado &nbsp',
                                            'city_label'           => 'Cidade &nbsp',
                                            'select_class'         => 'form-control',
                                            'label_class'          => 'control-label col-sm-4',
                                            'selected_state'       => $RHSUser->get_state_id(),
                                            'selected_municipio'   => $RHSUser->get_city_id()
                                        ) ); ?>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label" for="edit-formacao">Formação</label>
                                                <input class="form-control form-width"
                                                       value="<?php echo $RHSUser->get_formation(); ?>" type="text"
                                                       id="formation" name="formation" size="30" maxlength="254"
                                                       data-role="tagsinput">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="edit-interesses">Interesses</label>
                                                <textarea class="form-control form-textarea" id="interest"
                                                          name="interest"
                                                          rows="4"><?php echo $RHSUser->get_interest(); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="perfil-title">Links</h4>
                                </div>
                                <div class="panel-body">
                                    <?php echo $RHSUser->show_user_links_to_edit($RHSUser->get_id());?>
                                </div>
                            </div>
                            <div class="panel panel-info" style="border: none">
                                <div class="panel-body">
                                    <div class="panel-button form-actions register">
                                        <button type="submit" id="btn-login" class="btn btn-danger">Salvar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php $RHSPerfil->clear_messages(); ?>
<?php get_footer( 'full' );