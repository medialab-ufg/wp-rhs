<?php get_header('full'); ?>
<?php global $RHSLogin; ?>
    <div class="row">
        <!-- Container -->
        <div class="col-xs-12 col-md-12 login">
            <header class="userpage">
                <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                    <div class="panel panel-info" >
                        <div class="panel-heading">
                            <div class="panel-title">Recuperação de Senha</div>
                        </div>
                        <div class="panel-body" >
                            <?php foreach ($RHSLostPassword->messages() as $type => $messages){ ?>
                                <div class="alert alert-<?php echo ($type == 'error') ? 'danger' : 'success'; ?>">
                                    <?php foreach ($messages as $message){ ?>
                                        <p><?php echo $message ?></p>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <form autocomplete="off" class="form-horizontal" method="post" role="form" action="">
                                <div class="form-group float-label-control">
                                    <label for="user_login">Nova senha: </label>
                                    <input type="password" tabindex="1" name="pass1-text" id="pass1-text" class="form-control" value="" size="20" required>
                                </div>
                                <div class="panel-button form-group">
                                    <div class="col-sm-12">
                                        <button id="btn-login" type="submit" class="btn btn-success">Recuperar  </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
        </div>
    </div>
<?php get_footer('full');