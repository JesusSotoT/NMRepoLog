<?php
require('common.php');//Funciones basicas

//Carga el modelo para este controlador
require("models/conciliacionacontia.php");

// /////  - - - - - -      CONCILIACION     - - - - - - - - -   // /////
class conciliacionAcontia extends Common{
// * * * * *     FUNCIONES BASE      * * * * *
	public $conciliacionAcontiaModel;
	function __construct(){
		$this->ConciliacionAcontiaModel = new ConciliacionAcontiaModel();
		$this->ConciliacionAcontiaModel->connect();
	}

	function __destruct(){
		$this->ConciliacionAcontiaModel->close();
	}
	
	function verCaratulaConciliacion(){
		$cuentasB = $this->ConciliacionAcontiaModel->cuentasBancarias();
		$periodo = $this->ConciliacionAcontiaModel->periodos();
		$ejercicio = $this->ConciliacionAcontiaModel->ejercicio();
		$bancos = $this->ConciliacionAcontiaModel->validaBancos();
		include ('views/conciliacionacontia/conciliacionacontia.php');
	}
	function conciliados(){
		$this->BorrarDatos();
		unset($_SESSION['datos']['idejercicio']);
		$_SESSION['datos']['periodo'] 	= $_REQUEST['periodo'];
		$_SESSION['datos']['idejercicio'] 	= $_REQUEST['ejercicio'];
		$_SESSION['datos']['idbancaria']	= $_REQUEST['idbancaria'];
		//berifica si esta finalizada sisi sera nivel reporte
		$bandera = 0;
		$verifica = $this->ConciliacionAcontiaModel->existeFinconciliacion($_REQUEST['idbancaria'], $_REQUEST['periodo'], $_REQUEST['ejercicio']);
		if($verifica->num_rows>0){
			$_SESSION['datos']['finalizada']=1;
			$bandera =  1;
				
			}
		if($bandera==1){
			echo 1;
		}
		else{
		
		//ultimo saldo final registrado <periodo en saldo_conciliacion es el inicial 
		$saldoInicialFinal = $this->ConciliacionAcontiaModel->saldoConciliacion($_REQUEST['idbancaria'], $_REQUEST['periodo']);
		if($saldoInicialFinal->num_rows>0){
			
			$saldoInicial = $saldoInicialFinal->fetch_assoc();
			$saldo = $saldoInicial['saldo_final'];
			
		}else{
			
			$infocuenta = $this->ConciliacionAcontiaModel->infocuentasBancarias($_REQUEST['idbancaria']);
			$ejercicio = $this->ConciliacionAcontiaModel->NombreEjercicio($_REQUEST['ejercicio']);
			$saldo = $this->ConciliacionAcontiaModel->Saldos($infocuenta['account_id'],$ejercicio.'-'.$_REQUEST['periodo']."-01",'Antes',1);
			$_SESSION['datos']['sinconciliacion']=1;
		}
		
		$_SESSION['datos']['saldoEmpresainicial']=$saldo;
			$datos = $this->ConciliacionAcontiaModel->sumMovdelPeriodoPoliza($_REQUEST['idbancaria'], $_REQUEST['periodo'], $_REQUEST['ejercicio']);
				while($row = $datos->fetch_object()){
					if($row->TipoMovto=="Abono"){
						$saldo -= $row->importe;
					}
					if($row->TipoMovto=="Cargo"){
						$saldo += $row->importe;
					}
				}
			$_SESSION['datos']['saldoEmpresa']=$saldo;
		
		
		$saldoFinalEstadoCuenta = $this->ConciliacionAcontiaModel->salfoFinalEstadoCuenta($_REQUEST['idbancaria'], $_REQUEST['periodo'],$_REQUEST['ejercicio']);
		$_SESSION['datos']['saldoEstadoCuenta'] = $saldoFinalEstadoCuenta->saldofinal;
		
		$ids2 = $this->ConciliacionAcontiaModel->movimientosConciliadosMarcadosXperiodo($_REQUEST['periodo']);
		$ids = $this->ConciliacionAcontiaModel->idsMovPoliConciliacion();
		
		$conciliado = $this->ConciliacionAcontiaModel->movimientosConciliados($_REQUEST['ejercicio'], $_REQUEST['periodo'], $_REQUEST['idbancaria'],$ids);
		$conciliadoMarcados = $this->ConciliacionAcontiaModel->movimientosConciliadosMarcados($_REQUEST['ejercicio'], $_REQUEST['periodo'], $_REQUEST['idbancaria'],$ids2);
		$conciliados = array();
		
		while($row = $conciliadoMarcados->fetch_assoc()){//se muestra a nivel poliza
			if($row['TipoMovto'] == "Abono"){ $retiro = $row['Importe']; $deposito="0.00";}
			if($row['TipoMovto'] == "Cargo"){ $retiro = '0.00'; $deposito=$row['Importe'];}
			$conciliados['retiro']=$retiro;
			$conciliados['deposito']=$deposito;
			$conciliados['fecha']=$row['fecha'];
			$conciliados['numero']=$row['numero'];
			$conciliados['concepto']=$row['concepto'];
			$conciliados['idmov']=$row['idmov'];
			
			$_SESSION['conciliados'][]=$conciliados;
		}
		$conciliados = array();// sumar importe porsi son varias unidas a un mov creo si son varios mov no nesesita sumar el importe no cuadradara y lo mandara amanual
		while($row = $conciliado->fetch_assoc()){//se muestra a nivel poliza
			if($row['TipoMovto'] == "Abono"){ $retiro = number_format($row['Importe'],2,'.',','); $deposito="0.00";}
			if($row['TipoMovto'] == "Cargo"){ $retiro = '0.00'; $deposito = number_format($row['Importe'],2,'.',',');;}
			$conciliados['retiro']=$retiro;
			$conciliados['deposito']=$deposito;
			$conciliados['fecha']=$row['fecha'];
			$conciliados['numero']=$row['numero'];
			$conciliados['idmov']=$row['idmov'];
			$conciliados['concepto']=$row['concepto'];
			$movBan = $this->ConciliacionAcontiaModel->conciliaMovimientosPrimerVez($row['idmovbanc'], $row['idmov']);
			if($movBan){
				//$this->ConciliacionAcontiaModel->cambiaStatus($row['idmov']);
			}
			$_SESSION['conciliados'][]=$conciliados;
		}
		$ids = $this->ConciliacionAcontiaModel->idsMovPoliConciliacion();
		$ejercicio = $this->ConciliacionAcontiaModel->NombreEjercicio($_REQUEST['ejercicio']);
		$polizas = $this->ConciliacionAcontiaModel->movimientosSinConciliar($_REQUEST['ejercicio'], $_REQUEST['periodo'], $_REQUEST['idbancaria'],$ids,$ejercicio.'-'.$_REQUEST['periodo']."-31");
		$movpolizas = array();
		if($polizas->num_rows>0){
			while($row = $polizas->fetch_assoc()){
				if($row['TipoMovto'] == "Abono"){ $retiro = number_format($row['Importe'],2,'.',','); $deposito="0.00";}
				if($row['TipoMovto'] == "Cargo"){ $retiro = '0.00'; $deposito=number_format($row['Importe'],2,'.',',');;}
				$movpolizas['abono']=$retiro;
				$movpolizas['cargo']=$deposito;
				$movpolizas['fecha']=$row['fecha'];
				$movpolizas['numero']=$row['numero'];
				$movpolizas['concepto']=$row['concepto'];
				$movpolizas['idmov']=$row['idmov'];
				
				$_SESSION['movpolizas'][]=$movpolizas;
			}
		}else{ $_SESSION['Nohaypolizas']=1;}
		
		// $polizasdesca = $this->ConciliacionAcontiaModel->movimientosSinConciliarDescartar($_REQUEST['ejercicio'], $_REQUEST['periodo'], $_REQUEST['idbancaria'],$ids);
		// $movpolizas = array();
		// if($polizasdesca->num_rows>0){
			// while($row = $polizasdesca->fetch_assoc()){
				// if($row['TipoMovto'] == "Abono"){ $retiro = number_format($row['Importe'],2,'.',','); $deposito="0.00";}
				// if($row['TipoMovto'] == "Cargo"){ $retiro = '0.00'; $deposito=number_format($row['Importe'],2,'.',',');;}
				// $movpolizas['abono']=$retiro;
				// $movpolizas['cargo']=$deposito;
				// $movpolizas['fecha']=$row['fecha'];
				// $movpolizas['numero']=$row['numero'];
				// $movpolizas['concepto']=$row['concepto'];
				// $movpolizas['numpol']=$row['numpol'];
				// $movpolizas['idmov']=$row['idmov'];
// 				
				// $_SESSION['movpolizasdescartar'][]=$movpolizas;
			// }
		// }
		$movBancos = $this->ConciliacionAcontiaModel->movimientosSinConciliarBanco($_REQUEST['ejercicio'], $_REQUEST['periodo'], $_REQUEST['idbancaria'],0);
		$movbanco = array();
		if($movBancos->num_rows>0){
			while($row = $movBancos->fetch_assoc()){
				$movbanco['retiro']=number_format($row['cargos'],2,'.',',');
				$movbanco['deposito']=number_format($row['abonos'],2,'.',',');
				$movbanco['fecha']=$row['fecha'];
				$movbanco['numero']=$row['numero'];
				$movbanco['concepto']=$row['concepto'];
				$movbanco['id']=$row['id'];
				
				$_SESSION['movbancos'][]=$movbanco;
				
			}
		}else{ $_SESSION['todosConciliados']=1; }
		//Nuestros
		$this->chequesCirculacion($_REQUEST['ejercicio'], $_REQUEST['periodo'], $_REQUEST['idbancaria'], $ids,$ejercicio.'-'.$_REQUEST['periodo']."-31");
		$this->nuestrosDepositos($_REQUEST['ejercicio'], $_REQUEST['periodo'], $_REQUEST['idbancaria'], $ids,$ejercicio.'-'.$_REQUEST['periodo']."-31");
		//banco
		$this->bancoDepositos($_REQUEST['ejercicio'], $_REQUEST['periodo'], $_REQUEST['idbancaria']);
		$this->bancoCargos($_REQUEST['ejercicio'], $_REQUEST['periodo'], $_REQUEST['idbancaria']);
		$_SESSION['datos']['nombreejercicio'] = $this->ConciliacionAcontiaModel->NombreEjercicio($_REQUEST['ejercicio']);
	}

}
	function saldoEmpresa(){
		$saldoConciliacion = $this->ConciliacionAcontiaModel->saldoConciliacion($idbancaria);
		$saldoFinal = $this->ConciliacionAcontiaModel->salfoFinalEstadoCuenta($idbancaria, $periodo, $ejercicio);//object
	}
	function conciliaMovimientos(){
		$movBan = $this->ConciliacionAcontiaModel->conciliaMovimientos($_REQUEST['idMovBanco'],$_REQUEST['idMovPoliza']);
		if($movBan){
				$this->ConciliacionAcontiaModel->cambiaStatus($_REQUEST['idMovPoliza']);
			}
	}
	
