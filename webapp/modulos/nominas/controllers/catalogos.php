<?php
require('common.php');
require("models/catalogos.php");

class Catalogos extends Common
{
	public $CatalogosModel;
	
	function __construct()
	{
		
		$this->CatalogosModel = new CatalogosModel();
		$this->CatalogosModel->connect();
	}

	function __destruct()
	{
		
		$this->CatalogosModel->close();
	}
	
	// function rfcEmpleados(){
	// 	$rfcEmp=$this->CatalogosModel->rfcEmpleados();
	// 	$number = 1;

	// 	while ($e = $rfcEmp->fetch_object()){ 
	// 		 echo $number.'|'.$e->rfc.'|'.(chr(13).chr(10));
	// 		 $number = $number+1;

	// 	 }
	// }
	function listapercepdeduc(){
		$lista = $this->CatalogosModel->percepdedu($_REQUEST['t']);
		while ($t = $lista->fetch_object()){
			echo "<option value='".$t->idAgrupador."'>".$t->clave." ".$t->descripcion."</option>";
		}
	}
	function clasefactor(){
		$lista = $this->CatalogosModel->clasefactor($_REQUEST['fac']);
		while ($t = $lista->fetch_object()){
			echo "<option value='".$t->idfraccion."'>".$t->fraccion." ".$t->descripcion."</option>";
		}
	}
	function listaEmpleados(){
		$empleados = $this->CatalogosModel->listaEmpleados();
	  
	    $Nominas			= $this->CatalogosModel->validaNominas();
	    $valiperioact=='';
	    if ($Nominas==1) {
	     	$valiperioact = $this->CatalogosModel->	valiperioact();
	     } 
	    
		require("views/catalogos/listaempleados.php");
	} 	
	
