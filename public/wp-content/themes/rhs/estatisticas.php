<?php get_header('full'); ?>
<?php global $RHSStatistics; ?>
<div id="rhs-statistics" class="row">
	<div class="col-md-12">
        <h3 class="text-center" style="margin-top: 30px;margin-bottom: 0;">
            Estatísticas de Uso da Rede Humaniza SUS

            <a href="#estatisticas-filtros">
                <button class="btn btn-default pull-right">
                    <i class="fa fa-filter" aria-hidden="true"></i> Ver filtros
                </button>
            </a>
        </h3>
        <hr style="margin-bottom: 0">
        <?php if (!is_user_logged_in()): ?>
            <p class="text-center">
                <a href="<?php echo home_url("/login") ?>">Faça login</a> para continuar.
            </p>
        <?php else: ?>
            <div class="col-md-12 add_margin no-padding">
                <div id="loader" class="text-center" style="margin-top: 20px;">
                    <p>Carregando ... </p>
                    <img src="<?php echo get_template_directory_uri()?>/inc/comments/images/loadingAnimation.gif">
                </div>
                <div id="estatisticas"></div>
                <hr>
            </div>

            <div class="col-md-12 no-padding" style="margin-bottom: 40px;">

                <div class="container"><h4 style="color: black"> </h4></div>
                <div id="filtros-estatisticas" class="container col-md-7 no-padding">
                    <a id="estatisticas-filtros"></a>
                    <ul class="nav nav-pills">
                        <li class="active" data-type="increasing"> <a href="#quantidade" data-toggle="tab">Quantidade por data</a> </li>
                        <li data-type="average"><a href="#media" data-toggle="tab"> Média </a></li>
                        <li data-type="count"><a href="#total" data-toggle="tab"> Total </a> </li>
                    </ul>

                    <input type="hidden" id="selected_data_type" value="">
                    <input type="hidden" id="id_div" value="#quantidade ">
                    <div class="tab-content clearfix panel panel-default" style="padding: 10px 20px;">
                        <div class="tab-pane active" id="quantidade">
                            <!-- Increasing -->
                            <div id="filter_increasing" class="filter-">
                                <div class="col-md-12 no-padding">
                                    <div class="col-md-4 no-padding">
                                        <h6>Usuários cadastrados</h6>
                                        <label>
                                            <input type="checkbox" name="filter" value="all_users" data-name="Cadastros"> Total
                                        </label>
                                        <label>
                                            <input type="checkbox" name="filter" value="active_author" data-name="Autores"> Autores
                                        </label>
                                        <label>
                                            <input type="checkbox" name="filter" value="active_contributor" data-name="Contribuidores"> Contribuidores
                                        </label>
                                        <!--<label>
                                            <input type="checkbox" name="filter" value="author" data-name="Autores"> Autores
                                        </label>
                                        <label>
                                            <input type="checkbox" name="filter" value="contributor" data-name="Contribuidores"> Contribuidores
                                        </label>-->
                                        <label>
                                            <input type="checkbox" name="filter" value="voter" data-name="Votantes"> Votantes
                                        </label>
                                    </div>
                                    <div class="col-md-4 no-padding">
                                        <h6>Postagens</h6>
                                        <label>
                                            <input type="checkbox" name="filter" value="all_posts" data-name="Postagens" checked> Postagens
                                        </label>
                                        <label>
                                            <input type="checkbox" name="filter" value="followed" data-name="Postagens seguidas"> Seguidas
                                        </label>
                                        <label>
                                            <input type="checkbox" name="filter" value="comments" data-name="Comentários"> Comentários
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="media">
                            <!--Average-->
                            <div id="filter_average" class="filter-">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-4 no-padding">
                                            <h6>Usuários</h6>
                                            <label>
                                                <input type="checkbox" name="filter" value="all_users" data-name="Cadastros" checked> Cadastros
                                            </label>
                                            <label>
                                                <input type="checkbox" name="filter" value="active_author" data-name="Autores" checked> Autores
                                            </label>
                                            <label>
                                                <input type="checkbox" name="filter" value="active_contributor" data-name="Contribuidores" checked> Contribuidores
                                            </label>
                                            <!--<label>
                                                <input type="checkbox" name="filter" value="author" data-name="Autores"> Autores
                                            </label>
                                            <label>
                                                <input type="checkbox" name="filter" value="contributor" data-name="Contribuidores"> Contribuidores
                                            </label>-->
                                            <label>
                                                <input type="checkbox" name="filter" value="voter" data-name="Votantes"> Votantes
                                            </label>

                                        </div>
                                        <div class="col-md-4">
                                            <h6>Postagens</h6>
                                            <label>
                                                <input type="checkbox" name="filter" value="all_posts" data-name="Postagens" checked> Postagens
                                            </label>
                                            <label>
                                                <input type="checkbox" name="filter" value="followed" data-name="Postagens seguidas"> Seguidas
                                            </label>
                                            <label>
                                                <input type="checkbox" name="filter" value="comments" data-name="Comentários"> Comentários
                                            </label>
                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-4 no-padding">
                                            <hr>
                                            <h6>Compartilhamentos</h6>
                                            <label style="display: inline-block">
                                                <input type="checkbox" name="filter" value="facebook_share" data-name="Facebook"> Facebook
                                            </label>
                                            &nbsp;
                                            <label style="display: inline-block">
                                                <input type="checkbox" name="filter" value="twitter_share" data-name="Twitter"> Twitter
                                            </label>
                                        </div>
                                        <div class="col-md-2">
                                            <hr>
                                            <h6>Visitas</h6>
                                            <label>
                                                <input type="checkbox" name="filter" value="posts_visits" data-name="Visitas aos posts" checked> Posts
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <div id="date_period">
                                                <hr>
                                                <h6>Período</h6>
                                                <label style="display: inline-block">
                                                    <input type="radio" name="filter" value="day" data-name="Dia"> Dia
                                                </label>

                                                &nbsp;
                                                <label style="display: inline-block">
                                                    <input type="radio" name="filter" value="Week" data-name="Semana"> Semana
                                                </label>
                                                &nbsp;
                                                <label style="display: inline-block">
                                                    <input type="radio" name="filter" value="month" data-name="Mês" checked> Mês
                                                </label>
                                                &nbsp;
                                                <label style="display: inline-block">
                                                    <input type="radio" name="filter" value="year" data-name="Ano"> Ano
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="tab-pane" id="total">
                            <!-- Total -->
                            <div id="filter_count" class="filter-">
                                <div class="col-md-12 no-padding">
                                    <div class="col-md-3 no-padding">
                                        <h6>Usuário</h6>
                                        <label>
                                            <input type="checkbox" name="filter" value="all_users" data-name="Total de usuários" checked> Total
                                        </label>
                                        <label>
                                            <input type="checkbox" name="filter" value="active_users" data-name="Usuários ativos" checked> Ativos
                                        </label>
                                        <label>
                                            <input type="checkbox" name="filter" value="not_active_users" data-name="Usuários não ativos" checked> Não ativos
                                        </label>
                                        <label>
                                            <input type="checkbox" name="filter" value="active_author" data-name="Usuários que realizaram posts" checked> Autores
                                        </label>
                                        <label>
                                            <input type="checkbox" name="filter" value="active_contributor" data-name="Usuários que realizaram comentários" checked> Contribuidores
                                        </label>
                                        <!--<label>
                                            <input type="checkbox" name="filter" value="author" data-name="Autores"> Autores
                                        </label>
                                        <label>
                                            <input type="checkbox" name="filter" value="contributor" data-name="Contribuidores"> Contribuidores
                                        </label>-->
                                        <label>
                                            <input type="checkbox" name="filter" value="voter" data-name="Votantes" checked> Votantes
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <h6>Postagens</h6>
                                        <label>
                                            <input type="checkbox" name="filter" value="posts_visits" data-name="Visualização de posts"> Visualizações
                                        </label>
                                        <label>
                                            <input type="checkbox" name="filter" value="followed" data-name="Seguir em posts"> Seguir
                                        </label>
                                        <label>
                                            <input type="checkbox" name="filter" value="comments" data-name="Comentários"> Comentários
                                        </label>
                                    </div>

                                    <div class="col-md-3">
                                        <h6>Compartilhamentos</h6>
                                        <label>
                                            <input type="checkbox" name="filter" value="facebook_share" data-name="Compartilhamentos no Facebook" checked> Compartilhamentos no Facebook
                                        </label>
                                        <label>
                                            <input type="checkbox" name="filter" value="twitter_share" data-name="Compartilhamentos no Twitter"> Compartilhamentos no Twitter
                                        </label>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div id="data-estatisticas" class="container col-md-5">
                    <ul class="nav nav-pills">
                        <li><a href="#data" data-toggle="tab"> Período </a> </li>
                    </ul>
                    <div class="tab-content clearfix panel panel-default" style="padding: 10px 20px;">
                        <div class="tab-pane active" id="data">
                            <div id="date_filter">
                                <div class="col-md-12 no-padding">
                                    <div class="col-md-6 no-padding">
                                        <label>Data Inicial</label>
                                        <input type="date" name="initial_date" id="initial_date" class="form-control">
                                    </div>
                                    <div class="col-md-6" style="padding-right: 0">
                                        <label>Data Final</label>
                                        <input type="date" name="final_date" id="final_date" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 no-padding form-wrapper">
                                <form id="parametros" name="parametros">
                                    <input type="hidden" id="chart_type" value="bar">
                                    <button id="enviar" type="submit" class="btn btn-primary pull-right">Gerar Gráfico</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" id="show-legends" class="btn btn-xs btn-info pull-right legend-button">
                    Mostrar legenda
                </button>
            </div>
        <?php endif; ?>
        <div class="jumbotron legend" style="display: none">
            <h4>Legenda</h4>
            <ul>
                <li><strong>Usuários ativos</strong>: usuários que realizaram login pelo menos uma vez nos últimos dois anos</li>
                <li><strong>Usuários não ativos</strong>: usuários que não realizam login a pelo menos dois anos</li>
                <li><strong>Autores</strong>: usuários que realizaram ao menos um post</li>
                <li><strong>Contribuidores</strong>: usuários que realizaram pelo menos um comentário</li>
            </ul>
        </div>
	</div>
</div>
<?php get_footer('full');