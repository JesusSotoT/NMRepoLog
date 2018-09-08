<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Facturacion masiva</title>
    <link rel="stylesheet" href="">
</head>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
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
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../../libraries/numeric/jquery.numeric.js"></script>
    <script src="js/inventarios.js"></script>
<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />
    <!-- Datepicker -->
    <link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>

    <!--Data Tables -->
    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <script src="../../libraries/dataTable/js/datatables.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
    <script src="js/bootstrap-datepicker.es.js" type="text/javascript"></script>

    <!-- morris -->
    <link rel="stylesheet" href="../../libraries/morris.js-0.5.1/morris.css">
    <script src="../../libraries/morris.js-0.5.1/raphael.min.js"></script>
    <script src="../../libraries/morris.js-0.5.1/morris.min.js"></script>
   
<body>

<div id="modal-conff" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-default">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Facturacion</h4>
            </div>
            <div class="modal-body">
                <div class="row">
<input type="hidden" id="hmprod" value="'+idProd+'">
                    <div class="col-sm-12" style="padding-top:10px;">
                    <p>Se va a generar una factura de las ventas seleccionadas, seleccione un RFC</p> 
                    </div>
                    <!--<div class="col-sm-12" style="padding-top:10px;">
                        <label class="col-sm-6 control-label text-left">Tipo de venta:</label>
                        <label class="col-sm-6 control-label text-left"><span id="tipv" class="label label-primary" style="cursor:pointer;">Ticket</span></label>
                    </div>
                    <div class="col-sm-12" style="padding-top:10px;">
                        <label class="col-sm-6 control-label text-left">Total:</label>
                        <label id="ttt" class="col-sm-6 control-label text-left">Total:</label>
                    </div>-->
                    <div id="sitienef" class="col-sm-12" style="padding-top:10px;">
                        <label class="col-sm-6 control-label text-left">RFC:</label>
                        <div class="col-sm-6">
                            <select id="rfc_">
                                <option value="0">XAXX010101000 (Generico)</option>
                            </select>
                        </div>
                    </div>
                    <!--<div class="col-sm-12" style="padding-top:10px;">
                        <label class="col-sm-6 control-label text-left">Forma de pago:</label>
                        <div class="col-sm-6">
                            <select id="fp_">
                                <?php foreach ($fp as $k => $v) { ?>
                                    <option value="<?php echo $v['claveSat']; ?>"><?php echo $v['nombre']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    
                    
                    <div class="col-sm-12" style="padding-top:10px;">
                        <label class="col-sm-6 control-label text-left">Observaciones:</label>
                        <div class="col-sm-6">
                            <textarea id="txtobs" class="form-control"></textarea>
                        </div>
                    </div>
                    -->
                </div>
            </div>
            <div id="footvent" class="modal-footer">
                <button id="modal-btnconff-uno" type="button" class="btn btn-default">Continuar</button> 
                <button id="modal-btnconff-dos" type="button" class="btn btn-default">Cancelar</button>
            </div>
        </div>
    </div> 
</div>  

<div id="modal-fm" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-default">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Facturacion</h4>
            </div>
            <div class="modal-body">
                <p>Se va a generar una factura a publico en general de las ventas seleccionadas, ¿Desea continuar?</p> 


            </div>
            <div id="footvent2" class="modal-footer">
                <button id="modal-btnfm-uno" type="button" class="btn btn-default">Continuar</button> 
                <button id="modal-btnfm-dos" type="button" class="btn btn-default">Cancelar</button>
            </div>
        </div>
    </div> 
</div>

<br> 
<div class="container well" id="divfiltro">
    <div class="row">
        <div class="col-xs-12 col-md-12">
           <h3>Facturacion masiva</h3>
        </div>
    </div>
    <div class="row col-md-12">
        <div class="row">
            <div class="col-sm-6">
                <div class="row">&nbsp;</div>
                <label>Rango de Fechas Desde</label><br>
                <div  id="datetimepicker1" class="input-group date">
                    <input id="desde" class="form-control" type="text" placeholder="Desde">
                    <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                    </span> 
                </div>
                <div class="row">&nbsp;</div>

               
            </div>
            <div class="col-sm-6">
                <div class="row">&nbsp;</div>
                <label>Hasta</label>
                <div id="datetimepicker2" class="input-group date">
                    <input id="hasta" class="form-control" type="text" placeholder="Hasta">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>    
                </div>
                <div class="row">&nbsp;</div>
               
            </div>
              
                
                <div class="row">&nbsp;</div>
                <div class="row">&nbsp;</div>
                <div class="col-sm-12">
                    <button class="btn btn-primary col-sm-2 col-md-offset-5" onclick="generarReporte();">Buscar</button><br> 
                </div>

        </div>
        <div class="row">
            <div id="listareq_load" class="row" style="display:none;font-size:12px;padding:10px 0px 0px 35px;">
                <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
            </div>

            <div id="listareq" class="row" style="margin:30px 0px 20px 15px;font-size:12px;display:block">
            <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th><input style="cursor:pointer;" name="select_all" value="1" id="example-select-all" type="checkbox" /></th>
                        <th>No. Venta.</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Tipo venta</th>
                        <th>Subtotal</th>
                        <th>Total</th>


                    </tr>
                </thead>
            </table>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <button class="btn btn-success col-sm-2 col-md-offset-5" onclick="facturar();">Facturar seleccionadas</button><br> 
            </div>
        </div>

    </div>

</div>



 
        

<script>
    $(document).ready(function() {
        $('#desde').datepicker({
                format: "yyyy-mm-dd",
                language: "es"
        });

        $('#hasta').datepicker({
                format: "yyyy-mm-dd",
                language: "es"
        });



    });

    function facturar(){
        var table = $('#example').DataTable();
        ids='';
        var idscliente =[];

        table.$('input[type="checkbox"]').each(function(){
         if(this.checked){
            valor= (this.value);
            spli=valor.split('-');
            ids+=spli[0]+',';
            idscliente.push(spli[1]);
         }else{
            
         }
        });

        console.log(idscliente);
        //return false;

        if(ids==''){
            alert('Tienes que seleccionar al menos una venta para facturar');
            return false;
        }
        difclientes=0;
        cliente=0;
        for(var n in idscliente) {
            cliente=idscliente[n];
            for(var o in idscliente) {
                if(idscliente[o]!=idscliente[n]){
                    difclientes++;
                    
                    break;
                }
            }
            if(difclientes>0){
                break;
            }
        }

        if(difclientes>0){
            $('#modal-fm').modal({
                backdrop: 'static',
                keyboard: false, 
                show: true
            });
            $('#modal-btnfm-uno').on('click',function(){
                $('#footvent2').html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
                idfactu=0;
                $.ajax({
                    url:"ajax.php?c=ventas&f=a_facturaMasiva",
                    type: 'POST',
                    data:{ids:ids,idfactu:idfactu},
                    dataType: 'json',
                    success: function(resp){
                        /* NUEVA FACTURACION Y RESPUESTA DE VENTA
                        ================================================ */
                        if (resp.success == 0 || resp.success == 5) {
                            if (resp.success == 0) {
                                alert('Ha ocurrido un error durante el proceso de facturación. Error ' + resp.error + ' - ' + resp.mensaje);
                                window.location.reload();
                            }
                        }

                        if (resp.success == 1){
                            azu = JSON.parse(resp.azurian);
                            console.log(azu);
                            uid = resp.datos.UUID;
                            correo = resp.correo;

                            $.ajax({
                                type: 'POST',
                                url: 'ajax.php?c=ventas&f=guardarFacturacionAll',
                                dataType: 'json',
                                data: {
                                    UUID: uid,
                                    noCertificadoSAT: resp.datos.noCertificadoSAT,
                                    selloCFD: resp.datos.selloCFD,
                                    selloSAT: resp.datos.selloSAT,
                                    FechaTimbrado: resp.datos.FechaTimbrado,
                                    idComprobante: resp.datos.idComprobante,
                                    idFact: idfactu,
                                    idVenta: resp.datos.idVenta,
                                    noCertificado: resp.datos.noCertificado,
                                    tipoComp: resp.datos.tipoComp,
                                    trackId: resp.datos.trackId,
                                    monto: (resp.monto),
                                    cliente: 0,
                                    idRefact: ids,
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
                                            $('#modal-fm').modal('hide');

                                        },
                                        error: function() {
                                            alert('Error');
                                            window.location.reload();
                                        }
                                    });
                                alert('Has facturado correctamente');
                                window.location.reload();
                                 
                                },
                                error: function() {
                                    
                                }
                            });
                        }            
                    }
                });
            });
            $('#modal-btnfm-dos').on('click',function(){
                $('#modal-btnfm-uno').unbind();
                $('#modal-btnfm-dos').unbind();
                $('#modal-fm').modal('hide');
                
            });
        }else{
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
                    $('#modal-conff').modal({
                        backdrop: 'static',
                        keyboard: false, 
                        show: true
                    });
                    $('#modal-btnconff-uno').on('click',function(){

                        $('#footvent').html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
                        idfactu=$('#rfc_').val();
                        
                        $.ajax({
                            url:"ajax.php?c=ventas&f=a_facturaMasiva",
                            type: 'POST',
                            data:{ids:ids,idfactu:idfactu},
                            dataType: 'json',
                            success: function(resp){
                                /* NUEVA FACTURACION Y RESPUESTA DE VENTA
                                ================================================ */
                                if (resp.success == 0 || resp.success == 5) {
                                    if (resp.success == 0) {
                                        alert('Ha ocurrido un error durante el proceso de facturación. Error ' + resp.error + ' - ' + resp.mensaje);
                                        window.location.reload();
                                    }
                                }

                                if (resp.success == 1){
                                    azu = JSON.parse(resp.azurian);
                                    console.log(azu);
                                    uid = resp.datos.UUID;
                                    correo = resp.correo;

                                    $.ajax({
                                        type: 'POST',
                                        url: 'ajax.php?c=ventas&f=guardarFacturacionAll',
                                        dataType: 'json',
                                        data: {
                                            UUID: uid,
                                            noCertificadoSAT: resp.datos.noCertificadoSAT,
                                            selloCFD: resp.datos.selloCFD,
                                            selloSAT: resp.datos.selloSAT,
                                            FechaTimbrado: resp.datos.FechaTimbrado,
                                            idComprobante: resp.datos.idComprobante,
                                            idFact: idfactu,
                                            idVenta: resp.datos.idVenta,
                                            noCertificado: resp.datos.noCertificado,
                                            tipoComp: resp.datos.tipoComp,
                                            trackId: resp.datos.trackId,
                                            monto: (resp.monto),
                                            cliente: 0,
                                            idRefact: ids,
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
                                                    $('#modal-fm').modal('hide');

                                                },
                                                error: function() {
                                                    alert('Error');
                                                    window.location.reload();
                                                }
                                            });
                                        alert('Has facturado correctamente');
                                        window.location.reload();
                                         
                                        },
                                        error: function() {
                                            window.location.reload();
                                        }
                                    });
                                }            
                            }
                        });
                    });
                    $('#modal-btnconff-dos').on('click',function(){
                        $('#modal-btnconff-uno').unbind();
                        $('#modal-btnconff-dos').unbind();
                        $('#modal-conff').modal('hide');
                    });
                }
            });
        }
        return false;
        var r = confirm("Se va a realizar el proceso de facturacion, deseas continuar?");
        if(r == true){
        $('#modal-fm').modal({
            backdrop: 'static',
            keyboard: false, 
            show: true
        });
        $.ajax({
            url:"ajax.php?c=ventas&f=a_facturaMasiva",
            type: 'POST',
            data:{ids:ids},
            dataType: 'json',
            success: function(resp){
                /* NUEVA FACTURACION Y RESPUESTA DE VENTA
                ================================================ */
                if (resp.success == 0 || resp.success == 5) {
                    if (resp.success == 0) {
                        alert('Ha ocurrido un error durante el proceso de facturación. Error ' + resp.error + ' - ' + resp.mensaje);
                        $('#modal-fm').modal('hide');
                    }
                }

                if (resp.success == 1){
                    azu = JSON.parse(resp.azurian);
                    console.log(azu);
                    uid = resp.datos.UUID;
                    correo = resp.correo;

                    $.ajax({
                        type: 'POST',
                        url: 'ajax.php?c=ventas&f=guardarFacturacionAll',
                        dataType: 'json',
                        data: {
                            UUID: uid,
                            noCertificadoSAT: resp.datos.noCertificadoSAT,
                            selloCFD: resp.datos.selloCFD,
                            selloSAT: resp.datos.selloSAT,
                            FechaTimbrado: resp.datos.FechaTimbrado,
                            idComprobante: resp.datos.idComprobante,
                            idFact: resp.datos.idFact,
                            idVenta: resp.datos.idVenta,
                            noCertificado: resp.datos.noCertificado,
                            tipoComp: resp.datos.tipoComp,
                            trackId: resp.datos.trackId,
                            monto: (resp.monto),
                            cliente: 0,
                            idRefact: ids,
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
                                    $('#modal-fm').modal('hide');
                                },
                                error: function() {
                                    alert('Error');
                                    $('#modal-fm').modal('hide');
                                }
                            });
                        window.location.reload();
                        alert('Has facturado correctamente');
                            
                        },
                        error: function() {
                            
                        }
                    });
                }            
            }
        });
        }else{

        }
    }

    function generarReporte(){
        $('#grafica').css('display','none');
        desde = $('#desde').val();
        hasta = $('#hasta').val();

        if(desde==''){
            alert('La fecha de incio esta vacia');
            return false;
        }
        if(hasta==''){
            alert('La fecha de fin esta vacia');
            return false;
        }
$('#listareq_load').css('display','block');

        var table = $('#example').DataTable({
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
      'ajax': "ajax.php?c=ventas&f=a_listadoFacturas&fini="+desde+"&ffin="+hasta,
      'bDestroy': true,
      'columnDefs': [{
         'targets': 0,
         'searchable':false,
         'orderable':false,
         'className': 'dt-body-center',
         'render': function (data, type, full, meta){
             return '<input style="cursor:pointer;" type="checkbox" name="id[]" value="' 
                + $('<div/>').text(data).html() + '">';
         }
      }],
      'order': [1, 'desc']
   });

        $('#listareq_load').css('display','none');


        $('#example-select-all').on('click', function(){
          // Check/uncheck all checkboxes in the table
          var rows = table.rows({ 'search': 'applied' }).nodes();
          $('input[type="checkbox"]', rows).prop('checked', this.checked);
       });

       // Handle click on checkbox to set state of "Select all" control
       $('#example tbody').on('change', 'input[type="checkbox"]', function(){
          // If checkbox is not checked
          if(!this.checked){
             var el = $('#example-select-all').get(0);
             // If "Select all" control is checked and has 'indeterminate' property
             if(el && el.checked && ('indeterminate' in el)){
                // Set visual state of "Select all" control 
                // as 'indeterminate'
                el.indeterminate = true;
             }
          }
       });

/*
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
                url:"ajax.php?c=ventas&f=a_listadoFacturas&fini="+desde+"&ffin="+hasta,
                type: "POST",
                render: function (data, type, full, meta){
                     return '<input type="checkbox" name="id[]" value="'+$('<div/>').text(data).html() + '">';
                 }
            }
        });
                
*/
    }
</script>