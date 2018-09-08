<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Graficas de compras</title>
    <link rel="stylesheet" href="">
</head>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
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
<br> 
<div class="container well" id="divfiltro">
    <div class="row">
        <div class="col-xs-12 col-md-12">
           <h3>Graficas de compras</h3>
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

                <label>Sucursal</label><br>
                <select id="producto" class="form-control">
                    <option value="0">-Todas-</option>                        
                </select>
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
               
                <label>Ordenar segun variable</label><br>
                <select id="ordenar" class="form-control">
                    <option value="1">Productos mas comprados</option> 
                    <option value="2">Productos menos comprados</option>                        
                </select>
            </div>
                <div class="col-sm-6">
                    <div class="row">&nbsp;</div>
                    <label>Reporte</label><br>
                    <input style="cursor:pointer;" type="radio" name="rep" id="R1ambos" value="1" checked="checked"> Unidades<br>
                    <input style="cursor:pointer;" type="radio" name="rep" id="R1unidades" value="2" > Importe compras<br>
              
                </div>
                
                <div class="row">&nbsp;</div>
                <div class="row">&nbsp;</div>
                <div class="col-sm-12">
                    <button class="btn btn-primary col-sm-2 col-md-offset-5" onclick="generarReporte();">Generar</button><br> 
                </div>

            </div>
        </div>

    </div>
</div>

<div id="grafica" class="container" style="padding:0;display:none;">
    <div class="panel panel-default">
        <div class="panel-body" style="font-size:13px;">
            <div class="row"><span class="col-sm-12"><b>Reporte grafico de compras</b></span></div>
            <div class="row"><span id="titreporte" class="col-sm-12">Los 5 productos mas comprados</span><br></div>
            <div class="row">&nbsp;</div>
            <div class="row"><span class="col-sm-1"><b>Desde:</b></span><span id="titdesde" class="col-sm-11">2016-01-01</span></div>
            <div class="row"><span class="col-sm-1"><b>Hasta:</b></span><span id="tithasta" class="col-sm-11">2017-01-01</span></div>
            <div class="row"><span class="col-sm-1"><b>Visto por:</b></span><span id="tittipo" class="col-sm-11">Unidades</span></div>
            
        </div>
    </div>
    <div id="chart-bar"></div>
    <div class="row">&nbsp;</div>
    <div class="row">&nbsp;</div>
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

    function generarReporte(){
        $('#grafica').css('display','none');
        desde = $('#desde').val();
        hasta = $('#hasta').val();
        ordenar = $('#ordenar').val();
        radio = $('input[name=rep]:checked').val();

        if(desde==''){
            alert('La fecha de incio del reporte esta vacia');
            return false;
        }
        if(hasta==''){
            alert('La fecha de fin del reporte esta vacia');
            return false;
        }

        $.ajax({
            url:"ajax.php?c=reportes_compras&f=a_reporte",
            type: 'POST',
            dataType: 'JSON',
            data:{
                desde:desde,
                hasta:hasta,
                ordenar:ordenar,
                radio:radio
            },
            success: function(r){
                if(r.success==1){
                    $('#chart-bar').html('');
                    if(ordenar=='1'){
                        msgtitr='Los 5 productos mas comprados';
                    }
                    if(ordenar=='2'){
                        msgtitr='Los 5 productos menos comprados';
                    }

                    if(radio=='1'){
                        vp='Unidades';
                        txtlabel='Cantidad comprado ';
                    }
                    if(radio=='2'){
                        vp='Importes';
                        txtlabel='Importe comprado $';
                    }

                    $('#titreporte').text(msgtitr);
                    $('#titdesde').text(desde);
                    $('#tithasta').text(hasta);
                    $('#tittipo').text(vp);
                    

                      $('#grafica').css('display','block');

       
                    var MorrisBar = Morris.Bar({
                        barGap:4,
  barSizeRatio:0.55,
                        element: 'chart-bar',
                        data: r.datos,
                        xkey: 'ext',
                        ykeys: ['total'],
                        labels: [txtlabel],
                        hideHover: 'auto',
                        resize: true,
                        xLabelFormat: function(d)
                        {
                            return d.src.nombre;
                        },
                        barColors: ["#135a97"],
                        hoverCallback: function(index, options, content)
                        {
                            return content;
                        }
                    });
                

                }
                
            }
        });

    }
</script>