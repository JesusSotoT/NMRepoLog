<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/generacionIVA.php");

class generacionIVA extends Common
{
	public $generacionIVAModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar
		$this->generacionIVAModel = new generacionIVAModel();
	}

	function filtro()
	{
		$ejercicio = $this->consulta_ejercicio();
		require('views/fiscal/declaraciones/filtroGeneracionIVA.php');
	}
	function reporte(){
		$ejer=0;
		$per = $_REQUEST["per"];
		$toexcel = $_REQUEST["toexcel"];
		$ejercicio = $this->consulta_ejercicio($_REQUEST["ejercicio"]);
		$fac_acr = (float)$_REQUEST["fac_acr"];

		if($per==1){
			$ejer = $_REQUEST["ejercicio"];
			$ini = $_REQUEST["per_ini"];
			$fin = $_REQUEST["per_fin"];
		}
		if($per==0){
			$ini = $_REQUEST["fecha_ini"];
			$fin = $_REQUEST["fecha_fin"];
		}
		
		$gastos = $this->gastosGravados($ejer,$ini,$fin,$per);
		$organizacion = $this->organizacion();
		$meses = array('1' => 'enero','2' => 'febrero','3' => 'marzo','4' => 'abril','5' => 'mayo','6' => 'junio','7' => 'julio','8' => 'agosto','9' => 'septiembre','10' => 'octubre','11' => 'noviembre','12' => 'diciembre');
		require('views/fiscal/declaraciones/iva_acreditable.php');
	}

	function consulta_ejercicio($ejercicio=0){
		$qry_eje=$this->generacionIVAModel->con_ejercicio($ejercicio);					
		return $qry_eje;				
		
	}

	function organizacion(){
		$res="";
		$qry_eje=$this->generacionIVAModel->organizacion();
		$res=$qry_eje->fetch_object();

		return $res;
	}

	function gastosGravados($ejer=0,$ini,$fin,$per){
		$res=array();
		for($i=0;$i<10;$i++){
			$res[$i]=array("base"=>0,"16%"=>0,"11%"=>0,"noAcred"=>0);
		}
		$qry_eje=$this->generacionIVAModel->gastosGravados($ejer,$ini,$fin,$per);
		while($r=$qry_eje->fetch_object()){
			$res[$r->tipoIva]["base"]+=$r->base;
			//$res[$r->tipoIva]["noAcred"]+=$r->noAcred;
			$res[$r->tipoIva][$r->tasa]=$r;
			//if($r->tipoIva==6){
			//	$res[$r->tipoIva][$r->tasa]=$r;	
			//}
		}

		//print_r($res);
			return $res;
	}
	
	
}

?>