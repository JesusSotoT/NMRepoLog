<?php
/**
 * @author Fer De La Cruz
 */

// require('common.php');
require ("controllers/comandas.php");
require ("models/configuracion.php");

class configuracion extends comandas {
	public $configuracionModel;
	public $comandasModel;

	function __construct() {
		$this -> configuracionModel = new configuracionModel();
		$this -> comandasModel = $this -> configuracionModel;
	}

///////////////// ******** ---- 	vista_seguridad				------ ************ //////////////////
//////// Carga la vista en la que se configura una contraseña de seguridad
	// Como parametro puede recibir:

	function vista_seguridad($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// Consulta los datos de la mesa en la DB
		$result = $this -> configuracionModel -> pass($objeto);

		// Obtiene la contraseña de seguridad
		$pass = $result['rows'][0]['seguridad'];

		// Carga la vista del promedio por comensal
		require ('views/configuracion/vista_seguridad.php');
	}

///////////////// ******** ---- 	FIN	vista_seguridad			------ ************ //////////////////

///////////////// ******** ---- 	guardar_pass				------ ************ //////////////////
//////// Guarda la nueva contraseña en la BD
	// Como parametro puede recibir:
	// pass1 -> contraseña
	// pass2 -> debe coincidir con pass1

	function guardar_pass($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// Consulta los datos de la mesa en la DB
		$result = $this -> configuracionModel -> guardar_pass($objeto);

		// regresa el resultado al ajax
		echo json_encode($result);
	}

///////////////// ******** ---- 	FIN	guardar_pass		------ ************ //////////////////

///////////////// ******** ---- 	pass		------ ************ //////////////////
//////// Optiene la contraseña de seguridad
	// Como parametro puede recibir:

	function pass($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// Consulta los datos de la mesa en la DB
		$result = $this -> configuracionModel -> pass($objeto);

		// Obtiene la contraseña de seguridad
		$pass = $result['rows'][0]['seguridad'];

		// regresa la contraseña al ajax
		echo json_encode($pass);
	}

///////////////// ******** ---- 			FIN	pass			------ ************ //////////////////

///////////////// ******** ---- 		vista_productos			------ ************ //////////////////
//////// Carga la vista de los platillos a configurar
	// Como parametro puede recibir:

	function vista_productos($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		$productos = $this -> comandasModel -> getProducts(0, 0, 0);
		$productos = $productos['rows'];

		// Carga la vista del promedio por comensal
		require ('views/configuracion/vista_productos.php');
	}

///////////////// ******** ---- 		FIN vista_productos		------ ************ //////////////////

///////////////// ******** ---- 		guardar_platillo		------ ************ //////////////////
//////// Manda llamar a la funcion que guarda el horario del platillo
	// Como parametros recibe:
	// id -> ID del producto
	// dias -> cadena con los numeros de los dias (0,1,2,3,4,5,6) de domingo a lunes
	// inicio -> hora de inicio
	// fin -> hora final

	function guardar_platillo($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		$resp['result'] = $this -> configuracionModel -> guardar_platillo($objeto);

		// 1 -> Todo bien :)
		// 2 -> Fallo la consulta :(
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0;

		echo json_encode($resp);
	}

///////////////// ******** ---- 	FIN	guardar_platillo		------ ************ //////////////////

///////////////// ******** ---- 		eliminar_platillo		------ ************ //////////////////
//////// Elimina los dias y el horario de un platillo
	// Como parametros recibe:
	// id -> ID del producto

	function eliminar_platillo($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		$resp['result'] = $this -> configuracionModel -> eliminar_platillo($objeto);

		// 1 -> Todo bien :)
		// 2 -> Fallo la consulta :(
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0;

		echo json_encode($resp);
	}

///////////////// ******** ---- 	FIN	eliminar_platillo			------ ************ //////////////////

///////////////// ******** ---- 	vista_promociones				------ ************ //////////////////
//////// Carga la vista de las promociones
	// Como parametro puede recibir:

	function vista_promociones($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// Carga la vista de las promociones
		require ('views/configuracion/vista_promociones.php');
	}

///////////////// ******** ---- 	FIN	vista_promociones			------ ************ //////////////////

///////////////// ******** ---- 		vista_nueva					------ ************ //////////////////
//////// Consulta los productos, las configuracion y las agrega a un div
	// Como parametros recibe:
	// div -> div donde se cargara el contenido html
	// btn -> boton del loader

	function vista_nueva($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$panel = $objeto['panel'];

		// Consulta los productos
		// $objeto['tipo'] = 1;
		$productos = $this -> configuracionModel -> listar_productos($objeto);
		$productos = $productos['rows'];

		// Inicializa el array de los productos agregados
		session_start();
		$_SESSION['productos_agregados'] = '';

		// Carga la vista de las configuracion
		require ('views/configuracion/nueva_promocion.php');
	}

///////////////// ******** ---- 		FIN	vista_nueva				------ ************ //////////////////

///////////////// ******** ---- 		agregar_producto			------ ************ //////////////////
//////// Agrega un producto al array de los productos agregados y carga la vista donde aparecen
	// Como parametros recibe:
		// idProducto -> ID del producto
		// div -> ID de la div donde se cargara la vista
		// nombre -> nombre del producto
		// precio -> precio del producto

