<?php

	include "../../../netwarelog/catalog/conexionbd.php";
    $conexion->cerrar();

    include "../clases.php";
	
	$nmdev_common = new clnmdev_common();
	$mensaje = mysqli_real_escape_string($nmdev_common->cn, $_POST["msj"]);
	$contacto = mysqli_real_escape_string($nmdev_common->cn, $_POST["contact"]);
	$conversacion = $nmdev_common->graba_conversacion($bd, $contacto, $mensaje);
	$nmdev_common->disconnect();
?>

<div id="respuesta_hijo">
	<span>Consulta: <?php echo $conversacion?></span>
</div>