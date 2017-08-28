<?php 
get_header('full'); 

// Parametros de busca
echo "<h5>parametros</h5>";
echo "uf: " . $RHSSearch->get_param('uf') . "<br/>";

echo "municipio: " . $RHSSearch->get_param('municipio') . "<br/>";
echo "order: " . $RHSSearch->get_param('rhs_order') . "<br/>";
echo "keyword: " . $RHSSearch->get_param('keyword') . "<br/>";
echo "<hr>";

// Resultado da busca
$users = $RHSSearch->search_users();

echo "Total: " . $users->total_users; // deixando aqui só pra vc saber como pega e poder montar o layout

?>

<?php if (!empty($users->results)): ?>
    
    <?php foreach ($users->results as $user): ?>
        
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
        
    <?php endforeach; ?>
    
    <?php $RHSSearch->show_users_pagination(); ?>
    
<?php else: ?>
    Nenhum usuário não encontrado.
<?php endif; ?>

<?php get_footer('full');
