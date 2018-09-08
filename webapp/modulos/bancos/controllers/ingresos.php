<?php
   require('controllers/cheques.php');

//Carga el modelo para este controlador
require("models/ingresos.php");

class Ingresos extends Cheques
{
	public $IngresosModel;
	public $ChequesModel;
	function __construct()
	{
		
		$this->IngresosModel = new IngresosModel();
		$this->ChequesModel = $this->IngresosModel;
		$this->IngresosModel->connect();
	}

	function __destruct()
	{
		$this->IngresosModel->close();
	}
	function filtro(){
		switch($_REQUEST['fun']){
	case 'verIngreso':
		if($_REQUEST['cancela']>0){
			//if($_SESSION['ingresonew']){
				$ok = $this->ChequesModel->eliminaDocumento($_REQUEST['cancela']);
				if($ok == 1){
					unset($_SESSION['ingresonew']);
				}unset($_SESSION['ingresonew']);
			//}
		}
		
	break;
	case 'verIngresoNodep':
		if($_REQUEST['cancela']>0){
			//if(isset($_SESSION['proyectadonew'])){
				$ok = $this->ChequesModel->eliminaDocumento($_REQUEST['cancela']);
				if($ok == 1){
					unset($_SESSION['proyectadonew']);
				}unset($_SESSION['proyectadonew']);
			//}
		}
	break;
	case 'verDeposito':
		if($_REQUEST['cancela']>0){
			//if(isset($_SESSION['depositonew'])){
				$ok = $this->ChequesModel->eliminaDocumento($_REQUEST['cancela']);
				if($ok == 1){
					unset($_SESSION['depositonew']);
				}unset($_SESSION['depositonew']);
			//}
		}
	break;
	case 'verEgresos':
		if($_REQUEST['cancela']>0){
			//if(isset($_SESSION['egresonew'])){
				$ok = $this->ChequesModel->eliminaDocumento($_REQUEST['cancela']);
				if($ok == 1){
					unset($_SESSION['egresonew']);
				}
				unset($_SESSION['egresonew']);
			//}
		}
	break;
	case 'vercheque':
		if($_REQUEST['cancela']>0){
			//if(isset($_SESSION['newcheque'])){
				$ok = $this->ChequesModel->eliminaDocumento($_REQUEST['cancela']);
				if($ok == 1){
					unset($_SESSION['newcheque']);
				}unset($_SESSION['newcheque']);
			//}
		}
	break;
}
		require('views/documentos/filtro.php');
		
	}
	function verIngreso(){
		$formapago  = $this->ChequesModel->formapago();
		$segmento	= $this->ChequesModel->segmento();
		$sucursal	= $this->ChequesModel->sucursal();
		$acontia = $this->ChequesModel->validaAcontia();
		$appministra = $this->ChequesModel->validaAppministra();
		$info = $this->ChequesModel->infoConfiguracion();
		$cuentasbancarias = $this->ChequesModel->cuentasbancariaslista();
		$moneda = $this->ChequesModel->moneda();
		$clientes = $this->IngresosModel->catalogoCliente();
		$proveedores = $this->IngresosModel->proveedorBeneficiario();
		$cuentasAfectables = $this->IngresosModel->cuentaClientes();
		$listaconceptos = $this->ChequesModel->concepto(1);
		$clasificador = $this->IngresosModel->clasificadorIngre();
		$clasificadorsub = $this->IngresosModel->clasificadorIngre();
		$tipodocumento = $this->IngresosModel->tipodocumento(2);//2 ingre
		$empleados = $this->ChequesModel->empleados();
		$cuentasAsiganacion = $this->ChequesModel->configCuentas();
		if($info['RFC']==""){
			$Exercise = $this->ChequesModel->getExerciseInfo();
			$Ex = $Exercise->fetch_assoc();
			$firstExercise = $this->ChequesModel->getFirstLastExercise(0,'cont');
			$lastExercise = $this->ChequesModel->getFirstLastExercise(1,'cont');
		}else{
			$firstExercise = $this->ChequesModel->getFirstLastExercise(0,'bco');
			$lastExercise = $this->ChequesModel->getFirstLastExercise(1,'bco');
		}
		//$firstExercise = $this->ChequesModel->getFirstLastExercise(0,'cont');
		//$lastExercise = $this->ChequesModel->getFirstLastExercise(1,'cont');
		if($_REQUEST['editar']){
			$datos = $this->IngresosModel->editarDocumento($_REQUEST['editar']);
			$appPagos = $this->ChequesModel->pagosConDocumento($_REQUEST['editar'], $datos['idbeneficiario'], 0);
			$subcategoriasAsignadas = $this->ChequesModel->consultaSubcategoriasDoc($_REQUEST['editar']);
			if($datos['idmoneda']!=1){
				$cuentasAfectables = $this->ChequesModel->cuentasAfectables(1);
			}
		}else{
			
			
			if($_SESSION['ingresonew']){
				$comprueba = $this->ChequesModel->compruebaDisponibilidad($_SESSION['ingresonew']);
				if($comprueba!=0){//esq ya se ocupo
					$idtemporal = $this->ChequesModel->InsertDocumentoBasico(2);
					$_SESSION['ingresonew'] = $idtemporal;
				}
			}else{
				$idtemporal = $this->ChequesModel->InsertDocumentoBasico(2);
				$_SESSION['ingresonew'] = $idtemporal;
			}
			// if(!isset($_SESSION['ingresonew'])){
				 	// $idtemporal = $this->ChequesModel->InsertDocumentoBasico(2);
					// $_SESSION['ingresonew'] = $idtemporal;
				// }
				// $_SESSION['ingresonew'] = $idtemporal;
			 
		}
		require('views/documentos/ingresos.php');
	}
	function verIngresoNodep(){
		$appministra = $this->ChequesModel->validaAppministra();
		$info = $this->ChequesModel->infoConfiguracion();
		$infoOrg = $this->ChequesModel->rfcOrganizacion();
		$cuentasbancarias = $this->ChequesModel->cuentasbancariaslista();
		$moneda = $this->ChequesModel->moneda();
		$sqlprov = $this->ChequesModel->proveedor();
		$clientes = $this->IngresosModel->catalogoCliente();
		//$sqlcuentasnoenprv = $this->ChequesModel->cuentasAfectables();
		$proveedores = $this->IngresosModel->proveedorBeneficiario();
		$listaconceptos = $this->ChequesModel->concepto(1);
		$clasificador = $this->IngresosModel->clasificadorIngre();
		$tipodocumento = $this->IngresosModel->tipodocumento(3);
		$clasificadorsub = $this->IngresosModel->clasificadorIngre();
		$empleados = $this->ChequesModel->empleados();
		$cuentasAsiganacion = $this->ChequesModel->configCuentas();
		if($info['RFC']==""){
			$Exercise = $this->ChequesModel->getExerciseInfo();
			$Ex = $Exercise->fetch_assoc();
			$firstExercise = $this->ChequesModel->getFirstLastExercise(0,'cont');
			$lastExercise = $this->ChequesModel->getFirstLastExercise(1,'cont');
		}else{
			$firstExercise = $this->ChequesModel->getFirstLastExercise(0,'bco');
			$lastExercise = $this->ChequesModel->getFirstLastExercise(1,'bco');
		}
		if($_REQUEST['editar']){
			$datos = $this->IngresosModel->editarDocumento($_REQUEST['editar']);
			$appPagos = $this->IngresosModel->porAplicar($_REQUEST['editar'], $datos['idbeneficiario']);
			$subcategoriasAsignadas = $this->ChequesModel->consultaSubcategoriasDoc($_REQUEST['editar']);
			
		}else{
			// $basico = $this->ChequesModel->idUltimoDocumentoBasico(3);
			// if($basico>0){
				// $_SESSION['proyectadonew']=$basico;
			// }else{
				// unset($_SESSION['proyectadonew']);
				 // $idtemporal = $this->ChequesModel->InsertDocumentoBasico(3);
				// $_SESSION['proyectadonew'] = $idtemporal;
			// }
			
			if($_SESSION['proyectadonew']){
				$comprueba = $this->ChequesModel->compruebaDisponibilidad($_SESSION['proyectadonew']);
				if($comprueba!=0){//esq ya se ocupo
					$idtemporal = $this->ChequesModel->InsertDocumentoBasico(3);
					$_SESSION['proyectadonew'] = $idtemporal;
				}else{
					$existe = $this->ChequesModel->compruebaExistencia($_SESSION['proyectadonew']);
					if($existe==0){//si es igual a 0 es qno existe y trae algo loco la session 
						$idtemporal = $this->InsertDocumentoBasico(3);
						$id = $idtemporal;
					}
				}
			}else{
				$idtemporal = $this->ChequesModel->InsertDocumentoBasico(3);
				$_SESSION['proyectadonew'] = $idtemporal;
			}
			// if(!isset($_SESSION['proyectadonew'])){
				// $idtemporal = $this->ChequesModel->InsertDocumentoBasico(3);
				// $_SESSION['proyectadonew'] = $idtemporal;
			// }
		}
		require('views/documentos/ingresosNoDepositado.php');
	}
	function verDeposito(){
		$formapago  = $this->ChequesModel->formapago();
		$segmento	= $this->ChequesModel->segmento();
		$sucursal	= $this->ChequesModel->sucursal();
		$moneda = $this->ChequesModel->moneda();
		$acontia = $this->ChequesModel->validaAcontia();
		$cuentasbancarias = $this->ChequesModel->cuentasbancariaslista();
		$ingresoNo = $this->IngresosModel->ingresosPendientesDepositar();
		$listaconceptos = $this->ChequesModel->concepto(1);
		$tipodocumento = $this->IngresosModel->tipodocumento(4);
		$empleados = $this->ChequesModel->empleados();
		$cuentasAsiganacion = $this->ChequesModel->configCuentas();
		$info = $this->ChequesModel->infoConfiguracion();
		if($info['RFC']==""){
			$Exercise = $this->ChequesModel->getExerciseInfo();
			$Ex = $Exercise->fetch_assoc();
			$firstExercise = $this->ChequesModel->getFirstLastExercise(0,'cont');
			$lastExercise = $this->ChequesModel->getFirstLastExercise(1,'cont');
		}else{
			$firstExercise = $this->ChequesModel->getFirstLastExercise(0,'bco');
			$lastExercise = $this->ChequesModel->getFirstLastExercise(1,'bco');
		}
		//$firstExercise = $this->ChequesModel->getFirstLastExercise(0,'cont');
		//$lastExercise = $this->ChequesModel->getFirstLastExercise(1,'cont');
		if(!$ingresoNo->num_rows>0){
			$ingresoNo = 0;
		}
		if($_REQUEST['editar']){
			$datos = $this->IngresosModel->editarDocumento($_REQUEST['editar']);
			$agregados = $this->IngresosModel->IngresosDepositados($_REQUEST['editar']);
			$ingresoNo = $this->IngresosModel->ingresosPendientesDepositarMoneda($datos['idbancaria'],$datos['idmoneda']);
			$subcategoriasAsignadas = $this->ChequesModel->consultaSubcategoriasDoc($_REQUEST['editar']);
		}else{
			
			// if(!isset($_SESSION['depositonew'])){
				// $idtemporal = $this->ChequesModel->InsertDocumentoBasico(4);
				// $_SESSION['depositonew'] = $idtemporal;
			// }
			
			if($_SESSION['depositonew']){
				$comprueba = $this->ChequesModel->compruebaDisponibilidad($_SESSION['depositonew']);
				if($comprueba!=0){//esq ya se ocupo
					$idtemporal = $this->ChequesModel->InsertDocumentoBasico(4);
					$_SESSION['depositonew'] = $idtemporal;
				}else{
					
					$existe = $this->ChequesModel->compruebaExistencia($_SESSION['depositonew']);
					if($existe==0){//si es igual a 0 es qno existe y trae algo loco la session 
						$idtemporal = $this->InsertDocumentoBasico(4);
						$id = $idtemporal;
					}
					
				}
			}else{
				$idtemporal = $this->ChequesModel->InsertDocumentoBasico(4);
				$_SESSION['depositonew'] = $idtemporal;
			}			
		}
		// $basico = $this->ChequesModel->idUltimoDocumentoBasico(4);
			// if($basico>0){
				// $_SESSION['depositonew']=$basico;
			// }else{
				// unset($_SESSION['depositonew']);
				// $idtemporal = $this->ChequesModel->InsertDocumentoBasico(4);
				// $_SESSION['depositonew'] = $idtemporal;
			// }
		
		require('views/documentos/depositos.php');
	}
	function creaIngresoNoDepositado(){//ganzo
		//$moneda = explode('/', $_REQUEST['moneda']);
		$_REQUEST['fecha'] = date('Y-m-d', strtotime($_REQUEST['fecha']));
		
		$cuenta = explode('//',  $_REQUEST['cuenta']);
		$pagador = explode('/',  $_REQUEST['pagador']);
		$cuenta2 = explode('/', $_REQUEST['paguese2']);
		$tipobeneficiario = $pagador[2];//tiene beneficiario/pagador(clientes y prv)
		if( ($pagador[0]=="" || $pagador[0]<=0)){
				$cuentabeneficiario = $_REQUEST['paguese2'];
				if($cuenta2[1]==1 &&  $pagador[3]!=4 ){// si la cuenta elejida es 1 pesos almacena //verifica q no sea un prv como banco !=4
					if($tipobeneficiario==5){//cliente
						$this->IngresosModel->updateClienteCuenta($cuenta2[0],$pagador[1]);
					}elseif($tipobeneficiario==1){//prv
						$this->IngresosModel->updatePrvCuenta($cuenta2[0],$pagador[1]);
					}
				}
			}else{
				$cuentabeneficiario = $pagador[0]."/1";// el uno es para q siempre sea la moneda pesos porq no puede haber asignacion a catalogo cliente prv en ME
			}
		
		
		
		$_REQUEST['importe'] = str_replace(',', '', $_REQUEST['importe']);
		if(!$cuenta[2]){ $cuenta[2]=$_REQUEST['moneda'];}
		if(!isset($_REQUEST['statusinteres'])){$_REQUEST['statusinteres']=0;}
		if(!isset($_REQUEST['tipoPoliza'])){$_REQUEST['tipoPoliza']=0;}
		
		//$idDoc  = $this->IngresosModel->creaIngresoNoDepositado($_REQUEST['fecha'], $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['textarea'],$cuenta[0], $tipobeneficiario, $pagador[1], $_REQUEST['clasificador'], $cuenta[2],$_REQUEST['idDocumento'],$_REQUEST['proceso'],$_REQUEST['tipodocumento'],$_REQUEST['cambio']);
		if( isset($_REQUEST['id']) ){//si existe es edicion
			$previos = $this->ChequesModel->benePrevio($_REQUEST['id']);
					
			$crea  = $this->IngresosModel->actualizaIngreso($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],1,$_REQUEST['id'],$_REQUEST['fecha'], $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['textarea'],$cuenta[0], $tipobeneficiario, $pagador[1], $_REQUEST['clasificador'], $cuenta[2],$_REQUEST['idDocumento'],$_REQUEST['tipodocumento'],$_REQUEST['cambio'],$_REQUEST['tipoPoliza'],$_REQUEST['statusinteres'],$_REQUEST['formapago']);
			if($crea){
				$idDoc = $_REQUEST['id'];
				$reload = "window.location = 'index.php?c=Ingresos&f=".$_REQUEST['link']."'";
				$actual = '(Actualizado)';
				$act = "actualizar";
			}
			
		
		}else{
			//$idDoc = $this->ChequesModel->InsertCheque($_REQUEST['fecha'], $_REQUEST['numero'], $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['textarea'], $cuenta[0], $tipobeneficiario ,$pagador[1] ,$_REQUEST['proceso'],$_REQUEST['clasificador'],1,$_REQUEST['cambio'],$cuenta[2],$_REQUEST['tipodocumento'],$_REQUEST['bancodestino'],$_REQUEST['numcuentadestino'],$_REQUEST['tipoPoliza']);	
			
			$crea  = $this->IngresosModel->actualizaIngreso($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],0,$_REQUEST['idtemporal'],$_REQUEST['fecha'], $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['textarea'],$cuenta[0], $tipobeneficiario, $pagador[1], $_REQUEST['clasificador'], $cuenta[2],$_REQUEST['idDocumento'],$_REQUEST['tipodocumento'],$_REQUEST['cambio'],$_REQUEST['tipoPoliza'],$_REQUEST['statusinteres'],$_REQUEST['formapago']);
			if($crea){
				$idDoc = $crea;
				$reload = "window.location = 'index.php?c=Ingresos&f=".$_REQUEST['link']."'";
				$actual = "(Creado)";
				$act = "crear";
			}
		}
		
		if($crea){
			
			if(isset($_REQUEST['subcategorias'])){
				//$idDoc = $this->ChequesModel->idUltimoDocumento($_REQUEST['idDocumento']);
				$cont=0;
				$ok = $this->ChequesModel->eliminaSubcategoriaDoc($idDoc);
				foreach($_REQUEST['subcategorias'] as $ca){
					if($cont!=0){
						$this->ChequesModel->documentosSubcategorias($idDoc, $_REQUEST['subcategorias'][$cont],$_REQUEST['porcentaje'][$cont-1], number_format(($_REQUEST['porcentaje'][$cont-1]/100) * $_REQUEST['importe'] ,2,'.',''));
					}
					$cont++;
				}
			}
			
		/* 		CXC PROCESO		*/	
		
			
			if( isset($_REQUEST['id']) ){
				/* se queda solo asi en caso de que
				 * appministra maneje cxc o cxp de empleados 
				 * si debe verificar el beneficiario(tipo)*/
				if($previos->idbeneficiario != $pagador[1]){
					$this->ChequesModel->eliminaRegistrocxccxp($idDoc,$previos->idbeneficiario);
				}
				
			}
		if($_REQUEST['idDocumento']==3){ $No = " noDep";}else{ $No = "";}
		
			if(isset($_REQUEST['cargosapp'])){
				$cont=0;
				foreach($_REQUEST['cargosapp'] as $cargo){
					$cargosapp = explode('/', $_REQUEST['cargosapp'][$cont]);
					$impApp = $cargosapp[0];
					$idCargo = $cargosapp[1];
					if($_REQUEST['idDocumento']==3){ $refBanco = "c=>".$idCargo; }else{$refBanco=""; }
					//if($_REQUEST['cambio']<=0){$_REQUEST['cambio']=1;}
					$idPago = $this->ChequesModel->almacenaPago(0, $pagador[1], number_format($impApp,2,'.',''), $_REQUEST['fecha'], $_REQUEST['textarea'], 1, $cuenta[2] , $_REQUEST['cambio'],$idDoc,"Ingreso".$No,$refBanco);
					if($idPago){
						if($_REQUEST['idDocumento']==2){
							$this->ChequesModel->almacenaPagoRelacion($idPago,$idCargo,number_format($impApp,2,'.',''),0);
						}
					}
					$cont++;
				}
			}
			if(isset($_REQUEST['facturasapp'])){
				$cont=0;
				$ruta 	= $this->path('../cont/')."xmls/facturas/documentosbancarios/$idDoc/";
				if(!file_exists($ruta))
				{
					mkdir ($ruta,0777);
				}
				foreach($_REQUEST['facturasapp'] as $fact){
					$factapp = explode('/', $_REQUEST['facturasapp'][$cont]);
					$impApp = $factapp[0];
					$idCargo = $factapp[1];
					$xmlfact = $factapp[2];
					$montoOriginal = $factapp[3];
					if($_REQUEST['idDocumento']==3){ $refBanco = "f=>".$idCargo; }else{$refBanco=""; }
					$idPago = $this->ChequesModel->almacenaPago(0, $pagador[1], number_format($impApp,2,'.',''), $_REQUEST['fecha'], $_REQUEST['textarea'], 1, $cuenta[2] , $_REQUEST['cambio'],$idDoc,"Ingreso".$No,$refBanco);
					if($idPago){
						if($_REQUEST['idDocumento']==2){
							$this->ChequesModel->almacenaPagoRelacion($idPago,$idCargo,number_format($impApp,2,'.',''),1);
							if($xmlfact){
								if($montoOriginal==$impApp){
									rename($this->path('../cont/')."xmls/facturas/temporales/$xmlfact", $ruta.$xmlfact);
								}else{
									copy($this->path('../cont/')."xmls/facturas/temporales/$xmlfact", $ruta.$xmlfact);
								}
							}
						}
								
					}
					$cont++;
				}
			}
	/* 	FIN		CXC PROCESO		*/	
			
			if($_REQUEST['proceso']==1){//si el documento esta proyectado no puede hacer poliza 
				$_REQUEST['idDocumento']=3;
			}
		if($_REQUEST['idDocumento']==2){
			unset($_SESSION['ingresonew']);
			if($_REQUEST['automatica']==1){
				if($_REQUEST['statuscomision']==1){
					$_REQUEST['tipoPoliza']=3;
				}
				//$elimina = $this->ChequesModel->eliminaPolizaDocumento($idDoc);
				$verifica = $this->verficaPolizaLocal($idDoc);
				//if($elimina==1){
					if($_REQUEST['tipoPoliza']==1 ){
						$polizaAut = $this->creaPolizaAutomaticaIngresosIVA($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],$verifica,0,0,0,$pagador[1], $idDoc, $_REQUEST['textarea'], $_REQUEST['fecha'], '', 0,'' , $_REQUEST['cuenta'], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'],$_REQUEST['formapago'],$pagador[2],$_REQUEST['cambio']);
						if($polizaAut!=0){
							echo "<script>alert('Documento y Poliza $actual'); " .$this->mandaPoliza($polizaAut,"$reload",$pagador[1],0,$tipobeneficiario,$_REQUEST['tipoPoliza']).";</script>";
						}
					}
			
			///////////////////
					elseif($_REQUEST['tipoPoliza']==2 ){
						$cuentasConf = $this->ChequesModel->configCuentas();
						if($cuentasConf['CuentaIVAPendienteCobro']!=-1 && $cuentasConf['CuentaIVAcobrado']!=-1){
							$polizaAut = $this->creaPolizaAutomaticaIngresosIVA($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],$verifica,0,0,1,$pagador[1], $idDoc, $_REQUEST['textarea'], $_REQUEST['fecha'], '', 0,'' , $_REQUEST['cuenta'], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'],$pagador[2],$_REQUEST['cambio']);
							if($polizaAut!=0){
								echo "<script>alert('Documento y Poliza $actual'); " .$this->mandaPoliza($polizaAut,"$reload",$pagador[1],0,$tipobeneficiario,$_REQUEST['tipoPoliza']).";</script>";
							}
						}else{
							$polizaAut = $this->creaPolizaAutomaticaIngresosIVA($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],$verifica,0,0,0,$pagador[1], $idDoc, $_REQUEST['textarea'], $_REQUEST['fecha'], '', 0,'' , $_REQUEST['cuenta'], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'],$pagador[2],$_REQUEST['cambio']);
							if($polizaAut!=0){
							//$verifica = $this->verficaPolizaLocal($idDoc);
							//if($verifica!=0){
								echo "<script> alert('No tiene las cuentas de IVA asignadas');
								if(confirm('Desea agregarlos a la poliza manualmente?')){
									$reload
									window.parent.preguntar=false;
					 				window.parent.quitartab('tb0',0,'Polizas');
					 				window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&bancos=1&id=".$polizaAut."','Polizas','',0);
									window.parent.preguntar=true;
									 
								}else{
									$reload
								}
								</script>";
							//}
							}
						}
					}//if poliza==2
				////////////////////////
					elseif($_REQUEST['tipoPoliza']==3 ){
						$polizaAut = $this->creaPolizaAutomaticaIngresosSinProvision($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],$verifica,$cuenta[2],0,0,$pagador[1], $idDoc, $_REQUEST['textarea'], $_REQUEST['fecha'], '', 0,'' , $cuenta[0], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'], $cuenta[1],$pagador[2]);
							if($polizaAut!=0){
						//$verifica = $this->verficaPolizaLocal($idDoc);
						//if($verifica!=0){
								echo "<script> 
								if(confirm('Desea completar la poliza?')){
									window.parent.preguntar=false;
					 				window.parent.quitartab('tb0',0,'Polizas');
					 				window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&bancos=1&id=".$polizaAut."','Polizas','',0);
									window.parent.preguntar=true;
									$reload
								}else{
									$reload
								}
								</script>";
						//}
							}
					}
				//}
		 	 }//automatica
			 elseif($_REQUEST['automatica']==0 && $_REQUEST['acontia']==1){
			 	if( isset($_REQUEST['id']) ){
			 		$verifica = $this->verficaPolizaLocal($_REQUEST['id']);
					if($verifica!=0){// la manual no esta actualizada por en teoria ya no abra manual porq va suempre con acontia
						$hecho = $this->ChequesModel->actualizaPolizaManual($_REQUEST['fecha'], $cuenta[1], $_REQUEST['importe'], $verifica,$cuenta[2],$_REQUEST['cambio']);
						if($hecho==1){
							echo "<script>alert('Documento $actual');
							alert('Sera enviado a la Poliza');
									".$this->mandaPolizaManual($verifica,"$reload")
									."
							</script>";
						}else{
							echo "
							<script>
								alert('Error al crear poliza');
							</script>
							";
						}
					}else{
						$polizaAut = $this->creaPolizaAutomaticaIngresosSinProvision($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],0,$cuenta[2],1,$_REQUEST['cambio'],$pagador[1], $idDoc, $_REQUEST['textarea'], $_REQUEST['fecha'], '', 0,'' , $cuenta[0], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'], $cuenta[1],$pagador[2]);
						echo "<script>alert('Documento $actual');
							alert('Sera enviado a la Poliza');
									".$this->mandaPolizaManual($polizaAut,"$reload")
									."
						</script>";
					}
			 		
				}else{
					$polizaAut = $this->creaPolizaAutomaticaIngresosSinProvision($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],0,$cuenta[2],1,$_REQUEST['cambio'],$pagador[1], $idDoc, $_REQUEST['textarea'], $_REQUEST['fecha'], '', 0,'' , $cuenta[0], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'], $cuenta[1],$pagador[2]);
					
					echo "<script>alert('Documento $actual');
						alert('Sera enviado a la Poliza');
								".$this->mandaPolizaManual($polizaAut,"$reload")
								."
					</script>";
				}
			}
			else{
				echo "<script>alert('Documento $actual');$reload</script>";
			}
		}
		else{ unset($_SESSION['proyectadonew']);
			echo "<script>alert('Documento $actual');$reload </script>";
		}	
			
	}else{
		echo "<script>alert('Error al $act documento Intente de nuevo!'); $reload </script>";
	}
}
	// function updateIngreso(){//ganzo
		// //$moneda = explode('/', $_REQUEST['moneda']);/
		// $cuenta = explode('//',  $_REQUEST['cuenta']);
		// $pagador = explode('/',  $_REQUEST['pagador']);
		// $reload = "window.location = 'index.php?c=Ingresos&f=".$_REQUEST['link']."'";
		// if($pagador[0]=="" || $pagador[0]==0 || $pagador[0]==-1){
				// $cuentabeneficiario = $_REQUEST['paguese2'];
				// if($cuenta2[1]==1){// si la cuenta elejida es 1 pesos almacena
					// if($tipobeneficiario==5){//cliente
						// $this->IngresosModel->updateClienteCuenta($pagador[1], $cuenta2[0]);
					// }elseif($tipobeneficiario==1){//prv
						// $this->IngresosModel->updatePrvCuenta($pagador[1], $cuenta2[0]);
					// }
				// }
			// }else{
				// $cuentabeneficiario = $pagador[0].'/1';
			// }
		// $tipobeneficiario = $pagador[2];
