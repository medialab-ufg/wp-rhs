<?php get_header('full'); ?>
<?php global $RHSStatistics; ?>

<div class="row">
	<div class="col-md-12">
		<h1 class="titulo-page">Estatísticas</h1>
		<!--Barra lateral-->
		<div class="col-md-3">
			<div class="panel">
				<div class="panel-body">
					<form id="parametros">
						<h4>Relacionada a: </h4>
						<select id="type" class="form-control">
							<?php echo $RHSStatistics->get_type(); ?>
						</select>


                        <button id="enviar" type="submit" class="btn btn-primary pull-right">Gerar</button>
					</form>
				</div>
			</div>
		</div>

		<div class="col-md-9">
			<div class="panel">
				<div class="panel-body">
					<div id="estatisticas"><?php  ?></div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer('full');