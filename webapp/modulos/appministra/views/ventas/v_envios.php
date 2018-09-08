<head>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/datatablesboot.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker.min.css">
    <style>
        .p0{padding:0;}
        .glyphicon-refresh-animate {
    -animation: spin .7s infinite linear;
    -ms-animation: spin .7s infinite linear;
    -webkit-animation: spinw .7s infinite linear;
    -moz-animation: spinm .7s infinite linear;
}
@keyframes spin {
    from { transform: scale(1) rotate(0deg);}
    to { transform: scale(1) rotate(360deg);}
}
  
@-webkit-keyframes spinw {
    from { -webkit-transform: rotate(0deg);}
    to { -webkit-transform: rotate(360deg);}
}

@-moz-keyframes spinm {
    from { -moz-transform: rotate(0deg);}
    to { -moz-transform: rotate(360deg);}
}
    </style>
</head>

<div id="modal-agrega" class="modal sfade">
    <div class="modal-dialog">
        <div class="modal-content panel-default">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Sacar producto sin existencia</h4>
            </div>
            <div id="bodyespecialagrega" class="modal-body">
                <div class="row">
                    <div class="col-sm-12" style="padding-top:10px;">
                        <label class="col-sm-6 control-label text-left">Cantidad:</label>
                        <div class="col-sm-6">
                          <input id="cantnueva" type="text" value="0">
                        </div>
                    </div> 
                </div>
            </div>
            <div class="modal-footer">
                <button id="modal-agrega-uno" type="button" class="btn btn-default">Continuar</button> 
            </div>
        </div>
    </div> 
</div>

<div id="modal-nc" class="modal sfade">
    <div class="modal-dialog">
        <div class="modal-content panel-default">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Devolucion de productos</h4>
            </div>
            <div id="bodyespecialnc" class="modal-body">
                    <input id='reqnota' val='' type="hidden">
                    <div id="lalala">
                    <div id="segurodev" class="col-sm-12" style="padding:10px 0 10px 0;">
                        ¿Estas seguro de realizar la devolucion de los productos seleccionados?
                    </div>
                    <div class="row">
                    </div>
                    </div>
            </div>
            <div id="footernc" class="modal-footer">
                <button id="modal-nc-uno" type="button" class="btn btn-default">Continuar</button> 
                <button id="modal-nc-dos" type="button" class="btn btn-default">Cancelar</button>
            </div>
        </div>
    </div> 
</div>

<div id="modal-recep" class="modal sfade">
    <div id="hiddensProds">

    </div>
    <div class="modal-dialog">
        <div class="modal-content panel-default">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Recepcion de producto</h4>
            </div>
            <div id="bodyespecial" class="modal-body">

            </div>
            <div class="modal-footer">
                <button id="modal-recep-uno" type="button" class="btn btn-default">Continuar</button> 
                <button id="modal-recep-dos" type="button" class="btn btn-default">Cancelar</button>
            </div>
        </div>
    </div> 
</div> 

<div id="modal-recepc" class="modal sfade">
    <div id="hiddensProdsc">

    </div>
    <div class="modal-dialog">
        <div class="modal-content panel-default">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Devolucion de producto</h4>
            </div>
            <div id="bodyespecialc" class="modal-body">

            </div>
            <div class="modal-footer">
                <button id="modal-recepc-uno" type="button" class="btn btn-default">Continuar</button> 
                <button id="modal-recepc-dos" type="button" class="btn btn-default">Cancelar</button>
            </div>
        </div>
    </div> 
</div> 


<!-- CHRIS - COMMENTS
=============================*/
//Modales precargados
//Modal 1 y modal 2 
-->
<div id="modal-conf3" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Envio satisfactorio!</h4>
            </div>
            <div class="modal-body">
                <p>El envio fue guardada exitosamente.</p>
            </div>
        </div>
    </div> 
</div>

<div id="modal-cancel" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-default">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Cancelacion de factura</h4>
            </div>
            <div class="modal-body">
                <p>Cancelando factura, favor de esperar <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span></p>
            </div>
        </div>
    </div> 
</div> 

<div id="modal-acuse" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-default">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Acuse de cancelacion</h4>
            </div>
            <div class="modal-body">
                <table>
                <tr>
                    <td width="250px;"><b>Fecha y hora de solicitud:</b></td>
                    <td class="acusefecha">xxx</td>
                </tr>
                <tr>
                    <td><b>Fecha y hora de cancelacion:</b></td>
                    <td class="acusefecha">xxx</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td><b>RFC emisor:</b></td>
                    <td id="acuserfc">xxx</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td><b>Folio fiscal</b></td>
                    <td id="acusefolio">xxx</td>
                </tr>
                <tr>
                    <td><b>Estado CFDI</b></td>
                    <td id="estadofolio">Cancelado</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                
                </table>
            </div>
        </div>
    </div> 
</div> 

<div id="modal-conff" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-default">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Finalizar venta</h4>
            </div>
            <div class="modal-body">
                <div class="row">
