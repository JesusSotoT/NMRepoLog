<?php
	require('controllers/catalogos.php');
	require("models/nominalibre.php");

class Nominalibre extends Catalogos
{
	public $NominalibreModel;
	public $CatalogosModel;
	
	
	function __construct()
	{
		
		$this->NominalibreModel = new NominalibreModel();
		$this->CatalogosModel = $this->NominalibreModel;
		$this->NominalibreModel->connect();
	}

	function __destruct()
	{
		
		$this->NominalibreModel->close();
	}
	function viewNomina(){
		$percepciones 	= $this->CatalogosModel->percepdedu(1);
		$deducciones		= $this->CatalogosModel->percepdedu(2);
		$otros 			= $this->CatalogosModel->otrosPagos();
		$tipoHoras 		= $this->NominalibreModel->tipoHoras();
		$registroPatronal	= $this->CatalogosModel->registroPatronal();
		$regimenfiscal 		= $this->CatalogosModel->regimenFiscal();
		$datosaFacturacion 	= $this->NominalibreModel->datosaFacturacion();
		$org 				= $this->CatalogosModel->organizacion();
		//$confNomina 			= $this->CatalogosModel->configuracionNominas();
		$incapacidades 		= $this->NominalibreModel->incapacidadeslista();
		$tiponomina 			= $this->NominalibreModel->tiponomina();
		$periodicidadpagoLibre = $this->NominalibreModel->periodicidadpagoLibre();
		//$_REQUEST['idnomina']=15;
		if($_REQUEST['idnomina']){
			$datos 			= $this->NominalibreModel->datosNominas($_REQUEST['idnomina']);
			$datosTimbrada	= $this->NominalibreModel->datosNominaTimbrada($_REQUEST['idnomina']);
		}
		require("views/nominalibre/nominalibre.php");
	}
	function empleadosNomina(){
		$empleados = $this->CatalogosModel->empleadosperiodoNominalibre($_REQUEST['fecha']);
		if($empleados!=0){
			while($e = $empleados->fetch_object()){
				echo "<option value=".$e->idEmpleado."> (".$e->codigo.")". strtoupper($e->nombreEmpleado . " ". $e->apellidoPaterno)."</option>";
			}			
		}else{
			echo "<option>No tiene ningun empleado que entre en este periodo</option>";
		}	
	}
	function creaNominaXML(){
		date_default_timezone_set("Mexico/General"); $fechaXML = date('Y-m-d\TH:i:s');
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
		 	}
		 }
		//Para estos 2 atributos se debe registrar la suma de los atributos TotalPercepciones + TotalOtrosPagos.
		$valorUnitario = number_format($_REQUEST['totalpercepciones'] + $_REQUEST['totalotrospagos'],2,'.','');
		
		$this->generaPemNomina($rfc_cliente, $key_cliente, $pwd_cliente, $pathdc);
		$fff = date('YmdHis').rand(100,999);
		$cer = $this->generaCertificadoNomina($rfc_cliente,$cer_cliente,$pathdc);
		$noc = $this->generaNoCertificadoNomina($rfc_cliente,$cer_cliente,$pathdc);	 
		
		$empleado = $this->CatalogosModel->editarEmpleado($_REQUEST['empleado']);
		$tipocontrato = $this->NominalibreModel->tipocontratoEdicion($empleado->idtipocontrato);
		
		/* Cuando el tipo de nómina sea ordinaria el tipo de periodicidad de pago debe ser
			distinta de la clave 99 (Otra Periodiciad) y si el tipo de nómina es extraordinaria debe
			ser 99 (Otra Periodicidad).
		 */
		
		// if($tipocontrato->clave != 99 ){
			// $TipoNomina = "O";
		// }else{
			// $TipoNomina = "E";
		// }
		
		//CUANDO SEA A NIVEL EMPELADOS DE PERIODO SINO LIBRE PARA ESCOGER 
		//$PeriodicidadPago = $this->NominalibreModel->PeriodicidadPago($empleado->idtipop);
		$PeriodicidadPago->clave = $_REQUEST['periodicidad'];
		$TipoNomina = $_REQUEST['tiponomina'];
		if($TipoNomina == "E"){
			$PeriodicidadPago->clave = 99;
		}
		
		
		/* agrege q fuera mejor seleccionada por el user para el caso de extraordinaria si no
		 * tendria q cambiar a cada user primero*/
		
		
		$cadOri = "";
		/* formando cadena original
		 * a. Version
b. Serie
c. Folio
d. Fecha
e. FormaPago
f. NoCertificado
g. CondicionesDePago
h. Subtotal
i. Descuento
j. Moneda
l. Total
m. TipoDeComprobante
n. MetodoPago
o. LugarExpedicion
p.  */
		// parte CFDI
		$cadOri.= '3.3';//version
		$cadOri.= "|".$fechaXML;//fecha
		//$cadOri.= "|".$sel;//sello
		$cadOri.= "|99";//formaDePago
		$cadOri.= "|".$noc;//NoCertificado
		//$cadOri.= "|egreso";//tipoDeComprobante
		$cadOri.= "|".$valorUnitario;//subTotal
		if($_REQUEST['totaldeducciones']>0){
			$_REQUEST['totaldeducciones'] = number_format($_REQUEST['totaldeducciones'],2,'.','');
			$cadOri.= "|".$_REQUEST['totaldeducciones'];//descuento
		}else{
			$_REQUEST['totaldeducciones'] = 0;
		}//
		$total = $valorUnitario-$_REQUEST['totaldeducciones'];
		$cadOri.= "|MXN";//Moneda
		//$cadOri.= "|1";//TipoCambio
		$cadOri.= "|".$total;//total
		$cadOri.= "|N";//TipoDeComprobante
		//$cadOri.= "|NA";//metodoDePago	 3.2
		$cadOri.= "|PUE";//MetodoPago
		$cadOri.= "|".$datosemisorcp;//LugarExpedicion	
		$cadOri.= "|".$rfc_cliente;//rfc emisor
		$cadOri.= "|".$razonsocial;//nombre
		$cadOri.= "|".$_REQUEST['idregfiscal'];//Regimen
		$cadOri.= "|".$empleado->rfc;//rfc receptor
		$cadOri.= "|".$empleado->nombreEmpleado." ".$empleado->apellidoPaterno." ".$empleado->apellidoMaterno;//nombre receptor
		//3.3
		$cadOri.= "|P01";//UsoCFDI
		$cadOri.= "|84111505";//ClaveProdServ
		$cadOri.= "|1";//cantidad
		$cadOri.= "|ACT";//clave unidad
		$cadOri.= "|Pago de nómina";//descripcion
		$cadOri.= "|".$valorUnitario;//valorUnitario
		$cadOri.= "|".$valorUnitario;//importe
		//fin  CFDI
		
 		$cadOri.= '|1.2';/*version*/
 		$cadOri.= '|'.$TipoNomina; //TipoNomina 
		$cadOri.= '|'.$_REQUEST['fpago'];//FechaPago
		$cadOri.= '|'.$_REQUEST['finicio'];//FechaInicialPago
		$cadOri.= '|'.$_REQUEST['fin'];// FechaFinalPago
		$decimales = explode(".",$_REQUEST['dpago']);
		if($decimales[1]<=0){
			 $_REQUEST['dpago'] = number_format($_REQUEST['dpago'],0);
		}
		$cadOri.= '|'.$_REQUEST['dpago'];//NumDiasPagados
		if($_REQUEST['totalpercepciones']>0){
			$cadOri.= '|'.$_REQUEST['totalpercepciones'];//TotalPercepciones
		}
		if($_REQUEST['totaldeducciones']>0){
			$cadOri.= '|'.$_REQUEST['totaldeducciones'];//TotalDeducciones
		}
		if($_REQUEST['totalotrospagos']>0){
			$cadOri.= '|'.$_REQUEST['totalotrospagos'];//TotalOtrosPago
		}
	
		//Información del Nodo Emisor
		//$cadOri.= '|'.$_REQUEST['curporg'];//Curp
		$regpatronal = $this->NominalibreModel->registroPatronalEdicion($_REQUEST['idregistrop']);
		$cadOri.= '|'.$regpatronal->registro;//RegistroPatronal
		//$cadOri.= '|'.$_REQUEST["rfc"];//RfcPatronOrigen
		
		//	//	//	//	//	//	//	//	//
		
		//Información del Nodo Receptor (empleado)
		
		$antiguedad = $this->generaAntiguedad($empleado->fecha_altabajareingreso,$_REQUEST['fin']);
		if($empleado->curp){
			$cadOri.= '|'.$empleado->curp;//	 Curp
		}
		if($empleado->nss){
			$cadOri.= '|'.$empleado->nss;//	NumSeguridadSocial
		}
		$cadOri.= '|'.$empleado->fecha_altabajareingreso;//FechaInicioRelLaboral
		$cadOri.= '|'.$antiguedad;//Antigüedad
		$cadOri.= '|'.$tipocontrato->clave;//TipoContrato
		//SI-Sindicalizado,NO- confianza
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
		//$PeriodicidadPago = $this->NominalibreModel->PeriodicidadPago($empleado->idtipop);
		$cadOri.= '|'.$PeriodicidadPago->clave;//PeriodicidadPago
		
		/*
		 * Si el valor de este campo contiene una cuenta CLABE (18 posiciones), no debe existir
		el campo Banco, este dato será objeto de validación por el SAT o el proveedor de
		certificación de CFDI, se debe confirmar que el dígito de control es correcto.
		* Si el valor de este campo contiene una cuenta de tarjeta de débito (16 posiciones) o
		una cuenta bancaria (11 posiciones) o un número de teléfono celular (10 posiciones)
		debe de existir siempre el campo Banco.
		 */
		//LA CUENTA Y BANCO SON OPC ME AHORRO ESTE PASO SI DESPUES LO PIDEN AGREGO VER CONDICIONES 
		// $longitudclave = strlen($empleado->claveinterbancaria);
		// if($longitudclave == 18){
			// $cadOri.= '|'.$empleado->claveinterbancaria;
		// }else{
