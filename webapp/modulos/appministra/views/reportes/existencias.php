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
    <title>Existencias</title>
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


    <!-- morris -->
    <link rel="stylesheet" href="../../libraries/morris.js-0.5.1/morris.css">
    <script src="../../libraries/morris.js-0.5.1/raphael.min.js"></script>
    <script src="../../libraries/morris.js-0.5.1/morris.min.js"></script>

    <!--Button Print css -->
    <link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
    <!--Button Print js -->
    <!--<script src="../../libraries/dataTable/js/dataTables.buttons.min.js"></script>-->
    <script src="../../libraries/dataTable/js/buttons.print.min.js"></script>
   
<body> 
<br> 
<div class="container well">
    <div class="row">
        <div class="col-xs-12 col-md-12">
           <h3>Existencias</h3>
        </div>
    </div>
    <div class="row col-md-12" id="divfiltro">                     
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-6"> 
                        <label>Hasta la fecha de:</label>
                        <div id="datetimepicker2" class="input-group date">
                            <input id="hasta" class="form-control" type="text" placeholder="Hasta">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>    
                        </div>

                        <label>Productos</label><br>
                        <select id="producto" class="form-control" style="width: 100%;" multiple ="multiple">
                            <option value="0" selected>-Todos-</option> 
                            <?php 
                              foreach($listProductos as $v){
                                echo '<option value="'.$v['id'].'">'.$v['nombre'].'</option> ';
                              }
                             ?>                      
                        </select><br>
                    </div>
                    <div class="col-sm-6">
                    <label>Almacen</label><br>
                        <select id="almacen" class="form-control">
                          <?php 
                              foreach($listAlmacen as $v){
                                echo '<option value="'.$v['id'].'">'.$v['nombre'].'</option> ';
                              }
                             ?>                            
                        </select>
                      <label>Unidades</label><br>
                        <select id="unidades" class="form-control" style="width: 100%;" multiple ="multiple">
                        <option value="0" selected>-Todas-</option>
                          <?php 
                              foreach($listunidades as $v){
                                echo '<option value="'.$v['id'].'">'.$v['clave'].'</option> ';
                              }
                             ?>                            
                        </select>

                        <div class="col-sm-6">
                            <label>Reporte</label><br>
                            <input type="radio" name="rep" id="R1ambos" value="ambos" checked="checked">Ambos <br>
                            <input type="radio" name="rep" id="R1unidades" value="unidades">En Unidades<br>
                            <input type="radio" name="rep" id="R1importe" value="importe">En Importe<br>
                            

                        </div>
                        <br><br><button class="btn btn-default" onclick="procesarExs();">Procesar</button>
                        
                                         
                    </div>
                    <button id="btnGraf">
                                <i class="fa fa-line-chart" ></i><strong>Graficas</strong>
                    </button>
                    <div id="divgrafic" style="overflow:auto">
                        <div class="col-sm-12" id="graficas">    
                            <div style="height:150px;   width:520px;" id="graficaBar" style="height:100%;"></div>
                            <div style="height:150px;   width:520px;" id="graficaBar2" style="height:100%;"></div>
                        </div>
                    </div>

                </div>
            </div>    
        </div>
    </div>

  <div class="container" id="divambos" style="overflow:auto">
    <div class="col-sm-12">

            <h5>Tipo: <label id="lbtipo">Importe y Unidades</label> <br> Almacen: <label id="lbalmacen"> Almacen 1</label> <br> A la Fecha de : <label id="lbperiodo">2016-12-31</label> <br> Producto: <label id="lbproducto">Todos</label></h5>
            <table id="table_ambos" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>Codigo</th>
            <th>Producto</th>
            <th>Existencia Unidades</th>
            <th>Unidad Medida</th>
            <th>Costo Unitario</th>
            <th>Importe</th>
            <th>Moneda</th>
          </tr>
        </thead>
        <tbody>
        </tbody>

      </table>
    </div>
  </div>

  <div class="container" id="divcantidad" style="overflow:auto">
    <div class="col-sm-12">
        <h5>Tipo: <label>Unidades</label> <br> Almacen: <label id="lbalmacenC"> Almacen 1</label> <br> A la Fecha de : <label id="lbperiodoC">2016-12-31</label> <br> Producto: <label id="lbproductoC">Todos</label></h5>
        <table id="table_cantidad" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>Codigo</th>
            <th>Producto</th>
            <th>Existencia Unidades</th>
            <th>Unidad Medida</th>
          </tr>
        </thead>
        <tbody>
        </tbody>

      </table>
  </div>
  </div>

    <div class="container" id="divimporte" style="overflow:auto">
        <div class="col-sm-12">
        <h5>Tipo: <label>Importe</label> <br> Almacen: <label id="lbalmacenI"> Almacen 1</label> <br> A la Fecha de : <label id="lbperiodoI">2016-12-31</label> <br> Producto: <label id="lbproductoI">Todos</label></h5>
        <table id="table_importe" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>Codigo</th>
            <th>Producto</th>
            <th>Importe</th>
            <th>Moneda</th>
          </tr>
        </thead>
        <tbody>
        </tbody>

      </table>
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
  </div>

