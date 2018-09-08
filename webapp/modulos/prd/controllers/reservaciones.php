<?php
/**
 * @author Fer De La Cruz
 */

require ('common.php');
require ("models/reservaciones.php");

class reservaciones extends Common {
	public $reservacionesModel;

	function __construct() {
		$this -> reservacionesModel = new reservacionesModel();
	}
	
///////////////// ******** ---- 	vista_reservaciones		------ ************ //////////////////
//////// Carga la vista en la que se consultan las reservaciones

	function vista_reservaciones($objeto) {
	// Carga la vista reservaciones
	// Consulta las sucursales y las regresa en un array
		$sucursales = $this -> reservacionesModel -> listar_sucursales($objeto);
		$sucursales = $sucursales['rows'];

	// Consulta los empleado sy los regresa en un array
		$clientes = $this -> reservacionesModel -> listar_clientes($objeto);
		$clientes = $clientes['rows'];
		
	// Consulta las mesas y las regresa en un array
		$mesas = $this -> reservacionesModel -> listar_mesas($objeto);
		$mesas = $mesas['rows'];

		require ('views/reservaciones/vista_reservaciones.php');
	}

///////////////// ******** ---- 	FIN	vista_reservaciones		------ ************ //////////////////

///////////////// ******** ---- 				listar			------ ************ //////////////////
//////// Consulta las reservaciones
	// Como parametros recibe:
		// fini-> fecha de inicio
		// ffin-> fecha de final

	function listar($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Carga la vista
		if ($objeto['vista'] == 1) {
		// Formatea la fecha y la hora
			$objeto['f_ini'] = str_replace('T', ' ', $objeto['f_ini']).' 00:01';
			$objeto['f_fin'] = str_replace('T', ' ', $objeto['f_fin']).' 23:59';

		// Consulta las reservaciones y las regresa en un array
			$reservaciones = $this -> reservacionesModel -> listar($objeto);
			$total = $reservaciones['total'];
			$reservaciones = $reservaciones['rows'];

		// ** Grafica lineal
		// Consulta las reservaciones y las regresa en un array para la grafica lineal
			$objeto['agrupar'] = 'WEEK(inicio)';
			$reservaciones_2 = $this -> reservacionesModel -> listar($objeto);
			$reservaciones_2 = $reservaciones_2['rows'];
	
		// Arma el array para la grafica lineal
			foreach ($reservaciones_2 as $key => $value) {
				$lineal[$key]['reservaciones'] = $value['reservaciones'];
				$lineal[$key]['activo'] = $value['activo'];
				$lineal[$key]['inicio'] = $value['inicio'];
			}

		// ** Grafica de dona
		// Calcula el total de cada status de las reservaciones para la grafica
			foreach ($reservaciones as $key => $value) {
				$datos['Creadas'] += ($value['activo'] == -1) ? 1 : 0;
				$datos['Cerradas'] += ($value['activo'] == 0) ? 1 : 0;
				$datos['Activas'] += ($value['activo'] == 1) ? 1 : 0;
				$datos['Canceladas'] += ($value['activo'] == 2) ? 1 : 0;
			}
		
		// Datos dona
			$dona[0] = array('label' => 'Creadas', 'value' => $datos['Creadas']);
			$dona[0] = array('label' => 'Activas', 'value' => $datos['Activas']);
			$dona[1] = array('label' => 'Cerradas', 'value' => $datos['Cerradas']);
			$dona[2] = array('label' => 'Canceladas', 'value' => $datos['Canceladas']);

		// carga la vista para listar las reservaciones
			require ('views/reservaciones/listar.php');
			// Regresa un json
		} else {
		// Consulta las reservaciones
			$resp['result'] = $this -> reservacionesModel -> listar($objeto);

		// 1 -> Todo bien :)
		// 2 -> Fallo la consulta :(
			$resp['status'] = (!empty($resp['result'])) ? 1 : 0;

		// Regresa las reservaciones al ajax
			echo json_encode($resp);
		}
	}

///////////////// ******** ---- 				FIN listar			------ ************ //////////////////

///////////////// ******** ---- 		valida_reservaciones		------ ************ //////////////////
//////// Valida si se debe de mostrar el boton de reservaciones, si no lo oculta
	// Como parametros recibe:

	function valida_reservaciones($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// Consulta los datos de la mesa en la DB
		$result = $this -> reservacionesModel -> valida_reservaciones($objeto);

		// Regresa al ajax el mensaje
		echo json_encode($result);
	}

///////////////// ******** ---- 		FIN valida_reservaciones	------ ************ //////////////////

///////////////// ******** ---- 		mapa_reservaciones			------ ************ //////////////////
//////// Carga la vista del mapa de las reservaiones

