<?php
		//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
		include("../conexionbd.php");		
		include("../clases/clcampo.php");		
		
		session_start();
		
		$campo = new campo();
		
		$campo->setidestructura($_SESSION['idestructura']);
		$campo->setidcampo($_REQUEST['txtidcampo']);
		$campo->setnombrecampo($_REQUEST['txtnombrecampo']);
		$campo->setnombrecampousuario($_REQUEST['txtnombrecampousuario']);
		$campo->setdescripcion($_REQUEST['txtdescripcion']);
		$campo->setlongitud($_REQUEST['txtlongitud']);
		$campo->settipo($_REQUEST['cmbtipo']);
		$campo->setvalor($_REQUEST['txtvalor']);
		$campo->setformula($_REQUEST['txtformula']);
		
		if(empty($_REQUEST['chkrequerido'])){
			$campo->setrequerido("0");
		} else {
			$campo->setrequerido("-1");
		}
		
		$campo->setformato($_REQUEST['txtformato']);
		$campo->setorden($_REQUEST['txtorden']);
				
		$campo->guardar($conexion);
?>