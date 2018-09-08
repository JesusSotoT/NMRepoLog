<?php

	
	/* CANCELACION FACTURA
	================================================ */
	if(isset($cancelFac) && $cancelFac==1){

		date_default_timezone_set("Mexico/General");
		$fecha=date('Y-m-d').'T'.date('H:i:s',strtotime("-7 minute"));
		try {
			$client2 = new SoapClient($azurianUrls['concultacomp'], array('local_cert' =>$p12_netwar));
		    		$parametros = array("msg" => '<?xml version="1.0" encoding="utf-8"?><ConsultaEstadoComprobanteCFDI rfcEmpresa="'.$rfccancel.'" UUID="'.$cancelFolio.'"></ConsultaEstadoComprobanteCFDI>');
					    $result = $client2->consultarEstadoComprobante($parametros);
					    $xmlresponse =  $result->consultarEstadoComprobanteReturn;
					    //var_dump($result);
					   	$result2=explode('codigo="', $xmlresponse);
						$result2=explode('"', $result2[1]);
						$result2=$result2[0];

			if($result2==201 || $result2==202){
				$JSON = array('success' =>1, 
					'mensaje'=>'Se ha cancelado la facturaiiiiii.');
				echo json_encode($JSON);
				exit();
			}


		    $client = new SoapClient($azurianUrls['cancelacion'], array('local_cert' =>$p12_netwar));
		    	$d='<Cancelacion xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" Fecha="'.$fecha.'" RfcEmisor="'.$rfccancel.'" xmlns="http://cancelacfd.sat.gob.mx"><Folios><UUID>'.$cancelFolio.'</UUID></Folios></Cancelacion>';

			    $dom = new DOMDocument(); 
			    $yourXML = $d;
			    $dom->loadXML($yourXML);
			    $canonicalized = $dom->C14N();
			    $digest = base64_encode(pack("H*", sha1($canonicalized))); 


			    $nx='<SignedInfo xmlns="http://www.w3.org/2000/09/xmldsig#" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"></CanonicalizationMethod><SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"></SignatureMethod><Reference URI=""><Transforms><Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"></Transform></Transforms><DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"></DigestMethod><DigestValue>'.$digest.'</DigestValue></Reference></SignedInfo>';
			    $fff = date('YmdHis').rand(100,999);

			   	$pem = generaPem($rfccancel,'../../modulos/SAT/cliente/'.$elkey,$clave,$pathdc);
			    $ori = generaCadenaOriginal($nx,$fff,$rfccancel,'../../modulos/SAT/cliente');
				$sel = generaSello($rfccancel,$fff,'../../modulos/SAT/cliente');
				$cer = generaCertificado($rfccancel,'../../modulos/SAT/cliente/'.$elcer,'../../modulos/SAT/cliente');

		    $parametros = array("msg" => '<CancelarComprobanteCFDI id="'.$cancelID.'"><Mensaje><![CDATA[<?xml version="1.0" encoding="utf-8"?><s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/"><s:Body xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><CancelaCFD xmlns="http://cancelacfd.sat.gob.mx"><Cancelacion RfcEmisor="'.$rfccancel.'" Fecha="'.$fecha.'"><Folios><UUID>'.$cancelFolio.'</UUID></Folios><Signature xmlns="http://www.w3.org/2000/09/xmldsig#"><SignedInfo><CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/><SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/><Reference URI=""><Transforms><Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"/></Transforms><DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/><DigestValue>'.$digest.'</DigestValue></Reference></SignedInfo><SignatureValue>'.$sel.'</SignatureValue><KeyInfo><X509Data><X509IssuerSerial><X509IssuerName>OID.1.2.840.113549.1.9.2=Responsable: Héctor Ornelas Arciga, OID.2.5.4.45=SAT970701NN3, L=Coyoacán, ST=Distrito Federal, C=MX, POSTALCODE=06300, STREET="Av. Hidalgo 77, Col. Guerrero", EMAILADDRESS=asisnet@pruebas.sat.gob.mx, OU=Administración de Seguridad de la Información, O=Servicio de Administración Tributaria, CN=A.C. de pruebas</X509IssuerName><X509SerialNumber>286524172099382162235533054511188021807362226485</X509SerialNumber></X509IssuerSerial><X509Certificate>'.$cer.'</X509Certificate></X509Data></KeyInfo></Signature></Cancelacion></CancelaCFD></s:Body></s:Envelope>]]></Mensaje></CancelarComprobanteCFDI>');
		    $result = $client->cancelarComprobante($parametros);
		    $xmlresponse =  $result->cancelarComprobanteReturn;
		    $parametrosEnvio = $parametros;
		    //var_dump($parametros);

		    //var_dump($result);
			$result2=explode('result="', $xmlresponse);
			$result2=explode('">', $result2[1]);
			$result2=$result2[0];
				sleep(50);
		    		$client2 = new SoapClient($azurianUrls['concultacomp'], array('local_cert' =>$p12_netwar));
		    		$parametros = array("msg" => '<?xml version="1.0" encoding="utf-8"?><ConsultaEstadoComprobanteCFDI rfcEmpresa="'.$rfccancel.'" UUID="'.$cancelFolio.'"></ConsultaEstadoComprobanteCFDI>');
					    $result = $client2->consultarEstadoComprobante($parametros);
					    $xmlresponse =  $result->consultarEstadoComprobanteReturn;
					    //var_dump($result);
					   
					   	$result2=explode('codigo="', $xmlresponse);
						$result2=explode('"', $result2[1]);
						$result2=$result2[0];

						
					   	$resultMsj=explode('descripcion="', $xmlresponse);
						$resultMsj=explode('"', $resultMsj[1]);
						$resultMsj=$resultMsj[0];

						

			if($result2==201 || $result2==202){
				$JSON = array('success' =>1, 
					'mensaje'=>'Se ha cancelado la factura.');
				echo json_encode($JSON);
				exit();
			}else{
				$JSON = array('success' =>0,
					'error'=>202, 
					'mensaje'=>'Se envio la factura a cancelar al SAT, favor de revisar mas tarde.',
					'dump'=> 'codigo='.$result2,
					'dumpMsj'=> $resultMsj,
					'xmlResponse' => $xmlresponse,
					'xmlEnvio' => $parametrosEnvio );
				echo json_encode($JSON);
				exit();
			}

		}catch(SoapFault $exception){
			$JSON = array('success' =>0,
					'error'=>203, 
					'mensaje'=>'Por el momento el servicio esta presentando problemas externos a Netwarmonitor, Intentelo mas tarde.', 
					'dump'=>'Metodo: cancelarComprobante()',
					'idVenta'=>$idVenta);
			echo json_encode($JSON);
			exit();
		}

	}

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
		$comando="openssl dgst -sha1 -sign ".$pem." '".$pathdc."/CO" .$dteTrailer. ".txt' | openssl enc -base64 -A -out ".$pathdc."/sello" .$dteTrailer. ".txt"; 
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
	$r1=validaRFC($azurian['Emisor']['rfc']);
	$r2=validaRFC($azurian['Receptor']['rfc']);
	if($r1==0){
		$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
			'error'=>113, 
			'mensaje'=>'El RFC Emisor esta mal formado'.$azurian['Emisor']['rfc'], 
			'dump'=>'',
			'idVenta'=>$idVenta);
		echo json_encode($JSON);
		exit();
	}
	if($r2==0){
		$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
			'error'=>114, 
			'mensaje'=>'El RFC Receptor esta mal formado'.$azurian['Receptor']['rfc'], 
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

	if($azurian['Regimen']['Regimen']==''){
		$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
				'error'=>104, 
				'mensaje'=>'El dato de regimen fiscal esta vacio o mal capturado', 
				'dump'=>json_encode($azurian['Regimen']['Regimen']),
				'idVenta'=>$idVenta);
		echo json_encode($JSON);
		exit();
	}

	$vacios=array();
	$obligatorios=array('rfc','nombre');
	foreach ($azurian['Receptor'] as $key => $value) {
		if($value==''){
			$vacios[]=$key;
		}
	}	

	if($azurian['Receptor']['rfc']=='XAXX010101000'){
		$azurian['Receptor']['nombre'] = 'Factura Generica';
	}

	$valida = array_intersect($obligatorios, $vacios);
	if(count($valida)>=1 && $azurian['Receptor']['rfc']!='XAXX010101000'){
		$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
				'error'=>105, 
				'mensaje'=>'RFC o Razon Social del receptor estan vacios o mal capturados', 
				'dump'=>$valida,
				'idVenta'=>$idVenta);
		echo json_encode($JSON);
		exit();
	}

	$vacios=array();
	if($azurian['Receptor']['rfc']=='XAXX010101000'){
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

	if($azurian['Impuestos']['ivas']==''){
		$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
				'error'=>108, 
				'mensaje'=>'La cadena de impuestos esta mal formada o vacia', 
				'dump'=>$conceptos,
				'idVenta'=>$idVenta);
		echo json_encode($JSON);
		exit();
	}
	

	/* FORMAR CADENA ORGINAL
	======================================================= */
	$cadOri=$azurian['Basicos']['version'];
	$cadOri.='|'.$azurian['Basicos']['fecha'];
	$cadOri.='|'.$azurian['Basicos']['tipoDeComprobante'];
	$cadOri.='|'.$azurian['Basicos']['formaDePago'];
	$cadOri.='|'.$azurian['Basicos']['subTotal'];
	$cadOri.='|'.$azurian['Basicos']['Moneda'];
	$cadOri.='|'.$azurian['Basicos']['total'];
	$cadOri.='|'.$azurian['Basicos']['metodoDePago'];
	$cadOri.='|'.$azurian['Basicos']['LugarExpedicion'];
	$cadOri.='|'.$azurian['Emisor']['rfc'];
	$cadOri.='|'.$azurian['Emisor']['nombre'];
	$cadOri.='|'.$azurian['FiscalesEmisor']['calle'];
	$cadOri.='|'.$azurian['FiscalesEmisor']['noExterior'];
	$cadOri.='|'.$azurian['FiscalesEmisor']['colonia'];
	$cadOri.='|'.$azurian['FiscalesEmisor']['localidad'];
	$cadOri.='|'.$azurian['FiscalesEmisor']['municipio'];
	$cadOri.='|'.$azurian['FiscalesEmisor']['estado'];
	$cadOri.='|'.$azurian['FiscalesEmisor']['pais'];
	$cadOri.='|'.$azurian['FiscalesEmisor']['codigoPostal'];
	$cadOri.='|'.$azurian['Regimen']['Regimen'];
	$cadOri.='|'.$azurian['Receptor']['rfc'];
	$cadOri.='|'.$azurian['Receptor']['nombre'];
	$cadOri.='|'.$azurian['DomicilioReceptor']['calle'];
	$cadOri.='|'.$azurian['DomicilioReceptor']['noExterior'];
	$cadOri.='|'.$azurian['DomicilioReceptor']['colonia'];
	$cadOri.='|'.$azurian['DomicilioReceptor']['localidad'];
	$cadOri.='|'.$azurian['DomicilioReceptor']['municipio'];
	$cadOri.='|'.$azurian['DomicilioReceptor']['estado'];
	$cadOri.='|'.$azurian['DomicilioReceptor']['pais'];
	$cadOri.='|'.$azurian['DomicilioReceptor']['codigoPostal'];
	/*----- Comillas ------*/
	$azurian['Conceptos']['conceptosOri']= preg_replace('/&quot;/', '"', $azurian['Conceptos']['conceptosOri']);
	$azurian['Conceptos']['conceptosOri']= preg_replace('/&apos;/', "'", $azurian['Conceptos']['conceptosOri']);

	$cadOri.=$azurian['Conceptos']['conceptosOri'];
	$cadOri.=$azurian['Impuestos']['isr'];
	$cadOri.=$azurian['Impuestos']['iva'];
	$cadOri.=$azurian['Impuestos']['rtp_cad'];
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

	/* GENERAR XML
	======================================================== */
	$idComprobante=time();
	


	$azurian['Receptor']['nombre']= preg_replace('/&/', '&amp;', $azurian['Receptor']['nombre']);
	$azurian['Conceptos']['conceptos']= preg_replace('/&/', '&amp;', $azurian['Conceptos']['conceptos']);
	$azurian['Emisor']['rfc']= preg_replace('/&/', '&amp;', $azurian['Emisor']['rfc']);
	$azurian['Receptor']['rfc']= preg_replace('/&/', '&amp;', $azurian['Receptor']['rfc']);
