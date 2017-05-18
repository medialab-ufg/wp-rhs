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
                        <div class="jumbotron perfil pub">
                            <h3 class="perfil-title">Classificar Post</h3>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body sidebar-public">    
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="ms-filter" placeholder="Tags">
                                            </div>
                                            <?php UFMunicipio::form( array(
                                                'content_before' => '',
                                                'content_after' => '',
                                                'content_before_field' => '<div class="form-group">',
                                                'content_after_field' => '</div>',
                                                'select_before' => ' ',
                                                'select_after' => ' ',
                                                'state_label'  => 'Estado &nbsp',
                                                'city_label'   => 'Cidade &nbsp',
                                                'select_class' => 'form-control',
                                                'show_label'   => false,
                                            ) ); ?>
                                            <div class="form-group">
                                                <select class="form-control" name="post-type">
                                                    <option value="">Tipos de Post</option>
                                                <?php foreach ( get_categories() as $categori ) : ?>
                                                    <option value="<?php echo $categori->cat_name; ?>"><?php echo $categori->cat_name; ?></option>
                                                <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group text-center">
                                                <button class="btn btn-default form-submit rasc_visu">SALVAR RASCUNHO</button>
                                                <button class="btn btn-default form-submit rasc_visu">PRÉ-VISUALIZAR</button>
                                                <button class="btn btn-danger form-submit publicar">PUBLICAR POST</button>
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