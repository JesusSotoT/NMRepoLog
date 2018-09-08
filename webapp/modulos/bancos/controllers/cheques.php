<?php
   require('common.php');

//Carga el modelo para este controlador
require("models/cheques.php");

class Cheques extends Common
{
	public $ChequesModel;
	
	function __construct()
	{
		
		$this->ChequesModel = new ChequesModel();
		$this->ChequesModel->connect();
	}

	function __destruct()
	{
		

		$this->ChequesModel->close();
	}
	// C H E Q U E S // 
	function vercheque(){
		$acontia = $this->ChequesModel->validaAcontia();
		$appministra = $this->ChequesModel->validaAppministra();
		$cuentasbancarias = $this->ChequesModel->cuentasbancariaslista();
		$moneda = $this->ChequesModel->moneda();
		$sqlprov = $this->ChequesModel->proveedor();
		$empleados = $this->ChequesModel->empleados();
		$tipodocumento = $this->ChequesModel->tipodocumento(1);
		$cuentasAfectables = $this->ChequesModel->cuentasAfectables(0);
		$listaconceptos = $this->ChequesModel->concepto(2);
		$clasificador = $this->ChequesModel->clasificador();
		//$clientesBeneficiario = $this->ChequesModel->clienteBeneficiario();
		$clasificadorsub = $this->ChequesModel->clasificador();
		$formapago  = $this->ChequesModel->formapago();
		$segmento	= $this->ChequesModel->segmento();
		$sucursal	= $this->ChequesModel->sucursal();
		$bancos = $this->ChequesModel->bancos();
		$info = $this->ChequesModel->infoConfiguracion();
		$cuentasAsiganacion = $this->ChequesModel->configCuentas();
		$cuentasEmpleado = $this->ChequesModel->pasivoCirculante();
		//anticipos
		$usuarios = $this->ChequesModel->usuarios();
		//
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
			$datos = $this->ChequesModel->editarDocumento($_REQUEST['editar']);
			if($datos['idmoneda']!=1){
				$cuentasAfectables = $this->ChequesModel->cuentasAfectables(1);
			}
			$appPagos = $this->ChequesModel->pagosConDocumento($_REQUEST['editar'], $datos['idbeneficiario'], 1);
			$traspasoDocdestino = $this->ChequesModel->documentosDestinotraspaso($_REQUEST['editar']);
			$subcategoriasAsignadas = $this->ChequesModel->consultaSubcategoriasDoc($_REQUEST['editar']);
		}else{
			
			
			if($_SESSION['newcheque']){
				$comprueba = $this->ChequesModel->compruebaDisponibilidad($_SESSION['newcheque']);
				if($comprueba!=0){//esq ya se ocupo
					$idtemporal = $this->ChequesModel->InsertDocumentoBasico(1);
					$_SESSION['newcheque'] = $idtemporal;
				}
				else{
					$existe = $this->ChequesModel->compruebaExistencia($_SESSION['newcheque']);
					if($existe==0){//si es igual a 0 es qno existe y trae algo loco la session 
						$idtemporal = $this->ChequesModel->InsertDocumentoBasico(1);
						$_SESSION['newcheque'] = $idtemporal;
					}
				}
			}else{
				$idtemporal = $this->ChequesModel->InsertDocumentoBasico(1);
				$_SESSION['newcheque'] = $idtemporal;
			}
			// if(!isset($_SESSION['newcheque'])){
				// $idtemporal = $this->ChequesModel->InsertDocumentoBasico(1);
				// $_SESSION['newcheque'] = $idtemporal;
			// }
		}
		require('views/documentos/cheque.php');
	}
	function letra(){
		require 'libraries/letranumero.php';
		$v=new EnLetras(); 
 		$con_letra=strtoupper($v->ValorEnLetras($_REQUEST['importe'],$_REQUEST['moneda'],$_REQUEST['simbolo'])); 
		echo $con_letra;
	}
	function buscanumerocheque(){
		echo $this->ChequesModel->buscanumerocheque($_REQUEST['idbancaria']);
	}
	function beneficiariocheques(){
		
	}
	function saldocuenta(){
		$parsea = explode('-',date('Y-m-d', strtotime($_REQUEST['fecha'])));
		$idejercicio = $this->ChequesModel->ejercicio($parsea[0]);
		$saldo = $this->ChequesModel->saldocuenta($_REQUEST['idbancaria'],date('Y-m-d', strtotime($_REQUEST['fecha'])),$_REQUEST['cuenta']);
		//$saldobancario = $this->ChequesModel->saldocuentabancario($_REQUEST['idbancaria'],$_REQUEST['fecha'],$_REQUEST['cuenta']);
		echo $saldo;
	}
	
	function validarangonumero(){//ver si esta en uso
		$saldo = $this->ChequesModel->buscanumerocheque($_REQUEST['idbancaria']);
		if($row=$saldo->fetch_array()){
			if($row['actualrango']){
				echo "1//".$row['numeroinicial']."//".$row['numerofinal']."//".$row['actualrango'];
			}
			if($row['numeroactual']){
				echo '0//'.$row['numeroactual'];	
			}
		}
		
	}
	function crearpoliza(){//pato
	
		$cuenta = explode('//',   $_REQUEST['idbancaria']);//$b['idbancaria']."//".$b['account_id']."//".$b['coin_id']
		$datosBeneficiario = explode('/', $_REQUEST['cuentabeneficiario']);//$b['account_id']."/".$b['currency_id']
		$cuentacontable = $cuenta[1];
		$cuentabeneficiario = $datosBeneficiario[0];
		$idbancaria = $cuenta[0];
		$cuentamoneda = $cuenta[2];
		$_REQUEST['importe'] = str_replace(',', '', $_REQUEST['importe']);
		if($_REQUEST["tipopoliza"]==1){//Pago con Provision sin IVA
			$polizaAut = $this->creaPolizaAutomaticaIVA($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],0, $_REQUEST['idbeneficiario'], 0, $_REQUEST['beneficiario'], $_REQUEST['idDocumento'], $_REQUEST['concepto'], $_REQUEST['fecha'], $_REQUEST['numerocheque'], $_REQUEST['bancodestino'], $_REQUEST['cuentadestino'], $_REQUEST['idbancaria'], $_REQUEST['cuentabeneficiario'],  $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'], $_REQUEST['tc']);
			echo $polizaAut."/0";
		}elseif($_REQUEST["tipopoliza"]==2){//Pago con Provision con IVA
	
			$cuentasConf = $this->ChequesModel->configCuentas();
		
			if($cuentasConf['CuentaIVAPendientePago']!=-1 && $cuentasConf['CuentaIVApagado']!=-1){
			
				$polizaAut = $this->creaPolizaAutomaticaIVA($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],0, $_REQUEST['idbeneficiario'], 1, $_REQUEST['beneficiario'], $_REQUEST['idDocumento'], $_REQUEST['concepto'], $_REQUEST['fecha'], $_REQUEST['numerocheque'], $_REQUEST['bancodestino'], $_REQUEST['cuentadestino'], $_REQUEST['idbancaria'], $_REQUEST['cuentabeneficiario'],  $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'],  $_REQUEST['tc']);
				echo $polizaAut."/0";
			}else{//poliza con iva pero sin tener asignadas cuentas crea la poliza pero sin los movmientos de iva
			
				$polizaAut = $this->creaPolizaAutomaticaIVA($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],0, $_REQUEST['idbeneficiario'], 0, $_REQUEST['beneficiario'], $_REQUEST['idDocumento'], $_REQUEST['concepto'], $_REQUEST['fecha'], $_REQUEST['numerocheque'], $_REQUEST['bancodestino'], $_REQUEST['cuentadestino'], $_REQUEST['idbancaria'], $_REQUEST['cuentabeneficiario'],  $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'],  $_REQUEST['tc']);
				echo  $polizaAut."/1";
		
			}
		}elseif($_REQUEST["tipopoliza"]==3 || $_REQUEST["tipopoliza"]==0){//Pago sin Provision
			$polizaAut = $this->creaPolizaAutomaticasinProvision($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],0, $cuentamoneda, 1, $_REQUEST['tc'], $_REQUEST['idbeneficiario'], $_REQUEST['beneficiario'], $_REQUEST['idDocumento'], $_REQUEST['concepto'], $_REQUEST['fecha'], $_REQUEST['numerocheque'], $_REQUEST['bancodestino'], $_REQUEST['cuentadestino'], $idbancaria, $_REQUEST['cuentabeneficiario'],  $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'], $cuentacontable);
			echo $polizaAut."/0";
		}
		// $Exercise = $this->ChequesModel->getExerciseInfo();
		// if( $Ex = $Exercise->fetch_assoc() ){
		// $idorg = $Ex['IdOrganizacion'];
		// $idejer = $Ex['IdEx'];
		// $idperio = $Ex['PeriodoActual'];}//por default estara la de acontia
		// $info = $this->ChequesModel->infoConfiguracion();
// 		
		// if( isset($_COOKIE['ejercicio']) ){//si existen cambios se consultara la tabla contia ejer para q la poliza quede bien
			// $idperio = $_COOKIE['periodo'];
			// $idejer = $this->ChequesModel->idex($_COOKIE['ejercicio'],'cont');
		// }
		// else{
			// if(!$info['RFC']==""){// sino existe cambios la informacion de bancos consulta igual ejercicios acontia para que empate las polizas
				// $idejer = $this->ChequesModel->idex($info['EjercicioActual'],'cont');
				// $idperio = $info['PeriodoActual'];
			// }
		// }
		// $cuentasConf = $this->ChequesModel->configCuentas();
// 		
		// if($_REQUEST['idbeneficiario']==1){
			// $dato = $this->ChequesModel->datosproveedor($_REQUEST['beneficiario']);
			// $rfc = $dato['rfc'];
			// $tipo = '2-';
// 			
		// }if($_REQUEST['idbeneficiario']==5){//clientebeneficiario
			// $dato = $this->ChequesModel->clienteInfo($_REQUEST['beneficiario']);
			// $rfc = $dato['rfc'];
			// $tipo = '1-';
		// }
		// if($_REQUEST['idbeneficiario']==2){//empleado
			// $dato = $this->ChequesModel->datosempleados($_REQUEST['beneficiario']);
			// $rfc = $dato['rfc'];
			// $tipo = "";
		// }else{//traspaso rfc organizacion
			// $rfc = $cuentasConf['RFC'];
			// $tipo = "";
			// $idbeneficiario=6;
		// }
// 		
		// $xml="";$segmento=1;$sucursal=1;
		// $idDocumento = $_REQUEST['idDocumento'];
		// $cuenta = explode('//',   $_REQUEST['idbancaria']);//$b['idbancaria']."//".$b['account_id']."//".$b['coin_id']
		// $datosBeneficiario = explode('/', $_REQUEST['cuentabeneficiario']);//$b['account_id']."/".$b['currency_id']
		// $cuentacontable = $cuenta[1];
		// $cuentabeneficiario = $datosBeneficiario[0];
		// $idbancaria = $cuenta[0];
		// $cuentamoneda = $cuenta[2];
		// $modenaCuentaBene = $datosBeneficiario[1];
		// $_REQUEST['importe'] = str_replace(',', '', $_REQUEST['importe']);
// 		
		// $importe = $_REQUEST['importe'];
		// $poli = $this->ChequesModel->savePoliza(0,$idorg, $idejer, $idperio, 2, $_REQUEST['concepto'], $_REQUEST['fecha'], $_REQUEST['beneficiario'], $_REQUEST['numerocheque'], $rfc, $_REQUEST['bancodestino'], $_REQUEST['cuentadestino'],$cuenta[0],$idDocumento,$_REQUEST['idbeneficiario']);
		// if( $poli == 0 ){
			// $numPoliza = $this->ChequesModel->getLastNumPoliza();
			// $rutapoli 	= $this->path('../cont/')."xmls/facturas/" . $numPoliza['id'];
			// if(!file_exists($rutapoli))
			// {
				// mkdir ($rutapoli, 0777);
			// }
// 			
			// if($cuentamoneda!=1){
					// $ban = $this->ChequesModel->InsertMov($numPoliza['id'], 1, $segmento, $sucursal, $cuentacontable, "Abono M.E", number_format($_REQUEST['importe'],2,'.',''), $_REQUEST['concepto'],$tipo, $xml, $_REQUEST['referencia'], $_REQUEST['formapago'],$_REQUEST['tc']);
					// $ban = $this->ChequesModel->InsertMov($numPoliza['id'], 1, $segmento, $sucursal, $cuentacontable, "Abono", number_format($_REQUEST['importe']*$_REQUEST['tc'],2,'.',''), $_REQUEST['concepto'],$tipo, $xml, $_REQUEST['referencia'], $_REQUEST['formapago'],'0.0000');
					// $importedll = number_format($_REQUEST['importe'],2,'.','');
					// $importe = number_format(floatval($_REQUEST['importe'] * $_REQUEST['tc']),2,'.','');
// 					
				// }else{
					// $ban = $this->ChequesModel->InsertMov($numPoliza['id'], 1, $segmento, $sucursal, $cuentacontable, "Abono", number_format($_REQUEST['importe'],2,'.',''), $_REQUEST['concepto'],$tipo, $xml, $_REQUEST['referencia'], $_REQUEST['formapago'],'0.0000');
				// }
			// if($ban==true){
				// if($modenaCuentaBene!=1){
					// $bene = $this->ChequesModel->InsertMov($numPoliza['id'], 2, $segmento, $sucursal, $cuentabeneficiario, "Cargo M.E.", $importedll, $_REQUEST['concepto'],$tipo.$_REQUEST['beneficiario'], $xml, $_REQUEST['referencia'], $_REQUEST['formapago'],$_REQUEST['tc']);
					// $bene = $this->ChequesModel->InsertMov($numPoliza['id'], 2, $segmento, $sucursal, $cuentabeneficiario, "Cargo", $importe, $_REQUEST['concepto'],$tipo.$_REQUEST['beneficiario'], $xml, $_REQUEST['referencia'], $_REQUEST['formapago'],'0.0000');
// 					
				// }else{ 
					// $bene = $this->ChequesModel->InsertMov($numPoliza['id'], 2, $segmento, $sucursal, $cuentabeneficiario, "Cargo", number_format($importe,2,'.',''), $_REQUEST['concepto'],$tipo.$_REQUEST['beneficiario'], $xml, $_REQUEST['referencia'], $_REQUEST['formapago'],'0.0000');
				// }
// 				
				// if($bene==true){
							// /* mov xml a poliza */
// 							
					// $cont= 0;$xmlsvalidos = array();
						// $dirOrigen = $this->path('../cont/')."xmls/facturas/documentosbancarios/".$idDocumento;
						// if ($vcarga = opendir($dirOrigen)){
							// while($file = readdir($vcarga)){
								// if ($file != "." && $file != ".."){
									// if (!is_dir($dirOrigen.'/'.$file)){
										// if(copy($dirOrigen.'/'.$file, $rutapoli.'/'.$file)){
										// if(!in_array($file, $xmlsvalidos)){
											// $xmlsvalidos[]= $file;
											// $cont++;
										// }
// 											
										// }
									// }
								// }
							// }
						// }
							// foreach($xmlsvalidos as $rutaxml){
								// $uuid = explode('_', $rutaxml);
								// $uuid = str_replace('.xml', '', $uuid[2]);
								// $mov = $this->ChequesModel->movimientosPoliza($idDocumento);
								// if($mov->num_rows>0){
									// while($row = $mov->fetch_array()){
										// if($cont>1){
											// $this->ChequesModel->movMultipleFactUpdate($row['Id'], $row['IdPoliza'], $row['NumMovto'],$rutaxml, $uuid );
										// }else{
										// /* verifica si existen datos en el grupo
										 // * si si solo agrega al grupo otro xml
										 // * sino almacena no agrega al grupo y solo ase refrencia directa al mov */
											// $grupo = $this->ChequesModel->verificagrupo($row['IdPoliza']);
											// if($grupo==1){
												// $this->ChequesModel->movMultipleFactUpdate($row['Id'], $row['IdPoliza'], $row['NumMovto'],$rutaxml, $uuid );
											// }else{
												// $this->ChequesModel->movUUID($uuid, $row['Id'],$rutaxml);
											// }
										// /* fin referencia encuentra */
										// }
									// }
								// }
// 							
							// }
					// /* mov xml a poliza */
					// echo $numPoliza['id'];
				// }else{
					// echo 0;
				// }
			// }		
		// }
	}
	function crearcheque(){//ganzoc
		$cuenta = explode('//',  $_REQUEST['cuenta']);//$b['idbancaria']."//".$b['account_id']."//".$b['coin_id']
		$paguese2 = explode('/',  $_REQUEST['paguese2']);
		$pagador = explode('/',  $_REQUEST['pagador']);
		$traspaso = explode('/',$_REQUEST['listatraspaso']);
		$tipobeneficiario = 0;
		$bdestino=explode('/', $_REQUEST['bancodestino']);
		$_REQUEST['bancodestino']=$bdestino[0];
		if($pagador[2]==1){//comprueba que el beneficiario sea un proveedor
		$dato = $this->ChequesModel->datosproveedor( $pagador[1] );
		
		//if( $r = $dato->fetch_array() ){
			if($dato['idtipotercero']==7){//no calcula iva acreedor
				$tipobeneficiario = 3;//tipo 2 es empleado
			}else{
				$tipobeneficiario = 1;//Proveedor
			}
		}else{
			$tipobeneficiario = $pagador[2];
		}
		if($_REQUEST['statustraspaso']==0){
			if($pagador[0]=="" || $pagador[0]==0 || $pagador[0]==-1){
				$cuentabeneficiario = $_REQUEST['paguese2'];
				if($paguese2[1]==1 &&  $pagador[3]!=4){// si la cuenta elejida es 1 pesos almacena
					if($tipobeneficiario==5){//cliente
						$this->ChequesModel->updateClienteCuentaEgre($pagador[1], $paguese2[0]);
					}elseif($tipobeneficiario==1){//prv
						$this->ChequesModel->updatePrvCuentaEgre($pagador[1], $paguese2[0]);
					}
				}
			}else{
				$cuentabeneficiario = $pagador[0]."/1";
			}
		}else{
			$cuentabeneficiario = $traspaso[3].'/'.$traspaso[4];//cuenta contable
		}
		//}
		$_REQUEST['importe'] = str_replace(',', '', $_REQUEST['importe']);
		$_REQUEST['fecha'] = date('Y-m-d', strtotime($_REQUEST['fecha']));
		if($_REQUEST['statusanticipo']==0){$_REQUEST['usuarios']=0;}
		if( isset($_REQUEST['id']) ){//si existe es edicion
			$previos = $this->ChequesModel->benePrevio($_REQUEST['id']);
			$crea  = $this->ChequesModel->actualizaEgreso(1,$_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],1,$_REQUEST['id'],$_REQUEST['fecha'], $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['textarea'],$cuenta[0], $tipobeneficiario, $pagador[1], $_REQUEST['clasificador'],$_REQUEST['idDocumento'],$_REQUEST['tipodocumento'],$_REQUEST['bancodestino'],$_REQUEST['numcuentadestino'],$_REQUEST['formapago'],$_REQUEST['proceso'],$cuenta[2],$_REQUEST['cambio'],$_REQUEST['numero'],$_REQUEST['tipoPoliza'],$_REQUEST['statustraspaso'],0,$_REQUEST['statusanticipo'],$_REQUEST['usuarios']);//$cuenta[2] es la moneda
			if($crea){
				$idDoc = $_REQUEST['id'];
				$reload = "window.location = 'index.php?".$_REQUEST['link']."';";
				$actual = '(Actualizado)';
				$act = "actualizar";
			}
		
		}else{
			//$idDoc = $this->ChequesModel->InsertCheque($_REQUEST['fecha'], $_REQUEST['numero'], $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['textarea'], $cuenta[0], $tipobeneficiario ,$pagador[1] ,$_REQUEST['proceso'],$_REQUEST['clasificador'],1,$_REQUEST['cambio'],$cuenta[2],$_REQUEST['tipodocumento'],$_REQUEST['bancodestino'],$_REQUEST['numcuentadestino'],$_REQUEST['tipoPoliza']);	
			//1-cheque,2-ingre,3-ingrenodepo,4-depo,5-egre se agrego esto para cuando cree tome el numero siguente
			$crea  = $this->ChequesModel->actualizaEgreso(1,$_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],0,$_REQUEST['idtemporal'],$_REQUEST['fecha'], $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['textarea'],$cuenta[0], $tipobeneficiario, $pagador[1], $_REQUEST['clasificador'],$_REQUEST['idDocumento'],$_REQUEST['tipodocumento'],$_REQUEST['bancodestino'],$_REQUEST['numcuentadestino'],$_REQUEST['formapago'],$_REQUEST['proceso'],$cuenta[2],$_REQUEST['cambio'],$_REQUEST['numero'],$_REQUEST['tipoPoliza'],$_REQUEST['statustraspaso'],0,$_REQUEST['statusanticipo'],$_REQUEST['usuarios']);//$cuenta[2] es la moneda
			if($crea){
				$idDoc = $crea;
				$reload = 'window.location = "index.php?c=Cheques&f=vercheque";'; 
				$actual = "(Creado)";
				$act = "crear";
			}
		}
		if($idDoc){
			unset($_SESSION['newcheque']);
			if($_REQUEST['statustraspaso']==1){
				$importetraspaso = $_REQUEST['importe'];
				if($traspaso[4]==1){// si es d pesos
					if($cuenta[2]!=1){//pero lacuenta origen es de ME
						$importetraspaso = $_REQUEST['importe']*$_REQUEST['cambio'];
					}
				}else{
					if($cuenta[2]==1){//pero lacuenta origen es de pesos
						$importetraspaso = $_REQUEST['importe']/$_REQUEST['cambio'];
					}
				}
				if($_REQUEST['listadoctraspaso']==1){//no depositado
					$this->ChequesModel->creaIngresoNoDepositadoTraspaso($_REQUEST['fecha'], number_format($importetraspaso,2,'.',''), $_REQUEST['referencia'], $_REQUEST['textarea'], $traspaso[0], 0, 0, $_REQUEST['clasificador'], $traspaso[4], 4, 3, $_REQUEST['cambio'],$idDoc);
				}else{//deposito
					$this->ChequesModel->creaDepositoTraspaso($_REQUEST['fecha'],number_format($importetraspaso,2,'.',''), $_REQUEST['referencia'], $_REQUEST['textarea'], $traspaso[0], $_REQUEST['formapago'],1,$traspaso[4],$_REQUEST['cambio'],$idDoc);
					
				}
			}
			$actualizanumerocheq = $this->ChequesModel->actuliazanumerodocumento($_REQUEST['numero'], $cuenta[0]);
			$ok = $this->ChequesModel->eliminaSubcategoriaDoc($idDoc);
			if($ok==1){
				if(isset($_REQUEST['subcategorias'])){
					$cont=0;
					foreach($_REQUEST['subcategorias'] as $ca){
						if($cont!=0){
							$this->ChequesModel->documentosSubcategorias($idDoc, $_REQUEST['subcategorias'][$cont],$_REQUEST['porcentaje'][$cont-1], number_format(($_REQUEST['porcentaje'][$cont-1]/100) * $_REQUEST['importe'] ,2,'.',''));
						}
						$cont++;
					}
				} 
			}
			if( isset($_REQUEST['id']) ){
				/* se queda solo asi en caso de que
				 * appministra maneje cxc o cxp de empleados 
				 * si debe verificar el beneficiario(tipo)*/
				if($previos->idbeneficiario != $pagador[1]){
					$this->ChequesModel->eliminaRegistrocxccxp($idDoc,$previos->idbeneficiario);
				}
				
			}
			if(isset($_REQUEST['cargosapp'])){
				$cont=0;
				foreach($_REQUEST['cargosapp'] as $cargo){
					$cargosapp = explode('/', $_REQUEST['cargosapp'][$cont]);
					$impApp = $cargosapp[0];
					$idCargo = $cargosapp[1];
					$idPago = $this->ChequesModel->almacenaPago(1, $pagador[1], number_format($impApp,2,'.',''), $_REQUEST['fecha'], $_REQUEST['textarea'], $_REQUEST['formapago'], $cuenta[2] , $_REQUEST['cambio'],$idDoc,"Cheque");
					if($idPago){
						$this->ChequesModel->almacenaPagoRelacion($idPago,$idCargo,number_format($impApp,2,'.',''),0);
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
					$idPago = $this->ChequesModel->almacenaPago(1, $pagador[1], number_format($impApp,2,'.',''), $_REQUEST['fecha'], $_REQUEST['textarea'], $_REQUEST['formapago'], $cuenta[2] , $_REQUEST['cambio'],$idDoc,"Cheque");
					if($idPago){
						$this->ChequesModel->almacenaPagoRelacion($idPago,$idCargo,number_format($impApp,2,'.',''),1);
						if($xmlfact){
							if($montoOriginal==$impApp){
								rename($this->path('../cont/')."xmls/facturas/temporales/$xmlfact", $ruta.$xmlfact);
							}else{
								copy($this->path('../cont/')."xmls/facturas/temporales/$xmlfact", $ruta.$xmlfact);
							}
						}
								
					}
					$cont++;
				}
			}
			
			
			
			if($_REQUEST['automatica']==1){
				//$elimina = $this->ChequesModel->eliminaPolizaDocumento($idDoc);
				$verifica = $this->verficaPolizaLocal($idDoc);
					
				//if($act=='crear'){ $imp = $this->imprimirChequeAct($idDoc);}else{ $imp="";}
				if($_REQUEST['tipoPoliza']==1 || $_REQUEST['statustraspaso']==1 || $_REQUEST['statusanticipo']==1){
					
					$polizaAut = $this->creaPolizaAutomaticaIVA($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],$verifica,$pagador[2],0,$pagador[1],$idDoc, $_REQUEST['textarea'], $_REQUEST['fecha'], $_REQUEST['numero'], $_REQUEST['bancodestino'],$_REQUEST['numcuentadestino'] , $_REQUEST['cuenta'], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'],$_REQUEST['cambio'],$_REQUEST['statusanticipo'],$_REQUEST['usuarios']);
					if($polizaAut!=0){
						echo "<script>alert('Documento y Poliza $actual');  " .$this->mandaPoliza($polizaAut,"$reload",$pagador[1],1,$tipobeneficiario,$_REQUEST['tipoPoliza']).";   </script>";
					}
						
				}elseif($_REQUEST['tipoPoliza']==2){//con iva
					$cuentasConf = $this->ChequesModel->configCuentas();
					if($cuentasConf['CuentaIVAPendientePago']!=-1 && $cuentasConf['CuentaIVApagado']!=-1){
						$polizaAut = $this->creaPolizaAutomaticaIVA($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],$verifica,$pagador[2],1,$pagador[1], $idDoc, $_REQUEST['textarea'], $_REQUEST['fecha'], $_REQUEST['numero'], $_REQUEST['bancodestino'],$_REQUEST['numcuentadestino'] ,$_REQUEST['cuenta'], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'],$_REQUEST['cambio']);
						if($polizaAut!=0){
							echo "<script>alert('Documento y Poliza $actual');   ".$this->mandaPoliza($polizaAut,"$reload",$pagador[1],1,$tipobeneficiario,$_REQUEST['tipoPoliza']).";  </script>";
						}
							
					}else{//poliza con iva pero sin tener asignadas cuentas crea la poliza pero sin los movmientos de iva
						$polizaAut = $this->creaPolizaAutomaticaIVA($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],$verifica,$pagador[2],0,$pagador[1], $idDoc, $_REQUEST['textarea'], $_REQUEST['fecha'], $_REQUEST['numero'], $_REQUEST['bancodestino'],$_REQUEST['numcuentadestino'] , $_REQUEST['cuenta'], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'],$_REQUEST['cambio']);
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
				}elseif($_REQUEST['tipoPoliza']==3){//sin provision
					$polizaAut = $this->creaPolizaAutomaticasinProvision($verifica,$cuenta[2],0,0,$pagador[2],$pagador[1], $idDoc, $_REQUEST['textarea'], $_REQUEST['fecha'], $_REQUEST['numero'], $_REQUEST['bancodestino'],$_REQUEST['numcuentadestino'] , $cuenta[0], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'],$cuenta[1]);
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
				
			}elseif($_REQUEST['automatica']==0 && $_REQUEST['acontia']==1 ){
				if( isset($_REQUEST['id']) ){
			 		$verifica = $this->verficaPolizaLocal($_REQUEST['id']);
					if($verifica!=0){
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
						if($_REQUEST['statustraspaso']==1){
							$polizaAut = $this->creaPolizaAutomaticaIVA($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],0,$verifica,$pagador[2],0,$pagador[1],$idDoc, $_REQUEST['textarea'], $_REQUEST['fecha'], $_REQUEST['numero'], $_REQUEST['bancodestino'],$_REQUEST['numcuentadestino'] , $_REQUEST['cuenta'], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'],$_REQUEST['cambio']);
						}else{
							$polizaAut = $this->creaPolizaAutomaticasinProvision(0,$cuenta[2],1,$_REQUEST['cambio'],$pagador[2],$pagador[1], $idDoc, $_REQUEST['textarea'], $_REQUEST['fecha'], $_REQUEST['numero'], $_REQUEST['bancodestino'],$_REQUEST['numcuentadestino'] , $cuenta[0], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'],$cuenta[1]);
						}
							
						
						echo "<script>alert('Documento $actual');
							alert('Sera enviado a la Poliza');
									".$this->mandaPolizaManual($polizaAut,"$reload")
									."
						</script>";
					}
			 		
				}else{
					if($_REQUEST['statustraspaso']==1){
						$polizaAut = $this->creaPolizaAutomaticaIVA($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],0,$pagador[2],0,$pagador[1],$idDoc, $_REQUEST['textarea'], $_REQUEST['fecha'], $_REQUEST['numero'], $_REQUEST['bancodestino'],$_REQUEST['numcuentadestino'] , $_REQUEST['cuenta'], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'],$_REQUEST['cambio']);
					}else{
						$polizaAut = $this->creaPolizaAutomaticasinProvision($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],0,$cuenta[2],1,$_REQUEST['cambio'],$pagador[2],$pagador[1], $idDoc, $_REQUEST['textarea'], $_REQUEST['fecha'], $_REQUEST['numero'], $_REQUEST['bancodestino'],$_REQUEST['numcuentadestino'] , $cuenta[0], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'],$cuenta[1]);
					}
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
		
		}else{
			echo "<script>alert('Error al $act documento Intente de nuevo!'); $reload </script>";
		}
	}
	function mandaPoliza($poli,$reload,$beneficiario,$egreso,$tipobeneficiario,$tipopoliza){
		$iva="";	
		if(($tipobeneficiario!=2) && ($tipopoliza==2)){
			if($egreso==1){ $iva = "+&im=2&prv=".$beneficiario;}
			if($egreso==0){ $iva = "+&im=1"; }
		}

		return "
		if(confirm('Desea ver la poliza?')){
			$reload
			window.parent.preguntar=false;
			window.parent.quitartab('tb0',0,'Polizas');
			window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&bancos=1&id=".$poli."$iva','Polizas','',0);
			window.parent.preguntar=true;	
		
		}else{
			$reload
		}
		";
	}
	function mandaPolizaManual($poli,$reload){		
			
		
		return "
			$reload
			window.parent.preguntar=false;
			window.parent.quitartab('tb0',0,'Polizas');
			window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&bancos=1&id=".$poli."','Polizas','',0);
			window.parent.preguntar=true;
			
		
		";
	}
	function imprimirChequeAct($idDocumento){
		return "
		if(confirm('Desea imprimir el cheque?')){
		".$this->ChequesModel->updateimpreso($idDocumento)."
				window.print();
		}
			
	  ;";
	}
	function copiarcheque(){
		$listabanaria = $this->ChequesModel->cuentasbancariaslista();
		require('views/documentos/copiarcheque.php');
	}
	function cancela(){
		$cancela = $this->ChequesModel->cancela($_REQUEST['opc'], $_REQUEST['idDocumento']);
		$poliza = $this->verficaPolizaLocal($_REQUEST['idDocumento']);
		if($cancela){
			$this->ChequesModel->documentoActivoInactivoTraspaso(3, $_REQUEST['idDocumento']);
			
			$this->copyarhivos($this->path('../cont/')."xmls/facturas/documentosbancarios/".$_REQUEST['idDocumento']."/",$this->path('../cont/')."xmls/facturas/temporales/",$poliza);
		}
		echo $cancela;
	}
	function copyarhivos($dirOrigen,$dirDestino,$poliza){
		if ($vcarga = opendir($dirOrigen)){
			while($file = readdir($vcarga)){
				if($file != '.' AND $file != '..' AND $file != '.DS_Store' AND $file != '.file'){

					if (!is_dir($dirOrigen.$file)){
						if(copy($dirOrigen.$file, $dirDestino.$file)){
							unlink($dirOrigen.$file);
						}
					}
				}
			}
		}rmdir($dirOrigen);
		closedir($vcarga);
		if($poliza){
			if ($abrepol = opendir($this->path('../cont/')."xmls/facturas/$poliza/")){
				while($file = readdir($abrepol)){
					if($file != '.' AND $file != '..' AND $file != '.DS_Store' AND $file != '.file'){

						if (!is_dir($this->path('../cont/')."xmls/facturas/$poliza/".$file)){
							unlink($this->path('../cont/')."xmls/facturas/$poliza/".$file);
						}
					}
				}rmdir($this->path('../cont/')."xmls/facturas/$poliza/");
			}closedir($abrepol);
		}
		
		
	}
	function impreso(){
		// $idbancaria = $this->ChequesModel->validaguardado($_REQUEST['numerocheque'],$_REQUEST['cuenta']);
		// if($idbancaria!=0){
			$impreso = $this->ChequesModel->updateimpreso($_REQUEST['idDocumento']);
			if($impreso == true){
				echo 1;
			}
			else{
				echo 0;//no esta guardado
			}
	}
	function devolverdocumento(){//4-devuelto
		$ok =   $this->ChequesModel->status($_REQUEST['idDocumento'],$_REQUEST['status']);
		$idInversoDocONuevo = 0;
		if($ok){
			
			
			if($_REQUEST['status']==4){//agrega marca devuelto
				$idInversoDocONuevo = $this->ChequesModel->documentoInverso($_REQUEST['idDocumento'],date('Y-m-d', strtotime($_REQUEST['fecha'])));
				 $this->ChequesModel->documentoActivoInactivoTraspaso(3, $_REQUEST['idDocumento']);
			}
			if($_REQUEST['status']==1){
				$idInversoDocONuevo = $this->ChequesModel->documentoReactivado($_REQUEST['idDocumento']);
				$this->ChequesModel->documentoActivoInactivoTraspaso(1, $_REQUEST['idDocumento']);
			}
		}
		echo $idInversoDocONuevo;
	}
	// function agregados(){
		// $entregados = $this->ChequesModel->agregados($_REQUEST['idbancaria']);
			// echo '<option value=0>----</option>';
		// while($r=$entregados->fetch_array()){
			// echo "<option value=".$r['id'].">(".$r['folio'].")".$r['concepto']."</option>";
		// }
	// }
	function editar(){
		$ed = $this->ChequesModel->editados($_REQUEST['idDocumento']);
		$beneficiario = $this->ChequesModel->buscaBeneficiario($ed['idbeneficiario']);
		
		echo $ed['fecha']."//".$ed['fechaaplicacion']."//".$ed['folio']."//".$ed['importe']."//".$ed['referencia']."//".$ed['concepto']."//".$beneficiario['cuenta']."/".$ed['idbeneficiario']."//".$ed['status']."//".$ed['conciliado']."//".$ed['impreso']."//".$ed['asociado']."//".$ed['proceso']."//".$ed['idclasificador']."//".$ed['posibilidadpago']."//".$ed['id'];
	}
	function consulNumeroCheque(){ 
		$numero =  $this->ChequesModel->numchequsado($_REQUEST['numerocheque'], $_REQUEST['idbancaria']);
		echo $numero;
	}
	function borrar(){
		echo $this->ChequesModel->borrar($_REQUEST['idDocumento'],3);
	}
	function guardaCopiaCheque(){//ver si sirve aun
		$ed = $this->ChequesModel->editados($_REQUEST['idDocumento']);
		$estado = $this->ChequesModel->InsertCheque($ed['fecha'], $_REQUEST['numero'], $ed['importe'], $ed['referencia'], $ed['concepto'], $_REQUEST['idbancaria'], $ed['beneficiario'], $ed['idbeneficiario'], $ed['proceso'], $ed['idclasificador'], 1);
		if($estado == true){
			$actualizanumerocheq = $this->ChequesModel->actuliazanumerodocumento($_REQUEST['numero'], $_REQUEST['idbancaria']);
			echo 1;
		}else{
			echo 0;
		}
	}
	
	function actualizaPoliza(){
		
	}
	function numDocumentoEdicion(){
		$valida = $this->ChequesModel->numDocumentoEdicion($_REQUEST['idbancaria'],$_REQUEST['numerocheque'],$_REQUEST['idDocumento']);
		echo $valida;
	}
	function procesoUpdate(){
		 $this->ChequesModel->procesoupdate($_REQUEST['proceso'], $_REQUEST['id']);
	}
	function verficaPoliza(){
		$poliza = $this->ChequesModel->polizaDocumento($_REQUEST['idDocumento']);
		if($poliza!=0){
			echo $poliza['id'];
		}else{
			echo 0;
		}
	}
	function verficaPolizaDevolucion(){
		$poliza = $this->ChequesModel->polizaDocumentoDevolucion($_REQUEST['idDocumento']);
		if($poliza!=0){
			echo $poliza['id'];
		}else{
			echo 0;
		}
	}
	function verficaPolizaLocal($idDocumento){
		$poliza = $this->ChequesModel->polizaDocumento($idDocumento);
		if($poliza!=0){
			return $poliza['id'];
		}else{
			return 0;
		}
	}
	function eliminaPoliza(){
		echo $this->ChequesModel->deletePoliza($_REQUEST['idpoli']);
	}
	
	function inactivapoliza(){
		//$this->ChequesModel->borrar($_REQUEST['idDocumento'], 2);
		echo $this->ChequesModel->eliminaPolizaDocumento($_REQUEST['idDocumento']);
	}
/* Movimientos inverso
 * El mov inverso sera una copia de la poliza
 * origen solo con los mov invertidos 
 * y los nuevo datos de fecha
*/
function movInverso(){
		$Exercise = $this->ChequesModel->getExerciseInfo();
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
		$xml="";$segmento=1;$sucursal=1;
		$_REQUEST['fecha'] = date('Y-m-d', strtotime($_REQUEST['fecha']));
		$idDocInverso = $this->ChequesModel->documentoInverso($_REQUEST['idDocumento'],$_REQUEST['fecha']);
		
		$idPolizaInver = $this->ChequesModel->movInversoPoliza($_REQUEST['idpoli'], $idperio, $idejer, $_REQUEST['fecha'], "Mov inverso cheque No.".$_REQUEST['numerocheque'],$idDocInverso);
		$this->ChequesModel->inactivaRelacionPrv($_REQUEST['idpoli']);
		if($idPolizaInver!=0){
			$rutapoli 	= $this->path('../cont/')."xmls/facturas/" . $idPolizaInver;
			if(!file_exists($rutapoli))
			{
				mkdir ($rutapoli, 0777);
			}
			$dirOrigen = $this->path('../cont/')."xmls/facturas/documentosbancarios/".$_REQUEST['idDocumento'];
			if ($vcarga = opendir($dirOrigen)){
				while($file = readdir($vcarga)){
					if($file != '.' AND $file != '..' AND $file != '.DS_Store' AND $file != '.file'){

						if (!is_dir($dirOrigen.'/'.$file)){
							copy($dirOrigen.'/'.$file, $rutapoli.'/'.$file);
						}
					}
				}
			}
			$this->ChequesModel->almacenaDevolucion($_REQUEST['idDocumento'], $_REQUEST['idpoli'], $idPolizaInver,$_REQUEST['numDevolucion'],$idDocInverso);
			
			echo $idPolizaInver;
		}else{
			echo 0;
		}
	
	}
function almacenaInversoSinPoliza(){
	//$idDocInverso = $this->ChequesModel->documentoInverso($_REQUEST['idDocumento'],date('Y-m-d', strtotime($_REQUEST['fecha'])));
	echo $this->ChequesModel->almacenaDevolucion($_REQUEST['idDocumento'],0, 0,$_REQUEST['numDevolucion'],$_REQUEST['inverso']);
}
function numeroDevoluciones(){
	echo $this->ChequesModel->UltimoNumDevuelto($_REQUEST['idDocumento']);
}
/* fin inverso */

function identificaMovInverso(){
	$inverso = $this->ChequesModel->identificaMovInverso($_REQUEST['idDocumento'], $_REQUEST['numerocheque'], $_REQUEST['cuenta'], "Abono",0);
	if($inverso->num_rows>0){
		if($p = $inverso->fetch_assoc()){
			$this->ChequesModel->inactivActivaInverso($p['id'],$_REQUEST['status']);
		}
	}else{
		echo 0;
	}
}
function detectaMovInverso(){
	$inverso = $this->ChequesModel->identificaMovInverso($_REQUEST['idDocumento'], $_REQUEST['numerocheque'], $_REQUEST['cuenta'], "Abono",0);
	if($inverso->num_rows>0){
		 echo 1;
	}else{
		echo 0;
	}
}
	// F I N  C H E Q U E S //
	//EGRESOS//
	
	function verEgresos(){
		$acontia = $this->ChequesModel->validaAcontia();
		$appministra = $this->ChequesModel->validaAppministra();
		$info = $this->ChequesModel->infoConfiguracion();
		$cuentasbancarias = $this->ChequesModel->cuentasbancariaslista();
		$moneda = $this->ChequesModel->moneda();
		$sqlprov = $this->ChequesModel->proveedor();
		$tipodocumento = $this->ChequesModel->tipodocumento(5);
		$cuentasAfectables = $this->ChequesModel->cuentasAfectables(0);
		$listaconceptos = $this->ChequesModel->concepto(2);
		$clasificador = $this->ChequesModel->clasificador();
		$clasificadorsub = $this->ChequesModel->clasificador();
		$formapago  = $this->ChequesModel->formapago();
		$segmento	= $this->ChequesModel->segmento();
		$sucursal	= $this->ChequesModel->sucursal();
		$bancos = $this->ChequesModel->bancos();
		$empleados = $this->ChequesModel->empleados();
		$cuentasAsiganacion = $this->ChequesModel->configCuentas();
		$cuentasEmpleado = $this->ChequesModel->pasivoCirculante();
		//$clientesBeneficiario = $this->ChequesModel->clienteBeneficiario();
		
		$usuarios = $this->ChequesModel->usuarios();
		
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
			$datos = $this->ChequesModel->editarDocumento($_REQUEST['editar']);
			if($datos['idmoneda']!=1){
				$cuentasAfectables = $this->ChequesModel->cuentasAfectables(1);
			}
			$appPagos = $this->ChequesModel->pagosConDocumento($_REQUEST['editar'], $datos['idbeneficiario'], 1);
			$traspasoDocdestino = $this->ChequesModel->documentosDestinotraspaso($_REQUEST['editar']);
			$subcategoriasAsignadas = $this->ChequesModel->consultaSubcategoriasDoc($_REQUEST['editar']);
		}else{
			//$basico = $this->ChequesModel->idUltimoDocumentoBasico(5);
			//if($basico>0){
			//	$_SESSION['egresonew']=$basico;
			//}else{
			if($_SESSION['egresonew']){
				$comprueba = $this->ChequesModel->compruebaDisponibilidad($_SESSION['egresonew']);
				if($comprueba!=0){//esq ya se ocupo
					$idtemporal = $this->ChequesModel->InsertDocumentoBasico(5);
					$_SESSION['egresonew'] = $idtemporal;
				}else{
					$existe = $this->ChequesModel->compruebaExistencia($_SESSION['egresonew']);
					if($existe==0){//si es igual a 0 es qno existe y trae algo loco la session 
						$idtemporal = $this->ChequesModel->InsertDocumentoBasico(5);
						$_SESSION['egresonew'] = $idtemporal;
					}
				}
			}else{
				$idtemporal = $this->ChequesModel->InsertDocumentoBasico(5);
				$_SESSION['egresonew'] = $idtemporal;
			}
			
			
			
		}
		$benefeciarior 	= $this->ChequesModel->proveedor();
		$complementos 	= $this->ChequesModel->complementoRetenciones();
		require('views/documentos/egresos.php');
		
	}
	/* vista que carga las cxc y cxp pendientes 
	 * * cobrar_pagar  0-cxc 1cxp
	*/
	function cxcycxp(){
		$cargos  = $this->ChequesModel->listaCargos($_REQUEST['idPrvCli'], $_REQUEST['cobrar_pagar'],$_REQUEST['mone']);
		$facturas = $this->ChequesModel->listaFacturas($_REQUEST['idPrvCli'], $_REQUEST['cobrar_pagar'],$_REQUEST['mone']);
		require('views/documentos/appministra.php');
	}
	

	/* FUNCION QUE CREA EL EGRESO Y VALIDA LA POLIZA PRA CREACION
	 	 * Si la moneda es paguese2 quiere decir q escojio del 
		 * listado de cuentas porque los catalogos cliente prv solo
		 * pueden relacionarse con cuentas en pesos
		 * esto aplica para conservar el idmoneda para saber de cual seleccionaron
	*/
	function creaEgreso(){//ganzo
		$cuenta = explode('//',  $_REQUEST['cuenta']);
		$paguese2 = explode('/',  $_REQUEST['paguese2']);
		$pagador = explode('/',  $_REQUEST['pagador']);
		$traspaso = explode('/',$_REQUEST['listatraspaso']);
		$bdestino=explode('/', $_REQUEST['bancodestino']);
		$_REQUEST['bancodestino']=$bdestino[0];
		$tipobeneficiario = $pagador[2];//prv
		if($_REQUEST['statustraspaso']==0){
			if($pagador[0]=="" || $pagador[0]==0 || $pagador[0]==-1){
				$cuentabeneficiario = $_REQUEST['paguese2'];
				if($paguese2[1]==1 &&  $pagador[3]!=4){// si la cuenta elejida es 1 pesos almacena
					if($tipobeneficiario==5){//cliente
						$this->ChequesModel->updateClienteCuentaEgre($pagador[1], $paguese2[0]);
					}elseif($tipobeneficiario==1){//prv
						$this->ChequesModel->updatePrvCuentaEgre($pagador[1], $paguese2[0]);
					}
				}
			}else{
				$cuentabeneficiario = $pagador[0]."/1";
			}
		}else{
			$cuentabeneficiario = $traspaso[3].'/'.$traspaso[4];//cuenta contable
		}
		$_REQUEST['fecha'] =date('Y-m-d', strtotime($_REQUEST['fecha']));
		$_REQUEST['importe'] = str_replace(',', '', $_REQUEST['importe']);
		if($_REQUEST['statusanticipo']==0){$_REQUEST['usuarios']=0;}
		
		if( isset($_REQUEST['id']) ){//si existe es edicion
			$previos = $this->ChequesModel->benePrevio($_REQUEST['id']);
			$crea  = $this->ChequesModel->actualizaEgreso(5,$_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],1,$_REQUEST['id'],$_REQUEST['fecha'], $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['textarea'],$cuenta[0], $tipobeneficiario, $pagador[1], $_REQUEST['clasificador'],$_REQUEST['idDocumento'],$_REQUEST['tipodocumento'],$_REQUEST['bancodestino'],$_REQUEST['numcuentadestino'],$_REQUEST['formapago'],$_REQUEST['proceso'],$cuenta[2],$_REQUEST['cambio'],' ',$_REQUEST['tipoPoliza'],$_REQUEST['statustraspaso'],$_REQUEST['statuscomision'],$_REQUEST['statusanticipo'],$_REQUEST['usuarios']);//$cuenta[2] es la moneda
			if($crea){
				$idDoc = $_REQUEST['id'];
				$reload = "window.location = 'index.php?".$_REQUEST['link']."';";
				$actual = '(Actualizado)';
				$act = "actualizar";
				
			}
		
		}else{
			
			//$idDoc  = $this->ChequesModel->creaEgreso($_REQUEST['fecha'], $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['textarea'],$cuenta[0], $tipobeneficiario, $pagador[1], $_REQUEST['clasificador'], $cuenta[2],5,$_REQUEST['proceso'],$_REQUEST['tipodocumento'],$_REQUEST['bancodestino'],$_REQUEST['numcuentadestino'],$_REQUEST['cambio'],$_REQUEST['formapago'],$_REQUEST['tipoPoliza']);
			//1-cheque,2-ingre,3-ingrenodepo,4-depo,5-egre
			$crea  = $this->ChequesModel->actualizaEgreso(5,$_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],0,$_REQUEST['idtemporal'],$_REQUEST['fecha'], $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['textarea'],$cuenta[0], $tipobeneficiario, $pagador[1], $_REQUEST['clasificador'],$_REQUEST['idDocumento'],$_REQUEST['tipodocumento'],$_REQUEST['bancodestino'],$_REQUEST['numcuentadestino'],$_REQUEST['formapago'],$_REQUEST['proceso'],$cuenta[2],$_REQUEST['cambio'],' ',$_REQUEST['tipoPoliza'],$_REQUEST['statustraspaso'],$_REQUEST['statuscomision'],$_REQUEST['statusanticipo'],$_REQUEST['usuarios']);//$cuenta[2] es la moneda
			if($crea){
				$idDoc = $crea;
				$reload = "window.location = 'index.php?c=Cheques&f=".$_REQUEST['link']."';"; 
				$actual = "(Creado)";
				$act = "crear";
				
			}
			
		}
		if($idDoc){
			unset($_SESSION['egresonew']);
			
			if($_REQUEST['statustraspaso']==1){
				$importetraspaso = $_REQUEST['importe'];
				if($traspaso[4]==1){// si es d pesos
					if($cuenta[2]!=1){//pero lacuenta origen es de ME
						$importetraspaso = $_REQUEST['importe']*$_REQUEST['cambio'];
					}
				}else{
					if($cuenta[2]==1){//pero lacuenta origen es de pesos
						$importetraspaso = $_REQUEST['importe']/$_REQUEST['cambio'];
					}
				}
				if($_REQUEST['listadoctraspaso']==1){//no depositado
					$this->ChequesModel->creaIngresoNoDepositadoTraspaso($_REQUEST['fecha'], number_format($importetraspaso,2,'.',''), $_REQUEST['referencia'], $_REQUEST['textarea'], $traspaso[0], 0, 0, $_REQUEST['clasificador'], $traspaso[4], 4, 3, $_REQUEST['cambio'],$idDoc,$_REQUEST['formapago']);
				}else{//deposito
					$this->ChequesModel->creaDepositoTraspaso($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],$_REQUEST['fecha'],number_format($importetraspaso,2,'.',''), $_REQUEST['referencia'], $_REQUEST['textarea'], $traspaso[0], $_REQUEST['formapago'],1,$traspaso[4],$_REQUEST['cambio'],$idDoc);
					
				}
			}
			$ok = $this->ChequesModel->eliminaSubcategoriaDoc($idDoc);
			if($ok==1){
				if(isset($_REQUEST['subcategorias'])){
					//$idDoc = $this->ChequesModel->idUltimoDocumento(5);
					$cont=0;
					foreach($_REQUEST['subcategorias'] as $ca){
						if($cont!=0){
							$this->ChequesModel->documentosSubcategorias($idDoc, $_REQUEST['subcategorias'][$cont],$_REQUEST['porcentaje'][$cont-1], number_format(($_REQUEST['porcentaje'][$cont-1]/100) * $_REQUEST['importe'] ,2,'.',''));
						}
						$cont++;
					}
				}
			}
			if( isset($_REQUEST['id']) ){
				/* se queda solo asi en caso de que
				 * appministra maneje cxc o cxp de empleados 
				 * si debe verificar el beneficiario(tipo)*/
				if($previos->idbeneficiario != $pagador[1]){
					$this->ChequesModel->eliminaRegistrocxccxp($idDoc,$previos->idbeneficiario);
				}
				
			}
			if(isset($_REQUEST['cargosapp'])){
				$cont=0;
				foreach($_REQUEST['cargosapp'] as $cargo){
					$cargosapp = explode('/', $_REQUEST['cargosapp'][$cont]);
					$impApp = $cargosapp[0];
					$idCargo = $cargosapp[1];
					$idPago = $this->ChequesModel->almacenaPago(1, $pagador[1], number_format($impApp,2,'.',''), $_REQUEST['fecha'], $_REQUEST['textarea'], $_REQUEST['formapago'], $cuenta[2] , $_REQUEST['cambio'],$idDoc,"Egreso");
					if($idPago){
						$this->ChequesModel->almacenaPagoRelacion($idPago,$idCargo,number_format($impApp,2,'.',''),0);
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
					$idPago = $this->ChequesModel->almacenaPago(1, $pagador[1], number_format($impApp,2,'.',''), $_REQUEST['fecha'], $_REQUEST['textarea'], $_REQUEST['formapago'], $cuenta[2] , $_REQUEST['cambio'],$idDoc,"Egreso");
					if($idPago){
						$this->ChequesModel->almacenaPagoRelacion($idPago,$idCargo,number_format($impApp,2,'.',''),1);
						if($xmlfact){
							if($montoOriginal==$impApp){
								rename($this->path('../cont/')."xmls/facturas/temporales/$xmlfact", $ruta.$xmlfact);
							}else{
								copy($this->path('../cont/')."xmls/facturas/temporales/$xmlfact", $ruta.$xmlfact);
							}
						}
								
					}
					$cont++;
				}
			}
			
			
			if($_REQUEST['proceso']==1){//si el documento esta proyectado no puede hacer poliza
				echo "<script> alert('Documento $actual'); $reload </script>";
				return false;
			}
			
			if($_REQUEST['automatica']==1){
				if($_REQUEST['statuscomision']==1){
					$_REQUEST['tipoPoliza']=3;
				}
					//$elimina = $this->ChequesModel->eliminaPolizaDocumento($idDoc);
					$verifica = $this->verficaPolizaLocal($idDoc);
					
					if($_REQUEST['tipoPoliza']==1 || $_REQUEST['statustraspaso']==1 || $_REQUEST['statusanticipo']==1){
						$polizaAut = $this->creaPolizaAutomaticaIVA($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],$verifica,$pagador[2],0,$pagador[1],$idDoc, $_REQUEST['textarea'], $_REQUEST['fecha'], '', $_REQUEST['bancodestino'],$_REQUEST['numcuentadestino'] , $_REQUEST['cuenta'], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'],$_REQUEST['cambio'],$_REQUEST['statusanticipo'],$_REQUEST['usuarios']);
						if($polizaAut!=0){ 
							echo "<script>alert('Documento y Poliza $actual'); ".$this->mandaPoliza($polizaAut,"$reload",$pagador[1],1,$tipobeneficiario,$_REQUEST['tipoPoliza'])."; </script>";
						}
						
					}elseif($_REQUEST['tipoPoliza']==2 ){//con iva
						$cuentasConf = $this->ChequesModel->configCuentas();
						if($cuentasConf['CuentaIVAPendientePago']!=-1 && $cuentasConf['CuentaIVApagado']!=-1){
							$polizaAut = $this->creaPolizaAutomaticaIVA($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],$verifica,$pagador[2],1,$pagador[1], $idDoc, $_REQUEST['textarea'], $_REQUEST['fecha'], '', $_REQUEST['bancodestino'],$_REQUEST['numcuentadestino'] ,$_REQUEST['cuenta'], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'],$_REQUEST['cambio']);
							if($polizaAut!=0){
								echo "<script>alert('Documento y Poliza $actual');" .$this->mandaPoliza($polizaAut,"$reload",$pagador[1],1,$tipobeneficiario,$_REQUEST['tipoPoliza'])."; </script>";
							}
							
						}else{//poliza con iva pero sin tener asignadas cuentas crea la poliza pero sin los movmientos de iva
							$polizaAut = $this->creaPolizaAutomaticaIVA($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],$verifica,$pagador[2],0,$pagador[1], $idDoc, $_REQUEST['textarea'], $_REQUEST['fecha'], '', $_REQUEST['bancodestino'],$_REQUEST['numcuentadestino'] , $_REQUEST['cuenta'], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'],$_REQUEST['cambio']);
							if($polizaAut!=0){
								// $verifica = $this->verficaPolizaLocal($idDoc);
								// if($verifica!=0){
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
					}elseif($_REQUEST['tipoPoliza']==3 ){//sin provision
						$polizaAut = $this->creaPolizaAutomaticasinProvision($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],$verifica,$cuenta[2],0,0,$pagador[2],$pagador[1], $idDoc, $_REQUEST['textarea'], $_REQUEST['fecha'], '', $_REQUEST['bancodestino'],$_REQUEST['numcuentadestino'] , $cuenta[0], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'],$cuenta[1]);
						if($polizaAut!=0){
							// $verifica = $this->verficaPolizaLocal($idDoc);
							// if($verifica!=0){
								echo "<script> 
								if(confirm('Desea completar la poliza?')){
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
				
				
			
				}elseif($_REQUEST['automatica']==0 && $_REQUEST['acontia']==1){
					if( isset($_REQUEST['id']) ){
				 		$verifica = $this->verficaPolizaLocal($_REQUEST['id']);
						if($verifica!=0){
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
							if($_REQUEST['statustraspaso']==1){
								$polizaAut = $this->creaPolizaAutomaticaIVA($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],0,$pagador[2],0,$pagador[1],$idDoc, $_REQUEST['textarea'], $_REQUEST['fecha'], '', $_REQUEST['bancodestino'],$_REQUEST['numcuentadestino'] , $_REQUEST['cuenta'], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'],$_REQUEST['cambio']);
							}else{	
								$polizaAut = $this->creaPolizaAutomaticasinProvision($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],0,$cuenta[2],1,$_REQUEST['cambio'],$pagador[2],$pagador[1], $idDoc, $_REQUEST['textarea'], $_REQUEST['fecha'], '', $_REQUEST['bancodestino'],$_REQUEST['numcuentadestino'] , $cuenta[0], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'],$cuenta[1]);
							}
							echo "<script>alert('Documento $actual');
								alert('Sera enviado a la Poliza');
										".$this->mandaPolizaManual($polizaAut,"$reload")
										."
							</script>";
						}
				 		
					}else{
						if($_REQUEST['statustraspaso']==1){
							$polizaAut = $this->creaPolizaAutomaticaIVA($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],0,$pagador[2],0,$pagador[1],$idDoc, $_REQUEST['textarea'], $_REQUEST['fecha'], '', $_REQUEST['bancodestino'],$_REQUEST['numcuentadestino'] , $_REQUEST['cuenta'], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'],$_REQUEST['cambio']);
							
						}else{
							$polizaAut = $this->creaPolizaAutomaticasinProvision($_REQUEST['segmento'],$_REQUEST['sucursal'],$_REQUEST['numeroformapago'],0,$cuenta[2],1,$_REQUEST['cambio'],$pagador[2],$pagador[1], $idDoc, $_REQUEST['textarea'], $_REQUEST['fecha'], '', $_REQUEST['bancodestino'],$_REQUEST['numcuentadestino'] , $cuenta[0], $cuentabeneficiario, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['formapago'],$cuenta[1]);
						}
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
			
		}else{
			echo "<script>alert('Error al $act documento Intente de nuevo!'); $reload </script>";
		}
		
		
	}
	/* $importedll = $importe;
		$importe =  number_format(floatval($importe * $tc),2,'.','');
	 * 
	 * las asignaciones seran para q si la cuenta beneficiario
	 * no es ME entonces lo ponga el saldo en pesos, 
	 * asi esta ahorita porq si eliges una cuenta
	 * en ME el listado de beneficiario trae cuentas extranjeras y pesos
	 * si la cuenta bancaria en pesos solo traera cuentas en pesos
	 * la lista del beneficiario*/
function creaPolizaAutomaticaIVA($segmento,$sucursal,$numeroformap,$idpoliza,$idbeneficiario,$concuentas,$beneficiario,$idDocumento,$concepto,$fecha,$numerocheque,$bancodestino,$cuentadestino,$idbancaria,$cuentabeneficiariob,$importe,$referencia,$formapago,$tc,$statusanticipo=0,$idUser=0){
	$Exercise = $this->ChequesModel->getExerciseInfo();
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
		$cuentasConf = $this->ChequesModel->configCuentas();
		
		if($idbeneficiario==1){
			$dato = $this->ChequesModel->datosproveedor( $beneficiario);
			$rfc = $dato['rfc'];
			$tipo = "2-";
			
		}elseif($idbeneficiario==5){//clientebeneficiario
			$dato = $this->ChequesModel->clienteInfo( $beneficiario);
			$rfc = $dato['rfc'];
			$tipo = "1-";
		}
		elseif($idbeneficiario==2){//empleado
			$dato = $this->ChequesModel->datosempleados($beneficiario);
			$rfc = $dato['rfc'];
			$tipo = "";
		}else{//traspaso rfc organizacion
			$rfc = $cuentasConf['RFC'];
			$tipo = "";
			$idbeneficiario=6;
		}
		
		//pato
		$cuenta = explode('//',  $idbancaria);//$b['idbancaria']."//".$b['account_id']."//".$b['coin_id']
		$datosBeneficiario = explode('/', $cuentabeneficiariob);//$b['account_id']."/".$b['currency_id']
		$cuentacontable = $cuenta[1];
		$cuentabeneficiario = $datosBeneficiario[0];
		$idbancaria = $cuenta[0];
		$cuentamoneda = $cuenta[2];
		$modenaCuentaBene = $datosBeneficiario[1];
		$fecha = date('Y-m-d', strtotime($fecha));
		$xml="";//$segmento=1;$sucursal=1;
		if($idpoliza>0){
			
			$importAntes = $this->ChequesModel->importMovBancoPoliza($idDocumento, $cuentacontable);
			$sinmov = $this->ChequesModel->eliminaMovimientosPoliza($idDocumento);
			if($sinmov==1){
				if(($importAntes['importe'] != $importe) || ($importAntes['beneficiario'] != $beneficiario)){
					$this->ChequesModel->inactivaRelacionPrv($idpoliza);
				}
				$poli = $this->ChequesModel->savePoliza($referencia,$idpoliza,$idorg, $idejer, $idperio, 2, $concepto, $fecha, $beneficiario, $numeroformap, $rfc, $bancodestino, $cuentadestino, $idbancaria,$idDocumento,$idbeneficiario,$statusanticipo,$idUser);
				$numPoliza['id'] = $idpoliza;
			}
		}else{
			$poli = $this->ChequesModel->savePoliza($referencia,0,$idorg, $idejer, $idperio, 2, $concepto, $fecha, $beneficiario, $numeroformap, $rfc, $bancodestino, $cuentadestino, $idbancaria,$idDocumento,$idbeneficiario,$statusanticipo,$idUser);
			$numPoliza = $this->ChequesModel->getLastNumPoliza();
		}
		if( $poli == 0 ){
			
			//$numPoliza = $this->ChequesModel->getLastNumPoliza();
			$rutapoli 	= $this->path('../cont/')."xmls/facturas/" . $numPoliza['id'];
			if(!file_exists($rutapoli))
			{
				mkdir ($rutapoli, 0777);
			}
		
		
		
			/* MOVIMIENTO EXT */
				if($cuentamoneda!=1){
					$ban = $this->ChequesModel->InsertMov($numPoliza['id'], 1, $segmento, $sucursal, $cuentacontable, "Abono M.E",$importe, $concepto,$tipo, $xml, $referencia, $formapago,$tc);
					$ban = $this->ChequesModel->InsertMov($numPoliza['id'], 1, $segmento, $sucursal, $cuentacontable, "Abono", number_format(floatval($importe * $tc),2,'.',''), $concepto,$tipo, $xml, $referencia, $formapago,'0.0000');
					
					$importedll = $importe;
					$importe =  number_format(floatval($importe * $tc),2,'.','');
				}
				/* FIN MOV EXT */
				else{
					$ban = $this->ChequesModel->InsertMov($numPoliza['id'], 1, $segmento, $sucursal, $cuentacontable, "Abono", $importe, $concepto,$tipo, $xml, $referencia, $formapago,'0.0000');
				}
			
			if($ban==true){
					/* MOVIMIENTO EXT */
				if($modenaCuentaBene!=1){
					if($cuentamoneda==1){
						$importedll =  number_format(floatval($importe / $tc),2,'.','');
					}
					$bene = $this->ChequesModel->InsertMov($numPoliza['id'], 2, $segmento, $sucursal, $cuentabeneficiario, "Cargo M.E.", $importedll, $concepto,$tipo.$beneficiario, $xml, $referencia, $formapago,$tc);
					$bene = $this->ChequesModel->InsertMov($numPoliza['id'], 2, $segmento, $sucursal, $cuentabeneficiario, "Cargo", $importe, $concepto,$tipo.$beneficiario, $xml, $referencia, $formapago,'0.0000');
					
				}	
				/* FIN MOV EXT */
				else{
					
					$bene = $this->ChequesModel->InsertMov($numPoliza['id'], 2, $segmento, $sucursal, $cuentabeneficiario, "Cargo", $importe, $concepto,$tipo.$beneficiario, $xml, $referencia, $formapago,'0.0000');
				}
				if($bene==true){
					$iva = $importe / 1.16;
					$iva = $iva * .16;
					if($concuentas){
						$ivapen = $this->ChequesModel->InsertMov($numPoliza['id'], 3, $segmento, $sucursal, $cuentasConf['CuentaIVAPendientePago'], "Abono", number_format($iva,2,'.',''), $concepto,'2-', $xml, $referencia, $formapago,'0.0000');
						$ivapagado = $this->ChequesModel->InsertMov($numPoliza['id'], 4, $segmento, $sucursal, $cuentasConf['CuentaIVApagado'], "Cargo", number_format($iva,2,'.',''), $concepto,'2-', $xml, $referencia, $formapago,'0.0000');
					}
					
					
					/* mov xml a poliza */
		$cont= 0;$xmlsvalidos = array();
			$dirOrigen = $this->path('../cont/')."xmls/facturas/documentosbancarios/".$idDocumento;
			if ($vcarga = opendir($dirOrigen)){
				while($file = readdir($vcarga)){
				if($file != '.' AND $file != '..' AND $file != '.DS_Store' AND $file != '.file'){
					
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
			if($cont>0){
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
							}elseif($cont==1){
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
			}
		/* mov xml a poliza */
					
					
					return $numPoliza['id'];
				}else{
					return 0;
				}
			}			
		}
}

function creaPolizaAutomaticasinProvision($segmento,$sucursal,$numeroformap,$idpoliza,$moneda,$manual,$tc,$idbeneficiario,$beneficiario,$idDocumento,$concepto,$fecha,$numerocheque,$bancodestino,$cuentadestino,$idbancaria,$cuentabeneficiario,$importe,$referencia,$formapago,$cuentacontable){
	$Exercise = $this->ChequesModel->getExerciseInfo();
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
		if($idbeneficiario==1){
			$dato = $this->ChequesModel->datosproveedor( $beneficiario);
			$rfc = $dato['rfc'];
			$tipo = "2-";
			
		}elseif($idbeneficiario==5){//clientebeneficiario
			$dato = $this->ChequesModel->clienteInfo( $beneficiario);
			$rfc = $dato['rfc'];
			$tipo = "1-";
		}
		elseif($idbeneficiario==2){//empleado
			$dato = $this->ChequesModel->datosempleados($beneficiario);
			$rfc = $dato['rfc'];
			$tipo = "";
		}
		else{//traspaso rfc organizacion
			$rfc = $cuentasConf['RFC'];
			$tipo = "";
			$idbeneficiario=6;
		}
		$xml="";//$segmento=1;$sucursal=1;
		//pato
		$fecha = date('Y-m-d', strtotime($fecha));
		if($idpoliza>0){
			$sinmov = $this->ChequesModel->eliminaMovimientosPoliza($idDocumento);
			if($sinmov == 1){
				$poli = $this->ChequesModel->savePoliza($referencia,$idpoliza,$idorg, $idejer, $idperio, 2, $concepto, $fecha, $beneficiario, $numeroformap, $rfc, $bancodestino, $cuentadestino, $idbancaria,$idDocumento,$idbeneficiario);
				$numPoliza['id'] = $idpoliza;
			}
			
		}else{
			$poli = $this->ChequesModel->savePoliza($referencia,0,$idorg, $idejer, $idperio, 2, $concepto, $fecha, $beneficiario, $numeroformap, $rfc, $bancodestino, $cuentadestino, $idbancaria,$idDocumento,$idbeneficiario);
			$numPoliza = $this->ChequesModel->getLastNumPoliza();
		}
		if( $poli == 0 ){
			//$numPoliza = $this->ChequesModel->getLastNumPoliza();
			$rutapoli 	= $this->path('../cont/')."xmls/facturas/" . $numPoliza['id'];
			if(!file_exists($rutapoli))
			{
				mkdir ($rutapoli, 0777);
			}
			if($manual==0){
				$bene = $this->ChequesModel->InsertMov($numPoliza['id'], 1, $segmento, $sucursal, $cuentacontable, "Abono", $importe, $concepto,$tipo, $xml, $referencia, $formapago,'0.0000');
			}else{
				if($moneda!=1){
					$bene = $this->ChequesModel->InsertMov($numPoliza['id'], 1, $segmento, $sucursal, $cuentacontable, "Abono M.E", $importe, $concepto,$tipo, $xml, $referencia, $formapago,$tc);
					$bene = $this->ChequesModel->InsertMov($numPoliza['id'], 1, $segmento, $sucursal, $cuentacontable, "Abono", number_format(floatval($importe * $tc),2,'.',''), $concepto,$tipo, $xml, $referencia, $formapago,'0.0000');
					
				}else{
					$bene = $this->ChequesModel->InsertMov($numPoliza['id'], 1, $segmento, $sucursal, $cuentacontable, "Abono", $importe, $concepto,$tipo, $xml, $referencia, $formapago,'0.0000');
				}
			}
			
			if($bene==true){
				
							/* mov xml a poliza */
					$cont= 0;$xmlsvalidos = array();
						$dirOrigen = $this->path('../cont/')."xmls/facturas/documentosbancarios/".$idDocumento;
						if ($vcarga = opendir($dirOrigen)){
							while($file = readdir($vcarga)){
								if($file != '.' AND $file != '..' AND $file != '.DS_Store' AND $file != '.file'){

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
						if($cont>0){
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
						}
					/* mov xml a poliza */
				return $numPoliza['id'];
			}else{
				return 0;
			}
		}			
		
}
	function bancoDestino(){
		
		$bancos=$this->ChequesModel->buscabancos($_REQUEST['idprove'],$_REQUEST['idbeneficiario']);
		if($bancos){
			echo "<option value=0>Seleccione Banco Destino</option>";
			while($lista=$bancos->fetch_assoc()){
				echo "<option value=".$lista['idbanco']."/".$lista['id'].">".$lista['nombre']."</option>";
			}
			
		}else{
			echo 0;
		}
		
	}
	function bancoDestinoEmpleado(){
		$bancosLista = $this->ChequesModel->bancos();
		while($lista=$bancosLista->fetch_assoc()){
			echo "<option value=".$lista['idbanco'].">".$lista['nombre']."</option>";
		}
		$bancos  = $this->ChequesModel->datosempleados($_REQUEST['idprove']);
		if($bancos){
			
			echo "-_-".$bancos['idbanco']."/".$bancos['numeroCuenta'];
			
		}else{
			echo '-_-0';
		}
	}
	function numcuenta(){
		echo $numcuenta = $this->ChequesModel->numbancos($_REQUEST['banco'],$_REQUEST['prove'],$_REQUEST['beneficiario']);
	}
	function EliminaDocumento(){
		$infodoc = $this->ChequesModel->editados($_REQUEST['id']);
		if($infodoc['conciliado']==1){
			echo 2;
		}else if($infodoc['proceso']==4){
			echo 3;//depositado
		}else if($infodoc['idtraspaso']>0){
			echo 4;//traspaso o docuemntos destino
		}else if($infodoc['impreso']==1){
			echo 5;
		}else if($infodoc['inverso']>0){
			$ok = $this->ChequesModel->eliminaDocumento($_REQUEST['id']);
			if($ok==1){
				$this->ChequesModel->eliminaPolizaDocumento($_REQUEST['id']);
				
				$catego =  $this->ChequesModel->eliminaSubcategoriaDoc($_REQUEST['id']);
				if($catego==1){
					$this->ChequesModel->regresaContador($_REQUEST['id'],$infodoc['inverso']);
					echo 6;//inverso
					
				}
				
			}
		}else{
			$ok = $this->ChequesModel->eliminaDocumento($_REQUEST['id']);
			if($ok==1){
				$this->ChequesModel->eliminaPolizaDocumento($_REQUEST['id']);
				$catego =  $this->ChequesModel->eliminaSubcategoriaDoc($_REQUEST['id']);
				if($catego==1){
					$this->ChequesModel->regresaContador($_REQUEST['id'],$infodoc['inverso']);
					
					
				}
				echo 1;
			}
		}
		
	}
	function EliminaDocumentoDeposito(){
		$infodoc = $this->ChequesModel->editados($_REQUEST['id']);
		if($infodoc['conciliado']==1){
			echo 2;
		}else if($infodoc['idtraspaso']>0){
			echo 4;//traspaso o docuemntos destino
		}else{
			$ok = $this->ChequesModel->eliminaDocumento($_REQUEST['id']);
			if($ok==1){
				$this->ChequesModel->eliminaPolizaDocumento($_REQUEST['id']);
				
				$catego =  $this->ChequesModel->eliminaSubcategoriaDoc($_REQUEST['id']);
				if($catego==1){
					echo 1;
					
				}
				
			}
		}
	}
	function listadoEgreso(){
		$egreso = $this->ChequesModel->listadoEgreso();
		if(!$egreso->num_rows>0){
			$egreso = 0;
		}
		require('views/documentos/egresoListado.php');
	}
	function listadoCheques(){
		$cheques = $this->ChequesModel->listadoCheques();
		if(!$cheques->num_rows>0){
			$cheques = 0;
		}
		require('views/documentos/chequeslistado.php');
	}
	
	function proveedorMoneda(){//cuenta de prv si con moneda acorde ala cuenta abncaria y prv sin cuenta
		$idbancaria = explode('//',$_REQUEST['idbancaria']);
		// $prv = $this->ChequesModel->proveedorMoneda($idbancaria[0]);
		// while($p = $prv->fetch_assoc()){
			// echo "<option value=".$p['cuenta'].'/'. $p['idPrv']." >".$p['razon_social']."</option>";
		// }
	 	echo "<option value=0 selected>--Seleccione--</option>";
		$cuentasprvsin = $this->ChequesModel->cuentasAfectables($_REQUEST['ext']);
		while($b = $cuentasprvsin->fetch_assoc()){
			echo  "<option value='".$b['account_id']."/".$b['currency_id']."'>".$b['description']."(".$b['manual_code'].")</option>";
		}
		
	}
	
	//  R E T E N C I O N   / /
	function verRetencion(){
		$benefeciarior 	= $this->ChequesModel->proveedor();
		$ejercicios 		= $this->ChequesModel->cont_ejercicios();
		$complementos 	= $this->ChequesModel->complementoRetenciones();
		$tipodividendo 	= $this->ChequesModel->tipoDividendo();
		$contribuyente	= $this->ChequesModel->tipoContribuyente();
		$contribuyente2	= $this->ChequesModel->tipoContribuyente();
		$paises			= $this->ChequesModel->retencionPaises();
		$estados			= $this->ChequesModel->retencionEstados();
		$facturacion		= $this->ChequesModel->validaFacturacion();
		require('views/retenciones/retenciones.php');
	}
	function verImpuestosFactura(){
		$impuestos = $this->ChequesModel->impuestosRetenidos($_REQUEST['id']);
		if(!$impuestos->num_rows>0){
			$impuestos = 0;
		}
		require('views/retenciones/impuestos.php');
	}
	function proceso($proceso){
		switch ($proceso){
			case 1:
				return "Proyectado";
			break;
			case 2:
				return "Autorizado";
			break;
			case 3:
				return "Emitido";
			break;
			case 4:
				return "Depositado";
			break;
		}
	}
	function status($status){///* 1-activo,2-cancelado,3-borrado,4-devuelto */
	
		switch ($status){
			case 1:
				return "Activo";
			break;
			case 2:
				return "Cancelado";
			break;
			case 3:
				return "Borrado";
			break;
			case 4:
				return "Devuelto";
			break;
		}
	}
	function tipodoc($tipo){///* 1-cheque,2-ingre,3-ingrenodepo,4-depo,5-egre*/
	
		switch ($tipo){
			case 1:
				return "Cheque";
			break;
			case 2:
				return "Ingreso";
			break;
			case 3:
				return "Ingreso por Depositar";
			break;
			case 4:
				return "Deposito";
			break;
			case 5:
				return "Egresos";
			break;
		}
	}
	function InicioEjercicio()
	{
		$InicioEjercicio = $this->ChequesModel->InicioEjercicio();
		echo $InicioEjercicio['InicioEjercicio'];
	}
	function CambioEjerciciosession(){
		$idejer = $this->ChequesModel->idex($_REQUEST['NameEjercicio'],'cont');
		$existe = $this->ChequesModel->existeConciliacion($_REQUEST['Periodo'], $idejer,$_REQUEST['idbancaria']);
		if($existe==1){//si ya esta conciiado el periodo
			echo 1;
		}else{
			setcookie('periodo',$_REQUEST['Periodo']);
			setcookie('ejercicio',$_REQUEST['NameEjercicio']);
			setcookie('idejercicio',100);
		}
	}
	function ejercicioactual(){
		setcookie('periodo', '', time() - 1000);
		setcookie('ejercicio', '', time() - 1000);
		setcookie('idejercicio', '', time() - 1000);
	}
	function consulcambio(){
		//$validafin=date('w', strtotime($_REQUEST['fecha']));
		$fecha=$_REQUEST['fecha'];
		$fecha = date('Y-m-d', strtotime($fecha));
		
		$cambio=$this->ChequesModel->consulcambio($_REQUEST['idmoneda'],$fecha);
		if($cambio!='0'){
			if($t=$cambio->fetch_assoc()){
				echo ($t['tipo_cambio']);
			}
		}else{
			echo 0;
		}
	}
	function tipoCambio(){
		$lista=$this->ChequesModel->tipoCambio($_REQUEST['idmoneda'], $_REQUEST['fecha']);
		$tipocambiolista="<option value='0'>--Seleccione--</option>";
		while ($row = $lista->fetch_assoc()){
			$tipocambiolista.= "<option value='".$row['tipo_cambio']."'>".date('d-m-Y', strtotime($row['fecha']))." (".$row['tipo_cambio'].")</option>";	
		}
		echo $tipocambiolista;
	}
	function cobrarCheque(){
		echo $this->ChequesModel->cobrarCheque($_REQUEST['status'], $_REQUEST['idDoc'],date('Y-m-d', strtotime($_REQUEST['fecha'])) );
	}
/* XML en documentos */
function quitar_tildes($cadena) {
		$no_permitidas= array ("\n","á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹","/");
		$permitidas= array ("","a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E","");
		$texto = str_replace($no_permitidas, $permitidas ,$cadena);
		return $texto;
	}
	
function valida_xsd($version,$xml) 
	{

		libxml_use_internal_errors(true);   
		switch ($version) 
		{
  			case "2.0":
    			$ok = $xml->schemaValidate("../cont/xmls/valida_xmls/xsds/cfdv2complemento.xsd");
    			break;
  			case "2.2":
    			$ok = $xml->schemaValidate("../cont/xmls/valida_xmls/xsds/cfdv22complemento.xsd");
    			break;
  			case "3.0":
    			$ok = $xml->schemaValidate("../cont/xmls/valida_xmls/xsds/cfdv3complemento.xsd");
    			break;
  			case "3.2":
    			$ok = $xml->schemaValidate("../cont/xmls/valida_xmls/xsds/cfdv32.xsd");
    			break;
			case "3.3":
    			$ok = $xml->schemaValidate("../cont/xmls/valida_xmls/xsds/cfdv33.xsd");
    			break;   
  			default:
    			$ok = 0;
		}
		return $ok;
	}

	function getpath($qry) 
	{
		global $xp;
		$prm = array();
		$nodelist = $xp->query($qry);
		foreach ($nodelist as $tmpnode)  
		{
    		$prm[] = trim($tmpnode->nodeValue);
    	}
		$ret = (sizeof($prm)<=1) ? $prm[0] : $prm;
		return($ret);
	}

	function valida_en_sat($rfc,$rfc_receptor,$total,$uuid) 
	{
    	error_reporting(E_ALL);
    	require_once('../cont/xmls/valida_xmls/nusoap/nusoap.php');
    	error_reporting(E_ALL & ~(E_STRICT|E_NOTICE|E_WARNING|E_DEPRECATED));
    	$url = "https://consultaqr.facturaelectronica.sat.gob.mx/consultacfdiservice.svc?wsdl";

    	$soapclient = new nusoap_client($url,$esWSDL=true);
    	$soapclient->soap_defencoding = 'UTF-8'; 
    	$soapclient->decode_utf8 = false;

    	$rfc_emisor = utf8_encode($rfc);
    	$rfc_receptor = utf8_encode($rfc_receptor);
    	$impo = (double)$total;
    	$impo=sprintf("%.6f", $impo);
    	$impo = str_pad($impo,17,"0",STR_PAD_LEFT);

    	$uuid = strtoupper($uuid);

    	$factura = "?re=$rfc_emisor&rr=$rfc_receptor&tt=$impo&id=$uuid";

    	$prm = array('expresionImpresa'=>$factura);

    	$buscar=$soapclient->call('Consulta',$prm);

    	//echo "Status del C&oacute;digo: ".$buscar['ConsultaResult']['CodigoEstatus']."<br>";
    	//echo "Status: ".$buscar['ConsultaResult']['Estado']."<br>";
    	if($buscar['ConsultaResult']['Estado'] == "Cancelado")
    	{
    		return 0;
    	}
    	else
    	{
    		return 1;
    	}

	}
	
function listaAlmacen()
	{
		global $xp;
		$listaTemporales = "<tr><td width='50' style='color:white;'>*1_-{}*</td><td width='300'></td><td width='50'></td><td width='50'></td><td width='50'></td><td width='50'></td><td width='50'></td><td width='50'></td><td width='50'></td><td width='50' style='font-weight:bold;font-size:9px;text-align:center;'><button id='' onclick='buttondesclick(\"borrar\")'>Desmarcar</button></td></tr>";

		$buscar = "{".$_POST['folio_uuid']."}";

		$n = 2;
		
		if(isset($_COOKIE['inst_lig']))
			$n = 10;		
		
		if(intval($_POST['tipo_busqueda']) == 1)
		{//Si es UUID
			if($archivos = glob($this->path('../cont/')."xmls/facturas/temporales/*".strtoupper($buscar).".xml",GLOB_BRACE))
				$r = 0;//Si la encuentra en mayusculas
			elseif($archivos = glob($this->path('../cont/')."xmls/facturas/temporales/*".strtolower($buscar).".xml",GLOB_BRACE))
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
			if($archivos = glob($this->path('../cont/')."xmls/facturas/temporales/*_*".strtoupper($buscar)."*_*.xml",GLOB_BRACE))
				$r = 0;//Si la encuentra en mayusculas
			elseif($archivos = glob($this->path('../cont/')."xmls/facturas/temporales/*_*".strtolower($buscar)."*_*.xml",GLOB_BRACE))
				$r = 0;//Si la encuentra en minusculas
			else
			{
				$r = 1;//Si no la encuentra en ninguna
				$buscar = "*_*$buscar*_*";
			}
		}
			
		$dir = $this->path('../cont/')."xmls/facturas/temporales/$buscar.xml";

		// Abrir un directorio, y proceder a leer su contenido
		if($r)
			$archivos = glob($dir,GLOB_BRACE);
		array_multisort(array_map('filectime', $archivos),SORT_DESC,$archivos);

		$cont=1;
		foreach($archivos as $file) 
		{
			if( !is_dir($file)) {
			if($archivo != '.' AND $archivo != '..' AND $archivo != '.DS_Store' AND $archivo != '.file' ){
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
					$data['impuesto'] = $this->ChequesModel->nombreImpuesto($this->getpath("//@Impuesto"));
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
					if(is_array($data['uuid'])){
						$data['uuid'] 	= $data['uuid'][1];
					}
					$data['folio']=$this->getpath("//@Folio");
				}
				else
				{
					$data['total'] = $this->getpath("//@total");
					$data['rfc'] = $this->getpath("//@rfc");
					$data['descripcion'] = $this->getpath("//@descripcion");
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
					$data['folio']=$this->getpath("//@folio");
				}
				
				
								
				$data['moneda'] 	= $this->getpath("//@Moneda");
				$rfcOrganizacion= $this->ChequesModel->rfcOrganizacion();
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
//				<td width='50' style='text-align:center;'><input title='Mantener copia en almacen' type='radio' name='radio-$cont' id='copiar-$cont' value='".$file."' class='copiar'></td>
				$data['tipocomprobante']= $tipoDeComprobante;
				$name = explode('_',$file);
				$listaTemporales .= "<tr>
				<td width='50'><img src='../cont/xmls/imgs/xml.jpg' width=30><b>".$data['folio']."</b></td>
				<td width='300'>".$name[1]."</td>
				<td width=300'><b>".$data['descripcion']."</b></td>
				<td width='60'><center>".$tipoDeComprobante."</center></td>
				<td align='center' width='200'><b style='color:red'>".number_format($data['total'],2,'.',',')."</b></td><td></td>
				<td width='80'>".$data['moneda']."</td>
				<td width='200'><b>".$fec[0]."</b></td>
				<td width='50'><a href='../../modulos/cont/views/captpolizas/visor.php?data=".urlencode(serialize($data))."' target='_blank'>Ver</a></td>

				<td width='50' style='text-align:center;'><input title='Mover a documento' type='radio' name='radio-$cont' id='borrar-$cont' value='".$file."' class='borrar'></td>
				
				</tr>";
				$cont++;
			}
			}
		}

		echo $listaTemporales;
	}

	function listaAlmacenBD()
	{
		$datos = $this->ChequesModel->listaTemporalesBD($_POST);
		$listaTemporales = "<tr><td width='50' style='color:white;'>*1_-{}*</td><td width='300'></td><td width='50'></td><td width='50'></td><td width='50'></td><td width='50'></td><td width='50'></td><td width='50'></td><td width='50'></td><td width='50' style='font-weight:bold;font-size:9px;text-align:center;'><button id='' onclick='buttondesclick(\"borrar\")'>Desmarcar</button></td></tr>";
		$cont = 0;
		while($d = $datos->fetch_object())
		{
			$d->json = str_replace("\\", "", $d->json);
			$json = json_decode($d->json);
			$json = $this->object_to_array($json);

			$razon = $d->receptor;
			if($d->tipo == "Egreso")
				$razon = $d->emisor;

			if($d->version == '3.2')
			{
				$descripcion = $json['Comprobante']['cfdi:Conceptos']['cfdi:Concepto']['@descripcion'];
				if(!$descripcion)
					$descripcion = $json['Comprobante']['cfdi:Conceptos']['cfdi:Concepto'][0]['@descripcion'];
			}
			if($d->version == '3.3')
			{
				$descripcion = $json['Comprobante']['cfdi:Conceptos']['cfdi:Concepto']['@Descripcion'];
				if(!$descripcion)
					$descripcion = $json['Comprobante']['cfdi:Conceptos']['cfdi:Concepto'][0]['@Descripcion'];
			}
			if(strpos($d->xml,'facturas'))
			{
				$xmlnopath = explode('/',$d->xml);
				$d->xml = $xmlnopath[3];
			}

			$listaTemporales .= "<tr>
				<td width='50'><img src='../cont/xmls/imgs/xml.jpg' width=30><b>".$d->folio."</b></td>
				<td width='300'>".$razon."</td>
				<td width=300'><b>".$descripcion."</b></td>
				<td width='60'><center>".$d->tipo."</center></td>
				<td align='center' width='200'><b style='color:red'>".number_format($d->importe,2,'.',',')."</b></td><td></td>
				<td width='80'>".$d->moneda."</td>
				<td width='200'><b>".$d->fecha."</b></td>
				<td width='50'><a href='../../modulos/cont/index.php?c=factura&f=visor_factura&uuid=$d->uuid' target='_blank'>Ver</a></td>

				<td width='50' style='text-align:center;'><input title='Mover a documento' type='radio' name='radio-$cont' id='borrar-$cont' value='../cont/xmls/facturas/temporales/".$d->xml."' class='borrar'></td>
				
				</tr>";
				$cont++;
		}
		if($cont)
			echo $listaTemporales;
		else
			echo "No se encontraron registros";
	}

	public function object_to_array($data) {
		if (is_array($data) || is_object($data)) {
			$result = array();
			foreach ($data as $key => $value) {
				$result[$key] = $this->object_to_array($value);
			}
			return $result;
		}
		return $data;
	}

	function listaFacturas()
	{
		$ruta = $this->path('../cont/')."xmls/facturas/documentosbancarios/".$_POST['idDoc'];
		echo "<option value='-' uuid='-'>Ninguna</option>";
		if($directorio = opendir($ruta))
		{
			while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
			{
				if($archivo != '.' AND $archivo != '..' AND $archivo != '.DS_Store')
				{
					$a = $archivo;
					$archivo = explode('_',$archivo);
					$archivo[2] = str_replace('.xml', '', $archivo[2]);
	   				echo "<option value='".$a."'>".$archivo[0]."_".$archivo[1]."</option>";	
				}
			}
		}
	}
	
	/*si se elimina verificar si sigue siendo un grupo sisi nose hace nada
	siya no no es un grupo debera tomar el ultimo valor si hay y ponerlo como unico
	sino hay ninguno se elimina de la factura la referencia y el xml como sino se ubiera agregado nada
	 */
		
	function EliminarArchivo()
	{
		$nueva = explode('/',$_POST['Archivo']);
		if(isset($_POST['idDoc'])){
			$poliza = $this->ChequesModel->polizaDocumento($_REQUEST['idDoc']);
			if($poliza!=0){
			
				$uuid = explode('_', $nueva[6]);
				$uuid = str_replace('.xml', '', $uuid[2]);
				if(!$uuid){
					$uuid = str_replace('.xml', '', $rutaxml);
				}
				unlink($this->path('../cont/')."xmls/facturas/".$poliza['id']."/".$nueva[6]);
				unlink($this->path('../cont/')."xmls/facturas/documentosbancarios/".$_REQUEST['idDoc']."/".$nueva[6]);
				$elimina = $this->ChequesModel->deleteMovGrupo($poliza['id'], $nueva[6]);
				//if($elimina){
					$numReg = $this->ChequesModel->numMovGrupo($poliza['id']);
					if($numReg<=1){
						
						$fact = $this->ChequesModel->ultimoGrupo($poliza['id']);
						$mov = $this->ChequesModel->movimientosPoliza($_REQUEST['idDoc']);
						while($row = $mov->fetch_array()){
							if($numReg==0){// si no hay ya ningun xml borra toda la informacion
								$this->ChequesModel->movUUID('', $row['Id'],'');
							}else{
								$this->ChequesModel->movUUID($fact['UUID'], $row['Id'],$fact['Factura']);
							}
						}
						$elimina = $this->ChequesModel->deleteMovGrupoTodo($poliza['id']);		
					}
					
			}
		}else{
			unlink($this->path('../cont/')."xmls/facturas/documentosbancarios/".$_REQUEST['idDoctemp']."/".$nueva[6]);
		}
		
			
		unlink($_POST['Archivo']);
		
	}
	function borraFacturaForm()
	{
		$Archivo = explode('/', $_POST['Archivo']);

		$this->ChequesModel->borraFacturaForm($_POST['idDoc'],$Archivo[3]);
	}
	function facturas_dialog()
	{
		
			$cont=0;global $xp;
			if(isset($_POST['idDoc'])){
				$idDoc = $_POST['idDoc'];
			}else{
				$idDoc = $_POST['idDoctemp'];
			}
			$ruta = $this->path('../cont/')."xmls/facturas/documentosbancarios/".$idDoc;
			
			if($directorio = opendir($ruta))
			{
				while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
				{
					
					if($archivo != '.' AND $archivo != '..' AND $archivo != '.DS_Store' AND $archivo != '.file' )
					{
						$archivo_str = explode('_',$archivo);
						$cont++;
						$texto 	= file_get_contents($ruta."/".$archivo);
						$texto 	= preg_replace('{<Addenda.*/Addenda>}is', '<Addenda/>', $texto);
						$texto 	= preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '<cfdi:Addenda/>', $texto);
						$texto = preg_replace('{<ComplementoConcepto.*/ComplementoConcepto>}is', '<ComplementoConcepto/>', $texto);
                			$texto = preg_replace('{<cfdi:ComplementoConcepto.*/cfdi:ComplementoConcepto>}is', '<cfdi:ComplementoConcepto/>', $texto);
				
						$xml 	= new DOMDocument();
						$xml->loadXML($texto);
						$xp = new DOMXpath($xml);
						
						if($this->getpath("//@version")){
		                		$data['version'] = $this->getpath("//@version");
		                }else{
		               	 	$data['version'] = $this->getpath("//@Version");
						}
						$version = $data['version'];
						if($version[0] == '3.3')
						{
							$data['descripcion'] = $this->getpath("//@Descripcion");
							$data['total'] = $this->getpath("//@Total");
							$data['rfc'] = $this->getpath("//@Rfc");
							$data['FechaTimbrado'] = $this->getpath("//@FechaTimbrado");
							$data['impuesto'] = $this->ChequesModel->nombreImpuesto($this->getpath("//@Impuesto"));
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
						}
						else
						{
							$data['descripcion'] = $this->getpath("//@descripcion");
							$data['total'] = $this->getpath("//@total");
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
						}
						
						if(is_array($data['descripcion']))
						{
							$data['descripcion'] = $data['descripcion'][0];
						}
	
						$rfc = $this->ChequesModel->rfcOrganizacion();
						
						if($data['rfc'][0] == $rfc['RFC'])
						{
							$tipoDeComprobante = "Ingreso";
						}
						elseif($data['rfc'][1] == $rfc['RFC'])
						{
							$tipoDeComprobante = "Egreso";	
						}
						else
						{
							$tipoDeComprobante = "Otro";	
						}
						if($data['nomina']){ $tipoDeComprobante = "Nomina";}
						$data['tipocomprobante']= $tipoDeComprobante;
						$fec = explode("T", $data['FechaTimbrado'] );
		   				echo "<tr style='text-align:center;height:50px;'>
		   				<td style='font-size:8px;'>$cont</td>
		   				<td><img src='../cont/xmls/imgs/xml.jpg' width=30></td>
		   				<td><b>". $archivo_str[0] . "_" . $archivo_str[1] ."</b></td>
		   				<td width='100'><a href='../../modulos/cont/views/captpolizas/visor.php?data=".urlencode(serialize($data))."' target='_blank'>Ver</a></td>
		   				<td><b>".$data['descripcion']."</b></td><td width='60'><center>".$tipoDeComprobante."</center></td>
		   				<td width='200' style='color:orange;'><b>".number_format($data['total'],2,'.',',')."</b></td>
		   				<td width='100'>".$data['moneda']."</td>
		   				<td>".	$fec[0]."</td>
		   				<td><a href='javascript:eliminar(\"".$ruta."/".$archivo."\")'><img src='../cont/images/eliminado.png' title='Eliminar'></a></td>
		   				<td><b style='color:green;'>Validado</b></td></tr>";	
	
						
					}
				}
			}
			if($cont==0)
			{
				echo "<tr><td style='font-size:20px'><b>No hay archivos en el documento</b></td></tr>";
			}
		
	}
	function copiaFacturaBorra()
	{
		if(isset($_POST['idDoc'])){
			$idDoc = $_POST['idDoc'];
		}else{
			$idDoc = $_POST['idDoctemp'];
		}
		$ruta = $this->path('../cont/')."xmls/facturas/documentosbancarios/".$idDoc;
		
		if(isset($_POST['idDoc'])){
			$poliza = $this->ChequesModel->polizaDocumento($idDoc);
			if($poliza!=0){
				if(!file_exists($this->path('../cont/')."xmls/facturas/".$poliza['id']))
				{
					mkdir ($this->path('../cont/')."xmls/facturas/".$poliza['id'], 0777);
				}
			}
		}
		if(!file_exists($ruta))
		{
			mkdir ($ruta, 0777);
		}
		
		// for($i=0;$i<=count($_POST['Copiar'])-1;$i++)
		// {
			// $nueva = explode('/',$_POST['Copiar'][$i]);
// 			
				// copy($_POST['Copiar'][$i], $ruta."/".$nueva[5]);
				// if(isset($_POST['idDoc'])){
					// copy($_POST['Copiar'][$i], $this->path('../cont/')."xmls/facturas/".$poliza['id']."/".$nueva[5]);
// 				
					// if($poliza!=0){
// 						
						// $mov = $this->ChequesModel->movimientosPoliza($idDoc);
						// if($mov->num_rows>0){
							// while($row = $mov->fetch_array()){
								// $uuid = explode('_', $nueva[5]);
								// $uuid = str_replace('.xml', '', $uuid[2]);
								// if( count($_POST['Copiar']) > 1 || count($_POST['Borrar'])>0){
									// $this->ChequesModel->movMultipleFactUpdate($row['Id'], $row['IdPoliza'], $row['NumMovto'], $nueva[5], $uuid);
								// }else{
									// $grupo = $this->ChequesModel->verificagrupo($poliza['Id']);
									// if($grupo==1){
										// $this->ChequesModel->movMultipleFactUpdate($row['Id'], $row['IdPoliza'], $row['NumMovto'], $nueva[5], $uuid);
									// }else{
										// $this->ChequesModel->movUUID($uuid, $row['Id'], $nueva[5]);
									// }
								// }
							// }
						// }
					// }
				// }	
// 			
		// }
		global $xp;
		for($i=0;$i<=count($_POST['Borrar'])-1;$i++)
		{
			 $nueva = explode('/',$_POST['Borrar'][$i]);
			 
			 	$archivo = $_POST['Borrar'][$i];
				$texto 	= file_get_contents($archivo);
				$texto 	= preg_replace('{<Addenda.*/Addenda>}is', '<Addenda/>', $texto);
				$texto 	= preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '<cfdi:Addenda/>', $texto);
				$texto = preg_replace('{<ComplementoConcepto.*/ComplementoConcepto>}is', '<ComplementoConcepto/>', $texto);
                $texto = preg_replace('{<cfdi:ComplementoConcepto.*/cfdi:ComplementoConcepto>}is', '<cfdi:ComplementoConcepto/>', $texto);
				
				$xml 	= new DOMDocument();
				$xml->loadXML($texto);
				$xp = new DOMXpath($xml);
				if($this->getpath("//@version"))
                	$data['version'] = $this->getpath("//@version");
                else
                	$data['version'] = $this->getpath("//@Version");

				$version = $data['version'];
				//TERMINA VERSION---------------------------------------
				if($version[0] == '3.3')
				{
					$data['uuid'] 	= $this->getpath("//@UUID");
					if(is_array($data['uuid'])){
						$data['uuid'] 	= $data['uuid'][1];
					}
					$data['folio'] 	= $this->getpath("//@Folio");
					$data['emisor'] = $this->getpath("//@Nombre");
					$data['version'] = $this->getpath("//@Version");
					$data['FechaTimbrado'] = $this->getpath("//@FechaTimbrado");
					$data['descripcion'] = $this->getpath("//@Descripcion");
					
					$data['total'] = $this->getpath("//@Total");
					$data['rfc'] = $this->getpath("//@Rfc");
				}else{
					$data['uuid'] 	= $this->getpath("//@UUID");
					$data['folio'] 	= $this->getpath("//@folio");
					$data['emisor'] = $this->getpath("//@nombre");
					$data['version'] = $this->getpath("//@version");
					$data['FechaTimbrado'] = $this->getpath("//@FechaTimbrado");
					$data['descripcion'] = $this->getpath("//@descripcion");
					$version = $data['version'];
					
					$data['total'] = $this->getpath("//@total");
					$data['rfc'] = $this->getpath("//@rfc");
				}
				
				
				$rfcOrganizacion= $this->ChequesModel->rfcOrganizacion();
				if($data['rfc'][0] == $rfcOrganizacion['RFC']){
					$nombre = $data['emisor'][1];
				}
				elseif($data['rfc'][1] == $rfcOrganizacion['RFC']){
					$nombre = $data['emisor'][0];
				}
				else{
					$nombre = $data['emisor'][1];
				}//krmnaqui
				$nombreArchivo = $data['folio']."_".$nombre."_".$data['uuid'].".xml";
				$xmlsubefac = $this->quitar_tildes($nombreArchivo);
				
				copy($_POST['Borrar'][$i], $ruta."/".$xmlsubefac);
				$nueva = explode('temporales/',$_POST['Borrar'][$i]);
				 if(isset($_POST['idDoc'])){
					copy($_POST['Borrar'][$i], $this->path('../cont/')."xmls/facturas/".$poliza['id']."/".$xmlsubefac);
				
					if($poliza!=0){
						
						// $mov = $this->ChequesModel->movimientosPoliza($idDoc);
						// if($mov->num_rows>0){
							// while($row = $mov->fetch_array()){
								// $uuid = explode('_', $nueva[5]);
								// $uuid = str_replace('.xml', '', $uuid[2]);
								// if( count($_POST['Borrar']) > 1 ){
									// $this->ChequesModel->movMultipleFactUpdate($row['Id'], $row['IdPoliza'], $row['NumMovto'], $nueva[5], $uuid);
								// }else{
									// $grupo = $this->ChequesModel->verificagrupo($poliza['id']);
									// if($grupo==1){
										// $this->ChequesModel->movMultipleFactUpdate($row['Id'], $row['IdPoliza'], $row['NumMovto'], $nueva[5], $uuid);
									// }else{
										// $this->ChequesModel->movUUID($uuid, $row['Id'], $nueva[5]);
									// }
								// }
							// }
						// }
					}
				}	
				unlink($_POST['Borrar'][$i]);
				$this->ChequesModel->quitaTemporal($nueva[1]);
			
		}

	}
	function subeFactura()
	{
		global $xp; $xmlsvalidos =  array();
		$facturasNoValidas = $facturasValidas = '';
		$numeroInvalidos = $numeroValidos = $no_hay_problema = $noOrganizacion = 0;
		$maximo = count($_FILES['factura']['name']);
		$maximo = (intval($maximo)-1);
		if( $_REQUEST['idDocfact']>0){
			$poliza = $this->ChequesModel->polizaDocumento($_REQUEST['idDocfact']);
			$ruta 	= $this->path('../cont/')."xmls/facturas/documentosbancarios/" . $_REQUEST['idDocfact']."/";
			if(!file_exists($ruta))
			{
				mkdir ($ruta,0777);
			}
			if($poliza!=0){
				if(!file_exists($this->path('../cont/')."xmls/facturas/".$poliza['id']))
				{
					mkdir ($this->path('../cont/')."xmls/facturas/".$poliza['id'], 0777);
				}
			}
		}else{
			$ruta 	= $this->path('../cont/')."xmls/facturas/documentosbancarios/" . $_REQUEST['idDocfactemp']."/";
			if(!file_exists($ruta))
			{
				mkdir ($ruta,0777);
			}
		}
		for($i = 0; $i <= $maximo; $i++)
		{

			if($_FILES["factura"]["size"][$i] > 0)
			{
				
				//Comienza obtener UUID---------------------------
				$file 	= $_FILES['factura']['tmp_name'][$i];
				$texto 	= file_get_contents($file);
				$texto 	= preg_replace('{<Addenda.*/Addenda>}is', '<Addenda/>', $texto);
				$texto 	= preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '<cfdi:Addenda/>', $texto);
				$texto = preg_replace('{<ComplementoConcepto.*/ComplementoConcepto>}is', '<ComplementoConcepto/>', $texto);
                $texto = preg_replace('{<cfdi:ComplementoConcepto.*/cfdi:ComplementoConcepto>}is', '<cfdi:ComplementoConcepto/>', $texto);
				
				$xml 	= new DOMDocument();
				$xml->loadXML($texto);
				$xp = new DOMXpath($xml);
				
				if($this->getpath("//@version")){
                	$data['version'] = $this->getpath("//@version");
                }else{
                	$data['version'] = $this->getpath("//@Version");
				}
				$version = $data['version'];
				if($version[0] == '3.3')
				{
					$data['uuid'] 	= $this->getpath("//@UUID");
					if(is_array($data['uuid'])){
						$data['uuid'] 	= $data['uuid'][1];
					}
					$data['folio'] 	= $this->getpath("//@Folio");
					$data['emisor'] = $this->getpath("//@Nombre");
					$data['version'] = $this->getpath("//@Version");
					$data['FechaTimbrado'] = $this->getpath("//@FechaTimbrado");
					$data['descripcion'] = $this->getpath("//@Descripcion");
					
					$data['total'] = $this->getpath("//@Total");
					//$rfc = $this->getpath("//@rfc");
					$data['rfc'] = $this->getpath("//@Rfc");
				}else{
					$data['uuid'] 	= $this->getpath("//@UUID");
					$data['folio'] 	= $this->getpath("//@folio");
					$data['emisor'] = $this->getpath("//@nombre");
					$data['version'] = $this->getpath("//@version");
					$data['FechaTimbrado'] = $this->getpath("//@FechaTimbrado");
					$data['descripcion'] = $this->getpath("//@descripcion");
					
					$data['total'] = $this->getpath("//@total");
					//$rfc = $this->getpath("//@rfc");
					$data['rfc'] = $this->getpath("//@rfc");
				}
				
				//$data['rfc_receptor'] = utf8_decode($rfc[1]);
				//Termina obtener UUID---------------------------
				$rfcOrganizacion= $this->ChequesModel->rfcOrganizacion();
				if($data['rfc'][0] == $rfcOrganizacion['RFC']){
					$nombre = $data['emisor'][1];
				}
				elseif($data['rfc'][1] == $rfcOrganizacion['RFC']){
					$nombre = $data['emisor'][0];
				}
				else{
					$nombre = $data['emisor'][1];
				}
				if($this->valida_xsd($version[0],$xml) && $_FILES['factura']['type'][$i] == "text/xml")
				{ 
					if($version[0] == '3.2')
					{
						$no_hay_problema = $this->valida_en_sat($data['rfc'][0],$data['rfc'][1],$data['total'],$data['uuid']);
					}
					else
					{
						$no_hay_problema = 1;
					}
					if($rfcOrganizacion['RFC'] != $data['rfc'][0] &&  $rfcOrganizacion['RFC']!= $data['rfc'][1]){
						$noOrganizacion = 0;
						$numeroInvalidos++;
						$facturasNoValidas .= $_FILES['factura']['name'][$i]."(RFC no de Organizacion),\n";
					}else{ $noOrganizacion = 1; }
					
					$nombreArchivo = $data['folio']."_".$nombre."_".$data['uuid'].".xml";
					if($noOrganizacion){
						$validaexiste = $this->existeXML($nombreArchivo);
						echo $validaexiste."/";
						if($validaexiste){
							$noOrganizacion = 0;
							$numeroInvalidos++;
							$facturasNoValidas .= $_FILES['factura']['name'][$i]."Ya existe en $validaexiste.\n";
						}else{ $noOrganizacion = 1; }
					}
					if($noOrganizacion){
						if($no_hay_problema)
						{
							$xmlsubefac = $this->quitar_tildes($nombreArchivo);
							$numeroValidos++;
							$facturasValidas .= $_FILES['factura']['name'][$i].",\n";
							//if( $_REQUEST['idDocfact'] >0){
								if(move_uploaded_file($_FILES["factura"]["tmp_name"][$i], $ruta.$xmlsubefac))
								{
									if(!in_array($xmlsubefac, $xmlsvalidos)){
										$xmlsvalidos[]= $xmlsubefac;
									}
									
								}
							//}
						}
						else
						{
							$numeroInvalidos++;
							$facturasNoValidas .= $_FILES['factura']['name'][$i]."(Cancelada),\n";
						}
					}
				}
				else
				{
					$numeroInvalidos++;
					$facturasNoValidas .= $_FILES['factura']['name'][$i]."(Estructura incorrecta),\n";
				}
			}
		}
if( $_REQUEST['idDocfact'] >0 ){
		foreach($xmlsvalidos as $rutaxml){
			if(copy($ruta.$rutaxml, $this->path('../cont/')."xmls/facturas/".$poliza['id']."/".$rutaxml ) ){
				$uuid = explode('_', $rutaxml);
				$uuid = str_replace('.xml', '', $uuid[2]);
				if(!$uuid){
					$uuid = str_replace('.xml', '', $rutaxml);
				}
				$mov = $this->ChequesModel->movimientosPoliza($_REQUEST['idDocfact']);
				if($mov->num_rows>0){
					while($row = $mov->fetch_array()){
						// if($numeroValidos>1){
							// $this->ChequesModel->movMultipleFactUpdate($row['Id'], $row['IdPoliza'], $row['NumMovto'],$rutaxml, $uuid );
						// }else{
						// /* verifica si existen datos en el grupo
						 // * si si solo agrega al grupo otro xml
						 // * sino almacena no agrega al grupo y solo ase refrencia directa al mov */
							// $grupo = $this->ChequesModel->verificagrupo($row['IdPoliza']);
							// if($grupo==1){
								// $this->ChequesModel->movMultipleFactUpdate($row['Id'], $row['IdPoliza'], $row['NumMovto'],$rutaxml, $uuid );
							// }elseif($grupo==0 && $row['Factura'] != "" ){
								// $uuid2 = explode('_', $row['Factura']);
								// $uuid2 = str_replace('.xml', '', $uuid2[2]);
								// $this->ChequesModel->movMultipleFactUpdate($row['Id'], $row['IdPoliza'], $row['NumMovto'],$row['Factura'], $uuid2);
								// $this->ChequesModel->movMultipleFactUpdate($row['Id'], $row['IdPoliza'], $row['NumMovto'],$rutaxml, $uuid );
// 								
							// }elseif($grupo==0 && $row['Factura'] == ""){
								// $this->ChequesModel->movUUID($uuid, $row['Id'],$rutaxml);
							// }
						// /* fin referencia encuentra */
						// }
					}
				}
			}
		}
	}
		
		echo $numeroValidos."-/-*".$facturasValidas."-/-*".$numeroInvalidos."-/-*".$facturasNoValidas." PARA AGREGAR FACTURAS EXISTENTES REALIZARLO DESDE ALMACEN";
	}
	function existeXML($nombreArchivo){//validar q este en documentos y polizas
		$ruta = $this->path('../cont/')."xmls/facturas/";
		$directorio = opendir($ruta);
		$rutas="";
		while($carpeta = readdir($directorio)){
			if($carpeta != '.' && $carpeta != '..' && $carpeta != '.file' && $carpeta !='.DS_Store'){
			    if (is_dir($ruta.$carpeta)){
	    				$dir = opendir($ruta.$carpeta);
	    				while($archivo = readdir($dir))
					{
						if($archivo != '.' && $archivo != '..' && $archivo != '.file' && $archivo !='.DS_Store' && $archivo != '.file.rtf'){
							$archivo = str_replace("-Cobro", "", $archivo);
							$archivo = str_replace("-Pago", "", $archivo);
							$archivo = str_replace("Parcial-", "", $archivo);
							$archivo = str_replace("-Nomina", "", $archivo);
							$archiv = $this->quitar_tildes($archivo."");
							$nombreArchiv= $this->quitar_tildes($nombreArchivo);
							$nombreArchiv = strtolower($nombreArchiv);
							$archiv = strtolower($archiv);
							//if (preg_match("/".$nombreArchiv."/i", $archiv)){//i para no diferenciar mayus y minus
							if(strcmp ($nombreArchiv , $archiv ) == 0){
							//if($nombreArchivo == $archivo){
								if($carpeta!="repetidos"){
									if($carpeta!="temporales" && $carpeta!="documentosbancarios"){
										$poliza =  $this->ChequesModel->GetAllPolizaInfoActiva($carpeta);
										if($poliza!=0){
											switch($poliza['idtipopoliza']){
												case 1: $p="Ingresos"; break;
												case 2: $p="Egresos"; break;
												case 3: $p="Diario"; break;
											}
											$rutas.= " (Poliza:".$poliza['numpol']." ".$p." ".$poliza['fecha'].")";
										}
									}else if($carpeta=="temporales"){
										$rutas.= " (Almacen)";
									}
									else if($carpeta=="documentosbancarios"){	
										$dir = opendir($ruta.$carpeta);
						    				while($subcarpeta = readdir($dir))
										{
											$dir = opendir($ruta.$carpeta."/".$subcarpeta);
	    										while($archivo2 = readdir($dir)){
												if($archivo2 != '.' && $archivo2 != '..' && $archivo2 != '.file' && $archivo2 !='.DS_Store' && $archivo2 != '.file.rtf'){
													$archiv = $this->quitar_tildes($archivo."");
													$nombreArchiv= $this->quitar_tildes($nombreArchivo);
								
													if (preg_match("/".$nombreArchiv."/i", $archiv)){
														$doc = $this->ChequesModel->editarDocumento($subcarpeta);
														$rutas.= " (Documento:".$this->tipodoc($doc['idDocumento'])." ". $doc['concepto']." ".$doc['fecha'].")";
														
													}
												}	
											}
											
						
										}
									}
							}
							
						}
					}
	    				
	    			}
			}
    			}
    		}
		return $rutas;
	}
	
/* fin xml en documentos */

/* TRASPASO */
function cuentasTraspaso(){
$cuentasbancarias = $this->ChequesModel->cuentasbancariaslistaTras($_REQUEST['idbancaria']);
if($cuentasbancarias->num_rows>0){
	echo "<option value=0>--Seleccione Destino--</option>";
	while($row = $cuentasbancarias->fetch_assoc()){
		echo "<option value='".$row['idbancaria']."/".$row['idbanco']."/".$row['cuenta']."/".$row['account_id']."/".$row['coin_id']."'>".$row['nombre']."(".$row['cuenta'].")</option>";
	}
}else{
	echo 0;
}
	
}

 /* FIN TRASPASO */ 
 
/* 		C O M P L E M E N T O   D E   P A G O (RETENCION) FACTURA		*/

 function crearRetencionFact(){
 	require_once('../SAT/config.php');
	 $datosemisor = $this->ChequesModel->infoFactura();
	 if($datosemisor->num_rows>0){
	 	if($r = $datosemisor->fetch_object()){
	 		 $rfc_cliente = $r->rfc;
			$cer_cliente = $pathdc . '/' . $r->cer;
             $key_cliente = $pathdc . '/' . $r->llave;
             $pwd_cliente = $r->clave;
            	$pac = $r->pac; 
             
			 $razonsocial = $r->razon_social;
	 	}
	 }
	 // $cer_cliente = $pathdc . '/00001000000307601732.cer';
     // $key_cliente = $pathdc . '/CSD_MATRIZ_IHA000314A38_20150716_115157.key';
     // $pwd_cliente = 'H4G4G2015';
// 	 
	$this->generaPemBancos($rfc_cliente, $key_cliente, $pwd_cliente, $pathdc);
	$fff = date('YmdHis').rand(100,999);
	$cer = $this->generaCertificadoDesdeBancos($rfc_cliente,$cer_cliente,$pathdc);
	$noc = $this->generaNoCertificadoBancos($rfc_cliente,$cer_cliente,$pathdc);
	//krmninicio
 	/* armado de la cadena original acorde al SAT */
 	$cadOri = "";
 	$cadOri.='1.0';/*version*/
	$cadOri.='|'.$noc;/*NumCert	*/				
	//$cadOri.='|'.'';/*FolioInt*/				
	$cadOri.='|'.$_REQUEST['fechar'];/*FechaExp*/
	$cadOri.='|'.$_REQUEST['CveRetenc'];/*CveRetenc*/		
	
	if ($_REQUEST['CveRetenc'] == 25){//otro tipo de retenciones
		$_REQUEST['DescRetenc'] = preg_replace('/&quot;/', '"', $_REQUEST['DescRetenc']);
		$cadOri.='|'.$_REQUEST['DescRetenc'];/*DescRetenc*/	
	}
	$cadOri.='|'.$rfc_cliente;/*RFCEmisor */	
	$cadOri.='|'.($razonsocial);/*NomDenRazSocE*/	
	//$cadOri.='|'.'';/*CURPE*/	No hay curp en emisor por el momento
	$datosPrv = $this->ChequesModel->datosproveedor($_REQUEST['beneficiarior']);
	
	
	$datosPrv['razon_social']= preg_replace('/&/', '&amp;', $datosPrv['razon_social']);
	$razonsocial= preg_replace('/&/', '&amp;',$razonsocial);
	$razonsocial= preg_replace('/""/', '&quot;',$razonsocial);
	$datosPrv['razon_social']= preg_replace('/""/', '&quot;;',$datosPrv['razon_social']);
	$datosPrv['rfc']= preg_replace('/&/', '&amp;',$datosPrv['rfc']);
	$rfc_cliente= preg_replace('/&/', '&amp;', $rfc_cliente);
    $razonsocial=preg_replace('/&amp;quot;/', '&quot;',$razonsocial);
	$razonsocial=preg_replace('/&amp;apos;/', '&apos;',$razonsocial);
	$razonsocial=preg_replace("/\n/","",$razonsocial);
	$datosPrv['razon_social'] = preg_replace("/\n/","", $datosPrv['razon_social']);
	
	if ($datosPrv['PaisdeResidencia']==1 || $datosPrv['PaisdeResidencia']==0){
		
		$cadOri.='|'.'Nacional';/*Nacionalidad*/	
		$cadOri.='|'.$datosPrv['rfc'];/*RFCRecep*/	
		$cadOri.='|'.($datosPrv['razon_social']);/*NomDenRazSocR*/
		if($datosPrv['curp']){
			$cadOri.='|'.$datosPrv['curp'];/*CURPR*/	
		}
		
	}else{//extranjero
		$cadOri.='|'.'Extranjero';/*Nacionalidad*/	
		if($datosPrv['numidfiscal']){
			$cadOri.='|'.$datosPrv['numidfiscal'];/*NumRegIdTrib*/
		}
		$cadOri.='|'.($datosPrv['razon_social']);/*NomDenRazSocR*/		
	}
	$cadOri.='|'.$_REQUEST['pInicial'];/*MesIni*/		
	$cadOri.='|'.$_REQUEST['pFinal'];/*MesFin*/	
	$cadOri.='|'.$_REQUEST['ejercicior'];/*Ejerc*/	
	$cadOri.='|'.$_REQUEST['montoTotOperacion'];/*montoTotOperacion*/	
	$cadOri.='|'.$_REQUEST['montoTotGrav'];/*montoTotGrav*/			
	$cadOri.='|'.$_REQUEST['montoTotExent'];/*montoTotExent*/	
	$cadOri.='|'.$_REQUEST['montoTotRet'];/*montoTotRet*/				
	
	 
	 //*impuestos */
	foreach ($_REQUEST['importebase'] as $key=>$val)	{
		if($key!=0){
			$_REQUEST['importebase'][$key] = str_replace(',', '', $_REQUEST['importebase'][$key]);
			$_REQUEST['impuestoretenido'][$key] = str_replace(',', '', $_REQUEST['impuestoretenido'][$key]);
			
			$cadOri.='|'.$_REQUEST['importebase'][$key];/*BaseRet	*/	
			$cadOri.='|'.$_REQUEST['retencionlistar'][$key];/*Impuesto*/	
			$cadOri.='|'.$_REQUEST['impuestoretenido'][$key];/*montoRet*/
			$cadOri.='|'.$_REQUEST['tipopagor'][$key];/*TipoPagoRet*/	
		}	
	}		
	
	

	
	
	
		
    /* genera el xml retencion */
    
   
    $xml = new DomDocument('1.0', 'ISO-8859-1');
	//$xml->preserveWhiteSpace = false;
	
	$raiz = $xml->createElement('retenciones:Retenciones');
	$raiz->setAttribute('xmlns:retenciones', 'http://www.sat.gob.mx/esquemas/retencionpago/1');
	$raiz->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
	$raiz->setAttribute('Version',"1.0");
	$raiz->setAttribute('CveRetenc',$_REQUEST['CveRetenc']);
	$raiz->setAttribute('FechaExp',$_REQUEST['fechar']);
	if ($_REQUEST['CveRetenc'] == 25){//otro tipo de retenciones
		$raiz->setAttribute('DescRetenc',$_REQUEST['DescRetenc']);
	}
	$raiz->setAttribute('Cert',$cer);//certificado se genera
	$raiz->setAttribute('NumCert',$noc);//certificado fijo
	
	
	$Emisor = $xml->createElement('retenciones:Emisor');
	$Emisor->setAttribute('RFCEmisor',$rfc_cliente);
	$Emisor->setAttribute('NomDenRazSocE',($razonsocial));
	//$Emisor->setAttribute('CURPE','');
	$raiz->appendChild( $Emisor );
	
	$receptor = $xml->createElement('retenciones:Receptor');
	if ($datosPrv['PaisdeResidencia']==1 || $datosPrv['PaisdeResidencia']==0){
		
		
		$receptorDatos = $xml->createElement('retenciones:Nacional');
		$receptor->setAttribute('Nacionalidad','Nacional');
		$receptorDatos->setAttribute('RFCRecep',$datosPrv['rfc']);
		$receptorDatos->setAttribute('NomDenRazSocR',($datosPrv['razon_social']));
		if($datosPrv['curp']){
			$receptorDatos->setAttribute('CURPR',$datosPrv['curp']);
		}
		
		
	}else{//extranjero
		$receptorDatos = $xml->createElement('retenciones:Extranjero');
		$receptor->setAttribute('Nacionalidad','Extranjero');
		if($datosPrv['numidfiscal']){
			$receptorDatos->setAttribute('NumRegIdTrib',$datosPrv['numidfiscal']);
		}
		$receptorDatos->setAttribute('NomDenRazSocR',($datosPrv['razon_social']));
		
	}
	$receptor->appendChild($receptorDatos);
	$raiz->appendChild( $receptor );
	
	$periodo = $xml->createElement('retenciones:Periodo');
	$periodo->setAttribute('MesIni',$_REQUEST['pInicial']);
	$periodo->setAttribute('MesFin',$_REQUEST['pFinal']);
	$periodo->setAttribute('Ejerc',$_REQUEST['ejercicior']);
	
	$raiz->appendChild( $periodo );
	
	$Totales = $xml->createElement('retenciones:Totales');
	$Totales->setAttribute('montoTotOperacion',$_REQUEST['montoTotOperacion']);
	$Totales->setAttribute('montoTotGrav',$_REQUEST['montoTotGrav']);
	$Totales->setAttribute('montoTotExent',$_REQUEST['montoTotExent']); 
	$Totales->setAttribute('montoTotRet',$_REQUEST['montoTotRet']);
 
	// este se generara por los impuesto que aya agregado
	
	foreach ($_REQUEST['importebase'] as $key=>$val)	{
		if($key!=0){
			$impuesto = $xml->createElement('retenciones:ImpRetenidos');
			$impuesto->setAttribute('TipoPagoRet',$_REQUEST['tipopagor'][$key]);
			$impuesto->setAttribute('montoRet',$_REQUEST['impuestoretenido'][$key]);
			$impuesto->setAttribute('BaseRet',$_REQUEST['importebase'][$key]);
			$impuesto->setAttribute('Impuesto',$_REQUEST['retencionlistar'][$key]);
			$Totales->appendChild($impuesto);  
		}	

	}	
	$raiz->appendChild( $Totales );
	// fin impuestos //
	$schemaLocation = "";
	/* retenciones:Complemento */
	
	
	if($_REQUEST['CveRetenc']==19){//Enajenacion de acciones u operaciones en bolsa de valores
	/* enajenaciondeacciones:EnajenaciondeAcciones */ 
	$complento = $xml->createElement('retenciones:Complemento');
		$enajenaciondeacciones = $xml->createElement('enajenaciondeacciones:EnajenaciondeAcciones');
		$enajenaciondeacciones->setAttribute('Version','1.0');
		$enajenaciondeacciones->setAttribute('ContratoIntermediacion',$_REQUEST['ContratoIntermediacion']);
		$enajenaciondeacciones->setAttribute('Ganancia',$_REQUEST['Ganancia']);
		$enajenaciondeacciones->setAttribute('Perdida',$_REQUEST['Perdida']);
		$complento->appendChild($enajenaciondeacciones);
		$raiz->appendChild( $complento );
		$cadOri.='|1.0';
		$cadOri.='|'.$_REQUEST['ContratoIntermediacion'];
		$cadOri.='|'.$_REQUEST['Ganancia'];
		$cadOri.='|'.$_REQUEST['Perdida'];
		
		$raiz->setAttribute('xmlns:enajenaciondeacciones', 'http://www.sat.gob.mx/esquemas/retencionpago/1/enajenaciondeacciones');
		$schemaLocation = "http://www.sat.gob.mx/esquemas/retencionpago/1/enajenaciondeacciones http://www.sat.gob.mx/esquemas/retencionpago/1/enajenaciondeacciones/enajenaciondeacciones.xsd ";
		
		/* fin enajenaciondeacciones */
	}elseif($_REQUEST['CveRetenc'] == 17){//Arrendamiento en fideicomiso
		/* arrendamientoenfideicomiso */
		$complento = $xml->createElement('retenciones:Complemento');
		$tipo = $xml->createElement('arrendamientoenfideicomiso:Arrendamientoenfideicomiso');
		$tipo->setAttribute('Version','1.0');
		$tipo->setAttribute('PagProvEfecPorFiduc',$_REQUEST['PagProvEfecPorFiduc']);
		$tipo->setAttribute('RendimFideicom',$_REQUEST['RendimFideicom']); 
		$tipo->setAttribute('DeduccCorresp',$_REQUEST['DeduccCorresp']); 
		//$tipo->setAttribute('MontTotRet','01'); 
		if($_REQUEST['MontResFiscDistFibras']){
			$tipo->setAttribute('MontResFiscDistFibras',$_REQUEST['MontResFiscDistFibras']); 
		}
		if($_REQUEST['MontOtrosConceptDistr']){
			$tipo->setAttribute('MontOtrosConceptDistr',$_REQUEST['MontOtrosConceptDistr']); 
		}
		if($_REQUEST['DescrMontOtrosConceptDistr']){
			$tipo->setAttribute('DescrMontOtrosConceptDistr',$_REQUEST['DescrMontOtrosConceptDistr']); 
		}
		
		$complento->appendChild($tipo);
		$raiz->appendChild( $complento );
		$cadOri.='|1.0';
		$cadOri.='|'.$_REQUEST['PagProvEfecPorFiduc'];
		$cadOri.='|'.$_REQUEST['RendimFideicom'];
		$cadOri.='|'.$_REQUEST['DeduccCorresp'];
		//5. MontTotRet este lo lo agrege porq no encontre el campo acorde a compaq igual es opcional
		if($_REQUEST['MontResFiscDistFibras']){
			$cadOri.='|'.$_REQUEST['MontResFiscDistFibras'];
		} 
		if($_REQUEST['MontOtrosConceptDistr']){
			$cadOri.='|'.$_REQUEST['MontOtrosConceptDistr'];
		}
		if($_REQUEST['DescrMontOtrosConceptDistr']){
			$cadOri.='|'.$_REQUEST['DescrMontOtrosConceptDistr'];
		}

		$raiz->setAttribute('xmlns:arrendamientoenfideicomiso', 'http://www.sat.gob.mx/esquemas/retencionpago/1/arrendamientoenfideicomiso');
		$schemaLocation = "http://www.sat.gob.mx/esquemas/retencionpago/1/arrendamientoenfideicomiso http://www.sat.gob.mx/esquemas/retencionpago/1/arrendamientoenfideicomiso/arrendamientoenfideicomiso.xsd";

 
		/* fin arrendamientoenfideicomiso */
				
	}elseif($_REQUEST['CveRetenc'] == 14){//Dividendos o utilidades distribuidas
		//dividendos:Dividendos 
		$complento = $xml->createElement('retenciones:Complemento');
		$dividendo = $xml->createElement('dividendos:Dividendos');
		$dividendo->setAttribute('Version','1.0');
		$dividendoDividOUtil = $xml->createElement('dividendos:DividOUtil');
		$dividendoDividOUtil->setAttribute('CveTipDivOUtil',$_REQUEST['CveTipDivOUtil']); 
		$dividendoDividOUtil->setAttribute('MontISRAcredRetMexico',$_REQUEST['MontISRAcredRetMexico']); 
		$dividendoDividOUtil->setAttribute('MontISRAcredRetExtranjero',$_REQUEST['MontISRAcredRetExtranjero']); 
		if($_REQUEST['MontRetExtDivExt']){
			$dividendoDividOUtil->setAttribute('MontRetExtDivExt',$_REQUEST['MontRetExtDivExt']); 
		}
		$dividendoDividOUtil->setAttribute('TipoSocDistrDiv',$_REQUEST['TipoSocDistrDiv']); 
		if($_REQUEST['MontISRAcredNal']){
			$dividendoDividOUtil->setAttribute('MontISRAcredNal',$_REQUEST['MontISRAcredNal']); 
		}
		if($_REQUEST['MontDivAcumNal']){
			$dividendoDividOUtil->setAttribute('MontDivAcumNal',$_REQUEST['MontDivAcumNal']);
		}
		if($_REQUEST['MontDivAcumExt']){
			$dividendoDividOUtil->setAttribute('MontDivAcumExt',$_REQUEST['MontDivAcumExt']); 
		}
		$dividendoRemanente = $xml->createElement('dividendos:Remanente');
		if($_REQUEST['ProporcionRem']){
			$dividendoRemanente->setAttribute('ProporcionRem',$_REQUEST['ProporcionRem']);
		}
		
		$dividendo->appendChild($dividendoDividOUtil);
		$dividendo->appendChild($dividendoRemanente);
		$complento->appendChild($dividendo);
		$raiz->appendChild( $complento );
		$cadOri.='|1.0';
		$cadOri.='|'.$_REQUEST['CveTipDivOUtil'];
		$cadOri.='|'.$_REQUEST['MontISRAcredRetMexico'];
		$cadOri.='|'.$_REQUEST['MontISRAcredRetExtranjero'];
		if($_REQUEST['MontRetExtDivExt']){
			$cadOri.='|'.$_REQUEST['MontRetExtDivExt'];
		}
		$cadOri.='|'.$_REQUEST['TipoSocDistrDiv'];
		if($_REQUEST['MontISRAcredNal']){
			$cadOri.='|'.$_REQUEST['MontISRAcredNal'];
		}
		if($_REQUEST['MontDivAcumNal']){
			$cadOri.='|'.$_REQUEST['MontDivAcumNal'];
		}
		if($_REQUEST['MontDivAcumExt']){
			$cadOri.='|'.$_REQUEST['MontDivAcumExt'];
		}
		if($_REQUEST['ProporcionRem']){
			$cadOri.='|'.$_REQUEST['ProporcionRem'];
		}
		$raiz->setAttribute('xmlns:dividendos', 'http://www.sat.gob.mx/esquemas/retencionpago/1/dividendos');
		$schemaLocation = "http://www.sat.gob.mx/esquemas/retencionpago/1/dividendos http://www.sat.gob.mx/esquemas/retencionpago/1/dividendos/dividendos.xsd ";
		
		/* FIN DIVIDENDO */
	}elseif($_REQUEST['CveRetenc'] == 21){//Fideicomisos que no realizan actividades empresariales
	/* fideicomisonoempresarial:Fideicomisonoempresarial  */
	$complento = $xml->createElement('retenciones:Complemento');
		$fideicomisonoempresarial = $xml->createElement('fideicomisonoempresarial:Fideicomisonoempresarial');
		$fideicomisonoempresarial->setAttribute('Version','1.0');
		
		$IngresosOEntradas = $xml->createElement('fideicomisonoempresarial:IngresosOEntradas');
		$IngresosOEntradas->setAttribute('MontTotEntradasPeriodo',$_REQUEST['MontTotEntradasPeriodo']);
		$IngresosOEntradas->setAttribute('PartPropAcumDelFideicom',$_REQUEST['PartPropAcumDelFideicom']);
		$IngresosOEntradas->setAttribute('PropDelMontTot',$_REQUEST['PropDelMontTot']);
		
		$IntegracIngresos = $xml->createElement('fideicomisonoempresarial:IntegracIngresos');
		
		$IntegracIngresos->setAttribute('Concepto',$_REQUEST['Concepto']);
		
		$IngresosOEntradas->appendChild($IntegracIngresos);
		$fideicomisonoempresarial->appendChild($IngresosOEntradas);
		
		//////////fideicomisonoempresarial:DeduccOSalidas
		$DeduccOSalidas = $xml->createElement('fideicomisonoempresarial:DeduccOSalidas');
		$DeduccOSalidas->setAttribute('MontTotEgresPeriodo',$_REQUEST['MontTotEgresPeriodo']);
		$DeduccOSalidas->setAttribute('PartPropDelFideicom',$_REQUEST['PartPropDelFideicom']);
		$DeduccOSalidas->setAttribute('PropDelMontTot',$_REQUEST['PropDelMontTotEg']);
		
		$IntegracEgresos = $xml->createElement('fideicomisonoempresarial:IntegracEgresos');
		$IntegracEgresos->setAttribute('ConceptoS',$_REQUEST['ConceptoS']);
		
		$DeduccOSalidas->appendChild($IntegracEgresos);
		$fideicomisonoempresarial->appendChild($DeduccOSalidas);
		
		//fideicomisonoempresarial:RetEfectFideicomiso
		$RetEfectFideicomiso = $xml->createElement('fideicomisonoempresarial:RetEfectFideicomiso');
		$RetEfectFideicomiso->setAttribute('MontRetRelPagFideic',$_REQUEST['MontRetRelPagFideic']);
		$RetEfectFideicomiso->setAttribute('DescRetRelPagFideic',$_REQUEST['DescRetRelPagFideic']);
		
		$fideicomisonoempresarial->appendChild($RetEfectFideicomiso);
		//asigna todo el fideicomiso al complento
		$complento->appendChild($fideicomisonoempresarial); 
		$raiz->appendChild( $complento );
		$cadOri.='|1.0';
		$cadOri.='|'.$_REQUEST['MontTotEntradasPeriodo'];
		$cadOri.='|'.$_REQUEST['PartPropAcumDelFideicom'];
		$cadOri.='|'.$_REQUEST['PropDelMontTot']; //Patrón \d{1,3}\.\d{1,6} 
		$cadOri.='|'.$_REQUEST['Concepto'];
		$cadOri.='|'.$_REQUEST['MontTotEgresPeriodo'];
		$cadOri.='|'.$_REQUEST['PartPropDelFideicom'];
		$cadOri.='|'.$_REQUEST['PropDelMontTotEg'];//Patrón \d{1,3}\.\d{1,6}
		$cadOri.='|'.$_REQUEST['ConceptoS'];
		$cadOri.='|'.$_REQUEST['MontRetRelPagFideic'];
		$cadOri.='|'.$_REQUEST['DescRetRelPagFideic'];

		$raiz->setAttribute('xmlns:fideicomisonoempresarial', 'http://www.sat.gob.mx/esquemas/retencionpago/1/fideicomisonoempresarial');
		$schemaLocation = "http://www.sat.gob.mx/esquemas/retencionpago/1/fideicomisonoempresarial http://www.sat.gob.mx/esquemas/retencionpago/1/fideicomisonoempresarial/fideicomisonoempresarial.xsd ";
		
/* FIN fideicomisonoempresarial:Fideicomisonoempresarial		*/
	}elseif($_REQUEST['CveRetenc'] == 16){//Intereses
	/*	intereses:Intereses		*/
	$complento = $xml->createElement('retenciones:Complemento');
		$Intereses = $xml->createElement('intereses:Intereses');
		$Intereses->setAttribute('Version','1.0');
		$Intereses->setAttribute('SistFinanciero',$_REQUEST['SistFinanciero']);
		$Intereses->setAttribute('RetiroAORESRetInt',$_REQUEST['RetiroAORESRetInt']);
		$Intereses->setAttribute('OperFinancDerivad',$_REQUEST['OperFinancDerivad']);
		$Intereses->setAttribute('MontIntNominal',$_REQUEST['MontIntNominal']);
		$Intereses->setAttribute('MontIntReal',$_REQUEST['MontIntReal']);
		$Intereses->setAttribute('Perdida',$_REQUEST['Perdida']);
		
		$complento->appendChild($Intereses);
		$raiz->appendChild( $complento );
		$cadOri.='|1.0';
		$cadOri.='|'.$_REQUEST['SistFinanciero'];
		$cadOri.='|'.$_REQUEST['RetiroAORESRetInt'];
		$cadOri.='|'.$_REQUEST['OperFinancDerivad'];
		$cadOri.='|'.$_REQUEST['MontIntNominal'];
		$cadOri.='|'.$_REQUEST['MontIntReal'];
		$cadOri.='|'.$_REQUEST['Perdida'];

		$raiz->setAttribute('xmlns:intereses', 'http://www.sat.gob.mx/esquemas/retencionpago/1/intereses');
		$schemaLocation = "http://www.sat.gob.mx/esquemas/retencionpago/1/intereses http://www.sat.gob.mx/esquemas/retencionpago/1/intereses/intereses.xsd ";
		
		/* FIN $Intereses		*/
	
	}elseif($_REQUEST['CveRetenc'] == 23){//Intereses reales deducibles por creditos hipotecarios
	/*		intereseshipotecarios:Intereseshipotecarios		*/
	$complento = $xml->createElement('retenciones:Complemento');
		$Intereseshipotecarios = $xml->createElement('intereseshipotecarios:Intereseshipotecarios');
		$Intereseshipotecarios->setAttribute('Version','1.0');
		$Intereseshipotecarios->setAttribute('CreditoDeInstFinanc',$_REQUEST['CreditoDeInstFinanc']);
		$Intereseshipotecarios->setAttribute('SaldoInsoluto',$_REQUEST['SaldoInsoluto']);
		if($_REQUEST['PropDeducDelCredit']){
			$Intereseshipotecarios->setAttribute('PropDeducDelCredit',$_REQUEST['PropDeducDelCredit']);
		}
		if($_REQUEST['MontTotIntNominalesDev']){
			$Intereseshipotecarios->setAttribute('MontTotIntNominalesDev',$_REQUEST['MontTotIntNominalesDev']);
		}
		if($_REQUEST['MontTotIntNominalesDevYPag']){
			$Intereseshipotecarios->setAttribute('MontTotIntNominalesDevYPag',$_REQUEST['MontTotIntNominalesDevYPag']);
		}
		if($_REQUEST['MontTotIntRealPagDeduc']){
			$Intereseshipotecarios->setAttribute('MontTotIntRealPagDeduc',$_REQUEST['MontTotIntRealPagDeduc']);
		}
		if($_REQUEST['NumContrato']){
			$Intereseshipotecarios->setAttribute('NumContrato',$_REQUEST['NumContrato']);
		}
		$complento->appendChild($Intereseshipotecarios);
		$raiz->appendChild( $complento );
		$cadOri.='|1.0';
		$cadOri.='|'.$_REQUEST['CreditoDeInstFinanc'];
		$cadOri.='|'.$_REQUEST['SaldoInsoluto'];
		if($_REQUEST['PropDeducDelCredit']){
			$cadOri.='|'.$_REQUEST['PropDeducDelCredit'];
		}
		if($_REQUEST['MontTotIntNominalesDev']){
			$cadOri.='|'.$_REQUEST['MontTotIntNominalesDev'];
		}
		if($_REQUEST['MontTotIntNominalesDevYPag']){
			$cadOri.='|'.$_REQUEST['MontTotIntNominalesDevYPag'];
		}
		if($_REQUEST['MontTotIntRealPagDeduc']){
			$cadOri.='|'.$_REQUEST['MontTotIntRealPagDeduc'];
		}
		if($_REQUEST['NumContrato']){
			$cadOri.='|'.$_REQUEST['NumContrato'];
		}
		
		$raiz->setAttribute('xmlns:intereseshipotecarios', 'http://www.sat.gob.mx/esquemas/retencionpago/1/intereseshipotecarios');
		$schemaLocation = "http://www.sat.gob.mx/esquemas/retencionpago/1/intereseshipotecarios http://www.sat.gob.mx/esquemas/retencionpago/1/intereseshipotecarios/intereseshipotecarios.xsd ";
		
		/*		FIN intereseshipotecarios:Intereseshipotecarios		*/
	
	}elseif($_REQUEST['CveRetenc'] == 24){//Operaciones Financieras Derivadas de Capital
	/* operacionesconderivados:Operacionesconderivados		*/
	$complento = $xml->createElement('retenciones:Complemento');
		$Operacionesconderivados = $xml->createElement('operacionesconderivados:Operacionesconderivados');
		$Operacionesconderivados->setAttribute('Version','1.0');
		$Operacionesconderivados->setAttribute('MontGanAcum',$_REQUEST['MontGanAcum']);
		$Operacionesconderivados->setAttribute('MontPerdDed',$_REQUEST['MontPerdDed']);
		
		$complento->appendChild($Operacionesconderivados);
		$raiz->appendChild( $complento );
		$cadOri.='|1.0';
		$cadOri.='|'.$_REQUEST['MontGanAcum'];
		$cadOri.='|'.$_REQUEST['MontPerdDed'];
		
		$raiz->setAttribute('xmlns:operacionesconderivados', 'http://www.sat.gob.mx/esquemas/retencionpago/1/operacionesconderivados');
		$schemaLocation = "http://www.sat.gob.mx/esquemas/retencionpago/1/operacionesconderivados http://www.sat.gob.mx/esquemas/retencionpago/1/operacionesconderivados/operacionesconderivados.xsd ";
		
	/* 	FIN operacionesconderivados:Operacionesconderivados		*/
	
	}elseif($_REQUEST['CveRetenc'] == 18){//Pagos realizados a favor de residentes en el extranjero
	/* pagosaextranjeros:Pagosaextranjeros		*/
	$complento = $xml->createElement('retenciones:Complemento');
		$Pagosaextranjeros = $xml->createElement('pagosaextranjeros:Pagosaextranjeros');
		$Pagosaextranjeros->setAttribute('Version','1.0');
		$Pagosaextranjeros->setAttribute('EsBenefEfectDelCobro',$_REQUEST['EsBenefEfectDelCobro']);
		//pagosaextranjeros:NoBeneficiario
		if($_REQUEST['EsBenefEfectDelCobro']=="NO"){
		
		$NoBeneficiario = $xml->createElement('pagosaextranjeros:NoBeneficiario');
		$NoBeneficiario->setAttribute('PaisDeResidParaEfecFisc',$_REQUEST['PaisDeResidParaEfecFisc']);
		$NoBeneficiario->setAttribute('ConceptoPago',$_REQUEST['TipoContribuyenteSujetoRetencionNoBene']);
		$NoBeneficiario->setAttribute('DescripcionConcepto',$_REQUEST['ConceptoPagoNo']);
		
		$Pagosaextranjeros->appendChild($NoBeneficiario);
		}else{
			//pagosaextranjeros:Beneficiario
			$Beneficiario = $xml->createElement('pagosaextranjeros:Beneficiario');
			$Beneficiario->setAttribute('RFC',$_REQUEST['rfc']);
			$Beneficiario->setAttribute('CURP',$_REQUEST['CURP']);
			$Beneficiario->setAttribute('NomDenRazSocB',$_REQUEST['NomDenRazSocB']);
			$Beneficiario->setAttribute('ConceptoPago',$_REQUEST['TipoContribuyenteSujetoRetencionBene']);
			$Beneficiario->setAttribute('DescripcionConcepto',$_REQUEST['ConceptoPago']);
			
			$Pagosaextranjeros->appendChild($Beneficiario);
		}
		$complento->appendChild($Pagosaextranjeros);
		$raiz->appendChild( $complento );
		
		$cadOri.='|1.0';
		$cadOri.='|'.$_REQUEST['EsBenefEfectDelCobro'];
		if($_REQUEST['EsBenefEfectDelCobro']=="NO"){
			$cadOri.='|'.$_REQUEST['PaisDeResidParaEfecFisc'];
			$cadOri.='|'.$_REQUEST['TipoContribuyenteSujetoRetencionNoBene'];
			$cadOri.='|'.$_REQUEST['ConceptoPagoNo'];
		}else{
			$cadOri.='|'.$_REQUEST['rfc'];
			$cadOri.='|'.$_REQUEST['CURP'];
			$cadOri.='|'.$_REQUEST['NomDenRazSocB'];
			$cadOri.='|'.$_REQUEST['TipoContribuyenteSujetoRetencionBene'];
			$cadOri.='|'.$_REQUEST['ConceptoPago'];
		}
		
		$raiz->setAttribute('xmlns:pagosaextranjeros', 'http://www.sat.gob.mx/esquemas/retencionpago/1/pagosaextranjeros');
		$schemaLocation = "http://www.sat.gob.mx/esquemas/retencionpago/1/pagosaextranjeros http://www.sat.gob.mx/esquemas/retencionpago/1/pagosaextranjeros/pagosaextranjeros.xsd ";
		
/* FIN		pagosaextranjeros:Pagosaextranjeros		*/
	
	}elseif($_REQUEST['CveRetenc'] == 22){//Planes personales de retiro
	/*		planesderetiro:Planesderetiro		*/
	$complento = $xml->createElement('retenciones:Complemento');
		$Planesderetiro = $xml->createElement('planesderetiro:Planesderetiro');
		$Planesderetiro->setAttribute('Version','1.0');
		$Planesderetiro->setAttribute('SistemaFinanc',$_REQUEST['SistemaFinanc']);
		if($_REQUEST['MontTotAportAnioInmAnterior']){
			$Planesderetiro->setAttribute('MontTotAportAnioInmAnterior',$_REQUEST['MontTotAportAnioInmAnterior']);
		}
		$Planesderetiro->setAttribute('MontIntRealesDevengAniooInmAnt',$_REQUEST['MontIntRealesDevengAniooInmAnt']);
		$Planesderetiro->setAttribute('HuboRetirosAnioInmAntPer',$_REQUEST['HuboRetirosAnioInmAntPer']);
		$Planesderetiro->setAttribute('MontTotRetiradoAnioInmAntPer',$_REQUEST['MontTotRetiradoAnioInmAntPer']);
		if($_REQUEST['MontTotExentRetiradoAnioInmAnt']){
			$Planesderetiro->setAttribute('MontTotExentRetiradoAnioInmAnt',$_REQUEST['MontTotExentRetiradoAnioInmAnt']);
		}
		if($_REQUEST['MontTotExedenteAnioInmAnt']){
			$Planesderetiro->setAttribute('MontTotExedenteAnioInmAnt',$_REQUEST['MontTotExedenteAnioInmAnt']);
		}
		$Planesderetiro->setAttribute('HuboRetirosAnioInmAnt',$_REQUEST['HuboRetirosAnioInmAnt']);
		if($_REQUEST['MontTotRetiradoAnioInmAnt'])
		$Planesderetiro->setAttribute('MontTotRetiradoAnioInmAnt',$_REQUEST['MontTotRetiradoAnioInmAnt']);
		
		$complento->appendChild($Planesderetiro);
		$raiz->appendChild( $complento );
		
		$cadOri.='|1.0';
		$cadOri.='|'.$_REQUEST['SistemaFinanc'];
		if($_REQUEST['MontTotAportAnioInmAnterior']){
			$cadOri.='|'.$_REQUEST['MontTotAportAnioInmAnterior'];
		}
		$cadOri.='|'.$_REQUEST['MontIntRealesDevengAniooInmAnt'];
		$cadOri.='|'.$_REQUEST['HuboRetirosAnioInmAntPer'];
		if($_REQUEST['MontTotExentRetiradoAnioInmAnt']){
			$cadOri.='|'.$_REQUEST['MontTotRetiradoAnioInmAntPer'];
		}
		if($_REQUEST['MontTotExentRetiradoAnioInmAnt']){
			$cadOri.='|'.$_REQUEST['MontTotExentRetiradoAnioInmAnt'];
		}
		if($_REQUEST['MontTotExedenteAnioInmAnt']){
			$cadOri.='|'.$_REQUEST['MontTotExedenteAnioInmAnt'];
		}
		$cadOri.='|'.$_REQUEST['HuboRetirosAnioInmAnt'];
		if($_REQUEST['MontTotRetiradoAnioInmAnt']){
			$cadOri.='|'.$_REQUEST['MontTotRetiradoAnioInmAnt'];
		}
		
		$raiz->setAttribute('xmlns:planesderetiro', 'http://www.sat.gob.mx/esquemas/retencionpago/1/planesderetiro');
		$schemaLocation = "http://www.sat.gob.mx/esquemas/retencionpago/1/planesderetiro http://www.sat.gob.mx/esquemas/retencionpago/1/planesderetiro/planesderetiro.xsd ";
		
		
/* 	FIN		planesderetiro:Planesderetiro		*/
	
	}elseif($_REQUEST['CveRetenc'] == 20){//Obtencion de premios
	/*		premios:Premios		*/
	$complento = $xml->createElement('retenciones:Complemento');
		if($_REQUEST['EntidadFederativa']<10){ $_REQUEST['EntidadFederativa']='0'.$_REQUEST['EntidadFederativa'];}
		$Premios = $xml->createElement('premios:Premios');
		$Premios->setAttribute('Version','1.0');
		$Premios->setAttribute('EntidadFederativa',$_REQUEST['EntidadFederativa']);
		$Premios->setAttribute('MontTotPago',$_REQUEST['MontTotPago']);
		$Premios->setAttribute('MontTotPagoGrav',$_REQUEST['MontTotPagoGrav']);
		$Premios->setAttribute('MontTotPagoExent',$_REQUEST['MontTotPagoExent']);

		$complento->appendChild($Premios);
		$raiz->appendChild( $complento );
		
		$cadOri.='|1.0';
		$cadOri.='|'.$_REQUEST['EntidadFederativa'];
		$cadOri.='|'.$_REQUEST['MontTotPago'];
		$cadOri.='|'.$_REQUEST['MontTotPagoGrav'];
		$cadOri.='|'.$_REQUEST['MontTotPagoExent'];
	
		$raiz->setAttribute('xmlns:premios', 'http://www.sat.gob.mx/esquemas/retencionpago/1/premios');
		$schemaLocation = "http://www.sat.gob.mx/esquemas/retencionpago/1/premios http://www.sat.gob.mx/esquemas/retencionpago/1/premios/premios.xsd ";
	
/*		FIN 	premios:Premios		*/
	}


	// $cadOri= preg_replace('/&quot;/', '"', $cadOri);
	// $cadOri= preg_replace('/&apos;/', "'", $cadOri);
	// $cadOri= preg_replace('/&amp;/', '&',$cadOri);
// 	
	// $cadOri=preg_replace('/\|{2,}/', '|',$cadOri);
	// $cadOri=preg_replace('/ {2,}/', ' ',$cadOri);
	$cadOri='||'.$cadOri.'||';
	
	$raiz->setAttribute('xsi:schemaLocation','http://www.sat.gob.mx/esquemas/retencionpago/1 http://www.sat.gob.mx/esquemas/retencionpago/1/retencionpagov1.xsd'.' '.$schemaLocation);
	//$rfc_cliente= preg_replace('/&amp;/', '&', $rfc_cliente);
	
	$ori = $this->generaCadenaOriginalBancos($cadOri,$fff,$rfc_cliente,$pathdc);
	$sel = $this->generaSelloBancos($rfc_cliente,$fff,$pathdc);
	
	
	$raiz->setAttribute('Sello',$sel);//certificado se genera
	//krmnfin
	
 	$xml->appendChild( $raiz );
	
$el_xml = $xml->saveXML();

//puse ese replace porq el ISO me crea el xml con los caracteres como debe ser
$el_xml = str_replace("ISO-8859-1","UTF-8",$el_xml);

//$xml->save('../cont/xmls/facturas/temporales/'.$datosPrv['razon_social'].'_sintimbrar.xml');

//echo $el_xml;
if($pac==1){

 	require_once('../../modulos/lib/nusoap.php');
 
	$idComprobante = time();
	$xmlreal = "<?xml version='1.0' encoding='utf-8'?>
		<EnvioCFDI idEnvio='10'><Comprobantes>
			<Comprobante idEmpresa='".$rfc_cliente."' idComprobante='".$idComprobante."'>
			<![CDATA[$el_xml]]></Comprobante></Comprobantes>
		</EnvioCFDI>";
			
	$msjFinal = "";   
		try {
	 		$client = new SoapClient($azurianUrls['recepcion'], array('local_cert' =>$p12_netwar));
			$parametros = array("msg" => utf8_encode($xmlreal));
		    $result = $client->recepcionComprobante($parametros);
		    $xml_anzurian = $result->recepcionComprobanteReturn;
		    $xml = json_decode(json_encode((array) simplexml_load_string($xml_anzurian)), 1);
		    
		    if($xml['@attributes']['codigoResultado']=='-2'){
		   		$msjFinal = 'XML mal formado';
						echo "<script> alert('".$msjFinal."');  window.location = 'index.php?c=Ingresos&f=filtro&fun=".$_REQUEST['tipoegreso']."'; </script>";
				
		    		exit();
		    }
		    if($xml['@attributes']['codigoResultado']=='-3'){
		   	 	$msjFinal =  'Error al almacenar XML';
						echo "<script> alert('".$msjFinal."');  window.location = 'index.php?c=Ingresos&f=filtro&fun=".$_REQUEST['tipoegreso']."'; </script>";
				
		    		exit();
		    }
			
		    $idRastreo = $xml['@attributes']['trackId'];
			$idDoc = 0;
		    if($_REQUEST['incluirdoc']==1){
		    		$idDoc = $_REQUEST['idincluir'];
		    }
			$idRetencion = $this->ChequesModel->pendienteTimbrar($_REQUEST['CveRetenc'], $_REQUEST['fechar'], $_REQUEST['beneficiarior'], $_REQUEST['referenciar'], $_REQUEST['pInicial'], $_REQUEST['pFinal'], $_REQUEST['ejercicior'], $_REQUEST['montoTotOperacion'], $_REQUEST['montoTotGrav'], $_REQUEST['montoTotExent'], $_REQUEST['montoTotRet'], $idRastreo,$idComprobante,$idDoc);
			if($idRetencion){
				foreach ($_REQUEST['importebase'] as $key=>$val)	{
					if($key!=0){
						$this->ChequesModel->impuestosRetencion($_REQUEST['importebase'][$key], $_REQUEST['retencionlistar'][$key], $_REQUEST['impuestoretenido'][$key], $_REQUEST['tipopagor'][$key], $idRetencion);
					}	
				}
				//$this->ChequesModel->sintimbrarnombre($datosPrv['razon_social'].'_sintimbrar.xml', $idRetencion)	;
			}
		} catch (SoapFault $exception) {
			$msjFinal =  'Por el momento el servicio esta presentando problemas externos a Netwarmonitor, Intentelo mas tarde.';
					echo "<script> alert('".$msjFinal."');  window.location = 'index.php?c=Ingresos&f=filtro&fun=".$_REQUEST['tipoegreso']."'; </script>";
			
			exit();
		}
					 
// 
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
        if($xml2['@attributes']['codigoRespuesta']=='2'){
	    		$msjFinal =  'En estos momentos no se puede acceder a los servidores del SAT favor de intentar mas tarde.\nReintenta timbrar la retencion desde el reporte Retenciones e Inf. de Pagos';
	   	 			echo "<script> alert('".$msjFinal."');  window.location = 'index.php?c=Ingresos&f=filtro&fun=".$_REQUEST['tipoegreso']."'; </script>";
	   	 	exit();
	    }


	    if($xml2['@attributes']['codigoRespuesta']=='100'){
	    //	echo "del codigo erro".$xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo'];
	    	if (array_key_exists('ComprobantesValidos', $xml2)) {//si existen comprobantes validos
			    $parsear=1;
		}else{
			if (array_key_exists('ComprobantesErroneos', $xml2)) {//si nop
			    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='3'){
		   			$msjFinal =  'El comprobante es invalido o ya ha sido usado con anterioridad'; 
					$this->ChequesModel->borrarIncorrecto($idRetencion);
		   		 	echo "<script> alert('".$msjFinal."'); window.location = 'index.php?c=Ingresos&f=filtro&fun=".$_REQUEST['tipoegreso']."'; </script>";
					
		   		 	exit();
			    }
			    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='300'){
		    			$msjFinal =  'Usuario invalido, es necesario se autentifiquen los nodos de timbrado';
		    			$this->ChequesModel->borrarIncorrecto($idRetencion);	
		    			echo "<script> alert('".$msjFinal."'); window.location = 'index.php?c=Ingresos&f=filtro&fun=".$_REQUEST['tipoegreso']."'; </script>";
						
		    			exit();
			    }
			    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='301'){
			   		$msjFinal =  'XML mal formado';
					$this->ChequesModel->borrarIncorrecto($idRetencion);
			    		echo "<script> alert('".$msjFinal."'); window.location = 'index.php?c=Ingresos&f=filtro&fun=".$_REQUEST['tipoegreso']."';  </script>";
						
			    		exit();
			    }
			    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='302'){
			   		$msjFinal =  'Sello mal formado o invalido';
					$this->ChequesModel->borrarIncorrecto($idRetencion);
			    		echo "<script> alert('".$msjFinal."');  window.location = 'index.php?c=Ingresos&f=filtro&fun=".$_REQUEST['tipoegreso']."'; </script>";
						
			    		exit();
			    }
			    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='303'){
			    		$msjFinal =  'El CSD del emisor no corresponde al RFC que viene como emisor de comprobante';
			   	 	$this->ChequesModel->borrarIncorrecto($idRetencion);
			   	 	echo "<script> alert('".$msjFinal."');  window.location = 'index.php?c=Ingresos&f=filtro&fun=".$_REQUEST['tipoegreso']."'; </script>";
					
			   	 	exit();
			    }
			    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='304'){
			   		$msjFinal =  'El CSD del emisor ha sido revocado';
					$this->ChequesModel->borrarIncorrecto($idRetencion);
			   	 	echo "<script> alert('".$msjFinal."');  window.location = 'index.php?c=Ingresos&f=filtro&fun=".$_REQUEST['tipoegreso']."'; </script>";
					
			   	 	exit();
			    }
			    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='305'){
			    		$msjFinal =  'La fecha de emision no esta dentro de la vigencia del CSD emisor';
					$this->ChequesModel->borrarIncorrecto($idRetencion);
			    		echo "<script> alert('".$msjFinal."');  window.location = 'index.php?c=Ingresos&f=filtro&fun=".$_REQUEST['tipoegreso']."'; </script>";
						
			    		exit();
			    }
			    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='306'){
			    		$msjFinal =  'La llave utilizada para sellar no corresponde al CSD';
					$this->ChequesModel->borrarIncorrecto($idRetencion);
			    		echo "<script> alert('".$msjFinal."');  window.location = 'index.php?c=Ingresos&f=filtro&fun=".$_REQUEST['tipoegreso']."'; </script>";
						
			    		exit();
			    }
			    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='307'){
			  		$msjFinal =  'Esta factura ya contiene un timbre previo';
					$this->ChequesModel->borrarIncorrecto($idRetencion);
			    		echo "<script> alert('".$msjFinal."');  window.location = 'index.php?c=Ingresos&f=filtro&fun=".$_REQUEST['tipoegreso']."'; </script>";
			    		
			    		exit();
			    }
			    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='308'){
			    		$msjFinal =  'El CSD del emisor debe ser firmado por un certificado autorizado del SAT';
					$this->ChequesModel->borrarIncorrecto($idRetencion);
			   	 	echo "<script> alert('".$msjFinal."'); window.location = 'index.php?c=Ingresos&f=filtro&fun=".$_REQUEST['tipoegreso']."';  </script>";
			   	 	
			   	 	exit();
			    }
			    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='401'){
			   		$msjFinal =  'Fecha y hora de generacion fuera de rango.'.$_REQUEST['fechar'];
					$this->ChequesModel->borrarIncorrecto($idRetencion);
			   	 	echo "<script> alert('".$msjFinal."');  window.location = 'index.php?c=Ingresos&f=filtro&fun=".$_REQUEST['tipoegreso']."'; </script>";
					
			   	 	exit();
			    }
			    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='402'){
			   		$msjFinal =  'RFC no existe en el regimen de validacion LCO';
					$this->ChequesModel->borrarIncorrecto($idRetencion);
					echo "<script> alert('".$msjFinal."');  window.location = 'index.php?c=Ingresos&f=filtro&fun=".$_REQUEST['tipoegreso']."';  </script>";
					
			   	 	exit();
			    }
			    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='403'){
			   		$msjFinal =  'La fecha de emision debe ser posterior al 01 de Enero 2011';
					$this->ChequesModel->borrarIncorrecto($idRetencion);
					echo "<script> alert('".$msjFinal."');  window.location = 'index.php?c=Ingresos&f=filtro&fun=".$_REQUEST['tipoegreso']."'; </script>";
					
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
				
			//echo "todo ok uuid=".$cad[0];

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
	        $datosTimbrado['noCertificado']=$noc;
	        $datosTimbrado['tipoComp']='F';
	        $datosTimbrado['trackId']=$xml['@attributes']['trackId'];
	        $datosTimbrado['csdComplemento']='|1.0|'.$datosTimbrado['UUID'].'|'.$datosTimbrado['FechaTimbrado'].'|'.$datosTimbrado['selloCFD'].'|'.$datosTimbrado['noCertificadoSAT'];

	        $azurian['datosTimbrado']=$datosTimbrado;
	        //$cupon = cuponInadem($azurian['Receptor']['rfc']);
	      
	        	$positionPath="../../modulos/";
	        	$xmlfile='_'.$datosPrv['razon_social'].'_'.$datosTimbrado['UUID'].'.xml';
	        	$archivo = fopen($this->path($positionPath.'cont/').'xmls/facturas/temporales/'.$xmlfile.'','w');
			if(fwrite($archivo,$cadenaParseo[0])){
				fclose($archivo);
				$msjFinal =  "La factura se ha creado exitosamente, puede verla en el Almacen Digital o en el reporte de Retenciones";
				$this->ChequesModel->almacenaTimbrado($datosTimbrado['selloSAT'], $datosTimbrado['FechaTimbrado'], $datosTimbrado['UUID'], $idRetencion,$xmlfile,$datosTimbrado['selloCFD']);
				
				echo "<script> alert('".$msjFinal."'); window.location = 'index.php?c=Ingresos&f=filtro&fun=".$_REQUEST['tipoegreso']."'; </script>";
				exit();
			};
			 
		}else{
		    	$msjFinal =  "Error durante el proceso de facturación";
			echo "<script> alert('".$msjFinal."'); window.location = 'index.php?c=Ingresos&f=filtro&fun=".$_REQUEST['tipoegreso']."'; </script>";
				
		    	exit();
	    }

	}catch (SoapFault $exception) {
		$msjFinal =  'Por el momento el servicio esta presentando problemas externos a Netwarmonitor, Intentelo mas tarde.';
		echo "<script> alert('".$msjFinal."'); window.location = 'index.php?c=Ingresos&f=filtro&fun=".$_REQUEST['tipoegreso']."'; </script>";
		
		exit();
	}
	
}else{
// pac 2 es el formas continuas
	//$archivo = fopen('../../modulos/cont/xmls/facturas/temporales/'.$el_xml.'','w');
	//echo htmlentities($el_xml);
	//file_put_contents( '../../modulos/cont/xmls/facturas/temporales/original.xml',$el_xml); 
	$XML = utf8_encode($el_xml);
	//file_put_contents( '../../modulos/cont/xmls/facturas/temporales/original2.xml',$XML); 
	
	$idDoc = 0;
    if($_REQUEST['incluirdoc']==1){
    		$idDoc = $_REQUEST['idincluir'];
    }
	$idRetencion = $this->ChequesModel->pendienteTimbrar($_REQUEST['CveRetenc'], $_REQUEST['fechar'], $_REQUEST['beneficiarior'], $_REQUEST['referenciar'], $_REQUEST['pInicial'], $_REQUEST['pFinal'], $_REQUEST['ejercicior'], $_REQUEST['montoTotOperacion'], $_REQUEST['montoTotGrav'], $_REQUEST['montoTotExent'], $_REQUEST['montoTotRet'],0,0,$idDoc);
	if($idRetencion){
		foreach ($_REQUEST['importebase'] as $key=>$val)	{
			if($key!=0){
				$this->ChequesModel->impuestosRetencion($_REQUEST['importebase'][$key], $_REQUEST['retencionlistar'][$key], $_REQUEST['impuestoretenido'][$key], $_REQUEST['tipopagor'][$key], $idRetencion);
			}	
		}
		//$this->ChequesModel->sintimbrarnombre($datosPrv['razon_social'].'_sintimbrar.xml', $idRetencion)	;
	}
 	require_once('../../modulos/wsinvoice/sealRetention.php');
/*No maneja trackid el pac 2
 */
	//print_r($arrRetention);
	
	
	
	if($arrRetention['success']==1){
	  	$positionPath="../../modulos/";
	        $xmlfile='_'.$datosPrv['razon_social'].'_'.$arrRetention['datos']['UUID'].'.xml';
	        	// $archivo = fopen($positionPath.'/cont/xmls/facturas/temporales/'.$xmlfile.'','w');
// 	        	
	        $archivo = rename($this->path('../../modulos/cont/').'xmls/facturas/temporales/'.$arrRetention['datos']['UUID'].'.xml',$this->path('../../modulos/cont/').'xmls/facturas/temporales/'.$xmlfile);
	        $this->ChequesModel->facturaRename($xmlfile);
			$msjFinal =  "La factura se ha creado exitosamente, puede verla en el Almacen Digital o en el reporte de Retenciones";
					//$this->ChequesModel->almacenaTimbrado('d', "", "sd", 1,"$xmlfile","d");
			$this->ChequesModel->almacenaTimbrado($arrRetention['datos']['selloSAT'], $arrRetention['datos']['FechaTimbrado'], $arrRetention['datos']['UUID'], $idRetencion,$xmlfile,$arrRetention['datos']['selloCFD']);
				
			echo "<script> alert('".$msjFinal."'); window.location = 'index.php?c=Ingresos&f=filtro&fun=".$_REQUEST['tipoegreso']."'; </script>";
			exit();
			
			 
		}else{
		    	$msjFinal =  "Error durante el proceso de facturación";
			$this->ChequesModel->borrarIncorrecto($idRetencion);
				
			echo "<script> alert('".$msjFinal."'); window.location = 'index.php?c=Ingresos&f=filtro&fun=".$_REQUEST['tipoegreso']."'; </script>";
				
		    	exit();
	    }
	
}
	//window.location = 'index.php?c=Ingresos&f=filtro&fun=".$_REQUEST['tipoegreso']."';		
// // 
//echo htmlentities($xmlreal);
//echo htmlentities($el_xml);
// echo "<br>".$cadOri;
 }

 /* se crea copia de la funcion ya que el archivo original que lo contiene dispara 
  * la facturacion y el xml es diferente al de retenciones
  * por esto se separa para armar el xml del lado de bancos */
 function generaCertificadoDesdeBancos($rfc_cliente,$cer_cliente,$pathdc){
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
function generaNoCertificadoBancos($rfc_cliente,$cer_cliente,$pathdc){
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
function generaSelloBancos($rfc_cliente,$dteTrailer,$pathdc){
		$pem = $pathdc.'/'.$rfc_cliente.'.pem';
		$comando="openssl dgst -sha1 -sign ".$pem." '".$pathdc."/CO" .$dteTrailer. ".txt' | openssl enc -base64 -A -out ".$pathdc."/sello" .$dteTrailer. ".txt"; 
	  	exec($comando);
// 
		$sello_open = fopen($pathdc.'/sello' . $dteTrailer . '.txt', "r");
		$sello = fread($sello_open, filesize($pathdc.'/sello' . $dteTrailer . '.txt'));
		fclose($sello_open);

	  	$sello=trim($sello);

	  	unlink($pathdc.'/sello' . $dteTrailer . '.txt');
	  	unlink($pathdc.'/CO' . $dteTrailer .'.txt');

	  	return $sello;
	}
function generaPemBancos($rfc_cliente,$key_cliente,$pwd_cliente,$pathdc){
		$pem = $pathdc.'/'.$rfc_cliente.'.pem';
		$comando='openssl pkcs8 -inform DER -in '.$key_cliente.' -passin pass:'.$pwd_cliente.' -out '.$pem;
   	 	exec($comando);

    	$validacion = $this->validacionBancos('pem',$pem);
    	return $validacion; 
	}
 function validacionBancos($clave,$var){
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
 function generaCadenaOriginalBancos($cad,$dteTrailer,$rfc_cliente,$pathdc){
		$archivo = fopen($pathdc.'/CO' . $dteTrailer . '.txt','w');
		fwrite($archivo,$cad);
		fclose($archivo);
		return 1;
	
	}

function verRetencionPendiente(){
	require('views/retenciones/reporteretenciones.php');
}
function verReporte(){
	$retenciones = $this->ChequesModel->listadoFacturas($_REQUEST['fechainicio'],$_REQUEST['fechafin']);
	if(!$retenciones->num_rows>0){
		$retenciones=0;
	}
	require('views/retenciones/reporteretenciones.php');
}
function volverAtimbrar(){
 	require_once('../SAT/config.php');

	require_once('../../modulos/lib/nusoap.php');
	
	try {
        $parametros = array("msg" => '<?xml version="1.0" encoding="utf-8"?><ConsultaEnvioCFDI trackId="'.$_REQUEST['trackID'].'"></ConsultaEnvioCFDI>');
        $parsear=0;

        $intXX=0;
        do{
                $intXX++;
                $client = new SoapClient($azurianUrls['envio'], array('local_cert' =>$p12_netwar));
                $result2 = $client->consultarEnvio($parametros);
                $xml_anzurian2 = $result2->consultarEnvioReturn;
				//print_r($xml_anzurian2);
                $xml2 = json_decode(json_encode((array) simplexml_load_string($xml_anzurian2)), 1);
                sleep(1);
        }while ($xml2['@attributes']['codigoRespuesta']=='2' && $intXX < 61);
		//esta pendiente de procesamiento esto marca despues de aqui y entra en el error 2
        if($xml2['@attributes']['codigoRespuesta']=='2'){
	    		echo  'En estos momentos no se puede acceder a los servidores del SAT favor de intentar mas tarde.';
	   	 	exit();
	    }

	    if($xml2['@attributes']['codigoRespuesta']=='100'){
	    //	echo "del codigo erro".$xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo'];
	    	if (array_key_exists('ComprobantesValidos', $xml2)) {//si existen comprobantes validos
			    $parsear=1;
		}else{
			
			if (array_key_exists('ComprobantesErroneos', $xml2)) {//si nop
			    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='3'){
		   			echo 'El comprobante es invalido o ya ha sido usado con anterioridad'; 
					//$this->ChequesModel->borrarIncorrecto($idRetencion);
		   		 	exit();
			    }
			    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='300'){
		    			echo 'Usuario invalido, es necesario se autentifiquen los nodos de timbrado';
		    			exit();
			    }
			    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='301'){
			   		echo  'XML mal formado';
			    		exit();
			    }
			    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='302'){
			   		echo  'Sello mal formado o invalido';
			    		exit();
			    }
			    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='303'){
			    		echo 'El CSD del emisor no corresponde al RFC que viene como emisor de comprobante';
			   	 	exit();
			    }
			    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='304'){
			   		echo 'El CSD del emisor ha sido revocado';
			   	 	exit();
			    }
			    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='305'){
			    		echo 'La fecha de emision no esta dentro de la vigencia del CSD emisor';
			    		exit();
			    }
			    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='306'){
			    		echo  'La llave utilizada para sellar no corresponde al CSD';
			    		exit();
			    }
			    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='307'){
			  		echo 'Esta factura ya contiene un timbre previo';
			    		exit();
			    }
			    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='308'){
			    		echo 'El CSD del emisor debe ser firmado por un certificado autorizado del SAT';
			   	 	exit();
			    }
			    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='401'){
			   		echo 'Fecha y hora de generacion fuera de rango.';
			   	 	exit();
			    }
			    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='402'){
			   		echo 'RFC no existe en el regimen de validacion LCO';
			   	 	exit();
			    }
			    if($xml2['ComprobantesErroneos']['ComprobanteErroneo']['Errores']['Error']['@attributes']['codigo']=='403'){
			   		echo 'La fecha de emision debe ser posterior al 01 de Enero 2011';
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
				
			//echo "todo ok uuid=".$cad[0];

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
	        $datosTimbrado['noCertificado']=$noc;
	        $datosTimbrado['tipoComp']='F';
	        $datosTimbrado['trackId']=$xml['@attributes']['trackId'];
	        $datosTimbrado['csdComplemento']='|1.0|'.$datosTimbrado['UUID'].'|'.$datosTimbrado['FechaTimbrado'].'|'.$datosTimbrado['selloCFD'].'|'.$datosTimbrado['noCertificadoSAT'];

	        $azurian['datosTimbrado']=$datosTimbrado;
	        //$cupon = cuponInadem($azurian['Receptor']['rfc']);
	      
	        	$positionPath="../../modulos/";
			$datosPrv = $this->ChequesModel->datosproveedor($_REQUEST['idPrv']);
			
	        	$xmlfile='_'.$datosPrv['razon_social'].'_'.$datosTimbrado['UUID'].'.xml';
	        	$archivo = fopen($this->path($positionPath.'cont/').'xmls/facturas/temporales/'.$xmlfile.'','w');
			if(fwrite($archivo,$cadenaParseo[0])){
				fclose($archivo);
				$this->ChequesModel->almacenaTimbrado($datosTimbrado['selloSAT'], $datosTimbrado['FechaTimbrado'], $datosTimbrado['UUID'], $_REQUEST['idretencion'],$xmlfile,$datosTimbrado['selloCFD']);
				echo "La factura se ha creado exitosamente, puede verla en el Almacen Digital";
				exit();
			};
			 
		}else{
		    	 echo "Error durante el proceso de facturación";
			 exit();
	    }

	}catch (SoapFault $exception) {
		echo 'Por el momento el servicio esta presentando problemas externos a Netwarmonitor, Intentelo mas tarde.';
		exit();
	}
}

