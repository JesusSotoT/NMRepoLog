<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Inovekia</title>
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
        <!-- Inovekia -->
        <link href="css/inovekia.css" rel="stylesheet">
    </head>

    <body>
        
        <div id="listado" class="content-fluid">
            <div class="row">
                <div class="col-md-2 col-md-offset-1">
                    <div class="form-group">
                        <select id="organismo" name="organismo" class="form-control">
                            <option value="0">Selecciona un organismo</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select id="consultor" name="consultor" class="form-control">
                            <option value="0">Selecciona un consultor</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select id="folio" name="folio" class="form-control">
                            <option value="">Selecciona un folio</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select id="empresario" name="empresario" class="form-control">
                            <option value="">Selecciona un empresario</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row item">
                <div class="col-md-4 col-md-offset-1 bg-gray text-center">
                    <label class="separador">Curso</label>
                </div>
                <div class="col-md-5 text-center">
                    <label class="bg-gray separador text-center porcentaje">Empresarios</label>
                    <label class="bg-gray separador text-center porcentaje">Sin iniciar</label>
                    <label class="bg-gray separador text-center porcentaje">Iniciado</label>
                    <label class="bg-gray separador text-center porcentaje">Completado</label>
                </div>
            </div>
            <div class="row hidden item" id="base">
                <div class="col-md-4 col-md-offset-1 bg-info">
                    <label id="base-nombre" class="separador"></label>
                </div>
                <div class="col-md-5 text-center">
                    <label id="base-total" class="bg-gray separador text-center porcentaje"></label>
                    <label id="base-no" class="bg-gray separador text-center porcentaje"></label>
                    <label id="base-visto" class="bg-gray separador text-center porcentaje"></label>
                    <label id="base-completo" class="bg-gray separador text-center porcentaje"></label>
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
        <!-- Google Maps -->
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDUjprJFp24ioojmBHhXqT78c40vU4nILY&libraries=visualization" async defer></script>
        <!-- Inovekia -->
        <script src="js/general.js"></script>
        <script src="js/reporte/cursos/curso.js"></script>

    </body>
</html>
