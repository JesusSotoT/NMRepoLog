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

<!-- CHRIS - COMMENTS
=============================*/
//Modales precargados
//Modal 1 y modal 2 
-->

<div class="modal fade" id="modalDescParcial" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Descuento Parcial</h4>
        </div>
        <div class="modal-body">
            <div class="row">
            <input type="hidden" id="xProParc">
            <input type="hidden" id="idprodDescP">
            <input type="hidden" id="idcarDescp">
            <input type="hidden" id="idprecio">
                <div class="col-sm-8">
                    <h3 id="encabezadoNombre"></h3>
                </div>
                <div class="col-sm-4">
                    <h3 id="encabezadoPrecio"></h3>
                    <input type="hidden" id="encabezadoPrecioInput">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-6">
                    <div class="row">   
                        <div class="col-sm-4">
                            <label>Descuento:</label>
                        </div>
                        <div class="col-sm-4">
                            <select id="tipoDescu" class="form-control">
                                <option value="%">%</option>
                                <option value="$">$</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="desCantidad">
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <button class="btn btn-primary btn-block" onclick="aplicaDesParcial();">Aplicar</button>
                </div>
                <div class="col-sm-3">
                    <button class="btn btn-danger btn-block" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <label>Clave</label>
                </div>
                <div class="col-sm-4">
                    <input type="password" class="form-control" id="inpass" value="">
                </div>
            </div>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="modalDescGlobal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Descuento Global</h4>
        </div>
        <div class="modal-body">
            <div class="row">
            <input type="hidden" id="xProParc">
            <input type="hidden" id="total">
            <input type="hidden" id="subtotal">
                <div class="col-sm-6">
                    <h3 id="h3DescG1"></h3>
                </div>
                <div class="col-sm-6">
                    <h3 id="h3DescG2"></h3>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">   
                        <div class="col-sm-2">
                            <label>Descuento:</label>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="desCantidadG" placeholder="%">
                        </div>
                        <div class="col-sm-2">
                            <label>Clave</label>
                        </div>
                        <div class="col-sm-3">
                            <input type="password" class="form-control" id="inpassG" value="">
                        </div>
                    </div>
                </div>                
            </div>

            <div class="row">
                <div class="col-sm-3">
                    <button class="btn btn-primary btn-block" onclick="aplicaDesGlobal();">Aplicar</button>
                </div>
                <div class="col-sm-3">
                    <button class="btn btn-danger btn-block" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>

<div id="modal-ce" class="modal fade">
    <div class="modal-dialog" style="width:60% !important">
        <div class="modal-content panel-default">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Orden de compra rapida</h4>
                <input id="ist_exp" value="0" type="hidden"></input>
                <input id="it_exp" value="0" type="hidden"></input>
                <input id="cadimps_exp" value="0" type="hidden"></input>
                <input id="nextov" value="0" type="hidden"></input>
            </div>
            <div class="modal-body">
            <div id="formexp" class="row" style="margin:0px 0 10px 0px">
          
                    <label class="col-sm-2 control-label text-left"  style="padding:0px">Usuario:</label>
                    <div class="col-sm-10" style="color:#096;">
                        <label id="userlog">-</label>
                        <input id="iduserlog" value="3" type="hidden"></input>
                    </div>
                    <div class="row"></div>
                    <label class="col-sm-2 control-label text-left"  style="padding:0px">Almacen:</label>
                    <div class="col-sm-10" style="color:#000;">
                        <label id="alma_exp">Almacen general</label>
                    </div>
                    <div class="row"></div>
                    
                    <label class="col-sm-2 control-label text-left"  style="padding:0px">Solicitante:</label>
                    <div class="col-sm-10" style="color:#000;">
                        <select id="solicitante_exp" onchange="eee();">
                    
                        </select>
                    </div>
                    <div class="row"></div>
                    
                    <label class="col-sm-2 control-label text-left"  style="padding:0px">Tipo de gasto:</label>
                    <div class="col-sm-10" style="color:#000;">
                        <select id="tgasto_exp" onchange="eee();">
                    
                        </select>
                    </div>
                    
                    

            </div>
            <div class="row" style="margin:10px 0 10px 0px">
                <b>Proveedor &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
                <input type="hidden" id="idspoc" value="">
                <select id="selpoc" onchange="loki();">
                    
                </select>
            </div>

                <table>
                    <thead>
                    <tr>
                        <th width="15%">Codigo</th>
                        <th width="35%">Descripcion</th>
                        <th width="15%">Unidad</th>
                        <th width="10%">Unitario</th>
                        <th width="12%">Cantidad</th>
                        <th width="5%">Subtotal</th>
                    </tr>
                    </thead>
                    <tbody id="shala">
                    </tbody>
                </table>
            </div>
            <div class="row" style="margin:15px">
                <div id="imps_exp">

                </div>
            </div>
            <div class="modal-footer">
                <button id="modal-ce-uno" type="button" class="btn btn-default">Crear orden de compra y orden de venta</button> 
                <button id="modal-ce-dos" type="button" class="btn btn-default">Crear solo orden de compra</button> 
                <button id="modal-ce-tres" type="button" class="btn btn-default">Cancelar</button>
            </div>
        </div>
    </div> 
</div> 

<div id="modal-car" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-default">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Producto con caracteristicas</h4>
            </div>
            <div id="divcar" class="modal-body">

            </div>
            <div class="modal-footer">
                <button id="modal-car-uno" type="button" class="btn btn-default">Continuar</button> 
                <button id="modal-car-dos" type="button" class="btn btn-default">Cancelar</button>
            </div>
        </div>
    </div> 
</div> 

<div id="modal-agrega" class="modal sfade">
    <div class="modal-dialog">
        <div class="modal-content panel-default">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Precio de producto</h4>
            </div>
            <div id="bodyespecial" class="modal-body">
                <div class="row">
                    <div class="col-sm-12" style="padding-top:10px;">
                        <label class="col-sm-6 control-label text-left">$ Precio:</label>
                        <div class="col-sm-6">
                          <input id="precionuevo" type="text" value="0">
                        </div>
                    </div> 
                </div>
            </div>
            <div class="modal-footer">
                <button id="modal-recep-uno" type="button" class="btn btn-default">Continuar</button> 
            </div>
        </div>
    </div> 
</div>

<div id="modal-conf4" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-warning">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Atencion!</h4>
            </div>
            <div class="modal-body">
                <p>¿Seguro que deseas eliminar esta cotizacion?</p>
            </div>
            <div class="modal-footer">
                <button id="modal-btnconf4-uno" type="button" class="btn btn-default">Continuar</button> 
                <button id="modal-btnconf4-dos" type="button" class="btn btn-default">Cancelar</button>
            </div>
        </div>
    </div> 
</div> 


<div id="modal-conf3" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Orden de venta guardada!</h4>
            </div>
            <div class="modal-body">
                <p>La orden de venta fue guardada exitosamente.</p>
            </div>
        </div>
    </div> 
</div> 

<div id="modal-conf33" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Orden de compra guardada!</h4>
            </div>
            <div class="modal-body">
                <p>La orden de compra fue guardada exitosamente.</p>
                <div id="ovvv"></div>
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
                <p>Tienes una orden de venta sin guardar, ¿Deseas continuar sin guardar cambios?</p>
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
                <p>Tienes una orden de venta sin guardar, ¿Deseas continuar sin guardar cambios?</p>
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
    <?php if($vv!=1){ ?>
    <?php if($treqs>99999999) { ?>
    <div id="error_1">
        <div class="col-xs-12 col-md-12" style="display:block; padding-bottom:10px;">
        <div class="alert alert-warning">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>Atencion!</strong> Tienes <?php echo $treqs; ?> <a onclick="listareq();" style="cursor:pointer;"><?php echo $rtext; ?></a> por autorizar
        </div>
        </div>
    </div>
    <?php } ?>
    <?php } ?>

    <div class="container well" style="padding:25px;">
        <div class="row" style="padding-bottom:20px;">
            <div class="col-xs-12 col-md-12" style="padding:0px 0px 0px 3px;"><h3>Modulo Ventas</h3></div>
            <input type="hidden" id="ist" value="0">
            <input type="hidden" id="it" value="0">
            <input type="hidden" id="cadimps" value="0">
        </div>


        <?php if($vv!=1){ ?>
        <div class="row" style="margin-bottom:10px;">
            <button class="btn btn-default" type="button" onclick="nreq();">Nueva orden de venta</button>
            <button class="btn btn-default" type="submit" onclick="listareq();">Listado cotizaciones / ordenes de venta</button>
        </div>
        <?php } ?>
        <div id="nreq_load" class="row" style="display:none;font-size:12px;padding:2px;">
            <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
        </div>
       
        <div id="listareq_load" class="row" style="display:none;font-size:12px;padding:2px;">
            <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
        </div>

        <div id="listareq" class="row" style="display:block;margin-top:20px;font-size:12px;display:none">
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
                        <th class="no-sort" style="text-align: center;">Modificar</th>

                    </tr>
                </thead>
            </table>
        </div>


        <div id="nreq" class="row" style="display:none;">
            <div class="panel panel-default">
                <div id="ph"  class="panel-heading"><span opt="1" class="label label-primary" style="cursor:pointer;">Nueva orden de venta</span></div>
                <div class="panel-body" style="padding:15px 0 10px 0; font-size:12px;">
                    <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-2 control-label text-left">Usuario:</label>
                        <div class="col-sm-10" style="color:#096;">
                            <label id="userlog"><?php echo $username; ?></label>
                            <input type='hidden' id="iduserlog" value='<?php echo $iduser; ?>'>
                        </div>
                    </div>
                    </div>
                    <div class="col-sm-12" style="padding-top:10px;">
                    <div class="form-group">
                        <label class="col-sm-2 control-label text-left">No. OV</label>
                        <div id="txt_nreq" class="col-sm-2" style="color:#ff0000;">
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
                                    <option tc="<?php echo $v['tc']; ?>"  value="<?php echo $v['coin_id']; ?>"><?php echo $v['codigo']; ?></option>
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
                           <th width="30%" align="left">Descripcion</th>
                           <th width="10%" align="left">Unidad</th>
                           <th width="10%" align="left">$Unitario</th>
                           <th width="10%" align="left">Cantidad</th>
                           <th width="10" align="left">Subtotal</th>
                           <th class="no-sort" width="10%" align="right">&nbsp;</th>
                          </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="6" style="text-align:right">&nbsp;</th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                        <tbody id="filasprods">
                        </tbody>
                      </table>
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
                            <!--
                            <button id="btn_savequit" class="btn btn-sm btn-info pull-center" type="button" style="height:28px;" >Guardar y salirdddd</button> 
                        -->
                        <?php if($vv!=1){ ?>
                        <!--<button id="btn_imprimir" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-print"></span> Imprimir</button>-->
                            <button id="btn_savequit" class="btn btn-sm btn-primary pull-center" type="button" style="height:28px;">Guardar orden</button>
                            <button id="btn_authquit" class="btn btn-sm btn-success pull-center" type="button" style="height:28px;">Guardar y autorizar orden</button>
                            <?php } ?>
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
var table = '';
var ctipodesc = clspp = clspc = clgp = clgc = pass = '';
var siDescG = 0;
var auxsiDescG = 0;

