<style type="text/css">
	.post-categories{
		margin-bottom: 7px;
	}
	.post-categories a{
		color: #00b4b4;
		font-size: 11px;
    	letter-spacing: 2px;
    	font-weight: bold;
    	transition: all 0.3s ease-in-out;
    	text-transform: uppercase;
	}
	.post-categories a:hover{
		color: rgba(0, 180, 180, 0.34);
	}
	h1 {
		margin-top: 10px;
		padding-top: 0;
		margin-bottom: 8px;
		font-size: 29px;
		color: #2b2b2b;
    	font-weight: 700;
	}
	.post-meta a, .post-meta span{
		font-size: 11px;
		color: #8d8d8d;
	}
	.post-user-date{
		padding-right: 10px;
	    margin-right: 10px;
	    display: inline-block;
	    position: relative;
	    
	}
	.post-user-date a{
		text-decoration: none;
	}
	.post-user-date img{
		filter: grayscale(100%);
    	transition: 0.3s all ease-in-out;
	}
	.post-user-date img:hover{
		filter: grayscale(0%);
	}
	.usuario:after{
		content: "";
	    display: block;
	    position: absolute;
	    background-color: #d6d6d6;
	    width: 1px;
	    top: 5px;
	    bottom: 5px;
	    right: -1px;
	}
	.photo {
		margin-right: 8px;
    	display: inline-block;
		border-radius: 5px;
	}
	.content{
		padding-bottom: 0px;
	    margin-bottom: 0px;
	}
	.content p, .content div{
		line-height: 1.7 !important;
	    font-size: 15px !important;
	    color: #616161 !important;
	}
	.content img{
		border-radius: 8px;
		position: relative;
    	z-index: 100;
    	margin: 0 0 15px 0;
    	    vertical-align: middle;
	}
	.tags-content{
		margin-top: 35px;
	}
	.tags-content a{
		display: inline-block;
	    position: relative;
	    padding: 0 5px;
	    line-height: 18px;
	    margin-right: 10px;
	    font-size: 10px;
	    text-transform: uppercase;
	    border-radius: 100px;
	    border: 1px solid #e0e0e0;
	    font-weight: bold;
	    color: #8d8d8d;
	}
</style>
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
					<span class="post-date text-uppercase"><i class="fa fa-clock-o" aria-hidden="true"></i> <?php the_time('d/m/Y - H:i'); ?></span>
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