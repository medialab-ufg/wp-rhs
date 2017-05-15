<?php get_header(); ?>
    <?php $userOBJ = new RHSUser(get_the_author_meta( 'ID' )); ?>
    <div class="row">
        <!-- Container -->
        <div class="col-xs-12 col-md-12">
            <form class="form-horizontal" role="form" id="form-perfil">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="verDados">
                        <div class="jumbotron perfil">
                            <h3 class="perfil-title">Configurações</h3>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="perfil-title">Informação da Conta</h4>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="edit-mail">Endereço de email <span class="form-required" title="Este campo é obrigatório.">*</span></label>
                                                        <input class="form-control" type="text" id="edit-mail" name="mail" size="60" maxlength="254">
                                                        <p class="help-block">Um email válido. Todos os emails do sistema são enviados para este endereço. O email não é visível para o público e será usado apenas se você precisar recuperar a sua senha ou desejar receber notícias ou notificações por email.</p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="edit-pass">Senha</label>
                                                        <input class="perfil-pass form-control" type="password" id="edit-pass" name="pass" size="25" maxlength="128">
                                                        <label for="edit-pass2">Confirmar a Senha</label>
                                                        <input class="perfil-pass form-control" type="password" id="edit-pass2" name="pass2" size="25" maxlength="128">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearix"></div>    
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="perfil-title">Foto</h4>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <div class="col-sm-3">
                                                        <?php echo get_avatar(get_the_author_meta( 'ID' ), 30); ?>
                                                        </div>
                                                        <p class="help-block">Clique aqui para apagar a sua foto.</p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="edit-avatar">Enviar Foto</label>
                                                        <input class="form-control" type="file" id="edit-avatar" name="avatar">
                                                        <p class="help-block">Sua imagem ou foto. As dimensões máximas são 65x65 eo tamanho máximo é 5MB</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearix"></div>    
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="perfil-title">Perfil</h4>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group" data-toggle="tooltip" title="" data-original-title="Espaços são permitidos; A pontuação não é permitida exceto por pontos, hífenes, apóstrofos e sublinhados">
                                                        <label for="edit-nome">Nome Completo <span class="form-required" title="Este campo é obrigatório.">*</span></label>
                                                        <input class="form-control" type="text" id="edit-nome" name="nome" size="60" maxlength="254">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="edit-sobre">Sobre mim</label>
                                                        <textarea class="form-control form-textarea" id="edit-sobre" name="sobre" rows="4"></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12 form-inline">
                                                            <div class="col-xs-12 col-sm-3 col-sm-pull-0">
                                                            <?php UFMunicipio::form(array('state_label' => 'Estado &nbsp', 'city_label' => 'Cidade &nbsp', 'select_class' => 'form-control', 'separator' => '</div><div class="col-xs-12 col-sm-9 col-sm-pull-0">', 'label_class' => 'control-label')); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label" for="edit-formacao">Formação</label>
                                                            <input class="form-control form-width" type="text" id="edit-formacao" name="formacao" size="30" maxlength="254" data-role="tagsinput">
                                                            <p class="help-block">Separe por vírgula.</p>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="edit-interesses">Interesses</label>
                                                        <textarea class="form-control form-textarea" id="edit-interesses" name="interesses" rows="4"></textarea>
                                                    </div>
                                                    <div class="panel panel-default">
                                                    <div class="panel-body">
                                                        <label class="control-label" for="edit-formacao">Links</label>
                                                        <div class="form-group add-link">
                                                                <!--Parte onde o JS pega para Copiar e Criar um novo!-->
                                                                <div id="Links">
                                                                    <div class="col-sm-5">
                                                                        <label for="edit-titulo">Titulo</label>
                                                                        <input type="text" name="titulo" id="edit-titulo" class="form-control">
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <label for="edit-links">URL</label>
                                                                        <input type="url" name="url" id="edit-links" class="form-control">
                                                                    </div>
                                                                    <div class="col-sm-1 pos">
                                                                        <i><a onclick="removerLink(this)" title="Remover Link" class="remove" href="javascript:;">X</a></i>
                                                                    </div>
                                                                </div>
                                                                <!--Fim onde Copia-->
                                                        </div>
                                                    </div>
                                                    </div>
                                                    <div class="help-block">
                                                        <a class="btn btn-info js-add-link">
                                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearix"></div>    
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button class="btn btn-danger">Editar Dados</button>
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