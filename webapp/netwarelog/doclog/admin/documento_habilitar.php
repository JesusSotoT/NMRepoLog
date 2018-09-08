<?php
		//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
		include("../../catalog/conexionbd.php");
		include("../clases/cldocumento.php");
		
		$documento = new documento();
		$documento->setiddocumento($_GET['iddocumento']);		
		$documento->habilitar($conexion);
?>