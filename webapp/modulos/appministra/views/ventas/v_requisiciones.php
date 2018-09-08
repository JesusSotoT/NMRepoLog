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
                <p>¿Seguro que deseas inactivar esta cotizacion?</p>
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
                <h4 id="modal-label">Cotizacion guardada!</h4>
            </div>
            <div class="modal-body">
                <p>La cotizacion fue guardada exitosamente.</p>
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
                <p>Tienes una cotizacion sin guardar, ¿Deseas continuar sin guardar cambios?</p>
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
                <p>Tienes una cotizacion sin guardar, ¿Deseas continuar sin guardar cambios?</p>
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
            <input type="hidden" id="auxDescG" value="0"> 

        </div>



        <div class="row" style="margin-bottom:10px;">
            <button class="btn btn-default" type="button" onclick="nreq();">Nueva cotizacion</button>
            <button class="btn btn-default" type="submit" onclick="listareq();">Listado cotizaciones</button>
        </div>
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
                <div id="ph"  class="panel-heading"><span opt="1" class="label label-primary" style="cursor:pointer;">Nueva Cotizacion</span></div>
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
                        <label class="col-sm-2 control-label text-left">No. Cotizacion</label>
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
                                <option value="2">Ingreso</option>
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
                                    <select id="c_productos" disabled="disabled"  style="width:100%;">
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
                           <th class="no-sort" width="15%" align="right">&nbsp;</th>                           
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
                            <input id="cadenaCoti" type="hidden" value="">
                            <!--
                            <button id="btn_savequit" class="btn btn-sm btn-info pull-center" type="button" style="height:28px;" >Guardar y salirdddd</button> 
                        -->
                            <!--<button id="btn_imprimir" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-print"></span> Imprimir</button>-->
                            <button id="btn_vercli" onclick="vercomcli(0);" class="btn btn-sm btn-default pull-center" type="button" style="height:28px; visibility: hidden;">Comentarios Cliente</button>
                            <button id="btn_savequitCaja" class="btn btn-sm btn-primary pull-center" type="button" style="height:28px;">Generar para caja</button>
                            <button id="btn_savequit" class="btn btn-sm btn-success pull-center" type="button" style="height:28px;">Generar cotizacion</button>

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

// var desc config
var ctipodesc = clspp = clspc = clgp = clgc = pass = '';
var siDescG = 0;
var auxsiDescG = 0;
var auxnew = 1;

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
});
// var desc config fin