<script>
    $( "#btnGraf" ).click(function() {
          $( "#graficas" ).toggle( "slow" );
        });
       
       $(document).ready(function() {
        var hoy = $("#hasta").val(hoy2());
        $("#modalMensajes").modal('show');

            R1 = '';

            $("#divambos").hide();

            $("#divimporte").hide();
            $("#divcantidad").hide();

            if(R1 == 'ambos'){
                $("#divambos").show();
                $("#divimporte").hide();
                $("#divcantidad").hide();
            }
            if(R1 == 'unidades'){
                $("#divambos").hide();
                $("#divimporte").hide();
                $("#divcantidad").show();
            }
            if(R1 == 'importe'){
                $("#divambos").hide();
                $("#divimporte").show();
                $("#divcantidad").hide();
            }

         reloadExis0('',1,'','ambos');

        $("#lbmoneda").text("MXN");
        $('#producto, #unidades').select2();
        $('#desde, #hasta').datepicker({ 
            format: "yyyy-mm-dd",
            "autoclose": true, 
            language: "es"
        }).attr('readonly','readonly');
        
   });

    function procesarExs(){

        $("#modalMensajes").modal('show');

        hasta       = $("#hasta").val();
        var hastaF  = formatFecha(hasta);
        almacen     = $("#almacen").val();
        producto    = $("#producto").val();
        unidades    = $("#unidades").val();
        var nomAl       = $('#almacen option:selected').text();
        var lbproducto  = $('#producto option:selected').text();
        var lbfecha     = '';

        var R1 = "";
        if ($('#R1unidades').prop('checked')) {
            R1 = $('#R1unidades').val();
          }
          if ($('#R1importe').prop('checked')) {
            R1 = $('#R1importe').val();
          }
          if ($('#R1ambos').prop('checked')) {
            R1 = $('#R1ambos').val();
          }

            if(R1 == 'ambos'){
                $("#divambos").show();
                $("#divimporte").hide();
                $("#divcantidad").hide();

              }
              if(R1 == 'unidades'){
                $("#divambos").hide();
                $("#divimporte").hide();
                $("#divcantidad").show();
              }
              if(R1 == 'importe'){
                $("#divambos").hide();
                $("#divimporte").show();
                $("#divcantidad").hide();
              }

              if(hasta == ''){
                lbfecha = hoy();
              }else{
                lbfecha = hastaF;
              }
              
            
            $("#lbalmacen").text(nomAl);
            $("#lbperiodo").text(lbfecha);
            $("#lbproducto").text(lbproducto);

            $("#lbalmacenC").text(nomAl);
            $("#lbperiodoC").text(lbfecha);
            $("#lbproductoC").text(lbproducto);

            $("#lbalmacenI").text(nomAl);
            $("#lbperiodoI").text(lbfecha);
            $("#lbproductoI").text(lbproducto);

          reloadExis(hasta,almacen,producto,R1,lbfecha,unidades);
    }

    function reloadExis0(hasta,almacen,producto,R1,lbfecha,unidades){
      $.ajax({
                url: 'ajax.php?c=reportes&f=existenciasList',
                type: 'post',
                dataType: 'json',
                data:{hasta:hasta,almacen:almacen,producto:producto,R1:R1,All:0},
                
            })
        .done(function(data) {
          //// GRAFICA MORRIS
            if(R1 =='ambos'){
                $('#graficas').css("display","block");

                $('#graficaBar').html('').removeClass().addClass('col-sm-6').show();
                Morris.Bar({
                  element: 'graficaBar',
                  gridTextSize: '10',
                  //resize: true,
                  hideHover: 'auto',
                  data: data.graficaI,
                  resize: 'true',
                  preUnits: '$',
                  xkey: 'y',
                  ykeys: ['b'],
                  labels: ['Series B']
                });

                $('#graficaBar2').html('').removeClass().addClass('col-sm-6').show();
                Morris.Bar({
                  element: 'graficaBar2',
                  gridTextSize: '10',
                  //resize: true,
                  hideHover: 'auto',
                  data: data.graficaU,
                  xkey: 'y',
                  ykeys: ['b'],
                  labels: ['Series B']
                });
            }
            if(R1 =='unidades'){
                $('#graficas').css("display","block");
                $('#graficaBar').html('').removeClass().hide();
                $('#graficaBar2').html('').removeClass().addClass('col-sm-12').show();
                Morris.Bar({
                  element: 'graficaBar2',
                  //resize: true,
                  hideHover: 'auto',
                  data: data.graficaU,
                  xkey: 'y',
                  ykeys: ['b'],
                  labels: ['Series B']
                });    
            }
            if(R1 =='importe'){
                $('#graficas').css("display","block");
                $('#graficaBar2').html('').removeClass().hide();
                $('#graficaBar').html('').removeClass().addClass('col-sm-12').show();
                Morris.Bar({
                  element: 'graficaBar',
                  //resize: true,
                  hideHover: 'auto',
                  data: data.graficaI,
                  preUnits: '$',
                  xkey: 'y',
                  ykeys: ['b'],
                  labels: ['Series B']
                });
            }
            //// GRAFICA MORRIS FIN
            $("#modalMensajes").modal('hide');
        });
    }    

    function reloadExis(hasta,almacen,producto,R1,lbfecha,unidades){

            $.ajax({
                url: 'ajax.php?c=reportes&f=existenciasList',
                type: 'post',
                dataType: 'json',
                data:{hasta:hasta,almacen:almacen,producto:producto,R1:R1,All:1,unidades:unidades},
                
            })
            .done(function(data) {

              var productos = $('#producto option:selected').text();
              var hasta = $('#hasta').val();
              var nomAl = $('#almacen option:selected').text();

            //// GRAFICA MORRIS Y TABLE
              if(R1 =='ambos'){
                  $('#graficas').css("display","block");

                  $('#graficaBar').html('').removeClass().addClass('col-sm-6').show();
                  Morris.Bar({
                    element: 'graficaBar',
                    gridTextSize: '10',
                    //resize: true,
                    hideHover: 'auto',
                    data: data.graficaI,
                    resize: 'true',
                    preUnits: '$',
                    xkey: 'y',
                    ykeys: ['b'],
                    labels: ['Series B']
                  });

                  $('#graficaBar2').html('').removeClass().addClass('col-sm-6').show();
                  Morris.Bar({
                    element: 'graficaBar2',
                    gridTextSize: '10',
                    //resize: true,
                    hideHover: 'auto',
                    data: data.graficaU,
                    xkey: 'y',
                    ykeys: ['b'],
                    labels: ['Series B']
                  });

                  var table   = $('#table_ambos').DataTable( {
                                  dom: 'Bfrtip',
                                  buttons: [
                                                {
                                                  extend: 'print',
                                                  title: $('h1').text(),
                                                  customize: function ( win ) {
                                                  $(win.document.body)
                                                  .css( 'font-size', '10pt' )
                                                  .prepend(
                                                          '<h3>Existencias</h3><br><h5>Tipo: <label>Unidades e Importe </label> <br> Almacen: <label>'+nomAl+'</label> <br> Periodo: <label>'+lbfecha+'</label> <br> Productos: <label >'+productos+'</label></h5>'
                                                      );                                                     
                                                  }
                                                },
                                                'excel'
                                    ],
                                    
                                    destroy: true,
                                    searching: true,
                                    deferRender: true,
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
                  var x ='';

                  $.each(data.exis, function(index, val) {

                                          var codigo          = val.codigo;
                                          var producto        = val.nombre;
                                          var unidad          = val.unidad;
                                          var moneda          = val.moneda;
                                          var fecha           = val.fecha;                                     
                                          
                                          var existencia     = val.exisUpro*1;
                                          var existenciaF     = val.exisUproF;
                                          var existenciaFF     = val.exisUproI*1;
                                          
                                          var costoUni          = 0;
                                          
                                          if(unidad == null){
                                              unidad='N/A';
                                          }  
                                          if(moneda == null){
                                              moneda='N/A';
                                          }
                                          
                                          costoUni = existenciaFF / existencia;

                                          costoUniF = costoUni.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');

                                            existenciaFFR = existenciaFF.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');

                                          /// tabla ambos
                                              x ='<tr>'+
                                                              '<td>'+codigo+'</td>'+
                                                              '<td>'+producto+'</td>'+
                                                              '<td align="right">'+existenciaF+'</td>'+                                                   
                                                              '<td align="center ">'+unidad+'</td>'+
                                                              '<td align="right">$'+((isNaN(costoUniF) ) ? 0 : costoUniF)+'</td>'+//costo
                                                              '<td align="right">$'+existenciaFFR+'</td>'+
                                                              '<td>'+moneda+'</td>'+
                                                  '</tr>';  
                                                  table.row.add($(x)).draw();  
                                      });
              }
              if(R1 =='unidades'){
                  $('#graficas').css("display","block");
                  $('#graficaBar').html('').removeClass().hide();
                  $('#graficaBar2').html('').removeClass().addClass('col-sm-12').show();
                  Morris.Bar({
                    element: 'graficaBar2',
                    //resize: true,
                    hideHover: 'auto',
                    data: data.graficaU,
                    xkey: 'y',
                    ykeys: ['b'],
                    labels: ['Series B']
                  }); 

                  var table2  = $('#table_cantidad').DataTable( {
                                  dom: 'Bfrtip',
                                  buttons: [
                                                {
                                                  extend: 'print',
                                                  title: $('h1').text(),
                                                  customize: function ( win ) {
                                                  $(win.document.body)
                                                  .css( 'font-size', '10pt' )
                                                  .prepend(
                                                          '<h3>Existencias</h3><br><h5>Tipo: <label>Unidades </label> <br> Almacen: <label>'+nomAl+'</label> <br> Periodo: <label>'+lbfecha+'</label> <br> Productos: <label >'+productos+'</label></h5>'
                                                      );                                                     
                                                  }
                                                },

                                                'excel'
                                    ],
                                    
                                    destroy: true,
                                    searching: true,
                                    deferRender: true,
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
                  table2.clear().draw();
                  var x2 ='';
                  $.each(data.exis, function(index, val) {

                                        var codigo          = val.codigo;
                                        var producto        = val.nombre;
                                        var unidad          = val.unidad;
                                        var moneda          = val.moneda;
                                        var fecha           = val.fecha;                                     
                                        
                                        var existencia     = val.exisUpro*1;
                                        var existenciaF     = val.exisUproF;
                                        var existenciaFF     = val.exisUproI*1;
                                        
                                        var costoUni          = 0;
                                        
                                        if(unidad == null){
                                            unidad='N/A';
                                        }  
                                        if(moneda == null){
                                            moneda='N/A';
                                        }
                                        
                                        costoUni = existenciaFF / existencia;
                                        costoUniF = costoUni.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');

                                          existenciaFFR = existenciaFF.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
                                        
                                        /// tabla Unidades
                                            x2 ='<tr>'+
                                                            '<td>'+codigo+'</td>'+
                                                            '<td>'+producto+'</td>'+
                                                            '<td align="right">'+existenciaF+'</td>'+
                                                            '<td>'+unidad+'</td>'+
                                                '</tr>';  
                                                table2.row.add($(x2)).draw();  
                                                                                          
                                    });

              }
              if(R1 =='importe'){
                  $('#graficas').css("display","block");
                  $('#graficaBar2').html('').removeClass().hide();
                  $('#graficaBar').html('').removeClass().addClass('col-sm-12').show();
                  Morris.Bar({
                    element: 'graficaBar',
                    //resize: true,
                    hideHover: 'auto',
                    data: data.graficaI,
                    preUnits: '$',
                    xkey: 'y',
                    ykeys: ['b'],
                    labels: ['Series B']
                  });

                  var table3  = $('#table_importe').DataTable( {
                                  dom: 'Bfrtip',
                                  buttons: [
                                                {
                                                  extend: 'print',
                                                  title: $('h1').text(),
                                                  customize: function ( win ) {
                                                  $(win.document.body)
                                                  .css( 'font-size', '10pt' )
                                                  .prepend(
                                                          '<h3>Existencias</h3><br><h5>Tipo: <label>Importe </label> <br> Almacen: <label>'+nomAl+'</label> <br> Periodo: <label>'+lbfecha+'</label> <br> Productos: <label >'+productos+'</label></h5>'
                                                      );                                                     
                                                  }
                                                },
                                                'excel'
                                    ],
                                    
                                    destroy: true,
                                    searching: true,
                                    deferRender: true,
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
                  table3.clear().draw();
                  var x3 =''; 
                  $.each(data.exis, function(index, val) {

                                        var codigo          = val.codigo;
                                        var producto        = val.nombre;
                                        var unidad          = val.unidad;
                                        var moneda          = val.moneda;
                                        var fecha           = val.fecha;                                     
                                        
                                        var existencia     = val.exisUpro*1;
                                        var existenciaF     = val.exisUproF;
                                        var existenciaFF     = val.exisUproI*1;
                                        
                                        var costoUni          = 0;
                                        
                                        if(unidad == null){
                                            unidad='N/A';
                                        }  
                                        if(moneda == null){
                                            moneda='N/A';
                                        }
                                        
                                        costoUni = existenciaFF / existencia;
                                        costoUniF = costoUni.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');

                                          existenciaFFR = existenciaFF.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
                                        
                                        /// tabla Importe
                                            x3 ='<tr>'+
                                                            '<td>'+codigo+'</td>'+
                                                            '<td>'+producto+'</td>'+
                                                            '<td align="right">$'+existenciaFFR+'</td>'+
                                                            '<td>'+moneda+'</td>'+
                                                '</tr>';  
                                                table3.row.add($(x3)).draw();                                                                                           
                                    });

              }
            //// GRAFICA MORRIS Y TABLE FIN
                                                                                                                                            
              $("#modalMensajes").modal('hide');
          })        
    }

</script>



