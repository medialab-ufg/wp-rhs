<?php if(have_posts()) : ?>

	<div class="clearfix masonry">
		<div class="grid-sizer"></div>
		<div class="gutter-sizer"></div>
		<?php while (have_posts()): 
			the_post();
		?>
			<div class="grid-item">
				<?php
                    $is_the_author = ( is_user_logged_in() && is_author(get_current_user_id()) );
                    if( "private" != get_post_status() ||  $is_the_author) {
                        //Pega o painel dos posts para mostrar na pagina front-page os posts.
                        get_template_part( 'partes-templates/posts');
                    }
				?>
			</div>
		<?php endwhile; ?>
	</div>

	<div class="col-xs-12">
		<div class="text-center">
			<?php paginacao_personalizada(); ?>
		</div>
	</div>
<?php else : get_template_part('partes-templates/none'); ?>
<?php endif; ?>