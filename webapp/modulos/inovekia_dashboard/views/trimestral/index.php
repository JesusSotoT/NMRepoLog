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

        <style>
          .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 120px;
            height: 120px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
          }

          @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
          }

          @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
          }
        </style>
    </head>

    <body>
        
        <div class="container-fluid">
            <div class="row form-group">
                <div class="col-md-3">
                    <label>Desde:</label>
                    <input type="text" name="desde" id="desde" class="form-control" />
                </div>
                <div class="col-md-3">
                    <label>Hasta:</label>
                    <input type="text" name="hasta" id="hasta" class="form-control" />
                </div>
                <div class="col-md-3">
                    <label>Numero de folio:</label>
                    <select name="folio" id="folio" class="form-control">
                        <option value="">Selecciona un folio</option>
                        <?php
                            foreach ($folios as $folio) {
                                echo "<option value='". $folio["folio"] ."'>". $folio["folio"] ."</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Consultor:</label>
                    <select name="consultor" id="consultor" class="form-control"></select>
                </div>
            </div>
            
            <div class="row form-group">
                <div class="col-md-3">
                    <button id="reporte_micro_mercado" class="btn btn-primary btn-block">Reporte Micro Mercado</button>
                </div>
                <div class="col-md-3">
                    <button id="analisis_micro_mercado" class="btn btn-primary btn-block">Analisis Micro Mercado</button>
                </div>
                <div class="col-md-3">
                    <button id="foto_micro_empresario" class="btn btn-primary btn-block">Foto Micro Empresario</button>
                </div>
                <div class="col-md-3">
                    <button id="analisis_financiero" class="btn btn-primary btn-block">Analisis Financiero</button>
                </div>
            </div>

            <div class="row form-group">
                <div class="col-md-3">
                    <button id="plan_accion" class="btn btn-primary btn-block">Plan de Acción</button>
                </div>
                <div class="col-md-3">
                    <button id="ife" class="btn btn-primary btn-block">IFE/INE</button>
                </div>
                <div class="col-md-3">
                    <button id="carta_autoempleo" class="btn btn-primary btn-block">Carta de Autoempleo</button>
                </div>
                <div class="col-md-3">
                    <button id="recibo_luz" class="btn btn-primary btn-block">Recibo de Luz</button>
                </div>
            </div>

            <div class="row form-group">
                <div class="col-md-3">
                    <button id="lista_raya_hombre" class="btn btn-primary btn-block">Lista de Raya Hombres</button>
                </div>
                <div class="col-md-3">
                    <button id="ife_empleado_hombre" class="btn btn-primary btn-block">IFE/INE Empleados Hombres</button>
                </div>
                <div class="col-md-3">
                    <button id="lista_raya_mujer" class="btn btn-primary btn-block">Lista de Raya Mujeres</button>
                </div>
                <div class="col-md-3">
                    <button id="ife_empleado_mujer" class="btn btn-primary btn-block">IFE/INE Empleados Mujeres</button>
                </div>
            </div>

            <div class="row form-group">
                <div class="col-md-3">
                    <button id="rfc_actualizada" class="btn btn-primary btn-block">RFC Actualizada</button>
                </div>
            </div>

            <!--div class="row form-group">
                <div class="col-md-3">
                    <button id="reportar" class="btn btn-success btn-block">Hacer reporte</button>
                </div>
            </div-->

            <!-- Modal -->
              <div data-backdrop="static" class="modal fade" id="modal_carga" role="dialog">
                <div class="modal-dialog">
                
                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Descargando la informacíon</h4>
                    </div>
                    
                    <div class="modal-body">
                      <div class="row">
                        <div class="col-md-4"> </div>
                        <div class="col-md-4"> 
                          <div class="loader"> </div>
                        </div>
                      </div>  
                    </div>

                    <div class="modal-footer">
                      <h5 class="modal-title">Este proceso puede demorar, no recargues la pagina.</h5>
                    </div> 

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
        <script src="js/trimestral.js"></script>

    </body>
</html>
