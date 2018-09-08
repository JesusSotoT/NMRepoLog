<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reporte de Facturas</title>
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

<div id="modal-fm" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-default">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Generando factura</h4>
            </div>
            <div class="modal-body">
                <p>Procesando...</p> <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>

            </div>
        </div>
    </div> 
</div>

<br> 
<div class="container well" id="divfiltro">
    <div class="row">
        <div class="col-xs-12 col-md-12">
           <h3>Reporte de Facturas</h3>
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

                <div class="col-sm-6">
                    <div class="row">&nbsp;</div>
                    <label>Reporte</label><br>
                    <input style="cursor:pointer;" type="radio" name="rep" id="R1todas" value="1" checked="checked"> Todas<br>
                    <input style="cursor:pointer;" type="radio" name="rep" id="R1facturadas" value="2" > Ventas facturadas<br>
                    <input style="cursor:pointer;" type="radio" name="rep" id="R1nofacturadas" value="3" > Ventas no facturadas<br>
                    <input style="cursor:pointer;" type="radio" name="rep" id="R1nofacturadas" value="4" > Facturas canceladas<br>
              
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
                        <th width="1%">Venta.</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Subtotal</th>
                        <th>Total</th>
                        <th>Estatus</th>
                        <th>Tipo</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
            </table>
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
        table.$('input[type="checkbox"]').each(function(){
         if(this.checked){
            ids+=(this.value)+',';
         }else{
            
         }
        });

        if(ids==''){
            alert('Tienes que seleccionar al menos una venta para facturar');
            return false;
        }
        
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

    function generarReporte(){
        $('#grafica').css('display','none');
        desde = $('#desde').val();
        hasta = $('#hasta').val();
        radio = $('input[name=rep]:checked').val();

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
      'ajax': "ajax.php?c=ventas&f=a_listadoFacturasReporte&fini="+desde+"&ffin="+hasta+"&t="+radio,
      'bDestroy': true,
      'columnDefs': [{
         'searchable':false,
         'orderable':false,
         'className': 'dt-body-center',
         fnDrawCallback: function () {
            alert(8);
            var rows = this.fnGetData();
            console.log(rows);
            if ( rows.length === 0 ) {
            
            }
        }
      }],
      'order': [0, 'desc']
   });

        $('#listareq_load').css('display','none');

    }
</script>