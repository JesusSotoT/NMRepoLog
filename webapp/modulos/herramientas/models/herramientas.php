<?php
if(array_key_exists("api", $_REQUEST)){		
	require ("../webapp/modulos/herramientas/models/connection_sqli.php");
} else {
	require ("models/connection_sqli.php");
}
// funciones mySQLi

class herramientasModel extends Connection {
///////////////// ******** ---- 				listar_instancias						------ ************ //////////////////
//////// Consulta las instancias y las devuelve en un array
	// Como parametros recibe:

	function listar_instancias($objeto) {
		$sql = "SELECT
					id, instancia, nombre_db AS db
				FROM
					customer
				WHERE
					status_instancia != 4";
		// return $sql;
		$result = $this -> queryArray($sql);
		
		return $result;
	}

///////////////// ******** ---- 				FIN listar_instancias					------ ************ //////////////////

///////////////// ******** ---- 				listar_proveedores						------ ************ //////////////////
//////// Consulta los proveedores y los devuelve en un array
	// Como parametros recibe:

	function listar_proveedores($objeto) {
		$sql = "SELECT
					idPrv, razon_social
				FROM
					mrp_proveedor
				WHERE
					status = 0";
		// return $sql;
		$result = $this -> queryArray($sql);
		
		return $result;
	}

///////////////// ******** ---- 				FIN listar_proveedores					------ ************ //////////////////

	///////////////// ******** ---- 				listar_clientes						------ ************ //////////////////
//////// Consulta los proveedores y los devuelve en un array
	// Como parametros recibe:

	function listar_clientes($objeto) {
		$sql = "SELECT
					id 
				FROM
					comun_cliente";
		// return $sql;
		$result = $this -> queryArray($sql);
		
		return $result;
	}

///////////////// ******** ---- 				FIN listar_clientes					------ ************ //////////////////

		///////////////// ******** ---- 				listar_cxc						------ ************ //////////////////
//////// Consulta los proveedores y los devuelve en un array
	// Como parametros recibe:

	function listar_cxc($objeto) {
		$sql = "SELECT
					id 
				FROM
					app_pagos
				WHERE 
					cobrar_pagar = 0;";
		// return $sql;
		$result = $this -> queryArray($sql);
		
		return $result;
	}

///////////////// ******** ---- 				FIN listar_cxc					------ ************ //////////////////

			///////////////// ******** ---- 				listar_cxp					------ ************ //////////////////
//////// Consulta los proveedores y los devuelve en un array
	// Como parametros recibe:

	function listar_cxp($objeto) {
		$sql = "SELECT
					id 
				FROM
					app_pagos
				WHERE 
					cobrar_pagar = 1;";
		// return $sql;
		$result = $this -> queryArray($sql);
		
		return $result;
	}

///////////////// ******** ---- 				FIN listar_cxp					------ ************ //////////////////

///////////////// ******** ---- 				listar_productos						------ ************ //////////////////
//////// Consulta los productos y los devuelve en un array
	// Como parametros recibe:

	function listar_productos($objeto) {
		$sql = "SELECT
					id, nombre
				FROM
					app_productos";
		// return $sql;
		$result = $this -> queryArray($sql);
		
		return $result;
	}

///////////////// ******** ---- 				FIN listar_productos					------ ************ //////////////////

///////////////// ******** ---- 				listar_unidades_medida					------ ************ //////////////////
//////// Consulta las unidades de medida y las devuelve en un array
	// Como parametros recibe:

	function listar_unidades_medida($objeto) {
		$sql = "SELECT
					id, nombre
				FROM
					app_unidades_medida";
		// return $sql;
		$result = $this -> queryArray($sql);
		
		return $result;
	}

///////////////// ******** ---- 				FIN listar_unidades_medida				------ ************ //////////////////

///////////////// ******** ---- 				listar_sucursales						------ ************ //////////////////
//////// Consulta las sucursales y las devuelve en un array
	// Como parametros recibe:

	function listar_suc($objeto) {
		$sql = "SELECT
					idSuc
				FROM
					mrp_sucursal where idSuc > 1";
		// return $sql;
		$result = $this -> queryArray($sql);
		
		return $result;
	}

///////////////// ******** ---- 				FIN listar_sucursales					------ ************ //////////////////

///////////////// ******** ---- 				listar_almacenes						------ ************ //////////////////
//////// Consulta los almacenes y las devuelve en un array
	// Como parametros recibe:

