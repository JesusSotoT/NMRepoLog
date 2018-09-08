<?php
//Carga la funciones comunes top y footer
require('common.php');

class Index extends Common
{

	//Metodo que genera la Pagina default en caso de no existir la funcion
	function mainPage(){
		echo "<br />Inicial<br />";
		require("models/employees.php");
		$empleados=$Employee->getEmployees();
		
		$r=1;
		require('views/index.php');
	}

	//Metodo de prueba
	function getNada(){
		echo "<br />No hay nada<br />";
	}

}
?>