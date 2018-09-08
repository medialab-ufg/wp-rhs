<?php
get_header('full');

$c = get_comments(['user_id' => get_current_user_id()]);
?>
<div class="row">
    <div class="col-xs-9">
        <?php include(locate_template('partes-templates/user-header-info.php')); ?>

        <h1 class="titulo-page"> Meus comentários </h1>
        <div class="tab-content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <?php foreach($c as $comentario) {
                                if ($comentario instanceof WP_Comment) {
                                    echo $comentario->comment_content;
                                    echo " <br> - " . get_permalink($comentario->comment_post_ID) . "<hr>";
                                }
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-3">
        <?php get_sidebar(); ?>
    </div>
</div>

<?php get_footer('full');