	function empleadoview(){
		$Nominas			= $this->CatalogosModel->validaNominas();
		$NominasManual  = $this->CatalogosModel->validaNominasManual();
		//if( $NominasManual == 1 ){ $Nominas=1; }
		$formapago  	    = $this->CatalogosModel->formapago();
		$estadocivil 	    = $this->CatalogosModel->estadocivil();
		$bancos			    = $this->CatalogosModel->bancos();
		$estados		    = $this->CatalogosModel->estados();
		$entidadfed		    = $this->CatalogosModel->estados();


		if ($Nominas==1 || $NominasManual==1) {
			$registroPatronal	= $this->CatalogosModel->registroPatronal();
			$tipoperiodo		= $this->CatalogosModel->tipoperiodo();
			$basecotizacion		= $this->CatalogosModel->basecotizacion();
			$departamento		= $this->CatalogosModel->departamento();
			$puesto				= $this->CatalogosModel->puesto();
			$tipoEmpleado		= $this->CatalogosModel->tipoEmpleado();
			$basePago			= $this->CatalogosModel->basePago();
			$turno				= $this->CatalogosModel->turno();
			$regimenContratacion		= $this->CatalogosModel->regimenContratacion();
		$tipocontrato			= $this->CatalogosModel->tipocontrato();	# code...
	}


	$listaClas 				= $this->CatalogosModel->listaClas2(3);
	$areaempleadoapp			= $this->CatalogosModel->areaempleadoapp();
	$Appministra				= $this->CatalogosModel->validaAppministra();

	if($_REQUEST['editar']){
		$datos = $this->CatalogosModel->editarEmpleado($_REQUEST['editar']);
		$huella = 0;
		if ($Nominas==1 || $NominasManual==1) {
			$huella = $this->CatalogosModel->huellaEmpleado($_REQUEST['editar']);
		}
		
	}
	require("views/catalogos/empleados.php");
}
function municipios(){
	$municipios = $this->CatalogosModel->municipios($_REQUEST['idestado']);
	while($e = $municipios->fetch_object()){
		echo "<option value='".$e->idmunicipio."'>".$e->municipio."</option>";
	}
}
function almacenaEmpleado(){

	if($_REQUEST['opc']==1){ $funcion = "almacenaEmpleado";}else{ $funcion = "updateEmpleado";}
	$sql = $this->CatalogosModel->$funcion(
		
		$img = ((isset($_FILES["imagen"])) ? base64_encode(file_get_contents($_FILES['imagen']['tmp_name'])) : null),
		$_REQUEST['codigo'],
		$_REQUEST['fechaalta'],
		$_REQUEST['paterno'],
		$_REQUEST['materno'],
		$_REQUEST['nombre'],
		$_REQUEST['salario'],
		$_REQUEST['zona'],
		$_REQUEST['pago'],
		$_REQUEST['correo'],
		$_REQUEST['nss'],
		$_REQUEST['civil'],
		$_REQUEST['sexo'],
		$_REQUEST['nacimiento'],
		$_REQUEST['entidad'],
		$_REQUEST['ciudad'],
		$_REQUEST['rfc'],
		$_REQUEST['curp'],
		$_REQUEST['direccion'],
		$_REQUEST['estado'],
		$_REQUEST['poblacion'],
		round($_REQUEST['cp'],2),
		$_REQUEST['tel'],
		$_REQUEST['banco'],
		$_REQUEST['numcuenta'],
		$_REQUEST['interbancaria'],
		$_REQUEST['tipocomisionapp'],
		round($_REQUEST['comisionapp'],2),
		$_REQUEST['clasificacionapp'],
		$_REQUEST['areaempleadoapp'],
		$_REQUEST['contrato'],
		$_REQUEST['periodo'],
		$_REQUEST['cotizacion'],
		round($_REQUEST['sbcfija'],2),
		round($_REQUEST['sbcvariable'],2),
		round($_REQUEST['sbctopado'],2),
		$_REQUEST['departamento'],
		$_REQUEST['puesto'],
		$_REQUEST['tipoempleado'],
		round($_REQUEST['basepago'],2),
		$_REQUEST['turnotrabajo'],
		$_REQUEST['regimen'],
		round($_REQUEST['fonacot'],0),
		$_REQUEST['afore'],
		$_REQUEST['registropatronal'],
		round($_REQUEST['umf'],0),
		$avisos,
		round($_REQUEST['h1'],2),
		round($_REQUEST['h2'],2),
		round($_REQUEST['h3'],2),
		round($_REQUEST['dtrabajados'],2),
		round($_REQUEST['dpagados'],2),
		round($_REQUEST['dcotizados'],2),
		round($_REQUEST['ausencias'],2),
		round($_REQUEST['incapacidades'],2),
		round($_REQUEST['vacaciones'],2),
		round($_REQUEST['septimos'],2),
		round($_REQUEST['svariable'],2),
		$_REQUEST['fechavariable'],
		$_REQUEST['fechadiario'],
		round($_REQUEST['salariopromedio'],2),
		$_REQUEST['fechapromedio'],
		$_REQUEST['fechaintegrado'],
		round($_REQUEST['salarioliquidacion'],2),
		round($_REQUEST['salarioajusteneto'],2),
		$_REQUEST['alimento'],
		$_REQUEST['tipocuenta'],
		round($_REQUEST['idempleado'],0),
		$_REQUEST['fechahistorial'],
		$_REQUEST['SalarioHistorico'],
		$_REQUEST['sueldoneto']

		
		);

	if($sql==1){
		$msj = "Empleado Almacenado";
	}else{
		$msj = "Error al almacenar intente de nuevo";
	}
	echo  $msj;
}

function accionEmpleado(){ 
	$concepto = $this->CatalogosModel->accionEmpleado($_REQUEST['idempleado'],$_REQUEST['accion'],$_REQUEST['fecha']);
		//echo $concepto;
	if($concepto==1){	
		$msj = "Almacenado";
	}else{
		$msj = "Error al almacenar intente de nuevo";
	}
	echo  $msj;


}

// conceptos


function listaConceptos(){
	$listaConceptos = $this->CatalogosModel->listaConceptos();
	require("views/catalogos/listaconceptos.php");
}
function conceptos(){
	$horasext = $this->CatalogosModel->horasext();
	$formapago = $this->CatalogosModel->formapago();
	$tipoconcepto = $this->CatalogosModel->tipoconcepto();
	if($_REQUEST['editar']){
		$datos    = $this->CatalogosModel->editarConcepto($_REQUEST['editar']);
		$consulte = $this->CatalogosModel->consulte($_REQUEST['editar']);		
	}
	require("views/catalogos/conceptos.php");
}	
function almacenaConcepto(){
	if( isset($_REQUEST['idhora']) ){
		$idhora = implode(",", $_REQUEST['idhora']);
	}else{
		$idhora=0;
	}
	if( isset($_REQUEST['idFormapago']) ){
		$idFormapago = implode(",", $_REQUEST['idFormapago']);
	}else{
		$idFormapago=0;
	}
	if($_REQUEST['opc']==1){
		$funcion = "almacenaConcepto";
	}
	else{
		$funcion = "updateConcepto";
	}

	if(!$_REQUEST['global']){
		$_REQUEST['global'] = 0;
	}
	if(!$_REQUEST['liquidacion']){
		$_REQUEST['liquidacion'] = 0;
	}
	if(!$_REQUEST['especie']){
		$_REQUEST['especie'] = 0;
	}


	$concepto = $this->CatalogosModel->$funcion($_REQUEST['codigo'], $_REQUEST['descripcion'], $_REQUEST['global'], $_REQUEST['liquidacion'], $_REQUEST['especie'], $_REQUEST['idAgrupador'], $_REQUEST['idtipo'],$idhora, $idFormapago,$_REQUEST['idconcepto'] );
	if($concepto==1){
		$msj = "Concepto Almacenado";
	}else if ($concepto==2) {
		$msj="Concepto Almacenado.";
	}else{
		$msj = "Error al almacenar intente de nuevo";
	} 
	echo  "<script> alert('$msj'); 
	window.location = 'index.php?c=Catalogos&f=listaConceptos';</script>";
}



function accionEliminarConcepto(){ 
	echo $concepto = $this->CatalogosModel->accionEliminarConcepto($_REQUEST['idconcepto']);

}

/* C O N F I G U R A C I O N    N O M I N A S */


function configuracion(){
	$percepciones = array();
	$regimenfiscal = $this->CatalogosModel->regimenFiscal();
	$registroPatronal = $this->CatalogosModel->registroPatronal();
	$datos = $this->CatalogosModel->configuracionNominas();
	$percep = $this->CatalogosModel->percepcionesConfiguracion();
	$zona = $this->CatalogosModel->zona();
	$tipPeriodos=$this->CatalogosModel->tipoperiodo();
	$org = $this->CatalogosModel->organizacion();
	While ($result = $percep->fetch_array()){ 
		$percepciones[] = $result; 
	} 
	require("views/configuracion.php");
}
function almacenaConfiguracion(){
	if(!$_REQUEST['factor']){ $_REQUEST['factor'] = 0;}
	if(!$_REQUEST['infonavit']){ $_REQUEST['infonavit'] = 0;}
	if(!$_REQUEST['fonacot']){ $_REQUEST['fonacot'] = 0;}
	if(!$_REQUEST['mixta']){ $_REQUEST['mixta'] = "";}
	if(!$_REQUEST['anteriores']){ $_REQUEST['anteriores'] = 0;}
	if(!$_REQUEST['futuros']){ $_REQUEST['futuros'] = 0;}
	if(!$_REQUEST['sellos']){ $_REQUEST['sellos'] = 0;}
	if(!$_REQUEST['ss']){ $_REQUEST['ss'] = 0;}
	if(!$_REQUEST['curp']){ $_REQUEST['curp'] = '';}
	if(!$_REQUEST['idtipop']){ $_REQUEST['idtipop'] = 0;}
	if(!$_REQUEST['representa']){ $_REQUEST['representa'] = "";}

		//if(!$_REQUEST['idtipop']){ $_REQUEST['idtipop'] = "";}
		//if(!$_REQUEST['regcomisionmixta']){ $_REQUEST['regcomisionmixta'] = 0;}
		//if (!$_REQUEST['fechainicio']){ $_REQUEST['fechainicio'] = }
			# code...

	$conf = $this->CatalogosModel->actualizaConfiguracion(1, $_REQUEST['idregfiscal'], $_REQUEST['factor'], $_REQUEST['patronal'], $_REQUEST['infonavit'], $_REQUEST['fonacot'], $_REQUEST['ss'], $_REQUEST['mixta'],  $_REQUEST['futuros'], $_REQUEST['ptu'], $_REQUEST['aguinaldo'], $_REQUEST['prima'], $_REQUEST['vacaciones'], $_REQUEST['calculoinvertido'], $_REQUEST['zona'], $_REQUEST['sellos'], $_REQUEST['fecha'], $_REQUEST['curp'],$_REQUEST['idtipop']
		,$_REQUEST['representa']);
	if($conf == 1){
		echo "<script>
		alert('Informacion almacenada');
		window.location='index.php?c=Catalogos&f=configuracion';
	</script>";
}else{
	echo "<script>
	alert('Error de almacenamiento');
	window.location='index.php?c=Catalogos&f=configuracion';
</script>";
}
}

/* C O N F I G U R A C I O N    P R E N O M I N A */

function configPrenomina(){

	$percepciones	= $this->CatalogosModel->conceptosprenomina(1);
	$deduciones     = $this->CatalogosModel->conceptosprenomina(2);
	$obligaciones	= $this->CatalogosModel->conceptosprenomina(3);
	$otrosp			= $this->CatalogosModel->conceptosprenomina(4);
	$existente 		= $this->CatalogosModel->conceptosPrenominaExiste();
	require("views/configprenomina.php");
}
function eliminaPrevios(){
	$elimina = $this->CatalogosModel->eliminapreviosprenomina(0);
	if($_REQUEST['omision']){
		$elimina = $this->CatalogosModel->eliminapreviosprenomina(1);
	}
	if( $elimina == 1){
		echo 1;
	}else{
		echo 0;
	}
}
function almacenaConceptosPrenomina(){

	$idtipo = $this->CatalogosModel->idtipo($_REQUEST['idconcepto']);
	$define = $this->CatalogosModel->almacenaConceptosPrenomina($idtipo, $_REQUEST['idconcepto'], $_REQUEST['valor'], $_REQUEST['importe'], $_REQUEST['omision']);
	echo $define;
	
}
function conceptosPrenominaDefault(){
	$d = $this->CatalogosModel->conceptosPrenominaDefault();
	if($d->num_rows>0){
		while ($p = $d->fetch_object()){ 
			if($p->idtipo == 1){ $color = "background:#6E6E6E;color: #F0F0F0"; }
			if($p->idtipo == 2){ $color = " background: #A4A4A4"; }
			if($p->idtipo == 3){ $color = "  background: #D8D8D8;"; }
			if($p->valor == 1){ $checkva = "checked"; $val1 = 1; }else{ $checkva = ""; $val1 = 0; }
			if($p->importe == 1){ $checkimp = "checked"; $val2 = 1; }else{ $checkimp = ""; $val2 = 0;}
			echo '
			
			<li id="lista'.$p->idconcepto.'" value="'.$p->idconcepto.'" class="out"  ondragstart="dragStart(event)" ondrag="dragging(event)" draggable="true"" style="'.$color.'">
				'.$p->concepto.' - '.$p->descripcion.' 
				( <input type="checkbox" value="'.$val1.'" onclick="valorconf('.$p->idconcepto.')"  title="Valor" id="valor'.$p->idconcepto.'" '.$checkva.'/>Valor
				<input type="checkbox" value="'.$val2.'" onclick="importeconf('.$p->idconcepto.')"  title="Importe" id="importe'.$p->idconcepto.'" '.$checkimp.'/>Importe )
			</li>';
		} 
	}else{
		echo 0;
	}
}

/* P E R I O D O S */
function insertPeriodoProceso($diasperiodo,$idtipop,$diaspago,$fechainicioPeriodo,$extraordinario){
	date_default_timezone_set('America/Mexico_City');
	$configuracion = $this->CatalogosModel->configuracionNominas();
	$fechainicio = explode('-',$configuracion->fechainicio);
	
	/*para periodo quincenal se hara por meses
			 * porque en este no se puede unir dos meses en un periodo
			 * osea q la segunda quincena debe quedar con el resto de dias ya sean mas de 15 o menos de 15
			 * en el mes
			 */

	//$eliminaprevios = $this ->CatalogosModel->eliminaNominasdelperiodo($idtipop);


	if($extraordinario == 0 ){
		if($diasperiodo == 15){
			$fecha = $fechainicio[0]."-01-01";
			$numnomia = 1;
			
			//if( $eliminaprevios==1){
			for ($peri = 1; $peri <= 12; $peri++) {
				$menos = 0;
					$dias1 = 14;//siempre seran 14 dias para tomar la quincena porque ya estara en dia 1 mas 14 son los 15
					$menos += 1;	//sumamos siempre un dia porq asi iniciaria el periodo en 1 o 16 y se debe contar
					if($peri != 1 ){
						$nuevafecha = strtotime ( '+1 day' , strtotime ( $fecha ) ) ;
						$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
						$fecha = $nuevafecha;
					}
					
					/* definimos los inicios de bimestre */
					$iniciobimentre = 0;
					if($peri == 1 || $peri == 3 || $peri == 5 || $peri == 7 || $peri == 9 || $peri == 11){
						$iniciobimentre = 1;
					} 
					/* definimos los fin de bimestre */
					$finbimentre = 0;
					if($peri == 2 || $peri == 4 || $peri == 6 || $peri == 8 || $peri == 10 || $peri == 12){
						$finbimentre = 1;
					} 
					/* definimos los inicio ejercicio */
					$inicioejercicio = 0;
					if($peri == 1){
						$inicioejercicio = 1;
					}
					/* definimos los fin ejercicio */
					$finejercicio = 0;
					if($peri == 12){
						$finejercicio = 1;
					}

					//$array[$peri][] = $fecha;
					$nuevafecha = strtotime ( '+'.$dias1.' day' , strtotime ( $fecha ) ) ;
					$menos += $dias1;
					$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
					$fecha2 = $nuevafecha;
					//$array[$peri][] = $fecha;
					
					//$sql = $this->CatalogosModel->insertPeriodo($idtipop, $numnomia, $fecha, $fecha2, $fechainicio[0], $peri, $diaspago, 1, $iniciobimentre, $inicioejercicio, 0, 0, 0);
					$sql .="INSERT INTO nomi_nominasperiodo ( idtipop, numnomina, fechainicio, fechafin, ejercicio, mes, diaspago, iniciomes, iniciobimentreimss, inicioejercicio, finmes, finbimentreimss, finejercicio)
					VALUES
					( $idtipop, $numnomia, '$fecha', '$fecha2', $fechainicio[0], $peri, $diaspago, 1, $iniciobimentre,$inicioejercicio, 0, 0, 0);
					";
					
			// 			
					$nuevafecha = strtotime ( '+1 day' , strtotime ( $fecha2 ) ) ;
					$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
					$menos += 1;
					$fecha3 = $nuevafecha;
					//$array[$peri][] = $fecha;
					
					//sacamos todos los dias del mes para saber de cuanto sera la segunda quincena
					$numero = cal_days_in_month(CAL_GREGORIAN,$peri, $fechainicio[0]) ;
					//y restamos a esos dias la primera quincena
					$numero -= $menos ;
					$nuevafecha = strtotime ( '+'.$numero.' day' , strtotime ( $fecha3 ) ) ;
					$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
					$fecha = $nuevafecha;
					//$array[$peri][] = $fecha;
					$numnomia++;
					//$sql = $this->CatalogosModel->insertPeriodo($idtipop, $numnomia, $fecha3, $fecha, $fechainicio[0], $peri, $diaspago, 0, 0, 0, 1, $finbimentre, $finejercicio);
					$sql .="INSERT INTO nomi_nominasperiodo ( idtipop, numnomina, fechainicio, fechafin, ejercicio, mes, diaspago, iniciomes, iniciobimentreimss, inicioejercicio, finmes, finbimentreimss, finejercicio)
					VALUES
					( $idtipop, $numnomia, '$fecha3', '$fecha', $fechainicio[0], $peri, $diaspago, 0, 0,0, 1, $finbimentre, $finejercicio);
					";
			// 			
					$numnomia++;
				}

			//}

			}else{
			/* para los periodos diferentes de 15 dias se debera tomar en cuenta la fecha de inicio 
					 * descontando a los 365 dias del ano los dias transcurridos 
					 * de ser asi del periodo y estos seran divididos entre el total de dias
					 * del periodo para sacar el numero de nominas que tendra 
					 * el ejercicio
					 * SE HARA SOLO PARA SEMANAL POR QUE KERLAB A SI LO UTILIZA QUINCENAL Y SEMANAL
					 */

			$array = array();		
			$datetime1 = new DateTime($configuracion->fechainicio);
			$datetime2 = new DateTime($fechainicioPeriodo);
			$interval  = $datetime1->diff($datetime2);
			$diasano   = 365 - $interval->format('%R%a');
					//sacamos el total de nominas del ano
			$totalnomina = $diasano/$diasperiodo;
			$totalnominabimestral = 365/$diasperiodo;
					//dividimos el total de nominas por los dias trabajados 
					//para saber cuantas nominas iran en un mes
			$nominaenunmes =  number_format($totalnomina/12,0);
			$nominaenunmesbimestral =  number_format($totalnominabimestral/12,0);

					/* para los periodos que no sean quincena el sistema debe evaluar
					 * los periodos dentro del mes a corde a los dias de periodo
					 * y de estos sacar toda la nomina del ejercicio
					 * 
					 */
					$fecha = $fechainicioPeriodo;
					$iniciobimentre = 0;
					$fechaorigen = explode('-', $fecha);
					$fechacomparacion = $fechacomparacionbimestre =1;
					$nominaenunbimestre = $nominaenunmesbimestral*2;
					if($fechaorigen[1] == 2 || $fechaorigen[1] == 4 || $fechaorigen[1] == 6 || $fechaorigen[1] == 8 || $fechaorigen[1] == 10 || $fechaorigen[1] == 12 ){
						$fechacomparacionbimestre = $nominaenunbimestre;
					}
					
					//$eliminaprevios = $this ->CatalogosModel->eliminaNominasdelperiodo($idtipop);
					//if( $eliminaprevios == 1){
					for ($peri = 1; $peri <= number_format($totalnomina); $peri++) {
						
						$inicioejercicio = 0;
						if($peri == 1 ){
							$inicioejercicio = 1;
							$iniciomes = 1;
							$iniciobimentre = 1;
							$finmes = 0;
							$finejercicio = 0;
							$finbimentre = 0;
						}


						if($peri != 1 ){
							$nuevafecha = strtotime ( '+1 day' , strtotime ( $fecha ) ) ;
							$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
							$fecha = $nuevafecha;
						}

						$array[$peri][]=$fecha;
						$dias = $diasperiodo - 1;
						$nuevafecha = strtotime ( '+'.$dias.' day' , strtotime ( $fecha ) ) ;
						$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
						$fecha = $nuevafecha;
						$s = explode('-', $nuevafecha);

						$array[$peri][] = $fecha;
						$array[$peri]['iniciomes'] = $iniciomes;
						$array[$peri]['finmes'] = $finmes;
						$array[$peri]['iniciobime'] = $iniciobimentre;
						$array[$peri]['finbime'] = $finbimentre;
						$array[$peri]['inicioejericicio'] = $inicioejercicio;
						$array[$peri]['finejercicio'] = $finejercicio;
						if( $fechacomparacion == $nominaenunmes ){
							$array[$peri]['finmes'] = 1;
							$iniciomes = 1;
							$fechacomparacion = 1;
							$finmes = 0;

						}else{
							$iniciomes = 0;
							$fechacomparacion++;
						}
						if( $fechacomparacionbimestre == $nominaenunbimestre ){
							$array[$peri]['finbime'] = 1;
							$finbimentre = 0;
							$fechacomparacionbimestre = $iniciobimentre = 1;
						}else{
							$iniciobimentre = 0;
							$fechacomparacionbimestre++;
						}
						if(number_format($totalnomina) == $peri){
							$array[$peri]['finejercicio'] = 1;
						}
							// $sql = $this->CatalogosModel->insertPeriodo($idtipop, $peri, $array[$peri][0], $array[$peri][1], $fechainicio[0], $s[1], $diaspago, $array[$peri]['iniciomes'], $array[$peri]['iniciobime'], $array[$peri]['inicioejericicio'], $array[$peri]['finmes'], $array[$peri]['finbime'], $array[$peri]['finejercicio'] );
						$sql .="INSERT INTO nomi_nominasperiodo ( idtipop, numnomina, fechainicio, fechafin, ejercicio, mes, diaspago, iniciomes, iniciobimentreimss, inicioejercicio, finmes, finbimentreimss, finejercicio)
						VALUES
						( $idtipop, $peri, '".$array[$peri][0]."', '".$array[$peri][1]."', $fechainicio[0], $s[1], $diaspago, ".$array[$peri]['iniciomes'].", ".$array[$peri]['iniciobime'].",".$array[$peri]['inicioejericicio'].", ".$array[$peri]['finmes'].", ".$array[$peri]['finbime'].", ".$array[$peri]['finejercicio'].");
						";	
					}
					//}
					
				}
				$envio = $this->CatalogosModel->insertPeriodo($sql);
			}else{

				$ejercicioext = explode('-', $fechainicioPeriodo);
				$sql = "INSERT INTO nomi_nominasperiodo ( idtipop, numnomina, fechainicio, fechafin, ejercicio, mes, diaspago, iniciomes, iniciobimentreimss, inicioejercicio, finmes, finbimentreimss, finejercicio)
				VALUES
				( $idtipop, 1, '$fechainicioPeriodo', '".$ejercicioext[0].'-12-31'."',". $ejercicioext[0].", 0, 0, 0, 0,0, 0,0, 0);
				";	
				$envio = $this->CatalogosModel->insertSQL($sql);
			}

			if($envio==1){
				return 1;
			}else{
				return 0;
			}
		}


