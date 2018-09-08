<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL
require("models/connection_sqli_manual.php"); // funciones mySQLi

class AlmacenModel extends Connection
{
	function organizacion()
	{
		$myQuery = "SELECT 	
						nombreorganizacion, 
						rfc, 
						logoempresa, 
						tipoinstancia 
					FROM 
						organizaciones 
					WHERE 
						idorganizacion = 1";

		$datos = $this->query($myQuery);
		$datos = $datos->fetch_assoc();
		return $datos;
	}

	function listaFacturas($vars)
	{
		$inicial = $vars['inicial'];
		$final = $vars['final'];
		if(intval($vars['asignadas']) == 1)
		{
			$where = "AND f.cancelada = 0 AND temporal = 1 AND json NOT LIKE '%TipoDeComprobante\":\"P\"%'";
			$asig = ", 0 AS asig ";
			if(intval($vars['tipo_facturas']) == 1)
				$where .= "AND tipo LIKE '%Ingreso%'";
			if(intval($vars['tipo_facturas']) == 2)
				$where .= "AND tipo LIKE '%Egreso%'";
			if(intval($vars['tipo_facturas']) == 3)
				$where .= "AND tipo LIKE '%Nomina%'";
			if($vars['rfc'] != '0')
				$where .= " AND rfc LIKE '%".$vars['rfc']."%'";
		}

		if(intval($vars['asignadas']) == 2)
		{
			$where = "AND f.cancelada = 0";
			$asig = ", 1 AS asig ";
		}
		
		if(intval($vars['asignadas']) == 3)
		{
			$where = "AND cancelada = 1";
			$asig = ", 0 AS asig ";
		}

		if(intval($vars['asignadas']) == 4)
		{
			$where = "AND f.cancelada = 0 AND temporal = 1 AND json LIKE '%TipoDeComprobante\":\"P\"%' AND version = '3.3'";
			$asig = ", 0 AS asig ";
			if($vars['rfc'] != '0')
				$where .= " AND rfc LIKE '%".$vars['rfc']."%'";
		}

		
		if(intval($vars['prov']))
		{
			$where .= "AND f.id NOT IN (SELECT id_factura FROM cont_facturas_poliza WHERE id_factura = f.id AND provision = 1 AND activo = 1) ";
		}
		

		$myQuery = "SELECT f.id, f.folio, f.json, f.serie, f.importe, f.version, f.uuid, f.xml, f.fecha, f.rfc, f.emisor, f.receptor, f.tipo, f.fecha_subida, f.moneda $asig 
					FROM cont_facturas f USE INDEX (idx_fecha)  
					WHERE 
					f.fecha BETWEEN '$inicial 00:00:00' AND '$final 23:59:59' $where ;";
		$res = $this->query($myQuery);
		return $res;
	}

	

	function asig($uuid)
	{
		$myQuery = "SELECT COUNT(1) AS num FROM cont_movimientos USE INDEX (idx_factura) WHERE Factura LIKE '%$uuid%' AND Activo = 1;";
		if($res = $this->query($myQuery))
		{
			$res = $res->fetch_assoc();
			$res = $res['num'];
		}
		else
			$res = 0;

		return $res;
	}

	function polizasAsignadas($uuid)
	{
		$myQuery = "SELECT p.id, p.numpol, p.idperiodo, (SELECT NombreEjercicio FROM cont_ejercicios WHERE Id = p.idejercicio) AS ejercicio 
					FROM cont_movimientos m 
					INNER JOIN cont_polizas p ON p.id = m.Idpoliza
					WHERE Factura LIKE '%$uuid%' AND m.Activo = 1 AND p.activo = 1
					GROUP BY m.IdPoliza

					UNION ALL 

					SELECT p.id, p.numpol, p.idperiodo, (SELECT NombreEjercicio FROM cont_ejercicios WHERE Id = p.idejercicio) AS ejercicio 
					FROM cont_grupo_facturas g
					INNER JOIN cont_polizas p ON p.id = g.IdPoliza
					WHERE UUID = '$uuid' AND p.activo = 1
					GROUP BY g.IdPoliza";
		$res = $this->query($myQuery);
		return $res;
	}

