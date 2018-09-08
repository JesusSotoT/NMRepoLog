<?php
ini_set("display_errors",0);
ini_set('memory_limit', '-1');
//error_reporting(E_WARNING);

?>
<!DOCTYPE html>
<html>
<head>
		<meta charset="UTF-8">
</head>
<body>
<?php

require(dirname(__FILE__)."/../../../netwarelog/webconfig.php");

$objCon = mysqli_connect($servidor,$usuariobd,$clavebd,$bd);
$borrabdimport ='SELECT CONCAT("DROP TABLE ", GROUP_CONCAT(table_name), ";") FROM information_schema.tables
WHERE table_schema = "'.$bd.'" AND table_name LIKE "cont_import%";';
$sql= mysqli_fetch_row(mysqli_query($objCon,$borrabdimport));
mysqli_query($objCon,$sql[0]);
 
if ($_FILES['CONTPAC']['name'][0]){
$strTBName = "cont_import_" . date("YmdHis");
createTable($objCon,$strTBName);

require_once dirname(__FILE__).'/Excel/reader.php';

$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->read(dirname(__FILE__).'/cuentas.xls');

$cont = 0;
for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {

		$strFld00 = trim($data->sheets[0]["cells"][$i][1]); //A Tipo registro
		$strFld01 = trim($data->sheets[0]["cells"][$i][2]); //B Cuenta
		$strFld02 = trim($data->sheets[0]["cells"][$i][3]); //C Descripcion ESP
		$strFld03 = trim($data->sheets[0]["cells"][$i][4]); //D Descripcion ENG
		$strFld04 = trim($data->sheets[0]["cells"][$i][5]); //E Padre
		$strFld05 = trim($data->sheets[0]["cells"][$i][6]); //F Tipo Cuenta
		$strFld06 = trim($data->sheets[0]["cells"][$i][7]); //G Status
		$strFld07 = trim($data->sheets[0]["cells"][$i][8]); //H Mayor, Subcuenta, Argupadora
		$strFld09 = trim($data->sheets[0]["cells"][$i][9]); //I Fecha creacion
		$strFld10 = '11'; //11???????
		$strFld11 = trim($data->sheets[0]["cells"][$i][10]); //J Digito Agrupador SAT
		$strFld12 = trim($data->sheets[0]["cells"][$i][11]); //K Moneda
		$strFld13 = trim($data->sheets[0]["cells"][$i][12]); //????

		//quitar caracteres extraños.
		$strFld01 = preg_replace('([^A-Za-z0-9])', '', $strFld01);
		$strFld04 = preg_replace('([^A-Za-z0-9])', '', $strFld04);
		$strFld02 = str_replace("'", "", $strFld02);
		$strFld02 = str_replace('"', "", $strFld02);
		$strFld03 = str_replace("'", "", $strFld03);
		$strFld03 = str_replace('"', "", $strFld03);
		//$strFld11 = preg_replace('([^A-Za-z0-9])', '', $strFld11);

		if($strFld00=="C"){
				$strSql = "INSERT INTO " . $strTBName . "(Field01,Field02,Field03,Field04,Field05,Field06,Field07,Field08,Field09,Field10,Field11,Field12,Field13,Field14) ";
				$strSql .= "VALUES ('" . $strFld01 . "','" . $strFld02 . "','" . $strFld03 . "','" . $strFld04 . "','"  . $strFld05 . "','" . $strFld06 . "','" . $strFld07 . "','" . $strFld08 . "','" . $strFld09 . "','" . $strFld10 . "','" . $strFld11 . "','" . $strFld12 . "','" . $strFld13 . "','" . $strFld14 . "');";
				mysqli_query($objCon,$strSql);
		}

};

unset($arrRows);
unset($objFile);

$strSql = "DELETE FROM cont_accounts;";
mysqli_query($objCon,$strSql);
$strSql = "ALTER TABLE cont_accounts AUTO_INCREMENT = 1;";
mysqli_query($objCon,$strSql);

$strSql = "SELECT * FROM " . $strTBName . " WHERE Field04 = 0 ORDER BY Field01";
$rstAccounts = mysqli_query($objCon, $strSql);
$noCumple = 1;//No cumple con la cantidad minima de cuentas
$numRows = mysqli_num_rows($rstAccounts);
if($numRows >= 4)
{
		$noCumple = 0;

		while ($objAccounts = mysqli_fetch_row($rstAccounts)){
				$strAccountCode = getAccountId($objAccounts[5]);
				$strManualCode = maskAccount($objAccounts[1],$strMask,$strSeparator);
				$strDescription = $objAccounts[2];
				$strSec_Desc = $objAccounts[3];
				$strAccount_Type = $strAccountCode;
				$strStatus = $objAccounts[6];
				$strMain_Account = 2;
				$strCash_Flow = 0;
				$strReg_Date = substr($objAccounts[9],0,4) . "-" . substr($objAccounts[9],4,2) . "-" . substr($objAccounts[9],6,2);
				$strCurrency_Id = currencyId($objAccounts[12],$objCon);
				$strGroup_Dig = 0;
				$strId_Sucursal = 0;
				$strSeg_Neg_Mov = 0;
				$strAffectable = 0;
				$strMod_Date = date("Y-m-d");
				$strFather_Account_Id = 0;
				$strRemovable = 0;
				$strAccount_Nature = getAccountNature($objAccounts[5]);
				$strRemoved = 0;
				$strMain_Father = 0;
				$strCuentaOficial = diarioOficialId($objAccounts[11],$objCon);

				$strSql = "INSERT INTO cont_accounts ";
				$strSql .= "VALUES(0,";
				$strSql .= "'" . $strAccountCode . "',";
				$strSql .= "'" . $strManualCode . "',";
				$strSql .= "'" . $strDescription . "',";
				$strSql .= "'" . $strSec_Desc . "',";
				$strSql .= "" . $strAccount_Type . ",";
				$strSql .= "" . $strStatus . ",";
				$strSql .= "" . $strMain_Account . ",";
				$strSql .= "" . $strCash_Flow . ",";
				$strSql .= "'" . $strReg_Date . "',";
				$strSql .= "" . $strCurrency_Id . ",";
				$strSql .= "" . $strGroup_Dig . ",";
				$strSql .= "" . $strId_Sucursal . ",";
				$strSql .= "" . $strSeg_Neg_Mov . ",";
				$strSql .= "" . $strAffectable . ",";
				$strSql .= "'" . $strMod_Date . "',";
				$strSql .= "" . $strFather_Account_Id . ",";
				$strSql .= "" . $strRemovable . ",";
				$strSql .= "" . $strAccount_Nature . ",";
				$strSql .= "" . $strRemoved . ",";
				$strSql .= "" . $strMain_Father . ",";
				$strSql .= "'" . $strCuentaOficial . "',0);";

				mysqli_query($objCon,$strSql);
				getSons($objAccounts[1], $strAccountCode . ".",$strAccount_Type,$strTBName,$strMask,$strSeparator,$objCon);
		};

		mysqli_free_result($rstAccounts);
		unset($rstAccounts);

		$strSql = "DROP TABLE " . $strTBName . ";";
		mysqli_query($objCon,$strSql);

		$strSql = "UPDATE cont_config SET TipoNiveles = 'm'";
		mysqli_query($objCon,$strSql);

		unlink(dirname(__FILE__).'/cuentas.xls');
		}
		//######
		//IMPORTAR POLIZAS

		if($_FILES['CONTPAC']['name'][1]){
		require_once dirname(__FILE__).'/Excel/reader.php';


		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('CP1251');
		$data->read(dirname(__FILE__).'/polizas.xls');

		$arrRows = array();

		$strSql = " TRUNCATE TABLE cont_polizas;";
		mysqli_query($objCon,$strSql);

		$strSql = " TRUNCATE TABLE cont_movimientos;";
		mysqli_query($objCon,$strSql);
		$contPoliza = 1;
		$intPeriodoPrev = 0;


		 //INICIA INSERCION DE  PROVEEDORES//////////////////

				for ($i = 2; $i <= intval($data->sheets[1]['numRows']); $i++){
					//$strProv01 = $data->sheets[1]["cells"][$i][1];//Id Proveedor
					$strProv02 = trim($data->sheets[1]["cells"][$i][1]);//Razon Social
					$strProv03 = trim($data->sheets[1]["cells"][$i][2]);//RFC
					$strProv04 = trim($data->sheets[1]["cells"][$i][3]);//Curp
					$strProv05 = trim($data->sheets[1]["cells"][$i][4]);//id tipo tercero
					$strProv06 = trim($data->sheets[1]["cells"][$i][5]);//id tipo operacion
					$strProv07 = trim($data->sheets[1]["cells"][$i][6]);//cuenta
					$strProv08 = trim($data->sheets[1]["cells"][$i][7]);//id fiscal
					$strProv09 = trim($data->sheets[1]["cells"][$i][8]);//nombre extranjero
					$strProv10 = trim($data->sheets[1]["cells"][$i][9]);//nacionalidad
					$strProv11 = 0;
					if(trim($data->sheets[1]["cells"][$i][10] != "")) $strProv11 = trim($data->sheets[1]["cells"][$i][10]);//iva retenido
					$strProv12 = 0;
					if(trim($data->sheets[1]["cells"][$i][11] != "")) $strProv12 = trim($data->sheets[1]["cells"][$i][11]);//isr retenido
					$strProv13 = trim($data->sheets[1]["cells"][$i][12]);//tasas
					$strProv14 = 0;
					if(trim($data->sheets[1]["cells"][$i][13] != "")) $strProv14 = trim($data->sheets[1]["cells"][$i][13]);//id tipo iva
					$strProv15 = 0;
					if(trim($data->sheets[1]["cells"][$i][14] != "")) $strProv15 = trim($data->sheets[1]["cells"][$i][14]);//id ietu
					$strProv16 = trim($data->sheets[1]["cells"][$i][15]);//id banco
					$strProv17 = trim($data->sheets[1]["cells"][$i][16]);//cuenta

					//Si el campo id banco no esta vacio, verifica si tiene varios valores o solo 1, si tiene varios lo convierte en un array
					$existe = 0;
					if(trim($strProv16 != ""))
					{
							if(stristr($strProv16, ',') === FALSE) 
							{
									$bancoVarios = 0;
							}
							else
							{
									$bancoVarios = 1;
									$strProv16 = explode(',',$strProv16);
							}
							$existe++;
					}
					
					//Si el campo cuenta no esta vacio, verifica si tiene varios valores o solo 1, si tiene varios lo convierte en un array
					if($strProv17 != "")
					{
							if(stristr($strProv17, ',') === FALSE) 
							{
									$cuentaVarios = 0;
							}
							else   
							{
									$cuentaVarios = 1;
									$strProv17 = explode(',',$strProv17);
							}
							$existe++;  
					}

					 $strProv13 = explode('_',$strProv13);

					if($strProv07 != "")
					{
							//Busca el Id de la cuenta
							$strSql = "SELECT account_id FROM cont_accounts WHERE manual_code = '" . maskAccount($strProv07,$strMask, $strSeparator) . "';";
							$rstAccId = mysqli_query($objCon, $strSql);
							while($objAccId = mysqli_fetch_row($rstAccId))
							{
									$strProv07 = $objAccId[0];
							}
					}
					else
					{
							$strProv07 = 0;
					}

					if($strProv10 == 'mex' || $strProv10 == 'Mex' || $strProv10 == 'MEX') $strProv10 = 'Mexicana';

					$strSql = "
					INSERT INTO mrp_proveedor(razon_social, rfc, curp, idtipotercero, idtipoperacion, cuenta, numidfiscal, nombrextranjero, nacionalidad, ivaretenido, isretenido, idTasaPrvasumir, idtipoiva, idIETU, cuentacliente, idestado, idmunicipio, idtipo, beneficiario_pagador, status, saldo) 
					VALUES('$strProv02', '$strProv03', '$strProv04', $strProv05, $strProv06, $strProv07,'$strProv08', '$strProv09', '$strProv10', $strProv11, $strProv12, $strProv13, $strProv14, $strProv15, $strProv17, 1,1,0,0,-1,0)";
					mysqli_query($objCon,$strSql);

					$strSql = '';

					//Inserta los impuestos correspondientes al proveedor
					$strProv01 = buscaIdProv($strProv03,$objCon);

					if($strProv13[1] == '*')
					{
							$strSql = "INSERT INTO cont_tasaPrv VALUES(0,$strProv01,'16%',16,1),(0,$strProv01,'11%',11,1),(0,$strProv01,'0%',0,1),(0,$strProv01,'Exenta',0,1),(0,$strProv01,'15%',15,1),(0,$strProv01,'10%',10,1);";
							mysqli_multi_query($objCon,$strSql);
					}
					else
					{
							for($a = 0;$a<=count($strProv13)-1;$a++)
							{
									if($strProv13[$a] != 'e' || $strProv13[$a] != 'E')
									{
											$title  = $strProv13[$a]."%";
											$numero = intval($strProv13[$a]);
									}
									else
									{
											$title  = 'Exenta';
											$numero = 0;
									}
									if($strProv13[$a] != '')
									{
											$strSql = "INSERT INTO cont_tasaPrv VALUES(0,$strProv01,'$title',$numero,1);";
											mysqli_query($objCon,$strSql);
									}
									
							}        
					}

					$strSql = "UPDATE mrp_proveedor SET idTasaPrvasumir = ".buscaTasa($objCon,$strProv13[0],$strProv01)." WHERE idPrv = $strProv01;";

					mysqli_query($objCon,$strSql);
					$strSql = '';

					//Guarda los valores referentes a la cuenta y el id del banco del proveedor
					//Si existen multiples bancos y cuentas recorrera el array y relacionara bancos y cuentas por posicion en el puntero
					if($existe == 2)
					{
							if($bancoVarios && $cuentaVarios)
							{
									$insertar = '';
									for($a=0;$a<=count($strProv16)-1;$a++)
									{
											if($a!=0) $insertar .= ",";
											$insertar .= "(0,".$strProv16[$a].",$strProv01,'".$strProv17[$a]."',1)";
									}
									
									mysqli_multi_query($objCon,"INSERT INTO cont_bancosPrv VALUES".$insertar.";");
							}//Si solo existe un banco y una cuenta los gardara asi.
							elseif(!$bancoVarios && !$cuentaVarios)
							{
									mysqli_query($objCon,"INSERT INTO cont_bancosPrv VALUES(0,$strProv16,$strProv01,'$strProv17',1)");
							}
					}
				}
				//TERMINA INSERCION DE PROVEEDORES//////////////////

				//INICIA INSERCION DE SEGMENTOS//////////////////
				for ($i = 2; $i <= intval($data->sheets[2]['numRows']); $i++)
				{
						$strSeg01 = trim($data->sheets[2]["cells"][$i][1]);//Clave del Segmento
						$strSeg02 = trim($data->sheets[2]["cells"][$i][2]);//Nombre del Segmento

						$strSql = "INSERT INTO cont_segmentos VALUES(0,'$strSeg02','$strSeg01',-1)";
						mysqli_query($objCon,$strSql);
						$strSql = '';
				}

				//TERMINA INSERCION DE SEGMENTOS//////////////////

				//INICIA INSERCION DE CUENTAS BANCARIAS//////////////////
				for ($i = 2; $i <= intval($data->sheets[3]['numRows']); $i++)
				{
						$strCB01 = trim($data->sheets[3]["cells"][$i][1]);//ID CUENTA
						$strCB02 = trim($data->sheets[3]["cells"][$i][2]);//NUMERO DE CUENTA BANCARIA
						$strCB03 = trim($data->sheets[3]["cells"][$i][3]);//ID SAT BANCO
						$strCB04 = trim($data->sheets[3]["cells"][$i][4]);//TIPO DE CUENTA
						$strCB05 = trim($data->sheets[3]["cells"][$i][5]);//MONEDA
						$strCB06 = trim($data->sheets[3]["cells"][$i][6]);//TITULAR DE LA CUENTA
						$strCB07 = trim($data->sheets[3]["cells"][$i][7]);//CUENTA CONTABLE
						$strCB08 = trim($data->sheets[3]["cells"][$i][8]);//FECHA DE REGISTRO

						$strSql = "SELECT account_id FROM cont_accounts WHERE manual_code = '" . maskAccount($strCB07,$strMask, $strSeparator) . "';";
										$rstAccId = mysqli_query($objCon, $strSql);
										while($objAccId = mysqli_fetch_row($rstAccId))
										{
												$strCB07 = $objAccId[0];
										}

						$strSql = "INSERT INTO bco_cuentas_bancarias VALUES($strCB01,'$strCB02',(SELECT idbanco FROM cont_bancos WHERE Clave = '$strCB03'),$strCB04,(SELECT coin_id FROM cont_coin WHERE codigo = '$strCB05'),'$strCB06',$strCB07,'$strCB08',0)";
						mysqli_query($objCon,$strSql);
						$strSql = '';
				}
				//TERMINA INSERCION DE CUENTAS BANCARIAS//////////////////


		for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++)
		{
				$Tipo = $data->sheets[0]["cells"][$i][1]; //Tipo registro
				if($Tipo=="P")
				{
						$strPoliza01 = trim($data->sheets[0]["cells"][$i][2]); //Fecha
						$strPoliza02 = trim($data->sheets[0]["cells"][$i][3]); //Tipo Poliza
						$strPoliza03 = trim($data->sheets[0]["cells"][$i][5]); //Concepto
						$strPoliza03 = str_replace('"', "", $strPoliza03);
						$strPoliza03 = str_replace("'", "", $strPoliza03);
						$strPoliza04 = trim($data->sheets[0]["cells"][$i][4]); //NumPol
						$strPoliza12 = $data->sheets[0]["cells"][$i][12]; //Facturas

						$Organizacion = 1;
						$Ejercicio = 1;
						$strSql = "SELECT Id FROM cont_ejercicios WHERE NombreEjercicio = " . (int)substr($strPoliza01,0,4) . ";";
						$rstEjercicio = mysqli_query($objCon, $strSql);
						while ($objEjercicio = mysqli_fetch_row($rstEjercicio)){
								$Ejercicio = $objEjercicio[0];
						};
						unset($objEjercicio);
						mysqli_free_result($rstEjercicio);
						unset($rstEjercicio);
						$Periodo = (int)substr($strPoliza01,4,2);
						if($Periodo != $intPeriodoPrev){
								$intPeriodoPrev = $Periodo;
								//$contPoliza = 1;
						}
						//$numPol = $contPoliza;
						$numPol = $strPoliza04;
						$TipoPoliza = tipoPoliza($strPoliza02);
						$Referencia = "Carga Inicial";
						$Concepto = $strPoliza03;
						$Cargos = "";
						$Abonos = "";
						$Ajuste = "";
						$Fecha = substr($strPoliza01,0,4) . "-" . substr($strPoliza01,4,2) . "-" . substr($strPoliza01,6,2);
						$TimeStamp = date("Y-m-d");
						$Activo = 1;
						$Eliminado = 0;
						$pdv_aut = 0;

						$strSql = "INSERT INTO cont_polizas (id,idorganizacion,idejercicio,idperiodo,numpol,idtipopoliza,referencia,concepto,origen,idorigen,ajuste,fecha,fecha_creacion,activo,eliminado,pdv_aut,relacionExt,beneficiario,numero,rfc,idbanco,numtarjcuent) VALUES (0," . $Organizacion . "," . $Ejercicio . "," . $Periodo . "," . $numPol . ","  . $TipoPoliza . ",\"$Referencia\",\"$Concepto\",'" . $Cargos . "','" . $Abonos . "','" . $Ajuste . "','" . $Fecha . "', '" . $TimeStamp . "', " . $Activo . "," . $Eliminado ."," . $pdv_aut . ",0,0,0,NULL,0,0);";

						mysqli_query($objCon,$strSql);
						$contMovimiento = 1;
						
						if($strPoliza12 != "" && $strPoliza12 != " ")
								{
										$strPoliza12 = explode(', ',$strPoliza12);
										$limite = count($strPoliza12);
										for($j=0;$j<=$limite-1;$j++)
										{
												$dir = "xmls/facturas/temporales/*".$strPoliza12[$j]."*";
												if($existefactura = glob($dir,GLOB_NOSORT))
												{
														$factura = explode('temporales/',$existefactura[0]);
														$newdir = "xmls/facturas/$contPoliza";

														if(!file_exists($newdir))
														{
																mkdir($newdir, 0777);
														}
														copy($existefactura[0],"$newdir/".$factura[1]);
														//unlink($existefactura[0]);
												}
										}
								}

						$contPoliza++;
				}

				if($Tipo=="M")
				{
						$strMovimiento01 = trim($data->sheets[0]["cells"][$i][6]); //Cuenta
						$strMovimiento02 = trim($data->sheets[0]["cells"][$i][7]); //Referencia
						$strMovimiento02 = str_replace('"', "", $strMovimiento02);
						$strMovimiento02 = str_replace("'", "", $strMovimiento02);
						$strMovimiento03 = strtoupper(trim($data->sheets[0]["cells"][$i][8])); //Tipo Movimiento
						$strMovimiento04 = str_replace(",","",trim($data->sheets[0]["cells"][$i][9])); //Importe
						$strMovimiento05 = trim($data->sheets[0]["cells"][$i][10]); //Concepto
						$strMovimiento05 = str_replace('"', "", $strMovimiento05);
						$strMovimiento05 = str_replace("'", "", $strMovimiento05);
						$strMovimiento06 = trim($data->sheets[0]["cells"][$i][12]); //UUID
						$strMovimiento07 = trim($data->sheets[0]["cells"][$i][11]); //IdSegmentoNegocio

						$idPoliza = $contPoliza-1;
						$NumMovto = $contMovimiento;
						$idSucursal = 1;
						$strSql = "SELECT account_id FROM cont_accounts WHERE manual_code = '" . maskAccount($strMovimiento01,$strMask, $strSeparator) . "';";
						$rstAccId = mysqli_query($objCon, $strSql);
						while($objAccId = mysqli_fetch_row($rstAccId)){
								$Cuenta = $objAccId[0];
						}
						unset($objAccId);
						mysqli_free_result($rstAccId);
						unset($rstAccId);

						$TipoMovto = tipoMovto($strMovimiento03);
						$Importe = $strMovimiento04;
						
						
						$Concepto = "-".$strMovimiento05;
						$Referencia = $strMovimiento02; 
						$Activo = 1;
						$Fecha = date("Y-m-d");
						$factura = "";
						$Persona = "1-";
						$IdSegmento = buscaIdSegmento($strMovimiento07,$objCon);
						
						//COMIENZA PROCESO QUE BUSCA EL UUID DEL MOVIMIENTO Y LO RELACIONA A UNA FACTURA
						//COPIA LA FACTURA y LA BORRA DE LA CARPETA TEMPORALES Y LO MANDA A LA CARPETA DE LA POLIZA
						
						if($strMovimiento06)
						{
								$dir = "xmls/facturas/temporales/*$strMovimiento06*";
								if($existefactura = glob($dir,GLOB_NOSORT))
								{
										$Referencia = $strMovimiento06;    

										$factura = explode('temporales/',$existefactura[0]);
										$newdir = "xmls/facturas/$idPoliza";

										if(!file_exists($newdir))
										{
												mkdir($newdir, 0777);
										}
										copy($existefactura[0],"$newdir/".$factura[1]);
										//unlink($existefactura[0]);
										$factura = $factura[1];
								}

						}

						//TERMINA PROCESO DEL UUID-FACTURA
						
						$strSql = "INSERT INTO cont_movimientos (id,idPoliza,NumMovto,IdSucursal,Cuenta,TipoMovto,Importe,Referencia,Concepto,Activo,FechaCreacion,Factura,Persona,FormaPago,IdSegmento) ";
						$strSql .= "VALUES (0," . $idPoliza . "," . $NumMovto . "," . $idSucursal . "," . $Cuenta . ",'"  . $TipoMovto . "'," . $Importe . ",\"$Referencia\",\"$Concepto\", " . $Activo . ", '" . $Fecha . "', '" . $factura . "', '" . $Persona . "',0,$IdSegmento);";

						mysqli_query($objCon,$strSql);
						$contMovimiento++;
				}

				if($Tipo=="PA")
				{
						$idPoliza;
						$strPagos01 = trim($data->sheets[0]["cells"][$i][2]); //Folio
						$strPagos03 = trim($data->sheets[0]["cells"][$i][3]); //Cuenta Origen
						$strPagos04 = trim($data->sheets[0]["cells"][$i][4]); //Cuenta Destino
						$strPagos05 = trim($data->sheets[0]["cells"][$i][5]); //Banco Destino Nacional

						if($r = buscaIdProv(trim($data->sheets[0]["cells"][$i][6]),$objCon))//IDProvBeneficiario
						{
								$strPagos07 = $r;
						}
						else
						{
								$strPagos07 = 0;
						}
						$strPagos08 = trim($data->sheets[0]["cells"][$i][6]); //RFC BENEFICIARIO
						$strPagos10 = trim($data->sheets[0]["cells"][$i][7]); //IdTipoPago

						if($strPagos05 == '' || $strPagos05 == '0') $strPagos05 == '99';

						$strSql = "UPDATE cont_polizas SET 
						beneficiario    = $strPagos07, 
						numero          = '$strPagos01',  
						rfc             = '$strPagos08',  
						idbanco         = (SELECT idbanco FROM cont_bancos WHERE Clave = '$strPagos05'),  
						numtarjcuent    = '$strPagos04',  
						idCuentaBancariaOrigen = $strPagos03
						WHERE id = $idPoliza;";

						mysqli_query($objCon,$strSql);

						$strSql = "UPDATE cont_movimientos SET FormaPago = (SELECT idFormapago FROM forma_pago WHERE claveSat = ".sprintf('%02d', (intval($strPagos10))).") WHERE IdPoliza = $idPoliza;";

						mysqli_query($objCon,$strSql);
				}

				if($Tipo=="RP")
				{
						$strRP01 = buscaIdProv(trim($data->sheets[0]["cells"][$i][2]),$objCon); //IdProveedor
						$strRP02 = trim($data->sheets[0]["cells"][$i][3]); //Referencia
						$strRP03 = buscaTasa($objCon,trim($data->sheets[0]["cells"][$i][4]),$strRP01); //Tasa
						$strRP04 = trim($data->sheets[0]["cells"][$i][5]); //Importe
						$strRP05 = trim($data->sheets[0]["cells"][$i][6]); //Importe Base
						$strRP06 = trim($data->sheets[0]["cells"][$i][7]); //Otras Erogaciones
						$strRP07 = trim($data->sheets[0]["cells"][$i][8]); //IVA Retenido
						$strRP08 = trim($data->sheets[0]["cells"][$i][9]); //ISR Retenido
						$strRP09 = trim($data->sheets[0]["cells"][$i][10]); //Iva Pagado No Acreditable
						$strRP11 = trim($data->sheets[0]["cells"][$i][12]); //Aplica
						$strRP12 = trim($data->sheets[0]["cells"][$i][13]); //Periodo Acreditamiento
						$strRP13 = buscaEjercicio($objCon,trim($data->sheets[0]["cells"][$i][14])); //Ejercicio Acreditamiento
						$strRP14 = trim($data->sheets[0]["cells"][$i][15]); //Acreditable IETU
						$strRP15 = trim($data->sheets[0]["cells"][$i][16]); //Id IETU

						$strSql = "INSERT INTO cont_rel_pol_prov VALUES (0,$idPoliza,$strRP01,'$strRP02',$strRP03,$strRP04,$strRP05,$strRP06,$strRP07,$strRP08,$strRP09,$strRP11,1,$strRP12,$strRP13,$strRP15,$strRP14);";

						mysqli_query($objCon,$strSql);
				}

				if($Tipo=="DI")
				{
						$strDI01 = buscaIdProv(trim($data->sheets[0]["cells"][$i][2]),$objCon); //IdProveedor
						$strDI02 = trim($data->sheets[0]["cells"][$i][3]); //Referencia
						$strDI03 = buscaTasa($objCon,trim($data->sheets[0]["cells"][$i][4]),$strRP01); //Tasa
						$strDI04 = trim($data->sheets[0]["cells"][$i][5]); //Importe
						$strDI05 = trim($data->sheets[0]["cells"][$i][6]); //Importe Base
						$strDI06 = trim($data->sheets[0]["cells"][$i][7]); //Otras Erogaciones
						$strDI07 = trim($data->sheets[0]["cells"][$i][8]); //IVA Retenido
						$strDI08 = trim($data->sheets[0]["cells"][$i][9]); //ISR Retenido
						$strDI09 = trim($data->sheets[0]["cells"][$i][10]); //Iva Pagado No Acreditable
						$strDI11 = trim($data->sheets[0]["cells"][$i][12]); //Aplica
						$strDI12 = trim($data->sheets[0]["cells"][$i][13]); //Periodo Acreditamiento
						$strDI13 = buscaEjercicio($objCon,trim($data->sheets[0]["cells"][$i][14])); //Ejercicio Acreditamiento
						$strDI14 = trim($data->sheets[0]["cells"][$i][15]); //Acreditable IETU
						$strDI15 = trim($data->sheets[0]["cells"][$i][16]); //Id IETU

						$strSql = "INSERT INTO cont_rel_desglose_iva VALUES (0,$idPoliza,);";

						mysqli_query($objCon,$strSql);
				}

		};

		$files = glob('xmls/facturas/temporales/*'); // get all file names
		foreach($files as $file){ // iterate files
			if(is_file($file))
				unlink($file); // delete file
		}
}

