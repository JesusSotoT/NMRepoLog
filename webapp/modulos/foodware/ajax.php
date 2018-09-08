<?php

	//ini_set("display_errors", 1); error_reporting(E_ALL);

	//Indicamos de donde proviene la petición
	$_REQUEST['_tipo'] = "ajax";

	//Cargar el gestor de configuraciones
	require('config/settings.php');

	//Cargar el gestor de sobreescritura
	require('libraries/rewrite.php');

    require "../../netwarelog/mvc/ajax.php";

?>