	function agregar_producto($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		session_start();

	// Si no existe el producto lo agrega al array,  si existe lo elimina
		if (!empty($_SESSION['productos_agregados']['productos'][$objeto['id']])) {
			unset($_SESSION['productos_agregados']['productos'][$objeto['id']]);
		} else {
			$_SESSION['productos_agregados']['productos'][$objeto['id']] = $objeto;
		}
		
	// Valida si se debe carga la vista por default u otra
		$vista = (!empty($objeto['vista'])) ? $objeto['vista'] : 'listar_productos_agregados' ;
	
	// Carga la vista
		require ('views/configuracion/'.$vista.'.php');
	}

///////////////// ******** ---- 	FIN agregar_producto			------ ************ //////////////////

///////////////// ******** ---- 		guardar_promocion			------ ************ //////////////////
//////// Manda llamar a la funcion que guarda la promocion y los productos de la promocion
	// Como parametros puede recibir:
		// nombre -> nombre de la promocion
		// dias -> string con los numeros de los dias
		// tipo -> 1 -> por descuento, 2 -> por cantidad
		// descuento(si es tipo 1) -> porcentaje de descuento
		// cantidad(si es tipo 2) -> cantidad de productos
		// cantidad_descuento(si es tipo 2) -> cantidad que se descuenta
		// inicio -> hora de inicio
		// fin -> hora final

	function guardar_promocion($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		session_start();
	// Sin productos
		if(empty($_SESSION['productos_agregados']['productos'])){
			$resp['status'] = 2;
			
			echo json_encode($resp);
			
			return 0;
		}
		
	// llama a la funcion que guarda la promocion en la BD y obtiene el ID de la insercion
		$id_promocion = $this -> configuracionModel -> guardar_promocion($objeto);
		
	// Guarda los productos de la promocion en la BD
		foreach ($_SESSION['productos_agregados']['productos'] as $key => $value) {
			$value['id_promocion'] = $id_promocion;
			$resp['result'] = $this -> configuracionModel -> guardar_producto_promocion($value);
		}
		
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0 ;
		
		echo json_encode($resp);
	}

///////////////// ******** ---- 			FIN guardar_promocion				------ ************ //////////////////

///////////////// ******** ---- 			actualizar_promocion				------ ************ //////////////////
//////// Actualiza la informacion de la promocion y sus productos
	// Como parametros puede recibir:
		// nombre -> nombre de la promocion
		// dias -> string con los numeros de los dias
		// tipo -> 1 -> por descuento, 2 -> por cantidad
		// descuento(si es tipo 1) -> porcentaje de descuento
		// cantidad(si es tipo 2) -> cantidad de productos
		// cantidad_descuento(si es tipo 2) -> cantidad que se descuenta
		// inicio -> hora de inicio
		// fin -> hora final
		// id_promocion -> iD de la promocion

	function actualizar_promocion($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		session_start();
	// Sin productos
		if(empty($_SESSION['productos_agregados']['productos'])){
			$resp['status'] = 2;
			
			echo json_encode($resp);
			
			return 0;
		}
		
	// llama a la funcion que guarda la promocion en la BD
		$result = $this -> configuracionModel -> actualizar_promocion($objeto);
		
	// Manda llamar a la funcion que elimina los productos de la BD
		$productos_eliminados = $this -> configuracionModel -> eliminar_productos($objeto);
		
	// Guarda los productos de la promocion en la BD
		foreach ($_SESSION['productos_agregados']['productos'] as $key => $value) {
			$value['id_promocion'] = $objeto['id_promocion'];
			$resp['result'] = $this -> configuracionModel -> guardar_producto_promocion($value);
		}
		
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0 ;
		
		echo json_encode($resp);
	}

///////////////// ******** ---- 			FIN actualizar_promocion			------ ************ //////////////////

///////////////// ******** ---- 			vista_editar_promocion				------ ************ //////////////////
//////// Consulta las prmociones, sus productos y carga la vista
	// Como parametros recibe:
		// div -> div donde se cargara el contenido html
		// btn -> boton del loader
		// panel -> Clase que se le aplicara al panel de la receta
		