// 		
		// $_REQUEST['importe'] = str_replace(',', '', $_REQUEST['importe']);
		// if(!$cuenta[2]){ $cuenta[2]=$_REQUEST['moneda'];}
		// $crea  = $this->IngresosModel->actualizaDocumento($_REQUEST['id'],$_REQUEST['fecha'], $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['textarea'],$cuenta[0], $tipobeneficiario, $pagador[1], $_REQUEST['clasificador'], $cuenta[2],$_REQUEST['idDocumento'],$_REQUEST['tipodocumento'],$_REQUEST['cambio']);
		// if($crea){
			// $ok = $this->ChequesModel->eliminaSubcategoriaDoc($_REQUEST['id']);
			// if($ok==1){ 
				// if(isset($_REQUEST['subcategorias'])){
					// $cont=0;
					// foreach($_REQUEST['subcategorias'] as $ca){
						// if($cont!=0){
							// $this->ChequesModel->documentosSubcategorias($_REQUEST['id'], $_REQUEST['subcategorias'][$cont],$_REQUEST['porcentaje'][$cont-1], number_format(($_REQUEST['porcentaje'][$cont-1]/100) * $_REQUEST['importe'] ,2,'.',''));
						// }
						// $cont++;
					// }
				// }			
// 				
				// if($_REQUEST['idDocumento']==2){
					// if($_REQUEST['proceso']==1){//si el documento esta proyectado no puede hacer poliza 
						// echo "<script> alert('Documento Actualizado'); $reload </script>";
						// return false;
					// }
						// if($_REQUEST['automatica']==1){
							// $elimina = $this->ChequesModel->eliminaPolizaDocumento($_REQUEST['id']);
							// if($elimina==1){
								// if($_REQUEST['tipoPoliza']==1){
									// $polizaAut = $this->creaPolizaAutomaticaIngresosIVA(0,0,0,$pagador[1], $_REQUEST['id'], $_REQUEST['textarea'], $_REQUEST['fecha'], '', 0,'' , $_REQUEST['cuenta'], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'], 0, $pagador[2],$_REQUEST['cambio']);
									// if($polizaAut!=0){
										// echo "<script>alert('Documento y Poliza Actualizado'); " .$this->mandaPoliza($polizaAut,"$reload",$pagador[1],0,$tipobeneficiario,$_REQUEST['tipoPoliza']).";</script>";
