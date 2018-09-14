<?php
$author_id = get_the_author_meta( 'ID' );
$userOBJ = new RHSUsers($author_id);
$_post_id = get_the_ID();
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <?php if ( has_post_thumbnail() ) : ?>
            <a href="<?php the_permalink(); ?>">
                <img src="<?php echo get_the_post_thumbnail_url($_post_id, 'medium'); ?>" alt="" class="thumbnail_post">
            </a>
        <?php endif; ?>
    </div>
    <div class="panel-body">
        <a href="<?php the_permalink(); ?>"><?php the_title( '<h3 class="panel-title">', '</h3>' ); ?></a>
        <?php the_excerpt(); ?>
    </div><!-- .paine-body -->
    <div class="panel-footer">
        <div class="row">
            <div class="col-xs-12">
                <div class="post-titulo espacamento-topo">
                    <div class="img-usuario">
                        <a href="<?php echo esc_url( get_author_posts_url($author_id) ); ?>"
                           title="Ver o perfil do(a) <?php the_author_meta( 'display_name' ); ?>.">
                            <?php echo get_avatar($author_id); ?>
                        </a>
                    </div>
                    <div class="box-title">
                        <span class="nome-author">
                            <a href="<?php echo get_author_posts_url($author_id); ?>"><?php the_author(); ?></a>
                        </span>
                        <span class="post-date text-uppercase"><?php the_time( 'd/m/Y' ); ?></span>
                    </div>
                    <div class="votebox">
                        <?php
                        if(get_post_status($_post_id) != 'private')
                        {
                            do_action( 'rhs_votebox', $_post_id);
                        }
                        ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <?php
                if ( comments_open() && get_post_status($_post_id) != RHSVote::VOTING_QUEUE) :
                    comments_popup_link( '0 COMENTÁRIOS',
                        '<i class="fa fa-commenting-o" aria-hidden="true"></i> 1 COMENTÁRIO',
                        '<i class="fa fa-commenting-o" aria-hidden="true"></i> % COMENTÁRIOS', 'footer-link',
                        'Não é permitido Comentários neste post' );
                endif;
                ?>
            </div>
            <div class="col-xs-6">
                <span class="pull-right"><!-- Aqui contagem de compartilhamento caso tenha --></span>
            </div>
        </div>
    </div>
</div><!-- .panel .panel-default -->
