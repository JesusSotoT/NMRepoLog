<script type="text/javascript" charset="utf-8">
    $(document).ready(function() 
    {
        inicializa_listacred();
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
<input type='hidden' id='pestania' value='<?php echo $_GET['p'] ?>'>    
<div class="container well">
    <div class="row">
        <div class="col-xs-12 col-md-12"><h3>Configuraci&oacute;n de Tipos de Crédito</h3></div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-12 table-responsive">
            <div id='boton_virtual'><button class='btn btn-primary btn-sm' data-toggle="modal" data-target=".bs-example-modal-sm" onclick='nuevo_cred()'>Nuevo <span class='glyphicon glyphicon-plus'></span></button></div>
            <table id="tabla-data" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                <thead>
                    <tr><th>Id</th><th>Nombre</th><th>Clave</th><th>Modificar</th><th>Status</th></tr>
                </thead>
                <tbody id='trs'>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modificaciones RC -->
<script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
<script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
<script src="../../libraries/export_print/buttons.html5.min.js"></script>
<script src="../../libraries/export_print/jszip.min.js"></script>
<!--Button Print css -->
<link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">

<!--<script language='javascript' src='../../libraries/dataTable/js/datatables.min.js'></script>-->
<script language='javascript' src='../../libraries/dataTable/js/dataTables.bootstrap.min.js'></script>
<link rel="stylesheet" href="../../libraries/dataTable/css/datatables.min.css">
<link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
<script language='javascript' src='js/configuracion2.js'></script>

<!--AQUI ESTAN LOS MODALS-->
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div id='blanco' style='width:300px;height:320px;background-color:white;z-index:1;position:absolute;color:green;'>&nbsp;&nbsp;Cargando...</div>
      <div class="modal-header panel-heading">
                <h4 id="modal-label">Nuevo Tipo de Crédito</h4>
                <input type='hidden' id='idcred' value='0'>
            </div>
      <div class="modal-body">
        <div class="row">
                <div class="col-xs-4 col-md-4">
                    <b>Nombre:</b>
                </div>
                <div class="col-xs-4 col-md-6">
                    <input type='text' style='width:150px;' id='nombrecred' class='form-control'>
                </div>
        </div>
        <div class="row">
                <div class="col-xs-4 col-md-4">
                    <b>Clave:</b>
                </div>
                <div class="col-xs-4 col-md-6">
                    <input type='text' style='width:150px;' id='clavecred' class='form-control'>
                </div>
        </div>
        <div class="row">
                <div class="col-xs-4 col-md-4">
                    <b>Status:</b>
                </div>
                <div class="col-xs-4 col-md-6">
                    <select id='status' class='form-control' style='width:150px;'>
                        <option value='1'>Activo</option>
                        <option value='0'>Inactivo</option>
                    </select>
                </div>
        </div>
      </div>
            <div class="modal-footer">
                <button class='btn btn-default btn-sm' onclick='guardar_cred()'>Guardar</button><button class='btn btn-default btn-sm' onclick='cancelar_cred()'>Cancelar</button>
            </div>      
    </div>
  </div>
</div>