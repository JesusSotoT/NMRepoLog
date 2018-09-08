<?php 
    echo '  <script> 
                var cliente = "'. $_SESSION['accelog_nombre_instancia'] .'";
                var id_cliente = "'. $_SESSION['accelog_idempleado'] .'"; 
            </script>';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Inovekia</title>
        <link rel="shortcut icon" href="public/images/icono.png">
        <!-- Bootstrap -->
        <link href="../../libraries/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="../../libraries/font-awesome-4.7.0/css/font-awesome.css" rel="stylesheet">
        <!-- SweetAlert -->
        <link href="../../libraries/sweetalert/css/sweetalert.css" rel="stylesheet">
        <!-- Datatables -->
        <link href="../../libraries/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
        <link href="../../libraries/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
        <link href="../../libraries/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
        <link href="../../libraries/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
        <link href="../../libraries/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
        <!-- Datetimepicker -->
        <link href="../../libraries/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
        <!-- Inovekia -->
        <link href="css/inovekia.css" rel="stylesheet">
    </head>

    <body>
        
        <div class="row">
            <div class="col-md-12">
                <form id="frm">
                    <label>Descargar documento de relaciones:</label>
                    <button id="btn_relaciones" type="button" class="btn btn-primary">Descargar</button>
                    <label>Descargar documento de formulario uno:</label>
                    <button id="btn_formulario_uno" type="button" class="btn btn-primary">Descargar</button>
                    <label>Descargar documento de seguimiento:</label>
                    <button id="btn_seguimiento" type="button" class="btn btn-primary">Descargar</button>
                </form>
            </div>
        </div>

        <!-- jQuery -->
        <script src="../../libraries/jquery.min.js"></script>
        <!-- Bootstrap -->
        <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
        <!-- SweetAlert -->
        <script src="../../libraries/sweetalert/js/sweetalert.min.js"></script>
        <!-- Datatables -->
        <script src="../../libraries/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="../../libraries/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
        <script src="../../libraries/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="../../libraries/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
        <!-- Datetimepicker -->
        <script src="../../libraries/bootstrap-datetimepicker/js/moment.js"></script>
        <script src="../../libraries/bootstrap-datetimepicker/js/moment-local.js"></script>
        <script src="../../libraries/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
       <!-- Inovekia -->
        <script src="js/general.js"></script>
        <script src="js/descargar.js"></script>

    </body>
</html>
