<script language='javascript' src='js/bootstrap-datepicker.es.js'></script>
<script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
<script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
<script src="../../libraries/export_print/buttons.html5.min.js"> </script>
<script src="../../libraries/export_print/jszip.min.js"></script>
<link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="../../libraries/dataTable/css/datatables.min.css">
<link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">

<script type="text/javascript" charset="utf-8">
    $(document).ready(function()
    {
        //$("#listaClientes,#moneda").select2({'width':'100%'});
        $("#moneda").select2({'width':'100%'});
        $.fn.modal.Constructor.prototype.enforceFocus = function () {};
        $("#pagar,#listaFacturasBtn,#listaCargosBtn,#saldo_gral_div").hide();
        $('#fecha_pago').datepicker({
                    format: "yyyy-mm-dd",
                    language: "es"
            });
        $("#forma_pago").val(7)
        $("#listaClientes").click();
        listaCargosFacturas();
        $("#div_fade").hide();
    });
    function validar(t)
    {
        if(t.layout.value == '')
        {
            alert("Agregue un archivo xls.");
            return false;
        }
    }

    function fade()
    {
      $("#div_fade").slideToggle(500);
    }
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
  div.well {
    margin-bottom: 0;
  }
  .hidden {
    display: none;
  }
  .modal-footer {
    padding-bottom: 0;
  }
  .modal-header {
    padding-bottom: 0;
  }

  #fade a
  {
    color:black;
    text-decoration:none;
  }

  #fade a:hover
  {
    text-decoration:none;
  }

  th
  {
      text-align:center;
  }
  td
  {
      text-align:center;
  }

</style>