// 										
									// }
								// }elseif($_REQUEST['tipoPoliza']==2){//krmn
									// $cuentasConf = $this->ChequesModel->configCuentas();
										// if($cuentasConf['CuentaIVAPendienteCobro']!=-1 && $cuentasConf['CuentaIVAcobrado']!=-1){
											// $polizaAut = $this->creaPolizaAutomaticaIngresosIVA(0,0,1,$pagador[1], $_REQUEST['id'], $_REQUEST['textarea'], $_REQUEST['fecha'], '', 0,'' , $_REQUEST['cuenta'], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'], 0, $pagador[2],$_REQUEST['cambio']);
											// if($polizaAut!=0){
												// echo "<script>alert('Documento y Poliza Actualizado'); " .$this->mandaPoliza($polizaAut,"$reload",$pagador[1],0,$tipobeneficiario,$_REQUEST['tipoPoliza']).";</script>";
											// }
										// }else{
											// $polizaAut = $this->creaPolizaAutomaticaIngresosIVA(0,0,0,$pagador[1], $_REQUEST['id'], $_REQUEST['textarea'], $_REQUEST['fecha'], '', 0,'' ,  $_REQUEST['cuenta'], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'], 0, $pagador[2],$_REQUEST['cambio']);
											// if($polizaAut!=0){
												// //$verifica = $this->verficaPolizaLocal($_REQUEST['id']);
												// //if($verifica!=0){
													// echo "<script> alert('No tiene las cuentas de IVA asignadas');
													// if(confirm('Desea agregarlos a la poliza manualmente?')){
														// window.parent.preguntar=false;
										 				// window.parent.quitartab('tb0',0,'Polizas');
										 				// window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&id=".$polizaAut."','Polizas','',0);
														// window.parent.preguntar=true;
														// $reload 
													// }else{
														// $reload
													// }
													// </script>";
												// //}
											// }
										// }
								// }//if poliza==2
								// elseif($_REQUEST['tipoPoliza']==3){
									// $polizaAut = $this->creaPolizaAutomaticaIngresosSinProvision($pagador[1], $_REQUEST['id'], $_REQUEST['textarea'], $_REQUEST['fecha'], '', 0,'' , $cuenta[0], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'], 0, $cuenta[1],$pagador[2]);
									// if($polizaAut!=0){
										// //$verifica = $this->verficaPolizaLocal($_REQUEST['id']);
										// //if($verifica!=0){
											// echo "<script> 
											// if(confirm('Desea actualizar la poliza para agregar la cuenta de gasto?')){
												// window.parent.preguntar=false;
								 				// window.parent.quitartab('tb0',0,'Polizas');
								 				// window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&id=".$polizaAut."','Polizas','',0);
												// window.parent.preguntar=true;
												// $reload
											// }else{
												// $reload
											// }
											// </script>";
										// //}
									// }
								// }
							// }else{
								// echo "<script> alert('Error al actualizar Poliza'); window.location = 'index.php?c=Ingresos&f=".$_REQUEST['link']."'; </script>";
							// }
						// }else{
							// $verifica = $this->verficaPolizaLocal($_REQUEST['id']);
							// if($verifica!=0){
