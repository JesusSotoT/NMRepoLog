<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
class invoiceXmlToPdf
{
	//Variables constructor
	private $ruta, $xml, $intRed, $intGreen, $intBlue, $strImageLogo,$namexml;

	#Variables clase
	//Generales
	private $version, $fecha, $folio, $serie, $total, $subTotal, $descuento, $sello, $tipoDeComprobante,
	$lugarExpedicion;

	//Emisor
	private $rfcEmisor, $nombreEmisor, $regimenFiscal;

	//Receptor
	private $rfcReceptor, $nombreReceptor, $UsoCFDI;

	//Conceptos, Impuestos, Complementos.
	private $conceptos, $totalImpuestos, $trasladados, $retenidos, $todosLosImpuestos, $tfd;

	//Nomina
	private $nomina;

	//Cfdi Relacionados
	private $CfdiRelacionados, $tipoRelacion;

	//Pagos
	private $pagos;

	//Appministra
	private $caja, $obser;

	public  function __construct($ruta, $strImageLogo='', $intRed='0', $intGreen='0', $intBlue='0', $ruta2,$namexml,$caja,$obser) {
	  $this->ruta = $ruta;
	  $this->ruta2 = $ruta2;
	  $this->namexml = $namexml;
	  $this->caja = $caja;
	  $this->obser = $obser;
	  $this->strImageLogo = $strImageLogo;
	  $this->xml = $this->generarObjeto();
	  $this->intRed = $intRed;
	  if($intRed<0){
			$this->intRed = 0;
	  }else{
			if($intRed>255){
			  $this->intRed = 255;
			}
	  }
	  $this->intGreen = $intGreen;
	  if($intGreen<0){
			$this->intGreen = 0;
	  }else{
			if($intGreen>255){
			  $this->intGreen = 255;
			}
	  }
	  	$this->intBlue = $intBlue;
	  if($intBlue<0){
			$this->intBlue = 0;
	  }else{
			if($intBlue>255){
			  $this->intBlue = 255;
			}
	  }
	}

	private function generarObjeto()
	{
	  #Cargamos archivo
	  $objeto = array();
	  $sxe = new SimpleXMLElement($this->ruta, NULL, TRUE);

	  #1.- Atributos Nodo [principal]
	  foreach($sxe->attributes() as $campo => $valor){
	  	$objeto[$campo] = (string)$valor[0];
			//Obtenemos la version
			if($campo == "Version" || $campo == "version"){
				$this->version = (string)$valor[0];
			}
		
			//Obtenemos la fecha
			if($campo == "Fecha" || $campo == "fecha"){
				$this->fecha = (string)$valor[0];
			}
		
			//Obtenemos el folio
			if($campo == "Folio" || $campo == "folio"){
				$this->folio = (string)$valor[0];
			}

			//Obtenemos la serie
			if($campo == "Serie" || $campo == "serie"){
				$this->serie = (string)$valor[0];
			}

			//Obtenemos el total
			if($campo == "Total" || $campo == "total"){
				$this->total = (string)$valor[0];
			}
		
			//Obtenemos el subtotal
			if($campo == "SubTotal" || $campo == "subTotal"){
				$this->subTotal = (string)$valor[0];
			}

			//Obtenemos el descuento
			if($campo == "Descuento" || $campo == "descuento"){
				$this->descuento = (string)$valor[0];
			}
			
			//Obtenemos el sello
			if($campo == "Sello" || $campo == "sello"){
				$this->sello = (string)$valor[0];
			}

			//Obtenemos el número de certificado
			if($campo == "NoCertificado" || $campo == "noCertificado"){
				$this->noCertificado = (string)$valor[0];
			}

			//Obtenemos el tipo de comprobante
			if($campo == "TipoDeComprobante" || $campo == "tipoDeComprobante"){
				$this->tipoDeComprobante = (string)$valor[0];
			}

			//Obtenemos el metodo de pago
			if($campo == "MetodoPago" || $campo == "metodoDePago"){
				if ($valor == 'PUE') {
					$this->metodoDePago = "PUE Pago en una sola exhibición";
				} else if($valor == 'PPD'){
					$this->metodoDePago = "PPD Pago en parcialidades o diferido";
				} else if($valor == 'PIP'){
					$this->metodoDePago = "PIP Pago inicial y parcialidades";
				} else {
					$this->metodoDePago = (string)$valor[0];
				}
			}

			//Obtenemos la forma de pago
			if($campo == "FormaPago" || $campo == "formaDePago"){
				$this->formaDePago = (string)$valor[0];
			}

			//Obtenemos la moneda
			if($campo == "Moneda" || $campo == "moneda"){
				$this->moneda = (string)$valor[0];
			}
			if($campo == "TipoCambio"){
				$this->TipoCambio = (string)$valor[0];
			}
	  }
	  //$ns = $sxe->getNamespaces(true);

	  foreach ($sxe->attributes() as $campo => $valor) {
	  	if ($campo == "LugarExpedicion") {
	  		$this->lugarExpedicion = $valor;
	  	}
	  }

	  $hijos = $sxe->children('cfdi', true);

	  #2 .- Nodo [Emisor]
	  $objeto["Emisor"] = array();
	  foreach($hijos->Emisor->attributes() as $campo => $valor){
			$objeto["Emisor"][$campo] = (string)$valor[0];

			//Obtenemos el rfc del emisor
			if ($campo == "Rfc" || $campo == "rfc"){
				$this->rfcEmisor = (string)$valor[0];
			}

			//Obtenemos el nombre del emisor
			if ($campo == "Nombre" || $campo == "nombre"){
				$this->nombreEmisor = (string)$valor[0];
			}

			//Obtenemos el regimen fiscal
			if ($campo == "RegimenFiscal"){
				$this->regimenFiscal = (string)$valor[0];
			}
	  }

	  //En la version del cfdi 3.2 aun se utiliza domicilio.
	  foreach($hijos->Emisor->DomicilioFiscal->attributes() as $campo => $valor){
			$objeto["Emisor"]["DomicilioFiscal"][$campo] = (string)$valor[0];
	  }

	  //Obtenemos el regimen fiscal para los cfdi de version 3.2
	  foreach($hijos->Emisor->RegimenFiscal->attributes() as $campo => $valor){
			if (!isset($regimenFiscal)){
				$this->regimenFiscal = (string)$valor[0];
			}
	  }

	  #3 .- Nodo [Receptor]
	  $objeto["Receptor"] = array();
	  foreach($hijos->Receptor->attributes() as $campo => $valor){
			$objeto["Receptor"][$campo] = (string)$valor[0];

			//Obtenemos el rfc del receptor
			if ($campo == "Rfc" || $campo == "rfc"){
				$this->rfcReceptor = (string)$valor[0];
			}

			//Obtenemos el nombre del Receptor
			if ($campo == "Nombre" || $campo == "nombre"){
				$this->nombreReceptor = (string)$valor[0];
			}

			//Obtenemos el UsoCFDI
			if ($campo == "UsoCFDI"){
				$this->UsoCFDI = (string)$valor[0];
			}
	  }

	  //En la version del cfdi 3.2 aun se utiliza domicilio.
	  foreach($hijos->Receptor->Domicilio->attributes() as $campo => $valor){
			$objeto["Receptor"]["Domicilio"][$campo] = (string)$valor[0];
	  }

	  #4 .- Nodo Conceptos
	  $objeto["Conceptos"] = array();

	  $conceptos = $hijos->Conceptos->children('cfdi', true);
	  for ($cont = 0; $cont < count($conceptos); $cont++) {
			$concepto = array();
			foreach($conceptos[$cont]->attributes() as $campo => $valor){
			  $concepto[$campo] = (string)$valor[0];
			}

			#Impuestos si es que tiene [traslados]
			$impuestos = $conceptos[$cont]->children('cfdi', true);
			$impuestos = $impuestos->Impuestos;
			$traslados = $impuestos->Traslados;

			if (!empty($traslados)) {
				#Extraemos los impuestos trasladados del concepto
				foreach ($traslados->children('cfdi', true) as $nombre => $traslado) {
					$conceptoImp[$campo] = "";
					foreach ($traslado->attributes() as $campo => $valor) {
						$conceptoImp[$campo] = (string)$valor[0];
					}
					$concepto['Impuestos']['Traslados'][] = $conceptoImp;
				}
			}

			#Impuestos si es que tiene [retenidos]
			$retenidos = $impuestos->Retenciones;

			if (!empty($retenidos)) {
				#Extraemos los impuestos retenidos del concepto
				foreach ($retenidos->children('cfdi', true) as $nombre => $retenido) {
					foreach ($retenido->attributes() as $campo => $valor) {
						$conceptoImp[$campo] = (string)$valor[0];
					}
					$concepto['Impuestos']['Retenciones'][] = $conceptoImp;
				}	
			}

			$objeto["Conceptos"][] = $concepto;
	  }


	  $this->conceptos = $objeto["Conceptos"];

	  #5 .- Nodo [Impuestos]
	  $objeto["Impuestos"] = array();
	  $impuestos = $hijos->Impuestos->attributes();
	  if (!empty($impuestos)) {
		  foreach($hijos->Impuestos->attributes() as $a => $b){
				$objeto["Impuestos"][$a] = (string)$b[0];
		  }
		  $this->totalImpuestos = $objeto["Impuestos"];
		  
		  $objeto["Impuestos"]["Traslados"] = array();
		  $traslados = $hijos->Impuestos->Traslados->children('cfdi', true);
		  for ($t = 0; $t < count($traslados); $t++ ) {
				$traslado = array();
				foreach($traslados[$t]->attributes() as $campo => $valor) {
					$traslado[$campo] = (string)$valor[0];
				}
				//Los igualamos al objeto
				$objeto["Impuestos"]["Traslados"][] = $traslado;
				//Los igualamos a la variable de clase
				$this->trasladados[] = $traslado;
		  }

		  $objeto["Impuestos"]["Retenciones"] = array();
		  $retenciones = $hijos->Impuestos->Retenciones->children('cfdi', true);

		  for ($r = 0; $r < count($retenciones); $r++){
				$retencion = array();
				foreach ($retenciones[$r]->attributes() as $campo => $valor) {
					$retencion[$campo] = (string)$valor[0];
				}
				//Los igualamos al objeto
				$objeto["Impuestos"]["Retenciones"][] = $retencion;
				//Los igualamos a la variable de clase
				$this->retenidos[] = $retencion;
		  }
	  }

	  #6 .- Nodo [Complemento]
	  $objeto["Complemento"] = array("TimbreFiscalDigital" => array());

	  $complemento = $hijos->Complemento->children('tfd', true);

	  foreach($complemento->TimbreFiscalDigital->attributes() as $campo => $valor){
			$objeto["Complemento"]["TimbreFiscalDigital"][$campo] = (string)$valor[0];
			$this->tfd[$campo] = (string)$valor[0];
	  }

	  #7 .- Nominas
	  $objeto["Nomina"] = array();
	  //Complemento->Nomina
	  $compNom = $hijos->Complemento->children('nomina12', true);

	  #Validamos que exista el nodo de nomina
	  if (!empty($compNom)) {
		  foreach($compNom->Nomina->Percepciones->attributes() as $campo => $valor){
				$objeto["Nomina"][$campo] = (string)$valor[0];
		  }
		  $this->nomina = $objeto["Nomina"];

		  #8 .- Nodo Nomina[Emisor]
		  $nomina_emisor = $compNom->Nomina->children('nomina12', true);
		  $emisor = array();
		  foreach ($nomina_emisor->Emisor->attributes() as $campo => $valor) {
		  	$emisor[$campo] = $valor;
		  }
		  $this->nomina["emisor"] = $emisor;

		  #9 .- Nodo Nomina[Receptor]
		  $nomina_receptor = $compNom->Nomina->children('nomina12', true);
		  $receptor = array();
		  foreach ($nomina_receptor->Receptor->attributes() as $campo => $valor) {
		  	$receptor[$campo] = (string)$valor;
		  }
		  $this->nomina["receptor"] = $receptor;

		  #10 .- Nodo Nomina[Percepciones]
		  $objeto["Nomina"]["Percepciones"] = array();
		  $percepciones = $compNom->Nomina->Percepciones->children('nomina12', true);
		  for ($p = 0; $p < count($percepciones); $p++ ) {
				$percepcion = array();
				foreach($percepciones[$p]->attributes() as $campo => $valor) {
					$percepcion[$campo] = (string)$valor[0];
				}
				//Los igualamos a la variable de clase
				$this->nomina['percepciones'][] = $percepcion;
		  }

		  #11 .- Nodo Nomina[Deducciones]
		  $objeto["Nomina"]["Deducciones"] = array();
		  $deducciones = $compNom->Nomina->Deducciones->children('nomina12', true);
		  for ($p = 0; $p < count($deducciones); $p++ ) {
				$deducion = array();
				foreach($deducciones[$p]->attributes() as $campo => $valor) {
					$deducion[$campo] = (string)$valor[0];
				}
				//Los igualamos al objeto
				$objeto["Nomina"]["Deducciones"][] = $deducion;
				//Los igualamos a la variable de clase
				$this->nomina['deducciones'][] = $deducion;
		  }
	  }

	  #12 .- Nodo Cfdi Relacionados
	  $objeto["CfdiRelacionados"] = array();
		$cfdiRelacion = $hijos->CfdiRelacionados->children('cfdi', true);
	  
	  if (!empty($cfdiRelacion)){
	  	$this->tipoRelacion = $hijos->CfdiRelacionados->attributes();
		  $this->tipoRelacion = $this->tipoRelacion['TipoRelacion'];
		  $objeto["CfdiRelacionados"]["TipoRelacion"] = $this->tipoRelacion;

		  foreach ($cfdiRelacion as $index => $uuid) {
		  	$objeto["CfdiRelacionados"]["UUIDS"][] = $uuid->attributes();
		  }
		  $this->CfdiRelacionados = $objeto["CfdiRelacionados"];
	  }

	  #13 .- Nodo Pago10
	  $objeto['Pagos'] = array();
	  $pagos = $hijos->Complemento->children('pago10', true);
	  $pagos = $pagos->Pagos;

	  if (!empty($pagos)) {
		  $this->pagos["Atributos"] = $pagos->Pago->attributes();
		  $objeto["Pagos"]["Atributos"] = $this->pagos["Atributos"];

		  if (!isset($pagos->Pago->DoctoRelacionado)) {
		  	$objeto['Pagos']['Documentos'] = false;
		  } else {
			  foreach ($pagos->Pago->DoctoRelacionado as $documento => $valor) {
			  	$objeto['Pagos']['Documentos'][] = $valor->attributes();
			  }
		  }
		  $this->pagos['Documentos'] = $objeto['Pagos']['Documentos'];
	  }

	  //var_dump($objeto['Pagos']);
	  return $objeto;
	}

