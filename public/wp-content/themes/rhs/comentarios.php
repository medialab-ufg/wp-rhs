<?php
$author_data = get_queried_object();
if (!is_null($author_data) && $author_data->has_prop('ID'))
    $author_id = $author_data->get('ID');

if (isset($author_id)) {
    if ($author_id != get_current_user_id()) {
        wp_redirect(home_url());
    } else {
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

                                    if (is_object(get_post($post_id))) {
                                        $comment_link = get_permalink($post_id) . "/#comment-" . $id;
                                        $comment_post_title = get_post($post_id)->post_title;
                                    } else {
                                        $comment_link = "#";
                                        $comment_post_title = "<i>Post removido</i>";
                                    }
                                    ?>
                                    <li class="list-group-item list-group-item-action">
                                        <div class="col-xs-10 text-left">
                                            <p> Post:
                                                <a class="commented-post" href="<?php echo $comment_link; ?>">
                                                    <?php echo $comment_post_title; ?>
                                                </a> em
                                                <small><?php echo $date; ?></small>
                                            </p>
                                            <p> <?php echo $comentario->comment_content; ?> </p>
                                        </div>
                                        <div class="col-md-2 status">
                                            <span>Status </span>
                                            <i> <?php echo comment_string(wp_get_comment_status($id)); ?> </i>
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
        <?php
        get_footer('full');
    }
}