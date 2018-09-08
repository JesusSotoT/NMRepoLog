<?php
require('common.php');//Carga la funciones comunes top y footer
require("models/reports.php");

class Reports extends Common
{
	public $ReportsModel;

	function __construct(){
	$this->ReportsModel = new ReportsModel();
	$this->ReportsModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->ReportsModel->close();
	}

	function movcuentas()
	{
		if(isset($_GET['t']))
		{
			$listaCuentas = $this->ReportsModel->getAccountsMayor();
		}
		else
		{
			$listaCuentas = $this->ReportsModel->getAccounts();
		}
		$listaSegmentos = $this->ReportsModel->listaSegmentoSucursal(0);
		$listaSucursales = $this->ReportsModel->listaSegmentoSucursal(1);
		$config = $this->ReportsModel->info_config();
		require('views/reports/movcuentas.php');
	}

	function movcuentas_despues()
	{

		$fecha_antes = $_REQUEST['f3_3']."-".$_REQUEST['f3_1']."-".$_REQUEST['f3_2'];
		$fecha_despues = $_REQUEST['f4_3']."-".$_REQUEST['f4_1']."-".$_REQUEST['f4_2'];
		//echo $_REQUEST['cuentas'];
		if(intval($_REQUEST['f3_1']) != 12)
		{
			$saldo = 1;
		}

		if(intval($_REQUEST['f3_1']) == 12)
		{
			$saldo = 0;
			if(isset($_REQUEST['saldos'])) $saldo = 1;
		}

		$saldosSin = 0;
		if(isset($_REQUEST['saldosFin'])) $saldosSin = 1;

		$datos = $this->ReportsModel->getData_movcuentas_despues($_REQUEST['cuentas'],$fecha_antes,$fecha_despues,$_REQUEST['rango'],$_REQUEST['tipo'],$saldo,$_REQUEST['segmento'],$_REQUEST['sucursal']);
		$empresa = $this->ReportsModel->empresa();
		$logo = $this->ReportsModel->logo();
		require('views/reports/movcuentas_despues.php');
	}

	function libro_mayor()
	{
		require('views/reports/libro_mayor.php');
	}

	function libro_mayor_despues()
	{

		$fecha_antes = $_REQUEST['f3_3']."-".$_REQUEST['f3_1']."-".$_REQUEST['f3_2'];
		$fecha_despues = $_REQUEST['f4_3']."-".$_REQUEST['f4_1']."-".$_REQUEST['f4_2'];
		//echo $_REQUEST['cuentas'];
		if(intval($_REQUEST['f3_1']) != 12)
		{
			$saldo = 1;
		}

		if(intval($_REQUEST['f3_1']) == 12)
		{
			$saldo = 0;
			if(isset($_REQUEST['saldos'])) $saldo = 1;
		}

		$saldosSin = 0;
		if(isset($_REQUEST['saldosFin'])) $saldosSin = 1;
		$cuentas[0] = $_REQUEST['cuentas'];
		$cuentas[1] = 1;
		$datos = $this->ReportsModel->getData_movcuentas_despues($cuentas,$fecha_antes,$fecha_despues,$_REQUEST['rango'],$_REQUEST['tipo'],$saldo,$_REQUEST['segmento'],$_REQUEST['sucursal']);
		$empresa = $this->ReportsModel->empresa();
		$logo = $this->ReportsModel->logo();
		require('views/reports/libro_mayor_despues.php');
	}

	function a29Txt()
	{
		$ejercicios = $this->ReportsModel->ejercicios();
		$ejercicio_actual = $this->ReportsModel->ejercicioActual();
		$proveedor_inicial = $this->ReportsModel->proveedores(1);
		$proveedor_final = $this->ReportsModel->proveedores(1);
		$directorio = "a29";
		require("views/reports/navegadorXMLs.php");
	}

	function generarA29()
	{
		$ej = explode('-',$_POST['Ejercicio']);
		$ejercicio = $ej[1];
		$periodo_inicial = $_POST['Periodo_inicial'];
		$periodo_final = $_POST['Periodo_final'];
		$prov = $_POST['Prov'];
		$proveedor_inicial = $_POST['Proveedor_inicial'];
		$proveedor_final = $_POST['Proveedor_final'];
		$datos = $this->ReportsModel->generarConsultaA29($ej[0],$periodo_inicial,$periodo_final,$prov, $proveedor_inicial, $proveedor_final);
		$nombreTXT = "a29_".$prov."_".$periodo_inicial."_".$periodo_inicial."_".$ejercicio;//Nombre del archivo txt
		$ruta = $this->path()."xmls/a29/" . $ejercicio . "/";//Ruta donde se guardara
		if(!file_exists($ruta))//Si no existe la carpeta de ese periodo la crea
		{
			mkdir ($ruta, 0777);
		}

		$texto = "";
		while($d = $datos->fetch_object())
		{
			$p16 = $p15 = $p16N = $p11 = $p10 = $p11N = $p0 = $ep = $IvaRetenido = '';

			if($d->p16 != '') $p16 = round($d->p16);
			if($d->p15 != '') $p15 = round($d->p15);
			if($d->p16N == '0.00')
				{
					$p16N = '';
				}
				else
				{
					$p16N = round($d->p16N);
					if(intval($p16N) == 0) $p16N = '';
				}
			if($d->p11 != '') $p11 = round($d->p11);
			if($d->p10 != '') $p10 = round($d->p10);
			if($d->p11N == '0.00')
				{
					$p11N = '';
				}
				else
				{
					$p11N = round($d->p11N);
					if(intval($p11N) == 0) $p11N = '';
				}
			if($d->p0 != '') $p0 = round($d->p0);
			if($d->ep != '') $ep = round($d->ep);
			if($d->IvaRetenido == '0.00')
				{
					$IvaRetenido = '';
				}
				else
				{
					$IvaRetenido = round($d->IvaRetenido);
					if(intval($IvaRetenido) == 0) $IvaRetenido = '';
				}
			$rfc = $d->rfc;
			if(intval($d->idtipotercero) == 5 || intval($d->idtipotercero) == 6)
				$rfc = '';
			$texto .= "$d->TipoTercero|$d->TipoOperacion|$rfc|$d->numidfiscal|$d->nombrextranjero|||$p16|$p15|$p16N|$p11|$p10|$p11N||||||$p0|$ep|$IvaRetenido||\n";
		}



		//Genera el archivo y lo guarda o sobreescribe.
		$num=0;
		while(file_exists($ruta . "/" . $nombreTXT . "_" . $num . ".txt"))
		{
			$num++;
		}
		$nombre = $ruta . "/" . $nombreTXT . "_" . $num . ".txt";
		$archivo = fopen($nombre, "w+");
		fwrite($archivo,$texto);
		fclose($archivo);
		echo 1;
	}

	function balanzaComprobacionXML()
	{
		$ejercicios = $this->ReportsModel->ejercicios();
		$ejercicio_actual = $this->ReportsModel->ejercicioActual();
		$directorio = "balanzas";
		require("views/reports/navegadorXMLs.php");
	}

