<?php 
require ("models/connection_sqli.php");
// funciones mySQLi

session_start();
class repartidoresModel extends Connection {

// ch@
	function listarpedidosRep($idRep) {

				$filtro = '1 = 1';
		$idRep1         = implode(',', $idRep);

		if($idRep1!=""){
            if($idRep1=='0'){
                $filtro .='';
            }else{
                $filtro .=' and (au.idempleado IN ('.$idRep1.'))';
            }
        }

		$sql = "SELECT br.*, concat(au.nombre,' ',au.apellidos) nombre from com_bit_repartidores br
				left join administracion_usuarios au on au.idempleado = br.id_repartidor where $filtro;";
		$pedidos = $this -> queryArray($sql);
		return $pedidos['rows'];
	}
	function entregado($id_comanda) {
		date_default_timezone_set('America/Mexico_City');
		$f_entregado = date('Y-m-d H:i:s');
		$sql = "UPDATE com_bit_repartidores 
				SET fecha_pedido_entregado = '$f_entregado', estatus = 2 
				WHERE id_comanda = '$id_comanda';";
		return $this->query($sql);
	}
	function noentregado($id_comanda) {
		date_default_timezone_set('America/Mexico_City');
		$f_noentregado = date('Y-m-d H:i:s');
		$sql = "UPDATE com_bit_repartidores 
				SET fecha_pedido_entregado = '$f_noentregado', estatus = 4 
				WHERE id_comanda = '$id_comanda';";
		return $this->query($sql);
	}

	function listarRepartidor() {

		$sql = "SELECT DISTINCT au.idempleado id, concat(au.nombre,' ',au.apellidos) nombre 
				from com_bit_repartidores br
				left join administracion_usuarios au on au.idempleado = br.id_repartidor;";
		$pedidos = $this -> queryArray($sql);
		return $pedidos['rows'];
	}

	function getTables2(){
		$sql = "SELECT c.* from com_comandas c 
				left join com_mesas m on m.id_mesa = c.idmesa
				where (c.status  = 1 or c.status = 0) and m.status = 1 and m.tipo = 2;";
		$pedidos = $this -> queryArray($sql);
		return $pedidos;
	}

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
		$condicion .= ($objeto['asignar'] == 1) ? ' AND a.tipo=2' : '';

		$sql = "	SELECT 
						a.id_mesa AS mesa, a.x, a.y, b.nombre, a.personas, a.tipo, a.domicilio, a.idempleado,
						ad.nombreusuario AS mesero, a.notificacion, 
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
							(e.status = 0 or e.status = 2 )
					LEFT JOIN com_bit_repartidores cbr on cbr.id_comanda = e.id 
					WHERE(
							a.id_mesa 
								NOT IN(select idmesa from com_union) 
						OR 
							a.id_mesa 
								IN(select idprincipal from com_union) 
					)" . $condicion . " 
					AND 
						(a.status = 1 or a.status = 2)
					AND
						a.idSuc = " . $sucursal ."
					AND
						cbr.fecha_pedido_asignado is null
					AND 
						e.id is not null	
					GROUP BY 
						a.id_mesa 
					ORDER BY 
						a.id_mesa asc";
		$tablesComanda = $this -> queryArray($sql);
		//
		// return $sql;
		return $tablesComanda;
	}

///////////////// ******** ---- 		FIN listar_mesas		------ ************ //////////////////


	///////////////// ******** ---- 			listar_empleados			------ ************ //////////////////
//////// Consulta las comandas y las regresa en un array
	// Como parametros recibe:
		// id -> id del empleado

	function listar_empleados($objeto) {
		// Si viene el id del empleado Filtra por empleado
		$condicion .= (!empty($objeto['id'])) ? ' AND idempleado=\'' . $objeto['id'] . '\'' : '';
		// Elimina los administradores del listado
		//$condicion .= (!empty($objeto['vista_empleados'])) ? ' AND idperfil!=2' : '';
	
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
		date_default_timezone_set('America/Mexico_City');
		$f_asignacion = date('Y-m-d H:i:s');
		
		// Escapa para evitar hack
		$asignacion = $this -> escapalog($objeto['asignacion']);
		$idrep = $this -> escapalog($objeto['id']);

		$arrayAsig = explode(",", $asignacion);
		
		foreach($arrayAsig as $key => $value){ // ordenamiento
				$sql = "INSERT INTO com_bit_repartidores 
						(id, id_repartidor, id_comanda, fecha_pedido_asignado, estatus) 
						VALUES 
							('','$idrep','$value','" . $f_asignacion . "','1');";
				$asignacion = $this -> insert_id($sql);
            }
		return $asignacion;		
		exit();

		
	}

///////////////// ******** ---- 		FIN guardar_asignacion		------ ************ //////////////////

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

///////////////// ******** ---- 		FIN listar_ajustes			------ ************ //////////////////


}