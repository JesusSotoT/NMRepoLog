<?php
//El archivo ajax.php hace  lo mismo que el index.php excepto que no carga el top ni el footer.
//Sirve como auxiliar de las consultas que se hacen a travez de jquery.
	error_reporting(0);
	require('libraries/getcontrollers.php');
	$controller->content(@$_GET['f']);
	
?>