// 								
								// echo "<script> alert('Documento Actualizado');
								// if(confirm('Desea actualizar la poliza?')){
									// window.parent.preguntar=false;
					 				// window.parent.quitartab('tb0',0,'Polizas');
					 				// window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&id=".$verifica."','Polizas','',0);
									// window.parent.preguntar=true;
									// $reload 
								// }else{
									// $reload
								// }
								// </script>";
							// }else{
								// echo "<script> alert('Documento Actualizado'); $reload </script>";
							// }
						// }
				// }else{
					// echo "<script> alert('Documento Actualizado'); $reload </script>";
				// }
// 				
			// }
		// }else{
			// echo "<script>alert('Error al actualizar documento Intente de nuevo!'); $reload </script>";
		// }
	// }
function updateDeposito(){//ganzo
		$_REQUEST['fecha'] = date('Y-m-d', strtotime($_REQUEST['fecha']));
		$_REQUEST['fechaaplicacion'] = date('Y-m-d', strtotime($_REQUEST['fechaaplicacion']));

		$cuenta = explode('//',  $_REQUEST['cuenta']);
		$_REQUEST['importe'] = str_replace(',', '', $_REQUEST['importe']);
		$reload = "window.location = 'index.php?c=Ingresos&f=filtro&fun=verDeposito'; ";
		$crea  = $this->IngresosModel->actualizaDeposito(4,$_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],1,$_REQUEST['id'],$_REQUEST['fecha'], $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['textarea'],$cuenta[0], $_REQUEST['clasificador'],$_REQUEST['formapago'],$_REQUEST['tipodocumento'],$_REQUEST['cambio'],$cuenta[2],$_REQUEST['tipoPoliza'],$_REQUEST['fechaaplicacion']);
		if($crea){
			$hecho = $this->IngresosModel->updateIngresoSiempreNoDepositado($_REQUEST['id']);
			if($hecho){
				
				foreach ($_REQUEST['Nodepositado'] as $d){
					$idDoc = explode("/",$d);
					if($_REQUEST['cuenta']=='t'){
						$cuenta[0]=$idDoc[2];
					}
					$this->IngresosModel->updateIngresoNO($idDoc[1],$_REQUEST['fechaaplicacion'],$cuenta[0]);
					$this->IngresosModel->insertIngreso_deposito($idDoc[1],$_REQUEST['id']);
				
					$cxc = $this->IngresosModel->porAplicar($idDoc[1]);
					if($cxc->num_rows>0 ){
						while($cxcapp = $cxc->fetch_object()){
							$sepa = explode('=>', $cxcapp->ref_bancos);
							$concep = explode("-Ingreso noDep",$cxcapp->concepto);
							// para saber si es f=1 fatura o c-0 cobro
							if($sepa[0]=="f"){ $tipoap = "1";}else{ $tipoap = "0";}
							$this->ChequesModel->almacenaPagoRelacion($cxcapp->id,$sepa[1],number_format($cxcapp->abono,2,'.',''),$tipoap);
							$this->IngresosModel->updateNoDeptc($cxcapp->id,$_REQUEST['cambio'],$_REQUEST['fechaaplicacion']);
							}
					}
				
				}
				
				$ok = $this->ChequesModel->eliminaSubcategoriaDoc($_REQUEST['id']);
				if($ok==1){ 
					if(isset($_REQUEST['subcategorias'])){
						$cont=0;
						foreach($_REQUEST['subcategorias'] as $ca){
							if($cont!=0){
								$this->ChequesModel->documentosSubcategorias($_REQUEST['id'], $_REQUEST['subcategorias'][$cont],$_REQUEST['porcentaje'][$cont-1], number_format(($_REQUEST['porcentaje'][$cont-1]/100) * $_REQUEST['importe'] ,2,'.',''));
							}
							$cont++;
						}
					}
				}

				if($_REQUEST['automatica']==1){
					$verifica = $this->verficaPolizaLocal($_REQUEST['id']);
					
					//$elimina = $this->ChequesModel->eliminaPolizaDocumento($_REQUEST['id']);
					if($_REQUEST['tipoPoliza']==1){
						$polizaAut = $this->creaPolizaAutomaticaIngresosIVA($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],$verifica,$_REQUEST['Nodepositado'],1,0,$pagador[1], $_REQUEST['id'], $_REQUEST['textarea'], $_REQUEST['fecha'], '', 0,'' , $_REQUEST['cuenta'], 0, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'],0,$_REQUEST['cambio']);
						if($polizaAut!=0){
							echo "<script>alert('Documento y Poliza Actualizados'); " .$this->mandaPoliza($polizaAut,"$reload",$pagador[1],0,$tipobeneficiario,$_REQUEST['tipoPoliza']).";</script>";
						}
					}
					elseif($_REQUEST['tipoPoliza']==2){
						$cuentasConf = $this->ChequesModel->configCuentas();
						if($cuentasConf['CuentaIVAPendienteCobro']!=-1 && $cuentasConf['CuentaIVAcobrado']!=-1){
							$polizaAut = $this->creaPolizaAutomaticaIngresosIVA($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],$verifica,$_REQUEST['Nodepositado'],1,1,$pagador[1], $_REQUEST['id'], $_REQUEST['textarea'], $_REQUEST['fecha'], '', 0,'' , $_REQUEST['cuenta'], 0, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'], 0,$_REQUEST['cambio']);
							if($polizaAut!=0){
								echo "<script>alert('Documento y Poliza Actualizados'); " .$this->mandaPoliza($polizaAut,"$reload",$pagador[1],0,$tipobeneficiario,$_REQUEST['tipoPoliza'])."; </script>";
							}
						}else{
							$polizaAut = $this->creaPolizaAutomaticaIngresosIVA($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],$verifica,$_REQUEST['Nodepositado'],1,0,$pagador[1], $_REQUEST['id'], $_REQUEST['textarea'], $_REQUEST['fecha'], '', 0,'' , $_REQUEST['cuenta'], 0, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'], 0,$_REQUEST['cambio']);
							if($polizaAut!=0){
							
								echo "<script> alert('No tiene las cuentas de IVA asignadas');
								if(confirm('Desea agregarlos a la poliza manualmente?')){
									$reload
									window.parent.preguntar=false;
					 				window.parent.quitartab('tb0',0,'Polizas');
					 				window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&bancos=1&id=".$polizaAut."','Polizas','',0);
									window.parent.preguntar=true;
									 
								}else{
									$reload
								}
								</script>";
							}
						}
					}//if poliza==2
			////////////////////////
					elseif($_REQUEST['tipoPoliza']==3){
						$polizaAut = $this->creaPolizaAutomaticaIngresosSinProvision($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],$verifica,$cuenta[2],0,0,$pagador[1],$_REQUEST['id'], $_REQUEST['textarea'], $_REQUEST['fecha'], '', 0,'' , $cuenta[0], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'], $cuenta[1],0);
						if($polizaAut!=0){
							echo "<script> 
							if(confirm('Desea completar la poliza?')){
								window.parent.preguntar=false;
				 				window.parent.quitartab('tb0',0,'Polizas');
				 				window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&bancos=1&id=".$polizaAut."','Polizas','',0);
								window.parent.preguntar=true;
								$reload
							}else{
								$reload
							}
							</script>";
						}
					}
				}//automatica
				elseif($_REQUEST['automatica']==0 && $_REQUEST['acontia']==1){
		 			$verifica = $this->verficaPolizaLocal($_REQUEST['id']);
					if($verifica!=0){
						$hecho = $this->ChequesModel->actualizaPolizaManual($_REQUEST['fecha'], $cuenta[1], $_REQUEST['importe'], $verifica,$cuenta[2],$_REQUEST['cambio']);
						if($hecho==1){
							echo "<script>alert('Documento Actualizado');
							alert('Sera enviado a la Poliza');
								".$this->mandaPolizaManual($verifica,"$reload")
								."
							</script>";
						}else{
							echo "
							<script>
								alert('Error al crear poliza');
							</script>
							";
						}
					}else{
						$polizaAut = $this->creaPolizaAutomaticaIngresosSinProvision($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],0,$cuenta[2],1,$_REQUEST['cambio'],$pagador[1],$_REQUEST['id'], $_REQUEST['textarea'], $_REQUEST['fecha'], '', 0,'' , $cuenta[0], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'], $cuenta[1],0);
						echo "<script>alert('Documento Actualizado');
						alert('Sera enviado a la Poliza');
								".$this->mandaPolizaManual($polizaAut,"$reload")
								."
						</script>";
					}
				
				}
			elseif($_REQUEST['acontia']==0){
					echo "<script> alert('Documento Actualizado');$reload </script>";
				}
				
			}else{
				echo "<script>alert('Error al actualizar documento Intente de nuevo!'); $reload </script>";
			}
		}else{
			echo "<script>alert('Error al actualizar documento Intente de nuevo!'); $reload </script>";
		}
	}
	/* DEPOSITOS  TRASPASOS
	 * Crea depositos a partir de traspasos 
	 * hasta el momento los deposito estan creados sin cuenta preguntar a isis que aplica 
	 * en este caso */
	function creaDeposito(){
		$_REQUEST['fecha'] = date('Y-m-d', strtotime($_REQUEST['fecha']));
		$_REQUEST['fechaaplicacion'] =date('Y-m-d', strtotime($_REQUEST['fechaaplicacion']));
		$cuenta = explode('//',  $_REQUEST['cuenta']);
		$_REQUEST['importe'] = str_replace(',', '', $_REQUEST['importe']);
		//$crea = $this->IngresosModel->creaDeposito($_REQUEST['fecha'],number_format($_REQUEST['importe'],2,'.',''), $_REQUEST['referencia'], $_REQUEST['textarea'], $cuenta[0], $_REQUEST['formadeposito'],$_REQUEST['tipodocumento'],$cuenta[2],$_REQUEST['cambio']);
		if($_REQUEST['cuenta']=='t'){//TRASPASO
			$_REQUEST['automatica'] = 0;
			$_REQUEST['acontia'] = 0;
			$cuenta[2] = $_REQUEST['moneda'];
			$cuenta[0] = 0;
		}
		
		$crea  = $this->IngresosModel->actualizaDeposito(4,$_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],0,$_REQUEST['idtemporal'],$_REQUEST['fecha'], $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['textarea'],$cuenta[0], $_REQUEST['clasificador'],$_REQUEST['formapago'],$_REQUEST['tipodocumento'],$_REQUEST['cambio'],$cuenta[2],$_REQUEST['tipoPoliza'],$_REQUEST['fechaaplicacion']);
		
		$insert=""; $update="";
		if($crea){
			$sql="";
			$idDepo = $crea;
			unset($_SESSION['depositonew']);
			foreach ($_REQUEST['Nodepositado'] as $d){
				$idDoc = explode("/",$d);
				if($_REQUEST['cuenta']=='t'){
					$cuenta[0]=$idDoc[2];
				}
				//$idDoc[1] id del no depositado
				$this->IngresosModel->updateIngresoNO($idDoc[1],$_REQUEST['fechaaplicacion'],$cuenta[0]);
				$this->IngresosModel->insertIngreso_deposito($idDoc[1],$idDepo);
				
				//desd aqui
				$cxc = $this->IngresosModel->porAplicar($idDoc[1]);
				if($cxc->num_rows>0 ){
					while($cxcapp = $cxc->fetch_object()){
						$sepa = explode('=>', $cxcapp->ref_bancos);
						$concep = explode("-Ingreso noDep",$cxcapp->concepto);
						if($sepa[0]=="f"){ $tipoap = "1";}else{ $tipoap = "0";}
						$this->ChequesModel->almacenaPagoRelacion($cxcapp->id,$sepa[1],number_format($cxcapp->abono,2,'.',''),$tipoap);
						if($_REQUEST['cambio']<=0){$_REQUEST['cambio']=1;}
						$this->IngresosModel->updateNoDeptc($cxcapp->id,$_REQUEST['cambio'],$_REQUEST['fechaaplicacion']);
						}
				}
						
			}
			if($_REQUEST['cuenta']=='t'){// le puse 0 para numero de documento porq ya se creo
			//$_REQUEST['formadeposito'] lo cambie por $_REQUEST['formapago'] 
				$crea  = $this->IngresosModel->actualizaDeposito(4,$_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],0,$_REQUEST['idtemporal'],$_REQUEST['fecha'], $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['textarea'],$cuenta[0], $_REQUEST['clasificador'],$_REQUEST['formapago'],$_REQUEST['tipodocumento'],$_REQUEST['cambio'],$cuenta[2],$_REQUEST['tipoPoliza'],$_REQUEST['fechaaplicacion']);
			}
			$reload = "window.location = 'index.php?c=Ingresos&f=verDeposito';";
			
			
			
			if($_REQUEST['automatica']==1){
				if($_REQUEST['tipoPoliza']==1){
					$polizaAut = $this->creaPolizaAutomaticaIngresosIVA($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],0,$_REQUEST['Nodepositado'],1,0,0, $idDepo, $_REQUEST['textarea'], $_REQUEST['fecha'], '', 0,'' , $_REQUEST['cuenta'], 0, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'], 0,$_REQUEST['cambio']);
					
					if($polizaAut!=0){
						echo "<script>alert('Documento y Poliza Creado'); " .$this->mandaPoliza($polizaAut,"$reload",0,0,$tipobeneficiario,$_REQUEST['tipoPoliza'])."; </script>";
					}
				}
				elseif($_REQUEST['tipoPoliza']==2){
					$cuentasConf = $this->ChequesModel->configCuentas();//krmn
					if($cuentasConf['CuentaIVAPendienteCobro']!=-1 && $cuentasConf['CuentaIVAcobrado']!=-1){
						$polizaAut = $this->creaPolizaAutomaticaIngresosIVA($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],0,$_REQUEST['Nodepositado'],1,1,0, $idDepo, $_REQUEST['textarea'], $_REQUEST['fecha'], '', 0,'' , $_REQUEST['cuenta'], 0, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'],0,$_REQUEST['cambio']);
						if($polizaAut!=0){
							echo "<script>alert('Documento y Poliza Creado'); " .$this->mandaPoliza($polizaAut,"$reload",0,0,$tipobeneficiario,$_REQUEST['tipoPoliza'])."; </script>";
						}
					}else{
						$polizaAut = $this->creaPolizaAutomaticaIngresosIVA($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],0,$_REQUEST['Nodepositado'],1,0,0, $idDepo, $_REQUEST['textarea'], $_REQUEST['fecha'], '', 0,'' , $_REQUEST['cuenta'], 0, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'],0,$_REQUEST['cambio']);
						if($polizaAut!=0){
								echo "<script> alert('No tiene las cuentas de IVA asignadas');
								if(confirm('Desea agregarlos a la poliza manualmente?')){
									$reload
									window.parent.preguntar=false;
					 				window.parent.quitartab('tb0',0,'Polizas');
					 				window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&bancos=1&id=".$polizaAut."','Polizas','',0);
									window.parent.preguntar=true;
									 
								}else{
									$reload
								}
								</script>";
						}
					}
				}//if poliza==2
			////////////////////////
				elseif($_REQUEST['tipoPoliza']==3){
					$polizaAut = $this->creaPolizaAutomaticaIngresosSinProvision($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],0,$cuenta[2],0,0,$pagador[1],$idDepo, $_REQUEST['textarea'], $_REQUEST['fecha'], '', 0,'' , $cuenta[0], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'], $cuenta[1],0);
					if($polizaAut!=0){
							echo "<script> 
							if(confirm('Desea completar la poliza?')){
								window.parent.preguntar=false;
				 				window.parent.quitartab('tb0',0,'Polizas');
				 				window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&bancos=1&id=".$polizaAut."','Polizas','',0);
								window.parent.preguntar=true;
								$reload
							}else{
								$reload
							}
							</script>";
					}
				}
			}
			elseif($_REQUEST['automatica']==0 && $_REQUEST['acontia']==1){
					$polizaAut = $this->creaPolizaAutomaticaIngresosSinProvision($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],0,$cuenta[2],1,$_REQUEST['cambio'],$pagador[1],$idDepo, $_REQUEST['textarea'], $_REQUEST['fecha'], '', 0,'' , $cuenta[0], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'], $cuenta[1],0);
					echo "<script>alert('Documento Creado');
					alert('Sera enviado a la Poliza');
							".$this->mandaPolizaManual($polizaAut,"$reload")
							."
					</script>";
			}
			elseif( $_REQUEST['acontia']==0){
				echo "<script>alert('Documento Creado');$reload</script>";
			}	
			
			
		}else{
			echo "<script>alert('Error al Crear documento Intente de nuevo!'); window.location = 'index.php?c=Ingresos&f=verDeposito'; </script>";
		}
	}

	function ActualizaNodepostados(){
		if($_REQUEST['traspaso']==1){
			$ingresoNo = $this->IngresosModel->ingresosPendientesDepositarTraspaso($_REQUEST['moneda']);
		}else{
			$ingresoNo = $this->IngresosModel->ingresosPendientesDepositarMoneda($_REQUEST['idbancaria'],$_REQUEST['moneda']);
		}
		if($ingresoNo->num_rows>0){
			while($in = $ingresoNo->fetch_assoc()){
				echo "<tr class=\"out\" onmouseout=\"this.className='out'\" onmouseover=\"this.className='over'\" >
					<td><input  type='checkbox' name='Nodepositado[]' class='listacheck' data-value='".$in['idbancaria']."' value=".$in['importe']."/".$in['id']."/".$in['idbancaria']." onclick='calculo()'/> </td>
					<td>".$in['cuenta']."</td>
					<td>".$in['fecha']."</td>
					<td>".$in['nombre']."</td>
					<td>".$in['referencia']."</td>
					<td>".$in['concepto']."</td>
					<td><b style='color:red'>".$in['importe']."</b></td>
					<td>".$in['description']."</td>
				</tr>";
	  		}
	 }else{
	 	echo '
	 		<tr>
  				<td colspan="8" align="center">No tiene Ingresos Proyectados</td>
  			</tr>';
	 }
	 	
	}
