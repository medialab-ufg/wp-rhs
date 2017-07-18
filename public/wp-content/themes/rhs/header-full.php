<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#00b4b4">
    <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/favicon.ico"/>
    <title><?php wp_title( '|', true, 'right' );
        bloginfo( 'name' ); ?></title>
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>"/>

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
    <nav class="navbar navbar-default">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".bs-example-navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand navbar-btn pull-left" href="<?php bloginfo('url'); ?>"><img src="<?php bloginfo('template_directory'); ?>/assets/images/logo.png" class="img-responsive"></a>
        </div>
        <div class="collapse navbar-collapse bs-example-navbar-collapse primeiro-menu">
            <div class="container">
                <?php
                global $RHSUser;
                if(my_wp_is_mobile()){
                    get_search_form();
                }
                ?>
                <ul class="nav navbar-nav <?php if(!my_wp_is_mobile()):?>navbar-right dropdown-menu-right no-mobile<?php else:?>mobile-nav<?php endif;?>">
                    <?php if(!is_user_logged_in()): ?>
                        <li><a href="<?php echo wp_login_url(); ?>" style="color: #00b4b4">Faça seu login</a></li>
                        <span class="navbar-text">ou</span>
                        <li><a href="<?php echo wp_registration_url(); ?>" style="color: #00b4b4">Cadastre-se</a></li>
                    <?php else : ?>
                        <li class="dropdown user-dropdown hidden-xs">
                            <a href="#notifications-panel" class="dropdown-toggle user-dropdown-link" data-toggle="dropdown"  data-hover="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i data-count="2" class="glyphicon glyphicon-bell notification-count"></i>
                            </a>
                            <ul class="dropdown-menu notify-drop">
                                <div class="notify-drop-title">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-6">Notificações (<b>2</b>)</div>
                                        <div class="col-md-6 col-sm-6 col-xs-6 text-right">
                                            <a href="" class="rIcon allRead" data-tooltip="tooltip" data-placement="bottom" title="Marcar todos lido">
                                                <i class="fa fa-dot-circle-o"></i> Marcar como Lidas
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!-- end notify title -->
                                <!-- notify content -->
                                <div class="drop-content">
                                    <li>
                                        <div class="col-md-3 col-sm-3 col-xs-3">
                                            <div class="notify-img">
                                                <img src="http://placehold.it/45x45" alt="" class="img-circle">
                                            </div>
                                        </div>
                                        <div class="col-md-9 col-sm-9 col-xs-9 pd-l0">
                                            <a href="#">Fabiano Alencar</a>. 
                                            <a href="#">Votou em um...</a> 
                                            <a href="" class="rIcon" title="Marcar como lido">
                                                <i class="fa fa-dot-circle-o"></i>
                                            </a>
                                            <hr>
                                            <p class="time">12/02/2017 14:33</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="col-md-3 col-sm-3 col-xs-3">
                                            <div class="notify-img">
                                                <img src="http://placehold.it/45x45" alt="" class="img-circle">
                                            </div>
                                        </div>
                                        <div class="col-md-9 col-sm-9 col-xs-9 pd-l0">
                                            <a href="#">Fabiano Alencar</a>. 
                                            <a href="#">Comentou em um...</a> 
                                            <a href="" class="rIcon" title="Marcar como lido">
                                                <i class="fa fa-dot-circle-o"></i>
                                            </a>
                                            <hr>
                                            <p class="time">12/02/2017 13:33</p>
                                        </div>
                                    </li>
                                </div>
                                <div class="notify-drop-footer text-center">
                                    <a href="notificacoes"><i class="fa fa-eye"></i> Veja todas as notificações</a>
                                </div>
                            </ul>
                        </li><!-- /dropdown -->
                        <?php if(!my_wp_is_mobile()): ?>
                        <li class="dropdown user-dropdown">
                        <?php else : ?>
                        <li class="menu-item">
                        <?php endif; ?>
                                <a href="#" class="dropdown-toggle user-dropdown-link" data-toggle="dropdown" data-hover="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <?php echo $RHSUser->get_user_data('display_name'); ?>
                                    <?php echo get_avatar($RHSUser->getUserId()); ?>
                                    <?php if(!my_wp_is_mobile()): ?>
                                        <i class="icon-textDown fa fa-angle-down"></i>
                                    <?php endif; ?>
                                </a>
                    <?php if(my_wp_is_mobile()): ?>
                        </li>
                    <?php else : ?>
                        <ul class="dropdown-menu">
                    <?php endif; ?>
                            <li class="menu-item pub">
                                <a href="<?php echo home_url(RHSRewriteRules::POST_URL); ?>">
                                    <i class="icones-dropdown fa fa-pencil-square-o" aria-hidden="true"></i> Publicar Post
                                </a>
                            </li>
                        <?php
                        $current_user = wp_get_current_user();
                        if (user_can( $current_user, 'administrator' ) || user_can( $current_user, 'editor' )) : ?>
                            <li class="menu-item">
                                <a href="<?php echo admin_url();?>">
                                    <i class="icones-dropdown fa fa-tachometer" aria-hidden="true"></i> Painel
                                </a>
                            </li>
                        <?php endif; ?>
                            <li class="menu-item perf">
                                <a href="<?php echo get_author_posts_url($RHSUser->getUserId()); ?>">
                                    <i class="icones-dropdown fa fa-eye" aria-hidden="true"></i> Meu Perfil
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="<?php echo home_url(RHSRewriteRules::POSTAGENS_URL);?>">
                                    <i class="icones-dropdown fa fa-list-alt" aria-hidden="true"></i> Minhas Postagens
                                </a>
                            </li>
                            <li class="menu-item hidden-sm hidden-md hidden-lg">
                                <a href="notificacoes">
                                    <i class="icones-dropdown fa fa-list-alt" aria-hidden="true"></i> Notificações(<b>2</b>)
                                </a>
                            </li>
                            <li class="menu-item sair">
                                <a href="<?php echo wp_logout_url(); ?>">
                                    <i class="icones-dropdown fa fa-sign-out" aria-hidden="true"></i> Sair
                                </a>
                            </li>
                        </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="collapse navbar-collapse bs-example-navbar-collapse segundo-menu">
            <div class="container">
                <?php
                if(!my_wp_is_mobile()){
                    get_search_form();
                }
                if(my_wp_is_mobile()):
                    menuDropDownMobile();
                else :
                    ?>
                    <ul class="nav navbar-nav navbar-right dropdown-menu-right dropdown-ipad">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-menu-hamburger"></span> Menu</a>
                            <?php
                            /*
                            * menuDropDown vem de um register feito nas functions onde o mesmo entra em contato com o menu do wordpress.
                            */
                            menuTopoDropDown();
                            ?>
                        </li>
                    </ul>

                    <?php
                    /*
                    * SegundoMenu vem de um register feito nas functions onde o mesmo entra em contato com o menu do
                    * Wordpress.
                    * O mesmo é o que exibe os links para Contato e Ajuda.
                    */
                    menuTopo();
                    ?>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</header> <!-- /.header -->

<section>
    <div class="container">

