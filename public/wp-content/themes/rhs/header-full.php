<!DOCTYPE html>
<html lang="pt-br">
<?php
$description = get_bloginfo() . ' - ' . get_bloginfo('description');
$user_id = get_current_user_id();
global $RHSUsers;
global $RHSNotifications;
global $RHSComunities;
?>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#00b4b4">
    <meta name="description" content="RHS | <?php echo $description ?>">
    <meta name="keywords" content="notícias, acolhimento, pnh, política nacional de humanização, sus, humaniza sus, rhs, rede, publicações, eventos, relatos de experiência, saúde, saúde mental">
    <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/favicon.ico"/>
    <title> <?php wp_title( '|', true, 'right' ); echo $description; ?> </title>
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>"/>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <?php wp_head(); ?>
</head>
<body style="<?php if(RHSLogin::is_login_via_app()) : ?>background: #003c46;<?php endif; ?>" <?php body_class(); ?> >

<!-- SDK Facebook -->
<div id="fb-root"></div>
<!-- Fim SDK Facebook -->

<!-- Tag header para o Primeiro Menu -->
<header id="navBar-top">
    <nav class="navbar navbar-default navbar-static-top rhs_menu">
        <div class="container menu-container">

            <div class="navbar-header">
                <?php if(!RHSLogin::is_login_via_app()) : ?>
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar6">
                        <span class="sr-only">Alternar navegação</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                <?php endif; ?>
                <a class="navbar-brand text-hide" href="<?php if(!RHSLogin::is_login_via_app()) { bloginfo('url'); } else { echo '#'; } ?>" style="<?php if(RHSLogin::is_login_via_app()) : ?>width: 355px; margin-top: 22px; margin-bottom: -17px;<?php endif; ?>">RHS</a>
            </div>
            <div id="navbar6" class="navbar-collapse collapse primeiro-menu">

                <?php if (my_wp_is_mobile()) { get_search_form(); } ?>

                <?php get_template_part('partes-templates/links-top' ); ?>

                <ul class="nav navbar-nav <?php if(!my_wp_is_mobile()):?>navbar-right dropdown-menu-right no-mobile<?php else:?>mobile-nav <?php if(!is_user_logged_in()){ echo 'not-logged'; } endif;?>">

                    <?php if (!is_user_logged_in()): ?>
                        <li>
                            <a href="#" class="dropdown-toggle user-dropdown-link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" id="loginBox"> Faça login <span class="caret"></span></a>
                            <ul class="dropdown-menu dropdown-lr animated slideInRight" role="menu" aria-labelledby="loginBox">
                                <li class="login-box-container">
                                    <?php get_template_part("partes-templates/login-box"); ?>
                                </li>
                            </ul>
                        </li>
                        
                        <span class="navbar-text">ou</span>
                        <li> <a href="<?php echo wp_registration_url(); ?>" class="cadastrar">Cadastre-se</a> </li>
                    <?php else : ?>
                        <?php $notifications_number = $RHSNotifications->get_news_number($user_id); ?>
                        <li class="dropdown user-dropdown hidden-xs">
                            <a id="button-notifications" href="#notifications-panel" class="dropdown-toggle user-dropdown-link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i data-count="<?php echo $notifications_number; ?>" class="glyphicon glyphicon-bell <?php if($notifications_number){ ?>notification-count<?php } ?>"></i>
                            </a>
                            <ul class="dropdown-menu notify-drop">
                                <div class="notify-drop-title">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-6">Notificações (<b><?php echo $notifications_number; ?></b>)</div>
                                        
                                    </div>
                                </div>
                                <!-- end notify title -->
                                <!-- notify content -->

                                <div class="drop-content">
                                    
                                    <?php foreach ($RHSNotifications->get_notifications($user_id) as $notification): ?>
                                        
                                        <li>
                                            <div class="col-md-3 col-sm-3 col-xs-3">
                                                <div class="notify-img">
                                                    <?php echo $notification->getImage(); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-9 col-sm-9 col-xs-9 pd-l0">
                                                <?php echo $notification->getText(); ?>
                                                <hr>
                                                <p class="time"><?php echo $notification->getTextDate(); ?></p>
                                            </div>
                                        </li>
                                        
                                    <?php endforeach; ?>
                                    
                                </div>
                                <div class="notify-drop-footer text-center">
                                    <a href="<?php echo home_url('notificacoes'); ?>"><i class="fa fa-eye"></i> Veja todas as notificações</a>
                                </div>
                            </ul>
                        </li><!-- /dropdown -->
                        <?php if(!my_wp_is_mobile()): ?>
                        <li class="dropdown user-dropdown">
                        <?php else : ?>
                        <li class="menu-item">
                        <?php endif; ?>
                                <a href="#" class="dropdown-toggle user-dropdown-link" data-toggle="dropdown" data-hover="dropdown" role="button" aria-haspopup="true" aria-expanded="false" id="drop_perfil">
                                    <?php echo $RHSUsers->get_user_data('display_name'); ?>
                                    <?php echo get_avatar($RHSUsers->getUserId()); ?>
                                    <?php if(!my_wp_is_mobile()): ?>
                                        <i class="icon-textDown fa fa-angle-down"></i>
                                    <?php endif; ?>
                                </a>
                    <?php if(my_wp_is_mobile()): ?>
                        </li>
                    <?php else : ?>
                        <ul class="dropdown-menu cl-menu" aria-labelledby="drop_perfil" style="min-width: 200px">
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
                                <a href="<?php echo get_author_posts_url($RHSUsers->getUserId()); ?>">
                                    <i class="icones-dropdown fa fa-user" aria-hidden="true"></i> Meu Perfil
                                </a>
                            </li>
                        <?php if (user_can( $current_user, 'administrator' ) || user_can( $current_user, 'editor' )) {?>
                            <li class="menu-item perf">
                                <a href="<?php echo home_url(RHSRewriteRules::STATISTICS); ?>">
                                    <i class="icones-dropdown fa fa-area-chart" aria-hidden="true"></i> Estatísticas
                                </a>
                            </li>
                        <?php } ?>
                            <li class="menu-item">
                                <a href="<?php echo home_url(RHSRewriteRules::POSTAGENS_URL);?>">
                                    <i class="icones-dropdown fa fa-list-alt" aria-hidden="true"></i> Minhas Postagens
                                </a>
                            </li>

                            <?php
	                            $is_author = is_author( $user_id );
                                if ( $RHSComunities->get_communities_by_member( $user_id )) {
                                    ?>
                                    <li class="menu-item">
                                        <ul style="padding: 0; width: 75%;">
			                                <?php
			                                foreach ( $RHSComunities->get_comunities_objects_by_user( $user_id ) as $key => $comunidade ){
				                                if ( $comunidade->is_member() ) {
					                                ?>
                                                    <li>
                                                        <a style="padding: 10px 20px 10px 10px" href="<?php echo $comunidade->get_url() ?>"> <?php echo $comunidade->get_name() ?> </a>
                                                    </li>
					                                <?php
				                                }
			                                }
			                                ?>
                                        </ul>
                                        <a href="javascript:void(0)">
                                            <i class="fa fa-cubes" aria-hidden="true"></i> Minhas comunidades
                                        </a>

                                    </li>
                                    <?php
                                }
                            ?>
                            <li class="menu-item hidden-sm hidden-md hidden-lg">
                                <a href="notificacoes">
                                    <i class="icones-dropdown fa fa-list-alt" aria-hidden="true"></i> Notificações(<b>2</b>)
                                </a>
                            </li>
                            <li class="menu-item sair">
                                <a href="<?php echo wp_logout_url(home_url()); ?>">
                                    <i class="icones-dropdown fa fa-sign-out" aria-hidden="true"></i> Sair
                                </a>
                            </li>
                        </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="collapse navbar-collapse segundo-menu"> 
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
        <!--/.nav-collapse -->
        <!--/.container-fluid -->
    </nav>
</header> <!-- /.header -->

<section>
    <div class="container">