//######

mysqli_close($objCon);
unset($objCon);
unlink(dirname(__FILE__).'/cuentas.xls');
unlink(dirname(__FILE__).'/polizas.xls');
}

function tipoMovto($strType)
{
		switch ($strType){
				case 'C':
						$tipo = "Cargo";
						break;
				case 'A':
						$tipo = "Abono";
						break;
		}
		return $tipo;
}


function tipoPoliza($strType)
{
		switch ($strType){
				case 1:
						$poliza = 1;
						break;
				case 2:
						$poliza = 2;
						break;
				case 3:
						$poliza = 3;
						break;
		}
		return $poliza;
}

function getAffectable($strType){
		switch ($strType){
				case 1:
				case 3:
				case 4:
						$strAffectable = 0;
						break;
				case 2:
						$strAffectable = 1;
						break;
		}
		return $strAffectable;
}

function getMain($strType){
		switch ($strType){
				case 1:
						$strMain = 1;
						break;
				case 3:
				case 4:
						$strMain = 2;
						break;
				case 2:
						$strMain = 3;
						break;
		}
		return $strMain;
}

function maskAccount($strAccount,$strMask, $strSeparator){
//    global $strMask, $strSeparator;

		$arrMask = explode($strSeparator,$strMask);
		$intStart = 0;
		$strOut = "";
		for($intX=0; $intX<count($arrMask); $intX++){
				$strOut .= substr($strAccount,$intStart,strlen($arrMask[$intX])) . $strSeparator;
				$intStart = $intStart + strlen($arrMask[$intX]);
		}
		$strOut = substr($strOut,0,strlen($strOut)-1);
		return $strOut;
}

