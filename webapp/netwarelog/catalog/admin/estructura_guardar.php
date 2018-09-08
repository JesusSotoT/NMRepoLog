<?php
		//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
		include("../conexionbd.php");		
		include("../clases/clestructura.php");

		//CSRF
		//session_start();
		$reset_vars = false;
		include("../clases/clcsrf.php");	
		if(!$csrf->check_valid('post')){
				$accelog_access->raise_404(); 
				exit();
		}

		$estructura = new estructura();
		
		$estructura->setidestructura($conexion->escapalog($_REQUEST['txtidestructura']));
		$estructura->setnombreestructura($conexion->escapalog($_REQUEST['txtnombre']));
		$estructura->setdescripcion($conexion->escapalog($_REQUEST['txtdesc']));

        if(isset($_REQUEST['chkorg'])){
            $estructura->setutilizaidorganizacion(true);
        } else {
            $estructura->setutilizaidorganizacion(0);
        }

    $estructura->setlinkproceso($conexion->escapalog($_REQUEST['txtlinkproceso']));
    $estructura->setlinkprocesoantes($conexion->escapalog($_REQUEST['txtlinkprocesoantes']));
		$estructura->setcolumnas($conexion->escapalog($_REQUEST['txtcolumnas']));
              
		$estructura->guardar($conexion);
?>