	function vista_editar_promocion($objeto){
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
 		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$panel = $objeto['panel'];

	// Consulta las promociones
		$promociones = $this -> configuracionModel -> listar_pomociones($objeto);
		$promociones = $promociones['rows' ];

		foreach ($promociones as $key => $value) {
			$value['productos'] = $this -> configuracionModel -> listar_productos($value);
			$value['productos'] = $value['productos']['rows'];

			$horario = (!empty(strpos($value['dias'], "0"))) ? 'Do, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "1"))) ? 'Lu, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "2"))) ? 'Ma, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "3"))) ? 'Mi, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "4"))) ? 'Ju, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "5"))) ? 'Vi, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "6"))) ? 'Sa, ' : '' ;
			
			$value['horario'] = substr($horario, 0, -2);
			$value['horario'] .= ' == '.$value['inicio'].'-'.$value['fin'];
		
		// Agrega el elemento al array
			$datos[$value['id_promocion']] = $value;
		}
		
	// Inicializa el array de los insumos agregados
		session_start();
		$_SESSION['productos_agregados'] = '';	
		
	// Carga la vista de las promociones
		require('views/configuracion/editar_promocion.php');
	}
			
///////////////// ******** ---- 			FIN vista_editar_promocion			------ ************ //////////////////

///////////////// ******** ---- 			vista_eliminar_promocion			------ ************ //////////////////
//////// Consulta las prmociones, sus productos y carga la vista
	// Como parametros recibe:
		// div -> div donde se cargara el contenido html
		// btn -> boton del loader
		// panel -> Clase que se le aplicara al panel de la receta
		
	function vista_eliminar_promocion($objeto){
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
 		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$panel = $objeto['panel'];

	// Consulta las promociones
		$promociones = $this -> configuracionModel -> listar_pomociones($objeto);
		$promociones = $promociones['rows' ];

		foreach ($promociones as $key => $value) {
			$value['productos'] = $this -> configuracionModel -> listar_productos($value);
			$value['productos'] = $value['productos']['rows'];

			$horario = (!empty(strpos($value['dias'], "0"))) ? 'Do, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "1"))) ? 'Lu, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "2"))) ? 'Ma, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "3"))) ? 'Mi, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "4"))) ? 'Ju, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "5"))) ? 'Vi, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "6"))) ? 'Sa, ' : '' ;
			
			$value['horario'] = substr($horario, 0, -2);
			$value['horario'] .= ' == '.$value['inicio'].'-'.$value['fin'];
		
		// Agrega el elemento al array
			$datos[$value['id_promocion']] = $value;
		}
		
	// Inicializa el array de los insumos agregados
		session_start();
		$_SESSION['productos_agregados'] = '';	
		
	// Carga la vista de las promociones
		require('views/configuracion/eliminar_promocion.php');
	}
			
///////////////// ******** ---- 			FIN vista_eliminar_promocion		------ ************ //////////////////

///////////////// ******** ---- 			eliminar_promocion					------ ************ //////////////////
//////// Actualiza la informacion de la promocion y sus productos
	// Como parametros puede recibir:
		// id_promocion -> ID de la promocion

	function eliminar_promocion($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
	// llama a la funcion que elimina la promocion en la BD
		$result = $this -> configuracionModel -> eliminar_promocion($objeto);
		
	// Manda llamar a la funcion que elimina los productos de la BD
		$productos_eliminados = $this -> configuracionModel -> eliminar_productos($objeto);
		
	// 1 -> Todo bien :)
	// 2 -> Fallo la consulta :(
		$resp['status'] = (!empty($productos_eliminados)) ? 1 : 0;
		
		echo json_encode($resp);
	}

///////////////// ******** ---- 			FIN eliminar_promocion				------ ************ //////////////////

///////////////// ******** ---- 				vista_menu						------ ************ //////////////////
//////// Carga la vista del menu digital
	// Como parametro puede recibir:

	function vista_menu($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// Carga la vista de las promociones
		require ('views/configuracion/vista_menu.php');
	}

///////////////// ******** ---- 				FIN	vista_menu					------ ************ //////////////////

///////////////// ******** ---- 				vista_nuevo_menu				------ ************ //////////////////
//////// Carga la vista para crear un nuevo menu
	// Como parametros recibe:
		// div -> div donde se cargara el contenido html
		// btn -> boton del loader
		// panel -> Clase que se le aplicara al panel de la promocion

	function vista_nuevo_menu($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$panel = $objeto['panel'];

	// Consulta las categorias
		$objeto['tipo'] = 1;
		$categorias = $this -> configuracionModel -> listar_categorias_menu($objeto);
		$categorias = $categorias['rows'];
		
	// Consulta los productos
		$productos = $this -> configuracionModel -> listar_productos($objeto);
		$productos = $productos['rows'];
	
	// Arma el data para el arbol
		foreach ($productos as $key => $value) {
			$productos[$key]['data'] = $value;
		}
	
	// Une las categorias y los productos
		$datos = array_merge($categorias, $productos);
		
	// Carga la vista de las configuracion
		require ('views/configuracion/vista_nuevo_menu.php');
	}

///////////////// ******** ---- 			FIN	vista_nuevo_menu				------ ************ //////////////////

