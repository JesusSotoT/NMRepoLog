<?php
//echo $rfc_cliente.' '.$cer_cliente.' '.$pathdc;

	/* UTF8 AZURIAN
	======================================================= */
	$azurian = array_encode($azurian);

	if($bloqueo==1){
		$JSON = array('success' =>5, 'azurian' =>json_encode($azurian), 
			'error'=>5, 
			'mensaje'=>'Facturar ticket pendiente', 
			'dump'=>$valida,
			'idVenta'=>$idVenta);
		echo json_encode($JSON);
		exit();
	}

	function array_encode(&$arr){
	  array_walk_recursive($arr, function(&$val, $key){
	  	$val=trim($val);
	  	if(!mb_check_encoding($val, 'UTF-8')) $val = utf8_encode($val); 
	  });

	  return $arr;
	}

	function numericos($var,$dot){
		if(!preg_match('/[^\d'.$dot.']/', $var)) {
			return 1;
		}else{
			return 0;
		}
	}

	function validaRFC($rfc){
		if (preg_match('/[A-Z,Ñ,&]{3,4}[0-9]{2}[0-1][0-9][0-3][0-9][A-Z,0-9]?[A-Z,0-9]?[0-9,A-Z]?/', $rfc)) {
			return 1;
		}else{
			return 0;
		}
	}

	function validacion($clave,$var){
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
	function generaIniVigencia($rfc_cliente,$cer_cliente,$pathdc){
		$comando='openssl x509 -inform DER -in '.$cer_cliente.' -noout -startdate > "'.$pathdc.'/iniVigencia.txt"';
    	exec($comando);
    	return 1;

	}
	function generaFinVigencia($rfc_cliente,$cer_cliente,$pathdc){
		$comando='openssl x509 -inform DER -in '.$cer_cliente.' -noout -enddate > "'.$pathdc.'/finVigencia.txt"';
    	exec($comando);
    	return 1;
 
	}

	function generaPem($rfc_cliente,$key_cliente,$pwd_cliente,$pathdc){
		$pem = $pathdc.'/'.$rfc_cliente.'.pem';
		$comando="openssl pkcs8 -inform DER -in ".$key_cliente." -passin pass:'".$pwd_cliente."' -out ".$pem."";
    	exec($comando);

    	$validacion = validacion('pem',$pem);
    	return $validacion; 
	}

	function generaNoCertificado($rfc_cliente,$cer_cliente,$pathdc){
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

	function generaCertificado($rfc_cliente,$cer_cliente,$pathdc){
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

	function generaCadenaOriginal($cad,$dteTrailer,$rfc_cliente,$pathdc){
		$archivo = fopen($pathdc.'/CO' . $dteTrailer . '.txt','w');
		fwrite($archivo,$cad);
		fclose($archivo);
		return 1;
	
	}
	
	function generaSello($rfc_cliente,$dteTrailer,$pathdc){
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

	

	/* VALIDACION RFC'S
	======================================================= */
	$r1=validaRFC($azurian['Emisor']['Rfc']);
	$r2=validaRFC($azurian['Receptor']['Rfc']);
	if($r1==0){
		$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
			'error'=>113, 
			'mensaje'=>'El RFC Emisor esta mal formado'.$azurian['Emisor']['Rfc'], 
			'dump'=>'',
			'idVenta'=>$idVenta);
		echo json_encode($JSON);
		exit();
	}
	if($r2==0){
		$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
			'error'=>114, 
			'mensaje'=>'El RFC Receptor esta mal formado'.$azurian['Receptor']['Rfc'], 
			'dump'=>'',
			'idVenta'=>$idVenta);
		echo json_encode($JSON);
		exit();
	}

	/* VALIDACION DE IVA


	/* VALIDACION ARREGLO AZURIAN================================================
	 */
	$cadenaOriginal=array();

	$vacios=array();
	$obligatorios=array('Moneda','metodoDePago','LugarExpedicion','version','fecha','formaDePago','tipoDeComprobante','subTotal','total');
	
	foreach ($azurian['Basicos'] as $key => $value) {
		if($key=='subTotal' || $key=='subTotal'){
			if(numericos($value,'.')==0){
				$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
					'error'=>120, 
					'mensaje'=>'Los datos numericos (Sub total o Total) estan incorrectos', 
					'dump'=>$valida,
					'idVenta'=>$idVenta);
				echo json_encode($JSON);
				exit();
			}
		}
		if($value==''){
			$vacios[]=$key;
		}
	}
	$valida = array_intersect($obligatorios, $vacios);

	if(count($valida)>=1){
		$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
				'error'=>101, 
				'mensaje'=>'Los datos basicos estan vacios o mal capturados', 
				'dump'=>$valida,
				'idVenta'=>$idVenta);
		echo json_encode($JSON);
		exit();
	}

	$vacios=array();
	$obligatorios=array('rfc','nombre');
	foreach ($azurian['Emisor'] as $key => $value) {
		if($value==''){
			$vacios[]=$key;
		}
	}
	$valida = array_intersect($obligatorios, $vacios);
	if(count($valida)>=1){
		$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
				'error'=>102, 
				'mensaje'=>'RFC o Razon Social del emisor estan vacios o mal capturados', 
				'dump'=>$valida,
				'idVenta'=>$idVenta);
		echo json_encode($JSON);
		exit();
	}

	$vacios=array();
	$obligatorios=array('calle','noExterior','colonia','localidad','municipio','estado','pais','codigoPostal');
	foreach ($azurian['FiscalesEmisor'] as $key => $value) {
		if($key=='codigoPostal'){
			if(numericos($value,'')==0){
				$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
					'error'=>121, 
					'mensaje'=>'El Codigo Postal del emisor es incorrecto', 
					'dump'=>$valida,
					'idVenta'=>$idVenta);
				echo json_encode($JSON);
				exit();
			}
		}
		if($value==''){
			$vacios[]=$key;
		}
	}
	$valida = array_intersect($obligatorios, $vacios);
	if(count($valida)>=1){
		$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
				'error'=>103, 
				'mensaje'=>'Los datos de direccion del emisor estan vacios o mal capturados', 
				'dump'=>$valida,
				'idVenta'=>$idVenta);
		echo json_encode($JSON);
		exit();
	}

	/*if($azurian['Regimen']['Regimen']==''){
		$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
				'error'=>104, 
				'mensaje'=>'El dato de regimen fiscal esta vacio o mal capturado', 
				'dump'=>json_encode($azurian['Regimen']['Regimen']),
				'idVenta'=>$idVenta);
		echo json_encode($JSON);
		exit();
	} */

	$vacios=array();
	$obligatorios=array('Rfc','Nombre');
	foreach ($azurian['Receptor'] as $key => $value) {
		if($value==''){
			$vacios[]=$key;
		}
	}	

	if($azurian['Receptor']['Rfc']=='XAXX010101000'){
		$azurian['Receptor']['Nombre'] = 'Factura Generica';
	}

	$valida = array_intersect($obligatorios, $vacios);
	if(count($valida)>=1 && $azurian['Receptor']['Rfc']!='XAXX010101000'){
		$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
				'error'=>105, 
				'mensaje'=>'RFC o Razon Social del receptor estan vacios o mal capturados', 
				'dump'=>$valida,
				'idVenta'=>$idVenta);
		echo json_encode($JSON);
		exit();
	}

	$vacios=array();
	if($azurian['Receptor']['Rfc']=='XAXX010101000'){
		$obligatorios=array('');
	}else{
		$obligatorios=array('calle','noExterior','colonia','localidad','municipio','estado','pais','codigoPostal');
	}
	foreach ($azurian['DomicilioReceptor'] as $key => $value) {
		if($key=='codigoPostal'){
			if(numericos($value,'')==0){
				$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
					'error'=>122, 
					'mensaje'=>'El Codigo Postal del receptor es incorrecto', 
					'dump'=>$valida,
					'idVenta'=>$idVenta);
				echo json_encode($JSON);
				exit();
			}
		}
		if($value==''){
			$vacios[]=$key;
		}
	}
	$valida = array_intersect($obligatorios, $vacios);
	if(count($valida)>=1){
		$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
				'error'=>106, 
				'mensaje'=>'Los datos de direccion del receptor estan vacios o mal capturados', 
				'dump'=>$valida,
				'idVenta'=>$idVenta);
		echo json_encode($JSON);
		exit();
	}

	if($azurian['Conceptos']['conceptos']==''){
		$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
				'error'=>107, 
				'mensaje'=>'La cadena de conceptos esta mal formada o vacia', 
				'dump'=>$conceptos,
				'idVenta'=>$idVenta);
		echo json_encode($JSON);
		exit();
	}	

	if (!isset($comPago)){
		if($azurian['Impuestos']['ivas']==''){
			$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
					'error'=>108, 
					'mensaje'=>'La cadena de impuestos esta mal formada o vacia', 
					'dump'=>$conceptos,
					'idVenta'=>$idVenta);
			echo json_encode($JSON);
			exit();
		}
	}

	//echo '((('.$azurian['Receptor']['UsoCFDI'].')))';
	//exit();

	 $noc = generaNoCertificado($rfc_cliente,$cer_cliente,$pathdc);
	/* FORMAR CADENA ORGINAL
	======================================================= */
	$cadOri=$azurian['Basicos']['Version'];
	$cadOri.='|'.$azurian['Basicos']['Serie'];
	$cadOri.='|'.$azurian['Basicos']['Folio'];
	$cadOri.='|'.$azurian['Basicos']['Fecha'];
	$cadOri.='|'.$azurian['Basicos']['FormaPago'];
	$cadOri.='|'.$noc;
	$cadOri.='|'.$azurian['Basicos']['SubTotal'];
	//$cadOri.='|'.$azurian['Basicos']['formaDePago'];
	//$cadOri.='|'.$azurian['Basicos']['SubTotal'];
	if($azurian['Basicos']['Descuento'] > 0){
		$cadOri.='|'.$azurian['Basicos']['Descuento'];
	}
	
	$cadOri.='|'.$azurian['Basicos']['Moneda'];
	if($azurian['Basicos']['Moneda']!='MXN'){
		$cadOri.='|'.$azurian['Basicos']['TipoCambio'];
	}
	
	$cadOri.='|'.$azurian['Basicos']['Total'];
	$cadOri.='|'.$azurian['Basicos']['TipoDeComprobante'];
	$cadOri.='|'.$azurian['Basicos']['MetodoPago'];
	$cadOri.='|'.$azurian['Basicos']['LugarExpedicion'];
	/*if($azurian['Basicos']['NumCtaPago']!=''){
		$cadOri.='|'.$azurian['Basicos']['NumCtaPago'];
	} */
	if($azurian['Realacionados']['cadena']!=''){
		$cadOri.='|'.trim($azurian['Realacionados']['cadena'],'|');
	}
	$cadOri.='|'.$azurian['Emisor']['Rfc'];
	$cadOri.='|'.$azurian['Emisor']['Nombre'];
	$cadOri.='|'.$azurian['Emisor']['RegimenFiscal'];
	/*$cadOri.='|'.$azurian['FiscalesEmisor']['calle'];
	$cadOri.='|'.$azurian['FiscalesEmisor']['noExterior'];
	$cadOri.='|'.$azurian['FiscalesEmisor']['colonia'];
	$cadOri.='|'.$azurian['FiscalesEmisor']['localidad'];
	$cadOri.='|'.$azurian['FiscalesEmisor']['municipio'];
	$cadOri.='|'.$azurian['FiscalesEmisor']['estado'];
	$cadOri.='|'.$azurian['FiscalesEmisor']['pais'];
	$cadOri.='|'.$azurian['FiscalesEmisor']['codigoPostal']; 
	$cadOri.='|'.$azurian['Regimen']['Regimen'];*/
	$cadOri.='|'.$azurian['Receptor']['Rfc'];
	$cadOri.='|'.$azurian['Receptor']['Nombre'];
	$cadOri.='|'.$azurian['Receptor']['UsoCFDI'];
	/*$cadOri.='|'.$azurian['DomicilioReceptor']['calle'];
	$cadOri.='|'.$azurian['DomicilioReceptor']['noExterior'];
	$cadOri.='|'.$azurian['DomicilioReceptor']['colonia'];
	$cadOri.='|'.$azurian['DomicilioReceptor']['localidad'];
	$cadOri.='|'.$azurian['DomicilioReceptor']['municipio'];
	$cadOri.='|'.$azurian['DomicilioReceptor']['estado'];
	$cadOri.='|'.$azurian['DomicilioReceptor']['pais'];
	$cadOri.='|'.$azurian['DomicilioReceptor']['codigoPostal']; */
	/*----- Comillas ------*/
	$azurian['Conceptos']['conceptosOri']= preg_replace('/&quot;/', '"', $azurian['Conceptos']['conceptosOri']);
	$azurian['Conceptos']['conceptosOri']= preg_replace('/&apos;/', "'", $azurian['Conceptos']['conceptosOri']);

	$cadOri.=$azurian['Conceptos']['conceptosOri'];
	$cadOri.=$azurian['Impuestos']['isr'];
	$cadOri.=$azurian['Impuestos']['iva'];
	$cadOri.=$azurian['Impuestos']['rtp_cad'];
	$cadOri.=$azurian['ComprobantePago']['cadena'];
	//print_r($azurian['Conceptos']['conceptosOri']);
	$cadOri=preg_replace('/\|{2,}/', '|',$cadOri);
	$cadOri=preg_replace('/ {2,}/', ' ',$cadOri);
	$cadOri='||'.$cadOri.'||';

	/* FUCIONES GENERAR SELLOS Y CERTIFICADOS
	============================================================ */

	
	$pem = generaPem($rfc_cliente,$key_cliente,$pwd_cliente,$pathdc);
	if($pem!=1){
		$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
				'error'=>109, 
				'mensaje'=>'Error al leer el certificado pem, no es posible facturar con esta instancia', 
				'dump'=>'',
				'idVenta'=>$idVenta);
		echo json_encode($JSON);
		exit();
	}


	$ini = generaIniVigencia($rfc_cliente,$cer_cliente,$pathdc);
	$fin = generaFinVigencia($rfc_cliente,$cer_cliente,$pathdc);
	$noc = generaNoCertificado($rfc_cliente,$cer_cliente,$pathdc);
	$cer = generaCertificado($rfc_cliente,$cer_cliente,$pathdc);
	$fff = date('YmdHis').rand(100,999);
	$ori = generaCadenaOriginal($cadOri,$fff,$rfc_cliente,$pathdc);
	$sel = generaSello($rfc_cliente,$fff,$pathdc);
