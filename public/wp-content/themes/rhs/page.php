<?php get_header(); ?>
    <?php while (have_posts()): the_post();?>
        
        <div class="row">
            <div class="col-xs-12">
                <h1 class="titulo-page"><?php the_title(); ?></h1>
                <div class="pull-right edit-page">
                    <?php edit_post_link( __( 'Editar Página', 'rhs' ), '<span class="text-uppercase">', '</span>', null, 'btn btn-default' ); ?>
                </div>
            </div>
        </div>
        <div class="row">
			
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
		</div>
        <?php if ( comments_open() || get_comments_number() ) { ?>
            <div class="panel panel-default hidden-print">
                <div class="panel-footer panel-comentarios">
                    <?php comments_template(); ?>
                </div>
            </div>
        <?php } ?>
    <?php endwhile;	?>

<?php get_footer();