$( document ).ready(function() {
  // config desc
        //var ctipodesc = clspp = slspc = clgp = clgc = pass = '';
        $.ajax({
                url:"ajax.php?c=ventas&f=configDesc",
                type: 'post',
                dataType: 'json',
                async: false,                
        })
        .done(function(data) {
            $.each(data, function(index, val) {
                ctipodesc = val.tipo_descuento; 
                clspp = val.limit_sin_pass_p;                 
                clspc = val.limit_sin_pass_c; 
                clgp = val.limit_global_p; 
                clgc = val.limit_global_c; 
                pass = val.password; 
            });
        })  
        // config desc fin
        //alert(ctipodesc+' '+clspp+' '+clspc+' '+clgp+' '+clgc+' '+pass);
});
// var desc config fin


function redondeo(numero, decimales){
var flotante = parseFloat(numero);
var resultado = Math.round(flotante*Math.pow(10,decimales))/Math.pow(10,decimales);
return resultado;
}

    $(function() {
        $('#precionuevo').numeric();
        vv='<?php echo $vv; ?>';
        if(vv==1){
            idoc='<?php echo $id_oc; ?>';
            $.ajax({
                url:"ajax.php?c=ventas&f=a_change_idoc_idreq",
                type: 'POST',
                data:{idoc:idoc},
                success: function(r){
                    editReq(r,0);
                }
            });
        }else{
            listareq();
        }


        $('#date_entrega').datepicker({
                format: "yyyy-mm-dd",
                startDate:  "<?php echo $sd; ?>",
                endDate: "<?php echo $ed; ?>",
                language: "es"
        });

        $('#date_hoy').datepicker({
                format: "yyyy-mm-dd",
                startDate:  "<?php echo $sd; ?>",
                endDate: "<?php echo $ed; ?>",
                language: "es"
        });
        var table = $('#tablaprods').DataTable( {
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
                                    .column(7)
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(a) + intVal(b);
                                    }, 0 );
                     
                                // Total over this page
                                pageTotal = api
                                    .column(7, { page: 'current'} )
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(a) + intVal(b);
                                    }, 0 );
                     
                                // Update footer
                                pt = redondeo(pageTotal,2);
                                tt = redondeo(total,2);
                                $( api.column(7).footer() ).html(
                                    '<div id="imps">Cargando...</div>'
                                    //'$'+pt +' ( $'+ tt +' Gran total)<br><div id="imps">a</div>'
                                );
                                }
                            });

        //Solucion al scroll-y
        $('#tablaprods_wrapper div:nth-child(2) div:nth-child(1)').css('overflow-y','auto');
        
        $('#tablaprods_info').css('margin-to','-10px');

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

        $( "#c_productos" ).change(function() {
            $('#btn_addProd').trigger('click');
        });
        
        $("#c_moneda").change(function() {
            //$('#c_proveedores').val($('#c_proveedores option:first-child').val()).trigger('change');
            idMoneda=$(this).val();
            if(idMoneda>0){
                $.ajax({
                    url:"ajax.php?c=ventas&f=a_getProdMoneda",
                    type: 'POST',
                    dataType: 'JSON',
                    data:{idMoneda:idMoneda},
                    success: function(r){
                        console.log(r);
                        if(r.success==1){
                            llenado='';
                            $.each( r.datos, function(k,v) {
                                llenado+='<option tc="'+v.tc+'" value="'+v.id+'">'+v.descripcion_corta+'</option>';
                            });
                            $("#c_productos").html('');
                            $("#c_productos").append(llenado);
                            $("#c_productos").select2();
                            $("#c_productos").prop('disabled',false);
                        }else{
                            $("#c_productos").prop('disabled',true);
                            $("#c_productos").html('<option value="0">Seleccione</option>');
                            $("#c_productos").select2();
                            alert('No hay productos asociados con esta moneda moneda');
                        }
                    }
                });
            }else{
                $("#c_productos").prop('disabled',true);
                $('#c_moneda').prop('disabled',false);
                $("#c_productos").html('<option value="0">Seleccione</option>');
                $("#c_productos").select2();

            }
            tc=$('#c_moneda option:selected').attr('tc');
            if(idMoneda==1){
                //$('#c_proveedores').prop('disabled',false);
                $('#moneda_tc').css('display','none');
            }else if(idMoneda>1){
                //$('#c_proveedores').prop('disabled',false);
                $('#moneda_tc').val(tc);
                $('#moneda_tc').numeric();
                $('#moneda_tc').css('display','block');
            }else{
                $('#moneda_tc').css('display','none');
                //$('#c_proveedores').prop('disabled',true);
            }
        });

        $("#c_cliente").change(function() {
            idCliente=$(this).val()
            if(idCliente>0){
                //$("#c_productos").prop('disabled',false);
            }else{
                $("#c_productos").prop('disabled',true);
            }
        });


        

        $("#btn_imprimirx").click(function() {
            //Verifica si es edicion o update

            option = $('#ph span').attr('opt');

            
            id_req = $('#txt_nreq').text();
            idrequi = $('#idrequi').val();

            
            disabled_btn('#btn_imprimir','Procesando...');

            solicitante=$('#c_solicitante').val();
            tipogasto=$('#c_tipogasto').val();
            moneda=$('#c_moneda').val();
            proveedor=$('#c_proveedores').val();
            cliente=$('#c_cliente').val();

            obs=$('#comment').val();
            obs = obs.replace(/\r\n|\r|\n/g,"<br />");

            almacen=$('#c_almacen').val();


            ist=$('#ist').val();
            it=$('#it').val();
            cadimps=$('#cadimps').val();


          

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


            deten=0;
            if(solicitante==0){ 
                alert('Tienes que seleccionar un solicitante'); 
                deten=1;
            }else if(cliente==0 && deten==0){ 
                alert('Tienes que seleccionar un cliente'); 
                deten=1;
            }else if(fechahoy=='' && deten==0){ 
                alert('Tienes que seleccionar una fecha'); 
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
                enabled_btn('#btn_savequit','Generar cotizacion');
                return false;
            }

            idsProductos = $('#filasprods tr').map(function() {
                cant = $(this).find('.numeros').val();
                ch = $(this).attr('ch');

                precio = $(this).find('select').val();

                tftf = precio.split('>');

                precio = tftf[0];
                idlista= tftf[1];

                trid = this.id;
                id = trid.split('tr_');

                if (typeof id[1] !== "undefined") {
                    id= id[1]+'>#'+cant+'>#'+precio+'>#'+idlista+'>#'+ch;
                }
                
                return id;
            }).get().join(',# ');  

            console.log(idsProductos);
  

            if(idsProductos==''){
                msg_error(1);
                enabled_btn('#btn_imprimir','<span class="glyphicon glyphicon-print"></span> Imprimir');
                return false;
            }else{
                imps=$('#imps').html();
                console.log(idsProductos);
                $.ajax({
                    url:"ajax.php?c=ventas&f=a_imprimir",
                    type: 'POST',
                    data:{
                        cliente:cliente,
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
                        obs:obs,
                        ist:ist,
                        it:it,
                        cadimps:cadimps,
                        imps:imps
                    },
                    success: function(r){
                        window.open('../../modulos/appministra/ticketImp.php','');
                         enabled_btn('#btn_imprimir','<span class="glyphicon glyphicon-print"></span> Imprimir');
                        //enabled_btn('#btn_savequit','Guardar y salir');
                    }
                });
            }

        
        });

        $("#btn_savequit").click(function() {
            iduserlog = $('#iduserlog').val();
            total = $('#grantotal').val();

            //Verifica si es edicion o update

            option = $('#ph span').attr('opt');


            obs=$('#comment').val();
            obs = obs.replace(/\r\n|\r|\n/g,"<br />");

            
            id_req = $('#txt_nreq').text();
            idrequi = $('#idrequi').val();

            
            disabled_btn('#btn_savequit','Procesando...');

            solicitante=$('#c_solicitante').val();
            tipogasto=$('#c_tipogasto').val();
            moneda=$('#c_moneda').val();
            cliente=$('#c_cliente').val();
    

 




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

            ist=$('#ist').val();
            it=$('#it').val();
            cadimps=$('#cadimps').val();

            deten=0;
            if(solicitante==0){ 
                alert('Tienes que seleccionar un solicitante'); 
                deten=1;
            }else if(cliente==0 && deten==0){ 
                alert('Tienes que seleccionar un cliente'); 
                deten=1;
            }else if(fechahoy=='' && deten==0){ 
                alert('Tienes que seleccionar una fecha'); 
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
            }else if(ist==0 && deten==0){ 
                alert('El subtotal debe ser mayor a 0'); 
                deten=1; 
            }else if(it==0 && deten==0){ 
                alert('El total debe ser mayor a 0'); 
                deten=1; 
            }

            if(deten==1){
                enabled_btn('#btn_savequit','Guardar orden');
                return false;
            }

            idsProductos = $('#filasprods tr').map(function() {
                cant = $(this).find('.numeros').val();
                costoprod = $(this).find('#valUnit').find('input').val();
                ch = $(this).attr('ch');

                precio = $(this).find('select').val();

                //desc
                oldp = $(this).attr('oldp');
                tipodesc = $(this).attr('tipodesc');
                montod = $(this).attr('montod');                            
                //

                tftf = precio.split('>');

                precio = tftf[0];
                idlista= tftf[1];

                trid = this.id;
                id = trid.split('tr_');

                if (typeof id[1] !== "undefined") {
                    //id= id[1]+'>'+cant+'>'+costoprod;
                    id= id[1]+'>#'+cant+'>#'+precio+'>#'+idlista+'>#'+ch+'>#'+oldp+'>#'+tipodesc+'>#'+montod;
                }
                
                return id;
            }).get().join(',# ');  

            console.log(idsProductos);
            //return false;

            //DESCG
            var auxDescG = $("#auxDescG").val();
            var total = $("#total").val();
            var descG = 0;

            descG = total - it;
            var desCantidadG = $("#desCantidadG").val();


            if(idsProductos==''){
                msg_error(1);
                enabled_btn('#btn_savequit','Guardar orden');
                return false;
            }else{
                console.log(idsProductos);
                $.ajax({
                    url:"ajax.php?c=ventas&f=a_guardarOrden",
                    type: 'POST',
                    data:{
                        idsProductos:idsProductos,
                        solicitante:solicitante,
                        tipogasto:tipogasto,
                        moneda:moneda,
                        urgente:urgente,
                        inventariable:inventariable,
                        moneda_tc:moneda_tc,
                        fechaentrega:fechaentrega,
                        fechahoy:fechahoy,
                        option:option,
                        idrequi:idrequi,
                        idactivo:3,
                        obs:obs,
                        ist:ist,
                        it:it,
                        cadimps:cadimps,
                        cliente:cliente,
                        iduserlog:iduserlog,
                        total:total,
                        monto_desc:descG,
                        descc:desCantidadG
                    },
                    success: function(r){
                        console.log(r);
                        if(r>0){
                            table = $('#tablaprods').DataTable();
                            table.clear().draw();
                            $('#nreq').css('display','none');
                            resetearReq();
                            $('#modal-conf3').modal('show');
                        }else{
                            alert('Error de conexion');
                            enabled_btn('#btn_savequit','Guardar orden');
                        }
                        //enabled_btn('#btn_savequit','Guardar y salir');
                    }
                });
            }

        
        });

        $("#btn_authquit").click(function() {
            nextov = $('#nextov').val();
            iduserlog = $('#iduserlog').val();
            //total = $('#grantotal').val();

            //Verifica si es edicion o update

            option = $('#ph span').attr('opt');

            
            id_req = $('#txt_nreq').text();
            idrequi = $('#idrequi').val();

            
            disabled_btn('#btn_authquit','Procesando...');

            solicitante=$('#c_solicitante').val();
            tipogasto=$('#c_tipogasto').val();
            moneda=$('#c_moneda').val();
            cliente=$('#c_cliente').val();

            almacen=$('#c_almacen').val();

            obs=$('#comment').val();
            obs = obs.replace(/\r\n|\r|\n/g,"<br />");


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


            ist=$('#ist').val();
            it=$('#it').val();
            cadimps=$('#cadimps').val();


            deten=0;
            if(solicitante==0){ 
                alert('Tienes que seleccionar un solicitante'); 
                deten=1;
            }else if(fechahoy=='' && deten==0){ 
                alert('Tienes que seleccionar una fecha'); 
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
            }else if(cliente==0 && deten==0){ 
                alert('Tienes que seleccionar un cliente'); 
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
                enabled_btn('#btn_authquit','Guardar y autorizar orden');
                return false;
            }
            /*
            idsProductos = $('#filasprods tr').map(function() {
                cant = $(this).find('.numeros').val();
                costoprod = $(this).find('#valUnit').find('input').val();

                trid = this.id;
                id = trid.split('tr_');

                if (typeof id[1] !== "undefined") {
                    id= id[1]+'>'+cant+'>'+costoprod;
                }
                
                return id;
            }).get().join(', ');  

            console.log(idsProductos);
            */

            idsProductos = $('#filasprods tr').map(function() {
                cant = $(this).find('.numeros').val();
                costoprod = $(this).find('#valUnit').find('input').val();
                ch = $(this).attr('ch');

                precio = $(this).find('select').val();

                //desc
                oldp = $(this).attr('oldp');
                tipodesc = $(this).attr('tipodesc');
                montod = $(this).attr('montod');                            
                //

                tftf = precio.split('>');

                precio = tftf[0];
                idlista= tftf[1];

                trid = this.id;
                id = trid.split('tr_');

                if (typeof id[1] !== "undefined") {
                    //id= id[1]+'>'+cant+'>'+costoprod;
                    id= id[1]+'>#'+cant+'>#'+precio+'>#'+idlista+'>#'+ch+'>#'+oldp+'>#'+tipodesc+'>#'+montod;
                }
                
                return id;
            }).get().join(',# ');  

            console.log(idsProductos);

            var auxDescG = $("#auxDescG").val();
            var total = $("#total").val();
            var descG = 0;

            descG = total - it;
            var desCantidadG = $("#desCantidadG").val();
            //alert(total+''+descG+''+desCantidadG);

            //return false;

            if(idsProductos==''){
                msg_error(1);
                enabled_btn('#btn_authquit','Guardar y autorizar orden');
                return false;
            }else{

                if(nextov==0){
                console.log(idsProductos);

                $.ajax({
                    url:"ajax.php?c=ventas&f=a_ordenVentaInv",
                    type: 'POST',
                    dataType: 'json',
                    data:{
                        idsProductos:idsProductos,
                        solicitante:solicitante,
                        tipogasto:tipogasto,
                        moneda:moneda,
                        urgente:urgente,
                        inventariable:inventariable,
                        moneda_tc:moneda_tc,
                        fechaentrega:fechaentrega,
                        fechahoy:fechahoy,
                        option:option,
                        idrequi:idrequi,
                        idactivo:1,
                        obs:obs,
                        ist:ist,
                        it:it,
                        cadimps:cadimps,
                        cliente:cliente,
                        iduserlog:iduserlog,
                        total:total,
                        monto_desc:descG,
                        descc:desCantidadG

                    },
                    success: function(r){
                        fuego=0;
                        console.log(r);
                        if(r.success==1 && r.data!=null){
                            var rr = confirm("No hay suficiente inventario para surtit la venta. Presione ACEPTAR si desea crear una orden de compra o presione CANCELAR si desea continuar y generar la orden de venta.");
                            if(rr==true){
                                $('#shala').empty();
                                $('#imps_exp').empty();
                                $('#userlog').html( $('#userlog').text() );
                                $('#iduserlog').val(iduserlog);
                                $('#alma_exp').html('Almacen General');
                                $('#ovvv').html('');
                                

                                $('#solicitante_exp').prop('disabled',false);
                                $('#tgasto_exp').prop('disabled',false);
                                $('#selpoc').prop('disabled',false);

                                $('#ist_exp').val(0);
                                $('#it_exp').val(0);
                                $('#cadimps_exp').val(0);

          
                                $('#solicitante_exp').html( $('#c_solicitante').html() );
                                //$('#tgasto_exp').html( $('#c_tipogasto').html() );
                                $('#tgasto_exp').html('<option value="0">Seleccione</option><option value="6">Egreso</option>');
                                
                                /*
                                $arreglo[]=array('idProd'=>$idprod,
                            'cantov'=>$cant,
                            'cantinv'=>$totalcant,
                            'cantdif'=>$cantdif,
                            'car'=>$caracteristica
                            */
                                lostr='';
                                console.log(r.data);
                                i=0;
                                $.each(r.data, function( k, v ) {
                                    console.log(v);
                                    cod_p=$('#tr_'+v.idProd+'[ch="'+v.car+'"] td:eq(1)').text();
                                    des_p=$('#tr_'+v.idProd+'[ch="'+v.car+'"] td:eq(2)').text();
                                    uni_p=$('#tr_'+v.idProd+'[ch="'+v.car+'"] td:eq(3)').text();

                                    lostr+='<tr id="troc_'+v.idProd+'" choc="'+v.car+'">';
                                    lostr+='<td>'+cod_p+'</td>';
                                    lostr+='<td>'+des_p+'</td>';
                                    lostr+='<td>'+uni_p+'</td>';
                                    lostr+='<td id="unitco_'+i+'">-</td>';
                                    lostr+='<td id="cantco_'+i+'">'+v.cantdif+'</td>';
                                    lostr+='<td id="subtco_'+i+'">-</td>';
                                    lostr+='</tr>';
                                    i++;
                                });
                              

                                $('#idspoc').val(r.ids);
                                $('#shala').html(lostr);
                                if(r.prov==0){
                                    alert('No tienes un proveedor que surta todos los productos de esta orden.');
                                    $('#selpoc').html('<option value="0">No hay proveedores</option>');
                                    enabled_btn('#btn_authquit','Guardar y autorizar orden');
                                    $('#modal-ce').modal('hide');
                                }else{
                                    $('#selpoc').html(r.prov);
                                    $('#modal-ce').modal({
                                        backdrop: 'static',
                                        keyboard: false, 
                                        show: true
                                    });
                                }
                                
                            }else{
                                $.ajax({
                                        url:"ajax.php?c=ventas&f=a_guardarOrden",
                                        type: 'POST',
                                        data:{
                                            idsProductos:idsProductos,
                                            solicitante:solicitante,
                                            tipogasto:tipogasto,
                                            moneda:moneda,
                                            urgente:urgente,
                                            inventariable:inventariable,
                                            moneda_tc:moneda_tc,
                                            fechaentrega:fechaentrega,
                                            fechahoy:fechahoy,
                                            option:option,
                                            idrequi:idrequi,
                                            idactivo:1,
                                            obs:obs,
                                            ist:ist,
                                            it:it,
                                            cadimps:cadimps,
                                            cliente:cliente,
                                            iduserlog:iduserlog,
                                            total:total,
                                            monto_desc:descG,
                                            descc:desCantidadG
                                        },
                                        success: function(r){
                                            console.log(r);
                                            if(r>0){
                                                imps=$('#imps').html();
                                                $.ajax({
                                                    url:"ajax.php?c=ventas&f=a_enviarCotizacion2",
                                                    type: 'POST',
                                                    data:{
                                                        idCoti:r,
                                                        imps:imps,
                                                        op:1,
                                                        print:0,
                                                        tipo:'Orden de venta'
                                                    },
                                                    success: function(r){

                                                    }
                                                });
                                                

                                                $( "#modal-ce-uno" ).removeClass( "disabled" );
                                                $( "#modal-ce-dos" ).removeClass( "disabled" );
                                                $( "#modal-ce-tres" ).removeClass( "disabled" );

                                                $('#modal-ce').modal('hide');
                                                $('#nextov').val(0);

                                                table = $('#tablaprods').DataTable();
                                                table.clear().draw();
                                                $('#nreq').css('display','none');
                                                resetearReq();
                                                $('#modal-conf3').modal('show');
                                            }else{
                                                alert('Error de conexion');
                                                enabled_btn('#btn_savequit','Guardar y autorizar orden');
                                            }
                                            //enabled_btn('#btn_savequit','Guardar y salir');
                                        }
                                    });
                            }
                        }else{
                            $.ajax({
                                    url:"ajax.php?c=ventas&f=a_guardarOrden",
                                    type: 'POST',
                                    data:{
                                        idsProductos:idsProductos,
                                        solicitante:solicitante,
                                        tipogasto:tipogasto,
                                        moneda:moneda,
                                        urgente:urgente,
                                        inventariable:inventariable,
                                        moneda_tc:moneda_tc,
                                        fechaentrega:fechaentrega,
                                        fechahoy:fechahoy,
                                        option:option,
                                        idrequi:idrequi,
                                        idactivo:1,
                                        obs:obs,
                                        ist:ist,
                                        it:it,
                                        cadimps:cadimps,
                                        cliente:cliente,
                                        iduserlog:iduserlog,
                                        total:total,
                                        monto_desc:descG,
                                        descc:desCantidadG
                                    },
                                    success: function(r){
                                        console.log(r);
                                        if(r>0){
                                            imps=$('#imps').html();
                                            $.ajax({
                                                url:"ajax.php?c=ventas&f=a_enviarCotizacion2",
                                                type: 'POST',
                                                data:{
                                                    idCoti:r,
                                                    imps:imps,
                                                    op:1,
                                                    print:0,
                                                    tipo:'Orden de venta'
                                                },
                                                success: function(r){

                                                }
                                            });
                                            

                                            $( "#modal-ce-uno" ).removeClass( "disabled" );
                                            $( "#modal-ce-dos" ).removeClass( "disabled" );
                                            $( "#modal-ce-tres" ).removeClass( "disabled" );

                                            $('#modal-ce').modal('hide');
                                            $('#nextov').val(0);

                                            table = $('#tablaprods').DataTable();
                                            table.clear().draw();
                                            $('#nreq').css('display','none');
                                            resetearReq();
                                            $('#modal-conf3').modal('show');
                                        }else{
                                            alert('Error de conexion');
                                            enabled_btn('#btn_savequit','Guardar y autorizar orden');
                                        }
                                        //enabled_btn('#btn_savequit','Guardar y salir');
                                    }
                                });
                        }
                        //enabled_btn('#btn_savequit','Guardar y salir');
                    }
                });
                }else{
              
                
                $.ajax({
                    url:"ajax.php?c=ventas&f=a_guardarOrden",
                    type: 'POST',
                    data:{
                        idsProductos:idsProductos,
                        solicitante:solicitante,
                        tipogasto:tipogasto,
                        moneda:moneda,
                        urgente:urgente,
                        inventariable:inventariable,
                        moneda_tc:moneda_tc,
                        fechaentrega:fechaentrega,
                        fechahoy:fechahoy,
                        option:option,
                        idrequi:idrequi,
                        idactivo:1,
                        obs:obs,
                        ist:ist,
                        it:it,
                        cadimps:cadimps,
                        cliente:cliente,
                        iduserlog:iduserlog,
                        total:total,
                        monto_desc:descG,
                        descc:desCantidadG
                    },
                    success: function(r){
                        console.log(r);
                        if(r>0){
                            imps=$('#imps').html();
                            $.ajax({
                                url:"ajax.php?c=ventas&f=a_enviarCotizacion2",
                                type: 'POST',
                                data:{
                                    idCoti:r,
                                    imps:imps,
                                    op:1,
                                    print:0,
                                    tipo:'Orden de venta'
                                },
                                success: function(r){

                                }
                            });
                            

                            $( "#modal-ce-uno" ).removeClass( "disabled" );
                            $( "#modal-ce-dos" ).removeClass( "disabled" );
                            $( "#modal-ce-tres" ).removeClass( "disabled" );

                            $('#modal-ce').modal('hide');
                            $('#nextov').val(0);

                            table = $('#tablaprods').DataTable();
                            table.clear().draw();
                            $('#nreq').css('display','none');
                            resetearReq();
                            $('#modal-conf3').modal('show');
                        }else{
                            alert('Error de conexion');
                            enabled_btn('#btn_savequit','Guardar y autorizar orden');
                        }
                        //enabled_btn('#btn_savequit','Guardar y salir');
                    }
                });

                }
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
            idCliente = $('#c_cliente').val();
            if(idCliente==0){
                alert('Para agregar productos tienes que seleccionar un cliente');
                return false;
            }
            cprodval = $('#c_productos').val();
            splitcprod = cprodval.split('-');
            idProducto = splitcprod[0];
            cantidadalm = splitcprod[1];
            tienecar = $('#c_productos option:selected').attr('tc');
            if(idProducto>0){
         
                disabled_btn('#btn_addProd','Procesando...');
                if($("#tr_"+idProducto).length   && tienecar==0) {
                    valorig = $("#tr_"+idProducto+" #ccc").val()*1;
                    console.log(valorig,0);
                    $("#tr_"+idProducto+" #ccc").val((valorig*1)+1);
                    refreshCants(idProducto,0);
                    enabled_btn('#btn_addProd','Agregar producto');
                    return false;
                } 

                $.ajax({
                url:"ajax.php?c=ventas&f=a_addProductoVenta",
                type: 'POST',
                dataType:'JSON',
                data:{idProducto:idProducto,idCliente:idCliente},
                success: function(r){
                    console.log(r);
                    if(r.success==1){
                        if(r.car!=''){
                            $('#divcar').html(r.car);
                            $('#modal-car').modal('show');
                            $('#modal-car-uno').on('click',function(){

                                cadcar = $('.carh').map(function() {
                                    aaa=$(this).val();
                                    return aaa;
                                }).get().join(',');

                                if($("#tr_"+idProducto+"[ch='"+cadcar+"']").length) {
                                    valorig = $("#tr_"+idProducto+"[ch='"+cadcar+"'] #ccc").val();
                                    $("#tr_"+idProducto+"[ch='"+cadcar+"'] #ccc").val((valorig*1)+1);
                                    refreshCants(idProducto,cadcar);
                                    enabled_btn('#btn_addProd','Agregar producto');
                                    $('#modal-car').modal('hide');
                                    $('#modal-car-uno').unbind();
                                    $('#modal-car-dos').unbind();
                                    return false;
                                } 

                                cadcartxt = $('.s7').map(function() {
                                    padre=$(this).find('#npadre').text();
                                    hijo=$(this).find('.carh option:selected').text();
                                    aaa='( '+padre+': '+hijo+' )';
                                    return aaa;
                                }).get().join('');

                                $('#c_cliente').prop('disabled',true);
                                $('#c_proveedores').prop('disabled',true);
                                $('#c_almacen').prop('disabled',true);
                                data_almacen=$('#data_almacen').html();
                                txt_proveedor=$('#c_proveedores option:selected').text();
                                txt_almacen=$('#c_almacen option:selected').text();

                                if(ctipodesc == 2 || ctipodesc == 3){
                                    var btndescP = "<button class='btn btn-sm btn-info btndesc' type='button' style='height:26px;' onclick='modalDescProdCot("+r.datos[0].id+",\""+cadcar+"\",\""+r.datos[0].descripcion_corta+"\");'>Descuento</button>";
                                }else{
                                    var btndescP =''; /// ELIMINAR
                                }
                                                               
                                var Rowdata = "<tr ch='"+cadcar+"' newp='0' oldp='0' tipoDesc='0' montoD ='0' id='tr_"+r.datos[0].id+"'><td>0</td><td>"+r.datos[0].codigo+"</td><td>"+r.datos[0].descripcion_corta+" "+cadcartxt+"</td><td>"+r.datos[0].clave+"</td><td id='valUnit'>"+r.adds+"</td><td><input id='ccc' style='width:60%;' onkeyup='refreshCants("+r.datos[0].id+",\""+cadcar+"\")' class='numeros' type='text' value='1'/></td><td class='valImporte' implimpio='"+r.datos[0].costo+"' id='valImporte'>"+r.datos[0].costo+"</td><td><button onclick='removeProdReq("+r.datos[0].id+",\""+cadcar+"\");' id='btn_addProd' class='btn btn-sm btn-danger' type='button' style='height:26px;'>Quitar</button>"+btndescP+"</td></tr>";
                                $('#modal-car').modal('hide');
                                table.row.add($(Rowdata)).draw();
                                //$("#tr_"+r.datos[0].id+"[ch='"+cadcar+"']").find('#prelis').prop('disabled',true);

                                $("#tr_"+r.datos[0].id+"[ch='"+cadcar+"']").find('#prelis').attr("onchange","refreshCants("+r.datos[0].id+",\""+cadcar+"\")");

                                $('#panel_tabla').css('display','block');
                                $('.numeros').numeric();
                                $('.numerosunit').numeric();
                                enabled_btn('#btn_addProd','Agregar producto'); 
                                refreshCants(idProducto,cadcar);
                                $('#modal-car-uno').unbind();
                                $('#modal-car-dos').unbind();

                            });

                            $('#modal-car-dos').on('click',function(){
                                enabled_btn('#btn_addProd','Agregar producto');
                                $('#modal-car').modal('hide');
                                $('#modal-car-uno').unbind();
                                $('#modal-car-dos').unbind();
                            });

                        }else{

                            $('#c_cliente').prop('disabled',true);
                            $('#c_proveedores').prop('disabled',true);
                            $('#c_almacen').prop('disabled',true);
                            data_almacen=$('#data_almacen').html();
                            txt_proveedor=$('#c_proveedores option:selected').text();
                            txt_almacen=$('#c_almacen option:selected').text();


                        if(ctipodesc == 2 || ctipodesc == 3){
                            var btndescP = "<button class='btn btn-sm btn-info btndesc' type='button' style='height:26px;' onclick='modalDescProdCot("+r.datos[0].id+",0,\""+r.datos[0].descripcion_corta+"\");'>Descuento</button>";                                    
                        }else{
                            var btndescP =''; /// ELIMINAR
                        }
                            
                            
                            var Rowdata = "<tr ch='0' newp='0' oldp='0' tipoDesc='0' montoD='0' id='tr_"+r.datos[0].id+"'><td>0</td><td>"+r.datos[0].codigo+"</td><td>"+r.datos[0].descripcion_corta+"</td><td>"+r.datos[0].clave+"</td><td id='valUnit'>"+r.adds+"</td><td><input id='ccc' style='width:60%;' onkeyup='refreshCants("+r.datos[0].id+",0)' class='numeros' type='text' value='1'/></td><td class='valImporte' implimpio='"+r.datos[0].costo+"' id='valImporte'>"+r.datos[0].costo+"</td><td><button onclick='removeProdReq("+r.datos[0].id+",0);' id='btn_addProd' class='btn btn-sm btn-danger' type='button' style='height:26px;'>Quitar</button>"+btndescP+"</td></tr>";
                                table.row.add($(Rowdata)).draw();
                                $('#panel_tabla').css('display','block');
                                $('.numeros').numeric();
                                $('.numerosunit').numeric();
                                enabled_btn('#btn_addProd','Agregar producto'); 
                                refreshCants(idProducto,0);

                        }
                        
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

    // ch
    function modalDescProdCot(idProducto,cadcar,nomprod){
        $("#inpass").val('');
        $('#modalDescParcial').modal('show');
        
        $('#idprodDescP').val(idProducto);
        $('#idcarDescp').val(cadcar);
        var precio = $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(5)').find('select').val();
        var precioR = precio.slice(0,-2);
        $('#idprecio').val(precioR);
        $('#encabezadoNombre').text(nomprod);

        $('#encabezadoPrecio').text('$'+precioR); 
    }

    function aplicaDesParcial(){
        var limitDesPP = clspp;
        var limitDesCP = clspc; 

        var idProducto = $('#idprodDescP').val();
        var cadcar = $('#idcarDescp').val();
        var cantidadDesc = $("#desCantidad").val()*1;
        var precio = $('#idprecio').val()*1;
        var oldp = precio;

        var inpass = $("#inpass").val();


        if($("#tipoDescu").val() == '%'){
            if(cantidadDesc > 99){
                alert('No puede dar descuento de 100%');
                return false;
            }
            var tipoD = '1'; /// PORCENTAJE 1
            if(cantidadDesc > limitDesPP){
                if(inpass == ''){
                    alert('Ingrese la contraseña');
                    return false;
                }
                if(inpass != pass){
                    alert('La contraseña es incorrecta');
                    return false;
                }
                precio = precio - ((cantidadDesc * precio) / 100);            
            }else{
                precio = precio - ((cantidadDesc * precio) / 100);
            }
        }else{
            var tipoD = '0';  /// CANTIDAD
            if(cantidadDesc > limitDesCP){
                if(inpass == ''){
                    alert('Ingrese la contraseña');
                    return false;
                }
                if(inpass != pass){
                    alert('La contraseña es incorrecta');
                    return false;
                }
                precio = precio - cantidadDesc;
            }else{
                precio = precio - cantidadDesc;
            }
        }


        $("#filasprods").find('#tr_'+idProducto+'[ch="'+cadcar+'"]').attr('oldp',oldp); // se guarda el precio al que se le aplico el desc
        $('#tr_'+idProducto+'[ch="'+cadcar+'"]').attr('montoD', cantidadDesc);
        $('#tr_'+idProducto+'[ch="'+cadcar+'"]').attr('tipodesc', tipoD);

        pn = precio;

        //$("#tr_"+idProducto+"[ch='"+cadcar+"'] #valImporte").attr('implimpio',valImporte);

        $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(5)').find('select').append('<option value="'+pn+'>x" selected="selected">$'+pn+'</option>');
        refreshCants(idProducto,cadcar,1);
        $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(8)').find(".btndesc").attr('disabled', 'true');
        $('#modalDescParcial').modal('hide');
        //return false;

        // SI TIENE UN DESC GLOBAL
        if(siDescG > 0){
            aplicaDesGlobal2(siDescG);
            //alert(siDescG);
        }
    }
    function descGoblal(){ //ch@
        $("#inpassG").val('');
        var num_cot = $("#txt_nreq").text();
        var total = $("#total").val()*1;
        var subtotal = $("#subtotal").val()*1;
        $('#modalDescGlobal').modal('show');    
        $("#h3DescG1").text('Cotizacion No.'+ num_cot);
        $("#h3DescG2").text('Total: $'+total);
        //alert(total+' '+subtotal);
    }
    function aplicaDesGlobal(){
        var total = $('#total').val()*1;
        var desCantidadG = $("#desCantidadG").val()*1;
        var inpassG = $("#inpassG").val();
        if(desCantidadG > clgp){
            if(inpassG == ''){
              alert('Ingrese la Contraseña');  
              return false;
            }
            if(inpassG != pass){
               alert('La contraseña es incorrecta'); 
               return false;
            }
            var descG =  (desCantidadG * total) / 100;
            total = total - descG;
            $("#auxDescG").val(1); // auxiliar para saber si tiene descuento g 
            $("#imps .row:last-child").before('<div id="divdesc" class="row"><div class="col-sm-6"> <label> Descuento '+desCantidadG+'%</label> </div> <div class="col-sm-6"> <label> $'+descG+' </label>  </div></div>');
            $("#lbtotal").text('$'+total);
            $("#btndescG").prop( "disabled", true );
            $("#btndescG").after('<a onclick="removeDescG();" style="cursor:pointer;">X</a>');
            $('#modalDescGlobal').modal('hide');
            $('#it').val(total);
        }else{
            var descG =  (desCantidadG * total) / 100;
            total = total - descG;
            $("#auxDescG").val(1); // auxiliar para saber si tiene descuento g 
            $("#imps .row:last-child").before('<div id="divdesc" class="row"><div class="col-sm-6"> <label> Descuento '+desCantidadG+'%</label> </div> <div class="col-sm-6"> <label> $'+descG+' </label>  </div></div>');
            $("#lbtotal").text('$'+total);
            $("#btndescG").prop( "disabled", true );
            $("#btndescG").after('<a onclick="removeDescG();" style="cursor:pointer;">X</a>');
            $('#modalDescGlobal').modal('hide');
            $('#it').val(total);
        }
        
        
    }
    function aplicaDesGlobal2(desCantidadG){
        var total = $('#total').val()*1;
        //var desCantidadG = $("#desCantidadG").val()*1;
        var descG =  (desCantidadG * total) / 100;
        total = total - descG;
        $("#auxDescG").val(1); // auxiliar para saber si tiene descuento g 
        $("#imps .row:last-child").before('<div id="divdesc" class="row"><div class="col-sm-6"> <label> Descuento '+desCantidadG+'%</label> </div> <div class="col-sm-6"> <label> $'+descG+' </label>  </div></div>');
        $("#lbtotal").text('$'+total);
        $("#btndescG").prop( "disabled", true );
        $("#btndescG").after('<a onclick="removeDescG();" style="cursor:pointer;">X</a>');
        $('#modalDescGlobal').modal('hide');
        $('#it').val(total);
        
    }

    function removeDescG(){
        auxsiDescG = 1;
        var total = $("#total").val();
        $("#divdesc").remove();
        recalcula();
        $("#auxDescG").val(0);
        $("#desCantidadG").val(0);
    }
    //ch fin

        function imprimir2(req,modo){
            $('#btn_imprimir_'+req+'_').prop('disabled',true);
            $.ajax({
                url:"ajax.php?c=ventas&f=a_enviarCotizacion2",
                type: 'POST',
                data:{
                    idCoti:req,
                    modo:modo,
                    print:1,
                    tipo:'Orden de venta'

                },
                success: function(r){
                    window.open("../../modulos/cotizaciones/cotizacionesPdf/Oventa_"+r+".pdf");
                    $('#btn_imprimir_'+req+'_').prop('disabled',false);
                }
            });
        }

    function eliminaReq(idReq){


        $('#modal-conf4').modal('show').one('click', '#modal-btnconf4-uno', function(){
            $.ajax({
                url:"ajax.php?c=ventas&f=a_eliminaRequisicion",
                type: 'POST',
                dataType:'JSON',
                data:{idReq:idReq},
                success: function(r){
                    if(r==1){
                        $('#modal-conf4').modal('hide');
                        listareq();

                        //resetearReq();
                        //$('#txt_nreq').text(r.requisicion);
                        //$('#nreq_load').css('display','none');
                        //$('#nreq').css('display','block');
                    }else{
                        $('#modal-conf4').modal('hide');
                        alert('No se puede eliminar esta cotizacion');
                    }
                }
            });
        }).one('click', '#modal-btnconf4-dos', function(){
            $('#modal-conf4').modal('hide');

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

    $('#modal-ce-tres').on('click',function(){
            enabled_btn('#btn_authquit','Guardar y autorizar orden');
            $('#modal-ce').modal('hide');
    });

    $('#modal-ce-dos').on('click',function(){
            saveOrdenExp(0);
    });

    $('#modal-ce-uno').on('click',function(){
            saveOrdenExp(1);
    });

    function saveOrdenExp(ovn){
        $( "#modal-ce-dos" ).addClass( "disabled" );
         $( "#modal-ce-uno" ).addClass( "disabled" );
         $( "#modal-ce-tres" ).addClass( "disabled" );
        iduserlog = $('#iduserlog').val();

            option = 1;
            id_req = $('#txt_nreq').text();//.
            idrequi = $('#idrequi').val();//.
            //disabled_btn('#btn_authquit','Procesando...');
            solicitante=$('#solicitante_exp').val();
            tipogasto=$('#tgasto_exp').val();
            moneda=$('#c_moneda').val();
            proveedor=$('#selpoc').val();
            almacen=1;

            obs='';


            fechahoy=$('#fechahoy').text();//.
            fechahoy=$('#date_hoy').val();//.
            fechaentrega=$('#date_entrega').val();//.
            
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

            ist=$('#ist_exp').val();
            it=$('#it_exp').val();
            cadimps=$('#cadimps_exp').val();
            deten=0;
            if(solicitante==0){ 
                alert('Tienes que seleccionar un solicitante'); 
                deten=1;
            }else if(fechahoy=='' && deten==0){ 
                alert('Tienes que seleccionar una fecha de entrega'); 
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
                //enabled_btn('#btn_authquit','Guardar y autorizar orden');
                $( "#modal-ce-uno" ).removeClass( "disabled" );
                $( "#modal-ce-dos" ).removeClass( "disabled" );
                $( "#modal-ce-tres" ).removeClass( "disabled" );
                return false;
            }
            i=0;
            idsProductos = $('#shala tr').map(function() {

                cant = $(this).find('td#cantco_'+i).text();
                ch = $(this).attr('choc');
                costoprod = $(this).find('td#unitco_'+i).text();

                trid = this.id;
                id = trid.split('troc_');

                if (typeof id[1] !== "undefined") {
                    id= id[1]+'>#'+cant+'>#'+costoprod+'>#'+ch;
                }
                i++;
                return id;
            }).get().join(',# '); 
            

            if(idsProductos==''){
                alert('No tiene productos');
                $( "#modal-ce-uno" ).removeClass( "disabled" );
                $( "#modal-ce-dos" ).removeClass( "disabled" );
                $( "#modal-ce-tres" ).removeClass( "disabled" );
                return false;
            }else{
                console.log(idsProductos);
                $.ajax({
                    url:"ajax.php?c=compras&f=a_guardarOrden",
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
                        ist:ist,
                        it:it,
                        idactivo:1,
                        obs:obs,
                        cadimps:cadimps,
                        iduserlog:iduserlog
                    },
                    success: function(r){
                        console.log(r);
                        if(option==1){
                            if(r>0){
                                imps=$('#imps').html();
                                $.ajax({
                                    url:"ajax.php?c=compras&f=a_enviarPedido2",
                                    type: 'POST',
                                    data:{
                                        idOc:r,
                                        imps:imps,
                                        op:9,
                                        print:0

                                    },
                                    success: function(r){
                             
                                    }
                                });

                                $('#nextov').val(ovn);
                                
                                //table = $('#tablaprods').DataTable();
                                //table.clear().draw();
                                //$('#nreq').css('display','none');
                                //resetearReq();
                                $('#modal-conf33').modal('show');
                                if(ovn==1){
                                    $('#ovvv').html(' Generando orden de venta... ');
                                    $('#btn_authquit').trigger('click');
                                }else{
                                    $( "#modal-ce-uno" ).removeClass( "disabled" );
                                    $( "#modal-ce-dos" ).removeClass( "disabled" );
                                    $( "#modal-ce-tres" ).removeClass( "disabled" );
                                    $('#modal-ce').modal('hide');
                                    enabled_btn('#btn_authquit','Guardar y autorizar orden');
                                }
                      
                             
                                
                            }else{
                                alert('Error de conexion');
                                
                            }
                                
                        }else{
                            if(r>0){
                                $('#nextov').val(ovn);
                                //table = $('#tablaprods').DataTable();
                                //table.clear().draw();
                                //$('#nreq').css('display','none');
                                //resetearReq();
                                $('#modal-conf33').modal('show');
                                if(ovn==1){
                                    $('#btn_authquit').trigger('click');
                                }else{
                                    $('#modal-ce').modal('hide');
                                    enabled_btn('#btn_authquit','Guardar y autorizar orden');
                                }

                                /*$.ajax({
                                    url:"ajax.php?c=compras&f=a_tienesR",
                                    type: 'POST',
                                    data:{

                                    },
                                    success: function(r){
                                        if(r>0){
                                            $('#tienesr').html('<div id="tienesr" class="col-xs-12 col-md-12" style="padding:0px 0px 0px 3px; margin-bottom:-20px;"><div class="alert alert-warning"><strong>Atencion!</strong> Tienes ('+r+') Requisiciones pendientes por autorizar</div></div>');
                                        }else{
                                            $('#tienesr').html('');
                                        }
                                    }
                                });*/
                            }else{
                                alert('Error de conexion');
                            }
                                $( "#modal-ce-uno" ).removeClass( "disabled" );
                                $( "#modal-ce-dos" ).removeClass( "disabled" );
                                $( "#modal-ce-tres" ).removeClass( "disabled" );
                        }                        

                        /*if(r>0){
                            table = $('#tablaprods').DataTable();
                            table.clear().draw();
                            $('#nreq').css('display','none');
                            resetearReq();
                            $('#modal-conf3').modal('show');
                        }else{
                            alert('Error de conexion');
                            enabled_btn('#btn_authquit','Guardar y autorizar orden');
                        }*/
                        //enabled_btn('#btn_savequit','Guardar y salir');
                    }
                });
            }

    }
              

    function loki(){
        idProv=$('#selpoc').val();
        ids=$('#idspoc').val();

        $.ajax({
            url:"ajax.php?c=ventas&f=a_loki",
            type: 'POST',
            dataType:'JSON',
            data:{idProv:idProv,ids:ids},
            success: function(r){
                if(r.success==1){
                    i=0;
                    $.each(r.data, function( k, v ) {
                        cant = $('#cantco_'+i).text();
                        subt=(cant*1)*(v.costo);
                        $('#unitco_'+i).text(v.costo);
                        $('#subtco_'+i).text(subt);
                        i++;
                    });
                    calculaOCExp();
                }else{
                    
                }
            }
        });
    }

    function resetearReq(){
        $('#nextov').val(0);
        $('tbody').empty();
        $('#c_solicitante').prop('disabled',false);
        $('#c_tipogasto').prop('disabled',false);
        $('#c_moneda').prop('disabled',false);
        $('#c_proveedores').prop('disabled',false);
        $('#c_almacen').prop('disabled',false);
        //$('#c_productos').html('<option value="0">Seleccione</option>'); 
        //$('#c_productos').select2();
        //$('#c_productos').prop('disabled',false);

        $('#moneda_tc').prop('disabled',false);
        $('#comment').prop('disabled', false);
        $('#btn_savequit').prop('disabled', false);
        $('#btn_authquit').prop('disabled', false);
        $('#moneda_tc').prop('disabled', false);
        $('#date_entrega').prop('disabled', false);
        $('#checkbox').prop('disabled', false);
        $('#btn_addProd').prop('disabled', false);
        $('#opciones_2').prop('disabled', false);

        $('#comment').val('');
        $('#date_entrega').val('');

        $('#ist').val(0);
        $('#it').val(0);
        $('#cadimps').val(0);

        $('#c_solicitante').find('option[value="0"]').prop('selected', true); 
        $('#c_solicitante').select2();
        $('#c_tipogasto').find('option[value="0"]').prop('selected', true); 
        $('#c_tipogasto').select2();
        $('#c_moneda').find('option[value="0"]').prop('selected', true); 
        $('#c_moneda').select2();

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
        $('#date_hoy').val('');
        $('#date_hoy').prop('disabled',false);
        $('#c_cliente').find('option[value="0"]').prop('selected', true); 
        $('#c_cliente').select2();
        $('#c_cliente').prop('disabled',false);

        $('#date_entrega').val('');
        $('#date_hoy').val('');
        $("#date_entrega").datepicker("setDate", new Date());
        $("#date_hoy").datepicker("setDate", new Date());
        $('#c_tipogasto').val("6").trigger("change");
        $('#c_moneda').val("1").trigger("change");

        enabled_btn('#btn_savequit','Guardar orden');
        enabled_btn('#btn_authquit','Guardar y autorizar orden');
        enabled_btn('#btn_addProd','Agregar producto');

    }

    function disabledReq(){
        $('#c_solicitante').prop('disabled',true);
        $('#c_tipogasto').prop('disabled',true);
        $('#c_moneda').prop('disabled',true);
        $('#c_proveedores').prop('disabled',true);
        $('#c_almacen').prop('disabled',true);
        $('#c_productos').html('<option value="0">Seleccione</option>'); 
        //$('#c_productos').remove();
        $('#moneda_tc').prop('disabled',true);
        $('#comment').prop('disabled', true);
        $('#btn_savequit').prop('disabled', true);
        $('#btn_authquit').prop('disabled', true);
        $('#moneda_tc').prop('disabled', true);
        $('#date_entrega').prop('disabled', true);
        $('#checkbox').prop('disabled', true);
        $('#btn_addProd').prop('disabled', true);
        $('#opciones_2').prop('disabled', true);

    }

    function nreq(){

        

        span = $('#ph').find('span').text();
        if($('#nreq').is(':visible') && span!='Visualizar orden de venta') {
            $('#modal-conf1').modal('show');
            $('#modal-btnconf1-uno').on('click',function(){
                table = $('#tablaprods').DataTable();
                table.clear().draw();
                $('#modal-conf1').modal('hide');
                $('#nreq').css('display','none');
                $('#nreq_load').css('display','block');
                $.ajax({
                    url:"ajax.php?c=ventas&f=a_nuevaov",
                    type: 'POST',
                    dataType:'JSON',
                    data:{ano:1},
                    success: function(r){
                        if(r.success==1){
                            resetearReq();
                            $('#txt_nreq').text(r.ov);
                            $('#nreq_load').css('display','none');
                            $('#userlog').text('<?php echo $username; ?>');
                            $('#iduserlog').text('<?php echo $iduser; ?>');
                            $('#ph').html('<span opt="1" class="label label-primary" style="cursor:pointer;">Nueva Nueva orden de venta</span>');
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
            table = $('#tablaprods').DataTable();
            table.clear().draw();
            $('#listareq').css('display','none');
            $('#modal-conf1').modal('hide');
            $('#nreq').css('display','none');
            $('#nreq_load').css('display','block');
            $.ajax({
                url:"ajax.php?c=ventas&f=a_nuevaov",
                type: 'POST',
                dataType:'JSON',
                data:{ano:1},
                success: function(r){
                    if(r.success==1){
                        resetearReq();
                        $('#txt_nreq').text(r.ov);
                        $('#nreq_load').css('display','none');
                        $('#userlog').text('<?php echo $username; ?>');
                        $('#iduserlog').text('<?php echo $iduser; ?>');
                        $('#ph').html('<span opt="1" class="label label-primary" style="cursor:pointer;">Nueva orden de venta</span>');
                        $('#nreq').css('display','block');
                    }else{
                        alert('No se pueden cargar cotizaciones');
                    }
                }
            });
        }
        
    }

    function editReq(idReq,mod){
            var config_descP = 1;
            var config_descG = 1;
            auxsiDescG = 0;

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
                data:{idReq:idReq,m:mod},
                success: function(r){
                    var descG = r.requisicion.descc; //%
                        if(descG > 0){
                            siDescG = descG;
                            //aplicaDesGlobal2(descG);
                        }else{
                           siDescG = 0; 
                           //alert('no tiene');
                        }
                    if(r.success==1){
                        resetearReq();
                        $('#ph').html('<span opt="2" class="label label-warning" style="cursor:pointer;">Modificar Orden de compra</span>');
                        $('#c_solicitante').prop('disabled',true);
                        $('#c_tipogasto').prop('disabled',true);
                        $('#c_moneda').prop('disabled',true);

                        if(mod==0){                            
                            $('#ph').html('<span opt="3" class="label label-default" style="cursor:pointer;">Visualizar orden de venta</span>');
                            $('#c_cliente').prop('disabled',true);
                            $('#date_hoy').prop('disabled',true);
                            $('#c_productos').prop('disabled',true);

                            disabledReq();
                        }

                        $('#userlog').text(r.requisicion.username);
                        $('#iduserlog').val(r.requisicion.idempleado);

                        $('#c_proveedores').prop('disabled',true);
                        $('#c_almacen').prop('disabled',true);
                        $('#c_cliente').prop('disabled',true);
                        $('#date_hoy').prop('disabled',true);
                        $('#txt_nreq').text(r.requisicion.idoc);
                        $('#nreq_load').css('display','none');

                        
                        $("#c_cliente").val(r.requisicion.id_cliente).trigger("change");
                        $("#c_solicitante").val(r.requisicion.id_solicito).trigger("change");
                        //$("#fecha_hoy").text(r.requisicion.fecha);
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

                        //table = $('#tablaprods').DataTable();



                        $.each(r.productos, function( k, v ) {

                            var desc = v.monto_desc;
                            var aux = '';

                            if(mod==0){
                                eliminProd='';
                                txtdis='disabled';
                            }else{
                                if(ctipodesc == 2 || ctipodesc == 3){
                                    if(desc > 0){
                                        var disabled = 'disabled';

                                    }else{
                                        var disabled = '';
                                    }                                    
                                    txtdis='';
                                    eliminProd="<button onclick='removeProdReq("+v.id+",\""+v.caracteristica+"\");' id='btn_addProd' class='btn btn-sm btn-danger' type='button' style='height:26px;'>Quitar</button>";
                                    //descProd="<button onclick='descProdReq("+v.id+",\""+v.caracteristica+"\",\""+v.nomprod+"\");' id='btn_descProd' class='btn btn-sm btn-info' type='button' style='height:26px;' "+disabled+">Descuento</button>";                                
                                    var btndescPE = "<button class='btn btn-sm btn-info btndesc' type='button' style='height:26px;' onclick='modalDescProdCot("+v.id+",\""+v.caracteristica+"\",\""+v.nomprod+"\");' "+disabled+">Descuento</button>";

                                }else{
                                    txtdis='';
                                    eliminProd="<button onclick='removeProdReq("+v.id+",\""+v.caracteristica+"\");' id='btn_addProd' class='btn btn-sm btn-danger' type='button' style='height:26px;'>Quitar</button>";
                                    btndescPE='';
                                }
                            }

                            
                            Rowdata="<tr ch='"+v.caracteristica+"' id='tr_"+v.id+"' oldp='"+v.precioorig+"' tipodesc='"+v.tipo_desc+"' montod='"+v.monto_desc+"' ><td>0</td>\
                            <td>"+v.codigo+"</td>\
                            <td>"+v.nomprod+"</td>\
                            <td>"+v.clave+"</td>\
                            <td id='valUnit'>"+v.adds+"</td>\
                            <td>\
                                <input id='ccc' style='width:60%;' onkeyup='refreshCants("+v.id+",\""+v.caracteristica+"\")' class='numeros' type='text' value='"+v.cantidad+"' "+txtdis+" />\
                            </td>\
                            <td class='valImporte' implimpio='"+v.costo+"' id='valImporte'>"+v.costo+"</td>\
                            <td>"+eliminProd+btndescPE+"</td></tr>";

                            table.row.add($(Rowdata)).draw();
                            refreshCants(v.id,v.caracteristica);

                        });
                        //recalcula();
                        if(mod==0){
                            $('select').prop('disabled','disabled');
                        }

                        //btn_savequit

                        $('#btn_savequit').text('Guardar cambios');
                        $('#btn_authquit').text('Guardar cambios y autorizar orden');
                        $('#txt_nreq').append('<input id="idrequi" type="hidden" value="'+idReq+'">');

                        $('.numerosunit').numeric();
                        $('.numeros').numeric();
                        $('#panel_tabla').css('display','block');
                        $('#nreq').css('display','block');

                    }else{
                        alert('No se pueden cargar cotizaciones');
                    }
                }
            });

        
    }

    

    function listareq(){

        span = $('#ph').find('span').text();
        if($('#nreq').is(':visible') && span!='Visualizar orden de venta') {
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

                    "columnDefs": [
    { "width": "8%", "targets": 0 },
    { "width": "8%", "targets": 1 },
    { "width": "13%", "targets": 2 },
    { "width": "13%", "targets": 3 },
    { "width": "11%", "targets": 4 },
    { "width": "11%", "targets": 5 },
    { "width": "11%", "targets": 6 },
    { "width": "14%", "targets": 7, "orderable": false, "sClass": "center" }
  ],

  "aoColumnDefs": [
{ "sClass": "center", "aTargets": 8 }
],



                    "aaSorting": [[0,'desc']],
                    ajax: {
                        beforeSend: function() {  }, //Show spinner
                        complete: function() { $('#listareq_load').css('display','none'); }, //Hide spinner
                        url:"ajax.php?c=ventas&f=a_listaRequisicionesCompra",
                        type: "POST",
                        data: function ( d )    {
                            //d.site = $("#nombredeusuario").val();
                        }  
                    }
                });
                $('#example_wrapper div:nth-child(2) div:nth-child(1)').css('overflow-y','auto');
                //$('#listareq_load').css('display','none');
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
                "columnDefs": [
    { "width": "8%", "targets": 0 },
    { "width": "8%", "targets": 1 },
    { "width": "13%", "targets": 2 },
    { "width": "13%", "targets": 3 },
    { "width": "11%", "targets": 4 },
    { "width": "11%", "targets": 5 },
    { "width": "11%", "targets": 6 },
    { "width": "16%", "targets": 7, "orderable": false, "sClass": "center" }
  ],
                "aaSorting": [[0,'desc']],
                ajax: {
                    beforeSend: function() {  }, //Show spinner
                    complete: function() { $('#listareq_load').css('display','none'); }, //Hide spinner
                    url:"ajax.php?c=ventas&f=a_listaRequisicionesCompra",
                    type: "POST",
                    data: function ( d )    {
                        //d.site = $("#nombredeusuario").val();
                    }  
                }
            });
            $('#example_wrapper div:nth-child(2) div:nth-child(1)').css('overflow-y','auto');
            $('#listareq').css('display','block');
        }
        
    }

    function restartOTRO(idProducto,cadcar,aux){

        if(aux == 1){// desc
            $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(5)').find('a').remove();
            firstoption = $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(5)').find("#prelis option:first").val();
            lastoption = $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(5)').find("#prelis option:last").val();
            //alert(lastoption);
            $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(5)').find('#prelis').val(firstoption);
            $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(5)').find('#prelis').val(firstoption).trigger('change');

            $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(5)').find('select option[value="'+lastoption+'"]').remove();
            $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(8)').find(".btndesc").attr('disabled', false);

            $('#tr_'+idProducto+'[ch="'+cadcar+'"]').attr('oldp', 0);
            $('#tr_'+idProducto+'[ch="'+cadcar+'"]').attr('tipodesc', 0);
            $('#tr_'+idProducto+'[ch="'+cadcar+'"]').attr('montod', 0);

        }else{// otro precio

            $("a[ch='"+cadcar+"']").remove();
            
            $("#tr_"+idProducto+"[ch='"+cadcar+"'] td:nth-child(5)").find('#prelis option:selected').val('OTRO');
            $("#tr_"+idProducto+"[ch='"+cadcar+"'] td:nth-child(5)").find('#prelis option:selected').text('Otro precio');
            $("#tr_"+idProducto+"[ch='"+cadcar+"'] td:nth-child(5)").find('#prelis').val( $("#prelis option:first").val() ).trigger('change');

        }
        
    }

    function restartOTRO2(idProducto,cadcar,aux){
        if(aux == 1){// desc
            $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(5)').find('a').remove();
            firstoption = $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(5)').find("#prelis option:first").val();
            lastoption = $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(5)').find("#prelis option:last").val();
            //alert(lastoption);
            $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(5)').find('#prelis').val(firstoption);
            $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(5)').find('#prelis').val(firstoption).trigger('change');

            $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(5)').find('select option[value="'+lastoption+'"]').remove();
            $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(8)').find(".btndesc").attr('disabled', false);

            $('#tr_'+idProducto+'[ch="'+cadcar+'"]').attr('oldp', 0);
            $('#tr_'+idProducto+'[ch="'+cadcar+'"]').attr('tipodesc', 0);
            $('#tr_'+idProducto+'[ch="'+cadcar+'"]').attr('montod', 0);

            if(siDescG > 0){
                aplicaDesGlobal2(siDescG);
                //alert(siDescG);
            }

        }else{// otro precio
            $("a[ch='"+cadcar+"']").remove();
            
            $("#tr_"+idProducto+"[ch='"+cadcar+"'] td:nth-child(5)").find('#prelis option:selected').val('OTRO');
            $("#tr_"+idProducto+"[ch='"+cadcar+"'] td:nth-child(5)").find('#prelis option:selected').text('Otro precio');
            $("#tr_"+idProducto+"[ch='"+cadcar+"'] td:nth-child(5)").find('#prelis').val( $("#prelis option:first").val() ).trigger('change');
        }
    }

    function refreshCants(idProducto,cadcar,aux){

        ax = $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valUnit").find('#prelis option:last').is(":selected");


        idprodmodal=idProducto;
        valActual = $("#tr_"+idProducto+"[ch='"+cadcar+"'] #ccc").val()*1;
        valUnit = $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valUnit").find('#prelis option:selected').val();

        tftf = valUnit.split('>');
        valUnit=tftf[0];
        if(valUnit!='OTRO' && tftf[1]=='x'){
            totx = $("#elinew_"+idprodmodal+"[ch='"+cadcar+"']").length;
            /*if(totx==0){
                $("#tr_"+idprodmodal+"[ch='"+cadcar+"']").find('#valUnit').append('<a id="elinew_'+idprodmodal+'" style="cursor:pointer;" onclick="restartOTRO('+idprodmodal+',\''+cadcar+'\');"> x </a>');
            }*/

            if(totx==0 && aux == 1){
                //$('#tr_'+idprodmodal).find('#valUnit').append('<a id="elinew_'+idprodmodal+'" style="cursor:pointer;" onclick="restartOTRO('+idprodmodal+','+cadcar+');"> x </a>');
                

                $("#tr_"+idprodmodal+"[ch='"+cadcar+"']").find('#valUnit').append('<a id="elinew_'+idprodmodal+'_'+cadcar+'" ch="'+cadcar+'" style="cursor:pointer;" onclick="restartOTRO('+idprodmodal+',\''+cadcar+'\','+aux+');"> x </a>');
            }

        }

        if(valUnit=='OTRO'){
            $('#modal-agrega').modal({
                backdrop: 'static',
                keyboard: false, 
                show: true
            });
            
            $('#modal-recep-uno').on('click',function(){

                pn = $('#precionuevo').val();
                if(pn==''){
                    pn=0;
                }
                if(pn==0){
                    alert('El precio no puede ser  igual o menor a 0');
                    return false;
                }
                $('#newprecio').val(pn);

                $("#tr_"+idprodmodal+"[ch='"+cadcar+"'] td:nth-child(5)").find('#prelis option:selected').val(pn+'>x');
                $("#tr_"+idprodmodal+"[ch='"+cadcar+"'] td:nth-child(5)").find('#prelis option:selected').text('$'+pn);
                $("#tr_"+idprodmodal+"[ch='"+cadcar+"']").find('#valUnit').append('<a id="elinew_'+idprodmodal+'" ch="'+cadcar+'" style="cursor:pointer;" onclick="restartOTRO('+idprodmodal+',\''+cadcar+'\');"> x </a>');
                $('#modal-agrega').modal('hide');

                refreshCants(idprodmodal,cadcar,aux);
                $('#modal-recep-uno').unbind();
            });


        }else{
            valUnit=valUnit*1;
        }
/*
        if(ax===false){
            $("#elinew_"+idprodmodal+"[ch='"+cadcar+"']").css('visibility','hidden');
        }else{
            $("#elinew_"+idprodmodal+"[ch='"+cadcar+"']").css('visibility','visible');
        }
        */

        valImporte = valActual*valUnit;
        $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valImporte").attr('implimpio',valImporte);
        $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valImporte").text(valImporte).currency();
        valcurren = $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valImporte").text();
        table = $('#tablaprods').DataTable();
       // table.cell('#tr_'+idProducto+' td:nth-child(9)').data(valcurren).draw();

        $("#tr_"+idProducto+"[ch='"+cadcar+"'] input").focus();

        recalcula();



    }

    function truncar(numero,decimales){
       return (Math.floor(100 * numero) / 100).toFixed(2);
    }


    function calculaOCExp(){

        var subtotal = 0;
var total = 0;
var productos = '';
var arrprods = new Array();
    i=0;
    $("#shala tr").each(function (index) 
    {   //console.log($("#tablita input:hidden"));
        
        console.log(this);
        idp = $(this).attr('id');
        chexist = $(this).attr('choc');
        spliidp = idp.split('_');
        idProducto = spliidp[1];
        cantidad = $(this).find('td#cantco_'+i).text();
        precio = $(this).find('td#unitco_'+i).text();

        if(cantidad > 0){
            subtotal = parseFloat(precio) * parseFloat(cantidad);
            productos +=idProducto+'-'+cantidad+'-'+precio+'-'+chexist+'/';

            arrprods.push(idProducto);
        }
        console.log(arrprods);
        total +=parseFloat(subtotal);
        subtotal = 0;
        i++;
    });



    $.ajax({
        url: 'ajax.php?c=compras&f=calculaPrecios',
        type: 'POST',
        dataType: 'json',
        data: {productos: productos},
    })
    .done(function(data) {
        console.log(data);
        $('#imps_exp').empty();
        $('.totalesDiv_exp').empty();

        $('#imps_exp').append('<div class="row">'+
                        '<div class="col-sm-6"><label>Subtotal:</label></div>'+
                        '<div class="col-sm-6"><label>$'+truncar(data.cargos.subtotal)+'</label></div>'+
                        '</div>');

        $.each(data.cargos.impuestosPorcentajes, function(index, val) {
            console.log(data.cargos.idprod);
            $('#imps_exp').append('<div class="row">'+
                        '<div class="col-sm-6"><label>'+index+':</label></div>'+
                        '<div class="col-sm-6"><label>$'+truncar(val)+'</label></div>'+
                        '</div>'); 
        });

        cadimps='';
        $.each(data.cargos.ppii, function(index, val) {

            cadimps+=index+"#"+val+'|';
        });
        $('#cadimps_exp').val(cadimps); 
        console.log(cadimps);

        
        $('#imps_exp').append('<div class="row">'+
                        '<div class="col-sm-6"><label>Total:</label></div>'+
                        '<div class="col-sm-6"><label>$'+truncar(data.cargos.total)+'</label></div>'+
                        '</div>');

        $('#ist_exp').val(data.cargos.subtotal);
        $('#it_exp').val(data.cargos.total);
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
    $('#totalOrden_exp').val(total);
    $('#totalOrdenLable_exp').text(total);

    }

    function recalcula(){

        var subtotal = 0;
        var total = 0;
        var productos = '';
        var arrprods = new Array();

    $("#filasprods tr").each(function (index) 
    {   //console.log($("#tablita input:hidden"));
        
        //contador++;
        idp = $(this).attr('id');
        chexist = $(this).attr('ch');
        spliidp = idp.split('_');
        idProducto = spliidp[1];
        cantidad = $(this).find('.numeros').val();
        //precio = $(this).find('#valUnit').find('input').val();
        precio = $(this).find('#prelis').val();
        if(cantidad > 0){
           
            subtotal = parseFloat(precio) * parseFloat(cantidad);
            productos +=idProducto+'-'+cantidad+'-'+precio+'-'+chexist+'/';

            arrprods.push(idProducto);
        }
        console.log(arrprods);
        total +=parseFloat(subtotal);
        subtotal = 0;
    });
    //alert(productos);

    var btndescGR = '';
    if(ctipodesc == 1 || ctipodesc == 3){
        btndescGR = '<div><button id ="btndescG" type="" onclick="descGoblal();">Descuento</button></div>';
    }

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

        $('#imps').append('<div class="row">'+btndescGR+
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

            cadimps+=index+"#"+val+'|';
        });
        $('#cadimps').val(cadimps); 
        console.log(cadimps);

        $('#total').val(data.cargos.total); //ch@
        $('#imps').append('<div class="row">'+
                        '<div class="col-sm-6"><label>Total:</label></div>'+
                        '<div class="col-sm-6"><label id="lbtotal">$'+truncar(data.cargos.total)+'</label></div>'+
                        '</div>');

        $('#ist').val(data.cargos.subtotal);
        $('#it').val(data.cargos.total);
        /// SI DESC GLOBAL 
            if(siDescG > 0 && auxsiDescG == 0){
                aplicaDesGlobal2(siDescG);
            }
        /// SI DESC GLOBAL FIN
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

   /* function refreshUnit(idProducto){
        valActual = $("#tr_"+idProducto+" #ccc").val()*1;
        valUnit = $("#tr_"+idProducto+" #valUnit").find('input').val();
        valImporte = valActual*valUnit;
        $("#tr_"+idProducto+" #valImporte").attr('implimpio',valImporte);
        $("#tr_"+idProducto+" #valImporte").text(valImporte).currency();
        valcurren = $("#tr_"+idProducto+" #valImporte").text();
        table = $('#tablaprods').DataTable();
        table.cell('#tr_'+idProducto+' td:nth-child(9)').data(valcurren).draw();

        $("#tr_"+idProducto+" input").focus();

        //tdTotals();
    }                
*/
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
        $(btn).html(text);
    }
    
    function removeProdReq(idProducto,cadcar){
        table = $('#tablaprods').DataTable();
        rowquit= $('#tr_'+idProducto+"[ch='"+cadcar+"']");
        table.row(rowquit).remove().draw();

        if ( table.data().length !== 0 ) {
            
        }else{
            $('#c_proveedores').prop('disabled',false);
            $('#c_almacen').prop('disabled',false);
            $('#c_cliente').prop('disabled',false);
            $('#c_moneda').prop('disabled',false);
        }

        recalcula();

/*        if(!$( "#filasprods tr").length ) {
           $('#panel_tabla').css('display','none');
        } 
        */
        //table.draw();
        
        //var table = $('#tablaprods').DataTable();
/*
        var table = $('#tablaprods').DataTable( {
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
            $( api.column(8).footer() ).html(
                '$'+pageTotal +' ( $'+ total +' total)'
            );
            }
        });

        table.row('#tr_'+idProducto).remove().draw();

        */
        //table.fnDeleteRow(table.$();

/*
        $('#tr_'+idProducto).remove();
        tdTotals();
        if(!$( "#filasprods tr").length ) {
           $('#panel_tabla').css('display','none');
        } 

        */
    }

    function savequit(){
        disabled_btn('#btn_addProd','Procesando...');

        /*
        $.ajax({
            url:"ajax.php?c=compras&f=a_nuevarequisicion",
            type: 'POST',
            data:{ano:1},
            success: function(r){
                console.log(r);
            }
        });
*/
    }

    function compras_nuevaRequisicion(){
        /* CHRIS - COMENTARIOS
        =============================*/
        
        //Esta funcion muestra la plantilla de captura de requisiciones
        //La peticion ajax crea un id_tmp de rquisicion
        //La peticion ajax busca el ultimo id de requisicion

        $.ajax({
            url:"ajax.php?c=compras&f=a_nuevarequisicion",
            type: 'POST',
            data:{ano:1},
            success: function(r){
                console.log(r);
            }
        });
    }
</script>