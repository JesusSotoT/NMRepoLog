<?php

require ("models/connection_sqli.php");
// funciones mySQLi

class pedidosActivosModel extends Connection {

///////////////// ******** ----        getLugares              ------ ************ //////////////////
	// Consulta los departamentos y los devuelve en un array
	// Actualiza el inventario.
	// Como parametros puede recibir:

	function getLugares() {
		$sql = "  SELECT 
					id, nombre AS lugar
				FROM 
					app_departamento";
		$lugares = $this -> queryArray($sql);

		return $lugares;
	}

///////////////// ******** ----        FIN getLugares  ------ ************ //////////////////

	function getPedidos($post) {
		$tipo = $post["tipo"];
		/* Obtenemos los productos de cada comanda pero solo las que no estan abiertas en alguna pantalla,
		 y que no esten terminadas, tambien solo se obtienen las comandas que pertenecen a tu area de trabajo.

		 ejem. cocina o bar.  */
		 
		$sql = "SELECT
					tipo_operacion
				FROM
					com_configuracion";
		$tipo_operacion = $this -> queryArray($sql);
		$tipo_operacion = $tipo_operacion['rows'][0]['tipo_operacion'];

		if (!empty($post['sucursal'])) {
			$sucursal = $post['sucursal'];
		} else {
		// Obtiene la sucursal del usuario actual
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
	
	// 1- > Muestra solo los pedidos de comandas abiertas
		$condicion = ($tipo_operacion == 1) ? ' AND com.status = 0' : '';

		$myQuery = "	SELECT 
        					idcomanda AS comanda, com.idmesa, cp.id AS producto, cp.id, cp.nota_opcional, cp.nota_extra,
        					cp.nota_sin, npersona AS persona, cp.tipo AS departamento, mp.nombre AS descripcion, timestamp, 
        					cp.status, cp.cantidad, cp.opcionales, cp.adicionales, cp.sin, m.nombre AS nombre_mesa, 
        					cp.inicio, cp.fin, m.tipo, m.domicilio, cli.celular AS tel, mp.tipo_producto, 
        					mp.id AS id_producto, cp.tiempo AS tiempo_platillo
        				FROM 
        					 com_pedidos cp, app_productos mp, com_comandas com
        				LEFT JOIN
        						com_mesas m
        					ON
        						com.idmesa=m.id_mesa
						LEFT JOIN
								comun_cliente cli
							ON
								cli.nombre = m.nombre
        				WHERE 
        					cp.idcomanda=com.id 
        				AND  
        					cp.tipo = " . $tipo . " 
        				AND  
        					mp.id = cp.idproducto " . $condicion . " 
        				AND  
        					cp.id != 0 
						AND
							m.idSuc = " . $sucursal . "
        				AND  
        					(cp.status = 0 OR cp.status = 1)
        				GROUP BY
        					cp.id, cp.idproducto, opcionales, adicionales, sin
        				ORDER BY 
        					cp.tiempo DESC, timestamp, idcomanda, npersona FOR UPDATE";
		// return $myQuery;
		$pedidos = $this -> queryArray($myQuery);

		if ($pedidos["total"] > 0) {
			$ids = array();

			foreach ($pedidos["rows"] as $key => $value) {
			// Guarda el inicio del pedido en los pedidod nuevos
				if (empty($value['inicio'])) {
					date_default_timezone_set('America/Mexico_City');
					$fecha = date('Y-m-d H:i:s');

					$sql = "UPDATE 
	            				com_pedidos
	            			SET 
	            				inicio = '" . $fecha . "'
	            			WHERE 
	            				id = " . $value["producto"];
					$inicio = $this -> query($sql);

					$pedidos["rows"][$key]["inicio"] = $fecha;
				}

				$ids[] = $value["comanda"];
				$pedidos["rows"][$key]["opcionalesDesc"] = '';
				$pedidos["rows"][$key]["adicionalesDesc"] = '';

				$opcionalesCadena = '';
				$adicionalesCadena = '';
				$sin_cadena = '';

			// ** Opcionales
			//Si el producto tiene opcionales marcados, consultamos la descripcion del opcional.
				if ($value["opcionales"] != '') {
					$opcionalesCadena = 'Con: ';

					$selectOpcional = "		SELECT 
                    							nombre,descripcion_larga AS deslarga ";
					$selectOpcional .= "	FROM
                    							app_productos ";
					$selectOpcional .= "	WHERE 
                    							id in (" . $value["opcionales"] . ")  ";
					$opcionales = $this -> queryArray($selectOpcional);

					foreach ($opcionales["rows"] as $key2 => $producto) {
						$opcionalesCadena .= '/ >' . $producto["nombre"];
					}
				}

			// Si tiene nota extra la agrega
				$opcionalesCadena .= ($value["nota_opcional"]) ? ' [' . $value["nota_opcional"] . ']' : '';
				$pedidos["rows"][$key]["opcionalesDesc"] = $opcionalesCadena;

			// ** Extras
			//Si el producto tiene extra marcados, consultamos la descripcion del producto.
				if ($value["adicionales"] != '') {
					$adicionalesCadena = 'Extras: ';

					$selectAdicional = "	SELECT 
                    							nombre, descripcion_larga AS deslarga ";
					$selectAdicional .= " 	FROM 
                    							app_productos ";
					$selectAdicional .= " 	WHERE 
                    							id IN (" . $value["adicionales"] . ")";
					$adicionales = $this -> queryArray($selectAdicional);

					foreach ($adicionales["rows"] as $key2 => $producto) {
						$adicionalesCadena .= '/ >' . $producto["nombre"];
					}
				}

			// Si tiene nota extra la agrega
				$adicionalesCadena .= ($value["nota_extra"]) ? ' [' . $value["nota_extra"] . ']' : '';
				$pedidos["rows"][$key]["adicionalesDesc"] = $adicionalesCadena;

			// ** Sin
			//Si el producto tiene extra marcados, consultamos la descripcion del producto.
				if ($value["sin"] != '') {
					$sin_cadena = 'Sin: ';

					$sin = "	SELECT 
                    					nombre, descripcion_larga AS deslarga ";
					$sin .= " 	FROM 
                    					app_productos ";
					$sin .= " 	WHERE 
                    					id in (" . $value["sin"] . ")";
					$sin = $this -> queryArray($sin);

					foreach ($sin["rows"] as $key2 => $producto) {
						$sin_cadena .= '/ > ' . $producto["nombre"];
					}
				}

				$pedidos["rows"][$key]["sin_desc"] = $sin_cadena;

			// Si existe una nota sin la agregamos al array
				if (!empty($value["nota_sin"])) {
					$pedidos["rows"][$key]["nota_sin"] = $value["nota_sin"];
				}

			// Se bloquea el producto para no cargarlo cada que se manda llamar la funcion
				$productoOcupado = "UPDATE 
            							com_pedidos
            						SET 
            							status = 1
            						WHERE 
            							id = " . $value["producto"] . "
									AND
										status=0;";
				$result = $this -> query($productoOcupado);
			
			// Kit
				if ($value['tipo_producto'] == 6) {
					$pedidos["rows"][$key]["desc_kit"] = $this -> desc_kit($value);
				}
			}

			$ids = array_unique($ids);
			$ids = implode(",", $ids);

			/* Marcamos las comandas como abiertas = 1, para que no esten disponibles en otras pantallas, sin afectar el estatus de terminado.
			 */

			$update = "		UPDATE 
            					com_comandas ";
			$update .= "	SET 
            					abierta = 1 ";
			$update .= "	WHERE 
            					id in(" . $ids . ") ";
			$update = $this -> query($update);

			if ($update) {
				return $pedidos;
			}
		} else {
			return $pedidos;
		}
	}

	function getPedidosReprint($post) {
		$tipo = $post["tipo"];
		/*
		 Obtenemos los productos de cada comanda pero solo las que no estan abiertas en alguna pantalla,
		 y que no esten terminadas, tambien solo se obtienen las comandas que pertenecen a tu area de trabajo.

		 ejem. cocina o bar.
		 */

		$condicion .= (!empty($post['pedidos'])) ? ' AND cp.id IN (' . $post['pedidos'] . ')' : '';

		$myQuery = "	SELECT 
        					idcomanda AS comanda, com.idmesa, cp.id producto, cp.id,
        					npersona AS persona, cp.tipo AS departamento, mp.nombre AS descripcion, timestamp,
        					com.status, cp.cantidad, cp.opcionales, cp.adicionales, cp.sin, m.domicilio, cli.celular AS tel, m.tipo,
        					mp.id AS id_producto
        				FROM 
        					com_pedidos cp, app_productos mp, com_comandas com 
        				LEFT JOIN
        						com_mesas m
        					ON
        						com.idmesa = m.id_mesa
						LEFT JOIN
								comun_cliente cli
							ON
								cli.nombre = m.nombre
        				WHERE 
        					cp.idcomanda = " . $tipo . " 	
        				AND 
        					com.id = " . $tipo . " 	
        				AND  
        					mp.id = cp.idproducto 
        				AND  
        					cp.id != 0" . $condicion . "
        				GROUP BY
        					cp.id
        				ORDER BY 
        					timestamp, idcomanda, npersona FOR UPDATE";
		// return $myQuery;
		$pedidos = $this -> queryArray($myQuery);

		$ids = array();

		foreach ($pedidos["rows"] as $key => $value) {
			$ids[] = $value["comanda"];
			$pedidos["rows"][$key]["opcionalesDesc"] = '';
			$pedidos["rows"][$key]["adicionalesDesc"] = '';
			$opcionalesCadena = '';
			$adicionalesCadena = '';
			$sin_cadena = '';

			// ** Opcionales
			//Si el producto tiene opcionales marcados, consultamos la descripcion del opcional.
			if ($value["opcionales"] != '') {
				$opcionalesCadena = 'Con:';

				$selectOpcional = "	SELECT
										nombre, descripcion_larga AS deslarga 
									FROM
										app_productos
									WHERE
										id IN (" . $value["opcionales"] . ")  ";
				$opcionales = $this -> queryArray($selectOpcional);

				foreach ($opcionales["rows"] as $key2 => $producto) {
					$opcionalesCadena .= '/ >' . $producto["nombre"];
				}
			}

			// Si tiene nota extra la agrega
			$opcionalesCadena .= ($value["nota_opcional"]) ? ' [' . $value["nota_opcional"] . ']' : '';
			$pedidos["rows"][$key]["opcionalesDesc"] = $opcionalesCadena;

			// ** Extras
			//Si el producto tiene extra marcados, consultamos la descripcion del producto.
			if ($value["adicionales"] != '') {
				$adicionalesCadena = 'Extras: ';

				$selectAdicional = "	SELECT
											nombre, descripcion_larga AS deslarga 
										FROM
											app_productos 
										WHERE
											id IN (" . $value["adicionales"] . ")";
				$adicionales = $this -> queryArray($selectAdicional);

				foreach ($adicionales["rows"] as $key2 => $producto) {
					$adicionalesCadena .= '/ >' . $producto["nombre"];
				}
			}

			// Si tiene nota extra la agrega
			$adicionalesCadena .= ($value["nota_extra"]) ? ' [' . $value["nota_extra"] . ']' : '';
			$pedidos["rows"][$key]["adicionalesDesc"] = $adicionalesCadena;

			// ** Sin
			//Si el producto tiene extra marcados, consultamos la descripcion del producto.
			if ($value["sin"] != '') {
				$sin_cadena = 'Sin:';

				$sin = "	SELECT
									nombre, descripcion_larga AS deslarga 
								FROM
									app_productos 
								WHERE
									id IN (" . $value["sin"] . ")";
				$sin = $this -> queryArray($sin);

				foreach ($sin["rows"] as $key2 => $producto) {
					$sin_cadena .= '/ >' . $producto["nombre"];
				}
			}

			$pedidos["rows"][$key]["sin_desc"] = $sin_cadena;

		// Si existe una nota sin la agregamos al array
			if (!empty($value["nota_sin"])) {
				$pedidos["rows"][$key]["nota_sin"] = $value["nota_sin"];
			}
			
		// Kit
			if ($value['tipo_producto'] == 6) {
				$pedidos["rows"][$key]["desc_kit"] = $this -> desc_kit($value);
			}
		}

		return $pedidos;
	}

	function productoTerminado($post) {
		session_start();
		// Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
		$sql = "	INSERT INTO
					com_actividades
						(id, empleado, accion, fecha)
				VALUES
					(''," . $_SESSION['accelog_idempleado'] . ",'Termina pedido(cocina)', '" . $fecha . "')";
		$actividad = $this -> query($sql);

		$post = str_replace('p', '', $post["id"]);
		$array = explode('-', $post);
		$comanda = $array[0];
		$producto = $array[1];

		session_start();
		//Se obtiene la sucursal
		$qry = "SELECT 
					au.idSuc,
	           		mp.nombre
	           	FROM
	           		administracion_usuarios au
	          	INNER JOIN
	           		mrp_sucursal mp 
	           	ON 
	           		mp.idSuc = au.idSuc
	           	WHERE 
	          		au.idempleado = " . $_SESSION['accelog_idempleado'] . "
	           	LIMIT 1";

		$idSuc = $this -> query($qry);
		$row = $idSuc -> fetch_array();
		$sucursal = $row['idSuc'];

		//Se obtiene el almacen
		$query = "SELECT
	          		s.idSuc, 
	          		s.nombre sucursal,
          			a.idAlmacen ,
           			a.nombre
           		FROM
           			mrp_sucursal s,
           			almacen a 
           		WHERE
           			s.idAlmacen=a.idAlmacen 
           		AND 
           			s.idSuc=" . $sucursal;

		$idalm = $this -> query($query);
		$row = $idalm -> fetch_array();
		$almacen = $row['idAlmacen'];

		//Se obtiene el id_producto
		$query = "SELECT
	          		idProducto
           		FROM
           			com_pedidos
           		WHERE
           			id=" . $producto . " 
           		AND 
           			idcomanda=" . $comanda;

		$id_producto = $this -> query($query);
		$id_producto = $id_producto -> fetch_array();
		$id_producto = $id_producto['idProducto'];

		// Actualizamos los ocupados en almacen para que no aparescan en el listado
		$sql = "UPDATE
					mrp_stock
				SET
					ocupados=(ocupados-1)
				WHERE
					idProducto=" . $id_producto . "
				AND
					idAlmacen=" . $almacen;
		$modificado = $this -> query($sql);

		//Cambiamos el estatus del producto por '2' que es terminado
		$update = "		UPDATE 
        					com_pedidos ";
		$update .= "	SET 
        					status=2 ";
		$update .= "	WHERE 
        					id=" . $producto . " 
        				AND 
        					idcomanda=" . $comanda;
		$result = $this -> query($update);

		return json_encode($result);
	}

	function quitarProducto($post) {
		$post = str_replace('p', '', $post["id"]);
		$array = explode('-', $post);
		$comanda = $array[0];
		$producto = $array[1];

		// Consulta el precio del producto
		$sql = '	SELECT 
					idcomanda, adicionales, (	SELECT ROUND(if(SUM(e.valor) is null,p.precio,
													((SUM(e.valor)/100)*p.precio)+p.precio),2) precioventa 
												FROM 
													producto_impuesto e 
												WHERE 
													e.idProducto=p.id
											) precio
				FROM 
					app_productos p
				INNER JOIN
					com_pedidos pe
				WHERE 
					pe.idproducto=p.id
				AND
					pe.id=' . $producto;
		$result = $this -> queryArray($sql);
		$precio = $result['rows'][0]['precio'];
		$idcomanda = $result['rows'][0]['idcomanda'];
		$adicionales = $result['rows'][0]['adicionales'];

		// Obtiene el costo de los materiales extra si existen
		if (!empty($adicionales)) {
			$sql = "	SELECT SUM(
						(	SELECT ROUND(if(SUM(e.valor) is null,b.precio,
								((SUM(e.valor)/100)*b.precio)+b.precio),2) precioventa 
							FROM 
								producto_impuesto e 
							WHERE 
								e.idProducto=b.id
						)) costo_extra 
					FROM 
						app_productos b
					WHERE
						b.id IN(" . $adicionales . ");";
			$result['sql_extra'] = $sql;
			$result['extra'] = $this -> queryArray($sql);
			$result['extra'] = $result['extra']['rows'][0]['costo_extra'];

			$precio += $result['extra'];
		}

		// Actualiza el total de la comanda
		$sql = '	UPDATE 
					com_comandas
				SET 
					total = total-' . $precio . '
				WHERE 
					id = ' . $idcomanda;
		$precio = $this -> query($sql);

		//Eliminamos el producto de la tabla de com_pedidos para que no se vea en la comanda
		$update = "		UPDATE 
        					com_pedidos ";
		$update .= "	SET 
        					status = 3 ";
		$update .= "	WHERE 
        					id = " . $producto . " 
        				AND 
        					idcomanda = " . $comanda . "";
		$result = $this -> dataTransact($update);

		session_start();
		// Guarda la actividad
		$fecha = date('Y-m-d H:i:s');

		$sql = "	INSERT INTO
					com_actividades
						(id, empleado, accion, fecha)
				VALUES
					(''," . $_SESSION['accelog_idempleado'] . ",'Cancela pedido(cocina)', '" . $fecha . "')";
		// return json_encode($sql);
		$actividad = $this -> query($sql);

		return json_encode($result);
	}

