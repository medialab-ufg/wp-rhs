<?php get_header('full'); ?>
<?php global $RHSStatistics; ?>
<style type="text/css">
#rhs-statistics h6 { font-weight: bolder; text-transform: uppercase; color: black }
#rhs-statistics #filter_user label,
#rhs-statistics #filter_increasing label {
    display: block;
    font-size: 12px;
}
#rhs-statistics #filter_user label input {
    
}
</style>
<div id="rhs-statistics" class="row">
	<div class="col-md-12" style="background: white">
        <h1 class="text-center" style="color: black"> Estatísticas de Uso da Rede Humaniza SUS</h1>
        <hr>
		<div class="col-md-4 add_margin">
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
                                <input type="checkbox" name="filter" value="comments" data-name="Comentarios"> Comentários
                            </label>
                        </div>

                        <!--Average-->
                        <div id="filter_average" class="filter">
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
                            <h6>Visitas</h6>
                            <label>
                                <input type="checkbox" name="filter" value="posts_visits" data-name="Visitas ao site" checked> Posts
                            </label>
                        </div>

                        <div id="date_filter">
                            <hr>
                            <h5>Entre</h5>
                            <label>Inicial</label>
                            <input type="date" name="initial_date" id="initial_date" class="form-control">
                            <label>Final</label>
                            <input type="date" name="final_date" id="final_date" class="form-control">
                        </div>
                        <button id="enviar" type="submit" class="btn btn-primary pull-right">Gerar</button>
					</form>
				</div>
			</div>
		</div>

		<div class="col-md-8 add_margin">
			<div class="panel">
				<div class="panel-body">
					<div id="estatisticas"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer('full');