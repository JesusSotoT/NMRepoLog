<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/implementacion.php");

class Implementacion extends Common
{
	public $ImplementacionModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar

		$this->ImplementacionModel = new ImplementacionModel();
		$this->ImplementacionModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->ImplementacionModel->close();
	}

	function implementacion()
	{
		require('views/implementacion/implementacion.php');
	}
	function cargarInicial(){
		$cargarInicial = $this->ImplementacionModel->cargarInicial();
		echo json_encode($cargarInicial);
	}
}


?>