	function cancelarComanda($post) {

		$id = str_replace('C', '', $post["id"]);

		//Se eliminan los productos de la tabla de com_pedidos
		$update = "		UPDATE 
        					com_pedidos ";
		$update .= "	SET 
        					status = 3 ";
		$update .= "	WHERE 
        					idcomanda = " . $id . ";";

		//Se elimina el registro de la comanda de com_comandas
		$update .= "	UPDATE 
        					com_comandas ";
		$update .= "	SET 
        					status = 3 ";
		$update .= "	WHERE 
        					id = " . $id . ";";
		$result = $this -> dataTransact($update);

		return $result;
	}

	function terminarComanda($post) {
		$id = str_replace('T', '', $post["id"]);

		//Modificamos el status de los productos de la comanda a 2
		$update = "		UPDATE 
        					com_pedidos ";
		$update .= "	SET 
        					status = 3 ";
		$update .= "	WHERE 
        					idcomanda = " . $id . "";
		$result = $this -> query($update);

		return $result;
	}

	function serachPropina($term) {
		if (is_numeric($term)) {
			$where .= " id = " . $term . " ";
		} else {
			$where .= " nombre LIKE '%" . $term . "%' ";
		}

		$searchProducto = "		SELECT 
        							id AS value, nombre AS label";
		$searchProducto .= " 	From 
        							app_productos ";
		$searchProducto .= " 	WHERE  
        							$where";
		$result = $this -> query($searchProducto);

		while ($row = $result -> fetch_object()) {
			$rows[] = array('label' => $row -> label, 'value' => $row -> value);
		}

		return json_encode($rows);
	}

