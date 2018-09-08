<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/flujoEfectivoIva.php");

class flujoEfectivoIva extends Common
{
	public $flujoEfectivoIvaModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar
		$this->flujoEfectivoIvaModel = new flujoEfectivoIvaModel();
		$this->flujoEfectivoIvaModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->flujoEfectivoIvaModel->close();
	}

	function Inicial()
	{
		$flujo1 = $this->flujoEfectivoIvaModel->flujo();
		$flujo2 = $this->flujoEfectivoIvaModel->flujo();
		require('views/fiscal/diot/flujoEfectivoIva.php');
	}
	function VerReporte()
	{
		$empresa = $this->flujoEfectivoIvaModel->empresa();
		$logo= $this->flujoEfectivoIvaModel->logo();
		$fecha_normal = explode('-',$_POST['fecha_ini']);
		$inicio = $fecha_normal[2]."/".$fecha_normal[1]."/".$fecha_normal[0];

		$fecha_normal = explode('-',$_POST['fecha_fin']);
		$fin = $fecha_normal[2]."/".$fecha_normal[1]."/".$fecha_normal[0];


			$fecha = "<td colspan='2'>
			<b style='font-size:18px;color:black;'>$empresa </b><br>
			<b style='font-size:15px;'>Conciliación de Flujo de efectivo e IVA</b> 
			<br>Del <b>$inicio</b> Al <b>$fin</b><br><br></td>";
		


		//var_dump($tasas);
		if($_POST['soloAplican'])
		{
			$datos = $this->flujoEfectivoIvaModel->obtenerDatos($_POST['fecha_ini'],$_POST['fecha_fin'],'Aplican');
			//echo "Solo aplican";
		}
		else
		{
			$datos = $this->flujoEfectivoIvaModel->obtenerDatos($_POST['fecha_ini'],$_POST['fecha_fin'],'Todas');
			//echo "Todas";
		}
		
		require('views/fiscal/diot/flujoEfectivoIvaReporte.php');
		
	}

	function ProvsCuenta($poliza,$Ap)
			{
				$datos = $this->flujoEfectivoIvaModel->ProvsCuenta($poliza,$Ap);
				return $datos;
			}
	
}

?>
