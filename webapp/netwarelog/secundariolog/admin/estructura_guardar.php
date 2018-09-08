<?php
		//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
		include("../conexionbd.php");		
		include("../clases/clestructura.php");
		
		$estructura = new estructura();
		
		$estructura->setidestructura($_REQUEST['txtidestructura']);
		$estructura->setnombreestructura($_REQUEST['txtnombre']);
		$estructura->setdescripcion($_REQUEST['txtdesc']);

        if(isset($_REQUEST['chkorg'])){
            $estructura->setutilizaidorganizacion(true);
        } else {
            $estructura->setutilizaidorganizacion(0);
        }

        $estructura->setlinkproceso($_REQUEST['txtlinkproceso']);
        $estructura->setlinkprocesoantes($_REQUEST['txtlinkprocesoantes']);
		$estructura->setcolumnas($_REQUEST['txtcolumnas']);
              
		$estructura->guardar($conexion);
?>