<!-- Main Container -->
<div id='cargando' class='col-xs-12 col-md-12' style='margin-bottom:20px;text-align: center;font-size:20px;color:#337ab7;'><b>Cargando...</b></div>
<div class="container well" id='pantalla' style='display:none;'>
  <!-- Title -->
  <div class="row">
    <div class="col-xs-12 col-md-12"><h3>Detalle del Cliente</h3></div>

    <div class="col-xs-12 col-md-2"><a class='btn btn-default' href='index.php?c=cuentas&f=lista&t=0' role='button' ><span class='glyphicon glyphicon-arrow-left'></span> Regresar</a></div><div class="col-xs-12 col-md-10"></div>
  </div>
    <!-- Clientes -->
    <div class="row" style='border-top:1px solid #ddd;margin-top:2px;padding-top:5px;'>
      <!-- Label -->
        <div class='col-xs-12 col-md-7'>
          <span id='nombre_cliente' style='font-size:20px;font-weight:bold;'></span>
        </div> <!-- // Label -->
        <!-- Select Clientes -->
        <div class='col-xs-12 col-md-5' style='text-align: right;'>
          <!--<select id='listaClientes' onchange='cobrosSinAsignar()'>
            <option value='*|*'>Ninguno</option>-->
            <!--<option value='0'>(0) Público General</option>-->
            <?php
             // while($l = $listaClientes->fetch_assoc())
               // echo "<option value='".$l['id']."'>(".$l['codigo'].") ".$l['nombre']."</option>";
            ?>
          <!--</select>-->
          <input type='hidden' id='listaClientes' value='<?php echo $_GET['id'] ?>' onclick='cobrosSinAsignar()'>
           <!-- Boton nuevo pago -->
          <button id='pagar' class='btn btn-primary' onclick="pagar(0); esconder_campos()">Nuevo Pago</button>&nbsp;
          <!-- Boton nuevo cargo -->
          <button id='pagar' class='btn btn-primary' onclick="pagar(1); esconder_campos()">Nuevo Cargo</button>&nbsp;
          <!-- Boton facturas -->
          <button id='listaFacturasBtn' class='btn btn-default' onclick="listaFacturas(0)">Facturas <span class='glyphicon glyphicon-modal-window'></span></button>&nbsp;
          <!-- Boton cargos -->
          <button id='listaCargosBtn' class='btn btn-default' onclick="listaCargos(0)">Cargos <span class='glyphicon glyphicon-modal-window'></span></button>
        </div> <!-- // Select Clientes -->
    </div> <!-- Clientes -->

    

    <?php
    if(!intval($hayMovs))
    {
    ?>
    <!-- Subir layout -->
    <div class='row' id='layout'>
      <!-- Subir saldos iniciales mediante layout -->
      <div class='col-sm-12 col-md-offset-1 col-md-5'>
        <b>Subir saldos iniciales mediante layout</b> /
        <a href='importacion/cuentas_cobrar.xls'>Descargar</a>
        <br/>
        <!-- Form -->
        <form action='index.php?c=cuentas&f=subeLayout&t=0' method='post' name='archivo' enctype="multipart/form-data" id='arch' onsubmit='return validar(this)'>
          <input type='file' id='layout' name='layout'>
          <br/>
          <button type='submit' class='btn btn-default'>Cargar</button>
        </form> <!-- // Form --> 
      </div> <!-- // Subir saldos iniciales mediante layout -->
        
            <div class='col-sm-12 col-md-offset-1 col-md-5'>
              <b>Subir las cuentas por cobrar de la version anterior como saldos iniciales</b> <br />
                <button class='btn btn-default' onclick="$('.bs-impo-modal-sm').modal('show');">Cargar</button>
            </div>
    </div> <!-- // Subir layout -->
    <?php
    }
    ?>
    <!-- Lista pagos sin asignar -->
    <div class='row' id='listaPagosSinAsignar'>
      <!-- Table container -->
      <div class="col-xs-12 col-md-12" id='fade'><span class='glyphicon glyphicon-list'></span> <a href='javascript:fade()'>Saldos por aplicar</a></div>
      <div class="col-xs-12 col-md-12 table-responsive" id='div_fade'>
        <table id="tabla-esp" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
          <!-- Table header -->
          <thead>
            <tr>
              <th>Fecha del Pago</th>
              <th>Concepto</th>
              <th>Pago</th>
              <th>Saldo por Aplicar(MXN)</th>
              <th>Forma de Pago</th>
              <th></th>
            </tr>
          </thead> <!-- // Table header -->
          <!-- Table body -->
          <tbody id='trs_esp'>
          </tbody> <!-- // Table body -->
        </table> <!-- // Table -->
      </div> <!-- // Table container -->

      <!-- Saldo General & Total -->
      <div class="col-xs-12 col-md-12" id='saldo_gral_div' style='margin-top:25px;margin-bottom:25px;'>
        <table> <!-- Table bottom -->
          <!-- Total -->
          <tr><td>Total Pagos sin Aplicar en Pesos:</td><td>$ <span id='pagos_sin' style='font-weight: bold'></span></td></tr>
           <!-- Saldo General -->
          <!--<tr><td>Saldo General del Cliente en Pesos:</td><td>$ <span id='saldo_general' style='font-weight: bold'></span></td></tr>-->
        </table> <!-- // Table bottom -->
      </div> <!-- // Saldo General & Total -->
    </div> <!-- // Lista pagos sin asignar -->
    <div class='row' id='listaCargosFac'>
      <div class="col-xs-12 col-md-12 table-responsive">
      <div id='saldos_div2'>&nbsp;&nbsp;&nbsp;<span style='font-size:14px;'>Saldo total del cliente</span> <input type='text' id='total_saldos' readonly="readonly" style='text-align:center;font-weight:bold;font-size:16px;'></div>
        <table id="tabla-carfac" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th>Fecha de Cargo / Factura</th>
              <th>Fecha de Vencimiento</th>
              <th>Concepto</th>
              <th>Folio</th>
              <th>Moneda</th>
              <th>Monto</th>
              <th>Saldo Abonado MXN</th>
              <th>Saldo Actual MXN</th>
              <th>Estatus</th>
              <th>OV</th>
            </tr>
          </thead>
          <tbody id='trs_carfac'>
          </tbody>
        </table>
      </div>

    </div>
</div> <!-- // Main container -->


<!-- ######### Scripts & Links ######### -->
<script src="js/cuentas2.js" type="text/javascript"></script>
<script language='javascript' src='js/bootstrap-datepicker.min.js'></script>
<link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker.min.css">
<script src="../../libraries/select2/dist/js/select2.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />

<!--AQUI LOS MODALS*************************************-->

