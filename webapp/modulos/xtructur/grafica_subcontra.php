<?php 
	require_once("conexiondb.php");
	$id_obra =$_GET['id_obra'];

    $categorias="SELECT c.razon_social_sp  FROM constru_altas a 
                LEFT JOIN constru_info_sp c on c.id_alta=a.id 
                WHERE a.id_obra=$id_obra AND a.id_tipo_alta = 4 AND a.borrado=0;";
    $series="SELECT c.imp_cont FROM constru_altas a 
                LEFT JOIN constru_info_sp c on c.id_alta=a.id 
                WHERE a.id_obra='$id_obra' AND a.id_tipo_alta = 4 AND a.borrado=0;";
 
    $name="Subcontratistas";
    $name1="Destajisas";
    $serie1 = "";
    $filename = "gra_subconta".$id_obra;

 ?>

 <!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Subcontratistas</title>

        <script src="js/jquery-1.10.2.min.js"></script>
        <style type="text/css">
${demo.css}
        </style>
        <script type="text/javascript">
$(function () {
    $('#container').highcharts({
        
        chart: {
            type: 'column'
        },
        title: {
            text: 'Subcontratistas',
            x: -20 //center
        },
        subtitle: {
            text: '',
            x: -20
        },

        xAxis: {
            title: {
                text: '<?php echo $serie1;?>'
            },
            categories: [
            <?php
            $sql = $categorias;
            $result = $mysqli->query($sql);
            while($row = $result->fetch_array()) 
            {
             ?>
                '<?php echo $row["razon_social_sp"] ?>',
             <?php 
            }
              ?>
            ]
        },
        yAxis: {
            title: {
                text: 'Retencion'
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
            name: '<?php echo $name?>',
            data: [
            <?php
            $sql = $series;
            $result = $mysqli->query($sql);
            while($row = $result->fetch_array()) 
            {
                ?>
                    <?php //echo $row["retencion"]

                            $estimacion = $row["imp_cont"]; // solo se quita la sumatoria
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

<div id="container" style="min-width: 800px; height: 500px; margin: 0 auto"></div>

    </body>
</html>