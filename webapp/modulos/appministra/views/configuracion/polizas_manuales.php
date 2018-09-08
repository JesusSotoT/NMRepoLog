<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<style>
.mano
{
  cursor:pointer;
}
.row
{
    margin-bottom:20px;
}
.container
{
    margin-top:20px;
}
.select2-container{
    width: 100% !important;
  }
  .select2-container .select2-choice{
    background-image: unset !important;
    height: 31px !important;
  }
</style>
<?php
require "views/partial/modal-generico.php";
?>
<input type='hidden' id='conectar_hid' value='<?php echo $infoConf['conectar_acontia'] ?>'>
<input type='hidden' id='pestania' value='<?php echo $_GET['p'] ?>'>
<div class="container well">
    <div class="row">
        <div class="col-xs-12 col-md-12"><h3>Generación de Polizas Manuales</h3></div>
    </div>
    <div class="row">
       <!-- Nav tabs -->
      <ul id='myTabs' class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#ventas" aria-controls="ventas" role="tab" data-toggle="tab">Ventas</a></li>
        <li role="presentation"><a href="#compras" aria-controls="compras" role="tab" data-toggle="tab" class='ver'>Compras</a></li>
        <li role="presentation"><a href="#cxp" aria-controls="cxp" role="tab" data-toggle="tab" class='ver'>Cuentas por Pagar</a></li>
        <li role="presentation"><a href="#cxc" aria-controls="cxc" role="tab" data-toggle="tab" class='ver'>Cuentas por Cobrar</a></li>
        <li role="presentation"><a href="#entradas" aria-controls="entradas" role="tab" data-toggle="tab" class='ver'>Entradas de Inv.</a></li>
        <li role="presentation"><a href="#salidas" aria-controls="salidas" role="tab" data-toggle="tab" class='ver'>Salidas de Inv.</a></li>
        <li role="presentation"><a href="#traspasos" aria-controls="traspasos" role="tab" data-toggle="tab" class='ver'>Traspasos de Inv.</a></li>
        <li role="presentation"><a href="#cancelacion" aria-controls="cancelacion" role="tab" data-toggle="tab" class='ver'>Cancelación</a></li>
        <li role="presentation"><a href="#devolucion" aria-controls="devolucion" role="tab" data-toggle="tab" class='ver'>Devolución</a></li>
      </ul>

      <!-- Tab panes -->
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="ventas">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Poliza de Ventas</h3>
              </div>
              <div class="panel-body">
              <div class='col-xs-12 col-md-1'>
                <b>Desde</b>
              </div>
                <div class='col-xs-12 col-md-2'>
                  <select id='tipo_venta' class='form-control'>
                    <!--<option value='1'>Appministra Comercial</option>-->
                    <option value='2'>Appministra POS</option>
                  </select>
                </div>
                <div class='col-xs-12 col-md-1'>
                  <b>Cliente</b>
                </div>
                <div class='col-xs-12 col-md-3'>
                  <select id='id_cliente' class='form-control id_cliente'>
                    <option value='0'>Todos</option>
                    <?php 
                      while($cli = $clientes->fetch_object())
                      {
                        echo "<option value='$cli->id'>$cli->rfc / $cli->nombre</option>";
                      }
                     ?>
                  </select>
                </div>
                <div class='col-xs-12 col-md-1'><b>Rango</b></div>
                <div class='col-xs-12 col-md-2'><input type='text' id='fechas_vtas' class='form-control fechas_izq' value=''></div>
                <div class='col-xs-12 col-md-1'><button id='generar_vtas' class='btn btn-default' onclick='buscaVentas()'>Generar</button></div>
                <div class='col-xs-12 col-md-12'>
                  <div class='table-responsive'> 
                    <table id='tabla_ventas' class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                          <tr><th>Nombre</th><th>Folio</th><th>Fecha</th><th>Ids Ventas</th><th>Seleccionar Todos <input type='checkbox' id='todos_check_ventas' onchange="sel_todos_check('ventas')"></th></tr>
                        </thead>
                    </table>
                  </div>
                </div>
                <div class='col-xs-12 col-md-12'>
                <button id='generar_ventas' class='btn btn-primary' onclick='generar_poliza(1)'>Generar Poliza</button>
                </div>
              </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="compras">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Poliza de Compras</h3>
              </div>
              <div class="panel-body">
              <div class='col-xs-12 col-md-1'>
                <b>Tipo de Gasto</b>
              </div>
              <div class='col-xs-12 col-md-2' style='margin-bottom:10px;'>
              <select id='tipo_gasto' class='form-control'>
                <?php 
                  while($tg = $gastos->fetch_assoc())
                    echo "<option value='".$tg['id']."'>(".$tg['codigo'].") ".$tg['nombreclasificador']."</option>";
                 ?>
              </select>
              </div>
              <div class='col-xs-12 col-md-1'>
                  <b>Proveedor</b>
                </div>
                <div class='col-xs-12 col-md-3'>
                  <select id='id_proveedor' class='form-control id_proveedor'>
                    <option value='0'>Todos</option>
                    <?php 
                      while($prv = $proveedores->fetch_object())
                      {
                        echo "<option value='$prv->idPrv'>$prv->rfc / $prv->razon_social</option>";
                      }
                     ?>
                  </select>
                </div>
                <div class='col-xs-12 col-md-1'><b>Rango</b></div>
                <div class='col-xs-12 col-md-2'><input type='text' id='fechas_cmps' class='form-control fechas_izq' value=''></div>
                <div class='col-xs-12 col-md-1'><button id='generar_cmps' class='btn btn-default' onclick='carga_compras()'>Generar</button></div>
              <div class='col-xs-12 col-md-12'>
                  <div class='table-responsive'> 
                    <table id='tabla_compras' class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                          <tr><th>Nombre</th><th>Folio</th><th>Fecha</th><th>Id Compra</th><th>Seleccionar Todos <input type='checkbox' id='todos_check_compras' onchange="sel_todos_check('compras')"></th></tr>
                        </thead>
                    </table>
                  </div>
                </div>
                <div class='col-xs-12 col-md-12'>
                <button id='generar_ventas' class='btn btn-primary' onclick='generar_poliza(2)'>Generar Poliza</button>
                </div>
              </div>
            </div>
        </div>
         <div role="tabpanel" class="tab-pane fade" id="cxp">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Poliza de Cuentas por Pagar</h3>
              </div>
              <div class="panel-body">
              <div class='col-xs-12 col-md-1'>
                  <b>Proveedor</b>
                </div>
                <div class='col-xs-12 col-md-3'>
                  <select id='id_proveedor_cxp' class='form-control id_proveedor'>
                    <option value='0'>Todos</option>
                    <?php 
                      while($prv = $proveedores_cxp->fetch_object())
                      {
                        echo "<option value='$prv->idPrv'>$prv->rfc / $prv->razon_social</option>";
                      }
                     ?>
                  </select>
                </div>
                <div class='col-xs-12 col-md-1'><b>Rango</b></div>
                <div class='col-xs-12 col-md-2'><input type='text' id='fechas_cxp' class='form-control fechas_izq' value=''></div>
                <div class='col-xs-12 col-md-5'><button id='generar_cxp' class='btn btn-default' onclick='buscaDatos(3)'>Generar</button></div>
                <div class='col-xs-12 col-md-12'>
                  <div class='table-responsive'> 
                    <table id='tabla_cxp' class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                          <tr><th>Id / Concepto</th><th>Fecha</th><th>Monto</th><th>Id Compra</th><th>Seleccionar Todos <input type='checkbox' id='todos_check_cxp' onchange="sel_todos_check('cxp')"></th></tr>
                        </thead>
                    </table>
                  </div>
                </div>
                <div class='col-xs-12 col-md-12'>
                <button id='generar_cxp' class='btn btn-primary' onclick='generar_poliza(3)'>Generar Poliza</button>
                </div>
              </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="cxc">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Poliza de Cuentas por Cobrar</h3>
              </div>
              <div class="panel-body">
              <div class='col-xs-12 col-md-1'>
                  <b>Cliente</b>
                </div>
                <div class='col-xs-12 col-md-3'>
                  <select id='id_cliente_cxc' class='form-control id_cliente'>
                    <option value='0'>Todos</option>
                    <?php 
                      while($cli = $clientes_cxc->fetch_object())
                      {
                        echo "<option value='$cli->id'>$cli->rfc / $cli->nombre</option>";
                      }
                     ?>
                  </select>
                </div>
                <div class='col-xs-12 col-md-1'><b>Rango</b></div>
                <div class='col-xs-12 col-md-2'><input type='text' id='fechas_cxc' class='form-control fechas_izq' value=''></div>
                <div class='col-xs-12 col-md-5'><button id='generar_cxc' class='btn btn-default' onclick='buscaDatos(4)'>Generar</button></div>
                <div class='col-xs-12 col-md-12'>
                  <div class='table-responsive'> 
                    <table id='tabla_cxc' class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                          <tr><th>Id / Concepto</th><th>Fecha</th><th>Monto</th><th>Id Venta</th><th>Seleccionar Todos <input type='checkbox' id='todos_check_cxc' onchange="sel_todos_check('cxc')"></th></tr>
                        </thead>
                    </table>
                  </div>
                </div>
                <div class='col-xs-12 col-md-12'>
                <button id='generar_cxc' class='btn btn-primary' onclick='generar_poliza(4)'>Generar Poliza</button>
                </div>
              </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="entradas">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Poliza de Entradas de Inventario</h3>
              </div>
              <div class="panel-body">
              <div class='col-xs-12 col-md-1'><b>Rango</b></div>
                <div class='col-xs-12 col-md-2'><input type='text' id='fechas_entradas' class='form-control fechas_der' value=''></div>
                <div class='col-xs-12 col-md-5'><button id='generar_entradas' class='btn btn-default' onclick='buscaDatos(5)'>Generar</button></div>
                <div class='col-xs-12 col-md-12'>
                  <div class='table-responsive'> 
                    <table id='tabla_entradas' class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                          <tr><th>Id / Concepto</th><th>Producto</th><th>Monto</th><th>Fecha</th><th>Seleccionar Todos <input type='checkbox' id='todos_check_entrada' onchange="sel_todos_check('entrada')"></th></tr>
                        </thead>
                    </table>
                  </div>
                </div>
                <div class='col-xs-12 col-md-12'>
                <button id='generar_entradas' class='btn btn-primary' onclick='generar_poliza(5)'>Generar Poliza</button>
                </div>
              </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="salidas">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Poliza de Salidas de Inventario</h3>
              </div>
              <div class="panel-body">
              <div class='col-xs-12 col-md-1'><b>Rango</b></div>
                <div class='col-xs-12 col-md-2'><input type='text' id='fechas_salidas' class='form-control fechas_der' value=''></div>
                <div class='col-xs-12 col-md-5'><button id='generar_salidas' class='btn btn-default' onclick='buscaDatos(6)'>Generar</button></div>
                <div class='col-xs-12 col-md-12'>
                  <div class='table-responsive'> 
                    <table id='tabla_salidas' class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                          <tr><th>Id / Concepto</th><th>Producto</th><th>Monto</th><th>Fecha</th><th>Seleccionar Todos <input type='checkbox' id='todos_check_salida' onchange="sel_todos_check('salida')"></th></tr>
                        </thead>
                    </table>
                  </div>
                </div>
                <div class='col-xs-12 col-md-12'>
                <button id='generar_salidas' class='btn btn-primary' onclick='generar_poliza(6)'>Generar Poliza</button>
                </div>
              </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="traspasos">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Poliza de Traspasos de Inventario</h3>
              </div>
              <div class="panel-body">
              <div class='col-xs-12 col-md-1'><b>Rango</b></div>
                <div class='col-xs-12 col-md-2'><input type='text' id='fechas_traspasos' class='form-control fechas_der' value=''></div>
                <div class='col-xs-12 col-md-5'><button id='generar_traspasos' class='btn btn-default' onclick='buscaDatos(7)'>Generar</button></div>
                <div class='col-xs-12 col-md-12'>
                  <div class='table-responsive'> 
                    <table id='tabla_traspasos' class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                          <tr><th>Id / Concepto</th><th>Producto</th><th>Monto</th><th>Fecha</th><th>Seleccionar Todos <input type='checkbox' id='todos_check_traspaso' onchange="sel_todos_check('traspaso')"></th></tr>
                        </thead>
                    </table>
                  </div>
                </div>
                <div class='col-xs-12 col-md-12'>
                <button id='generar_traspasos' class='btn btn-primary' onclick='generar_poliza(7)'>Generar Poliza</button>
                </div>
              </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="cancelacion">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Poliza de Cancelacion de venta</h3>
              </div>
              <div class="panel-body">
                <div class='col-xs-12 col-md-1'><b>Rango</b></div>
                <div class='col-xs-12 col-md-2'><input type='text' id='fechas_cancelacion' class='form-control fechas_der' value=''></div>
                <div class='col-xs-12 col-md-5'><button id='generar_cancelacion' class='btn btn-default' onclick='buscaDatos(8)'>Generar</button></div>
                <div class='col-xs-12 col-md-12'>
                  <div class='table-responsive'> 
                    <table id='tabla_cancelacion' class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                          <tr><th>Id / Concepto</th><th>Folio</th><th>Fecha</th><th>Id Venta</th><th>Seleccionar Todos <input type='checkbox' id='todos_check_cancelacion' onchange="sel_todos_check('cancelacion')"></th></tr>
                        </thead>
                    </table>
                  </div>
                </div>
                <div class='col-xs-12 col-md-12'>
                <button id='generar_cancelacion' class='btn btn-primary' onclick='generar_poliza(8)'>Generar Poliza</button>
                </div>
              </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="devolucion">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Poliza de Devolucion de Clientes</h3>
              </div>
              <div class="panel-body">
                <div class='col-xs-12 col-md-1'><b>Rango</b></div>
                <div class='col-xs-12 col-md-2'><input type='text' id='fechas_devolucion' class='form-control fechas_der' value=''></div>
                <div class='col-xs-12 col-md-5'><button id='generar_devolucion' class='btn btn-default' onclick='buscaDatos(9)'>Generar</button></div>
                <div class='col-xs-12 col-md-12'>
                  <div class='table-responsive'> 
                    <table id='tabla_devolucion' class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                          <tr><th>Id / Concepto</th><th>Folio</th><th>Monto</th><th>Id Venta</th><th>Seleccionar Todos <input type='checkbox' id='todos_check_devolucion' onchange="sel_todos_check('devolucion')"></th></tr>
                        </thead>
                    </table>
                  </div>
                </div>
                <div class='col-xs-12 col-md-12'>
                <button id='generar_devolucion' class='btn btn-primary' onclick='generar_poliza(9)'>Generar Poliza</button>
                </div>
              </div>
            </div>
        </div>
