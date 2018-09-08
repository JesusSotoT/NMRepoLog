<?php

	//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
	include("catalog/conexionbd.php");

	$descarga = $_GET["d"];
	$campo = $_GET["nc"]; //Nombre del campo que contiene el archivo
	$estructura = $_GET["ne"]; //Nombre del campo que contiene el archivo
	$sql = $_GET["s"]; //SQL con el que se abre el registro
	$sql = str_replace("\\","",$sql);	
	$sql = "select ".$campo.",".$campo."_name, ".$campo."_size, ".$campo."_type from ".$estructura." where ".$sql;
		
	//echo "recibi --- ".$sql." -- ".$campo." -- ".$estructura;
	
	
	$result = $conexion->consultar($sql);	
	if($rs = $conexion->siguiente($result)){		
			
		if($descarga==1){
			header("Content-type: application/octet-stream");
		} else {
			header("Content-type: ".$rs{$campo."_type"});
		}
			
		header("Content-lenght: ".$rs{$campo."_size"});
		header("Content-Disposition: inline; filename=".$rs{$campo."_name"});
				
		echo $rs{$campo};		
		
	}
	
	
	$conexion->cerrar();


?>