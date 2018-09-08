<?php
require ("models/connection_sqli.php");
// funciones mySQLi

session_start();
class comandasModel extends Connection {
///////////////// ******** ---- 		listar_mesas		------ ************ //////////////////
//////// Consulta las mesas y las regresa en un array
	// Como parametros recibe:
	// id -> id de la mesa

	function getTables($objeto) {
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

		$sql = "SELECT 
					a.id_mesa AS mesa, a.x, a.y, b.nombre, a.personas, a.tipo, a.domicilio, a.idempleado,
					ad.nombreusuario AS mesero, a.notificacion, 
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
						idcomanda 
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
				WHERE(
						a.id_mesa 
							NOT IN(select idmesa from com_union) 
					OR 
						a.id_mesa 
							IN(select idprincipal from com_union) 
				)" . $condicion . " 
				AND
					a.status = 1
				AND
					a.idSuc = " . $sucursal ."
				GROUP BY 
					a.id_mesa 
				ORDER BY 
					a.id_mesa asc";
		$tablesComanda = $this -> queryArray($sql);

		// return $sql;
		return $tablesComanda;
	}

///////////////// ******** ---- 		FIN listar_mesas		------ ************ //////////////////

	function getProducts($idDeparment = 0, $idFamily = 0, $idLine = 0, $limite = 0) {
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
					tipo_producto != 6 " . 
				$condicion . "
				GROUP BY
					p.id
				ORDER BY
					f.rate DESC".
				$limite;
		$productsComanda = $this -> queryArray($sql);

		return $productsComanda;
	}

