<?php get_header(); ?>

    <div class="row">
        <!-- Container -->
        <form class="form-horizontal" role="form" id="form-perfil">
            <div class="col-xs-12 col-md-9">
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="verDados">
                            <div class="jumbotron perfil">
                                <h3 class="perfil-title">Criar Post</h3>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <label for="titulo">Título <span class="form-required" title="Este campo é obrigatório.">*</span></label>
                                                    <input class="form-control" type="text" id="titulo" name="titulo" size="60" maxlength="254">
                                                </div>
                                                <div class="form-group">
                                                    <label for="descricao">Descrição</label>
                                                </div>
                                                <div class="form-group">
                                                    <?php get_template_part('partes-templates/colapse_criar_post'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="col-xs-12 col-md-3">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="verDados">
                        <div class="jumbotron perfil">
                            <h3 class="perfil-title">Classificar Post</h3>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body sidebar-public">
                                            <div class="borda-tags">
                                            <div class="form-group">
                                                <select multiple data-role="tagsinput">
                                                    <option value="Amsterdam">Amsterdam</option>
                                                    <option value="Washington">Washington</option>
                                                    <option value="Sydney">Sydney</option>
                                                    <option value="Beijing">Beijing</option>
                                                    <option value="Cairo">Cairo</option>
                                                </select>
                                                <span class="fa fa-refresh form-control-feedback" aria-hidden="true"></span>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

<?php get_footer();