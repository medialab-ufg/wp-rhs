<?php get_header(); ?>

<div class="row">
	<!-- Container -->
	<div class="col-xs-12 col-md-9">
		<div class="row">
			<div class="col-xs-12">
				<?php
					//get_template_part('partes-templates/carousel' );
				?>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<!-- form de busca -->				
			</div>
		</div>
		<div class="row display-row">
			<?php 
				if(have_posts()) :
					while (have_posts()): 
						the_post();

						//Pega o paineldosposts para mostrar na pagina front-page os posts.
						get_template_part( 'partes-templates/paineldosposts');
					endwhile;	
			?>
		</div><!--display-row-->
		<div class="row">
			<div class="col-xs-12">
				<div class="text-center">
					<?php paginacao_personalizada(); ?>
				</div>
			</div>
			<?php
				else :
					get_template_part('partes-templates/content', 'none'); 
				endif;
			?>
		</div>
	</div>
	<!-- Sidebar -->
	<div class="col-xs-12 col-md-3"><?php get_sidebar(); ?></div>
</div>

<?php get_footer();