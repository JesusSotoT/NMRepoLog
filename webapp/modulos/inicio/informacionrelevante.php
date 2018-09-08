<?php

//Evalua version
$version=1;
$sQuery = "select FLOOR(version) versionmayor from netwarelog_version";
           $result = $conexion->consultar($sQuery);
           while($rs = $conexion->siguiente($result)){
               $version=$rs["versionmayor"];
               }
           $conexion->cerrar_consulta($result);

//Generando fechas
$fecha = date('Y-m-d');
$initDate = strtotime ( '-30 day' , strtotime ( $fecha ) ) ;
$initDate = date ( 'Y-m-d' , $initDate );

$finalDate=$fecha;
//echo "Del: ".$initDate." Al: $finalDate";

$contenedores="
            <div class=\"panel-heading\">Información Relevante </div>
                <center>
                <div id=\"grafica_ventas_dona\" style=\"height:25%\"></div>
                <div id=\"grafica_ventas_lineal\" style=\"height:25%\"></div>
                </center>
            </div>
            ";


//Ecalua su puede mostrar el Grafico 1
if ($rg1==1){
  //Obtiene Informacion de Grafico 1
  if ($version==1) {
    $sql  = "SELECT	 SUM(vp.total) AS Ventas, p.nombre AS Nombre
            FROM venta_producto vp
              INNER JOIN venta v ON v.idVenta = vp.idVenta AND v.estatus = 1
                AND v.fecha BETWEEN '" . $initDate . " 00:00:00' AND '" . $finalDate . " 23:59:59'
                INNER JOIN mrp_producto p ON p.idProducto = vp.idProducto
            GROUP BY 2 order by Ventas desc limit 10";
  }else{
    $sql="SELECT ROUND(ROUND(sum(vp.total),2),2) as Ventas, p.nombre as Nombre
          From app_pos_venta_producto vp
                INNER JOIN app_productos p ON p.id = vp.idProducto
                INNER JOIN app_pos_venta v on v.idVenta=vp.idVenta
                INNER JOIN accelog_usuarios u on u.idempleado = v.idEmpleado
                INNER JOIN mrp_sucursal s on s.idSuc=v.idSucursal
              Where v.estatus=1 AND v.fecha BETWEEN '" . $initDate . " 00:00:00' AND '" . $finalDate . " 23:59:59'
              Group By idProducto order by total desc limit 10;";
  }

  $strgraf1="<div id=\"divinfo\" class=\"prettyprint linenums\" hidden=\"true\">
      Morris.Donut({
        element: 'grafica_ventas_dona',
        data: [";
  $np=1;
  $result = $conexion->consultar($sql);
  while($rs = $conexion->siguiente($result)){
      $strgraf1.="{value: ".$rs["Ventas"].", label: '".$rs["Nombre"]."', formatted: 'Producto: ".$np." de los primeros 10' },";
      $np++;
  }
  $conexion->cerrar_consulta($result);

  $strgraf1=substr($strgraf1, 0, -1);
  $strgraf1.="],
            formatter: function (x, data) { return data.formatted; }
          });
          </div>";

  $htmlgraf.=$strgraf1;
}else{
  $htmlgraf="";
}


$htmlgraf2="";
$strgraf2="";
//Ecalua su puede mostrar el Grafico 2
if ($rg2==1){
  //Obtiene Informacion de Grafico 1
  if ($version==1){
    $sql  = "SELECT	SUM(v.monto) AS Ventas,	DATE(v.fecha) AS Fecha
              FROM venta v WHERE DATE(v.fecha)	BETWEEN '".$initDate." 00:00:00'
              AND '".$finalDate." 23:59:59'
              AND v.estatus = 1 GROUP BY 2;";
  }else{
    $sql = "SELECT  ROUND(sum(ROUND(v.monto,2)),2) as Ventas, date(v.fecha) as Fecha
            From app_pos_venta v
            Where v.estatus=1 AND v.fecha BETWEEN '".$initDate." 00:00:00' AND '".$finalDate." 23:59:59'
            group by (date(v.fecha));";
  }

  $strgraf2="<div id=\"divinfo2\" class=\"prettyprint linenums\" hidden=\"true\">
      Morris.Area({
        element: 'grafica_ventas_lineal',
        data: [";
  $np=1;
  $result = $conexion->consultar($sql);
  while($rs = $conexion->siguiente($result)){
      $strgraf2.="{x: '".$rs["Fecha"]."', y: ".$rs["Ventas"]."},";
      $np++;
  }
  $conexion->cerrar_consulta($result);
  //$strgraf2=substr($strgraf2, 0, -1);
  $strgraf2.="],
                xkey: 'x',
                ykeys: ['y'],
                labels: ['$']
              }).on('click', function(i, row){
                console.log(i, row);
              });
          </div>";
  $htmlgraf2=$strgraf2;
}else{
  $htmlgraf2="";
}


$jsgraf="<script>
        $(function () {
          eval($('#divinfo').text());
          eval($('#divinfo2').text());
          prettyPrint();
        });
        </script>";

          //eval($('#divinfo2').text());


echo $contenedores.$htmlgraf.$htmlgraf2.$jsgraf;



?>
