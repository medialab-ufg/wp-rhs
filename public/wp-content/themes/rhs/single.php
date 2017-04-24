<?php get_header(); ?>

<div class="row">
	<!-- Container -->
	<div class="col-xs-9">
		<div class="row">
			<div class="col-xs-12"></div>
		</div>
		<div class="row">
			<?php 
				while (have_posts()): the_post();
			?>
			<article id="post-<?php the_ID(); ?>">
				<div class="col-xs-12">
					<?php	
						//Pega o paineldosposts para mostrar na pagina front-page os posts.
						get_template_part( 'partes-templates/painel-single', get_post_format()); 
					?>
				</div>
			</article><!-- #post-## -->
			<?php endwhile;	?>
		</div>
	</div>

	<!-- Sidebar -->
	<div class="col-xs-3"><?php get_sidebar(); ?></div>
</div>

<?php get_footer();