function listadoIngresos(){
	$ingresos = $this->IngresosModel->listadoIngresos();
	if(!$ingresos->num_rows>0){
		$ingresos = 0;
	}
	require('views/documentos/ingresosListado.php');
	
}
function listadoIngresosP(){
	$ingresos = $this->IngresosModel->listadoIngresosProyectados();
	if(!$ingresos->num_rows>0){
		$ingresos = 0;
	}
	require('views/documentos/proyectadoListado.php');
	
}
function listadoDeposito(){
	$depositos = $this->IngresosModel->listadoDepositos();
	if(!$depositos->num_rows>0){
		$depositos = 0;
	}
	require('views/documentos/depositosListado.php');
}

function eliminaIngresoDeDeposito(){
	echo $hecho = $this->IngresosModel->updateIngresoSiempreNoDepositado($_REQUEST['id']);
}

function creaPolizaAutomaticaIngresosIVA($segmento,$sucursal,$numeroformap,$idpoliza,$proyectados,$deposito,$concuentas,$beneficiario,$idDocumento,$concepto,$fecha,$numerocheque,$bancodestino,$cuentadestino,$idbancaria,$cuentabeneficiariob,$importe,$referencia,$formapago,$idbeneficiario,$tc){
	$Exercise = $this->ChequesModel->getExerciseInfo();//pato
	if( $Ex = $Exercise->fetch_assoc() ){
		$idorg = $Ex['IdOrganizacion'];
		$idejer = $Ex['IdEx'];
		$idperio = $Ex['PeriodoActual'];}
		$info = $this->ChequesModel->infoConfiguracion();
		
		if( isset($_COOKIE['ejercicio']) ){
			$idperio = $_COOKIE['periodo'];
			$idejer = $this->ChequesModel->idex($_COOKIE['ejercicio'],'cont');
		}
		else{
			if(!$info['RFC']==""){
				$idejer = $this->ChequesModel->idex($info['EjercicioActual'],'cont');
				$idperio = $info['PeriodoActual'];
			}
		}
		
		if($idbeneficiario==1){$tipo="2-";}elseif($idbeneficiario==5){ $tipo="1-";}elseif($idbeneficiario==2){ $tipo="1-";}
		
		$cuentasConf = $this->ChequesModel->configCuentas();
		$xml="";//$segmento=1;$sucursal=1;
		
		$cuenta = explode('//',  $idbancaria);//$b['idbancaria']."//".$b['account_id']."//".$b['coin_id']
		$datosBeneficiario = explode('/', $cuentabeneficiariob);//$b['account_id']."/".$b['currency_id']
		$cuentacontable = $cuenta[1];
		$cuentabeneficiario = $datosBeneficiario[0];
		$idbancaria = $cuenta[0];
		$cuentamoneda = $cuenta[2];
		$modenaCuentaBene = $datosBeneficiario[1];
		$tcb = $tc;//este es para los depositos
		$fecha = date('Y-m-d', strtotime($fecha));
		if($idpoliza>0){
			$importAntes = $this->IngresosModel->importMovBancoPoliza($idDocumento, $cuentacontable);
			$sinmov = $this->ChequesModel->eliminaMovimientosPoliza($idDocumento);
			if($sinmov==1){
				if($importAntes['importe']!=$importe){
					$imporBase = $importe / 1.16;
					$imporIva  = $importe - $imporBase;
					$this->IngresosModel->desgloseIva($imporBase, $imporIva, $idpoliza, $importe);
				}
				$poli = $this->ChequesModel->savePoliza($referencia,$idpoliza,$idorg, $idejer, $idperio, 1, $concepto, $fecha, 0, $numeroformap, '', 0, '', $idbancaria,$idDocumento,$idbeneficiario);
				$numPoliza['id'] = $idpoliza;
			}
		}else{
			$poli = $this->ChequesModel->savePoliza($referencia,0,$idorg, $idejer, $idperio, 1, $concepto, $fecha, 0, $numeroformap, '', 0, '', $idbancaria,$idDocumento,$idbeneficiario);
			$numPoliza = $this->ChequesModel->getLastNumPoliza();
		}
		if( $poli == 0 ){
			
			
			//$numPoliza = $this->ChequesModel->getLastNumPoliza();
			$rutapoli 	= $this->path('../cont/')."xmls/facturas/" . $numPoliza['id'];
			if(!file_exists($rutapoli))
			{
				mkdir ($rutapoli, 0777);
			}
			$numMov = 1;
			if($cuentamoneda!=1){
				$ban = $this->ChequesModel->InsertMov($numPoliza['id'], $numMov, $segmento, $sucursal, $cuentacontable, "Cargo M.E.", $importe, $concepto,$tipo, $xml, $referencia, $formapago,$tc);
				$ban = $this->ChequesModel->InsertMov($numPoliza['id'], $numMov, $segmento, $sucursal, $cuentacontable, "Cargo", number_format(floatval($importe * $tc),2,'.',''), $concepto,$tipo, $xml, $referencia, $formapago,'0.0000');
				$numMov++;
				$importedll = $importe;
				$importe =  number_format(floatval($importe * $tc),2,'.','');
			}else{
				$ban = $this->ChequesModel->InsertMov($numPoliza['id'], $numMov, $segmento, $sucursal, $cuentacontable, "Cargo", $importe, $concepto,$tipo, $xml, $referencia, $formapago,'0.0000');
				$numMov++;
			}
			if($ban==true){
				if($deposito){// si es un deposito tendra q crear un movimiento por cada proyectado
					if($cuentamoneda==1){
						$tcb=1;
					}
					foreach($proyectados as $p){
						$idDoc = explode("/",$p);
						$infoB = $this->ChequesModel->idBeneficiario($idDoc[1]);
						if($infoB['beneficiario']==5){
							$cliente = $this->ChequesModel->clienteInfo($infoB['idbeneficiario']);
							$bene = $this->ChequesModel->InsertMov($numPoliza['id'], $numMov, $segmento, $sucursal, $cliente['cuenta'], "Abono",number_format(floatval($infoB['importe']*$tcb),2,'.',''), $concepto,'1-'.$cliente['id'], $xml, $referencia, $formapago,'0.0000');
							$numMov++;
						}
						elseif($infoB['beneficiario']==1){
							$prv = $this->ChequesModel->datosproveedor($infoB['idbeneficiario']);
							$bene = $this->ChequesModel->InsertMov($numPoliza['id'], $numMov, $segmento, $sucursal, $prv['cuentacliente'], "Abono", number_format(floatval($infoB['importe']*$tcb),2,'.',''), $concepto,'2-'.$prv['idPrv'], $xml, $referencia, $formapago,'0.0000');
							$numMov++;
						}
						elseif($infoB['beneficiario']==2){
							$bene = $this->ChequesModel->InsertMov($numPoliza['id'], $numMov, $segmento, $sucursal, $_SESSION['empleadosDepositos'][$infoB['idbeneficiario']], "Abono", number_format(floatval($infoB['importe']*$tcb),2,'.',''), $concepto,'emp-'.$infoB['idbeneficiario'], $xml, $referencia, $formapago,'0.0000');
							$numMov++;
						}
						
					}
					unset($_SESSION['empleadosDepositos']);
				}else{
					
					if($modenaCuentaBene!=1){
						$bene = $this->ChequesModel->InsertMov($numPoliza['id'],$numMov , $segmento, $sucursal, $cuentabeneficiario, "Abono M.E", $importedll , $concepto,$tipo.$beneficiario, $xml, $referencia, $formapago,$tc);
						$bene = $this->ChequesModel->InsertMov($numPoliza['id'],$numMov , $segmento, $sucursal, $cuentabeneficiario, "Abono", $importe, $concepto,$tipo.$beneficiario, $xml, $referencia, $formapago,'0.0000');
						$numMov++;
					}else{
						$bene = $this->ChequesModel->InsertMov($numPoliza['id'],$numMov , $segmento, $sucursal, $cuentabeneficiario, "Abono", $importe, $concepto,$tipo.$beneficiario, $xml, $referencia, $formapago,'0.0000');
						$numMov++;
					}
				}
				
				if($bene==true){
					if($concuentas){
						$iva = $importe / 1.16;
						$iva = $iva * .16;
						$ivaabono = $this->ChequesModel->InsertMov($numPoliza['id'], $numMov, $segmento, $sucursal, $cuentasConf['CuentaIVAPendienteCobro'], "Cargo", number_format($iva,2,'.',''), $concepto,'', $xml, $referencia, $formapago,'0.0000');
						$ivacargo = $this->ChequesModel->InsertMov($numPoliza['id'], $numMov+1, $segmento, $sucursal, $cuentasConf['CuentaIVAcobrado'], "Abono", number_format($iva,2,'.',''), $concepto,'', $xml, $referencia, $formapago,'0.0000');
					}
					
					/* mov xml a poliza */
		$cont= 0;$xmlsvalidos = array();
			$dirOrigen = $this->path('../cont/')."xmls/facturas/documentosbancarios/".$idDocumento;
			if ($vcarga = opendir($dirOrigen)){
				while($file = readdir($vcarga)){
					if ($file != "." && $file != ".." && $file!=".DS_Store"){
						if (!is_dir($dirOrigen.'/'.$file)){
							if(copy($dirOrigen.'/'.$file, $rutapoli.'/'.$file)){
							if(!in_array($file, $xmlsvalidos)){
								$xmlsvalidos[]= $file;
								$cont++;
							}
								
							}
						}
					}
				}
			}
				foreach($xmlsvalidos as $rutaxml){
					$uuid = explode('_', $rutaxml);
					$uuid = str_replace('.xml', '', $uuid[2]);
					if(!$uuid){
						$uuid = str_replace('.xml', '', $rutaxml);
					}
					$mov = $this->ChequesModel->movimientosPoliza($idDocumento);
					if($mov->num_rows>0){
						while($row = $mov->fetch_array()){
							if($cont>1){
								$this->ChequesModel->movMultipleFactUpdate($row['Id'], $row['IdPoliza'], $row['NumMovto'],$rutaxml, $uuid );
							}else{
							/* verifica si existen datos en el grupo
							 * si si solo agrega al grupo otro xml
							 * sino almacena no agrega al grupo y solo ase refrencia directa al mov */
								$grupo = $this->ChequesModel->verificagrupo($row['IdPoliza']);
								if($grupo==1){
									$this->ChequesModel->movMultipleFactUpdate($row['Id'], $row['IdPoliza'], $row['NumMovto'],$rutaxml, $uuid );
								}else{
									$this->ChequesModel->movUUID($uuid, $row['Id'],$rutaxml);
								}
							/* fin referencia encuentra */
							}
						}
					}
				
				}
		/* mov xml a poliza */
					return $numPoliza['id'];
				}else{
					return 0;
				}
			}			
		}
}
function creaPolizaAutomaticaIngresosSinProvision($segmento,$sucursal,$numeroformap,$idpoliza,$moneda,$manual,$tc,$beneficiario,$idDocumento,$concepto,$fecha,$numerocheque,$bancodestino,$cuentadestino,$idbancaria,$cuentabeneficiario,$importe,$referencia,$formapago,$cuentacontable,$idbeneficiario){
	$Exercise = $this->ChequesModel->getExerciseInfo();//pato
	if( $Ex = $Exercise->fetch_assoc() ){
		$idorg = $Ex['IdOrganizacion'];
		$idejer = $Ex['IdEx'];
		$idperio = $Ex['PeriodoActual'];}
		$info = $this->ChequesModel->infoConfiguracion();
		
		if( isset($_COOKIE['ejercicio']) ){
			$idperio = $_COOKIE['periodo'];
			$idejer = $this->ChequesModel->idex($_COOKIE['ejercicio'],'cont');
		}
		else{
			if(!$info['RFC']==""){
				$idejer = $this->ChequesModel->idex($info['EjercicioActual'],'cont');
				$idperio = $info['PeriodoActual'];
			}
		}
		$xml="";//$segmento=1;$sucursal=1;
		$fecha = date('Y-m-d', strtotime($fecha));
		
		if($idpoliza>0){
			$sinmov = $this->ChequesModel->eliminaMovimientosPoliza($idDocumento);
			if($sinmov==1){
				$poli = $this->ChequesModel->savePoliza($referencia,$idpoliza,$idorg, $idejer, $idperio, 1, $concepto, $fecha, 0, $numeroformap, '', 0, '', $idbancaria,$idDocumento,$idbeneficiario);
				$numPoliza['id'] = $idpoliza;
			}
		}else{
			$poli = $this->ChequesModel->savePoliza($referencia,0,$idorg, $idejer, $idperio, 1, $concepto, $fecha, 0, $numeroformap, '', 0, '', $idbancaria,$idDocumento,$idbeneficiario);
			$numPoliza = $this->ChequesModel->getLastNumPoliza();
		}
		if( $poli == 0 ){
			if($idbeneficiario==1){$tipo="2-";}elseif($idbeneficiario==5){ $tipo="1-";}
			
			//$numPoliza = $this->ChequesModel->getLastNumPoliza();
			$rutapoli 	= $this->path('../cont/')."xmls/facturas/" . $numPoliza['id'];
			if(!file_exists($rutapoli))
			{
				mkdir ($rutapoli, 0777);
			}
			if($manual==0){
				$ban = $this->ChequesModel->InsertMov($numPoliza['id'], 1, $segmento, $sucursal, $cuentacontable, "Cargo", $importe, $concepto,$tipo.$beneficiario, $xml, $referencia, $formapago,'0.0000');
			}else{
				if($moneda!=1){
					$ban = $this->ChequesModel->InsertMov($numPoliza['id'], 1, $segmento, $sucursal, $cuentacontable, "Cargo M.E.", $importe, $concepto,$tipo.$beneficiario, $xml, $referencia, $formapago,$tc);
					$ban = $this->ChequesModel->InsertMov($numPoliza['id'], 1, $segmento, $sucursal, $cuentacontable, "Cargo", number_format(floatval($importe * $tc),2,'.',''), $concepto,$tipo.$beneficiario, $xml, $referencia, $formapago,'0.0000');
				}else{
					$ban = $this->ChequesModel->InsertMov($numPoliza['id'], 1, $segmento, $sucursal, $cuentacontable, "Cargo", $importe, $concepto,$tipo.$beneficiario, $xml, $referencia, $formapago,'0.0000');
				}
			}
			if($ban==true){
				/* mov xml a poliza */
		$cont= 0;$xmlsvalidos = array();
			$dirOrigen = $this->path('../cont/')."xmls/facturas/documentosbancarios/".$idDocumento;
			if ($vcarga = opendir($dirOrigen)){
				while($file = readdir($vcarga)){
					if ($file != "." && $file != ".."){
						if (!is_dir($dirOrigen.'/'.$file)){
							if(copy($dirOrigen.'/'.$file, $rutapoli.'/'.$file)){
							if(!in_array($file, $xmlsvalidos)){
								$xmlsvalidos[]= $file;
								$cont++;
							}
								
							}
						}
					}
				}
			}
				foreach($xmlsvalidos as $rutaxml){
					$uuid = explode('_', $rutaxml);
					$uuid = str_replace('.xml', '', $uuid[2]);
					$mov = $this->ChequesModel->movimientosPoliza($idDocumento);
					if(!$uuid){
						$uuid = str_replace('.xml', '', $rutaxml);
					}
					if($mov->num_rows>0){
						while($row = $mov->fetch_array()){
							if($cont>1){
								$this->ChequesModel->movMultipleFactUpdate($row['Id'], $row['IdPoliza'], $row['NumMovto'],$rutaxml, $uuid );
							}else{
							/* verifica si existen datos en el grupo
							 * si si solo agrega al grupo otro xml
							 * sino almacena no agrega al grupo y solo ase refrencia directa al mov */
								$grupo = $this->ChequesModel->verificagrupo($row['IdPoliza']);
								if($grupo==1){
									$this->ChequesModel->movMultipleFactUpdate($row['Id'], $row['IdPoliza'], $row['NumMovto'],$rutaxml, $uuid );
								}else{
									$this->ChequesModel->movUUID($uuid, $row['Id'],$rutaxml);
								}
							/* fin referencia encuentra */
							}
						}
					}
				
				}
		/* mov xml a poliza */
				return $numPoliza['id'];
			}else{
				return 0;
			}
		}			
	
}
function creaPolizaAutomaticaIngresosManual(){//pato
	$_REQUEST['fecha'] = date('Y-m-d', strtotime($_REQUEST['fecha']));
	$Exercise = $this->ChequesModel->getExerciseInfo();
	if( $Ex = $Exercise->fetch_assoc() ){
		$idorg = $Ex['IdOrganizacion'];
		$idejer = $Ex['IdEx'];
		$idperio = $Ex['PeriodoActual'];
	}//por default estara la de acontia
	$info = $this->ChequesModel->infoConfiguracion();
	
	if( isset($_COOKIE['ejercicio']) ){//si existen cambios se consultara la tabla contia ejer para q la poliza quede bien
		$idperio = $_COOKIE['periodo'];
		$idejer = $this->ChequesModel->idex($_COOKIE['ejercicio'],'cont');
	}
	else{
		if(!$info['RFC']==""){// sino existe cambios la informacion de bancos consulta igual ejercicios acontia para que empate las polizas
			$idejer = $this->ChequesModel->idex($info['EjercicioActual'],'cont');
			$idperio = $info['PeriodoActual'];
		}
	}
		$xml="";$segmento=1;$sucursal=1;
		$cuenta = explode('//',   $_REQUEST['idbancaria']);//$b['idbancaria']."//".$b['account_id']."//".$b['coin_id']
		$datosBeneficiario = explode('/', $_REQUEST['cuentabeneficiario']);//$b['account_id']."/".$b['currency_id']
		$cuentacontable = $cuenta[1];
		$cuentabeneficiario = $datosBeneficiario[0];
		$idbancaria = $cuenta[0];
		$cuentamoneda = $cuenta[2];
		$modenaCuentaBene = $datosBeneficiario[1];
		$importe = $_REQUEST['importe'];
		$poli = $this->ChequesModel->savePoliza($_REQUEST['referencia'],0,$idorg, $idejer, $idperio, 1, $_REQUEST['concepto'], $_REQUEST['fecha'], 0, '', '', 0, $_REQUEST['numeroformapago'], $idbancaria,$_REQUEST['idDocumento'],$_REQUEST['idBeneficiario']);
		if( $poli == 0 ){
			$numMov = 1;
			$numPoliza = $this->ChequesModel->getLastNumPoliza();
			$rutapoli 	= $this->path('../cont/')."xmls/facturas/" . $numPoliza['id'];
			if(!file_exists($rutapoli))
			{
				mkdir ($rutapoli, 0777);
			}
			if($_REQUEST['idBeneficiario']==1){$tipo="2-";}elseif($_REQUEST['idBeneficiario']==5){ $tipo="1-";}elseif($_REQUEST['idBeneficiario']==2){ $tipo="1-";}
			if($cuentamoneda!=1){
					$ban = $this->ChequesModel->InsertMov($numPoliza['id'], $numMov, $segmento, $sucursal,$cuentacontable, "Cargo M.E.", number_format($_REQUEST['importe'],2,'.',''), $_REQUEST['concepto'],$tipo, '', $_REQUEST['referencia'], 0,$_REQUEST['tc']);
					$ban = $this->ChequesModel->InsertMov($numPoliza['id'], $numMov, $segmento, $sucursal, $cuentacontable, "Cargo", number_format(floatval($_REQUEST['importe']*$_REQUEST['tc']),2,'.',''), $_REQUEST['concepto'],$tipo, '', $_REQUEST['referencia'], 0,'0.0000');
					$numMov++;
					$importe =  number_format(floatval($_REQUEST['importe']*$_REQUEST['tc']),2,'.','');
				}else{
					$ban = $this->ChequesModel->InsertMov($numPoliza['id'], $numMov, $segmento, $sucursal, $cuentacontable, "Cargo", number_format($_REQUEST['importe'],2,'.',''), $_REQUEST['concepto'],$tipo, '', $_REQUEST['referencia'], 0,'0.0000');
					$numMov++;
				}
			if($ban==true){
				if($_REQUEST['deposito']){// si es un deposito tendra q crear un movimiento por cada proyectado
					if($cuentamoneda==1){
						$tc = 1;
					}
					foreach($_REQUEST['proyectados'] as $p){
						$idDoc = explode("/",$p);
						$infoB = $this->ChequesModel->idBeneficiario($idDoc[1]);
						if($infoB['beneficiario']==5){
							$cliente = $this->ChequesModel->clienteInfo($infoB['idbeneficiario']);
							$bene = $this->ChequesModel->InsertMov($numPoliza['id'], $numMov, $segmento, $sucursal, $cliente['cuenta'], "Abono", number_format(floatval($infoB['importe']*$tc),2,'.',''), $_REQUEST['concepto'],'1-'.$cliente['id'], $xml, $_REQUEST['referencia'], 0,'0.0000');
							$numMov++;
						}
						elseif($infoB['beneficiario']==1){
							$prv = $this->ChequesModel->datosproveedor($infoB['idbeneficiario']);
							$bene = $this->ChequesModel->InsertMov($numPoliza['id'], $numMov, $segmento, $sucursal, $prv['cuentacliente'], "Abono", number_format(floatval($infoB['importe']*$tc),2,'.',''), $_REQUEST['concepto'],'2-'.$prv['idPrv'], $xml, $_REQUEST['referencia'], 0,'0.0000');
							$numMov++;
						}
						elseif($infoB['beneficiario']==2){
							$bene = $this->ChequesModel->InsertMov($numPoliza['id'], $numMov, $segmento, $sucursal, $_SESSION['empleadosDepositos'][$infoB['idbeneficiario']], "Abono", number_format(floatval($infoB['importe']*$tcb),2,'.',''), $concepto,'emp-'.$infoB['idbeneficiario'], $xml, $referencia, $formapago,'0.0000');
							$numMov++;
						}
						
					}
					unset($_SESSION['empleadosDepositos']);
				}else{
				
					if($modenaCuentaBene!=1){
						$bene = $this->ChequesModel->InsertMov($numPoliza['id'], $numMov, $segmento, $sucursal, $cuentabeneficiario, "Abono M.E",$_REQUEST['importe'], $_REQUEST['concepto'],$tipo.$_REQUEST['beneficiario'], '', $_REQUEST['referencia'], 0,$_REQUEST['tc']);
						$bene = $this->ChequesModel->InsertMov($numPoliza['id'], $numMov, $segmento, $sucursal, $cuentabeneficiario, "Abono",$importe, $_REQUEST['concepto'],$tipo.$_REQUEST['beneficiario'], '', $_REQUEST['referencia'], 0,'0.0000');
						$numMov++;
						
					}else{
						$bene = $this->ChequesModel->InsertMov($numPoliza['id'], $numMov, $segmento, $sucursal, $cuentabeneficiario, "Abono",$importe, $_REQUEST['concepto'],$tipo.$_REQUEST['beneficiario'], '', $_REQUEST['referencia'], 0,'0.0000');
						$numMov++;
					}

				}	
					if($bene==true){
						/* mov xml a poliza */
		$cont= 0;$xmlsvalidos = array();
			$dirOrigen = $this->path('../cont/')."xmls/facturas/documentosbancarios/".$_REQUEST['idDocumento'];
			if ($vcarga = opendir($dirOrigen)){
				while($file = readdir($vcarga)){
					if ($file != "." && $file != ".."){
						if (!is_dir($dirOrigen.'/'.$file)){
							if(copy($dirOrigen.'/'.$file, $rutapoli.'/'.$file)){
							if(!in_array($file, $xmlsvalidos)){
								$xmlsvalidos[]= $file;
								$cont++;
							}
								
							}
						}
					}
				}
			}
				foreach($xmlsvalidos as $rutaxml){
					$uuid = explode('_', $rutaxml);
					$uuid = str_replace('.xml', '', $uuid[2]);
					$mov = $this->ChequesModel->movimientosPoliza($_REQUEST['idDocumento']);
					if(!$uuid){
						$uuid = str_replace('.xml', '', $rutaxml);
					}
					if($mov->num_rows>0){
						while($row = $mov->fetch_array()){
							if($cont>1){
								$this->ChequesModel->movMultipleFactUpdate($row['Id'], $row['IdPoliza'], $row['NumMovto'],$rutaxml, $uuid );
							}else{
							/* verifica si existen datos en el grupo
							 * si si solo agrega al grupo otro xml
							 * sino almacena no agrega al grupo y solo ase refrencia directa al mov */
								$grupo = $this->ChequesModel->verificagrupo($row['IdPoliza']);
								if($grupo==1){
									$this->ChequesModel->movMultipleFactUpdate($row['Id'], $row['IdPoliza'], $row['NumMovto'],$rutaxml, $uuid );
								}else{
									$this->ChequesModel->movUUID($uuid, $row['Id'],$rutaxml);
								}
							/* fin referencia encuentra */
							}
						}
					}
				
				}
		/* mov xml a poliza */
						echo $numPoliza['id'];
					}else{
						echo 0;
					}
				}			
		}else{ echo 0;}
}
	
