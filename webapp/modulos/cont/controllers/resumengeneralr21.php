<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/resumenGeneralR21.php");

class resumenGeneralR21 extends Common{
	public $resumenGeneralR21Model;

	function __construct(){
		//Se crea el objeto que instancia al modelo que se va a utilizar
		$this->resumenGeneralR21Model = new resumenGeneralR21Model();
	}

	function filtro(){
		$ejercicio=$this->consulta_ejercicio();
		$tasaIVA = $this->tasas_iva();
		require('views/fiscal/declaraciones/filtroResumenGeneralR21.php');
	}

	function reporte(){
		$toexcel = $_REQUEST["to_excel"];
		$per_ini = $_REQUEST["per_ini"];
		$per_fin = $_REQUEST["per_fin"];
		$acr_iva = $_REQUEST['acr_iva'];
		$use_prop = $_REQUEST['use_prop'];//SELECT DE ARTICULO
		$sel_rep = $_REQUEST['sel_rep'];//SI SERA Resumen R21
		$ejercicio = $this->consulta_ejercicio($_REQUEST["ejercicio"]);
		$ejer = $_REQUEST["ejercicio"];
		$prop = $_REQUEST["prop"];
		$organizacion = $this->organizacion();
		$IvaTerceros = $this->valorIvaTerceros($ejer);
		/*		Montos pagados					*/
		$valorBase16 = $this -> totalbaseiva($ejer,'16%','totalbaseiva');
		$valorBase11 = $this -> totalbaseiva($ejer,'11%','totalbaseiva');
		$valorbase0 = $this-> totalbaseiva($ejer,'0%','totalbaseiva');
		$totalBaseIvaExcento = $this->totalbaseiva($ejer,"Exenta",'totalbaseiva');
		$valorbaseimport16=$this->totalbaseiva($ejer,'16%','totalbaseimport');
		$valorbaseimport11=$this->totalbaseiva($ejer,'11%','totalbaseimport');
		/*   Determinacion del impuesto al valor agregado acreditable  */
		$totalTasaIvaAcr16 = $this->totaliva($ejer,"16%",'totalTasaIvaAcr',0);
		$totalTasaIvaAcr11 = $this-> totaliva($ejer,"11%",'totalTasaIvaAcr',0);
		$ivaimport16=$this->totaliva($ejer,"16%",'totalTasaIvaAcr',2);
		$ivaimport11=$this->totaliva($ejer,"11%",'totalTasaIvaAcr',2);
			/*efectivamente pagado */
		$efectivamentepagado=array();
		for($i=1;$i<=12;$i++){
			$efectivamentepagado[$i]=$totalTasaIvaAcr16[$i]["16%"]+$totalTasaIvaAcr11[$i]["11%"]+$ivaimport16[$i]["16%"]+$ivaimport11[$i]["11%"];
			
		}
		/* termina efectivamente pagado */
		/* actos gravados*/
		$arr = $this->ivaXTipo($ejer);
		$sumagravados=array();
		$ivabienesutilizados=array();
		$multipliart5=array();
		$ivaacreditable=array();
		$resumenivas=$this->resumenivas($ejer);
		$totalacreditable=array();
		$IVAExcentos=array();
		$totalBaseIvaImp16 = $this->causado($ejer, 'tasa16',1);
		$totalBaseIvaImp11 =$this->causado($ejer, 'tasa11',1);
		$totalBaseIvacausa0=$this->causado($ejer, 'tasa0',1);
		$totalBaseIvacausaotros = $this->causado($ejer, 'otrasTasas',1);
		 // gravados/
		$sumaactosgravados=array();
		$impuestocausado=array();
		$totalcargo=array();
		$totalBaseIvacausaExenta=$this->causado($ejer, 'tasaExenta',1);
		  $cargo=array();$favor=array();$ivafavorcargo=array();$totalcargo=0;
		 
		for($i=1;$i<=12;$i++){
			
		$IVAExcentos[$i] = $arr[$i]['GastosExentos']+$arr[$i]['GastosExentosnacional']+$arr[$i]['InvExentos']+$arr[$i]['InvExentosnacional'];
			$sumagravados[$i]=$arr[$i]['GastosGravadosNacional']+$arr[$i]['GastosGravadosExtrangeros']+$arr[$i]['InvGravadosNacional']+$arr[$i]['InvGravadosExtrangeros'];
			//* fin actos gravados*/
			//iva de actos exentos
			//$ivabienesutilizados[$i]=$efectivamentepagado[$i]-$sumagravados[$i]-$arr[$i]['GastosExentos']-$arr[$i]['InvExentos'];
			$ivabienesutilizados[$i]=$efectivamentepagado[$i]-$sumagravados[$i]-$arr[$i]['gastosnoident']-$arr[$i]['invernoident'];//este ;p movi por la el tipo de no identificado que pendiente ver si se agregaran los q estaban aneterior
			
			/////////
			$multipliart5[$i]=$ivabienesutilizados[$i]*$prop;
			//total iva acreditable
			$ivaacreditable[$i]= $multipliart5[$i]+$sumagravados[$i];
			$totalacreditable[$i]=$ivaacreditable[$i]+$resumenivas[$i]['derivada_ajuste'];
			
			$sumaactosgravados[$i]=$totalBaseIvaImp16[$i]['tasa16']+$totalBaseIvaImp11[$i]['tasa11']+$totalBaseIvacausa0[$i]['tasa0']+$totalBaseIvacausaotros[$i]['otrasTasas'];
		 	 $impuestocausado[$i]=($totalBaseIvaImp16[$i]['tasa16']*0.16)+($totalBaseIvaImp11[$i]['tasa11']*0.11);
		 	
		 	 $cargo[$i]=0;
			 $favor[$i]=0;
			 $ivafavorcargo[$i]=0;
			 
			 
			 if($acr_iva==1){//modifique aki toma como favor el cargo del mes anterior
		 		$cargo[$i]=$resumenivas[$i]['cargo'];
				$favor[$i]=$resumenivas[$i-1]['cargo'];
			}
		
		 	// $totalcargo=$impuestocausado[$i]+$resumenivas[$i]['cantidadreintegrarse']-$resumenivas[$i]['ivaretenido']-$totalacreditable[$i]+$resumenivas[$i]['cargo']-$resumenivas[$i]['favor'];
		 	// if( $totalcargo > 0 ){$ivafavorcargo[$i] = $totalcargo; }
		 	// else if( $totalcargo < 0 ){ $ivafavorcargo[$i] = $totalcargo ; }
		 	//ESTABA ANTES CON LO DE ARRIBA//
			$totalcargo=($impuestocausado[$i])-($totalacreditable[$i])-($favor[$i])+($cargo[$i]);
			$ivafavorcargo[$i] = $totalcargo ;		
		}
		
		/*																*/
		
		$meses = array('1' => 'Enero','2' => 'Febrero','3' => 'Marzo','4' => 'Abril','5' => 'Mayo','6' => 'Junio','7' => 'Julio','8' => 'Agosto','9' => 'Septiembre','10' => 'Octubre','11' => 'Noviembre','12' => 'Diciembre');
		if($sel_rep==1){
			require('views/fiscal/declaraciones/resumen_mov_r21.php');
		}
		// if($sel_rep==2){
			// require('views/fiscal/declaraciones/resumen_mov_r21_x_tipo.php');
		// }
		
	}