<!-- Modal Pagos -->
<div class="modal fade bs-pagos-modal-md" tabindex="-1" role="dialog" aria-labelledby="pagos" id="pagos_modal">
  <!-- Modal Dialog -->
  <div class="modal-dialog modal-md">
    <!-- Modal content -->
    <div class="modal-content">
      <!-- Modal header -->
      <div class="modal-header panel-heading" style='background-color:#337ab7;color:#FFFFFF;'>
        <h4 id="modal-label">Nuevo <label id='pc'></label></h4>
        <input type='hidden' id='cobrar_pagar' value='0'><input type='hidden' id='tipo_pago'>
      </div> <!-- // Modal header -->

      <!-- Modal body -->
      <div class="modal-body well">
        <!-- Fecha -->
        <div class='row'>
          <!-- Label -->
          <div class='col-xs-6 col-md-4'>
            <b>Fecha:</b>
          </div> <!-- // label -->
          <!-- Input -->
          <div class='col-xs-6 col-md-6'>
            <input type='text' id='fecha_pago' class='form-control' onchange='tipo_cambio()'>
          </div> <!-- // Input -->
        </div> <!-- // Fecha -->

        <!-- Concepto -->
        <div class='row'>
            <div class='col-xs-6 col-md-4'><b>Concepto:</b></div><div class='col-xs-6 col-md-6'><input type='text' id='concepto_pago' class='form-control'></div>
        </div> <!-- // Concepto -->

        <!-- Importe -->
        <div class='row'>
          <!-- Label -->
          <div class='col-xs-6 col-md-4'>
            <b>Importe:</b>
          </div> <!-- // label -->
          <!-- Input -->
          <div class='col-xs-6 col-md-6'>
            <input type='text' id='importe_pago' class='form-control' onkeypress="return NumCheck(event, this)">
          </div> <!-- // input -->
        </div> <!-- // Importe -->

        <!-- Forma de pago -->
        <div class='row' id='forma_pago_div'>
          <!-- Label -->
          <div class='col-xs-6 col-md-4'>
            <b>Forma de Pago:</b>
          </div> <!-- // label -->
          <!-- Select -->
          <div class='col-xs-6 col-md-6'>
            <select id='forma_pago' class='form-control'
            onchange="mostrar_numero_cheque(this.options[this.selectedIndex].value)">
            <?php
              while($lfp = $listaFormasPago->fetch_object())
                echo "<option value='$lfp->idFormapago'>($lfp->claveSat) $lfp->nombre</option>";
            ?>
            </select>
          </div> <!-- // Select -->
        </div> <!-- // Forma de pago -->

        <!-- Numero cheque -->
        <div class="row hidden" id="num_cheque_container">
        </div> <!-- Numero cheque -->

        <!-- Comprobante -->
        <div class="row hidden" id="comprobante_container">
        </div> <!-- Comprobante -->

        <!-- Moneda -->
        <div class='row'>
          <!-- Label -->
          <div class='col-xs-6 col-md-4'>
            <b>Moneda:</b>
          </div> <!-- // Label -->
          <!-- Select -->
          <div class='col-xs-6 col-md-6'>
            <select id='moneda' class='form-control' onchange='tipo_cambio()'>
            <?php
              while($l = $listaMonedas->fetch_object())
                echo "<option value='$l->coin_id'>($l->codigo) $l->description</option>";
            ?>
            </select>
          </div> <!-- Select -->
        </div> <!-- Moneda -->

        <!-- Tipo cambio -->
        <div class='row' id='row_tipo_cambio'>
          <!-- Label -->
          <div class='col-xs-6 col-md-4'>
            <b>Tipo de Cambio:</b>
          </div> <!-- // label -->
          <!-- Input -->
          <div class='col-xs-6 col-md-6'>
            <input type='text' id='tipo_cambio'>
          </div> <!-- // input -->
        </div> <!-- // Tipo cambio -->

        <!-- Modal footer -->
        <div class="modal-footer">
          <button class='btn btn-default btn-sm' onclick="guardar_pagos()" id="btn_guardar_pago">Guardar</button>
          <button class='btn btn-default btn-sm' onclick="cancelar_pagos(); esconder_campos()" id="btn_cancelar_pago">Cancelar</button>
        </div> <!-- // Modal footer -->
      </div> <!-- // Modal body -->
    </div> <!-- // Modal content -->
  </div> <!-- // Modal dialog -->
