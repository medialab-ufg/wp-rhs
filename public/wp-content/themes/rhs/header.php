<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
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
        <!-- Tag header to first nav -->
        <header id="navBar-top">
            <nav class="navbar navbar-default navbar-static-top">
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

                            <!-- // Para um uso futuro pois o mesmo se econtra fixo sem a necessidade do adimin do Wordpress. 
                            <?php
                                wp_nav_menu( array(
                                    //MenudoTopo vem de um register feito nas functions onde o mesmo entra em contato com o menu do wordpress.
                                    'menu'              => 'MenuTopo',
                                    'theme_location'    => 'MenuTopo',
                                    'depth'             => 0,
                                    'container'         => 'div',
                                    'container_class'   => 'collapse navbar-collapse',
                                    'container_id'      => 'bs-example-navbar-collapse-1',
                                    'menu_class'        => 'nav navbar-nav navbar-right',
                                    'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
                                    'walker'            => new WP_Bootstrap_Navwalker())
                                );
                            ?> -->
                </div><!-- /.container -->
            </nav>
        </header>

        <!-- Tag header to second nav -->
        <header>
            <!-- Segundo menu -->
            <nav class="navbar navbar-default segundo-menu">
                <div class="container">
                    <form class="form-search-rhs navbar-form navbar-left" id="menuPesquisa">
                        <div class="form-group" style="display: inline;">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Digite aqui o que você procura." size="15" maxlength="128">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-default">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </form>
                    <ul class="nav navbar-nav navbar-right dropdown-menu-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-menu-hamburger"></span> MENU</a>
                            <?php
                                //SegundoMenu vem de um register feito nas functions onde o mesmo entra em contato com o menu do wordpress.
                                wp_nav_menu( array(
                                    'menu'              => 'MenuDropdDown',
                                    'theme_location'    => 'MenuDropdDown',
                                    'depth'             => 1,
                                    'container'         => false,
                                    'menu_class'        => 'dropdown-menu',
                                    'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
                                    'walker'            => new WP_Bootstrap_Navwalker())
                                );
                            ?>
                        </li>
                    </ul>
                    <?php
                        //SegundoMenu vem de um register feito nas functions onde o mesmo entra em contato com o menu do wordpress.
                        wp_nav_menu( array(
                            'menu'              => 'SegundoMenu',
                            'theme_location'    => 'SegundoMenu',
                            'depth'             => 0,
                            'menu_class'        => 'nav navbar-nav navbar-right',
                            'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
                            'walker'            => new WP_Bootstrap_Navwalker())
                        );
                    ?>
                </div><!-- /.container -->
            </nav>
        </header>

        <section>
            <div class="container">