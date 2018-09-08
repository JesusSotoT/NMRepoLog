<?php
		//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
		include("../conexionbd.php");		
		include("../clases/clestructura.php");
		
		$estructura = new estructura();
		
		$estructura->setidestructura($_REQUEST['txtidestructura']);
		$estructura->setnombreestructura($_REQUEST['txtnombre']);
		$estructura->setdescripcion($_REQUEST['txtdesc']);
		
		$estructura->guardar($conexion);
?>