//echo $sel.'===============';
	/* GENERAR XML
	======================================================== */
	$idComprobante=time();
	

	$azurian['Emisor']['Nombre']= preg_replace('/&/', '&amp;', $azurian['Emisor']['Nombre']);
	$azurian['Receptor']['Nombre']= preg_replace('/&/', '&amp;', $azurian['Receptor']['Nombre']);
	$azurian['Conceptos']['conceptos']= preg_replace('/&/', '&amp;', $azurian['Conceptos']['conceptos']);
	$azurian['Emisor']['Rfc']= preg_replace('/&/', '&amp;', $azurian['Emisor']['Rfc']);
	$azurian['Receptor']['Rfc']= preg_replace('/&/', '&amp;', $azurian['Receptor']['Rfc']);

/*----Comillas -----*/
    $azurian['Conceptos']['conceptos']= preg_replace('/&amp;quot;/', '&quot;', $azurian['Conceptos']['conceptos']);
    $azurian['Conceptos']['conceptos']= preg_replace('/&amp;apos;/', '&apos;', $azurian['Conceptos']['conceptos']);

	$XML='';
	/*$XML.="<?xml version='1.0' encoding='UTF-8'?><cfdi:Comprobante xmlns:cfdi='http://www.sat.gob.mx/cfd/3' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation='http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd http://www.sat.gob.mx/implocal http://www.sat.gob.mx/sitio_internet/cfd/implocal/implocal.xsd'"; */
	if (!isset($comPago)){
		$XML.="<?xml version='1.0' encoding='UTF-8'?><cfdi:Comprobante xmlns:cfdi='http://www.sat.gob.mx/cfd/3' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation='http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd'"; 
	}else{
		///Comprobante de pago
		$XML.="<?xml version='1.0' encoding='UTF-8'?><cfdi:Comprobante xmlns:cfdi='http://www.sat.gob.mx/cfd/3' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation='http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd http://www.sat.gob.mx/Pagos http://www.sat.gob.mx/sitio_internet/cfd/Pagos/Pagos10.xsd'"; 
	}	
	
	
	/*$XML.='<?xml version="1.0" encoding="UTF-8"?><cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd"'; */


	foreach ($azurian['Basicos'] as $key => $value) {
		if($key=='Sello'){
			$value=$sel;
		}
		if($key=='NoCertificado'){
			$value=$noc;
		}
		if($key=='Certificado'){
			$value=$cer;
		}
		if($value!=''){
			if($key!='NumCtaPago'){
				$XML.=" ".$key."='".$value."'";
				//$XML.=' '.$key.'="'.$value.'"';
			}
		}
	}
	$XML.='>';

	if($azurian['Realacionados']['xml']!=''){
		$XML.=$azurian['Realacionados']['xml'];
	}


	$XML.='<cfdi:Emisor';
	foreach ($azurian['Emisor'] as $key => $value) {
		if($value!=''){
			$XML.=" ".$key."='".$value."'";
			//$XML.=' '.$key.'="'.$value.'"';
		}
	}
	$XML.='>';

	/*$XML.='<cfdi:DomicilioFiscal';
	foreach ($azurian['FiscalesEmisor'] as $key => $value) {
		if($value!=''){
			$XML.=" ".$key."='".$value."'";
		}
	}
	$XML.='/>'; */
	/*$XML.='<cfdi:RegimenFiscal';
	foreach ($azurian['Regimen'] as $key => $value) {
		if($value!=''){
			$XML.=" ".$key."='".$value."'";
		}
	}
	$XML.='/>'; */
	$XML.='</cfdi:Emisor><cfdi:Receptor';
	foreach ($azurian['Receptor'] as $key => $value) {
		if($value!=''){
			$XML.=" ".$key."='".$value."'";
			//$XML.=' '.$key.'="'.$value.'"';
		}
	}
	$XML.='>';

	if($azurian['Receptor']['rfc']=='XAXX010101000'){

	}else{
		/*$XML.='<cfdi:Domicilio';
		foreach ($azurian['DomicilioReceptor'] as $key => $value) {
			if($value!=''){
				$XML.=" ".$key."='".$value."'";
			}
		}
		$XML.='/>'; */
	}


	$XML.='</cfdi:Receptor>';
	$XML.='<cfdi:Conceptos>'.$azurian['Conceptos']['conceptos'].'</cfdi:Conceptos>';

	if (!isset($comPago)){
		$XML.='<cfdi:Impuestos ';
		if ($azurian['Impuestos']['totalImpuestosRetenidos']>0) {
			$XML.="TotalImpuestosRetenidos='".$azurian['Impuestos']['totalImpuestosRetenidos']."' ";
			//$XML.='TotalImpuestosRetenidos="'.$azurian['Impuestos']['totalImpuestosRetenidos'].'" ';
		}
		$XML.=" TotalImpuestosTrasladados='".$azurian['Impuestos']['totalImpuestosTrasladados']."'>";
		//$XML.=' TotalImpuestosTrasladados="'.$azurian['Impuestos']['totalImpuestosTrasladados'].'">';
		$XML.=$azurian['Impuestos']['ivas'];
		$XML.='</cfdi:Impuestos><cfdi:Complemento>'.$azurian['Impuestos']['rtp'].'</cfdi:Complemento></cfdi:Comprobante>';
	}else{
		$XML.='<cfdi:Complemento>'.$azurian['ComprobantePago']['xml'].'</cfdi:Complemento></cfdi:Comprobante>';
	}

	

	$azurian['Emisor']['Nombre']= preg_replace('/&amp;/', '&', $azurian['Emisor']['Nombre']);
	$azurian['Receptor']['Nombre']= preg_replace('/&amp;/', '&', $azurian['Receptor']['Nombre']);
	$azurian['Conceptos']['conceptos']= preg_replace('/&amp;/', '&', $azurian['Conceptos']['conceptos']);
	$azurian['Emisor']['Rfc']= preg_replace('/&amp;/', '&', $azurian['Emisor']['Rfc']);
	$azurian['Receptor']['Rfc']= preg_replace('/&amp;/', '&', $azurian['Receptor']['Rfc']);
	/*----Comillas ------*/
	$azurian['Conceptos']['conceptos']= preg_replace('/&apos;/', "'", $azurian['Conceptos']['conceptos']);
	$azurian['Conceptos']['conceptos']= preg_replace('/&quot;/', '"', $azurian['Conceptos']['conceptos']);
	//echo '<br><br>';
	//echo $cadOri;
	//echo $XML;
	//exit();
	$archivo = fopen('../../modulos/SAT/xml33.xml','w');

	fwrite($archivo,$XML);
	fclose($archivo);
	
	//INVOCAR WSINVOICE
	//if(!isset($positionPath))
	//	$positionPath="../../modulos";


	if(!isset($positionPath))
		$positionPath="../../modulos";
	

	if(isset($kiosko)){
		$positionPath="../webapp/modulos";
	}
