<div class="row">
	<div class="col-xs-12 col-md-12">
		<header class="page-header">
			<h1 class="titulo-page"><?php _e( 'Não há posts', 'rhs' ); ?>
				<span style="float: right; margin-top: -10px"><?php get_search_form(); ?></span>
			</h1>
		</header>
		<?php
		if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p><?php printf( __( 'Deseja criar seu primeiro post? <a href="%1$s">Comece por aqui</a>.', 'rhs' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

		<?php else : ?>

			<p style="background: #FFF; padding: 15px; font-weight: bold"><?php _e( 'Parece que não podemos encontrar o que você está procurando. Talvez a pesquisa possa ajudar.', 'rhs' ); ?></p>
			<?php
				
		endif; ?>
	</div>
</div>