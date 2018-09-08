<?php
  require('controllers/captpolizas.php');
//Carga el modelo para este controlador
require("models/automaticasmonedaext.php");

class automaticasMonedaExt extends CaptPolizas
{
	
	public $automaticasMonedaExtModel;
	public $CaptpolizasModel;
	function __construct()
	{
		$this->automaticasMonedaExtModel = new automaticasMonedaExtModel();
		$this->CaptPolizasModel = $this->automaticasMonedaExtModel;
		$this->automaticasMonedaExtModel->connect();
	}
	function __destruct()
	{
		$this->automaticasMonedaExtModel->close();
	}
	function provisionmultipleExt(){
	$Exercise = $this->CaptPolizasModel->getExerciseInfo();
		$Ex = $Exercise->fetch_assoc();
		$moneda = $this->CaptPolizasModel->tipomoneda();
		$cuentaingresos=$this->CaptPolizasModel->cuentaproviciones('4.1',0);
		$cuentaegresos=$this->CaptPolizasModel->cuentaproviciones('4.2',1);
		$ListaSegmentos = $this->CaptPolizasModel->ListaSegmentos();
		$ListaSucursales = $this->CaptPolizasModel->ListaSucursales();
		$Suc = $this->CaptPolizasModel->getSegmentoInfo();
		$firstExercise = $this->CaptPolizasModel->getFirstLastExercise(0);
		$lastExercise = $this->CaptPolizasModel->getFirstLastExercise(1);
		$cuentaivas = $this->CaptPolizasModel->cuentaivas();
		$cuentas = $this->CaptPolizasModel->cuentasconf();
		if($row=$cuentas->fetch_array()){
			$iepspendientecobro = $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSPendienteCobro']);
			$iepspendientepago  = $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSPendientePago']);
			$ivapendientecobro  = $this->CaptPolizasModel->buscacuenta($row['CuentaIVAPendienteCobro']);
			$ivapendientepago   = $this->CaptPolizasModel->buscacuenta($row['CuentaIVAPendientePago']);
			$ishh				= $this->CaptPolizasModel->buscacuenta($row['ISH']);
			$ivaretenido 		= $this->CaptPolizasModel->buscacuenta($row['IVAretenido']);
			$isrretenido		= $this->CaptPolizasModel->buscacuenta($row['ISRretenido']);
			$statusIVAIEPS      = $row['statusIVAIEPS'];
			$statusRetencionISH = $row['statusRetencionISH'];
			$statusIEPS		    = $row['statusIEPS'];
			$statusIVA		    = $row['statusIVA'];
			$CuentaIEPSgasto		= $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSgasto']);
			$CuentaIVAgasto		= $this->CaptPolizasModel->buscacuenta($row['CuentaIVAgasto']);
		
		}
		$iepspendientecobro = explode("//", $iepspendientecobro);
		$ivapendientecobro = explode("//", $ivapendientecobro);
		$iepspendientepago = explode("//", $iepspendientepago);
		$ivapendientepago = explode("//", $ivapendientepago);
		$ishh = explode("//", $ishh);
		$ivaretenido = explode("//", $ivaretenido); 
		$isrretenido = explode("//", $isrretenido);
		$CuentaIEPSgasto = explode("//", $CuentaIEPSgasto);
		$CuentaIVAgasto  = explode("//",$CuentaIVAgasto);
		
		if(intval($Ex['IdEx']) AND intval($Suc))// Si existe el ejercicio
		{
		 	$Abonos = $this->CaptPolizasModel->GetAbonosCargos('Abono','Periodo',$Ex['PeriodoActual'],$Ex['IdEx']);
			$Cargos = $this->CaptPolizasModel->GetAbonosCargos('Cargo','Periodo',$Ex['PeriodoActual'],$Ex['IdEx']);
			$per  = $Ex['PeriodoActual'];
			if($per<10){ $per='0'.$per;}
			$lista = $this->automaticasMonedaExtModel->listaTipoCambio(0,$Ex['NombreEjercicio']."-".$per);
			
			if($_REQUEST['detalle']){
				include('views/captpolizas/monedaext/provisiondetalladoextranjera.php');
			}else{
 				include('views/captpolizas/monedaext/provisionextranjera.php');			}
		}
		else
		{
			echo "<script>alert('Antes de capturar polizas configure un ejercicio y tambien Segmentos de Negocio.');window.parent.agregatab('../../modulos/cont/index.php?c=Config','Configuración Ejercicios','',142);window.parent.agregatab('../catalog/gestor.php?idestructura=251&ticket=testing','Segmentos de Negocio','',1656)</script>";
		}
				 
	}
	function listaTipoCambio(){
		unset($_SESSION['datosext']['cuentaClienteslista']);
		unset($_SESSION['datosext']['cuentaProveelista']);
		$lista = $this->automaticasMonedaExtModel->listaTipoCambio($_REQUEST['idmoneda'],$_REQUEST['periodo']);
		$_SESSION['datosext']['moneda'] = $_REQUEST['idmoneda'];
		$clientes = $this->CaptPolizasModel->clientesCuentas($_REQUEST['idmoneda']);
		$clie = array();
		while($c = $clientes->fetch_assoc()){
			$clie['account_id']=$c['account_id'];
			$clie['description']=$c['description'];
			$clie['manual_code']=$c['manual_code'];
			$_SESSION['datosext']['cuentaClienteslista'][]= $clie;
		}
		$prove = $this->CaptPolizasModel->cuentasprove($_REQUEST['idmoneda']);
		$clie = array();
		while($c = $prove->fetch_assoc()){
			$clie['account_id']=$c['account_id'];
			$clie['description']=$c['description'];
			$clie['manual_code']=$c['manual_code'];
			$_SESSION['datosext']['cuentaProveelista'][]= $clie;
		}
		
		$tipocambiolista="<option value='0'>Elija una Moneda</option>";
		while ($row = $lista->fetch_assoc()){ $_SESSION['datosext']['codigo']=$row['codigo'];
			$tipocambiolista.= "<option value='".$row['tipo_cambio']."'>".$row['fecha']." (".$row['tipo_cambio'].")</option>";	
		}
		echo $tipocambiolista;
		//$_SESSION['datosext']['listatipocambio'] = $tipocambiolista;
	}
	
	function guardaProvisionMultiple(){
	$error=false;
	global $xp;
	$comprobante = $_REQUEST['comprobante'];
	if($_REQUEST['tipocambio']!=0){
		$tipocambio = $_REQUEST['tipocambio'];
		$_SESSION['datosext']['tipocambio'] = $tipocambio;
	}
	$facturasNoValidas = $facturasValidas = '';
	$numeroInvalidos = $numeroValidos = $no_hay_problema = $noOrganizacion = 0;
	$maximo = count($_FILES['xml']['name']);
	$maximo = (intval($maximo)-1);
	for($i = 0; $i <= $maximo; $i++){
		if($_FILES["xml"]["size"][$i] > 0){
	$retencion=array();$retencionmxn = array();$agregaprove = array(); $previo = array();
			$file 	= $_FILES['xml']['tmp_name'][$i];
			$texto 	= file_get_contents($file);
			$texto 	= preg_replace('{<Addenda.*/Addenda>}is', '<Addenda/>', $texto);
			$texto 	= preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '<cfdi:Addenda/>', $texto);
			$texto = preg_replace('{<ComplementoConcepto.*/ComplementoConcepto>}is', '<ComplementoConcepto/>', $texto);
            $texto = preg_replace('{<cfdi:ComplementoConcepto.*/cfdi:ComplementoConcepto>}is', '<cfdi:ComplementoConcepto/>', $texto);
				
			$xml 	= new DOMDocument();
			$xml->loadXML($texto);
			
			$xp = new DOMXpath($xml);
			// $data['uuid'] 	= $this->getpath("//@UUID");
			// $data['folio'] 	= $this->getpath("//@folio");
			// $data['emisor'] = $this->getpath("//@nombre");
			// $data['version'] = $this->getpath("//@version");
			// $data['fecha'] = $this->getpath("//@FechaTimbrado");
			 $moneda 	= $this->getpath("//@Moneda");
			$monedacambio = $this->getpath("//@TipoCambio");
		
			$ishimport = $this->getpath("//@TotaldeTraslados");
									
			$separa = explode("T",$data['fecha']);
			$fechatp = $separa[0];
			if($_REQUEST['tipocambio']==0){
				$infotp = $this->automaticasMonedaExtModel->tipocambioXfecha($fechatp, $_REQUEST['moneda']);
				if($infotp->num_rows>0){
					if($t=$infotp->fetch_object()){
						$tipocambio =$t->tipo_cambio;
						$_SESSION['datosext']['tipocambio'] = $tipocambio;
					}
				}else{
					echo '<script> alert("No selecciono un Tipo de Cambio \nY la fecha de su factura no tiene un registro de Tipo Cambiario"); window.location="index.php?c=automaticasMonedaExt&f=provisionmultipleExt&detalle='.$_REQUEST['detalle'].'"; </script>';
					exit(0);
				}
			
			}
			//$version = $data['version'];
			//$data['total'] = $this->getpath("//@total");
			//$data['rfc'] = $this->getpath("//@rfc");
			
				if($this->getpath("//@version"))
                	$data['version'] = $this->getpath("//@version");
                else
                	$data['version'] = $this->getpath("//@Version");
			$version = $data['version'];
			
			if($version[0] == '3.3')
			{
				
				$data['rfc'] = $this->getpath("//@Rfc");
				$ishimport = $this->getpath("//@TotaldeTraslados");
				$fecha= $this->getpath("//@Fecha");
				$total = $this->getpath("//@Total");
				$subTotal = $this->getpath("//@SubTotal");
				$data['uuid'] = 	$this->getpath("//@UUID");
				$data['uuid'] = $data['uuid'][1];
				$folio 	= $this->getpath("//@Folio");
				$data['emisor'] = $this->getpath("//@Nombre");
				$descuento = $this->getpath("//@Descuento");
				if($descuento){
					$subTotal= floatval($subTotal)-floatval($descuento);
				}
			}else{
				$data['rfc'] = $this->getpath("//@rfc");
				$ishimport = $this->getpath("//@TotaldeTraslados");
				$fecha= $this->getpath("//@fecha");
			    $total=$this->getpath("//@total");
			    $subTotal= $this->getpath("//@subTotal");
				$data['uuid'] = 	$this->getpath("//@UUID");
				$folio 	= $this->getpath("//@folio");
				$data['emisor'] = $this->getpath("//@nombre");
				
				$descuento = $this->getpath("//@descuento");
				if($descuento){
					$subTotal= floatval($subTotal)-floatval($descuento);
					//$subTotal= number_format($subTotal,2,'.','') - number_format($cfdiComprobante['descuento'],2,'.','');
				}
			}
			
			
			$rfcOrganizacion= $this->CaptPolizasModel->rfcOrganizacion();
			
			if($data['rfc'][0] == $rfcOrganizacion['RFC'])
				{
					$tipoDeComprobante = "Ingreso";$nombre = $data['emisor'][1];
				}
				elseif($data['rfc'][1] == $rfcOrganizacion['RFC'])
				{
					$tipoDeComprobante = "Egreso";	$nombre = $data['emisor'][0];
					
				}
				
			//Termina obtener UUID---------------------------
			
			if($this->valida_xsd($version[0],$xml) && $_FILES['xml']['type'][$i] == "text/xml")
			{
				
				if($version[0] == '3.2'){
					$no_hay_problema = $this->valida_en_sat($data['rfc'][0],$data['rfc'][1],$data['total'],$data['uuid']);
				}
				else{
					$no_hay_problema = 1;
				}
				if($rfcOrganizacion['RFC'] != $data['rfc'][0] &&  $rfcOrganizacion['RFC']!= $data['rfc'][1]){
					$noOrganizacion = 0;
					$numeroInvalidos++;
					$facturasNoValidas .= $_FILES['xml']['name'][$i]."(RFC no de Organizacion),\\n";
				}else{ $noOrganizacion = 1; }
				
				$nombreArchivo = $folio."_".$nombre."_".$data['uuid'].".xml";
				if($noOrganizacion){
					$validaexiste = $this->existeXML($nombreArchivo);
					if($validaexiste){
						$noOrganizacion = 0;
						$numeroInvalidos++;
						$facturasNoValidas .= $_FILES['xml']['name'][$i].",Ya existe en $validaexiste.\\n";
					}else{ $noOrganizacion = 1; }
				}
				if($noOrganizacion){
					if( $moneda=="MXN" || $moneda=="Peso Mexicano" || $moneda=="MN" || $moneda=="MXP" || $moneda=="Pesos" || $moneda=="M.N." || $moneda=="M.X.N." || $moneda=="Pesos Mexicanos" || !$monedacambio){
						$noOrganizacion = 0;
						$numeroInvalidos++;
						$facturasNoValidas .= $_FILES['xml']['name'][$i].",El xml esta en Pesos Mexicanos.\\n";
					}else{
							$noOrganizacion = 1; 
					}
					
				}
				
				if($noOrganizacion){
					if($no_hay_problema)
					{
		
		
						$xml = simplexml_load_file($_FILES['xml']['tmp_name'][$i]);
						$ns = $xml -> getNamespaces(true);
						$xml -> registerXPathNamespace('c', $ns['cfdi']);//altera los prefijos del namespace
						$xml -> registerXPathNamespace('t', $ns['tfd']);
				 		// foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){ 
						    // $fecha= $cfdiComprobante['fecha']; 
						    // $total=($cfdiComprobante['total']);
						    // $subTotal= $cfdiComprobante['subTotal']; 
							// if($cfdiComprobante['descuento']){
								// $subTotal= floatval($subTotal)-floatval($cfdiComprobante['descuento']);
								// //$subTotal= number_format($subTotal,2,'.','') - number_format($cfdiComprobante['descuento'],2,'.','');
							// }
						// } 
						$fec = explode("T", $fecha);
						
							if($_REQUEST['fecha']){
								$_SESSION['fechaprovi']=$_REQUEST['fecha'];
							}else{
								$_SESSION['fechaprovi']=$fec[0]."";
							}
						
						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){
							
							if($version[0] == '3.3'){
						   		$rfcemisor= $Emisor['Rfc'];
						   		$nombreemisor= utf8_decode($Emisor['Nombre']);
						   		$nombreemisor2= ($Emisor['Nombre']);
						   	}else{
						   		$rfcemisor= $Emisor['rfc'];
						   		$nombreemisor= utf8_decode($Emisor['nombre']);
						   		$nombreemisor2= ($Emisor['nombre']);
							} 
						   // $rfcemisor= $Emisor['rfc']; 
						   // $nombreemisor= utf8_decode($Emisor['nombre']); 
						   // $nombreemisor2= ($Emisor['nombre']); 
						   $agregaprove[]=$nombreemisor.""; $agregaprove[]=$rfcemisor."";
						} 
						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:DomicilioFiscal') as $DomicilioFiscal){
						   if($version[0] == '3.3'){
								$calleemisor = $noExterioremisor = "N/A";
								$municipioemisor = 0;
						  }else{  
							   $paisemisor= $DomicilioFiscal['pais']; 
							   $calleemisor= $DomicilioFiscal['calle']; 
							   $estadoemisor= $DomicilioFiscal['estado']; 
							   $coloniaemisor= $DomicilioFiscal['colonia']; 
							   $municipioemisor= $DomicilioFiscal['municipio']; 
							   $noExterioremisor= $DomicilioFiscal['noExterior']; 
						  	   $codigoPostalemisor= $DomicilioFiscal['codigoPostal'];
						   } 
						   $agregaprove[]=$calleemisor." ".$noExterioremisor;
						   $agregaprove[]=$municipioemisor."";
						} 
						
						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor){ 
						   if($version[0] == '3.3'){
								$rfcreceptor= $Receptor['Rfc'];
						  	 	$nombrereceptor=utf8_decode($Receptor['Nombre']);
							}else{
								$rfcreceptor= $Receptor['rfc']; 
						   		$nombrereceptor=utf8_decode($Receptor['nombre']); 
							}
						} 
						$receptorcliente = array();
						$receptorcliente[]=$nombrereceptor."";$receptorcliente[] = $rfcreceptor."";
						if($version[0] == '3.3'){
							
							foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor//cfdi:Domicilio') as $ReceptorDomicilio){ 
							   $callerecep= $ReceptorDomicilio['calle']; 
							   $estadorecep= $ReceptorDomicilio['estado']; 
							   $coloniarecep= $ReceptorDomicilio['colonia']; 
							   $municipiorecep= $ReceptorDomicilio['municipio']; 
							   $noExteriorrecep= $ReceptorDomicilio['noExterior']; 
							   $noInteriorrecep= $ReceptorDomicilio['noInterior']; 
							   $codigoPostalrecep= $ReceptorDomicilio['codigoPostal']; 
							   $receptorcliente[] = $callerecep." ".$noExteriorrecep;
							   $receptorcliente[] = $coloniarecep."";
							   $receptorcliente[] = $codigoPostalrecep."";
							   $receptorcliente[] = $municipiorecep."";
							} 
						}
				//($nombrereceptor, $callerecep." ".$noExteriorrecep, $coloniarecep, $codigoPostalrecep, $idestado, $idmunicipio, $rfcreceptor,$idcuentacliente);
						
						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Traslado){ 
						   $tasa=$Traslado['tasa']; 
						   if($version[0] == '3.3'){
					  			$Traslado['importe']		= $Traslado['Importe'];
								$Traslado['impuesto'] 	= $this->CaptPolizasModel->nombreImpuestoIndividual($Traslado['Impuesto']);
							}
						  if($Traslado['impuesto']=="IVA"){
						  			if($Traslado['importe']>0){
						  				if($comprobante==1 || $comprobante==3){	
											$previo['cliente']['abono2']=floatval($Traslado['importe']);
											$previo['cliente']['abono2mxn'] = floatval($Traslado['importe']) * floatval($tipocambio);
										}
										if($comprobante==2 || $comprobante==4){
							  				$previo['proveedor']['cargo2']=floatval($Traslado['importe']);
											$previo['proveedor']['cargo2mxn']=floatval($Traslado['importe']) * floatval($tipocambio);
										}
									}
								}
								if($Traslado['impuesto']=="IEPS"){
									if($Traslado['importe']>0){
										if($comprobante==1 || $comprobante==3){
											$previo['cliente']['ieps']=floatval($Traslado['importe']);
											$previo['cliente']['iepsmxn']=floatval($Traslado['importe']) * floatval($tipocambio);
										}
										if($comprobante==2 || $comprobante==4){
											$previo['proveedor']['ieps']=floatval($Traslado['importe']);
											$previo['proveedor']['iepsmxn']=floatval($Traslado['importe']) * floatval($tipocambio);
										}
									}
								}
						} 
						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos') as $Traslad){
							if($version[0] == '3.3'){
								$Traslad['totalImpuestosTrasladados'] = $Traslad['TotalImpuestosTrasladados'];
							}
							$importe=$Traslad['totalImpuestosTrasladados'];
						}
						foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
				  			$UUID= $tfd['UUID']; 
						} 
						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Retenciones//cfdi:Retencion') as $retenido){
								
							if($version[0] == '3.3'){
								$retenido["impuesto"] = $this->CaptPolizasModel->nombreImpuestoIndividual($retenido["Impuesto"]);
								$retenido['importe']	  = $retenido['Importe'];
							}
							$retencion["$retenido[impuesto]"]= number_format(floatval($retenido['importe']),2,'.','');
							$retencionmxn["$retenido[impuesto]"]= floatval($retenido['importe']) * floatval($tipocambio);  
							
						}
						
						foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){
							if($version[0] == '3.3'){
								$cfdiComprobante['folio'] = $cfdiComprobante['Folio'];
							}
								 $folio=$cfdiComprobante['folio']; 
						}
						$conceptosdetalle=array();$precioimporte=array();$precioimportemxn=array();
						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $concepto){
							if($version[0] == '3.3'){
								$concepto['descripcion']	= $concepto['Descripcion'];
								$concepto['importe']		= $concepto['Importe'];			
							}
								
							$conceptosdetalle[] = $concepto['descripcion']."";
							$precioimporte[] = $concepto['importe']."";
							$precioimportemxn[] = floatval($concepto['importe']) * floatval($tipocambio);  
							$concepto = $concepto['descripcion'].""; 
							
						}
						if(!$ishimport){
							$ishimport=0;
							foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//implocal:ImpuestosLocales//implocal:TrasladosLocales') as $ish){
						 		$ishimport=$ish['Importe'];
							}
						}
						$xmlreal = $this->quitar_tildes($folio."_".$nombre."_".$UUID.".xml");
						if($comprobante==1){
							$xmld=$this->quitar_tildes($folio."-Cobro"."_".$nombre."_".$UUID.".xml");
						}
						if($comprobante==2){
							$xmld=$this->quitar_tildes($folio."-Pago"."_".$nombre."_".$UUID.".xml");
						}
						if($comprobante==3 || $comprobante==4){
							$xmld=$this->quitar_tildes($folio."_".$nombre."_".$UUID.".xml");
						}
						
						$_SESSION['comprobante']=$comprobante;
					if($comprobante==1 || $comprobante==3){
							$previo['cliente']['tipo_cambio']= $tipocambio;
							$previo['cliente']['agregacliente'] = $receptorcliente; 
							$previo['cliente']['retenidos']=$retencion;
							$previo['cliente']['retenidosmxn']=$retencionmxn;
							$previo['cliente']['nombre']=utf8_encode($nombrereceptor);
							$previo['cliente']['abono']=number_format(floatval($subTotal),2,'.','');
							$previo['cliente']['abonomxn']=$previo['cliente']['abono'] * floatval($tipocambio);
							$previo['cliente']['concepto']=$concepto."";
							$previo['cliente']['cargo']=number_format(floatval($total),2,'.','');
							$previo['cliente']['cargomxn']=$previo['cliente']['cargo']* floatval($tipocambio) ;
							$previo['cliente']['xml']=($xmld);
							$previo['cliente']['xmlreal']=($xmlreal);
							$previo['cliente']['referencia']=$UUID."";
							$previo['cliente']['ish']=number_format(floatval($ishimport),2,'.','');
							$previo['cliente']['ishmxn']=$previo['cliente']['ish'] * floatval($tipocambio);
							$previo['cliente']['conceptodetalle']= $conceptosdetalle;
							$previo['cliente']['preciodetalle']= $precioimporte;
							$previo['cliente']['preciodetallemxn']= $precioimportemxn;
							$consultacliente=$this->CaptPolizasModel->consultacliente($rfcreceptor);
							$previo['cliente']['ordenador']=1;
							$previo['cliente']['listacliente'] =0;
							// if($consultacliente->num_rows>0){
									// if( $re = $consultacliente->fetch_array() ){
										// if( $re['cuenta'] <= 0 ){//sino tienen cuenta
											// $previo['cliente']['listacliente'] =0;
// 											 
										// }
									// }
								// }else{
									// $previo['cliente']['listacliente'] =0;
								// }
							$_SESSION['provisioncliente'][]=$previo;
					 }else if($comprobante==2 || $comprobante==4){
					 		$previo['proveedor']['tipo_cambio']= $tipocambio;
					 		$previo['proveedor']['agregaprovee'] = $agregaprove;
							$previo['proveedor']['ish']=number_format(floatval($ishimport),2,'.','');
							$previo['proveedor']['ishmxn']=number_format(floatval($previo['proveedor']['ish'] * $tipocambio),2,'.','');;
							$previo["proveedor"]['retenidos']=$retencion;
							$previo["proveedor"]['retenidosmxn']=$retencionmxn;
							$previo["proveedor"]['nombre']=utf8_encode($nombreemisor);
							$previo["proveedor"]['concepto']=$concepto."";
							$previo["proveedor"]['cargo']=number_format(floatval($subTotal),2,'.','');
							$previo["proveedor"]['cargomxn']=number_format(floatval($previo["proveedor"]['cargo'] *$tipocambio),2,'.','');;
							$previo["proveedor"]['abono']=number_format(floatval($total),2,'.','');
							$previo["proveedor"]['abonomxn']=number_format(floatval($previo["proveedor"]['abono'] * $tipocambio),2,'.','');
							$previo["proveedor"]['xml']=($xmld);
							$previo["proveedor"]['xmlreal']=($xmlreal);
							$previo["proveedor"]['referencia']=$UUID."";
							$previo['proveedor']['conceptodetalle']= $conceptosdetalle;
							$previo['proveedor']['preciodetalle']= $precioimporte;
							$previo['proveedor']['preciodetallemxn']= $precioimportemxn;
							$previo['proveedor']['ordenador']=1;
							$previo['proveedor']['listaprove'] = 0;
							$emisor=$this->CaptPolizasModel->emisorprove($rfcemisor,$nombreemisor);
							// if($emisor->num_rows>0){
								// if( $re = $emisor->fetch_array() ){
								  // if( $re['cuenta'] == " " || $re['cuenta'] == 0){//sino tienen cuenta
								  	// $previo['proveedor']['listaprove'] = 0;
								  // }
								// }
							// }else{
								// $previo['proveedor']['listaprove'] = 0;
							// }
							//PROVAR CON RETENCIONES EL PROVEEDORES
						 $_SESSION['poliprove'][]=$previo;
						}//comprobante 2
						move_uploaded_file($_FILES['xml']['tmp_name'][$i],$this->path().'xmls/facturas/temporales/'.($xmlreal));
						
						$numeroValidos++;
						//$facturasValidas .= $_FILES['xml']['name'][$i].",\n";
					}
					else{
						$numeroInvalidos++;
						$facturasNoValidas .= $_FILES['xml']['name'][$i]."(Cancelada),\\n";
					}
				}
					
			}
			else{
				$numeroInvalidos++;
				$facturasNoValidas .= $_FILES['xml']['name'][$i]."(Estructura invalida),\\n";
			}
		}
	}
		$cadena = "";
		if ($numeroInvalidos!=0){
			$cadena = 'alert("Facturas no validas:\n'.$facturasNoValidas.'PARA AGREGAR FACTURAS EXISTENTES REALIZARLO DESDE ALMACEN");';
		}else{
			$cadena = "alert('Facturas validas');";
		}
		echo '<script>'.$cadena.' window.location="index.php?c=automaticasMonedaExt&f=provisionmultipleExt&detalle='.$_REQUEST['detalle'].'"; </script>';
	}