// 			
			// $bancoClave = $this->NominalibreModel->bancoClave($empleado->idbanco);
			// $cadOri.= '|'.$bancoClave->Clave;// Banco
// 		
		// //num cuenta obligatorio si no tiene numcuenta poner claveinterbancaria
			// if($empleado->numeroCuenta){//CuentaBancaria
				// $cadOri.= '|'.$empleado->numeroCuenta;
			// }
			// else{
				// $cadOri.= '|'.$empleado->claveinterbancaria;
			// }
		// }
		
		
//  SalarioBaseCotApor es opcional qued pendiente ver si es ncesario agregarlo

		if($empleado->sbcfija){// SalarioDiarioIntegrado SDI
			$cadOri.= '|'.$empleado->sbcfija;
		}
		$estado = $this->NominalibreModel->estadoClave($empleado->idestado);
		$cadOri.= '|'.$estado->clave;// ClaveEntFed 
	
	/*cadena original aqui seguiria 
	 * Se debe incluir información por cada instancia del punto 5
		5. Información del Nodo nomina12:SubContratacion
	 */
	
	
	
	//F I N   Información del Nodo nomina12:Receptor (empleado)
	
		$xml = new DomDocument('1.0', 'UTF-8');
		$raiz = $xml->createElement('cfdi:Comprobante');
		$raiz->setAttribute("xmlns:cfdi","http://www.sat.gob.mx/cfd/3");
		$raiz->setAttribute("xmlns:xsi","http://www.w3.org/2001/XMLSchema-instance");
		$raiz->setAttribute("xmlns:nomina12","http://www.sat.gob.mx/nomina12");
		$raiz->setAttribute("xsi:schemaLocation","http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd http://www.sat.gob.mx/nomina12 http://www.sat.gob.mx/sitio_internet/cfd/nomina/nomina12.xsd");
		$raiz->setAttribute("Version","3.3");
		$raiz->setAttribute("Fecha",$fechaXML);
		$raiz->setAttribute("SubTotal",$valorUnitario);//krmn
		if($_REQUEST['totaldeducciones']>0){
			$raiz->setAttribute("Descuento",$_REQUEST['totaldeducciones']);
		}
		$raiz->setAttribute("Total",$valorUnitario-$_REQUEST['totaldeducciones']);
		$raiz->setAttribute("LugarExpedicion",$datosemisorcp);
		
		//valores default acorde al sat
		$raiz->setAttribute('FormaPago',"99");
		//$raiz->setAttribute('TipoCambio',1); 3.2
		$raiz->setAttribute('Moneda',"MXN");
		$raiz->setAttribute('TipoDeComprobante','N');
		$raiz->setAttribute('MetodoPago',"PUE");
		//fin valores default sat
		

		$raiz->setAttribute('Certificado',$cer);//certificado se genera
		$raiz->setAttribute('NoCertificado',$noc);//certificado fijo
		//EMISOR
		$Emisor = $xml->createElement('cfdi:Emisor');
		$Emisor->setAttribute('Rfc',$_REQUEST["rfc"]);
		$Emisor->setAttribute('Nombre',$_REQUEST['razon_social']);
		
		// 3.2
		//$regimenemisor = $xml->createElement('cfdi:RegimenFiscal');
		// $regimenemisor->setAttribute('Regimen',$_REQUEST['idregfiscal']);
		// $Emisor->appendChild($regimenemisor);
		//fin 3.2
		$Emisor->setAttribute('RegimenFiscal',$_REQUEST["idregfiscal"]);
		$raiz->appendChild( $Emisor );
		// FIN EMISOR
		
		//RECEPTOR
		$Receptor = $xml->createElement('cfdi:Receptor');
		$Receptor->setAttribute('Rfc',$empleado->rfc);
		$Receptor->setAttribute('Nombre',$empleado->nombreEmpleado." ".$empleado->apellidoPaterno." ".$empleado->apellidoMaterno);
		
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
		//////////
		
		$raiz->appendChild( $Conceptos );
		
		//FIN CONCEPTOS
		
		// este es default asi lo pide sat 3.2
		//$Impuestos = $xml->createElement('cfdi:Impuestos');
		//$raiz->appendChild( $Impuestos );
		//////
		
		// cfdi:Complemento
		$Complemento = $xml->createElement('cfdi:Complemento');
		$nomina12 = $xml->createElement('nomina12:Nomina');
		$nomina12->setAttribute('Version',1.2);
		$nomina12->setAttribute('TipoNomina',$TipoNomina);
		$nomina12->setAttribute('FechaPago',$_REQUEST['fpago']);
		$nomina12->setAttribute('FechaInicialPago',$_REQUEST['finicio']);
		$nomina12->setAttribute('FechaFinalPago',$_REQUEST['fin']);
		$nomina12->setAttribute('NumDiasPagados',$_REQUEST['dpago']);
		if($_REQUEST['totalpercepciones']>0){
			$nomina12->setAttribute('TotalPercepciones',$_REQUEST['totalpercepciones']);
		}
		if($_REQUEST['totaldeducciones']>0){
			$nomina12->setAttribute('TotalDeducciones',$_REQUEST['totaldeducciones']); //TotalImpuestosRetenidos+TotalOtrasDeducciones
		}
		if($_REQUEST['totalotrospagos']>0){
			$nomina12->setAttribute('TotalOtrosPagos',$_REQUEST['totalotrospagos']); //
		}

		
		$nomina12emisor = $xml->createElement('nomina12:Emisor');
		$nomina12emisor->setAttribute('RegistroPatronal',$regpatronal->registro);
		$nomina12->appendChild( $nomina12emisor );
		/* receptor */
		$nomina12receptor = $xml->createElement('nomina12:Receptor');
		$nomina12receptor->setAttribute('Antigüedad',$antiguedad);
		if($empleado->curp){
		$nomina12receptor->setAttribute('Curp',$empleado->curp);
		}
		if($empleado->nss){
			$nomina12receptor->setAttribute('NumSeguridadSocial',$empleado->nss);
		}
		$nomina12receptor->setAttribute('FechaInicioRelLaboral',$empleado->fecha_altabajareingreso);
		
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
		
		//LA CUENTA Y BANCO SON OPC ME AHORRO ESTE PASO SI DESPUES LO PIDEN AGREGO VER CONDICIONES
		// if($longitudclave == 18){
			// $nomina12receptor->setAttribute('CuentaBancaria',$empleado->claveinterbancaria);
