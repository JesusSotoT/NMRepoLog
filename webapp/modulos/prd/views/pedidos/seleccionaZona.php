<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Selecciona zona de comanda</title>
        <link rel="stylesheet" href="">

        <!--Archivos css-->
        <link rel="stylesheet" type="text/css" href="css/reset.css">
        <link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="css/pedidos/pedidos.css">
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/pedidos/zona.css">


        <!--Archivos js-->
        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/jquery-ui.js"></script>
        <script type="text/javascript" src="js/jquery.scrollTo.js"></script>
        <script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/pedidos/pedidos.js"></script>

        <script type="text/javascript"> 
            $(document).ready(function() {
                pedidos.zona();
            });
        </script>
    </head>
    <body>
        <div class="col-md-12 container-fluid" >
            <div class="col-md-12 well"><?php
                foreach ($lugares["rows"] as $key => $value) {
                    echo "<div id=" . $value["id"] . " class='col-md-3 div-btn'>" . $value["lugar"] . "</div>";
                } ?>
            </div>
        </div>
    </body>
</html>