		function SDI(){
		// $datetime1 = new DateTime($_REQUEST['fechaalta']);
		// $datetime2 = new DateTime($dateactual);
		// $interval = $datetime1->diff($datetime2);
		// $anosantiguedad = $interval->format('%y%');
// 		
			$alta = strtotime ( '-1 day' , strtotime (  $_REQUEST['fechaalta']) ) ;
			$alta = date ( 'Y-m-d' , $alta );
			
			$datetime1 = date_create($alta);
			$datetime2 = date_create(date('Y-m-d'));
			$interval = date_diff($datetime1,$datetime2);
			
			$anosantiguedad = $interval->format('%y');
			$tablaantiguedad = $this->CatalogosModel->antiguedades( $anosantiguedad );

	/*Proporcion por aguinaldo:
	* 	dias a que se tiene derecho por ley 
	* 	entre dias del año
	* 	igual a proporcion de aguinaldo
	*/

		//dias a que se tiene derecho por ley  aguinaldo
		if($_REQUEST['tipoempleado'] == 1){//sindicalizado
			$aguinaldo = $tablaantiguedad->dias_aguinaldo_si;
		}else{//confianza
			$aguinaldo = $tablaantiguedad->dias_aguinaldo_c;
		}
		//esto lo agrege porq si no no hay propocion
		// y por ley deben ser 15
		if(!$aguinaldo){ $aguinaldo = 15; }
		$proporcionaguinaldo = ($aguinaldo/365);
		
		/* 	/	/	/	/	/	/	/	/	/	/	/	/	/	//	/	/	*/	
		
	/* Proporcion por vacaciones:
		dias a que se tiene derecho por ley
		entre dias del año
		igual a proporcion por prima de vacaciones */
		
		if($_REQUEST['tipoempleado'] == 1){//sindicalizado
			$vacaciones = $tablaantiguedad->dias_vac_sind;
		}else{//confianza
			$vacaciones = $tablaantiguedad->dias_vac_conf;
		}
		//esto lo agrege porq si no no hay propocion
		// y por ley deben ser 6
		if(!$vacaciones){ $vacaciones = 6; }
		$proporcionvaca = ( $vacaciones/365 );

		
		/* 	/	/	/	/	/	/	/	/	/	/	/	/	/	/	/	/	/	*/		
	/* Proporcion por Prima Vacacional:
	 	porcentaje sobre los dias de vacaciones
	 	a los que se tiene derecho por ley
	 	igual proporcion por prima vacacional */
	 	if($_REQUEST['tipoempleado'] == 1){//sindicalizado
	 		$prima = $tablaantiguedad->porc_prima_sind;
		}else{//confianza
			$prima = $tablaantiguedad->porc_prima_conf;
		}
		//esto lo agrege porq si no no hay propocion
		// y por ley deben ser 25
		if(!$prima){ $prima = 25; }
		$proporcioprima =  ( $proporcionvaca * ($prima/100));
		
		/*	/Determinar el factor integracionn total 	/	/	/	/	/	/	/	/	/	/	/*/
	//proporcion por aguinaldo + proporcion por prima vacacional + la unidad(1)->es uno siempre uno a si lo marca la formula de la ley
		$factorintegracion =  ( $proporcionaguinaldo + $proporcioprima + 1);


	 //*	/	/	/	SDI	/	/	/	/	/	/		/	/*/
		$sdi = number_format($_REQUEST['salariodiario'] * $factorintegracion , 4);
		//echo $proporcionvaca."->".$tablaantiguedad->dias_aguinaldo_si."->".$proporcionaguinaldo."->" .$proporcionvaca."->".$proporcioprima."->".$factorintegracion;
		echo $sdi;
	}
	
