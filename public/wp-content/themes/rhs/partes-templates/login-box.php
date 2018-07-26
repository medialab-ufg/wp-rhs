
<?php global $RHSLogin; ?>

<div class="col-xs-12 login-box-wrapper">

    <h3 class="text-center"> Acesse sua Conta </h3>

    <?php foreach ($RHSLogin->messages() as $type => $messages){ ?>
        <?php foreach ($messages as $message){ ?>
            <script>
                jQuery( function( $ ) {
                    swal({title: '<?php echo $message ?>', html: true});
                });
            </script>
        <?php } ?>
    <?php } ?>

    <form id="login" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post" role="form" autocomplete="off" class="col-xs-12">
        <div class="form-group">
            <label for="username"> E-mail </label>
            <input type="email" placeholder="Digite seu e-mail cadastrado" name="log" id="log" tabindex="1" class="form-control" value="" autocomplete="off">
        </div>

        <div class="form-group">
            <label for="password"> Senha </label>
            <input type="password" name="pwd" id="pwd" tabindex="2" class="form-control" placeholder="Digite sua senha" autocomplete="off">
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-xs-7">
                    <input id="rememberme" name="rememberme" type="checkbox" value="forever" tabindex="3">
                    <label for="rememberme"> Permanecer Logado </label>
                </div>
                <div class="col-xs-5 pull-right">
                    <input type="hidden" name="currentURL" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
                    <input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-rhs" value="Entrar">
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <a href="<?php echo wp_lostpassword_url(); ?>" tabindex="5" class="forgot-password">Esqueceu sua senha?</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php $RHSLogin->clear_messages(); ?>