	public function genPDF() {

		# Instancia objeto xml
		$d = $this->xml;

		#template
		$r = $this->intRed;
		$g = $this->intGreen;
		$b = $this->intBlue;

		/*switch( $this-> template )
		{
			case "black": $r = 0; $g = 0; $b = 0;   break;
			case "red": $r = 254; $g = 0; $b = 0; break;
			case "blue": $r = 0; $g = 82; $b = 204; break;
			case "orange": $r = 255; $g = 102; $b = 0; break;
			case "green": $r = 0; $g = 153; $b = 51; break;
			default: $r = 0; $g = 0; $b = 0;   break;
		}*/

		#0.- Definimos el arreglo de hojas
		$sumaImpuestos = count($this->tasladados)+count($this->retenidos);
		$hojas = $this->crearHojas(count($this->conceptos), $sumaImpuestos);

		# Creamos objeto FPDF y definimos variables iniciales
		$pdf = new FPDF('P','mm',array(215.9, 279.4));

		/*echo "<pre>";
		print_r($hojas);
		echo "</pre>";*/
		$paginaActual = 0;
		foreach ($hojas as $hoja){

			$paginaActual++;

			$pdf->AddPage();
			$pdf->SetMargins(0,0,0);
			$pdf->SetAutoPageBreak(false,0);
			//echo 'sirve';
			$pdf->SetFont('arial','',8);
			//echo 'XXXXXXXX';
			//$pdf->SetFillColor(254, 0, 0);
			$pdf->SetDrawColor($r, $g, $b); 
			$pdf->SetFillColor($r, $g, $b);
			$y = 10;
			$conceptoIl = 0;
			$conceptoIF = 0;

			foreach ($hoja as $seccion) {
				switch($seccion["tipo"]) {
					case "encabezado":
						# Logotipo
						if($this->strImageLogo != ''){
							if(strpos(FPDF_LOGOIMAGEPATH, '/mlog/webapp/') === false){
								$strImageFile = dirname(__FILE__) . '/' . (FPDF_LOGOIMAGEPATH . $this->strImageLogo);
							} else {
							  //Recordatorio: Cambiar ruta fija por variable
							  $strImageFile = "../../../netwarelog/archivos/1/organizaciones/".$this->strImageLogo;
							}
							$strImageFile = "../../../netwarelog/archivos/1/organizaciones/".$this->strImageLogo;
							if(exif_imagetype($strImageFile)==IMAGETYPE_PNG){
								$image = @imagecreatefrompng($strImageFile);
								imagejpeg($image, $this->tfd['UUID'].'.jpg', 100);
								imagedestroy($image);
								$pdf->Image($this->tfd['UUID'].'.jpg',10, 10, 35);
								unlink($this->tfd['UUID'].'.jpg');
							}else{
							  $pdf->Image($strImageFile, 10, 10, 35);
							}

						}
						#1.- Datos Emisor
						$pdf->SetFont('arial','B',8);
						$pdf->setXY(50, 10); 
						$pdf->Cell(60, 5, utf8_decode('EMISOR'), 0, 1, 'L', false);

						//Nombre Emisor
						$pdf->SetFont('arial','',8);
						$pdf->setXY(50, 15); 
						$pdf->MultiCell(60, 4, utf8_decode($this->nombreEmisor."\nRFC: ".$this->rfcEmisor), 0, 'L');
						#Validamos que sea de tipo pago para mostrar datos de receptor al comienzo del pdf
						if ($this->tipoDeComprobante == 'P') {
							$pdf->SetFont('arial','B',8);
							$pdf->setXY(50, 25);
							$pdf->Cell(60, 5, utf8_decode('RECEPTOR'), 0, 1, 'L', false);

							$pdf->SetFont('arial','',8);
							$pdf->setXY(50, 30); 
							$pdf->MultiCell(60, 4, utf8_decode($this->nombreReceptor."\nRFC: ".$this->rfcReceptor), 0, 'L');
						}

						//Si el CFDI es 3.2 mostrara los datos de domicilio del Emisor
						if (!empty($this->nomina)) {
							//Lugar de expedición
							$pdf->setXY(50, 26); 
							$pdf->MultiCell(60, 4, utf8_decode("LUGAR DE EXPEDICIÓN: ".$this->lugarExpedicion), 0, 'L');
							//Curp Emisor
							$pdf->setXY(50, 30);
							$pdf->MultiCell(60, 4, utf8_decode("CURP: ".$this->nomina["emisor"]["Curp"][0]), 0, 'L');
							//Registro Patronal
							$pdf->setXY(50, 34); 
							$pdf->MultiCell(60, 4, utf8_decode("REGISTRO PATRONAL: ".$this->nomina["emisor"]["RegistroPatronal"][0]), 0, 'L');
						} else if ($this->version == '3.2') {
							$domicilioFiscal = $d["Emisor"]["DomicilioFiscal"]["calle"] . " " . $d["Emisor"]["DomicilioFiscal"]["noexterior"] . "\nCol. " . $d["Emisor"]["DomicilioFiscal"]["colonia"] . ", C.P. " . $d["Emisor"]["DomicilioFiscal"]["codigoPostal"]."\n". $d["Emisor"]["DomicilioFiscal"]["estado"]. " " .$d["Emisor"]["DomicilioFiscal"]["pais"]."\n". $d["Emisor"]["rfc"];
							$pdf->setXY(50, 30); $pdf->MultiCell(60, 4, utf8_decode($domicilioFiscal), 0, 'L');
						}

						#2.- Datos Factura
						#Encabezado
						//Definimos la tipografia
						$pdf->SetTextColor(255, 255, 255);  
						$pdf->SetFont('arial','B',9);

						//Definimos los titulos
						$pdf->setXY(120, 10); $pdf->Cell(40, 6, utf8_decode('FECHA'), 1, 1, 'C', true);
						
						if($this->version == '3.3' && $this->tipoDeComprobante == 'P'){
							$pdf->setXY(163, 10); $pdf->Cell(42, 6, utf8_decode("COMPLEMENTO DE PAGO"), 1, 1, 'C', true);
						} 
						//Hacemos ajustes a la información mostrada dependiendo si es nomina o no.
						else if (empty($this->nomina)){
							$pdf->setXY(165, 10); $pdf->Cell(40, 6, utf8_decode("FACTURA"), 1, 1, 'C', true);
						} else {
							$pdf->setXY(165, 10); $pdf->Cell(40, 6, utf8_decode("RECIBO NOMINA"), 1, 1, 'C', true);
						}
						$pdf->setXY(120, 30); $pdf->Cell(85, 6, utf8_decode("FOLIO FISCAL"), 1, 1, 'C', true);

						//Definimos el tipo de letra y color
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetFont('arial','',8);
						$pdf->setXY(120, 16);
						$pdf->Cell(40, 8, utf8_decode(''), 1, 1, 'C', false);
						
						//Definimos la fecha
						$pdf->setXY(120, 16);
						$pdf->MultiCell(40, 4, utf8_decode($this->dateFormatString($this->fecha)), 0, 'C');
						
						if ($this->version == '3.3' && $this->tipoDeComprobante == 'P') {
							$anchoCompP = 163;
							$anchoCompP2 = 42;
						} else {
							$anchoCompP = 165;
							$anchoCompP2 = 40;
						}
						//Definimos el folio
						$pdf->setXY($anchoCompP, 16);
						$pdf->Cell($anchoCompP2, 8, utf8_decode("".$this->serie."".$this->folio), 1, 1, 'C', false);
						
						//Definimos el folio fiscal
						$pdf->setXY(120, 36);
						$pdf->Cell(85, 8, utf8_decode($this->tfd['UUID']), 1, 1, 'C', false);

						//Definimos el tipo de letra y color
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetFont('arial','',8);

						#Regimen Fiscal
						if ($this->version == '3.3') {
							$regimenFiscal = $this->regimenFiscal." ".$this->regimenFiscal($this->regimenFiscal);
						} else {
							$regimenFiscal = $this->regimenFiscal;
						}
						$pdf->setXY(125, 46);
						$pdf->Cell(80, 3, utf8_decode("RÉGIMEN FISCAL: ".$regimenFiscal), 0, 1, 'R', false);

						#3.- Datos Cliente
						#Encabezado
						//Validamos que no sea nomina
						if (empty($this->nomina) && $this->tipoDeComprobante != 'P') {
							$pdf->SetTextColor(255, 255, 255);
							$pdf->SetFont('arial','B',9);
							$pdf->setXY(10, 50); $pdf->Cell(195, 6, utf8_decode('RECEPTOR'), 1, 1, 'C', true);
							
							//Definimos tipografia
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetFont('arial','',8);
							
							//Si es diferente de la version 3.3 se mostrara el domicilio del cliente
							if ($this->version != "3.3") {
								//Definimos la celda izquierda de cliente
								$pdf->setXY(10, 56);
								$pdf->Cell(97.5, 17, utf8_decode(''), 1, 1, 'C', false);
								//Definimos la celda derecha de cliente
								$pdf->setXY(107.5, 56);
								$pdf->Cell(97.5, 17, utf8_decode(''), 1, 1, 'C', false);
								//Definimos el nombre o razon social del cliente
								$pdf->setXY(10, 57);
								$pdf->MultiCell(97.5, 4, utf8_decode("NOMBRE O RAZÓN SOCIAL: ".$this->nombreReceptor ."\nR.F.C. CLIENTE: ".$this->rfcReceptor), 0, 'L');
								//Formamos la dirección del cliente
								$domicilioCliente = "DOMICILIO: Calle: " . $d["Receptor"]["Domicilio"]["calle"] . " " . $d["Receptor"]["Domicilio"]["noExterior"] .", Col: " . $d["Receptor"]["Domicilio"]["colonia"] . " CP: " . $d["Receptor"]["Domicilio"]["codigoPostal"] . "\n" . $d["Receptor"]["Domicilio"]["municipio"] . ", " . $d["Receptor"]["Domicilio"]["estado"] . ", ". $d["Receptor"]["Domicilio"]["pais"] .  ".";
								//Definimos la dirección del cliente
								$pdf->setXY(107.5, 57);
								$pdf->MultiCell(97.5, 4, utf8_decode($domicilioCliente), 0, 'L');
								$y = 75;
							} else {
								//Definimos la celda de cliente
								$pdf->setXY(10, 56);
								$pdf->Cell(195, 14, utf8_decode(''), 1, 1, 'C', false);
								//Definimos el nombre o razon social del cliente
								$pdf->setXY(10, 57);
								$pdf->MultiCell(195, 4, utf8_decode("NOMBRE O RAZÓN SOCIAL: ".$this->nombreReceptor ."\nR.F.C. CLIENTE: ".$this->rfcReceptor."\nUso CFDI: ".$this->UsoCFDI." ".$this->usoCFDI($this->UsoCFDI)), 0, 'L');
								$y = 70.5;
							}
						}
						#Validamos si la factura es de tipo pago
						elseif($this->tipoDeComprobante == 'P') {
							$pdf->SetTextColor(255, 255, 255);
							$pdf->SetFont('arial','B',9);
							$pdf->setXY(10, 50); 
							$pdf->Cell(97.5, 6, utf8_decode('ORDENANTE'), 1, 1, 'C', true);
							$pdf->setXY(107.5, 50);
							$pdf->Cell(97.5, 6, utf8_decode('BENEFICIARIO'), 1, 1, 'C', true);

							//Definimos tipografia
							$pdf->SetTextColor(0, 0, 0);  
							$pdf->SetFont('arial','',8);
							$y = 56;

							//Definimos la celda izquierda del empleado
							$pdf->setXY(10, $y);
							$pdf->Cell(97.5, 13, utf8_decode(''), 1, 1, 'C', false);

							//Definimos la celda derecha del empleado
							$pdf->setXY(107.5, $y);
							$pdf->Cell(97.5, 13, utf8_decode(''), 1, 1, 'C', false);

							#Definimos el RFC del ordenante
							$pdf->setXY(10, $y+1);
							$pdf->Cell(97.5, 4, utf8_decode('RFC EMISOR CUENTA ORDENANTE: '.$this->pagos['Atributos']['RfcEmisorCtaOrd']), 0, 1, 'L', false);

							#Definimos el RFC del beneficiario
							$pdf->setXY(107.5, $y+1);
							$pdf->Cell(97.5, 4, utf8_decode('RFC EMISOR CUENTA ORDENANTE: '.$this->pagos['Atributos']['RfcEmisorCtaBen']), 0, 1, 'L', false);							
							$y+=4.5;

							#Definimos el nombre de banco del ordenante
							$pdf->setXY(10, $y);
							$this->pagos['Atributos']['banco'] = "-";
							$pdf->Cell(97.5, 4, utf8_decode('NOMBRE BANCO ORDENANTE EXTRANJERO: '.$this->pagos['Atributos']['banco']), 0, 1, 'L', false);

							#Definimos el nombre de banco del ordenante
							$pdf->setXY(107.5, $y);
							$pdf->Cell(97.5, 4, utf8_decode('CUENTA BENEFICIARIO: '.$this->pagos['Atributos']['CtaBeneficiario']), 0, 1, 'L', false);
							$y+=3.5;

							#Definimos la cuenta del ordenante
							$pdf->setXY(10, $y);
							$pdf->Cell(97.5, 4, utf8_decode('CUENTA ORDENANTE: '.$this->pagos['Atributos']['CtaOrdenante']), 0, 1, 'L', false);

							//var_dump($this->pagos['Atributos']);
							$y+=6;
						} 
						#Si es nomina
						else {
							#Encabezado Empleado
							$pdf->SetTextColor(255, 255, 255);
							$pdf->SetFont('arial','B',9);
							$pdf->setXY(10, 50); $pdf->Cell(195, 6, utf8_decode('EMPLEADO'), 1, 1, 'C', true);
							
							//Definimos tipografia
							$pdf->SetTextColor(0, 0, 0);  
							$pdf->SetFont('arial','',8);
							$y = 56;

							$alto_receptor = ((count($this->nomina['receptor'])+2)/2)*4+1.5;

							//Definimos la celda izquierda del empleado
							$pdf->setXY(10, $y);
							$pdf->Cell(97.5, $alto_receptor, utf8_decode(''), 1, 1, 'C', false);

							//Definimos la celda derecha del empleado
							$pdf->setXY(107.5, $y);   
							$pdf->Cell(97.5, $alto_receptor, utf8_decode(''), 1, 1, 'C', false);
							$y+=1;

							//Definimos el nombre o razon social del empleado
							$pdf->setXY(10, $y);
							$pdf->Cell(97.5, 4, utf8_decode($this->nombreReceptor), 0, 'L');
							$y+=4;
							$pdf->setXY(10, $y);
							$pdf->Cell(97.5, 4, utf8_decode("R.F.C. EMPLEADO: ".$this->rfcReceptor), 0, 'L');
							$y+=4;
							$mitad = (count($this->nomina['receptor'])+2)/2;
							//Extraemos los datos del receptor
							$cont = 1; $guardaY = $y-8;
							foreach ($this->nomina['receptor'] as $campo => $valor) {
								if ($cont < ($mitad-1)) {
									$pdf->setXY(10, $y);
									$pdf->Cell(97.5, 4, utf8_decode($campo.": ".$valor), 0,'L');
									$y+=4;
								} else {
									$pdf->setXY(107.5, $guardaY);
									$pdf->Cell(97.5, 4, utf8_decode($campo.": ".$valor), 0,'L');
									$guardaY+=4;
								}
								$cont++;
							}
						}
						$y+=1;
					break;

					case "conceptos":
						#3.- Conceptos
						#Encabezado
						//Definimos la tipografia
						$pdf->SetTextColor(255, 255, 255);
						$pdf->SetFont('arial','B',7);
						//Definimos los titulos 3.3
						if ($this->version == "3.3" && $this->tipoDeComprobante != 'P') {
							#Ancho total 195
							#Clave Producto 10% del ancho 
							$pdf->setXY(10, $y); 
							$pdf->Cell(19.5, 5, utf8_decode("C. P."), 1, 1, 'C', true);
							#Cantidad 5% del ancho 
							$pdf->setXY(29.5, $y); 
							$pdf->Cell(9.75, 5, utf8_decode("CAN"), 1, 1, 'C', true);
							#Descripcion 25% del ancho 
							$pdf->setXY(39.25, $y); 
							$pdf->Cell(48.75, 5, utf8_decode("DESCRIPCIÓN"), 1, 1, 'C', true);
							#Clave Unidad 5% del ancho 
							$pdf->setXY(88, $y); 
							$pdf->Cell(9.75, 5, utf8_decode("C. U."), 1, 1, 'C', true);
							#Unidad 10% del ancho 
							$pdf->setXY(97.75, $y); 
							$pdf->Cell(19.5, 5, utf8_decode("UNIDAD"), 1, 1, 'C', true);
							#Valor unitario 10% del ancho 
							$pdf->setXY(117.25, $y); 
							$pdf->Cell(19.5, 5, utf8_decode("V. UNIT"), 1, 1, 'C', true);
							#Impuesto 15% del ancho 
							$pdf->setXY(136.75, $y); 
							$pdf->Cell(29.25, 5, utf8_decode("IMPUESTO"), 1, 1, 'C', true);
							#Descuento 10% del ancho 
							$pdf->setXY(166, $y); 
							$pdf->Cell(19.5, 5, utf8_decode("DESC"), 1, 1, 'C', true);
							#Importe 10% del ancho 
							$pdf->setXY(185.5, $y); 
							$pdf->Cell(19.5, 5, utf8_decode("IMPORTE"), 1, 1, 'C', true);
						}
						//Definimos los titulos para la 3.3 cuando es de pago
						else if($this->version == '3.3' && $this->tipoDeComprobante == 'P'){
							if ($this->pagos['Documentos'] !== false) {
								$pdf->setXY(10, $y);
								$pdf->Cell(21.66, 5, utf8_decode("SERIE"), 1, 1, 'C', true);
								$pdf->setXY(31.66, $y);
								$pdf->Cell(21.66, 5, utf8_decode("FOLIO"), 1, 1, 'C', true);
								$pdf->setXY(53.32, $y);
								$pdf->Cell(21.66, 5, utf8_decode("MONEDA"), 1, 1, 'C', true);
								$pdf->setXY(74.98, $y);
								$pdf->Cell(21.66, 5, utf8_decode("TIPO C."), 1, 1, 'C', true);
								$pdf->setXY(96.64, $y);
								$pdf->Cell(21.66, 5, utf8_decode("M. P."), 1, 1, 'C', true);
								$pdf->setXY(118.3, $y);
								$pdf->Cell(21.66, 5, utf8_decode("NUM P."), 1, 1, 'C', true);
								$pdf->setXY(140, $y);
								$pdf->Cell(21.66, 5, utf8_decode("I. S. A."), 1, 1, 'C', true);
								$pdf->setXY(161.62, $y);
								$pdf->Cell(21.66, 5, utf8_decode("I. P."), 1, 1, 'C', true);
								$pdf->setXY(183.28, $y);
								$pdf->Cell(21.66, 5, utf8_decode("I. S. I."), 1, 1, 'C', true);
							}
						} 
						//Definimos los titulos 3.2
						else {
							$pdf->setXY(10, $y); 
							$pdf->Cell(20, 5, utf8_decode("CANTIDAD"), 1, 1, 'C', true);
							$pdf->setXY(30, $y); 
							$pdf->Cell(85, 5, utf8_decode("DESCRIPCIÓN"), 1, 1, 'C', true);
							$pdf->setXY(115, $y); 
							$pdf->Cell(30, 5, utf8_decode("UNIDAD"), 1, 1, 'C', true);
							$pdf->setXY(145, $y); 
							$pdf->Cell(30, 5, utf8_decode("VALOR UNITARIO"), 1, 1, 'C', true);
							$pdf->setXY(175, $y); 
							$pdf->Cell(30, 5, utf8_decode("IMPORTE"), 1, 1, 'C', true);
						}

						$y+=5;

						#Datos
						//Definimos la tipografia
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetFont('arial','',7);
						$conceptoIF = $conceptoIF + $seccion["cantidad"];

						//Definimos los titulos 3.3
						if ($this->version == "3.3" && $this->tipoDeComprobante != 'P') {
							foreach($this->conceptos as $concepto) {
								#Validamos descripcion
								//$descripcion = mb_strimwidth($concepto['Descripcion'], 0, 21, "...");
								
								#Definimos la clave del producto
								$pdf->setXY(10, $y);
								$pdf->Cell(19.5, 4, utf8_decode($concepto['ClaveProdServ']), 0, 1, 'C', false);
								
								#Definimos cantidad
								$pdf->setXY(29.5, $y);
								$pdf->Cell(9.75, 4, utf8_decode($concepto["Cantidad"]), 0, 1, 'C', false);
								
								#Definimos descripcion
								$pdf->setXY(39.25, $y); 
								$pdf->MultiCell(48.75, 4, utf8_decode($concepto["Descripcion"]), 0, 'C');
								$altura_actual = $pdf->GetY();
								$altura_celda = $altura_actual - $y;
								
								#Definimos la clave de unidad
								$pdf->setXY(88, $y);
								$pdf->Cell(9.75, 4, utf8_decode($concepto['ClaveUnidad']),  0, 1, 'C', false);
								
								#Defimos la unidad
								$pdf->setXY(97.5, $y);
								$pdf->MultiCell(19.5, 4, utf8_decode($concepto["Unidad"]), 0, 'C');
								
								#Definimos el valor unitario
								$pdf->setXY(117.25, $y); 
								$pdf->Cell(19.5, 4, utf8_decode($this->stringFormatMoney($concepto["ValorUnitario"])), 0, 1, 'C', false);
								
								$guardaY = $y;
								
								$impuestos = $concepto['Impuestos'];
								$cadenaImpuesto = '';
								foreach ($impuestos as $index => $tipoImpuesto) {
									$length = count($tipoImpuesto);
									for ($i=0; $i < $length; $i++) { 
										#Definimos cadena para el Impuesto
										$cadenaImpuesto .= $concepto['Impuestos'][$index][$i]['Impuesto']." ".$this->iva33($concepto['Impuestos'][$index][$i]['Impuesto'])."\n".$this->stringFormatMoney($concepto['Impuestos'][$index][$i]['Importe'])." ".$concepto['Impuestos'][$index][$i]['TipoFactor'].": ".doubleval($concepto['Impuestos'][$index][$i]['TasaOCuota'])."\n";
									}
								}
								$pdf->setXY(136.75, $y); 
								$pdf->MultiCell(29.25, 4, utf8_decode($cadenaImpuesto), 0, 'C');

								$altura_actual = $pdf->GetY();
								if ($altura_celda < ($altura_actual-$y)) {
									$altura_celda = $altura_actual - $y;
								}

								$y = $guardaY;
								
								#Definimos el Descuento
								if (empty($concepto['Descuento'])) {
									$concepto['Descuento'] = '-';
								}
								$pdf->setXY(166, $y);
								$pdf->Cell(19.5, 4, utf8_decode($concepto['Descuento']),  0, 1, 'C', false);
								
								#Definimos el importe del concepto
								$pdf->setXY(185.5, $y);
								$pdf->Cell(19.5, 4, utf8_decode($this->stringFormatMoney($concepto["Importe"])), 0, 1, 'C', false);

								#Definimos la celda
								$pdf->setXY(10, $y);
								$pdf->Cell(195, $altura_celda, utf8_decode(), 1, 1, 'C', false);

								$y+=($altura_celda);

								if ($y > 270) {
									$pdf->AddPage();
									$pdf->SetMargins(0,0,0);
									$y = 10;
								}
							}
							$y+=1;		
						}
						//Definimos los titulos para la 3.3 cuando es de pago
						else if($this->version == '3.3' && $this->tipoDeComprobante == 'P'){
							#Definimos las celdas
							if ($this->pagos['Documentos'] !== false) {
								foreach ($this->pagos['Documentos'] as $documento => $valor) {
									$pdf->SetFillColor(240, 240, 240);
									$pdf->SetFont('arial','B',7);

									#Definimos id del documento
									$pdf->setXY(10, $y);
									$pdf->Cell(194.9, 4, utf8_decode("DOCUMENTO: ".$valor->IdDocumento), 1, 1, 'C', tue);
									$y+=4;

									$pdf->SetFillColor($r, $g, $b);
									$pdf->SetFont('arial','',7);

									#Definimos serie del documento
									$pdf->setXY(10, $y);
									$pdf->Cell(21.66, 4, utf8_decode($valor->Serie), 1, 1, 'C', false);

									#Definimos folio del documento
									$pdf->setXY(31.66, $y);
									$pdf->Cell(21.66, 4, utf8_decode($valor->Folio), 1, 1, 'C', false);

									#Definimos moneda del documento
									if (isset($valor->MonedaDR)) {
										$moneda = $valor->MonedaDR;
									} else if(isset($valor->MonedaP)){
										$moneda = $valor->MonedaP;
									} else {
										$moneda = "-";
									}
									$pdf->setXY(53.32, $y);
									$pdf->Cell(21.66, 4, utf8_decode($moneda), 1, 1, 'C', false);

									#Definimos tipo cambio del documento
									if (isset($valor->TipoCambioDR)) {
										$tipoCambio = $valor->TipoCambioDR;
									} else if(isset($valor->TipoCambioP)){
										$tipoCambio = $valor->TipoCambioP;
									} else {
										$tipoCambio = "-";
									}
									$pdf->setXY(74.98, $y);
									$pdf->Cell(21.66, 4, utf8_decode($tipoCambio), 1, 1, 'C', false);

									#Definimos metodo de pago del documento
									if (isset($valor->MetodoDePagoDR)) {
										$metodoPago = $valor->MetodoDePagoDR;
									} else if(isset($valor->MetodoDePagoP)){
										$metodoPago = $valor->MetodoDePagoP;
									} else {
										$metodoPago = "-";
									}
									$pdf->setXY(96.64, $y);
									$pdf->Cell(21.66, 4, utf8_decode($metodoPago), 1, 1, 'C', false);

									#Definimos numero de parcialidad del documento
									$pdf->setXY(118.3, $y);
									$pdf->Cell(21.66, 4, utf8_decode($valor->NumParcialidad), 1, 1, 'C', false);

									#Definimos importe saldo anterior del documento
									$pdf->setXY(139.96, $y);
									$pdf->Cell(21.66, 4, utf8_decode($this->stringFormatMoney(doubleval($valor->ImpSaldoAnt))), 1, 1, 'C', false);

									#Definimos importe pagado del documento
									$pdf->setXY(161.62, $y);
									$pdf->Cell(21.66, 4, utf8_decode($this->stringFormatMoney(doubleval($valor->ImpPagado))), 1, 1, 'C', false);

									#Definimos importe saldo insoluto del documento
									$pdf->setXY(183.28, $y);
									$pdf->Cell(21.66, 4, utf8_decode($this->stringFormatMoney(doubleval($valor->ImpSaldoInsoluto))), 1, 1, 'C', false);

									$y+=4;
								}
							}
						} 
						//Definimos los titulos 3.2
						else {
							for($c = $conceptoIl; $c < $conceptoIF; $c++) {
								$concepto = $this->conceptos[$c];
								#Validamos descripcion
								$descripcion = mb_strimwidth($concepto['descripcion'], 0, 50, "...");
								
								#Definimos cantidad 
								$pdf->setXY(10, $y);
								$pdf->Cell(20, 4, utf8_decode($concepto["cantidad"]), 1, 1, 'C', false);
								#Definimos descripcion
								$pdf->setXY(30, $y); 
								$pdf->Cell(85, 4, utf8_decode($descripcion), 1, 1, 'L', false);	
								#Defimos la unidad
								$pdf->setXY(115, $y);
								$pdf->Cell(30, 4, utf8_decode($concepto["unidad"]), 1, 1, 'L', false);
								#Definimos el valor unitario
								$pdf->setXY(145, $y); 
								$pdf->Cell(30, 4, utf8_decode($this->stringFormatMoney($concepto["valorUnitario"])), 1, 1, 'R', false);
								#Definimos el importe del concepto
								$pdf->setXY(175, $y);
								$pdf->Cell(30, 4, utf8_decode($this->stringFormatMoney($concepto["importe"])), 1, 1, 'R', false);
								$conceptoActual = $c;
								$y+=4;
							}
						}
						$y+=2;
						if ($y > 270) {
							$pdf->AddPage();
							$pdf->SetMargins(0,0,0);
							$y = 10;
						}

						#Nomina
						if (isset($this->nomina)) {
							//Definimos tipografia
							$pdf->SetTextColor(255, 255, 255);  
							$pdf->SetFont('arial','B',9);
							
							//Definimos titulos de celda
							$pdf->setXY(10, $y); 
							$pdf->Cell(97.5, 5, utf8_decode("PERCEPCIONES"), 1, 1, 'C', true);
							$pdf->setXY(107.5, $y); 
							$pdf->Cell(97.5, 5, utf8_decode("DEDUCCIONES"), 1, 1, 'C', true);
							
							$y+=5;
							//Definimos tipografia
							$pdf->SetTextColor(255, 255, 255);  
							$pdf->SetFont('arial','B',9);

							//Definimos subtitulos de celda Percepciones
							$pdf->setXY(10, $y);
							$pdf->Cell(19.5, 5, utf8_decode("Tipo P.."), 1, 1, 'C', true);
							$pdf->setXY(29.5, $y);
							$pdf->Cell(19.5, 5, utf8_decode("Clave"), 1, 1, 'C', true);
							$pdf->setXY(49, $y);
							$pdf->Cell(19.5, 5, utf8_decode("Concepto"), 1, 1, 'C', true);
							$pdf->setXY(68.5, $y);
							$pdf->Cell(19.5, 5, utf8_decode("Gravado"), 1, 1, 'C', true);
							$pdf->setXY(88, $y);
							$pdf->Cell(19.5, 5, utf8_decode("Exento"), 1, 1, 'C', true);
							
							//Definimos subtitulos de celda Deducciones
							$pdf->setXY(107.5, $y);
							$pdf->Cell(24.375, 5, utf8_decode("Tipo D.."), 1, 1, 'C', true);
							$pdf->setXY(131.875, $y);
							$pdf->Cell(24.375, 5, utf8_decode("Clave"), 1, 1, 'C', true);
							$pdf->setXY(156.25, $y);
							$pdf->Cell(24.375, 5, utf8_decode("Concepto"), 1, 1, 'C', true);
							$pdf->setXY(180.625, $y);
							$pdf->Cell(24.375, 5, utf8_decode("Importe"), 1, 1, 'C', true);

							$y+=5;

							#Definimos tipografia
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetFont('arial','',8);

							if (isset($this->nomina['percepciones'])) {
								$altoP += 5*count($this->nomina['percepciones']);
							}
							if (isset($this->nomina['deducciones'])) {
								$altoD += 5*count($this->nomina['deducciones']);	
							}
							if ($altoP >= $altoD) {
								$altoNomina = $altoP;
							} else {
								$altoNomina = $altoD;
							}
							#Definimos tamaño de celdas
							$pdf->setXY(10, $y); 
							$pdf->Cell(97.5, $altoNomina, utf8_decode(""), 1, 1, 'L', false);
							$pdf->setXY(107.5, $y); 
							$pdf->Cell(97.5, $altoNomina, utf8_decode(""), 1, 1, 'L', false);
							$y+=1;
							$actualY = $y;
							#Mostramos percepciones
							for ($i=0; $i < count($this->nomina['percepciones']); $i++) {
								$x = 10;
								foreach ($this->nomina['percepciones'][$i] as $campo => $valor) {
									$pdf->setXY($x, $y);
									if ($campo == "Concepto") {
										$valor = mb_strimwidth($valor, 0, 12, "...");
									}
									$pdf->Cell(19.5, 4, utf8_decode($valor), 0, 1, 'C', false);
									$x+=19.5;
								}
								$y+=4;
							}
							$y = $actualY;
							#Mostramos deducciones
							for ($i=0; $i < count($this->nomina['deducciones']); $i++) {
								$x = 107.5;
								foreach ($this->nomina['deducciones'][$i] as $campo => $valor) {
									$pdf->setXY($x, $y);
									if ($campo == "Concepto") {
										$valor = mb_strimwidth($valor, 0, 12, "...");
									}
									$pdf->Cell(24.375, 4, utf8_decode($valor), 0, 0, 'C', false);
									$x+=24.375;
								}
								$y+=4;
							}
							$y=$actualY+$altoNomina+1;
						}

						if ($y > 260) {
							$pdf->AddPage();
							$pdf->SetMargins(0,0,0);
							$y = 10;
						}

						# CFDI's Relacionados
						if (isset($this->CfdiRelacionados)) {
							//Definimos tipografia
							$pdf->SetTextColor(255, 255, 255);  
							$pdf->SetFont('arial','B',9);

							//Definimos titulo
							$pdf->setXY(10, $y);
							$pdf->Cell(195, 5, utf8_decode("CFDI'S RELACIONADOS"), 1, 1, 'C', true);

							$y+=5;

							#Definimos tipografia
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetFont('arial','B',7);
							#Obtenemos numero total de cfdis relacionados
							$altoCfdiRelacion = count($this->CfdiRelacionados['UUIDS']);
							#Le sumamos el tipo de relacion
							$altoCfdiRelacion +=1;
							#Definimos la celda
							$pdf->setXY(10, $y);
							$pdf->Cell(195, ($altoCfdiRelacion*4)+1, utf8_decode(), 1, 0, 'L', false);
							$y+=1;

							#Definimos tipo de relacion
							$pdf->setXY(10, $y);
							$pdf->Cell(195, 4, utf8_decode("Tipo de relación: $this->tipoRelacion ".$this->tipoDeRelacion($this->tipoRelacion)), 0, 0, 'L', false);
							$y+=3;

							#Definimos tipografia
							$pdf->SetFont('arial','',8);
							#Definimos los uuids
							foreach ($this->CfdiRelacionados['UUIDS'] as $index => $uuid) {
								$pdf->setXY(10, $y);
								$pdf->Cell(195, 5, utf8_decode(($index+1).": ".$uuid), 0, 0, 'L', false);
								$y+=4;
							}
							$y+=4;
						}
					break;

					case "totales":
						#4.- Totales
						#Encabezado
						if ($y > 260) {
							$pdf->AddPage();
							$pdf->SetMargins(0,0,0);
							$y = 10;
						}
						if ($this->pagos['Documentos'] === false) {
							$y-=6;
						}
						//Definimos tipografia
						$pdf->SetTextColor(255, 255, 255);  
						$pdf->SetFont('arial','B',9);
						//Definimos titulos de celda
						$pdf->setXY(10, $y); 
						$pdf->Cell(130, 5, utf8_decode("TOTAL CON LETRA"), 1, 1, 'C', true);
						$pdf->setXY(145, $y); 
						$pdf->Cell(60, 5, utf8_decode("TOTAL"), 1, 1, 'C', true);
						$y+=5;

						#Datos
						$tieneTraslados = false;
						$tieneRetenciones = false;
						$altoCelda = 9 + ((count($this->trasladados)+count($this->retenidos)) * 4 );
						if (isset($this->descuento)) {
						  $altoCelda = intval($altoCelda) + 4;
						}
						if((isset($this->trasladados)) && (count($this->trasladados) > 0)){
							$altoCelda = intVal($altoCelda) + 4;
							$tieneTraslados = true;
						}	
						if((isset($this->retenidos)) && (count($this->retenidos) > 0)){
							$altoCelda = intVal($altoCelda) + 4;
							$tieneRetenciones = true;
						}	

						#Definimos tipografia
						$pdf->SetTextColor(0, 0, 0);  
						$pdf->SetFont('arial','',8);
						
						#Definimos tamaño de celdas
						$pdf->setXY(10, $y);
						$pdf->Cell(130, $altoCelda, utf8_decode(""), 1, 1, 'L', false);
						$pdf->setXY(145, $y);    
						$pdf->Cell(60, $altoCelda, utf8_decode(""), 1, 1, 'L', false);
						$y++;

						#Definimos total con letra
						$pdf->setXY(10, $y);    
						$pdf->MultiCell(130, 4, utf8_decode("(".$this->cantidadLetra($this->total,false,true,$this->moneda).")"), 0, 'L');
						
						#Definimos subtotal
						$pdf->setXY(145, $y);
						$pdf->Cell(30, 4, utf8_decode("SUBTOTAL"), 0, 1, 'L', false);
						$pdf->setXY(175, $y);    
						$pdf->Cell(30, 4, utf8_decode($this->stringFormatMoney($this->subTotal)), 0, 1, 'R', false);
						$y+=4;

						#Definimos descuento si es que tiene
						if (isset($this->descuento)) {
						  $pdf->setXY(145, $y);
						  $pdf->Cell(30, 4, utf8_decode("DESCUENTO"), 0, 1, 'L', false);
						  $pdf->setXY(175, $y);
						  $pdf->Cell(30, 4, utf8_decode($this->stringFormatMoney($this->descuento)), 0, 1, 'R', false);
						  $y+=4;  
						}

						#Definimos impuestos trasladados
						if ($tieneTraslados) {
							#Definimos tipografia
							$pdf->SetTextColor(0, 0, 0);  
							$pdf->SetFont('arial','B',7);

							//Añadimos el texto "Impuestos Trasladados"
							$pdf->setXY(145, $y);
							$pdf->Cell(30, 4, utf8_decode("Impuestos Trasladados"), 0, 1, 'L', false);
							$y+=4;
							$pdf->setXY(145, $y);

							#Definimos tipografia
							$pdf->SetTextColor(0, 0, 0); 
							$pdf->SetFont('arial','',8);
							//Añadimos los impuestos
							foreach ($this->trasladados as $impuesto){
								if ($this->version == "3.2") {
								  $pdf->setXY(145, $y); 
								  //Verificamos que la "tasa" tenga valor
								  if (isset($impuesto['tasa'])) {
								  	$tasa = $impuesto['tasa']."%";
								  } else {
								  	$tasa = "";
								  }
								  $pdf->Cell(30, 4, utf8_decode($impuesto["impuesto"]." ".$tasa), 0, 1, 'L', false);
								  $pdf->setXY(175, $y);
								  $importe = utf8_decode($this->stringFormatMoney($impuesto["importe"]));
								  $pdf->Cell(30, 4, $importe, 0, 1, 'R', false);
								  $y+=4;
								} else {
								  $pdf->setXY(145, $y);    
								  //Verificamos que la "tasa" tenga valor
								  if (isset($impuesto['TasaOCuota'])) {
								  	$porcentaje = floatval($impuesto['TasaOCuota'])*100;
								  	$tasa = $porcentaje."%";
								  } else {
								  	$tasa = "";
								  }
								  $impuestoCorregido = $this->corregirImpuesto($impuesto["Impuesto"]);
								  $pdf->Cell(30, 4, utf8_decode($impuestoCorregido." ".$tasa), 0, 1, 'L', false);
								  $pdf->setXY(175, $y);
								  $importe = utf8_decode($this->stringFormatMoney($impuesto["Importe"]));
								  $pdf->Cell(30, 4, $importe, 0, 1, 'R', false);
								  $y+=4;
								}
							}
						}

						#Definimos impuestos retenidos
						if ($tieneRetenciones) {
							#Definimos tipografia
							$pdf->SetTextColor(0, 0, 0); 
							$pdf->SetFont('arial','B',7);
							//Añadimos el texto "Impuestos Trasladados"
							$pdf->setXY(145, $y);
							$pdf->Cell(30, 4, utf8_decode("Impuestos Retenidos"), 0, 1, 'L', false);
							$y+=4;
							$pdf->setXY(145, $y);
							
							#Definimos tipografia
							$pdf->SetTextColor(0, 0, 0);  
							$pdf->SetFont('arial','',8);
							//Añadimos los impuestos
							foreach($this->retenidos as $impuesto) {
								if ($this->version == "3.2") {
								  $pdf->setXY(145, $y);
								  //Verificamos que la "tasa" tenga valor
								  if (isset($impuesto['tasa'])) {
								  	$tasa = $impuesto['tasa']."%";
								  } else {
								  	$tasa = "";
								  }
								  $pdf->Cell(30, 4, utf8_decode($impuesto["impuesto"]." ".$tasa), 0, 1, 'L', false);
								  $pdf->setXY(175, $y);
									$importe = utf8_decode($this->stringFormatMoney($impuesto["importe"]));
								  $pdf->Cell(30, 4, $importe, 0, 1, 'R', false);
								  $y+=4;
								} else {
								  $pdf->setXY(145, $y);
								  //Verificamos que la "tasa" tenga valor
								  if (isset($impuesto['TasaOCuota'])) {
								  	$porcentaje = floatval($impuesto['TasaOCuota'])*100;
								  	$tasa = $porcentaje."%";
								  } else {
								  	$tasa = "";
								  }
								  $impuestoCorregido = $this->corregirImpuesto($impuesto["Impuesto"]);
								  $pdf->Cell(30, 4, utf8_decode($impuestoCorregido." ".$tasa), 0, 1, 'L', false);
								  $pdf->setXY(175, $y);
								  $importe = utf8_decode($this->stringFormatMoney($impuesto["Importe"]));
								  $pdf->Cell(30, 4, $importe, 0, 1, 'R', false);
								  $y+=4;
								}
							}
						}

						#Definimos el total
						$pdf->setXY(145, $y);
						$pdf->Cell(30, 4, utf8_decode("TOTAL"), 0, 1, 'L', false);
						$pdf->setXY(175, $y);
						$pdf->Cell(30, 4, utf8_decode($this->stringFormatMoney($this->total)), 0, 1, 'R', false) ;
						$y+=6;
					break;

					case "coccds":
						#5.- CADENA ORIGINAL DEL COMPLEMENTO DE CERTIFICACIÓN DIGITAL DEL SAT
						//Para los CFDI 3.2
						if ($this->version == '3.2') {
						$coccd = "||". $this->tfd['version'] . "|" . $this->tfd['UUID'] . "|" . $this->tfd["FechaTimbrado"] ."|". $this->sello ."|". $this->tfd["noCertificadoSAT"] ."||";	
						} 
						//Para los CFDI 3.3
						else {
							$coccd = "||".$this->tfd['Version']."|" .$this->tfd['UUID']."|".$this->tfd["FechaTimbrado"]."|".$this->sello."|". $this->tfd["NoCertificadoSAT"] ."||";	
						}

						if ($y > 260) {
							$pdf->AddPage();
							$pdf->SetMargins(0,0,0);
							$y = 10;
						}

						#Definimos titulo
						$pdf->SetTextColor(255, 255, 255);  
						$pdf->SetFont('arial','B',9);

						$pdf->setXY(10, $y);
						$pdf->Cell(195, 5, utf8_decode("CADENA ORIGINAL DEL COMPLEMENTO DE CERTIFICACIÓN DIGITAL DEL SAT"), 1, 1, 'C', true);
						$y+=5;
						$pdf->SetTextColor(0, 0, 0);  $pdf->SetFont('arial','',8);
						$pdf->setXY(10, $y);
						$pdf->Cell(195, 18, utf8_decode(""), 1, 1, 'L', false);
						$y++;
						#Definimos la CADENA ORIGINAL DEL COMPLEMENTO DE CERTIFICACIÓN DIGITAL
						$pdf->setXY(10, $y);
						$pdf->MultiCell(195, 4, utf8_decode($coccd), 0, 'L');
						$y+=19;
					break;

					case "sd_emisor":
						#6.- SELLO DIGITAL DEL EMISOR

						if ($y > 260) {
							$pdf->AddPage();
							$pdf->SetMargins(0,0,0);
							$y = 10;
						}

						#Definimos titulo
						$pdf->SetTextColor(255, 255, 255);
						$pdf->SetFont('arial','B',9);
						#Definimos celda
						$pdf->setXY(10, $y);    
						$pdf->Cell(195, 5, utf8_decode("SELLO DIGITAL DEL EMISOR"), 1, 1, 'C', true);
						$y+=5;

						#Definimos tipografia
						$pdf->SetTextColor(0, 0, 0);  
						$pdf->SetFont('arial','',8);

						#Definimos Sello digital del emisor
						$pdf->setXY(10, $y);
						$pdf->Cell(195, 14, utf8_decode(""), 1, 1, 'L', false);
						$y++;
						$pdf->setXY(10, $y);
						$pdf->MultiCell(195, 4, utf8_decode($this->sello), 0, 'L');
						$y+=15;
					break;

					case "sd_sat":
						#7.- SELLO DIGITAL DEL SAT
						
						if ($y > 260) {
							$pdf->AddPage();
							$pdf->SetMargins(0,0,0);
							$y = 10;
						}

						#Definimos tipografia
						$pdf->SetTextColor(255, 255, 255);
						$pdf->SetFont('arial','B',9);
						
						#Definimos celda
						$pdf->setXY(10, $y);
						$pdf->Cell(195, 5, utf8_decode("SELLO DIGITAL DEL SAT"), 1, 1, 'C', true);
						$y+=5;

						#Definimos tipografia
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetFont('arial','',8);
						#Definimos celda
						$pdf->setXY(10, $y);
						$pdf->Cell(195, 14, utf8_decode(""), 1, 1, 'L', false);
						$y++;

						$pdf->setXY(10, $y);
						//Para los CFDI 3.2
						if ($this->version == "3.2") {
							$pdf->MultiCell(195, 4, utf8_decode($this->tfd["selloSAT"]), 0, 'L');
						
						//Para los CFDI 3.3 
						} else {
							$pdf->MultiCell(195, 4, utf8_decode($this->tfd["SelloSAT"]), 0, 'L');
						}
						$y+=15;
					break;

					case "qr":
						#7.- QR,Condiciones y especificaciones
						//https://www.reachcore.com/qr-para-la-factura-electronica/
						if ($y > 255) {
							$pdf->AddPage();
							$pdf->SetMargins(0,0,0);
							$y = 10;
						}

						#Definimos cadena para QR
						$qrString = "?re=" . $this->rfcEmisor . "&rr=" . $this->rfcReceptor . "&tt=" . $this->cantidad10_6($this->total) . "&id=" . $this->tfd['UUID'];

						#Definimos tipografia titulo
						$pdf->SetTextColor(255, 255, 255);  
						$pdf->SetFont('arial','B',9);

						#Definimos celda
						$pdf->setXY(10, $y);
						$pdf->Cell(195, 5, utf8_decode(""), 1, 1, 'C', true);
						$y+=5;

						#Definimos tipografia cuerpo
						$pdf->SetTextColor(0, 0, 0);  
						$pdf->SetFont('arial','',8);

						#Definimos celda
						$pdf->setXY(10, $y);
						$pdf->Cell(195, 42, utf8_decode(""), 1, 1, 'L', false);
						$y++;
						$yBeforeQR = $y;

						$qrcode = new QRcode(utf8_encode($qrString), "H");
						//$qrcode->disableBorder();
						$qrcode->displayFPDF($pdf, 12, $y+1, 32,  array(255, 255, 255), array(0, 0, 0));
						$pdf->SetDrawColor($r, $g, $b); $pdf->SetFillColor($r,   $g,   $b);
						$y = $yBeforeQR;

						#Definimos tipografia cuerpo
						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetFont('arial','',7);
						
						#Definimos fecha y hora de emisión
						$pdf->setXY(45, $y); 
						$pdf->Cell(50, 4, utf8_decode("Fecha y hora de emisión"), 0, 1, 'L', false);
						$pdf->setXY(95, $y);
						$pdf->Cell(70, 4, utf8_decode($this->dateFormatString($this->fecha)), 0, 1, 'L', false);
						$y+=4;

						#Definimos fecha y hora de certificación
						$pdf->setXY(45, $y);
						$pdf->Cell(50, 4, utf8_decode("Fecha y hora de certificación:"), 0, 1, 'L', false);
						$pdf->setXY(95, $y);
						$pdf->Cell(70, 4, utf8_decode($this->dateFormatString($this->tfd["FechaTimbrado"])), 0, 1, 'L', false);
						$y+=4;

						#Definimos número de serie del certificado del emisor
						$pdf->setXY(45, $y);
						$pdf->Cell(50, 4, utf8_decode("No. de serie del certificado del emisor:"), 0, 1, 'L', false);
						$pdf->setXY(95, $y);
						$pdf->Cell(70, 4, utf8_decode($this->noCertificado), 0, 1, 'L', false);	
						$y+=4;

						#Definimos número de serie certificado del SAT
						$pdf->setXY(45, $y);
						$pdf->Cell(50, 4, utf8_decode("No. de serie del certificado del SAT:"), 0, 1, 'L', false);
						$pdf->setXY(95, $y);
						//Para CFDI 3.2
						if ($this->version == "3.2") {
							$pdf->Cell(70, 4, utf8_decode($this->tfd["noCertificadoSAT"]), 0, 1, 'L', false);

						//Para CFDI 3.3
						} else {
							$pdf->Cell(70, 4, utf8_decode($this->tfd["NoCertificadoSAT"]), 0, 1, 'L', false);
						}
						$y+=4;

						#Definimos el tipo de comprobante
						if ($this->version == '3.3') {
							$tipoDeComprobante = $this->tipoDeComprobante." ".$this->tipoDeComprobante($this->tipoDeComprobante);
						} else {
							$tipoDeComprobante = $this->tipoDeComprobante;
						}
						$pdf->setXY(45, $y);
						$pdf->Cell(50, 4, utf8_decode("Tipo de comprobante:"), 0, 1, 'L', false);
						$pdf->setXY(95, $y);
						$pdf->Cell(70, 4, utf8_decode($tipoDeComprobante), 0, 1, 'L', false);
						$y+=4;

						if ($this->tipoDeComprobante != 'P') {
							#Definimos el metodo de pago
							$pdf->setXY(45, $y);
							$pdf->Cell(50, 4, utf8_decode("Método de pago:"), 0, 1, 'L', false);
							$pdf->setXY(95, $y);
							$pdf->Cell(70, 4, utf8_decode($this->metodoDePago), 0, 1, 'L', false);
							$y+=4;
						}

						#Definimos la forma de pago
						if ($this->version == '3.3' && $this->tipoDeComprobante != 'P') {
							$formaDePago = $this->formaDePago." ".$this->formaDePago($this->formaDePago);
						} else if($this->tipoDeComprobante == 'P'){
							$formaDePago = $this->pagos['Atributos']['FormaDePagoP']." ".$this->formaDePago($this->pagos['Atributos']['FormaDePagoP']);
						} else {
							$formaDePago = $this->formaDePago;
						}
						$pdf->setXY(45, $y);
						$pdf->Cell(50, 4, utf8_decode("Forma de pago:"), 0, 1, 'L', false);
						$pdf->setXY(95, $y);
						$pdf->Cell(70, 4, utf8_decode($formaDePago), 0, 1, 'L', false);
						$y+=4;


						#Definimos el tipo de moneda
						if ($this->tipoDeComprobante != 'P') {
							$pdf->setXY(45, $y);
							$pdf->Cell(50, 4, utf8_decode("Moneda:"), 0, 1, 'L', false);
							$pdf->setXY(95, $y);
							if (!isset($this->moneda)) {
								$this->moneda = "-"; 
							}
							$pdf->Cell(70, 4, utf8_decode($this->moneda), 0, 1, 'L', false);
							$y+=4;	
						}
						if($this->TipoCambio > 0){
							if ($this->tipoDeComprobante != 'P') {
								$pdf->setXY(45, $y);
								$pdf->Cell(50, 4, utf8_decode("Tipo de Cambio:"), 0, 1, 'L', false);
								$pdf->setXY(95, $y);
								if (!isset($this->TipoCambio)) {
									$this->TipoCambio = "-"; 
								}
								$pdf->Cell(70, 4, utf8_decode($this->TipoCambio), 0, 1, 'L', false);
								$y+=4;	
							}
						}
						

						#Definimos el lugar de expidicion
						$pdf->setXY(45, $y);
						$pdf->Cell(50, 4, utf8_decode("Lugar de expedición:"), 0, 1, 'L', false);
						$pdf->setXY(95, $y);
						if (!isset($this->lugarExpedicion)) {
							$this->lugarExpedicion = "-"; 
						}
						$pdf->Cell(70, 4, utf8_decode($this->lugarExpedicion), 0, 1, 'L', false);
						$y+=6.5;

						#Nodo Observaciones
						if (isset($this->obser) && !empty($this->obser)) {
							if ($y > 250) {
								$pdf->AddPage();
								$pdf->SetMargins(0,0,0);
								$y = 10;
							} else {
								$y+=2;
							}
							#Definimos tipografia titulo
							$pdf->SetTextColor(255, 255, 255);
							$pdf->SetFont('arial','B',9);
							
							$pdf->setXY(10, $y);
							$pdf->Cell(195, 4, utf8_decode("OBSERVACIONES"), 1, 1, 'C', true);
							$y+=4;
							#Definimos tipografia cuerpo
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetFont('arial','',8);

							#Definimos la celda
							$pdf->setXY(10, $y);
							$pdf->Cell(195, 16, utf8_decode(""), 1, 1, 'L', false);
							$y+=1;

							#Añadimos las observaciones al contenedor
							$pdf->setXY(10, $y);
							$pdf->MultiCell(195, 4, utf8_decode($this->obser), 0, 'L');
							$y+=18;

						}
					break;
				}
			}

			#N.- Pie
			$pdf->setXY(10, 264);
			$pdf->Cell(195, 6+($xxxx+16) , utf8_decode("ESTE DOCUMENTO ES UNA REPRESENTACIÓN IMPRESA DE UN CFDI"), 0, 1, 'C', false);
			$pdf->setXY(185, 264);
			$pdf->Cell(20, 6+($xxxx+16), utf8_decode("Página $paginaActual de " . count($hojas)), 0, 1, 'R', false);
			/*$pdf->Line(10, 10, 205.9, 10);
			$pdf->Line(205.9, 10, 205.9, 269.4);
			$pdf->Line(10, 269.4, 205.9, 269.4);
			$pdf->Line(10, 10,10, 269.4);
			$pdf->Line(7, 10,7, 75);
			#N.- Salida*/
		}
		//echo 'EXITOEXITOEXITO';

		$strPDFFileName = str_replace('.xml','.pdf',$this->ruta2);
		if($this->namexml){
			$namexml = str_replace('.xml','.pdf', $this->namexml);
			if($this->caja==1){
				$pdf->Output('F','../../../modulos/facturas/'.$namexml,true);
				$pdf->Output('I','../../../modulos/facturas/'.$namexml,true);
			}elseif($this->caja==2){
				$pdf->Output('F','../webapp/modulos/facturas/'.$namexml,true);
				//$pdf->Output('I','../modulos/facturas/'.$namexml,true);
				echo json_encode( array('estatus' => true));
			}elseif($this->caja==3){ //era 10
				
				$pdf->Output('F','../../modulos/facturas/'.$namexml,true);
				//$pdf->Output('I','../modulos/facturas/'.$namexml,true);
				echo json_encode( array('estatus' => true));
			
			}else{
				$pdf->Output('I','../../../modulos/cont/xmls/facturas/temporales/pdfnominas/'.$namexml,true);
				
			}
		}else{
			$pdf->Output('I',$strPDFFileName,true);
			
		}
	}