///////////////// ******** ---- 				imprimir_menu					------ ************ //////////////////
//////// Carga la vista  segun el estilo seleccionado con los datos del menu
	// Como parametros recibe:
		// div -> div donde se cargara el contenido html
		// productos -> array con los productos del menu
		// nombre -> nombre del menu
		// nombre_restaurante -> nombre del restaurante
		// estilo -> 1 -> alternativo, 2 -> clasico, 3 -> organico vintage, 4 -> tradicional

	function imprimir_menu($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		$array = $objeto['productos'];
		$groupkey = 'parent';
		
		$keys = array_keys($array[0]);
		$removekey = array_search($groupkey, $keys);
		
		$groupcriteria = array();
		$return = array();
		
		foreach ($array as $value) {
			$item = null;
			
			foreach ($keys as $key) {
				$item[$key] = $value[$key];
			}
			
			$busca = array_search($value[$groupkey], $groupcriteria);
			if ($busca === false) {
				$groupcriteria[] = $value[$groupkey];
				$return_2[] = array($groupkey => $value[$groupkey], 'datos' => array());
				$busca = count($return_2) - 1;
				$value[$groupkey];
			}
			
			if (!empty($item)) {
				$productos[$value[$groupkey]]['categoria'] = $item['parent_text'];
				$productos[$value[$groupkey]]['datos'][] = $item;
			}
		}
		
	// Optenemos el logo
		$logo = $this -> configuracionModel -> logo($objet);
		$objeto['logo'] = $logo['rows'][0]['logo'];
		
	// Carga la vista de las configuracion
		require ('views/configuracion/menu_'.$objeto['estilo'].'.php');
	}

///////////////// ******** ---- 			FIN	imprimir_menu					------ ************ //////////////////

///////////////// ******** ---- 			agregar_menu						------ ************ //////////////////
//////// Agrega un nuevo menu digital
	// Como parametros recibe:
		// nombre -> nombre del menu
		// nombre_restaurante -> nombre del restaurante
		// estilo -> estilo seleccionado
		// btn -> boton del loader
		// productos -> array con los productos para el menu

	function agregar_menu($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
	
	// Valida que existan productos
		if (empty($objeto['productos'])) {
			$resp['status'] = 2;
			echo json_encode($resp);
			
			return 0;
		}
		
	// Agrega el menu ala BD
		$objeto['id_menu'] = $this -> configuracionModel -> agregar_menu($objeto);
	
	// Agrega los productos al del menu a la BD
		foreach ($objeto['productos'] as $key => $value) {
			$value['id_menu'] = $objeto['id_menu'];
			$result = $this -> configuracionModel -> agregar_producto_menu($value);
		}
		
	// 1 -> Todo bien :)
	// 2 -> Fallo la consulta :(
		$resp['status'] = (!empty($result)) ? 1 : 0;
		
		echo json_encode($resp);
	}

///////////////// ******** ---- 			FIN agregar_menu					------ ************ //////////////////

///////////////// ******** ---- 			vista_editar_menu					------ ************ //////////////////
//////// Carga la vista para editar un menu
	// Como parametros recibe:
		// div -> div donde se cargara el contenido html
		// btn -> boton del loader
		// panel -> Clase que se le aplicara al panel de la promocion
		
	function vista_editar_menu($objeto){
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
 		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$panel = $objeto['panel'];

	// Consulta las promociones
		$menus = $this -> configuracionModel -> listar_menus($objeto);
		$menus = $menus['rows' ];
		
		foreach ($menus as $key => $value){
			$value['activo'] = 1;
			$productos = $this -> configuracionModel -> listar_productos_menu($value);
			$menus[$key]['productos'] = $productos['rows'];
		}
	
		
	// Carga la vista de las promociones
		require('views/configuracion/vista_editar_menu.php');
	}
			
///////////////// ******** ---- 			FIN vista_editar_menu				------ ************ //////////////////

///////////////// ******** ---- 			actualizar_menu						------ ************ //////////////////
//////// Actualiza los datos del menu
	// Como parametros recibe:
		// nombre -> nombre del menu
		// nombre_restaurante -> nombre del restaurante
		// estilo -> estilo seleccionado
		// btn -> boton del loader
		// productos -> array con los productos para el menu

	function actualizar_menu($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
	
	// Valida que existan productos
		if (empty($objeto['productos'])) {
			$resp['status'] = 2;
			echo json_encode($resp);
			
			return 0;
		}
		
	// Agrega el menu ala BD
		$result = $this -> configuracionModel -> actualizar_menu($objeto);
	
	// Elimina los productos viejos del menu
		$productos_eliminados = $this -> configuracionModel -> eliminar_productos_menu($objeto);
			
	// Agrega los productos al del menu a la BD
		foreach ($objeto['productos'] as $key => $value) {
		// Agrega los nuevos productos del menu
			$value['id_menu'] = $objeto['id_menu'];
			$result = $this -> configuracionModel -> agregar_producto_menu($value);
		}
		
	// 1 -> Todo bien :)
	// 2 -> Fallo la consulta :(
		$resp['status'] = (!empty($result)) ? 1 : 0;
		
		echo json_encode($resp);
	}

///////////////// ******** ---- 			FIN actualizar_menu					------ ************ //////////////////

