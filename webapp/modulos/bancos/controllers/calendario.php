<?php
    require('controllers/flujo.php');

//Carga el modelo para este controlador
require("models/calendario.php");

class Calendario extends Flujo
{
	public $FlujoModel;
	public $CalendarioModel;
	function __construct()
	{
		
		$this->CalendarioModel = new CalendarioModel();
		$this->FlujoModel = $this->CalendarioModel;
		$this->CalendarioModel->connect();
	}

	function __destruct()
	{
		
		$this->CalendarioModel->close();
	}
	function vercalendario(){
		$logo 				= $this->FlujoModel->logo();
		$moneda				= $this->FlujoModel->moneda();
		$cuentasbancarias	= $this->FlujoModel->cuentasbancariaslista();
		if( isset($_REQUEST['moneda']) ){
			/* TODAS LAS CUENTAS */
			if(	$_REQUEST['cuenta']	==	0 ){//todas las cuentas
			
				$cuentas = $this->FlujoModel->cuentasBancariasPorMoneda($_REQUEST['moneda'],0);
			}
			/* UNA SOLA CUENTA */
			else{
				$cuentas = $this->FlujoModel->cuentasBancariasPorMoneda($_REQUEST['moneda'],$_REQUEST['cuenta']);
			}
			
			$saldoInicialBancario = $saldoInicialContable = $ingresosBancarios = $egresosBancarios = $egresosTransitoMes = $ingresosTransito = $ingresosAntes = $egresosAntes = $Nodepositados = $saldoInicialTransito = 0;
			$DocIngresos = $DocIngresosNoDep = $DocDep = $DocEgresos = $DocCheques = $DocChequesNoCobrado = array();
			while($row = $cuentas->fetch_assoc()){
				$saldoInicialBancario +=  $this->CalendarioModel->saldoinicialCalendario($row['idbancaria'],$_REQUEST['fechainicio'],$_REQUEST['fechafin'],1);
				$saldoInicialContable +=  $this->CalendarioModel->saldoinicialCalendario($row['idbancaria'],$_REQUEST['fechainicio'],$_REQUEST['fechafin'],0);
				
	/* 			I N G R E S O S 				*/
				//$ingresosBancarios = 0;
				
				$ingresos = $this->CalendarioModel->ingresosBancario($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $row['idbancaria']);
				if($ingresos){
					while($ingre = $ingresos->fetch_assoc()){
						$DocIngresos[ $ingre['id'] ]['concepto']		= $ingre['concepto'];
						$DocIngresos[ $ingre['id'] ]['importe'] 		= $ingre['importe'];
						$DocIngresos[ $ingre['id'] ]['fecha'] 		= $ingre['fecha'];
						$DocIngresos[ $ingre['id'] ]['cuenta'] 		= $ingre['cuenta'];
						$DocIngresos[ $ingre['id'] ]['banco'] 		= $ingre['banco'];
						$ingresosBancarios+=$ingre['importe'];
					}
				}
				$Depositos = $this->CalendarioModel->depositosBancario($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $row['idbancaria']);
				if($Depositos){
					while($ingre = $Depositos->fetch_assoc()){
						$DocDep[ $ingre['id'] ]['concepto']		= $ingre['concepto'];
						$DocDep[ $ingre['id'] ]['importe'] 		= $ingre['importe'];
						$DocDep[ $ingre['id'] ]['fecha'] 		= $ingre['fecha'];
						$DocDep[ $ingre['id'] ]['cuenta'] 		= $ingre['cuenta'];
						$DocDep[ $ingre['id'] ]['banco'] 		= $ingre['banco'];
						$DocDep[ $ingre['id'] ]['status'] 		= "Depositado";
						
						$ingresosBancarios+=$ingre['importe'];
					}
				}
				
				
		/* 	E G R E S O S		 */	
				//$egresosBancarios = 0;
				$egresos = $this->CalendarioModel->egresosBancario($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $row['idbancaria']);
				if($egresos){
					while($ingre = $egresos->fetch_assoc()){
						$DocEgresos[ $ingre['id'] ]['concepto']		= $ingre['concepto'];
						$DocEgresos[ $ingre['id'] ]['importe'] 		= $ingre['importe'];
						$DocEgresos[ $ingre['id'] ]['fecha'] 		= $ingre['fecha'];
						$DocEgresos[ $ingre['id'] ]['cuenta'] 		= $ingre['cuenta'];
						$DocEgresos[ $ingre['id'] ]['banco'] 		= $ingre['banco'];
						$egresosBancarios+=$ingre['importe'];
					}
				}
				$cheques = $this->CalendarioModel->chequesBancario($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $row['idbancaria']);
				if($cheques){
					while($ingre = $cheques->fetch_assoc()){
						$status = $this->status($ingre['status']);
						$DocCheques[ $ingre['id'] ]['concepto']	= $ingre['concepto'];
						$DocCheques[ $ingre['id'] ]['importe'] 	= $ingre['importe'];
						$DocCheques[ $ingre['id'] ]['fecha'] 	= $ingre['fecha'];
						$DocCheques[ $ingre['id'] ]['cuenta'] 	= $ingre['cuenta'];
						$DocCheques[ $ingre['id'] ]['banco'] 	= $ingre['banco'];
						$DocCheques[ $ingre['id'] ]['cobrado'] 	= "Cobrado";
						$DocCheques[ $ingre['id'] ]['status'] 	= $status;
						$egresosBancarios+=$ingre['importe'];
						
					}
				}
			/* T R A N S I T O */
				//$egresosTransitoMes = 0;
				$cheques = $this->CalendarioModel->chequesContable($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $row['idbancaria']);
				if($cheques){
					while($ingre = $cheques->fetch_assoc()){
						$status = $this->status($ingre['status']);
						$DocCheques[ $ingre['id'] ]['concepto']	= $ingre['concepto'];
						$DocCheques[ $ingre['id'] ]['importe'] 	= $ingre['importe'];
						$DocCheques[ $ingre['id'] ]['fecha'] 	= $ingre['fecha'];
						$DocCheques[ $ingre['id'] ]['cuenta'] 	= $ingre['cuenta'];
						$DocCheques[ $ingre['id'] ]['banco'] 	= $ingre['banco'];
						$DocCheques[ $ingre['id'] ]['cobrado'] 	= "Sin Cobrar";
						$DocCheques[ $ingre['id'] ]['status'] 	= $status;
						$egresosTransitoMes+=$ingre['importe'];
						
					}
				}
				//$ingresosTransito = 0;//depositos circulacion
				$Depositos = $this->CalendarioModel->depositosContable($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $row['idbancaria']);
				if($Depositos){
					while($ingre = $Depositos->fetch_assoc()){
						$DocDep[ $ingre['id'] ]['concepto']		= $ingre['concepto'];
						$DocDep[ $ingre['id'] ]['importe'] 		= $ingre['importe'];
						$DocDep[ $ingre['id'] ]['fecha'] 		= $ingre['fecha'];
						$DocDep[ $ingre['id'] ]['cuenta'] 		= $ingre['cuenta'];
						$DocDep[ $ingre['id'] ]['banco'] 		= $ingre['banco'];
						$DocDep[ $ingre['id'] ]['status'] 		= "Sin Depositar";
						
						$ingresosTransito+=$ingre['importe'];
					}
				}
//ingreso depositados en este mes pero registrados en un mes anterior
// estos se deben restar yaque se traen todos los depositos y cobros en el mes consultado
//pero si stan registrados en es anteriores ya ubo afectacion de saldo y se deben restar
				$ingresosAntes += $this->CalendarioModel->depositosContableantes($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $row['idbancaria']);
				$egresosAntes += $this->CalendarioModel->chequesContableantes($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $row['idbancaria']);
				
				/* no depositados*/
				//$Nodepositados = 0;
				$ingresosProyectados = $this->CalendarioModel->noDepositados($_REQUEST['fechainicio'],$_REQUEST['fechafin'], $row['idbancaria']);
				if($ingresosProyectados){
					while($ingre = $ingresosProyectados->fetch_assoc()){
						$DocIngresosNoDep[ $ingre['id'] ]['concepto'	]	= $ingre['concepto'];
						$DocIngresosNoDep[ $ingre['id'] ]['importe'] 	= $ingre['importe'];
						$DocIngresosNoDep[ $ingre['id'] ]['fecha'] 		= $ingre['fecha'];
						$DocIngresosNoDep[ $ingre['id'] ]['cuenta'] 		= $ingre['cuenta'];
						$DocIngresosNoDep[ $ingre['id'] ]['banco'] 		= $ingre['banco'];
						$Nodepositados += $ingre['importe'];
					}
				}
				
				$saldoInicialTransito += $this->CalendarioModel->saldoinicialTransito($_REQUEST['fechainicio'], $row['idbancaria']);
				
			}
/* CXC Y CXP APPMINISTRA			*/
			$cargosIngresos = array(); $totalIngresoscxc = 0;
			$cargosIn  = $this->CalendarioModel->listaCargosCalendario( 0,$_REQUEST['moneda'],$_REQUEST['fechainicio'],$_REQUEST['fechafin']);
			if($cargosIn){
				$conti =	 0;
				while ($cI = $cargosIn->fetch_assoc()) {
					 if(round(floatval($cI['saldo'])) > 0){
						$cargosIngresos[$conti]["concepto"] = $cI['concepto'];
						$cargosIngresos[$conti]["fecha_pago"] = $cI['fecha_pago'];
						$cargosIngresos[$conti]["saldo"] = $cI['saldo'];
						$conti++;
						$totalIngresoscxc+=$cI['saldo'];
					 }
				}
				
			}
			$facturasIngresos = array();
			$facturasIn = $this->CalendarioModel->listaFacturasCalendario( 0,$_REQUEST['moneda'],$_REQUEST['fechainicio'],$_REQUEST['fechafin']);
			if($facturasIn){
				$conti =	 0;
				while ($fI = $facturasIn->fetch_assoc()) {
					if(!floatval($fI['rq_tipo_cambio']))
						$nuevoImp = floatval($fI['imp_factura']);
					else
						$nuevoImp = floatval($fI['imp_factura'])*floatval($fI['rq_tipo_cambio']);
		
					$saldo = $nuevoImp - floatval($fI['pagos']);
					if(round(floatval($saldo),2) > 0)
					{
						$facturasIngresos[$conti]["desc_concepto"] = $fI['desc_concepto'];
						$facturasIngresos[$conti]["fecha_factura"] = $fI['fecha_factura'];
						$facturasIngresos[$conti]["folio"] = $fI['folio'];
						$facturasIngresos[$conti]["saldo"] = $saldo;
						$conti++;
						$totalIngresoscxc += $saldo;
					}
				}
					
			}
			$cargosEgresos = array(); $totalEgresoscxp = 0;
			$cargosEg  = $this->CalendarioModel->listaCargosCalendario( 1,$_REQUEST['moneda'],$_REQUEST['fechainicio'],$_REQUEST['fechafin']);
			if($cargosEg){
				$conti =	 0;
				while ($cI = $cargosEg->fetch_assoc()) {
					 if(round(floatval($cI['saldo'])) > 0){
						$cargosEgresos[$conti]["concepto"] = $cI['concepto'];
						$cargosEgresos[$conti]["fecha_pago"] = $cI['fecha_pago'];
						$cargosEgresos[$conti]["saldo"] = $cI['saldo'];
						$conti++;
						$totalEgresoscxp += $cI['saldo'];
					 }
				}
				
			}
			$facturasEgresos = array();
			$facturasEg = $this->CalendarioModel->listaFacturasCalendario( 1,$_REQUEST['moneda'],$_REQUEST['fechainicio'],$_REQUEST['fechafin']);
			if($facturasEg){
				$conti =	 0;
				while ($fI = $facturasEg->fetch_assoc()) {
					if(!floatval($fI['rq_tipo_cambio']))
						$nuevoImp = floatval($fI['imp_factura']);
					else
						$nuevoImp = floatval($fI['imp_factura'])*floatval($fI['rq_tipo_cambio']);
		
					$saldo = $nuevoImp - floatval($fI['pagos']);
					if(round(floatval($saldo),2) > 0)
					{
						$facturasEgresos[$conti]["desc_concepto"] = $fI['desc_concepto'];
						$facturasEgresos[$conti]["fecha_factura"] = $fI['fecha_factura'];
						$facturasEgresos[$conti]["no_factura"] = $fI['no_factura'];
						$facturasEgresos[$conti]["saldo"] = $saldo;
						$conti++;
						$totalEgresoscxp += $saldo;
					}
				}
					
			}
			
		}
	require("views/flujo/calendario.php");
	}

