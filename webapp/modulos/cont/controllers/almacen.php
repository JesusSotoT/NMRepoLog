<?php
require('common.php');//Carga la funciones comunes top y footer
require("models/almacen.php");

class Almacen extends Common
{
	public $AlmacenModel;

	function __construct(){
		$this->AlmacenModel = new AlmacenModel();
		$this->AlmacenModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->AlmacenModel->close();
	}

	//Pagina principal del almacen
	function principal()
	{
		$datos_empresa = $this->AlmacenModel->organizacion();
		require('views/almacen/index.php');
	}

	//Pagina principal del almacen
	function almacenXml()
	{
		require('views/almacen/navegadorXmls.php');
	}

	//Pagina de la lista de facturas que genera polizas
	function almacenXmlPolizas()
	{
			require('views/almacen/navegadorXmlsPolizas.php');
	}

	//Listado de facturas
	function listaFacturas()
	{
		$lista = $this->AlmacenModel->listaFacturas($_POST);
		$datos=array(); 
		$cont = 0;
		while($l = $lista->fetch_assoc())
		{
			$cont++;
			$pago = "P";
			$descargar = "<a href='#' role='button' class='btn btn-danger btn-xs'>PDF</a> <a href='#' role='button' class='btn btn-success btn-xs'>XML</a>";
			$l['fecha'] = explode(' ',$l['fecha']);
			$l['fecha'] = $l['fecha'][0];
			array_push($datos,array(
                'num' => $cont,
                'fecha' => "<span title='UUID: ".$l['uuid']."\nVersion ".$l['version']."'>".$l['fecha']."</span>",
                'tipo' => utf8_encode($l['tipo']),
                'serie' => utf8_encode($l['serie']),
                'folio' => utf8_encode($l['folio']),
                'emisor' => utf8_encode($l['emisor']),
                'concepto' => "<select class='form-control'><option value='0'>Ninguna</option></select>",
                'cuenta' => "<select class='form-control'><option value='0'>Ninguna</option></select>",
                'total' => "<b class='importes' cantidad='".$l['importe']."'>".number_format($l['importe'],2)." ".$l['moneda']."</b>",
                'poliza' => $l['id_poliza'],
                'receptor' => utf8_encode($l['receptor']),
                'pago' => $pago,
                'descargar' => $descargar
                ));
		}

		//Pinta aqui el json para la vista
		echo json_encode($datos);
	}

