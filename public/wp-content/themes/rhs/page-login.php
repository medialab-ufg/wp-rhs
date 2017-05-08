<?php get_header(); ?>

<div class="row">
	<!-- Container -->
	<div class="col-xs-12 col-md-9">
		<div class="row">
			<div class="col-xs-12"></div>
		</div>
		<div class="row">
			<?php
				$args = array(
				    'redirect' => home_url(),
				   );
			?>
			<?php wp_login_form($args); ?>
		</div>
	</div>

	<!-- Sidebar -->
	<div class="col-xs-12 col-md-3"><?php get_sidebar(); ?></div>
</div>

<?php get_footer();