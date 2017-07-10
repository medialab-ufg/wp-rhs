<?php get_header(); ?>
        <div class="row">
            <?php 
                while (have_posts()): the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" class="post-container">
                <div class="col-xs-12">
                    <?php   
                        //Pega o paineldosposts para mostrar na pagina front-page os posts.
                        get_template_part( 'partes-templates/painel-single', get_post_format()); 
                    ?>
                </div>
            </article><!-- #post-## -->
            <?php endwhile; ?>
        </div>
<?php get_footer();