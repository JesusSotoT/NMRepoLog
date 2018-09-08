<?php
		
		include_once('webconfig.php');
		global $servidor, $usuariobd, $clavebd, $bd, $tipobd, $super_idorganizacion, $accelog_salt, $campo_idorganizacion, $tabla_empleados, $campo_idempleado, $tabla_organizacion, $campo_nombre_org;

		include_once('libraries/clconexion.php');
		
		$conexion = new conection($servidor, $usuariobd, $clavebd, $bd, $tipobd);
		
?>
