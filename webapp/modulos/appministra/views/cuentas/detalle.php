<input type='hidden' id='detalle' value='<?php echo $_GET['f'] ?>'>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function()
    {
        //info_detalle(<?php echo $_GET['id'] ?>)
        pagos_detalle(<?php echo $_GET['id'] ?>,'<?php echo $_GET['t'] ?>',<?php echo $_GET['cp'] ?>,<?php echo $_GET['ori'] ?>);
        Number.prototype.format = function() {
          return this.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
        };
        $('#fecha_pago').datepicker({
                    format: "yyyy-mm-dd",
                    language: "es"
            });
        
    });
    function printer_s(id,t,cp,idrel,ori)
    {
      if(t=='f')
        t=1;
      else
        t=0;
         $.post('ajax.php?c=cuentas&f=printer',
        {
            idpago : id,
            idrelaciones: 0,
            tipo: t,
            valores : 0,
            monedas: 0,
            monedaPago : 0,
            cp: cp,
            idrel: idrel,
            proc_final:0,
            ori:ori
        },
        function(data)
        {
            var win=window.open('about:blank');
            with(win.document)
            {
              open();
              write(data);
              close();
            }
        });
    }
    function pagos_detalle(id,t,cp,ori)
    {
      $.post('ajax.php?c=cuentas&f=pagos_detalle',
        {
            id : id,
            t  : t,
            cp : cp,
            ori: ori

        },
         function(data)
        {
            $("#trs_pagos").html(data);
            var a = $("#inicial").attr('cantidad')
            
            var b = $("#tabla-pagos tr:last")
            var pendiente = $("#inicial").val();
            var pendiente2 = $("#inicial").attr('cantidad');
            if($("td:nth-child(6)",b).text())
            {
              pendiente = $("td:nth-child(6)",b).text();
              pendiente2 = $("td:nth-child(6)",b).attr('cantidad');
            }

            
            $("#pendiente").val(pendiente)
            $("#pendiente").attr('cantidad',pendiente2)
            var abonado = 0;
            $("#tabla-pagos tr").each(function(){
                if($("td:nth-child(2)",this).attr('cantidad'))
                  abonado += parseFloat($("td:nth-child(2)",this).attr('cantidad'))
              });
            $("#abonado").val("$ "+abonado.format()+" MXN")

        });
    }

    function rel_pago_det()
    {
      var cantidad    = parseFloat($("#cantidad_pagar").val()).toFixed(2);
      var disponible  = parseFloat($("#disponible").val()).toFixed(2);
      var total_cargo = parseFloat($("#total_cargo").val()).toFixed(2);
      disponible      = parseFloat(disponible);
      cantidad        = parseFloat(cantidad);
      total_cargo     = parseFloat(total_cargo);
      var sigue = 1;
      if(disponible < cantidad)
      {
        alert("El disponible en el pago es menor a la cantidad a pagar")
        sigue = 0;
      }

      if(total_cargo < cantidad)
      {
        alert("La cantidad a pagar es mayor al saldo de la cuenta")
        sigue = 0;
      }

      if(!cantidad)
      {
        alert("La cantidad a pagar debe ser mayor a cero")
        sigue = 0;
      }


      if(sigue)
      {
        $.post('ajax.php?c=cuentas&f=guardar_relacion_ret',
        {
            idpago  : $("#id_pago_ret").val(),
            cantidad:$("#cantidad_pagar").val(),
            docu    :$("#id_docu_ret").val(),
            tipo    :$("#tipo_ret").val(),
            monedaPago: $("#moneda").val()
        },
        function(data)
        {
          if(data)
            location.reload();
          else
            alert("Ocurrió un error y no se guardó la relación")
        });
      }
    }
    function cancelar_pago_det()
    {
        $('.bs-relacionar-modal-sm').modal('hide');
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

<div class="container well" id='pantalla'>
  <!-- Title -->
  <div class="row">
    <div class="col-xs-12 col-md-12"><h3>Detalle de la Cuenta</h3></div>
<?php
if(!intval($_GET['cp']))
  $cxcp = "cuentasxcobrar";
else
  $cxcp = "cuentasxpagar";
?>
    <div class="col-xs-12 col-md-2"><a class='btn btn-default' href='index.php?c=cuentas&f=<?php echo $cxcp;?>&id=<?php echo $datos_cli_prov['id_prov_cli'] ?>' role='button' ><span class='glyphicon glyphicon-arrow-left'></span> Regresar</a></div><div class="col-xs-12 col-md-10"></div>
  </div>
    <!-- Clientes -->
    <div class="row" style='border-top:1px solid #ddd;margin-top:2px;padding-top:5px;'>
      <!-- Label -->
        <div class='col-xs-12 col-md-6'>
        <?php
        $provcli = explode('**/**',$datos_cli_prov['provcli']);
         ?>
          <span id='concepto_detalle' style='color:#337ab7;font-size:18px;'><?php echo $provcli[0]."<br />".$datos_cli_prov['concepto']; ?></span>
          <input type='hidden' value='<?php echo $_GET['cp']; ?>' id='cobrar_pagar'>
          <?php
          if(intval($_GET['cp']))
            echo "<input type='hidden' value='".$datos_cli_prov['id_prov_cli']."' id='listaProveedores'>";
          else
            echo "<input type='hidden' value='".$datos_cli_prov['id_prov_cli']."' id='listaClientes'>";
          ?>
        </div> <!-- // Label -->
        <div class='col-xs-12 col-md-6'>
        <?php
        $datos_cli_prov['fecha_pago'] = explode(' ',$datos_cli_prov['fecha_pago']);
        $datos_cli_prov['fecha_pago'] = $datos_cli_prov['fecha_pago'][0];
        $vencimiento = new DateTime($datos_cli_prov['fecha_pago']);
        if(intval($provcli[1]))
          $vencimiento->add(new DateInterval('P'.$provcli[1].'D'));
        ?>
          <div class='row'>
            <div class='col-xs-12 col-md-4'>Fecha de Cargo
              <div class="input-group">
                <span class="input-group-addon glyphicon glyphicon-calendar" id="basic-addon1"></span>
                <input type='text' class='form-control' value='<?php echo $datos_cli_prov['fecha_pago']; ?>' readonly='readonly'>
              </div>
            </div>
            <div class='col-xs-12 col-md-4'>Fecha de Vencimiento
              <div class="input-group">
                  <span class="input-group-addon glyphicon glyphicon-calendar" id="basic-addon1"></span>
                  <input type='text' class='form-control' value='<?php echo $vencimiento->format('Y-m-d'); ?>' readonly='readonly'>
              </div>
            </div>
          </div>
          <div class='row'>
            <div class='col-xs-12 col-md-4'>Monto Inicial
            <?php
            if(!$_GET['t'] == 'c')
              $inicial = floatval($datos_cli_prov['cargo']) * floatval($datos_cli_prov['tipo_cambio']);
            else
              $inicial = floatval($datos_cli_prov['cargo']);
             ?>
              <input type='text' class='form-control' cantidad='<?php echo $inicial; ?>' value='$ <?php echo number_format(round($inicial,2),2) ?> MXN' id='inicial' readonly='readonly'>
            </div>
            <div class='col-xs-12 col-md-4'>Saldo Abonado
              <input type='text' class='form-control' value='' id='abonado' readonly='readonly'>
            </div>
            <div class='col-xs-12 col-md-4'><span style='color:red;'>Saldo Pendiente</span>
              <input type='text' class='form-control' value='' id='pendiente' readonly='readonly'>
            </div>
          </div>

        </div>
        
        
    </div> 

    
    
    
    <div class='row' id='listaPagos'>

      <div class="col-xs-12 col-md-12 table-responsive">
      <button id='pagar' class='btn btn-primary' onclick="pagar(0)">Nuevo Pago</button>
        <table id="tabla-pagos" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th>Fecha del Pago</th>
              <th>Abono</th>
              <th>Origen</th>
              <th>Forma de Pago</th>
              <th>Saldo Inicial</th>
              <th>Saldo Final</th>
              <th><span class='glyphicon glyphicon-inbox'></span></th>
            </tr>
          </thead>
          <tbody id='trs_pagos'>
          </tbody>
        </table>
      </div>

    </div>
</div> <!-- // Main container -->



<!--AQUI LOS MODALS*************************************-->

<!-- Modal Nuevo Pago -->
<div class="modal fade bs-pagos-modal-md" tabindex="-1" role="dialog" aria-labelledby="pagos" id="nuevo_pago">
  <!-- Modal Dialog -->
  <div class="modal-dialog modal-md">
    <!-- Modal container -->
    <div class="modal-content">
      <!-- Header modal -->
      <div class="modal-header panel-heading" style='background-color:#337ab7;color:#FFFFFF;'>
        <h4 id="modal-label">Nuevo <label id='pc'></label></h4>
        <input type='hidden' id='cobrar_pagar' value='1'><input type='hidden' id='tipo_pago'>
      </div> <!-- //header modal -->

      <!-- Body modal -->
      <div class="modal-body well">
        <!-- Fecha -->
        <div class='row'>
          <!-- Label fecha -->
          <div class='col-xs-6 col-md-4'>
            <b>Fecha:</b>
          </div> <!-- // label fecha -->
          <!-- Input fecha -->
          <div class='col-xs-6 col-md-6'>
            <input type='text' id='fecha_pago' class='form-control' onchange='tipo_cambio()'>
          </div> <!-- // input fecha -->
        </div> <!-- // Fecha -->

        <!-- Concepto -->
        <div class='row'>
          <!-- Label concepto -->
          <div class='col-xs-6 col-md-4'>
            <b>Concepto:</b>
          </div> <!-- // label concepto -->
          <!-- Input concepto -->
          <div class='col-xs-6 col-md-6'>
            <input type='text' id='concepto_pago' class='form-control'>
          </div> <!-- // input concepto -->
        </div> <!-- // Concepto -->

        <!-- Importe -->
        <div class='row'>
          <!-- Label importe -->
          <div class='col-xs-6 col-md-4'>
            <b>Importe:</b>
          </div> <!-- // label importe -->
          <!-- Input importe -->
          <div class='col-xs-6 col-md-6'>
            <input type='text' id='importe_pago' class='form-control' onkeypress="return NumCheck(event, this)">
          </div> <!-- // Input importe -->
        </div> <!-- Importe -->

        <!-- Forma de pago -->
        <div class='row' id='forma_pago_div'>
          <!-- Label Forma pago -->
          <div class='col-xs-6 col-md-4'>
            <b>Forma de Pago:</b>
          </div> <!--  // Label forma pago -->
          <!-- Select Forma pago -->
          <div class='col-xs-6 col-md-6'>
            <select id='forma_pago' class='form-control' onchange="mostrar_numero_cheque(this.options[this.selectedIndex].value)">
              <?php
                while($lfp = $listaFormasPago->fetch_object())
                echo "<option value='$lfp->idFormapago'>($lfp->claveSat) $lfp->nombre</option>";
              ?>
            </select>
          </div> <!-- // Select forma pago -->
        </div> <!-- // Forma de pago -->

        <!-- Numero cheque -->
        <div class="row hidden" id="num_cheque_container">
        </div> <!-- Numero cheque -->

        <!-- Numero cheque -->
        <div class="row hidden" id="comprobante_container">
        </div> <!-- Numero cheque -->

        <!-- Moneda -->
        <div class='row'>
          <!-- Label Moneda -->
          <div class='col-xs-6 col-md-4'>
            <b>Moneda:</b>
          </div> <!-- // label moneda -->
          <!-- Input moneda -->
          <div class='col-xs-6 col-md-6'>
            <select id='moneda' class='form-control' onchange='tipo_cambio()'>
              <?php
                while($l = $listaMonedas->fetch_object())
                echo "<option value='$l->coin_id'>($l->codigo) $l->description</option>";
              ?>
            </select>
          </div> <!-- // input moneda -->
        </div> <!-- // Moneda -->

        <!-- Tipo cambio -->
        <div class='row' id='row_tipo_cambio'>
          <!-- Label tipo de cambio -->
          <div class='col-xs-6 col-md-4'>
            <b>Tipo de Cambio:</b>
          </div> <!-- // label tipo de cambio -->
          <!-- Input tipo de cambio -->
          <div class='col-xs-6 col-md-6'>
              <input type='text' id='tipo_cambio'>
          </div> <!-- // input tipo de cambio -->
        </div> <!-- // tipo cambio -->

        <!-- Modal footer -->
        <div class="modal-footer">
          <button class='btn btn-default btn-sm' onclick="guardar_pagos()" id="btn_guardar_pago">Siguiente</button>
          <button class='btn btn-default btn-sm' onclick="cancelar_pagos()" id="btn_cancelar_pago">Cancelar</button>
        </div> <!-- // Modal footer -->
      </div> <!-- // Modal body -->
    </div> <!-- // Modal content -->
  </div> <!-- // Modal dialog -->
</div> <!-- // Modal nuevo pago -->

<!-- Modal Nuevo Pago -->
<div class="modal fade bs-relacionar-modal-sm" tabindex="-1" role="dialog" aria-labelledby="relacionar" id="nueva_relacion">
  <!-- Modal Dialog -->
  <div class="modal-dialog modal-sm">
    <!-- Modal container -->
    <div class="modal-content">
      <!-- Header modal -->
      <div class="modal-header panel-heading" style='background-color:#337ab7;color:#FFFFFF;'>
        <h4 id="modal-label">Relacionar  este cargo<input type='hidden' id='id_pago_ret' class='form-control'><input type='hidden' id='id_docu_ret' class='form-control' value='<?php echo $_GET['id']; ?>'><input type='hidden' id='tipo_ret' class='form-control' value='<?php echo $_GET['t']; ?>'>
      </div> <!-- //header modal -->
      <!-- Body modal -->
      <div class="modal-body well">
        <!-- Concepto -->
        <div class='row'>
          <!-- Label concepto -->
          <div class='col-xs-6 col-md-4'>
            <b>Cantidad disponible del Pago:</b>
          </div> <!-- // label concepto -->
          <!-- Input concepto -->
          <div class='col-xs-6 col-md-6'>
            <input type='text' id='disponible' class='form-control' disabled>
          </div> <!-- // input concepto -->
        </div> <!-- // Concepto -->

        <div class='row'>
          <!-- Label concepto -->
          <div class='col-xs-6 col-md-4'>
            <b>Cantidad a pagar:</b>
          </div> <!-- // label concepto -->
          <!-- Input concepto -->
          <div class='col-xs-6 col-md-6'>
            <input type='text' id='cantidad_pagar' class='form-control'>
            <input type='text' id='total_cargo' class='form-control' disabled>
          </div> <!-- // input concepto -->
        </div> <!-- // Concepto -->
        <!-- Modal footer -->
        <div class="modal-footer">
          <button class='btn btn-default btn-sm' onclick="rel_pago_det()">Guardar</button>
          <button class='btn btn-default btn-sm' onclick="cancelar_pago_det()">Cancelar</button>
        </div> <!-- // Modal footer -->
      </div> <!-- // Modal body -->
    </div> <!-- // Modal content -->
  </div> <!-- // Modal dialog -->
</div> <!-- // Modal nuevo pago -->

<script src="js/cuentas2.js" type="text/javascript"></script>
<script language='javascript' src='js/bootstrap-datepicker.es.js'></script>
<script language='javascript' src='js/bootstrap-datepicker.min.js'></script>
<link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker.min.css">
<script src="../../libraries/select2/dist/js/select2.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />