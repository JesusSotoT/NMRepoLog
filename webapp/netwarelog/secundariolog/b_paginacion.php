<?php

	session_start();

	include("conexionbd.php");

	$filas = $_GET["f"];
	$m = $_GET["m"];
		
	if($filas==0){
		if($_SESSION["secundariolog_pag_".$_SESSION['secundariolog_nombreestructura']]!=0){
			$_SESSION["secundariolog_pag_".$_SESSION['secundariolog_nombreestructura']]-=$filas_pagina;					
		}
	} else {
		if($_SESSION["secundariolog_pag_".$_SESSION['secundariolog_nombreestructura']."_limite"]!="1"){
			$_SESSION["secundariolog_pag_".$_SESSION['secundariolog_nombreestructura']]+=$filas_pagina;			
		}				
	}

	header("location: b.php?m=".$m);
	
	
	$conexion->cerrar();

?>