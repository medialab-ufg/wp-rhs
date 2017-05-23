<div class="panel panel-default display-panel">
	<div class="panel-heading">
        <?php $userOBJ = new RHSUser(get_the_author_meta( 'ID' )); ?>
		<div class="row post-titulo espacamento-topo">
			<div class="col-xs-3 col-md-3">
				<div class="img-usuario">
					<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title="Ver o perfil do(a) <?php the_author_meta('display_name'); ?>.">
						<img src="<?php echo $userOBJ->getAvatarImage(); ?>" alt="..." class="img-circle">
					</a>	
				</div>
			</div>
			<div class="col-xs-6 col-md-7 col-md-pull-1 col-xs-pull-1">
				<div class="col-xs-12 col-md-12">
					<span class="nome-author">
						<a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><?php the_author(); ?></a>
					</span>
				</div>
				<div class="col-xs-12 col-md-12">
					<span class="post_city"><?php the_ufmun(); ?></span>
				</div>
				<div class="col-xs-12 col-md-12">
					<span class="post-date text-uppercase"><?php the_time('D, d/m/Y - H:i'); ?></span>
				</div>
			</div>
			<div class="col-xs-3 col-md-2 col-sm-2 vdivide">
				<?php do_action('rhs_votebox', get_the_ID()); ?>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	<div class="panel-body">
		<a href="<?php the_permalink(); ?>"><?php the_title( '<h3 class="panel-title">', '</h3>' ); ?></a>
		<?php if( has_post_thumbnail() ) :	?>
			<a href="<?php the_permalink(); ?>">
				<img src="<?php echo get_the_post_thumbnail_url() ?>" alt="">
			</a>
		<?php
			endif;
			the_excerpt(); 
		?>
	</div><!-- .paine-body -->
	<div class="panel-footer">
		<div class="row">
			<div class="col-xs-6">
				<?php
					if ( comments_open() ) :
					  comments_popup_link( '0 COMENTÁRIOS', '<i class="fa fa-commenting-o" aria-hidden="true"></i> 1 COMENTÁRIO', '<i class="fa fa-commenting-o" aria-hidden="true"></i> % COMENTÁRIOS', 'comments-link', 'Não é permitido Comentários neste post');
					endif;
				?>
			</div>
		</div>
	</div>
</div><!-- .panel .panel-default -->
