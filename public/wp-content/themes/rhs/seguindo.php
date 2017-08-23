<?php $title_box = 'Seguindo'; ?>
<?php get_header('full'); ?>
<?php $meta = RHSFollow::FOLLOWED_KEY; ?>
<?php 
$current_user = wp_get_current_user();

$author_data = get_queried_object();
if ($author_data->has_prop('ID')) {
    $author_id = $author_data->get('ID');
}
$current_user->ID == $author_id ? $var = true : $var = false;

$paged = !empty(get_query_var('rhs_paged')) ? get_query_var('rhs_paged') : 1;
?>

<div class="row">
    <div class="col-xs-9">
        <?php include(locate_template('partes-templates/user-header-info.php')); ?>
                
        <h1 class="titulo-page">
            <?php ($var) ? _e('Quem eu Sigo') : printf('Quem %s segue', $author_data->get('display_name')); ?>
        </h1>
        <div class="tab-content">                
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <?php include(locate_template('partes-templates/loop-users.php')); ?>
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

