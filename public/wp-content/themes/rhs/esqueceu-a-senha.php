<?php get_header('full'); ?>
<?php
global $RHSCaptcha;
global $RHSLostPassword;
?>
<div class="row">
	<!-- Container -->
	<div class="login">
        <header class="userpage">
            <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">
                <div class="panel panel-info" >
                    <div class="panel-heading">
                        <div class="panel-title">Recuperar Senha</div>
                    </div>
                    <?php $RHSLostPassword->run(); ?>
                    <div class="panel-body">
                        <?php foreach ($RHSLostPassword->messages() as $type => $messages){ ?>
                        <div class="alert alert-<?php echo ($type == 'error') ? 'danger' : 'success'; ?>">
                            <?php foreach ($messages as $message){ ?>
                                    <p><?php echo $message ?></p>
                            <?php } ?>
                        </div>
                        <?php } ?>
                        <form id="lostpassword" class="form-horizontal" method="post" role="form" action="">
                            <div class="form-group float-label-control">
                                <label for="user_login">Digite seu e-mail: </label>
                                <input type="email" tabindex="1" name="user_login" id="user_login" class="form-control" value="" size="20" required>
                                <input type="hidden" name="lostpassword_user_wp" value="<?php echo $RHSLostPassword->getKey(); ?>">
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12 panel-captcha">
                                    <?php echo $RHSCaptcha->display_recuperar_captcha(); ?>
                                </div>
                            </div>
                            <div class="panel-button form-actions pull-right">
                                  <button type="submit" id="btn-lostpassword" class="btn btn-success">Recuperar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </header>
	</div>
</div>
<?php $RHSLostPassword->clear_messages(); ?>
<?php get_footer('full');