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
</style>
<?php
require "views/partial/modal-generico.php";
?>
<input type='hidden' id='conectar_hid' value='<?php echo $infoConf['conectar_acontia'] ?>'>
<input type='hidden' id='conectar_bco_hid' value='<?php echo $infoConf['conectar_bancos'] ?>'>
<input type='hidden' id='pestania' value='<?php echo $_GET['p'] ?>'>
<div class="container well">
    <div class="row">
        <div class="col-xs-12 col-md-12"><h3>Configuraci&oacute;n de Polizas</h3></div>
    </div>
    <div class="row">
       <!-- Nav tabs -->
      <ul id='myTabs' class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab">General</a></li>
        <li role="presentation"><a href="#ventas" aria-controls="ventas" role="tab" data-toggle="tab" class='ver'>Ventas</a></li>
        <li role="presentation"><a href="#compras" aria-controls="compras" role="tab" data-toggle="tab" class='ver'>Compras</a></li>
        <li role="presentation"><a href="#cxp" aria-controls="cxp" role="tab" data-toggle="tab" class='ver'>Cuentas por Pagar</a></li>
        <li role="presentation"><a href="#cxc" aria-controls="cxc" role="tab" data-toggle="tab" class='ver'>Cuentas por Cobrar</a></li>
        <li role="presentation"><a href="#entrada" aria-controls="entrada" role="tab" data-toggle="tab" class='ver'>Entrada de Inv.</a></li>
        <li role="presentation"><a href="#salida" aria-controls="salida" role="tab" data-toggle="tab" class='ver'>Salida de Inv.</a></li>
        <li role="presentation"><a href="#traspaso" aria-controls="traspaso" role="tab" data-toggle="tab" class='ver'>Traspaso de Inv.</a></li>
        <li role="presentation"><a href="#cancelacion" aria-controls="cancelacion" role="tab" data-toggle="tab" class='ver'>Cancelacion</a></li>
        <li role="presentation"><a href="#devolucion" aria-controls="devolucion" role="tab" data-toggle="tab" class='ver'>Devolucion</a></li>
      </ul>

      <!-- Tab panes -->
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="general">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Configuracion General</h3>
              </div>
              <div class="panel-body">
                <div class='row'>
                  <div class='col-xs-12 col-md-3 col-md-offset-4'>Conectar con Acontia</div>
                  <div class='col-xs-12 col-md-5'><input type='checkbox' id='conectar' value='1' onchange='ver()'></div>
                </div>
                <?php
                if(intval($tiene_bancos)){
                ?>
                  <div class='row'>
                    <div class='col-xs-12 col-md-3 col-md-offset-4'>Conectar con Bancos</div>
                    <div class='col-xs-12 col-md-5'><input type='checkbox' id='conectar_bancos' value='1'></div>
                  </div>
                <?php 
                }
                else
                {
                ?>
                  <div class='row'>
                    <div class='col-xs-12 col-md-12 col-md-offset-4'><input type='hidden' value='0' id='conectar_bancos'></div>
                  </div>
                <?php
                } 
                ?>
                <div class='row ver'>
                  <div class='col-xs-12 col-md-3 col-md-offset-4'>Las polizas requieren autorizacion?</div>
                  <div class='col-xs-12 col-md-5'><input type='checkbox' id='autorizacion' value='1' <?php echo $checked; ?>></div>
                </div>
                <div class='row'>
                  <div class='col-xs-12 col-md-3 col-md-offset-5'><button onclick="guardar_gral()" class='btn btn-default'>Guardar</button></div>
                </div>
              </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="ventas">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Configurar poliza de Ventas</h3>
              </div>
              <center><b>Ventas Facturadas</b></center>
              <?php
                $tipo = 1;
                require("polizas_comun.php");
                echo "<hr /><center><b>Ventas Ticket</b></center>";
                //AGREGAR VALIDACION SI TIENE POS
                $tipo = 10;
                require("polizas_comun.php");
              ?>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="compras">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Configurar polizas de Compras</h3>
              </div>
              <div class="panel-body">
              <div class='col-xs-12 col-md-12'>
                  <table>
                    <tr><td colspan='5'><button onclick='abrir_polizas_compras(0)' class='btn btn-primary'>Agregar Poliza de Compras</button></td></tr>
                  </table>
                </div>
                <div class='col-xs-12 col-md-12'>
                    <div class='table-responsive'>  
                      <table id='polizas_compras' class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                          <tr><th>Nombre Poliza</th><th>Gasto</th><th>Tipo Poliza</th><th>Ejecucion</th><th>Por Mov.</th><th>Dias</th><th>Modificar</th><th>Eliminar</th></tr>
                        </thead>
                      </table>
                    </div>
                  </div>
              </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="cxp">
              <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Configurar poliza de Cuentas por Pagar.</h3>
              </div>
              <center><b>Cuentas por Pagar Facturas</b></center>
              <?php
                $tipo = 3;
                require("polizas_comun.php");
                echo "<hr /><center><b>Cuentas por Pagar Cargos</b></center>";
                //AGREGAR VALIDACION SI TIENE POS
                $tipo = 12;
                require("polizas_comun.php");
              ?>

            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="cxc">
              <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Configurar poliza de Cuentas por Cobrar.</h3>
              </div>
              <center><b>Cuentas por Cobrar Facturas</b></center>
              <?php
                $tipo = 4;
                require("polizas_comun.php");
                echo "<hr /><center><b>Cuentas por Cobrar Cargos</b></center>";
                //AGREGAR VALIDACION SI TIENE POS
                $tipo = 11;
                require("polizas_comun.php");
              ?>

            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="entrada">
             <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Configurar poliza de Entradas de Inventario.</h3>
              </div>
              <?php
                $tipo = 5;
                require("polizas_comun.php");
              ?>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="salida">
             <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Configurar poliza de Salidas de Inventario.</h3>
              </div>
              <?php
                $tipo = 6;
                require("polizas_comun.php");
              ?>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="traspaso">
             <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Configurar poliza de Traspaso de Inventarios.</h3>
              </div>
              <?php
                $tipo = 7;
                require("polizas_comun.php");
              ?>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="cancelacion">
             <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Configurar poliza de Cancelacion de Venta.</h3>
              </div>
              <b>Las cuentas y configuracion de cancelaciones estan vinculadas con las de ventas.</b>
              <?php
                $tipo = 8;
                //require("polizas_comun.php");
              ?>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="devolucion">
             <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Configurar poliza de Devolucion de Clientes.</h3>
              </div>
              <?php
                $tipo = 9;
                require("polizas_comun.php");
              ?>
            </div>
        </div>

    </div>