///////////////// ******** ---- 			vista_eliminar_menu					------ ************ //////////////////
//////// Carga la vista para eliminar un menu
	// Como parametros recibe:
		// div -> div donde se cargara el contenido html
		// btn -> boton del loader
		// panel -> Clase que se le aplicara al panel de la promocion
		
	function vista_eliminar_menu($objeto){
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
 		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$panel = $objeto['panel'];

	// Consulta las promociones
		$menus = $this -> configuracionModel -> listar_menus($objeto);
		$menus = $menus['rows' ];
		
	// Carga la vista de las promociones
		require('views/configuracion/vista_eliminar_menu.php');
	}
			
///////////////// ******** ---- 			FIN vista_eliminar_menu				------ ************ //////////////////

///////////////// ******** ---- 				eliminar_menu					------ ************ //////////////////
//////// Elimina el menu y sus productos
	// Como parametros recibe:
		// id_menu -> ID del menu

	function eliminar_menu($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
	// llama a la funcion que elimina la promocion en la BD
		$result = $this -> configuracionModel -> eliminar_menu($objeto);
		
	// Manda llamar a la funcion que elimina los productos de la BD
		$productos_eliminados = $this -> configuracionModel -> eliminar_productos_menu($objeto);
		
	// 1 -> Todo bien :)
	// 2 -> Fallo la consulta :(
		$resp['status'] = (!empty($productos_eliminados)) ? 1 : 0;
		
		echo json_encode($resp);
	}

///////////////// ******** ---- 			FIN eliminar_promocion				------ ************ //////////////////

///////////////// ******** ---- 				vista_kits						------ ************ //////////////////
//////// Carga la vista de los kits
	// Como parametro puede recibir:

	function vista_kits($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Carga la vista de las promociones
		require ('views/configuracion/vista_kits.php');
	}

///////////////// ******** ---- 				FIN vista_kits					------ ************ //////////////////

///////////////// ******** ---- 				vista_nuevo_kit					------ ************ //////////////////
//////// Carga la vista para un nuevo kit
	// Como parametros recibe:
	// div -> div donde se cargara el contenido html
	// btn -> boton del loader
	// tipo -> Tipo de producto(se utiliza al consultar los productos)
	// vista -> Vista que se debe cargar

	function vista_nuevo_kit($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$panel = (!empty($objeto['panel'])) ?  $objeto['panel'] : 'default' ;

	// Consulta los productos
		$productos = $this -> configuracionModel -> listar_productos($objeto);
		$productos = $productos['rows'];

	// Inicializa el array de los productos agregados
		session_start();
		$_SESSION['productos_agregados'] = '';
	
	// Carga la vista de nuevo por default si no existe una vista
		$vista = (!empty($objeto['vista'])) ? $objeto['vista'] : 'nuevo_kit';
		
	// Carga la vista
		require ('views/configuracion/'.$vista.'.php');
	}

///////////////// ******** ---- 				FIN	vista_nuevo_kit				------ ************ //////////////////

///////////////// ******** ---- 				actualizar_cantidad				------ ************ //////////////////
//////// Cambia la cantidad del producto
	// Como parametros recibe:
		// id -> ID del producto
		// cantidad -> Nueva cantidad

	function actualizar_cantidad($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$precio = 0;
	
	// Valida que se guarde la cantidad
		session_start();
		if ($_SESSION['productos_agregados']['productos'][$objeto['id']]['cantidad'] = $objeto['cantidad']) {
		// Actualiza el sub total
			$precio = $_SESSION['productos_agregados']['productos'][$objeto['id']]['precio'];
			$_SESSION['productos_agregados']['productos'][$objeto['id']]['sub_total'] = $objeto['cantidad'] * $precio;
			$resp['productos_agregados'] = 	$_SESSION['productos_agregados']['productos'];
			$resp['status'] = 1;
		} else {
			$resp['status'] = 0;
		}
		
		
		echo json_encode($resp);
	}

///////////////// ******** ---- 			FIN actualizar_cantidad				------ ************ //////////////////

///////////////// ******** ---- 				guardar_kit						------ ************ //////////////////
//////// Manda llamar a la funcion que guarda el kit y sus productos
	// Como parametros puede recibir:
		// nombre -> nombre de la promocion
		// dias -> string con los numeros de los dias
		// precio -> Precio del kit
		// inicio -> hora de inicio
		// fin -> hora final

	function guardar_kit($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
	// Sin productos
		session_start();
		if(empty($_SESSION['productos_agregados']['productos'])){
			$resp['status'] = 2;
			
			echo json_encode($resp);
			
			return 0;
		}
		
	// llama a la funcion que guarda el producto en la BD y obtiene el ID de la insercion
		$objeto['tipo'] = 6;
		$objeto['id_producto'] = $this -> configuracionModel -> guardar_producto($objeto);
		
	// llama a la funcion que guarda la kit en la BD
		$id_kit = $this -> configuracionModel -> guardar_kit($objeto);
		
	// Guarda los productos del kit en la BD
		foreach ($_SESSION['productos_agregados']['productos'] as $key => $value) {
			$value['id_kit'] = $objeto['id_producto'];
			$resp['result'] = $this -> configuracionModel -> guardar_productos_kit($value);
		}
		
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0 ;
		
		echo json_encode($resp);
	}