	function cuentas_diario()
	{
		$myQuery = "SELECT c.account_id, c.manual_code, c.description, d.codigo_agrupador, d.descripcion
					FROM cont_accounts c
					INNER JOIN cont_diarioficial d ON d.id = c.cuentaoficial
					WHERE c.removed = 0 AND c.main_account = 3 AND c.cuentaoficial != 0";
		$res = $this->query($myQuery);
		return $res;
	}

	function logo()
		{
			$myQuery = "SELECT logoempresa FROM organizaciones WHERE idorganizacion=1";
			$logo = $this->query($myQuery);
			$logo = $logo->fetch_assoc();
			return $logo['logoempresa'];
		}

	function cuentas($cp,$tipo)
	{
		if(!intval($cp))
		{
			$myQuery = "SELECT a.id, a.nombre, a.idcuenta, c.manual_code, c.description
						FROM cont_cuentas_default a
						LEFT JOIN cont_accounts c ON c.account_id = a.idcuenta 
						WHERE tipo = $tipo ORDER BY a.id";
		}
		else
		{
			$myQuery = "SELECT d.id, d.nombre, p.idcuenta, c.manual_code, c.description 
						FROM cont_cuentas_default d 
						LEFT JOIN cont_cuentas_per p ON p.idcampo = d.id AND p.idpersona = $cp 
						LEFT JOIN cont_accounts c ON c.account_id = p.idcuenta 
						WHERE d.tipo = $tipo ORDER BY d.id;";
		}
		
		$res = $this->query($myQuery);
		return $res;
	}

	function listaClientes()
	{
		$myQuery = "SELECT id, nombre,cuenta FROM comun_cliente WHERE borrado = 0";
		$res = $this->query($myQuery);
		return $res;
	}

	function listaProveedores()
	{
		$myQuery = "SELECT idPrv AS id, razon_social AS nombre, cuenta FROM mrp_proveedor WHERE status = -1";
		$res = $this->query($myQuery);
		return $res;
	}

	function guardar_cta($vars)
	{
		$myQuery = "SELECT id FROM cont_cuentas_per WHERE tipo = ".$vars['tipo']." AND idpersona = ".$vars['cp']." AND idcampo = ".$vars['id'];
		$res = $this->query($myQuery);
		$res = $res->num_rows;
		if(!intval($res) && intval($vars['idcuenta']))
		{
			$myQuery = "INSERT INTO cont_cuentas_per VALUES(0,".$vars['tipo'].",".$vars['cp'].",".$vars['id'].",".$vars['idcuenta'].");";
		}

		if(intval($res) && !intval($vars['idcuenta']))
		{
			$myQuery = "DELETE FROM cont_cuentas_per WHERE tipo = ".$vars['tipo']." AND idpersona = ".$vars['cp']." AND idcampo = ".$vars['id'];
		}

		if(intval($res) && intval($vars['idcuenta']))
		{
			$myQuery = "UPDATE cont_cuentas_per SET idcuenta = ".$vars['idcuenta']." WHERE tipo = ".$vars['tipo']." AND idpersona = ".$vars['cp']." AND idcampo = ".$vars['id'];
		}

		if(!intval($vars['cp']))
		{
			$myQuery = "UPDATE cont_cuentas_default SET idcuenta = ".$vars['idcuenta']." WHERE id = ".$vars['id'];
		}

		return $this->query($myQuery);
	}

	function yaExistePoliza($UUID)
	{
		$res = $this->query("SELECT COUNT(*) AS existe FROM cont_facturas WHERE uuid = '$UUID';");
		$res = $res->fetch_assoc();
		return $res['existe'];
	}

