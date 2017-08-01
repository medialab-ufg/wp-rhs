<?php if(have_posts()) : ?>
	<div class="grid" data-masonry='{ "itemSelector": ".grid-item", "columnWidth": ".grid-sizer" }'>
		<div class="grid-sizer col-xs-12 col-sm-12 col-md-12"></div>
		<?php while (have_posts()): 
			the_post();
		?>
			<div class="grid-item col-xs-12 col-sm-6 col-md-6">
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