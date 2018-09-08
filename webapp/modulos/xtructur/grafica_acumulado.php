<?php 
/// Consulta para Direcrto url ...  http://jsfiddle.net/gh/get/jquery/1.7.2/highslide-software/highcharts.com/tree/master/samples/highcharts/plotoptions/series-point-events-click/
require_once("conexiondb.php");

$opt =$_GET["opt"];
$id_obra =$_GET["id_obra"];

if($opt=="acumulado"){
	//echo $opt;
	$campo = "Centro_Costo";
	$where = "";
	$serie1 = "Acumulado";
}

if($opt=="COSTO DIRECTO"){
	//echo $opt;
	$campo = "costo";
	$where = "AND b.cc = 'COSTO DIRECTO'";
}

if($opt=="COSTO INDIRECTO"){
	//echo $opt;
	$campo = "Cargo";
	$where = "AND b.cc = 'COSTO INDIRECTO'";
}
if($opt=="MANO DE OBRA"){
	//echo $opt;
	$campo = "Cargo";
	$where = "AND c.costo = 'MANO DE OBRA'";
}
    if($opt=="MANO OBRA A DESTAJO"){
        //echo $opt;
        $campo = "concepto";
        $where = "AND d.cargo = 'MANO OBRA A DESTAJO'";
    }
        if($opt=="Nomina Destajista"){
                //echo $opt;
                $campo = "info";
                $where = "AND concepto = 'Nomina Destajista'";
            }
    if($opt=="MANO OBRA POR ADMINISTRACION"){
        //echo $opt;
        $campo = "concepto";
        $where = "AND d.cargo = 'MANO OBRA POR ADMINISTRACION'";
    }
    

if($opt=="MATERIALES"){
	//echo $opt;
	$campo = "Cargo";
	$where = "AND c.costo = 'MATERIALES'";
}
if($opt=="SUBCONTRATOS"){
	//echo $opt;
	$campo = "Cargo";
	$where = "AND c.costo = 'SUBCONTRATOS'";
}
    if($opt=="SUBCONTRATISTAS"){
        //echo $opt;
        $campo = "concepto";
        $where = "AND d.cargo = 'SUBCONTRATISTAS'";
    }