	function generarXMLBalanza()
	{
		$ejercicio 	=	 $_POST['Ejercicio'];
		$periodo 	=	 $_POST['Periodo'];
		$fecha 		=	 explode('-',$_POST['Fecha']);
		$orden 		=	 0;

		$rfc 		=	 $this->ReportsModel->rfc();
		//$datos 	=	 $this->ReportsModel->generarConsultaBalanza($ejercicio,$periodo);//Trae los datos de la consulta Balanza de comprobacion
		$tipoCuenta = 	 $this->ReportsModel->tipoCuenta();

		if(intval($periodo) == 13)
			$orden = 1;
		$datos 		=	 $this->ReportsModel->balanzaComprobacionReporte($this->ReportsModel->IdEjercicio($ejercicio),(int) $periodo,(int) $periodo,$tipoCuenta,0,0,$orden,0,$_POST['agrup']);//Trae los datos de la consulta Balanza de comprobacion
		if($_POST['Tipo'])
		{
			$tipo = 'BC';
		}
		else
		{
			$tipo = 'BN';
		}
		$nombreXML 	=	 "$rfc$ejercicio$periodo$tipo";//Nombre del archivo xml
		$ruta 		= 	 $this->path()."xmls/balanzas/" . $ejercicio . "/";//Ruta donde se guardara
		if(!file_exists($ruta))//Si no existe la carpeta de ese periodo la crea
			{
				mkdir ($ruta, 0777);
			}

		//Llena el arreglo con los datos de la consulta----------------------------------
		$xml['Balanza']['version'] 		= '1.3';
		$xml['Balanza']['rfc'] 			= $rfc;
		$xml['Balanza']['TotalCtas'] 	= $datos->num_rows;
		$xml['Balanza']['Mes'] 			= $periodo;
		$xml['Balanza']['Ano'] 			= $ejercicio;

		$n = 0;//Numeracion del arreglo
		$agrupAnt = '';
		while($d = $datos->fetch_array())
		{
			//Calculos-----------------------------------------------------------------
			if($d['account_nature'] == '2')
			{
				$SaldoAntes 	= floatval($d['CargosAntes']) - floatval($d['AbonosAntes']);
				$SaldoDespues	= $SaldoAntes + floatval($d['CargosMes']) - floatval($d['AbonosMes']);
			}
			if($d['account_nature'] == '1')
			{
				$SaldoAntes 	= floatval($d['AbonosAntes']) - floatval($d['CargosAntes']);
				$SaldoDespues 	= $SaldoAntes + floatval($d['AbonosMes']) - floatval($d['CargosMes']);
			}

			//Almacena resultado--------------------------------------------------------
			if($d['esagrup'] == '')
			{
				if($agrupAnt != '')
				{
					$xml['Balanza']['Cuentas'][$n]['SaldoIni'] 	= number_format($sa, 2, '.', '');
					$xml['Balanza']['Cuentas'][$n]['Debe'] 		= number_format($CargosMes, 2, '.', '');
					$xml['Balanza']['Cuentas'][$n]['Haber'] 	= number_format($AbonosMes, 2, '.', '');
					$xml['Balanza']['Cuentas'][$n]['SaldoFin'] 	= number_format($sd, 2, '.', '');
					$n++;
				}

				$xml['Balanza']['Cuentas'];
				$xml['Balanza']['Cuentas'][$n]['NumCta'] 	= $d['manual_code'];
				$xml['Balanza']['Cuentas'][$n]['SaldoIni'] 	= number_format($SaldoAntes, 2, '.', '');
				$xml['Balanza']['Cuentas'][$n]['Debe'] 		= number_format($d['CargosMes'], 2, '.', '');
				$xml['Balanza']['Cuentas'][$n]['Haber'] 	= number_format($d['AbonosMes'], 2, '.', '');
				$xml['Balanza']['Cuentas'][$n]['SaldoFin'] 	= number_format($SaldoDespues, 2, '.', '');
				$n++;
			}
			else
			{
				if($d['esagrup'] != $agrupAnt)
				{
					if($agrupAnt != '')
					{
						$xml['Balanza']['Cuentas'][$n]['SaldoIni'] 	= number_format($sa, 2, '.', '');
						$xml['Balanza']['Cuentas'][$n]['Debe'] 		= number_format($CargosMes, 2, '.', '');
						$xml['Balanza']['Cuentas'][$n]['Haber'] 	= number_format($AbonosMes, 2, '.', '');
						$xml['Balanza']['Cuentas'][$n]['SaldoFin'] 	= number_format($sd, 2, '.', '');
						$n++;
					}

					//Reinicia
					$sa = $sd = $CargosMes = $AbonosMes = 0;
					$xml['Balanza']['Cuentas'];
					$xml['Balanza']['Cuentas'][$n]['NumCta'] 	= $d['manual_code'];
					$sa += $SaldoAntes;
					$CargosMes += $d['CargosMes'];
					$AbonosMes += $d['AbonosMes'];
					$sd += $SaldoDespues;
				}
				else
				{
					$sa += $SaldoAntes;
					$CargosMes += $d['CargosMes'];
					$AbonosMes += $d['AbonosMes'];
					$sd += $SaldoDespues;
				}
			}
			$agrupAnt = $d['esagrup'];

		}
		//Escribe y genera el codigo XML
		$xml_final='';
		$tipo2 = str_replace('B', '', $tipo);
		if($tipo2 == "C") $tipo2 = "C' FechaModBal='".$fecha[2]."-".$fecha[1]."-".$fecha[0];
		$xml_final.="<BCE:Balanza xsi:schemaLocation='http://www.sat.gob.mx/esquemas/ContabilidadE/1_3/BalanzaComprobacion http://www.sat.gob.mx/esquemas/ContabilidadE/1_3/BalanzaComprobacion/BalanzaComprobacion_1_3.xsd' Version='".$xml['Balanza']['version']."' RFC='".$xml['Balanza']['rfc']."' Mes='".$xml['Balanza']['Mes']."' Anio='".$xml['Balanza']['Ano']."' TipoEnvio='".$tipo2."' xmlns:BCE='http://www.sat.gob.mx/esquemas/ContabilidadE/1_3/BalanzaComprobacion' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'>";
		foreach ($xml['Balanza']['Cuentas'] as $key => $row)
		{
			$xml_final.="\n<BCE:Ctas NumCta='".$row['NumCta']."' SaldoIni='".$row['SaldoIni']."' Debe='".$row['Debe']."' Haber='".$row['Haber']."' SaldoFin='".$row['SaldoFin']."' />";
		}
		$xml_final.="</BCE:Balanza>";

			//Genera el archivo y lo guarda o sobreescribe.
		/*$num=0;
		while(file_exists($ruta . "/" . $nombreXML . "_" . $num . ".xml"))
		{
			$num++;
		}
		$nombre = $ruta . "/" . $nombreXML . "_" . $num . ".xml";
		*/
		$nombre = $ruta . "/" . $nombreXML . ".xml";
		$archivo = fopen($nombre, "w+");
		fwrite($archivo,$xml_final);
		fclose($archivo);
		echo 1;
	}

	function catalogoXML()
	{
		$ejercicios = $this->ReportsModel->ejercicios();
		$ejercicio_actual = $this->ReportsModel->ejercicioActual();
		$directorio = "cuentas";
		require("views/reports/navegadorXMLs.php");
	}


	function generarXMLCatalogo()
	{
		$ejercicio 	= 	$_POST['Ejercicio'];
		$periodo 	= 	$_POST['Periodo'];
		$rfc 		=	$this->ReportsModel->rfc();
		$datos 		= 	$this->ReportsModel->generarConsultaCatalogo($_POST['tipocuenta']);//Trae los datos de la consulta Balanza de comprobacion
		$nombreXML 	= 	$rfc.$ejercicio.$periodo."CT";//Nombre del archivo xml
		$ruta 		= 	$this->path()."xmls/cuentas/" . $ejercicio;//Ruta donde se guardara
		if(!file_exists($ruta))//Si no existe la carpeta de ese periodo la crea
			{
				mkdir ($ruta, 0777);
			}

		//Llena el arreglo con los datos de la consulta----------------------------------
		$xml['Catalogo']['version']		= 		'1.3';
		$xml['Catalogo']['rfc'] 		= 		$rfc;
		$xml['Catalogo']['TotalCtas'] 	= 		$datos->num_rows;
		$xml['Catalogo']['Mes'] 		= 		$periodo;
		$xml['Catalogo']['Ano'] 		= 		$ejercicio;

		$n = 0;//Numeracion del arreglo
		while($d = $datos->fetch_array())
		{

			/*$Nivel 	= 	substr_count($d['account_code'], '.');
			if(intval($d['account_type']) != 3)
			{
				$Nivel 	= 	intval($Nivel)-1;
			}
			else
			{
				$Nivel 	= 	intval($Nivel);
			}*/

			if(strpos($d['CA'], '.'))
			{
				$Nivel = 2;
			}
			else
			{
				$Nivel = 1;
			}


			$desc 	= 	str_replace('&','&amp;',$d['description']);
			$desc 	= 	str_replace('"','&quot;',$desc);
			$desc 	= 	str_replace("'",'&apos;',$desc);
			$desc 	= 	str_replace("<",'&lt;',$desc);
			$desc 	= 	str_replace(">",'&gt;',$desc);

			if(floatval($d['CA']) == 0) $cuentasSinNif++;
			$CA 	=	number_format($d['CA'],2);
			$CA     =   str_replace('.00', '', $CA);

			$xml['Catalogo']['Cuentas'];
			$xml['Catalogo']['Cuentas'][$n]['CodAgrup'] = 	$CA;
			$xml['Catalogo']['Cuentas'][$n]['NumCta'] 	= 	$d['manual_code'];
			$xml['Catalogo']['Cuentas'][$n]['Desc'] 	= 	$desc;
			$xml['Catalogo']['Cuentas'][$n]['SubCtaDe'] = 	$d['CuentaDe'];
			$xml['Catalogo']['Cuentas'][$n]['Nivel'] 	= 	$Nivel;
			$xml['Catalogo']['Cuentas'][$n]['Natur'] 	= 	$d['Naturaleza'];
			$n++;
		}
		//Escribe y genera el codigo XML
		$xml_final	=	'';
		$xml_final	.=	"<catalogocuentas:Catalogo Anio='".$xml['Catalogo']['Ano']."' Mes='".$xml['Catalogo']['Mes']."' RFC='".$xml['Catalogo']['rfc']."' Version='".$xml['Catalogo']['version']."' xmlns:catalogocuentas='http://www.sat.gob.mx/esquemas/ContabilidadE/1_3/CatalogoCuentas' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation='http://www.sat.gob.mx/esquemas/ContabilidadE/1_3/CatalogoCuentas http://www.sat.gob.mx/esquemas/ContabilidadE/1_3/CatalogoCuentas/CatalogoCuentas_1_3.xsd'>";
		foreach ($xml['Catalogo']['Cuentas'] as $key => $row)
		{
			if(intval($row['Nivel']) <= 1)
			{
				$xml_final	.=	"\n<catalogocuentas:Ctas CodAgrup='".$row['CodAgrup']."' NumCta='".$row['NumCta']."' Desc='".$row['Desc']."' Nivel='".$row['Nivel']."' Natur='".$row['Natur']."' />";
			}
			else
			{
				$xml_final	.=	"\n<catalogocuentas:Ctas CodAgrup='".$row['CodAgrup']."' NumCta='".$row['NumCta']."' Desc='".$row['Desc']."' SubCtaDe='".$row['SubCtaDe']."' Nivel='".$row['Nivel']."' Natur='".$row['Natur']."' />";
			}
		}
		$xml_final	.=	"</catalogocuentas:Catalogo>";


			//Genera el archivo y lo guarda o sobreescribe.
		/*$num=0;
		while(file_exists($ruta . "/" . $nombreXML . "_" . $num . ".xml"))
		{
			$num++;
		}*/
		//$nombre 	=	$ruta . "/" . $nombreXML . "_" . $num . ".xml";
		$nombre 	=	$ruta . "/" . $nombreXML . ".xml";
		if(!$cuentasSinNif)
		{
			$archivo 	=	fopen($nombre, "w+");
			fwrite($archivo,$xml_final);
			fclose($archivo);
			echo 1;
		}
		else
		{
			echo 2;
		}
	}

