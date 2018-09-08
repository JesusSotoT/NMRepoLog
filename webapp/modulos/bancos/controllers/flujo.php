<?php
  require('controllers/cheques.php');

//Carga el modelo para este controlador
require("models/flujo.php");

class Flujo extends Cheques
{
	public $FlujoModel;
	public $ChequesModel;
	function __construct()
	{
		
		$this->FlujoModel = new FlujoModel();
		$this->ChequesModel = $this->FlujoModel;
		$this->FlujoModel->connect();
	}

	function __destruct()
	{
		
		$this->FlujoModel->close();
	}
    // F L U J O  D E   E F E C T I V O
	function verflujo(){
		$moneda				= $this->ChequesModel->moneda();
		$cuentasbancarias	= $this->ChequesModel->cuentasbancariaslista();
		
		include('views/flujo/filtroflujo.php');
	}
	function verauxiliar(){
		$moneda				= $this->ChequesModel->moneda();
		$cuentasbancarias	= $this->ChequesModel->cuentasbancariaslista();
		$provee				= $this->ChequesModel->proveedor();
		$cliente				= $this->ChequesModel->cliente();
		$empleado			= $this->ChequesModel->empleados();
		include('views/flujo/filtroauxiliar.php');
	}
	function cuentasPorMoneda(){
		$lista = $this->FlujoModel->cuentasBancariasPorMoneda($_REQUEST['idmoneda']);
		if($lista->num_rows>0){
			echo "<option value='0' selected=''>--Todas--</option>";
			while($row = $lista->fetch_assoc()){
				echo "<option value=".$row['idbancaria']."> " .$row['nombre']." (".$row['cuenta'].") </option>";
			}
		}else{
			echo "<option>-No tiene cuentas-</option>";
		}
	}
	function reporteFlujo(){
		$logo = $this->ChequesModel->logo();
		$infocuenta 		= array();
		$detalle=0;
		if(	$_REQUEST['cuenta']	==	0 ){//todas las cuentas
			$ingresosArray 	= array();
			$egresosArray 	= array();
			$saldoFinaltotal = $saldoInicialtotal = 0;
			$cuentas = $this->FlujoModel->cuentasBancariasPorMoneda($_REQUEST['moneda'],0);
			$cont=0;
			while($row = $cuentas->fetch_assoc()){
				$saldoFinal = 0;
				$saldoInicial	= $this->FlujoModel->saldoInicialFujo($row['idbancaria'],$_REQUEST['fechainicio'],$_REQUEST['cobrados']);
				$infocuenta[$cont] ['numcuenta']		= $row['cuenta'];
				$infocuenta[$cont] ['saldoinicial']	= $saldoInicial;
				$infocuenta[$cont] ['nombre']		= $row['nombre'];
				$infocuenta[$cont] ['idbancaria']	= $row['idbancaria'];
				
				$ingresos = $this->FlujoModel->ingresosFlujoGlobal($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $row['idbancaria'] ,0);
				
				while($ingre = $ingresos->fetch_assoc()){
					$ingresosArray[ $ingre['id'] ] ['clasificador']					= $ingre['nombreclasificador'];
					$ingresosArray[ $ingre['id'] ] [$row['idbancaria']] ['importe'] 	= $ingre['importe'];
					$ingresosArray[ $ingre['id'] ] [$row['idbancaria']] ['idbancaria']  				 	= $row['idbancaria'];
					$saldoFinal 		+= $ingre['importe'];
					$saldoFinaltotal	+= $ingre['importe'];
				}
				
				if($_REQUEST['proyectados']==1){
					$ingresosproyectados = $this->FlujoModel->ingresosFlujoGlobal($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $row['idbancaria'] ,1);
				
					while($ingre = $ingresosproyectados->fetch_assoc()){
						$ingresosArray[ $ingre['id'] ] ['clasificador']					= $ingre['nombreclasificador'];
						$ingresosArray[ $ingre['id'] ] [$row['idbancaria']] ['importe'] += $ingre['importe'];
						$ingresosArray[ $ingre['id'] ] [$row['idbancaria']] ['idbancaria']  = $row['idbancaria'];
						$saldoFinal 		+= $ingre['importe'];
						$saldoFinaltotal	+= $ingre['importe'];
					}
				}
// 				
				$depositos 		= $this->FlujoModel->depositosFlujoGlobal($_REQUEST['fechainicio'], $_REQUEST['fechafin'],$row['idbancaria'] );
				
				while($ingre = $depositos->fetch_assoc()){ 
					$ingresosArray[ $ingre['id'] ] ['clasificador']						= $ingre['nombreclasificador'];
					$ingresosArray[ $ingre['id'] ] [$row['idbancaria']] ['importe'] 		+= $ingre['importe'];
					$ingresosArray[ $ingre['id'] ] [$row['idbancaria']] ['idbancaria']   = $row['idbancaria'];
					$saldoFinal			+= $ingre['importe'];
					$saldoFinaltotal	 	+= $ingre['importe'];
				}
				$depositostras 		= $this->FlujoModel->depositosFlujoTraspasoGlobal($_REQUEST['fechainicio'], $_REQUEST['fechafin'],$row['idbancaria'] );
				
				while($ingre = $depositostras->fetch_assoc()){ 
					$ingresosArray[ $ingre['id'] ] ['clasificador']						= $ingre['nombreclasificador'];
					$ingresosArray[ $ingre['id'] ] [$row['idbancaria']] ['importe'] 		+= $ingre['importe'];
					$ingresosArray[ $ingre['id'] ] [$row['idbancaria']] ['idbancaria']   = $row['idbancaria'];
					$saldoFinal			+= $ingre['importe'];
					$saldoFinaltotal	 	+= $ingre['importe'];
				}
	
	
		/* 		FIN DOCUMENTOS INGRESOS 			*/
				$saldoFinal 			+= $saldoInicial;
				$saldoFinaltotal		+= $saldoInicial;
				/* egresos 
				 * Documentos de egresos
				 * Cheques*/
				$egresos 		= $this->FlujoModel->EgresosFlujoGlobal($_REQUEST['fechainicio'], $_REQUEST['fechafin'],$row['idbancaria'] ,$_REQUEST['cobrados']);
				while($egre = $egresos->fetch_assoc()){
					$egresosArray[ $egre['id'] ]['clasificador']						= $egre['nombreclasificador'];
					$egresosArray[ $egre['id'] ][$row['idbancaria']]['importe']		= $egre['importe'];
					$egresosArray[ $egre['id'] ][$row['idbancaria']]['idbancaria']  	= $row['idbancaria'];
					$saldoFinal 			-= $egre['importe'];
					$saldoFinaltotal		-= $egre['importe'];
				}
				$egresos 		= $this->FlujoModel->chequesFlujoGlobal($_REQUEST['fechainicio'], $_REQUEST['fechafin'],$row['idbancaria']);
			
				while($egre = $egresos->fetch_assoc()){
					$egresosArray[ $egre['id'] ][$row['idbancaria']]['clasificador']	= $egre['nombreclasificador'];
					$egresosArray[ $egre['id'] ][$row['idbancaria']]['importe']   	+= $egre['importe'];
					$egresosArray[ $egre['id'] ][$row['idbancaria']]['idbancaria']  	= $row['idbancaria'];
					
					$saldoFinal -= $egre['importe'];
					$saldoFinaltotal		-= $egre['importe'];
				}
				if($_REQUEST['cobrados']==0){
					$egresos 		= $this->FlujoModel->ChequesNocobraFlujoGlobal($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $row['idbancaria']);
				
					while($egre = $egresos->fetch_assoc()){
						$egresosArray[ $egre['id'] ][$row['idbancaria']]['clasificador']	= $egre['nombreclasificador'];
						$egresosArray[ $egre['id'] ][$row['idbancaria']]['importe']   	+= $egre['importe'];
						$egresosArray[ $egre['id'] ][$row['idbancaria']]['idbancaria']  	= $row['idbancaria'];
						
						$saldoFinal -= $egre['importe'];
						$saldoFinaltotal		-= $egre['importe'];
					}
					/*cheques cobrados pero en otro mes 
					 * quedan como no cobrados en este mes */
					$egresos 		= $this->FlujoModel->ChequesNocobraFlujoGlobalCobradosDespues($_REQUEST['fechainicio'], $_REQUEST['fechafin'],$row['idbancaria']);
				
					while($egre = $egresos->fetch_assoc()){
						$egresosArray[ $egre['id'] ][$row['idbancaria']]['clasificador']	= $egre['nombreclasificador'];
						$egresosArray[ $egre['id'] ][$row['idbancaria']]['importe']   	+= $egre['importe'];
						$egresosArray[ $egre['id'] ][$row['idbancaria']]['idbancaria']  	= $row['idbancaria'];
						
						$saldoFinal -= $egre['importe'];
						$saldoFinaltotal		-= $egre['importe'];
					}
				}
				//$infocuenta[$cont] ['saldofinal'] = $saldoFinal;
				$cont++;
			}
		
					
			
/* 		FIN TODAS LAS cuentas 		*/		
			require("views/flujo/reporteflujotodas.php");
		}else{//una sola cuenta
			/* a detalle */
			$cuentas = $this->FlujoModel->cuentasBancariasPorMoneda($_REQUEST['moneda'],$_REQUEST['cuenta']);
			while($row = $cuentas->fetch_assoc()){
				$cuenta = $row['cuenta'];
				$banco = $row['nombre'];
				
			}
			$ingresosArray 	= array();
			$egresosArray 	= array();
			$saldoFinal = 0;
			$saldoInicial	= $this->FlujoModel->saldoInicialFujo($_REQUEST['cuenta'],$_REQUEST['fechainicio'],$_REQUEST['fechafin'],$_REQUEST['cobrados'],$_REQUEST['proyectados']);
			//$saldoFinal		= $this->FlujoModel->saldoFinalFlujo($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $saldoInicial, $_REQUEST['cuenta']);
			
			
	/* 	ingresos 
			 * Documentos de ingresos
			 * Depositos 			*/
			$ingresos 		= $this->FlujoModel->ingresosFlujoGlobal($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $_REQUEST['cuenta'],0);
			
			while($ingre = $ingresos->fetch_assoc()){
				$ingresosArray[ $ingre['id'] ]['clasificador']	= $ingre['nombreclasificador'];
				$ingresosArray[ $ingre['id'] ]['importe']   		= $ingre['importe'];
				$saldoFinal += $ingre['importe'];
			}
			
			
			$depositos 		= $this->FlujoModel->depositosFlujoGlobal($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $_REQUEST['cuenta']);
			
			while($ingre = $depositos->fetch_assoc()){
				$ingresosArray[ $ingre['id'] ]['clasificador']	= $ingre['nombreclasificador'];
				$ingresosArray[ $ingre['id'] ]['importe']   += $ingre['importe'];
				$saldoFinal += $ingre['importe'];
			}
			$depositos 		= $this->FlujoModel->depositosFlujoTraspasoGlobal($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $_REQUEST['cuenta']);
			
			while($ingre = $depositos->fetch_assoc()){
				$ingresosArray[ $ingre['id'] ]['clasificador']	= $ingre['nombreclasificador'];
				$ingresosArray[ $ingre['id'] ]['importe']   += $ingre['importe'];
				$saldoFinal += $ingre['importe'];
			}

			if($_REQUEST['proyectados']==1){
				$ingresosproyectados 		= $this->FlujoModel->ingresosFlujoGlobal($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $_REQUEST['cuenta'],1);
			
				while($ingre = $ingresosproyectados->fetch_assoc()){
					$ingresosArray[ $ingre['id'] ]['clasificador']	= $ingre['nombreclasificador'];
					$ingresosArray[ $ingre['id'] ]['importe']   	+= $ingre['importe'];
					$saldoFinal += $ingre['importe'];
				}

			}
	/* 		FIN DOCUMENTOS INGRESOS 			*/
			$saldoFinal += $saldoInicial;
			
			/* egresos 
			 * Documentos de egresos
			 * Cheques*/
			$egresos 		= $this->FlujoModel->EgresosFlujoGlobal($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $_REQUEST['cuenta']);
			
			while($egre = $egresos->fetch_assoc()){
				$egresosArray[ $egre['id'] ]['clasificador']	= $egre['nombreclasificador'];
				$egresosArray[ $egre['id'] ]['importe']   	= $egre['importe'];
				$saldoFinal -= $egre['importe'];
			}
			$egresos 		= $this->FlujoModel->chequesFlujoGlobal($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $_REQUEST['cuenta']);
			
			while($egre = $egresos->fetch_assoc()){
				$egresosArray[ $egre['id'] ]['clasificador']	= $egre['nombreclasificador'];
				$egresosArray[ $egre['id'] ]['importe']   	+= $egre['importe'];
				$saldoFinal -= $egre['importe'];
			}
			if($_REQUEST['cobrados']==0){
				$egresos 		= $this->FlujoModel->ChequesNocobraFlujoGlobal($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $_REQUEST['cuenta']);
			
				while($egre = $egresos->fetch_assoc()){
					$egresosArray[ $egre['id'] ]['clasificador']	= $egre['nombreclasificador'];
					$egresosArray[ $egre['id'] ]['importe']   	+= $egre['importe'];
					$saldoFinal -= $egre['importe'];
				}
				/*cheques cobrados pero en otro mes 
				 * quedan como no cobrados en este mes */
				$egresos 		= $this->FlujoModel->ChequesNocobraFlujoGlobalCobradosDespues($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $_REQUEST['cuenta']);
			
				while($egre = $egresos->fetch_assoc()){
					$egresosArray[ $egre['id'] ]['clasificador']	= $egre['nombreclasificador'];
					$egresosArray[ $egre['id'] ]['importe']   	+= $egre['importe'];
					$saldoFinal -= $egre['importe'];
				}
			}
			require("views/flujo/reporteflujoefectivo.php");
		}

		
	}
	function reporteDetalle(){
		
			$ingresos 		= $this->FlujoModel->ingresosFlujo($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $_REQUEST['cuenta']);
			
			while($ingre = $ingresos->fetch_assoc()){
				$ingresosArray[ $ingre['idclasificador'] ][ $ingre['id'] ]['concepto']		= $ingre['concepto'];
				$ingresosArray[ $ingre['idclasificador'] ][ $ingre['id'] ]['importe']		= $ingre['importe'];
				$ingresosArray[ $ingre['idclasificador'] ][ $ingre['id'] ]['fecha']			= $ingre['fecha'];
				$ingresosArray[ $ingre['idclasificador'] ][ $ingre['id'] ]['clasificador']	= $ingre['nombreclasificador'];
				$ingresosArray[ $ingre['idclasificador'] ][ $ingre['id'] ]['documento']		= $ingre['idDocumento'];
			}
			
			$egresos		= $this->FlujoModel->egresosFlujo($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $_REQUEST['cuenta']);
			
			while($egre = $egresos->fetch_assoc()){
				$egresosArray[ $egre['idclasificador'] ][ $egre['id'] ]['concepto']		= $egre['concepto'];
				$egresosArray[ $egre['idclasificador'] ][ $egre['id'] ]['importe']		= $egre['importe'];
				$egresosArray[ $egre['idclasificador'] ][ $egre['id'] ]['fecha']		= $egre['fecha'];
				$egresosArray[ $egre['idclasificador'] ][ $egre['id'] ]['clasificador']	= $egre['nombreclasificador'];
				$egresosArray[ $egre['idclasificador'] ][ $egre['id'] ]['documento']	= $egre['idDocumento'];
				
			}
	}
/* FIN REPORTE FLUJO EFECTIVO */


/*		 INICIO POSICION BANCARIA DIARIA		 */
function verposicion(){
	$moneda				= $this->ChequesModel->moneda();
	$cuentasbancarias	= $this->ChequesModel->cuentasbancariaslista();
	
	include('views/flujo/filtroposicion.php');
}
function reportePosicion(){
	$logo = $this->ChequesModel->logo();
		$infocuenta 		= array();
		if(	$_REQUEST['cuenta']	==	0 ){//todas las cuentas
		
			$cuentas = $this->FlujoModel->cuentasBancariasPorMoneda($_REQUEST['moneda'],0);
		}else{
			$cuentas = $this->FlujoModel->cuentasBancariasPorMoneda($_REQUEST['moneda'],$_REQUEST['cuenta']);
		}
			while($row = $cuentas->fetch_assoc()){
			
				$saldoFinal = 0;
				
				$saldoInicial	= $this->FlujoModel->saldoInicialFujo($row['idbancaria'],$_REQUEST['fechainicio'],$_REQUEST['fechafin'],1,0);
				
				$infocuenta[ $row['idbancaria'] ] ['numcuenta']		= $row['cuenta'];
				$infocuenta[ $row['idbancaria'] ] ['saldoinicial']	= $saldoInicial;
				$infocuenta[ $row['idbancaria'] ] ['nombre']			= $row['nombre'];
				$banco = $row['nombre']; $cuenta=$row['cuenta'];
				
				$ingresos 	= $this->FlujoModel->ingresosPosicion($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $row['idbancaria'],0);
				$infocuenta[ $row['idbancaria'] ] ['ingresos']	= $ingresos['importe'];
				$saldoFinal 								+= $ingresos['importe'];
				
				$depositos 	= $this->FlujoModel->depositosPosicion($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $row['idbancaria'],0);
				$infocuenta[ $row['idbancaria'] ] ['ingresos']	+= $depositos['importe'];
				$saldoFinal 								+= $depositos['importe'];
			
				$depositostransito 	= $this->FlujoModel->depositosPosiciontransito($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $row['idbancaria'],0);
				$infocuenta[ $row['idbancaria'] ] ['ingresostransito']	= $depositostransito['importe'];
				$saldoFinal 								+= $depositostransito['importe'];
	
			
				$ingresosproyectados = $this->FlujoModel->ingresosPosicion($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $row['idbancaria'],1);
				$infocuenta[ $row['idbancaria'] ] ['ingresosproyectados']	= $ingresosproyectados['importe'];;
				//$saldoFinal											+= $ingresosproyectados['importe'];
	
				$saldoFinal += $saldoInicial;
			
				$egresos = $this->FlujoModel->egresosPosicion($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $row['idbancaria']);
				$infocuenta[ $row['idbancaria'] ] ['egresos']	= $egresos['importe'];
				$saldoFinal 										-= $egresos['importe'];
				
				$egresos = $this->FlujoModel->chequesPosicion($_REQUEST['fechainicio'], $_REQUEST['fechafin'], $row['idbancaria'],1);
				$infocuenta[ $row['idbancaria'] ] ['egresos']	+= $egresos['importe'];
				$saldoFinal 										-= $egresos['importe'];
				
	/*		 egresos en transito 		*/
				$egresostransito = $this->FlujoModel->chequesPosicion($_REQUEST['fechainicio'], $_REQUEST['fechafin'],$row['idbancaria'],0);
				$infocuenta[ $row['idbancaria'] ] ['egresostransitoactual']	= $egresostransito['importe'];
				$saldoFinal 												-= $egresostransito['importe'];
				/*cheques con estatus cobrado pero pagados despues quedan como en circulacion*/
				$egresostransitocobradosdespues = $this->FlujoModel->chequesPosicionCobradosDespues($_REQUEST['fechainicio'], $_REQUEST['fechafin'],$row['idbancaria']);
				$infocuenta[ $row['idbancaria'] ] ['egresostransitoactual']	+= $egresostransitocobradosdespues['importe'];
				$saldoFinal 												-= $egresostransitocobradosdespues['importe'];
				/* cheques con estatus cobrado pero cobrado en el mes, tiene registro previo
				 * por eso se restaran del saldo contable 
				 * porque en la fecha de registro ya s eiso la poliza*/
				// $egresostransitocobradosantes = $this->FlujoModel->chequesPosicionCobradosAntes($_REQUEST['fechainicio'], $_REQUEST['fechafin'],$row['idbancaria']);
				// $infocuenta[ $row['idbancaria'] ] ['egresostransitodespues']	= $egresostransitocobradosantes['importe'];
				// $saldoFinal 												-= $egresostransitocobradosantes['importe'];
// 				
				$infocuenta[ $row['idbancaria'] ] ['saldofinal']			= $saldoFinal;
			}
			
		require("views/flujo/reporteposicion.php");

		
	}

/* 		FIN POSICION BANCARIA DIARIA 			*/

}
?>