	function listar_alm($objeto) {
		$sql = "SELECT
					id
				FROM
					app_almacenes where id > 1";
		// return $sql;
		$result = $this -> queryArray($sql);
		
		return $result;
	}

///////////////// ******** ---- 				FIN listar_almacenes					------ ************ //////////////////

///////////////// ******** ---- 				listar_movimientos(exis)						------ ************ //////////////////
//////// Consulta los movimientos y las devuelve en un array
	// Como parametros recibe:

	function listar_exi($objeto) {
		$sql = "SELECT
					id
				FROM
					app_inventario_movimientos";
		// return $sql;
		$result = $this -> queryArray($sql);
		
		return $result;
	}

///////////////// ******** ---- 				FIN listar_movimientos					------ ************ //////////////////

///////////////// ******** ---- 				mudar_proveedores						------ ************ //////////////////
//////// Muda los proveedores de la version vieja a la nueva
	// Como parametros recibe:
		// instancia_vieja -> Instancia de donde obtenemos la informacion
		// instancia_nueva -> Instancia donde guardamos la informacion
		
	function mudar_proveedores($objeto) {
		if( $objeto['version'] == '2' ) {
			$sql = "INSERT IGNORE INTO 
					".$objeto['instancia_nueva'].".mrp_proveedor
				(Select  *
				FROM ".$objeto['instancia_vieja'].".mrp_proveedor);
				
				INSERT IGNORE INTO 
					".$objeto['instancia_nueva'].".app_producto_proveedor 
					(SELECT * FROM ".$objeto['instancia_vieja'].".app_producto_proveedor);
				
				INSERT IGNORE INTO 
					".$objeto['instancia_nueva'].".app_costos_proveedor 
					(SELECT * FROM ".$objeto['instancia_vieja'].".app_costos_proveedor);";
		} else {
			$sql = "INSERT IGNORE INTO 
					".$objeto['instancia_nueva'].".mrp_proveedor(idPrv, razon_social, rfc, domicilio, telefono, email, 
					web, diascredito, idestado, idmunicipio, legal, precioycalidad, disponibilidad, idtipotercero, 
					idtipoperacion, curp, cuenta, numidfiscal, nombrextranjero, PaisdeResidencia, nacionalidad, 
					ivaretenido, isretenido, idTasaPrvasumir, idtipoiva, idIETU, ImOtSis, idtipo)
				(Select  idPrv, razon_social, rfc, domicilio, telefono, email, web, diascredito, idestado, idmunicipio, 
					legal, precioycalidad, disponibilidad, idtipotercero, idtipoperacion, curp, cuenta, numidfiscal, 
					nombrextranjero, PaisdeResidencia, nacionalidad, ivaretenido, isretenido, idTasaPrvasumir, idtipoiva, 
					idIETU, ImOtSis, idtipo 
				FROM ".$objeto['instancia_vieja'].".mrp_proveedor);
				
				INSERT IGNORE INTO 
					".$objeto['instancia_nueva'].".app_producto_proveedor(id, id_producto, id_proveedor, id_unidad)
					(SELECT id, idProducto, idPrv, idUni FROM ".$objeto['instancia_vieja'].".mrp_producto_proveedor);
				
				INSERT IGNORE INTO 
					".$objeto['instancia_nueva'].".app_costos_proveedor(id_proveedor, id_producto, id_moneda, costo, fecha)
					(SELECT idPrv, idProducto, 1, costo, curdate() FROM ".$objeto['instancia_vieja'].".mrp_producto_proveedor);";
		}
		// return $sql;
		$result = $this -> dataTransact($sql);
		
		return $result;
	}

///////////////// ******** ---- 				FIN mudar_proveedores					------ ************ //////////////////

///////////////// ******** ---- 				mudar_cxc						------ ************ //////////////////
//////// Muda los proveedores de la version vieja a la nueva
	// Como parametros recibe:
		// instancia_vieja -> Instancia de donde obtenemos la informacion
		// instancia_nueva -> Instancia donde guardamos la informacion
		
