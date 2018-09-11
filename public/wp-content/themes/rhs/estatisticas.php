<?php get_header('full'); ?>
<?php global $RHSStatistics; ?>
    <style>
        .nav-pills>li>a { color: black; font-size: 15px;}
        .nav-pills>li.active>a, .nav-pills>li.active>a:focus, .nav-pills>li.active>a:hover {
            color: #fff;
            background-color: #00b4b4;
        }
    </style>
<div id="rhs-statistics" class="row">
	<div class="col-md-12">
        <h2 class="text-center"> Estatísticas de Uso da Rede Humaniza SUS</h2>
        <hr>
        <?php if (!is_user_logged_in()): ?>
            <p class="text-center">
                <a href="<?php echo home_url("/login") ?>">Faça login</a> para continuar.
            </p>
        <?php else: ?>
            <div class="col-md-12 no-padding">

                <div class="container"><h4 style="color: black"> </h4></div>
                <div id="exTab3" class="container col-md-12 no-padding">
                    <ul class="nav nav-pills">
                        <li class="active"> <a href="#quantidade" data-toggle="tab">Quantidade por data</a> </li>
                        <li><a href="#media" data-toggle="tab"> Média </a></li>
                        <li><a href="#total" data-toggle="tab"> Total </a> </li>
                        <li><a href="#data" data-toggle="tab"> Período </a> </li>
                    </ul>

                    <div class="tab-content clearfix panel panel-default" style="padding: 20px;">
                        <div class="tab-pane active" id="quantidade">
                            <!-- Increasing -->
                            <div id="filter_increasing" class="filter-">

                                <div class="col-md-12 no-padding">
                                    <div class="col-md-4 no-padding">
                                        <h6>Usuários cadastrados</h6>
                                        <label>
                                            <input type="checkbox" name="filter" value="all_users" data-name="Cadastros" checked> Total
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
                                    </div>
                                    <div class="col-md-4 no-padding">
                                        <h6>Interações</h6>
                                        <label>
                                            <input type="checkbox" name="filter" value="active_author" data-name="Usuários que realizaram posts" checked> Usuarios que postaram
                                        </label>
                                        <label>
                                            <input type="checkbox" name="filter" value="active_contributor" data-name="Usuários que realizaram comentários" checked> Usuários que comentaram
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
                                            <input type="checkbox" name="filter" value="comments" data-name="Comentarios"> Comentários
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
                                        <div class="col-md-4">
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

                                        </div>
                                        <div class="col-md-4">
                                            <h6>Interações</h6>
                                            <label>
                                                <input type="checkbox" name="filter" value="active_author" data-name="Usuários que realizaram posts" checked> Usuarios que postaram
                                            </label>
                                            <label>
                                                <input type="checkbox" name="filter" value="active_contributor" data-name="Usuários que realizaram comentários" checked> Usuários que comentaram
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
                                                <input type="checkbox" name="filter" value="comments" data-name="Comentarios"> Comentários
                                            </label>
                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-4">
                                            <hr>
                                            <h6>Compartilhamentos em Redes sociais</h6>
                                            <label>
                                                <input type="checkbox" name="filter" value="facebook_share" data-name="Facebook"> Facebook
                                            </label>

                                            <label>
                                                <input type="checkbox" name="filter" value="twitter_share" data-name="Twitter"> Twitter
                                            </label>
                                        </div>
                                        <div class="col-md-4">
                                            <hr>
                                            <h6>Visitas</h6>
                                            <label>
                                                <input type="checkbox" name="filter" value="posts_visits" data-name="Visitas aos posts" checked> Posts
                                            </label>
                                        </div>
                                        <div class="col-md-4">
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
                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="tab-pane" id="total">
                            <!-- Total -->
                            <div id="filter_count" class="filter-">
                                <div class="col-md-12 no-padding">
                                    <div class="col-md-6 no-padding">
                                        <h6>Tipo usuário</h6>
                                        <label>
                                            <input type="checkbox" name="filter" value="all_users" data-name="Total" checked> Total
                                        </label>
                                        <label>
                                            <input type="checkbox" name="filter" value="active_users" data-name="Ativos" checked> Ativos
                                        </label>
                                        <label>
                                            <input type="checkbox" name="filter" value="not_active_users" data-name="Não ativos" checked> Não ativos
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
                                    <div class="col-md-6">
                                        <h6>Interações</h6>
                                        <label>
                                            <input type="checkbox" name="filter" value="active_author" data-name="Usuários que realizaram posts" checked> Usuarios que postaram
                                        </label>
                                        <label>
                                            <input type="checkbox" name="filter" value="active_contributor" data-name="Usuários que realizaram comentários" checked> Usuários que comentaram
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="tab-pane" id="data">
                            <div id="date_filter">
                                <div class="col-md-12 no-padding">
                                    <div class="col-md-6 no-padding">
                                        <label>Data Inicial</label>
                                        <input type="date" name="initial_date" id="initial_date" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Data Final</label>
                                        <input type="date" name="final_date" id="final_date" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 no-padding" style="margin-top: 40px">
                            <button id="enviar" type="submit" class="btn btn-primary pull-right">Gerar Gráfico</button>
                        </div>
                    </div>
                </div>

                <?php /*
                <div class="panel">
                    <div class="panel-body">
                        <form id="parametros">
                            <input type="hidden" id="chart_type" value="bar">
                            <h4>Selecione dados referentes a: </h4>
                            <select id="type" name="type" class="form-control">
                                <?php echo $RHSStatistics->get_type(); ?>
                            </select>
<!--                            <h5 class="text-center">Filtros Disponíveis</h5>-->
                        </form>
                    </div>
                </div> */ ?>
            </div>

            <div class="col-md-12 add_margin no-padding">
                <div class="panel">
                    <div class="panel-body">
                        <div id="loader" class="text-center">
                            <p>Carregando ... </p>
                            <img src="<?php echo get_template_directory_uri()?>/inc/comments/images/loadingAnimation.gif">
                        </div>
                        <div id="estatisticas"></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
	</div>
</div>
<?php get_footer('full');