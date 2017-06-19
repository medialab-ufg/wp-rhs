<div class="panel panel-default padding-bottom">
	<div class="panel-heading">
		<div class="row post-titulo">
		<?php $userOBJ = new RHSUser(get_the_author_meta( 'ID' )); ?>
			<div class="col-xs-9 col-sm-11 col-md-10">
				<?php the_title( '<h1>', '</h1>' ); ?>
			</div>
			<div class="col-xs-3 col-sm-1 col-md-2 vdivide">
                <div class="votebox">
				    <?php do_action('rhs_votebox', get_the_ID()); ?>
                </div>
			</div>
			<div class="col-xs-9 col-sm-11 col-md-10">
				<div class="post-categories">
					<?php if(has_category()) : ?>
							<?php the_category(', '); ?>
					<?php endif; ?>	
				</div>
			</div>
			<div class="col-xs-12 col-md-12">
				<div class="post-meta">
					<span class="post-user-date">
						<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"  title="Ver perfil do usuário.">
						    <?php echo get_avatar($userOBJ->getUserId(),33); ?>
	                    </a>
	                    <span class="usuario">
							<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"  title="Ver perfil do usuário.">
								<?php the_author(); ?>
							</a>
						</span>
					</span>
					<span class="post-date text-uppercase"><i class="fa fa-calendar" aria-hidden="true"></i> <?php the_time('d/m/Y'); ?></span>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	<div class="panel-body content">
		<?php the_content(); ?>
	</div><!-- .paine-body -->
	<div class="panel-footer">
		<div class="tags-content">	
			<?php if(has_tag()) : ?>
				<span class="tags-list">
					<?php the_tags('', ''); ?>
				</span>
			<?php endif; ?>
		</div>
	</div>
</div><!-- .panel .panel-default -->
<div class="panel panel-default">
	<div class="panel-footer panel-comentarios">
		<?php  
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		?>
	</div>
</div>