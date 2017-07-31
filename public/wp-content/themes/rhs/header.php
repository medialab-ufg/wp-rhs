<?php require_once('header-full.php'); ?>

<div class="row">
    <!-- Container -->
    <div class="col-xs-12 col-md-9">
        <?php if(is_user_logged_in()) : ?>
            <div class="row">
                <!-- Button Publicar e Ver Fila de Votação -->
                <?php get_template_part('partes-templates/buttons-top' ); ?>
            </div>
        <?php endif; ?>
        
