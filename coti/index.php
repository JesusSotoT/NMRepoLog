<?php
    require "libraries/access.php";
    
    //Desbloquea la seguridad del modulo, para que el desarrollador haga pruebas, 
    //cuando termines vuelve a comentar esta variable
    
    $bloqueo = 0;

		//Carga la libreria que devuelve los controladores
	require "libraries/getcontrollers.php";

	//Carga el contenido de la vista top
	$controller->top();

	//Carga el contenido cambiante de las vistas generadas por los controladores, $_GET['f'] contiene el nombre del controlador
	$controller->content(@$_GET['f']);

	//Carga el contenido de la vista footer
	$controller->footer();
?>

