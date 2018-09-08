<head>
    <!-- Iconos font-awesome -->
    <link rel="stylesheet" href="../../libraries/font-awesome-4.7.0/css/font-awesome-4.7.min.css">
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

<div id="modal-conf4" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-warning">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Atencion!</h4>
            </div>
            <div class="modal-body">
                <p>¿Seguro que deseas eliminar esta requisicion?</p>
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
                <h4 id="modal-label">Orden guardada!</h4>
            </div>
            <div class="modal-body">
                <p>La orden de compra fue guardada exitosamente.</p>
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
                <p>Tienes una orden de compra sin guardar, ¿Deseas continuar sin guardar cambios?</p>
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
                <p>Tienes una orden de compra sin guardar, ¿Deseas continuar sin guardar cambios?</p>
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
    <!--
    <div id="error_1">
        <div class="col-xs-12 col-md-12" style="display:block; padding-bottom:10px;">
        <div class="alert alert-warning">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>Atencion!</strong> Tienes -->
            <?php // echo $treqs; ?>  <!-- <a onclick="listareq();" style="cursor:pointer;"><?php  // echo $rtext; ?> <!-- </a> 
            
            por autorizar
        </div>
        </div>
    </div>
-->
    
    <?php } ?>

    <div class="container well" style="padding:25px;">
        <input type="hidden" id="modCosto" value="<?php echo $modCosto; ?>">
        <div class="row" style="padding-bottom:20px;">
        <div id='tienesr'>
        <?php if($treqs>0) { ?>
            <div class="col-xs-12 col-md-12" style="padding:0px 0px 0px 3px; margin-bottom:-20px;">
                <div class="alert alert-warning">
                    <strong>Atencion!</strong> Tienes (<?php echo $treqs; ?>) Requisiciones pendientes por autorizar
                </div>
            </div>
        <?php } ?>
        </div>
            <div class="col-xs-12 col-md-12" style="padding:0px 0px 0px 3px;"><h3>Modulo Compras</h3></div>
            <input type="hidden" id="ist" value="0">
            <input type="hidden" id="it" value="0">
            <input type="hidden" id="cadimps" value="0">
        </div>


        <?php if($vv!=1){ ?>
        <div class="row" style="margin-bottom:10px;">
            <button class="btn btn-default" type="button" onclick="nreq();">Nueva orden de compra</button>
            <button class="btn btn-default" type="submit" onclick="listareq();">Listado requisiciones / ordenes de compra</button>
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
                        <th>No. Req.</th>
                        <th>Fecha</th>
                        <th>Proveedor</th>
                        <th>Solicitante</th>
                        <th>Fecha entrega</th>
                        <th>Almacen</th>
                        <th>Tipo de compra</th>
                        <th>Total</th>
                        <th>Prioridad</th>
                        <th>Estatus</th>
                        <th class="no-sort" style="text-align: center;">Modificar</th>

                    </tr>
                </thead>
            </table>
        </div>


        <div id="nreq" class="row" style="display:none;">
            <div class="panel panel-default">
                <div id="ph"  class="panel-heading"><span opt="1" class="label label-primary" style="cursor:pointer;">Nueva orden de compra</span>
                </div>
                <div class="panel-body" style="padding:15px 0 10px 0; font-size:12px;">
                    <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-2 control-label text-left">Usuario:</label>
                        <div class="col-sm-6" style="color:#096;">
                            <label id="userlog"><?php echo $username; ?></label>
                            <input type='hidden' id="iduserlog" value='<?php echo $iduser; ?>'>
                        </div>
                        <label class="col-sm-2 control-label text-left">Tipo de compra</label>
                        <div class="col-sm-2 text-left" style="color:#ff0000;">
                            <select id="s_tipo_compra" onchange="change_tipo_compra()" class="form-control" style="width:100%;">
                                <option value="1">Normal</option>
                                <option value="2">Directa</option>
                            </select>
                        </div>
                    </div>
                    </div>
                    <div class="col-sm-12" style="padding-top:10px;">
                    <div class="form-group">
                        <label class="col-sm-2 control-label text-left">No. Requisicion</label>
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
                        <label class="col-sm-2 control-label text-left urgent">Urgente</label>
                        <div class="col-sm-2 urgent" style="color:#ff0000;">
                            <input  type="radio" name="radiourgente" id="opciones_2" value="1">
                        </div>
                        <label class="col-sm-2 control-label text-left no_fact" style="display:none;">No. de Factura</label>
                        <div class="col-sm-2 no_fact" style="color:#ff0000; display:none;">
                            <input style="height:30px;width:100%" id="num_fact" type="text" class="form-control">
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
                                    <option area="<?php echo $v['nomarea']; ?>" value="<?php echo $v['idempleado']; ?>"><?php echo $v['nombre']; ?> (<?php echo $v['nomarea']; ?>)</option>
                                <?php } ?>
                            </select>
                        </div>
                        <label class="col-sm-2 control-label text-left">&nbsp;</label>
                        <div class="col-sm-2" style="color:#000;">
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
                                    <option tc="<?php echo $v['tc']; ?>" value="<?php echo $v['coin_id']; ?>"><?php echo $v['codigo']; ?></option>
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
                           <th width="5%" align="left">Almacen</th>
                           <th width="10%" align="left">Codigo</th>
                           <th width="30%" align="left">Descripcion</th>
                           <th width="10%" align="left">Unidad</th>
                           <th width="10%" align="left">Proveedor</th>
                           <th width="10%" align="left">$Unitario</th>
                           <th width="10%" align="left">Cantidad</th>
                           <th width="10" align="left">Importe</th>
                           <th class="no-sort" width="10%" align="right">&nbsp;</th>
                          </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="7" style="text-align:right">&nbsp;</th>
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
                            <button id="btn_authquit_2" class="btn btn-sm btn-success pull-center" type="button" style="height:28px; display:none;">Guardar y Recibir Orden</button>
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

