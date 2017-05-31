<?php get_header(); ?>
<?php $RHSPost = new RHSPost(get_query_var('rhs_edit_post')); ?>
    <div class="row">
        <!-- Container -->
        <form method="post" class="form-horizontal" id="posting" role="form" action="">
            <?php if ($RHSPost->is_post()): ?>
                <input type="hidden" id="post_ID" name="post_ID" value="<?php echo $RHSPost->get_post_data('ID'); ?>" />
            <?php endif; ?>
            <div class="col-xs-12 col-md-9">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="verDados">
                        <div class="jumbotron perfil">
                            <h3 class="perfil-title"><?php echo $RHSPost->is_post() ? 'Editar' : 'Criar'; ?> Post</h3>
                            <div class="row">
                                <div class="col-xs-12">
                                    <?php foreach ($RHSPost->messages() as $type => $messages){ ?>
                                        <div class="alert alert-<?php echo $type == 'error' ? 'danger' : 'success' ; ?>">
                                            <?php foreach ($messages as $message){ ?>
                                                <p><?php echo $message ?></p>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                    <?php $RHSPost->clear_messages(); ?>
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <label for="title">Título <span class="form-required" title="Este campo é obrigatório.">*</span></label>
                                                <input class="form-control" type="text" id="title" name="title" size="60" maxlength="254" value="<?php echo $RHSPost->get_post_data('post_title'); ?>">
                                                <input class="form-control" type="hidden" value="<?php echo $RHSPost->getKey(); ?>" name="post_user_wp" />
                                            </div>
                                            <div class="form-group">
                                                <label for="descricao">Conteúdo</label>
                                                <?php
                                                wp_editor( $RHSPost->is_post() ? $RHSPost->get_post_data('post_content') : 'Escreva seu Post.', 'public_post',
                                                    array(
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
                                                    )
                                                );
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
                                                <input type="text" value="" class="form-control" id="input-tags" placeholder="Tags">
                                                    <script>
                                                        var ms = jQuery('#input-tags').magicSuggest({
                                                            placeholder: 'Select...',
                                                            allowFreeEntries: false,
                                                            selectionPosition: 'bottom',
                                                            selectionStacked: true,
                                                            selectionRenderer: function(data){
                                                                return data.name;
                                                            },
                                                            data: vars.ajaxurl,
                                                            dataUrlParams: { action: 'get_tags' },
                                                            minChars: 3,
                                                            name: 'tags'
                                                        });

                                                        <?php if($RHSPost->get_post_data('tags_json')){ ?>
                                                            var ms = jQuery('#input-tags').magicSuggest({});
                                                            ms.setValue(<?php echo $RHSPost->get_post_data('tags_json'); ?>);
                                                        <?php } ?>

                                                    </script>
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
                                                'selected_state' => $RHSPost->get_post_data('state'),
                                                'selected_municipio' => $RHSPost->get_post_data('city'),
                                            ) ); ?>
                                            <div class="form-group">
                                                <input type="text" value="" class="form-control" id="input-category" placeholder="Categoria">
                                            </div>
                                            <script>

                                                var ms = jQuery('#input-category').magicSuggest({
                                                    placeholder: 'Select...',
                                                    allowFreeEntries: false,
                                                    selectionPosition: 'bottom',
                                                    selectionStacked: true,
                                                    <?php echo $RHSPost->get_post_data('category_json'); ?>
                                                    selectionRenderer: function(data){
                                                        return data.name;
                                                    },
                                                    name: 'category'
                                                });

                                                <?php if($RHSPost->get_post_data('category')){ ?>
                                                var ms = jQuery('#input-category').magicSuggest({});
                                                ms.setValue(<?php echo $RHSPost->get_post_data('category'); ?>);
                                                <?php } ?>

                                            </script>
                                            <div class="form-group text-center">
                                                <input type="hidden" value="" id="img_destacada" name="img_destacada">
                                                <button type="button" class="btn btn-default form-submit dest_visu set_img_destacada">IMAGEM DESTACADA</button>
                                                <button type="submit" name="status" value="draft" class="btn btn-default form-submit rasc_visu">SALVAR RASCUNHO</button>
                                                <button type="button" class="btn btn-default form-submit rasc_visu" id="pre-visualizar">PRÉ-VISUALIZAR
                                                </button>
                                                <button type="submit" name="status" value="publish" class="btn btn-danger form-submit publicar"><?php echo (!$RHSPost->get_post_data('post_status') || $RHSPost->get_post_data('post_status') == 'draft') ? 'PUBLICAR' : 'EDITAR'; ?>  POST
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
            <div class="col-md-9" id="pre-view">
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
