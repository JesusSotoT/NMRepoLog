<?php
/*$cfecha=$_POST['i323_1_3']."-".$_POST['i323_1_1']."-".$_POST['i323_1_2']." ".$_POST['i323_1t'];
$sql = "update admin_pagoscxc set fecha='".$cfecha."' where idpago=''";
*/
$limpia=array("idcxc =","'","\\");
$conexion->consultar("Delete FROM `admin_cxcpagos` where idcxc=".str_replace($limpia,"",$_POST["sw_m"]));

for($i=1;$i<=$_POST["txt_filasdetalles"];$i++)
{
	if(is_numeric($_POST['i323_'.$i.'_3']))
{
$cfecha=$_POST['i323_'.$i.'_3']."-".$_POST['i323_'.$i.'_1']."-".$_POST['i323_'.$i.'_2']." ".$_POST['i323_'.$i.'t'];

$insertq="INSERT INTO  `admin_cxcpagos` (`idcxc`, `idpago`, `fechapago`, `pago`, `idformapago`, `referencia`, `idcuenta`, `observaciones`) VALUES (".str_replace($limpia,"",$_POST["sw_m"]).", NULL, '".$cfecha."', '".$_POST["i324_".$i.""]."', '".$_POST["i325_".$i.""]."', '".$_POST["i328_".$i.""]."', '".$_POST["i329_".$i.""]."', '".$_POST["i338_".$i.""]."');";

//echo $insertq;
//echo "<br>";

$conexion->consultar($insertq);
}
}

/*
echo "<pre>";
print_r($_POST);
echo "</pre>";
*/
?>