<?php 
	require_once("conexiondb.php");
	$id_obra =$_GET['id_obra'];
       $p =$_GET['p'];
        $pt =$_GET['pt'];
        $s =$_GET['s'];
        $st =$_GET['st'];
    //$id =$_GET['id'];
    $obra="SELECT obra FROM constru_generales where id='$id_obra';";
    $result = $mysqli->query($obra);
    $row = $result->fetch_array();
    $obra = $row['obra'];

    if($p=='0'){
    $filtro1='';

}
    else{
$filtro1=' and a.id_cliente='.$p.' ';

    }

    if($s=='x'){
    $filtro2='';
}
    else{

$filtro2=' and a.estatus='.$s.' ';
    }


    //$categorias="SELECT b.semana from constru_estimaciones_bit_cliente b inner join constru_estimaciones_cliente a on a.id_bit_cliente=b.id left join constru_recurso c on c.id=a.id_insumo WHERE a.id_obra='$id_obra' order by b.semana asc;";
    //$series="SELECT a.sestmp from constru_estimaciones_bit_cliente b inner join constru_estimaciones_cliente a on a.id_bit_cliente=b.id left join constru_recurso c on c.id=a.id_insumo WHERE a.id_obra='$id_obra' order by b.semana asc;";
    $categorias="SELECT a.id, sum(a.imp_estimacion), month(a.fecha) as mes,year(a.fecha) as year FROM constru_estimaciones_bit_cliente a where a.id_obra='$id_obra' and a.borrado=0  ".$filtro1.$filtro2."group by year,mes order by year,mes asc";
    $series="SELECT a.id, sum(a.imp_estimacion) as sestmp, month(a.fecha) as mes,year(a.fecha) as year FROM constru_estimaciones_bit_cliente a where a.id_obra='$id_obra' and a.borrado=0 ".$filtro1.$filtro2."group by year,mes order by year,mes asc";
    
    $name="Mes";
    $name1="Destajisas";
    $serie1 = "";
    $filename = "gra_estcliet".$id_obra;

 ?>

 <!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Estimaciones al Cliente</title>

        <script src="js/jquery-1.10.2.min.js"></script>
        <style type="text/css">
${demo.css}
        </style>
        <script type="text/javascript">
$(function () {
    $('#container').highcharts({
        
        title: {
            text: 'Estimaciones  al Cliente' +' <?php echo $obra; ?>'+'<br>Cliente: '+'<?php echo $pt; ?>'+' <br>Estatus: '+'<?php echo $st; ?>',
            x: -20 //center
        },
        subtitle: {
            text: '',
            x: -20
        },

        xAxis: {
            title: {
                text: 'Mes'
            },
            categories: [
            <?php
            $sql = $categorias;
            $result = $mysqli->query($sql);
            while($row = $result->fetch_array()) 
            {
             ?>
                '<?php 
switch($row[mes]){
                case 1:$row["mes"]=Enero; break;
                case 2:$row["mes"]=Febrero; break;
                case 3:$row["mes"]=Marzo; break;
                case 4:$row["mes"]=Abril; break;
                case 5:$row["mes"]=Mayo; break;
                case 6:$row["mes"]=Junio; break;
                case 7:$row["mes"]=Julio; break;
                case 8:$row["mes"]=Agosto; break;
                case 9:$row["mes"]=Septimebre; break;
                case 10:$row["mes"]=Octubre; break;
                case 11:$row["mes"]=Noviembre; break;
                case 12:$row["mes"]=Diciembre;  break;  
                case 13:$row["mes"]=septubre; break;}
                echo $row[year]."  - ".$row["mes"] ?>',
             <?php 
            }
              ?>
            ]
        },
        yAxis: {
            title: {
                text: 'Importe'
            },
            labels: {
                formatter: function () {
                    //return this.value / 1000 + 'k'; 
                    
                    return '$' + this.value;
                    
                }
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: 'MXN'
        },
        legend: {
            layout: 'vertical',
            align: 'center',
            verticalAlign: 'bottom',
            borderWidth: 0
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '${point.y:,.2f}'
                }
            }
        },

        series: [{
            name: 'Acumulado',
            data: [
            <?php
            $estimacion=0;
            $sql = $series;
            $result = $mysqli->query($sql);
            while($row = $result->fetch_array()) 
            {
                ?>
                    <?php //echo $row["retencion"]

                            $estimacion = $estimacion+$row["sestmp"]; // solo se quita la sumatoria
                            echo $estimacion;
                                          
                     ?>,      
                <?php
            }
            ?>
            ]

            },
                 {
            type: 'column',
            name: 'Estimacion',
            data: [
            <?php
            $sql = $series;
            $result = $mysqli->query($sql);
            while($row = $result->fetch_array()) 
            {
                ?>
                    <?php 

                        
                          $estimacion = $row["sestmp"];
                            echo $estimacion;
                        

                       
                     ?>,      
                <?php
            }
            ?>
            ]
        },
           
            ],

            exporting: {
                sourceWidth: 1200,
                sourceHeight: 500,
                filename: '<?php echo $filename ?>',
            },

    });
});
        </script>
    </head>
    <body>
<script src="highcharts/js/highcharts.js"></script>
<script src="highcharts/js/modules/exporting.js"></script>

<div class="row">
    <div class="col-xs-12 tablaResponsiva">
      <div class="table-responsive" id="container" style="height: 500px; margin: 0 auto">
          
      </div>
    </div>
</div> 

    </body>
</html>