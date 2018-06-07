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
                        <?php get_template_part("partes-templates/header_search_users"); ?>
                    </div>
                    <div class="row resultado">

                        <?php include_once ("partes-templates/search_common.php"); ?>

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
