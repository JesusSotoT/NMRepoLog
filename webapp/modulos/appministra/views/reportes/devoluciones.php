<style>
    .headers {
  color: #000000;
  font-size: 100%;
  font-weight: bold;
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

<!--Data Tables -->
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
           <h3>Devoluciones</h3>
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
                            <label>Cliente</label><br>
                            <select id="cliente" class="form-control" style="width: 100%;" multiple="multiple">
                                <option value="0" selected>-Todos-</option>                        
                            </select>
                        </div>
                        <div>
                            <label>Encargado</label><br>
                            <select id="empleado" class="form-control" style="width: 100%;" multiple="multiple">
                                <option value="0" selected>-Todos-</option>                        
                            </select>
                        </div>  
                    </div>
                    
                    <div class="col-sm-6">
                        <div>
                            <label>Sucursal</label><br>
                            <select id="sucursal" class="form-control" style="width: 100%;">
                                <option value="0" selected>-Todos-</option>                        
                            </select>
                        </div>
                        <div>
                            <label>Almacen</label><br>
                            <select id="almacen" class="form-control" style="width: 100%;">
                                <option value="0" selected>-Todos-</option>                        
                            </select>
                        </div>
                        <div>
                            <label>Producto</label><br>
                            <select id="producto" class="form-control" style="width: 100%;" multiple="multiple">
                                <option value="0" selected>-Todos-</option>                        
                            </select>
                        </div>
                        <div>
                            <button class="btn btn-default" onclick="procesar();">Procesar</button><br> 
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
    <label >Reporte de Ventas </label><br>
    <label >Documento: Devoluciones de Ventas</label><br>
    <label >Periodo:</label> <label id="lbperiodo"> Sin Rango</label><br>
    <label >Sucursal:</label> <label id="lbsucursal">Todas</label><br>
    <label >Almacen:</label> <label id="lbalmacen">Todos</label><br>
    <label >Usuario:</label> <label id="lbempleado">Todos</label>
    <table id="table_listado" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th style="display: none;"></th>
            <th style="display: none;"></th>
            <th style="display: none;"></th>
            <th style="display: none;"></th>
            <th style="display: none;"></th>
            <th style="display: none;"></th>
            <th style="display: none;"></th>
            <th style="display: none;"></th>
          </tr>
        </thead>
        <tbody>
        </tbody>

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

    $("#producto, #cliente, #empleado").select2();

    reloadselectVV();
    
    });

    function reloadselectVV(){
        
        $.ajax({
            url: 'ajax.php?c=reportes&f=selectVV',
            type: 'post',
            dataType: 'json',
        })
        .done(function(data) {
            $.each(data.cliente, function(index, val) {
              $('#cliente').append('<option value="'+val.id+'">'+val.nombrecliente+'</option>');  
            });
            $.each(data.vendedor, function(index, val) {
              $('#empleado').append('<option value="'+val.idVendedor+'">'+val.vendedor+'</option>');  
            });
        });
        $.ajax({
            url: 'ajax.php?c=reportes&f=selectProductos',
            type: 'post',
            dataType: 'json',
        })
        .done(function(data) {
            $.each(data, function(index, val) {
                  $('#producto').append('<option value="'+val.id+'">'+val.nombre+'</option>');  
            });
        })
        $.ajax({ 
            url: 'ajax.php?c=reportes&f=listAlmacen',
            type: 'post',
            dataType: 'json',
        })
        .done(function(data) {
            $.each(data, function(index, val) {
                $('#almacen').append('<option value="'+val.id+'">'+val.nombre+'</option>');
            });
        })
        $.ajax({
            url: 'ajax.php?c=reportes&f=listSucursal',
            type: 'post',
            dataType: 'json',
        })
        .done(function(data) {
            $('#sucursal').html('');
            $('#sucursal').append('<option value="0">-Todos-</option>');
            $.each(data, function(index, val) {
                  $('#sucursal').append('<option value="'+val.idSuc+'">'+val.nombre+'</option>');  
            });
            $('#sucursal').change(function()
            {   
                $('#almacen').html('');
                $('#almacen').append('<option selected="selected" value="0">-Todos-</option>');
                idSuc = $('#sucursal').val();

                    $.ajax({ 
                        data : {idSuc:idSuc},
                        url: 'ajax.php?c=reportes&f=listAlmacen',
                        type: 'post',
                        dataType: 'json',
                    })
                    .done(function(data) {
                        $.each(data, function(index, val) {
                              $('#almacen').append('<option value="'+val.id+'">'+val.nombre+'</option>');
                        });
                    }) 
                
            });
        }) 
    }
    function reloadDevoluciones(desde,hasta,cliente,producto,sucursal,almacen,empleado,sucursalF,almacenF,empleadoF,periodo){

        
        var lbcliente = $('#cliente option:selected').text();
        var lbproducto = $("#producto").text();

        $.ajax({
            url: 'ajax.php?c=reportes&f=listDevoluciones',
            type: 'post',
            dataType: 'json',
            data:{desde:desde,hasta:hasta,cliente:cliente,producto:producto,sucursal:sucursal,almacen:almacen,empleado:empleado},
        })
        .done(function(data) {
            var table = $('#table_listado').DataTable( {dom: 'Bfrtip',
                                                        buttons: [
                                                                                            {
                                                                                                extend: 'print',
                                                                                                title: $('h2').text(),
                                                                                                customize: function ( win ) {
                                                                                                    $(win.document.body)
                                                                                                        .css( 'font-size', '10pt' )
                                                                                                        .prepend(
                                                                                                            '<h3>Reporte de Ventas</h3><br>'+
                                                                                                            '<h5>Documento: Devoluciones de Ventas<br>'+
                                                                                                            '<label>Periodo: '+periodo+' </label><br>'+
                                                                                                            '<label>Sucursal: '+sucursalF+' </label><br>'+
                                                                                                            '<label>Almacen: '+almacenF+' </label><br>'+
                                                                                                            '<label>Usuario: '+empleadoF+' </label><br></h5>'
                                                                                                        );                                                     
                                                                                                }
                                                                                            }
                                                                                            ],
                                                        language: { 
                                                            buttons: {
                                                                print: 'Imprimir'
                                                            }
                                                        },
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
                                                                 },
                                });

                table.clear().draw();
            var x ='';
            var contProAnt = 0;

                            $.each(data, function(index, val) {
                                var cliente     = val.cliente;
                                var producto    = val.producto;

                                var fecha       = val.fecha;
                                var id_dev      = val.id;
                                var cantidad    = val.cantidad;                                                                                                
                                var importe     = val.importe;
                                var total       = val.total;
                                var unidad      = val.unidad;
                                var impuestos   = val.impuestos;
                                var unitario    = val.unitario;

                                var contCli     = val.contCli;
                                var contPro     = val.contPro;

                                var auxPro      = val.auxPro;
                                var auxCli      = val.auxCli;

                                var sumCan      = val.sumCan;
                                var sumTot      = val.sumTot;
                                var sumImpo     = val.sumImpo;
                                var sumImpu     = val.sumImpu;
                                
                                var sumCanC      = val.sumCanC;
                                var sumTotC      = val.sumTotC;
                                var sumImpoC     = val.sumImpoC;
                                var sumImpuC     = val.sumImpuC;

                                    if((contCli == 1)){
                                        x = '<tr class= "headers">'+
                                                '<td>Cliente:</td>'+
                                                '<td colspan="7">'+cliente+'</td>'+
                                                '<td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td>'+
                                        '</tr>';  
                                        table.row.add($(x)).draw(); 
                                    }
                                    if(contPro == 1){
                                            x = '<tr class= "headers">'+
                                                    '<td>Producto:</td>'+
                                                    '<td colspan="7">'+producto+'</td>'+
                                                    '<td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td>'+
                                                '</tr>';  
                                        table.row.add($(x)).draw();
                                    }
                                    if((contCli == 1)){
                                        x = '<tr class= "headers">'+
                                                    '<td align="center">Fecha</td>'+
                                                    '<td align="center">No. Dev</td>'+
                                                    '<td align="center">Cantidad</td>'+
                                                    '<td align="center">Unidad</td>'+
                                                    '<td align="center">Unitario</td>'+
                                                    '<td align="center">Importe</td>'+
                                                    '<td align="center">Impuestos</td>'+
                                                    '<td align="center">Total</td>'+
                                        '</tr>';  
                                        table.row.add($(x)).draw(); 
                                    }
                                    fechaF = formatFechaL(fecha);
                                    x ='<tr>'+
                                                    '<td>'+fechaF+'</td>'+
                                                    '<td align="center">'+id_dev+'</td>'+
                                                    '<td align="center">'+cantidad+'</td>'+
                                                    '<td align="center">'+unidad+'</td>'+
                                                    '<td align="right">'+unitario+'</td>'+
                                                    '<td align="right">'+importe+'</td>'+
                                                    '<td align="right">'+impuestos+'</td>'+
                                                    '<td align="right">'+total+'</td>'+
                                        '</tr>';  
                                    table.row.add($(x)).draw(); 
                                    if(auxPro == 1){
                                            x = '<tr class= "headers">'+
                                                    '<td colspan="2" align="center">Total:</td>'+
                                                    '<td style="display: none;"></td>'+
                                                    '<td align="center">'+sumCan+'</td>'+
                                                    '<td></td>'+
                                                    '<td></td>'+
                                                    '<td align="right">'+sumImpo+'</td>'+
                                                    '<td align="right">'+sumImpu+'</td>'+
                                                    '<td align="right">'+sumTot+'</td>'+                                                                                                    
                                                '</tr>';  
                                        table.row.add($(x)).draw();
                                    }
                                    if(auxCli == 1){
                                            x = '<tr class= "headers">'+
                                                    '<td colspan="2">Total: '+cliente+'</td>'+
                                                    '<td style="display: none;"></td>'+
                                                    '<td align="center">'+sumCanC+'</td>'+
                                                    '<td></td>'+
                                                    '<td></td>'+
                                                    '<td align="right">'+sumImpoC+'</td>'+
                                                    '<td align="right">'+sumImpuC+'</td>'+
                                                    '<td align="right">'+sumTotC+'</td>'+                                                                                                    
                                                '</tr>';  
                                        table.row.add($(x)).draw();
                                    }

                            });
                $("#divtable").show();
                $("#modalMensajes").modal('hide');
            })                     
    
    }
    function procesar(){
        
        var desde = $('#desde').val();
        var hasta = $('#hasta').val();
        var sucursal = $('#sucursal').val();
        var almacen = $('#almacen').val();
        var producto = $('#producto').val();
        var cliente = $('#cliente').val();
        var empleado = $('#empleado').val();

        if((desde != '' && hasta == '') || (desde == '' && hasta != '')){
        alert('Debe selecionar un Rango de Fecha');
        return false;
        }

        var desdeF =  formatFecha(desde);
        var hastaF =  formatFecha(hasta);
        if(desde == '' && hasta ==''){
            var periodo = 'Sin Rango';
        }else{
            var periodo = 'Del '+desdeF+' al '+hastaF;
        }
        
        $("#lbperiodo").text(periodo);
        var sucursalF = $('#sucursal option:selected').text();
        $("#lbsucursal").text(sucursalF);
        var almacenF = $('#almacen option:selected').text();
        $("#lbalmacen").text(almacenF);
        var empleadoF = $('#empleado option:selected').text();
        $("#lbempleado").text(empleadoF);

        $("#modalMensajes").modal('show');

        reloadDevoluciones(desde,hasta,cliente,producto,sucursal,almacen,empleado,sucursalF,almacenF,empleadoF,periodo);

    }


</script>



