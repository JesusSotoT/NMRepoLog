<?php
    require('common.php');

require("models/declaracionr54.php");

class Declaracionr54 extends Common
{
	public $Declaracionr54Model;

	function __construct()
	{
		

		$this->Declaracionr54Model = new Declaracionr54Model();
		$this->Declaracionr54Model->connect();
	}

	function __destruct()
	{
	
		$this->Declaracionr54Model->close();
	}
	function viewdeclaracion(){
		$ejercicio= $this->Declaracionr54Model->ejercicio();
		require('views/fiscal/declaraciones/declaracionr54.php');	
	}
	function ejecutareporte(){
		$detalle = @$_REQUEST['detalle'];
		$toexcel =$_REQUEST['excel'];
		$ejercicio = $_REQUEST['ejercicio'];
		$inicio = $_REQUEST['delperiodo'];
		// $maquilaselect = $_REQUEST['maquilaselect'];
		// $maquila = $_REQUEST['maquilaselect'];
		$inversiones = $_REQUEST['inversiones'];
		$inventarios = $_REQUEST['inventarios'];
		$inmediataperdida = $_REQUEST['inmediataperdida'];
		$perdidas = $_REQUEST['perdidas'];
		$enajenacion = $_REQUEST['enajenacion'];
		$ISRautorizadas = $_REQUEST['ISRautorizadas'];
		$ISRentregados = $_REQUEST['ISRentregados'];
		$ISRretenido = $_REQUEST['ISRretenido'];
		$acreditamientomaquiladoras = $_REQUEST['maquiladoras'];
		$proviIETU = $_REQUEST['proviIETU'];
		$cargo = $_REQUEST['cargo'];
		$favor = $_REQUEST['favor'];
		
		$mesesanteriores = $this->Declaracionr54Model->mesesanteriores($ejercicio,$inicio,1);
		$mesactual = $this->Declaracionr54Model->mesesanteriores($ejercicio,$inicio,0);
		$totaldeingresospercibidos = $mesesanteriores + $mesactual;
		$exentos = $this->Declaracionr54Model->impexentos($ejercicio,$inicio);
		$deduccionesautorizadasanteriores = $this->Declaracionr54Model->deduccionesautorizadas($ejercicio,$inicio,1);
		$deduccionesautorizadaactual = $this->Declaracionr54Model->deduccionesautorizadas($ejercicio,$inicio,0);
		$totaldeduccionesautorizadas = $deduccionesautorizadasanteriores + $deduccionesautorizadaactual;
		//Base Gravable del Pago Provisional
		$operacionbgravable  = $totaldeingresospercibidos - $exentos - $totaldeduccionesautorizadas;
		if($operacionbgravable < 0){
			$basegravabledelpagoprovisional = 0;
		}else{
			$basegravabledelpagoprovisional = $operacionbgravable;
		}
		//FIN Base Gravable del Pago Provisional
		// 2008-16.5%
		// 2009-17%
		// 2010-2013— 17.5%	
		$suma = $this->Declaracionr54Model->ejercicioactual();
		if($suma->EjercicioActual == 2008){
			$impuesto = 0.165;
		}else if($suma->EjercicioActual == 2009){
			$impuesto = 0.17;
		}else if($suma->EjercicioActual >= 2010 && $suma->EjercicioActual <= 2013){
			$impuesto = 0.175;
		}
		
		$impuestocausadodelperiodo = $basegravabledelpagoprovisional * $impuesto;
		$creditodeducmayoringresos = $_REQUEST['deduccioningre'];
		$acreditasueldosid23 = $this->Declaracionr54Model->acreditamientos($ejercicio,$inicio,23);
		$acreditamientoaportaciones = $this->Declaracionr54Model->acreditamientos($ejercicio,$inicio,24);
		$acreditasueldosid23 = $acreditasueldosid23 * $impuesto;
		 $acreditamientoaportaciones = $acreditamientoaportaciones * $impuesto;
		$operacion = $impuestocausadodelperiodo - ($creditodeducmayoringresos + $acreditasueldosid23 + $acreditamientoaportaciones + $inversiones) - ($inventarios + $inmediataperdida + $perdidas + $enajenacion + $ISRautorizadas + $ISRentregados + $ISRretenido);
		if( $operacion < 0){
			$impuestocargo1diferencia = 0;
		}else{
			$impuestocargo1diferencia = $operacion;
		}
		
		$opreracionimpcargo = $impuestocargo1diferencia - $acreditamientomaquiladoras - $proviIETU + $cargo - $favor;
		if( $opreracionimpcargo < 0 ){
			$impuestocargo = 0;
		}else{
			$impuestocargo = $opreracionimpcargo;
		}
		
		$organizacion = $this->Declaracionr54Model->organizacion();
		$meses = array('1' => 'enero','2' => 'febrero','3' => 'marzo','4' => 'abril','5' => 'mayo','6' => 'junio','7' => 'julio','8' => 'agosto','9' => 'septiembre','10' => 'octubre','11' => 'noviembre','12' => 'diciembre');
		
		
		
		if(!isset($detalle)){
			require('views/fiscal/declaraciones/reporter54.php');
		}else{
			$estimuloactual = $_REQUEST['estimuloactual'];
			$estimuloanterior = $_REQUEST['estimuloanterior'];
			
			$exagropecuriariosanteriores = $this->Declaracionr54Model->ingresosietu($ejercicio,$inicio, 3,2);
			$exagropecuriariosactual = $this->Declaracionr54Model->ingresosietu($ejercicio,$inicio, 3,1);
			
			$incisosanteriores = $this->Declaracionr54Model->ingresosietu($ejercicio,$inicio, 4,2);
			$incisosactual = $this->Declaracionr54Model->ingresosietu($ejercicio,$inicio, 4,1);
			
			$jubilacionanteriores = $this->Declaracionr54Model->ingresosietu($ejercicio,$inicio, 5,2);
			$jubilacionactual = $this->Declaracionr54Model->ingresosietu($ejercicio,$inicio, 5,1);
			
			$cajahorroanteriores = $this->Declaracionr54Model->ingresosietu($ejercicio,$inicio, 6,2);
			$cajahorroactual = $this->Declaracionr54Model->ingresosietu($ejercicio,$inicio, 6,1);
			
			$otroexentosanteriores = $this->Declaracionr54Model->ingresosietu($ejercicio,$inicio, 7,2);
			$otroexentosactual = $this->Declaracionr54Model->ingresosietu($ejercicio,$inicio, 7,1);
			
			$sumaanteriores = $mesesanteriores - ($exagropecuriariosanteriores + $incisosanteriores + $jubilacionanteriores + $cajahorroanteriores + $otroexentosanteriores);
			$sumaactual = $mesactual - ($exagropecuriariosactual + $incisosactual + $jubilacionactual + $cajahorroactual + $otroexentosactual); 
			if ($sumaanteriores<0){
				$sumaanteriores=0;
			}
			if ($sumaactual<0){
				$sumaactual=0;
			}
			
			// D E D U C C I O N E S //
			$bienesanteriores = $this->Declaracionr54Model->deduccionesautorizadasdetalle($ejercicio,$inicio,8, 2);
			$bienesactual = $this->Declaracionr54Model->deduccionesautorizadasdetalle($ejercicio,$inicio,8, 1);
			
			$independienteanterior = $this->Declaracionr54Model->deduccionesautorizadasdetalle($ejercicio,$inicio,9, 2);
			$independienteactual = $this->Declaracionr54Model->deduccionesautorizadasdetalle($ejercicio,$inicio,9, 1);
			
			$temporalanterior = $this->Declaracionr54Model->deduccionesautorizadasdetalle($ejercicio,$inicio,10, 2);
			$temporalactual = $this->Declaracionr54Model->deduccionesautorizadasdetalle($ejercicio,$inicio,10, 1);
			
			$contribucioncargoanterior = $this->Declaracionr54Model->deduccionesautorizadasdetalle($ejercicio,$inicio,11, 2);
			$contribucionactual = $this->Declaracionr54Model->deduccionesautorizadasdetalle($ejercicio,$inicio,11, 1);
			
			$aprovechamientosanterior = $this->Declaracionr54Model->deduccionesautorizadasdetalle($ejercicio,$inicio,12, 2);
			$aprovechamientosactual = $this->Declaracionr54Model->deduccionesautorizadasdetalle($ejercicio,$inicio,12, 1);
			
			$Indemnizacionesanterior = $this->Declaracionr54Model->deduccionesautorizadasdetalle($ejercicio,$inicio,13, 2);
			$Indemnizacionesactual = $this->Declaracionr54Model->deduccionesautorizadasdetalle($ejercicio,$inicio,13, 1);
			
			$premiosanterior = $this->Declaracionr54Model->deduccionesautorizadasdetalle($ejercicio,$inicio,14, 2);
			$premiosactual = $this->Declaracionr54Model->deduccionesautorizadasdetalle($ejercicio,$inicio,14, 1);
			
			$donativosanterior = $this->Declaracionr54Model->deduccionesautorizadasdetalle($ejercicio,$inicio,15, 2);
			$donativosactual = $this->Declaracionr54Model->deduccionesautorizadasdetalle($ejercicio,$inicio,15, 1);
			
			$Fortuitoanterior = $this->Declaracionr54Model->deduccionesautorizadasdetalle($ejercicio,$inicio,16, 2);
			$Fortuitoactual = $this->Declaracionr54Model->deduccionesautorizadasdetalle($ejercicio,$inicio,16, 1);
			
			$inversionanterior = $this->Declaracionr54Model->deduccionesautorizadasdetalle($ejercicio,$inicio,17, 2);
			$inversionactual = $this->Declaracionr54Model->deduccionesautorizadasdetalle($ejercicio,$inicio,17, 1);
			
			$reservasanterior = $this->Declaracionr54Model->deduccionesautorizadasdetalle($ejercicio,$inicio,18, 2);
			$reservasactual = $this->Declaracionr54Model->deduccionesautorizadasdetalle($ejercicio,$inicio,18, 1);
			
			$Incobrablesanterior = $this->Declaracionr54Model->deduccionesautorizadasdetalle($ejercicio,$inicio,19, 2);
			$Incobrablesactual = $this->Declaracionr54Model->deduccionesautorizadasdetalle($ejercicio,$inicio,19, 1);
			
			$adicionalanterior = $this->Declaracionr54Model->deduccionesautorizadasdetalle($ejercicio,$inicio,20,2);
			$adicionalactual = $this->Declaracionr54Model->deduccionesautorizadasdetalle($ejercicio,$inicio,20,1);
			
			$autorizadasanterior = $this->Declaracionr54Model->deduccionesautorizadasdetalle($ejercicio,$inicio,21,2);
			$autorizadasactual = $this->Declaracionr54Model->deduccionesautorizadasdetalle($ejercicio,$inicio,21,1);
			//total deducciones
			$totalanterior = $bienesanteriores + $independienteanterior + $temporalanterior + $contribucioncargoanterior;
			$totalanterior += $aprovechamientosanterior + $Indemnizacionesanterior + $premiosanterior + $Fortuitoanterior;
			$totalanterior += $inversionanterior + $reservasanterior + $Incobrablesanterior + $adicionalanterior + $autorizadasanterior;
			
			$totalactual = $bienesactual + $independienteactual + $temporalactual + $contribucionactual + $aprovechamientosactual;
			$totalactual += $Indemnizacionesactual + $premiosactual + $Fortuitoactual + $inversionactual + $reservasactual;
			$totalactual += $Incobrablesactual + $adicionalactual + $autorizadasactual;
			//base gravable
			$basegravabledetalleanterior = $sumaanteriores - $totalanterior;
			if($basegravabledetalleanterior < 0){
				$basegravabledetalleanterior = 0;
			}
			$basegravabledetalleactual = $sumaactual - $totalactual;
			if($basegravabledetalleactual < 0){
				$basegravabledetalleactual = 0;
			}
			require('views/fiscal/declaraciones/reporte54detallado.php');
		}
	}
}
?>