	function borrarDatos(){
		unset($_SESSION['conciliados']);
		unset($_SESSION['movpolizas']);
		unset($_SESSION['movbancos']);
		unset($_SESSION['Nohaypolizas']);
		unset($_SESSION['todosConciliados']);
		unset($_SESSION['datos']);
		unset($_SESSION['sinsaldo']);
		unset($_SESSION['bancocargos']);
		unset($_SESSION['bancodepositos']);
		unset($_SESSION['misdepositos']);
		unset($_SESSION['cheques']);
		unset($_SESSION['movpolizasdescartar']);
		unset($_SESSION['movpolizasdescartarnuevosinpoliza']);
		unset($_SESSION['movpolizasdescartarnuevo']);
		setcookie('periodo', '', time() - 1000);
		setcookie('ejercicio', '', time() - 1000);
		setcookie('idejercicio', '', time() - 1000);
	}
	function calculaSaldoEmpresa(){
		$saldo = $_REQUEST['saldo'];
		$datos = $this->ConciliacionAcontiaModel->sumMovdelPeriodoPoliza($_REQUEST['idbancaria'], $_REQUEST['periodo'], $_REQUEST['ejercicio']);
		while($row = $datos->fetch_object()){
			if($row->TipoMovto=="Abono"){
				$saldo -= $row->importe;
			}
			if($row->TipoMovto=="Cargo"){
				$saldo += $row->importe;
			}
		}
		echo number_format($saldo,2,'.',',');
	}
	function chequesCirculacion($ejercicio,$periodo,$idbancaria,$ids,$fecha){
		$polizasc = $this->ConciliacionAcontiaModel->movimientosSinConciliar($ejercicio, $periodo, $idbancaria,$ids,$fecha);
		$movpolizas = array();
		while($row = $polizasc->fetch_assoc()){
			if($row['formapago']==2 || $row['TipoMovto']=="Abono"){
				$movpolizas['abono']=$row['Importe'];//retiro;
				$movpolizas['fecha']=$row['fecha'];
				$movpolizas['numero']=$row['numero'];
				$movpolizas['concepto']=$row['concepto'];
				$movpolizas['idmov']=$row['idmov'];
				$_SESSION['cheques'][]=$movpolizas;
			}
		}
	}
	function nuestrosDepositos($ejercicio,$periodo,$idbancaria,$ids,$fecha){
		$polizas = $this->ConciliacionAcontiaModel->movimientosSinConciliar($ejercicio, $periodo, $idbancaria,$ids,$fecha);
		$movpolizas = array();
		while($row = $polizas->fetch_assoc()){
			if($row['TipoMovto']=="Cargo"){
				$movpolizas['cargo']=$row['Importe'];//abono;
				$movpolizas['fecha']=$row['fecha'];
				$movpolizas['numero']=$row['numero'];
				$movpolizas['concepto']=$row['concepto'];
				$movpolizas['idmov']=$row['idmov'];
				$_SESSION['misdepositos'][]=$movpolizas;
			}
		}
	}
	function bancoDepositos($ejercicio,$periodo,$idbancaria){
		$bancos = $this->ConciliacionAcontiaModel->movimientosSinConciliarBanco($ejercicio, $periodo, $idbancaria,1);
		$movBancosde = array();
		while($row = $bancos->fetch_assoc()){
			//depositos del banco que no tenemos registrados
				$movBancosde['abonos']=$row['abonos'];//abono;
				$movBancosde['fecha']=$row['fecha'];
				$movBancosde['numero']=$row['folio'];
				$movBancosde['concepto']=$row['concepto'];
				$movBancosde['id']=$row['id'];
				$_SESSION['bancodepositos'][]=$movBancosde;
		}
	}
	function bancoCargos($ejercicio,$periodo,$idbancaria){
		$bancos = $this->ConciliacionAcontiaModel->movimientosSinConciliarBanco($ejercicio, $periodo, $idbancaria,2);
		$movBancos = array();
		while($row = $bancos->fetch_assoc()){
			//cargos del banco que no tenemos registrados
				$movBancos['cargos']=$row['cargos'];//abono;
				$movBancos['fecha']=$row['fecha'];
				$movBancos['numero']=$row['folio'];
				$movBancos['concepto']=$row['concepto'];
				$movBancos['id']=$row['id'];
				$_SESSION['bancocargos'][]=$movBancos;
		}
	}
	function finalizaConciliacion(){
		$hecho = $this->ConciliacionAcontiaModel->finalizaConciliacion($_REQUEST['idbancaria'],$_REQUEST['periodo'],$_REQUEST['ejercicio'],number_format($_REQUEST['saldoinicial'],2,'.',''),number_format($_REQUEST['saldofinal'],2,'.',''));
		if($hecho == 1){
			$this->borrarDatos();
			unset($_SESSION['datos']['idejercicio']);
			echo 1;
		}else{
			echo 0;
		}
	}
	function estadocuentafiltro(){
		$cuentasB = $this->ConciliacionAcontiaModel->cuentasBancarias();
		$periodo = $this->ConciliacionAcontiaModel->periodos();
		$ejercicio = $this->ConciliacionAcontiaModel->ejercicio();
		include ('views/reports/estadocuenta.php');
	
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
				case 12:$p = 'Diciembre'.$saldos;break;
			}
			return $p;
	}
	
	function reporteEstadoCuenta(){
		$logo = $this->ConciliacionAcontiaModel->logo();
		$periodo=$this->NombrePeriodo($_REQUEST['periodo']);
		$ejercicio = $this->ConciliacionAcontiaModel->NombreEjercicio($_POST['ejercicio']);
		$empresa = $this->ConciliacionAcontiaModel->empresa();
		$estadoCuenta = 	$this->ConciliacionAcontiaModel->estadoCuentaBancario($_REQUEST['cuentabancaria'],$_REQUEST['periodo'],$_REQUEST['ejercicio']);
		$infocuenta = $this->ConciliacionAcontiaModel->infocuentasBancarias($_REQUEST['cuentabancaria']);
		include ('views/reports/reporteestadocuenta.php');
		
	}
	function descartarMov(){
		$maximo = count($_REQUEST['descartar']);
		$maximo = (intval($maximo)-1);
		for($i=0;$i<=$maximo;$i++){
			$this->ConciliacionAcontiaModel->cambiaStatus($_REQUEST['descartar'][$i]);
		}
		unset($_SESSION['datos']['sinconciliacion']);
		if(!isset($_SESSION['datos']['sinconciliacion'])){
			echo 1;
		}
	}