	function consulta_ejercicio($ejercicio=0){
		$qry_eje=$this->resumenGeneralR21Model->con_ejercicio($ejercicio);					
		return $qry_eje;				
		
	}

	function tasas_iva(){
		$qry_eje=$this->resumenGeneralR21Model->tasas_iva();
		return $qry_eje;
	}

	function organizacion(){
		$res="";
		$qry_eje=$this->resumenGeneralR21Model->organizacion();
			$res=$qry_eje->fetch_object();
			return $res;
	}

	function valorBase($ejer){
		$tasaIVA = $this->tasas_iva();
		$qry_eje=$this->resumenGeneralR21Model->valorBase($ejer);

		$arr = array();
	
		while($tasasIva = $tasaIVA->fetch_object()){
			for ($i=1; $i <= 12 ; $i++) { 
				$arr[$tasasIva->tasa]["ext"][$i] = 0;
				$arr[$tasasIva->tasa]["nac"][$i] = 0;
				$arr[$tasasIva->tasa]["otro"][$i] = 0;
			}
		}
		while ($var = $qry_eje->fetch_object()) {
			switch ($var->idtipotercero) {
				case '2':
				case '6':
					$arr[$var->tasa]["ext"][$var->periodoAcreditamiento] += $var->base;
					break;
				case '1':
				case '5':
					$arr[$var->tasa]["nac"][$var->periodoAcreditamiento] += $var->base;
					break;
				
				default:
					$arr[$var->tasa]["otro"][$var->periodoAcreditamiento] += $var->base;
					break;
			}
		}
		return $arr;
	}