	private function dateFormatString($d)
	{
		//2016-08-29T16:45:22
		$meses = array( "01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre" );
		$result =  substr($d, 8, 2) . " de " . $meses[substr($d, 5, 2)] . " del " . substr($d, 0, 4) . " " . substr($d, 11, 8);
		return $result;
	}

	private function obtenerObjetoImpuestos(){
		if($this->caja==1){
			require ('../../../netwarelog/webconfig.php');
		}elseif($this->caja==3){
			require ('../../netwarelog/webconfig.php');
		}else{
			require ('../../../netwarelog/webconfig.php');
		}
		
		$conNMDB = mysqli_connect($servidor,$usuariobd,$clavebd, $bd);
		mysqli_query($conNMDB,"SET NAMES '" . DB_CHARSET . "'");
		$myQuery = "SELECT claveSat, descripcion FROM cont_impuestos";
		
		$result = mysqli_query($conNMDB, $myQuery);
		mysqli_close($conNMDB);
		return $result;
	}

	private function corregirImpuesto($impuestoCorregir){
		$impuestos = $this->obtenerObjetoImpuestos();
		$impuestoCorregido = '';
		$validar = false;
	  while ($impuesto = mysqli_fetch_assoc($impuestos)) {
			foreach ($impuesto as $atributo => $valor) {
				if ($atributo == "claveSat") {
					if ($valor == $impuestoCorregir) {
						$validar = true;
					}
				}
				if ($validar) {
					if ($atributo == "descripcion") {
						$impuestoCorregido = $valor;
						$validar = false;
					}
				}
			}	
		}
		return $impuestoCorregido;
	}

