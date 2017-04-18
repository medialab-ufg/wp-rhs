<article id="post-<?php the_ID(); ?>">

	<?php if ( has_post_thumbnail() ) :
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'img-responsive' );

		$post_thumbnail_id = get_post_thumbnail_id( $post->ID );

		$thumbnail_attributes = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'img-responsive' );

		// Calculate aspect ratio: h / w * 100%.
		$ratio = $thumbnail_attributes[2] / $thumbnail_attributes[1] * 100;
		?>

		<div class="panel-image" style="background-image: url(<?php echo esc_url( $thumbnail[0] ); ?>);">
			<div class="panel-image-prop" style="padding-top: <?php echo esc_attr( $ratio ); ?>%"></div>
		</div><!-- .panel-image -->

	<?php endif; ?>
	<div class="col-xs-12 col-sm-6 col-md-6">
		<div class="panel-group">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="row post-titulo">
						<div class="col-xs-4 col-md-3">
							<div class="img-usuario">
								<img src="<?php echo get_avatar_url(get_the_author_meta( 'ID' )); ?>" alt="..." class="img-circle">
							</div>
						</div>
						<div class="col-xs-8 col-md-9 col-md-pull-1 col-xs-pull-2">
							<div class="col-xs-12 col-md-12">
								<span class="nome-author">
									<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"  title="Ver perfil do usuário.">
										<?php the_author_meta('user_firstname'); ?> <?php the_author_meta('user_lastname'); ?>
									</a>
								</span>
							</div>

							<div class="col-xs-12 col-md-12"><span class="post-date text-uppercase"><?php the_time('D, d/m/Y - H:i'); ?></p></div>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="panel-body">
					<a href="<?php the_permalink(); ?>"><?php the_title( '<h3>', '</h3>' ); ?></a>
					
					<?php
						the_content( sprintf(
							__( 'Continue lendo<span class="screen-reader-text"> "%s"</span>', 'rhs' ),
							get_the_title()
						) );
					?>
				</div><!-- .paine-body -->
			</div><!-- .panel .panel-default -->
		</div><!-- .paine-group -->
	</div>
</article><!-- #post-## -->