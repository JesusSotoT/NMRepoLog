<?php 
///ch@iSystem//
require_once("conexiondb.php");
$id_destajista =$_GET["id_des"];
$id_subcontratista =$_GET["id_sub"];
$id_obra =$_GET["id_obra"];
$opt =$_GET["opt"];

if($opt=='des_todos'){
    $categorias="SELECT concat('IDDES-',a.id_destajista,' ',b.nombre,' ',b.paterno,' ',b.materno) as serie, sum(a.retencion) as retencion FROM constru_estimaciones_bit_destajista a left join constru_info_tdo b on b.id_alta=a.id_destajista WHERE a.id_obra='$id_obra' AND estatus='1' group by a.id_destajista;";
    $series="SELECT concat('IDDES-',a.id_destajista,' ',b.nombre,' ',b.paterno,' ',b.materno) as serie, sum(a.retencion) as retencion FROM constru_estimaciones_bit_destajista a left join constru_info_tdo b on b.id_alta=a.id_destajista WHERE a.id_obra='$id_obra' AND estatus='1' group by a.id_destajista;";
    $name="Destajisas (Acumulado)";
    $name1="Destajisas";
    $serie1 = "";
    $filename = "destajisas_obra".$id_obra;
}
if($opt=='sub_todos'){
    $categorias="SELECT concat('IDSUB-',a.id_subcontratista,' ',b.razon_social_sp) as serie, sum(a.retencion) as retencion FROM constru_estimaciones_bit_subcontratista a left join constru_info_sp b on b.id_alta=a.id_subcontratista WHERE a.id_obra='$id_obra' AND estatus='1' group by a.id_subcontratista;";
    $series="SELECT concat('IDSUB-',a.id_subcontratista,' ',b.razon_social_sp) as serie, sum(a.retencion) as retencion FROM constru_estimaciones_bit_subcontratista a left join constru_info_sp b on b.id_alta=a.id_subcontratista WHERE a.id_obra='$id_obra' AND estatus='1' group by a.id_subcontratista;";
    $name="Subcontratistas (Acumulado)";
    $name1="Subcontratistas";
    $serie1 = "";
    $filename = "subcontratista_obra".$id_obra;
}
if($opt=='sub_uno'){
    $categorias="SELECT a.semana serie FROM constru_estimaciones_bit_subcontratista a where a.id_subcontratista = '$id_subcontratista';";
    $series="SELECT a.retencion FROM constru_estimaciones_bit_subcontratista a where a.id_subcontratista = '$id_subcontratista';";
    $sql="SELECT concat('IDSUB-',a.id_subcontratista,' ',b.razon_social_sp) as nombre FROM constru_estimaciones_bit_subcontratista a left join constru_info_sp b on b.id_alta=a.id_subcontratista WHERE  a.id_subcontratista='$id_subcontratista' and estatus='1';";
    $result = $mysqli->query($sql);
    $row = $result->fetch_array();
    $name = $row['nombre']."(Acumulado)";
    $name1 = $row['nombre']."(Retencion)";
    $serie1 = "Semana";
    $filename ="subcontratista_id".$id_subcontratista;
}
if($opt=='des_uno'){
    $categorias="SELECT a.semana serie FROM constru_estimaciones_bit_destajista a where a.id_destajista = '$id_destajista';";
    $series="SELECT a.retencion FROM constru_estimaciones_bit_destajista a where a.id_destajista = '$id_destajista';";
    $sql="SELECT  concat('IDDES-',a.id_destajista,' ',b.nombre,' ',b.paterno,' ',b.materno) as nombre FROM constru_estimaciones_bit_destajista a left join constru_info_tdo b on b.id_alta=a.id_destajista where a.id_destajista = '$id_destajista' and estatus='1';";
    $result = $mysqli->query($sql);
    $row = $result->fetch_array();
    $name = $row['nombre']." (Acumulado)";
    $name1 = $row['nombre']." (Retencion)";
    $serie1 = "Semana";
    $filename = "destajista_id".$id_destajista;
}

 ?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Retenciones y Fondos de Garantia</title>

        <script src="js/jquery-1.10.2.min.js"></script>
        <style type="text/css">
${demo.css}
        </style>
        <script type="text/javascript">
$(function () {
    $('#container').highcharts({
        
        title: {
            text: 'Retenciones y Fondos de Garantia',
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
                '<?php echo $row["serie"] ?>',
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
                        if($opt=='des_todos' or $opt=='sub_todos'){
                            $retencion1 = $retencion1 + $row["retencion"]; // solo se quita la sumatoria
                            echo $retencion1;
                        }
                        if($opt=='des_uno' or $opt=='sub_uno'){
                            $retencion1 = $retencion1 + $row["retencion"];
                            echo $retencion1;
                        }
                       
                     ?>,      
                <?php
            }
            ?>
            ]

            },
            <?php if($opt=='des_uno' or $opt=='sub_uno' or $opt=='des_todos' or $opt=='sub_todos'){ //se eliminan las 2 ultimas condiciones?> 
            {
            type: 'column',
            name: '<?php echo $name1?>',
            data: [
            <?php
            $sql = $series;
            $result = $mysqli->query($sql);
            while($row = $result->fetch_array()) 
            {
                ?>
                    <?php 

                        if($opt=='des_uno' or $opt=='sub_uno'){
                            $retencion1 = $row["retencion"];
                            echo $retencion1;
                        }

                       
                     ?>,      
                <?php
            }
            ?>
            ]
        },
        <?php } ?> 
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
