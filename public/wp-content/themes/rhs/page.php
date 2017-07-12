<?php RHSHtml::setTitulo(get_the_title()); ?>
<?php get_header(); ?>
		<div class="row">
			<?php 
				while (have_posts()): the_post();
			?>
			<article id="post-<?php the_ID(); ?>">
				<div class="col-xs-12">
					<div class="panel panel-default padding-bottom">
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
					</div><!-- .panel .panel-default -->
				</div>
			</article><!-- #post-## -->
			<?php endwhile;	?>
		</div>

<?php get_footer();