	function valorIva($ejer){
		$tasaIVA = $this->tasas_iva();
		$qry_eje=$this->resumenGeneralR21Model->valorIva($ejer);

		$arr = array();

		while($tasasIva = $tasaIVA->fetch_object()){
			for ($i=1; $i <= 12 ; $i++) { 
				$arr[$tasasIva->tasa]["ext"][$i] = 0;
				$arr[$tasasIva->tasa]["nac"][$i] = 0;
				$arr[$tasasIva->tasa]["otro"][$i] = 0;
			}
		}
		while ($var = $qry_eje->fetch_object()) {
			switch ($var->idtipotercero) {
				case '2':
				case '6':
					$arr[$var->tasa]["ext"][$var->periodoAcreditamiento] += $var->iva;
					break;
				case '1':
				case '5':
					$arr[$var->tasa]["nac"][$var->periodoAcreditamiento] += $var->iva;
					break;
				
				default:
					$arr[$var->tasa]["otro"][$var->periodoAcreditamiento] += $var->iva;
					break;
			}
		}
		return $arr;
	}	
	
	function valorIvaTerceros($ejer){
		
		$qry_eje=$this->resumenGeneralR21Model->valorIvaTerceros($ejer);

		$arr = array();
		$arrExentos = array();

		for ($i=1; $i <= 12 ; $i++) { 
			$arr["ext"]['compra'][$i] = 0;
			$arr["ext"]['inv'][$i] = 0;
			$arr["nac"]['compra'][$i] = 0;
			$arr["nac"]['inv'][$i] = 0;
			$arrExentos['compra'][$i] = 0;
			$arrExentos['inv'][$i] = 0;
		}
		
		while ($var = $qry_eje->fetch_object()) {

			if($var->tipoiva==7){ $arrExentos['compra'][$var->periodoAcreditamiento]= $var->iva; }
			if($var->tipoiva==9){ $arrExentos['inv'][$var->periodoAcreditamiento]= $var->iva; }

			switch ($var->idtipotercero) {
				case '2':
				case '6':
					if($var->tipoiva==1){ $arr["ext"]['compra'][$var->periodoAcreditamiento] = $var->iva; }
					if($var->tipoiva==2){ $arr["ext"]['inv'][$var->periodoAcreditamiento] = $var->iva; }
					break;

				case '1':
				case '5':
					if($var->tipoiva==1){ $arr["nac"]['compra'][$var->periodoAcreditamiento] = $var->iva; }
					if($var->tipoiva==2){ $arr["nac"]['inv'][$var->periodoAcreditamiento] = $var->iva; }
					break;
				
				default:
					//$arr["otro"][$var->periodoAcreditamiento] = $var->iva;
					break;
			}
		}
		return $arr;
	}	
	function TotalBasePeriodo($ejer){
		$qry_eje=$this->resumenGeneralR21Model->TotalBasePeriodo($ejer);

		$arr = array();

		$var = new stdClass();
		$var->periodo=0;
		$var->base=0;
		$var->iva=0;
		$var->ivaNoAcreditable = 0;
		$var->retenido = 0;
		$var->ivaAcreditable = 0;
		$var->base_acreditable = 0;
		
		for ($i=0; $i <= 12 ; $i++) { 
			$arr[$i] = $var;
		}
		while ($var = $qry_eje->fetch_object()) {
			$arr[$var->periodo]=$var;
			$arr[$var->periodo]->ivaAcreditable = $var->iva - $var->ivaNoAcreditable - $var->retenido;
		}

		return $arr;
	}
	function GastosInversiones($ejer){
		$gastos = $this->resumenGeneralR21Model->GastosInversiones($ejer);

		$arr = array();
		$var = new stdClass();

		$var->periodo=0;
		$var->base=0;
		$var->iva=0;
		$var->tipoIvaId = 0;
		//$var->ivaTotalGravados = 0;
		//$var->ivaTotalExentos=0;
		//$var->ivaTotalNoIndentificados=0;

		for ($i=1; $i <= 12 ; $i++){ 
			for ($j=1; $j <= 9 ; $j++){ 
				$arr[$i][$j] = $var;
			}
			$arr[$i]['ivaTotalGravados']=0;
			$arr[$i]['ivaTotalExentos']=0;
			$arr[$i]['ivaTotalNoIndentificados']=0;
		}
		while ($var = $gastos->fetch_object()) {
			$arr[$var->periodo][$var->tipoIvaId]=$var;
			$arr[$var->periodo]['ivaTotalGravados']=($arr[$var->periodo][1]->base+$arr[$var->periodo][2]->base);
			$arr[$var->periodo]['ivaTotalExentos']=$arr[$var->periodo][7]->base+$arr[$var->periodo][9]->base;
			$arr[$var->periodo]['ivaTotalNoIndentificados']=$arr[$var->periodo][6]->base+$arr[$var->periodo][8]->base;

		}

		return $arr;

	}