	function guardaFactura($vars)
	{
		$return = 0;
		if(strpos($vars['xml'],'facturas'))
		{
			$xmlnopath = explode('/',$vars['xml']);
			$vars['xml'] = $xmlnopath[3];
		}

		$myQuery = "INSERT INTO cont_facturas VALUES(0,'".$vars['folio']."','".$vars['uuid']."','".$vars['er']."','".$vars['tipo']."','".$vars['serie']."','".$this->quitar_tildes($vars['emisor'])."','".$this->quitar_tildes($vars['receptor'])."',".$vars['importe'].",'".$vars['moneda']."','".$vars['rfc']."','".$vars['fecha']."',DATE_SUB(NOW(), INTERVAL 6 HOUR),'".$this->quitar_tildes($vars['xml'])."','".$vars['version']."',".$vars['cancelada'].",'".$vars['json']."',".$vars['temporal'].",".$vars['origen'].");";
		if($this->query($myQuery))
		{
			if($vars['TipoDeComprobante'] == "P")
			{
				$i = 0;
				if(is_array($vars['IdDocumento']))
				{
					for($i=0;$i<=count($vars['IdDocumento'])-1;$i++)
						$this->insertarRelacion($vars,$i,1);
				}
				else
					$this->insertarRelacion($vars,$i,0);
			}
			$return = 1;
		}
		return $return;
		
	}

	function actFacturaTemp($vars)
	{
		$this->query("UPDATE cont_facturas SET temporal = ".$vars['temporal']." , cancelada = ".$vars['cancelada']." WHERE uuid LIKE '%".$vars['uuid']."%'");
		
		if($vars['TipoDeComprobante'] == "P")
		{
			$i = 0;
			if(is_array($vars['IdDocumento'])){
				for($i=0;$i<=count($vars['IdDocumento'])-1;$i++)
					$this->insertarRelacion($vars,$i,1);
			}
			else
				$this->insertarRelacion($vars,$i,0);
		}
	}

	function insertarRelacion($vars,$i,$arr)
	{
		if($arr)
			$myQuery = "INSERT IGNORE INTO cont_facturas_relacion VALUES('".$vars['uuid']."','".$vars['IdDocumento'][$i]."',".$vars['ImpPagado'][$i].",".$vars['ImpSaldoAnt'][$i].",".$vars['ImpSaldoInsoluto'][$i].",'".$vars['MonedaDR'][$i]."','".$vars['MetodoDePagoDR'][$i]."',".$vars['NumParcialidad'][$i].");";
		else
			$myQuery = "INSERT IGNORE INTO cont_facturas_relacion VALUES('".$vars['uuid']."','".$vars['IdDocumento']."',".$vars['ImpPagado'].",".$vars['ImpSaldoAnt'].",".$vars['ImpSaldoInsoluto'].",'".$vars['MonedaDR']."','".$vars['MetodoDePagoDR']."',".$vars['NumParcialidad'].");";

			$this->query($myQuery);
	}

	//UPDATE cont_facturas SET xml = REPLACE(xml,'xmls/facturas/temporales/', '');
	//UPDATE cont_facturas SET xml = REPLACE(xml,SUBSTRING_INDEX(xml, '/', 1), '');
	//SELECT* FROM cont_facturas WHERE id = 2;
	function quitar_tildes($cadena) {
		$no_permitidas= array ("\n","á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","Ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹","/","'","´",'"');
		$permitidas= array ("","a","e","i","o","u","A","E","I","O","U","n","N","A","A","I","O","U","A","A","A","A","A","A","c","C","A","e","A","A","A","A","AS","AZ","A","A","u","A","A","A","A","","O","A","A","A","","","","");
		$texto = str_replace($no_permitidas, $permitidas ,$cadena);
		return $texto;
	}

	function traerMovimientos()//Traer movimientos con facturas relacionadas
	{
		$myQuery = "SELECT m.IdPoliza, m.Factura FROM cont_movimientos m INNER JOIN cont_polizas p ON p.id = m.IdPoliza 
					WHERE m.Factura != '-' AND m.Factura != '' AND m.Activo = 1 AND p.activo = 1 
					GROUP BY m.IdPoliza, m.Factura 
					 
					UNION ALL

					SELECT gf.IdPoliza, gf.Factura FROM cont_grupo_facturas gf 
					INNER JOIN cont_polizas p ON p.id = gf.IdPoliza 
					WHERE p.activo = 1
					GROUP BY gf.IdPoliza, gf.Factura 
					ORDER BY IdPoliza;";
		return $this->query($myQuery);
	}