	public function iva33($clave){
		switch ($clave) {
			case '001':
				return 'ISR';
				break;
			case '002':
				return 'IVA';
				break;
			case '003':
				return 'IEPS';
				break;
			default:
				return '';
				break;
		}
	}

	public function tipoDeComprobante($tipo){
		switch ($tipo) {
			case 'I':
				return 'Ingreso';
				break;
			case 'E':
				return 'Egreso';
				break;
			case 'T':
				return 'Traslado';
				break;
			case 'N':
				return 'Nómina';
				break;
			case 'P':
				return 'Pago';
				break;
			default:
				return "";
				break;
		}
	}

	public function tipoDeRelacion($tipo){
		switch ($tipo) {
			case '01':
				return 'Nota de crédito de los documentos relacionados';
				break;
			case '02':
				return 'Nota de débito de los documentos relacionados';
				break;
			case '03':
				return 'Devolución de mercancía sobre facturas o traslados previos';
				break;
			case '04':
				return 'Sustitución de los CFDI previos';
				break;
			case '05':
				return 'Traslados de mercancias facturados previamente';
				break;
			case '06':
				return 'Factura generada por los traslados previos';
				break;
			case '07':
				return 'CFDI por aplicación de anticipo';
				break;
			default:
				return "";
				break;
		}
	}