function cancelaFactura(){
	require_once('../SAT/config.php');
	
	$cancelFolio = $_REQUEST['uuid'];
	$cancelID = $_REQUEST['idcomprobante'];
	$datosemisor = $this->ChequesModel->infoFactura();
	 if($datosemisor->num_rows>0){
	 	if($r = $datosemisor->fetch_object()){
	 		 $rfccancel = $r->rfc;
			 $elcer = $pathdc . '/' . $r->cer;
             $elkey = $pathdc . '/' . $r->llave;
             $clave = $r->clave;
			 $razonsocial = $r->razon_social;
			 $pac=$r->pac;
	 	}
	 }
	 // $rfccancel = 'IHA000314A38';
	 // $elcer = $pathdc . '/00001000000307601732.cer';
     // $elkey = $pathdc . '/CSD_MATRIZ_IHA000314A38_20150716_115157.key';
     // $clave = 'H4G4G2015';
       date_default_timezone_set("Mexico/General");

    if($pac==1){   
       require_once('../../modulos/lib/nusoap.php');
	//date('Y-m-d\TH:i:sP')
		$fecha=date('Y-m-d').'T'.date('H:i:s',strtotime("-7 minute"));
		try {//verfica si esta cancelada
			$client2 = new SoapClient($azurianUrls['concultacomp'], array('local_cert' =>$p12_netwar));
		    		$parametros = array("msg" => '<?xml version="1.0" encoding="utf-8"?><ConsultaEstadoComprobanteCFDI rfcEmpresa="'.$rfccancel.'" UUID="'.$cancelFolio.'"></ConsultaEstadoComprobanteCFDI>');
					    $result = $client2->consultarEstadoComprobante($parametros);
					    $xmlresponse =  $result->consultarEstadoComprobanteReturn;
					   	$result2=explode('codigo="', $xmlresponse);
						$result2=explode('"', $result2[1]);
						$result2=$result2[0];

			if($result2==201 || $result2==202){
				$this->ChequesModel->cancelaRetencion($cancelFolio);
				echo 'Se ha cancelado la Retencion.';
				exit();
			}


		      $client = new SoapClient($azurianUrls['cancelacion'], array('local_cert' =>$p12_netwar));
		    	$d='<Cancelacion xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns="http://www.sat.gob.mx/esquemas/retencionpago/1" Fecha="'.$fecha.'" RfcEmisor="'.$rfccancel.'" xmlns="http://cancelaretencion.sat.gob.mx"><Folios><UUID>'.$cancelFolio.'</UUID></Folios></Cancelacion>';

			    $dom = new DOMDocument(); 
			    $yourXML = $d;
			    $dom->loadXML($yourXML);
			    $canonicalized = $dom->C14N();
			    $digest = base64_encode(pack("H*", sha1($canonicalized))); 


			    $nx='<SignedInfo xmlns="http://www.w3.org/2000/09/xmldsig#" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"></CanonicalizationMethod><SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"></SignatureMethod><Reference URI=""><Transforms><Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"></Transform></Transforms><DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"></DigestMethod><DigestValue>'.$digest.'</DigestValue></Reference></SignedInfo>';
			    $fff = date('YmdHis').rand(100,999);

			  
			   	$pem = $this->generaPemBancos($rfccancel,$elkey,$clave,$pathdc);
			    $ori = $this->generaCadenaOriginalBancos($nx,$fff,$rfccancel,$pathdc);
				$sel = $this->generaSelloBancos($rfccancel,$fff,$pathdc);
				$cer = $this->generaCertificadoDesdeBancos($rfccancel,$elcer,$pathdc);
				
				
 			$parametros = array("msg" => '<CancelarComprobanteCFDI id="'.$cancelID.'"><Mensaje><![CDATA[<?xml version="1.0" encoding="utf-8"?><s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/"><s:Body xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><CancelaCFD xmlns="http://cancelaretencion.sat.gob.mx"><Cancelacion RfcEmisor="'.$rfccancel.'" Fecha="'.$fecha.'"><Folios><UUID>'.$cancelFolio.'</UUID></Folios><Signature xmlns="http://www.w3.org/2000/09/xmldsig#"><SignedInfo><CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/><SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/><Reference URI=""><Transforms><Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"/></Transforms><DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/><DigestValue>'.$digest.'</DigestValue></Reference></SignedInfo><SignatureValue>'.$sel.'</SignatureValue><KeyInfo>
 			<X509Data><X509Certificate>'.$cer.'</X509Certificate></X509Data></KeyInfo></Signature></Cancelacion></CancelaCFD></s:Body></s:Envelope>]]></Mensaje></CancelarComprobanteCFDI>');
		    $result = $client->cancelarComprobante($parametros);
		    $xmlresponse =  $result->cancelarComprobanteReturn;
		    $parametrosEnvio = $parametros;
		   // var_dump($parametros);

		   // var_dump($result);
			$result2=explode('result="', $xmlresponse);
			$result2=explode('">', $result2[1]);
			$result2=$result2[0];
				sleep(50);
		    		$client2 = new SoapClient($azurianUrls['concultacomp'], array('local_cert' =>$p12_netwar));
		    		$parametros = array("msg" => '<?xml version="1.0" encoding="utf-8"?><ConsultaEstadoComprobanteCFDI rfcEmpresa="'.$rfccancel.'" UUID="'.$cancelFolio.'"></ConsultaEstadoComprobanteCFDI>');
					    $result = $client2->consultarEstadoComprobante($parametros);
					    $xmlresponse =  $result->consultarEstadoComprobanteReturn;
					   var_dump($result);
					   
					   	$result2=explode('codigo="', $xmlresponse);
						$result2=explode('"', $result2[1]);
						$result2=$result2[0];

						
					   	$resultMsj=explode('descripcion="', $xmlresponse);
						$resultMsj=explode('"', $resultMsj[1]);
						$resultMsj=$resultMsj[0];

						

			if($result2==201 || $result2==202){
				$this->ChequesModel->cancelaRetencion($cancelFolio);
				echo 'Se ha cancelado la Retencion.';
				exit();
			}else{
				echo 'Se envio la factura a cancelar al SAT, favor de revisar mas tarde.',
				exit();
			}
		}catch(SoapFault $exception){
			echo 'Por el momento el servicio esta presentando problemas externos a Netwarmonitor, Intentelo mas tarde.'; 
			exit();
		}
//PAC 2
	}else{
		$strUUID = $cancelFolio;
		require_once('../../modulos/wsinvoice/cancelRetention.php');
		
	}
	
}

