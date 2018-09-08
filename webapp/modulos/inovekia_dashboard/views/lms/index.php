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
        
        <div class="content-fluid">
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table id="data_table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Curso</th>
                                <th>Reproducir</th>
                            </tr>
                        </thead>
                        <tbody id="data_table_body">
                            <tr>
                                <td>Atención a clientes</td>
                                <td><a href='javascript:mostrarLms(<?php echo $_SESSION['accelog_idempleado']; ?>, 1);'><i class='fa fa-play-circle-o'></i></a></td>
                            </tr>
                            <tr>
                                <td>Operaciones contables</td>
                                <td><a href='javascript:mostrarLms(<?php echo $_SESSION['accelog_idempleado']; ?>, 2);'><i class='fa fa-play-circle-o'></i></a></td>
                            </tr>
                            <tr>
                                <td>Inventarios</td>
                                <td><a href='javascript:mostrarLms(<?php echo $_SESSION['accelog_idempleado']; ?>, 3);'><i class='fa fa-play-circle-o'></i></a></td>
                            </tr>
                            <tr>
                                <td>Hardware y Software</td>
                                <td><a href='javascript:mostrarLms(<?php echo $_SESSION['accelog_idempleado']; ?>, 4);'><i class='fa fa-play-circle-o'></i></a></td>
                            </tr>
                            <tr>
                                <td>Capacidad de endeudamiento</td>
                                <td><a href='javascript:mostrarLms(<?php echo $_SESSION['accelog_idempleado']; ?>, 5);'><i class='fa fa-play-circle-o'></i></a></td>
                            </tr>
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
                        <h4 class="modal-title">Curso</h4>
                    </div>
                    <div class="modal-body">
                    <?php   
                        if($_SESSION['accelog_nombre_instancia'] != "inovekia"){
                            echo '<div class="row" style="display: none;">';
                        } else {
                            echo '<div class="row">';
                        }
                    ?>
                            <div class="col-md-6">
                                <label>Empresario:</label>
                                <select id="empresario" class="form-control" ></select>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <iframe id="scorm" src=""></iframe>  
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
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
        <script src="js/lms.js"></script>

    </body>
</html>
