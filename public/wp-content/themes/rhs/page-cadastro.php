<?php get_header(); ?>

<div class="row">
	<!-- Container -->
	<div class="col-xs-12 col-md-12 login">
    		<header class="userpage">
				<div class="col-md-6 col-md-offset-1 col-sm-8">                    
					<div class="panel panel-info" >
						<div class="panel-heading">
							<div class="panel-title">Criar Conta</div>
						</div>     

						<div class="panel-body" >
							<form class="form-horizontal" role="form">
								<div class="form-group float-label-control">
									<label for="edit-mail">Email</label>
									<input class="form-control form-text" type="text" id="edit-mail" name="mail" size="60" maxlength="254" required>                      
								</div>
								<div class="form-group float-label-control">
									<label for="edit-name">Nome Completo</label>
									<input class="form-control form-text" type="text" id="edit-name" name="name" size="60" maxlength="60" required>
								</div>
								<div class="form-group">
									<label for="sobre-mim">Sobre mim <em>(opcional)</em></label>
									<textarea class="form-control form-textarea" id="sobre-mim" name="sobre-mim" cols="60" rows="5"></textarea>
								</div>
                                <div class="form-group">
                                    <div class="col-sm-12 controls">
                                      <a id="btn-login" href="#" class="btn btn-danger">CRIAR NOVA CONTA  </a>
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