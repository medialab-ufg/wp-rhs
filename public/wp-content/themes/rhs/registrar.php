<?php get_header('full');
global $RHSRegister;
global $RHSCaptcha;
?>
    <div class="row">
        <!-- Container -->
        <div class="register">
            <header class="userpage userpage_register">
                <div class="col-md-12 col-sm-12">
                    <form autocomplete="off" method="post" class="form-horizontal" role="form" id="register" action="">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <div class="panel-title">Registro</div>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php foreach ($RHSRegister->messages() as $type => $messages){ ?>
                                            <div class="alert alert-<?php echo $type == 'error' ? 'danger' : 'success' ; ?>">
                                                <?php foreach ($messages as $message){ ?>
                                                    <p><?php echo $message ?></p>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Dados do Login</legend>

                                            <div class="form-group float-label-control">
                                                <label for="mail" class="col-sm-1 control-label">Email <span class="required">*</span></label>
                                                <div class="col-sm-7">
                                                    <input class="form-control form-text" type="email" id="mail" name="mail" maxlength="254">
                                                    <input type="hidden" name="register_user_wp" value="<?php echo $RHSRegister->getKey(); ?>">
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="help-block text-center">

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group float-label-control" id="confirm_email">
                                                <label for="confirm_mail" class="col-sm-1 control-label"> Confirmar Email <span class="required">*</span></label>
                                                <div class="col-sm-7">
                                                    <input class="form-control form-text" type="text" id="confirm_mail" name="confirm_mail" maxlength="50">
                                                </div>
                                            </div>


                                            <?php if( rand(0,2) == 0 ): ?>
                                                <div class="form-group float-label-control" id="u_login">
                                                    <label for="user_login" class="col-sm-1 control-label">Login <span class="required">*</span></label>
                                                    <div class="col-sm-7">
                                                        <input class="form-control form-text" type="text" id="user_login" name="user_login" maxlength="254">
                                                    </div>
                                                </div>
                                            <?php endif; ?>

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

                                            <?php if( rand(5,7) == 6 ): ?>
                                                <div class="form-group float-label-control" id="phone">
                                                    <label for="phone"  class="col-sm-4 control-label">Telefone</label>
                                                    <div class="col-sm-7">
                                                        <input class="form-control form-text" type="text" id="phone" name="phone" value="">
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <div class="form-group float-label-control">
                                                <label for="last_name" class="col-sm-4 control-label">Segundo
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
                                                            'content_before' => '<div class="row">',
                                                            'content_after' => '</div>',
                                                            'content_before_field' => '<div class="col-md-6"><div class="form-group float-label-control">',
                                                            'content_after_field' => '<div class="clearfix"></div></div></div>',
                                                            'state_label'  => 'Estado &nbsp',
                                                            'city_label'   => 'Cidade &nbsp',
                                                            'select_class' => 'form-control',
                                                            'label_class'  => 'control-label col-sm-4'
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
                                            <div class="form-group float-label-control capt">
                                                <div class="col-sm-7">
                                                    <?php $RHSCaptcha->display_recuperar_captcha(); ?>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="help-block text-center">

                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <div class="panel-button form-actions register">
                                            <button type="submit" id="btn-login" class="btn btn-danger">Cadastrar</button>
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
<?php $RHSRegister->clear_messages(); ?>
<?php get_footer('full');