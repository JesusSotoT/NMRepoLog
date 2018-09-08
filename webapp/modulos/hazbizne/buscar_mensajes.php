<?php
	include "../../netwarelog/catalog/conexionbd.php";
    $conexion->cerrar();
	
	include "clases.php";
	
	$nmdev_common = new clnmdev_common();
	$contactos = $nmdev_common->revisar_mensajes($bd);
	$nmdev_common->disconnect();

	echo $contactos;
?>