</div> <!-- // Modal pagos -->

<!-- Modal Aplicar -->
<div class="modal fade bs-aplicar-modal-md" tabindex="-1" role="dialog" aria-labelledby="aplicar">
  <!-- Modal dialog -->
  <div class="modal-dialog modal-md">
    <!-- Modal content -->
    <div class="modal-content">
      <!-- Modal header -->
      <div class="modal-header panel-heading" style='background-color:#337ab7;color:#FFFFFF;'>
        <h4 id="modal-label">Aplicar Pago<input type='hidden' id='idpago'>
      </div> <!-- // Modal header -->

      <!-- Modal body -->
      <div class="modal-body well">
        <!-- Facturas -->
        <div class='row'>
          <!-- label -->
          <div class='col-xs-6 col-md-4'>
            <b>Factura:</b>
          </div> <!-- // label -->
          <!-- select -->
          <div class='col-xs-6 col-md-6'>
          <select id='listaFolios' multiple>
              <option value='0'>Ninguna</option>
          </select>
          </div> <!-- // select -->
        </div> <!-- // factura -->

        <!-- Cargos -->
        <div class='row'>
          <!-- Label -->
          <div class='col-xs-6 col-md-4'>
            <b>Cargo:</b>
          </div> <!-- // label -->
          <!-- Select -->
          <div class='col-xs-6 col-md-6'>
            <select id='listaCargos' multiple>
              <option value='0'>Ninguna</option>
            </select>
          </div> <!-- // Select -->
        </div> <!-- // Cargos -->

        <div class='row' id='importes_fac_car'></div>


        <!-- Modal footer -->
        <div class="modal-footer">
          <button class='btn btn-default btn-sm' onclick="guardar_relacion_pagos()">Guardar</button>
          <button class='btn btn-default btn-sm' onclick="cancelar_relacion_pagos()">Cancelar</button>
        </div> <!-- // Modal footer -->

      </div> <!-- // modal body -->
    </div> <!-- // Modal content -->
  </div> <!-- // Modal dialog -->
</div> <!-- // Modal aplicar -->

<!-- Modal lista facturas -->
<div class="modal fade bs-listaFacturas-modal-lg" tabindex="-1" principal-scroll='1' role="dialog" aria-labelledby="listaFacturas" id='myModal'>
  <!-- Modal dialog -->
  <div class="modal-dialog modal-lg">
    <!-- Modal content -->
    <div class="modal-content">
      <!-- Modal header -->
      <div class="modal-header panel-heading" style='background-color:#337ab7;color:#FFFFFF;'>
        <h4 id="modal-label">Facturas</h4>
      </div> <!-- // Modal header -->

      <!-- Modal body -->
      <div class="modal-body well">
        <!-- Relacionar -->
        <div class='row'>
          <div class='col-xs-12 col-md-1'>
            <button id='relacionar_factura' class='btn btn-primary' onclick="relacionar(1)">Relacionar</button>
          </div>
        </div> <!-- // Relacionar -->

        <!-- Lista facturas -->
        <div class='row' id='listaFacturas'>
          <!-- Table container -->
          <div class="col-xs-12 col-md-12 table-responsive">
            <!-- Table -->
            <table id="tabla-esp" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
              <!-- Table header -->
              <thead>
                <tr>
                  <th></th>
                  <th>Id Venta</th>
                  <th>Folio</th>
                  <th>Concepto</th>
                  <th>Fecha</th>
                  <th>Fecha de Vencimiento</th>
                  <th>Importe</th>
                  <th>Saldo</th>
                  <th>Aplicar</th>
                </tr>
              </thead> <!-- table header -->

              <!-- Table body -->
              <tbody id='trs_fac'>
              </tbody><!-- // table body -->
            </table> <!-- // table -->
          </div> <!-- // table container -->
        </div> <!-- // Lista facturas -->
      </div> <!-- // Modal body -->
    </div> <!-- // Modal content -->
  </div> <!-- // Modal dialog -->
</div> <!-- // Modal lista de facturas -->