	function mudar_cxc($objeto) {
		$bd = $objeto['instancia_nueva'];
		$bd2 = $objeto['instancia_vieja'];
		if( $objeto['version'] == '2' ) {
			$myQuery = "INSERT IGNORE INTO $bd.app_pagos (SELECT * FROM $bd2.app_pagos); 
						INSERT IGNORE INTO $bd.app_pagos_relacion (SELECT * FROM $bd2.app_pagos_relacion);

						INSERT IGNORE INTO $bd.app_respuestaFacturacion (SELECT * FROM $bd2.app_respuestaFacturacion);
						INSERT IGNORE INTO $bd.app_respuestaFacturacion (SELECT * FROM $bd2.app_respuestaFacturacion);
						INSERT IGNORE INTO $bd.app_pos_venta (SELECT * FROM $bd2.app_pos_venta);
						INSERT IGNORE INTO $bd.app_pos_venta_pagos (SELECT * FROM $bd2.app_pos_venta_pagos);
						INSERT IGNORE INTO $bd.app_pos_venta_producto (SELECT * FROM $bd2.app_pos_venta_producto);
						INSERT IGNORE INTO $bd.app_pos_venta_producto_impuesto (SELECT * FROM $bd2.app_pos_venta_producto_impuesto);
						INSERT IGNORE INTO $bd.app_pos_venta_suspendida (SELECT * FROM $bd2.app_pos_venta_suspendida);
						";
		} else {
			$myQuery = "INSERT IGNORE INTO $bd.app_pagos(cobrar_pagar, id_prov_cli, cargo, abono, fecha_pago, concepto, id_forma_pago, id_moneda, tipo_cambio, origen) ";
	            
	        $myQuery .= "SELECT 0, idCliente, saldoactual, 0, fechacargo, CONCAT(concepto,' (VER ANT) ID VENTA: ',idVenta) AS concepto, 1, 1, 1, 0
	                     FROM $bd2.cxc
	                     WHERE
	                     estatus = 0 AND saldoactual > 0 AND idCliente > 0;";
		}
		// return $sql;
		$result = $this -> dataTransact($myQuery);
		return $result;
	}

///////////////// ******** ---- 				FIN mudar_cxc					------ ************ //////////////////

	///////////////// ******** ---- 				mudar_cxp						------ ************ //////////////////
//////// Muda los proveedores de la version vieja a la nueva
	// Como parametros recibe:
		// instancia_vieja -> Instancia de donde obtenemos la informacion
		// instancia_nueva -> Instancia donde guardamos la informacion
		
	function mudar_cxp($objeto) {
		$bd = $objeto['instancia_nueva'];
		$bd2 = $objeto['instancia_vieja'];
		if( $objeto['version'] == '2' ) {
			$myQuery = "INSERT IGNORE INTO $bd.app_pagos (SELECT * FROM $bd2.app_pagos); 
						INSERT INTO $bd.app_pagos (SELECT * FROM $bd2.app_pagos_relacion);

						INSERT IGNORE INTO $bd.app_ocompra (SELECT * FROM $bd2.app_ocompra);
						INSERT IGNORE INTO $bd.app_requisiciones (SELECT * FROM $bd2.app_requisiciones);
						INSERT IGNORE INTO $bd.app_recepcion_xml (SELECT * FROM $bd2.app_recepcion_xml);
						INSERT IGNORE INTO $bd.mrp_proveedor (SELECT * FROM $bd2.mrp_proveedor);
						";
		} else {
			$myQuery = "INSERT IGNORE INTO $bd.app_pagos(cobrar_pagar, id_prov_cli, cargo, abono, fecha_pago, concepto, id_forma_pago, id_moneda, tipo_cambio, origen) ";
	            
	        $myQuery .= "SELECT 1, idProveedor, saldoactual, 0, fechacargo, CONCAT(concepto,' (VER ANT)') AS concepto, 1, 1, 1, 0
	                            FROM $bd2.cxp
	                            WHERE
	                            estatus = 0 AND saldoactual > 0 AND idProveedor > 0;";
		}
		// return $sql;
		$result = $this -> dataTransact($myQuery);
		return $result;
	}

///////////////// ******** ---- 				FIN mudar_cxp					------ ************ //////////////////

///////////////// ******** ---- 				mudar_clientes						------ ************ //////////////////
//////// Muda los proveedores de la version vieja a la nueva
	// Como parametros recibe:
		// instancia_vieja -> Instancia de donde obtenemos la informacion
		// instancia_nueva -> Instancia donde guardamos la informacion
		
