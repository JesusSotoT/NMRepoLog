<?php

	include("clNotificaciones.php");
	
	include("../../netwarelog/catalog/conexionbd.php");
	
	$notificaciones = new clNotificaciones();
	//echo $notificaciones->addNotif(3,"Ejemplo notificacion pa' borrar",1,1,"www.netwaremonitor.com",false,"2011-10-21","2011-10-21",$conexion);
	echo $notificaciones->delNotif(10,$conexion);
	//$notificaciones->showNotif(1,9,$conexion);
?>
<br>PRUEBA AGREGAR