	function cobrado($ejer){
		$cobrado = $this->resumenGeneralR21Model->cobrado($ejer);

		$arr = array();
		$obj = new stdClass();

		$obj->baseCobrado=0;

		$obj->ivaCausado=0;

		$obj->ivaRetenido=0;

		$obj->isrRetenido=0;

		$obj->otros=0;
		
			$var = $cobrado->fetch_object();
			$tasa16=$this->separaIvaValor($var->tasa16);
			$tasa11=$this->separaIvaValor($var->tasa11);
			$tasa15=$this->separaIvaValor($var->tasa15);
			$tasa10=$this->separaIvaValor($var->tasa10);
			$otrasTasas=$this->separaIvaValor($var->otrasTasas);
			$tasa0=$this->separaIvaValor($var->tasa0);
			$tasaExenta=$this->separaIvaValor($var->tasaExenta);

			$arr[$var->periodoAcreditamiento]=new stdClass();
			$arr[$var->periodoAcreditamiento]->baseCobrado=$tasa16->base+$tasa11->base+$tasa15->base+$tasa10->base+$otrasTasas->base+$tasa0->base+$tasaExenta->base;
			$arr[$var->periodoAcreditamiento]->ivaCausado=$tasa16->iva+$tasa11->iva+$tasa15->iva+$tasa10->iva+$otrasTasas->iva+$tasa0->iva+$tasaExenta->iva;
			$arr[$var->periodoAcreditamiento]->ivaRetenido=$var->ivaRetenido;
			$arr[$var->periodoAcreditamiento]->isrRetenido=$var->isrRetenido;
			$arr[$var->periodoAcreditamiento]->otros=$var->otros;
	
		for ($i=0; $i <= 12; $i++){ 
			if (!array_key_exists($i, $arr)){
			    $arr[$i]=$obj;
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
	function totalbaseiva($ejer,$tasa,$model){
		$bas=$this->resumenGeneralR21Model->$model($ejer,$tasa);
		$base=array();
		
		for($i=1;$i<=12;$i++){
			$base[$i][$tasa]=0;
			
		}
		while($bases = $bas->fetch_object()){
		$base[$bases->periodoAcreditamiento][$tasa]=$bases->base;
		}
		return $base;
	}
	function totaliva($ejer,$tasa,$model,$filtro){
		$bas=$this->resumenGeneralR21Model->$model($ejer,$tasa,$filtro);
		$base=array();
		
		for($i=1;$i<=12;$i++){
			$base[$i][$tasa]=0;
			
		}
		while($bases = $bas->fetch_object()){
		$base[$bases->periodoAcreditamiento][$tasa]=$bases->IVA;
		}
		return $base;
	}
	
	function ivaXTipo($ejer){
		$arr=array();
		for($i=1;$i<=12;$i++){
			$arr[$i]['GastosGravadosNacional']=0;
			$arr[$i]['GastosGravadosExtrangeros']=0;
			$arr[$i]['InvGravadosNacional']=0;
			$arr[$i]['InvGravadosExtrangeros']=0;
			$arr[$i]['GastosExentos']=0;
			$arr[$i]['InvExentos']=0;
			$arr[$i]['gastosnoident']=0;
			$arr[$i]['invernoident']=0;
			$arr[$i]['GastosExentosnacional']=0;
			$arr[$i]['InvExentosnacional']=0;
			
		}
		$qry_eje=$this->resumenGeneralR21Model->ivaXTipo($ejer);
		while($res = $qry_eje->fetch_object()){
			if($res->idtipoiva==1 && ($res->tipoTercero==1 || $res->tipoTercero==5)){
				$arr[$res->periodoAcreditamiento]['GastosGravadosNacional']+=$res->iva;
			}
			if($res->idtipoiva==1 && ($res->tipoTercero==2 || $res->tipoTercero==6)){
				$arr[$res->periodoAcreditamiento]['GastosGravadosExtrangeros']+=$res->iva;
			}
			if($res->idtipoiva==2 && ($res->tipoTercero==1 || $res->tipoTercero==5)){
				$arr[$res->periodoAcreditamiento]['InvGravadosNacional']+=$res->iva;
			}
			if($res->idtipoiva==2 && ($res->tipoTercero==2 || $res->tipoTercero==6)){
				$arr[$res->periodoAcreditamiento]['InvGravadosExtrangeros']+=$res->iva;
			}
			if($res->idtipoiva==7 && ($res->tipoTercero==2 || $res->tipoTercero==6)){//tercero 2 y 6 Gastos para generar ingresos exentos de IVA
				$arr[$res->periodoAcreditamiento]['GastosExentos']+=$res->iva;
			}
			if($res->idtipoiva==7 && ($res->tipoTercero==1 || $res->tipoTercero==5)){//tercero 2 y 6 Gastos para generar ingresos exentos de IVA
				$arr[$res->periodoAcreditamiento]['GastosExentosnacional']+=$res->iva;
			}
			if($res->idtipoiva==9 && ($res->tipoTercero==2 || $res->tipoTercero==6)){//Inversiones para generar ingresos exentos de IVA
				$arr[$res->periodoAcreditamiento]['InvExentos']+=$res->iva;
			}
			if($res->idtipoiva==9 && ($res->tipoTercero==1 || $res->tipoTercero==5)){//Inversiones para generar ingresos exentos de IVA
				$arr[$res->periodoAcreditamiento]['InvExentosnacional']+=$res->iva;
			}
			if($res->idtipoiva==6){//Gastos para generar ingresos NO identificados para IVA
				$arr[$res->periodoAcreditamiento]['gastosnoident']+=$res->iva;
			}
			if($res->idtipoiva==8){//Inversiones para generar ingresos NO identificados para IVA
				$arr[$res->periodoAcreditamiento]['invernoident']+=$res->iva;
			}	
		}
			
		return $arr;
	}
	
	function causado($ejer,$tasa,$num){
		$sql = $this->resumenGeneralR21Model->causado($ejer,$tasa);
		$base=array();
		for($i=1;$i<=12;$i++){
			$base[$i][$tasa]=0;
			
		}
		while($row=$sql->fetch_array()){
			$ta=explode('-',$row[$tasa]);
			$base[$row['periodoAcreditamiento']][$tasa]+=$ta[$num];
		}
	return $base;
	}
	function resumenivas($ejer){
		$sql = $this->resumenGeneralR21Model->resumenivas($ejer);
		$base=array();
		for($i=1;$i<=12;$i++){
			$base[$i]['cargo']=0;
			$base[$i]['favor']=0;
			$base[$i]['derivada_ajuste']=0;
			$base[$i]['cantidadreintegrarse']=0;
			$base[$i]['ivaretenido']=0;
		}
		while($row=$sql->fetch_object()){
			
			$base[$row->mes]['cargo']=$row->cargo;
			$base[$row->mes]['favor']=$row->favor;
			$base[$row->mes]['derivada_ajuste']=$row->derivada_ajuste;
			$base[$row->mes]['cantidadreintegrarse']=$row->cantidadreintegrarse;
			$base[$row->mes]['ivaretenido']=$row->ivaretenido;
		}
		
		return $base;
	}

}
?>