<?php get_header(); ?>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<?php get_template_part('partes-templates/carousel' ); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<!-- form de busca -->				
			</div>
		</div>
		<?php get_template_part( 'partes-templates/loop-posts'); ?>

<?php get_footer();