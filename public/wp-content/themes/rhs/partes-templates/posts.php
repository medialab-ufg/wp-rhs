<?php $url_name = add_query_arg(array(),$wp->request); ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <?php if ( has_post_thumbnail() ) : ?>
                <a href="<?php the_permalink(); ?>">
                    <img src="<?php echo get_the_post_thumbnail_url() ?>" alt="" class="thumbnail_post">
                </a>
            <?php endif; ?>
        </div>
        <div class="panel-body">
            <a href="<?php the_permalink(); ?>"><?php the_title( '<h3 class="panel-title">', '</h3>' ); ?></a>
            <?php the_excerpt(); ?>
        </div><!-- .paine-body -->
        <div class="panel-footer">
            <?php $userOBJ = new RHSUsers( get_the_author_meta( 'ID' ) ); ?>
            <div class="row">
                <div class="col-xs-12">
                    <div class="post-titulo espacamento-topo">
                        <div class="img-usuario">
                            <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"
                               title="Ver o perfil do(a) <?php the_author_meta( 'display_name' ); ?>.">
                                <?php echo get_avatar(get_the_author_meta( 'ID' ) ); ?>
                            </a>
                        </div>
                        <div class="box-title">
                            <span class="nome-author">
                                <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
                            </span>
                            <span class="post-date text-uppercase"><?php the_time( 'd/m/Y' ); ?></span>
                        </div>
                        <div class="votebox">
                            <?php do_action( 'rhs_votebox', get_the_ID() ); ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <?php
                    if ( comments_open() && $url_name != 'fila-de-votacao') :
                        comments_popup_link( '0 COMENTÁRIOS',
                            '<i class="fa fa-commenting-o" aria-hidden="true"></i> 1 COMENTÁRIO',
                            '<i class="fa fa-commenting-o" aria-hidden="true"></i> % COMENTÁRIOS', 'footer-link',
                            'Não é permitido Comentários neste post' );
                    endif;
                    ?>
                </div>
                <div class="col-xs-6">
                    <span class="pull-right"><?php echo get_post_meta(get_the_ID(), 'socialcount_TOTAL', true); ?></span>
                </div>
            </div>
        </div>
    </div><!-- .panel .panel-default -->
