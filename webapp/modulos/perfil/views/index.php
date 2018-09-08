<!doctype html>
<html lang="es_LA">
   <head>
      <!-- Bootstrap -->
      <link href="../../libraries/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
      <!-- Font Awesome -->
      <link href="../../libraries/font-awesome-4.7.0/css/font-awesome.css" rel="stylesheet">
      <!-- SweetAlert -->
      <link href="../../libraries/sweetalert/css/sweetalert.css" rel="stylesheet">
      <!-- Datatables -->
      <link href="../../libraries/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
      <link href="../../libraries/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
      <link href="../../libraries/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
      <link href="../../libraries/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
      <link href="../../libraries/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
      <!-- Datepicker -->
      <link href="../../libraries/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
      <link href="css/index.css" rel="stylesheet">
   </head> 
   <body>

      <div class="container-fluid">
         <div class="col-md-12">
            <div class="row ">
               <div class="col-md-12 fondo">
                  <div class="col-md-6">
                     <div class="panel-group margintop">
                        <div class="panel panel-default">
                           <div class="panel-heading">
                              <h4 class="panel-title">
                                 <a data-toggle="collapse" href="#info-usuario">Información general</a>
                              </h4>
                           </div>
                           <div id="info-usuario" class="panel-collapse collapse in">
                              <div class="panel-body">
                                 <form class="form-horizontal" id="frm">
                                    <div class="row">
                                       <div class="col-md-6">
                                          <label>Razón social</label>
                                          <input  type="text" id="razon" name="razon" class="form-control alturaestandar requerido" placeholder="Razón social" onfocus="this.blur()" readonly="readonly" />
                                       </div>
                                       <div class="col-md-6">
                                          <label>RFC</label>
                                          <input  type="text" id="rfc" name="rfc" class="form-control alturaestandar requerido" placeholder="RFC" onfocus="this.blur()" readonly="readonly" />
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-md-6 margintop">
                                          <label>Usuario</label>
                                          <input type="text" id="usuario" name="usuario" class="form-control alturaestandar" placeholder="Usuario" onfocus="this.blur()" readonly="readonly" />
                                       </div>
                                       <div class="col-md-6 margintop">
                                          <label>Contraseña</label>
                                          <div class="input-group">
                                             <span class="input-group-btn">
                                                <button id="cambiar_contrasena" class="btn btn-default" type="button"><li class="fa fa-refresh"></li></button>
                                             </span>
                                             <input type="password" id="contrasena" name="contrasena" class="form-control alturaestandar requerido" placeholder="Contraseña" />
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-md-6 margintop">
                                          <label>Correo</label>
                                          <input type="text" id="correo" name="correo" class="form-control alturaestandar" placeholder="Correo" onfocus="this.blur()" readonly="readonly" />
                                       </div>
                                    </div>
                                 </form>
                              </div>
                           </div>
                        </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                     <div class="panel-group margintop">
                        <div class="panel panel-default">
                           <div class="panel-heading">
                              <h4 class="panel-title">
                                 <div class="row">
                                    <div class="col-md-10">
                                       <a data-toggle="collapse" href="#info-productos">Productos</a>
                                    </div>
                                    <div class="col-md-2 hidden">
                                       <button id="btn_pagar_suscripcion" type="button" class="btn btn-block btn-primary">Pagar</button>
                                    </div>
                                 </div>
                              </h4>
                           </div>
                           <div id="info-productos" class="panel-collapse collapse in">
                              <div class="panel-body">
                                 <div id="producto_base" class="panel-group producto hidden">
                                    <div class="panel panel-default">
                                       <div class="panel-heading">
                                          <h4 class="panel-title">
                                             <div class="row">
                                                <div class="col-sm-6">
                                                   <a id="titulo_producto_base" data-toggle="collapse" href="#info_producto_base"></a>
                                                </div>
                                                <div class="col-sm-6">
                                                   <div class="progress">
                                                      <div id="progreso_producto_base" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </h4>
                                       </div>
                                       <div id="info_producto_base" class="panel-collapse collapse">
                                          <div class="panel-body">
                                             <div class="row">
                                                <div class="col-sm-6">
                                                   <label>Tipo de suscripción:</label>
                                                   <br><span id="suscripcion_producto_base"></span>
                                                </div>
                                                <div class="col-sm-6">
                                                   <label>Fecha de vencimiento:</label>
                                                   <br><span id="vencimiento_producto_base"></span>
                                                </div>
                                             </div>
                                             <div class="row">
                                                <div class="col-sm-6">
                                                   <label>Precio por suscripción:</label>
                                                   <br><span id="precio_producto_base"></span>
                                                </div>
                                                <div class="col-sm-3">
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <section id="listado_productos"></section>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="row ">
               <div class="col-md-12 fondo">
                  <div class="col-md-6">
                     <div class="panel-group margintop">
                        <div class="panel panel-default">
                           <div class="panel-heading">
                              <h4 class="panel-title">
                                 <a data-toggle="collapse" href="#info-mailing">Configuración de mailing</a>
                              </h4>
                           </div>
                           <div id="info-mailing" class="panel-collapse collapse in">
                              <div class="panel-body">
                                 <form class="form-horizontal" id="frm_mailing">
                                    <div class="row">
                                       <div class="col-md-6">
                                          <label>Correo electrónico</label>
                                          <input  type="text" id="mailing_correo" name="mailing_correo" class="form-control alturaestandar requerido" placeholder="Correo electrónico" />
                                       </div>
                                       <div class="col-md-6">
                                          <label>Contraseña</label>
                                          <input  type="password" id="mailing_contrasena" name="mailing_contrasena" class="form-control alturaestandar requerido" placeholder="Contraseña" />
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-md-6 margintop">
                                          <div class="row">
                                             <div class="col-md-10">
                                                <label>Proveedor de correo electrónico</label>
                                                <select id="mailing_tipo" name="mailing_tipo" class="form-control alturaestandar requerido selector">
                                                   <option value="0">Selecciona un proveedor</option>
                                                   <option value="1">Gmail</option>
                                                   <option value="2">Microsoft - Hotmail / Live</option>
                                                   <option value="3">Microsoft - Outlook</option>
                                                   <option value="4">Yahoo</option>
                                                   <option value="5">Otro</option>
                                                </select>
                                             </div>
                                             <div class="col-md-2 hidden mailing_info">
                                                <a href="https://support.google.com/a/answer/6260879" target="_blank"><li class="fa fa-info-circle" data-toggle="tooltip" title="Para activar el envió de emails en tu cuenta, da clic en icono de información o visita el siguiente enlace: https://support.google.com/a/answer/6260879" data-placement="right"></li></a>
                                             </div>
                                          </div>
                                          <div class="row">
                                             <div class="col-md-12">
                                                <label>&nbsp;</label>
                                                <button id="btn_config_mailing" type="button" class="btn btn-primary btn-block">Guardar</button>
                                             </div>
                                          </div>
                                       </div>
                                       <div id="mailing_config" class="col-md-6 margintop hidden">
                                          <div class="row">
                                             <div class="col-md-12">
                                                <label>¿Requiere autenticación?</label>
                                                <select id="mailing_conf_activo" name="mailing_conf_activo" class="form-control alturaestandar selector">
                                                   <option value="1">Si</option>
                                                   <option value="2">No</option>
                                                </select>
                                             </div>
                                          </div>
                                          <div class="row">
                                             <div class="col-md-12">
                                                <label>Método de seguridad</label>
                                                <select id="mailing_conf_metodo" name="mailing_conf_metodo" class="form-control alturaestandar">
                                                   <option value="ssl">SSL</option>
                                                   <option value="tls">TLS</option>
                                                </select>
                                             </div>
                                          </div>
                                          <div class="row">
                                             <div class="col-md-12">
                                                <label>Servidor SMTP</label>
                                                <input type="text" id="mailing_conf_servidor" name="mailing_conf_servidor" class="form-control alturaestandar" placeholder="URL de servidor SMTP">
                                             </div>
                                          </div>
                                          <div class="row">
                                             <div class="col-md-12">
                                                <label>Puerto de salida SMTP</label>
                                                <input type="text" id="mailing_conf_puerto" name="mailing_conf_puerto" class="form-control alturaestandar" placeholder="Puerto de salida SMTP">
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </form>
                              </div>
                           </div>
                        </div>
                    </div>
                  </div>
               </div>
            </div>
            <div class="row hidden">
               <div class="col-md-12 fondo">
                  <div class="col-md-12">
                     <div class="panel-group margintop">
                        <div class="panel panel-default">
                           <div class="panel-heading">
                              <h4 class="panel-title">
                                 <a data-toggle="collapse" href="#info-pagos">Pagos</a>
                              </h4>
                           </div>
                           <div id="info-pagos" class="panel-collapse collapse in">
                              <div class="panel-body">
                                 <div class="row">
                                    <div class="col-sm-6">
                                       <div class="panel-group">
                                          <div class="panel panel-default">
                                             <div class="panel-heading">
                                                <h4 class="panel-title">
                                                   <a data-toggle="collapse" href="#info-tarjeta">Tarjetas de crédito / debito</a>
                                                </h4>
                                             </div>
                                             <div id="info-tarjeta" class="panel-collapse collapse in">
                                                <div class="panel-body">
                                                   <div class="row">
                                                      <div class="col-md-12">
                                                         <div class="panel-group">
                                                            <div class="panel panel-default">
                                                               <div class="panel-heading">
                                                                  <h4 class="panel-title">
                                                                     <a data-toggle="collapse" href="#info-listado">Tus tarjetas</a>
                                                                  </h4>
                                                               </div>
                                                               <div id="info-listado" class="panel-collapse collapse in">
                                                                  <div class="panel-body">
                                                                     <div class="row">
                                                                        <div id="tarjeta_base" class="col-md-10 col-md-offset-1 tarjeta hidden">
                                                                           <label><strong id="numero_base"></strong></label>
                                                                           <li id="limpiar_base" class="fa fa-trash apuntador txt-rojo pull-right" data-toggle="tooltip" title="Eliminar tarjeta" data-placement="right"></li>
                                                                           <li id="default_base" class="fa fa-thumbs-o-up apuntador txt-verde pull-right" data-toggle="tooltip" title="Seleccionar como tarjeta predefinida" data-placement="right"></li>
                                                                        </div>
                                                                     </div>
                                                                     <section id="listado_tarjetas"></section>
                                                                  </div>
                                                               </div>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-md-12">
                                                         <div class="panel-group">
                                                            <div class="panel panel-default">
                                                               <div class="panel-heading">
                                                                  <h4 class="panel-title">
                                                                     <a data-toggle="collapse" href="#info-agregar">Agregar tarjeta</a>
                                                                  </h4>
                                                               </div>
                                                               <div id="info-agregar" class="panel-collapse collapse">
                                                                  <div class="panel-body">
                                                                     <form id="frm_tarjeta">
                                                                        <div class="row">
                                                                           <div class="col-md-12">
                                                                              <label>Nombre del tarjetahabiente:</label>
                                                                              <input type="text" id="tarjetahabiente" name="tarjetahabiente" placeholder="Nombre del tarjetahabiente" class="form-control requerido">
                                                                           </div>
                                                                        </div>
                                                                        <div class="row">
                                                                           <div class="col-md-7">
                                                                              <label>Numero de tarjeta:</label>
                                                                              <input type="hidden" name="banco" id="banco" value="">
                                                                              <input type="password" id="numero" name="numero" placeholder="Numero de tarjeta" class="form-control requerido">
                                                                           </div>
                                                                           <div class="col-md-5">
                                                                              <label>CVV:</label>
                                                                              <input type="password" id="cvv" name="cvv" placeholder="CVV" class="form-control requerido">
                                                                           </div>
                                                                        </div>
                                                                        <div class="row">
                                                                           <div class="col-md-6">
                                                                              <label>Año de vencimiento:</label>
                                                                              <input type="text" id="ano" name="ano" placeholder="Año" class="form-control requerido">
                                                                           </div>
                                                                           <div class="col-md-6">
                                                                              <label>Mes de vencimiento:</label>
                                                                              <input type="text" id="mes" name="mes" placeholder="Mes" class="form-control requerido">
                                                                           </div>
                                                                        </div>
                                                                     </form>
                                                                     <br><p>Se te hará un cargo de 10 pesos para validar la tarjeta. El cargo será devuelto en un plazo de 24 - 48 horas.</p><br>
                                                                     <div class="row">
                                                                        <div class="col-md-6 text-center">
                                                                           <button id="tarjeta_limpiar" class="btn btn-danger btn-block">Cancelar</button>
                                                                        </div>
                                                                        <div class="col-md-6 text-center">
                                                                           <button id="tarjeta_agregar" class="btn btn-success btn-block">Agregar</button>
                                                                        </div>
                                                                     </div>
                                                                  </div>
                                                               </div>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-sm-6">
                                       <div class="panel-group">
                                          <div class="panel panel-default">
                                             <div class="panel-heading">
                                                <h4 class="panel-title">
                                                   <a data-toggle="collapse" href="#info-configuracion">Configuración de pagos</a>
                                                </h4>
                                             </div>
                                             <div id="info-configuracion" class="panel-collapse collapse in">
                                                <div class="panel-body">
                                                   <div class="row">
                                                      <div class="col-md-12">
                                                         <label>Selecciona el método de pago predefinido <span class="fa fa-info-circle" data-toggle="tooltip" title="El método de pago que selecciones será con el que se intenten realizar los cargos automáticos" data-placement="top"></span> :</label><br>
                                                         <label class="radio-inline">
                                                            <input type="radio" name="metodo_pago" id="metodo_pago_tarjeta" value="option1"> Tarjeta de Crédito / Debito
                                                         </label>
                                                         <label class="radio-inline">
                                                            <input type="radio" name="metodo_pago" id="metodo_pago_paypal" value="option2"> PayPal
                                                         </label>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-sm-12">
                                       <div class="panel-group">
                                          <div class="panel panel-default">
                                             <div class="panel-heading">
                                                <h4 class="panel-title">
                                                   <a data-toggle="collapse" href="#info-historico">Historial de pagos</a>
                                                </h4>
                                             </div>
                                             <div id="info-historico" class="panel-collapse collapse in">
                                                <div class="panel-body">
                                                   <table id="data_table" class="table table-bordered table-striped">
                                                      <thead>
                                                         <tr>
                                                            <th>Fecha</th>
                                                            <th>Referencia</th>
                                                            <th>Método</th>
                                                            <th>Monto</th>
                                                            <th>Productos</th>
                                                         </tr>
                                                      </thead>
                                                      <tbody id="data_table_body">
                                                      </tbody>
                                                   </table>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div id="modal-cambiar-contrasena" class="modal fade" tabindex="-1" role="dialog">
         <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Cambiar contraseña</h4>
               </div>
               <div class="modal-body">
                  <form id="frm-cambio-contrasena">
                     <div class="row">
                        <div class="col-md-12">
                           <label>Contraseña actual:</label>
                           <input type="password" id="contrasena_actual" name="contrasena_actual" class="form-control requerido" placeholder="Actual">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-12">
                           <label>Contraseña nueva:</label>
                           <input type="password" id="contrasena_nueva" name="contrasena_nueva" class="form-control requerido" placeholder="Nueva">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-12">
                           <label>Repetir contraseña:</label>
                           <input type="password" id="contrasena_repetir" name="contrasena_repetir" class="form-control requerido" placeholder="Repetir">
                        </div>
                     </div>
                  </form>
               </div>
               <div class="modal-footer">
                  <button id="guardar_contrasena" type="button" class="btn btn-primary btn-block">Guardar</button>
               </div>
            </div>
         </div>
      </div>

      <div id="modal_complemento_pago_productos" class="modal fade" tabindex="-1" role="dialog">
         <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Productos pagados</h4>
               </div>
               <div class="modal-body">
                  <table id="data_table_pago_productos" class="table table-striped table-bordered">
                     <thead>
                        <tr>
                           <th>Producto</th>
                           <th>Version</th>
                           <th>Periodo</th>
                           <th>Monto</th>
                        </tr>
                     </thead>
                     <tbody id="data_table_body_pago_productos">
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>

      <div id="modal-pagar-suscripcion" class="modal fade" tabindex="-1" role="dialog">
         <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Productos pendientes de pago</h4>
               </div>
               <div class="modal-body">
                  <div class="row">
                     <div class="col-md-6">
                        <select id="tipo_pago" name="tipo_pago" class="form-control">
                           <option value="0">Selecciona un método de pago</option>
                           <option value="1">Tarjeta de Crédito / Debito</option>
                           <option value="2">PayPal</option>
                        </select>
                     </div>
                     <div class="col-md-6">
                        <button class="btn btn-success btn-block hidden form-control" id="btn_pago_paypal">Pagar con PayPal</button>
                        <div id="pago_tarjeta" class="row hidden">
                           <div class="col-md-8">
                              <select id="tarjeta_pago" class="form-control">
                                 <option value="0">Selecciona una tarjeta</option>
                              </select>
                           </div>
                           <div class="col-md-4">
                              <button id="btn_pago_tarjeta" class="btn btn-success btn-block form-control">Pagar</button>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="row margintop">
                     <div class="col-md-12">
                        <table id="data_table_pago_pendiente_productos" class="table table-striped table-bordered">
                           <thead>
                              <tr>
                                 <th>Producto</th>
                                 <th>Version</th>
                                 <th>Periodo</th>
                                 <th>Monto</th>
                              </tr>
                           </thead>
                           <tbody id="data_table_body_pago_pendiente_productos">
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <!-- jQuery -->
      <script src="../../libraries/jquery.min.js"></script>
      <!-- Bootstrap -->
      <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
      <!-- SweetAlert -->
      <script src="../../libraries/sweetalert/js/sweetalert.min.js"></script>
      <!-- Datatables -->
      <script src="../../libraries/datatables.net/js/jquery.dataTables.min.js"></script>
      <script src="../../libraries/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
      <script src="../../libraries/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
      <script src="../../libraries/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
      <!-- Datepicker -->
      <script src="../../libraries/bootstrap-datetimepicker/js/moment.js"></script>
      <script src="../../libraries/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
      <script src="../../libraries/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js"></script>
      <!-- CreditCardValidator -->
      <script src="../../libraries/credit-card-validator/js/jquery.creditcardvalidator.js"></script>
      <!-- Perfil -->
      <script src="js/general.js"></script>
      <script src="js/catalogos.js"></script>
      <script src="js/index.js"></script>
   </body>
</html>