///INDIRECTO
    if($opt=="PERSONAL TECNICO"){
    	//echo $opt;
    	$campo = "concepto";
    	$where = "AND d.cargo = 'PERSONAL TECNICO'";
    }
    if($opt=="COMBUSTIBLE Y LUBRICANTES"){
        //echo $opt;
        $campo = "concepto";
        $where = "AND d.cargo = 'COMBUSTIBLE Y LUBRICANTES'";
    }
    if($opt=="COMUNICACIONES"){
        //echo $opt;
        $campo = "concepto";
        $where = "AND d.cargo = 'COMUNICACIONES'";
    }
    if($opt=="CONTROL DE CALIDAD Y PRUEBAS"){
        //echo $opt;
        $campo = "concepto";
        $where = "AND d.cargo = 'CONTROL DE CALIDAD Y PRUEBAS'";
    }
    if($opt=="CONTROL DE CALIDAD Y PRUEBAS"){
        //echo $opt;
        $campo = "concepto";
        $where = "AND d.cargo = 'CONTROL DE CALIDAD Y PRUEBAS'";
    }
    if($opt=="LIMPIEZA Y MANTENIMIENTO"){
        //echo $opt;
        $campo = "concepto";
        $where = "AND d.cargo = 'LIMPIEZA Y MANTENIMIENTO'";
    }
    if($opt=="LUZ, AGUA Y ENERGIA"){
        //echo $opt;
        $campo = "concepto";
        $where = "AND d.cargo = 'LUZ, AGUA Y ENERGIA'";
    }
    if($opt=="MATERIALES ALMACENABLES"){
        //echo $opt;
        $campo = "concepto";
        $where = "AND d.cargo = 'MATERIALES ALMACENABLES'";
    }
    if($opt=="MATERIALES SIN CARGO AL CLIENTE"){
        //echo $opt;
        $campo = "concepto";
        $where = "AND d.cargo = 'MATERIALES SIN CARGO AL CLIENTE'";
    }
    if($opt=="PAPELERIA Y ARTICULOS DE ESCRITORIO"){
        //echo $opt;
        $campo = "concepto";
        $where = "AND d.cargo = 'PAPELERIA Y ARTICULOS DE ESCRITORIO'";
    }
    if($opt=="PASAJES EN TRANSITO"){
        //echo $opt;
        $campo = "concepto";
        $where = "AND d.cargo = 'PASAJES EN TRANSITO'";
    }
    if($opt=="PERSONAL  ADMINISTRATIVO"){
        //echo $opt;
        $campo = "concepto";
        $where = "AND d.cargo = 'PERSONAL  ADMINISTRATIVO'";
    }
    if($opt=="RELACIONES PUBLICAS Y ATENCIONES"){
        //echo $opt;
        $campo = "concepto";
        $where = "AND d.cargo = 'RELACIONES PUBLICAS Y ATENCIONES'";
    }
    if($opt=="REPARACIONES MENORES Y REFACCIONES"){
        //echo $opt;
        $campo = "concepto";
        $where = "AND d.cargo = 'REPARACIONES MENORES Y REFACCIONES'";
    }
    if($opt=="SEGURIDAD E HIGIENE"){
        //echo $opt;
        $campo = "concepto";
        $where = "AND d.cargo = 'SEGURIDAD E HIGIENE'";
    }
    if($opt=="SERVICIO VIGILANCIA EXTERNA"){
        //echo $opt;
        $campo = "concepto";
        $where = "AND d.cargo = 'SERVICIO VIGILANCIA EXTERNA'";
    }
    if($opt=="VIATICOS Y HOSPEDAJE"){
        //echo $opt;
        $campo = "concepto";
        $where = "AND d.cargo = 'VIATICOS Y HOSPEDAJE'";
    }

//$serie1 = "jskd";
$name = $opt;   // estas variables entraran en los IF
$filename = "file";



//$where = "AND c.costo = '$campo2'";
//$where = "";

