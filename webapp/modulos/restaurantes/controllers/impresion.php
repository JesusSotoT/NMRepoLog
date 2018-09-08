<?php
/*ini_set('display_errors', 1);
error_reporting(E_ALL);*/
require ('common.php');
require ("models/impresion.php");

class impresion extends Common {
	public $impresionModel;

	function __construct() {
		$this -> impresionModel = new impresionModel();
	}

	function insertar() {
		$resultado = $this -> impresionModel -> insertar($_REQUEST);
		echo $resultado;
	}

	function consultar() {
		$resultado = $this -> impresionModel -> consultar($_REQUEST);
		echo $resultado;
	}

	function borrar() {
		$resultado = $this -> impresionModel -> borrar($_REQUEST);
		echo $resultado;
	}

	function insertarVinculo() {
		$resultado = $this -> impresionModel -> insertarVinculo($_REQUEST);
		echo $resultado;
	}

	function leerVinculos() {
		$resultado = $this -> impresionModel -> leerVinculos($_REQUEST);
		echo $resultado;
	}

	function borrarVinculo() {
		$resultado = $this -> impresionModel -> borrarVinculo($_REQUEST);
		echo $resultado;
	}

	function leerAreas() {
		$resultado = $this -> impresionModel -> leerAreas($_REQUEST);
		echo $resultado;
	}
}

?>