	function mudar_clientes($objeto) {
		if( $objeto['version'] == '2' ) {
			$sql = "INSERT IGNORE INTO ".$objeto['instancia_nueva'].".comun_cliente 
			(SELECT * FROM ".$objeto['instancia_vieja'].".comun_cliente);

			INSERT INTO {$objeto['instancia_nueva']}.comun_facturacion 
			(SELECT * FROM {$objeto['instancia_vieja']}.comun_facturacion);
			";
		} else {
			$sql = "INSERT IGNORE INTO ".$objeto['instancia_nueva'].".comun_cliente (id, nombre, nombretienda, direccion, colonia, idTipotienda, idRubro, idGiro, idPromotor, idRuta, email, celular, cp, idPais, idEstado, idMunicipio, escliente, contacto, id_tipo_fiscal, rfc, telefono1, telefono2, tipo_persona, borrado, limite_credito, dias_credito, cuenta, ciudad, cumpleanos) 
			(SELECT id, nombre, nombretienda, direccion, colonia, idTipotienda, idRubro, idGiro, idPromotor, idRuta, email, celular, cp, idPais, idEstado, idMunicipio, escliente, contacto, id_tipo_fiscal, rfc, telefono1, telefono2, tipo_persona, borrado, limite_credito, dias_credito, cuenta, ciudad, cumpleanos FROM ".$objeto['instancia_vieja'].".comun_cliente);

			INSERT INTO {$objeto['instancia_nueva']}.comun_facturacion (id, nombre, rfc, razon_social, correo, pais, regimen_fiscal, domicilio, num_ext, cp, colonia, idPais, estado, ciudad, municipio, cliPro)
				(SELECT * FROM {$objeto['instancia_vieja']}.comun_facturacion);
			";
		}
		
		//return $sql;
		$result = $this -> dataTransact($sql);
		return $result;
	}

///////////////// ******** ---- 				FIN mudar_clientes					------ ************ //////////////////

///////////////// ******** ---- 				mudar_productos							------ ************ //////////////////
//////// Muda los productos de la version vieja a la nueva
	// Como parametros recibe:
		// instancia_vieja -> Instancia de donde obtenemos la informacion
		// instancia_nueva -> Instancia donde guardamos la informacion
		