	//Listado de facturas normales Almacen
	function listaFacturas2()
	{
		$lista = $this->AlmacenModel->listaFacturas($_POST);
		$logo = $this->AlmacenModel->logo();
		$datos = array(); 
		$cont = 0;
		$total_final = 0;
		while($l = $lista->fetch_assoc())
		{
			$ok = 1;
			if(intval($l['asig']))
			{
				//$ok = $this->AlmacenModel->asig($l['uuid']);
				$ok = 0;
				if($archivo = glob($this->path()."xmls/facturas/[1-9]*/*".$l['uuid'].".xml",GLOB_NOSORT))
					$ok = 1;
				
			}
			if(intval($ok))
			{
				$cont++;
				
				$l['json'] = str_replace("\\", "", $l['json']);
				$json = json_decode($l['json']);
				$json = $this->object_to_array($json);
				$dir = '';
				$subtotal = $ivas = 0;
				$total_final += $l['importe'];

				if($l['version'] == '3.2')
				{
					$subtotal = $json['Comprobante']['@subTotal'];
					$pago = $json['Comprobante']['@metodoDePago'];
					$metodo = $json['Comprobante']['@formaDePago'];
					
					if(strpos($metodo,'exhi') !== FALSE || strpos($metodo,'EXHI') !== FALSE)
						$metodo = "PAGO EN UNA EXHIBICION";
					if(strpos($metodo,'parcial') !== FALSE || strpos($metodo,'PARCIAL') !== FALSE)
						$metodo = "PAGO EN PARCIALIDADES";

					$ivas = $json['Comprobante']['cfdi:Impuestos']['@totalImpuestosTrasladados'];
					//Extraemos el domicilio del emisor
					$domicilio_emisor = $json['Comprobante']['cfdi:Emisor']['cfdi:DomicilioFiscal'];
					$dom_emisor = '';
					foreach ($domicilio_emisor as $campo => $valor) {
						$dom_emisor .= $valor." ";
					}
					//Extraemos el domicilio del receptor
					$domicilio_receptor = $json['Comprobante']['cfdi:Receptor']['cfdi:Domicilio'];
					$dom_receptor = '';
					foreach ($domicilio_receptor as $campo => $valor) {
						$dom_receptor .= $valor." ";
					}
				}
				else
				{
					$subtotal = $json['Comprobante']['@SubTotal'];
					$pago = $json['Comprobante']['@FormaPago'];
					$metodo = $json['Comprobante']['@MetodoPago'];
					$ivas = $json['Comprobante']['cfdi:Impuestos']['@TotalImpuestosTrasladados'];
				}	

				$tieneRelacionPagos = "";
				if(strpos($metodo,'PARCIAL') !== FALSE || strpos($metodo,'PPD') !== FALSE)
				{
					$tieneRelacionPagos = "<br /><i style='opacity: 0.01;font-size:11px;'>*Si tiene pagos</i>";
					if(!$this->AlmacenModel->tieneRelacionPagos($l['uuid']))
						$tieneRelacionPagos = "<br /><i style='color:red;font-size:11px;'>*No tiene pagos</i>";
				}
				
				$asignadas = '';
				if(intval($l['asig']))
				{
					$polizas = $this->AlmacenModel->polizasAsignadas($l['uuid']);
					while($pol = $polizas->fetch_object())
					{
						$asignadas .= "<a href='index.php?c=CaptPolizas&f=ModificarPoliza&id=$pol->id' target='_blank'>".$pol->numpol." (".$pol->idperiodo."-".$pol->ejercicio.")</a> ";
						$dir = "$pol->id";
					}
				}
				else {
					$asignadas = 'No Asignada';
					$dir = "temporales";
				}

				if(intval($_POST['asignadas']) == 3)
					$dir = "canceladas";

				$basename = '';
				if (strpos($l['xml'], '/') !== FALSE)
				{
					$basename = explode("/", $l['xml']);
				}

				if (!empty($basename[3])) {
					$l['xml'] = $basename[3];
				}

				array_push($datos,array(
		        'fecha' => utf8_encode($l['fecha']),
		        'rfc' => utf8_encode($l['rfc']),
		        'emisor' => utf8_encode($l['emisor']),
		        'receptor' => utf8_encode($l['receptor']),
		        'links' => "<a href='xmls/facturas/$dir/".$l['xml']."' target='_blank'><i class='material-icons preview' data-toggle='tooltip' data-placement='top' title='Visualizar'>remove_red_eye</i></a><a href='controllers/visorpdf.php?name=".$l['xml']."&logo=$logo&id=$dir' target='_blank'><i class='material-icons preview' data-toggle='tooltip' data-placement='top' title='Ver PDF'>picture_as_pdf</i></a>",
		        'tipo' => utf8_encode($l['tipo']),
		        'pago' => $pago,
		        'metodo' => $metodo." ".$tieneRelacionPagos,
		        'moneda' => $l['moneda'],
		        'subtotal' => number_format($subtotal,2),
		        'ivas' => number_format($ivas,2),
		        'total' => $l['importe'],
		        'serie' => $l['serie'],
		        'folio' => $l['folio'],
		        'uuid' => $l['uuid'],
		        'fecha_sub' => utf8_encode($l['fecha_subida']),
		        'version' => $l['version'],
		        'estatus' => $asignadas,
		        'check' => "<input type='checkbox' idfac='".$l['id']."' xml='"."xmls/facturas/".$dir."/".$l['xml']."'>",
		        'domicilio_emisor' => $dom_emisor,
		        'domicilio_receptor' => $dom_receptor,
		        'basename'=>$basename));
			}
		}
		array_push($datos, array('total_final' => $total_final));
		//Pinta aqui el json para la vista
		echo json_encode($datos);
	}

