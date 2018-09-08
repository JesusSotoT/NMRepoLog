<?php
		//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
		include("../conexionbd.php");		
		include("../clases/clcampo.php");		
		
		$campo = new campo();
		$campo->setidcampo($_GET['idcampo']);
		
		$campo->eliminar($conexion);
				
?>