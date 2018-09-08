<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/Auxiliar_Impuestos.php");

class Auxiliar_Impuestos extends Common
{
	public $Auxiliar_ImpuestosModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar
		$this->Auxiliar_ImpuestosModel = new Auxiliar_ImpuestosModel();
	}

	function filtro()
	{
		$ejercicio = $this->Auxiliar_ImpuestosModel->con_ejercicio($ejercicio);					
		$tasaIVA = $this->Auxiliar_ImpuestosModel->tasas_iva();
		$operacion = $this->Auxiliar_ImpuestosModel->tipo_operacion();
		$tercero = $this->Auxiliar_ImpuestosModel->tipo_tercero();
		$tipoIVA = $this->Auxiliar_ImpuestosModel->tipo_iva();
		require('views/fiscal/declaraciones/filtro_aux_imp.php');
	}

	function reporte()
	{
		$logo = $this->Auxiliar_ImpuestosModel->logo();
		$empresa = $this->Auxiliar_ImpuestosModel->empresa();
		if($_REQUEST['considera_per'])
		{
			$fecIni=$this->NombrePeriodo($_REQUEST['per_ini']);
			$fecFin=$this->NombrePeriodo($_REQUEST['per_fin']);
			$fecha = "1*/*".$_REQUEST['per_ini']."*/*".$_REQUEST['sel_ejercicio'];
			$fecha_reporte = "<td colspan=2> <b style='font-size:18px;color:black;'>$empresa</b> <br>
			                  <b style='font-size:15px;'>Auxiliar de Impuestos</b><br>
			                  Periodo de acreditamiento de <b>".$fecIni."</b> A <b>".$fecFin."</b><br><br></td>";
		}
		else
		{
			$fecha = "0*/*".$_REQUEST['fecha_ini']."*/*".$_REQUEST['fecha_fin'];
			$fecha_reporte = "<td colspan=2> <b style='font-size:18px;color:black;'>$empresa</b> <br>
			                  <b style='font-size:15px;'>Auxiliar de Impuestos</b><br>
			                  Del <b> ".$_REQUEST['fecha_ini']."</b> Al <b>".$_REQUEST['fecha_fin']."</b><br><br></td>";
		}

		if($_REQUEST['radio_mov'])
		{
			$egresos = $this->Auxiliar_ImpuestosModel->egresos($fecha,$_REQUEST['tasa_sel'],$_REQUEST['acreed100'],$_REQUEST['pago_prov'],$_REQUEST['tipo_op'],$_REQUEST['tipo_iva']);
			require('views/fiscal/declaraciones/aux_imp_egresos.php');
		}
		else
		{
			$ingresos = $this->Auxiliar_ImpuestosModel->ingresos($fecha,$_REQUEST['tasa_sel'],$_REQUEST['acreed100'],$_REQUEST['pago_prov'],$_REQUEST['tipo_op'],$_REQUEST['tipo_iva']);
			require('views/fiscal/declaraciones/aux_imp_ingresos.php');
		}
	}
	function NombrePeriodo($periodo)
	{
		$p;
		switch(intval($periodo))
			{
				case 1:$p  = 'Enero';break;
				case 2:$p  = 'Febrero';break;
				case 3:$p  = 'Marzo';break;
				case 4:$p  = 'Abril';break;
				case 5:$p  = 'Mayo';break;
				case 6:$p  = 'Junio';break;
				case 7:$p  = 'Julio';break;
				case 8:$p  = 'Agosto';break;
				case 9:$p  = 'Septiembre';break;
				case 10:$p = 'Octubre';break;
				case 11:$p = 'Noviembre';break;
				case 12:$p = 'Diciembre';break;
			}
			return $p;
	}
}

?>