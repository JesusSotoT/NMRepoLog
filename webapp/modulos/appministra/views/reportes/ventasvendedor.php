<style>
.rowhide {
    display: none;
}
</style>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ordenes de Compra</title>
    <link rel="stylesheet" href="">
</head>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="js/reportes.js"></script>
<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />
    <!-- Datepicker -->
    <link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>

 <!--dataTables 2 -->
    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <script src="../../libraries/dataTable/js/datatables.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>

<!--Button Print css -->
    <link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
<!--Button Print js -->
    <script src="../../libraries/dataTable/js/dataTables.buttons.min.js"></script>
    <script src="../../libraries/dataTable/js/buttons.print.min.js"></script>

<body> 
<br> 
<div class="container well" id="divfiltro">
    <div class="row">
        <div class="col-xs-12 col-md-12">
           <h3>Ventas</h3>
        </div>
    </div>
    <div class="row col-md-12" >                     
        <div class="panel panel-default ">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Rango de Fechas Desde</label><br>
                        <div id="datetimepicker1" class="input-group date">
                            <input id="desde" class="form-control" type="text" placeholder="Desde">
                            <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                            </span> 
                        </div>
                        <div id="datetimepicker2" class="input-group date">
                            <input id="hasta" class="form-control" type="text" placeholder="Hasta">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>    
                        </div>
                        <div>
                            <label>Vendedor</label><br>
                            <select id="vendedor" class="form-control" style="width: 100%;" multiple="multiple">
                                <option value="0" selected>-Todos-</option>                        
                            </select>
                        </div>
                        <div>
                            <label>Cliente</label><br>
                            <select id="cliente" class="form-control" style="width: 100%;" multiple="multiple">
                                <option value="0" selected>-Todos-</option>                        
                            </select>
                        </div>
                        
                    </div>
                    
                    <div class="col-sm-6">
                        <div>
                            <label>Periodo</label><br>
                            <select id="periodo" class="form-control" style="width: 100%;">
                                            <option value="0">Abierto</option>
                                            <option value="01">Enero</option>
                                            <option value="02">Febrero</option>
                                            <option value="03">Marzo</option>
                                            <option value="04">Abril</option>
                                            <option value="05">Mayo</option>
                                            <option value="06">Junio</option>
                                            <option value="07">Julio</option>
                                            <option value="08">Agosto</option>
                                            <option value="09">Septiembre</option>
                                            <option value="10">Octubre</option>
                                            <option value="11">Noviembre</option>
                                            <option value="12">Diciembre</option>                       
                            </select>
                        </div>
                        <div>
                            <label>Documento</label><br>
                            <select id="docuemnto" class="form-control" style="width: 100%;">
                                <option value="0" selected>-Todos-</option>
                                <option value="1" >-Factura-</option>                        
                                <option value="2" >-Ticket-</option>
                                <option value="3" >-Nota de Credito-</option>
                            </select>
                            <label >Status</label><br>
                            <input type="radio" name="sta" id="Rcobrada" value="1" checked="checked" >Cobrada<br>
                            <input type="radio" name="sta" id="Rparcial" value="2">Parcialmente Cobrada<br>
                            <input type="radio" name="sta" id="Rpendiente" value="3">Pendiente Cobro<br>
                            <button class="btn btn-default" onclick="procesarVV();">Procesar</button><br> 
                        </div>
                    </div> 

<!---->

                </div>
            </div>  
        </div>
    </div>
</div>      


<div class="container">
  <div class="container" id="divtable">
    <label >Ventas por Vendedor</label><br>
    <label >Periodo:</label> <label id="lbperiodo"></label><br>
    <label >Moneda: </label> <label id="lbperiodo">MXN</label>
  <br/>
        <table id="table_ventas" class="table table-striped table-bordered borderless" cellspacing="0" width="100%">
        <thead>
          <tr>  
            <th width="105px">Fecha</th>
            <th>Serie</th>
            <th>Folio</th>
            <th>Codigo Producto</th>
            <th>Concepto</th>
            <th>Cantidad</th>
            <th>$ Unitario</th>
            <th>Importe</th>
            <th>Iva</th>
            <th>Total</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot> 
            <th colspan="7"></th>
            <td align="right"> <b><label id="lbimporteVV"></label></b> </td>
            <td align="right"> <b><label id="lbivaVV"></label></b> </td>
            <td align="right"> <b><label id="lbtotalVV"></label></b> </td>
            <th></th>
        </tfoot>

      </table>
  </div>


</div>

    <div class="modal fade" id="modalMensajes" role="dialog" style="z-index:1051;" data-backdrop="static">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Espere un momento...</h4>
        </div>
        <div class="modal-body">
          <div class="alert alert-default">
            <div align="center"><label id="lblMensajeEstado"></label></div>
            <div align="center"><i class="fa fa-refresh fa-spin fa-5x fa-fw margin-bottom"></i>
                 <span class="sr-only">Loading...</span>
             </div>
        </div>
        </div>
      </div>
    </div>
  </div>
     
