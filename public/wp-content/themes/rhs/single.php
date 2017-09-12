<?php get_header(); ?>
        <div class="row">
            <?php while (have_posts()): the_post(); ?>
            <article id="post-<?php the_ID(); ?>" class="post-container">
                <?php if (get_post_status(get_the_ID()) != 'private' || current_user_can('read_post', get_the_ID())): ?>
                    <div class="col-xs-12">
                        <?php  
                            //Pega o paineldosposts para mostrar na pagina front-page os posts.
                            get_template_part( 'partes-templates/painel-single', get_post_format());
                        ?>
                    </div>
                <?php else: ?>
                    
                    <div class="col-xs-12">
                        <div class="panel panel-default padding-bottom">
                            <div class="panel-heading" style="padding: 21px;">
                        		<div class="row post-titulo">
                                    <div class="col-xs-12">
                                        Conteúdo restrito
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                <?php endif; ?>
            </article><!-- #post-## -->
            <?php endwhile; ?>
        </div>
<?php get_footer();