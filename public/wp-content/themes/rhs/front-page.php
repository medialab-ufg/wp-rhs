<?php get_header(); ?>

<div class="row">
	<!-- Container -->
	<div class="col-xs-12 col-sm-9 col-md-8">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<?php
					//get_template_part('partes-templates/carousel' );
				?>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<!-- form de busca -->				
			</div>
		</div>
		<div class="row">
			<?php 
				if(have_posts()) :
					while (have_posts()): the_post();
			?>
			<div class="col-xs-12 col-sm-6 col-md-6">
					<?php	
						//Pega o paineldosposts para mostrar na pagina front-page os posts.
						get_template_part( 'partes-templates/paineldosposts');
					?>
			</div>
			<?php 
					endwhile;
				else :
					get_template_part('partes-templates/content', 'none'); 
				endif;
			?>
		</div>
	</div>

	<!-- Sidebar -->
	<div class="col-xs-6 col-sm-3 col-md-4"></div>
</div>

<?php get_footer();