</body>
</html>
<script>

    $(document).ready(function() {

        $("#divtable").hide();

        $('#desde, #hasta').datepicker({ 
            format: "yyyy-mm-dd",
            "autoclose": true, 
            language: "es"
        }).attr('readonly','readonly');

    $("#vendedor, #cliente, #periodo, #docuemnto").select2();

    reloadselectVV();
    });

    function reloadselectVV(){
        $.ajax({
            url: 'ajax.php?c=reportes&f=selectVV',
            type: 'post',
            dataType: 'json',
        })
        .done(function(data) {
            $.each(data.vendedor, function(index, val) {
              $('#vendedor').append('<option value="'+val.idVendedor+'">'+val.vendedor+'</option>');  
            });
            $.each(data.cliente, function(index, val) {
              $('#cliente').append('<option value="'+val.id+'">'+val.nombrecliente+'</option>');  
            });
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
    }
    function reloadtableVV(desde,hasta,vendedor,cliente,documento,R1,periodoF){
        
        $.ajax({
            url: 'ajax.php?c=reportes&f=listventasVendedor',
            type: 'post',
            dataType: 'json',
            data:{desde:desde,hasta:hasta,vendedor:vendedor,cliente:cliente,documento:documento,R1:R1},
        })
        .done(function(data) {

            var table   = $('#table_ventas').DataTable( {
                                                            dom: 'Bfrtip',
                                                                buttons: [
                                                                {
                                                                    extend: 'print',
                                                                    title: $('h1').text(),
                                                                    customize: function ( win ) {
                                                                        $(win.document.body)
                                                                            .css( 'font-size', '10pt' )
                                                                            .prepend(
                                                                                '<h3>Ventas Por Vendedor</h3><br><h5> Periodo: <label>'+periodoF+'</label> <br> Moneda: <label>MXN</label></h5>'
                                                                            );                                                     
                                                                    }
                                                                }
                                                            ],
                                                            destroy: true,
                                                            searching: false,
                                                            paginate: false,
                                                            filter: false,
                                                            sort: false,
                                                            info: false,
                                                            language: {
                                                                buttons: {
                                                                        print: 'Imprimir'
                                                                },
                                                                search: "Buscar:",
                                                                lengthMenu:"Mostrar _MENU_ elementos",
                                                                zeroRecords: "No hay datos.",
                                                                infoEmpty: "No hay datos que mostrar.",
                                                                info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                                                                paginate: {
                                                                    first:      "Primero",
                                                                    previous:   "Anterior",
                                                                    next:       "Siguiente",
                                                                    last:       "Último"
                                                                }
                                                            }
                                    });
                table.clear().draw();
            var c ='';
            var e ='';
            var x ='';
            var y ='';
            var aux =1;
            var idVendedorAnt = 0;
            var importeT      = 0;
            var ivaT          = 0;
            var totalT        = 0;

                $.each(data.ventas, function(index, val) {
                    
                    var idVenta         = val.iden;
                    var idVendedor      = val.idVendedor;
                    var vendedor        = val.vendedor;
                    var nombrecliente   = val.nombrecliente;
                    var fecha           = val.fecha;
                    var folio           = val.folio;
                    //var codigo          = val.codigo;
                    //var nombre          = val.nombre;
                    //var cantidad        = val.cantidad;
                    //var costo           = val.costo;
                    var importe         = val.importe*1;
                    var imp             = val.imp*1;
                    var total           = val.total*1;
                    var statusF         = val.statusF;

                    // Suma  de Totales    
                    importeT    += importe;
                    ivaT        += imp;
                    totalT      += total;

                    if(idVendedor != idVendedorAnt){
                        e ='<tr>'+
                                    '<td><b>Agente:</b></td>'+
                                    '<td><b>'+idVendedor+'</b></td>'+
                                    '<td colspan="9"><b>'+vendedor+'</b></td>'+
                                    '<td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td>'+
                            '</tr>';
                        table.row.add($(e)).draw();                     
                    }

                    var btnU = '';
                    $.each(data.ventasD, function(index, valor) {
                        var id_oventa         = valor.id_oventa;
                        var aux               = valor.aux;

                        if(id_oventa == folio && aux == 1){
                           
                             btnU = '<button class="btn btn-default btn-xs" onclick="rowU(\''+folio+'\')"><i id="iU'+folio+'" class="glyphicon glyphicon-chevron-down"></i></button>';

                             return false;
                        }else{
                             btnU = '';
                        } 
                    }); 
                    var cantidadT = 0;
                    var conceptoT = '';
                    var codigoT = '';
                    var unitarioT = 0;
                    $.each(data.ventasD, function(index, value) {
                        var folioD       = value.id_oventa;
                        var codigoD      = value.codigo;
                        var nombreD      = value.nombre;
                        var cantidadD    = value.cantidad*1;
                        var costoD       = value.costo;
                        var importeD     = value.importe;
                        var iva          = value.iva;
                        var totalD       = value.total;
                    
                        if(folio == folioD){    
                            codigoT = codigoT + " " + codigoD;
                            conceptoT = conceptoT + " " + nombreD;                    
                            cantidadT += cantidadD; 
                        }                        
                    });
                    unitarioT = importe / cantidadT;
                    unitarioTF            = unitarioT.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');

                    c ='<tr>'+
                                '<td><b>Cliente:</b></td>'+
                                '<td colspan="10"><b>'+nombrecliente+'</b></td>'+
                                '<td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td>'+                                
                        '</tr>';
                    table.row.add($(c)).draw(); 
                    var fechaF = formatFechaL(fecha);

                    importeF            = importe.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
                    impF                = imp.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
                    totalF              = total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');

                    x ='<tr>'+
                                '<td>'+btnU+' '+fechaF+'</td>'+
                                '<td>A</td>'+
                                //'<td align = "center">'+folio+'</td>'+
                                '<td align = "center">'+idVenta+'</td>'+
                                '<td>'+codigoT+'</td>'+
                                '<td>'+conceptoT+'</td>'+
                                '<td align = "right">'+cantidadT+'</td>'+
                                '<td align = "right">$'+unitarioTF+'</td>'+
                                '<td align = "right">$'+importeF+'</td>'+
                                '<td align = "right">$'+impF+'</td>'+
                                '<td align = "right">$'+totalF+'</td>'+
                                '<td>'+statusF+'</td>'+
                        '</tr>';
                    
                    table.row.add($(x)).draw(); 

                    $.each(data.ventasD, function(index, value) {
                        var folioD       = value.id_oventa;
                        var codigoD      = value.codigo;
                        var nombreD      = value.nombre;
                        var cantidadD    = value.cantidad;
                        var costoD       = value.costo;
                        var importeD     = value.importe;
                        var iva          = value.iva;
                        var totalD       = value.total;
                                        
                        if(folio == folioD){                        
                            y ='<tr class="rowU'+folio+' rowhide borderless">'+
                                    '<td></td>'+
                                    '<td></td>'+
                                    '<td></td>'+
                                    '<td>'+codigoD+'</td>'+
                                    '<td>'+nombreD+'</td>'+
                                    '<td align = "right">'+cantidadD+'</td>'+
                                    '<td align = "right">$'+costoD+'</td>'+
                                    '<td align = "right">$'+importeD+'</td>'+
                                    '<td align = "right">$'+iva+'</td>'+
                                    '<td align = "right">$'+totalD+'</td>'+
                                    '<td></td>'+
                            '</tr>';
                            table.row.add($(y)).draw(); 
                        }                        
                    });
                    
                    idVendedorAnt      = val.idVendedor;

                });
            
            importeTF            = importeT.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
            ivaTF                = ivaT.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
            totalTF              = totalT.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');

            $("#lbimporteVV").text('$'+importeTF);
            $("#lbivaVV").text('$'+ivaTF);
            $("#lbtotalVV").text('$'+totalTF);

            $("#divtable").show();
            $("#modalMensajes").modal('hide');

        })
        .fail(function() {
            $("#divtable").show();
            $("#modalMensajes").modal('hide');
        })
        .always(function() {
            $("#divtable").show();
            $("#modalMensajes").modal('hide');
        });
    }
    function procesarVV(){
        var desde = $("#desde").val();
        var hasta = $("#hasta").val();
        var vendedor = $("#vendedor").val();
        var cliente = $("#cliente").val();
        var docuemnto = $("#docuemnto").val();

        var R1 = "";
        if ($('#Rcobrada').prop('checked')) {
            R1 = $('#Rcobrada').val();
          }
          if ($('#Rparcial').prop('checked')) {
            R1 = $('#Rparcial').val();
          }
          if ($('#Rpendiente').prop('checked')) {
            R1 = $('#Rpendiente').val();
          }

        //alert('desde: '+desde+' hasta: '+hasta+' vendedor: '+vendedor+'cliente: '+cliente+' docuemnto: '+docuemnto+'R1: '+R1);

        if(desde == '' && hasta != ''){
            alert('Debe Selecionar Desde que Fecha');
            return false;
        }
        if(hasta == '' && desde != ''){
            alert('Debe Selecionar Hasta que Fecha');
            return false;
        }

        $("#modalMensajes").modal('show');

        if(desde != ''){
           desdeF =  formatFecha(desde); 
        }
        if(hasta != ''){
            hastaF =  formatFecha(hasta);
        }
        
        
        if(desde == '' && hasta ==''){
            var periodoF = 'Sin Rango';
        }else{
            var periodoF = 'Del '+desdeF+' al '+hastaF;
        }

        var periodo = $("#periodo").val(); 
        if(periodo > 0){
            var yyyy = (new Date).getFullYear();
            desde = yyyy+'-'+periodo+'-01';
            desdeF = '01-'+periodo+'-'+yyyy;
            hasta = yyyy+'-'+periodo+'-31';
            hastaF = '31-'+periodo+'-'+yyyy;
            var periodoF = 'Del '+desdeF+' al '+hastaF;
        }
        $("#desde").val('');
        $("#hasta").val('');

        $("#lbperiodo").text(periodoF);

        reloadtableVV(desde,hasta,vendedor,cliente,docuemnto,R1,periodoF);
    }

</script>



