<?php get_header(); ?>

    <div class="row">
        <!-- Container -->
        <div class="col-xs-12 col-md-12">
            <form role="form" id="form-perfil">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="verDados">
                        <div class="jumbotron perfil">
                            <h3 class="perfil-title">Editar Perfil</h3>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="perfil-title">Configurações de Segurança da Conta</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="edit-mail">Endereço de email <i
                                                            class="fa fa-question-circle "></i></label>
                                                <input disabled class="form-control" type="text" id="edit-mail"
                                                       name="mail" size="60" maxlength="254">
                                                <p class="help-block">Um email válido. Todos os emails do sistema são
                                                    enviados para este endereço. O email não é visível para o público e
                                                    será usado apenas se você precisar recuperar a sua senha ou desejar
                                                    receber notícias ou notificações por email.</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="edit-pass">Senha atual <i
                                                            class="fa fa-question-circle "></i></label>
                                                <input class="perfil-pass form-control" type="password" id="edit-pass"
                                                       name="pass" size="25" maxlength="128">
                                                <label for="edit-pass">Senha nova</label>
                                                <input class="perfil-pass form-control" type="password" id="edit-pass"
                                                       name="pass" size="25" maxlength="128">
                                                <label for="edit-pass2">Confirmar senha nova</label>
                                                <input class="perfil-pass form-control" type="password" id="edit-pass2"
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
                                            <div class="form-group">
                                                <label for="edit-avatar">Enviar Foto</label>
                                                <input class="form-control" type="file" id="edit-avatar" name="avatar">
                                                <p class="help-block">Sua imagem ou foto. As dimensões máximas são 65x65
                                                    eo tamanho máximo é 5MB</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="edit-nome">Primeiro nome</label>
                                                <input class="form-control" type="text" id="edit-nome" name="nome"
                                                       size="60" maxlength="254">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="edit-nome">Segundo nome</label>
                                                <input class="form-control" type="text" id="edit-nome" name="nome"
                                                       size="60" maxlength="254">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="edit-sobre">Sobre mim</label>
                                                <textarea class="form-control form-textarea" id="edit-sobre"
                                                          name="sobre" rows="4"></textarea>
                                            </div>
                                        </div>
                                        <?php UFMunicipio::form( array(
                                            'content_before' => '<div class="row">',
                                            'content_after' => '</div>',
                                            'content_before_field' => '<div class="col-md-6"><div class="form-group float-label-control">',
                                            'content_after_field' => '<div class="clearfix"></div></div></div>',
                                            'state_label'  => 'Estado &nbsp',
                                            'city_label'   => 'Cidade &nbsp',
                                            'select_class' => 'form-control',
                                            'label_class'  => 'control-label col-sm-4'
                                        ) ); ?>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label" for="edit-formacao">Formação</label>
                                                <input class="form-control form-width" type="text" id="edit-formacao" name="formacao">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="edit-interesses">Interesses</label>
                                                <textarea class="form-control form-textarea" id="edit-interesses"
                                                          name="interesses" rows="4"></textarea>
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
                                    <div class="row links">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="edit-nome">Titulo</label>
                                                <input class="form-control" type="text" id="edit-nome" name="nome"
                                                       size="60" maxlength="254">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="edit-nome">URL</label>
                                                <input class="form-control" type="text" id="edit-nome" name="nome"
                                                       size="60" maxlength="254">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="help-block">
                                                <a class="btn btn-info js-add-link">
                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php get_footer();