function validaProyectados(){
	$sincuenta = $cuentasBeneficiarios = $cuentaEmpleados =  array();
	$cuentasAsiganacion = $this->ChequesModel->configCuentas();
	
	foreach ($_REQUEST['Nodepositado'] as $d){
		$idDoc = explode("/",$d);
		$beneficiario = $this->IngresosModel->proyectadoClienteCuenta($idDoc[1]);
		if($beneficiario){
			if($beneficiario['account_id']==0){
				$sincuenta[ $idDoc[1] ] = $beneficiario['nombre']."(Cliente)";
			}
		}
		$beneficiario2 = $this->IngresosModel->proyectadoProveCuenta($idDoc[1]);
		if($beneficiario2!=0){
			if($beneficiario2['account_id']==0 || $beneficiario2['account_id']==-1){
				$sincuenta[ $idDoc[1] ] = $beneficiario2['nombre']."(Beneficiario/Pagador)";
			}
		}
		$beneficiarioempl = $this->IngresosModel->proyectadoEmpleado($idDoc[1]);
		//$cuentasAsiganacion['CuentaSueldoxPagar']=="-1" || 
		if(!array_key_exists($beneficiarioempl['idbeneficiario'], $_SESSION['empleadosDepositos']) ){
			if($beneficiarioempl!=0){
				$cuentaEmpleados[ $idDoc[1] ] = $beneficiarioempl['nombre']." (Empleado)";
			}
		}
	}		//1-prv,2-acreedor,3-empleado,4-empresa,5-cliente
	if(empty($sincuenta) && empty($cuentaEmpleados)){
		echo 0;
	}else{
		$option= $option2 = "";$contenido="";
		$cuentasAfectables = $this->ChequesModel->cuentasAfectables(0);
		$afectable = $this->IngresosModel->cuentaClientesAsignar();
		while($in = $afectable->fetch_assoc()){
			$option .= "<option value='".$in['account_id']."'>".$in['description']."</option>";
		}
		while($in = $cuentasAfectables->fetch_assoc()){
			$option2 .= "<option value='".$in['account_id']."'>".$in['description']."</option>";
		}
		foreach ($sincuenta as $key => $be){ 
			$contenido.= "
				<tr>
				<td>$be
				<input type='hidden' name='ids[]' value='$key' class='idsp'>
				</td>
			 	<td>
			 	 <select name='cuentas[]' class='clasecuen'>
			  		$option
			 	 </select>
			 	 <td></tr>";
		}
		foreach ($cuentaEmpleados as $key => $be){ 
			$contenido.= "
				<tr>
				<td>$be
				<input type='hidden' name='ids[]' value='$key' class='idsp'>
				</td>
			 	<td>
			 	 <select name='cuentas[]' class='clasecuen'>
			  		$option2
			 	 </select>
			 	 <td></tr>";
		}
		echo $contenido;
		
  }
		
}
function asociaCuentasClientes(){
	for($i=0;$i<count($_REQUEST['ids']);$i++){
		$infoB = $this->ChequesModel->idBeneficiario($_REQUEST['ids'][$i]);
		$idtipo = $this->ChequesModel->datosproveedor($_REQUEST['ids'][$i]);
		//echo $infoB['beneficiario']."/".$infoB['idbeneficiario'];
		if($infoB['beneficiario']==5){//cliente
			$this->IngresosModel->updateClienteCuenta($_REQUEST['cuentasp'][$i], $infoB['idbeneficiario']);
		}elseif($infoB['beneficiario']==1 && $idtipo['idtipo']!=4){//prv
			$this->IngresosModel->updatePrvCuenta($_REQUEST['cuentasp'][$i], $infoB['idbeneficiario']);
		}elseif( $infoB['beneficiario']==2 ){
			//$this->IngresosModel->updateCuentaSueldoXpagar($_REQUEST['cuentasp'][$i]);
			$_SESSION['empleadosDepositos'][$infoB['idbeneficiario']] = $_REQUEST['cuentasp'][$i];
		}
	}
	
}	


