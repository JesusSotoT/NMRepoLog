<?php
//Carga la funciones comunes top y footer
//require('controllers/common.php');
require('controllers/captpolizas.php');
//Carga el modelo para este controlador
require("models/nomina.php");

class Nomina extends CaptPolizas
{
	public $NominaModel;
	public $CaptpolizasModel;
	function __construct()
	{
		$this->NominaModel = new NominaModel();
		$this->CaptPolizasModel = $this->NominaModel;
		$this->NominaModel->connect();
	}
	function __destruct()
	{
		$this->NominaModel->close();
	}
	
	function viewNomina(){
		$Exercise = $this->NominaModel->getExerciseInfo();
		$Ex = $Exercise->fetch_assoc();
		$todas_facturas = $Ex['TodasFacturas'];
		$ListaSegmentos = $this->NominaModel->ListaSegmentos();
		$ListaSucursales = $this->NominaModel->ListaSucursales();
		$Suc = $this->NominaModel->getSegmentoInfo();
		$firstExercise = $this->NominaModel->getFirstLastExercise(0);
		$lastExercise = $this->NominaModel->getFirstLastExercise(1);
		$afectables = $this->CaptPolizasModel->getAccounts("manual_code",0);//todas las afectables para los concepto d Otros
		$cuentasconf = $this->NominaModel->cuentasConfig();
		$sueldo = $this->CaptPolizasModel->buscacuenta($cuentasconf['CuentaSueldoxPagar']);
		$listaSueldo = $this->NominaModel->pasivoCirculante();
		$listaPercepcion = $this->NominaModel->percepcionesDeducciones("nomi_percepciones");
		$percepcionesarray = array();
		while ($p=$listaPercepcion->fetch_assoc()){
			$percepcionesarray[$p['clave']]=$this->NominaModel->cuentaNomina("nomi_percepciones", $p['clave']);
		}
		$listaDeduccion = $this->NominaModel->percepcionesDeducciones("nomi_deducciones");
		$deduccionesarray = array();
		while ($d=$listaDeduccion->fetch_assoc()){
			$deduccionesarray[$d['clave']]=$this->NominaModel->cuentaNomina("nomi_deducciones", $d['clave']);
		}
		$listaOtrosp = $this->NominaModel->percepcionesDeducciones("nomi_otros_pagos");
		$otrosarray = array();
		while ($d=$listaOtrosp->fetch_assoc()){
			$otrosarray[$d['clave']]=$this->NominaModel->cuentaNomina("nomi_otros_pagos", $d['clave']);
		}
		if(intval($Ex['IdEx']) AND intval($Suc))// Si existe el ejercicio
		{
		 	$Abonos = $this->NominaModel->GetAbonosCargos('Abono','Periodo',$Ex['PeriodoActual'],$Ex['IdEx']);
			$Cargos = $this->NominaModel->GetAbonosCargos('Cargo','Periodo',$Ex['PeriodoActual'],$Ex['IdEx']);
			include("views/nominas/nominas.php");
		}
		else
		{
			echo "<script>alert('Antes de capturar polizas configure un ejercicio y tambien Segmentos de Negocio.');window.parent.agregatab('../../modulos/cont/index.php?c=Config','Configuración Ejercicios','',142);window.parent.agregatab('../catalog/gestor.php?idestructura=251&ticket=testing','Segmentos de Negocio','',1656)</script>";
		}
			//
		
		
	}
	function visualizaXML(){
		$error=false;
		global $xp;
		$facturasNoValidas = $facturasValidas = '';
		$numeroInvalidos = $numeroValidos = $no_hay_problema = $noOrganizacion = 0;
		$maximo = count($_FILES['xml']['name']);
		$maximo = (intval($maximo)-1);
		for($i = 0; $i <= $maximo; $i++){
			if($_FILES["xml"]["size"][$i] > 0){
				$data = array();
				$file 	= $_FILES['xml']['tmp_name'][$i];
				$texto 	= file_get_contents($file);
				$texto 	= preg_replace('{<Addenda.*/Addenda>}is', '<Addenda/>', $texto);
				$texto 	= preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '<cfdi:Addenda/>', $texto);
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
					$data['uuid'] = 	$this->getpath("//@UUID");
					if(is_array($data['uuid'])){
						$data['uuid'] = $data['uuid'][1];
					}
					
					$data['folio'] 	= $this->getpath("//@Folio");
					$data['nombre'] = $this->getpath("//@Nombre");
					$data['fecha'] = $this->getpath("//@FechaTimbrado");
					$data['total'] = $this->getpath("//@Total");//el total sera el monto complementario
					$data['rfc'] = $this->getpath("//@Rfc");
				}else{
					$data['uuid'] 	= $this->getpath("//@UUID");
					$data['folio'] 	= $this->getpath("//@folio");
					$data['nombre'] = $this->getpath("//@nombre");
					$data['version'] = $this->getpath("//@version");
					$data['fecha'] = $this->getpath("//@FechaTimbrado");
					$version = $data['version'];
					$data['total'] = $this->getpath("//@total");//el total sera el monto complementario
					$data['rfc'] = $this->getpath("//@rfc");
					
					
				}
				$rfcOrganizacion= $this->NominaModel->rfcOrganizacion();
				if($data['rfc'][0] == $rfcOrganizacion['RFC'])
				{
					$tipoDeComprobante = "Ingreso";$nombre = $data['nombre'][1];
				}
				elseif($data['rfc'][1] == $rfcOrganizacion['RFC'])
				{
					$tipoDeComprobante = "Egreso";	$nombre = $data['nombre'][0];
					
				}
				if($this->valida_xsd($version[0],$xml) && $_FILES['xml']['type'][$i] == "text/xml")
				{
					if($version[0] == '3.2'){
						$no_hay_problema = $this->valida_en_sat($data['rfc'][0],$data['rfc'][1],$data['total'],$data['uuid']);
					}else{$no_hay_problema = 1;}
					if($rfcOrganizacion['RFC'] != $data['rfc'][0] &&  $rfcOrganizacion['RFC']!= $data['rfc'][1]){
						$noOrganizacion = 0;
						$numeroInvalidos++;
						$facturasNoValidas .= $_FILES['xml']['name'][$i]."(RFC no de Organizacion),\\n";
					}else{ $noOrganizacion = 1; }
					$nombreArchivo = $data['folio']."_".$nombre."_".$data['uuid'].".xml";
					if($noOrganizacion){
						$validaexiste = $this->existeXML($nombreArchivo);
						if($validaexiste){
							$noOrganizacion = 0;
							$numeroInvalidos++;
							$facturasNoValidas .= $_FILES['xml']['name'][$i].",Ya existe en $validaexiste.\\n";
						}else{ $noOrganizacion = 1; }
					}
					
					if($noOrganizacion){
						if($no_hay_problema){
							$fec = explode("T", $data['fecha']);
							
								if($_REQUEST['fecha']){
									$_SESSION['fechanomina']=$_REQUEST['fecha'];
								}else{
									$_SESSION['fechanomina']=$fec[0]."";
								}

								if($_REQUEST['concepto']){
									$_SESSION['conceptopoli']=$_REQUEST['concepto'];
								}

							
							$xmlreal = $this->quitar_tildes($data['folio']."_".$nombre."_".$data['uuid'].".xml");
							$xmld=$this->quitar_tildes($data['folio']."-Nomina"."_".$nombre."_".$data['uuid'].".xml");
							$data['xml']=$xmld;
							$data['xmlreal']=$xmlreal;
							$percepciones = $deducciones = $otrospagos = array();
// 							
							$xml = simplexml_load_file($_FILES['xml']['tmp_name'][$i]);
							$ns = $xml -> getNamespaces(true);
							$xml -> registerXPathNamespace('c', $ns['cfdi']);//altera los prefijos del namespace
							$xml -> registerXPathNamespace('t', $ns['tfd']);
							$xml -> registerXPathNamespace('n12',$ns['nomina12']);
							
							/*agrege esto paa leer todo el nodo de nomina
							 * porque suele venir en algunos xml la direccion de nomina 
							 * xmlns:nomina="http://www.sat.gob.mx/nomina"
							 * y si la contiene no deja leer el contenido
							 * asi que mejor use prefijo*/
							$xml -> registerXPathNamespace('n',$ns['nomina']);
							
							$cont=0;
							foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//n:Nomina//n:Percepciones//n:Percepcion') as $percepcion){
								if($percepcion['ImporteGravado']!=0 || $percepcion['ImporteExento']!=0){ 
									//$cuenta = $this->NominaModel->cuentaNomina("nomi_percepciones", $percepcion['TipoPercepcion']);
									$percepciones[$cont]['cuenta'] 	= $percepcion['TipoPercepcion']."";
									$percepciones[$cont]['concepto']	= $percepcion['Concepto'].""; 
									$percepciones[$cont]['importe']	= number_format(floatval($percepcion['ImporteGravado']),2,'.','') + number_format(floatval($percepcion['ImporteExento']),2,'.','');
									$cont++;
								}
							}
							$cont=0;	
							foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//n:Nomina//n:Deducciones//n:Deduccion') as $deduccion){
								if($deduccion['ImporteGravado']!=0 || $deduccion['ImporteExento']!=0){ 
									//$cuenta = $this->NominaModel->cuentaNomina("nomi_deducciones", $deduccion['TipoDeduccion']);
									$deducciones[$cont]['cuenta'] 	= $deduccion['TipoDeduccion']."";
									$deducciones[$cont]['concepto']	= $deduccion['Concepto']."";
									$deducciones[$cont]['importe']	= number_format(floatval($deduccion['ImporteGravado']),2,'.','') + number_format(floatval($deduccion['ImporteExento']),2,'.','');
									$cont++;
								}
							}
						
				// complemento 1.2 de nomina
						
						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//n12:Nomina//n12:Percepciones//n12:Percepcion') as $percepcion){
								if($percepcion['ImporteGravado']!=0 || $percepcion['ImporteExento']!=0){ 
									//$cuenta = $this->NominaModel->cuentaNomina("nomi_percepciones", $percepcion['TipoPercepcion']);
									$percepciones[$cont]['cuenta'] 	= $percepcion['TipoPercepcion']."";
									$percepciones[$cont]['concepto']	= $percepcion['Concepto'].""; 
									$percepciones[$cont]['importe']	= number_format(floatval($percepcion['ImporteGravado']),2,'.','') + number_format(floatval($percepcion['ImporteExento']),2,'.','');
									$cont++;
								}
							}
					/* OTROS PAGO SubsidioAlEmpleo  
					 * Se manejan como percepciones y solo hay un tipo SubsidioCausado
					 * foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//n12:Nomina//n12:OtrosPagos//n12:OtroPago//n12:SubsidioAlEmpleo') as $percepcion){
					 * por el momento se deja como si fuera solo subsidiocausado 
					 * exite uno de compensacion pero seria ver como lo maneja*/
						
						$cont=0;	
						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//n12:Nomina//n12:OtrosPagos//n12:OtroPago') as $otro){
								if($otro['Importe']!=0){ 
									$otrospagos[$cont]['cuenta'] 	= $otro['TipoOtroPago']."";
									$otrospagos[$cont]['concepto']	= $otro['Concepto'].""; 
									$otrospagos[$cont]['importe']	= number_format(floatval($otro['Importe']),2,'.','');
									$cont++;
								}
							}
					/* FIN OTROS PAGO SubsidioAlEmpleo  */	
						
						
						
