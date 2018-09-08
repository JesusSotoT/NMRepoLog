<?php

	include("../conexionbd.php");
	include("../clases/clcampo.php");
	
	$campo = new campo();
	$campo->setidcampo($_GET["idcampo"]);
	
	if($_GET['llave']==1){
		$campo->marcar_llave($conexion);
	} else {
		$campo->desmarcar_llave($conexion);		
	}
	
	
	
	
?>