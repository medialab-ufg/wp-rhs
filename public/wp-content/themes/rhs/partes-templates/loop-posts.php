<?php if(have_posts()) : ?>
	<div class="row">
		<div class="grid">
			<div class="grid-sizer col-xs-12 col-sm-12"></div>
				<div class="grid-item">
					<?php while (have_posts()): 
						the_post();
					?>
						<div class="col-xs-12 col-sm-6">
							<?php
								//Pega o paineldosposts para mostrar na pagina front-page os posts.
								get_template_part( 'partes-templates/posts');
							?>
						</div>
					<?php endwhile; ?>
				</div>
		</div>
	</div>
	<div class="col-xs-12">
		<div class="text-center">
			<?php paginacao_personalizada(); ?>
		</div>
	</div>
<?php else : get_template_part('partes-templates/none'); ?>
<?php endif; ?>