<?php


		//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
		include("../../catalog/conexionbd.php");		
		include("../clases/cldocumento.php");
		
		error_log("[documento_guardar.php]\nEntre");

		//CSRF
		$reset_vars = false;
		include("../../catalog/clases/clcsrf.php");	
		if(!$csrf->check_valid('post')){
				$accelog_access->raise_404(); 
				exit();
		}

		$documento = new documento();
		
		$documento->setiddocumento($conexion->escapalog($_REQUEST['txtiddocumento']));
		$documento->setnombredocumento($conexion->escapalog($_REQUEST['txtnombre']));
		$documento->setobservaciones($conexion->escapalog($_REQUEST['txtobs']));

        if(isset($_REQUEST['chkorg'])){
            $documento->setutilizaidorganizacion(true);
        } else {
            $documento->setutilizaidorganizacion(0);
        }

        $documento->setlinkantes($conexion->escapalog($_REQUEST['txtlinkantes']));
        $documento->setlinkdespues($conexion->escapalog($_REQUEST['txtlinkdespues']));
		$documento->setidestructuratitulo($conexion->escapalog($_REQUEST['cmbestructuratitulo']));
		//$documento->setcolumnas($_REQUEST['txtcolumnas']);

		$a=$_REQUEST["lstdetalles"];		      		
		if(isset($_REQUEST["lstdetalles"])){
			$documento->setdetalles($_REQUEST["lstdetalles"]);			
		}
		
		$documento->guardar($conexion);
?>
