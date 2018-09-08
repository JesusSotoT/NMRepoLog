<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/ejemplo.php");

class Ejemplo extends Common
{
	public $EjemploModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar

		$this->EjemploModel = new EjemploModel();
		$this->EjemploModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->EjemploModel->close();
	}

	//Funcion que genera la vista inicial donde se presentan las polizas del periodo
	function lista()
	{
		$num = 32;
		$lista = $this->EjemploModel->lista();
		$NombreCuenta = $this->EjemploModel->cuenta($num);
		$hola = "Hola Mundo";
		require('views/ejemplo/pantalla.php');
	}
}


?>
