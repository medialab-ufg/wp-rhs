<?php get_header(); ?>

<div class="row">
	<!-- Container -->
	<div class="col-xs-12 col-md-12 login">
    		<header class="userpage">
				<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
					<div class="panel panel-info" >
						<div class="panel-heading">
							<div class="panel-title">Recuperar Senha</div>
						</div>     
						<?php
                        $login = new RHSLogin();
                        $errors = $login->lostpassword();
                        ?>
						<div class="panel-body" >
                            <?php if($errors){ ?>
                                <div class="alert alert-danger">
                                    <?php foreach ($errors as $erro){ ?>
                                            <p><?php echo $erro ?></p>
                                    <?php } ?>
                                </div>
                            <?php } ?>
							<form class="form-horizontal" method="post" role="form" action="">
								<div class="form-group float-label-control">
									<label for="user_login">Nome de usuário ou E-mail: </label>
									<input type="email" tabindex="1" name="user_login" id="user_login" class="form-control" value="" size="20" required>
								</div>
		                        <div class="form-group">
		                            <div class="col-sm-12 panel-captcha">
		                            	<?php echo $login->display_recuperar_captcha(); ?>
		                            </div>
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

<?php get_footer();