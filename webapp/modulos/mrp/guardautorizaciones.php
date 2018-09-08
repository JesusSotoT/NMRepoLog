<?php
include("../../netwarelog/catalog/conexionbd.php");


$namefiles=explode("*",$_POST["archivos"]);
foreach($namefiles as $file)
{
	if(strlen($file)>2)
	{	
	mysql_query("INSERT INTO mrp_proveedor_autorizaciones(archivo,idProveedor,tipo) VALUES ('".$file."',".$_POST["proveedor"].",".$_POST["opcion"].")");
	//echo "INSERT INTO mrp_proveedor_autorizaciones(archivo,idProveedor,tipo) VALUES ('".$file."',".$_POST["proveedor"].",".$_POST["opcion"].")";
	}
	}

?>