<!-- Modal Lista cargos -->
<div class="modal fade bs-listaCargos-modal-lg" tabindex="-1" principal-scroll='1' role="dialog" aria-labelledby="listaCargos">
  <!-- Modal dialog -->
  <div class="modal-dialog modal-lg">
    <!-- Modal content -->
    <div class="modal-content">
      <!-- Modal header -->
      <div class="modal-header panel-heading" style='background-color:#337ab7;color:#FFFFFF;'>
        <h4 id="modal-label">Cargos</h4>
      </div> <!-- Modal header -->

      <!-- Modal body -->
      <div class="modal-body well">
        <!-- Relacionar -->
        <div class='row'>
          <div class='col-xs-12 col-md-1'>
            <button id='relacionar_cargo' class='btn btn-primary' onclick="relacionar(0)">Relacionar</button>
          </div> <!-- // div col -->
        </div> <!-- // Relacionar -->

        <!-- Lista Cargos -->
        <div class='row' id='listaCargos'>
          <div class="col-xs-12 col-md-12 table-responsive">
            <!-- Table -->
            <table id="tabla-car" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
              <!-- Table header -->
              <thead>
                <tr>
                  <th></th>
                  <th>Id Documento</th>
                  <th>Documento</th>
                  <th>Fecha</th>
                  <th>Importe</th>
                  <th>Saldo</th>
                  <th>Aplicar</th>
                </tr>
              </thead> <!-- // table header -->

              <!-- Table body -->
              <tbody id='trs_car'>
              </tbody> <!-- // table body -->
            </table> <!-- // table -->
          </div> <!-- // table container -->
        </div> <!-- // lista cargos -->
      </div> <!-- // modal body -->
    </div> <!-- //  modal content -->
  </div> <!-- //  modal dialog -->
</div> <!-- //  modal lista cargos-->

<!-- Modal aplicar -->
<div class="modal fade bs-aplicar-modal-sm" tabindex="-1" role="dialog" aria-labelledby="aplicar">
  <!-- Modal dialog -->
  <div class="modal-dialog modal-sm">
    <!-- Modal content -->
    <div class="modal-content">
      <!-- Modal header -->
      <div class="modal-header panel-heading" style='background-color:#337ab7;color:#FFFFFF;'>
        <h4 id="modal-label">Aplicar a:</h4>
      </div>
      <!-- Modal body -->
      <div class="modal-body well">
        <!-- Aplicar factura -->
        <div class='row'>
          <div class='col-xs-12 col-md-12'>
            <button id='aplicar_factura' class='btn btn-default' onclick="aplicar_factura()">Facturas</button>
            <button id='aplicar_cargo' class='btn btn-default' onclick="aplicar_cargo()">Cargos</button>
          </div> <!-- // div col -->
        </div> <!-- // aplicar factura -->
      </div> <!-- // modal body -->
    </div> <!-- // modal content -->
  </div> <!-- // modal dialog -->
</div> <!-- // modal aplicar -->

<!-- Modal Relaciones -->
<div class="modal fade bs-relaciones-modal-md" tabindex="-1" role="dialog" aria-labelledby="relaciones">
  <!-- Modal Dialog -->
  <div class="modal-dialog modal-md">
    <!-- Modal Content -->
    <div class="modal-content">
      <div class="modal-header panel-heading" style='background-color:#337ab7;color:#FFFFFF;'>
        <h4 id="modal-label">Relaciones de Pagos</h4>
      </div> <!-- // Modal Header -->

      <!-- Modal Body -->
      <div class="modal-body well">
        <!-- Folio o cargo -->
        <div class='row'>
          <!-- Title Folio o cargo -->
          <div class='col-xs-6 col-md-7'>
            <b id='folio_o_cargo'></b>
          </div> <!-- div col title folio o cargo -->
          <!-- Cantidad -->
          <div class='col-xs-6 col-md-5'>
            <b>Cantidad</b>
          </div> <!-- // div col cantidad -->
        </div> <!-- // Folio o cargo -->
        <div class="row" id='inputRelaciones'>
        </div>
        <div class="row">
          <div class='col-xs-6 col-md-7'></div><div class='col-xs-6 col-md-5'><b id='tot_rel'></b></div>
          <div class='col-xs-6 col-md-5'></div><div class='col-xs-6 col-md-2'><b>Diferencia:</b></div><div class='col-xs-6 col-md-5'><b id='dif_rel'></b></div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
          <button class='btn btn-default btn-sm' id='guardar_relacion'>Guardar</button><button class='btn btn-default btn-sm' onclick="cancelar_relacion()">Cancelar</button>
      </div> <!-- // Modal footer -->
    </div> <!-- // Modal Content -->
  </div> <!-- // Modal dialog -->
