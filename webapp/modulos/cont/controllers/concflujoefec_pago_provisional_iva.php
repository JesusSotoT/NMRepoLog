<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/ConcFlujoEfec_Pago_provisional_IVA.php");

class ConcFlujoEfec_Pago_provisional_IVA extends Common
{
	public $ConcFlujoEfec_Pago_provisional_IVAModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar
		$this->ConcFlujoEfec_Pago_provisional_IVAModel = new ConcFlujoEfec_Pago_provisional_IVAModel();
	}

	function filtro()
	{
		$cuentas = $this->cuentas();
		require('views/fiscal/pago_provicional/filtroConcFlujoEfec_Pago_provisional_IVA.php');
	}
	function reporte(){
		$toexcel = $_REQUEST["toexcel"];
		$fecha_ini = $_REQUEST["fecha_ini"];
		$fecha_fin = $_REQUEST["fecha_fin"];
		$cuenta_Ini=$_REQUEST['cuenta_Ini'];
		$cuenta_Fin=$_REQUEST['cuenta_Fin'];
		$organizacion = $this->organizacion();
		$cuentasPolizas = $this->cuentasPolizas($fecha_ini,$fecha_fin,$cuenta_Ini,$cuenta_Fin,$_REQUEST['aplica']);
		require('views/fiscal/pago_provicional/Conciliacion_flujo_efectivo_pago_provisional_IVA.php');
	}
	function organizacion(){
		$res="";
		$qry_eje=$this->ConcFlujoEfec_Pago_provisional_IVAModel->organizacion();
		//if($qry_eje){
			$res=$qry_eje->fetch_object();

		//	}
			return $res;
		
	}
	function cuentas(){
		$arr = array();
		$qry_eje=$this->ConcFlujoEfec_Pago_provisional_IVAModel->cuentas("","");
		while($res=$qry_eje->fetch_object()){
			$arr[] = $res;
		}
		return $arr;
	}
	function cuentasPolizas($fecha_ini,$fecha_fin,$cuenta_Ini,$cuenta_Fin,$aplica){

		$arr = array();
		$qry_eje=$this->ConcFlujoEfec_Pago_provisional_IVAModel->cuentas($cuenta_Ini,$cuenta_Fin);
		while($res=$qry_eje->fetch_object()){
			$arr[$res->account_id][0] = $res;
		}
		$qry_eje2=$this->ConcFlujoEfec_Pago_provisional_IVAModel->poliza($fecha_ini,$fecha_fin,$cuenta_Ini,$cuenta_Fin,$aplica);
		while($res2=$qry_eje2->fetch_object()){
			//[$res2->concepto]
			$arr[$res2->account_id][$res2->num] = $res2;
			//print_r($res2->account_id);
			//print_r($arr[$res2->account_id][$res2->num]);
		}
		//print_r($arr);
		return $arr;
	}

}

?>