	function mapa_reservaciones($objeto) {
		// Carga la vista del mapa de reservaciones
		$objeto['f_ini'] = date('Y-m-d') . ' 00:01';
		$objeto['f_fin'] = date('Y-m-d') . ' 23:59';
		// Filtra para que no aparescan las mesas de servicio a domicilio y para llevar
		$objeto['asignar'] = 1;

		// Consulta las reservaciones
		$reservaciones = $this -> reservacionesModel -> listar($objeto);
		$reservaciones = $reservaciones['rows'];

		// Consulta los clientes
		$clientes = $this -> reservacionesModel -> listar_clientes($objeto);
		$clientes = $clientes['rows'];

		// Valida que la funcion se llame por primera vez o al momento de unir las mesas
		if ($_SESSION['area'] != 1) {
			// Consulta las mesas
			$mesas = $this -> reservacionesModel -> listar_mesas($objeto);
			$mesas = $mesas['rows'];

			$_SESSION['mesas'] = $mesas;
		}

		// Consulta los datos de la mesa en la DB
		$areas = $this -> reservacionesModel -> areas($objeto);

		require ('views/reservaciones/mapa_reservaciones.php');
	}

///////////////// ******** ---- 		FIN	mapa_reservaciones		------ ************ //////////////////

///////////////// ******** ---- 		areas		------ ************ //////////////////
//////// Obtiene el listado de las areas en las que estan las mesas
	// Como parametros recibe:
		// id -> id del area

	function areas($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Consulta los datos de la mesa en la DB
		$result = $this -> reservacionesModel -> listar_mesas($objeto);
		$result = $result['rows'];
	
		session_start();
		$_SESSION['mesas'] = $result;
		$_SESSION['area'] = 1;

	// Regresa al ajax el mensaje
		echo json_encode($_SESSION['mesas']);
	}

///////////////// ******** ---- 		FIN areas		------ ************ //////////////////

///////////////// ******** ---- 		guardar		------ ************ //////////////////
//////// Guarda la reservacion
	// Como parametros recibe:
	// cliente -> ID del cliente
	// fecha -> fecha y hora de la reservacion
	// btn -> boton del loader

	function guardar($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// Si el cliente no existe lo agrega
		if (!is_numeric($objeto['cliente'])) {
			$objeto['nombre'] = $objeto['cliente'];
			$objeto['cliente'] = $this -> reservacionesModel -> guardar_cliente($objeto);
		}

		// Sino existe la fecha agrega la actual
		if (empty($objeto['fecha'])) {
			// Establece la zona horaria
			date_default_timezone_set('America/Mexico_City');
			$objeto['fecha'] = date('Y-m-d H:i:s');
		}

		// Consulta las reservaciones
		$resp['result'] = $this -> reservacionesModel -> guardar($objeto);

		// 1 -> Todo bien :)
		// 2 -> Fallo la consulta :(
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0;

		echo json_encode($resp);
	}

///////////////// ******** ---- 	FIN	guardar		------ ************ //////////////////

///////////////// ******** ---- 		listar_pendientes		------ ************ //////////////////
//////// Consulta las reservaciones y las agrega a un div
	// Como parametros recibe:
		// fecha -> fecha y hora del dia
		// div -> div donde se cargara el contenido html

	function listar_pendientes($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Consulta las reservaciones
		$reservaciones = $this -> reservacionesModel -> listar($objeto);
		$reservaciones = $reservaciones['rows'];

		session_start();
	// Consulta las mesas si no existen
		if (empty($_SESSION['mesas'])) {
			$mesas = $this -> reservacionesModel -> listar_mesas($objeto);
			$_SESSION['mesas'] = $mesas['rows'];
		}

	// La funcion es llamada desde la lista de espera, carga una vista diferente a la ordinaria de solo informacion
		if ($objeto['lista_espera'] == 1) {
			require ('views/reservaciones/listar_espera.php');

			return 0;
		}
	
	// carga la vista para listar las reservaciones
		require ('views/reservaciones/listar_pendientes.php');
	}

///////////////// ******** ---- 				FIN listar_pendientes			------ ************ //////////////////

///////////////// ******** ---- 		listar_reservaciones		------ ************ //////////////////
//////// Consulta las reservaciones y las agrega a un div
	// Como parametros recibe:
		// fecha -> fecha y hora del dia
		// div -> div donde se cargara el contenido html