	public function usoCFDI($usoCFDI){
		switch ($usoCFDI) {
			case 'G01':
				return 'Adquisición de mercancias';
				break;
			case 'G02':
				return 'Devoluciones, descuentos o bonificaciones';
				break;
			case 'G03':
				return 'Gastos en general';
				break;
			case 'I01':
				return 'Construcciones';
				break;
			case 'I02':
				return 'Mobilario y equipo de oficina para inversiones';
				break;
			case 'I03':
				return 'Equipo de transporte';
				break;
			case 'I04':
				return 'Equipo de computo y accesorios';
				break;
			case 'I05':
				return 'Dados, troqueles, moldes, matrices y herramental';
				break;
			case 'I06':
				return 'Comunicaciones telefónicas';
				break;
			case 'I07':
				return 'Comunicaciones satelitales';
				break;
			case 'I08':
				return 'Otra maquinaria y equipo';
				break;
			case 'D01':
				return 'Honorarios médicos, dentales, y gastos hospitalarios';
				break;
			case 'D02':
				return 'Gastos médicos por incapacidad o discapacidad';
				break;
			case 'D03':
				return 'Gastos funerales';
				break;
			case 'D04':
				return 'Donativos';
				break;
			case 'D05':
				return 'Intereses reales efectivamente pagados por créditos hipotecarios (casa habitación).';
				break;
			case 'D06':
				return 'Aportaciones voluntarias al SAR';
				break;
			case 'D07':
				return 'Primas por seguros de gastos médicos';
				break;
			case 'D08':
				return 'Gastos de transportación escolar obligatoria';
				break;
			case 'D09':
				return 'Depósitos en cuentas para el ahorro, primas que tengan como base planes de pensiones.';
				break;
			case 'D10':
				return 'Pagos por servicios educativos(colegiaturas)';
				break;
			case 'P01':
				return 'Por definir';
				break;
			default:
				return "";
				break;
		}
	}