$sql="SELECT $campo, SUM(total) FROM (
	SELECT a.id cpid, a.cp Costo_Proyecto, b.id ccid, b.cc Centro_Costo, c.id costoid, c.costo Costo,  d.id cargoid, d.cargo Cargo, 'Estimacion Subcontratista' as concepto, e1.imp_estimacion as total, concat('Semana: ',e1.semana,' ESTSUB-',e1.id,' - ',f1.razon_social_sp) as info , e1.fecha FROM constru_cuentas_cp a 
	left join constru_cuentas_cc b on b.id_cp=a.id 
	left join constru_cuentas_costo c on c.id_cc=b.id 
	left join constru_cuentas_cargo d on d.id_costo=c.id 
	left join constru_estimaciones_bit_subcontratista e1 on e1.id_cc=d.id aND e1.id_obra='$id_obra' and e1.estatus=1 
	left join constru_info_sp f1 on f1.id_alta=e1.id_subcontratista where d.id>0 and e1.imp_estimacion>0 $where AND e1.imp_estimacion is not null group by e1.id
	UNION 
	SELECT a.id cpid, a.cp Costo_Proyecto, b.id ccid, b.cc Centro_Costo, c.id costoid, c.costo Costo,  d.id cargoid, d.cargo Cargo, 'Estimacion Indirectos' as concepto, e2.imp_estimacion as total, concat('Semana: ',e2.semana,' Factura: ',e2.factura) as info , e2.fecha FROM constru_cuentas_cp a
	left join constru_cuentas_cc b on b.id_cp=a.id
	left join constru_cuentas_costo c on c.id_cc=b.id
	left join constru_cuentas_cargo d on d.id_costo=c.id
	left join constru_estimaciones_bit_indirectos e2 on e2.id_cc=d.id aND e2.id_obra='$id_obra' and e2.estatus=1
	where d.id>0 and e2.imp_estimacion>0 $where AND e2.imp_estimacion is not null  group by e2.id
	UNION
	SELECT a.id cpid, a.cp Costo_Proyecto, b.id ccid, b.cc Centro_Costo, c.id costoid, c.costo Costo,  d.id cargoid, d.cargo Cargo, 'Nomina Destajista' as concepto, e3.total as total, concat('Semana: ',e3.semana,' NOMI-',e3.id,' - ',f3.nombre,' ',f3.paterno,' ',f3.materno ) as info,e3.fecha FROM constru_cuentas_cp a
	left join constru_cuentas_cc b on b.id_cp=a.id
	left join constru_cuentas_costo c on c.id_cc=b.id
	left join constru_cuentas_cargo d on d.id_costo=c.id
	left join constru_bit_nominadest e3 on e3.id_cc=d.id aND e3.id_obra='$id_obra' and e3.estatus=1
	left join constru_info_tdo f3 on f3.id_alta=e3.id_dest
	where d.id>0 and e3.total>0 $where AND e3.total is not null  group by e3.id
	UNION
	SELECT a.id cpid, a.cp Costo_Proyecto, b.id ccid, b.cc Centro_Costo, c.id costoid, c.costo Costo,  d.id cargoid, d.cargo Cargo, 'Nomina Tecnicos' as concepto, e4.total as total,concat('NOMITEC-',e4.id) as info,e4.fecha  FROM constru_cuentas_cp a
	left join constru_cuentas_cc b on b.id_cp=a.id
	left join constru_cuentas_costo c on c.id_cc=b.id
	left join constru_cuentas_cargo d on d.id_costo=c.id
	left join constru_bit_nominaca e4 on e4.id_cc=d.id aND e4.id_obra='$id_obra' and e4.estatus=1
	where d.id>0 and e4.total>0 $where AND e4.total is not null  group by e4.id
	UNION
	SELECT  a.id cpid, a.cp Costo_Proyecto, b.id ccid, b.cc Centro_Costo, c.id costoid, c.costo Costo,  d.id cargoid, d.cargo Cargo, 'Salida Materiales' as concepto, sum(xb.salio*xd.precio_compra) as total,concat('ID SALIDA-',e5.id) as info,e5.fecha  FROM constru_cuentas_cp a
	left join constru_cuentas_cc b on b.id_cp=a.id
	left join constru_cuentas_costo c on c.id_cc=b.id
	left join constru_cuentas_cargo d on d.id_costo=c.id
	left join constru_bit_salidas e5 on e5.id_cc=d.id aND e5.id_obra='$id_obra'
	left join constru_salida_almacen xb on xb.id_bit_salida=e5.id
	left join constru_requis xc on xc.id=xb.id_req
	left join constru_requisiciones xd on xd.id_requi=xc.id AND xd.id_clave=xb.id_insumo 
	where  d.id>0 $where group by e5.id
	UNION
	SELECT a.id cpid, a.cp Costo_Proyecto, b.id ccid, b.cc Centro_Costo, c.id costoid, c.costo Costo,  d.id cargoid, d.cargo Cargo, 'Caja Chica' as concepto, e6.val_fact as total, concat('Semana: ',dd.semana,' ESTCHICA-',e6.id,' ',e6.concepto,' Factura: ',e6.factura) as info,dd.fecha FROM constru_cuentas_cp a
	left join constru_cuentas_cc b on b.id_cp=a.id
	left join constru_cuentas_costo c on c.id_cc=b.id
	left join constru_cuentas_cargo d on d.id_costo=c.id
	left join constru_estimaciones_chica e6 on e6.id_cc=d.id aND e6.id_obra='$id_obra' and e6.id_bit_chica>0
	left join constru_estimaciones_bit_chica dd on dd.id=e6.id_bit_chica and dd.estatus=1
	where d.id>0 and e6.val_fact>0 and e6.val_fact is not null $where AND dd.id is not null  group by e6.id
	) T GROUP BY $campo;";

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
        var id_obra = "<?php echo $id_obra; ?>";
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Costo Acumulado',
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
            $result = $mysqli->query($sql);
            while($row = $result->fetch_array()) 
            {
                if($opt=="COSTO INDIRECTO"
                    || $opt=="MANO DE OBRA"
                    || $opt=="MATERIALES"
                    || $opt=="SUBCONTRATOS"){
             ?>
                '<?php echo $row["Cargo"] ?>',
            <?php
                }
                if($opt=="PERSONAL TECNICO" 
                    || $opt=="COMBUSTIBLE Y LUBRICANTES" 
                    || $opt=="COMUNICACIONES" 
                    || $opt=="CONTROL DE CALIDAD Y PRUEBAS"
                    || $opt=="LIMPIEZA Y MANTENIMIENTO" 
                    || $opt=="LUZ, AGUA Y ENERGIA" 
                    || $opt=="MATERIALES ALMACENABLES"
                    || $opt=="MATERIALES SIN CARGO AL CLIENTE"
                    || $opt=="PAPELERIA Y ARTICULOS DE ESCRITORIO"
                    || $opt=="PASAJES EN TRANSITO"
                    || $opt=="PERSONAL  ADMINISTRATIVO"
                    || $opt=="RELACIONES PUBLICAS Y ATENCIONES"
                    || $opt=="REPARACIONES MENORES Y REFACCIONES" 
                    || $opt=="SEGURIDAD E HIGIENE"
                    || $opt=="SERVICIO VIGILANCIA EXTERNA"
                    || $opt=="VIATICOS Y HOSPEDAJE"
                    ///
                    || $opt=="MANO OBRA A DESTAJO"
                    || $opt=="MANO OBRA POR ADMINISTRACION"
                    || $opt=="SUBCONTRATISTAS"
                    ){
            ?>    
                '<?php echo $row["concepto"] ?>',
             <?php 
             	}
             	if($opt=="acumulado"){
             ?>
                '<?php echo $row["Centro_Costo"] ?>',
             <?php 
             	}
             	if($opt=="COSTO DIRECTO"){
             		?>
                '<?php echo $row["costo"] ?>',
                <?php
                }
                if($opt=="Nomina Destajista"){
                    ?>
                '<?php echo $row["info"] ?>',
                <?php
                }
                
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
            /*
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '${point.y:,.2f}'
                }
            }
            */
            series: {
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '${point.y:,.2f}'
                },
                point: {
                    events: {
                        click: function () {
                            //alert('Category: ' + this.category + ', value: ' + this.y);
                            var nom = this.category;
                            //alert(nom);
                            if(nom=="CONTROL ANTICIPOS" 
                            || nom=="Nomina Destajista" 
                            || nom=="Salida Materiales" 
                            || nom=="ACARREOS" 
                            || nom=="Estimacion Subcontratista" 
                            || nom=="Caja Chica" 
                            || nom=="Estimacion Indirectos" 
                            || nom=="Nomina Tecnicos"){
                            return false;
                            }
                            window.open("grafica_acumulado.php?id_obra="+id_obra+"&opt="+nom);
                            //window.open('grafica_acumulado.php?id_obra='+id_obra+'&opt='+nom);
                        }
                    }
                }
            }
        },

        series: [{
            name: '<?php echo $name?>',
            data: [
            <?php
            $result = $mysqli->query($sql);
            while($row = $result->fetch_array()) 
            {
                ?>
                    <?php //echo $row["retencion"]
                            $total = $row["SUM(total)"]; // solo se quita la sumatoria
                            if($total==""){
                                $total=0;
                                echo $total;
                            }
                            echo $total;
                                          
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
