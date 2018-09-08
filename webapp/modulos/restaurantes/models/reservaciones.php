<?php
/**
 * @author Fer De La Cruz
 */

require ("models/connection_sqli.php");
// funciones mySQLi

class reservacionesModel extends Connection {

///////////////// ******** ---- 		getTables		------ ************ //////////////////
//////// Consulta las mesas y las regresa en un array
	// Como parametros recibe:
	// id -> id de la mesa
 
	function getTables($objeto) {
		//print_r($objeto);
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
		//$condicion .= (!empty($objeto['id'])) ? ' AND a.idDep=\'' . $objeto['id'] . '\' ' : '';
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
						a.id_mesa AS mesa, res.id as id_res, a.x, a.y, a.width as width_barra, a.id_area, a.height as height_barra, b.nombre, b.idDep, a.personas, a.status as mesa_status, a.tipo, a.domicilio, a.idempleado,
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
						a.id_mesa AS mesa, a.x, a.y, a.width as width_barra, a.id_area, a.height as height_barra, b.nombre, a.personas, a.status as mesa_status, a.domicilio, a.idempleado,
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
					and
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
		//print_r($sql);
		$tablesComanda = $this -> queryArray($sql);

		// return $sql;
		return $tablesComanda;
	}

///////////////// ******** ---- 		FIN getTables		------ ************ //////////////////

///////////////// ******** ---- 		getSillas		------ ************ //////////////////
//////// Consulta las mesas y las regresa en un array
	// Como parametros recibe:
	// mesa -> id de la mesa

