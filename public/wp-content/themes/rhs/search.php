<?php get_header('full'); ?>

<div class="col-xs-12 panel panel-default busca-page">

    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#posts" aria-controls="posts" role="tab" data-toggle="tab">Posts</a></li>
        <li role="presentation"><a href="<?php echo RHSSearch::get_users_search_url(); ?>">Usuários</a></li>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="posts">
            <div class="jumbotron formulario">
                <?php get_template_part("partes-templates/header_search_post"); ?>
            </div>

            <div class="row resultado">
                <?php include_once ("partes-templates/search_common.php"); ?>
            </div>

            <?php get_template_part( 'partes-templates/loop-posts'); ?>
        </div>

    </div>
</div>

<?php get_template_part('partes-templates/export-modal'); ?>

<?php get_footer('full');
