<?php
get_header('full');

$user_comments = get_comments(['user_id' => get_current_user_id()]);
?>
<div class="row">
    <div class="col-xs-9">
        <?php include(locate_template('partes-templates/user-header-info.php')); ?>

        <h1 class="titulo-page"> Meus comentários </h1>
        <div class="tab-content">

            <div class="col-xs-12" style="background: white; padding-top: 30px">
                <ul class="list-group" id="followContent">
                    <?php
                    if (count($user_comments) > 0) {
                        foreach($user_comments as $comentario) {
                            if ($comentario instanceof WP_Comment) { ?>

                                <li class="list-group-item">

                                    <div class="col-xs-10 text-left">
                                        <p>
                                            <span class="span">Em: </span>

                                            <a href="<?php echo get_permalink($comentario->comment_post_ID); ?>">
                                                <?php echo get_post($comentario->comment_post_ID)->post_title; ?>
                                            </a>
                                            <small style="color: black"><?php echo $comentario->comment_date ?></small>
                                        </p>
                                        <p>
                                            <?php echo $comentario->comment_content; ?>
                                        </p>

                                    </div>
                                    <div class="col-md-2">
                                        <strong style="color: #003c46">Status</strong>
                                        <br>
                                        <?php echo wp_get_comment_status($comentario->comment_ID); ?>
                                    </div>
                                    <div class="clearfix"></div>
                                </li>

                            <?php }

                        }
                    } else {
                     echo '<p class="text-center">Você ainda não comentou em nenhuma postagem.</p>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-xs-3"> <?php get_sidebar(); ?> </div>
</div>

<?php get_footer('full');