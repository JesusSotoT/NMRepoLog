<?php

	//Carga la funciones comunes top y footer
	require_once 'common.php';
	require_once dirname(__DIR__) .'/models/connection_sqli_manual.php'; // funciones mySQLi 

	class Index extends Common
	{

		//Metodo que genera la Pagina default en caso de no existir la funcion
		function principal(){
			if(!isset($_SESSION)) session_start();
			if($_SESSION["estatus_cobranza"] == 0) echo "<script>var pago_ok = true;</script>";
			require('views/index.php');
		}

	}

?>