/*----Comillas -----*/
    $azurian['Conceptos']['conceptos']= preg_replace('/&amp;quot;/', '&quot;', $azurian['Conceptos']['conceptos']);
    $azurian['Conceptos']['conceptos']= preg_replace('/&amp;apos;/', '&apos;', $azurian['Conceptos']['conceptos']);

	$XML='';
	$XML.="<?xml version='1.0' encoding='utf-8'?><EnvioCFDI idEnvio='10'><Comprobantes><Comprobante idEmpresa='".$rfc_cliente."' idComprobante='".$idComprobante."'><![CDATA[<?xml version='1.0' encoding='UTF-8'?><cfdi:Comprobante xmlns:cfdi='http://www.sat.gob.mx/cfd/3' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xmlns:implocal='http://www.sat.gob.mx/implocal' xsi:schemaLocation='http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd http://www.sat.gob.mx/implocal http://www.sat.gob.mx/sitio_internet/cfd/implocal/implocal.xsd'";
	foreach ($azurian['Basicos'] as $key => $value) {
		if($key=='sello'){
			$value=$sel;
		}
		if($key=='noCertificado'){
			$value=$noc;
		}
		if($key=='certificado'){
			$value=$cer;
		}
		if($value!=''){
			$XML.=" ".$key."='".$value."'";
		}
	}
	$XML.='>';

	$XML.='<cfdi:Emisor';
	foreach ($azurian['Emisor'] as $key => $value) {
		if($value!=''){
			$XML.=" ".$key."='".$value."'";
		}
	}
	$XML.='>';

	$XML.='<cfdi:DomicilioFiscal';
	foreach ($azurian['FiscalesEmisor'] as $key => $value) {
		if($value!=''){
			$XML.=" ".$key."='".$value."'";
		}
	}
	$XML.='/>';
	$XML.='<cfdi:RegimenFiscal';
	foreach ($azurian['Regimen'] as $key => $value) {
		if($value!=''){
			$XML.=" ".$key."='".$value."'";
		}
	}
	$XML.='/>';
	$XML.='</cfdi:Emisor><cfdi:Receptor';
	foreach ($azurian['Receptor'] as $key => $value) {
		if($value!=''){
			$XML.=" ".$key."='".$value."'";
		}
	}
	$XML.='>';

	if($azurian['Receptor']['rfc']=='XAXX010101000'){

	}else{
		$XML.='<cfdi:Domicilio';
		foreach ($azurian['DomicilioReceptor'] as $key => $value) {
			if($value!=''){
				$XML.=" ".$key."='".$value."'";
			}
		}
		$XML.='/>';
	}

	$XML.='</cfdi:Receptor>';
	$XML.='<cfdi:Conceptos>'.$azurian['Conceptos']['conceptos'].'</cfdi:Conceptos>';
	$XML.=$azurian['Impuestos']['rtp'];
	$XML.='<cfdi:Impuestos ';
	if ($azurian['Impuestos']['totalImpuestosRetenidos']>0) {
		$XML.="totalImpuestosRetenidos='".$azurian['Impuestos']['totalImpuestosRetenidos']."' ";
	}
	$XML.=" totalImpuestosTrasladados='".$azurian['Impuestos']['totalImpuestosTrasladados']."'>";
	$XML.=$azurian['Impuestos']['ivas'];
	$XML.='</cfdi:Impuestos><cfdi:Complemento>'.$azurian['Impuestos']['rtp'].'</cfdi:Complemento></cfdi:Comprobante>';
	$XML.=']]></Comprobante></Comprobantes></EnvioCFDI>';

	$azurian['Receptor']['nombre']= preg_replace('/&amp;/', '&', $azurian['Receptor']['nombre']);
	$azurian['Conceptos']['conceptos']= preg_replace('/&amp;/', '&', $azurian['Conceptos']['conceptos']);
	$azurian['Emisor']['rfc']= preg_replace('/&amp;/', '&', $azurian['Emisor']['rfc']);
	$azurian['Receptor']['rfc']= preg_replace('/&amp;/', '&', $azurian['Receptor']['rfc']);
	/*----Comillas ------*/
	$azurian['Conceptos']['conceptos']= preg_replace('/&apos;/', "'", $azurian['Conceptos']['conceptos']);
	$azurian['Conceptos']['conceptos']= preg_replace('/&quot;/', '"', $azurian['Conceptos']['conceptos']);

	//echo $cadOri;
	//echo $XML;
	//exit();
	if(isset($consultaCFDI) && $consultaCFDI==1){
		$idRastreo = $rastreo;
		//echo 'entro al if';
    }else{
    	//echo 'entro al else';
    	try {
			    $client = new SoapClient($azurianUrls['recepcion'], array('local_cert' =>$p12_netwar));
			    $parametros = array("msg" => $XML);
			    $result = $client->recepcionComprobante($parametros);
			    $xml_anzurian = $result->recepcionComprobanteReturn;

			    $xml = json_decode(json_encode((array) simplexml_load_string($xml_anzurian)), 1);
			    if($xml['@attributes']['codigoResultado']=='-2'){
			    	$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
						'error'=>-2, 
						'mensaje'=>'XML mal formado', 
						'dump'=>'',
						'idVenta'=>$idVenta);
					echo json_encode($JSON);
			    	exit();
			    }
			    if($xml['@attributes']['codigoResultado']=='-3'){
			    	$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
						'error'=>-3, 
						'mensaje'=>'Error al almacenar XML', 
						'dump'=>'',
						'idVenta'=>$idVenta);
					echo json_encode($JSON);
			    	exit();
			    }

			    $idRastreo = $xml['@attributes']['trackId'];

			} catch (SoapFault $exception) {

				$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
						'error'=>110, 
						'mensaje'=>'Por el momento el servicio esta presentando problemas externos a Netwarmonitor, Intentelo mas tarde.', 
						'dump'=>'Metodo: recepcionComprobante()',
						'idVenta'=>$idVenta);
				echo json_encode($JSON);
				exit();
			}
    }
    ///fin de if

	/*if(!preg_match('/(\.png)$|(\.jpg)$|(\.gif)$|(\.jpeg)$|(\.bmp)$/', strtolower(trim($azurian['org']['logo'])))) {
		$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
			'error'=>2000, 
			'mensaje'=>'La imagen tiene un formato incorrecto', 
			'dump'=>'',
			'idVenta'=>$idVenta);
		echo json_encode($JSON);
    	exit();
	}*/

 sleep(1);

    try {
        $parametros = array("msg" => '<?xml version="1.0" encoding="utf-8"?><ConsultaEnvioCFDI trackId="'.$idRastreo.'"></ConsultaEnvioCFDI>');
        $parsear=0;

        $intXX=0;
        do{
                $intXX++;
                unset($xml2);
                unset($xml_anzurian2);
                unset($result2);
                unset($client);
                $client = new SoapClient($azurianUrls['envio'], array('local_cert' =>$p12_netwar));
                $result2 = $client->consultarEnvio($parametros);
                $xml_anzurian2 = $result2->consultarEnvioReturn;
                $xml2 = json_decode(json_encode((array) simplexml_load_string($xml_anzurian2)), 1);
                sleep(1);
        }while ($xml2['@attributes']['codigoRespuesta']=='2' && $intXX < 61);
        //$xml2['@attributes']['codigoRespuesta'] = 2;
	    if($xml2['@attributes']['codigoRespuesta']=='2'){
	    	$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
	    		'error'=>'001',
				'mensaje'=>'En estos momentos no se puede acceder a los servidores del SAT favor de intentar mas tarde.Reintenta facturar tu venta desde el Reporte Ventas No Facturadas.', 
				'dump'=>'',
				'trackId'=>$xml['@attributes']['trackId'],
				'idVenta'=>$idVenta);
			echo json_encode($JSON);
	    	exit();
	    }


	    if($xml2['@attributes']['codigoRespuesta']=='100'){
	    	if (array_key_exists('ComprobantesValidos', $xml2)) {
			    $parsear=1;
			}else{
				if (array_key_exists('ComprobantesErroneos', $xml2)) {
				    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='3'){
				    	$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
								'error'=>3, 
								'mensaje'=>'El comprobante es invalido o ya ha sido usado con anterioridad', 
								'dump'=>'',
								'idVenta'=>$idVenta);
						echo json_encode($JSON);
				    	exit();
				    }
				    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='300'){
				    	$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
								'error'=>300, 
								'mensaje'=>'Usuario invalido, es necesario se autentifiquen los nodos de timbrado', 
								'dump'=>'',
								'idVenta'=>$idVenta);
						echo json_encode($JSON);
				    	exit();
				    }
				    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='301'){
				    	$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
								'error'=>301, 
								'mensaje'=>'XML mal formado', 
								'dump'=>'',
								'idVenta'=>$idVenta);
						echo json_encode($JSON);
				    	exit();
				    }
				    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='302'){
				    	$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
								'error'=>302, 
								'mensaje'=>'Sello mal formado o invalido', 
								'dump'=>$sel,
								'idVenta'=>$idVenta);
						echo json_encode($JSON);
				    	exit();
				    }
				    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='303'){
				    	$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
								'error'=>303, 
								'mensaje'=>'El CSD del emisor no corresponde al RFC que viene como emisor de comprobante', 
								'dump'=>'',
								'idVenta'=>$idVenta);
						echo json_encode($JSON);
				    	exit();
				    }
				    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='304'){
				    	$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
								'error'=>304, 
								'mensaje'=>'El CSD del emisor ha sido revocado', 
								'dump'=>'',
								'idVenta'=>$idVenta);
						echo json_encode($JSON);
				    	exit();
				    }
				    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='305'){
				    	$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
								'error'=>305, 
								'mensaje'=>'La fecha de emision no esta dentro de la vigencia del CSD emisor', 
								'dump'=>'',
								'idVenta'=>$idVenta);
						echo json_encode($JSON);
				    	exit();
				    }
				    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='306'){
				    	$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
								'error'=>306, 
								'mensaje'=>'La llave utilizada para sellar no corresponde al CSD', 
								'dump'=>'',
								'idVenta'=>$idVenta);
						echo json_encode($JSON);
				    	exit();
				    }
				    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='307'){
				    	$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
								'error'=>307, 
								'mensaje'=>'Esta factura ya contiene un timbre previo', 
								'dump'=>'',
								'idVenta'=>$idVenta);
						echo json_encode($JSON);
				    	exit();
				    }
				    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='308'){
				    	$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
								'error'=>308, 
								'mensaje'=>'El CSD del emisor debe ser firmado por un certificado autorizado del SAT', 
								'dump'=>'',
								'idVenta'=>$idVenta);
						echo json_encode($JSON);
				    	exit();
				    }
				    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='401'){
				    	$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
								'error'=>401, 
								'mensaje'=>'Fecha y hora de generacion fuera de rango.'.$azurian['Basicos']['fecha'], 
								'dump'=>$fecha,
								'idVenta'=>$idVenta);
						echo json_encode($JSON);
				    	exit();
				    }
				    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='402'){
				    	$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
								'error'=>402, 
								'mensaje'=>'RFC no existe en el regimen de validacion LCO', 
								'dump'=>$rfc_cliente,
								'idVenta'=>$idVenta);
						echo json_encode($JSON);
				    	exit();
				    }
				    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='403'){
				    	$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
								'error'=>403, 
								'mensaje'=>'La fecha de emision debe ser posterior al 01 de Enero 2011', 
								'dump'=>$fecha,
								'idVenta'=>$idVenta);
						echo json_encode($JSON);
				    	exit();
				    }
				}

				//var_dump($xml2);
				exit();
			}
	    }

	    if($parsear==1){
	    	$datosTimbrado=array();
			$cadenaParseo=$result2->consultarEnvioReturn;
			$cadenaParseo=explode('[CDATA[', $cadenaParseo);
			$cadenaParseo=explode(']]', $cadenaParseo[1]);

			$pcad=explode('UUID="',$cadenaParseo[0]);
        	$cad=explode('"',$pcad[1]);
        	$datosTimbrado['UUID']=$cad[0];

        	$pcad=explode('noCertificadoSAT="',$cadenaParseo[0]);
	        $cad=explode('"',$pcad[1]);
	        $datosTimbrado['noCertificadoSAT']=$cad[0];

	        $pcad=explode('selloCFD="',$cadenaParseo[0]);
	        $cad=explode('"',$pcad[1]);
	        $datosTimbrado['selloCFD']=$cad[0];

	        $pcad=explode('selloSAT="',$cadenaParseo[0]);
	        $cad=explode('"',$pcad[1]);
	        $datosTimbrado['selloSAT']=$cad[0];

	        $pcad=explode('FechaTimbrado="',$cadenaParseo[0]);
	        $cad=explode('"',$pcad[1]);
	        $datosTimbrado['FechaTimbrado']=$cad[0];
	        $datosTimbrado['idComprobante']=$idComprobante;
	        $datosTimbrado['idFact']=$idFact;
	        $datosTimbrado['idVenta']=$idVenta;
	        $datosTimbrado['noCertificado']=$noc;
	        $datosTimbrado['tipoComp']='F';
	        $datosTimbrado['trackId']=$xml['@attributes']['trackId'];
	        $datosTimbrado['csdComplemento']='|1.0|'.$datosTimbrado['UUID'].'|'.$datosTimbrado['FechaTimbrado'].'|'.$datosTimbrado['selloCFD'].'|'.$datosTimbrado['noCertificadoSAT'];

	        $azurian['datosTimbrado']=$datosTimbrado;
	        //$cupon = cuponInadem($azurian['Receptor']['rfc']);
	        if(isset($appministra_nuevo)){
	        	if(!isset($positionPath))
	        	$positionPath="../../modulos";
	        	$xmlfile='_'.$rzst.'_'.$datosTimbrado['UUID'].'.xml';
	        	$archivo = fopen($positionPath.'/cont/xmls/facturas/temporales/'.$xmlfile.'','w');

	        	fwrite($archivo,$cadenaParseo[0]);
				fclose($archivo);
	        }else{
	        	$xmlfile='';
		        $cupon = '';
		        if($cupon==nul || $cupon==''){
			        if(!isset($positionPath))
			        	$positionPath="../../modulos";
			        $archivo = fopen($positionPath.'/facturas/'.$datosTimbrado['UUID'].'.xml','w');
			       	//$archivo = fopen($positionPath.'/facturas/'.$azurian['Receptor']['nombre'].'__'.$datosTimbrado['UUID'].'.xml','w');
					fwrite($archivo,$cadenaParseo[0]);
					fclose($archivo);	
		        }else{
			        if(!isset($positionPath))
			        	$positionPath="../../modulos";
			        $archivo = fopen($positionPath.'/facturas/'.$datosTimbrado['UUID'].'__'.str_replace(' ','_',$azurian['Receptor']['nombre']).'__'.$cupon.'.xml','w');
			       	//$archivo = fopen($positionPath.'/facturas/'.$azurian['Receptor']['nombre'].'__'.$datosTimbrado['UUID'].'.xml','w');
					fwrite($archivo,$cadenaParseo[0]);
					fclose($archivo);	        	
		        }

		        if(!isset($positionPath))
		        	$positionPath="../../modulos";
		        $archivo = fopen($positionPath.'/facturas/'.$datosTimbrado['UUID'].'__'.str_replace(' ','_',$azurian['Receptor']['nombre']).'__'.$cupon.'.xml','w');
		       	//$archivo = fopen($positionPath.'/facturas/'.$azurian['Receptor']['nombre'].'__'.$datosTimbrado['UUID'].'.xml','w');
				fwrite($archivo,$cadenaParseo[0]);
				fclose($archivo);
			}
			

			if($azurian['FiscalesEmisor']['noExterior']==''){
				$nemi='';
			}else{
				$nemi=' #'.$azurian['FiscalesEmisor']['noExterior'];
			}

			if($azurian['DomicilioReceptor']['noExterior']==''){
				$nrec='';
			}else{
				$nrec=' #'.$azurian['DomicilioReceptor']['noExterior'];
			}

			$JSON = array('success' =>1, 
				'estatus'=>'La factura se ha creado exitosamente.', 'azurian' =>json_encode($azurian),
				'datos'=>$datosTimbrado,
				'idVenta'=>$idVenta,
				'idCliente'=>$idCliente,
				'monto'=>$azurian['Basicos']['total'],
				'xmlfile'=>$xmlfile,
				'correo'=>$azurian['Correo']['Correo']);
			echo json_encode($JSON);
			exit();

	    }else{
	    	$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
				'error'=>111, 
				'mensaje'=>'Error durante el proceso de facturación', 
				'dump'=>'',
				'idVenta'=>$idVenta);
			echo json_encode($JSON);
	    	exit();
	    }

	} catch (SoapFault $exception) {
		$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
				'error'=>112, 
				'mensaje'=>'Por el momento el servicio esta presentando problemas externos a Netwarmonitor, Intentelo mas tarde.', 
				'dump'=>'Metodo: consultarEnvio()',
				'idVenta'=>$idVenta);
		echo json_encode($JSON);
		exit();
	}

	function cuponInadem($rfc){

			$servidor = "nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
			$objConG = mysqli_connect($servidor, "nmdevel", "nmdevel", "_dbmlog0000005471");

		    $strSqlG = "SELECT nombre from comun_facturacion where rfc='".$rfc."' order by nombre desc";
		    $rstWebconfigG = mysqli_query($objConG, $strSqlG);
		    while ($objWebconfigG = mysqli_fetch_assoc($rstWebconfigG)) {
		        $idClienteReceptor = $objWebconfigG['nombre'];
		        break;
		    }
		    $queryCupon = "SELECT cupon from comun_cliente_inadem where idCliente=".$idClienteReceptor;
		    $result = mysqli_query($objConG, $queryCupon);
		    while ($resultados = mysqli_fetch_assoc($result)) {
		        $cuponCliente = $resultados['cupon'];
		        break;
		    } 
		    return $cuponCliente;
		}

?>