</div> <!-- // Modal Relaciones -->

<div class="modal fade bs-datos-modal-md" tabindex="-1" role="dialog" aria-labelledby="datos">
  <!-- Modal Dialog -->
  <div class="modal-dialog modal-md">
    <!-- Modal Content -->
    <div class="modal-content">
      <div class="modal-header panel-heading" style='background-color:#337ab7;color:#FFFFFF;'>
        <h4 id="modal-label">Datos Generales</h4>
      </div> <!-- // Modal Header -->

      <div class="modal-body well">
        <div class='row'>
         <div class='col-xs-12 col-md-12'>
            <div class='col-xs-12 col-md-8'>
              <div class='col-xs-12 col-md-12' id='razon' style='font-size:17px;font-weight:bold;'></div>
              <div class='col-xs-12 col-md-12' id='rfc' style='color:gray;'></div>
              <div class='col-xs-12 col-md-12' id='domicilio'></div>
            </div>
            <div class='col-xs-12 col-md-4'>
              <div class='col-xs-12 col-md-12' style='color:white;background-color:#337ab7;'>Detalles del cliente</div>
              <div class='col-xs-12 col-md-12' id='dias_credito' style='border-left:1px solid #337ab7;'></div>
              <div class='col-xs-12 col-md-12' id='limite_credito' style='border-left:1px solid #337ab7;'></div>
            </div>
        </div>
      </div>
      <div class='row'>
        <div class='col-xs-12 col-md-12' style='color:white;background-color:#337ab7;font-size:17px;margin-bottom:10px;'>
          Datos de Contactos
        </div>
        <div class='col-xs-12 col-md-12' id='datos_contacto'>
            
        </div>
      </div>
    </div> <!-- // Modal Content -->
  </div> <!-- // Modal dialog -->
</div> <!-- // Modal Relaciones -->
</div>

<!-- Modal Importacion Pagos -->

<!-- Modal Importacion Pagos -->
<div class="modal fade bs-impo-modal-sm" tabindex="-1" role="dialog" aria-labelledby="impo">
  <!-- Modal dialog -->
  <div class="modal-dialog modal-sm">
    <!-- Modal content -->
    <div class="modal-content">
      <!-- Modal header -->
      <div class="modal-header panel-heading" style='background-color:#337ab7;color:#FFFFFF;'>
        <h4 id="modal-label">Extraer cobros de sistema anterior</h4>
      </div> <!-- modal header -->

      <!-- Modal body -->
      <div class="modal-body well">  
        <div class="row">
          <div class='col-xs-12 col-md-6'>Nombre Instancia</div>
          <div class='col-xs-12 col-md-6'><input type='text' id='instancia' class='form-control'></div>
        </div> 
        <div class="row">
          <div class='col-xs-12 col-md-6'>Usuario</div>
          <div class='col-xs-12 col-md-6'><input type='text' id='usuario_p' class='form-control'></div>
        </div> 
        <div class="row">
          <div class='col-xs-12 col-md-6'>Contraseña</div>
          <div class='col-xs-12 col-md-6'><input type='password' id='contrasenia_p' class='form-control'></div>
        </div> 
      </div> <!-- // modal body -->

      <!-- Modal footer -->
      <div class="modal-footer">
        <button class='btn btn-default btn-sm' id='importar' onclick='cuentas_sis_anterior(0)'>Importar</button>
      </div> <!-- modal footer -->
    </div> <!-- modal content -->
  </div> <!-- modal dialog -->
</div> <!-- modal relaciones -->

<!--TERMINAN MODALS*************************************-->
<a id='printer' style='width:10px;color:white;' >.</a>
