<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/conciliacion_IVA_contable_fiscal.php");

class conciliacion_IVA_contable_fiscal extends Common
{
	public $conciliacion_IVA_contable_fiscalModel;

	function __construct(){
		//Se crea el objeto que instancia al modelo que se va a utilizar
		$this->conciliacion_IVA_contable_fiscalModel = new conciliacion_IVA_contable_fiscalModel();
	}

	function filtro(){
		$ejercicio = $this->conciliacion_IVA_contable_fiscalModel->consulta_ejercicio();
		$cuentas1 = $this->conciliacion_IVA_contable_fiscalModel->cuentas();
		$cuentas2 = $this->conciliacion_IVA_contable_fiscalModel->cuentas();
		require('views/fiscal/pago_provicional/filtroConciliacion_IVA_contable_fiscal.php');
	}

	function reporte()
	{

		$empresa = $this->conciliacion_IVA_contable_fiscalModel->empresa();
		$logo = $this->conciliacion_IVA_contable_fiscalModel->logo();
		$fecha_normal = explode('-',$_POST['fecha_ini']);
		$inicio = $fecha_normal[2]."/".$fecha_normal[1]."/".$fecha_normal[0];

		$fecha_normal = explode('-',$_POST['fecha_fin']);
		$fin = $fecha_normal[2]."/".$fecha_normal[1]."/".$fecha_normal[0];
		if($_POST['considera_per'])
		{
			$rango_inicio = $_POST['sel_ejercicio']."/".$_POST['per_ini'];
			$rango_fin = $_POST['per_fin'];
			$perIn = $_POST['per_ini'];
			$perIni=$this->NombrePeriodo($perIn);
			$perFin=$this->NombrePeriodo($rango_fin);
			$periodoAcreditamiento = 1;
			$eje=$this->conciliacion_IVA_contable_fiscalModel->consulta_ejercicio($_POST['sel_ejercicio']);

			$fecha = "<td colspan=2><b style='font-size:18px;color:black;'>$empresa</b>
			          <br><b style='font-size:15px;'>Conciliacion de de IVA fiscal y contable</b> <br> 
			          Ejercicio <b>$eje->NombreEjercicio</b> Periodo De <b> $perIni </b> A <b> $perFin</b><br><br></td><td></td>";
		}
		else
		{
			$rango_inicio = $_POST['fecha_ini'];
			$rango_fin = $_POST['fecha_fin'];
			$periodoAcreditamiento = 0;
			$fecha = "<td colspan=2><b style='font-size:18px;color:black;'>$empresa</b><br>
			          <b style='font-size:18px;'>Conciliacion de de IVA fiscal y contable</b> <br>
			          Del<b> $inicio </b> Al <b> $fin</b><br><br></td>";
		}
		if($_POST['radio_tipo'] == '0')
		{
			$datosTrasladado = $this->conciliacion_IVA_contable_fiscalModel->ivaTrasladado($periodoAcreditamiento,$rango_inicio,$rango_fin,$_POST['cuenta_Trans']);
			$datosTrasladadoNo = $this->conciliacion_IVA_contable_fiscalModel->ivaTrasladadoNo($periodoAcreditamiento,$rango_inicio,$rango_fin,$_POST['cuenta_Trans']);
			$trasladado=1;
			$acreditable=0;
		}
		if($_POST['radio_tipo'] == '1')
		{
			$datosAcreditado = $this->conciliacion_IVA_contable_fiscalModel->ivaAcreditable($periodoAcreditamiento,$rango_inicio,$rango_fin,$_POST['cuenta_Acred']);
			$datosAcreditadoNo = $this->conciliacion_IVA_contable_fiscalModel->ivaAcreditableNo($periodoAcreditamiento,$rango_inicio,$rango_fin,$_POST['cuenta_Acred']);
			$trasladado=0;
			$acreditable=1;
		}
		if($_POST['radio_tipo'] == '2')
		{
			$datosTrasladado = $this->conciliacion_IVA_contable_fiscalModel->ivaTrasladado($periodoAcreditamiento,$rango_inicio,$rango_fin,$_POST['cuenta_Trans']);
			$datosTrasladadoNo = $this->conciliacion_IVA_contable_fiscalModel->ivaTrasladadoNo($periodoAcreditamiento,$rango_inicio,$rango_fin,$_POST['cuenta_Trans']);
			$datosAcreditado = $this->conciliacion_IVA_contable_fiscalModel->ivaAcreditable($periodoAcreditamiento,$rango_inicio,$rango_fin,$_POST['cuenta_Acred']);
			$datosAcreditadoNo = $this->conciliacion_IVA_contable_fiscalModel->ivaAcreditableNo($periodoAcreditamiento,$rango_inicio,$rango_fin,$_POST['cuenta_Acred']);
			$trasladado=1;
			$acreditable=1;
		}
		require('views/fiscal/pago_provicional/Conciliacion_IVA_contable_fiscal.php');
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