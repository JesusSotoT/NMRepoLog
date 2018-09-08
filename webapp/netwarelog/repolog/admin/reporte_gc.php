<?php
	$ocultar = $_GET['ocultar'];
	setcookie("repolog_ocultar_deshabilitados",$ocultar);
	header("Location: index.php");
?>
