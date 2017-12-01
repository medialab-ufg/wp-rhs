<?php if(have_posts()) : ?>

	<div class="clearfix masonry">
		<div class="grid-sizer"></div> <div class="gutter-sizer"></div>
        <?php

        $_is_community = $wp_query->is_tax(RHSComunities::TAXONOMY);
        while( have_posts() ):
            $_show_post = false;
            the_post();

            /*
             * Se estamos na lista de posts da comunidade, exibimos também os posts privados.
             * Caso contrário, só listamos posts privados se o autor corrente for o autor do post
             */
            if($_is_community) {
                $_show_post = true;
            } else {
                if("private" === get_post_status()) {
                    $_is_the_author = get_current_user_id() === get_the_author_meta('ID');
                    if($_is_the_author) {
                        $_show_post = true;
                    }
                } else {
                    $_show_post = true;
                }
            }

            if($_show_post): ?>
                <div class="grid-item"> <?php get_template_part( 'partes-templates/posts'); ?> </div>
                <?php
            endif;

        endwhile;
        ?>
	</div>

	<div class="col-xs-12">
		<div class="text-center">
			<?php paginacao_personalizada(); ?>
		</div>
	</div>

<?php else : get_template_part('partes-templates/none'); ?>

<?php endif; ?>