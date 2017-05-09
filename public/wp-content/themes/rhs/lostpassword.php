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
						<?php $reCaptcha = new RECaptcha(); ?>
						<div class="panel-body" >
							<form class="form-horizontal" role="form">
								<div class="form-group float-label-control">
									<label for="user_login">Nome de usuário ou E-mail: </label>
									<input type="email" tabindex="1" name="log" id="user_login" class="form-control" value="" size="20" required>                               
								</div>
		                        <div class="form-group">
		                            <div class="col-sm-12 panel-captcha">
		                            	<?php echo $reCaptcha->display_recuperar_captcha(); ?>
		                            </div>
		                        </div>   
                                <div class="panel-button form-group">
                                    <div class="col-sm-12">
                                      <a id="btn-login" href="#" class="btn btn-success">Recuperar  </a>
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