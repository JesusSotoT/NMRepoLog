<style>
    tfoot, thead {
  background-color: #d3d3d3;
  color: #000000;
  font-size: 100%;
  font-weight: bold;
}
.rowhide {
    display: none;
}
.rowshow {
    display: none;
}
.sizeprint {
    max-width: 612px;
}
</style>

<!DOCTYPE html>

<html>
<head>
    <meta http-equiv="Expires" content="0">
    <title>Pedidos</title>
</head>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../../libraries/numeric/jquery.numeric.js"></script>
    <!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />
    <!-- Datepicker -->
    <link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.es.js"></script>

    <!--Data Tables -->
    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <script src="../../libraries/dataTable/js/datatables.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>

    <!-- Modificaciones RC -->
    <script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
    <script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
    <script src="../../libraries/export_print/buttons.html5.min.js"></script>
    <script src="../../libraries/export_print/jszip.min.js"></script>

    <!--Button Print css -->
    <link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
    <!--Button Print js -->
    <script src="../../libraries/dataTable/js/dataTables.buttons.min.js"></script>
    <script src="../../libraries/dataTable/js/buttons.print.min.js"></script>
    
<body> 
<br> 
<div class="container well" >
    <div class="row">
        <div class="col-xs-12 col-md-12">
           <h3>Lista de Compra</h3>
        </div>
    </div>
    <div class="row col-md-12"> <input type="hidden" value="" id="reporte"> 
        <div class="panel panel-default" id="divfiltro">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Rango de Fechas</label><br>
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
                    </div>
                    <div class="col-sm-6">
                        <div>
                            <label>Clientes</label><br>
                            <select id="cliente" class="form-control" style="width: 100%;">                      
                            </select>
                        </div>
                        <div>
                            <label>Productos</label><br>
                            <select id="producto" class="form-control" style="width: 100%;" multiple="multiple">
                                <option value="0" selected="selected">-Todos-</option>                        
                            </select>
                        </div>
                        <button class="btn btn-default" onclick="listaCompra();">Procesar</button><br> 
                    </div>
                </div>
            </div>    
        </div>
    </div>
</div>
    
<div class="container">
    <div class="container" id="divambos" style="overflow:auto">
        <div class="col-sm-12">
            <table id="table_ambos" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th td width="60%">Producto</th>
                        <th td width="10%">Uni. Comp.</th>
                        <th td width="20%">Cantidad</th>
                        <th td width="20%">Costo de Compra</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td align = "right">Sub Total</td>
                        <td></td>
                        <td></td>
                        <td align = "right" id="subtotal"></td>
                    </tr>
                    <tr>
                        <td align = "right">Impuestos</td>
                        <td></td>
                        <td></td>
                        <td align = "right" id="impuestos"></td>
                    </tr>
                    <tr>
                        <td align = "right">Total</td>
                        <td></td>
                        <td align = "right"><label id="total"></label></td>
                        <td align = "right"><label id="totalI"></label></td>
                    </tr>
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

