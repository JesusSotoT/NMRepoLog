<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/fiscal_diot.php");

class Fiscal_Diot extends Common
{
	public $Fiscal_DiotModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar
		$this->Fiscal_DiotModel = new Fiscal_DiotModel();
	}

	function EgresosSinControlDeIVA()
	{
		require('views/fiscal/diot/EgresosSinControlDeIVA.php');
	}
	function AuxiliarFormatoA29()
	{
		require('views/fiscal/diot/AuxiliarFormatoA29.php');
	}
	
}

?>
