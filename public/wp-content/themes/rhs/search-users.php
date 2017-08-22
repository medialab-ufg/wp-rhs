<?php get_header('full'); ?>

<?php
// Parametros de busca
echo $RHSSearch->get_param('uf');
echo $RHSSearch->get_param('keyword');
echo $RHSSearch->get_param('municipio');
echo $RHSSearch->get_param('rhs_order');
echo get_query_var('paged');

//var_dump($wp_query);

$users = $RHSSearch->searchUsers();

// loop de usuários e paginação

?>


<?php get_footer('full');
