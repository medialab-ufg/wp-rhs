<?php global $RHSLogin; ?>

    <!-- Container -->
    <div>
        <header>
            <div class="col-md-12" style="padding-top: 10px">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title">Acessar sua Conta</div>
                        <div class="panel-sub"><a href="<?php echo wp_lostpassword_url(); ?>">Esqueceu a Senha?</a></div>
                    </div>
                    <div class="panel-body">
                        <?php foreach ($RHSLogin->messages() as $type => $messages){ ?>
                            <?php foreach ($messages as $message){ ?>
                                <script>
                                    jQuery( function( $ ) {
                                        swal({title: '<?php echo $message ?>', html: true});
                                    });
                                </script>
                            <?php } ?>
                        <?php } ?>
                        <form id="login" class="form-horizontal" role="form" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">
                            <div class="form-group float-label-control">
                                <label for="user_login">Email</label>
                                <input type="email" tabindex="1" name="log" id="log" class="form-control" value="" >
                            </div>
                            <div class="form-group float-label-control">
                                <label for="user_pass">Senha</label>
                                <input type="password" tabindex="2" name="pwd" id="pwd" class="form-control" value="">
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input id="rememberme" name="rememberme"  type="checkbox" value="forever"> Lembrar
                                    </label>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="panel-button form-actions pull-right">
                                <button id="btn-login" class="btn btn-success" type="submit" >Login</button>
                            </div>
                            <div class="clearfix"></div>
                            <input type="hidden" name="redirect_to" id="redirect_to" value="<?php echo esc_attr( isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : '' ); ?>" />
                        </form>
                        <?php if(!RHSLogin::is_login_via_app()) : ?>
                            <div class="panel-other" >
                                Você não tem uma conta?
                                <a href="<?php echo wp_registration_url(); ?>">
                                    Crie uma aqui!
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </header>
    </div>
<?php $RHSLogin->clear_messages(); ?>