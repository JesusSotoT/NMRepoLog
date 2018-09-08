<script type="text/javascript" charset="utf-8">
    $(document).ready(function() 
    {
        inicializa_lista_cat('altas');
        inicializa_lista_cat('bajas');
        inicializa_lista_cat('bienes');
        inicializa_lista_cat('formulas');
        inicializa_lista_cat('inpc');
        inicializa_lista_cat('depr');
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

<input type='hidden' id='pestania' value='<?php echo $_GET['p'] ?>'>
<div class="container well">
    <div class="row">
        <div class="col-xs-12 col-md-12"><h3>Configuraci&oacute;n de Catalogos de Activos Fijos.</h3></div>
    </div>
    <div class="row">
       <!-- Nav tabs -->
      <ul id='myTabs' class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#altas" aria-controls="altas" role="tab" data-toggle="tab">Catalogo de Altas</a></li>
        <li role="presentation"><a href="#bajas" aria-controls="bajas" role="tab" data-toggle="tab">Catalogo de Bajas</a></li>
        <li role="presentation"><a href="#bienes" aria-controls="bienes" role="tab" data-toggle="tab">Categorias de Bienes</a></li>
        <li role="presentation"><a href="#formulas" aria-controls="formulas" role="tab" data-toggle="tab">Catalogo de Formulas</a></li>
        <li role="presentation"><a href="#inpc" aria-controls="inpc" role="tab" data-toggle="tab">INPC</a></li>
        <li role="presentation"><a href="#depr" aria-controls="depr" role="tab" data-toggle="tab">Porcentajes de Depreciacion</a></li>
      </ul>

      <!-- Tab panes -->
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="altas">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Catalogo de Altas.</h3>
              </div>
              <div class="panel-body">
                
              <div class="col-xs-12 col-md-12 table-responsive">
                  <div id='boton_virtual1'><button class='btn btn-primary btn-sm' data-toggle="modal" data-target=".bs-example-modal-sm" onclick="nuevo_cat('altas')">Nuevo <span class='glyphicon glyphicon-plus'></span></button></div>
                  <table id="tabla-altas" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                      <thead>
                          <tr><th>Id</th><th>Codigo</th><th>Nombre</th><th>Estatus</th><th>Modificar</th></tr>
                      </thead>
                      <tbody id='trs_altas'>
                      </tbody>
                  </table>
              </div>
    
              </div>
            </div>
        </div>
         <div role="tabpanel" class="tab-pane fade" id="bajas">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Catalogo de Bajas.</h3>
              </div>
              <div class="panel-body">
                
              <div class="col-xs-12 col-md-12 table-responsive">
                  <div id='boton_virtual2'><button class='btn btn-primary btn-sm' data-toggle="modal" data-target=".bs-example-modal-sm" onclick="nuevo_cat('bajas')">Nuevo <span class='glyphicon glyphicon-plus'></span></button></div>
                  <table id="tabla-bajas" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                      <thead>
                          <tr><th>Id</th><th>Codigo</th><th>Nombre</th><th>Estatus</th><th>Modificar</th></tr>
                      </thead>
                      <tbody id='trs_bajas'>
                      </tbody>
                  </table>
              </div>
    
              </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="bienes">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Categorias de Bienes.</h3>
              </div>
              <div class="panel-body">
                
              <div class="col-xs-12 col-md-12 table-responsive">
                  <div id='boton_virtual3'><button class='btn btn-primary btn-sm' data-toggle="modal" data-target=".bs-example-modal-sm" onclick="nuevo_cat('bienes')">Nuevo <span class='glyphicon glyphicon-plus'></span></button></div>
                  <table id="tabla-bienes" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                      <thead>
                          <tr><th>Id</th><th>Codigo</th><th>Nombre</th><th>Estatus</th><th>Modificar</th></tr>
                      </thead>
                      <tbody id='trs_bienes'>
                      </tbody>
                  </table>
              </div>
    
              </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="formulas">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Catalogo de Formulas.</h3>
              </div>
              <div class="panel-body">
                
              <div class="col-xs-12 col-md-12 table-responsive">
                  <div id='boton_virtual4'><button class='btn btn-primary btn-sm' data-toggle="modal" data-target=".bs-example-modal-sm" onclick="nuevo_cat('formulas')">Nuevo <span class='glyphicon glyphicon-plus'></span></button></div>
                  <table id="tabla-formulas" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                      <thead>
                          <tr><th>Id</th><th>Nombre</th><th>Formula</th><th>Estatus</th><th>Modificar</th></tr>
                      </thead>
                      <tbody id='trs_formulas'>
                      </tbody>
                  </table>
              </div>
    
              </div>
            </div>
        </div>
         <div role="tabpanel" class="tab-pane fade" id="inpc">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Catalogo de Indice Nacional de Precios al Consumidor.</h3>
              </div>
              <div class="panel-body">
                
              <div class="col-xs-12 col-md-12 table-responsive">
                  <div id='boton_virtual5'><button class='btn btn-primary btn-sm' data-toggle="modal" data-target=".bs-example-modal-sm" onclick="nuevo_cat('inpc')">Nuevo <span class='glyphicon glyphicon-plus'></span></button></div>
                  <table id="tabla-inpc" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                      <thead>
                          <tr><th>Año</th><th>Mes</th><th>Indice</th><th>Estatus</th><th>Modificar</th></tr>
                      </thead>
                      <tbody id='trs_inpc'>
                      </tbody>
                  </table>
              </div>
    
              </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="depr">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Catalogo de Porcentajes de depreciacion.</h3>
              </div>
              <div class="panel-body">
                
              <div class="col-xs-12 col-md-12 table-responsive">
                  <div id='boton_virtual6'><button class='btn btn-primary btn-sm' data-toggle="modal" data-target=".bs-example-modal-sm" onclick="nuevo_cat('depr')">Nuevo <span class='glyphicon glyphicon-plus'></span></button></div>
                  <table id="tabla-depr" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                      <thead>
                          <tr><th>Id</th><th>Nombre</th><th>Valor</th><th>Estatus</th><th>Modificar</th></tr>
                      </thead>
                      <tbody id='trs_depr'>
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
<script language='javascript' src='http://transtatic.com/js/numericInput.min.js'></script>

<!--AQUI ESTAN LOS MODALS-->
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div id='blanco' style='width:300px;height:300px;background-color:white;z-index:1;position:absolute;color:green;'>&nbsp;&nbsp;Cargando...</div>
      <div class="modal-header panel-heading">
                <h4 id="modal-label" class='tit'></h4>
                <input type='hidden' style='width:150px;' id='tipo_reg'>
                <input type='hidden' style='width:150px;' id='id_reg'>
            </div>
      <div class="modal-body">
        <div class="row">
                <div class="col-xs-12 col-md-5">
                    <b id='primero_b'></b>
                </div>
                <div class="col-xs-12 col-md-7">
                    <input type='text' id='primero' class='form-control original'>
                </div>
        </div>
        <div class="row">
                <div class="col-xs-12 col-md-5">
                    <b id='segundo_b'></b>
                </div>
                <div class="col-xs-12 col-md-7">
                    <input type='text' id='segundo' class='form-control original'>
                </div>
        </div>
        <div class="row" id='indice_inpc'>
                <div class="col-xs-12 col-md-5">
                    <b>Indice</b>
                </div>
                <div class="col-xs-12 col-md-7">
                    <input type='text' id='indice' class='form-control'>
                </div>
        </div>
        <div class="row" id='indice_inpc'>
                <div class="col-xs-12 col-md-5">
                    <b>Estatus</b>
                </div>
                <div class="col-xs-12 col-md-7">
                    <select id='estatus' class='form-control'>
                      <option value='1'>Activo</option>
                      <option value='0'>Inactivo</option>
                    </select>

                </div>
        </div>
      </div>
            <div class="modal-footer">
                <button class='btn btn-default btn-sm' id='guardar' onclick='guardar()'>Guardar</button><button class='btn btn-default btn-sm' onclick='cancelar_cat()'>Cancelar</button>
            </div>      
    </div>
  </div>
</div>