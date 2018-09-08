<?php
//Carga la funciones comunes top y footer
require ('common.php');

//Carga el modelo para este controlador
require ("models/pedidosactivos.php");

class pedidosActivos extends Common {
	public $pedidosActivosModel;

	function __construct() {
		//Se crea el objeto que instancia al modelo que se va a utilizar
		$this -> pedidosActivosModel = new pedidosActivosModel();
	}

	function Zona() {
	// Limpiamos los arrays
		session_start();
		$_SESSION['terminados'] = '';
		$_SESSION['eliminados'] = '';
		$_SESSION['listado_terminados'] = '';
		$_SESSION['listado_eliminados'] = '';

		$lugares = $this -> pedidosActivosModel -> getLugares();

		require ('views/pedidos/pedidosActivos.php');
	}

#Funcion para obtener las comandas que esten activas para preparar
	function Ver() {
	// Consultamos los pedidos
		$pedidos_nuevos = '';
		$pedidos = $this -> pedidosActivosModel -> getPedidos($_POST);
		$pedidos = $pedidos['rows'];
		
		$ticket = $_POST['ticket'];
		$vista_listado = $_POST['vista_listado'];
		$pedidos_nuevos = '';
		$producto = 0;
		$persona = 0;
		$id = $_POST['tipo'];
		
		foreach ($pedidos as $key => $value) {
			if ($persona != $value["persona"]) {
				$persona = $value["persona"];
				$producto = 0;
				$cantidad = 0;
			} else {
				$producto++;
			}
			
		/* Calculamos el tiempo que lleva el pedido en el area
		=================================================================== */
		
			date_default_timezone_set('America/Mexico_City');
			if (!empty($value['inicio'])) {
				$segundos = strtotime(date('Y-m-d H:i:s')) - strtotime($value['inicio']);
				$horas = floor($segundos / 3600);
				$minutos = floor(($segundos - ($horas * 3600)) / 60);
				$tiempo = $horas.":".$minutos;
			}else{
				$tiempo = "0:0";
			}
			
			$value['tiempo'] = $tiempo;
			
		/* FIN 
		=================================================================== */
			
		// Si son pedidos nuevos(status = 0), crea variables para el ticket
			if ($value['status'] == 0) {
				$pedidos_nuevos[$value["comanda"]]["nombre_mesa"] = $value["nombre_mesa"];
				$pedidos_nuevos[$value["comanda"]]["inicioPedido"] = $value["timestamp"];
				$pedidos_nuevos[$value["comanda"]]["comanda"] = $value["comanda"];
				$pedidos_nuevos[$value["comanda"]]["mesa"] = $value["idmesa"];
				$pedidos_nuevos[$value["comanda"]]["domicilio"] = $value["domicilio"];
				$pedidos_nuevos[$value["comanda"]]["tel"] = $value["tel"];
				$pedidos_nuevos[$value["comanda"]]["tipo"] = $value["tipo"];
				
				if ($id_producto == $value["id_producto"]) {
					$cantidad += $value["cantidad"];
				} else {
					$cantidad = $value["cantidad"];
				}
				
				$pedidos_nuevos[$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["id_producto"]]["opcionalesDesc"] = $value["opcionalesDesc"];
				$pedidos_nuevos[$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["id_producto"]]["adicionalesDesc"] = $value["adicionalesDesc"];
				$pedidos_nuevos[$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["id_producto"]]["sin_desc"] = $value["sin_desc"];
				$pedidos_nuevos[$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["id_producto"]]["nota_sin"] = $value["nota_sin"];

				$pedidos_nuevos[$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["id_producto"]]["producto"] = utf8_decode($value["producto"]);
				$pedidos_nuevos[$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["id_producto"]]["idproducto"] = utf8_decode($value["idproducto"]);
				$pedidos_nuevos[$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["id_producto"]]["cantidad"] = $cantidad;
				$pedidos_nuevos[$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["id_producto"]]["descripcion"] = $value["descripcion"];
				$pedidos_nuevos[$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["id_producto"]]["departamento"] = utf8_decode($value["departamento"]);
				$pedidos_nuevos[$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["id_producto"]]["opcionales"] = utf8_decode($value["opcionales"]);
				$pedidos_nuevos[$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["id_producto"]]["adicionales"] = utf8_decode($value["adicionales"]);
				$pedidos_nuevos[$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["id_producto"]]["sin"] = utf8_decode($value["sin"]);
				$pedidos_nuevos[$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["id_producto"]]["desc_kit"] = utf8_decode($value["desc_kit"]);
				$pedidos_nuevos[$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["id_producto"]]["tiempo_platillo"] = $value["tiempo_platillo"];
				
				$id_producto = $value["id_producto"];
			}
			
			$pedidos_permanentes["pedidos"][$id]["status"] = TRUE;
			$pedidos_permanentes["pedidos"][$id]["organizacion"] = $_SESSION["accelog_nombre_organizacion"];

			$pedidos_permanentes["pedidos"][$id]["comanda"][$value["comanda"]]["nombre_mesa"] = $value["nombre_mesa"];
			$pedidos_permanentes["pedidos"][$id]["comanda"][$value["comanda"]]["inicioPedido"] = $value["timestamp"];
			$pedidos_permanentes["pedidos"][$id]["comanda"][$value["comanda"]]["comanda"] = $value["comanda"];
			$pedidos_permanentes["pedidos"][$id]["comanda"][$value["comanda"]]["mesa"] = $value["idmesa"];
			$pedidos_permanentes["pedidos"][$id]["comanda"][$value["comanda"]]["domicilio"] = $value["domicilio"];
			$pedidos_permanentes["pedidos"][$id]["comanda"][$value["comanda"]]["tel"] = $value["tel"];
			$pedidos_permanentes["pedidos"][$id]["comanda"][$value["comanda"]]["tipo"] = $value["tipo"];
			
		// Arma el array de los pedidos
			$pedidos_permanentes["pedidos"][$id]["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["tiempo"] = $tiempo;
			$pedidos_permanentes["pedidos"][$id]["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["opcionales"] = utf8_decode($value["opcionales"]);
			$pedidos_permanentes["pedidos"][$id]["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["adicionales"] = utf8_decode($value["adicionales"]);
			$pedidos_permanentes["pedidos"][$id]["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["sin"] = utf8_decode($value["sin"]);

			$pedidos_permanentes["pedidos"][$id]["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["opcionalesDesc"] = $value["opcionalesDesc"];
			$pedidos_permanentes["pedidos"][$id]["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["adicionalesDesc"] = $value["adicionalesDesc"];
			$pedidos_permanentes["pedidos"][$id]["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["sin_desc"] = $value["sin_desc"];
			$pedidos_permanentes["pedidos"][$id]["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["nota_sin"] = $value["nota_sin"];
			$pedidos_permanentes["pedidos"][$id]["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["desc_kit"] = $value["desc_kit"];

			$pedidos_permanentes["pedidos"][$id]["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["producto"] = utf8_decode($value["producto"]);
			$pedidos_permanentes["pedidos"][$id]["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["idproducto"] = utf8_decode($value["idproducto"]);
			$pedidos_permanentes["pedidos"][$id]["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["cantidad"] = utf8_decode($value["cantidad"]);
			$pedidos_permanentes["pedidos"][$id]["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["descripcion"] = $value["descripcion"];
			$pedidos_permanentes["pedidos"][$id]["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["departamento"] = utf8_decode($value["departamento"]);
		
		// Array para la vista de listado
			$listado_pedidos[$key] = $value;
		}
		
	// Valida si se debe de mostrar en listado o por comandas los pedidos(1-> listado, 0->comandas)
		if ($vista_listado == 1) {
			require ('views/pedidos/listado_pendientes.php');
		} else {
			require ('views/pedidos/listar_pendientes.php');
		}
	}

	function reimprime() {
		$pedidos = $this -> pedidosActivosModel -> getPedidosReprint($_POST);
		
		if ($pedidos['total']>0) {
			$arrayComanda = array();
			$producto = 0;
			$persona = 0;

			foreach ($pedidos["rows"] as $key => $value) {
				if ($persona != $value["persona"]) {
					$persona = $value["persona"];
					$producto = 0;
					$cantidad = 0;
				} else {
					$producto++;
				}
				
				if ($id_producto == $value["id_producto"]) {
					$cantidad += $value["cantidad"];
				} else {
					$cantidad = $value["cantidad"];
				}
				
				$arrayComanda["status"] = TRUE;
				$arrayComanda["organizacion"] = $_SESSION["accelog_nombre_organizacion"];

				$arrayComanda["comanda"][$value["comanda"]]["inicioPedido"] = $value["timestamp"];
				$arrayComanda["comanda"][$value["comanda"]]["comanda"] = $value["comanda"];
				$arrayComanda["comanda"][$value["comanda"]]["mesa"] = $value["idmesa"];
				$arrayComanda["comanda"][$value["comanda"]]["domicilio"] = $value["domicilio"];
				$arrayComanda["comanda"][$value["comanda"]]["tel"] = $value["tel"];
				$arrayComanda["comanda"][$value["comanda"]]["tipo"] = $value["tipo"];

			// Arma el array que se regresa a la reimpresion
				$arrayComanda["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["id_producto"]]["opcionales"] = utf8_decode($value["opcionales"]);
				$arrayComanda["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["id_producto"]]["adicionales"] = utf8_decode($value["adicionales"]);
				$arrayComanda["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["id_producto"]]["sin"] = utf8_decode($value["sin"]);

				$arrayComanda["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["id_producto"]]["opcionalesDesc"] = $value["opcionalesDesc"];
				$arrayComanda["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["id_producto"]]["adicionalesDesc"] = $value["adicionalesDesc"];
				$arrayComanda["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["id_producto"]]["sin_desc"] = $value["sin_desc"];
				$arrayComanda["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["id_producto"]]["nota_sin"] = $value["nota_sin"];
				$arrayComanda["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["id_producto"]]["desc_kit"] = utf8_decode($value["desc_kit"]);

				$arrayComanda["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["id_producto"]]["producto"] = utf8_decode($value["producto"]);
				$arrayComanda["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["id_producto"]]["idproducto"] = utf8_decode($value["idproducto"]);
				$arrayComanda["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["id_producto"]]["cantidad"] = $cantidad;
				$arrayComanda["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["id_producto"]]["descripcion"] = $value["descripcion"];
				$arrayComanda["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["id_producto"]]["departamento"] = utf8_decode($value["departamento"]);
				$arrayComanda["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["id_producto"]]["tiempo_platillo"] = $value["tiempo_platillo"];
				
				$id_producto = $value["id_producto"];
			}

			echo json_encode($arrayComanda);
		}else{
			$resp['status'] = 2 ;
			echo json_encode($resp);
		}
	}

	function productoTerminado() {
		$resultado = $this -> pedidosActivosModel -> productoTerminado($_POST);

		echo $resultado;
	}

	function eliminarProducto() {

		$resultado = $this -> pedidosActivosModel -> quitarProducto($_POST);

		echo $resultado;
	}

	function cancelarComanda() {
		$resultado = $this -> pedidosActivosModel -> cancelarComanda($_POST);

		echo $resultado;
	}

	function terminarComanda() {
		$resultado = $this -> pedidosActivosModel -> terminarComanda($_POST);

		echo $resultado;
	}

	function configuraProdPropina() {
		require ('views/pedidos/confpropina.php');
	}

	function serachPropina() {
		$resultado = $this -> pedidosActivosModel -> serachPropina($_GET["term"]);

		echo $resultado;
	}

	function addidPropina() {
		$resultado = $this -> pedidosActivosModel -> addidPropina($_POST["id"]);

		echo $resultado;
	}

///////////////// ******** ---- 		mostrar_propina				------ ************ //////////////////
//////// Llama una funcion a la base de datos y le manda el estatus del check
	// Como parametros recibe:
		// mostrar: valor del Checkbox

	function mostrar_propina($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Valida si el Check esta checado o no, si esta checado manda un 1 a la consulta
		$objeto['mostrar'] = ($objeto['mostrar'] == 'false') ? 0 : 1;

	// Actualiza los campos en la BD
		$result = $this -> pedidosActivosModel -> mostrar_propina($objeto);

	// Regresa al ajax el mensaje
		echo json_encode($result);
	}

///////////////// ******** ---- 		FIN mostrar_propina			------ ************ //////////////////

///////////////// ******** ---- 		mostrar_consumo				------ ************ //////////////////
//////// Llama una funcion a la base de datos y le manda el estatus del check
	// Como parametros recibe:
		// mostrar: valor del Checkbox

	function mostrar_consumo($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Valida si el Check esta checado o no, si esta checado manda un 1 a la consulta
		$objeto['mostrar'] = ($objeto['mostrar'] == 'false') ? 0 : 1;

	// Actualiza los campos en la BD
		$result = $this -> pedidosActivosModel -> mostrar_consumo($objeto);

	// Regresa al ajax el mensaje
		echo json_encode($result);
	}

///////////////// ******** ---- 		FIN mostrar_propina		------ ************ //////////////////

///////////////// ******** ---- 		listar_ajustes		------ ************ //////////////////
//////// Consulta los ajustes de Foodware y los regresa en un array
	// Como parametros recibe:

	function listar_ajustes($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Consulta los datos en la DB y los regresa en un array
		$result = $this -> pedidosActivosModel -> listar_ajustes($objeto);
		$result = $result['rows'][0];

		echo json_encode($result);
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
		// sin -> cadena con los id de los productos sin
		// adicionales_desc -> descripcion de los productos extras
		// nota_sin -> nota de los productos sin
		// opcionales -> cadena con los id de los productos opcionales
		// opcionalesDesc -> descripcion de los productos extras
		// persona -> numero de persona
		// producto -> ID del pedido

	function terminar($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Consulta los datos en la DB
		$resp['result'] = $this -> pedidosActivosModel -> terminar($objeto);

	// 1 -> Todo bien :)
	// 2 -> Fallo la consulta :(
		if (!empty($resp['result'])) {
			session_start();
		// Elimina el pedido del array de pendientes
			$_SESSION["pedidos"][$id]["comanda"][$objeto["comanda"]]["persona"][$objeto["persona"]]["productos"][$objeto["producto"]] = '';
			unset($_SESSION["pedidos"][$id]["comanda"][$objeto["comanda"]]["persona"][$objeto["persona"]]["productos"][$objeto["producto"]]);
		
		// Agrega el pedido al array de terminados
			$_SESSION['terminados'][$objeto['comanda']]['persona'][$objeto['persona']]['productos'][$objeto['producto']] = $objeto;
			$_SESSION['listado_terminados'][$objeto['producto']] = $objeto;
			
		// Todo bien :D, regresa el resultado
			$resp['terminados'] = $_SESSION['terminados'];
			$resp['status'] = 1;
		} else {
			$resp['status'] = 0;
		}

	// Regresa al ajax el mensaje
		echo json_encode($resp);
	}

///////////////// ******** ----                FIN terminar                    ------ ************ //////////////////

///////////////// ******** ----                listar_terminados               ------ ************ //////////////////
//////// Obtiene la vista de los pedidos terminados y los carga a la div
	// Como parametros puede recibir:
		// div -> Div donde se debe de cargar la vista
		// vista_listado -> bandera para indicar si es listado o no (1-> listado, 0 -> comanda)

	function listar_terminados($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		session_start();
		
	// Valida si se debe de mostrar en listado o por comandas los pedidos(1-> listado, 0->comandas)
		if ($objeto['vista_listado'] == 1) {
			$terminados = $_SESSION['listado_terminados'];
			require ('views/pedidos/listado_terminados.php');
		} else {
			$terminados = $_SESSION['terminados'];
			require ('views/pedidos/listar_terminados.php');
		}
	}

///////////////// ******** ----                FIN listar_terminados   ------ ************ //////////////////

///////////////// ******** ----                        eliminar                        ------ ************ //////////////////
//////// Elimina el pedido
	// Como parametros puede recibir:
		// adicionales -> cadena con los id de los productos extras
		// adicionalesDesc -> descripcion de los productos extras
		// cantidad -> cantidad del pedido
		// comanda -> ID de la comanda
		// departamento -> ID del departamento
		// descripcion -> nombre del producto
		// idproducto -> ID del producto
		// sin -> cadena con los id de los productos sin
		// adicionales_desc -> descripcion de los productos extras
		// nota_sin -> nota de los productos sin
		// opcionales -> cadena con los id de los productos opcionales
		// opcionalesDesc -> descripcion de los productos extras
		// persona -> numero de persona
		// producto -> ID del pedido

	function eliminar($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Consulta los datos en la DB
		$resp['result'] = $this -> pedidosActivosModel -> eliminar($objeto);

	// 1 -> Todo bien :)
	// 2 -> Fallo la consulta :(
		if (!empty($resp['result'])) {
			session_start();
		// Elimina el pedido del array de pendientes
			$_SESSION["pedidos"][$id]["comanda"][$objeto["comanda"]]["persona"][$objeto["persona"]]["productos"][$objeto["producto"]] = '';
			unset($_SESSION["pedidos"][$id]["comanda"][$objeto["comanda"]]["persona"][$objeto["persona"]]["productos"][$objeto["producto"]]);
		
		// Agrega el pedido del array de los eliminados
			$_SESSION['eliminados'][$objeto['comanda']]['persona'][$objeto['persona']]['productos'][$objeto['producto']] = $objeto;
			$_SESSION['listado_eliminados'][$objeto['producto']] = $objeto;
		
		// Todo bien :D, regresa el resultado
			$resp['eliminados'] = $_SESSION['terminados'];
			$resp['status'] = 1;
		} else {
			$resp['status'] = 0;
		}

	// Regresa al ajax el mensaje
		echo json_encode($resp);
	}

///////////////// ******** ----                FIN eliminar                    ------ ************ //////////////////

///////////////// ******** ----                listar_eliminados               ------ ************ //////////////////
//////// Obtiene la vista de los pedidos eliminados y los carga a la div
	// Como parametros puede recibir:
		// div -> Div donde se debe de cargar la vista
		// vista_listado -> bandera para indicar si es listado o no (1-> listado, 0 -> comanda)

	function listar_eliminados($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		session_start();
		
	// Valida si se debe de mostrar en listado o por comandas los pedidos(1-> listado, 0->comandas)
		if ($objeto['vista_listado'] == 1) {
			$eliminados = $_SESSION['listado_eliminados'];
			require ('views/pedidos/listado_eliminados.php');
		} else {
			$eliminados = $_SESSION['eliminados'];
			require ('views/pedidos/listar_eliminados.php');
		}
	}

///////////////// ******** ----     		FIN listar_eliminados  	 			------ ************ //////////////////

///////////////// ******** ---- 			actualizar_configuracion			------ ************ //////////////////
//////// Actualiza la configuracion de Foodware
	// Como parametros recibe:
		// tipo -> tipo de operacion 1: Terminar Pedidos Después de Pago, 2: Mantener Pedidos Después de Pago
		// pedir_pass -> 1 -> debe pedir el password, 2 -> no

	function actualizar_configuracion($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Actualiza los campos en la BD
		$result = $this -> pedidosActivosModel -> actualizar_configuracion($objeto);

	// Regresa al ajax el mensaje
		echo json_encode($result);
	}

///////////////// ******** ---- 			FIN actualizar_configuracion		------ ************ //////////////////

} //Fin de la clase

?>