<?php

	include "../../netwarelog/catalog/conexionbd.php";
    $conexion->cerrar();
	
	include "clases.php";
	
	$dev = new clnmdev();
	$idEvento = mysqli_real_escape_string($dev->cn, $_POST["sidEvento"]);
	$idAsistente = mysqli_real_escape_string($dev->cn, $_POST["sidAsistente"]);
	$idEmpresa = mysqli_real_escape_string($dev->cn, $_POST["sidEmpresa"]);
	$dateFechaRegistro = mysqli_real_escape_string($dev->cn, $_POST["sdateFechaRegistro"]);
	$registro = $dev->registra_asistentes($idEvento, $idAsistente, $idEmpresa, $dateFechaRegistro);

	$dev->disconnect();

?>