	function auxCuentasXML()
	{
		$ejercicios = $this->ReportsModel->ejercicios();
		$ejercicio_actual = $this->ReportsModel->ejercicioActual();
		$directorio = "auxcuentas";
		require("views/reports/navegadorXMLs.php");
	}


	function generarXMLauxCuentas()
	{
		$ejercicio 	= 	$_POST['Ejercicio'];
		$periodo 	= 	$_POST['Periodo'];
		$rfc 		=	$this->ReportsModel->rfc();
		$fecha_antes =	"$ejercicio-$periodo-01";
		$fecha_despues 	=   "$ejercicio-$periodo-31";
		$arrayCuentas = array('todos');
		$datos 		= 	$this->ReportsModel->getData_movcuentas_despues($arrayCuentas,$fecha_antes,$fecha_despues,0,0,0,'todos');//Trae los datos del Auxiliar de Movimiento por Cuenta
		$nombreXML 	= 	$rfc.$ejercicio.$periodo."XC";//Nombre del archivo xml
		$ruta 		= 	$this->path()."xmls/auxcuentas/" . $ejercicio;//Ruta donde se guardara
		if(!file_exists($ruta))//Si no existe la carpeta de ese periodo la crea
			{
				mkdir ($ruta, 0777);
			}
		// Tipo de Solicitud
		if($_POST['tipoPol'] == "AF" || $_POST['tipoPol'] == "FC")
		{
			$TipoSolicitud = "TipoSolicitud='".$_POST['tipoPol']."' NumOrden='".$_POST['numOrden']."'";
		}
		else
		{
			$TipoSolicitud = "TipoSolicitud='".$_POST['tipoPol']."' NumTramite='".$_POST['numTramite']."'";
		}
		//Llena el arreglo con los datos de la consulta----------------------------------
		$xml['Catalogo']['version']		= 		'1.3';
		$xml['Catalogo']['rfc'] 		= 		$rfc;
		$xml['Catalogo']['TotalCtas'] 	= 		$datos->num_rows;
		$xml['Catalogo']['Mes'] 		= 		$periodo;
		$xml['Catalogo']['Ano'] 		= 		$ejercicio;

		//Escribe y genera el codigo XML
		$xml_final  ="";
		$xml_final	.=	"<?xml version='1.0' encoding='UTF-8'?>";
		$xml_final	.=	"<AuxiliarCtas:AuxiliarCtas xmlns:AuxiliarCtas='http://www.sat.gob.mx/esquemas/ContabilidadE/1_3/AuxiliarCtas' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation='http://www.sat.gob.mx/esquemas/ContabilidadE/1_3/AuxiliarCtas http://www.sat.gob.mx/esquemas/ContabilidadE/1_3/AuxiliarCtas/AuxiliarCtas_1_3.xsd' Version='1.3' RFC='".$xml['Catalogo']['rfc']."' Anio='".$xml['Catalogo']['Ano']."' Mes='".$xml['Catalogo']['Mes']."' $TipoSolicitud>";
		$anterior = '0';
		if($datos->num_rows)
		{
			while($d = $datos->fetch_array())
			{
					if($d['account_id'] != $anterior)
					{
						if($anterior != '0')
						{
							$xml_final .= "</AuxiliarCtas:Cuenta>";
						}
						$d['Descripcion_Cuenta'] = str_replace('&', 'AND', $d['Descripcion_Cuenta']);
						$xml_final .= "\n<AuxiliarCtas:Cuenta NumCta='".$d['Codigo_Cuenta']."' DesCta='".$d['Descripcion_Cuenta']."' SaldoIni='".number_format($this->ReportsModel->Saldos($d['account_id'],$fecha_despues,'Antes',1,'todos',1,'todos'),2,'.','')."' SaldoFin='".number_format($this->ReportsModel->Saldos($d['account_id'],$fecha_despues,'Despues',1,'todos',1,'todos'),2,'.','')."'>";
					}

					$d['Concepto_Movimiento'] = str_replace('&', 'AND', $d['Concepto_Movimiento']);
					$xml_final .= "\n<AuxiliarCtas:DetalleAux Fecha='".$d['Fecha']."' NumUnIdenPol='".$d['ID_Tipo_Poliza']."-$ejercicio-$periodo-".$d['Numero_Poliza']."' Concepto='".$d['Concepto_Movimiento']."' Debe='".number_format($d['Cargos'],2,'.','')."' Haber='".number_format($d['Abonos'],2,'.','')."' />";
					$anterior = $d['account_id'];
			}
			$xml_final .= "</AuxiliarCtas:Cuenta>";
			$xml_final	.=	"</AuxiliarCtas:AuxiliarCtas>";


			$nombre 	=	$ruta . "/" . $nombreXML . ".xml";
			$archivo 	=	fopen($nombre, "w+");
			fwrite($archivo,$xml_final);
			fclose($archivo);
			echo 1;
		}
		else
		{
			echo 0;
		}
	}

	function existeArchivo()
	{
		if($_POST['Tipo'])
		{
			$tipo = 'BC';
		}
		else
		{
			$tipo = 'BN';
		}
		$rfc 	=	$this->ReportsModel->rfc();
			switch($_POST['Funcion'])
			{
				case 'catalogoXML':
								$ruta = $this->path().'xmls/cuentas/'.$_POST['Ejercicio']."/$rfc".$_POST['Ejercicio'].$_POST['Periodo']."-CT.xml";
								break;
				case 'balanzaComprobacionXML':
								$ruta = $this->path().'xmls/balanzas/'.$_POST['Ejercicio']."/$rfc".$_POST['Ejercicio'].$_POST['Periodo']."$tipo.xml";
								break;
			}
			if(file_exists($ruta))
			{
				echo 1;
			}
			else
			{
				echo 0;
			}
	}

	function EliminarArchivo()
	{
		$nueva = explode('/',$_POST['Archivo']);
		$accion = 0;
		
		if (!file_exists($this->path()."xmls/facturas/temporales/".$nueva[3]))
		{
		    copy($_POST['Archivo'], $this->path()."xmls/facturas/temporales/".$nueva[3]);
		    $accion = 1;
		}
		
		unlink($_POST['Archivo']);
		$accion = $this->ReportsModel->quitaTemporal($nueva[3],1);//La activa de temporales
		echo $accion;
	}

	function almacenXML()
	{
		$ejercicios = $this->ReportsModel->ejercicios();
		$ejercicio_actual = $this->ReportsModel->ejercicioActual();
		$RFCInstancia = $this->ReportsModel->rfc();
		$bancos = $this->ReportsModel->validaBancos();
		$directorio = "facturas";
		$logo = $this->ReportsModel->logo();
		require("views/reports/navegadorXMLs_facturas.php");
	}
	