	//Listado de facturas pagos Almacen
	function listaFacturasPagos()
	{
		$lista = $this->AlmacenModel->listaFacturas($_POST);
		$logo = $this->AlmacenModel->logo();
		$datos = array(); 
		$cont = 0;
		$total_final = 0;
		while($l = $lista->fetch_assoc())
		{
			$cont++;
			$rels = $this->AlmacenModel->verRelaciones($l['uuid']);
			while($rel = $rels->fetch_assoc())
			{
				array_push($datos,array(
							'uuid' => $l['uuid'],					
					        'fecha' => utf8_encode($l['fecha']),
					        'rfc' => utf8_encode($l['rfc']),
					        'emisor' => utf8_encode($l['emisor']),
					        'receptor' => utf8_encode($l['receptor']),
					        'links' => "<a href='xmls/facturas/temporales/".$l['xml']."' target='_blank'><i class='material-icons preview' data-toggle='tooltip' data-placement='top' title='Visualizar'>remove_red_eye</i></a><a href='controllers/visorpdf.php?name=".$l['xml']."&logo=$logo&id=temporales' target='_blank'><i class='material-icons preview' data-toggle='tooltip' data-placement='top' title='Ver PDF'>picture_as_pdf</i></a>",
					        'tipo' => utf8_encode($l['tipo']),
					        'serie' => $l['serie'],
					        'folio' => $l['folio'],
					        'uuid_doc' => $rel['uuid_relacionado'],	
					        'pago' => $rel['metodo_de_pago_dr'],
					        'moneda' => $rel['moneda_dr'],
					        'saldo_ant' => number_format($rel['imp_saldo_ant'],2),
					        'saldo_inso' => number_format($rel['imp_saldo_insoluto'],2),
					        'importe' => number_format($rel['imp_pagado'],2),
					        'parcialidad' => $rel['parcialidades'],
					        'fecha_sub' => utf8_encode($l['fecha_subida'])));
					        $total_final += $rel['imp_pagado'];
			}
		}
		array_push($datos, array('total_final' => $total_final));
		//Pinta aqui el json para la vista
		echo json_encode($datos);
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

	function cuentas()
	{
		$cuentas = $this->AlmacenModel->cuentas_diario();
		require('views/almacen/cuentas.php');
	}

	//Esta funcion genera el contenido de la tabla dependiendo 
	//del resultdo de la consulta de las cuentas
	function cuentas_busca()
	{
		if(!isset($_REQUEST['tipo']))
			$_REQUEST['tipo'] = 1;

		if(!isset($_REQUEST['cp']))
			$_REQUEST['cp'] = 0;

		$lista = $this->AlmacenModel->cuentas($_REQUEST['cp'],$_REQUEST['tipo']);
		$tabla = "";
		while($l = $lista->fetch_assoc())
		{
			$manual_code = $description = "Ninguna";
			if($l['manual_code'] != '')
			{
				$manual_code = $l['manual_code'];
				$description = $l['description'];
			}
			if($l['idcuenta'] == '')
				$l['idcuenta'] = 0;
			$tabla .= "<tr>
						<td>".$l['nombre']."</td>
						<td>($manual_code) $description</td>
						<td><button class='btn btn-default' onclick='editar(".$_REQUEST['tipo'].",".$_REQUEST['cp'].",".$l['id'].",".$l['idcuenta'].")'>Editar</button></td>
					   </tr>";
		}
		echo $tabla;
	}

	//Listado de clientes o proveerdores
	function lista_cp()
	{
		if(intval($_POST['tipo']) == 1)
			$lista = $this->AlmacenModel->listaClientes();
		
		if(intval($_POST['tipo']) == 2)
			$lista = $this->AlmacenModel->listaProveedores();

		$tabla = "<option value='0' cuenta='0'>Default</option>";
		while($l = $lista->fetch_assoc())
		{
			$tabla .= "<option value='".$l['id']."' cuenta='".$l['cuenta']."'>".$l['nombre']."</option>";
		}
		echo $tabla;
	}

	function guardar_cta()
	{
		echo $this->AlmacenModel->guardar_cta($_POST);
	}

	function buscaFacturas()
	{
		include("../../libraries/xml2json/xml2json.php");
		global $xp;
		$archivos = glob($this->path()."xmls/facturas/*/*.xml",GLOB_BRACE);
		array_multisort(array_map('filectime', $archivos),SORT_DESC,$archivos);
		$rfcOrg = $this->AlmacenModel->organizacion();
		$rfcOrg = $rfcOrg['rfc'];
		foreach($archivos as $file)
		{
			$cont_xml = simplexml_load_file($file);
	        $json = xmlToArray($cont_xml);

			$UUID = $json['Comprobante']['cfdi:Complemento']['tfd:TimbreFiscalDigital']['@UUID'];

			$temporal = 1;
			if(strpos($file,'temporales') === false)
				$temporal = 0;

			$cancelada = 1;
			if(strpos($file,'canceladas') === false)
				$cancelada = 0;
			$vars['TipoDeComprobante'] = $vars['IdDocumento'] = $vars['MetodoDePagoDR'] = $vars['MonedaDR'] = '';
			$vars['ImpPagado'] = $vars['ImpSaldoAnt'] = $vars['ImpSaldoInsoluto'] = $vars['NumParcialidad'] = 0;
			$NumEmpleado = '';
			if(!$UUID)
			{
				$texto 	= file_get_contents($file);
				//Generamos un nuevo xml
				$xml 	= new DOMDocument();
				//Le añadimos el contenido del archivo anterior
				$xml->loadXML($texto);
				//Obtenemos las rutas del xml
				$xp = new DOMXpath($xml);
				$UUID = $this->getpath("//@UUID");
				$NumEmpleado = $this->getpath("//@NumEmpleado");
				$vars['TipoDeComprobante'] = $this->getpath("//@TipoDeComprobante");
				if($vars['TipoDeComprobante'] == "P")
				{
					$vars['IdDocumento'] 	= $this->getpath("//@IdDocumento");
					$vars['ImpPagado'] 		= $this->getpath("//@ImpPagado");
					$vars['ImpSaldoAnt'] 	= $this->getpath("//@ImpSaldoAnt");
					$vars['ImpSaldoInsoluto'] = $this->getpath("//@ImpSaldoInsoluto");
					$vars['MonedaDR'] 		= $this->getpath("//@MonedaDR");
					$vars['MetodoDePagoDR'] = $this->getpath("//@MetodoDePagoDR");
					if($this->getpath("//@NumParcialidad"))
						$vars['NumParcialidad'] =$this->getpath("//@NumParcialidad");
				}

				if(is_array($UUID))
					$UUID = $UUID[1];
				
			}
			$existe = $this->AlmacenModel->yaExistePoliza($UUID);
			//Version 3.2
				if($json['Comprobante']['@version'])
				{
					if($rfcOrg == $json['Comprobante']['cfdi:Emisor']['@rfc'])
					{
						$er = "E";
						$tipo = "Ingresos";
						$rfc = $json['Comprobante']['cfdi:Receptor']['@rfc'];
					}

					if($rfcOrg == $json['Comprobante']['cfdi:Receptor']['@rfc'])
					{
						$er = "R";
						$tipo = "Egresos";
						$rfc = $json['Comprobante']['cfdi:Emisor']['@rfc'];
					}
					
					if($json['Comprobante']['cfdi:Complemento']['nomina12:Nomina']['nomina12:Receptor']['@NumEmpleado'] || $NumEmpleado != '')
						$tipo = "Nomina";

					$vars['folio'] = $json['Comprobante']['@folio'];
					$vars['serie'] = $json['Comprobante']['@serie'];
					$vars['emisor'] = $json['Comprobante']['cfdi:Emisor']['@nombre'];
					$vars['receptor'] = $json['Comprobante']['cfdi:Receptor']['@nombre'];
					$vars['importe'] = $json['Comprobante']['@total'];
					$vars['fecha'] = $json['Comprobante']['@fecha'];
					$vars['version'] = $json['Comprobante']['@version'];
				}
				//Version 3.3
				if($json['Comprobante']['@Version'])
				{
					if($rfcOrg == $json['Comprobante']['cfdi:Emisor']['@Rfc'])
					{
						$er = "E";
						$tipo = "Ingresos";
						$rfc = $json['Comprobante']['cfdi:Receptor']['@Rfc'];
					}

					if($rfcOrg == $json['Comprobante']['cfdi:Receptor']['@Rfc'])
					{
						$er = "R";
						$tipo = "Egresos";
						$rfc = $json['Comprobante']['cfdi:Emisor']['@Rfc'];
					}
					
					if($json['Comprobante']['cfdi:Complemento']['nomina12:Nomina']['nomina12:Receptor']['@NumEmpleado'] || $NumEmpleado != '')
						$tipo = "Nomina";

					$vars['folio'] = $json['Comprobante']['@Folio'];
					$vars['serie'] = $json['Comprobante']['@Serie'];
					$vars['emisor'] = $json['Comprobante']['cfdi:Emisor']['@Nombre'];
					$vars['receptor'] = $json['Comprobante']['cfdi:Receptor']['@Nombre'];
					$vars['importe'] = $json['Comprobante']['@Total'];
					$vars['fecha'] = $json['Comprobante']['@Fecha'];
					$vars['version'] = $json['Comprobante']['@Version'];
				}

				$vars['uuid'] = $UUID;
				$vars['er'] = $er;
				$vars['tipo'] = $tipo;
				$vars['moneda'] = $json['Comprobante']['@Moneda'];
				$vars['rfc'] = $rfc;
				$vars['xml'] = $file;
				$vars['cancelada'] = $cancelada;
				$vars['json'] = json_encode($json,JSON_HEX_APOS);
				$vars['temporal'] = $temporal;
				$vars['origen'] = 0;

			if(!intval($existe))
				$this->AlmacenModel->guardaFactura($vars);
			else
				$this->AlmacenModel->actFacturaTemp($vars);	

			$temps = glob($this->path().'xmls/facturas/temporales/TEMP_*'); // obtiene todos los archivos que no se generaron bien
		    foreach($temps as $temp)
		    {
		        unlink($temp); // lo elimina
		    }
		    $this->AlmacenModel->quitaRutaFactura();	
			
		}
		echo 1;
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

	function relacionaFacturasMovs()
	{
		$movs = $this->AlmacenModel->traerMovimientos();
		$ruta = $this->path()."xmls/facturas/";
		while($m = $movs->fetch_assoc())
		{
			if(file_exists($ruta . "temporales/" . $m['Factura']))
			{
				if(!file_exists($ruta . $m['IdPoliza'] ."/"))
				{
					mkdir ($ruta . $m['IdPoliza'] ."/",0777);
				}
				if(!file_exists($ruta . $m['IdPoliza'] . "/" . $m['Factura']))
					copy($ruta . "temporales/" . $m['Factura'], $ruta . $m['IdPoliza'] . "/" . $m['Factura']);

			}
		}
		echo 1;
	}

	function polizas()
	{
		$segmentos = $this->AlmacenModel->ListaSegmentos();
		$sucursales = $this->AlmacenModel->ListaSucursales();
		$cuentas = $this->AlmacenModel->ListaCuentas();
		$vincular = $this->AlmacenModel->ListaDatos();
		require('views/almacen/polizas.php');	
	}

	function getPolizasLista()
	{
		$lista = $this->AlmacenModel->getPolizasLista();
		$datos = array(); 
		while($l = $lista->fetch_assoc())
		{
			switch($l['id_tipo_poliza'])
			{
				case 1: $tipo_poliza = "Ingresos";break;
				case 2: $tipo_poliza = "Egresos";break;
				case 3: $tipo_poliza = "Diario";break;
				case 4: $tipo_poliza = "Orden";break;
			}

			$provision = "No";
			if(intval($l['provision']))
				$provision = "Si";

			array_push($datos,array(
		        'id' => utf8_encode($l['id']),
		        'documento' => utf8_encode($l['nombre_documento']),
		        'tipo_poliza' => utf8_encode($tipo_poliza),
		        'nombre_poliza' => utf8_encode($l['nombre_poliza']),
		        'provision' => utf8_encode($provision),
		        'modificar' => "<a href='javascript:abrir_polizas(".$l['id'].")'>Modificar</a>",
		        'eliminar'=> "<a href='javascript:eliminar(".$l['id'].")'>Eliminar</a>"));
		}
		echo json_encode($datos);
	}



	function hacer_facturas()
	{
		$ruta = $this->path()."xmls/facturas/";
		$xmls = $_POST['xmls'];
		for($i=0;$i<=count($xmls)-1;$i++)
		{
			//Poliza de Provision
			if($datos_factura = $this->AlmacenModel->datos_factura($xmls[$i],$_POST['tipofac'],1))
			{
				if(intval($datos_factura['idTpl']) && $datos_factura['uuid'])
				{
					$fecha = explode("-",$datos_factura['fecha']);
					$numpol = $this->AlmacenModel->numpol($datos_factura,$fecha);
					if(!intval($numpol))
						$numpol = 1;

					//Crea la poliza
					$id_poliza_acontia = $this->AlmacenModel->insertarPoliza($datos_factura,$fecha,$numpol,$xmls[$i]);
					if(intval($id_poliza_acontia))
					{
						//Crea la carpeta si no existe y agrega el xml
						if(!file_exists($ruta.$id_poliza_acontia))//Si no existe la carpeta de ese poliza la crea
						{
							mkdir ($ruta.$id_poliza_acontia, 0777);
						}
						copy($ruta.'temporales/'.$datos_factura['xml'], $ruta.$id_poliza_acontia."/".$datos_factura['xml']);
						
						//Inserta lo movimientos de la poliza
						$datos_factura['json'] = str_replace("\\", "", $datos_factura['json']);
						$json = json_decode($datos_factura['json']);
						$json = $this->object_to_array($json);
						
						$this->AlmacenModel->insertarMovimientos($id_poliza_acontia,$datos_factura,$fecha,$xmls[$i],$json);
						$hechos .= $datos_factura['uuid']."(Prov), ";
						$this->AlmacenModel->creaCargo($datos_factura,$json);
					}
				}
			}
			//Poliza de Pagos
			if($datos_factura = $this->AlmacenModel->datos_factura($xmls[$i],$_POST['tipofac'],0))
			{
				if(intval($datos_factura['idTpl']) && $datos_factura['uuid'])
				{
					$fecha = explode("-",$datos_factura['fecha']);
					$numpol = $this->AlmacenModel->numpol($datos_factura,$fecha);
					if(!intval($numpol))
						$numpol = 1;

					//Crea la poliza
					$id_poliza_acontia = $this->AlmacenModel->insertarPoliza($datos_factura,$fecha,$numpol,$xmls[$i]);
					if(intval($id_poliza_acontia))
					{
						//Crea la carpeta si no existe y agrega el xml
						if(!file_exists($ruta.$id_poliza_acontia))//Si no existe la carpeta de ese poliza la crea
						{
							mkdir ($ruta.$id_poliza_acontia, 0777);
						}
						copy($ruta.'temporales/'.$datos_factura['xml'], $ruta.$id_poliza_acontia."/".$datos_factura['xml']);
						unlink($ruta.'temporales/'.$datos_factura['xml']);
						
						//Inserta lo movimientos de la poliza
						$datos_factura['json'] = str_replace("\\", "", $datos_factura['json']);
						$json = json_decode($datos_factura['json']);
						$json = $this->object_to_array($json);
						
						$movs = $this->AlmacenModel->insertarMovimientos($id_poliza_acontia,$datos_factura,$fecha,$xmls[$i],$json);
						$this->AlmacenModel->pagaCargo($datos_factura,$json,$movs);
						$hechos .= $datos_factura['uuid']."(Pago), ";
					}
				}
			}
		}
		echo $hechos;
		
	}

	function getCuentasAsoc()
	{
		$lista = $this->AlmacenModel->getCuentasAsoc($_POST['idpoliza']);
		$datos = array(); 
		while($l = $lista->fetch_assoc())
		{
			$abono = "";
            $cargo = "";
			if(intval($l['tipo_movto']) == 1)
                $abono = "<center><span class='glyphicon glyphicon-ok'></span></center>";
            if(intval($l['tipo_movto']) == 2)
                $cargo = "<center><span class='glyphicon glyphicon-ok'></span></center>";
            if(intval($l['id_dato']) == 6)
            	$l['vinculacion'] = "Gasto";
			array_push($datos,array(
		        'manual_code' => utf8_encode($l['manual_code']),
		        'description' => utf8_encode($l['description']),
		        'cargo' => utf8_encode($cargo),
		        'abono' => utf8_encode($abono),
		        'vinculado' => utf8_encode($l['vinculacion']),
		        'modificar' => "<a href='javascript:abrir_cuenta(".$l['id'].")'>Modificar</a>",
		        'eliminar'=> "<a href='javascript:eliminar_cuenta(".$l['id'].")'>Eliminar</a>"));
		}
		echo json_encode($datos);
	}

	function agregar_cuenta()
	{
		echo $this->AlmacenModel->agregar_cuenta($_POST);
	}

	function datos_cuenta()
	{
		$res = $this->AlmacenModel->datos_cuenta($_POST['idmov']);
		$res = $res->fetch_object();
		echo "$res->id_cuenta**/**$res->tipo_movto**/**$res->id_dato**/**$res->nombre_impuesto";
	}

	function guardar_poliza()
	{
		echo $this->AlmacenModel->guardar_poliza($_POST);
	}

	function eliminar_poliza()
	{
		echo $this->AlmacenModel->eliminar_poliza($_POST['idpoliza']);
	}

	function eliminar_cuenta()
	{
		echo $this->AlmacenModel->eliminar_cuenta($_POST['idmov']);
	}

	function getInfoPoliza()
	{
		$res = $this->AlmacenModel->getInfoPoliza($_POST['idpoliza']);
		$res = $res->fetch_assoc(); 
		echo $res['id']."**/**".$res['id_tipo_poliza']."**/**".$res['nombre_poliza']."**/**".$res['provision']."**/**".$res['idsegmento']."**/**".$res['idsucursal'];
	}

	function guardarxmlBD()
	{
		include("../../libraries/xml2json/xml2json.php");
		$vars['IdDocumento'] = $vars['MetodoDePagoDR'] = $vars['MonedaDR'] = '';
		$vars['ImpPagado'] = $vars['ImpSaldoAnt'] = $vars['ImpSaldoInsoluto'] = $vars['NumParcialidad'] = 0;
		$file = $this->path()."xmls/facturas/temporales/".$_POST['xmlfile'];
		$texto 	= file_get_contents($file);
		//Generamos un nuevo xml
		$xml 	= new DOMDocument();
		//Le añadimos el contenido del archivo anterior
		$xml->loadXML($texto);
		//Obtenemos las rutas del xml
		global $xp;
		$xp = new DOMXpath($xml);
		$UUID = $this->getpath("//@UUID");

		$vars['IdDocumento'] 	= $this->getpath("//@IdDocumento");
		$vars['ImpPagado'] 		= $this->getpath("//@ImpPagado");
		$vars['ImpSaldoAnt'] 	= $this->getpath("//@ImpSaldoAnt");
		$vars['ImpSaldoInsoluto'] = $this->getpath("//@ImpSaldoInsoluto");
		$vars['MonedaDR'] 		= $this->getpath("//@MonedaDR");
		$vars['MetodoDePagoDR'] = $this->getpath("//@MetodoDePagoDR");
		$vars['NumParcialidad'] = $this->getpath("//@NumParcialidad");
		
		
		$rfcOrg = $this->AlmacenModel->organizacion();
		$rfcOrg = $rfcOrg['rfc'];

		if($rfcOrg == $this->getpath("//cfdi:Comprobante/cfdi:Emisor/@Rfc"))
		{
			$er = "E";
			$tipo = "Ingresos";
			$rfc = $this->getpath("//cfdi:Comprobante/cfdi:Receptor/@Rfc");
		}

		if($rfcOrg == $this->getpath("//cfdi:Comprobante/cfdi:Receptor/@Rfc"))
		{
			$er = "R";
			$tipo = "Egresos";
			$rfc = $this->getpath("//cfdi:Comprobante/cfdi:Emisor/@Rfc");
		}

		$vars['TipoDeComprobante'] = $this->getpath("//@TipoDeComprobante");
		$vars['folio'] = $this->getpath("//cfdi:Comprobante/@Folio");
		$vars['serie'] = $this->getpath("//cfdi:Comprobante/@Serie");
		$vars['emisor'] = $this->getpath("//cfdi:Comprobante/cfdi:Emisor/@Nombre");
		$vars['receptor'] = $this->getpath("//cfdi:Comprobante/cfdi:Receptor/@Nombre");
		$vars['importe'] = $this->getpath("//cfdi:Comprobante/@Total");
		$vars['fecha'] = $this->getpath("//cfdi:Comprobante/@Fecha");
		$vars['version'] = $this->getpath("//cfdi:Comprobante/@Version");
		$vars['uuid'] = $UUID;
		$vars['er'] = $er;
		$vars['tipo'] = $tipo;
		$vars['moneda'] = $this->getpath("//cfdi:Comprobante/@Moneda");
		$vars['rfc'] = $rfc;
		$vars['xml'] = $_POST['xmlfile'];
		$vars['cancelada'] = 0;
		$cont_xml = simplexml_load_file($file);
	    $json = xmlToArray($cont_xml);
		$vars['json'] = json_encode($json,JSON_HEX_APOS);
		$vars['temporal'] = 1;
		$vars['origen'] = 3;

		echo $this->AlmacenModel->guardaFactura($vars);

	}
}
?>
