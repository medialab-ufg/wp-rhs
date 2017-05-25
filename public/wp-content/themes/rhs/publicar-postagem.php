<?php

$edit_post = get_query_var('rhs_edit_post');

$current_post = false;
$cur_title = '';
$cur_content = '';
$cur_state = false;
$cur_city = false;
$cur_status = false;
$cur_category = false;
$cur_tags = false;

if ( !empty($edit_post) && is_numeric($edit_post) && current_user_can('edit_post', $edit_post) ) {

    $current_post = get_post($edit_post);

    $cur_status = $current_post->post_status;
    $cur_title = $current_post->post_title;
    $cur_content = $current_post->post_content;
    $cur_ufmun = get_post_ufmun($edit_post);
    
    if (is_numeric($cur_ufmun['uf']['id']))
        $cur_state = $cur_ufmun['uf']['id'];
    
    if (is_numeric($cur_ufmun['mun']['id']))
        $cur_city = $cur_ufmun['mun']['id'];

    $cur_category = wp_get_post_categories($edit_post);

    if($cur_category){

        $cur_category = current($cur_category);
        $cur_category = get_category($cur_category);

        if($cur_category){
            $cur_category = $cur_category->term_id;
        }

    }

    $cur_tags = wp_get_post_tags($edit_post);

    echo '<pre>';
    print_r($cur_tags);
    exit;

} 
?>


<?php get_header(); ?>
<?php global $RHSPost; ?>
    <div class="row">
        <!-- Container -->
        <form method="post" class="form-horizontal" id="posting" role="form" action="">
            
            <?php if ($current_post): ?>
                <input type="hidden" name="current_ID" value="<?php echo $edit_post; ?>" />
            <?php endif; ?>
            
            <div class="col-xs-12 col-md-9">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="verDados">
                        <div class="jumbotron perfil">
                            <h3 class="perfil-title"><?php echo $current_post ? 'Editar' : 'Criar'; ?> Post</h3>
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
                                                <input class="form-control" type="text" id="title" name="title" size="60" maxlength="254" value="<?php echo $cur_title; ?>">
                                                <input class="form-control" type="hidden" value="<?php echo $RHSPost->getKey(); ?>" name="post_user_wp" />
                                            </div>
                                            <div class="form-group">
                                                <label for="descricao">Conteúdo</label>
                                                <?php
                                                wp_editor( $current_post ? $cur_content : 'Escreva seu Post.', 
                                                            'public_post', 
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
                                                'selected_state' => $cur_state,
                                                'selected_municipio' => $cur_city,
                                            ) ); ?>
                                            <div class="form-group">
                                                <select class="form-control" name="category">
                                                    <option value="">Categoria</option>
                                                    <?php foreach ( get_categories() as $categori ) : ?>
                                                        <option <?php selected($cur_category, $categori->term_id, true) ?> value="<?php echo $categori->term_id; ?>"><?php echo $categori->cat_name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group text-center">
                                                <?php if(!$cur_status || $cur_status != 'draft'){ ?>
                                                <button type="submit" name="status" value="draft" class="btn btn-default form-submit rasc_visu">SALVAR RASCUNHO
                                                </button>
                                                <?php } ?>
                                                <button type="button" class="btn btn-default form-submit rasc_visu" id="pre-visualizar">PRÉ-VISUALIZAR
                                                </button>
                                                <button type="submit" name="status" value="publish" class="btn btn-danger form-submit publicar"><?php echo (!$cur_status || $cur_status == 'draft') ? 'PUBLICAR' : 'EDITAR'; ?>  POST
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
