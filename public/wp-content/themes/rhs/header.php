<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>TITLE</title>

        <!-- Bootstrap - Latest compiled and minified CSS -->
        <link rel="stylesheet" type="text/css" href="assets/bootstrap/css/bootstrap.min.css">

        <link rel="stylesheet" type="text/css" href="assets/css/style.css">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
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
                    <a class="navbar-brand navbar-btn pull-left" href="#"><img src="assets/images/logo.png" class="img-responsive"></a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#">Faça seu login</a></li>
                        <p class="navbar-text">ou</p>
                        <li><a href="#">Cadastre-se</a></li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container -->
        </nav>
    </header>

    <!-- Tag header to second nav -->
    <header>
        <!-- sencond navBar -->
        <nav class="navbar navbar-default second-nav">
            <div class="container">
                <form class="form-search-rhs navbar-form" id="navBarSearchForm">
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
            </div><!-- /.container -->
        </nav>
    </header>