	function listar_reservaciones($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Consulta las reservaciones
		$reservaciones = $this -> reservacionesModel -> listar($objeto);
		$reservaciones = $reservaciones['rows'];

		date_default_timezone_set('America/Mexico_City');
		$fecha = date('Y-m-d H:i:s');
		$fechaActualR = date_parse($fecha);
		
		foreach ($reservaciones as $key => $val) {
			$resercacion = $val['inicio'];
			$fechaReservacionR = date_parse($resercacion);
			$hora = substr($val['inicio'],11);
			
			if($fechaActualR > $fechaReservacionR){
				$reservaciones1[] = array(							
					cliente          => $val['cliente'],
                    inicio           => $hora,							                             
                );  
			}  
		}
		echo json_encode($reservaciones1);
	}

///////////////// ******** ---- 				FIN listar_reservaciones			------ ************ //////////////////	

///////////////// ******** ---- 						asignar					------ ************ //////////////////
//////// Manda llamar a la funcion que actualiza la reservacion
	// Como parametros recibe:
	// mesa -> ID de la mesa
	// id -> ID de la reservacion

	function asignar($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// ** Valida que no este asignada esta mesa
		session_start();
		if (!empty($_SESSION['activas'][$objeto['mesa']])) {
			$resp['status'] = 2;
		} else {
			// Agrega la reservacion al array de las reservaciones activas
			$_SESSION['activas'][$objeto['mesa']] = $objeto;

			// Consulta las reservaciones
			$resp['result'] = $this -> reservacionesModel -> asignar($objeto);

			// 1 -> Todo bien :)
			// 2 -> Fallo la consulta :(
			$resp['status'] = (!empty($resp['result'])) ? 1 : 0;
		}

		echo json_encode($resp);
	}

///////////////// ******** ---- 						FIN asignar				------ ************ //////////////////

///////////////// ******** ---- 				status_reservaciones			------ ************ //////////////////
//////// Consulta las reservaciones y las agrega a un div
	// Como parametros recibe:
	// fecha -> fecha y hora del dia
	// div -> div donde se cargara el contenido html

	function status_reservaciones($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// Consulta las reservaciones
		$resp['result'] = $this -> reservacionesModel -> listar($objeto);
		$resp['result'] = $resp['result']['rows'];

		// 1 -> Todo bien :)
		// 2 -> Fallo la consulta :(
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0;

		echo json_encode($resp);
	}

///////////////// ******** ---- 				FIN status_reservaciones		------ ************ //////////////////

///////////////// ******** ---- 				terminar				------ ************ //////////////////
//////// Termina la reservacion
	// Como parametros recibe:
	// id -> ID de la reservacion
	// btn -> Nombre del boton del loader
	// mesa -> ID de la mesa

	function terminar($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// Consulta las reservaciones
		$resp['result'] = $this -> reservacionesModel -> terminar($objeto);

		// Elimina la mesa del array
		session_start();
		$_SESSION['activas'][$objeto['mesa']] = '';

		// 1 -> Todo bien :)
		// 2 -> Fallo la consulta :(
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0;

		echo json_encode($resp);
	}

///////////////// ******** ---- 				FIN terminar		------ ************ //////////////////

///////////////// ******** ---- 				actualizar			------ ************ //////////////////
//////// Actualizar la reservacion
	// Como parametros recibe:
	// cliente -> ID del cliente
	// fecha -> fecha y hora de la reservacion
	// btn -> boton del loader
	// id -> ID de la reservacion

	function actualizar($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// Consulta las reservaciones
		$resp['result'] = $this -> reservacionesModel -> actualizar($objeto);

		// 1 -> Todo bien :)
		// 2 -> Fallo la consulta :(
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0;

		echo json_encode($resp);
	}

///////////////// ******** ---- 			FIN	actualizar		------ ************ //////////////////

///////////////// ******** ---- 				eliminar				------ ************ //////////////////
//////// Elimina la reservacion
	// Como parametros recibe:
	// id -> ID de la reservacion

	function eliminar($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// Consulta las reservaciones
		$resp['result'] = $this -> reservacionesModel -> eliminar($objeto);

		// 1 -> Todo bien :)
		// 2 -> Fallo la consulta :(
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0;

		echo json_encode($resp);
	}

///////////////// ******** ---- 				FIN eliminar			------ ************ //////////////////

///////////////// ******** ---- 			guardar_cliente				------ ************ //////////////////
//////// Agrega un cliente a la base de datos en la tabla comun_cliente
	// Como parametros puede recibir:
	// Campos del formulario:
	// -> Nombre, Direccion, Numero interios, Numero Exterior
	// -> Colonia, CP, estado, Municipio, E-mail, Tel

	function guardar_cliente($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// Consulta las reservaciones
		$resp['result'] = $this -> reservacionesModel -> guardar_cliente($objeto);

		// 1 -> Todo bien :)
		// 2 -> Fallo la consulta :(
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0;

		echo json_encode($resp);
	}

///////////////// ******** ---- 			FIN	guardar_cliente			------ ************ //////////////////
}
?>