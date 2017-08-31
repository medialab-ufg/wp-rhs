<?php get_header('full'); ?>
<?php global $RHSPosts; ?>

<?php $RHSPost = $RHSPosts->get_current_post(); ?>
    <div class="row">
        <!-- Container -->
        <form autocomplete="off" method="post" class="form-horizontal" id="posting" role="form" action="">
            <?php if ($RHSPost->getId()): ?>
                <input type="hidden" id="post_ID" name="post_ID" value="<?php echo $RHSPost->getId(); ?>" />
            <?php endif; ?>
            <div class="col-xs-12 col-md-9">
                <h1 class="titulo-page"><?php echo $RHSPost->getId() ? 'Editar' : 'Criar'; ?> Post</h1>
                <div class="tab-content">
                    <div id="verDados">
                        <div class="jumbotron perfil">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="panel panel-default">
                                        <?php foreach ($RHSPosts->messages() as $type => $messages){ ?>
                                            <div class="alert alert-<?php echo $type == 'error' ? 'info' : 'success' ; ?>">
                                                <?php foreach ($messages as $message){ ?>
                                                    <p><?php echo $message ?></p>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                        <?php $RHSPosts->clear_messages(); ?>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <label for="title">Título <span class="form-required" title="Este campo é obrigatório.">*</span></label>
                                                <input class="form-control" type="text" id="title" name="title" size="60" maxlength="254" value="<?php echo $RHSPost->getTitle(); ?>">
                                                <input class="form-control" type="hidden" value="<?php echo $RHSPosts->getKey(); ?>" name="post_user_wp" />
                                            </div>
                                            <div class="form-group">
                                                <label for="descricao">Conteúdo</label>
                                                <?php
                                                wp_editor( $RHSPost->getContent() ? $RHSPost->getContent() : '', 'public_post',
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-3">
                <div id="sidebar" class="sidebar hidden-print">
                    <aside id="class_post-1" class="widget widget_class_post">
                        <h2 class="widget-title">Classificar Post</h2>
                        <div class="panel">
                            <div class="panel-body sidebar-public">
                                <div class="form-group">
                                    <input type="text" value="" class="form-control" id="input-tags" placeholder="Tags">
                                </div>
                                <div class="form-group publish_post_sidebox_city_state">
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
                                        'selected_state' => $RHSPost->getState(),
                                        'selected_municipio' => $RHSPost->getCity(),
                                    ) ); ?>
                                </div>
                                <div class="form-group form-checkbox publish_post_sidebox">
                                <label>Categorias</label>
                                <?php 
                                    $category = get_categories();
                                    foreach($category as $cat){
                                        $checked = in_array($cat->term_id, $RHSPost->getCategoriesIds()) ? 'checked' : '';
                                        echo '<div class="checkbox">
                                            <input type="checkbox" name="category[]" class="uniform" value="'. $cat->term_id .'" id="input-category" '.$checked.'>
                                            <label for="category-'. $cat->term_id .'">' . $cat->name .'</label>
                                        </div>';
                                    }
                                ?>
                                </div>

                                <?php global $RHSComunities; ?>
                                <?php $comunidades = $RHSComunities->get_comunities_by_user( get_current_user_id() );  ?>
                                <?php if($RHSPost->getStatus() == 'private' || $comunidades) { ?>

                                    <div class="form-group form-checkbox publish_post_sidebox">
                                        <label>Publicar em</label>
                                    
                                        <div>
                                            <input <?php echo (!$RHSPost->getComunities() || $RHSPost->getStatus() != 'private') ? 'checked' : ''; ?> type="checkbox" class="uniform" id="comunity-status" name="comunity-status[]" value="public">
                                            <label for="checkbox1">Público</label>
                                        </div>
                                        <?php foreach ( $RHSComunities->get_comunities_by_user( get_current_user_id() ) as $key => $comunidade ) { ?>
                                            <?php if(!$comunidade->is_member()){
                                                continue;
                                            } ?>
                                            <div>
                                                <input <?php echo $RHSPost->getComunitiesId() && in_array($comunidade->get_id(), $RHSPost->getComunitiesId()) ? 'checked' : ''; ?> type="checkbox" class="uniform" id="comunity-status-<?php echo $key; ?>" name="comunity-status[]" value="<?php echo $comunidade->get_name(); ?>">
                                                <label for="comunity-status-<?php echo $key; ?>"><?php echo $comunidade->get_name() ?> <strong>(Comunidade)</strong></label>
                                            </div>
                                        <?php } //foreach ?>
                                    </div>
                                <?php } else { ?>
                                    <input type="hidden" class="form-control" name="comunity-status[]" value="public" />
                                <?php } // if communitties ?>
                                <div class="form-group text-center">
                                    <input type="hidden" value="<?php echo $RHSPost->getFeaturedImageId(); ?>" id="img_destacada" name="img_destacada">
                                    <div id="img_destacada_preview">
                                        <?php if ($RHSPost->getFeaturedImageId()) echo $RHSPost->getFeaturedImage(array( 200, 200)); ?>
                                    </div>
                                    <button type="button" class="btn btn-default form-submit dest_visu set_img_destacada">DEFINIR IMAGEM DESTACADA</button>
                                    <?php if(!$RHSPost->getId() || $RHSPost->getStatus() == 'draft'){ ?>
                                    <button type="submit" name="status" value="draft" class="btn btn-default form-submit rasc_visu">SALVAR RASCUNHO</button>
                                    <?php } ?>
                                    <button type="button" class="btn btn-default form-submit rasc_visu" id="pre-visualizar">PRÉ-VISUALIZAR
                                    </button>
                                    <button type="submit" name="status" value="publish" class="btn btn-danger form-submit publicar">
                                        <?php echo (!$RHSPost->getId() || $RHSPost->getStatus() == 'draft') ? 'PUBLICAR' : 'SALVAR'; ?>  POST
                                    </button>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
            <!-- Tags para visualizar o posts ao clicar em Pré-Visualizar -->
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
                        <div class="panel"></div>
                    </div>
                </div>
            </div>
            <!-- Fim -->
        </form>
    </div>
<?php get_footer('full');
