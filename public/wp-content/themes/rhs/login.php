<?php get_header(); ?>

<div class="row">
	<!-- Container -->
	<div class="col-xs-12 col-md-12 login">
    		<header class="userpage">
				<div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">
					<div class="panel panel-info" >
						<div class="panel-heading">
							<div class="panel-title">Acessar sua Conta</div>
                            <div class="panel-sub"><a href="<?php echo wp_lostpassword_url(); ?>">Esqueceu a Senha?</a></div>
						</div>
						<div class="panel-body" >
                            <?php if(!empty($_SESSION['login_errors'])){ ?>
                            <div class="alert alert-danger">
                                <?php foreach ($_SESSION['login_errors'] as $erro){ ?>
                                        <p><?php echo $erro ?></p>
                                <?php } ?>
                            </div>
                                <?php unset($_SESSION['login_errors']); ?>
                            <?php } ?>
                            <?php if(!empty($_SESSION['login_messages'])){ ?>
                                <div class="alert alert-success">
                                    <?php foreach ($_SESSION['login_messages'] as $messages){ ?>
                                        <p><?php echo $messages ?></p>
                                    <?php } ?>
                                </div>
                                <?php unset($_SESSION['login_messages']); ?>
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
                            <div class="panel-other" >
                                Você não tem uma conta?
                                <a href="<?php echo wp_registration_url(); ?>">
                                    Crie uma aqui!
                                </a>
                            </div>
						</div>                     
					</div>  
				</div>
    		</header>
	</div>
</div>

<?php get_footer();
