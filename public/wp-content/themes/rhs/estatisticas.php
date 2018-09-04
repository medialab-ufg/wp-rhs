<?php get_header('full'); ?>
<?php global $RHSStatistics; ?>

<div id="rhs-statistics" class="row">
	<div class="col-md-12">
        <h2 class="text-center"> Estatísticas de Uso da Rede Humaniza SUS</h2>
        <hr>
        <?php if (!is_user_logged_in()): ?>
            <p class="text-center">
                <a href="<?php echo home_url("/login") ?>">Faça login</a> para continuar.
            </p>
        <?php else: ?>
            <div class="col-md-3 add_margin no-padding">
                <div class="panel">
                    <div class="panel-body">
                        <form id="parametros">
                            <input type="hidden" id="chart_type" value="bar">
                            <h4>Selecione dados referentes a: </h4>
                            <select id="type" name="type" class="form-control">
                                <?php echo $RHSStatistics->get_type(); ?>
                            </select>
                            <h5 class="text-center">Filtros Disponíveis</h5>
                            <!--User-->
                            <div id="filter_user" class="filter">
                                <label>
                                    <input type="checkbox" name="filter" value="all_users" data-name="Total" checked> Total
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

                                <hr>

                                <h6>Visitas</h6>
                                <label>
                                    <input type="checkbox" name="filter" value="posts_visits" data-name="Visitas aos posts" checked> Posts
                                </label>

                                <div id="date_period">
                                    <hr>
                                    <h6>Período</h6>
                                    <label>
                                        <input type="radio" name="filter" value="day" data-name="Dia"> Dia
                                    </label>

                                    <label>
                                        <input type="radio" name="filter" value="Week" data-name="Semana"> Semana
                                    </label>

                                    <label>
                                        <input type="radio" name="filter" value="month" data-name="Mês" checked> Mês
                                    </label>

                                    <label>
                                        <input type="radio" name="filter" value="year" data-name="Ano"> Ano
                                    </label>
                                </div>
                            </div>

                            <div id="date_filter">
                                <hr>
                                <h4>Selecione o período:</h4>
                                <label>Data Inicial</label>
                                <input type="date" name="initial_date" id="initial_date" class="form-control">
                                <label>Data Final</label> <br>
                                <input type="date" name="final_date" id="final_date" class="form-control">
                            </div>
                            <button id="enviar" type="submit" class="btn btn-primary pull-left">Gerar Gráfico</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-9 add_margin no-padding">
                <div class="panel">
                    <div class="panel-body">
                        <div id="estatisticas"></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
	</div>
</div>
<?php get_footer('full');