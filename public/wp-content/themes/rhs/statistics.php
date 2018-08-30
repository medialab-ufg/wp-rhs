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
                        <input type="hidden" id="chart_type" value="bar">
						<h4>Relacionada a: </h4>
						<select id="type" name="type" class="form-control">
							<?php echo $RHSStatistics->get_type(); ?>
						</select>
                        <h5>Filtro</h5>
                        <!--User-->
                        <div id="filter_user" class="filter">
                            <label>
                                <input type="checkbox" name="filter" value="all" data-name="Total" checked> Total
                            </label>
                            <label>
                                <input type="checkbox" name="filter" value="active" data-name="Ativos" checked> Ativos
                            </label>
                            <label>
                                <input type="checkbox" name="filter" value="not_active" data-name="Não ativos" checked> Não ativos
                            </label>
                            <label>
                                <input type="checkbox" name="filter" value="author" data-name="Autores" checked> Autores
                            </label>
                            <label>
                                <input type="checkbox" name="filter" value="contributor" data-name="Contribuidores" checked> Contribuidores
                            </label>
                            <label>
                                <input type="checkbox" name="filter" value="voter" data-name="Votantes" checked> Votantes
                            </label>
                        </div>

                        <!-- Increasing -->
                        <div id="filter_increasing" class="filter">
                            <hr>

                            <h6>Usuários</h6>
                            <label>
                                <input type="checkbox" name="filter" value="all_users" data-name="Cadastros" checked> Cadastros
                            </label>
                            <label>
                                <input type="checkbox" name="filter" value="author" data-name="Autores"> Autores
                            </label>
                            <label>
                                <input type="checkbox" name="filter" value="contributor" data-name="Contribuidores"> Contribuidores
                            </label>
                            <label>
                                <input type="checkbox" name="filter" value="voter" data-name="Votantes"> Votantes
                            </label>

                            <hr>

                            <h6>Postagens</h6>
                            <label>
                                <input type="checkbox" name="filter" value="all_posts" data-name="Postagens" checked> Postagens
                            </label>
                            <label>
                                <input type="checkbox" name="filter" value="followed" data-name="Postagens seguidas"> Seguidas
                            </label>
                            <label>
                                <input type="checkbox" name="filter" value="followed" data-name="Comentarios em postagens"> Comentadas
                            </label>

                            <hr>
                            <h6>Visitas</h6>
                            <label>
                                <input type="checkbox" name="filter" value="site_visits" data-name="Visitas ao site" checked> Ao site
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