<?php get_header(); ?>

    <div class="row">
        <!-- Container -->
        <div class="col-xs-12 col-md-12">
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
                                                    <input class="form-control form-text" type="text" id="edit-mail" name="mail" size="60" maxlength="254">
                                                    <p class="info-text">Um email válido. Todos os emails do sistema são enviados para este endereço. O email não é visível para o público e será usado apenas se você precisar recuperar a sua senha ou desejar receber notícias ou notificações por email.</p>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="edit-pass">Senha</label>
                                                <input class="perfil-pass form-control form-text" type="password" id="edit-pass" name="pass" size="25" maxlength="128">
                                                <label for="edit-pass2">Confirmar a Senha</label>
                                                <input class="perfil-pass form-control form-text" type="password" id="edit-pass2" name="pass2" size="25" maxlength="128">
                                            </div>
                                        </div>
                                        <div class="clearix"></div>    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php get_footer();