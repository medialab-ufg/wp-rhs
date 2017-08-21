<?php get_header('full'); ?>

<?php
// Parametros de busca
echo $RHSSearch->get_param('keyword');
echo $RHSSearch->get_param('uf');
echo $RHSSearch->get_param('municipio');
echo $RHSSearch->get_param('date_from');
echo $RHSSearch->get_param('date_to');
echo $RHSSearch->get_param('rhs_order'); // comments, views, shares, votes ou date (padrão)
echo get_query_var('cat');
echo get_query_var('tag');

//var_dump($wp_query);

?>



    <?php get_template_part( 'partes-templates/loop-posts'); ?>


<?php get_footer('full');
