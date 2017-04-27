<div class="panel panel-default">
	<div class="panel-heading">
		<div class="row post-titulo">
			<div class="col-xs-12">
				<?php the_title( '<h3>', '</h3>' ); ?>
			</div>
			<div class="col-xs-4 col-md-3">
				<div class="img-usuario">
					<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"  title="Ver perfil do usuário.">
						<img src="<?php echo get_avatar_url(get_the_author_meta( 'ID' )); ?>" alt="..." class="img-circle">
					</a>	
				</div>
			</div>
			<div class="col-xs-8 col-md-9 col-md-pull-2 col-xs-pull-2">
				<div class="col-xs-12 col-md-12">
					<p class="nome-author">
						<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"  title="Ver perfil do usuário.">
							<?php the_author_meta('user_firstname'); ?> <?php the_author_meta('user_lastname'); ?>
						</a>
					</p>
					<small class="localidade">Goiânia, Goias</small>
				</div>

				<div class="col-xs-12">
					<span class="post-date text-uppercase"><?php the_time('D, d/m/Y - H:i'); ?></span>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	<div class="panel-body content">
		<?php the_content(); ?>
	</div><!-- .paine-body -->
	<div class="panel-footer panel-comentarios">
		<?php  
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		?>
	</div>
</div><!-- .panel .panel-default -->