function descartar(){
	unset($_SESSION['movpolizasdescartar']);
	$polizasdesca = $this->ConciliacionAcontiaModel->movimientosSinConciliarDescartar($_REQUEST['nameejercicio'], $_REQUEST['periodo'], $_REQUEST['idbancaria'],$_REQUEST['desde'],$_REQUEST['hasta']);
		$movpolizas = array();
		if($polizasdesca->num_rows>0){
			while($row = $polizasdesca->fetch_assoc()){
				if($row['TipoMovto'] == "Abono"){ $retiro = number_format($row['Importe'],2,'.',','); $deposito="0.00";}
				if($row['TipoMovto'] == "Cargo"){ $retiro = '0.00'; $deposito=number_format($row['Importe'],2,'.',',');;}
				$movpolizas['abono']=$retiro;
				$movpolizas['cargo']=$deposito;
				$movpolizas['fecha']=$row['fecha'];
				$movpolizas['numero']=$row['numero'];
				$movpolizas['concepto']=$row['concepto'];
				$movpolizas['numpol']=$row['numpol'];
				$movpolizas['idmov']=$row['idmov'];
				
				$_SESSION['movpolizasdescartar'][]=$movpolizas;
			}
		}
		echo '<script>window.location="index.php?c=conciliacionAcontia&f=verCaratulaConciliacion"; </script>';
}
function volverAdescartar(){
	unset($_SESSION['movpolizasdescartarnuevo']);
	$_SESSION['movpolizasdescartarnuevosinpoliza']=0;
	$polizasdesca = $this->ConciliacionAcontiaModel->movimientosSinConciliarDescartar($_REQUEST['nameejercicio2'], $_REQUEST['periodo2'], $_REQUEST['idbancaria2'],$_REQUEST['desde2'],$_REQUEST['hasta2']);
		$movpolizas = array();
		if($polizasdesca->num_rows>0){
			while($row = $polizasdesca->fetch_assoc()){
				if($row['TipoMovto'] == "Abono"){ $retiro = number_format($row['Importe'],2,'.',','); $deposito="0.00";}
				if($row['TipoMovto'] == "Cargo"){ $retiro = '0.00'; $deposito=number_format($row['Importe'],2,'.',',');;}
				$movpolizas['abono']=$retiro;
				$movpolizas['cargo']=$deposito;
				$movpolizas['fecha']=$row['fecha'];
				$movpolizas['numero']=$row['numero'];
				$movpolizas['concepto']=$row['concepto'];
				$movpolizas['numpol']=$row['numpol'];
				$movpolizas['idmov']=$row['idmov'];
				$_SESSION['movpolizasdescartarnuevosinpoliza']=1;
				$_SESSION['movpolizasdescartardenuevo'][]=$movpolizas;
			}
		}
		echo '<script>window.location="index.php?c=conciliacionAcontia&f=verCaratulaConciliacion"; </script>';
		
}
function verReporteConciliacion(){
	$cuentasB = $this->ConciliacionAcontiaModel->cuentasBancarias();
	$periodo = $this->ConciliacionAcontiaModel->periodos();
	$ejercicio = $this->ConciliacionAcontiaModel->ejercicio();
	include ('views/conciliacionacontia/filtroreporteconciliacion.php');
	
}
function ReporteConciliacion(){
	
	$logo=$this->ConciliacionAcontiaModel->logo();
	$periodoreporte=$this->NombrePeriodo($_REQUEST['periodo']);
	$empresa = $this->ConciliacionAcontiaModel->empresa();
	$infocuentas = $this->ConciliacionAcontiaModel->infocuentasBancarias($_REQUEST['cuentabancaria']);
	$saldoFinalEstadoCuenta = $this->ConciliacionAcontiaModel->salfoFinalEstadoCuenta($_REQUEST['cuentabancaria'], $_REQUEST['periodo'],$_REQUEST['ejercicio']);
	$saldoEstadoCuenta= $saldoFinalEstadoCuenta->saldofinal;
	
	$saldoInicialFinal = $this->ConciliacionAcontiaModel->saldoConciliacion($_REQUEST['cuentabancaria'], $_REQUEST['periodo']);
		if($saldoInicialFinal->num_rows>0){
			
			$saldoInicial = $saldoInicialFinal->fetch_assoc();
			$saldo = $saldoInicial['saldo_final'];
			
		}else{
			
			$infocuenta = $this->ConciliacionAcontiaModel->infocuentasBancarias($_REQUEST['cuentabancaria']);
			$ejercicio = $this->ConciliacionAcontiaModel->NombreEjercicio($_REQUEST['ejercicio']);
			$saldo = $this->ConciliacionAcontiaModel->Saldos($infocuenta['account_id'],$ejercicio.'-'.$_REQUEST['periodo']."-01",'Antes',1);
			//$_SESSION['datos']['sinconciliacion']=1;
		}
		
		$saldoEmpresainicial=$saldo;
		$datosfinal = $this->ConciliacionAcontiaModel->saldoConciliacionReport($_REQUEST['cuentabancaria'], $_REQUEST['periodo'], $_REQUEST['ejercicio']);
		if($row = $datosfinal->fetch_assoc()){
			$saldoEmpresa = $row['saldo_final'];
			$finconciliacion=0;
		}else{
			$finconciliacion=1;
		}
			
				
	$nombreejercicio = $this->ConciliacionAcontiaModel->NombreEjercicio($_REQUEST['ejercicio']);
	$ids = $this->ConciliacionAcontiaModel->idsMovPoliConciliacionReporte($_REQUEST['periodo'],$_REQUEST['cuentabancaria'],$_REQUEST['ejercicio']);
	
	$chequescircula = $this->ConciliacionAcontiaModel->movimientosSinConciliarReporte($_REQUEST['ejercicio'], $_REQUEST['periodo'], $_REQUEST['cuentabancaria'],$ids,$nombreejercicio.'-'.$_REQUEST['periodo']."-31");
	$depositos = $this->ConciliacionAcontiaModel->movimientosSinConciliarReporte($_REQUEST['ejercicio'], $_REQUEST['periodo'], $_REQUEST['cuentabancaria'],$ids,$nombreejercicio.'-'.$_REQUEST['periodo']."-31");
	include ('views/conciliacionacontia/reporteconciliacion.php');
	
	
}
	function verificaMontosConciliados(){
		$regresaMov = "";
		foreach ($_REQUEST['idMovBancos'] as $idval){
			$idMovimientoPoliza = $this->ConciliacionAcontiaModel->idsMovBancario($idval);//ids del movimientos e importe 
			$imporConcepto = $this->ConciliacionAcontiaModel->importeConceptoBancario($idval);
			$separa = explode("/", $imporConcepto);
			$importeBanco = $separa[0];
			$conceptoBanco = $separa[1];
			$impMovPoliza = $this->ConciliacionAcontiaModel->verificaMontosConciliados($idMovimientoPoliza);//importe de las polizas
			if($importeBanco!=$impMovPoliza){
				$this->ConciliacionAcontiaModel->desconsilia_Movnulosbancos($idMovimientoPoliza,$idval);
				$regresaMov .= $conceptoBanco."\n";
			}
		}
		echo $regresaMov;
		
	}
	function verificaMontosConciliadosPolizas(){
		$regresaMov = "";
		foreach ($_REQUEST['idMovPolizas'] as $idval){
			$idDocumentoPoliza = $this->ConciliacionAcontiaModel->idsMovBancarioPoliza($idval);//ids del movimientos e importe 
			$imporConcepto = $this->ConciliacionAcontiaModel->importeConceptoBancarioPoliza($idval);
			$separa = explode("/", $imporConcepto);
			$importeBanco = $separa[0];
			$conceptoBanco = $separa[1];
			$impMovDocumento = $this->ConciliacionAcontiaModel->verificaMontosConciliadosPolizas($idDocumentoPoliza);//importe de los documentos
			if($importeBanco!=$impMovDocumento){
				$this->ConciliacionAcontiaModel->desconsilia_MovnulosbancosPoliza($idDocumentoPoliza,$idval);
				$regresaMov .= $conceptoBanco."\n";
			}
		}
		echo $regresaMov;
		
	}
/* 	A C O N T I A		 C O N 		B A N C O S		 */	

function verificaConciliacionBancos(){
	$valida = $this->ConciliacionAcontiaModel->validaEstado($_REQUEST['periodo'], $_REQUEST['ejercicio'], $_REQUEST['idbancaria']);
	if($valida->num_rows>0){
		$finconciiado = $this->ConciliacionAcontiaModel->verifaFinConciliacionBancos($_REQUEST['idbancaria'], $_REQUEST['periodo'], $_REQUEST['ejercicio']);
		if($finconciiado->num_rows>0){
			echo 2;//si ya esta conciliado osea en la tabla de saldos conciliacionBancos puede proceder
		}else{
			echo 1;//si trae movimientos esq aun no finaliza no podra proceder
		}
	}else{
		echo 0;//el estado de cuenta no esta importado
	}
}

/*				FIN ACONTIA CON BANCOS		*/	
		
}
?>