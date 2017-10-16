

</div><!-- /.container -->
</section><!-- /.section -->
<?php if(!RHSLogin::is_login_via_app()) : ?>
    <footer class="footer hidden-print">
        <section class="footerDescricao">
            <p> -- <?php bloginfo( 'description' ); ?> - </p>
        </section>
        <section class="footerMenu">
            <nav class="navbar navbar-default">
                <div class="container">
                    <?php
                    /*
                    * menuFundo vem de um register feito nas functions onde o mesmo entra em contato com o menu do
                    * Wordpress.
                    */
                    menuRodape();
                    ?>
                </div>
            </nav>
        </section>

        <section class="corporation">
            <div class="bordar"></div>
            <img alt="Licença Creative Commons" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icreativecommons.png" style="border-width:0">
            <a href="http://creativecommons.org/licenses/by-nc-nd/3.0/deed.pt" rel="license" target="_blank"> Conteúdos do site sob Licença&nbsp;Creative Commons - Atribuição-Não Comercial-Sem Derivados 3.0 Não Adaptada.</a>
        </section>
    </footer>
<?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>