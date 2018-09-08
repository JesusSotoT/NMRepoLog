<?php
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

	class ConfigModel extends Connection
	{
		function importinsert($cadena)
		{
			//Inserta valores en la tabla
			$sql=$this->query("INSERT INTO `cont_accounts` (`account_id`, `account_code`, `manual_code`, `description`, `sec_desc`, `account_type`, `status`, `main_account`, `cash_flow`, `reg_date`, `currency_id`, `group_dig`, `id_sucursal`, `seg_neg_mov`, `affectable`, `mod_date`, `father_account_id`, `removable`, `account_nature`, `removed`,`main_father`,`cuentaoficial`,`nif`)
					VALUES ( ".$cadena.";");
					
		}
		function saveConfig($act,$IdCompany,$default_catalog,$structure,$values,$level,$numpol,$rfc,$begin,$period,$periods,$current_period,$open_periods,$primera_vez,$cl_num,$tipoinstancia,$todas_facturas,$confirmar_poliza, $siete_dimensiones)
		{	
			$date_begin=explode('-',$begin);//Acomoda la fecha al formato de la bd
			if(!$act)
			{
				$myQuery = "INSERT INTO cont_config (IdOrganizacion,TipoCatalogo,Estructura,TipoValores,TipoNiveles,NumPol,RFC,InicioEjercicio,FinEjercicio,TipoPeriodo,NumPeriodos,PeriodoActual,PeriodosAbiertos,EjercicioActual,PrimeraVez) 
						VALUES ($IdCompany,'$default_catalog','$structure','$values','$level',$numpol,'$rfc','$date_begin[2]-$date_begin[1]-$date_begin[0]',DATE_ADD('$date_begin[2]-$date_begin[1]-$date_begin[0]',INTERVAL 364 DAY),'$period',$periods,$current_period,$open_periods,0,0)";
			}
			else
			{
				if(intval($primera_vez))
				{
					// Inicia seccion que llena el arbol contable
					if($default_catalog<2)
					{
						$handle = ( $default_catalog == 1 ) ? fopen( 'models/fullTree.sql', "r" ) : fopen( 'models/basicTree.sql', "r" );
					    
    					if(intval($this->tipoinstancia()) != 1)
    						$handle = fopen( 'models/eduTree.sql', "r" );
					  
					  $myQuery = "SELECT account_id FROM cont_accounts";
					  $result = $this->query($myQuery);
					  $row = $result->fetch_assoc();

					  if (!$row) {
					  	$query = fread($handle,15000);
							$this->query( $query );
					  } 
						fclose($handle);
	
						//INICIA//////////////////////////////////////////////////
						//Si el usuario eligio - en vez de . en la mascara de la configuracion, substituye todos los . con -.

						//Compara si la estructura es con guio entonces hace las modificaciones
						/*if(strpos($structure, '-') == true)
						{
							//Extrae todas las cuentas
							$c = $this->query("SELECT account_id,manual_code FROM cont_accounts");
							while($cn = $c->fetch_assoc())
							{
								//Substituye el caracter
								$nuevo = str_replace('.', '-', $cn['manual_code']);
								//Actualiza el registro con el nuevo caracter
								$this->query("UPDATE cont_accounts SET manual_code = '$nuevo' WHERE account_id = ".$cn['account_id']);
							}
						}*/
						//TERMINA//////////////////////////////////////////////////
						

					}
					// Inicia seccion que llena el arbol contable
					$myQuery = "UPDATE  cont_config SET 
					TipoCatalogo = '$default_catalog',
					Estructura = '$structure',
					TipoValores = '$values',
					TipoNiveles = '$level',
					NumPol = $numpol,
					RFC = '$rfc',
					ClaveOrg = '$cl_num',
					InicioEjercicio = '$date_begin[2]-$date_begin[1]-$date_begin[0]',
					FinEjercicio = DATE_ADD('$date_begin[2]-$date_begin[1]-$date_begin[0]',INTERVAL 364 DAY),
					TipoPeriodo = '$period',
					NumPeriodos = $periods,
					PeriodoActual = $current_period,
					PeriodosAbiertos = $open_periods,
					PrimeraVez = 0,
					TodasFacturas = $todas_facturas,
					ConfirmPoliza = $confirmar_poliza,
					siete_dimensiones = $siete_dimensiones
					";
				}
				else
				{
					$myQuery = "UPDATE  cont_config SET 
					TipoValores = '$values',
					RFC = '$rfc',
					InicioEjercicio = '$date_begin[2]-$date_begin[1]-$date_begin[0]',
					FinEjercicio = DATE_ADD('$date_begin[2]-$date_begin[1]-$date_begin[0]',INTERVAL 364 DAY),
					TipoPeriodo = '$period',
					NumPeriodos = $periods,
					PeriodoActual = $current_period,
					PeriodosAbiertos = $open_periods,
					PrimeraVez = 0,
					TodasFacturas = $todas_facturas,
					ConfirmPoliza = $confirmar_poliza,
					siete_dimensiones = $siete_dimensiones
					";
				}
			}
			$this->query( $myQuery );			
		}

		function saveConfigAccounts($compras,$ventas,$dev,$clientes,$IVA,$Caja,$TR,$Bancos,$Saldos,$Flujo,$Proveedores,$utilidades,$perdida,$CuentaIVAPendientePago,$CuentaIVApagado,$CuentaIVAPendienteCobro,$CuentaIVAcobrado,$CuentaIEPSPendientePago,$CuentaIEPSpagado,$CuentaIEPSPendienteCobro,$CuentaIEPScobrado,$CuentaDeudores,$CuentaGastosPolizas,$ISH,$IVAretenido,$ISRretenido,$statusIVAIEPS,$statusRetencionISH,$iepsgasto,$statusIEPS,$CuentaSueldoxPagar,$statuSueldoxPagar,$ivagasto,$statusIVA)
		{	
			//$date_begin=explode('-',$begin);//Acomoda la fecha al formato de la bd
			
					// Inicia seccion que llena el arbol contable
					$myQuery = "UPDATE  cont_config SET 
					CuentaCompras = -1,
					CuentaVentas = $ventas,
					CuentaDev = -1,
					CuentaClientes = $clientes,
					CuentaIVA = $IVA,
					CuentaCaja = $Caja,
					CuentaTR = -1,
					CuentaBancos = $Bancos,
					CuentaSaldos = $Saldos,
					CuentaFlujoEfectivo = -1,
					CuentaProveedores = $Proveedores,
					CuentaUtilidad = $utilidades,
					CuentaPerdida = $perdida,
					CuentaIVAPendientePago = $CuentaIVAPendientePago,
					CuentaIVApagado = $CuentaIVApagado,
					CuentaIVAPendienteCobro = $CuentaIVAPendienteCobro,
					CuentaIVAcobrado = $CuentaIVAcobrado,
					CuentaIEPSPendientePago = $CuentaIEPSPendientePago,
					CuentaIEPSpagado = $CuentaIEPSpagado,
					CuentaIEPSPendienteCobro = $CuentaIEPSPendienteCobro,
					CuentaIEPScobrado = $CuentaIEPScobrado,
					CuentaDeudores = $CuentaDeudores,
					ISH = $ISH,
					ISRretenido = $ISRretenido,
					IVAretenido = $IVAretenido,
					CuentaIEPSgasto = $iepsgasto,
					CuentaIVAgasto = $ivagasto,
					CuentasGastosPolizas = $CuentaGastosPolizas,
					CuentaSueldoxPagar = $CuentaSueldoxPagar,
					statusIVAIEPS = $statusIVAIEPS,
					statusRetencionISH = $statusRetencionISH,
					statusIEPS = $statusIEPS,
					statusIVA = $statusIVA,
					statuSueldoxPagar = $statuSueldoxPagar
					WHERE id=1
					";
				
			$this->query( $myQuery );			
		}

		function getAllExercises()
		{
			$myQuery = "SELECT e.Id, e.NombreEjercicio, e.Cerrado, c.EjercicioActual FROM cont_ejercicios e LEFT JOIN cont_config c ON c.EjercicioActual = e.NombreEjercicio AND c.id = 1 ORDER BY NombreEjercicio";
			$companies = $this->query($myQuery);
			return $companies;
		}


		function getCompanyName($idorg)
		{
			$myQuery = "SELECT nombreorganizacion FROM organizaciones WHERE idorganizacion=".$idorg;
			$companies = $this->query($myQuery);
			$com = $companies->fetch_assoc();
			return $com;
		}

		function getExerciseName($idex)
		{
			$myQuery = "SELECT NombreEjercicio FROM cont_ejercicios WHERE Id=".$idex;
			$exercises = $this->query($myQuery);
			$ex = $exercises->fetch_assoc();
			return $ex;
		}

		function getExerciseInfo()
		{
			$myQuery = "SELECT * FROM cont_config";
			$info = $this->query($myQuery);
			$inform = $info->fetch_assoc();
			return $inform;
		}
		function sinConfiguracion()
		{
			$myQuery = $this->query("SELECT * FROM cont_config");
			if($myQuery->num_rows>0){
				return 1;
			}else{
				return 0;
			}
		}

		function Establecer($EjActivo)
		{
			$myQuery = " UPDATE cont_config SET InicioEjercicio = '$EjActivo-01-01',FinEjercicio = '$EjActivo-12-31',EjercicioActual = $EjActivo";
			$companies = $this->query($myQuery);
		}

		//Proceso que cierra el ejercicio
		function CloseExercise($Id,$Ejercicio) 
		{
			//Busca si la poliza del periodo 13 ha sido generada
			$myQuery = "SELECT id FROM cont_polizas WHERE idejercicio = $Id AND idperiodo = 13 AND activo=1";
			$existePoliza = $this->query($myQuery);
			$poliza13 = mysqli_num_rows($existePoliza);

			if($poliza13)
			{

				//Crear nuevo periodo
				$anteriorCerrado = $this->anteriorCerrado($Ejercicio);//bandera, El ejercicio anterior ha sido cerrado
				if(intval($anteriorCerrado))
				{
					//Cerrar ejercicio
					$myQuery = "UPDATE cont_ejercicios SET Cerrado=1 WHERE Id=".$Id;
					$this->query($myQuery);
					
					$NuevoEjercicio = $Ejercicio+2;
					$myQuery = "INSERT INTO cont_ejercicios (NombreEjercicio,Cerrado) VALUES ($NuevoEjercicio,0)";
					$this->query($myQuery);
					$AgregaPolizaPDV = "UPDATE cont_polizas SET idejercicio = (SELECT Id FROM cont_ejercicios WHERE NombreEjercicio = $NuevoEjercicio) WHERE fecha BETWEEN '$NuevoEjercicio-01-01' AND '$NuevoEjercicio-12-31' AND pdv_aut = 1";
					$this->query($AgregaPolizaPDV);
				
					//Liberar Memoria
					unset($Id,$Ejercicio,$myQuery,$NuevoEjercicio);
				
					return 'Si';
				}
				else
				{
					return 'NoCerradoAnterior';
				}
			}
			else
			{
				return 'No';
			}
		}

		function anteriorCerrado($Ejercicio)
		{
			$myQuery = "SELECT Id FROM cont_ejercicios WHERE NombreEjercicio = $Ejercicio";
			$Actual = $this->query($myQuery);
			$Actual = $Actual->fetch_assoc();
			if(intval($Actual['Id']) > 1)
			{
				$Ejercicio = intval($Ejercicio)-1;
				$myQuery = "SELECT Cerrado FROM cont_ejercicios WHERE NombreEjercicio = $Ejercicio";
				$Existe = $this->query($myQuery);
				$Existe = $Existe->fetch_assoc();
				return $Existe['Cerrado'];
			}

			return '1';
		}
		function IsEmpty()
		{
			$myQuery = "SELECT Id FROM cont_ejercicios";
			$IsEmpty = $this->query($myQuery);
			$IE = mysqli_num_rows($IsEmpty);
			return $IE;
		}
			function FirstExercise($Ejercicio)
		{
			$myQuery = "INSERT INTO cont_ejercicios (NombreEjercicio,Cerrado) VALUES ($Ejercicio,0),(".(intval($Ejercicio)+1).",0);";
			$this->query($myQuery);
			$myQuery = "INSERT INTO cont_config (IdOrganizacion,TipoCatalogo,Estructura,TipoValores,TipoNiveles,NumPol,RFC,InicioEjercicio,FinEjercicio,TipoPeriodo,NumPeriodos,PeriodoActual,PeriodosAbiertos,EjercicioActual,PrimeraVez) 
						VALUES ( 1, 1, '999.99.999', 'n', 'm',0, '', '$Ejercicio-01-01', '$Ejercicio-12-31', 'm', 12, 1, 0, $Ejercicio,1)";
			$this->query($myQuery);
			$myQuery = "INSERT INTO cont_config_pdv (historial,conectar,polizas_por) 
						VALUES (0,0,0)";
			$this->query($myQuery);

			$segmentos = "INSERT INTO cont_segmentos(idSuc,nombre,Clave) VALUES(1,'Ninguno','00')";
			$this->query( $segmentos );

			//Agrega las polizas del punto de venta para este ejercicio
			$AgregaPolizaPDV = "UPDATE cont_polizas SET idejercicio = (SELECT Id FROM cont_ejercicios WHERE NombreEjercicio = $Ejercicio) WHERE fecha BETWEEN '$Ejercicio-01-01' AND '$Ejercicio-12-31' AND pdv_aut = 1";
			$this->query($AgregaPolizaPDV);

			//Crea un ejercicio extra posterior, al crearlo por primera vez
			//$Ejercicio = intval($Ejercicio)+1;
			//$myQuery = "INSERT INTO cont_ejercicios (NombreEjercicio,Cerrado) VALUES ($Ejercicio,0)";
			//$this->query($myQuery);

			
		}
		function getRFC()
		{
			$myQuery = "SELECT RFC FROM organizaciones";
			$rfc = $this->query($myQuery);
			$rfc = $rfc->fetch_assoc();
			return $rfc['RFC'];
		}
		
		// Inician Metodos de obtencion de cuentas afectables y tipo de cuenta
			function getAccounts($code)
			{
				$myQuery = "SELECT account_id, description, ".$code." FROM cont_accounts 
				WHERE status=1 AND removed=0 AND affectable=1 AND account_id NOT IN (select father_account_id FROM cont_accounts WHERE removed=0)";
				$ListaCuentas = $this->query($myQuery);
				return $ListaCuentas;	
			}
			// para sacar solo las cuentas de mayor
			function cuentasmayor(){
				$sql=$this->query("SELECT account_id,manual_code,description FROM cont_accounts 
				WHERE status=1 AND removed=0 AND affectable=0 AND main_account=1");
				return $sql;
			}

			function CuentaTipoCaptura()
			{
				$myQuery = "SELECT TipoNiveles FROM cont_config";
				$valor = $this->query($myQuery);
				$valor = $valor->fetch_assoc();
				if($valor['TipoNiveles'] == 'a') $resultado = 'account_code';
				if($valor['TipoNiveles'] == 'm') $resultado = 'manual_code';

				return $resultado;
			}
		// Terminan Metodos de obtencion de cuentas afectables y tipo de cuenta

		public function getNumAccounts()
		{
			$sql  = "SELECT ";
			$sql .= "	COUNT(*) ";
			$sql .= "FROM ";
			$sql .= "cont_accounts;";
			$result = $this->query( $sql );

			$result = $result->fetch_array( MYSQLI_NUM );
			return  $result[0];
		}

		function getConfigAccount($Account)
		{

			$myQuery = "SELECT $Account FROM cont_config WHERE id=1";
			$GetAccount = $this->query($myQuery);
			$GetAccount = $GetAccount->fetch_assoc();
			if($GetAccount[$Account] == '')
			{
				$GetAccount[$Account] = -1;
			}
			return $GetAccount[$Account];	
		}

		function updateAccount($Account,$NewAccount)
		{
			$myQuery = "UPDATE cont_movimientos SET Cuenta = $NewAccount WHERE Cuenta = $Account";
			$this->query($myQuery);
		}

		//Comienza funciones punto de venta
		function getExerciseInfoPDV()
		{
			$myQuery = "SELECT p.*, c.EjercicioActual FROM cont_config_pdv p INNER JOIN cont_config c ON c.id=p.id where p.id=1";
			$info = $this->query($myQuery);
			$inform = $info->fetch_assoc();
			return $inform;
		}

		function claves()
		{
			$myQuery = "SELECT CONCAT(Sector_Publico,'.',Sector_Financiero,'.',Sector_Economia1,'.',Sector_Economia2,'.',Entes_Publicos) AS Clave, Descripcion FROM pre_clasif_adm ORDER BY Clave";
			$i = $this->query($myQuery);
			return $i;
		}

		function saveConfigPDV($historial,$conectar,$corte,$anterior_corte,$ventas,$clientes,$IVA,$caja,$bancos)
		{
			$historial_c = (intval($anterior_corte)) ? '' : 'historial = '.$historial.',';
			$myQuery = "UPDATE cont_config_pdv SET $historial_c 
			conectar = $conectar, 
			polizas_por = $corte,
			ventas = $ventas,
			clientes = $clientes, 
			iva = $IVA,
			caja = -1,
			bancos = -1
			WHERE id = 1";
			$this->query($myQuery);
		}

		function saveHistoryPDV($ejercicio)
		{
			for($periodo=1;$periodo<=12;$periodo++)
			{
				$numpol = $this->ultimaNumpol($ejercicio,$periodo);
				if(is_null($numpol))
				{
					$numpol=0;
				}
				for($dia=1;$dia<=31;$dia++)
				{
					$ventas = $this->ventas($ejercicio,$periodo,$dia);
					if(intval($ventas->num_rows)>0)
					{
						$numpol++;
						$Poliza = $this->poliza($ejercicio,$periodo,$dia,$numpol);
						$consulta = '';
						$NumMovto = 1;
						while($v = $ventas->fetch_assoc())
						{
							$factura = "-";
							$archivo = "../facturas/".$v['Factura'].".xml";
							if(file_exists($archivo))
							{
								global $xp;
								$newdir = "xmls/facturas/$Poliza";
								mkdir($newdir, 0777);

								$file 	= $archivo;
								$texto 	= file_get_contents($file);
								$texto 	= preg_replace('{<Addenda.*/Addenda>}is', '<Addenda/>', $texto);
								$texto 	= preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '<cfdi:Addenda/>', $texto);
								$xml 	= new DOMDocument();
								$xml->loadXML($texto);
								
								$xp = new DOMXpath($xml);
								$data['uuid'] 	= $this->getpath("//@UUID");
								$data['folio'] 	= $this->getpath("//@folio");
								$data['emisor'] = $this->getpath("//@nombre");
								$factura = $data['folio']."_".$data['emisor'][0]."_".$data['uuid'].".xml";
								copy($archivo,$newdir."/".$factura);
							}
							if(floatval($v['montoMenosImpuestos'])>0)
								{
									$prodventas = $this->ventaProductos($v['idVenta']);
									while($pv = $prodventas->fetch_assoc())
									{
										if(intval($pv['idCuenta']) != 0)
										{
											$consulta .= "INSERT INTO cont_movimientos(IdPoliza, NumMovto, IdSegmento, IdSucursal, Cuenta, TipoMovto, Importe, Referencia, Concepto, Activo, FechaCreacion, Factura) 
													VALUES($Poliza,$NumMovto,1,".$v['idSucursal'].",".$pv['idCuenta'].",'Abono',".number_format($pv['SubTotal'],2,'.','').",'Ventas Linea ".$pv['nombre']."','Venta PDV(".$v['idVenta'].")',1,NOW(),'$factura'); ";//Guarda Ventas
										}
										else
										{
											$consulta .= "INSERT INTO cont_movimientos(IdPoliza, NumMovto, IdSegmento, IdSucursal, Cuenta, TipoMovto, Importe, Referencia, Concepto, Activo, FechaCreacion, Factura) 
													VALUES($Poliza,$NumMovto,1,".$v['idSucursal'].",(SELECT ventas FROM cont_config_pdv WHERE id=1),'Abono',".number_format($pv['SubTotal'],2,'.','').",'Ventas','Venta PDV(".$v['idVenta'].")',1,NOW(),'$factura'); ";//Guarda Ventas
										}
									}
									$NumMovto++;
								}
							/*if(floatval($v['montoMenosImpuestos'])>0)
							{
								$consulta .= "INSERT INTO cont_movimientos(IdPoliza, NumMovto, IdSegmento, IdSucursal, Cuenta, TipoMovto, Importe, Referencia, Concepto, Activo, FechaCreacion, Factura) 
												VALUES($Poliza,$NumMovto,1,".$v['idSucursal'].",(SELECT ventas FROM cont_config_pdv WHERE id=1),'Abono',".number_format($v['montoMenosImpuestos'],2,'.','').",'Ventas','Venta PDV(".$v['idVenta'].")',0,NOW(),'-'); ";//Guarda Ventas
								$NumMovto++;
							}*/
							if(floatval($v['montoimpuestos'])>0)
							{
								$consulta .= "INSERT INTO cont_movimientos(IdPoliza, NumMovto, IdSegmento, IdSucursal, Cuenta, TipoMovto, Importe, Referencia, Concepto, Activo, FechaCreacion, Factura) 
												VALUES($Poliza,$NumMovto,1,".$v['idSucursal'].",(SELECT iva FROM cont_config_pdv WHERE id=1),'Abono',".number_format($v['montoimpuestos'],2,'.','').",'IVA','Venta PDV(".$v['idVenta'].")',1,NOW(),'$factura'); ";//Guarda IVA
								$NumMovto++;
							}

							//INICIA Medios de pago----------------------------------------

							/*if(floatval($v['Efectivo'])>0)
							{
								$consulta .= "INSERT INTO cont_movimientos(IdPoliza, NumMovto,IdSegmento,IdSucursal,Cuenta,TipoMovto,Importe,Referencia,Concepto,Activo,FechaCreacion,Factura) 
												VALUES($Poliza,$NumMovto,1,".$v['idSucursal'].",(SELECT bancos FROM cont_config_pdv WHERE id=1),'Cargo',".number_format($v['Efectivo'],2,'.','').",'Bancos','Provision Venta PDV(".$v['idVenta'].")',0,NOW(),'-'); ";//Guarda Bancos
								$NumMovto++;
							}
							if(floatval($v['Credito'])>0)
							{
								$consulta .= "INSERT INTO cont_movimientos(IdPoliza, NumMovto,IdSegmento,IdSucursal,Cuenta,TipoMovto,Importe,Referencia,Concepto,Activo,FechaCreacion,Factura,Persona) 
												VALUES($Poliza,$NumMovto,1,".$v['idSucursal'].",(SELECT clientes FROM cont_config_pdv WHERE id=1),'Cargo',".number_format($v['Credito'],2,'.','').",'Cliente','Provision Venta PDV(".$v['idVenta'].")',0,NOW(),'-','1-".$v['idCliente']."'); ";//Guarda Cliente
								$NumMovto++;
							}*/
							if(intval($v['Cuenta'])>0)
								{
									$consulta .= "INSERT INTO cont_movimientos(IdPoliza, NumMovto,IdSegmento,IdSucursal,Cuenta,TipoMovto,Importe,Referencia,Concepto,Activo,FechaCreacion,Factura,Persona) 
													VALUES($Poliza,$NumMovto,1,".$v['idSucursal'].",".$v['Cuenta'].",'Cargo',".number_format($v['monto'],2,'.','').",'Cliente','Venta PDV(".$v['idVenta'].")',1,NOW(),'$factura','1-".$v['idCliente']."'); ";//Guarda Cliente
									$NumMovto++;
								}
								else
								{
									$consulta .= "INSERT INTO cont_movimientos(IdPoliza, NumMovto,IdSegmento,IdSucursal,Cuenta,TipoMovto,Importe,Referencia,Concepto,Activo,FechaCreacion,Factura,Persona) 
													VALUES($Poliza,$NumMovto,1,".$v['idSucursal'].",(SELECT clientes FROM cont_config_pdv WHERE id=1),'Cargo',".number_format($v['monto'],2,'.','').",'Cliente','Venta PDV(".$v['idVenta'].")',1,NOW(),'$factura','1-".$v['idCliente']."'); ";//Guarda Cliente
									$NumMovto++;	
								}

							//TERMINA Medios de pago----------------------------------------
							
							/*if(floatval($v['Caja'])>0)
							{
								$consulta .= "INSERT INTO cont_movimientos(IdPoliza, NumMovto,IdSegmento,IdSucursal,Cuenta,TipoMovto,Importe,Referencia,Concepto,Activo,FechaCreacion,Factura,Persona) 
												VALUES($Poliza,$NumMovto,1,".$v['idSucursal'].",(SELECT caja FROM cont_config_pdv WHERE id=1),'Abono',".number_format($v['Credito'],2,'.','').",'Cliente','Provision Venta PDV(".$v['idVenta'].")',0,NOW(),'-','1-".$v['idCliente']."'); ";//Guarda Cliente
								$NumMovto++;
							}*/
						}
						$this->dataTransact($consulta);
					}
				}
			}
		
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

		function ventas($ejercicio,$periodo,$dia)
		{
			$myQuery = "SELECT 
v.idVenta,
v.idCliente, 
(SELECT cuenta FROM comun_cliente WHERE id=v.idCliente) AS Cuenta,
@Efectivo := (SELECT SUM(monto) FROM venta_pagos WHERE idVenta = v.idVenta AND idFormaPago = 1) AS EfectivoAntes,
@Cambio := v.cambio AS Cambio,
@Resultado:=@Efectivo - @Cambio AS EfectivoMenosCambio,
(v.monto-v.montoimpuestos) AS montoMenosImpuestos, 
v.monto,
v.montoimpuestos,
IF (@Resultado<=0,0,@Resultado)  AS Efectivo,
(SELECT SUM(monto) FROM venta_pagos WHERE idVenta = v.idVenta AND idFormaPago = 2) AS Cheques,
(SELECT SUM(monto) FROM venta_pagos WHERE idVenta = v.idVenta AND idFormaPago = 3) AS TarjetaRegalo,
(SELECT SUM(monto) FROM venta_pagos WHERE idVenta = v.idVenta AND idFormaPago = 4) AS TarjetaCredito,
(SELECT SUM(monto) FROM venta_pagos WHERE idVenta = v.idVenta AND idFormaPago = 5) AS TarjetaDebito,
(SELECT SUM(monto) FROM venta_pagos WHERE idVenta = v.idVenta AND idFormaPago = 6) AS Credito,
(SELECT SUM(monto) FROM venta_pagos WHERE idVenta = v.idVenta AND idFormaPago = 7) AS Transferencia,
(SELECT SUM(monto) FROM venta_pagos WHERE idVenta = v.idVenta AND idFormaPago = 8) AS Spei,
(SELECT SUM(monto) FROM venta_pagos WHERE idVenta = v.idVenta AND idFormaPago = 9) AS Otro,
IF (@Resultado<0,@Resultado*-1,0)  AS Caja,
(SELECT folio FROM pvt_respuestaFacturacion WHERE idSale = v.idVenta LIMIT 1) AS Factura,
v.idSucursal,
v.fecha
FROM 	venta v 
WHERE 	fecha BETWEEN '$ejercicio-".sprintf('%02d', (intval($periodo)))."-".sprintf('%02d', (intval($dia)))." 00:00:00' AND '$ejercicio-".sprintf('%02d', (intval($periodo)))."-".sprintf('%02d', (intval($dia)))." 23:59:59' 
AND v.estatus=1;";
			$ventas = $this->query($myQuery);
			return $ventas;
		}

		function ventaProductos($idVenta)
		{
			$qry = "SELECT 
SUM(vp.subtotal) AS SubTotal,
l.idCuenta, 
l.nombre
FROM venta_producto vp
INNER JOIN mrp_producto p ON p.idProducto = vp.idProducto
INNER JOIN mrp_linea l ON l.idLin = p.idLinea
WHERE idVenta = $idVenta
GROUP BY idLin";
			$data = $this->query( $qry );
			return $data;
		}		

		function poliza($ejercicio,$periodo,$dia,$numpol)
		{
			$myQuery = "INSERT INTO cont_polizas(idorganizacion, idejercicio, idperiodo, numpol, idtipopoliza, referencia, concepto, cargos, abonos, ajuste, fecha, fecha_creacion, activo, eliminado, pdv_aut)
							 			VALUES(1,(SELECT Id FROM cont_ejercicios WHERE NombreEjercicio = $ejercicio),$periodo,".$numpol.",3,'','Provision Ventas PDV($dia/$periodo/$ejercicio)',0,0,0,'$ejercicio-".sprintf('%02d', (intval($periodo)))."-".sprintf('%02d', (intval($dia)))."',NOW(),0,0,1)";
			$idPoliza = $this->insert_id($myQuery);
			return $idPoliza;
		}

		function cuentasAfectables(){
				$sql=$this->query("SELECT account_id,manual_code,description FROM cont_accounts 
				WHERE status=1 AND removed=0 AND affectable=1 AND main_account=3");
				return $sql;
			}
			function ultimaNumpol($ejercicio,$periodo)
			{
				$myQuery = "SELECT numpol FROM cont_polizas WHERE idperiodo=$periodo AND idejercicio = (SELECT Id FROM cont_ejercicios WHERE NombreEjercicio = '$ejercicio') ORDER BY numpol DESC LIMIT 1";
				$numpol = $this->query($myQuery);
				$numpol = $numpol->fetch_assoc();
				return $numpol['numpol'];
			}

			function passAdmin($Pass)
			{
				include('../../netwarelog/webconfig.php');
				$strPwd = $Pass;
				$strPwd = crypt($strPwd,$accelog_salt);

				$strResult = "NOF";

				$strSql = "SELECT * FROM accelog_usuarios WHERE idempleado = 2 AND clave = '" . $strPwd . "';";
				//$strSql = "SELECT * FROM accelog_usuarios;";
				$rstPwd = $this->query($strSql);
				if($rstPwd->num_rows>0)
				{
    				$strResult = "OK";
				}
				return $strResult;
			}

			function ReiniciarContabilidad($conservar)
			{
				
				//$fecha = date(strtotime('-7 hours',strtotime(date('Y_m_d_H_i_s'))));
		
				$fecha = date('Y_m_d_H_i_s',strtotime('-7 hour',strtotime(date('Y-m-d H:i:s'))));
				$bancos = $this->validaBancos();
				//Si no desea conservar el arbol se añadira al reinicio de la contabilidad
				if ($conservar == 0) {
					$myQuery = "CREATE TABLE cont_accounts_res_$fecha LIKE cont_accounts;
					INSERT cont_accounts_res_$fecha SELECT* FROM cont_accounts;
					TRUNCATE TABLE cont_accounts;
					";
				} else {
					$myQuery = " ";
				}
				$myQuery .= "
				CREATE TABLE cont_config_res_$fecha LIKE cont_config;
				INSERT cont_config_res_$fecha SELECT* FROM cont_config;
				TRUNCATE TABLE cont_config; 

				CREATE TABLE cont_config_pdv_res_$fecha LIKE cont_config_pdv;
				INSERT cont_config_pdv_res_$fecha SELECT* FROM cont_config_pdv;
				TRUNCATE TABLE cont_config_pdv; 

				CREATE TABLE cont_ejercicios_res_$fecha LIKE cont_ejercicios;
				INSERT cont_ejercicios_res_$fecha SELECT* FROM cont_ejercicios;
				TRUNCATE TABLE cont_ejercicios; 

				CREATE TABLE cont_movimientos_res_$fecha LIKE cont_movimientos;
				INSERT cont_movimientos_res_$fecha SELECT* FROM cont_movimientos;
				TRUNCATE TABLE cont_movimientos; 

				CREATE TABLE cont_polizas_res_$fecha LIKE cont_polizas;
				INSERT cont_polizas_res_$fecha SELECT* FROM cont_polizas;
				TRUNCATE TABLE cont_polizas; 

				CREATE TABLE cont_rel_desglose_iva_res_$fecha LIKE cont_rel_desglose_iva;
				INSERT cont_rel_desglose_iva_res_$fecha SELECT* FROM cont_rel_desglose_iva;
				TRUNCATE TABLE cont_rel_desglose_iva; 

				CREATE TABLE cont_rel_pol_prov_res_$fecha LIKE cont_rel_pol_prov;
				INSERT cont_rel_pol_prov_res_$fecha SELECT* FROM cont_rel_pol_prov;
				TRUNCATE TABLE cont_rel_pol_prov;

				CREATE TABLE cont_segmentos_res_$fecha LIKE cont_segmentos;
				INSERT cont_segmentos_res_$fecha SELECT* FROM cont_segmentos;
				TRUNCATE TABLE cont_segmentos;

				CREATE TABLE bco_cuentas_bancarias_res_$fecha LIKE bco_cuentas_bancarias;
				INSERT bco_cuentas_bancarias_res_$fecha SELECT* FROM bco_cuentas_bancarias;
				TRUNCATE TABLE bco_cuentas_bancarias;

				CREATE TABLE cont_presupuestos_res_$fecha LIKE cont_presupuestos;
				INSERT cont_presupuestos_res_$fecha SELECT* FROM cont_presupuestos;
				TRUNCATE TABLE cont_presupuestos;
				
				CREATE TABLE cont_resumen_ivas_retenidos_res_$fecha LIKE cont_resumen_ivas_retenidos;
				INSERT cont_resumen_ivas_retenidos_res_$fecha SELECT* FROM cont_resumen_ivas_retenidos;
				TRUNCATE TABLE cont_resumen_ivas_retenidos;

				CREATE TABLE cont_grupo_facturas_res_$fecha LIKE cont_grupo_facturas;
				INSERT cont_grupo_facturas_res_$fecha SELECT* FROM cont_grupo_facturas;
				TRUNCATE TABLE cont_grupo_facturas;

				CREATE TABLE cont_facturas_res_$fecha LIKE cont_facturas;
				INSERT cont_facturas_res_$fecha SELECT* FROM cont_facturas;
				TRUNCATE TABLE cont_facturas;

				CREATE TABLE cont_facturas_relacion_res_$fecha LIKE cont_facturas_relacion;
				INSERT cont_facturas_relacion_res_$fecha SELECT* FROM cont_facturas_relacion;
				TRUNCATE TABLE cont_facturas_relacion;
				";

				
				if($bancos==1){
			
					$myQuery.="	CREATE TABLE bco_controlNumeroCheque_res_$fecha LIKE bco_controlNumeroCheque;
					INSERT bco_controlNumeroCheque_res_$fecha SELECT* FROM bco_controlNumeroCheque;
					TRUNCATE TABLE bco_controlNumeroCheque;
					
					CREATE TABLE bco_documentos_res_$fecha LIKE bco_documentos;
					INSERT bco_documentos_res_$fecha SELECT* FROM bco_documentos;
					TRUNCATE TABLE bco_documentos;
					
					CREATE TABLE bco_devoluciones_res_$fecha LIKE bco_devoluciones;
					INSERT bco_devoluciones_res_$fecha SELECT* FROM bco_devoluciones;
					TRUNCATE TABLE bco_devoluciones;
					
					CREATE TABLE bco_documentoSubcategorias_res_$fecha LIKE bco_documentoSubcategorias;
					INSERT bco_documentoSubcategorias_res_$fecha SELECT* FROM bco_documentoSubcategorias;
					TRUNCATE TABLE bco_documentoSubcategorias;
					
					CREATE TABLE bco_ingresos_depositos_res_$fecha LIKE bco_ingresos_depositos;
					INSERT bco_ingresos_depositos_res_$fecha SELECT* FROM bco_ingresos_depositos;
					TRUNCATE TABLE bco_ingresos_depositos;
					
					CREATE TABLE bco_saldo_bancario_res_$fecha LIKE bco_saldo_bancario;
					INSERT bco_saldo_bancario_res_$fecha SELECT* FROM bco_saldo_bancario;
					TRUNCATE TABLE bco_saldo_bancario;
					
					CREATE TABLE bco_saldos_conciliacion_res_$fecha LIKE bco_saldos_conciliacion;
					INSERT bco_saldos_conciliacion_res_$fecha SELECT* FROM bco_saldos_conciliacion;
					TRUNCATE TABLE bco_saldos_conciliacion;
					
					CREATE TABLE bco_saldos_conciliacionBancos_res_$fecha LIKE bco_saldos_conciliacionBancos;
					INSERT bco_saldos_conciliacionBancos_res_$fecha SELECT* FROM bco_saldos_conciliacionBancos;
					TRUNCATE TABLE bco_saldos_conciliacionBancos;
					";
				}
				$myQuery.="CREATE TABLE cont_tasaPrv_res_$fecha LIKE cont_tasaPrv;
				INSERT cont_tasaPrv_res_$fecha SELECT* FROM cont_tasaPrv;
				DELETE FROM cont_tasaPrv WHERE idPrv IN (SELECT idPrv FROM mrp_proveedor WHERE ImOtSis = 1);

				CREATE TABLE mrp_proveedor_res_$fecha LIKE mrp_proveedor;
				INSERT mrp_proveedor_res_$fecha SELECT* FROM mrp_proveedor;
				DELETE FROM mrp_proveedor WHERE ImOtSis = 1;

				CREATE TABLE cont_bancosPrv_res_$fecha LIKE cont_bancosPrv;
				INSERT cont_bancosPrv_res_$fecha SELECT* FROM cont_bancosPrv;
				DELETE FROM cont_bancosPrv WHERE ImOtSis = 1;

				UPDATE mrp_proveedor SET cuenta = 0;
				UPDATE comun_cliente SET cuenta = 0;

				UPDATE nomi_percepciones SET account_id = -1;
				UPDATE nomi_deducciones SET account_id = -1;
				UPDATE nomi_otros_pagos SET account_id = -1;
				";
				
				
				$this->transaccion('Reinicia contabilidad desde config',"Borra todo");
				$this->multi_query($myQuery);
				return $conservar." ".$myQuery;
				//return 1;
			}
			function ReiniciarBancos()
			{
				
				//$fecha = date(strtotime('-7 hours',strtotime(date('Y_m_d_H_i_s'))));
		
				$fecha = date('Y_m_d_H_i_s',strtotime('-7 hour',strtotime(date('Y-m-d H:i:s'))));

				$myQuery = "
				CREATE TABLE bco_clasificador_res_$fecha LIKE bco_clasificador;
				INSERT bco_clasificador_res_$fecha SELECT* FROM bco_clasificador;
				TRUNCATE TABLE bco_clasificador; 

				CREATE TABLE bco_tiposDocumentoConcepto_res_$fecha LIKE bco_tiposDocumentoConcepto;
				INSERT bco_tiposDocumentoConcepto_res_$fecha SELECT* FROM bco_tiposDocumentoConcepto;
				TRUNCATE TABLE bco_tiposDocumentoConcepto; 
				
				CREATE TABLE bco_conceptos_res_$fecha LIKE bco_conceptos;
				INSERT bco_conceptos_res_$fecha SELECT* FROM bco_conceptos;
				TRUNCATE TABLE bco_conceptos; 

				CREATE TABLE bco_configuracion_res_$fecha LIKE bco_configuracion;
				INSERT bco_configuracion_res_$fecha SELECT* FROM bco_configuracion;
				TRUNCATE TABLE bco_configuracion; 

				CREATE TABLE bco_impuestos_retencion_res_$fecha LIKE bco_impuestos_retencion;
				INSERT bco_impuestos_retencion_res_$fecha SELECT* FROM bco_impuestos_retencion;
				TRUNCATE TABLE bco_impuestos_retencion; 

				CREATE TABLE bco_pendiente_timbrar_res_$fecha LIKE bco_pendiente_timbrar;
				INSERT bco_pendiente_timbrar_res_$fecha SELECT* FROM bco_pendiente_timbrar;
				TRUNCATE TABLE bco_pendiente_timbrar; 

				CREATE TABLE  bco_sucursalBancaria_res_$fecha LIKE bco_sucursalBancaria;
				INSERT bco_sucursalBancaria_res_$fecha SELECT* FROM bco_sucursalBancaria;
				TRUNCATE TABLE bco_sucursalBancaria; 
				
				INSERT IGNORE INTO `bco_tiposDocumentoConcepto` (`idTipoDoc`, `nombre`, `fecha`, `id`, `idstatus`)
				VALUES
				  (1, 'Traspaso de Cheque', '2016-01-05', 4, 1),
				  (2, 'Devolucion de Cheque', NULL, 2, 1),
				  (3, 'Traspaso de Cheque', '2016-01-05', 3, 1);
				  
				 INSERT IGNORE INTO `bco_clasificador` (`id`, `codigo`, `nombreclasificador`, `coin_id`, `idtipo`, `account_id`, `idplantilla`, `idNivel`, `cuentapadre`, `activo`)
					VALUES
					  (1, '', 'INGRESOS', 1, 1, -1, 0, 2, 1, -1),
					  (2, '', 'Traspaso', 1, 1, -1, 0, 1, 1, -1),
					  (3, '', 'EGRESOS', 1, 2, -1, 0, 2, -1, -1),
					  (4, '', 'Traspaso', 1, 2, -1, 0, 1, 3, -1),
					  (5, NULL, 'Devolucion de Cheque', 1, 1, -1, 0, 1, 1, -1),
					  (6, '', 'Ingresos', 1, 1, -1, 0, 1, 1, -1),
					  (7, '', 'Egresos', 1, 2, -1, 0, 1, 3, -1);
				 
				";
				$this->transaccion('Reinicia Bancos desde config',"Borra todo");
				$this->multi_query($myQuery);
				
				return 1;
			}

			function ReiniciarContabilidad2($n)
			{
				
				if(intval($n))
				{
					$myQuery = "DELETE FROM cont_accounts WHERE removed=9";
					$this->query($myQuery);
				}
				else
				{
					$myQuery = "
				TRUNCATE TABLE cont_accounts; 
				TRUNCATE TABLE cont_config_pdv; 
				TRUNCATE TABLE cont_movimientos; 
				TRUNCATE TABLE cont_polizas; 
				TRUNCATE TABLE cont_rel_desglose_iva; 
				TRUNCATE TABLE cont_rel_pol_prov;
				TRUNCATE TABLE cont_segmentos;
				TRUNCATE TABLE bco_cuentas_bancarias;
				DELETE FROM cont_tasaPrv WHERE idPrv IN (SELECT idPrv FROM mrp_proveedor WHERE ImOtSis = 1);
				DELETE FROM mrp_proveedor WHERE ImOtSis = 1;
				DELETE FROM cont_bancosPrv WHERE ImOtSis = 1;
				UPDATE cont_config SET PrimeraVez = 1 WHERE id=1;
				";
				$this->transaccion('Reinicia contabilidad automatico por estar mal layout',$myQuery);
				$this->dataTransact($myQuery);
				//$this->multi_query($myQuery);
				}
			}


		//Termina funciones punto de venta
		function circulante(){
			$sql=$this->query("select * from cont_accounts where  (account_code like '1.%' || account_code like '2.%') and affectable=1 ");
			return $sql;

		}
		function CuentaGasto(){//resultado deudoras aplica tambien para percepciones de nomina
			$sql=$this->query("select * from cont_accounts where account_code like '4.2%' and affectable=1 ");
			return $sql;

		}
		function pasivoCirculante(){//para las deducciones nomina
			$sql=$this->query("select * from cont_accounts where   account_code like '2.1%' and affectable=1 ");
			return $sql;
		}
		function percepcionesNomina(){
			$sql=$this->query("select * from nomi_percepciones where idAgrupador!=14");//esta dif 14 porq es otros y a ese debe especificar a cual se va manual porq otros abarca varias cosas
			return $sql;
		}
		function deduccionesNomina(){
			$sql=$this->query("select * from nomi_deducciones where idAgrupador!=4");//esta dif 4 porq es otros y a ese debe especificar a cual se va manual porq otros abarca varias cosas
			return $sql;
		}
		function otrospagosNomina(){
			$sql=$this->query("select * from nomi_otros_pagos ");
			return $sql;
		}
		function deduccionesPercepcionesNominaCreadas($tabla){
			$sql=$this->query("select p.*,concat ( c.description ,'(', c.manual_code, ')') description from $tabla p, cont_accounts c where p.account_id!=-1 and c.account_id=p.account_id");
			return $sql;
		}
		function insertPercepcionesDeducciones($idagrupador,$idcuenta,$tabla){
			if($idagrupador !=-1 ){
				$sql = $this->query("update $tabla set account_id=".$idcuenta." where idAgrupador=".$idagrupador);
			}
		}
		// function updateOtraSinCuenta($ids,$tabla){
			// $sql = $this->query("update $tabla set account_id=-1 where idAgrupador not in ($ids)");
		// }
		function buscaDeduccionPercepcion($tabla,$clave){
			$sql=$this->query("select * from $tabla where clave='$clave'");
			return $sql->fetch_assoc();
		}

		function cuentasSinMayor($n)
		{	
			$where = '';
			if(intval($n))
				{
					$where = 'AND removed=9';
				}
			$myQuery = "SELECT manual_code,description FROM cont_accounts WHERE main_father = 0 AND main_account = 3 $where";
			return $this->query($myQuery);
		}
		function Estructura()
		{
			$myQuery = "SELECT Estructura FROM cont_config WHERE id=1";
			$res = $this->query($myQuery);
			$res = $res->fetch_assoc();
			return $res['Estructura'];
		}

		function quitar9removed()
		{
			$myQuery = "UPDATE cont_accounts SET removed=0 WHERE removed = 9";
			$this->query($myQuery);
		}

		function hayCuentasRepetidas()
		{
			$myQuery = "SELECT manual_code,description FROM cont_accounts WHERE removed = 0 OR removed = 9 GROUP BY manual_code HAVING COUNT(manual_code) > 1";
			$res = $this->query($myQuery);
			return $res;
		}

		function tipoinstancia()
	 {
	 	$myQuery = "SELECT tipoinstancia FROM organizaciones WHERE idorganizacion=1;";
	 	$id = $this->query($myQuery);
		$id = $id->fetch_assoc();
		return $id['tipoinstancia'];
	 }
	 
	 function validaBancos(){
		$sql = $this->query("select * from accelog_perfiles_me where idmenu=1932");
		if($sql->num_rows>0){
			return 1;
		}else{
			return 0;
		}
	}

	function reiniciar_ejer()
	{
		$myQuery = "TRUNCATE TABLE cont_ejercicios; ";
		$myQuery .= "TRUNCATE TABLE cont_config;";
		$this->multi_query($myQuery);
	}

	function primera_vez()
	{
		$primera_vez = 0;
		$p = $this->getExerciseInfo();
		if(intval($p['PrimeraVez']))//Si es la primera vez y no se ha configurado ejercicio
			if(!$this->tiene_polizas())//Si no tiene polizas
				$primera_vez = 1;
		
		return $primera_vez;	
	}

	function tiene_polizas()
	{
		$t = $this->query("SELECT id FROM cont_polizas");
		$t = $t->num_rows;
		return $t;
	}

}
?>