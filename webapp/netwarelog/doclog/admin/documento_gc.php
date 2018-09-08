<?php
	$ocultar = $_GET['ocultar'];
	setcookie("doclog_ocultar_deshabilitados",$ocultar);
	header("Location: index.php");
?>