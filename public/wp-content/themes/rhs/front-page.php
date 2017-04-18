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
			$args = array('numberposts' => get_option( 'posts_per_page' ));
			$posts = get_posts( $args );
				if(have_posts()) :
					foreach($posts as $post) : setup_postdata( $post );
						//Pega o content-front-page.php para mostrar na pagina front-page os posts.
						get_template_part( 'partes-templates/pagina/content', 'front-page' ); 
					endforeach;
				else :
					get_template_part('partes-templates/pagina/content', 'none'); 
				endif;
			?>
		</div>
	</div>

	<!-- Sidebar -->
	<div class="col-xs-6 col-md-3"></div>
</div>

<?php
get_footer();