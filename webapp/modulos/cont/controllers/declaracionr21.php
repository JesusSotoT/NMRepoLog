<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/declaracionR21.php");

class declaracionR21 extends Common
{
	public $declaracionR21Model;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar
		$this->declaracionR21Model = new declaracionR21Model();
	}

	function filtro(){
		$ejercicio=$this->consulta_ejercicio();
		$tasaIVA = $this->tasas_iva();
		require('views/fiscal/declaraciones/filtroDeclaracionR21.php');
	}
	function reporte(){
		$arr=array();
		$acr_iva = $_REQUEST["acr_iva"];
		//si acredito acredito al 100
		$retenidocontri=$_REQUEST['retenidocontri'];
		
		$ejercicio=$this->consulta_ejercicio($_REQUEST["ejercicio"]);
		$ejer = $_REQUEST["ejercicio"];
		$toexcel = $_REQUEST["toexcel"];
		$per_ini = $_REQUEST["per_ini"];
		$prop_select = $_REQUEST["prop_select"];
		$prop = $_REQUEST["prop"];
		$montoAjuste = $_REQUEST["cant1"];
		$cantidadReintegrarse = $_REQUEST["cant2"];
		$otrasCargo = $_REQUEST["cant3"];
		$otrasFavor = $_REQUEST["cant4"];
		$devolucionObtenida = $_REQUEST["cant5"];
		$acredAnteriores = $_REQUEST["cant6"];
		$iepsAcred = $_REQUEST["cant7"];
		/////
		
		if($acr_iva==0){//si esta checado
			$retenidoc=$this->declaracionR21Model->retenidomes($per_ini,$ejer);
			$retenidocontri=$retenidoc->retenido;
		}
		if($acr_iva==1){//si no se acredita tomara el valor del mes a cargo y a favor el cargo del mes anterior
			$retprove=$this->declaracionR21Model->retenidoiv($per_ini,$ejer);
			$otrasCargo+=$retprove->noacredita;
			$afavor=$this->declaracionR21Model->cargomesanterior($per_ini-1, $ejer);
			$otrasFavor+=$afavor->cargo;
		}
		////
		
		
		//$ivaTransvienesgravados= $this->declaracionR21Model->totalTasaIvaAcr("16%",$per_ini,$ejer,1);
		//$ivaadquisicioninverciones=$this->ivaadquisicioninverciones($per_ini,$ejer,"1");
		$organizacion = $this->organizacion();
		$totalBaseIva16 = $this->totalBaseIva("16%",$per_ini,$ejer,0);//se le debe restar el iva pagado no acreditable
		$totalBaseIva11 = $this->totalBaseIva("11%",$per_ini,$ejer,0);
		$totalBaseIva0 = $this->totalBaseIva2("0%",$per_ini,$ejer);
		$totalBaseIvaExcento = $this->totalBaseIva2("Exenta",$per_ini,$ejer);
		
		//importacion que pasa si se incluye el pagado no acreditabel
		$totalbaseimport16= $this->declaracionR21Model->totalBaseIvaImpor("16%",$per_ini,$ejer,1,0);
		$totalbaseimport11= $this->declaracionR21Model->totalBaseIvaImpor("11%",$per_ini,$ejer,1,0);
		$ivaimport16=$this->declaracionR21Model->totalTasaIvaAcr("16%",$per_ini,$ejer,2);
		$ivaimport11=$this->declaracionR21Model->totalTasaIvaAcr("11%",$per_ini,$ejer,2);
		$arr = $this->ivaXTipo($ejer,$per_ini);
		
		if($_REQUEST["inc_iva"] == 0){
			$totalBaseIva16 = $this->totalBaseIva2("16%",$per_ini,$ejer);//se le debe restar el iva pagado no acreditable
			$totalBaseIva11 = $this->totalBaseIva2("11%",$per_ini,$ejer);
			
			$totalbaseimport16= $this->declaracionR21Model->totalBaseIvaImpor2("16%",$per_ini,$ejer,1,1);
			$totalbaseimport11= $this->declaracionR21Model->totalBaseIvaImpor2("11%",$per_ini,$ejer,1,1);
			$ivaimport16=$this->declaracionR21Model->totalTasaIvaAcr2("16%",$per_ini,$ejer,2);
			$ivaimport11=$this->declaracionR21Model->totalTasaIvaAcr2("11%",$per_ini,$ejer,2);
			$arr = $this->ivaXTipo($ejer,$per_ini,1);
		}else{
			
		}
		
				////fin importacion
		/// acorde a causado 
		$totalBaseIvaImp16 = $this->declaracionR21Model->causado($per_ini, $ejer, '16',1);
		$totalBaseIvaImp11 = $this->declaracionR21Model->causado($per_ini, $ejer, '11',1);
		$totalBaseIvacausa0 =$this->declaracionR21Model->causado($per_ini, $ejer, '0',1);
		$totalBaseIvacausaotros=$this->declaracionR21Model->causado($per_ini, $ejer, 'otra',1);
		$sumaactosgravados=$totalBaseIvaImp16+$totalBaseIvaImp11+$totalBaseIvacausa0+$totalBaseIvacausaotros;
		$impuestocausado=($totalBaseIvaImp16*0.16)+($totalBaseIvaImp11*0.11);
		$totalBaseIvacausaExenta=$this->declaracionR21Model->causado($per_ini, $ejer, 'ex',1);
		
		
		
		
		//$causado=$this->declaracionR21Model->causado($per_ini, $ejer, '16',2)+$this->declaracionR21Model->causado($per_ini, $ejer, '11',2);
		
		$totalTasaIvaAcr16 = $this->declaracionR21Model->totalTasaIvaAcr("16%",$per_ini,$ejer,"0");
		$totalTasaIvaAcr11 = $this->declaracionR21Model->totalTasaIvaAcr("11%",$per_ini,$ejer,"0");
		$totalTasaIvaAcrImp16 = $this->declaracionR21Model->totalTasaIvaAcr("16%",$per_ini,$ejer,"1");
		$totalTasaIvaAcrImp11 = $this->declaracionR21Model->totalTasaIvaAcr("11%",$per_ini,$ejer,"1");
		
		if($_REQUEST["inc_iva"] == 0){//si toma en cuenta ivapagadono acreditable
			// $ivapagadono=$this->declaracionR21Model->pagadonpacredita($per_ini, $ejer);
			// $otrasCargo+=$ivapagadono->noacredita;
			$totalTasaIvaAcr16 = $this->declaracionR21Model->totalTasaIvaAcr2("16%",$per_ini,$ejer,"0");
			$totalTasaIvaAcr11 = $this->declaracionR21Model->totalTasaIvaAcr2("11%",$per_ini,$ejer,"0");
		}
		
		$efectivamentepagado=$totalTasaIvaAcr16->IVA+$totalTasaIvaAcr11->IVA+$ivaimport16->IVA+$ivaimport11->IVA;
		
		$sumagravados=$arr['GastosGravadosNacional']+$arr['GastosGravadosExtrangeros']+$arr['InvGravadosNacional']+$arr['InvGravadosExtrangeros'];
		$ivabienesutilizados=$efectivamentepagado-$sumagravados-$arr['GastosExentos']-$arr['InvExentos'];
		
		$multipliart5=$ivabienesutilizados*$prop;
		$ivaacreditable= $multipliart5+$sumagravados;
		$totalacreditable=$ivaacreditable+$montoAjuste;
		$cargo=0;$favor=0;$totalcargo=0;
		$totalcargo=$impuestocausado+$cantidadReintegrarse-$retenidocontri-$totalacreditable+$otrasCargo-$otrasFavor;
		if( $totalcargo > 0 ){$cargo = $totalcargo; }
		else if( $totalcargo < 0 ){ $favor = $totalcargo *-1; }
		
		$saldofavorp=$favor-$devolucionObtenida;
		if($saldofavorp>0){ $saldofavorperiodo=$saldofavorp;}else{ $saldofavorperiodo=0.00;}
		
		$diferenciacargo=$cargo-$acredAnteriores;
		$impuestocargo=$diferenciacargo-$iepsAcred;
		if($impuestocargo>0){$impuestocargoresult=$impuestocargo;}else{$impuestocargoresult=0.00;}
		if($impuestocargo<0){$remateieps=abs($impuestocargo);}else{ $remateieps=0.00;}//si es menor toma el abs que en si es na cantidad mayor a 0 preguntar sies correcto dejarla asi en 0.00
		
		$meses = array('1' => 'Enero','2' => 'Febrero','3' => 'Marzo','4' => 'Abril','5' => 'Mayo','6' => 'Junio','7' => 'Julio','8' => 'Agosto','9' => 'Septiembre','10' => 'Octubre','11' => 'Noviembre','12' => 'Diciembre');
		require('views/fiscal/declaraciones/declaracion_r21.php');
		
	}
	function consulta_ejercicio($ejercicio=0){
		$qry_eje=$this->declaracionR21Model->con_ejercicio($ejercicio);					
		return $qry_eje;
	}
	function tasas_iva(){
		$qry_eje=$this->declaracionR21Model->tasas_iva();
		return $qry_eje;
	}
	function organizacion(){
		$res="";
		$qry_eje=$this->declaracionR21Model->organizacion();
			$res=$qry_eje->fetch_object();
			return $res;
	}
	function totalBaseIva($tasa,$per_ini,$ejer){
		$qry_eje=$this->declaracionR21Model->totalBaseIva($tasa,$per_ini,$ejer);
		$res = $qry_eje->fetch_object();
		return $res;
	}
	function totalBaseIva2($tasa,$per_ini,$ejer){
		$qry_eje=$this->declaracionR21Model->totalBaseIva2($tasa,$per_ini,$ejer);
		$res = $qry_eje->fetch_object();
		return $res;
	}
	function totalBaseIvaImp($tasa,$per_ini,$ejer,$tipoIva){
		$qry_eje=$this->declaracionR21Model->totalBaseIvaImp($tasa,$per_ini,$ejer,$tipoIva);
		$res = $qry_eje->fetch_object();
		return $res;
	}
	
	function totalIvaAcr($per_ini,$ejer){
		$qry_eje=$this->declaracionR21Model->totalIvaAcr($per_ini,$ejer);
		$res = $qry_eje->fetch_object();
		return $res;
	}
	function ivaXTipo($ejer,$per_ini,$noacreditable){
		$arr=array();
		$arr['GastosGravadosNacional']=0;
		$arr['GastosGravadosExtrangeros']=0;
		$arr['InvGravadosNacional']=0;
		$arr['InvGravadosExtrangeros']=0;
		$arr['GastosExentos']=0;
		$arr['InvExentos']=0;
		$qry_eje=$this->declaracionR21Model->ivaXTipo($ejer,$per_ini,$noacreditable);
		while($res = $qry_eje->fetch_object()){
			if($res->idtipoiva==1 && ($res->tipoTercero==1 || $res->tipoTercero==5)){
				$arr['GastosGravadosNacional']+=$res->iva;
			}
			if($res->idtipoiva==1 && ($res->tipoTercero==2 || $res->tipoTercero==6)){
				$arr['GastosGravadosExtrangeros']+=$res->iva;
			}
			if($res->idtipoiva==2 && ($res->tipoTercero==1 || $res->tipoTercero==5)){
				$arr['InvGravadosNacional']+=$res->iva;
			}
			if($res->idtipoiva==2 && ($res->tipoTercero==2 || $res->tipoTercero==6)){
				$arr['InvGravadosExtrangeros']+=$res->iva;
			}
			if($res->idtipoiva==7 && ($res->tipoTercero==2 || $res->tipoTercero==6)){//tercero 2 y 6 Gastos para generar ingresos exentos de IVA
				$arr['GastosExentos']+=$res->iva;
			}
			if($res->idtipoiva==9 && ($res->tipoTercero==2 || $res->tipoTercero==6)){//Inversiones para generar ingresos exentos de IVA
				$arr['InvExentos']+=$res->iva;
			}
				
		}
			
		return $arr;
	}
	// function ivaadquisicioninverciones($per_ini,$ejer,$filtro){
		// $qry_eje=$this->declaracionR21Model->ivaadquisicioninverciones($per_ini,$ejer,$filtro);
		// $res = $qry_eje->fetch_object();
		// return $res;
	// }
}

?>
