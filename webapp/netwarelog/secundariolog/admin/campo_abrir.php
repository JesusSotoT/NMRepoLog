<?php
	session_start();
	$_SESSION['idestructura'] = $_GET['idestructura'];
	$_SESSION['nombreestructura'] = $_GET['nombreestructura'];	
	header("Location: campo.php");
?>