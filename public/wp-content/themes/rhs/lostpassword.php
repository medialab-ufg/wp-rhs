<?php get_header(); ?>

<div class="row">
	<!-- Container -->
	<div class="login">
    		<header class="userpage">
				<div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">
					<div class="panel panel-info" >
						<div class="panel-heading">
							<div class="panel-title">Recuperar Senha</div>
						</div>     
						<?php
                        global $RHSLogin;
                        $result = $RHSLogin->lostpassword();
                        ?>
						<div class="panel-body">
                            <?php if($result){ ?>
                                <?php foreach ($result as $type => $msgs){ ?>
                                <div class="alert alert-<?php echo ($type == 'error') ? 'danger' : 'success'; ?>">
                                    <?php foreach ($msgs as $msg){ ?>
                                            <p><?php echo $msg ?></p>
                                    <?php } ?>
                                </div>
                                <?php } ?>
                            <?php } ?>
							<form id="lostpassword" class="form-horizontal" method="post" role="form" action="">
								<div class="form-group float-label-control">
									<label for="user_login">Digite seu e-mail: </label>
									<input type="email" tabindex="1" name="user_login" id="user_login" class="form-control" value="" size="20" required>
								</div>
		                        <div class="form-group">
		                            <div class="col-sm-12 panel-captcha">
		                            	<?php echo $RHSLogin->display_recuperar_captcha(); ?>
		                            </div>
		                        </div>
                                <div class="panel-button form-actions pull-right">
                                      <button id="btn-login" type="submit" class="btn btn-success">Recuperar</button>
                                </div>   
							</form>    
						</div>                     
					</div>  
				</div>
    		</header>
	</div>
</div>
<?php get_footer();