//echo '?'.$positionPath.'/wsinvoice/sealInvoice.php';
	include ($positionPath.'/wsinvoice/sealInvoice.php');
	/*$strFileName = './' . INVOICE_PATH . '/' . date('Ymdhis') . '_' . rand(10,99) . '.';
    file_put_contents($strFileName . 'xml',base64_decode($strXML));
//    file_put_contents($strFileName . 'xmlo',base64_decode($strXML));
//    file_put_contents($strFileName . 'data',"||".$strUser."||".$strPassword."||".$strEnvironment."||");
    $strXMLString = '';
    $strOutput = '';
    $objXML = fopen($strFileName . 'xml',"r");
    while(!feof($objXML)) {
        $strOutput = fgets($objXML);
        $strXMLString .= str_replace("'",'"',str_replace(">","&gt;",str_replace("<","&lt;",str_replace("&amp;","&amp;amp;",trim($strOutput)))));
    }
    unset($objXML);
    unset($strOutput);
    unlink($strFileName . 'xml'); */
   /* $strUser = 'pruebasWS';
    $strPassword = 'pruebasWS';

      $strXMLString = str_replace("'",'"',str_replace(">","&gt;",str_replace("<","&lt;",str_replace("&amp;","&amp;amp;",trim($XML))))); 

    $strXMLBody = '<S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/"><S:Body><ns3:Timbrar xmlns:ns3="http://wservicios/" xmlns:xmime="http://www.w3.org/2005/05/xmlmime"><accesos><password>' . $strPassword . '</password><usuario>' . $strUser . '</usuario></accesos><comprobante>' . $strXMLString . '</comprobante></ns3:Timbrar></S:Body></S:Envelope>';
     //$strXMLBody = '<S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/"><S:Body><ns3:TimbrarCFDI xmlns:ns3="http://wservicios/" xmlns:xmime="http://www.w3.org/2005/05/xmlmime"><accesos><password>' . $strPassword . '</password><usuario>' . $strUser . '</usuario></accesos><comprobante>' . $strXMLString . '</comprobante></ns3:TimbrarCFDI></S:Body></S:Envelope>';
   
    unset($strXMLString);

    $arrHeaders = array('Content-Type: text/xml; charset="utf-8"', 'Content-Length: ' . strlen($strXMLBody), 'Accept: text/xml', 'Cache-Control: no-cache', 'Pragma: no-cache', 'SOAPAction: "Timbrar"');

	/*$client2 = new SoapClient('http://dev33.facturacfdi.mx/WSForcogsaService?wsdl');
	var_dump($client2);
	$parametros = array('accesos' => array("usuario" =>$strUser, "password" => $strPassword), "comprobante" => $strXMLString );
	$result = $client2->Timbrar($parametros);
	var_dump($result);
	exit(); */
//$arrayName = array('accesos' => array("usuario" =>$strUser, "password" => $strPassword), "comprobante" => $strXMLString );



    /*$curlObj = curl_init();
    curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, false);
    /*switch($strEnvironment){
        case WS_DEV: */
           // curl_setopt($curlObj, CURLOPT_URL, 'http://dev33.facturacfdi.mx/WSForcogsaService?wsdl');
     /*       break;
        case WS_PROD:
            curl_setopt($curlObj, CURLOPT_URL, FCWS_PROD_URL);
            break;
    } */
   /* curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curlObj, CURLOPT_TIMEOUT, 180);
    curl_setopt($curlObj, CURLOPT_HTTPHEADER, $arrHeaders);
    curl_setopt($curlObj, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curlObj, CURLOPT_POST, true);
    curl_setopt($curlObj, CURLOPT_POSTFIELDS, $strXMLBody);
    curl_setopt($curlObj, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    $xmlResult = curl_exec($curlObj);
    var_dump($xmlResult);
    curl_close($curlObj);
    unset($curlObj); 
    exit(); */


	//exit();
	
	
?>
