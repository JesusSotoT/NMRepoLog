<?php //ch@
require ('common.php');
require ("models/repartidores.php");

class repartidores extends Common {
	public $repartidoresModel;

	function __construct() {
		$this -> repartidoresModel = new repartidoresModel();
	}

	function pedidosRep(){
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$objeto['permisos'] = 0;
		$objeto['vista_empleados'] = 1;

		$empleados = $this -> repartidoresModel -> listar_empleados($objeto);

		// Arma el array
		$_SESSION['permisos'] = '';
		foreach ($empleados as $key => $value) {
			$_SESSION['permisos']['empleados'][$value['id']] = $value;
		}

		// Consulta los ajustes de Foodware
		$configuracion = $this -> repartidoresModel -> listar_ajustes($objeto);
		$configuracion = $configuracion['rows'][0];
	
	// Zonas de reparto
		$areas = $this -> repartidoresModel -> listar_areas();
		$areas = $areas['rows'];
		
		
		//echo json_encode($_SESSION);
		require('views/repartidores/pedidosRep.php'); 
	}
	function listar_configuracion(){
		// Consulta los ajustes de Foodware
		$configuracion = $this -> repartidoresModel -> listar_ajustes($objeto);
		$configuracion = $configuracion['rows'][0];
	}
	function listpedidosRep() {
		$idRep = $_POST['idRep'];
		$result = $this -> repartidoresModel -> listarpedidosRep($idRep);
		echo json_encode($result);
	}

	function entregado() {
		$id_comanda = $_POST['id_comanda'];
		$result = $this -> repartidoresModel -> entregado($id_comanda);
		echo json_encode($result);
	}
	function noentregado() {
		$id_comanda = $_POST['id_comanda'];
		$result = $this -> repartidoresModel -> noentregado($id_comanda);
		echo json_encode($result);
	}

	function reporteRep(){
		session_start();
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$objeto['permisos'] = $_SESSION['mesero']['permisos'];
		require('views/repartidores/reporteRep.php'); 
	}
	function listarRepartidor(){
		$result = $this -> repartidoresModel -> listarRepartidor();
		echo json_encode($result);
	}
	function save_turn($objeto){
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$result = $this -> repartidoresModel -> save_turn($objeto);
		if ($result) {
			$result['status'] = 1;
		} else {
			$result['status'] = 0;
		}
		echo json_encode($result);
	}
///////////////// ******** ---- 		listar_mesas		------ ************ //////////////////
//////// Consulta las mesas y lo agrega a la div
	// Como parametros recibe:
	// empleado -> ID del empleado
	// asignar -> variable para quitar las mesas de servicio a domicilio y para llevar
	// div -> div donde se cargara el contenido html

	function listar_mesas($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
	
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
	/*

		// Valida que las mesas sean consultadas por primera vez
		if (empty($_SESSION['permisos']['mesas'])) {
			// Consulta las mesas y las regresa en un array
			$mesas = $this -> repartidoresModel -> getTables($objeto);
			$mesas = $mesas['rows'];

			$_SESSION['permisos']['mesas'] = $mesas;
		} else {
			$mesas = $_SESSION['permisos']['mesas'];
		}
		//echo json_encode($mesas);
		//exit();
	*/
		$mesas = $this -> repartidoresModel -> getTables($objeto);
		$mesas = $mesas['rows'];

		$_SESSION['permisos']['mesas'] = $mesas;
		require ('views/repartidores/listar_mesas.php');
	}

///////////////// ******** ---- 	FIN	listar_mesas		------ ************ //////////////////

	///////////////// ******** ---- 	iniciar_sesion		------ ************ //////////////////
//////// Inicia la sesion para el empleado y carga la vista con los filtros solo para el usuario
	// Como parametros puede recibir:
	//	pass -> contraseña a bsucar
	// empleado -> ID del empleado