	function getSillas($mesa) {
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
						a.id_mesa AS mesa, res.id as id_res, a.x, a.y, a.width as width_barra, a.id_area, a.height as height_barra, b.nombre, b.idDep, a.status as mesa_status, a.personas, a.domicilio, a.idempleado,
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
		//print_r($sql);
		// return $sql;
		return $sillaBarra['rows'];
	}
///////////////// ******** ---- 		FIN getSillas		------ ************ //////////////////

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
		if (count($result["rows"]) > 0) {
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
			' AND ((r.inicio BETWEEN \'' . $datos['f_ini'] . '\' AND \'' . $datos['f_fin'] . '\')
				OR (r.fin BETWEEN \'' . $datos['f_ini'] . '\' AND \'' . $datos['f_fin'] . '\'))' : '';
	// Filtra por fecha de inicio
		$condicion .= (!empty($datos['f_ini']) && empty($datos['f_fin'])) ? 
			' AND (r.inicio >= \'' . $datos['f_ini'] . ' 00:01\' AND r.inicio <=\'' . $datos['f_ini'] . ' 23:59\')' : '';

	// Ordena la consulta por los parametros indicados si existe, si no la ordena por id Descendente
		$agrupar .= (!empty($objeto['agrupar'])) ? ' GROUP BY ' . $objeto['agrupar'] : ' GROUP BY r.id';

	// Ordena la consulta por los parametros indicados si existe, si no la ordena por id Descendente
		$orden .= (!empty($datos['orden'])) ? ' ' . $datos['orden'] : ' r.id DESC';

		$sql = "SELECT 
					COUNT(r.id) AS reservaciones, r.* ,c.id AS id_cliente, c.nombre AS cliente, m.nombre AS nombre_mesa, m.tipo_mesa as tipo_mesa, 
					suc.nombre AS sucursal, com.id_venta, com.total
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
				LEFT JOIN 
						com_comandas com
					ON 
						com.idmesa = m.id_mesa 
					AND 
						com.status = 1 
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

///////////////// ******** ---- 			FIN areas				------ ************ //////////////////
	
///////////////// ******** ---- 		first_area			------ ************ //////////////////
//////// Consulta en la BD las areas que contienen las mesas
	// Como parametros recibe:

	function first_area($objeto) {


		// Filtra por los permisos del mesero

		// Selecciona todos los departamentos de las mesas
		$sql = "SELECT DISTINCT
					idDep as id, nombre AS area
				FROM 
					mrp_departamento
					limit 1";
		// return $sql;
		$result = $this -> queryArray($sql);
		$result = $result['rows'];

		return $result[0];
	}

///////////////// ******** ---- 		FIN first_area					------ ************ //////////////////
	
///////////////// ******** ---- 			listar_clientes			------ ************ //////////////////
//////// Consulta los datos de los clientes en la BD
	// Como parametros puede recibir:

	function listar_clientes($objet) {
		// orden
		$orden = (!empty($objeto['orden'])) ? 'ORDER BY ' . $objeto['orden'] : 'ORDER BY nombre ASC';

		$sql = "SELECT 
					id, nombre, celular, email
				FROM 
					comun_cliente
				WHERE
					1 = 1 " . 
				$orden;
				//print_r($sql);
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
		//print_r($datos);
		// Valida si existe la mesa o no
		$mesa = (!empty($datos['mesa'])) ? $datos['mesa'] : '';

		// Guarda la reservacion
		$sql = "	INSERT INTO
							com_reservaciones
								(inicio, descripcion, idCliente, activo, mesa, num_personas)
						VALUES
							('" . $datos['fecha'] . "','" . $datos['des'] . "','" . $datos['cliente_id'] . "', ".$datos['status'].",'" . $mesa . "', ".$datos['num_per'].")";
		//print_r($datos['cliente']);
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
		if($result && !empty($objeto['correo'])){
			$content = '<div style="width:100%; text-align: center;">';
			if (!empty($objeto['logo'])) { 
				$content = $content.'<div id="logo" style="text-align: center">
					<input type="image" src="'.$objeto['logo'].'" style="width:90%; max-width: 350px;"/>
				</div>';
			}

			$content = $content.'<br><div class="info_correo" style="margin:auto; width: 70%; text-align: center; font-size:15px;font-family: Tahoma,'."'".'Trebuchet MS'."'".',Arial;">Confirmación de Reservación en '.$objeto['organizacion'][0]['nombreorganizacion'].'.</div>';

			$content = $content.'<br><div class="info_correo" style="margin:auto; width: 70%; text-align: center; font-size:15px;font-family: Tahoma,'."'".'Trebuchet MS'."'".',Arial;">'.utf8_decode($objeto['datos_sucursal'][0]['direccion']." ".$objeto['datos_sucursal'][0]['municipio'].", ".$objeto['datos_sucursal'][0]['estado']).'</div>';

			$content = $content.'<div class="info_correo" style="margin:auto; width: 70%; text-align: center; font-size:15px;font-family: Tahoma,'."'".'Trebuchet MS'."'".',Arial;">'.$datos['fecha'].'</div>';

			$content = $content.'<br><div class="info_correo" style="margin:auto; width: 70%; text-align: center; font-size:15px;font-family: Tahoma,'."'".'Trebuchet MS'."'".',Arial;">'.$objeto['nombre'].'</div>';

			if($objeto['organizacion'][0]['paginaweb']!='-' || !empty($objeto['datos_sucursal'][0]['tel_contacto'])){
				$content = $content.'<br><br><div class="info_correo" style="margin:auto; width: 70%; text-align: center; font-size:15px;font-family: Tahoma,'."'".'Trebuchet MS'."'".',Arial;">Dudas o aclaraciones: ';
				if(!empty($objeto['datos_sucursal'][0]['tel_contacto'])){
					$content = $content.$objeto['datos_sucursal'][0]['tel_contacto'];
					if($objeto['organizacion'][0]['paginaweb']!='-'){
						$content = $content.' y ';
					}
				}
				if($objeto['organizacion'][0]['paginaweb']!='-'){
					$content = $content.$objeto['organizacion'][0]['paginaweb'];
				}
				$content = $content.'</div>';
			}
			
			$content = $content.'</div>';
			require_once('../../modulos/phpmailer/sendMail.php');

			$mail->From = "mailer@netwarmonitor.com";
			$mail->FromName = $objeto['organizacion'][0]['nombreorganizacion'];
			$mail->Subject = "Confirmación de reservación";
			$mail->AltBody = $objeto['organizacion'][0]['nombreorganizacion'];
			$mail->MsgHTML($content);
			$mail->AddAddress($objeto['correo'], $objeto['correo']);
			
			@$mail->Send();
		}
		return $result;
	}

///////////////// ******** ---- 	FIN	guardar		------ ************ ///////////////////////////////////

/////////////////////// * * * * * -- 			logo			--	* * * * * * * *  * * *  //////////////////////
	//** Consulta el logo de la empresa
	// Como parametro recibe:
	// id-> id de la empresa

	function logo($objet) {
		//$condicion .= (!empty($objet['id'])) ? ' AND idorganizacion=\'' . $objet['id'] . '\'' : '';

		$sql = "
				SELECT 
					logoempresa as logo
				FROM 
					organizaciones
				WHERE 
					1=1";
		// return $sql;
		$result = $this -> queryArray($sql);

		return $result;
	}

/////////////////////// * * * * * -- 		FIN	logo			--	* * * * * * * *  * * *  //////////////////////

	function getSucursal($objeto) {
					session_start();
			$sucursal = "	SELECT 
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
			return $sucursal;
	}

///////////////// ******** ---- 			datos_organizacion			------ ************ //////////////////
//////// Carga los datos de la organización
    public function datos_organizacion(){
        $sql = "SELECT * from organizaciones c left join estados e on e.idestado=c.idestado left join municipios m on m.idmunicipio=c.idmunicipio where idorganizacion=1";
        $result = $this->queryArray($sql);
        return $result['rows'];
    }
///////////////// ******** ---- 			FIN datos_organizacion		------ ************ //////////////////

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
						idCliente=" . $datos['cliente'] . ",
						num_personas=" . $datos['num_per'] . "
					WHERE
						id=" . $datos['id'];
		//print_r($sql);
		$result = $this -> query($sql);

		if($result){
			$content = '<div style="width:100%; text-align: center;">';
			if (!empty($objeto['logo'])) { 
				$content = $content.'<div id="logo" style="text-align: center">
					<input type="image" src="'.$objeto['logo'].'" style="width:90%; max-width: 350px;"/>
				</div>';
			}

			$content = $content.'<br><div class="info_correo" style="margin:auto; width: 70%; text-align: center; font-size:15px;font-family: Tahoma,'."'".'Trebuchet MS'."'".',Arial;">Cambio de Reservación en '.$objeto['organizacion'][0]['nombreorganizacion'].'.</div>';

			$content = $content.'<br><div class="info_correo" style="margin:auto; width: 70%; text-align: center; font-size:15px;font-family: Tahoma,'."'".'Trebuchet MS'."'".',Arial;">'.utf8_decode($objeto['datos_sucursal'][0]['direccion']." ".$objeto['datos_sucursal'][0]['municipio'].", ".$objeto['datos_sucursal'][0]['estado']).'</div>';

			$content = $content.'<div class="info_correo" style="margin:auto; width: 70%; text-align: center; font-size:15px;font-family: Tahoma,'."'".'Trebuchet MS'."'".',Arial;">'.$datos['fecha'].'</div>';

			$content = $content.'<br><div class="info_correo" style="margin:auto; width: 70%; text-align: center; font-size:15px;font-family: Tahoma,'."'".'Trebuchet MS'."'".',Arial;">'.$objeto['nombre'].'</div>';

			if($objeto['organizacion'][0]['paginaweb']!='-' || !empty($objeto['datos_sucursal'][0]['tel_contacto'])){
				$content = $content.'<br><br><div class="info_correo" style="margin:auto; width: 70%; text-align: center; font-size:15px;font-family: Tahoma,'."'".'Trebuchet MS'."'".',Arial;">Dudas o aclaraciones: ';
				if(!empty($objeto['datos_sucursal'][0]['tel_contacto'])){
					$content = $content.$objeto['datos_sucursal'][0]['tel_contacto'];
					if($objeto['organizacion'][0]['paginaweb']!='-'){
						$content = $content.' y ';
					}
				}
				if($objeto['organizacion'][0]['paginaweb']!='-'){
					$content = $content.$objeto['organizacion'][0]['paginaweb'];
				}
				$content = $content.'</div>';
			}
			
			$content = $content.'</div>';
			require_once('../../modulos/phpmailer/sendMail.php');

			$mail->From = "mailer@netwarmonitor.com";
			$mail->FromName = $objeto['organizacion'][0]['nombreorganizacion'];
			$mail->Subject = "Cambio de reservación";
			$mail->AltBody = $objeto['organizacion'][0]['nombreorganizacion'];
			$mail->MsgHTML($content);
			$mail->AddAddress($objeto['correo'], $objeto['correo']);
			
			@$mail->Send();
		}
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

///////////////// ******** ---- 	bloquear_mesa		------ ************ //////////////////
	// Elimina la comanda, elimina los pedidos, elimina la reservacion(si existe)
	// Como parametros puede recibir:
	// $idmesa -> ID de la mesa

	function bloquear_mesa($idmesa, $mesa_status) {
		if($mesa_status == 1){
			$sql = "	UPDATE
								com_mesas
							SET
								status = 4
							WHERE 
								id_mesa=" . $idmesa;
		} else {
			$sql = "	UPDATE
								com_mesas
							SET
								status = 1
							WHERE 
								id_mesa=" . $idmesa;
		}
		return $this -> query($sql);

	}

///////////////// ******** ---- 	FIN bloquear_mesa		------ ************ //////////////////

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
		if($objeto['tipo'] == 2){
			$sql = "UPDATE
								comun_cliente
							SET
								nombre = '".$objeto['nombre_edi']."', 
								celular = '".$objeto['tel_edi']."', 
								email = '".$objeto['mail_edi']."' 
							WHERE id = ".$objeto['id_cli'];
			$result = $this -> query($sql);
			if($result)
				$result = $objeto['id_cli'];
		} else {
			$sql = "
						INSERT INTO 
							comun_cliente(nombre, direccion, colonia, email, celular, cp, idEstado, idMunicipio, rfc)
						VALUES('" . $objeto['nombre'] . "', '" . $objeto['direccion'] . "', '" . $objeto['colonia'] . "',
								'" . $objeto['mail'] . "', '" . $objeto['tel'] . "', '" . $objeto['cp'] . "', '" . $objeto['estado'] . "',
								'" . $objeto['municipio'] . "', 'XAXX010101000'
						);";
			$result = $this -> insert_id($sql);
		}
		$sql = "Select id, nombre, celular, email from comun_cliente where id = ".$result;
		$result = $this -> queryArray($sql);
		return $result['rows'][0];
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