///////////////// ******** ---- 				FIN guardar_kit					------ ************ //////////////////

///////////////// ******** ---- 				vista_editar_kit				------ ************ //////////////////
//////// Carga la vista para editar los kits
	// Como parametros recibe:
	// div -> div donde se cargara el contenido html
	// btn -> boton del loader
	// tipo -> Tipo de producto(se utiliza al consultar los productos)
	// vista -> Vista que se debe cargar

	function vista_editar_kit($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$panel = $objeto['panel'];

	// Consulta los productos
		$kits = $this -> configuracionModel -> listar_kits($objeto);
		$kits = $kits['rows'];
	
	// Obtiene los productos de los kits y los agrega
		foreach ($kits as $key => $value) {
			$productos = $this -> configuracionModel -> listar_productos($value);
			$value['productos'] = $productos['rows'];
			
			$horario = (!empty(strpos($value['dias'], "0"))) ? 'Do, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "1"))) ? 'Lu, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "2"))) ? 'Ma, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "3"))) ? 'Mi, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "4"))) ? 'Ju, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "5"))) ? 'Vi, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "6"))) ? 'Sa, ' : '' ;
			
			$value['horario'] = substr($horario, 0, -2);
			$value['horario'] .= ' == '.$value['inicio'].'-'.$value['fin'];
			
		// Agrega el elemento al array
			$datos[$key] = $value;
		}
		
	// Inicializa el array de los productos agregados
		session_start();
		$_SESSION['productos_agregados'] = '';
	
	// Carga la vista de edicion por default si no existe una vista
		$vista = (!empty($objeto['vista'])) ? $objeto['vista'] : 'editar_kit';
		
	// Carga la vista
		require ('views/configuracion/'.$vista.'.php');
	}

///////////////// ******** ---- 				FIN vista_editar_kit				------ ************ //////////////////

///////////////// ******** ---- 					actualizar_kit					------ ************ //////////////////
//////// Actualiza la informacion en la DB del kit
//////// Manda llamar a la funcion que guarda el kit y sus productos
	// Como parametros puede recibir:
		// nombre -> nombre de la promocion
		// dias -> string con los numeros de los dias
		// precio -> Precio del kit
		// inicio -> hora de inicio
		// fin -> hora final

	function actualizar_kit($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
	// Sin productos
		session_start();
		if(empty($_SESSION['productos_agregados']['productos'])){
			$resp['status'] = 2;
			
			echo json_encode($resp);
			
			return 0;
		}
		
	// llama a la funcion que guarda el producto en la BD y obtiene el ID de la insercion
		$objeto['tipo'] = 6;
		$objeto['id_producto'] = $this -> configuracionModel -> actualizar_producto($objeto);
		
	// llama a la funcion que guarda la kit en la BD
		$id_kit = $this -> configuracionModel -> actualizar_kit($objeto);
		
	// Elimina los productos del kit
		$productos_eliminados = $this -> configuracionModel -> eliminar_productos_kit($objeto);
		
	// Guarda los productos del kit en la BD
		foreach ($_SESSION['productos_agregados']['productos'] as $key => $value) {
			$value['id_kit'] = $objeto['id_kit'];
			$resp['result'] = $this -> configuracionModel -> guardar_productos_kit($value);
		}
		
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0 ;
		
		echo json_encode($resp);
	}

///////////////// ******** ---- 				FIN actualizar_kit					------ ************ //////////////////

///////////////// ******** ---- 					eliminar_kit					------ ************ //////////////////
//////// Elimina el kit y sus productos
	// Como parametros recibe:
		// id_kit -> ID del kit

	function eliminar_kit($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
	// llama a la funcion que elimina el kit de la BD
		$result = $this -> configuracionModel -> eliminar_kit($objeto);
		
	// Manda llamar a la funcion que elimina los productos de la BD
		$productos_eliminados = $this -> configuracionModel -> eliminar_productos_kit($objeto);
		
	// 1 -> Todo bien :)
	// 2 -> Fallo la consulta :(
		$resp['status'] = (!empty($productos_eliminados)) ? 1 : 0;
		
		echo json_encode($resp);
	}

///////////////// ******** ---- 				FIN eliminar_kit				------ ************ //////////////////

///////////////// ******** ---- 				vista_combos					------ ************ //////////////////
//////// Carga la vista de los combos
	// Como parametro puede recibir:

	function vista_combos($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Carga la vista de las promociones
		require ('views/configuracion/vista_combos.php');
	}

///////////////// ******** ---- 				FIN vista_combos				------ ************ //////////////////

