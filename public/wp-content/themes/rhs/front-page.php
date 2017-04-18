<?php
get_header();
?>

<div class="row">
	<!-- Container -->
	<div class="col-xs-12 col-sm-6 col-md-9">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12"></div>
		</div>
		<div class="row">
			<?php 
				if(have_posts()) :
					while(have_posts()) : the_post();
						//Pega o content-front-page.php para mostrar na pagina front-page os posts.
						get_template_part( 'partes-templates/pagina/content', 'front-page' ); 
					endwhile;
				else :
					get_template_part('partes-templates/pagina/content', 'none');
			?>
					
			<?php 
				endif;
			?>
		</div>
	</div>

	<!-- Sidebar -->
	<div class="col-xs-6 col-md-3"></div>
</div>

<?php
get_footer();