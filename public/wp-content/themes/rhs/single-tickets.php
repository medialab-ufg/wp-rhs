<?php

// Apenas os donos dos posts podem ver essa página

if (!is_user_logged_in() || wp_get_current_user()->ID != $post->post_author) {
    wp_redirect(home_url('nao-permitido'));
}

global $post;
?>

<?php get_header(); ?>
    
    <?php while (have_posts()): the_post(); ?>
    
        <div class="row">
            <div class="col-xs-12">
                <h1 class="titulo-page"><?php the_title(); ?></h1>
            </div>
        </div>
        
        
        <div class="row">
                <div class="col-xs-12 tickets">
                    
                    <div class="wrapper-content">
                        <div class="panel panel-default">
                            <?php $term_list = wp_get_post_terms($post->ID, 'tickets-category'); ?>
                            <?php  
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
                                                <h3><?php echo get_the_title(); ?></h3>
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
                                            <article class="panel panel-primary">

                                                <!-- Heading -->
                                                <div class="panel-heading">
                                                    <h2 class="panel-title">Mensagem:</h2>
                                                </div>
                                                <!-- /Heading -->

                                                <!-- Body -->
                                                <div class="panel-body">
                                                    <?php the_content(); ?>
                                                </div>
                                                <!-- /Body -->

                                            </article>
                                        </div>
                                        <div class="col-xs-12">
                                            <h4>Respostas:</h4>
                                        </div>  
                                        <?php
                                            $comments = get_comments('post_id='.$post->ID . '&order=asc');
                                            if(!$comments){
                                                echo '<span style="margin-left: 33px;">Agradecemos por entrar em contato, em breve você será respondi. <strong>-</strong> Rede Humaniza SuS</span>';
                                            }
                                            foreach($comments as $comment) :
                                                $commentAuthor=get_userdata($comment->user_id);
                                                ?>
                                                <div class="col-xs-12">
                                                        <div class="well<?php if($comment->user_id != $post->post_author) : ?> text-right well-after <?php else : ?> well-before<?php endif; ?>"><?php echo $comment->comment_content; ?>
                                                            <span class="<?php if($comment->user_id != $post->post_author) : ?> text-right<?php endif; ?>"> <strong>-</strong> 
                                                                <?php if ($comment->user_id) {
                                                                        echo '<a href="'.get_author_posts_url($comment->user_id).'">'.$commentAuthor->display_name.'</a>';
                                                                    } else { 
                                                                        comment_author_link();
                                                                } ?>
                                                            </span>
                                                        </div>
                                                </div>
                                            <?php 
                                            endforeach; 
                                            ?>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="wrapper-content">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <form autocomplete="off" id="responder_editor" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php"  method="post">
                                    <h4>Novo comentário</h4>
                                    <div class="form-group float-label-control">
                                        <?php comment_id_fields(); ?>
                                        <textarea name="comment" id="comment" tabindex="1" class="form-control" rows="4"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-default pull-right">Responder</button>
                                    <?php if(get_option("comment_moderation") == "1") : ?>
                                        <?php _e('Todos os comentarios precisam ser aprovados.', 'rhs'); ?>
                                    <?php endif; ?>
                                    <?php do_action('comment_form', $post->ID); ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div> <!-- .tickets -->
        </div> <!-- .row -->
    <?php endwhile; ?>
<?php get_footer();
