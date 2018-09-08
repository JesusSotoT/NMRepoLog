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
                <p>Tienes una requisicion sin guardar, ¿Deseas continuar sin guardar cambios?</p>
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
                <p>Tienes una requisicion sin guardar, ¿Deseas continuar sin guardar cambios?</p>
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
    <?php if($treqs>0) { ?>
    <div id="error_1">
        <div class="col-xs-12 col-md-12" style="display:block; padding-bottom:10px;">
        <div class="alert alert-warning">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>Atencion!</strong> Tienes <?php echo $treqs; ?> <a onclick="listareq();" style="cursor:pointer;"><?php echo $rtext; ?></a> por autorizar
        </div>
        </div>
    </div>
    <?php } ?>

    <div class="container well" style="padding:25px;">
        <input type="hidden" id="grantotal">
        <div class="row" style="padding-bottom:20px;">
            <div class="col-xs-12 col-md-12" style="padding:0px 0px 0px 3px;"><h3>Modulo Compras</h3></div>
        </div>

        

        
        <div class="row" style="margin-bottom:10px;">
            <button class="btn btn-default" type="button" onclick="nreq();">Nueva orden</button>
            <button class="btn btn-default" type="submit" onclick="listareq();">Listado requisiciones / ordenes</button>
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
                        <th>No. Req.</th>
                        <th>Fecha</th>
                        <th>Solicitante</th>
                        <th>Fecha entrega</th>
                        <th>Tipo gasto</th>
                        <th>Almacen</th>
                        <th>Total</th>
                        <th>Prioridad</th>
                        <th>Estatus</th>
                    </tr>
                </thead>
            </table>
        </div>


        <div id="nreq" class="row" style="display:none;">
            <div class="panel panel-default">
                <div class="panel-heading">Requisiciones</div>
                <div class="panel-body" style="padding:15px 0 10px 0; font-size:12px;">
                    <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-2 control-label text-left">No. Requisicion</label>
                        <div id="txt_nreq" class="col-sm-2" style="color:#ff0000;">
                            178
                        </div>
                        <label class="col-sm-2 control-label text-left">Fecha</label>
                        <div id="fechahoy" class="col-sm-2">
                            <?php echo date('Y-m-d'); ?>
                        </div>
                        <label class="col-sm-2 control-label text-left">Fecha entrega</label>
                        <div class="col-sm-2 text-left">
                            <input style="height:30px;width:87%" id="date_entrega" type="text" class="form-control">
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
                            <input  type="radio" name="radiourgente" id="opciones_1" value="1">
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
                           <th width="5%" align="right">Moneda</th>
                           <th class="no-sort" width="10%" align="right">&nbsp;</th>
                          </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="8" style="text-align:right">Total:</th>
                                <th colspan="3"></th>
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
                            <button id="btn_savequit" class="btn btn-sm btn-info pull-center" type="button" style="height:28px;" >Guardar y salir</button> 
                            <button id="btn_addProd" class="btn btn-sm btn-success pull-center" type="button" style="height:28px;">Generar orden</button>
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




</body>

<script>
var table = '';

