<?php get_header('full'); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default busca-page" style="padding: 10px;">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#posts" aria-controls="posts" role="tab" data-toggle="tab">Posts</a></li>
                <li role="presentation"><a href="<?php echo RHSSearch::get_users_search_url(); ?>">Usuários</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="posts">
                    <div class="jumbotron formulario">
                        <div class="container">
                            <form class="form-horizontal form-inline" action="<?php echo home_url('/'); ?>busca/" id="filter">
                                <div class="col-xs-12 col-sm-7">
                                    <div class="form-inline">    
                                        <?php UFMunicipio::form( array(
                                            'content_before' => '',
                                            'content_after' => '',
                                            'content_before_field' => '<div class="form-group">',
                                            'content_after_field' => '</div>',
                                            'select_before' => ' ',
                                            'select_after' => ' ',
                                            'state_label' => 'Estado &nbsp',
                                            'state_field_name' => 'uf',
                                            'city_label' => 'Cidade &nbsp',
                                            'select_class' => 'form-control',
                                            'label_class' => 'control-label',
                                            'show_label' => true,
                                            'selected_state' => RHSSearch::get_param('uf'),
                                            'selected_municipio' => RHSSearch::get_param('municipio'),
                                        ) ); ?>
                                    </div>

                                    <div class="form-inline">
                                        <div class="col-xs-12 col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="tag" class="control-label">Tags</label>
                                                <input type="text" value="" class="form-control" id="input-tag" placeholder="Tags" name="tag">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6 tags-cats">
                                            <div class="form-group">
                                                <label for="categoria" class="control-label">Categoria</label>
                                                <?php wp_dropdown_categories( [
                                                    'show_option_none' => 'Selecione uma Categoria',
                                                    'selected' => RHSSearch::get_param('cat'),
                                                    'option_none_value' => '',
                                                    //'hierarchical' => 1, 
                                                    'orderby' => 'name',
                                                    'class' => 'form-control '
                                                ] ); ?>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-5">
                                    <div class="form-inline">
                                        <label for="date" class="control-label">Data</label>
                                        <div class="form-group">
                                            <div class="input-group input-daterange">
                                                <input type="text" class="form-control" value="<?php echo RHSSearch::get_param('date_from'); ?>" name="date_from">
                                                <div class="input-group-addon">até</div>
                                                <input type="text" class="form-control" value="<?php echo RHSSearch::get_param('date_to'); ?>" name="date_to">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-inline">
                                        <div class="form-group">
                                            <label for="keyword" class="control-label">Palavra Chave</label>
                                            <input type="text" name="keyword" id="keyword" class="form-control" value="<?php echo RHSSearch::get_param('keyword'); ?>">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-default filtro">Filtrar</button>
                            </form>
                        </div>
                    </div>
                    <div class="row resultado">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="pull-right">
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" type="button" id="busca_filtro" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        Ordenar por
                                        <?php
                                            if(RHSSearch::get_param('rhs_order') == 'date')
                                                echo 'Data';
                                            elseif(RHSSearch::get_param('rhs_order') == 'comments')
                                                echo 'Comentários';
                                            elseif(RHSSearch::get_param('rhs_order') == 'votes')
                                                echo 'Votos';
                                            elseif(RHSSearch::get_param('rhs_order') == 'views')
                                                echo 'Visualizações';
                                            elseif(RHSSearch::get_param('rhs_order') == 'shares')
                                                echo 'Compartilhamentos';
                                            else
                                                echo 'Cassificar por';
                                        ?>
                                        <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                            <li><a href="<?php echo RHSSearch::get_search_neworder_urls('date'); ?>">Data</a></li>
                                            <li><a href="<?php echo RHSSearch::get_search_neworder_urls('comments'); ?>">Comentários</a></li>
                                            <li><a href="<?php echo RHSSearch::get_search_neworder_urls('votes'); ?>">Votos</a></li>
                                            <li><a href="<?php echo RHSSearch::get_search_neworder_urls('views'); ?>">Visualizações</a></li>
                                            <li><a href="<?php echo RHSSearch::get_search_neworder_urls('shares'); ?>">Compartilhamentos</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        <?php get_template_part( 'partes-templates/loop-posts'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer('full');
