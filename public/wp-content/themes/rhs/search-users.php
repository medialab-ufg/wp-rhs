<?php 
get_header('full'); 

// Resultado da busca
$users = $RHSSearch->search_users();
?>
<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default busca-page" style="padding: 10px;">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation"><a href="<?php echo RHSSearch::get_search_url(); ?>">Posts</a></li>
                <li role="presentation" class="active"><a href="#user" aria-controls="user" role="tab" data-toggle="tab">Usuários</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="user">
                    <div class="jumbotron formulario">
                        <div class="container">
                            <form class="form-horizontal form-inline" action="<?php echo home_url('/'); ?>busca/usuarios/" id="filter">
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
                                </div>
                                <div class="col-xs-12 col-sm-5">
                                    <div class="form-inline">
                                        <div class="form-group">
                                            <label for="keyword" class="control-label">Nome ou E-mail</label>
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
                            <div class="col-xs-6 retorno">
                                <?php if(count($_GET)) {?>
                                    <div class="label-rhs"> 
                                    <?php
                                        //Mostra o resultado da busca dos usuarios
                                        exibir_resultado_user();
                                        
                                    ?>
                                    </div>
                                <?php }?>
                            </div>
                            <div class="col-xs-6 classificar">
                                <div class="pull-right">
                                    <div class="dropdown">
                                        <?php RHSSearch::show_button_download_report(); ?>
                                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        Ordenar por
                                        <?php
                                            if(RHSSearch::get_param('rhs_order') == 'name')
                                                echo 'Nome';
                                            elseif(RHSSearch::get_param('rhs_order') == 'register_date')
                                                echo 'Data de Cadastro';
                                            elseif(RHSSearch::get_param('rhs_order') == 'posts')
                                                echo 'Número de Posts';
                                            elseif(RHSSearch::get_param('rhs_order') == 'votes')
                                                echo 'Número de Votos';
                                            else
                                                echo '';
                                        ?>
                                        <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                            <li><a href="<?php echo RHSSearch::get_search_neworder_urls('name'); ?>">Nome</a></li>
                                            <li><a href="<?php echo RHSSearch::get_search_neworder_urls('register_date'); ?>">Data de Cadastro</a></li>
                                            <li><a href="<?php echo RHSSearch::get_search_neworder_urls('posts'); ?>">Número de Posts</a></li>
                                            <li><a href="<?php echo RHSSearch::get_search_neworder_urls('votes'); ?>">Número de Votos</a></li>
                                            <li><a href="<?php echo RHSSearch::get_search_neworder_urls('last_login'); ?>">Último Login</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        <div class="row membros">
                            <?php if (!empty($users->results)): ?>
                                <?php foreach ($users->results as $user): ?>
                                    <div class="col-md-4 col-xs-12 well-disp" data-userid="<?php echo $user->ID; ?>" data-id="<?php echo $user->ID; ?>">
                                        <div class="well profile_view">
                                            <div class="left">
                                                <span class="membros-avatar">
                                                    <a href="<?php echo get_author_posts_url($user->ID); ?>" class="membros">
                                                        <?php echo get_avatar($user->ID); ?>
                                                    </a>
                                                </span>
                                            </div>
                                            <div class="right">
                                                <h1>
                                                    <a href="<?php echo get_author_posts_url($user->ID); ?>" class="membros">
                                                        <span class="membros-name"><?php echo $user->display_name; ?></span>
                                                    </a>
                                                </h1>
                                                <?php if(has_user_ufmun($user->ID)) { ?>
                                                    <div class="info-membros">
                                                        <p class="location">
                                                            <strong>Localidade: </strong> 
                                                            <span class="membros-location">
                                                                <?php echo the_user_ufmun($user->ID); ?>
                                                            </span>
                                                        </p>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                <div class="col-xs-12 text-center">
                                    <?php $RHSSearch->show_users_pagination($users); ?>
                                </div>
                            <?php else : ?>
                                <h3 class="text-center"><?php echo __('Nenhum usuário encontrado, tente outro nome.'); ?></h3>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_template_part('partes-templates/export-modal'); ?>

<?php get_footer('full');
