<?php

	include "../../../netwarelog/catalog/conexionbd.php";
    $conexion->cerrar();

    include "../clases.php";
	
	$nmdev_common = new clnmdev_common();
	$mensaje = mysqli_real_escape_string($nmdev_common->cn, $_POST["msj"]);
	$correo = mysqli_real_escape_string($nmdev_common->cn, $_POST["correo"]);
	$registro = mysqli_real_escape_string($nmdev_common->cn, $_POST["registro"]);
	$based = mysqli_real_escape_string($nmdev_common->cn, $_POST["based"]);
	$conversacion = $nmdev_common->graba_conversacion_busqueda($bd, $mensaje, $registro, $based);
	$nmdev_common->disconnect();
?>