	function mudar_productos($objeto) {
		if( $objeto['version'] == '2' ) {
			$sql = "INSERT IGNORE INTO ".$objeto['instancia_nueva'].".app_departamento
				(SELECT * FROM ".$objeto['instancia_vieja'].".app_departamento);
		
				INSERT IGNORE INTO ".$objeto['instancia_nueva'].".app_familia
				(SELECT * FROM ".$objeto['instancia_vieja'].".app_familia);
				
				INSERT IGNORE INTO ".$objeto['instancia_nueva'].".app_linea
				(id, nombre, id_familia, activo)
				(SELECT * FROM ".$objeto['instancia_vieja'].".app_linea);
				
				INSERT IGNORE INTO ".$objeto['instancia_nueva'].".app_producto_impuesto
				(SELECT * FROM ".$objeto['instancia_vieja'].".app_producto_impuesto);
				
				INSERT IGNORE INTO ".$objeto['instancia_nueva'].".app_productos
				(SELECT * FROM ".$objeto['instancia_vieja'].".app_productos );

				INSERT IGNORE INTO ".$objeto['instancia_nueva'].".app_campos_foodware
				(SELECT * FROM ".$objeto['instancia_vieja'].".app_campos_foodware);

				INSERT IGNORE INTO ".$objeto['instancia_nueva'].".app_costos_proveedor
				(SELECT * FROM ".$objeto['instancia_vieja'].".app_costos_proveedor);

				INSERT IGNORE INTO ".$objeto['instancia_nueva'].".app_lista_precio_prods
				(SELECT * FROM ".$objeto['instancia_vieja'].".app_lista_precio_prods);

				INSERT IGNORE INTO ".$objeto['instancia_nueva'].".app_precio_sucursal
				(SELECT * FROM ".$objeto['instancia_vieja'].".app_precio_sucursal);

				INSERT IGNORE INTO ".$objeto['instancia_nueva'].".app_comision_productos
				(SELECT * FROM ".$objeto['instancia_vieja'].".app_comision_productos);

				INSERT IGNORE INTO ".$objeto['instancia_nueva'].".app_producto_caracteristicas
				(SELECT * FROM ".$objeto['instancia_vieja'].".app_producto_caracteristicas);

				INSERT IGNORE INTO ".$objeto['instancia_nueva'].".app_caracteristicas_padre
				(SELECT * FROM ".$objeto['instancia_vieja'].".app_caracteristicas_padre );

				INSERT IGNORE INTO ".$objeto['instancia_nueva'].".app_caracteristicas_hija
				(SELECT * FROM ".$objeto['instancia_vieja'].".app_caracteristicas_hija );
				
				";
		} else {
			$sql = "INSERT IGNORE INTO ".$objeto['instancia_nueva'].".app_departamento
				(SELECT idDep, nombre FROM ".$objeto['instancia_vieja'].".mrp_departamento);
		
				INSERT IGNORE INTO ".$objeto['instancia_nueva'].".app_familia
				(SELECT * FROM ".$objeto['instancia_vieja'].".mrp_familia);
				
				INSERT IGNORE INTO ".$objeto['instancia_nueva'].".app_linea
				(id, nombre, id_familia, activo)
				(SELECT idLin, nombre, idFam, 1 AS activo FROM ".$objeto['instancia_vieja'].".mrp_linea);
				
				INSERT IGNORE INTO ".$objeto['instancia_nueva'].".app_producto_impuesto
				(SELECT * FROM ".$objeto['instancia_vieja'].".producto_impuesto);
				
				INSERT IGNORE INTO 
					".$objeto['instancia_nueva'].".app_productos
					(id, codigo, nombre, precio, descripcion_corta, descripcion_larga, ruta_imagen, tipo_producto, maximos, 
					minimos, departamento, familia, linea, inventariable, id_unidad_venta, status, id_unidad_compra)
					(SELECT
						p.idProducto, p.codigo, p.nombre, p.precioventa, p.descorta, p.deslarga, p.imagen, 
						CASE p.tipo_producto
						    WHEN '1' THEN '1'
						    WHEN '2' THEN '8'
						    WHEN '3' THEN '3'
						    WHEN '4' THEN '6'
						    WHEN '5' THEN '9'
						    WHEN '6' THEN '2'
						END tipo_producto,
						p.maximo, p.minimo, d.idDep, f.idFam, l.idLin, p.vendible, p.idunidad, p.estatus, p.idunidadCompra
					FROM
						".$objeto['instancia_vieja'].".mrp_producto p
					LEFT JOIN
							".$objeto['instancia_vieja'].".mrp_linea l
						ON
							l.idLin = p.idLinea
					LEFT JOIN
							".$objeto['instancia_vieja'].".mrp_familia f
						ON
							f.idFam = l.idFam
					LEFT JOIN
							".$objeto['instancia_vieja'].".mrp_departamento d
						ON
							d.idDep = f.idDep);
				
				INSERT IGNORE INTO ".$objeto['instancia_nueva'].".app_campos_foodware(id_producto)
				(SELECT idProducto FROM ".$objeto['instancia_vieja'].".mrp_producto);";
		}
		
		//return $sql;
		$result = $this -> dataTransact($sql);
		
		return $result;
	}

///////////////// ******** ---- 				FIN mudar_productos						------ ************ //////////////////

///////////////// ******** ---- 				mudar_unidades							------ ************ //////////////////
	// Como parametros recibe:
		// instancia_vieja -> Instancia de donde obtenemos la informacion
		// instancia_nueva -> Instancia donde guardamos la informacion
		
	function mudar_unidades($objeto) {
		/*$sql = "INSERT INTO 
					".$objeto['instancia_nueva'].".app_unidades_medida(id, nombre, factor, unidad_base, activo)
					(SELECT idUni, compuesto, conversion, unidad, 1 FROM ".$objeto['instancia_vieja'].".mrp_unidades);";*/
		if( $objeto['version'] == '2' ) {
			$sql = "SET foreign_key_checks = 0;
				TRUNCATE TABLE {$objeto['instancia_nueva']}.app_unidades_medida;
				SET foreign_key_checks = 1;
				INSERT IGNORE INTO {$objeto['instancia_nueva']}.app_unidades_medida 
				(SELECT *	FROM	{$objeto['instancia_vieja']}.app_unidades_medida);";
		} else {
			$sql = "SET foreign_key_checks = 0;
				TRUNCATE TABLE {$objeto['instancia_nueva']}.app_unidades_medida;
				SET foreign_key_checks = 1;
				INSERT INTO {$objeto['instancia_nueva']}.app_unidades_medida (id, clave, nombre, factor, unidad_base, activo, codigo_sat)
					 (SELECT	idUni id, SUBSTR( UPPER(compuesto) , 1,2) clave, compuesto nombre, conversion factor, IF(unidad=idUni, 0, unidad) unidad_base, '1' activo, NULL codigo_sat
						FROM	{$objeto['instancia_vieja']}.mrp_unidades);";
		}
		
		// return $sql;
		$result = $this -> dataTransact($sql);
		
		return $result;
	}

///////////////// ******** ---- 				FIN mudar_unidades						------ ************ //////////////////

///////////////// ******** ---- 				mudar_alm						------ ************ //////////////////
	// Como parametros recibe:
		// instancia_vieja -> Instancia de donde obtenemos la informacion
		// instancia_nueva -> Instancia donde guardamos la informacion
		
