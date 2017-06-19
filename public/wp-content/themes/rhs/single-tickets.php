<?php

// Apenas os donos dos posts podem ver essa página

if (!is_user_logged_in() || wp_get_current_user()->ID != $post->post_author) {
    wp_redirect(home_url('nao-permitido'));
}

?>


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
                    <h1 class="titulo-page"><?php _e('Tickets') ?> </h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 tickets">
                    <div class="wrapper-content">
                        <div class="panel panel-default">
                            <?php $term_list = wp_get_post_terms($post->ID, 'tickets-category'); ?>
                            <?php  while (have_posts()): the_post();
                                if(get_post_status() == 'open'){
                                    $status = 'Em Aberto';
                                    $lab = 'success';
                                }
                                elseif(get_post_status() == 'close'){
                                    $status = 'Fechado';
                                    $lab = 'danger';
                                }
                                elseif(get_post_status() == 'not_response'){
                                    $status = 'Não Repondido';
                                    $lab = 'primary';
                                } 
                            ?>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="m-b-md">
                                                <h3 style="border-bottom: 1px solid rgba(119, 119, 119, 0.1)"><?php echo get_the_title(); ?></h3>
                                            </div>
                                            <dl class="dl-horizontal">
                                                <dt>Status:</dt> <dd><span class="label label-<?php echo $lab; ?>"><?php echo $status; ?></span></dd>
                                            </dl>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-5">
                                            <dl class="dl-horizontal">
                                                <dt>Criado por:</dt> <dd><?php the_author(); ?></dd>
                                                <dt>Categoria:</dt> <dd><?php echo $term_list[0]->name; ?></dd>
                                            </dl>
                                        </div>
                                        <div class="col-xs-7" id="cluster_info">
                                            <dl class="dl-horizontal">
                                                <dt>Criado em:</dt> <dd><?php the_time('D, d/m/Y - H:i'); ?></dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <h4>Mensagem:</h4>
                                            <div class="well"><?php the_content(); ?></div>
                                        </div>
                                        <?php
                                            global $post;
                                            $comments = get_comments('post_id='.$post->ID . '&order=asc');
                                            foreach($comments as $comment) :
                                        ?>
                                            <div class="col-xs-12">
                                                    <h4>Respostas:</h4>
                                                    <div class="well"><?php echo $comment->comment_content; ?>
                                                        <p style="text-align: right;"> <strong>-</strong> 
                                                            <?php if ($comment->user_id) {
                                                                $user=get_userdata($comment->user_id);
                                                                    echo '<a href="'.get_author_posts_url($comment->user_id).'">'.$user->display_name.'</a>';
                                                                } else { 
                                                                    comment_author_link();
                                                            } ?>
                                                        </p>
                                                    </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sidebar -->
        <div class="col-xs-12 col-md-3"><?php get_sidebar(); ?></div>
    </div>

<?php get_footer();
