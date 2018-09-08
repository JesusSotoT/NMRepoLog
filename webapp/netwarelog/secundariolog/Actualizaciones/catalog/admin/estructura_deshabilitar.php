<?php
		//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
		include("../conexionbd.php");
		include("../clases/clestructura.php");
		
		$estructura = new estructura();
		$estructura->setidestructura($_GET['idestructura']);		
		$estructura->deshabilitar($conexion);
?>