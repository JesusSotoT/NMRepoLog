<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>NMSERVER</title>
        <base href="<?php echo $servidor_path; ?>" />
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
        <!-- NMServer -->
        <link href="css/nmserver.css" rel="stylesheet">
    </head>

    <body>
        
        <div class="content-fluid">
            <div class="row form-group">
                <form id="frm">
                    <div class="col-md-3">
                        <label for="version">Version del codigo:</label>
                        <input type="text" id="version" name="version" class="form-control requerido" />
                    </div>
                    <div class="col-md-3">
                        <label for="zip">Seleccionar Zip Android:</label>
                        <input type="file" id="zip_android" name="zip_android" class="requerido" />
                    </div>
                    <div class="col-md-3">
                        <label for="zip">Seleccionar Zip Windows:</label>
                        <input type="file" id="zip_windows" name="zip_windows" class="requerido" />
                    </div>
                </form>
                <div class="col-md-2">
                    <button id="btn_cargar" class="btn btn-primary">Cargar</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table id="data_table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Version</th>
                                <th>Fecha de actualizacion</th>
                                <th>Estatus</th>
                                <th>Descargar Android</th>
                                <th>Descargar Windows</th>
                            </tr>
                        </thead>
                        <tbody id="data_table_body">
                        </tbody>
                    </table>
                </div>
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
        <!-- NMServer -->
        <script src="js/general.js"></script>
        <script src="js/catalogos.js"></script>
        <script src="js/actualizar.js"></script>

    </body>
</html>
