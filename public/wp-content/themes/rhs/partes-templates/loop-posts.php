<?php 
	if(have_posts()) :
		while (have_posts()): 
			the_post();
?>
<div class="row display-row">
		<?php
				//Pega o paineldosposts para mostrar na pagina front-page os posts.
				get_template_part( 'partes-templates/posts');
			endwhile;
	?>
</div><!--display-row-->
<?php
else :
	get_template_part('partes-templates/none'); 
endif;
?>
<div class="row">
	<div class="col-xs-12">
		<div class="text-center">
			<?php paginacao_personalizada(); ?>
		</div>
	</div>
</div>