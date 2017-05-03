<?php get_header(); ?>

    <div class="row">
        <!-- Container -->
        <div class="col-xs-12 col-md-9">
            <div class="row">
                <div class="col-xs-12">
                    <h1 class="titulo-page">Fila de Votação</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <p class="box-descricao-page">
                        A fila de votação é o espaço de curadoria coletiva de posts da rede. Quando um post recebe 5 votos, vai automaticamente para a página principal da rede e fica visível para todos. Assim, ele passa a ocupar o primeiro lugar na lista de posts a cada voto recebido. Quem pode votar? Se você teve um post com cinco votos, automaticamente passa à condição de votante. Participe!
                    </p>
                </div>
            </div>
			<?php get_template_part( 'partes-templates/voting-queue'); ?>
        </div>
        <!-- Sidebar -->
        <div class="col-xs-12 col-md-3"><?php get_sidebar(); ?></div>
    </div>

<?php get_footer();