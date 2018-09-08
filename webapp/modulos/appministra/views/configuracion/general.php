
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
<input type='hidden' id='pestania' value='<?php echo $_GET['p'] ?>'>
<div class="container well">
    <div class="row">
        <div class="col-xs-12 col-md-12"><h3>Configuraci&oacute;n Avanzada</h3></div>
    </div>
    <div class="row">
       <!-- Nav tabs -->
      <ul id='myTabs' class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#ejercicios" aria-controls="ejercicios" role="tab" data-toggle="tab">Ejercicios</a></li>
        <li role="presentation"><a href="#periodos" aria-controls="periodos" role="tab" data-toggle="tab">Periodos</a></li>
        <li role="presentation"><a href="#metcosteo" aria-controls="metcosteo" role="tab" data-toggle="tab">Costeo y existencia</a></li>
        <li role="presentation"><a href="#impuestos" aria-controls="impuestos" role="tab" data-toggle="tab">Impuestos y Retenciones Generales</a></li>
        <li role="presentation"><a href="#notificaciones" aria-controls="notificaciones" role="tab" data-toggle="tab">Notificaciones</a></li>
        <li role="presentation"><a href="#otros" aria-controls="otros" role="tab" data-toggle="tab">Otros</a></li>
      </ul>

      <!-- Tab panes -->
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="ejercicios">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Seleccione el ejercicio actual</h3>
              </div>
              <div class="panel-body">
                <table class="table table-hover">
                    <tr><th class="col-xs-12 col-md-2">Actual</th><th class="col-xs-12 col-md-2">Ejercicio</th><th class="col-xs-12 col-md-4">Cerrar</th></tr>
                    <?php
                        while($ejer = $ejercicios->fetch_object())
                        {
                            $seleccionado = '';
                            $ejerNombre = "<b><a href='javascript:actual($ejer->id)'>$ejer->nombre</a></b>";
                            if(intval($ejer->id) == intval($actual))
                            {
                                $seleccionado = "<span class='label label-success'>Ejercicio Actual</span>";
                                $ejerNombre = "<b><a href='#'>$ejer->nombre</a></b>";
                            }

                            $cerrado = "<button class='btn btn-default' onclick='cerrar($ejer->id,$ejer->nombre)'>Cerrar <span class='glyphicon glyphicon-lock'></span></button>";
                            if(intval($ejer->cerrado))
                                $cerrado = 'Cerrado';
                            echo "<tr><td class='col-xs-12 col-md-2'>$seleccionado</td><td class='col-xs-12 col-md-2' title='seleccionar como actual'>$ejerNombre</td><td class='col-xs-12 col-md-4'>$cerrado</td></tr>";
                        }
                    ?>
                </table>
              </div>
            </div>
            <?php
            $checkedCer = '';
            if(intval($permitir_cerrados))
              $checkedCer = 'checked';
            ?>
            Permitir capturar en ejercicios cerrados: <input type='checkbox' id='ej_cer' onclick='ej_cer()' value='1' <?php echo $checkedCer; ?>> 
        </div>
        <div role="tabpanel" class="tab-pane fade" id="periodos">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Seleccione el periodo actual</h3>
              </div>
              <div class="panel-body">
                <table class="table table-hover">
                    <tr><th class="col-xs-12 col-md-2">Actual</th><th class="col-xs-12 col-md-2">Periodo</th><th class="col-xs-12 col-md-4">Cerrar</th></tr>
                    <?php
                        while($per = $periodos->fetch_object())
                        {
                            $seleccionado = '';
                            $perNombre = "<b><a href='javascript:actualPeriodo($per->num_mes)'>$per->num_mes / ".$this->nombre_mes($per->num_mes)."</a></b>";
                            if(intval($per->num_mes) == intval($actualPeriodo))
                            {
                                $seleccionado = "<span class='label label-success'>Periodo Actual</span>";
                                $perNombre = "<b><a href='#'>$per->num_mes / ".$this->nombre_mes($per->num_mes)."</a></b>";
                            }

                            $cerrado = "<button class='btn btn-default' onclick='cerrarPeriodo($per->id,\"".$this->nombre_mes($per->num_mes)."\")'>Cerrar <span class='glyphicon glyphicon-lock'></span></button>";
                            if(intval($per->cerrado))
                                $cerrado = 'Cerrado';
                            echo "<tr><td class='col-xs-12 col-md-2'>$seleccionado</td><td class='col-xs-12 col-md-2' title='seleccionar como actual'>$perNombre</td><td class='col-xs-12 col-md-4'>$cerrado</td></tr>";
                        }
                    ?>
                </table>
              </div>
            </div>
            <?php
                $checked = '';
                if(intval($periodosAbiertos))
                    $checked = "checked";
                $checkedEx = '';
                if(intval($existencias))
                    $checkedEx = "checked";

                $checkedMod = '';
                if(intval($mod_costo_compras))
                    $checkedMod = "checked";

                $checkedPol = '';
                if(intval($pol_aut))
                    $checkedPol = "checked";  
            ?>
            Manejar periodos abiertos: <input type='checkbox' id='abiertos' onclick='abiertos()' value='1' <?php echo $checked; ?>>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="metcosteo">
             <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Seleccione las opciones de costeo y existencias.</h3>
              </div>
              <div class="panel-body">
                <div class="col-xs-12 col-md-4 col-md-offset-4">
                    Metodo de costeo.
                    <input type='hidden' id='costeo_hidden' value="<?php echo $infoConf['id_costeo_general']; ?>">
                    <input type='hidden' id='id_costeo_salida_hidden' value="<?php echo $idcosteosalida; ?>">
                    <select id='costeo' class="form-control">
                        <option value='0'>Ninguno</option>
                    <?php
                    while($lc = $lista_costeo->fetch_object())
                    {
                        echo "<option value='$lc->id'>$lc->nombre</option>";
                    }
                    ?>
                </select>
                </div>  
              </div>
              <div class='panel-body'>
                <div class="col-xs-12 col-md-4 col-md-offset-4">
                    Surtir productos sin existencia?<input type='checkbox' id='existencia' value='1' onclick='existencia_combo()' <?php echo $checkedEx ?>>
                    <select id='costeo_existencia' class="form-control" id='costo_existencia'>
                        <option value='0'>Costo cero</option>
                        <option value='1'>Costo Promedio</option>
                        <option value='3'>Ultimo Costo</option>
                    </select>
                </div>    
              </div>
               <div class='panel-body'>
                <div class="col-xs-12 col-md-4 col-md-offset-4">
                    Modificar Costo de Compras?<input type='checkbox' id='mod_costo_compras' value='1' <?php echo $checkedMod ?>>
                </div>    
              </div>
              <div class='panel-body'>
                <div class="col-xs-12 col-md-4 col-md-offset-4">
                    <center><button class='btn btn-default' onclick='guardar(1)' id='btn-1'>Guardar <span class='glyphicon glyphicon-ok'></span></button></center>
                </div>    
              </div>
            </div>
        </div>

        <div role="tabpanel" class="tab-pane fade" id="impuestos">
             <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Seleccione las opciones para impuestos y retenciones.</h3>
              </div>
              <div class="panel-body">
                <div class="col-xs-12 col-md-3 col-md-offset-5">
                    <hr />
                   <h3>Impuestos:</h3>
                   <hr />
                </div> 
              </div>
              <div class="panel-body">
                <div class="col-xs-12 col-md-1 col-md-offset-5">
                    IVA.
                </div>
                <div class="col-xs-12 col-md-2">
                    <div class='input-group'>
                        <input type='hidden' id='iva_hidden' value='<?php echo $infoConf['iva'] ?>'>
                    <select id='iva' class="form-control">
                        <option value='0'>Ninguno</option>
                        <option value='1'>16</option>
                        <option value='2'>0</option>
                        <option value='3'>Exento</option>
                    </select>
                    <span class="input-group-addon">%</span>
                    </div>
                </div>  
              </div>
              <div class="panel-body">
                <div class="col-xs-12 col-md-1 col-md-offset-5">
                    IEPS.
                </div>
                <div class="col-xs-12 col-md-2">
                    <div class='input-group'>
                      <?php
                      $checkedIeps = "";
                      if(intval($infoConf['ieps']))
                        $checkedIeps = "checked";
                      ?>
                        <input type='checkbox' id='ieps' value='1' <?php echo $checkedIeps; ?>>
                        
                    </div>
                </div>  
              </div>
              <div class="panel-body">
                <div class="col-xs-12 col-md-1 col-md-offset-5">
                    ISH.
                </div>
                <div class="col-xs-12 col-md-2">
                    <div class='input-group'>
                        <?php
                      $checkedIsh = "";
                      if(intval($infoConf['ish']))
                        $checkedIsh = "checked";
                      ?>
                        <input type='checkbox' id='ish' value='1' <?php echo $checkedIsh; ?>>
                    </div>
                </div>  
              </div>
              <div class="panel-body">
                <div class="col-xs-12 col-md-3 col-md-offset-5">
                    <hr />
                   <h3>Retenciones:</h3>
                   <hr />
                </div> 
              </div>
              <div class="panel-body">
                <div class="col-xs-12 col-md-1 col-md-offset-5">
                    IVA.
                </div>
                <div class="col-xs-12 col-md-2">
                    <div class='input-group'>
                      <input type='hidden' id='ret_iva_hidden' value='<?php echo $infoConf['ret_iva'] ?>'>
                         <select id='ret_iva' class="form-control">
                         <option value='0'>Ninguno</option>
                        <option value='4'>10.667</option>
                        <option value='5'>4</option>
                    </select>
                    <span class="input-group-addon">%</span>
                    </div>
                </div>  
              </div>
              <div class="panel-body">
                <div class="col-xs-12 col-md-1 col-md-offset-5">
                    ISR.
                </div>
                <div class="col-xs-12 col-md-2">
                    <div class='input-group'>
                        <?php
                      $checkedRetIsr = "";
                      if(intval($infoConf['ret_isr']))
                        $checkedRetIsr = "checked";
                      ?>
                        <input type='checkbox' id='ret_isr' value='1' <?php echo $checkedRetIsr; ?>>
                    </div>
                </div>  
              </div>
              <div class="panel-body">
                <div class="col-xs-12 col-md-2 col-md-offset-6">
                    <button id='guarda_imp' class='btn btn-default' onclick='guardar(2)'>Guardar <span class="glyphicon glyphicon-ok"></span></button>
                </div> 
              </div>
            </div>
        </div>

        <div role="tabpanel" class="tab-pane fade" id="notificaciones">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Notificar (Mandar un email) por cada recepcion y requisicion.</h3>
              </div>
              <div class="panel-body">
                  <div class="col-xs-12 col-md-2 col-md-offset-4">Compras: </div> <div id='col-xs-12 col-md-2'><input type='text' id='not_compras' value='<?php echo $not_compras; ?>'></div>
                  <div class="col-xs-12 col-md-2 col-md-offset-4">Ventas: </div> <div id='col-xs-12 col-md-2'><input type='text' id='not_ventas' value='<?php echo $not_ventas; ?>'></div>
                  <div class="col-xs-12 col-md-2 col-md-offset-4">Cortes de Caja: </div> <div id='col-xs-12 col-md-2'><input type='text' id='not_cortes' value='<?php echo $not_cortes; ?>'></div>
                  <div class="col-xs-12 col-md-5 col-md-offset-4"><label style='font-size:10px;color:gray;'>*Si dejas los campos vacios no se enviará notificaciones, para incluir varias direcciones de email agregar comas (,) ejemplo alguien@empresa.com,otro@espresa.com</label></div>
                  <div class="col-xs-12 col-md-5 col-md-offset-5" style='margin-top:20px;'><button id='guarda_not' class='btn btn-default' onclick='guardar(3)'>Guardar <span class="glyphicon glyphicon-ok"></span></button>
                  </div>
              </div> 
            </div>
        </div>
        

        <div role="tabpanel" class="tab-pane fade" id="otros">
             <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Seleccione las opciones.</h3>
              </div>
              <div class="panel-body">
                <div class="col-xs-12 col-md-3 col-md-offset-4">
                   
                   <center><button id='reiniciar' onclick='reiniciar()' class='btn btn-danger'>Reiniciar Sistema</button></center>
                   
                </div> 
              </div>
          <hr />
              <div class="row">
                  <div class="col-xs-12 col-md-2 col-md-offset-4">Dias para cancelar factura: </div> <div id='col-xs-12 col-md-6'><input type='text' id='dias_canc' value='<?php echo $dias_canc; ?>'></div> <br>
              <div class="row">
                  <div class="col-xs-12 col-md-2 col-md-offset-4">&nbsp;Dias para emitir factura: </div> <div id='col-xs-12 col-md-6'><input type='text' id='dias_emit' value='<?php echo $dias_emit; ?>'></div>
              </div>
              <div class="row">
                  <div class="col-xs-12 col-md-5 col-md-offset-4"><label style='font-size:10px;color:gray;'>*Si dejas los campos en cero se tomará soló los dias restantes del mes actual</label></div>
              </div>
              <hr />
              <!--<div class="row">
                <div class="col-xs-12 col-md-5 col-md-offset-4">
                    <input type='checkbox' id='pol_aut' onclick='pol_aut()' value='1' <?php echo $checkedPol; ?>> *Crear polizas automaticamente?
                    <br /><label style='font-size:10px;color:gray;'>*Crear un boton en el modulo de compras y ventas que genere las polizas <br />si se cuenta con el modulo Acontia.</label>
                </div> 
              </div>-->
              <div class="row">
                  <div class="col-xs-12 col-md-3 col-md-offset-4" style='margin-top:20px;'><center><button id='guarda_not' class='btn btn-default' onclick='guardar(4)'>Guardar <span class="glyphicon glyphicon-ok"></span></button></center>
                  </div>
              </div> 
            </div>
        </div>

      </div>
    </div>
</div>
<script language='javascript' src='js/configuracion.js'></script>
<script language='javascript' src='https://transtatic.com/js/numericInput.min.js'></script>