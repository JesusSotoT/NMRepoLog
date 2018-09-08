
<?php
?>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() 
    {
        inicializa_lista_clas_prod('dep');
        inicializa_lista_clas_prod('fam');
        inicializa_lista_clas_prod('lin');
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
        <div class="col-xs-12 col-md-12"><h3>Configuraci&oacute;n de clasificadores de productos.</h3></div>
    </div>
    <div class="row">
       <!-- Nav tabs -->
      <ul id='myTabs' class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#departamentos" aria-controls="departamentos" role="tab" data-toggle="tab">Departamentos</a></li>
        <li role="presentation"><a href="#familias" aria-controls="familias" role="tab" data-toggle="tab">Familias</a></li>
        <li role="presentation"><a href="#lineas" aria-controls="lineas" role="tab" data-toggle="tab">Líneas</a></li>
      </ul>

      <!-- Tab panes -->
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="departamentos">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Listado de Departamentos.</h3>
              </div>
              <div class="panel-body">
                
              <div class="col-xs-12 col-md-12 table-responsive">
                  <div id='boton_virtual1'><button class='btn btn-primary btn-sm' data-toggle="modal" data-target=".bs-example-modal-sm" onclick="nuevo_clas_dep('dep')">Nuevo <span class='glyphicon glyphicon-plus'></span></button></div>
                  <table id="tabla-dep" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                      <thead>
                          <tr><th>Id</th><th>Nombre</th><th>Modificar</th></tr>
                      </thead>
                      <tbody id='trs_dep'>
                      </tbody>
                  </table>
              </div>
    
              </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="familias">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Listado de Familias.</h3>
              </div>
              <div class="panel-body">
                  <div class="col-xs-12 col-md-12 table-responsive">
                    <div id='boton_virtual2'><button class='btn btn-primary btn-sm' data-toggle="modal" data-target=".bs-example-modal-sm" onclick="nuevo_clas_dep('fam')">Nuevo <span class='glyphicon glyphicon-plus'></span></button></div>
                    <table id="tabla-fam" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                            <tr><th>Id</th><th>Nombre</th><th>Departamento</th><th>Modificar</th></tr>
                        </thead>
                        <tbody id='trs_fam'>
                        </tbody>
                    </table>
                </div>
              </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="lineas">
             <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Listado de Líneas.</h3>
              </div>
              <div class="panel-body"> 
                    <div class="col-xs-12 col-md-12 table-responsive">
                      <div id='boton_virtual3'><button class='btn btn-primary btn-sm' data-toggle="modal" data-target=".bs-example-modal-sm" onclick="nuevo_clas_dep('lin')">Nuevo <span class='glyphicon glyphicon-plus'></span></button></div>
                      <table id="tabla-lin" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                          <thead>
                              <tr><th>Id</th><th>Nombre</th><th>Familia</th><th>Modificar</th><th>Status</th></tr>
                          </thead>
                          <tbody id='trs_lin'>
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
<script language='javascript' src='js/configuracion.js'></script>
<script language='javascript' src='../../libraries/numericInput.min.js'></script>

<!--AQUI ESTAN LOS MODALS-->
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div id='blanco' style='width:300px;height:300px;background-color:white;z-index:1;position:absolute;color:green;'>&nbsp;&nbsp;Cargando...</div>
      <div class="modal-header panel-heading">
                <h4 id="modal-label">Nuevo Departamento</h4>
                <input type='hidden' id='idclasprod' value='0'>
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
                    <b id='depende_label'>Depende de:</b>
                </div>
                <div class="col-xs-4 col-md-6">
                    <select class='form-control' style='width:150px;' id='depende' onchange='padreclas(this)'>
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
                    <select id='status' class='form-control' style='width:150px;'>
                        <option value='1'>Activo</option>
                        <option value='0'>Inactivo</option>
                    </select>
                </div>
        </div>
      </div>
            <div class="modal-footer">
                <button class='btn btn-default btn-sm' id='guardar'>Guardar</button><button class='btn btn-default btn-sm' onclick='cancelar_clas_prod()'>Cancelar</button>
            </div>      
    </div>
  </div>
</div>