	/* tipos de periodos */
	function accionTipop(){
		echo $this->CatalogosModel->accionTipop($_REQUEST['idtipo'],$_REQUEST['accion']);
	}
	function listaTiposperiodos(){
		$tipoperiodo = $this->CatalogosModel->tipoperiodocatalogo();
		require("views/catalogos/listatipoperiodo.php");
	}
	function tipoPeriodoview(){

		$periodicidad = $this->CatalogosModel->periodicidad();
		$ajusteDias = $this->CatalogosModel->ajusteDias();
		if($_REQUEST['editar']){
			$datos = $this->CatalogosModel->editarTipoperidoo($_REQUEST['editar']);
		}

		$fechaInicioPeriodo = $this->CatalogosModel->validarfechaperiodos();

		require("views/catalogos/tipoperiodo.php");
	}
	
	function almacenaTipop(){			
		
		if($_REQUEST['opc']==1){ $funcion = "almacenaTipoPeriodo";}else{ $funcion = "updateTipoPeriodo";}
		$idsql = $this->CatalogosModel->$funcion(
			$_REQUEST['fechainicio'],
			$_REQUEST['nombre'],
			$_REQUEST['diasperiodo'],
			round($_REQUEST['diaspago'],0),
			round($_REQUEST['periodotrabajo'],0),
			$_REQUEST['ajustemes'],
			round($_REQUEST['septimodia'],0),
			$_REQUEST['idajuste'],
			round($_REQUEST['diapago'],0),
			round($_REQUEST['idperiodicidad'],0),
			round($_REQUEST['idtipop'],0),
			$_REQUEST['extrahidden']
			);
		$ejercicio = explode('-', $_REQUEST['fechainicio']);
		if($idsql>0){
			if($_REQUEST['idtipop']){
				$idsql = $_REQUEST['idtipop'];
			}
			if( $_REQUEST['diasperiodofijo'] != $_REQUEST['diasperiodo'] ){


				$eliminaprevios = $this ->CatalogosModel->eliminaNominasdelperiodo($idsql);
				if($eliminaprevios == 1){

							$si =  $this->insertPeriodoProceso($_REQUEST['diasperiodo'],$idsql,$_REQUEST['diaspago'],$_REQUEST['fechainicio'],$_REQUEST['extrahidden']);//1 $extraordinario
							if($si == 1){
								$msj = "Tipo Periodo Almacenado";
							}else{
								$msj = "Error al almacenar las nominas, por favor genere de nuevo su periodo";
							}
							
						}elseif($eliminaprevios == 0){
							$msj = "Error en el proceso, por favor genere de nuevo su periodo";
							
						}elseif($eliminaprevios == 2){
							$msj = "El periodo ya tiene nominas autorizadas, no puede ser cambiado!";
						}
					}else{
						$msj = "Tipo Periodo Almacenado";
					}

				}else if($idsql == 0){
					$msj = "Error al almacenar intente de nuevo";
				}else if($idsql == -1){
					$msj = "Ya existe un periodo extraordinario del ejercicio ".$ejercicio[0];
				}
				echo  "<script> alert('$msj'); 
				window.location = 'index.php?c=Catalogos&f=listaTiposperiodos';</script>";
			}