// 			
		// }else{
		// //num cuenta obligatorio si no tiene numcuenta poner claveinterbancaria
			// $nomina12receptor->setAttribute('Banco',$bancoClave->Clave);
			// if($empleado->numeroCuenta){//CuentaBancaria
				// $nomina12receptor->setAttribute('CuentaBancaria',$empleado->numeroCuenta);
			// }else{
				// $nomina12receptor->setAttribute('CuentaBancaria',$empleado->claveinterbancaria);
			// }
// 		
		// }
		
		if($empleado->sbcfija){// SalarioDiarioIntegrado SDI
			$nomina12receptor->setAttribute('SalarioDiarioIntegrado',$empleado->sbcfija);
		}
		$nomina12receptor->setAttribute('ClaveEntFed',$estado->clave);
		/* SubContratacion  aqui iria esta parte revisar si abarcamos hasta aqui */
		$nomina12->appendChild( $nomina12receptor );
		/* FIN receptor */
		
		
		/* nomina12:Percepciones */
		if($_REQUEST['totalpercepciones']>0){
			
			$Percepciones = $xml->createElement('nomina12:Percepciones');
			if($_REQUEST['percepxsueldos']){
				$Percepciones->setAttribute('TotalSueldos',$_REQUEST['percepxsueldos']);
				$cadOri.= "|".$_REQUEST['percepxsueldos'];
			}
			/*aqui irian estas
			$cadOri.=2. TotalSeparacionIndemnizacion
			$cadOri.=3. TotalJubilacionPensionRetiro
			*/
			if($_REQUEST['importetotalseparacion']){
				$cadOri.= "|".$_REQUEST['importetotalseparacion'];//TotalSeparacionIndemnizacion
				$Percepciones->setAttribute('TotalSeparacionIndemnizacion',$_REQUEST['importetotalseparacion']);
			}
			if($_REQUEST['importetotaljubiliacionetc']){
				$cadOri.= "|".$_REQUEST['importetotaljubiliacionetc'];//TotalJubilacionPensionRetiro
				$Percepciones->setAttribute('TotalJubilacionPensionRetiro',$_REQUEST['importetotaljubiliacionetc']);
				
			}
			
			
			
			$Percepciones->setAttribute('TotalGravado',$_REQUEST['pgravadas']);
			$cadOri.= "|".$_REQUEST['pgravadas'];
			$Percepciones->setAttribute('TotalExento',$_REQUEST['pexenta']);
			$cadOri.= "|".$_REQUEST['pexenta'];
			
			
			
			foreach ($_REQUEST['percepciones'] as $key=>$val){
				$this->NominalibreModel->almacenaConceptotimbrado($empleado->idEmpleado,0, 0, 1, $_REQUEST['clave'][$key],$_REQUEST['pgravada'][$key]+$_REQUEST['pexento'][$key] ,$_REQUEST['pgravada'][$key],$_REQUEST['pexento'][$key]);
				
				 //if($key!=0){
				 	 $_REQUEST['concepto'][$key] = str_replace("(", "", $_REQUEST['concepto'][$key]);
					 $_REQUEST['concepto'][$key] = str_replace(")", "", $_REQUEST['concepto'][$key]);
				 		
				 	
				 	$Percepcionesdetalle = $xml->createElement('nomina12:Percepcion');
					$Percepcionesdetalle->setAttribute('TipoPercepcion',$_REQUEST['percepciones'][$key]);
					$cadOri.= "|".$_REQUEST['percepciones'][$key];
					$Percepcionesdetalle->setAttribute('Clave',$_REQUEST['clave'][$key]);
					$cadOri.= "|".$_REQUEST['clave'][$key];
					$Percepcionesdetalle->setAttribute('Concepto',$_REQUEST['concepto'][$key]);
					$cadOri.= "|".$_REQUEST['concepto'][$key];
					$Percepcionesdetalle->setAttribute('ImporteGravado',$_REQUEST['pgravada'][$key]);
					$cadOri.= "|".$_REQUEST['pgravada'][$key];
					$Percepcionesdetalle->setAttribute('ImporteExento',$_REQUEST['pexento'][$key]);
					$cadOri.= "|".$_REQUEST['pexento'][$key];
					if($_REQUEST['horasext'.$_REQUEST['hi'][$key]]>0){
						foreach ($_REQUEST['horasext'.$_REQUEST['hi'][$key]] as $key2=>$val){	 
							$horasExt = $xml->createElement('nomina12:HorasExtra');
							$horasExt->setAttribute('Dias',$_REQUEST['diash'.$_REQUEST['hi'][$key]][$key2]);
							$cadOri.= "|".$_REQUEST['diash'.$_REQUEST['hi'][$key]][$key2];//Dias
							$horasExt->setAttribute('TipoHoras',$_REQUEST['horasext'.$_REQUEST['hi'][$key]][$key2]);
							$cadOri.= "|".$_REQUEST['horasext'.$_REQUEST['hi'][$key]][$key2];//TipoHoras
							$horasExt->setAttribute('HorasExtra',$_REQUEST['numdiash'.$_REQUEST['hi'][$key]][$key2]);
							$cadOri.= "|".$_REQUEST['numdiash'.$_REQUEST['hi'][$key]][$key2];//HorasExtra
							$horasExt->setAttribute('ImportePagado',$_REQUEST['importehoras'.$_REQUEST['hi'][$key]][$key2]);
							$cadOri.= "|".$_REQUEST['importehoras'.$_REQUEST['hi'][$key]][$key2];//ImportePagado
							$Percepcionesdetalle->appendChild( $horasExt );
						}
					}
					// AccionesOTitulos
					if($_REQUEST['valormercado'][$key]>0){//AccionesOTitulos
						$AccionesOTitulos = $xml->createElement('nomina12:AccionesOTitulos');
						$AccionesOTitulos->setAttribute('ValorMercado',$_REQUEST['valormercado'][$key]);
						$cadOri.= "|".$_REQUEST['valormercado'][$key];
						$AccionesOTitulos->setAttribute('PrecioAlOtorgarse',$_REQUEST['preciootorgarse'][$key]);
						$cadOri.= "|".$_REQUEST['preciootorgarse'][$key];
						$Percepcionesdetalle->appendChild( $AccionesOTitulos );
					}
					//fin AccionesOTitulos
					$Percepciones->appendChild( $Percepcionesdetalle );
				//}
			}
			
			
		
		/*FIN nomina12:Percepciones */
		
		/*cadena original seguiria esta parte
		 *8. Información del Nodo nomina12:AccionesOTitulos
			1. ValorMercado
			2. PrecioAlOtorgarse
		 */
		
		/*CADENA ORIGINAL JUBILACION
		 * 10. Información del Nodo nomina12:JubilacionPensionRetiro
			1. TotalUnaExhibicion
			2. TotalParcialidad
			3. MontoDiario
			4. IngresoAcumulable
			5. IngresoNoAcumulable
		*/
	 	if($_REQUEST['importetotaljubiliacionetc']>0){
	 		$JubilacionPensionRetiro = $xml->createElement('nomina12:JubilacionPensionRetiro');
			if($_REQUEST['unasolaexibicion']>0){
				$JubilacionPensionRetiro->setAttribute('TotalUnaExhibicion',$_REQUEST['unasolaexibicion']);
				$cadOri.= "|".$_REQUEST['unasolaexibicion'];//TotalUnaExhibicion
			}
			if($_REQUEST['totalparcialidades']>0){
				$JubilacionPensionRetiro->setAttribute('TotalParcialidad',$_REQUEST['totalparcialidades']);
				$cadOri.= "|".$_REQUEST['totalparcialidades'];//TotalParcialidad
			}
			if($_REQUEST['montodiario']>0){
				$JubilacionPensionRetiro->setAttribute('MontoDiario',$_REQUEST['montodiario']);
				$cadOri.= "|".$_REQUEST['montodiario'];//MontoDiario
			}
			$JubilacionPensionRetiro->setAttribute('IngresoAcumulable',$_REQUEST['ingresoacumulable']);
			$cadOri.= "|".$_REQUEST['ingresoacumulable'];//IngresoAcumulable
			$JubilacionPensionRetiro->setAttribute('IngresoNoAcumulable',$_REQUEST['ingresonoacumulable']);
			$cadOri.= "|".$_REQUEST['ingresonoacumulable'];//IngresoNoAcumulable
			
			$Percepciones->appendChild( $JubilacionPensionRetiro );
		}
		 	
		 
		/*	
		
		11. Información del Nodo nomina12:SeparacionIndemnizacion
			1. TotalPagado
			2. NumAñosServicio
			3. UltimoSueldoMensOrd
			4. IngresoAcumulable
			5. IngresoNoAcumulable
		*/	
		if($_REQUEST['ingresoacumulableseparacion']>0){
	 		$SeparacionIndemnizacion = $xml->createElement('nomina12:SeparacionIndemnizacion');
			
			$SeparacionIndemnizacion->setAttribute('TotalPagado',$_REQUEST['totalpagadoindemnizacion']);
			$cadOri.= "|".$_REQUEST['totalpagadoindemnizacion'];//TotalPagado
			$SeparacionIndemnizacion->setAttribute('NumAñosServicio',$_REQUEST['anosservicio']);
			$cadOri.= "|".$_REQUEST['anosservicio'];//NumAñosServicio
			$SeparacionIndemnizacion->setAttribute('UltimoSueldoMensOrd',$_REQUEST['ultimosueldo']);
			$cadOri.= "|".$_REQUEST['ultimosueldo'];//UltimoSueldoMensOrd
			$SeparacionIndemnizacion->setAttribute('IngresoAcumulable',$_REQUEST['ingresoacumulableseparacion']);
			$cadOri.= "|".$_REQUEST['ingresoacumulableseparacion'];//IngresoAcumulable
			$SeparacionIndemnizacion->setAttribute('IngresoNoAcumulable',$_REQUEST['ingresonoacumulableseparacion']);
			$cadOri.= "|".$_REQUEST['ingresonoacumulableseparacion'];//IngresoNoAcumulable
			
			$Percepciones->appendChild( $SeparacionIndemnizacion );
		}


		$nomina12->appendChild( $Percepciones );
	}//cierre de percepciones y anexos

		/* nomina12:Deducciones */
		if($_REQUEST['totaldeducciones']>0){
			$_REQUEST['totaldeducciones'] = number_format($_REQUEST['totaldeducciones'],2,'.','');
			$Deducciones = $xml->createElement('nomina12:Deducciones');
			if($_REQUEST['otrasdedu']>0){
				$_REQUEST['otrasdedu'] = number_format($_REQUEST['otrasdedu'],2,'.','');
				$Deducciones->setAttribute('TotalOtrasDeducciones',$_REQUEST['otrasdedu']);
				$cadOri.= "|".$_REQUEST['otrasdedu'];
			}
			if($_REQUEST['impuestosretenidos']>0){
				$Deducciones->setAttribute('TotalImpuestosRetenidos',$_REQUEST['impuestosretenidos']);
				$cadOri.= "|".$_REQUEST['impuestosretenidos'];
			}
			
			
			
			foreach ($_REQUEST['deducciones'] as $key=>$val){
				
					$this->NominalibreModel->almacenaConceptotimbrado($empleado->idEmpleado,0, 0, 2, $_REQUEST['dclave'][$key],$_REQUEST['dimporte'][$key] ,0,0);
				
				// if($key!=0){
					$_REQUEST['dconcepto'][$key] = str_replace("(", "", $_REQUEST['dconcepto'][$key]);
					$_REQUEST['dconcepto'][$key] = str_replace(")", "", $_REQUEST['dconcepto'][$key]);
					
				 	$Deduccionesdetalle = $xml->createElement('nomina12:Deduccion');
					$Deduccionesdetalle->setAttribute('TipoDeduccion',$_REQUEST['deducciones'][$key]);
					$cadOri.= "|".$_REQUEST['deducciones'][$key];
					$Deduccionesdetalle->setAttribute('Clave',$_REQUEST['dclave'][$key]);
					$cadOri.= "|".$_REQUEST['dclave'][$key];
					$Deduccionesdetalle->setAttribute('Concepto',$_REQUEST['dconcepto'][$key]);
					$cadOri.= "|".$_REQUEST['dconcepto'][$key];
					$Deduccionesdetalle->setAttribute('Importe',$_REQUEST['dimporte'][$key]);
					$cadOri.= "|".$_REQUEST['dimporte'][$key];
					$Deducciones->appendChild( $Deduccionesdetalle );
				 //}
			}
			
			$nomina12->appendChild( $Deducciones );
		}
		/*FIN nomina12:deducciones */
		if($_REQUEST['totalotrospagos']>0){
			
			$OtrosPagos = $xml->createElement('nomina12:OtrosPagos');
			
			
			foreach ($_REQUEST['otros'] as $key=>$val){
					$this->NominalibreModel->almacenaConceptotimbrado($empleado->idEmpleado,0, 0, 4, $_REQUEST['oclave'][$key],$_REQUEST['oimporte'][$key] ,0,0);
				//if($key!=0){
					$_REQUEST['oconcepto'][$key] = str_replace("(", "", $_REQUEST['oconcepto'][$key]);
					$_REQUEST['oconcepto'][$key] = str_replace(")", "", $_REQUEST['oconcepto'][$key]);
					$OtrosPagosDetalle = $xml->createElement('nomina12:OtroPago');
					$OtrosPagosDetalle->setAttribute('TipoOtroPago',$_REQUEST['otros'][$key]);
					$cadOri.= "|".$_REQUEST['otros'][$key];
					$OtrosPagosDetalle->setAttribute('Clave',$_REQUEST['oclave'][$key]);
					$cadOri.= "|".$_REQUEST['oclave'][$key];
					$OtrosPagosDetalle->setAttribute('Concepto',$_REQUEST['oconcepto'][$key]);
					$cadOri.= "|".$_REQUEST['oconcepto'][$key];
					$OtrosPagosDetalle->setAttribute('Importe',$_REQUEST['oimporte'][$key]);
					$cadOri.= "|".$_REQUEST['oimporte'][$key];
					
					if($_REQUEST['otros'][$key] == "002"){ 
						$OtrosPagosubsidio = $xml->createElement('nomina12:SubsidioAlEmpleo');
						$OtrosPagosubsidio->setAttribute('SubsidioCausado',$_REQUEST['subsidio'][$key]);
						$cadOri.= "|".$_REQUEST['subsidio'][$key];//SubsidioCausado
						$OtrosPagosDetalle->appendChild( $OtrosPagosubsidio );
					}

					if($_REQUEST['otros'][$key] == "004"){
						$Otrosaldo = $xml->createElement('nomina12:CompensacionSaldosAFavor');
						$Otrosaldo->setAttribute('SaldoAFavor',$_REQUEST['saldofavor'][$key]);
						$cadOri.= "|".$_REQUEST['saldofavor'][$key];//SaldoAFavor
						$Otrosaldo->setAttribute('Año',$_REQUEST['anosubsidio'][$key]);
						$cadOri.= "|".$_REQUEST['anosubsidio'][$key];//Año
						$Otrosaldo->setAttribute('RemanenteSalFav',$_REQUEST['remanente'][$key]);
						$cadOri.= "|".$_REQUEST['remanente'][$key];//RemanenteSalFav
						
						$OtrosPagosDetalle->appendChild( $Otrosaldo );
					}
										
					
					$OtrosPagos->appendChild( $OtrosPagosDetalle );
					
				//}
			}
			
			$nomina12->appendChild( $OtrosPagos );
		}
	/// FIN OtrosPagos //
		
		
		 /* CADENA ORIGINAL
			17. Información del Nodo nomina12:Incapacidad
				1. DiasIncapacidad
				2. TipoIncapacidad
				3. ImporteMonetario
		 */
		// if( $_REQUEST['diasinc']>0){