function actualizacionCatalogos(){
	switch ($_REQUEST['opc']) {
		case 1://tipo documento
			$tipodocumento = $this->ChequesModel->tipodocumento($_REQUEST['tipo']);
			
			while($b=$tipodocumento->fetch_array()){
				echo "<option value=". $b['idTipoDoc']." >".$b['nombre']."</option>";
			}
				
			break;
		
		case 2://subclasificador
			if($_REQUEST['tipocla'] ==1 ){
				$clasificador = $this->ChequesModel->clasificador();
				while($c = $clasificador->fetch_array()){
					echo "<option value=".$c['id'].">".$c['nombreclasificador']."(".$c['codigo'].")</option>";
				}
			}else{
				$clasificador = $this->ChequesModel->clasificadorIngre();
				while($c = $clasificador->fetch_array()){
					echo "<option value=".$c['id'].">".$c['nombreclasificador']."(".$c['codigo'].")</option>";
				}
			}

			break;
		
		case 3:
			$cuentasbancarias = $this->ChequesModel->cuentasbancariaslista();
			while($b=$cuentasbancarias->fetch_array()){
				echo "<option value=".$b['idbancaria']."//".$b['account_id']."//".$b['coin_id']." >".$b['nombre']." (".$b['cuenta'].")</option>";
			}
			
			break;
		case 4://pagador ingresos
			if( $_REQUEST['empleado']==0){
				$clientes = $this->IngresosModel->catalogoCliente();
				while($c = $clientes->fetch_array()){
					echo '<option value="'.$c['cuenta'].'/'.$c['id'].'/5" '. $se.'>'. $c['nombre'].'</option>';
				} 
				$proveedores = $this->IngresosModel->proveedorBeneficiario();
				while($c = $proveedores->fetch_array()){ 
					echo '<option value="'.$c['cuentacliente'].'/'. $c['idPrv'].'/1/'.$c['idtipo'].'" '.$seprv.'>'.$c['razon_social'].'</option>';
				} 
			}else{
				$empleados = $this->ChequesModel->empleados();
				while($c = $empleados->fetch_array()){
					echo '<option value="'.$cuentasAsiganacion['CuentaSueldoxPagar'].'/'. $c['idEmpleado'].'/2 " '. $sel.' >'.$c['nombreEmpleado'].' '.$c['apellidoPaterno'].' ('.$c['codigo'].')</option>';
				}
			}
			break;
		case 5://pagador egresos
			if( $_REQUEST['empleado']==0){
				$proveedores = $this->ChequesModel->proveedor();
				while($c = $proveedores->fetch_array()){
					echo "<option value='".$c['cuenta']."/". $c['idPrv']."/1/".$c['idtipo']."' ".$se.">".$c['razon_social']."</option>";
				} 
			}else{
				$empleados = $this->ChequesModel->empleados();
				while($c = $empleados->fetch_array()){
					echo '<option value="'.$cuentasAsiganacion['CuentaSueldoxPagar'].'/'. $c['idEmpleado'].'/2 " '. $sel.' >'.$c['nombreEmpleado'].' '.$c['apellidoPaterno'].' ('.$c['codigo'].')</option>';
				}
			}
			break;
			
			
	}
	
}
	
}