<?php

	include "../../../netwarelog/catalog/conexionbd.php";
    $conexion->cerrar();
	
	include "../clases.php";
	
	$nmdev_common = new clnmdev_common();
	$contacto = mysqli_real_escape_string($nmdev_common->cn, $_POST["user"]);
	$conversacion = $nmdev_common->get_conversacion($bd, $contacto);
	$nmdev_common->marcar_conversacion($bd, $contacto);
	$nmdev_common->disconnect();
?>
	

<?php
	
	foreach ($conversacion as $linea) {
		$cadena = "Usuario";
		?><div class="lineas"><span>[<?php echo $linea["fecha"]?>] </span><span style="font-weight:bold"><?php echo $linea["bd_emisor"].": "?></span><span><?php echo $linea["mensaje"]?></span></div>
		<?php
	}
?>

