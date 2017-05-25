<div class="panel panel-default padding-bottom">
	<div class="panel-heading">
		<div class="row post-titulo">
		<?php $userOBJ = new RHSUser(get_the_author_meta( 'ID' )); ?>
			<div class="col-xs-9 col-sm-11 col-md-11">
				<?php the_title( '<h3>', '</h3>' ); ?>
			</div>
			<div class="col-xs-3 col-sm-1 col-md-1 vdivide">
				<?php do_action('rhs_votebox', get_the_ID()); ?>
			</div>
			<div class="col-xs-4 col-md-3">
				<div class="img-usuario">
					<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"  title="Ver perfil do usuário.">
						<img src="<?php echo $userOBJ->getAvatarImage(); ?>" alt="..." class="img-circle">
					</a>	
				</div>
			</div>
			<div class="col-xs-8 col-md-9 col-md-pull-2 col-xs-pull-2">
				<div class="col-xs-12 col-md-12">
					<p class="nome-author">
						<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"  title="Ver perfil do usuário.">
							<?php the_author(); ?>
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
	<div class="col-xs-12">
		<div class="tags-content">	
		<?php if(has_category()) : ?>
			<span class="tags-list">
				<i class="fa fa-archive" aria-hidden="true" title="Categoria"></i>
				<?php the_category(' '); ?>
			</span>
		<?php endif; ?>		
		<?php if(has_tag()) : ?>
			<span class="tags-list">
				<i class="fa fa-tags" aria-hidden="true"  title="Tags"></i>
				<?php the_tags(' '); ?>
			</span>
		<?php endif; ?>
		</div>
	</div>
	<div class="panel-footer panel-comentarios">
		<?php  
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		?>
	</div>
</div><!-- .panel .panel-default -->
