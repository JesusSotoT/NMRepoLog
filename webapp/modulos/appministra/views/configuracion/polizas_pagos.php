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
<input type='hidden' id='pestania' value='<?php echo $_GET['p'] ?>'>
<div class="container well">
    <div class="row">
        <div class="col-xs-12 col-md-12"><h3>Configuraci&oacute;n de Polizas de Pagos</h3></div>
    </div>
    <div class="row">
       <!-- Nav tabs -->
      <ul id='myTabs' class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#efectivo" aria-controls="efectivo" role="tab" data-toggle="tab">Efectivo</a></li>
        <li role="presentation"><a href="#tarjeta" aria-controls="tarjeta" role="tab" data-toggle="tab" class='ver'>Tarjeta</a></li>
        <li role="presentation"><a href="#transferencia" aria-controls="transferencia" role="tab" data-toggle="tab" class='ver'>Transferencia</a></li>
        <li role="presentation"><a href="#compensacion" aria-controls="compensacion" role="tab" data-toggle="tab" class='ver'>Compensación</a></li>
      </ul>

      <!-- Tab panes -->
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="efectivo">
             <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Configurar poliza de Pagos en Efectivo.</h3>
              </div>
              <?php
                echo "<hr /><center><b>Pagos en efectivo Factura</b></center>";
                $tipo = 1;
                require("polizas_pagos_comun.php");
                echo "<hr /><center><b>Pagos en efectivo Ticket</b></center>";
                $tipo = 4;
                require("polizas_pagos_comun.php");
              ?>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="tarjeta">
             <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Configurar poliza de Pagos con Tarjeta.</h3>
              </div>
              <?php
                echo "<hr /><center><b>Pagos en tarjeta Factura</b></center>";
                $tipo = 2;
                require("polizas_pagos_comun.php");
                echo "<hr /><center><b>Pagos en tarjeta Ticket</b></center>";
                $tipo = 5;
                require("polizas_pagos_comun.php");
              ?>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="transferencia">
             <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Configurar poliza de Pagos con Transferencia.</h3>
              </div>
              <?php
                echo "<hr /><center><b>Pagos en transferencia Factura</b></center>";
                $tipo = 3;
                require("polizas_pagos_comun.php");
                echo "<hr /><center><b>Pagos en transferencia Ticket</b></center>";
                $tipo = 6;
                require("polizas_pagos_comun.php");
              ?>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="compensacion">
             <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Configurar poliza de Pagos con Compensación.</h3>
              </div>
              <?php
                echo "<hr /><center><b>Pagos en compensacion Factura</b></center>";
                $tipo = 7;
                require("polizas_pagos_comun.php");
                echo "<hr /><center><b>Pagos en compensacion Ticket</b></center>";
                $tipo = 8;
                require("polizas_pagos_comun.php");
              ?>
            </div>
        </div>
    </div>
</div>
<script src="../../libraries/select2/dist/js/select2.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />
<script language='javascript' src='js/configuracion_polizas_pagos.js'></script>
<script language='javascript' src='http://transtatic.com/js/numericInput.min.js'></script>
<script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
<script src="../../libraries/export_print/jszip.min.js"></script>
<script language='javascript' src='../../libraries/dataTable/js/dataTables.bootstrap.min.js'></script>
<link rel="stylesheet" href="../../libraries/dataTable/css/datatables.min.css">
<link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
<link rel="stylesheet" href="https://raw.githubusercontent.com/t0m/select2-bootstrap-css/bootstrap3/select2-bootstrap.css">
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
            <select id='vinculacion'></select>
          </div>
        </div>
        <div class='row' id='imps' style='display: none'>  
          <div class='col-xs-6 col-md-4'><b>Impuesto:</b></div>
          <div class='col-xs-6 col-md-6'>
            <select id='impuestos'></select>
          </div>
        </div>
      </div>
            <div class="modal-footer">
                <button class='btn btn-default btn-sm' onclick="agregar_cuenta()">Guardar</button><button class='btn btn-default btn-sm' onclick="cerrar_cuenta()">Cerrar</button>
            </div>      
    </div>
  </div>
</div>