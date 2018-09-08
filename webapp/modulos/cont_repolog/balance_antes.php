<?php
$particion = explode(" ",$sql);
//echo $sql."<br />";
//echo $particion[8]." ".$particion[11];
$empresa = "SELECT nombreorganizacion FROM organizaciones WHERE idorganizacion=1";
$empresa = $conexion->consultar($empresa);	
$empresa = mysql_fetch_array($empresa);
echo "<table border=0 id='empresa' style='width:550px'><tr style='width:550px'><td style='text-align:center;width:550px'><b style='text-align:center;'>".$empresa['nombreorganizacion']."</b></td></tr></table>";

$anyo = "SELECT NombreEjercicio FROM cont_ejercicios WHERE id=".$particion[8];
$anyo = $conexion->consultar($anyo);	
$anyo = mysql_fetch_array($anyo);

if(trim($particion[11])==12)
{
$el13 = ' AND bb.idperiodo <> 13';
$where = 'b.idperiodo <> 13 AND';
}
else
{
$el13 = '';
$where = '';
}

$sql="SELECT 
CASE SUBSTRING(CODE,1,1) WHEN '4' 
THEN (SELECT SUM(bb.Cargos) FROM cont_view_init_balance2 bb WHERE bb.Cuenta_de_Mayor = b.Cuenta_de_Mayor AND bb.fecha BETWEEN '".$anyo['NombreEjercicio']."-01-01' AND '".$anyo['NombreEjercicio']."-".trim($particion[11])."-31')
ELSE (SELECT SUM(bb.Cargos) FROM cont_view_init_balance2 bb WHERE bb.Cuenta_de_Mayor = b.Cuenta_de_Mayor AND bb.fecha BETWEEN '2009-01-01' AND '".$anyo['NombreEjercicio']."-".trim($particion[11])."-31'".$el13.") END AS CARGOS, 
CASE SUBSTRING(CODE,1,1) WHEN '4' 
THEN (SELECT SUM(bb.Abonos) FROM cont_view_init_balance2 bb WHERE bb.Cuenta_de_Mayor = b.Cuenta_de_Mayor AND bb.fecha BETWEEN '".$anyo['NombreEjercicio']."-01-01' AND '".$anyo['NombreEjercicio']."-".trim($particion[11])."-31')
ELSE (SELECT SUM(bb.Abonos) FROM cont_view_init_balance2 bb WHERE bb.Cuenta_de_Mayor = b.Cuenta_de_Mayor AND bb.fecha BETWEEN '2009-01-01' AND '".$anyo['NombreEjercicio']."-".trim($particion[11])."-31'".$el13.") END AS ABONOS, 
(SELECT account_code FROM cont_accounts WHERE account_id = (SELECT main_father FROM cont_accounts WHERE account_code = b.Code AND removed=0)) AS CODE,b.Clasificacion AS Tipo,
(SELECT manual_code FROM cont_accounts WHERE account_id = (SELECT main_father FROM cont_accounts WHERE account_code = b.Code AND removed=0)) AS Codigo,
b.Naturaleza,(SELECT description FROM cont_accounts WHERE account_code LIKE LEFT(b.code, 3)) AS Grupo,
b.Cuenta_de_Mayor,b.idperiodo
FROM cont_view_init_balance2 b WHERE ".$where." Cuenta_de_Mayor != '' GROUP BY Cuenta_de_Mayor ORDER BY SUBSTRING(CODE,1,3),4,5";
//FROM cont_view_init_balance2 b WHERE ".$where." Cuenta_de_Mayor != '' GROUP BY Cuenta_de_Mayor ORDER BY SUBSTRING(CODE,1,1),Tipo,Grupo,4,5";
//echo $sql;
?>