// 			
			// foreach( $_REQUEST['TipoIncapacidad'] as $key=>$val){
				// $Incapacidad = $xml->createElement('nomina12:Incapacidad');
// 				
				// $Incapacidad = setAttribute('DiasIncapacidad',$_REQUEST['diasinc'][$key]);
				// $cadOri.= "|".$_REQUEST['diasinc'][$key];//DiasIncapacidad
				// $Incapacidad = setAttribute('TipoIncapacidad',$_REQUEST['tipoinc'][$key]);
				// $cadOri.= "|".$_REQUEST['tipoinc'][$key];//TipoIncapacidad
				// if($_REQUEST['importeinc']>0){
					// $Incapacidad = setAttribute('ImporteMonetario',$_REQUEST['importeinc'][$key]);
					// $cadOri.= "|".$_REQUEST['importeinc'][$key];//ImporteMonetario
				// }
				// $nomina12->appendChild( $Incapacidad );
			// }
		// }
		
		
		
		
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
		//$el_xml = str_replace("ISO-8859-1","UTF-8",$el_xml);
		$XML = $el_xml;
		//echo $XML;
		$xml->save('../cont/xmls/facturas/temporales/sintimbrar2.xml');
		$nominas=1;
		require_once('../../modulos/wsinvoice/sealInvoice.php');
		
		//print_r ($arrInvoice);
