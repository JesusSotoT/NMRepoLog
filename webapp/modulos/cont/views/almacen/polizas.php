<script type="text/javascript" src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">

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
//require "views/partial/modal-generico.php";
?>
<div class="container well">
    <div class="row">
        <div class="col-xs-12 col-md-12"><h3>Configuraci&oacute;n de Polizas</h3></div>
    </div>
    <div class="row">
      
              
              <div class="panel-heading">
                <h3 class="panel-title">Configurar polizas</h3>
              </div>
              <div class="panel-body">
              <div class='col-xs-12 col-md-12'>
                  <table>
                    <tr><td colspan='5'><button onclick='abrir_polizas(0)' class='btn btn-primary'>Agregar Poliza</button></td></tr>
                  </table>
                </div>
                <div class='col-xs-12 col-md-12'>
                    <div class='table-responsive'>  
                      <table id='polizas' class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                          <tr><th>Id</th><th>Documento</th><th>Tipo Poliza</th><th>Nombre Poliza</th><th>Provision</th><th>Modificar</th><th>Eliminar</th></tr>
                        </thead>
                      </table>
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


<!--Modal de Polizas-->
<div class="modal fade bs-polizas-modal-lg" tabindex="-1" role="dialog" aria-labelledby="polizas">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header panel-heading" style='background-color:#337ab7;color:#FFFFFF;'>
                <h4 id="modal-label"><label id='am_c'></label> Poliza</h4>
            </div>
      <div class="modal-body well"><input type='text' id='id_poliza'>
        

              <div class="panel-body">
                <div class='row'>
                  <div class='col-xs-12 col-md-4'>
                  </div>
                  <div class='col-xs-12 col-md-5'>
                  <span id='mensaje'></span>
                  </div>
                  
                  
                </div>
                <div class='row'>
                  <div class='col-xs-12 col-md-6'>
                    <div class='col-xs-12 col-md-12'>Tipo de Poliza</div>
                    <div class='col-xs-12 col-md-12'>
                        <select id='tipo_poliza' class='form-control'>
                            <option value='1'>Ingresos</option>
                            <option value='2'>Egresos</option>
                            <option value='3'>Diario</option>
                            <option value='4'>Orden</option>
                          </select>
                      </div>
                  </div>
                  <div class='col-xs-12 col-md-6'>
                    <div class='col-xs-12 col-md-12'>Es de provision?</div>
                    <div class='col-xs-12 col-md-12'><select id='provision' class='form-control'><option value='1'>Si</option><option value='0'>No</option></select></div>
                  </div>
                </div>
                  <div class='row'>
                    <div class='col-xs-12 col-md-12'>
                    <div class='col-xs-12 col-md-12'>Concepto</div>
                    <div class='col-xs-12 col-md-9'>
                      <input type='text' id='concepto' class='form-control'>
                    </div>
                  </div>
                </div>
                <div class='row'>
                    <div class='col-xs-12 col-md-12'>
                    <div class='col-xs-12 col-md-6'>Segmento</div><div class='col-xs-12 col-md-6'>Sucursal</div>
                    <div class='col-xs-12 col-md-6'>
                      <select id='segmentos' class='form-control'>
                      <?php
                      while($s = $segmentos->fetch_object())
                        echo "<option value='$s->idSuc'>($s->clave) $s->nombre</option>";
                      ?>
                      </select>
                    </div>
                    <div class='col-xs-12 col-md-6'>
                      <select id='sucursales' class='form-control'>
                      <?php
                      while($s = $sucursales->fetch_object())
                        echo "<option value='$s->idSuc'>($s->clave) $s->nombre</option>";
                      ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class='row'>
                  <div class='col-xs-12 col-md-12'>
                </div>
                <div class='row'>
                <div class='col-xs-12 col-md-12'>
                  <table>
                    <tr><td colspan='5'><button onclick='abrir_cuenta(0)' class='btn btn-primary' id='agregar_cuenta_btn'>Agregar Cuenta</button></td></tr>
                  </table>
                </div>
                  <div class='col-xs-12 col-md-12'>
                    <div class='table-responsive'>  
                      <table id='cuentas' class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                          <tr><th>Codigo</th><th>Cuenta</th><th>Cargo</th><th>Abono</th><th>Vinculacion</th><th>Modificar</th><th>Eliminar</th></tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                  <div class='col-xs-12 col-md-12'>
                    <button id='guardar_poliza' onclick="guardar_poliza()" class='btn btn-default'>Guardar Poliza</button>
                  </div>
                </div>
              </div>
                
      </div>
            <!--<div class="modal-footer">
                <button class='btn btn-default btn-sm' onclick="cerrar_poliza()">Cerrar</button>
            </div>      -->
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
            <div class='col-xs-6 col-md-4'><b>Cuenta:</b><input type='hidden' id='cuenta_hid' value='0'></div><div class='col-xs-6 col-md-6'>
              <select id='cuentas_lista' class='form-control cuentas_lista'>
                <?php
                      while($c = $cuentas->fetch_object())
                        echo "<option value='$c->account_id'>($c->manual_code) $c->description</option>";
                      ?>
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
            <!--<select id='vinculacion' onchange='imp()' class='form-control'>-->
            <select id='vinculacion' class='form-control'>
               <?php
                while($v = $vincular->fetch_object())
                  echo "<option value='$v->id'>$v->nombre</option>";
                ?>
                <option value='6'>Gasto</option>
            </select>
          </div>
        </div>
        <!--<div class='row' id='imps'>  
          <div class='col-xs-6 col-md-4'><b>Impuesto:</b></div>
          <div class='col-xs-6 col-md-6'>
            <select id='impuestos'>
              <option value="IVA 16%">IVA 16%</option>
              <option value="IVA 0%">TOTAL IMPORTES CON IVA 0%</option>
              <option value="IVA EXENTO">TOTAL IMPORTES CON IVA EXENTO</option>
              <option value="IVA IMPS">TOTAL IMPORTES CON IVA 16%</option>
            </select>
          </div>
        </div>-->
      </div>
            <div class="modal-footer">
                <button class='btn btn-default btn-sm' onclick="agregar_cuenta()">Guardar</button><button class='btn btn-default btn-sm' onclick="cerrar_cuenta()">Cerrar</button>
            </div>      
    </div>
  </div>
</div>