function guardaProvisionMultipleAlmacen(){
		$error=false;
		$comprobante = $_REQUEST['comprobante'];
		
		if($_REQUEST['tipocambio']!=0){
			$tipocambio = $_REQUEST['tipocambio'];
			$_SESSION['datosext']['tipocambio'] = $tipocambio;
		}
		$maximo = count($_POST['xml']);
		$maximo = (intval($maximo)-1);
		for($i=0;$i<=$maximo;$i++){
			$previo = array();$retencion=array();$retencionmxn =array(); $agregaprove = array();	
			global $xp;
			$archivo = $_POST['xml'][$i];	
			$texto 	= file_get_contents($archivo);
			$texto 	= preg_replace('{<Addenda.*/Addenda>}is', '<Addenda/>', $texto);
			$texto 	= preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '<cfdi:Addenda/>', $texto);
			$xmld 	= new DOMDocument();
			$xmld->loadXML($texto);
			$xp = new DOMXpath($xmld);
			$ishimport = $this->getpath("//@TotaldeTraslados");
			if($this->getpath("//@version"))
                	$data['version'] = $this->getpath("//@version");
                else
                	$data['version'] = $this->getpath("//@Version");
			$version = $data['version'];
			if($version[0] == '3.3')
			{
				
				$data['rfc'] = $this->getpath("//@Rfc");
				$ishimport = $this->getpath("//@TotaldeTraslados");
				$fecha= $this->getpath("//@Fecha");
				$total = $this->getpath("//@Total");
				$subTotal = $this->getpath("//@SubTotal");
				$data['uuid'] = 	$this->getpath("//@UUID");
				$data['uuid'] = $data['uuid'][1];
				$folio 	= $this->getpath("//@Folio");
				$data['emisor'] = $this->getpath("//@Nombre");
				$descuento = $this->getpath("//@Descuento");
				if($descuento){
					$subTotal= floatval($subTotal)-floatval($descuento);
				}
			}else{
				$data['rfc'] = $this->getpath("//@rfc");
				$ishimport = $this->getpath("//@TotaldeTraslados");
				$fecha= $this->getpath("//@fecha");
			    $total=$this->getpath("//@total");
			    $subTotal= $this->getpath("//@subTotal");
				$data['uuid'] = 	$this->getpath("//@UUID");
				$folio 	= $this->getpath("//@folio");
				$data['emisor'] = $this->getpath("//@nombre");
				
				$descuento = $this->getpath("//@descuento");
				if($descuento){
					$subTotal= floatval($subTotal)-floatval($descuento);
					//$subTotal= number_format($subTotal,2,'.','') - number_format($cfdiComprobante['descuento'],2,'.','');
				}
			}
			$xml = simplexml_load_file($_POST['xml'][$i]);
			$ns = $xml -> getNamespaces(true);
			$xml -> registerXPathNamespace('c', $ns['cfdi']);//altera los prefijos del namespace
			$xml -> registerXPathNamespace('t', $ns['tfd']);
	 		// foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){ 
			    // $fecha= $cfdiComprobante['fecha']; 
			    // $total=($cfdiComprobante['total']);
			    // $subTotal= $cfdiComprobante['subTotal']; 
				// if($cfdiComprobante['descuento']){
					// $subTotal= floatval($subTotal)-floatval($cfdiComprobante['descuento']);
					// //$subTotal= number_format($subTotal,2,'.','') - number_format($cfdiComprobante['descuento'],2,'.','');
				// }
			// }
 			
			
			$fec = explode("T", $fecha);
			$fechatp = $fec[0];
			if($_REQUEST['tipocambio']==0){ 
				$infotp = $this->automaticasMonedaExtModel->tipocambioXfecha($fechatp, $_REQUEST['moneda']);
				if($infotp->num_rows>0){
					if($t=$infotp->fetch_object()){
						$tipocambio = $t->tipo_cambio;
						$_SESSION['datosext']['tipocambio'] = $tipocambio;
					}
				}else{
					echo '<script> alert("No selecciono un Tipo de Cambio \nY la fecha de su factura no tiene un registro de Tipo Cambiario"); window.location="index.php?c=automaticasMonedaExt&f=provisionmultipleExt&detalle='.$_REQUEST['detalle'].'"; </script>';
					exit(0);
				}
			
			}
			// if(!isset($_SESSION['fechaprovi'])){
				// $_SESSION['fechaprovi']=$fec[0]."";
			//}else{
				if($_REQUEST['fecha']){
					$_SESSION['fechaprovi']=$_REQUEST['fecha'];
				}else{
					$_SESSION['fechaprovi']=$fec[0]."";
				}
			//}
			foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){
				if($version[0] == '3.3'){
					$rfcemisor= $Emisor['Rfc'];
			   		$nombreemisor= utf8_decode($Emisor['Nombre']);
				}else{
			   		$rfcemisor= $Emisor['rfc'];
			  		$nombreemisor= utf8_decode($Emisor['nombre']);
				}
			   $rfcemisor= $Emisor['rfc']; 
			   $nombreemisor= utf8_decode($Emisor['nombre']); 
			   $agregaprove[]=$nombreemisor.""; $agregaprove[]=$rfcemisor."";
			}
			if($version[0] != '3.3'){ 
				foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:DomicilioFiscal') as $DomicilioFiscal){ 
				   $paisemisor= $DomicilioFiscal['pais']; 
				   $calleemisor= $DomicilioFiscal['calle']; 
				   $estadoemisor= $DomicilioFiscal['estado']; 
				   $coloniaemisor= $DomicilioFiscal['colonia']; 
				   $municipioemisor= $DomicilioFiscal['municipio']; 
				   $noExterioremisor= $DomicilioFiscal['noExterior']; 
				   $codigoPostalemisor= $DomicilioFiscal['codigoPostal']; 
				   $agregaprove[]=$calleemisor." ".$noExterioremisor;
				   $agregaprove[]=$municipioemisor."";
				} 
			}			
			foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor){
				if($version[0] == '3.3'){
					$rfcreceptor= $Receptor['Rfc'];
			  	 	$nombrereceptor=utf8_decode($Receptor['Nombre']);
				}else{
			   		$rfcreceptor= $Receptor['rfc'];
			   		$nombrereceptor=utf8_decode($Receptor['nombre']);
				} 
			   $rfcreceptor= $Receptor['rfc']; 
			   $nombrereceptor=utf8_decode($Receptor['nombre']); 
			} 
			$receptorcliente = array();
			$receptorcliente[]=$nombrereceptor."";$receptorcliente[] = $rfcreceptor."";
			if($version[0] != '3.3'){
				foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor//cfdi:Domicilio') as $ReceptorDomicilio){ 
				   $callerecep= $ReceptorDomicilio['calle']; 
				   $estadorecep= $ReceptorDomicilio['estado']; 
				   $coloniarecep= $ReceptorDomicilio['colonia']; 
				   $municipiorecep= $ReceptorDomicilio['municipio']; 
				   $noExteriorrecep= $ReceptorDomicilio['noExterior']; 
				   $noInteriorrecep= $ReceptorDomicilio['noInterior']; 
				   $codigoPostalrecep= $ReceptorDomicilio['codigoPostal']; 
				   $receptorcliente[] = $callerecep." ".$noExteriorrecep;
				   $receptorcliente[] = $coloniarecep."";
				   $receptorcliente[] = $codigoPostalrecep."";
				   $receptorcliente[] = $municipiorecep."";
				} 
			}
						
			foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Traslado){ 
			   $tasa=$Traslado['tasa'];
				 if($version[0] == '3.3'){
			   		$Traslado['importe'] = $Traslado['Importe'];
				   $Traslado['impuesto'] =$this->CaptPolizasModel->nombreImpuestoIndividual( $Traslado['Impuesto']);
			   } 
			  if($Traslado['impuesto']=="IVA"){
			  			if($Traslado['importe']>0){
			  				if($comprobante==1 || $comprobante==3){	
								$previo['cliente']['abono2']=floatval($Traslado['importe']);
								$previo['cliente']['abono2mxn'] = floatval($Traslado['importe']) *  floatval($tipocambio);
								
							}
							if($comprobante==2 || $comprobante==4){
				  				$previo['proveedor']['cargo2']=floatval($Traslado['importe']);
								$previo['proveedor']['cargo2mxn']=floatval($Traslado['importe']) * floatval($tipocambio);
								
							}
						}
					}
					if($Traslado['impuesto']=="IEPS"){
						if($Traslado['importe']>0){
							if($comprobante==1 || $comprobante==3){
								$previo['cliente']['ieps']=floatval($Traslado['importe']);
								$previo['cliente']['iepsmxn']=floatval($Traslado['importe']) * floatval($tipocambio);
								
							}
							if($comprobante==2 || $comprobante==4){
								$previo['proveedor']['ieps']=floatval($Traslado['importe']);
								$previo['proveedor']['iepsmxn']=floatval($Traslado['importe']) * floatval($tipocambio);
								
							}
						}
					}
			} 
			foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos') as $Traslad){
				if($version[0] == '3.3'){
					$importe=$Traslad['TotalImpuestosTrasladados'];
				}else{
					$importe=$Traslad['totalImpuestosTrasladados'];
				}
			}
			foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
	  			$UUID= $tfd['UUID']; 
			} 
			foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Retenciones//cfdi:Retencion') as $retenido){
				if($version[0] == '3.3'){
		  			$retenido['impuesto']	= $this->CaptPolizasModel->nombreImpuestoIndividual($retenido['Impuesto']);
					$retenido['importe'] 	= $retenido['Importe'];
				}
				$retencion["$retenido[impuesto]"]= number_format(floatval($retenido['importe']),2,'.',''); 
				$retencionmxn["$retenido[impuesto]"]= floatval($retenido['importe']) * floatval($tipocambio);  
				
			}
						
			foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){
				if($version[0] == '3.3'){
					 $folio=$cfdiComprobante['Folio']; 
				}else{
					 $folio=$cfdiComprobante['folio']; 
				}
			}
			$conceptosdetalle=array();$precioimporte=array();
			foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $concepto){
				if($version[0] == '3.3'){
					$concepto['descripcion']= $concepto['Descripcion'];
					$concepto['importe']		= $concepto['Importe'];
				}
				$conceptosdetalle[] = $concepto['descripcion']."";
				$precioimporte[] = $concepto['importe']."";
				$precioimportemxn[] = floatval($concepto['importe']) * floatval($tipocambio); 
				$concepto = $concepto['descripcion'].""; 
				
			}
			if(!$ishimport){
				$ishimport=0;
				foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//implocal:ImpuestosLocales//implocal:TrasladosLocales') as $ish){
			 		$ishimport=$ish['Importe'];
				}
			}
			$archivopar = explode("/",$_POST['xml'][$i]);
			$xmlreal = $archivopar[3];
			$archivopar[3] = str_replace("-Cobro", "", $archivopar[3]);
			$archivopar[3] = str_replace("-Pago", "", $archivopar[3]);
			$archivopar[3] = str_replace("-Nomina", "", $archivopar[3]);

			$separa = explode("_",$archivopar[3]);
			if($comprobante==1){
				$xmld=$this->quitar_tildes($separa[0]."-Cobro_".$separa[1]."_".$separa[2]);
			}
			if($comprobante==2){
				$xmld=$this->quitar_tildes($separa[0]."-Pago_".$separa[1]."_".$separa[2]);
			}
			if($comprobante==3 || $comprobante==4){
				$xmld=$this->quitar_tildes($archivopar[3]);
			}
			
			$_SESSION['comprobante']=$comprobante;
		
			if($comprobante==1 || $comprobante==3){
				$previo['cliente']['tipo_cambio']= $tipocambio;
				$previo['cliente']['agregacliente'] = $receptorcliente; 
				$previo['cliente']['retenidos']=$retencion;
				$previo['cliente']['retenidosmxn']=$retencionmxn;
				$previo['cliente']['nombre']=utf8_encode($nombrereceptor);
				$previo['cliente']['abono']=number_format(floatval($subTotal),2,'.','');
				$previo['cliente']['abonomxn']=number_format(floatval($previo['cliente']['abono']) * floatval($tipocambio),2,'.','');
				$previo['cliente']['concepto']=$concepto."";
				$previo['cliente']['cargo']=number_format(floatval($total),2,'.','');
				$previo['cliente']['cargomxn']=number_format(floatval($previo['cliente']['cargo'])* floatval($tipocambio),2,'.','') ;
				$previo['cliente']['xml']=($xmld);
				$previo['cliente']['xmlreal']=($xmlreal);
				$previo['cliente']['referencia']=$UUID."";
				$previo['cliente']['ish']=number_format(floatval($ishimport),2,'.','');
				$previo['cliente']['ishmxn']=number_format(floatval($previo['cliente']['ish']) * floatval($tipocambio),2,'.','');
				$previo['cliente']['conceptodetalle']= $conceptosdetalle;
				$previo['cliente']['preciodetalle']= $precioimporte;
				$previo['cliente']['preciodetallemxn']= $precioimportemxn;
				$previo['cliente']['listacliente'] =0;
				//$consultacliente=$this->CaptPolizasModel->consultacliente($rfcreceptor);
				// if($consultacliente->num_rows>0){
					// if( $re = $consultacliente->fetch_array() ){
						// if( $re['cuenta'] <= 0 ){//sino tienen cuenta
							// $previo['cliente']['listacliente'] =0;
// 							 
						// }
					// }
				// }else{
					// $previo['cliente']['listacliente'] =0;
				// }
			$_SESSION['provisioncliente'][]=$previo;
		 }else if($comprobante==2 || $comprobante==4){
		 		$previo['proveedor']['tipo_cambio']= $tipocambio;
		 		$previo['proveedor']['agregaprovee'] = $agregaprove;
				$previo['proveedor']['ish']=number_format(floatval($ishimport),2,'.','');
				$previo['proveedor']['ishmxn']=number_format(floatval($previo['proveedor']['ish'] * $tipocambio),2,'.','');;
				$previo["proveedor"]['retenidos']=$retencion;
				$previo["proveedor"]['retenidosmxn']=$retencionmxn;
				$previo["proveedor"]['nombre']=utf8_encode($nombreemisor);
				$previo["proveedor"]['concepto']=$concepto."";
				$previo["proveedor"]['cargo']=number_format(floatval($subTotal),2,'.','');
				$previo["proveedor"]['cargomxn']=number_format(floatval($previo["proveedor"]['cargo'] *$tipocambio),2,'.','');;
				$previo["proveedor"]['abono']=number_format(floatval($total),2,'.','');
				$previo["proveedor"]['abonomxn']=number_format(floatval($previo["proveedor"]['abono'] * $tipocambio),2,'.','');
				$previo["proveedor"]['xml']=($xmld);
				$previo["proveedor"]['xmlreal']=($xmlreal);
				$previo["proveedor"]['referencia']=$UUID."";
				$previo['proveedor']['conceptodetalle']= $conceptosdetalle;
				$previo['proveedor']['preciodetalle']= $precioimporte;
				$previo['proveedor']['preciodetallemxn']= $precioimportemxn;
				$previo['proveedor']['listaprove'] = 0;
				// $emisor=$this->CaptPolizasModel->emisorprove($rfcemisor,$nombreemisor);
				// if($emisor->num_rows>0){
					// if( $re = $emisor->fetch_array() ){
					  // if( $re['cuenta'] == " " || $re['cuenta'] == 0){//sino tienen cuenta
					  	// $previo['proveedor']['listaprove'] = 0;
					  // }
					// }
				// }else{
					// $previo['proveedor']['listaprove'] = 0;
				// }
			 $_SESSION['poliprove'][]=$previo;
			}//comprobante 2
			//rename($_POST['xml'][$i],'xmls/facturas/temporales/'.($xmld));
	}
}
	function guardaprovimultiple(){
		$error=false;
		$fecha = $_REQUEST['fecha'];
		$carpeta=false;
		$cuentas = $this->CaptPolizasModel->cuentasconf();
		if($row=$cuentas->fetch_array()){
			$iepspendientecobro 	= $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSPendienteCobro']);
			$iepspendientepago  	= $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSPendientePago']);
			$ivapendientecobro  	= $this->CaptPolizasModel->buscacuenta($row['CuentaIVAPendienteCobro']);
			$ivapendientepago   	= $this->CaptPolizasModel->buscacuenta($row['CuentaIVAPendientePago']);
			$ish				    = $this->CaptPolizasModel->buscacuenta($row['ISH']);
			$ivaretenido 		= $this->CaptPolizasModel->buscacuenta($row['IVAretenido']);
			$isrretenido			= $this->CaptPolizasModel->buscacuenta($row['ISRretenido']);
			$statusIVAIEPS      	= $row['statusIVAIEPS'];
			$statusRetencionISH 	= $row['statusRetencionISH'];
			$statusIEPS		    = $row['statusIEPS'];
			$statusIVA		    = $row['statusIVA'];
			$CuentaIEPSgasto		= $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSgasto']);
			$CuentaIVAgasto		= $this->CaptPolizasModel->buscacuenta($row['CuentaIVAgasto']);
			
		
		}
			$iepspendientecobro 	= explode("//", $iepspendientecobro);
			$iepspendientepago  	= explode("//", $iepspendientepago);
			$ivapendientecobro  	= explode("//", $ivapendientecobro);
			$ivapendientepago   	= explode("//", $ivapendientepago);
			$ish					= explode("//", $ish);
			$ivaretenido 		= explode("//", $ivaretenido);
			$isrretenido			= explode("//", $isrretenido);
			$CuentaIEPSgasto			= explode("//", $CuentaIEPSgasto);
			$CuentaIVAgasto		= explode("//",$CuentaIVAgasto);
			$Exercise = $this->CaptPolizasModel->getExerciseInfo();
			if( $Ex = $Exercise->fetch_assoc() ){
				$idorg 	= $Ex['IdOrganizacion'];
				$idejer	= $Ex['IdEx'];
				$idperio	= $Ex['PeriodoActual'];
			}
			if( isset($_COOKIE['ejercicio']) ){
				$idejer = $this->CaptPolizasModel->idex($_COOKIE['ejercicio']);
			}if( isset($_COOKIE['periodo']) ){
				$idperio = $_COOKIE['periodo'];
			}
			if($_REQUEST['conceptopoliza']){
				$conceptopoliza=$_REQUEST['conceptopoliza'];
			}	
			
// 	//////////////////////////////////////////////////////		
		if( $_SESSION['comprobante'] == 1 || $_SESSION['comprobante'] == 3 ){// si es ingresos el cliente sera el receptor
			$idreceptor = 0;
			if(!$conceptopoliza){
				$conceptopoliza='Provision Ingresos';
				if($_SESSION['comprobante'] == 3){
					$conceptopoliza = 'Provision Nota de Credito Ingresos';
				}
			}
			////////////////////////////
			$poli = $this->CaptPolizasModel->savePoliza2($idorg,$idejer,$idperio,3,$conceptopoliza,$fecha,0,"","",0,"",0,0);
			if($poli == 0){
				$numPoliza = $this->CaptPolizasModel->getLastNumPoliza();
				if( mkdir($this->path()."xmls/facturas/".$numPoliza['id'],  0777)){
					$carpeta = true;
				}
			}
			$numov = 1;
			foreach( $_SESSION['provisioncliente'] as $cli){
				foreach($cli as $cliente){
					$idcuentacliente = $cliente['CuentaClientes'];
					
					$receptor = $this->CaptPolizasModel->consultacliente($cliente['agregacliente'][1]);
					if($receptor->num_rows>0){
						if($re=$receptor->fetch_array()){
						  $idreceptor=$re['id'];
							  // if( $re['cuenta'] <=0){
							  	// //$idcuentacliente = $idcuentacliente;
								// $actualizacliente = $this->CaptPolizasModel->actualizacliente($idcuentacliente, $re['id']);
							  // }else{
							  	// $idcuentacliente = $re['cuenta'];
							  // }
						}
					}else{
						$consul = $this->CaptPolizasModel->consultamuniesta($cliente['agregacliente'][5]);
						if($consul == 0){
							$idestado = 1;
							$idmunicipio = 1;
						}else{
							$separa = explode('/', $consul);
							$idestado = $separa[0];
							$idmunicipio = $separa[1];
						}
						$registroreceptor = $this->CaptPolizasModel->agregareceptorcliente($cliente['agregacliente'][0], $cliente['agregacliente'][2], $cliente['agregacliente'][3], $cliente['agregacliente'][4], $idestado, $idmunicipio, $cliente['agregacliente'][1],0);
						if($registroreceptor == 0){
							$idreceptor = $this->CaptPolizasModel->ultimo(1);
						}
					}
			
					if( $statusIVAIEPS == 0 ){
						$ivapendientecobro[0]  = $cliente['cuentaivapendiente'];
						$iepspendientecobro[0] = $cliente['cuentaiepspendiente'];
					}
					if( $statusRetencionISH == 0 ){
						$ish	[0]			= $cliente['cuentaish'];
						$ivaretenido[0]	= $cliente['cuentaiva'];
						$isrretenido[0]	= $cliente['cuentaisr'];
					}
					
					$referencia 	= $cliente['referencia'];
					$segmento 	= $cliente['segmento'][0];
					$sucursal   	= $cliente['sucursal'][0];
					
					$abonocomprobantemxn = "Abono";
					$cargocomprobantemxn = "Cargo";
					$abonocomprobante = "Abono M.E";
					$cargocomprobante = "Cargo M.E.";
					if($_SESSION['comprobante'] == 3){//Si es nota de credito ingre los movimientos son contrarios
						$abonocomprobantemxn = "Cargo";
						$cargocomprobantemxn = "Abono";
						
						$abonocomprobante = "Cargo M.E.";
						$cargocomprobante = "Abono M.E";
						
					}$suma = 0; $suma2 =0;
					$suma += $cliente['iepsmxn'] + $cliente['ishmxn'];
					$suma += number_format($cliente['abono2mxn'],2,'.','');//iva
					$suma2 = $cliente['cargomxn'] + $cliente['retenidosmxn']['ISR'] + $cliente['retenidosmxn']['IVA'];
					
						
					if($_REQUEST['detalle']){
						if(!$_REQUEST['conceptopoliza']){
							$cliente['concepto']=$conceptopoliza;
						}
						$maximo = count($cliente['conceptodetalle']);
						$maximo = (intval($maximo)-1);
							
						for($c=0;$c<=($maximo);$c++){
							$montomxn = $cliente['preciodetalle'][$c] * $cliente['tipo_cambio'];
							// $abono = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$cliente['cuentadetalle'][$c],"Abono M.E",number_format($cliente['preciodetalle'][$c],2,'.',''),$cliente['conceptodetalle'][$c],'1-',$cliente['xml'],"$referencia",0);
							$suma += number_format($montomxn,2,'.','');
							if($c == $maximo){
								$totaldecima = $suma- $suma2;
								if($suma>$suma2){
									$montomxn = $montomxn - abs(number_format($totaldecima,2));
								}if($suma<$suma2){
									$montomxn = $montomxn + abs(number_format($totaldecima,2));
								}
							}
							
							$abono = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$cliente['cuentadetalle'][$c],"Abono",number_format($montomxn,2,'.',''),$cliente['conceptodetalle'][$c],'1-',$cliente['xml'],"$referencia",0);
							
							$numov++;
						}
					}else{
						if($cliente['hayProrrateo']==0){
							$suma+=number_format($cliente['abonomxn'],2,'.','');
							$totaldecima = $suma- $suma2;
							if($suma>$suma2){
								$cliente['abonomxn'] = $cliente['abonomxn'] - abs(number_format($totaldecima,2));
							}if($suma<$suma2){
								$cliente['abonomxn'] = $cliente['abonomxn'] + abs(number_format($totaldecima,2));
							}
							// $abono = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$cliente['cuentacompraventa'][0],$abonocomprobante,$cliente['abono'],$cliente['concepto'][0],'1-',$cliente['xml'],"$referencia",0);
							$abono = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$cliente['cuentacompraventa'][0],$abonocomprobantemxn,number_format($cliente['abonomxn'],2,'.',''),$cliente['concepto'][0],'1-',$cliente['xml'],"$referencia",0);
							$numov++;
						}else{
							$p=0;
							$maximo = count($cliente['prorrateo']);$maximo = (intval($maximo)-1);
							
							for($p;$p<=($maximo);$p++){
								$porcen = $cliente['prorrateo'][$p] / 100;
								$monto = $cliente['abono'] * $porcen;
								$montomxn = $monto * $cliente['tipo_cambio'];
								$suma += number_format($montomxn,2,'.','');
								if($p == $maximo){
									$totaldecima = $suma- $suma2;
									if($suma>$suma2){
										$montomxn = $montomxn - abs(number_format($totaldecima,2));
									}if($suma<$suma2){
										$montomxn = $montomxn + abs(number_format($totaldecima,2));
									}
									
								}
								$abono = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$cliente['segmento'][$p],$cliente['sucursal'][$p],$cliente['cuentacompraventa'][$p],$abonocomprobantemxn,number_format($montomxn,2,'.',''),$cliente['concepto'][$p],'1-',$cliente['xml'],"$referencia",0);
								// $abono = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$cliente['segmento'][$p],$cliente['sucursal'][$p],$cliente['cuentacompraventa'][$p],$abonocomprobantemxn,number_format($monto * $cliente['tipo_cambio'],2,'.',''),$cliente['concepto'][$p],'1-',$cliente['xml'],"$referencia",0);
								
								$numov++;
							}
							$cliente['concepto'][0]="-";
						}
					}
					if( $abono == true ){
						
						if($statusIEPS==0){
							$iepspendientecobro[0]=$CuentaIEPSgasto[0];
						}
						if($statusIVA==0){
							$ivapendientecobro[0]=$CuentaIVAgasto[0];
						}
						if($cliente['hayProrrateo']==0){
							
						
							
							if( $cliente['iepsmxn'] > 0 ){
								$insertaiepsmxn = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$iepspendientecobro[0],$abonocomprobantemxn,number_format($cliente['iepsmxn'],2,'.',''),$cliente['concepto'][0],'1-',$cliente['xml'],"$referencia",0);
								// $insertaieps = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$iepspendientecobro[0],$abonocomprobante,number_format($cliente['ieps'],2,'.',''),$cliente['concepto'][0],'1-',$cliente['xml'],"$referencia",0);
								$numov++;
							}
							if( $cliente['ishmxn'] > 0){
								$insertishmxn = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$ish[0],$abonocomprobantemxn,number_format($cliente['ishmxn'],2,'.',''),$cliente['concepto'][0],'1-',$cliente['xml'],"$referencia",0);
								// $insertish = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$ish[0],$abonocomprobante,$cliente['ish'],$cliente['concepto'][0],'1-',$cliente['xml'],"$referencia",0);
								$numov++;
							}
						}else{
							if( $cliente['iepsmxn'] > 0 ){
								$insertaiepsmxn = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$cliente['segmento'][$p],$cliente['sucursal'][$p],$iepspendientecobro[0],$abonocomprobantemxn,number_format($cliente['iepsmxn'],2,'.',''),$cliente['concepto'][0],'1-',$cliente['xml'],"$referencia",0);
								// $insertaieps = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$cliente['segmento'][$p],$cliente['sucursal'][$p],$iepspendientecobro[0],$abonocomprobante,number_format($cliente['ieps'],2,'.',''),$cliente['concepto'][0],'1-',$cliente['xml'],"$referencia",0);
								$numov++; $p++;
							}
							if( $cliente['ishmxn'] > 0){
								$insertishmxn = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$cliente['segmento'][$p],$cliente['sucursal'][$p],$ish[0],$abonocomprobantemxn,number_format($cliente['ishmxn'],2,'.',''),$cliente['concepto'][0],'1-',$cliente['xml'],"$referencia",0);
								// $insertish = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$cliente['segmento'][$p],$cliente['sucursal'][$p],$ish[0],$abonocomprobante,$cliente['ish'],$cliente['concepto'][0],'1-',$cliente['xml'],"$referencia",0);
								$numov++; $p++;
							}
						}
						//nombre
						
						if($cliente['hayProrrateo']==0){
							if( $cliente['abono2mxn'] > 0){//$ivaingre=$_REQUEST['ivaingre'];
								$abono2mxn = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$ivapendientecobro[0],$abonocomprobantemxn,number_format($cliente['abono2mxn'],2,'.',''),$cliente['concepto'][0],'1-',$cliente['xml'],"$referencia",0);
								// $abono2 = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$ivapendientecobro[0],$abonocomprobante,number_format($cliente['abono2'],2,'.',''),$cliente['concepto'][0],'1-',$cliente['xml'],"$referencia",0);
								$numov++;
							}
						}else{
							if( $cliente['abono2mxn'] > 0){//$ivaingre=$_REQUEST['ivaingre'];
								$abono2mxn = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$cliente['segmento'][$p],$cliente['sucursal'][$p],$ivapendientecobro[0],$abonocomprobantemxn,number_format($cliente['abono2mxn'],2,'.',''),'-','1-',$cliente['xml'],"$referencia",0);
								// $abono2 = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$cliente['segmento'][$p],$cliente['sucursal'][$p],$ivapendientecobro[0],$abonocomprobante,number_format($cliente['abono2'],2,'.',''),'-','1-',$cliente['xml'],"$referencia",0);
								$numov++; $p++;
							}
						}
						
						if($cliente['hayProrrateo']==0){
							
							$si = $this->automaticasMonedaExtModel->InsertMovExt($numPoliza['id'],$numov,$segmento,$sucursal,$idcuentacliente,$cargocomprobante,number_format($cliente['cargo'],2,'.',''),$cliente['concepto'][0],'1-'.$idreceptor,$cliente['xml'],"$referencia",0,$cliente['tipo_cambio']);	
							$simxn = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$idcuentacliente,$cargocomprobantemxn,number_format($cliente['cargomxn'],2,'.',''),$cliente['concepto'][0],'1-'.$idreceptor,$cliente['xml'],"$referencia",0);	
							$numov++;
						}else{
							$si = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$cliente['segmento'][$p],$cliente['sucursal'][$p],$idcuentacliente,$cargocomprobante,number_format($cliente['cargo'],2,'.',''),'-','1-'.$idreceptor,$cliente['xml'],"$referencia",0,$cliente['tipo_cambio']);	
							$simxn = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$cliente['segmento'][$p],$cliente['sucursal'][$p],$idcuentacliente,$cargocomprobantemxn,number_format($cliente['cargomxn'],2,'.',''),'-','1-'.$idreceptor,$cliente['xml'],"$referencia",0);	
							$numov++; $p++;
						}
						if( $si != false ){
				 	  	//////retencion/////
				 	 
							foreach ( $cliente['retenidosmxn'] as $key => $value){
								if($cliente['hayProrrateo']==1){
									$segmento = $cliente['segmento'][$p];
									$sucursal = $cliente['sucursal'][$p];
									$p++;
								} 
						 	  	if( $key == "ISR"){
						 	  		if( $value > 0 ){
						 	  			$si = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$isrretenido[0],$cargocomprobantemxn,number_format($value,2,'.',''),$cliente['concepto'][0],'1-',$cliente['xml'],"$referencia",0);	
						 	  			$numov++;
									}
								}
								if( $key == "IVA" ){
						 	  		if( $value > 0 ){
						 	  		 	$si = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$ivaretenido[0],$cargocomprobantemxn,number_format($value,2,'.',''),$cliente['concepto'][0],'1-',$cliente['xml'],"$referencia",0);	
										$numov++;
									}
								}
						}	
						
				 	  	/////////////
						}else{
		 					$error = true;
		 	   			}
				
				 }else{
				 	$error = true;
				}
					 
					 if($error==false){
						if($carpeta==true){
							if($_SESSION['comprobante'] == 3){
								rename($this->path()."xmls/facturas/temporales/".$cliente['xmlreal'], $this->path()."xmls/facturas/".$numPoliza['id']."/".$cliente['xml']);
							}else{
								copy($this->path()."xmls/facturas/temporales/".$cliente['xmlreal'], $this->path()."xmls/facturas/".$numPoliza['id']."/".$cliente['xml']);
								rename($this->path()."xmls/facturas/temporales/".$cliente['xmlreal'], $this->path()."xmls/facturas/temporales/".$cliente['xml']);
							}
							$this->CaptPolizasModel->facturaRename($cliente['xml']);

						}
					}
			}//segun foreach
		}//primer foreach
		if($error==false){
			unset($_SESSION['comprobante']);
			unset($_SESSION['provisioncliente']);
			unset($_SESSION['fechaprovi']);
			unset($_SESSION['datosext']);
			
			echo 0;
		}else{
			echo 1;
		}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
		}else if($_SESSION['comprobante']==2 || $_SESSION['comprobante'] == 4){//si es egresos el proveedor sera el emisor
			if(!$conceptopoliza){
				 $conceptopoliza='Provision Egresos';
				if($_SESSION['comprobante'] == 4){
					$conceptopoliza = 'Provision Nota de Credito Egresos';
				}
			}
			$poli=$this->CaptPolizasModel->savePoliza2($idorg,$idejer,$idperio,3,$conceptopoliza,$fecha,0,"","",0,"",0,0);
				
				if($poli==0){
					$numPoliza = $this->CaptPolizasModel->getLastNumPoliza();
					if(mkdir($this->path()."xmls/facturas/".$numPoliza['id'],  0777)){
						$carpeta=true;
					}
				}
				
			$numov=1;
			foreach( $_SESSION['poliprove'] as $cli){
				foreach($cli as $prove){
					$idcuentaprove = $prove['CuentaProveedores']; 
					
					$emisor=$this->CaptPolizasModel->emisorprove($prove['agregaprovee'][1],$prove['agregaprovee'][0]);
					if($emisor->num_rows>0){
						if( $re = $emisor->fetch_array() ){
						  $idemisor = $re['idPrv'];
						  // if( $re['cuenta'] == " " || $re['cuenta'] == 0){
						  	// $idcuentaprove = $idcuentaproveedores;
							// $actualizzaprove=$this->CaptPolizasModel->actulizaprove($idcuentaproveedores, $re['idPrv']);
						  // }else{
						  	// $idcuentaprove = $re['cuenta'];
						  // }
						}
					}else{
						$consul=$this->CaptPolizasModel->consultamuniesta($prove['agregaprovee'][3]);
						$separa=explode('/', $consul);
						$idestado=$separa[0];
						$idmunicipio=$separa[1];//el proveedor es agregado con al cuenta seleccionada
						$registroemisor=$this->CaptPolizasModel->agregaremisorprove($prove['agregaprovee'][0],$prove['agregaprovee'][1], $prove['agregaprovee'][2],$idestado,$idmunicipio,0);
						if($registroemisor==0){
							$idemisor=$this->CaptPolizasModel->ultimo(2);
							
						}
					}

					
					if( $statusIVAIEPS == 0 ){
						$ivapendientepago[0]  = $prove['cuentaivapendiente'];
						$iepspendientepago[0] = $prove['cuentaiepspendiente'];
					}
					if( $statusRetencionISH == 0 ){
						$ish	[0]			= $prove['cuentaish'];
						$ivaretenido[0]	= $prove['cuentaiva'];
						$isrretenido[0]	= $prove['cuentaisr'];
					}
					
					$referencia 	= $prove['referencia'];
					$segmento 	= $prove['segmento'][0];
					$sucursal   	= $prove['sucursal'][0];
					$abonocomprobantemxn = "Abono";
					$cargocomprobantemxn = "Cargo";
					$abonocomprobante = "Abono M.E";
					$cargocomprobante = "Cargo M.E.";
					if($_SESSION['comprobante'] == 4){//Si es nota de credito egre los movimientos son contrarios
						$abonocomprobantemxn = "Cargo";
						$cargocomprobantemxn = "Abono";
						$abonocomprobante = "Cargo M.E.";
						$cargocomprobante = "Abono M.E";
					}
					$suma = 0; $suma2 =0;
					$suma += $prove['iepsmxn'] + $prove['ishmxn'];
					$suma2 = $prove['abonomxn'] + $prove['retenidosmxn']['ISR'] + $prove['retenidosmxn']['IVA'];
					$suma += number_format($prove['cargo2mxn'],2,'.','');
					
					if($_REQUEST['detalle']){
						if(!$_REQUEST['conceptopoliza']){
							$prove['concepto']=$conceptopoliza;
						}
						$maximo = count($prove['conceptodetalle']);
						$maximo = (intval($maximo)-1);
						for($c=0;$c<=($maximo);$c++){
							$montomxndetalle = number_format($prove['preciodetalle'][$c] * $prove['tipo_cambio'],2,'.','');
							$suma += number_format($montomxndetalle,2,'.','');
							if($c == $maximo){
								$totaldecima = $suma - $suma2;
								if($suma>$suma2){
									$montomxndetalle = $montomxndetalle - abs(number_format($totaldecima,2));
								}if($suma<$suma2){
									$montomxndetalle = $montomxndetalle + abs(number_format($totaldecima,2));
								}
							}
							$cargo=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$prove['cuentadetalle'][$c],"Cargo",$montomxndetalle,$prove['conceptodetalle'][$c],'2-',$prove['xml'],"$referencia",9);
							// $cargo=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$prove['cuentadetalle'][$c],"Cargo M.E.",number_format($prove['preciodetalle'][$c],2,'.',''),$prove['conceptodetalle'][$c],'2-',$prove['xml'],"$referencia",9);
							
							$numov++;
						}
					}else{
						if($prove['hayProrrateo']==0){
							$suma+=number_format($prove['cargomxn'],2,'.','');
							$totaldecima = $suma- $suma2;
							if($suma>$suma2){
								$prove['cargomxn'] = $prove['cargomxn'] - abs(number_format($totaldecima,2));
							}if($suma<$suma2){
								$prove['cargomxn'] = $prove['cargomxn'] + abs(number_format($totaldecima,2));
							}
							$cargo=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$prove['cuentacompraventa'][0],$cargocomprobantemxn,number_format($prove['cargomxn'],2,'.',''),$prove['concepto'][0],'2-',$prove['xml'],"$referencia",9);
							// $cargo=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$prove['cuentacompraventa'][0],$cargocomprobante,$prove['cargo'],$prove['concepto'][0],'2-',$prove['xml'],"$referencia",9);
							
							$numov++;
						}else{
							$p=0;
							$maximo = count($prove['prorrateo']);$maximo = (intval($maximo)-1);
							for($p;$p<=($maximo);$p++){
								$porcen = $prove['prorrateo'][$p] / 100;
								$monto = $prove['cargo'] * $porcen;
								$montomxn = $monto * $prove['tipo_cambio'];
								$suma += number_format($montomxn,2,'.','');
								if($p == $maximo){
									$totaldecima = $suma- $suma2;
									if($suma>$suma2){
										$montomxn = $montomxn - abs(number_format($totaldecima,2));
									}if($suma<$suma2){
										$montomxn = $montomxn + abs(number_format($totaldecima,2));
									}
									
								}
								$cargo=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$prove['segmento'][$p],$prove['sucursal'][$p],$prove['cuentacompraventa'][$p],$cargocomprobantemxn,number_format($montomxn,2,'.',''),$prove['concepto'][$p],'2-',$prove['xml'],"$referencia",9);
								// $cargo=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$prove['segmento'][$p],$prove['sucursal'][$p],$prove['cuentacompraventa'][$p],$cargocomprobante,number_format($monto,2,'.',''),$prove['concepto'][$p],'2-',$prove['xml'],"$referencia",9);
								$numov++;
							}
							$prove['concepto'][0]="-";
						}
					}
					if($cargo==true){
						
						if($statusIEPS==0){
							$iepspendientepago[0]=$CuentaIEPSgasto[0];
						}
						if($statusIVA==0){
							$ivapendientepago[0]=$CuentaIVAgasto[0];
						}
						if($prove['hayProrrateo']==0){
							if($prove['iepsmxn']>0){
								$insertiepss=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$iepspendientepago[0],$cargocomprobantemxn,number_format($prove['iepsmxn'],2,'.',''),$prove['concepto'][0],'2-',$prove['xml'],"$referencia",9);
								$numov++;
							}
							if($prove['ishmxn']>0){
								$ishinsert=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$ish[0],$cargocomprobantemxn,number_format($prove['ishmxn'],2,'.',''),$prove['concepto'][0],'2-',$prove['xml'],"$referencia",0);
								$numov++;
							}
							if($prove['cargo2mxn']>0){//impuesto
								$cargo2=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$ivapendientepago[0],$cargocomprobantemxn,number_format($prove['cargo2mxn'],2,'.',''),$prove['concepto'][0],'2-',$prove['xml'],"$referencia",9);
								$numov++;
							}
						}else{
							if($prove['iepsmxn']>0){
								$insertiepss=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$prove['segmento'][$p],$prove['sucursal'][$p],$iepspendientepago[0],$cargocomprobantemxn,number_format($prove['iepsmxn'],2,'.',''),$prove['concepto'][0],'2-',$prove['xml'],"$referencia",9);
								$numov++; $p++;
							}
							if($prove['ishmxn']>0){
								$ishinsert=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$prove['segmento'][$p],$prove['sucursal'][$p],$ish[0],$cargocomprobantemxn,number_format($prove['ishmxn'],2,'.',''),$prove['concepto'][0],'2-',$prove['xml'],"$referencia",0);
								$numov++; $p++;
							}
							if($prove['cargo2mxn']>0){
								$cargo2=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$prove['segmento'][$p],$prove['sucursal'][$p],$ivapendientepago[0],$cargocomprobantemxn,number_format($prove['cargo2mxn'],2,'.',''),$prove['concepto'][0],'2-',$prove['xml'],"$referencia",9);
								$numov++; $p++;
							}
						}
						if($prove['hayProrrateo']==0){
							$si=$this->automaticasMonedaExtModel->InsertMovExt($numPoliza['id'],$numov,$segmento,$sucursal,$idcuentaprove,$abonocomprobante,number_format($prove['abono'],2,'.',''),$prove['concepto'][0],'2-'.$idemisor,$prove['xml'],"$referencia",9,$prove['tipo_cambio']);	
							$simxn=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$idcuentaprove,$abonocomprobantemxn,number_format($prove['abonomxn'],2,'.',''),$prove['concepto'][0],'2-'.$idemisor,$prove['xml'],"$referencia",9);	
						}else{
							$si=$this->automaticasMonedaExtModel->InsertMovExt($numPoliza['id'],$numov,$prove['segmento'][$p],$prove['sucursal'][$p],$idcuentaprove,$abonocomprobante,number_format($prove['abono'],2,'.',''),$prove['concepto'][0],'2-'.$idemisor,$prove['xml'],"$referencia",9,$prove['tipo_cambio']);	
							$smxni=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$prove['segmento'][$p],$prove['sucursal'][$p],$idcuentaprove,$abonocomprobantemxn,number_format($prove['abonomxn'],2,'.',''),$prove['concepto'][0],'2-'.$idemisor,$prove['xml'],"$referencia",9);	
							$p++;
						}
				 	 	if($si!=false){
				 	  		$numov++;
				 	  		foreach ( $prove['retenidosmxn'] as $key => $value){
				 	  			if($prove['hayProrrateo']==1){
									$segmento = $prove['segmento'][$p];
									$sucursal = $prove['sucursal'][$p];
									$p++;
								}  
					 	  		if($key=="ISR"){
					 	  			if($value > 0){
					 	  				$si=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$isrretenido[0],$abonocomprobantemxn,number_format($value,2,'.',''),$prove['concepto'][0],'2-',$prove['xml'],"$referencia",9);	
					 	  				$numov++;
									}
								}
								if($key == "IVA"){
									if($value > 0){
					 	  				$si=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$ivaretenido[0],$abonocomprobantemxn,number_format($value,2,'.',''),$prove['concepto'][0],'2-',$prove['xml'],"$referencia",9);	
					 	  				$numov++;
									}
								}
							}
				 	 	}else{$error=true;};
						 	   
						
					}else{
				 		$error=true;
				 	}
					if($error==false){
						if($carpeta==true){
							if($_SESSION['comprobante'] == 4){
								rename($this->path()."xmls/facturas/temporales/".$prove['xmlreal'], $this->path()."xmls/facturas/".$numPoliza['id']."/".$prove['xml']);
							}else{
								copy($this->path()."xmls/facturas/temporales/".$prove['xmlreal'], $this->path()."xmls/facturas/".$numPoliza['id']."/".$prove['xml']);
								rename($this->path()."xmls/facturas/temporales/".$prove['xmlreal'], $this->path()."xmls/facturas/temporales/".$prove['xml']);
							}
							$this->CaptPolizasModel->facturaRename($prove['xml']);
						}
					}
		
				}//foreach interno
			}//foreach principal
			if($error==false){
				unset($_SESSION['comprobante']);
				unset($_SESSION['poliprove']);
				unset($_SESSION['fechaprovi']);
				unset($_SESSION['datosext']);
				echo 0;
			}else{
				echo 1;
			}
		}//else del ==2	
			
		
	
	}
	function actualizaprovprovecuentas(){
		$listaprove=$this->CaptPolizasModel->cuentasprove($_SESSION['datosext']['moneda']);
		
		while ($li=$listaprove->fetch_array()){
			echo	"<option value=$li[account_id]>$li[description](".$li['manual_code'].")</option>";
		}
	}
	function actualizaprovclicuentas(){
		$listacli=$this->CaptPolizasModel->clientesCuentas($_SESSION['datosext']['moneda']);
		
		while ($li=$listacli->fetch_array()){
			echo	"<option value=$li[account_id]>$li[description](".$li['manual_code'].")</option>";
		}
	}

	//ver q pasa con las decimas y hacer pruebas en provisiones
	
	function verpagoext()
	{
		$periodo=$this->CaptPolizasModel->getExerciseInfo();
		$forma_pago=$this->CaptPolizasModel->formapago();
		$cuentas = $this->CaptPolizasModel->cuentasconf();
		if($row=$cuentas->fetch_array()){
			$iepspendientepago  = $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSPendientePago']);
			$ivapendientepago   = $this->CaptPolizasModel->buscacuenta($row['CuentaIVAPendientePago']);
			$CuentaIVApagado = $this->CaptPolizasModel->buscacuenta($row['CuentaIVApagado']);
			$CuentaIEPSpagado  = $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSpagado']);
			$statusIVAIEPS      = $row['statusIVAIEPS'];
			$statusRetencionISH = $row['statusRetencionISH'];
			$statusIEPS		    = $row['statusIEPS'];
			$statusIVA		    = $row['statusIVA'];
			$CuentaIEPSgasto		= $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSgasto']);
			$CuentaIVAgasto		= $this->CaptPolizasModel->buscacuenta($row['CuentaIVAgasto']);
				
		}
		$ivapendientepago = explode("//", $ivapendientepago);
		$CuentaIVApagado = explode("//", $CuentaIVApagado);
		$iepspendientepago = explode("//", $iepspendientepago);
		$CuentaIEPSpagado = explode("//", $CuentaIEPSpagado);
		$CuentaIEPSgasto = explode("//", $CuentaIEPSgasto);
		$CuentaIVAgasto  = explode("//",$CuentaIVAgasto);
		
		$cuentas=$this->CaptPolizasModel->cuentaivas();
		$listadoivaieps="";
		while($campo=$cuentas->fetch_array()){
			$listadoivaieps .= "<option value=".$campo['account_id'].">".$campo['description']."(".$campo['manual_code'].")</option>";
		}
		if($p=$periodo->fetch_array()){
			$ejercicio = $p['NombreEjercicio'];
			$idperiodo=$p['PeriodoActual'];
			if($p['PeriodoActual']!=13){
				$moneda = $this->automaticasMonedaExtModel->tipomoneda();
				$sql=$this->automaticasMonedaExtModel->bancosexttodas();
				$sqlprov=$this->automaticasMonedaExtModel->proveedor();//proveedores del padron asociados a cuenta contable
				$sqlprov2=$this->automaticasMonedaExtModel->proveedorcuentasext();//proveedores del arbol no asociados auna cuenta
				$ListaSegmentos = $this->CaptPolizasModel->ListaSegmentos();
				$ListaSucursales = $this->CaptPolizasModel->ListaSucursales();
				$beneficiario=$this->CaptPolizasModel->proveedor();//proveedores del padron asociados a cuenta contable
				$listabancos = $this->CaptPolizasModel->listabancos();//IBM
				$Exercise = $this->CaptPolizasModel->getExerciseInfo();
				$Ex = $Exercise->fetch_assoc();
				$firstExercise = $this->CaptPolizasModel->getFirstLastExercise(0);
				$lastExercise = $this->CaptPolizasModel->getFirstLastExercise(1);
				$Suc = $this->CaptPolizasModel->getSegmentoInfo();
				$listacuentasbancarias = $this->CaptPolizasModel->cuentasbancariaslista();
				//bet
				if($sql->num_rows>0){
					$bancos=$sql;
				}else{
					$bancosno="Por favor elija una cuenta de bancos en el menu de configuracion o agrege hijos a la cuenta";
				}
				if($sqlprov->num_rows>0 || $sqlprov2->num_rows>0){
					$proveedores2=$sqlprov2;
					$proveedores=$sqlprov;
				}else{
					$proveedoresno="No hay proveedores registrados o asociados";
				}
				
				if(intval($Ex['IdEx']) AND intval($Suc))// Si existe el ejercicio
				{
				 	$Abonos = $this->CaptPolizasModel->GetAbonosCargos('Abono','Periodo',$Ex['PeriodoActual'],$Ex['IdEx']);
					$Cargos = $this->CaptPolizasModel->GetAbonosCargos('Cargo','Periodo',$Ex['PeriodoActual'],$Ex['IdEx']);
					$lista = $this->automaticasMonedaExtModel->listaTipoCambio(0,$Ex['NombreEjercicio']."-".$Ex['PeriodoActual']);
					
					include('views/captpolizas/monedaext/pagoext.php');
				}
				else
				{
					echo "<script>alert('Antes de capturar polizas configure un ejercicio y tambien Segmentos de Negocio.');window.parent.agregatab('../../modulos/cont/index.php?c=Config','Configuración Ejercicios','',142);window.parent.agregatab('../catalog/gestor.php?idestructura=251&ticket=testing','Segmentos de Negocio','',1656)</script>";
				}
				
				
				
			}else{
				echo '<script> alert("No puedes generar polizas automaticas en el perido 13");</script>';
			}
		}
	}
	function arraytabla(){
		$moneda = $_REQUEST['moneda'];
		if($_REQUEST['tipocambio']==0){
			$_REQUEST['tipocambio']=$_REQUEST['tipocambio2'];
		}
		$tipocambio = $_REQUEST['tipocambio'];
		$_SESSION['datospagoext']['tipocambio']=$tipocambio;
		if($_REQUEST['radio']==2){
		}else{
			$maximo = count($_POST['xml']);
				$maximo = (intval($maximo)-1);
			for($i=0;$i<=$maximo;$i++){
				$xml="";$uuid=""; $total=0.00;
				$xml=$_POST['xml'][$i];
				$xmld = simplexml_load_file($this->path().'xmls/facturas/temporales/'.$_POST['xml'][$i]);
				$ns = $xmld -> getNamespaces(true);
				$xmld -> registerXPathNamespace('c', $ns['cfdi']);//altera los prefijos del namespace
				$xmld -> registerXPathNamespace('t', $ns['tfd']);
				foreach ($xmld->xpath('//t:TimbreFiscalDigital') as $tfd) {
  					$uuid= $tfd['UUID']; 
				} 
				foreach ($xmld->xpath('//cfdi:Comprobante') as $cfdiComprobante){
					if(!$cfdiComprobante['fecha']){
				 		$fecha= $cfdiComprobante['Fecha'];
					}
				  	if(!$cfdiComprobante['folio']){
				  		$folio=$cfdiComprobante['Folio'];
					}
					if(!$cfdiComprobante['total']){
						$cfdiComprobante['total'] = $cfdiComprobante['Total'];
					}
				  $fecha= $cfdiComprobante['fecha']; 
				  $folio=$cfdiComprobante['folio'];
				  $total+=number_format(floatval($cfdiComprobante['total']),2,'.',''); 
				}
				 foreach ($xmld->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){
				 	if(!$Emisor['nombre']){
				 		$Emisor['nombre'] = $Emisor['Nombre'];
				 	} 
   					 $emisor=$Emisor['nombre']; 
    				} 
				 $ieps=0; $iva=0;
				$tabla[$_REQUEST['proveedor']]['IVA']=0;
				$tabla[$_REQUEST['proveedor']]['IEPS']=0;
				foreach ($xmld->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Trasla){
					if(!$Trasla['impuesto']){
						$Trasla['impuesto'] = $this->CaptPolizasModel->nombreImpuestoIndividual($Trasla['Impuesto']);
						$Trasla['importe'] = $Trasla['Importe'];	
					} 
					if($Trasla['impuesto']=="IVA"){
				  		if($Trasla['importe']>0){
				  			$iva+=number_format(floatval($Trasla['importe']),2,'.','');
						 }
						$tasaiva=$Trasla['tasa'];
					 }
					 if($Trasla['impuesto']=="IEPS"){
						 if($Trasla['importe']>0){
							  $ieps+=number_format(floatval($Trasla['importe']),2,'.','');
						 }
						 $tasaieps=$Trasla['tasa'];
			 		 }
			 		
			 		// echo '<script>alert("'.$ieps.'");</script>';
				} 
			 	
				$tabla=array();
				$tabla[$_REQUEST['proveedor']]['proveedor']=$_REQUEST['proveedor'];
				$tabla[$_REQUEST['proveedor']]['banco']=$_REQUEST['banco'];
				$tabla[$_REQUEST['proveedor']]['concepto']=$_REQUEST['concepto'];
				$tabla[$_REQUEST['proveedor']]['xml']=$xml;
				$tabla[$_REQUEST['proveedor']]['segmento']=$_REQUEST['segmento'];
				$tabla[$_REQUEST['proveedor']]['sucursal']=$_REQUEST['sucursal'];
				$tabla[$_REQUEST['proveedor']]['beneficiario']=$_REQUEST['beneficiario'];
				$tabla[$_REQUEST['proveedor']]['numero'] = $_REQUEST['numero'];
				$tabla[$_REQUEST['proveedor']]['rfc'] =$_REQUEST['rfc'];
				$tabla[$_REQUEST['proveedor']]['numtarje'] = $_REQUEST['numtarje'];
				$tabla[$_REQUEST['proveedor']]['listabanco']=$_REQUEST['listabanco'];				
				$tabla[$_REQUEST['proveedor']]['formapago']=$_REQUEST['formapago'];
				$tabla[$_REQUEST['proveedor']]['listabancoorigen']=$_REQUEST['listabancoorigen'];
				$tabla[$_REQUEST['proveedor']]['numorigen']=$_REQUEST['numorigen'];//bet
				$tabla[$_REQUEST['proveedor']]['tipocambio']=$tipocambio;
				if(!mb_stristr($xml, "Parcial")){
					$tabla[$_REQUEST['proveedor']]['IVA']=number_format(floatval($iva),2, '.', '');
					$tabla[$_REQUEST['proveedor']]['IVAmxn']=number_format(floatval($iva * $tipocambio),2, '.', '');
					$tabla[$_REQUEST['proveedor']]['IEPS']=number_format(floatval($ieps),2, '.', '');
					$tabla[$_REQUEST['proveedor']]['IEPSmxn']=number_format(floatval($ieps * $tipocambio),2, '.', '');
					$tabla[$_REQUEST['proveedor']]['importe']=number_format(floatval($total),2, '.', '');
					$tabla[$_REQUEST['proveedor']]['importemxn']=number_format(floatval($total * $tipocambio),2, '.', '');
					$tabla[$_REQUEST['proveedor']]['MontoParcial']=0;
				
				 }else{
					$idcuenta=0;
					if(strrpos($_REQUEST['proveedor'],"-")){
						$c=explode('-',$_REQUEST['proveedor']);
						$idcuenta=$c[0];
					}else{
						$provee=explode('/',$_REQUEST['proveedor']);//cargo
						$idcuenta=$provee[0];
						
					}
 					$tasaiva = $tasaiva/100;
					$tasaieps = $tasaieps/100 ;
					$archivo=str_replace("Parcial-", "", $xml);
					$consultaCobros = $this->CaptPolizasModel->cuentaXMLimporte($archivo, $idcuenta,"Cargo M.E.",2);
					$nuevototal = $total - $consultaCobros['monto'];
					$tabla[$_REQUEST['proveedor']]['importe']	= number_format(floatval($nuevototal),2,'.','');
					$tabla[$_REQUEST['proveedor']]['IVA']		= number_format(floatval(($nuevototal/($tasaiva+1))*$tasaiva),2,'.','');
					$tabla[$_REQUEST['proveedor']]['IEPS']		= number_format(floatval(($nuevototal/($tasaieps+1))*$tasaieps),2,'.','');
					
					$tabla[$_REQUEST['proveedor']]['importemxn']	= number_format(floatval($nuevototal * $tipocambio),2,'.','');
					$tabla[$_REQUEST['proveedor']]['IVAmxn']		= number_format(floatval( (($nuevototal/($tasaiva+1))*$tasaiva) *$tipocambio),2,'.','');
					$tabla[$_REQUEST['proveedor']]['IEPSmxn']	= number_format(floatval( (($nuevototal/($tasaieps+1))*$tasaieps) *$tipocambio),2,'.','');
					
					$tabla[$_REQUEST['proveedor']]['MontoParcial']=1;
					
				 }//asigna
				 
				$fec = explode("T", $fecha);
				if($_REQUEST['fecha']){
					$_SESSION['fechaprove']=$_REQUEST['fecha'];
				}else{
					$_SESSION['fechaprove']=$fec[0]."";
				}
					
				
				$_SESSION['proveedor'][]=$tabla;
			}
		}
		echo '<script> window.location="index.php?c=automaticasMonedaExt&f=verpagoext"; </script>';
	}	
			
	function consultaTipoCambioPago(){
		unset($_SESSION['datospagoext']['tipocambiolista']);
		unset($_SESSION['datospagoext']['bancosxmoneda']);
		unset($_SESSION['datospagoext']['cuentaprvxmoneda']);
		unset($_SESSION['datospagoext']['prvconcuenta']);
		$lista = $this->automaticasMonedaExtModel->listaTipoCambio($_REQUEST['idmoneda'],$_REQUEST['periodot']);
		$_SESSION['datospagoext']['moneda'] = $_REQUEST['idmoneda'];
		$bancosxmoneda=$this->automaticasMonedaExtModel->bancosextXmoneda($_REQUEST['idmoneda']);
		$proveedorpadron=$this->automaticasMonedaExtModel->proveedorXmoneda($_REQUEST['idmoneda']);//proveedores del padron asociados a cuenta contable
		$proveedoresarbol=$this->automaticasMonedaExtModel->proveedorcuentasextXmoneda($_REQUEST['idmoneda']);//proveedores del arbol no asociados auna cuenta
		$clie = array();
		while($c = $lista->fetch_assoc()){
			$clie['tipo_cambio']=$c['tipo_cambio'];
			$clie['fecha']=$c['fecha'];
			$_SESSION['datospagoext']['tipocambiolista'][]= $clie;
		}		
		$clie = array();
		while($c = $bancosxmoneda->fetch_assoc()){
			$clie['account_id']=$c['account_id'];
			$clie['description']=$c['description'];
			$clie['manual_code']=$c['manual_code'];
			$_SESSION['datospagoext']['bancosxmoneda'][]= $clie;
		}
		$prvarb = array();
		while($p = $proveedoresarbol->fetch_assoc()){
			$prvarb['account_id']=$p['account_id'];
			$prvarb['description']=$p['description'];
			$prvarb['manual_code']=$p['manual_code'];
			$_SESSION['datospagoext']['cuentaprvxmoneda'][]= $prvarb;
		}
		$prv = array();
		while($d = $proveedorpadron->fetch_assoc()){
			$prv['cuenta']=$d['cuenta'];
			$prv['idPrv']=$d['idPrv'];
			$prv['razon_social']=$d['razon_social'];
			$_SESSION['datospagoext']['prvconcuenta'][]= $prv;
		}
		//pago no detecta las cuenta de prv ver q pasa
		// $tipocambiolista="";
		// while ($row = $lista->fetch_assoc()){ $_SESSION['datosext']['codigo']=$row['codigo'];
			// $tipocambiolista.= "<option value='".$row['tipo_cambio']."'>".$row['fecha']." (".$row['tipo_cambio'].")</option>";	
		// }
		// echo $tipocambiolista;
		//$_SESSION['datosext']['listatipocambio'] = $tipocambiolista;
	}
	
	function cancelaPago(){
		unset($_SESSION['datospagoext']);
		unset($_SESSION['proveedor']);
		unset($_SESSION['fechaprove']);
	}
	function guardaPago(){
		$fecha=$_REQUEST['fecha'];
		$beneficiario = $_REQUEST['beneficiario'];
		$numero = $_REQUEST['numero'];
		$rfc =$_REQUEST['rfc'];
		$numtarjcuent = $_REQUEST['numtarje'];
		$idbanco =$_REQUEST['listabanco'];
		$f=explode("/",$_REQUEST['formapago']);
		$formapago=$f[0];
		$bancoorigen= $_REQUEST['bancoorigen'];
		$numorigen = $_REQUEST['numorigen'];
		$unsolobanco = $_REQUEST['unsolobanco'];
		
		$Exercise = $this->CaptPolizasModel->getExerciseInfo();
		$proveedores = $this->CaptPolizasModel->proveedor();
		// $cuenta = $this->CaptPolizasModel->conf();
		
		if($Ex=$Exercise->fetch_assoc()){
		$idorg=$Ex['IdOrganizacion'];
		$idejer=$Ex['IdEx'];
		$idperio=$Ex['PeriodoActual'];}
		if(isset($_COOKIE['ejercicio'])){
			$idejer = $this->CaptPolizasModel->idex($_COOKIE['ejercicio']);
		}if(isset($_COOKIE['periodo'])){
			$idperio = $_COOKIE['periodo'];
		}
		$cuentas = $this->CaptPolizasModel->cuentasconf();
		
		if($row=$cuentas->fetch_array()){
			$iepspendientepago	= $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSPendientePago']);
			$ivapendientepago	= $this->CaptPolizasModel->buscacuenta($row['CuentaIVAPendientePago']);
			$CuentaIVApagado 	= $this->CaptPolizasModel->buscacuenta($row['CuentaIVApagado']);
			$CuentaIEPSpagado  	= $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSpagado']);
			$statusIVAIEPS      	= $row['statusIVAIEPS'];
			$statusIEPS		    = $row['statusIEPS'];
			$statusIVA		    = $row['statusIVA'];
		}
			$iepspendientepago  = explode("//", $iepspendientepago);
			$ivapendientepago   = explode("//", $ivapendientepago);
			$CuentaIVApagado 	= explode("//", $CuentaIVApagado);
			$CuentaIEPSpagado	= explode("//", $CuentaIEPSpagado);	
		$error=false;
		$carpeta=false;
			
			// if($idperio != 13)
			// {
			
			$poli=$this->CaptPolizasModel->savePoliza2($idorg,$idejer,$idperio,2,'Pago a proveedores',$fecha,$beneficiario,$numero,$rfc,$idbanco,$numtarjcuent,$bancoorigen,1);
			if($poli==0){
				
				$numPoliza = $this->CaptPolizasModel->getLastNumPoliza();
					if(mkdir($this->path()."xmls/facturas/".$numPoliza['id'],  0777)){
						$carpeta=true;
					}
					$UltimoMov=1;$totalbancosme = $totalbancosmxn = 0;
					foreach($_SESSION['proveedor'] as $cli){
						foreach($cli as $prove){
							
							
							if(strrpos($prove['proveedor'],"-")){
								$cprovee="";
								$c=explode('-',$prove['proveedor']);
								$idcuenta=$c[0];
							}else{
								$provee=explode('/',$prove['proveedor']);//cargo
								$cprovee=$provee[1];
								$idcuenta=$provee[0];
								
							}
							$banco=explode('/',$prove['banco']);//abono
							$cbanco=$banco[0];
							
							$separa=explode('_',$prove['xml']);
							 $referencia=str_replace(".xml"," ",$separa[2]);
							 // $f=explode("/",$prove['formapago']);
							 // $formapago=$f[0];
							 $segment = explode('//',$prove['segmento']);
							$sucu = explode('//',$prove['sucursal']);
							
							if($statusIVAIEPS==0){
								$iepspendientepago[0] = $prove['cuentaiepspendiente'];
								$ivapendientepago[0] = $prove['cuentaivapendiente'];
								$CuentaIEPSpagado[0] = $prove['cuentaiepscobrado'];
								$CuentaIVApagado[0] = $prove['cuentaivacobrado'];
							}
							
							$xmlrelacion= str_replace("Parcial-", "", $prove['xml']);
							
							if($prove['concepto']==""){ $prove['concepto']="Pago a Proveedores";}
							 $abono=$this->automaticasMonedaExtModel->InsertMovExt($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$idcuenta,"Cargo M.E.",$prove['importe'],$prove['concepto'],'2-'.$cprovee,$xmlrelacion,$referencia,$formapago,$prove['tipocambio']);
							 $abonomxn=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$idcuenta,"Cargo",number_format($prove['importe'] * $prove['tipocambio'],2,'.',''),$prove['concepto'],'2-'.$cprovee,$xmlrelacion,$referencia,$formapago);
							
							if($abono==true){
								if($unsolobanco==0){
								 $UltimoMov+=1;
								  $si=$this->automaticasMonedaExtModel->InsertMovExt($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$cbanco,"Abono M.E",$prove['importe'],$prove['concepto'],'2-'.$cprovee,$xmlrelacion,$referencia,$formapago,$prove['tipocambio']);	
							 	  $simxn=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$cbanco,"Abono",number_format($prove['importe'] * $prove['tipocambio'],2,'.',''),$prove['concepto'],'2-'.$cprovee,$xmlrelacion,$referencia,$formapago);	
								}else{
									$si = true;
									$conceptoBancos = $prove['concepto'];
									$totalbancosmxn += $prove['importe'] * $prove['tipocambio'];
									$totalbancosme  += $prove['importe'];
									$tipocambiounsolo = $prove['tipocambio'];
								}
							 		if($si==true){ $UltimoMov+=1;
										if($statusIVA==1){
								 			if($prove['IVA']>0){
									 			//$insertaivapendiente = 	$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$ivapendientepago[0],"Abono M.E",$prove['IVA'],$prove['concepto'],'2-'.$cprovee,$xmlrelacion,$referencia,$formapago);	
												$insertaivapendientemxn = 	$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$ivapendientepago[0],"Abono",number_format($prove['IVA'] * $prove['tipocambio'],2,'.',''),$prove['concepto'],'2-'.$cprovee,$xmlrelacion,$referencia,$formapago);	
												
												$UltimoMov+=1;
												//$insertaivapagado = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$CuentaIVApagado[0],"Cargo M.E.",$prove['IVA'],$prove['concepto'],'2-'.$cprovee,$xmlrelacion,$referencia,$formapago);
												$insertaivapagadomxn = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$CuentaIVApagado[0],"Cargo",number_format($prove['IVA'] * $prove['tipocambio'],2,'.',''),$prove['concepto'],'2-'.$cprovee,$xmlrelacion,$referencia,$formapago);
												
												$UltimoMov+=1;
											}
										}
										if($statusIEPS==1){
											if($prove['IEPS']>0){//
									 			//$insertaivapendiente = 	$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$iepspendientepago[0],"Abono M.E",$prove['IEPS'],$prove['concepto'],'2-'.$cprovee,$xmlrelacion,$referencia,$formapago);	
												$insertaivapendientemxn = 	$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$iepspendientepago[0],"Abono",number_format($prove['IEPS'] * $prove['tipocambio'],2,'.',''),$prove['concepto'],'2-'.$cprovee,$xmlrelacion,$referencia,$formapago);	
												
												$UltimoMov+=1;
												//$insertaivapagado = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$CuentaIEPSpagado[0],"Cargo M.E.",$prove['IEPS'],$prove['concepto'],'2-'.$cprovee,$xmlrelacion,$referencia,$formapago);
												$insertaivapagadomxn = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$CuentaIEPSpagado[0],"Cargo",number_format($prove['IEPS'] * $prove['tipocambio'],2,'.',''),$prove['concepto'],'2-'.$cprovee,$xmlrelacion,$referencia,$formapago);
												
												$UltimoMov+=1;
											}
										}
							 		}
									 if($si==false){
							 			$error=true;
							 		}else{
							 			if($carpeta==true){
							 				$rutaOrigen = $this->path()."xmls/facturas/temporales/".$prove['xml'];
							 				if($prove['parcial']==0){
												//str_replace("Parcial-", "", $prove['xml'])
												rename($rutaOrigen, $this->path()."xmls/facturas/".$numPoliza['id']."/".$xmlrelacion);
												$this->CaptPolizasModel->facturaRename($xmlrelacion);
											}
											else{
												copy($rutaOrigen, $this->path()."xmls/facturas/".$numPoliza['id']."/".$xmlrelacion);
												rename($rutaOrigen, $this->path()."xmls/facturas/temporales/Parcial-".$xmlrelacion);
												$this->CaptPolizasModel->facturaRename("Parcial-".$xmlrelacion);
											}
											
										}
							 		}
							 }else{
							 	$error=true;
							 }
						}
					}
					if($unsolobanco == 1){
					  $UltimoMov+=1;
					  $si=$this->automaticasMonedaExtModel->InsertMovExt($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$cbanco,"Abono M.E",$totalbancosme,$conceptoBancos,'2-'.$cprovee,$xmlrelacion,$referencia,$formapago,$tipocambiounsolo);	
				 	  $simxn=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$cbanco,"Abono",number_format($totalbancosmxn,2,'.',''),$conceptoBancos,'2-'.$cprovee,$xmlrelacion,$referencia,$formapago);	
					}
			
			}//el de insertar poliza 
			else{
				$error=true;
			}
  			if($error==false){
  				unset($_SESSION['proveedor']);
				unset($_SESSION['fechaprove']);
				unset($_SESSION['datospagoext']);
				echo '-_-1-_-';
  			}else{
  				echo '-_-2-_-';
  			}
			
		
		// }else
		// {
			// echo '-_-0-_-';
		// }
	}
