<?php

	//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
	include("catalog/conexionbd.php");

	session_start();
	$idorg=$_SESSION["accelog_idorganizacion"];
	$descarga = $_GET["d"]; 	//Sí d=1 entonces hacer stream para descargar archivo
	$archivo = $_GET["f"]; 		//Nombre del archivo

	if(!empty($archivo)){
		
		$estructura = $_GET["ne"]; 	//Nombre del campo que contiene el archivo	
		$directorio = "archivos/".$idorg."/".$estructura."/".$archivo;


		if($descarga==1){

			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"$archivo\"");
			$fp = fopen($directorio,"r");
			fpassthru($fp);
			fclose($fp);		

		} else {

			header("Location: ".$directorio);

		}	
			
	} else {
		
		echo "<b><font face='tahoma' size=1 color=gray>Archivo no encontrado.</font></b>";
		
	}
	
	$conexion->cerrar();


?>