function createTable($objCon,$strTName){
		//global $strTBName;
		$strSql = "CREATE TABLE " . $strTName . " (
				id INT NOT NULL AUTO_INCREMENT,
				Field01 VARCHAR(31) NULL,
				Field02 VARCHAR(51) NULL,
				Field03 VARCHAR(51) NULL,
				Field04 VARCHAR(31) NULL,
				Field05 VARCHAR(10) NULL,
				Field06 VARCHAR(10) NULL,
				Field07 VARCHAR(10) NULL,
				Field08 VARCHAR(10) NULL,
				Field09 VARCHAR(10) NULL,
				Field10 VARCHAR(10) NULL,
				Field11 VARCHAR(10) NULL,
				Field12 VARCHAR(10) NULL,
				Field13 VARCHAR(10) NULL,
				Field14 VARCHAR(10) NULL,
				PRIMARY KEY (id));";

		mysqli_query($objCon,$strSql);
}

function getSons($strFather, $strLevel, $strAccType,$strTName,$strMask,$strSeparator,$objCon){
//    global $objCon;
		$strSql = "SELECT COUNT(*) FROM " . $strTName . " WHERE Field04 = '" . $strFather . "' ORDER BY Field01;";

		$rstCount = mysqli_query($objCon, $strSql);

//var_dump($rstCount);

		while($objCount = mysqli_fetch_row($rstCount)){
				if($objCount[0]!=0){
						$strSql = "SELECT * FROM " . $strTName . " WHERE Field04 = '" . $strFather . "' ORDER BY Field01;";
						$rstAccounts = mysqli_query($objCon, $strSql);
						$intCount = 0;
						while ($objAccounts = mysqli_fetch_row($rstAccounts)){
								$intCount++;
								$strAccountCode = $strLevel . $intCount;
								$strManualCode = maskAccount($objAccounts[1],$strMask,$strSeparator);
								$strDescription = $objAccounts[2];
								$strSec_Desc = $objAccounts[3];
								$strAccount_Type = $strAccType;
								$strStatus = $objAccounts[6];
								$strMain_Account = getMain($objAccounts[7]);
								$strCash_Flow = 0;
								#$strReg_Date = substr($objAccounts[9],0,4) . "-" . substr($objAccounts[9],4,2) . "-" . substr($objAccounts[9],6,2);
								$strReg_Date = date("Y-m-d");
								$strCurrency_Id = currencyId($objAccounts[12],$objCon);
								$strGroup_Dig = 0;
								$strId_Sucursal = 0;
								$strSeg_Neg_Mov = 0;
								$strAffectable = getAffectable($objAccounts[7]);
								$strMod_Date = date("Y-m-d");
								$strSql = "SELECT account_id FROM cont_accounts WHERE manual_code = '" . maskAccount($strFather,$strMask,$strSeparator) . "';";
								$rstFather = mysqli_query($objCon,$strSql);
								while($objFather = mysqli_fetch_row($rstFather)) {
										$strFather_Account_Id = $objFather[0];
								}
								unset($objFather);
								mysqli_free_result($rstFather);
								unset($rstFather);
								$strRemovable = 1;
								$strAccount_Nature = getAccountNature($objAccounts[5]);
								$strRemoved = 0;
								$strMain_Father = 0;
								$strCuentaOficial = diarioOficialId($objAccounts[11],$objCon);

								$strSql = "INSERT INTO cont_accounts ";
								$strSql .= "VALUES(0,";
								$strSql .= "'" . $strAccountCode . "',";
								$strSql .= "'" . $strManualCode . "',";
								$strSql .= "'" . $strDescription . "',";
								$strSql .= "'" . $strSec_Desc . "',";
								$strSql .= "" . $strAccount_Type . ",";
								$strSql .= "" . $strStatus . ",";
								$strSql .= "" . $strMain_Account . ",";
								$strSql .= "" . $strCash_Flow . ",";
								$strSql .= "'" . $strReg_Date . "',";
								$strSql .= "" . $strCurrency_Id . ",";
								$strSql .= "" . $strGroup_Dig . ",";
								$strSql .= "" . $strId_Sucursal . ",";
								$strSql .= "" . $strSeg_Neg_Mov . ",";
								$strSql .= "" . $strAffectable . ",";
								$strSql .= "'" . $strMod_Date . "',";
								$strSql .= "" . $strFather_Account_Id . ",";
								$strSql .= "" . $strRemovable . ",";
								$strSql .= "" . $strAccount_Nature . ",";
								$strSql .= "" . $strRemoved . ",";
								$strSql .= "" . $strMain_Father . ",";
								$strSql .= "'" . $strCuentaOficial . "',0);";

								mysqli_query($objCon,$strSql);

								$strSql = "SELECT MAX(account_id) FROM cont_accounts;";
								$rstAccount_Id = mysqli_query($objCon,$strSql);
								while($objAccount_Id = mysqli_fetch_row($rstAccount_Id)) {
										$strAccount_Id = $objAccount_Id[0];
								}
								unset($objAccount_Id);
								mysqli_free_result($rstAccount_Id);
								unset($rstAccount_Id);

								switch ($strMain_Account){
								
										case 1:
												$strMain_Father = $strAccount_Id;
												break;
										DEFAULT:
												$strSql = "SELECT main_father FROM cont_accounts WHERE account_id = $strFather_Account_Id";
												$rstMain_Father = mysqli_query($objCon,$strSql);
												while($objMain_Father = mysqli_fetch_row($rstMain_Father)) {
														$strMain_Father = $objMain_Father[0];
												}
												unset($objMain_Father);
												mysqli_free_result($rstMain_Father);
												unset($rstMain_Father);
												break;
								}
								$strSql = "UPDATE cont_accounts SET main_father = " . $strMain_Father . " WHERE account_id = " . $strAccount_Id . ";";
								mysqli_query($objCon,$strSql);

								getSons($objAccounts[1], $strLevel . $intCount . ".", $strAccType,$strTName,$strMask,$strSeparator,$objCon);
						}
						mysqli_free_result($rstAccounts);
						unset($rstAccounts);
				}
		}
		unset($objCount);
		mysqli_free_result($rstCount);
		unset($rstCount);
}