	private function regimenFiscal($regimen){
		switch ($regimen) {
			case '601':
				return 'General de Ley Personas Morales';
				break;
			case '603':
				return 'Personas Morales con Fines no Lucrativos';
				break;
			case '605':
				return 'Sueldos y Salarios e Ingresos Asimilados a Salarios';
				break;
			case '606':
				return 'Arrendamiento';
				break;
			case '607':
				return 'Régimen de Enajenación o Adquisición de Bienes';
				break;
			case '608':
				return 'Demás ingresos';
				break;
			case '609':
				return 'Consolidación';
				break;
			case '610':
				return 'Residentes en el Extranjero sin Establecimiento Permanente en México';
				break;
			case '611':
				return 'Ingresos por Dividendos (socios y accionistas)';
				break;
			case '612':
				return 'Personas Físicas con Actividades Empresariales y Profesionales';
				break;
			case '614':
				return 'Ingresos por intereses';
				break;
			case '615':
				return 'Régimen de los ingresos por obtención de premios';
				break;
			case '616':
				return 'Sin obligaciones fiscales';
				break;
			case '620':
				return 'Sociedades Cooperativas de Producción que optan por diferir sus ingresos';
				break;
			case '621':
				return 'Incorporación Fiscal';
				break;
			case '622':
				return 'Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras';
				break;
			case '623':
				return 'Opcional para Grupos de Sociedades';
				break;
			case '624':
				return 'Coordinados';
				break;
			case '628':
				return 'Hidrocarburos';
				break;
			case '629':
				return 'De los Regímenes Fiscales Preferentes y de las Empresas Multinacionales';
				break;
			case '630':
				return 'Enajenación de acciones en bolsa de valores';
				break;
			default:
				return "";
				break;
		}
	}

