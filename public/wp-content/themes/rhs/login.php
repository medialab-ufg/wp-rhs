<?php get_header(); ?>

<div class="row">
	<!-- Container -->
	<div class="col-xs-12 col-md-12 login">
    		<header class="userpage">
				<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
					<div class="panel panel-info" >
						<div class="panel-heading">
							<div class="panel-title">Acessar sua Conta</div>
							<div class="panel-sub"><a href="<?php wp_login ?>">Esqueceu a Senha?</a></div>
						</div>
						<div class="panel-body" >
                            <?php if(!empty($_SESSION['login_errors'])){ ?>
                            <div class="alert alert-danger">
                                <?php foreach ($_SESSION['login_errors'] as $erros){ ?>
                                    <?php foreach ($erros as $erro){ ?>
                                        <p><?php echo $erro ?></p>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                                <?php unset($_SESSION['login_errors']); ?>
                            <?php } ?>
							<form class="form-horizontal" role="form" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">
								<div class="form-group float-label-control">
									<label for="user_login">Email</label>
									<input type="email" tabindex="1" name="log" id="user_login" class="form-control" value="" size="20" required>
								</div>
								<div class="form-group float-label-control">
									<label for="user_pass">Senha</label>
									<input type="password" tabindex="2" name="pwd" id="user_pass" class="form-control" value="" size="20" required>
								</div>
								<div class="form-group">
									<div class="checkbox">
										<label>
											<input id="login-remember" name="rememberme"  type="checkbox" value="forever"> Salvar dados de Acesso?
										</label>
									</div>
								</div>
                                <div class="panel-button form-group">
                                    <div class="col-sm-12 controls">
                                    	<input id="btn-login" class="btn btn-success" type="submit" value="Login">
                                    </div>
                                </div>
								<div class="form-group">
									<div class="col-md-12">
										<div class="panel-other" >
											Você não tem uma conta?
											<a href="<?php echo wp_registration_url(); ?>">
												Crie uma aqui! 
											</a>
										</div>
									</div>
								</div>
                                <input type="hidden" name="redirect_to" value="<?php echo esc_attr( isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : '' ); ?>" />
							</form>    
						</div>                     
					</div>  
				</div>
    		</header>
	</div>
</div>

<?php get_footer();