//		echo "*****".$arrInvoice['datos']['UUID'];
		
		if($arrInvoice['success'] == 1){
			//$msjFinal = $arrInvoice['estatus'];
			
			$xmlfile='_'.$empleado->nombreEmpleado." ".$empleado->apellidoPaterno." ".$empleado->apellidoMaterno.'_'.$arrInvoice['datos']['UUID'].'.xml';
	        $archivo = rename('../../modulos/cont/xmls/facturas/temporales/'.$arrInvoice['datos']['UUID'].'.xml','../../modulos/cont/xmls/facturas/temporales/'.$xmlfile);
			$almacenatimbre = $this->NominalibreModel->almacenaTimbrado( $empleado->idEmpleado, $_REQUEST['finicio'], $_REQUEST['fin'], $_REQUEST['fpago'], $_REQUEST['dpago'], $TipoNomina, $idnomp=0, $valorUnitario, $_REQUEST['totaldeducciones'], $total, 1, $arrInvoice['datos']['selloSAT'], $arrInvoice['datos']['selloCFD'], $arrInvoice['datos'][FechaTimbrado] , $arrInvoice['datos']['UUID'], $xmlfile, 0,$_REQUEST['idregfiscal'],$_REQUEST['periodicidad']);
			
			
			if($almacenatimbre>0){
				$this->NominalibreModel->updateConceptosTimbrados($almacenatimbre,1);
				$msjFinal = 'La factura se ha creado exitosamente, puede verla en el Almacen Digital o en el reporte de Nominas timbradas';
			}
			echo "<script> alert('".$msjFinal."'); window.location = 'index.php?c=Nominalibre&f=viewNomina'; </script>";
			exit();
		
			
		}else{
			$this->NominalibreModel->updateConceptosTimbrados(0,0);
			$msjFinal =str_replace("'", "", $arrInvoice['mensaje']) ;
			
			echo "<script> alert('".$msjFinal."\\nINTENTE DE NUEVO!'); window.location = 'index.php?c=Nominalibre&f=viewNomina'; </script>";
			exit();
		}








    // // Ruta al archivo XSLT
    // $xslFile = "../cont/xmls/facturas/temporales/cadenaoriginal_3_2.xslt"; 
