<?php get_header(); ?>
<?php global $RHSUser; ?>
<?php global $RHSPerfil; ?>
    <div class="row">
        <!-- Container -->
        <div class="col-xs-12 col-md-12">
            <form role="form" id="perfil" method="post" action="" enctype="multipart/form-data">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="verDados">
                        <div class="jumbotron perfil">
                            <h3 class="perfil-title">Editar Perfil</h3>
                            <?php foreach ($RHSPerfil->messages() as $type => $messages){ ?>
                                <div class="alert alert-<?php echo $type == 'error' ? 'danger' : 'success' ; ?>">
                                    <?php foreach ($messages as $message){ ?>
                                        <p><?php echo $message ?></p>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="perfil-title">Configurações de Segurança da Conta</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="mail">Endereço de email</label>
                                                <input value="<?php echo $RHSUser->get_user_data('user_login') ?>" disabled class="form-control" type="text" id="mail" name="mail" size="60" maxlength="254">
                                                <input value="<?php echo $RHSUser->getKey(); ?>" name="edit_user_wp" type="hidden" />
                                                <p class="help-block">Um email válido. Todos os emails do sistema são
                                                    enviados para este endereço. O email não é visível para o público e
                                                    será usado apenas se você precisar recuperar a sua senha ou desejar
                                                    receber notícias ou notificações por email.</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="pass_old">Senha atual</label>
                                                <input class="perfil-pass form-control" type="password" id="pass_old" name="pass_old" size="25" maxlength="128">
                                            </div>
                                            <div class="form-group">
                                                <label for="edit-pass">Senha nova</label>
                                                <input class="perfil-pass form-control" type="password" id="pass" name="pass" size="25" maxlength="128">
                                            </div>
                                            <div class="form-group">
                                                <label for="edit-pass2">Confirmar senha nova</label>
                                                <input class="perfil-pass form-control" type="password" id="pass2" name="pass2" size="25" maxlength="128">
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
                                            <div class="form-image <?php echo !$RHSUser->getAvatar() ? 'hide' : ''; ?>">
                                                <img style="object-fit: cover;" src="<?php echo $RHSUser->getAvatarImage(); ?>" height="100" width="100"/>
                                                <input type="hidden" name="avatar" size="60" value="<?php echo $RHSUser->getAvatar(); ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="edit-avatar">Enviar Foto</label>
                                                <input class="form-control" type="file" id="edit-avatar" name="avatar">
                                                <p class="help-block">Sua imagem ou foto. As dimensões máximas são 100x100 e o tamanho máximo é 5MB</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="first_name">Primeiro nome</label>
                                                <input value="<?php echo $RHSUser->get_user_data('first_name'); ?>" class="form-control" type="text" id="first_name" name="first_name" size="60" maxlength="254">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="last_name">Segundo nome</label>
                                                <input class="form-control" value="<?php echo $RHSUser->get_user_data('last_name'); ?>" type="text" id="last_name" name="last_name" size="60" maxlength="254">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="edit-sobre">Sobre mim</label>
                                                <textarea class="form-control form-textarea" id="description" name="description" rows="4"><?php echo $RHSUser->get_user_data('description'); ?></textarea>
                                            </div>
                                        </div>
                                        <?php

                                        $location = get_user_ufmun($RHSPerfil->getUserId());

                                        UFMunicipio::form( array(
                                            'content_before' => '<div class="row">',
                                            'content_after' => '</div>',
                                            'content_before_field' => '<div class="col-md-6"><div class="form-group float-label-control">',
                                            'content_after_field' => '<div class="clearfix"></div></div></div>',
                                            'state_label'  => 'Estado &nbsp',
                                            'city_label'   => 'Cidade &nbsp',
                                            'select_class' => 'form-control',
                                            'label_class'  => 'control-label col-sm-4',
                                            'selected_state' => $location['uf']['id'],
                                            'selected_municipio' => $location['mun']['id'],
                                            'type' => 'user'
                                        ) ); ?>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label" for="edit-formacao">Formação</label>
                                                <input class="form-control form-width" value="<?php echo $RHSUser->get_user_data('rhs_formation'); ?>" type="text" id="formation" name="formation" size="30" maxlength="254" data-role="tagsinput">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="edit-interesses">Interesses</label>
                                                <textarea class="form-control form-textarea" id="interest" name="interest" rows="4"><?php echo $RHSUser->get_user_data('rhs_interest'); ?></textarea>
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
                                    <?php foreach ( $RHSUser->getLinks( true ) as $key => $link ) { ?>
                                    <div class="row links">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="edit-nome">Titulo</label>
                                                <input class="form-control" type="text" id="links-title-<?php echo $key ?>" name="links[title][]" size="60" maxlength="254" value="<?php echo $link['title'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="edit-nome">URL</label>
                                                <input class="form-control" type="text" id="links-url-<?php echo $key ?>" name="links[url][]" size="60" maxlength="254" value="<?php echo $link['url'] ?>">
                                                <a title="Remover link" class="remove-link" href="javascript:;">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="help-block">
                                                <a title="Adicionar Link" href="javascript:;" class="btn btn-info js-add-link">
                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
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
<?php get_footer();