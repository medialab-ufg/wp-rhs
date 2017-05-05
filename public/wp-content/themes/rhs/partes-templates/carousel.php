<div id="myCarousel" class="carousel slide">
	<ol class="carousel-indicators">
		<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
		<li data-target="#myCarousel" data-slide-to="1"></li>
		<li data-target="#myCarousel" data-slide-to="2"></li>
		<li data-target="#myCarousel" data-slide-to="3"></li>
		<li data-target="#myCarousel" data-slide-to="4"></li>
	</ol>
	<div class="carousel-inner" style="min-height: 320px; background-color: #fff">
		<?php if(have_posts()) :
				while (have_posts()): 
					the_post(); ?>
		<div class="item">
			<div class="row">
				<div class="col-xs-6">
					<?php if( has_post_thumbnail() ) :  ?>
						<a href="<?php the_permalink(); ?>">
							<img src="<?php get_the_post_thumbnail_url() ?>" alt="">
						</a>
					<?php endif; ?>
				</div>
				<div class="col-xs-6">
					<div class="carousel-caption">
						<a href="<?php the_permalink(); ?>"><?php the_title( '<h3>', '</h3>' ); ?></a>
						<p>
							<?php the_excerpt(); ?>
						</p>
					</div>
				</div>
			</div>
		</div>
		<?php endwhile; endif; ?>
	</div>
</div>
<?php wp_reset_query(); ?>