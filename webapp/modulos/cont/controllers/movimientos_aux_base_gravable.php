<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/Movimientos_aux_base_gravable.php");

class Movimientos_aux_base_gravable extends Common
{
	public $Movimientos_aux_base_gravableModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar
		$this->Movimientos_aux_base_gravableModel = new Movimientos_aux_base_gravableModel();
	}

	function filtro()
	{
		$ejercicio=$this->consulta_ejercicio();
		$tasaIVA = $this->tasas_iva();
		$operacion = $this->tipo_operacion();
		$tipoIVA=$this->tipo_iva();
		$cuentaTrans = $this->cuenta_transladado();
		$cuentaAcred = $this->cuenta_acreditable();
		require('views/fiscal/pago_provicional/filtroMovimientos_aux_base_gravable.php');
	}
	function reporte(){
		$toexcel = $_REQUEST["toexcel"];
		$usaPeriodo = $_REQUEST["per"];
		$ejercicio = $this->consulta_ejercicio($_REQUEST["ejercicio"]);
		$ejer = 0;
		$ini=0;
		$fin =0;
		if($usaPeriodo==1){
			$ejer = $_REQUEST["ejercicio"];
			$ini = $_REQUEST["per_ini"];
			$fin = $_REQUEST["per_fin"];
		}
		else{
			$ini = $_REQUEST["fecha_ini"];
			$fin = $_REQUEST["fecha_fin"];
		}
		$tipo_mov = $_REQUEST["radio_mov"];
		$cuentaAcred = $_REQUEST["cuenta_acred"];
		$cuentaTrans = $_REQUEST["cuenta_trans"];
		$polAcred = $this->poliza($cuentaTrans,$cuentaAcred,$ini,$fin,$ejer);
		$polCausado = $this->polizaCausado($cuentaTrans,$cuentaAcred,$ini,$fin,$ejer);
		$organizacion = $this->organizacion();
		$meses = array('1' => 'enero','2' => 'febrero','3' => 'marzo','4' => 'abril','5' => 'mayo','6' => 'junio','7' => 'julio','8' => 'agosto','9' => 'septiembre','10' => 'octubre','11' => 'noviembre','12' => 'diciembre');
		
		require('views/fiscal/pago_provicional/Movimientos_aux_x_base_gravable.php');
	}
	function consulta_ejercicio($ejercicio=0){
		$qry_eje=$this->Movimientos_aux_base_gravableModel->con_ejercicio($ejercicio);					
		return $qry_eje;				
		
	}
	function tasas_iva(){
		$qry_eje=$this->Movimientos_aux_base_gravableModel->tasas_iva();
		return $qry_eje;
	}
	function tipo_operacion(){
		$qry_eje=$this->Movimientos_aux_base_gravableModel->tipo_operacion();
		return $qry_eje;
	}
	function tipo_iva(){
		$qry_eje=$this->Movimientos_aux_base_gravableModel->tipo_iva();
		return $qry_eje;
	}
	function organizacion(){
		$res="";
		$qry_eje=$this->Movimientos_aux_base_gravableModel->organizacion();
		//if($qry_eje){
			$res=$qry_eje->fetch_object();

		//	}
			return $res;
		
	}
	function cuenta_transladado(){
		$arr = array();
		$qry_eje=$this->Movimientos_aux_base_gravableModel->cuenta_transladado();
		while($res=$qry_eje->fetch_object()){
			$arr[] = $res;
		}
		//print_r($arr);
		return $arr;
		
	}

	function cuenta_acreditable(){
		$arr = array();
		$qry_eje=$this->Movimientos_aux_base_gravableModel->cuenta_acreditable();
		while($res=$qry_eje->fetch_object()){
			$arr[] = $res;
		}
		//print_r($arr);
		return $arr;
		
	}

	function polizaCausado($cuentaTrans,$cuentaAcred,$ini,$fin,$ejer){
		$arr = array();
		
		$qry_eje=$this->Movimientos_aux_base_gravableModel->polizaCausado($cuentaTrans,$cuentaAcred,$ini,$fin,$ejer);
		while($res=$qry_eje->fetch_object()){
			$valor16 = $this->separaIvaValor($res->tasa16);
			$valor11 = $this->separaIvaValor($res->tasa11);
			$valor0 = $this->separaIvaValor($res->tasa0);
			$valorExenta = $this->separaIvaValor($res->tasaExenta);
			$valorTasas = $this->separaIvaValor($res->otrasTasas);



			$arr['16%'][$res->num]->fecha = $res->fecha;
			$arr['16%'][$res->num]->concepto = $res->concepto;
			$arr['16%'][$res->num]->num = $res->num;
			$arr['16%'][$res->num]->poliza = $res->poliza;
			$arr['16%'][$res->num]->NombreEjercicio = $res->NombreEjercicio;
			$arr['16%'][$res->num]->idperiodo = $res->idperiodo;
			$arr['16%'][$res->num]->otros = $res->otros;
			$arr['16%'][$res->num]->ivaRetenido = $res->ivaRetenido;
			$arr['16%'][$res->num]->isrRetenido = $res->isrRetenido;
			$arr['16%'][$res->num]->total=$valor16->total;
			$arr['16%'][$res->num]->base=$valor16->base;
			$arr['16%'][$res->num]->iva=$valor16->iva;
			$arr['16%'][$res->num]->noAcre=$valor16->noAcre;

			$arr['11%'][$res->num]->fecha = $res->fecha;
			$arr['11%'][$res->num]->concepto = $res->concepto;
			$arr['11%'][$res->num]->num = $res->num;
			$arr['11%'][$res->num]->poliza = $res->poliza;
			$arr['11%'][$res->num]->NombreEjercicio = $res->NombreEjercicio;
			$arr['11%'][$res->num]->idperiodo = $res->idperiodo;
			$arr['11%'][$res->num]->otros = $res->otros;
			$arr['11%'][$res->num]->ivaRetenido = $res->ivaRetenido;
			$arr['11%'][$res->num]->isrRetenido = $res->isrRetenido;
			$arr['11%'][$res->num]->total=$valor11->total;
			$arr['11%'][$res->num]->base=$valor11->base;
			$arr['11%'][$res->num]->iva=$valor11->iva;
			$arr['11%'][$res->num]->noAcre=$valor11->noAcre;

			$arr['0%'][$res->num]->fecha = $res->fecha;
			$arr['0%'][$res->num]->concepto = $res->concepto;
			$arr['0%'][$res->num]->num = $res->num;
			$arr['0%'][$res->num]->poliza = $res->poliza;
			$arr['0%'][$res->num]->NombreEjercicio = $res->NombreEjercicio;
			$arr['0%'][$res->num]->idperiodo = $res->idperiodo;
			$arr['0%'][$res->num]->otros = $res->otros;
			$arr['0%'][$res->num]->ivaRetenido = $res->ivaRetenido;
			$arr['0%'][$res->num]->isrRetenido = $res->isrRetenido;
			$arr['0%'][$res->num]->total=$valor0->total;
			$arr['0%'][$res->num]->base=$valor0->base;
			$arr['0%'][$res->num]->iva=$valor0->iva;
			$arr['0%'][$res->num]->noAcre=$valor0->noAcre;

			$arr['Exenta'][$res->num]->fecha = $res->fecha;
			$arr['Exenta'][$res->num]->concepto = $res->concepto;
			$arr['Exenta'][$res->num]->num = $res->num;
			$arr['Exenta'][$res->num]->poliza = $res->poliza;
			$arr['Exenta'][$res->num]->NombreEjercicio = $res->NombreEjercicio;
			$arr['Exenta'][$res->num]->idperiodo = $res->idperiodo;
			$arr['Exenta'][$res->num]->otros = $res->otros;
			$arr['Exenta'][$res->num]->ivaRetenido = $res->ivaRetenido;
			$arr['Exenta'][$res->num]->isrRetenido = $res->isrRetenido;
			$arr['Exenta'][$res->num]->total=$valorExenta->total;
			$arr['Exenta'][$res->num]->base=$valorExenta->base;
			$arr['Exenta'][$res->num]->iva=$valorExenta->iva;
			$arr['Exenta'][$res->num]->noAcre=$valorExenta->noAcre;

			$arr['otras'][$res->num]->fecha = $res->fecha;
			$arr['otras'][$res->num]->concepto = $res->concepto;
			$arr['otras'][$res->num]->num = $res->num;
			$arr['otras'][$res->num]->poliza = $res->poliza;
			$arr['otras'][$res->num]->NombreEjercicio = $res->NombreEjercicio;
			$arr['otras'][$res->num]->idperiodo = $res->idperiodo;
			$arr['otras'][$res->num]->otros = $res->otros;
			$arr['otras'][$res->num]->ivaRetenido = $res->ivaRetenido;
			$arr['otras'][$res->num]->isrRetenido = $res->isrRetenido;
			$arr['otras'][$res->num]->total=$valorTasas->total;
			$arr['otras'][$res->num]->base=$valorTasas->base;
			$arr['otras'][$res->num]->iva=$valorTasas->iva;
			$arr['otras'][$res->num]->noAcre=$valorTasas->noAcre;

			if(array_key_exists($res->num,$arr['otros'])){
				$arr['otros'][$res->num]->otros += $res->otros;
			}
			else{
				$arr['otros'][$res->num] = $res;
			}

			if(array_key_exists($res->num,$arr['ivaretenido'])){
				$arr['ivaretenido'][$res->num]->ivaRetenido += $res->ivaRetenido;
			}
			else{
				$arr['ivaretenido'][$res->num] = $res;
			}

			if(array_key_exists($res->num,$arr['isrretenido'])){
				$arr['isrretenido'][$res->num]->isrRetenido += $res->isrRetenido;
			}
			else{
				$arr['isrretenido'][$res->num] = $res;
			}	
		}

		//print_r($arr['16%']);
		return $arr;
	}

	function poliza($cuentaTrans,$cuentaAcred,$ini,$fin,$ejer){
		$arr = array();
		$arr['otros'][0]=0;
		$arr['ivaretenido'][0]=0;
		$arr['isrretenido'][0]=0;
		$qry_eje=$this->Movimientos_aux_base_gravableModel->poliza($cuentaTrans,$cuentaAcred,$ini,$fin,$ejer);
		while($res=$qry_eje->fetch_object()){
			$arr[$res->tasa][] = $res;

			if(array_key_exists($res->num,$arr['otros'])){
				$arr['otros'][$res->num]->otros += $res->otros;
			}
			else{
				$arr['otros'][$res->num] = $res;
			}

			if(array_key_exists($res->num,$arr['ivaretenido'])){
				$arr['ivaretenido'][$res->num]->ivaRetenido += $res->ivaRetenido;
			}
			else{
				$arr['ivaretenido'][$res->num] = $res;
			}

			if(array_key_exists($res->num,$arr['isrretenido'])){
				$arr['isrretenido'][$res->num]->isrRetenido += $res->isrRetenido;
			}
			else{
				$arr['isrretenido'][$res->num] = $res;
			}	
		}
		return $arr;
	}	

	function separaIvaValor($valIva){
		$arreglo = explode("-",$valIva);
		$obj = new stdClass();

		$obj->total=$arreglo[0];
		$obj->base=$arreglo[1];
		$obj->iva=$arreglo[2];
		$obj->noAcre=$arreglo[3];

		return $obj;

	}				
}

?>