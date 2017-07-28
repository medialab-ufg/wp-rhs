<div class="panel panel-default padding-bottom">
	<div class="panel-heading" style="padding: 21px;">
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
			<div class="col-xs-12">
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
					<span class="post-date text-uppercase">
						<i class="fa fa-calendar" aria-hidden="true"></i> <?php the_time('d/m/Y'); ?>
					</span>
					<span class="post-user-edit">
						<?php edit_post_link( __( 'Editar Post', 'rhs' ), '<span class="divisor text-uppercase">', '</span>', null, 'btn' ); ?>
					</span>
					<div class="pull-right share share-wrap">
						<span class="hidden-print" style="">
							<?php //echo do_shortcode('[ssba]'); ?>

							<a data-site="" class="facebook_share" href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>" target="_blank">
								<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/facebook.png" title="Facebook" alt="Compartilhar no Facebook">
							</a>
							<a data-site="" class="twitter_share" href="http://twitter.com/share?url=<?php the_permalink(); ?>&amp;text=<?php the_title_attribute(); ?>&amp;via=RedeHumanizaSUS" target="_blank">
								<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/twitter.png" title="Twitter" alt="Compartilhar no Twitter">
							</a>
							<a href="whatsapp://send?text=<?php the_title_attribute( 'after= ' ); ?><?php the_permalink(); ?>" data-text="<?php the_title_attribute(); ?>" data-href="<?php the_permalink(); ?>" target="_top" onclick="window.parent.null" class="hidden-md hidden-lg">
								<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/whatsapp.png" title="Whatsapp" alt="Compartilhar no Whatsapp">
							</a>
							<a data-site="print" class="share_print share_link" href="#" onclick="window.print()">
								<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/print.png" title="Print" alt="Imprimir está página">
							</a>
						</span>
					</div>
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
					<?php the_tags('', '', ''); ?>
				</span>
			<?php endif; ?>
		</div>
	</div>
</div><!-- .panel .panel-default -->
<div class="panel panel-default hidden-print">
	<div class="panel-footer panel-comentarios">
		<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		?>
	</div>
</div>
