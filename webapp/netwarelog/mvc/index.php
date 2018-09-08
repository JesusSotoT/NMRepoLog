<?php

	//Carga la libreria que devuelve los controladores
	require "../../netwarelog/mvc/libraries/getcontrollers.php";

	//Carga el contenido de la vista top
	if(method_exists($controller, "top")) $controller->top();

	//Carga el contenido cambiante de las vistas generadas por los controladores, $_REQUEST['f'] contiene el nombre del controlador
	$controller->content(@$_REQUEST['f']);

	//Carga el contenido de la vista footer
	if(method_exists($controller, "footer")) $controller->footer();
	
?>
