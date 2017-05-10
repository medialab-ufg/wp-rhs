<?php get_header(); ?>

<div class="row">
	<!-- Container -->
	<div class="col-xs-12 col-md-12 login">
    	<header class="userpage">

			<form class="form-horizontal" role="form" id="form-cadastro">
				<div class="col-md-6 col-md-offset-1 col-sm-8">                    
					<div class="panel panel-info" >
						<div class="panel-heading">
							<div class="panel-title">Dados de Login</div>
						</div>     

						<div class="panel-body" >
								<div class="form-group float-label-control">
									<label for="edit-mail" class="col-sm-1 control-label">Email</label>
									<div class="col-sm-12">
										<input class="form-control form-text" type="email" id="edit-mail" name="mail" maxlength="254" required>
									</div>
								</div>
								<div class="form-group float-label-control">
									<label for="pass" class="col-sm-1 control-label">Senha</label>
									<div class="col-sm-12">
										<input class="form-control form-text" type="password" id="pass" name="pass" maxlength="50" required> 
										<div class="checkbox">
											<label>
												<input type="checkbox"> Exibir Senha
											</label>
										</div>
									</div>
								</div>
						</div>                  
					</div>  
					<div class="panel panel-info">
						<div class="panel-heading">
							<div class="panel-title">Dados Pessoais</div>
						</div>
						<div class="panel-body">
							<div class="form-group float-label-control">
								<label for="edit-name" class="col-sm-4 control-label">Nome Completo</label>
								<div class="col-sm-12">
									<input class="form-control form-text" type="text" id="edit-name" name="name" size="60" maxlength="60" required>
								</div>
							</div>
							<div class="form-group float-label-control">
								<label for="sobre-mim" class="col-sm-5 control-label">Sobre mim</label>
								<div class="col-sm-12">
									<textarea class="form-control form-textarea" id="sobre-mim" name="sobre-mim" cols="60" rows="5"></textarea>
								</div>
							</div> 
						</div>
					</div>
                    <div class="form-group">
                        <div class="col-sm-12 controls">
                          <a id="btn-login" href="#" class="btn btn-danger">CRIAR NOVA CONTA  </a>
                        </div>
                    </div>
				</div>
			</form>
				<div class="col-md-4">
					<div class="panel block-info" style="background: #f2f3f5;">    
						<div class="panel-body">
							<span class="block-email"></span>
						</div> 
					</div>
				</div>
    	</header>
	</div>
</div>

<?php get_footer();