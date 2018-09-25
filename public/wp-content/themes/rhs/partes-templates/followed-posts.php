<?php
$followed_posts = $RHSFollow->get_followed_posts($author_id, $meta);
if (!empty($followed_posts)) {
    global $RHSFollowPost;
    foreach ($followed_posts as $post_id => $post) {
        ?>

        <ul class="list-group" id="followContent">
            <li class="list-group-item">
                <div class="col-xs-12 col-sm-7">
                    <div class="user-name"><a href="<?php echo $post['permalink']; ?> "><?php echo $post['post_title']; ?></a></div><br/>
                    <h6>Autor: <a href="<?php echo $post['author_link']?>"><?php echo $post['author']; ?></a></h6>
                </div>
                <div class="col-sm-2">
                    Seguido desde
                    <h5 class="pull-right"><?php echo $post['follow_date']; ?></h5>
                </div>
                <div class="col-xs-12 col-sm-3 text-right">
                    <?php $RHSFollowPost->show_header_follow_post_box($post_id); ?>
                </div>
                <div class="clearfix"></div>
            </li>
        </ul>

        <?php
    }
} else {
    _e('Você não está seguindo nenhum post');
}
$RHSFollow->show_follow_pagination($meta, $paged);
