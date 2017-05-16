<?php get_header(); ?>
    <div class="row">
        <!-- Container -->
        <div class="login">
            <header class="userpage">
                <div class="col-md-12 col-sm-12">
                    <form method="post" class="form-horizontal" role="form" id="register" action="">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <div class="panel-title">Registro</div>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php if ( ! empty( $_SESSION['register_messages'] ) ) { ?>
                                            <?php foreach ( $_SESSION['register_messages'] as $type => $message ) { ?>
                                                <div class="alert alert-<?php echo $type ? 'danger' : 'success'; ?>">
                                                    <p><?php echo $message ?></p>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Dados do Login</legend>
                                                <div class="form-group float-label-control">
                                                    <label for="mail" class="col-sm-1 control-label">Email <span class="required">*</span></label>
                                                    <div class="col-sm-7">
                                                        <input class="form-control form-text" type="email" id="mail" name="mail" maxlength="254">
                                                        <input type="hidden" name="register_user_wp" value="1">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="help-block text-center">

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group float-label-control">
                                                    <label for="pass" class="col-sm-1 control-label">Senha <span class="required">*</span></label>
                                                    <div class="col-sm-7">
                                                        <input class="form-control form-text" type="password" id="pass" name="pass" maxlength="50">
                                                        <label title="Exibir senha" class="show_pass">
                                                            <i class="fa fa-eye-slash"></i>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="help-block text-center">

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group float-label-control">
                                                    <label for="pass" class="col-sm-1 control-label">Confirme a Senha <span class="required">*</span></label>
                                                    <div class="col-sm-7">
                                                        <input class="form-control form-text" type="password" id="pass2" name="pass2" maxlength="50">
                                                        <label title="Exibir senha" class="show_pass">
                                                            <i class="fa fa-eye-slash"></i>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="help-block text-center">

                                                        </div>
                                                    </div>
                                                </div>
                                        </fieldset>
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Dados Pessoais</legend>
                                                    <div class="form-group float-label-control">
                                                        <label for="first_name" class="col-sm-4 control-label">Primeiro Nome <span class="required">*</span></label>
                                                        <div class="col-sm-7">
                                                            <input class="form-control form-text" type="text" id="first_name" name="first_name" size="30" maxlength="30">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <div class="help-block text-center">

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group float-label-control">
                                                        <label for="second_name" class="col-sm-4 control-label">Segundo
                                                            Nome <span class="required">*</span></label>
                                                        <div class="col-sm-7">
                                                            <input class="form-control form-text" type="text" id="last_name" name="last_name" size="30" maxlength="30">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <div class="help-block text-center">

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group float-label-control">
                                                        <label for="description" class="col-sm-5 control-label">Sobre mim
                                                            <small class="form-text text-muted">(opcional)</small>
                                                        </label>
                                                        <div class="col-sm-7">
                                                            <textarea class="form-control form-textarea" id="description" name="description" cols="60" rows="5"></textarea>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <div class="help-block text-center">

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group float-label-control">
                                                        <div class="row">
                                                            <div class="col-sm-7">
                                                            <?php UFMunicipio::form( array(
                                                                'state_label' => 'Estado <span class="required">*</span>',
                                                                'city_label'  => 'Cidade <span class="required">*</span>'
                                                            ) ); ?>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <div class="help-block text-center">

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                        </fieldset>
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Captcha</legend>
                                            <?php
                                            global $RHSLogin;
                                            $RHSLogin->display_recuperar_captcha(); ?>
                                        </fieldset>
                                        <div class="panel-button form-actions register">
                                            <button id="btn-login" class="btn btn-danger">Cadastrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel block-info" style="background: #f2f3f5;">
                            <div class="panel-body">
                                <span class="block-email text-center"></span>
                            </div>
                        </div>
                    </form>
                </div>
            </header>
        </div>
    </div>

<?php get_footer();