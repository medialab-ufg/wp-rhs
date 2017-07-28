<?php get_header(); ?>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<?php get_template_part('partes-templates/carousel' ); ?>
			</div>
		</div>
		<!--form de busca
		<div class="row">
			<div class="col-xs-12">
				 			
			</div>
		</div>
		-->	
		<?php get_template_part( 'partes-templates/loop-posts'); ?>

<?php get_footer();