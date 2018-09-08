<?php
		//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
		include("../conexionbd.php");		
		include("../clases/clcampo.php");		
		
	
		//CSRF
		$reset_vars = false;
		include("../clases/clcsrf.php");	
		if(!$csrf->check_valid('post')){
				$accelog_access->raise_404(); 
				exit();
		}
	
		$campo = new campo();
		
		$campo->setidestructura($conexion->escapalog($_SESSION['idestructura']));
		$campo->setidcampo($conexion->escapalog($_REQUEST['txtidcampo']));
		$campo->setnombrecampo($conexion->escapalog($_REQUEST['txtnombrecampo']));
		$campo->setnombrecampousuario($conexion->escapalog($_REQUEST['txtnombrecampousuario']));
		$campo->setdescripcion($conexion->escapalog($_REQUEST['txtdescripcion']));
		$campo->setlongitud($conexion->escapalog($_REQUEST['txtlongitud']));
		$campo->settipo($conexion->escapalog($_REQUEST['cmbtipo']));
		$campo->setvalor($conexion->escapalog($_REQUEST['txtvalor']));
		$campo->setformula($conexion->escapalog($_REQUEST['txtformula']));
		
		if(empty($_REQUEST['chkrequerido'])){
			$campo->setrequerido("0");
		} else {
			$campo->setrequerido("-1");
		}
		
		$campo->setformato(trim($conexion->escapalog($_REQUEST['txtformato'])));
		$campo->setorden($conexion->escapalog($_REQUEST['txtorden']));
				
		$campo->guardar($conexion);

?>
