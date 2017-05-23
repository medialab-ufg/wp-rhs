<?php get_header(); ?>
<?php global $RHSPost; ?>
    <div class="row">
        <!-- Container -->
        <form method="post" class="form-horizontal" id="posting" role="form" action="">
            <div class="col-xs-12 col-md-9">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="verDados">
                        <div class="jumbotron perfil">
                            <h3 class="perfil-title">Criar Post</h3>
                            <div class="row">
                                <div class="col-xs-12">
                                    <?php foreach ($RHSPost->messages() as $type => $messages){ ?>
                                        <div class="alert alert-<?php echo $type == 'error' ? 'danger' : 'success' ; ?>">
                                            <?php foreach ($messages as $message){ ?>
                                                <p><?php echo $message ?></p>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <label for="title">Título <span class="form-required" title="Este campo é obrigatório.">*</span></label>
                                                <input class="form-control" type="text" id="title" name="title" size="60" maxlength="254">
                                                <input class="form-control" type="hidden" value="<?php echo $RHSPost->getKey(); ?>" name="post_user_wp" />
                                            </div>
                                            <div class="form-group">
                                                <label for="descricao">Descrição</label>
                                                <?php
                                                wp_editor( 'Escreva seu Post.', 'public_post', array(
                                                    'media_buttons' => true,
                                                    // show insert/upload button(s) to users with permission
                                                    'dfw'           => false,
                                                    // replace the default full screen with DFW (WordPress 3.4+)
                                                    'tinymce'       => array(
                                                        'theme_advanced_buttons1' => 'bold,italic,underline,strikethrough,bullist,numlist,code,blockquote,link,unlink,outdent,indent,|,undo,redo,fullscreen,paste'
                                                    ),
                                                    'quicktags'     => array(
                                                        'buttons' => 'strong,em,link,block,del,ins,img,ul,ol,li,code,close'
                                                    )
                                                ) );
                                                ?>
                                            </div>
                                            <div class="form-group">
                                                <?php get_template_part( 'partes-templates/colapse_criar_post' ); ?>
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
                                                'state_label' => 'Estado &nbsp',
                                                'city_label' => 'Cidade &nbsp',
                                                'select_class' => 'form-control',
                                                'show_label' => false,
                                            ) ); ?>
                                            <div class="form-group">
                                                <select class="form-control" name="category">
                                                    <option value="">Categoria</option>
                                                    <?php foreach ( get_categories() as $categori ) : ?>
                                                        <option value="<?php echo $categori->term_id; ?>"><?php echo $categori->cat_name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group text-center">
                                                <button type="submit" name="type" value="draft" class="btn btn-default form-submit rasc_visu">SALVAR RASCUNHO
                                                </button>
                                                <button type="button" class="btn btn-default form-submit rasc_visu" id="pre-visualizar">PRÉ-VISUALIZAR
                                                </button>
                                                <button type="submit" name="type" value="publish" class="btn btn-danger form-submit publicar">PUBLICAR POST
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12" id="pre-view">
                <div class="panel-icon text-center">
                    <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row post-titulo">
                            <div class="col-md-12">
                                <h3></h3>			</div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="panel-body content">

                    </div>
                </div>
            </div>
        </form>
    </div>
<?php get_footer();