function redondeo(numero, decimales){
var flotante = parseFloat(numero);
var resultado = Math.round(flotante*Math.pow(10,decimales))/Math.pow(10,decimales);
return resultado;
}

    $(function() {
        $('#date_entrega').datepicker({
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
                                $('#grantotal').val(tt);
                                $( api.column(8).footer() ).html(
                                    '$'+pt +' ( $'+ tt +' Gran total)'
                                    
                                );
                                }
                            });

        //Solucion al scroll-y
        $('#tablaprods_wrapper div:nth-child(2) div:nth-child(1)').css('overflow-y','auto');
        
        $('#tablaprods_info').css('margin-to','-10px');

        $('#c_productos').select2();
        $('#c_proveedores').select2({ width: '300px' });
        $('#c_solicitante').select2();
        $('#c_tipogasto').select2();
        $('#c_area').select2();
        
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



        $("#btn_savequit").click(function() {
            total = $('#grantotal').val();

            option = $('#ph span').attr('opt');

            
            id_req = $('#txt_nreq').text();
            idrequi = $('#idrequi').val();

            disabled_btn('#btn_savequit','Procesando...');

            solicitante=$('#c_solicitante').val();
            tipogasto=$('#c_tipogasto').val();
            moneda=$('#c_moneda').val();
            proveedor=$('#c_proveedores').val();


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
            }else if(moneda_tc=='' && deten==0){ 
                alert('Tienes que ingresar un tipo de cambio'); 
                deten=1; 
            }else if(proveedor==0 && deten==0){ 
                alert('Tienes que seleccionar un proveedor'); 
                deten=1; 
            }else if(total==0 && deten==0){ 
                alert('El total de tu orden de compra no puede ser 0'); 
                deten=1; 
            }

            if(deten==1){
                enabled_btn('#btn_savequit','Guardar y salir');
                return false;
            }

            idsProductos = $('#filasprods tr').map(function() {
                cant = $(this).find('.numeros').val();

                trid = this.id;
                id = trid.split('tr_');
                id= id[1]+'>'+cant;
                return id;
            }).get().join(', ');            


            if(idsProductos==''){
                msg_error(1);
                enabled_btn('#btn_savequit','Guardar y salir');
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
                        total:total
                    },
                    success: function(r){
                        if(r>0){
                            $('#nreq').css('display','none');
                            resetearReq();
                            $('#modal-conf3').modal('show');
                        }else{
                            alert('Error de conexion');
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
            
            idProducto = $('#c_productos').val();
            if(idProducto>0){
                idProveedor = $('#c_proveedores').val();
                disabled_btn('#btn_addProd','Procesando...');
                if($("#tr_"+idProducto).length ) {
                    valorig = $("#tr_"+idProducto+" input").val();
                    $("#tr_"+idProducto+" input").val((valorig*1)+1);
                    refreshCants(idProducto);

                    //table = $('#tablaprods').DataTable();
                    //table.draw();
                    //table.row('#tr_'+idProducto).cell('td:nth-child(9)').data((valorig*1)+1);
                    //console.log( table.cell().data() );


                    //tdTotals();
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
                        $('#c_proveedores').prop('disabled',true);
                        data_almacen=$('#data_almacen').html();
                        txt_proveedor=$('#c_proveedores option:selected').text();

                        
                        /*
                        table.row.add( [
                            '0',
                            data_almacen,
                            '30DCO001',
                            r.datos[0].descripcion_corta,
                            'Kilo',
                            txt_proveedor,
                            '45.05',
                            "<input onkeyup='refreshCants("+r.datos[0].id+")' class='numeros' type='text' value='1'/>",
                            '45.05',
                            'MXN',
                            "<button onclick='removeProdReq("+r.datos[0].id+");' id='btn_addProd' class='btn btn-sm btn-danger' type='button' style='height:26px;'>Quitar</button>"
                        ] ).draw().nodes().to$().attr( 'id', "tr_"+r.datos[0].id+"" );
                     
                     table.row(row).column(5).nodes().to$().addClass('testClass2');
*/


                        var Rowdata = "<tr id='tr_"+r.datos[0].id+"'><td>0</td>\<td>"+data_almacen+"</td><td>"+r.datos[0].codigo+"</td><td>"+r.datos[0].descripcion_corta+"</td><td> - </td><td>"+txt_proveedor+"</td><td id='valUnit'>"+r.datos[0].costo+"</td><td><input style='width:60%;' onkeyup='refreshCants("+r.datos[0].id+")' class='numeros' type='text' value='1'/></td><td class='valImporte' implimpio='"+r.datos[0].costo+"' id='valImporte'>"+r.datos[0].costo+"</td><td>"+r.datos[0].moneda+"</td><td><button onclick='removeProdReq("+r.datos[0].id+");' id='btn_addProd' class='btn btn-sm btn-danger' type='button' style='height:26px;'>Quitar</button></td></tr>";
 
                        table.row.add($(Rowdata)).draw();
                        $('#panel_tabla').css('display','block');
                     //console.log(i);

                     //$(i).eq(0).attr( 'id', "tr_"+r.datos[0].id+"");

                    //table.row(0).node().to$().attr("id", "aa");


/*
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

                        */
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
        $('#c_solicitante').find('option[value="0"]').prop('selected', true); 
        $('#c_solicitante').select2();
        $('#c_tipogasto').find('option[value="0"]').prop('selected', true); 
        $('#c_tipogasto').select2();
        $('#c_moneda').find('option[value="0"]').prop('selected', true); 
        $('#c_moneda').select2();
        $('#c_proveedores').find('option[value="0"]').prop('selected', true); 
        $('#c_proveedores').select2();
        $('#c_productos').html('<option value="0">Seleccione</option>'); 
        $('#c_productos').select2();
        $('#c_productos').prop('disabled',true);
        $('#moneda_tc').css('display','none');
        $('#moneda_tc').val('');
        $('#panel_tabla').css('display','none');
        enabled_btn('#btn_savequit','Guardar y salir');
        enabled_btn('#btn_addProd','Agregar producto');

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

    function listareq(){

        if($('#nreq').is(':visible')) {
            $('#modal-conf2').modal('show');
            $('#modal-btnconf2-uno').on('click',function(){
                $('#modal-conf2').modal('hide');
                $('#nreq').css('display','none');
                $('#listareq_load').css('display','block');
                var table = $('#example').DataTable();
                table.destroy();                
                $('#example').DataTable({
                    "aaSorting": [[0,'desc']],
                    ajax: {
                        beforeSend: function() {  }, //Show spinner
                        complete: function() { $('#listareq_load').css('display','none'); }, //Hide spinner
                        url:"ajax.php?c=compras&f=a_listaRequisiciones",
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
            $('#example').DataTable({
                "aaSorting": [[0,'desc']],
                ajax: {
                    beforeSend: function() {  }, //Show spinner
                    complete: function() { $('#listareq_load').css('display','none'); }, //Hide spinner
                    url:"ajax.php?c=compras&f=a_listaRequisiciones",
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

    function refreshCants(idProducto){
        valActual = $("#tr_"+idProducto+" input").val()*1;
        valUnit = $("#tr_"+idProducto+" #valUnit").text()*1;
        valImporte = valActual*valUnit;
        $("#tr_"+idProducto+" #valImporte").attr('implimpio',valImporte);
        $("#tr_"+idProducto+" #valImporte").text(valImporte).currency();
        valcurren = $("#tr_"+idProducto+" #valImporte").text();
        table = $('#tablaprods').DataTable();
        table.cell('#tr_'+idProducto+' td:nth-child(9)').data(valcurren).draw();

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
        table = $('#tablaprods').DataTable();
        table.row('#tr_'+idProducto).remove().draw();

        if ( table.data().length !== 0 ) {
            
        }else{
            $('#c_proveedores').prop('disabled',false);
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
</script>