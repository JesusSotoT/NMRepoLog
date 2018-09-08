<?php

		include "../../netwarelog/webconfig.php";
		include "../../netwarelog/catalog/conexionbd.php";

		//Verifica si este usuario puede seleccionar perfiles
    $sQuery = "SELECT usuario,clave FROM accelog_usuarios";
    $result = $conexion->consultar($sQuery);
    while($rs = $conexion->siguiente($result)){
    	
			$clave = $conexion->fencripta($rs{"clave"},$accelog_salt);

			$sql = "update accelog_usuarios set clave='".$clave."' where usuario='".$rs{"usuario"}."' ";
			$conexion->consultar($sql,false);

			$sql = "update employees set password='".$clave."' where username='".$rs{"usuario"}."'";
			$conexion->consultar($sql,false);

			echo "<br>Encriptado >> <b>".$rs{"usuario"}."</b>";

		}
		$conexion->cerrar_consulta($result);

		$conexion->cerrar();

?> 
<br><br> Proceso concluido.
