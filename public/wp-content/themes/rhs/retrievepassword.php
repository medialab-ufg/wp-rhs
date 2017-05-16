<?php
global $RHSLogin;
$errors = $RHSLogin->retrievepassword();
?>
<?php get_header(); ?>
    <div class="row">
        <!-- Container -->
        <div class="login">
            <header class="userpage">
                <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">
                    <div class="panel panel-info" >
                        <div class="panel-heading">
                            <div class="panel-title">Recuperação de Senha</div>
                        </div>

                        <div class="panel-body" >
                            <?php if($errors){ ?>
                                <div class="alert alert-danger">
                                    <?php foreach ($errors as $erro){ ?>
                                        <p><?php echo $erro ?></p>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <form id="retrievepassword" class="form-horizontal" method="post" role="form" action="">
                                <div class="form-group float-label-control">
                                    <label for="user_login">Nova senha: </label>
                                    <input type="password" tabindex="1" name="pass1" id="pass1-text" class="form-control" value="" size="20" required />
                                    <?php if($_SESSION['rp_key']){ ?>
                                        <input type="hidden" name="rp_key" value="<?php echo $_SESSION['rp_key'] ?>">
                                    <?php } ?>
                                </div>
                                <div class="form-group float-label-control">
                                    <label for="user_login">Confirmar nova senha: </label>
                                    <input type="password" tabindex="1" name="pass2" id="pass2-text" class="form-control" value="" size="20" required />
                                </div>
                                <div class="panel-button form-actions pull-right">
                                        <button id="btn-login" type="submit" class="btn btn-success">Recuperar  </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
        </div>
    </div>
<?php get_footer();