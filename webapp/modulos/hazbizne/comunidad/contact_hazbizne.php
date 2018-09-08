<?php
	include "../../../netwarelog/catalog/conexionbd.php";
	$conexion->cerrar();	

	include "../clases.php";
	$nmdev_common = new clnmdev_common();
	$bd2 = mysqli_real_escape_string($nmdev_common->cn, $_POST["nombre_db_2"]);
	$respuesta = $nmdev_common->contact_hazbizne($bd,$bd2);
	$nmdev_common->disconnect();
	echo $respuesta;
?>
