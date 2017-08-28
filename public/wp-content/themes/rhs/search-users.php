<?php get_header('full'); ?>

<div class="container">
    <form >
        <div class="col-xs-6">
            <div class="form-inline">    
                <?php UFMunicipio::form( array(
                    'content_before' => '',
                    'content_after' => '',
                    'content_before_field' => '<div class="form-group">',
                    'content_after_field' => '</div>',
                    'select_before' => ' ',
                    'select_after' => ' ',
                    'state_field_name' => 'estado_user',
                    'state_field_id' => 'estado_user',
                    'city_field_id' => 'municipio_user',
                    'city_field_name' => 'municipio_user',
                    'state_label' => 'Estado &nbsp',
                    'city_label' => 'Cidade &nbsp',
                    'select_class' => 'form-control',
                    'show_label' => true
                ) ); ?>
            </div>
        </div>
    </form>
</div>

<?php
// Parametros de busca
$paged = $RHSSearch->get_param('paged') ? $RHSSearch->get_param('paged') : 1;
echo "<h5>parametros</h5>";
echo "uf: " . $RHSSearch->get_param('uf') . "<br/>";

echo "municipio: " . $RHSSearch->get_param('municipio') . "<br/>";
echo "order: " . $RHSSearch->get_param('rhs_order') . "<br/>";
echo "keyword: " . $RHSSearch->get_param('keyword') . "<br/>";
echo "<hr>";

$users = $RHSSearch->search_users(array(
    'uf' => $RHSSearch->get_param('uf'), 
    'keyword' => $RHSSearch->get_param('keyword'),
    'municipio' => $RHSSearch->get_param('municipio')
), $paged);


echo "<hr>";

// User Loop
if (!empty($users->results)) {
	foreach ($users->results as $user) {
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
	echo 'Usuário não encontrado.';
}
$RHSSearch->show_users_pagination($paged);

echo "<br/>total: " . $users->total_users;
?>

<?php get_footer('full');