	function tieneRelacionPagos($uuid)
	{
		$myQuery = "SELECT parcialidades FROM cont_facturas_relacion USE INDEX (idx_uuid_relacionado) WHERE uuid_relacionado LIKE '%$uuid%'";
		$res = $this->query($myQuery);
		if(intval($res->num_rows))
			return 1;
		else
			return 0;
	}

	function verRelaciones($uuid)
	{
		$myQuery = "SELECT* FROM cont_facturas_relacion USE INDEX (idx_uuid_pago) WHERE uuid_pago LIKE '%$uuid%'";
		return $this->query($myQuery);
	}

	function quitaRutaFactura()
	{
		$this->query("UPDATE cont_facturas SET xml = REPLACE(xml,'xmls/facturas/temporales/', '') WHERE xml LIKE '%xmls/facturas/temporales/%';");
	}

	function datos_factura($xml,$tipofac,$provision)
	{
		if(intval($provision))
			$prepoliza = "cp.id_prepoliza";
		else
			$prepoliza = "cp.id_prepoliza_pagos";

		switch($tipofac)
		{
			case 1:
					$like = "Ingreso";
					$cliProv = "LEFT JOIN comun_cliente cp ON cp.rfc = f.rfc COLLATE utf8_general_ci ";
					$idcliprov = "cp.id AS cliprov";
					break;
			case 2:
					$like = "Egreso";
					$cliProv = "LEFT JOIN mrp_proveedor cp ON cp.rfc = f.rfc COLLATE utf8_general_ci ";
					$idcliprov = "cp.idPrv AS cliprov";
					break;
			case 3:
					$like = "Nomina";
					$cliProv = "LEFT JOIN nomi_empleados cp ON cp.rfc = f.rfc COLLATE utf8_general_ci ";
					$idcliprov = "cp.idEmpleado AS cliprov";
					break;					
		}
		$myQuery = "SELECT f.*, $idcliprov, cp.cuenta, cp.id_cuenta_gasto, tpl.id AS idTpl, 
					tpl.nombre_documento, tpl.id_tipo_poliza, tpl.nombre_poliza, tpl.provision, tpl.idsegmento, tpl.idsucursal  
					FROM cont_facturas f 
					$cliProv 
					INNER JOIN cont_tpl_polizas tpl ON tpl.id = $prepoliza
					WHERE f.tipo LIKE '%$like%' AND f.id = $xml ;";
		$res = $this->query($myQuery);
		return $res->fetch_assoc();
	}

	function numpol($datos_factura,$fecha)
	{
		$numpol = $this->query("SELECT pp.numpol+1 AS numpol FROM cont_polizas pp  WHERE pp.idtipopoliza = ".$datos_factura['id_tipo_poliza']." AND pp.activo = 1 AND pp.idejercicio = (SELECT Id FROM cont_ejercicios WHERE NombreEjercicio = ".$fecha[0].") AND pp.idperiodo = ".intval($fecha[1])." ORDER BY pp.numpol DESC LIMIT 1");
		$numpol = $numpol->fetch_assoc();
		return $numpol['numpol'];
	}

