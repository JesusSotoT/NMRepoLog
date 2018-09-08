<?php

	//echo "CARGANDO LOS DATOS DEL SERVIDOR USUARIO Y CONTRASEÑA...";
	ini_set("display_errors", 1);
	$servidor = "nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
	$user = "nmdevel";
	$contra = "nmdevel";
	$based = "_dbmlog0000006473";

	//echo "CONECTANDO AL SERVIDOR ...";
	$mysqli = new mysqli($servidor, $user, $contra, $based);
	if ($mysqli->connect_errno) {
		echo "Error en la conexion";
	}

	//echo "FUNCION PARA CAMBIAR DE CARACTERES...";
	function normaliza ($cadena) {
		$originales =  'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
		$modificadas = 'AAAAAAACEEEEIIIIDNOOOOOOUUUUYbsaaaaaaaceeeeiiiionoooooouuuyybyRr';
		$cadena = utf8_decode($cadena);
		$cadena = strtr($cadena, utf8_decode($originales), $modificadas);
		$cadena = strtolower($cadena);
		return utf8_encode($cadena);
	}

	//echo "COMENZANDO LAS CONFIGURACIONES PARA TIMBRAR LAS FACTURAS...";
	include "../../SAT/config.php";
	$pathd = "../" . $pathd;
	$pathdc = "../" . $pathdc;
	$p12_netwar = $pathd . "/netwar.produccion.pem";
	date_default_timezone_set("Mexico/General");
	//$fecha = date("Y-m-d H:i:s");
	$fecha = date("Y-m-d") . "T" . date("H:i:s", strtotime("-7 minute"));

	// CARGANDO LAS VARIABLES ESTATICAS...
	$rfc_cliente = "SAGR660226AE7";
	$key_cliente = $pathdc . "/saggr660226ae7_13010312482.key";
	$pwd_cliente = "SAGR6602C";
	$cer_cliente = $pathdc . "/00001000000202724321.cer";

	// DECLARANDO LAS VARIABLES A USAR...
	$azurian = array();
	$bloqueo = 0;
	$registro = 0;
	$nombre = 0;
	$p = 0;
	$traslads = "";
	$retenids = "";
	$haytras = 0;
	$hayret = 0;
	$trasladsimp = 0.00;
	$retenciones = 0.00;
	$trasxml = '';
	$retexml = '';
	$conceptosOri = '';
	$conceptos = '';
	$co = 0;
	$idcliente = 0;
	$idventa = 0;
	$cadRet = '';
	$ivat = '';
	$isr = '';
	$ivas = '';

	//echo  "1. Obtengo la informacion de la url de los titulos...<br>";
	$urltitulo = $_GET['t'];
	$titulofact = explode('_', $urltitulo);

	$folio_factura = $titulofact[0];
	$fecha_factura = $fecha;
	$expedidaen = $titulofact[1];
	$ffactura = $titulofact[2];
	$origen = $titulofact[3];
	$remitente = $titulofact[4];
	$rfc_cte = trim($titulofact[5]);
	$direccion1 = $titulofact[6];
	$recoger = $titulofact[7];
	$destino = $titulofact[8];
	$destinatario = $titulofact[9];
	$rfc2 = $titulofact[10];
	$direccion2 = $titulofact[11];
	$entregar = $titulofact[12];
	$valor_unitario = number_format(floatval($titulofact[13]), 2, '.', '');
	$peso = $titulofact[15];
	$cond_pago = $titulofact[16];
	$operador = $titulofact[17];
	$unidad = $titulofact[18];
	$placas = $titulofact[19];
	$repartos = $titulofact[20];
	$otros = $titulofact[21];
	$importe = number_format($titulofact[22], 2, '.', '');
	$iva = number_format($titulofact[23], 2, '.', '');
	$retencion = number_format($titulofact[24]);
	$total = number_format($titulofact[25],2 ,'.', '');
	$total_letra = $titulofact[26];
	$usuario = $titulofact[27];
	$cadena = $titulofact[28];

	//echo "2. Obteniendo la informacion de las url para el detalle... <br>";
	$prods = $_GET['prod'];
	$urlprods = explode('_', $prods);

	// = = = = = PRECIOS = = = = =
	$urlprecios = $_GET['precio'];
	$precios = explode('_', $urlprecios);

	// = = = = = CANTIDADES = = = = =
	$urlcantidad = $_GET['cant'];
	$cantidades = explode('_', $urlcantidad);

	// = = = = = OBSERVACIONES = = = = =
	$urlobs = $_GET['obs'];
	$observa = explode('_', $urlobs);

	// = = = = = 2as OBSERVACIONES = = = = =
	$urlobs2 = $_GET['obs2'];
	$observa2 = explode('_', $urlobs2);

	// = = = = = CUANTOS PRODUCTOS REGISTRADOS = = = = =
	$cuantos = $_GET['v'];

	//echo "3. Extraer el ID del cliente... <br>";
	echo "SELECT id, nombre, rfc FROM comun_cliente WHERE rfc like '$rfc_cte'";
	if ($micliente = mysqli_query($mysqli, "SELECT id, nombre, rfc FROM comun_cliente WHERE rfc like '$rfc_cte'")) {
		while ($tcliente = $micliente->fetch_object()) {
			$idcliente.=$tcliente->id;
		}
	}
	//echo "<br>El ID del cliente obtenido es el: " . $idcliente;

	// Si no existe el cliente no hará nada...
	if ($idcliente == "" or $idcliente == NULL or $idcliente == 0) {
		echo "El cliente no existe, favor de verificar";
	} else {
		//echo "4. Guarda la venta... <br>";
		$tv_obs = $operador . ' Unidad:' . $unidad . ' placas:' . $placas;
		$insert_venta = "INSERT INTO venta (idCliente, monto, estatus, idEmpleado, rfc, documento, fecha, montoimpuestos, idSucursal, observacion, envio) VALUES ('$idcliente', $total, 1, 2, '$rfc_cte', 1, '$fecha_factura', $iva, 1, '$tv_obs', 0)";
		$registra_venta = mysqli_query($mysqli, $insert_venta);
		//echo "<br> Servicio guardado en la tabla venta... ";

		//echo "- - - 4.1. Extrae el ID de la última venta registrada...";
		$miventa_reg = mysqli_query($mysqli, "SELECT max(idVenta) idventa FROM venta");
		while ($tventa = $miventa_reg->fetch_object()) {
			$idventa.=$tventa->idventa;
		}
		echo "<br> El ID del servicio es: " . $idventa;

		//echo "- - - 4.2. Guarda en la tabla venta_pagos con el id... ".$idventa."<br>";
		$insert_ventapagos = "INSERT INTO venta_pagos (idVenta, idFormapago, monto) VALUES ('$idventa', 9, $total)";
		$reg_vp = mysqli_query($mysqli, $insert_ventapagos);

		//echo "5. Guardo la información de los ".$cuantos." productos...<br>";
		for ($p=0; $p < $cuantos; $p++) {
			// DECLARANDO Y LIMPIANDO LAS VARIABLES A USAR...
			$nombre = '';
			$idprod = '';
			$idventaprod = '';
			$nombre_prod = strtoupper($urlprods[$p]);
			$precio_prod = number_format($precios[$p],2 ,'.', '');
			$cant_venta = $cantidades[$p];
			$obs_producto = $observa[$p];

			$subtotal_prod = number_format($precio_prod * $cant_venta,2 ,'.', '');
			$iva_pProd = $precio_prod * 0.16;
			$total_prod = $iva_pProd + $precio_prod;

			//echo "- - - 5.1. Verifico que el producto exista...<br>";
			if ($miproducto = mysqli_query($mysqli, "SELECT * FROM mrp_producto WHERE nombre='$nombre_prod'")) {
				while ($qproducto = $miproducto->fetch_object()) {
					$nombre.=$qproducto->nombre;
					$idprod.=$qproducto->idProducto;
				}
			}

			//echo "- - - - - - 5.1.1. No existe el producto, agrego el registro a la tabla mrp_productos ...<br>";
			if ($nombre == "" or $nombre == NULL or $nombre== 0) {
				$reg_prod = "INSERT INTO mrp_producto (nombre, vendible, idLinea, maximo, minimo, imagen, precioventa, idUnidad, tipo_Producto, idUnidadCompra, estatus) VALUES ('$nombre_prod', 1, 1, 1, 1, 'images/noimage.jpeg','$precio_prod',1,6,1,1)";
				$registra_miprod = mysqli_query($mysqli, $reg_prod);

				//echo "- - - - - - 5.1.2. Obtengo el id del producto guardado...<br>";
				$idprod = "";
				if ($miproducto2 = mysqli_query($mysqli, "SELECT * FROM mrp_producto WHERE nombre='$nombre_prod'")) {
					while ($qproducto2 = $miproducto2->fetch_object()) {
						$idprod.=$qproducto2->idProducto;
					}
				}

				// echo "- - - - - - 5.1.3. Guardo el registro del stock del producto guardado...<br>";
				$reg_stock = "INSERT INTO mrp_stock (idproducto, cantidad, idAlmacen, idUnidad, ocupados) VALUES ('$idprod','0','1','1','0')";
				$regstock = mysqli_query($mysqli, $reg_stock);

				// echo "- - - - - - 5.1.4. Agrego el impuesto (IVA )del producto a la tabla producto_impuesto... <br>";
				$insImp = "INSERT INTO producto_impuesto (idproducto, idImpuesto, valor) VALUES ('$idprod', 1, 16)";
				$reg_prodimp = mysqli_query($mysqli, $insImp);

				// echo "- - - - - - 5.1.5. Si el producto lleva la palabra FLETE agrego el impuesto (ISR o Retención) del producto a la tabla producto_impuesto... <br>";
				$nprod = substr($nombre, 0,5);
				$nprod = strtoupper($nprod);
				if ($nprod == "FLETE") {
					echo "<br>".$nprod;
					$insret = "INSERT INTO producto_impuesto (idproducto, idImpuesto, valor) VALUES ('$idprod', 10, 4)";
					$reg_prodret = mysqli_query($mysqli, $insret);
				}

				//echo "- - - 5.2 Actualizo el precio del producto en mrp_productos...<br>";
				$cod_prod = "cod".$idprod;
				$actual_prod = "UPDATE mrp_producto SET nombre='$nombre_prod', precioventa='$precio_prod', codigo='$codprod' Where idproducto = '$idprod' ";
				$actualiza_p = mysqli_query($mysqli, $actual_prod);
			}
			
			//echo "- - - 5.3. Guardo las ventas en venta_producto...<br> ";
			$ins_ventaprod = "INSERT INTO venta_producto (idProducto, cantidad, preciounitario, subtotal, idventa, impuestosproductoventa, total) VALUES ('$idprod', '$cant_venta', '$precio_prod', '$subtotal_prod', '$idventa', '$iva_pProd', '$total_prod')";
			$reg_ventaprod = mysqli_query($mysqli, $ins_ventaprod);

			//echo "- - - - - - 5.3.1. Obtengo la información del Id del último registro guardado <br>";
			$miventa_prod = mysqli_query($mysqli, "SELECT max(idventa_producto) idventa_producto FROM venta_producto");
			while ($tventaprod = $miventa_prod->fetch_object())	{
				$idventaprod.=$tventaprod->idventa_producto;
			}

			//echo "- - - 5.4. Guardo los impuestos de los productos en la tabla venta_producto_impuesto...<br>";
			$ins_vtaprodimp = "INSERT INTO venta_producto_impuesto (idVentaproducto, idImpuesto, porcentaje) VALUES ('$idventaprod', 1, 16)";
			$reg_vtaprodimp = mysqli_query($mysqli, $ins_vtaprodimp);
			$nombre = '';
		}

		// TENGO QUE OBTENER TANTO LOS DATOS DEL REMITENTE COMO DEL EMISOR PARA ENCLAUTRARLOS EN LA CADENA ORIGINAL
		echo "<br> GENERANDO LA CADENA ORIGINAL . . . <br>";

		// OBTENIENDO LOS DATOS DEL EMISOR = = = = = = = = = = = = = = = = = = = = = = = = = = = =
		if ($inf_emisor = mysqli_query($mysqli, "SELECT * FROM pvt_configura_facturacion")) {
			while ($emisor = $inf_emisor->fetch_object()) {
				$rfc_emisor = $emisor->rfc;
				$reg_emisor = $emisor->regimen;
				$pais_emisor = $emisor->pais;
				$razon_emisor = $emisor->razon_social;
				$calle_emisor = $emisor->calle;
				$num_emisor = $emisor->num_ext;
				$ciudad_emisor = $emisor->ciudad;
				$colonia_emisor = $emisor->colonia;
				$municipio_emisor = $emisor->municipio;
				$estado_emisor = $emisor->estado;
				$cp_emisor = $emisor->cp;
			}
		}

		echo " ID cliente = ".$idcliente."<br><br>";
		// OBTENIENDO LOS DATOS DEL RECEPTOR = = = = = = = = = = = = = = = = = = = = = = = =
		if ($inf_receptor = mysqli_query($mysqli, "SELECT a.rfc, a.razon_social, a.correo, a.pais, a.domicilio, a.num_ext, a.cp, a.colonia, b.estado, a.ciudad, a.municipio FROM comun_facturacion a INNER JOIN estados b ON a.estado = b.idestado WHERE nombre='$idcliente'")) {
			while ($receptor = $inf_receptor->fetch_object()) {
				$rfc_receptor = $receptor->rfc;
				$pais_receptor = normaliza($receptor->pais);
				$razon_receptor = normaliza($receptor->razon_social);
				$calle_receptor = normaliza(strtoupper($receptor->domicilio));
				$num_receptor = $receptor->num_ext;
				$ciudad_receptor = normaliza($receptor->ciudad);
				$colonia_receptor = normaliza($receptor->colonia);
				$municipio_receptor = normaliza($receptor->municipio);
				$estado_receptor = $receptor->estado;
				$cp_receptor = $receptor->cp;
				$correo_receptor = $receptor->correo;
			}
		}

		for ($co=0; $co<$cuantos; $co++) {
			$nombre = '';
			$idprod = '';
			$idventaprod = '';
			$nombre_prod = strtoupper($urlprods[$co]);
			$precio_prod = $precios[$co];
			$cant_venta = $cantidades[$co];
			$obs_producto = $observa[$co];
			$importe_prod = $cant_venta * $precio_prod;

			$conceptosOri.= '|' . $cantidades[$co];
			$conceptosOri.= '|' . 'No aplica';
			$conceptosOri.= '|' . strtoupper(trim($urlprods[$co]));
			$conceptosOri.= '|' . number_format($precios[$co], 2, '.', '');
			$conceptosOri.= '|' . number_format($importe_prod, 2, '.', '');
			$conceptos.= "<cfdi:Concepto cantidad='".$cant_venta."' unidad='No aplica' descripcion='".strtoupper(trim($nombre_prod))."' valorUnitario='".number_format($precio_prod, 2, '.', '')."' importe='".number_format($importe_prod, 2, '.', '')."'/>";
		}

		$azurian['Observacion']['Observacion'] = "";
		$azurian['org']['logo'] = '1461003427___sali-trans.png';

		// CORREO RECEPTOR = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
		$azurian['Correo']['Correo'] = strtolower($correo_receptor);

		// DATOS BASICOS = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
		$azurian['Basicos']['Moneda'] = "MXP";
		$azurian['Basicos']['metodoDePago'] = "Efectivo";
		$azurian['Basicos']['LugarExpedicion'] = "Mexico"; //$expedidaen;
		$azurian['Basicos']['version'] = "3.2";
		$azurian['Basicos']['serie'] = "";
		$azurian['Basicos']['folio'] = "";
		$azurian['Basicos']['fecha'] = $fecha_factura;
		$azurian['Basicos']['sello'] = '';
		$azurian['Basicos']['formaDePago'] = 'Pago en una sola exhibicion';
		$azurian['Basicos']['tipoDeComprobante'] = 'ingreso';
		$azurian['Basicos']['noCertificado'] = '';
		$azurian['Basicos']['certificado'] = '';
		$azurian['Basicos']['subTotal'] = number_format($importe,2, '.', '');
		$azurian['Basicos']['total'] = number_format($total, 2, '.', '');
		//$azurian['Basicos']['documento'] = 'Factura';
		
		$azurian['tipoFactura'] = 'factura';

		$azurian['Emisor']['rfc'] = $rfc_emisor;
		$azurian['Emisor']['nombre'] = $razon_emisor;
		
		// DATOS FISCALES EMISOR = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
		$azurian['FiscalesEmisor']['calle'] = $calle_emisor;
		$azurian['FiscalesEmisor']['noExterior'] = $num_emisor;
		$azurian['FiscalesEmisor']['colonia'] = $colonia_emisor;
		$azurian['FiscalesEmisor']['localidad'] = $ciudad_emisor;
		$azurian['FiscalesEmisor']['municipio'] = $municipio_emisor;
		$azurian['FiscalesEmisor']['estado'] = $estado_emisor;
		$azurian['FiscalesEmisor']['pais'] = $pais_emisor;
		$azurian['FiscalesEmisor']['codigoPostal'] = $cp_emisor;

		// DATOS REGIMEN = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
		$azurian['Regimen']['Regimen'] = 'REGIMEN CON ACTIVIDADES EMPRESARIALES Y PROFESIONALES';

		// DATOS RECEPTOR = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
		$azurian['Receptor']['rfc'] = strtoupper($rfc_receptor);
		$azurian['Receptor']['nombre'] = strtoupper($razon_receptor);

		// DOMICILIO RECEPTOR = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
		$azurian['DomicilioReceptor']['calle'] = $calle_receptor;
		$azurian['DomicilioReceptor']['noExterior'] = $num_receptor;
		$azurian['DomicilioReceptor']['colonia'] = $colonia_receptor;
		$azurian['DomicilioReceptor']['localidad'] = $ciudad_receptor;
		$azurian['DomicilioReceptor']['municipio'] = $municipio_receptor;
		$azurian['DomicilioReceptor']['estado'] = $estado_receptor;
		$azurian['DomicilioReceptor']['pais'] = $pais_receptor;
		$azurian['DomicilioReceptor']['codigoPostal'] = $cp_receptor;

		$azurian['Conceptos']['conceptos'] = $conceptos;
		$azurian['Conceptos']['conceptosOri'] = $conceptosOri;
	
		$traslads.='|IVA|';
		$traslads.='16.00|';
		$traslads.=number_format($iva, 2, '.', '');
		$trasladsimp+=number_format($iva, 2, '.', '');
		$trasxml.="<cfdi:Traslado impuesto='IVA' tasa='16.00' importe='" . number_format($iva, 2, '.', '') . "' />";

		$retenids.='|ISR|';
		//$retenids.='4.00|';
		$retenids.=number_format($retencion, 2, '.', '');
		$retenciones+=number_format($retencion, 2, '.', '');
		$retexml.="<cfdi:Retencion impuesto='ISR' importe='" . number_format($retencion, 2, '.', '') . "' />";

		$ivat.='<cfdi:Traslados>' . $trasxml . '</cfdi:Traslados>';
		$isr.='<cfdi:Retenciones>' . $retexml . '</cfdi:Retenciones>';

		$azurian['Impuestos']['totalImpuestosIeps'] = 0;
		$azurian['Impuestos']['isr'] = $retenids . '|' . number_format($retenciones, 2, '.', '');
		$azurian['Impuestos']['iva'] = $traslads . '|' . number_format($trasladsimp, 2, '.', '');
		$azurian['Impuestos']['totalImpuestosRetenidos'] = number_format($retenciones, 2, '.', '');
		$azurian['Impuestos']['totalImpuestosTrasladados'] = number_format($trasladsimp, 2, '.', '');		

		$ivas.=$isr.$ivat;
		$azurian['Impuestos']['ivas'] = $ivas;
		//print_r($azurian);  // Me sirve para ver lo que se esta cargando a la cadena original

		$azurianJson = json_encode($azurian);
		$azurianB64 = base64_encode($azurianJson);
		//echo '<br><br>'.$azurianB64;  // Sirve para ver lo que contiene la cadena codificada.

		//echo "<br><br> 6. Guardo en la tabla pvt_pendiente_factura...<br>";
		$insert_factura = "INSERT INTO pvt_pendienteFactura (id_sale, fecha, id_cliente, monto, facturado, factNum, cadenaoriginal, tipoComp) VALUES ('$idventa','$fecha_factura','$idcliente',$total, 0, 0,'$azurianB64','F')";
		$reg_factura_p = mysqli_query($mysqli, $insert_factura);

		include('../../lib/nusoap.php');
		include('../../SAT/funcionesSAT.php');

	}
//	echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
	echo "<br> <br> FACTURA GENERADA FAVOR DE CERRAR ESTA VENTANA...";
?>
