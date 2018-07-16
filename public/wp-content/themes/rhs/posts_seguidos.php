<?php
$_current_user = get_current_user_id();
$author_data = get_queried_object();

if (!is_null($author_data) && $author_data->has_prop('ID'))
    $author_id = $author_data->get('ID');

if (isset($author_id)) {
    if ($author_id != $_current_user):
        wp_redirect(home_url());
    else:
         get_header('full');
         $meta = RHSFollow::FOLLOWED_POSTS_KEY;
         $paged = !empty(get_query_var('rhs_paged')) ? get_query_var('rhs_paged') : 1;
        ?>

        <div class="row">
            <div class="col-xs-9">
                <?php include(locate_template('partes-templates/user-header-info.php')); ?>

                <h1 class="titulo-page"> Posts que eu sigo </h1>
                <div class="tab-content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <?php include(locate_template('partes-templates/followed-posts.php')); ?>
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
    endif;
} else {
    wp_redirect(home_url());
}
