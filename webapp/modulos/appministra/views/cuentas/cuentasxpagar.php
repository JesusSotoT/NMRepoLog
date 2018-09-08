
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() 
    {
        $("#listaProveedores").select2({'width':'100%'});
        $.fn.modal.Constructor.prototype.enforceFocus = function () {};
        $('#del,#al').datepicker({
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
        <div class="col-xs-12 col-md-12"><h3>Cuentas por Pagar</h3></div>
    </div>
    <div class="row">
        <div class='col-xs-12 col-md-1 col-md-offset-3'>
            Proveedor:
        </div>
        <div class='col-xs-12 col-md-5'>
            <select id='listaProveedores' onchange='facturas(this)'>
                <option value='0'>Ninguno</option>
                <?php
                    while($l = $listaProveedores->fetch_assoc())
                        echo "<option value='".$l['idPrv']."'>(".$l['codigo'].") ".$l['razon_social']."</option>";
                ?>
            </select>
        </div>
    </div>
    <div class='row'>
        <div class='col-xs-12 col-md-1'>
        <button id='pagar' class='btn btn-primary' onclick="pagar(1)">Pago</button>
        </div>
    </div>
    <div class='row' id='listaFacturas'>
        <div class="col-xs-12 col-md-12 table-responsive">
                    
                    <table id="tabla-esp" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                            <tr><th></th><th>Id Compra</th><th>Fecha</th><th>Folio</th><th>Importe</th><th>Concepto</th><th>Saldo</th><th>Fecha de Vencimiento</th></tr>
                        </thead>
                        <tbody id='trs_esp'>
                        </tbody>
                    </table>
                </div>
    </div>
              
</div>

<script src="js/cuentas.js" type="text/javascript"></script>
<script language='javascript' src='js/bootstrap-datepicker.min.js'></script>
<link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker.min.css">
<script src="../../libraries/select2/dist/js/select2.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />

<!--AQUI LOS MODALS*************************************-->

<div class="modal fade bs-pagos-modal-md" tabindex="-1" role="dialog" aria-labelledby="pagos">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header panel-heading" style='background-color:#337ab7;color:#FFFFFF;'>
                <h4 id="modal-label">Pagos</h4>
            </div>
      <div class="modal-body well">
      <div class="row">
            <div class="col-xs-12 col-md-4">Pago Total: <input type='radio' id='radio_pago1' name='radio_pago' onclick='bloq_pagos(1)'></div>
            <div class="col-xs-12 col-md-6">Pago Parcial: <input type='radio' id='radio_pago2' name='radio_pago' onclick='bloq_pagos(0)'></div>
        </div>
        <div class='row'>
            <div class='col-xs-6 col-md-4'><b>Folio</b></div><div class='col-xs-6 col-md-6'><b>Cantidad</b></div>
        </div>
        <div class="row" id='inputPagos'>
        </div>
      </div>
            <div class="modal-footer">
                <button class='btn btn-default btn-sm' onclick="guardar_pagos()">Guardar</button><button class='btn btn-default btn-sm' onclick="cancelar_pagos()">Cancelar</button>
            </div>      
    </div>
  </div>
</div>


<!--TERMINAN MODALS*************************************-->
