<?php

	include "../../netwarelog/catalog/conexionbd.php";
    $conexion->cerrar();
	
	include "../../modulos/hazbizne/clases.php";
	
	$netwarstorep = new clnetwarstore_p();
	$codigo = mysqli_real_escape_string($netwarstorep->cn, $_POST["code"]);
	$licencia = $netwarstorep->desactiva_licencias($codigo);
	$netwarstorep->disconnect();
?>

