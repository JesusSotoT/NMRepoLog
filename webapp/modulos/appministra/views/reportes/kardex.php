<style>
    tfoot, thead {
  background-color: #d3d3d3;
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
    <title>Kardex</title>
    <link rel="stylesheet" href="">
</head>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../../libraries/numeric/jquery.numeric.js"></script>
    <script src="js/reportes.js"></script>
<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />
    <!-- Datepicker -->
    <link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="js/bootstrap-datepicker.es.js" type="text/javascript"></script>

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
           <h3>Kardex</h3>
        </div>
    </div>
    <div class="row col-md-12">                     <input type="hidden" value="" id="reporte"> 
        <div class="panel panel-default" id="divfiltro">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Rango de Fechas Desde</label><br>
                        <label >Desde:</label>
                        <div id="datetimepicker1" class="input-group date">                            
                            <input id="desde" class="form-control" type="text" placeholder="Desde">
                            <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                            </span> 
                        </div>
                        <label >Hasta:</label>
                        <div id="datetimepicker2" class="input-group date">
                            <input id="hasta" class="form-control" type="text" placeholder="Hasta">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>    
                        </div>
                        
                        <div>
                            <label>Productos</label><br>
                            <select id="producto" class="form-control" style="width: 100%;" multiple="multiple">
                                <option value="0" selected="selected">-Todos-</option>                        
                            </select>
                        </div>
                        
                        <!--
                        <label>Departamento</label><br>
                        <select id="departamento" class="form-control">
                            <option value="0">-Todos-</option>                        
                        </select><br>

                        <div id="divfamilia">
                            <label>Familia</label><br>
                            <select id="familia" class="form-control">
                                <option value="0">-Todas-</option>                          
                            </select><br>
                        </div>
                        <div id="divlinea">
                            <label>Linea</label><br>
                            <select id="linea" class="form-control">
                                <option value="0">-Todas-</option>                          
                            </select><br> 
                        </div>
                        <div id="divcaract">
                            <label>Caracteristicas</label><br>
                            <select id="caracteristicas" class="form-control">
                                <option value="0">-Todas-</option>                          
                            </select>
                        </div>
                    -->
                    </div>
                    <div class="col-sm-6">
                        <label>Sucursal</label><br>
                        <select id="sucursal" class="form-control">                         
                        </select>
                        <label>Almacen</label><br>
                        <select id="almacen" class="form-control">                        
                        </select><br>
                        <!--
                        <label>Unidad</label><br>
                        <select id="unidad" class="form-control">
                            <option value="0">-Unidad Base-</option>                          
                        </select><br>
                        <label>Seleccion de Unidad</label><br>
                        <select id="selunidad" class="form-control">
                            <option value="0">-PZA-</option>
                      -->

                        <div class="col-sm-6">
                            <label>Reporte</label><br>
                            <input type="radio" name="rep" id="R1new" value="new" checked="checked">Unidades detalle<br>
                            <input type="radio" name="rep" id="R1newI" value="newI">Importe detalle<br>
                            <input type="radio" name="rep" id="R1ambos" value="ambos">Ambos<br>                        
                        </div>
                        <button class="btn btn-default" onclick="listKardex();">Procesar</button><br> 
                        
                        <!-- <button class="btn btn-default" id="btnprocesarIA" onclick="printIA('divfiltro');">Imprimir</button>
                        <input type="radio" name="rep" id="R1unidades" value="unidades" checked="checked" >En Unidades<br>
                            <input type="radio" name="rep" id="R1importe" value="importe">En Importe<br>
                         <div class="col-sm-6">
                            <label></label><br>
                            <br>
                            <input type="radio" name="rep2" id="R2global" value="global" checked="checked">Global<br>
                            <input type="radio" name="rep2" id="R2detalle" value="detalle">A detalle
                        </div>
                        <div class="col-sm-12">
                            <label>Imprimir Productos</label><br>
                            <input type="radio" name="rep3" id="R3todos" value="todos" checked="checked">Todos<br>
                            <input type="radio" name="rep3" id="R3movimientos" value="movimientos">Solo con moviminetos  
                        </div>
                        -->
                    </div>
                </div>
            </div>    
        </div>
    </div>
    
    <div class="container">
        <div class="container" id="divambos" style="overflow:auto">
            <div class="col-sm-12">
            <h5>Tipo: <label>Unidades </label> <br> Almacen: <label id="lbalmacen"> Almacen 1</label> <br> Periodo: <label id="lbperiodo"> De 2016-01-01 al 2016-12-31</label> <br> Productos: <label id="lbProductosC"> Todos</label></h5>
                <div id="divu" style="max-width:95%"></div>
            </div>
        </div>
        <div class="container" id="divimporte" style="overflow:auto">
            <div class="col-sm-12">
            <h5>Tipo: <label>Importe </label> <br> Almacen: <label id="lbalmacen"> Almacen 1</label> <br> Periodo: <label id="lbperiodo2"> Del 2016-01-01 al 2016-12-31</label> <br> Productos: <label id="lbProductosI"> Todos</label></h5>
                <div id="divi" style="max-width:95%"></div>
            </div>
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
 

  <div id="divu">
      
  </div>


<script>

$(document).ready(function() {

        $("#divimporte").hide();
        $("#divambos").hide();

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
        function mesA(){
            var fecha=new Date();
            var mesA=new Date(fecha.getTime() - (24*60*60*1000)*30);
            var dd = mesA.getDate();
            var mm = mesA.getMonth()+1;
            var yyyy = mesA.getFullYear();

                if(dd<10) {
                    dd='0'+dd
                } 
                if(mm<10) {
                    mm='0'+mm
                } 
                 return mesA = yyyy+'-'+mm+'-'+dd;
        } 
        
        var mesA = mesA();
        var hoy = hoy();
        $('#desde').val(mesA);
        $('#hasta').val(hoy);
           

        $('#desde, #hasta').datepicker({ 
            format: "yyyy-mm-dd",
            "autoclose": true, 
            language: "es"
        }).attr('readonly','readonly');
        
        $('#producto').select2();
        
        jsonMaxCont = '';
        jsonMaxCont2 = '';
        jsonExis = '';
        jsonExis2 = '';


        // Select Productos
        $.ajax({
                url: 'ajax.php?c=reportes&f=listProductos',
                type: 'post',
                dataType: 'json',
        })
        .done(function(data) {
            $.each(data, function(index, val) {
                  $('#producto').append('<option value="'+val.id+'">'+val.nombre+'</option>');  
            });
        }) 
          //SUCURSAL
        $.ajax({
                url: 'ajax.php?c=reportes&f=listSucursal',
                type: 'post',
                dataType: 'json',
        })
        .done(function(data) {
            $('#sucursal').html('');
            //$('#sucursal').append('<option value="0">-Todas-</option>');
            $.each(data, function(index, val) {
                  $('#sucursal').append('<option value="'+val.idSuc+'">'+val.nombre+'</option>');  
            });
            $('#sucursal').change(function()
            {   

                $('#almacen').html('');
                //$('#almacen').append('<option selected="selected" value="0">-Todos-</option>');
                idSuc = $('#sucursal').val();

                    $.ajax({ 
                        data : {idSuc:idSuc},
                        url: 'ajax.php?c=reportes&f=listAlmacen',
                        type: 'post',
                        dataType: 'json',
                    })
                    .done(function(data) {
                        //$('#almacen').select2("val", '');
                        $.each(data, function(index, val) {
                              $('#almacen').append('<option value="'+val.id+'">'+val.nombre+'</option>');
                        });
                    }) 
                
            });
        })
        // Select Almacenes
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
});
function listKardex(){

    var producto = $('#producto').val();
    var almacen = $('#almacen').val();
    var sucursal = $('#sucursal').val();
    var desde = $('#desde').val();
    var hasta = $('#hasta').val();
    var nomAl = $('#almacen option:selected').text();
    var productos = $('#producto option:selected').text();

    if(almacen == null){
        alert("Seleccione un Almacen");
        return false
    }

    if(desde > hasta){
        alert('Debe Selecionar un Rango Correcto');
        return false;
    }
    
    var R1 = "";

      if ($('#R1ambos').prop('checked')) {
        R1 = $('#R1ambos').val();
      }
      if ($('#R1new').prop('checked')) {
        R1 = $('#R1new').val();
      }
      if ($('#R1newI').prop('checked')) {
        R1 = $('#R1newI').val();
      }


      if(R1 == 'ambos'){
        $("#divambos").show();
        $("#divimporte").show();
      }
      if(R1 == 'new'){
        $("#divambos").show();
        $("#divimporte").hide();
      }
      if(R1 == 'newI'){
        $("#divambos").hide();
        $("#divimporte").show();
      }
      var periodo;

      if(desde == '' && hasta == ''){
        periodo = 'Sin Periodo';
      }else{
        var hastaF = formatFecha(hasta);
        var desdeF = formatFecha(desde);
        periodo = 'Del '+desdeF +' al '+hastaF;
      }
      if(desde == '' && hasta != ''){
        alert('Debe Selecionar Desde que Fecha');
        return false;
      }
        if(hasta == '' && desde != ''){
        alert('Debe Selecionar Hasta que Fecha');
        return false;
      }

      $("#modalMensajes").modal('show');

    $("#lbalmacen").text(nomAl);
    $("#lbperiodo").text(periodo);
    $("#lbperiodo2").text(periodo);
    $("#lbProductosI").text(productos);
    $("#lbProductosC").text(productos);
      

    if(R1 == 'new'){
        kardex(producto,almacen,desde,hasta,periodo,sucursal);
    }
    if(R1 == 'newI'){
        kardexI(producto,almacen,desde,hasta,periodo,sucursal);
    }
    if(R1 == 'ambos'){
        kardex(producto,almacen,desde,hasta,periodo,sucursal);
        kardexI(producto,almacen,desde,hasta,periodo,sucursal);
    }     
}
function kardex(producto,almacen,desde,hasta,periodo,sucursal){

    var productos = $('#producto option:selected').text();
    var almacenF = $('#almacen option:selected').text();
    var almacenSlct = $('#almacen option:selected').text();

        $.ajax({ /// TODOS LOS MOVIMIENTOS ENTRE EL RANGO DE FECHAS 'movs'
                url: 'ajax.php?c=reportes&f=listUbicCaractMov',
                type: 'post',
                dataType: 'html',
                data: {producto:producto,desde:desde,hasta:hasta,tipo:'movs',almacen:almacen,sucursal:sucursal,almacenSlct:almacenSlct}, 
        })
        .done(function(data) {
            var tablas = data.split('ª');
            var tablau = tablas[0];
            $('#divu').html('');
            $('#divu').append(tablau);
            $('#tableu').DataTable( {dom: 'Bfrtip',
                                                            buttons: [  
                                                                        {
                                                                            extend: 'print',
                                                                            title: $('h2').text(),
                                                                            customize: function ( win ) {
                                                                                $(win.document.body)
                                                                                    .css( 'font-size', '10pt' )
                                                                                    .prepend(
                                                                                        '<h3>Inventarios</h3><br>'+
                                                                                        '<h5>Documento:Kardex<br>'+
                                                                                        '<label>Tipo: Unidades </label><br>'+
                                                                                        '<label>Almacen: '+almacenF+' </label><br>'+
                                                                                        '<label>Periodo: '+periodo+' </label><br>'+                                                                                                    
                                                                                        '<label>Productos: '+productos+' </label><br></h5>'
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
            $("#modalMensajes").modal('hide');
        })
}
function kardexI(producto,almacen,desde,hasta,periodo,sucursal){

    var productos = $('#producto option:selected').text();
    var almacenSlct = $('#almacen option:selected').text();
    var almacenF = $('#almacen option:selected').text();

    $.ajax({ /// TODOS LOS MOVIMIENTOS ENTRE EL RANGO DE FECHAS 'movs'
                url: 'ajax.php?c=reportes&f=listUbicCaractMov',
                type: 'post',
                dataType: 'html',
                data: {producto:producto,desde:desde,hasta:hasta,tipo:'movs',almacen:almacen,sucursal:sucursal,almacenSlct:almacenSlct}, 
        })
        .done(function(data) {
            var tablas = data.split('ª');
            var tablai = tablas[1];
            $('#divi').html('');
            $('#divi').append(tablai);
            $('#tablei').DataTable( {dom: 'Bfrtip',
                                                            buttons: [  
                                                                        {
                                                                            extend: 'print',
                                                                            title: $('h2').text(),
                                                                            customize: function ( win ) {
                                                                                $(win.document.body)
                                                                                    .css( 'font-size', '10pt' )
                                                                                    .prepend(
                                                                                        '<h3>Inventarios</h3><br>'+
                                                                                        '<h5>Documento:Kardex<br>'+
                                                                                        '<label>Tipo: Unidades </label><br>'+
                                                                                        '<label>Almacen: '+almacenF+' </label><br>'+
                                                                                        '<label>Periodo: '+periodo+' </label><br>'+                                                                                                    
                                                                                        '<label>Productos: '+productos+' </label><br></h5>'
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
            $("#modalMensajes").modal('hide');
        }) 

}

</script>