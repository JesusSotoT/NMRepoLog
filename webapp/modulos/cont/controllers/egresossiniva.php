<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/egresossinIva.php");

class EgresosSinIva extends Common
{
	public $EgresosSinIvaModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar
		$this->EgresosSinIvaModel = new EgresosSinIvaModel();
		$this->EgresosSinIvaModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->EgresosSinIvaModel->close();
	}

	function Inicial()
	{
		require('views/fiscal/diot/EgresosSinControlDeIVA.php');
	}
	function VerReporte()
	{
		$impresion = '';
		$fecha_normal = explode('-',$_POST['fecha_ini']);
		$inicio = $fecha_normal[2]."/".$fecha_normal[1]."/".$fecha_normal[0];

		$fecha_normal = explode('-',$_POST['fecha_fin']);
		$fin = $fecha_normal[2]."/".$fecha_normal[1]."/".$fecha_normal[0];

		$datos = $this->EgresosSinIvaModel->obtenerDatos($_POST['fecha_ini'],$_POST['fecha_fin']);

		if($_POST['impresion'])
		{
			$fecha_normal = explode('-',$_POST['fecha_impresion']);
			$impresion = "<b>Fecha del reporte:</b> ".$fecha_normal[2]."/".$fecha_normal[1]."/".$fecha_normal[0];
		}
		$empresa = $this->EgresosSinIvaModel->empresa();
		$logo = $this->EgresosSinIvaModel->logo();
		require('views/fiscal/diot/EgresosSinControlDeIVAReporte.php');
	}

	
}

?>