	function iniciar_sesion($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal

		//session_start();
		//$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		//$objeto['permisos'] = $_SESSION['mesero']['permisos'];
	
		$respuesta['status'] = 0;

		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// Consulta los datos del empleado
		$result = $this -> repartidoresModel -> iniciar_sesion($objeto);
		if ($result['total'] > 0) {
			$_SESSION['mesero'] = $result['rows'][0];

			// Valida si el mesero tiene permisos asignados o no
			// 2.- Todas las mesas
			// 1.- Filtra por los permisos que tenga el mesero
			$respuesta['status'] = (empty($result['rows'][0]['permisos'])) ? 2 : 1;
			$respuesta['permisos'] = $result['rows'][0]['permisos'];
		} else {
			$respuesta['sql'] = $result['sql'];
		}

		echo json_encode($respuesta);
	}

///////////////// ******** ---- 		FIN iniciar_sesion		------ ************ //////////////////

///////////////// ******** ---- 		listar_asignacion		------ ************ //////////////////
//////// Obtien los permisos del empleado y palome los checks correspodientes
	// Como parametros recibe:
	// empleado -> ID del empleado

	function listar_asignacion($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		$respuesta['status'] = 0;

		// Consulta los permisos del empleado y los regresa en un array
		$permisos = $_SESSION['permisos']['empleados'][$objeto['id']]['asignacion'];

		// Obtiene las mesas
		$respuesta['mesas'] = $_SESSION['permisos']['mesas'];
		//$mesas = $this -> repartidoresModel -> getTables2();
		//$respuesta['mesas'] = $mesas;

		// Comprueba si el empleado tiene permisos
		if (!empty($permisos)) {
			$respuesta['status'] = 1;
			$respuesta['permisos'] = explode(", ", $permisos);
		} else {
			$respuesta['status'] = 2;
		}

		echo json_encode($respuesta);
	}

///////////////// ******** ---- 	FIN	listar_asignacion		------ ************ //////////////////	
///////////////// ******** ---- 		asignar		------ ************ //////////////////
//////// Agrega la mesa a los permisos del empleado
	// Como parametros recibe:
	// empleado -> ID del empleado
	// id_mesa -> ID de la mesa

	function asignar($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		$resp['status'] = 0;

		// Consulta si se encuentra la mesa en el array
		$cadena = "" . $_SESSION['permisos']['empleados'][$objeto['id']]['asignacion'];
		$resp['user'] = $cadena;
		$buscar = "" . $objeto['id_mesa'];
		$resultado = strpos($cadena, $buscar);

		$resp['result'] = $resultado;

		// Elimina la mesa de los permisos
		if ($resultado !== FALSE) {
			// Elimina el id de la mesa con la coma
			$permisos = str_replace(', ' . $buscar, '', $cadena);
			// Elimina el id si esta en la primera posicion
			$permisos = str_replace($buscar, '', $permisos);
			// Limpia la cadena si hay una coma al principio
			$permisos = (0 === strpos($permisos, ', ')) ? substr($permisos, 2) : $permisos;

			// Todo bien :D
			$resp['status'] = 1;
			// Agrega la mesa de los permisos
		} else {
			// Agrega la mesa al final separada con una coma
			$permisos = (empty($cadena)) ? $buscar : $cadena . ', ' . $buscar;

			// Todo bien :D
			$resp['status'] = 1;
		}

		// Agrega los permisos al empleado
		$_SESSION['permisos']['empleados'][$objeto['id']]['asignacion'] = $permisos;

		// Regresa el resultado al ajax
		$resp['permisos'] = $permisos;
		echo json_encode($resp);
	}

///////////////// ******** ---- 		FIN asignar		------ ************ //////////////////

	///////////////// ******** ---- 		guardar_asignacion		------ ************ //////////////////
//////// Guarda los permisos de los empleados
	// Como parametros recibe:
	// empleado -> ID del empleado

	function guardar_asignacion($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// Guarda los permisos del mesero
		$resp['result'] = $this -> repartidoresModel -> guardar_asignacion($_SESSION['permisos']['empleados'][$objeto['empleado']]);
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0;

		echo json_encode($resp);
	}

///////////////// ******** ---- 		FIN guardar_asignacion		------ ************ //////////////////

}
?>