</div>
<script src="../../libraries/select2/dist/js/select2.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />
<script language='javascript' src='http://transtatic.com/js/numericInput.min.js'></script>
<script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
<script src="../../libraries/export_print/jszip.min.js"></script>
<script language='javascript' src='../../libraries/dataTable/js/dataTables.bootstrap.min.js'></script>
<link rel="stylesheet" href="../../libraries/dataTable/css/datatables.min.css">
<link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
<link rel="stylesheet" href="https://raw.githubusercontent.com/t0m/select2-bootstrap-css/bootstrap3/select2-bootstrap.css">
<script language='javascript' src='js/polizas_manuales.js'></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

<!--Modal-->
<div class="modal fade bs-polizas-modal-md" tabindex="-1" role="dialog" aria-labelledby="polizas">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header panel-heading" style='background-color:#337ab7;color:#FFFFFF;'>
                <h4 id="modal-label"></label> Generar Poliza</h4>
            </div>
      <div class="modal-body well"><input type='hidden' id='tipo'>
          <div class='row'>
              <div class='col-xs-12 col-md-4'>Concepto</div>
              <div class='col-xs-12 col-md-8'><input type='text' id='concepto' class='form-control'></div>
          </div>
          <div class='row'>
              <div class='col-xs-12 col-md-4'>Fecha</div>
              <div class='col-xs-12 col-md-8'><input type='text' id='fecha' class='form-control'></div>
          </div>
          <div class='row'>
              <div class='col-xs-12 col-md-4'>Segmento</div>
              <div class='col-xs-12 col-md-8'>
                <select id='segmento' class='form-control'>
                  <?php
                      while($s = $segs->fetch_assoc())
                      {
                        echo "<option value='".$s['idSuc']."'>(".$s['clave'].") ".$s['nombre']."</option>";
                      }
                    ?>
                    </select>
              </div>
          </div>
      </div>
            <div class="modal-footer">
                <button id='guardar' class='btn btn-default' onclick='guardar()'>Guardar</button>
                <button class='btn btn-default btn-sm' onclick="ventana_poliza(0,0)">Cerrar</button>
            </div>      
    </div>
  </div>
</div>