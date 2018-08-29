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
						<select id="type" name="type" class="form-control">
							<?php echo $RHSStatistics->get_type(); ?>
						</select>
                        <h5>Filtro</h5>
                        <!--User-->
                        <div id="filter_user" class="filter">
                            <label>
                                <input type="checkbox" name="filter" value="all" checked> Total
                            </label>
                            <label>
                                <input type="checkbox" name="filter" value="active" checked> Ativos
                            </label>
                            <label>
                                <input type="checkbox" name="filter" value="not_active" checked> Não ativos
                            </label>
                            <label>
                                <input type="checkbox" name="filter" value="author" checked> Autores
                            </label>
                            <label>
                                <input type="checkbox" name="filter" value="contributor" checked> Contribuidores
                            </label>
                            <label>
                                <input type="checkbox" name="filter" value="voter" checked> Votante
                            </label>
                        </div>

                        <!-- Increasing -->
                        <div id="filter_increasing" class="filter">
                            <hr>

                            <h6>Usuários</h6>
                            <label>
                                <input type="checkbox" name="filter" value="all_users" checked> Total
                            </label>
                            <label>
                                <input type="checkbox" name="filter" value="author"> Autores
                            </label>
                            <label>
                                <input type="checkbox" name="filter" value="contributor"> Contribuidores
                            </label>
                            <label>
                                <input type="checkbox" name="filter" value="voter"> Votante
                            </label>

                            <hr>

                            <h6>Postagens</h6>
                            <label>
                                <input type="checkbox" name="filter" value="all_posts" checked> Total
                            </label>
                            <label>
                                <input type="checkbox" name="filter" value="followed"> Seguidas
                            </label>
                            <label>
                                <input type="checkbox" name="filter" value="followed"> Comentadas
                            </label>

                            <hr>
                            <h6>Visitas</h6>
                            <label>
                                <input type="checkbox" name="filter" value="site_visits" checked> Ao site
                            </label>
                        </div>
                        <button id="enviar" type="submit" class="btn btn-primary pull-right">Gerar</button>
					</form>
				</div>
			</div>
		</div>

		<div class="col-md-9">
			<div class="panel">
				<div class="panel-body">
					<div id="estatisticas"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer('full');