/* 	F I N 	C O M P L E M E N T O   D E   P A G O (RETENCION) FACTURA	*/ 
 
/* cxc y cxp */
function cxccxcp()
	{
		$listaPagos = $this->ChequesModel->pagosCobrosSinAsignar($_POST['id'],$_REQUEST['tipo'],$_REQUEST['moneda']);
		$datos = '';
		if($_REQUEST['cambio']<1){
			$_REQUEST['cambio']=1;
		}
		while($p = $listaPagos->fetch_object())
		{
			
			$datos .= "<tr><td><input  type='checkbox' name='cxc[]' class='listacheckcxc' data-value=".$p->id." value='".number_format($p->saldo/$_REQUEST['cambio'],2,'.','')."/".$p->id."' onclick='calculocxc()' /> </td>
			<td>$p->fecha_pago</td><td>$p->concepto</td><td>".number_format($p->abono,2,'.',',')."</td><td>".number_format($p->saldo/$_REQUEST['cambio'],2,'.','')."</td>
			<td><input type='text' onkeypress='parcial()' id='".$p->id."' value=".number_format($p->saldo/$_REQUEST['cambio'],2,'.',',').">
			</tr>";
			
		}	
		echo $datos;
	}
function eliminaPagoApp(){
	echo $this->ChequesModel->eliminaPagoApp($_REQUEST['idpago'], $_REQUEST['idpagorelacion']);
}
/* si cambia el beneficiario
 * va eliminar los pagos que se ayan hecho con el anterior
 * se hace desde el onchange para no meterlo en parte de actualizar 
 * (que era como lo tenia)
 * esto para evitar que se cambie el beneficiario
 * y agrege nuevos pagos conservando
 * el saldo de los pagos del antiguo beneficiario
 * NOTA .- se cambio agregando desde actualizar de nuevo
 * para evitar que el user se equivoque
 * y cambie el bene y cuando no teia que hacerlo
 * asi solo hasta el momento de actualizar garantiza que esta 
 * seguro y que no es error
 * si se cambia la logica usar esta funcion y descomentar lo de validacuenta de ingresos.js
 */ 
function cambiaBeneficiario(){
	/* se queda solo asi en caso de que
	 * appministra maneje cxc o cxp de empleados 
	 * si debe verificar el beneficiario(tipo)
	 * 
	 * se cambio para borrar todos los pagos previos sin importar el beneficiario*/
	echo $this->ChequesModel->eliminaDocumentoCXCCXCP($_REQUEST['idDoc']);
}
function devolucionescxp(){
	if($_REQUEST['opc']==1){//1 reactivacion
		$this->ChequesModel->pagoxReactivacion($_REQUEST['idDoc']);
	}else{// 0 cargo por devolucion para revertir
		$this->ChequesModel->cargoxDevolucion($_REQUEST['idDoc'],$_REQUEST['fecha']);
	}
	
}/* FIN cxc y cxp */
}
?>