	public function formaDePago($forma){
		switch ($forma) {
			case '01':
				return 'Efectivo';
				break;
			case '02':
				return 'Cheque nominativo';
				break;
			case '03':
				return 'Transferencia electrónica de fondos';
				break;
			case '04':
				return 'Tarjeta de crédito';
				break;
			case '05':
				return 'Monedero electrónico';
				break;
			case '06':
				return 'Dinero electrónico';
				break;
			case '08':
				return 'Vales de despensa';
				break;
			case '12':
				return 'Dación en pago';
				break;
			case '13':
				return 'Pago por subrogación';
				break;
			case '14':
				return 'Pago por consignación';
				break;
			case '15':
				return 'Condonación';
				break;
			case '17':
				return 'Compensación';
				break;
			case '23':
				return 'Novación';
				break;
			case '24':
				return 'Confunsión';
				break;
			case '25':
				return 'Remisión de deuda';
				break;
			case '26':
				return 'Prescripción o caducidad';
				break;
			case '27':
				return 'A satisfacción del acreedor';
				break;
			case '28':
				return 'Tarjeta de débito';
				break;
			case '29':
				return 'Tarjeta de servicios';
				break;
			case '30':
				return 'Aplicación de anticipos';
				break;
			case '99':
				return 'Por definir';
				break;				
			default:
				return "";
				break;
		}
	}

