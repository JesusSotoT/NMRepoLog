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

<div id="modal-adju" class="modal sfade">
    <div class="modal-dialog">
        <div class="modal-content panel-default">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Adjuntar XML'S</h4>
            </div>
            <div id="bodyespecialxx" class="modal-body">
                <div id="adju_header" class="col-sm-12" style="padding:10px 0 10px 0;">
                    &nbsp;
                </div>
                <div class="col-sm-12" style="padding:10px 0 10px 0;">
                    <b>Recepciones</b>
                </div>
                <div id="adju_recep" class="col-sm-12" style="padding:10px 0 10px 0;">
                    Cargando...
                </div>
                <div class="col-sm-12" style="padding:10px 0 10px 0;">
                    <b>Xml's Adjuntos</b>
                </div>
                <div id="adju_xmls" class="col-sm-12" style="padding:10px 0 10px 0;">
                    Cargando...
                </div>

                <div class="col-sm-12" style="padding:10px 0 10px 0;">
                    <b>Subir archivos xml</b>
                </div>
                <div id="divxmls" class="col-sm-12" style="padding:0px;">
                <div class="form-group"  style="padding:0px;">
                    
                    <form name='fac' id='fac' action='' method='post' enctype='multipart/form-data'>
                    <div class="col-sm-10" style="padding:0px;">
                        
                        <input type='file' name='factura[]' id='factura' onchange='check_file()'>
                        <input type='hidden' name='plz' id='plz' value='lala'>
                        
                    </div>
                    <div class="col-sm-10" style="margin-top:10px; padding:0px;">
                        <input type='submit' id='buttonFactura' value='Asociar Factura'>
                        <div id="resultasoc" style="margin-top:10px;">
                            
                        </div>
                        
                        <span id='verif' style='color:green;display:none;'>Verificando...</span>
                    </div>
                    </form>
                </div>
                </div>

                <div class="row">
                </div>
            </div>
            <div class="modal-footer">
                <button id="modal-adju-uno" type="button" class="btn btn-default">Salir</button> 
            </div>
        </div>
    </div> 
</div> 

<div id="modal-nc" class="modal sfade">
    <div class="modal-dialog">
        <div class="modal-content panel-default">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Devolución de productos</h4>
            </div>
            <div id="bodyespecialnc" class="modal-body">
                    <input id='reqnota' val='' type="hidden">
                    <div id="segurodev" class="col-sm-12" style="padding:10px 0 10px 0;">
                        ¿Estas seguro de realizar la devolucion de los productos seleccionados?
                    </div>
                    <div id="divxmls2" class="col-sm-12" style="padding:10px 0 10px 0;">
                    <div class="col-sm-12" style="padding:10px 0 10px 0;">
                        Tienes que adjuntar una nota de credito (xml)
                    </div>
                    <div class="form-group">
                        
                        <form name='fac' id='fac2' action='' method='post' enctype='multipart/form-data'>
                        <div class="col-sm-10">
                            
                            <input type='file' name='factura[]' id='factura2' onchange='check_file2()'>
                            <input type='hidden' name='plz' id='plz' value='lala'>
                            
                        </div>
                        <div class="col-sm-12" style="margin-top:10px;">
                            <input type='submit' id='buttonFactura2' value='Asociar Factura'>
                            <div id="resultasoc2" style="margin-top:10px;">
                                
                            </div>
                            
                            <span id='verif' style='color:green;display:none;'>Verificando...</span>
                        </div>
                        </form>
                    </div>
                    </div>
                    <div class="row">
                    </div>
            </div>
            <div class="modal-footer">
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

<div id="modal-recepv" class="modal sfade">
    <div id="hiddensProdsv">

    </div>
    <div class="modal-dialog">
        <div class="modal-content panel-default">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Devolución de producto</h4>
            </div>
            <div id="bodyespecialv" class="modal-body">
            </div>
            <div class="modal-footer">
                <button id="modal-recep-unov" type="button" class="btn btn-default">Continuar</button> 
                <button id="modal-recep-dosv" type="button" class="btn btn-default">Cancelar</button>
            </div>
        </div>
    </div> 
</div> 


<!-- CHRIS - COMMENTS
=============================*/
//Modales precargados
//Modal 1 y modal 2 
-->
<div id="modal-conf5" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Devolución realizada!</h4>
            </div>
            <div class="modal-body">
                <p>La devolucion fue realizada exitosamente.</p>
            </div>
        </div>
    </div> 
</div> 