	function getProduct($idproduct) {
		$sql = "SELECT 
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

	function getPersons($idcomanda) {
		$sql = "SELECT 
					npersona, COUNT(npersona) AS num_personas
				FROM 
					com_pedidos 
				WHERE 
					idcomanda = " . $idcomanda . " 
				GROUP BY 
					npersona 
				ORDER BY 
					npersona ASC";
		// return $sql;
		$people = $this -> queryArray($sql);

		return $people;
	}

///////////////// ******** ---- 	getItemsPerson		------ ************ //////////////////
//////// Obtiene los pedidos de la persona y los regresa en un array
	// Como parametros puede recibir:
	// 	$person -> numero de persona
	//	$comanda -> id de la comanda

	function getItemsPerson($person, $comanda) {
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
					a.npersona = $person
				AND 
					a.idcomanda = " . $comanda . " 
				GROUP BY 
						status, a.idproducto, a.opcionales, a.adicionales";
		// return $sql;
		$productsComanda = $this -> queryArray($sql);
		$array = Array("rows");

		$contador = 0;

		// Recorre los registros para formar una cadena de lo opcionales, extra y sin
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
				$sql = "SELECT 
							CONCAT('Extra: ',GROUP_CONCAT(nombre)) nombre, id
						FROM 
							app_productos 
						WHERE 
							id in(" . $value['adicionales'] . ")";
				$itemsProduct = $this -> queryArray($sql);

				foreach ($itemsProduct['rows'] as $k => $v) {
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

			$array['rows'][$contador] = Array('id' => $value['id'], 'idproducto' => $value['idproducto'], 'status' => $value['status'], 'cantidad' => $value['cantidad'], 'nombre' => $value['nombre'] . " $items", 'precio' => $precio);
			$contador++;
		}

		return $array;
	}

///////////////// ******** ---- 	FIN getItemsPerson		------ ************ //////////////////

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
		$sql = 'UPDATE 
					com_comandas
				SET 
					total = total-' . $precio . '
				WHERE 
					id=' . $idcomanda;
		$precio = $this -> query($sql);

	// Obtiene el ID del producto
		$sql = "SELECT 
					id 
				FROM 
					com_pedidos 
				WHERE 
					id=" . $idorder . " 
				AND 
					cantidad>1";
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
					status = -1";
		$cantidad = $this -> queryArray($sql);

	// Calcula la el precio multiplicando por la cantidad
		$precio = $precio * $cantidad['rows'][0]['cantidad'];

	// Actualiza el total de la comanda
		$sql = 'UPDATE 
					com_comandas
				SET 
					total = total - ' . $precio . '
				WHERE 
					id = ' . $idcomanda;
		$precio = $this -> query($sql);

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
						status=3,
						fin='" . $fecha . "'
					WHERE 
						id=" . $idcomanda;
		$products = $this -> query($sql);

		// Actualiza los pedidos para indicar que se elimino la comanda
		$sql = "	UPDATE
						com_pedidos 
					SET
						status=3
					WHERE 
						idcomanda=" . $idcomanda;
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

		if ($row = $persons -> fetch_array()) {
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

	function addProduct($idproduct, $idperson, $idcomanda, $opcionales = '', $extras, $sin = '', $iddep, $nota_opcional = '', $nota_extra = '', $nota_sin = '') {
	// obtiene el tipo de producto y su precio
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
		$sql = 'UPDATE 
					com_comandas
				SET 
					total = total + ' . $precio . '
				WHERE 
						id = ' . $idcomanda;
		$precio = $this -> query($sql);

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
				$sql = "INSERT INTO 
							com_pedidos 
								(id, idcomanda, idproducto, cantidad, npersona, tipo, status, opcionales, adicionales,
									 sin, nota_opcional, nota_extra, nota_sin) 
						VALUES(null,'$idcomanda','$idproduct','1','$idperson','$iddep','-1','$opcionales','$extras',
								'$sin','$nota_opcional','$nota_extra', '$nota_sin')";
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
					$sql = "INSERT INTO 
								com_pedidos 
									(id, idcomanda, idproducto, cantidad, npersona, tipo, status, opcionales, adicionales,
									sin, nota_opcional, nota_extra, nota_sin) 
							VALUES
							(null,'$idcomanda','$idproduct','1','$idperson','$iddep','-1','$opcionales','$extras','$sin',
							'$nota_opcional','$nota_extra', '$nota_sin')";
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
								idProducto=' . $idproduct . ' 
							AND 
								status';
			$cancom = $this -> query($querycomanda);
			$row = $cancom -> fetch_array();
			$cantidadcomandas = $row['cantidad_comanda'];

			if ($cantidadcomandas >= $stock) {
				return array("status" => false, "msg" => 'No tienes suficiente insumos para realizar el pedido');
				exit();
			} else {
				$sql = "INSERT INTO 
							com_pedidos 
								(id, idcomanda, idproducto, cantidad, npersona, tipo, status, opcionales, adicionales, sin, 
								nota_opcional, nota_extra, nota_sin) 
						VALUES 
							(null,'$idcomanda','$idproduct','1','$idperson','$iddep','-1','$opcionales','$extras','$sin',
							'$nota_opcional','$nota_extra','$nota_sin')";
				$product = $this -> insert_id($sql);

				return $product;
			}

		}
	}

///////////////// ******** ---- 		FIN addProduct			------ ************ //////////////////

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
		
	
	// Obtiene todos los productos de la comanda
		$condicion .= (!empty($objeto['persona'])) ? ' AND a.npersona = '.$objeto['persona'] : '' ;
		
		$sql = "SELECT 
					a.npersona, SUM(a.cantidad) cantidad, b.nombre, ROUND(b.precio, 2) AS precioventa, b.id, 
					a.opcionales, a.adicionales, a.sin, c.tipo, c.nombre nombreu, c.domicilio, d.codigo, c.nombre AS nombre_mesa
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
					idcomanda = " . $idComanda . "
				AND
					a.status != 3 ".
				$condicion. "
				GROUP BY 
					a.npersona, a.idProducto, a.opcionales, a.adicionales
				ORDER BY 
					a.npersona ASC, a.id ASC";
		$productsComanda = $this -> queryArray($sql);

		$array = Array('rows', 'tipo');
		$contador = 0;

	// La comanda se cierra pagando todo junto
		if (!$bandera) {
			$array['tipo'][0] = 0;

			foreach ($productsComanda['rows'] as $value) {
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

			// Obitne los opcionales si existen
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

			// Obtiene los sin si existen
				if ($value['sin'] != "") {
					$sql = "SELECT 
								CONCAT('Sin: ',GROUP_CONCAT(nombre)) nombre 
							FROM 
								app_productos 
							WHERE 
								id IN(" . $value['sin'] . ")";
					$itemsProduct = $this -> query($sql);

				// Si hay registros agrega los nombres
					if ($row = $itemsProduct -> fetch_array())
						$items .= "(" . $row['nombre'] . ")";
				}

				$array['rows'][$contador] = Array('impuestos' => $impuestos_comanda, 'costo_extra' => $costo_extra, 'npersona' => $value['npersona'], 'cantidad' => $value['cantidad'], 'nombre' => $value['nombre'] . " $items", 'precioventa' => $value['precioventa'], 'tipo' => $value['tipo'], 'nombreu' => $value['nombreu'], 'domicilio' => $value['domicilio'], 'codigo' => $value['codigo'], 'nombre_mesa' => $value['nombre_mesa']);
				$contador++;
			}
		}

	// La comanda se cierra pagando individual
		if ($bandera == 1) {
			$array['tipo'][0] = 1;
			$impuestos_comanda = 0;
			$person = 0;
			$codigo = "";

			foreach ($productsComanda['rows'] as $value) {
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

			// Pedido
				$array['rows'][$person]['pedidos'][$contador] = Array('impuestos' => $impuestos_comanda, 'costo_extra' => $costo_extra, 'npersona' => $value['npersona'], 'cantidad' => $value['cantidad'], 'nombre' => $value['nombre'] . " $items", 'precioventa' => $value['precioventa'], 'tipo' => $value['tipo'], 'nombreu' => $value['nombreu'], 'domicilio' => $value['domicilio'], 'codigo' => $codigo);

			// Siguiente pedido
				$contador++;
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
					propina, tipo_operacion
				FROM
					com_configuracion";
		$result = $this -> queryArray($sql);

		$array['mostrar'] = $result['rows'][0]['propina'];
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

///////////////// ******** ---- 		 addTemporalTableFg		------ ************ //////////////////
// Agrega una mesa temporal para llevar
	// Como parametros puede recibir:
		// name -> nombre del cliente
		// domicilio -> domicilio del cliente

	function addTemporalTableFg($objeto) {
		session_start();
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
			
		$sql = "INSERT INTO 
					com_mesas 
						(id_mesa,idDep,personas,tipo,nombre,domicilio, x, y, idSuc, id_via_contacto) 
				VALUES 
						(null,'0','1','2','" . $objeto['nombre'] . "','" . $objeto['domicilio'] . "',-1,-1, 
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
		$actividad = $this -> query($sql);
	}

	function joinTables($jtables) {
		$tables = explode(',', $jtables);
		$contador = 0;

		$sql = "SELECT 
					a.id_mesa, if(b.id is NULL,0,b.id) id 
				FROM 
					com_mesas a 
				LEFT JOIN 
						com_comandas b 
					ON 
						b.idmesa=a.id_mesa 
					AND 
						b.status=0 
				WHERE 
					a.id_mesa in(" . $jtables . ") 
				ORDER BY 
					b.id desc";
		$idcomandas = $this -> query($sql);

		while ($row = $idcomandas -> fetch_array()) {
			$tables[$contador] = $row['id_mesa'];
			$contador++;
		}

		//** Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
		// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['mesero']['id'])) ? $_SESSION['mesero']['id'] : 0;
		$sql = "	INSERT INTO
						com_actividades
							(id, empleado, accion, fecha)
					VALUES
						(''," . $usuario . ",'Junta mesas', '" . $fecha . "')";
		$actividad = $this -> query($sql);

		foreach ($tables as $i => $key) {
			$sql = "SELECT 
						idprincipal 
					FROM 
						com_union 
					WHERE 
						idprincipal='" . $tables[0] . "' 
					AND 
						idmesa='" . $key . "'";
			$union = $this -> query($sql);

			// Guarda la union de las tablas
			if (!($row = $union -> fetch_array())) {
				$sql = "INSERT INTO 
							com_union 
								(idprincipal,idmesa) 
						VALUES 
							(" . $tables[0] . "," . $key . ")";
				$this -> query($sql);
			}
		}
	}

//** * * * *			- - - 		Actualiza el inventario de productos en stock		-	-	-	**  * * * * //

// Procesa los pedidos
	function process($idcomanda) {
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
					idcomanda =" . $idcomanda;
		$pedidos = $this -> queryArray($sql);

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
							'" . $usuario . "', 0, '" . $v['importe'] . "', 'Comanda " . $idcomanda . "')";
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
								'" . $usuario . "', 0, '" . $v['importe'] . "', 'Comanda " . $idcomanda . "')";
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
								'" . $usuario . "', 0, '" . $v['importe'] . "', 'Comanda " . $idcomanda . "')";
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
									'" . $usuario . "', 0, '" . $v['importe'] . "', 'Comanda " . $idcomanda . "')";
					$result_adicionales = $this -> query($sql);
				}
			}
		
		// Kit
			if ($value['tipo_producto'] == 6) {
				$result = $this -> actualizar_inventario_kit($value);
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
		$queryCheck = "		SELECT 
									idproducto,p.idcomanda,d.nombre lugar";
		$queryCheck .= "	FROM 
									com_pedidos p, app_departamento d, com_comandas c ";
		$queryCheck .= "	WHERE 
									p.status = 2 
								AND 
									c.status != 2 
								AND 
									p.tipo=d.id  
								AND 
									p.idcomanda=c.id  ";
		$result = $this -> queryArray($queryCheck);

		return $result;
	}

	// Consulta las mesas y su estado
	function checkTables() {
		$queryCheck = "	SELECT 
								id AS id_comanda, idmesa, personas, tipo, abierta, status ";
		$queryCheck .= " 	FROM 
								com_comandas ";
		$queryCheck .= " 	WHERE 
								status = 0 
							AND 
								tipo = 0 ";
		$result = $this -> queryArray($queryCheck);

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
						(nombre, direccion, colonia, email, celular, cp, idEstado, idMunicipio, rfc)
				VALUES
					('" . $datos['nombre'] . "', '" . $datos['direccion'] . "', '" . $datos['colonia'] . "',
					'" . $datos['mail'] . "','" . $datos['tel'] . "', '" . $datos['cp'] . "', '" . $datos['estado'] . "',	
					'" . $datos['municipio'] . "', 'XAXX010101000');";
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
					u.usuario AS empleado, c.promedioComensal
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
				RIGHT JOIN
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
					y = " . $objeto['y'] . "
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
	// id -> id del area
	// permisos -> ids de las mesas a las que tiene permiso el empleado

	function areas($objeto) {
		// Si viene el id del estado Filtra por el id del estado
		$condicion .= (!empty($objeto['id'])) ? ' AND m.idDep=\'' . $objeto['id'] . '\'' : '';

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
					1=1 " . $condicion;
		// return $sql;
		$result = $this -> queryArray($sql);
		$result = $result['rows'];

		return $result;
	}

///////////////// ******** ---- 		FIN areas					------ ************ //////////////////

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
																	p.tipo_producto != 2
																AND 
																	p.tipo_producto != 3';
	// Filtra por el orden si existe
		$condicion .= (!empty($objeto['orden'])) ? ' ORDER BY '.$objeto['orden'] : ' ORDER BY rate DESC';
	// Si no existe limite, filtra los 100 primeros
		$condicion .= (!empty($objeto['limite'])) ? ' LIMIT ' . $objeto['limite'].', 100' : ' LIMIT 0, 100';

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
					p.status=1
				AND
					tipo_producto != 3
				AND
					tipo_producto != 6 " . 
				$condicion;
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
							status = 0 AND tipo = 0
					)" . 
				$condicion;
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN mesas_libres		------ ************ //////////////////

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
		$sql = "	DELETE FROM
						com_union
					WHERE
						idprincipal=" . $objeto['mesa_origen'];
		$union = $this -> query($sql);

		return $result;
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
					c.id_venta AS venta, m.nombre AS nombre_mesa,
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
					cli.celular AS tel, v.nombre AS via_contacto_text, v.id AS id_via_contacto
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
					1 = 1 " . $condicion . " " . $agrupar . "
				ORDER BY " . $orden;
		// return $sql;
		// $result['sql'] = $sql;
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 			FIN listar_comandas			------ ************ //////////////////