	private function cantidadLetra($num, $fem = false, $dec = true,$moneda) {
		//http://www.thezilus.com/blog/wp-content/uploads/2010/06/num2letras.txt
		$matuni[2]  = "dos";
		$matuni[3]  = "tres";
		$matuni[4]  = "cuatro";
		$matuni[5]  = "cinco";
		$matuni[6]  = "seis";
		$matuni[7]  = "siete";
		$matuni[8]  = "ocho";
		$matuni[9]  = "nueve";
		$matuni[10] = "diez";
		$matuni[11] = "once";
		$matuni[12] = "doce";
		$matuni[13] = "trece";
		$matuni[14] = "catorce";
		$matuni[15] = "quince";
		$matuni[16] = "dieciseis";
		$matuni[17] = "diecisiete";
		$matuni[18] = "dieciocho";
		$matuni[19] = "diecinueve";
		$matuni[20] = "veinte";
		$matunisub[2] = "dos";
		$matunisub[3] = "tres";
		$matunisub[4] = "cuatro";
		$matunisub[5] = "quin";
		$matunisub[6] = "seis";
		$matunisub[7] = "sete";
		$matunisub[8] = "ocho";
		$matunisub[9] = "nove";

		$matdec[2] = "veint";
		$matdec[3] = "treinta";
		$matdec[4] = "cuarenta";
		$matdec[5] = "cincuenta";
		$matdec[6] = "sesenta";
		$matdec[7] = "setenta";
		$matdec[8] = "ochenta";
		$matdec[9] = "noventa";
		$matsub[3]  = 'mill';
		$matsub[5]  = 'bill';
		$matsub[7]  = 'mill';
		$matsub[9]  = 'trill';
		$matsub[11] = 'mill';
		$matsub[13] = 'bill';
		$matsub[15] = 'mill';
		$matmil[4]  = 'millones';
		$matmil[6]  = 'billones';
		$matmil[7]  = 'de billones';
		$matmil[8]  = 'millones de billones';
		$matmil[10] = 'trillones';
		$matmil[11] = 'de trillones';
		$matmil[12] = 'millones de trillones';
		$matmil[13] = 'de trillones';
		$matmil[14] = 'billones de trillones';
		$matmil[15] = 'de billones de trillones';
		$matmil[16] = 'millones de billones de trillones';

		//Zi hack
		$float=explode('.',$num);
		$num=$float[0];

		$num = trim((string)@$num);
		if ($num[0] == '-') {
			$neg = 'menos ';
			$num = substr($num, 1);
		}else
			$neg = '';
		while ($num[0] == '0') $num = substr($num, 1);
		if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num;
		$zeros = true;
		$punt = false;
		$ent = '';
		$fra = '';
		for ($c = 0; $c < strlen($num); $c++) {
			$n = $num[$c];
			if (! (strpos(".,'''", $n) === false)) {
				if ($punt) break;
				else{
					$punt = true;
					continue;
				}

			}elseif (! (strpos('0123456789', $n) === false)) {
				if ($punt) {
					if ($n != '0') $zeros = false;
					$fra .= $n;
				}else

					$ent .= $n;
			}else

				break;

		}
		$ent = '     ' . $ent;
		if ($dec and $fra and ! $zeros) {
			$fin = ' coma';
			for ($n = 0; $n < strlen($fra); $n++) {
				if (($s = $fra[$n]) == '0')
					$fin .= ' cero';
				elseif ($s == '1')
					$fin .= $fem ? ' una' : ' un';
				else
					$fin .= ' ' . $matuni[$s];
			}
		}else
			$fin = '';
		if ((int)$ent === 0) return 'Cero ' . $fin;
		$tex = '';
		$sub = 0;
		$mils = 0;
		$neutro = false;
		while ( ($num = substr($ent, -3)) != '   ') {
			$ent = substr($ent, 0, -3);
			if (++$sub < 3 and $fem) {
				$matuni[1] = 'una';
				$subcent = 'as';
			}else{
				$matuni[1] = $neutro ? 'un' : 'uno';
				$subcent = 'os';
			}
			$t = '';
			$n2 = substr($num, 1);
			if ($n2 == '00') {
			}elseif ($n2 < 21)
				$t = ' ' . $matuni[(int)$n2];
			elseif ($n2 < 30) {
				$n3 = $num[2];
				if ($n3 != 0) $t = 'i' . $matuni[$n3];
				$n2 = $num[1];
				$t = ' ' . $matdec[$n2] . $t;
			}else{
				$n3 = $num[2];
				if ($n3 != 0) $t = ' y ' . $matuni[$n3];
				$n2 = $num[1];
				$t = ' ' . $matdec[$n2] . $t;
			}
			$n = $num[0];
			if ($n == 1) {
				$t = ' ciento' . $t;
			}elseif ($n == 5){
				$t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
			}elseif ($n != 0){
				$t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
			}
			if ($sub == 1) {
			}elseif (! isset($matsub[$sub])) {
				if ($num == 1) {
					$t = ' mil';
				}elseif ($num > 1){
					$t .= ' mil';
				}
			}elseif ($num == 1) {
				$t .= ' ' . $matsub[$sub] . '?n';
			}elseif ($num > 1){
				$t .= ' ' . $matsub[$sub] . 'ones';
			}
			if ($num == '000') $mils ++;
			elseif ($mils != 0) {
				if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];
				$mils = 0;
			}
			$neutro = true;
			$tex = $t . $tex;
		}
		$tex = $neg . substr($tex, 1) . $fin;
		//Zi hack --> return ucfirst($tex);
		if($moneda=='USD'){
			$mon = 'dolares';
			$mon2 = 'USD';
		}else{
			$mon = 'pesos';
			$mon2 = 'M.N.';
		}
		$end_num=ucfirst($tex).' '.$mon.' '.$float[1].'/100 '.$mon2;
		return $end_num;
	}

	private function stringFormatMoney($cantidad)
	{
		$negativo = ($cantidad < 0 ?  "-" : "" );
		$cantidad = number_format($cantidad, 2, ".", ",");
		$cantidad = $negativo . "$" . $cantidad;
		return $cantidad;
	}

	private function cantidad10_6($cantidad)
	{
		settype($cantidad, "string");
		$cantidad = number_format($cantidad, 6, ".", "");
		settype($cantidad, "string");
		$posPunto = strpos($cantidad, ".");
		$cantidad =  str_pad($cantidad, 17, "0", STR_PAD_LEFT);
		return $cantidad;
	}

	public function crearHojas($conceptos, $traslados) {
		$hojas = array();
		$pivote = 0;
		$hoja = 0;

		#Si no cabe hacer mas grande el numero de area de trabajo.
		$areaTrabajoMax = 360;
		$areaTrabajo = $areaTrabajoMax;
		/* [0] encabezado   [1] registros  [2] totales  [3] coccds   [4] sd_emisor  [5] sd_sat   [6] qr */

		#ENCABEZADO
		$areaTrabajo-= 63;
		$hojas[] = array();
		$hojas[$hoja][] = array( "tipo" => "encabezado");

		$areaTrabajo-=2;

		#Conceptos

		if( $areaTrabajo == 0 ){
			$areaTrabajo = $areaTrabajoMax;
			$hojas[] = array();
			$hoja++;
		}

		$conceptosRestantes = $conceptos;
		while( $conceptosRestantes > 0 ) {
			if( $areaTrabajo < 4 ) {
				$areaTrabajo = $areaTrabajoMax;
				$hojas[] = array();
				$hoja++;
			}

			if ($areaTrabajo == (5 + ($conceptosRestantes * 4))) {
				$areaTrabajo = 0;
				$hojas[$hoja][] = array("tipo" => "conceptos", "cantidad" => $conceptosRestantes );
				$conceptosRestantes = 0;
			}
			else {
				if($areaTrabajo > (5 + ($conceptosRestantes * 4))) {
					$areaTrabajo -= (5 + ($conceptosRestantes * 4));
					$hojas[$hoja][] = array("tipo" => "conceptos", "cantidad" => $conceptosRestantes);
					$conceptosRestantes = 0;
				}
				else {
					// osea si $areaTrabajo < ( 5 + ($conceptosRestantes * 4) )
					$registrosParaEstaHoja = ( $areaTrabajo / 4 );
					if($areaTrabajo % 4 != 0) {
						settype($registrosParaEstaHoja, "String");
						$registrosParaEstaHoja = strstr($registrosParaEstaHoja, ".", true);
						settype($registrosParaEstaHoja, "integer");
					}
					$hojas[$hoja][] = array( "tipo" => "conceptos", "cantidad" => $registrosParaEstaHoja );
					$conceptosRestantes -= $registrosParaEstaHoja;
					$areaTrabajo -= ( $registrosParaEstaHoja * 4 );
				}
			}
		}

		$areaTrabajo-=2;

		#TOTALES

		if( ! ( $areaTrabajo >= ( 5 + 14 + (4 * $traslados) )) )
		{
			$areaTrabajo = $areaTrabajoMax;
			$hojas[] = array();
			$hoja++;
		}
		$areaTrabajo -= ( 5 + 14 + (4 * $traslados) );
		$hojas[$hoja][] = array( "tipo" => "totales");

		$areaTrabajo -=2;

		#coccds

		if( ! ( $areaTrabajo >= ( 5 + 14 )) )
		{
			$areaTrabajo = $areaTrabajoMax;
			$hojas[] = array();
			$hoja++;
		}
		$areaTrabajo -= ( 5 + 14 );
		$hojas[$hoja][] = array( "tipo" => "coccds");

		$areaTrabajo -=2;

		#SD_EMISOR

		if( ! ( $areaTrabajo >= ( 5 + 10 )) )
		{
			$areaTrabajo = $areaTrabajoMax;
			$hojas[] = array();
			$hoja++;
		}
		$areaTrabajo -= ( 5 + 10 );
		$hojas[$hoja][] = array( "tipo" => "sd_emisor");

		$areaTrabajo -=2;

		#SD_SAT

		if( ! ( $areaTrabajo >= ( 5 + 10 )) )
		{
			$areaTrabajo = $areaTrabajoMax;
			$hojas[] = array();
			$hoja++;
		}
		$areaTrabajo -= ( 5 + 10 );
		$hojas[$hoja][] = array( "tipo" => "sd_sat");

		$areaTrabajo -=2;

		#QR

		if( ! ( $areaTrabajo >= ( 5 + ( 1 + 4 + 4 + 4 + 4 + 4 + 4 + 4 + 4 + 1 ) )) )
		{
			$areaTrabajo = $areaTrabajoMax;
			$hojas[] = array();
			$hoja++;
		}
		$areaTrabajo -= ( 5 + ( 1 + 4 + 4 + 4 + 4 + 4 + 4 + 4 + 4 + 1 ) );
		$hojas[$hoja][] = array( "tipo" => "qr");

		return $hojas;
	}
}