	function insertarPoliza($datos_factura,$fecha,$numpol,$xmls)
	{
		$concepto = "Factura(Prov):";
		$origen = "AlmacenProv";
		if(!intval($datos_factura['provision']))
		{	
			$concepto = "Factura(Pago):";
			$origen = "AlmacenPago";
		}

		$myQuery = "INSERT INTO cont_polizas(idorganizacion, idejercicio, idperiodo, numpol, idtipopoliza, concepto, origen, idorigen, fecha, fecha_creacion, activo, eliminado, pdv_aut, usuario_creacion, usuario_modificacion)
					 VALUES(1,(SELECT Id FROM cont_ejercicios WHERE NombreEjercicio = ".$fecha[0]."),".intval($fecha[1]).",$numpol,".$datos_factura['id_tipo_poliza'].",'".$datos_factura['nombre_poliza']." $concepto ".$datos_factura['folio']."','$origen',".$xmls.",'$fecha[0]-$fecha[1]-$fecha[2]',DATE_SUB(NOW(), INTERVAL 6 HOUR), 1, 0, 0, ".$_SESSION["accelog_idempleado"].", 0)";
		$idpoliza = $this->insert_id($myQuery);
		$this->query("INSERT INTO cont_facturas_poliza VALUES(0,$xmls,".$datos_factura['provision'].",$idpoliza,1)");
		return $idpoliza;
	}

