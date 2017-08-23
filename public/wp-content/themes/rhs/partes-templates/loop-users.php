<?php 
$author_query = $RHSFollow->get_follows_list($author_id, $meta, $paged);
if (!empty($author_query->results)) {
    foreach ($author_query->results as $user) {
?>

<ul class="list-group" id="followContent">
    <li class="list-group-item">
        <div class="col-xs-12 col-sm-8">
            <div class="follow-user-thumb">
                <?php echo get_avatar($user->ID, 40); ?>
            </div>
            <div class="user-name"><a href="<?php echo get_author_posts_url($user->ID); ?> "><?php echo $user->display_name; ?></a></div><br/>
        </div>
        <div class="col-xs-12 col-sm-4 text-right">
            <?php $RHSFollow->show_header_follow_box($user->ID); ?>
        </div>
        <div class="clearfix"></div>
    </li>
</ul>

<?php
    }
} else {
    echo _e('Não há usuários.');
}
$RHSFollow->show_follow_pagination($meta, $paged);
?>