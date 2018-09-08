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
        
        <div class="content-fluid">
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table id="data_table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Usuario</th>
                                <th>Empresarios</th>
                                <th>Inadem</th>
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
                        <h4 class="modal-title">Empresarios</h4>
                    </div>
                    <div class="modal-body">
                        <table id="data_table_empresarios" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Empresario</th>
                                    <th>Seleccionar</th>
                                    <th>Visita</th>
                                    <!--<th>Seguimientos</th>
                                    <th>Formularios</th>-->
                                </tr>
                            </thead>
                            <tbody id="data_table_body_empresarios">
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para seleccionar empresarios -->
        <div id="modal-inadem" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Seguimiento Inadem</h4>
                    </div>
                    <div class="modal-body table-responsive">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Email:</label>
                                <input type="text" id="txt_correo" name="txt_correo" class="form-control"/>
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <button id="btn_abrir_inadem" class="btn btn-primary">Consultar</button>
                            </div>
                        </div>

                        <table id="data_table_inadem" class="table table-striped table-bordered">
                            <thead>
                                <tr id="modal-inadem-encabezados">
                                    
                                </tr>
                            </thead>
                            <tbody id="data_table_body_empresarios">
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para definir visitas -->
        <div id="modal-visita" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Visita</h4>
                    </div>
                    <div class="modal-body">
                        <form id="frm">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <div id="fecha"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="mapa"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="btn_guardar_visita" type="button" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para definir seguimientos -->
        <div id="modal-seguimiento" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Seguimientos</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong><label>Ultimo curso visto:</label></strong><br>
                                <small><label id="ultimo_curso"></label></small>
                            </div>
                            <div class="col-md-6">
                                <strong><label>Ultimo curso aprobado:</label></strong><br>
                                <small><label id="ultimo_curso_aprobado"></label></small>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <table id="data_table_seguimientos" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Ultimo slide visto</th>
                                            <th>Tiempo de reproducción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data_table_body_seguimientos">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para definir formularios -->
        <div id="modal-formulario" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Formularios</h4>
                    </div>
                    <div class="modal-body">
                        <table id="data_table_formularios" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Ultimo slide visto</th>
                                    <th>Tiempo de reproducción</th>
                                </tr>
                            </thead>
                            <tbody id="data_table_body_seguimientos">
                            </tbody>
                        </table>
                    </div>
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
        <script src="js/catalogos.js"></script>
        <script src="js/consultor.js"></script>

    </body>
</html>
