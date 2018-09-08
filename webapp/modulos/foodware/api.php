<?php

	//ini_set("display_errors", 1); error_reporting(E_ALL);

	/*
		Definición del comportamiento del sistema a través de una llamada a la API.
	*/

	//Indicamos de donde proviene la petición
	$_REQUEST['_tipo'] = "api";
	
	//Cargar las configuraciones generales
	require_once('config/settings.php');

	//Cargar el gestor de sobreescritura
	require('libraries/rewrite.php');

	require "../../netwarelog/mvc/api.php";
	
?>