	function insertarMovimientos($id_poliza_acontia,$datos_factura,$fecha,$xmls,$json)
	{
		$movs = "";
		$cuentas_poliza = $this->query("SELECT id_cuenta, tipo_movto, id_dato FROM cont_tpl_polizas_mov WHERE activo = 1 AND id_tpl_poliza = ".$datos_factura['idTpl']);
		$cont = 0;
		while($cp = $cuentas_poliza->fetch_assoc())
		{
			$cont++;
			//Cargo o abono
			if(intval($cp['tipo_movto']) == 1)
				$tipo_movto = "Abono";
			if(intval($cp['tipo_movto']) == 2)
				$tipo_movto = "Cargo";
			//dependiendo el tipo de dato sera el valor que tomara, en este caso solo existe el total del pago.
			$importe = 0;

			$tipo_cambio = 1;
			if(floatval($json['Comprobante']['@TipoCambio']) > 0)
				$tipo_cambio = $json['Comprobante']['@TipoCambio'];
			$subtotal = $ivas = 0;
			if($json['Comprobante']['@version'] == '3.2')
			{
				if($json['Comprobante']['@subTotal'])
					$subtotal = $json['Comprobante']['@subTotal'];
				if($json['Comprobante']['cfdi:Impuestos']['@totalImpuestosTrasladados'])
					$ivas = $json['Comprobante']['cfdi:Impuestos']['@totalImpuestosTrasladados'];
				$forma_pago = $json['Comprobante']['@metodoDePago'];
			}
			else
			{
				if($json['Comprobante']['@SubTotal'])
					$subtotal = $json['Comprobante']['@SubTotal'];
				if($json['Comprobante']['cfdi:Impuestos']['@TotalImpuestosTrasladados'])
					$ivas = $json['Comprobante']['cfdi:Impuestos']['@TotalImpuestosTrasladados'];
				$forma_pago = $json['Comprobante']['@FormaPago'];
			}

			if(!$ivas)
				$ivas = floatval($datos_factura['importe']) - floatval($subtotal);

			if(!$subtotal)
				$subtotal = floatval($datos_factura['importe']) - floatval($ivas);

			if($forma_pago == '99')
				$forma_pago = 24;
			else
				$forma_pago = "IFNULL((SELECT idFormapago FROM forma_pago WHERE claveSat = '$forma_pago'),24)";

			
			if(intval($cp['id_dato']) == 2 || intval($cp['id_dato']) == 6)
			{
				$importe = $subtotal;
			}
			elseif(intval($cp['id_dato']) == 3)
			{
				$importe = $ivas;
			}
			else
			{
				//Si es total, cliente o proveedor agrega el total en el importe
				$importe = $datos_factura['importe'];
			}
			//Si tiene cuenta de clientes busca si el id del cliente esta vinculado a una cuenta, si no es asi lo asignara a la cuenta configurada.
			if(intval($cp['id_dato']) == 4 || intval($cp['id_dato']) == 5)
			{
				if(intval($datos_factura['cuenta']))
					$cp['id_cuenta'] = $datos_factura['cuenta'];
			}

			if(intval($cp['id_dato']) == 6)
			{
				if(intval($datos_factura['id_cuenta_gasto']))
					$cp['id_cuenta'] = $datos_factura['id_cuenta_gasto'];
			}
			
			 $mov = $this->insert_id("INSERT INTO cont_movimientos(IdPoliza, NumMovto, IdSegmento, IdSucursal, Cuenta, TipoMovto, Importe, Referencia, Concepto, Activo, FechaCreacion, Factura, FormaPago, tipocambio) 
								VALUES($id_poliza_acontia, $cont, ".$datos_factura['idsegmento'].", ".$datos_factura['idsucursal'].", ".$cp['id_cuenta'].", '$tipo_movto', $importe, '".$datos_factura['uuid']."','".$datos_factura['xml']."', 1, DATE_SUB(NOW(), INTERVAL 6 HOUR), '".$datos_factura['xml']."', $forma_pago, $tipo_cambio);");
			 $movs .= $mov.",";
		}
		return $movs;
	}

	function getPolizasLista()
	{
		$myQuery = "SELECT* FROM cont_tpl_polizas;";
		return $this->query($myQuery);
	}

	function ListaSegmentos()
	{
		$myQuery = "SELECT* FROM cont_segmentos WHERE activo = -1;";
		return $this->query($myQuery);
	}

	function ListaSucursales()
	{
		$myQuery = "SELECT idSuc,nombre FROM mrp_sucursal WHERE activo = -1;";
		return $this->query($myQuery);
	}

	function ListaCuentas()
	{
		$myQuery = "SELECT account_id, manual_code, description FROM cont_accounts WHERE removed = 0 AND main_account = 3;";
		return $this->query($myQuery);
	}

	function  listaDatos()
	{
		return $this->query("SELECT* FROM app_tpl_datos;");
	}

	function getCuentasAsoc($idpoliza)
	{
		$myQuery = "SELECT pm.id, a.manual_code, a.description, pm.tipo_movto, (SELECT nombre FROM app_tpl_datos WHERE id = pm.id_dato) AS vinculacion, pm.id_dato 
					FROM cont_tpl_polizas_mov pm
					INNER JOIN cont_accounts a ON a.account_id = pm.id_cuenta 
					WHERE pm.id_tpl_poliza = $idpoliza AND activo = 1";
		return $this->query($myQuery);
	}

	function agregar_cuenta($vars)
	{
		//Es abono o Cargo?
		if(intval($vars['abono']) && !intval($vars['cargo']))
			$tipo_movto = 1;
		if(!intval($vars['abono']) && intval($vars['cargo']))
			$tipo_movto = 2;

		//Si no existe la cuenta asociada entonces es un registro nuevo, sino se tratará de una actualizacion
		if(!intval($vars['existe']))
			$myQuery = "INSERT INTO cont_tpl_polizas_mov (id, id_tpl_poliza, id_cuenta, tipo_movto, id_dato, nombre_impuesto, activo) VALUES(0, ".$vars['idpoliza'].", ".$vars['cuenta'].", $tipo_movto, ".$vars['vincular'].", '$nombre_impuesto',1);";
		else
			$myQuery = "UPDATE cont_tpl_polizas_mov SET id_cuenta = ".$vars['cuenta'].", tipo_movto = ".$tipo_movto.", id_dato = ".$vars['vincular'].", nombre_impuesto = '' WHERE id = ".$vars['existe'];

		//Guardar
		return $this->query($myQuery);
	}

	function datos_cuenta($id)
	{
		$myQuery = "SELECT* FROM cont_tpl_polizas_mov WHERE id = $id";
		return $this->query($myQuery);
	}

	function guardar_poliza($vars)
	{
		$documento = "Poliza de Pago";
		if($vars['provision'])
			$documento = "Poliza de Provision";

		if(intval($vars['idpoliza']))
		{
			//Si es una poliza existente actualiza
			$myQuery = "UPDATE cont_tpl_polizas SET nombre_documento = '$documento', id_tipo_poliza = ".$vars['tipo_pol'].", nombre_poliza = '".$vars['concepto']."', provision = ".$vars['provision'].", idsegmento = ".$vars['segmento'].", idsucursal = ".$vars['sucursal'].", fecha_mod = 'DATE_SUB(NOW(), INTERVAL 6 HOUR)'  WHERE id = ".$vars['idpoliza'];
			return $this->query($myQuery);
		}
		else
		{
			//Si no existe la poliza inserta el registro y actualiza los movimientos.
			$myQuery = "INSERT INTO cont_tpl_polizas VALUES(0,'$documento',".$vars['tipo_pol'].",'".$vars['concepto']."',".$vars['provision'].",".$vars['segmento'].",".$vars['sucursal'].",'')";
			$idpol = $this->insert_id($myQuery);

			$this->query("UPDATE cont_tpl_polizas_mov SET id_tpl_poliza = $idpol WHERE id_tpl_poliza = 0");
			return $idpol;

		}

		
	}

	function getInfoPoliza($id)
	{
		//Busca los datos y los regresa al controlador    
		return $this->query("SELECT* FROM cont_tpl_polizas WHERE id = ".$id);
	}

	function eliminar_poliza($idpoliza)
	{
		return $this->multi_query("DELETE FROM cont_tpl_polizas WHERE id = $idpoliza ; DELETE FROM cont_tpl_polizas_mov WHERE id_tpl_poliza = $idpoliza");
	}

	function eliminar_cuenta($idmov)
	{
		return $this->query("DELETE FROM cont_tpl_polizas_mov WHERE id = $idmov");
	}

	function creaCargo($datos_factura,$json)
	{
		$cp = 0;
		if($datos_factura['er'] == "R")
			$cp = 1;

		$moneda = 1;
		$tipo_cambio = 1;
		if($datos_factura['moneda'] == "USD" || $datos_factura['moneda'] == "2" || $datos_factura['moneda'] == "Dolares")
		{
			$moneda = 2;
			$tipo_cambio = $json['Comprobante']['@TipoCambio'];
		}

		$this->query("INSERT INTO app_pagos VALUES(0, $cp, ".$datos_factura['cliprov'].", ".$datos_factura['importe'].", 0, DATE_SUB(NOW(), INTERVAL 6 HOUR), 'cargo por factura ".$datos_factura['uuid']."', 1, $moneda, $tipo_cambio, 9, NULL,NULL,NULL);");
	}

	function pagaCargo($datos_factura,$json,$movs)
	{
		//Busca el cargo a pagar
		$res = $this->query("SELECT id FROM app_pagos WHERE concepto LIKE '%".$datos_factura['uuid']."%' AND origen = 9 ");
		$res = $res->fetch_assoc();
		
		$idcargo = 0;
		if(intval($res['id']))
			$idcargo = $res['id'];

		$cp = 0;
		if($datos_factura['er'] == "R")
			$cp = 1;

		$moneda = 1;
		$tipo_cambio = 1;
		if($datos_factura['moneda'] == "USD" || $datos_factura['moneda'] == "2" || $datos_factura['moneda'] == "Dolares")
		{
			$moneda = 2;
			$tipo_cambio = $json['Comprobante']['@TipoCambio'];
		}
		//Inserta el pago
		$pago = $this->insert_id("INSERT INTO app_pagos VALUES(0, $cp, ".$datos_factura['cliprov'].", 0, ".$datos_factura['importe'].", DATE_SUB(NOW(), INTERVAL 6 HOUR), 'pago a cargo ".$datos_factura['uuid']."', 1, $moneda, $tipo_cambio, 9, NULL,NUll,NULL);");

		//Relaciona el pago con el cargo y lo liquida
		$this->query("INSERT INTO app_pagos_relacion VALUES (0, $pago, 0, $idcargo, 0, ".$datos_factura['importe'].", '$movs');");
		//Actualiza la factura y la quita de temporales 
		$this->query("UPDATE cont_facturas SET temporal = 0 WHERE uuid = '".$datos_factura['uuid']."';");
	}
}
?>
