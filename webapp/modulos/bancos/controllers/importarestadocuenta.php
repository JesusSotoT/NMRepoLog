<?php
 require('common.php');

//Carga el modelo para este controlador
require("models/importarestadocuenta.php");

class importarEstadoCuenta extends Common
{
	public $ImportaModel;
	
	function __construct()
	{
		
		$this->ImportaModel = new ImportaModel();
		$this->ImportaModel->connect();
	}

	function __destruct()
	{

		$this->ImportaModel->close();
	}
	
	function verImport(){
		$cuentasB = $this->ImportaModel->cuentasBancarias();
		$periodos = $this->ImportaModel->periodos();
		$ejercicio = $this->ImportaModel->ejercicio();
		$acontia = $this->ImportaModel->validaAcontia();
		$bancos = $this->ImportaModel->validaBancos();
		if( ($bancos==1 && $acontia==1) || ($bancos==1 && $acontia==0) ){///tiene los dos o solo bancos
		
			if(isset($_SESSION['bancos'])){
				$ejer = $this->ImportaModel->nombreEjer($_SESSION['bancos']['idejercicio']);
				$fecha = $ejer."-".$_SESSION['bancos']['periodo']."-31";
				$pendiente 	= $this->ImportaModel->pendientesConciliar($_SESSION['bancos']['idbancaria'], $_SESSION['bancos']['periodo'] , $_SESSION['bancos']['idejercicio']);
				$pendiente2 = $this->ImportaModel->pendientesConciliar($_SESSION['bancos']['idbancaria'], $_SESSION['bancos']['periodo'] , $_SESSION['bancos']['idejercicio']);;
				$pendiente3 = $this->ImportaModel->pendientesConciliar($_SESSION['bancos']['idbancaria'], $_SESSION['bancos']['periodo'] , $_SESSION['bancos']['idejercicio']);;
				$egresosP 	= $this->ImportaModel->documentosPendientes($_SESSION['bancos']['idbancaria'], $fecha, 5);
				$egresosP2 	= $this->ImportaModel->documentosPendientes($_SESSION['bancos']['idbancaria'], $fecha, 5);
				$ingresosP 	= $this->ImportaModel->documentosPendientes($_SESSION['bancos']['idbancaria'], $fecha, 2);
				$ingresosP2 = $this->ImportaModel->documentosPendientes($_SESSION['bancos']['idbancaria'], $fecha, 2);
				$depositosP = $this->ImportaModel->documentosPendientes($_SESSION['bancos']['idbancaria'], $fecha, 4);
				$depositosP2	= $this->ImportaModel->documentosPendientes($_SESSION['bancos']['idbancaria'], $fecha, 4);
				$chequesP 	= $this->ImportaModel->documentosPendientes($_SESSION['bancos']['idbancaria'], $fecha, 1);
				$chequesP2 	= $this->ImportaModel->documentosPendientes($_SESSION['bancos']['idbancaria'], $fecha, 1);
				$listaconciliados = $this->ImportaModel->listaConciliadosLista($_SESSION['bancos']['periodo'] , $_SESSION['bancos']['idejercicio'],$_SESSION['bancos']['idbancaria']);
			}
			
			require('views/importador/estadobancosacontia.php');
			
		}elseif($bancos==0 && $acontia==1){//si es solo acontia
			require('views/importador/importaestadocuenta.php');
		} 
		
	}
	function almacenaEstado(){
		$idbancaria = $_REQUEST['cuentabancaria'];
		$periodo = $_REQUEST['periodo'];
		$ejercicio = $_REQUEST['ejercicio'];
		$cadena="";
		
		require_once 'importar/Excel/reader.php';
		if (isset($_FILES['archivo'])) { 
			if (move_uploaded_file($_FILES['archivo']['tmp_name'], "importar/estado.xls" )) {
				
				$data = new Spreadsheet_Excel_Reader();
				$data->setOutputEncoding('CP1251');
				$data->read("importar/estado.xls");
				$cadena="";
				$cont = 0; $dato = array(); //$data->sheets hoja
				$dato[1] = trim($data->sheets[0]["cells"][1][6]);
					$dato[2] = trim($data->sheets[0]["cells"][3][1]);//A fecha
					$dato[3] = trim($data->sheets[0]["cells"][3][2]);//B referencia
					$dato[4] = trim($data->sheets[0]["cells"][3][3]);//C concepto
					$dato[5] = trim($data->sheets[0]["cells"][3][4]);//D deposito
					$dato[6] = trim($data->sheets[0]["cells"][3][5]);//E retiro
					$dato[7] = trim($data->sheets[0]["cells"][3][6]);//F saldo
	    
			 		$regdate = substr($dato[2],0,4) . "-" . substr($dato[2],4,2) . "-" . substr($dato[2],6,2);
					if($dato[5]=='') {$dato[5]='0';} if($dato[6]==''){ $dato[6]='0';}

					$cadena=$cadena.$periodo.",".$ejercicio.",'".$regdate."',".$dato[1].",".$dato[5].",".$dato[6].",".$dato[7].",".$idbancaria.",'".$dato[3]."','".$dato[4]."'),(";
					
				
				for ($i = 4; $i <= $data->sheets[0]['numRows']; $i++) {
					if(isset($data->sheets[0]["cells"][$i][6])){
						$dato[2] = trim($data->sheets[0]["cells"][$i][1]);//fecha
						$dato[3] = trim($data->sheets[0]["cells"][$i][2]);//referencia
						$dato[4] = trim($data->sheets[0]["cells"][$i][3]);//concepto
						$dato[5] = trim($data->sheets[0]["cells"][$i][4]);//deposito
						$dato[6] = trim($data->sheets[0]["cells"][$i][5]);//retiro
						$dato[7] = trim($data->sheets[0]["cells"][$i][6]);//saldo
		    				$regdate = substr($dato[2],0,4) . "-" . substr($dato[2],4,2) . "-" . substr($dato[2],6,2);
						if($dato[5]==''){ $dato[5]='0';} if($dato[6]==''){ $dato[6]='0';} 
	
						$cadena = $cadena.$periodo.",".$ejercicio.",'".$regdate."',".trim($data->sheets[0]["cells"][$i-1][6]).",".$dato[5].",".$dato[6].",".$dato[7].",".$idbancaria.",'".$dato[3]."','".$dato[4]."'),(";
					}
					
			  }
				$sql = substr($cadena, 0, -2);
				$insert = $this->ImportaModel->importinsert($sql);
				if($insert==1){
					unlink("importar/estado.xls");
					echo "<script>alert('Datos Importados');window.location='index.php?c=importarEstadoCuenta&f=verImport';</script>";
				}else{
					echo "<script>alert('Error en la insercion');</script>";
				}
		} else {
			 echo "<script>alert('No se subio el Estado de cuentas'); window.location='index.php?c=importarEstadoCuenta&f=verImport'; </script>";
		}

		}
		
		
	}
function almacenaEstadoBancos(){
	$idbancaria = $_REQUEST['cuentabancaria'];
		$periodo = $_REQUEST['periodo'];
		$ejercicio = $_REQUEST['ejercicio'];
		$cadena="";
		unset($_SESSION['bancos']);
		require_once 'importar/Excel/reader.php';
		if (isset($_FILES['archivo'])) { 
			if (move_uploaded_file($_FILES['archivo']['tmp_name'], "importar/estado.xls" )) {
				
				$data = new Spreadsheet_Excel_Reader();
				$data->setOutputEncoding('CP1251');
				$data->read("importar/estado.xls");
				$cadena="";
				$cont = 0; $dato = array(); 
				
				/* [3][1] ===>> [3]=> fila, [1]=> columna */
				
				/* UN DEPOSITO ES UN ABONO UN RETIRO ES UN CARGO
				 * un abono es un ingreso 
				 * un cargo es un egreso
				 */
				$dato[1] = trim($data->sheets[0]["cells"][1][6]);//saldo inicial
					$dato[2] = trim($data->sheets[0]["cells"][3][1]);//A fecha
					$dato[3] = trim($data->sheets[0]["cells"][3][2]);//B referencia
					$dato[4] = trim($data->sheets[0]["cells"][3][3]);//C concepto
					$dato[5] = trim($data->sheets[0]["cells"][3][4]);//D deposito
					$dato[6] = trim($data->sheets[0]["cells"][3][5]);//E retiro
					$dato[7] = trim($data->sheets[0]["cells"][3][6]);//F saldo
					
	    				$saldoInicial = $dato[1];
	    				$saldoFinal = $dato[7];
			 		$regdate = substr($dato[2],0,4) . "-" . substr($dato[2],4,2) . "-" . substr($dato[2],6,2);
					$saldoInicialCuenta = $this->ImportaModel->saldoInicial($idbancaria,$periodo,$ejercicio );
					
				/* VERIFICA SALDO ESTADO CUENTA CON CUENTA BANCARIO LIBROS */	
					if($saldoInicialCuenta!=$dato[1]){
						echo "<script> alert('Los Saldo Iniciales no son iguales!\\n(Saldo contable:".$saldoInicialCuenta.", Saldo Bancario:".$dato[1].") \\nVerifique su cuenta.'); window.location='index.php?c=importarEstadoCuenta&f=verImport'; </script>";
					}else{
						if( $dato[4] == "SIN MOVIMIENTOS" ){
						echo "	<script>
								
								if(confirm('‎¿La cuenta no tiene movimientos desea finalizar la conciliacion? ')){
									".$this->finalizaSinMov($idbancaria,$periodo,$ejercicio,$dato[7],$regdate)."
								}else{
									window.location='index.php?c=importarEstadoCuenta&f=verImport';
								}
								
								</script>";
						}
					else{
										
						if($dato[5]=='') {$dato[5]='0';} if($dato[6]==''){ $dato[6]='0';}
	
						$ids = "0,";
						$campoiDocumentos = "";$conciliadoBancos = 0;
						if($dato[5]>0){//es deposito(abono) ingresos
							$ingresos = $this->ImportaModel->ingresosEgresosB($idbancaria,$dato[4], $regdate, $dato[5],2,$dato[3]);//el 2 para q sea doc de ingreso
							if($ingresos==0){
								$deposito =	$this->ImportaModel->depositosB($idbancaria,$dato[4], $regdate, $dato[5],$dato[3]);
								if($deposito>0){
									$ids.=$deposito.",";
									$campoiDocumentos = ",".$deposito;
									//$this->ImportaModel->conciliaDocumentosAutomatico($deposito);
								}
							}else{
								$ids.=$ingresos.",";
								$campoiDocumentos = ",".$ingresos;
								//$this->ImportaModel->conciliaDocumentosAutomatico($ingresos);
							}
						}
						/* es un retiro un cargo Egreso
						 * busca en egreso si lo encuentra guarda el id del doc
						 * sino no lo encuentra busca en los cheques 
						 */
							elseif($dato[6]>0){ 
								$egreso = $this->ImportaModel->ingresosEgresosB($idbancaria,$dato[4], $regdate, $dato[6],5,$dato[3]);//el 5 para q sea doc de egreso
								if($egreso>0){
									$ids.=$egreso.",";
									$campoiDocumentos = ",".$egreso;
									//$this->ImportaModel->conciliaDocumentosAutomatico($egreso);
								}else{
									$cheque = $this->ImportaModel->chequesB($idbancaria,$dato[4], $regdate, $dato[6],$dato[3]);
									if($cheque>0){
										$ids.=$cheque.",";
										$campoiDocumentos = ",".$cheque;
										//$this->ImportaModel->conciliaDocumentosAutomatico($cheque);
									}
								}
							}
					
					if($campoiDocumentos!=''){
						$conciliadoBancos = 1;
					}
						
					$cadena=$cadena.$periodo.",".$ejercicio.",'".$regdate."',".$dato[1].",".$dato[5].",".$dato[6].",".$dato[7].",".$idbancaria.",'".$dato[3]."','".utf8_encode($dato[4])."','".$campoiDocumentos."',$conciliadoBancos),(";
					
					for ($i = 4; $i <= $data->sheets[0]['numRows']; $i++) {
						if(isset($data->sheets[0]["cells"][$i][6])){
							$dato[2] = trim($data->sheets[0]["cells"][$i][1]);//fecha
							$dato[3] = trim($data->sheets[0]["cells"][$i][2]);//referencia
							$dato[4] = trim($data->sheets[0]["cells"][$i][3]);//concepto
							$dato[5] = trim($data->sheets[0]["cells"][$i][4]);//deposito
							$dato[6] = trim($data->sheets[0]["cells"][$i][5]);//retiro
							$dato[7] = trim($data->sheets[0]["cells"][$i][6]);//saldo
							
			    				$saldoFinal = $dato[7];
								
					 		$regdate = substr($dato[2],0,4) . "-" . substr($dato[2],4,2) . "-" . substr($dato[2],6,2);
							if($dato[5]==''){ $dato[5]='0';} if($dato[6]==''){ $dato[6]='0';} 
		
							$campoiDocumentos = "";$conciliadoBancos=0;
							if($dato[5]>0){//es deposito(abono) ingresos
								$ingresos = $this->ImportaModel->ingresosEgresosB($idbancaria,$dato[4], $regdate, $dato[5],2,$dato[3]);//el 2 para q sea doc de ingreso
								if($ingresos==0){
									$deposito =	$this->ImportaModel->depositosB($idbancaria,$dato[4], $regdate, $dato[5],$dato[3]);
									if($deposito>0){
										$ids.=$deposito.",";
										//$this->ImportaModel->conciliaDocumentosAutomatico($deposito);
										$campoiDocumentos = ",".$deposito;
									}
								}else{
									$ids.=$ingresos.",";
									$campoiDocumentos = ",".$ingresos;
									//$this->ImportaModel->conciliaDocumentosAutomatico($ingresos);
									
								}
							}
							/* es un retiro un cargo Egreso
							 * busca en egreso si lo encuentra guarda el id del doc
							 * sino no lo encuentra busca en los cheques 
							 */
							elseif($dato[6]>0){
								$egreso = $this->ImportaModel->ingresosEgresosB($idbancaria,$dato[4], $regdate, $dato[6],5,$dato[3]);//el 5 para q sea doc de egreso
								if($egreso>0){
									$ids.= $egreso.",";
									$campoiDocumentos = ",".$egreso;
									//$this->ImportaModel->conciliaDocumentosAutomatico($egreso);
								}else{
									$cheque = $this->ImportaModel->chequesB($idbancaria,$dato[4], $regdate, $dato[6],$dato[3]);
									if($cheque>0){
										$ids.= $cheque.",";
										$campoiDocumentos = ",".$cheque;
										//$this->ImportaModel->conciliaDocumentosAutomatico($cheque);
									}
								}
							}
						if($campoiDocumentos!=''){
							$conciliadoBancos = 1;
						}
						
						$cadena = $cadena.$periodo.",".$ejercicio.",'".$regdate."',".trim($data->sheets[0]["cells"][$i-1][6]).",".$dato[5].",".$dato[6].",".$dato[7].",".$idbancaria.",'".$dato[3]."','".utf8_encode($dato[4])."','".$campoiDocumentos."',$conciliadoBancos),(";
							
						}
						
					  }
					$sql = substr($cadena, 0, -2);
					$idDocs =  substr($ids, 0, -1);
					$insert = $this->ImportaModel->importinsertBancos($sql,$idDocs);
					if($insert === 1){
							unlink("importar/estado.xls");
							
							$faltantes 		= $this->ImportaModel->pendientesConciliar($idbancaria, $periodo , $ejercicio);
							$pendientedoc  	= $this->ImportaModel->DocumenpendientesConciliar($idbancaria, $periodo, $ejercicio);
							
							if($faltantes!=0 || ($pendientedoc->num_rows>0)){
								
								$_SESSION['bancos']['periodo'] 		= $_REQUEST['periodo'];
								$_SESSION['bancos']['idejercicio'] 	= $_REQUEST['ejercicio'];
								$_SESSION['bancos']['idbancaria']	= $idbancaria;
								
							echo "<script>alert('Datos Importados');window.location='index.php?c=importarEstadoCuenta&f=verImport';</script>";
							}else{
								$fin = $this->ImportaModel->finalizaConciliacion($idbancaria, $periodo, $ejercicio, $saldoInicial, $saldoFinal);
								if($fin){
									echo "<script>
									alert('Todos los movimimientos fueron Conciliados! :) ');
									
									if(confirm('Desea conciliar Contabilidad(Acontia)?')){
										".$this->ConciliaAcontiaControl($idbancaria, $periodo, $ejercicio)."
									}else{
										window.location='index.php?c=importarEstadoCuenta&f=verImport';
									}
									
									</script>";
								}else{
									echo "<script>alert('Todos los movimimientos se conciliaron!\\nPero no se pudo finalizar la conciliacion\\nImporte de nuevo');window.location='index.php?c=importarEstadoCuenta&f=verImport';</script>";
								}
							}
							
					}else{
						echo "<script>alert('Error en la insercion');</script>";
					}
				}//else de sin mov	
			}//primer else


			} else {
				echo "<script>alert('No se subio el Estado de cuentas'); window.location='index.php?c=importarEstadoCuenta&f=verImport'; </script>";
			}

		}
}


function finaliza(){
		$saldoFinal		= $this->ImportaModel->saldos(0, $_REQUEST['idbancaria'], $_REQUEST['periodo'], $_REQUEST['ejercicio']);
		
	
	$saldoInicial	= $this->ImportaModel->saldos(1, $_REQUEST['idbancaria'], $_REQUEST['periodo'], $_REQUEST['ejercicio']);
	
	$fin = $this->ImportaModel->finalizaConciliacion($_REQUEST['idbancaria'], $_REQUEST['periodo'], $_REQUEST['ejercicio'], $saldoInicial, $saldoFinal);
	if($fin==1){
		unset($_SESSION['bancos']);
	}
	echo $fin;
}
function ConciliaAcontia(){
	$conciliacion = $this->ImportaModel->conciliaAcontiaAuto($_REQUEST['idbancaria'],$_REQUEST['periodo'],$_REQUEST['ejercicio']);
	while($c = $conciliacion->fetch_object()){
		$this->ImportaModel->updateMovPolizas($c->idDocumento, $c->idmov);	
	}
	echo $this->ImportaModel->pendientesConciliarAcont($_REQUEST['idbancaria'],$_REQUEST['periodo'],$_REQUEST['ejercicio']);
		
}
function finalizaSinMov($idbancaria,$periodo,$ejercicio,$saldo,$regdate){
	
	$cadena = $periodo.",".$ejercicio.",'".$regdate."',".$saldo.",0,0,".$saldo.",".$idbancaria.",'-','SIN MOVIMIENTOS',1,1)";
	
	$request = $this->ImportaModel->finalizaSinMovimientosenelMes($periodo,$saldo,$idbancaria,$ejercicio,$cadena);
	if( $insert == 1){
		unset($_SESSION['bancos']);
	}
	return '
		if('.$request.'===1){
			alert("Conciliacion Finalizada, puede consultar su reporte :)");
			window.location="index.php?c=importarEstadoCuenta&f=verImport";
			
		}else if('.$request.'===0){
			alert("Error al Finalizar intente de nuevo.");
			window.location="index.php?c=importarEstadoCuenta&f=verImport";
			
		}
		
	';
		
}
function ConciliaAcontiaControl($idbancaria,$periodo,$ejercicio){
	$conciliacion = $this->ImportaModel->conciliaAcontiaAuto($idbancaria,$periodo,$ejercicio);
	while($c = $conciliacion->fetch_object()){
		$this->ImportaModel->updateMovPolizas($c->idDocumento, $c->idmov);	
	}
	$request =  $this->ImportaModel->pendientesConciliarAcont($idbancaria,$periodo,$ejercicio);
	return '
		if('.$request.'===1){
			alert("No se pudieron conciliar todos los movimientos,\n Deberá realizar conciliación de Acontia manual.");
			window.location="index.php?c=importarEstadoCuenta&f=verImport";
			
		}else if('.$request.'===2){
			alert("Conciliacion Acontia Finalizada!");
			window.location="index.php?c=importarEstadoCuenta&f=verImport";
			
		}else if('.$request.'===0){
			alert("Error al finalizar. Finalice manualmente en Realizar conciliación de Acontia .");
			window.location="index.php?c=importarEstadoCuenta&f=verImport";
			
		}
		
	';
		
}
function validaEstado(){
	$valida = $this->ImportaModel->validaEstado($_REQUEST['periodo'], $_REQUEST['ejercicio'], $_REQUEST['cuentabancaria']);
	if($valida->num_rows>0){
		$finconciiado = $this->ImportaModel->verifaFinConciliacion($_REQUEST['cuentabancaria'], $_REQUEST['periodo'], $_REQUEST['ejercicio']);
		if($finconciiado->num_rows>0){
			echo 2;//si ya esta conciliado osea en la tabla de saldos conciliacion ya no podra reemplazar
		}else{
			echo 1;//si trae movimientos pero aun no finaliza la conciliacion
		}
	}else{
		echo 0;
	}
}
/*
 */
function validaEstadoBancos(){
	$valida = $this->ImportaModel->validaEstado($_REQUEST['periodo'], $_REQUEST['ejercicio'], $_REQUEST['cuentabancaria']);
	if($valida->num_rows>0){
		$finconciiado = $this->ImportaModel->verifaFinConciliacionBancos($_REQUEST['cuentabancaria'], $_REQUEST['periodo'], $_REQUEST['ejercicio']);
		if($finconciiado->num_rows>0){
			echo 2;//si ya esta conciliado osea en la tabla de saldos conciliacion ya no podra reemplazar
		}else{
			echo 1;//si trae movimientos pero aun no finaliza la conciliacion
		}
	}else{
		echo 0;
	}
	 // $ultima = $this->ImportaModel->ultimaconciliacion($_REQUEST['cuentabancaria'],$_REQUEST['periodo'],$_REQUEST['ejercicio']);
	 // $separa = explode('-', $ultima);
	 // $ultimaconciliacion = $separa[0];
	 // $ejer = $separa[1];
	 // if($ultimaconciliacion != 0){
	 	// //print_r($_REQUEST['periodo'] ."--". $ultimaconciliacion);
		// // $previo = $this->ImportaModel->validaPeriodoPrevio($_REQUEST['periodo'], $_REQUEST['ejercicio'], $_REQUEST['cuentabancaria']);
		// if($ultimaconciliacion == 12){
			// $ultimaconciliacionfut = 1;
// 			
		// }else{
			// $ultimaconciliacionfut = $ultimaconciliacion+1;
		// }
		// //del mismo ano pero diferente periodo menor o mayor no podra importar el estadod y diferente al ultimo conciliado
		// if($_REQUEST['periodo'] != 12 && ($_REQUEST['periodo'] < $ultimaconciliacionfut || $_REQUEST['periodo'] > $ultimaconciliacionfut) && $_REQUEST['ejercicio'] == $ejer && $_REQUEST['periodo'] != $ultimaconciliacion){
			// echo 3;
		// }
		// //periodo diferente al 12 periodo y periodo no es el q debe subir y diferente ejercicio  y no es la ultima conciliacion no sube
		// elseif($_REQUEST['periodo'] != 12 && ($_REQUEST['periodo'] < $ultimaconciliacionfut || $_REQUEST['periodo'] > $ultimaconciliacionfut) && $_REQUEST['ejercicio'] != $ejer  && $_REQUEST['periodo'] != $ultimaconciliacion){
			// echo 3;
		// }
		// // diferente al 12 periodo igual al que debe importar pero no es menor ejercicio no sube
		// elseif($_REQUEST['periodo'] != 12 && ($_REQUEST['periodo'] == $ultimaconciliacionfut) && $_REQUEST['ejercicio'] < $ejer ){
			// echo 3;
		// }
		// // si periodo 12 e igual al que debe importar pero no es de ejericio no sube
		// elseif($_REQUEST['periodo'] == 12 && $_REQUEST['periodo'] ==  $ultimaconciliacionfut && $_REQUEST['ejercicio']!=$ejer){
			// echo 3;
		// }
		// //si periodo 12 pero no es el periodo a importar ni aunq sea del ejericico no sube
		// elseif($_REQUEST['periodo'] == 12 && $_REQUEST['periodo'] !=  $ultimaconciliacionfut && $_REQUEST['ejercicio']==$ejer){
			// echo 3;
		// }
		// //si es periodo 12 pero no es el q debe importar ni es del ejercicio no sube
		// elseif($_REQUEST['periodo'] == 12 && $_REQUEST['periodo'] !=  $ultimaconciliacionfut && $_REQUEST['ejercicio']!=$ejer){
			// echo 3;
		// }
		// // periodo igual al q debe subir y si es Enero(1) el ejercicio debe ser mayor
		// //esto tomando en cuanta que en la tabla de ejercicio esta consecutivo que asi deberia ser
		// elseif($_REQUEST['periodo'] == $ultimaconciliacionfut && $ultimaconciliacionfut==1){
			// if($_REQUEST['ejercicio'] == $ejer+1){
				// $valida = $this->ImportaModel->validaEstado($_REQUEST['periodo'], $_REQUEST['ejercicio'], $_REQUEST['cuentabancaria']);
				// if($valida->num_rows>0){
					// $finconciiado = $this->ImportaModel->verifaFinConciliacionBancos($_REQUEST['cuentabancaria'], $_REQUEST['periodo'], $_REQUEST['ejercicio']);
					// if($finconciiado->num_rows>0){
						// echo 2;//si ya esta conciliado osea en la tabla de saldos conciliacion ya no podra reemplazar
					// }else{
						// echo 1;//si trae movimientos pero aun no finaliza la conciliacion
					// }
				// }else{
					// echo 0;
				// }
			// }else{
				// echo 3;
			// }
		// }
		// elseif($_REQUEST['periodo'] == $ultimaconciliacionfut && $ultimaconciliacionfut!=1){
			// if($_REQUEST['ejercicio'] == $ejer){
				// $valida = $this->ImportaModel->validaEstado($_REQUEST['periodo'], $_REQUEST['ejercicio'], $_REQUEST['cuentabancaria']);
				// if($valida->num_rows>0){
					// $finconciiado = $this->ImportaModel->verifaFinConciliacionBancos($_REQUEST['cuentabancaria'], $_REQUEST['periodo'], $_REQUEST['ejercicio']);
					// if($finconciiado->num_rows>0){
						// echo 2;//si ya esta conciliado osea en la tabla de saldos conciliacion ya no podra reemplazar
					// }else{
						// echo 1;//si trae movimientos pero aun no finaliza la conciliacion
					// }
				// }else{
					// echo 0;
				// }
			// }else{
				// echo 3;
			// }
		// }
		// elseif( $_REQUEST['periodo'] != $ultimaconciliacionfut && $ultimaconciliacionfut!=1){
			// if($_REQUEST['ejercicio'] == $ejer){
				// $valida = $this->ImportaModel->validaEstado($_REQUEST['periodo'], $_REQUEST['ejercicio'], $_REQUEST['cuentabancaria']);
				// if($valida->num_rows>0){
					// $finconciiado = $this->ImportaModel->verifaFinConciliacionBancos($_REQUEST['cuentabancaria'], $_REQUEST['periodo'], $_REQUEST['ejercicio']);
					// if($finconciiado->num_rows>0){
						// echo 2;//si ya esta conciliado osea en la tabla de saldos conciliacion ya no podra reemplazar
					// }else{
						// echo 1;//si trae movimientos pero aun no finaliza la conciliacion
					// }
				// }else{
					// echo 0;
				// }
			// }else{
				// echo 3;
			// }
		// }
// 		
// 		
// 		
	 // }		
}			
/* Validar si es la primera conciliacion
 * si lo es debera preguntar si el periodo
 * a conciliar sera el primero de ser asi no
 * podra agregar conciliaciones previas
 * y ese estado de cuenta debera tener todos los movimientos bancarios
 * anteriores o tener ningun movimiento por parte del banco
 * REGRESARA 0 cuando no tenga ninguna finalizacion y esto quedra decir que es la primera
 */ 
function primerConciliacionB(){
	$primer = $this->ImportaModel->primerConciliacionB($_REQUEST['cuentabancaria']);
	echo $primer;
}

function borraDatosPrevios(){
	$ids = $this->ImportaModel->idsConciliacion($_REQUEST['cuentabancaria'], $_REQUEST['periodo'], $_REQUEST['ejercicio']);
	$borraStatusMovPolizas = $this->ImportaModel->borraids($ids);
	if($borraStatusMovPolizas==1){
		$borraMovEstado = $this->ImportaModel->borraMovsEstadoCuenta($_REQUEST['cuentabancaria'], $_REQUEST['periodo'], $_REQUEST['ejercicio']);
		if($borraMovEstado){
			echo 1;
		}else{
			echo 0;
		}
	}
	
}
function borraDatosPreviosBancos(){
	$ids = $this->ImportaModel->idsConciliacionBancos($_REQUEST['cuentabancaria'], $_REQUEST['periodo'], $_REQUEST['ejercicio']);
	//$borra = $this->ImportaModel->borraidsBancos($ids);
	if($ids==1){
		$borraEstado = $this->ImportaModel->borraMovsEstadoCuenta($_REQUEST['cuentabancaria'], $_REQUEST['periodo'], $_REQUEST['ejercicio']);
		if($borraEstado){
			echo 1;
		}else{
			echo 0;
		}
	}
	
}
function conciliaMovimientosDocumentos(){
	//agrega el idDoc al mov bancario
	$movBan = $this->ImportaModel->conciliaDocumentosManual($_REQUEST['idMovBanco'],$_REQUEST['idDoc']);
	if($movBan){
		//cambia como conciliado el documento
		$this->ImportaModel->conciliaDocumentosAutomatico($_REQUEST['idDoc']);
	}
}
function verificaMontosConciliadosB(){
	$regresaMov = "";
	foreach ($_REQUEST['idMovBancos'] as $idval){
		$idDoc = $this->ImportaModel->idsMovBancarioDoc($idval);//ids del movimientos e importe 
		$imporConcepto = $this->ImportaModel->importeConceptoBancario($idval);
		$separa = explode("/", $imporConcepto);
		$importeBanco = $separa[0];
		$conceptoBanco = $separa[1];
		$impDoc = $this->ImportaModel->verificaMontosConciliados($idDoc);//importe de las documentos
		if($importeBanco!=$impDoc){
			//$regresaMov = str_replace("*", "", $regresaMov);
			$this->ImportaModel->desconsilia_Movnulosbancos($idDoc,$idval);
			$regresaMov .= $conceptoBanco."\n";
		}
	}
	
	$retVal = (empty($regresaMov)) ?  1 : $regresaMov ;
	echo $retVal;
}
/*	UN DOCUMENTOS VARIOS MOV BANCARIOS
 * $_REQUEST['idMovBancos']  idDoc 
 * de este se saca importe y concepto.
 * se suman los importes de los mov bancario relacinados al idDoc
 * para sacar el importe total y verificar si es igual al del documento
 * Si no es igual desconcilia  ambos y muestra el concepto del documento q no cuadro 
 */
function verificaMontosConciliadosDocumentos(){
	$regresaMov = "";
	foreach ($_REQUEST['idMovBancos'] as $idval){
		$idDocumentoBanco = $this->ImportaModel->idsMovBancarioDocumento($idval);//ids del movimientos e importe 
		$imporConcepto = $this->ImportaModel->importeConceptoBancarioDocumento($idval);
		$separa = explode("/", $imporConcepto);
		$importeBanco = $separa[0];
		$conceptoBanco = $separa[1];
		$impMovDocumento = $this->ImportaModel->verificaMontosConciliadosB($idDocumentoBanco);//importe de los documentos
		if($importeBanco!=$impMovDocumento){
			$this->ImportaModel->desconsilia_MovnulosbancosDocumentos($idDocumentoBanco,$idval);
			$regresaMov .= $conceptoBanco."\n";
		}
	}
	$retVal = (empty($regresaMov)) ?  1 : $regresaMov ;
	echo $retVal;
	
}
function consultaPrevios(){

	$_SESSION['bancos']['periodo'] 		= $_REQUEST['periodo'];
	$_SESSION['bancos']['idejercicio'] 	= $_REQUEST['ejercicio'];
	$_SESSION['bancos']['idbancaria']	= $_REQUEST['idbancaria'];
}




}
	
?>