function getAccountId($strAccountType){
		switch ($strAccountType){
				case 'A';
				case 'B':
						$strAccountId = '1';
						break;
				case 'C':
				case 'D':
						$strAccountId = '2';
						break;
				case 'E':
				case 'F':
						$strAccountId = '3';
						break;
				case 'G':
						$strAccountId = '4.2';
						break;
				case 'H':
						$strAccountId = '4.1';
						break;
				case 'K':
						$strAccountId = '5.1';
						break;
				case 'L':
						$strAccountId = '5.2';
						break;
				case 'I':
						$strAccountId = '7';
						break;
				case 'J':
						$strAccountId = '6';
						break;
		}
		return $strAccountId;
};

function getAccountNature($strAccountType){
		switch ($strAccountType){
				case 'B':
				case 'D':
				case 'F':
				case 'H':
				case 'J':
				case 'L':
						$strAccountNature = '1';
						break;
				case 'A':
				case 'E':
				case 'G':
				case 'I':
				case 'K':
						$strAccountNature = '2';
						break;
		}
		return $strAccountNature;
};

function diarioOficialId($IdOf,$objCon)
{
		$myQuery = "SELECT id FROM cont_diarioficial WHERE codigo_agrupador = '$IdOf'";
		$res = mysqli_query($objCon,$myQuery);
		$res = mysqli_fetch_assoc($res);
		return $res['id'];
};