function redondeo(numero, decimales){
var flotante = parseFloat(numero);
var resultado = Math.round(flotante*Math.pow(10,decimales))/Math.pow(10,decimales);
return resultado;
}

    $(function() {

        listareq();
        $('#precionuevo').numeric();
        $('#date_entrega').datepicker({
                format: "yyyy-mm-dd",
                startDate:  "<?php echo $sd2; ?>",
                endDate: "<?php echo $ed2; ?>",
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
        

        $("#c_cliente").change(function() {
            idCliente=$(this).val()
            if(idCliente>0){
               // $("#c_productos").prop('disabled',false);
            }else{
                $("#c_productos").prop('disabled',true);
            }
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

        $("#btn_imprimir").click(function() {
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
            //Verifica si es edicion o update

            option = $('#ph span').attr('opt');

            
            id_req = $('#txt_nreq').text();
            idrequi = $('#idrequi').val();

            
            //disabled_btn('#btn_savequit','Procesando...');

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
            //alert('subtotal '+ist+' Total con des '+it+ 'tiene desc?'+auxDescG+ 'el descuento fue de '+desCantidadG+ 'que equivale a '+descG+ ' Precio sin desc '+total ); //ch@
            //return false;
  

            if(idsProductos==''){
                msg_error(1);
                enabled_btn('#btn_savequit','Generar cotizacion');
                return false;
            }else{
                console.log(idsProductos);
                $.ajax({
                    url:"ajax.php?c=ventas&f=a_guardarRequisicion",
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
                        iduserlog:iduserlog,
                        total:total,
                        monto_desc:descG,
                        descc:desCantidadG
                    },
                    success: function(r){ // retonrna id de cotizacion 
                        console.log(r);
                        
                        if(r>0){
                            imps=$('#imps').html();

                            $.ajax({
                                url:"ajax.php?c=ventas&f=a_checkNoti",
                                type: 'POST',
                                data:{
                                    idOc:r
                                },
                                success: function(r){
                                    console.log(r);
                                }
                            });
                            $.ajax({
                                url:"ajax.php?c=ventas&f=a_enviarCotizacion2",
                                type: 'POST',
                                data:{
                                    idCoti:r,
                                    imps:imps,
                                    op:1,
                                    print:0,
                                    tipo:'Cotizacion'

                                },
                                success: function(r){
                         
                                }
                            });
                            /*
                            $.ajax({
                                url:"ajax.php?c=ventas&f=a_enviarCotizacion2",
                                type: 'POST',
                                data:{
                                    idCoti:r,
                                    imps:imps,
                                    op:1,
                                    print:0

                                },
                                success: function(r){
                         
                                }
                            });

                            */
                            
                            table = $('#tablaprods').DataTable();
                            table.clear().draw();
                            $('#nreq').css('display','none');
                            resetearReq();
                            $('#modal-conf3').modal('show');
                        }else{
                            alert('Error de conexion');
                            enabled_btn('#btn_savequit','Generar cotizacion');
                        }
                        //enabled_btn('#btn_savequit','Guardar y salir');
                    }
                });
            }

        
        });

$("#btn_savequitCaja").click(function() {
            
            iduserlog = $('#iduserlog').val();
            //Verifica si es edicion o update

            option = 4;

            
            id_req = $('#txt_nreq').text();
            idrequi = $('#idrequi').val();

            
            //disabled_btn('#btn_savequit','Procesando...');

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
            //alert('subtotal '+ist+' Total con des '+it+ 'tiene desc?'+auxDescG+ 'el descuento fue de '+desCantidadG+ 'que equivale a '+descG+ ' Precio sin desc '+total ); //ch@
            //return false;
  

            if(idsProductos==''){
                msg_error(1);
                enabled_btn('#btn_savequit','Generar cotizacion');
                return false;
            }else{
                console.log(idsProductos);
                $.ajax({
                    url:"ajax.php?c=ventas&f=a_guardarRequisicionCaja",
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
                        iduserlog:iduserlog,
                        total:total,
                        monto_desc:descG,
                        descc:desCantidadG
                    },
                    success: function(r){ // retonrna id de cotizacion 
                        console.log(r);
                        
                        if(r>0){
                            imps=$('#imps').html();
                            table = $('#tablaprods').DataTable();
                            table.clear().draw();
                            $('#nreq').css('display','none');
                            resetearReq();
                            $('#modal-conf3').modal('show');
                        }else{
                            alert('Error de conexion');
                            enabled_btn('#btn_savequit','Generar cotizacion');
                        }
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

        $('#btn_addProdx').on( 'click', function () {
            
        } );

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
                if($("#tr_"+idProducto).length  && tienecar==0) {
                    valorig = $("#tr_"+idProducto+" input").val();
                    $("#tr_"+idProducto+" input").val((valorig*1)+1);
                    refreshCants(idProducto,0,0);
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
                        if(r.car!=''){ // caracteristicas
                            $('#divcar').html(r.car);
                            $('#modal-car').modal('show');
                            $('#modal-car-uno').on('click',function(){

                                cadcar = $('.carh').map(function() {
                                    aaa=$(this).val();
                                    return aaa;
                                }).get().join(',');
                                /*
                                1=>1,2=>4,3=>6,4=>8<select id="prelis" onchange="refreshCants(30,0)">
                                <option value="10>0">$10 Precio lista</option><option value="9.5>1">$9.5 Mayoreo</option><option value="9.9>2">$9.9 Menudeo</option><option value="OTRO>x">Otro precio</option>
                                */ //ch@
                                //alert(cadcar+''+r.adds);
                                if($("#tr_"+idProducto+"[ch='"+cadcar+"']").length) {
                                    valorig = $("#tr_"+idProducto+"[ch='"+cadcar+"'] input").val();
                                    $("#tr_"+idProducto+"[ch='"+cadcar+"'] input").val((valorig*1)+1);
                                    refreshCants(idProducto,cadcar,0);
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
                                $('#c_moneda').prop('disabled',true);
                                data_almacen=$('#data_almacen').html();
                                txt_proveedor=$('#c_proveedores option:selected').text();
                                txt_almacen=$('#c_almacen option:selected').text();

                                if(ctipodesc == 2 || ctipodesc == 3){
                                    var btndescP = "<button class='btn btn-sm btn-info btndesc' type='button' style='height:26px;' onclick='modalDescProdCot("+r.datos[0].id+",\""+cadcar+"\",\""+r.datos[0].descripcion_corta+"\");'>Descuento</button>";
                                }else{
                                    var btndescP =''; /// ELIMINAR
                                }
                                
                                    

                                var Rowdata = "<tr ch='"+cadcar+"' 'newp'='0' oldp='0' tipoDesc='0' montoD ='0' id='tr_"+r.datos[0].id+"'><td>0</td><td>"+r.datos[0].codigo+"</td><td style='cursor:pointer;' onclick='modalDescuento("+r.datos[0].id+",\""+cadcar+"\");'>"+r.datos[0].descripcion_corta+" "+cadcartxt+"</td><td>"+r.datos[0].clave+"</td><td id='valUnit'>"+r.adds+"</td><td><input style='width:60%;' onkeyup='refreshCants("+r.datos[0].id+",\""+cadcar+"\",1)' class='numeros' type='text' value='1'/></td><td class='valImporte' implimpio='"+r.datos[0].costo+"' id='valImporte'>"+r.datos[0].costo+"</td><td><button onclick='removeProdReq("+r.datos[0].id+",\""+cadcar+"\");' id='btn_addProd' class='btn btn-sm btn-danger' type='button' style='height:26px;'>Quitar</button>"+btndescP+"</td></tr>";
                                $('#modal-car').modal('hide');
                                table.row.add($(Rowdata)).draw();

                                /// agregar parametro a onclick
                                $('#tr_'+idProducto+'[ch="'+cadcar+'"] td:nth-child(5)').find('select').attr("onchange","refreshCants("+r.datos[0].id+",\""+cadcar+"\",0)");
                                //alert(cadcar);
                                //$("#prelis [ch="+cadcar+"] td:nth-child(5)").find('select').attr("onchange","refreshCants("+r.datos[0].id+",\""+cadcar+"\")");
                                /////////////////////////////////

                                $("#tr_"+r.datos[0].id+"[ch='"+cadcar+"']").find('#prelis').prop('disabled',true);
                                $('#panel_tabla').css('display','block');
                                $('.numeros').numeric();
                                enabled_btn('#btn_addProd','Agregar producto');
                                refreshCants(idProducto,cadcar,0);
                                $('#modal-car-uno').unbind();
                                $('#modal-car-dos').unbind();
                            });

                            $('#modal-car-dos').on('click',function(){
                                enabled_btn('#btn_addProd','Agregar producto');
                                $('#modal-car').modal('hide');
                                $('#modal-car-uno').unbind();
                                $('#modal-car-dos').unbind();
                            });
                        }else{ // sin caracteristica
                            $('#c_cliente').prop('disabled',true);
                            $('#c_proveedores').prop('disabled',true);
                            $('#c_almacen').prop('disabled',true);
                            $('#c_moneda').prop('disabled',true);
                            data_almacen=$('#data_almacen').html();
                            txt_proveedor=$('#c_proveedores option:selected').text();
                            txt_almacen=$('#c_almacen option:selected').text();

                            
                                //btndescP = '';  /// ELIMINAR

                            if(ctipodesc == 2 || ctipodesc == 3){
                                    var btndescP = "<button class='btn btn-sm btn-info btndesc' type='button' style='height:26px;' onclick='modalDescProdCot("+r.datos[0].id+",0,\""+r.datos[0].descripcion_corta+"\");'>Descuento</button>";                                    
                                }else{
                                    var btndescP =''; /// ELIMINAR
                                }

                            var Rowdata = "<tr ch='0' newp='0' oldp='0' tipoDesc='0' montoD='0' id='tr_"+r.datos[0].id+"'><td>0</td><td>"+r.datos[0].codigo+"</td><td style='cursor:pointer;' onclick='modalDescuento("+r.datos[0].id+",0);'>"+r.datos[0].descripcion_corta+"</td><td>"+r.datos[0].clave+"</td><td id='valUnit'>"+r.adds+"</td><td><input style='width:60%;' onkeyup='refreshCants("+r.datos[0].id+",0,1)' class='numeros' type='text' value='1'/></td><td class='valImporte' implimpio='"+r.datos[0].costo+"' id='valImporte'>"+r.datos[0].costo+"</td><td><button onclick='removeProdReq("+r.datos[0].id+",0);' id='btn_addProd' class='btn btn-sm btn-danger' type='button' style='height:26px;'>Quitar</button>"+btndescP+"</td></tr>";

                            table.row.add($(Rowdata)).draw();

                            
                            $('#panel_tabla').css('display','block');
                            $('.numeros').numeric();
                            enabled_btn('#btn_addProd','Agregar producto');
                            refreshCants(idProducto,0,0);
                            cadcarAux = 0;
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

        function imprimir2(req,modo){
            $('#btn_imprimir_'+req+'_').prop('disabled',true);
            $.ajax({
                url:"ajax.php?c=ventas&f=a_enviarCotizacion2",
                type: 'POST',
                data:{
                    idCoti:req,
                    modo:modo,
                    print:1,
                    tipo:'Cotizacion'

                },
                success: function(r){
                    window.open("../../modulos/cotizaciones/cotizacionesPdf/cotizacion_"+req+".pdf");
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
                        alert('No se puede inactivar esta cotizacion');
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

    function resetearReq(){
        $('#btn_vercli').css('visibility','hidden');

        $('tbody').empty();
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
        $('#c_solicitante').prop('disabled',false);
        $('#c_tipogasto').prop('disabled',false);
        $('#c_moneda').prop('disabled',false);
        $('#comment').prop('disabled',false);
        $('#date_entrega').prop('disabled',false);
        $('#date_hoy').prop('disabled',false);
        $('#comment').val('');
        $('#date_entrega').val('');
        $('#date_hoy').val('');
        $('#c_cliente').find('option[value="0"]').prop('selected', true); 
        $('#c_cliente').select2();
        $('#c_cliente').prop('disabled',false);

        $('#date_entrega').val('');
        $('#date_hoy').val('');
        $("#date_entrega").datepicker("setDate", new Date());
        $("#date_hoy").datepicker("setDate", new Date());
        $('#c_tipogasto').val("6").trigger("change");
        $('#c_moneda').val("1").trigger("change");
        enabled_btn('#btn_savequit','Generar cotizacion');
        enabled_btn('#btn_addProd','Agregar producto');

    }

    function nreq(){
        $('#btn_savequitCaja').css('visibility','visible');
        auxnew = 0;

        span = $('#ph').find('span').text();
        if($('#nreq').is(':visible') && span!='Visualizar cotizacion') {
            $('#modal-conf1').modal('show');
            $('#modal-btnconf1-uno').on('click',function(){
                table = $('#tablaprods').DataTable();
                table.clear().draw();
                $('#modal-conf1').modal('hide');
                $('#nreq').css('display','none');
                $('#nreq_load').css('display','block');
                $.ajax({
                    url:"ajax.php?c=ventas&f=a_nuevarequisicion",
                    type: 'POST',
                    dataType:'JSON',
                    data:{ano:1},
                    success: function(r){
                        if(r.success==1){
                            resetearReq();
                            $('#txt_nreq').text(r.requisicion);
                            $('#nreq_load').css('display','none');
                            $('#userlog').text('<?php echo $username; ?>');
                            $('#iduserlog').text('<?php echo $iduser; ?>');
                            $('#ph').html('<span opt="1" class="label label-primary" style="cursor:pointer;">Nueva Cotizacion</span>');
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
                url:"ajax.php?c=ventas&f=a_nuevarequisicion",
                type: 'POST',
                dataType:'JSON',
                data:{ano:1},
                success: function(r){
                    if(r.success==1){
                        resetearReq();
                        $('#txt_nreq').text(r.requisicion);
                        $('#nreq_load').css('display','none');
                        $('#userlog').text('<?php echo $username; ?>');
                        $('#iduserlog').text('<?php echo $iduser; ?>');
                        $('#ph').html('<span opt="1" class="label label-primary" style="cursor:pointer;">Nueva Cotizacion</span>');
                        $('#nreq').css('display','block');
                    }else{
                        alert('No se pueden cargar cotizaciones');
                    }
                }
            });
        }
        
    }

    function vercomcli(op){
        if(op==0){
        
            c=$('#cadenaCoti').val();
        }else{
        
            c=op;
        }
        p='c';
        window.open("../../../coti/index.php?c="+c+'&p='+p);
    }
    
    function editReq(idReq,mod){ //ch@
            $('#btn_savequitCaja').css('visibility','hidden');
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
                data:{idReq:idReq,m:1,pr:'req'},
                success: function(r){
                    var descG = r.requisicion.descc; //%
                        if(descG > 0){
                            siDescG = descG;
                            //aplicaDesGlobal2(descG);
                        }else{
                           siDescG = 0; 
                           //alert('no tiene');
                        }
                    console.log(r);
                    if(r.success==1){
                        resetearReq();
                        $('#ph').html('<span opt="2" class="label label-warning" style="cursor:pointer;">Modificar Cotizacion</span>');
                        if(mod==0){                            
                            $('#ph').html('<span opt="3" class="label label-default" style="cursor:pointer;">Visualizar cotizacion</span>');
                            $('#c_cliente').prop('disabled',true);
                            $('#date_hoy').prop('disabled',true);
                            $('#c_productos').prop('disabled',true);
                            disabledReq();
                        }

                        $('#userlog').text(r.requisicion.username);
                        $('#iduserlog').val(r.requisicion.idempleado);

           
                  
                        if(r.requisicion.cadenaCoti!=null){
                            $('#btn_vercli').css('visibility','visible');
                            $('#cadenaCoti').val(r.requisicion.cadenaCoti);
                        }

                        
                        $('#c_proveedores').prop('disabled',true);
                        $('#c_almacen').prop('disabled',true);
                        $('#txt_nreq').text(r.requisicion.id);
                        $('#nreq_load').css('display','none');

                        

                        
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

                        console.log(r.productos);
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

                            //alert(v.adds);
                            
                            Rowdata="<tr ch='"+v.caracteristica+"' id='tr_"+v.id+"' oldp='"+v.precioorig+"' tipodesc='"+v.tipo_desc+"' montod='"+v.monto_desc+"' ><td>0</td><td>"+v.codigo+"</td><td>"+v.nomprod+"</td><td>"+v.clave+"</td><td id='valUnit'>"+v.adds+"</td><td><input style='width:60%;' onkeyup='refreshCants("+v.id+",\""+v.caracteristica+"\",1)' class='numeros' type='text' value='"+v.cantidad+"'/></td><td class='valImporte' implimpio='"+v.costo+"' id='valImporte'>"+v.costo+"</td><td>"+eliminProd+" "+btndescPE+"</td></tr>";
                            table.row.add($(Rowdata)).draw();
                            refreshCants(v.id,v.caracteristica,0);
                            

                        });

                        $('#btn_savequit').text('Guardar cambios');
                        $('#txt_nreq').append('<input id="idrequi" type="hidden" value="'+idReq+'">');

                        $('.numeros').numeric();
                        $('#panel_tabla').css('display','block');
                        $('#nreq').css('display','block');


                        



                    }else{
                        alert('No se pueden cargar cotizaciones');
                    }
                        /// DESCUENTO GLOBAL
                        var total = r.requisicion.total;
                        //alert(total);
                        //recalcula();
                        /// DESCUENTO GLOBAL FIN
                }
            });

        
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
    

    function listareq(){

        span = $('#ph').find('span').text();
        if($('#nreq').is(':visible') && span!='Visualizar cotizacion') {
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
                    { "width": "16%", "targets": 6, "orderable": false, "sClass": "center" }
                  ],
                "aaSorting": [[0,'desc']],



                    "aaSorting": [[0,'desc']],
                    ajax: {
                        beforeSend: function() {  }, //Show spinner
                        complete: function() {  $('#listareq_load').css('display','none'); }, //Hide spinner
                        url:"ajax.php?c=ventas&f=a_listaRequisiciones",
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
                    { "width": "16%", "targets": 6, "orderable": false, "sClass": "center" }
                  ],
                "aaSorting": [[0,'desc']],
                ajax: {
                    beforeSend: function() {  }, //Show spinner
                    complete: function() { $('#listareq_load').css('display','none'); }, //Hide spinner
                    url:"ajax.php?c=ventas&f=a_listaRequisiciones",
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

    //var aux = 0;       
                
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

    function modalDescuento(idProducto,cadcar){
        /*
        $('#modalDescParcial').modal({
            backdrop: 'static',
            keyboard: false, 
            show: true
        });

        $('#encabezadoNombre').text( $("#tr_"+idProducto+"[ch='"+cadcar+"'] td:nth-child(3)").text() );
        $('#encabezadoPrecio').text('$'+$("#tr_"+idProducto+"[ch='"+cadcar+"'] td:nth-child(7)").text() );
        $('#encabezadoPrecioInput').val($("#tr_"+idProducto+"[ch='"+cadcar+"'] td:nth-child(7)").text());
        $('#xProParc').val(idProducto);
        */

    }

    

    function refreshCants(idProducto,cadcar,aux){
        //var aux = 0 //-> desc
        //aux = 0; // otro precio AGREGAR COMENT (ELIMINAR)
       
        //ax = $("#tr_"+idProducto+"[ch='"+cadcar+"']").find('#prelis option:last').is(":selected");
        ax = $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valUnit").find('#prelis option:last').is(":selected");


        idprodmodal=idProducto;
        valActual = $("#tr_"+idProducto+"[ch='"+cadcar+"'] input").val()*1;
        //valUnit = $("#tr_"+idProducto+"[ch='"+cadcar+"']").find('#prelis').val();
        valUnit = $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valUnit").find('#prelis option:selected').val();


        tftf = valUnit.split('>');
        valUnit=tftf[0];
        if(valUnit!='OTRO' && tftf[1]=='x'){
            //totx = $('#elinew_'+idprodmodal).length;
            totx = $("#elinew_"+idprodmodal+"[ch='"+cadcar+"']").length;
            
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

            /*
                $('#tr_'+idprodmodal+' td:nth-child(5)').find('#prelis option:selected').val(pn+'>x');
                $('#tr_'+idprodmodal+' td:nth-child(5)').find('#prelis option:selected').text('$'+pn);
                $('#tr_'+idprodmodal).find('#valUnit').append('<a id="elinew_'+idprodmodal+'_'+cadcar+'" style="cursor:pointer;" onclick="restartOTRO('+idprodmodal+',\''+cadcar+'\');"> x </a>');
                $('#modal-agrega').modal('hide');
            */
                $("#tr_"+idprodmodal+"[ch='"+cadcar+"'] td:nth-child(5)").find('#prelis option:selected').val(pn+'>x');
                $("#tr_"+idprodmodal+"[ch='"+cadcar+"'] td:nth-child(5)").find('#prelis option:selected').text('$'+pn);
                $("#tr_"+idprodmodal+"[ch='"+cadcar+"']").find('#valUnit').append('<a id="elinew_'+idprodmodal+'" ch="'+cadcar+'" style="cursor:pointer;" onclick="restartOTRO('+idprodmodal+',\''+cadcar+'\');"> x </a>');
                $('#modal-agrega').modal('hide');

                //refreshCants(idprodmodal,0,0);
                refreshCants(idprodmodal,cadcar,aux);
                $('#modal-recep-uno').unbind();
            });


        }else{
            valUnit=valUnit*1;
        }

    /*

        if(ax===false){
            $('#elinew_'+idprodmodal).css('visibility','hidden');
        }else{
            $('#elinew_'+idprodmodal).css('visibility','visible');
        }
*/
        valImporte = valActual*valUnit;

        $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valImporte").attr('implimpio',valImporte);
        $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valImporte").text(valImporte).currency();
        valcurren = $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valImporte").text();
        table = $('#tablaprods').DataTable();
        //table.cell('#tr_'+idProducto+' td:nth-child(9)').data(valcurren).draw();

        $("#tr_"+idProducto+"[ch='"+cadcar+"'] input").focus();

        recalcula();

    }  

    function recalcula(){

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
            precio = $(this).find('#prelis').val();
            //precio = $(this).find('#valUnit').text();
            if(cantidad > 0){
               
                subtotal = parseFloat(precio) * parseFloat(cantidad);
                productos +=idProducto+'-'+cantidad+'-'+precio+'/';


            }
        // alert(productos);
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
            async:false,
        })
        .done(function(data) {
            console.log(data);
            $('#imps').empty();
            $('.totalesDiv').empty();
            var subtotal = 0;
            subtotal = data.cargos.subtotal.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
            $('#subtotal').val(subtotal); //ch@
            $('#imps').append('<div class="row">'+btndescGR+                            
                            '<div class="col-sm-6"><label>Subtotal:</label></div>'+
                            '<div class="col-sm-6"><label>$'+subtotal+'</label></div>'+
                            '</div>');
            
            $.each(data.cargos.impuestosPorcentajes, function(index, val) {

                var valF = val.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'); //ch@

                $('#imps').append('<div class="row">'+
                            '<div class="col-sm-6"><label>'+index+':</label></div>'+
                            '<div class="col-sm-6"><label>$'+valF+'</label></div>'+
                            '</div>');   
            });

            cadimps='';
            $.each(data.cargos.ppii, function(index, val) {
                cadimps+=val+'|';
            });
            $('#cadimps').val(cadimps); 

            var total = 0;
            total = data.cargos.total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
            $('#total').val(total); //ch@
            $('#imps').append('<div class="row">'+
                            '<div class="col-sm-6"><label>Total:</label></div>'+
                            '<div class="col-sm-6"><label id="lbtotal">$'+total+'</label></div>'+
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
        $(btn).html(text);
    }
    ////////////// ch@ /////////////////////////
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
            if(descG != 0 || descG != ''){
                var descGF = descG.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
            }else{
                var descGF = 0;
            }

            if(total != 0 || total != ''){
                var totalF = total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
            }else{
                var totalF = 0;
            }

            $("#auxDescG").val(1); // auxiliar para saber si tiene descuento g 
            $("#imps .row:last-child").before('<div id="divdesc" class="row"><div class="col-sm-6"> <label> Descuento '+desCantidadG+'%</label> </div> <div class="col-sm-6"> <label> $'+descGF+' </label>  </div></div>');
            $("#lbtotal").text('$'+total);
            $("#btndescG").prop( "disabled", true );
            $("#btndescG").after('<a onclick="removeDescG();" style="cursor:pointer;">X</a>');
            $('#modalDescGlobal').modal('hide');
            $('#it').val(totalF);
        }else{
            var descG =  (desCantidadG * total) / 100;
            total = total - descG;
            if(descG != 0 || descG != ''){
                var descGF = descG.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
            }else{
                var descGF = 0;
            }

            if(total != 0 || total != ''){
                var totalF = total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
            }else{
                var totalF = 0;
            }


            $("#auxDescG").val(1); // auxiliar para saber si tiene descuento g 
            $("#imps .row:last-child").before('<div id="divdesc" class="row"><div class="col-sm-6"> <label> Descuento '+desCantidadG+'%</label> </div> <div class="col-sm-6"> <label> $'+descGF+' </label>  </div></div>');
            $("#lbtotal").text('$'+total);
            $("#btndescG").prop( "disabled", true );
            $("#btndescG").after('<a onclick="removeDescG();" style="cursor:pointer;">X</a>');
            $('#modalDescGlobal').modal('hide');
            $('#it').val(totalF);
        }
        
        
    }

    function aplicaDesGlobal2(desCantidadG){
        var total = $('#total').val()*1;
        //var desCantidadG = $("#desCantidadG").val()*1;
        var descG =  (desCantidadG * total) / 100;
        total = total - descG;

        if(descG != 0 || descG != ''){
                var descGF = descG.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
            }else{
                var descGF = 0;
            }
        if(total != 0 || total != ''){
                var totalF = total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
            }else{
                var totalF = 0;
            }

        $("#auxDescG").val(1); // auxiliar para saber si tiene descuento g 
        $("#imps .row:last-child").before('<div id="divdesc" class="row"><div class="col-sm-6"> <label> Descuento '+desCantidadG+'%</label> </div> <div class="col-sm-6"> <label> $'+descGF+' </label>  </div></div>');
        $("#lbtotal").text('$'+totalF);
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


    
    /// delete
    function descProdReq(idProducto,cadcar,nomprod){ //ch@
        $('#modalDescParcial').modal('show');
        $('#encabezadoNombre').text(nomprod);
        $('#tr_'+idProducto+"[ch='"+cadcar+"']").find('select').val(111).text('111');  
        //alert(nomprod+' '+cadcar);
    }
    /*
        function aplicaDesParcial(){
            var id = $('#xProParc').val();
            var cantidad = $('#desCantidad').val();
            var tipoDes = $('#tipoDescu').val();
            var pre = $('#encabezadoPrecioInput').val();

            if(parseFloat(cantidad) < 0){
                alert('La cantidad debe ser mayor a cero');
                return false;
            }

            if(tipoDes=='%'){
                if(parseFloat(cantidad) > 100 ){
                    alert('El descuento no puede ser mayor al 100%');
                    return false;
                }
            }
            if(tipoDes=='$'){
                if(parseFloat(cantidad) > parseFloat(pre)){
                    alert('El descuento no puede ser mayor al precio del producto');
                    return false;
                }
            } 


            //caja.mensaje('Procesando...');

            $.ajax({
                url: 'ajax.php?c=caja&f=cambiaCantidad',
                type: 'POST',
                dataType: 'json',
                data: {id: id,
                       cantidad : cantidad,
                       tipo : tipoDes,
                    },
            })
            .done(function(data) {
                
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
        }
    */



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

    ////////////// ch@  fin/////////////////////////
    
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