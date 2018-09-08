<?php

	$nombrecampo=$_GET['nc'];
	$nombreestructura=$_GET['ne'];
	$sqlw = $_GET['sw'];
	$sqlw = str_replace("\\","",$sqlw);	

	include("conexionbd.php");
	
	if($sqlw!=""){
		
		$sqlw=" where ".$sqlw;			
		$sql = " select ".$nombrecampo." from ".$nombreestructura." ".$sqlw;
		echo $sql;
		if($conexion->existe($sql)){
			echo "<input type='hidden' id='repetido' value='1' />";
		} else {
			echo "<input type='hidden' id='repetido' value='0' />";
		}
		
	} else {
		echo "<input type='hidden' id='repetido' value='0' />";
	}

	$conexion->cerrar();
	
?>