	function reporteAuxiliar(){
		$logo 				= $this->FlujoModel->logo();
		$moneda				= $this->FlujoModel->moneda();
		$cuentasbancarias	= $this->FlujoModel->cuentasbancariaslista();
		$infobene  = $listasBene = array();
		if($_REQUEST['beneficiario'] == 5){
			if($_REQUEST['cliente'][0]==0){
				$cli = $this->FlujoModel->cliente();
				while($c = $cli->fetch_assoc()){
					$listasBene[]=$c["id"];
				}
			}else{
				$listasBene = $_REQUEST['cliente'];
			}
		}elseif($_REQUEST['beneficiario'] == 1){
			if($_REQUEST['prove'][0]==0){
				$prv = $this->FlujoModel->proveedor();
				while($c = $prv->fetch_assoc()){
					$listasBene[]=$c["idPrv"];
				}
			}else{
				$listasBene = $_REQUEST['prove'];
			}
		}
		elseif($_REQUEST['beneficiario'] == 2){
			if($_REQUEST['empleado'][0]==0){
				$emp = $this->FlujoModel->empleados();
				while($c = $emp->fetch_assoc()){
					$listasBene[]=$c["idEmpleado"];
				}
			}else{
				$listasBene = $_REQUEST['empleado'];
			}
		}
		$DocIngresos = $DocDep = $DocEgresos = $DocCheques = $DocIngresosNoDep =  array();
		foreach ($listasBene as $idbene) {
			
		
			if($_REQUEST['beneficiario'] == 5){
				$info = $this->FlujoModel->clienteInfo($idbene); 
				$nombreBene = $info['nombre'];
				$idbeneficiario = $idbene;
				$infobene[$idbene]['nombrebene']=$nombreBene;
			}
			elseif($_REQUEST['beneficiario'] == 1){
				$info = $this->FlujoModel->datosproveedor($idbene);
				$idbeneficiario = $idbene;
				$nombreBene = $info['razon_social'];
				$infobene[$idbene]['nombrebene']=$nombreBene;
			}
			elseif($_REQUEST['beneficiario'] == 2){
				$info = $this->FlujoModel->datosempleados($idbene);
				$idbeneficiario = $idbene;
				$nombreBene = $info['nombreEmpleado']." ".$info['apellidoPaterno'];
				$infobene[$idbene]['nombrebene']=$nombreBene;
			}
			if( isset($_REQUEST['moneda']) ){
				/* TODAS LAS CUENTAS */
				if(	$_REQUEST['cuenta']	==	0 ){//todas las cuentas
				
					$cuentas = $this->FlujoModel->cuentasBancariasPorMoneda($_REQUEST['moneda'],0);
				}
				/* UNA SOLA CUENTA */
				else{
					$cuentas = $this->FlujoModel->cuentasBancariasPorMoneda($_REQUEST['moneda'],$_REQUEST['cuenta']);
				}
				
				while($row = $cuentas->fetch_assoc()){
					$banco	= $row['nombre'];
					$cuenta = $row['cuenta'];
					$ingresos = $this->CalendarioModel->ingresosAuxiliar($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $row['idbancaria'],$_REQUEST['beneficiario'],$idbeneficiario);
					if($ingresos){
						while($ingre = $ingresos->fetch_assoc()){
							$DocIngresos[$idbene][ $ingre['id'] ]['concepto']		= $ingre['concepto'];
							$DocIngresos[$idbene][ $ingre['id'] ]['importe'] 		= $ingre['importe'];
							$DocIngresos[$idbene][ $ingre['id'] ]['fecha'] 		= $ingre['fecha'];
							$DocIngresos[$idbene][ $ingre['id'] ]['cuenta'] 		= $ingre['cuenta'];
							$DocIngresos[$idbene][ $ingre['id'] ]['banco'] 		= $ingre['banco'];
							$DocIngresos[$idbene][ $ingre['id'] ]['numdoc'] 		= $ingre['numdoc'];
						}
					}
					
					$Depositos = $this->CalendarioModel->depositosAuxiliar($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $row['idbancaria'],$_REQUEST['beneficiario'],$idbeneficiario);
					if($Depositos){
						while($ingre = $Depositos->fetch_assoc()){
							$DocDep[$idbene][ $ingre['id'] ]['concepto']		= $ingre['concepto'];
							$DocDep[$idbene][ $ingre['id'] ]['importe'] 		= $ingre['importe'];
							$DocDep[$idbene][ $ingre['id'] ]['fecha'] 		= $ingre['fecha'];
							$DocDep[$idbene][ $ingre['id'] ]['cuenta'] 		= $ingre['cuenta'];
							$DocDep[$idbene][ $ingre['id'] ]['banco'] 		= $ingre['banco'];
							$DocDep[$idbene][ $ingre['id'] ]['status'] 		= "Depositado";
							$DocDep[$idbene][ $ingre['id'] ]['numdoc'] 		= $ingre['numdoc'];
						}
					}
					$egresos = $this->CalendarioModel->egresosAuxiliar($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $row['idbancaria'],$_REQUEST['beneficiario'],$idbeneficiario);
					if($egresos){
						while($ingre = $egresos->fetch_assoc()){
							$DocEgresos[$idbene][ $ingre['id'] ]['concepto']		= $ingre['concepto'];
							$DocEgresos[$idbene][ $ingre['id'] ]['importe'] 		= $ingre['importe'];
							$DocEgresos[$idbene][ $ingre['id'] ]['fecha'] 		= $ingre['fecha'];
							$DocEgresos[$idbene][ $ingre['id'] ]['cuenta'] 		= $ingre['cuenta'];
							$DocEgresos[$idbene][ $ingre['id'] ]['banco'] 		= $ingre['banco'];
							$DocEgresos[$idbene][ $ingre['id'] ]['numdoc'] 		= $ingre['numdoc'];
						}
					}
					$cheques = $this->CalendarioModel->chequesAuxiliar($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $row['idbancaria'],$_REQUEST['beneficiario'],$idbeneficiario,$_REQUEST['cobrados']);
					if($cheques){
						while($ingre = $cheques->fetch_assoc()){
							//$status = $this->status($ingre['status']);
							$DocCheques[$idbene][ $ingre['id'] ]['concepto']	= $ingre['concepto'];
							$DocCheques[$idbene][ $ingre['id'] ]['importe'] 	= $ingre['importe'];
							$DocCheques[$idbene][ $ingre['id'] ]['fecha'] 	= $ingre['fecha'];
							$DocCheques[$idbene][ $ingre['id'] ]['cuenta'] 	= $ingre['cuenta'];
							$DocCheques[$idbene][ $ingre['id'] ]['banco'] 	= $ingre['banco'];
							$DocCheques[$idbene][ $ingre['id'] ]['numdoc'] 	= $ingre['numdoc'];
							//$DocCheques[$idbene][ $ingre['id'] ]['cobrado'] 	= "Cobrado";
							//$DocCheques[$idbene][ $ingre['id'] ]['status'] 	= $status;
							
						}
					}
					if($_REQUEST['proyectados']==1){
						$ingresosProyectados = $this->CalendarioModel->noDepositadosAuxiliar($_REQUEST['fechainicio'],$_REQUEST['fechafin'], $row['idbancaria'],$_REQUEST['beneficiario'],$idbeneficiario);
						if($ingresosProyectados){
							while($ingre = $ingresosProyectados->fetch_assoc()){
								$DocIngresosNoDep[$idbene][ $ingre['id'] ]['concepto']	= $ingre['concepto'];
								$DocIngresosNoDep[$idbene][ $ingre['id'] ]['importe'] 	= $ingre['importe'];
								$DocIngresosNoDep[$idbene][ $ingre['id'] ]['fecha'] 		= $ingre['fecha'];
								$DocIngresosNoDep[$idbene][ $ingre['id'] ]['cuenta'] 	= $ingre['cuenta'];
								$DocIngresosNoDep[$idbene][ $ingre['id'] ]['banco'] 		= $ingre['banco'];
								$DocIngresosNoDep[$idbene][ $ingre['id'] ]['numdoc'] 	= $ingre['numdoc'];
							}
						}
					}
					
				}
			}
		}
		require("views/flujo/auxiliarbeneficiario.php");
	
	}


	
}



?>