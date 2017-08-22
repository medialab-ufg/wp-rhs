<?php if(have_posts()) : ?>

	<div class="clearfix masonry">
		<div class="grid-sizer"></div>
		<div class="gutter-sizer"></div>
		<?php while (have_posts()): 
			the_post();
		?>
			<div class="grid-item">
				<?php
					//Pega o paineldosposts para mostrar na pagina front-page os posts.
					get_template_part( 'partes-templates/posts');
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