<?php get_header(); ?>

<div class="row">
	<!-- Container -->
	<div class="col-xs-12 col-md-12 login">
    	<header class="userpage">
			<form class="form-horizontal" role="form" id="form-cadastro" action="../">
				<div class="col-md-6 col-md-offset-2 col-sm-8">                    
					<div class="panel panel-info" >
						<div class="panel-heading">
							<div class="panel-title">Dados de Login</div>
						</div>
						<div class="panel-body" >
								<div class="form-group float-label-control">
									<label for="mail" class="col-sm-1 control-label">Email</label>
									<div class="col-sm-12">
										<input class="form-control form-text" type="email" id="mail" name="mail" maxlength="254">
									</div>
								</div>
								<div class="form-group float-label-control">
									<label for="pass" class="col-sm-1 control-label">Senha</label>
									<div class="col-sm-12">
										<input class="form-control form-text" type="password" id="pass" name="pass" maxlength="50"> 
										<div class="checkbox">
											<label for="show_pass">
												<input type="checkbox" name="show_pass" id="show_pass"> Exibir Senha
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
								<label for="first_name" class="col-sm-4 control-label">Primeiro Nome</label>
								<div class="col-sm-12">
									<input class="form-control form-text" type="text" id="first_name" name="first_name" size="60" maxlength="60">
								</div>
							</div>
                            <div class="form-group float-label-control">
                                <label for="second_name" class="col-sm-4 control-label">Segundo Nome</label>
                                <div class="col-sm-12">
                                    <input class="form-control form-text" type="text" id="second_name" name="second_name" size="60" maxlength="60">
                                </div>
                            </div>
							<div class="form-group float-label-control">
								<label for="description" class="col-sm-5 control-label">Sobre mim <small class="form-text text-muted">(opcional)</small></label>
								<div class="col-sm-12">
									<textarea class="form-control form-textarea" id="description" name="description" cols="60" rows="5"></textarea>
								</div>
							</div>
							<div class="form-group float-label-control">
								<div class="col-sm-12 form-inline">
									<?php UFMunicipio::form(array('state_label' => 'UF: &nbsp', 'city_label' => 'Cidade: &nbsp', 'select_class' => 'form-control', 'separator' => ' ', 'label_class' => 'control-label')); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="panel panel-info" >
						<div class="panel-heading">
							<div class="panel-title">Captcha</div>
						</div>     
						<div class="panel-body" >
							<?php
                            global $RHSLogin;
							$RHSLogin->display_recuperar_captcha(); ?>
						</div>                  
					</div>
                    <div class="form-group">
                        <div class="col-sm-12 controls">
                          <input type="submit" id="btn-login" href="../" class="btn btn-danger" value="CRIAR NOVA CONTA">
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