<script>
    
    var idclienteLog = 0;
   $(document).ready(function() {

        $.ajax({ 
            url: 'ajax.php?c=caja&f=clienteLog',
            async: false,
        })
        .done(function(data) {  
            idclienteLog = data;
        })
        
        function hoy(){
            var hoy = new Date();
            var dd = hoy.getDate();
            var mm = hoy.getMonth()+1; //hoy es 0!
            var yyyy = hoy.getFullYear();

            if(dd<10) {
                dd='0'+dd
            } 
            if(mm<10) {
                mm='0'+mm
            } 
            return hoy = yyyy+'-'+mm+'-'+dd;
        }
        var hoy = hoy();
        $('#desde').val(hoy);
        $('#hasta').val(hoy);

        $("#divambos").hide();       
        $('#desde, #hasta').datepicker({ 
            format: "yyyy-mm-dd",
            "autoclose": true, 
            language: "es"
        }).attr('readonly','readonly');
        $('#producto').select2(); 
        $.ajax({
                url: 'ajax.php?c=caja&f=selectListaCompra',
                type: 'post',
                dataType: 'json',
                data : {idclienteLog:idclienteLog},
        })
        .done(function(data) {

            $.each(data.clientes, function(index, val) {
                  $('#cliente').append('<option value="'+val.id+'" selected="selected">'+val.nombreCliente+'</option>');
            });
            $.each(data.productos, function(index, val) {
                  $('#producto').append('<option value="'+val.id+'">'+val.nombre+'</option>');  
            }); 
        })

    });


    function rowU(codigo){

        if($(".rowU"+codigo+"").hasClass('rowhide') == true){
            $(".rowU"+codigo+"").removeClass('rowhide');
            $("#iU"+codigo+"").removeClass('glyphicon-chevron-down');
            $("#iU"+codigo+"").addClass('glyphicon-chevron-up'); 
        }else{
            $(".rowU"+codigo+"").addClass('rowhide');
            $("#iU"+codigo+"").removeClass('glyphicon-chevron-up');
            $("#iU"+codigo+"").addClass('glyphicon-chevron-down'); 
        }  
    }
    function listaCompra(){

        var producto = $('#producto').val();
        var cliente = $('#cliente').val();
        var desde = $('#desde').val();
        var hasta = $('#hasta').val();

        var horaini = $("horaIn").val();
        var horafin = $("horafin").val();
        var minutosini = $("minutoIn").val();
        var minutosfin = $("minutofin").val();

        var horainicial = $("#horaIn").val() + ":" + $("#minutoIn").val() + ":00";
        var horafinal = $("#horafin").val() + ":" + $("#minutofin").val() + ":00";

        if(desde == '' && hasta != ''){
            alert('Debe Selecionar Desde que Fecha');
            return false;
        }
        if(hasta == '' && desde != ''){
            alert('Debe Selecionar Hasta que Fecha');
            return false;
        }
        if(hasta != '' && desde != ''){
            if(desde > hasta){
                alert('No debe ser mayor la fecha de inicio a la fecha final');
                return false;
            }
        }

        $("#modalMensajes").modal('show');
        listarCompra(desde,hasta,producto,cliente,horainicial,horafinal);
    }
    function listarCompra(desde,hasta,producto,cliente,horainicial,horafinal){
        
        alert("listarCompra: "+horainicial+"  "+horafinal);
        var cont = 0;
        var btnU = '';

        $.ajax({ 
            url: 'ajax.php?c=caja&f=listarCompra',
            type: 'post',
            dataType: 'json',
            data:{desde:desde,hasta:hasta,producto:producto,cliente:cliente,user:1,horainicial:horainicial,horafinal:horafinal}, 
        })
        .done(function(data) {  
            var table = $('#table_ambos').DataTable( {dom: 'Bfrtip',
                                                            buttons: [
                                                                        {
                                                                            extend: 'print',
                                                                            title: $('h2').text(),
                                                                            customize: function ( win ) {
                                                                                $(win.document.body)
                                                                                    .css( 'font-size', '10pt' )
                                                                                    .prepend(
                                                                                        '<h3>Lista de Compra</h3><br>'
                                                                                    );                                                     
                                                                            }
                                                                        },
                                                                        'excel',
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
            var x               ='';
            var y               ='';
            var totalI          = 0;
            var total           = 0;
            var totalImp        = 0;
            var subtotal        = 0;
            
            $.each(data.prod, function(index, val) {
                cont++;                   
                var idprod          = val.idprod;
                var caracteristicas = val.caracteristicas;
                var nombre          = val.nombre;
                var cantidad        = val.cantidad*1;
                var costoCompra     = val.costoCompra*1;
                var impuestos       = val.impuestos*1;
                var caract          = val.caract;
                var unidad          = val.unidad;
                    total       += cantidad;
                    totalI      += costoCompra;
                    totalImp    += impuestos;
                
                cantidadF       = cantidad.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
                costoCompraF    = costoCompra.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');  

                btnU = '<button class="btn btn-default btn-xs" onclick="rowU('+idprod+cont+')"><i id="iU'+idprod+cont+'" class="glyphicon glyphicon-chevron-down"></i></button>';

                x ='<tr>'+
                        '<td><b>'+btnU+' '+nombre+' '+caract+'</b></td>'+
                        '<td><b>'+unidad+'</b></td>'+
                        '<td align="center"><b>'+cantidadF+'</b></td>'+
                        '<td align="center"><b>$'+costoCompraF+'</b></td>'+
                    '</tr>';  
                table.row.add($(x)).draw();

                $.each(data.prod2, function(index, v) {
                    var idprod2          = v.idprod;
                    var caracteristicas2 = v.caracteristicas;
                    var nombreCliente    = v.nombreCliente;
                    var cantidad2        = v.cantidad*1;
                    var costoCompra2     = v.costoCompra*1;
                    var impuestos2       = v.impuestos*1;
                    var caract2          = v.caract;

                    cantidadF2      = cantidad2.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
                    costoCompraF2   = costoCompra2.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');  

                     if(idprod == idprod2 && caracteristicas == caracteristicas2){
                        y ='<tr class="rowU'+idprod+cont+' rowhide">'+
                            '<td>-- <em>'+nombreCliente+'</em></td>'+
                            '<td></td>'+
                            '<td align="center">'+cantidadF2+'</td>'+
                            '<td align="center">$'+costoCompraF2+'</td>'+
                        '</tr>';
                        table.row.add($(y)).draw();
                     }   
                })            
            })

            $("#modalMensajes").modal('hide');
            $("#divambos").show();
             
             subtotal   = totalI - totalImp;
             subtotalF  = subtotal.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
             $("#subtotal").text('$'+subtotalF);

             totalIF    = totalI.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
             $("#totalI").text('$'+totalIF);
             
             totalF     = total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
             $("#total").text(totalF);
             
             totalImpF  = totalImp.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');                         
             $("#impuestos").text('$'+totalImpF);
                          
        })
    }
    
</script>