	function mudar_alm($objeto) {
		$bd = $objeto['instancia_nueva'];
		$bd2 = $objeto['instancia_vieja'];

		if( $objeto['version'] == '2' ) {
			$sql2 = "INSERT IGNORE INTO $bd.almacen(idAlmacen, nombre, idEstado, idmunicipio) ";
			$sql2 .= "(SELECT idAlmacen, nombre, idEstado, idmunicipio FROM $bd2.almacen where idAlmacen > 1)";

			$result2 = $this -> dataTransact($sql2);

			$myQuery = "INSERT IGNORE INTO $bd.app_almacenes ";  
	        $myQuery .= "(SELECT *   FROM $bd2.app_almacenes )";
			// return $sql2;
			$result = $this -> dataTransact($myQuery);
		} else {
			$sql2 = "INSERT IGNORE INTO $bd.almacen(idAlmacen, nombre, idEstado, idmunicipio) ";
			$sql2 .= "(SELECT idAlmacen, nombre, idEstado, idmunicipio FROM $bd2.almacen where idAlmacen > 1)";

			$result2 = $this -> dataTransact($sql2);

			$myQuery = "INSERT IGNORE INTO $bd.app_almacenes(id, codigo_sistema, nombre, id_padre, id_sucursal, id_estado, id_municipio, id_almacen_tipo, id_clasificador, activo) ";
	            
	        $myQuery .= "(SELECT idAlmacen, idAlmacen, nombre, 0, 1, idEstado, idmunicipio, 1, 1, 1   FROM $bd2.almacen where idAlmacen > 1)";
			// return $sql2;
			$result = $this -> dataTransact($myQuery);
		}
		
		return $result;
	}

///////////////// ******** ---- 				FIN mudar_alm					------ ************ //////////////////

///////////////// ******** ---- 				mudar_suc						------ ************ //////////////////
	// Como parametros recibe:
		// instancia_vieja -> Instancia de donde obtenemos la informacion
		// instancia_nueva -> Instancia donde guardamos la informacion
		
	function mudar_suc($objeto) {
		$bd = $objeto['instancia_nueva'];
		$bd2 = $objeto['instancia_vieja'];
		$myQuery = "INSERT IGNORE INTO $bd.mrp_sucursal(idSuc, nombre, direccion, idEstado, idMunicipio, cp, tel_contacto, contacto, idorganizacion, idAlmacen, activo) ";
            
        $myQuery .= "(SELECT idSuc, nombre, direccion, idEstado, idMunicipio, cp, tel_contacto, contacto, idorganizacion, idAlmacen, -1 FROM $bd2.mrp_sucursal where idSuc > 1)";
		// return $myQuery;
		$result = $this -> dataTransact($myQuery);
		return $result;
	}

///////////////// ******** ---- 				FIN mudar_suc					------ ************ //////////////////

///////////////// ******** ---- 				mudar_exis						------ ************ //////////////////
	// Como parametros recibe:
		// instancia_vieja -> Instancia de donde obtenemos la informacion
		// instancia_nueva -> Instancia donde guardamos la informacion
		
	function mudar_exi($objeto) {
		$bd = $objeto['instancia_nueva'];
		$bd2 = $objeto['instancia_vieja'];
		if( $objeto['version'] == '2' ) {
			$myQuery = "INSERT INTO $bd.app_inventario_movimientos (SELECT * FROM $bd2.app_inventario_movimientos);";
		} else {
			$fecha = date("Y-m-d H:i:s");
			$myQuery = "INSERT IGNORE INTO $bd.app_inventario_movimientos (id_producto, cantidad, id_almacen_destino, fecha, tipo_traspaso, origen) ";
            
        	$myQuery .= "(SELECT idProducto, cantidad, idAlmacen, '$fecha', 1, 1 FROM $bd2.mrp_stock);";
		}
		
        //return $myQuery;
		$result = $this -> dataTransact($myQuery);
		return $result;
	}

///////////////// ******** ---- 				FIN mudar_exis					------ ************ //////////////////



} // Fin Clase herramientasModel

?>