///////////////// ******** ---- 				vista_nuevo_combo					------ ************ //////////////////
//////// Carga la vista para un nuevo combo
	// Como parametros recibe:
	// div -> div donde se cargara el contenido html
	// btn -> boton del loader
	// tipo -> Tipo de producto(se utiliza al consultar los productos)
	// vista -> Vista que se debe cargar

	function vista_nuevo_combo($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$panel = (!empty($objeto['panel'])) ?  $objeto['panel'] : 'default' ;

	// Consulta los productos
		$productos = $this -> configuracionModel -> listar_productos($objeto);
		$productos = $productos['rows'];
		
		$grupo = 1;
		
	// Inicializa el array de los productos agregados
		session_start();
		$_SESSION['productos_agregados_combo'] = '';
		$_SESSION['combos']['num_grupos'] = 1;
	
	// Carga la vista de nuevo por default si no existe una vista
		$vista = (!empty($objeto['vista'])) ? $objeto['vista'] : 'nuevo_combo';
		
	// Carga la vista
		require ('views/configuracion/'.$vista.'.php');
	}

///////////////// ******** ---- 				FIN	vista_nuevo_combo				------ ************ //////////////////

///////////////// ******** ---- 				agregar_producto_combo				------ ************ //////////////////
//////// Agrega un producto al array de los productos agregados del combo  y carga la vista donde aparecen
	// Como parametros recibe:
		// idProducto -> ID del producto
		// div -> ID de la div donde se cargara la vista
		// nombre -> nombre del producto
		// precio -> precio del producto

	function agregar_producto_combo($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		session_start();
		$productos = Array();
		
	// Si no existe el producto lo agrega al array,  si existe lo elimina
		if (!empty($_SESSION['productos_agregados_combo'][$objeto['grupo']]['productos'][$objeto['id']])) {
			unset($_SESSION['productos_agregados_combo'][$objeto['grupo']]['productos'][$objeto['id']]);
		} else {
			$_SESSION['productos_agregados_combo'][$objeto['grupo']]['productos'][$objeto['id']] = $objeto;
			$_SESSION['combos']['num_grupos'] = $objeto['grupo'];
		}
		
		$productos = $_SESSION['productos_agregados_combo'];
		
	// Valida si se debe carga la vista por default u otra
		$vista = (!empty($objeto['vista'])) ? $objeto['vista'] : 'listar_productos_agregados_combo' ;
		
	// Carga la vista
		require ('views/configuracion/'.$vista.'.php');
	}

///////////////// ******** ---- 			FIN agregar_producto_combo				------ ************ //////////////////

///////////////// ******** ---- 				agregar_grupo						------ ************ //////////////////
//////// Crea un nuevo grupo y lo selecciona
	// Como parametros recibe:
		// grupo -> ID del grupo a agregar
		// div -> Din donde se cargara en contenido

	function agregar_grupo($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		session_start();
		$productos = Array();
		
	// Si no existe el grupo lo agrega al array, si existe lo elimina
		if (!empty($objeto['grupo'])) {
			unset($_SESSION['productos_agregados_combo'][$objeto['grupo']]);
		
			$_SESSION['combos']['num_grupos'] = key($_SESSION['productos_agregados_combo']);
			$objeto['grupo'] = $_SESSION['combos']['num_grupos'];
		} else {
			$_SESSION['combos']['num_grupos'] ++;
			$objeto['grupo'] = $_SESSION['combos']['num_grupos'];
			$_SESSION['productos_agregados_combo'][$_SESSION['combos']['num_grupos']] = '';
		}
		
		$productos = $_SESSION['productos_agregados_combo'];
		
	// Valida si se debe carga la vista por default u otra
		$vista = (!empty($objeto['vista'])) ? $objeto['vista'] : 'listar_productos_agregados_combo';
	
	// Selecciona el grupo creado
		echo "<script>$('#grupo').val(".$_SESSION['combos']['num_grupos'].");</script>";
		
	// Carga la vista
		require ('views/configuracion/'.$vista.'.php');
	}

///////////////// ******** ---- 				FIN agregar_grupo					------ ************ //////////////////

///////////////// ******** ---- 				guardar_combo						------ ************ //////////////////
//////// Manda llamar a la funcion que guarda el combo y sus productos
	// Como parametros puede recibir:
		// nombre -> nombre de la promocion
		// dias -> string con los numeros de los dias
		// precio -> Precio del combo
		// inicio -> hora de inicio
		// fin -> hora final

	function guardar_combo($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
	// Sin productos
		session_start();
		if(empty($_SESSION['productos_agregados_combo'])){
			$resp['status'] = 2;
			
			echo json_encode($resp);
			
			return 0;
		}
		
	// llama a la funcion que guarda el producto en la BD y obtiene el ID de la insercion
		$objeto['tipo'] = 7;
		$objeto['id_producto'] = $this -> configuracionModel -> guardar_producto($objeto);
		
	// llama a la funcion que guarda la combo en la BD
		$id_combo = $this -> configuracionModel -> guardar_combo($objeto);
		
	// Guarda los productos del combo en la BD
		foreach ($_SESSION['productos_agregados_combo'] as $key => $value) {
		// Valida que tenga productos el grupo
			if (!empty($value['productos'])) {
				foreach ($value['productos'] as $k => $v) {
					$v['id_combo'] = $objeto['id_producto'];
					$v['grupo'] = $key;
					$resp['result'] = $this -> configuracionModel -> guardar_productos_combo($v);
				}
			}
		}
		
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0 ;
		
		echo json_encode($resp);
	}

