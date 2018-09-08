<?php
require('controllers/nominalibre.php');
require("models/prenomina.php");

class Prenomina extends Nominalibre
{
	public $PrenominaModel;
	public $CatalogosModel;
	public $NominalibreModel;
	
	function __construct()
	{
		
		$this->PrenominaModel = new PrenominaModel();
		$this->CatalogosModel = $this->PrenominaModel;
		$this->NominalibreModel = $this->PrenominaModel;
		$this->PrenominaModel->connect();
		
	}

	function __destruct()
	{
		
		$this->PrenominaModel->close();
	}
	
	/* P R E N O M I N A      I N T E R F A Z */

	function vistaPrenomina(){
		$nominasPeriodo 		= $this->CatalogosModel->periodoactualPrenomina();
		$conceptosConfig        = $this->CatalogosModel->conceptosPrenominaExiste();
		$numconceptosConf  = $conceptosConfig->num_rows;
		require("views/prenomina/prenomina.php");
	}
	
	function empleadosNomina(){
		$empleados = $this->CatalogosModel->empleadosDperiodo($_REQUEST['fecha']);
		$numConf = $_REQUEST['numConf'] + 3;
		if($empleados!=0){
			while($e = $empleados->fetch_object()){
				echo "	<tr style='' onMouseDown='adicional(".$e->idEmpleado.");' id='".$e->idEmpleado."'>
				<td style='height:40px;width:110px;border:solid  1px;' align='center'>".$e->codigo."</td>
				<td style='height:40px;width:110px;border:solid  1px;' align='center'>".strtoupper($e->nombreEmpleado . " ". $e->apellidoPaterno." ".$e->apellidoMaterno)."</td>";
			}
			
		}else{
			echo "<tr><td colspan='".$numConf."'>No tiene ningun empleado dado de alta en este periodo</td></tr>";
		}	
	}
	function CalculoNomina(){
		$arrayEmpleados = array();
		$SMvigente = $this->PrenominaModel->SMvigente();
		$UMA = $this->PrenominaModel->UMAvigente();
		
		if($_REQUEST['todos']>0){
			/* la ultima fecha de la nomina sera el tope
			 * para traer los empleados registrados hasta el cierre */
			$empleados = $this->CatalogosModel->empleadosDperiodo($_REQUEST['fechafin']);
			/* datos de empleado nomi_empleados */
		}else{
			$empleados = $this->PrenominaModel->empleadosDperiodoSincalcular($_REQUEST['fechafin'],$_REQUEST['numnomina']);
			
		}
		if($empleados!=0){
			while($e = $empleados->fetch_object()){
				
				$arrayEmpleados[ $e->idEmpleado ]['salariobase'] = $e->salario;
				$arrayEmpleados[ $e->idEmpleado ]['SDI'] = $e->sbcfija;
				$arrayEmpleados[ $e->idEmpleado ]['codigo'] = $e->codigo;
				$arrayEmpleados[ $e->idEmpleado ]['fechaActiva'] = $e->fechaActiva;
				$arrayEmpleados[ $e->idEmpleado ]['alimentos'] = $e->alimento;
				$arrayEmpleados[ $e->idEmpleado ]['nombre'] = $e->nombreEmpleado." ".$e->apellidoPaterno." ".$e->apellidoMaterno;
			}
		}
		/* fin de datos empleado base */
		
		$tiposperiododatos = $this->CatalogosModel->editarTipoperidoo($_REQUEST['idtipop']);
		if($tiposperiododatos->idperiodicidad == 2){
			//$complemento = 2;
			$proporciondias = 7;
		}elseif($tiposperiododatos->idperiodicidad == 3){
			//$complemento = 4;
			$proporciondias = 14;
		}
		$jornada = 47.30; //tambien en catalogo d politicas
		$checkEmpleado = $arrayISPT = $bonosEmpleados = $percepcionesEmpleado = $deduccionesEmpleado = $otrasPercepciones = $otrasDeducciones = $infonavitarray =  array();
		
		/* INCIDENCIAS MANUALES POR EMPLEADOS 
		 * idconsiderado: ID tabla nomi_considera_incidencia 1-ausencia, 2-incapacidad, 3-vacaciones, 4-ninguno
		 * derecho sueldo: -1 -SI, 0-NO
		 * idtipoincidencia: ID tabla nomi_tipoincidencia en esta estan todas las incidencias
		 * idclasificadorincidencia:ID tabla nomi_clasificacion_incidencias 1-destajos, 2-dias, 3-horas
		 */ 
		 
		/*VACACIONES 
		 * 29 agosto 2017
		 * Segun lo mencionado por javier(contador kerlab) 
		 * se pagara la prima correspodiente a los dias tomados
		 * Los dias de vacaciones se agregaran como dias checados para la proporcion pero debera el importe
		 * verse reflejado en su concepto*/
		
		
		$arrayIncidenciasEmpleado = array();
		foreach ( $arrayEmpleados as $idEmpleados => $datos ){
			$checkEmpleado[ $idEmpleados ]['diasvacaciones']=0;
			$checkEmpleado[ $idEmpleados ]['diasvacacionesproporcion']=0;
			$checkEmpleado[ $idEmpleados ]['diasfestivos']=0; 
			$incidencia = $this->PrenominaModel->incidenciasDeEmpleado($idEmpleados, $_REQUEST['numnomina']);
			if( $incidencia != 0 ){
				while ( $i = $incidencia->fetch_object() ){
					$arrayIncidenciasEmpleado[ $idEmpleados ]['derechosueldo'] 		= $i->derechosueldo;
					$arrayIncidenciasEmpleado[ $idEmpleados ]['idconsiderado'] 		= $i->idconsiderado;
					$arrayIncidenciasEmpleado[ $idEmpleados ]['idtipoincidencia'] 	= $i->idtipoincidencia;
					$arrayIncidenciasEmpleado[ $idEmpleados ]['idclasificadorincidencia'] = $i->idclasificadorincidencia;
					$arrayIncidenciasEmpleado[ $idEmpleados ]['sobrerecibo'] = $i->sobrerecibo;//1 incapacidad, 2 vacaciones
					if( $i->sobrerecibo == 2){
						$checkEmpleado[ $idEmpleados ]['diasvacaciones'] +=1;
					}
					if( $i->idconsiderado == 5){
						//si es festivo a los dias checados
						$checkEmpleado[ $idEmpleados ]['diasfestivos'] +=1;
					}
					
				}
			}
			
		
		
		/* fin incidencias manuales */
		
		
		
		/* CHECK POR EMPLEADO todo esto iria en catalogo de politicas pero por las prisas solo dejo la nota ¬¬
		 * de acuerdo con KERLAB los empleados checan
		 * entrada a trabajar, inicio comida, fin comida, salida de trabajar
		 * tienen solo una hora de comida
		 * su periodo semanal corresponde con 1 total de 47:30hrs
		 * L-J 10 hrs diarias excluyendo la hora de comida
		 * V 7:30 hrs salida a las 3:30 sin hora de comida corrido
		 * 10min de tolerancia despues de este no se pagara bono de puntualidad
		 * la JORNADA sera acorde a kerlab 47:30
		 */
		
		
			$check = $this->PrenominaModel->checkDeEmpleado($idEmpleados, $_REQUEST['numnomina']);
			$checkEmpleado[ $idEmpleados ]['totaldiacheck'] =0 ;
			$bonosEmpleados [ $idEmpleados ] [ "bonopuntualidad" ] = 0;
			$bonosEmpleados [ $idEmpleados ] [ "bonoasistencia" ] = 0;
			if( $check != 0 ){
				while ( $i = $check->fetch_object() ){
					
					$checkEmpleado[ $idEmpleados ][$i->fecha]['horaentrada']		= $i->horaentrada;
					$checkEmpleado[ $idEmpleados ][$i->fecha]['iniciocomida']	= $i->iniciocomida;
					$checkEmpleado[ $idEmpleados ][$i->fecha]['fincomida']		= $i->fincomida;
					$checkEmpleado[ $idEmpleados ][$i->fecha]['horasalida']		= $i->horasalida;
					$checkEmpleado[ $idEmpleados ][$i->fecha]['fecha']			= $i->fecha;
					$checkEmpleado[ $idEmpleados ][$i->fecha]['dia']			= $i->dia;
					$horasdia = $this->calcular_tiempo_trasnc($i->horasalida,$i->horaentrada);
					$checkEmpleado[ $idEmpleados ][$i->fecha]['horastrabajadas']	= $horasdia;
					//el viernes la jornada es de 7:30 para kerlab esto tambien iria en catalogo d politicas
					if($i->dia == 'Vie'){
						$checkEmpleado[ $idEmpleados ][$i->fecha]['minutosmenos']	= $this->calcular_tiempo_trasnc('07:30:00', $horasdia);
					// y los demas dias sn 10 hrs 
					}else{
						$checkEmpleado[ $idEmpleados ][$i->fecha]['minutosmenos']	= $this->calcular_tiempo_trasnc('10:00:00', $horasdia); 
					}
					$checkEmpleado[ $idEmpleados ]['totaldiacheck']	+=1;
					$checkEmpleado[ $idEmpleados ]['diasIMSS']	+=1;
					/* En el horario se puso un rango kerlab entra a las 8
					 * con 10 min de tolerancia, en caso de adaptarlo para otra empresa
					 * creo q estaria bien agregar una catalogo de politicas
					 * y en este caso las 7:00 seria un campo de entrada inico rango
					 * y 8:10 el limite para alcanzar bonito de puntualidad
					 */
					if( strtotime($i->horaentrada) > strtotime("07:00:00") && strtotime($i->horaentrada) <= strtotime("8:10:00") ){
						$checkEmpleado[ $idEmpleados ]['bonopuntualidad'] += 1;
					}
					
					
					
				}
			}
			//si es festivo se agregara como si hubiera chec ado
			if($checkEmpleado[ $idEmpleados ]['diasfestivos']>0){
				$checkEmpleado[ $idEmpleados ]['totaldiacheck'] += $checkEmpleado[ $idEmpleados ]['diasfestivos'];
			}
		/*se calcula el salario diario por los dias trabajados para sacar 
		 * el monto total a pagar del empleado
		y ver en q limite queda de la tabla de isr
		 * SE MULTIPLICA LOS DIAS DEL PERIODO POR LOS DIAS Q TRABAJAN CASO KERLAB(no trabjan sab y domin)
		 *  esto va dentro de politica 
		 *PARA SACAR LA PROPORCION DE DIAS APAGAR EN CASO DE FALTAS
		 */
		
		
		/*INCAPACIDADES (se separan porq en el sobrerecibo estan los datos y dias acorde a la hoja imss)
		 * en teoría debe estar en las dos tablas de arriba y sobrerecibo pero nos guiaremos con esta
		 
		 * Incapacidad no se debe sacar proporción solo restar los dias de incapacidad (SI en un periodo existe una incapacidad afecta todo el periodo y no debe sacar porporcion)
		 * terminando y no existiendo  incapacidad ya debe calcular la proporción
		 */
		 
		 $incapacidad = $this->PrenominaModel->incapacidadesSobrerecibo($_REQUEST['fechainicio'], $_REQUEST['fechafin'],$idEmpleados);
		
		 $diasincapacidad = $incapacidad->diasautorizados;
		 
		 //*FIN INCAPACIDADES */
		 //29 AGOSTO 2017- SE COMENTO PORQ LOS CONTADORES dijeron que dias trabajados dias pagados 
		 //SI EXISTEN INCAPACIDADES NO DEBE SACAR PROPORCION Y SE PAGAN SOLO LOS DIAS QUE ASISTIO IGUAL PARA IMSS
		// if($diasincapacidad){
			// $checkEmpleado[ $idEmpleados ]['diasincapacidad'] = $diasincapacidad;
// 			
			// if($tiposperiododatos->idperiodicidad == 2){
				// $diaspago = ((7 / 5 ) * $checkEmpleado[ $idEmpleados ]['totaldiacheck']) - $diasincapacidad;
// 				
			// }elseif($tiposperiododatos->idperiodicidad == 3){
				// $diaspago = ((14 / 10 ) * $checkEmpleado[ $idEmpleados ]['totaldiacheck'])- $diasincapacidad;
// 	
			// }
		//}else{
			if($tiposperiododatos->idperiodicidad == 2){
				$diaspagoi = (7 / 5 )  ;
				
			}elseif($tiposperiododatos->idperiodicidad == 3){
				$diaspagoi = (14 / 10 );
			}
			$diaspago = $diaspagoi * ($checkEmpleado[ $idEmpleados ]['totaldiacheck'] + $checkEmpleado[ $idEmpleados ]['diasvacaciones'])  ;
			$diaspagosalario = $diaspagoi * ($checkEmpleado[ $idEmpleados ]['totaldiacheck'])  ;
			
			$checkEmpleado[ $idEmpleados ]['diasvacacionesproporcion'] = $diaspagoi * $checkEmpleado[ $idEmpleados ]['diasvacaciones'];
		//}
		
		//$bonosEmpleados [ $idEmpleados ] [ "bonopuntualidad" ] = 0;
		//$bonosEmpleados [ $idEmpleados ] [ "bonoasistencia" ] = 0;
		$checkEmpleado[ $idEmpleados ]['diaspago'] = $diaspago;
		$checkEmpleado[ $idEmpleados ]['diaspagoi'] = $diaspagoi;
		$checkEmpleado[ $idEmpleados ]['diaspagosalario'] = $diaspagosalario;
		
		//se agregan los dias de vacaciones solo para pagar el bono despues hay q quitarlos
		//(SE ACTUALIZO DE ACUERDO A LO DICHO POR JAVIER LOS BONOS SON PROPORCIONALES A LOS DIAS Q FUE SIN IMPORTAR VACACIONES ETC)
		//$checkEmpleado[ $idEmpleados ]['totaldiacheck'] += $checkEmpleado[ $idEmpleados ]['diasvacaciones'];
		
		/*parte exenta para bonos bonos
		 * Se calcula la parte exenta 10% queda exento al 100
		 * el exendente del 10% sera gravado
		 */ 
		 
		 //AGREGE ESTO PARA NO CONTAR LOS DIAS DE VACA Y AHORA LOS BONOS SON PROPORCIONALES ALOS DIAS Q FUE
		 // ahora se multiplica por $checkEmpleado[ $idEmpleados ]['totaldiacheck'] 
		// antes estaba $diaspago
		 
		 
		$baseparabonos = ($arrayEmpleados[ $idEmpleados ]['salariobase']* $diaspago ) * (10/100);	
		/*ESTE APLICA SI SOLO DAN BONOS SI ASISTEN TODA LA SEMANA CASO KERLAB SOLO SE PAGA EL PROPORCIONAL
		 * DE LOS DIAS Q ASISTIO OSEA Q SE LE DAN BONOS PROPORCIONALES 
		if($tiposperiododatos->idperiodicidad == 2){
			
			
			if($checkEmpleado[ $idEmpleados ]['totaldiacheck'] == 5){//periodo semanal trabajan solop 5 dias, 2 dias sab y dom
				$bonosEmpleados [ $idEmpleados ] [ "bonoasistencia" ] = ($arrayEmpleados[ $idEmpleados ]['salariobase']* $diaspago )* (10/100);
				$checkEmpleado[ $idEmpleados ]['diasIMSS'] = 7;//se paga 7 dias al imss porq si fue toda la semana
			}
			
			if($checkEmpleado[ $idEmpleados ]['bonopuntualidad'] == 5){//semanal si los 5 dias fue puntual BONO!
				$bonosEmpleados [ $idEmpleados ] [ "bonopuntualidad" ] = ($arrayEmpleados[ $idEmpleados ]['salariobase']* $diaspago ) * (10/100) ;
			}
		}elseif($tiposperiododatos->idperiodicidad == 3){
			
			if($checkEmpleado[ $idEmpleados ]['totaldiacheck'] == 10){//periodo catorcenal trabajan 10 dias, 4 dias sab y dom
				$bonosEmpleados [ $idEmpleados ] [ "bonoasistencia" ] = ($arrayEmpleados[ $idEmpleados ]['salariobase']* $diaspago ) * (10/100);
				$checkEmpleado[ $idEmpleados ]['diasIMSS'] = 14;//fue en la catorcena los 10 dias se paga al imss 14
			}
			
			if($checkEmpleado[ $idEmpleados ]['bonopuntualidad'] == 10){//catorcenal si los 10 dias fue puntual BONO!
				$bonosEmpleados [ $idEmpleados ] [ "bonopuntualidad" ] = ($arrayEmpleados[ $idEmpleados ]['salariobase']* $diaspago ) * (10/100) ;
			}
		}
		 */
		//se quitan los dias porq tenemos separadas las incidencias
		//$checkEmpleado[ $idEmpleados ]['totaldiacheck'] -= $checkEmpleado[ $idEmpleados ]['diasvacaciones'];
		//krmn
		//nota puntualidad se paga igual
		if($tiposperiododatos->idperiodicidad == 2){
			
			if($checkEmpleado[ $idEmpleados ]['totaldiacheck'] == 5){ //periodo semanal trabajan solop 5 dias, 2 dias sab y dom
				$bonosEmpleados [ $idEmpleados ] [ "bonoasistencia" ] = ($arrayEmpleados[ $idEmpleados ]['salariobase']* $diaspago )* (10/100);
				$checkEmpleado[ $idEmpleados ]['diasIMSS'] = 7;//se paga 7 dias al imss porq si fue toda la semana
				//$bonosEmpleados [ $idEmpleados ] [ "bonopuntualidad" ] = ($arrayEmpleados[ $idEmpleados ]['salariobase']* $diaspago ) * (10/100) ;
			
			}else{
			//if($checkEmpleado[ $idEmpleados ]['totaldiacheck'] == 5){//periodo semanal trabajan solop 5 dias, 2 dias sab y dom
				$bonosEmpleados [ $idEmpleados ] [ "bonoasistencia" ] = ($arrayEmpleados[ $idEmpleados ]['salariobase']* $checkEmpleado[ $idEmpleados ]['diaspagosalario'] )* (10/100);
				$checkEmpleado[ $idEmpleados ]['diasIMSS'] = 7;//se paga 7 dias al imss porq si fue toda la semana
				//$bonosEmpleados [ $idEmpleados ] [ "bonopuntualidad" ] = ($arrayEmpleados[ $idEmpleados ]['salariobase']* $checkEmpleado[ $idEmpleados ]['diaspagosalario'] ) * (10/100) ;
				
			}
			if($checkEmpleado[ $idEmpleados ]['bonopuntualidad'] == 5){//semanal si los 5 dias fue puntual BONO!
				$bonosEmpleados [ $idEmpleados ] [ "bonopuntualidad" ] = ($arrayEmpleados[ $idEmpleados ]['salariobase']* $diaspago ) * (10/100) ;
			}
			
			
		}elseif($tiposperiododatos->idperiodicidad == 3){
			
			
			if($checkEmpleado[ $idEmpleados ]['totaldiacheck'] == 10){
				$bonosEmpleados [ $idEmpleados ] [ "bonoasistencia" ] = ($arrayEmpleados[ $idEmpleados ]['salariobase']* $diaspago )* (10/100);
				$checkEmpleado[ $idEmpleados ]['diasIMSS'] = 14;
				//$bonosEmpleados [ $idEmpleados ] [ "bonopuntualidad" ] = ($arrayEmpleados[ $idEmpleados ]['salariobase']* $diaspago ) * (10/100) ;
			
			}else{
				$bonosEmpleados [ $idEmpleados ] [ "bonoasistencia" ] = ($arrayEmpleados[ $idEmpleados ]['salariobase']* $checkEmpleado[ $idEmpleados ]['totaldiacheck'] )* (10/100);
				$checkEmpleado[ $idEmpleados ]['diasIMSS'] = 14;
				//$bonosEmpleados [ $idEmpleados ] [ "bonopuntualidad" ] = ($arrayEmpleados[ $idEmpleados ]['salariobase']* $checkEmpleado[ $idEmpleados ]['totaldiacheck'] ) * (10/100) ;
				
			}
			if($checkEmpleado[ $idEmpleados ]['bonopuntualidad'] == 10){//catorcenal si los 10 dias fue puntual BONO!
				$bonosEmpleados [ $idEmpleados ] [ "bonopuntualidad" ] = ($arrayEmpleados[ $idEmpleados ]['salariobase']* $diaspago ) * (10/100) ;
			}
		}	
			
			
	}
		/* 		FIN CHECK EMPLEADO		 */
		
		/*  C A L C U L O   N O M I N A   P O R   E M P L E A D O  */
		

		/* CALCULO ISPT(ISR) de acuerdo al tipo de periodo
			TABLA ISR SEMANAL x 2 para el periodo catorcenal todo por dos excepto el porcentaje
			TABLA SUBSIDO  todo x2 
		 	ispt = ((base - limite inferior) * pocentanje) + cuota fija
		 	ISR RETENIDO = ISPT - SUBSIDIO
		 	ISR ENTREGADO = SUBSIDIO - ISPT
		 	//idperiodicidad
		 	2	Semanal	
			3	Catorcenal	
		*/
		
	foreach ( $arrayEmpleados as $idEmpleados => $datos ){ 
	
		
		$diaspago = $checkEmpleado[ $idEmpleados ]['diaspago'];
		
		$salarioperiodo = $arrayEmpleados[ $idEmpleados ]['salariobase'] * ($checkEmpleado[ $idEmpleados ]['diaspago']) 	;
		/*	28 de agosto 2017 - se modifico el retirar los bonos de el salarioperiodo para dejar la base gravada ya que el
		 * sueldo es gravado al 100% pero los alimentos/bonos solo si es el 10% quedan exentos
		 * por si cambian de opinion ¬¬ estaba asi 
		 * $salarioperiodo = $arrayEmpleados[ $idEmpleados ]['salariobase'] * ($diaspago) +$bonosEmpleados [ $idEmpleados ] [ "bonoasistencia" ]+$bonosEmpleados [ $idEmpleados ] [ "bonopuntualidad" ]	;
		 * 
		 * 
		 * $basegravada =  $salarioperiodo + partegravada( primavacacional ) + partegravada(aguinaldo )
			partegravada( primavacacional + aguinaldo definicion trello nominas +bonos
			el salario del empleado es gravado al 100% 
		 * 
		*/
		$primavacacional= $gravadoprimaImporte = 0;
		if($checkEmpleado[ $idEmpleados ]['diasvacaciones']!=0){//15 veces SM O UMA 
			$primavacacional = ($checkEmpleado[ $idEmpleados ]['diasvacacionesproporcion'] * $arrayEmpleados[ $idEmpleados ]['salariobase'])* .25;
			$exentoprima = $UMA->valor * 15;
			// si la parte exenta es mayor que la prima entonces toda la prima esta exenta
			if($exentoprima > $primavacacional){
				$gravadoprimaImporte = 0;
			}else{
				$gravadoprimaImporte = $primavacacional - $exentoprima;
			}  
		}
		
		/*Cambio al 26 de octubre 2017 los bonos seran incluidos para isr
		 * antes estaba sin ellos
		 * $basegravada = $salarioperiodo + $gravadoprimaImporte */
		$basegravada = $salarioperiodo + $gravadoprimaImporte + $bonosEmpleados [ $idEmpleados ] [ "bonoasistencia" ]+$bonosEmpleados [ $idEmpleados ] [ "bonopuntualidad" ]	;
			$isrvalores = $this->PrenominaModel->tablaISR($tiposperiododatos->idperiodicidad,$basegravada);
			$arrayISPT[ $idEmpleados ]['cuotafija'] 	= $isrvalores->cuotafija;
			$arrayISPT[ $idEmpleados ]['porcentaje'] 	= $isrvalores->porcentaje;
			//se quitan los dias de vacacione sdel sueldo para ponerlo en otro concepto aunq es tomado para los calculos de imss etc
			$arrayISPT[ $idEmpleados ]['salarioperiodo']= $arrayEmpleados[ $idEmpleados ]['salariobase'] * (($checkEmpleado[ $idEmpleados ]['diaspagosalario']));
			$ispt =  ( ($basegravada - $isrvalores->limite_inferior) * ($isrvalores->porcentaje/100))  + $isrvalores->cuotafija ;
			$arrayISPT[ $idEmpleados ]['ispt'] = $ispt;
		/* SUBSIDIO */
			$subsidiovalores = $this->PrenominaModel->tablaSubsidio($tiposperiododatos->idperiodicidad,$basegravada);
			$arrayISPT[ $idEmpleados ]['subsalempleo'] = $subsidiovalores->subsalempleo;
			$arrayISPT[ $idEmpleados ]['isptentregado'] = $subsidiovalores->subsalempleo - $ispt;
			$arrayISPT[ $idEmpleados ]['isptretenido'] =  $ispt - $subsidiovalores->subsalempleo;
		/*Cuando el ispt es mayor al subsidio se le debe retener.
		  Cuando el ispt es menor se le debe entregar.
	 * si es retenido es deduccion
		 * si es entregado es percepcion
		*/
			if($ispt < $subsidiovalores->subsalempleo){
				//Subs. Sala CONCEPTO DE NOMINA
				$arrayISPT[ $idEmpleados ]['subsidiopercepcion'] =  $arrayISPT[ $idEmpleados ]['isptentregado'];
			}elseif($ispt > $subsidiovalores->subsalempleo){
				//ISPT CONCEPTO DE NOMINA
				$arrayISPT[ $idEmpleados ]['isptdeduccion'] =  $arrayISPT[ $idEmpleados ]['isptretenido'];
			}
			
		
		
			
		}//foreach empleados
		
		/*BONO DE PUNTUALIDAD 
		 * SUELDO = SALARIO DIARIO * DIAS TRABAJADOS + SAB Y DOM EN CASO KERLAB
		 * SUELDO DIARIOS
			Bono Asistencia  = (SUELDO * 10%) 
			Bono Puntualidad = (SUELDO * 10%)
			esto solo si entra en los 10 min tolerancia en checada de empleado 
		 */
		
		foreach ( $arrayEmpleados as $idEmpleados => $datos ){
			
			/* listamos los conceptos de nomina establecidos en la configuracion 
			 * EN TEORIA DEBERIAMOS HACER ESTO PARA PRIMERO SABER QUE SE DEBE CALCULAR 
			 * PERO EN CASO KERLAB YA LO SABEMOS igual se pone asi para facilitar un poco
			 * cuando se vaya a dejar abierto para otras empresas
			 * */
			 
			 
 /*	######## N O T A     M U Y        I M P O R T A N T E E E E E E E  !!!!######################
			 en el caso del calculo el usuario debe tener bien relacionado el idagrupador del sat
			  * ya que si este no esta correcto no calculara nada y si cambia el catalogo abra 
			  * q reasignar todo lo hice a si porq era la forma de identificar 
  			* q estaba calculando dentro de la nomina acuerdo al sat
  			* porq puede tener muchas percepciones pero no sabría cual es en especifico por eso me guíe con el sat
			  * si encuentran otra forma agreguenla ¬¬ 
  * ###########################################################################################*/
			$conceptos = $this->CatalogosModel->conceptosPrenominaExiste();
			$conceptosarary = array();
			while($c = $conceptos->fetch_object()){
				
				$conceptosarary[$c->idtipo][$c->idconfpre] = $c->idconfpre."/".$c->idconcepto;
				
				/* PERCEPCIONES */
				if($c->idAgrupador == 1 && $c->idtipo == 1){//si el tipo es 1-percepcion y el idagrupador es 1 estamos hablando de sueldo y salarios
				// las vacaciones son consideras como sueldos y salarios pero debe ponerse aparte
					//$busca = stristr();
					$busca2 = strripos($c->descripcion, "vacacion");
					if($busca2 !== false){
						$sueldovacaciones = $checkEmpleado[ $idEmpleados ]['diasvacacionesproporcion'] * $arrayEmpleados[ $idEmpleados ]['salariobase'];
						$percepcionesEmpleado [$idEmpleados][$c->idconfpre] ['importe']= $sueldovacaciones;
						$percepcionesEmpleado [$idEmpleados][$c->idconfpre] ['diasvacaciones']= $checkEmpleado[ $idEmpleados ]['diasvacaciones'];
						/* vacaciones al 100% */
						$percepcionesEmpleado [$idEmpleados][$c->idconfpre] ['gravado'] = $sueldovacaciones;
						$percepcionesEmpleado [$idEmpleados][$c->idconfpre] ['exento'] = 0;
					}else{
						$percepcionesEmpleado [$idEmpleados][$c->idconfpre] ['importe'] = $checkEmpleado[ $idEmpleados ]['diaspagosalario'] * $arrayEmpleados[ $idEmpleados ]['salariobase'];
						//$percepcionesEmpleado [$idEmpleados][$c->idconfpre] ['importe']= $arrayISPT[ $idEmpleados ]['salarioperiodo'];
						/* sueldo gravado al 100% */
						//$percepcionesEmpleado [$idEmpleados][$c->idconfpre] ['gravado'] = $arrayISPT[ $idEmpleados ]['salarioperiodo'];
						$percepcionesEmpleado [$idEmpleados][$c->idconfpre] ['gravado'] = $checkEmpleado[ $idEmpleados ]['diaspagosalario'] * $arrayEmpleados[ $idEmpleados ]['salariobase'];
						$percepcionesEmpleado [$idEmpleados][$c->idconfpre] ['exento'] = 0;
						if($checkEmpleado[ $idEmpleados ]['diasfestivos']>0){
							$checkEmpleado[ $idEmpleados ][$c->idconfpre]['diasfestivos'] = $checkEmpleado[ $idEmpleados ]['diasfestivos'];
						}
					
					}
					
					
					
				}
				/*PRIMA VACACIONAL
				 * Salario Diario x Dias de Vacaciones = Resultado x .25 (que es % de Prima Vacacional)
				 * */
				if($c->idAgrupador == 18 && $c->idtipo == 1){//prima vacacional
					if($checkEmpleado[ $idEmpleados ]['diasvacaciones']>0){
						$primavacacional = ($checkEmpleado[ $idEmpleados ]['diasvacacionesproporcion'] * $arrayEmpleados[ $idEmpleados ]['salariobase'])* .25;
						$percepcionesEmpleado [$idEmpleados][$c->idconfpre] ['importe']= $primavacacional;
						/* sueldo gravado al 100% */
						$percepcionesEmpleado [$idEmpleados][$c->idconfpre] ['gravado'] = $primavacacional;
						$percepcionesEmpleado [$idEmpleados][$c->idconfpre] ['exento'] = 0;
					}
				}

	
				if($c->idAgrupador == 35 && $c->idtipo == 1){//Bono asistencia SAT(otros) y ayuda alimentos los 2 estan en otros igual son ids difernetes
					
					$busca = strripos($c->descripcion, "alimentos");
					if($busca !== false){ 
						//$diasIncEnChecador  = $this->PrenominaModel->diasIncEnChecador($idEmpleados, $_REQUEST['numnomina']);
						//se restan los dias de incapacidad marcados en checador para no tomarlos en cuenta para los pago de alimentos
						if( $arrayEmpleados[ $idEmpleados]['alimentos'] >0 ){ 
							if($tiposperiododatos->idperiodicidad == 2){
								$alimentoxdia = $arrayEmpleados[ $idEmpleados]['alimentos'] / 5;//5 dias trabajan en semanal caso kerlab, el 5 seria para politicas dias trabajados
								$totalalimento = $alimentoxdia * ($checkEmpleado[ $idEmpleados ]['totaldiacheck']);
							}else if($tiposperiododatos->idperiodicidad == 3){
								$alimentoxdia = $arrayEmpleados[ $idEmpleados]['alimentos'] / 10;//5 dias trabajan en semanal caso kerlab, el 5 seria para politicas dias trabajados
								$totalalimento = $alimentoxdia * ($checkEmpleado[ $idEmpleados ]['totaldiacheck']);
							}
							
							$percepcionesEmpleado [$idEmpleados][$c->idconfpre]['importe'] = $totalalimento;
							$percepcionesEmpleado [$idEmpleados][$c->idconfpre] ['gravado']= 0;
							$percepcionesEmpleado [$idEmpleados][$c->idconfpre] ['exento'] = $totalalimento;
						}
						
					}
					$busca2 = strripos($c->descripcion, "asistencia");
					if($busca2 !== false){
						$percepcionesEmpleado [$idEmpleados][$c->idconfpre]['importe'] = $bonosEmpleados [ $idEmpleados ] [ "bonoasistencia" ];
						/* gravado al 100 */
						$percepcionesEmpleado [$idEmpleados][$c->idconfpre] ['gravado'] =0;
						$percepcionesEmpleado [$idEmpleados][$c->idconfpre] ['exento'] = $bonosEmpleados [ $idEmpleados ] [ "bonoasistencia" ];
					}
					//$otrasPercepciones [$idEmpleados][$c->idconfpre][$c->concepto] = alimentos;
				}
			/*El subsidio se movera porq en el 3.3 complemento 1.2 ya no debe ir el subsidio como
			 * percepcion debe estar en otros pagos
			 * si este permanece aqui al timbrar marca como invalido*/
				// if($c->idAgrupador == 15 && $c->idtipo == 1){//subsidio SAT(subsidio para el emple)
					// $percepcionesEmpleado [$idEmpleados][$c->idconfpre]['importe'] = $arrayISPT[ $idEmpleados ]['subsidiopercepcion'];
					// $percepcionesEmpleado [$idEmpleados]['acumula']['idconcepto'] = $c->idconcepto;	
					// $percepcionesEmpleado [$idEmpleados]['acumula']['valor']	= $arrayISPT[ $idEmpleados ]['subsidiopercepcion'];
// 				
				// }
		/*Fin nota */
				if($c->idAgrupador == 8 && $c->idtipo == 1){//premio puntualidad SAT(premio x puntualidad)
				//$bonosEmpleados[ $idEmpleados ]['bonopuntualidad']
					$percepcionesEmpleado [$idEmpleados][$c->idconfpre]['importe'] = $bonosEmpleados[ $idEmpleados ]['bonopuntualidad'];
					$percepcionesEmpleado [$idEmpleados][$c->idconfpre]['gravado'] = 0;
					$percepcionesEmpleado [$idEmpleados][$c->idconfpre]['exento']  = $bonosEmpleados[ $idEmpleados ]['bonopuntualidad'];
				}
				
				
				
				/* FIN PERCEPCIONES */
				
				/*OTROS PAGOS*/
				if($c->idAgrupador == 2 && $c->idtipo == 4){//subsidio SAT(subsidio para el emple)
					$percepcionesEmpleado [$idEmpleados][$c->idconfpre]['importe'] = $arrayISPT[ $idEmpleados ]['subsidiopercepcion'];
					$percepcionesEmpleado [$idEmpleados]['acumula']['idconcepto'] = $c->idconcepto;	
					$percepcionesEmpleado [$idEmpleados]['acumula']['valor']	= $arrayISPT[ $idEmpleados ]['subsidiopercepcion'];
				
				}
				/*FIN OTROS PAGOS*/
				
				
				/* DEDUCCIONES */
				
				/*Deduccion de alimentos
				 * SM * 20% = $RESULT * DIAS LABORADOS
				*/
				if($c->idAgrupador == 4 && $c->idtipo == 2){//alimentos sat(otros)
					
					$alimentosDeduc = $SMvigente->zona_a * (20/100); 
					$alimentosDeduc = $alimentosDeduc * ($checkEmpleado[ $idEmpleados ]['totaldiacheck'] );
					
					$deduccionesEmpleado [$idEmpleados][$c->idconfpre] = number_format($alimentosDeduc,2,'.','');
					
				}
				
				/* I M S S 
				 * 
				 * 	
				 */
				
				if($c->idAgrupador == 1 && $c->idtipo == 2){//imss sat(seguridad social)
					// 3 veces el salario minimo MINIMO
					$tresvecesSM = ($SMvigente->zona_a * 3);
					//
					/*
					 * SI EL EMPLEADO GANA EL SALARIO MINIMO TOPADO 25 VECES COMO SDI 
					 * SE CALCULA TODO IGUAL PERO el nuevo SDI sera el topado 
					 * y sobre ese se ara el calculo, EL MONTO EMPLEADO SE LE ABONA AL TOTAL PATRON 
					 * Y AL EMPLEADO NO DEBE APARECERLE NADA DE IMSS COMO DEDUCCION
					 * topado a 25 veces el SM (MAXIMO)
					 * SE CAMBIO A UMA PORQUE A SI LO DIJO EL CONTADOR ERNESTO
					 * (AHORA EL TOPADO ES 25 UMA A PARTIR DE ABRIL 2017)
					*/
					$topado25vecesSM = ($UMA->valor * 25);
					
					$SDIimss = $arrayEmpleados[ $idEmpleados ]['SDI'];
					
					if($arrayEmpleados[ $idEmpleados ]['SDI'] > $topado25vecesSM ){
						$SDIimss = $topado25vecesSM;
					}
					// fin topado
					
					/*$diasIMSS se usa esta porq para el IMSS
					 *  se deben tomar los dias trabajados EJEMPLO: 
					 * * En un periodo 14 solo fue 6 dias se cotizan 6 dias unicamente
					 * * En periodo 14 fue 10 completa las 2 semanas entonces se le pagan 14 dias
					 * 
					 * ahora se debe sacar tambien la proporcion tambien esto mencionado por el contador
					 * $diaspago= diasIMSS
					 * $checkEmpleado[ $idEmpleados ]['diasIMSS'] 
					 * $diaspago ya tiene la porporcion de los dias
					 */
					$diaspago = $checkEmpleado[ $idEmpleados ]['diaspago'];
					$base = $SDIimss * $diaspago ;
					
				//ENFERMEDADES Y MATERNIDAD//	ENFERMEDADES Y MATERNIDAD //ENFERMEDADES Y MATERNIDAD
				
					//C U O T A   F I J A (HASTA 3 SALARIOS MINIMOS) Y  C U O T A  F I J A  E X E N D E N T E(SALARIO BASE MAYOR A 3 VECES EL SALARIO MINIMO)	
					if($SDIimss < $tresvecesSM ){
						
						$cuotafijaPatron 			= $base * (20.4/100);//20.40%
						$cuotafijaExedentePatron		= 0;
						$cuotafijaExedenteEmpleado 	= 0;
					}
					//SI EL SDI ES MAYO A 3 VECES EL SM ENTONCES SE DEBE SACAR LA DIFERENCIA
					//Y POR LOS DIAS TRABAJADOS Y POR LA CUOTA DE EXEDENTE
					else{
						
						$cuotafijaPatron				= ($tresvecesSM * $diaspago) * (20.4/100);//20.4%
						$baseExedente			 	= ($SDIimss - $tresvecesSM) * $diaspago;
						$cuotafijaExedentePatron 	= $baseExedente * (1.1/100);//1.1%
						$cuotafijaExedenteEmpleado 	= $baseExedente * (.4/100);//.4%
					}
					//F I N  C U O T A   F I J A  Y  C U O T A  F I J A  E X E N D E N T E
					
					//G A S T O S   M E D I C O S
					
					$gastosMedicosPatron		= $base * (1.05/100);//1,050%
					$gastosMedicosEmpleado	= $base * (0.375/100);//1,050%
					// FIN G A S T O S   M E D I C O S
					
					//E N  D I N E R O
					$endineroPatron		= $base * (0.7/100);
					$endineroEmpleado	= $base * (0.25/100);
					//F I N  "E N  D I N E R O"
					
				// FIN ENFERMEDADES Y MATERNIDAD //FIN ENFERMEDADES Y MATERNIDAD //FIN ENFERMEDADES Y MATERNIDAD 
			
				//INVALIDEZ Y VIDA // INVALIDEZ Y VIDA// INVALIDEZ Y VIDA // INVALIDEZ Y VIDA/// INVALIDEZ Y VIDA
					//E N  E S P E C I E  Y  D I N E R O	
					$enespeciePatron 	= $base * (1.75/100);
					$enespecieEmpleado	= $base * (0.625/100);
					// F I N  "E N  E S P E C I E  Y  D I N E R O"
				//FIN INVALIDEZ Y VIDA // INVALIDEZ Y VIDA// INVALIDEZ Y VIDA // INVALIDEZ Y VIDA/// INVALIDEZ Y VIDA
				
				//GUARDERIA	//	GUARDERIA 	// GUARDERIA		// 	GUARDERIA	//
					$guarderiaPatron = $base * (1/100);
			
				//FIN //GUARDERIA	//	GUARDERIA 	// GUARDERIA		// 	GUARDERIA	//
			
				//	RIESGO DE TRABAJO	//	RIESGO DE TRABAJO	//	RIESGO DE TRABAJO	//
					$riesgotrabajoPatron = $base * (0.5/100);
				//FIN //	RIESGO DE TRABAJO	//	RIESGO DE TRABAJO	//	RIESGO DE TRABAJO	//
			
				//RETIRO, CESANTIA EN EDAD AVANZADA Y VEJEZ	//RETIRO, CESANTIA EN EDAD AVANZADA Y VEJEZ	//
					$retiroPatron			= $base * (2/100);
					$cesantiavejesPatron 	= $base * (3.15/100);
					$cesantiavejesEmpleado 	= $base * (1.125/100);
				//FIN //RETIRO, CESANTIA EN EDAD AVANZADA Y VEJEZ	//RETIRO, CESANTIA EN EDAD AVANZADA Y VEJEZ	//
			
					$imsscuotas[$idEmpleados]['patron']		= $cuotafijaPatron + $cuotafijaExedentePatron + $gastosMedicosPatron + $endineroPatron + $enespeciePatron + $guarderiaPatron +  $riesgotrabajoPatron+$retiroPatron+$cesantiavejesPatron;
					$imsscuotas[$idEmpleados]['empleado'] 	= $cesantiavejesEmpleado +$enespecieEmpleado+$endineroEmpleado+$gastosMedicosEmpleado+$cuotafijaExedenteEmpleado;
					$imsscuotas[$idEmpleados]["base"] = $base;
					$imsscuotas[$idEmpleados]["dias"] = $diaspago;
					$imsscuotas[$idEmpleados]["sdi"] = $SDIimss;
					
					
					
					
					
					
			/* 		F I N 		I M S S 		uff!! */
				
					$deduccionesEmpleado [$idEmpleados][$c->idconfpre] = $imsscuotas[$idEmpleados]['empleado'] ;
				}
				if($c->idAgrupador == 2 && $c->idtipo == 2){//ispt sat(isr)
					$deduccionesEmpleado [$idEmpleados][$c->idconfpre] =abs( $arrayISPT[ $idEmpleados ]['isptdeduccion']);
					$deduccionesEmpleado [$idEmpleados]['acumula']['idconcepto']	= $c->idconcepto;	
					$deduccionesEmpleado [$idEmpleados]['acumula']['valor']	= abs($arrayISPT[ $idEmpleados ]['isptdeduccion']);
				}
				
		/* infonavitEmpleado */
				/* Si es porcentaje sobre => sdi * %porcentaje
				 * Si es vsm => salario minimo * veces
				 * Cuota fija => monto entre el (periodo semanal /4 catorcenal /2) 
				 * Movimiento permanente => monto fijo por periodo a la semana quincena etc sin operaciones
				 * SOLO SE PUEDE TENER UN CREDITO INFONAVIT
				 * nomi_tinfonavit_segvivienda ESTA TABLA PARA CUANDO se marco el incluir pago de seguro
				 * este siempre sera tan cual el monto
				 * proporcion de dias trabajados siempre la empresa solo paga eso si el 
				 * tiene monto fijo u otra el paga por fuera
				 * el seguro se paga solo una vez al mes
				*/
				if($c->idAgrupador == 10 && $c->idtipo == 2){//prestamo infonavit  sat(Pago por crédito de vivienda)
					if($tiposperiododatos->idperiodicidad == 2){
						$periodoenmes = 4;
						$diasperiodoinf = 7;
					}elseif($tiposperiododatos->idperiodicidad == 3){
						$periodoenmes = 2;
						$diasperiodoinf = 14;
					}
					$datosInfonavit = $this->PrenominaModel->infonavitEmpleado( $idEmpleados, $_REQUEST['fechafin']); 
					
					//1-Movto. Permanente ( Concepto D-59),
					//2-Porcentaje ( Concepto D-59,
					//3-Veces salario minimo ( Concepto D-15),29 AGOSTO 2017 - ahora ya no es vsm es el uma
					//4-Cuota fija ( Concepto D-16)
					if($datosInfonavit!=0){
						$infonavitarray[ $idEmpleados ][$datosInfonavit->tipocredito]["factormensual"] = $datosInfonavit->importecreditofactormensual;
						if( $datosInfonavit->tipocredito == 1 ){//Monto permanente => monto fijo por periodo a la semana quicena etc sin operaciones
							$totalinfonavitmes = $datosInfonavit->importecreditofactormensual;
							$totalinfonavitperiodo = $totalinfonavitmes / $diasperiodoinf;
							$totalinfonavitperiododias = $totalinfonavitperiodo * $checkEmpleado[ $idEmpleados ]['diaspago'] ;
						}
						if( $datosInfonavit->tipocredito == 2 ){//Si es porcentaje sobre => sdi * %porcentaje
							$totalinfonavitmes = ( $arrayEmpleados[ $idEmpleados ]['SDI'] * ($datosInfonavit->importecreditofactormensual/100 )) ;
							//$totalinfonavitperiodo = $totalinfonavitmes / $diasperiodoinf;
							$totalinfonavitperiododias = $totalinfonavitmes * $checkEmpleado[ $idEmpleados ]['diaspago'];
						}
						if( $datosInfonavit->tipocredito == 3 ){//uma * veces * 2(Que es el bimestre) + 15(que es el seguro)/62 (dias en el bimestre como marca infonavit)
							$totalinfonavitmes = (( ($UMA->valor * $datosInfonavit->importecreditofactormensual )* 2 ) + 15 ) /62;
							//$totalinfonavitperiodo = $totalinfonavitmes / $diasperiodoinf;
							$totalinfonavitperiododias = $totalinfonavitmes * $checkEmpleado[ $idEmpleados ]['diaspago'];
						}
						if( $datosInfonavit->tipocredito == 4 ){//Cuota fija => monto al mes
							$totalinfonavitmes = ( ( $datosInfonavit->importecreditofactormensual * 2) + 15) / 62;
							//$totalinfonavitperiodo = $totalinfonavitmes / $diasperiodoinf;
							$totalinfonavitperiododias = $totalinfonavitmes * $checkEmpleado[ $idEmpleados ]['diaspago'];
						}
						$deduccionesEmpleado [$idEmpleados][$c->idconfpre] = $totalinfonavitperiododias;
						$infonavitarray[ $idEmpleados ][$datosInfonavit->tipocredito]["importe"] = $deduccionesEmpleado [$idEmpleados][$c->idconfpre];
						//sumado el 15 de seguro de vivienda
						$datosNomina = $this->CatalogosModel->datosNomina($_REQUEST['numnomina']);
			
						// OMITI EL SEGURO PORQ YA SE CONTEMPLO EN EL MONTO BIMENSTRAL
						//if( $datosInfonavit->incluirpagoseguro == 1){//si incluye el seguro debera tomar el valor de la tabla nomi_tinfonavit_segvivienda que es fijo
							// if( $datosNomina->finbimentreimss == 1){// como es una sola vez al bimestre la tomare el fin de bimestre
								// $segurocuotaInfo = $this->PrenominaModel->seguroViviendaInfonavit();
								// $infonavitarray[ $idEmpleados ][$datosInfonavit->tipocredito]["Seguro de vivienda Infonavit"]=$segurocuotaInfo;
								// $infonavitarray[ $idEmpleados ][$datosInfonavit->tipocredito] += $segurocuotaInfo;
							// }
						// }
						//fin seguro
						
					}else{
						$deduccionesEmpleado [$idEmpleados][$c->idconfpre] = 0.00;
					}
				}
				
				
		/* fin infonavit Empleado */
		
		/* seguro infonavit Empleado  con concepto separado evaluar si se queda*/
		// if($c->idAgrupador == 9 && $c->idtipo == 2){
			// $datosInfonavit = $this->PrenominaModel->infonavitEmpleado( $idEmpleados, $_REQUEST['fechafin']); 
			// //el seguro se paga una sola vez al mes
			// $datosNomina = $this->CatalogosModel->datosNomina($_REQUEST['numnomina']);
// 			
			// if( $datosInfonavit->incluirpagoseguro == 1){//si incluye el seguro debera tomar el valor de la tabla nomi_tinfonavit_segvivienda que es fijo
				// if( $datosNomina->finbimentreimss == 1){// como es una sola vez al bimestre la tomare el fin de bimestre
					// $segurocuotaInfo = $this->PrenominaModel->seguroViviendaInfonavit();
					// $infonavitarray[ $idEmpleados ][$datosInfonavit->tipocredito]["Seguro de vivienda Infonavit"]=$segurocuotaInfo;
					// $deduccionesEmpleado [$idEmpleados][$c->idconfpre] = $segurocuotaInfo;
				// }
			// }
		// }
		/* fin seguro infonavit Empleado */
				
				
				
				/* FIN DEDUCCIONES */
				
				
			}
			
		} 
		
		
		$arraygeneral ["percepciones"] = $percepcionesEmpleado;
		$arraygeneral ["deducciones"] = $deduccionesEmpleado;
		
		
	/* muestra en tabla de prenomina */
	$return = "";
	if($_REQUEST['todos']>0){
		$this->PrenominaModel->eliminaCalculoprevio($_REQUEST['numnomina'],0);//elimino calculo previo de nomina
	}
	foreach ( $arrayEmpleados as $idEmpleados => $datos ){
			
			$return.= "	
			<tr style='' onMouseDown='adicional(".$idEmpleados.");' id='".$idEmpleados."'>
				<td style='height:40px;width:110px;' align='center'>".$datos['codigo']."</td>
				<td style='height:40px;width:110px;' align='center'>".strtoupper($datos['nombre'])."</td>";
			
			//$conceptosarary[$c->idtipo][$c->idconfpre]
			$percep = $deduc = $diasvacaciones = $diasfestivos = 0;
			if($checkEmpleado[ $idEmpleados ]['diasvacaciones']>0){
				$diasvacaciones = $checkEmpleado[ $idEmpleados ]['diasvacaciones'];		
			}
			if($checkEmpleado[ $idEmpleados ]['diasfestivos']>0){
				$diasfestivos = $checkEmpleado[ $idEmpleados ]['diasfestivos'];
			}
			$diaslaborados = $checkEmpleado[ $idEmpleados ]['totaldiacheck']-$checkEmpleado[ $idEmpleados ]['diasfestivos'];
			$diaspagopropor = $checkEmpleado[ $idEmpleados ]['diaspagoi'] * ($diaslaborados)  ;
		
			foreach($conceptosarary as $tipo => $concepto ){
				
				//$percepcionesEmpleado [$idEmpleados][$c->idconfpre]
				//para que quiera tipo-1 percepciones y tipo-4 otros pagos
				if($tipo==1 || $tipo==4){
					foreach($conceptosarary[$tipo] as $idtipo ){
						$separa = explode('/', $idtipo);
						$idtipoconcepto = $separa[1];//id concepto d tabla nomi_conceptos
						$idtipo = $separa[0];//id concepto de configuracion prenomia
						
							$percep += number_format($percepcionesEmpleado[$idEmpleados][$idtipo]['importe'],2,'.','');
							$return.="<td style='height:40px;width:110px;' align='right' onclick='editar()' >".number_format($percepcionesEmpleado[$idEmpleados][$idtipo]['importe'],2,'.',',')."</td>";
						if($percepcionesEmpleado[$idEmpleados][$idtipo]['importe']>0){
							
							if($percepcionesEmpleado[$idEmpleados][$idtipo]['diasvacaciones']>0){
								$diasvalor = $percepcionesEmpleado[$idEmpleados][$idtipo]['diasvacaciones'];
								
								
							}else{
								$percepcionesEmpleado[$idEmpleados][$idtipo]['diasvacaciones']=0;
								$diasvalor = $checkEmpleado[ $idEmpleados ]['diaspago'] ; 
							}
							
							$this->PrenominaModel->almacenaCalculo(0,$idEmpleados, $_REQUEST['numnomina'], $idtipoconcepto, number_format($percepcionesEmpleado[$idEmpleados][$idtipo]['importe'],2,'.',''), number_format($percepcionesEmpleado[$idEmpleados][$idtipo]['gravado'],2,'.',''),number_format($percepcionesEmpleado[$idEmpleados][$idtipo]['exento'],2,'.',''),$checkEmpleado[ $idEmpleados ]['diaspago'],$diaslaborados,$diasvalor,1,'', $arrayEmpleados[ $idEmpleados ]['salariobase'], $arrayEmpleados[ $idEmpleados ]['SDI'],0 ,$diasvacaciones,$diasfestivos,$diaspagopropor );
							// if($checkEmpleado[ $idEmpleados ]['diasvacaciones']>0){
								// $this->PrenominaModel->updateValorDiasVac($checkEmpleado[$idEmpleados]['diasvacaciones'], $idtipoconcepto, $_REQUEST['numnomina'], $idEmpleados);
							// }
							//para solo ponerlo en el concepto
							//if($checkEmpleado[ $idEmpleados ][$idtipo]['diasfestivos']>0){
								//$this->PrenominaModel->updateValorDiasFestivo($checkEmpleado[ $idEmpleados ][$idtipo]['diasfestivos'], $idtipoconcepto, $_REQUEST['numnomina'], $idEmpleados);
							//}
							
						}
					}
				}
				if($tipo==2){
					foreach($conceptosarary[$tipo] as $idtipo ){
						$separa = explode('/', $idtipo);
						$idtipoconcepto = $separa[1];
						$idtipo = $separa[0];	
							$deduc += number_format($deduccionesEmpleado[$idEmpleados][$idtipo],2,'.','');	
							$return.="<td style='height:40px;width:110px;' align='right'>".number_format($deduccionesEmpleado[$idEmpleados][$idtipo],2,'.',',')."</td>";
						if($deduccionesEmpleado[$idEmpleados][$idtipo]>0){
							//$checkEmpleado[ $idEmpleados ]['diaspago'],$diaslaborados,$diasvalor  );
							$this->PrenominaModel->almacenaCalculo(0,$idEmpleados, $_REQUEST['numnomina'], $idtipoconcepto, number_format($deduccionesEmpleado[$idEmpleados][$idtipo],2,'.',''),0,0,$checkEmpleado[ $idEmpleados ]['diaspago'],$diaslaborados,0,1,'',$arrayEmpleados[ $idEmpleados ]['salariobase'], $arrayEmpleados[ $idEmpleados ]['SDI'],0,$diasvacaciones,$diasfestivos,$diaspagopropor );
						}
					
					}
						
				}
				
					
			}
				
			$return.="<td style='height:40px;width:110px; background-color: #46B8DA;color:white;' align='right'>".number_format($percep-$deduc,2,'.',',')."</td>";
			$return.="</tr>";
			unset($deduc);
			unset($percep);
			//se deben almacenar los dos aunq solo este uno en el recibo esto para el acumulado
			//almacena ispt acumulado
			
					$existe = $this->PrenominaModel->VerificaConceptoprevio($_REQUEST['numnomina'], 110, $idEmpleados);
					if($existe){
						//$this->PrenominaModel->lmacenaCalculo($idEmpleados, $_REQUEST['numnomina'], $deduccionesEmpleado [$idEmpleados]['acumula']['idconcepto'], number_format($deduccionesEmpleado [$idEmpleados]['acumula']['valor'],2,'.',''),0,0,$checkEmpleado[ $idEmpleados ]['diaspago'],$checkEmpleado[ $idEmpleados ]['totaldiacheck'],$checkEmpleado[ $idEmpleados ]['diaspago'] ,0  );
						$this->PrenominaModel->almacenaCalculo(0,$idEmpleados, $_REQUEST['numnomina'], 110, abs(number_format($arrayISPT[ $idEmpleados ]['isptretenido'],2,'.','')),0,0,$checkEmpleado[ $idEmpleados ]['diaspago'],0,$checkEmpleado[ $idEmpleados ]['diaspago'] ,0 ,'',$arrayEmpleados[ $idEmpleados ]['salariobase'], $arrayEmpleados[ $idEmpleados ]['SDI'],$arrayISPT[ $idEmpleados ]['ispt'] ,$diasvacaciones,$diasfestivos,$diaspagopropor);
						$this->PrenominaModel->updateValorneto($arrayISPT[ $idEmpleados ]['subsalempleo'],105, $_REQUEST['numnomina'], $idEmpleados);
					}
				//almacena subsidio acumulado
					$existe = $this->PrenominaModel->VerificaConceptoprevio($_REQUEST['numnomina'], 105, $idEmpleados);
					if($existe){
						$this->PrenominaModel->almacenaCalculo(0,$idEmpleados, $_REQUEST['numnomina'], 105, abs(number_format($arrayISPT[ $idEmpleados ]['isptentregado'],2,'.','')),0,0,$checkEmpleado[ $idEmpleados ]['diaspago'],0,$checkEmpleado[ $idEmpleados ]['diaspago'] ,0,'' ,$arrayEmpleados[ $idEmpleados ]['salariobase'], $arrayEmpleados[ $idEmpleados ]['SDI'] ,$arrayISPT[ $idEmpleados ]['subsalempleo'],$diasvacaciones,$diasfestivos,$diaspagopropor );
						$this->PrenominaModel->updateValorneto($arrayISPT[ $idEmpleados ]['ispt'],110, $_REQUEST['numnomina'], $idEmpleados);
						
					}
					unset($existe);
		}
	
	if($_REQUEST['todos']==0){
			
			$conceptos = $this->CatalogosModel->conceptosPrenominaExiste();
			$conceptosarary = array();
			/*El idconcepto esta asignado al idconfprenomina para q busque el id del registro q esta en prenomina
			 * para buscar el id concepto de nomi_conceptos para mostrar el importe en el corresponde acuerdo a la configuracion
			 * */
			while($c = $conceptos->fetch_object()){
				
				$conceptosarary[$c->idtipo][$c->idconfpre] = $c->idconcepto;
			}
			
			$empleadosPrevios = $this->PrenominaModel->empleadosEnNomina($_REQUEST['numnomina']);
			while($e = $empleadosPrevios->fetch_object()){
				$empleadoprevios [$e->idEmpleado][$e->idconfpre]["importe"]= $e->importe;
				$empleadoprevios [$e->idEmpleado][$e->idconfpre]["idconfpre"]=$e->idconfpre;
				$empleadoprevios [$e->idEmpleado]["codigo"]= $e->codigo;
				$empleadoprevios [$e->idEmpleado]["nombre"]= $e->nombre;
				
			}
			foreach($empleadoprevios as $idEmpleado =>$idconfpree){
				$return.= "	
				<tr style='' onMouseDown='adicional(".$idEmpleado.");' id='".$idEmpleado."'>
					<td style='height:40px;width:110px;' align='center'>".$empleadoprevios[$idEmpleado]['codigo']."</td>
					<td style='height:40px;width:110px;' align='center'>".strtoupper($empleadoprevios[$idEmpleado]['nombre'])."</td>";
				
				//$conceptosarary[$c->idtipo][$c->idconfpre]
				$percep = $deduc = 0;
				
			foreach($conceptosarary as $tipo => $concepto ){//print_r($concepto);
					
					if($tipo==1 || $tipo==4){
						
						foreach($conceptosarary[$tipo] as $idtipo ){
								$percep += number_format($empleadoprevios[$idEmpleado][$idtipo]['importe'],2,'.','');
								$return.="<td style='height:40px;width:110px;' align='right' onclick='editar()' >".number_format($empleadoprevios[$idEmpleado][$idtipo]['importe'],2,'.',',')."</td>";
							
						}
					}
					if($tipo==2){
						foreach($conceptosarary[$tipo] as $idtipo ){
							
								$deduc += number_format($idconcep['importe'],2,'.','');	
								$return.="<td style='height:40px;width:110px;' align='right'>".number_format($empleadoprevios[$idEmpleado][$idtipo]['importe'],2,'.',',')."</td>";
							
						}
					}

				
					
			}
				$return.="<td style='height:40px;width:110px; background-color: #46B8DA;color:white;' align='right'>".number_format($percep-$deduc,2,'.',',')."</td>";
					$return.="</tr>";
					unset($deduc);
					unset($percep);
		}
	}
		
			
	
		
		//echo json_encode($arrayISPT);
		
	     echo $return;
		//echo json_encode($infonavitarray)  ;
		//echo json_encode($checkEmpleado)  ;
		
		
		/* 			F I N   C A L C U L O			*/
	}
	function calcular_tiempo_trasnc($horamayor,$horamenor){ 
	    $separar[1]=explode(':',$horamayor); 	
	    $separar[2]=explode(':',$horamenor); 
	
		$total_minutos_trasncurridos[1] = ($separar[1][0]*60)+$separar[1][1]; 
		$total_minutos_trasncurridos[2] = ($separar[2][0]*60)+$separar[2][1]; 
		$total_minutos_trasncurridos = $total_minutos_trasncurridos[1]-$total_minutos_trasncurridos[2]; 
		if($total_minutos_trasncurridos<=59) return($total_minutos_trasncurridos.' Minutos'); 
		elseif($total_minutos_trasncurridos>59){ 
			$horatrascurrida = round($total_minutos_trasncurridos/60); 
		if($horatrascurrida<=9) $horatrascurrida='0'.$horatrascurrida; 
			$minutostrascurridos = $total_minutos_trasncurridos%60; 
		if($minutostrascurridos<=9) $minutostrascurridos='0'.$minutostrascurridos; 
		//se agrega -1 para no incluir la hora de la comida
			return ( ($horatrascurrida-1).':'.$minutostrascurridos.' Horas'); 
		} 
	}



	
	
/* 		A G U I N A L D O 		 */

function veraguinaldo(){
	$tipoperiodo = $this->CatalogosModel->tipoperiodo();
	$listaIncidencias = $this->PrenominaModel->listaIncidencias();
	$percepciones = $this->CatalogosModel->conceptosPorTipo(1);//percepciones para el aguinaldo
	$deducciones = $this->CatalogosModel->conceptosPorTipo(2);
	require("views/prenomina/aguinaldo.php");
}
function calculoAguinaldo(){
	unset($_SESSION['aguinaldo']);
	$periodosepara = explode("/", $_REQUEST['periodo']);
	$idperiodo = $periodosepara[0];
	$empleadoslista = $this->PrenominaModel->empleadosEn1periodo($idperiodo);
	$datosNomina = $this->CatalogosModel->datosNomina($idperiodo);
	$periodoactual = $this->CatalogosModel->periodoactual();
	$incidenciasrequest = 0;
	if($_REQUEST['incidencias']){
		$incidenciasrequest = implode(",", $_REQUEST['incidencias']);
	}
	if($empleadoslista->num_rows>0){
		// $datetime1 = new DateTime($periodoactual->ejercicio."-01-01");
		// $datetime2 = new DateTime(date('Y-m-d'));
		// $interval = $datetime1->diff($datetime2);
		$inicio	= strtotime ( '-1 day' , strtotime ( $periodoactual->ejercicio."-01-01") ) ;
		$inicio = date ( 'Y-m-d' , $inicio );
		$datetime1 = date_create($inicio);
		$datetime2 = date_create(date('Y-m-d'));
		$interval = date_diff($datetime1,$datetime2);
		
		$diaslaboradosp = $interval->format('%a%');
		///ias restantes del ano
		$actual= strtotime ( '-1 day' , strtotime ( date('Y-m-d') ) ) ;
		$actual = date ( 'Y-m-d' , $actual );
		
		$datetime1 = date_create($actual);
		$datetime2 = date_create($periodoactual->ejercicio."-12-31");
		$interval = date_diff($datetime1,$datetime2);
		
		$diasrestantes = $interval->format('%a');
		//dfin dias	
		
		//$diasano = $diaslaboradosp + $diasrestantes;
		$diasano = $this->diasAno($periodoactual->ejercicio);	
		while($empleados = $empleadoslista->fetch_object()){
			$diaslaborados = $diaslaboradosp;
			$numeroincidencias = 0;
			if($_REQUEST['incidencias']){
				$incidenciaEmp = $this->PrenominaModel->incidenciaEmpldelAno($empleados->idEmpleado,$incidenciasrequest);
				$diaslaborados -= $incidenciaEmp->numreg;
				
				$numeroincidencias = $incidenciaEmp->numreg;
			}
			//dias que pasaron desde q entro para descontarlos a los dias q van del ano
			//esto por si entro despues de enero
			if($empleados->fechaActiva > $periodoactual->ejercicio."-01-01"){
				$inicio2	= strtotime ( '-1 day' , strtotime ( $empleados->fechaActiva) ) ;
				$inicio2 = date ( 'Y-m-d' ,$inicio2 );
				$datetime1 = date_create($inicio2);
				$datetime2 = date_create(date('Y-m-d'));
				$interval = date_diff($datetime1,$datetime2);
				
				$diasDesdeEntrada = $interval->format('%a%');
				$diaslaborados -= $diasDesdeEntrada;
			}
			///// fin dias desde q entro
			
			
			// $datetime1 = new DateTime($empleados->fechaActiva);
			// $datetime2 = new DateTime("2017-12-31");
			// $interval = $datetime1->diff($datetime2);
			// $anosantiguedad = $interval->format('%y%');
// 			
			$altae= strtotime ( '-1 day' , strtotime ( $empleados->fechaActiva) ) ;
			$altae = date ( 'Y-m-d' , $altae );
			
			$datetime1 = date_create($altae);
			$datetime2 = date_create($periodoactual->ejercicio."-12-31");
			$interval = date_diff($datetime1,$datetime2);
			$anosantiguedad = $interval->format('%y%');
			
			if($anosantiguedad >= 1){
				$tablaantiguedad = $this->CatalogosModel->antiguedades( $anosantiguedad );
				
				if($empleados->idtipoempleado == 1){//sindicalizado
					$diasaguinaldo = $tablaantiguedad->dias_aguinaldo_si;
				}else{//confianza
					$diasaguinaldo = $tablaantiguedad->dias_aguinaldo_c;
				}
			}
			else{//si no tiene ninguna ano de antiguedad igualo a los 15 dias de ley para sacar la proporcion
				$diasaguinaldo = 15;
			}
			//dias aguinaldo entre dias del ano
			$proporcion = $diasaguinaldo/$diasano;
			//dias proporcion = proporcion por dias laborados
			if ($_REQUEST['diasrestantes'] == 1){
				$diaspropocionaguinaldo = number_format($proporcion * (($diasrestantes-1)+$diaslaborados),2,'.','');
			}else{
				$diaspropocionaguinaldo = number_format($proporcion * ($diaslaborados),'2','.','');
			}
			//total aguinaldo como percepcion art 142
			$proporcionAguinaldoPercepcion = $diaspropocionaguinaldo * $empleados->salario;
			//Exención del aguinaldo
			// $SM = $this->PrenominaModel->SMvigente();
			// $salarioMinimo = $SM->zona_a;
			//EL EXENTO SERA DE 30 UMAS
			$UMA = $this->PrenominaModel->UMAvigente();
			$parteexenta		= $UMA->valor * 30;
			
			
			if( $parteexenta>$proporcionAguinaldoPercepcion){
				$partegravada = 0; 
				$parteexenta = $proporcionAguinaldoPercepcion;
				$isr = 0;
			}else{
				//la parte gravada es la que sacaremos el isr
				//sino existe parte gravada entonces no llevara la deduccion ISR
				$partegravada 	= $proporcionAguinaldoPercepcion - $parteexenta;
				//crear tabla para agregar acumulados para parte exenta y gravda
				
				/*asi estaba antes del art 96 verificar*/
				// $isranual = $this->PrenominaModel->isrAnual($partegravada);
				// $isr =  ( ($partegravada - $isranual->limite_inferior) * ($isranual->porcentaje/100))  + $isranual->cuotafija ;
				/*fin nota lo siguiente es nuevo*/
				
				$ultimosueldomensual = ($empleados->salario * 30.4) ;
				$partegravadaAguinaldo = $ultimosueldomensual + $partegravada;
				
				$isrmensualaguinaldo = $this->PrenominaModel->tablasISR("mensual",$ultimosueldomensual);
				$isrUltimomes =  ( ($ultimosueldomensual - $isrmensualaguinaldo->limite_inferior) * ($isrmensualaguinaldo->porcentaje/100))  + $isrmensualaguinaldo->cuotafija ;
				$subsidioultimomes =  $this->PrenominaModel->tablasSubsidio("mensual", $ultimosueldomensual);
				if(!$subsidioultimomes){
					$subsidioultimomes=0;
				}
				
				if($isrUltimomes < $subsidioultimomes){
					$isrUltimomes = 0;
				}elseif($isrUltimomes > $subsidioultimomes){
					$isrUltimomes -= $subsidioultimomes;
				}
				
				$isrmensualaguinaldo = $this->PrenominaModel->tablasISR( "mensual",$partegravadaAguinaldo);
				$israguinaldo =  ( ($partegravadaAguinaldo - $isrmensualaguinaldo->limite_inferior) * ($isrmensualaguinaldo->porcentaje/100))  + $isrmensualaguinaldo->cuotafija ;
				$subsidioultimomesaguinal =  $this->PrenominaModel->tablasSubsidio("mensual", $partegravadaAguinaldo);
				if(!$subsidioultimomesaguinal){
					$subsidioultimomesaguinal=0;
				}
				if($israguinaldo < $subsidioultimomes){
					$israguinaldo = 0;
				}elseif($israguinaldo > $subsidioultimomes){
					$israguinaldo -= $subsidioultimomes;
				}
				$isr = $israguinaldo - $isrUltimomes;
			
			}
			// esto almacena acumulado
			//$almacenaAcumulado = $this->PrenominaModel->acumuladoAguinaldo($proporcionAguinaldoPercepcion, $parteexenta, $partegravada, $periodoactual->ejercicio);
			
			if($empleados->idtipoempleado == 1){
				$tipoe = "Sindicalizado";
			}else{
				$tipoe = "Confianza";
			}
			$arrayDatos[$empleados->idEmpleado]['id'] = $empleados->idEmpleado;
			$arrayDatos[$empleados->idEmpleado]['salario'] = $empleados->salario;
			$arrayDatos[$empleados->idEmpleado]['sdi'] = $empleados->sbcfija;
			$arrayDatos[$empleados->idEmpleado]['diasaguinaldo'] = $diasaguinaldo;
			$arrayDatos[$empleados->idEmpleado]['diaspropocionaguinaldo'] = number_format($diaspropocionaguinaldo,2,'.','');
			$arrayDatos[$empleados->idEmpleado]['aguinaldo'] = number_format($proporcionAguinaldoPercepcion-$isr,2,'.','');
			$arrayDatos[$empleados->idEmpleado]['tipoperiodo'] = $periodosepara[0];
			$arrayDatos[$empleados->idEmpleado]['numeroincidencias'] = $numeroincidencias;
			$arrayDatos[$empleados->idEmpleado]["partegravada"]= $partegravada;
			$arrayDatos[$empleados->idEmpleado]["parteexenta"]= $parteexenta;
			
			
			
			
			$return.= " <tr><td  align='center'>".$empleados->codigo."</td>";
			$return.= " <td>".strtoupper($empleados->nombreEmpleado." ".$empleados->apellidoPaterno)."</td>";
			$return.= " <td  align='right'>".$empleados->salario."</td>";
			$return.= " <td align='right'>".$diasaguinaldo."</td>";
			$return.= "	<td align='right'>".number_format($diaspropocionaguinaldo,2,'.',',')."</td>";
			$return.= "	<td align='right'>".number_format($proporcionAguinaldoPercepcion,2,'.',',')."</td>";
			$return.= "	<td align='right'>".number_format($isr,2,'.',',')."</td>";
			$return.= "	<td align='right'>".number_format($proporcionAguinaldoPercepcion-$isr,2,'.',',')."</td>";
			
			$arrayDatos[$empleados->idEmpleado]["isr"] = $isr;
			
			
			$return.= "	<td align='right'>".$periodosepara[1]."</td>";
			$return.= "	<td align='right'>".$diaslaborados."</td>";
			$return.= "	<td align='right'>".$numeroincidencias."</td>";
			if ($_REQUEST['diasrestantes'] == 1){
				$return.= "	<td align='right'>".($diasrestantes-1)."</td>";
				$return.= "	<td align='right'>".number_format($diaslaborados+($diasrestantes-1),2,'.','')."</td>";
				$arrayDatos[$empleados->idEmpleado]['diasrestantes'] = $diasrestantes-1;
				$arrayDatos[$empleados->idEmpleado]['totaldias'] = $diaslaborados+($diasrestantes-1);
				
			}else{
				$return.= "	<td align='right'>0</td>";
				$return.= "	<td align='right'>".$diaslaborados."</td>";
				$arrayDatos[$empleados->idEmpleado]['diasrestantes'] = 0;
				$arrayDatos[$empleados->idEmpleado]['totaldias'] = $diaslaborados;
				
			}
			
			// $return.= "	<td align='center'>".$tipoe."</td></tr>";//tipo empleado
				
				
			$sql.= "INSERT INTO nomi_calculo_prenomina (origen, idEmpleado, idnomp, idconfpre, importe, gravado, exento,diaspagados,diaslaborados,valordias,aplicarecibo,fechabaja,fecha_calculo,salario,sdi)
			VALUES (1, ".$empleados->idEmpleado.",0, ".$_REQUEST['percep'].",". number_format($proporcionAguinaldoPercepcion-$isr,2,'.','').",$partegravada,$parteexenta,".number_format($diaspropocionaguinaldo,2,'.','').",$diaslaborados,0,0,'',DATE_SUB(NOW(), INTERVAL 6 HOUR),$empleados->salario,$empleados->sbcfija);";
			$sql.= " INSERT INTO nomi_calculo_prenomina (origen, idEmpleado, idnomp, idconfpre, importe, gravado, exento,diaspagados,diaslaborados,valordias,aplicarecibo,fechabaja,fecha_calculo,salario,sdi)
			VALUES (1, ".$empleados->idEmpleado.",0, ".$_REQUEST['isr'].",". number_format($isr,2,'.','').",0,0,".number_format($diaspropocionaguinaldo,2,'.','').",$diaslaborados,0,0,'',DATE_SUB(NOW(), INTERVAL 6 HOUR),$empleados->salario,$empleados->sbcfija);";
			
			//echo $proporcionAguinaldoPercepcion-$isr;
		}	
		$_SESSION['aguinaldo'] = $arrayDatos;
		echo $return;	
	}else{
		//no empleados
	}
	
}
function acumulaAguinaldo(){
	$periodosepara = explode("/", $_REQUEST['periodo']);
	$idperiodo = $periodosepara[0];
	$configuracion = $this->CatalogosModel->configuracionNominas();
	$acumula = $this->PrenominaModel->acumulaExtraordinarioAguinaldo($idperiodo,$_SESSION['aguinaldo'],$configuracion->aguinaldo,$_REQUEST['isr']);
	if($acumula == 1){
		unset($_SESSION['aguinaldo']);
	}
	echo $acumula;
	
}

/// F I N I Q U I T O 
function verfiniquito(){
	unset($_SESSION['finiquito']);
	$arrayconceptosnomina = $relacionConcepCausas = array();
	$cuasasfiniquito		= $this->PrenominaModel->causasFiniquito();
	$listaempleados		= $this->PrenominaModel->empleadosParaFiniquito();
	$conceptosFiniquito	= $this->PrenominaModel->conceptosFiniquito();
	$listaConceptos		= $this->PrenominaModel->listaConceptos();
	while($list = $listaConceptos->fetch_object()){
		$arrayconceptosnomina[$list->idconcepto]["value"]	= $list->idconcepto;
		$arrayconceptosnomina[$list->idconcepto]["text"]	 	= $list->concepto." ".$list->descripcion;
	}
	$SMvigente = $this->PrenominaModel->SMvigente();
	require("views/prenomina/finiquito.php");
}
function datosEmpleadoFiniquito(){
	
	$empleado = $this->PrenominaModel->datosEmpFiniquito($_REQUEST['idEmpleado']);
	echo $empleado->descripcion."/".$empleado->salario."/".$empleado->fechaActiva."/".$empleado->sbcfija;
	
}
function causasConceptos(){
	
	$relacionConcepCausasFiniquito = $this->PrenominaModel->relacionConcepCausasFiniquito($_REQUEST['idcausa']);
	while($listrelacion = $relacionConcepCausasFiniquito->fetch_object()){
		$return[$listrelacion->idconf]["diastotal"]=$listrelacion->diastotal;
		$return[$listrelacion->idconf]["id"]=$listrelacion->idconf;
	}
	echo json_encode($return);
}
function diasAno($Ano){
	// dias del ano par saber si es bisiesto
	$diasano=365;
   	if( ($Ano%4 == 0 && $Ano%100 != 0) || $Ano%400 == 0 ){
   		$diasano=366;
   	}
	return $diasano;
}
function DatosLaboradosEmpleadoFiniquito(){
	$finiquito = explode("-",$_REQUEST['fechafiniquito']);
	$fechaAlta = $_REQUEST['fechaAlta'];
	$fechaAltaSep = explode('-', $fechaAlta);
	// dias del ano par saber si es bisiesto
	$diasano = $this->diasAno($finiquito[0]);	
	//dias correspondientes
	$fechaparadias = $finiquito[0]."-01-01";
	// se hace esto por si entro despues de enero
	
	
	if($fechaAlta > $fechaparadias){
		$fechaparadias = $fechaAlta;
	}
	
	$inicio	= strtotime ( '-1 day' , strtotime ( $fechaparadias) ) ;
	$inicio = date ( 'Y-m-d' , $inicio );
	$datetime1 = date_create($inicio);
	$datetime2 = date_create($_REQUEST['fechafiniquito']);
	$interval = date_diff($datetime1,$datetime2);
	$diaslaboradosp = $interval->format('%a%');
	////////
	
	
	//antiguedad
			$altae= strtotime ( '-1 day' , strtotime ( $fechaAlta) ) ;
			$altae = date ( 'Y-m-d' , $altae );
			$datetime1 = date_create($altae);
			$datetime2 = date_create($_REQUEST['fechafiniquito']);
			$interval = date_diff($datetime1,$datetime2);
			$anosantiguedad = $interval->format('%y%');
			//de la fecha q cumplio anos en adelnate esos dias q proporcion de antiguedad son
			$proporcionantiguedad = 0;
			$aniversario = $finiquito[0].'-'.$fechaAltaSep[1].'-'.$fechaAltaSep[2];
			
			if($aniversario < $_REQUEST['fechafiniquito'] ){// si es menos es q ya fue su aniverdario y se debe calcular proporcion
				
				$altae= strtotime ( '-1 day' , strtotime ( $aniversario) ) ;
				$altae = date ( 'Y-m-d' , $altae );
				$datetime1 = date_create($altae);
				$datetime2 = date_create($_REQUEST['fechafiniquito']);
				$interval = date_diff($datetime1,$datetime2);
				$diaspasados = $interval->format('%a%');
				//proporcion por dias laborados despues de su aniversario
				$proporcionantiguedad = $diaspasados/$diasano;
				
			}else if ($aniversario > $_REQUEST['fechafiniquito'] ){
				$aniversario = ($finiquito[0]-1).'-'.$fechaAltaSep[1].'-'.$fechaAltaSep[2];	
				$altae= strtotime ( '-1 day' , strtotime ( $aniversario) ) ;
				$altae = date ( 'Y-m-d' , $altae );
				$datetime1 = date_create($altae);
				$datetime2 = date_create($_REQUEST['fechafiniquito']);
				$interval = date_diff($datetime1,$datetime2);
				$diaspasados = $interval->format('%a%');
				//proporcion por dias laborados despues de su aniversario
				$proporcionantiguedad = $diaspasados/$diasano;
			}
			
			
	$array["antiguedad"] 		= number_format($proporcionantiguedad+$anosantiguedad,3,'.','');
	$array["laboradosenAno"]		= $diaslaboradosp;
	echo json_encode($array);
	
}
function calculoProporcionFiniquito(){
	$idEmpleado = $_REQUEST['idEmpleado'];
	$fechaFiniquito = explode('-', $_REQUEST['fechabaja']);
	$fechaAlta = $_REQUEST['fechaalta'];
	$Anofiniquito = $fechaFiniquito[0];
	$fechaAltaSep = explode('-', $_REQUEST['fechaalta']);
	$relacionConcepCausasFiniquito = $this->PrenominaModel->relacionConcepCausasFiniquito($_REQUEST['idcausa']);
	$empleados = $this->PrenominaModel->datosEmpFiniquito($_REQUEST['idEmpleado']);
	
	
// 	
	$relacionConcepCausasFiniquito = $this->PrenominaModel->relacionConcepCausasFiniquito($_REQUEST['idcausa']);
	while($listrelacion = $relacionConcepCausasFiniquito->fetch_object()){
		$return[$listrelacion->idconf]["idactivos"]=$listrelacion->idconf;
		
	}	
		/*SUELDO VERIFICAR SI AUN NO SE LE PAGA LA NOMINA*/
		
		
		
		
			/*Aguinaldo */
			
			//dias correspondientes
			$fechaparadias = $Anofiniquito."-01-01";
			// se hace esto por si entro despues de enero
			if($fechaAlta>$Anofiniquito."-01-01"){
				$fechaparadias=$fechaAlta;
			}
			
			$inicio	= strtotime ( '-1 day' , strtotime ( $fechaparadias) ) ;
			$inicio = date ( 'Y-m-d' , $inicio );
			$datetime1 = date_create($inicio);
			$datetime2 = date_create($_REQUEST['fechabaja']);
			$interval = date_diff($datetime1,$datetime2);
			$diaslaboradosp = $interval->format('%a%');
			//
			
			// dias del ano par saber si es bisiesto
			$diasano = $this->diasAno($Anofiniquito);	
			//
			//antiguedad
			// $altae= strtotime ( '-1 day' , strtotime ( $fechaAlta) ) ;
			// $altae = date ( 'Y-m-d' , $altae );
			// $datetime1 = date_create($altae);
			// $datetime2 = date_create($_REQUEST['fechabaja']);
			// $interval = date_diff($datetime1,$datetime2);
			// $anosantiguedad = $interval->format('%y%');
			$anosantiguedad = $_REQUEST['antiguedad'];
			//		
			if($anosantiguedad >= 1){// mayor a 0 porq puede aun no tener el ano y traer una proporcion
				$tablaantiguedad = $this->CatalogosModel->antiguedades( $anosantiguedad );
				
				if($empleados->idtipoempleado == 1){//sindicalizado
					$diasaguinaldo = $tablaantiguedad->dias_aguinaldo_si;
				}else{//confianza
					$diasaguinaldo = $tablaantiguedad->dias_aguinaldo_c;
				}
			}
			else{//si no tiene ninguna ano de antiguedad igualo a los 15 dias de ley para sacar la proporcion
				$diasaguinaldo = 15;
			}
			//dias aguinaldo entre dias del ano
			$proporcion = $diasaguinaldo/$diasano;
			//dias proporcion = proporcion por dias laborados
			
			$diaspropocionaguinaldo = number_format($proporcion * ($diaslaboradosp),3,'.','');
			
			/*BUSCAMOS SI YA LE PAGARON EL AGUINALDO PARA DESCONTARLE LOS DIAS, YA QUE SE LE PAGO ANO COMPLETO*/
			$diasPagadosAguinaldo = $this->PrenominaModel->buscaDescuentoAguinaldoFiniquito($_REQUEST['idEmpleado'], $Anofiniquito);
			/*FIN DE BUSQUEDA DIAS AGUINALDO*/
			if($diasPagadosAguinaldo){
				$diasDeMas = $diasPagadosAguinaldo - $diaspropocionaguinaldo;
				$diaspropocionaguinaldo = $diasDeMas * -1;
			}
			
			//el 4 es el aguinaldo
			$return[4]["id"]=4;
			$return[4]["diastotal"]=$diaspropocionaguinaldo;
			
		/* fin AGUINALDO		*/
		
		
		
		
		/* se calculo los dias despues de su aniversario para obtener los dias q a trabajado y 
		 * dar el proporcion de vacaciones que debe tomar del nuevo ano q trabajo
		 */
		$aniversario = $Anofiniquito.'-'.$fechaAltaSep[1].'-'.$fechaAltaSep[2];
		$proporcionVac = 0;
		if($aniversario < $_REQUEST['fechabaja'] ){// si es menos es q ya fue su aniverdario y se debe calcular proporcion
			$altae= strtotime ( '-1 day' , strtotime ( $aniversario) ) ;
			$altae = date ( 'Y-m-d' , $altae );
			$datetime1 = date_create($altae);
			$datetime2 = date_create($_REQUEST['fechabaja']);
			$interval = date_diff($datetime1,$datetime2);
			$diaspasadosVac = $interval->format('%a%');
			$tablaantiguedad = $this->CatalogosModel->antiguedades( $anosantiguedad + 1);//sumamos un ano para tomar las vacaciones del prox a las q ya tiene derecho en proporcion
			
			if($empleados->idtipoempleado == 1){//sindicalizado
				$diasVac = $tablaantiguedad->dias_vac_sind;
			}else{//confianza
				$diasVac = $tablaantiguedad->dias_vac_conf;
			}
			$proporcionVac = ($diasVac / $diasano) * $diaspasadosVac;
		}else if($aniversario > $_REQUEST['fechabaja']){
			$antiguedadparavac = $anosantiguedad; //se coloca asi porq deduce que aun no cumple el ano pero si tiene derecho a la proporcion del primer ano
			$aniversariovac = ($Anofiniquito-1).'-'.$fechaAltaSep[1].'-'.$fechaAltaSep[2];	
				$altae= strtotime ( '-1 day' , strtotime ( $aniversariovac) ) ;
				$altae = date ( 'Y-m-d' , $altae );
				$datetime1 = date_create($altae);
				$datetime2 = date_create($_REQUEST['fechabaja']);
				$interval = date_diff($datetime1,$datetime2);
				$diaspasadosVac = $interval->format('%a%'); 
				//proporcion por dias laborados despues de su aniversario
				$tablaantiguedad = $this->CatalogosModel->antiguedades( $antiguedadparavac  );//sumamos un ano para tomar las vacaciones del prox a las q ya tiene derecho en proporcion
			
			if($empleados->idtipoempleado == 1){//sindicalizado
				$diasVac = $tablaantiguedad->dias_vac_sind;
			}else{//confianza
				$diasVac = $tablaantiguedad->dias_vac_conf;
			}
			//aqui no es proporcion extra si no el total, son los dias q estaba acumulando porq aun no cumplia el ano
			$diasRestantes = ($diasVac / $diasano) * $diaspasadosVac;
		}
		////
		/* Vac. pendientes */
		$Vac = $this->PrenominaModel->VacacionesDelAno($idEmpleado,$_REQUEST['fechabaja'],$_REQUEST['fechabaja'],$_REQUEST['fechabaja']);
		$diasVacAdelantado = 0;
		if(!$Vac->diasrestantes){//si aun no cumple el ano y ya tomo dias
			if($Vac->diastomados){
				$diasVacAdelantado = $Vac->diastomados;
			}
			//$diasRestantes = 0;
		}else{
			$diasRestantes = $Vac->diasrestantes;
		}
		//vac a tiempo
		$return[2]["id"]=2;
		$return[2]["diastotal"] = 0;
		//echo "diasrestantes-".$diasRestantes."---adelantado-".$diasVacAdelantado."-----proporcio".$proporcionVac;
		//vac a pendientes
		$vacacionesApagar = ($diasRestantes - $diasVacAdelantado) + $proporcionVac ;
		$return[3]["id"]=3;
		$return[3]["diastotal"] = number_format( $vacacionesApagar  ,3);
		
		///
		//PRIMA VAC EN DIAS
		$montovac 		= ($vacacionesApagar) * $_REQUEST['sueldo'];
		$primaVac 		= $montovac * 0.25;
		$primaVacDias 	= $primaVac / $_REQUEST['sueldo'];//se divide entre el sueldo para sacar la proporcion en dias
		$return[5]["id"]=5;
		$return[5]["diastotal"] = number_format($primaVacDias,3);
		//
		
		/* 20 dias por ano
		 * consiste en el importe de 20 dias de salario por cada ano trabajado
		 */
		if($anosantiguedad>=1){
			$Antiguedad20 = intval($anosantiguedad) * 20; 
		}else{
			$Antiguedad20  = 0;
		}
		$return[7]["id"]=7;
		$return[7]["diastotal"] = $Antiguedad20;
		
		/*Prima de antiguedad
		 * consiste en el importe de 12 dias de salario por cada ano trabajado
		 */
		if($anosantiguedad>=1){ 
			$primaAntiguedad = intval($anosantiguedad) * 12; 
		}else{
			$primaAntiguedad  = 0;
		}
		$return[8]["id"]=8;
		$return[8]["diastotal"] = $primaAntiguedad;
		
		//isr
		$return[10]["id"]=10;
		
		//
		
		
	echo json_encode($return);
}
function validaExtraordinario(){
	$val = $this->PrenominaModel->validaExtraordinario($_REQUEST['ejercicio']);
	echo $val;
}
function reciboFiniquito(){
	$nombreempleado = explode(")",$_REQUEST['nombreempleado']);
	$nombreempleado = $nombreempleado[1];
	$org = $this->CatalogosModel->organizacion();
	//$idnomp = $this->CatalogosModel->nominaActiva();
	$conf = $this->CatalogosModel->configuracionNominas();
	$fechaFiniquito = explode('-', $_REQUEST['fechabaja']);
	$Anofiniquito = $fechaFiniquito[0];
	
	$idnomp = $this->CatalogosModel->validaExtraordinario($Anofiniquito);
	$UMA = $this->PrenominaModel->UMAvigente();
	$SMvigente = $this->PrenominaModel->SMvigente();
	
	/* CALCULO DE ISR */
	$liquidacion = $Indemnizacion  = $_20Dias = $PrimaAntiguedad = $gravadoGratificacion = 0;
	foreach($_REQUEST['aplica'] as $key =>$val){
		if($val){
			
/* G R A V A D O   F I N I Q U I T O */
			if($key == 4){//isr aguinaldo
			
				$baseAguinaldo = $_REQUEST['importedias'][$key-1]*$_REQUEST['sueldo'];
				$parteexentaAguinaldo		= $SMvigente->zona_a * 30;
				if($parteexentaAguinaldo < $baseAguinaldo){
					$gravadaAguinaldo = $baseAguinaldo - $parteexentaAguinaldo;
				}else{// sera todo exento si es mayor
					$gravadaAguinaldo = 0;
				}
							
			}
			if($key == 3){//vacaciones gravadas al 100
				$gravadoVacaciones = $_REQUEST['importedias'][$key-1]*$_REQUEST['sueldo'];
			}
			if($key == 5){//
				$primavacacional = $_REQUEST['importedias'][$key-1]*$_REQUEST['sueldo'];
				$exentoprima = $SMvigente->zona_a * 15;
				// si la parte exenta es mayor que la prima entonces toda la prima esta exenta
				if($exentoprima > $primavacacional){
					$gravadoPrima = 0;
				}else{
					$gravadoPrima = $primavacacional - $exentoprima;
				} 
			}
			
			if($key == 9){//gratificacion gravado al 100
				$gravadoGratificacion = $_REQUEST['importedias'][$key-1];
			}
/*FIN G R A V A D O   F I N I Q U I T O */
			
/* G R A V A D O    L I Q U I D A C I O N */ 
			
			
			//bandera para saber si calculamos liquidacion con un concepto de los siguientes existentes se debera calcular
			//todo gravado al 100
			
			if($key == 6){//indemnizacion
			
				$liquidacion = 1;
				$Indemnizacion = $_REQUEST['importedias'][$key-1] * $_REQUEST['sueldo'];
			}
			if($key == 7){//20 diaspor ano
				$liquidacion = 1;
				$_20Dias = $_REQUEST['importedias'][$key-1] * $_REQUEST['sueldo'];
			}
			if($key == 8){//prima antiguedad
				$liquidacion = 1;
			/* queda topado el salario a 2 SM este no cambia a UMA*/
				$topadoPrima = 2 * $SMvigente->zona_a;
				$sueldoParaPrima = $_REQUEST['sueldo'];
				/*Si el sueldo es mayor al tope entonces la prima se ara sobre el topado
				 * no puede ser mas salario del tope*/
				if($_REQUEST['sueldo'] > $topadoPrima){
					$sueldoParaPrima = $topadoPrima;
				}
				$PrimaAntiguedad = $_REQUEST['importedias'][$key-1] * $sueldoParaPrima;
			}
/* FIN G R A V A D O    L I Q U I D A C I O N */ 
			
		}			
	}
	$baseGravadaFiniquito	= $gravadaAguinaldo + $gravadoVacaciones + $gravadoPrima + $gravadoGratificacion;
	$isrvalores				= $this->PrenominaModel->tablasISR("mensual",$baseGravadaFiniquito);
	$isrFiniquito			= ( ($baseGravadaFiniquito - $isrvalores->limite_inferior) * ($isrvalores->porcentaje/100))  + $isrvalores->cuotafija ;
	 
	/* GRAVADO LIQUIDACION */ 
	
	if($liquidacion){//si existe liquidacion realizara el proceso
		
		$baseLiquidacion 	= ($Indemnizacion + $_20Dias + $PrimaAntiguedad);
		//ISR DEL ULTIMO SUELDO MENSUAL ORDINARIO
		$sueldoMensual = $_REQUEST['sueldo'] * 30.4;
		$isrvalores				= $this->PrenominaModel->tablasISR("mensual",$sueldoMensual);
		$isrUltimomes			= ( ($sueldoMensual - $isrvalores->limite_inferior) * ($isrvalores->porcentaje/100))  + $isrvalores->cuotafija ;
		//el valor exento de liquidacion son 90 umas
		$valorExentoLiquidacion = $UMA->valor * 90;
		
		//SI ES MENOS REALIZARA EL PROCESO DE ISR
		$isrliquidacion = 0;
		if($valorExentoLiquidacion < $baseLiquidacion){
			
			$totalGravadoLiquidacion = $baseLiquidacion - $valorExentoLiquidacion;
		
			/* SACAR EL PORCENTAJE
			* $isrUltimomes / $sueldoMensual) * 100  este me da el porcentaje ejem 10%
			* lo divido entre 100 para q me de el pocentaje en num eje 0.1 */
			$porcentajeExentoBasegravada = ( ($isrUltimomes / $sueldoMensual) * 100 ) / 100;
			
			
			/*SI EL PAGO DE LIQUIDACION COMPLETO SIN SEPARAR EXENTO GRAVADO ES INFERIOR AL ULTIMO SUELDO MENSUAL,
			 * LA TARIFA SE CALCULA CON EL ART 96 SI NO SERA APLICADO SOLO EL PORCENTAJE
			 */
			 
			 if( $totalGravadoLiquidacion < $sueldoMensual){
				$isrvalores	= $this->PrenominaModel->tablasISR("mensual",$totalGravadoLiquidacion);
				$isrliquidacion 		= ( ($totalGravadoLiquidacion - $isrvalores->limite_inferior) * ($isrvalores->porcentaje/100))  + $isrvalores->cuotafija ;
			
			 }else{
			 	$isrliquidacion = $totalGravadoLiquidacion * $porcentajeExentoBasegravada;
			 }
			 
		}
	}//if liquidacion
	//pongo el 10 para isr
	//echo $isrliquidacion;
	
	$_REQUEST['aplica'][10] = $isrliquidacion + $isrFiniquito;
	
	
	
	//////	////	//	
	/* FIN CALCULO DE ISR */
	
	
	require("views/prenomina/finiquitorecibo.php");
}
function entregaFiniquito(){
	$_REQUEST['insert'].= " update nomi_empleados set activo=2 where idEmpleado=".$_REQUEST['idEmpleado']."; 
		INSERT INTO nomi_historial_empleado (idEmpleado, tipo, fecha) VALUES (".$_REQUEST['idEmpleado'].", 2, '".$_REQUEST['fechabaja']."');";
	 $insert = $this->CatalogosModel->insertPeriodo($_REQUEST['insert']);
	 echo $insert;
	 if($insert){
	 	$_SESSION['finiquito']= 1;
	 }
	 
	
}
//FIN FINIQUITO	  

/*AUTORIZACION*/
function viewAutorizaNomina(){
	$periodoactual = $this->CatalogosModel->nominaActiva();
	require("views/prenomina/autorizacion.php");
}
function verificaPagoEmpleado(){
	$empleados = $this->PrenominaModel->empleadosDperiodoSincalcular($_REQUEST['fechafin'],$_REQUEST['idnomina']);
	if($empleados == 0){
		//echo 1;
		$estatus = $this->PrenominaModel->empleadoparaNominaAutorizar($_REQUEST['idnomina'], $_REQUEST['fechafin'], $_REQUEST['fechainicio'], $_REQUEST['idtipoperiodo'],$_REQUEST['numnomina']);
		echo $estatus;
	}else{
		echo 2;
	}
}
/*FIN AUTORIZACION*/

/* XML DE NOMINA AUTORIZADA
 * parametros:
 * idnomina
 * */
function xmlNomina(){
	date_default_timezone_set("Mexico/General"); 
	require_once('../SAT/config.php');
	$datosemisor = $this->NominalibreModel->infoFactura();
	 if($datosemisor->num_rows>0){
	 	if($r = $datosemisor->fetch_object()){
	 		$rfc_cliente = $r->rfc;
			$cer_cliente = $pathdc . '/' . $r->cer;
	        $key_cliente = $pathdc . '/' . $r->llave;
	        $pwd_cliente = $r->clave;
	        	$pac = $r->pac; 
	        $datosemisorcp = $r->cp;
			$razonsocial = $r->razon_social;
			$claveregfiscal = $r->clavereg;
	 	}
	 }
	$idnomp = $_REQUEST['idnomp'];
	$configuracionNominas = $this->CatalogosModel->configuracionNominas();
	$empleadosdatos = $this->PrenominaModel->empleadosDeXML($_REQUEST['idnomp']);
	while($empleado = $empleadosdatos->fetch_object()){
		
		$fechaXML = date('Y-m-d\TH:i:s');
		$this->generaPemNomina($rfc_cliente, $key_cliente, $pwd_cliente, $pathdc);
		$fff = date('YmdHis').rand(100,999);
		$cer = $this->generaCertificadoNomina($rfc_cliente,$cer_cliente,$pathdc);
		$noc = $this->generaNoCertificadoNomina($rfc_cliente,$cer_cliente,$pathdc);	 
		$tipocontrato = $this->NominalibreModel->tipocontratoEdicion($empleado->idtipocontrato);
		$PeriodicidadPago = $this->NominalibreModel->PeriodicidadPago($empleado->idtipop);
// 		
		if($e->origen == 0 ){
			$TipoNomina = "O";
		}else{
			$TipoNomina = "E";
			$PeriodicidadPago->clave = 99;
		}
		$totalpercep = $this->PrenominaModel->importePortipoConcepto($idnomp, $empleado->idEmpleado, 1);
		$totaldeducciones = $this->PrenominaModel->importePortipoConcepto($idnomp, $empleado->idEmpleado, 2);
		$totalotrospagos = $this->PrenominaModel->importePortipoConcepto($idnomp, $empleado->idEmpleado, 4);
		
		$valorUnitario = number_format($totalpercep->importe + $totalotrospagos->importe,2,'.','');
		
		$cadOri = "";
		$cadOri.= '3.3';//version
		$cadOri.= "|".$fechaXML;//fecha
		$cadOri.= "|99";//formaDePago
		$cadOri.= "|".$noc;//NoCertificado
		$cadOri.= "|".$valorUnitario;//subTotal
		if($totaldeducciones->importe >0){
			$totaldeducciones->importe = number_format($totaldeducciones->importe,2,'.','');
			$cadOri.= "|".$totaldeducciones->importe;//descuento
		}
		
		$total = $valorUnitario-$totaldeducciones->importe;
		$cadOri.= "|MXN";//Moneda
		$cadOri.= "|".$total;//total
		$cadOri.= "|N";//TipoDeComprobante
		$cadOri.= "|PUE";//MetodoPago
		$cadOri.= "|".$datosemisorcp;//LugarExpedicion	
		$cadOri.= "|".$rfc_cliente;//rfc emisor
		$cadOri.= "|".$razonsocial;//nombre
		
		$cadOri.= "|".$claveregfiscal;//Regimen
		$cadOri.= "|".$empleado->rfc;//rfc receptor
		$cadOri.= "|".$empleado->nombre;//nombre receptor
		$cadOri.= "|P01";//UsoCFDI
		$cadOri.= "|84111505";//ClaveProdServ
		$cadOri.= "|1";//cantidad
		$cadOri.= "|ACT";//clave unidad
		$cadOri.= "|Pago de nómina";//descripcion
		$cadOri.= "|".$valorUnitario;//valorUnitario
		$cadOri.= "|".$valorUnitario;//importe
		//cfdi
		$datosNomina = $this->CatalogosModel->datosNomina($idnomp);
		$cadOri.= '|1.2';/*version*/
 		$cadOri.= '|'.$datosNomina->TipoNomina; //TipoNomina 
		$cadOri.= '|'.$_REQUEST['fechapago'];//FechaPago
		$cadOri.= '|'.$datosNomina->fechainicio;//FechaInicialPago
		$cadOri.= '|'.$datosNomina->fechafin;// FechaFinalPago
		$decimales = explode(".",$empleado->diaspagados);
		if($decimales[1]<=0){
			 $empleado->diaspagados = number_format($empleado->diaspagados,0);
		}
		$cadOri.= '|'.$empleado->diaspagados;//NumDiasPagados
// 		
		if($totalpercep->importe >0){
			$cadOri.= '|'.$totalpercep->importe;//TotalPercepciones
		}
		if($totaldeducciones->importe > 0){
			$cadOri.= '|'.$totaldeducciones->importe;//TotalDeducciones
		}
		if($totalotrospagos->importe >0){
			$cadOri.= '|'.$totalotrospagos->importe;//TotalOtrosPago
		}
		
		$regpatronal = $this->NominalibreModel->registroPatronalEdicion($configuracionNominas->idregistrop);
		$cadOri.= '|'.$regpatronal->registro;//RegistroPatronal
		
		$antiguedad = $this->generaAntiguedad($empleado->fechaActiva,$datosNomina->fechafin);
		if($empleado->curp){
			$cadOri.= '|'.$empleado->curp;//	 Curp
		}
		if($empleado->nss){
			$cadOri.= '|'.$empleado->nss;//	NumSeguridadSocial
		}
		$cadOri.= '|'.$empleado->fechaActiva;//FechaInicioRelLaboral
		$cadOri.= '|'.$antiguedad;//Antigüedad
		$cadOri.= '|'.$tipocontrato->clave;//TipoContrato
		
		$sindicalizado = "No";
		if($empleado->idtipoempleado == "1"){//SI-Sindicalizado
			$sindicalizado = "Sí";
		}
		$cadOri.= '|'.$sindicalizado;//Sindicalizado
		if($empleado->idturno){
			$jornada = $this->NominalibreModel->turnoJornada($empleado->idturno);
			$cadOri.= '|'.$jornada->clave;//TipoJornada
		}
		$regimenContrato = $this->NominalibreModel->regimenContrato($empleado->idregimencontrato);
		$cadOri.= '|'.$regimenContrato->clave;//TipoRegimen(se refiere al regimen de contratacion)
		$cadOri.= '|'.$empleado->codigo;// NumEmpleado
		
		if($empleado->idDep){
			$departamento = $this->NominalibreModel->depaNombre($empleado->idDep);
			$cadOri.= '|'.$departamento->nombre;//Departamento
		}
		if($empleado->idPuesto){
			$puesto = $this->NominalibreModel->puestoNombre($empleado->idPuesto);
			$cadOri.= '|'.$puesto->nombre;//Puesto
			$cadOri.= '|'.$puesto->idclaveriesgopuesto;//RiesgoPuesto
		}
		$cadOri.= '|'.$PeriodicidadPago->clave;//PeriodicidadPago
		
		if($empleado->sbcfija){// SalarioDiarioIntegrado SDI
			$cadOri.= '|'.$empleado->sbcfija;
		}
		$estado = $this->NominalibreModel->estadoClave($empleado->idestado);
		$cadOri.= '|'.$estado->clave;// ClaveEntFed 
		
		$xml = new DomDocument('1.0', 'UTF-8');
		$raiz = $xml->createElement('cfdi:Comprobante');
		$raiz->setAttribute("xmlns:cfdi","http://www.sat.gob.mx/cfd/3");
		$raiz->setAttribute("xmlns:xsi","http://www.w3.org/2001/XMLSchema-instance");
		$raiz->setAttribute("xmlns:nomina12","http://www.sat.gob.mx/nomina12");
		$raiz->setAttribute("xsi:schemaLocation","http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd http://www.sat.gob.mx/nomina12 http://www.sat.gob.mx/sitio_internet/cfd/nomina/nomina12.xsd");
		$raiz->setAttribute("Version","3.3");
		$raiz->setAttribute("Fecha",$fechaXML);
		$raiz->setAttribute("SubTotal",$valorUnitario);//krmn
		if($totaldeducciones->importe >0){
			$raiz->setAttribute("Descuento",$totaldeducciones->importe);
		}
		$raiz->setAttribute("Total",$valorUnitario-$totaldeducciones->importe);
		$raiz->setAttribute("LugarExpedicion",$datosemisorcp);
		$raiz->setAttribute('FormaPago',"99");
		//$raiz->setAttribute('TipoCambio',1); 3.2
		$raiz->setAttribute('Moneda',"MXN");
		$raiz->setAttribute('TipoDeComprobante','N');
		$raiz->setAttribute('MetodoPago',"PUE");
		//fin valores default sat
		
		$raiz->setAttribute('Certificado',$cer);//certificado se genera
		$raiz->setAttribute('NoCertificado',$noc);//certificado fijo
		//EMISOR
		$datosaFacturacion 	= $this->NominalibreModel->datosaFacturacion();
		
		$Emisor = $xml->createElement('cfdi:Emisor');
		$Emisor->setAttribute('Rfc',$datosaFacturacion->rfc);
		$Emisor->setAttribute('Nombre',$datosaFacturacion->razon_social);
		$Emisor->setAttribute('RegimenFiscal',$claveregfiscal);
		$raiz->appendChild( $Emisor );
		// FIN EMISOR
// 		
// 		
		//RECEPTOR
		$Receptor = $xml->createElement('cfdi:Receptor');
		$Receptor->setAttribute('Rfc',$empleado->rfc);
		$Receptor->setAttribute('Nombre',$empleado->nombre);
		
		$Receptor->setAttribute('UsoCFDI',"P01");
		$raiz->appendChild( $Receptor );
		//FIN RECEPTOR
		//CONCEPTOS
		$Conceptos = $xml->createElement('cfdi:Conceptos');
		//default
		$Conceptosdetalle = $xml->createElement('cfdi:Concepto');
		$Conceptosdetalle->setAttribute('Cantidad','1');
		$Conceptosdetalle->setAttribute('ClaveProdServ','84111505');
		$Conceptosdetalle->setAttribute('ClaveUnidad','ACT');
		$Conceptosdetalle->setAttribute('Descripcion','Pago de nómina');
		//fin default
		$Conceptosdetalle->setAttribute('ValorUnitario',$valorUnitario);
		$Conceptosdetalle->setAttribute('Importe',$valorUnitario);
		
		$Conceptos->appendChild( $Conceptosdetalle );
		
		$raiz->appendChild( $Conceptos );
		// cfdi:Complemento
		$Complemento = $xml->createElement('cfdi:Complemento');
		$nomina12 = $xml->createElement('nomina12:Nomina');
		$nomina12->setAttribute('Version',1.2);
		$nomina12->setAttribute('TipoNomina',$TipoNomina);
		$nomina12->setAttribute('FechaPago',$_REQUEST['fechapago']);
		$nomina12->setAttribute('FechaInicialPago',$datosNomina->fechainicio);
		$nomina12->setAttribute('FechaFinalPago',$datosNomina->fechafin);
		$nomina12->setAttribute('NumDiasPagados',$empleado->diaspagados);
		if($totalpercep->importe >0){
			$nomina12->setAttribute('TotalPercepciones',$totalpercep->importe);
		}
		if($totaldeducciones->importe >0){
			$nomina12->setAttribute('TotalDeducciones',$totaldeducciones->importe); //TotalImpuestosRetenidos+TotalOtrasDeducciones
		}
		if($totalotrospagos->importe >0){
			$nomina12->setAttribute('TotalOtrosPagos',$totalotrospagos->importe); //
		}
		
		$nomina12emisor = $xml->createElement('nomina12:Emisor');
		$nomina12emisor->setAttribute('RegistroPatronal',$regpatronal->registro);
		$nomina12->appendChild( $nomina12emisor );
		
		$nomina12receptor = $xml->createElement('nomina12:Receptor');
		$nomina12receptor->setAttribute('Antigüedad',$antiguedad);
		if($empleado->curp){
			$nomina12receptor->setAttribute('Curp',$empleado->curp);
		}
		if($empleado->nss){
			$nomina12receptor->setAttribute('NumSeguridadSocial',$empleado->nss);
		}
		$nomina12receptor->setAttribute('FechaInicioRelLaboral',$empleado->fechaActiva);
		$nomina12receptor->setAttribute('TipoContrato',$tipocontrato->clave);
		$nomina12receptor->setAttribute('Sindicalizado',$sindicalizado);
		if($jornada->clave){
			$nomina12receptor->setAttribute('TipoJornada',$jornada->clave);
		}
		$nomina12receptor->setAttribute('TipoRegimen',$regimenContrato->clave);
		$nomina12receptor->setAttribute('NumEmpleado',$empleado->codigo);
		if($departamento->nombre){
			$nomina12receptor->setAttribute('Departamento',$departamento->nombre);
		}
		if($empleado->idPuesto){
			$puesto = $this->NominalibreModel->puestoNombre($empleado->idPuesto);
			$nomina12receptor->setAttribute('Puesto',$puesto->nombre);//Puesto
			$nomina12receptor->setAttribute('RiesgoPuesto',$puesto->idclaveriesgopuesto);//RiesgoPuesto
		}
		$nomina12receptor->setAttribute('PeriodicidadPago',$PeriodicidadPago->clave);
		if($empleado->sbcfija){// SalarioDiarioIntegrado SDI
			$nomina12receptor->setAttribute('SalarioDiarioIntegrado',$empleado->sbcfija);
		}
		$nomina12receptor->setAttribute('ClaveEntFed',$estado->clave);
		/* SubContratacion  aqui iria esta parte revisar si abarcamos hasta aqui */
		$nomina12->appendChild( $nomina12receptor );
		/* FIN receptor */
// 		
		/*PERCEPCIONES*/
		if($totalpercep->importe > 0){
			
			$Percepciones = $xml->createElement('nomina12:Percepciones');
				$Percepciones->setAttribute('TotalSueldos',$totalpercep->importe);
				$cadOri.= "|".$totalpercep->importe;
			$Percepciones->setAttribute('TotalGravado',$totalpercep->gravado);
				$cadOri.= "|".$totalpercep->gravado;
			$Percepciones->setAttribute('TotalExento',$totalpercep->exento);
				$cadOri.= "|".$totalpercep->exento;
				
			$Percepcioneslista = $this->PrenominaModel->conceptodeXML($idnomp, $empleado->idEmpleado, 1)	;
			 while($pe =  $Percepcioneslista->fetch_object() ){
// 				
				 $this->NominalibreModel->almacenaConceptotimbrado($empleado->idEmpleado,0, 0, 1, $pe->clavesat,$pe->gravado + $pe->exento ,$pe->gravado,$pe->exento);
				$pe->concepto = str_replace("(", "", $pe->concepto);
			 	$pe->concepto = str_replace(")", "", $pe->concepto);

				$Percepcionesdetalle = $xml->createElement('nomina12:Percepcion');
				
					$Percepcionesdetalle->setAttribute('TipoPercepcion',$pe->clavesat);
					$cadOri.= "|".$pe->clavesat;
					$Percepcionesdetalle->setAttribute('Clave',$pe->claveint);
					$cadOri.= "|".$pe->claveint;
					$Percepcionesdetalle->setAttribute('Concepto',$pe->concepto);
					$cadOri.= "|".$pe->concepto;
					$Percepcionesdetalle->setAttribute('ImporteGravado',$pe->gravado);
					$cadOri.= "|".$pe->gravado;
					$Percepcionesdetalle->setAttribute('ImporteExento',$pe->exento);
					$cadOri.= "|".$pe->exento;	
					
				 $Percepciones->appendChild( $Percepcionesdetalle );
			}
			$nomina12->appendChild( $Percepciones );
		}	
		/*FIN PERCEPCIONES*/
// 		
		/*DEDUCCIONES*/
		if($totaldeducciones->importe > 0){
			
			$Deducciones = $xml->createElement('nomina12:Deducciones');
			
			$deducciones = $this->PrenominaModel->conceptodeXML($idnomp, $empleado->idEmpleado, 2)	;
			while($de =  $deducciones->fetch_object() ){
				$this->NominalibreModel->almacenaConceptotimbrado($empleado->idEmpleado,0, 0, 2, $de->clavesat,$de->importe ,0,0);
				$otrasDedu = $impuestosretenidos = 0;
				
				if($de->clavesat == "002"){//002 es isr
					$impuestosretenidos += $de->importe;
				}else{
					$otrasDedu += $de->importe;
				}
				
				$de->concepto = str_replace("(", "", $de->concepto);
				$de->concepto = str_replace(")", "", $de->concepto);
					
				 $Deduccionesdetalle = $xml->createElement('nomina12:Deduccion');
					$Deduccionesdetalle->setAttribute('TipoDeduccion',$de->clavesat);
					$cadOri.= "|".$de->clavesat;
					$Deduccionesdetalle->setAttribute('Clave',$de->claveint);
					$cadOri.= "|".$de->claveint;
					$Deduccionesdetalle->setAttribute('Concepto',$de->concepto);
					$cadOri.= "|".$de->concepto;
					$Deduccionesdetalle->setAttribute('Importe',$de->importe);
					$cadOri.= "|".$de->importe;
				$Deducciones->appendChild( $Deduccionesdetalle );
			}
			
			
			if($otrasDedu >0){
				//$otrasDedu = number_format($otrasDedu,2,'.','');
				$Deducciones->setAttribute('TotalOtrasDeducciones',$otrasDedu);
				$cadOri.= "|".$otrasDedu;
			}
			if($impuestosretenidos>0){
				$Deducciones->setAttribute('TotalImpuestosRetenidos',$impuestosretenidos);
				$cadOri.= "|".$impuestosretenidos;
			}
			$nomina12->appendChild( $Deducciones );
			
		}//fin dedu
		/*FIND EDUCCIONES*/
		
		/*OTROS PAGOS*/
		if($totalotrospagos->importe > 0){
			
			$OtrosPagos = $xml->createElement('nomina12:OtrosPagos');
			$otrospagoslist = $this->PrenominaModel->conceptodeXML($idnomp, $empleado->idEmpleado, 4);
			while($otr =  $otrospagoslist->fetch_object() ){
				$this->NominalibreModel->almacenaConceptotimbrado($empleado->idEmpleado,0, 0, 4, $otr->clavesat,$otr->importe ,0,0);
				
				$otr->concepto = str_replace("(", "", $otr->concepto );
				$otr->concepto  = str_replace(")", "", $otr->concepto );
				
				$OtrosPagosDetalle = $xml->createElement('nomina12:OtroPago');
				$OtrosPagosDetalle->setAttribute('TipoOtroPago',$otr->clavesat );
				$cadOri.= "|".$otr->clavesat;
				$OtrosPagosDetalle->setAttribute('Clave',$otr->claveint );
				$cadOri.= "|".$otr->claveint;
				$OtrosPagosDetalle->setAttribute('Concepto',$otr->concepto);
				$cadOri.= "|".$otr->concepto;
				$OtrosPagosDetalle->setAttribute('Importe',$otr->importe);
				$cadOri.= "|".$otr->importe;
				if($otr->clavesat == "002"){ 
						$OtrosPagosubsidio = $xml->createElement('nomina12:SubsidioAlEmpleo');
						$OtrosPagosubsidio->setAttribute('SubsidioCausado',$otr->valorneto);
						$cadOri.= "|".$otr->valorneto;//SubsidioCausado
						$OtrosPagosDetalle->appendChild( $OtrosPagosubsidio );
				}
				$OtrosPagos->appendChild( $OtrosPagosDetalle );
			}
			$nomina12->appendChild( $OtrosPagos );
		}
		/*FIN OTROS PAGOS*/
		
		
		$Complemento->appendChild( $nomina12 );
		$raiz->appendChild( $Complemento );
		// fin cfdi:Complemento
		
		
		///
		$cadOri= preg_replace('/&quot;/', '"', $cadOri);
		$cadOri= preg_replace('/&apos;/', "'", $cadOri);
		$cadOri= preg_replace('/&amp;/', '&',$cadOri);
		
		$cadOri=preg_replace('/\|{2,}/', '|',$cadOri);
		$cadOri=preg_replace('/ {2,}/', ' ',$cadOri);
		$cadOri=('||'.$cadOri.'||');
		

		$ori = $this->generaCadenaOriginalNomina($cadOri,$fff,$rfc_cliente,$pathdc);
		
		$sel = $this->generaSelloNomina($rfc_cliente,$fff,$pathdc);
	
		$raiz->setAttribute('Sello',$sel);
		$xml->appendChild( $raiz );
	
		$el_xml = $xml->saveXML();
		$XML = $el_xml;
		$xml->save('../cont/xmls/facturas/temporales/_'.$empleado->nombre.'.xml');
		$nominas=1;
		require_once('../../modulos/wsinvoice/sealInvoice.php');
		
		if($arrInvoice['success'] == 1){
			
			$xmlfile='_'.$empleado->nombre.'_'.$arrInvoice['datos']['UUID'].'.xml';
	        $archivo = rename('../../modulos/cont/xmls/facturas/temporales/'.$arrInvoice['datos']['UUID'].'.xml','../../modulos/cont/xmls/facturas/temporales/'.$xmlfile);
			$almacenatimbre = $this->NominalibreModel->almacenaTimbrado( $empleado->idEmpleado, $datosNomina->fechainicio, $datosNomina->fechafin, $_REQUEST['fechapago'], $empleado->diaspagados, $TipoNomina, $idnomp=0, $valorUnitario, $totaldeducciones->importe, $total, 1, $arrInvoice['datos']['selloSAT'], $arrInvoice['datos']['selloCFD'], $arrInvoice['datos'][FechaTimbrado] , $arrInvoice['datos']['UUID'], $xmlfile, 0,$claveregfiscal,$PeriodicidadPago->clave);
			
			
			if($almacenatimbre>0){
				$this->NominalibreModel->updateConceptosTimbrados($almacenatimbre,1);
				$msjFinal .= $empleado->nombre." Factura generada\n";
				/*acumulaTimbreConcepto
				 * en los conceptos de calculo prenomina se cambia estado timbrado=1 
				 * para ya no volver a timbrar este recibo
				 * Y se le agrega el id de nominas timbradas que tiene la informacion del cfdi
				*/
				$acumular = $this->PrenominaModel->acumulaTimbreConcepto($idnomp, $empleado->idEmpleado, $almacenatimbre);
				
			}//window.location = 'index.php?c=Nominalibre&f=viewNomina';
			
			//exit();
		
			
		}else{
			$this->NominalibreModel->updateConceptosTimbrados(0,0);
			$msjrespuesta = str_replace("'","", $arrInvoice['mensaje']);
			$msjFinal .= $empleado->nombre." Factura No generada, Msj:".$msjrespuesta."\n";
			//echo "<script> alert('".$msjFinal."\\nINTENTE DE NUEVO!'); ";
		}
	}//while empleados
	
echo "<script> alert('$msjFinal');  </script>";	

}
/* FIN PROCESO XML*/

function xmlview(){

	$listaDeNominasSinTimbrar = $this->PrenominaModel->nominasParaTimbrar();
	require("views/prenomina/xmlnominas.php");
	
}




}

?>