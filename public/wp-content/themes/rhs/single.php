<?php get_header(); ?>

<div class="row">
	<!-- Container -->
	<div class="col-xs-12 col-sm-6 col-md-9">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12"></div>
		</div>
		<div class="row">
			<?php 
				while (have_posts()): the_post();
			?>
			<article id="post-<?php the_ID(); ?>">
				<div class="col-xs-12 col-sm-6 col-md-12">
					<?php	
						//Pega o paineldosposts para mostrar na pagina front-page os posts.
						get_template_part( 'partes-templates/paineldosposts', get_post_format()); 

					?>
				</div>
			</article><!-- #post-## -->
			<?php endwhile;	?>
		</div>
	</div>

	<!-- Sidebar -->
	<div class="col-xs-6 col-md-3"></div>
</div>

<?php get_footer();