function currencyId($IdCur,$objCon)
{
		$myQuery = "SELECT coin_id FROM cont_coin WHERE codigo = '$IdCur'";
		$res = mysqli_query($objCon,$myQuery);
		$res = mysqli_fetch_assoc($res);
		return $res['coin_id'];
}

function buscaTasa($objCon,$Tasa,$Prov)
{
		
		if($Tasa == 'E')
		{
				$where = "tasa = 'Exenta'";
		}elseif($Tasa == '0')
		{
				$where = "tasa = '0%'";
		}
		else
		{
				$where = "valor = $Tasa";
		}

		$res = mysqli_query($objCon,"SELECT id FROM cont_tasaPrv WHERE idPrv = $Prov AND $where");
		$res = mysqli_fetch_assoc($res);
		return $res['id'];
}
function buscaEjercicio($objCon,$ej)
{
		$res = mysqli_query($objCon,"SELECT Id FROM cont_ejercicios WHERE NombreEjercicio = '$ej'");
		$res = mysqli_fetch_assoc($res);
		return $res['Id'];
}

function buscaIdProv($RFC,$objCon)
{
		$res = mysqli_query($objCon,"SELECT idPrv FROM mrp_proveedor WHERE rfc = '$RFC'");
		$res = mysqli_fetch_assoc($res);
		return $res['idPrv'];
}

function buscaIdSegmento($ClSeg,$objCon)
{
		$res = mysqli_query($objCon,"SELECT idSuc FROM cont_segmentos WHERE Clave = '$ClSeg'");
		$res = mysqli_fetch_assoc($res);
		return $res['idSuc'];
}
?>
</body>
</html>
