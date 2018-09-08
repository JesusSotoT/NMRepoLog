<?php
/**
 * @author Fer De La Cruz
 */

require ("models/connection_sqli.php");
// funciones mySQLi

class reservacionesModel extends Connection {

///////////////// ******** ---- 		listar		------ ************ //////////////////
//////// Consulta las reservaciones
	// Como parametros recibe:
		// fini-> fecha de inicio
		// ffin-> fecha de final

	function listar($objeto) {
	// Anti hack
		foreach ($objeto as $key => $value) {
			$datos[$key] = $this -> escapalog($value);
		}

	// Filtra por la sucursal si existe
		$condicion .= ($objeto['sucursal'] != '*' && !empty($objeto['sucursal'])) ? 
			' AND m.idSuc = \'' . $objeto['sucursal'] . '\'' : '';
	// Filtra por el ID de la comanda si existe
		$condicion .= (!empty($datos['comanda'])) ? ' AND idprincipal=\'' . $datos['comanda'] . '\'' : '';
	// Si viene el id del empleado Filtra por empleado
		$condicion .= ($objeto['cliente'] != '*' && !empty($objeto['cliente'])) ? 
			' AND r.idCliente = \'' . $objeto['cliente'] . '\'' : '';
	// Si viene el id de la mesa Filtra por la mesa
		$condicion .= ($objeto['mesa'] != '*' && !empty($objeto['mesa'])) ? 
			' AND m.id_mesa=\'' . $objeto['mesa'] . '\'' : '';
	// Filtra por el status si existe
		$condicion .= (!empty($datos['status'])) ? ' AND r.activo=' . $datos['status'] : '';
	// Filtra por fecha de inicio y fin si existen
		$condicion .= (!empty($datos['f_ini']) && !empty($datos['f_fin'])) ? 
			' AND ((inicio BETWEEN \'' . $datos['f_ini'] . '\' AND \'' . $datos['f_fin'] . '\')
				OR (fin BETWEEN \'' . $datos['f_ini'] . '\' AND \'' . $datos['f_fin'] . '\'))' : '';
	// Filtra por fecha de inicio
		$condicion .= (!empty($datos['f_ini']) && empty($datos['f_fin'])) ? 
			' AND (inicio >= \'' . $datos['f_ini'] . ' 00:01\' AND fin <=\'' . $datos['f_ini'] . ' 23:59\')' : '';

	// Ordena la consulta por los parametros indicados si existe, si no la ordena por id Descendente
		$agrupar .= (!empty($objeto['agrupar'])) ? ' GROUP BY ' . $objeto['agrupar'] : ' GROUP BY id';

	// Ordena la consulta por los parametros indicados si existe, si no la ordena por id Descendente
		$orden .= (!empty($datos['orden'])) ? ' ' . $datos['orden'] : ' id DESC';

		$sql = "SELECT 
					COUNT(r.id) AS reservaciones, r.* , c.nombre AS cliente, m.nombre AS nombre_mesa, suc.nombre AS sucursal
				FROM 
					com_reservaciones r
				LEFT JOIN
						comun_cliente c
					ON
						c.id = r.idCliente
				LEFT JOIN
						com_mesas m 
					ON
						m.id_mesa = r.mesa
				LEFT JOIN
						mrp_sucursal suc
					ON
						suc.idSuc = m.idSuc
				LEFT JOIN
						accelog_usuarios u
					ON
						m.idempleado = u.idempleado
				WHERE 
					1=1 " . 
					$condicion . " " . 
				$agrupar . "
				ORDER BY " . 
					$orden;
		// return $sql;
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN listar					------ ************ //////////////////

///////////////// ******** ---- 		valida_reservaciones		------ ************ //////////////////
//////// Valida si se debe de mostrar el boton de reservaciones, si no lo oculra
	// Como parametros recibe:

	function valida_reservaciones($objeto) {
		$sql = "	SELECT
						reservaciones
					FROM
						com_configuracion";
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN valida_reservaciones		------ ************ //////////////////

///////////////// ******** ---- 				areas					------ ************ //////////////////
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
		$sql = "
					SELECT DISTINCT
						m.idDep id, d.nombre area
					FROM 
						com_mesas m
					INNER JOIN
							mrp_departamento d
						ON
							m.idDep=d.idDep
					WHERE 1=1 " . $condicion . "
					AND
						m.status!=2 ";
		// return $sql;
		$result = $this -> queryArray($sql);
		$result = $result['rows'];

		return $result;
	}

///////////////// ******** ---- 			FIN areas				------ ************ //////////////////
	
///////////////// ******** ---- 			listar_clientes			------ ************ //////////////////
//////// Consulta los datos de los clientes en la BD
	// Como parametros puede recibir:

	function listar_clientes($objet) {
		// orden
		$orden = (!empty($objeto['orden'])) ? 'ORDER BY ' . $objeto['orden'] : 'ORDER BY nombre ASC';

		$sql = "SELECT 
					id, nombre
				FROM 
					comun_cliente
				WHERE
					1 = 1 " . 
				$orden;
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN listar_clientes			------ ************ //////////////////

///////////////// ******** ---- 		guardar						------ ************ //////////////////
//////// Guarda la reservacion
	// Como parametros recibe:
	// cliente -> ID del cliente
	// fecha -> fecha y hora de la reservacion
	// btn -> boton del loader

	function guardar($objeto) {
		// Anti hack
		foreach ($objeto as $key => $value) {
			$datos[$key] = $this -> escapalog($value);
		}

		// Valida si existe la mesa o no
		$mesa = (!empty($datos['mesa'])) ? $datos['mesa'] : '';

		// Guarda la reservacion
		$sql = "	INSERT INTO
							com_reservaciones
								(inicio, descripcion, idCliente, activo, mesa)
						VALUES
							('" . $datos['fecha'] . "','" . $datos['des'] . "','" . $datos['cliente'] . "', -1,'" . $mesa . "')";
		$result = $this -> insert_id($sql);

		// Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
		// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['accelog_idempleado'])) ? $_SESSION['accelog_idempleado'] : 0;
		$sql = "	INSERT INTO
							com_actividades
								(empleado, accion, fecha)
						VALUES
							(" . $usuario . ",'Agrega reservacion', '" . $fecha . "')";
		$actividad = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN	guardar		------ ************ ///////////////////////////////////

///////////////// ******** ---- 		asignar		------ ************ //////////////////
//////// Asigna una mesa a la reservacion
	// Como parametros recibe:
	// mesa -> ID de la mesa
	// id -> ID de la reservacion

	function asignar($objeto) {
		// Anti hack
		foreach ($objeto as $key => $value) {
			$datos[$key] = $this -> escapalog($value);
		}

		// Actualiza la reservacion para marcar que ya fue asignada
		$sql = "	UPDATE
							com_reservaciones
						SET
							mesa=" . $datos['mesa'] . ",
							activo=1
						WHERE
							id=" . $datos['id'];
		$result = $this -> query($sql);

		// Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
		// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['accelog_idempleado'])) ? $_SESSION['accelog_idempleado'] : 0;
		$sql = "	INSERT INTO
							com_actividades
								(id, empleado, accion, fecha)
						VALUES
							(''," . $usuario . ",'Asigna reservacion', '" . $fecha . "')";
		$actividad = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN	asignar		------ ************ ///////////////////////////////////

///////////////// ******** ---- 				terminar				------ ************ //////////////////
//////// Termina la reservacion
	// Como parametros recibe:
	// id -> ID de la reservacion

	function terminar($objeto) {
		// Actualiza la reservacion para indicar que ya termino
		$sql = "	UPDATE
						com_reservaciones
					SET
						activo=0
					WHERE
						id=" . $objeto['id'];
		$result = $this -> query($sql);

		// Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
		// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['accelog_idempleado'])) ? $_SESSION['accelog_idempleado'] : 0;
		$sql = "	INSERT INTO
						com_actividades
							(id, empleado, accion, fecha)
					VALUES
						(''," . $usuario . ",'Termina reservacion', '" . $fecha . "')";
		$actividad = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 				FIN	terminar			------ ************ //////////////////

///////////////// ******** ---- 				actualizar			------ ************ //////////////////
//////// Actualizar la reservacion
	// Como parametros recibe:
	// cliente -> ID del cliente
	// fecha -> fecha y hora de la reservacion
	// btn -> boton del loader
	// id -> ID de la reservacion

	function actualizar($objeto) {
		// Anti hack
		foreach ($objeto as $key => $value) {
			$datos[$key] = $this -> escapalog($value);
		}

		// Guarda la reservacion
		$sql = "	UPDATE
						com_reservaciones
					SET
						inicio='" . $datos['fecha'] . "',
						descripcion='" . $datos['des'] . "',
						idCliente=" . $datos['cliente'] . "
					WHERE
						id=" . $datos['id'];
		$result = $this -> query($sql);

		// Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
		// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['accelog_idempleado'])) ? $_SESSION['accelog_idempleado'] : 0;
		$sql = "	INSERT INTO
						com_actividades
							(empleado, accion, fecha)
					VALUES
						(" . $usuario . ",'Modifica reservacion', '" . $fecha . "')";
		$actividad = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN	actualizar		------ ************ ///////////////////////////////////

///////////////// ******** ---- 				eliminar				------ ************ //////////////////
//////// Elimina la reservacion
	// Como parametros recibe:
	// id -> ID de la reservacion

	function eliminar($objeto) {
		// Actualiza la reservacion para marcar que ya fue asignada
		$sql = "	UPDATE
							com_reservaciones
						SET
							activo=2
						WHERE
							id=" . $objeto['id'];
		$result = $this -> query($sql);

		// Guarda la actividad
		$fecha = date('Y-m-d H:i:s');
		// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['accelog_idempleado'])) ? $_SESSION['accelog_idempleado'] : 0;
		$sql = "	INSERT INTO
							com_actividades
								(id, empleado, accion, fecha)
						VALUES
							(''," . $usuario . ",'Cancela reservacion', '" . $fecha . "')";
		$actividad = $this -> query($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN	eliminar		------ ************ ///////////////////////////////////
///////////////// ******** ---- 		guardar_cliente		------ ************ //////////////////
//////// Agrega un cliente a la base de datos en la tabla comun_cliente
	// Como parametros puede recibir:
		// Campos del formulario:
		// -> Nombre, Direccion, Numero interios, Numero Exterior
		// -> Colonia, CP, estado, Municipio, E-mail, Tel

	function guardar_cliente($objeto) {
		$sql = "
					INSERT INTO 
						comun_cliente(nombre, direccion, colonia, email, celular, cp, idEstado, idMunicipio, rfc)
					VALUES('" . $objeto['nombre'] . "', '" . $objeto['direccion'] . "', '" . $objeto['colonia'] . "',
							'" . $objeto['mail'] . "', '" . $objeto['tel'] . "', '" . $objeto['cp'] . "', '" . $objeto['estado'] . "',
							'" . $objeto['municipio'] . "', 'XAXX010101000'
					);";
		$result = $this -> insert_id($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN	guardar_cliente		------ ************ ///////////////////////////////////

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

///////////////// ******** ---- 			listar_mesas					------ ************ //////////////////
//////// Consulta las mesas y las regresa en un array
	// Como parametros recibe:
	// id -> id de la mesa

	function listar_mesas($objeto) {
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
			
		$sql = "SELECT 
					a.nombre AS nombre_mesa,
					a.id_mesa mesa, 
					a.x,
					a.y,
					b.nombre, a.personas, 
					a.tipo,
					a.domicilio,
					a.idempleado,
					ad.nombreusuario AS mesero,
					IF(GROUP_CONCAT(c.idmesa) is NULL, a.nombre, (SELECT 
																		GROUP_CONCAT(a.nombre)
																	FROM
																		com_mesas a
																	INNER JOIN
																			com_union c
																		ON
																			c.idmesa=a.id_mesa
																))
						nombre_mesa,
					if(GROUP_CONCAT(c.idmesa) IS NULL,'',GROUP_CONCAT(c.idmesa)) 
						idmesas, 
					if(GROUP_CONCAT(d.personas) IS NULL,'',GROUP_CONCAT(d.personas)) 
						mpersonas, 
				if(e.id IS NULL, 0, e.id) 
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
		// return $sql;
		$tablesComanda = $this -> queryArray($sql);
		
		return $tablesComanda;
	}

///////////////// ******** ---- 			FIN listar_mesas				------ ************ //////////////////
}
?>