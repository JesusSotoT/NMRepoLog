<?php
	/* UTF8 AZURIAN
	======================================================= */
	$azurian = array_encode($azurian);

	if($bloqueo==1){
		$JSON = array('success' =>5, 'azurian' =>json_encode($azurian), 
			'error'=>5, 
			'mensaje'=>'Facturar ticket pendiente', 
			'dump'=>$valida,
			'idVenta'=>$idVenta);
		return;
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
		$comando='openssl pkcs8 -inform DER -in '.$key_cliente.' -passin pass:'.$pwd_cliente.' -out '.$pem.'';
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
		return;
	}
	if($r2==0){
		$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
			'error'=>114, 
			'mensaje'=>'El RFC Receptor esta mal formado'.$azurian['Receptor']['rfc'], 
			'dump'=>'',
			'idVenta'=>$idVenta);
		return;
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
				return;
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
		return;
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
		return;
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
				return;
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
		return;
	}

	if($azurian['Regimen']['Regimen']==''){
		$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
				'error'=>104, 
				'mensaje'=>'El dato de regimen fiscal esta vacio o mal capturado', 
				'dump'=>json_encode($azurian['Regimen']['Regimen']),
				'idVenta'=>$idVenta);
		return;
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
		return;
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
				return;
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
		return;
	}

	if($azurian['Conceptos']['conceptos']==''){
		$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
				'error'=>107, 
				'mensaje'=>'La cadena de conceptos esta mal formada o vacia', 
				'dump'=>$conceptos,
				'idVenta'=>$idVenta);
		return;
	}

	if($azurian['Impuestos']['ivas']==''){
		$JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
				'error'=>108, 
				'mensaje'=>'La cadena de impuestos esta mal formada o vacia', 
				'dump'=>$conceptos,
				'idVenta'=>$idVenta);
		return;
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
		return;
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
	$XML.="<?xml version='1.0' encoding='UTF-8'?><cfdi:Comprobante xmlns:cfdi='http://www.sat.gob.mx/cfd/3' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation='http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd'";
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
	$XML.='<cfdi:Impuestos ';
	if ($azurian['Impuestos']['totalImpuestosRetenidos']>0) {
		$XML.="totalImpuestosRetenidos='".$azurian['Impuestos']['totalImpuestosRetenidos']."' ";
	}
	$XML.=" totalImpuestosTrasladados='".$azurian['Impuestos']['totalImpuestosTrasladados']."'>";
	$XML.=$azurian['Impuestos']['ivas'];
	$XML.='</cfdi:Impuestos><cfdi:Complemento></cfdi:Complemento></cfdi:Comprobante>';


	$azurian['Receptor']['nombre']= preg_replace('/&amp;/', '&', $azurian['Receptor']['nombre']);
	$azurian['Conceptos']['conceptos']= preg_replace('/&amp;/', '&', $azurian['Conceptos']['conceptos']);
	$azurian['Emisor']['rfc']= preg_replace('/&amp;/', '&', $azurian['Emisor']['rfc']);
	$azurian['Receptor']['rfc']= preg_replace('/&amp;/', '&', $azurian['Receptor']['rfc']);
	/*----Comillas ------*/
	$azurian['Conceptos']['conceptos']= preg_replace('/&apos;/', "'", $azurian['Conceptos']['conceptos']);
	$azurian['Conceptos']['conceptos']= preg_replace('/&quot;/', '"', $azurian['Conceptos']['conceptos']);

	//echo $cadOri;
	//echo $XML;
	//INVOCAR WSINVOICE

	if(!isset($positionPath))
		$positionPath="../../modulos";

	include ($positionPath.'/wsinvoice/sealInvoice.php');
	
	//exit();
	
?>