///////////////// ******** ---- 				FIN guardar_combo					------ ************ //////////////////

///////////////// ******** ---- 				vista_editar_combo					------ ************ //////////////////
//////// Carga la vista para editar los combos
	// Como parametros recibe:
	// div -> div donde se cargara el contenido html
	// btn -> boton del loader
	// tipo -> Tipo de producto(se utiliza al consultar los productos)
	// vista -> Vista que se debe cargar

	function vista_editar_combo($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$panel = $objeto['panel'];

	// Consulta los productos
		$combos = $this -> configuracionModel -> listar_combos($objeto);
		$combos = $combos['rows'];
	
	// Obtiene los productos de los combos y los agrega
		foreach ($combos as $key => $value) {
			$productos = $this -> configuracionModel -> listar_productos($value);
			$value['productos'] = $productos['rows'];
			
			$horario = (!empty(strpos($value['dias'], "0"))) ? 'Do, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "1"))) ? 'Lu, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "2"))) ? 'Ma, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "3"))) ? 'Mi, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "4"))) ? 'Ju, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "5"))) ? 'Vi, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "6"))) ? 'Sa ' : '' ;
			
			$value['horario'] = substr($horario, 0, -2);
			$value['horario'] .= ' == '.$value['inicio'].'-'.$value['fin'];
			
		// Agrega el elemento al array
			$datos[$key] = $value;
		}
		
	// Inicializa el array de los productos agregados
		session_start();
		$_SESSION['productos_agregados_combo'] = '';
	
	// Carga la vista de edicion por default si no existe una vista
		$vista = (!empty($objeto['vista'])) ? $objeto['vista'] : 'editar_combo';
		
	// Carga la vista
		require ('views/configuracion/'.$vista.'.php');
	}

///////////////// ******** ---- 				FIN vista_editar_combo				------ ************ //////////////////

///////////////// ******** ---- 					actualizar_combo				------ ************ //////////////////
//////// Actualiza la informacion en la DB del combo
//////// Manda llamar a la funcion que guarda el combo y sus productos
	// Como parametros puede recibir:
		// nombre -> nombre de la promocion
		// dias -> string con los numeros de los dias
		// precio -> Precio del combo
		// inicio -> hora de inicio
		// fin -> hora final

	function actualizar_combo($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
	// Sin productos
		session_start();
		if(empty($_SESSION['productos_agregados_combo'])){
			$resp['status'] = 2;
			
			echo json_encode($resp);
			
			return 0;
		}
		
	// llama a la funcion que guarda el producto en la BD y obtiene el ID de la insercion
		$objeto['tipo'] = 7;
		$objeto['id_producto'] = $this -> configuracionModel -> actualizar_producto($objeto);
		
	// llama a la funcion que guarda la combo en la BD
		$id_combo = $this -> configuracionModel -> actualizar_combo($objeto);
		
	// Elimina los productos del combo
		$productos_eliminados = $this -> configuracionModel -> eliminar_productos_combo($objeto);
		
	// Guarda los productos del combo en la BD
		foreach ($_SESSION['productos_agregados_combo'] as $key => $value) {
		// Valida que tenga productos el grupo
			if (!empty($value['productos'])) {
				foreach ($value['productos'] as $k => $v) {
					$v['id_combo'] = $objeto['id_combo'];
					$v['grupo'] = $key;
					$resp['result'] = $this -> configuracionModel -> guardar_productos_combo($v);
				}
			}
		}
		
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0 ;
		
		echo json_encode($resp);
	}

///////////////// ******** ---- 				FIN actualizar_combo				------ ************ //////////////////

///////////////// ******** ---- 					eliminar_combo					------ ************ //////////////////
//////// Elimina el combo y sus productos
	// Como parametros recibe:
		// id_combo -> ID del combo

	function eliminar_combo($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
	// llama a la funcion que elimina el combo de la BD
		$result = $this -> configuracionModel -> eliminar_combo($objeto);
		
	// Manda llamar a la funcion que elimina los productos de la BD
		$productos_eliminados = $this -> configuracionModel -> eliminar_productos_combo($objeto);
		
	// 1 -> Todo bien :)
	// 2 -> Fallo la consulta :(
		$resp['status'] = (!empty($productos_eliminados)) ? 1 : 0;
		
		echo json_encode($resp);
	}

///////////////// ******** ---- 				FIN eliminar_combo						------ ************ //////////////////

} ?>