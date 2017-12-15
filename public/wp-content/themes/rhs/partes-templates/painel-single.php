<div class="panel panel-default padding-bottom">
	<div class="panel-heading" style="padding: 21px;">
		<div class="row post-titulo">
			<div class="col-xs-9 col-sm-11 col-md-10">
                <?php
                global $RHSNetwork;
                $_post_id = get_the_ID();
                $_post_author_id = get_the_author_meta( 'ID' );
                $_post_ = [
                    'id' => $_post_id,
                    'status' => get_post_status($_post_id),
                    'shares' => $RHSNetwork->get_post_total_shares($_post_id ),
                    'views'  =>  $RHSNetwork->get_post_total_views($_post_id ),
                    'author' => [ 'url' => esc_url( get_author_posts_url($_post_author_id) ),
                                  'href_title' => 'Ver perfil do usuário.'
                    ]
                ];

                the_title( '<h1>', '</h1>' );
                ?>

			</div>

            <div class="col-xs-3 col-sm-1 col-md-2 vdivide">
                <div class="votebox">
                    <?php
                    if( $_post_['status'] != 'private')
                        do_action('rhs_votebox', get_the_ID());
                    ?>
                </div>
            </div>

			<div class="col-xs-12">
				<div class="post-categories">
					<?php 
						if(has_category())
							the_category(', ');
					?>
				</div>
			</div>
			<div class="col-xs-12 col-md-12">
				<div class="post-meta">
					<span class="post-user-date">
						<a href="<?php echo $_post_['author']['url']; ?>" title="<?php echo $_post_['author']['href_title']; ?>">
						    <?php echo get_avatar($_post_author_id,33); ?>
						</a>
						<span class="usuario">
							<a href="<?php echo $_post_['author']['url']; ?>" title="<?php echo $_post_['author']['href_title']; ?>">
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
					<div class="pull-right share share-wrap col-md-5" style="padding: 0;">
						<div class="hidden-print">

                            <div class="col-md-4" style="padding: 10px 0 10px 0; text-align: right; margin-right: 7px;">
                                <?php do_action('rhs_follow_post_box', $_post_id); ?>
                            </div>

							<?php if( $_post_['status'] == 'publish') : ?>

                                <div class="col-md-3 alignright" style="padding: 0; border-left: 1px solid #d6d6d6; border-right: 1px solid #d6d6d6">

                                    <div class="voteboxNO">

                                        <div class="col-md-6" style="text-align: center; padding: 0 0 0 10px">
                                            <div style="width: 30px;">
                                                <span class="vTexto" style="font-size: 15px"> <?php echo $_post_['views']; ?> </span>
                                                <div class="vTexto" style="font-size: 13px !important;margin: 0">
                                                    <span class="glyphicon-eye-open glyphicon" style="color: black; font-size: 13px"></span> </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6" style="padding: 0 15px 0 5px">
                                            <div style="text-align: center; width: 30px;">
                                                <span class="vTexto" style="font-size: 15px"> <?php echo $_post_['shares']; ?> </span>
                                                <div class="vTexto" style="font-size: 13px !important;">
                                                    <?php /* ?>
                                                    <span class="label label-primary" style="padding-top: 5px">
                                                        <div class="glyphicon-share glyphicon" style="color: white; padding-top: 2px"></div>
                                                    </span>
                                                    <?php */ ?>
                                                    <div class="glyphicon-share glyphicon" style="color: black"></div>
                                                </div>




                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-3" style="padding: 0; text-align: right">
                                    <a data-site="" class="facebook_share" href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>" target="_blank">
                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/facebook.png" title="Facebook" alt="Compartilhar no Facebook">
                                    </a>
                                    <a data-site="" class="twitter_share" href="http://twitter.com/share?url=<?php the_permalink(); ?>&amp;text=<?php the_title_attribute(); ?>&amp;via=RedeHumanizaSUS" target="_blank">
                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/twitter.png" title="Twitter" alt="Compartilhar no Twitter">
                                    </a>
                                    <a href="whatsapp://send?text=<?php the_title_attribute( 'after= ' ); ?><?php the_permalink(); ?>" data-text="<?php the_title_attribute(); ?>" data-href="<?php the_permalink(); ?>" target="_top" onclick="window.parent.null" class="hidden-md hidden-lg whatsapp_share">
                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/whatsapp.png" title="Whatsapp" alt="Compartilhar no Whatsapp">
                                    </a>



                                </div>
							<?php endif;?>
                            <div class="col-md-1" style="padding: 0;">
                                <a data-site="print" class="share_print share_link" href="#" onclick="window.print()"> <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/print.png" title="Print" alt="Imprimir está página"> </a>
                            </div>

                        </div>

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
        <?php if (has_post_ufmun($_post_id)) : ?>
            <div class="relacionado">
				<span>Esse Post está relacionado à </span>
				<?php echo the_ufmun(); ?>
		    </div>
        <?php endif; ?>
		<?php if(has_tag()) : ?>
			<div class="tags-content">
				<span class="tags-list">
					<?php the_tags('', '', ''); ?>
				</span>
			</div>
		<?php endif; ?>
	</div>
</div><!-- .panel .panel-default -->
<?php
if (  $_post_['status'] != 'draft' && $_post_['status'] != 'voting-expired' && ( comments_open() || get_comments_number() ) ) { ?>
	<div class="panel panel-default hidden-print">
		<div class="panel-footer panel-comentarios">
			<?php comments_template(); ?>
		</div>
	</div>
<?php } ?>