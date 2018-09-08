<script src="../../libraries/select2/dist/js/select2.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />
<script language='javascript' src='js/bootstrap-datepicker.es.js'></script>

<!-- Modificaciones RC -->
<script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
<script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
<script src="../../libraries/export_print/buttons.html5.min.js"> </script>
<script src="../../libraries/export_print/jszip.min.js"></script>
<link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/buttons.dataTables.min.css">

<script type="text/javascript" charset="utf-8">
    $(document).ready(function() 
    {
        inicializa_movimientos3();
        $.fn.modal.Constructor.prototype.enforceFocus = function () {};
        $("#tipo").val(2).trigger("change")

        $('#fecha_recepcion').datepicker({
                    format: "yyyy-mm-dd",
                    language: "es"
            });
        
    });
     
    </script>
    <style>
.row
{
    margin-bottom:20px;
}
.container
{
    margin-top:20px;
}


</style>
<?php
require "views/partial/modal-generico.php";
?>
<div class="container well">
    <div class="row">
        <div class="col-xs-12 col-md-12"><h3>Recepcion de Traspasos</h3></div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-12 table-responsive">
            <table id="tabla-data" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                <thead>
                    <tr><th># de Traspaso</th><th>Fecha</th><th>Origen</th><th>Destino</th><th>Solicitante</th><th>Referencia</th><th>Accion</th></tr>
                </thead>
                <tbody id='trs'>
                </tbody>
            </table>
        </div>
    </div>            
</div>
<!-- <script language='javascript' src='../../libraries/dataTable/js/datatables.min.js'></script> -->
<script language='javascript' src='../../libraries/dataTable/js/dataTables.bootstrap.min.js'></script>

<link rel="stylesheet" href="../../libraries/dataTable/css/datatables.min.css">
<link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
<script language='javascript' src='js/inventarios.js'></script>
<script language='javascript' src='js/bootstrap-datepicker.min.js'></script>
<link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker.min.css">

<!--AQUI ESTAN LOS MODALS-->

<div id='modal-recepcion' principal-scroll='1' class="modal fade bs-recepcion-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabelRecepcion">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header panel-heading" style='background-color:#337ab7;color:#FFFFFF;'>
                <h4 id="modal-label">Recepcion</h4>
            </div>
      <div class="modal-body well">
      <div class="row">
                <div class="col-xs-4 col-md-offset-1 col-md-4">
                    <b>Numero de Traspaso:</b>
                </div>
                <div class="col-xs-6 col-md-7 input-group">
                    <label id='num_traspaso'></label>
                    <input type='hidden' id='id_traspaso' class='form-control'>
                </div>
        </div>
        <div class="row">
                <div class="col-xs-4 col-md-offset-1 col-md-4">
                    <b>Almacen Origen:</b>
                </div>
                <div class="col-xs-6 col-md-7 input-group">
                    <span id='origen'></span>
                    <input type='hidden' id='id_origen_real' value='<?php echo $idAlmacenTransito; ?>'>
                </div>
        </div>
        <div class="row">
                <div class="col-xs-4 col-md-offset-1 col-md-4">
                    <b>Almacen Destino:</b>
                </div>
                <div class="col-xs-6 col-md-7 input-group">
                   <span id='destino'></span>
                   <input type='hidden' id='id_destino'>
                </div>
        </div>
        <div class="row">
                <div class="col-xs-4 col-md-offset-1 col-md-4">
                    <b>Fecha:</b>
                </div>
                <div class="col-xs-6 col-md-7 input-group">
                    <span id='fecha_tras_r'></span>
                </div>
        </div>
        <div class="row">
                <div class="col-xs-4 col-md-offset-1 col-md-4">
                    <b>Solicitante:</b>
                </div>
                <div class="col-xs-6 col-md-7 input-group">
                    <span id='solicitante_r'></span>
                </div>
        </div>
        <div class="row">
                <div class="col-xs-4 col-md-offset-1 col-md-4">
                    <b>Referencia:</b>
                </div>
                <div class="col-xs-6 col-md-7 input-group">
                    <span id='referencia_tras_r'></span>
                </div>
        </div>

        <div class="row">
                <div class="col-xs-4 col-md-offset-1 col-md-4">
                    <b>Fecha Recepcion:</b>
                </div>
                <div class="col-xs-6 col-md-7 input-group">
                    <input type='text' id='fecha_recepcion'>
                </div>
        </div>

        <div class="row">
                <div class="col-xs-4 col-md-offset-1 col-md-4">
                    <b>Comentario:</b>
                </div>
                <div class="col-xs-6 col-md-7 input-group">
                    <input type='text' id='comentario' value='N/A'>
                </div>
        </div>
        
        <div class="row" style='margin-top:5px;'>
            <div class="col-xs-7 col-md-offset-1 col-md-11">
                            <table id='tabla'>
                            </table>
                </div>
        </div>
      </div>
            <div class="modal-footer">
                <button class='btn btn-default btn-sm' onclick='guardar_recepcion()'>Guardar</button><button class='btn btn-default btn-sm' onclick='cerrar_recepcion()'>Cerrar sin Guardar</button>
            </div>      
    </div>
  </div>
</div>

<a id='printer' style='width:10px;color:white;' >.</a>