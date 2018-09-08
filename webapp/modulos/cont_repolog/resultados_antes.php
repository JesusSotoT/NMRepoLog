<?php
$particion = explode(" ",$sql);
//echo $sql."<br />";
//echo $particion[8]."/".$particion[11]."/".$particion[15]."/".$particion[19]."?";
$empresa = "SELECT nombreorganizacion FROM organizaciones WHERE idorganizacion=1";
$empresa = $conexion->consultar($empresa);	
$empresa = mysql_fetch_array($empresa);
echo "<table border=0 id='empresa' style='width:550px'><tr style='width:550px'><td style='text-align:center;width:550px'><b style='text-align:center;'>".$empresa['nombreorganizacion']."</b></td></tr></table>";

$anyo = "SELECT NombreEjercicio FROM cont_ejercicios WHERE id=".$particion[8];
$anyo = $conexion->consultar($anyo);	
$anyo = mysql_fetch_array($anyo);

$sucursal_CA = ''; //Buscar por sucursales en Cargos y Abonos
$sucursal_W = ''; //Buscar por sucursales en el Where
if(intval($particion[15]))
{
	$sucursal_CA =  "AND bb.idsucursal = ".$particion[15];
	$sucursal_W =  "AND b.idsucursal = ".$particion[15];
}

if(intval($particion[19]))
{
	$sucursal_CA .=  "AND bb.idsegmento = ".$particion[19];
	$sucursal_W .=  "AND b.idsegmento = ".$particion[19];
}

$sql="SELECT 
(SELECT SUM(bb.Cargos) FROM cont_view_init_balance2 bb WHERE bb.Cuenta_de_Mayor = b.Cuenta_de_Mayor AND bb.fecha BETWEEN '".$anyo['NombreEjercicio']."-01-01' AND '".$anyo['NombreEjercicio']."-".trim($particion[11])."-31' $sucursal_CA) AS CARGOSACUMULADOS, 
(SELECT SUM(bb.Abonos) FROM cont_view_init_balance2 bb WHERE bb.Cuenta_de_Mayor = b.Cuenta_de_Mayor AND bb.fecha BETWEEN '".$anyo['NombreEjercicio']."-01-01' AND '".$anyo['NombreEjercicio']."-".trim($particion[11])."-31' $sucursal_CA) AS ABONOSACUMULADOS,

(SELECT SUM(bb.Cargos) FROM cont_view_init_balance2 bb WHERE bb.Cuenta_de_Mayor = b.Cuenta_de_Mayor AND bb.fecha BETWEEN '".$anyo['NombreEjercicio']."-".trim($particion[11])."-01' AND '".$anyo['NombreEjercicio']."-".trim($particion[11])."-31' $sucursal_CA) AS CARGOS, 
(SELECT SUM(bb.Abonos) FROM cont_view_init_balance2 bb WHERE bb.Cuenta_de_Mayor = b.Cuenta_de_Mayor AND bb.fecha BETWEEN '".$anyo['NombreEjercicio']."-".trim($particion[11])."-01' AND '".$anyo['NombreEjercicio']."-".trim($particion[11])."-31' $sucursal_CA) AS ABONOS,

(SELECT account_code FROM cont_accounts WHERE account_id = (SELECT main_father FROM cont_accounts WHERE account_code = b.Code AND removed=0)) AS CODE,b.Clasificacion AS Tipo,
(SELECT manual_code FROM cont_accounts WHERE account_id = (SELECT main_father FROM cont_accounts WHERE account_code = b.Code AND removed=0)) AS Codigo,
b.Naturaleza,(SELECT description FROM cont_accounts WHERE account_code LIKE LEFT(b.code, 3)) AS Grupo,
b.Cuenta_de_Mayor 
FROM cont_view_init_balance2 b 
WHERE b.Code LIKE '4.1%' OR b.Code LIKE '4.2%' $sucursal_W
GROUP BY Cuenta_de_Mayor ORDER BY Grupo DESC,Codigo ASC";
//echo $sql;
?>