	function listaTemporales()
	{	
		global $xp;
		$listaTemporales = "
		<tr>
			<td width='50' style='color:white;'>*1_-{}*</td>
			<td width='150'></td>
			<td width='50'></td>
			<td width='50'></td>
			<td width='50'></td>
			<td width='50'></td>
			<td width='50'></td>
			<td width='50' colspan='2'></td>
			<td width='50'></td>
			<td width='50' style='font-weight:bold;font-size:9px;text-align:center;'>Sin acción<br>
				<button id='nada_todos' onclick='buttonclick(\"nada\")'>Todos</button>
			</td>
			<td width='50' style='font-weight:bold;font-size:9px;text-align:center;'>Sólo Copiar<br>
				<button id='copiar_todos' onclick='buttonclick(\"copiar\")'>Todos</button>
			</td>
			<td width='50' style='font-weight:bold;font-size:9px;text-align:center;'>Copiar y Eliminar Almacen<br>
				<button id='borrar_todos' onclick='buttonclick(\"borrar\")'>Todos</button>
			</td>
		</tr>";
		//require('xmls/funciones/generarXML.php');
		
		$buscar = "{".$_POST['folio_uuid']."}";

		$n = 2;
		
		if(isset($_COOKIE['inst_lig']))
			$n = 10;		
		
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

		// Abrir un directorio, y proceder a leer su contenido
		if($r)
			$archivos = glob($dir,GLOB_BRACE);
		array_multisort(array_map('filectime', $archivos),SORT_DESC,$archivos);

		#Validamos que no se encuentren facturas repetidas y que no se encuentren en canceladas o 
		# documentos bancarios.
		/*$validar_repetidos = array();
		foreach ($archivos as $registro => $ruta) {
			//Dividimos la ruta del archivo.
			$ruta = explode("/", $ruta);
			//Validamos que no este dentro de canceladas o documentos bancarios.
			if ($ruta[2] == "canceladas" || $ruta[2] == "documentosbancarios") {
				unset($archivos[$registro]);
			} else {
				//Añadimos el primer registro valido al arreglo para comparar repetidos.
				if (empty($validar_repetidos)) {
					array_push($validar_repetidos, $ruta[3]);
				} else {
					//Si no reconoce la factura la agrega al arreglo
					if (!in_array($ruta[3], $validar_repetidos)) {
						array_push($validar_repetidos, $ruta[3]);
					} else {
						//Si la reconoce la borra del array que contiene las rutas de los archivos.
						unset($archivos[$registro]);
					}
				}
			}
		}*/

		$cont = 1;
		$sum = 0;
		$contador = 0;
		foreach($archivos as $file)
		{
			if(!preg_match('/-Cobro|-Pago|Parcial-|-Nomina/', $file))
			{
				$texto = file_get_contents($file);
				$xml = new DOMDocument();
				$xml->loadXML($texto);
				$xp = new DOMXpath($xml);
				//COMIENZA VERSION---------------------------------------
				if($this->getpath("//@version")) 
				{
	        		$data['version'] = $this->getpath("//@version");
				}
	      		else 
	      		{
	      			$data['version'] = $this->getpath("//@Version");
	      		}

				$version = $data['version'];
				//TERMINA VERSION---------------------------------------
				if($version[0] == '3.3')
				{
					$data['folio'] = $this->getpath("//@Folio");
					$data['rfc'] = $this->getpath("//@Rfc");
					$data['uuid'] = $this->getpath("//@UUID");
					$data['total'] = $this->getpath("//@Total");
					$data['nombre'] = $this->getpath("//@Nombre");
					$data['unidad'] = $this->getpath("//@Unidad");
					$data['importe'] = $this->getpath("//@Importe");
					$data['subtotal'] = $this->getpath("//@SubTotal");
					$data['cantidad'] = $this->getpath("//@Cantidad");
					$data['nomina'] = $this->getpath("//@NumEmpleado");
					$data['descuento'] = $this->getpath("//@Descuento");
					$data['descripcion'] = $this->getpath("//@Descripcion");
					$data['metodoDePago'] = $this->getpath("//@MetodoPago");
					$data['descripcion2'] = $this->getpath("//@Descripcion");
					$data['FechaTimbrado'] = $this->getpath("//@FechaTimbrado");
					$data['valorUnitario'] = $this->getpath("//@ValorUnitario");
					$data['impuesto'] = $this->ReportsModel->nombreImpuesto($this->getpath("//@Impuesto"));

					$data['uuid'] = $data['uuid'][1];
				}
				else
				{
					$data['folio'] = $this->getpath("//@folio");
					$data['rfc'] = $this->getpath("//@rfc");
					$data['uuid'] = $this->getpath("//@UUID");
					$data['total'] = $this->getpath("//@total");
					$data['nombre'] = $this->getpath("//@nombre");
					$data['unidad'] = $this->getpath("//@unidad");
					$data['importe'] = $this->getpath("//@importe");
					$data['subtotal'] = $this->getpath("//@subTotal");
					$data['cantidad'] = $this->getpath("//@cantidad");
					$data['impuesto'] = $this->getpath("//@impuesto");
					$data['nomina'] = $this->getpath("//@NumEmpleado");
					$data['descuento'] = $this->getpath("//@descuento");
					$data['descripcion'] = $this->getpath("//@descripcion");
					$data['descripcion2'] = $this->getpath("//@descripcion");
					$data['metodoDePago'] = $this->getpath("//@metodoDePago");
					$data['FechaTimbrado'] = $this->getpath("//@FechaTimbrado");
					$data['valorUnitario'] = $this->getpath("//@valorUnitario");
				}

				if(is_array($data['descripcion']))
				{
					$data['descripcion'] = $data['descripcion'][0];
				}

				if($data['rfc'][0] == $this->ReportsModel->rfc())
				{
					$tipoDeComprobante = "Ingreso";
				}
				elseif($data['rfc'][1] == $this->ReportsModel->rfc())
				{
					$tipoDeComprobante = "Egreso";
				}
				else
				{
					$tipoDeComprobante = "Otro";
				}
				if($data['nomina']){ $tipoDeComprobante = "Nomina";}
				$data['tipocomprobante']= $tipoDeComprobante;
				$name = explode('_',$file);
				$name = str_replace('.xml', '', $name);
				$data['basename'] = basename($file);
				//$auto = explode('/', $name[0]);
				$tienePolizas = 0;
				$tienePolizas = $this->ReportsModel->tienePolizas($data['uuid']);
				$razon_social = $name[1];
				if($name[1] == '')
					$razon_social = $data['nombre'][1];
				$listaTemporales .= "
				<tr>
					<td width='50'><img src='xmls/imgs/xml.jpg' width=30></td>
					<td width='400' style='font-size: 11px;'><b>".$data['folio']."</b> ".$razon_social."</td>
					<td width='140'>
						<a href='index.php?c=factura&f=visor_factura&uuid=".$data['uuid']."' target='_blank'>
						Ver</a>
						/ <a href='controllers/visorpdf.php?name=".$data['basename']."&logo=".$logo."&id=".$carpeta[$n]."' target='_blank'>PDF</a>
					</td>
					<td width=300' style='font-size: 11px;'>".$data['descripcion']."</td>
					<td width='40'>".$data['uuid']."</td>
					<td width='40'><center>".$tipoDeComprobante."</center></td>
					<td align='center' width='200'>
					<b style='color:orange'>$".number_format($data['total'],2,'.',',')."</b>
					</td>
					<td></td>
					<td width='200'><b>".$data['metodoDePago']."</b></td>
					<td width='200'>".$data['FechaTimbrado']."</td>
					<td width='50' style='text-align:center;'>
						<input title='Sin Accion' type='radio' name='radio-$cont' id='nada-$cont' value='' class='nada' checked>
					</td>
					<td width='50' style='text-align:center;'>
						<input title='Sólo copiar' type='radio' name='radio-$cont' id='copiar-$cont' value='".$file."' polizas='$tienePolizas' class='copiar' onclick='tienePolizas(\"".$data['uuid']."\",$cont)'>
					</td>
					<td width='50' style='text-align:center;'>
					<input title='Copiar y pegar' type='radio' name='radio-$cont' id='borrar-$cont' value='".$file."' polizas='$tienePolizas' class='borrar' onclick='tienePolizas(\"".$data['uuid']."\",$cont)'>
					</td>
				</tr>";
				$cont++;
				//$sum = $sum + floatval($data['total']); <- Sumatoria total de la columna.
				$contador++;
			}
		}
		//Imprimir total en listaTemporales.
		/*$total = "
		<tr style='border-top: 1px solid #ddd; padding-top:0.5em;'>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td align='right'>
				<h4>Total:<h4>
			</td>
			<td align='center'>
				<h4 style='margin: 0; color:orange;'>$".number_format($sum,2,'.',',')."</h4>
			</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>";
		//echo($listaTemporales);
		*/
		//echo $listaTemporales; ." ". $total;
		$contador = 1;
		if($contador)
			echo $listaTemporales;
		else
			echo "No se encontraron registros";
	}