<!-- Modificaciones RCA -->
<link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
<script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
<script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
<script src="../../libraries/export_print/buttons.html5.min.js"></script>
<script src="../../libraries/export_print/jszip.min.js"></script>

</body>

<script>
var table = '';
var disa=0;

function redondeo(numero, decimales){
var flotante = parseFloat(numero);
var resultado = Math.round(flotante*Math.pow(10,decimales))/Math.pow(10,decimales);
return resultado;
}

function change_tipo_compra(){
    if($("#s_tipo_compra").val() == 1){
        $(".urgent").show();
        $(".no_fact").hide();
        $("#btn_authquit").show();
        $("#btn_authquit_2").hide();
    } else {
        $(".no_fact").show();
        $(".urgent").hide();
        $("#btn_authquit").hide();
        $("#btn_authquit_2").show();
    }
}

    $(function() {
        vv='<?php echo $vv; ?>';
        if(vv==1){
            idoc='<?php echo $id_oc; ?>';
            $.ajax({
                url:"ajax.php?c=compras&f=a_change_idoc_idreq",
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
                                $( api.column(8).footer() ).html(
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
        $('#s_tipo_compra').select2();
        $('#c_tipogasto').select2();
        $('#c_area').select2();
        $('#c_almacen').select2();
        
        $('#c_moneda').select2();
        $('footer div').remove();

       //$('#c_productos').on("select2:selecting", function(e) { 
        
            //$('#btn_addProd').trigger('click');
        //});

        $( "#c_productos" ).change(function() {
            $('#btn_addProd').trigger('click');
        });


        $("#c_moneda").change(function() {
            if(disa==1){return false;}
            $('#c_proveedores').val($('#c_proveedores option:first-child').val()).trigger('change');
            idMoneda=$(this).val();
            tc=$('#c_moneda option:selected').attr('tc');
            if(idMoneda==1){
                $('#c_proveedores').prop('disabled',false);
                $('#moneda_tc').css('display','none');
            }else if(idMoneda>1){
                $('#c_proveedores').prop('disabled',false);
                $('#moneda_tc').val(tc);
                $('#moneda_tc').numeric();
                $('#moneda_tc').css('display','block');
            }else{
                $('#moneda_tc').css('display','none');
                $('#c_proveedores').prop('disabled',true);
            }
        });

        $("#c_proveedores").change(function() {
            if(disa==1){return false;}
            $("#c_productos").html('<option value="0">Seleccione</option>');
            $("#c_productos").select2();
            idProveedor=$(this).val()
            if(idProveedor>0){
                idmoneda=$('#c_moneda').val();
                $.ajax({
                    url:"ajax.php?c=compras&f=a_getProvProducto",
                    type: 'POST',
                    dataType: 'JSON',
                    data:{idProveedor:idProveedor,idmoneda:idmoneda},
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
                            alert('El proveedor seleccionado no tiene productos');
                        }
                    }
                });
            }else{
                $('#c_moneda').prop('disabled',false);
                $("#c_productos").html('<option value="0">Seleccione</option>');
                $("#c_productos").select2();
            }
        });


        $("#btn_imprimir").click(function() {
            option = $('#ph span').attr('opt');

            id_req = $('#txt_nreq').text();
            idrequi = $('#idrequi').val();

            solicitante=$('#c_solicitante').val();
            tipogasto=$('#c_tipogasto').val();
            moneda=$('#c_moneda').val();
            proveedor=$('#c_proveedores').val();

            obs=$('#comment').val();
            obs = obs.replace(/\r\n|\r|\n/g,"<br />");

            almacen=$('#c_almacen').val();


            fechahoy=$('#fechahoy').text();
            fechahoy=$('#date_hoy').val();
            fechaentrega=$('#date_entrega').val();
            

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
            }else if(proveedor==0 && deten==0){ 
                alert('Tienes que seleccionar un proveedor'); 
                deten=1; 
            }else if(almacen==0 && deten==0){ 
                alert('Tienes que seleccionar un almacen'); 
                deten=1; 
            }

            if(deten==1){
                enabled_btn('#btn_savequit','Generar requisicion');
                return false;
            }

            idsProductos = $('#filasprods tr').map(function() {
                cant = $(this).find('.numeros').val();
                ch = $(this).attr('ch');
                costoprod = $(this).find('#valUnit').find('input').val();

                trid = this.id;
                id = trid.split('tr_');

                if (typeof id[1] !== "undefined") {
                    id= id[1]+'>#'+cant+'>#'+ch+'>#'+costoprod;
                }
                
                return id;
            }).get().join(',# ');  

            console.log(idsProductos);


            if(idsProductos==''){
                msg_error(1);
                enabled_btn('#btn_imprimir','<span class="glyphicon glyphicon-print"></span> Imprimir');
                return false;
            }else{
                disabled_btn('#btn_imprimir','Procesando...');
                imps=$('#imps').html();
                
                $.ajax({
                    url:"ajax.php?c=compras&f=a_imprimir",
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
                        obs:obs,
                        imp:'req',
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
            proveedor=$('#c_proveedores').val();

            almacen=$('#c_almacen').val();


            fechahoy=$('#fechahoy').text();
            fechahoy=$('#date_hoy').val();
            fechaentrega=$('#date_entrega').val();
            

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

            ist=$('#ist').val();
            it=$('#it').val();
            cadimps=$('#cadimps').val();

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
                enabled_btn('#btn_savequit','Guardar orden');
                return false;
            }

            preciosvaliods=0;
            modCosto=$('#modCosto').val();

            idsProductos = $('#filasprods tr').map(function() {
                cant = $(this).find('.numeros').val();
                ch = $(this).attr('ch');
                costoprod = $(this).find('#valUnit').find('input').val();

                trid = this.id;
                id = trid.split('tr_');

                if (typeof id[1] !== "undefined") {
                    id= id[1]+'>#'+cant+'>#'+costoprod+'>#'+ch;
                }

                valOrigin = $(this).find("#valUnit").find('input').attr('unitorigin');
                valUnit = $(this).find("#valUnit").find('input').val();
                if(modCosto==0){
                    if( (valUnit*1)<(valOrigin*1) ){
                        preciosvaliods++;
                    }
                }
                
                return id;
            }).get().join(',# ');  

            console.log(idsProductos);
            //return false;

            if(preciosvaliods>0){
                enabled_btn('#btn_savequit','Guardar orden');
                alert('Tienes productos con precios menores al costo real');
                return false;
            }

            if(idsProductos==''){
                msg_error(1);
                enabled_btn('#btn_savequit','Guardar orden');
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
                        idactivo:3,
                        obs:obs,
                        ist:ist,
                        it:it,
                        cadimps:cadimps,
                        iduserlog:iduserlog
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
            proveedor=$('#c_proveedores').val();

            almacen=$('#c_almacen').val();

            obs=$('#comment').val();
            obs = obs.replace(/\r\n|\r|\n/g,"<br />");


            fechahoy=$('#fechahoy').text();
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
                enabled_btn('#btn_authquit','Guardar y autorizar orden');
                return false;
            }

            preciosvaliods=0;
            modCosto=$('#modCosto').val();
        
            idsProductos = $('#filasprods tr').map(function() {
                cant = $(this).find('.numeros').val();
                ch = $(this).attr('ch');
                costoprod = $(this).find('#valUnit').find('input').val();

                trid = this.id;
                id = trid.split('tr_');

                if (typeof id[1] !== "undefined") {
                    id= id[1]+'>#'+cant+'>#'+costoprod+'>#'+ch;
                }


                valOrigin = $(this).find("#valUnit").find('input').attr('unitorigin');
                valUnit = $(this).find("#valUnit").find('input').val();
                if(modCosto==0){
                    if( (valUnit*1)<(valOrigin*1) ){
                        preciosvaliods++;
                    }
                }
                
                return id;
            }).get().join(',# '); 
            //return false;
            if(preciosvaliods>0){
                enabled_btn('#btn_authquit','Guardar y autorizar orden');
                alert('Tienes productos con precios menores al costo real');
                return false;
            }

            if(idsProductos==''){
                msg_error(1);
                enabled_btn('#btn_authquit','Guardar y autorizar orden');
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
                                        print:0,
                                        tipo:'Orden de compra'

                                    },
                                    success: function(r){
                             
                                    }
                                });

                                
                                
                                table = $('#tablaprods').DataTable();
                                table.clear().draw();
                                $('#nreq').css('display','none');
                                resetearReq();
                                $('#modal-conf3').modal('show');
                                
                            }else{
                                alert('Error de conexion');
                                enabled_btn('#btn_authquit','Guardar y autorizar orden');
                            }
                        }else{
                            if(r>0){

                                imps=$('#imps').html();
                                $.ajax({
                                    url:"ajax.php?c=compras&f=a_enviarPedido2",
                                    type: 'POST',
                                    data:{
                                        idOc:r,
                                        imps:imps,
                                        op:9,
                                        print:0,
                                        tipo:'Orden de compra'

                                    },
                                    success: function(nada){
                             
                                    }
                                });


                                table = $('#tablaprods').DataTable();
                                table.clear().draw();
                                $('#nreq').css('display','none');
                                resetearReq();
                                $('#modal-conf3').modal('show');
                                $.ajax({
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
                                });
                            }else{
                                alert('Error de conexion');
                                enabled_btn('#btn_authquit','Guardar y autorizar orden');
                            }  
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

        
        });

        $("#btn_authquit_2").click(function() {
            iduserlog = $('#iduserlog').val();
            //total = $('#grantotal').val();

            //Verifica si es edicion o update

            option = $('#ph span').attr('opt');

            
            id_req = $('#txt_nreq').text();
            idrequi = $('#idrequi').val();

            
            disabled_btn('#btn_authquit_2','Procesando...');

            solicitante=$('#c_solicitante').val();
            tipogasto=$('#c_tipogasto').val();
            moneda=$('#c_moneda').val();
            proveedor=$('#c_proveedores').val();

            almacen=$('#c_almacen').val();

            tipo_compra=$('#s_tipo_compra').val();

            num_fact=$('#num_fact').val();

            obs=$('#comment').val();
            obs = obs.replace(/\r\n|\r|\n/g,"<br />");


            fechahoy=$('#fechahoy').text();
            fechahoy=$('#date_hoy').val();
            fechaentrega=$('#date_entrega').val();
            

            if(moneda==1){
                moneda_tc=0;
            }else{
                moneda_tc=$('#moneda_tc').val();  
            }
            
            var cxp = 0;
            if(confirm("¿Quuieres que se envia a saldo por pagar?")){
                cxp = 1;
            } else {
                cxp = 0;
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
                enabled_btn('#btn_authquit_2','Guardar y Recibir Orden');
                return false;
            }

            preciosvaliods=0;
            modCosto=$('#modCosto').val();
        
            idsProductos = $('#filasprods tr').map(function() {
                cant = $(this).find('.numeros').val();
                ch = $(this).attr('ch');
                costoprod = $(this).find('#valUnit').find('input').val();

                trid = this.id;
                id = trid.split('tr_');

                if (typeof id[1] !== "undefined") {
                    id= id[1]+'>#'+cant+'>#'+costoprod+'>#'+ch;
                }


                valOrigin = $(this).find("#valUnit").find('input').attr('unitorigin');
                valUnit = $(this).find("#valUnit").find('input').val();
                if(modCosto==0){
                    if( (valUnit*1)<(valOrigin*1) ){
                        preciosvaliods++;
                    }
                }
                
                return id;
            }).get().join(',# '); 
            //return false;
            if(preciosvaliods>0){
                enabled_btn('#btn_authquit_2','Guardar y Recibir Orden');
                alert('Tienes productos con precios menores al costo real');
                return false;
            }

            if(idsProductos==''){
                msg_error(1);
                enabled_btn('#btn_authquit_2','Guardar y Recibir Orden');
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
                        num_fact: num_fact,
                        tipo_compra: tipo_compra,
                        ist:ist,
                        cxp:cxp,
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
                                        print:0,
                                        tipo:'Orden de compra'

                                    },
                                    success: function(r){
                             
                                    }
                                });

                                
                                
                                table = $('#tablaprods').DataTable();
                                table.clear().draw();
                                $('#nreq').css('display','none');
                                resetearReq();
                                $('#modal-conf3').modal('show');
                                
                            }else{
                                alert('Error de conexion');
                                enabled_btn('#btn_authquit_2','Guardar y Recibir Orden');
                            }

                            if(tipo_compra == 2){

                            }
                        }else{
                            if(r>0){

                                imps=$('#imps').html();
                                $.ajax({
                                    url:"ajax.php?c=compras&f=a_enviarPedido2",
                                    type: 'POST',
                                    data:{
                                        idOc:r,
                                        imps:imps,
                                        op:9,
                                        print:0,
                                        tipo:'Orden de compra'

                                    },
                                    success: function(nada){
                             
                                    }
                                });


                                table = $('#tablaprods').DataTable();
                                table.clear().draw();
                                $('#nreq').css('display','none');
                                resetearReq();
                                $('#modal-conf3').modal('show');
                                $.ajax({
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
                                });
                            }else{
                                alert('Error de conexion');
                                enabled_btn('#btn_authquit_2','Guardar y Recibir Orden');
                            }  
                        }                        

                        /*if(r>0){
                            table = $('#tablaprods').DataTable();
                            table.clear().draw();
                            $('#nreq').css('display','none');
                            resetearReq();
                            $('#modal-conf3').modal('show');
                        }else{
                            alert('Error de conexion');
                            enabled_btn('#btn_authquit_2','Guardar y autorizar orden');
                        }*/
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
            alm = $('#c_almacen').val();
            if(alm==0){
                alert('Seleccione un almacen');
                return false;
            }
            idProducto = $('#c_productos').val();
            tienecar = $('#c_productos option:selected').attr('tc');
            if(idProducto>0){
                idProveedor = $('#c_proveedores').val();
                disabled_btn('#btn_addProd','Procesando...');
                if($("#tr_"+idProducto).length && tienecar==0) {
                    valorig = $("#tr_"+idProducto+" #ccc").val()*1;
                    $("#tr_"+idProducto+" #ccc").val((valorig*1)+1);
                    refreshCants(idProducto,0);
                    enabled_btn('#btn_addProd','Agregar producto');
                    return false;
                } 

                $.ajax({
                url:"ajax.php?c=compras&f=a_addProductoReq",
                type: 'POST',
                dataType:'JSON',
                data:{idProducto:idProducto,idProveedor:idProveedor},
                success: function(r){
                    console.log(r);
                    if(r.success==1){

                        if(r.car!=''){
                            $('#divcar').html(r.car);
                            $('#modal-car').modal('show');
                            $('#alltable tr').find('input').numeric();
                            $('#modal-car-uno').on('click',function(){



                                ctr = $('#alltable tr').find('input').map(function() {
                                    ctrval=$(this).val();
                                    ctrid=$(this).attr('ctr');
                                    if(ctrval!=0){
                                        cadcarN = $('#ctr_'+ctrid).find('td').map(function() {
                                            ccvv=$(this).attr('ccvv');
                                            return ccvv;
                                            }).get().join(',');

                                        cadcarNT = $('#ctr_'+ctrid).find('td').map(function() {
                                            padre=$(this).attr('padre');
                                            hija=$(this).text();
                                            if(padre!='x.x.x.'){
                                            phija='( '+padre+': '+hija+' )';
                                            }
                                            return phija;
                                            }).get().join(',');

                                    }else{
                                        cadcarN='';
                                        cadcarNT='';
                                    }

                                    txt_almacen=$('#c_almacen option:selected').text();
                                    txt_proveedor=$('#c_proveedores option:selected').text();
                                    cadcar=cadcarN;
                                    cadcartxt=cadcarNT;

                                    if($("#tr_"+idProducto+"[ch='"+cadcar+"']").length) {
                                        valorig = $("#tr_"+idProducto+"[ch='"+cadcar+"'] input").val();
                                        $("#tr_"+idProducto+"[ch='"+cadcar+"'] input").val((valorig*1)+(ctrval*1));
                                        refreshCants(idProducto,cadcar);
                                        
                                    }else{

                                        formateado = redondeo((r.datos[0].costo)*(ctrval*1),2);
                                        if(ctrval!=0 && ctrval!=''){
                                       /* Rowdata = "<tr ch='"+cadcar+"' id='tr_"+r.datos[0].id+"'><td>0</td>\<td>"+txt_almacen+"</td><td>"+r.datos[0].codigo+"</td><td>"+r.datos[0].descripcion_corta+" "+cadcartxt+"</td><td>"+r.datos[0].clave+"</td><td>"+txt_proveedor+"</td><td id='valUnit'>"+r.datos[0].costo+"</td><td><input style='width:60%;' onkeyup='refreshCants("+r.datos[0].id+",\""+cadcar+"\")' class='numeros' type='text' value='"+ctrval+"'/></td><td class='valImporte' implimpio='"+(r.datos[0].costo)*(ctrval*1)+"' id='valImporte'>"+formateado+"</td><td>&nbsp;</td><td><button onclick='removeProdReq("+r.datos[0].id+",\""+cadcar+"\");' id='btn_addProd' class='btn btn-sm btn-danger' type='button' style='height:26px;'>Quitar</button></td></tr>";

*/
                                        
                                        Rowdata = "<tr ch='"+cadcar+"' id='tr_"+r.datos[0].id+"'><td>0</td>\<td>"+txt_almacen+"</td><td>"+r.datos[0].codigo+"</td><td>"+r.datos[0].descripcion_corta+" "+cadcartxt+"</td><td>"+r.datos[0].clave+"</td><td>"+txt_proveedor+"</td><td id='valUnit'><input type='text' value='"+r.datos[0].costo+"' onkeyup='refreshCants("+r.datos[0].id+",\""+cadcar+"\")' class='numerosunit' unitorigin='"+r.datos[0].costo+"'></td><td><input id='ccc' style='width:60%;' onkeyup='refreshCants("+r.datos[0].id+",\""+cadcar+"\")' class='numeros' type='text' value='"+ctrval+"'/></td><td class='valImporte' implimpio='"+(r.datos[0].costo)*(ctrval*1)+"' id='valImporte'>"+formateado+"</td><td><button onclick='removeProdReq("+r.datos[0].id+",\""+cadcar+"\");' id='btn_addProd' class='btn btn-sm btn-danger' type='button' style='height:26px;'>Quitar</button></td></tr>";



                                        }else{
                                            Rowdata='';
                                        }

                                    }




                                    //cadcarN=cadcarN+'##'+ctrval;
                                    return Rowdata;
                                }).get().join('');
                              
                                /*
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

                                $('#c_proveedores').prop('disabled',true);
                                $('#c_almacen').prop('disabled',true);
                                $('#c_moneda').prop('disabled',true);
                                data_almacen=$('#data_almacen').html();
                                txt_proveedor=$('#c_proveedores option:selected').text();
                                txt_almacen=$('#c_almacen option:selected').text();

                                
                                var Rowdata = "<tr ch='"+cadcar+"' id='tr_"+r.datos[0].id+"'><td>0</td>\<td>"+txt_almacen+"</td><td>"+r.datos[0].codigo+"</td><td>"+r.datos[0].descripcion_corta+" "+cadcartxt+"</td><td>"+r.datos[0].clave+"</td><td>"+txt_proveedor+"</td><td id='valUnit'><input type='text' value='"+r.datos[0].costo+"' onkeyup='refreshCants("+r.datos[0].id+",\""+cadcar+"\")' class='numerosunit'></td><td><input id='ccc' style='width:60%;' onkeyup='refreshCants("+r.datos[0].id+",\""+cadcar+"\")' class='numeros' type='text' value='1'/></td><td class='valImporte' implimpio='"+r.datos[0].costo+"' id='valImporte'>"+r.datos[0].costo+"</td><td><button onclick='removeProdReq("+r.datos[0].id+",\""+cadcar+"\");' id='btn_addProd' class='btn btn-sm btn-danger' type='button' style='height:26px;'>Quitar</button></td></tr>";

                                */
                                $('#modal-car').modal('hide');
                                table.rows.add($(ctr)).draw();


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
                            $('#c_proveedores').prop('disabled',true);
                            $('#c_almacen').prop('disabled',true);
                            $('#c_moneda').prop('disabled',true);
                            data_almacen=$('#data_almacen').html();
                            txt_proveedor=$('#c_proveedores option:selected').text();
                            txt_almacen=$('#c_almacen option:selected').text();

                            
                            var Rowdata = "<tr ch='0' id='tr_"+r.datos[0].id+"'><td>0</td>\<td>"+txt_almacen+"</td><td>"+r.datos[0].codigo+"</td><td>"+r.datos[0].descripcion_corta+"</td><td>"+r.datos[0].clave+"</td><td>"+txt_proveedor+"</td><td id='valUnit'><input type='text' value='"+r.datos[0].costo+"' onkeyup='refreshCants("+r.datos[0].id+",0)' class='numerosunit' unitorigin='"+r.datos[0].costo+"'></td><td><input id='ccc' style='width:60%;' onkeyup='refreshCants("+r.datos[0].id+",0)' class='numeros' type='text' value='1'/></td><td class='valImporte' implimpio='"+r.datos[0].costo+"' id='valImporte'>"+r.datos[0].costo+"</td><td><button onclick='removeProdReq("+r.datos[0].id+",0);' id='btn_addProd' class='btn btn-sm btn-danger' type='button' style='height:26px;'>Quitar</button></td></tr>";
     
                            table.row.add($(Rowdata)).draw();
                            $('#panel_tabla').css('display','block');
                            $('.numeros').numeric();
                            $('.numerosunit').numeric();
                            enabled_btn('#btn_addProd','Agregar producto');
                            refreshCants(idProducto,0);
                        }
                        
                    }else{
                        alert('Error al agregar producto, verifica que tu producto tenga proveedores y unidades de medida registrados');
                        enabled_btn('#btn_addProd','Agregar producto');
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
                url:"ajax.php?c=compras&f=a_enviarPedido2",
                type: 'POST',
                data:{
                    idOc:req,
                    modo:modo,
                    print:1,
                    tipo:'Orden de compra'

                },
                success: function(r){
                    window.open("../../modulos/cotizaciones/cotizacionesPdf/pedido_"+req+".pdf");
                    $('#btn_imprimir_'+req+'_').prop('disabled',false);
                }
            });
        }

    function imprimir_ticket(req,modo){
            $('#btn_imprimir_ticket_'+req+'_').prop('disabled',true);
            $.ajax({
                url:"ajax.php?c=compras&f=a_enviarPedido3",
                dataType:'html',
                type: 'POST',
                data:{
                    idOc:req,
                    modo:modo,
                    print:1,
                    tipo:'Orden de compra'

                },
                success: function(r){
                    console.log(r);
                    var ventana = window.open('', '_blank', 'width=207.874015748,height=10,leftmargin=0');
                    ventana.document.write(r);
                    setTimeout(function(){
                        ventana.print();
                    }, 1000);
                    $('#btn_imprimir_ticket_'+req+'_').prop('disabled',false);
                }
            });
        }

    function eliminaReq(idReq){


        $('#modal-conf4').modal('show').one('click', '#modal-btnconf4-uno', function(){
            $.ajax({
                url:"ajax.php?c=compras&f=a_eliminaRequisicion",
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
                        alert('No se puede eliminar esta requisicion');
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
        $('tbody').empty();
        $('#c_solicitante').prop('disabled',false);
        //$('#c_tipogasto').prop('disabled',false);
        $('#c_moneda').prop('disabled',false);
        $('#c_proveedores').prop('disabled',false);
        $('#c_almacen').prop('disabled',false);
        $('#c_productos').html('<option value="0">Seleccione</option>'); 
        $('#moneda_tc').prop('disabled',false);
        $('#comment').prop('disabled', false);
        $('#btn_savequit').prop('disabled', false);
        $('#btn_authquit').prop('disabled', false);
        $('#btn_authquit_2').prop('disabled', false);
        $('#moneda_tc').prop('disabled', false);
        $('#date_entrega').prop('disabled', false);
        $('#checkbox').prop('disabled', false);
        $('#btn_addProd').prop('disabled', false);
        $('#opciones_2').prop('disabled', false);


        $("#s_tipo_compra").val(1);
        $('#s_tipo_compra').prop('disabled',false);
        $("#num_fact").val('');
        $('#num_fact').prop('disabled',false);
        $('#s_tipo_compra').select2();

        $(".urgent").show();
        $(".no_fact").hide();

        $('#comment').val('');
        $('#date_entrega').val('');

        $('#ist').val(0);
        $('#it').val(0);
        $('#cadimps').val(0);

        $('#c_solicitante').find('option[value="0"]').prop('selected', true); 
        $('#c_solicitante').select2();
        //$('#c_tipogasto').find('option[value="0"]').prop('selected', true); 
        //$('#c_tipogasto').select2();
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
        $('#date_hoy').prop('disabled',false);
        $('#date_hoy').val('');

        $('#date_entrega').val('');
        $('#date_hoy').val('');
        $("#date_entrega").datepicker("setDate", new Date());
        $("#date_hoy").datepicker("setDate", new Date());
        $('#c_tipogasto').val("7").trigger("change");
        $('#c_moneda').val("1").trigger("change");
        enabled_btn('#btn_savequit','Guardar orden');
        enabled_btn('#btn_authquit','Guardar y autorizar orden');
        enabled_btn('#btn_authquit_2','Guardar y Recibir Orden');
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
        $('#btn_authquit_2').prop('disabled', true);
        $('#moneda_tc').prop('disabled', true);
        $('#date_entrega').prop('disabled', true);
        $('#date_hoy').prop('disabled', true);
        $('#checkbox').prop('disabled', true);
        $('#btn_addProd').prop('disabled', true);
        $('#opciones_2').prop('disabled', true);

    }

    function nreq(){
        span = $('#ph').find('span').text();
        if($('#nreq').is(':visible') && span!='Visualizar orden de compra') {
            console.log("if");
            $('#modal-conf1').modal('show');
            $('#modal-btnconf1-uno').on('click',function(){
                table = $('#tablaprods').DataTable();
                table.clear().draw();
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
                            $('#userlog').text('<?php echo $username; ?>');
                            $('#iduserlog').text('<?php echo $iduser; ?>');
                            $('#ph').html('<span opt="1" class="label label-primary" style="cursor:pointer;">Nueva orden de compra</span>');
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
            console.log("else");
            table = $('#tablaprods').DataTable();
            table.clear().draw();
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
                        $('#userlog').text('<?php echo $username; ?>');
                        $('#iduserlog').text('<?php echo $iduser; ?>');
                        $('#ph').html('<span opt="1" class="label label-primary" style="cursor:pointer;">Nueva orden de compra Requisicion</span>');
                        $('#nreq').css('display','block');
                    }else{
                        alert('No se pueden cargar requisiciones');
                    }
                }
            });
        }
        
    }

    function editReq(idReq,mod){

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
                     console.log("====> editReq");
                    console.log(r);
                    if(r.success==1){
                        disa=0;
                        resetearReq();
                        disa=1; 
                        $('#ph').html('<span opt="2" class="label label-warning" style="cursor:pointer;">Modificar Orden de compra</span>');
                        $('#c_solicitante').prop('disabled',true);
                        $('#c_tipogasto').prop('disabled',true);
                        $('#c_moneda').prop('disabled',true);
                        $('#date_hoy').prop('disabled',true);

                        if(mod==0){      

                            disa=1;                      
                            $('#ph').html('<span opt="3" class="label label-default" style="cursor:pointer;">Visualizar orden de compra</span>');
                            disabledReq();
                        }

                        $('#userlog').text(r.requisicion.username);
                        $('#iduserlog').val(r.requisicion.idempleado);


                        $('#c_almacen').prop('disabled',true);
                        
                        $('#txt_nreq').text(r.requisicion.id);
                        $('#nreq_load').css('display','none');

                        

                        $("#c_solicitante").val(r.requisicion.id_solicito).trigger("change");
                        $("#date_hoy").val(r.requisicion.fecha);
                        $("#date_entrega").val(r.requisicion.fecha_entrega);

                        if(r.requisicion.urgente==0){
                             $("#opciones_1").attr('checked', 'checked');
                        }else{
                             $("#opciones_2").attr('checked', 'checked');
                        }

                        $("#s_tipo_compra").val(r.requisicion.tipo_compra);
                        $('#s_tipo_compra').prop('disabled',true);
                        $("#num_fact").val(r.requisicion.num_factura);
                        $('#num_fact').prop('disabled',true);
                        $('#s_tipo_compra').select2();
                        if(r.requisicion.tipo_compra == 2){
                            $(".urgent").hide();
                            $(".no_fact").show();
                        } else {
                            $(".urgent").show();
                            $(".no_fact").hide();
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

                        if(mod==2){

                            if(r.requisicion.id_proveedor>0){
                                idProveedor=r.requisicion.id_proveedor;
                                idmoneda=$('#c_moneda').val();
                                $.ajax({
                                    url:"ajax.php?c=compras&f=a_getProvProducto",
                                    type: 'POST',
                                    dataType: 'JSON',
                                    data:{idProveedor:idProveedor,idmoneda:idmoneda},
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
                                            alert('El proveedor seleccionado no tiene productos');
                                        }
                                    }
                                });
                            }else{
                                $('#c_moneda').prop('disabled',false);
                                $("#c_productos").html('<option value="0">Seleccione</option>');
                                $("#c_productos").select2();
                            }
                        }

                        var comment = r.requisicion.observaciones.replace(/<br\s?\/?>/g,"\n");
                        $("#comment").val(comment);

                        txt_proveedor=$('#c_proveedores option:selected').text();
                        txt_almacen=$('#c_almacen option:selected').text();

                        //table = $('#tablaprods').DataTable();
                        $('#c_proveedores').prop('disabled',true);
                        $('#c_moneda').prop('disabled',true);


                        $.each(r.productos, function( k, v ) {
                            if(mod==0){
                                eliminProd='';
                                txtdis='disabled';
                            }else{
                                txtdis='';
                                eliminProd="<button onclick='removeProdReq("+v.id+",\""+v.caracteristica+"\");' id='btn_addProd' class='btn btn-sm btn-danger' type='button' style='height:26px;'>Quitar</button>";
                            }

                            if(v.caracteristica!='0'){
                                disa=' readonly="readonly" onclick=modCost('+v.id+'); ';
                            }else{
                                disa='';
                            }


                            Rowdata="<tr ch='"+v.caracteristica+"' id='tr_"+v.id+"'><td>0</td>\
                            <td>"+txt_almacen+"</td>\
                            <td>"+v.codigo+"</td>\
                            <td>"+v.nombre+"</td>\
                            <td>"+v.clave+"</td>\
                            <td>"+txt_proveedor+"</td>\
                            <td id='valUnit'>\
                            <input type='text' value='"+v.costo+"' onkeyup='refreshCants("+v.id+",\""+v.caracteristica+"\")' class='numerosunit' "+txtdis+" "+disa+" unitorigin='"+v.costo+"'>\
                            </td>\
                            <td>\
                                <input id='ccc' style='width:60%;' onkeyup='refreshCants("+v.id+",\""+v.caracteristica+"\")' class='numeros' type='text' value='"+v.cantidad+"' "+txtdis+" />\
                            </td>\
                            <td class='valImporte' implimpio='"+v.costo+"' id='valImporte'>"+v.costo+"</td>\
                            <td>"+eliminProd+"</td></tr>";
                            table.row.add($(Rowdata)).draw();
                            refreshCants(v.id,v.caracteristica);

                        });

                        recalcula();
                        //btn_savequit

                        $('#btn_savequit').text('Guardar cambios');
                        $('#btn_authquit').text('Guardar cambios y autorizar orden');
                        $('#btn_authquit_2').text('Guardar cambios y Recibir Orden');
                        $('#txt_nreq').append('<input id="idrequi" type="hidden" value="'+idReq+'">');

                        $('.numerosunit').numeric();
                        $('.numeros').numeric();
                        $('#panel_tabla').css('display','block');
                        $('#nreq').css('display','block');

                    }else{
                        alert('No se pueden cargar requisiciones');
                    }
                }
            });

        
    }

    function modCost(id){
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
                $('#newprecio').val(pn);

                $('tr[id="tr_'+id+'"').each(function (index) {
                    cadcar=$(this).attr('ch');
                    $(this).find('input.numerosunit').val(pn);
                    refreshCants(id,cadcar);
                }); 

                $('#modal-recep-uno').unbind();
                $('#modal-agrega').modal('hide');
            });
    }

    function listareq(){

        span = $('#ph').find('span').text();
        if($('#nreq').is(':visible') && span!='Visualizar orden de compra') {
            $('#modal-conf2').modal('show');
            $('#modal-btnconf2-uno').on('click',function(){
                $('#modal-conf2').modal('hide');
                $('#nreq').css('display','none');
                $('#listareq_load').css('display','block');
                var table = $('#example').DataTable();
                table.destroy();                
                $('#example').DataTable( {
                dom: 'Bfrtip',
                buttons: [ 
                    'pageLength', 'excel',
                ],
                language: {
                    buttons: {
                        pageLength: 'Mostrar %d filas'
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
                    "columnDefs": [
    { "width": "8%", "targets": 0 },
    { "width": "8%", "targets": 1 },
    { "width": "13%", "targets": 2 },
    { "width": "13%", "targets": 3 },
    { "width": "11%", "targets": 4 },
    { "width": "11%", "targets": 5 },
    { "width": "11%", "targets": 6 },
    { "width": "11%", "targets": 7 },
    { "width": "14%", "targets": 8, "orderable": false, "sClass": "center" }
  ],

  "aoColumnDefs": [
{ "sClass": "center", "aTargets": 8 }
],



                    "aaSorting": [[0,'desc']],
                    ajax: {
                        beforeSend: function() {  }, //Show spinner
                        complete: function() { $('#listareq_load').css('display','none'); }, //Hide spinner
                        url:"ajax.php?c=compras&f=a_listaRequisicionesCompra",
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
                dom: 'Bfrtip',
                buttons: [ 
                    'pageLength', 'excel',
                ],
                language: {
                    buttons: {
                        pageLength: 'Mostrar %d filas'
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
             
                "columnDefs": [
    { "width": "8%", "targets": 0 },
    { "width": "8%", "targets": 1 },
    { "width": "13%", "targets": 2 },
    { "width": "13%", "targets": 3 },
    { "width": "11%", "targets": 4 },
    { "width": "11%", "targets": 5 },
    { "width": "11%", "targets": 6 },
    { "width": "11%", "targets": 7 },
    { "width": "16%", "targets": 8, "orderable": false, "sClass": "center" }
  ],
                "aaSorting": [[0,'desc']],
                ajax: {
                    beforeSend: function() {  }, //Show spinner
                    complete: function() { $('#listareq_load').css('display','none'); }, //Hide spinner
                    url:"ajax.php?c=compras&f=a_listaRequisicionesCompra",
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
        precio = $(this).find('#valUnit').find('input').val();
        //precio = $(this).find('#prelis').val();
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
console.log(productos);
    
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

        fsbt= data.cargos.subtotal.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');

        $('#imps').append('<div class="row">'+
                        '<div class="col-sm-6"><label>Subtotal:</label></div>'+
                        '<div class="col-sm-6"><label>$'+fsbt+'</label></div>'+
                        '</div>');

        $.each(data.cargos.impuestosPorcentajes, function(index, val) {
            console.log(data.cargos.idprod);
            fval= val.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
            $('#imps').append('<div class="row">'+
                        '<div class="col-sm-6"><label>'+index+':</label></div>'+
                        '<div class="col-sm-6"><label>$'+fval+'</label></div>'+
                        '</div>'); 
        });

        cadimps='';
        $.each(data.cargos.ppii, function(index, val) {

            cadimps+=index+"#"+val+'|';
        });
        $('#cadimps').val(cadimps); 
        console.log(cadimps);

        ftt= data.cargos.total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
        $('#imps').append('<div class="row">'+
                        '<div class="col-sm-6"><label>Total:</label></div>'+
                        '<div class="col-sm-6"><label>$'+ftt+'</label></div>'+
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

    function refreshCants(idProducto,cadcar){
        modCosto=$('#modCosto').val();
        if(modCosto==0){
            valOrigin = $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valUnit").find('input').attr('unitorigin');
            valUnit = $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valUnit").find('input').val();
            
            if( (valUnit*1)<(valOrigin*1) ){
                $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valUnit").find('input').css('background-color','#ffc0c0');
                //$("#tr_"+idProducto+"[ch='"+cadcar+"'] #valUnit").find('input').val(valOrigin);
                //refreshCants(idProducto,cadcar);
                //alert('No puedes ingresar un valor menor al precio unitario registrado');
                //return false;
            }else{
                $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valUnit").find('input').css('background-color','#ffffff');
            }
        }
        valActual = $("#tr_"+idProducto+"[ch='"+cadcar+"'] #ccc").val()*1;
        valUnit = $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valUnit").find('input').val();
        valImporte = valActual*valUnit;
        $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valImporte").attr('implimpio',valImporte);
        $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valImporte").text(valImporte).currency();
        valcurren = $("#tr_"+idProducto+"[ch='"+cadcar+"'] #valImporte").text();
        table = $('#tablaprods').DataTable();
        //table.cell('#tr_'+idProducto+' td:nth-child(9)').data(valcurren).draw();

        $("#tr_"+idProducto+"[ch='"+cadcar+"'] input").focus();


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
        precio = $(this).find('#valUnit').find('input').val();
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

        fsbt= data.cargos.subtotal.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
        //alert(afg);
        
        $('#imps').append('<div class="row">'+
                        '<div class="col-sm-6"><label>Subtotal:</label></div>'+
                        '<div class="col-sm-6"><label id="p1">$'+fsbt+'</label></div>'+
                        '</div>');

        $.each(data.cargos.impuestosPorcentajes, function(index, val) {
            fval= val.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
            console.log(data.cargos.idprod);
            $('#imps').append('<div class="row">'+
                        '<div class="col-sm-6"><label>'+index+':</label></div>'+
                        '<div class="col-sm-6"><label>$'+fval+'</label></div>'+
                        '</div>'); 
        });

        cadimps='';
        console.log(data.cargos.ppii);
        $.each(data.cargos.ppii, function(index, val) {

            cadimps+=index+"#"+val+'|';
        });
        $('#cadimps').val(cadimps); 
        console.log(cadimps);

        ftt= data.cargos.total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
        $('#imps').append('<div class="row">'+
                        '<div class="col-sm-6"><label>Total:</label></div>'+
                        '<div class="col-sm-6"><label>$'+ftt+'</label></div>'+
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
        $(btn).html(text);
    }
    
    function removeProdReq(idProducto,cadcar){
        table = $('#tablaprods').DataTable();
        rowquit= $('#tr_'+idProducto+"[ch='"+cadcar+"']");
        table.row(rowquit).remove().draw();

        if ( table.data().length !== 0 ) {
            refreshCants(idProducto,0);
        }else{
            $('#c_proveedores').prop('disabled',false);
            $('#c_almacen').prop('disabled',false);
            $('#c_moneda').prop('disabled',false);
        }

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
    $(document).ready(function() {
        re='<?php echo $re; ?>';
        if(re==1){
            nreq();
        }
    });
</script>