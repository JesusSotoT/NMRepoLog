<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/fiscal_proveedores.php");

class Fiscal_Proveedores extends Common
{
	public $Fiscal_ProveedoresModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar
		$this->Fiscal_ProveedoresModel = new Fiscal_ProveedoresModel();
	}

	function Mi_metodo()
	{
		$Exercise = $this->CaptPolizasModel->getExerciseInfo();
		$Ex = $Exercise->fetch_array();
		$Abonos = $this->FiscalA29Model->GetAbonosCargos('Abono','Periodo',$Ex['PeriodoActual'],$Ex['IdEx']);
		$Cargos = $this->CaptPolizasModel->GetAbonosCargos('Cargo','Periodo',$Ex['PeriodoActual'],$Ex['IdEx']);
		require('views/captpolizas/verpolizas.php');
	}

	

	
}

?>