//  
    // // Crear un objeto DOMDocument para cargar el CFDI
    // $xml = new DOMDocument("1.0","UTF-8"); 
    // // Cargar el CFDI
    // $xml->load($xmlFile);
// //  
    // // Crear un objeto DOMDocument para cargar el archivo de transformación XSLT
    // $xsl = new DOMDocument();
    // $xsl->load($xslFile);
//  
    // // Crear el procesador XSLT que nos generará la cadena original con base en las reglas descritas en el XSLT
    // $proc = new XSLTProcessor;
    // // Cargar las reglas de transformación desde el archivo XSLT.
    // $proc->importStyleSheet($xsl);
    // // Generar la cadena original y asignarla a una variable
    // $cadenaOriginal = $proc->transformToXML($xml);
//  
    // echo $cadenaOriginal;
	}

	function cancelaReciboNomina(){
		$strUUID = $_REQUEST['uuid'];
		$nomi=$_REQUEST['idNominatimbre'];
		$nominas = 1;
		require_once('../../modulos/wsinvoice/cancelInvoice.php');
	}
	
	/*F U N C I O N E S   P A R A   G E N E R A R   E L   X M L */
	
	/*
	Si un trabajador tiene una antigüedad de 10 años, 8 meses, 15 días, se debe registrar
	de la siguiente manera:
	Antigüedad= P10Y8M15D
	Si un trabajador tiene una antigüedad de 0 años, 0 meses 20 días, se debe registrar de
	la siguiente manera:
	Antigüedad= P20D
	Si un trabajador tiene una antigüedad de 110 semanas, se debe registrar de la 
	siguiente manera:
	Antigüedad= P110W
	Es importante mencionar que el registro se realizará conforme al año
	calendario.
	Ejemplo:
	Si un trabajador tiene un antigüedad de un mes (febrero 2016)
	Antigüedad= =P29D
	Si un trabajador tiene un antigüedad de un mes (febrero 2017)
	Antigüedad= =P28D 
	 * 
	 *Si el valor tiene el patrón P[1-9][0-9]{0,3}W, entonces el valor numérico del
	atributo debe ser menor o igual al cociente de (la suma del número de días
	transcurridos entre la FechaInicioRelLaboral y la FechaFinalPago más uno)
	dividido entre siete; en otro caso, el valor registrado debe corresponder con el
	número de años, meses y días transcurridos entre la FechaInicioRelLaboral y la
	FechaFinalPago.*/
	function generaAntiguedad($fechaAlta,$fechaFinpago){
		
		date_default_timezone_set("Mexico/General"); 
		$nuevafechaAlta = strtotime ( '-1 day' , strtotime ( $fechaAlta ) ) ;
		$nuevafechaAlta = date ( 'Y-m-d' , $nuevafechaAlta );
		
		$datetime1 = date_create($nuevafechaAlta);
		$datetime2 = date_create($fechaFinpago);
		$interval = date_diff($datetime1,$datetime2);
		$y = $m = $d = $w = '';
		if($interval->format('%yY')>0){
			$y = $interval->format('%yY');
		}
		if($interval->format('%mM')>0){
			$m = $interval->format('%mM');
		}
		if($interval->format('%dD')>0){
			$d = $interval->format('%dD');
		}
		
		if(!$y && !$d && $m){
			
			$m = "";
			$w = $interval->format('%a') / 7;
			$w = intval($w)."W";
			
		}
		
		return $interval->format('P'.$y.$m.$d.$w);
	}
	function generaCertificadoNomina($rfc_cliente,$cer_cliente,$pathdc){
		$comando='openssl x509 -inform DER -in '.$cer_cliente.' > "'.$pathdc.'/certificado.txt"';  
	    exec($comando); 

	    $certificado_open = fopen($pathdc.'/certificado.txt', "r");
	    $certificado = fread($certificado_open, filesize($pathdc.'/certificado.txt'));
	    fclose($certificado_open);

	    $certificado=  str_replace("-----BEGIN CERTIFICATE-----", "", $certificado);
	    $certificado=  str_replace("-----END CERTIFICATE-----", "", $certificado);
	    $certificado=  str_replace("\n", "", $certificado);
	    $certificado= trim($certificado);
	    return $certificado;
	}
	function generaNoCertificadoNomina($rfc_cliente,$cer_cliente,$pathdc){
			$comando='openssl x509 -inform DER -in '.$cer_cliente.' -noout -serial > "'.$pathdc.'/noCertificado.txt"';
		    exec($comando);
	
		    $noCertificado_open = fopen("".$pathdc."/noCertificado.txt", "r");
		    $noCertificado = fread($noCertificado_open, filesize($pathdc.'/noCertificado.txt'));
		    fclose($noCertificado_open);
	
		    $noCertificado=  preg_replace("/serial=/", "", trim($noCertificado));
		    $temporal=  str_split($noCertificado);
		    $noCertificado="";
		    $i=0;
		    foreach ($temporal as $value) {
		        if(($i%2))
		        $noCertificado .= $value;
		        $i++;
		    }
			
	
	    	return $noCertificado;
	
	}
	function generaSelloNomina($rfc_cliente,$dteTrailer,$pathdc){
		// $pem = $pathdc.'/'.$rfc_cliente.'.pem';
		// $comando="openssl dgst -sha1 -sign ".$pem." '".$pathdc."/CO" .$dteTrailer. ".txt' | openssl enc -base64 -A -out ".$pathdc."/sello" .$dteTrailer. ".txt"; 
	  	// exec($comando);
// // 
		// $sello_open = fopen($pathdc.'/sello' . $dteTrailer . '.txt', "r");
		// $sello = fread($sello_open, filesize($pathdc.'/sello' . $dteTrailer . '.txt'));
		// fclose($sello_open);
// 
	  	// $sello=trim($sello);
// 
	  	// unlink($pathdc.'/sello' . $dteTrailer . '.txt');
	  	// unlink($pathdc.'/CO' . $dteTrailer .'.txt');
// 
	  	// return $sello;
	  	$pem = $pathdc.'/'.$rfc_cliente.'.pem';
		$comando="openssl dgst -sha256 -sign ".$pem." '".$pathdc."/CO" .$dteTrailer. ".txt' | openssl enc -base64 -A -out ".$pathdc."/sello" .$dteTrailer. ".txt"; 
	  	exec($comando);

		$sello_open = fopen($pathdc.'/sello' . $dteTrailer . '.txt', "r");
		$sello = fread($sello_open, filesize($pathdc.'/sello' . $dteTrailer . '.txt'));
		fclose($sello_open);

	  	$sello=trim($sello);

	  	unlink($pathdc.'/sello' . $dteTrailer . '.txt');
	  	unlink($pathdc.'/CO' . $dteTrailer .'.txt');

	  	return $sello;
		
	}
	function generaPemNomina($rfc_cliente,$key_cliente,$pwd_cliente,$pathdc){
		$pem = $pathdc.'/'.$rfc_cliente.'.pem';
		$comando='openssl pkcs8 -inform DER -in '.$key_cliente.' -passin pass:'.$pwd_cliente.' -out '.$pem;
   	 	exec($comando);

    		$validacion = $this->validacionNominaxml('pem',$pem);
    		return $validacion; 
			
	}
	function validacionNominaxml($clave,$var){
		if($clave=='pem'){
			$open = fopen($var, "r");
			$contenido = fread($open, filesize($var));
			fclose($open);
			if($contenido!=''){
				return 1;
			}else{
				return 0;
			}
			
		}
	}
 	function generaCadenaOriginalNomina($cad,$dteTrailer,$rfc_cliente,$pathdc){
		$archivo = fopen($pathdc.'/CO' . $dteTrailer . '.txt','w');
		fwrite($archivo,$cad);
		fclose($archivo);
		return 1;
	
	}
	
	
}




?>