<?php
?>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() 
    {
        inicializa_lista_car_prod('gral');
        inicializa_lista_car_prod('esp');
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
<input type='hidden' id='pestania_prod' value='<?php echo $_GET['p'] ?>'>
<div class="container well">
    <div class="row">
        <div class="col-xs-12 col-md-12"><h3>Características de productos.</h3></div>
    </div>
    <div class="row">
       <!-- Nav tabs -->
      <ul id='myTabs' class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#generales" aria-controls="generales" role="tab" data-toggle="tab">Características Generales</a></li>
        <li role="presentation"><a href="#especificas" aria-controls="especificas" role="tab" data-toggle="tab">Características Específicas</a></li>
      </ul>

      <!-- Tab panes -->
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="generales">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Listado de Características Generales.</h3>
              </div>
              <div class="panel-body">
                
              <div class="col-xs-12 col-md-12 table-responsive">
                  <div id='boton_virtual1'><button class='btn btn-primary btn-sm' data-toggle="modal" data-target=".bs-example-modal-sm" onclick="nueva_car('gral')">Nuevo <span class='glyphicon glyphicon-plus'></span></button></div>
                  <table id="tabla-gral" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                      <thead>
                          <tr><th>Id</th><th>Nombre</th><th>Modificar</th><th>Status</th></tr>
                      </thead>
                      <tbody id='trs_gral'>
                      </tbody>
                  </table>
              </div>
    
              </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="especificas">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Listado de Características Especificas.</h3>
              </div>
              <div class="panel-body">
                  <div class="col-xs-12 col-md-12 table-responsive">
                    <div id='boton_virtual2'><button class='btn btn-primary btn-sm' data-toggle="modal" data-target=".bs-example-modal-sm" onclick="nueva_car('esp')">Nuevo <span class='glyphicon glyphicon-plus'></span></button></div>
                    <table id="tabla-esp" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                            <tr><th>Id</th><th>Nombre</th><th>General</th><th>Modificar</th><th>Status</th></tr>
                        </thead>
                        <tbody id='trs_esp'>
                        </tbody>
                    </table>
                </div>
              </div>
            </div>
        </div>
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
<script language='javascript' src='http://transtatic.com/js/numericInput.min.js'></script>

<!--AQUI ESTAN LOS MODALS-->
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div id='blanco' style='width:300px;height:300px;background-color:white;z-index:1;position:absolute;color:green;'>&nbsp;&nbsp;Cargando...</div>
      <div class="modal-header panel-heading">
                <h4 id="modal-label">Nueva característica</h4>
            </div>
      <div class="modal-body">
        <div class="row">
                <div class="col-xs-4 col-md-6">
                    <input type='hidden' style='width:150px;' id='id' class='form-control'>
                </div>
        </div>
        <div class="row">
                <div class="col-xs-4 col-md-4">
                    <b id='nombre_label'>Nombre:</b>
                </div>
                <div class="col-xs-4 col-md-6">
                    <input type='text' style='width:150px;' id='nombre' class='form-control'>
                </div>
        </div>
        <div class="row">
                <div class="col-xs-4 col-md-4">
                    <b id='padre_label'>General:</b>
                </div>
                <div class="col-xs-4 col-md-6">
                    <select class='form-control' style='width:150px;' id='padre'>
                        <option value='0' tipo='0'>Ninguno</option>
                    </select>
                    <label id='label-warning' style='color:red;font-size:9px;'>No se puede guardar, falta llenar un o mas campos.</label>
                </div>
        </div>
        <div class="row">
                <div class="col-xs-4 col-md-4">
                    <b id='status_label'>Status:</b>
                </div>
                <div class="col-xs-4 col-md-6">
                    <select class='form-control' style='width:150px;' id='status'>
                        <option value='1'>Activo</option>
                        <option value='0'>Inactivo</option>
                    </select>
                </div>
        </div>
      </div>
            <div class="modal-footer">
                <button class='btn btn-default btn-sm' id='guardar'>Guardar</button><button class='btn btn-default btn-sm' onclick='cancelar_car_prod()'>Cancelar</button>
            </div>      
    </div>
  </div>
</div>