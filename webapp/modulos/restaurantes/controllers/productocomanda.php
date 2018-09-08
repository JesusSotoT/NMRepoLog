<?php 
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/productocomanda.php");

class productoComanda extends Common {
	public $productocomandaModel;

	function __construct() {
        //Se crea el objeto que instancia al modelo que se va a utilizar
		$this->productocomandaModel = new productocomandaModel();
	}

	function borrarProductoTemporal(){
		$lugares = $this->productocomandaModel->borrar($_POST["codigo"]);

		echo $lugares;
	}
}
?>