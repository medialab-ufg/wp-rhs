<?php 
get_header('full'); 

// Resultado da busca
$users = $RHSSearch->search_users();

echo "Total: " . $users->total_users; // deixando aqui só pra vc saber como pega e poder montar o layout

?>
<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default busca-page" style="padding: 10px;">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation"><a href="<?php echo home_url('/'); ?>busca/">Posts</a></li>
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
                                            'show_label' => true
                                        ) ); ?>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-5">
                                    <div class="form-inline">
                                        <div class="form-group">
                                            <label for="keyword" class="control-label">Palavra Chave</label>
                                            <input type="text" name="keyword" id="keyword" class="form-control" value="<?php echo $s; ?>">
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
                                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        Classificar por
                                        <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                            <li><a href="?rhs_order=date">Data</a></li>
                                            <li><a href="?rhs_order=comments">Comentários</a></li>
                                            <li><a href="?rhs_order=votes">Votos</a></li>
                                            <li><a href="?rhs_order=views">Visualizações</a></li>
                                            <li><a href="?rhs_order=shares">Compartilhamentos</a></li>
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
                                        <?php //var_dump(UFMunicipio::get_user_meta($user->ID) == ''); die; ?>
                                            <div class="left">
                                                <span class="comunity-avatar">
                                                    <a href="<?php echo get_author_posts_url($user->ID); ?>" class="membros">
                                                        <?php echo get_avatar($user->ID); ?>
                                                    </a>
                                                </span>
                                            </div>
                                            <div class="right">
                                                <h1>
                                                    <a href="<?php echo get_author_posts_url($user->ID); ?>" class="membros">
                                                        <span class="comunity-name"><?php echo $user->display_name; ?></span>
                                                    </a>
                                                </h1>
                                                <?php if(has_user_ufmun($user->ID)) { ?>
                                                    <div class="info">
                                                        <p><strong>Localidade: </strong> <span class="comunity-location"><?php echo the_user_ufmun($user->ID); ?></span></p>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                <div class="col-xs-12 text-center">
                                    <?php $RHSSearch->show_users_pagination(); ?>
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

<?php get_footer('full');