</div>
<script src="../../libraries/select2/dist/js/select2.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />
<script language='javascript' src='js/configuracion_polizas.js'></script>
<script language='javascript' src='http://transtatic.com/js/numericInput.min.js'></script>
<script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
<script src="../../libraries/export_print/jszip.min.js"></script>
<script language='javascript' src='../../libraries/dataTable/js/dataTables.bootstrap.min.js'></script>
<link rel="stylesheet" href="../../libraries/dataTable/css/datatables.min.css">
<link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
<link rel="stylesheet" href="https://raw.githubusercontent.com/t0m/select2-bootstrap-css/bootstrap3/select2-bootstrap.css">


<!--Modal de Compras-->
<div class="modal fade bs-compras-modal-md" tabindex="-1" role="dialog" aria-labelledby="compras">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header panel-heading" style='background-color:#337ab7;color:#FFFFFF;'>
                <h4 id="modal-label"><label id='am_c'></label> Poliza de Compras</h4>
            </div>
      <div class="modal-body well"><input type='hidden' id='id_poliza'>
        <?php
                $tipo = 2;
                require("polizas_comun.php");
        ?>
      </div>
            <div class="modal-footer">
                <button class='btn btn-default btn-sm' onclick="cerrar_poliza()">Cerrar</button>
            </div>      
    </div>
  </div>
</div>
<!--Aqui los modals-->
<div class="modal fade bs-cuentas-modal-md" tabindex="-1" role="dialog" aria-labelledby="cuentas">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header panel-heading" style='background-color:#337ab7;color:#FFFFFF;'>
                <h4 id="modal-label"><label id='am'></label></h4>
            </div>
      <div class="modal-body well">
        <div class='row'>
            <div class='col-xs-6 col-md-4'><b>Cuenta:</b><input type='hidden' id='cuenta_hid' value='0'><input type='hidden' id='tipo_hid' value='0'></div><div class='col-xs-6 col-md-6'>
              <select id='cuentas_lista' class='form-control cuentas_lista'>
              </select>
            </div>
        </div>  
        <div class='row'>  
          <div class='col-xs-6 col-md-4'><b>Tipo de Movto:</b></div>
          <div class='col-xs-6 col-md-6'>
            <input type='radio' name='ca' id='cargo' checked>Cargo &nbsp;
            <input type='radio' name='ca' id='abono'>Abono
          </div>
        </div>
        <div class='row'>  
          <div class='col-xs-6 col-md-4'><b>Vincular con:</b></div>
          <div class='col-xs-6 col-md-6'>
            <select id='vinculacion' onchange='imp()'></select>
          </div>
        </div>
        <div class='row' id='imps'>  
          <div class='col-xs-6 col-md-4'><b>Impuesto:</b></div>
          <div class='col-xs-6 col-md-6'>
            <select id='impuestos'>
              <option value="IVA 16%">IVA 16%</option>
              <option value="IVA 0%">TOTAL IMPORTES CON IVA 0%</option>
              <option value="IVA EXENTO">TOTAL IMPORTES CON IVA EXENTO</option>
              <option value="IVA IMPS">TOTAL IMPORTES CON IVA 16%</option>
            </select>
          </div>
        </div>
      </div>
            <div class="modal-footer">
                <button class='btn btn-default btn-sm' onclick="agregar_cuenta()">Guardar</button><button class='btn btn-default btn-sm' onclick="cerrar_cuenta()">Cerrar</button>
            </div>      
    </div>
  </div>
</div>