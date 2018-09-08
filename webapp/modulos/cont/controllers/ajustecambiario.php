<?php
   require('common.php');

//Carga el modelo para este controlador
require("models/ajustecambiario.php");

class Ajustecambiario extends Common
{
	public $AjustecambiarioModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar

		$this->AjustecambiarioModel = new AjustecambiarioModel();
		$this->AjustecambiarioModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->AjustecambiarioModel->close();
	}
	
	function verfiltro(){
		$datos= $this->AjustecambiarioModel->ejercicio();
		$tipomoneda=$this->AjustecambiarioModel->tipomoneda();
		$utilidad=$this->AjustecambiarioModel->utilidadperdida("utilidad");
		$perdida=$this->AjustecambiarioModel->utilidadperdida("perdida");
		$ListaSegmentos = $this->AjustecambiarioModel->ListaSegmentos();
		$ListaSucursales = $this->AjustecambiarioModel->ListaSucursales();
		require('views/captpolizas/ajustecambiario.php');
	}
	function validapoliza(){
		
		$validapoli=$this->AjustecambiarioModel->compruebapoliza($_REQUEST['periodo'],$_REQUEST['ejercicio']);
			if($validapoli!=0){
				echo ($validapoli);
			}else if($validapoli==0){
				echo (0);
			}
	}
	
	function generapoliza(){
		$segmento=$_REQUEST['segmento'];
		$sucursal=$_REQUEST['sucursal'];
		 $tc=$_REQUEST['tc'];
		$ejer=explode("/", $_REQUEST['ejercicio']);
		$Exercise = $this->AjustecambiarioModel->getExerciseInfo();
		if($Ex=$Exercise->fetch_assoc()){
		$idorg=$Ex['IdOrganizacion'];
		//$idejer=$Ex['IdEx'];
		$idperio=$Ex['PeriodoActual'];}
		$separau=explode("//",$_REQUEST['utilidad']);
		$utilidad=$separau[0];
		
		
		$separaper=explode("//",$_REQUEST['perdida']);
		$perdida=$separaper[0];
		
		$fecha=$ejer[1]."-".$_REQUEST['periodo']."-".date("d",(mktime(0,0,0,$_REQUEST['periodo']+1,1,$ejer[1])-1));
		
		
		
			
			$diferencia=0; //$perdida=0; $utilidad=0;
			$provisiones = array();
			//$tcME = array();
			$cli="";
			$poli="";
			$oper=0;
			$cuentas=array();
			$total = array();
			$cobropago=$this->AjustecambiarioModel->provisionescargos($_REQUEST['periodo'], $ejer[0],$_REQUEST['moneda'],1);//cargos me
			if($cobropago->num_rows>0){
				while($row2 = $cobropago->fetch_array()){
					//$provision = $this->AjustecambiarioModel->provisionesunidas($row2['Cuenta'], $row2['relacionExt']);
					// if($provisiones[ $row2['Cuenta'] ]['Cargo M.E.']){
						// $provisiones[ $row2['Cuenta'] ]['Cargo M.E.'] += $row2['importe'];
					// }else{
						// $provisiones[ $row2['Cuenta'] ]['Cargo M.E.'] = $row2['importe'];
					// }
					$provisiones[ $row2['Cuenta'] ]['cuenta'] = $row2['Cuenta'];		
					$provisiones[ $row2['Cuenta'] ]['periodo'] = $row2['idperiodo'];
					if(!@$provisiones[ $row2['Cuenta'] ]['monto']){
						$provisiones[ $row2['Cuenta'] ]['monto']= $row2['importe'];
					}else{
						$provisiones[ $row2['Cuenta'] ]['monto']+= $row2['importe'];	
					}
					$cuentas[$row2['Cuenta']]['cuenta']=$row2['Cuenta'];
					//para el reporte
					$provisiones[ $row2['Cuenta'] ]['descripcion']=$row2['description'];
					$provisiones[ $row2['Cuenta'] ]['manual'] = $row2['manual_code'];
				}	
				foreach($cuentas as $valcuent){ 
					$abonosme = $this->AjustecambiarioModel->pagoscobros($_REQUEST['periodo'],$ejer[0],$_REQUEST['moneda'],$valcuent['cuenta'],2);//abonos me
					if($abonosme->num_rows>0){
						while($row = $abonosme->fetch_array()){
							
							// if($provisiones[ $row['Cuenta'] ]['Abono M.E']){
								// $provisiones[ $row['Cuenta'] ]['Abono M.E'] += $row['importe'];
							// }else{
								// $provisiones[ $row['Cuenta'] ]['Abono M.E'] = $row['importe'];
							// }
							$provisiones[ $valcuent['cuenta']] ['monto'] -= $row['importe'];
							//$provisiones[ $row2['Cuenta'] ]['Cargo M.E.']-= $row['importe'];
							$provisiones[  $row['Cuenta'] ]['cuenta'] = $row['Cuenta'];
							$provisiones[ $row['Cuenta'] ]['periodo'] = $row['idperiodo'];
							//$cuentas[$row['Cuenta']]['cuenta']=$row['Cuenta'];
						}
					}
				}	
				
			}	
				// foreach($provisiones as $value){
						// $value['monto']."//" ;	
				// }
			/* ------------------ moneda nacional ------------------------*/
		foreach($cuentas as $valcuent){ 
			$cargosprovimn = $this->AjustecambiarioModel->pagoscobros($_REQUEST['periodo'], $ejer[0],$_REQUEST['moneda'],$valcuent['cuenta'],4);//cargos me
			if($cargosprovimn->num_rows>0){
				while($row2 = $cargosprovimn->fetch_array()){
					//$provision = $this->AjustecambiarioModel->provisionesunidas($row2['Cuenta'], $row2['relacionExt']);
					// if($provisiones[ $row2['Cuenta'] ]['Cargo']){
						// $provisiones[ $row2['Cuenta'] ]['Cargo']= $row2['importe'];
					// }else{
						// $provisiones[ $row2['Cuenta'] ]['Cargo'] += $row2['importe'];
					// }
					$cuentas[$row2['Cuenta'] ]['cuenta']=$row2['Cuenta'];
					$provisiones[ $row2['Cuenta'] ]['cuenta'] = $row2['Cuenta'];		
					$provisiones[ $row2['Cuenta'] ]['periodo'] = $row2['idperiodo'];
					if(!@$provisiones[ $row2['Cuenta'] ]['montomn']){
						$provisiones[ $row2['Cuenta'] ]['montomn']= $row2['importe'];
					}else{
						$provisiones[ $row2['Cuenta'] ]['montomn']+= $row2['importe'];	
					}
					//para el reporte
					$provisiones[ $row2['Cuenta'] ]['descripcion']=$row2['description'];
					$provisiones[ $row2['Cuenta'] ]['manual'] = $row2['manual_code'];
				}
				// foreach($cuentas as $valcuent){
					$abonosmn = $this->AjustecambiarioModel->pagoscobros($_REQUEST['periodo'],$ejer[0],$_REQUEST['moneda'],$valcuent['cuenta'],3);//abonos me
					if($abonosmn->num_rows>0){
						while($row = $abonosmn->fetch_array()){
							
							// if($provisiones[ $row['Cuenta'] ]['Abono']){
								// $provisiones[ $row['Cuenta'] ]['Abono'] += $row['importe'];
							// }else{
								// $provisiones[ $row['Cuenta'] ]['Abono'] = $row['importe'];
							// }
							//$cuentas[$row['Cuenta']]['cuenta']=$row['Cuenta'];
							$provisiones[ $valcuent['cuenta'] ] ['montomn'] -= $row['importe'];
							$provisiones[ $row['Cuenta'] ]['cuenta'] = $row['Cuenta'];
							$provisiones[ $row['Cuenta'] ]['periodo'] = $row['idperiodo'];
							
						}
					//}
					
				}		
			}
			}
// 				
			/* ------------------fin moneda nacional ------------------------*/	
					//ver q aser con los acreedores porq se meten en los calculos y probar bancos incluir la poliza d eajuste
/* ------------------------------------ACREDOR-------------------------------------------------*/
		$cuentas=array();
			$abonome=$this->AjustecambiarioModel->provisionescargos($_REQUEST['periodo'], $ejer[0],$_REQUEST['moneda'],2);//abonos me
			if($abonome->num_rows>0){
				while($row2 = $abonome->fetch_array()){
					//$provision = $this->AjustecambiarioModel->provisionesunidas($row2['Cuenta'], $row2['relacionExt']);
					// if($provisiones[ $row2['Cuenta'] ]['Abono M.E']){
						// $provisiones[ $row2['Cuenta'] ]['Abono M.E'] += $row2['importe'];
					// }else{
						// $provisiones[ $row2['Cuenta'] ]['Abono M.E'] = $row2['importe'];
					// }
					$cuentas[$row2['Cuenta']]['cuenta']=$row2['Cuenta'];
					$provisiones[ $row2['Cuenta'] ]['cuenta'] = $row2['Cuenta'];		
					$provisiones[ $row2['Cuenta'] ]['periodo'] = $row2['idperiodo'];
					if(!@$provisiones[ $row2['Cuenta'] ]['provemonto']){
						$provisiones[ $row2['Cuenta'] ]['provemonto']= $row2['importe'];
					}else{
						 $provisiones[ $row2['Cuenta'] ]['provemonto']+= $row2['importe'];	
					}
					//para el reporte
					$provisiones[ $row2['Cuenta'] ]['descripcion']=$row2['description'];
					$provisiones[ $row2['Cuenta'] ]['manual'] = $row2['manual_code'];
				}
				foreach($cuentas as $valcuent){	
					$cobrosme = $this->AjustecambiarioModel->pagoscobros($_REQUEST['periodo'],$ejer[0],$_REQUEST['moneda'],$valcuent['cuenta'],1);//cargos me
					if($cobrosme->num_rows>0){
						while($row = $cobrosme->fetch_array()){
							
							// if($provisiones[ $row['Cuenta'] ]['Cargo M.E.']){
								// $provisiones[ $row['Cuenta'] ]['Cargo M.E.'] += $row['importe'];
							// }else{
								// $provisiones[ $row['Cuenta'] ]['Cargo M.E.'] = $row['importe'];
							// }
							$provisiones[ $valcuent['cuenta'] ] ['provemonto'] -= $row['importe'];
							//$provisiones[ $row2['Cuenta'] ]['Abono M.E']-= $row['importe'];
							$provisiones[ $row['Cuenta'] ]['cuenta'] = $row['Cuenta'];
							$provisiones[ $row['Cuenta'] ]['periodo'] = $row['idperiodo'];
							//$cuentas[$row['Cuenta']]['cuenta']=$row['Cuenta'];
						}
					}
					
				}
				
				// foreach($provisiones as $value){
				 	// $value['Cargo M.E.']."//" ;	
				// }
			/* ------------------ moneda nacional ------------------------*/
		foreach($cuentas as $valcuent){	
			$cargosprovimn=$this->AjustecambiarioModel->pagoscobros($_REQUEST['periodo'], $ejer[0],$_REQUEST['moneda'],$valcuent['cuenta'],3);//abono mn
			if($cargosprovimn->num_rows>0){
				while($row2 = $cargosprovimn->fetch_array()){
					//$provision = $this->AjustecambiarioModel->provisionesunidas($row2['Cuenta'], $row2['relacionExt']);
					
					//$cuentas[$row2['Cuenta'] ]['cuenta']=$row2['Cuenta'];
					$provisiones[ $row2['Cuenta'] ]['cuenta'] = $row2['Cuenta'];
					$provisiones[ $row2['Cuenta'] ]['periodo'] = $row2['idperiodo'];
					if(!@$provisiones[ $row2['Cuenta'] ]['provemontomn']){
						$provisiones[ $row2['Cuenta'] ]['provemontomn']= $row2['importe'];
					}else{
						$provisiones[ $row2['Cuenta'] ]['provemontomn']+= $row2['importe'];	
					}
					//para el reporte
					$provisiones[ $row2['Cuenta'] ]['descripcion']=$row2['description'];
					$provisiones[ $row2['Cuenta'] ]['manual'] = $row2['manual_code'];
				}
			}		
		}
		foreach($cuentas as $valcuent){			
					$abonosmn = $this->AjustecambiarioModel->pagoscobros($_REQUEST['periodo'],$ejer[0],$_REQUEST['moneda'],$valcuent['cuenta'],4);//cargo mn
					if($abonosmn->num_rows>0){
						while($row = $abonosmn->fetch_array()){ 
							
							// if(!@$provisiones[ $row['Cuenta'] ]['Cargo']){
								// $provisiones[ $row['Cuenta'] ]['Cargo'] = $row['importe'];
							// }else{
								// $provisiones[ $row['Cuenta'] ]['Cargo'] += $row['importe'];
// 								
							// }
							$provisiones[ $valcuent['cuenta'] ] ['provemontomn'] -= $row['importe'];
							$provisiones[ $row['Cuenta'] ]['cuenta'] = $row['Cuenta'];
							$provisiones[ $row['Cuenta'] ]['periodo'] = $row['idperiodo'];
							//$cuentas[$row['Cuenta']]['cuenta']=$row['Cuenta'];
						}
					}
				
			
		  }//foreach	
		}				
///TODAS LAS CUENTAS SI TIENEN SALDO SE SACA EL AJUSTE D EFIN DE MES SI SE SALDA ESE MES Y QUEDA SALDO 0 YANO SE TOMA EN CUENTA
				foreach($provisiones as $value){
					if(isset($value['monto'])){ 
						if(@$value['monto']!=0){
							$saldoext=$value['monto']*$tc;
							$utiper = $value['montomn'] - $saldoext;
						
							//echo $utiper."//" ;	
						}else{
							$utiper = number_format(@$value['montomn'],2,'.','');
						}
							//echo $value['cuenta'] ."->".$value['monto']."/total/".$utiper;
							if($utiper<0){//utilidad
								$total[ $value['cuenta'] ]['cuenta'] = $value['cuenta'];
								//$provisiones[ $value['cuenta'] ]['manual'] = $row2['manual_code'];
								$total[ $value['cuenta'] ]['utilidad']=abs($utiper);
								$total[ $value['cuenta'] ]['perdida']=0;
								//$provisiones[ $value['cuenta'] ]['descripcion']=$row2['description'];
								
							}else{
								$total[ $value['cuenta'] ]['cuenta'] = $value['cuenta'];
								//$provisiones[ $value['cuenta'] ]['manual'] = $row2['manual_code'];
								$total[ $value['cuenta'] ]['perdida']=abs($utiper);
								$total[ $value['cuenta'] ]['utilidad']=0;
								
								//$provisiones[ $value['cuenta'] ]['descripcion']=$row2['description'];
							}
						//para el reporte
							$total[ $value['cuenta'] ]['descripcion']=$value['descripcion'];
							$total[ $value['cuenta'] ]['manual']=@$value['manual'];
							$total[ $value['cuenta'] ]['montome']=@$value['monto'];
							$total[ $value['cuenta'] ]['montomn']=@$value['montomn'];
					}	
//echo $value['provemonto'];	
					if(isset($value['provemonto'])){
						if(@$value['provemonto']!=0){
								$saldoext=$value['provemonto']*$tc;
								$utiperprove = $value['provemontomn'] - $saldoext;
							}else if(@$value['provemonto']==0){
								
									$utiperprove = $value['provemontomn'];
								
							}
						if((number_format(abs($value['provemontomn']),2,'.',''))>0){	
							if($utiperprove<0){//utilidad
								$total[ $value['cuenta'] ]['utilidad']=0;
								$total[ $value['cuenta'] ]['perdida']=abs($utiperprove);
							}else{
								$total[ $value['cuenta'] ]['utilidad']=abs($utiperprove);
								$total[ $value['cuenta'] ]['perdida']=0;
							}
								
							$total[ $value['cuenta'] ]['cuenta']=$value['cuenta'];
							//reporte
							$total[ $value['cuenta'] ]['descripcion']=$value['descripcion'];
							$total[ $value['cuenta'] ]['manual']=$value['manual'];
							$total[ $value['cuenta'] ]['provemontome']=@$value['provemonto'];
							$total[ $value['cuenta'] ]['provemontomn']=@$value['provemontomn'];
						}
					}	
				} 	
					
					//echo $utiper;
				//}			
			 // foreach($total as $value){
				 // echo $value['Abono M.E']."//";
			 // }
			
				/*	B  A  N  C  O  S  */
				$mov=array();
				$cuentasbancos=array();
			$cargosme=$this->AjustecambiarioModel->bancossaldos($_REQUEST['periodo'], $ejer[0],$_REQUEST['moneda'],1);//cargos me
				if($cargosme->num_rows>0){
					while($row2 = $cargosme->fetch_array()){
						$mov[$row2['Cuenta']]['periodo']=$row2['idperiodo'];
						$mov[$row2['Cuenta']]['cuenta']=$row2['Cuenta'];
						$mov[$row2['Cuenta']]['poliza']=$row2['IdPoliza'];
						$mov[$row2['Cuenta']]['manual']=$row2['manual_code'];
						$mov[$row2['Cuenta']]['nombre']=$row2['description'];
						if(!@$mov[ $row2['Cuenta'] ]['saldome']){
							$mov[$row2['Cuenta']]['saldome'] = $row2['importe'];
						}else{
							$mov[$row2['Cuenta']]['saldome'] += $row2['importe'];
						}
						
					 	$cuentasbancos[$row2['Cuenta']]['cuenta']=$row2['Cuenta'];
						$mov[$row2['Cuenta']]['manual']=$row2['manual_code'];
						$mov[$row2['Cuenta']]['nombre']=$row2['description'];
					}
							
				}
			foreach($cuentasbancos as $valbanco){
				$abonome=$this->AjustecambiarioModel->pagocobrobancossaldos($_REQUEST['periodo'], $ejer[0],$_REQUEST['moneda'],$valbanco['cuenta'],2);//abonos me
					if($abonome->num_rows>0){
					
						while($row = $abonome->fetch_array()){ 
							$mov[$row['Cuenta']]['periodo']=$row['idperiodo'];
							$mov[$row['Cuenta']]['cuenta']=$row['Cuenta'];
							$mov[$row['Cuenta']]['poliza']=$row['IdPoliza'];
							$mov[$row['Cuenta']]['manual']=$row['manual_code'];
							$mov[$row['Cuenta']]['nombre']=$row['description'];
							
							$mov[$valbanco['cuenta']]['saldome'] -= $row['importe'];
							
						}
					}		
				}	
		/*   ............      movimientos mn   .............          */		
				$cuentasbancos=array();
				$cargosmn=$this->AjustecambiarioModel->bancossaldos($_REQUEST['periodo'], $ejer[0],$_REQUEST['moneda'],4);//cargos mn
				if($cargosmn->num_rows>0){
					while($row2 = $cargosmn->fetch_array()){
						$mov[$row2['Cuenta']]['periodo']=$row2['idperiodo'];
						$mov[$row2['Cuenta']]['cuenta']=$row2['Cuenta'];
						$mov[$row2['Cuenta']]['poliza']=$row2['IdPoliza'];
						$mov[$row2['Cuenta']]['manual']=$row2['manual_code'];
						$mov[$row2['Cuenta']]['nombre']=$row2['description'];
						if(!@$mov[$row2['Cuenta']]['saldomn']){
							$mov[$row2['Cuenta']]['saldomn'] = $row2['importe'];
						}else{
							$mov[$row2['Cuenta']]['saldomn'] += $row2['importe'];
						}
						
					 	$cuentasbancos[$row2['Cuenta']]['cuenta']=$row2['Cuenta'];
						$mov[$row2['Cuenta']]['manual']=$row2['manual_code'];
						$mov[$row2['Cuenta']]['nombre']=$row2['description'];
					}
							
				}
				foreach($cuentasbancos as $valbanco){
				$bancosmn=$this->AjustecambiarioModel->pagocobrobancossaldos($_REQUEST['periodo'], $ejer[0],$_REQUEST['moneda'],$valbanco['cuenta'],3);//abono me
					if($bancosmn->num_rows>0){
					
						while($row = $bancosmn->fetch_array()){
							$mov[$row['Cuenta']]['periodo']=$row['idperiodo'];
							$mov[$row['Cuenta']]['cuenta']=$row['Cuenta'];
							$mov[$row['Cuenta']]['poliza']=$row['IdPoliza'];
							$mov[$row['Cuenta']]['manual']=$row['manual_code'];
							$mov[$row['Cuenta']]['nombre']=$row['description'];
							
							$mov[$valbanco['cuenta']]['saldomn'] -= $row['importe'];
							
						}
					}		
				}	
				

				
				foreach($mov as $value){
				 if(isset($value['saldome'])){
						if($value['saldome']!=0){
							$saldoext=$value['saldome']*$tc; 
							
							$utiper = $value['saldomn'] - $saldoext;
							
						}else if($value['saldome']==0){
							$utiper = $value['saldomn'];
						}
					//$oper=abs(@$val['saldoext']*$tc)-abs(@$val['saldo']);
						if(number_format($value['saldomn'],2,'.','')<0){
										
							$bancos[ $value['cuenta'] ]['utilidad']=0;
							$bancos[ $value['cuenta'] ]['perdida']=$utiper;
										
						} if(number_format($value['saldomn'],2,'.','')>0){
							if($utiper<0){//utilidad
								$bancos[ $value['cuenta'] ]['utilidad']=abs($utiper);
								$bancos[ $value['cuenta'] ]['perdida']=0;
							}else{
								$bancos[ $value['cuenta'] ]['utilidad']=0;
								$bancos[ $value['cuenta'] ]['perdida']=abs($utiper);
							}
						}
						$bancos[ $value['cuenta'] ]['cuenta']=$value['cuenta'];
						//reporte
						$bancos[$value['cuenta']]['manual']=$value['manual'];
						$bancos[$value['cuenta']]['nombre']=$value['nombre'];
						$bancos[ $value['cuenta'] ]['saldome']=$value['saldome'];
						$bancos[ $value['cuenta'] ]['saldomn']=$value['saldomn'];
					}
					
				}
			
			// foreach ($movperiodo as $val){
			// foreach($val as $value){
				// //echo $value['saldoext']."<br>";
			// }
			// }
			
			// foreach($bancos as $val){
// 				
				// //if($val['cuenta']){
				// echo $val['cuenta']."->".$val['saldome']."//".$val['perdida']."<br>";
				// //}
// 				
			// }
					
			/* 	F I N   B A N C O S   */
	if($_REQUEST['proceso']==1){
			/* 		C R E A C I O N 	P O L I Z A 		*/
			 $idpoliza=0; $error=false;
			 if($_REQUEST['idpoli']==0){//la poliza cuando es nueva
			 	 $poli=$this->AjustecambiarioModel->savePoliza2($idorg, $ejer[0], $_REQUEST['periodo'], 3, "POLIZA DE AJUSTE POR DIFERENCIA CAMBIARIA" , $fecha);
				 if($poli==0){
					 $numPoliza = $this->AjustecambiarioModel->getLastNumPoliza();
					 $idpoliza=$numPoliza['id'];
				 }
			 }//
			 else if($_REQUEST['idpoli']!=0){// sino sera nueva poliza
				 $borramov=$this->AjustecambiarioModel->borramovimientos($_REQUEST['idpoli']);
				 $idpoliza=$_REQUEST['idpoli'];
// 				
			}
				$nummov=1;
			foreach($total as $value){
					// //echo $value['CME']."//".$value['AME'];
					//echo $value['utilidad'];
				if($value['utilidad']!=0){
					$cargo=$this->AjustecambiarioModel->InsertMov2($idpoliza,$nummov,$segmento,$sucursal,$value['cuenta'],"Cargo",($value['utilidad']),"Utilidad en cambios",'1-','',"Utilidad en cambios",0);
					// if($value['Cargo M.E.']){
						// //$cargo=$this->AjustecambiarioModel->InsertMov2($idpoliza,$nummov,$segmento,$sucursal,$value['cuenta'],"Cargo M.E.",$value['Cargo M.E.'],"Utilidad en cambios",'1-','',"Utilidad en cambios",0);
					// }if($value['Abono M.E']){
						// //$cargo=$this->AjustecambiarioModel->InsertMov2($idpoliza,$nummov+1,$segmento,$sucursal,$utilidad,"Abono M.E",$value['Abono M.E'],"Utilidad en cambios",'1-','',"Utilidad en cambios",0);
					// }
					if($cargo){
						$this->AjustecambiarioModel->InsertMov2($idpoliza,$nummov+1,$segmento,$sucursal,$utilidad,"Abono",($value['utilidad']),"Utilidad en cambios",'1-','',"Utilidad en cambios",0);
					}else{ $error=true;}
				}else if($value['perdida']!=0){
			 		$abono=$this->AjustecambiarioModel->InsertMov2($idpoliza,$nummov,$segmento,$sucursal,$value['cuenta'],"Abono",$value['perdida'],"Perdida en cambios",'1-','',"Perdida en cambios",0);
				 	// if($value['Abono M.E']){
				 		// //$cargo=$this->AjustecambiarioModel->InsertMov2($idpoliza,$nummov,$segmento,$sucursal,$value['cuenta'],"Abono M.E",$value['Abono M.E'],"Perdida en cambios",'1-','',"Perdida en cambios",0);
				 	// }if($value['Cargo M.E.']){
				 		// //$cargo=$this->AjustecambiarioModel->InsertMov2($idpoliza,$nummov+1,$segmento,$sucursal,$perdida,"Cargo M.E.",$value['Cargo M.E.'],"Perdida en cambios",'1-','',"Perdida en cambios",0);
				 	// }
			 		if($abono){
			 			$this->AjustecambiarioModel->InsertMov2($idpoliza,$nummov+1,$segmento,$sucursal,$perdida,"Cargo",$value['perdida'],"Perdida  en cambios",'1-','',"Perdida en cambios",0);
					}else{
						$error=true;
					}
						
			 	}
				if($error==false){	
					$nummov+=2;
				}
							// // echo "utilidadperdida<br>".$value['utilidad'].'<br>';
							// // echo $value['perdida'].'<br>///extranjero<br>';
							// // echo $value['AME'].'<br>';
							// // echo $value['CME'].'<br>';
// 				
// 				
		 }//if del foreach
				foreach($bancos as $val){//VER Q META A BANCOS LA UTILIDAD Y PERDIDA BIEN PORQ NOE STA BIEN
					if(@$val['cuenta']){
						if(@$val['utilidad']!=0){
							$cargo=$this->AjustecambiarioModel->InsertMov2($idpoliza,$nummov,$segmento,$sucursal,$val['cuenta'],"Cargo",abs($val['utilidad']),"Utilidad en cambios",'1-','',"Utilidad en cambios",0);
							//$cargome=$this->AjustecambiarioModel->InsertMov2($idpoliza,$nummov,$segmento,$sucursal,$val['cuenta'],"Cargo M.E.",abs($val['saldoext']),"Utilidad en cambios",'1-','',"Utilidad en cambios",0);
							//$abonome=$this->AjustecambiarioModel->InsertMov2($idpoliza,$nummov+1,$segmento,$sucursal,$utilidad,"Abono M.E",abs($val['saldoext']),"Utilidad en cambios",'1-','',"Utilidad en cambios",0);
							
							$abonoutlidad=$this->AjustecambiarioModel->InsertMov2($idpoliza,$nummov+1,$segmento,$sucursal,$utilidad,"Abono",abs($val['utilidad']),"Utilidad en cambios",'1-','',"Utilidad en cambios",0);
							
						
						}else if(@$val['perdida']!=0){
							$abono=$this->AjustecambiarioModel->InsertMov2($idpoliza,$nummov,$segmento,$sucursal,$val['cuenta'],"Abono",abs($val['perdida']),"Perdida en cambios",'1-','',"Perdida en cambios",0);
							//$abonome=$this->AjustecambiarioModel->InsertMov2($idpoliza,$nummov,$segmento,$sucursal,$val['cuenta'],"Abono M.E",abs($val['saldoext']),"Perdida en cambios",'1-','',"Perdida en cambios",0);
							//$cargome=$this->AjustecambiarioModel->InsertMov2($idpoliza,$nummov+1,$segmento,$sucursal,$perdida,"Cargo M.E.",abs($val['saldoext']),"Perdida en cambios",'1-','',"Perdida en cambios",0);
							$abonoperdia=$this->AjustecambiarioModel->InsertMov2($idpoliza,$nummov+1,$segmento,$sucursal,$perdida,"Cargo",abs($val['perdida']),"Perdida en cambios",'1-','',"Perdida en cambios",0);
							
						}
					//echo $val['cuenta']."->".$val['utilidad']."//".$val['perdida']."<br>";
					}
					$nummov+=2;
					
				}
				
				if($error==false){
					echo '-_-1-_-';
				}else{
					echo '-_-0-_-';
				}		
		
		}//proceso cuando es 1
		else if ($_REQUEST['proceso']==2){
			$moneda=$this->AjustecambiarioModel->moneda($_REQUEST['moneda']);
			require('views/captpolizas/ajustecambiarioreport.php');
			
		}
		
	}
	
	function cargacuentas(){
		
	}
	
	function moviextranjeros2(){
		$mov=$this->AjustecambiarioModel->moviextranjeros($_REQUEST['idejer'], $_REQUEST['idpoliza']);
		if($mov->num_rows>0){
			if($row2=$mov->fetch_array()){
				$sitiene = $this->AjustecambiarioModel->relacionext($_REQUEST['idpoliza']);
				$cuentas=$this->AjustecambiarioModel->consultaprovisiones($_REQUEST['idejer'], $row2['Cuenta'],$_REQUEST['idpoliza'],$sitiene);
				if($cuentas->num_rows>0){
					echo "<option value=0>Ninguna</option>";
					while($row=$cuentas->fetch_array()){
						if($sitiene==$row['id']){
							echo "<option value=".$row['id']." selected>Poliza:".$row['numpol'].",[".$row['fecha']."] Concepto:".$row['concepto']."</option>";
						}else{
							echo "<option value=".$row['id'].">Poliza:".$row['numpol'].",[".$row['fecha']."] Concepto:".$row['concepto']."</option>";
						}
					}
				}else{
					echo 0;
				}	
			}
		}else{ echo 'no';}
	}
	
}
?>