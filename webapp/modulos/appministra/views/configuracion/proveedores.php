<script type="text/javascript" charset="utf-8">
    $(document).ready(function() 
    {
        inicializa_proveedores();
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
<div class="container well">
    <div class="row">
        <div class="col-xs-12 col-md-12"><h3>Lista Proveedores.</h3></div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-12 table-responsive">
            <div id='boton_virtual'><button class='btn btn-primary btn-sm' data-toggle="modal" data-target=".bs-example-modal-md" onclick='nuevo_proveedor()'>Nuevo <span class='glyphicon glyphicon-plus'></span></button></div>
            <table id="tabla-data" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                <thead>
                    <tr><th>Codigo</th><th>Razon Social</th><th>RFC</th><th>Municipio</th><th>Estado</th><th>Teléfono</th><th>Email</th><th>Modificar</th><th>Status</th></tr>
                </thead>
                <tbody id='trs'>
                </tbody>
            </table>
        </div>
    </div>            
</div>
<script language='javascript' src='../../libraries/dataTable/js/datatables.min.js'></script>
<script language='javascript' src='../../libraries/dataTable/js/dataTables.bootstrap.min.js'></script>
<link rel="stylesheet" href="../../libraries/dataTable/css/datatables.min.css">
<link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
<script language='javascript' src='js/proveedores.js'></script>

<!--AQUI ESTAN LOS MODALS-->
<div class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
        <div id='blanco' style='width:298px;height:420px;background-color:white;z-index:1;position:absolute;color:green;'>&nbsp;&nbsp;Cargando...</div>
      <div class="modal-header panel-heading">
                <h4 id="modal-label">Nuevo Proveedor</h4>
                <input type='hidden' id='idPrv' value='0'>
            </div>
      <div class="modal-body">
        <div class='row'>
            <ul id='myTabs' class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab">General</a></li>
            <li role="presentation"><a href="#fiscal" aria-controls="fiscal" role="tab" data-toggle="tab">Fiscal</a></li>
          </ul>
        </div>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="general">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <div class="row">
                            <div class="col-xs-12 col-md-3">
                                <b>Código:</b>
                            </div>
                            <div class="col-xs-12 col-md-9">
                                <input type='text' id='codigo' class='form-control'>
                            </div>
                    </div>
                    <div class="row">
                            <div class="col-xs-12 col-md-3">
                                <b>RFC:</b>
                            </div>
                            <div class="col-xs-12 col-md-9">
                                <input type='text' id='rfc' class='form-control'>
                            </div>
                    </div>
                    <div class="row">
                            <div class="col-xs-12 col-md-3">
                                <b>Curp:</b>
                            </div>
                            <div class="col-xs-12 col-md-9">
                                <input type='text' id='curp' class='form-control'>
                            </div>
                    </div>
                    <div class="row">
                            <div class="col-xs-12 col-md-3">
                                <b>Razon Social / Nombre:</b>
                            </div>
                            <div class="col-xs-12 col-md-9">
                                <input type='text' id='razon_social' class='form-control'>
                            </div>
                    </div>
                    <div class="row">
                            <div class="col-xs-12 col-md-3">
                                <b>Domicilio:</b>
                            </div>
                            <div class="col-xs-12 col-md-9">
                                <textarea id='domicilio' class='form-control'></textarea> 
                            </div>
                    </div>
                    <div class="row">
                            <div class="col-xs-12 col-md-3">
                                <b>Estado:</b>
                            </div>
                            <div class="col-xs-12 col-md-9">
                                <select id='estado' onclick='municipios()'></select>
                            </div>
                    </div>
                     <div class="row">
                            <div class="col-xs-12 col-md-3">
                                <b>Municipio:</b>
                            </div>
                            <div class="col-xs-12 col-md-9">
                                <select id='municipio'></select>
                            </div>
                    </div>
                     <div class="row">
                            <div class="col-xs-12 col-md-3">
                                <b>Teléfono:</b>
                            </div>
                            <div class="col-xs-12 col-md-9">
                                <input type='text' id='telefono' class='form-control'>
                            </div>
                    </div>
                    <div class="row">
                            <div class="col-xs-12 col-md-3">
                                <b>Correo electrónico:</b>
                            </div>
                            <div class="col-xs-12 col-md-9">
                                <input type='text' id='email' class='form-control'>
                            </div>
                    </div>
                    <div class="row">
                            <div class="col-xs-12 col-md-3">
                                <b>Página web:</b>
                            </div>
                            <div class="col-xs-12 col-md-9">
                                <input type='text' id='web' class='form-control'>
                            </div>
                    </div>
                    <div class="row">
                            <div class="col-xs-12 col-md-3">
                                <b>Días de Crédito:</b>
                            </div>
                            <div class="col-xs-12 col-md-9">
                                <input type='text' id='diascredito' class='form-control'>
                            </div>
                    </div>
                  </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade in active" id="fiscal">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <div class="row">
                            <div class="col-xs-12 col-md-3">
                                <b>Tipo:</b>
                            </div>
                            <div class="col-xs-12 col-md-9">
                                <input type='text' id='tipo' class='form-control'>
                            </div>
                    </div>
                    <div class="row">
                            <div class="col-xs-12 col-md-3">
                                <b>Cuenta:</b>
                            </div>
                            <div class="col-xs-12 col-md-9">
                                <input type='text' id='cuenta' class='form-control'>
                            </div>
                    </div>
                    <div class="row">
                            <div class="col-xs-12 col-md-3">
                                <b>Beneficiario / Pagador:</b>
                            </div>
                            <div class="col-xs-12 col-md-9">
                                <input type='text' id='beneficiario' class='form-control'>
                            </div>
                    </div>
                    <div class="row">
                            <div class="col-xs-12 col-md-3">
                                <b>Cuenta Cliente:</b>
                            </div>
                            <div class="col-xs-12 col-md-9">
                                <input type='text' id='cuenta_cliente' class='form-control'>
                            </div>
                    </div>
                    <div class="row">
                            <div class="col-xs-12 col-md-3">
                                <b>Tipo Tercero:</b>
                            </div>
                            <div class="col-xs-12 col-md-9">
                                <input type='text' id='tipo_tercero' class='form-control'>
                            </div>
                    </div>
                    <div class="row">
                            <div class="col-xs-12 col-md-3">
                                <b>Tipo Operacion:</b>
                            </div>
                            <div class="col-xs-12 col-md-9">
                                <input type='text' id='tipo_operacion' class='form-control'>
                            </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
      </div>
            <div class="modal-footer">
                <button class='btn btn-default btn-sm' onclick='guardar_proveedor()'>Guardar</button><button class='btn btn-default btn-sm' onclick='cancelar_proveedor()'>Cancelar</button>
            </div>      
    </div>
  </div>
</div>