function vercobroext(){
	$periodo=$this->CaptPolizasModel->getExerciseInfo();
	$forma_pago=$this->CaptPolizasModel->formapago();
		$cuentas = $this->CaptPolizasModel->cuentasconf();
		if($row=$cuentas->fetch_array()){
			$iepspendientecobro = $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSPendienteCobro']);
			$ivapendientecobro  = $this->CaptPolizasModel->buscacuenta($row['CuentaIVAPendienteCobro']);
			$iepscobrado        = $this->CaptPolizasModel->buscacuenta($row['CuentaIEPScobrado']);
			$ivacobrado         = $this->CaptPolizasModel->buscacuenta($row['CuentaIVAcobrado']);
			$statusIVAIEPS      = $row['statusIVAIEPS'];
			$statusRetencionISH = $row['statusRetencionISH'];
			$statusIEPS		    = $row['statusIEPS'];
			$statusIVA		    = $row['statusIVA'];
			$CuentaIEPSgasto		= $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSgasto']);
			$CuentaIVAgasto		= $this->CaptPolizasModel->buscacuenta($row['CuentaIVAgasto']);
		
		}
		$iepspendientecobro = explode("//", $iepspendientecobro);
		$ivapendientecobro = explode("//", $ivapendientecobro);
		$iepscobrado = explode("//", $iepscobrado);
		$ivacobrado = explode("//", $ivacobrado);
		$CuentaIEPSgasto = explode("//", $CuentaIEPSgasto);
		$CuentaIVAgasto  = explode("//",$CuentaIVAgasto);
		
		$cuentas=$this->CaptPolizasModel->cuentaivas();
		$listadoivaieps="";
		while($campo=$cuentas->fetch_array()){
			$listadoivaieps .= "<option value=".$campo['account_id'].">".$campo['description']."(".$campo['manual_code'].")</option>";
		}
			
		if($p=$periodo->fetch_array()){
			$ejercicio = $p['NombreEjercicio'];
			if($p['PeriodoActual']!=13){
				$idperiodo=$p['PeriodoActual'];
				$moneda = $this->automaticasMonedaExtModel->tipomoneda();
				$sql=$this->automaticasMonedaExtModel->bancosexttodas();
				//$sql=$this->CaptPolizasModel->bancos();
				//$sqlcli=$this->automaticasMonedaExtModel->clientesext();
				$sqlcliarbol=$this->automaticasMonedaExtModel->clientesCuentaExt();//
				$sqlcliarbol2=$this->automaticasMonedaExtModel->clientesCuentaExt();//
				$ListaSegmentos = $this->CaptPolizasModel->ListaSegmentos();
				$ListaSucursales = $this->CaptPolizasModel->ListaSucursales();
				$Exercise = $this->CaptPolizasModel->getExerciseInfo();
				$Ex = $Exercise->fetch_assoc();
				$firstExercise = $this->CaptPolizasModel->getFirstLastExercise(0);
				$lastExercise = $this->CaptPolizasModel->getFirstLastExercise(1);
				$Suc = $this->CaptPolizasModel->getSegmentoInfo();
		
				if($sql->num_rows>0){
					$bancos=$sql;
				}else{
					$bancosno="Por favor elija una cuenta de bancos en el menu de configuracion  o agregue cuentas al arbol ";
				}
				if($sqlcliarbol->num_rows>0){
					//$clientes=$sqlcli;
					$sqlcli2=$sqlcliarbol;
					$cuentasinarbol=$sqlcliarbol2;
					
				}else{
					$clientesno="Por favor elija una cuenta de clientes en el menu de configuracion o agregue cuentas al arbol ";
				}
				if(intval($Ex['IdEx']) AND intval($Suc))// Si existe el ejercicio
				{
				 	$Abonos = $this->CaptPolizasModel->GetAbonosCargos('Abono','Periodo',$Ex['PeriodoActual'],$Ex['IdEx']);
					$Cargos = $this->CaptPolizasModel->GetAbonosCargos('Cargo','Periodo',$Ex['PeriodoActual'],$Ex['IdEx']);
					$lista = $this->automaticasMonedaExtModel->listaTipoCambio(0,$Ex['NombreEjercicio']."-".$Ex['PeriodoActual']);
					
					include('views/captpolizas/monedaext/cobroext.php');
				}
				else
				{
					echo "<script>alert('Antes de capturar polizas configure un ejercicio y tambien Segmentos de Negocio.');window.parent.agregatab('../../modulos/cont/index.php?c=Config','Configuración Ejercicios','',142);window.parent.agregatab('../catalog/gestor.php?idestructura=251&ticket=testing','Segmentos de Negocio','',1656)</script>";
				}
				
			
			}else{
				echo '<script> alert("No puedes generar polizas automaticas en el perido 13");</script>';
			}
		}
}
function consultaTipoCambioCobro(){
	unset($_SESSION['datoscobroext']['tipocambiolista']);
	unset($_SESSION['datoscobroext']['bancosxmoneda']);
	unset($_SESSION['datoscobroext']['cuentaclixmoneda']);
	
	$lista = $this->automaticasMonedaExtModel->listaTipoCambio($_REQUEST['idmoneda'],$_REQUEST['periodot']);
	$_SESSION['datoscobroext']['moneda'] = $_REQUEST['idmoneda'];
	$bancosxmoneda=$this->automaticasMonedaExtModel->bancosextXmoneda($_REQUEST['idmoneda']);
	$cuentaxmoneda=$this->automaticasMonedaExtModel->clientesCuentaExtXmoneda($_REQUEST['idmoneda']);//proveedores del arbol no asociados auna cuenta
	$clie = array();
	while($c = $lista->fetch_assoc()){
		$clie['tipo_cambio']=$c['tipo_cambio'];
		$clie['fecha']=$c['fecha'];
		$_SESSION['datoscobroext']['tipocambiolista'][]= $clie;
	}		
	$clie = array();
	while($c = $bancosxmoneda->fetch_assoc()){
		$clie['account_id']=$c['account_id'];
		$clie['description']=$c['description'];
		$clie['manual_code']=$c['manual_code'];
		$_SESSION['datoscobroext']['bancosxmoneda'][]= $clie;
	}
	$prvarb = array();
	while($p = $cuentaxmoneda->fetch_assoc()){
		$prvarb['account_id']=$p['account_id'];
		$prvarb['description']=$p['description'];
		$prvarb['manual_code']=$p['manual_code'];
		$_SESSION['datoscobroext']['cuentaclixmoneda'][]= $prvarb;
	}
		
}
function cancelaCobro(){
	unset($_SESSION['tabla']);
	unset($_SESSION['fechacli']);
	unset($_SESSION['datoscobroext']);
}
function arraycobro(){
	$moneda = $_REQUEST['moneda'];
	
	if($_REQUEST['tipocambio']==0){
			$_REQUEST['tipocambio']=$_REQUEST['tipocambio2'];
		}
	$tipocambio = $_REQUEST['tipocambio'];
	$_SESSION['datoscobroext']['tipocambio']=$tipocambio;
	$cuentaconf = $this->CaptPolizasModel->cuentasconf();
	$cuentaimp = $cuentaconf->fetch_object();
			if($_REQUEST['radio']==2){
				
			}else{
				$maximo = count($_POST['xml']);
				$maximo = (intval($maximo)-1);
				//$leer=file_get_contents($_REQUEST['xml']);
				for($i=0;$i<=$maximo;$i++){
					$cade="";$xml="";$uuid="";$total=0.00;$tabla=array();
					$xml = $_POST['xml'][$i];
					$xmld = simplexml_load_file($this->path().'xmls/facturas/temporales/'.$_POST['xml'][$i]);
					$ns = $xmld -> getNamespaces(true);
					$xmld -> registerXPathNamespace('c', $ns['cfdi']);//altera los prefijos del namespace
					$xmld -> registerXPathNamespace('t', $ns['tfd']);
					foreach ($xmld->xpath('//t:TimbreFiscalDigital') as $tfd) {
	  					$uuid= $tfd['UUID']; 
					} 
					foreach ($xmld->xpath('//cfdi:Comprobante') as $cfdiComprobante){
						if(!$cfdiComprobante['fecha']){
					 		$fecha= $cfdiComprobante['Fecha'];
						}
					  	if(!$cfdiComprobante['folio']){
					  		$folio=$cfdiComprobante['Folio'];
						}
						if(!$cfdiComprobante['total']){
							$cfdiComprobante['total'] = $cfdiComprobante['Total'];
						}
					  $fecha= $cfdiComprobante['fecha']; 
					  $folio=$cfdiComprobante['folio'];
					  $total+=number_format(floatval($cfdiComprobante['total']),2,'.',''); 
					}
					 foreach ($xmld->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){
					 	if(!$Emisor['nombre']){
					 		$Emisor['nombre'] = $Emisor['Nombre'];
					 	} 
	   					 $emisor=$Emisor['nombre']; 
	    				}	 
					$ieps=0; $iva=0;
					$tabla[$_REQUEST['cliente']]['IVA']=0;
					$tabla[$_REQUEST['cliente']]['IEPS']=0;
					foreach ($xmld->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Trasla){
						if(!$Trasla['impuesto']){
							$Trasla['impuesto'] = $this->CaptPolizasModel->nombreImpuestoIndividual($Trasla['Impuesto']);
							$Trasla['importe'] = $Trasla['Importe'];	
						} 
						if($Trasla['impuesto']=="IVA"){
					  		if($Trasla['importe']>0){
					  			$iva+=number_format(floatval($Trasla['importe']),2,'.','');
							 }
							$tasaiva=$Trasla['tasa'];
						 }
						 if($Trasla['impuesto']=="IEPS"){
							 if($Trasla['importe']>0){
								  $ieps+=number_format(floatval($Trasla['importe']),2,'.','');
							 }
							 $tasaieps = $Trasla['tasa'];
				 		 }
				 		
				 		// echo '<script>alert("'.$ieps.'");</script>';
					} 
				
				
				//pato
				
				$tabla[$_REQUEST['cliente']]['cliente']		= $_REQUEST['cliente'];
				$tabla[$_REQUEST['cliente']]['cuentacliente']= $_REQUEST['clientesincuenta'];
				$tabla[$_REQUEST['cliente']]['banco']		= $_REQUEST['banco'];
				$tabla[$_REQUEST['cliente']]['concepto']		= $_REQUEST['concepto'];
				$tabla[$_REQUEST['cliente']]['segmento']		= $_REQUEST['segmento'];
				$tabla[$_REQUEST['cliente']]['sucursal']		= $_REQUEST['sucursal'];
				$tabla[$_REQUEST['cliente']]['xml']			= $xml;
				$tabla[$_REQUEST['cliente']]['tipocambio']=$tipocambio;
				
				$tabla[$_REQUEST['cliente']]['formapago']	= $_REQUEST['formapago'];
				$tabla[$_REQUEST['cliente']]['numeroformapago']	= $_REQUEST['numeroformapago'];
				
				if(!mb_stristr($xml, "Parcial")){
					$tabla[$_REQUEST['cliente']]['importe']	= number_format(floatval($total),2,'.','');
					$tabla[$_REQUEST['cliente']]['IVA']		= number_format(floatval($iva),2,'.','');
					$tabla[$_REQUEST['cliente']]['IEPS']		= number_format(floatval($ieps),2,'.','');
					
					$tabla[$_REQUEST['cliente']]['importemxn']	= number_format(floatval($total * $tipocambio),2,'.','');
					$tabla[$_REQUEST['cliente']]['IVAmxn']		= number_format(floatval($iva * $tipocambio),2,'.','');
					$tabla[$_REQUEST['cliente']]['IEPSmxn']		= number_format(floatval($ieps * $tipocambio),2,'.','');
					
					$tabla[$_REQUEST['cliente']]['MontoParcial']=0;
				}else{
					$idcuenta=0;
					if(strrpos($_REQUEST['cliente'],"-")){
						$c=explode('-',$_REQUEST['cliente']);
						$idcuenta = $c[0];
					}else{
						$client=explode('/',$_REQUEST['cliente']);//abono
						$verifica=$this->CaptPolizasModel->validacuentaclientes($client[0]);
						if($verifica==0){
							$idcuenta=$cliente['cuentacliente'];
						}else{
							$idcuenta=$verifica;
							
						}
					}
					$tasaiva = $tasaiva/100;
					$tasaieps = $tasaieps/100 ;
					$archivo=str_replace("Parcial-", "", $xml);
					$consultaCobros = $this->CaptPolizasModel->cuentaXMLimporte($archivo, $idcuenta,"Abono M.E",1);
					$nuevototal = $total - $consultaCobros['monto'];
					$tabla[$_REQUEST['cliente']]['importe']	= number_format(floatval($nuevototal),2,'.','');
					$tabla[$_REQUEST['cliente']]['IVA']	= number_format(floatval(($nuevototal/($tasaiva+1))*$tasaiva),2,'.','');
					$tabla[$_REQUEST['cliente']]['IEPS']	= number_format(floatval(($nuevototal/($tasaieps+1))*$tasaieps),2,'.','');
					
					$tabla[$_REQUEST['cliente']]['importemxn']	= number_format(floatval($nuevototal * $tipocambio),2,'.','');
					$tabla[$_REQUEST['cliente']]['IVAmxn']	= number_format(floatval( (($nuevototal/($tasaiva+1))*$tasaiva) * $tipocambio),2,'.','');
					$tabla[$_REQUEST['cliente']]['IEPSmxn']	= number_format(floatval( (($nuevototal/($tasaieps+1))*$tasaieps) * $tipocambio),2,'.','');
					
					$tabla[$_REQUEST['cliente']]['MontoParcial']=1;
					
				 }//asigna
				
				
				$fec = explode("T", $fecha);
				if($_REQUEST['fecha']){
					$_SESSION['fechacli']=$_REQUEST['fecha'];
				}else{
					$_SESSION['fechacli']=$fec[0]."";
				}
				
						
				$_SESSION['tabla'][]=$tabla;
			}
		}
			echo '<script>window.location="index.php?c=automaticasMonedaExt&f=vercobroext"; </script>';
		
}
function guardaCobro(){
	$fecha=$_REQUEST['fecha'];
	$unsolobanco = $_REQUEST['unsolobanco'];
	
		$Exercise = $this->CaptPolizasModel->getExerciseInfo();
		$cuenta = $this->CaptPolizasModel->conf();
		if($Ex=$Exercise->fetch_assoc()){
		$idorg=$Ex['IdOrganizacion'];
		$idejer=$Ex['IdEx'];
		$idperio=$Ex['PeriodoActual'];}
		if(isset($_COOKIE['ejercicio'])){
			$idejer = $this->CaptPolizasModel->idex($_COOKIE['ejercicio']);
		}if(isset($_COOKIE['periodo'])){
			$idperio = $_COOKIE['periodo'];
		}
		$error=false;
		$carpeta=false;
		// if($idperio != 13)
		// {
		$cuentas = $this->CaptPolizasModel->cuentasconf();
		if($row=$cuentas->fetch_array()){
			$iepspendientecobro = $this->CaptPolizasModel->buscacuenta($row['CuentaIEPSPendienteCobro']);
			$ivapendientecobro  = $this->CaptPolizasModel->buscacuenta($row['CuentaIVAPendienteCobro']);
			$iepscobrado        = $this->CaptPolizasModel->buscacuenta($row['CuentaIEPScobrado']);
			$ivacobrado         = $this->CaptPolizasModel->buscacuenta($row['CuentaIVAcobrado']);
			$statusIVAIEPS      = $row['statusIVAIEPS'];
			$statusIEPS		    = $row['statusIEPS'];
			$statusIVA		    = $row['statusIVA'];
				
		}
		$iepspendientecobro = explode("//", $iepspendientecobro);
		$ivapendientecobro = explode("//", $ivapendientecobro);
		$iepscobrado = explode("//", $iepscobrado);
		$ivacobrado = explode("//", $ivacobrado);
		
		
		//pasar los valores a insertar
			$poli=$this->CaptPolizasModel->savePoliza2($idorg,$idejer,$idperio,1,'Cobro a cliente',$fecha,0,$_REQUEST['numeroforma'],"",0,"",0,0);
			if($poli==0){
				$numPoliza = $this->CaptPolizasModel->getLastNumPoliza();
					if(mkdir($this->path()."xmls/facturas/".$numPoliza['id'],  0777)){
						$carpeta=true;
					}
				//krmn aki cambia clientes
				$UltimoMov=1;$totalbancosme = $totalbancosmxn = 0;
					foreach($_SESSION['tabla'] as $cli){
						foreach($cli as $cliente){
							if(strrpos($cliente['cliente'],"-")){
								$ccliente="";
								$c=explode('-',$cliente['cliente']);
								$idcuenta = $c[0];
							}else{
								// $client=explode('/',$cliente['cliente']);//abono
								// $ccliente = $client[0];
								// $verifica=$this->CaptPolizasModel->validacuentaclientes($client[0]);
								// if($verifica==0){
								// //if($row=$cuenta->fetch_assoc()){
									// $idcuenta=$cliente['cuentacliente'];
									// //$updatecliente=$this->CaptPolizasModel->actualizacliente($idcuenta, $ccliente);
								// }else{
									// $idcuenta=$verifica;
								// }
							 	////hacer prueba con los demas clientes
								//}
							}
							if($statusIVAIEPS==0){
								$iepspendientecobro[0] = $cliente['cuentaiepspendiente'];
								$ivapendientecobro[0] = $cliente['cuentaivapendiente'];
								$iepscobrado[0] = $cliente['cuentaiepscobrado'];
								$ivacobrado[0] = $cliente['cuentaivacobrado'];
							}
							$banco=explode('/',$cliente['banco']);//cargo
							 $cbanco=$banco[0];
							
							
							 // $UltimoMov = $this->CaptPolizasModel->UltimoMov($numPoliza['id']);
							 // if($UltimoMov==""){
							 	// $UltimoMov=1;
							 // }else{
							 	// $UltimoMov++;
							 // }
							$separa=explode('_',$cliente['xml']);
							$referencia=str_replace(".xml"," ",$separa[2]);
							$segment = explode('//',$cliente['segmento']);
							$sucu = explode('//',$cliente['sucursal']);
							 //pato2
							 
							$xmlrelacion= str_replace("Parcial-", "", $cliente['xml']);
							if($cliente['concepto']==""){ $cliente['concepto']="Cobro a Clientes";}
								$abono=$this->automaticasMonedaExtModel->InsertMovExt($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$idcuenta,"Abono M.E",$cliente['importe'],$cliente['concepto'],'1-'.$ccliente,$xmlrelacion,$referencia,0,$cliente['tipocambio']);
								$abonomxn=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$idcuenta,"Abono",number_format($cliente['importe'] * $cliente['tipocambio'],2,'.',''),$cliente['concepto'],'1-'.$ccliente,$xmlrelacion,$referencia,$cliente['formapago']);
							 if($abono==true){
							 	if($unsolobanco==0){
									$UltimoMov+=1;
								 	$si=$this->automaticasMonedaExtModel->InsertMovExt($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$cbanco,"Cargo M.E.",$cliente['importe'],$cliente['concepto'],'1-'.$ccliente,$xmlrelacion,$referencia,0,$cliente['tipocambio']);	
								 	$simxn=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$cbanco,"Cargo",number_format($cliente['importe'] * $cliente['tipocambio'],2,'.',''),$cliente['concepto'],'1-'.$ccliente,$xmlrelacion,$referencia,$cliente['formapago']);	
								 }else{
								 	$si = true;
									$conceptoBancos = $cliente['concepto'];
									$totalbancosmxn += $cliente['importe'] * $cliente['tipocambio'];
									$totalbancosme  += $cliente['importe'];
									$tipocambiounsolo = $cliente['tipocambio'];
								 }
						 		$UltimoMov+=1;
						 		if($statusIVA==1){
							 		if($cliente['IVA']>0){
							 			//$insertaivapendiente = 	$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$ivapendientecobro[0],"Cargo M.E.",$cliente['IVA'],$cliente['concepto'],'1-'.$ccliente,$xmlrelacion,$referencia,0);	
							 			$insertaivapendientemxn = 	$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$ivapendientecobro[0],"Cargo",number_format($cliente['IVA']* $cliente['tipocambio'],2,'.',''),$cliente['concepto'],'1-'.$ccliente,$xmlrelacion,$referencia,$cliente['formapago']);	
										$UltimoMov+=1;
										//$insertaivacobrado = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$ivacobrado[0],"Abono M.E",$cliente['IVA'],$cliente['concepto'],'1-'.$ccliente,$xmlrelacion,$referencia,0);
										$insertaivacobradomxn = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$ivacobrado[0],"Abono",number_format($cliente['IVA']* $cliente['tipocambio'],2,'.',''),$cliente['concepto'],'1-'.$ccliente,$xmlrelacion,$referencia,$cliente['formapago']);
										$UltimoMov+=1;
									}
								}
								if($statusIEPS==1){
									if($cliente['IEPS']>0){//
										$insertaivapendientemxn = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$iepspendientecobro[0],"Cargo",number_format($cliente['IEPS']* $cliente['tipocambio'],2,'.','') ,$cliente['concepto'],'1-'.$ccliente,$xmlrelacion,$referencia,$cliente['formapago']);	
							 			//$insertaivapendiente = 	$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$iepspendientecobro[0],"Cargo M.E.",$cliente['IEPS'],$cliente['concepto'],'1-'.$ccliente,$xmlrelacion,$referencia,0);	
										$UltimoMov+=1;
										//$insertaivacobrado = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$iepscobrado[0],"Abono M.E",$cliente['IEPS'],$cliente['concepto'],'1-'.$ccliente,$xmlrelacion,$referencia,0);
										$insertaivacobradomxn = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$iepscobrado[0],"Abono",number_format($cliente['IEPS']* $cliente['tipocambio'],2,'.',''),$cliente['concepto'],'1-'.$ccliente,$xmlrelacion,$referencia,$cliente['formapago']);
										$UltimoMov+=1;
									}
								}
						 		if($si==false){
						 			$error=true;
						 		}else{
						 			 if($carpeta==true){
						 			 	$rutaOrigen = $this->path()."xmls/facturas/temporales/".$cliente['xml'];
										 //str_replace("Parcial", "", $cliente['xml'])
						 			 	if($cliente['parcial']==0){//cobro
											rename($rutaOrigen, $this->path()."xmls/facturas/".$numPoliza['id']."/".$xmlrelacion );
											$this->CaptPolizasModel->facturaRename($xmlrelacion);
										}
										else{
											copy($rutaOrigen, $this->path()."xmls/facturas/".$numPoliza['id']."/".$xmlrelacion );
											rename($rutaOrigen, $this->path()."xmls/facturas/temporales/Parcial-".$xmlrelacion);
											$this->CaptPolizasModel->facturaRename("Parcial-".$xmlrelacion);
										}
									}
								 }
							 }else{
							 	$error=true;
							 }
						}
					}
					if($unsolobanco==1){
					 	$si=$this->automaticasMonedaExtModel->InsertMovExt($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$cbanco,"Cargo M.E.",$totalbancosme,$conceptoBancos,'1-'.$ccliente,$xmlrelacion,$referencia,0,$tipocambiounsolo);	
					 	$simxn=$this->CaptPolizasModel->InsertMov2($numPoliza['id'],$UltimoMov,$segment[0],$sucu[0],$cbanco,"Cargo",number_format($totalbancosmxn,2,'.',''),$conceptoBancos,'1-'.$ccliente,$xmlrelacion,$referencia,$cliente['formapago']);	
					 }
			}//el de insertar poliza 
			else{
				$error=true;
			}
  			if($error==false){
  				unset($_SESSION['tabla']);
				unset($_SESSION['fechacli']);
				unset($_SESSION['datoscobroext']);
				
				echo '-_-1-_-';
  			}else{
  				echo '-_-2-_-';
  			}
			
}
function listaTemporalesProvisionME()
	{
		global $xp;
		$listaTemporales = "<tr><td width='50' style='color:white;'>*1_-{}*</td><td width='300'></td><td width='50'></td><td width='50'></td><td width='50'></td><td width='50'></td><td width='50'></td><td width='50'></td><td width='50'></td><td width='50' style='font-weight:bold;font-size:9px;text-align:center;'><button id='' onclick='buttondesclick(\"copiar\")'>Desmarcar</button></td></tr>";

		$buscar = "{".$_POST['folio_uuid']."}";
			
		
		if(intval($_POST['tipo_busqueda']) == 1)
		{//Si es UUID
			if($archivos = glob($this->path()."xmls/facturas/temporales/*".strtoupper($buscar).".xml",GLOB_BRACE))
				$r = 0;//Si la encuentra en mayusculas
			elseif($archivos = glob($this->path()."xmls/facturas/temporales/*".strtolower($buscar).".xml",GLOB_BRACE))
				$r = 0;//Si la encuentra en minusculas
			else
			{
				$buscar = "*".$buscar;
				$r = 1;
			}
		}
		if(!intval($_POST['tipo_busqueda']))
		{//Si es Folio
			$buscar = $buscar."_*";	
			$r = 1;
		}

		if(intval($_POST['tipo_busqueda']) == 2)
		{//Si es Razon Social
			if($archivos = glob($this->path()."xmls/facturas/temporales/*_*".strtoupper($buscar)."*_*.xml",GLOB_BRACE))
				$r = 0;//Si la encuentra en mayusculas
			elseif($archivos = glob($this->path()."xmls/facturas/temporales/*_*".strtolower($buscar)."*_*.xml",GLOB_BRACE))
				$r = 0;//Si la encuentra en minusculas
			else
			{
				$r = 1;//Si no la encuentra en ninguna
				$buscar = "*_*$buscar*_*";
			}
		}

		
			
		$dir = $this->path()."xmls/facturas/temporales/$buscar.xml";
		//echo $dir;

		// Abrir un directorio, y proceder a leer su contenido
		if($r)
			$archivos = glob($dir,GLOB_BRACE);
		array_multisort(array_map('filectime', $archivos),SORT_DESC,$archivos);

		$cont=1;
		foreach($archivos as $file) 
		{
			if($archivo != '.' AND $archivo != '..' AND $archivo != '.DS_Store' AND $archivo != '.file'){
				$texto 	= file_get_contents($file);
				$xml 	= new DOMDocument();
				$xml->loadXML($texto);
				$xp = new DOMXpath($xml);
				if($this->getpath("//@version"))
		            $data['version'] = $this->getpath("//@version");
		        else
		        		$data['version'] = $this->getpath("//@Version");
	
				$version = $data['version'];
				if($version[0] == '3.3')
			{
				$data['total'] = $this->getpath("//@Total");
				$data['descripcion'] = $this->getpath("//@Descripcion");
				$data['rfc'] = $this->getpath("//@Rfc");
				$data['FechaTimbrado'] = $this->getpath("//@FechaTimbrado");
				$data['impuesto'] = $this->CaptPolizasModel->nombreImpuesto($this->getpath("//@Impuesto"));
				$data['subtotal'] = $this->getpath("//@SubTotal");
				$data['descuento'] = $this->getpath("//@Descuento");
				$data['nombre'] = $this->getpath("//@Nombre");
				$data['descripcion2']=$this->getpath("//@Descripcion");
				$data['cantidad']=$this->getpath("//@Cantidad");
				$data['unidad']=$this->getpath("//@Unidad");
				$data['valorUnitario']=$this->getpath("//@ValorUnitario");
				$data['importe']=$this->getpath("//@Importe");
				$data['nomina']=$this->getpath("//@NumEmpleado");
				$data['metodoDePago']=$this->getpath("//@MetodoPago");
				$data['uuid'] = 	$this->getpath("//@UUID");
				$data['uuid'] = $data['uuid'][1];
				$data['folio']=$this->getpath("//@Folio");
				$data['metodoDePago']=$this->getpath("//@MetodoPago");
			}
			else
			{
				$data['total'] = $this->getpath("//@total");
				$data['descripcion'] = $this->getpath("//@descripcion");
				$data['rfc'] = $this->getpath("//@rfc");
				$data['FechaTimbrado'] = $this->getpath("//@FechaTimbrado");
				$data['impuesto'] = $this->getpath("//@impuesto");
				$data['subtotal'] = $this->getpath("//@subTotal");
				$data['descuento'] = $this->getpath("//@descuento");
				$data['nombre'] = $this->getpath("//@nombre");
				$data['descripcion2']=$this->getpath("//@descripcion");
				$data['cantidad']=$this->getpath("//@cantidad");
				$data['unidad']=$this->getpath("//@unidad");
				$data['valorUnitario']=$this->getpath("//@valorUnitario");
				$data['importe']=$this->getpath("//@importe");
				$data['nomina']=$this->getpath("//@NumEmpleado");
				$data['metodoDePago']=$this->getpath("//@metodoDePago");
				$data['uuid'] = 	$this->getpath("//@UUID");
				$data['folio']=$this->getpath("//@folio");
				$data['metodoDePago']=$this->getpath("//@metodoDePago");
				
			}
				$moneda 	= $this->getpath("//@Moneda");
				
				$monedacambio = $this->getpath("//@TipoCambio");
			if($moneda!="MXN" && $moneda!="Peso Mexicano" && $moneda!="MN" && $moneda!="MXP" && $moneda!="Pesos" && $moneda!="M.N." && $moneda!="M.X.N." && $moneda!="Pesos Mexicanos" && $monedacambio){
					
					$rfcOrganizacion= $this->CaptPolizasModel->rfcOrganizacion();
					if($data['rfc'][0] == $rfcOrganizacion['RFC'])
					{
						$tipoDeComprobante = "Ingreso";
					}
					elseif($data['rfc'][1] == $rfcOrganizacion['RFC'])
					{
						$tipoDeComprobante = "Egreso";	
					}
					if($data['nomina']){ $tipoDeComprobante = "Nomina";}
					$fec = explode("T", $data['FechaTimbrado'] );
					if(is_array($data['descripcion']))
						{
							$data['descripcion'] = $data['descripcion'][0];
						}
					$data['tipocomprobante']= $tipoDeComprobante;
					$name = explode('_',$file);
					$auto = explode('/', $name[0]);
					$listaTemporales .= "<tr>
					<td width='50'><img src='xmls/imgs/xml.jpg' width=30><b>".$data['folio']."</b></td>
					<td width='300'><b>".$auto[3]."</b> ".$name[1]."</td>
					<td width=300'><b>".$data['descripcion']."</b></td>
					<td width='60'><center>".$tipoDeComprobante."</center></td>
					<td align='center' width='200'><b style='color:red'>".number_format($data['total'],2,'.',',')."</b></td><td></td>
					<td width='200'><b>".$data['metodoDePago']."</b></td>
					<td width='200'><b>".$fec[0]."</b></td>
					<td width='50'><a href='views/captpolizas/visor.php?data=".urlencode(serialize($data))."' target='_blank'>Ver</a></td>
					<td width='50' style='text-align:center;'><input title='Sólo copiar' type='radio' name='radio-$cont' id='copiar-$cont' value='".$file."' class='copiar'></td>
					</tr>";
					$cont++;
				}
			}
		}

		echo $listaTemporales;
	}

	function listaTemporalesProvisionMEBD()
	{
		$_POST['moneda'] = 'Dlls';
		$datos = $this->CaptPolizasModel->listaTemporalesBD($_POST);
		$listaTemporales = "<tr><td width='50' style='color:white;'>*1_-{}*</td><td width='300'></td><td width='50'></td><td width='50'></td><td width='50'></td><td width='50'></td><td width='50'></td><td width='50'></td><td width='50'></td><td width='50' style='font-weight:bold;font-size:9px;text-align:center;'><button id='' onclick='buttondesclick(\"copiar\")'>Desmarcar</button></td></tr>";

		$cont = 0;

		while($d = $datos->fetch_object())
		{
			$d->json = str_replace("\\", "", $d->json);
			$json = json_decode($d->json);
			$json = $this->object_to_array($json);

			$tipocambio = $json['Comprobante']['@TipoCambio'];

			if($tipocambio)
			{
				$razon = $d->receptor;
				if($d->tipo == "Egreso")
					$razon = $d->emisor;

				if($d->version == '3.2')
				{
					$descripcion = $json['Comprobante']['cfdi:Conceptos']['cfdi:Concepto']['@descripcion'];
					if(!$descripcion)
						$descripcion = $json['Comprobante']['cfdi:Conceptos']['cfdi:Concepto'][0]['@descripcion'];
					$metodoPago = $json['Comprobante']['@metodoDePago'];
				}
				if($d->version == '3.3')
				{
					$descripcion = $json['Comprobante']['cfdi:Conceptos']['cfdi:Concepto']['@Descripcion'];
					if(!$descripcion)
						$descripcion = $json['Comprobante']['cfdi:Conceptos']['cfdi:Concepto'][0]['@Descripcion'];
					$metodoPago = $json['Comprobante']['@FormaPago'];
				}

				if(strpos($d->xml,'facturas'))
				{
					$xmlnopath = explode('/',$d->xml);
					$d->xml = $xmlnopath[3];
				}
				$url = $this->path()."xmls/facturas/temporales/";

				$listaTemporales .= "<tr>
						<td width='50'><img src='xmls/imgs/xml.jpg' width=30></td>
						<td width='300'><b>".$d->folio."</b> ".$razon."</td>
						<td width=300'><b>".$descripcion."</b></td>
						<td width='60'><center>".$d->tipo."</center></td>
						<td align='center' width='200'><b style='color:red'>".number_format($d->importe,2,'.',',')."</b></td><td></td>
						<td width='200'><b>".$metodoPago."</b></td>
						<td width='200'><b>".$d->fecha."</b></td>
						<td width='50'><a href='index.php?c=factura&f=visor_factura&uuid=$d->uuid' target='_blank'>Ver</a></td>
						<td width='50' style='text-align:center;'><input title='Sólo copiar' type='radio' name='radio-$cont' id='copiar-$cont' value='$url".htmlspecialchars($d->xml)."' class='copiar'></td>
						</tr>";
				$cont++;	
			}
		}
		if($cont)
			echo $listaTemporales;
		else
			echo "No se encontraron registros";
	}

	
}
?>