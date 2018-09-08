<?php
	//Carga la libreria que devuelve los controladores
	require('libraries/getcontrollers.php');

	//Carga el contenido de la vista top
	$controller->top();

	//Carga el contenido cambiante de las vistas generadas por los controladores, $_GET['f'] contiene el nombre del controlador
	$controller->content(@$_GET['f']);

	//Carga el contenido de la vista footer
	$controller->footer();
?>