<div id="modal-conf3" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Recepcion guardada!</h4>
            </div>
            <div class="modal-body">
                <p>La recepcion fue guardada exitosamente.</p>
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
                <p>Tienes una orden de compra sin recibir, ¿Deseas continuar sin realizar la recepcion?</p>
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
                <p>Tienes una orden de compra sin recibir, ¿Deseas continuar sin realizar la recepcion?</p>
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
            <div class="col-xs-12 col-md-12" style="padding:0px 0px 0px 3px;"><h3>Modulo Compras</h3></div>
            <input type="hidden" id="ist" value="0">
            <input type="hidden" id="it" value="0">
            <input type="hidden" id="cadimps" value="0">
        </div>
        <?php if($vv!=1){ ?>
        <div class="row" style="margin-bottom:10px;">
            <button class="btn btn-default" type="button" onclick="listareq();" >Ordenes de compra autorizadas</button>
            <button class="btn btn-default" type="button" onclick="listarec(0);" >Recepciones</button>
        </div>
        <?php } ?>
        <div id="nreq_load" class="row" style="display:none;font-size:12px;padding:2px;">
            <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
        </div>
       
        <div id="listareq_load" class="row" style="display:none;font-size:12px;padding:2px;">
            <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
        </div>

        <div id="listareq" class="row" style="display:block;margin-top:20px;font-size:12px;display:none;">
            <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr><!--
                        <th>No. OC.</th>
                        <th>Fecha</th>
                        <th>Solicitante</th>
                        <th>Area</th>
                        <th>Tipo gasto</th>
                        <th>Subtotal</th>
                        <th>Tipo</th>
                        <th>Estatus</th>
                        <th>Recepcion</th>-->

                        <th>No. OC.</th>
                        <th>Fecha</th>
                        <th>Proveedor</th>
                        <th>Solicitante</th>
                        <th>Fecha entrega</th>
                        <th>Almacen</th>
                        <th>Total</th>
                        <th>Prioridad</th>
                        <th>Estatus</th>
                        <th class="no-sort" style="text-align: center;">Acciones</th>

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
                        <th>No. OC.</th>
                        <th>No. Recepcion</th>
                        <th>Fecha recepcion</th>
                        <th>Proveedor</th>
                        <th>Recibio</th>
                        <th>Importe recibido</th>
                        <th>Prioridad</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
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
                        <label id="nodeque" class="col-sm-2 control-label text-left">No. OC</label>
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
                                    <option value="<?php echo $v['idempleado']; ?>"><?php echo $v['nombre']; ?> (<?php echo $v['nomarea']; ?>)</option>
                                <?php } ?>
                            </select>
                        </div>
                        <label class="col-sm-2 control-label text-left">Area</label>
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
                        <label class="col-sm-2 control-label text-left">Almacen</label>
                        <div class="col-sm-2" style="color:#ff0000;">
                            <select id="c_almacen"  style="width:100%;">
                                <option value="0">Seleccione</option>
                              <?php foreach ($almacenes as $k => $v) { ?>
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
                                <label class="col-sm-2 control-label text-left">Proveedor</label>
                                <div class="col-sm-2" style="color:#ff0000;">
                                    <select id="c_proveedores" style="width:100%;">
                                        <option value="0">Seleccione</option>
                                        <?php foreach ($proveedores as $k => $v) { ?>
                                            <option value="<?php echo $v['idPrv']; ?>"><?php echo $v['razon_social']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <label class="col-sm-2 control-label text-left">Producto</label>
                                <div id="div_productos" class="col-sm-4" style="color:#ff0000;">
                                    <select id="c_productos" disabled="disabled"  style="width:85%;">
                                        <option value="0">Seleccione</option>
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
                           <th width="20%" align="left">Almacen</th>
                           <th width="10%" align="left">Codigo</th>
                           <th width="35%" align="left">Descripcion</th>
                           <th width="8%" align="left">$Unitario</th>
                           <th width="8%" align="left">Cant. OC</th>
                           <th width="8%" align="left">Importe OC</th>
                           <th width="8%" align="left">Recibidos</th>
                           <th id="arecibir" width="8%" align="left">Cant. A recibir</th>
                           <th class="no-sort" width="1%" align="left">&nbsp;</th>
                           <th  class="no-sort" width="1%" align="left">&nbsp;</th>
                           
                           <th id="coldevos" class="no-sort" width="1%" align="right">&nbsp;</th>
                          </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="7" style="text-align:right">&nbsp;</th>
                                <th colspan="5"></th>
                            </tr>
                        </tfoot>
                        <tbody id="filasprods">
                        </tbody>
                      </table>
                    </div>
                    
                   

                    <div id="panel_tabla_cons" class="col-sm-12" style="padding: 15px 37px 15px 31px; display:none;">
                        <table width="100%" id="tablacons" class="table table-hover">
                        <thead>
                          <tr>
                            <th width="10%" align="left">No. Recepcion</th>
                           <th width="10%" align="left">Codigo</th>
                           <th width="35%" align="left">Descripcion</th>
                           <th width="8%" align="left">$Unitario</th>
                           <th width="8%" align="left">Importe OC</th>
                           <th width="8%" align="left">Cant. Recibidos</th>
                           <th width="8%" align="left">Cant. A comprar</th>
                           <th width="25%" align="left">Mover a</th>
                         
                          </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="5" style="text-align:right">&nbsp;</th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                        <tbody id="filasprodsconsig">
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
                            <input id="desc_concepto" type="text" style="width:100%">
                        </div>
                    </div>
                    </div>

                    <div class="col-sm-12" style="display:none;">
                    <div class="form-group">
                        <label class="col-sm-2 control-label text-left">No. Factura</label>
                        <div class="col-sm-2">
                            <input id="nofactrec2" type="text">
                        </div>
                        <label class="col-sm-2 control-label text-center">Fecha factura</label>
                        <label class="col-sm-2 control-label text-center">
                        <input style="height:30px;width:100%" id="date_recepcion2" type="text" class="form-control">
                        </label>
                        <label class="col-sm-2 control-label text-right">$Factura</label>
                        <div class="col-sm-2">
                            <input id="impfactrec2" type="text" style="width:100%">
                            <input id="desc_concepto2" type="text" style="width:100%">
                        </div>
                    </div>
                    </div>

                    <!--<div id="divxmls" class="col-sm-12" style="padding-top:10px;">
                    <div class="form-group">
                        <label id="divxmls_txt" class="col-sm-2 control-label text-left">Subir factura(s) xml</label>
                        <form name='fac' id='fac' action='' method='post' enctype='multipart/form-data'>
                        <div class="col-sm-10">
                            
                            <input type='file' name='factura[]' id='factura' onchange='check_file()'>
                            <input type='hidden' name='plz' id='plz' value='lala'>
                            
                        </div>
                        <div class="col-sm-10" style="margin-top:10px;">
                            <input type='submit' id='buttonFactura' value='Asociar Factura'>
                            <div id="resultasoc" style="margin-top:10px;">
                                
                            </div>
                            
                            <span id='verif' style='color:green;display:none;'>Verificando...</span>
                        </div>
                        </form>
                    </div>
                    </div>-->
                    
                    

  


                    <div class="col-sm-12" style="padding-top:10px;">
                    <div class="form-group">
                        <label class="col-sm-2 control-label text-left">Observaciones</label>
                        <div class="col-sm-10" style="color:#ff0000;">
                            <textarea class="form-control" rows="3" id="comment"></textarea>
                        </div>
                    </div>
                    <div class="form-group" >
                     &nbsp;
                    </div>
                    <div class="form-group">
                        <label class="col-sm-10 control-label text-right">Fecha de recepcion</label>
                        <div class="col-sm-2" style="color:#ff0000;">
                            <input style="height:30px;width:100%" id="date_recep" type="text" class="form-control">
                        </div>
                    </div>
                    </div>




                    <div class="col-sm-12" style="padding-top:10px;">
                    <div class="form-group">
                        <div id="btns" class="col-sm-12 text-right">
                            <!--
                            <button id="btn_savequit" class="btn btn-sm btn-info pull-center" type="button" style="height:28px;" >Guardar y salirdddd</button> 
                        -->
                            <?php if($vv!=1){ ?>
                            <button  id="btn_devolucion_ver" activo="5" class="col-sm-2 btn btn-sm btn-primary pull-center" type="button" style="height:28px;display:none;">Ver devoluciones</button>

                            <button  id="btn_authquit_p" activo="5" class="btn btn-sm btn-primary pull-center" type="button" style="height:28px;">Parcialmente surtido</button>
                            <button  id="btn_solacla" class="btn btn-sm btn-primary pull-center" type="button" style="height:28px;">Solicitar aclaracion</button>
                            <button  id="btn_authquit_ok" activo="4" class="btn btn-sm btn-success pull-center" type="button" style="height:28px;">Recibido ok</button>
                            <button  id="btn_devolucion" activo="5" class="col-sm-2 btn btn-sm btn-danger pull-right" type="button" style="height:28px;display:none;margin:0 0 0 4px;">Hacer devolucion</button>

                            <button style="display:none;"  id="btn_comprar_ok" activo="4" class="btn btn-sm btn-danger pull-center" type="button" style="height:28px;">Comprar consignacion</button>
                            <?php } ?>
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

<!-- Modificaciones RC -->
<script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
<script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
<script src="../../libraries/export_print/buttons.html5.min.js"></script>
<script src="../../libraries/export_print/jszip.min.js"></script>
<!--Button Print css -->
<link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">

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

</body>

<script>
var disprod=0;
var table = '';

function redondeo(numero, decimales){
var flotante = parseFloat(numero);
var resultado = Math.round(flotante*Math.pow(10,decimales))/Math.pow(10,decimales);
return resultado;
}

    function check_file2()
    {
        var ext = $('#factura2').val();
        ext = ext.split('.');
        ext = ext[1];
        if(ext != 'zip' && ext != 'xml')
        {
            alert("Archivo Inválido \nEl archivo debe tener una extensión xml o zip.");
            $("#factura").val('');
        }
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

        vv='<?php echo $vv; ?>';
        if(vv==1){
            idrec='<?php echo $id_rec; ?>';
            $.ajax({
                url:"ajax.php?c=compras&f=a_get_idoc_idrec",
                type: 'POST',
                data:{idrec:idrec},
                success: function(r){
                    editRec(idrec,4,r);
                }
            });
        }else{
            listareq();
        }

        $('#date_recep').datepicker({
                format: "yyyy-mm-dd"
        });

        $('#date_recep').datepicker("update", new Date());

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
                    //$('#resultasoc').html('<button id="xmlfile" name="" class="btn btn-danger btn-sx active">XML no asociado</button>');
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
                    fac_desc_concepto=datosfac[4];
                    fac_subtotal=datosfac[5];
                    xmlfile=data1[6];
                    idoc = $('#idocadju').val();//OC

                    $.ajax({
                        url:"ajax.php?c=compras&f=a_guardaXmlAdju",
                        type: 'POST',
                        data:{fac_folio:fac_folio,fac_fecha:fac_fecha,fac_total:fac_total,fac_uuid:fac_uuid,concepto:fac_desc_concepto,xmlfile:xmlfile,idoc:idoc,fac_subtotal:fac_subtotal},
                        success: function(r){
                            if(r>0){
                                $('#adju_recep').html('Cargando...');
                                $('#adju_xmls').html('Cargando...');
                                $('#modal-adju').modal('hide');
                                alert('XML adjuntado con exito');
                            }
                        }
                    });

                    /*
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
      <td id="fftt" width="150">'+fac_total+'</td>\
      <td width="100"><a id="xmlfile" name="'+data1[6]+'" class="btn btn-success btn-xs" onclick="openxml(\''+data1[6]+'\')">Ver xml</a>\
      </td><td width="50" align="center"><a class="btn btn-danger btn-xs" onclick="quitafactasoc(\''+data1[6]+'\');"><span class="glyphicon glyphicon-remove"></span> Quitar</a>\
    </td></tr>\
  </tbody></table>');

                    if(fac_folio==''){
                        fac_folio=fac_uuid;
                    }

                    $('#nofactrec').val(fac_folio);
                    $('#date_recepcion').val(fac_fecha);
                    $('#impfactrec').val(fac_total);
                    $('#desc_concepto').val(fac_desc_concepto);

                    //alert(fac_folio+' . '+fac_fecha+' . '+fac_total);
                    */
                    
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

                //$('#resultasoc').html('<button id="xmlfile" name="" class="btn btn-danger btn-sx active">XML no asociado</button>');
            }
        
    
    });
    e.preventDefault();
  });


    $('#fac2').submit( function( e ) {
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
                    $('#resultasoc2').html('<button id="xmlfile" name="" class="btn btn-danger btn-sx active">XML no asociado</button>');
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
                    fac_desc_concepto=datosfac[4];
                    $('#resultasoc2').html('<table w style="margin: 15px 0px 8px; width: 100%; border: 1px solid rgb(236, 236, 236); background-color: rgb(250, 250, 250);">\
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
      </td><td width="50" align="center"><a class="btn btn-danger btn-xs" onclick="quitafactasoc2(\''+data1[6]+'\');"><span class="glyphicon glyphicon-remove"></span> Quitar</a>\
    </td></tr>\
  </tbody></table>');

                    if(fac_folio==''){
                        fac_folio=fac_uuid;
                    }

                    $('#nofactrec2').val(fac_folio);
                    $('#date_recepcion2').val(fac_fecha);
                    $('#impfactrec2').val(fac_total);
                    $('#desc_concepto2').val(fac_desc_concepto);

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

        $("#btn_comprar_ok").click(function() {
            esconsig=$('#esconsig').val();
            activo = $(this).attr('activo');
            option = 1;

            id_req = $('#txt_nreq').text();
            idrequi = $('#idrequi').val();

            
            disabled_btn('#btn_authquit_ok','Procesando...');

            solicitante=$('#c_solicitante').val();
            tipogasto=$('#c_tipogasto').val();
            moneda=$('#c_moneda').val();
            proveedor=$('#c_proveedores').val();

            almacen=$('#c_almacen').val();

            //xmlfile=$('#xmlfile').attr('name');
            xmlfile='';

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
/*
            nofactrec = $('#nofactrec').val();
            date_recepcion = $('#date_recepcion').val();
            impfactrec = $('#impfactrec').val();
            desc_concepto = $('#desc_concepto').val();

*/
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
            }else if(ist==0 && deten==1){ 
                alert('El subtotal debe ser mayor a 0'); 
                deten=1; 
            }else if(it==0 && deten==1){ 
                alert('El total debe ser mayor a 0'); 
                deten=1; 
            }


            

            if(deten==1){
                enabled_btn('#btn_authquit_ok','Recibido ok');
                return false;
            }



            

            idsProductos = $('#filasprodsconsig tr').map(function() {
                cantrecibi=$(this).find('td:nth-child(6)').text()*1;
                cantvendi= $(this).find('td:nth-child(7)').text()*1;

                cant=cantrecibi-cantvendi;


                //cant = $(this).find('.numeros').val();
                idalm = $(this).find('#c_almacen').val();
                esp = $(this).attr('especial');
                tp = $(this).attr('tp');
                ch = $(this).attr('ch');


                trid = this.id;
                id = trid.split('tr_');

                if (typeof id[1] !== "undefined") {
                    id= id[1]+'>#'+cant+'>#'+idalm+'>#'+esp+'>#'+tp+'>#'+ch;
                }
                
                return id;
            }).get().join(',# ');  

            console.log(idsProductos);
           
           

            if(idsProductos==''){
                msg_error(1);
                enabled_btn('#btn_authquit_ok','Recibido ok');
                return false;
            }else{
                console.log(idsProductos);
                $.ajax({
                    url:"ajax.php?c=compras&f=a_compraConsignacion",
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
                        //nofactrec:nofactrec,
                        //date_recepcion:date_recepcion,
                        //impfactrec:impfactrec,
                        //xmlfile:xmlfile,
                        //desc_concepto:desc_concepto,
                        ist:ist,
                        it:it,
                        date_recep:date_recep,
                        esconsig:esconsig

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
            }

        
        });

        $("#btn_authquit_ok").click(function() {
        var r = confirm("¿Estas seguro de realizar esta recepcion y cerrar esta orden de compra?");
        if (r == true) {

            var cxp = 0;
            if(confirm("¿Quuieres que se envia a saldo por pagar?")){
                cxp = 1;
            } else {
                cxp = 0;
            }

            esconsig=$('#esconsig').val();
            activo = $(this).attr('activo');
            option = 1;

            id_req = $('#txt_nreq').text();
            idrequi = $('#idrequi').val();

            
            disabled_btn('#btn_authquit_ok','Procesando...');

            solicitante=$('#c_solicitante').val();
            tipogasto=$('#c_tipogasto').val();
            moneda=$('#c_moneda').val();
            proveedor=$('#c_proveedores').val();

            almacen=$('#c_almacen').val();

            //xmlfile=$('#xmlfile').attr('name');

            fechahoy=$('#date_hoy').val();
            fechaentrega=$('#date_entrega').val();
            date_recep=$('#date_recep').val();

            ist=$('#ist').val();
            it=$('#it').val();
            fftt=$('#fftt').text();
            

            if(moneda==1){
                moneda_tc=1;
            }else{
                moneda_tc=$('#moneda_tc').val();  
            }
            

            

            urgente = $('input[name=radiourgente]:checked').val();
            if ($('#checkbox').is(':checked')) {
                inventariable=1;
            }else{
                inventariable=0;
            }

/*
            nofactrec = $('#nofactrec').val();
            date_recepcion = $('#date_recepcion').val();
            impfactrec = $('#impfactrec').val();
            desc_concepto = $('#desc_concepto').val();
*/
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

            

            idsProductos = $('#filasprods tr').map(function() {
                cant = $(this).find('.numeros').val();
                idalm = $(this).find('#c_almacen').val();
                esp = $(this).find('.numeros').attr('especial');
                tp = $(this).attr('tp');
                ch = $(this).attr('ch');


                trid = this.id;
                id = trid.split('tr_');

                if (typeof id[1] !== "undefined") {
                    id= id[1]+'>#'+cant+'>#'+idalm+'>#'+esp+'>#'+tp+'>#'+ch;
                }
                
                return id;
            }).get().join(',# ');  

            console.log(idsProductos);

           
            //fftt=$('#fftt').val();

            
            if(idsProductos==''){
                msg_error(1);
                enabled_btn('#btn_authquit_ok','Recibido ok');
                return false;
            }else{
                //var r = confirm("El monto de la factura ("+fftt+") no coincide con el total de la venta ("+it+"), ¿Desea continuar?");
                //if (r == true) {
                console.log(idsProductos);
                $.ajax({
                    url:"ajax.php?c=compras&f=a_recepcionOrden",
                    type: 'POST',
                    data:{
                        idsProductos:idsProductos,
                        solicitante:solicitante,
                        tipogasto:tipogasto,
                        moneda:moneda,
                        cxp:cxp,
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
                        //nofactrec:nofactrec,
                        //date_recepcion:date_recepcion,
                        //impfactrec:impfactrec,
                        //xmlfile:xmlfile,
                        //desc_concepto:desc_concepto,
                        ist:ist,
                        it:it,
                        date_recep:date_recep,
                        esconsig:esconsig

                    },
                    success: function(r){
                        console.log(r);
                        if(r>0){
                            $('#nreq').css('display','none');
                            resetearReq();
                            $('#modal-conf3').modal('show');
                            enabled_btn('#btn_authquit_ok','Recibido ok');
                        }else{
                            alert('Error de conexion');
                            enabled_btn('#btn_authquit_ok','Recibido ok');
                        }
                        //enabled_btn('#btn_savequit','Guardar y salir');
                    }
                });
               // }else{

                //}
            }
        }
        
        });

        $("#btn_devolucion").click(function() {
            existendevos = $('#filasprods tr').map(function() {
                cant = $(this).find('.numeros').val();
                if(cant=='' || cant==0 ){ return ''; }else{ return 's'; }
            }).get().join('');  

            if(existendevos!=''){
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
            tipodevo=$('#reqnota').val();
            if(tipodevo==0){
                devolucion2();
            }else{
                nofactrec2 = $('#nofactrec2').val();
                date_recepcion2 = $('#date_recepcion2').val();
                impfactrec2 = $('#impfactrec2').val();
                desc_concepto2 = $('#desc_concepto2').val();
                deten=0;
                if(nofactrec2==0){ 
                    alert('Tienes que subir una nota de credito'); 
                    deten=1; 
                }else if(date_recepcion2==''){ 
                    alert('Tienes que subir una nota de credito'); 
                    deten=1; 
                }else if(impfactrec2==0){ 
                    alert('Tienes que subir una nota de credito'); 
                    deten=1; 
                }
                if(deten==0){
                    devolucion2();
                }
                
                //alert('Tienes que subir una nota de credito');
            }
            
        });
        $('#modal-nc-dos').on('click',function(){
            $('#modal-nc').modal('hide');
        });


        $('#modal-adju-uno').on('click',function(){
            $('#adju_recep').html('Cargando...');
            $('#adju_xmls').html('Cargando...');
            $('#modal-adju').modal('hide');
        });


        function devolucion2(){
            esconsig=$('#esconsig').val();
            activo = $(this).attr('activo');
            option = 1;

            id_req = $('#txt_nreq').text();//OC
            id_rec = $('#txt_nreq2').text();//REC
            idrequi = $('#idrequi').val();

            
            //disabled_btn('#btn_authquit_p','Procesando...');

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
                enabled_btn('#btn_authquit_p','Parcialmente surtido');
                return false;
            }

            recibidook=0; 
            

            idsProductos = $('#filasprods tr').map(function() {
                cant = $(this).find('.numeros').val();
                idalm = $(this).find('#c_almacen').val();
                esp = $(this).find('.numeros').attr('especial');
                tp = $(this).attr('tp');
                ch = $(this).attr('ch');
       
                if(cant==''){
                    cant=0;
                }
               

                cantoc=$(this).find('td:nth-child(6)').text()*1;
                cantrecibida=$(this).find('td:nth-child(10)').text()*1;

                console.log(cantrecibida);
                console.log(cant);
                console.log(cantoc);
                if( ((cantrecibida*1)+(cant*1)) <= cantoc*1 ){
                    recibidook++;
                }else{
                    //recibidook=0;
                } 
                trid = this.id;
                id = trid.split('tr_');

                if (typeof id[1] !== "undefined") {
                    id= id[1]+'>#'+cant+'>#'+idalm+'>#'+esp+'>#'+tp+'>#'+ch;
                }
                console.log(id);
                return id;
            }).get().join(',# ');  
            

            if(recibidook==0){
                activo=4;
                if (typeof xmlfile == "undefined" || xmlfile=='') {
                    alert('Tienes que asociar una factura a esta recepcion'); 
                    deten=1; 
                }
            }

            if(deten==1){
                enabled_btn('#btn_authquit_p','Parcialmente surtido');
                return false;
            }

            if(idsProductos==''){
                msg_error(1);
                enabled_btn('#btn_authquit_p','Parcialmente surtido');
                return false;
            }else{

                console.log(idsProductos);
                $.ajax({
                    url:"ajax.php?c=compras&f=a_devolucionProveedor",
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
                        id_rec:id_rec,
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
                        esconsig:esconsig

                    },
                    success: function(r){
                        console.log(r);
                        if(r>0){
                            $('#modal-nc').modal('hide');
                            $('#nreq').css('display','none');
                            resetearReq();
                            $('#modal-conf5').modal('show');
                        }else{
                            alert('Error de conexion');
                            enabled_btn('#btn_authquit_p','Parcialmente surtido');
                        }
                        //enabled_btn('#btn_savequit','Guardar y salir');
                    }
                });
            }

        
        };


        $("#btn_authquit_p").click(function() {
            esconsig=$('#esconsig').val();
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

            //xmlfile=$('#xmlfile').attr('name');


            fechahoy=$('#date_hoy').val();
            fechaentrega=$('#date_entrega').val();
            date_recep=$('#date_recep').val();


            ist=$('#ist').val();
            it=$('#it').val();
            

            if(moneda==1){
                moneda_tc=1;
            }else{
                moneda_tc=$('#moneda_tc').val();  
            }
            

            

            urgente = $('input[name=radiourgente]:checked').val();
            if ($('#checkbox').is(':checked')) {
                inventariable=1;
            }else{
                inventariable=0;
            }

/*
            nofactrec = $('#nofactrec').val();
            date_recepcion = $('#date_recepcion').val();
            impfactrec = $('#impfactrec').val();
            desc_concepto = $('#desc_concepto').val();

            */

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
                enabled_btn('#btn_authquit_p','Parcialmente surtido');
                return false;
            }

            recibidook=0; 
            

            idsProductos = $('#filasprods tr').map(function() {
                cant = $(this).find('.numeros').val();
                idalm = $(this).find('#c_almacen').val();
                esp = $(this).find('.numeros').attr('especial');
                tp = $(this).attr('tp');
                ch = $(this).attr('ch');

                cantoc=$(this).find('td:nth-child(6)').text()*1;
                cantrecibida=$(this).find('td:nth-child(8)').text()*1;
                if( ((cantrecibida*1)+(cant*1)) < cantoc*1 ){
                    recibidook++;
                }else{
                    //recibidook=0;
                } 
                trid = this.id;
                id = trid.split('tr_');

                if (typeof id[1] !== "undefined") {
                    id= id[1]+'>#'+cant+'>#'+idalm+'>#'+esp+'>#'+tp+'>#'+ch;
                }
                
                return id;
            }).get().join(',# ');  
            

            if(recibidook==0){
                activo=4;
               /* if (typeof xmlfile == "undefined" || xmlfile=='') {
                    alert('Tienes que asociar una factura a esta recepcion'); 
                    deten=1; 
                } */
            }

            if(deten==1){
                enabled_btn('#btn_authquit_p','Parcialmente surtido');
                return false;
            }

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
                        //nofactrec:nofactrec,
                        //date_recepcion:date_recepcion,
                        //impfactrec:impfactrec,
                        //xmlfile:xmlfile,
                        //desc_concepto:desc_concepto,
                        ist:ist,
                        it:it,
                        date_recep:date_recep,
                        esconsig:esconsig

                    },
                    success: function(r){
                        console.log(r);
                        if(r>0){
                            $('#nreq').css('display','none');
                            enabled_btn('#btn_authquit_p','Parcialmente surtido');
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
            dom: 'Bfrtip',
            buttons: [ 'pageLength', 'excel' ],
            language: {
                buttons: {
                    pageLength: "Mostrar %d filas"
                },
                search: "Buscar:",
                lengthMenu:"Mostrar _MENU_ elementos",
                zeroRecords:"No hay datos",
                infoEmpty:"",
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
                                    .column(9)
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(a) + intVal(b);
                                    }, 0 );
                     
                                // Total over this page
                                pageTotal = api
                                    .column(9, { page: 'current'} )
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(a) + intVal(b);
                                    }, 0 );
                     
                                // Update footer
                                pt = redondeo(pageTotal,2);
                                tt = redondeo(total,2);
                                $( api.column(8).footer() ).html(
                                    '<div id="imps">Cargando...</div>'
                                    //'$'+pt +' ( $'+ tt +' Gran total)<br><div id="imps">a</div>'
                                );
                                }
                            });

        

        $('#c_productos').select2();
        $('#c_proveedores').select2({ width: '300px' });
        $('#c_solicitante').select2();
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
                                    <td>"+data_almacen+"</td>\
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
        $('#hiddensProds').empty();
        $('#hiddensProdsv').empty();
        $('#modal-recep').find('input').val('');


        


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
        $('#date_recep').prop('disabled', false);
        $('#impfactrec').prop('disabled', false);

        $('#btns .btn').prop('disabled', false);
        $('#btn_devolucion').css('display', 'none');
        $('#btn_devolucion_ver').css('display', 'none');
        //$('#btns .btn').css('display', 'block');


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

        $('#nofactrec').val('');
        $('#date_recepcion').val('');
        $('#impfactrec').val('');
        $('#ist').val(0);
        $('#it').val(0);
        $('#cadimps').val(0);

        $('#arecibir').text('Cant. a recibir');
        $('#coldevos').html('&nbsp;');

    }

    function addseries(idProd,cadcar){
        cantant=$("#tr_"+idProd+"[ch='"+cadcar+"']").find('#crecibida').text();
        cantseries = $('#modalcantrecibida').val();
        txtcant=$('#tr_'+idProd+' td:eq(6)').text();
        txtcant = $("#tr_"+idProd+"[ch='"+cadcar+"']").find('#cantidUnic').text();

        console.log("cantant-"+cantant,"cantseries-"+cantseries,"txtcant-"+txtcant);
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
                            <input class="inpsprod" type="text" id="serie'+((i*1)+1)+'">\
                        </div>\
                    </div>';
            };
            $('#seriesprods__'+idProd).html(divseries);
        }else{
            alert('La cantidad de recepcion deber ser mayor a 0');
        }

    }

    function transeries(idProd){//ch@
        var string = $('#allseries').val();
        var array = string.split(',');
        divseries='';
        var i = 0;
        if(array.length <= 1000){
            for (i=0; i<=array.length - 1; i++) {
                divseries+='<div class="sprod" class="col-sm-12" style="padding:15px;">\
                                <label class="col-sm-6 control-label text-left">No serie '+((i*1)+1)+':</label>\
                                <div class="col-sm-6">\
                                    <input class="inpsprod" type="text" value="'+array[i]+'" >\
                                </div>\
                            </div>';
            };
            $('#seriesprods__'+idProd).html(divseries);
            $('#modalcantrecibida').val(i);
        }else{
            alert('Excede los mil registros!');
        }
        
    }

    function quitafactasoc2(xmlfile){
        $.ajax({
            url:"ajax.php?c=compras&f=a_quitafactasoc",
            type: 'POST',
            data:{xmlfile:xmlfile},
            success: function(r){
                if(r==1){
                    alert('No se pudo desasociar esta factura')
                }
                if(r==0){
                    $('#resultasoc2').html('');
                }
            }
        });

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

    function recibirProductov(idProd,modo,txtprod,txtcant,mod,cadcar){
esconsig=$("#esconsig").val();
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
                        <label class="col-sm-6 control-label text-left">Características:</label>\
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

            $('#bodyespecialv').html(divbody);


            $("#pedimentoslote").select2({ width: '100%' }); 

            $('#modalcantrecibida').numeric();
            cadena=$('#hiddensProdsv input[id="'+idProd+'"][ch="'+cadcar+'"]').val();   
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
    deten=0;
    $.ajax({
        async: false,
        url:"ajax.php?c=ventas&f=a_getExistencias",
        type: 'POST',
        dataType: 'json',
        data:{idProd:idProd,modo:modo,cadcar:cadcar,esconsig:esconsig},
        success: function(r){
            if(r==null){
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
                        <label class="col-sm-6 control-label text-left">Cantidad recibida:</label>\
                        <div class="col-sm-6">\
                          <input class="numeric" type="text" value="'+txtcant+'" readonly="readonly">\
                        </div>\
                    </div>\
                    '+canterioridad+'\
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
                </div>';

            $('#bodyespecialv').html(divbody);

            $('#datepedimento').datepicker({
                    format: "yyyy-mm-dd",
                    language: "es"
            });
            $("#existencias").select2({ width: '100%' }); 
            $('#tipcambio').numeric();
            cadena=$('#hiddensProdsv input[id="'+idProd+'"][ch="'+cadcar+'"]').val();        
            if(typeof cadena != "undefined"){
                cadsplit = cadena.split('->-');
                $('#modalcantrecibida').val(cadsplit[2]);
                divseries='';
                seriesplit = cadsplit[3].split(',');
                cantscoma = cadsplit[4].split(',');
                console.log(seriesplit);
                $("#existencias").val(seriesplit).trigger("change");
                i=0;
                $("#newexistencias").find('#divexistencias div').each(function( index ) {
                    cantsalmacen=cantscoma[i].split('-');
                    $(this).find('input').val(cantsalmacen[2]);
                    i++;
                });

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
                        <label class="col-sm-6 control-label text-left">Características:</label>\
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

            $('#bodyespecialv').html(divbody);

            $('#datepedimento').datepicker({
                    format: "yyyy-mm-dd",
                    language: "es"
            });
            $("#pedimentos").select2({ width: '100%' }); 
            $('#tipcambio').numeric();
            cadena=$('#hiddensProdsv input[id="'+idProd+'"][ch="'+cadcar+'"]').val();   
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
                        <label class="col-sm-6 control-label text-left">Características:</label>\
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

            $('#bodyespecialv').html(divbody);
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
            cadena=$('#hiddensProdsv input[id="'+idProd+'"][ch="'+cadcar+'"]').val();   
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
                options+='<option value="'+v.idSerie+'">'+v.serie+'</option>';
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
                        <label class="col-sm-6 control-label text-left">Características:</label>\
                        <div class="col-sm-6">\
                          <span class="label label-primary" style="cursor:pointer;">Series</span>\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Cantidad recibida:</label>\
                        <div class="col-sm-6">\
                          <input id="idUnicCant" class="numeric" type="text" value="'+txtcant+'" readonly="readonly">\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">No. series:</label>\
                        <div class="col-sm-6">\
                            <select id="series2" multiple="" >\
                                '+options+'\
                            </select>\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Cantidad a devolver:</label>\
                        <div class="col-sm-6">\
                          <input id="modalcantrecibida" class="numeric" type="text" >\
                        </div>\
                    </div>\
                    <div id="seriesprods__'+idProd+'">\
                    </div>\
                </div>';

            $('#bodyespecialv').html(divbody);
            $("#series2").select2({ width: '100%' }); 
            cadena=$('#hiddensProdsv input[id="'+idProd+'"][ch="'+cadcar+'"]').val();             
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
                        <label class="col-sm-6 control-label text-left">Características:</label>\
                        <div class="col-sm-6">\
                          <span class="label label-primary" style="cursor:pointer;">Series - Pedimentos</span>\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Cantidad recibida:</label>\
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
                        <label class="col-sm-6 control-label text-left">Cantidad a devolver:</label>\
                        <div class="col-sm-6">\
                          <input id="modalcantrecibida" class="numeric" type="text" >\
                        </div>\
                    </div>\
                    <div id="seriesprods__'+idProd+'">\
                    </div>\
                </div>';

            $('#bodyespecialv').html(divbody);
            $("#spedimentos").select2({ width: '100%' }); 

            $('#datepedimento').datepicker({
                    format: "yyyy-mm-dd",
                    language: "es"
            });

            cadena=$('#hiddensProdsv input[id="'+idProd+'"][ch="'+cadcar+'"]').val();           
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
        
        mmod = $('#modal-recepv').modal({
                        backdrop: 'static',
                        keyboard: false, 
                        show: true
                });

        $('#modal-recep-unov').on('click',function(){
            txtcant=$("#tr_"+idProd+"[ch='"+cadcar+"'] td:eq(7)").text();
            txtdevs=$("#tr_"+idProd+"[ch='"+cadcar+"'] td:eq(8)").text();

            maxadevolver = (txtcant*1)-(txtdevs*1);
            if(modo!=1 && modo!=4 && modo!=0 && modo!=5){
                modalcantrecibida=$('#modalcantrecibida').val();


                cantant=$("#cantr_"+idProd+"_prod").val();
                cantseries = $('#modalcantrecibida').val();

                if(cantseries>0){
                    if( (cantseries*1)>(txtcant*1) ){
                    //if( (cantant*1)+(cantseries*1)>(txtcant*1) ){
                        alert('La cantidad a devolver no puede superar a la cantidad solicitada en la orden de venta');
                        $('#modalcantrecibida').val( (txtcant-cantant) );
                        return false;
                    }
                }else{
                    alert('La cantidad a devolver debe ser mayor a 0');
                    return false;
                }
            }

            if(modo==0){
                existencias=$('#existencias').val();
                modalcantrecibida=0;
                cantsexistencias='';
                $( ".quantity" ).each(function( index ) {
                    modalcantrecibida=modalcantrecibida+($(this).val()*1);
                    cantsexistencias+=$(this).attr('data')+'-'+$(this).val()+',';
                });

                if(modalcantrecibida==0){
                    alert('La cantidad a devolver no puede ser 0');
                    return false;
                }


                if(modalcantrecibida>(maxadevolver*1)){
                    alert('La cantidad a devolver es mayor a la cantidad maxima a devolver ('+maxadevolver+')');
                    return false;
                }

           


                    $.ajax({
                    url:"ajax.php?c=compras&f=a_modalRecepcionv",
                    type: 'POST',
                    data:{idProd:idProd,modo:modo,modalcantrecibida:modalcantrecibida,existencias:existencias,cantsexistencias:cantsexistencias,cadcar:cadcar},
                    success: function(r){
                        $('#modal-recepv').modal('hide');
                        $('#hiddensProdsv input[id="'+idProd+'"][ch="'+cadcar+'"]').remove();
                        $('#hiddensProdsv').append('<input ch="'+cadcar+'" id="'+idProd+'" type="hidden" value="'+r+'" >');

                        if(typeof r != "undefined"){
                             rsplit = r.split('->-');
                        }
                       // alert(idProd);
                        $("#cantrv_"+idProd+"_prod"+"[chp='"+cadcar+"']").val(rsplit[2]);

                        refreshCants(idProd,cadcar);

                    }
                });
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
                    alert('La cantidad a devolver no puede ser 0');
                    return false;
                }


                if(modalcantrecibida>(maxadevolver*1)){
                    alert('La cantidad a devolver es mayor a la cantidad maxima a devolver ('+maxadevolver+')');
                    return false;
                }


                    $.ajax({
                    url:"ajax.php?c=compras&f=a_modalRecepcionv",
                    type: 'POST',
                    data:{idProd:idProd,modo:modo,modalcantrecibida:modalcantrecibida,lotes:lotes,cantslotes:cantslotes,cadcar:cadcar},
                    success: function(r){
                        $('#modal-recepv').modal('hide');
                        $('#hiddensProdsv input[id="'+idProd+'"][ch="'+cadcar+'"]').remove();
                        $('#hiddensProdsv').append('<input ch="'+cadcar+'" id="'+idProd+'" type="hidden" value="'+r+'" >');

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
                    series=$('#series2').val();
                    if(series==null){
                        alert('Tienes que seleccionar las series de tus productos a devolver');
                        return false;
                    }
                    console.log(series);
                    noseries= series.length;
                    
                    if(modalcantrecibida<noseries || modalcantrecibida>noseries){
                        alert('La cantidad de productos a devolver no concuerda con la cantidad de series');
                        return false;
                    }

                    if(modalcantrecibida>(maxadevolver*1)){
                        alert('La cantidad a devolver es mayor a la cantidad maxima a devolver ('+maxadevolver+')');
                        return false;
                    }

                    $.ajax({
                    url:"ajax.php?c=compras&f=a_modalRecepcionv",
                    type: 'POST',
                    data:{idProd:idProd,modo:modo,modalcantrecibida:modalcantrecibida,series:series,cadcar:cadcar},
                    success: function(r){
                        $('#modal-recepv').modal('hide');
                        $('#hiddensProdsv input[id="'+idProd+'"][ch="'+cadcar+'"]').remove();
                        $('#hiddensProdsv').append('<input ch="'+cadcar+'" id="'+idProd+'" type="hidden" value="'+r+'" >');

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
               
                    pedimentos=$('#spedimentos').val();
                    series=$('#selseries').val();
                    if(series==null){
                        alert('Tienes que seleccionar las series de tus productos a devolver');
                        return false;
                    }
                    console.log(series);
                    noseries= series.length;
                    
                    if(modalcantrecibida<noseries || modalcantrecibida>noseries){
                        alert('La cantidad de productos a devolver no concuerda con la cantidad de series');
                        return false;
                    }

                    if(modalcantrecibida>(maxadevolver*1)){
                        alert('La cantidad a devolver es mayor a la cantidad maxima a devolver ('+maxadevolver+')');
                        return false;
                    }

                   

              
                    $.ajax({
                    url:"ajax.php?c=compras&f=a_modalRecepcionv",
                    type: 'POST',
                    data:{idProd:idProd,modo:modo,modalcantrecibida:modalcantrecibida,pedimentos:pedimentos,series:series,cadcar:cadcar},
                    success: function(r){
                        $('#modal-recepv').modal('hide');
                        $('#hiddensProdsv input[id="'+idProd+'"][ch="'+cadcar+'"]').remove();
                        $('#hiddensProdsv').append('<input ch="'+cadcar+'" id="'+idProd+'" type="hidden" value="'+r+'" >');

                        if(typeof r != "undefined"){
                             rsplit = r.split('->-');
                        }


                        $("#cantrv_"+idProd+"_prod"+"[chp='"+cadcar+"']").val(rsplit[2]);
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

                if(modalcantrecibida>(maxadevolver*1)){
                        alert('La cantidad a devolver es mayor a la cantidad maxima a devolver ('+maxadevolver+')');
                        return false;
                    }

                    $.ajax({
                    url:"ajax.php?c=compras&f=a_modalRecepcionv",
                    type: 'POST',
                    data:{idProd:idProd,modo:modo,modalcantrecibida:modalcantrecibida,pedimentos:pedimentos,cantspedimentos:cantspedimentos,cadcar:cadcar},
                    success: function(r){
                        $('#modal-recep-unov').unbind();
                        $('#modal-recep-dosv').unbind();
                        $('#modal-recepv').modal('hide');
                        $('#hiddensProdsv input[id="'+idProd+'"][ch="'+cadcar+'"]').remove();
                        $('#hiddensProdsv').append('<input ch="'+cadcar+'" id="'+idProd+'" type="hidden" value="'+r+'" >');

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

                if(modalcantrecibida>(maxadevolver*1)){
                        alert('La cantidad a devolver es mayor a la cantidad maxima a devolver ('+maxadevolver+')');
                        return false;
                    }

                    $.ajax({
                    url:"ajax.php?c=compras&f=a_modalRecepcionv",
                    type: 'POST',
                    data:{idProd:idProd,modo:modo,modalcantrecibida:modalcantrecibida,pedimentoslote:pedimentoslote,cantspedimentos:cantspedimentos,cadcar:cadcar},
                    success: function(r){
                        $('#modal-recep-unov').unbind();
                        $('#modal-recep-dosv').unbind();
                        $('#modal-recepv').modal('hide');
                        $('#hiddensProdsv input[id="'+idProd+'"][ch="'+cadcar+'"]').remove();
                        $('#hiddensProdsv').append('<input ch="'+cadcar+'" id="'+idProd+'" type="hidden" value="'+r+'" >');

                        if(typeof r != "undefined"){
                             rsplit = r.split('->-');
                        }
                       // alert(idProd);


                        $("#cantrv_"+idProd+"_prod"+"[chp='"+cadcar+"']").val(rsplit[2]);
                        recalcula();
                    }
                });
            }

            $('#modal-recep-unov').unbind();
            $('#modal-recep-dosv').unbind();
        });

        $('#modal-recep-dosv').one('click',function(){
            $('#modal-recepv').modal('hide');
            $('#modal-recep-unov').unbind();
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

    function recibirProducto(idProd,modo,txtprod,txtcant,mod,cadcar,txtrecibido){

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
                        <label class="col-sm-6 control-label text-left">Características:</label>\
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
                        <label class="col-sm-6 control-label text-left">Cantidad recibida:</label>\
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

            $('#bodyespecial').html(divbody);

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
            cadena=$('#hiddensProds input[id="'+idProd+'"][ch="'+cadcar+'"]').val();
            if(typeof cadena != "undefined"){
                 cadsplit = cadena.split('->-');
                 $('#modalcantrecibida').val(cadsplit[2]);
                 $('#nopedimento').val(cadsplit[3]);
                 $('#aduanatext').val(cadsplit[4]);
                 $('#noaduana').val(cadsplit[5]);
                 $('#tipcambio').val(cadsplit[6]);
                 $('#datepedimento').val(cadsplit[7]);
                 $('#nolote').val(cadsplit[8]);
                 $('#datelotefab').val(cadsplit[9]);
                 $('#datelotecad').val(cadsplit[10]);
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
                        <label class="col-sm-6 control-label text-left">Características:</label>\
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
                        <label class="col-sm-6 control-label text-left">Cantidad recibida:</label>\
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

            $('#bodyespecial').html(divbody);

            $('#datepedimento').datepicker({
                    format: "yyyy-mm-dd",
                    language: "es"
            });

            $('#tipcambio').numeric();
            $('#modalcantrecibida').numeric();
            cadena=$('#hiddensProds input[id="'+idProd+'"][ch="'+cadcar+'"]').val();
            if(typeof cadena != "undefined"){
                 cadsplit = cadena.split('->-');
                 $('#modalcantrecibida').val(cadsplit[2]);
                 $('#nopedimento').val(cadsplit[3]);
                 $('#aduanatext').val(cadsplit[4]);
                 $('#noaduana').val(cadsplit[5]);
                 $('#tipcambio').val(cadsplit[6]);
                 $('#datepedimento').val(cadsplit[7]);
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
                        <label class="col-sm-6 control-label text-left">Características:</label>\
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
                        <label class="col-sm-6 control-label text-left">Cantidad recibida:</label>\
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

            $('#bodyespecial').html(divbody);
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
            cadena=$('#hiddensProds input[id="'+idProd+'"][ch="'+cadcar+'"]').val();
            //alert(cadena);
            if(typeof cadena != "undefined"){
                 cadsplit = cadena.split('->-');
                 $('#modalcantrecibida').val(cadsplit[2]);
                 $('#nolote').val(cadsplit[3]);
                 $('#datelotefab').val(cadsplit[4]);
                 $('#datelotecad').val(cadsplit[5]);
            }
}
if(modo==2){

divbody='<div class="row" style="overflow-x:hidden; overflow-y:scroll; height:480px;width:600px;">\
<input type="hidden" id="hmprod" value="'+idProd+'">\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Producto:</label>\
                        <div class="col-sm-6">\
                          '+txtprod+'\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Características:</label>\
                        <div class="col-sm-6">\
                          <span class="label label-primary" style="cursor:pointer;">Series</span>\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Cantidad solicitada:</label>\
                        <div class="col-sm-6">\
                          <input id="idUnicCant" class="numeric" type="text" value="'+txtcant+'" readonly="readonly">\
                        </div>\
                    </div>\
                    '+canterioridad+'\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Cantidad recibida:</label>\
                        <div class="col-sm-6">\
                          <input id="modalcantrecibida" class="numeric" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Series:</label>\
                        <div class="col-sm-6">\
                            <button onclick="addseries('+idProd+',\''+cadcar+'\');" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-plus"></span> Agregar series</button>\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <div class="col-sm-6"><textarea data-toggle="tooltip"  title="No debe exceder 1000 series!" rows="2" cols="30" id="allseries" placeholder="Copié y pegué las series separadas por coma (,)"></textarea></div>\
                        <div class="col-sm-6"><button onclick="transeries('+idProd+');" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-chevron-down"></span> Transferir series</button></div>\
                    </div>\
                    <div id="seriesprods__'+idProd+'">\
                    </div>\
                </div>';
            // ch@
            $('#bodyespecial').html(divbody);
            $('#tipcambio').numeric();
            $('#modalcantrecibida').numeric();
            cadena=$('#hiddensProds input[id="'+idProd+'"][ch="'+cadcar+'"]').val();
            //alert(cadena);
            if(typeof cadena != "undefined"){
                cadsplit = cadena.split('->-');
                $('#modalcantrecibida').val(cadsplit[2]);
                divseries='';
                seriesplit = cadsplit[4].split(',');
                for (var i=0; i<cadsplit[2]; i++) {
                    divseries+='<div class="sprod" class="col-sm-12" style="padding:15px;">\
                            <label class="col-sm-6 control-label text-left">No serie '+((i*1)+1)+':</label>\
                            <div class="col-sm-6">\
                                <input class="inpsprod" type="text" value="'+seriesplit[i]+'" >\
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
                        <label class="col-sm-6 control-label text-left">Características:</label>\
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
                        <label class="col-sm-6 control-label text-left">Cantidad recibida:</label>\
                        <div class="col-sm-6">\
                          <input id="modalcantrecibida" class="numeric" type="text" >\
                        </div>\
                    </div>\
                    <div class="col-sm-12" style="padding-top:10px;">\
                        <label class="col-sm-6 control-label text-left">Series:</label>\
                        <div class="col-sm-6">\
                            <button onclick="addseries('+idProd+',\''+cadcar+'\');" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-plus"></span> Agregar series</button>\
                        </div>\
                    </div>\
                    <div id="seriesprods__'+idProd+'">\
                    </div>\
                </div>';

            $('#bodyespecial').html(divbody);
            $('#tipcambio').numeric();
            $('#modalcantrecibida').numeric();
            $('#datepedimento').datepicker({
                    format: "yyyy-mm-dd",
                    language: "es"
            });

            cadena=$('#hiddensProds input[id="'+idProd+'"][ch="'+cadcar+'"]').val();
            //alert(cadena);
            if(typeof cadena != "undefined"){
                cadsplit = cadena.split('->-');
                $('#modalcantrecibida').val(cadsplit[2]);
                $('#modalcantrecibida').val(cadsplit[2]);
                $('#nopedimento').val(cadsplit[3]);
                $('#aduanatext').val(cadsplit[4]);
                $('#noaduana').val(cadsplit[5]);
                $('#tipcambio').val(cadsplit[6]);
                $('#datepedimento').val(cadsplit[7]);
                divseries='';
                seriesplit = cadsplit[9].split(',');
                for (var i=0; i<cadsplit[2]; i++) {
                    divseries+='<div class="sprod" class="col-sm-12" style="padding:15px;">\
                            <label class="col-sm-6 control-label text-left">No serie '+((i*1)+1)+':</label>\
                            <div class="col-sm-6">\
                                <input class="inpsprod" type="text" value="'+seriesplit[i]+'" >\
                            </div>\
                        </div>';
                };
            $('#seriesprods__'+idProd).html(divseries);
            }
}
       
        
        mmod = $('#modal-recep').modal({
                        backdrop: 'static',
                        keyboard: false, 
                        show: true
                });

        $('#modal-recep-uno').on('click',function(){
            modalcantrecibida=$('#modalcantrecibida').val();


            cantant=$("#tr_"+idProd+"[ch='"+cadcar+"']").find("#cantr_"+idProd+"_prod").val();
            cantseries = $('#modalcantrecibida').val();
            txtcant=$("#tr_"+idProd+"[ch='"+cadcar+"'] td:eq(5)").text();

            if(cantseries>0){
                if( ((cantseries*1)+(txtrecibido*1)) >(txtcant*1) ){
                    alert('La cantidad recibida actual no puede ser mayor al total de la orden de compra solicitada mas sus recibidos');
                    $('#modalcantrecibida').val( (txtcant- ((cantant*1)+(cantseries*1)) ) );
                    //$('#modalcantrecibida').css('border','1px solid #ff0000');
                    return false;
                }
            }else{
                alert('La cantidad recibida debe ser mayor a 0');
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
                var arrseries =[];
                
                nseries=0;
                detener=0;
                seriesprods = $('#seriesprods__'+idProd+' .inpsprod').map(function() {
                    seriescad = $(this).val();
                    if(seriescad==""){
                        detener++;
                    }
                    arrseries.push(seriescad);
                    nseries++;
                    return seriescad;
                }).get().join(',');

                serepiten=0;
                for(var n in arrseries) {
                    for(var o in arrseries) {
                        if(n!=o){
                            if(arrseries[o]==arrseries[n]){
                                serepiten++;
                                break;
                            }
                        }
                    }
                    if(serepiten>0){
                        break;
                    }
                }



                if(seriesprods==''){
                    alert('Tienes que agregar series a este producto');
                    return false;
                }
                if(detener>0){
                    alert('No puedes dejar series en blanco');
                    return false;
                }

                if(serepiten>0){
                    alert('Tienes una o mas series repetidas');
                    return false;
                }
                console.log(seriesprods);
                    $.ajax({
                    url:"ajax.php?c=compras&f=a_modalRecepcion",
                    type: 'POST',
                    data:{idProd:idProd,modo:modo,modalcantrecibida:modalcantrecibida,nseries:nseries,seriesprods:seriesprods,cadcar:cadcar},
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

    function nreq(){

        if($('#nreq').is(':visible')) {
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
                            alert('No se pueden cargar requisiciones');
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
                        alert('No se pueden cargar requisiciones');
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
        $('#date_hoy').prop('disabled', true);
        $('#checkbox').prop('disabled', true);
        $('#btn_addProd').prop('disabled', true);
        $('#opciones_2').prop('disabled', true);

        if(op!=1){
            $('#nofactrec').prop('disabled', true);
            $('#date_recepcion').prop('disabled', true);
            $('#impfactrec').prop('disabled', true);

        }

        
        //$('#btn_authquit').prop('disabled', true);


    }

    function popupCantv(idProd,lotes,series,pedimentos,cadcar){

        mod=1;
        //alert(idProd+' - '+lotes+' - '+series+' - '+pedimentos);

        txtprod=$("#tr_"+idProd+"[ch='"+cadcar+"'] td:eq(3)").text();
        txtcant=$("#tr_"+idProd+"[ch='"+cadcar+"'] td:eq(7)").text();

        if(lotes==1 && series==0 && pedimentos==0){
            recibirProductov(idProd,1,txtprod,txtcant,mod,cadcar); //solo lote
        }else if(series==1 && pedimentos==0 && lotes==0){
            recibirProductov(idProd,2,txtprod,txtcant,mod,cadcar); //solo series
        }else if(series==1 && pedimentos==1  && lotes==0){
            recibirProductov(idProd,3,txtprod,txtcant,mod,cadcar); //series y pedimentos
        }else if(pedimentos==1 && series==0  && lotes==0){
            recibirProductov(idProd,4,txtprod,txtcant,mod,cadcar); //solo pedimentos
        }else if(pedimentos==0 && series==0  && lotes==0){
            recibirProductov(idProd,0,txtprod,txtcant,mod,cadcar); //solo pedimentos
        }else if(pedimentos==1 && series==0  && lotes==1){
            recibirProductov(idProd,5,txtprod,txtcant,mod,cadcar); //solo pedimentos y lotes
        }else{
           // recibirProducto(idProd,0); //nada
        }
        //alert(idProd+' - '+lotes+' - '+series+' - '+pedimentos);

    }

    function popupCant(idProd,lotes,series,pedimentos,cadcar){
        mod=1;
        //alert(idProd+' - '+lotes+' - '+series+' - '+pedimentos);

        txtprod=$("#tr_"+idProd+"[ch='"+cadcar+"'] td:eq(3)").text();
        txtcant=$("#tr_"+idProd+"[ch='"+cadcar+"'] td:eq(5)").text();
        txtrecibido=$("#tr_"+idProd+"[ch='"+cadcar+"'] td:eq(7)").text();

        if(lotes==1 && series==0 && pedimentos==0){
            recibirProducto(idProd,1,txtprod,txtcant,mod,cadcar,txtrecibido); //solo lote
        }else if(series==1 && pedimentos==0 && lotes==0){
            recibirProducto(idProd,2,txtprod,txtcant,mod,cadcar,txtrecibido); //solo series
        }else if(series==1 && pedimentos==1  && lotes==0){
            recibirProducto(idProd,3,txtprod,txtcant,mod,cadcar,txtrecibido); //series y pedimentos
        }else if(pedimentos==1 && series==0  && lotes==0){
            recibirProducto(idProd,4,txtprod,txtcant,mod,cadcar,txtrecibido); //solo pedimentos
        }else if(pedimentos==1 && series==0  && lotes==1){
            recibirProducto(idProd,5,txtprod,txtcant,mod,cadcar,txtrecibido); //solo pedimentos
        }else{
           // recibirProducto(idProd,0); //nada
        }
        //alert(idProd+' - '+lotes+' - '+series+' - '+pedimentos);

    }

    function editRec(idRec,mod,idOC){
        $('#divxmls2').css('display','none');
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
                url:"ajax.php?c=compras&f=a_verRecepcion",
                type: 'POST',
                dataType:'JSON',
                data:{idRec:idRec,m:3,mod:mod},
                success: function(r){
                    if(r.success==1){
                        console.log(r);
                        resetearReq();
                        $('#ph').html('<span opt="2" class="label label-warning" style="cursor:pointer;">Recepcion Orden de compra</span>');
                        if(mod==4){                            
                            $('#ph').html('<span opt="3" class="label label-default" style="cursor:pointer;">Visualizar recepciones</span>');
                            disabledReq();
                            $('#btns .btn').prop('disabled', true);
                            //$('#btns .btn').css('display', 'none');
                            $('#btn_devolucion').css('display', 'block');
                            $('#btn_devolucion').prop('disabled', false);
                            $('#arecibir').text('Devueltos');
                            $('#coldevos').text('Devoluciones');

                            $('#comment').prop('disabled', false);


                            $('#btn_devolucion_ver').prop('disabled', false);
                            //$('#btn_devolucion_ver').css('display','block');
                            $('#btn_savequit').prop('disabled', true);
                            //$('#buttonFactura').css('display','none');
                            $('#factura').css('display','none');

                            $('#divxmls2').css('display','block');


                        }
                            $('#divxmls2').css('display','none');
                            $('#segurodev').css('display','block');
                            $('#reqnota').val(0);
                        if(r.requisicion.xmlfile!=''){
                            $('#segurodev').css('display','none');
                            $('#divxmls2').css('display','block');
                            $('#reqnota').val(1);
                            $('#divxmls_txt').text('XML adjuntado');
                            $('#resultasoc').html('<table w style="margin: 15px 0px 8px; width: 100%; border: 1px solid rgb(236, 236, 236); background-color: rgb(250, 250, 250);">\
    <tbody>\
    <tr style="height:25px;">\
      <th width="100">Folio factura</th>\
      <th width="170">Fecha timbrado</th>\
      <th width="150">$ Total factura</th>\
      <th width="100">Ver xml</th>\
    </tr>\
      <tr>\
      <td width="100">'+r.requisicion.no_factura+'</td>\
      <td width="170">'+r.requisicion.fecha_factura+'</td>\
      <td width="150">'+r.requisicion.imp_factura+'</td>\
      <td width="100"><a id="xmlfile" name="'+r.requisicion.xmlfile+'" class="btn btn-success btn-xs" onclick="openxml(\''+r.requisicion.xmlfile+'\')">Ver xml</a>\
      </td></tr>\
  </tbody></table>');
                        }

                        $('#c_proveedores').prop('disabled',true);
                        $('#c_almacen').prop('disabled',true);
                        $('#txt_nreq').text(idOC);//OC
                        $('#txt_nreq2').text(idRec);//Recepcion
                        $('#nreq_load').css('display','none');

                        $("#c_solicitante").val(r.requisicion.id_solicito).trigger("change");

                        $("#c_solicitante").val(r.requisicion.id_solicito).trigger("change");
                        $("#fecha_hoy").val(r.requisicion.fecha);
                        $("#date_entrega").val(r.requisicion.fecha_entrega);
                        $("#date_hoy").val(r.requisicion.fecha);
                        $("#date_recep").val(r.requisicion.fecha_recepcion);

                        $('#date_recep').prop('disabled',true);
                        
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

                        $("#comment").val(r.requisicion.observaciones);

                        txt_proveedor=$('#c_proveedores option:selected').text();
                        txt_almacen=$('#c_almacen option:selected').text();


                        $("#nofactrec").val(r.requisicion.no_factura);
                        $("#date_recepcion").val(r.requisicion.fecha_factura);
                        $("#impfactrec").val(r.requisicion.imp_factura);

                        //table = $('#tablaprods').DataTable();

                        data_almacen=$('#data_almacen').html();
                        disablecant=0;

                        if(r.requisicion.es_consignacion==1){
                            data_almacen="<select id='c_almacen' style='width:100px;'><option selected value='"+r.requisicion.almafinal+"'>"+r.requisicion.nomalmacen+"</option></select>";
                            $('#btn_authquit_p').css('visibility','hidden');
                            $('#ph').append('<input type="hidden" id="esconsig" value="'+r.requisicion.almafinal+'">');
                            $('#divxmls').css('display','none');
                        }else{
                            
                            $('#btn_authquit_p').css('visibility','visible');
                            $('#ph').append('<input type="hidden" id="esconsig" value="0">');
                            $('#divxmls').css('display','block');
                        }


                        $.each(r.productos, function( k, v ) {
                            if(mod==0){
                                eliminProd="&nbsp;";
                                txtdis='disabled';
                            }else if(mod==4){

                                if(v.lotes==1 && v.series==0 && v.pedimentos==0){
                                    disabledcant='readonly="readonly"';
                                    onkeycant="especial='1' onclick='popupCantv("+v.id+","+v.lotes+",0,0,\""+v.caracteristica+"\")'";
                                }else if(v.series==1 && v.pedimentos==0 && v.lotes==0){
                                    disabledcant='readonly="readonly"';
                                    onkeycant="especial='2' onclick='popupCantv("+v.id+",0,"+v.series+",0,\""+v.caracteristica+"\")'";
                                }else if(v.series==1 && v.pedimentos==1  && v.lotes==0){
                                    disabledcant='readonly="readonly"';
                                    onkeycant="especial='3' onclick='popupCantv("+v.id+",0,"+v.series+","+v.pedimentos+",\""+v.caracteristica+"\")'";
                                }else if(v.pedimentos==1 && v.series==0  && v.lotes==0){
                                    disabledcant='readonly="readonly"';
                                    onkeycant="especial='4' onclick='popupCantv("+v.id+",0,0,"+v.pedimentos+",\""+v.caracteristica+"\")'";
                                }else if(v.pedimentos==0 && v.series==0  && v.lotes==0){
                                        disabledcant='readonly="readonly"';
                                        onkeycant="especial='0' aaaa='2' onclick='popupCantv("+v.id+",0,0,0,\""+v.caracteristica+"\")'";
                                }else if(v.pedimentos==1 && v.series==0  && v.lotes==1){
                                    disabledcant='readonly="readonly"';
                                    onkeycant="especial='5' onclick='popupCantv("+v.id+","+v.lotes+",0,"+v.pedimentos+",\""+v.caracteristica+"\")'";
                                }else{
                                    disabledcant=" onkeyup=verifyCants("+v.id+"); ";
                                    onkeycant="especial='0' ";
                                }


                                if( (v.recibidorec-v.cantdev)<=0 ){
                                    didids=' disabled ';
                                }else{
                                    didids='';
                                }

                                txtdis='';
                                eliminProd="<input chp='"+v.caracteristica+"' id='cantrv_"+v.id+"_prod'  "+onkeycant+" class='numeros' style='border:1px solid #ef7070;width:100px;padding:2px;' type='text' placeholder='Cant. a devolver' "+didids+"/>";
                            }else{
                                txtdis='';
                                eliminProd="<button onclick='removeProdReq("+v.id+");' id='btn_addProd' class='btn btn-sm btn-danger' type='button' style='height:26px;'>Quitar</button>";
                            }

                            /*
                            if(mod!=4){
                                if(v.lotes==1 && v.series==0 && v.pedimentos==0){
                                    disabledcant='readonly="readonly"';
                                    onkeycant="especial='1' onclick=popupCant("+v.id+","+v.lotes+",0,0,"+mod+")";
                                }else if(v.series==1 && v.pedimentos==0 && v.lotes==0){
                                    disabledcant='readonly="readonly"';
                                    onkeycant="especial='2' onclick=popupCant("+v.id+",0,"+v.series+",0,"+mod+")";
                                }else if(v.series==1 && v.pedimentos==1  && v.lotes==0){
                                    disabledcant='readonly="readonly"';
                                    onkeycant="especial='3' onclick=popupCant("+v.id+",0,"+v.series+","+v.pedimentos+","+mod+")";
                                }else if(v.pedimentos==1 && v.series==0  && v.lotes==0){
                                    disabledcant='readonly="readonly"';
                                    onkeycant="especial='4' onclick=popupCant("+v.id+",0,0,"+v.pedimentos+","+mod+")";
                                }else{
                                    disabledcant='';
                                    onkeycant="especial='0' ";
                                }
                            }else{
                                disabledcant='disabled="disabled"';
                                onkeycant='readonly="readonly"';
                                //onkeycant="";
                            }
*/

                            Rowdata="<tr  tp='"+v.tipo_producto+"' ch='"+v.caracteristica+"' id='tr_"+v.id+"'>\
                            <td>0</td>\
                            <td>"+data_almacen+"</td>\
                            <td>"+v.codigo+"</td>\
                            <td>"+v.nombre+"</td>\
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
                            vv='<?php echo $vv; ?>';
                            if(vv==1){
                                $('.numeros').prop('disabled',true);
                                $('#imps').css('display','none');
                            }

                            $("#tr_"+v.id+"[ch='"+v.caracteristica+"']").find('#c_almacen').val(v.id_almacen).trigger("change");

                            $("#tr_"+v.id+"[ch='"+v.caracteristica+"']").find('#c_almacen').find("option:not(:selected)").remove();
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
                        alert('No se pueden cargar requisiciones');
                    }
                }
            });

        
    }

    function editCon(idRec,mod,idOC){
            disprod=1;
            //table = $('#tablaprods').DataTable();
            
            table = $('#tablacons').DataTable();
            table.clear().draw();
            //table.row('#tr_'+idProducto).remove().draw();

            $('#listareq').css('display','none');
            $('#listarec').css('display','none');
            $('#modal-conf1').modal('hide');
            $('#nreq').css('display','none');
            $('#nreq_load').css('display','block');
            $.ajax({
                url:"ajax.php?c=compras&f=a_verConsignacion",
                type: 'POST',
                dataType:'JSON',
                data:{idRec:idRec,m:3,mod:mod},
                success: function(r){
                    if(r.success==1){
                        console.log(r);

                        resetearReq();
                        $('#ph').html('<span opt="2" class="label label-warning" style="cursor:pointer;">Recepcion Orden de compra</span>');
                        if(mod==4){                            
                            $('#ph').html('<span opt="3" class="label label-default" style="cursor:pointer;">Consignacion</span>');
                            disabledReq();
                            $('#btns .btn').css('display', 'none');
                            $('#btn_savequit').prop('disabled', false);
                            $('#btn_comprar_ok').css('display','block');
                            //$('#factura').css('display','none');

                        }



                        $('#c_proveedores').prop('disabled',true);
                        $('#c_almacen').prop('disabled',true);
                        //$('#nodeque').text('No. Recepcion');
                        $('#txt_nreq').text(idOC);
                        $('#nreq_load').css('display','none');

                        $("#c_solicitante").val(r.requisicion.id_solicito).trigger("change");

                        $("#c_solicitante").val(r.requisicion.id_solicito).trigger("change");
                        $("#fecha_hoy").val(r.requisicion.fecha);
                        $("#date_entrega").val(r.requisicion.fecha_entrega);
                        $("#date_hoy").val(r.requisicion.fecha);
                        $("#date_recep").val(r.requisicion.fecha_recepcion);

                        $('#date_recep').prop('disabled',true);
                        
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

                        $("#comment").val(r.requisicion.observaciones);

                        txt_proveedor=$('#c_proveedores option:selected').text();
                        txt_almacen=$('#c_almacen option:selected').text();


                        $("#nofactrec").val(r.requisicion.no_factura);
                        $("#date_recepcion").val(r.requisicion.fecha_factura);
                        $("#impfactrec").val(r.requisicion.imp_factura);

                        if(r.requisicion.es_consignacion==1){
                            $('#ph').append('<input type="hidden" id="esconsig" value="'+r.requisicion.almafinal+'">');
                        }else{
        
                            $('#ph').append('<input type="hidden" id="esconsig" value="0">');
                      
                        }

                        //table = $('#tablaprods').DataTable();

                        data_almacen=$('#data_almacen').html();
                        disablecant=0;
                        $.each(r.productos, function( k, v ) {
                            if(mod==0){
                                eliminProd="&nbsp;";
                                txtdis='disabled';
                            }else if(mod==4){
                                txtdis='';
                                eliminProd="<!--<button onclick='' id='btn_addProd' class='btn btn-sm btn-danger' type='button' style='height:26px;'>Devoluciones</button>-->";
                            }else{
                                txtdis='';
                                eliminProd="<button onclick='removeProdReq("+v.id+");' id='btn_addProd' class='btn btn-sm btn-danger' type='button' style='height:26px;'>Quitar</button>";
                            }

                            
                            

                            if(v.lotes==1 && v.series==0 && v.pedimentos==0){
                                disabledcant='readonly="readonly"';
                                onkeycant="especial='1' onclick='popupCantv("+v.id+","+v.lotes+",0,0,\""+v.caracteristica+"\")'";
                            }else if(v.series==1 && v.pedimentos==0 && v.lotes==0){
                                disabledcant='readonly="readonly"';
                                onkeycant="especial='2' onclick='popupCantv("+v.id+",0,"+v.series+",0,\""+v.caracteristica+"\")'";
                            }else if(v.series==1 && v.pedimentos==1  && v.lotes==0){
                                disabledcant='readonly="readonly"';
                                onkeycant="especial='3' onclick='popupCantv("+v.id+",0,"+v.series+","+v.pedimentos+",\""+v.caracteristica+"\")'";
                            }else if(v.pedimentos==1 && v.series==0  && v.lotes==0){
                                disabledcant='readonly="readonly"';
                                onkeycant="especial='4' onclick='popupCantv("+v.id+",0,0,"+v.pedimentos+",\""+v.caracteristica+"\")'";
                            }else if(v.pedimentos==0 && v.series==0  && v.lotes==0){
                                    disabledcant='readonly="readonly"';
                                    onkeycant="especial='0' aaaa='2' onclick='popupCantv("+v.id+",0,0,0,\""+v.caracteristica+"\")'";
                            }else if(v.pedimentos==1 && v.series==0  && v.lotes==1){
                                disabledcant='readonly="readonly"';
                                onkeycant="especial='5' onclick='popupCantv("+v.id+","+v.lotes+",0,"+v.pedimentos+",\""+v.caracteristica+"\")'";
                            }else{
                                disabledcant=" onkeyup=verifyCants("+v.id+"); ";
                                onkeycant="especial='0' ";
                            }
                           
/*
                             <th width="10%" align="left">Codigo</th>
                           <th width="35%" align="left">Descripcion</th>
                           <th width="8%" align="left">$Unitario</th>
                           <th width="8%" align="left">Importe OC</th>
                           <th width="8%" align="left">Cant. Recibidos</th>
                           <th width="8%" align="left">Cant. Vendidos</th>
                           <th width="8%" align="left">Mover a</th>
                           <th class="no-sort" width="1%" align="left">&nbsp;</th>
*/

                            Rowdata="<tr ch='"+v.caracteristica+"' id='tr_"+v.id+"'  "+onkeycant+">\
                            <td>"+v.id_recepcion+"</td>\
                            <td>"+v.codigo+"</td>\
                            <td>"+v.nombre+"</td>\
                            <td id='valUnit'>"+v.costo+"</td>\
                            <td>"+(v.costo*v.cantidad)+"</td>\
                            <td>"+v.recibidorec+"</td>\
                            <td>\
                                <input id='cantr_"+v.id+"_prod' style='width:60%;' "+onkeycant+" class='numeros' type='text' value='0' "+disabledcant+" />\
                            </td>\
                            <td>"+data_almacen+"</td>\
                            </tr>";
                            table.row.add($(Rowdata)).draw();
                            

                            //$("#tr_"+v.id+"[ch='"+v.caracteristica+"']").find('#c_almacen').val(v.id_almacen).trigger("change");
                            //$("#tr_"+v.id+"[ch='"+v.caracteristica+"'] select").prop('disabled',true);
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
                        $('#txt_nreq').append('<input id="idrequi" type="hidden" value="'+idRec+'">');

                        $('.numeros').numeric();
                        $('#panel_tabla_cons').css('display','block');
                        $('#nreq').css('display','block');

                    }else{
                        alert('No se pueden cargar requisiciones');
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
                url:"ajax.php?c=compras&f=a_editarrequisicion",
                type: 'POST',
                dataType:'JSON',
                data:{idReq:idReq,m:2,mod:mod},
                success: function(r){
                    if(r.success==1){
                        console.log(r);
                        resetearReq();
                        $('#ph').html('<span opt="2" class="label label-warning" style="cursor:pointer;">Recepcion Orden de compra</span>');
                        if(mod==0){                            
                            $('#ph').html('<span opt="3" class="label label-default" style="cursor:pointer;">Recepcion de orden de compra</span>');
                            disabledReq(1);
                        }

                        $('#divxmls_txt').text('Subir factura(s) xml');

                        $('#c_proveedores').prop('disabled',true);
                        $('#c_almacen').prop('disabled',true);
                        $('#txt_nreq').text(idOC);
                        $('#nreq_load').css('display','none');

                        

                        $("#c_solicitante").val(r.requisicion.id_solicito).trigger("change");
                        $("#fecha_hoy").text(r.requisicion.fecha);
                        $("#date_entrega").val(r.requisicion.fecha_entrega);
                        $("#date_hoy").val(r.requisicion.fecha);

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

                        $("#comment").val(r.requisicion.observaciones);

                        txt_proveedor=$('#c_proveedores option:selected').text();
                        txt_almacen=$('#c_almacen option:selected').text();





                       // $("#nofactrec").val(r.requisicion.no_factura);
                       // $("#date_recepcion").val(r.requisicion.fecha_factura);
                       // $("#impfactrec").val(r.requisicion.imp_factura);

                        //table = $('#tablaprods').DataTable();
                        data_almacen=$('#data_almacen').html();
                        disablecant=0;

                        if(r.requisicion.es_consignacion==1){
                            data_almacen="<select id='c_almacen' style='width:100px;'><option selected value='"+r.requisicion.almafinal+"'>"+r.requisicion.nomalmacen+"</option></select>";
                            $('#btn_authquit_p').css('visibility','hidden');
                            $('#ph').append('<input type="hidden" id="esconsig" value="'+r.requisicion.almafinal+'">');
                            $('#divxmls').css('display','none');
                        }else{
                            
                            $('#btn_authquit_p').css('visibility','visible');
                            $('#ph').append('<input type="hidden" id="esconsig" value="0">');
                            $('#divxmls').css('display','block');
                        }

                        rcants=0;
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
                            }else if(v.pedimentos==1 && v.series==0  && v.lotes==1){
                                disabledcant='readonly="readonly"';
                                onkeycant="especial='5' onclick='popupCant("+v.id+","+v.lotes+",0,"+v.pedimentos+",\""+v.caracteristica+"\")'";
                            }else{
                                disabledcant=" onkeyup='verifyCants("+v.id+",\""+v.caracteristica+"\")'";
                                onkeycant="especial='0' ";
                            }

                            if(v.cantidad-v.cantidadr==0){
                                disabledcant='disabled="disabled"';
                            }

                            if(v.caracteristica!='0'){
                                disa=' disabled="disabled" ';
                            }else{
                                disa='';
                            }

                            Rowdata="<tr ch='"+v.caracteristica+"' id='tr_"+v.id+"' tp='"+v.tipo_producto+"'>\
                            <td>0</td>\
                            <td>"+data_almacen+"</td>\
                            <td>"+v.codigo+"</td>\
                            <td>"+v.nombre+"</td>\
                            <td id='valUnit'>"+v.costo+"</td>\
                            <td id='cantidUnic'>"+v.cantidad+"</td>\
                            <td>"+(v.costo*v.cantidad)+"</td>\
                            <td id='crecibida'>"+v.cantidadr+"</td>\
                            <td>\
                                <input chp='"+v.caracteristica+"'  id='cantr_"+v.id+"_prod' style='width:60%;' "+onkeycant+" class='numeros' type='text' value='0' "+disabledcant+" />\
                            </td>\
                            <!--<td class='valImporte' implimpio='"+v.costo+"' id='valImporte'>"+v.costo+"</td>-->\
                            <td>&nbsp;</td>\
                            <td>&nbsp;</td>\
                            <td>"+eliminProd+"</td>\
                            </tr>";
                            table.row.add($(Rowdata)).draw();
                            //refreshCants(v.id,v.caracteristica);

                            if(r.requisicion.es_consignacion==1){
                                $("#tr_"+v.id+"[ch='"+v.caracteristica+"']").find('#c_almacen').val(r.requisicion.almafinal).prop('disabled',true);

                            }

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
                        alert('No se pueden cargar requisiciones');
                    }
                }
            });

        
    }

    function verifyCants(idProd,cadcar){

        cantoc=$("#tr_"+idProd+"[ch='"+cadcar+"'] td:nth-child(6)").text()*1;
        cantrecibida=$("#tr_"+idProd+"[ch='"+cadcar+"'] td:nth-child(8)").text()*1;

        actual=$("#cantr_"+idProd+"_prod"+"[chp='"+cadcar+"']").val();

        if( (actual*1)>(cantoc-cantrecibida)){
            alert('La cantidad de recibidos es mayor a la cantidad solicitada');
            $('#cantr_'+idProd+'_prod'+"[chp='"+cadcar+"']").val( (cantoc-cantrecibida) );
            
        }

        refreshCants(idProd,cadcar);


        //

        //table.cell('#tr_'+idProducto+' td:nth-child(10)').data(valcurren).draw();

    }

    function listarec(idoc){

        span = $('#ph').find('span').text();
        if($('#nreq').is(':visible') && span!='Visualizar recepciones') {
            $('#modal-conf2').modal('show');
            $('#modal-btnconf2-uno').on('click',function(){
                $('#modal-conf2').modal('hide');
                $('#nreq').css('display','none');
                $('#listareq_load').css('display','block');
                var table = $('#examplec').DataTable();
                table.destroy();                
                $('#examplec').DataTable( {
            dom: 'Bfrtip',
            buttons: [ 'pageLength', 'excel' ],
            language: {
                buttons: {
                    pageLength: "Mostrar %d filas"
                },
                search: "Buscar:",
                lengthMenu:"Mostrar _MENU_ elementos",
                zeroRecords:"No hay datos",
                infoEmpty:"",
                info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                paginate: {
                    first:      "Primero",
                    previous:   "Anterior",
                    next:       "Siguiente",
                    last:       "Último"
                },
             },

                    "aaSorting": [[1,'desc']],
                    ajax: {
                        beforeSend: function() {  }, //Show spinner
                        complete: function() { $('#listareq_load').css('display','none'); }, //Hide spinner
                        url:"ajax.php?c=compras&f=a_listaRecepciones&idoc="+idoc,
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
            dom: 'Bfrtip',
            buttons: [ 'pageLength', 'excel' ],
            language: {
                buttons: {
                    pageLength: "Mostrar %d filas"
                },
                search: "Buscar:",
                lengthMenu:"Mostrar _MENU_ elementos",
                zeroRecords:"No hay datos",
                infoEmpty:"",
                info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                paginate: {
                    first:      "Primero",
                    previous:   "Anterior",
                    next:       "Siguiente",
                    last:       "Último"
                },
             },

                "aaSorting": [[1,'desc']],
                ajax: {
                    beforeSend: function() {  }, //Show spinner
                    complete: function() { $('#listareq_load').css('display','none'); }, //Hide spinner
                    url:"ajax.php?c=compras&f=a_listaRecepciones&idoc="+idoc,
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
/*
    adju_recep" class="col-sm-12" style="padding:10px 0 10px 0;">
                    <table>
                        <tr>
                        <th width="45%">No. Recepcion</th>
                        <th width="40%">Fecha</th>
                        <th width="40%">Monto</th>
                        </tr>
                        <tr>
                        <td>1</td>
                        <td>2012-09-09</td>
                        <td>$500</td>
                        </tr>
                    </table>*/

    function adjuntarxml(idoc){
        deten=0;
        $.ajax({
            async:false,
            url:"ajax.php?c=compras&f=a_verificarPagos",
            type: 'POST',
            dataType: 'json',
            data:{idoc:idoc},
            success: function(r){
                if(r>0){
                    alert('Esta orden de compra ya tiene pagos realizados, no puedes subir facturas.');
                    deten=1;
                }
            }
        });
        if(deten==1){
            return false;
        }
        $('#modal-adju').modal({
            backdrop: 'static',
            keyboard: false, 
            show: true
        });
        $('#idocadju').remove();
        $('body').append('<input id="idocadju" type="hidden" value="'+idoc+'">');
        $.ajax({
            url:"ajax.php?c=compras&f=a_adjuntarxml",
            type: 'POST',
            dataType: 'json',
            data:{idoc:idoc},
            success: function(r){
                console.log(r);
                tabla='<table>\
                        <tr>\
                        <th width="420">No Recepcion</th>\
                        <th width="160">Fecha recepcion</th>\
                        <th width="100">Monto</th>\
                        </tr>';
                trecep='';
                $.each(r.rows, function(i,v) {
                    tabla+='<tr>\
                        <td style="padding: 2px;">ID Recepcion - '+v.idr+'</td>\
                        <td style="padding: 2px;">'+v.fechar+'</td>\
                        <td style="padding: 2px;">$'+v.total+'</td>\
                        </tr>';
                });
                tabla+='<tr>\
                        <td style="padding: 2px;">&nbsp;</td>\
                        <td style="padding: 2px;">&nbsp;</td>\
                        <td style="padding: 2px;"><b>$'+r.total+'</b></td>\
                        </tr>';
                tabla+='</table>';

                tabla2='<table id="tablaxmladju">\
                        <tr>\
                        <th width="420">Xml archivo</th>\
                        <th width="160">Fecha subida</th>\
                        <th width="100">Monto</th>\
                        </tr>';


                $.each(r.xmls, function(i,v) {
                    tabla2+='<tr>\
                        <td style="padding: 2px;">'+v.xmlfile+'</td>\
                        <td style="padding: 2px;">'+v.fecha_subida+'</td>\
                        <td style="padding: 2px;">$'+v.imp_factura+'</td>\
                        </tr>';
                });
                tabla2+='<tr>\
                        <td style="padding: 2px;">&nbsp;</td>\
                        <td style="padding: 2px;">&nbsp;</td>\
                        <td style="padding: 2px;"><b>$'+r.totalxmls+'</b></td>\
                        </tr>';

                $('#adju_header').html('Orden de compra <b>'+idoc+'</b>');
                $('#adju_recep').html(tabla);
                if(r.xmls==0){
                    $('#adju_xmls').html('No hay facturas adjuntas');
                }else{
                    $('#adju_xmls').html(tabla2);
                }
                
            }
        });
    }

    function listareq(){
        span = $('#ph').find('span').text();
        if($('#nreq').is(':visible') && span!='Visualizar recepciones') {
            $('#modal-conf2').modal('show');
            $('#modal-btnconf2-uno').on('click',function(){
                $('#modal-conf2').modal('hide');
                $('#nreq').css('display','none');
                $('#listareq_load').css('display','block');
                var table = $('#example').DataTable();
                table.destroy();                
                $('#example').DataTable( {
            dom: 'Bfrtip',
            buttons: [ 'pageLength', 'excel'],
            language: {
                buttons: {
                    pageLength: "Mostrar %d filas"
                },
                search: "Buscar:",
                lengthMenu:"Mostrar _MENU_ elementos",
                zeroRecords:"No hay datos",
                infoEmpty:"",
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
                        url:"ajax.php?c=compras&f=a_listaOrdenesRecepcion",
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
            dom: 'Bfrtip',
            buttons: [ 'pageLength', 'excel'],
            language: {
                buttons:{
                            pageLength: "Mostrar %d filas"
                        },
                search: "Buscar:",
                lengthMenu:"Mostrar _MENU_ elementos",
                zeroRecords:"No hay datos",
                infoEmpty:"",
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
                    url:"ajax.php?c=compras&f=a_listaOrdenesRecepcion",
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

    function refreshCants(idProducto,cadcar){
        valActual = $("#tr_"+idProducto+"[ch='"+cadcar+"'] input").val()*1;
        valUnit = $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valUnit").text()*1;
        valImporte = valActual*valUnit;
        $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valImporte").attr('implimpio',valImporte);
        $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valImporte").text(valImporte).currency();
        valcurren = $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valImporte").text();
        table = $('#tablaprods').DataTable();
        //table.cell('#tr_'+idProducto+' td:nth-child(10)').data(valcurren).draw();

        $("#tr_"+idProducto+"[ch='"+cadcar+"'] input").focus();

        recalcula();

        /*
        //iniciocaclulaivas
var subtotal = 0;
var total = 0;
var productos = '';
    $("#filasprods tr").each(function (index) 
    {   //console.log($("#tablita input:hidden"));
        
        //contador++;
        idp = $(this).attr('id');
        spliidp = idp.split('_');
        idProducto = spliidp[1];
        cantidad = $(this).find('.numeros').val();
        precio = $(this).find('#valUnit').text();
        if(cantidad > 0){
           
            subtotal = parseFloat(precio) * parseFloat(cantidad);
            productos +=idProducto+'-'+cantidad+'-'+precio+'/';


        }
// alert(productos);
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
        $.each(data.cargos.impuestosPorcentajes, function(index, val) {
            $('#imps').append('<div class="row">'+
                        '<div class="col-sm-6"><label>'+index+':</label></div>'+
                        '<div class="col-sm-6"><label>$'+val+'</label></div>'+
                        '</div>');   
        });
        if(data.cargos.subtotal==null){
            data.cargos.subtotal=0;
        }
        if(data.cargos.total==null){
            data.cargos.total=0;
        }
        $('#imps').append('<div class="row">'+
                        '<div class="col-sm-6"><label>Subtotal:</label></div>'+
                        '<div class="col-sm-6"><label>$'+data.cargos.subtotal+'</label></div>'+
                        '</div>');
        $('#imps').append('<div class="row">'+
                        '<div class="col-sm-6"><label>Total:</label></div>'+
                        '<div class="col-sm-6"><label>$'+data.cargos.total+'</label></div>'+
                        '</div>');

        //$('#inputSubTotal').val(data.cargos.subtotal);
        //$('#inputTotal').val(data.cargos.total);

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
    */
    //fin calcilaivas

        //tdTotals();
    }   

    function recalcula(){

        var subtotal = 0;
var total = 0;
var productos = '';
    $("#filasprods tr").each(function (index) 
    {   //console.log($("#tablita input:hidden"));
        
        //contador++;
        idp = $(this).attr('id');
        spliidp = idp.split('_');
        idProducto = spliidp[1];
        cantidad = $(this).find('.numeros').val();
        precio = $(this).find('#valUnit').text();
        if(cantidad > 0){
           
            subtotal = parseFloat(precio) * parseFloat(cantidad);
            productos +=idProducto+'-'+cantidad+'-'+precio+'/';


        }

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
        if(data.cargos.subtotal==null){
            data.cargos.subtotal=0;
        }

        fsbt= data.cargos.subtotal.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');

        $('#imps').append('<div class="row">'+
                        '<div class="col-sm-6"><label>Subtotal:</label></div>'+
                        '<div class="col-sm-6"><label>$'+fsbt+'</label></div>'+
                        '</div>');
        
        $.each(data.cargos.impuestosPorcentajes, function(index, val) {
            fval= val.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
            $('#imps').append('<div class="row">'+
                        '<div class="col-sm-6"><label>'+index+':</label></div>'+
                        '<div class="col-sm-6"><label>$'+fval+'</label></div>'+
                        '</div>');   
        });
        
        if(data.cargos.total==null){
            data.cargos.total=0;
        }
        
        ftt= data.cargos.total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
        $('#imps').append('<div class="row">'+
                        '<div class="col-sm-6"><label>Total:</label></div>'+
                        '<div class="col-sm-6"><label>$'+ftt+'</label></div>'+
                        '</div>');

        //$('#inputSubTotal').val(data.cargos.subtotal);
        //$('#inputTotal').val(data.cargos.total);

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