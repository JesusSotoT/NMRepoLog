<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/anexosIVACausadoAcreditable.php");

class anexosIVACausadoAcreditable extends Common
{
	public $anexosIVACausadoAcreditableModel;

	function __construct(){
		//Se crea el objeto que instancia al modelo que se va a utilizar
		$this->anexosIVACausadoAcreditableModel = new anexosIVACausadoAcreditableModel();
	}

	function filtro(){
		$ejercicio = $this->consulta_ejercicio();
		require('views/fiscal/pago_provicional/filtroAnexos_IVA_Causado_Acreditable.php');
	}
	function reporte(){
		$acredita=$_REQUEST['acr_iva_ret'];
		$per = $_REQUEST["per"];
		$toexcel = $_REQUEST["toexcel"];
		$ejercicio = $this->consulta_ejercicio($_REQUEST["ejercicio"]);
		$ejer = $_REQUEST["ejercicio"];
		if($per==1){
			$ini = $_REQUEST["per_ini"];
			$fin = $_REQUEST["per_fin"];
			$baseComprado = $this->valorBase($ejer,$ini,$fin,$per);
			$ivaComprado = $this->valorIva($ejer,$ini,$fin,$per);
			$arr=$this->desglose($ejer, $ini, $fin, $per);
			$per_fech_ini = "per_ini";
			$per_fech_fin = "per_fin";
				
		}
		if($per==0){
			$ini = $_REQUEST["fecha_ini"];
			$fin = $_REQUEST["fecha_fin"];
			$baseComprado = $this->valorBase(0,$ini,$fin,$per);
			$ivaComprado = $this->valorIva(0,$ini,$fin,$per);
			$arr=$this->desglose(0, $ini, $fin, $per);
			$per_fech_ini = "fecha_ini";
			$per_fech_fin = "fecha_fin";
			
		}
		
		$organizacion = $this->organizacion();

		$totalBaseComprado = @$baseComprado["16%"]+@$baseComprado["11%"]+@$baseComprado["0%"]+@$baseComprado["Exenta"]+@$baseComprado["Otra Tasa 1"]+@$baseComprado["Otra Tasa 2"];
		$totalIvaComprado = @$ivaComprado["16%"]+@$ivaComprado["11%"]+@$ivaComprado["Otra Tasa 1"]+@$ivaComprado["Otra Tasa 2"];
		$meses = array('1' => 'Enero','2' => 'Febrero','3' => 'Marzo','4' => 'Abril','5' => 'Mayo','6' => 'Junio','7' => 'Julio','8' => 'Agosto','9' => 'Septiembre','10' => 'Octubre','11' => 'Noviembre','12' => 'Diciembre');

		require('views/fiscal/pago_provicional/Anexos_IVA_Causado_Acreditable.php');
		
	}
	function consulta_ejercicio($ejercicio=0){
		$qry_eje=$this->anexosIVACausadoAcreditableModel->con_ejercicio($ejercicio);					
		return $qry_eje;				
		
	}
	function organizacion(){
		$res="";
		$qry_eje=$this->anexosIVACausadoAcreditableModel->organizacion();
			$res=$qry_eje->fetch_object();
			return $res;
		
	} 
	function valorBase($ejer=0,$ini,$fin,$per){//compras y gastos
		$arr = array();
		$qry_eje=$this->anexosIVACausadoAcreditableModel->valorBase($ejer,$ini,$fin,$per);
		$rete=0;
		while($valor = $qry_eje->fetch_object()){
			$arr[$valor->tasa]=$valor->base;
			$rete+=$valor->retenido;
		}
			$arr['ivaretenido']=$rete;
		return $arr;
	}
	function valorIva($ejer,$ini,$fin,$per){//ivA CYG
		$arr = array();
		$qry_eje=$this->anexosIVACausadoAcreditableModel->valorIva($ejer,$ini,$fin,$per);
		while($valor = $qry_eje->fetch_object()){
			$arr[$valor->tasa]=$valor->iva;
		}			
		return $arr;
	}
	function desglose($ejer,$ini,$fin,$per){
		$arr=array();
		$arr["tasa16"]=0;
		$arr["tasa11"]=0;
		$arr["tasa0"]=0;
		$arr["tasaExenta"]=0;
		$arr["tasa15"]=0;
		$arr["tasa10"]=0;
		$arr["otrasTasas"]=0;
		$arr["16%"]=0;
		$arr["11%"]=0;
		$arr["0%"]=0;
		$arr["Exenta"]=0;
		$arr['retenido']=0;
		$arr["ivaRetenido"]=0;
		
		$desglose=$this->anexosIVACausadoAcreditableModel->desglose($ejer,$ini,$fin,$per);
		$i=0;
		$tasa16 = new stdClass();
		$tasa16->baset=0;
		$tasa16->ivat=0;
		$tasa11 = new stdClass();
		$tasa11->baset=0;
		$tasa11->ivat=0;
		$tasa0 = new stdClass();
		$tasa0->baset=0;
		$tasa0->ivat=0;
		$tasaExenta = new stdClass();
		$tasaExenta->baset=0;
		$tasaExenta->ivat=0;
		$otrasTasas = new stdClass();
		$otrasTasas->baset=0;
		$otrasTasas->ivat=0;
		$ivaretenido=0;
		while($valor = $desglose->fetch_array()){
			$t16=$this->separaIvaValor($valor["tasa16"]);
			$t11=$this->separaIvaValor($valor["tasa11"]);
			$t0=$this->separaIvaValor($valor["tasa0"]);
			$tExenta=$this->separaIvaValor($valor["tasaExenta"]);
			$t15=$this->separaIvaValor($valor["tasa15"]);
			$t10=$this->separaIvaValor($valor["tasa10"]);
			$oTasas=$this->separaIvaValor($valor["otrasTasas"]);
		
			$tasa16->baset+=$t16->base;
			$tasa16->ivat+=$t16->iva;
			$tasa11->baset+=$t11->base;
			$tasa11->ivat+=$t11->iva;
			$tasa0->baset+=$t0->base;
			$tasa0->ivat+=$t0->iva;
			$tasaExenta->baset+=$tExenta->base;
			$tasaExenta->ivat+=$tExenta->iva;
			$otrasTasas->baset+=$oTasas->base;
			$otrasTasas->ivat+=$oTasas->iva;
			@$ivaretenido+=$valor['ivaRetenido'];
		}
		$arr["tasa16"]=$tasa16;
		$arr["tasa11"]=$tasa11;
		$arr["tasa0"]=$tasa0;
		$arr["tasaExenta"]=$tasaExenta;
		$arr["otrasTasas"]=$otrasTasas;
		$arr["ivaRetenido"]=$ivaretenido;
		$desglose2=$this->anexosIVACausadoAcreditableModel->pagadoacredita($ejer,$ini,$fin,$per);
		$mesesanteriores=$this->anexosIVACausadoAcreditableModel->mesanteriores($ejer,$ini,$fin,$per);
		$r=0;
		$m=0;
		while($valor2 = $desglose2->fetch_object()){
			$arr[$valor2->tasa]=$valor2;
			$r+=$valor2->retenido;
			
		}
		while($valor3 = $mesesanteriores->fetch_array()){
			$m+=$valor3['mes'];
		}
		$arr['mes']=$m;
		$arr['retenido']=$r;
		//print_r($arr["0%"]);
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