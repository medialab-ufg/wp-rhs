<?php get_header(); ?>

    <div class="row">
        <!-- Container -->
        <div class="col-xs-12 col-md-9">
            <div class="row">
                <!-- Button Publicar e Ver Fila de Votação -->
                <?php get_template_part('partes-templates/buttons-top' ); ?>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <h1 class="titulo-page"><?php _e('Minhas Postagens') ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 postagens">
                    <div class="wrapper-content table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Post <i class="fa fa-long-arrow-down" aria-hidden="true"></i></th>
                                    <th>Data</th>
                                    <th>Visualizações</th>
                                    <th>Comentários</th>
                                    <th>Votos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    global $current_user;
                                    wp_get_current_user();
                                    $author_query = array('posts_per_page' => '-1','author' => $current_user->ID);
                                    $author_posts = new WP_Query($author_query);
                                    $RHSVote = new RHSVote();
                                    while($author_posts->have_posts()) : $author_posts->the_post();
                                    ?>
                                        <tr>
                                            <td><?php the_ID(); ?></td>
                                            <td><?php the_time('D, d/m/Y - H:i'); ?></td>
                                            <td></td>
                                            <td>
                                                <?php
                                                    if ( comments_open() ) :
                                                      comments_popup_link( '0', '1 ', '%', '', '<i class="fa fa-ban" aria-hidden="true"></i>');
                                                    endif;
                                                ?>
                                            </td>
                                            <td>
                                            <?php
                                                $votos = $RHSVote->get_total_votes(get_the_ID());
                                                if($votos <= 0){
                                                    echo '0';
                                                }else {
                                                    echo $votos;
                                                }
                                            ?>
                                            </td>
                                        </tr>   
                                    <?php           
                                    endwhile;
                                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sidebar -->
        <div class="col-xs-12 col-md-3"><?php get_sidebar(); ?></div>
    </div>

<?php get_footer();