///////////////// ******** ---- 			listar_empleados			------ ************ //////////////////
//////// Consulta las comandas y las regresa en un array
	// Como parametros recibe:
		// id -> id del empleado

	function listar_empleados($objeto) {
		// Si viene el id del empleado Filtra por empleado
		$condicion .= (!empty($objeto['id'])) ? ' AND idempleado=\'' . $objeto['id'] . '\'' : '';
		// Elimina los administradores del listado
		$condicion .= (!empty($objeto['vista_empleados'])) ? ' AND idperfil!=2' : '';
	
		// Ordena la consulta por los parametros indicados si existe, si no la ordena por id Descendente
		$orden .= (!empty($objeto['orden'])) ? ' ' . $objeto['orden'] : ' nombreusuario ASC';
		
		
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
		
		$sql = "SELECT
					idempleado AS id, nombreusuario AS usuario, permisos, asignacion
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

///////////////// ******** ---- 		iniciar_sesion				------ ************ //////////////////
//////// Inicia la sesion para el empleado y carga la vista con los filtros solo para el usuario
	// Como parametros puede recibir:
		//	pass -> contraseña a bsucar
		// empleado -> ID del empleado

	function iniciar_sesion($objeto) {
		require ('../../netwarelog/webconfig.php');
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
		// Filtra por status
		$condicion = (!empty($objeto['status_padre'])) ? " AND a.status != " . $objeto['status_padre'] : '';
		$condicion .= (!empty($objeto['status'])) ? " AND a.status = " . $objeto['status'] : ' AND a.status != 3';

		$sql = "SELECT 
					a.idProducto, a.cantidad, b.nombre, a.id AS pedido, b.id, 
					ROUND(b.precio, 2) AS precioventa, a.adicionales, b.ruta_imagen AS imagen,d.codigo, d.personas
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
					" . $condicion . "
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
		$sql = "	INSERT INTO
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

///////////////// ******** ---- 		actualizar_pedidos		------ ************ //////////////////
//////// Actualiza el estatus de los pedidos de la comanda padre
	// Como parametros recibe:
	// codigo -> codigo de la sub comanda
	// ids_pedidos -> cadena con los id´s de los pedidos

	function actualizar_pedidos($objeto) {
		// Actualiza el codigo si viene
		$campos = (!empty($objeto['status'])) ? " status = " . $objeto['status'] : '';

		$sql = "	UPDATE
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
	// Actualiza el status si viene
		$campos .= (!empty($objeto['status'])) ? " status = " . $objeto['status'] : '';
	// Actualiza el total si viene
		$campos .= (!empty($objeto['total'])) ? " total = " . $objeto['total'] : '';
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
		// return $sql;
		$result = $this -> query($sql);

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
						sin, nota, nota_opcional, nota_extra, nota_sin, id_sub_comanda)
					SELECT
						idcomanda, idproducto, cantidad, npersona, tipo, -1, opcionales, adicionales,
						sin, nota, nota_opcional, nota_extra, nota_sin, id_sub_comanda
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
					total = total+" . $precio . "
				WHERE
				id=" . $objeto['idcomanda'];
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
					x = -1,
					y = -1;";
		// return $sql;
		$result = $this -> dataTransact($sql);

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

///////////////// ******** ---- 	agregar_mesas				------ ************ //////////////////
//////// Inserta la mesa en la BD
	// Como parametros puede recibir:
		// pass -> contraseña a bsucar
		// num_mesas -> numero de mesas a aagregar
		// num_comensales -> numero de comensales a aagregar

	function agregar_mesas($objeto) {
		$empleado = (!empty($objeto['empleado'])) ? ', ' . $objeto['empleado'] : ', ""';
		
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
			
		$sql = "INSERT INTO
					com_mesas
					(idDep, nombre, personas, tipo, idempleado, x, y, idSuc)
				VALUES
					('1', '".$objeto['nombre']."', " . $objeto['num_comensales'] . ", 0" . $empleado . ", -1, -1, '".$sucursal."')";
		// return $sql;
		$result = $this -> insert_id($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN	agregar_mesas		------ ************ //////////////////

///////////////// ******** ---- 	listar_clientes			------ ************ //////////////////
//////// Consulta los datos de los clientes en la BD
	// Como parametros puede recibir:

	function listar_clientes($objet) {
		$orden = (!empty($objeto['orden'])) ? 'ORDER BY ' . $objeto['orden'] : 'ORDER BY cc.nombre ASC';
		$sql = "SELECT DISTINCT 
					cc.id, cc.nombre, cc.direccion, cc.celular AS tel, vc.id id_viacontacto, 
					vc.nombre via_contacto, zp.id id_zonareparto, zp.nombre zona_reparto
				FROM 
					comun_cliente cc
					left join com_mesas m on m.nombre = cc.nombre
					left join com_vias_contacto vc on vc.id = m.id_via_contacto
					left join com_zonas_reparto zp on zp.id = m.id_zona_reparto 
				WHERE
					1=1  " . $orden;
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN listar_clientes			------ ************ //////////////////

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
									ELSE '---' END) AS status, pedidos, s.id_venta
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
					1 = 1 " . $condicion . "
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
				left join
						app_pos_venta vent
					ON	
						vent.idVenta=SUBSTRING(v.referencia,6)		 	
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
		$result = $this -> queryArray($sql);

		return $result;
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
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 				FIN listar_productos_detalle		------ ************ //////////////////

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
	// Filtra por promocion si existe
		$condicion .= (!empty($objeto['id_promocion']))?' AND pro.id_promocion = '.$objeto['id_promocion']:'';
	// Filtra por tipo
		$condicion .= (!empty($objeto['tipo']))?' AND p.tipo_producto = '.$objeto['tipo']:'';
	// Ordena la consulta si existe
		$condicion .= (!empty($objeto['orden']))?' ORDER BY '.$objeto['orden']:'';
		
	// Agrupa la consulta si existe
		$condicion .= (!empty($objeto['agrupar']))?' GROUP BY '.$objeto['agrupar']:' GROUP BY p.id';
		
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
							id_producto = p.id) > 0, 1, 0) materiales, departamento AS id_departamento
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
				WHERE
					p.status = 1".
				$condicion;
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
	
	function actualizar_pedido($objeto){
		$sql = "UPDATE
					com_pedidos
				SET
					idcomanda = ".$objeto['id_comanda']."
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
					a.npersona = ".$objeto['persona']."
				AND 
					a.idcomanda = " . $objeto['id_comanda'] . " 
				GROUP BY 
					a.id";
		$result = $this -> queryArray($sql);
		
		return $result;
	}
///////////////// ******** ---- 		FIN listar_pedidos_persona			------ ************ //////////////////

///////////////// ******** ---- 			listar_pedidos					------ ************ //////////////////
//////// Obtiene los pedidos de la persona y los regresa en un array
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

} // Fin Clase comandasModel
?>