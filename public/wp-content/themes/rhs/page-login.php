<?php get_header(); ?>

<div class="row">
	<!-- Container -->
	<div class="col-xs-12 col-md-12 login">
    		<header class="userpage">
				<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
					<div class="panel panel-info" >
						<div class="panel-heading">
							<div class="panel-title">Acessar sua Conta</div>
							<div class="panel-sub"><a href="#">Esqueceu a Senha?</a></div>
						</div>     

						<div class="panel-body" >
							<form class="form-horizontal" role="form">
								<div class="form-group float-label-control">
									<label for="user_login">Email</label>
									<input type="text" tabindex="1" name="log" id="user_login" class="form-control" value="" size="20" required>                               
								</div>
								<div class="form-group float-label-control">
									<label for="user_pass">Senha</label>
									<input type="password" tabindex="2" name="pwd" id="user_pass" class="form-control" value="" size="20">
								</div>
								<div class="form-group">
									<div class="checkbox">
										<label>
											<input id="login-remember" type="checkbox" name="remember" value="1"> Salvar dados de Acesso?
										</label>
									</div>
								</div>
                                <div class="panel-button form-group">
                                    <div class="col-sm-12 controls">
                                      <a id="btn-login" href="#" class="btn btn-success">Login  </a>
                                    </div>
                                </div>
								<div class="form-group">
									<div class="col-md-12">
										<div class="panel-other" >
											Você não tem uma conta?
											<a href="#" onClick="$('#loginbox').hide(); $('#signupbox').show()">
												Crie uma aqui!
											</a>
										</div>
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