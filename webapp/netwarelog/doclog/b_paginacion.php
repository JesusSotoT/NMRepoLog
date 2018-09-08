<?php

	session_start();

	include("../catalog/conexionbd.php");

	$filas = $_GET["f"];
	$m = $_GET["m"];
		
	if($filas==0){
		if($_SESSION["pag_".$_SESSION['nombreestructura']]!=0){
			$_SESSION["pag_".$_SESSION['nombreestructura']]-=$filas_pagina;					
		}
	} else {
		if($_SESSION["pag_".$_SESSION['nombreestructura']."_limite"]!="1"){
			$_SESSION["pag_".$_SESSION['nombreestructura']]+=$filas_pagina;			
		}				
	}

	header("location: b.php?m=".$m);
	
	
	$conexion->cerrar();

?>