			/* 	P	E	R	I	O	D	O	S	 */

			function periodosview(){	
				$periodos = $this->CatalogosModel->tipoperiodo();

				require("views/catalogos/periodos.php");
			}

			function listadoNominasxPeriodo(){
				$lista = $this->CatalogosModel->listadoNominasxPeriodo($_REQUEST['idtipop']);
				if( $lista->num_rows>0 ){
					while( $p = $lista->fetch_object() ){
						echo 	"<tr onclick='alimentaDatos($p->idnomp)' style='cursor: pointer' title='Click para editar'>
						<td align='center'> $p->numnomina </td>
						<td align='center'> $p->fechainicio <i id='i$p->idnomp' style='display:none' class='fa fa-refresh fa-spin fa-2x fa-fw'></i></td>
						<td align='center'> $p->fechafin </td></tr>";
					}
				}
			}
			function editaPeriodo(){
				$datos =  $this->CatalogosModel->datosNomina($_REQUEST['idNomina']);
				$idtipop = $this->CatalogosModel->editarTipoperidoo($datos->idtipop);

				echo $datos->idnomp."->".$datos->numnomina."->".$datos->fechainicio."->".$datos->fechafin."->".$datos->diaspago."->".
				$datos->iniciomes."->".$datos->iniciobimentreimss."->".$datos->inicioejercicio."->".$datos->finmes."->".$datos->finbimentreimss."->".
				$datos->finejercicio."->".$idtipop->diasperiodo;

			}
			function almacenaedicion(){
				echo $almacena = $this->CatalogosModel->updatePeriodo($_REQUEST['idnomina'], $_REQUEST['numero'], $_REQUEST['fechainicio'], $_REQUEST['fechafin'], $_REQUEST['diaspago'],round( $_REQUEST['inimes'],0), round($_REQUEST['inibimestre'],0),round( $_REQUEST['iniejer'],0), round($_REQUEST['finmes'],0), round($_REQUEST['finbimestre'],0), round($_REQUEST['finejer'],0));
				if($almacena == 1){
					return 1;
			// $msj = "Cambio Realizado";

				}else if($almacena==2){
					return 2;
			//$msj = "Existen registros";

				}else{

					return 0;
			//$msj = "No se pudo cambiar intente de nuevo :(";
				}
				echo $msj;
			}

			function periodoactual(){

				$periodoactual = $this->CatalogosModel->periodoactual();
				$fechaseleccionada = $_REQUEST["fecha"];
				$fechainicio = $periodoactual->fechainicio;	
				$fechafin = $periodoactual->fechafin;	

				if($fechaseleccionada >= $fechainicio && $fechaseleccionada <=$fechafin) 

					echo "true";
				else
					echo "false";
		// 	echo "true"; 
		// else echo "false"; 


			}
	function eliminaHuella(){
		echo $this->CatalogosModel->eliminaHuella($_REQUEST['idempleado']);
	}

}


?>