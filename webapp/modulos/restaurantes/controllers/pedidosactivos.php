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

		$_POST['agrupar_producto'] = 1;
		$pedidos_ticket = $this -> pedidosActivosModel -> getPedidos($_POST);
		$pedidos_ticket = $pedidos_ticket['rows'];
		
		foreach ($pedidos_ticket as $key => $value) {
		// Si son pedidos nuevos(status = 0), crea variables para el ticket
			if ($value['status'] == 0) {
				$pedidos_nuevos[$value["comanda"]]["nombre_mesa"] = $value["nombre_mesa"];
				$pedidos_nuevos[$value["comanda"]]["inicioPedido"] = $value["timestamp"];
				$pedidos_nuevos[$value["comanda"]]["comanda"] = $value["comanda"];
				$pedidos_nuevos[$value["comanda"]]["mesa"] = $value["idmesa"];
				$pedidos_nuevos[$value["comanda"]]["domicilio"] = $value["domicilio"];
				$pedidos_nuevos[$value["comanda"]]["tel"] = $value["tel"];
				$pedidos_nuevos[$value["comanda"]]["tipo"] = $value["tipo"];
				$pedidos_nuevos[$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["opcionalesDesc"] = $value["opcionalesDesc"];
				$pedidos_nuevos[$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["adicionalesDesc"] = $value["adicionalesDesc"];
				$pedidos_nuevos[$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["sin_desc"] = $value["sin_desc"];
				$pedidos_nuevos[$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["nota_sin"] = $value["nota_sin"];

				$pedidos_nuevos[$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["producto"] = utf8_decode($value["producto"]);
				$pedidos_nuevos[$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["idproducto"] = utf8_decode($value["idproducto"]);
				$pedidos_nuevos[$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["descripcion"] = $value["descripcion"];
				$pedidos_nuevos[$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["departamento"] = utf8_decode($value["departamento"]);
				$pedidos_nuevos[$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["opcionales"] = utf8_decode($value["opcionales"]);
				$pedidos_nuevos[$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["adicionales"] = utf8_decode($value["adicionales"]);
				$pedidos_nuevos[$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["sin"] = utf8_decode($value["sin"]);
				$pedidos_nuevos[$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["desc_kit"] = utf8_decode($value["desc_kit"]);
				$pedidos_nuevos[$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["tiempo_platillo"] = $value["tiempo_platillo"];
				if($value["notap"] != '') { $notap = '['.$value["notap"].']'; }else{ $notap = '';}
				$pedidos_nuevos[$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["notap"] = $notap;
				
				
				$pedidos_nuevos[$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["cantidad"] = $value["cantidad"];
			}
		}
		$_POST["perro"] = 1;
		$_POST["agrupar_producto"] = 0;
		$pedidos = $this -> pedidosActivosModel -> getPedidos($_POST);
		$pedidos = $pedidos['rows'];
		
		$ticket = $_POST['ticket'];
		$vista_listado = $_POST['vista_listado'];
		
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
			$pedidos_permanentes["pedidos"][$id]["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["costo"] = $value["costo"];

			$pedidos_permanentes["pedidos"][$id]["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["producto"] = utf8_decode($value["producto"]);
			$pedidos_permanentes["pedidos"][$id]["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["idproducto"] = utf8_decode($value["idproducto"]);
			$pedidos_permanentes["pedidos"][$id]["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["cantidad"] = utf8_decode($value["cantidad"]);
			$pedidos_permanentes["pedidos"][$id]["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["descripcion"] = $value["descripcion"];
			$pedidos_permanentes["pedidos"][$id]["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["departamento"] = utf8_decode($value["departamento"]);
			if($value["notap"] != '') { $notap = '['.$value["notap"].']'; }else{ $notap = '';}
			$pedidos_permanentes["pedidos"][$id]["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["notap"] = $notap; 
		
		// Array para la vista de listado
			$listado_pedidos[$key] = $value;
		}
			// Consulta los datos en la DB y los regresa en un array
			$ajustes = $this -> pedidosActivosModel -> listar_ajustes($objeto);
			$ajustes = $ajustes['rows'][0];


		  $datos['vista_listado'] = $vista_listado;
		  $datos['ticket'] = $ticket;
		  $datos['listado_pedidos'] = $listado_pedidos;

		  $datos['pedidos_nuevos'] = $pedidos_nuevos;
		  $datos['pedidos_permanentes'] = $pedidos_permanentes;
		  $datos['id'] = $id;


	   
	  	if($_POST['moduloPrint'] == 1){
	  		/// NUEVO PARA MODULO DE IMPRESION
		  	echo json_encode($datos);
		  	return json_encode($datos); 
		 }else{	  	
			// Valida si se debe de mostrar en listado o por comandas los pedidos(1-> listado, 0->comandas)
			if ($vista_listado == 1) {
				require ('views/pedidos/listado_pendientes.php');
			} else {
				require ('views/pedidos/listar_pendientes.php');
			}		
	  	}
	 
	}

	function ver2(){

		$datos = $_POST;
		$listado_pedidos = $datos['listado_pedidos'];
		$vista_listado = $datos['vista_listado'];
		// Valida si se debe de mostrar en listado o por comandas los pedidos(1-> listado, 0->comandas)
		if ($vista_listado == 1) {
			require ('views/pedidos/listado_pendientes.php');
		} else {
			require ('views/pedidos/listar_pendientes.php');
		}
	}

	function reimprime() {
		$_POST['agrupar_producto'] = 1;
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
				
				$arrayComanda["status"] = TRUE;
				$arrayComanda["organizacion"] = $_SESSION["accelog_nombre_organizacion"];
				$tablet_browser = 0;
				$mobile_browser = 0;
				$body_class = 'desktop';
				 
				if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
				    $tablet_browser++;
				    $body_class = "tablet";
				}
				 
				if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
				    $mobile_browser++;
				    $body_class = "mobile";
				}
				 
				if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
				    $mobile_browser++;
				    $body_class = "mobile";
				}
				 
				$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
				$mobile_agents = array(
				    'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
				    'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
				    'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
				    'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
				    'newt','noki','palm','pana','pant','phil','play','port','prox',
				    'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
				    'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
				    'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
				    'wapr','webc','winw','winw','xda ','xda-');
				 
				if (in_array($mobile_ua,$mobile_agents)) {
				    $mobile_browser++;
				}
				 
				if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0) {
				    $mobile_browser++;
				    //Check for tablets on opera mini alternative headers
				    $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
				    if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
				      $tablet_browser++;
				    }
				}
				if ($tablet_browser > 0) {
				// Si es tablet has lo que necesites
					$arrayComanda["dispositivo"] = 1;
				}
				else if ($mobile_browser > 0) {
				// Si es dispositivo mobil has lo que necesites
				 	$arrayComanda["dispositivo"] = 1;
				}
				else {
				// Si es ordenador de escritorio has lo que necesites
				  	$arrayComanda["dispositivo"] = 2;
				} 

				$arrayComanda["comanda"][$value["comanda"]]["inicioPedido"] = $value["timestamp"];
				$arrayComanda["comanda"][$value["comanda"]]["comanda"] = $value["comanda"];
				$arrayComanda["comanda"][$value["comanda"]]["mesa"] = $value["idmesa"];
				$arrayComanda["comanda"][$value["comanda"]]["nombre_mesa"] = $value["nombre_mesa"];
				$arrayComanda["comanda"][$value["comanda"]]["domicilio"] = $value["domicilio"];
				$arrayComanda["comanda"][$value["comanda"]]["tel"] = $value["tel"];
				$arrayComanda["comanda"][$value["comanda"]]["tipo"] = $value["tipo"];

			// Arma el array que se regresa a la reimpresion
				$arrayComanda["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["opcionales"] = utf8_decode($value["opcionales"]);
				$arrayComanda["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["adicionales"] = utf8_decode($value["adicionales"]);
				$arrayComanda["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["sin"] = utf8_decode($value["sin"]);

				$arrayComanda["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["opcionalesDesc"] = $value["opcionalesDesc"];
				$arrayComanda["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["adicionalesDesc"] = $value["adicionalesDesc"];
				$arrayComanda["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["sin_desc"] = $value["sin_desc"];
				$arrayComanda["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["nota_sin"] = $value["nota_sin"];
				$arrayComanda["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["desc_kit"] = utf8_decode($value["desc_kit"]);

				$arrayComanda["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["producto"] = utf8_decode($value["producto"]);
				$arrayComanda["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["idproducto"] = utf8_decode($value["idproducto"]);
				$arrayComanda["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["cantidad"] = $value["cantidad"];
				$arrayComanda["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["descripcion"] = $value["descripcion"];
				$arrayComanda["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["departamento"] = utf8_decode($value["departamento"]);
				$arrayComanda["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["tiempo_platillo"] = $value["tiempo_platillo"];
				$arrayComanda["comanda"][$value["comanda"]]["persona"][$value["persona"]]["productos"][$value["producto"]]["tiempo_platillo"] = $value["tiempo_platillo"];
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
		$clave_consumo = $this -> pedidosActivosModel -> clave_consumo();
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

///////////////// ******** ----                        reactivar                        ------ ************ //////////////////
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

	function reactivar($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Consulta los datos en la DB
		$resp['result'] = $this -> pedidosActivosModel -> reactivar($objeto);

	// 1 -> Todo bien :)
	// 2 -> Fallo la consulta :(
		if (!empty($resp['result'])) {
			session_start();
		// Elimina el pedido del array de pendientes
			$_SESSION["pedidos"][$id]["comanda"][$objeto["comanda"]]["persona"][$objeto["persona"]]["productos"][$objeto["producto"]] = $objeto;
			//unset($_SESSION["pedidos"][$id]["comanda"][$objeto["comanda"]]["persona"][$objeto["persona"]]["productos"][$objeto["producto"]]);
		
		// Agrega el pedido al array de terminados
			unset($_SESSION['terminados'][$objeto['comanda']]['persona'][$objeto['persona']]['productos'][$objeto['producto']]);
			unset($_SESSION['listado_terminados'][$objeto['producto']]);
		// Todo bien :D, regresa el resultado
			$resp['terminados'] = $_SESSION['terminados'];
			$resp['status'] = 1;
		} else {
			$resp['status'] = 0;
		}

	// Regresa al ajax el mensaje
		echo json_encode($resp);
	}

///////////////// ******** ----                FIN reactivar                    ------ ************ //////////////////

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
		$resp['result'] = $this -> pedidosActivosModel -> eliminar($objeto['pedido']);

	// 1 -> Todo bien :)
	// 2 -> Fallo la consulta :(
		if (!empty($resp['result'])) {
			
		// Guarda la merma
			if($objeto['merma'] == 1){
				$resp['result'] = $this -> pedidosActivosModel -> guardar_merma($objeto);
			}
			
			session_start();
		// Elimina el pedido del array de pendientes
			$_SESSION["pedidos"][$id]["comanda"][$objeto["pedido"]["comanda"]]["persona"][$objeto["pedido"]["persona"]]["productos"][$objeto["pedido"]["producto"]] = '';
			unset($_SESSION["pedidos"][$id]["comanda"][$objeto["pedido"]["comanda"]]["persona"][$objeto["pedido"]["persona"]]["productos"][$objeto["pedido"]["producto"]]);
		
		// Agrega el pedido del array de los eliminados
			$_SESSION['eliminados'][$objeto["pedido"]['comanda']]['persona'][$objeto["pedido"]['persona']]['productos'][$objeto["pedido"]['producto']] = $objeto["pedido"];
			$_SESSION['listado_eliminados'][$objeto["pedido"]['producto']] = $objeto["pedido"];
			
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

///////////////// ******** ----                monitorear_pedidos               ------ ************ //////////////////
//////// Carga la vista para monitorear los pedidos
	// Como parametros puede recibir:

	function monitorear_pedidos($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		require ('views/pedidos/monitorear_pedidos.php');
	}

///////////////// ******** ----     		FIN monitorear_pedidos  	 		------ ************ //////////////////

///////////////// ******** ----				listar_comandas_activas 			------ ************ //////////////////
//////// Obtiene la vista de los pedidos eliminados y los carga a la div
	// Como parametros puede recibir:
		// div -> Div donde se debe de cargar la vista

	function listar_comandas_activas($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		$comandas = $this -> pedidosActivosModel -> listar_comandas_activas($_POST);
		$comandas = $comandas['rows'];
		
		require ('views/pedidos/listar_comandas_activas.php');
	}

///////////////// ******** ----			FIN listar_comandas_activas 			------ ************ //////////////////

///////////////// ******** ----				actualizar_pedidos	 				------ ************ //////////////////
//////// Obtiene la vista de los pedidos eliminados y los carga a la div
	// Como parametros puede recibir:
		// id_comanda -> ID de la comanda

	function actualizar_pedidos($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Actualiza los pedidos en la BD
		$result = $this -> pedidosActivosModel -> actualizar_pedidos($objeto);

	// Regresa al ajax el mensaje
		echo json_encode($result);
	}

///////////////// ******** ----				FIN actualizar_pedidos	 			------ ************ //////////////////


	function todasAreas() {
		$result = $this -> pedidosActivosModel -> todasAreas();
		echo json_encode($result);
	}

	function moduloPrint(){
		echo $this -> pedidosActivosModel -> moduloPrint();
	}

} //Fin de la clase

?>