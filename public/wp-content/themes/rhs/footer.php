


		</section>

		<footer class="footer container">
			<section>
					<p><?php bloginfo( 'description' ); ?></p>
			</section>

			<section>
	            <?php
	                wp_nav_menu( array(
	                    //MenudoTopo vem de um register feito nas functions onde o mesmo entra em contato com o menu do wordpress.
	                    'menu'              => 'MenuFundo',
	                    'theme_location'    => 'MenuFundo',
	                    'depth'             => 0,
	                    'menu_class'        => 'nav navbar-nav navbar-default menu-fundo',
	                    'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
	                    'walker'            => new WP_Bootstrap_Navwalker())
	                );
	            ?>
			</section>
		</footer>
		<?php wp_footer(); ?>
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	    <!-- JavaScript Latest compiled and minified JavaScript -->
	    <script src="<?php echo get_template_directory_uri(); ?>/assets/bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>