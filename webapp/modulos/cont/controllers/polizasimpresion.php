<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/polizasImpresion.php");

class polizasImpresion extends Common
{
	public $polizasImpresionModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar
		$this->polizasImpresion = new polizasImpresionModel();
		$this->polizasImpresion->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->polizasImpresion->close();
	}

	function Inicial()
	{
		$tipo = $this->polizasImpresion->tipoPoliza();
		require('views/captpolizas/polizasImpresion.php');
	}
	function VerReporte()
	{
		$pol_id = $_GET['pol_id'];
		$fecha_normal = explode('-',$_POST['fecha_ini']);
		$inicio = $fecha_normal[2]."/".$fecha_normal[1]."/".$fecha_normal[0];
		$fecha_normal = explode('-',$_POST['fecha_fin']);
		$fin = $fecha_normal[2]."/".$fecha_normal[1]."/".$fecha_normal[0];
		$p13 = 0;
		if(isset($_POST['saldos'])) $p13=1;
		if(intval($pol_id)){
			$datos = $this->polizasImpresion->obtenerPoliza($pol_id);
			$imprimirPoliza = true;
		} else {
			$datos = $this->polizasImpresion->obtenerDatos($_POST['fecha_ini'], $_POST['fecha_fin'], $_POST['tipo'], $p13, $_POST['pol_ini'], $_POST['pol_fin']);
		}
		$empresa = $this->polizasImpresion->empresa();
		$logo = $this->polizasImpresion->logo();
		require('views/captpolizas/polizasImpresionReporte.php');
	}

	//Nuevo Commit
}

?>