	function listaTemporalesBD()
	{	
		$datos = $this->ReportsModel->listaTemporalesBD($_POST);
		$listaTemporales = "
		<tr>
			<th width='50' style='color:white;'>*1_-{}*</th>
			<th width='150'>Folio/Razon</th>
			<th width='50'></th>
			<th width='50'>Concepto</th>
			<th width='50'>UUID</th>
			<th width='50'>Tipo</th>
			<th width='50'><center>Importe</center></th>
			<th width='50' colspan='2'>MetodoPago</th>
			<th width='50'>Fecha</th>
			<th width='50' style='font-weight:bold;font-size:9px;text-align:center;'>Sin acción<br>
				<button id='nada_todos' onclick='buttonclick(\"nada\")'>Todos</button>
			</th>
			<th width='50' style='font-weight:bold;font-size:9px;text-align:center;'>Sólo Copiar<br>
				<button id='copiar_todos' onclick='buttonclick(\"copiar\")'>Todos</button>
			</th>
			<th width='50' style='font-weight:bold;font-size:9px;text-align:center;'>Copiar y Eliminar Almacen<br>
				<button id='borrar_todos' onclick='buttonclick(\"borrar\")'>Todos</button>
			</th>
		</tr>";
		$cont = 0;
		while($d = $datos->fetch_object())
		{
			$d->json = str_replace("\\", "", $d->json);
			$json = json_decode($d->json);
			$json = $this->object_to_array($json);

			$razon = $d->receptor;
			if($d->tipo == "Egreso" || $d->tipo == "Egresos")
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

			$listaTemporales .= "
				<tr>
					<td width='50'><img src='xmls/imgs/xml.jpg' width=30></td>
					<td width='400' style='font-size: 11px;'><b>".$d->folio."</b> ".$razon."</td>
					<td width='140'>
						<a href='index.php?c=factura&f=visor_factura&uuid=$d->uuid' target='_blank'>Ver</a>
						/ <a href='controllers/visorpdf.php?name=".$d->xml."' target='_blank'>PDF</a>
					</td>
					<td width=300' style='font-size: 11px;'>".$descripcion."</td>
					<td width='40'>".$d->uuid."</td>
					<td width='40'><center>".$d->tipo."</center></td>
					<td align='center' width='200'>
					<b style='color:orange'>$".number_format($d->importe,2,'.',',')."</b>
					</td>
					<td></td>
					<td width='200'><b>".$metodoPago."</b></td>
					<td width='200'>".$d->fecha."</td>
					<td width='50' style='text-align:center;'>
						<input title='Sin Accion' type='radio' name='radio-$cont' id='nada-$cont' value='' class='nada' checked>
					</td>
					<td width='50' style='text-align:center;'>
						<input title='Sólo copiar' type='radio' name='radio-$cont' id='copiar-$cont' value='xmls/facturas/temporales/".$d->xml."' class='copiar' onclick='tienePolizas(\"".$d->uuid."\",$cont)'>
					</td>
					<td width='50' style='text-align:center;'>
					<input title='Copiar y pegar' type='radio' name='radio-$cont' id='borrar-$cont' value='xmls/facturas/temporales/".$d->xml."' class='borrar' onclick='tienePolizas(\"".$d->uuid."\",$cont)'>
					</td>
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

	function tienePolizas()
	{
		$devuelve = 0 ;
		/*$devuelve = $this->ReportsModel->tienePolizas($_POST['UUID']);//Busca con UUID NOrmal
		if(!intval($devuelve))//Si regresa cero vuelve a buscar po UUID sin guiones
		{
			$_POST['UUID'] = str_replace('-', '', $_POST['UUID']);
			$devuelve = $this->ReportsModel->tienePolizas($_POST['UUID']);
		}*/
		echo $devuelve;
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

	function copiaFacturaBorra()
	{
		#Mueve las facturas a la carpeta de la poliza(id)
		$ruta = $this->path()."xmls/facturas/" . $_POST['IdPoliza'];//Ruta donde se copiara
		if(!file_exists($ruta))//Si no existe la carpeta de ese periodo la crea
		{
			mkdir ($ruta, 0777);
		}

		for($i=0;$i<=count($_POST['Copiar'])-1;$i++)
		{
			$nueva = explode('temporales/',$_POST['Copiar'][$i]);
			if(file_exists($_POST['Copiar'][$i]))
				copy($_POST['Copiar'][$i], $ruta."/".$nueva[1]);
			else
			{
				$archivos = glob($this->path()."xmls/facturas/[1-9]*/".$nueva[1],GLOB_NOSORT);
				foreach($archivos as $file)
				{
					copy($file, $ruta."/".$nueva[1]);
					break;
				}
			}
		}

		for($i=0;$i<=count($_POST['Borrar'])-1;$i++)
		{
			$nueva = explode('temporales/',$_POST['Borrar'][$i]);
			
			if(file_exists($_POST['Borrar'][$i]))
			{
				copy($_POST['Borrar'][$i], $ruta."/".$nueva[1]);
				unlink($_POST['Borrar'][$i]);
			}
			else
			{
				$archivos = glob($this->path()."xmls/facturas/[1-9]*/".$nueva[1],GLOB_NOSORT);
				foreach($archivos as $file)
				{
					copy($file, $ruta."/".$nueva[1]);
					break;
				}
			}
			$this->ReportsModel->quitaTemporal($nueva[1],0);//Inactiva de temporales
		}
	}

	function listaAcreditamientoProveedores()
	{
		$ejercicios = $this->ReportsModel->ejercicios();
		$polizas = $this->ReportsModel->listaAcreditamientoProveedores();
		require("views/reports/listaAcreditamientoProveedores.php");
	}

	function listaAcreditamientoDesglose()
	{
		$ejercicios = $this->ReportsModel->ejercicios();
		$polizas = $this->ReportsModel->listaAcreditamientoDesglose();
		require("views/reports/listaAcreditamientoDesglose.php");
	}

	function actAcreditamiento()
	{
		$a = $this->ReportsModel->actAcreditamiento($_POST['Ids'], $_POST['Periodo'], $_POST['Ejercicio'], $_POST['Tipo']);
		echo $a;
	}

	function balanzaComprobacion()
	{
		$ejercicios = $this->ReportsModel->ejercicios();
		$monedas = $this ->ReportsModel->listaMonedas();
		require("views/reports/balanzaComprobacion.php");

	}

	function balanzaComprobacionReporte()
	{
		if($_POST['orden'] == 'Si')
			$_POST['orden'] = 0;
		else
			$_POST['orden'] = 1;

		if($_POST['ceros'] == 'Si')
			$_POST['ceros'] = 1;
		else
			$_POST['ceros'] = 0;

		if($_POST['saldo'] == 'Si')
			$_POST['saldo'] = 1;
		else
			$_POST['saldo'] = 0;

		$logo = $this->ReportsModel->logo();
		$inicio = sprintf('%02d', $_POST['periodo_inicio']);
		$fin = sprintf('%02d', $_POST['periodo_fin']);
		$fecIni=$this->NombrePeriodo($inicio);
		$fecFin=$this->NombrePeriodo($fin);
		$tipoCuenta = $this->ReportsModel->tipoCuenta();
		$datos = $this->ReportsModel->balanzaComprobacionReporte($_POST['ejercicio'],$inicio,$fin,$tipoCuenta, $_POST['tipo'],$_POST['idioma'],0,$_POST['orden']);
		$n_cuentas = $datos->num_rows;
		$ej = $_POST['ejercicio'];
		$ej2 = $this->ReportsModel->NombreEjercicio($ej);
		$tipoVista = $_POST['tipo'];
		$empresa = $this->ReportsModel->empresa();
		require("views/reports/balanzaComprobacionReporte2.php");
	}

	function balanceGeneral()
	{
		ini_set('display_errors','Off');
		$ejercicios = $this->ReportsModel->ejercicios();
		$sucursales = $this->ReportsModel->listaSegmentoSucursal(1);
		$segmentos = $this->ReportsModel->listaSegmentoSucursal(0);
		$monedas = $this ->ReportsModel->listaMonedas();
		require("views/reports/balanceGeneral.php");
	}

	function NombrePeriodo($periodo)
	{
		$saldos = "";
		if(intval($periodo) == 13)
			{
				$periodo = 12;
				$saldos = " con Saldos";
			}
		$p;
		switch(intval($periodo))
			{
				case 1:$p  = 'Enero';break;
				case 2:$p  = 'Febrero';break;
				case 3:$p  = 'Marzo';break;
				case 4:$p  = 'Abril';break;
				case 5:$p  = 'Mayo';break;
				case 6:$p  = 'Junio';break;
				case 7:$p  = 'Julio';break;
				case 8:$p  = 'Agosto';break;
				case 9:$p  = 'Septiembre';break;
				case 10:$p = 'Octubre';break;
				case 11:$p = 'Noviembre';break;
				case 12:$p = 'Diciembre'.$saldos;break;
			}
			return $p;
	}

	function balanceGeneralReporte()
	{
		$periSaldo = '';
		$tipoC = 0;
		if(isset($_POST['tipoC']))
		{
			$tipoC = 1;
		}
		//echo "tipoC: ".$tipoC;
		if(intval($_POST['periodo']) == 13)
		{
			$_POST['periodo'] = 12;
			$p13 = 1;
			$periSaldo = ' con Saldos';
		}
		$ej = $_POST['ejercicio'];
		$empresa = $this->ReportsModel->empresa();
		$tipoCuenta = $this->ReportsModel->tipoCuenta();
		$logo = $this->ReportsModel->logo();
		if($_POST['segmento']>=1){
			$nomSegmento=$this->ReportsModel->nomSegmento($_POST['segmento']);
		}else { $nomSegmento="Todos"; }
		if($_POST['sucursal']>=1){
			$nomSucursal=$this->ReportsModel->nomSucursal($_POST['sucursal']);
		}else { $nomSucursal="Todas"; }

		if(intval($_POST['periodo']))
		{
			$periodo = $this->NombrePeriodo($_POST['periodo']);
			$periodo = $periodo . $periSaldo;
			$anterior = intval($_POST['periodo']) -1;
			if($anterior == 0)
			{
				$anterior = 12;
			}
			$periodoAnterior = $this->NombrePeriodo($anterior);

			switch(intval($_GET['tipo']))
			{
				case 0:
				//Estado de resultados
					if($_POST['comSeg']==1){
						$segmentos = $this->ReportsModel->listaSegmentoSucursal(0);
						$datos = $this->ReportsModel->EstadoResultadoxSegmento($_POST['ejercicio'],$_POST['periodo'],$_POST['sucursal'],$segmentos,0,$tipoCuenta,$_POST['detalle'],$_POST['idioma']);
						require("views/reports/estadoResultadosxSegmento2.php");
						}else{
							$datos = $this->ReportsModel->balanceGeneralReporteDemas($_POST['ejercicio'],$_POST['periodo'],$_POST['sucursal'],$_POST['segmento'],$_GET['tipo'],$tipoCuenta,$_POST['detalle'],$p13,$_POST['idioma'],$_POST['presup']);
							if(intval($_POST['presup']))
								require("views/reports/estadoResultadosReportePresup.php");
							else
								require("views/reports/estadoResultadosReporte.php");
							}

					break;
				case 1:
				//Balance General
					$datos = $this->ReportsModel->balanceGeneralReporteDemas($_POST['ejercicio'],$_POST['periodo'],$_POST['sucursal'],$_POST['segmento'],$_GET['tipo'],$tipoCuenta,$_POST['detalle'],$p13,$_POST['idioma']);
					$activos = $this->ReportsModel->balanceGeneralReporteActivo($_POST['ejercicio'],$_POST['periodo'],$_POST['sucursal'],$_POST['segmento'],$tipoCuenta,$_POST['idioma']);
					require("views/reports/balanceGeneralReporte.php");
					break;
				case 2:
				//Estado de Origen y aplicacion de recursos
					$datos = $this->ReportsModel->balanceGeneralReporteDemas($_POST['ejercicio'],$_POST['periodo'],$_POST['sucursal'],$_POST['segmento'],$_GET['tipo'],$tipoCuenta,$_POST['detalle'],$p13,$_POST['idioma']);
					require("views/reports/estadoOrigenReporte.php");
					break;
				case 3:
				//NIF B6 Estado de situacion financiera
					$datos = $this->ReportsModel->nifReporte($_POST['ejercicio'],$_POST['periodo'],$_POST['sucursal'],$_POST['segmento'],$_GET['tipo'],$p13);
					require("views/reports/nifReporte.php");
					break;
				case 4:
				//NIF B3 Estado de resultado Integral
					$datos = $this->ReportsModel->nifReporte($_POST['ejercicio'],$_POST['periodo'],$_POST['sucursal'],$_POST['segmento'],$_GET['tipo'],$p13);
					require("views/reports/nifReporteB3.php");
					break;
			}
		}
		else
		{
			//Balance General y Estado de resultados a 12 periodos
			$datos = $this->ReportsModel->balanceGeneralReportePeriodos($_POST['ejercicio'],$_POST['sucursal'],$_POST['segmento'],$_GET['tipo'],$tipoCuenta,$_POST['detalle'],$_POST['idioma']);
			require("views/reports/balanceGeneralReportePeriodos.php");
		}

	}

	function catalogoCuentas()
	{
		$naturalezas = $this->ReportsModel->naturalezas();
		$tipos = $this->ReportsModel->tipos();
		require("views/reports/catalogoCuentas.php");
	}

	function catalogoCuentasReporte()
	{
		$logo = $this->ReportsModel->logo();
		$empresa = $this->ReportsModel->empresa();
		$tipoCuenta = $this->ReportsModel->tipoCuenta();
		$cuentas = $this->ReportsModel->catalogoCuentasReporte($_POST['naturaleza'],$_POST['tipo'],$tipoCuenta);
		require("views/reports/catalogoCuentasReporte.php");
	}

	function polizasXML()
	{
		$ejercicios = $this->ReportsModel->ejercicios();
		$ejercicio_actual = $this->ReportsModel->ejercicioActual();
		$directorio = "polizas";
		require("views/reports/navegadorXMLs.php");
	}

	function generarXMLPolizas()
	{
		//INICIAN VARIABLES INICIALES/////////////////////////////////////////////////
		$ejercicio 	= 	$_POST['Ejercicio'];
		$periodo 	= 	$_POST['Periodo'];
		$rfc 		= 	$this->ReportsModel->rfc();
		$datos 		= 	$this->ReportsModel->generarConsultaPolizas($ejercicio,$periodo);//Trae los datos de la consulta Balanza de comprobacion
		$nombreXML 	= 	$rfc.$ejercicio.$periodo."PL";//Nombre del archivo xml
		$ruta 		= 	$this->path()."xmls/polizas/" . $ejercicio;//Ruta donde se guardara
		//TERMINAN VARIABLES INICIALES***********************************************

		//INICIA TIPO DE SOLICITUD/////////////////////////////////////////////////
		if($_POST['tipoPol'] == "AF" || $_POST['tipoPol'] == "FC")
		{
			$TipoSolicitud = "TipoSolicitud='".$_POST['tipoPol']."' NumOrden='".$_POST['numOrden']."'";
		}
		else
		{
			$TipoSolicitud = "TipoSolicitud='".$_POST['tipoPol']."' NumTramite='".$_POST['numTramite']."'";
		}

		//TERMINA TIPO DE SOLICITUD***********************************************

		//INICIA SI LA CARPETA NO EXISTE LA CREA CON SUS PERMISOS///////////////////
		if(!file_exists($ruta))//Si no existe la carpeta de ese periodo la crea
			{
				mkdir ($ruta, 0777);
			}
		//TERMINA SI LA CARPETA NO EXISTE LA CREA CON SUS PERMISOS*****************


		$xml_final	=	'';

		//INICIA LA GENERACION DEL XML//////////////////////////////////////////////

		//Cabeceras
		$xml_final	.=	"<PLZ:Polizas xsi:schemaLocation='http://www.sat.gob.mx/esquemas/ContabilidadE/1_3/PolizasPeriodo http://www.sat.gob.mx/esquemas/ContabilidadE/1_3/PolizasPeriodo/PolizasPeriodo_1_3.xsd' Version='1.3' RFC='$rfc' Mes='$periodo' Anio='$ejercicio' $TipoSolicitud xmlns:PLZ='http://www.sat.gob.mx/esquemas/ContabilidadE/1_3/PolizasPeriodo' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'>";

		$IdPolizaAnterior = 0;

		//Si la consulta devuelve datos genera el archivo y manda true
		if($datos->num_rows)
		{
			while($d = $datos->fetch_array())
			{
				if($d['Benef'] == '')
					$d['Benef'] = '-';
				$d['conceptoPoliza'] 	= 	str_replace('&','&amp;',$d['conceptoPoliza']);
				$d['conceptoMovimiento'] = str_replace('&','&amp;',$d['conceptoMovimiento']);
				$d['conceptoPoliza'] 	= 	str_replace('"','&quot;',$d['conceptoPoliza']);
				$d['conceptoMovimiento'] = str_replace('"','&quot;',$d['conceptoMovimiento']);
				$d['conceptoPoliza'] 	= 	str_replace("'",'&apos;',$d['conceptoPoliza']);
				$d['conceptoMovimiento'] = str_replace("'",'&amp;',$d['conceptoMovimiento']);
				$d['conceptoPoliza'] 	= 	str_replace("<",'&lt;',$d['conceptoPoliza']);
				$d['conceptoMovimiento'] = str_replace("<",'&lt;',$d['conceptoMovimiento']);
				$d['conceptoPoliza'] 	= 	str_replace(">",'&gt;',$d['conceptoPoliza']);
				$d['conceptoMovimiento'] = str_replace(">",'&gt;',$d['conceptoMovimiento']);
				if($d['id'] != $IdPolizaAnterior)
				{
					if($IdPolizaAnterior != 0) $xml_final .= "</PLZ:Poliza>";
					$xml_final .= "\n<PLZ:Poliza NumUnIdenPol='".$d['idtipopoliza']."-$ejercicio-$periodo-".$d['numpol']."' Fecha='".$d['fecha']."' Concepto='".$d['conceptoPoliza']."'>";
				}

				//Si es cargo o abono
				if($d['TipoMovto'] == 'Cargo')
					{
						$cargo = $d['Importe'];
						$abono = 0.0;
					}
					else
					{
						$cargo = 0.0;
						$abono = $d['Importe'];
					}

				$xml_final .= "\n<PLZ:Transaccion NumCta='".$d['manual_code']."' DesCta='".$d['description']."' Concepto='".$d['conceptoMovimiento']."' Debe='".number_format($cargo,2,'.','')."' Haber='".number_format($abono,2,'.','')."' >";


					if(!intval($d['MultipleFacturas'] AND $d['Factura'] != '' AND $d['Factura'] != NULL AND $d['Factura'] != '-'))
					{
						//Busca la factura relacionada, si encuentra la factura agrega estas lineas del CompNal al xml
						if($datosFactura = $this->MontoFactura($d['id'],$d['Factura']))
						{
							//$datosFactura = "10xxx_*-*_rfcloco_*-*_1200";
							$datosFactura = explode('_*-*_',$datosFactura);
							$uuidCFDI = $this->generarGuiones($datosFactura[0]);
							$xml_final .= "\n<PLZ:CompNal UUID_CFDI='".$uuidCFDI."' RFC='".$datosFactura[1]."' MontoTotal='".number_format($datosFactura[2],2,'.','')."' />";
						}
					}

					if(intval($d['MultipleFacturas']))
					{
						if($grupoFacturas = $this->ReportsModel->MultipleFacturas($d['id'],$d['NumMovto']))
						{
							while($gf = $grupoFacturas->fetch_assoc())
							{
								//Busca la factura relacionada, si encuentra la factura agrega estas lineas del CompNal al xml
								if($datosFactura = $this->MontoFactura($d['id'],$gf['Factura']))
								{
									//$datosFactura = "10xxx_*-*_rfcloco_*-*_1200";
									$datosFactura = explode('_*-*_',$datosFactura);
									$uuidCFDI = $this->generarGuiones($datosFactura[0]);
									$xml_final .= "\n<PLZ:CompNal UUID_CFDI='".$uuidCFDI."' RFC='".$datosFactura[1]."' MontoTotal='".number_format($datosFactura[2],2,'.','')."' />";
								}
							}
						}
					}


				//Si la forma de pago es con cheque.
						if(intval($d['FormaPago']) == 2)
						{
							//Si se trata de una cuenta de bancos
							if($this->ReportsModel->CuentaBancos($d['main_father']) && $d['TipoMovto'] == 'Abono' && $d['numero'] && $d['idtipopoliza'] == 2)
							{
								$cbo = explode('*-/-*',$d['CuentaBancariaBancoOrigen']);
								$xml_final .= "\n<PLZ:Cheque Num='".$d['numero']."' BanEmisNal='".str_pad($cbo[1], 3, "0", STR_PAD_LEFT)."' CtaOri='".$cbo[0]."' Fecha='".$d['fecha']."' Benef='".$d['Benef']."' RFC='".$d['rfc']."' Monto='".$d['Importe']."' />";
							}
							if($d['idtipopoliza'] == 1)
							{
								$xml_final .= "\n<PLZ:Cheque Fecha='".$d['fecha']."' Monto='".$d['Importe']."' />";
							}
						}elseif(intval($d['FormaPago']) == 7 || intval($d['idFormaPago']) == 8)
						{
							//Si la forma de pago es con una transferencia o spei.
							if($this->ReportsModel->CuentaBancos($d['main_father']) && $d['TipoMovto'] == 'Abono' && $d['idtipopoliza'] == 2)
							{
								$cbo = explode('*-/-*',$d['CuentaBancariaBancoOrigen']);
								$xml_final .= "\n<PLZ:Transferencia CtaOri='".$cbo[0]."' BancoOriNal='".str_pad($cbo[1], 3, "0", STR_PAD_LEFT)."' CtaDest='".$d['CuentaDestino']."' BancoDestNal='".str_pad($d['BancoDestino'], 3, "0", STR_PAD_LEFT)."' Fecha='".$d['fecha']."' Benef='".$d['Benef']."' RFC='".$d['rfc']."' Monto='".$d['Importe']."' />";
							}
							if($d['idtipopoliza'] == 1)
							{
								$xml_final .= "\n<PLZ:Transferencia Fecha='".$d['fecha']."' Monto='".$d['Importe']."' />";
							}
						}else
						{
							//Si es metodo de pago credito o debito lo cambia a otros
							if(intval($d['idFormaPago']) == 5 || intval($d['idFormaPago']) == 6) $d['FormaPago'] = '99';

							//Otros metodos de pago
							if($this->ReportsModel->CuentaBancos($d['main_father']) && $d['TipoMovto'] == 'Abono' && $d['FormaPago'] && $d['idtipopoliza'] == 2)
							{
								$cbo = explode('*-/-*',$d['CuentaBancariaBancoOrigen']);
								$xml_final .= "\n<PLZ:OtrMetodoPago MetPagoPol='".$d['FormaPago']."' Fecha='".$d['fecha']."' Benef='".$d['Benef']."' RFC='".$d['rfc']."' Monto='".$d['Importe']."' />";
							}
							if($d['idtipopoliza'] == 1 && $d['FormaPago'])
							{
								$xml_final .= "\n<PLZ:OtrMetodoPago MetPagoPol='".$d['FormaPago']."' Fecha='".$d['fecha']."' Monto='".$d['Importe']."' />";
							}

						}

				$xml_final .= "</PLZ:Transaccion>";

				//Guarda el ultimo id de la poliza
				$IdPolizaAnterior = $d['id'];

			}

			$xml_final .= "</PLZ:Poliza>";
			$xml_final	.=	"</PLZ:Polizas>";
			//TERMINA LA GENERACION DEL XML//////////////////////////////////////////////


				//Genera el archivo y lo guarda o sobreescribe.
			/*$num=0;
			while(file_exists($ruta . "/" . $nombreXML . "_" . $num . ".xml"))
			{
				$num++;
			}*/
			//$nombre 	=	$ruta . "/" . $nombreXML . "_" . $num . ".xml";

			//ruta y nombre del archivo xml
			$nombre 	=	$ruta . "/" . $nombreXML . ".xml";

			//abre o crea el documento
			$archivo 	=	fopen($nombre, "w+");

			//escribe el documento con el contenido del xml (la variable $xml_final almacena el contenido)
			fwrite($archivo,$xml_final);

			//cierra el documento y devuelve true
			fclose($archivo);
			echo 1;
		}
		else
		{
			//Si la consulta no genera datos manda false
			echo 0;
		}
	}

	function foliosXML()
	{
		$ejercicios = $this->ReportsModel->ejercicios();
		$ejercicio_actual = $this->ReportsModel->ejercicioActual();
		$directorio = "folios";
		require("views/reports/navegadorXMLs.php");
	}

	function generarXMLFolios()
	{

		//INICIAN VARIABLES INICIALES//////////////////////////////////////////////////
		$ejercicio 	= 	$_POST['Ejercicio'];
		$periodo 	= 	$_POST['Periodo'];
		$rfc 		= 	$this->ReportsModel->rfc();
		$datos 		= 	$this->ReportsModel->generarConsultaFolios($ejercicio,$periodo);//Trae los datos de la consulta Balanza de comprobacion
		$nombreXML 	= 	$rfc.$ejercicio.$periodo."XF";//Nombre del archivo xml
		$ruta 		= 	$this->path()."xmls/folios/" . $ejercicio;//Ruta donde se guardara
		$xmlAnterior = array();
		$contArray = 0;
		//TERMINAN VARIABLES INICIALES***********************************************


		//INICIA SI LA CARPETA NO EXISTE LA CREA CON SUS PERMISOS///////////////////
		if(!file_exists($ruta))//Si no existe la carpeta de ese periodo la crea
			{
				mkdir ($ruta, 0777);
			}
		//TERMINA SI LA CARPETA NO EXISTE LA CREA CON SUS PERMISOS*****************
		if($_POST['tipoPol'] == "AF" || $_POST['tipoPol'] == "FC")
		{
			$TipoSolicitud = "TipoSolicitud='".$_POST['tipoPol']."' NumOrden='".$_POST['numOrden']."'";
		}
		else
		{
			$TipoSolicitud = "TipoSolicitud='".$_POST['tipoPol']."' NumTramite='".$_POST['numTramite']."'";
		}

		$xml_final	=	'';

		//INICIA LA GENERACION DEL XML//////////////////////////////////////////////

		//Cabeceras
		$xml_final	.=	"<RepAux:RepAuxFol xmlns:RepAux='http://www.sat.gob.mx/esquemas/ContabilidadE/1_3/AuxiliarFolios' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation='http://www.sat.gob.mx/esquemas/ContabilidadE/1_3/AuxiliarFolios http://www.sat.gob.mx/esquemas/ContabilidadE/1_3/AuxiliarFolios/AuxiliarFolios_1_3.xsd' Version='1.3' RFC='$rfc' Anio='$ejercicio' Mes='$periodo' $TipoSolicitud>";

		$IdPolizaAnterior = 0;

		//Si la consulta devuelve datos genera el archivo y manda true
		if($datos->num_rows)
		{
			while($d = $datos->fetch_array())
			{

				if($d['id'] != $IdPolizaAnterior)
				{
					if($IdPolizaAnterior != 0)
					{
						$xml_final .= "</RepAux:DetAuxFol>";
						unset($xmlAnterior);//Reinicia historial de UIID's de la poliza (Para no repetir UUID's en la misma poliza)
						$contArray = 0;//Reinicia contador del array
					}
					$xml_final .= "\n<RepAux:DetAuxFol NumUnIdenPol='".$d['idtipopoliza']."-$ejercicio-$periodo-".$d['numpol']."' Fecha='".$d['fecha']."'>";
				}

				if(!intval($d['MultipleFacturas'] AND $d['Factura'] != '' AND $d['Factura'] != NULL AND $d['Factura'] != '-'))
				{
				//Busca la factura relacionada, si encuentra la factura agrega estas lineas del CompNal al xml
					if($datosFactura = $this->MontoFactura($d['id'],$d['Factura']))
					{
						//$datosFactura = "10xxx_*-*_rfcloco_*-*_1200";
						$datosFactura = explode('_*-*_',$datosFactura);
						if(!array_keys($xmlAnterior,$datosFactura[0]))//No Existe ese UUID en el historial de la poliza entonces lo escribe
						{
							$moneda = $this->ReportsModel->CuentaMoneda($d['Cuenta'],$d['fecha']);
							$moneda = explode('/',$moneda);
							$uuidCFDI = $this->generarGuiones($datosFactura[0]);
							$xml_final .= "\n<RepAux:ComprNal UUID_CFDI='".$uuidCFDI."' MontoTotal='".number_format($datosFactura[2],2,'.','')."' RFC='".$datosFactura[1]."' MetPagoAux='".$datosFactura[3]."' Moneda='".$moneda[0]."' TipCamb='".$moneda[1]."' />";
							$xmlAnterior[$contArray] = $datosFactura[0];//Escribe el UUID en el historial de la poliza
							$contArray++;
						}
					}
				}

					if(intval($d['MultipleFacturas']))
					{
						if($grupoFacturas = $this->ReportsModel->MultipleFacturas($d['id'],$d['NumMovto']))
						{
							while($gf = $grupoFacturas->fetch_assoc())
							{
								//Busca la factura relacionada, si encuentra la factura agrega estas lineas del CompNal al xml
								if($datosFactura = $this->MontoFactura($d['id'],$gf['Factura']))
								{
									//$datosFactura = "10xxx_*-*_rfcloco_*-*_1200";
									$datosFactura = explode('_*-*_',$datosFactura);
									if(!array_keys($xmlAnterior,$datosFactura[0]))//No Existe ese UUID en la poliza entonces lo escribe
									{
										$moneda = $this->ReportsModel->CuentaMoneda($d['Cuenta'],$d['fecha']);
										$moneda = explode('/',$moneda);
										$uuidCFDI = $this->generarGuiones($datosFactura[0]);
										$xml_final .= "\n<RepAux:ComprNal UUID_CFDI='".$uuidCFDI."' MontoTotal='".number_format($datosFactura[2],2,'.','')."' RFC='".$datosFactura[1]."' MetPagoAux='".$datosFactura[3]."' Moneda='".$moneda[0]."' TipCamb='".$moneda[1]."' />";
										$xmlAnterior[$contArray] = $datosFactura[0];//Escribe el UUID en el historial de la poliza
										$contArray++;
									}
								}
							}
						}
					}


				//Guarda el ultimo id de la poliza
				$IdPolizaAnterior = $d['id'];

			}

			$xml_final .= "</RepAux:DetAuxFol>";
			$xml_final	.=	"</RepAux:RepAuxFol>";
			//TERMINA LA GENERACION DEL XML//////////////////////////////////////////////


				//Genera el archivo y lo guarda o sobreescribe.
			/*$num=0;
			while(file_exists($ruta . "/" . $nombreXML . "_" . $num . ".xml"))
			{
				$num++;
			}*/
			//$nombre 	=	$ruta . "/" . $nombreXML . "_" . $num . ".xml";

			//ruta y nombre del archivo xml
			$nombre 	=	$ruta . "/" . $nombreXML . ".xml";

			//abre o crea el documento
			$archivo 	=	fopen($nombre, "w+");

			//escribe el documento con el contenido del xml (la variable $xml_final almacena el contenido)
			fwrite($archivo,$xml_final);

			//cierra el documento y devuelve true
			fclose($archivo);
			echo 1;
		}
		else
		{
			//Si la consulta no genera datos manda false
			echo 0;
		}
	}

	function MontoFactura($poliza,$factura)
	{
		//$return = false;
		//Carga el archivo
			
			if($f = simplexml_load_file($this->path()."xmls/facturas/$poliza/$factura"))
			{
				if($namespaces = $f->getNamespaces(true))
				{
					//Buscara el los namespaces del cfdi
					$child = $f->children($namespaces['cfdi']);

					//Busca el RFC del xml
					foreach($child->Emisor[0]->attributes() AS $a => $b)
					{
						if($a == 'rfc')
						{
							$rfc = $b;
						}

					}

					foreach($f->attributes() AS $a => $b)
					{
						if($a == 'metodoDePago')
						{
							$pago = $b;
							$pago = $this->ReportsModel->metodoPago($pago);
						}

						if($a == 'Moneda')
						{
							$moneda = $b;
						}

					}


					foreach($child->Impuestos[0]->attributes() AS $a => $b)
					{
						if($a == 'totalImpuestosTrasladados')
						{
							$totalImpuestosTrasladados = $b;
						}

						if($a == 'totalImpuestosRetenidos')
						{
							$totalImpuestosRetenidos = $b;
						}

					}

					//Extrae el UUID del nombre de la factura
					$uuid = str_replace('.xml', '', $factura);
					$uuid = explode('_',$uuid);
					$uuid = $uuid[2];

					//Busca los importes y los suma
					for($i=0;$i<=(count($child->Conceptos->Concepto)-1);$i++)
					{

						foreach($child->Conceptos->Concepto[$i]->attributes() AS $a => $b)
						{
								if($a == 'importe')
								{
									$importes += floatval($b);
								}
						}
					}
					$importes = $importes + $totalImpuestosTrasladados - $totalImpuestosRetenidos;
					$return  = $uuid."_*-*_".$rfc."_*-*_".$importes."_*-*_".$pago."_*-*_".$moneda;

				}
			}


		return $return;
	}

	function borraFacturaForm()
	{
		$Archivo = explode('/', $_POST['Archivo']);

		$this->ReportsModel->borraFacturaForm($_POST['IdPoliza'],$Archivo[3]);
	}

	//Clases para movimientos de polizas y facturas.
	function movpolizas()
	{
		require('views/reports/movpolizas.php');
	}

	function movpolizas_despues()
	{
		$fecha_antes = $_REQUEST['f3_3']."-".$_REQUEST['f3_1']."-".$_REQUEST['f3_2'];
		$fecha_despues = $_REQUEST['f4_3']."-".$_REQUEST['f4_1']."-".$_REQUEST['f4_2'];
		$facturas_asociadas = $_REQUEST['asociar'];
		$tipo_poliza = $_REQUEST['tipo_poliza'];
		$datos = $this->ReportsModel->getData_movpolizas_despues($fecha_antes, $fecha_despues, $facturas_asociadas, $tipo_poliza);
		$empresa = $this->ReportsModel->empresa();
		$logo = $this->ReportsModel->logo();
		require('views/reports/movpolizas_despues.php');
	}


	function importes($pol_id,$num_mov)
	{
		global $xp;
		$suma = 0;
		$facturas = $this->ReportsModel->getImporteXML($pol_id, $num_mov);
		
		while($fac = $facturas->fetch_assoc())
		{
			$archivo = $this->path()."xmls/facturas/$pol_id/".$fac['factura'];

			$texto 	= file_get_contents($archivo);
			$xml = new DOMDocument();
			$xml->loadXML($texto);
			$xp = new DOMXpath($xml);
			$importe = $this->getpath("//@importe");
			if (is_array($importe)) {
				for ($i=0; $i <= count($importe)-1; $i++) {
					$suma += floatval($importe[$i]);
				}
			} else {
				$suma += floatval($importe);
			}
			//$suma += $this->getpath("//@importe");
			//echo $this->getpath("//@importe");

		}
		return $suma;
	}

	function generarGuiones($uuid) {
		if (isset($uuid)) {
			$uuidGuiones = $uuid;
		} else {
			$uuidGuiones = $_POST['uuid'];
		}
		if (!strpos($uuidGuiones, '-') !== false) {
			$replacement = "-";
			for ($i = 0; $i < strlen($uuidGuiones); $i++) { 
				if ($i == 8) {
					$uuidGuiones = substr_replace($uuidGuiones, $replacement, $i, 0);
				}
				if ($i == 13) {
					$uuidGuiones = substr_replace($uuidGuiones, $replacement, $i, 0);			
				}
				if ($i == 18) {
					$uuidGuiones = substr_replace($uuidGuiones, $replacement, $i, 0);
				}
				if ($i == 23) {
					$uuidGuiones = substr_replace($uuidGuiones, $replacement, $i, 0);
				}
			}
		}
		return $uuidGuiones;
	}

	function obtener_tipo_poliza($id){
		$Result = $this->ReportsModel->obtener_tipo_poliza($id);
		$tipo_poliza = $Result->fetch_assoc();
		return $tipo_poliza['titulo'];
	}

	function Eliminarxml()
	{
		unlink($_POST['xml']);
	}

	function obtener_datos_factura($uuid){
		$Result = $this->ReportsModel->obtener_datos_factura($uuid);
		return $Result;
	}
}
?>
