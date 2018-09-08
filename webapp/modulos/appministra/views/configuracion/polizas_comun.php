              <div class="panel-body">
                <div class='row'>
                  <div class='col-xs-12 col-md-4'>
                  <?php
                  $corte = "";
                  if(intval($tipo) == 1 || intval($tipo) == 10)
                  {
                  ?>
                    <div class='col-xs-12 col-md-9'>Generacion automatica Ventas</div>
                    <div class='col-xs-12 col-md-3'><input type='radio' id='aut_man_<?php echo $tipo; ?>_2' value='2' name='aut_man_<?php echo $tipo; ?>' onclick='aut_man(<?php echo $tipo; ?>)'></div>
                  <?php
                    $corte = "Corte de Caja";
                  }
                  ?>
                    <div class='col-xs-12 col-md-9'>Generacion automatica  <?php echo $corte;?></div>
                    <div class='col-xs-12 col-md-3'><input type='radio' id='aut_man_<?php echo $tipo; ?>_1' value='1' name='aut_man_<?php echo $tipo; ?>' onclick='aut_man(<?php echo $tipo; ?>)' checked></div>
                    <?php
                    if(intval($tipo) != 10)
                    {
                    ?>
                    <div class='col-xs-12 col-md-9'>Generacion manual</div>
                    <div class='col-xs-12 col-md-3'><input type='radio' id='aut_man_<?php echo $tipo; ?>_0' value='0' name='aut_man_<?php echo $tipo; ?>' onclick='aut_man(<?php echo $tipo; ?>)'></div>
                  <?php } ?>
                  </div>
                  <div class='col-xs-12 col-md-5'>
                  <span id='mensaje_<?php echo $tipo; ?>'></span>
                    <input type='hidden' value='1' id='por_mov_<?php echo $tipo; ?>'><input type='hidden' id='dias_<?php echo $tipo; ?>' class='form-control' value='1'>
                  </div>
                  
                  
                </div>
                <div class='row'>
                  <div class='col-xs-12 col-md-6'>
                    <div class='col-xs-12 col-md-12'>Tipo de Poliza</div>
                    <div class='col-xs-12 col-md-12'>
                        <select id='tipo_poliza_<?php echo $tipo; ?>' class='form-control'>
                            <option value='1'>Ingresos</option>
                            <option value='2'>Egresos</option>
                            <option value='3'>Diario</option>
                            <option value='4'>Orden</option>
                          </select>
                      </div>
                  </div>
                  <div class='col-xs-12 col-md-6'>
                    <div class='col-xs-12 col-md-12'>Tipo de Gasto</div>
                    <div class='col-xs-12 col-md-12'>
                    <?php
                    if(intval($tipo) != 2)
                      $disabled = "disabled";
                    else
                      $disabled = "";

                    ?>
                      <select id='gasto_<?php echo $tipo; ?>' class='form-control gastos' <?php echo $disabled; ?>>
                      </select>
                    </div>
                  </div>
                </div>
                  <div class='row'>
                  <div class='col-xs-12 col-md-12'>
                    <div class='col-xs-12 col-md-12'>Concepto</div>
                    <div class='col-xs-12 col-md-9'>
                      <input type='text' id='concepto_<?php echo $tipo; ?>' class='form-control'>
                    </div>
                  </div>
                </div>
                <div class='row'>
                <div class='col-xs-12 col-md-12'>
                  <table>
                    <tr><td colspan='5'><button onclick='abrir_cuenta(1,<?php echo $tipo; ?>)' class='btn btn-primary' id='agregar_cuenta_btn'>Agregar Cuenta</button></td></tr>
                  </table>
                </div>
                  <div class='col-xs-12 col-md-12'>
                    <div class='table-responsive'>  
                      <table id='cuentas_<?php echo $tipo; ?>' class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                          <tr><th>Codigo</th><th>Cuenta</th><th>Cargo</th><th>Abono</th><th>Vinculacion</th><th>Modificar</th><th>Eliminar</th></tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                  <div class='col-xs-12 col-md-12'>
                    <button id='guardar_poliza_<?php echo $tipo; ?>' onclick="guardar_poliza(<?php echo $tipo; ?>)" class='btn btn-default'>Guardar Poliza</button>
                  </div>
                </div>
              </div>