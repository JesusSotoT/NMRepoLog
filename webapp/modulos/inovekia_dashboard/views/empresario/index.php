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
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <link href="css/inovekia.css" rel="stylesheet">
    </head>

    <body>
        
        <div class="content-fluid">
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table id="data_table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Folio</th>
                            </tr>
                        </thead>
                        <tbody id="data_table_body">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal para seleccionar empresarios -->
        <div id="modal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Folio</h4>
                    </div>
                    <div class="modal-body">
                        <form id="frm">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <input type="text" id="folio" name="folio" class="form-control requerido" placeholder="Folio">
                                </div>
                            </div>
                            <input type="hidden" id="id" name="id" value="0" class="requerido">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="btn_guardar_folio" type="button" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- jQuery -->
        <script src="../../libraries/jquery.min.js"></script>
        <script src="../../libraries/jquery-ui-1.11.4/jquery-ui.min.js"></script>
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
        <script src="js/catalogos.js"></script>
        <script src="js/empresario.js"></script>

    </body>
</html>