	function addidPropina($id) {
		$queryDelete = " 	DELETE FROM 
        						com_productos_propina ";
		$result = $this -> query($queryDelete);

		$queryInsert = "	INSERT INTO 
        						com_productos_propina";
		$queryInsert .= " 	VALUES 
        						(0," . $id . ") ";
		$result = $this -> query($queryInsert);

		return $result;
	}

///////////////// ******** ---- 		mostrar_propina				------ ************ //////////////////
//////// Actualiza el campo de "propina" en la BD
	// Como parametros recibe:
	// mostrar: valor del Checkbox

	function mostrar_propina($objeto) {
		$sql = "	UPDATE
					com_configuracion
				SET
					propina = " . $objeto['mostrar'];
		$result = $this -> query($sql);

		// Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
		$empleado = (!empty($_SESSION['accelog_idempleado'])) ? $_SESSION['accelog_idempleado'] : 0;
		$sql = "	INSERT INTO
						com_actividades
							(id, empleado, accion, fecha)
					VALUES
						(''," . $empleado . ",'Cambia mostrar propina', '" . $fecha . "')";
		$actividad = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN mostrar_propina			------ ************ //////////////////

///////////////// ******** ---- 		mostrar_consumo				------ ************ //////////////////
//////// Llama una funcion a la base de datos y le manda el estatus del check
	// Como parametros recibe:
	// mostrar: valor del Checkbox

	function mostrar_consumo($objeto) {
		$sql = "	UPDATE
					com_configuracion
				SET
					consumo = " . $objeto['mostrar'];
		$result = $this -> query($sql);

		// Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
		$empleado = (!empty($_SESSION['accelog_idempleado'])) ? $_SESSION['accelog_idempleado'] : 0;
		$sql = "	INSERT INTO
						com_actividades
							(id, empleado, accion, fecha)
					VALUES
						(''," . $empleado . ",'Cambia mostrar consumo', '" . $fecha . "')";
		$actividad = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN mostrar_consumo			------ ************ //////////////////

///////////////// ******** ---- 		listar_ajustes		------ ************ //////////////////
//////// Consulta el estatus de la propina en la BD
	// Como parametros recibe:

	function listar_ajustes($objeto) {
		$sql = "	SELECT
					*
				FROM
					com_configuracion";
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN listar_ajustes		------ ************ //////////////////

///////////////// ******** ----                        terminar                        ------ ************ //////////////////
//////// Termina el pedido
	// Como parametros puede recibir:
	// adicionales -> cadena con los id de los productos extras
	// adicionalesDesc -> descripcion de los productos extras
	// cantidad -> cantidad del pedido
	// comanda -> ID de la comanda
	// departamento -> ID del departamento
	// descripcion -> nombre del producto
	// idproducto -> ID del producto
	// Sin -> cadena con los id de los productos sin
	// adicionales_desc -> descripcion de los productos extras
	// nota_sin -> nota de los productos sin
	// opcionales -> cadena con los id de los productos opcionales
	// opcionalesDesc -> descripcion de los productos extras
	// persona -> numero de persona
	// producto -> ID del pedido

	function terminar($objeto) {
		date_default_timezone_set('America/Mexico_City');
		$fecha = date('Y-m-d H:i:s');

		$sql = "  UPDATE 
						com_pedidos
					SET 
						status = 2, 
						fin = '" . $fecha . "'
					WHERE
						id = " . $objeto['producto'];
		$result = $this -> query($sql);

		return $result;
	}

///////////////// ******** ----                        FIN terminar            ------ ************ //////////////////

///////////////// ******** ----                        eliminar                ------ ************ //////////////////
//////// Elimina el pedido
	// Como parametros puede recibir:
	// adicionales -> cadena con los id de los productos extras
	// adicionalesDesc -> descripcion de los productos extras
	// cantidad -> cantidad del pedido
	// comanda -> ID de la comanda
	// departamento -> ID del departamento
	// descripcion -> nombre del producto
	// idproducto -> ID del producto
	// Sin -> cadena con los id de los productos sin
	// adicionales_desc -> descripcion de los productos extras
	// nota_sin -> nota de los productos sin
	// opcionales -> cadena con los id de los productos opcionales
	// opcionalesDesc -> descripcion de los productos extras
	// persona -> numero de persona
	// producto -> ID del pedido

	function eliminar($objeto) {
		// Consulta el precio del producto
		$sql = '	SELECT 
							idcomanda, p.id AS idproducto, pe.cantidad,
							ROUND(p.precio, 2) AS precio, adicionales, p.formulaieps AS formula
						FROM  
							app_productos p
						INNER JOIN
							com_pedidos pe
						WHERE 
							pe.idproducto = p.id
						AND
							pe.id = ' . $objeto['producto'];
		$result = $this -> queryArray($sql);
		$extras = $result['rows'][0]['adicionales'];
		$idcomanda = $result['rows'][0]['idcomanda'];

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

		// Optiene los costos de los productos extra si existen
		if (!empty($result['rows'][0]['adicionales'])) {
			$sql = '	SELECT 
							ROUND(b.precio, 2) AS precioventa, id
						FROM 
							app_productos b
						WHERE
							id in(' . $result['rows'][0]['adicionales'] . ')';
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
		$sql = '	UPDATE 
							com_comandas
						SET 
							total = total-' . $precio . '
						WHERE 
							id = ' . $idcomanda;
		$precio = $this -> query($sql);

		// Actualiza el status del pedido y su fecha final
		date_default_timezone_set('America/Mexico_City');
		$fecha = date('Y-m-d H:i:s');
		$sql = "  UPDATE 
						com_pedidos
					SET 
						status = 3, 
						fin = '" . $fecha . "'
					WHERE
						id = " . $objeto['producto'];
		$result = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 				FIN eliminar           			 ------ ************ //////////////////

///////////////// ******** ---- 			actualizar_configuracion			------ ************ //////////////////
//////// Actualiza la configuracion de Foodware
	// Como parametros recibe:
		// tipo -> tipo de operacion 1: Terminar Pedidos Después de Pago, 2: Mantener Pedidos Después de Pago
		// pedir_pass -> 1 -> debe pedir el password, 2 -> no

	function actualizar_configuracion($objeto) {
	// Actualiza el campo de tipo de operacion si existe
		$campos .= (!empty($objeto['tipo'])) ? ' tipo_operacion = '.$objeto['tipo'] : '' ;
	// Actualiza el campo de pedir pass si existe
		$campos .= (!empty($objeto['pedir_pass'])) ? ' pedir_pass = '.$objeto['pedir_pass'] : '' ;
	// Actualiza si se deben de mostrar los dolares
		$campos .= (!empty($objeto['mostrar_dolares'])) ? ' mostrar_dolares = '.$objeto['mostrar_dolares'] : '' ;
	// Actualiza si se debe de mostrar la informacion de la comanda
		$campos .= (!empty($objeto['mostrar_info_comanda'])) ? ' mostrar_info_comanda = '.$objeto['mostrar_info_comanda'] : '' ;
		
		$sql = "UPDATE
					com_configuracion
				SET" . 
					$campos;
		$result = $this -> query($sql);

	// Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
		$empleado = (!empty($_SESSION['accelog_idempleado'])) ? $_SESSION['accelog_idempleado'] : 0;
		$sql = "	INSERT INTO
						com_actividades
							(id, empleado, accion, fecha)
					VALUES
						(''," . $empleado . ",'Cambia la configuracion', '" . $fecha . "')";
		$actividad = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN actualizar_configuracion		------ ************ //////////////////

///////////////// ******** ---- 			listar_impuestos				------ ************ //////////////////
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

///////////////// ******** ---- 		FIN listar_impuestos			------ ************ //////////////////

///////////////// ******** ---- 			desc_kit					------ ************ //////////////////
//////// Consulta los pedidos del kit, forma una cadena con la informacion y la regresa
	// Como parametros recibe:

		function desc_kit($objeto) {
			$desc = '';
			
			$sql = "SELECT 
						pe.*, p.tipo_producto, p.nombre
					FROM 
						com_pedidos_kit pe
					LEFT JOIN
							app_productos p
						ON
							p.id = pe.id_producto
					WHERE
						id_pedido = ".$objeto['id'];
			// return $sql;
			$pedidos = $this -> queryArray($sql);
			
			foreach ($pedidos["rows"] as $key => $value) {
			$desc .='<br>'.$value['nombre'];
			
			// ** Opcionales
			//Si el producto tiene opcionales marcados, consultamos la descripcion del opcional.
				if ($value["opcionales"] != '') {
					$desc .= ' >Con: ';
					$selectOpcional = "	SELECT 
                    						nombre, descripcion_larga AS deslarga
                    					FROM
                    						app_productos 
                    					WHERE 
                    						id in (" . $value["opcionales"] . ")  ";
					$opcionales = $this -> queryArray($selectOpcional);

					foreach ($opcionales["rows"] as $key2 => $producto) {
						$desc .= '/ ' . $producto["nombre"];
					}
					
					if ($producto["nota_opcional"]) {
						$desc .= ' >>' . $producto["nota_opcional"];	
					}
				}
				
			// ** Extras
			//Si el producto tiene extra marcados, consultamos la descripcion del producto.
				if ($value["extras"] != '') {
					$desc .= ' >Extras: ';

					$selectAdicional = "SELECT 
                    						nombre, descripcion_larga AS deslarga 
                    					FROM 
                    						app_productos
                    					WHERE 
                    						id IN (" . $value["extras"] . ")";
					$adicionales = $this -> queryArray($selectAdicional);

					foreach ($adicionales["rows"] as $key2 => $producto) {
						$desc .= '/ ' . $producto["nombre"];
					}
					
					if ($producto["nota_extra"]) {
						$desc .= ' >>' . $producto["nota_extra"];	
					}
				}
				
			// ** Sin
			//Si el producto tiene extra marcados, consultamos la descripcion del producto.
				if ($value["sin"] != '') {
					$desc .= ' >Sin: ';

					$sin = "SELECT 
                    			nombre, descripcion_larga AS deslarga
                    		FROM 
                    			app_productos 
                    		WHERE 
                    			id in (" . $value["sin"] . ")";
					$sin = $this -> queryArray($sin);

					foreach ($sin["rows"] as $key2 => $producto) {
						$desc .= '/ ' . $producto["nombre"];
					}
					
					
					if ($producto["nota_sin"]) {
						$desc .= ' >>' . $producto["nota_sin"];	
					}
				}
			}

			return $desc;
		}
	///////////////// ******** ---- 			FIN desc_kit				------ ************ //////////////////
}
?>