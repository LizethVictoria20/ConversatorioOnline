<!doctype html>
<html lang="es">
    <head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
        
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Módulo de pago</title>

        <!-- Bootstrap core CSS -->
        <link href="dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="form-validation.css" rel="stylesheet">
        
        <style>
            .carousel-item {
                height: 65vh;
                min-height: 350px;
                background: no-repeat center center scroll;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
            }
        </style>

    </head>

    <body class="bg-light">
         <header>
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">                
                <div class="carousel-inner" role="listbox">
                    <!-- Slide One - Set the background image for this slide in the line below -->
                    <div class="carousel-item active" style="background-image: url('assets/img/banner.jpg');height: 200px;"></div>                   
                </div>                
            </div>
        </header>
        <div class="container">       
            <div class="py-5 text-center">
                <h2>Resultado del pago</h2>
                <p class="lead">A continuación mostraremos el resultado de su proceso de pago para el Conversatorio Mundial Online / Agosto 05 de 2019.</p>
            </div>
            <div style="display: none" class="resultado alert alert-primary" role="alert"></div>        
            <div class="row">
                <div class="col-md-8 order-md-1">
                    <h4 class="mb-3">Detalle de su compra</h4>
                    <?php
                    include 'controller/responsePayu.php';
                    echo $responsePayu;
                    ?>
                </div>
            </div>

            <a href="https://www.conversatoriomundial.com" type="button" class="btn btn-primary">Volver a Inicio</a>
            <footer class="my-5 pt-5 text-muted text-center text-small">
                <p class="mb-1">Conversatorio Mundial Online es un producto de Invertir Mejor <br>
                                Copyright Invertir Mejor ® / Todos los derechos reservados<br>
                                www.InvertirMejor.com / comunicaciones@invertirmejor.com</p>
                <ul class="list-inline">
                    <li class="list-inline-item"><a href="https://invertirmejor.com/terminos-y-condiciones/" target="_blank">Privacidad</a></li>
                    <li class="list-inline-item"><a href="https://invertirmejor.com/terminos-y-condiciones/" target="_blank">Términos</a></li>
                </ul>
            </footer>
        </div>

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="dist/jquery.min.js"></script>
        <script src="dist/js/bootstrap.min.js"></script>       
    </body>
</html>
