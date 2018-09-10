<?php
get_header('full');
$user_comments = RHSUser::current_user_comments();
?>
<div class="row user-comments">
    <div class="col-xs-9">
        <?php include(locate_template('partes-templates/user-header-info.php')); ?>

        <h1 class="titulo-page"> Meus comentários </h1>

        <div class="tab-content col-xs-12">
            <ul class="list-group" id="followContent">
                <?php if (count($user_comments) > 0) {
                    foreach($user_comments as $comentario):
                        if ($comentario instanceof WP_Comment) {
                        $id = $comentario->comment_ID;
                        $post_id = $comentario->comment_post_ID;
                        $date = date("d/m/Y à\s H:i",strtotime($comentario->comment_date));
                        $comment_link = get_permalink($post_id) . "/#comment-" . $id;
                        ?>
                            <li class="list-group-item list-group-item-action">
                                <div class="col-xs-10 text-left">
                                    <p>
                                        <a class="commented-post" href="<?php echo $comment_link; ?>">
                                            <?php echo get_post($post_id)->post_title; ?>
                                        </a> em
                                        <small><?php echo $date; ?></small>
                                    </p>
                                    <p> <?php echo $comentario->comment_content; ?> </p>
                                </div>
                                <div class="col-md-2 status">
                                    <span>Status</span>
                                    <?php echo comment_string(wp_get_comment_status($id)); ?>
                                </div>
                                <div class="clearfix"></div>
                            </li>
                        <?php
                        }
                    endforeach;
                } else {
                 echo '<p class="text-center">Você ainda não comentou em nenhuma postagem.</p>';
                }
                ?>
            </ul>
        </div>
    </div>
    <div class="col-xs-3"> <?php get_sidebar(); ?> </div>
</div>

<?php get_footer('full');