<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/favicon.ico" />
        <title><?php wp_title( '|', true, 'right' ); bloginfo( 'name' );?></title>
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <?php wp_head(); ?>
    </head>
    <body>
        <!-- Tag header para o Primeiro Menu -->
        <header id="navBar-top">
            <nav class="navbar navbar-default navbar-fixed-top">
                <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand navbar-btn pull-left" href="<?php bloginfo('url'); ?>"><img src="<?php bloginfo('template_directory'); ?>/assets/images/logo.png" class="img-responsive"></a>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-right">
                                <li><a href="#">Faça seu login</a></li>
                                <span class="navbar-text">ou</span>
                                <li><a href="#">Cadastre-se</a></li>
                        </ul>
                    </div>
                </div><!-- /.container -->
            </nav>
        </header> <!-- /.header -->

        <!-- Tag header para o Segundo Menu -->
        <header>
            <!-- Segundo menu -->
            <nav class="navbar navbar-default segundo-menu">
                <div class="container">
                    <!-- Pega o template de busca para o menu. O mesmo se encontra no tema com o nome de searchform.php -->
                    <?php get_search_form(); ?>
                    <!--End Form-->
                    <ul class="nav navbar-nav navbar-right dropdown-menu-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-menu-hamburger"></span> MENU</a>
                            <?php
                                /*
                                * menuDropDown vem de um register feito nas functions onde o mesmo entra em contato com o menu do 
                                * wordpress.
                                */
                                menuTopoDrodDown();
                            ?>
                        </li>
                    </ul>
                    <?php
                        /*
                        * SegundoMenu vem de um register feito nas functions onde o mesmo entra em contato com o menu do 
                        * Wordpress.
                        */
                        menuTopo();
                    ?>
                </div><!-- /.container -->
            </nav>
        </header> <!-- /.header -->

        <section>
            <div class="container">