<input type="hidden" id="hmprod" value="'+idProd+'">
                    <div class="col-sm-12" style="padding-top:10px;">
                        <label class="col-sm-6 control-label text-left">Tipo de venta:</label>
                        <label class="col-sm-6 control-label text-left"><span id="tipv" class="label label-primary" style="cursor:pointer;">Ticket</span></label>
                    </div>
                    <div class="col-sm-12" style="padding-top:10px;">
                        <label class="col-sm-6 control-label text-left">Total:</label>
                        <label id="ttt" class="col-sm-6 control-label text-left">Total:</label>
                    </div>
                    <div id="sitienef" class="col-sm-12" style="padding-top:10px;">
                        <label class="col-sm-6 control-label text-left">RFC:</label>
                        <div class="col-sm-6">
                            <select id="rfc_">
                                <option value="0">XAXX010101000 (Generico)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12" style="padding-top:10px;">
                        <label class="col-sm-6 control-label text-left">Forma de pago:</label>
                        <div class="col-sm-6">
                            <select id="fp_">
                                <?php foreach ($fp as $k => $v) { ?>
                                    <option value="<?php echo $v['idFormapago']; ?>"><?php echo $v['nombre']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <!--ch@-->
                    <div id="divnumpago" class="col-sm-12" style="padding-top:10px; display: none;">
                        <label id ="lbnumpago"class="col-sm-6 control-label text-left">Numero de cheque:</label>
                        <div class="col-sm-6">
                            <input type="text" id="numpago">                            
                        </div>
                    </div>
                    
                    
                    <div class="col-sm-12" style="padding-top:10px;">
                        <label class="col-sm-6 control-label text-left">Observaciones:</label>
                        <div class="col-sm-6">
                            <textarea id="txtobs" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div id="footvent" class="modal-footer">
                <button id="modal-btnconff-uno" type="button" class="btn btn-default">Finalizar</button> 
                <button id="modal-btnconff-dos" type="button" class="btn btn-default">Cancelar</button>
            </div>
        </div>
    </div> 
</div> 

<div id="modal-conf1" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-warning">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Atencion!</h4>
            </div>
            <div class="modal-body">
                <p>Tienes una orden de venta sin enviar, ¿Deseas continuar sin realizar el envio?</p>
            </div>
            <div class="modal-footer">
                <button id="modal-btnconf1-uno" type="button" class="btn btn-default">Continuar</button> 
                <button id="modal-btnconf1-dos" type="button" class="btn btn-default">Cancelar</button>
            </div>
        </div>
    </div> 
</div> 

<div id="modal-conf2" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-warning">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Atencion!</h4>
            </div>
            <div class="modal-body">
                <p>Tienes una orden de venta sin enviar, ¿Deseas continuar sin realizar el envio?</p>
            </div>
            <div class="modal-footer">
                <button id="modal-btnconf2-uno" type="button" class="btn btn-default">Continuar</button> 
                <button id="modal-btnconf2-dos" type="button" class="btn btn-default">Cancelar</button>
            </div>
        </div>
    </div> 
</div>
 <!--Fin modales precargados-->

<body>
    <br>
    <div class="container well" style="padding:25px;">
        <div class="row" style="padding-bottom:20px;">
            <div class="col-xs-12 col-md-12" style="padding:0px 0px 0px 3px;"><h3>Modulo Ventas</h3></div>
            <input type="hidden" id="ist" value="0">
            <input type="hidden" id="it" value="0">
            <input type="hidden" id="cadimps" value="0">
            <input type="hidden" id="ss" value="0">
        </div>
        <div class="row" style="margin-bottom:10px;">
            <button class="btn btn-default" type="button" onclick="listareq();" >Ordenes de venta autorizadas</button>
            <button class="btn btn-default" type="button" onclick="listarec(0);" >Ventas realizadas</button>
        </div>
        <div id="nreq_load" class="row" style="display:none;font-size:12px;padding:2px;">
            <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
        </div>
       
        <div id="listareq_load" class="row" style="display:none;font-size:12px;padding:2px;">
            <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
        </div>

        <div id="listareq" class="row" style="display:block;margin-top:20px;font-size:12px;display:none;">
            <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>No. Cot.</th>
                        <th>No. OV.</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Solicitante</th>
                        <th>Total</th>
                        <th>Tipo</th>
                        <th>Estatus</th>
                        <th>Envio</th>
                    </tr>
                </thead>
                
                <tbody>
                    <!--<tr>
                        <td><a style="cursor:pointer;">200</a></td>
                        <td>Fecha</td>
                        <td>Solicitante</td>
                        <td>Area</td>
                        <td>Tipo gasto</td>
                        <td>Importe</td>
                        <td><span class="label label-danger" style="cursor:pointer;">Urgente</span></td>
                        <td><span class="label label-success" style="cursor:pointer;">Activo</span></td>
                    </tr>
                    <tr>
                        <td><a style="cursor:pointer;">201</a></td>
                        <td>Fecha</td>
                        <td>Solicitante</td>
                        <td>Area</td>
                        <td>Tipo gasto</td>
                        <td>Importe</td>
                        <td><span class="label label-info" style="cursor:pointer;">Normal</span></td>
                        <td><span class="label label-success" style="cursor:pointer;">Activo</span></td>
                    </tr>-->
                </tbody>
            </table>
        </div>

        <div id="listarec" class="row" style="display:block;margin-top:20px;font-size:12px;display:none;">
            <table id="examplec" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>No. OV.</th>
                        <th>No. Envio</th>
                        <th>Fecha envio</th>
                        <th>Fecha factura</th>
                        <th>Importe</th>
                        <th>Estatus</th>
                        <th>Activo</th>
                        <th>Envio</th>
                        <th>Factura</th>

                    </tr>
                </thead>
                
                <tbody>
                    <!--<tr>
                        <td><a style="cursor:pointer;">200</a></td>
                        <td>Fecha</td>
                        <td>Solicitante</td>
                        <td>Area</td>
                        <td>Tipo gasto</td>
                        <td>Importe</td>
                        <td><span class="label label-danger" style="cursor:pointer;">Urgente</span></td>
                        <td><span class="label label-success" style="cursor:pointer;">Activo</span></td>
                    </tr>
                    <tr>
                        <td><a style="cursor:pointer;">201</a></td>
                        <td>Fecha</td>
                        <td>Solicitante</td>
                        <td>Area</td>
                        <td>Tipo gasto</td>
                        <td>Importe</td>
                        <td><span class="label label-info" style="cursor:pointer;">Normal</span></td>
                        <td><span class="label label-success" style="cursor:pointer;">Activo</span></td>
                    </tr>-->
                </tbody>
            </table>
        </div>


        <div id="nreq" class="row" style="display:none;">
            <div class="panel panel-default">
                <div id="ph"  class="panel-heading"><span opt="1" class="label label-primary" style="cursor:pointer;">Nueva orden de compra</span></div>
                <div class="panel-body" style="padding:15px 0 10px 0; font-size:12px;">
                    <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-2 control-label text-left">No. OV</label>
                        <div id="txt_nreq" class="col-sm-2" style="color:#ff0000;">
                        </div>
                        <div id="txt_nreq2" class="col-sm-2" style="color:#ff0000;display:none;">
                        </div>
                        <label class="col-sm-2 control-label text-left">Fecha</label>
                        <div id="fechahoy" class="col-sm-2">
                            <input style="height:30px;width:100%" id="date_hoy" type="text" class="form-control">
                        </div>
                        <label class="col-sm-2 control-label text-left">Fecha entrega</label>
                        <div class="col-sm-2 text-left">
                            <input style="height:30px;width:100%" id="date_entrega" type="text" class="form-control">
                        </div>
                    </div>
                    </div>
                    <div class="col-sm-12" style="padding-top:10px;">
                    <div class="form-group">
                        <label class="col-sm-2 control-label text-left">Normal</label>
                        <div class="col-sm-2" style="color:#ff0000;">
                            <input  type="radio" name="radiourgente" id="opciones_1" value="0" checked>
                        </div>
                        <label class="col-sm-2 control-label text-left">Urgente</label>
                        <div class="col-sm-2" style="color:#ff0000;">
                            <input  type="radio" name="radiourgente" id="opciones_2" value="1">
                        </div>
                        <label class="col-sm-2 control-label text-left">Inventariable</label>
                        <div class="col-sm-2 text-left" style="color:#ff0000;">
                            <input  type="checkbox" name="normal" id="checkbox" value="opcion_1" checked>
                        </div>
                        
                    </div>
                    </div>   
                    <div class="col-sm-12" style="padding-top:10px;">
                    <div class="form-group">
                        <label class="col-sm-2 control-label text-left">Solicitante</label>
                        <div class="col-sm-2" style="color:#ff0000;">
                            <select id="c_solicitante" style="width:100%;">
                                <option value="0">Seleccione</option>
                                <?php foreach ($empleados as $k => $v) { ?>
                                    <option value="<?php echo $v['idempleado']; ?>"><?php echo $v['nombre']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <label class="col-sm-2 control-label text-left">&nbsp;</label>
                        <div class="col-sm-2" style="color:#ff0000;">
                            &nbsp;
                        </div>
                        <label class="col-sm-2 control-label text-left">Tipo gasto</label>
                        <div class="col-sm-2 text-left" style="color:#ff0000;">
                            <select id="c_tipogasto"  style="width:100%;">
                                <option value="0">Seleccione</option>
                                <?php foreach ($tipoGasto as $k => $v) { ?>
                                    <option value="<?php echo $v['id']; ?>"><?php echo $v['nombreclasificador']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    </div>
                    <div class="col-sm-12" style="padding-top:10px;">
                    <div class="form-group">
                        <label class="col-sm-2 control-label text-left">Cliente</label>
                        <div class="col-sm-2" style="color:#ff0000;">
                            <select id="c_cliente"  style="width:100%;">
                                <option value="0">Seleccione</option>
                              <?php foreach ($clientes as $k => $v) { ?>
                                    <option value="<?php echo $v['id']; ?>"><?php echo $v['nombre']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <label class="col-sm-2 control-label text-left">Moneda</label>
                        <div class="col-sm-2" style="color:#ff0000;">
                            <select id="c_moneda"  style="width:100%;">
                                <option value="0">Seleccione</option>
                              <?php foreach ($monedas as $k => $v) { ?>
                                    <option value="<?php echo $v['coin_id']; ?>"><?php echo $v['codigo']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                        <input type="text" id="moneda_tc"  placeholder="Tipo de cambio" style="display:none;height:28px;">
                        </div>

                    </div>
                    </div>

                    <div class="col-sm-12" style="padding-top:15px;">
                        <div class="panel panel-default" style="border-radius:0px;">
                        <div class="panel-body" style="padding:15px 0 10px 0; font-size:12px;background-color:#f4f4f4;border:1px solid #fff;">
                            <div class="col-sm-12 p0">
                            <div class="form-group">
                                <label class="col-sm-2 control-label text-left">Producto</label>
                                <div class="col-sm-8" style="color:#ff0000;">
                                    <select id="c_productos" style="width:100%;">
                                        <option value="0">Seleccione</option>
                                        <?php foreach ($productos as $k => $v) { ?>
                                            <option value="<?php echo $v['id']; ?>-<?php echo $v['cmas']; ?>"><?php echo $v['nombre']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                
                                <div class="col-sm-2 text-left" >

                                    <button id="btn_addProd"  class="btn btn-default btn-sm btn-block"><span class="glyphicon glyphicon-plus"></span> Agregar producto</button>
                                </div>
                                
                            </div>
                            </div>
                        </div>
                        </div>

                    </div>
                    <div id="panel_tabla" class="col-sm-12" style="padding: 15px 37px 15px 31px; display:none;">
                        <table width="100%" id="tablaprods" class="table table-hover">
                        <thead>
                          <tr>
                           <th width="5%" align="left">Seg.</th>
                           <th width="10%" align="left">Codigo</th>
                           <th width="35%" align="left">Descripcion</th>
                           <th width="8%" align="left">$Unitario</th>
                           <th width="8%" align="left">Cant. Orden</th>
                           <th width="8%" align="left">Importe OV</th>
                           <th width="8%" align="left">Enviados</th>
                           <th  id="arecibir" width="8%" align="left">Cant. Enviada</th>
                           <th class="no-sort" width="1%" align="left">&nbsp;</th>
                           <th class="no-sort" width="1%" align="left">&nbsp;</th>
                           
                           <th id="coldevos" class="no-sort" width="1%" align="right">&nbsp;</th>
                          </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="9" style="text-align:right">&nbsp;</th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                        <tbody id="filasprods">
                        </tbody>
                      </table>
                    </div>

                    <div class="col-sm-12" style="display:none;">
                    <div class="form-group">
                        <label class="col-sm-2 control-label text-left">No. Factura</label>
                        <div class="col-sm-2">
                            <input id="nofactrec" type="text">
                        </div>
                        <label class="col-sm-2 control-label text-center">Fecha factura</label>
                        <label class="col-sm-2 control-label text-center">
                        <input style="height:30px;width:100%" id="date_recepcion" type="text" class="form-control">
                        </label>
                        <label class="col-sm-2 control-label text-right">$Factura</label>
                        <div class="col-sm-2">
                            <input id="impfactrec" type="text" style="width:100%">
                        </div>
                    </div>
                    </div>

                    <div class="col-sm-12" style="padding-top:10px;">
                    <div id="divfactenv" class="form-group">
                        <label class="col-sm-11 control-label text-right">Facturar envio:</label>
                        <form name='fac' id='fac' action='' method='post' enctype='multipart/form-data'>
                        <div class="col-sm-1 text-right">
                            
                            <input id="facturar" type='checkbox' style="cursor:pointer">
 
                            
                        </div>
                        </form>
                    </div>
                    </div>

                    <div class="col-sm-12" style="padding:30px;">
                        <div id="resultasoc" style="margin-top:10px;">
                            
                        </div>
                    </div>

                    <div class="col-sm-12" style="padding-top:10px;">
                    <div class="form-group">
                        <label class="col-sm-2 control-label text-left">Observaciones</label>
                        <div class="col-sm-10" style="color:#ff0000;">
                            <textarea class="form-control" rows="3" id="comment"></textarea>
                        </div>
                    </div>
                    </div>




                    <div class="col-sm-12" style="padding-top:10px;">
                    <div class="form-group">
                        <div id="btns" class="col-sm-12 text-right">
                            <!--
                            <button id="btn_savequit" class="btn btn-sm btn-info pull-center" type="button" style="height:28px;" >Guardar y salirdddd</button> 
                        -->
                            <!--<button  id="btn_authquit_p" activo="5" class="btn btn-sm btn-primary pull-center" type="button" style="height:28px;">Parcialmente enviado</button>-->
                            <button  id="btn_solacla" class="btn btn-sm btn-primary pull-center" type="button" style="height:28px;">Solicitar aclaracion</button>
                            <button  id="btn_authquit_ok" activo="4" class="btn btn-sm btn-success pull-center" type="button" style="height:28px;">Enviado ok</button>
                            <button  id="btn_devolucion" activo="5" class="col-sm-2 btn btn-sm btn-danger pull-right" type="button" style="height:28px;display:none;margin:0 0 0 4px;">Hacer devolucion</button>
                        </div>
                    </div>
                    </div>
                    
                    <div id="error_1"></div>

                    <div id="data_almacen" style="display:none;">
                        <select id="c_almacen" style="width:100px;">
                            <?php foreach ($arreglo as $k => $v) { ?>
                                <option value="<?php echo $v['id']; ?>"><?php echo $v['nombre']; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                 </div>
                 </div>             

        </div>
<!--
        <div id="nreq" class="row" style="display:none;">
            <div class="panel panel-default">
                <div class="panel-heading">Recepcion de orden de compra</div>
                <div class="panel-body" style="padding:15px 0 10px 0; font-size:12px;">
                    <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-2 control-label text-left">No. Requisicion</label>
                        <div id="txt_nreq" class="col-sm-2" style="color:#ff0000;">
                            178
                        </div>
                        <label class="col-sm-2 control-label text-left">Fecha</label>
                        <div class="col-sm-2">
                            09/02/2016
                        </div>
                        <label class="col-sm-2 control-label text-left">Fecha entrega</label>
                        <div class="col-sm-2 text-left">
                            09/02/2016
                        </div>
                    </div>
                    </div>
                    <div class="col-sm-12" style="padding-top:10px;">
                    <div class="form-group">
                        <label class="col-sm-2 control-label text-left">Normal</label>
                        <div class="col-sm-2" style="color:#ff0000;">
                            <input  type="radio" name="normal" id="opciones_1" value="opcion_1" checked>
                        </div>
                        <label class="col-sm-2 control-label text-left">Urgente</label>
                        <div class="col-sm-2" style="color:#ff0000;">
                            <input  type="radio" name="normal" id="opciones_1" value="opcion_1">
                        </div>
                        <label class="col-sm-2 control-label text-left">Inventariable</label>
                        <div class="col-sm-2 text-left" style="color:#ff0000;">
                            <input  type="checkbox" name="normal" id="opciones_1" value="opcion_1" checked>
                        </div>
                        
                    </div>
                    </div>   
                    <div class="col-sm-12" style="padding-top:10px;">
                    <div class="form-group">
                        <label class="col-sm-2 control-label text-left">Solicitante</label>
                        <div class="col-sm-2" style="color:#ff0000;">
                            <select id="c_solicitante">
                                <option value="0">Seleccione</option>
                                <?php foreach ($empleados as $k => $v) { ?>
                                    <option value="<?php echo $v['idempleado']; ?>"><?php echo $v['nombre']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <label class="col-sm-2 control-label text-left">Area</label>
                        <div class="col-sm-2" style="color:#ff0000;">
                            <input type="text" value="" disabled="disabledss">
                        </div>
                        <label class="col-sm-2 control-label text-left">Tipo gasto</label>
                        <div class="col-sm-2 text-left" style="color:#ff0000;">
                            <select id="c_tipogasto">
                                <option value="0">Seleccione</option>
                                <?php foreach ($tipoGasto as $k => $v) { ?>
                                    <option value="<?php echo $v['id']; ?>"><?php echo $v['nombreclasificador']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    </div>
                    <div class="col-sm-12" style="padding-top:10px;">
                    <div class="form-group">
                        <label class="col-sm-2 control-label text-left">Moneda</label>
                        <div class="col-sm-2" style="color:#ff0000;">
                            <select id="c_moneda">
                                <option value="0">Seleccione</option>
                              <?php foreach ($monedas as $k => $v) { ?>
                                    <option value="<?php echo $v['coin_id']; ?>"><?php echo $v['codigo']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                        <input type="text" id="moneda_tc" placeholder="Tipo de cambio" style="display:none;height:28px;">
                        </div>
                    </div>
                    </div>
                    <div class="col-sm-12" style="padding-top:15px;">
                        <div class="panel panel-default" style="border-radius:0px;">
                        <div class="panel-body" style="padding:15px 0 10px 0; font-size:12px;background-color:#f4f4f4;border:1px solid #fff;">
                            <div class="col-sm-12 p0">
                            <div class="form-group">
                                <label class="col-sm-2 control-label text-left">Proveedor</label>
                                <div class="col-sm-2" style="color:#ff0000;">
                                    <select id="c_proveedores">
                                        <option value="0">Seleccione</option>
                                        <?php foreach ($proveedores as $k => $v) { ?>
                                            <option value="<?php echo $v['idPrv']; ?>"><?php echo $v['razon_social']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                              
                                <label class="col-sm-2 control-label text-left">Producto</label>
                                
                                <div id="div_productos" class="col-sm-4" style="color:#ff0000;">
                                    <select id="c_productos" disabled="disabled">
                                        <option value="0">Seleccione</option>
                                    </select>
                                </div>
                                
                                <div class="col-sm-2 text-left" >
                                    <button id="btn_addProd" class="btn btn-sm btn-info" type="button" style="height:28px;">Agregar producto</button>
                                </div>
                                
                                
                            </div>
                            </div>
                        </div>
                        </div>

                    </div>

                    <div class="col-sm-12" style="padding:20px 30px 20px 30px;">
                        <table id="tablaprodsrecep" class="table table-hover">
                        <thead>
                          <tr>
                           <th width="5%" align="left">Segmento</th>
                           <th width="5%" align="left">Almacen</th>
                           <th width="10%" align="left">Codigo</th>
                           <th width="30%" align="left">Descripcion</th>
                           <th width="10%" align="left">Unidad</th>
                           <th width="10%" align="left">Proveedor</th>
                           <th width="10%" align="left">$Unitario</th>
                           <th width="10%" align="left">Cantidad</th>
                           <th width="10" align="left">Importe</th>
                           <th width="5%" align="right">Moneda</th>
      
                          </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="8" style="text-align:right">Total:</th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <tr>
                               <td>0</td>
                               <td>002</td>
                               <td>CXCXC</td>
                               <td>Coca cola 600 ml</td>
                               <td>Pieza</td>
                               <td>Coca SA de CV</td>
                               <td>6.70</td>
                               <td>4</td>
                               <td>45.06</td>
                               <td>MXN</td>
                            </tr>
                            <tr>
                               <td>0</td>
                               <td>001</td>
                               <td>CXCXC</td>
                               <td>Seven up 600 ml</td>
                               <td>Pieza</td>
                               <td>Coca SA de CV</td>
                               <td>6.70</td>
                               <td>4</td>
                               <td>45.06</td>
                               <td>MXN</td>
                            </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-2 control-label text-left">No. Factura</label>
                        <div class="col-sm-2">
                            <input type="text">
                        </div>
                        <label class="col-sm-2 control-label text-center">Fecha factura</label>
                        <label class="col-sm-2 control-label text-center">2016-05-03</label>
                        <label class="col-sm-2 control-label text-right">$Factura</label>
                        <div class="col-sm-2">
                            <input type="text" style="width:100%">
                        </div>
                    </div>
                    </div>



                    <div class="col-sm-12" style="padding-top:10px;">
                    <div class="form-group">
                        <label class="col-sm-2 control-label text-left">Observaciones</label>
                        <div class="col-sm-10" style="color:#ff0000;">
                            <textarea class="form-control" rows="3" id="comment"></textarea>
                        </div>
                    </div>
                    </div>

                    <div class="col-sm-12" style="padding-top:10px;">
                    <div class="form-group">
                        <div class="col-sm-12 text-right">
                            <button id="btn_savequit" class="btn btn-sm btn-info pull-center" type="button" style="height:28px;" >Parcialmente surtido</button> 
                            <button id="btn_savequit" class="btn btn-sm btn-warning pull-center" type="button" style="height:28px;" >Solicitar aclaracion</button> 
                            <button id="btn_addProd" class="btn btn-sm btn-success pull-center" type="button" style="height:28px;">Recibido OK</button>
                        </div>
                    </div>
                    </div>
                    
                    <div id="error_1"></div>

                    <div id="data_almacen" style="display:none;">
                        <select id="c_almacen" style="width:100px;">
                            <?php foreach ($empleados as $k => $v) { ?>
                                <option value="<?php echo $v['idempleado']; ?>"><?php echo $v['nombre']; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                 </div>
                 </div>             

        </div>
    -->


        
      
<!-- CHRIS - COMENTARIOS
============================= 
//Librerias genericas 
-->

<script src="../../libraries/jquery.min.js" type="text/javascript"></script>
<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
<script src="../../libraries/select2/dist/js/select2.min.js" type="text/javascript"></script>

<!-- CHRIS - COMENTARIOS
============================= 
//Librerias raiz appministra 
-->
<script src="js/numeric.js" type="text/javascript"></script>
<script src="js/moneda.js" type="text/javascript"></script>
<script src="js/datatables.min.js" type="text/javascript"></script>
<script src="js/dataTables.bootstrap.min.js" type="text/javascript"></script>
<script src="js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="js/bootstrap-datepicker.es.js" type="text/javascript"></script>

<!-- CH@
==============================
// Librerias 
<script src="js/select2.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="css/select2.css">
-->


</body>

<script>
var savecont='';
var disprod=0;
var table = '';
var numpago='';
$('#fp_').change(function(){ //ch@
    numpago = $('#fp_').val();
    $('#numpago').val('');
    //cheque 2 trans 3 credito 4 debito 8
    if(numpago == 2 || numpago == 3 || numpago == 4 || numpago == 8 ){
        $("#divnumpago").show();
        if(numpago == 2){ lbnumpago = 'Numero de Cheque:'; }
        if(numpago == 3){ lbnumpago = 'Numero de Trasferencia:'; }
        if(numpago == 4  || numpago == 8){ lbnumpago = 'Numero de Tarjeta:'; }
    }else{
        $("#divnumpago").hide();
    }
    $("#lbnumpago").text(lbnumpago);
});

function redondeo(numero, decimales){
var flotante = parseFloat(numero);
var resultado = Math.round(flotante*Math.pow(10,decimales))/Math.pow(10,decimales);
return resultado;
}

    function check_file()
    {
        var ext = $('#factura').val();
        ext = ext.split('.');
        ext = ext[1];
        if(ext != 'zip' && ext != 'xml')
        {
            alert("Archivo Inválido \nEl archivo debe tener una extensión xml o zip.");
            $("#factura").val('');
        }
    }

    $(function() {
    listareq();
    $('#fac').submit( function( e ) {
    console.log(this);
    //return false;
    //$('#verif').css('display','inline');
    $.ajax( {
      url: 'ajax.php?c=compras&f=subeFactura',
      type: 'POST',
      data: new FormData( this ),
      processData: false,
      contentType: false
    } ).done(function( data1 ) {
        console.log(data1)
        //$("#Facturas").dialog('refresh')
        //alert(data1)
           // return false;
           // $('#factura').val('')
            data1 = data1.split('-/-*');
            $('#verif').css('display','none');

            if(parseInt(data1[0]))
            {
                if(parseInt(data1[3]))
                {
                    alert('Los siguientes '+data1[3]+' archivos no son validos: \n'+data1[4])
                    $('#resultasoc').html('<button id="xmlfile" name="" class="btn btn-danger btn-sx active">XML no asociado</button>');
                }

                if(parseInt(data1[1]))
                {
                    //alert(data1[1]+' Archivos Validados: \n'+data1[2])
                    //$('#resultasoc').html('<button id="xmlfile" name="'+data1[6]+'" class="btn btn-success btn-sx active"><span class="glyphicon glyphicon-ok"></span> XML asociado</button>');
                    datosfac = data1[7].split('##');
                    fac_folio=datosfac[0];
                    fac_fecha=datosfac[1];
                    fac_total=datosfac[2];
                    fac_uuid=datosfac[3];
                    $('#resultasoc').html('<table w style="margin: 15px 0px 8px; width: 100%; border: 1px solid rgb(236, 236, 236); background-color: rgb(250, 250, 250);">\
    <tbody>\
    <tr style="height:25px;">\
      <th width="100">Folio factura</th>\
      <th width="170">Fecha timbrado</th>\
      <th width="150">$ Total factura</th>\
      <th width="100">Ver xml</th>\
      <th width="50" align="center">Eliminar</th>\
    </tr>\
      <tr>\
      <td width="100">'+fac_folio+'</td>\
      <td width="170">'+fac_fecha+'</td>\
      <td width="150">'+fac_total+'</td>\
      <td width="100"><a id="xmlfile" name="'+data1[6]+'" class="btn btn-success btn-xs" onclick="openxml(\''+data1[6]+'\')">Ver xml</a>\
      </td><td width="50" align="center"><a class="btn btn-danger btn-xs" onclick="quitafactasoc(\''+data1[6]+'\');"><span class="glyphicon glyphicon-remove"></span> Quitar</a>\
    </td></tr>\
  </tbody></table>');

                    

                    $('#nofactrec').val(fac_folio);
                    $('#date_recepcion').val(fac_fecha);
                    $('#impfactrec').val(fac_total);

                    //alert(fac_folio+' . '+fac_fecha+' . '+fac_total);
                    
                }
                //alert(parseInt(data1[5]))
                if(parseInt(data1[5])){
                    abrefacturasrepetidas();
                    
                }else{
                  //  location.reload();
                }
            }
            else
            {
                alert("El archivo zip no cumple con el formato correcto\nDebe llamarse igual que la carpeta que contiene los xmls.\nSólo debe contener una carpeta.");

                $('#resultasoc').html('<button id="xmlfile" name="" class="btn btn-danger btn-sx active">XML no asociado</button>');
            }
        
    
    });
    e.preventDefault();
  });
        /*$('#example').DataTable({
        
            "aaSorting": [[0,'desc']],
            ajax: {
                beforeSend: function() {  }, //Show spinner
                url:"ajax.php?c=compras&f=a_listaOrdenesRecepcion",
                type: "POST",
                data: function ( d )    {
                    //d.site = $("#nombredeusuario").val();
                }  
            }
        });
*/

        $("#btn_devolucion").click(function() {
            existendevos = $('#filasprods tr').map(function() {
                cant = $(this).find('.numeros').val();
                if(cant=='' || cant==0 ){ return ''; }else{ return 's'; }
            }).get().join('');  

            if(existendevos!=''){
                tipodevo=$('#reqnota').val();
                if(tipodevo==1){
                    it=$('#it').val();
                    cliente=$('#c_cliente').val();

                    $('#lalala').html('<div class="row">\
                        <div class="col-sm-12" style="padding-top:10px;">\
                            <label class="col-sm-6 control-label text-left">Tipo de factura:</label>\
                            <label class="col-sm-6 control-label text-left"><span id="tipv" class="label label-primary" style="cursor:pointer;">Nota de credito</span></label>\
                        </div>\
                        <div class="col-sm-12" style="padding-top:10px;">\
                            <label class="col-sm-6 control-label text-left">Total:</label>\
                            <label id="ttt" class="col-sm-6 control-label text-left">'+it+'</label>\
                        </div>');
                    
                }else{
                    $('#lalala').html('<div id="segurodev" class="col-sm-12" style="padding:10px 0 10px 0;">\
                        ¿Estas seguro de realizar la devolucion de los productos seleccionados?\
                    </div>\
                    <div class="row">\
                    </div>');
                }
                $('#modal-nc').modal({
                    backdrop: 'static',
                    keyboard: false, 
                    show: true
                });
            }else{
                alert('Tienes que seleccionar las cantidades a devolver');
            }
        });

        $('#modal-nc-uno').on('click',function(){
                devolucion2();
            
        });
        $('#modal-nc-dos').on('click',function(){
            $('#modal-nc').modal('hide');
        });


        function devolucion2(){
            tipodevo=$('#reqnota').val();
            
            esconsig=$('#esconsig').val();
            activo = $(this).attr('activo');
            option = 1;

            id_req = $('#txt_nreq').text();
            id_rec = $('#txt_nreq2').text();//REC
            idrequi = $('#idrequi').val();

            
            disabled_btn('#btn_authquit_ok','Procesando...');

            solicitante=$('#c_solicitante').val();
            tipogasto=$('#c_tipogasto').val();
            moneda=$('#c_moneda').val();
            proveedor=$('#c_proveedores').val();
            almacen=$('#c_almacen').val();
            xmlfile=$('#xmlfile').attr('name');
            fechahoy=$('#date_hoy').val();
            fechaentrega=$('#date_entrega').val();
            date_recep=$('#date_recep').val();

            ist=$('#ist').val();
            it=$('#it').val();
            

            if(moneda==1){
                moneda_tc=0;
            }else{
                moneda_tc=$('#moneda_tc').val();  
            }
        
            urgente = $('input[name=radiourgente]:checked').val();
            if ($('#checkbox').is(':checked')) {
                inventariable=1;
            }else{
                inventariable=0;
            }

            nofactrec = $('#nofactrec').val();
            date_recepcion = $('#date_recepcion').val();
            impfactrec = $('#impfactrec').val();
            desc_concepto = $('#desc_concepto').val();

            deten=0;
            
            if ( (typeof xmlfile == "undefined" || xmlfile=='') && esconsig==0) {
                alert('Tienes que asociar una factura a esta recepcion'); 
                deten=1; 
            }

            if(deten==1){
                enabled_btn('#btn_authquit_ok','Recibido ok');
                return false;
            }

            

            idsProductos = $('#filasprods tr').map(function() {
                cant = $(this).find('.numeros').val();
                idalm = $(this).find('#ccalma').val();
                esp = $(this).find('.numeros').attr('especial');
                tp = $(this).attr('tp');
                ch = $(this).attr('ch');

                if(cant==''){
                    cant=0;
                }

                trid = this.id;
                id = trid.split('tr_');

                if (typeof id[1] !== "undefined") {
                    id= id[1]+'>#'+cant+'>#'+idalm+'>#'+esp+'>#'+tp+'>#'+ch;
                }
                
                return id;
            }).get().join(',# ');  

            console.log(idsProductos);

            idProdFact = $('#filasprods tr').map(function() {
                cant = $(this).find('.numeros').val();
                trid = this.id;
                id = trid.split('tr_');
                ch = $(this).attr('ch');

                valUnit = $(this).find('#valUnit').text();

                if (typeof id[1] !== "undefined") {
                    id= id[1]+'-'+valUnit+'-'+cant+'-0'+'-'+ch;
                }
                
                return id;
            }).get().join('/');  

            console.log(idProdFact);

         
            cliente=$('#c_cliente').val();

            if(idsProductos==''){
                msg_error(1);
                enabled_btn('#btn_authquit_ok','Recibido ok');
                return false;
            }else{
                
                $('#footernc').html('Procesando <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
                console.log(idsProductos);

          
                $.ajax({
                    url:"ajax.php?c=ventas&f=a_devolucionCliente",
                    type: 'POST',
                    data:{
                        idsProductos:idsProductos,
                        solicitante:solicitante,
                        tipogasto:tipogasto,
                        moneda:moneda,
                        proveedor:proveedor,
                        urgente:urgente,
                        inventariable:inventariable,
                        moneda_tc:moneda_tc,
                        fechaentrega:fechaentrega,
                        fechahoy:fechahoy,
                        option:option,
                        idrequi:idrequi,
                        almacen:almacen,
                        idactivo:1,
                        activo:activo,
                        nofactrec:nofactrec,
                        date_recepcion:date_recepcion,
                        impfactrec:impfactrec,
                        xmlfile:xmlfile,
                        desc_concepto:desc_concepto,
                        ist:ist,
                        it:it,
                        date_recep:date_recep,
                        esconsig:esconsig,
                        id_rec:id_rec,
                        cliente:cliente

                    },
                    success: function(r){
                        console.log(r);
                        if(r>0){
                            if(tipodevo==1){
                                idFact = $('#idrfc').val();
                                    $.ajax({
                                        url:"ajax.php?c=ventas&f=a_factProceso",
                                        type: 'POST',
                                        dataType: 'json',
                                        data:{
                                            cliente:cliente,
                                            idsProductos:idsProductos,
                                            ist:ist,
                                            it:it,
                                            cadimps:cadimps,
                                            idProdFact:idProdFact,
                                            idventa:r,
                                            obs:'',
                                            idFact:idFact,
                                            fp:99,
                                            facturo:1,
                                            devo:'s'

                                        },
                                        success: function(resp){
                                            console.log(resp);
                                            if (resp.success == '3') {
                                            alert('Devolucion realizada con exito.');
/*
                                            imps=$('#imps').html();
                                            $.ajax({
                                                async:false,
                                                url:"ajax.php?c=ventas&f=a_verTicket",
                                                type: 'POST',
                                                data:{
                                                    idCoti:idrequi,
                                                    imps:imps,
                                                    op:1 //orden

                                                },
                                                success: function(base){
                                                    
                                                    window.open('../../modulos/appministra/ticket.php','',"width=800,height=500");

                                                    window.location.reload();
                                                     $('#modal-conff').modal('hide');
                                                    return false;
                                                }
                                            });
                                            */
                                            
                                            }
                                            /* NUEVA FACTURACION Y RESPUESTA DE VENTA
                                            ================================================ */
                                            if (resp.success == 0 || resp.success == 5) {
                                                if (resp.success == 0) {
                                                    alert('Ha ocurrido un error durante el proceso de creacion de nota de credito. Error ' + resp.error + ' - ' + resp.mensaje);
                                                }
                                          
                                                $.ajax({
                                                    type: 'POST',
                                                    url:'ajax.php?c=ventas&f=pendienteFacturacion',
                                                    data:{
                                                        azurian:resp.azurian,
                                                        idFact:idFact,
                                                        monto:it,
                                                        cliente:cliente,
                                                        trackId:resp.trackId,
                                                        idVenta:resp.idVenta,
                                                        doc: 2,
                                                        devo:'s'

                                                    },
                                                    beforeSend: function() {
                                                        //caja.mensaje("Guardando Factura 2");
                                                    },
                                                    success: function(resp){ 
                                                        alert('Nota de credito pendiente por facturar'); 
                                                        window.location.reload();
                                                        $('#modal-nc').modal('hide');
                                                        
                                                    }

                                                });
                                                

                                            }

                                            if (resp.success == 1){
                                                azu = JSON.parse(resp.azurian);
                                                uid = resp.datos.UUID;
                                                correo = resp.correo;

                                                $.ajax({
                                                    type: 'POST',
                                                    url: 'ajax.php?c=ventas&f=guardarFacturacion',
                                                    dataType: 'json',
                                                    data: {
                                                        UUID: uid,
                                                        noCertificadoSAT: resp.datos.noCertificadoSAT,
                                                        selloCFD: resp.datos.selloCFD,
                                                        selloSAT: resp.datos.selloSAT,
                                                        FechaTimbrado: resp.datos.FechaTimbrado,
                                                        idComprobante: resp.datos.idComprobante,
                                                        idFact: idFact,
                                                        idVenta: resp.datos.idVenta,
                                                        noCertificado: resp.datos.noCertificado,
                                                        tipoComp: 'C',
                                                        trackId: resp.datos.trackId,
                                                        monto: ist,
                                                        cliente: cliente,
                                                        idRefact: 0,
                                                        azurian: resp.azurian,
                                                        xmlfile:resp.xmlfile,
                                                        doc: 2
                                                    },
                                                    beforeSend: function() {
                                                        
                                                    },
                                                    success: function(resp) {
                                                       
                                                        //caja.eliminaMensaje();
                                                        //window.open('../../modulos/facturas/'+uid+'.pdf');
                                                        $.ajax({
                                                            async: false,
                                                            type: 'POST',
                                                            url: 'ajax.php?c=ventas&f=envioFactura',
                                                            dataType: 'json',
                                                            data: {
                                                                uid: uid,
                                                                correo: correo,
                                                                azurian: azu,
                                                                moneda:moneda,
                                                                doc: 2
                                                            },
                                                            beforeSend: function() {
                                                                
                                                            },
                                                            success: function(resp) {
                                                                
                                                              
                                                                window.open('../../modulos/facturas/' + uid + '.pdf');
                                                                //window.location.reload();
                                                            },
                                                            error: function() {
                                                                alert('Error');
                                                                $('#modal-nc').modal('hide');
                                                            }
                                                        });
                                                        
                                                    window.location.reload();
                                                    alert('Se ha generado la devolucion correctamente');
                                                        
                                                        
                                                    },
                                                    error: function() {
                                                        alert('Error 456');
                                                        $('#modal-nc').modal('hide');
                                                        window.location.reload();
                                                        //$('#modal-conff').modal('hide');
                                                    }
                                                });
                                            }            
                                        }
                                    });


                                    //$('#nreq').css('display','none');
                                    //resetearReq();
                                    //$('#modal-conf3').modal('show');
                                
                            }else{
                                alert('Devolucion creada con exito');
                                $('#nreq').css('display','none');
                                resetearReq();
                                $('#modal-nc').modal('hide');
                            }
                        
                        }else{
                            alert('Error de conexion');
                            enabled_btn('#btn_authquit_ok','Recibido ok');
                        }
                        //enabled_btn('#btn_savequit','Guardar y salir');
                    }
                });
            }
        }

        $("#btn_authquit_ok").click(function() {

            activo = $(this).attr('activo');
            option = 1;

            id_req = $('#txt_nreq').text();
            idrequi = $('#idrequi').val();

            
            //disabled_btn('#btn_authquit_ok','Procesando...');

            solicitante=$('#c_solicitante').val();
            tipogasto=$('#c_tipogasto').val();
            moneda=$('#c_moneda').val();
            proveedor=$('#c_proveedores').val();

            almacen=$('#c_almacen').val();
            cliente=$('#c_cliente').val();

            xmlfile=$('#xmlfile').attr('name');

            fechahoy=$('#date_hoy').val();
            fechaentrega=$('#date_entrega').val();
            

            if(moneda==1){
                moneda_tc=0;
            }else{
                moneda_tc=$('#moneda_tc').val();  
            }
            

            

            urgente = $('input[name=radiourgente]:checked').val();
            if ($('#checkbox').is(':checked')) {
                inventariable=1;
            }else{
                inventariable=0;
            }

            if ($('#facturar').is(':checked')) {
                facturar=1;
            }else{
                facturar=0;
            }

            ist=$('#ist').val();
            it=$('#it').val();
            cadimps=$('#cadimps').val();

            nofactrec = $('#nofactrec').val();
            date_recepcion = $('#date_recepcion').val();
            impfactrec = $('#impfactrec').val();

            deten=0;
            if(solicitante==0){ 
                alert('Tienes que seleccionar un solicitante'); 
                deten=1;
            }else if(fechaentrega=='' && deten==0){ 
                alert('Tienes que seleccionar una fecha de entrega'); 
                deten=1;
            }else if(tipogasto==0 && deten==0){ 
                alert('Tienes que seleccionar un tipo de gasto'); 
                deten=1; 
            }else if(moneda==0 && deten==0){ 
                alert('Tienes que seleccionar una moneda'); 
                deten=1; 
            }else if(moneda_tc=='' && deten==0 && moneda>1){ 
                alert('Tienes que ingresar un tipo de cambio'); 
                deten=1; 
            }else if(proveedor==0 && deten==0){ 
                alert('Tienes que seleccionar un proveedor'); 
                deten=1; 
            }else if(almacen==0 && deten==0){ 
                alert('Tienes que seleccionar un almacen'); 
                deten=1; 
            }else if(ist==0 && deten==0){ 
                alert('El subtotal debe ser mayor a 0'); 
                deten=1; 
            }else if(it==0 && deten==0){ 
                alert('El total debe ser mayor a 0'); 
                deten=1; 
            }

            if(deten==1){
                enabled_btn('#btn_authquit_ok','Recibido ok');
                return false;
            }

            concept=$('#filasprods tr:nth-child(1)').find('td:nth-child(3)').text();

          

            idsProductos = $('#filasprods tr').map(function() {
                cant = $(this).find('.numeros').val();
                idalm = $(this).find('#c_almacen').val();
                esp = $(this).find('.numeros').attr('especial');
                ch = $(this).attr('ch');
                sinexi = $(this).find('input').attr('sinexi');
                tp = $(this).attr('tp');

                trid = this.id;
                id = trid.split('tr_');

                if (typeof id[1] !== "undefined") {
                    id= id[1]+'>#'+cant+'>#'+idalm+'>#'+esp+'>#'+ch+'>#'+sinexi+'>#'+tp;
                }
                
                return id;
            }).get().join(',# ');  

            console.log(idsProductos);
            //return false;


            idProdFact = $('#filasprods tr').map(function() {
                cant = $(this).find('.numeros').val();
                trid = this.id;
                id = trid.split('tr_');
                ch = $(this).attr('ch');
                tp = $(this).attr('tp');

                valUnit = $(this).find('#valUnit').text();

                if (typeof id[1] !== "undefined") {
                    id= id[1]+'-'+valUnit+'-'+cant+'-0'+'-'+ch;
                }
                
                return id;
            }).get().join('/');  

            console.log(idProdFact);
            

            if(idsProductos==''){
                msg_error(1);
                enabled_btn('#btn_authquit_ok','Recibido ok');
                return false;
            }else{
                if(facturar==1){
                    facturo=1;

                    
                    $('#tipv').html('Factura');
                    $('#sitienef').css('display','block');
                    $.ajax({
                        url:"ajax.php?c=ventas&f=a_getRFCcliente",
                        type: 'POST',
                        dataType: 'json',
                        data:{
                            cliente:cliente
                        },
                        success: function(r){
                            if(r==0){
                                $('#rfc_').html('<option value="0">XAXX010101000 (Generico)</option>');
                            }else{
                                llenado='<option value="0">XAXX010101000 (Generico)</option>';
                                $.each(r, function(kk,vv) {
                                    llenado+='<option value="'+vv.id+'">'+vv.rfc+'</option>';
                                });
                                $('#rfc_').html(llenado);
                            }
                        }
                    });
                }else{
                    $('#tipv').html('Ticket');
                    facturo=0;
                    $('#sitienef').css('display','none');
                }
                $('#ttt').text('$'+it);
                    $('#modal-conff').modal({
                        backdrop: 'static',
                        keyboard: false, 
                        show: true
                    });
                    $('#modal-btnconff-dos').on('click',function(){
              
                        $('#modal-btnconff-uno').unbind();
                        $('#modal-btnconff-dos').unbind();
                        $('#modal-conff').modal('hide');
                    });
                    $('#modal-btnconff-uno').on('click',function(){
                        $('#modal-btnconff-uno').unbind();
                        $('#modal-btnconff-dos').unbind();

                        $('#footvent').html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');

                        idFact=$('#rfc_').val();
                        fp=$('#fp_').val();
                        obs=$('#txtobs').val();
                        fptext= $("#fp_ option:selected").text();

                        $.ajax({
                            url:"ajax.php?c=ventas&f=a_envioOrden",
                            type: 'POST',
                            data:{
                                idsProductos:idsProductos,
                                solicitante:solicitante,
                                tipogasto:tipogasto,
                                moneda:moneda,
                                proveedor:proveedor,
                                cliente:cliente,
                                urgente:urgente,
                                inventariable:inventariable,
                                moneda_tc:moneda_tc,
                                fechaentrega:fechaentrega,
                                fechahoy:fechahoy,
                                option:option,
                                idrequi:idrequi,
                                almacen:almacen,
                                idactivo:1,
                                activo:activo,
                                nofactrec:nofactrec,
                                date_recepcion:date_recepcion,
                                impfactrec:impfactrec,
                                xmlfile:xmlfile,
                                ist:ist,
                                it:it,
                                fp:fp,
                                facturo:facturo,
                                obs:obs,
                                concept:concept,
                                cadimps:cadimps,
                                fptext:fptext

                            },
                            success: function(r){
                                console.log(r);
                                

                                if(r>0){
                                    $.ajax({
                                        url:"ajax.php?c=ventas&f=a_factProceso",
                                        type: 'POST',
                                        dataType: 'json',
                                        data:{
                                            cliente:cliente,
                                            idsProductos:idsProductos,
                                            ist:ist,
                                            it:it,
                                            cadimps:cadimps,
                                            idProdFact:idProdFact,
                                            idventa:r,
                                            obs:obs,
                                            idFact:idFact,
                                            fp:fp,
                                            facturo:facturo

                                        },
                                        success: function(resp){
                                            console.log(resp);
                                            if (resp.success == '3') {
                                            alert('Venta realizada con exito.');

                                            imps=$('#imps').html();

/*
                                            $.ajax({
                                                url:"ajax.php?c=ventas&f=a_enviarVenta2",
                                                type: 'POST',
                                                data:{
                                                    idCoti:idrequi,
                                                    imps:imps,
                                                    modo:3,
                                                    print:1,
                                                    op:1

                                                },
                                                success: function(r){
                                                    window.open("../../modulos/cotizaciones/cotizacionesPdf/cotizacion_"+idrequi+".pdf");
                                                    //window.open('../../modulos/appministra/ticket.php','',"width=800,height=500");

                                                    //window.location.reload();
                                                     //$('#modal-conff').modal('hide');
                                                    return false;
                                                }
                                            });
*/
                                            
                                            $.ajax({
                                                async:false,
                                                url:"ajax.php?c=ventas&f=a_verTicketN",
                                                type: 'POST',
                                                data:{
                                                    idCoti:idrequi,
                                                    imps:imps,
                                                    venta:r,
                                                    ist:ist,
                                                    it:it,
                                                    op:1 //orden

                                                },
                                                success: function(base){
                                                    window.open("../../modulos/cotizaciones/cotizacionesPdf/Envio_"+r+".pdf");
                                                   // window.open('../../modulos/appministra/ticket.php','',"width=800,height=500");

                                                    //window.location.reload();
                                                     $('#modal-conff').modal('hide');
                                                    return false;
                                                }
                                            });
                                            
                                            
                                            }
                                            /* NUEVA FACTURACION Y RESPUESTA DE VENTA
                                            ================================================ */
                                            if (resp.success == 0 || resp.success == 5) {
                                                if (resp.success == 0) {
                                                    alert('Ha ocurrido un error durante el proceso de facturación. Error ' + resp.error + ' - ' + resp.mensaje);
                                                }
                                                
                                                imps=$('#imps').html();
/*
                                                $.ajax({
                                                url:"ajax.php?c=ventas&f=a_enviarVenta2",
                                                type: 'POST',
                                                data:{
                                                    idCoti:idrequi,
                                                    imps:imps,
                                                    modo:3,
                                                    print:1,
                                                    op:1

                                                },
                                                success: function(r){
                                                    window.open("../../modulos/cotizaciones/cotizacionesPdf/cotizacion_"+idrequi+".pdf");
                                                    //window.open('../../modulos/appministra/ticket.php','',"width=800,height=500");

                                                    //window.location.reload();
                                                     //$('#modal-conff').modal('hide');
                                                    return false;
                                                }
                                            });
                                            */
                                                
                                                $.ajax({
                                                    async:false,
                                                    url:"ajax.php?c=ventas&f=a_verTicketN",
                                                    type: 'POST',
                                                    data:{
                                                        idCoti:idrequi,
                                                        imps:imps,
                                                        venta:r,
                                                        ist:ist,
                                                        it:it,
                                                        op:1 //orden

                                                    },
                                                    success: function(base){
                                                         window.open("../../modulos/cotizaciones/cotizacionesPdf/Envio_"+r+".pdf");
                                                        //window.open('../../modulos/appministra/ticket.php','',"width=800,height=500");

                                                       // window.location.reload();
                                                         $('#modal-conff').modal('hide');
                                                        return false;
                                                    }
                                                });
                                                
                                                $.ajax({
                                                    type: 'POST',
                                                    url:'ajax.php?c=ventas&f=pendienteFacturacion',
                                                    data:{
                                                        azurian:resp.azurian,
                                                        idFact:idFact,
                                                        monto:it,
                                                        cliente:cliente,
                                                        trackId:resp.trackId,
                                                        idVenta:resp.idVenta,
                                                        doc: 2

                                                    },
                                                    beforeSend: function() {
                                                        //caja.mensaje("Guardando Factura 2");
                                                    },
                                                    success: function(resp){ 
                                                        alert('Venta pendiente por facturar'); 
                                                        window.location.reload();
                                                        $('#modal-conff').modal('hide');
                                                        
                                                    }

                                                });

                                            }

                                            if (resp.success == 1){
                                                azu = JSON.parse(resp.azurian);
                                                uid = resp.datos.UUID;
                                                correo = resp.correo;

                                                $.ajax({
                                                    type: 'POST',
                                                    url: 'ajax.php?c=ventas&f=guardarFacturacion',
                                                    dataType: 'json',
                                                    data: {
                                                        UUID: uid,
                                                        noCertificadoSAT: resp.datos.noCertificadoSAT,
                                                        selloCFD: resp.datos.selloCFD,
                                                        selloSAT: resp.datos.selloSAT,
                                                        FechaTimbrado: resp.datos.FechaTimbrado,
                                                        idComprobante: resp.datos.idComprobante,
                                                        idFact: idFact,
                                                        idVenta: resp.datos.idVenta,
                                                        noCertificado: resp.datos.noCertificado,
                                                        tipoComp: resp.datos.tipoComp,
                                                        trackId: resp.datos.trackId,
                                                        monto: ist,
                                                        cliente: cliente,
                                                        idRefact: 0,
                                                        azurian: resp.azurian,
                                                        xmlfile:resp.xmlfile,
                                                        doc: 2
                                                    },
                                                    beforeSend: function() {
                                                        
                                                    },
                                                    success: function(resp) {
                                                       
                                                        //caja.eliminaMensaje();
                                                        //window.open('../../modulos/facturas/'+uid+'.pdf');
                                                        $.ajax({
                                                            async: false,
                                                            type: 'POST',
                                                            url: 'ajax.php?c=ventas&f=envioFactura',
                                                            dataType: 'json',
                                                            data: {
                                                                uid: uid,
                                                                correo: correo,
                                                                azurian: azu,
                                                                doc: 2
                                                            },
                                                            beforeSend: function() {
                                                                
                                                            },
                                                            success: function(resp) {
                                                                
                                                              
                                                                window.open('../../modulos/facturas/' + uid + '.pdf');
                                                                //window.location.reload();
                                                            },
                                                            error: function() {
                                                                alert('Error');
                                                            }
                                                        });
                                                    window.location.reload();
                                                    alert('Has registrado la venta con exito');
                                                        
                                                        
                                                    },
                                                    error: function() {
                                                        alert('Error 456');
                                                        $('#modal-conff').modal('hide');
                                                    }
                                                });
                                            }            
                                        }
                                    });


                                    //$('#nreq').css('display','none');
                                    //resetearReq();
                                    //$('#modal-conf3').modal('show');
                                }else{
                                    alert('Error de conexion');
                                    enabled_btn('#btn_authquit_ok','Recibido ok');
                                }
                                //enabled_btn('#btn_savequit','Guardar y salir');
                            }
                        });




                        
                        
                       
                    });
                    return false;
                
                /*console.log(idsProductos);
                $.ajax({
                    url:"ajax.php?c=ventas&f=a_envioOrden",
                    type: 'POST',
                    data:{
                        idsProductos:idsProductos,
                        solicitante:solicitante,
                        tipogasto:tipogasto,
                        moneda:moneda,
                        proveedor:proveedor,
                        urgente:urgente,
                        inventariable:inventariable,
                        moneda_tc:moneda_tc,
                        fechaentrega:fechaentrega,
                        fechahoy:fechahoy,
                        option:option,
                        idrequi:idrequi,
                        almacen:almacen,
                        idactivo:1,
                        activo:activo,
                        nofactrec:nofactrec,
                        date_recepcion:date_recepcion,
                        impfactrec:impfactrec,
                        xmlfile:xmlfile,
                        ist:ist,
                        it:it,
                        fp:0,
                        facturo:0

                    },
                    success: function(r){
                        console.log(r);
                        if(r>0){
                            $('#nreq').css('display','none');
                            resetearReq();
                            $('#modal-conf3').modal('show');
                        }else{
                            alert('Error de conexion');
                            enabled_btn('#btn_authquit_ok','Recibido ok');
                        }
                        //enabled_btn('#btn_savequit','Guardar y salir');
                    }
                });
                */
            }

        
        });


        $("#btn_authquit_p").click(function() {

            activo = $(this).attr('activo');
            option = 1;

            id_req = $('#txt_nreq').text();
            idrequi = $('#idrequi').val();

            
            disabled_btn('#btn_authquit_p','Procesando...');

            solicitante=$('#c_solicitante').val();
            tipogasto=$('#c_tipogasto').val();
            moneda=$('#c_moneda').val();
            proveedor=$('#c_proveedores').val();

            almacen=$('#c_almacen').val();

            xmlfile=$('#xmlfile').attr('name');


            fechahoy=$('#fechahoy').text();
            fechaentrega=$('#date_entrega').val();
            

            if(moneda==1){
                moneda_tc=0;
            }else{
                moneda_tc=$('#moneda_tc').val();  
            }
            

            

            urgente = $('input[name=radiourgente]:checked').val();
            if ($('#checkbox').is(':checked')) {
                inventariable=1;
            }else{
                inventariable=0;
            }

            nofactrec = $('#nofactrec').val();
            date_recepcion = $('#date_recepcion').val();
            impfactrec = $('#impfactrec').val();

            deten=0;
            if(solicitante==0){ 
                alert('Tienes que seleccionar un solicitante'); 
                deten=1;
            }else if(fechaentrega=='' && deten==0){ 
                alert('Tienes que seleccionar una fecha de entrega'); 
                deten=1;
            }else if(tipogasto==0 && deten==0){ 
                alert('Tienes que seleccionar un tipo de gasto'); 
                deten=1; 
            }else if(moneda==0 && deten==0){ 
                alert('Tienes que seleccionar una moneda'); 
                deten=1; 
            }else if(moneda_tc=='' && deten==0 && moneda>1){ 
                alert('Tienes que ingresar un tipo de cambio'); 
                deten=1; 
            }else if(proveedor==0 && deten==0){ 
                alert('Tienes que seleccionar un proveedor'); 
                deten=1; 
            }else if(almacen==0 && deten==0){ 
                alert('Tienes que seleccionar un almacen'); 
                deten=1; 
            }

            

            if(deten==1){
                enabled_btn('#btn_authquit_p','Parcialmente surtido');
                return false;
            }

            recibidook=0; 

            idsProductos = $('#filasprods tr').map(function() {
                cant = $(this).find('.numeros').val();
                idalm = $(this).find('#c_almacen').val();
                esp = $(this).find('.numeros').attr('especial');

                cantoc=$(this).find('td:nth-child(6)').text()*1;
                cantrecibida=$(this).find('td:nth-child(8)').text()*1;

                //alert(cantoc+' - '+cantrecibida+' - '+cant); 
                //alert( ((cantrecibida*1)+(cant*1)) +' - '+cantoc);
                if( ((cantrecibida*1)+(cant*1)) < cantoc*1 ){
                    recibidook++;
                }else{
                    //recibidook=0;
                } 

                //alert(recibidook);

                trid = this.id;
                id = trid.split('tr_');

                if (typeof id[1] !== "undefined") {
                    id= id[1]+'>'+cant+'>'+idalm+'>'+esp;
                }
                
                return id;
            }).get().join(', ');  

            //alert(recibidook);

            if(recibidook==0){
                activo=4;
                if (typeof xmlfile == "undefined" || xmlfile=='') {
                    alert('Tienes que asociar una factura a esta recepcion'); 
                    deten=1; 
                }
            }
            console.log(idsProductos);
            //return false;

            if(idsProductos==''){
                msg_error(1);
                enabled_btn('#btn_authquit_p','Parcialmente surtido');
                return false;
            }else{

                console.log(idsProductos);
                $.ajax({
                    url:"ajax.php?c=compras&f=a_recepcionOrden",
                    type: 'POST',
                    data:{
                        idsProductos:idsProductos,
                        solicitante:solicitante,
                        tipogasto:tipogasto,
                        moneda:moneda,
                        proveedor:proveedor,
                        urgente:urgente,
                        inventariable:inventariable,
                        moneda_tc:moneda_tc,
                        fechaentrega:fechaentrega,
                        fechahoy:fechahoy,
                        option:option,
                        idrequi:idrequi,
                        almacen:almacen,
                        idactivo:1,
                        activo:activo,
                        nofactrec:nofactrec,
                        date_recepcion:date_recepcion,
                        impfactrec:impfactrec,
                        xmlfile:xmlfile

                    },
                    success: function(r){
                        console.log(r);
                        if(r>0){
                            $('#nreq').css('display','none');
                            resetearReq();
                            $('#modal-conf3').modal('show');
                        }else{
                            alert('Error de conexion');
                            enabled_btn('#btn_authquit_p','Parcialmente surtido');
                        }
                        //enabled_btn('#btn_savequit','Guardar y salir');
                    }
                });
            }

        
        });
        


        //$('#example').DataTable({});
        $('#example_wrapper div:nth-child(2) div:nth-child(1)').css('overflow-y','auto');

        var table = $('#tablaprods').DataTable( {
            language: {
                search: "Buscar:",
                lengthMenu:"Mostrar _MENU_ elementos",
                info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                paginate: {
                    first:      "Primero",
                    previous:   "Anterior",
                    next:       "Siguiente",
                    last:       "Último"
                },
             },
                                paging: false,
                                fixedHeader: true,
                               // "scrollY": true,

                                    "order": [],
                                    "columnDefs": [ {
                                    "targets"  : 'no-sort',
                                    "orderable": false,
                                }],
                            "footerCallback": function ( row, data, start, end, display ) {



                                var api = this.api(), data;
                     
                                // Remove the formatting to get integer data for summation
                                var intVal = function ( i ) {
                                    return typeof i === 'string' ?
                                        i.replace(/[\$,]/g, '')*1 :
                                        typeof i === 'number' ?
                                            i : 0;
                                };
                     
                                // Total over all pages
                                total = api
                                    .column(8)
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(a) + intVal(b);
                                    }, 0 );
                     
                                // Total over this page
                                pageTotal = api
                                    .column(8, { page: 'current'} )
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(a) + intVal(b);
                                    }, 0 );
                     
                                // Update footer
                                pt = redondeo(pageTotal,2);
                                tt = redondeo(total,2);
                                $( api.column(9).footer() ).html(
                                    '<div id="imps">Cargando...</div>'
                                    //'$'+pt +' ( $'+ tt +' Gran total)<br><div id="imps">a</div>'
                                );
                                $( api.column(1).footer() ).html(
                                    '<div id="impsxx"></div>'
                                    //'$'+pt +' ( $'+ tt +' Gran total)<br><div id="imps">a</div>'
                                );
                                }
                            });

        

        $('#c_productos').select2();
        $('#c_proveedores').select2({ width: '300px' });
        $('#c_solicitante').select2();
        $('#c_cliente').select2();
        $('#c_tipogasto').select2();
        $('#c_area').select2();
        $('#c_almacen').select2();
        
        $('#c_moneda').select2();
        $('footer div').remove();

        $("#c_solicitante").change(function() {

        });
        $("#c_moneda").change(function() {
            idMoneda=$(this).val()
            if(idMoneda>1){
                $('#moneda_tc').val('');
                $('#moneda_tc').numeric();
                $('#moneda_tc').css('display','block');
            }else{
                $('#moneda_tc').css('display','none');
            }
        });

        $("#c_proveedores").change(function() {
            if(disprod==1){
                return false;
            }
            $("#c_productos").html('<option value="0">Seleccione</option>');
            $("#c_productos").select2();
            idProveedor=$(this).val()
            if(idProveedor>0){
                $.ajax({
                    url:"ajax.php?c=compras&f=a_getProvProducto",
                    type: 'POST',
                    dataType: 'JSON',
                    data:{idProveedor:idProveedor},
                    success: function(r){
                        console.log(r);
                        if(r.success==1){
                            llenado='';
                            $.each( r.datos, function(k,v) {
                                llenado+='<option value="'+v.id+'">'+v.descripcion_corta+'</option>';
                            });
                            $("#c_productos").html('');
                            $("#c_productos").append(llenado);
                            $("#c_productos").select2();
                            $("#c_productos").prop('disabled',false);
                        }else{
                            $("#c_productos").prop('disabled',true);
                            alert('El proveedor seleccionado no tiene productos');
                        }
                    }
                });
            }else{
                $("#c_productos").html('<option value="0">Seleccione</option>');
                $("#c_productos").select2();
            }
        });

        $("#btn_solacla").click(function() {
            idrequi = $('#idrequi').val();
            $.ajax({
                url:"ajax.php?c=compras&f=a_solacla",
                type: 'POST',
                data:{
                    idrequi:idrequi
                },
                success: function(r){
                    $('#nreq').css('display','none');
                    resetearReq();
                    $('#modal-conf3').modal('show');
                    //enabled_btn('#btn_savequit','Guardar y salir');
                }
            });
        });


        $("#btn_savequit").click(function() {
            disabled_btn('#btn_savequit','Procesando...');

            solicitante=$('#c_solicitante').val();
            tipogasto=$('#c_tipogasto').val();
            moneda=$('#c_moneda').val();
            proveedor=$('#c_proveedores').val();

            deten=0;
            if(solicitante==0){ 
                alert('Tienes que seleccionar un solicitante'); 
                deten=1; 
            }else if(tipogasto==0 && deten==0){ 
                alert('Tienes que seleccionar un tipo de gasto'); 
                deten=1; 
            }else if(moneda==0 && deten==0){ 
                alert('Tienes que seleccionar una moneda'); 
                deten=1; 
            }else if(proveedor==0 && deten==0){ 
                alert('Tienes que seleccionar un proveedor'); 
                deten=1; 
            }

            if(deten==1){
                enabled_btn('#btn_savequit','Guardar y salir');
                return false;
            }

            idsProductos = $('#filasprods tr').map(function() {
                trid = this.id;
                id = trid.split('tr_');
                id= id[1];
                return id;
            }).get().join(', ');            

            if(idsProductos==''){
                msg_error(1);
                enabled_btn('#btn_savequit','Guardar y salir');
                return false;
            }else{
                console.log(idsProductos);
                $.ajax({
                    url:"ajax.php?c=compras&f=a_guardarRequisicion",
                    type: 'POST',
                    data:{
                        idsProductos:idsProductos,
                        solicitante:solicitante,
                        tipogasto:tipogasto,
                        moneda:moneda,
                        proveedor:proveedor
                    },
                    success: function(r){
                        $('#nreq').css('display','none');
                        resetearReq();
                        $('#modal-conf3').modal('show');
                        //enabled_btn('#btn_savequit','Guardar y salir');
                    }
                });
            }

        
        });

        $('.numeros').on('input', function() {
            alert('cambios');
        });

        $("#div_productos").click(function() {
            var isDisabled = $('#c_productos').prop('disabled');
            if(isDisabled==1){
                $('#select2-c_proveedores-container').css('border-color','#ff0000');
                alert('Seleccione un proveedor');
            }
        });  

        $("#btn_addProd").click(function() {
            idProducto = $('#c_productos').val();
            if(idProducto>0){
                disabled_btn('#btn_addProd','Procesando...');
                if($("#tr_"+idProducto).length ) {
                    valorig = $("#tr_"+idProducto+" input").val();
                    $("#tr_"+idProducto+" input").val((valorig*1)+1);
                    refreshCants(idProducto);
                    tdTotals();
                    enabled_btn('#btn_addProd','Agregar producto');
                    return false;
                } 

                $.ajax({
                url:"ajax.php?c=compras&f=a_addProductoReq",
                type: 'POST',
                dataType:'JSON',
                data:{idProducto:idProducto},
                success: function(r){
                    console.log(r);
                    if(r.success==1){
                        data_almacen=$('#data_almacen').html();
                        txt_proveedor=$('#c_proveedores option:selected').text();
                        $('#panel_tabla').css('display','block');
                        filasprod="<tr id='tr_"+r.datos[0].id+"'>\
                                    <td>0</td>\
                                    <td>30DCO001</td>\
                                    <td>"+r.datos[0].descripcion_corta+"</td>\
                                    <td>Kilo</td>\
                                    <td>"+txt_proveedor+"</td>\
                                    <td id='valUnit'>45.05</td>\
                                    <td><input onkeyup='refreshCants("+r.datos[0].id+")' class='numeros' type='text' value='1'/></td>\
                                    <td class='valImporte' implimpio='45.05' id='valImporte'>45.05</td>\
                                    <td>MXN</td>\
                                    <td><button onclick='removeProdReq("+r.datos[0].id+");' id='btn_addProd' class='btn btn-sm btn-danger' type='button' style='height:26px;'>Quitar</button></td>\
                                  </tr>";

                        if($("#tr_totals").length ) {
                            $('#filasprods #tr_totals').before(filasprod);
                        }else{
                            $('#filasprods').append(filasprod);
                        }
                        tdTotals();

                        $('#myTable tr.grand-total').before('<tr></tr>');
                        $('.numeros').numeric();
                        //$('#c_almacen').select2();
                        enabled_btn('#btn_addProd','Agregar producto');
                    }else{
                        alert('Error al agregar producto');
                    }

                }
                });
            }else{
                alert('Selecciona un producto valido');
            }
        });
    });//*****FIN READY****


    function refacturar(idSale,idFact,total){
        $('#numpago, #txtobs').val('');        
        $.ajax({
            url:"ajax.php?c=ventas&f=a_getRFCcliente",
            type: 'POST',
            dataType: 'json',
            data:{
                cliente:idFact
            },
            success: function(r){
                if(r==0){
                    $('#rfc_').html('<option value="0">XAXX010101000 (Generico)</option>');
                }else{
                    llenado='<option value="0">XAXX010101000 (Generico)</option>';
                    $.each(r, function(kk,vv) {
                        llenado+='<option value="'+vv.id+'">'+vv.rfc+'</option>';
                    });
                    $('#rfc_').html(llenado);
                }
            }
        });

        $('#modal-conff').modal({
            backdrop: 'static',
            keyboard: false, 
            show: true
        });

        $('#tipv').html('Factura');
        $('#ttt').text('$'+total);
        $('#modal-btnconff-dos').on('click',function(){
              
            $('#modal-btnconff-uno').unbind();
            $('#modal-btnconff-dos').unbind();
            $('#modal-conff').modal('hide');
        });

        $('#modal-btnconff-uno').on('click',function(){
                    $('#modal-btnconff-uno').unbind();
                    $('#modal-btnconff-dos').unbind();
                    $('#footvent').html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');

                        idFact=$('#rfc_').val();
                        fp=$('#fp_').val();
                        txtobs=$('#txtobs').val();
                        numpago=$('#numpago').val();

                        $.ajax({
                            url:"ajax.php?c=ventas&f=oneFact",
                            type: 'POST',
                            dataType: 'json',
                            data:{
                                idFact:idFact,
                                idSale:idSale,
                                txtobs:txtobs

                            },
                            success: function(resp){
                                console.log(resp);
                                if (resp.success == '3') {
                                alert('Venta realizada con exito.');
                                
                                window.open('../../modulos/appministra/ticket.php?idv='+resp.idVenta+'&s='+ist+'&t='+it,'',"width=400,height=500");

                                    window.location.reload();
                                     $('#modal-conff').modal('hide');
                                    return false;
                                }
                                /* NUEVA FACTURACION Y RESPUESTA DE VENTA
                                ================================================ */
                                if (resp.success == 0 || resp.success == 5) {
                                    if (resp.success == 0) {
                                        alert('Ha ocurrido un error durante el proceso de facturación. Error ' + resp.error + ' - ' + resp.mensaje);
                                        $('#modal-conff').modal('hide');
                                        $('#footvent').html('<button id="modal-btnconff-uno" type="button" class="btn btn-default">Finalizar</button> <button id="modal-btnconff-dos" type="button" class="btn btn-default">Cancelar</button>');
                                    }
                                }

                                if (resp.success == 1){
                                    azu = JSON.parse(resp.azurian);
                                    console.log(azu);
                                    uid = resp.datos.UUID;
                                    correo = resp.correo;

                                    $.ajax({
                                        type: 'POST',
                                        url: 'ajax.php?c=ventas&f=guardarFacturacion',
                                        dataType: 'json',
                                        data: {
                                            UUID: uid,
                                            noCertificadoSAT: resp.datos.noCertificadoSAT,
                                            selloCFD: resp.datos.selloCFD,
                                            selloSAT: resp.datos.selloSAT,
                                            FechaTimbrado: resp.datos.FechaTimbrado,
                                            idComprobante: resp.datos.idComprobante,
                                            idFact: idFact,
                                            idVenta: resp.datos.idVenta,
                                            noCertificado: resp.datos.noCertificado,
                                            tipoComp: resp.datos.tipoComp,
                                            trackId: resp.datos.trackId,
                                            monto: (resp.monto),
                                            cliente: 0,
                                            idRefact: idSale,
                                            azurian: resp.azurian,
                                            xmlfile:resp.xmlfile,
                                            doc: 2,
                                            fp:fp,
                                            numpago:numpago

                                        },
                                        beforeSend: function() {
                                            
                                        },
                                        success: function(resp) {
                 
                                            $.ajax({
                                                async: false,
                                                type: 'POST',
                                                url: 'ajax.php?c=ventas&f=envioFactura',
                                                dataType: 'json',
                                                data: {
                                                    uid: uid,
                                                    correo: correo,
                                                    azurian: azu,
                                                    doc: 2
                                                },
                                                beforeSend: function() {
                                                    
                                                },
                                                success: function(resp) {
                                                    
                                                  
                                                    window.open('../../modulos/facturas/' + uid + '.pdf');
                                                    $('#modal-conff').modal('hide');
                                                },
                                                error: function() {
                                                    alert('Error');
                                                    $('#modal-conff').modal('hide');
                                                    $('#footvent').html('<button id="modal-btnconff-uno" type="button" class="btn btn-default">Finalizar</button> <button id="modal-btnconff-dos" type="button" class="btn btn-default">Cancelar</button>');
                                                }
                                            });
                                        window.location.reload();
                                        alert('Has registrado la venta con exito');
                                            
                                        },
                                        error: function() {
                                            
                                        }
                                    });
                                }            
                            }
                        });


                    });


    }

    function refacturar2(idSale,idFact,total){
        $.ajax({
            url:"ajax.php?c=ventas&f=a_getRFCcliente",
            type: 'POST',
            dataType: 'json',
            data:{
                cliente:idFact
            },
            success: function(r){
                if(r==0){
                    $('#rfc_').html('<option value="0">XAXX010101000 (Generico)</option>');
                }else{
                    llenado='<option value="0">XAXX010101000 (Generico)</option>';
                    $.each(r, function(kk,vv) {
                        llenado+='<option value="'+vv.id+'">'+vv.rfc+'</option>';
                    });
                    $('#rfc_').html(llenado);
                }
            }
        });

        $('#modal-conff').modal({
            backdrop: 'static',
            keyboard: false, 
            show: true
        });

        $('#tipv').html('Factura');
        $('#ttt').text('$'+total);
        $('#modal-btnconff-dos').on('click',function(){
              
            $('#modal-btnconff-uno').unbind();
            $('#modal-btnconff-dos').unbind();
            $('#modal-conff').modal('hide');
        });

        $('#modal-btnconff-uno').on('click',function(){
                    $('#modal-btnconff-uno').unbind();
                    $('#modal-btnconff-dos').unbind();
                    $('#footvent').html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');

                        idFact=$('#rfc_').val();
                        fp=$('#fp_').val();
                        txtobs=$('#txtobs').val();

                        $.ajax({
                            url:"ajax.php?c=ventas&f=oneFact2",
                            type: 'POST',
                            dataType: 'json',
                            data:{
                                idFact:idFact,
                                idSale:idSale,
                                txtobs:txtobs

                            },
                            success: function(resp){
                                console.log(resp);
                                if (resp.success == '3') {
                                alert('Venta realizada con exito.');
                                
                                window.open('../../modulos/appministra/ticket.php?idv='+resp.idVenta+'&s='+ist+'&t='+it,'',"width=400,height=500");

                                    window.location.reload();
                                     $('#modal-conff').modal('hide');
                                    return false;
                                }
                                /* NUEVA FACTURACION Y RESPUESTA DE VENTA
                                ================================================ */
                                if (resp.success == 0 || resp.success == 5) {
                                    if (resp.success == 0) {
                                        alert('Ha ocurrido un error durante el proceso de facturación. Error ' + resp.error + ' - ' + resp.mensaje);
                                        $('#modal-conff').modal('hide');
                                        $('#footvent').html('<button id="modal-btnconff-uno" type="button" class="btn btn-default">Finalizar</button> <button id="modal-btnconff-dos" type="button" class="btn btn-default">Cancelar</button>');
                                    }
                                }

                                if (resp.success == 1){
                                    azu = JSON.parse(resp.azurian);
                                    console.log(azu);
                                    uid = resp.datos.UUID;
                                    correo = resp.correo;

                                    $.ajax({
                                        type: 'POST',
                                        url: 'ajax.php?c=ventas&f=guardarFacturacion',
                                        dataType: 'json',
                                        data: {
                                            UUID: uid,
                                            noCertificadoSAT: resp.datos.noCertificadoSAT,
                                            selloCFD: resp.datos.selloCFD,
                                            selloSAT: resp.datos.selloSAT,
                                            FechaTimbrado: resp.datos.FechaTimbrado,
                                            idComprobante: resp.datos.idComprobante,
                                            idFact: idFact,
                                            idVenta: resp.datos.idVenta,
                                            noCertificado: resp.datos.noCertificado,
                                            tipoComp: resp.datos.tipoComp,
                                            trackId: resp.datos.trackId,
                                            monto: (resp.monto),
                                            cliente: 'refact2',
                                            idRefact: idSale,
                                            azurian: resp.azurian,
                                            xmlfile:resp.xmlfile,
                                            doc: 2

                                        },
                                        beforeSend: function() {
                                            
                                        },
                                        success: function(resp) {
                 
                                            $.ajax({
                                                async: false,
                                                type: 'POST',
                                                url: 'ajax.php?c=ventas&f=envioFactura',
                                                dataType: 'json',
                                                data: {
                                                    uid: uid,
                                                    correo: correo,
                                                    azurian: azu,
                                                    doc: 2
                                                },
                                                beforeSend: function() {
                                                    
                                                },
                                                success: function(resp) {
                                                    
                                                  
                                                    window.open('../../modulos/facturas/' + uid + '.pdf');
                                                    $('#modal-conff').modal('hide');
                                                },
                                                error: function() {
                                                    alert('Error');
                                                    $('#modal-conff').modal('hide');
                                                    $('#footvent').html('<button id="modal-btnconff-uno" type="button" class="btn btn-default">Finalizar</button> <button id="modal-btnconff-dos" type="button" class="btn btn-default">Cancelar</button>');
                                                }
                                            });
                                        window.location.reload();
                                        alert('Has registrado la venta con exito');
                                            
                                        },
                                        error: function() {
                                            
                                        }
                                    });
                                }            
                            }
                        });


                    });


    }

    function acuse(idFact){
        
        $.ajax({
            type: 'POST',
            url:'ajax.php?c=ventas&f=a_acuse',
            dataType: 'JSON',
            data:{
                idFact:idFact
            },
            success: function(resp){  
                if (resp.success == 1) {
                    $('#modal-acuse').modal('show');
                    $('.acusefecha').text(resp.fecha);
                    $('#acuserfc').text(resp.rfc);
                    $('#acusefolio').text(resp.uuid);

                }
            }

        });
    }

    
    function cancelarFactura(idFact){
        $('#modal-cancel').modal('show');
        $.ajax({
            type: 'POST',
            url:'ajax.php?c=ventas&f=a_cancelaFactura',
            dataType: 'JSON',
            data:{
                idFact:idFact
            },
            beforeSend: function() {
                //caja.mensaje("Guardando Factura 2");
            },
            success: function(resp){  
                if (resp.success == 1) {
                    alert(resp.mensaje);
                    $.ajax({
                        type: 'POST',
                        url:'ajax.php?c=ventas&f=a_actualizaCancelFact',
                        data:{
                            idFact:idFact
                        },
                        beforeSend: function() {
                            //caja.mensaje("Guardando Factura 2");
                        },
                        success: function(){  
                            location.reload();
                            $('#modal-cancel').modal('hide');
                        }

                    });   
                }

                if (resp.success == 0) {
                    $('#modal-cancel').modal('hide');
                    alert(resp.mensaje);
                    return false;
                }


            }

        });
    }

    function verFactura(idFact){
        $.ajax({
            type: 'POST',
            url:'ajax.php?c=ventas&f=a_verFactura',
            data:{
                idFact:idFact
            },
            beforeSend: function() {
                //caja.mensaje("Guardando Factura 2");
            },
            success: function(resp){  
                console.log(resp);
                window.open('../../modulos/cont/xmls/facturas/temporales/'+resp);
            }

        });
    }

    function verFacturaPdf(idFact){
        $.ajax({
            type: 'POST',
            url:'ajax.php?c=ventas&f=a_verFacturaPdf',
            data:{
                idFact:idFact
            },
            beforeSend: function() {
                //caja.mensaje("Guardando Factura 2");
            },
            success: function(resp){  
                console.log(resp);
                window.open('../../modulos/facturas/'+resp+'.pdf');
            }

        });
    }

    function tdTotals(idProducto){
        if($("#tr_totals").length ) {
            total=0;
            $(".valImporte").each(function( index ) {
                totfila = $(this).attr('implimpio');
                totfila = totfila.replace("/,/g", ""); 
                totfila=totfila*1;
                total+=totfila;
            });

            $('#sumatotales').text(total).currency();
            return false;
        } 

        filasprod="<tr id='tr_totals'>\
                    <td>&nbsp;</td>\
                    <td>&nbsp;</td>\
                    <td>&nbsp;</td>\
                    <td>&nbsp;</td>\
                    <td>&nbsp;</td>\
                    <td>&nbsp;</td>\
                    <td>&nbsp;</td>\
                    <td align='right'><b>Total:</b></td>\
                    <td id='sumatotales'>0.00</td>\
                    <td>MXN</td>\
                    <td>&nbsp;</td>\
                  </tr>";
        $('#filasprods').append(filasprod);
        tt = $('#valImporte').attr('implimpio'); tt=tt*1;
        $('#sumatotales').text(tt).currency();
        
    } 

    function resetearReq(){
        $('tbody').empty();

        $('#facturar').prop('disabled', false);


        $('#c_solicitante').prop('disabled',false);
        $('#c_tipogasto').prop('disabled',false);
        $('#c_moneda').prop('disabled',false);
        $('#c_proveedores').prop('disabled',false);
        $('#c_almacen').prop('disabled',false);
        $('#c_productos').html('<option value="0">Seleccione</option>'); 
        $('#moneda_tc').prop('disabled',false);
        $('#comment').prop('disabled', false);
        $('#btn_savequit').prop('disabled', false);
        $('#btn_authquit').prop('disabled', false);
        $('#moneda_tc').prop('disabled', false);
        $('#date_entrega').prop('disabled', false);
        $('#checkbox').prop('disabled', false);
        $('#btn_addProd').prop('disabled', false);
        $('#opciones_2').prop('disabled', false);

        $('#nofactrec').prop('disabled', false);
        $('#date_recepcion').prop('disabled', false);
        $('#impfactrec').prop('disabled', false);

        $('#btns .btn').prop('disabled', false);


        $('#c_solicitante').find('option[value="0"]').prop('selected', true); 
        $('#c_solicitante').select2();
        $('#c_tipogasto').find('option[value="0"]').prop('selected', true); 
        $('#c_tipogasto').select2();
        $('#c_moneda').find('option[value="0"]').prop('selected', true); 
        $('#c_moneda').select2();
        $('#c_proveedores').find('option[value="0"]').prop('selected', true); 
        $('#c_proveedores').select2();
        $('#c_almacen').find('option[value="0"]').prop('selected', true); 
        $('#c_almacen').select2();
        $('#c_productos').html('<option value="0">Seleccione</option>'); 
        $('#c_productos').select2();
        $('#c_productos').prop('disabled',true);
        $('#moneda_tc').css('display','none');
        $('#moneda_tc').val('');
        $('#panel_tabla').css('display','none');
        $('#c_proveedores').prop('disabled',false);
        $('#c_almacen').prop('disabled',false);
        enabled_btn('#btn_savequit','Solicitar aclaracion');
        enabled_btn('#btn_addProd','Agregar producto');

        $('#buttonFactura').css('display','block');
        $('#factura').css('display','block');

    }

    function addseries(idProd){
        cantant=$("#cantr_"+idProd+"_prod").val();
        cantseries = $('#modalcantrecibida').val();
        txtcant=$('#tr_'+idProd+' td:eq(5)').text();
        if(cantseries>0){

            if( (cantant*1)+(cantseries*1)>(txtcant*1) ){
                alert('La cantidad recibida no puede superar a la cantidad solicitada en la orden de compra');
                $('#modalcantrecibida').val( (txtcant-cantant) );
                return false;
            }
            divseries='';
            for (var i=0; i<cantseries; i++) {
                divseries+='<div class="sprod" class="col-sm-12" style="padding:15px;">\
                        <label class="col-sm-6 control-label text-left">No serie '+((i*1)+1)+':</label>\
                        <div class="col-sm-6">\
                            <input class="inpsprod" type="text" >\
                        </div>\
                    </div>';
            };
            $('#seriesprods__'+idProd).html(divseries);
        }else{
            alert('La cantidad de recepcion deber ser mayor a 0');
        }

    }

    function quitafactasoc(xmlfile){
        $.ajax({
            url:"ajax.php?c=compras&f=a_quitafactasoc",
            type: 'POST',
            data:{xmlfile:xmlfile},
            success: function(r){
                if(r==1){
                    alert('No se pudo desasociar esta factura')
                }
                if(r==0){
                    $('#resultasoc').html('');
                }
            }
        });

    }

    function addseriespedis(){
        cantseries = $('#modalcantrecibida').val();
        if(cantseries>0){
            divseries='';
            x=0;
            for (var i=0; i<cantseries; i++) {
                if(x==0){
                    sty='background-color:#ffffff';
                    x=1;
                }else{
                    sty='background-color:#f7f7f7';
                    x=0;
                }
                divseries+='<div class="col-sm-12" style="padding-top:10px; margin-top:10px; border-top:1px solid #d7d7d7;">\
                        <label class="col-sm-6 control-label text-left">No serie '+((i*1)+1)+':</label>\
                        <div class="col-sm-6">\
                            <input id="series" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">No. pedimento:</label>\
                        <div class="col-sm-6">\
                            <input id="nopedimento" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Aduana:</label>\
                        <div class="col-sm-6">\
                            <input id="aduanatext" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">No. aduana:</label>\
                        <div class="col-sm-6">\
                            <input id="noaduana" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Tipo de cambio:</label>\
                        <div class="col-sm-6">\
                            <input id="tipcambio" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Fecha de pedimento:</label>\
                        <div class="col-sm-6">\
                            <input id="datepedimento" type="text" >\
                        </div>\
                    </div>';
            };
            $('#seriesprods').html(divseries);
        }else{
            alert('La cantidad de recepcion deber ser mayor a 0');
        }

    }

    function checksinexist(chx,div,idProd){//
        if ($('#chx'+chx).is(':checked')) {
            $('#cambio').css('display','none');
            $('#cambio_ss').css('display','block');

        }else{
            $('#cambio_ss').css('display','none');
            $('#cambio').css('display','block');

        }
    }

    function recibirProducto(idProd,modo,txtprod,txtcant,mod,cadcar){
ss=$('#ss').val();

cantant=$("#cantr_"+idProd+"_prod").val();
if(cantant>0){
    /*
    canterioridad='<div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left"><font color="#bbbbbb">Cantidad recibida anterioridad:</font></label>\
                        <div class="col-sm-6">\
                          <input id="modalcantrecibidap" class="numeric" type="text" value="'+cantant+'" readonly="readonly" >\
                        </div>\
                    </div>';
                    */
    canterioridad='';
}else{
    canterioridad='';
}
if(modo==5){
    $.ajax({
        async: false,
        url:"ajax.php?c=ventas&f=a_getPedimentosLotes",
        type: 'POST',
        dataType: 'json',
        data:{idProd:idProd,modo:modo,cadcar:cadcar},
        success: function(r){
            console.log(r);
            options='';
            $.each(r, function( k, v ) {
                options+='<option value="'+v.idPedimentoLote+'">'+v.numero+' ('+v.cantidad+')</option>';
            });
            
           // $('#pedimentos').html(options);
            
        }
    });
divbody='<div class="row">\
                    <input type="hidden" id="hmprod" value="'+idProd+'">\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Producto:</label>\
                        <div class="col-sm-6">\
                          '+txtprod+'\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Caracteristicas:</label>\
                        <div class="col-sm-6">\
                          <span class="label label-primary" style="cursor:pointer;">Pedimentos y lotes</span>\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Cantidad solicitada:</label>\
                        <div class="col-sm-6">\
                          <input class="numeric" type="text" value="'+txtcant+'" readonly="readonly">\
                        </div>\
                    </div>\
                    '+canterioridad+'\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Pedimento/Lote:</label>\
                        <div class="col-sm-6">\
                            <select id="pedimentoslote" multiple="" onchange="cambiopedimentolote('+idProd+');">\
                                '+options+'\
                            </select>\
                        </div>\
                    </div>\
                    <div id="newpedimentoslote">\
                    </div>\
                </div>';

            $('#bodyespecial').html(divbody);


            $("#pedimentoslote").select2({ width: '100%' }); 

            $('#modalcantrecibida').numeric();
            cadena=$('#hiddensProds input[id="'+idProd+'"][ch="'+cadcar+'"]').val();   
            if(typeof cadena != "undefined"){
                cadsplit = cadena.split('->-');
                $('#modalcantrecibida').val(cadsplit[2]);
                divseries='';
                seriesplit = cadsplit[3].split(',');
                cantscoma = cadsplit[4].split(',');
                console.log(seriesplit);
                $("#pedimentoslote").val(seriesplit).trigger("change");
                i=0;
                $("#newpedimentoslote").find('#divpedimentos div').each(function( index ) {
                    cantsalmacen=cantscoma[i].split('-');

                    $(this).find('input').val(cantsalmacen[2]);
                    i++;
                });

            }

}
if(modo==0){
    if(ss==1){

        $.ajax({
            async: false,
            url:"ajax.php?c=ventas&f=a_getAlmacenes",
            type: 'POST',
            dataType: 'json',
            success: function(r){
                options_ss='<option value="0">Seleccione un almacen</option>';
                $.each(r, function( k, v ) {
                    options_ss+='<option value="'+v.id+'">'+v.nombre+'</option>';
                });
            }
        });

        divss='<div class="col-sm-12" style="padding-top:10px;">\
            <label class="col-sm-6 control-label text-left">Sacar producto sin existencia:</label>\
            <div class="col-sm-6">\
              <input id="chx0" type="checkbox" style="cursor:pointer;" onclick="checksinexist(0,\'existencias\','+idProd+');">\
            </div>\
        </div>';
    }else{
        divss='';
        options_ss='';
    }
    deten=0;
    $.ajax({
        async: false,
        url:"ajax.php?c=ventas&f=a_getExistencias",
        type: 'POST',
        dataType: 'json',
        data:{idProd:idProd,modo:modo,cadcar:cadcar},
        success: function(r){
            if(r==null && ss==0){
                alert('No hay existencias en ningun almacen');
                deten=1;
                return false;
            }else{
                options='';
                $.each(r, function( k, v ) {
                    options+='<option value="'+v.idAlmacen+'">'+v.almacen+' ('+v.cantidad+')</option>';
                });
            }
           // $('#pedimentos').html(options);
            
        }
    });
    if(deten==1){ return false; }
divbody='<div class="row">\
                    <input type="hidden" id="hmprod" value="'+idProd+'">\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Producto:</label>\
                        <div class="col-sm-6">\
                          '+txtprod+'\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Cantidad solicitada:</label>\
                        <div class="col-sm-6">\
                          <input class="numeric" type="text" value="'+txtcant+'" readonly="readonly">\
                        </div>\
                    </div>\
                    '+divss+'\
                    '+canterioridad+'\
                    <div id="cambio">\
                        <div class="col-sm-12" style="padding-top:10px;">\
                            <label class="col-sm-6 control-label text-left">Almacen:</label>\
                            <div class="col-sm-6">\
                                <select id="existencias" multiple="" onchange="cambioexistencias('+idProd+');">\
                                    '+options+'\
                                </select>\
                            </div>\
                        </div>\
                        <div id="newexistencias">\
                        </div>\
                    </div>\
                    <div id="cambio_ss" style="display:none;">\
                        <div class="col-sm-12" style="padding-top:10px;">\
                            <label class="col-sm-6 control-label text-left">Almacen:</label>\
                            <div class="col-sm-6">\
                                <select id="existencias_ss"  onchange="cambioexistencias_ss('+idProd+');">\
                                    '+options_ss+'\
                                </select>\
                            </div>\
                        </div>\
                        <div id="newexistencias_ss">\
                        </div>\
                    <div>\
                </div>';

            $('#bodyespecial').html(divbody);

            $('#datepedimento').datepicker({
                    format: "yyyy-mm-dd",
                    language: "es"
            });
            $("#existencias").select2({ width: '100%' });
            $("#existencias_ss").select2({ width: '100%' }); 

            $('#tipcambio').numeric();
            cadena=$('#hiddensProds input[id="'+idProd+'"][ch="'+cadcar+'"]').val();        
            if(typeof cadena != "undefined"){
                cadsplit = cadena.split('->-');

                $('#modalcantrecibida').val(cadsplit[2]);
                divseries='';
                seriesplit = cadsplit[3].split(',');
                cantscoma = cadsplit[4].split(',');
                console.log(seriesplit);
                if(cadsplit[5]==1){
                    $("#chx0").trigger("click");
                    $("#existencias_ss").val(seriesplit).trigger("change");
                    i=0;
                    $(".quantity_ss").val(cadsplit[2]); 
                }else{
                    
                    $("#existencias").val(seriesplit).trigger("change");
                    i=0;
                    $("#newexistencias").find('#divexistencias div').each(function( index ) {
                        cantsalmacen=cantscoma[i].split('-');
                        $(this).find('input').val(cantsalmacen[2]);
                        i++;
                    });
                }

            }
}
if(modo==4){

    $.ajax({
        async: false,
        url:"ajax.php?c=ventas&f=a_getPedimentos",
        type: 'POST',
        dataType: 'json',
        data:{idProd:idProd,modo:modo,cadcar:cadcar},
        success: function(r){
            console.log(r);
            options='';
            $.each(r, function( k, v ) {
                options+='<option value="'+v.idPedimento+'">'+v.numero+' ('+v.cantidad+')</option>';
            });
            
           // $('#pedimentos').html(options);
            
        }
    });
divbody='<div class="row">\
                    <input type="hidden" id="hmprod" value="'+idProd+'">\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Producto:</label>\
                        <div class="col-sm-6">\
                          '+txtprod+'\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Caracteristicas:</label>\
                        <div class="col-sm-6">\
                          <span class="label label-primary" style="cursor:pointer;">Pedimentos</span>\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Cantidad solicitada:</label>\
                        <div class="col-sm-6">\
                          <input class="numeric" type="text" value="'+txtcant+'" readonly="readonly">\
                        </div>\
                    </div>\
                    '+canterioridad+'\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">No. pedimento:</label>\
                        <div class="col-sm-6">\
                            <select id="pedimentos" multiple="" onchange="cambiopedimento('+idProd+');">\
                                '+options+'\
                            </select>\
                        </div>\
                    </div>\
                    <div id="newpedimentos">\
                    </div>\
                </div>';

            $('#bodyespecial').html(divbody);

            $('#datepedimento').datepicker({
                    format: "yyyy-mm-dd",
                    language: "es"
            });
            $("#pedimentos").select2({ width: '100%' }); 
            $('#tipcambio').numeric();
            cadena=$('#hiddensProds input[id="'+idProd+'"][ch="'+cadcar+'"]').val();   
            if(typeof cadena != "undefined"){
                cadsplit = cadena.split('->-');
                $('#modalcantrecibida').val(cadsplit[2]);
                divseries='';
                seriesplit = cadsplit[3].split(',');
                cantscoma = cadsplit[4].split(',');
                console.log(seriesplit);
                $("#pedimentos").val(seriesplit).trigger("change");
                i=0;
                $("#newpedimentos").find('#divpedimentos div').each(function( index ) {
                    cantsalmacen=cantscoma[i].split('-');

                    $(this).find('input').val(cantsalmacen[2]);
                    i++;
                });

            }
}
if(modo==1){
    $.ajax({
        async: false,
        url:"ajax.php?c=ventas&f=a_getLotes",
        type: 'POST',
        dataType: 'json',
        data:{idProd:idProd,modo:modo,cadcar:cadcar},
        success: function(r){
            console.log(r);
            options='';
            $.each(r, function( k, v ) {
                options+='<option value="'+v.idLote+'">'+v.numero+' ('+v.cantidad+')</option>';
            });
            
           // $('#pedimentos').html(options);
            
        }
    });
divbody='<div class="row">\
<input type="hidden" id="hmprod" value="'+idProd+'">\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Producto:</label>\
                        <div class="col-sm-6">\
                          '+txtprod+'\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Caracteristicas:</label>\
                        <div class="col-sm-6">\
                          <span class="label label-primary" style="cursor:pointer;">Lotes</span>\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Cantidad solicitada:</label>\
                        <div class="col-sm-6">\
                          <input class="numeric" type="text" value="'+txtcant+'" readonly="readonly">\
                        </div>\
                    </div>\
                    '+canterioridad+'\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">No. lote:</label>\
                        <div class="col-sm-6">\
                            <select id="lotes" multiple="" onchange="cambiolote('+idProd+');">\
                                '+options+'\
                            </select>\
                        </div>\
                    </div>\
                    <div id="newlotes">\
                    </div>\
                    <div id="divlotes" class="col-sm-12" style="padding-top:10px; display:none;">\
                        <label class="col-sm-6 control-label text-left">Series:</label>\
                        <div class="col-sm-6">\
                          <input  class="numeric" type="text" >\
                        </div>\
                    </div>\
                </div>';

            $('#bodyespecial').html(divbody);
            $('#lotes').select2({ width: '100%' });

            $('#datelotefab').datepicker({
                    format: "yyyy-mm-dd",
                    startDate:  "<?php echo $sd; ?>",
                    language: "es"
            });

            $('#datelotecad').datepicker({
                    format: "yyyy-mm-dd",
                    startDate:  "<?php echo $sd; ?>",
                    language: "es"
            });

            $('#modalcantrecibida').numeric();
            cadena=$('#hiddensProds input[id="'+idProd+'"][ch="'+cadcar+'"]').val();   
            if(typeof cadena != "undefined"){
                cadsplit = cadena.split('->-');
                $('#modalcantrecibida').val(cadsplit[2]);
                divseries='';
                seriesplit = cadsplit[3].split(',');
                cantscoma = cadsplit[4].split(',');
                console.log(seriesplit);
                $("#lotes").val(seriesplit).trigger("change");
                i=0;
                $("#newlotes").find('#divlotes div').each(function( index ) {
                    cantsalmacen=cantscoma[i].split('-');

                    $(this).find('input').val(cantsalmacen[2]);
                    i++;
                });

            }
}
if(modo==2){
    $.ajax({
        async: false,
        url:"ajax.php?c=ventas&f=a_getSeriesProd",
        type: 'POST',
        dataType: 'json',
        data:{idProd:idProd,modo:modo,cadcar:cadcar},
        success: function(r){
            console.log(r);
            options='';
            $.each(r, function( k, v ) {
                options+='<option id="ser_'+v.serie2+'" value="'+v.idSerie+'">'+v.serie+'</option>';
            });
            
           // $('#pedimentos').html(options);
           
        }
    });
divbody='<div class="row" style="overflow-x:hidden; overflow-y:scroll; height:480px;width:600px;">\
<input type="hidden" id="hmprod" value="'+idProd+'">\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Producto:</label>\
                        <div class="col-sm-6">\
                          '+txtprod+'\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Caracteristicas:</label>\
                        <div class="col-sm-6">\
                          <span class="label label-primary" style="cursor:pointer;">Series</span>\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Cantidad solicitada:</label>\
                        <div class="col-sm-6">\
                          <input class="numeric" type="text" value="'+txtcant+'" readonly="readonly">\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">No. series:</label>\
                        <div class="col-sm-6">\
                            <select id="series2" multiple="">\
                                '+options+'\
                            </select>\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Cantidad a enviar:</label>\
                        <div class="col-sm-6">\
                          <input id="modalcantrecibida" class="numeric" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <textarea class="col-md-9" id="txtareaSeries" placeholder="Ingrese las series separadas por coma sin espacio o mediante lector" cols="12" rows="2"></textarea>\
                        <div class="col-md-3">\
                            <input class="col-md-9" id="canSer" value="" align="center" /><button onclick="seriesComa('+idProd+');"> Procesar</button>\
                        </div>\
                    </div>\
                    <div id="seriesprods__'+idProd+'">\
                    </div>\
                </div>';

            $('#bodyespecial').html(divbody);
            $("#series2").select2({ width: '100%' });

           
                $('#txtareaSeries').keypress(function(e){
                if(e.keyCode == 13)
                {
                    e.preventDefault();
                    $("#txtareaSeries").val($("#txtareaSeries").val() + ',');
                }
                });
            //$("#series2").select2({closeOnSelect:false});// ch@


            cadena=$('#hiddensProds input[id="'+idProd+'"][ch="'+cadcar+'"]').val();             
            if(typeof cadena != "undefined"){
                cadsplit = cadena.split('->-');
                $('#modalcantrecibida').val(cadsplit[2]);
                divseries='';
                seriesplit = cadsplit[3].split(',');
                console.log(seriesplit);
                $("#series2").val(seriesplit).trigger("change");


            }
            

}

if(modo==3){
    $.ajax({
        async: false,
        url:"ajax.php?c=ventas&f=a_getPedimentosProd",
        type: 'POST',
        dataType: 'json',
        data:{idProd:idProd,modo:modo,cadcar:cadcar},
        success: function(r){
            console.log(r);
            options='';
            $.each(r, function( k, v ) {
                options+='<option value="'+v.idPedimento+'">'+v.numero+' ('+v.cantidad+')</option>';
            });
            
           // $('#pedimentos').html(options);
            
        }
    });

divbody='<div class="row">\
<input type="hidden" id="hmprod" value="'+idProd+'">\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Producto:</label>\
                        <div class="col-sm-6">\
                          '+txtprod+'\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Caracteristicas:</label>\
                        <div class="col-sm-6">\
                          <span class="label label-primary" style="cursor:pointer;">Series - Pedimentos</span>\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Cantidad solicitada:</label>\
                        <div class="col-sm-6">\
                          <input class="numeric" type="text" value="'+txtcant+'" readonly="readonly">\
                        </div>\
                    </div>\
                    '+canterioridad+'\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">No. pedimento:</label>\
                        <div class="col-sm-6">\
                            <select id="spedimentos" multiple="" onchange="muestraSeriesPed('+idProd+');">\
                                '+options+'\
                            </select>\
                        </div>\
                    </div>\
                    <div id="newcants">\
                    </div>\
                    <div id="divseries" class="col-sm-12" style="padding-top:10px; display:none;">\
                        <label class="col-sm-6 control-label text-left">Series:</label>\
                        <div class="col-sm-6">\
                          <input  class="numeric" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Cantidad a enviar:</label>\
                        <div class="col-sm-6">\
                          <input id="modalcantrecibida" class="numeric" type="text" >\
                        </div>\
                    </div>\
                    <div id="seriesprods__'+idProd+'">\
                    </div>\
                </div>';

            $('#bodyespecial').html(divbody);
            $("#spedimentos").select2({ width: '100%' }); 

            $('#datepedimento').datepicker({
                    format: "yyyy-mm-dd",
                    language: "es"
            });

            cadena=$('#hiddensProds input[id="'+idProd+'"][ch="'+cadcar+'"]').val();           
            if(typeof cadena != "undefined"){
                cadsplit = cadena.split('->-');
                $('#modalcantrecibida').val(cadsplit[2]);
                divseries='';
                pedsplit = cadsplit[3].split(',');
                seriesplit = cadsplit[4].split(',');
                console.log(seriesplit);
                $("#spedimentos").val(pedsplit).trigger("change");
                
                setTimeout(function(){ 
                    $("#selseries").val(seriesplit).trigger("change");
                }, 3000);

                


            }
}
        
        mmod = $('#modal-recep').modal({
                        backdrop: 'static',
                        keyboard: false, 
                        show: true
                });

        $('#modal-recep-uno').on('click',function(){
            txtcant=$("#tr_"+idProd+"[ch='"+cadcar+"'] td:eq(4)").text();
            if(modo!=1 && modo!=4 && modo!=0 && modo!=5){
                modalcantrecibida=$('#modalcantrecibida').val();


                cantant=$("#cantr_"+idProd+"_prod").val();
                cantseries = $('#modalcantrecibida').val();

                if(cantseries>0){
                    if( (cantseries*1)>(txtcant*1) ){
                    //if( (cantant*1)+(cantseries*1)>(txtcant*1) ){
                        alert('La cantidad recibida no puede superar a la cantidad solicitada en la orden de venta');
                        $('#modalcantrecibida').val( (txtcant-cantant) );
                        return false;
                    }
                }else{
                    alert('La cantidad a enviar debe ser mayor a 0');
                    return false;
                }
            }

            if(modo==0){

                if($('#chx'+modo).is(':checked')) {
                    $("#cantr_"+idProd+"_prod"+"[chp='"+cadcar+"']").attr('sinexi', '1');
                    existencias=$('#existencias_ss').val();

                    //modalcantrecibida=0;
                    cantsexistencias='';
                    //$( ".quantity_ss" ).each(function( index ) {
                        modalcantrecibida=$('.quantity_ss').val()*1;
                        cantsexistencias=existencias+'-100-'+modalcantrecibida+',';
                    //});
                    
                    if(existencias==0){
                        alert('Tienes que seleccionar un almacen');
                        return false;
                    }

                    if(modalcantrecibida==0){
                        alert('La cantidad a enviar no puede ser 0');
                        return false;
                    }

                    

                    if(modalcantrecibida>(txtcant*1)){
                        alert('La cantidad recibida no puede superar a la cantidad solicitada en la orden de venta');
                        return false;
                    }

                        $.ajax({
                        url:"ajax.php?c=ventas&f=a_modalRecepcion",
                        type: 'POST',
                        data:{idProd:idProd,modo:modo,modalcantrecibida:modalcantrecibida,existencias:existencias,cantsexistencias:cantsexistencias,cadcar:cadcar,ss:1},
                        success: function(r){
                            $('#modal-recep').modal('hide');
                            $('#hiddensProds input[id="'+idProd+'"][ch="'+cadcar+'"]').remove();
                            $('#hiddensProds').append('<input ch="'+cadcar+'" id="'+idProd+'" type="hidden" value="'+r+'" >');

                            if(typeof r != "undefined"){
                                 rsplit = r.split('->-');
                            }
                           // alert(idProd);
                            $("#cantr_"+idProd+"_prod"+"[chp='"+cadcar+"']").val(rsplit[2]);

                            refreshCants(idProd,cadcar);

                        }
                    });

                }else{
                    $("#cantr_"+idProd+"_prod"+"[chp='"+cadcar+"']").attr('sinexi', '0');
                    existencias=$('#existencias').val();
                    modalcantrecibida=0;
                    cantsexistencias='';
                    $( ".quantity" ).each(function( index ) {
                        modalcantrecibida=modalcantrecibida+($(this).val()*1);
                        cantsexistencias+=$(this).attr('data')+'-'+$(this).val()+',';
                    });
                    
                    if(modalcantrecibida==0){
                        alert('La cantidad a enviar no puede ser 0');
                        return false;
                    }


                    if(modalcantrecibida>(txtcant*1)){
                        alert('La cantidad recibida no puede superar a la cantidad solicitada en la orden de venta');
                        return false;
                    }

               


                        $.ajax({
                        url:"ajax.php?c=ventas&f=a_modalRecepcion",
                        type: 'POST',
                        data:{idProd:idProd,modo:modo,modalcantrecibida:modalcantrecibida,existencias:existencias,cantsexistencias:cantsexistencias,cadcar:cadcar,ss:0},
                        success: function(r){
                            $('#modal-recep').modal('hide');
                            $('#hiddensProds input[id="'+idProd+'"][ch="'+cadcar+'"]').remove();
                            $('#hiddensProds').append('<input ch="'+cadcar+'" id="'+idProd+'" type="hidden" value="'+r+'" >');

                            if(typeof r != "undefined"){
                                 rsplit = r.split('->-');
                            }
                           // alert(idProd);
                            $("#cantr_"+idProd+"_prod"+"[chp='"+cadcar+"']").val(rsplit[2]);

                            refreshCants(idProd,cadcar);

                        }
                    });
                }
            }

            if(modo==1){
                lotes=$('#lotes').val();
                modalcantrecibida=0;
                cantslotes='';
                $( ".quantity" ).each(function( index ) {
                    modalcantrecibida=modalcantrecibida+($(this).val()*1);
                    cantslotes+=$(this).attr('data')+'-'+$(this).val()+',';
                });

                if(modalcantrecibida==0){
                    alert('La cantidad a enviar no puede ser 0');
                    return false;
                }


                if(modalcantrecibida>(txtcant*1)){
                    alert('La cantidad recibida no puede superar a la cantidad solicitada en la orden de venta');
                    return false;
                }


                    $.ajax({
                    url:"ajax.php?c=ventas&f=a_modalRecepcion",
                    type: 'POST',
                    data:{idProd:idProd,modo:modo,modalcantrecibida:modalcantrecibida,lotes:lotes,cantslotes:cantslotes,cadcar:cadcar},
                    success: function(r){
                        $('#modal-recep').modal('hide');
                        $('#hiddensProds input[id="'+idProd+'"][ch="'+cadcar+'"]').remove();
                        $('#hiddensProds').append('<input ch="'+cadcar+'" id="'+idProd+'" type="hidden" value="'+r+'" >');

                        if(typeof r != "undefined"){
                             rsplit = r.split('->-');
                        }
                       // alert(idProd);
                        $("#cantr_"+idProd+"_prod"+"[chp='"+cadcar+"']").val(rsplit[2]);

                        recalcula();

                    }
                });
            }
            if(modo==2){
                    series=$('#series2').val();
                    if(series==null){
                        alert('Tienes que seleccionar las series de tus productos a enviar');
                        return false;
                    }
                    console.log(series);
                    noseries= series.length;
                    
                    if(modalcantrecibida<noseries || modalcantrecibida>noseries){
                        alert('La cantidad de productos a enviar no concuerda con la cantidad de series');
                        return false;
                    }

                    if(modalcantrecibida>(txtcant*1)){
                        alert('La cantidad recibida no puede superar a la cantidad solicitada en la orden de venta');
                        return false;
                    }

                    $.ajax({
                    url:"ajax.php?c=ventas&f=a_modalRecepcion",
                    type: 'POST',
                    data:{idProd:idProd,modo:modo,modalcantrecibida:modalcantrecibida,series:series,cadcar:cadcar},
                    success: function(r){
                        $('#modal-recep').modal('hide');
                        $('#hiddensProds input[id="'+idProd+'"][ch="'+cadcar+'"]').remove();
                        $('#hiddensProds').append('<input ch="'+cadcar+'" id="'+idProd+'" type="hidden" value="'+r+'" >');

                        if(typeof r != "undefined"){
                             rsplit = r.split('->-');
                        }
                       // alert(idProd);
                        $("#cantr_"+idProd+"_prod"+"[chp='"+cadcar+"']").val(rsplit[2]);

                        recalcula();

                    }
                });
            }
            if(modo==3){
               
                    pedimentos=$('#spedimentos').val();
                    series=$('#selseries').val();
                    if(series==null){
                        alert('Tienes que seleccionar las series de tus productos a enviar');
                        return false;
                    }
                    console.log(series);
                    noseries= series.length;
                    
                    if(modalcantrecibida<noseries || modalcantrecibida>noseries){
                        alert('La cantidad de productos a enviar no concuerda con la cantidad de series');
                        return false;
                    }

                    if(modalcantrecibida>(txtcant*1)){
                        alert('La cantidad recibida no puede superar a la cantidad solicitada en la orden de venta');
                        return false;
                    }

                   

              
                    $.ajax({
                    url:"ajax.php?c=ventas&f=a_modalRecepcion",
                    type: 'POST',
                    data:{idProd:idProd,modo:modo,modalcantrecibida:modalcantrecibida,pedimentos:pedimentos,series:series,cadcar:cadcar},
                    success: function(r){
                        $('#modal-recep').modal('hide');
                        $('#hiddensProds input[id="'+idProd+'"][ch="'+cadcar+'"]').remove();
                        $('#hiddensProds').append('<input ch="'+cadcar+'" id="'+idProd+'" type="hidden" value="'+r+'" >');

                        if(typeof r != "undefined"){
                             rsplit = r.split('->-');
                        }


                        $("#cantr_"+idProd+"_prod"+"[chp='"+cadcar+"']").val(rsplit[2]);
                        recalcula();
                    }
                });
            }
            if(modo==4){
                pedimentos=$('#pedimentos').val();
                modalcantrecibida=0;
                cantspedimentos='';
                $( ".quantity" ).each(function( index ) {
                    modalcantrecibida=modalcantrecibida+($(this).val()*1);
                    cantspedimentos+=$(this).attr('data')+'-'+$(this).val()+',';
                });

                if(modalcantrecibida==0){
                    alert('La cantidad a enviar no puede ser 0');
                    return false;
                }

                if(modalcantrecibida>(txtcant*1)){
                    alert('La cantidad recibida no puede superar a la cantidad solicitada en la orden de venta');
                    return false;
                }

                    $.ajax({
                    url:"ajax.php?c=ventas&f=a_modalRecepcion",
                    type: 'POST',
                    data:{idProd:idProd,modo:modo,modalcantrecibida:modalcantrecibida,pedimentos:pedimentos,cantspedimentos:cantspedimentos,cadcar:cadcar},
                    success: function(r){
                        $('#modal-recep-uno').unbind();
                        $('#modal-recep-dos').unbind();
                        $('#modal-recep').modal('hide');
                        $('#hiddensProds input[id="'+idProd+'"][ch="'+cadcar+'"]').remove();
                        $('#hiddensProds').append('<input ch="'+cadcar+'" id="'+idProd+'" type="hidden" value="'+r+'" >');

                        if(typeof r != "undefined"){
                             rsplit = r.split('->-');
                        }
                       // alert(idProd);


                        $("#cantr_"+idProd+"_prod"+"[chp='"+cadcar+"']").val(rsplit[2]);
                        recalcula();
                    }
                });
            }
            if(modo==5){
                pedimentoslote=$('#pedimentoslote').val();
                modalcantrecibida=0;
                cantspedimentos='';
                $( ".quantity" ).each(function( index ) {

                    modalcantrecibida=modalcantrecibida+($(this).val()*1);
                    cantspedimentos+=$(this).attr('data')+'-'+$(this).val()+',';
                });

                if(modalcantrecibida==0){
                    alert('La cantidad a enviar no puede ser 0');
                    return false;
                }

                if(modalcantrecibida>(txtcant*1)){
                    alert('La cantidad recibida no puede superar a la cantidad solicitada en la orden de venta');
                    return false;
                }

                    $.ajax({
                    url:"ajax.php?c=ventas&f=a_modalRecepcion",
                    type: 'POST',
                    data:{idProd:idProd,modo:modo,modalcantrecibida:modalcantrecibida,pedimentoslote:pedimentoslote,cantspedimentos:cantspedimentos,cadcar:cadcar},
                    success: function(r){
                        $('#modal-recep-uno').unbind();
                        $('#modal-recep-dos').unbind();
                        $('#modal-recep').modal('hide');
                        $('#hiddensProds input[id="'+idProd+'"][ch="'+cadcar+'"]').remove();
                        $('#hiddensProds').append('<input ch="'+cadcar+'" id="'+idProd+'" type="hidden" value="'+r+'" >');

                        if(typeof r != "undefined"){
                             rsplit = r.split('->-');
                        }
                       // alert(idProd);


                        $("#cantr_"+idProd+"_prod"+"[chp='"+cadcar+"']").val(rsplit[2]);
                        recalcula();
                    }
                });
            }

            $('#modal-recep-uno').unbind();
            $('#modal-recep-dos').unbind();
        });

        $('#modal-recep-dos').one('click',function(){
            $('#modal-recep').modal('hide');
            $('#modal-recep-uno').unbind();
        });


    

        /*.one('click', '#modal-recep-uno', function(){
            modalcantrecibida=$('#modalcantrecibida').val();
            if(modo==1){
                nolote=$('#nolote').val();
                datelotefab=$('#datelotefab').val();
                datelotecad=$('#datelotecad').val();
                    $.ajax({
                    url:"ajax.php?c=compras&f=a_modalRecepcion",
                    type: 'POST',
                    data:{idProd:idProd,modo:modo,modalcantrecibida:modalcantrecibida,nolote:nolote,datelotefab:datelotefab,datelotecad:datelotecad},
                    success: function(r){
                        $('#modal-recep').modal('hide');
                        $('#hiddensProds input[id='+idProd+']').remove();
                        $('#hiddensProds').append('<input id="'+idProd+'" type="hidden" value="'+r+'" >');

                        if(typeof r != "undefined"){
                             rsplit = r.split('->-');
                        }
                        alert(idProd);
                        $('#cantr_'+idProd+'_prod').val(rsplit[2]);

                    }
                });
            }
            if(modo==4){
                nopedimento=$('#nopedimento').val();
                aduanatext=$('#aduanatext').val();
                noaduana=$('#noaduana').val();
                tipcambio=$('#tipcambio').val();
                datepedimento=$('#datepedimento').val();
                    $.ajax({
                    url:"ajax.php?c=compras&f=a_modalRecepcion",
                    type: 'POST',
                    data:{idProd:idProd,modo:modo,modalcantrecibida:modalcantrecibida,nopedimento:nopedimento,aduanatext:aduanatext,noaduana:noaduana,tipcambio:tipcambio,datepedimento:datepedimento},
                    success: function(r){
                        $('#modal-recep-uno').unbind();
                        $('#modal-recep-dos').unbind();
                        $('#modal-recep').modal('hide');
                        $('#hiddensProds input[id='+idProd+']').remove();
                        $('#hiddensProds').append('<input id="'+idProd+'" type="hidden" value="'+r+'" >');

                        if(typeof r != "undefined"){
                             rsplit = r.split('->-');
                        }
                        alert(idProd);
                        $('#cantr_'+idProd+'_prod').val(rsplit[2]);
                        
                    }
                });
            }
            
        }).one('click', '#modal-recep-dos', function(){
            console.log(mmod);
            $('#modal-recep-uno').unbind('click');
            $('#modal-recep-uno').trigger('click');
            $('#modal-recep-dos').unbind();
            $('#modal-recep').modal('hide');
            

        });
*/
                    
    }

    function seriesComa(idProd){
        var series = $("#txtareaSeries").val();
        var coma = series.substr(-1);
        if(coma == ','){
            series = series.slice(0,-1);
        }
        //alert(series);
        var array = series.split(',');
        console.log(array);
        var i = 0;
        var cadena = '';
        
        for (i=0; i<=array.length - 1; i++) {
                var serie = $("#ser_"+array[i]+"").val();
                var serieT = $("#ser_"+array[i]+"").text();
                if(serieT == ''){
                    alert('La serie '+array[i]+' No existe!!!');
                    return false;
                }
                cadena += serie+',';
                //alert(array[i]+' con valor '+serie+' texto '+serieT); 
            };
            cadena = cadena.slice(0,-1);
            cadena = cadena.split(',');
            $("#canSer").val(i);
            $("#series2").val(cadena).trigger("change");
    }  

    function cambioexistencias(idProd){
        existencias = $('#existencias').val();
        if(existencias!='' && existencias!=null){
            $('#newexistencias').html('');
            $.each(existencias, function( k, v ) {
                separa=v.split('-#*-');
                separa2=v.split('-');
 
                $('#newexistencias').append('<div id="divexistencias" class="col-sm-12" style="padding-top:10px;">\
                            <label class="col-sm-6 control-label text-left">Cant: '+separa[1]+':</label>\
                            <div class ="col-sm-6">\
                              <input class="quantity" data="'+separa2[0]+'-'+separa2[1]+'" type="number" min="1" max="'+separa2[1]+'" value="1"/>\
                            </div>\
                        </div>');
            });

            $('.quantity').numeric();

        }else{
            $('#newexistencias').html('');
        }
        
    }

    function cambioexistencias_ss(idProd){
        existencias = $('#existencias_ss').val();
        if(existencias!='' && existencias!=null && existencias!=0){
            $('#newexistencias_ss').html('');
            //$.each(existencias, function( k, v ) {
                //separa=v.split('-#*-');
                //separa2=v.split('-');
 
                $('#newexistencias_ss').append('<div id="divexistencias_ss" class="col-sm-12" style="padding-top:10px;">\
                            <label class="col-sm-6 control-label text-left">Cant:</label>\
                            <div class ="col-sm-6">\
                              <input class="quantity_ss"  type="number"  value="1"/>\
                            </div>\
                        </div>');
           // });

            $('.quantity_ss').numeric();

        }else{
            $('#newexistencias_ss').html('');
        }
        
    }

    function cambiopedimentolote(idProd){
        lotes = $('#pedimentoslote').val();
        if(lotes!='' && lotes!=null){
            $('#newpedimentoslote').html('');
            $.each(lotes, function( k, v ) {
                separa=v.split('-#*-');
                separa2=v.split('-');
 
                $('#newpedimentoslote').append('<div id="divpedimentos" class="col-sm-12" style="padding-top:10px;">\
                            <label class="col-sm-6 control-label text-left">Cant: '+separa[1]+':</label>\
                            <div class ="col-sm-6">\
                              <input class="quantity" data="'+separa2[0]+'-'+separa2[1]+'" type="number" min="1" max="'+separa2[2]+'" value="1"/>\
                            </div>\
                        </div>');
                $('.quantity').numeric();
            });

        }else{
            $('#newpedimentoslote').html('');
        }
        
    }

    function cambiopedimento(idProd){
        lotes = $('#pedimentos').val();
        if(lotes!='' && lotes!=null){
            $('#newpedimentos').html('');
            $.each(lotes, function( k, v ) {
                separa=v.split('-#*-');
                separa2=v.split('-');
 
                $('#newpedimentos').append('<div id="divpedimentos" class="col-sm-12" style="padding-top:10px;">\
                            <label class="col-sm-6 control-label text-left">Cant: '+separa[1]+':</label>\
                            <div class ="col-sm-6">\
                              <input class="quantity" data="'+separa2[0]+'-'+separa2[1]+'" type="number" min="1" max="'+separa2[2]+'" value="1"/>\
                            </div>\
                        </div>');
                $('.quantity').numeric();
            });

        }else{
            $('#newpedimentos').html('');
        }
        
    }

    function cambiolote(idProd){
        lotes = $('#lotes').val();
        console.log(lotes);
        if(lotes!='' && lotes!=null){
            $('#newlotes').html('');
            $.each(lotes, function( k, v ) {
                console.log(v);
                separa=v.split('-#*-');
                separa2=v.split('-');
 
                $('#newlotes').append('<div id="divlotes" class="col-sm-12" style="padding-top:10px;">\
                            <label class="col-sm-6 control-label text-left">Cant: '+separa[1]+':</label>\
                            <div class ="col-sm-6">\
                              <input class="quantity" data="'+separa2[0]+'-'+separa2[1]+'" type="number" min="1" max="'+separa2[2]+'" value="1"/>\
                            </div>\
                        </div>');
                $('.quantity').numeric();
            });

        }else{
            $('#newlotes').html('');
        }
        
    }

    function muestraSeriesPed(idProd){

        pedimentos = $('#spedimentos').val();
        if(pedimentos!='' && pedimentos!=null){
            $('#divseries').find('div').html('Cargando...');
            $('#divseries').css('display','block');
            $.ajax({
                url:"ajax.php?c=ventas&f=a_getSeriesPed",
                type: 'POST',
                dataType:'JSON',
                data:{pedimentos:pedimentos,idProd:idProd},
                success: function(r){
                    console.log(r);
                    options='<select id="selseries" multiple="">';
                    $.each(r, function( k, v ) {
                        options+='<option value="'+v.idSerie+'">'+v.serie+'</option>';
                    });
                    options+='</select>';
                    $('#divseries').find('div').html(options);
                    $('#selseries').select2({ width: '100%' });
                    $('#divseries').css('display','block');
                }
            });
        }else{
            $('#divseries').find('div').html('');
            $('#divseries').css('display','none');
        }
        
    }

    function nreq(){

        span = $('#ph').find('span').text();
        if($('#nreq').is(':visible') && span!='Visualizar venta') {
            $('#modal-conf1').modal('show');
            $('#modal-btnconf1-uno').on('click',function(){
                $('#modal-conf1').modal('hide');
                $('#nreq').css('display','none');
                $('#nreq_load').css('display','block');
                $.ajax({
                    url:"ajax.php?c=compras&f=a_nuevarequisicion",
                    type: 'POST',
                    dataType:'JSON',
                    data:{ano:1},
                    success: function(r){
                        if(r.success==1){
                            resetearReq();
                            $('#txt_nreq').text(r.requisicion);
                            $('#nreq_load').css('display','none');
                            $('#nreq').css('display','block');
                        }else{
                            alert('No se pueden cargar cotizaciones');
                        }
                    }
                });
            });
            $('#modal-btnconf1-dos').on('click',function(){
                $('#modal-conf1').modal('hide');
                return false;
            });
        }else{
            $('#listareq').css('display','none');
            $('#modal-conf1').modal('hide');
            $('#nreq').css('display','none');
            $('#nreq_load').css('display','block');
            $.ajax({
                url:"ajax.php?c=compras&f=a_nuevarequisicion",
                type: 'POST',
                dataType:'JSON',
                data:{ano:1},
                success: function(r){
                    if(r.success==1){
                        resetearReq();
                        $('#txt_nreq').text(r.requisicion);
                        $('#nreq_load').css('display','none');
                        $('#nreq').css('display','block');
                    }else{
                        alert('No se pueden cargar cotizaciones');
                    }
                }
            });
        }
        
    }

    function disabledReq(op){
        
        $('#c_solicitante').prop('disabled',true);
        $('#c_tipogasto').prop('disabled',true);
        $('#c_moneda').prop('disabled',true);
        $('#c_proveedores').prop('disabled',true);
        $('#c_almacen').prop('disabled',true);
        $('#c_productos').html('<option value="0">Seleccione</option>'); 
        $('#c_productos').off('click');
        //$('#c_productos').remove();
        $('#moneda_tc').prop('disabled',true);
        $('#comment').prop('disabled', true);
        //$('#btn_savequit').prop('disabled', true);
        //$('#btn_authquit').prop('disabled', true);
        $('#moneda_tc').prop('disabled', true);
        $('#date_entrega').prop('disabled', true);
        $('#checkbox').prop('disabled', true);
        $('#btn_addProd').prop('disabled', true);
        $('#opciones_2').prop('disabled', true);

        $('#facturar').prop('disabled', true);
        


        if(op!=1){
            $('#nofactrec').prop('disabled', true);
            $('#date_recepcion').prop('disabled', true);
            $('#impfactrec').prop('disabled', true);


        }

        
        //$('#btn_authquit').prop('disabled', true);


    }

    function eldelserie(idProd,cadcar,i){
        $('#sid_'+idProd+'_chid_'+cadcar+'_'+i+'').remove();
        tot = $('#modalcantrecibida').val();
        $('#modalcantrecibida').val(tot-1);
    }

    function recibirProductoc(idProd,modo,txtprod,txtcant,mod,cadcar,txtrecibido){
     
cantant=$("#cantr_"+idProd+"_prod").val();
if(cantant>0){
    /*
    canterioridad='<div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left"><font color="#bbbbbb">Cantidad recibida anterioridad:</font></label>\
                        <div class="col-sm-6">\
                          <input id="modalcantrecibidap" class="numeric" type="text" value="'+cantant+'" readonly="readonly" >\
                        </div>\
                    </div>';
                    */
    canterioridad='';
}else{
    canterioridad='';
}
if(modo==5){
divbody='<div class="row">\
                    <input type="hidden" id="hmprod" value="'+idProd+'">\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Producto:</label>\
                        <div class="col-sm-6">\
                          '+txtprod+'\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Caracteristicas:</label>\
                        <div class="col-sm-6">\
                          <span class="label label-primary" style="cursor:pointer;">Pedimentos y lotes</span>\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Cantidad vendida:</label>\
                        <div class="col-sm-6">\
                          <input class="numeric" type="text" value="'+txtcant+'" readonly="readonly">\
                        </div>\
                    </div>\
                    '+canterioridad+'\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Cantidad a devolver:</label>\
                        <div class="col-sm-6">\
                          <input id="modalcantrecibida" class="numeric" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">No. pedimento:</label>\
                        <div class="col-sm-6">\
                            <input id="nopedimento" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Aduana:</label>\
                        <div class="col-sm-6">\
                            <input id="aduanatext" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">No. aduana:</label>\
                        <div class="col-sm-6">\
                            <input id="noaduana" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Tipo de cambio:</label>\
                        <div class="col-sm-6">\
                            <input id="tipcambio" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Fecha de pedimento:</label>\
                        <div class="col-sm-6">\
                            <input id="datepedimento" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">No. lote:</label>\
                        <div class="col-sm-6">\
                            <input id="nolote" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Fecha de fabricacion:</label>\
                        <div class="col-sm-6">\
                            <input id="datelotefab" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Fecha de caducidad:</label>\
                        <div class="col-sm-6">\
                            <input id="datelotecad" type="text" >\
                        </div>\
                    </div>\
                </div>';

            $('#bodyespecialc').html(divbody);

            $('#datepedimento').datepicker({
                    format: "yyyy-mm-dd",
                    language: "es"
            });

            $('#datelotefab').datepicker({
                    format: "yyyy-mm-dd",
                    language: "es"
            });

            $('#datelotecad').datepicker({
                    format: "yyyy-mm-dd",
                    language: "es"
            });

            $('#tipcambio').numeric();
            $('#modalcantrecibida').numeric();
            cadena=$('#hiddensProdsc input[id="'+idProd+'"][ch="'+cadcar+'"]').val();
            if(typeof cadena != "undefined"){
                 cadsplit = cadena.split('->-');
                 //$('#modalcantrecibida').val(cadsplit[2]).prop('disabled',true);
                 $('#modalcantrecibida').val('');
                 $('#nopedimento').val(cadsplit[3]).prop('disabled',true);
                 $('#aduanatext').val(cadsplit[4]).prop('disabled',true);
                 $('#noaduana').val(cadsplit[5]).prop('disabled',true);
                 $('#tipcambio').val(cadsplit[6]).prop('disabled',true);
                 $('#datepedimento').val(cadsplit[7]).prop('disabled',true);
                 $('#nolote').val(cadsplit[8]).prop('disabled',true);
                 $('#datelotefab').val(cadsplit[9]).prop('disabled',true);
                 $('#datelotecad').val(cadsplit[10]).prop('disabled',true);
            }
}

if(modo==4){
divbody='<div class="row">\
                    <input type="hidden" id="hmprod" value="'+idProd+'">\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Producto:</label>\
                        <div class="col-sm-6">\
                          '+txtprod+'\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Caracteristicas:</label>\
                        <div class="col-sm-6">\
                          <span class="label label-primary" style="cursor:pointer;">Pedimentos</span>\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Cantidad enviada:</label>\
                        <div class="col-sm-6">\
                          <input class="numeric" type="text" value="'+txtcant+'" readonly="readonly">\
                        </div>\
                    </div>\
                    '+canterioridad+'\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Cantidad a devolver:</label>\
                        <div class="col-sm-6">\
                          <input id="modalcantrecibida" class="numeric" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">No. pedimento:</label>\
                        <div class="col-sm-6">\
                            <input id="nopedimento" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Aduana:</label>\
                        <div class="col-sm-6">\
                            <input id="aduanatext" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">No. aduana:</label>\
                        <div class="col-sm-6">\
                            <input id="noaduana" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Tipo de cambio:</label>\
                        <div class="col-sm-6">\
                            <input id="tipcambio" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Fecha de pedimento:</label>\
                        <div class="col-sm-6">\
                            <input id="datepedimento" type="text" >\
                        </div>\
                    </div>\
                </div>';

            $('#bodyespecialc').html(divbody);

            $('#datepedimento').datepicker({
                    format: "yyyy-mm-dd",
                    language: "es"
            });

            $('#tipcambio').numeric();
            $('#modalcantrecibida').numeric();
            cadena=$('#hiddensProdsc input[id="'+idProd+'"][ch="'+cadcar+'"]').val();
            if(typeof cadena != "undefined"){
                 cadsplit = cadena.split('->-');
                 $('#modalcantrecibida').val('');
                 $('#nopedimento').val(cadsplit[3]).prop('disabled',true);
                 $('#aduanatext').val(cadsplit[4]).prop('disabled',true);
                 $('#noaduana').val(cadsplit[5]).prop('disabled',true);
                 $('#tipcambio').val(cadsplit[6]).prop('disabled',true);
                 $('#datepedimento').val(cadsplit[7]).prop('disabled',true);
            }
}
if(modo==1){
divbody='<div class="row">\
<input type="hidden" id="hmprod" value="'+idProd+'">\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Producto:</label>\
                        <div class="col-sm-6">\
                          '+txtprod+'\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Caracteristicas:</label>\
                        <div class="col-sm-6">\
                          <span class="label label-primary" style="cursor:pointer;">Lotes</span>\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Cantidad enviada:</label>\
                        <div class="col-sm-6">\
                          <input class="numeric" type="text" value="'+txtcant+'" readonly="readonly">\
                        </div>\
                    </div>\
                    '+canterioridad+'\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Cantidad a devolver:</label>\
                        <div class="col-sm-6">\
                          <input id="modalcantrecibida" class="numeric" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">No. lote:</label>\
                        <div class="col-sm-6">\
                            <input id="nolote" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Fecha de fabricacion:</label>\
                        <div class="col-sm-6">\
                            <input id="datelotefab" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Fecha de caducidad:</label>\
                        <div class="col-sm-6">\
                            <input id="datelotecad" type="text" >\
                        </div>\
                    </div>\
                </div>';

            $('#bodyespecialc').html(divbody);
            $('#tipcambio').numeric();

            $('#datelotefab').datepicker({
                    format: "yyyy-mm-dd",
                    startDate:  "<?php echo $sd; ?>",
                    language: "es"
            });

            $('#datelotecad').datepicker({
                    format: "yyyy-mm-dd",
                    startDate:  "<?php echo $sd; ?>",
                    language: "es"
            });

            $('#modalcantrecibida').numeric();
            cadena=$('#hiddensProdsc input[id="'+idProd+'"][ch="'+cadcar+'"]').val();
            //alert(cadena);
            if(typeof cadena != "undefined"){
                 cadsplit = cadena.split('->-');
                 $('#modalcantrecibida').val('');
                 $('#nolote').val(cadsplit[3]).prop('disabled',true);
                 $('#datelotefab').val(cadsplit[4]).prop('disabled',true);
                 $('#datelotecad').val(cadsplit[5]).prop('disabled',true);
            }
}
if(modo==2){
divbody='<div class="row">\
<input type="hidden" id="hmprod" value="'+idProd+'">\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Producto:</label>\
                        <div class="col-sm-6">\
                          '+txtprod+'\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Caracteristicas:</label>\
                        <div class="col-sm-6">\
                          <span class="label label-primary" style="cursor:pointer;">Series</span>\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Cantidad solicitada:</label>\
                        <div class="col-sm-6">\
                          <input class="numeric" type="text" value="'+txtcant+'" readonly="readonly">\
                        </div>\
                    </div>\
                    '+canterioridad+'\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Cantidad a devolver:</label>\
                        <div class="col-sm-6">\
                          <input disabled id="modalcantrecibida" class="numeric" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Series a devolver:</label>\
                        <div class="col-sm-6">\
                            &nbsp;\
                        </div>\
                    </div>\
                    <div id="seriesprods__'+idProd+'">\
                    </div>\
                </div>';

            $('#bodyespecialc').html(divbody);
            $('#tipcambio').numeric();
            $('#modalcantrecibida').numeric();
            cadena=$('#hiddensProdsc input[id="'+idProd+'"][ch="'+cadcar+'"]').val();
            //alert(cadena);
            if(typeof cadena != "undefined"){
                cadsplit = cadena.split('->-');
                $('#modalcantrecibida').val(cadsplit[2]);
                divseries='';
                seriesplit = cadsplit[4].split(',');
                for (var i=0; i<cadsplit[2]; i++) {
                    divseries+='<div id="sid_'+idProd+'_chid_'+cadcar+'_'+i+'" class="sprod" class="col-sm-12" style="padding:15px;">\
                            <label class="col-sm-6 control-label text-left">No serie '+((i*1)+1)+':</label>\
                            <div class="col-sm-6">\
                                <input class="inpsprod" type="text" value="'+seriesplit[i]+'" disabled >\
                                <a style="cursor:pointer;" onclick="eldelserie('+idProd+',\''+cadcar+'\','+i+');">Eliminar</a>\
                            </div>\
                        </div>';
                };
            $('#seriesprods__'+idProd).html(divseries);
            }

}

if(modo==3){
divbody='<div class="row">\
<input type="hidden" id="hmprod" value="'+idProd+'">\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Producto:</label>\
                        <div class="col-sm-6">\
                          '+txtprod+'\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Caracteristicas:</label>\
                        <div class="col-sm-6">\
                          <span class="label label-primary" style="cursor:pointer;">Series - Pedimentos</span>\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Cantidad enviada:</label>\
                        <div class="col-sm-6">\
                          <input class="numeric" type="text" value="'+txtcant+'" readonly="readonly">\
                        </div>\
                    </div>\
                    '+canterioridad+'\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">No. pedimento:</label>\
                        <div class="col-sm-6">\
                            <input id="nopedimento" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Aduana:</label>\
                        <div class="col-sm-6">\
                            <input id="aduanatext" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">No. aduana:</label>\
                        <div class="col-sm-6">\
                            <input id="noaduana" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Tipo de cambio:</label>\
                        <div class="col-sm-6">\
                            <input id="tipcambio" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Fecha de pedimento:</label>\
                        <div class="col-sm-6">\
                            <input id="datepedimento" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Cantidad a devolver:</label>\
                        <div class="col-sm-6">\
                          <input id="modalcantrecibida" class="numeric" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Series a devolver:</label>\
                        <div class="col-sm-6">\
                            &nbsp;\
                        </div>\
                    </div>\
                    <div id="seriesprods__'+idProd+'">\
                    </div>\
                </div>';

            $('#bodyespecialc').html(divbody);
            $('#tipcambio').numeric();
            $('#modalcantrecibida').numeric();
            $('#datepedimento').datepicker({
                    format: "yyyy-mm-dd",
                    language: "es"
            });

            cadena=$('#hiddensProdsc input[id="'+idProd+'"][ch="'+cadcar+'"]').val();
            //alert(cadena);
            if(typeof cadena != "undefined"){
                cadsplit = cadena.split('->-');
                $('#modalcantrecibida').val(cadsplit[2]);
   
                $('#nopedimento').val(cadsplit[3]).prop('disabled',true);
                $('#aduanatext').val(cadsplit[4]).prop('disabled',true);
                $('#noaduana').val(cadsplit[5]).prop('disabled',true);
                $('#tipcambio').val(cadsplit[6]).prop('disabled',true);
                $('#datepedimento').val(cadsplit[7]).prop('disabled',true);
                divseries='';
                seriesplit = cadsplit[9].split(',');
                for (var i=0; i<cadsplit[2]; i++) {
                    divseries+='<div id="sid_'+idProd+'_chid_'+cadcar+'_'+i+'" class="sprod" class="col-sm-12" style="padding:15px;">\
                            <label class="col-sm-6 control-label text-left">No serie '+((i*1)+1)+':</label>\
                            <div class="col-sm-6">\
                                <input disabled class="inpsprod" type="text" value="'+seriesplit[i]+'" >\
                                <a style="cursor:pointer;" onclick="eldelserie('+idProd+',\''+cadcar+'\','+i+');">Eliminar</a>\
                            </div>\
                        </div>';
                };
            $('#seriesprods__'+idProd).html(divseries);
            }
}
       
        
        mmod = $('#modal-recepc').modal({
                        backdrop: 'static',
                        keyboard: false, 
                        show: true
                });

        $('#modal-recepc-uno').on('click',function(){
            modalcantrecibida=$('#modalcantrecibida').val();


            cantant=$("#tr_"+idProd+"[ch='"+cadcar+"']").find("#cantrv_"+idProd+"_prod").val();
            cantseries = $('#modalcantrecibida').val();
            txtcant=$("#tr_"+idProd+"[ch='"+cadcar+"'] td:eq(6)").text();
            txtcant=9999999;


            enviados=$("#tr_"+idProd+"[ch='"+cadcar+"'] td:nth-child(7)").text()*1;
            devueltos=$("#tr_"+idProd+"[ch='"+cadcar+"'] td:nth-child(8)").text()*1;

            if(cantseries>0){
                if( ((cantseries*1)+(devueltos*1))>(enviados)){
                    alert('La cantidad a devolver no puede ser mayor al total de vendidos');
                    $('#modalcantrecibida').val( (enviados*1)-(devueltos*1) );
                    //$('#modalcantrecibida').css('border','1px solid #ff0000');
                    return false;
                }
            }else{
                alert('La cantidad a devolver debe ser mayor a 0');
                return false;
            }


            if(modo==1){
                nolote=$('#nolote').val();
                datelotefab=$('#datelotefab').val();
                datelotecad=$('#datelotecad').val();
                    $.ajax({
                    url:"ajax.php?c=compras&f=a_modalRecepcion",
                    type: 'POST',
                    data:{idProd:idProd,modo:modo,modalcantrecibida:modalcantrecibida,nolote:nolote,datelotefab:datelotefab,datelotecad:datelotecad,cadcar:cadcar},
                    success: function(r){
                        $('#modal-recepc').modal('hide');
                        $('#hiddensProdsc input[id="'+idProd+'"][ch="'+cadcar+'"]').remove();
                        $('#hiddensProdsc').append('<input ch="'+cadcar+'" id="'+idProd+'" type="hidden" value="'+r+'" >');

                        if(typeof r != "undefined"){
                             rsplit = r.split('->-');
                        }
                       // alert(idProd);
                        $("#cantrv_"+idProd+"_prod"+"[chp='"+cadcar+"']").val(rsplit[2]);
                        recalcula();
                    }
                });
            }
            if(modo==2){
                nseries=0;
                detener=0;
                seriesprods = $('#seriesprods__'+idProd+' .inpsprod').map(function() {
                    seriescad = $(this).val();

                    if(seriescad==""){
                        detener++;
                    }
                    nseries++;
                    return seriescad;
                }).get().join(',');

                if(seriesprods==''){
                    alert('Tienes que agregar series a este producto');
                    return false;
                }
                if(detener>0){
                    alert('No puedes dejar series en blanco');
                    return false;
                }
                
                    $.ajax({
                    url:"ajax.php?c=compras&f=a_modalRecepcion",
                    type: 'POST',
                    data:{idProd:idProd,modo:modo,modalcantrecibida:modalcantrecibida,nseries:nseries,seriesprods:seriesprods,cadcar:cadcar},
                    success: function(r){
                        console.log(r);
                        $('#modal-recepc').modal('hide');
                        $('#hiddensProdsc input[id="'+idProd+'"][ch="'+cadcar+'"]').remove();
                        $('#hiddensProdsc').append('<input ch="'+cadcar+'" id="'+idProd+'" type="hidden" value="'+r+'" >');

                        if(typeof r != "undefined"){
                             rsplit = r.split('->-');
                        }
                       // alert(idProd);
                        $("#cantrv_"+idProd+"_prod"+"[chp='"+cadcar+"']").val(rsplit[2]);
                        recalcula();
                    }
                });
            }
            if(modo==3){
                nopedimento=$('#nopedimento').val();
                aduanatext=$('#aduanatext').val();
                noaduana=$('#noaduana').val();
                tipcambio=$('#tipcambio').val();
                datepedimento=$('#datepedimento').val();

                nseries=0;
                detener=0;
                
                seriesprods = $('#seriesprods__'+idProd+' .inpsprod').map(function() {    
                    seriescad = $(this).val();
                    if(seriescad==""){
                        detener++;
                    }
                    nseries++;
                    return seriescad;
                }).get().join(',');
                if(seriesprods==''){
                    alert('Tienes que agregar series a este producto');
                    return false;
                }
                if(detener>0){
                    alert('No puedes dejar series en blanco');
                    return false;
                }
                console.log(seriesprods);
                    $.ajax({
                    url:"ajax.php?c=compras&f=a_modalRecepcion",
                    type: 'POST',
                    data:{idProd:idProd,modo:modo,modalcantrecibida:modalcantrecibida,modalcantrecibida:modalcantrecibida,nopedimento:nopedimento,aduanatext:aduanatext,noaduana:noaduana,tipcambio:tipcambio,datepedimento:datepedimento,nseries:nseries,seriesprods:seriesprods,cadcar:cadcar},
                    success: function(r){
                        $('#modal-recepc').modal('hide');
                        $('#hiddensProdsc input[id="'+idProd+'"][ch="'+cadcar+'"]').remove();
                        $('#hiddensProdsc').append('<input ch="'+cadcar+'" id="'+idProd+'" type="hidden" value="'+r+'" >');

                        if(typeof r != "undefined"){
                             rsplit = r.split('->-');
                        }
                       // alert(idProd);
                        $("#cantrv_"+idProd+"_prod"+"[chp='"+cadcar+"']").val(rsplit[2]);

                        recalcula();

                    }
                });
            }
            if(modo==4){

                nopedimento=$('#nopedimento').val();
                aduanatext=$('#aduanatext').val();
                noaduana=$('#noaduana').val();
                tipcambio=$('#tipcambio').val();
                datepedimento=$('#datepedimento').val();
                    $.ajax({
                    url:"ajax.php?c=compras&f=a_modalRecepcion",
                    type: 'POST',
                    data:{idProd:idProd,modo:modo,modalcantrecibida:modalcantrecibida,nopedimento:nopedimento,aduanatext:aduanatext,noaduana:noaduana,tipcambio:tipcambio,datepedimento:datepedimento,cadcar:cadcar},
                    success: function(r){
                        $('#modal-recepc-uno').unbind();
                        $('#modal-recepc-dos').unbind();
                        $('#modal-recepc').modal('hide');
                        $('#hiddensProdsc input[id="'+idProd+'"][ch="'+cadcar+'"]').remove();
                        $('#hiddensProdsc').append('<input ch="'+cadcar+'" id="'+idProd+'" type="hidden" value="'+r+'" >');

                        if(typeof r != "undefined"){
                             rsplit = r.split('->-');
                        }
                       // alert(idProd);
                        $("#cantrv_"+idProd+"_prod"+"[chp='"+cadcar+"']").val(rsplit[2]);
                        recalcula();
                        
                    }
                });
            }
            if(modo==5){

                nopedimento=$('#nopedimento').val();
                aduanatext=$('#aduanatext').val();
                noaduana=$('#noaduana').val();
                tipcambio=$('#tipcambio').val();
                datepedimento=$('#datepedimento').val();
                nolote=$('#nolote').val();
                datelotefab=$('#datelotefab').val();
                datelotecad=$('#datelotecad').val();
                    $.ajax({
                    url:"ajax.php?c=compras&f=a_modalRecepcion",
                    type: 'POST',
                    data:{idProd:idProd,modo:modo,modalcantrecibida:modalcantrecibida,nopedimento:nopedimento,aduanatext:aduanatext,noaduana:noaduana,tipcambio:tipcambio,datepedimento:datepedimento,cadcar:cadcar,nolote:nolote,datelotefab:datelotefab,datelotecad:datelotecad},
                    success: function(r){
                        $('#modal-recepc-uno').unbind();
                        $('#modal-recepc-dos').unbind();
                        $('#modal-recepc').modal('hide');
                        $('#hiddensProdsc input[id="'+idProd+'"][ch="'+cadcar+'"]').remove();
                        $('#hiddensProdsc').append('<input ch="'+cadcar+'" id="'+idProd+'" type="hidden" value="'+r+'" >');

                        if(typeof r != "undefined"){
                             rsplit = r.split('->-');
                        }
                       // alert(idProd);
                        $("#cantrv_"+idProd+"_prod"+"[chp='"+cadcar+"']").val(rsplit[2]);
                        recalcula();
                        
                    }
                });
            }
            $('#modal-recepc-uno').unbind();
            $('#modal-recepc-dos').unbind();
        });

        $('#modal-recepc-dos').one('click',function(){
            $('#modal-recepc').modal('hide');
            $('#modal-recepc-uno').unbind();
        });

             
    }

    function popupCantc(idProd,lotes,series,pedimentos,cadcar){
        mod=1;

    
        //alert(idProd+' - '+lotes+' - '+series+' - '+pedimentos);

        txtprod=$("#tr_"+idProd+"[ch='"+cadcar+"'] td:eq(2)").text();
        txtcant=$("#tr_"+idProd+"[ch='"+cadcar+"'] td:eq(4)").text();
        txtrecibido=$("#tr_"+idProd+"[ch='"+cadcar+"'] td:eq(6)").text();

        if(lotes==1 && series==0 && pedimentos==0){
            recibirProductoc(idProd,1,txtprod,txtcant,mod,cadcar,txtrecibido); //solo lote
        }else if(series==1 && pedimentos==0 && lotes==0){
            recibirProductoc(idProd,2,txtprod,txtcant,mod,cadcar,txtrecibido); //solo series
        }else if(series==1 && pedimentos==1  && lotes==0){
            recibirProductoc(idProd,3,txtprod,txtcant,mod,cadcar,txtrecibido); //series y pedimentos
        }else if(pedimentos==1 && series==0  && lotes==0){
            recibirProductoc(idProd,4,txtprod,txtcant,mod,cadcar,txtrecibido); //solo pedimentos
        }else if(pedimentos==0 && series==0  && lotes==0){
            recibirProductoc(idProd,0,txtprod,txtcant,mod,cadcar,txtrecibido); //sin nada
        }else if(pedimentos==1 && series==0  && lotes==1){
            recibirProductoc(idProd,5,txtprod,txtcant,mod,cadcar,txtrecibido); //lotes pedimentos
        }else{
           // recibirProducto(idProd,0); //nada
        }
        //alert(idProd+' - '+lotes+' - '+series+' - '+pedimentos);
    
    }

    function popupCant(idProd,lotes,series,pedimentos,cadcar){

        mod=1;
/*
        var r = confirm("¿Deseas sacar producto sin existencia?.");
        if (r == true) {
            txtprod=$("#tr_"+idProd+"[ch='"+cadcar+"'] td:eq(2)").text();
            txtcant=$("#tr_"+idProd+"[ch='"+cadcar+"'] td:eq(4)").text();

            $('#modal-agrega').modal({
                backdrop: 'static',
                keyboard: false, 
                show: true
            });
            $('#modal-agrega-uno').on('click',function(){
                modalcantrecibida=$('#cantnueva').val();

                if(modalcantrecibida>(txtcant*1)){
                    alert('La cantidad a vender no puede superar a la cantidad solicitada en la orden de venta');
                    return false;
                }

                $("#cantr_"+idProd+"_prod"+"[chp='"+cadcar+"']").attr('sinexi', '1');
                $("#cantr_"+idProd+"_prod"+"[chp='"+cadcar+"']").val(modalcantrecibida);
                refreshCants(idProd,cadcar);
                $('#modal-agrega').modal('hide');
            });     
        } else {

            */

        //$("#cantr_"+idProd+"_prod"+"[chp='"+cadcar+"']").attr('sinexi', '0');
        //alert(idProd+' - '+lotes+' - '+series+' - '+pedimentos);

        txtprod=$("#tr_"+idProd+"[ch='"+cadcar+"'] td:eq(2)").text();
        txtcant=$("#tr_"+idProd+"[ch='"+cadcar+"'] td:eq(4)").text();

        if(lotes==1 && series==0 && pedimentos==0){
            recibirProducto(idProd,1,txtprod,txtcant,mod,cadcar); //solo lote
        }else if(series==1 && pedimentos==0 && lotes==0){
            recibirProducto(idProd,2,txtprod,txtcant,mod,cadcar); //solo series
        }else if(series==1 && pedimentos==1  && lotes==0){
            recibirProducto(idProd,3,txtprod,txtcant,mod,cadcar); //series y pedimentos
        }else if(pedimentos==1 && series==0  && lotes==0){
            recibirProducto(idProd,4,txtprod,txtcant,mod,cadcar); //solo pedimentos
        }else if(pedimentos==0 && series==0  && lotes==0){
            recibirProducto(idProd,0,txtprod,txtcant,mod,cadcar); //solo pedimentos
        }else if(pedimentos==1 && series==0  && lotes==1){
            recibirProducto(idProd,5,txtprod,txtcant,mod,cadcar); //solo pedimentos y lotes
        }else{
           // recibirProducto(idProd,0); //nada
        }
        //alert(idProd+' - '+lotes+' - '+series+' - '+pedimentos);
    //}
    }

    function reprintRec(env){        
        window.open("../../modulos/cotizaciones/cotizacionesPdf/Envio_"+env+".pdf");
    }
    
    function editRec(idRec,mod,idOC){
        $('#divxmls2').css('display','none');
        $('#hiddensProdsc').html('');
            disprod=1;
            table = $('#tablaprods').DataTable();
            table.clear().draw();
            //table.row('#tr_'+idProducto).remove().draw();

            $('#listareq').css('display','none');
            $('#listarec').css('display','none');
            $('#modal-conf1').modal('hide');
            $('#nreq').css('display','none');
            $('#nreq_load').css('display','block');
            $.ajax({
                url:"ajax.php?c=ventas&f=a_verEnvio",
                type: 'POST',
                dataType:'JSON',
                data:{idRec:idRec,m:3,mod:mod,idOC:idOC},
                success: function(r){
                    if(r.success==1){
                        $('#divfactenv').css('display','block');
                        console.log(r);
                        resetearReq();
                        $('#ph').html('<span opt="2" class="label label-warning" style="cursor:pointer;">Envio Orden de venta</span>');
                        if(mod==4){                            
                            $('#ph').html('<span opt="3" class="label label-default" style="cursor:pointer;">Visualizar venta</span>');
                            disabledReq();
                            $('#btns .btn').prop('disabled', true);
                            $('#btn_savequit').prop('disabled', true);
                            $('#btn_devolucion').css('display', 'block');
                            $('#btn_devolucion').prop('disabled', false);

                            $('#buttonFactura').css('display','none');
                            $('#factura').css('display','none');
                            $('#divfactenv').css('display','none');
                            $('#arecibir').text('Devueltos');
                            $('#coldevos').text('Devoluciones');

                            $('#divxmls2').css('display','block');
                        }
                        $('#reqnota').val(0);
                        if(r.requisicion.xmlfile!='' && r.requisicion.xmlfile!=null){
                            $('#reqnota').val(1);
                            $('#idrfc').val(r.requisicion.idrfc);
                            $('#divxmls2').css('display','none');
                            $('#resultasoc').html('<table w style="margin: 15px 0px 8px; width: 100%; border: 1px solid rgb(236, 236, 236); background-color: rgb(250, 250, 250);">\
    <tbody>\
    <tr style="height:25px;">\
      <th width="170">Folio factura</th>\
      <th width="100">Fecha timbrado</th>\
      <th width="150">$ Total factura</th>\
      <th width="100">Ver xml</th>\
    </tr>\
      <tr>\
      <td width="100">'+r.requisicion.folio+'</td>\
      <td width="170">'+r.requisicion.fecha_fact+'</td>\
      <td width="150">'+r.requisicion.totventa+'</td>\
      <td width="100"><a id="xmlfile" name="'+r.requisicion.xmlfile+'" class="btn btn-success btn-xs" onclick="openxml(\''+r.requisicion.xmlfile+'\')">Ver xml</a>\
      </td></tr>\
  </tbody></table>');
                        }

                        $('#c_proveedores').prop('disabled',true);
                        $('#c_almacen').prop('disabled',true);
                        $('#txt_nreq').text(idOC);
                        $('#txt_nreq2').text(idRec);//Recepcion
                        $('#nreq_load').css('display','none');

                        $("#c_solicitante").val(r.requisicion.id_solicito).trigger("change");

                        $("#c_cliente").val(r.requisicion.id_cliente).trigger("change");
                        $("#c_solicitante").val(r.requisicion.id_solicito).trigger("change");
                        $("#date_hoy").val(r.requisicion.fecha);
                        $('#date_hoy').prop('disabled',true);
                        $("#date_entrega").val(r.requisicion.fecha_entrega);

                        if(r.requisicion.urgente==0){
                             $("#opciones_1").attr('checked', 'checked');
                        }else{
                             $("#opciones_2").attr('checked', 'checked');
                        }

                        if(r.requisicion.inventariable==0){
                            //$("#checkbox").prop( "checked", true );
                            $('#checkbox').attr('checked', false);
                        }else{
                            //$("#checkbox").prop( "checked", false );
                            $('#checkbox').attr('checked', true);
                        }
                       

                        $("#c_tipogasto").val(r.requisicion.id_tipogasto).trigger("change");
                        $("#c_moneda").val(r.requisicion.id_moneda).trigger("change");
                        $("#c_almacen").val(r.requisicion.id_almacen).trigger("change");

                        if(r.requisicion.id_moneda>1){
                            $("#moneda_tc").val(r.requisicion.tipo_cambio);
                        }

                        $("#c_proveedores").val(r.requisicion.id_proveedor).trigger("change");

                        var comment = r.requisicion.observaciones.replace(/<br\s?\/?>/g,"\n");
                        $("#comment").val(comment);

                        txt_proveedor=$('#c_proveedores option:selected').text();
                        txt_almacen=$('#c_almacen option:selected').text();


                        $("#nofactrec").val(r.requisicion.no_factura);
                        $("#date_recepcion").val(r.requisicion.fecha_factura);
                        $("#impfactrec").val(r.requisicion.imp_factura);

                        //table = $('#tablaprods').DataTable();
                        $('#c_cliente').prop('disabled', true);
                        data_almacen=$('#data_almacen').html();
                        disablecant=0;

                        $.each(r.productos, function( k, v ) {
                            $('#hiddensProdsc').append('<input id="'+v.id+'" ch="'+v.caracteristica+'" type="hidden" value="'+v.eecho+'">');
                            if(mod==0){
                                eliminProd="&nbsp;";
                                txtdis='disabled';
                            }else if(mod==4){
                                txtdis='';
                                eliminProd="<button onclick='' id='btn_addProd' class='btn btn-sm btn-danger' type='button' style='height:26px;'>Devoluciones</button>";
                            }else{
                                txtdis='';
                                eliminProd="<button onclick='removeProdReq("+v.id+");' id='btn_addProd' class='btn btn-sm btn-danger' type='button' style='height:26px;'>Quitar</button>";
                            }

                            
                            if(mod==4){

        

                               if(v.lotes==1 && v.series==0 && v.pedimentos==0){
                                    disabledcant='readonly="readonly"';
                                    onkeycant="especial='1' onclick='popupCantc("+v.id+","+v.lotes+",0,0,\""+v.caracteristica+"\")'";
                                }else if(v.series==1 && v.pedimentos==0 && v.lotes==0){
                                    disabledcant='readonly="readonly"';
                                    onkeycant="especial='2' onclick='popupCantc("+v.id+",0,"+v.series+",0,\""+v.caracteristica+"\")'";
                                }else if(v.series==1 && v.pedimentos==1  && v.lotes==0){
                                    disabledcant='readonly="readonly"';
                                    onkeycant="especial='3' onclick='popupCantc("+v.id+",0,"+v.series+","+v.pedimentos+",\""+v.caracteristica+"\")'";
                                }else if(v.pedimentos==1 && v.series==0  && v.lotes==0){
                                    disabledcant='readonly="readonly"';
                                    onkeycant="especial='4' onclick='popupCantc("+v.id+",0,0,"+v.pedimentos+",\""+v.caracteristica+"\")'";
                                }else if(v.pedimentos==0 && v.series==0  && v.lotes==0){
                                        disabledcant=" onkeyup=verifyCants("+v.id+"); ";
                                        onkeycant="especial='0' onkeyup='verifyCantsc("+v.id+",\""+v.caracteristica+"\");'";
                                }else if(v.pedimentos==1 && v.series==0  && v.lotes==1){
                                    disabledcant='readonly="readonly"';
                                    onkeycant="especial='5' onclick='popupCantc("+v.id+","+v.lotes+",0,"+v.pedimentos+",\""+v.caracteristica+"\")'";
                                }else{
                                    disabledcant=" onkeyup='verifyCants("+v.id+");' ";
                                    onkeycant="especial='0' ";
                                }

                                if( (v.recibidorec-v.cantdev)<=0 ){
                                    didids=' disabled ';
                                }else{
                                    didids='';
                                }

                                 eliminProd="<input chp='"+v.caracteristica+"' id='cantrv_"+v.id+"_prod'  "+onkeycant+" class='numeros' style='border:1px solid #ef7070;width:100px;padding:2px;' type='text' placeholder='Cant. a devolver' "+didids+"/>";

                               

                            }else{
                                disabledcant='disabled="disabled"';
                                onkeycant='readonly="readonly"';
                                eliminProd='';
                                //onkeycant="";
                            }


                            Rowdata="<tr ch='"+v.caracteristica+"' id='tr_"+v.id+"'>\
                            <td>0</td>\
                            <td>"+v.codigo+"<input type='hidden' id='ccalma' value='"+v.id_almacen+"'></td>\
                            <td>"+v.nomprod+"</td>\
                            <td id='valUnit'>"+v.costo+"</td>\
                            <td>"+v.cantidad+"</td>\
                            <td>"+(v.costo*v.cantidad)+"</td>\
                            <td>"+v.recibidorec+"</td>\
                            <td>"+v.cantdev+"</td>\
                            <!--<td class='valImporte' implimpio='"+v.costo+"' id='valImporte'>"+v.costo+"</td>-->\
                            <td>&nbsp;</td>\
                            <td>&nbsp;</td>\
                            <td>"+eliminProd+"</td>\
                            </tr>";
                            
                            table.row.add($(Rowdata)).draw();
                            refreshCants(v.id,v.caracteristica,2);

                            $("#tr_"+v.id).find('#c_almacen').val(v.id_almacen).trigger("change");

                          /*  $( ".numeros" ).focus(function() {
                              alert( "Handler for .focus() called." );
                            });
*/
                        });

    
                        $('#date_recepcion').datepicker({
                                format: "yyyy-mm-dd",
                                startDate:  "<?php echo $sd; ?>",
                                endDate: "<?php echo $ed; ?>",
                                language: "es"
                        });

                        //btn_savequit



                        //$('#btn_savequit').text('Guardar cambios');
                        //$('#btn_authquit').text('Guardar cambios y autorizar orden');
                        $('#txt_nreq').append('<input id="idrequi" type="hidden" value="'+idOC+'">');

                        $('.numeros').numeric();
                        $('#panel_tabla').css('display','block');
                        $('#nreq').css('display','block');

                    }else{
                        alert('No se pueden cargar cotizaciones');
                    }
                }
            });

        
    }

    function openxml(xml){
        window.open('../cont/xmls/facturas/temporales/'+xml,'targetWindow','toolbar=no',
                                    'location=no',
                                    'status=no',
                                    'menubar=no',
                                    'scrollbars=yes',
                                    'resizable=yes',
                                    'width=SomeSize',
                                    'height=SomeSize');
    }

    function editReq(idReq,mod,idOC){
            disprod=1;
            table = $('#tablaprods').DataTable();
            table.clear().draw();
            //table.row('#tr_'+idProducto).remove().draw();

            $('#listareq').css('display','none');
            $('#modal-conf1').modal('hide');
            $('#nreq').css('display','none');
            $('#nreq_load').css('display','block');
            $.ajax({
                url:"ajax.php?c=ventas&f=a_editarrequisicion",
                type: 'POST',
                dataType:'JSON',
                data:{idReq:idReq,m:2,mod:mod},
                success: function(r){
                    console.log(r);
                    if(r.success==1){
                        console.log(r);
                        resetearReq();
                        $('#ph').html('<span opt="2" class="label label-warning" style="cursor:pointer;">Envio Orden de venta</span>');
                        if(mod==0){                            
                            $('#ph').html('<span opt="3" class="label label-default" style="cursor:pointer;">Envio de orden de venta</span>');
                            disabledReq(1);
                            $('#facturar').prop('disabled', false);


                            //$('#btns .btn').prop('disabled', true);
                            //$('#btn_savequit').prop('disabled', true);
                            $('#btn_devolucion').css('display', 'none');
                            $('#btn_devolucion').prop('disabled', true);

                            //$('#buttonFactura').css('display','none');
                            $('#factura').css('display','block');
                            $('#divfactenv').css('display','block');
                            $('#arecibir').text('Cant. Enviada');
                            $('#coldevos').text('');

                            //$('#divxmls2').css('display','block');
                        }

                        $('#c_proveedores').prop('disabled',true);
                        $('#c_cliente').prop('disabled',true);
                        $('#date_hoy').prop('disabled',true);
                        $('#txt_nreq').text(idOC);
                        $('#nreq_load').css('display','none');

                        $("#ss").val(r.ss);

                        $("#c_cliente").val(r.requisicion.id_cliente).trigger("change");
                        $("#c_solicitante").val(r.requisicion.id_solicito).trigger("change");
                        $("#date_hoy").val(r.requisicion.fecha);
                        $("#date_entrega").val(r.requisicion.fecha_entrega);

                        if(r.requisicion.urgente==0){
                             $("#opciones_1").attr('checked', 'checked');
                        }else{
                             $("#opciones_2").attr('checked', 'checked');
                        }

                        if(r.requisicion.inventariable==0){
                            //$("#checkbox").prop( "checked", true );
                            $('#checkbox').attr('checked', false);
                        }else{
                            //$("#checkbox").prop( "checked", false );
                            $('#checkbox').attr('checked', true);
                        }
                       
                        if(r.requisicion.limite_credito>0){
                            //$("#fp_ option:last").remove();
                             //$("#opciones_1").attr('checked', 'checked');
                        }else{
                            $("#fp_ option[value='6']").remove();
                             //$("#opciones_2").attr('checked', 'checked');
                        }

                        $("#c_tipogasto").val(r.requisicion.id_tipogasto).trigger("change");
                        $("#c_moneda").val(r.requisicion.id_moneda).trigger("change");
                        $("#c_almacen").val(r.requisicion.id_almacen).trigger("change");

                        if(r.requisicion.id_moneda>1){
                            $("#moneda_tc").val(r.requisicion.tipo_cambio);
                        }

                        $("#c_proveedores").val(r.requisicion.id_proveedor).trigger("change");

                        var comment = r.requisicion.observaciones.replace(/<br\s?\/?>/g,"\n");
                        $("#comment").val(comment);

                        txt_proveedor=$('#c_proveedores option:selected').text();
                        txt_almacen=$('#c_almacen option:selected').text();


                       // $("#nofactrec").val(r.requisicion.no_factura);
                       // $("#date_recepcion").val(r.requisicion.fecha_factura);
                       // $("#impfactrec").val(r.requisicion.imp_factura);

                        //table = $('#tablaprods').DataTable();

                        data_almacen=$('#data_almacen').html();
                        disablecant=0;
                        $.each(r.productos, function( k, v ) {

                            if(mod==0){
                                eliminProd="&nbsp;";
                                txtdis='disabled';
                            }else{
                                txtdis='';
                                eliminProd="<button onclick='removeProdReq("+v.id+");' id='btn_addProd' class='btn btn-sm btn-danger' type='button' style='height:26px;'>Quitar</button>";
                            }

                        

                            if(v.lotes==1 && v.series==0 && v.pedimentos==0){
                                disabledcant='readonly="readonly"';
                                onkeycant="especial='1' onclick='popupCant("+v.id+","+v.lotes+",0,0,\""+v.caracteristica+"\")'";
                            }else if(v.series==1 && v.pedimentos==0 && v.lotes==0){
                                disabledcant='readonly="readonly"';
                                onkeycant="especial='2' onclick='popupCant("+v.id+",0,"+v.series+",0,\""+v.caracteristica+"\")'";
                            }else if(v.series==1 && v.pedimentos==1  && v.lotes==0){
                                disabledcant='readonly="readonly"';
                                onkeycant="especial='3' onclick='popupCant("+v.id+",0,"+v.series+","+v.pedimentos+",\""+v.caracteristica+"\")'";
                            }else if(v.pedimentos==1 && v.series==0  && v.lotes==0){
                                disabledcant='readonly="readonly"';
                                onkeycant="especial='4' onclick='popupCant("+v.id+",0,0,"+v.pedimentos+",\""+v.caracteristica+"\")'";
                            }else if(v.pedimentos==0 && v.series==0  && v.lotes==0){
                                    disabledcant='readonly="readonly"';
                                    onkeycant="especial='0' aaaa='2' onclick='popupCant("+v.id+",0,0,0,\""+v.caracteristica+"\")'";
                            }else if(v.pedimentos==1 && v.series==0  && v.lotes==1){
                                disabledcant='readonly="readonly"';
                                onkeycant="especial='5' onclick='popupCant("+v.id+","+v.lotes+",0,"+v.pedimentos+",\""+v.caracteristica+"\")'";
                            }else{
                                disabledcant=" onkeyup=verifyCants("+v.id+"); ";
                                onkeycant="especial='0' ";
                            }

                            if(v.tipo_producto==2){
                                disabledcant=" onkeyup=verifyCants("+v.id+"); ";
                                onkeycant="especial='0' ";
                            }
                            if(v.cantidad-v.cantidadr==0){
                                //alert(8);
                                //disabledcant='disabled="disabled"';
                            }

                            Rowdata="<tr tp='"+v.tipo_producto+"' ch='"+v.caracteristica+"' id='tr_"+v.id+"'>\
                            <td>0</td>\
                            <td>"+v.codigo+"</td>\
                            <td>"+v.nomprod+"</td>\
                            <td id='valUnit'>"+v.costo+"</td>\
                            <td>"+v.cantidad+"</td>\
                            <td>"+(v.costo*v.cantidad)+"</td>\
                            <td>"+v.cantidadr+"</td>\
                            <td>\
                                <input chp='"+v.caracteristica+"' id='cantr_"+v.id+"_prod' style='width:60%;' "+onkeycant+" class='numeros' type='text' value='0' "+disabledcant+" />\
                            </td>\
                            <!--<td class='valImporte' implimpio='"+v.costo+"' id='valImporte'>"+v.costo+"</td>-->\
                            <td>&nbsp;</td>\
                            <td>&nbsp;</td>\
                            <td>"+eliminProd+"</td>\
                            </tr>";
                            table.row.add($(Rowdata)).draw();
                            //refreshCants(v.id);

                          /*  $( ".numeros" ).focus(function() {
                              alert( "Handler for .focus() called." );
                            });
*/
                        });

                        recalcula();

    
                        $('#date_recepcion').datepicker({
                                format: "yyyy-mm-dd",
                                startDate:  "<?php echo $sd; ?>",
                                endDate: "<?php echo $ed; ?>",
                                language: "es"
                        });

                        //btn_savequit



                        //$('#btn_savequit').text('Guardar cambios');
                        //$('#btn_authquit').text('Guardar cambios y autorizar orden');
                        $('#txt_nreq').append('<input id="idrequi" type="hidden" value="'+idOC+'">');

                        $('.numeros').numeric();
                        $('#panel_tabla').css('display','block');
                        $('#nreq').css('display','block');


                    }else{
                        alert('No se pueden cargar cotizaciones');
                    }
                }
            });

        
    }

    function verifyCantsc(idProd,cadcar){

        cantoc=$('#tr_'+idProd+' td:nth-child(4)').text()*1;
        cantrecibida=$('#tr_'+idProd+' td:nth-child(8)').text()*1;
        enviados=$("#tr_"+idProd+"[ch='"+cadcar+"'] td:nth-child(7)").text()*1;
        devueltos=$("#tr_"+idProd+"[ch='"+cadcar+"'] td:nth-child(8)").text()*1;

        actual=$('#cantrv_'+idProd+'_prod'+"[chp='"+cadcar+"']").val();

        if( ((actual*1)+(devueltos*1))>(enviados)){
            alert('La cantidad de devolucion es mayor a la cantidad enviada');
            $("#tr_"+idProd+"[ch='"+cadcar+"'] input").val( (enviados*1)-(devueltos*1) );
            return false;
        } 

        refreshCants(idProd);
        //

        //table.cell('#tr_'+idProducto+' td:nth-child(10)').data(valcurren).draw();

    }

    function verifyCants(idProd){

        cantoc=$('#tr_'+idProd+' td:nth-child(5)').text()*1;
        cantrecibida=$('#tr_'+idProd+' td:nth-child(8)').text()*1;

        actual=$('#cantr_'+idProd+'_prod').val();

        if( (actual*1)>(cantoc-cantrecibida)){
            alert('La cantidad de recibidos es mayor a la cantidad solicitada');
            $('#cantr_'+idProd+'_prod').val( (cantoc-cantrecibida) );
            return false;
        } 

        refreshCants(idProd);
        //

        //table.cell('#tr_'+idProducto+' td:nth-child(10)').data(valcurren).draw();

    }

    function listarec(idoc){


        span = $('#ph').find('span').text();
        if($('#nreq').is(':visible') && span!='Visualizar venta') {
            $('#modal-conf2').modal('show');
            $('#modal-btnconf2-uno').on('click',function(){
                $('#modal-conf2').modal('hide');
                $('#nreq').css('display','none');
                $('#listareq_load').css('display','block');
                var table = $('#examplec').DataTable();
                table.destroy();                
                $('#examplec').DataTable( {
            language: {
                search: "Buscar:",
                lengthMenu:"Mostrar _MENU_ elementos",
                info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                paginate: {
                    first:      "Primero",
                    previous:   "Anterior",
                    next:       "Siguiente",
                    last:       "Último"
                },
             },
                    "aaSorting": [[0,'desc']],
                    ajax: {
                        beforeSend: function() {  }, //Show spinner
                        complete: function() { $('#listareq_load').css('display','none'); }, //Hide spinner
                        url:"ajax.php?c=ventas&f=a_listaVentas&idoc="+idoc,
                        type: "POST",
                        data: function ( d )    {
                        //d.site = $("#nombredeusuario").val();
                        } 
                    }
                });
                //$('#listareq_load').css('display','none');
                $('#listareq').css('display','none');
                $('#listarec').css('display','block');
                
            });
            $('#modal-btnconf2-dos').on('click',function(){
                $('#modal-conf2').modal('hide');
                return false;
            });
        }else{
            $('#modal-conf2').modal('hide');
            $('#nreq').css('display','none');
            $('#listareq_load').css('display','block');
            var table = $('#examplec').DataTable();
            table.destroy();
            $('#examplec').DataTable( {
            language: {
                search: "Buscar:",
                lengthMenu:"Mostrar _MENU_ elementos",
                info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                paginate: {
                    first:      "Primero",
                    previous:   "Anterior",
                    next:       "Siguiente",
                    last:       "Último"
                },
             },
                "aaSorting": [[0,'desc']],
                ajax: {
                    beforeSend: function() {  }, //Show spinner
                    complete: function() { $('#listareq_load').css('display','none'); }, //Hide spinner
                    url:"ajax.php?c=ventas&f=a_listaVentas&idoc="+idoc,
                    type: "POST",
                    data: function ( d )    {
                        //d.site = $("#nombredeusuario").val();
                    }  
                }
            });
            $('#listareq').css('display','none');
            $('#listarec').css('display','block');
        }
        
    }

    function listareq(){

        span = $('#ph').find('span').text();
        if($('#nreq').is(':visible') && span!='Visualizar venta') {
            $('#modal-conf2').modal('show');
            $('#modal-btnconf2-uno').on('click',function(){
                $('#modal-conf2').modal('hide');
                $('#nreq').css('display','none');
                $('#listareq_load').css('display','block');
                var table = $('#example').DataTable();
                table.destroy();                
                $('#example').DataTable( {
            language: {
                search: "Buscar:",
                lengthMenu:"Mostrar _MENU_ elementos",
                info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                paginate: {
                    first:      "Primero",
                    previous:   "Anterior",
                    next:       "Siguiente",
                    last:       "Último"
                },
             },
                    "aaSorting": [[0,'desc']],
                    ajax: {
                        beforeSend: function() {  }, //Show spinner
                        complete: function() { $('#listareq_load').css('display','none'); }, //Hide spinner
                        url:"ajax.php?c=ventas&f=a_listaOrdenesEnvio",
                        type: "POST",
                        data: function ( d )    {
                            //d.site = $("#nombredeusuario").val();
                        }  
                    }
                });
                //$('#listareq_load').css('display','none');
                $('#listarec').css('display','none');
                $('#listareq').css('display','block');
                
            });
            $('#modal-btnconf2-dos').on('click',function(){
                $('#modal-conf2').modal('hide');
                return false;
            });
        }else{
            $('#modal-conf2').modal('hide');
            $('#nreq').css('display','none');
            $('#listareq_load').css('display','block');
            var table = $('#example').DataTable();
            table.destroy();
            $('#example').DataTable( {
            language: {
                search: "Buscar:",
                lengthMenu:"Mostrar _MENU_ elementos",
                info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                paginate: {
                    first:      "Primero",
                    previous:   "Anterior",
                    next:       "Siguiente",
                    last:       "Último"
                },
             },

             
                "aaSorting": [[0,'desc']],
                ajax: {
                    beforeSend: function() {  }, //Show spinner
                    complete: function() { $('#listareq_load').css('display','none'); }, //Hide spinner
                    url:"ajax.php?c=ventas&f=a_listaOrdenesEnvio",
                    type: "POST",
                    data: function ( d )    {
                        //d.site = $("#nombredeusuario").val();
                    }  
                }
            });
            $('#listarec').css('display','none');
            $('#listareq').css('display','block');
        }
        
    }

    function refreshCants(idProducto,cadcar,op){
        

        idprodmodal=idProducto;
        valActual = $("#tr_"+idProducto+"[ch='"+cadcar+"']").find('#cantr_'+idProducto+'_prod').val()*1;


        valUnit = $("#tr_"+idProducto+"[ch='"+cadcar+"']").find('#valUnit').text();
        valUnit=valUnit*1;

        valImporte = valActual*valUnit;
        $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valImporte").attr('implimpio',valImporte);
        $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valImporte").text(valImporte).currency();
        valcurren = $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valImporte").text();
        table = $('#tablaprods').DataTable();

        $("#tr_"+idProducto+" input").focus();

        recalcula(op);



    }

    function truncar(numero,decimales){
       return (Math.floor(100 * numero) / 100).toFixed(2);
    }

    
    function recalculaVent(){


        var subtotal = 0;
var total = 0;
var productos = '';
var arrprods = new Array();

    $("#filasprods tr").each(function (index) 
    {   //console.log($("#tablita input:hidden"));
        
        //contador++;
        idp = $(this).attr('id');
        spliidp = idp.split('_');
        idProducto = spliidp[1];
        //cantidad = $(this).find('.numeros').val();
        //precio = $(this).find('#valUnit').find('input').val();
        //precio = $(this).find('#prelis').val();


        precio = $(this).find('#valUnit').text();

        cantidad = $(this).find('td:nth-child(7)').text();
        //if(cantidad > 0){
           
            subtotal = parseFloat(precio) * parseFloat(cantidad);
            productos +=idProducto+'-'+cantidad+'-'+precio+'/';

            arrprods.push(idProducto);
        //}else{

            //}
        console.log(arrprods);
        total +=parseFloat(subtotal);
        subtotal = 0;
    });
  

    
    $.ajax({
        url: 'ajax.php?c=compras&f=calculaPrecios',
        type: 'POST',
        dataType: 'json',
        data: {productos: productos},
    })
    .done(function(data) {

    $('#impsxx').empty();
    $('.totalesDivxx').empty();
    $('#impsxx').append('<div class="row"><div class="col-sm-4" style="background-color:#f1f1f1;"><label>Monto venta</label></div></div>');
    $('#impsxx').append('<div class="row">'+
                    '<div class="col-sm-2" style="background-color:#f1f1f1;"><label>Subtotal:</label></div>'+
                    '<div class="col-sm-2" style="background-color:#f1f1f1;"><label>$'+truncar(data.cargos.subtotal)+'</label></div>'+
                    '</div>');
    $.each(data.cargos.impuestosPorcentajes, function(index, val) {
            console.log(data.cargos.idprod);
            $('#impsxx').append('<div class="row">'+
                        '<div class="col-sm-2" style="background-color:#f1f1f1;"><label>'+index+':</label></div>'+
                        '<div class="col-sm-2" style="background-color:#f1f1f1;"><label>$'+truncar(val)+'</label></div>'+
                        '</div>'); 
        });
    $('#impsxx').append('<div class="row">'+
                        '<div class="col-sm-2" style="background-color:#f1f1f1;"><label>Total:</label></div>'+
                        '<div class="col-sm-2" style="background-color:#f1f1f1;"><label>$'+truncar(data.cargos.total)+'</label></div>'+
                        '</div>');


       

    });
}


    function recalcula(op){
        if(op==2){
            recalculaVent();
        }

        var subtotal = 0;
var total = 0;
var productos = '';
var arrprods = new Array();

    $("#filasprods tr").each(function (index) 
    {   //console.log($("#tablita input:hidden"));
        
        //contador++;
        idp = $(this).attr('id');
        spliidp = idp.split('_');
        idProducto = spliidp[1];
        cantidad = $(this).find('.numeros').val();
        //precio = $(this).find('#valUnit').find('input').val();
        //precio = $(this).find('#prelis').val();


        precio = $(this).find('#valUnit').text();
        //if(cantidad > 0){
           
            subtotal = parseFloat(precio) * parseFloat(cantidad);
            productos +=idProducto+'-'+cantidad+'-'+precio+'/';

            arrprods.push(idProducto);
        //}else{

            //}
        console.log(arrprods);
        total +=parseFloat(subtotal);
        subtotal = 0;
    });
    //alert(productos);

    
    $.ajax({
        url: 'ajax.php?c=compras&f=calculaPrecios',
        type: 'POST',
        dataType: 'json',
        data: {productos: productos},
    })
    .done(function(data) {


        console.log(data);
        $('#imps').empty();
        $('.totalesDiv').empty();
        $('#imps').append('<div class="row"><div class="col-sm-9"><label>&nbsp;</label></div></div>');
        $('#imps').append('<div class="row">'+
                        '<div class="col-sm-6"><label>Subtotal:</label></div>'+
                        '<div class="col-sm-6"><label>$'+truncar(data.cargos.subtotal)+'</label></div>'+
                        '</div>');

        $.each(data.cargos.impuestosPorcentajes, function(index, val) {
            console.log(data.cargos.idprod);
            $('#imps').append('<div class="row">'+
                        '<div class="col-sm-6"><label>'+index+':</label></div>'+
                        '<div class="col-sm-6"><label>$'+truncar(val)+'</label></div>'+
                        '</div>'); 
        });

        cadimps='';
        $.each(data.cargos.ppii, function(index, val) {
            cadimps+=val+'|';
        });
        $('#cadimps').val(cadimps); 
        console.log(cadimps);

        
        $('#imps').append('<div class="row">'+
                        '<div class="col-sm-6"><label>Total:</label></div>'+
                        '<div class="col-sm-6"><label>$'+truncar(data.cargos.total)+'</label></div>'+
                        '</div>');

        $('#ist').val(data.cargos.subtotal);
        $('#it').val(data.cargos.total);
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
    $('#totalOrden').val(total);
    $('#totalOrdenLable').text(total);
        //tdTotals();

    }              

    function msg_error(error){
        $('#error_1').html('<div class="col-sm-12" style="padding-top:10px; display:block;">\
                    <div class="alert alert-danger">\
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
                        <strong>Atencion!</strong> Tienes que agregar productos para poder continuar.\
                    </div>\
                    </div>');
        $('#error_1').css('display','block');
    }

    function disabled_btn(btn,text){
        txt_orig= $(btn).val();
        $(btn).prop('disabled', true);
        $(btn).text(text);
    }

    function enabled_btn(btn,text){
        $(btn).prop('disabled', false);
        $(btn).text(text);
    }
    
    function removeProdReq(idProducto){

        $('#tr_'+idProducto).remove();
        tdTotals();
        if(!$( "#filasprods tr").length ) {
           $('#panel_tabla').css('display','none');
        } 

        
    }

</script>