							$cont=0;	
							foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//n12:Nomina//n12:Deducciones//n12:Deduccion') as $deduccion){
								if($deduccion['Importe']!=0){ 
									//$cuenta = $this->NominaModel->cuentaNomina("nomi_deducciones", $deduccion['TipoDeduccion']);
									$deducciones[$cont]['cuenta'] 	= $deduccion['TipoDeduccion']."";
									$deducciones[$cont]['concepto']	= $deduccion['Concepto']."";
									$deducciones[$cont]['importe']	= number_format(floatval($deduccion['Importe']),2,'.','');
									$cont++;
								}
							}
						// fin complemento 1.2

						
							$data['ordenador']=1;
							$data['percepciones']  = $percepciones;
							$data['deducciones'] = $deducciones;
							$data['otrospagos'] = $otrospagos;
							if(move_uploaded_file($_FILES['xml']['tmp_name'][$i],'xmls/facturas/temporales/'.($xmlreal))){
								$_SESSION['provisionNomina'][]=$data;
							}else{
								$errorSubir=true;
								$this->CaptPolizasModel->transaccionController('Error al subir archivo Nomina','' );
								
							}
								
							//move_uploaded_file($_FILES['xml']['tmp_name'][$i],'xmls/facturas/temporales/'.($xmld));
							$numeroValidos++;
						}else{
							$numeroInvalidos++;
							$facturasNoValidas .= $_FILES['xml']['name'][$i]."(Cancelada),\\n";
						}
					}
					
				}//if valida_xsd
				else{
					$numeroInvalidos++;
					$facturasNoValidas .= $_FILES['xml']['name'][$i]."(Estructura invalida),\\n";
				}
			
			}//if size
		}//for
		$cadena = "";
		if($errorSubir){
			$cadena = "alert('Error al Subir archivos intente de nuevo.');";
		}else{
			if ($numeroInvalidos!=0){
				$cadena = 'alert("Facturas no validas:\n'.$facturasNoValidas.'PARA AGREGAR FACTURAS EXISTENTES REALIZARLO DESDE ALMACEN");';
			}else{
				$cadena = "alert('Facturas validas');";
			}
		}
	echo '<script>'.$cadena.' window.location="index.php?c=Nomina&f=viewNomina"; </script>';
	
	}
	function visualizaXMLalmacen(){
		$maximo = count($_POST['xml']);
		$maximo = (intval($maximo)-1);
		for($i=0;$i<=$maximo;$i++){
			$percepciones = $deducciones = $otrospagos = array();
			$data = array();	
						
			$xml = simplexml_load_file($_POST['xml'][$i]);
			$ns = $xml ->getNamespaces(true);
			
			$xml -> registerXPathNamespace('c', $ns['cfdi']);//altera los prefijos del namespace
			$xml -> registerXPathNamespace('t', $ns['tfd']);
			$xml -> registerXPathNamespace('n',$ns['nomina']);
			$xml -> registerXPathNamespace('n12',$ns['nomina12']);
			
			foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){ 
			    $fecha= $cfdiComprobante['fecha']; 
			    $total=$cfdiComprobante['total'];
			 }
			if(!$total){
				foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){ 
				    $fecha= $cfdiComprobante['Fecha']; 
				    $total=$cfdiComprobante['Total'];
				 }
			}
			$cont=0;
			foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//n:Nomina//n:Percepciones//n:Percepcion') as $percepcion){
				if($percepcion['ImporteGravado']!=0 || $percepcion['ImporteExento']!=0){ 
					//$cuenta = $this->NominaModel->cuentaNomina("nomi_percepciones", $percepcion['TipoPercepcion']);
					$percepciones[$cont]['cuenta'] 	= $percepcion['TipoPercepcion']."";
					$percepciones[$cont]['concepto']	= $percepcion['Concepto'].""; 
					$percepciones[$cont]['importe']	= number_format(floatval($percepcion['ImporteGravado']),2,'.','') + number_format(floatval($percepcion['ImporteExento']),2,'.','');
					$cont++;
				}
			}
			$cont=0;	
			foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//n:Nomina//n:Deducciones//n:Deduccion') as $deduccion){
				if($deduccion['ImporteGravado']!=0 || $deduccion['ImporteExento']!=0){ 
					//$cuenta = $this->NominaModel->cuentaNomina("nomi_deducciones", $deduccion['TipoDeduccion']);
					$deducciones[$cont]['cuenta'] 	= $deduccion['TipoDeduccion']."";
					$deducciones[$cont]['concepto']	= $deduccion['Concepto']."";
					$deducciones[$cont]['importe']	= number_format(floatval($deduccion['ImporteGravado']),2,'.','') + number_format(floatval($deduccion['ImporteExento']),2,'.','');
					$cont++;
				}
			}

				// complemento 1.2 de nomina
						
						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//n12:Nomina//n12:Percepciones//n12:Percepcion') as $percepcion){
								if($percepcion['ImporteGravado']!=0 || $percepcion['ImporteExento']!=0){ 
									//$cuenta = $this->NominaModel->cuentaNomina("nomi_percepciones", $percepcion['TipoPercepcion']);
									$percepciones[$cont]['cuenta'] 	= $percepcion['TipoPercepcion']."";
									$percepciones[$cont]['concepto']	= $percepcion['Concepto'].""; 
									$percepciones[$cont]['importe']	= number_format(floatval($percepcion['ImporteGravado']),2,'.','') + number_format(floatval($percepcion['ImporteExento']),2,'.','');
									$cont++;
								}
							}
						/* OTROS PAGO SubsidioAlEmpleo  
					 * Se manejan como percepciones y solo hay un tipo SubsidioCausado
					 * foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//n12:Nomina//n12:OtrosPagos//n12:OtroPago//n12:SubsidioAlEmpleo') as $percepcion){
					 * por el momento se deja como si fuera solo subsidiocausado 
					 * exite uno de compensacion pero seria ver como lo maneja*/
						$cont=0;	
						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//n12:Nomina//n12:OtrosPagos//n12:OtroPago') as $otro){
								if($otro['Importe']!=0){ 
									$otrospagos[$cont]['cuenta'] 	= $otro['TipoOtroPago']."";
									$otrospagos[$cont]['concepto']	= $otro['Concepto'].""; 
									$otrospagos[$cont]['importe']	= number_format(floatval($otro['Importe']),2,'.','');
									$cont++;
								}
							}
					/* FIN OTROS PAGO SubsidioAlEmpleo  */	
						
					
							$cont=0;	
							foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//n12:Nomina//n12:Deducciones//n12:Deduccion') as $deduccion){
								if($deduccion['Importe']!=0){ 
									//$cuenta = $this->NominaModel->cuentaNomina("nomi_deducciones", $deduccion['TipoDeduccion']);
									$deducciones[$cont]['cuenta'] 	= $deduccion['TipoDeduccion']."";
									$deducciones[$cont]['concepto']	= $deduccion['Concepto']."";
									$deducciones[$cont]['importe']	= number_format(floatval($deduccion['Importe']),2,'.','');
									$cont++;
								}
							}
						// fin complemento 1.2


			$archivopar = explode("/",$_POST['xml'][$i]);
			$xmlreal = $archivopar[3];
			$archivopar[3] = str_replace("-Cobro", "", $archivopar[3]);
			$archivopar[3] = str_replace("-Pago", "", $archivopar[3]);
			$archivopar[3] = str_replace("-Nomina", "", $archivopar[3]);
			$separa = explode("_",$archivopar[3]);
			
			$xmld=$this->quitar_tildes($separa[0]."-Nomina_".$separa[1]."_".$separa[2]);
			$data['uuid'] = str_replace(".xml", "",$separa[2]);
			$data['total'] = number_format(floatval($total),2,'.','');
			$data['xml'] = $xmld;
			$data['xmlreal'] = $xmlreal;
			$data['nombre'][1] = $separa[1];
			$data['percepciones']  = $percepciones;
			$data['deducciones'] = $deducciones;
			$data['otrospagos'] = $otrospagos;
			$_SESSION['provisionNomina'][] = $data;
			
			$fec = explode("T", $fecha);
			if($_REQUEST['fecha']){
				$_SESSION['fechanomina']=$_REQUEST['fecha'];
			}else{
				$_SESSION['fechanomina']=$fec[0]."";
			}

			if($_REQUEST['concepto']){
				$_SESSION['conceptopoli']=$_REQUEST['concepto'];
			}
			//rename($_POST['xml'][$i],'xmls/facturas/temporales/'.($xmld));
		}
	}
	function cancelaProvision(){
		foreach($_SESSION['provisionNomina'] as $data){
			if($data['ordenador']){
				unlink("xmls/facturas/temporales/".$data['xml']);
			}
		}
		unset($_SESSION['provisionNomina']);
		unset($_SESSION['fechanomina']);
		unset($_SESSION['conceptopoli']);
	}
	function cuentaValorNomina(){
		if($_REQUEST['cuentasaldo']){
			$_SESSION[$_REQUEST['array']][$_REQUEST['cont']]['cuentasaldo']=$_REQUEST['cuentasaldo'];
		}
		if($_REQUEST['segmento']){
			$_SESSION[$_REQUEST['array']][$_REQUEST['cont']]['segmento']=$_REQUEST['segmento'];
		}
		if($_REQUEST['sucursal']){
			$_SESSION[$_REQUEST['array']][$_REQUEST['cont']]['sucursal']=$_REQUEST['sucursal'];
		}
		if($_REQUEST['banco']){
			$_SESSION[$_REQUEST['array']][$_REQUEST['cont']]['banco']=$_REQUEST['banco'];
		}
		if($_REQUEST['cuentapercepciones']){
			$_SESSION[$_REQUEST['array']][$_REQUEST['cont']]['cuentapercepciones']=$_REQUEST['cuentapercepciones'];
		}
		if($_REQUEST['cuentadeducciones']){
			$_SESSION[$_REQUEST['array']][$_REQUEST['cont']]['cuentadeducciones']=$_REQUEST['cuentadeducciones'];
		}
	}
	
	function creaProvisionNomina(){
		$error=false;$fecha = $_REQUEST['fecha'];$carpeta=false;
		$cuentasconf = $this->NominaModel->cuentasConfig();
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
		
		$conceptopoliza=$_REQUEST['conceptopoliza'];
		if(!$conceptopoliza){ $conceptopoliza="Provision Nomina"; }
		
		$poli = $this->CaptPolizasModel->savePoliza2($idorg,$idejer,$idperio,3,$conceptopoliza,$fecha,0,"","",0,"",0,2);
		if($poli == 0){
			$numPoliza = $this->CaptPolizasModel->getLastNumPoliza();
			if( mkdir("xmls/facturas/".$numPoliza['id'],  0777)){
				$carpeta = true;
			}
		}
		$listaPercepcion = $this->NominaModel->percepcionesDeducciones("nomi_percepciones");
		$percepcionesarray = array();
		while ($p=$listaPercepcion->fetch_assoc()){
			$percepcionesarray[$p['clave']]=$this->NominaModel->cuentaNomina("nomi_percepciones", $p['clave']);
		}
		$listaDeduccion = $this->NominaModel->percepcionesDeducciones("nomi_deducciones");
		$deduccionesarray = array();
		while ($d=$listaDeduccion->fetch_assoc()){
			$deduccionesarray[$d['clave']]=$this->NominaModel->cuentaNomina("nomi_deducciones", $d['clave']);
		}
		$listaOtrosp = $this->NominaModel->percepcionesDeducciones("nomi_otros_pagos");
		$otrosarray = array();
		while ($d=$listaOtrosp->fetch_assoc()){
			$otrosarray[$d['clave']]=$this->NominaModel->cuentaNomina("nomi_otros_pagos", $d['clave']);
		}
		$numov = 1;			
		$abono=true;$cargo=true;
		foreach($_SESSION['provisionNomina'] as $data){
			$segmento = $data['segmento'];
			$sucursal = $data['sucursal'];
			$xml = $data['xml'];
			$xmlreal = $data['xmlreal'];
			$referencia = $data['uuid'];
	 		foreach ($data['percepciones'] as $percepcion){
	 			if($abono==true){
		 			$perce = $percepcionesarray[$percepcion['cuenta']];
		 			$clave = explode("//",$perce);
					if($percepcion['cuenta']!="016"){
						$cuenta = $clave[2];
						$abono = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$cuenta,"Cargo",$percepcion['importe'],$percepcion['concepto'],'3-',$xml,"$referencia",0);
						$numov++;
					}else{
						$cuenta = $data['cuentapercepciones'];
						$abono = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$cuenta,"Cargo",$percepcion['importe'],$percepcion['concepto'],'3-',$xml,"$referencia",0);
						$numov++;
					}
				}else{
					$error==true;
				}
			}
			foreach ($data['otrospagos'] as $percepcion){
	 			if($abono==true){
		 			$perce = $otrosarray[$percepcion['cuenta']];
		 			$clave = explode("//",$perce);
					if($percepcion['cuenta']!="016"){
						$cuenta = $clave[2];
						$abono = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$cuenta,"Cargo",$percepcion['importe'],$percepcion['concepto'],'3-',$xml,"$referencia",0);
						$numov++;
					}//else{
						// $cuenta = $data['cuentapercepciones'];
						// $abono = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$cuenta,"Cargo",$percepcion['importe'],$percepcion['concepto'],'3-',$xml,"$referencia",0);
						// $numov++;
					// }
				}else{
					$error==true;
				}
			}
			if($error==false){
				foreach ($data['deducciones'] as $deduccion){
					if($cargo==true){
						$dedu = $deduccionesarray[$deduccion['cuenta']];
			 			$clave = explode("//",$dedu);
						if($deduccion['cuenta']!="004"){
			 				$cuenta = $clave[2];
							$cargo = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$cuenta,"Abono",$deduccion['importe'],$deduccion['concepto'],'3-',$xml,"$referencia",0);
							$numov++;
						}else{
							$cuenta = $data['cuentadeducciones'];
							$cargo = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$cuenta,"Abono",$deduccion['importe'],$deduccion['concepto'],'3-',$xml,"$referencia",0);
							$numov++;
						}
					}else{
						$error=true;
					}
					
				}
			}
			if($cuentasconf['statuSueldoxPagar']==1){
				$cuenta=$cuentasconf['CuentaSueldoxPagar'];
			}else{
				$cuenta = $data['cuentasaldo'];
			}
			if($error==false){
				$cargo = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$cuenta,"Abono",$data['total'],"$conceptopoliza",'1-',$xml,"$referencia",0);
				$nombre = $data['nombre'][1];
				
				if($cargo==true){
					$numov++;
					copy("xmls/facturas/temporales/".$xmlreal, "xmls/facturas/".$numPoliza['id']."/".$xml);
					rename("xmls/facturas/temporales/".$xmlreal, "xmls/facturas/temporales/".$xml);
				}else{
					$error==true;
				}
			}
			
		}
			if($error==false){
				$this->NominaModel->updateConceptoPoliza($conceptopoliza." ".$nombre, $numPoliza['id']);
				unset($_SESSION['provisionNomina']);
				unset($_SESSION['fechanomina']);
				unset($_SESSION['conceptopoli']);
				echo 1;
			}else{
				echo 0;
			}
		
		
	}
	function actualizaListaSueldo(){
		$listaSueldo = $this->NominaModel->pasivoCirculante();
		while($egresos=$listaSueldo->fetch_array()){ 
			echo "<option value=$egresos[account_id]>$egresos[description](".$egresos['manual_code'].")</option>";
		 }
	}
	function viewPagoNomina(){
		$Exercise = $this->NominaModel->getExerciseInfo();
		$Ex = $Exercise->fetch_assoc();
		$ListaSegmentos = $this->NominaModel->ListaSegmentos();
		$ListaSucursales = $this->NominaModel->ListaSucursales();
		$Suc = $this->NominaModel->getSegmentoInfo();
		$firstExercise = $this->NominaModel->getFirstLastExercise(0);
		$lastExercise = $this->NominaModel->getFirstLastExercise(1);
		$cuentasconf = $this->NominaModel->cuentasConfig();
		$sueldo = $this->CaptPolizasModel->buscacuenta($cuentasconf['CuentaSueldoxPagar']);
		$listacuentasbancarias = $this->NominaModel->cuentasbancariaslista();
		$bancosArbol = $this->NominaModel->cuentasbancariaslista();
		$listaEmpleados = $this->NominaModel->empleadosRegistrados();
		$listabancos = $this->CaptPolizasModel->listabancos();//IBM
		$listaSueldo = $this->NominaModel->pasivoCirculante();
		$forma_pago=$this->CaptPolizasModel->formapago();
			
		if(intval($Ex['IdEx']) AND intval($Suc))// Si existe el ejercicio
		{
		 	$Abonos = $this->NominaModel->GetAbonosCargos('Abono','Periodo',$Ex['PeriodoActual'],$Ex['IdEx']);
			$Cargos = $this->NominaModel->GetAbonosCargos('Cargo','Periodo',$Ex['PeriodoActual'],$Ex['IdEx']);
			include("views/nominas/pagonomina.php");
		}
		else
		{
			echo "<script>alert('Antes de capturar polizas configure un ejercicio y tambien Segmentos de Negocio.');window.parent.agregatab('../../modulos/cont/index.php?c=Config','Configuración Ejercicios','',142);window.parent.agregatab('../catalog/gestor.php?idestructura=251&ticket=testing','Segmentos de Negocio','',1656)</script>";
		}
	}
	function datosEmpleado(){
		$datosE = $this->NominaModel->datosEmpleado($_REQUEST['idEmpleado']);
		echo $datosE['rfc']."//".$datosE['numeroCuenta']."//".$datosE['idbanco'];
	}
	function visualizaPagoXML(){
		global $xp;
		$maximo = count($_POST['xml']);
		$maximo = (intval($maximo)-1);
		for($i=0;$i<=$maximo;$i++){
			$data = array();
			$file 	= "xmls/facturas/temporales/".$_POST['xml'][$i];
			$texto 	= file_get_contents($file);
			$texto 	= preg_replace('{<Addenda.*/Addenda>}is', '<Addenda/>', $texto);
			$texto 	= preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '<cfdi:Addenda/>', $texto);
			$xml 	= new DOMDocument();
			$xml->loadXML($texto);
			
			$xp = new DOMXpath($xml);
			$data['uuid'] 	= $this->getpath("//@UUID");
			$data['folio'] 	= $this->getpath("//@folio");
			$data['nombre'] = $this->getpath("//@nombre");
			$data['fecha'] = $this->getpath("//@FechaTimbrado");
			$data['total'] = $this->getpath("//@total");//el total sera el monto complementario
			$data['rfc'] = $this->getpath("//@rfc");
			$data['xml'] = $_POST['xml'][$i];
			
			$_SESSION['pagonomina'][] = $data;	
			$fec = explode("T", $data['fecha']);
			if($_REQUEST['fecha']){
				$_SESSION['fechapago']=$_REQUEST['fecha'];
			}else{
				$_SESSION['fechapago']=$fec[0]."";
			}

			$_SESSION['datospoliza'][0] = $_REQUEST['formapago'];
			$_SESSION['datospoliza'][1] = $_REQUEST['numero'];
			$_SESSION['datospoliza'][2] = $_REQUEST['listabancoorigen'];
			$_SESSION['datospoliza'][3] = $_REQUEST['numorigen'];
			$_SESSION['datospoliza'][4] = $_REQUEST['beneficiario'];
			$_SESSION['datospoliza'][5] = $_REQUEST['rfc'];
			$_SESSION['datospoliza'][6] = $_REQUEST['listabanco'];
			$_SESSION['datospoliza'][7] = $_REQUEST['numtarje'];
			$_SESSION['datospoliza'][8] = $_REQUEST['concepto'];
			
			
			echo '<script> window.location="index.php?c=Nomina&f=viewPagoNomina"; </script>';
			
		}
	}
	function cancelaPago(){
		unset($_SESSION['pagonomina']);
		unset($_SESSION['fechapago']);
		unset($_SESSION['datospoliza']);
	}
	function actualizaListaBanco(){
		$bancosArbol = $this->NominaModel->cuentasbancariaslista();
		while($b=$bancosArbol->fetch_array()){
			echo "<option value=$b[account_id]>$b[description](".$b['manual_code'].")</option>";
		}
	}
	function creaPagoNomina(){
		$error=false;$fecha = $_REQUEST['fecha'];$carpeta=false;
		$cuentasconf = $this->NominaModel->cuentasConfig();
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
		
		$conceptopoliza=$_REQUEST['concepto'];
		if(!$conceptopoliza){ $conceptopoliza="Pago Nomina"; }
		
		$poli=$this->CaptPolizasModel->savePoliza2($idorg,$idejer,$idperio,2,$conceptopoliza,$fecha,$_REQUEST['beneficiario'],$_REQUEST['numero'],$_REQUEST['rfc'],$_REQUEST['listabanco'],$_REQUEST['numtarje'],$_REQUEST['listabancoorigen'],2);
		if($poli == 0){
			$numPoliza = $this->CaptPolizasModel->getLastNumPoliza();
			if( mkdir("xmls/facturas/".$numPoliza['id'],  0777)){
				$carpeta = true;
			}
		}
		$numov = 1;
		foreach($_SESSION['pagonomina'] as $data){
			$segmento = $data['segmento'];
			$sucursal = $data['sucursal'];
			$xml = $data['xml'];
			$referencia = $data['uuid'];
			if($cuentasconf['statuSueldoxPagar']==1){
				$cuenta=$cuentasconf['CuentaSueldoxPagar'];
			}else{
				$cuenta = $data['cuentasaldo'];
			}
			$cargo = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$cuenta,"Cargo",number_format(floatval($data['total']),2,'.',''),"$conceptopoliza",'3-'.$_REQUEST['beneficiario'],$xml,"$referencia",$_REQUEST['formapago']);
			if($cargo==true){
				$numov ++;
				$abono = $this->CaptPolizasModel->InsertMov2($numPoliza['id'],$numov,$segmento,$sucursal,$data['banco'],"Abono",number_format(floatval($data['total']),2,'.',''),"$conceptopoliza",'3-'.$_REQUEST['beneficiario'],$xml,"$referencia",$_REQUEST['formapago']);
			}else{
				$error=true;
			}
			if($abono==true){
				if($carpeta==true){
					rename("xmls/facturas/temporales/".$data['xml'], "xmls/facturas/".$numPoliza['id']."/".$data['xml']);
				}
				$numov++;
				$nombre = $data['nombre'][1];
			}else{
				$error=true;
			}
			
		}
		if($error==false){
			$this->NominaModel->updateConceptoPoliza($conceptopoliza." ".$nombre, $numPoliza['id']);
			unset($_SESSION['pagonomina']);
			unset($_SESSION['fechapago']);
			unset($_SESSION['datospoliza']);
		}
	
	}
	function ActualizaListaEmppleado(){
		$listaEmpleados = $this->NominaModel->empleadosRegistrados();
		while($e = $listaEmpleados->fetch_assoc()){
			echo "<option value=$e[idEmpleado]>$e[nombreEmpleado] ".$e['apellidoPaterno']." ".$e['apellidoMaterno']."(".$e['codigo'].") </option>";
		} 
	}




}
?>