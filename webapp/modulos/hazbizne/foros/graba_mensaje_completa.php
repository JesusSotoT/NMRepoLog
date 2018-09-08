<?php

	include "../../../netwarelog/catalog/conexionbd.php";
    $conexion->cerrar();

    include "../clases.php";
	
    
    $netwarstore = new clnetwarstore();
    $rfc_emisor = $netwarstore->get_rfc($bd);
    $netwarstore->disconnect();
	

	$nmdev_common = new clnmdev_common();
	
	$rfc_remitente = mysqli_real_escape_string($nmdev_common->cn, $_POST["rfc_r"]);
	$mensaje = mysqli_real_escape_string($nmdev_common->cn, $_POST["msj"]);
	
	$bd_remitente = mysqli_real_escape_string($nmdev_common->cn, $_POST["bd_r"]);

	$conversacion = $nmdev_common->graba_conversacion_completa($rfc_emisor, $rfc_remitente, $mensaje, $bd, $bd_remitente);
	$nmdev_common->disconnect();
?>
	<tr>
		<td><span><?php echo $conversacion?></span></td>
	</tr>
	