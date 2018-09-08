<?php
	$ocultar = $_GET['ocultar'];
	setcookie("catalog_ocultar_deshabilitados",$ocultar);
	header("Location: index.php");
?>