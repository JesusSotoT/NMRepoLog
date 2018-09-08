<?php
if(array_key_exists("api", $_REQUEST)){		
	require ("../webapp/modulos/restaurantes/models/connection_sqli.php");
} else {
	require ("models/connection_sqli.php");
}
// funciones mySQLi

session_start();
class comandasModel extends Connection {
///////////////// ******** ---- 		getTables		------ ************ //////////////////
//////// Consulta las mesas y las regresa en un array
	// Como parametros recibe:
	// id -> id de la mesa
 
	function getTables($objeto) {
		if (!empty($objeto['sucursal'])) {
			$sucursal = $objeto['sucursal'];
		} else {
		// Obtiene la sucursal
			session_start();
			$sucursal = "	SELECT DISTINCT
								mp.idSuc AS id 
							FROM 
								administracion_usuarios au 
							INNER JOIN 
									mrp_sucursal mp 
								ON 
									mp.idSuc = au.idSuc 
							WHERE 
								au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
							LIMIT 1";
			$sucursal = $this -> queryArray($sucursal);
			$sucursal = $sucursal['rows'][0]['id'];
		}
		
	// Filtra por departamento
		$condicion .= (!empty($objeto['id'])) ? ' AND a.idDep=\'' . $objeto['id'] . '\' ' : '';
	// Filtra por los permisos del mesero
		$condicion .= (!empty($objeto['permisos'])) ? ' AND 
															(a.id_mesa IN(' . $objeto['permisos'] . ')
																OR
															a.tipo != 0)' : '';
		// Filtra por las asignaciones del mesero
		$condicion .= (!empty($objeto['asignacion'])) ? ' AND a.id_mesa IN(' . $objeto['asignacion'] . ')' : '';
		// Filtra para que no se muestren las mesas de servicio a domicilio y para llevar
		$condicion .= ($objeto['asignar'] == 1) ? ' AND a.tipo=0' : '';

		if(!$objeto["noJuntas"]){
			 $sql = "SELECT 
						a.id_mesa AS mesa, res.id as id_res, a.x, a.y, a.width as width_barra, s.nombre as sucursal, a.id_area, a.height as height_barra, b.nombre, b.idDep, a.personas, a.status as mesa_status, a.tipo, a.domicilio, a.idempleado,
						ad.nombreusuario AS mesero, a.notificacion, tm.id as id_tipo_mesa, tm.tipo_mesa, tm.width, tm.height, tm.imagen,
						IF(GROUP_CONCAT(c.idmesa) is NULL, a.nombre, (SELECT 
																			GROUP_CONCAT(d.nombre)
																		FROM
																			com_mesas d
																		INNER JOIN
																				com_union c
																			ON
																				c.idmesa=d.id_mesa
																		WHERE
																			c.idprincipal = a.id_mesa
																		))
							nombre_mesa,
						if(GROUP_CONCAT(c.idmesa) is NULL,'',GROUP_CONCAT(c.idmesa)) 
							idmesas, 
						if(GROUP_CONCAT(d.personas) is NULL,'',GROUP_CONCAT(d.personas)) 
							mpersonas, 
						if(e.id is NULL,0,e.id) 
							idcomanda, a.tipo mesaTipo 
					FROM 
						com_mesas a
					LEFT JOIN
							administracion_usuarios ad
						ON
							ad.idempleado = a.idempleado
					LEFT JOIN 
							mrp_departamento b 
						ON 
							b.idDep = a.idDep 
					LEFT JOIN 
							mrp_sucursal s 
						ON 
							s.idSuc = a.idSuc 
					LEFT JOIN 
							com_union c 
						ON 
							c.idprincipal = a.id_mesa 
					LEFT JOIN 
							com_mesas d 
						ON 
							d.id_mesa = c.idmesa 
					LEFT JOIN 
							com_comandas e 
						ON 
							e.idmesa = a.id_mesa 
						AND 
							e.status = 0 
					LEFT JOIN 
							com_reservaciones res 
						ON 
							res.mesa = a.id_mesa 
						AND 
							res.activo = 1
						AND 
							(res.inicio >= '".$objeto['f_ini']."' 
						AND 
							res.inicio <= '".$objeto['f_fin']."')
					JOIN 
						com_tipo_mesas tm ON a.tipo_mesa = tm.id
					WHERE
						a.status = 1
					AND (
							a.id_mesa 
								NOT IN(select idmesa from com_union) 
						OR 
							a.id_mesa 
								IN(select idprincipal from com_union) 
					)" . $condicion . " 
					AND
						a.id_dependencia = 0
					AND
						a.idSuc = " . $sucursal ."
					OR
						a.status = 4
					AND (
							a.id_mesa 
								NOT IN(select idmesa from com_union) 
						OR 
							a.id_mesa 
								IN(select idprincipal from com_union) 
					)" . $condicion . " 
					AND
						a.id_dependencia = 0
					AND
						a.idSuc = " . $sucursal ."
					GROUP BY 
						a.id_mesa 
					ORDER BY 
						a.id_mesa asc";
		} else {
			$sql = "SELECT
						a.id_mesa AS mesa, a.x, a.y, a.width as width_barra, s.nombre as sucursal, a.id_area, a.height as height_barra, b.nombre, a.personas, a.status as mesa_status, a.domicilio, a.idempleado,
						ad.nombreusuario AS mesero, a.notificacion, a.nombre as nombre_mesa, if(e.id is NULL,0,e.id) idcomanda,
						tm.id as id_tipo_mesa, tm.tipo_mesa, tm.width, tm.height, tm.imagen 
					FROM 
						com_mesas a
					LEFT JOIN
							administracion_usuarios ad
						ON
							ad.idempleado = a.idempleado
					LEFT JOIN 
							mrp_departamento b 
						ON 
							b.idDep = a.idDep 
					LEFT JOIN 
							mrp_sucursal s 
						ON 
							s.idSuc = a.idSuc 
					LEFT JOIN 
							com_mesas d 
						ON 
							d.id_mesa = a.id_mesa 
					LEFT JOIN 
							com_comandas e 
						ON 
							e.idmesa = a.id_mesa 
						AND 
							e.status = 0 
					JOIN 
						com_tipo_mesas tm ON a.tipo_mesa = tm.id
					WHERE
						a.status = 1 
					AND
						a.tipo = 0 
					AND 
						a.id_dependencia = 0
						" . $condicion . " 
					AND
						a.idSuc = " . $sucursal ."
					OR
						a.status = 4 
					and
						a.tipo = 0 
					AND 
						a.id_dependencia = 0
						" . $condicion . " 
					AND
						a.idSuc = " . $sucursal ."
					GROUP BY 
						a.id_mesa 
					ORDER BY 
						a.id_mesa asc";
		}
		$tablesComanda = $this -> queryArray($sql);

		//echo $sql;
		return $tablesComanda;
	}

///////////////// ******** ---- 		FIN getTables		------ ************ //////////////////

///////////////// ******** ---- 	set_correo_archivos		------ ************ //////////////////
//////// Obtiene los pedidos de la persona y los regresa en un array
	// Como parametros puede recibir:
	// 	$tipo -> 1: promocion - 2: felicitaciones
	//	$direccion -> direccion de la imagen
	function set_correo_archivos($tipo, $direccion) {
		if($tipo == 1)
			$set = " imagen_promo = '" . $direccion . "' ";
		else if($tipo == 2)
			$set = " imagen_felicitaciones = '" . $direccion . "' ";
		else if($tipo == 3)
			$set = " menu_digital = '" . $direccion . "' ";
		else if($tipo == 4)
			$set = " logo_empresa = '" . $direccion . "' ";
		$sql = "UPDATE 
					com_configuracion
				SET 
					".$set."
				WHERE 
					id= 1";
		$lala = $this -> query($sql);

		return $lala;
	}
///////////////// ******** ---- 	FIN set_correo_archivos		------ ************ //////////////////



///////////////////////////////


		function getTables2($objeto) {
		if (!empty($objeto['sucursal'])) {
			$sucursal = $objeto['sucursal'];
		} else {
		// Obtiene la sucursal
			session_start();
			$sucursal = "	SELECT DISTINCT mp.idSuc AS id 
							FROM administracion_usuarios au 
							INNER JOIN mrp_sucursal mp 
							ON mp.idSuc = au.idSuc 
							WHERE au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
							LIMIT 1";
			$sucursal = $this -> queryArray($sucursal);
			$sucursal = $sucursal['rows'][0]['id'];
		}
		
	// Filtra por departamento
		$condicion .= (!empty($objeto['id'])) ? ' AND a.idDep=\'' . $objeto['id'] . '\' ' : '';
	// Filtra por los permisos del mesero
		$condicion .= (!empty($objeto['permisos'])) ? ' AND 
															(a.id_mesa IN(' . $objeto['permisos'] . ')
																OR
															a.tipo != 0)' : '';
		// Filtra por las asignaciones del mesero
		$condicion .= (!empty($objeto['asignacion'])) ? ' AND a.id_mesa IN(' . $objeto['asignacion'] . ')' : '';
		// Filtra para que no se muestren las mesas de servicio a domicilio y para llevar
		$condicion .= ($objeto['asignar'] == 1) ? ' AND a.tipo=0' : '';

		if(!$objeto["noJuntas"]){
			$sql = "SELECT 
					a.id_mesa AS mesa, res.id as id_res, a.x, a.y, a.width as width_barra, s.nombre as sucursal, a.id_area, a.height as height_barra, b.nombre, b.idDep,
					a.personas, a.status as mesa_status, a.tipo, a.domicilio, a.idempleado, ad.nombreusuario AS mesero, a.notificacion, tm.id as id_tipo_mesa, tm.tipo_mesa, tm.width, tm.height, tm.imagen,
					IF(GROUP_CONCAT(c.idmesa) is NULL, a.nombre, (SELECT GROUP_CONCAT(d.nombre) FROM com_mesas d INNER JOIN com_union c ON c.idmesa=d.id_mesa WHERE c.idprincipal = a.id_mesa)) nombre_mesa,
					if(GROUP_CONCAT(c.idmesa) is NULL,'',GROUP_CONCAT(c.idmesa)) idmesas, 
					if(GROUP_CONCAT(d.personas) is NULL,'',GROUP_CONCAT(d.personas)) mpersonas, 
					if(e.id is NULL,0,e.id) idcomanda, a.tipo mesaTipo 
					FROM com_mesas a
					LEFT JOIN administracion_usuarios ad ON ad.idempleado = a.idempleado
					LEFT JOIN mrp_departamento b ON b.idDep = a.idDep 
					LEFT JOIN mrp_sucursal s ON s.idSuc = a.idSuc 
					LEFT JOIN com_union c ON c.idprincipal = a.id_mesa 
					LEFT JOIN com_mesas d ON d.id_mesa = c.idmesa 
					LEFT JOIN com_comandas e ON e.idmesa = a.id_mesa AND e.status = 0 
					LEFT JOIN com_reservaciones res ON res.mesa = a.id_mesa AND res.activo = 1 AND (res.inicio >= '".$objeto['f_ini']."' AND res.inicio <= '".$objeto['f_fin']."')
					JOIN com_tipo_mesas tm ON a.tipo_mesa = tm.id
					WHERE a.status = 1 and a.idempleado = ".$_SESSION['mesero']['id']."
					AND (a.id_mesa NOT IN(select idmesa from com_union) OR a.id_mesa IN(select idprincipal from com_union) )  and a.tipo_mesa = 9
					AND (a.idSuc = " . $sucursal ." OR a.status = 4) 
					AND (a.id_mesa NOT IN(select idmesa from com_union) OR a.id_mesa IN(select idprincipal from com_union) )
					GROUP BY a.id_mesa 
					ORDER BY a.id_mesa asc";
		} else {
			$sql = "SELECT
						a.id_mesa AS mesa, a.x, a.y, a.width as width_barra, s.nombre as sucursal, a.id_area, a.height as height_barra, b.nombre, a.personas, a.status as mesa_status, a.domicilio, a.idempleado,
						ad.nombreusuario AS mesero, a.notificacion, a.nombre as nombre_mesa, if(e.id is NULL,0,e.id) idcomanda,
						tm.id as id_tipo_mesa, tm.tipo_mesa, tm.width, tm.height, tm.imagen 
					FROM 
						com_mesas a
					LEFT JOIN
							administracion_usuarios ad
						ON
							ad.idempleado = a.idempleado
					LEFT JOIN 
							mrp_departamento b 
						ON 
							b.idDep = a.idDep 
					LEFT JOIN 
							mrp_sucursal s 
						ON 
							s.idSuc = a.idSuc 
					LEFT JOIN 
							com_mesas d 
						ON 
							d.id_mesa = a.id_mesa 
					LEFT JOIN 
							com_comandas e 
						ON 
							e.idmesa = a.id_mesa 
						AND 
							e.status = 0 
					JOIN 
						com_tipo_mesas tm ON a.tipo_mesa = tm.id
					WHERE
						a.status = 1 
					AND
						a.tipo = 0 
					AND 
						a.id_dependencia = 0
						" . $condicion . " 
					AND
						a.idSuc = " . $sucursal ."
					OR
						a.status = 4 
					and
						a.tipo = 0 
					AND 
						a.id_dependencia = 0
						" . $condicion . " 
					AND
						a.idSuc = " . $sucursal ."
					GROUP BY 
						a.id_mesa 
					ORDER BY 
						a.id_mesa asc";
		}
		$tablesComanda = $this -> queryArray($sql);

		// return $sql;
		return $tablesComanda;
	}

///////////////////////////////

///////////////// ******** ---- 	guardar_info		------ ************ //////////////////
	// Como parametros puede recibir:
	// 	informacion adicional
	function guardar_info($info) {
		$sql = "UPDATE 
					com_configuracion
				SET 
					informacion_adicional = '".$info['info']."'
				WHERE 
					id= 1";
		$lala = $this -> query($sql);

		return $lala;
	}
///////////////// ******** ---- 	FIN guardar_info		------ ************ //////////////////

function arreglar() {
		$sql = "select com_pedidos.idproducto, app_productos.nombre, app_productos.precio, ( app_productos.precio *  SUM(com_pedidos.cantidad) )as total, app_pos_venta.idVenta, SUM(com_pedidos.cantidad) as cantidad from com_pedidos 
LEFT JOIN com_comandas ON com_comandas.id = com_pedidos.idcomanda 
LEFT JOIN app_pos_venta ON com_comandas.id_venta = app_pos_venta.idVenta
LEFT JOIN app_productos ON app_productos.id = com_pedidos.idproducto
where idproducto != 0 AND dependencia_promocion = 0 AND id_promocion = 0 and idcomanda in (select id from com_comandas where id_venta in (select idventa from app_pos_venta_producto where idproducto = 0)) group by com_pedidos.idproducto, app_pos_venta.idVenta;";
			$todos = $this -> queryArray($sql);
			$todos = $todos['rows'];
			foreach ($todos as $key => $value) {
				$sql = "update app_pos_venta_producto set idproducto = '".$value['idproducto']."',  cantidad = '".$value['cantidad']."', subtotal = ".$value['total'].", total = ".$value['total'].", comentario = '' where idVenta = ".$value['idVenta']." and comentario = '".$value['nombre']."' and idproducto = 0;";
				$jeje = $this -> query($sql);
			}

		return 1;
	}

///////////////// ******** ---- 	getImgCorreo		------ ************ //////////////////
//////// Obtiene los pedidos de la persona y los regresa en un array
	// Como parametros puede recibir:
	function getImgCorreo() {
		$imgs = "SELECT 
								imagen_promo, imagen_felicitaciones, informacion_adicional, 
								enviar_promociones, enviar_menu, enviar_felicitaciones, menu_digital,
								mostrar_info_correo, mostrar_logo_correo, mostrar_info_qr, mostrar_logo_qr,
								mostrar_opciones_menu, imagen_fondo
							FROM 
								com_configuracion 
							WHERE 
								id = 1";
			$imgs = $this -> queryArray($imgs);
			$imgs = $imgs['rows'][0];

		return $imgs;
	}
///////////////// ******** ---- 	FIN getImgCorreo		------ ************ //////////////////

///////////////// ******** ---- 	detalles_producto		------ ************ //////////////////
//////// Obtiene los pedidos de la persona y los regresa en un array
	// Como parametros puede recibir:
	function detalles_producto($objeto) {
		$sql = "SELECT 
								nombre, link, resena
							FROM 
								app_productos 
							WHERE 
								id = ".$objeto['id'];
		$detalles_producto = $this -> queryArray($sql);

		return $detalles_producto;
	}
///////////////// ******** ---- 	FIN detalles_producto		------ ************ //////////////////

	function getProducts($idDeparment = 0, $idFamily = 0, $idLine = 0, $limite = 0, $sucursal) {
		if (!empty($sucursal)) {
			$sucursal = $sucursal;
		} else {
			session_start();
			$sucursal = "	SELECT DISTINCT
								mp.idSuc AS id 
							FROM 
								administracion_usuarios au 
							INNER JOIN 
									mrp_sucursal mp 
								ON 
									mp.idSuc = au.idSuc 
							WHERE 
								au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
							LIMIT 1";
			$sucursal = $this -> queryArray($sucursal);
			$sucursal = $sucursal['rows'][0]['id'];
		}
	// Filtra por departamento si existe
		if ($idDeparment)
			$condicion .= " AND p.departamento=$idDeparment ";

	// Filtra por familia si existe
		if ($idFamily)
			$condicion .= " AND p.familia=$idFamily ";

	// Filtra por linea si existe
		if ($idLine)
			$condicion .= " AND p.linea=$idLine ";
		
		$limite = (!empty($limite)) ? ' LIMIT '.$limite : ' LIMIT 0, 100' ;
		$sql = "select * from app_producto_sucursal limit 1";
		$total = $this -> queryArray($sql);
		if($total['total'] > 0){
			$sql = "SELECT
					p.id AS idProducto, p.nombre, ROUND(p.precio, 2) AS precioventa, p.ruta_imagen AS imagen, 
					IF((SELECT 
							COUNT(id)
						FROM
							app_producto_material
						WHERE
							id_producto = p.id) > 0, 1, 0) materiales, departamento AS idDep, 
					f.h_ini AS inicio, f.h_fin AS fin, f.dias, p.formulaieps AS formula
				FROM
					app_productos p
				LEFT JOIN
						app_campos_foodware f
					ON
						p.id=f.id_producto
				LEFT JOIN 
						app_linea l
					ON 
						p.linea=l.id
				LEFT JOIN 
						app_familia fa
					ON 
						p.familia=fa.id
				LEFT JOIN 
						app_departamento d
					ON 
						p.departamento=d.id
				INNER JOIN app_producto_sucursal aps 
					ON 
						aps.id_producto = p.id 
					AND 
						aps.id_sucursal = ".$sucursal."
				WHERE
					p.status = 1
				AND
					tipo_producto != 3 
				AND
					tipo_producto != 6
				AND
					tipo_producto != 7
				AND
					tipo_producto != 8 " . 
				$condicion . "
				GROUP BY
					p.id
				ORDER BY
					f.rate DESC".
				$limite;
		} else {
			
		$sql = "SELECT
					p.id AS idProducto, p.nombre, ROUND(p.precio, 2) AS precioventa, p.ruta_imagen AS imagen, 
					IF((SELECT 
							COUNT(id)
						FROM
							app_producto_material
						WHERE
							id_producto = p.id) > 0, 1, 0) materiales, departamento AS idDep, 
					f.h_ini AS inicio, f.h_fin AS fin, f.dias, p.formulaieps AS formula
				FROM
					app_productos p
				LEFT JOIN
						app_campos_foodware f
					ON
						p.id=f.id_producto
				LEFT JOIN 
						app_linea l
					ON 
						p.linea=l.id
				LEFT JOIN 
						app_familia fa
					ON 
					p.familia=fa.id
				LEFT JOIN 
						app_departamento d
					ON 
						p.departamento=d.id
				WHERE
					p.status = 1
				AND
					tipo_producto != 3 
				AND
					tipo_producto != 6
				AND
					tipo_producto != 7
				AND
					tipo_producto != 8 " . 
				$condicion . "
				GROUP BY
					p.id
				ORDER BY
					f.rate DESC".
				$limite;
			}
		$productsComanda = $this -> queryArray($sql);
		return $productsComanda;
	}

	function getProduct($idproduct) {
		$sql = "SELECT distinct
					p.id AS idProducto, p.nombre, ROUND(p.precio, 2) AS precioventa, 
					p.ruta_imagen AS imagen, p.formulaieps AS formula
				FROM 
					app_productos p
				WHERE 
					status=1 
				AND 
					id=" . $idproduct;
		$productsComanda = $this -> query($sql);

		return $productsComanda;
	}
	public function formasDePago(){
		$query = "select * from forma_pago WHERE activo = 1 ORDER BY claveSat ASC ";
		$res = $this->queryArray($query);

		return array('formas' => $res['rows'] );
	}
// Obtiene la informacion de la comanda
	function getComanda($idmesa) {
		$sql = "SELECT 
					id, c.personas, timestamp, comensales, m.nombre AS nombre_mesa
				FROM 
					com_comandas c
				LEFT JOIN
						com_mesas m
					ON
						m.id_mesa = c.idmesa
				WHERE 
					c.status = 0
				AND 
					idmesa = " . $idmesa;
		$comanda = $this -> query($sql);

		return $comanda;
	}

// Obtiene la informacion de la comanda
	function getSucursal($objeto) {
		if (!empty($objeto['sucursal'])) {
			$sucursal = $objeto['sucursal'];
		} else {
					session_start();
			$sucursal = "	SELECT DISTINCT
								mp.idSuc AS id,
								mp.nombre, mp.tel_contacto,
								mp.direccion, m.municipio,
								e.estado
							FROM 
								administracion_usuarios au 
							INNER JOIN 
									mrp_sucursal mp 
								ON 
									mp.idSuc = au.idSuc
							INNER JOIN 
									municipios m 
								ON 
									mp.idMunicipio = m.idmunicipio 
							INNER JOIN 
									estados e 
								ON 
									mp.idEstado = m.idestado  
 
							WHERE 
								au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
							LIMIT 1";
			$sucursal = $this -> queryArray($sucursal);
			$sucursal = $sucursal['rows'];
		}
			return $sucursal;
	}


	function getPersons($idcomanda) {
		$sql = "SELECT 
					npersona, COUNT(npersona) AS num_personas
				FROM 
					com_pedidos 
				WHERE 
					idcomanda = " . $idcomanda . " 
				AND
					origen = 1
				GROUP BY 
					npersona 
				ORDER BY 
					npersona ASC";
		// return $sql;
		$people = $this -> queryArray($sql);

		return $people;
	}


	function get_promocion($id_promocion) {
		$sql = "SELECT 
					nombre, tipo, cantidad, cantidad_descuento, descuento, precio_fijo
				FROM 
					com_promociones 
				WHERE 
					id = " . $id_promocion . " 
				AND
					status = 1";
		// return $sql;
		$promocion = $this -> queryArray($sql);

		return $promocion['rows'][0];
	}

	function get_promociones($id_dependencia, $id_promocion) {
		 $sql = "SELECT a.id, (CASE a.dependencia_promocion 
						WHEN 0 THEN
							'0'
						ELSE c.comprar END) as comprar, c.recibir, a.idproducto, SUM(a.cantidad) AS cantidad, b.nombre, 
			ROUND(b.precio, 2) AS precio, opcionales, adicionales, sin, a.status, 
			a.complementos, a.id_promocion, (0) as sumaExtras FROM com_pedidos a 
			LEFT JOIN app_productos b ON b.id = a.idproducto 
			LEFT JOIN com_promocionesXproductos c on a.idproducto = c.id_producto AND c.id_promocion = ".$id_promocion."
			WHERE a.dependencia_promocion = ".$id_dependencia." 
			AND cantidad > 0
			GROUP BY status, a.id, a.opcionales, a.adicionales, a.complementos
			ORDER BY comprar desc, b.precio desc, b.id asc;";
			//print_r($sql); exit();
		// return $sql;
		$productsComanda = $this -> queryArray($sql);
		$array = Array("rows");

		$contador = 0;

		// Recorre los registros para formar una cadena de lo opcionales, extra y sin
		$sumaExtras = 0;
		//echo '<br>---- 1 ----<br>';
		//echo json_encode($productsComanda['rows']);
		//echo '<br>--------<br>';
		foreach ($productsComanda['rows'] as $value) {
			/* Impuestos del producto
			 ============================================================================= */

			$precio = $value['precio'];
			$objeto['id'] = $value['idproducto'];

			$impuestos = $this -> listar_impuestos($objeto);
			if ($impuestos['total'] > 0) {
				foreach ($impuestos['rows'] as $k => $v) {
					if ($v["clave"] == 'IEPS') {
						$producto_impuesto = $ieps = (($v["precio"]) * $v["valor"] / 100);
					} else {
						if ($ieps != 0) {
							$producto_impuesto = ((($v["precio"] + $ieps)) * $v["valor"] / 100);
						} else {
							$producto_impuesto = (($v["precio"]) * $v["valor"] / 100);
						}
					}

					// Precio actualizado
					$precio += $producto_impuesto;
					$precio = round($precio, 2);
				}
			}

			/* FIN Impuestos del producto
			 ============================================================================= */

			$items = "";

		// Opcionales
			if ($value['opcionales'] != "") {
				$sql = "SELECT 
							CONCAT('Con: ',GROUP_CONCAT(nombre)) nombre 
						FROM 
							app_productos 
						WHERE 
							id IN(" . $value['opcionales'] . ")";
				$itemsProduct = $this -> query($sql);

				if ($row = $itemsProduct -> fetch_array())
					$items .= "(" . $row['nombre'] . ")";
			}

		// Adicionales
			if ($value['adicionales'] != "") {
				/*
				$sql = "SELECT CONCAT('Extra: ',GROUP_CONCAT(nombre)) nombre, id, precio FROM  app_productos WHERE id in(" . $value['adicionales'] . ")";
				$itemsProduct = $this -> queryArray($sql);
				*/

				$sql2 = "SELECT  CONCAT('Extra: ',GROUP_CONCAT(nombre)) nombre, id, precio FROM app_productos WHERE id in(" . $value['adicionales'] . ")";
				$itemsProduct2 = $this -> queryArray($sql2);

				//echo '<br>----2 ----<br>';
				//echo json_encode($itemsProduct2);
				//echo '<br>--------<br>';

				foreach ($itemsProduct2['rows'] as $k => $v) {
				/* Impuestos del producto
				============================================================================= */
					$objeto['id'] = $v['id'];
					$producto_impuesto = 0;
					$impuestos = $this -> listar_impuestos($objeto);
					if ($impuestos['total'] > 0) {
						foreach ($impuestos['rows'] as $kk => $vv) {
							if ($vv["clave"] == 'IEPS') {
								$producto_impuesto = $ieps = (($vv["precio"]) * $vv["valor"] / 100);
							} else {
								if ($ieps != 0) {
									$producto_impuesto = ((($vv["precio"] + $ieps)) * $vv["valor"] / 100);
								} else {
									$producto_impuesto = (($vv["precio"]) * $vv["valor"] / 100);
								}
							}

						// Precio actualizado
							$precio += $producto_impuesto + $vv["precio"];
							$precio = round($precio, 2);
							$sumaExtras += $producto_impuesto + $v["precio"]; // extras							
						}
					}else{ /// extra sin impustos
						$precio +=  $v["precio"];
						$precio = round($precio, 2);
						$sumaExtras  += $v["precio"];						
					}

				/* FIN Impuestos del producto
				============================================================================= */

					$items .= "(" . $v['nombre'] . ")";
				}
			}

			// Sin
			if ($value['sin'] != "") {
				$sql = "SELECT
							CONCAT('Sin: ',GROUP_CONCAT(nombre)) nombre 
						FROM 
							app_productos 
						WHERE 
							id in(" . $value['sin'] . ")";
				$itemsProduct = $this -> query($sql);

				if ($row = $itemsProduct -> fetch_array())
					$items .= "(" . $row['nombre'] . ")";
			}



			$array['rows'][$contador] = Array(
				'id' => $value['id'], 
				'idproducto' => $value['idproducto'], 
				'status' => $value['status'], 
				'cantidad' => $value['cantidad'], 
				'nombre' => $value['nombre'] . " $items", 
				'precio' => $precio, 
				'complementos' => $value['complementos'], 
				'id_promocion' => $value['id_promocion'], 
				'recibir' => $value['recibir'], 
				'comprar' => $value['comprar'], 
				'sumaExtras' => $sumaExtras);
			$contador++;
			$items = '';
		}
		//echo '<br>-----sql---';
		//echo json_encode($array);		
		return $array;
	}


///////////////// ******** ---- 	getItemsPerson		------ ************ //////////////////
//////// Obtiene los pedidos de la persona y los regresa en un array
	// Como parametros puede recibir:
	// 	$person -> numero de persona
	//	$comanda -> id de la comanda

	function getItemsPerson($person, $comanda, $sucursal) {
		//print_r("lala");

		if (!empty($sucursal)) {
			$sucursal = $sucursal;
		} else {
		session_start();
		$sucursal = "	SELECT 
							mp.idSuc AS id 
						FROM 
							administracion_usuarios au 
						INNER JOIN 
								mrp_sucursal mp 
							ON 
								mp.idSuc = au.idSuc 
						WHERE 
							au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
						LIMIT 1";
		$sucursal = $this -> queryArray($sucursal);
		$sucursal = $sucursal['rows'][0]['id'];
		}
		$sql = "SELECT a.id, a.idproducto, SUM(a.cantidad) AS cantidad, b.nombre, 
				(CASE WHEN (Select precio from app_precio_sucursal where sucursal = ".$sucursal." and producto = a.idproducto limit 1) IS NULL THEN
				ROUND(b.precio, 2)ELSE ROUND((Select precio from app_precio_sucursal where sucursal = ".$sucursal." and producto = a.idproducto limit 1), 2) END) as precio, 
				opcionales, adicionales,  nota_sin, nota_extra, nota_opcional, sin, a.status, a.complementos, a.id_promocion, (CASE a.id_promocion WHEN 0 THEN 'producto' ELSE a.id END) as tipin, 
				notap, tipo_desc, monto_desc,
				(SELECT COUNT(*) FROM app_producto_impuesto WHERE id_producto = b.id) AS totalimp, i.valor, i.nombre as nimp  
				FROM com_pedidos a 
				LEFT JOIN app_productos b ON b.id = a.idproducto 

				LEFT JOIN app_producto_impuesto pi on b.id = pi.id_producto
				LEFT JOIN app_impuesto i on i.id = pi.id_impuesto

				WHERE a.dependencia_promocion = 0 AND cantidad > 0 AND origen = 1 AND a.npersona = ".$person." AND a.idcomanda = " . $comanda . "  
				GROUP BY tipin, status, a.idproducto, a.opcionales, a.adicionales, a.complementos, a.tipo_desc, a.monto_desc;";
						//print_r($sql);
		// return $sql;
		$productsComanda = $this -> queryArray($sql);
		$array = Array("rows");

		$contador = 0;

		// Recorre los registros para formar una cadena de lo opcionales, extra y sin
		
		foreach ($productsComanda['rows'] as $value) {

			// SI SE REQUIERE, ELIMINAR IF Y DEJAR ELSE, MODIFICANDO LA CONSULTA PARA QUITAR IMPUESTOS
			if($value['totalimp']*1<=1){ // cuando solo tiene un inpuesto o ninguno

					$precio = $value['precio'];
					if ($value["clave"] == 'IEPS') {
						$producto_impuesto = $ieps = (($value["precio"]) * $value["valor"] / 100);
					} else {
						if ($ieps != 0) {
							$producto_impuesto = ((($value["precio"] + $ieps)) * $value["valor"] / 100);
						} else {
							$producto_impuesto = (($value["precio"]) * $value["valor"] / 100);
						}
					}

					// Precio actualizado
					$precio += $producto_impuesto;
					$precio = round($precio, 2);					
					//impuestos

			}else{

				//impuestos
				$precio = $value['precio'];
				$objeto['id'] = $value['idproducto'];

				$impuestos = $this -> listar_impuestos($objeto);
				if ($impuestos['total'] > 0) {
					foreach ($impuestos['rows'] as $k => $v) {
						if ($v["clave"] == 'IEPS') {
							$producto_impuesto = $ieps = (($v["precio"]) * $v["valor"] / 100);
						} else {
							if ($ieps != 0) {
								$producto_impuesto = ((($v["precio"] + $ieps)) * $v["valor"] / 100);
							} else {
								$producto_impuesto = (($v["precio"]) * $v["valor"] / 100);
							}
						}

						// Precio actualizado
						$precio += $producto_impuesto;
						$precio = round($precio, 2);
					}
				}

				//impuestos

			}
			
			

			$items = "";

			// Opcionales
			if ($value['opcionales'] != "") {
				$sql = "SELECT 
							CONCAT('Con: ',GROUP_CONCAT(nombre)) nombre 
						FROM 
							app_productos 
						WHERE 
							id IN(" . $value['opcionales'] . ")";
				$itemsProduct = $this -> query($sql);

				if ($row = $itemsProduct -> fetch_array()){
					if($value['nota_opcional'] != ''){
						$items .= "(" . $row['nombre'] . ",".$value['nota_opcional'].")";
					} else {
						$items .= "(" . $row['nombre'] . ")";
					}
				} else if($value['nota_opcional'] != '') {
					$items .= "(" . $value['nota_opcional'] . ")";
				}

			} else if($value['nota_opcional'] != '') {
				$items .= "(" . $value['nota_opcional'] . ")";
			}

			
			// Adicionales
			if ($value['adicionales'] != "") {


				$sql = "SELECT CONCAT('Extra: ',GROUP_CONCAT(p.nombre)) nombre, p.id,
						(SELECT COUNT(*) FROM app_producto_impuesto WHERE id_producto = p.id) AS totalimp, i.valor
						FROM app_productos p 
						LEFT JOIN app_producto_impuesto pi on p.id = pi.id_producto
						LEFT JOIN app_impuesto i on i.id = pi.id_impuesto
						WHERE p.id in(" . $value['adicionales'] . ")";
				$itemsProduct = $this -> queryArray($sql);

				if (count($itemsProduct['rows']) > 0) {
					foreach ($itemsProduct['rows'] as $key5 => $value5) {
						if($value['nota_extra'] != ''){
							$items .= "(" . $value5['nombre'] . ",".$value['nota_extra'].")";
						} else {
							$items .= "(" . $value5['nombre'] . ")";
						}
					}
				}else if($value['nota_extra'] != '') {
					$items .= "(" . $value['nota_extra'] . ")";
				}
				
				foreach ($itemsProduct['rows'] as $k => $v) {

					if($v['totalimp']*1<=1){ // cuando solo tiene un inpuesto o ninguno

							if ($v["clave"] == 'IEPS') { /// correcion de variables
								$producto_impuesto = $ieps = (($value["precio"]) * $v["valor"] / 100);
							} else {
								if ($ieps != 0) {
									$producto_impuesto = ((($value["precio"] + $ieps)) * $v["valor"] / 100);
								} else {
									$producto_impuesto = (($value["precio"]) * $v["valor"] / 100);
								}
							}

							// Precio actualizado
							$precio += $producto_impuesto;
							$precio = round($precio, 2);
							//impuestos

					}else{

						/* Impuestos del producto
						============================================================================= */
							$objeto['id'] = $v['id'];

							$impuestos = $this -> listar_impuestos($objeto);
							if ($impuestos['total'] > 0) {
								foreach ($impuestos['rows'] as $kk => $vv) {
									if ($vv["clave"] == 'IEPS') {
										$producto_impuesto = $ieps = (($vv["precio"]) * $vv["valor"] / 100);
									} else {
										if ($ieps != 0) {
											$producto_impuesto = ((($vv["precio"] + $ieps)) * $vv["valor"] / 100);
										} else {
											$producto_impuesto = (($vv["precio"]) * $vv["valor"] / 100);
										}
									}

								// Precio actualizado
									$precio += $producto_impuesto + $vv["precio"];
									$precio = round($precio, 2);
								}
							}

						/* FIN Impuestos del producto
						============================================================================= */

					}
					
				}

			} else if($value['nota_extra'] != '') {
				$items .= "(" . $value['nota_extra'] . ")";
			}

			

			// Sin
			if ($value['sin'] != "") {
				$sql = "SELECT
							CONCAT('Sin: ',GROUP_CONCAT(nombre)) nombre 
						FROM 
							app_productos 
						WHERE 
							id in(" . $value['sin'] . ")";
				$itemsProduct = $this -> query($sql);

				if ($row = $itemsProduct -> fetch_array()){
					if($value['nota_sin'] != ''){
						$items .= "(" . $row['nombre'] . ",".$value['nota_sin'].")";
					} else {
						$items .= "(" . $row['nombre'] . ")";
					}
				} else if($value['nota_opcional'] != '') {
					$items .= "(" . $value['nota_sin'] . ")";
				}
			} else if($value['nota_opcional'] != '') {
				$items .= "(" . $value['nota_sin'] . ")";
			}

			$desc = '';
			if($value['tipo_desc'] == '%' and $value['monto_desc'] > 0){
				$precio = $precio - ($precio*($value['monto_desc']/100));
				$desc = 'Desc de '.$value['monto_desc'].'%';				
			}

			$array['rows'][$contador] = Array('id' => $value['id'], '
				idproducto' => $value['idproducto'], 
				'status' => $value['status'], 
				'cantidad' => $value['cantidad'], 
				'nombre' => $value['nombre'] . " $items ". $desc, 
				'precio' => $precio, 
				'complementos' => $value['complementos'], 
				'id_promocion' => $value['id_promocion'], 
				'notap' => $value['notap'],
				'monto_desc' => $value['monto_desc'],
				);
			$contador++;
		}

		return $array;
	}

///////////////// ******** ---- 	FIN getItemsPerson		------ ************ //////////////////

///////////////// ******** ---- 	getItemsPerson_2		------ ************ //////////////////
//////// Obtiene los pedidos de la persona y los regresa en un array
	// Como parametros puede recibir:
	// 	$person -> numero de persona
	//	$comanda -> id de la comanda

	function getItemsPerson_2($comanda, $sucursal) {
		if (!empty($sucursal)) {
			$sucursal = $sucursal;
		} else {
		session_start();
		$sucursal = "	SELECT 
							mp.idSuc AS id 
						FROM 
							administracion_usuarios au 
						INNER JOIN 
								mrp_sucursal mp 
							ON 
								mp.idSuc = au.idSuc 
						WHERE 
							au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
						LIMIT 1";
		$sucursal = $this -> queryArray($sucursal);
		$sucursal = $sucursal['rows'][0]['id'];
	}
		$sql = "SELECT a.id, a.idproducto, SUM(a.cantidad) AS cantidad, b.nombre, 
				(CASE WHEN (Select precio from app_precio_sucursal where sucursal = ".$sucursal." and producto = a.idproducto limit 1) IS NULL THEN
				ROUND(b.precio, 2)ELSE ROUND((Select precio from app_precio_sucursal where sucursal = ".$sucursal." and producto = a.idproducto limit 1), 2) END) as precio, 
				opcionales, adicionales,  nota_sin, nota_extra, nota_opcional, sin, a.status, a.complementos, a.id_promocion, (CASE a.id_promocion WHEN 0 THEN 'producto' ELSE a.id END) as tipin, 
				notap, tipo_desc, monto_desc,
				(SELECT COUNT(*) FROM app_producto_impuesto WHERE id_producto = b.id) AS totalimp, i.valor, i.nombre as nimp  
				FROM com_pedidos a 
				LEFT JOIN app_productos b ON b.id = a.idproducto 

				LEFT JOIN app_producto_impuesto pi on b.id = pi.id_producto
				LEFT JOIN app_impuesto i on i.id = pi.id_impuesto

				WHERE a.dependencia_promocion = 0 AND cantidad > 0 AND origen = 1 AND a.idcomanda = " . $comanda . "
				GROUP BY tipin, status, a.idproducto, a.opcionales, a.adicionales, a.complementos, a.tipo_desc, a.monto_desc;";
				//print_r($sql);
		// return $sql;
		$productsComanda = $this -> queryArray($sql);
		$array = Array("rows");

		$contador = 0;

		// Recorre los registros para formar una cadena de lo opcionales, extra y sin
		foreach ($productsComanda['rows'] as $value) {

			// SI SE REQUIERE, ELIMINAR IF Y DEJAR ELSE, MODIFICANDO LA CONSULTA PARA QUITAR IMPUESTOS
			if($value['totalimp']*1<=1){ // cuando solo tiene un inpuesto o ninguno

					$precio = $value['precio'];
					if ($value["clave"] == 'IEPS') {
						$producto_impuesto = $ieps = (($value["precio"]) * $value["valor"] / 100);
					} else {
						if ($ieps != 0) {
							$producto_impuesto = ((($value["precio"] + $ieps)) * $value["valor"] / 100);
						} else {
							$producto_impuesto = (($value["precio"]) * $value["valor"] / 100);
						}
					}

					// Precio actualizado
					$precio += $producto_impuesto;
					$precio = round($precio, 2);

					//impuestos

			}else{
			
				/* Impuestos del producto
				 ============================================================================= */

				$precio = $value['precio'];
				$objeto['id'] = $value['idproducto'];

				$impuestos = $this -> listar_impuestos($objeto);
				if ($impuestos['total'] > 0) {
					foreach ($impuestos['rows'] as $k => $v) {
						if ($v["clave"] == 'IEPS') {
							$producto_impuesto = $ieps = (($v["precio"]) * $v["valor"] / 100);
						} else {
							if ($ieps != 0) {
								$producto_impuesto = ((($v["precio"] + $ieps)) * $v["valor"] / 100);
							} else {
								$producto_impuesto = (($v["precio"]) * $v["valor"] / 100);
							}
						}

						// Precio actualizado
						$precio += $producto_impuesto;
						$precio = round($precio, 2);
					}
				}

				/* FIN Impuestos del producto
				 ============================================================================= */
			}

			$items = "";

		// Opcionales
			if ($value['opcionales'] != "") {
				$sql = "SELECT 
							CONCAT('Con: ',GROUP_CONCAT(nombre)) nombre 
						FROM 
							app_productos 
						WHERE 
							id IN(" . $value['opcionales'] . ")";
				$itemsProduct = $this -> query($sql);

				if ($row = $itemsProduct -> fetch_array()){
					if($value['nota_opcional'] != ''){
						$items .= "(" . $row['nombre'] . ",".$value['nota_opcional'].")";
					} else {
						$items .= "(" . $row['nombre'] . ")";
					}
				} else if($value['nota_opcional'] != '') {
					$items .= "(" . $value['nota_opcional'] . ")";
				}

			} else if($value['nota_opcional'] != '') {
				$items .= "(" . $value['nota_opcional'] . ")";
			}

			
		// Adicionales
			if ($value['adicionales'] != "") {
				$sql = "SELECT CONCAT('Extra: ',GROUP_CONCAT(p.nombre)) nombre, p.id,
						(SELECT COUNT(*) FROM app_producto_impuesto WHERE id_producto = p.id) AS totalimp, i.valor
						FROM app_productos p 
						LEFT JOIN app_producto_impuesto pi on p.id = pi.id_producto
						LEFT JOIN app_impuesto i on i.id = pi.id_impuesto
						WHERE p.id in(" . $value['adicionales'] . ")";
				$itemsProduct = $this -> queryArray($sql);

				if (count($itemsProduct['rows']) > 0) {
					foreach ($itemsProduct['rows'] as $key5 => $value5) {
						if($value['nota_extra'] != ''){
							$items .= "(" . $value5['nombre'] . ",".$value['nota_extra'].")";
						} else {
							$items .= "(" . $value5['nombre'] . ")";
						}
					}
				}else if($value['nota_extra'] != '') {
					$items .= "(" . $value['nota_extra'] . ")";
				}
				

				foreach ($itemsProduct['rows'] as $k => $v) {

					if($v['totalimp']*1<=1){ // cuando solo tiene un inpuesto o ninguno

							if ($v["clave"] == 'IEPS') {
								$producto_impuesto = $ieps = (($v["precio"]) * $v["valor"] / 100);
							} else {
								if ($ieps != 0) {
									$producto_impuesto = ((($v["precio"] + $ieps)) * $v["valor"] / 100);
								} else {
									$producto_impuesto = (($v["precio"]) * $v["valor"] / 100);
								}
							}

							// Precio actualizado
							$precio += $producto_impuesto;
							$precio = round($precio, 2);

							//impuestos

					}else{


						/* Impuestos del producto
						============================================================================= */
							$objeto['id'] = $v['id'];

							$impuestos = $this -> listar_impuestos($objeto);
							if ($impuestos['total'] > 0) {
								foreach ($impuestos['rows'] as $kk => $vv) {
									if ($vv["clave"] == 'IEPS') {
										$producto_impuesto = $ieps = (($vv["precio"]) * $vv["valor"] / 100);
									} else {
										if ($ieps != 0) {
											$producto_impuesto = ((($vv["precio"] + $ieps)) * $vv["valor"] / 100);
										} else {
											$producto_impuesto = (($vv["precio"]) * $vv["valor"] / 100);
										}
									}

								// Precio actualizado
									$precio += $producto_impuesto + $vv["precio"];
									$precio = round($precio, 2);
								}
							}

						/* FIN Impuestos del producto
						============================================================================= */
					}

					
				}

			} else if($value['nota_extra'] != '') {
				$items .= "(" . $value['nota_extra'] . ")";
			}

			

		// Sin
			if ($value['sin'] != "") {
				$sql = "SELECT
							CONCAT('Sin: ',GROUP_CONCAT(nombre)) nombre 
						FROM 
							app_productos 
						WHERE 
							id in(" . $value['sin'] . ")";
				$itemsProduct = $this -> query($sql);

				if ($row = $itemsProduct -> fetch_array()){
					if($value['nota_sin'] != ''){
						$items .= "(" . $row['nombre'] . ",".$value['nota_sin'].")";
					} else {
						$items .= "(" . $row['nombre'] . ")";
					}
				} else if($value['nota_opcional'] != '') {
					$items .= "(" . $value['nota_sin'] . ")";
				}
			} else if($value['nota_opcional'] != '') {
				$items .= "(" . $value['nota_sin'] . ")";
			}

			/// descuentos
			$desc = '';
			if($value['tipo_desc'] == '%' and $value['monto_desc'] > 0){
				$precio = $precio - ($precio*($value['monto_desc']/100));
				$desc = 'Desc de '.$value['monto_desc'].'%';				
			}

			

			$array['rows'][$contador] = Array('id' => $value['id'], 
				'idproducto' => $value['idproducto'], 
				'status' => $value['status'], 
				'cantidad' => $value['cantidad'], 
				'nombre' => $value['nombre'] . " $items", 
				'precio' => $precio*$value['cantidad'], 
				'complementos' => $value['complementos'], 
				'id_promocion' => $value['id_promocion']);
			$contador++;
		}

		return $array;
	}

///////////////// ******** ---- 	FIN getItemsPerson_2		------ ************ //////////////////

///////////////// ******** ---- 	 lessProduct		------ ************ //////////////////
//////// Resta un pedido a los pedidos de la comanda y actualiza el total
	// Como parametros puede recibir:
		// 	$idorder -> ID de la orden

	function lessProduct($idorder) {
	// Consulta el precio del producto
		$sql = 'SELECT 
					idcomanda, adicionales, ROUND(p.precio, 2) AS precio, p.formulaieps AS formula, p.id
				FROM 
					app_productos p
				INNER JOIN
					com_pedidos pe
				WHERE 
					pe.idproducto = p.id
				AND
					pe.origen = 1
				AND
					pe.id = ' . $idorder;
		$result = $this -> queryArray($sql);
		$idcomanda = $result['rows'][0]['idcomanda'];
		$adicionales = $result['rows'][0]['adicionales'];

	/* Impuestos del producto
	============================================================================= */

		$precio = $result['rows'][0]['precio'];
		$objeto['id'] = $result['rows'][0]['id'];

		$impuestos = $this -> listar_impuestos($objeto);
		if ($impuestos['total'] > 0) {
			foreach ($impuestos['rows'] as $k => $v) {
				if ($v["clave"] == 'IEPS') {
					$producto_impuesto = $ieps = (($v["precio"]) * $v["valor"] / 100);
				} else {
					if ($ieps != 0) {
						$producto_impuesto = ((($v["precio"] + $ieps)) * $v["valor"] / 100);
					} else {
						$producto_impuesto = (($v["precio"]) * $v["valor"] / 100);
					}
				}

				// Precio actualizado
				$precio += $producto_impuesto;
				$precio = round($precio, 2);
			}
		}

	/* FIN Impuestos del producto
	============================================================================= */

	// Obtiene los costos de los productos extra si existen
		if (!empty($adicionales)) {
			$sql = 'SELECT 
						ROUND(b.precio, 2) AS precioventa, id
					FROM 
						app_productos b
					WHERE
						id in(' . $adicionales . ')';
			$precios_extra = $this -> queryArray($sql);

		// Recorre los costos y los agrega al precio
			foreach ($precios_extra['rows'] as $key => $value) {
			/* Impuestos del producto
			============================================================================= */

				$objeto['id'] = $value['id'];
				$impuestos = $this -> listar_impuestos($objeto);
				if ($impuestos['total'] > 0) {
					foreach ($impuestos['rows'] as $k => $v) {
						if ($v["clave"] == 'IEPS') {
							$producto_impuesto = $ieps = (($value['precioventa']) * $v["valor"] / 100);
						} else {
							if ($ieps != 0) {
								$producto_impuesto = ((($value['precioventa'] + $ieps)) * $v["valor"] / 100);
							} else {
								$producto_impuesto = (($value['precioventa']) * $v["valor"] / 100);
							}
						}

					// Precio actualizado
						$precio += $producto_impuesto + $value['precioventa'];
						$precio = round($precio, 2);

						$impuestos_comanda += $producto_impuesto;
					}
				}

			/* FIN Impuestos del producto
			============================================================================= */
			}
		}

	// Actualiza el total de la comanda
		/*
		$sql = 'UPDATE 
					com_comandas
				SET 
					total = total-' . $precio . '
				WHERE 
					id=' . $idcomanda;
		$precio = $this -> query($sql);
		*/

	// Obtiene el ID del producto
		$sql = "SELECT 
					id 
				FROM 
					com_pedidos 
				WHERE 
					id = " . $idorder . " 
				AND 
					cantidad > 1";
		$idproduct = $this -> query($sql);

		$fecha = date('Y-m-d H:i:s');
	// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;

	// Guarda la actividad
		$sql = "INSERT INTO
					com_actividades
						(id, empleado, accion, fecha)
				VALUES
					(''," . $usuario . ",'Resta producto', '" . $fecha . "')";
		$actividad = $this -> query($sql);

	// Si la cantidad es mayor a 1 lo resta si no lo elimina
		if ($row = $idproduct -> fetch_array())
			return $this -> query("UPDATE com_pedidos SET cantidad = cantidad - 1 WHERE id = " . $idorder);
		else
			return $this -> query("DELETE FROM com_pedidos WHERE id = " . $idorder);
	}

///////////////// ******** ---- 	 FIN lessProduct		------ ************ //////////////////

///////////////// ******** ---- 	 deleteProduct		------ ************ //////////////////
//////// Elimina un producto de la persona
	// Como parametros puede recibir:
	// 	$idorder -> ID de la orden

	function deleteProduct($objeto) {
	// Consulta el precio del producto
		if ($objeto['id_promocion'] == 0) {
		$sql = 'SELECT 
					idcomanda, p.id AS idproducto, pe.cantidad, npersona AS persona,
					ROUND(p.precio, 2) AS precio, adicionales, p.formulaieps AS formula, p.nombre
				FROM  
					app_productos p
				INNER JOIN
					com_pedidos pe
				WHERE 
					pe.idproducto = p.id
				AND
					pe.origen = 1
				AND
						pe.id = ' . $objeto['idorder'];
		$result = $this -> queryArray($sql);
		$extras = $result['rows'][0]['adicionales'];
		$idproducto = $result['rows'][0]['idproducto'];
		$persona = $result['rows'][0]['persona'];
		$idcomanda = $result['rows'][0]['idcomanda'];
		$nombre = $result['rows'][0]['nombre'];

	/* Impuestos del producto
	============================================================================= */

		$precio = $result['rows'][0]['precio'];
		$objeto['id'] = $result['rows'][0]['idproducto'];

		$impuestos = $this -> listar_impuestos($objeto);
		if ($impuestos['total'] > 0) {
			foreach ($impuestos['rows'] as $k => $v) {
				if ($v["clave"] == 'IEPS') {
					$producto_impuesto = $ieps = (($v["precio"]) * $v["valor"] / 100);
				} else {
					if ($ieps != 0) {
						$producto_impuesto = ((($v["precio"] + $ieps)) * $v["valor"] / 100);
					} else {
						$producto_impuesto = (($v["precio"]) * $v["valor"] / 100);
					}
				}

				// Precio actualizado
				$precio += $producto_impuesto;
				$precio = round($precio, 2);
			}
		}

	/* FIN Impuestos del producto
	============================================================================= */
	
		// Obtiene los costos de los productos extra si existen
		if (!empty($extras)) {
			$sql = 'SELECT 
						ROUND(b.precio, 2) AS precioventa, id
					FROM 
						app_productos b
					WHERE
						id in(' . $extras . ')';
			$precios_extra = $this -> queryArray($sql);

		// Recorre los costos y los agrega al precio
			foreach ($precios_extra['rows'] as $key => $value) {
			/* Impuestos del producto
			============================================================================= */

				$objeto['id'] = $value['id'];
				$impuestos = $this -> listar_impuestos($objeto);
				
				if ($impuestos['total'] > 0) {
					foreach ($impuestos['rows'] as $k => $v) {
						if ($v["clave"] == 'IEPS') {
							$producto_impuesto = $ieps = (($value['precioventa']) * $v["valor"] / 100);
						} else {
							if ($ieps != 0) {
								$producto_impuesto = ((($value['precioventa'] + $ieps)) * $v["valor"] / 100);
							} else {
								$producto_impuesto = (($value['precioventa']) * $v["valor"] / 100);
							}
						}

						// Precio actualizado
						$precio += $producto_impuesto + $value['precioventa'];
						$precio = round($precio, 2);

						$impuestos_comanda += $producto_impuesto;
					}
				}

			/* FIN Impuestos del producto
			============================================================================= */
			}
		}

	// Obtiene la cantidad
		$sql = "SELECT 
					SUM(cantidad) AS cantidad
				FROM
					com_pedidos
				WHERE
					idproducto = " . $idproducto . "
				AND
					npersona = " . $persona . "
				AND
					idcomanda = " . $idcomanda."
				AND
					origen = 1
				AND
					status = -1";
		$cantidad = $this -> queryArray($sql);

	// Calcula la el precio multiplicando por la cantidad
		$precio = $precio * $cantidad['rows'][0]['cantidad'];

	// Actualiza el total de la comanda
		/*
		$sql = 'UPDATE 
					com_comandas
				SET 
					total = total - ' . $precio . '
				WHERE 
					id = ' . $idcomanda;
		$precio = $this -> query($sql);
		*/

	// Obtiene los datos del pedido
		$sql = "SELECT 
					idcomanda, idproducto, npersona, opcionales, adicionales 
				FROM 
					com_pedidos 
				WHERE 
					id = " . $objeto['idorder'];
		$idproduct = $this -> query($sql);

	// ** Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
	// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;
		$sql = "	INSERT INTO
						com_actividades
							(id, empleado, accion, descripcion, fecha)
					VALUES
						(''," . $usuario . ",'Elimina producto', 'Elimina el producto ".$nombre." de la comanda ".$idcomanda."',
						 '" . $fecha . "')";
		$actividad = $this -> query($sql);

		if ($row = $idproduct -> fetch_array()) {
			$sql = "DELETE FROM 
						com_pedidos 
					WHERE 
						dependencia_promocion = '".$objeto['idorder']."'";
			$this -> query($sql);
			$sql = "DELETE FROM 
						com_pedidos 
					WHERE 
						idcomanda = '" . $row['idcomanda'] . "' 
					AND 
						idproducto = '" . $row['idproducto'] . "' 
					AND 
						npersona = '" . $row['npersona'] . "' 
					AND 
						opcionales = '" . $row['opcionales'] . "' 
					AND 
						adicionales = '" . $row['adicionales'] . "'
					AND 
						status = -1";
			return $this -> query($sql);
		}
		} else {
			$sql = "DELETE FROM 
						com_pedidos 
					WHERE 
						dependencia_promocion = '".$objeto['idorder']."'";
			$this -> query($sql);
			$sql = "DELETE FROM 
						com_pedidos 
					WHERE 
						id = '".$objeto['idorder']."'";
			return $this -> query($sql);
		}
	}

///////////////// ******** ---- 	 FIN deleteProduct		------ ************ //////////////////

	function insertComanda($idmesa, $iddeparment) {
	// Inserta la comanda en la BD
		date_default_timezone_set('America/Mexico_City');
		
	// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;
		
		$fecha = date('Y-m-d H:i:s');
		$sql = "INSERT INTO 
					com_comandas 
					(id, idmesa, personas, status, tipo, codigo, timestamp, abierta, idempleado) 
				VALUES 
					('','$idmesa','0','0','$iddeparment','','" . $fecha . "','3','" . $usuario . "')";
		$comanda = $this -> insert_id($sql);

	// ** Consulta si es la comanda de la reservacion
		$sql = "SELECT  
					*
				FROM 
					com_reservaciones
				WHERE 
					1 = 1 
				AND 
					'" . $fecha . "'
				BETWEEN 
						inicio 
					AND 
						fin 
				AND 
					activo = 1";
		$reservaciones = $this -> queryArray($sql);

	// Si es la comanda actualiza la reservacion
		if (!empty($reservaciones['rows'])) {
			$sql = "UPDATE 
						com_reservaciones 
					SET 
						activo = 1
					WHERE 
						id=" . $reservaciones['rows'][0]['id'];
			$update = $this -> queryArray($sql);
		}

	// ** FIN Consulta si es la comanda de la reservacion

	// Agrega el codigo al a comanda
		if ($comanda) {
			$size = 5 - strlen($comanda);
			$string = "";

			for ($i = 0; $i < $size; $i++)
				$string .= "0";

			$string .= $comanda;
			$sql = "UPDATE 
						com_comandas 
					SET 
						codigo='COM" . $string . "' 
					WHERE 
						id = " . $comanda;
			$this -> query($sql);
		}

	//** Guarda la actividad
		$fecha = date('Y-m-d H:i:s');

	// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;
		$sql = "INSERT INTO
					com_actividades
						(id, empleado, accion, fecha)
				VALUES
					(''," . $usuario . ",'Abre comanda', '" . $fecha . "')";
		$actividad = $this -> query($sql);

		$this -> incrementPersons($comanda);
		return $comanda;
	}

///////////////// ******** ---- 	deleteComanda		------ ************ //////////////////
	// Elimina la comanda, elimina los pedidos, elimina la reservacion(si existe)
	// Como parametros puede recibir:
	//	$idcomanda -> id de la comanda
	// $idmesa -> ID de la mesa
	// $id_reservacion -> ID de la reservacion

	function deleteComanda($idcomanda, $idmesa, $id_reservacion) {
		$fecha = date('Y-m-d H:i:s');

	// Actualiza el status y la fecha de la comanda a 3 para indicar que se elimino
		$sql = "	UPDATE
						com_comandas 
					SET
						status = 3,
						fin = '" . $fecha . "'
					WHERE 
						id = " . $idcomanda;
		$products = $this -> query($sql);

	// Actualiza los pedidos para indicar que se elimino la comanda
		$sql = "	UPDATE
						com_pedidos 
					SET
						status = 3
					WHERE 
						idcomanda = " . $idcomanda;
		$products = $this -> query($sql);

	// Consulta si la mesa se debe eliminar o no
		$sql = "	SELECT
						tipo 
					FROM
						com_mesas
					WHERE 
						id_mesa = " . $idmesa;
		$mesa = $this -> queryArray($sql);
		$tipo = $mesa['rows'][0]['tipo'];

	// Elimina la mesa si es servicio a domicilio o para llevar
		if ($tipo == 2 || $tipo == 1) {
			$sql = "	UPDATE
							com_mesas
						SET
							status = 2
						WHERE 
							id_mesa=" . $idmesa;
			$elimina = $this -> query($sql);
		}

	// Separa las mesas de los registros de la tabla com_union
		$sql = "	DELETE FROM
						com_union
					WHERE
						idprincipal = " . $idmesa;
		$union = $this -> query($sql);

	//** Guarda la actividad
	// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;
		$sql = "	INSERT INTO
						com_actividades
							(id, empleado, accion, fecha)
					VALUES
						(''," . $usuario . ",'Elimina comanda', '" . $fecha . "')";
		$actividad = $this -> query($sql);
	}

///////////////// ******** ---- 	FIN deleteComanda		------ ************ //////////////////

///////////////// ******** ---- 	incrementPersons		------ ************ //////////////////
	// Actualiza el numero de personas y comensales
	// Como parametro puede recibir:
	// $idcomanda -> id de la comanda

	function incrementPersons($idcomanda) {
	// Actualiza los registros
		$sql = "UPDATE 
					com_comandas 
				SET 
					personas = personas + 1, comensales = personas 
				WHERE 
					id=" . $idcomanda;
		$persons = $this -> query($sql);

	// Obtiene el numero de personas
		$sql = "SELECT 
					npersona 
				FROM 
					com_pedidos 
				WHERE 
					idcomanda = " . $idcomanda . " 
				ORDER BY 
					npersona DESC LIMIT 1";
		$persons = $this -> query($sql);

		$idperson = 0;

		//if ($row = $persons -> fetch_array()) {
		if ($persons->num_rows>0) {
			$row = $persons -> fetch_array(); // new
			$sql = "INSERT INTO 
						com_pedidos (id, idcomanda, idproducto, cantidad, npersona, tipo, status,
							opcionales, adicionales) 
					VALUES 
						(null, '$idcomanda', '0', '0', '" . ($row['npersona'] + 1) . "', '0', '0', '', '')";
			$product = $this -> query($sql);
			$idperson = ($row['npersona'] + 1);
		} else {
			$sql = "INSERT INTO 
						com_pedidos (id, idcomanda, idproducto, cantidad, npersona, tipo, status,
							opcionales, adicionales) 
					VALUES 
						(null,'$idcomanda','0','0','1','0','0','','')";
			$product = $this -> query($sql);
			$idperson = 1;
		}

	//** Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
	// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;
		$sql = "	INSERT INTO
						com_actividades
							(id, empleado, accion, fecha)
					VALUES
						(''," . $usuario . ",'Agrega persona', '" . $fecha . "')";
		$actividad = $this -> query($sql);

		return $idperson;
	}

///////////////// ******** ---- 	FIN incrementPersons		------ ************ //////////////////

	function deletePersons($idcomanda, $persons) {
	// AActualiza la cantidad de personas en la mesa
		$sql = "UPDATE 
					com_comandas 
				SET 
					personas = (personas-" . count(explode(',', $persons)) . ") 
				WHERE 
					id=" . $idcomanda;
		$person = $this -> query($sql);

	// Elimina los pedidos de la persona
		$sql = "DELETE FROM 
					com_pedidos 
				WHERE 
					idcomanda = " . $idcomanda . " 
				AND 
					npersona in(" . $persons . ")";
		$person = $this -> query($sql);

	//** Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
	// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;
		$sql = "	INSERT INTO
						com_actividades
							(id, empleado, accion, fecha)
					VALUES
						(''," . $usuario . ",'Elimina persona', '" . $fecha . "')";
		$actividad = $this -> query($sql);

		return $person;
	}

///////////////// ******** ---- 			addProduct			------ ************ //////////////////
//	Agrega un producto a la persona, actualiza el total y los impuestos de la comanda
	// Como parametros puede recibir:
		// $idproduct -> ID del producto
		// $idperson -> ID de la persona
		// $idcomanda -> ID de la comanda
		// $opcionales -> Cadena con los IDs de los productos opcionales
		// $extras -> Cadena con los IDs de los productos extras
		// $sin -> Cadena con los IDs de los productos sin
		// $iddep -> ID del departamento
		// $nota_opcional -> string con la nota de los productos opcionales
		// $nota_extra -> string con la nota de los productos extras
		// $nota_sin -> string con la nota de los productos sin

	function addProduct($idproduct, $idperson, $idcomanda, $opcionales = '', $extras, $sin = '', $iddep, $nota_opcional = '', $nota_extra = '', $nota_sin = '', $id_promocion, $is_promocion = 0, $dependencia_promocion = 0) {
			
		
		$sql1 = 'SELECT idSuc FROM administracion_usuarios where idEmpleado = '.$_SESSION['accelog_idempleado'].';';
		$result1 = $this->queryArray($sql1);
		$idSuc = $result1['rows'][0]['idSuc'];

		// obtiene el tipo de producto y su precio
		if($is_promocion == 0){
			$queryproduct = 'SELECT 
								tipo_producto,

								(CASE WHEN (Select precio from app_precio_sucursal where sucursal = 17 and producto = b.id limit 1) IS NULL THEN ROUND(precio, 2) ELSE ROUND((Select precio from app_precio_sucursal where sucursal = 17 and producto = b.id limit 1), 2) END) as precioventa

							FROM 
								app_productos b
							WHERE 
								id = ' . $idproduct;
			$tipro = $this -> query($queryproduct);
			$row = $tipro -> fetch_array();
			$tipo_producto = $row['tipo_producto'];
			$precio = $row['precioventa'];

			/* Impuestos del producto
			============================================================================= */

				$impuestos_comanda = 0;
				$objeto['id'] = $idproduct;
				$impuestos = $this -> listar_impuestos($objeto);

				if ($impuestos['total'] > 0) {
					foreach ($impuestos['rows'] as $k => $v) {
						if ($v["clave"] == 'IEPS') {
							$producto_impuesto = $ieps = (($precio) * $v["valor"] / 100);
						} else {
							if ($ieps != 0) {
								$producto_impuesto = ((($precio + $ieps)) * $v["valor"] / 100);
							} else {
								$producto_impuesto = (($precio) * $v["valor"] / 100);
							}
						}

					// Precio actualizado
						$precio += $producto_impuesto;
						$precio = round($precio, 2);

						$impuestos_comanda += $producto_impuesto;
					}
				}

			/* FIN Impuestos del producto
			============================================================================= */

			// Obtiene los costos de los productos extra si existen
			if (!empty($extras)) {
				$sql = 'SELECT 
							ROUND(b.precio, 2) AS precioventa, id
						FROM 
							app_productos b
						WHERE
							id in(' . $extras . ')';
				$precios_extra = $this -> queryArray($sql);

			// Recorre los costos y los agrega al precio
				foreach ($precios_extra['rows'] as $key => $value) {
				/* Impuestos del producto
				============================================================================= */

					$objeto['id'] = $value['id'];
					$impuestos = $this -> listar_impuestos($objeto);
					if ($impuestos['total'] > 0) {
						foreach ($impuestos['rows'] as $k => $v) {
							if ($v["clave"] == 'IEPS') {
								$producto_impuesto = $ieps = (($value['precioventa']) * $v["valor"] / 100);
							} else {
								if ($ieps != 0) {
									$producto_impuesto = ((($value['precioventa'] + $ieps)) * $v["valor"] / 100);
								} else {
									$producto_impuesto = (($value['precioventa']) * $v["valor"] / 100);
								}
							}

						// Precio actualizado
							$precio += $producto_impuesto + $value['precioventa'];
							$precio = round($precio, 2);

							$impuestos_comanda += $producto_impuesto;
						}
					}

				/* FIN Impuestos del producto
				============================================================================= */
				}
			}

			// Actualiza el total y los impuestos de la comanda
			/*
			$sql = 'UPDATE 
						com_comandas
					SET 
						total = total + ' . $precio . '
					WHERE 
							id = ' . $idcomanda;
			$precio = $this -> query($sql);
			*/

			/* Guarda la actividad
			============================================================================= */

				$fecha = date('Y-m-d H:i:s');
				// Valida que exista el empleado si no agrega un cero como id
				$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;
				$sql = "	INSERT INTO
								com_actividades
									(id, empleado, accion, fecha)
							VALUES
								(''," . $usuario . ",'Agrega producto', '" . $fecha . "')";
				$actividad = $this -> query($sql);

			/* FIN Guarda la actividad
			============================================================================= */

			// Aumenta el rating del producto
			$sql = "UPDATE
						app_campos_foodware
					SET
						rate = rate + 1
					WHERE
						id_producto = " . $idproduct;
			$rate = $this -> query($sql);

			// Si es Producir producto obtiene los materiales
			if ($tipo_producto == 5) {
				$querycompuesto = '	SELECT 
										p.id, p.id_producto AS idProducto, p.cantidad, p.id_unidad AS idUnidad, 
										p.id_material AS idMaterial, l.nombre Nom
									FROM 
										app_producto_material p
									INNER JOIN 
											app_productos l 
										ON 
											p.id_material = l.id
									WHERE 
										p.id_producto = ' . $idproduct;
				$productocompuesto = $this -> queryArray($querycompuesto);

				// Si no tiene ningun material solamente inserta el registro
				if (!$productocompuesto['rows']) { 

					// CH@ PRODUCTO 5 RECETA SIN MATERIAL Y EL COSTO SERVICIO
					// producto normal, el costo deberia ser mediante el costeo desde app_inventario movimientos
								
						/*
							$sql3 = "SELECT costo from app_costos_proveedor where id_producto = '$idproduct' order by fecha desc limit 1;";
							$result3 = $this -> queryArray($sql3);
							$costo = $result3['rows'][0]['costo'];
						*/
									
						$sql4 = "SELECT costo_servicio from app_productos where id = '$idproduct';";
						$result4 = $this -> queryArray($sql4);
						$costo = $result4['rows'][0]['costo_servicio'];	
				
					// CH@ PRODUCTO 5 RECETA SIN MATERIAL Y EL COSTO COSTO SERVICIO FIN

					$sql = "INSERT INTO 
								com_pedidos 
									(idcomanda, idproducto, cantidad, npersona, tipo, status, opcionales, adicionales,
										 sin, nota_opcional, nota_extra, nota_sin, costo) 
							VALUES('$idcomanda','$idproduct','1','$idperson','$iddep','-1','$opcionales','$extras',
									'$sin','$nota_opcional','$nota_extra', '$nota_sin','$costo')";
					$product = $this -> insert_id($sql);

					return $product;
				}

				// Recorre los materiales y checa si hay suficientes para hacer el pedido
				foreach ($productocompuesto['rows'] as $value) {
					$stock = 999999;

					//se obtiene la cantida del producto en las comandas
					$querycomanda = 'SELECT 
										count(*) as cantidad_comanda 
									FROM 
										com_pedidos 
									WHERE 
										idProducto=' . $idproduct . ' 
									AND 
										status';
					$cancom = $this -> query($querycomanda);
					$row = $cancom -> fetch_array();
					$cantidadcomandas = $row['cantidad_comanda'];

					// Valida que se pueda crear el producto
					if (($cantidadcomandas * $value['cantidad']) >= $stock) {
						return array("status" => false, "msg" => 'No tienes suficiente insumos para realizar el pedido');
						exit();
					} else {

						// CH@ PRODUCTO 5 RECETA SIN MATERIAL Y EL COSTO SE SACA DEL COSTEO
						
						$costoF = $opc2 = $ext2 = $sin2 = $nor2 = 0;
						
						// COSTO NORMAL  
							$sqlr = 'SELECT costo_receta from com_recetas where id = '.$idproduct.';';
							$resultr = $this -> queryArray($sqlr);
							$nor2 = $resultr['rows'][0]['costo_receta'];
						// COSTO NORMAL FIN

							$sqls = 'SELECT ids_insumos, proveedores_insumos, ids_insumos_preparados from com_recetas where id = '.$idproduct.';';
							$results = $this -> queryArray($sqls);
							$insumos = $results['rows'][0]['ids_insumos'];
							$proveed = $results['rows'][0]['proveedores_insumos'];
							$insumosp = $results['rows'][0]['ids_insumos_preparados'];
							
							$insumosR = explode(',', $insumos);
							$insumospR = explode(',', $insumosp); 

							$proveedR = explode(',', $proveed);													

							$extrasR = explode(',', $extras);
							$opcionalesR = explode(',', $opcionales);
							$sinR = explode(',', $sin);
							
						// COSTO EXTRA
							$new = array_intersect($insumosR, $extrasR); // insumos normales
							$new2 = array_intersect($insumospR, $extrasR); // insumos preparados

							// extras insumos normales
							
							
							foreach ($new as $key => $value) {
								if(!empty($value)){								
									foreach ($proveedR as $k => $v) {
										if($key == $k){


											$sqlf = "SELECT (c.factor / b.factor) factor from app_productos a 
						                            inner join app_unidades_medida b on b.id=a.id_unidad_compra
						                            inner join app_unidades_medida c on c.id=a.id_unidad_venta
						                            where a.id='$value';";
											$resultf = $this -> queryArray($sqlf);
											$factor = $resultf['rows'][0]['factor'];
											
											$sql2 = 'SELECT cantidad from app_producto_material pm where id_producto = '.$idproduct.' and id_material = '.$value.';'; 											
								        	$result2 = $this -> queryArray($sql2);
											$cantidad = $result2['rows'][0]['cantidad'];

											$cantidad = $cantidad * $factor;

											//echo $cantidad.' = '.$cantidad.' * '.$factor;

											$sql = 'SELECT costo from app_costos_proveedor where id_producto = '.$value.' and id_proveedor = '.$v.';';
								        	$result = $this -> queryArray($sql);
											$costo = ($result['rows'][0]['costo']) * ($cantidad*1);									
								        	$ext2 += $costo;

								        	//echo 'costo '.$result['rows'][0]['costo'].' * '.$cantidad;


											$arrayex[]=array(
								                    idproducto   => $value,
								                    idproveedor  => $v,
								                    costo        => $costo,
								                    cantidad     => $cantidad,
								                    sumcosto     => $ext2
								            );
								        
										}
									}
								}
							}
							// extras insumos normales fin
							// extras insumos preparados
							foreach ($new2 as $key2 => $value2) {
								if($value2 != ''){

									$sqlf = "SELECT (c.factor / b.factor) factor from app_productos a 
				                            inner join app_unidades_medida b on b.id=a.id_unidad_compra
				                            inner join app_unidades_medida c on c.id=a.id_unidad_venta
				                            where a.id='$value2';";
									$resultf = $this -> queryArray($sqlf);
									$factor = $resultf['rows'][0]['factor'];

									$sql2 = 'SELECT cantidad from app_producto_material pm where id_producto = '.$idproduct.' and id_material = '.$value2.';'; 											
						        	$result2 = $this -> queryArray($sql2);
									$cantidad = $result2['rows'][0]['cantidad'];

									$cantidad = $cantidad * $factor;

									$sqlp = 'SELECT costo_servicio FROM app_productos where id = '.$value2.';'; 
									$resultp = $this -> queryArray($sqlp);
									$costop = ($resultp['rows'][0]['costo_servicio'])*$cantidad;									
							        $ext2 += $costop;
								}
							}
							// extras insumos preparados fin
						// COSTO EXTRA FIN

						// COSTO ADICIONAL 
							$newo = array_intersect($insumosR, $opcionalesR); // insumos normales
							$newo2 = array_intersect($insumospR, $opcionalesR); // insumos preparados
							// adicionales insumos normales
							foreach ($newo as $key => $value) {
								if(!empty($value)){
									foreach ($proveedR as $k => $v) {
										if($key == $k){

											$sqlf = "SELECT (c.factor / b.factor) factor from app_productos a 
						                            inner join app_unidades_medida b on b.id=a.id_unidad_compra
						                            inner join app_unidades_medida c on c.id=a.id_unidad_venta
						                            where a.id='$value';";
											$resultf = $this -> queryArray($sqlf);
											$factor = $resultf['rows'][0]['factor'];
											
											$sql2 = 'SELECT cantidad from app_producto_material pm where id_producto = '.$idproduct.' and id_material = '.$value.';';										
								        	$result2 = $this -> queryArray($sql2);
											$cantidad = $result2['rows'][0]['cantidad'];

											$cantidad = $cantidad * $factor;

											$sql = 'SELECT costo from app_costos_proveedor where id_producto = '.$value.' and id_proveedor = '.$v.';';
								        	$result = $this -> queryArray($sql);
											$costo = ($result['rows'][0]['costo']) * ($cantidad*1);									
								        	$opc2 += $costo;
								        
										}
									}
								}
							}
							// adicionales insumos normales fin
							// adicionales insumos preparados
							foreach ($newo2 as $key2 => $value2) {
								if($value2 != ''){

									$sqlf = "SELECT (c.factor / b.factor) factor from app_productos a 
				                            inner join app_unidades_medida b on b.id=a.id_unidad_compra
				                            inner join app_unidades_medida c on c.id=a.id_unidad_venta
				                            where a.id='$value2';";
									$resultf = $this -> queryArray($sqlf);
									$factor = $resultf['rows'][0]['factor'];

									$sql2 = 'SELECT cantidad from app_producto_material pm where id_producto = '.$idproduct.' and id_material = '.$value2.';'; 											
						        	$result2 = $this -> queryArray($sql2);
									$cantidad = $result2['rows'][0]['cantidad'];

									$cantidad = $cantidad * $factor;

									$sqlp = 'SELECT costo_servicio FROM app_productos where id = '.$value2.';'; 
									$resultp = $this -> queryArray($sqlp);
									$costop = ($resultp['rows'][0]['costo_servicio'])*$cantidad;									
							        $opc2 += $costop;
								}
							}
							// adicionales insumos preparados fin						
						// COSTO ADICIONAL FIN

						// COSTO SIN
							$news = array_intersect($insumosR, $sinR); // insumos normales
							$news2 = array_intersect($insumospR, $sinR); // insumos preparados
							// adicionales insumos normales


							foreach ($news as $key => $value) {
								if(!empty($value)){
									foreach ($proveedR as $k => $v) {
										if($key == $k){


											$sqlf = "SELECT (c.factor / b.factor) factor from app_productos a 
						                            inner join app_unidades_medida b on b.id=a.id_unidad_compra
						                            inner join app_unidades_medida c on c.id=a.id_unidad_venta
						                            where a.id='$value';";
											$resultf = $this -> queryArray($sqlf);
											$factor = $resultf['rows'][0]['factor'];
											
											$sql2 = 'SELECT cantidad from app_producto_material pm where id_producto = '.$idproduct.' and id_material = '.$value.';';
											/*
											$sql2 = 'SELECT (cantidad / (select factor from app_unidades_medida where unidad_base = 9))cantidad from app_producto_material pm
													left join app_unidades_medida um on um .id = id_unidad where id_producto = '.$idproduct.' and id_material = '.$value.';';
											*/

								        	$result2 = $this -> queryArray($sql2);
											$cantidad = $result2['rows'][0]['cantidad'];

											$cantidad = $cantidad * $factor;

											$sql = 'SELECT costo from app_costos_proveedor where id_producto = '.$value.' and id_proveedor = '.$v.';';
								        	$result = $this -> queryArray($sql);
											$costo = ($result['rows'][0]['costo']) * ($cantidad*1);									
								        	$sin2 += $costo;
								        
										}
									}
								}
							}
							// adicionales insumos normales fin		
							// adicionales insumos preparados
							foreach ($news2 as $key2 => $value2) {
								if($value2 != ''){

									$sqlf = "SELECT (c.factor / b.factor) factor from app_productos a 
				                            inner join app_unidades_medida b on b.id=a.id_unidad_compra
				                            inner join app_unidades_medida c on c.id=a.id_unidad_venta
				                            where a.id='$value2';";
									$resultf = $this -> queryArray($sqlf);
									$factor = $resultf['rows'][0]['factor'];

									$sql2 = 'SELECT cantidad from app_producto_material pm where id_producto = '.$idproduct.' and id_material = '.$value2.';'; 											
						        	$result2 = $this -> queryArray($sql2);
									$cantidad = $result2['rows'][0]['cantidad'];

									$cantidad = $cantidad * $factor;

									$sqlp = 'SELECT costo_servicio FROM app_productos where id = '.$value2.';'; 
									$resultp = $this -> queryArray($sqlp);
									$costop = ($resultp['rows'][0]['costo_servicio'])*$cantidad;									
							        $sin2 += $costop;
								}
							}
							// adicionales insumos preparados fin				
						// COSTO SIN FIN

						// COSTO FINAL
							//echo $costoF.'  '.$nor2.'  '.$ext2.'  '.$opc2.'  '.$sin2.'   ';
							$costoF = ($nor2*1) + ($ext2*1) + ($opc2*1) - ($sin2*1);
						// COSTO FINAL FIN
						
						// CH@ PRODUCTO 5 RECETA FIN
						$sql = "INSERT INTO 
									com_pedidos 
										(idcomanda, idproducto, cantidad, npersona, tipo, status, opcionales, adicionales,
										sin, nota_opcional, nota_extra, nota_sin, costo) 
								VALUES
								('$idcomanda','$idproduct','1','$idperson','$iddep','-1','$opcionales','$extras','$sin',
								'$nota_opcional','$nota_extra', '$nota_sin', '$costoF')";
						$product = $this -> insert_id($sql);

						return $product;
					}
				}
			} else {
				$stock = 999999;

				//se obtiene la cantida del producto en las comandas
				$querycomanda = 'SELECT 
									count(*) as cantidad_comanda 
								FROM 
									com_pedidos 
								WHERE 
									idProducto = ' . $idproduct . ' 
								AND 
									status';
				$cancom = $this -> query($querycomanda);
				$row = $cancom -> fetch_array();
				$cantidadcomandas = $row['cantidad_comanda'];

				if ($cantidadcomandas >= $stock) {
					return array("status" => false, "msg" => 'No tienes suficiente insumos para realizar el pedido');
					exit();
				} else {

					// CH@ PRODUCTO 1 COSTO					
						/* COSTEO
							$sql2 = 'SELECT costo from `app_inventario_movimientos` where id_producto = '.$idproduct.' order by fecha desc limit 1;';					
							$result2 = $this -> query($sql2);
							$costo = $result2['rows'][0]['costo'];
						*/

					$sql3 = "SELECT costo from app_costos_proveedor where id_producto = '$idproduct' order by fecha desc limit 1;";
					$result3 = $this -> queryArray($sql3);
					$costo = $result3['rows'][0]['costo'];
				
					if($costo == ''){
						$sql4 = "SELECT costo_servicio from app_productos where id = '$idproduct';";
						$result4 = $this -> queryArray($sql4);
						$costo = $result4['rows'][0]['costo_servicio'];
					}

					// CH@ PRODUCTO 1 COSTO FIN
					if($tipo_producto == 7){ // combo - el costo se actualiza al ingresar los productos del combo
						$costo = 0;
					}
					

					$sql = "INSERT INTO 
								com_pedidos 
									(idcomanda, idproducto, cantidad, npersona, tipo, status, opcionales, adicionales, sin, 
									nota_opcional, nota_extra, nota_sin, costo) 
							VALUES 
								('$idcomanda','$idproduct','1','$idperson','$iddep','-1','$opcionales','$extras','$sin',
								'$nota_opcional','$nota_extra','$nota_sin', '$costo')";
					$result = $this -> insert_id($sql);

					return $result;
				}

			}
		} else { // con promocion pendiente
			$sql = "INSERT INTO 
								com_pedidos 
									(idcomanda, idproducto, cantidad, npersona, tipo, status, opcionales, adicionales,
										 sin, nota_opcional, nota_extra, nota_sin, id_promocion, dependencia_promocion) 
							VALUES('$idcomanda','$idproduct','1','$idperson','0','-1','','',
									'','','', '', '$id_promocion', '$dependencia_promocion')";
					$product = $this -> insert_id($sql);

					return $product;
		}
	}

///////////////// ******** ---- 		FIN addProduct			------ ************ //////////////////

///////////////// ******** ---- 	act_total_com		------ ************ //////////////////
	// Consulta los departamentos y los devuelve en un array
	// Actualiza el inventario.
	// Como parametros puede recibir:

	function act_total_com($idcomanda, $precio) {
		/*
		$sql = 'UPDATE 
						com_comandas
					SET 
						total = ' . $precio . '
					WHERE 
							id = ' . $idcomanda;
			$total = $this -> query($sql);
			return $total;
		*/
		
	}

///////////////// ******** ---- 	FIN act_total_com	------ ************ //////////////////

///////////////// ******** ---- 	getDeparments		------ ************ //////////////////
	// Consulta los departamentos y los devuelve en un array
	// Actualiza el inventario.
	// Como parametros puede recibir:

	function getDeparments() {
		$sql = "	SELECT 
						id AS idDep, nombre 
					FROM 
						app_departamento";
		$deparments = $this -> queryArray($sql);

		return $deparments;
	}

///////////////// ******** ---- 	FIN getDeparments	------ ************ //////////////////

///////////////// ******** ---- 	getFamilies		------ ************ //////////////////
	// Consulta los departamentos y los devuelve en un array
	// Actualiza el inventario.
	// Como parametros puede recibir:

	function getFamilies($idDeparment) {
		$sql = "SELECT 
					id AS idFam, nombre 
				FROM 
					app_familia 
				WHERE 
					id_departamento = " . $idDeparment;
		$families = $this -> queryArray($sql);

		return $families;
	}

///////////////// ******** ---- 	FIN getFamilies	------ ************ //////////////////

///////////////// ******** ---- 	getLines		------ ************ //////////////////
	// Consulta los departamentos y los devuelve en un array
	// Actualiza el inventario.
	// Como parametros puede recibir:

	function getLines($idFamily) {
		$sql = "SELECT 
					id AS idLin, nombre 
				FROM 
					app_linea 
				WHERE 
					id_familia = " . $idFamily . "
				AND
					activo=1";
		$lines = $this -> queryArray($sql);

		return $lines;
	}

///////////////// ******** ---- 	FIN getLines	------ ************ //////////////////

///////////////// ******** ---- 	fecha_fin		------ ************ //////////////////
	// Consulta los departamentos y los devuelve en un array
	// Actualiza el inventario.
	// Como parametros puede recibir:

	function fecha_fin($id_comanda) {
		$sql = "SELECT 
					fin 
				FROM 
					com_comandas 
				WHERE 
					id = " . $id_comanda;
		$fecha = $this -> queryArray($sql);

		return $fecha["rows"][0]["fin"];
	}

///////////////// ******** ---- 	FIN fecha_fin	------ ************ //////////////////


///////////////// ******** ---- 	closeComanda		------ ************ //////////////////
// Cierra la comanda, separa las mesas(si existen), elimina la mesa(si es temporal)
// Actualiza el inventario.
	// Como parametros puede recibir:
		// $idComanda -> ID de la comanda
		// $bandera -> Como se debe cerrar la comanda (0 -> todo junto, 1 -> individual, 2 -> se paga en caja, 3 -> se manda a caja) 
		// $idmesa -> ID de la mesa
		// $tipo -> si es mesa temporal(para llevar, servicio a domicilio) o normal
		// $id_reservacion -> ID de la reservacion(si existe)
		// reimprime -> bandera que indica que es reimpresion de comanda

	function closeComanda($objeto) {

		$idComanda = $objeto['idComanda'];
		$bandera = $objeto['bandera'];
		$idmesa = $objeto['idmesa'];
		$tipo = $objeto['tipo'];
		$tel = $objeto['tel'];
		$rbandera = $bandera;

		if ($bandera == 2)
			$rbandera = 0;

	// Valida que no venga de la reimpresion
		if ($objeto['reimprime'] != 1) {
		// Borra la union de las tablas si existe
			$sql = "DELETE FROM 
						com_union 
					WHERE 
						idprincipal='$idmesa'";
			$table = $this -> query($sql);

		// Actualiza el estatus de la comanda para marcar como cerrada
			$sql = "UPDATE 
						com_comandas 
					SET 
						status = 2, 
						individual = '" . $rbandera . "' 
					WHERE 
						id = " . $idComanda;
			$status = $this -> query($sql);
		}

	// Elimina la mesa si es servicio a domicilio o para llevar
		if ($tipo == 2 || $tipo == 1) {
			// Valida que no venga de la reimpresion
			if ($objeto['reimprime'] != 1) {
				$sql = "UPDATE
							com_mesas
						SET
							status = 2
						WHERE 
							id_mesa = " . $idmesa;
				$elimina = $this -> query($sql);
			}
		}

		$size = 5 - strlen($idComanda);
		$string = "";

		for ($i = 0; $i < $size; $i++)
			$string .= "0";
		
	// Filtra por persona
		$condicion .= (!empty($objeto['persona'])) ? ' AND a.npersona = '.$objeto['persona'] : '' ;

		if (!empty($objeto['sucursal'])) {
			$sucursal = $objeto['sucursal'];
		} else {
			session_start();
			$sucursal = "	SELECT 
								mp.idSuc AS id 
							FROM 
								administracion_usuarios au 
							INNER JOIN 
									mrp_sucursal mp 
								ON 
									mp.idSuc = au.idSuc 
							WHERE 
								au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
							LIMIT 1";
			$sucursal = $this -> queryArray($sucursal);
			$sucursal = $sucursal['rows'][0]['id'];
		}
	// Obtiene todos los productos de la comanda
		$sql = "SELECT 
					a.npersona, SUM(a.cantidad) cantidad, b.nombre, (CASE WHEN (Select precio from app_precio_sucursal where sucursal = ".$sucursal." and producto = a.idproducto limit 1) IS NULL THEN
				ROUND(b.precio, 2)ELSE ROUND((Select precio from app_precio_sucursal where sucursal = ".$sucursal." and producto = a.idproducto limit 1), 2) END) as precioventa, b.id, 
					a.opcionales, a.nota_opcional, a.nota_extra, a.nota_sin, a.adicionales, a.sin, c.tipo, c.nombre nombreu, c.domicilio, d.codigo, c.nombre AS nombre_mesa,
					d.timestamp AS fecha_comanda, a.complementos, a.id_promocion, (CASE a.id_promocion WHEN 0 THEN 'producto' ELSE a.id END) as tipin, a.monto_desc
				FROM 
					com_pedidos a 
				LEFT JOIN 
						app_productos b 
					ON 
						b.id=a.idproducto 
				LEFT JOIN 
						com_comandas d 
					ON 
						d.id=" . $idComanda . " 
				LEFT JOIN 
						com_mesas c 
					ON 
						c.id_mesa = d.idmesa 
				WHERE 
					idcomanda = " . $idComanda . "
				AND
					a.origen = 1
				AND
					a.status != 3 
				AND 
					a.dependencia_promocion = 0 
				AND 
					cantidad > 0 ".
				$condicion. "
				GROUP BY 
					tipin, a.npersona, a.idProducto, a.opcionales, a.adicionales, a.tipo_desc
				ORDER BY 
					a.npersona ASC, precioventa desc, a.id, a.tipo_desc ASC";
					//print_r($sql);
		$productsComanda = $this -> queryArray($sql);

		$array = Array('rows', 'tipo');

		$contador = 0;
	// La comanda se cierra pagando todo junto
		if (!$bandera) {
			$array['tipo'][0] = 0;

			foreach ($productsComanda['rows'] as $key => $value) {
				if ($value['id_promocion'] == 0) {
					/* Impuestos del producto
					============================================================================= */

					$impuestos_comanda = 0;
					$precio = $value['precioventa'];
					$objeto['id'] = $value['id'];

					$impuestos = $this -> listar_impuestos($objeto);
					if ($impuestos['total'] > 0) {
						foreach ($impuestos['rows'] as $k => $v) {
							if ($v["clave"] == 'IEPS') {
								$producto_impuesto = $ieps = (($v["precio"]) * $v["valor"] / 100);
							} else {
								if ($ieps != 0) {
									$producto_impuesto = ((($v["precio"] + $ieps)) * $v["valor"] / 100);
								} else {
									$producto_impuesto = (($v["precio"]) * $v["valor"] / 100);
								}
							}

						// Precio e impuestos de comanda actualizados
							$precio += $producto_impuesto;
							$precio = round($precio, 2);
							$value['precioventa'] = $precio;

							$impuestos_comanda += $producto_impuesto;
						}
					}

					/* FIN Impuestos del producto
					============================================================================= */

					$items = "";
					$costo_extra = '';

					// Obtiene los opcionales si existen
					if ($value['opcionales'] != "") {
						$sql = "SELECT 
									CONCAT('Con: ',GROUP_CONCAT(nombre)) nombre 
								FROM 
									app_productos 
								WHERE 
									id IN(" . $value['opcionales'] . ")";
						$itemsProduct = $this -> query($sql);

						if ($row = $itemsProduct -> fetch_array()){
							if($value['nota_opcional'] != ''){
								$items .= "(" . $row['nombre'] . ",".$value['nota_opcional'].")";
							} else {
								$items .= "(" . $row['nombre'] . ")";
							}
						} else if($value['nota_opcional'] != '') {
							$items .= "(" . $value['nota_opcional'] . ")";
						}

					} else if($value['nota_opcional'] != '') {
						$items .= "(" . $value['nota_opcional'] . ")";
					}
					
					// Adicionales
					$costo_extra = [];
					if (!empty($value['adicionales'])) {
						$sql = "SELECT 
									CONCAT('Extras: ',GROUP_CONCAT(nombre)) nombre 
								FROM 	
									app_productos 
								WHERE 
									id IN(" . $value['adicionales'] . ")";
						$itemsProduct = $this -> query($sql);

						if ($row = $itemsProduct -> fetch_array())
							$items .= "(" . $row['nombre'] . ")";

					// Obtiene el costo y nombre de los productos
						$sql = "SELECT 
									nombre, ROUND(precio, 2) AS costo, id
								FROM 
									app_productos
								WHERE 
									id IN(" . $value['adicionales'] . ")";
						$costo_extra = $this -> queryArray($sql);
						$costo_extra = $costo_extra['rows'];

					/* Impuestos del producto
					============================================================================= */
					
						foreach ($costo_extra as $kk => $vv) {
							$precio = $vv['costo'];
							$objeto['id'] = $vv['id'];

							$impuestos = $this -> listar_impuestos($objeto);
							if ($impuestos['total'] > 0) {
								foreach ($impuestos['rows'] as $k => $v) {
									if ($v["clave"] == 'IEPS') {
										$producto_impuesto = $ieps = (($v["precio"]) * $v["valor"] / 100);
									} else {
										if ($ieps != 0) {
											$producto_impuesto = ((($v["precio"] + $ieps)) * $v["valor"] / 100);
										} else {
											$producto_impuesto = (($v["precio"]) * $v["valor"] / 100);
										}
									}

								// Precio e impuestos de comanda actualizados
									$precio += $producto_impuesto;
									$precio = round($precio, 2);
									$costo_extra[$kk]['costo'] = $precio;

									$impuestos_comanda += $producto_impuesto;
								}
							}
						}

					/* FIN Impuestos del producto
					============================================================================= */
					}
					

					

					// Sin
					if ($value['sin'] != "") {
						$sql = "SELECT
									CONCAT('Sin: ',GROUP_CONCAT(nombre)) nombre 
								FROM 
									app_productos 
								WHERE 
									id in(" . $value['sin'] . ")";
						$itemsProduct = $this -> query($sql);

						if ($row = $itemsProduct -> fetch_array()){
							if($value['nota_sin'] != ''){
								$items .= "(" . $row['nombre'] . ",".$value['nota_sin'].")";
							} else {
								$items .= "(" . $row['nombre'] . ")";
							}
						} else if($value['nota_opcional'] != '') {
							$items .= "(" . $value['nota_sin'] . ")";
						}
					} else if($value['nota_opcional'] != '') {
						$items .= "(" . $value['nota_sin'] . ")";
					}
					$costo_complementos = [];
					// Si tiene adicionales los agrega al total
					if (!empty($value['complementos'])) {
						$sql = "SELECT 
									CONCAT('Complementos: ',GROUP_CONCAT(nombre)) nombre 
								FROM 	
									app_productos 
								WHERE 
									id IN(" . $value['complementos'] . ")";
						$itemsProduct = $this -> query($sql);

						if ($row = $itemsProduct -> fetch_array())
							$items .= "(" . $row['nombre'] . ")";

					// Obtiene el costo y nombre de los productos
						$sql = "SELECT 
									nombre, ROUND(precio, 2) AS costo, id
								FROM 
									app_productos
								WHERE 
									id IN(" . $value['complementos'] . ")";
						$costo_complementos = $this -> queryArray($sql);
						$costo_complementos = $costo_complementos['rows'];

					/* Impuestos del producto
					============================================================================= */
					
						foreach ($costo_complementos as $kk => $vv) {
							$precio = $vv['costo'];
							$objeto['id'] = $vv['id'];

							$impuestos = $this -> listar_impuestos($objeto);
							if ($impuestos['total'] > 0) {
								foreach ($impuestos['rows'] as $k => $v) {
									if ($v["clave"] == 'IEPS') {
										$producto_impuesto = $ieps = (($v["precio"]) * $v["valor"] / 100);
									} else {
										if ($ieps != 0) {
											$producto_impuesto = ((($v["precio"] + $ieps)) * $v["valor"] / 100);
										} else {
											$producto_impuesto = (($v["precio"]) * $v["valor"] / 100);
										}
									}

								// Precio e impuestos de comanda actualizados
									$precio += $producto_impuesto;
									$precio = round($precio, 2);
									$costo_complementos[$kk]['costo'] = $precio;

									$impuestos_comanda += $producto_impuesto;
								}
							}
						}

					/* FIN Impuestos del producto
					============================================================================= */
					}

					///DESC
					$precioventa = 0;
					 if ($value['monto_desc'] != NULL) {
					 	if($value['monto_desc'] != 0){
					 		$items .= '(Desc. '.$value['monto_desc'].'%)';
						 	$precioventa = $value['precioventa'];
						 	$precioventa = $precioventa - ($precioventa*($value['monto_desc']/100));
					 	}
					 }else{
					 	$precioventa = $value['precioventa'];
					 }
					
					///DESC FIN
				
				
					
					
					
					// Pedido
					$array['rows'][$contador] = Array(
						'impuestos' => $impuestos_comanda, 'costo_extra' => $costo_extra, 
						'costo_complementos' => $costo_complementos, 'npersona' => $value['npersona'], 
						'cantidad' => $value['cantidad'], 'nombre' => $value['nombre'] . " $items", 
						'precioventa' => $value['precioventa'], 'tipo' => $value['tipo'], 
						'nombreu' => $value['nombreu'], 'domicilio' => $value['domicilio'], 
						'codigo' => $value['codigo'], 'nombre_mesa' => $value['nombre_mesa'],
						'monto_desc' => $value['monto_desc'], 'precioUnitarito' => $value['precioventa']
					);

					// Siguiente pedido
					$contador++;

				} else {
					$promocion = [];
					$promociones = [];
					$promocion = $this -> get_promocion($value['id_promocion']);
					$productsComanda['rows'][$key]['nombre'] = $promocion['nombre'];
					$productsComanda['rows'][$key]['tipo_promocion'] = $promocion['tipo'];
					$productsComanda['rows'][$key]['cantidad_to'] = $promocion['cantidad'];
					$productsComanda['rows'][$key]['cantidad_descuento'] = $promocion['cantidad_descuento'];
					$productsComanda['rows'][$key]['descuento'] = $promocion['descuento'];
					$productsComanda['rows'][$key]['precio_fijo'] = $promocion['precio_fijo'];
					$promociones = $this -> get_promociones($value['tipin'], $value['id_promocion']);
					$promociones = $promociones['rows'];					
					$precio = 0;
					$items = '';
					if($promocion['tipo'] == 1){
						foreach ($promociones as $k => $v) {
							$precio += $v['precio'];
							$promociones[$k]['precio'] = 0;
						}
						$desc = (100 - $promocion['descuento']) / 100;
						$precio = $precio * $desc;
						
						$productsComanda['rows'][$key]['precioventa'] = $precio;
					} else if($promocion['tipo'] == 2){
						foreach ($promociones as $k => $v) {
							if($k%2==0){
								$precio += $v['precio'];
							}
							$promociones[$k]['precio'] = 0;
						}
						$productsComanda['rows'][$key]['precioventa'] = $precio;
					} else if($promocion['tipo'] == 4){
						$i = $extras = 0;
						foreach ($promociones as $k => $v) {
							$i++;
							$extras += $promociones[$k]['sumaExtras']; // suma extras
							$precio = $promocion['precio_fijo'];
							$promociones[$k]['precio'] = 0;
						}
						$precio = $precio * $i;
						$productsComanda['rows'][$key]['precioventa'] = $precio + $extras;
						
					} else if($promocion['tipo'] == 3){
						for ($x=0; $x < $promocion['cantidad_descuento']; $x++) { 
							$promociones[(count($promociones)-1) - $x]['precio'] = 0;
						}
						foreach ($promociones as $k => $v) {
							$precio += $v['precio'];
							$promociones[$k]['precio'] = 0;
						}
						$productsComanda['rows'][$key]['precioventa'] = $precio;
					} else if($promocion['tipo'] == 5){
						foreach ($promociones as $k => $v) {
							if($v['comprar'] == 1){
								$precio += $v['precio'];
							}
							$promociones[$k]['precio'] = 0;
						}
						$productsComanda['rows'][$key]['precioventa'] = $precio;
					} 

					//echo '<pre>'; print_r($promociones); exit();
					
					//$productsComanda['rows'][$key]['promociones'] = $promociones;
					//echo '<pre>'; print_r($productsComanda['rows'][$key]); exit();
					// Pedido

					$array['rows'][$contador] = Array(
						'impuestos' => '', 'costo_extra' => '', 
						'costo_complementos' => '', 
						'npersona' => $productsComanda['rows'][$key]['npersona'], 
						'cantidad' => $productsComanda['rows'][$key]['cantidad'],
						'nombre' => $productsComanda['rows'][$key]['nombre'] . " $items", 
						'precioventa' => $productsComanda['rows'][$key]['precioventa'], 
						'tipo' => $productsComanda['rows'][$key]['tipo'], 
						'nombreu' => $productsComanda['rows'][$key]['nombreu'], 
						'domicilio' => $productsComanda['rows'][$key]['domicilio'], 
						'codigo' => $productsComanda['rows'][$key]['codigo'], 
						'nombre_mesa' => $productsComanda['rows'][$key]['nombre_mesa'],
						'promociones' => $promociones,
						'precioUnitarito' => $productsComanda['rows'][$key]['precioventa']
					);

				// Siguiente pedido
					$contador++;
					$precio = 0;
					$items = '';
				}
				
			}
			
			

			
		}

	// La comanda se cierra pagando individual
		if ($bandera == 1) {
			$array['tipo'][0] = 1;
			$impuestos_comanda = 0;
			$person = 0;
			$codigo = "";

			foreach ($productsComanda['rows'] as $value) {
				//print_r($value); exit();
				if ($value['id_promocion'] == 0) {
				/* Impuestos del producto
				============================================================================= */

					$impuestos_comanda = 0;
					$precio = $value['precioventa'];
					$objeto['id'] = $value['id'];

					$impuestos = $this -> listar_impuestos($objeto);
					if ($impuestos['total'] > 0) {
						$impuestos_comanda = 0;

						foreach ($impuestos['rows'] as $k => $v) {
							if ($v["clave"] == 'IEPS') {
								$producto_impuesto = $ieps = (($v["precio"]) * $v["valor"] / 100);
							} else {
								if ($ieps != 0) {
									$producto_impuesto = ((($v["precio"] + $ieps)) * $v["valor"] / 100);
								} else {
									$producto_impuesto = (($v["precio"]) * $v["valor"] / 100);
								}
							}

						// Precio e impuestos de comanda actualizados
							$precio += $producto_impuesto;
							$precio = round($precio, 2);
							$value['precioventa'] = $precio;

							$impuestos_comanda += $producto_impuesto;
						}
					}

				/* FIN Impuestos del producto
				============================================================================= */

					$items = "";
					$costo_extra = '';

					if ($value['opcionales'] != "") {
						$sql = "SELECT 
									CONCAT('Con: ',GROUP_CONCAT(nombre)) nombre 
								FROM 
									app_productos 
								WHERE 
									id IN(" . $value['opcionales'] . ")";
						$itemsProduct = $this -> query($sql);

						if ($row = $itemsProduct -> fetch_array())
							$items .= "(" . $row['nombre'] . ")";
					}

				// Si tiene adicionales los agrega al total
					if ($value['adicionales'] != "") {
						$sql = "SELECT 
									CONCAT('Extras: ',GROUP_CONCAT(nombre)) nombre 
								FROM 	
									app_productos 
								WHERE 
									id IN(" . $value['adicionales'] . ")";
						$itemsProduct = $this -> query($sql);

						if ($row = $itemsProduct -> fetch_array())
							$items .= "(" . $row['nombre'] . ")";

					// Obtiene el costo y nombre de los productos
						$sql = "SELECT 
									nombre, ROUND(precio, 2) AS costo, id
								FROM 
									app_productos 
								WHERE 
									id IN(" . $value['adicionales'] . ")";
						$costo_extra = $this -> queryArray($sql);

						$costo_extra = $costo_extra['rows'];

					/* Impuestos del producto
					============================================================================= */
					
						foreach ($costo_extra as $kk => $vv) {
							$precio = $vv['costo'];
							$objeto['id'] = $vv['id'];

							$impuestos = $this -> listar_impuestos($objeto);
							if ($impuestos['total'] > 0) {
								foreach ($impuestos['rows'] as $k => $v) {
									if ($v["clave"] == 'IEPS') {
										$producto_impuesto = $ieps = (($v["precio"]) * $v["valor"] / 100);
									} else {
										if ($ieps != 0) {
											$producto_impuesto = ((($v["precio"] + $ieps)) * $v["valor"] / 100);
										} else {
											$producto_impuesto = (($v["precio"]) * $v["valor"] / 100);
										}
									}

								// Precio e impuestos de comanda actualizados
									$precio += $producto_impuesto;
									$precio = round($precio, 2);
									$costo_extra[$kk]['costo'] = $precio;

									$impuestos_comanda += $producto_impuesto;
								}
							}
						}

					/* FIN Impuestos del producto
					============================================================================= */
					}
					$costo_complementos = [];
				// Si tiene adicionales los agrega al total
					if (!empty($value['complementos'])) {
						$sql = "SELECT 
									CONCAT('Complementos: ',GROUP_CONCAT(nombre)) nombre 
								FROM 	
									app_productos 
								WHERE 
									id IN(" . $value['complementos'] . ")";
						$itemsProduct = $this -> query($sql);

						if ($row = $itemsProduct -> fetch_array())
							$items .= "(" . $row['nombre'] . ")";

					// Obtiene el costo y nombre de los productos
						$sql = "SELECT 
									nombre, ROUND(precio, 2) AS costo, id
								FROM 
									app_productos
								WHERE 
									id IN(" . $value['complementos'] . ")";
						$costo_complementos = $this -> queryArray($sql);
						$costo_complementos = $costo_complementos['rows'];

					/* Impuestos del producto
					============================================================================= */
					
						foreach ($costo_complementos as $kk => $vv) {
							$precio = $vv['costo'];
							$objeto['id'] = $vv['id'];

							$impuestos = $this -> listar_impuestos($objeto);
							if ($impuestos['total'] > 0) {
								foreach ($impuestos['rows'] as $k => $v) {
									if ($v["clave"] == 'IEPS') {
										$producto_impuesto = $ieps = (($v["precio"]) * $v["valor"] / 100);
									} else {
										if ($ieps != 0) {
											$producto_impuesto = ((($v["precio"] + $ieps)) * $v["valor"] / 100);
										} else {
											$producto_impuesto = (($v["precio"]) * $v["valor"] / 100);
										}
									}

								// Precio e impuestos de comanda actualizados
									$precio += $producto_impuesto;
									$precio = round($precio, 2);
									$costo_complementos[$kk]['costo'] = $precio;

									$impuestos_comanda += $producto_impuesto;
								}
							}
						}

					/* FIN Impuestos del producto
					============================================================================= */
					}
				
					if ($value['sin'] != "") {
						$sql = "SELECT 
									CONCAT('Sin: ',GROUP_CONCAT(nombre)) nombre 
								FROM 
									app_productos 
								WHERE 
									id IN(" . $value['sin'] . ")";
						$itemsProduct = $this -> query($sql);

						if ($row = $itemsProduct -> fetch_array())
							$items .= "(" . $row['nombre'] . ")";
					}

					if ($person != $value['npersona']) {
						$ceros = "";

						if ($value['npersona'] < 10)
							$ceros = "0" . $value['npersona'];

						$codigo = "COM" . $string . $idComanda . "P" . $ceros;
						$person = $value['npersona'];
					}

				// Armamos el array que se devuelve
					$array['rows'][$person]['tipo'] = $value['tipo'];
					$array['rows'][$person]['nombre_usuario'] = $value['nombreu'];
					$array['rows'][$person]['domicilio'] = $value['domicilio'];
					$array['rows'][$person]['codigo'] = $codigo;
					
					///DESC
					$precioventa = 0;
					 if ($value['monto_desc'] != NULL) {
					 	$items .= '(Desc. '.$value['monto_desc'].'%)';
					 	$precioventa = $value['precioventa'];
					 	$precioventa = $precioventa - ($precioventa*($value['monto_desc']/100));
					 }else{
					 	$precioventa = $value['precioventa'];
					 }
					
					///DESC FIN
				
		            
					// Pedido
					$array['rows'][$person]['pedidos'][$contador] = Array(
							'impuestos' => $impuestos_comanda, 'costo_extra' => $costo_extra, 
							'costo_complementos' => $costo_complementos,'npersona' => $value['npersona'], 
							'cantidad' => $value['cantidad'], 'nombre' => $value['nombre'] . " $items", 
							'precioventa' => $precioventa, 'tipo' => $value['tipo'], 'nombreu' => $value['nombreu'], 
							'domicilio' => $value['domicilio'], 'codigo' => $codigo,
							'monto_desc' => $value['monto_desc'], 'precioUnitarito' => $value['precioventa']
					);

					// Siguiente pedido	
					$contador++;
					
				
				} else { 
					$promocion = [];
					$promociones = [];
					$promocion = $this -> get_promocion($value['id_promocion']);
					$value['nombre'] = $promocion['nombre'];
					$value['tipo_promocion'] = $promocion['tipo'];
					$value['cantidad_to'] = $promocion['cantidad'];
					$value['cantidad_descuento'] = $promocion['cantidad_descuento'];
					$value['descuento'] = $promocion['descuento'];
					$value['precio_fijo'] = $promocion['precio_fijo'];
					//echo "<pre>"; print_r($value); exit();
					$promociones = $this -> get_promociones($value['tipin'], $value['id_promocion']);
					$promociones = $promociones['rows'];

					$precio = 0;					

					if($promocion['tipo'] == 1){
						foreach ($promociones as $k => $v) {
							$precio += $v['precio'];
							$promociones[$k]['precio'] = 0;
						}
						$desc = (100 - $promocion['descuento']) / 100;
						$precio = $precio * $desc;
						
						$value['precioventa'] = $precio;
					} else if($promocion['tipo'] == 2){
						foreach ($promociones as $k => $v) {
							if($k%2==0){
								$precio += $v['precio'];
							}
							$promociones[$k]['precio'] = 0;
						}
						$value['precioventa'] = $precio;
					} else if($promocion['tipo'] == 4){
						foreach ($promociones as $k => $v) {
							$precio += $promocion['precio_fijo'];
							$promociones[$k]['precio'] = 0;
						}
						$value['precioventa'] = $precio;
						
					} else if($promocion['tipo'] == 3){
						for ($x=0; $x < $promocion['cantidad_descuento']; $x++) { 
							$promociones[(count($promociones)-1) - $x]['precio'] = 0;
						}
						foreach ($promociones as $k => $v) {
							$precio += $v['precio'];
							$promociones[$k]['precio'] = 0;
						}
						$value['precioventa'] = $precio;
					} else if($promocion['tipo'] == 5){
						//print_r($promociones);
						foreach ($promociones as $k => $v) {
							if($v['comprar'] == 1){
								$precio += $v['precio'];
							}
							$promociones[$k]['precio'] = 0;
						}
						$value['precioventa'] = $precio;
					} 
					if ($person != $value['npersona']) {
						$ceros = "";

						if ($value['npersona'] < 10)
							$ceros = "0" . $value['npersona'];

						$codigo = "COM" . $string . $idComanda . "P" . $ceros;
						$person = $value['npersona'];
					}

					// Armamos el array que se devuelve
					$array['rows'][$person]['tipo'] = $value['tipo'];
					$array['rows'][$person]['nombre_usuario'] = $value['nombreu'];
					$array['rows'][$person]['domicilio'] = $value['domicilio'];
					$array['rows'][$person]['codigo'] = $codigo;
					//echo '<pre>'; print_r($promociones); exit();
					
					//$value['promociones'] = $promociones;
					//echo '<pre>'; print_r($value); exit();
					// Pedido
					$array['rows'][$person]['pedidos'][$contador] = Array(
							'impuestos' => $impuestos_comanda, 'costo_extra' => $costo_extra, 
							'costo_complementos' => $costo_complementos,'npersona' => $value['npersona'], 
							'cantidad' => $value['cantidad'], 'nombre' => $value['nombre'] . " $items", 
							'precioventa' => $value['precioventa'], 'tipo' => $value['tipo'], 'nombreu' => $value['nombreu'], 
							'domicilio' => $value['domicilio'], 'codigo' => $codigo, 'promociones' => $promociones
					);

					// Siguiente pedido	
					$contador++;
					$precio = 0;					
				}
			}				
			
		}

	// La comanda se cierra pagando en caja
		if ($bandera == 2) {
			$array['tipo'][0] = 2;
			$array['rows'][0] = Array('respuesta' => 'ok', 'comanda' => 'COM' . $string . $idComanda);
		}

	// La comanda se manda a caja
		if ($bandera == 3) {
			$array['tipo'][0] = 3;
			$array['rows'][0] = Array('respuesta' => 'ok', 'comanda' => 'COM' . $string . $idComanda);
		}

	// Consulta si se debe de mostrar la propina o no
		$sql = "SELECT
					propina, tipo_operacion, mostrar_dolares
				FROM
					com_configuracion";
		$result = $this -> queryArray($sql);

		$array['mostrar'] = $result['rows'][0]['propina'];
		$array['mostrar_dolares'] = $result['rows'][0]['mostrar_dolares'];
		$array['tipo_operacion'] = $result['rows'][0]['tipo_operacion'];
		$array['tel'] = $tel;

	// ** Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
		// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;
		$sql = "INSERT INTO
					com_actividades
						(id, empleado, accion, fecha)
				VALUES
					(''," . $usuario . ",'Cierra comanda', '" . $fecha . "')";
		$actividad = $this -> query($sql);

	// Valida que no venga de la reimpresion
		if ($objeto['reimprime'] != 1) {
			// Actualiza la fecha de cierre de la comanda
			$sql = "UPDATE
						com_comandas
					SET
						fin = '" . $fecha . "'
					WHERE
						id = " . $idComanda;
			$fin = $this -> query($sql);
		}

		// Actualiza la reservacion
		$sql = "UPDATE
					com_reservaciones
				SET
					activo = 0
				WHERE
					mesa = " . $idmesa;
		$fin = $this -> query($sql);
		
 		
		return $array;
	}


///////////////// ******** ---- 	FIN closeComanda		------ ************ //////////////////

	function reImprimeComanda($idComanda, $bandera, $idmesa, $tipo) {
		$rbandera = $bandera;

		if ($bandera == 2)
			$rbandera = 0;

		if ($tipo)
			$size = 5 - strlen($idComanda);
		$string = "";

		for ($i = 0; $i < $size; $i++)
			$string .= "0";

		$sql = "SELECT 
					a.npersona, SUM(a.cantidad) cantidad, b.nombre, 
					ROUND(b.precio, 2) AS precioventa, a.opcionales, a.adicionales, a.sin, c.tipo, 
					c.nombre nombreu, c.domicilio, d.codigo 
				FROM 
					com_pedidos a 
				INNER JOIN 
						app_productos b 
					ON 
						b.id=a.idproducto 
				LEFT JOIN 
						com_comandas d 
					ON 
						d.id=" . $idComanda . " 
				LEFT JOIN 
						com_mesas c 
					ON 
						c.id_mesa = d.idmesa 
				WHERE 
					idcomanda=" . $idComanda . " 
				AND
					a.origen = 1
				GROUP BY 
					a.npersona, a.idProducto, a.opcionales, a.adicionales 
				ORDER BY 
					a.npersona ASC";
		$productsComanda = $this -> queryArray($sql);

		$array = Array('rows', 'tipo');
		$contador = 0;

	//** Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
	// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;
		$sql = "	INSERT INTO
						com_actividades
							(id, empleado, accion, fecha)
					VALUES
						(''," . $usuario . ",'Reimprime comanda', '" . $fecha . "')";
		$actividad = $this -> query($sql);

		// La comanda se cierra pagando todo junto
		if (!$bandera) {
			$array['tipo'][0] = 0;

			foreach ($productsComanda['rows'] as $value) {
				$items = "";
				$costo_extra = '';

			// Opcionales
				if ($value['opcionales'] != "") {
					$sql = "SELECT 
								CONCAT('Con: ',GROUP_CONCAT(nombre)) nombre 
							FROM 
								app_productos 
							WHERE 
								id IN(" . $value['opcionales'] . ")";
					$itemsProduct = $this -> query($sql);

					if ($row = $itemsProduct -> fetch_array())
						$items .= "(" . $row['nombre'] . ")";
				}

			// Extra
				if ($value['adicionales'] != "") {
					$sql = "SELECT 
								CONCAT('Extras: ',GROUP_CONCAT(nombre)) nombre 
							FROM 
								app_productos 
							WHERE 
								id IN(" . $value['adicionales'] . ")";
					$itemsProduct = $this -> query($sql);

					if ($row = $itemsProduct -> fetch_array())
						$items .= "(" . $row['nombre'] . ")";

				// Obtiene el costo y nombre de los productos
					$sql = "SELECT 
								nombre, ROUND(precio, 2) AS costo
							FROM 
								app_productos 
							WHERE 
								id IN(" . $value['adicionales'] . ")";
					$costo_extra = $this -> queryArray($sql);

					$costo_extra = $costo_extra['rows'];
				}

			// Sin
				if ($value['sin'] != "") {
					$sql = "SELECT 
								CONCAT('Sin: ',GROUP_CONCAT(nombre)) nombre 
							FROM 
								app_productos 
							WHERE 
								id IN(" . $value['sin'] . ")";
					$itemsProduct = $this -> query();
					if ($row = $itemsProduct -> fetch_array())
						$items .= "(" . $row['nombre'] . ")";
				}

				$array['rows'][$contador] = Array('costo_extra' => $costo_extra, 'npersona' => $value['npersona'], 'cantidad' => $value['cantidad'], 'nombre' => $value['nombre'] . " $items", 'precioventa' => $value['precioventa'], 'tipo' => $value['tipo'], 'nombreu' => $value['nombreu'], 'domicilio' => $value['domicilio'], 'codigo' => $value['codigo']);
				$contador++;
			}
		}

		// La comanda se cierra pagando individual
		if ($bandera == 1) {
			$array['tipo'][0] = 1;
			$person = 0;
			$codigo = "COM";

			foreach ($productsComanda['rows'] as $value) {
				$items = "";
				$costo_extra = '';

			// Opcionales
				if ($value['opcionales'] != "") {
					$sql = "SELECT 
								CONCAT('Con: ',GROUP_CONCAT(nombre)) nombre 
							FROM 
								app_productos 
							WHERE 
								id IN(" . $value['opcionales'] . ")";
					$itemsProduct = $this -> query($sql);

					if ($row = $itemsProduct -> fetch_array())
						$items .= "(" . $row['nombre'] . ")";
				}

			// Extra
				if ($value['adicionales'] != "") {
					$sql = "SELECT 
								CONCAT('Extras: ',GROUP_CONCAT(nombre)) nombre 
							FROM 
								app_productos 
							WHERE 
								id IN(" . $value['adicionales'] . ")";
					$itemsProduct = $this -> query($sql);

					if ($row = $itemsProduct -> fetch_array())
						$items .= "(" . $row['nombre'] . ")";

					// Obtiene el costo y nombre de los productos
					$sql = "SELECT 
								nombre, ROUND(precio, 2) AS costo
							FROM 
								app_productos 
							WHERE 
								id IN(" . $value['adicionales'] . ")";
					$costo_extra = $this -> queryArray($sql);

					$costo_extra = $costo_extra['rows'];
				}

			// Sin
				if ($value['sin'] != "") {
					$sql = "SELECT 
								CONCAT('Sin: ',GROUP_CONCAT(nombre)) nombre 
							FROM 
								app_productos 
							WHERE 
								id IN(" . $value['sin'] . ")";
					$itemsProduct = $this -> query($sql);

					if ($row = $itemsProduct -> fetch_array())
						$items .= "(" . $row['nombre'] . ")";
				}

				if ($person != $value['npersona']) {

					$size = 4 - strlen($idComanda);
					$string = "";

					for ($i = 0; $i < $size; $i++)
						$string .= "0";

					$ceros = "";
					if ($value['npersona'] < 10)
						$ceros = "0" . $value['npersona'];

					$codigo = "COM" . $idComanda . "P" . $value['npersona'];
					$person = $value['npersona'];
				}

				$array['rows'][$contador] = Array('costo_extra' => $costo_extra, 'npersona' => $value['npersona'], 'cantidad' => $value['cantidad'], 'nombre' => $value['nombre'] . " $items", 'precioventa' => $value['precioventa'], 'tipo' => $value['tipo'], 'nombreu' => $value['nombreu'], 'domicilio' => $value['domicilio'], 'codigo' => $codigo);
				$contador++;
			}
		}

		// La comanda se cierra pagando en caja
		if ($bandera == 2) {
			$array['tipo'][0] = 2;
			$array['rows'][0] = Array('respuesta' => 'ok', 'comanda' => 'COM' . $string . $idComanda);
		}

		// Consulta si se debe de mostrar la propina o no
		$sql = "	SELECT
						propina, tipo_operacion
					FROM
						com_configuracion";
		$result = $this -> queryArray($sql);

		$array['mostrar'] = $result['rows'][0]['propina'];
		$array['tipo_operacion'] = $result['rows'][0]['tipo_operacion'];

	// Consulta el nombre de la mesa
		$sql = "SELECT
					id_mesa,nombre
				FROM
					com_mesas m
				INNER JOIN
						com_comandas c
					ON
						m.id_mesa = c.idmesa
				WHERE
					c.id = " . $idComanda;
		$result = $this -> queryArray($sql);

		$array['nombre_mesa'] = $result['rows'][0]['nombre'];
		$array['id_mesa'] = $result['rows'][0]['id_mesa'];
		$array['tel'] = $tel;
		
		return $array;
	}

///////////////// ******** ---- 		 getItemsProduct		------ ************ //////////////////
	// ** Busca los materiales del producto y los regresa en un array
	// Como parametro puede recibir:
	// $idProduct -> ID del producto

	function getItemsProduct($idProduct) {
		$sql = "SELECT 
					b.id AS idProducto, b.nombre, a.opcionales 
				FROM 
					app_producto_material a 
				INNER JOIN 
						app_productos b 
					ON 
						b.id=a.id_material 
				WHERE 
					a.id_producto = " . $idProduct;
					
		return $this -> queryArray($sql);
	}

///////////////// ******** ---- 		 FIN getItemsProduct		------ ************ //////////////////

///////////////// ******** ---- 		 repintar_mesas		------ ************ //////////////////
	// ** Busca los materiales del producto y los regresa en un array
	// Como parametro puede recibir:
	// ids_mesas -> IDs de las mesas

	function repintar_mesas($objeto) {
		
		$ids_mesas = explode(",", $objeto['ids_mesas']);
		foreach ($ids_mesas as $key => $value) {
			if($key == 0){
				$where = "a.id_mesa = ". $value;
			}else{
				$where = $where." OR a.id_mesa = ".$value;
			}
		}		
		$sql = "SELECT 
					a.id_mesa AS mesa, a.x, a.y, a.id_area, b.nombre, b.idDep, a.idempleado, ad.nombreusuario AS mesero, tm.id as id_tipo_mesa, tm.tipo_mesa, tm.width, tm.height, tm.imagen, a.nombre as nombre_mesa 
				FROM 
					com_mesas a 
				LEFT JOIN 
					administracion_usuarios ad ON ad.idempleado = a.idempleado 
				LEFT JOIN 
					mrp_departamento b ON b.idDep = a.idDep 
				JOIN
					com_tipo_mesas tm ON a.tipo_mesa = tm.id 
				WHERE 
					a.status = 1 
				AND 
					a.tipo = 0
				AND 
					(".$where.") 
				GROUP BY 
					a.id_mesa 
				ORDER BY a.id_mesa asc";
		$result = $this -> queryArray($sql);
		return $result['rows'];
	}

///////////////// ******** ---- 		 FIN repintar_mesas		------ ************ //////////////////

///////////////// ******** ---- 		 addTemporalTableFg		------ ************ //////////////////
// Agrega una mesa temporal para llevar
	// Como parametros puede recibir:
		// name -> nombre del cliente
		// domicilio -> domicilio del cliente

	function addTemporalTableFg($objeto) {
		session_start();
		if (!empty($objeto['sucursal'])) {
			$sucursal = $objeto['sucursal'];
		} else {
		$sucursal = "SELECT 
						mp.idSuc AS id 
					FROM 
						administracion_usuarios au 
					INNER JOIN 
							mrp_sucursal mp 
						ON 
							mp.idSuc = au.idSuc 
					WHERE 
						au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
					LIMIT 1";
		$sucursal = $this -> queryArray($sucursal);
		$sucursal = $sucursal['rows'][0]['id'];
		}
	// Agrega la mesa temporal
		$sql = "INSERT INTO 
					com_mesas 
						(id_mesa ,idDep, personas, tipo, nombre, domicilio, x, y, idSuc, id_via_contacto) 
				VALUES 
						(null,'0','1','1','" . $objeto['nombre'] . "','" . $objeto['domicilio'] . "',-1,-1, 
						'".$sucursal."', '" . $objeto['via_contacto'] . "')";
		$id = $this -> insert_id($sql);
		
	// Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
	// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;
		$sql = "	INSERT INTO
						com_actividades
							(id, empleado, accion, descripcion, fecha)
					VALUES
						(''," . $usuario . ",'Crea mesa para llevar', 'Crea la mesa [".$id."] ". $objeto['nombre'] . "', 
						'" . $fecha . "')";
		$actividad = $this -> query($sql);
		

		return $id;
	}

///////////////// ******** ---- 		 FIN addTemporalTableFg		------ ************ //////////////////

///////////////// ******** ---- 		 addTemporalTableDs			------ ************ //////////////////
// Agrega una mesa temporal para servicio a domicilio
	// Como parametros puede recibir:
		// name -> nombre del cliente
		// address -> domicilio del cliente

	function addTemporalTableDs($objeto) {
		if (!empty($objeto['sucursal'])) {
			$sucursal = $objeto['sucursal'];
		} else {
		session_start();
		$sucursal = "	SELECT 
							mp.idSuc AS id 
						FROM 
							administracion_usuarios au 
						INNER JOIN 
								mrp_sucursal mp 
							ON 
								mp.idSuc = au.idSuc 
						WHERE 
							au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
						LIMIT 1";
		$sucursal = $this -> queryArray($sucursal);
		$sucursal = $sucursal['rows'][0]['id'];
		}
		$sql = "INSERT INTO 
					com_mesas 
						(id_mesa,idDep,personas,tipo,nombre,domicilio, x, y, idSuc, id_via_contacto, id_zona_reparto) 
				VALUES 
						(null,'0','1','2','" . $objeto['nombre'] . "','" . $objeto['domicilio'] . "',-1,-1, 
						'".$sucursal."', '" . $objeto['via_contacto'] . "', '" . $objeto['zona_reparto'] . "')";
		$id = $this -> insert_id($sql);
		
	// Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
	// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;
		$sql = "	INSERT INTO
						com_actividades
							(id, empleado, accion, descripcion, fecha)
					VALUES
						(''," . $usuario . ",'Crea mesa de servicio a domicilio', 'Crea la mesa [".$id."] ". $objeto['nombre'] . "', 
						'" . $fecha . "')";
		$actividad = $this -> query($sql);

		return $id;
	}

///////////////// ******** ---- 		 FIN addTemporalTableDs		------ ************ //////////////////

///////////////// ******** ---- 		 		removeTable			------ ************ //////////////////
// Elimina la mesa y sus pedidos si existen
	// Como parametros puede recibir:
		// $idmesa -> ID de la mesa

	function removeTable($idmesa) {
	// Elimina la mesa
		$sql = "UPDATE
					com_mesas
				SET
					status = 2
				WHERE
					id_mesa = " . $idmesa;
		// return $sql;
		$query = $this -> query($sql);


	// Consulta si la mesa tiene comanda
		$sql = "SELECT 
					id 
				FROM 
					com_comandas 
				WHERE 
					idmesa = " . $idmesa . "
				AND
					status = 0";
		$id = $this -> queryArray($sql);
		$id = $id['rows'][0]['id'];

	// Elimina la comnada y los pedidos si hay una comanda abierta
		if (!empty($id)) {
			$sql = "UPDATE 
						com_pedidos 
					set
						status = 3
					WHERE 
						idcomanda = " . $id;
			$query = $this -> query($sql);

		// Elimina la comanda
			$sql = "UPDATE 
						com_comandas 
					set
						status = 3
					WHERE 
						id = " . $id;
			$query = $this -> query($sql);
		}

	//** Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
	// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;
		$sql = "INSERT INTO
					com_actividades
						(id, empleado, descripcion, accion, fecha)
				VALUES
					(''," . $usuario . ",'Elimina mesa', 'Comanda: ".$id."', '" . $fecha . "')";
		// return  $sql;
		$actividad = $this -> query($sql);
		
		return $query;
	}
	
	/* Junta las tablas */
	/* Recibe como parametro $jtables que son las mesas que desea juntar */
	function joinTables($jtables) {
		// Separa las tablas a juntar en un array
		$tables = explode(",", $jtables);
		
		//Variable para saber si existe una mesa principal en la tabla union
		$existPrinc = 0;

		for($x = 0; $x < count($tables); $x++) {
			// Compara si en esa posicion existe un idprincipal con esa mesa
			$sql = "SELECT 
						idprincipal 
					FROM 
						com_union 
					WHERE 
						idprincipal = $tables[$x]";
			$idcomandas = $this -> queryArray($sql);

			// En caso que exista una idprincipal con esa mesa el primero en pasar se reemplazara con la primer posicion del array 
			// y la variable $existPrinc se cambiara de valor para en caso de que exista otro idprincipal no lo cambie
			if (count($idcomandas["rows"]) > 0 && $existPrinc == 0) {
				$aux = $tables[0];
				$tables[0] = $tables[$x];
				$tables[$x] = $aux;
				$existPrinc = 1;
			} 
			// Entrara siempre y cuando ya exista una mesa con idprincipal
			else if($existPrinc != 0){
				// Actualiza la tabla com_union para que las tablas ya juntas se junten con las otras mesas
				$sql = "UPDATE 
							com_union
						SET 
							idprincipal = $tables[0]
						WHERE 
							idprincipal = $tables[$x]";
				$this -> query($sql);
			}
		}
	
		// Genera fecha
		$fecha = date('Y-m-d H:i:s');
		// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;
		// Inserta la actividad de juntar mesas
		$sql = "	INSERT INTO
						com_actividades
							(id, empleado, accion, fecha)
					VALUES
						(''," . $usuario . ",'Junta mesas', '" . $fecha . "')";
		$actividad = $this -> query($sql);

		// Se dirige a la funcion "updateTodoJuntar"
		$idcomanda = $this->updateTodoJuntar($tables);


		return $idcomanda;

	}

//** * * * *			- - - 		Actualiza el inventario de productos en stock		-	-	-	**  * * * * //

//** * * * *			- - - 		Fin joinTables 		-	-	-	**  * * * * //


//** * * * *			- - - 		updateTodoJuntar		-	-	-	**  * * * * //
// Actualiza las personas y las mueve de comandas con todo y productos
// Recibe como parametros: 
// $tables: tablas a juntar
	function updateTodoJuntar($tables){
		// Primer posicion en $tables es el id_principal
		$id_principal = $tables[0];

		// Busca si existe comanda en la mesa principal
		$sql = "SELECT 
					id 
				FROM 
					com_comandas 
				WHERE 
					status = 0
				and
					idmesa = $id_principal";
		$idcomanda = $this -> queryArray($sql);
		
		// En caso de que exista comanda guarda el id de dicha comanda
		if(count($idcomanda["rows"]) > 0){
			$idcomanda = $idcomanda["rows"][0];
			$idcomanda = $idcomanda["id"];
		}

		foreach ($tables as $i => $key) {
			// Busca en la tabla com_union si existe campo que tenga la mesa principal y en idmesa tengan la mesa actual en el foreach
			$sql = "SELECT 
						idprincipal 
					FROM 
						com_union 
					WHERE 
						idprincipal='" . $id_principal . "' 
					AND 
						idmesa='" . $key . "'";
			$union = $this -> query($sql);

			// Checa si $idcomanda trae datos o si no es numerico
			if(count($idcomanda["rows"]) == 0 || !is_numeric($idcomanda)){
				// Compara si existe comanda para la tabla actual en el foreach
				$sql = "SELECT 
							id 
						FROM 
							com_comandas 
						WHERE 
							status = 0
						and
							idmesa = $key";
				$idcomanda = $this -> queryArray($sql);
				// Checa si la query anterior si trae datos
				if(count($idcomanda["rows"]) > 0){
					// Se iguala a la primera row de los resultados
					$idcomanda = $idcomanda["rows"][0];
					
					// Se actualiza el todos los id de la mesa a la de la mesa principal dependiendo el id de la comanda 
					$sql = "UPDATE 
								com_comandas 
							SET 
								idmesa = $id_principal
							WHERE 
								id = " . $idcomanda["id"];
					$this -> query($sql);
				}
			}
			else{
				// Compara si idcomanda es diferente a un numero
				if(!is_numeric($idcomanda)){
					$idcomanda = $idcomanda["rows"][0];
					$idcomanda = $idcomanda["id"];
				}
				// Compara si existe comanda para la tabla actual en el foreach
				$sql = "SELECT 
							id 
						FROM 
							com_comandas 
						WHERE 
							status = 0
						and
							idmesa = $key";
				$idcomandasec = $this -> queryArray($sql);
				// Checa si la query anterior si trae datos
				if(count($idcomandasec["rows"]) > 0){
					$idcomandasec = $idcomandasec["rows"][0];
					$idcomandasec = $idcomandasec["id"];
					// En caso de que la comanda principal sea diferente a la comanda secundaria entra al if
					if($idcomanda != $idcomandasec){
						// Da de baja la comanda secundaria
						$sql = "UPDATE 
									com_comandas 
								SET 
									status = 3
								WHERE 
									id = " . $idcomandasec;
						$this -> query($sql);
						// Busca las persoanas que tienen pedidos
						$sql = "SELECT 
									id, npersona, idcomanda
								FROM 
									com_pedidos 
								WHERE 
									status != 3
								and
									idcomanda = $idcomanda
								and
									idproducto != 0
								or 
									status != 3
								and
									idcomanda = $idcomandasec
								and
									idproducto != 0
								group by 
									npersona, idcomanda";
						$npersonas = $this -> queryArray($sql);
						$npersonas = $npersonas["rows"];
						// Foreach para las personas que tienen pedidos
						foreach ($npersonas as $key2 => $value) {
							// Variable para saber que numero de persona colocarle
							$nper = $key2+1;
							// ACtualiza el numero de persona en cada pedido y cambia el idcomanda a 0 para no confundirlo dependiendo el numero de personas y el id de su comanda
							$sql = "UPDATE 
										com_pedidos
									SET 
										idcomanda = 0,
										npersona = $nper
									WHERE 
										idcomanda = " . $value['idcomanda'] . "
									and 
										npersona = ".$value['npersona'];
							$this -> query($sql);
						}
						// Actualiza el id de la comanda al de la comanda principal
						$sql = "UPDATE 
									com_pedidos
								SET 
									idcomanda = $idcomanda
								WHERE 
									idcomanda = 0";
						$this -> query($sql);
					}
				}
			}
			// Compara si la query anterior nos regreso algo
			if (!($row = $union -> fetch_array())) {

				// Inserta en la tabla com_union las uniones con el id_principal y la mesa actuale en el foreach
				$sql = "INSERT INTO 
							com_union 
								(idprincipal,idmesa) 
						VALUES 
							(" . $id_principal . "," . $key . ")";
				$this -> query($sql);
			}
		}
		return $idcomanda;	
	}
//** * * * *			- - - 		Fin updateTodoJuntar 		-	-	-	**  * * * * //


//** * * * *			- - - 		Actualiza el inventario de productos en stock		-	-	-	**  * * * * //
// Procesa los pedidos
	function process($idcomanda, $sucursal) {
	// Valida el usuario
		$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : $_SESSION['accelog_idempleado'];
		

		$sql = "SELECT 
					pe.*, p.tipo_producto
				FROM 
					com_pedidos pe
				LEFT JOIN
						app_productos p
					ON
						p.id = pe.idproducto
				WHERE 
					pe.status = '-1' 
				AND
					pe.origen = 1
				AND 
					idcomanda =" . $idcomanda;
		$pedidos = $this -> queryArray($sql);
		
		if (!empty($sucursal)) {
			// Obtiene el almacen
			$almacen = "SELECT 
							a.id
						FROM 
							administracion_usuarios au
						LEFT JOIN 
								app_almacenes a
							ON 
								a.id_sucursal = au.idSuc
						WHERE 
							au.idSuc = " . $sucursal . " 
						AND 
							a.activo = 1
						LIMIT 1";
			$almacen = $this -> queryArray($almacen);
			$almacen = $almacen['rows'][0]['id'];
		} else {
			session_start();
		// Obtiene el almacen
			$almacen = "SELECT 
							a.id
						FROM 
							administracion_usuarios au
						LEFT JOIN 
								app_almacenes a
							ON 
								a.id_sucursal = au.idSuc
						WHERE 
							au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
						AND 
							a.activo = 1
						LIMIT 1";
			$almacen = $this -> queryArray($almacen);
			$almacen = $almacen['rows'][0]['id'];
		}
	// Valida que exista el almacen
		$almacen = (empty($almacen)) ? 1 : $almacen;

		$fecha = date('Y-m-d H:i:s');

	/* Actualiza el inventario
	=========================================================================== */

		foreach ($pedidos['rows'] as $key => $value) {
			// Obtiene los insumos normales
			$sql = "SELECT
						p.id, m.cantidad, ROUND(p.precio, 2) AS precio, m.cantidad AS importe, p.formulaieps AS formula
					FROM
						app_productos p
					INNER JOIN
							app_producto_material m
						ON
							m.id_material = p.id
					WHERE
						m.id_producto = " . $value['idproducto'] ."
					AND
						m.opcionales LIKE '%0%'";
			// return $sql;
			$normales = $this -> queryArray($sql);
			
			// Actualiza el inventario por cada insumo
			foreach ($normales['rows'] as $k => $v) {
				$sql = "INSERT INTO
							app_inventario_movimientos
							(id_producto, cantidad, importe, id_almacen_origen, fecha, id_empleado,
							tipo_traspaso, costo, referencia)
						VALUES
							('" . $v['id'] . "', '" . $v['cantidad'] . "', '" . $v['importe'] . "', '" . $almacen . "', '" . $fecha . "', 
							'" . $usuario . "', 0, '" . $v['importe'] . "', 'Pedido " . $value['id'] . "')";
				$result_opcional = $this -> query($sql);
			}
			
			// Opcionales
			if (!empty($value['opcionales'])) {
			// Filtra solo por los opcionales seleccionados
				$condicion = (!empty($value['opcionales'])) ? " AND p.id IN(" . $value['opcionales'] . ")" : "";
				
			// Obtiene los productos
				$sql = "SELECT
							p.id, m.cantidad, ROUND(p.precio, 2) AS precio, m.cantidad AS importe, p.formulaieps AS formula
						FROM
							app_productos p
						INNER JOIN
								app_producto_material m
							ON
								m.id_material = p.id
						WHERE
							m.id_producto = " . $value['idproducto'] . $condicion;
				// return $sql;
				$opcionales = $this -> queryArray($sql);
				
				// Actualiza el inventario por cada producto
				foreach ($opcionales['rows'] as $k => $v) {
					$sql = "INSERT INTO
								app_inventario_movimientos
								(id_producto, cantidad, importe, id_almacen_origen, fecha, id_empleado,
								tipo_traspaso, costo, referencia)
							VALUES
								('" . $v['id'] . "', '" . $v['cantidad'] . "', '" . $v['importe'] . "', '" . $almacen . "', '" . $fecha . "', 
								'" . $usuario . "', 0, '" . $v['importe'] . "', 'Pedido " . $value['id'] . "')";
					$result_opcional = $this -> query($sql);
				}
			}

			// Sin
			if (!empty($value['sin'])) {
			// Excluye los insumos sin del inventario
				$condicion = (!empty($value['sin'])) ? " AND p.id NOT IN(" . $value['sin'] . ")" : "";
				
			// Obtiene los productos
				$sql = "SELECT
							p.id, m.cantidad, ROUND(p.precio, 2) AS precio, m.cantidad AS importe, p.formulaieps AS formula
						FROM
							app_productos p
						INNER JOIN
								app_producto_material m
							ON
								m.id_material = p.id
						WHERE
							m.id_producto = " . $value['idproducto'] . $condicion;
				// return $sql;
				$sin = $this -> queryArray($sql);
				
			// Actualiza el inventario por cada producto
				foreach ($sin['rows'] as $k => $v) {
					$sql = "INSERT INTO
								app_inventario_movimientos
								(id_producto, cantidad, importe, id_almacen_origen, fecha, id_empleado,
								tipo_traspaso, costo, referencia)
							VALUES
								('" . $v['id'] . "', '" . $v['cantidad'] . "', '" . $v['importe'] . "', '" . $almacen . "', '" . $fecha . "', 
								'" . $usuario . "', 0, '" . $v['importe'] . "', 'Pedido " . $value['id'] . "')";
					$result_opcional = $this -> query($sql);
				}
			}
		
			// Extras
			if (!empty($value['adicionales'])) {
				$sql = "SELECT
							p.id, m.cantidad, ROUND(p.precio, 2) AS precio, m.cantidad AS importe, p.formulaieps AS formula
						FROM
							app_productos p
						INNER JOIN
								app_producto_material m
							ON
								m.id_material = p.id
						WHERE
							p.id IN(" . $value['adicionales'] . ")
						AND
							m.id_producto = " . $value['idproducto'];
				$adicionales = $this -> queryArray($sql);

			// Actualiza el inventario por cada producto
				foreach ($adicionales['rows'] as $k => $v) {
					$sql = "INSERT INTO
									app_inventario_movimientos
									(id_producto, cantidad, importe, id_almacen_origen, fecha, id_empleado,
									tipo_traspaso, costo, referencia)
								VALUES
									('" . $v['id'] . "', '" . $v['cantidad'] . "', '" . $v['importe'] . "', '" . $almacen . "', '" . $fecha . "', 
									'" . $usuario . "', 0, '" . $v['importe'] . "', 'Pedido " . $value['id'] . "')";
					$result_adicionales = $this -> query($sql);
				}
			}
		
			// Complementos
			if (!empty($value['complementos'])) {
				$sql = "SELECT
							p.id, c.cantidad, ROUND(p.precio, 2) AS precio, c.cantidad AS importe, p.formulaieps AS formula
						FROM
							com_complementos c
						LEFT JOIN
								app_productos p
							ON
								p.id = c.id_producto
						LEFT JOIN
								app_costos_proveedor pro
							ON
								pro.id_producto = p.id
						WHERE
							c.id_producto IN(" . $value['complementos'] . ")";
				$complementos = $this -> queryArray($sql);

			// Actualiza el inventario por cada producto
				foreach ($complementos['rows'] as $k => $v) {
					$sql = "INSERT INTO
									app_inventario_movimientos
									(id_producto, cantidad, importe, id_almacen_origen, fecha, id_empleado,
									tipo_traspaso, costo, referencia)
								VALUES
									('" . $v['id'] . "', '" . $v['cantidad'] . "', '" . $v['importe'] . "', '" . $almacen . "', '" . $fecha . "', 
									'" . $usuario . "', 0, '" . $v['importe'] . "', 'Pedido " . $value['id'] . "')";
					$result_adicionales = $this -> query($sql);
				}
			}
			
			// Receta(Crea una entrada al incentario si es receta)
			if ($value['tipo_producto'] == 5) {
				$sql = "INSERT INTO
							app_inventario_movimientos
							(id_producto, cantidad, importe, id_almacen_destino, fecha, id_empleado,
							tipo_traspaso, costo, referencia)
						VALUES
							('" . $value['idproducto'] . "', '" . $value['cantidad'] . "', '" . $value['cantidad'] . "', '" . $almacen . "', '" . $fecha . "', 
							'" . $usuario . "', 1, '" . $value['cantidad'] . "', 'Pedido " . $value['id'] . "')";
				$result_receta = $this -> query($sql);
			}

			$value['sucursal'] = $sucursal;
			// Kit
			if ($value['tipo_producto'] == 6) {
				$result = $this -> actualizar_inventario_kit($value);
			}

			// Combo
			if ($value['tipo_producto'] == 7) {
				$result = $this -> actualizar_inventario_combo($value);
			}

		} //FIN foreach

	/* FIN Actualiza el inventario
	=========================================================================== */

	//** Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
	// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;
		$sql = "INSERT INTO
					com_actividades
						(id, empleado, accion, fecha)
				VALUES
					(''," . $usuario . ",'Procesa pedidos', '" . $fecha . "')";
		$actividad = $this -> query($sql);

	// Actuliza el estado del pedido para indicar que se dio de alta (status='0')
		$sql = "UPDATE 
					com_pedidos 
				SET 
					status = '0' 
				WHERE 
					status = '-1' 
				AND 
					idcomanda = " . $idcomanda;
		$result = $this -> query($sql);
	
	// Consulta el tipo de operacion y lo devuelve
		$sql = "SELECT
					tipo_operacion
				FROM
					com_configuracion";
		$result = $this -> queryArray($sql);
		$result = $result['rows'][0];

		return $result;
	}

//** * * * *			- - - 		FIN Actualiza el inventario de productos en stock		-	-	-	**  * * * * //

	//Consulta si hay productos terminados en cocina
	function checkProducts() {
		$queryCheck = "	SELECT 
							idproducto, p.idcomanda, d.nombre AS lugar
						FROM
							com_pedidos p
						INNER JOIN
								app_departamento d
							ON
								p.tipo = d.id
						INNER JOIN
								com_comandas c
							ON
								p.idcomanda = c.id
						WHERE 
							p.status = 2 
						AND 
							c.status = 0";
		$result = $this -> queryArray($queryCheck);

		return $result;
	}

	// Consulta las mesas y su estado
	function checkTables($objeto) {
	 $sql = "SELECT 
			(SELECT id FROM com_reservaciones r WHERE r.mesa = m.id_mesa AND r.activo = 1 AND (r.inicio >= '".$objeto['f_ini']."' AND r.inicio <= '".$objeto['f_fin']."')) id_res,
			IF(c.id IS NULL,0,c.id) id_comanda,
			m.id_mesa AS mesa, 
			m.status AS mesa_status,
			m.tipo tipoMesa, 
			c.personas, 
			c.tipo, 
			c.abierta, 
			c.status, 
			m.tipo_mesa,
			m.notificacion,
			u.idprincipal AS juntas, 
			m.status statusMesa
			FROM com_mesas m
			LEFT JOIN com_comandas c ON m.id_mesa = c.idmesa AND c.status = 0 
			LEFT JOIN com_union u ON u.idprincipal = m.id_mesa
			WHERE m.status = 1 AND m.tipo = 0 AND m.tipo_mesa IS NOT NULL AND m.tipo_mesa != 7 AND m.tipo_mesa != 8 OR m.status = 4 AND m.tipo_mesa IS NOT NULL OR (m.tipo = 3 and (m.status = 2 or m.status = 1))
		GROUP BY mesa ORDER BY mesa ASC;";
		//print_r($queryCheck);
		$result = $this -> queryArray($sql);

		return $result;
	}

	//Marcamos los productos como entregados
	function entregado($comanda, $ids) {
		//** Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
		// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;
		$sql = "	INSERT INTO
						com_actividades
							(id, empleado, accion, fecha)
					VALUES
						(''," . $usuario . ",'Entrega pedidos', '" . $fecha . "')";
		$actividad = $this -> query($sql);

		$ids = str_replace('|', ',', $ids);
		$updateProducto = "		Update 
										com_pedidos ";
		$updateProducto .= " 	SET 
										status=4 ";
		$updateProducto .= " 	WHERE 
										idproducto in(" . $ids . ") 
									AND 
										idcomanda = " . $comanda . " 
									AND 
										status = 2";
		$result = $this -> query($updateProducto);

		return $result;
	}

	function getNames($name) {
		$sql = "	SELECT 
						id, nombre 
					FROM 
						comun_cliente 
					WHERE 
						nombre LIKE '$name%' 
					LIMIT 
						10";
		return $this -> queryArray($sql);
	}

///////////////// ******** ---- 	buscar_direccion		------ ************ //////////////////
//////// Consulta la direccion del cliente en la BD
	// Como parametros puede recibir:
	// nombre-> nombre escrito en el campo de texto

	function buscar_direccion($objet) {
		$sql = "SELECT 
					direccion
				FROM 
					comun_cliente
				WHERE
					nombre = '" . $objet['nombre'] . "'";
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN buscar_direccion		------ ************ //////////////////

///////////////// ******** ---- 		getSillas		------ ************ //////////////////
//////// Consulta las mesas y las regresa en un array
	// Como parametros recibe:
	// mesa -> id de la mesa

	function getSillas($mesa) {
		if (!empty($objeto['sucursal'])) {
			$sucursal = $objeto['sucursal'];
		} else {
		// Obtiene la sucursal
			session_start();
			$sucursal = "	SELECT 
								mp.idSuc AS id 
							FROM 
								administracion_usuarios au 
							INNER JOIN 
									mrp_sucursal mp 
								ON 
									mp.idSuc = au.idSuc 
							WHERE 
								au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
							LIMIT 1";
			$sucursal = $this -> queryArray($sucursal);
			$sucursal = $sucursal['rows'][0]['id'];
		}
		$sql = "SELECT
						a.id_mesa AS mesa, res.id as id_res, a.x, a.y, a.width as width_barra, a.id_area, a.height as height_barra, b.nombre, b.idDep, a.status as mesa_status, a.personas, a.domicilio, a.idempleado,
						ad.nombreusuario AS mesero, a.notificacion, 

						IF(GROUP_CONCAT(c.idmesa) is NULL, a.nombre, (SELECT GROUP_CONCAT(d.nombre) FROM com_mesas d INNER JOIN com_union c ON c.idmesa=d.id_mesa WHERE c.idprincipal = a.id_mesa )) nombre_mesa,
						if(GROUP_CONCAT(c.idmesa) is NULL,'',GROUP_CONCAT(c.idmesa)) idmesas, 
						(LENGTH(if(GROUP_CONCAT(c.idmesa) is NULL,'',GROUP_CONCAT(c.idmesa))) - LENGTH(REPLACE(if(GROUP_CONCAT(c.idmesa) is NULL,'',GROUP_CONCAT(c.idmesa)), ',', '')) + 1) cantsillas,
						if(GROUP_CONCAT(d.personas) is NULL,'',GROUP_CONCAT(d.personas)) mpersonas,

						if(e.id is NULL,0,e.id) idcomanda,
						tm.id as id_tipo_mesa, tm.tipo_mesa, tm.width, tm.height, tm.imagen 
					FROM 
						com_mesas a
					LEFT JOIN
							administracion_usuarios ad
						ON
							ad.idempleado = a.idempleado
					LEFT JOIN 
							mrp_departamento b 
						ON 
							b.idDep = a.idDep

					LEFT JOIN mrp_sucursal s ON s.idSuc = a.idSuc
					LEFT JOIN com_union c ON c.idprincipal = a.id_mesa   
					
					LEFT JOIN 
							com_mesas d 
						ON 
							d.id_mesa = a.id_mesa 
					LEFT JOIN 
							com_comandas e 
						ON 
							e.idmesa = a.id_mesa 
						AND 
							e.status = 0 
					LEFT JOIN 
						com_reservaciones res 
						ON 
							res.mesa = a.id_mesa 
						AND 
							res.activo = 1
						AND 
							(res.inicio >= '".$mesa['f_ini']."' 
						AND 
							res.inicio <= '".$mesa['f_fin']."')
					JOIN 
						com_tipo_mesas tm ON a.tipo_mesa = tm.id
					WHERE
						a.status = 1 
					AND ( a.id_mesa NOT IN(select idmesa from com_union) OR a.id_mesa IN(select idprincipal from com_union) )
					and
						a.tipo = 0 
					". $empleado ." 
					AND 
						a.id_dependencia = ". $mesa['mesa'] ."
					AND
						a.idSuc = " . $sucursal ."
					OR
						a.status = 4
					and
						a.tipo = 0 
					". $empleado ." 
					AND 
						a.id_dependencia = ". $mesa['mesa'] ."
					AND
						a.idSuc = " . $sucursal ."
					GROUP BY 
						a.id_mesa 
					ORDER BY 
						a.id_mesa asc";

		$sillaBarra = $this -> queryArray($sql);
		// return $sql;
		return $sillaBarra['rows'];
	}
///////////////// ******** ---- 		FIN getSillas		------ ************ //////////////////

/////////////////////// * * * * * -- 			logo			--	* * * * * * * *  * * *  //////////////////////
	//** Consulta el logo de la empresa
	// Como parametro recibe:
	// id-> id de la empresa

	function logo($objet) {
		$condicion .= (!empty($objet['id'])) ? ' AND idorganizacion=\'' . $objet['id'] . '\'' : '';

		$sql = "
				SELECT 
					logoempresa as logo
				FROM 
					organizaciones
				WHERE 
					1=1" . $condicion;
		// return $sql;
		$result = $this -> queryArray($sql);

		return $result;
	}

/////////////////////// * * * * * -- 		FIN	logo			--	* * * * * * * *  * * *  //////////////////////

///////////////// ******** ---- 			info_estados		------ ************ //////////////////
//////// Obtiene la informacion de los estados
//////// Crea un select con la informacion de los estados
	// Como parametros puede recibir:
	// id-> id del estado

	function info_estados($objeto) {
		$condicion .= (!empty($objet['id'])) ? ' AND idestado=\'' . $objet['id'] . '\'' : '';

		$sql = "SELECT 
					*
				FROM 
					estados
				WHERE 
					1=1 " . $condicion;
		// return $sql;
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN	info_estados		------ ************ //////////////////

///////////////// ******** ---- 		info_municipios			------ ************ //////////////////
//////// Obtiene la informacion de los municipios
//////// Crea un select con la informacion de los municipios
	// Como parametros puede recibir:
	// id-> id del estado

	function info_municipios($objeto) {
		// Si viene el id del estado Filtra por el id del estado
		$condicion .= (!empty($objeto['id_estado'])) ? ' AND idestado=\'' . $objeto['id_estado'] . '\'' : '';

		$sql = "
					SELECT 
						*
					FROM 
						municipios
					WHERE 
						1=1 " . $condicion;
		// return $sql;
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN	info_municipios		------ ************ //////////////////

///////////////// ******** ---- 		agregar_cliente		------ ************ //////////////////
//////// Agrega un cliente a la base de datos en la tabla comun_cliente
	// Como parametros puede recibir:
		// Campos del formulario:
		// -> Nombre, Direccion, Numero interios, Numero Exterior
		// -> Colonia, CP, estado, Municipio, E-mail, Tel

	function agregar_cliente($objeto) {
		foreach ($objeto as $key => $value) {
			$datos[$key] = $this -> escapalog($value);
		}

		$sql = "INSERT INTO 
					comun_cliente
						(nombre, direccion, num_ext, num_int, cp, colonia, celular, telefono1, email)
				VALUES
					('".$datos['nombre']."', '".$datos['direccion']."', '".$datos['exterior']."', '".$datos['interior']."', '".$datos['cp']."',
						'".$datos['colonia']."', '".$datos['cel']."', '".$datos['tel']."', '".$datos['email']."');";
		$result = $this -> insert_id($sql);

	// Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
	// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;
		$sql = "	INSERT INTO
							com_actividades
								(id, empleado, accion, descripcion,  fecha)
						VALUES
							(''," . $usuario . ",'Agrega cliente','Agrega el cliente [".$result."] ".$datos['nombre']."', '" . $fecha . "')";
		$actividad = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN	agregar_cliente		------ ************ ///////////////////////////////////

///////////////// ******** ---- 		editar_cliente		------ ************ //////////////////
//////// Agrega un cliente a la base de datos en la tabla comun_cliente
	// Como parametros puede recibir:
		// Campos del formulario:
		// -> Nombre, Direccion, Numero interios, Numero Exterior
		// -> Colonia, CP, estado, Municipio, E-mail, Tel

	function editar_cliente($objeto) {
		foreach ($objeto as $key => $value) {
			$datos[$key] = $this -> escapalog($value);
		}
		$campos = '';
		if(!empty($datos['nombre']))
			$campos .= " nombre = '".$datos['nombre']."' ";

		if(!empty($datos['direccion'])){
			if($campos != '')
				$campos .= ",";
			$campos .= " direccion = '".$datos['direccion']."' ";
		}

		if(!empty($datos['exterior'])){
			if($campos != '')
				$campos .= ",";
			$campos .= " num_ext = '".$datos['exterior']."' ";
		}

		if(!empty($datos['interior'])){
			if($campos != '')
				$campos .= ",";
			$campos .= " num_int = '".$datos['interior']."' ";
		}

		if(!empty($datos['cp'])){
			if($campos != '')
				$campos .= ",";
			$campos .= " cp = '".$datos['cp']."' ";
		}

		if(!empty($datos['colonia'])){
			if($campos != '')
				$campos .= ",";
			$campos .= " colonia = '".$datos['colonia']."' ";
		}

		if(!empty($datos['cel'])){
			if($campos != '')
				$campos .= ",";
			$campos .= " celular = '".$datos['cel']."' ";
		}

		if(!empty($datos['tel'])){
			if($campos != '')
				$campos .= ",";
			$campos .= " telefono1 = '".$datos['tel']."' ";
		}

		if(!empty($datos['email'])){
			if($campos != '')
				$campos .= ",";
			$campos .= " email = '".$datos['email']."' ";
		}

		$sql = "UPDATE 
					comun_cliente 
				SET 
					".$campos."
				WHERE 
					id = '".$datos['id']."'";
		$result = $this -> query($sql);

		if($result)
			$result = $datos['id'];
	// Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
	// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;
		$sql = "	INSERT INTO
							com_actividades
								(id, empleado, accion, descripcion,  fecha)
						VALUES
							(''," . $usuario . ",'Agrega cliente','Agrega el cliente [".$result."] ".$datos['nombre']."', '" . $fecha . "')";
		$actividad = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN	editar_cliente		------ ************ ///////////////////////////////////


///////////////// ******** ---- 		edit_client		------ ************ //////////////////
//////// Agrega un cliente a la base de datos en la tabla comun_cliente
	// Como parametros puede recibir:
		// Campos del formulario:
		// -> Nombre, Direccion, Numero interios, Numero Exterior
		// -> Colonia, CP, estado, Municipio, E-mail, Tel

	function edit_client($objeto) {

		$update = "	UPDATE 
								comun_cliente
							SET 
								nombre='" . $objeto['nombre'] . "',
								email='" . $objeto['email'] . "',
								celular='" . $objeto['tel'] . "'
							WHERE
								id=" . $objeto['id'];
		$update = $this -> query($update);

		return $update;
	}

///////////////// ******** ---- 	FIN	edit_client		------ ************ ///////////////////////////////////

///////////////// ******** ---- 		all_correos		------ ************ //////////////////
	// Como parametros puede recibir:
	// Campos del formulario:
		// -> ids cleintes

	function all_correos($objeto) {
		$ids = explode(",", $objeto['id']);
		foreach ($ids as $key => $value) {
			if($key > 0){
				if(!empty($value))
				$where = $where.' OR id = '.$value." AND email != '0' AND email != ''";
			} else {
				$where = $where." id = '".$value."' AND email != '0' AND email != ''";
			}
			
 		}
 		$sql = "SELECT email from comun_cliente where ". $where;
 		$result = $this -> queryArray($sql);
		$result = $result['rows'];
		foreach ($result as $key => $value) {
			if(!empty($value))
				$correo = $correo.$value['email'].";";
		}
		if (!empty($objeto['logo']) && $objeto['img_correo']['mostrar_logo_correo'] == 1) { 
			$content = '<div id="logo" style="text-align: center">
				<input type="image" src="'.$objeto['logo'].'" style="width:90%; max-width: 350px"/>
			</div>';
		}
		if ($objeto['img_correo']['mostrar_info_correo'] == 1) { 
			if (!empty($objeto['organizacion'][0]['nombreorganizacion'])) {
				$content = $content.'<div class="info_correo" style="text-align: center; font-size:15px;font-family: Tahoma,'."'".'Trebuchet MS'."'".',Arial;">'.$objeto['organizacion'][0]['nombreorganizacion'].'</div>';
			}
			if (!empty($objeto['organizacion'][0]['RFC'])) {
				$content = $content.'<div class="info_correo" style="text-align: center; font-size:15px;font-family: Tahoma,'."'".'Trebuchet MS'."'".',Arial;">RFC: '.$objeto['organizacion'][0]['RFC'].'</div>';
			}
			if (!empty($objeto['datos_sucursal'][0]['nombre'])) {
				$content = $content.'<div class="info_correo" style="text-align: center; font-size:15px;font-family: Tahoma,'."'".'Trebuchet MS'."'".',Arial;">Sucursal: '.$objeto['datos_sucursal'][0]['nombre'].'</div>';
			}
			$content = $content.'<div class="info_correo" style="text-align: center; font-size:15px;font-family: Tahoma,'."'".'Trebuchet MS'."'".',Arial;">'.utf8_decode($objeto['datos_sucursal'][0]['direccion']." ".$objeto['datos_sucursal'][0]['municipio'].", ".$objeto['datos_sucursal'][0]['estado']).'</div>';
			if($objeto['organizacion'][0]['paginaweb']!='-'){
					$content = $content.'<div class="info_correo" style="text-align: center; font-size:13px;font-family: Tahoma,'."'Trebuchet MS'".',Arial;">'.$objeto['organizacion'][0]['paginaweb'].'</div>';	
			}
			if (!empty($objeto['datos_sucursal'][0]['tel_contacto'])) {
				$content = $content.'<div class="info_correo" style="text-align: center; font-size:15px;font-family: Tahoma,'."'Trebuchet MS'".',Arial;">Telefono: '.$objeto['datos_sucursal'][0]['tel_contacto'].'</div>';
			}
		}
		if (!empty($objeto['img_correo']['informacion_adicional'])) {
			$content = $content.'<div id="info_adi" style="text-align: center; font-size:15px;font-family: Tahoma,'."'Trebuchet MS'".',Arial;"><br>'.$objeto['img_correo']['informacion_adicional'].'<br></div>';
		}


		require_once('../../modulos/phpmailer/sendMail.php');

		$mail->From = "mailer@netwarmonitor.com";
		$mail->FromName = $objeto['organizacion'][0]['nombreorganizacion'];
		$mail->Subject = "Información";
		$mail->AltBody = $objeto['organizacion'][0]['nombreorganizacion'];
		if($objeto['img_correo']['enviar_promociones'] == 1){
			$type = pathinfo($objeto['img_correo']['imagen_promo'], PATHINFO_EXTENSION);
        	$mail->AddAttachment($objeto['img_correo']['imagen_promo'],"Promocion.".$type);
        }
       	if($objeto['img_correo']['enviar_felicitaciones'] == 1){
       		$type = pathinfo($objeto['img_correo']['imagen_felicitaciones'], PATHINFO_EXTENSION);
       		$mail->AddAttachment($objeto['img_correo']['imagen_felicitaciones'], "Felicitaciones.".$type);
       	}
       	if($objeto['img_correo']['enviar_menu'] == 1){
       		$type = pathinfo($objeto['img_correo']['menu_digital'], PATHINFO_EXTENSION);
       		$mail->AddAttachment($objeto['img_correo']['menu_digital'], "Menu.".$type);
       	}
		$mail->MsgHTML($content);
		$correo = explode(';', $correo);
		foreach ($correo as $key => $value) {
			$mail->AddAddress($value, $value);
		}
		
		

 		return @$mail->Send();
	}

///////////////// ******** ---- 	FIN	all_correos		------ ************ //////////////////

///////////////// ******** ---- 		add_client		------ ************ //////////////////
//////// Agrega un cliente a la base de datos en la tabla comun_cliente
	// Como parametros puede recibir:
		// Campos del formulario:
		// -> Nombre, Direccion, Numero interios, Numero Exterior
		// -> Colonia, CP, estado, Municipio, E-mail, Tel

	function add_client($objeto) {

		$sql = "INSERT INTO 
					comun_cliente
						(nombre, email, celular)
				VALUES
					('" . $objeto['nombre'] . "', '" . $objeto['email'] . "','" . $objeto['tel'] . "');";
		$result = $this -> insert_id($sql);

		return $result;
	}
	

///////////////// ******** ---- 	FIN	add_client		------ ************ ///////////////////////////////////

///////////////// ******** ---- 	mesas_juntas		------ ************ //////////////////
//////// Consulta si existe una union en las mesas, si existe regresa una arreglo con las mesas agrupadas
	// Como parametros puede recibir:
	// id_mesa-> id de la mesa agrupada

	function mesas_juntas($objeto) {
		// Si viene el id del estado Filtra por el id del estado
		$condicion .= (!empty($objeto['id_mesa'])) ? ' AND idprincipal = \'' . $objeto['id_mesa'] . '\'' : '';

		$sql = "SELECT 
					*
				FROM 
					com_union
				WHERE 
					1 = 1 " . $condicion;
		// return $sql;
		$result = $this -> queryArray($sql);
		$result = $result['rows'];

		return $result;
	}

///////////////// ******** ---- 	FIN	mesas_juntas		------ ************ //////////////////

///////////////// ******** ---- 	separar_mesas		------ ************ //////////////////
//////// Separa las mesas unidas
	// Como parametros puede recibir:
	// idprincipal-> id de union en la tabla com_union
	// idmesa-> el id de la mesa en la que se guardaran los pedidos
	// idcomanda-> el id de la comanda

	function separar_mesas($objeto) {
		$mensaje = '';

		// ** Elimina la union de las mesas
		$delete = "	DELETE FROM 
								com_union
							WHERE
								idprincipal=" . $objeto['idprincipal'];
		$delete = $this -> query($delete);

		// Si algo sale mal guarda un mensaje de error
		$mensaje .= (empty($delete)) ? '\n - Error al eliminar de com_union - ' : $mensaje;
		// Si viene el id del estado Filtra por el id del estado
		$condicion .= (!empty($objeto['idprincipal'])) ? ' AND idmesa=\'' . $objeto['idprincipal'] . '\'' : '';

		// ** Consulta el ultimo registro de la mesa y la comanda segun la fecha
		$comanda = "	SELECT 
								* 
							FROM 
								com_comandas
							WHERE 1=1 " . $condicion . "
							ORDER BY 
								timestamp DESC";
		$comanda = $this -> queryArray($comanda);
		$comanda = $comanda['rows'][0]['id'];

		// Si algo sale mal guarda un mensaje de error
		$mensaje .= (empty($comanda)) ? '\n  - Error al consultar la comanda - ' : $mensaje;

		// ** Actualiza la comanda con el id de la nueva mesa
		$update = "	UPDATE 
								com_comandas
							SET 
								idmesa=" . $objeto['idmesa'] . "
							WHERE
								id=" . $comanda;
		$update = $this -> query($update);

		// Si algo sale mal guarda un mensaje de error
		$mensaje .= (empty($update)) ? '\n  - Error al actualizar la comanda - ' : $mensaje;

		//** Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
		// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;
		$sql = "	INSERT INTO
							com_actividades
								(id, empleado, accion, fecha)
						VALUES
							(''," . $usuario . ",'Separa las mesas', '" . $fecha . "')";
		$actividad = $this -> query($sql);

		return $mensaje;
	}

///////////////// ******** ---- 	FIN	separar_mesas				------ ************ //////////////////

///////////////// ******** ---- 	guardar_promedio_comensal		------ ************ //////////////////
//////// Actualiza el promedio por comensal
	// Como parametros puede recibir:
	// 	promedio -> promedio por comensal de la comanda a registrar
	//	comanda -> id de la comanda

	function guardar_promedio_comensal($objeto) {
		$sql = "UPDATE 
					com_comandas
				SET 
					promedioComensal=" . $objeto['promedio'] . ",
					personas=" . $objeto['personas'] . "
				WHERE 
					id=" . $objeto['comanda'];
		// return $sql;
		$result = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN	guardar_promedio_comensal		------ ************ //////////////////

///////////////// ******** ---- 		promedio_comensal				------ ************ //////////////////
//////// Consulta el promedio por comensal y lo agrega a la div
	// Como parametros recibe:
		// f_ini -> fecha y hora de inicio
		// F_fin -> fecha y hora final
		// sucursal -> ID de la sucursal
		// empleado -> ID del empleado
		// comensales -> Numero de comensales
		// comanda -> ID de la comanda

	function promedio_comensal($objeto) {
	// Filtra por la sucursal si existe
		$condicion .= ($objeto['sucursal'] != '*' && !empty($objeto['sucursal'])) ? 
			' AND suc.idSuc = \'' . $objeto['sucursal'] . '\'' : '';
	// Filtra por el empleado si existe
		$condicion .= ($objeto['empleado'] != '*') ? ' AND c.idempleado = \'' . $objeto['empleado'] . '\'' : '';
	// Filtra por numero de comensales si existe
		$condicion .= (!empty($objeto['comensales'])) ? ' AND c.personas >= \'' . $objeto['comensales'] . '\'' : '';
	// Filtra por el ID de la comanda si existe
		$condicion .= (!empty($objeto['comanda'])) ? ' AND idprincipal = \'' . $objeto['comanda'] . '\'' : '';
	// Se filtra por fecha de inicio y fin si estas existen
		$condicion .= (!empty($objeto['f_ini']) && !empty($objeto['f_fin'])) ? 
			' AND timestamp BETWEEN \'' . $objeto['f_ini'] . '\' AND \'' . $objeto['f_fin'] . '\'' : '';

	// Ordena la consulta por los parametros indicados si existe, si no la ordena por id Descendente
		$orden .= (!empty($objeto['orden'])) ? ' ' . $objeto['orden'] : ' id DESC';

		$sql = "SELECT 
					c.id, c.personas AS comensales, timestamp AS fecha, suc.nombre AS sucursal,
					u.usuario AS empleado, (c.total / c.personas) promedioComensal
				FROM 
					com_comandas c
				LEFT JOIN
						com_mesas m
					ON
						m.id_mesa = c.idmesa
				LEFT JOIN
						accelog_usuarios u
					ON
					 	c.idempleado = u.idempleado
				LEFT JOIN
						administracion_usuarios ad
					ON
					 	ad.idempleado = c.idempleado
				LEFT JOIN
						mrp_sucursal suc
					ON
					 	suc.idSuc = ad.idSuc
				WHERE 
					1=1 " . 
				$condicion . "
				ORDER BY " . 
					$orden;
		// return $sql;
		$result = $this -> queryArray($sql);
		$result = $result['rows'];

		return $result;
	}

///////////////// ******** ---- 		FIN promedio_comensal		------ ************ //////////////////

///////////////// ******** ---- 	detalles_mesa		------ ************ //////////////////
//////// Obtiene los datos de la mesa
	// Como parametros puede recibir:
		//	id -> id de la mesa

	function detalles_mesa($objeto) {
	// Si viene el id de la mesa Filtra por el id de la mesa
		$condicion .= (!empty($objeto['id'])) ? ' AND m.id_mesa=' . $objeto['id'] : '';

	// Selecciona todos los datos de la tabla com_mesas y solo el nombre de empleado de la tabla usuarios
		$sql = "SELECT
					m.*, nombreusuario AS mesero, c.id AS id_comanda, cli.celular AS tel
				FROM
					com_mesas m
				LEFT JOIN
						administracion_usuarios a
					ON
						m.idempleado = a.idempleado
				LEFT JOIN
						com_comandas c
					ON
						(c.idmesa = m.id_mesa AND c.status = 0)
				LEFT JOIN
						comun_cliente cli
					ON
						cli.nombre = m.nombre
				WHERE
					1=1 " . $condicion . "
				LIMIT 1";
		// return $sql;
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN	detalles_mesa		------ ************ //////////////////

///////////////// ******** ---- 		guardar_cordenadas		------ ************ //////////////////
//////// Guarda las cordenadas de la mesa actual en la BD
	// Como parametros recibe:
	// id -> id de la mesa que se movera
	// x -> numero con la distancia del objeto hacia la derecha
	// y -> numero con la distancia del objeto hacia abajo

	function guardar_cordenadas($objeto) {
		//** Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
		// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;
		$sql = "	INSERT INTO
							com_actividades
								(id, empleado, accion, fecha)
						VALUES
							(''," . $usuario . ",'Mueve las mesas', '" . $fecha . "')";
		$actividad = $this -> query($sql);

		$sql = "UPDATE 
					com_mesas
				SET 
					x = " . $objeto['x'] . ",
					y = " . $objeto['y'] . ",
					width = " . $objeto['width'] . ",
					height = " . $objeto['height'] . "
				WHERE 
					id_mesa=" . $objeto['id'];
		// return $sql;
		$result = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN guardar_cordenadas		------ ************ //////////////////

///////////////// ******** ---- 		mover		------ ************ //////////////////
//////// Consulta las coordenadas en la DB y regresa un array con los datos
	// Como parametros recibe:
		// id -> id de la mesa que se movera

	function mover($objeto) {
		// Si viene el id del estado Filtra por el id del estado
		$condicion .= (!empty($objeto['id'])) ? ' AND id_mesa=\'' . $objeto['id'] . '\'' : '';

		// Selecciona todos los datos de la tabla com_mesas
		$sql = "SELECT 
					*
				FROM 
					com_mesas
				WHERE 
					1=1 " . 
				$condicion;
		// return $sql;
		$result = $this -> queryArray($sql);
		$result = $result['rows'];

		return $result;
	}

///////////////// ******** ---- 		FIN mover		------ ************ //////////////////

///////////////// ******** ---- 		areas			------ ************ //////////////////
//////// Consulta en la BD las areas que contienen las mesas
	// Como parametros recibe:
	// permisos -> ids de las mesas a las que tiene permiso el empleado

	function areas($objeto) {


		// Filtra por los permisos del mesero
		$condicion .= (!empty($objeto['permisos'])) ? ' AND m.id_mesa IN(' . $objeto['permisos'] . ')' : '';

		// Selecciona todos los departamentos de las mesas
		$sql = "SELECT DISTINCT
					m.idDep id, d.nombre AS area
				FROM 
					com_mesas m
				INNER JOIN
						mrp_departamento d
					ON
						m.idDep=d.idDep
				WHERE 
					m.status = 1 
				AND
					m.tipo_mesa IS NOT NULL 
					" . $condicion.
				"ORDER BY 
					area";
		// return $sql;
		$result = $this -> queryArray($sql);
		$result = $result['rows'];

		return $result;
	}

///////////////// ******** ---- 		FIN areas					------ ************ //////////////////

///////////////// ******** ---- 		first_area			------ ************ //////////////////
//////// Consulta en la BD las areas que contienen las mesas
	// Como parametros recibe:

	function first_area($objeto) {

			$sucursal = "	SELECT 
								mp.idSuc AS id 
							FROM 
								administracion_usuarios au 
							INNER JOIN 
									mrp_sucursal mp 
								ON 
									mp.idSuc = au.idSuc 
							WHERE 
								au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
							LIMIT 1";
			$sucursal = $this -> queryArray($sucursal);
			$sucursal = $sucursal['rows'][0]['id'];


		// Filtra por los permisos del mesero

		// Selecciona todos los departamentos de las mesas

		$sql = "SELECT DISTINCT idDep as id, nombre AS area FROM  mrp_departamento where idsuc = ".$sucursal." limit 1;";
		// return $sql;
		$result = $this -> queryArray($sql);
		$result = $result['rows'];

		return $result[0];
	}

///////////////// ******** ---- 		FIN first_area					------ ************ //////////////////

///////////////// ******** ---- 		all_areas			------ ************ //////////////////
//////// Consulta en la BD las areas que contienen las mesas
	// Como parametros recibe:

	function all_areas($objeto) {
		// Filtra por los permisos del mesero
		$condicion .= (!empty($objeto['permisos'])) ? ' AND m.id_mesa IN(' . $objeto['permisos'] . ')' : '';

		// Selecciona todos los departamentos de las mesas
		$sql = "SELECT DISTINCT d.idDep as id, CONCAT(IF(s.nombre is null,'',s.nombre) ,' - ',d.nombre) AS area, d.idsuc, s.nombre sucursal		
				FROM mrp_departamento d
				LEFT JOIN mrp_sucursal s on s.idSuc = d.idsuc
				ORDER BY d.idsuc;";
		// return $sql;
		$result = $this -> queryArray($sql);
		$result = $result['rows'];

		return $result;
	}

///////////////// ******** ---- 		FIN all_areas					------ ************ //////////////////

///////////////// ******** ---- 		area			------ ************ //////////////////
//////// Consulta el area que requiere
	// Como parametros recibe:
	// area -> id del area

	function area($objeto) {

		// Selecciona todos los departamentos de las mesas
		$sql = "SELECT
					idDep as id, nombre AS area
				FROM 
					mrp_departamento
				WHERE 
					idDep = ". $objeto['area'];
		// return $sql;
		$result = $this -> queryArray($sql);
		$result = $result['rows'];

		return $result[0];
	}

///////////////// ******** ---- 		FIN area					------ ************ //////////////////

///////////////// ******** ---- 		buscar_reservaciones		------ ************ //////////////////
//////// Consulta si hay reservaciones para la hora actual
	// Como parametros recibe:
	// fecha-> fecha y hora a buscar
	// activo -> si la reservacion esta activa o no

	function buscar_reservaciones($objeto) {
		// Si viene el la fecha filtra la consulta entre la fecha de inicio y fin
		$condicion .= (!empty($objeto['fecha'])) ? ' AND 
															inicio
														BETWEEN 
																\'' . $objeto['fecha'] . ' 00:00:01\' 
															AND 
																\'' . $objeto['fecha'] . ' 23:59:59\'' : '';

		// Si viene "activo" lo filtra, si no selecciona las reservaciones activas
		$condicion .= (!empty($objeto['activo'])) ? ' AND activo=\'' . $objeto['activo'] . '\'' : ' AND activo=1';

		// Selecciona todos las reservaciones
		$sql = "	SELECT  
							*
						FROM 
							com_reservaciones
						WHERE 
							1=1" . $condicion;
		// return $sql;
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN buscar_reservaciones		------ ************ //////////////////

///////////////// ******** ---- 		buscar_productos		------ ************ //////////////////
//////// Consulta a la BD los productos
	// Como parametros recibe:
		// texto -> palabra u oracion a buscar en los productos
		// div -> div donde se cargaran los resultados
		// comanda -> ID de la comanda
		// departamento -> ID del departamento
		// familia -> ID de la familia
		// linea -> id de la linea

	function buscar_productos($objeto) {
	// Filtra por departamento si existe
		$condicion .= (!empty($objeto['departamento'])) ? ' AND p.departamento = \'' . $objeto['departamento'] . '\'' : '';
	// Filtra por familia si existe
		$condicion .= (!empty($objeto['familia'])) ? ' AND p.familia = \'' . $objeto['familia'] . '\'' : '';
	// Filtra por linea si existe
		$condicion .= (!empty($objeto['linea'])) ? ' AND p.linea = \'' . $objeto['linea'] . '\'' : '';
	// Filtra por nombre si existe si se indica
		$condicion .= (!empty($objeto['texto'])) ? ' AND p.nombre LIKE \'%' . $objeto['texto'] . '%\'' : '';
	// Filtra por el tipo de producto si existe
		$condicion .= (!empty($objeto['tipo_producto'])) ? ' AND p.tipo_producto = ' . $objeto['tipo_producto'] : ' 
																AND 
																	p.tipo_producto != 2';
	// Filtra por el orden si existe
		$condicion .= (!empty($objeto['orden'])) ? ' GROUP by idProducto ORDER BY '.$objeto['orden'] : ' GROUP by idProducto ORDER BY rate DESC';
	// Si no existe limite, filtra los 100 primeros
		$condicion .= (!empty($objeto['limite'])) ? ' LIMIT ' . $objeto['limite'].', 100' : ' LIMIT 0, 100';
		if (!empty($objeto['sucursal'])) {
			$sucursal = $objeto['sucursal'];
		} else {
			session_start();
			$sucursal = "	SELECT 
								mp.idSuc AS id 
							FROM 
								administracion_usuarios au 
							INNER JOIN 
									mrp_sucursal mp 
								ON 
									mp.idSuc = au.idSuc 
							WHERE 
								au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
							LIMIT 1";
			$sucursal = $this -> queryArray($sucursal);
			$sucursal = $sucursal['rows'][0]['id'];
		}
		$sql = "select * from app_producto_sucursal limit 1";
		$total = $this -> queryArray($sql);
		if($total['total'] > 0){
			$sql = "SELECT p.id AS idProducto, p.nombre,
				(CASE WHEN  pp.id IS NULL OR pp.precio IS NULL THEN
						ROUND(p.precio, 2)

						ELSE ROUND(pp.precio, 2) END) as precioventa, p.ruta_imagen AS imagen, IF((SELECT COUNT(id) FROM app_producto_material WHERE id_producto = p.id) > 0, 1, 0) materiales, departamento AS idDep, f.h_ini AS inicio, f.h_fin AS fin, f.dias, p.formulaieps AS formula, pp.id as id_pre_suc, (SELECT COUNT(*) FROM app_producto_impuesto WHERE id_producto = p.id) AS totalimp, p.id, p.precio, i.valor, i.clave, pi.formula, i.nombre as nimp FROM app_productos p 
				LEFT JOIN app_campos_foodware f ON p.id=f.id_producto 
				LEFT JOIN app_precio_sucursal pp ON p.id=pp.producto AND pp.sucursal = ".$sucursal."
				LEFT JOIN app_linea l ON p.linea=l.id 
				LEFT JOIN app_familia fa ON p.familia=fa.id 
				LEFT JOIN app_departamento d ON p.departamento=d.id 
				INNER JOIN app_producto_sucursal aps ON aps.id_producto = p.id AND aps.id_sucursal = ".$sucursal."
				LEFT JOIN app_producto_impuesto pi on p.id = pi.id_producto
				LEFT JOIN app_impuesto i on i.id = pi.id_impuesto 
				WHERE
					p.status=1
				AND
					tipo_producto != 6 and p.tipo_producto != 3 " . 
				$condicion;
		} else {
			$sql = "SELECT p.id AS idProducto, p.nombre,
				(CASE WHEN  pp.id IS NULL OR pp.precio IS NULL THEN
						ROUND(p.precio, 2)
						ELSE ROUND(pp.precio, 2) END) as precioventa, p.ruta_imagen AS imagen, IF((SELECT COUNT(id) FROM app_producto_material WHERE id_producto = p.id) > 0, 1, 0) materiales, departamento AS idDep, f.h_ini AS inicio, f.h_fin AS fin, f.dias, p.formulaieps AS formula, pp.id as id_pre_suc, (SELECT COUNT(*) FROM app_producto_impuesto WHERE id_producto = p.id) AS totalimp, p.id, p.precio, i.valor, i.clave, pi.formula, i.nombre as nimp FROM app_productos p 
				LEFT JOIN app_campos_foodware f ON p.id=f.id_producto 
				LEFT JOIN app_precio_sucursal pp ON p.id=pp.producto AND pp.sucursal = ".$sucursal."
				LEFT JOIN app_linea l ON p.linea=l.id 
				LEFT JOIN app_familia fa ON p.familia=fa.id 
				LEFT JOIN app_departamento d ON p.departamento=d.id 
				LEFT JOIN app_producto_impuesto pi on p.id = pi.id_producto
				LEFT JOIN app_impuesto i on i.id = pi.id_impuesto 
				WHERE
					p.status=1
				AND
					tipo_producto != 6 and p.tipo_producto != 3 " . 
				$condicion;
				//print_r($sql);
		}
		// return $sql;
		$result = $this -> queryArray($sql);

		return $result;
		
	}

///////////////// ******** ---- 			FIN buscar_productos			------ ************ //////////////////

///////////////// ******** ---- 			mesas_libres					------ ************ //////////////////
//////// Consulta en la BD las mesas libres
	// Como parametros recibe:
		// permisos -> cadena con los ID´s de las mesas permitidas
		
	function mesas_libres($objeto) {
	// Filtra por los permisos del mesero
		$condicion .= (!empty($objeto['permisos'])) ? ' AND id_mesa IN(' . $objeto['permisos'] . ')' : '';

	// Selecciona todas las mesas libres
		$sql = "SELECT
					id_mesa, nombre AS nombre_mesa
				FROM
					(	SELECT
							id_mesa, nombre
						FROM
							com_mesas
						WHERE
							id_mesa NOT IN(
								SELECT
									idmesa
								FROM
									com_union
							)
					) AS id_mesa
				WHERE
					id_mesa NOT IN(	
						SELECT 
							idmesa
						FROM 
							com_comandas
						WHERE 
							status !=3 AND tipo = 0
					)" . 
				$condicion;
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN mesas_libres		------ ************ //////////////////

///////////////// ******** ---- 			datos_organizacion			------ ************ //////////////////
//////// Carga los datos de la organización
    public function datos_organizacion(){
        $sql = "SELECT * from organizaciones c left join estados e on e.idestado=c.idestado left join municipios m on m.idmunicipio=c.idmunicipio where idorganizacion=1";
        $result = $this->queryArray($sql);
        return $result['rows'];
    }
///////////////// ******** ---- 			FIN datos_organizacion		------ ************ //////////////////

///////////////// ******** ---- 			mesas_ocupadas					------ ************ //////////////////
//////// Consulta en la BD las mesas ocupadas
	// Como parametros recibe:
		//id_mesa -> id_mesa para checar si existe en comanda
		
	function mesas_ocupadas($id_mesa) {
	
		$sql = "SELECT
					status
				FROM
					com_comandas 
				WHERE
					idmesa = $id_mesa 
				and
					status = 0";
		$result = $this -> queryArray($sql);
		if (count($result['rows']) > 0) {
			return 1;

		}
		return 0;
	}

///////////////// ******** ---- 		FIN mesas_ocupadas		------ ************ //////////////////

///////////////// ******** ---- 			mesa_junta					------ ************ //////////////////
//////// Consulta en la BD las mesas ocupadas
	// Como parametros recibe:
		//id_mesa -> id_mesa para checar si es una mesa junta
		
	function mesa_junta($id_mesa) {
	
		$sql = "SELECT
					idprincipal
				FROM
					com_union
				WHERE
					idprincipal = $id_mesa";
		$result = $this -> queryArray($sql);
		if (count($result["rows"]) > 0) {
			return 1;
		}
		return 0;
	}

///////////////// ******** ---- 		FIN mesa_junta		------ ************ //////////////////
	
///////////////// ******** ---- 		mudar_comanda		------ ************ //////////////////
//////// Muda la comanda de mesa
	// Como parametros recibe:
	// mesa -> id de la mesa a la que se mudara la comanda
	// comanda -> id de la comanda
	// mesa_origen -> id de la mesa original

	function mudar_comanda($objeto) {
		// Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
		// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;
		$sql = "	INSERT INTO
						com_actividades
							(id, empleado, accion, fecha)
					VALUES
						(''," . $usuario . ",'Muda la comanda', '" . $fecha . "')";
		$actividad = $this -> query($sql);

		$sql = "SELECT 
					id 
				FROM 
					com_comandas 
				WHERE 
					status = 0
				and
					idmesa = ".$objeto['mesa'];
		$idcomanda = $this -> queryArray($sql);
		// En caso de que exista comanda guarda el id de dicha comanda
		if(count($idcomanda["rows"]) > 0){
			$idcomanda = $idcomanda["rows"][0];
			$idcomanda = $idcomanda["id"];
			
			// Da de baja la comanda secundaria
						$sql = "UPDATE 
									com_comandas 
								SET 
									status = 3
								WHERE 
									id = " . $objeto["comanda"];
						$this -> query($sql);

						// Busca las persoanas que tienen pedidos
						$sql = "SELECT 
									id, npersona, idcomanda
								FROM 
									com_pedidos 
								WHERE 
									status != 3
								and
									idcomanda = $idcomanda
								and
									idproducto != 0
								or 
									status != 3
								and
									idcomanda = " . $objeto["comanda"] ." 
								and
									idproducto != 0
								group by 
									npersona, idcomanda";
						$npersonas = $this -> queryArray($sql);
						$npersonas = $npersonas["rows"];

						// Foreach para las personas que tienen pedidos
						foreach ($npersonas as $key2 => $value) {
							// Variable para saber que numero de persona colocarle
							$nper = $key2+1;
							// ACtualiza el numero de persona en cada pedido y cambia el idcomanda a 0 para no confundirlo dependiendo el numero de personas y el id de su comanda
							$sql = "UPDATE 
										com_pedidos
									SET 
										idcomanda = 0,
										npersona = $nper
									WHERE 
										idcomanda = " . $value['idcomanda'] . "
									and 
										npersona = ".$value['npersona'];
							$this -> query($sql);
						}
						// Actualiza el id de la comanda al de la comanda principal
						$sql = "UPDATE 
									com_pedidos
								SET 
									idcomanda = $idcomanda
								WHERE 
									idcomanda = 0";
						$this -> query($sql);

		}
		else{
		$idcomanda = $objeto['comanda'];
		// Cambia la mesa de la comanda
		$sql = "
				UPDATE
					com_comandas
				SET
					idmesa=" . $objeto['mesa'] . "
				WHERE
					id=" . $objeto['comanda'];
		$result = $this -> query($sql);

		// Separa las mesas borrando los registros de la tabla com_union
		

		}
		$sql = "	DELETE FROM
						com_union
					WHERE
						idprincipal=" . $objeto['mesa_origen'];
		$union = $this -> query($sql);
		return $idcomanda;
	}

///////////////// ******** ---- 		FIN mudar_comanda		------ ************ //////////////////



///////////////// ******** ---- 		listar_comandas			------ ************ //////////////////
//////// Consulta las comandas y las regresa en un array
	// Como parametros recibe:
		// id -> id de la comanda
		// f_ini -> fecha y hora de inicio
		// F_fin -> fecha y hora final
		// status -> status de la comanda(abierta, cerrada, eliminada)

	function listar_comandas($objeto) {
		$result['$objeto_despues'] = $objeto;
		
	// Filtra por la sucursal si existe
		$condicion .= ($objeto['sucursal'] != '*' && !empty($objeto['sucursal'])) ? 
			' AND m.idSuc = \'' . $objeto['sucursal'] . '\'' : '';
	// Si viene el id del la comanda Filtra por el id de la comanda
		$condicion .= (!empty($objeto['id'])) ? ' AND c.id=\'' . $objeto['id'] . '\'' : '';
	// Si viene el status Filtra por status de la comanda
		$condicion .= ($objeto['status'] != '*' && $objeto['status'] != '') ? 
			' AND c.status = \'' . $objeto['status'] . '\'' : '';
	// Si viene el id del empleado Filtra por empleado
		$condicion .= ($objeto['empleado'] != '*' && !empty($objeto['empleado'])) ? 
			' AND u.idempleado=\'' . $objeto['empleado'] . '\'' : '';
	// Si viene el id de la mesa Filtra por la mesa
		$condicion .= ($objeto['mesa'] != '*' && !empty($objeto['mesa'])) ? 
			' AND c.idmesa=\'' . $objeto['mesa'] . '\'' : '';
	// Filtra por la via de contacto si existe
		$condicion .= (!empty($objeto['via_contacto'])) ? ' AND v.id = ' . $objeto['via_contacto'] : '';
	// Se filtra por fecha de inicio y fin si estas existen
		$condicion .= (!empty($objeto['f_ini']) && !empty($objeto['f_fin'])) ? 
			' AND timestamp BETWEEN \'' . $objeto['f_ini'] . '\' AND \'' . $objeto['f_fin'] . '\'' : '';
			
	// Filtra por la duracion de la comanda
		$condicion .= (!empty($objeto['duracion'])) ? ' AND TIMESTAMPDIFF(MINUTE, c.timestamp, c.fin)>' . $objeto['duracion'] : '';

	// Agrupa la consulta por los parametros indicados si existe, si no la agrupa por id
		$agrupar .= (!empty($objeto['agrupar'])) ? ' GROUP BY ' . $objeto['agrupar'] : ' GROUP BY id';

	// Ordena la consulta por los parametros indicados si existe, si no la ordena por id Descendente
		$orden .= (!empty($objeto['orden'])) ? ' ' . $objeto['orden'] : ' c.codigo DESC';

		$sql = "SELECT 
					COUNT(c.id) AS comandas, c.id, c.idmesa, c.personas, c.total, 
					c.id_venta AS venta,
					if(m.id_dependencia, concat((select nombre from com_mesas where id_mesa = m.id_dependencia),' ',m.nombre),m.nombre) AS nombre_mesa, 
					SEC_TO_TIME(TIMESTAMPDIFF(SECOND, c.fin, c.timestamp))  as duracion,
					(CASE m.tipo 
						WHEN 0 THEN
							'Mesa'
						WHEN 1 THEN
							'Para llevar'
						WHEN 2 THEN
							'A domicilio'
						ELSE '---' END) as tipo,
					(CASE c.status 
						WHEN 0 THEN
							'Abierta'
						WHEN 1 THEN
							'Cerrada / Pagada'
						WHEN 2 THEN
							'Cerrada / Sin pago'
						WHEN 3 THEN
							'Eliminada'
						ELSE '---' END) as status,
					u.usuario, c.codigo,c.timestamp,c.total, c.fin, 
					SUM(promedioComensal) AS promedioComensal, GROUP_CONCAT(s.id) AS sub_comandas, suc.nombre AS sucursal, 
					cli.celular AS tel, v.nombre AS via_contacto_text, v.id AS id_via_contacto,
					(select group_concat(p.complementos) from com_pedidos p left join app_productos pp on pp.id = p.complementos where p.idcomanda = c.id ) complementos
				FROM 
					com_comandas c
				LEFT JOIN
						com_mesas m
					ON
						c.idmesa = m.id_mesa
				LEFT JOIN
						com_vias_contacto v
					ON
						v.id = m.id_via_contacto
				LEFT JOIN
						comun_cliente cli
					ON
						cli.nombre = m.nombre
				LEFT JOIN
						com_sub_comandas s
					ON
						s.idpadre = c.id
				LEFT JOIN
						mrp_sucursal suc
					ON
						suc.idSuc = m.idSuc
				LEFT JOIN
						accelog_usuarios u
					ON
						c.idempleado = u.idempleado
				WHERE 
					1 = 1 
				AND 
					m.tipo_mesa is not null
				AND 
					m.tipo_mesa != 7
				AND 
					m.tipo_mesa != 8 " . $condicion . " " . $agrupar . "
				ORDER BY " . $orden;
		// return $sql;
		// $result['sql'] = $sql;
		//echo $sql;
		$result = $this -> queryArray($sql);

		return $result;

		/*
			CAMPOS OMITIDOS Y REMPLAZODOS EN CONTROLADOR
			(select sum(pp.precio) from com_pedidos p left join app_productos pp on pp.id = p.complementos where p.idcomanda = c.id ) pComplementos,
			if(((select sum(pp.precio) from com_pedidos p left join app_productos pp on pp.id = p.complementos where p.idcomanda = c.id ) + c.total) is null, c.total, ((select sum(pp.precio) from com_pedidos p left join app_productos pp on pp.id = p.complementos where p.idcomanda = c.id ) + c.total)) totalR 					

		*/
	}

	function precio_complementos($complementos){
	
		if (!empty($complementos)) { // <= false
			$precio = 0;
			$complementos = explode(",", $complementos);
				foreach ($complementos as $key => $value) {
					if($value > 0){
						$sql = "SELECT sum(precio) precio FROM app_productos WHERE id = $value;";
						$result = $this->queryArray($sql);
						$precio += $result['rows'][0]['precio'];
					}					
				}
			return $precio;
		} else {
		   return 0;
		}
	
	/*	
		$buscar = ',,';
		$pasa = 1;
		if(eregi($buscar, $complementos)){
			$pasa = 0;
		}
		//echo json_encode($complementos);
		if($complementos != null and $pasa == 1){
				$complementos = explode(",", $complementos);
				foreach ($complementos as $key => $value) {
					$sql = "SELECT sum(precio) precio FROM app_productos WHERE id = $value;";
					$result = $this->queryArray($sql);
					$precio += $result['rows'][0]['precio'];
				}
				return $precio;
		}else{
			return 0;
		}
	*/
	
	}

///////////////// ******** ---- 			FIN listar_comandas			------ ************ //////////////////

///////////////// ******** ---- 			listar_empleados			------ ************ //////////////////
//////// Consulta las comandas y las regresa en un array
	// Como parametros recibe:
		// id -> id del empleado

	function listar_empleados($objeto) {
	// Si viene el id del empleado Filtra por empleado
		$condicion .= (!empty($objeto['id'])) ? ' AND idempleado = \'' . $objeto['id'] . '\'' : '';
	// Elimina los administradores del listado
		$condicion .= (!empty($objeto['vista_empleados'])) ? ' AND idperfil != 2' : '';
	// Filtra por el tipo de usuario si existe
		$condicion .= (!empty($objeto['ocultar_empleados'])) ? ' AND u.mostrar_comanda = 1' : '';
	
	// Ordena la consulta por los parametros indicados si existe, si no la ordena por id Descendente
		$orden .= (!empty($objeto['orden'])) ? ' ' . $objeto['orden'] : ' nombreusuario ASC';
		
		if (!empty($objeto['sucursal'])) {
			$sucursal = $objeto['sucursal'];
		} else {
		session_start();
		$sucursal = "	SELECT 
							mp.idSuc AS id 
						FROM 
							administracion_usuarios au 
						INNER JOIN 
								mrp_sucursal mp 
							ON 
								mp.idSuc = au.idSuc 
						WHERE 
							au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
						LIMIT 1";
		$sucursal = $this -> queryArray($sucursal);
		$sucursal = $sucursal['rows'][0]['id'];
		}
		$sql = "SELECT
					idempleado AS id, nombreusuario AS usuario, permisos, asignacion, u.mostrar_comanda
				FROM 
					administracion_usuarios u
				LEFT JOIN
						com_meseros m
					ON
						m.id_mesero = u.idempleado
				WHERE
					idempleado != 'null'
				AND 
					u.idSuc =  " . $sucursal . " " . 
				$condicion . "
				ORDER BY " . 
					$orden;
		// return $sql;
		$result = $this -> queryArray($sql);
		$result = $result['rows'];

		return $result;
	}

///////////////// ******** ---- 		FIN listar_empleados		------ ************ //////////////////

///////////////// ******** ---- 			listar_tipo_mesas			------ ************ //////////////////
//////// Consulta las comandas y las regresa en un array
	// Como parametros recibe:

	function listar_tipo_mesas($objeto) {
		$sql = "SELECT
					*
				FROM 
					com_tipo_mesas
				ORDER BY 
					tipo_mesa";
		// return $sql;
		$result = $this -> queryArray($sql);
		$result = $result['rows'];

		return $result;
	}

///////////////// ******** ---- 		FIN listar_tipo_mesas		------ ************ //////////////////


///////////////// ******** ---- 		iniciar_sesion				------ ************ //////////////////
//////// Inicia la sesion para el empleado y carga la vista con los filtros solo para el usuario
	// Como parametros puede recibir:
		//	pass -> contraseña a bsucar
		// empleado -> ID del empleado

	function iniciar_sesion($objeto) {
		if(array_key_exists("api", $_REQUEST)){		
			require ("../webapp/netwarelog/webconfig.php");
		} else {
			require ('../../netwarelog/webconfig.php');
		}
		$condicion = '';
		
	// Valida si se debe de pedir el pass o no
		if (!empty($objeto['pedir_pass']) != 2) {	
			$pass = $objeto['pass'];
			$pass = $this -> escapalog($pass);
			$pass = $this -> fencripta($pass, $accelog_salt);
			
			$condicion .= " AND u.clave = '" . $pass . "'";
		}

		$empleado = $objeto['empleado'];
		$empleado = $this -> escapalog($empleado);
		
		$sql = "SELECT
					u.idempleado AS id, usuario, permisos, p.idperfil AS perfil
				FROM
					accelog_usuarios u
				INNER JOIN
						administracion_usuarios a
					ON
						u.idempleado = a.idempleado
				LEFT JOIN
						com_meseros m
					ON
						m.id_mesero = u.idempleado
				LEFT JOIN
						accelog_usuarios_per p
					ON
						p.idempleado = u.idempleado
				WHERE 
					u.idempleado = " . $empleado .
				$condicion;
		$result = $this -> queryArray($sql);

	// Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
	// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($objeto['empleado'])) ? $objeto['empleado'] : 0;
		$sql = "INSERT INTO
					com_actividades
						(id, empleado, accion, fecha)
				VALUES
					(''," . $usuario . ",'Inicia sesion', '" . $fecha . "')";
		$actividad = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN iniciar_sesion		------ ************ //////////////////

///////////////// ******** ---- 	guardar_asignacion		------ ************ //////////////////
//////// Guarda los permisos para el empleado
	// Como parametros puede recibir:
	// id -> ID del empleado
	// permisos -> ids de las mesas asignadas

	function guardar_asignacion($objeto) {
		// Escapa para evitar hack
		$asignacion = $this -> escapalog($objeto['asignacion']);
		$empleado = $this -> escapalog($objeto['id']);

		// Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
		// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($empleado)) ? $empleado : 0;

		$sql = "	INSERT INTO
							com_actividades
								(id, empleado, accion, fecha)
						VALUES
							(''," . $usuario . ",'Guarda asignacion', '" . $fecha . "')";
		$actividad = $this -> query($sql);

		// Consulta el ID del mesero para comparar si existe
		$sql = "	SELECT
							id_mesero
						FROM
							com_meseros
						WHERE
							id_mesero=" . $empleado;
		$result = $this -> queryArray($sql);

		// Si existe actualiza sus asignaciones
		if ($result['total'] > 0) {
			$sql = "	UPDATE
								com_meseros
							SET
								asignacion='" . $asignacion . "'
							WHERE 
								id_mesero=" . $empleado;
			// return $sql;
			$result = $this -> query($sql);
			// Si no lo agrega a la BD
		} else {
			$sql = "	INSERT INTO
								com_meseros
								(id, id_mesero, permisos, asignacion)
							VALUES
								('', " . $empleado . ",'', '" . $asignacion . "')";
			$result = $this -> query($sql);

		}

		return $result;
	}

///////////////// ******** ---- 		FIN guardar_asignacion		------ ************ //////////////////

///////////////// ******** ---- 	autorizar_asignacion		------ ************ //////////////////
//////// Autoriza la asignacion de mesas
	// Como parametros puede recibir:
		//	id -> id del empleado
		//	asignacion -> cada con los ID´s de las mesas asignadas

	function autorizar_asignacion($objeto) {
	// Escapa para evitar hack
		$asignacion = $this -> escapalog($objeto['asignacion']);
		$empleado = $this -> escapalog($objeto['id']);

	// Consulta para saber si existe el mesero
		$sql = "SELECT
					id_mesero
				FROM
					com_meseros
				WHERE
					id_mesero = " . $empleado;
		$result = $this -> queryArray($sql);

		// Si no existe el messero lo agrega
		if ($result['total'] > 0) {
			$sql = "	UPDATE
								com_meseros
							SET
								permisos='" . $asignacion . "'
							WHERE 
								id_mesero=" . $empleado;
			// return $sql;
			$result = $this -> query($sql);
		} else {
			$sql = "	INSERT INTO
								com_meseros
								(id, id_mesero, permisos, asignacion)
							VALUES
								('', " . $empleado . ",'" . $asignacion . "', '" . $asignacion . "')";
			$result = $this -> query($sql);

		}

	// Actualiza las mesas para que aparesca el nombre del mesero en el mapa de mesas
		if (!empty($asignacion)) {
			$sql = "	UPDATE
								com_mesas
							SET
								idempleado=" . $empleado . "
							WHERE
								id_mesa IN(" . $asignacion . ");";
			$result = $this -> query($sql);
		}

	// Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
	
	// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;
		$sql = "	INSERT INTO
							com_actividades
								(id, empleado, accion, fecha)
						VALUES
							(''," . $usuario . ",'Autoriza asignacion', '" . $fecha . "')";
		$actividad = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN autorizar_asignacion		------ ************ //////////////////

///////////////// ******** ---- 		listar_productos_comanda	------ ************ //////////////////
//////// Consulta los productos de la comanda y las regresa en un array
	// Como parametros recibe:
	// idcomanda -> id de la comanda

	function listar_productos_comanda($objeto) {
		//echo json_encode($objeto);
		$idSuc = 1;
		/// id suc
		
		$sql1 = 'SELECT idSuc FROM administracion_usuarios where idEmpleado = '.$_SESSION['accelog_idempleado'].';';
		$result1 = $this->queryArray($sql1);
		$idSuc = $result1['rows'][0]['idSuc'];

		/// id suc fin

		// Filtra por status
		$condicion = (!empty($objeto['status_padre'])) ? " AND a.status != " . $objeto['status_padre'] : '';
		$condicion .= (!empty($objeto['status'])) ? " AND a.status = " . $objeto['status'] : ' AND a.status != 3';

		$sql = "SELECT 
					a.idProducto, a.cantidad, b.nombre, a.id AS pedido, b.id, 

					(CASE WHEN (Select precio from app_precio_sucursal where sucursal = '$idSuc' and producto = a.idproducto limit 1) IS NULL THEN ROUND(b.precio, 2)ELSE ROUND((Select precio from app_precio_sucursal where sucursal = '$idSuc' and producto = a.idproducto limit 1), 2) END) as precioventa,

					a.adicionales, b.ruta_imagen AS imagen,d.codigo, d.personas,
					d.timestamp AS fecha_comanda
				FROM 
					com_pedidos a 
				INNER JOIN 
						app_productos b 
					ON 
						b.id=a.idproducto 
				LEFT JOIN 
						com_comandas d 
					ON 
						d.id=" . $objeto['idComanda'] . "
				LEFT JOIN 
						com_mesas c 
					ON 
						c.id_mesa=d.idmesa 
				WHERE 
					idcomanda=" . $objeto['idComanda'] . " 
				AND
					a.origen = 1 " . 
				$condicion . "
				ORDER BY 
					a.id ASC";
		// return $sql;
		$result = $this -> queryArray($sql);

	// Valida que tenga datos la consulta
		if ($result['total'] > 0) {
			$result = $result['rows'];

			foreach ($result as $key => $value) {
				if ($value['adicionales'] != "") {
				// Obtiene el costo y nombre de los productos
					$sql = "SELECT 
								ROUND(b.precio, 2) AS costo, b.id
							FROM 
								app_productos b
							WHERE 
								id in(" . $value['adicionales'] . ")";
					$costo_extra = $this -> queryArray($sql);

					$result[$key]['extras'] = $costo_extra['rows'];
				}
			}

			return $result;
		} else {
			return $result = 0;
		}
	}

///////////////// ******** ---- 		FIN listar_productos_comanda		------ ************ //////////////////

///////////////// ******** ---- 		guardar_comanda_parcial		------ ************ //////////////////
//////// Crear una comanda parcial, la guarda e imprime un Ticket
	// Como parametros recibe:
	// idpadre -> ID de la comanda padre
	// mesa -> ID de la mesa
	// Persona -> numero de persona
	// ids_pedidos -> cadena con los id´s de los pedidos
	// total -> total de  la sub comanda

	function guardar_comanda_parcial($objeto) {
		$sql = "INSERT INTO
					com_sub_comandas
					(id, idpadre, mesa, persona, pedidos, total, fecha, empleado, estatus)					
				VALUES
					(''," . $objeto['idpadre'] . "," . $objeto['mesa'] . "," . $objeto['persona'] . ",
					'" . $objeto['ids_pedidos'] . "'," . $objeto['total'] . ",'" . $objeto['fecha'] . "',
					" . $objeto['empleado'] . ",'2');";
		$result = $this -> insert_id($sql);

		// Guarda la actividad
		date_default_timezone_set('America/Mexico_City');
		$fecha = date('Y-m-d H:i:s');
		// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;
		$sql = "	INSERT INTO
								com_actividades
									(id, empleado, accion, fecha)
							VALUES
								(''," . $usuario . ",'Guarda sub comanda', '" . $fecha . "')";
		$actividad = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN guardar_comanda_parcial		------ ************ //////////////////


	///////////////// ******** ---- 		guardar_comanda_parcial2		------ ************ //////////////////

//////// Crear una comanda parcial, la guarda e imprime un Ticket
	// Como parametros recibe:
	// idpadre -> ID de la comanda padre
	// mesa -> ID de la mesa
	// Persona -> numero de persona
	// ids_pedidos -> cadena con los id´s de los pedidos
	// total -> total de  la sub comanda

	function guardar_comanda_parcial2($idpadre,$mesa,$persona,$pedidos,$total,$fecha,$empleado,$divcant,$divporc,$cantidad) {
		$sql = "INSERT INTO
					com_sub_comandas
					(id, idpadre, mesa, persona, pedidos, total, fecha, empleado, estatus, divcant, divporc,tipo,cantidad)					
				VALUES
					('','".$idpadre."','".$mesa."','".$persona."','".$pedidos."','".$total."','" .$fecha."','".$empleado."','2','".$divcant."','".$divporc."',2,'".$cantidad."');";
		$result = $this -> insert_id($sql);

		// Guarda la actividad
		date_default_timezone_set('America/Mexico_City');
		$fecha = date('Y-m-d H:i:s');
		// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;
		$sql = "	INSERT INTO
								com_actividades
									(id, empleado, accion, fecha)
							VALUES
								(''," . $usuario . ",'Guarda sub comanda', '" . $fecha . "')";
		$actividad = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN guardar_comanda_parcial2		------ ************ //////////////////

///////////////// ******** ---- 		actualizar_comanda_parcial		------ ************ //////////////////
//////// Actualiza la comanda parcial
	// Como parametros recibe:
	// codigo -> codigo de la sub comanda
	// id -> ID de la comanda

	function actualizar_comanda_parcial($objeto) {
		// Actualiza el codigo si viene
		$campos = (!empty($objeto['codigo'])) ? " codigo='" . $objeto['codigo'] . "'" : '';

		$sql = "UPDATE
					com_sub_comandas
				SET  
					" . $campos . "
				WHERE		
					id = " . $objeto['id'];
		// return $sql;
		$result = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN actualizar_comanda_parcial		------ ************ //////////////////

///////////////// ******** ---- 		actualizar_comanda_parcial2		------ ************ //////////////////

//////// Actualiza la comanda parcial
	// Como parametros recibe:
	// codigo -> codigo de la sub comanda
	// id -> ID de la comanda

	function actualizar_comanda_parcial2($objeto) {
		// Actualiza el codigo si viene
		$campos = (!empty($objeto['codigo'])) ? " codigo='" . $objeto['codigo'] . "'" : '';

		$sql = "UPDATE
					com_sub_comandas
				SET  
					" . $campos . "
				WHERE		
					id = " . $objeto['id'];
		// return $sql;
		$result = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN actualizar_comanda_parcial2		------ ************ //////////////////

///////////////// ******** ---- 		actualizar_pedidos		------ ************ //////////////////
//////// Actualiza el estatus de los pedidos de la comanda padre
	// Como parametros recibe:
		// codigo -> codigo de la sub comanda
		// ids_pedidos -> cadena con los id´s de los pedidos

	function actualizar_pedidos($objeto) {
	// Actualiza el codigo si viene
		$campos = (!empty($objeto['status'])) ? " status = " . $objeto['status'] : '';

		$sql = "UPDATE
					com_pedidos
				SET  
					" . $campos . "
				WHERE		
					id in(" . $objeto['ids_pedidos'] . ")";
		// return $sql;
		$result = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN actualizar_pedidos		------ ************ //////////////////

///////////////// ******** ---- 		actualizar_comanda			------ ************ //////////////////
//////// Actualiza la comanda con nuevos datos
	// Como parametros recibe:
		// id -> ID de la comanda
		// status -> status de la comanda
		// total -> Total de la comanda

	function actualizar_comanda($objeto) {
		//echo json_encode($objeto);
	// Actualiza el status si viene
		$campos .= (!empty($objeto['status'])) ? " status = " . $objeto['status'] : '';
	// Actualiza el total si viene
		//$campos .= (!empty($objeto['total'])) ? " total = " . $objeto['total'] : '';
	// Actualiza el numero de personas en la comanda
		$campos .= (!empty($objeto['personas'])) ? " personas = " . $objeto['personas'] : '';
	// Actualiza el total si viene
		$campos .= (!empty($objeto['promedio_comensal'])) ? " promedioComensal = " . $objeto['promedio_comensal'] : '';

		$sql = "UPDATE
					com_comandas
				SET  
					" . $campos . "
				WHERE
					id = " . $objeto['id'];
					//echo $s
		// return $sql;
		$result = $this -> query($sql);

		//busca el id de la mesa
		$sql2 = "SELECT 
					idmesa
				FROM
				   com_comandas 
				WHERE  
				id = " . $objeto['id'] ; 
				
				;
		$result2 = $this -> queryArray($sql2);
		
		$idmesa = $result2['rows'][0]['idmesa'];

	//actualiza el estatus de la mesa para reabrir solo pedidos para llevar y domicilio
		$sql3 = "UPDATE
				com_mesas
				SET
				 status = 1
				WHERE
				 id_mesa = '$idmesa'
				 AND tipo > 0 ";

				 $result3 = $this -> query($sql3);	

				
					return $result;
	}	

///////////////// ******** ---- 		FIN actualizar_comanda		------ ************ //////////////////

///////////////// ******** ---- 			bloquear_mesas			------ ************ //////////////////
//////// Bloquea las mesas asignadas para que no las pueda seleccionar el usuario
	// Como parametros recibe:

	function bloquear_mesas($objeto) {
		// Actualiza el codigo si viene
		$campos = (!empty($objeto['status'])) ? "status=" . $objeto['status'] : '';

		$sql = "	SELECT 
							GROUP_CONCAT(asignacion) ids 
						FROM 
							com_meseros
						WHERE 
							asignacion!=''";
		// return $sql;
		$ids = $this -> queryArray($sql);

		$sql = "SELECT
						id_mesa
				FROM
					com_mesas
				WHERE
					id_mesa IN(" . $ids['rows'][0]['ids'] . ");";
		// return $sql;
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN bloquear_mesas		------ ************ //////////////////

///////////////// ******** ---- 	reiniciar_asignacion		------ ************ //////////////////
//////// Reinicia las asignaciones de los meseros
	// Como parametros puede recibir:
	//	pass -> contraseña del admin

	function reiniciar_asignacion($objeto) {
		// Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
		// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;
		$sql = "	INSERT INTO
							com_actividades
								(id, empleado, accion, fecha)
						VALUES
							(''," . $usuario . ",'Reinicia asignacion', '" . $fecha . "')";
		$actividad = $this -> query($sql);

		$sql = "	UPDATE
							com_meseros
						SET
							asignacion=NULL,
							permisos=NULL";
		// return $sql;
		$result = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN reiniciar_asignacion		------ ************ //////////////////

///////////////// ******** ----  		sumar_pedido					------ ************ //////////////////
	// Aumenta la cantidad de la orden y lista los productos de la persona
	// Como parametro puede recibi:
	// idorder -> ID del pedido
	// idcomanda -> ID de la comanda
	// idperson -> numero de  persona

	function sumar_pedido($objeto) {
		session_start();
	// Agrega un pedido igual a la comanda
		$sql = "INSERT INTO
					com_pedidos
					(idcomanda, idproducto, cantidad, npersona, tipo, status, opcionales, adicionales,
						sin, nota, nota_opcional, nota_extra, nota_sin, id_sub_comanda,costo)
					SELECT
						idcomanda, idproducto, cantidad, npersona, tipo, -1, opcionales, adicionales,
						sin, nota, nota_opcional, nota_extra, nota_sin, id_sub_comanda, costo
					FROM
						com_pedidos
					WHERE
						id = " . $objeto['idorder'];
		// return $sql;
		$id_pedido = $result = $this -> insert_id($sql);

	// Consulta los datos del producto
		$sql = "SELECT
					p.id, p.idcomanda, p.adicionales, m.id AS idproducto, p.cantidad, 
					ROUND(m.precio, 2) AS precioventa, m.tipo_producto
				FROM
					com_pedidos p
				INNER JOIN
						app_productos m
					ON
						p.idproducto = m.id
				WHERE
					p.id = " . $objeto['idorder'];
		$result = $this -> queryArray($sql);
		$result['datos'] = $result['rows'][0];
		$precio = $result['datos']['precioventa'];
		$idproducto = $result['datos']['idproducto'];
		$tipo_producto = $result['datos']['tipo_producto'];
		
	// Guarda los pedidos del kit(si es kit)
		if ($tipo_producto == 6) {
			$sql = "INSERT INTO
						com_pedidos_kit
						(id_pedido, id_comanda, id_producto, persona, status, opcionales, extras, sin, nota_opcional, nota_extra, nota_sin)
						SELECT
							".$id_pedido.", id_comanda, id_producto, persona, -1, opcionales, extras, sin, nota_opcional, nota_extra, nota_sin
						FROM
							com_pedidos_kit
						WHERE
							id_pedido = " . $objeto['idorder'];
			// return $sql;
			$result_kit = $this -> query($sql);
		}
			
	// Guarda los pedidos del combo(si es combo)
		if ($tipo_producto == 7) {
			$sql = "INSERT INTO
						com_pedidos_combo
						(id_pedido, id_comanda, id_producto, persona, status, opcionales, extras, sin, nota_opcional, nota_extra, nota_sin)
						SELECT
							".$id_pedido.", id_comanda, id_producto, persona, -1, opcionales, extras, sin, nota_opcional, nota_extra, nota_sin
						FROM
							com_pedidos_combo
						WHERE
							id_pedido = " . $objeto['idorder'];
			// return $sql;
			$result_kit = $this -> query($sql);
		}
			
	/* Impuestos del producto
	============================================================================= */

		$impuestos_comanda = 0;
		$objeto['id'] = $idproducto;
		$impuestos = $this -> listar_impuestos($objeto);

		if ($impuestos['total'] > 0) {
			foreach ($impuestos['rows'] as $k => $v) {
				if ($v["clave"] == 'IEPS') {
					$producto_impuesto = $ieps = (($precio) * $v["valor"] / 100);
				} else {
					if ($ieps != 0) {
						$producto_impuesto = ((($precio + $ieps)) * $v["valor"] / 100);
					} else {
						$producto_impuesto = (($precio) * $v["valor"] / 100);
					}
				}

				// Precio actualizado
				$precio += $producto_impuesto;
				$precio = round($precio, 2);

				$impuestos_comanda += $producto_impuesto;
			}
		}

	/* FIN Impuestos del producto
	============================================================================= */

	// Obtiene los costos de los productos extra si existen
		if (!empty($result['datos']['adicionales'])) {
			$sql = 'SELECT 
						ROUND(b.precio, 2) AS precioventa, id
					FROM 
						app_productos b
					WHERE
						id in(' . $result['datos']['adicionales'] . ')';
			$precios_extra = $this -> queryArray($sql);

		// Recorre los costos y los agrega al precio
			foreach ($precios_extra['rows'] as $key => $value) {
			/* Impuestos del producto
			============================================================================= */

				$objeto['id'] = $value['id'];
				$impuestos = $this -> listar_impuestos($objeto);
				
				if ($impuestos['total'] > 0) {
					foreach ($impuestos['rows'] as $k => $v) {
						if ($v["clave"] == 'IEPS') {
							$producto_impuesto = $ieps = (($value['precioventa']) * $v["valor"] / 100);
						} else {
							if ($ieps != 0) {
								$producto_impuesto = ((($value['precioventa'] + $ieps)) * $v["valor"] / 100);
							} else {
								$producto_impuesto = (($value['precioventa']) * $v["valor"] / 100);
							}
						}

						// Precio actualizado
						$precio += $producto_impuesto + $value['precioventa'];
						$precio = round($precio, 2);

						$impuestos_comanda += $producto_impuesto;
					}
				}

			/* FIN Impuestos del producto
			============================================================================= */
			}
		}

	//** Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
	// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;
		$sql = "INSERT INTO
					com_actividades
						(id, empleado, accion, fecha)
				VALUES
					(''," . $usuario . ",'Suma un pedido', '" . $fecha . "')";
	// return $sql;
		$actividad = $this -> query($sql);
		
	// Actualiza el total de la comanda
		
		$sql = "UPDATE
					com_comandas
				SET
					total = total
				WHERE
				id=" . $objeto['idcomanda'];
				//print_r($sql);
		$result['comanda'] = $this -> query($sql);
		

		return $result;
	}

///////////////// ******** ---- 		FIN sumar_pedido		------ ************ //////////////////

///////////////// ******** ---- 		listar_actividades		------ ************ //////////////////
//////// Consulta las actividades y las regresa en un array
	// Como parametros recibe:
		// empleado -> id del empleado
		// f_ini -> fecha y hora de inicio
		// F_fin -> fecha y hora final
		// actividad -> actividad seleccionada

	function listar_actividades($objeto) {
	// Filtra por la sucursal si existe
		$condicion .= ($objeto['sucursal'] != '*' && !empty($objeto['sucursal'])) ? 
			' AND suc.idSuc = \'' . $objeto['sucursal'] . '\'' : '';
	// Si viene el id del empleado Filtra por empleado
		$condicion .= ($objeto['empleado'] != '*' && !empty($objeto['empleado'])) ? 
			' AND u.idempleado=\'' . $objeto['empleado'] . '\'' : '';
	// Filtra por actividad si existe
		$condicion .= ($objeto['actividad'] != '*' && !empty($objeto['actividad'])) ? 
			' AND accion=\'' . $objeto['actividad'] . '\'' : '';
	// Se filtra por fecha de inicio y fin si estas existen
		$condicion .= (!empty($objeto['f_ini']) && !empty($objeto['f_fin'])) ? 
			' AND fecha BETWEEN \'' . $objeto['f_ini'] . '\' AND \'' . $objeto['f_fin'] . '\'' : '';
		
	// Agrupa los registros
		$agrupar = (!empty($objeto['agrupar'])) ? ' GROUP BY ' . $objeto['agrupar'] . '' : ' GROUP BY a.id';

	// Ordena la consulta por los parametros indicados si existe, si no la ordena por id Descendente
		$orden .= (!empty($objeto['orden'])) ? ' ' . $objeto['orden'] : ' accion ASC';

		$sql = "SELECT 
					COUNT(a.id) AS actividades, a.id, empleado AS id_empleado, usuario AS empleado, 
					accion, fecha, a.descripcion, a.id_sucursal, suc.nombre AS sucursal
				FROM 
					com_actividades a
				LEFT JOIN
						accelog_usuarios u
					ON
					 	a.empleado = u.idempleado
				LEFT JOIN
						administracion_usuarios ad
					ON
					 	ad.idempleado = a.empleado
				LEFT JOIN
						mrp_sucursal suc
					ON
					 	suc.idSuc = ad.idSuc
				WHERE
					1 = 1 " . 
				$condicion . " " . 
				$agrupar . "
				ORDER BY " . 
					$orden;
		$result['sql'] = $sql;
		$result['result'] = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN listar_actividades		------ ************ //////////////////

///////////////// ******** ---- 	asignar_mesa		------ ************ //////////////////
//////// Asigna la mesa al mesero
	// Como parametros puede recibir:
	// empleado -> ID del mesero
	// mesa -> ID de la mesa

	function asignar_mesa($objeto) {
		$sql = "UPDATE 
					com_mesas
				SET 
					idempleado = " . $objeto['empleado'] . "
				WHERE 
					id_mesa=" . $objeto['mesa'];
		// return $sql;
		$result = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN	asignar_mesa			------ ************ //////////////////

///////////////// ******** ---- 		listar_comensalesXmesa		------ ************ //////////////////
//////// Obtien los registros de los comensales y los carga en la div
	// Como parametros recibe:
		// empleado -> id del empleado
		// f_ini -> fecha y hora inicial
		// f_fin -> Fecha y hora final
		// mesa -> ID de la mesa

	function listar_comensalesXmesa($objeto) {
	// Filtra por la sucursal si existe
		$condicion .= ($objeto['sucursal'] != '*' && !empty($objeto['sucursal'])) ? 
			' AND m.idSuc = \'' . $objeto['sucursal'] . '\'' : '';
	// Si viene el id del empleado Filtra por empleado
		$condicion .= ($objeto['empleado'] != '*' && !empty($objeto['empleado'])) ? 
			' AND u.idempleado = \'' . $objeto['empleado'] . '\'' : '';
	// Filtra por mesa si existe
		$condicion .= ($objeto['mesa'] != '*' && !empty($objeto['mesa'])) ? 
			' AND mesa = \'' . $objeto['mesa'] . '\'' : '';
	// Se filtra por fecha de inicio y fin si estas existen
		$condicion .= (!empty($objeto['f_ini']) && !empty($objeto['f_fin'])) ? 
			' AND fecha BETWEEN \'' . $objeto['f_ini'] . '\' AND \'' . $objeto['f_fin'] . '\'' : '';

	// Ordena la consulta por los parametros indicados si existe, si no la ordena por id Descendente
		$orden .= (!empty($objeto['orden'])) ? ' ' . $objeto['orden'] : ' accion ASC';

		$sql = "SELECT 
					id, empleado as id_empleado, usuario as empleado, accion, fecha
				FROM 
					com_actividades a , accelog_usuarios u 
				WHERE 
					a.empleado = u.idempleado 
				AND
					" . 
				$condicion . " " . 
				$agrupar . "
				ORDER BY " . 
							$orden;
		// $result['sql']= $sql;
		$result['result'] = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN listar_comensalesXmesa			------ ************ //////////////////

///////////////// ******** ---- 			reiniciar_mesas					------ ************ //////////////////
//////// Actualiza las cordenadas de las mesas en la BD
	// Como parametros recibe:

	function reiniciar_mesas($objeto) {
		$sql = "UPDATE 
					com_mesas
				SET 
					x = ".$objeto['x'].",
					y = ".$objeto['y']."
				WHERE
					id_mesa = ".$objeto['id_mesa'];
		// return $sql;
		$result = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN	reiniciar_mesas					------ ************ //////////////////

///////////////// ******** ---- 			listar_zonas					------ ************ //////////////////
//////// Obtien los registros de las zonas de mayor influencia y las regresa en un array
	// Como parametros recibe:
		// empleado -> id del empleado
		// f_ini -> fecha y hora inicial
		// f_fin -> Fecha y hora final
		// mesa -> ID de la mesa
		// zona -> nombre del area de la mesa
		// comandas -> total de comandas
		// sucursal -> ID de la sucursal

	function listar_zonas($objeto) {
	// Filtra por la sucursal si existe
		$condicion .= ($objeto['sucursal'] != '*' && !empty($objeto['sucursal'])) ? 
			' AND m.idSuc = \'' . $objeto['sucursal'] . '\'' : '';
	// Filtra por empleado si existe
		$condicion .= ($objeto['empleado'] != '*' && !empty($objeto['empleado'])) ? 
			' AND u.idempleado=\'' . $objeto['empleado'] . '\'' : '';
	// Filtra por mesa si existe
		$condicion .= ($objeto['mesa'] != '*' && !empty($objeto['mesa'])) ? 
			' AND m.id_mesa=\'' . $objeto['mesa'] . '\'' : '';
	// Filtra por zona si existe
		$condicion .= ($objeto['zona'] != '*' && !empty($objeto['zona'])) ? 
			' AND m.nombre=\'' . $objeto['zona'] . '\'' : '';
	// Se filtra por fecha de inicio y fin si estas existen
		$condicion .= (!empty($objeto['f_ini']) && !empty($objeto['f_fin'])) ? 
			' AND timestamp BETWEEN \'' . $objeto['f_ini'] . '\' AND \'' . $objeto['f_fin'] . '\'' : '';
	// Filtra por numero de comandas si existe
		$having .= (!empty($objeto['comandas'])) ? ' HAVING COUNT(c.id) >= \'' . $objeto['comandas'] . '\'' : '';

	// Ordena la consulta por los parametros indicados si existe, si no la ordena por id Descendente
		$orden .= (!empty($objeto['orden'])) ? ' ' . $objeto['orden'] : ' comandas DESC';

		$sql = "SELECT
					GROUP_CONCAT(DISTINCT m.nombre) AS mesa, d.nombre AS zona, GROUP_CONCAT(DISTINCT u.usuario) AS empleado, u.usuario,
					m.personas AS comensales, COUNT(c.id) AS comandas, SUM(c.total) AS total, suc.nombre AS sucursal
				FROM
					com_comandas c
				INNER JOIN
						com_mesas m
					ON
						m.id_mesa = c.idmesa
				LEFT JOIN
						mrp_departamento d
					ON
						d.idDep = m.idDep
				INNER JOIN
						accelog_usuarios u
					ON
						m.idempleado = u.idempleado
				LEFT JOIN
						mrp_sucursal suc
					ON
						suc.idSuc = m.idSuc
				WHERE 
					m.tipo = 0" . $condicion . "
				GROUP BY
					d.nombre, suc.idSuc" . $having . "
				ORDER BY " . $orden;
		// return $sql;
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN listar_zonas		------ ************ //////////////////

///////////////// ******** ---- 		listar_ocupacion		------ ************ //////////////////
//////// Obtien los registros de las ocupaciones y las regresa en un array
	// Como parametros recibe:
		// empleado -> id del empleado
		// f_ini -> fecha y hora inicial
		// f_fin -> Fecha y hora final
		// mesa -> ID de la mesa
		// zona -> nombre del area de la mesa
		// comandas -> total de comandas

	function listar_ocupacion($objeto) {
	// Filtra por la sucursal si existe
		$condicion .= ($objeto['sucursal'] != '*' && !empty($objeto['sucursal'])) ? 
			' AND m.idSuc = \'' . $objeto['sucursal'] . '\'' : '';
	// Filtra por empleado si existe
		$condicion .= ($objeto['empleado'] != '*') ? ' AND u.idempleado=\'' . $objeto['empleado'] . '\'' : '';
	// Filtra por mesa si existe
		$condicion .= ($objeto['mesa'] != '*') ? ' AND m.id_mesa=\'' . $objeto['mesa'] . '\'' : '';
	// Filtra por zona si existe
		$condicion .= ($objeto['zona'] != '*') ? ' AND d.nombre=\'' . $objeto['zona'] . '\'' : '';
	// Se filtra por fecha de inicio y fin si estas existen
		$condicion .= (!empty($objeto['f_ini']) && !empty($objeto['f_fin'])) ? 
			' AND timestamp BETWEEN \'' . $objeto['f_ini'] . '\' AND \'' . $objeto['f_fin'] . '\'' : '';
	// Filtra por numero de comandas si existe
		$having .= (!empty($objeto['comandas'])) ? ' HAVING COUNT(c.id) >= \'' . $objeto['comandas'] . '\'' : '';

	// Ordena la consulta por los parametros indicados si existe, si no la ordena por id Descendente
		$orden .= (!empty($objeto['orden'])) ? ' ' . $objeto['orden'] : ' HOUR(timestamp) ASC';

		$sql = "SELECT
					CONCAT('0000-00-00 ', DATE_FORMAT(timestamp, '%H:%i:%s')) AS hora_grafica,
					DATE_FORMAT(timestamp, '%h %p') AS hora, COUNT(c.id) as comandas, 
					GROUP_CONCAT(DISTINCT m.nombre) AS mesas, GROUP_CONCAT(DISTINCT d.nombre) AS zonas,
					GROUP_CONCAT(DISTINCT m.idempleado) AS empleado,GROUP_CONCAT(DISTINCT u.usuario) AS usuarios,
					SUM(c.personas) AS comensales, suc.nombre AS sucursal
				FROM
					com_comandas c
				INNER JOIN
						com_mesas m
					ON
						m.id_mesa=c.idmesa
				INNER JOIN
						accelog_usuarios u
					ON
						m.idempleado = u.idempleado
				LEFT JOIN
						mrp_departamento d
					ON
						d.idDep = m.idDep
				LEFT JOIN
						mrp_sucursal suc
					ON
						suc.idSuc = m.idSuc
				WHERE 
					m.tipo=0" . $condicion . "
				GROUP BY
					HOUR(timestamp), suc.idSuc" . $having . "
				ORDER BY " . $orden;
		// return $sql;
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN listar_ocupacion		------ ************ //////////////////

///////////////// ******** ---- 		guardar_comensales			------ ************ //////////////////
//////// Actualiza el numero de comensales en la BD
	// Como parametros puede recibir:
	//	comanda -> ID de la comanda
	// comensales -> numero de comensales

	function guardar_comensales($objeto) {
		$sql = "	UPDATE 
							com_comandas
						SET 
							comensales=" . $objeto['comensales'] . "
						WHERE
							id=" . $objeto['comanda'];
		// return $sql;
		$result = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN	guardar_comensales		------ ************ //////////////////

///////////////// ******** ---- 	agregar_area				------ ************ //////////////////
//////// Inserta la mesa en la BD
	// Como parametros puede recibir:
		// nom_area -> nombre del area

	function agregar_area($objeto) {
		
			$sql = "INSERT INTO
						mrp_departamento
						(nombre, idsuc)
					VALUES
						('".$objeto['nom_area']."','".$objeto['suc_area']."')";
		
		$result = $this -> insert_id($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN	agregar_area		------ ************ //////////////////

///////////////// ******** ---- 	delete_area				------ ************ //////////////////
//////// Inserta la mesa en la BD
	// Como parametros puede recibir:
		// area -> id del area

	function delete_area($objeto) {
		
		$sql = "SELECT * from mrp_familia where idDep = ".$objeto['area'];
		
		$result = $this -> queryArray($sql);

		foreach ($result['rows'] as $key => $value) {
			$sql = "DELETE FROM
						mrp_linea
					WHERE
						idFam = ".$value['idFam'];
		
			$this -> query($sql);
		}

		$sql = "DELETE FROM
						mrp_familia
					WHERE
						idDep = ".$objeto['area'];
		
		$result = $this -> query($sql);

		$sql = "DELETE FROM
						mrp_familia
					WHERE
						idDep = ".$objeto['area'];
		
		$result = $this -> query($sql);

		if($result)
			{$sql = "DELETE FROM
						mrp_departamento
					WHERE
						idDep = ".$objeto['area'];
		
			$result = $this -> query($sql);
			$sql = "DELETE FROM
						com_mesas
					WHERE
						idDep = ".$objeto['area'];
		
			$this -> query($sql);
		}

		return $result;
	}

///////////////// ******** ---- 	FIN	delete_area		------ ************ //////////////////

///////////////// ******** ---- 	edit_area				------ ************ //////////////////
//////// Inserta la mesa en la BD
	// Como parametros puede recibir:
		// nom_area -> nombre del area
		// area -> id del area

	function edit_area($objeto) {
			$sql = "UPDATE
						mrp_departamento
					SET
						nombre = '".$objeto['nom_area']."', 
						idsuc = '".$objeto['idsuc']."'
					WHERE 
						idDep = " . $objeto['area'];
		$result = $this -> query($sql);

		if($result){
			return $objeto['area'];
		}
		else
			return $result;
	}

///////////////// ******** ---- 	FIN	edit_area		------ ************ //////////////////


///////////////// ******** ---- 	agregar_mesas				------ ************ //////////////////
//////// Inserta la mesa en la BD
	// Como parametros puede recibir:
			// tipo_mesa -> tipo de mesa a agregar
			// num_mesas -> numero de mesas a aagregar o numero de personas
			// empleado -> id empleado
			// idDep -> id del area a donde se va asignar la mesa
			// area -> id del area para crear
			// nombre_area -> nombre del area
			// total_barras -> numero total de barras
			// total_mesas -> numero total de mesas
			// total_sillones -> numero total de sillones

	function agregar_mesas($objeto) {
		$empleado = (!empty($objeto['empleado'])) ? ', ' . $objeto['empleado'] : ', ""';
		if (!empty($objeto['sucursal'])) {
			$sucursal = $objeto['sucursal'];
		} else {
		session_start();
		$sucursal = "	SELECT 
							mp.idSuc AS id 
						FROM 
							administracion_usuarios au 
						INNER JOIN 
								mrp_sucursal mp 
							ON 
								mp.idSuc = au.idSuc 
						WHERE 
							au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
						LIMIT 1";
		$sucursal = $this -> queryArray($sucursal);
		$sucursal = $sucursal['rows'][0]['id'];
		}
		if($objeto['tipo_mesa'] == 8){
			$sql = "INSERT INTO
						com_mesas
						(idDep, nombre, tipo_mesa, tipo, id_area, x, y, idSuc)
					VALUES
						('".$objeto['idDep']."', '".trim($objeto['nombre_area'])."', " . $objeto['tipo_mesa'] . ", 0," . $objeto['area'] . ", -1, -1, '".$sucursal."')";
			
		} else if($objeto['tipo_mesa'] == 9){ 
			$sql = "INSERT INTO
						com_mesas
						(idDep, nombre, tipo_mesa, tipo, idempleado, x, y, idSuc, id_dependencia)
					VALUES
						('".$objeto['idDep']."', '".$objeto['nombre']."', " . $objeto['tipo_mesa'] . ", 0" . $empleado . ", -1, -1, '".$sucursal."', '".$objeto['id_dependencia']."')";
		}else {
			$sql = "INSERT INTO
						com_mesas
						(idDep, nombre, tipo_mesa, tipo, idempleado, x, y, idSuc)
					VALUES
						('".$objeto['idDep']."', '".$objeto['nombre']."', " . $objeto['tipo_mesa'] . ", 0" . $empleado . ", -1, -1, '".$sucursal."')";
		}
		$result = $this -> insert_id($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN	agregar_mesas		------ ************ //////////////////

///////////////// ******** ---- 	editar_mesa				------ ************ //////////////////
//////// Edita la mesa en la BD
	// Como parametros puede recibir:
		// tipo_mesa -> tipo de mesa
		// nombre_mesa -> nombre de la mesa
		// mesa -> id mesa a editar
		// empleado -> id empleado

	function editar_mesa($objeto) {

		$sql = "UPDATE
					com_mesas
				SET
					tipo_mesa = '". $objeto['tipo_mesa'] . "', 
					nombre = '". $objeto['nombre_mesa'] ."', 
					idempleado = '". $objeto['empleado'] ."'	
				WHERE
					id_mesa = " . $objeto['mesa'];
		$result = $this -> query($sql);

		if ($result) {
			return $objeto['mesa'];
		}
		return 0;
	}

///////////////// ******** ---- 	FIN	editar_mesa		------ ************ //////////////////

///////////////// ******** ---- 	eliminar_mesa				------ ************ //////////////////
//////// Inserta la mesa en la BD
	// Como parametros puede recibir:
		// mesa -> id mesa a eliminar

	function eliminar_mesa($objeto) {
		$sql = "UPDATE
					com_mesas
				SET
					status = 2
				WHERE
					id_mesa = " . $objeto['mesa'] ." 
				OR 
					id_dependencia = ". $objeto['mesa'];
		$result = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN	eliminar_mesa		------ ************ //////////////////

///////////////// ******** ---- 	vista_editar_mesa				------ ************ //////////////////
//////// Inserta la mesa en la BD
	// Como parametros puede recibir:
		// mesa -> id mesa a eliminar

	function vista_editar_mesa($objeto) {
		$sql = "SELECT
					id_mesa, nombre, idempleado, tipo_mesa
				FROM
					com_mesas
				WHERE
					status = 1 
				and 
					id_mesa = ".$objeto['mesa'];

		$result = $this -> queryArray($sql);
		return $result['rows'][0];
	}

///////////////// ******** ---- 	FIN	vista_editar_mesa		------ ************ //////////////////

///////////////// ******** ---- 	listar_clientes			------ ************ //////////////////
//////// Consulta los datos de los clientes en la BD
	// Como parametros puede recibir:

	function listar_clientes($objet) {
		$orden = (!empty($objeto['orden'])) ? 'ORDER BY ' . $objeto['orden'] : 'ORDER BY cc.nombre ASC';
		$sql = "SELECT DISTINCT 
					cc.id, cc.nombre, cc.direccion, cc.celular AS tel, cc.email, vc.id id_viacontacto, 
					vc.nombre via_contacto, zp.id id_zonareparto, zp.nombre zona_reparto
				FROM 
					comun_cliente cc
					left join com_mesas m on m.nombre = cc.nombre
					left join com_vias_contacto vc on vc.id = m.id_via_contacto
					left join com_zonas_reparto zp on zp.id = m.id_zona_reparto 
				WHERE
					1=1 AND cc.borrado = 0  " . $orden;
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN listar_clientes			------ ************ //////////////////
///////////////// ******** ---- 	listar_clientes2			------ ************ //////////////////
//////// Consulta los datos de los clientes en la BD
	// Como parametros puede recibir:

	function listar_clientes2($objet) {
		$orden = (!empty($objeto['orden'])) ? 'ORDER BY ' . $objeto['orden'] : 'ORDER BY cc.nombre ASC';
		$sql = "SELECT 
					cc.id, cc.nombre, cc.direccion, cc.celular AS tel, cc.email
				FROM 
					comun_cliente cc
				WHERE
					1=1  AND cc.borrado = 0  ORDER BY cc.id ASC";
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN listar_clientes2			------ ************ //////////////////

///////////////// ******** ---- 	listar_clientes_3			------ ************ //////////////////
//////// Consulta los datos de los clientes en la BD
	// Como parametros puede recibir:

	function listar_clientes_3($objet) {
		$orden = (!empty($objeto['orden'])) ? 'ORDER BY ' . $objeto['orden'] : 'ORDER BY cc.nombre ASC';
		$sql = "SELECT DISTINCT 
					cc.id, cc.nombre, cc.direccion, cc.num_ext as exterior, cc.num_int as interior, cc.cp,
					cc.colonia, cc.celular as cel, cc.telefono1 as tel, cc.email, vc.id as via_contacto,
					zp.id as zona_reparto, vc.nombre as via_contacto_nombre,
					zp.nombre as zona_reparto_nombre
				FROM 
					comun_cliente cc
					left join com_mesas m on m.nombre = cc.nombre
					left join com_vias_contacto vc on vc.id = m.id_via_contacto
					left join com_zonas_reparto zp on zp.id = m.id_zona_reparto 
				WHERE
					1=1  AND cc.borrado = 0 group by cc.id order by cc.id desc";
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN listar_clientes_3			------ ************ //////////////////

///////////////// ******** ---- 		actualizar_permisos		------ ************ //////////////////
//////// Actualiza los permisos del mesero
	// Como parametros recibe:
	// permisos -> cadena con los ID´s de las mesas
	// empleado -> ID del mesero

	function actualizar_permisos($objeto) {
		$sql = "UPDATE
					com_meseros
				SET
					asignacion = if(asignacion,CONCAT(asignacion,'," . $objeto['permisos'] . "'),
									'" . $objeto['permisos'] . "'),
					permisos = if(permisos,CONCAT(permisos,'," . $objeto['permisos'] . "'),
									'" . $objeto['permisos'] . "')
				WHERE 
					id_mesero = " . $objeto['empleado'];
		// return $sql;
		$result = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN actualizar_permisos		------ ************ //////////////////

///////////////// ******** ---- 			validar_mesa		------ ************ //////////////////
//////// Valida que la mesa no este eliminada
	// Como parametros recibe:
		// id -> ID de la mesa

	function validar_mesa($objeto) {
	// Obtiene la sucursal
		if (!empty($objeto['sucursal'])) {
			$sucursal = $objeto['sucursal'];
		} else {
			session_start();
			$sucursal = "	SELECT 
								mp.idSuc AS id 
							FROM 
								administracion_usuarios au 
							INNER JOIN 
									mrp_sucursal mp 
								ON 
									mp.idSuc = au.idSuc 
							WHERE 
								au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
							LIMIT 1";
			$sucursal = $this -> queryArray($sucursal);
			$sucursal = $sucursal['rows'][0]['id'];
		}
		
	// Filtra por el nombre de la mesa o por el ID
		$condicion = (!empty($objeto['nombre'])) ? ' 
					AND nombre = "' . $objeto['nombre'] . '"' : ' AND id_mesa = ' . $objeto['id'];

		$sql = "SELECT
					id_mesa
				FROM
					com_mesas
				WHERE
					status = 1
				AND
					idSuc = " . $sucursal.
				$condicion;
		// return $sql;
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN validar_mesa		------ ************ //////////////////

///////////////// ******** ---- 		listar_impuestos		------ ************ //////////////////
//////// Consulta los impuestos de un producto y los devuelve en un array
	// Como parametros recibe:
	// id -> ID de la mesa

	function listar_impuestos($objeto) {
		$orden = ($objeto['formula'] == 2) ? ' ASC' : ' DESC';

		$sql = "SELECT
					p.id, p.precio, i.valor, i.clave, pi.formula, i.nombre
				FROM 
					app_impuesto i, app_productos p 
				LEFT JOIN
						app_producto_impuesto pi 
					ON	
						p.id = pi.id_producto 
				WHERE
					p.id = " . $objeto['id'] . "
				AND
					i.id = pi.id_impuesto 
				ORDER BY
					pi.id_impuesto " . $orden;
		// return $sql;
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN listar_impuestos		------ ************ //////////////////

///////////////// ******** ---- 		actualizar_impuesto		------ ************ //////////////////
//////// Actualiza los impuestos de la comanda
	// Como parametros recibe:
	// id -> ID de la mesa

	function actualizar_impuesto($objeto) {
		$sql = "	UPDATE
								com_comandas
							SET
								impuestos = " . $objeto['impuestos'] . "
							WHERE
								id_comanda = " . $objeto['id'];
		// return $sql;
		$result = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN actualizar_impuesto		------ ************ //////////////////

///////////////// ******** ---- 		listar_ajustes			------ ************ //////////////////
//////// Consulta los ajustes de Foodware y los regresa en un array
	// Como parametros recibe:

	function listar_ajustes($objeto) {
		$sql = "SELECT
					*
				FROM
					com_configuracion";
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 		listar_ajustes			------ ************ //////////////////

///////////////// ******** ---- 		listar_sub_comandas		------ ************ //////////////////
//////// Obtien las sub comandas y las carga en una div
	// Como parametros recibe:
	// div -> div en donde se cargara el contenido
	// id_padre -> ID de la comanda padre

	function listar_sub_comandas($objeto) {
	// Filtra por el ID si existe
		$condicion .= (!empty($objeto['id'])) ? ' AND id = ' . $objeto['id'] : '';

	// Ordena la consulta por los parametros indicados si existe, si no la ordena por id Descendente
		$orden .= (!empty($objeto['orden'])) ? ' ' . $objeto['orden'] : ' s.id DESC';

		$sql = "	SELECT
							s.id, s.idpadre, s.codigo, m.nombre AS nombre_mesa, s.persona, u.usuario, s.fecha, s.total,  
							(CASE s.estatus 
									WHEN 0 THEN
										'Abierta'
									WHEN 1 THEN
										'Cerrada / Pagada'
									WHEN 2 THEN
										'Cerrada / Sin pago'
									WHEN 3 THEN
										'Eliminada'
									ELSE '---' END) AS status, pedidos, s.id_venta, s.tipo
						FROM
							com_sub_comandas s
						LEFT JOIN
								com_mesas m
							ON
								s.mesa = m.id_mesa
						LEFT JOIN
								accelog_usuarios u
							ON
								s.empleado = u.idempleado
						WHERE
							s.idpadre = " . $objeto['id_padre'] . " " . $condicion . "
						ORDER BY " . $orden;
		// return $sql;
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN listar_sub_comandas			------ ************ //////////////////

///////////////// ******** ---- 		listar_pedidos_sub_comanda		------ ************ //////////////////
//////// Obtien los pedidos de la sub comandas y los regresa en un array
	// Como parametros recibe:
	// id -> ID de la sub comanda
	// pedidos -> cadena con los ID de los pedidos

	function listar_pedidos_sub_comanda($objeto) {
		// Filtra por el ID si existe
		$condicion .= (!empty($objeto['id'])) ? ' AND s.id = ' . $objeto['id'] : '';
		// Filtra por los pedidos si existen
		$condicion .= (!empty($objeto['pedidos'])) ? ' AND p.id IN ( ' . $objeto['pedidos'] . ')' : '';

		// Ordena la consulta por los parametros indicados si existe, si no la ordena por id acendente
		$orden .= (!empty($objeto['orden'])) ? ' ' . $objeto['orden'] : ' p.id ASC';

		$sql = "SELECT 
					p.idProducto, p.cantidad, pro.nombre, p.id AS pedido, pro.id, 
					ROUND(pro.precio, 2) AS precioventa, p.sin, p.opcionales, p.adicionales, 
					pro.ruta_imagen AS imagen, s.codigo, s.persona
				FROM 
					com_pedidos p
				INNER JOIN 
						app_productos pro
					ON 
						pro.id = p.idproducto 
				LEFT JOIN 
						com_sub_comandas s 
					ON 
						s.idpadre = p.idcomanda
				LEFT JOIN 
						com_mesas m
					ON 
						m.id_mesa = s.mesa 
				WHERE 
					p.origen = 1 " . 
				$condicion . "
				ORDER BY " . $orden;
		// return $sql;
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 			FIN listar_sub_comandas			------ ************ //////////////////

///////////////// ******** ---- 				listar_utilidades			------ ************ //////////////////
//////// Obtien los registros de las utilidades y los carga en la div
	// Como parametros puede recibir:
		// btn -> boton del loading
		// div -> Div donde se cargara el contenido
		// empleado -> id del empleado
		// f_ini -> fecha y hora inicial
		// f_fin -> Fecha y hora final
		// grafica -> bandera que indica el filtrado de la grafica(1-> dia, 2-> semanda, 3->mes, 4-> año)
		// producto -> ID del producto o *-> todos los productos

	function listar_utilidades($objeto) {
	// Filtra por la sucursal si existe
		$condicion .= ($objeto['sucursal'] != '*' && !empty($objeto['sucursal'])) ? 
			' AND suc.idSuc = \'' . $objeto['sucursal'] . '\'' : '';
	// Si viene el id del empleado Filtra por empleado
		$condicion .= ($objeto['empleado'] != '*' && !empty($objeto['empleado'])) ? 
			' AND u.idempleado = \'' . $objeto['empleado'] . '\'' : '';
	// Filtra por producto si existe
		$condicion .= ($objeto['producto'] != '*' && !empty($objeto['producto'])) ? 
			' AND p.id = \'' . $objeto['producto'] . '\'' : '';
	// Se filtra por fecha de inicio y fin si estas existen
		$condicion .= (!empty($objeto['f_ini']) && !empty($objeto['f_fin'])) ? 
			' AND v.fecha BETWEEN \'' . $objeto['f_ini'] . '\' AND \'' . $objeto['f_fin'] . '\'' : '';
	// Se filtra por cliente
		$condicion .= ($objeto['cliente'] > 0) ? 
			' AND vent.idCliente='.$objeto['cliente'] : '';			
		
	// Agrupa la consulta por los parametros indicados si existe, si no la agrupa por id
		$agrupar .= (!empty($objeto['agrupar'])) ? ' GROUP BY ' . $objeto['agrupar'] : ' GROUP BY p.id';
		
	// Ordena la consulta por los parametros indicados si existe, si no los ordena por el lo mas popular y mayor ganancia
		$orden .= (!empty($objeto['orden'])) ? ' ORDER BY ' . $objeto['orden'] : ' ORDER BY rate DESC,  ganancia DESC';
		
		/*
			$sql = "SELECT
			p.id, 
			p.nombre, 
			ROUND(p.precio, 2) AS precio, 
			ROUND(IF(p.costo_servicio > 0, p.costo_servicio, pro.costo), 2) AS costo,
			SUM(v.cantidad) AS ventas, 
			(ROUND(p.precio, 2))*(SUM(v.cantidad)) AS venta_total,
			(ROUND(IF(p.costo_servicio > 0, p.costo_servicio, pro.costo), 2))*(SUM(v.cantidad)) AS costo_total,
			ROUND((ROUND(p.precio, 2))*(SUM(v.cantidad)) - (ROUND(IF(p.costo_servicio > 0, p.costo_servicio, pro.costo), 2)) * (SUM(v.cantidad)) , 2) AS ganancia,
			(ROUND(IF(p.costo_servicio > 0, p.costo_servicio, pro.costo), 2))*(SUM(v.cantidad)) AS costo_total,
			ROUND((ROUND((ROUND(p.precio, 2))*(SUM(v.cantidad)) - (ROUND(IF(p.costo_servicio > 0, p.costo_servicio, pro.costo), 2)) * (SUM(v.cantidad)) , 2) /
			(ROUND((IF(p.costo_servicio > 0, p.costo_servicio, pro.costo))*(SUM(v.cantidad)), 2))), 1) * 100 AS porcentaje,
			v.id_empleado, 
			nombreusuario AS empleado, 
			v.fecha, 
			suc.nombre AS sucursal, 
			f.rate
					FROM
						app_productos p
					LEFT JOIN
							app_campos_foodware f
						ON
							f.id_producto = p.id
					LEFT JOIN
							com_recetas r
						ON
							p.id = r.id
					LEFT JOIN
							app_costos_proveedor pro
						ON
							pro.id_producto = p.id
					LEFT JOIN
							app_inventario_movimientos v
						ON
							v.id_producto = p.id
					LEFT JOIN
							administracion_usuarios u
						ON
							u.idempleado = v.id_empleado
					LEFT JOIN
							mrp_sucursal suc
						ON
						 	suc.idSuc = u.idSuc
					LEFT JOIN
							app_pos_venta vent
						ON	
							vent.idVenta = SUBSTRING(v.referencia,6)		 	
					WHERE 
						(p.costo_servicio > 0 OR pro.costo > 0)
					AND
						tipo_traspaso = 0
					AND
						(tipo_producto != 3 && tipo_producto != 4) ".
					$condicion." ".
					$agrupar." ".
					$orden;
		*/
		$sql = "SELECT 
			v.id, 
			p.nombre, 
			ROUND(p.precio, 2) AS precio,
			ROUND(avg(v.costo), 2) costo, 
			sum(v.cantidad) ventas, 
			ROUND(sum(v.importe), 2) venta_total, 
			ROUND((avg(v.costo) * sum(v.cantidad)), 2) costo_total,
			ROUND( ROUND(sum(v.importe), 2) - ROUND((avg(v.costo) * sum(v.cantidad)), 2) )  ganancia,
			ROUND(((sum(v.importe) - (avg(v.costo) * sum(v.cantidad))) / (avg(v.costo) * sum(v.cantidad)) * 100), 2) porcentaje,
			v.id_empleado, 
			nombreusuario AS empleado, 
			v.fecha, 
			suc.nombre AS sucursal, 
			f.rate 
			FROM app_inventario_movimientos v
			left join app_productos p on p.id = v.id_producto
			LEFT JOIN administracion_usuarios u	ON u.idempleado = v.id_empleado
			LEFT JOIN mrp_sucursal suc ON suc.idSuc = u.idSuc
			LEFT JOIN app_pos_venta vent ON	 vent.idVenta = SUBSTRING(v.referencia,6)
			LEFT JOIN app_campos_foodware f ON f.id_producto = p.id
			WHERE 1 = 1 and v.referencia like '%Venta %' ".
			$condicion." ".
			$agrupar." ".
			$orden;
		 //return $sql;
		$result = $this -> queryArray($sql);

		return $result;
	}

	function listar_utilidades2($ini, $end,  $sucursal, $producto)
	{
		$sql = "SELECT	s.nombre sucursal, p.nombre producto, sum(cantidad) cantidad, sum(importe) importe, sum(costo) costo, sum(importe)-sum(costo) utilidad
				FROM	(
						SELECT	id_producto, 
								CASE WHEN (SUBSTRING_INDEX(referencia,' ',3) = 'Devolución de venta' ) THEN SUBSTRING_INDEX(SUBSTRING_INDEX(referencia,' ',4),' ',-1)
								ELSE (SUBSTRING_INDEX(referencia,' ',-1)) 
								END id_venta,
								CASE WHEN SUBSTRING_INDEX(referencia,' ',1) = 'Venta' THEN (cantidad*1)
								ELSE (cantidad*-1)
								END cantidad,
								CASE WHEN SUBSTRING_INDEX(referencia,' ',1) = 'Venta' THEN ((importe)*1)
								ELSE ((importe)*-1)
								END importe,
								CASE WHEN SUBSTRING_INDEX(referencia,' ',1) = 'Venta' THEN ((costo*cantidad)*1)
								ELSE ((costo*cantidad)*-1)
								END costo,
								CASE WHEN SUBSTRING_INDEX(referencia,' ',1) = 'Venta' THEN ((importe)-(costo*cantidad))
								ELSE ( ((importe)-(costo*cantidad))*-1 )
								END utilidad 
						FROM	app_inventario_movimientos 
						WHERE	SUBSTRING_INDEX(referencia,' ',1) = 'Venta' OR
								(SUBSTRING_INDEX(referencia,' ',2) = 'Cancelacion Venta') OR
								(SUBSTRING_INDEX(referencia,' ',3) = 'Devolución de venta' )
						) im
				INNER JOIN	app_pos_venta v ON im.id_venta = v.idVenta
				INNER JOIN	mrp_sucursal s ON v.idSucursal = s.idSuc
				INNER JOIN	app_productos p ON im.id_producto = p.id
				WHERE		(fecha BETWEEN '$ini' AND '$end 23:59:59') AND s.idSuc LIKE '%$sucursal%' AND im.id_producto LIKE '%$producto%'
				GROUP BY	s.idSuc, im.id_producto;";
		return  $this -> queryArray($sql);
	}

///////////////// ******** ---- 				FIN listar_utilidades		------ ************ //////////////////

///////////////// ******** ---- 				listar_productos_detalle			------ ************ //////////////////
	function listar_productos_detalle($objeto) {
	// Filtra por la sucursal si existe
		$condicion .= ($objeto['sucursal'] != '*' && !empty($objeto['sucursal'])) ? 
			' AND suc.idSuc = \'' . $objeto['sucursal'] . '\'' : '';
	// Si viene el id del empleado Filtra por empleado
		$condicion .= ($objeto['empleado'] != '*' && !empty($objeto['empleado'])) ? 
			' AND u.idempleado = \'' . $objeto['empleado'] . '\'' : '';

	// Filtra por departamento si existe
		$condicion .= ($objeto['departamento'] != '*' && !empty($objeto['departamento'])) ? ' AND de.id = \'' . $objeto['departamento'] . '\'' : '';
	// Se filtra por fecha de inicio y fin si estas existen
		$condicion .= (!empty($objeto['f_ini']) && !empty($objeto['f_fin'])) ? 
			' AND v.fecha BETWEEN \'' . $objeto['f_ini'] . '\' AND \'' . $objeto['f_fin'] . '\'' : '';
		
	// Agrupa la consulta por los parametros indicados si existe, si no la agrupa por id
		$agrupar .= (!empty($objeto['agrupar'])) ? ' GROUP BY ' . $objeto['agrupar'] : ' GROUP BY p.id';
		
	// Ordena la consulta por los parametros indicados si existe, si no los ordena por el lo mas popular y mayor ganancia
		$orden .= (!empty($objeto['orden'])) ? ' ORDER BY ' . $objeto['orden'] : ' ORDER BY rate DESC,  ganancia DESC';
		
		/*
		$sql = "SELECT
					p.id, p.nombre, ROUND(p.precio, 2) AS precio, 
					ROUND(IF(p.costo_servicio > 0, p.costo_servicio, pro.costo), 2) AS costo,
					COUNT(v.id) AS ventas, (ROUND(p.precio, 2))*(COUNT(v.id)) AS venta_total,
					(ROUND(IF(p.costo_servicio > 0, p.costo_servicio, pro.costo), 2))*(COUNT(v.id)) AS costo_total,
					ROUND((ROUND(p.precio, 2))*(COUNT(v.id)) - (ROUND(IF(p.costo_servicio > 0, p.costo_servicio, pro.costo), 2))
						*
						(COUNT(v.id))
					, 2) AS ganancia,
					(ROUND(IF(p.costo_servicio > 0, p.costo_servicio, pro.costo), 2))*(COUNT(v.id)) AS costo_total,
					ROUND((ROUND((ROUND(p.precio, 2))*(COUNT(v.id)) - (ROUND(IF(p.costo_servicio > 0, p.costo_servicio, pro.costo), 2))
						*
						(COUNT(v.id))
					, 2)
					/
					(ROUND((IF(p.costo_servicio > 0, p.costo_servicio, pro.costo))*(COUNT(v.id)), 2))), 1) * 100 AS porcentaje,
					v.id_empleado, nombreusuario AS empleado, v.fecha, suc.nombre AS sucursal, f.rate
				FROM
					app_productos p
				LEFT JOIN
						app_campos_foodware f
					ON
						f.id_producto = p.id
				LEFT JOIN
						com_recetas r
					ON
						p.id = r.id
				LEFT JOIN
						app_costos_proveedor pro
					ON
						pro.id_producto = p.id
				LEFT JOIN
						app_inventario_movimientos v
					ON
						v.id_producto = p.id
				LEFT JOIN
						administracion_usuarios u
					ON
						u.idempleado = v.id_empleado
				LEFT JOIN
						mrp_sucursal suc
					ON
					 	suc.idSuc = u.idSuc
				LEFT JOIN 
						app_departamento de 
					ON 
						de.id = p.departamento
				WHERE
					(p.costo_servicio > 0 OR pro.costo > 0)
				AND
					tipo_traspaso = 0
				AND
					(tipo_producto != 3 && tipo_producto != 4) ".
				$condicion." ".
				$agrupar." ".
				$orden;
		//return $sql;


		*/

		$sql = "SELECT v.id, p.nombre, 
				ROUND(p.precio, 2) AS precio, 
				ROUND(avg(v.costo), 2) costo,
				sum(v.cantidad) ventas,
				ROUND(sum(v.importe), 2) venta_total,  
				ROUND((avg(v.costo) * sum(v.cantidad)), 2) costo_total,
				ROUND( ROUND(sum(v.importe), 2) - ROUND((avg(v.costo) * sum(v.cantidad)), 2) ) ganancia,
				ROUND(((sum(v.importe) - (avg(v.costo) * sum(v.cantidad))) / (avg(v.costo) * sum(v.cantidad)) * 100), 2) porcentaje, 
				v.id_empleado, 
				nombreusuario AS empleado, 
				v.fecha, 
				suc.nombre AS sucursal, 
				f.rate 
				FROM app_inventario_movimientos v 
				left join app_productos p on p.id = v.id_producto 
				LEFT JOIN administracion_usuarios u ON u.idempleado = v.id_empleado 
				LEFT JOIN mrp_sucursal suc ON suc.idSuc = u.idSuc 
				LEFT JOIN app_pos_venta vent ON vent.idVenta = SUBSTRING(v.referencia,6) 
				LEFT JOIN app_campos_foodware f ON f.id_producto = p.id 
				LEFT JOIN app_departamento de ON de.id = p.departamento
				WHERE 1 = 1 ".
				$condicion." ".
				$agrupar." ".
				$orden;
				
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 				FIN listar_productos_detalle		------ ************ //////////////////

///////////////// ******** ---- 				getMesasQr			------ ************ //////////////////
//////// Consulta las sucursales y las regresa en un array
	// Como parametros recibe:
		// mesas -> mesas a buscar

	function getMesasQr($objeto) {
		$objeto['mesas'] = trim($objeto['mesas'], ',');

		$sql = "SELECT
					id_mesa, nombre
				FROM 
					com_mesas
				WHERE 
					id_mesa IN(".$objeto['mesas'].")";
		// return $sql;
		$result = $this -> queryArray($sql);
		return $result['rows'];
	}

///////////////// ******** ---- 			FIN getMesasQr			------ ************ //////////////////

///////////////// ******** ---- 				listar_sucursales			------ ************ //////////////////
//////// Consulta las sucursales y las regresa en un array
	// Como parametros recibe:
		// id -> id del empleado

	function listar_sucursales($objeto) {
	// Si viene el id del empleado Filtra por empleado
		$condicion .= (!empty($objeto['id'])) ? ' AND idSuc = \'' . $objeto['id'] . '\'' : '';

	// Ordena si existe, si no ordena por ID descendente
		$orden .= (!empty($objeto['orden'])) ? ' ' . $objeto['orden'] : ' idSuc DESC';

		$sql = "SELECT
					idSuc AS id, nombre
				FROM 
					mrp_sucursal
				WHERE
					1 = 1" . 
				$condicion . "
				ORDER BY " . 
					$orden;
		// return $sql;
		$result = $this -> queryArray($sql);
		return $result;
	}

///////////////// ******** ---- 			FIN listar_sucursales			------ ************ //////////////////

///////////////// ******** ---- 			eliminar_cliente				------ ************ //////////////////
//////// Elimina un cliente nuevo en la BD
	// Como parametros recibe:
		// id -> ID del cliente
		// btn -> Boton
		// tr -> TR de la tabla

	function eliminar_cliente($objeto) {
		$sql = "DELETE FROM
					comun_cliente
				WHERE
					id = ".$objeto['id'];
		$result = $this -> query($sql);

	// Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
	// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;
		$sql = "INSERT INTO
					com_actividades
						(id, empleado, accion, descripcion,  fecha)
				VALUES
					(''," . $usuario . ",'Elimina cliente','Elimina el cliente ".$datos['id']."', '" . $fecha . "')";
		$actividad = $this -> query($sql);

		return $result;
	}
///////////////// ******** ---- 			FIN eliminar_cliente			------ ************ //////////////////

///////////////// ******** ---- 				listar_kits					------ ************ //////////////////
//////// Consulta lOS KITS y las regresa en un array
	// Como parametros puede recibir:
		// id -> ID del producto
		// orden -> Orden de la consulta
			
	function listar_kits($objeto){
	// Filtra por el ID del producto si existe
		$condicion .= (!empty($objeto['id']))?' AND k.id = '.$objeto['id']:'';
	// Filtra por el status del kit
		$condicion.=(!empty($objeto['status']))?' AND k.status = '.$objeto['status']:' AND k.status = 1';
		
	// Ordena la consulta si existe
		$condicion .= (!empty($objeto['orden']))?' ORDER BY '.$objeto['orden']:' ORDER BY k.id DESC';
		
		$sql = "SELECT
					k.id AS id_kit, k.nombre, p.codigo, p.precio, k.dias, k.inicio, k.fin, 
					p.costo_servicio AS costo, p.ruta_imagen AS imagen, departamento AS id_departamento
				FROM
					com_kits k
				LEFT JOIN
						app_productos p
					ON
						p.id = k.id
				WHERE
					1 = 1 ".
				$condicion;
		// return $sql;
		$result = $this->queryArray($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 			FIN listar_kits					------ ************ //////////////////

///////////////// ******** ---- 				listar_productos			------ ************ //////////////////
//////// Consulta los productos y los regresa en un array
	// Como parametros puede recibir:
		// id -> ID del producto
		// tipo -> tipo de producto
		// orden -> orden de la consulta
			
	function listar_productos($objeto){
	// Filtra por el ID del producto si existe
		$condicion .= (!empty($objeto['id']))?' AND p.id = '.$objeto['id']:'';
	// Filtra por el ID del kit si existe
		$condicion .= (!empty($objeto['id_kit']))?' AND k.id_kit = '.$objeto['id_kit']:'';
	// Filtra por el ID del kit si existe
		$condicion .= (!empty($objeto['id_combo']))?' AND c.id_combo = '.$objeto['id_combo']:'';
	// Filtra por promocion si existe
		$condicion .= (!empty($objeto['id_promocion']))?' AND pro.id_promocion = '.$objeto['id_promocion']:'';
	// Filtra por tipo
		$condicion .= (!empty($objeto['tipo']))?' AND p.tipo_producto = '.$objeto['tipo']:'';
	// Ordena la consulta si existe
		$condicion .= (!empty($objeto['orden']))?' ORDER BY '.$objeto['orden']:'';
		
	// Agrupa la consulta si existe, default ID
		$condicion .= (!empty($objeto['agrupar']))?' GROUP BY '.$objeto['agrupar']:' GROUP BY p.id';
		
		session_start();
		$sucursal = "	SELECT 
							mp.idSuc AS id 
						FROM 
							administracion_usuarios au 
						INNER JOIN 
								mrp_sucursal mp 
							ON 
								mp.idSuc = au.idSuc 
						WHERE 
							au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
						LIMIT 1";
		$sucursal = $this -> queryArray($sucursal);
		$sucursal = $sucursal['rows'][0]['id'];
	
		$sql = "select * from app_producto_sucursal limit 1";
		$total = $this -> queryArray($sql);
		if($total['total'] > 0){
			$sql = "SELECT 
						p.id AS idProducto, p.nombre, p.costo_servicio AS costo, p.ruta_imagen AS imagen, 
						p.id_unidad_compra AS idunidadCompra, p.id_unidad_venta AS idunidad, 
						(SELECT
							nombre
						FROM
							app_unidades_medida uni
						WHERE
							uni.id=p.id_unidad_venta) AS unidad, u.factor, p.tipo_producto, 
						ROUND(p.precio, 2) AS precio, p.codigo, 
						IF(p.linea > 0, CONCAT('l-', p.linea), 
							IF(p.familia > 0, CONCAT('f-', p.familia), 
								IF(p.departamento > 0, CONCAT('d-', p.departamento), '#')
							)
						) AS parent, p.id AS id,  CONCAT(p.nombre, ' $', ROUND(p.precio, 2)) AS text, 'fa fa-cutlery' AS icon, 
						k.cantidad,
						IF((SELECT 
								COUNT(id)
							FROM
								app_producto_material
							WHERE
								id_producto = p.id) > 0, 1, 0) materiales, departamento AS id_departamento, c.grupo, c.cantidad_grupo,
							pro.recibir, pro.comprar,
							(CASE WHEN ids_normales LIKE CONCAT('%',(select ids_insumos from com_recetas where id = p.id),'%') THEN 1 ELSE 0 END) ins,
							(CASE WHEN ids_normales LIKE CONCAT('%',(select ids_insumos_preparados from com_recetas where id = p.id),'%') THEN 1 ELSE 0 END) inse,
							(CASE 
								WHEN GROUP_CONCAT(pm.opcionales) LIKE CONCAT('%1%') THEN 0
								WHEN GROUP_CONCAT(pm.opcionales) LIKE CONCAT('%2%') THEN 0 
								WHEN GROUP_CONCAT(pm.opcionales) LIKE CONCAT('%3%') THEN 0 
							ELSE 1 END) opc  
					FROM
						app_productos p
					LEFT JOIN
							app_campos_foodware f
						ON	
							p.id = f.id_producto
					LEFT JOIN
							com_promocionesXproductos pro
						ON
							pro.id_producto = p.id
					LEFT JOIN
							app_unidades_medida u
						ON
							u.id = p.id_unidad_compra
					LEFT JOIN 
							app_linea l
						ON 
							p.linea = l.id
					LEFT JOIN 
							app_familia fa
						ON 
							p.familia = fa.id
					LEFT JOIN 
							app_departamento d
						ON 
							p.departamento = d.id
					LEFT JOIN 
							com_kitsXproductos k
						ON 
							k.id_producto = p.id
					LEFT JOIN 
							com_combosXproductos c
						ON 
							c.id_producto = p.id
					LEFT JOIN com_recetas r ON r.id = p.id 
					LEFT JOIN app_producto_material pm ON pm.id_producto = r.id
					INNER JOIN app_producto_sucursal aps ON aps.id_producto = p.id AND aps.id_sucursal = ".$sucursal."
					WHERE
						p.status = 1 and p.tipo_producto != 3".
					$condicion;
		} else {
			$sql = "SELECT 
						p.id AS idProducto, p.nombre, p.costo_servicio AS costo, p.ruta_imagen AS imagen, 
						p.id_unidad_compra AS idunidadCompra, p.id_unidad_venta AS idunidad, 
						(SELECT
							nombre
						FROM
							app_unidades_medida uni
						WHERE
							uni.id=p.id_unidad_venta) AS unidad, u.factor, p.tipo_producto, 
						ROUND(p.precio, 2) AS precio, p.codigo, 
						IF(p.linea > 0, CONCAT('l-', p.linea), 
							IF(p.familia > 0, CONCAT('f-', p.familia), 
								IF(p.departamento > 0, CONCAT('d-', p.departamento), '#')
							)
						) AS parent, p.id AS id,  CONCAT(p.nombre, ' $', ROUND(p.precio, 2)) AS text, 'fa fa-cutlery' AS icon, 
						k.cantidad,
						IF((SELECT 
								COUNT(id)
							FROM
								app_producto_material
							WHERE
								id_producto = p.id) > 0, 1, 0) materiales, departamento AS id_departamento, c.grupo, c.cantidad_grupo,
							pro.recibir, pro.comprar,
							(CASE WHEN ids_normales LIKE CONCAT('%',(select ids_insumos from com_recetas where id = p.id),'%') THEN 1 ELSE 0 END) ins,
							(CASE WHEN ids_normales LIKE CONCAT('%',(select ids_insumos_preparados from com_recetas where id = p.id),'%') THEN 1 ELSE 0 END) inse,
							(CASE 
								WHEN GROUP_CONCAT(pm.opcionales) LIKE CONCAT('%1%') THEN 0
								WHEN GROUP_CONCAT(pm.opcionales) LIKE CONCAT('%2%') THEN 0 
								WHEN GROUP_CONCAT(pm.opcionales) LIKE CONCAT('%3%') THEN 0 
							ELSE 1 END) opc 
					FROM
						app_productos p
					LEFT JOIN
							app_campos_foodware f
						ON	
							p.id = f.id_producto
					LEFT JOIN
							com_promocionesXproductos pro
						ON
							pro.id_producto = p.id
					LEFT JOIN
							app_unidades_medida u
						ON
							u.id = p.id_unidad_compra
					LEFT JOIN 
							app_linea l
						ON 
							p.linea = l.id
					LEFT JOIN 
							app_familia fa
						ON 
							p.familia = fa.id
					LEFT JOIN 
							app_departamento d
						ON 
							p.departamento = d.id
					LEFT JOIN 
							com_kitsXproductos k
						ON 
							k.id_producto = p.id
					LEFT JOIN 
							com_combosXproductos c
						ON 
							c.id_producto = p.id
					LEFT JOIN com_recetas r ON r.id = p.id 
					LEFT JOIN app_producto_material pm ON pm.id_producto = r.id
					WHERE
						p.status = 1 and p.tipo_producto != 3".
					$condicion;
			}
		// return $sql;
		$result = $this->queryArray($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 			FIN listar_productos			------ ************ //////////////////

	function asignarRepM($idrep,$idcomanda){
		date_default_timezone_set('America/Mexico_City');
		$f_asignacion = date('Y-m-d H:i:s');
		$sql = "	INSERT INTO 
							com_bit_repartidores 
								(id, id_repartidor, id_comanda, fecha_pedido_asignado, estatus) 
						VALUES 
							('','$idrep','$idcomanda','" . $f_asignacion . "','1');";
		$asignacion = $this -> insert_id($sql);
		return $asignacion;
	}
	function verAsignadoM($idcomanda){
		$sql = "SELECT 	id_repartidor from com_bit_repartidores where id_comanda = $idcomanda;";
		$result = $this->queryArray($sql);
		return $result['rows'];
	}
	
	function listar_repartidores($objeto) {
	// Si viene el id del empleado Filtra por empleado
		$condicion .= (!empty($objeto['id'])) ? ' AND idempleado=\'' . $objeto['id'] . '\'' : '';
	// Elimina los administradores del listado
		$condicion .= (!empty($objeto['vista_empleados'])) ? ' AND idperfil!=2' : '';
	
	// Ordena la consulta por los parametros indicados si existe, si no la ordena por id Descendente
		$orden .= (!empty($objeto['orden'])) ? ' ' . $objeto['orden'] : ' nombreusuario ASC';
		if (!empty($objeto['sucursal'])) {
			$sucursal = $objeto['sucursal'];
		} else {
		session_start();
		$sucursal = "	SELECT 
							mp.idSuc AS id 
						FROM 
							administracion_usuarios au 
						INNER JOIN 
								mrp_sucursal mp 
							ON 
								mp.idSuc = au.idSuc 
						WHERE 
							au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
						LIMIT 1";
		$sucursal = $this -> queryArray($sucursal);
		$sucursal = $sucursal['rows'][0]['id'];
	}
		
		$sql = "SELECT
					idempleado AS id, nombreusuario AS usuario, permisos, asignacion
				FROM 
					administracion_usuarios u
				LEFT JOIN
						com_meseros m
					ON
						m.id_mesero = u.idempleado
				/*	SI SE REQUIERE LISTAR BASADO EN STATUS 					
				LEFT JOIN 
						com_bit_repartidores br 
					ON 
						br.id_repartidor = u.idempleado
				*/		
				WHERE
					idempleado != 'null'
				/* AND if(br.estatus = 1,1,0) = 0 and if(br.estatus = 2,1,0) = 0 */
				AND
					u.idSuc =  " . $sucursal . " " . 
				$condicion . "
				ORDER BY " . 
					$orden;
	// return $sql;
		$result = $this -> queryArray($sql);
		$result = $result['rows'];

		return $result;
	}
	

///////////////// ******** ---- 			guardar_pedido_kit				------ ************ //////////////////
//////// Guarda el pedido del kit
	// Como parametros puede recibir:
	
	function guardar_pedido_kit($objeto){
		$sql = "INSERT INTO
					com_pedidos_kit(id_pedido, id_comanda, id_producto, persona, status, opcionales, extras, 
									sin, nota_opcional, nota_extra, nota_sin)
				VALUES
					(".$objeto['id_pedido'].", ".$objeto['id_comanda'].", ".$objeto['idProducto'].", ".$objeto['persona'].", -1, 
					'".$objeto['opcionales']."', '".$objeto['extras']."', '".$objeto['sin']."', '".$objeto['nota_opcional']."',  
					'".$objeto['nota_extra']."',  '".$objeto['nota_sin']."')";
		// return $sql;
		$result = $this->query($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 			FIN guardar_pedido_kit			------ ************ //////////////////

///////////////// ******** ---- 			actualizar_inventario_kit		------ ************ //////////////////
//////// Descuenta del inventario los productos y sus insumos del kit
	// Como parametros puede recibir:
		// idproducto -> ID del kit
		// id -> ID del pedido
	
	function actualizar_inventario_kit($objeto){
		$sql = "SELECT 
					pe.*, p.tipo_producto
				FROM 
					com_pedidos_kit pe
				LEFT JOIN
						app_productos p
					ON
						p.id = pe.id_producto
				AND
					pe.status = -1
				WHERE
					id_pedido = " . $objeto['id'];
		// $result['pedidos']['result_opcionales'] = $sql;
		$pedidos = $this -> queryArray($sql);

		if (!empty($objeto['sucursal'])) {
			$almacen = "SELECT 
							a.id
						FROM 
							administracion_usuarios au
						LEFT JOIN 
								app_almacenes a
							ON 
								a.id_sucursal = au.idSuc
						WHERE 
							au.idSuc = " . $objeto['sucursal'] . " 
						AND 
							a.activo = 1
						LIMIT 1";
			$almacen = $this -> queryArray($almacen);
			$almacen = $almacen['rows'][0]['id'];
		} else {
			session_start();
		// Obtiene el almacen
			$almacen = "SELECT 
							a.id
						FROM 
							administracion_usuarios au
						LEFT JOIN 
								app_almacenes a
							ON 
								a.id_sucursal = au.idSuc
						WHERE 
							au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
						LIMIT 1";
			$almacen = $this -> queryArray($almacen);
			$almacen = $almacen['rows'][0]['id'];
		}
	// Valida que exista el almacen
		$almacen = (empty($almacen)) ? 1 : $almacen;

		$fecha = date('Y-m-d H:i:s');

	/* Actualiza el inventario
	=========================================================================== */

		foreach ($pedidos['rows'] as $key => $value) {
		// Filtra para que no se descuenten de inventario los opcionales(Con jitomate, lechuga, etc.)
			$condicion = (!empty($value['opcionales'])) ? " AND p.id NOT IN(" . $value['opcionales'] . ")" : "";

		// Obtiene los productos
			$sql = "SELECT
						p.id, m.cantidad, ROUND(p.precio, 2) AS precio, m.cantidad AS importe, p.formulaieps AS formula
					FROM
						app_productos p
					INNER JOIN
							app_producto_material m
						ON
							m.id_material = p.id
					WHERE
						m.id_producto = " . $value['id_producto'] . $condicion;
			// $result[$value['id_producto']]['result_opcionales'] = $sql;
			$opcionales = $this -> queryArray($sql);

		// Actualiza el inventario por cada producto
			foreach ($opcionales['rows'] as $k => $v) {
				$sql = "INSERT INTO
							app_inventario_movimientos
							(id_producto, cantidad, importe, id_almacen_origen, fecha, id_empleado,
							tipo_traspaso, costo, referencia)
						VALUES
							('" . $v['id'] . "', '" . $v['cantidad'] . "', '" . $v['importe'] . "', '" . $almacen . "', '" . $fecha . "', 
							'" . $_SESSION['accelog_idempleado'] . "', 0, '" . $v['importe'] . "', 
							'Kit " . $objeto['idproducto'] . "')";
				$result_opcional = $this -> query($sql);
			}

			if (!empty($value['extras'])) {
				$sql = "SELECT
							p.id, m.cantidad, ROUND(p.precio, 2) AS precio, m.cantidad AS importe, p.formulaieps AS formula
						FROM
							app_productos p
						INNER JOIN
								app_producto_material m
							ON
								m.id_material = p.id
						WHERE
							p.id IN(" . $value['extras'] . ")
						AND
							m.id_producto = " . $value['id_producto'];
			// $result[$value['id_producto']]['extras'] = $sql;
				$adicionales = $this -> queryArray($sql);

			// Actualiza el inventario por cada producto
				foreach ($adicionales['rows'] as $k => $v) {
					$sql = "INSERT INTO
								app_inventario_movimientos
									(id_producto, cantidad, importe, id_almacen_origen, fecha, id_empleado,
									tipo_traspaso, costo, referencia)
							VALUES
								('" . $v['id'] . "', '" . $v['cantidad'] . "', '" . $v['importe'] . "', '" . $almacen . "', '" . $fecha . "', 
								'" . $_SESSION['accelog_idempleado'] . "', 0, '" . $v['importe'] . "', 
								'Kit " . $objeto['idproducto'] . "')";
					$result_adicionales = $this -> query($sql);
				}
			}
		} //FIN foreach
		
	// Actuliza el estado del pedido para indicar que se dio de alta (status='0')
		$sql = "UPDATE 
					com_pedidos_kit 
				SET 
					status = '0' 
				WHERE 
					status = '-1' 
				AND 
					id_pedido = " . $objeto['id'];
		$result = $this -> query($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 		FIN actualizar_inventario_kit		------ ************ //////////////////

///////////////// ******** ---- 			agregar_comanda					------ ************ //////////////////
//////// Descuenta del inventario los productos y sus insumos del kit
	// Como parametros puede recibir:
		// idproducto -> ID del kit
		// id -> ID del pedido
	
	function agregar_comanda($objeto){
	// Inserta la comanda en la BD
		date_default_timezone_set('America/Mexico_City');
		$fecha = date('Y-m-d H:i:s');
		$sql = "INSERT INTO 
					com_comandas 
					(idmesa, personas, status, codigo, timestamp, abierta, idempleado) 
				VALUES 
					(".$objeto['id_mesa'].",'1','0','','" . $fecha . "','3','" . $_SESSION['accelog_idempleado'] . "')";
		$comanda = $this -> insert_id($sql);
		
		$size = 5 - strlen($comanda);
		$string = "";
	
		for ($i = 0; $i < $size; $i++)
			$string .= "0";

	// Guarda el codigo de la comanda
		$string .= $comanda;
		$sql = "UPDATE 
					com_comandas 
				SET 
					codigo = 'COM" . $string . "' 
				WHERE 
					id = " . $comanda;
		$this -> query($sql);
		
		return $comanda;
	}
	
///////////////// ******** ---- 			FIN agregar_comanda				------ ************ //////////////////

///////////////// ******** ---- 			actualizar_pedidos				------ ************ //////////////////
//////// Actualiza los pedidos
	// Como parametros puede recibir:
		// id_comanda-> ID de la comanda
		// id -> ID del pedido
		// complemento -> ID del producto
	
	function actualizar_pedido($objeto,$id_complemento){
		
		//print_r($objeto); exit();
	// Actualiza la comanda
		$campos .= (!empty($objeto['id_comanda'])) ? " idcomanda = " . $objeto['id_comanda'] : '';
	// Actualiza los complemento si existe
		$campos .= (!empty($objeto['complemento'])) ? " complementos = " . $objeto['complemento'] : '';
	// Actualiza los complemento a manera de string
		$campos .= (!empty($objeto['complemento_string'])) ? " complementos = '" . $objeto['complemento_string']. "'" : '';
	// Limpia el campo de los complementos
		$campos .= ($objeto['borrar_complemento'] == 1) ? " complementos = NULL" : '';

		
		if (!empty($objeto['complemento'])){
			$sql2='SELECT ((c.cantidad * cp.costo) * (cc.factor / bb.factor)) costoR FROM com_complementos c
				left join app_costos_proveedor cp on cp.id_producto = c.id_producto
				left join app_productos a on a.id = c.id_producto
				inner join app_unidades_medida bb on bb.id=a.id_unidad_compra
				inner join app_unidades_medida cc on cc.id=a.id_unidad_venta
				where c.id_producto = '.$id_complemento.' limit 1;';
				
			$result2 = $this -> queryArray($sql2);		
			$costo = $result2['rows'][0]['costoR'];
			$pedido = $objeto['id'];

			$sql3 = "UPDATE com_pedidos SET costo = costo + '$costo*1' WHERE id = '$pedido';";
			$this -> query($sql3);
		}
		
		
		if (!empty($objeto['borrar_complemento'])){
				$sql2='SELECT ((c.cantidad * cp.costo) * (cc.factor / bb.factor)) costoR FROM com_complementos c
				left join app_costos_proveedor cp on cp.id_producto = c.id_producto
				left join app_productos a on a.id = c.id_producto
				inner join app_unidades_medida bb on bb.id=a.id_unidad_compra
				inner join app_unidades_medida cc on cc.id=a.id_unidad_venta
				where c.id_producto = '.$id_complemento.' limit 1;';
			
			$result2 = $this -> queryArray($sql2);		
			$costo = $result2['rows'][0]['costoR'];
			$pedido = $objeto['id'];
			
			$sql3 = "UPDATE com_pedidos SET costo = costo - '$costo*1' WHERE id = '$pedido';";
			$this -> query($sql3);
			
		}
		

		$sql = "UPDATE
					com_pedidos
				SET
					".$campos."
				WHERE
					id = ".$objeto['id'];
		$result = $this -> query($sql);
		
		return $result;
	}
	
///////////////// ******** ---- 			FIN actualizar_pedidos			------ ************ //////////////////

///////////////// ******** ---- 			listar_pedidos_persona			------ ************ //////////////////
//////// Obtiene los pedidos de la persona y los regresa en un array
	// Como parametros puede recibir:
	// 	$person -> numero de persona
	//	$comanda -> id de la comanda

	function listar_pedidos_persona($objeto) {
		$sql = "SELECT 
					a.id, a.idproducto, SUM(a.cantidad) AS cantidad, b.nombre, 
					ROUND(b.precio, 2) AS precio, opcionales, adicionales, sin, a.status, a.complementos, a.id_promocion,
					(CASE a.id_promocion 
						WHEN 0 THEN
							'producto'
						ELSE a.id END) as tipin
				FROM 
					com_pedidos a 
				LEFT JOIN 
						app_productos b 
					ON 
						b.id = a.idproducto
				WHERE 
					a.dependencia_promocion = 0 
				AND
					cantidad > 0
				AND
					origen = 1
				AND 
					a.npersona = ".$objeto['persona']."
				AND 
					a.idcomanda = " . $objeto['id_comanda'] . " 
				GROUP BY 
						a.id, tipin, status, a.idproducto, a.opcionales, a.adicionales, a.complementos";
		$result = $this -> queryArray($sql);
		//print_r($sql);
		return $result;
	}
///////////////// ******** ---- 		FIN listar_pedidos_persona			------ ************ //////////////////

///////////////// ******** ---- 			listar_pedidos					------ ************ //////////////////
//////// Obtiene los pedidos de la comanda y los regresa en un array
	// Como parametros puede recibir:
	// 	$person -> numero de persona
	//	$comanda -> id de la comanda

	function listar_pedidos($objeto) {
		$sql = "SELECT 
					a.id, a.idproducto, SUM(a.cantidad) AS cantidad, b.nombre, 
					ROUND(b.precio, 2) AS precio, opcionales, adicionales, sin, a.status
				FROM 
					com_pedidos a 
				INNER JOIN 
						app_productos b 
					ON 
						b.id = a.idproducto 
				WHERE 
					a.idproducto != 0
				AND
					a.origen = 1
				AND 
					a.idcomanda = " . $objeto['id_comanda'] . " 
				GROUP BY 
					a.id";
		$result = $this -> queryArray($sql);
		
		return $result;
	}
///////////////// ******** ---- 		FIN listar_pedidos					------ ************ //////////////////

///////////////// ******** ---- 			agregar_via_contacto			------ ************ //////////////////
//////// Agrega una via de contacto, esconde la modal, actualiza el select y selecciona la nueva opcion
	// Como parametros recibe:
		// nombre -> Nombre de la nueva via de contacto

	function agregar_via_contacto($objeto) {
	// Anti hack
		foreach ($objeto as $key => $value) {
			$datos[$key] = $this -> escapalog($value);
		}
		
		$sql = "INSERT INTO
					com_vias_contacto(nombre)
				VALUE
					('".$datos['nombre']."')";
		$result = $this -> insert_id($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN agregar_via_contacto			------ ************ //////////////////

///////////////// ******** ---- 			agregar_zona_reparto			------ ************ //////////////////
//////// Agrega una via de contacto, esconde la modal, actualiza el select y selecciona la nueva opcion
	// Como parametros recibe:
		// nombre -> Nombre de la nueva via de contacto

	function agregar_zona_reparto($objeto) {
	// Anti hack
		foreach ($objeto as $key => $value) {
			$datos[$key] = $this -> escapalog($value);
		}
		
		$sql = "INSERT INTO
					com_zonas_reparto(nombre)
				VALUE
					('".$datos['nombre']."')";
		$result = $this -> insert_id($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN agregar_zona_reparto			------ ************ //////////////////

///////////////// ******** ---- 			listar_vias_contacto			------ ************ //////////////////
//////// Consulta los datos de las vias de contacto en la BD
	// Como parametros puede recibir:

	function listar_vias_contacto($objet) {
		$sql = "SELECT 
					* 
				FROM 
					com_vias_contacto";
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN listar_vias_contacto			------ ************ //////////////////

///////////////// ******** ---- 			listar_zonas_reparto			------ ************ //////////////////
//////// Consulta los datos de las vias de contacto en la BD
	// Como parametros puede recibir:

	function listar_zonas_reparto($objet) {
		$sql = "SELECT 
					* 
				FROM 
					com_zonas_reparto";
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN listar_zonas_reparto			------ ************ //////////////////

///////////////// ******** ---- 			actualizar_mesa					------ ************ //////////////////
//////// Actualiza los campos de la mesa en la BD
	// Como parametros recibe:
		// id -> ID de la mesa
		// notificacion -> status de la notificacion

	function actualizar_mesa($objeto) {
	// Actualiza el status de la notificacion si viene
		$campos .= (!empty($objeto['notificacion'])) ? " notificacion = " . $objeto['notificacion'] : '';
	// Actualiza el empleado si viene
		$campos .= (!empty($objeto['id_mesero'])) ? " idempleado = " . $objeto['id_mesero'] : '';

		$sql = "UPDATE
					com_mesas
				SET  
					" . $campos . "
				WHERE

					id_mesa = " . $objeto['id_mesa'];
		// return $sql;
		$result = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 			FIN actualizar_mesa				------ ************ //////////////////

///////////////// ******** ---- 		actualizar_tiempo_pedidos			------ ************ //////////////////
//////// Actualiza el tiempo de los pedidos
	// Como parametros puede recibir:
		// id_comanda -> ID de la comanda
		// tiempo -> Tiempo del platillo
		
	function actualizar_tiempo_pedidos($objeto) {
	// Actualiza el status de la notificacion si viene
		$campos .= (!empty($objeto['tiempo'])) ? " tiempo = " . $objeto['tiempo'] : '';

		$sql = "UPDATE
					com_pedidos
				SET  
					" . $campos . "
				WHERE
					idcomanda = " . $objeto['id_comanda']."
				AND
					tiempo = 0";
		// return $sql;
		$result = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN actualizar_tiempo_pedidos		------ ************ //////////////////

///////////////// ******** ---- 			editCliente					------ ************ //////////////////
//////// Actualiza los campos de la mesa en la BD
	// Como parametros recibe:
		// id -> ID de la mesa
		// notificacion -> status de la notificacion

	function editCliente($objeto) {
	// Actualiza el status de la notificacion si viene
		

		//$sql = "UPDATE comun_cliente SET nombre = '".$vars['nombre']."', direccion = '".$vars['direccion']."', celular = '".$vars['celular']."',  WHERE id = ".$vars['idrec'];
		// return $sql;
		//$result = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 			FIN editCliente				------ ************ //////////////////

///////////////// ******** ---- 			listar_promociones				------ ************ //////////////////
//////// Consulta lOS comboS y las regresa en un array
	// Como parametros puede recibir:
		// id -> ID del producto
		// orden -> Orden de la consulta
			
	function listar_promociones($objeto){

			session_start();
		/// CONDICION PARA PROMOCIONES POR SUCURSAL 
			$condicion2 = '';			
			$sucursal = "	SELECT mp.idSuc AS id 
							FROM administracion_usuarios au 
							INNER JOIN mrp_sucursal mp ON mp.idSuc = au.idSuc 
							WHERE au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
							LIMIT 1";
			$sucursal = $this -> queryArray($sucursal);
			$sucursal = $sucursal['rows'][0]['id'];

			// Filtra por el ID de la sucursal si existe
			$condicion2 = (!empty($sucursal))?' and idsuc like "%'.$sucursal.'%"':'';
		/// CONDICION PARA PROMOCIONES POR SUCURSAL  FIN
	

	// Filtra por el ID del producto si existe
		$condicion .= (!empty($objeto['id']))?' AND id = '.$objeto['id']:'';
	// Filtra por el status del combo
		$condicion.=(!empty($objeto['status']))?' AND status = '.$objeto['status']:' AND status = 1';
	// Filtra por nombre si existe si se indica
		$condicion .= (!empty($objeto['texto'])) ? ' AND nombre LIKE \'%' . $objeto['texto'] . '%\'' : '';
	// Ordena la consulta si existe
		$condicion .= (!empty($objeto['orden']))?' ORDER BY '.$objeto['orden']:' ORDER BY id DESC';
		
		$sql = "SELECT id AS id_promocion, nombre, tipo, cantidad, cantidad_descuento, inicio, fin, precio_fijo FROM com_promociones
				WHERE
					1 = 1 ".$condicion2."
				AND 
					dias like '%".$objeto['day']."%' 
				AND '".$objeto['hour']."' between inicio and fin ".
				$condicion; 
				//print_r($sql);
		// return $sql;
		$result = $this->queryArray($sql);

		//print_r($sql);
		return $result;
	}
		
///////////////// ******** ---- 			FIN listar_promociones			------ ************ //////////////////

///////////////// ******** ---- 			listar_combos				------ ************ //////////////////
//////// Consulta lOS comboS y las regresa en un array
	// Como parametros puede recibir:
		// id -> ID del producto
		// orden -> Orden de la consulta
			
	function listar_combos($objeto){
		session_start();

		/// CONDICION PARA PROMOCIONES POR SUCURSAL 
			$condicion2 = '';			
			$sucursal = "	SELECT mp.idSuc AS id 
							FROM administracion_usuarios au 
							INNER JOIN mrp_sucursal mp ON mp.idSuc = au.idSuc 
							WHERE au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
							LIMIT 1";
			$sucursal = $this -> queryArray($sucursal);
			$sucursal = $sucursal['rows'][0]['id'];

			// Filtra por el ID de la sucursal si existe
			$condicion2 = (!empty($sucursal))?' and idsuc like "%'.$sucursal.'%"':'';
		/// CONDICION PARA PROMOCIONES POR SUCURSAL  FIN

		$sucursal = "	SELECT 
							mp.idSuc AS id 
						FROM 
							administracion_usuarios au 
						INNER JOIN 
								mrp_sucursal mp 
							ON 
								mp.idSuc = au.idSuc 
						WHERE 
							au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
						LIMIT 1";
		$sucursal = $this -> queryArray($sucursal);
		$sucursal = $sucursal['rows'][0]['id'];
	
		$sql = "select * from app_producto_sucursal limit 1";
		$total = $this -> queryArray($sql);
		if($total['total'] > 0){
			// Filtra por el ID del producto si existe
			$condicion .= (!empty($objeto['id']))?' AND c.id = '.$objeto['id']:'';
		// Filtra por el status del combo
			$condicion.=(!empty($objeto['status']))?' AND c.status = '.$objeto['status']:' AND c.status = 1';
			
		// Ordena la consulta si existe
			$condicion .= (!empty($objeto['orden']))?' ORDER BY '.$objeto['orden']:' ORDER BY c.id DESC';
			
			$sql = "SELECT
						c.id AS id_combo, c.nombre, p.codigo, p.precio, c.dias, c.inicio, c.fin, 
						p.costo_servicio AS costo, p.ruta_imagen AS imagen, departamento AS id_departamento
					FROM
						com_combos c
					LEFT JOIN
							app_productos p
						ON
							p.id = c.id
					INNER JOIN app_producto_sucursal aps ON aps.id_producto = p.id AND aps.id_sucursal = ".$sucursal."
					WHERE
						1 = 1 
					AND 
						dias like '%".$objeto['day']."%' 
					AND '".$objeto['hour']."' between inicio and fin ".
					$condicion;
		} else {
		// Filtra por el ID del producto si existe
			$condicion .= (!empty($objeto['id']))?' AND c.id = '.$objeto['id']:'';
		// Filtra por el status del combo
			$condicion.=(!empty($objeto['status']))?' AND c.status = '.$objeto['status']:' AND c.status = 1';
			
		// Ordena la consulta si existe
			$condicion .= (!empty($objeto['orden']))?' ORDER BY '.$objeto['orden']:' ORDER BY c.id DESC';
			
			$sql = "SELECT
						c.id AS id_combo, c.nombre, p.codigo, p.precio, c.dias, c.inicio, c.fin, 
						p.costo_servicio AS costo, p.ruta_imagen AS imagen, departamento AS id_departamento
					FROM
						com_combos c
					LEFT JOIN
							app_productos p
						ON
							p.id = c.id
					WHERE
						1 = 1 ".$condicion2." 
					AND 
						dias like '%".$objeto['day']."%' 
					AND '".$objeto['hour']."' between inicio and fin ".
					$condicion;
			// return $sql;
			}
		$result = $this->queryArray($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 			FIN listar_combos			------ ************ //////////////////

///////////////// ******** ---- 			guardar_pedido_combo		------ ************ //////////////////
//////// Guarda el pedido del combo
	// Como parametros puede recibir:
		// id_pedido -> ID del pedido
		// id_comanda -> ID de la comanda
		// idProducto -> ID del producto
		// persona -> Persona seleccionada
		// opcionales -> String con los IDs de los opcionales
		// extras -> String con los IDs de los extras
		// sin -> String con los IDs de los sin
		// nota_extra -> Nota de los extras
		// nota_sin -> Nota de los sin
		
	function guardar_pedido_combo($objeto){		

		/// COSTO
			$costoF = $opc2 = $ext2 = $sin2 = $nor2 = 0;
			$extras = $objeto['extras'];
			$opcionales = $objeto['opcionales'];
			$extras = $objeto['extras'];
			$sin = $objeto['sin'];
			$idproduct = $objeto['id_producto'];

			// SQL PARA TIPO DE PRODUCTO
				$sqlp = "SELECT tipo_producto FROM app_productos where id = '$idproduct';";
				$resultp = $this -> queryArray($sqlp);
				$tipo = $resultp['rows'][0]['tipo_producto'];		
			// SQL PARA TIPO DE PRODUCTO FIN

			// SQL PARA OBTENER COSTO
				if($tipo == 1){// PRODUCTO
					$sql3 = "SELECT costo from app_costos_proveedor where id_producto = '$idproduct' order by fecha desc limit 1;";
					$result3 = $this -> queryArray($sql3);
					$costoF = $result3['rows'][0]['costo'];
				
					if($costoF == ''){
						$sql4 = "SELECT costo_servicio from app_productos where id = '$idproduct';";
						$result4 = $this -> queryArray($sql4);
						$costoF = $result4['rows'][0]['costo_servicio'];
					}

				}else if($tipo == 5){ // RECETA

					// COSTO NORMAL  
						$sqlr = 'SELECT costo_receta from com_recetas where id = '.$idproduct.';';
						$resultr = $this -> queryArray($sqlr);
						$nor2 = $resultr['rows'][0]['costo_receta'];
					// COSTO NORMAL FIN

					// SQL NECESARIOS DE RECETAS
						$sqls = 'SELECT ids_insumos, proveedores_insumos, ids_insumos_preparados from com_recetas where id = '.$idproduct.';';
						$results = $this -> queryArray($sqls);
						$insumos = $results['rows'][0]['ids_insumos'];
						$proveed = $results['rows'][0]['proveedores_insumos'];
						$insumosp = $results['rows'][0]['ids_insumos_preparados'];
						
						$insumosR = explode(',', $insumos);
						$insumospR = explode(',', $insumosp); 

						$proveedR = explode(',', $proveed);													

						$extrasR = explode(',', $extras);
						$opcionalesR = explode(',', $opcionales);
						$sinR = explode(',', $sin);
					// SQL NECESARIOS DE RECETAS FIN
						
					// COSTO EXTRA
						$new = array_intersect($insumosR, $extrasR); // insumos normales
						$new2 = array_intersect($insumospR, $extrasR); // insumos preparados
						// extras insumos normales
						
						foreach ($new as $key => $value) {
							if(!empty($value)){								
								foreach ($proveedR as $k => $v) {
									if($key == $k){


										$sqlf = "SELECT (c.factor / b.factor) factor from app_productos a 
					                            inner join app_unidades_medida b on b.id=a.id_unidad_compra
					                            inner join app_unidades_medida c on c.id=a.id_unidad_venta
					                            where a.id='$value';";
										$resultf = $this -> queryArray($sqlf);
										$factor = $resultf['rows'][0]['factor'];
										
										$sql2 = 'SELECT cantidad from app_producto_material pm where id_producto = '.$idproduct.' and id_material = '.$value.';'; 											
							        	$result2 = $this -> queryArray($sql2);
										$cantidad = $result2['rows'][0]['cantidad'];

										$cantidad = $cantidad * $factor;

										//echo $cantidad.' = '.$cantidad.' * '.$factor;

										$sql = 'SELECT costo from app_costos_proveedor where id_producto = '.$value.' and id_proveedor = '.$v.';';
							        	$result = $this -> queryArray($sql);
										$costo = ($result['rows'][0]['costo']) * ($cantidad*1);									
							        	$ext2 += $costo;

							        	//echo 'costo '.$result['rows'][0]['costo'].' * '.$cantidad;


										$arrayex[]=array(
							                    idproducto   => $value,
							                    idproveedor  => $v,
							                    costo        => $costo,
							                    cantidad     => $cantidad,
							                    sumcosto     => $ext2
							            );
							        
									}
								}
							}
						}
						// extras insumos normales fin
						// extras insumos preparados
						foreach ($new2 as $key2 => $value2) {
							if($value2 != ''){

								$sqlf = "SELECT (c.factor / b.factor) factor from app_productos a 
			                            inner join app_unidades_medida b on b.id=a.id_unidad_compra
			                            inner join app_unidades_medida c on c.id=a.id_unidad_venta
			                            where a.id='$value2';";
								$resultf = $this -> queryArray($sqlf);
								$factor = $resultf['rows'][0]['factor'];

								$sql2 = 'SELECT cantidad from app_producto_material pm where id_producto = '.$idproduct.' and id_material = '.$value2.';'; 											
					        	$result2 = $this -> queryArray($sql2);
								$cantidad = $result2['rows'][0]['cantidad'];

								$cantidad = $cantidad * $factor;

								$sqlp = 'SELECT costo_servicio FROM app_productos where id = '.$value2.';'; 
								$resultp = $this -> queryArray($sqlp);
								$costop = ($resultp['rows'][0]['costo_servicio'])*$cantidad;									
						        $ext2 += $costop;
							}
						}
						// extras insumos preparados fin
					// COSTO EXTRA FIN

					// COSTO ADICIONAL 
						$newo = array_intersect($insumosR, $opcionalesR); // insumos normales
						$newo2 = array_intersect($insumospR, $opcionalesR); // insumos preparados
						// adicionales insumos normales
						foreach ($newo as $key => $value) {
							if(!empty($value)){
								foreach ($proveedR as $k => $v) {
									if($key == $k){

										$sqlf = "SELECT (c.factor / b.factor) factor from app_productos a 
					                            inner join app_unidades_medida b on b.id=a.id_unidad_compra
					                            inner join app_unidades_medida c on c.id=a.id_unidad_venta
					                            where a.id='$value';";
										$resultf = $this -> queryArray($sqlf);
										$factor = $resultf['rows'][0]['factor'];
										
										$sql2 = 'SELECT cantidad from app_producto_material pm where id_producto = '.$idproduct.' and id_material = '.$value.';';										
							        	$result2 = $this -> queryArray($sql2);
										$cantidad = $result2['rows'][0]['cantidad'];

										$cantidad = $cantidad * $factor;

										$sql = 'SELECT costo from app_costos_proveedor where id_producto = '.$value.' and id_proveedor = '.$v.';';
							        	$result = $this -> queryArray($sql);
										$costo = ($result['rows'][0]['costo']) * ($cantidad*1);									
							        	$opc2 += $costo;
							        
									}
								}
							}
						}
						// adicionales insumos normales fin
						// adicionales insumos preparados
						foreach ($newo2 as $key2 => $value2) {
							if($value2 != ''){

								$sqlf = "SELECT (c.factor / b.factor) factor from app_productos a 
			                            inner join app_unidades_medida b on b.id=a.id_unidad_compra
			                            inner join app_unidades_medida c on c.id=a.id_unidad_venta
			                            where a.id='$value2';";
								$resultf = $this -> queryArray($sqlf);
								$factor = $resultf['rows'][0]['factor'];

								$sql2 = 'SELECT cantidad from app_producto_material pm where id_producto = '.$idproduct.' and id_material = '.$value2.';'; 											
					        	$result2 = $this -> queryArray($sql2);
								$cantidad = $result2['rows'][0]['cantidad'];

								$cantidad = $cantidad * $factor;

								$sqlp = 'SELECT costo_servicio FROM app_productos where id = '.$value2.';'; 
								$resultp = $this -> queryArray($sqlp);
								$costop = ($resultp['rows'][0]['costo_servicio'])*$cantidad;									
						        $opc2 += $costop;
							}
						}
						// adicionales insumos preparados fin						
					// COSTO ADICIONAL FIN

					// COSTO SIN
						$news = array_intersect($insumosR, $sinR); // insumos normales
						$news2 = array_intersect($insumospR, $sinR); // insumos preparados
						// adicionales insumos normales


						foreach ($news as $key => $value) {
							if(!empty($value)){
								foreach ($proveedR as $k => $v) {
									if($key == $k){


										$sqlf = "SELECT (c.factor / b.factor) factor from app_productos a 
					                            inner join app_unidades_medida b on b.id=a.id_unidad_compra
					                            inner join app_unidades_medida c on c.id=a.id_unidad_venta
					                            where a.id='$value';";
										$resultf = $this -> queryArray($sqlf);
										$factor = $resultf['rows'][0]['factor'];
										
										$sql2 = 'SELECT cantidad from app_producto_material pm where id_producto = '.$idproduct.' and id_material = '.$value.';';
										/*
										$sql2 = 'SELECT (cantidad / (select factor from app_unidades_medida where unidad_base = 9))cantidad from app_producto_material pm
												left join app_unidades_medida um on um .id = id_unidad where id_producto = '.$idproduct.' and id_material = '.$value.';';
										*/

							        	$result2 = $this -> queryArray($sql2);
										$cantidad = $result2['rows'][0]['cantidad'];

										$cantidad = $cantidad * $factor;

										$sql = 'SELECT costo from app_costos_proveedor where id_producto = '.$value.' and id_proveedor = '.$v.';';
							        	$result = $this -> queryArray($sql);
										$costo = ($result['rows'][0]['costo']) * ($cantidad*1);									
							        	$sin2 += $costo;
							        
									}
								}
							}
						}
						// adicionales insumos normales fin		
						// adicionales insumos preparados
						foreach ($news2 as $key2 => $value2) {
							if($value2 != ''){

								$sqlf = "SELECT (c.factor / b.factor) factor from app_productos a 
			                            inner join app_unidades_medida b on b.id=a.id_unidad_compra
			                            inner join app_unidades_medida c on c.id=a.id_unidad_venta
			                            where a.id='$value2';";
								$resultf = $this -> queryArray($sqlf);
								$factor = $resultf['rows'][0]['factor'];

								$sql2 = 'SELECT cantidad from app_producto_material pm where id_producto = '.$idproduct.' and id_material = '.$value2.';'; 											
					        	$result2 = $this -> queryArray($sql2);
								$cantidad = $result2['rows'][0]['cantidad'];

								$cantidad = $cantidad * $factor;

								$sqlp = 'SELECT costo_servicio FROM app_productos where id = '.$value2.';'; 
								$resultp = $this -> queryArray($sqlp);
								$costop = ($resultp['rows'][0]['costo_servicio'])*$cantidad;									
						        $sin2 += $costop;
							}
						}
						// adicionales insumos preparados fin				
					// COSTO SIN FIN

					// COSTO FINAL					
						$costoF = ($nor2*1) + ($ext2*1) + ($opc2*1) - ($sin2*1);
					// COSTO FINAL FIN

				}
			// SQL PARA OBTENER COSTO FIN

			// SQL PARA ACTUALIZAR COSTO DEL COMBO
				$sqlc = "UPDATE com_pedidos SET costo = costo + ".$costoF ." WHERE id = ".$objeto['id_pedido'].";";
				$this -> queryArray($sqlc);
			// SQL PARA ACTUALIZAR COSTO DEL COMBO

		/// COSTO FIN
		
		$sql = "INSERT INTO
					com_pedidos_combo(id_pedido, id_comanda, id_producto, persona, status, opcionales, extras, 
									sin, nota_opcional, nota_extra, nota_sin, cantidad_pedidos,costo)
				VALUES
					(".$objeto['id_pedido'].", ".$objeto['id_comanda'].", ".$objeto['id_producto'].", ".$objeto['persona'].", -1, 
					'".$objeto['opcionales']."', '".$objeto['extras']."', '".$objeto['sin']."', '".$objeto['nota_opcional']."',  
					'".$objeto['nota_extra']."',  '".$objeto['nota_sin']."',  '1',".$costoF.")";

		// return $sql;
		$result = $this->query($sql);
		return $result;
	}
	
///////////////// ******** ---- 		FIN guardar_pedido_combo		------ ************ //////////////////

///////////////// ******** ---- 			guardar_pedido_promociones		------ ************ //////////////////
//////// Guarda el pedido del combo
	// Como parametros puede recibir:
		// id_pedido -> ID del pedido
		// id_comanda -> ID de la comanda
		// idProducto -> ID del producto
		// persona -> Persona seleccionada
		// opcionales -> String con los IDs de los opcionales
		// extras -> String con los IDs de los extras
		// sin -> String con los IDs de los sin
		// nota_extra -> Nota de los extras
		// nota_sin -> Nota de los sin
		
	function guardar_pedido_promociones($objeto){
			$idproduct = $objeto['id_producto']; 
			$idperson = $objeto['persona'];
			$idcomanda = $objeto['id_comanda'];
			$opcionales = $objeto['opcionales'];
			$extras  = $objeto['extras'];
			$sin  = $objeto['sin'];
			$iddep = $objeto['departamento'];
			$nota_opcional = $objeto['nota_opcional'];
			$nota_extra = $objeto['nota_extra'];
			$nota_sin = $objeto['nota_sin'];
			$id_promocion = $objeto['promocion'];
			$dependencia_promocion = $objeto['dependencia_promocion'];
			$queryproduct = 'SELECT 
								tipo_producto,
								ROUND(b.precio, 2) AS precioventa
							FROM 
								app_productos b
							WHERE 
								id = ' . $idproduct;
			$tipro = $this -> query($queryproduct);
			$row = $tipro -> fetch_array();
			$tipo_producto = $row['tipo_producto'];
			$precio = $row['precioventa'];

		/* Impuestos del producto
		============================================================================= */

			$impuestos_comanda = 0;
			$objeto['id'] = $idproduct;
			$impuestos = $this -> listar_impuestos($objeto);

			if ($impuestos['total'] > 0) {
				foreach ($impuestos['rows'] as $k => $v) {
					if ($v["clave"] == 'IEPS') {
						$producto_impuesto = $ieps = (($precio) * $v["valor"] / 100);
					} else {
						if ($ieps != 0) {
							$producto_impuesto = ((($precio + $ieps)) * $v["valor"] / 100);
						} else {
							$producto_impuesto = (($precio) * $v["valor"] / 100);
						}
					}

				// Precio actualizado
					$precio += $producto_impuesto;
					$precio = round($precio, 2);

					$impuestos_comanda += $producto_impuesto;
				}
			}

		/* FIN Impuestos del producto
		============================================================================= */

		// Obtiene los costos de los productos extra si existen
			if (!empty($extras)) {
				$sql = 'SELECT 
							ROUND(b.precio, 2) AS precioventa, id
						FROM 
							app_productos b
						WHERE
							id in(' . $extras . ')';
				$precios_extra = $this -> queryArray($sql);

			// Recorre los costos y los agrega al precio
				foreach ($precios_extra['rows'] as $key => $value) {
				/* Impuestos del producto
				============================================================================= */

					$objeto['id'] = $value['id'];
					$impuestos = $this -> listar_impuestos($objeto);
					if ($impuestos['total'] > 0) {
						foreach ($impuestos['rows'] as $k => $v) {
							if ($v["clave"] == 'IEPS') {
								$producto_impuesto = $ieps = (($value['precioventa']) * $v["valor"] / 100);
							} else {
								if ($ieps != 0) {
									$producto_impuesto = ((($value['precioventa'] + $ieps)) * $v["valor"] / 100);
								} else {
									$producto_impuesto = (($value['precioventa']) * $v["valor"] / 100);
								}
							}

						// Precio actualizado
							$precio += $producto_impuesto + $value['precioventa'];
							$precio = round($precio, 2);

							$impuestos_comanda += $producto_impuesto;
						}
					}

				/* FIN Impuestos del producto
				============================================================================= */
				}
			}

		// Actualiza el total y los impuestos de la comanda
			/*
			$sql = 'UPDATE 
						com_comandas
					SET 
						total = total + ' . $precio . '
					WHERE 
							id = ' . $idcomanda;
			$precio = $this -> query($sql);
			*/

		/* Guarda la actividad
		============================================================================= */

			$fecha = date('Y-m-d H:i:s');
		// Valida que exista el empleado si no agrega un cero como id
			$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;
			$sql = "	INSERT INTO
							com_actividades
								(id, empleado, accion, fecha)
						VALUES
							(''," . $usuario . ",'Agrega producto', '" . $fecha . "')";
			$actividad = $this -> query($sql);

		/* FIN Guarda la actividad
		============================================================================= */

		// Aumenta el rating del producto
			$sql = "UPDATE
						app_campos_foodware
					SET
						rate = rate + 1
					WHERE
						id_producto = " . $idproduct;
			$rate = $this -> query($sql);

		// Si es Producir producto obtiene los materiales
			if ($tipo_producto == 5) {
				$querycompuesto = '	SELECT 
										p.id, p.id_producto AS idProducto, p.cantidad, p.id_unidad AS idUnidad, 
										p.id_material AS idMaterial, l.nombre Nom
									FROM 
										app_producto_material p
									INNER JOIN 
											app_productos l 
										ON 
											p.id_material = l.id
									WHERE 
										p.id_producto = ' . $idproduct;
				$productocompuesto = $this -> queryArray($querycompuesto);

				// Si no tiene ningun material solamente inserta el registro
				if (!$productocompuesto['rows']) {
					
					$sql4 = "SELECT costo_servicio from app_productos where id = '$idproduct';";
					$result4 = $this -> queryArray($sql4);
					$costo = $result4['rows'][0]['costo_servicio'];	

					$sql = "INSERT INTO 
								com_pedidos 
									(idcomanda, idproducto, cantidad, npersona, tipo, status, opcionales, adicionales,
										 sin, nota_opcional, nota_extra, nota_sin, id_promocion, dependencia_promocion, costo) 
							VALUES('$idcomanda','$idproduct','1','$idperson','$iddep','-1','$opcionales','$extras',
									'$sin','$nota_opcional','$nota_extra', '$nota_sin', '0', '".$objeto['dependencia_promocion']."','$costo')";
					$product = $this -> insert_id($sql);

					return $product;
				}

				// Recorre los materiales y checa si hay suficientes para hacer el pedido
				foreach ($productocompuesto['rows'] as $value) {
					$stock = 999999;

					//se obtiene la cantida del producto en las comandas
					$querycomanda = 'SELECT 
										count(*) as cantidad_comanda 
									FROM 
										com_pedidos 
									WHERE 
										idProducto=' . $idproduct . ' 
									AND 
										status';
					$cancom = $this -> query($querycomanda);
					$row = $cancom -> fetch_array();
					$cantidadcomandas = $row['cantidad_comanda'];

				// Valida que se pueda crear el producto
					if (($cantidadcomandas * $value['cantidad']) >= $stock) {
						return array("status" => false, "msg" => 'No tienes suficiente insumos para realizar el pedido');
						exit();
					} else {

						/// COSTO
							$costoF = $opc2 = $ext2 = $sin2 = $nor2 = 0;

							// COSTO NORMAL  
								$sqlr = 'SELECT costo_receta from com_recetas where id = '.$idproduct.';';
								$resultr = $this -> queryArray($sqlr);
								$nor2 = $resultr['rows'][0]['costo_receta'];
							// COSTO NORMAL FIN

							// SQL NECESARIOS PARA LA RECETA
								$sqls = 'SELECT ids_insumos, proveedores_insumos, ids_insumos_preparados from com_recetas where id = '.$idproduct.';';
								$results = $this -> queryArray($sqls);
								$insumos = $results['rows'][0]['ids_insumos'];
								$proveed = $results['rows'][0]['proveedores_insumos'];
								$insumosp = $results['rows'][0]['ids_insumos_preparados'];
								
								$insumosR = explode(',', $insumos);
								$insumospR = explode(',', $insumosp); 

								$proveedR = explode(',', $proveed);													

								$extrasR = explode(',', $extras);
								$opcionalesR = explode(',', $opcionales);
								$sinR = explode(',', $sin);
							// SQL NECESARIOS PARA LA RECETA FIN

							// COSTO EXTRA
								$new = array_intersect($insumosR, $extrasR); // insumos normales
								$new2 = array_intersect($insumospR, $extrasR); // insumos preparados

								// extras insumos normales
								
								
								foreach ($new as $key => $value) {
									if(!empty($value)){								
										foreach ($proveedR as $k => $v) {
											if($key == $k){


												$sqlf = "SELECT (c.factor / b.factor) factor from app_productos a 
							                            inner join app_unidades_medida b on b.id=a.id_unidad_compra
							                            inner join app_unidades_medida c on c.id=a.id_unidad_venta
							                            where a.id='$value';";
												$resultf = $this -> queryArray($sqlf);
												$factor = $resultf['rows'][0]['factor'];
												
												$sql2 = 'SELECT cantidad from app_producto_material pm where id_producto = '.$idproduct.' and id_material = '.$value.';'; 											
									        	$result2 = $this -> queryArray($sql2);
												$cantidad = $result2['rows'][0]['cantidad'];

												$cantidad = $cantidad * $factor;

												//echo $cantidad.' = '.$cantidad.' * '.$factor;

												$sql = 'SELECT costo from app_costos_proveedor where id_producto = '.$value.' and id_proveedor = '.$v.';';
									        	$result = $this -> queryArray($sql);
												$costo = ($result['rows'][0]['costo']) * ($cantidad*1);									
									        	$ext2 += $costo;

									        	//echo 'costo '.$result['rows'][0]['costo'].' * '.$cantidad;


												$arrayex[]=array(
									                    idproducto   => $value,
									                    idproveedor  => $v,
									                    costo        => $costo,
									                    cantidad     => $cantidad,
									                    sumcosto     => $ext2
									            );
									        
											}
										}
									}
								}
								// extras insumos normales fin
								// extras insumos preparados
								foreach ($new2 as $key2 => $value2) {
									if($value2 != ''){

										$sqlf = "SELECT (c.factor / b.factor) factor from app_productos a 
					                            inner join app_unidades_medida b on b.id=a.id_unidad_compra
					                            inner join app_unidades_medida c on c.id=a.id_unidad_venta
					                            where a.id='$value2';";
										$resultf = $this -> queryArray($sqlf);
										$factor = $resultf['rows'][0]['factor'];

										$sql2 = 'SELECT cantidad from app_producto_material pm where id_producto = '.$idproduct.' and id_material = '.$value2.';'; 											
							        	$result2 = $this -> queryArray($sql2);
										$cantidad = $result2['rows'][0]['cantidad'];

										$cantidad = $cantidad * $factor;

										$sqlp = 'SELECT costo_servicio FROM app_productos where id = '.$value2.';'; 
										$resultp = $this -> queryArray($sqlp);
										$costop = ($resultp['rows'][0]['costo_servicio'])*$cantidad;									
								        $ext2 += $costop;
									}
								}
								// extras insumos preparados fin
							// COSTO EXTRA FIN

							// COSTO ADICIONAL 
								$newo = array_intersect($insumosR, $opcionalesR); // insumos normales
								$newo2 = array_intersect($insumospR, $opcionalesR); // insumos preparados
								// adicionales insumos normales
								foreach ($newo as $key => $value) {
									if(!empty($value)){
										foreach ($proveedR as $k => $v) {
											if($key == $k){

												$sqlf = "SELECT (c.factor / b.factor) factor from app_productos a 
							                            inner join app_unidades_medida b on b.id=a.id_unidad_compra
							                            inner join app_unidades_medida c on c.id=a.id_unidad_venta
							                            where a.id='$value';";
												$resultf = $this -> queryArray($sqlf);
												$factor = $resultf['rows'][0]['factor'];
												
												$sql2 = 'SELECT cantidad from app_producto_material pm where id_producto = '.$idproduct.' and id_material = '.$value.';';										
									        	$result2 = $this -> queryArray($sql2);
												$cantidad = $result2['rows'][0]['cantidad'];

												$cantidad = $cantidad * $factor;

												$sql = 'SELECT costo from app_costos_proveedor where id_producto = '.$value.' and id_proveedor = '.$v.';';
									        	$result = $this -> queryArray($sql);
												$costo = ($result['rows'][0]['costo']) * ($cantidad*1);									
									        	$opc2 += $costo;
									        
											}
										}
									}
								}
								// adicionales insumos normales fin
								// adicionales insumos preparados
								foreach ($newo2 as $key2 => $value2) {
									if($value2 != ''){

										$sqlf = "SELECT (c.factor / b.factor) factor from app_productos a 
					                            inner join app_unidades_medida b on b.id=a.id_unidad_compra
					                            inner join app_unidades_medida c on c.id=a.id_unidad_venta
					                            where a.id='$value2';";
										$resultf = $this -> queryArray($sqlf);
										$factor = $resultf['rows'][0]['factor'];

										$sql2 = 'SELECT cantidad from app_producto_material pm where id_producto = '.$idproduct.' and id_material = '.$value2.';'; 											
							        	$result2 = $this -> queryArray($sql2);
										$cantidad = $result2['rows'][0]['cantidad'];

										$cantidad = $cantidad * $factor;

										$sqlp = 'SELECT costo_servicio FROM app_productos where id = '.$value2.';'; 
										$resultp = $this -> queryArray($sqlp);
										$costop = ($resultp['rows'][0]['costo_servicio'])*$cantidad;									
								        $opc2 += $costop;
									}
								}
								// adicionales insumos preparados fin						
							// COSTO ADICIONAL FIN

							// COSTO SIN
								$news = array_intersect($insumosR, $sinR); // insumos normales
								$news2 = array_intersect($insumospR, $sinR); // insumos preparados
								// adicionales insumos normales


								foreach ($news as $key => $value) {
									if(!empty($value)){
										foreach ($proveedR as $k => $v) {
											if($key == $k){


												$sqlf = "SELECT (c.factor / b.factor) factor from app_productos a 
							                            inner join app_unidades_medida b on b.id=a.id_unidad_compra
							                            inner join app_unidades_medida c on c.id=a.id_unidad_venta
							                            where a.id='$value';";
												$resultf = $this -> queryArray($sqlf);
												$factor = $resultf['rows'][0]['factor'];
												
												$sql2 = 'SELECT cantidad from app_producto_material pm where id_producto = '.$idproduct.' and id_material = '.$value.';';
												/*
												$sql2 = 'SELECT (cantidad / (select factor from app_unidades_medida where unidad_base = 9))cantidad from app_producto_material pm
														left join app_unidades_medida um on um .id = id_unidad where id_producto = '.$idproduct.' and id_material = '.$value.';';
												*/

									        	$result2 = $this -> queryArray($sql2);
												$cantidad = $result2['rows'][0]['cantidad'];

												$cantidad = $cantidad * $factor;

												$sql = 'SELECT costo from app_costos_proveedor where id_producto = '.$value.' and id_proveedor = '.$v.';';
									        	$result = $this -> queryArray($sql);
												$costo = ($result['rows'][0]['costo']) * ($cantidad*1);									
									        	$sin2 += $costo;
									        
											}
										}
									}
								}
								// adicionales insumos normales fin		
								// adicionales insumos preparados
								foreach ($news2 as $key2 => $value2) {
									if($value2 != ''){

										$sqlf = "SELECT (c.factor / b.factor) factor from app_productos a 
					                            inner join app_unidades_medida b on b.id=a.id_unidad_compra
					                            inner join app_unidades_medida c on c.id=a.id_unidad_venta
					                            where a.id='$value2';";
										$resultf = $this -> queryArray($sqlf);
										$factor = $resultf['rows'][0]['factor'];

										$sql2 = 'SELECT cantidad from app_producto_material pm where id_producto = '.$idproduct.' and id_material = '.$value2.';'; 											
							        	$result2 = $this -> queryArray($sql2);
										$cantidad = $result2['rows'][0]['cantidad'];

										$cantidad = $cantidad * $factor;

										$sqlp = 'SELECT costo_servicio FROM app_productos where id = '.$value2.';'; 
										$resultp = $this -> queryArray($sqlp);
										$costop = ($resultp['rows'][0]['costo_servicio'])*$cantidad;									
								        $sin2 += $costop;
									}
								}
								// adicionales insumos preparados fin				
							// COSTO SIN FIN

							// COSTO FINAL								
								$costoF = ($nor2*1) + ($ext2*1) + ($opc2*1) - ($sin2*1);
							// COSTO FINAL FIN

							/// COSTO FIN

						
						$sql = "INSERT INTO 
									com_pedidos 
										(idcomanda, idproducto, cantidad, npersona, tipo, status, opcionales, adicionales,
										sin, nota_opcional, nota_extra, nota_sin, id_promocion, dependencia_promocion,costo) 
								VALUES
								('$idcomanda','$idproduct','1','$idperson','$iddep','-1','$opcionales','$extras','$sin',
								'$nota_opcional','$nota_extra', '$nota_sin', '0', '".$objeto['dependencia_promocion']."', '$costoF')";
						$product = $this -> insert_id($sql);

						return $product;
					}
				}
			} else {
				$stock = 999999;

			//se obtiene la cantida del producto en las comandas
				$querycomanda = 'SELECT 
									count(*) as cantidad_comanda 
								FROM 
									com_pedidos 
								WHERE 
									idProducto = ' . $idproduct . ' 
								AND 
									status';
				$cancom = $this -> query($querycomanda);
				$row = $cancom -> fetch_array();
				$cantidadcomandas = $row['cantidad_comanda'];

				if ($cantidadcomandas >= $stock) {
					return array("status" => false, "msg" => 'No tienes suficiente insumos para realizar el pedido');
					exit();
				} else {
					/// COSTO
						$sql3 = "SELECT costo from app_costos_proveedor where id_producto = '$idproduct' order by fecha desc limit 1;";
						$result3 = $this -> queryArray($sql3);
						$costo = $result3['rows'][0]['costo'];
					
						if($costo == ''){
							$sql4 = "SELECT costo_servicio from app_productos where id = '$idproduct';";
							$result4 = $this -> queryArray($sql4);
							$costo = $result4['rows'][0]['costo_servicio'];
						}

						// CH@ PRODUCTO 1 COSTO FIN
						if($tipo_producto == 7){ // combo - el costo se actualiza al ingresar los productos del combo
							$costo = 0;
						}
					/// COSTO FIN
					$sql = "INSERT INTO 
								com_pedidos 
									(idcomanda, idproducto, cantidad, npersona, tipo, status, opcionales, adicionales, sin, 
									nota_opcional, nota_extra, nota_sin, id_promocion, dependencia_promocion, costo) 
							VALUES 
								('$idcomanda','$idproduct','1','$idperson','$iddep','-1','$opcionales','$extras','$sin',
								'$nota_opcional','$nota_extra','$nota_sin', '0', '".$objeto['dependencia_promocion']."', '$costo')";
					$result = $this -> insert_id($sql);

					return $result;
				}

			}
	}
	
///////////////// ******** ---- 		FIN guardar_pedido_promociones		------ ************ //////////////////

///////////////// ******** ---- 		actualizar_inventario_combo		------ ************ //////////////////
//////// Descuenta del inventario los productos y sus insumos del combo
	// Como parametros puede recibir:
		// idproducto -> ID del combo
		// id -> ID del pedido
	
	function actualizar_inventario_combo($objeto){
		$sql = "SELECT 
					pe.*, p.tipo_producto, p.departamento, (1 * ROUND(p.precio, 2)) AS importe, p.precio
				FROM 
					com_pedidos_combo pe
				LEFT JOIN
						app_productos p
					ON
						p.id = pe.id_producto
				WHERE
					id_pedido = " . $objeto['id']."
				AND
					pe.status = -1";
		$result['pedidos']['result_opcionales'] = $sql;
		$pedidos = $this -> queryArray($sql);
		if (!empty($objeto['sucursal'])) {
			$almacen = "SELECT 
							a.id
						FROM 
							administracion_usuarios au
						LEFT JOIN 
								app_almacenes a
							ON 
								a.id_sucursal = au.idSuc
						WHERE 
							au.idSuc = " . $objeto['sucursal'] . " 
						AND 
							a.activo = 1
						LIMIT 1";
			$almacen = $this -> queryArray($almacen);
			$almacen = $almacen['rows'][0]['id'];
		} else {
		session_start();
	// Valida que exista el empleado si no agrega un cero como id
				$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;
			// Obtiene el almacen
				$almacen = "SELECT 
								a.id
							FROM 
								administracion_usuarios au
							LEFT JOIN 
									app_almacenes a
								ON 
									a.id_sucursal = au.idSuc
							WHERE 
								au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
							LIMIT 1";
				$almacen = $this -> queryArray($almacen);
				$almacen = $almacen['rows'][0]['id'];
		}
	// Valida que exista el almacen
		$almacen = (empty($almacen)) ? 1 : $almacen;

		$fecha = date('Y-m-d H:i:s');

		foreach ($pedidos['rows'] as $key => $value) {
			/// el costo es 0 ya que se calculo en el pedido principal -- se podria insertar el costo que viene de la tabla com_pedidos_combo pero se debe omitir el costo del combo
			$sql = "INSERT INTO 
						com_pedidos 
							(id, idcomanda, idproducto, cantidad, npersona, tipo, status, opcionales, adicionales,
							sin, nota_opcional, nota_extra, nota_sin, origen, costo, complementos) 
					VALUES	
						(null, ".$value['id_comanda'].", ".$value['id_producto'].", '".$value['cantidad_pedidos']."', ".$value['persona'].", '".$value['departamento']."', 
						'0', '".$value['opcionales']."', '".$value['extras']."', '".$value['sin']."', '".$value['nota_opcional']."',
						'".$value['nota_extra']."', '".$value['nota_sin']."', 2, 0, '".$value['complementos']."')";
			$product = $this -> query($sql);
			
		/* Actualiza el inventario
		=========================================================================== */
		
			$sql = "INSERT INTO
						app_inventario_movimientos
						(id_producto, cantidad, importe, id_almacen_origen, fecha, id_empleado,
						tipo_traspaso, costo, referencia)
					VALUES
						('" . $value['id_producto'] . "', '1', '" . $value['importe'] . "', '" . $almacen . "', '" . $fecha . "', 
						'" . $usuario . "', 0, '" . $value['precio'] . "', 'Pedido " . $value['id'] . "')";
			$result_inventario = $this -> query($sql);
			
		// Opcionales
			if (!empty($value['opcionales'])) {
			// Filtra solo por los opcionales seleccionados
				$condicion = (!empty($value['opcionales'])) ? " AND p.id IN(" . $value['opcionales'] . ")" : "";
				
			// Obtiene los productos
				$sql = "SELECT
							p.id, m.cantidad, ROUND(p.precio, 2) AS precio, m.cantidad AS importe, p.formulaieps AS formula
						FROM
							app_productos p
						INNER JOIN
								app_producto_material m
							ON
								m.id_material = p.id
						WHERE
							m.id_producto = " . $value['id_producto'] . $condicion;
				// return $sql;
				$opcionales = $this -> queryArray($sql);
				
			// Actualiza el inventario por cada producto
				foreach ($opcionales['rows'] as $k => $v) {
					$sql = "INSERT INTO
								app_inventario_movimientos
								(id_producto, cantidad, importe, id_almacen_origen, fecha, id_empleado,
								tipo_traspaso, costo, referencia)
							VALUES
								('" . $v['id'] . "', '" . $v['cantidad'] . "', '" . $v['importe'] . "', '" . $almacen . "', '" . $fecha . "', 
								'" . $usuario . "', 0, '" . $v['importe'] . "', 'Pedido " . $value['id'] . "')";
					$result_opcional = $this -> query($sql);
				}
			}

		// Sin
			if (!empty($value['sin'])) {
			// Excluye los insumos sin del inventario
				$condicion = (!empty($value['sin'])) ? " AND p.id NOT IN(" . $value['sin'] . ")" : "";
				
			// Obtiene los productos
				$sql = "SELECT
							p.id, m.cantidad, ROUND(p.precio, 2) AS precio, m.cantidad AS importe, p.formulaieps AS formula
						FROM
							app_productos p
						INNER JOIN
								app_producto_material m
							ON
								m.id_material = p.id
						WHERE
							m.id_producto = " . $value['id_producto'] . $condicion;
				// return $sql;
				$sin = $this -> queryArray($sql);
				
			// Actualiza el inventario por cada producto
				foreach ($sin['rows'] as $k => $v) {
					$sql = "INSERT INTO
								app_inventario_movimientos
								(id_producto, cantidad, importe, id_almacen_origen, fecha, id_empleado,
								tipo_traspaso, costo, referencia)
							VALUES
								('" . $v['id'] . "', '" . $v['cantidad'] . "', '" . $v['importe'] . "', '" . $almacen . "', '" . $fecha . "', 
								'" . $usuario . "', 0, '" . $v['importe'] . "', 'Pedido " . $value['id']  . "')";
					$result_opcional = $this -> query($sql);
				}
			}
			
		// Extras
			if (!empty($value['extras'])) {
				$sql = "SELECT
							p.id, m.cantidad, ROUND(p.precio, 2) AS precio, m.cantidad AS importe, p.formulaieps AS formula
						FROM
							app_productos p
						INNER JOIN
								app_producto_material m
							ON
								m.id_material = p.id
						WHERE
							p.id IN(" . $value['extras'] . ")
						AND
							m.id_producto = " . $value['id_producto'];
			// $result[$value['id_producto']]['extras'] = $sql;
				$adicionales = $this -> queryArray($sql);

			// Actualiza el inventario por cada producto
				foreach ($adicionales['rows'] as $k => $v) {
					$sql = "INSERT INTO
								app_inventario_movimientos
									(id_producto, cantidad, importe, id_almacen_origen, fecha, id_empleado,
									tipo_traspaso, costo, referencia)
							VALUES
								('" . $v['id'] . "', '" . $v['cantidad'] . "', '" . $v['importe'] . "', '" . $almacen . "', '" . $fecha . "', 
								'" . $_SESSION['accelog_idempleado'] . "', 0, '" . $v['importe'] . "', 
								'Combo " . $objeto['idproducto'] . "')";
					$result_adicionales = $this -> query($sql);
				}
			}
			
		/* FIN Actualiza el inventario
		=========================================================================== */
					
		} //FIN foreach
	
	// Actuliza el estado del pedido para indicar que se dio de alta (status='0')
		$sql = "UPDATE 
					com_pedidos_combo 
				SET 
					status = '0' 
				WHERE 
					status = '-1' 
				AND 
					id_pedido = " . $objeto['id'];
		$result = $this -> query($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 	FIN actualizar_inventario_combo		------ ************ //////////////////

///////////////// ******** ---- 			listar_propinas				------ ************ //////////////////
//////// Consulta las propinas y lo agrega a la div
	// Como parametros recibe:
		// f_ini -> fecha y hora de inicio
		// F_fin -> fecha y hora final
		// div -> div donde se cargara el contenido html
		// empleado -> ID del empleado
		// mesa -> ID de la emsa
		// sucursal -> ID de la sucursal
		// metodo_pago -> Metodo de pago

	function listar_propinas($objeto) {
	// Filtra por la sucursal si existe
		$condicion .= (!empty($objeto['sucursal'])) ? ' AND m.idSuc IN('.$objeto['sucursal'].')' : '';
	// Filtra por el empleado si existe
		$condicion .= (!empty($objeto['empleado'])) ? ' AND u.idempleado IN('.$objeto['empleado'].')' : '';
	// Si viene el id de la mesa Filtra por la mesa
		$condicion .= (!empty($objeto['mesa'])) ? ' AND c.idmesa IN('.$objeto['mesa'].')' : '';
	// Filtra por la via de contacto si existe
		$condicion .= (!empty($objeto['via_contacto'])) ? ' AND via.id  IN('.$objeto['via_contacto'].')' : '';

		$condicion .= (!empty($objeto['metodo_pago'])) ? ' AND metodo_pago  IN('.$objeto['metodo_pago'].')' : '';
		
	// Se filtra por fecha de inicio y fin si estas existen
		$condicion .= (!empty($objeto['f_ini']) && !empty($objeto['f_fin'])) ? 
			' AND v.fecha BETWEEN \'' . $objeto['f_ini'] . '\' AND \'' . $objeto['f_fin'] . '\'' : '';
			
	// Agrupa la consulta por los parametros indicados si existe, si no la agrupa por id
		$condicion .= (!empty($objeto['agrupar'])) ? ' GROUP BY ' . $objeto['agrupar'] . ', pro.metodo_pago': ' GROUP BY pro.id, pro.metodo_pago';

	// Ordena la consulta por los parametros indicados si existe, si no la ordena por id Descendente
		$condicion .= (!empty($objeto['orden'])) ? ' ' . $objeto['orden'] : ' ORDER BY pro.id DESC';
		
		$sql = "SELECT
					COUNT(pro.id_venta) AS propinas, pro.id_venta, c.codigo, m.nombre AS nombre_mesa, u.usuario AS mesero, SUM(pro.monto) AS total_propina,
					ROUND(v.monto, 2) AS total_venta, suc.nombre AS sucursal, via.nombre AS via_contacto, v.fecha, pa.nombre AS metodo_pago
				FROM
					com_propinas pro
				LEFT JOIN
						app_pos_venta v
					ON
						v.idVenta = pro.id_venta
				LEFT JOIN
						com_comandas c
					ON
						c.id_venta = v.idVenta
				LEFT JOIN
						com_mesas m
					ON
						c.idmesa = m.id_mesa
				LEFT JOIN
						mrp_sucursal suc
					ON
						suc.idSuc = m.idSuc
				LEFT JOIN
						accelog_usuarios u
					ON
						c.idempleado = u.idempleado
				LEFT JOIN
						com_vias_contacto via
					ON
						via.id = m.id_via_contacto
				LEFT JOIN
						forma_pago pa
					ON
						pa.idFormapago = pro.metodo_pago
				WHERE
					1 = 1".
					$condicion;
		// return $sql;
					//print_r($sql);
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 			FIN listar_propinas			------ ************ //////////////////

///////////////// ******** ---- 				get_idioma				------ ************ //////////////////
//////// Consulta el idioma en la BD
	// Como parametros recibe:
		
	function get_idioma($objeto) {
		$sql = "SELECT
					idioma
				FROM
					com_configuracion";

        $result = $this->queryArray($sql);
        
        return $result["rows"][0]["idioma"];
	}

///////////////// ******** ---- 			FIN get_idioma				------ ************ //////////////////

///////////////// ******** ---- 			datos_sucursal		------ ************ //////////////////
//////// Consulta los datos de la sucursal 
	// Como parametros recibe:
		// id_mesa -> id de la mesa para saber que sucursal es
    public function datos_sucursal($id_mesa){
		if (empty($id_mesa)) {
			$sucursal = "	SELECT 
								mp.idSuc AS id 
							FROM 
								administracion_usuarios au 
							INNER JOIN 
									mrp_sucursal mp 
								ON 
									mp.idSuc = au.idSuc 
							WHERE 
								au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
							LIMIT 1";
			$sucursal = $this -> queryArray($sucursal);
			$idSuc = $sucursal['rows'][0]['id'];
		} else {
	        $sql = "SELECT idSuc from com_mesas where id_mesa=".$id_mesa;
	
	        $result = $this->queryArray($sql);
	        $idSuc = $result['rows'][0]['idSuc'];
		}

        $sql = "SELECT 
        			* 
        		FROM 
        			mrp_sucursal s 
        		LEFT JOIN estados e ON e.idestado = s.idEstado 
        		LEFT JOIN municipios m on m.idmunicipio = s.idMunicipio 
        		WHERE 
        			idSuc=".$idSuc;
        $result = $this->queryArray($sql);

        return $result['rows'];
    }

///////////////// ******** ---- 			FIN datos_sucursal				------ ************ //////////////////

///////////////// ******** ---- 			mesero_comanda		------ ************ //////////////////
//////// Consulta el mesero de la comanda
	// Como parametros recibe:
		// id_comanda -> id de la comanda para saber su mesero
    public function mesero_comanda($id_comanda){

        $sql = "SELECT empleados.nombre from empleados
				join com_comandas on com_comandas.idempleado = empleados.idempleado
				where com_comandas.id =".$id_comanda;
				

        $result = $this->queryArray($sql);
        return $result['rows'][0]['nombre'];

    }

 ///////////////// ******** ---- 			FIN mesero_comanda				------ ************ //////////////////

///////////////// ******** ---- 			get_que_mostrar_ticket		------ ************ //////////////////
//////// Consulta el mostrar_domicilio, mostrar_tel y mostrar_nombre en la BD
	// Como parametros recibe:
		
	function get_que_mostrar_ticket($objeto) {
		$sql = "SELECT
					mostrar_nombre, mostrar_tel, mostrar_domicilio, switch_info_ticket, mostrar_info_empresa, mostrar_iva, una_linea
				FROM
					com_configuracion";
        $result = $this->queryArray($sql);
        
        return $result["rows"][0];
	}

///////////////// ******** ---- 			FIN get_que_mostrar_ticket				------ ************ //////////////////

///////////////// ******** ----             	listar_complementos       	 	    ------ ************ //////////////////
/////// Consulta los complementos
	// Como parametros recibe:
		// complementos -> String con los ID de los productos
		
	function listar_complementos($objeto){

			session_start();
		/// CONDICION PARA PROMOCIONES POR SUCURSAL 
			$condicion2 = '';			
			$sucursal = "	SELECT mp.idSuc AS id 
							FROM administracion_usuarios au 
							INNER JOIN mrp_sucursal mp ON mp.idSuc = au.idSuc 
							WHERE au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
							LIMIT 1";
			$sucursal = $this -> queryArray($sucursal);
			$sucursal = $sucursal['rows'][0]['id'];

			// Filtra por el ID de la sucursal si existe
			$condicion2 = (!empty($sucursal))?' and idsuc like "%'.$sucursal.'%" ':'';
		/// CONDICION PARA PROMOCIONES POR SUCURSAL  FIN

		session_start();
		$sucursal = "	SELECT 
							mp.idSuc AS id 
						FROM 
							administracion_usuarios au 
						INNER JOIN 
								mrp_sucursal mp 
							ON 
								mp.idSuc = au.idSuc 
						WHERE 
							au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
						LIMIT 1";
		$sucursal = $this -> queryArray($sucursal);
		$sucursal = $sucursal['rows'][0]['id'];
	
		$sql = "select * from app_producto_sucursal limit 1";
		$total = $this -> queryArray($sql);
		if($total['total'] > 0){
		// Filtra por los complementos si existe
			$condicion .= (!empty($objeto['complementos'])) ? ' AND c.id_producto IN('.$objeto['complementos'].')' : '';
			
			$sql = "SELECT
						p.id, c.cantidad, p.nombre, IF(p.tipo_producto = 4, ROUND(p.costo_servicio, 2), IFNULL(pro.costo,0)) AS costo,
						p.tipo_producto, p.ruta_imagen AS imagen, ROUND(p.precio, 2) AS precio, c.id AS id_complemento
					FROM
						com_complementos c
					LEFT JOIN
							app_productos p
						ON
							p.id = c.id_producto
					LEFT JOIN
							app_costos_proveedor pro
						ON
							pro.id_producto = p.id
					INNER JOIN app_producto_sucursal aps ON aps.id_producto = p.id AND aps.id_sucursal = ".$sucursal."
					WHERE
						1 = 1 ".$condicion2.
						
						$condicion . " GROUP BY p.id";
		} else {
		// Filtra por los complementos si existe
			$condicion .= (!empty($objeto['complementos'])) ? ' AND c.id_producto IN('.$objeto['complementos'].')' : '';
			
			$sql = "SELECT
						p.id, c.cantidad, p.nombre, IF(p.tipo_producto = 4, ROUND(p.costo_servicio, 2), IFNULL(pro.costo,0)) AS costo,
						p.tipo_producto, p.ruta_imagen AS imagen, ROUND(p.precio, 2) AS precio, c.id AS id_complemento
					FROM
						com_complementos c
					LEFT JOIN
							app_productos p
						ON
							p.id = c.id_producto
					LEFT JOIN
							app_costos_proveedor pro
						ON
							pro.id_producto = p.id
					WHERE
						1 = 1 ".$condicion2.
						$condicion . " GROUP BY p.id";
		}
		// return $sql;
		$result = $this -> queryArray($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 				FIN guardar_complementos			------ ************ //////////////////

///////////////// ******** ---- 				editar_empleado						------ ************ //////////////////
//////// Edita los datos del empleado
	// Como parametros recibe:
		// id -> ID del emppleado
		// mostrar_comanda -> 1 -> Se debe mostrar en la comanda

	function editar_empleado($objeto) {

		$sql = "UPDATE
					administracion_usuarios
				SET
					mostrar_comanda = '". $objeto['mostrar_comanda'] . "'	
				WHERE
					idempleado = " . $objeto['id'];
		$result = $this -> query($sql);
		
		return $result;
	}

///////////////// ******** ---- 				FIN editar_empleado					------ ************ //////////////////

///////////////// ******** ---- 				guardar_merma						------ ************ //////////////////
//////// Guarda la merma del producto
	// Como parametros recibe:
		// pedido -> Arra y con los datos del pedido
		// comentario -> String con el comentario de la merma

	function guardar_merma($objeto) {
		session_start();
        date_default_timezone_set("Mexico/General");
        $fecha = date('Y-m-d H:i:s');
		
	// Obtiene el almacen
		$almacen = "SELECT 
						a.id
					FROM 
						administracion_usuarios au
					LEFT JOIN 
							app_almacenes a
						ON 
							a.id_sucursal = au.idSuc
					WHERE 
						au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
					AND 
						a.activo = 1
					LIMIT 1";
		$almacen = $this -> queryArray($almacen);
		$almacen = $almacen['rows'][0]['id'];
		
        $sql = "INSERT INTO 
        			app_merma(fecha, usuario, productos, importe) 
        		VALUES 
        			('".$fecha."','".$_SESSION['accelog_idempleado']."', '".$objeto['pedido_merma']['cantidad']."', 0)";
        $id_merma = $this->insert_id($sql);
		
		$sql = "SELECT 
					pe.*, p.tipo_producto, ROUND(IF(p.costo_servicio > 0, p.costo_servicio, pro.costo), 2) AS costo
				FROM 
					com_pedidos pe
				LEFT JOIN
						app_productos p
					ON
						p.id = pe.idproducto
				LEFT JOIN
						app_costos_proveedor pro
					ON
						pro.id_producto = p.id
				WHERE 
					pe.id = " . $objeto['id'];
		$pedido = $this -> queryArray($sql);
		$pedido = $pedido['rows'][0];
	
	// Combo
		if ($value['tipo_producto'] == 7) {
			$pedido['coemntario'] = $objeto['comentario'];
			$pedido['id_merma'] = $id_merma;
			$result = $this -> guardar_merma_combo($pedido);
		
			return $result;
		}
		
        $sql = "INSERT INTO 
        			app_merma_datos(id_merma, id_producto, cantidad, precio, usuario, almacen, observaciones)
        		VALUES 
        			('".$id_merma."', '".$pedido['idproducto']."', '".$pedido['cantidad']."', '".$pedido['costo']."', 
        			'".$_SESSION['accelog_idempleado']."', '".$almacen."','".$objeto['comentario']."')";
        //echo $queryInserProd;
        $result = $this->query($sql);
		        
	// Opcionales
		if (!empty($pedido['opcionales'])) {
		// Filtra solo por los opcionales seleccionados
			$condicion = (!empty($pedido['opcionales'])) ? " AND p.id IN(" . $pedido['opcionales'] . ")" : "";
			
		// Obtiene los productos
			$sql = "SELECT
						p.id, m.cantidad, ROUND(IF(p.costo_servicio > 0, p.costo_servicio, pro.costo), 2) AS costo
					FROM
						app_productos p
					INNER JOIN
							app_producto_material m
						ON
							m.id_material = p.id
					LEFT JOIN
							app_costos_proveedor pro
						ON
							pro.id_producto = p.id
					WHERE
						m.id_producto = " . $pedido['idproducto'] . $condicion;
			// return $sql;
			$opcionales = $this -> queryArray($sql);
			
		// Actualiza el inventario por cada producto
			foreach ($opcionales['rows'] as $k => $v) {
		        $sql = "INSERT INTO 
		        			app_merma_datos(id_merma, id_producto, cantidad, precio, usuario, almacen, observaciones)
		        		VALUES 
		        			('".$id_merma."', '".$v['id']."', '".$v['cantidad']."', '".$v['costo']."', 
		        			'".$_SESSION['accelog_idempleado']."', '".$almacen."','".$objeto['comentario']."')";
		        //echo $queryInserProd;
		        $result = $this->query($sql);
			}
		}

	// Sin
		if (!empty($pedido['sin'])) {
		// Excluye los insumos sin del inventario
			$condicion = (!empty($pedido['sin'])) ? " AND p.id NOT IN(" . $pedido['sin'] . ")" : "";
			
		// Obtiene los productos
			$sql = "SELECT
						p.id, m.cantidad, ROUND(IF(p.costo_servicio > 0, p.costo_servicio, pro.costo), 2) AS costo
					FROM
						app_productos p
					INNER JOIN
							app_producto_material m
						ON
							m.id_material = p.id
					LEFT JOIN
							app_costos_proveedor pro
						ON
							pro.id_producto = p.id
					WHERE
						m.id_producto = " . $pedido['idproducto'] . $condicion;
			// return $sql;
			$sin = $this -> queryArray($sql);
			
		// Actualiza el inventario por cada producto
			foreach ($sin['rows'] as $k => $v) {
		        $sql = "INSERT INTO 
		        			app_merma_datos(id_merma, id_producto, cantidad, precio, usuario, almacen, observaciones)
		        		VALUES 
		        			('".$id_merma."', '".$v['id']."', '".$v['cantidad']."', '".$v['costo']."', 
		        			'".$_SESSION['accelog_idempleado']."', '".$almacen."','".$objeto['comentario']."')";
		        //echo $queryInserProd;
		        $result = $this->query($sql);
			}
		}
	
	// Extras
		if (!empty($pedido['adicionales'])) {
			$sql = "SELECT
						p.id, m.cantidad, ROUND(IF(p.costo_servicio > 0, p.costo_servicio, pro.costo), 2) AS costo
					FROM
						app_productos p
					INNER JOIN
							app_producto_material m
						ON
							m.id_material = p.id
					LEFT JOIN
							app_costos_proveedor pro
						ON
							pro.id_producto = p.id
					WHERE
						p.id IN(" . $pedido['adicionales'] . ")
					AND
						m.id_producto = " . $pedido['idproducto'];
			$adicionales = $this -> queryArray($sql);

		// Actualiza el inventario por cada producto
			foreach ($adicionales['rows'] as $k => $v) {
		        $sql = "INSERT INTO 
		        			app_merma_datos(id_merma, id_producto, cantidad, precio, usuario, almacen, observaciones)
		        		VALUES 
		        			('".$id_merma."', '".$v['id']."', '".$v['cantidad']."', '".$v['costo']."', 
		        			'".$_SESSION['accelog_idempleado']."', '".$almacen."','".$objeto['comentario']."')";
				$result_adicionales = $this -> query($sql);
			}
		}
	
	// Complementos
		if (!empty($pedido['complementos'])) {
			$sql = "SELECT
						p.id, m.cantidad, ROUND(IF(p.costo_servicio > 0, p.costo_servicio, pro.costo), 2) AS costo
					FROM
						app_productos p
					INNER JOIN
							app_producto_material m
						ON
							m.id_material = p.id
					LEFT JOIN
							app_costos_proveedor pro
						ON
							pro.id_producto = p.id
					WHERE
						c.id_producto IN(" . $pedido['complementos'] . ")";
			$complementos = $this -> queryArray($sql);

		// Actualiza el inventario por cada producto
			foreach ($complementos['rows'] as $k => $v) {
		        $sql = "INSERT INTO 
		        			app_merma_datos(id_merma, id_producto, cantidad, precio, usuario, almacen, observaciones)
		        		VALUES 
		        			('".$id_merma."', '".$v['id']."', '".$v['cantidad']."', '".$v['costo']."', 
		        			'".$_SESSION['accelog_idempleado']."', '".$almacen."','".$objeto['comentario']."')";
				$result_adicionales = $this -> query($sql);
			}
		}
		
		return $result;
	}

///////////////// ******** ----				FIN guardar_merma	 				------ ************ //////////////////
	
///////////////// ******** ---- 			guardar_merma_combo					------ ************ //////////////////
//////// Guarda las mermas del combo, sus productos e insumos
	// Como parametros puede recibir:
		// idproducto -> ID del combo
		// id -> ID del pedido
	
	function guardar_merma_combo($objeto){
		session_start();
		$id_merma = $objeto['id_merma'];
		
		$sql = "SELECT 
					pe.*, p.tipo_producto, p.departamento
				FROM 
					com_pedidos_combo pe
				LEFT JOIN
						app_productos p
					ON
						p.id = pe.id_producto
				AND
					pe.status = -1
				WHERE
					id_pedido = " . $objeto['id'];
		// $result['pedidos']['result_opcionales'] = $sql;
		$pedidos = $this -> queryArray($sql);
		
	// Obtiene el almacen
		$almacen = "SELECT 
						a.id
					FROM 
						administracion_usuarios au
					LEFT JOIN 
							app_almacenes a
						ON 
							a.id_sucursal = au.idSuc
					WHERE 
						au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
					LIMIT 1";
		$almacen = $this -> queryArray($almacen);
		$almacen = $almacen['rows'][0]['id'];

	// Valida que exista el almacen
		$almacen = (empty($almacen)) ? 1 : $almacen;

		$fecha = date('Y-m-d H:i:s');

		foreach ($pedidos['rows'] as $key => $value) {
		// Opcionales
			if (!empty($value['opcionales'])) {
			// Filtra solo por los opcionales seleccionados
				$condicion = (!empty($value['opcionales'])) ? " AND p.id IN(" . $value['opcionales'] . ")" : "";
				
			// Obtiene los productos
				$sql = "SELECT
							p.id, m.cantidad, ROUND(IF(p.costo_servicio > 0, p.costo_servicio, pro.costo), 2) AS costo
						FROM
							app_productos p
						INNER JOIN
								app_producto_material m
							ON
								m.id_material = p.id
						LEFT JOIN
								app_costos_proveedor pro
							ON
								pro.id_producto = p.id
						WHERE
							m.id_producto = " . $value['id_producto'] . $condicion;
				// return $sql;
				$opcionales = $this -> queryArray($sql);
				
			// Actualiza el inventario por cada producto
				foreach ($opcionales['rows'] as $k => $v) {
			        $sql = "INSERT INTO 
			        			app_merma_datos(id_merma, id_producto, cantidad, precio, usuario, almacen, observaciones)
			        		VALUES 
			        			('".$id_merma."', '".$v['id']."', '".$v['cantidad']."', '".$v['costo']."', 
			        			'".$_SESSION['accelog_idempleado']."', '".$almacen."','".$objeto['comentario']."')";
					$result_adicionales = $this -> query($sql);
				}
			}

		// Sin
			if (!empty($value['sin'])) {
			// Excluye los insumos sin del inventario
				$condicion = (!empty($value['sin'])) ? " AND p.id NOT IN(" . $value['sin'] . ")" : "";
				
			// Obtiene los productos
				$sql = "SELECT
							p.id, m.cantidad, ROUND(IF(p.costo_servicio > 0, p.costo_servicio, pro.costo), 2) AS costo
						FROM
							app_productos p
						INNER JOIN
								app_producto_material m
							ON
								m.id_material = p.id
						LEFT JOIN
								app_costos_proveedor pro
							ON
								pro.id_producto = p.id
						WHERE
							m.id_producto = " . $value['id_producto'] . $condicion;
				// return $sql;
				$sin = $this -> queryArray($sql);
				
			// Actualiza el inventario por cada producto
				foreach ($sin['rows'] as $k => $v) {
			        $sql = "INSERT INTO 
			        			app_merma_datos(id_merma, id_producto, cantidad, precio, usuario, almacen, observaciones)
			        		VALUES 
			        			('".$id_merma."', '".$v['id']."', '".$v['cantidad']."', '".$v['costo']."', 
			        			'".$_SESSION['accelog_idempleado']."', '".$almacen."','".$objeto['comentario']."')";
					$result_opcional = $this -> query($sql);
				}
			}
			
		// Extras
			if (!empty($value['extras'])) {
				$sql = "SELECT
							p.id, m.cantidad, ROUND(IF(p.costo_servicio > 0, p.costo_servicio, pro.costo), 2) AS costo
						FROM
							app_productos p
						INNER JOIN
								app_producto_material m
							ON
								m.id_material = p.id
						LEFT JOIN
								app_costos_proveedor pro
							ON
								pro.id_producto = p.id
						WHERE
							p.id IN(" . $value['extras'] . ")
						AND
							m.id_producto = " . $value['id_producto'];
			// $result[$value['id_producto']]['extras'] = $sql;
				$adicionales = $this -> queryArray($sql);

			// Actualiza el inventario por cada producto
				foreach ($adicionales['rows'] as $k => $v) {
			        $sql = "INSERT INTO 
			        			app_merma_datos(id_merma, id_producto, cantidad, precio, usuario, almacen, observaciones)
			        		VALUES 
			        			('".$id_merma."', '".$v['id']."', '".$v['cantidad']."', '".$v['costo']."', 
			        			'".$_SESSION['accelog_idempleado']."', '".$almacen."','".$objeto['comentario']."')";
					$result_adicionales = $this -> query($sql);
				}
			}
					
		} //FIN foreach
	
		return $result;
	}
		
///////////////// ******** ---- 		FIN actualizar_inventario_combo			------ ************ //////////////////
	
///////////////// ******** ---- 		listar_metodos_pago						------ ************ //////////////////
//////// Edita los datos del empleado
	// Como parametros recibe:
		// id -> ID del emppleado
		// mostrar_comanda -> 1 -> Se debe mostrar en la comanda

	function listar_metodos_pago($objeto) {

		$sql = "SELECT
					idFormapago AS id, nombre
				FROM
					forma_pago";
		$result = $this -> queryArray($sql);
		
		return $result;
	}

///////////////// ******** ---- 			FIN listar_metodos_pago				------ ************ //////////////////

///////////////// ******** ---- 		save_pass						------ ************ //////////////////
//////// Edita los datos del empleado
	// Como parametros recibe:
		// id -> ID del emppleado
		// mostrar_comanda -> 1 -> Se debe mostrar en la comanda

	function save_pass($objeto) {

		$sql = "UPDATE 
					com_mesas
				SET 
					password = '".$objeto['pass']."'
				WHERE 
					id_mesa = ".$objeto['id'];
		$res = $this -> query($sql);

		return $res;
	}

///////////////// ******** ---- 			FIN save_pass				------ ************ //////////////////

///////////////// ******** ---- 		ordenar						------ ************ //////////////////
//////// Edita los datos del empleado
	// Como parametros recibe:
		// id -> ID del emppleado
		// mostrar_comanda -> 1 -> Se debe mostrar en la comanda

	function ordenar($objeto) {

		$sql = "SELECT password FROM com_mesas where id_mesa = ".$objeto['id'];
		$res = $this -> queryArray($sql);

		return $res['rows'][0];
	}

///////////////// ******** ---- 			FIN ordenar				------ ************ //////////////////
	
///////////////// ******** ---- 		removePassMesa						------ ************ //////////////////
//////// Edita los datos del empleado
	// Como parametros recibe:
		// id -> ID del emppleado
		// mostrar_comanda -> 1 -> Se debe mostrar en la comanda

	function removePassMesa($objeto) {

		$sql = "UPDATE 
					com_mesas
				SET 
					password = ''
				WHERE 
					id_mesa = ".$objeto['idmesa'];
		$res = $this -> query($sql);

		return $res;
	}

///////////////// ******** ---- 			FIN removePassMesa				------ ************ //////////////////

//ch@ 
	function statusComanda($id_comanda){
		$sql = "SELECT status FROM com_comandas where id = '$id_comanda';";
		$result = $this->queryArray($sql);
		return $result['rows'][0]['status'];
	}
	function statusPedidos($id_comanda){
		$sql = "SELECT count(id) as id FROM com_pedidos WHERE idcomanda = ".$id_comanda." AND status != 3;";
		$result = $this->queryArray($sql);
		return $result['rows'][0]['id'];
	}
	function pedidosDiv($id_comanda){
		$sql = 'SELECT id, idproducto, npersona from com_pedidos where idcomanda = '.$id_comanda.' and idproducto != 0;';
		$result = $this->queryArray($sql);
		return $result['rows'];
	}

	function notificacionesMoviles($objeto){

		$idComanda = $objeto['idComanda'];
		$bandera = $objeto['bandera'];
		$idmesa = $objeto['idmesa'];

		$sql = 'SELECT push FROM api_token WHERE id_empleado ='. $_SESSION['accelog_idempleado'] .' AND activo = 1 ORDER BY inicio DESC LIMIT 1 ;';
		$result = $this->queryArray($sql);
		$push = $result['rows'][0]['push'];
	
		$google = 'https://fcm.googleapis.com/fcm/send';
        $parametros = array ('registration_ids' => array($push), 'data' => array ("message" => $idmesa));
        $parametros = json_encode($parametros);
        $encabezados = array ('Authorization: key=' . "AIzaSyCOoBuThk_UzHs5skEVqa9Vmdv4X9BaLPU", 'Content-Type: application/json');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $google);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $encabezados);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parametros);
        $resultado = curl_exec($ch);
        curl_close($ch);
	}
	function savenota($id_pedido,$nota){
		$sql = "UPDATE com_pedidos SET notap = '$nota' WHERE id = ".$id_pedido.";";
		$res = $this -> query($sql);
		return $res;
	}

	function aplidaDesc($id_pedido,$tipo_desc,$monto_desc){
		$sql = "UPDATE com_pedidos SET tipo_desc = '$tipo_desc', monto_desc = '$monto_desc' WHERE id = ".$id_pedido.";";
		$res = $this -> query($sql);
		return $res;
	}

	function tipocambio(){
		$sql = "SELECT tipo_cambio FROM cont_tipo_cambio ORDER BY id DESC limit 1;";
		$result = $this->queryArray($sql);
		return $result['rows'][0]['tipo_cambio'];
	}

	function is_promocion($id_pedido,$complementos){
		$sql = "SELECT id from com_pedidos where dependencia_promocion = '$id_pedido';";
		$result = $this->queryArray($sql);
		$result1 = $result['rows'][0]['id'];

		if($result1 > 0){
			$sql1 = "UPDATE com_pedidos SET complementos = '$complementos' WHERE id = ".$result1.";";
			$result = $this->query($sql1);
		}
		
		return $result;
		

	}

	function is_combo($id_pedido,$complementos){
		$sql = "SELECT id from com_pedidos_combo where id_pedido = '$id_pedido' limit 1;";
		$result = $this->queryArray($sql);
		$result1 = $result['rows'][0]['id'];

		if($result1 > 0){
			$sql1 = "UPDATE com_pedidos_combo SET complementos = '$complementos' WHERE id = ".$result1.";";
			$result = $this->query($sql1);
		}
		
		return $result;
		

	}
	function actualizarTotalComanda($idcomanda,$total){
		$sql = "UPDATE com_comandas SET total = '$total' WHERE id = ".$idcomanda.";";
		$result = $this->query($sql);
		return $result;
	}
	function getSuc($idempleado){
		$sql = "SELECT DISTINCT mp.idSuc AS id 
				FROM administracion_usuarios au 
				INNER JOIN mrp_sucursal mp ON mp.idSuc = au.idSuc 
				WHERE au.idempleado = " . $idempleado . " LIMIT 1";
		$result = $this -> queryArray($sql);
		$idsuc = $result['rows'][0]['id'];
		return $idsuc;
	}

	function newtable($nombreMesa,$idsuc,$mesero,$idarea){
		$sql = "INSERT INTO `com_mesas` (`idDep`, `personas`, `tipo`, `nombre`, `domicilio`, `idempleado`, `x`, `y`, `status`, `idSuc`, `notificacion`, `tipo_mesa`, `width`, `height`, `id_area`, `id_dependencia`, `password`)
				VALUES('$idarea', 12, 3, '$nombreMesa', '', '$mesero', 18, 0, 1, '$idsuc', 0, 1, 2, 2, 1, 0, '');";
		$idmesa = $this->insert_id($sql);			

		$sql = "UPDATE com_meseros
				SET asignacion = concat(asignacion,',".$idmesa."'), permisos = concat(permisos,',".$idmesa."')
				WHERE id_mesero = " . $mesero;
		$result = $this -> query($sql);

		return $result;
	}

	function eliminaMesa($id_mesa,$idempleado){
		$sql = "UPDATE com_mesas SET status = 2 WHERE id_mesa = " .$id_mesa;
		$result = $this -> query($sql);

		$sql = "SELECT permisos FROM com_meseros WHERE id_mesero = " .$idempleado;
		$result = $this -> queryArray($sql);
		$permisos = $result['rows'][0]['permisos'];

		$id_mesa = ','.$id_mesa;
		$permisos = str_replace($id_mesa,"",$permisos);

		$sql = "UPDATE com_meseros SET permisos = '".$permisos."' WHERE id_mesero = " .$idempleado;
		$result = $this -> query($sql);
	}
	

} // Fin Clase comandasModel

?>
