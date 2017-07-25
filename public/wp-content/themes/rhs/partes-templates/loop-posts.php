<?php if(have_posts()) : ?>
	<div class="row display-row">
		<?php
			while (have_posts()): 
				the_post();

				//Pega o paineldosposts para mostrar na pagina front-page os posts.
				get_template_part( 'partes-templates/posts');
			endwhile;	
		?>
	</div><!--display-row-->
	<div class="row">
		<div class="col-xs-12">
			<div class="text-right">
				<?php paginacao_personalizada(); ?>
			</div>
		</div>
	</div>
	<?php
		else :
			get_template_part('partes-templates/none'); 
	?>
<?php endif; ?>