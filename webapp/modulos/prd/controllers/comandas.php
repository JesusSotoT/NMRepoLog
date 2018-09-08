<?php
require ('common.php');
require ("models/comandas.php");

class comandas extends Common {
	public $comandasModel;

	function __construct() {
		$this -> comandasModel = new comandasModel();
	}

	function menuMesas() {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		session_start();
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$objeto['permisos'] = $_SESSION['mesero']['permisos'];
		
	// Valida que la funcion se llame por primera vez o al momento de unir las mesas
		if ($_SESSION['area'] != 1) {
			$result = $this -> comandasModel -> getTables($objeto);
			$result = $result['rows'];

			$_SESSION['tables'] = $result;
		}

		$_SESSION['area'] = 0;

	// Consulta los datos de la mesa en la DB
		$areas = $this -> comandasModel -> areas($objeto);

	// Consulta las comandas y las regresa en un array
		$empleados = $this -> comandasModel -> listar_empleados($objeto);
		
	// Consulta los empleados y los regresa en un array
		$clientes = $this -> comandasModel -> listar_clientes($objeto);
		$clientes = $clientes['rows'];
		
	// Consulta las vias de contacto y las regresa en un array
		$vias_contacto = $this -> comandasModel -> listar_vias_contacto($objeto);
		$vias_contacto = $vias_contacto['rows'];

	// Consulta las vias de contacto y las regresa en un array
		$zonas_reparto = $this -> comandasModel -> listar_zonas_reparto($objeto);
		$zonas_reparto = $zonas_reparto['rows'];
		
	// Consulta los ajustes de Foodware
		$configuracion = $this -> comandasModel -> listar_ajustes($objeto);
		$configuracion = $configuracion['rows'][0];
		
		session_start();
		$_SESSION['kit'] = '';

	//ch@
	// Consulta para repartidores disponibles	 CH
		$repartidores = $this -> comandasModel -> listar_repartidores($objeto);

		require ('views/comandas/Gmesas.php');
	}

	function addComanda() {
		$idmesa = $_GET['idmesa'];
		$iddeparment = $_GET['iddeparment'];

		if ($this -> comandasModel -> insertComanda($idmesa, $iddeparment)) {
			$this -> mesa();
		} else {
			echo "No se pudo crear la comanda!";
		}

		return;
	}

	function deleteComanda() {
		$idcomanda = $_GET['idcomanda'];
		$idmesa = $_GET['idmesa'];
		$id_reservacion = $_GET['id_reservacion'];

		$result = $this -> comandasModel -> deleteComanda($idcomanda, $idmesa, $id_reservacion);

		if ($result)
			echo "Comanda borrada correctamente";
		else
			echo "No se pudo borrar la comanda!";
	}

///////////////// ******** ----  		lessProduct					------ ************ //////////////////
// Resta la cantidad de la orden y lista los productos de la persona
	// Como parametro puede recibi:
		// idorder -> ID del pedido
		// idcomanda -> ID de la comanda
		// idperson -> numero de  persona

	function lessProduct($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		$idorder = $objeto['idorder'];
		$comanda = $objeto['idcomanda'];
		$person = $objeto['idperson'];

		if ($this -> comandasModel -> lessProduct($idorder))
			$this -> getItemsPerson($person, $comanda);

	}

///////////////// ******** ---- 		FIN lessProduct		------ ************ //////////////////

///////////////// ******** ----  deleteProduct		------ ************ //////////////////
// Elimina la orden de la comanda y lista los productos de la persona
	// Como parametro puede recibi:
		// idorder -> ID del pedido
		// idcomanda -> ID de la comanda
		// idperson -> numero de  persona

	function deleteProduct($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		$idorder = $objeto['idorder'];
		$comanda = $objeto['idcomanda'];
		$person = $objeto['idperson'];
		$result = $this -> comandasModel -> deleteProduct($objeto);

		if (!empty($result)) {
			$this -> getItemsPerson($person, $comanda);
		}
	}

///////////////// ******** ---- 	FIN deleteProduct		------ ************ //////////////////

///////////////// ******** ----  		sumar_pedido					------ ************ //////////////////
// Aumenta la cantidad de la orden y lista los productos de la persona
	// Como parametro puede recibi:
		// idorder -> ID del pedido
		// idcomanda -> ID de la comanda
		// idperson -> numero de  persona

	function sumar_pedido($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		$resp['result'] = $this -> comandasModel -> sumar_pedido($objeto);

	// 1 -> Todo bien :)
	// 2 -> Fallo la consulta :(
		$resp['status'] = (!empty($resp['result']['comanda'])) ? 1 : 0;

	// Regresa al ajax el resultado
		echo json_encode($resp);
	}

///////////////// ******** ---- 	FIN sumar_pedido		------ ************ //////////////////

	function mesa() {
		session_start();
		$idmesa = $_GET['idmesa'];
		$tipo = $_GET['tipo'];
		$id_reservacion = $_GET['id_reservacion'];
		$comanda = $this -> comandasModel -> getComanda($idmesa);
		$deparments = $this -> comandasModel -> getDeparments();

		$products = null;
		$persons = null;
		$row = $comanda -> fetch_array();

		if($row['id'] != ''){
			$repartidor = $this -> comandasModel -> verAsignadoM($row['id']);
			$repa = $repartidor[0]['id_repartidor'];
		}

		if ($row) {
		// Si viene el nombre y la direccion se le asignan a una variable de sesion
		// si no conserva su valor
			$_SESSION['nombre'] = (!empty($_GET['nombre'])) ? $_GET['nombre'] : $_SESSION['nombre'];
			$_SESSION['direccion'] = (!empty($_GET['direccion'])) ? $_GET['direccion'] : $_SESSION['direccion'];
			$_SESSION['tel'] = (!empty($_GET['tel'])) ? $_GET['tel'] : $_SESSION['tel'];
			
			$objeto['id_mesa'] = $idmesa;

		// Consulta si existe una union de mesas
			$mesas_juntas = $this -> comandasModel -> mesas_juntas($objeto);
			
		// Consulta las mesas libres
		// $objeto['permisos']=$_SESSION['mesero']['permisos'];
			$mesas_libres = $this -> comandasModel -> mesas_libres($objeto);

			$nombre = $_SESSION['nombre'];
			$nombre = str_replace('"', '', $nombre);
			$direccion = $_SESSION['direccion'];
			$direccion = str_replace('"', '', $direccion);
			$tel = $_SESSION['tel'];
			$tel = str_replace('"', '', $tel);
			
		// Consulta los productos
			$products = $this -> comandasModel -> getProducts(0, 0, 0);

		// ** Consultamos el dia y la hora para los productos especiales
			date_default_timezone_set('America/Mexico_City');
		// Calcula el dia en numero 0-6 Domingo-Sabado
			$fecha = date('Y-m-d');
			$dia = date('w');
		// Obtiene la hora actual
			$hora = strtotime(date('H:i'));

		// Recorre los resgitros para ordenarlos
			foreach ($products['rows'] as $key => $value) {
			/* Impuestos del producto
			============================================================================= */

				$objeto['id'] = $value['idProducto'];
				$impuestos = $this -> comandasModel -> listar_impuestos($objeto);
				if ($impuestos['total'] > 0) {
					$impuestos_comanda = 0;
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
						$products['rows'][$key]['precioventa'] += $producto_impuesto;
						$products['rows'][$key]['precioventa'] = round($products['rows'][$key]['precioventa'], 2);
					}
				}

			/* FIN Impuestos del producto
			============================================================================= */

			// Valida que exista la imagen
				if (!empty($value['imagen'])) {
					$src = '../pos/' . $value['imagen'];
					$products['rows'][$key]['imagen'] = (file_exists($src)) ? $src : '';
				} else {
					$products['rows'][$key]['imagen'] = '';
				}

			// Consulta si se encuentra el platillo actual en el dia
				$busca = strpos($value['dias'], $dia);

				if (!empty($busca)) {
					$h_ini = strtotime($value['inicio']);
					$h_fin = strtotime($value['fin']);

				// Si el platillo se encuentra en el horario lo inserta al principio del array
					if ($hora >= $h_ini && $hora <= $h_fin) {
						$elemento = $products['rows'][$key];
						$elemento['especial'] = 1;
						unset($products['rows'][$key]);
						array_unshift($products['rows'], $elemento);
					}
				}
			}

		// Obtiene el numero de personas
			$persons = $this -> comandasModel -> getPersons($row['id']);

		// Obtiene el listado de los empleados
			$empleados = $this -> comandasModel -> listar_empleados();
			
		// Obtiene las configuraciones
			$configuraciones = $this -> comandasModel -> listar_ajustes();
			$configuraciones = $configuraciones['rows'][0];
			
			session_start();
			$_SESSION['kit'] = '';
		
			require ('views/comandas/comanda.php');
		} else {
			//Agrega una comanda
			$this -> addComanda();
		}
	}

	function getItemsPerson($person = 0, $comanda = 0) {
		if (isset($_GET['idperson']))
			$person = $_GET['idperson'];
		if (isset($_GET['idcomanda']))
			$comanda = $_GET['idcomanda'];
		if ($products = $this -> comandasModel -> getItemsPerson($person, $comanda)) {
			echo json_encode($products);
		}
	}

	function incrementPersons() {
		$comanda = $_GET['idcomanda'];
		$persons = $_GET['persons'];
		if ($idperson = $this -> comandasModel -> incrementPersons($comanda)) {
			echo json_encode(Array('idperson' => $idperson));
		}
	}

	function deletePersons() {
		$comanda = $_GET['idcomanda'];
		$persons = $_GET['idspersons'];
		if ($this -> comandasModel -> deletePersons($comanda, $persons))
			echo "Las personas han sido eliminadas!";
		else
			echo "Error al eliminar usuarios!";
	}

///////////////// ******** ---- 			addProduct			------ ************ //////////////////
// Agrega un producto a la persona, actualiza el total y los impuestos de la comanda
	// Como parametros puede recibir:
		// $idproduct -> ID del producto
		// $idperson -> ID de la persona
		// $idcomanda -> ID de la comanda
		// $opcionales -> Cadena con los IDs de los productos opcionales
		// $extras -> Cadena con los IDs de los productos extras
		// $normales -> Cadena con los IDs de los productos normales
		// $iddep -> ID del departamento
		// $nota_opcional -> string con la nota de los productos opcionales
		// $nota_extra -> string con la nota de los productos extras
		// $nota_normal -> string con la nota de los productos normales

	function addProduct($opcionales = "", $extras = "", $sin = "", $nota_opcional = "", $nota_extra = "", $nota_sin = "") {
		$idproduct = $_GET['idproduct'];
		$idperson = $_GET['idperson'];
		$idcomanda = $_GET['idcomanda'];
		$iddep = $_GET['iddep'];

		$data = $this -> comandasModel -> addProduct($idproduct, $idperson, $idcomanda, $opcionales, $extras, $sin, $iddep, $nota_opcional, $nota_extra, $nota_sin);

		if ((isset($data["status"]) && $data["status"] == false) || $data == false) {
			if (isset($_GET['idperson']))
				$person = $_GET['idperson'];
			if (isset($_GET['idcomanda']))
				$comanda = $_GET['idcomanda'];
			if ($products = $this -> comandasModel -> getItemsPerson($person, $comanda)) {
				echo json_encode(array_merge($products, $data));
			}
		} else {
			$this -> getItemsPerson($idperson, $idcomanda);
		}
	}

///////////////// ******** ---- 		FIN addProduct			------ ************ //////////////////

	function getDeparments() {
		if ($deparments = $this -> comandasModel -> getDeparments()) {
			$products = $this -> comandasModel -> getProducts(0, 0, 0);

			// Calcula el dia en numero 0-6 Domingo-Sabado
			$fecha = date('Y-m-d');
			$dia = date('w', strtotime($fecha));

			// Obtiene la hora actual
			$hora = strtotime(date('H:i'));

			// Recorre los resgitros para ordenarlos
			foreach ($products['rows'] as $key => $value) {
			/* Impuestos del producto
			============================================================================= */

				$objeto['id'] = $value['idProducto'];
				$impuestos = $this -> comandasModel -> listar_impuestos($objeto);
				if ($impuestos['total'] > 0) {
					$impuestos_comanda = 0;
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
						$products['rows'][$key]['precioventa'] += $producto_impuesto;
						$products['rows'][$key]['precioventa'] = round($products['rows'][$key]['precioventa'], 2);
					}
				}

			/* FIN Impuestos del producto
			============================================================================= */

			// Valida que exista la imagen
				if (!empty($value['imagen'])) {
					$src = '../pos/' . $value['imagen'];
					$products['rows'][$key]['imagen'] = (file_exists($src)) ? $src : '';
				} else {
					$products['rows'][$key]['imagen'] = '';
				}

			// Consulta si se encuentra el platillo actual en el dis
				$busca = strpos($value['dias'], $dia);

				if (!empty($busca)) {
					$h_ini = strtotime($value['inicio']);
					$h_fin = strtotime($value['fin']);

				// Si el platillo se encuentra en el horario lo inserta al principio del array
					if ($hora >= $h_ini && $hora <= $h_fin) {
						$elemento = $value;
						$elemento['especial'] = 1;
						unset($products['rows'][$key]);
						array_unshift($products['rows'], $elemento);
					}
				}
			}

			echo json_encode(Array('deparments' => $deparments, 'products' => $products));
		}
	}

	function getFamilies() {
		$idDeparment = $_GET['idDeparment'];
		if ($family = $this -> comandasModel -> getFamilies($idDeparment)) {
			$products = $this -> comandasModel -> getProducts($idDeparment, 0, 0);

			// Calcula el dia en numero 0-6 Domingo-Sabado
			$fecha = date('Y-m-d');
			$dia = date('w', strtotime($fecha));

			// Obtiene la hora actual
			$hora = strtotime(date('H:i'));

			// Recorre los resgitros para ordenarlos
			foreach ($products['rows'] as $key => $value) {
			/* Impuestos del producto
			============================================================================= */

				$objeto['id'] = $value['idProducto'];
				$impuestos = $this -> comandasModel -> listar_impuestos($objeto);
				if ($impuestos['total'] > 0) {
					$impuestos_comanda = 0;
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
						$products['rows'][$key]['precioventa'] += $producto_impuesto;
						$products['rows'][$key]['precioventa'] = round($products['rows'][$key]['precioventa'], 2);
					}
				}

			/* FIN Impuestos del producto
			============================================================================= */

			// Valida que exista la imagen
				if (!empty($value['imagen'])) {
					$src = '../pos/' . $value['imagen'];
					$products['rows'][$key]['imagen'] = (file_exists($src)) ? $src : '';
				} else {
					$products['rows'][$key]['imagen'] = '';
				}

			// Consulta si se encuentra el platillo actual en el dis
				$busca = strpos($value['dias'], $dia);

				if (!empty($busca)) {
					$h_ini = strtotime($value['inicio']);
					$h_fin = strtotime($value['fin']);

				// Si el platillo se encuentra en el horario lo inserta al principio del array
					if ($hora >= $h_ini && $hora <= $h_fin) {
						$elemento = $value;
						$elemento['especial'] = 1;
						unset($products['rows'][$key]);
						array_unshift($products['rows'], $elemento);
					}
				}
			}

			echo json_encode(Array('families' => $family, 'products' => $products));
		}
	}

	function getLines() {
		$idFamily = $_GET['idFamily'];
		if ($line = $this -> comandasModel -> getLines($idFamily)) {
			$products = $this -> comandasModel -> getProducts(0, $idFamily, 0);

			// Calcula el dia en numero 0-6 Domingo-Sabado
			$fecha = date('Y-m-d');
			$dia = date('w', strtotime($fecha));

			// Obtiene la hora actual
			$hora = strtotime(date('H:i'));

			// Recorre los resgitros para ordenarlos
			foreach ($products['rows'] as $key => $value) {
			/* Impuestos del producto
			============================================================================= */

				$objeto['id'] = $value['idProducto'];
				$impuestos = $this -> comandasModel -> listar_impuestos($objeto);
				if ($impuestos['total'] > 0) {
					$impuestos_comanda = 0;
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
						$products['rows'][$key]['precioventa'] += $producto_impuesto;
						$products['rows'][$key]['precioventa'] = round($products['rows'][$key]['precioventa'], 2);
					}
				}

			/* FIN Impuestos del producto
			============================================================================= */

			// Valida que exista la imagen
				if (!empty($value['imagen'])) {
					$src = '../pos/' . $value['imagen'];
					$products['rows'][$key]['imagen'] = (file_exists($src)) ? $src : '';
				} else {
					$products['rows'][$key]['imagen'] = '';
				}

			// Consulta si se encuentra el platillo actual en el dis
				$busca = strpos($value['dias'], $dia);

				if (!empty($busca)) {
					$h_ini = strtotime($value['inicio']);
					$h_fin = strtotime($value['fin']);

				// Si el platillo se encuentra en el horario lo inserta al principio del array
					if ($hora >= $h_ini && $hora <= $h_fin) {
						$elemento = $value;
						$elemento['especial'] = 1;
						unset($products['rows'][$key]);
						array_unshift($products['rows'], $elemento);
					}
				}
			}

			echo json_encode(Array('lines' => $line, 'products' => $products));
		}
	}

	function getProducts() {
		$idLine = $_GET['idLine'];
		$idComanda = $_GET['idComanda'];
		if ($idProduct = $this -> comandasModel -> getProducts(0, 0, $idLine)) {

			// Calcula el dia en numero 0-6 Domingo-Sabado
			$fecha = date('Y-m-d');
			$dia = date('w', strtotime($fecha));

			// Obtiene la hora actual
			$hora = strtotime(date('H:i'));

			// Recorre los resgitros para ordenarlos
			foreach ($idProduct['rows'] as $key => $value) {
			/* Impuestos del producto
			============================================================================= */

				$objeto['id'] = $value['idProducto'];
				$impuestos = $this -> comandasModel -> listar_impuestos($objeto);
				if ($impuestos['total'] > 0) {
					$impuestos_comanda = 0;
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
						$idProduct['rows'][$key]['precioventa'] += $producto_impuesto;
						$idProduct['rows'][$key]['precioventa'] = round($idProduct['rows'][$key]['precioventa'], 2);
					}
				}

			/* FIN Impuestos del producto
			============================================================================= */
			// Valida que exista la imagen
				if (!empty($value['imagen'])) {
					$src = '../pos/' . $value['imagen'];
					$idProduct['rows'][$key]['imagen'] = (file_exists($src)) ? $src : '';
				} else {
					$idProduct['rows'][$key]['imagen'] = '';
				}

			// Consulta si se encuentra el platillo actual en el dis
				$busca = strpos($value['dias'], $dia);

				if (!empty($busca)) {
					$h_ini = strtotime($value['inicio']);
					$h_fin = strtotime($value['fin']);

				// Si el platillo se encuentra en el horario lo inserta al principio del array
					if ($hora >= $h_ini && $hora <= $h_fin) {
						$elemento = $value;
						$elemento['especial'] = 1;
						unset($idProduct['rows'][$key]);
						array_unshift($idProduct['rows'], $elemento);
					}
				}
			}

			echo json_encode($idProduct);
		}
	}

///////////////// ******** ---- 	closeComanda		------ ************ //////////////////
// Cierra la comanda, separa las mesas(si existen), elimina la mesa(si es temporal), Actualiza el inventario.
	// Como parametros puede recibir:
		// $idComanda -> ID de la comanda
		// $bandera -> si existen o no productos extra u opcionales
		// $idmesa -> ID de la mesa
		// $tipo -> si es mesa temporal(para llevar, servicio a domicilio) o normal
		// $id_reservacion -> ID de la reservacion(si existe)

	function closeComanda($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
	// Cerramos la comanda y regresamos el resultado
		$comanda = $this -> comandasModel -> closeComanda($objeto);

	// Optenemos el logo
		$logo = $this -> comandasModel -> logo($objeto);
		$comanda['logo'] = $logo['rows'][0]['logo'];

		echo json_encode($comanda);
	}

///////////////// ******** ---- 	FIN closeComanda		------ ************ //////////////////

	function reImprimeComanda() {
		$idComanda = $_GET['idComanda'];
		$bandera = $_GET['bandera'];
		$idmesa = $_GET['idmesa'];
		$tipo = $_GET['tipo'];

		if ($comanda = $this -> comandasModel -> reImprimeComanda($idComanda, $bandera, $idmesa, $tipo)) {
			// Optenemos el logo
			$logo = $this -> comandasModel -> logo($objeto);
			$comanda['logo'] = $logo['rows'][0]['logo'];

			echo json_encode($comanda);

		}
	}

	function getItemsProduct() {
		$idProduct = $_GET['idProduct'];

		if ($items = $this -> comandasModel -> getItemsProduct($idProduct))
			echo json_encode($items);
	}

// Manda llamar a la funcion que agrega los productos a las personas
	function addItemsProduct() {
		$opcionales = $_GET['opcionales'];
		$extras = $_GET['extras'];
		$sin = $_GET['sin'];

		$nota_opcional = $_REQUEST['nota_opcional'];
		$nota_extra = $_REQUEST['nota_extra'];
		$nota_sin = $_REQUEST['nota_sin'];

		$result = $this -> addProduct($opcionales, $extras, $sin, $nota_opcional, $nota_extra, $nota_sin);
	}

///////////////// ******** ---- 	para_llevar		------ ************ //////////////////
//////// Manda llamar a la funcion que inserta una mesa temporal en la BD y regresa el ID de la mesa
	// Como parametros puede recibir:
		// nombre-> nombre del cliente
		// domicilio-> direccion

	function foodGo($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Agrega la mesa temporal y consulta el ID
		$id = $this -> comandasModel -> addTemporalTableFg($objeto);
		
		require ('views/comandera/vista_nueva_mesa.php');
	}

///////////////// ******** ---- 	FIN para_llevar		------ ************ //////////////////

///////////////// ******** ---- 	servicio_domicilio		------ ************ //////////////////
//////// Manda llamar a la funcion que inserta una mesa temporal en la BD y regresa el ID de la mesa
	// Como parametros puede recibir:
		// name-> nombre del cliente
		// address-> direccion

	function deliveryService($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Agrega la mesa temporal y consulta el ID
		$id = $this -> comandasModel -> addTemporalTableDs($objeto);
		
		require ('views/comandera/vista_nueva_mesa_domicilio.php');
	}

///////////////// ******** ---- 	servicio_domicilio		------ ************ //////////////////

//** Elimina la mesa
	function removeTable() {
		$idmesa = $_GET['idmesa'];
		$this -> comandasModel -> removeTable($idmesa);
	}

	//** Junta las mensas
	function joinTables() {
		$jtables = $_GET['jtables'];

		$this -> comandasModel -> joinTables($jtables);
	}

// ** Procesa los pedidos
	function process() {
		$idcomanda = $_GET['idcomanda'];
		$result = $this -> comandasModel -> process($idcomanda);
		echo json_encode($result);
	}

	function checkProductos() {
		$resultado = $this -> comandasModel -> checkProducts();

		/*
		 Reorganizamos el array para que los productos de la misma comanda queden en la misma posicion.
		 */
		$newArray = array();

		foreach ($resultado["rows"] as $key => $value) {
			$newArray["status"] = true;
			$newArray["comandas"][$value["idcomanda"]]["productos"] = $newArray["comandas"][$value["idcomanda"]]["productos"] . "|" . $value["idproducto"];
			$newArray["comandas"][$value["idcomanda"]]["lugar"] = $value["lugar"];
		}

		echo json_encode($newArray);
	}

	function checkMesas() {
		$resultado = $this -> comandasModel -> checkTables();

		$newArray = array();
		foreach ($resultado['rows'] as $key => $value) {
			//echo $value.'eeidmesa';
			$newArray["status"] = true;
			$newArray["comandas"][$value["idmesa"]]["mesa"] = $value["idmesa"];
			$newArray["comandas"][$value["idmesa"]]["estado"] = $value["status"];
			$newArray["comandas"][$value["idmesa"]]["id_comanda"] = $value["id_comanda"];
		}
		//print_r($newArray);
		echo json_encode($newArray);
	}

	function entregado() {
		$comanda = $_POST['comanda'];
		$ids = $_POST['ids'];
		$result = $this -> comandasModel -> entregado($comanda, $ids);

		echo $result;
	}

	function getNames() {
		$name = $_GET['name'];

		if ($result = $this -> comandasModel -> getNames($name))
			echo json_encode($result);
	}

///////////////// ******** ---- 	buscar_direccion		------ ************ //////////////////
//////// Manda llamar a la funcion que consulta los datos del cliente en la BD
	// Como parametros puede recibir:
		// nombre-> nombre escrito en el campo de texto

	function buscar_direccion($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Consulta si el cliente tiene alguna direccion, si es asi la devuelve, si no regresa un cero
		$resp['result'] = $this -> comandasModel -> buscar_direccion($objeto);
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0;

		echo json_encode($resp);
	}

///////////////// ******** ---- 	FIN buscar_direccion		------ ************ //////////////////

///////////////// ******** ---- 			logo				------ ************ //////////////////
// Consulta el logo de la empresa
	// Como parametro recibe:
		// id-> id de la empresa

	function logo($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Consulta si el logo de la empresa
		$result = $this -> comandasModel -> logo($objeto);

		$result = $result['rows'][0]['logo'];

	// Regresa al ajax el resultado
		echo json_encode($result);
	}

///////////////// ******** ---- 			FIN logo			------ ************ //////////////////

///////////////// ******** ---- 			info_estados		------ ************ //////////////////
//////// Optiene la informacion de los estados
//////// Crea un select con la informacion de los estados
	// Como parametros puede recibir:

	function info_estados($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// Consulta si el logo de la empresa
		$result = $this -> comandasModel -> info_estados($objeto);

		$result = $result['rows'];

		// Regresa al ajax el resultado
		echo json_encode($result);
	}

///////////////// ******** ---- 	FIN	info_estados			------ ************ //////////////////

///////////////// ******** ---- 		info_municipios			------ ************ //////////////////
//////// Optiene la informacion de los municipios
//////// Crea un select con la informacion de los municipios
	// Como parametros puede recibir:
		// id-> id del estado

	function info_municipios($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Consulta si el logo de la empresa
		$result = $this -> comandasModel -> info_municipios($objeto);

		$result = $result['rows'];

	// Regresa al ajax el resultado
		echo json_encode($result);
	}

///////////////// ******** ---- 	FIN	info_municipios		------ ************ //////////////////

///////////////// ******** ---- 		agregar_cliente		------ ************ //////////////////
//////// Agrega un cliente a la base de datos en la tabla comun_cliente
	// Como parametros puede recibir:
	// Campos del formulario:
		// -> Nombre, Direccion, Numero interios, Numero Exterior
		// -> Colonia, CP, estado, Municipio, E-mail, Tel

	function agregar_cliente($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Inserta un registro en la base de datos
		$result = $this -> comandasModel -> agregar_cliente($objeto);

	// Regresa al ajax el mensaje
		echo json_encode($result);
	}

///////////////// ******** ---- 	FIN	agregar_cliente		------ ************ //////////////////

///////////////// ******** ---- 	separar_mesas			------ ************ //////////////////
//////// Separa las mesas unidas
	// Como parametros puede recibir:
		// $objeto-> un objeto con el contenido del regitro de la mesa
		// idprincipal-> id de union en la tabla com_union
		// idmesa-> el id de la mesa en la que se guardaran los pedidos
		// idcomanda-> el id de la comanda

	function separar_mesas($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Inserta un registro en la base de datos
		$result = $this -> comandasModel -> separar_mesas($objeto);
	
	// Regresa al ajax el mensaje
		echo json_encode($result);
	}

///////////////// ******** ---- 	FIN	separar_mesas		------ ************ //////////////////

///////////////// ******** ---- 	promedio_comensal		------ ************ //////////////////
//////// Registra el promedio por comensal de la comanda
	// Como parametros puede recibir:
		// 	promedio -> promedio por comensal de la comanda a registrar
		//	comanda -> id de la comanda

	function guardar_promedio_comensal($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Actualiza el costo promedio por comensal de la comanda
		$result = $this -> comandasModel -> guardar_promedio_comensal($objeto);

	// Regresa al ajax el mensaje
		echo json_encode($result);
	}

///////////////// ******** ---- 	FIN	promedio_comensal			------ ************ //////////////////

///////////////// ******** ---- 	vista_promedio_comensal			------ ************ //////////////////
//////// Carga la vista en la que se consulta el promedio por comensal

	function vista_promedio_comensal($objeto) {
	// Consulta las sucursales y las regresa en un array
		$sucursales = $this -> comandasModel -> listar_sucursales($objeto);
		$sucursales = $sucursales['rows'];

	// Consulta los empleado sy los regresa en un array
		$empleados = $this -> comandasModel -> listar_empleados($objeto);
		
	// Carga la vista del promedio por comensal
		require ('views/comandas/vista_promedio_comensal.php');
	}

///////////////// ******** ---- 	FIN	vista_promedio_comensal		------ ************ //////////////////

///////////////// ******** ---- 		promedio_comensal			------ ************ //////////////////
//////// Consulta el promedio por comensal y lo agrega a la div
	// Como parametros recibe:
		// f_ini -> fecha y hora de inicio
		// F_fin -> fecha y hora final
		// sucursal -> ID de la sucursal
		// empleado -> ID del empleado
		// comensales -> Numero de comensales
	
	function promedio_comensal($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Formatea la fecha y la hora
		$objeto['f_ini'] = str_replace('T', ' ', $objeto['f_ini']).' 00:01';
		$objeto['f_fin'] = str_replace('T', ' ', $objeto['f_fin']).' 23:59';

	// Consulta los promedios por comanda y los regresa en un array
		$promedios = $this -> comandasModel -> promedio_comensal($objeto);
		
	// Consulta las comandas y las regresa en un array para la grafica lineal
		$objeto['agrupar'] = 'WEEK(timestamp)';
		$objeto['status'] = '*';
		$objeto['mesa'] = '*';
		$objeto['orden'] = 'c.id ASC';
		$comandas = $this -> comandasModel -> listar_comandas($objeto);
		$comandas = $comandas['rows'];

	// Arma el array para la grafica lineal
		foreach ($comandas as $key => $value) {
			$lineal[$key]['comandas'] = $value['comandas'];
			$lineal[$key]['promedioComensal'] = $value['promedioComensal'];
			$lineal[$key]['timestamp'] = $value['timestamp'];
		}

	// carga la vista para listar los promedios
		require ('views/comandas/listar_promedio_comensal.php');
	}

///////////////// ******** ---- 		FIN promedio_comensal		------ ************ //////////////////

///////////////// ******** ---- 			detalles_mesa			------ ************ //////////////////
//////// Obtiene los datos de la mesa
	// Como parametros puede recibir:
		//	id -> id de la mesa

	function detalles_mesa($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Consulta los datos de la mesa en la DB
		$resp = $this -> comandasModel -> detalles_mesa($objeto);
		$resp = $resp['rows'][0];

		$resp['status'] = (!empty($resp)) ? 1 : 0;

		date_default_timezone_set('America/Mexico_City');

	// Calcula el dia en numero 0-6 Domingo-Sabado
		$fecha = date('Y-m-d');
		$dia = date('w');
		
	// Obtiene la hora actual
		$hora = date('H:i');

		$resp['fecha'] = $fecha;
		$resp['dia'] = $dia;
		$resp['hora'] = $hora;

	// Regresa al ajax el mensaje
		echo json_encode($resp);
	}

///////////////// ******** ---- 		FIN	detalles_mesa			------ ************ //////////////////

///////////////// ******** ---- 		guardar_cordenadas			------ ************ //////////////////
//////// Guarda las cordenadas de la mesa actual en la BD
	// Como parametros recibe:
		// id -> id de la mesa que se movera
		// x -> numero con la distancia del objeto hacia la derecha
		// y -> numero con la distancia del objeto hacia abajo

	function guardar_cordenadas($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Guarda las nuevas cordenadas en la BD
		$result = $this -> comandasModel -> guardar_cordenadas($objeto);

	// Regresa al ajax el mensaje
		echo json_encode($result);
	}

///////////////// ******** ---- 		FIN guardar_cordenadas		------ ************ //////////////////

///////////////// ******** ---- 				mover				------ ************ //////////////////
//////// Consulta las coordenadas en la DB y establece la posicion de la mesa
	// Como parametros recibe:
		// id -> id de la mesa que se movera

	function mover($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Consulta los datos de la mesa en la DB
		$result = $this -> comandasModel -> mover($objeto);

	// Regresa los valores del primer reistro
		$result = $result[0];

	// Regresa al ajax el mensaje
		echo json_encode($result);
	}

///////////////// ******** ---- 				FIN mover			------ ************ //////////////////

///////////////// ******** ---- 				areas				------ ************ //////////////////
//////// Obtiene el listado de las areas en las que estan las mesas
	// Como parametros recibe:
		// id -> id del area

	function areas($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		session_start();
		$objeto['permisos'] = $_SESSION['mesero']['permisos'];

	// Consulta los datos de la mesa en la DB
		$result = $this -> comandasModel -> getTables($objeto);
		$result = $result['rows'];

		$_SESSION['tables'] = $result;
		$_SESSION['area'] = 1;

	// Regresa al ajax el mensaje
		echo json_encode($_SESSION['tables']);
	}

///////////////// ******** ---- 			FIN areas				------ ************ //////////////////

///////////////// ******** ---- 		buscar_reservaciones		------ ************ //////////////////
//////// Consulta si hay reservaciones para la hora actual
	// Como parametros recibe:
		// fecha-> fecha y hora a buscar

	function buscar_reservaciones($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Si no tiene una fecha le asigna la fecha y hora actual
		$objeto['fecha'] = (empty($objeto['fecha'])) ? date('Y-m-d') : $objeto['fecha'];

	// Consulta los datos de la mesa en la DB
		$result = $this -> comandasModel -> buscar_reservaciones($objeto);

	// Regresa al ajax el mensaje
		echo json_encode($result);
	}

///////////////// ******** ---- 		FIN buscar_reservaciones		------ ************ //////////////////

///////////////// ******** ---- 			buscar_productos			------ ************ //////////////////
//////// Llama a la funcion que consulta a la BD, carga la vista con los datos correspondientes
	// Como parametros recibe:
		// texto -> palabra u oracion a buscar en los productos
		// div -> div donde se cargaran los resultados
		// comanda -> ID de la comanda
		// departamento -> ID del departamento
		// familia -> ID de la familia
		// linea -> id de la linea

	function buscar_productos($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Consulta los datos de la mesa en la DB
		$productos = $this -> comandasModel -> buscar_productos($objeto);
		
		// echo $productos;

	// Calcula el dia en numero 0-6 Domingo-Sabado
		$fecha = date('Y-m-d');
		$dia = date('w', strtotime($fecha));

	// Obtiene la hora actual
		$hora = strtotime(date('H:i'));

	// Recorre los resgitros para ordenarlos
		foreach ($productos['rows'] as $key => $value) {
		/* Impuestos del producto
		============================================================================= */

			$objeto['id'] = $value['idProducto'];
			$impuestos = $this -> comandasModel -> listar_impuestos($objeto);
			if ($impuestos['total'] > 0) {
				$impuestos_comanda = 0;
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
					$productos['rows'][$key]['precioventa'] += $producto_impuesto;
					$productos['rows'][$key]['precioventa'] = round($productos['rows'][$key]['precioventa'], 2);
				}
			}

		/* FIN Impuestos del producto
		============================================================================= */

		// Valida que exista la imagen
			if (!empty($value['imagen'])) {
				$src = '../pos/' . $value['imagen'];
				$productos['rows'][$key]['imagen'] = (file_exists($src)) ? $src : '';
			} else {
				$productos['rows'][$key]['imagen'] = '';
			}

		// Consulta si se encuentra el platillo actual en el dis
			$busca = strpos($value['dias'], $dia);

			if (!empty($busca)) {
				$h_ini = strtotime($value['inicio']);
				$h_fin = strtotime($value['fin']);

			// Si el platillo se encuentra en el horario lo inserta al principio del array
				if ($hora >= $h_ini && $hora <= $h_fin) {
					$elemento = $value;
					$elemento['especial'] = 1;
					unset($productos['rows'][$key]);
					array_unshift($productos['rows'], $elemento);
				}
			}
		}
	
	// Si no existe una vista carga una por default
		$vista = (!empty($objeto['vista'])) ? $objeto['vista'] : 'vista_productos' ;
		
		require ('views/comandas/'.$vista.'.php');
	}

///////////////// ******** ---- 		FIN buscar_productos		------ ************ //////////////////

///////////////// ******** ---- 			mesas_libres			------ ************ //////////////////
//////// Consulta en la BD las mesas libres
	// Como parametros recibe:
		// id -> id de la mesa

	function mesas_libres($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Consulta los datos de la mesa en la DB
		$mesas_libres = $this -> comandasModel -> mesas_libres($objeto);

	// Regresa al ajax el mensaje
		echo json_encode($mesas_libres);
	}

///////////////// ******** ---- 		FIN mesas_libres		------ ************ //////////////////

///////////////// ******** ---- 		mudar_comanda			------ ************ //////////////////
//////// Muda la comanda de mesa
	// Como parametros recibe:
		// mesa -> id de la mesa a la que se mudara la comanda
		// comanda -> id de la comanda
		// mesa_origen -> id de la mesa original

	function mudar_comanda($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Consulta los datos de la mesa en la DB
		$result = $this -> comandasModel -> mudar_comanda($objeto);

	// Regresa al ajax el mensaje
		echo json_encode($result);
	}

///////////////// ******** ---- 		FIN mudar_comanda		------ ************ //////////////////

///////////////// ******** ---- 	vista_estatus_comandas		------ ************ //////////////////
//////// Carga la vista en la que se consultan los estatus de comandas
	// Como parametros puede revibir:
	 
	function vista_estatus_comandas($objeto) {
	// Consulta las mesas y las regresa en un array
		$mesas = $this -> comandasModel -> getTables($objeto);
		$mesas = $mesas['rows'];
	
	// Consulta las sucursales y las regresa en un array
		$sucursales = $this -> comandasModel -> listar_sucursales($objeto);
		$sucursales = $sucursales['rows'];
		
	// Consulta las vias de contacto y las regresa en un array
		$vias_contacto = $this -> comandasModel -> listar_vias_contacto($objeto);
		$vias_contacto = $vias_contacto['rows'];

	// Consulta las vias de contacto y las regresa en un array
		$zonas_reparto = $this -> comandasModel -> listar_zonas_reparto($objeto);
		$zonas_reparto = $zonas_reparto['rows'];

	// Consulta los empleado sy los regresa en un array
		$empleados = $this -> comandasModel -> listar_empleados($objeto);

		require ('views/comandas/vista_estatus_comandas.php');
	}

///////////////// ******** ---- 	FIN	vista_estatus_comandas		------ ************ //////////////////

///////////////// ******** ---- 		listar_comandas		------ ************ //////////////////
//////// Trasforma el objeto para consultar a la BD y cargar los resultados a la vista
	// Como parametros puede recibir:
		// id -> id de la comanda
		// f_ini -> fecha y hora de inicio
		// F_fin -> fecha y hora final
		// status -> status de la comanda(abierta, cerrada, eliminada)

	function listar_comandas($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Formatea la fecha y la hora
		$objeto['f_ini'] = str_replace('T', ' ', $objeto['f_ini']).' 00:01';
		$objeto['f_fin'] = str_replace('T', ' ', $objeto['f_fin']).' 23:59';

	// Consulta las comandas y las regresa en un array
		$comandas = $this -> comandasModel -> listar_comandas($objeto);
		$comandas = $comandas['rows'];

	// Consulta las comandas y las regresa en un array para la grafica lineal
		$objeto['agrupar'] = 'WEEK(timestamp)';
		$comandas_2 = $this -> comandasModel -> listar_comandas($objeto);
		$comandas_2 = $comandas_2['rows'];

	// Arma el array para la grafica lineal
		foreach ($comandas_2 as $key => $value) {
			$lineal[$key]['comandas'] = $value['comandas'];
			$lineal[$key]['status'] = $value['status'];
			$lineal[$key]['timestamp'] = $value['timestamp'];
		}

	// Calcula el total de cada status de comandas para la grafica
		foreach ($comandas as $key => $value) {
			$contar = array_count_values($value);
			$datos['Abiertas'] += $contar['Abierta'];
			$datos['Sin pago'] += $contar['Cerrada / Sin pago'];
			$datos['Pagadas'] += $contar['Cerrada / Pagada'];
			$datos['Eliminadas'] += $contar['Eliminada'];
		}

	// Datos dona
		$dona[0] = array('label' => 'Abiertas', 'value' => $datos['Abiertas']);
		$dona[1] = array('label' => 'Sin pago', 'value' => $datos['Sin pago']);
		$dona[2] = array('label' => 'Eliminadas', 'value' => $datos['Eliminadas']);
		$dona[3] = array('label' => 'Pagadas', 'value' => $datos['Pagadas']);

	// carga la vista para listar las comandas
		require ('views/comandas/listar_comandas.php');
	}

///////////////// ******** ---- 		FIN listar_comandas		------ ************ //////////////////

///////////////// ******** ---- 	iniciar_sesion		------ ************ //////////////////
//////// Inicia la sesion para el empleado y carga la vista con los filtros solo para el usuario
	// Como parametros puede recibir:
	//	pass -> contraseña a bsucar
	// empleado -> ID del empleado

	function iniciar_sesion($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$respuesta['status'] = 0;

		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// Consulta los datos del empleado
		$result = $this -> comandasModel -> iniciar_sesion($objeto);
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

///////////////// ******** ---- 	cerrar_sesion		------ ************ //////////////////
//////// Cierra la sesion del empleado
	// Como parametros puede recibir:

	function cerrar_sesion($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$valida = 0;

		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		$_SESSION['mesero'] = '';

		if (empty($_SESSION['mesero'])) {
			$valida = 1;
		}

		echo json_encode($valida);
	}

///////////////// ******** ---- 		FIN cerrar_sesion		------ ************ //////////////////

///////////////// ******** ---- 	vista_empleados		------ ************ //////////////////
//////// Carga la vista en la que se podran editar los permisos a los usuarios

	function vista_empleados($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$objeto['permisos'] = 0;
		$objeto['vista_empleados'] = 1;

		// Consulta los empleados y los regresa en un array
		$empleados = $this -> comandasModel -> listar_empleados($objeto);

		// Arma el array
		$_SESSION['permisos'] = '';
		foreach ($empleados as $key => $value) {
			$_SESSION['permisos']['empleados'][$value['id']] = $value;
		}

		require ('views/comandas/vista_empleados.php');
	}

///////////////// ******** ---- 	FIN	vista_empleados		------ ************ //////////////////

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

		// Valida que las mesas sean consultadas por primera vez
		if (empty($_SESSION['permisos']['mesas'])) {
			// Consulta las mesas y las regresa en un array
			$mesas = $this -> comandasModel -> getTables($objeto);
			$mesas = $mesas['rows'];

			$_SESSION['permisos']['mesas'] = $mesas;
		} else {
			$mesas = $_SESSION['permisos']['mesas'];
		}

		require ('views/comandas/listar_mesas.php');
	}

///////////////// ******** ---- 	FIN	listar_mesas		------ ************ //////////////////

///////////////// ******** ---- 		listar_empleados		------ ************ //////////////////
//////// Obtiene los empleados con sus permisos y asiganaciones y los carga en una div
	// Como parametros recibe:

	function listar_empleados($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// Consulta los empleados y los regresa en un array
		$empleados = $this -> comandasModel -> listar_empleados($objeto);

		foreach ($empleados as $key => $value) {
			if (!empty($value['asignacion'])) {
				$objeto['asignacion'] = $value['asignacion'];
				$mesas_asignacion = $this -> comandasModel -> getTables($objeto);
				$mesas_asignacion = $mesas_asignacion['rows'];
				$mesas_asignacion = array_column($mesas_asignacion, 'nombre_mesa');
				$mesas_asignacion = implode(',', $mesas_asignacion);

				$value['mesas_asignacion'] = $mesas_asignacion;
			}

			if (!empty($value['permisos'])) {
				$objeto['asignacion'] = $value['asignacion'];
				$mesas_permisos = $this -> comandasModel -> getTables($objeto);
				$mesas_permisos = $mesas_permisos['rows'];
				$mesas_permisos = array_column($mesas_permisos, 'nombre_mesa');
				$mesas_permisos = implode(',', $mesas_permisos);

				$value['mesas_permisos'] = $mesas_permisos;
			}

			$_SESSION['permisos']['empleados'][$value['id']] = $value;
		}

		// Carga la vista
		require ('views/comandas/listar_empleados.php');
	}

///////////////// ******** ---- 		FIN listar_empleados		------ ************ //////////////////

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

///////////////// ******** ---- 		guardar_asignacion		------ ************ //////////////////
//////// Guarda los permisos de los empleados
	// Como parametros recibe:
	// empleado -> ID del empleado

	function guardar_asignacion($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// Guarda los permisos del mesero
		$resp['result'] = $this -> comandasModel -> guardar_asignacion($_SESSION['permisos']['empleados'][$objeto['empleado']]);
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0;

		echo json_encode($resp);
	}

///////////////// ******** ---- 		FIN guardar_asignacion		------ ************ //////////////////

///////////////// ******** ---- 		autorizar_asignacion		------ ************ //////////////////
//////// Autoriza la asignacion de mesas
	// Como parametros puede recibir:
		//	pass -> contraseña del admin

	function autorizar_asignacion($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// Recorre los empleados y guarda sus asignaciones
		foreach ($_SESSION['permisos']['empleados'] as $key => $value) {
			// Guarda los permisos del mesero
			$resp['result'] = $this -> comandasModel -> autorizar_asignacion($value);
		}

		$resp['status'] = (!empty($resp['result'])) ? 1 : 0;

		// Regresa al ajax el mensaje
		echo json_encode($resp);
	}

///////////////// ******** ---- 	FIN	autorizar_asignacion		------ ************ //////////////////

///////////////// ******** ---- 	cambiar_vista		------ ************ //////////////////
//////// Cambia la vista de las mesas de cuadricula a listado
	// Como parametros puede recibir:
	// Div: div donde se cargaran las mesas

	function cambiar_vista($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// Carga la vista
		require ('views/comandas/listar_mesas_mapa.php');
	}

///////////////// ******** ---- 		FIN cambiar_vista		------ ************ //////////////////

///////////////// ******** ---- 		vista_cerrar_personalizado		------ ************ //////////////////
//////// Carga la vista para cerrar la comanda de manera personalizada
	// Como parametros recibe:
		// servicio -> si es para llevar, a domicilio o normal
		// nombre -> nombre del cliente si es servicio a domicilio
		// dirreccion -> direccion del cliente
		// id_reservacion -> id de la reservacion
		// num_comensales -> numero de comensales de la comanda
		// idcomanda -> ID de la comanda
		// idmesa -> ID de la mesa
		// tipo -> tipo de comanda

	function vista_cerrar_personalizado($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Consulta los pedidos e inicializa el array
		$resp['pedidos'] = $this -> comandasModel -> listar_productos_comanda($objeto);

	/* Impuestos del producto
	============================================================================= */

		foreach ($resp['pedidos'] as $key => $value) {
			$objeto['id'] = $value['id'];
			$impuestos = $this -> comandasModel -> listar_impuestos($objeto);

			if ($impuestos['total'] > 0) {
				$impuestos_comanda = 0;
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

					$resp['pedidos'][$key]['impuestos'] = $producto_impuesto;

				// Precio actualizado
					$resp['pedidos'][$key]['precioventa'] += $producto_impuesto;
					$resp['pedidos'][$key]['precioventa'] = round($resp['pedidos'][$key]['precioventa'], 2);
				}
			}
			
		// Precio en el ticket
			$resp['pedidos'][$key]['precio_ticket'] = $resp['pedidos'][$key]['precioventa'];
		}

	/* FIN Impuestos del producto
	============================================================================= */

		$resp['num_personas'] = $resp['pedidos'][0]['personas'];
		
	// Arma el array
		$_SESSION['cerrar_personalizado'] = '';
		$_SESSION['cerrar_personalizado']['num_personas'] = $resp['num_personas'];
		$_SESSION['cerrar_personalizado']['num_personas_base'] = $resp['num_personas'];
		$_SESSION['cerrar_personalizado']['pedidos'] = $resp['pedidos'];

	// Modificacmos el precio de venta si tiene extras
		foreach ($_SESSION['cerrar_personalizado']['pedidos'] as $key => $value) {
		// Agrega el costo de los extra al precio que se muestra
			if (!empty($value['extras'])) {
				foreach ($value['extras'] as $k => $v) {
				/* Impuestos del producto
				============================================================================= */

					$objeto['id'] = $v['id'];
					$impuestos = $this -> comandasModel -> listar_impuestos($objeto);

					if ($impuestos['total'] > 0) {
						$impuestos_comanda = 0;
						foreach ($impuestos['rows'] as $kk => $vv) {
							if ($v["clave"] == 'IEPS') {
								$producto_impuesto = $ieps = (($v['costo']) * $vv["valor"] / 100);
							} else {
								if ($ieps != 0) {
									$producto_impuesto = ((($v['costo'] + $ieps)) * $vv["valor"] / 100);
								} else {
									$producto_impuesto = (($v['costo']) * $vv["valor"] / 100);
								}
							}

							$_SESSION['cerrar_personalizado']['pedidos'][$key]['impuestos'] = $producto_impuesto;

						// Precio actualizado
							$_SESSION['cerrar_personalizado']['pedidos'][$key]['precioventa'] += $producto_impuesto;
							$_SESSION['cerrar_personalizado']['pedidos'][$key]['precioventa'] = round($_SESSION['cerrar_personalizado']['pedidos'][$key]['precioventa'], 2);
							
						// Actualiza el precio de los extra y sus impuestos
							$_SESSION['cerrar_personalizado']['pedidos'][$key]['extras'][$k]['costo'] += $producto_impuesto;
							$_SESSION['cerrar_personalizado']['pedidos'][$key]['extras'][$k]['impuestos'] += $producto_impuesto;

						}
					}

				/* Impuestos del producto
				============================================================================= */
					
					$_SESSION['cerrar_personalizado']['pedidos'][$key]['precioventa'] += $v['costo'];
				}
			}
			
		// Precio en el ticket
			$_SESSION['cerrar_personalizado']['pedidos'][$key]['precio_ticket'] = $_SESSION['cerrar_personalizado']['pedidos'][$key]['precioventa'];
		}

	// Si se eliminan todas las personas las inicializa con el numero original
		if ($_SESSION['cerrar_personalizado']['num_personas'] < 1) {
			$_SESSION['cerrar_personalizado']['num_personas'] = $_SESSION['cerrar_personalizado']['num_personas_base'];
		}


	// Carga la vista
		require ('views/comandas/cerrar_personalizado.php');
	}

///////////////// ******** ---- 		FIN vista_cerrar_personalizado		------ ************ //////////////////

///////////////// ******** ---- 		agregar_pedido		------ ************ //////////////////
//////// Agrega un pedido a la persona seleccionada
	// Como parametros recibe:
		// persona -> Numero de persona
		// pedido -> array con los datos del pedido
		// Div -> din donde se cargara el contenido
		// Clase -> Clase que llevaran los botones

	function agregar_pedido($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Agrega el pedido y la clase a la persona
		$_SESSION['cerrar_personalizado']['comanda'][$objeto['persona']]['pedidos'][$objeto['id']] = $objeto['pedido'];
		$_SESSION['cerrar_personalizado'][$objeto['persona']]['clase'] = $objeto['clase'];

	// Actualiza el total de las comandas
		$_SESSION['cerrar_personalizado']['total_sub_comandas'] += $objeto['pedido']['precioventa'];

	// Elimina el pedido del array de pedidos de la comanda
		unset($_SESSION['cerrar_personalizado']['pedidos'][$objeto['id']]);

	// Manda llamar a la funcion que lista los pedidos agregados del cliente
		$this -> listar_agregados($objeto);
	}

///////////////// ******** ---- 		FIN agregar_pedido		------ ************ //////////////////

///////////////// ******** ---- 		quitar_pedido		------ ************ //////////////////
//////// Elimina un pedido a la persona seleccionada
	// Como parametros recibe:
		// persona -> Numero de persona
		// pedido -> array con los datos del pedido
		// Div -> din donde se cargara el contenido

	function quitar_pedido($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Agrega el pedido de la persona a la comanda
		$_SESSION['cerrar_personalizado']['pedidos'][$objeto['id']] = $objeto['pedido'];

	// Actualiza el total de las comandas
		$_SESSION['cerrar_personalizado']['total_sub_comandas'] -= $objeto['pedido']['precioventa'];

	// Elimina el pedido de los pedidos agregados de la persona
		unset($_SESSION['cerrar_personalizado']['comanda'][$objeto['persona']]['pedidos'][$objeto['id']]);

	// Manda llamar a la funcion que lista los pedidos
		$this -> listar_pedidos($objeto);
	}

///////////////// ******** ---- 		FIN quitar_pedido		------ ************ //////////////////

///////////////// ******** ---- 		listar_agregados		------ ************ //////////////////
//////// Obtien los pedidos de la persona seleccionada
	// Como parametros recibe:
		// persona -> Numero de persona
		// Div -> div en donde se cargara el contenido
		// Clase -> la clase del color que se deben de pintar los productos

	function listar_agregados($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Variables para la vista
		$pedidos = $_SESSION['cerrar_personalizado']['comanda'][$objeto['persona']]['pedidos'];
		$clase = $objeto['clase'];

	// Carga la vista
		require ('views/comandas/listar_agregados.php');
	}

///////////////// ******** ---- 		FIN listar_agregados		------ ************ //////////////////

///////////////// ******** ---- 		listar_pedidos		------ ************ //////////////////
//////// Obtien los pedidos de la comanda
	// Como parametros recibe:
		// Div -> div en donde se cargara el contenido

	function listar_pedidos($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Carga la vista
		require ('views/comandas/listar_pedidos.php');
	}

///////////////// ******** ---- 		FIN listar_pedidos		------ ************ //////////////////

///////////////// ******** ---- 		listar_sub_comandas		------ ************ //////////////////
//////// Obtien las sub comandas y las carga en una div
	// Como parametros recibe:
		// Div -> div en donde se cargara el contenido
		// status -> el estatus por el que filtrara la comanda
		// id -> ID de la comanda

	function listar_sub_comandas($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Valida si existe el total de la comanda, si no existe lo consulta
		if (empty($_SESSION['cerrar_personalizado']['total_comanda'])) {
		// Consulta el total de la comanda
			$resp['result'] = $this -> comandasModel -> listar_comandas($objeto);

		// Obtenemos el total de la comanda
			$_SESSION['cerrar_personalizado']['total_comanda'] = ($resp['result']['total'] > 0) ? $resp['result']['rows'][0]['total'] : 0;
		}

	// Carga la vista
		require ('views/comandas/listar_sub_comandas.php');
	}

///////////////// ******** ---- 		FIN listar_sub_comandas		------ ************ //////////////////

///////////////// ******** ---- 		listar_personas		------ ************ //////////////////
//////// Obtienlas personas de la comanda y las carga en una div
	// Como parametros recibe:
		// Div -> div en donde se cargara el contenido

	function listar_personas($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Carga la vista
		require ('views/comandas/listar_personas.php');
	}

///////////////// ******** ---- 		FIN listar_personas		------ ************ //////////////////

///////////////// ******** ---- 		agregar_persona		------ ************ //////////////////
//////// Agrega una persona a la comanda y las carga en una div
	// Como parametros recibe:
		// Div -> div en donde se cargara el contenido

	function agregar_persona($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Incrementa el numero de personas
		$_SESSION['cerrar_personalizado']['num_personas'] += $objeto['persona'];

	// Manda llamar a la funcion que lista las personas
		$this -> listar_personas($objeto);
	}

///////////////// ******** ---- 		FIN agregar_persona		------ ************ //////////////////

///////////////// ******** ---- 		quitar_persona		------ ************ //////////////////
//////// Agrega una persona a la comanda y las carga en una div
	// Como parametros recibe:
		// Div -> div en donde se cargara el contenido

	function quitar_persona($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Recorre los pedidos de la persona y los agrega a la comanda
		foreach ($_SESSION['cerrar_personalizado']['comanda'][$objeto['persona']]['pedidos'] as $key => $value) {
		// Agrega el pedido de la persona a la comanda
			$_SESSION['cerrar_personalizado']['pedidos'][$key] = $value;

		// Actualiza el total de las comandas
			$_SESSION['cerrar_personalizado']['total_sub_comandas'] -= $value['precioventa'];
		}

	// Elimina la persona del array
		unset($_SESSION['cerrar_personalizado']['comanda'][$objeto['persona']]);

	// Decrementa el numero de personas
		$_SESSION['cerrar_personalizado']['num_personas']--;

	// Manda llamar a la funcion que lista las personas
		$this -> listar_personas($objeto);
	}

///////////////// ******** ---- 		FIN quitar_persona		------ ************ //////////////////

///////////////// ******** ---- 		guardar_comanda_parcial		------ ************ //////////////////
//////// Crear una comanda parcial, la guarda e imprime un Ticket
	// Como parametros recibe:

	function guardar_comanda_parcial($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Limpia el array
		$_SESSION['cerrar_personalizado']['finalizados'] = '';

	// Valida que existan pedidos en la comanda y que no venga del reporte de estatus comanda
		if (!empty($_SESSION['cerrar_personalizado']['pedidos']) && $objeto['vista_estatus_comanda'] != 1) {
			$resp = 2;

			echo($resp);

			return 0;
		}

	// Consulta los pedidos si viene del estatus comanda
		if ($objeto['vista_estatus_comanda'] == 1) {
			$objeto['mesa'] = $objeto['nombre_mesa'];
			$pedidos = $this -> listar_pedidos_sub_comanda($objeto);
		} else {
			$pedidos = $_SESSION['cerrar_personalizado']['comanda'];
		}

	// Recorre la comanda
		foreach ($pedidos as $k => $v) {
			$objeto['pedidos'] = $pedidos[$k]['pedidos'];

		// Valida que tenga pedidos
			if (!empty($objeto['pedidos'])) {
			// Guarda la sub comanda y obtine el id si no viene de estatus comanda
				if ($objeto['vista_estatus_comanda'] != 1) {
				// Inicializamos variables
					date_default_timezone_set('America/Mexico_City');
					$objeto['empleado'] = $_SESSION['mesero']['id'];
					$objeto['fecha'] = date('Y-m-d H:i:s');
					$objeto['ids_pedidos'] = '';
					$objeto['total'] = 0;
					$objeto['persona'] = $k;
					$string = "";

				// Obtiene los ID´s de los pedidos y calcula el total
					foreach ($objeto['pedidos'] as $key => $value) {
						$objeto['ids_pedidos'] .= $value['pedido'] . ',';
						$objeto['total'] += $value['precioventa'];
						$objeto['impuestos'] += $value['impuestos'];
					}
					$objeto['ids_pedidos'] = substr($objeto['ids_pedidos'], 0, -1);

					$resp['id'] = $this -> comandasModel -> guardar_comanda_parcial($objeto);

				// Calcula el numero de 0 que debe de llevar el codigo
					$size = 5 - strlen($resp['id']);
					for ($i = 0; $i < $size; $i++)
						$string .= "0";

				// Formatea el codigo
					$objeto['codigo'] = 'SUB' . $string . $resp['id'];
					$objeto['id'] = $resp['id'];

					$resp['actualizar_comanda'] = $this -> comandasModel -> actualizar_comanda_parcial($objeto);

				// Asigna el status 2 a los pedidos
					$objeto['status'] = 2;

					$resp['actualizar_pedidos'] = $this -> comandasModel -> actualizar_pedidos($objeto);

				// Calcula la propina
					$objeto['propina'] = $objeto['total'] * 0.10;

				// Agrega los datos de la sub comanda
					$_SESSION['cerrar_personalizado']['finalizados'][$k]['pedidos'] = $objeto['pedidos'];
					$_SESSION['cerrar_personalizado']['finalizados'][$k]['propina'] = $objeto['propina'];
					$_SESSION['cerrar_personalizado']['finalizados'][$k]['total'] = $objeto['total'];
					$_SESSION['cerrar_personalizado']['finalizados'][$k]['codigo'] = $objeto['codigo'];
					$_SESSION['cerrar_personalizado']['finalizados'][$k]['id'] = $objeto['id'];

					unset($_SESSION['cerrar_personalizado']['comanda'][$k]);
				} else {
				// Agrega los datos de la sub comanda
					$propina = $objeto['total'] * 0.10;
					$_SESSION['cerrar_personalizado']['finalizados'][$k]['pedidos'] = $objeto['pedidos'];
					$_SESSION['cerrar_personalizado']['finalizados'][$k]['propina'] = $propina;
					$_SESSION['cerrar_personalizado']['finalizados'][$k]['total'] = $objeto['total'];
					$_SESSION['cerrar_personalizado']['finalizados'][$k]['codigo'] = $objeto['codigo'];
					$_SESSION['cerrar_personalizado']['finalizados'][$k]['id'] = $objeto['id'];
				}
			}//If pedidos
		}//Foreach

	// Optenemos el logo
		$logo = $this -> comandasModel -> logo($objet);
		$objeto['logo'] = $logo['rows'][0]['logo'];

	// Consulta si se debe de mostrar la propina o no
		$propina = $this -> comandasModel -> listar_ajustes($objet);
		$objeto['mostrar'] = $propina['rows'][0]['propina'];

	// Carga la vista
		require ('views/comandas/imprime_comanda_parcial.php');
	}

///////////////// ******** ---- 		FIN guardar_comanda_parcial		------ ************ //////////////////

///////////////// ******** ---- 		info_comandas					------ ************ //////////////////
//////// Consulta el tiempo de la comanda y lo agrega a la div
	// Como parametros puede recibir:

	function info_comandas($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
	// Si no existe la fecha le da la actual del dia
		date_default_timezone_set('America/Mexico_City');
		$objeto['f_ini'] = (empty($objeto['f_ini'])) ? date('Y-m-d') . ' 00:01' : $objeto['f_ini']. ' 00:01';
		$objeto['f_fin'] = (empty($objeto['f_fin'])) ? date('Y-m-d H:i:s') : $objeto['f_fin'].' 23:59';
		$objeto['status'] = '0';
		$resp['objet_antes'] = $objeto;
		
	// Consulta las comandas y las regresa en un array
		$resp['result'] = $this -> comandasModel -> listar_comandas($objeto);
		
	// Calcula los minutos de la comanda
		foreach ($resp['result']['rows'] as $key => $value) {
		// Calcula el tiempo
			$segundos = strtotime(date('Y-m-d H:i:s')) - strtotime($value['timestamp']);
			$horas = floor($segundos / 3600);
			$minutos = floor(($segundos - ($horas * 3600)) / 60);
			
			$minutos = ($minutos < 10) ? '0'.$minutos : $minutos ;
			$horas = ($horas < 10) ? '0'.$horas : $horas ;
			
		// Formateamos el tiempo y lo agregamos al array
			$tiempo = $horas . ":" . $minutos;
			$resp['result']['rows'][$key]['tiempo'] = $tiempo;
		}
	
	// 1 -> Todo bien :)
	// 2 -> Fallo la consulta :(
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0;

		echo json_encode($resp);
	}

///////////////// ******** ---- 		FIN info_comandas				------ ************ //////////////////

///////////////// ******** ---- 		comanda_padre					------ ************ //////////////////
//////// Consulta si la comanda padre tiene pedidos sin pagar
	// Si tiene pedidos, refresca los datos
	// si no, cierra la comanda
	// Como parametros recibe:
	// idpadre -> ID de la comanda padre
	// status -> estatus de los pedidos

	function comanda_padre($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// Consulta los pedidos de la comanda y los regresa en un array
		$resp['result'] = $this -> comandasModel -> listar_productos_comanda($objeto);

		// 1 -> Tienes pedidos por pagar
		if (!empty($resp['result'])) {
			$resp['status'] = 1;
			// 1 -> Cierra la comanda padre
		} else {
			$resp['status'] = 2;
			$objeto['id'] = $objeto['idComanda'];
			$objeto['status'] = 2;

			// Actualiza el estatus dela comanda para marcarla como cerrada
			$resp['actualizar'] = $this -> comandasModel -> actualizar_comanda($objeto);

			if (!empty($resp['actualizar'])) {
				$resp['status'] = 2;

				// Limpia el array
				$_SESSION['cerrar_personalizado'] = '';
				unset($_SESSION['cerrar_personalizado']);
			} else {
				$resp['status'] = 3;
			}
		}

		echo json_encode($resp);
	}

///////////////// ******** ---- 		FIN comanda_padre		------ ************ //////////////////

///////////////// ******** ---- 		vista_asignaciones		------ ************ //////////////////
//////// Carga la vista para cerrar la comanda de manera personalizada
	// Como parametros recibe:

	function vista_asignaciones($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// Consulta los empleados y los regresa en un array
		$empleados = $this -> comandasModel -> listar_empleados($objeto);
		$_SESSION['permisos'] = '';

		foreach ($empleados as $key => $value) {
			$_SESSION['permisos']['empleados'][$value['id']] = $value;

			// Se utiliza al momento de cancelar
			$_SESSION['permisos_original']['empleados'][$value['id']] = $value;
		}

		// Carga la vista
		require ('views/comandas/asignaciones.php');
	}

///////////////// ******** ---- 		FIN vista_asignaciones		------ ************ //////////////////

///////////////// ******** ---- 		bloquear_mesas		------ ************ //////////////////
//////// Bloquea las mesas asignadas para que no las pueda seleccionar el usuario
	// Como parametros recibe:

	function bloquear_mesas($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// Consulta los empleados y los regresa en un array
		$resp['result'] = $this -> comandasModel -> bloquear_mesas($objeto);
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0;

		echo json_encode($resp);
	}

///////////////// ******** ---- 		FIN vista_asignaciones		------ ************ //////////////////

///////////////// ******** ---- 	reiniciar_asignacion		------ ************ //////////////////
//////// Reinicia las asignaciones de los meseros
	// Como parametros puede recibir:
	//	pass -> contraseña del admin

	function reiniciar_asignacion($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// Limpia los registros de los permisos de los empleados
		$resp['result'] = $this -> comandasModel -> reiniciar_asignacion($objeto);
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0;

		echo json_encode($resp);
	}

///////////////// ******** ---- 		FIN reiniciar_asignacion		------ ************ //////////////////

///////////////// ******** ---- 	vista_actividad		------ ************ //////////////////
//////// Carga la vista del reporte de actividades de usuario
	// Como parametros puede recibir
	
	function vista_actividad($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$objeto['empleado'] = '*';
		$objeto['actividad'] = '*';
		$objeto['agrupar'] = 'accion';

	// Consulta los empleado sy los regresa en un array
		$empleados = $this -> comandasModel -> listar_empleados($objeto);
		
	// Consulta las sucursales y las regresa en un array
		$sucursales = $this -> comandasModel -> listar_sucursales($objeto);
		$sucursales = $sucursales['rows'];
		
	// Consulta las actividades y las regresa en un array
		$actividades = $this -> comandasModel -> listar_actividades($objeto);
		$actividades = $actividades['result']['rows'];

	// Carga la vista de actividades
		require ('views/comandas/vista_actividad.php');
	}

///////////////// ******** ---- 		FIN	vista_actividad		------ ************ //////////////////

///////////////// ******** ---- 		listar_actividades		------ ************ //////////////////
//////// Manda llamar a la funcion que consulta las actividades y carga la vista
	// Como parametros recibe:
		// empleado -> id del empleado
		// f_ini -> fecha y hora de inicio
		// F_fin -> fecha y hora final
		// actividad -> actividad seleccionada

	function listar_actividades($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Formatea la fecha y la hora
		$objeto['f_ini'] = str_replace('T', ' ', $objeto['f_ini']).' 00:01';
		$objeto['f_fin'] = str_replace('T', ' ', $objeto['f_fin']).' 23:59';

	// Consulta las comandas y las regresa en un array
		$actividades = $this -> comandasModel -> listar_actividades($objeto);
		$actividades = $actividades['result']['rows'];

	// Consulta las actividades y las regresa en un array para la grafica lineal
		$objeto['agrupar'] = 'id_empleado';
		$actividades_2 = $this -> comandasModel -> listar_actividades($objeto);
		$actividades_2 = $actividades_2['result']['rows'];
	
	// Arma el array para la grafica de barras
		foreach ($actividades_2 as $key => $value) {
			$barras[$key]['actividades'] = $value['actividades'];
			$barras[$key]['empleado'] = $value['empleado'];
		}

	// Consulta las actividades y las regresa en un array para la grafica de dona
		$objeto['agrupar'] = 'accion';
		$actividades_3 = $this -> comandasModel -> listar_actividades($objeto);
		$actividades_3 = $actividades_3['result']['rows'];
	
	// Arma el array para la grafica de dona
		foreach ($actividades_3 as $key => $value) {
			$dona[$key] = array('label' => $value['accion'], 'value' => $value['actividades']);
		}

	// carga la vista para listar las comandas
		require ('views/comandas/listar_actividades.php');
	}

///////////////// ******** ---- 		FIN listar_actividades		------ ************ //////////////////

///////////////// ******** ---- 	asignar_mesa		------ ************ //////////////////
//////// Asigna la mesa al mesero
	// Como parametros puede recibir:
	// empleado -> ID del mesero
	// mesa -> ID de la mesa

	function asignar_mesa($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// Asigna la mesa al empleado
		$resp['result'] = $this -> comandasModel -> asignar_mesa($objeto);
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0;

		echo json_encode($resp);
	}

///////////////// ******** ---- 		FIN asignar_mesa		------ ************ //////////////////

///////////////// ******** ---- 	vista_comensales		------ ************ //////////////////
//////// Carga la vista del reporte de comensales por mesa

	function vista_comensales($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Consulta las mesas y las regresa en un array
		$mesas = $this -> comandasModel -> getTables($objeto);
		$mesas = $mesas['rows'];

	// Consulta los empleado sy los regresa en un array
		$empleados = $this -> comandasModel -> listar_empleados($objeto);
		
	// Consulta las sucursales y las regresa en un array
		$sucursales = $this -> comandasModel -> listar_sucursales($objeto);
		$sucursales = $sucursales['rows'];
		
	// Carga la vista de los comensales
		require ('views/comandas/vista_comensales.php');
	}

///////////////// ******** ---- 		FIN	vista_comensales		------ ************ //////////////////

///////////////// ******** ---- 		listar_comensalesXmesa		------ ************ //////////////////
//////// Obtien los registros de los comensales y los carga en la div
	// Como parametros puede recibir:
		// empleado -> id del empleado
		// f_ini -> fecha y hora inicial
		// f_fin -> Fecha y hora final
		// mesa -> ID de la mesa
		// sucursal -> ID de la sucursal

	function listar_comensalesXmesa($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Formatea la fecha y la hora
		$objeto['f_ini'] = str_replace('T', ' ', $objeto['f_ini']).' 00:01';
		$objeto['f_fin'] = str_replace('T', ' ', $objeto['f_fin']).' 23:59';

	// Consulta las comandas y las regresa en un array
		$comandas = $this -> comandasModel -> listar_comandas($objeto);
		$comandas = $comandas['rows'];

	// Si hay registros calcula la duracion de las comandas
		if (!empty($comandas)) {
			foreach ($comandas as $key => $value) {
				$comandas[$key]['duracion'] = round(abs(strtotime($value['fin']) - strtotime($value['timestamp'])) / 60);
			}

		// ** Consulta las comandas y las regresa en un array para la grafica lineal
		// Valida por que debe agrupar la consulta
			switch ($objeto['grafica']) {
			// Dia
				case 1 :
					$objeto['agrupar'] = "DATE_FORMAT(c.timestamp, ' %Y %m %d')";
					break;
			// Semana
				case 2 :
					$objeto['agrupar'] = "DATE_FORMAT(c.timestamp, ' %Y %m %v')";
					break;
			// Mes
				case 3 :
					$objeto['agrupar'] = "DATE_FORMAT(c.timestamp, ' %Y %m')";
					break;
			// Año
				case 4 :
					$objeto['agrupar'] = "DATE_FORMAT(c.timestamp, ' %Y')";
					break;
			}
			
			$comandas_2 = $this -> comandasModel -> listar_comandas($objeto);
			$comandas_2 = $comandas_2['rows'];
		
		// Arma el array para la grafica lineal
			foreach ($comandas_2 as $key => $value) {
				$lineal[$key]['personas'] = $value['personas'];
				$lineal[$key]['timestamp'] = $value['timestamp'];
			}

		// ** Arma el array para la grafica de dona
			foreach ($comandas as $key => $value) {
				$datos[$value['usuario']]['value'] += $value['personas'];
				$datos[$value['usuario']]['label'] = $value['usuario'];
			}
			$dona = array();
			foreach ($datos as $key => $value) {
				array_push($dona, $value);
			}
		}

	// carga la vista para listar las comandas
		require ('views/comandas/listar_comensalesXmesa.php');
	}

///////////////// ******** ---- 		FIN listar_comensalesXmesa		------ ************ //////////////////

///////////////// ******** ---- 		reiniciar_mesas		------ ************ //////////////////
//////// Manda llamar a la funcion que actualiza la pocision de las mesas
	// Como parametros recibe:

	function reiniciar_mesas($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// Guarda los permisos del mesero
		$resp['result'] = $this -> comandasModel -> reiniciar_mesas($objeto);
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0;

		echo json_encode($resp);
	}

///////////////// ******** ---- 		FIN reiniciar_mesas		------ ************ //////////////////

///////////////// ******** ---- 		vista_zonas				------ ************ //////////////////
//////// Carga la vista del reporte de zonas de mayor influencia

	function vista_zonas($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		$objeto['empleado'] = '*';
		$objeto['mesa'] = '*';
		$objeto['zona'] = '*';

	// Consulta los empleado sy los regresa en un array
		$zonas = $this -> comandasModel -> listar_zonas($objeto);
		$zonas = $zonas['rows'];
		
	// Consulta las sucursales y las regresa en un array
		$sucursales = $this -> comandasModel -> listar_sucursales($objeto);
		$sucursales = $sucursales['rows'];

	// Inicializa las zonas
		$zonas_sin_repetir = array();

	// Limpia las zonas para que no se repitan
		foreach ($zonas as $key => $value) {
			if (!in_array($value['zona'], $zonas_sin_repetir)) {
				array_push($zonas_sin_repetir, $value['zona']);
			}
		}

	// Consulta los empleado sy los regresa en un array
		$empleados = $this -> comandasModel -> listar_empleados($objeto);

	// Carga la vista de zonas
		require ('views/comandas/vista_zonas.php');
	}

///////////////// ******** ---- 		FIN	vista_zonas		------ ************ //////////////////

///////////////// ******** ---- 		listar_zonas		------ ************ //////////////////
//////// Obtien los registros de los comensales y los carga en la div
	// Como parametros puede recibir:
		// empleado -> id del empleado
		// f_ini -> fecha y hora inicial
		// f_fin -> Fecha y hora final
		// mesa -> ID de la mesa
		// comandas -> numero total de comandas
		// zona -> zona
		// sucrusal -> ID de la sucursal

	function listar_zonas($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Consulta las comandas y las regresa en un array
		$zonas = $this -> comandasModel -> listar_zonas($objeto);
		$zonas = $zonas['rows'];

	//** Arma el array para la grafica de barras y de dona
		foreach ($zonas as $key => $value) {
			$value['zona'] = (empty($value['zona'])) ? 'Sin nombre' : $value['zona'];
			$datos['barras'][$value['zona']]['zona'] = $value['zona'];
			$datos['barras'][$value['zona']]['comandas'] = $value['comandas'];
			$datos['dona'][$value['zona']]['value'] += $value['total'];
			$datos['dona'][$value['zona']]['label'] = $value['zona'];
		}

	// Grafica de dona
		$dona = array();
		foreach ($datos['dona'] as $key => $value) {
			array_push($dona, $value);
		}

	// Grafica de barras
		$barras = array();
		foreach ($datos['barras'] as $key => $value) {
			array_push($barras, $value);
		}

	// carga la vista para listar las comandas
		require ('views/comandas/listar_zonas.php');
	}

///////////////// ******** ---- 		FIN listar_comensalesXmesa		------ ************ //////////////////

///////////////// ******** ---- 		vista_ocupacion					------ ************ //////////////////
//////// Carga la vista del reporte de ocupacion

	function vista_ocupacion($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Consulta las comandas y las regresa en un array
		$objeto['zona'] = '*';
		$objeto['empleado'] = '*';
		$objeto['mesa'] = '*';
		$zonas = $this -> comandasModel -> listar_zonas($objeto);
		$zonas = $zonas['rows'];

	// Inicializa las zonas
		$zonas_sin_repetir = array();

	// Limpia las zonas para que no se repitan
		foreach ($zonas as $key => $value) {
			if (!in_array($value['zona'], $zonas_sin_repetir)) {
				array_push($zonas_sin_repetir, $value['zona']);
			}
		}

	// Consulta los empleado sy los regresa en un array
		$empleados = $this -> comandasModel -> listar_empleados($objeto);
		
	// Consulta las sucursales y las regresa en un array
		$sucursales = $this -> comandasModel -> listar_sucursales($objeto);
		$sucursales = $sucursales['rows'];
		
	// Carga la vista de ocupacion
		require ('views/comandas/vista_ocupacion.php');
	}

///////////////// ******** ---- 		FIN	vista_ocupacion				------ ************ //////////////////

///////////////// ******** ---- 		listar_ocupacion				------ ************ //////////////////
//////// Obtien los registros de las ocupaciones y carga la vista
	// Como parametros recibe:
		// empleado -> id del empleado
		// f_ini -> fecha y hora inicial
		// f_fin -> Fecha y hora final
		// mesa -> ID de la mesa
		// comandas -> numero total de comandas
		// zona -> zona

	function listar_ocupacion($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Formatea la fecha y la hora
		$objeto['f_ini'] = str_replace('T', ' ', $objeto['f_ini']).' 00:01';
		$objeto['f_fin'] = str_replace('T', ' ', $objeto['f_fin']).' 23:59';

	// Consulta las comandas y las regresa en un array
		$ocupaciones = $this -> comandasModel -> listar_ocupacion($objeto);
		$ocupaciones = $ocupaciones['rows'];

	// Arma el array para la grafica lineal y la de dona
		foreach ($ocupaciones as $key => $value) {
			$lineal[$key]['comensales'] = $value['comensales'];
			$lineal[$key]['hora_grafica'] = $value['hora_grafica'];
			$dona[$key] = array('label' => $value['hora'], 'value' => $value['comandas']);
		}

	// carga la vista para listar las comandas
		require ('views/comandas/listar_ocupacion.php');
	}

///////////////// ******** ---- 		FIN listar_ocupacion		------ ************ //////////////////

///////////////// ******** ---- 	guardar_comensales		------ ************ //////////////////
//////// Manda llamar a la funcion que Guarda el numero de comensales de la comanda
	// Como parametros puede recibir:
	//	comanda -> ID de la comanda
	// comensales -> numero de comensales

	function guardar_comensales($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// Guarda los permisos del mesero
		$resp['result'] = $this -> comandasModel -> guardar_comensales($objeto);
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0;

		echo json_encode($resp);
	}

///////////////// ******** ---- 		FIN guardar_comensales		------ ************ //////////////////

///////////////// ******** ---- 	agregar_mesas		------ ************ //////////////////
//////// Manda llamar a la funcion que inserta las mesas en la BD
	// Como parametros puede recibir:
		// pass -> contraseña a bsucar
		// num_mesas -> numero de mesas a aagregar
		// num_comensales -> numero de comensales a aagregar

	function agregar_mesas($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Guarda las mesas
		for ($i = 0; $i < $objeto['num_mesas']; $i++) {
			$objeto['nombre'] = 'Mesa '.$i;
			$resp['result'] = $this -> comandasModel -> agregar_mesas($objeto);
			$permisos .= $resp['result'] . ',';
			$resp['status'] = (!empty($resp['result'])) ? 1 : 0;
		}

		session_start();
	// Agrega las mesas al mesero si existe
		if (!empty($objeto['empleado'])) {
			$objeto['permisos'] = substr($permisos, 0, -1);
			$resp['result'] = $this -> comandasModel -> actualizar_permisos($objeto);
			
			if (!empty($_SESSION['mesero']['permisos'])) {
				$_SESSION['mesero']['permisos'] .= ', '.$objeto['permisos'];
			} else {
				$_SESSION['mesero']['permisos'] = $objeto['permisos'];
			}
		}
		
		$_SESSION['area'] = 0;
		
		echo json_encode($resp);
	}

///////////////// ******** ---- 		FIN agregar_mesas		------ ************ //////////////////

///////////////// ******** ---- 	listar_clientes		------ ************ //////////////////
//////// Consulta los datos de los clientes en la BD
	// Como parametros puede recibir:
		// nombre-> nombre del cliente

	function listar_clientes($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Consulta los empleados y los regresa en un array
		$clientes = $this -> comandasModel -> listar_clientes($objeto);

		echo json_encode($clientes);
	}

///////////////// ******** ---- 		FIN listar_empleados		------ ************ //////////////////

///////////////// ******** ---- 		actualizar_comanda			------ ************ //////////////////
//////// Actualiza el status de la comanda
	// Como parametros recibe:
		// id -> ID de la comanda
		// status -> Nuevo status
		// total -> Total de la comanda

	function actualizar_comanda($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Actualiza el estatus dela comanda para marcarla como cerrada
		$resp['result'] = $this -> comandasModel -> actualizar_comanda($objeto);

		echo json_encode($resp);
	}

///////////////// ******** ---- 		FIN comanda_padre			------ ************ //////////////////

///////////////// ******** ---- 			validar_mesa			------ ************ //////////////////
//////// Valida que la mesa no este eliminada
	// Como parametros recibe:
	// id -> ID de la mesa

	function validar_mesa($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// Actualiza el estatus dela comanda para marcarla como cerrada
		$resp['result'] = $this -> comandasModel -> validar_mesa($objeto);
		$resp['id_mesa'] = $resp['result']['rows'][0]['id_mesa'];

		// 1 -> Todo bien, 2 -> la mesa esta eliminada
		$resp['status'] = ($resp['result']['total'] > 0) ? 1 : 2;

		echo json_encode($resp);
	}

///////////////// ******** ---- 		FIN validar_mesa			------ ************ //////////////////

///////////////// ******** ---- 		listar_comandas_hijas		------ ************ //////////////////
//////// Obtien las sub comandas y las carga en una div
	// Como parametros recibe:
	// div -> div en donde se cargara el contenido
	// id_padre -> ID de la comanda padre

	function listar_comandas_hijas($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		$sub_comandas = $this -> comandasModel -> listar_sub_comandas($objeto);
		$sub_comandas = $sub_comandas['rows'];

		// Carga la vista
		require ('views/comandas/listar_comandas_hijas.php');
	}

///////////////// ******** ---- 		FIN listar_comandas_hijas		------ ************ //////////////////

///////////////// ******** ---- 		listar_pedidos_sub_comanda		------ ************ //////////////////
//////// Consulta los pedidos de la sub comanda y los regresa en un array
	// Como parametros recibe:
		// id -> ID de la sub comanda
		// pedidos -> Cadena con los ID's de los pedidos

	function listar_pedidos_sub_comanda($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		$pedidos = $this -> comandasModel -> listar_pedidos_sub_comanda($objeto);
		$pedidos = $pedidos['rows'];

		foreach ($pedidos as $key => $value) {
			/* Impuestos del producto
			 ============================================================================= */

			$impuestos_comanda = 0;
			$precio = $value['precioventa'];
			$objeto['id'] = $value['idProducto'];

			$impuestos = $this -> comandasModel -> listar_impuestos($objeto);
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
				$sql = "	SELECT 
									CONCAT('Con: ',GROUP_CONCAT(nombre)) nombre 
								FROM 
									app_productos
								WHERE 
									id IN(" . $value['opcionales'] . ")";
				$itemsProduct = $this -> query($sql);
				if ($row = $itemsProduct -> fetch_array())
					$items .= "(" . $row['nombre'] . ")";
			}

			// Obtiene los sin si existen
			if ($value['sin'] != "") {
				$sql = "	SELECT 
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

			// Si tiene adicionales los agrega al total
			if ($value['adicionales'] != "") {
				$sql = "	SELECT 
									CONCAT('Extras: ',GROUP_CONCAT(nombre)) nombre 
								FROM 	
									app_productos 
								WHERE 
									id IN(" . $value['adicionales'] . ")";
				$itemsProduct = $this -> query($sql);

				if ($row = $itemsProduct -> fetch_array())
					$items .= "(" . $row['nombre'] . ")";

				// Obtiene el costo y nombre de los productos
				$sql = "	SELECT 
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

					$impuestos = $this -> comandasModel -> listar_impuestos($objeto);
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

			$result[$value['persona']]['pedidos'][$key] = Array('impuestos' => $impuestos_comanda, 'costo_extra' => $costo_extra, 'persona' => $value['npersona'], 'cantidad' => $value['cantidad'], 'nombre' => $value['nombre'] . " $items", 'precio_ticket' => $value['precio_ticket'], 'tipo' => $value['tipo']);
		}

		return $result;
	}

///////////////// ******** ---- 		FIN listar_pedidos_sub_comanda		------ ************ //////////////////

///////////////// ******** ---- 				vista_utilidad				------ ************ //////////////////
//////// Carga la vista del reporte de utilidad

	function vista_utilidad($objeto) {
		$productos = $this -> comandasModel -> getProducts($objeto);
		$productos = $productos['rows'];

	// Consulta los empleado sy los regresa en un array
		$empleados = $this -> comandasModel -> listar_empleados($objeto);
		
	// Consulta las sucursales y las regresa en un array
		$sucursales = $this -> comandasModel -> listar_sucursales($objeto);
		$sucursales = $sucursales['rows'];
		$clientes = $this->comandasModel->listar_clientes($objeto);
		
		require ('views/comandas/vista_utilidad.php');
	}

///////////////// ******** ---- 			FIN	vista_utilidad				------ ************ //////////////////

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
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Formatea la fecha y la hora
		$objeto['f_ini'] = (!empty($objeto['f_ini'])) ? str_replace('T', ' ', $objeto['f_ini']) . ' 00:01' : date('Y-m-d').' 00:01';
		$objeto['f_fin'] = (!empty($objeto['f_fin'])) ? str_replace('T', ' ', $objeto['f_fin']) . ' 23:59' : date('Y-m-d').' 23:59' ;

	// Consulta las comandas y las regresa en un array
		$utilidades = $this -> comandasModel -> listar_utilidades($objeto);
		$utilidades = $utilidades['rows'];

	// Consulta las sucursales y las regresa en un array
		$sucursales = $this -> comandasModel -> listar_sucursales($objeto);
		$sucursales = $sucursales['rows'];
		
	// Calcula el rango en el que se deben de mostrar los datos
		if (!empty($utilidades)) {
			switch ($objeto['grafica']) {
				// Dia
				case 1 :
					$objeto['agrupar'] = "DATE_FORMAT(v.fecha, ' %Y %m %d')";
					break;
				// Semana
				case 2 :
					$objeto['agrupar'] = "DATE_FORMAT(v.fecha, ' %Y %m %v')";
					break;
				// Mes
				case 3 :
					$objeto['agrupar'] = "DATE_FORMAT(v.fecha, ' %Y %m')";
					break;
				// Año
				case 4 :
					$objeto['agrupar'] = "DATE_FORMAT(v.fecha, ' %Y')";
					break;
			}
			
			$utilidades_2 = $this -> comandasModel -> listar_utilidades($objeto);
			$utilidades_2 = $utilidades_2['rows'];
		
		// Arma el array para la grafica lineal
			foreach ($utilidades_2 as $key => $value) {
				$lineal[$key]['ventas'] = $value['ventas'];
				$lineal[$key]['fecha'] = $value['fecha'];
			}

		// ** Arma el array para la grafica de dona
			foreach ($utilidades as $key => $value) {
				$datos[$value['id_empleado']]['value'] += $value['ventas'];
				$datos[$value['id_empleado']]['label'] = $value['empleado'];
			}
			$dona = array();
			foreach ($datos as $key => $value) {
				array_push($dona, $value);
			}
		}
		
	// carga la vista que se indica desde el objeto
		if (!empty($objeto['vista'])) {
			require ('views/comandas/'.$objeto['vista'].'.php');
		} else {
			
	// carga la vista para listar las comandas
		require ('views/comandas/listar_utilidades.php');
		}
	}

///////////////// ******** ---- 			FIN listar_utilidades			------ ************ //////////////////

///////////////// ******** ---- 				listar_productos_detalle			------ ************ //////////////////

	function listar_productos_detalle($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		// ultimo dia del mes
		$month = $objeto['mes'];
      	$year = $objeto['yyyy'];
      	$day = date("d", mktime(0,0,0, $month+1, 0, $year));

      	$objeto['f_ini'] =  date('Y-m-d', mktime(0,0,0, $month, 1, $year)).' 00:01';
      	$objeto['f_fin'] =  date('Y-m-d', mktime(0,0,0, $month, $day, $year)).' 23:59';

	// Consulta las comandas y las regresa en un array
		$utilidades = $this -> comandasModel -> listar_productos_detalle($objeto);
		$utilidades = $utilidades['rows'];
		
	// Calcula el rango en el que se deben de mostrar los datos
		if (!empty($utilidades)) {
			
			$est = $rom = $cab = $per = 0;

			foreach ($utilidades as $key => $val) {
				$promGB += ($val['ganancia'] / $val['ventas']); // Promedio ganacia bruta
				$promV  += $val['ventas']; 						// Promedio Ventas
				$sumGB  += $val['ganancia']; 						// Total Ganacia Bruta
				$cont++;
			}

			foreach ($utilidades as $key => $value) { 

				$producto = $value['nombre'];
				$utilidadU = $value['ganancia'] / $value['ventas'];
				$mix = number_format(($value['ganancia'] / $sumGB) * 100,2);
				$mixT += $mix; 

				/// GRAFICA DONA MIX %
				$dona[] = array('label' => $producto,
							    'value' => $mix);

				$rent = $pop = $cal ='';

				$rent = ($utilidadU > ($promGB / $cont)) ? 'ALTA' : 'BAJA';
				$pop = ($value['ventas'] > ($promV  / $cont )) ? 'ALTA' : 'BAJA';

				if ($rent == 'ALTA' and $pop == 'ALTA'){ $est++; }
				if ($rent == 'ALTA' and $pop == 'BAJA'){ $rom++; }
				if ($rent == 'BAJA' and $pop == 'ALTA'){ $cab++; }
				if ($rent == 'BAJA' and $pop == 'BAJA'){ $per++; }
			}

			$mixG = array('est' => $est,
							 'rom' => $rom,
							 'cab' => $cab,
							 'per' => $per);
			
		}		

		require ('views/comandas/listar_productos_detalle.php');

	}

///////////////// ******** ---- 			FIN listar_productos_detalle			------ ************ //////////////////	

///////////////// ******** ---- 			eliminar_cliente				------ ************ //////////////////
//////// Elimina un cliente nuevo en la BD
	// Como parametros recibe:
		// id -> ID del cliente
		// btn -> Boton
		// tr -> TR de la tabla

	function eliminar_cliente($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		$resp['result'] = $this -> comandasModel -> eliminar_cliente($objeto);

	// 1 -> Todo bien :)
	// 2 -> Fallo la consulta :(
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0;

	// Regresa al ajax el resultado
		echo json_encode($resp);
	}

///////////////// ******** ---- 			FIN	eliminar_cliente			------ ************ //////////////////

///////////////// ******** ---- 			vista_producto_detalle			------ ************ //////////////////
//////// Carga la vista del reporte de utilidad

	function vista_producto_detalle($objeto) { // vista

		$departamentos = $this -> comandasModel -> getDeparments($objeto);
		$departamentos = $departamentos['rows'];

		$productos = $this -> comandasModel -> getProducts($objeto);
		$productos = $productos['rows'];

	// Consulta los empleado sy los regresa en un array
		$empleados = $this -> comandasModel -> listar_empleados($objeto);
		
	// Consulta las sucursales y las regresa en un array
		$sucursales = $this -> comandasModel -> listar_sucursales($objeto);
		$sucursales = $sucursales['rows'];
		
		require ('views/comandas/vista_producto_detalle.php');
	}

///////////////// ******** ---- 			FIN	vista_producto_detalle		------ ************ //////////////////

///////////////// ******** ---- 			litar_productos_mercado			------ ************ //////////////////
//////// Carga la vista del reporte de utilidad

	function litar_productos_mercado($objeto) {
		$texto = file_get_contents("http://www.economia-sniim.gob.mx/nuevo/Consultas/MercadosNacionales/PreciosDeMercado/Agricolas/ResultadoConsultaSemanalFrutasYHortalizas.aspx?Anio=2016&Mes=11&NombreMes=Noviembre&Semana=1&DestinoId=140&MercadoDestino=Jalisco:%20Mercado%20de%20Abasto%20de%20Guadalajara");
		echo $texto;
	}

///////////////// ******** ---- 			FIN	litar_productos_mercado		------ ************ //////////////////

///////////////// ******** ---- 					listar_kits				------ ************ //////////////////
//////// Consulta los kits, sus productos y carga la vista de los kits
	// Como parametros recibe:
		// div -> Div donde se cargaron los kits

	function listar_kits($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
	// Consulta los productos
		$kits = $this -> comandasModel -> listar_kits($objeto);
		$kits = $kits['rows'];
		
	// Obtiene los productos de los kits y los agrega
		foreach ($kits as $key => $value) {
			$productos = $this -> comandasModel -> listar_productos($value);
			$value['productos'] = $productos['rows'];
		
		// Ordena los productos
			foreach ($value['productos'] as $k => $v) {
				
			// Valida que exista la imagen
				if (!empty($v['imagen'])) {
					$src = '../pos/' . $v['imagen'];
					$v['imagen'] = (file_exists($src)) ? $src : '';
				} else {
					// $v['imagen'] = '';
				}
				
				$productos_ordenados[$v['idProducto']] = $v;
			}
			
			$value['productos'] = $productos_ordenados;
			
			
			$horario = (!empty(strpos($value['dias'], "0"))) ? 'Do, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "1"))) ? 'Lu, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "2"))) ? 'Ma, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "3"))) ? 'Mi, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "4"))) ? 'Ju, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "5"))) ? 'Vi, ' : '' ;
			$horario .= (!empty(strpos($value['dias'], "6"))) ? 'Sa, ' : '' ;
			
			$value['horario'] = substr($horario, 0, -2);
			$value['horario'] .= ' == '.$value['inicio'].'-'.$value['fin'];
			$value['id_comanda'] = $objeto['comanda'];
			$value['persona'] = $objeto['persona'];
			
		// Agrega el elemento al array
			$datos[$key] = $value;
		}
		
		session_start();
		$_SESSION['kit'] = '';
		
	// Carga la vista de listado por default si no existe una vista
		$vista = (!empty($objeto['vista'])) ? $objeto['vista'] : 'listar_kits';
		
	// Carga la vista
		require ('views/comandas/'.$vista.'.php');
	}

///////////////// ******** ---- 				FIN listar_kits				------ ************ //////////////////

///////////////// ******** ---- 			listar_productos_kit			------ ************ //////////////////
//////// Carga la vista de los productos del kit
	// Como parametros recibe:
		// div -> Div donde se cargaron los kits
		// id_kit -> ID del kit
		
	function listar_productos_kit($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		session_start();
		$_SESSION['kit'] = $objeto['kit'];
		
	// Carga la vista
		require ('views/comandas/listar_productos_kit.php');
	}

///////////////// ******** ---- 		FIN listar_productos_kit			------ ************ //////////////////
	
	//ch@
	function asignarRep(){
		$idrep 		= $_POST['idrep'];
		$idcomanda 	= $_POST['idcomanda'];
		$asignarRep = $this -> comandasModel -> asignarRepM($idrep,$idcomanda,$f_asignacion);	
		echo json_encode($asignarRep);
	}
	function verAsignado(){
		$idcomanda 	= $_POST['idcomanda'];
		$verAsignado = $this -> comandasModel -> verAsignadoM($idcomanda);	
		echo json_encode($verAsignado);
	}


///////////////// ******** ---- 			editar_producto_kit				------ ************ //////////////////
//////// Edita la informacion del producto del kit
	// Como parametros recibe:
		
	function editar_producto_kit($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
	
	// Editar el producto
		session_start();
		$_SESSION['kit']['productos'][$objeto['id_producto']]['extras'] = $objeto['extras'];
		$_SESSION['kit']['productos'][$objeto['id_producto']]['nota_extra'] = $objeto['nota_extra'];
		$_SESSION['kit']['productos'][$objeto['id_producto']]['nota_opcional'] = $objeto['nota_opcional'];
		$_SESSION['kit']['productos'][$objeto['id_producto']]['nota_sin'] = $objeto['nota_sin'];
		$_SESSION['kit']['productos'][$objeto['id_producto']]['opcionales'] = $objeto['opcionales'];
		$_SESSION['kit']['productos'][$objeto['id_producto']]['sin'] = $objeto['sin'];
		
		echo json_encode($_SESSION['kit']['productos'][$objeto['id_producto']]);
	}

///////////////// ******** ---- 			FIN editar_producto_kit			------ ************ //////////////////

///////////////// ******** ---- 				guardar_kit					------ ************ //////////////////
//////// Guarda el pedido del kit y los pedidos de sus productos
	// Como parametros recibe:
		
	function guardar_kit($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
	
		session_start();
		$_SESSION['kit']['persona'] = $objeto['persona'];
		
		$idproduct = $_SESSION['kit']['id_kit'];
		$idperson = $_SESSION['kit']['persona'];
		$idcomanda = $resp['id_comanda'] = $_SESSION['kit']['id_comanda'];
		$iddep = $_SESSION['kit']['id_departamento'];
		
		$id_pedido = $this -> comandasModel -> addProduct($idproduct, $idperson, $idcomanda, $opcionales, $extras, $sin, $iddep, $nota_opcional, $nota_extra, $nota_sin);
		
		foreach ($_SESSION['kit']['productos'] as $key => $value) {
			$value['id_pedido'] = $id_pedido;
			$value['id_comanda'] = $idcomanda;
			$value['persona'] = $idperson;
			$resp['result'] = $this -> comandasModel -> guardar_pedido_kit($value);
		}
		
		echo json_encode($resp);
	}

///////////////// ******** ---- 			FIN guardar_kit					------ ************ //////////////////

///////////////// ******** ---- 		cerrar_comanda_persona				------ ************ //////////////////
//////// Genera una comanda de la persona y la cierra
	// Como parametros recibe:
		// persona -> Numero de persona	
		// id_comanda -> ID de la comanda
		
	function cerrar_comanda_persona($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
	// Consulta los pedidos de la persona
		$comanda = $objeto['id_comanda'];
		$person = $objeto['persona'];
		$pedidos = $this -> comandasModel -> listar_pedidos_persona($objeto);
		$pedidos = $pedidos['rows'];
		
	// Sin pedidos
		if (empty($pedidos)) {
			$resp['status'] = 2;
			echo json_encode($resp);
			
			return 0;
		}
	
	// Crea una nueva comanda
		$resp['id_comanda_padre'] = $objeto['id_comanda'];
		$resp['id_comanda'] = $this -> comandasModel -> agregar_comanda($objeto);
	
	// Actualiza los pedidos con la nueva comanda
		foreach ($pedidos as $key => $value) {
			$value['id_comanda'] = $resp['id_comanda'];
			$resp['result_pedidos'] = $this -> comandasModel -> actualizar_pedido($value);
		}
		
	// Elimina la persona de la comanda
		$resp['result'] = $this -> comandasModel -> deletePersons($comanda, $person);
		
		echo json_encode($resp);
	}

///////////////// ******** ---- 		FIN cerrar_comanda_persona			------ ************ //////////////////

///////////////// ******** ---- 				cambiar_status				------ ************ //////////////////
//////// Cambia el estatus de la comanda en la BD
	// Como parametros recibe:
		// pass -> Contraseña de seguridad
		// id_comanda -> ID de la comanda

	function cambiar_status($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		$pass = $this -> comandasModel -> listar_ajustes($value);
		$pass = $pass['rows'][0]['seguridad'];
		$resp['status'] = 1;
		
	// Valida que sea el pass
		if ($objeto['pass'] != $pass) {
			$resp['status'] = 2;
			echo json_encode($resp);
			
			return 0;
		}
		
		$objeto['id'] = $objeto['id_comanda'];
		$objeto['status'] = " 0"; //Importante dejar el espacio en el 0
		$resp['result'] = $this -> comandasModel -> actualizar_comanda($objeto);
		
	// Regresa al ajax el mensaje
		echo json_encode($resp);
	}

///////////////// ******** ---- 			FIN cambiar_status				------ ************ //////////////////

///////////////// ******** ---- 			agregar_via_contacto			------ ************ //////////////////
//////// Agrega una via de contacto, esconde la modal, actualiza el select y selecciona la nueva opcion
	// Como parametros recibe:
		// nombre -> Nombre de la nueva via de contacto

	function agregar_via_contacto($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		$resp['result'] = $this -> comandasModel -> agregar_via_contacto($objeto);
		
	// Regresa al ajax el mensaje
		echo json_encode($resp);
	}

///////////////// ******** ---- 			FIN agregar_via_contacto		------ ************ //////////////////

///////////////// ******** ---- 			agregar_via_contacto			------ ************ //////////////////
//////// Agrega una via de contacto, esconde la modal, actualiza el select y selecciona la nueva opcion
	// Como parametros recibe:
		// nombre -> Nombre de la nueva via de contacto

	function agregar_zona_reparto($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		$resp['result'] = $this -> comandasModel -> agregar_zona_reparto($objeto);
		
	// Regresa al ajax el mensaje
		echo json_encode($resp);
	}

///////////////// ******** ---- 			FIN agregar_via_contacto		------ ************ //////////////////


///////////////// ******** ---- 				actualizar_mesa				------ ************ //////////////////
//////// Actualiza los datos de la mesa
	// Como parametros recibe:
		// id -> ID de la mesa
		// notificacion -> status de la notificacion

	function actualizar_mesa($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
	// Actualiza los campos de la mesa
		$resp['result'] = $this -> comandasModel -> actualizar_mesa($objeto);

		echo json_encode($resp);
	}

///////////////// ******** ---- 			FIN actualizar_mesa				------ ************ //////////////////

///////////////// ******** ---- 				editCliente				------ ************ //////////////////
//////// Actualiza los datos de la mesa
	// Como parametros recibe:
		// id -> ID de la mesa
		// notificacion -> status de la notificacion
/*
	function editCliente() {

		$resp['result'] = $this -> comandasModel -> editCliente();
		echo json_encode($resp);
	}
*/

///////////////// ******** ---- 			FIN editCliente					------ ************ //////////////////

///////////////// ******** ---- 				info_mesas					------ ************ //////////////////
//////// Consulta las mesas y las devuelve en un array
	// Como parametros puede recibir:

	function info_mesas($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		session_start();
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$objeto['permisos'] = $_SESSION['mesero']['permisos'];

		$result = $this -> comandasModel -> getTables($objeto);
		$resp['mesas'] = $result['rows'];
			
		echo json_encode($resp);
	}

///////////////// ******** ---- 			FIN info_mesas					------ ************ //////////////////

///////////////// ******** ---- 				vista_comandera				------ ************ //////////////////
//////// Carga la vista de la comandera
	// Como parametros recibe:
		// div -> div donde se carga la vista de la comandera

	function vista_comandera($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
	
	// Consulta las mesas libres
		$mesas_libres = $this -> comandasModel -> mesas_libres($objeto);
		
	// Consulta los productos
		$products = $this -> comandasModel -> getProducts(0, 0, 0);
	
	// Consulta los departamentos
		$deparmentos = $this -> comandasModel -> getDeparments();
		
// ** Consultamos el dia y la hora para los productos especiales
		date_default_timezone_set('America/Mexico_City');
	// Calcula el dia en numero 0-6 Domingo-Sabado
		$fecha = date('Y-m-d');
		$dia = date('w');
	// Obtiene la hora actual
		$hora = strtotime(date('H:i'));
		
	// Recorre los resgitros para ordenarlos
		foreach ($products['rows'] as $key => $value) {
		/* Impuestos del producto
		============================================================================= */

			$objeto['id'] = $value['idProducto'];
			$impuestos = $this -> comandasModel -> listar_impuestos($objeto);
			if ($impuestos['total'] > 0) {
				$impuestos_comanda = 0;
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
					$products['rows'][$key]['precioventa'] += $producto_impuesto;
					$products['rows'][$key]['precioventa'] = round($products['rows'][$key]['precioventa'], 2);
				}
			}
		
		/* FIN Impuestos del producto
		============================================================================= */

		// Valida que exista la imagen
			if (!empty($value['imagen'])) {
				$src = '../pos/' . $value['imagen'];
				$products['rows'][$key]['imagen'] = (file_exists($src)) ? $src : '';
			} else {
				$products['rows'][$key]['imagen'] = '';
			}

		// Consulta si se encuentra el platillo actual en el dia
			$busca = strpos($value['dias'], $dia);

			if (!empty($busca)) {
				$h_ini = strtotime($value['inicio']);
				$h_fin = strtotime($value['fin']);

			// Si el platillo se encuentra en el horario lo inserta al principio del array
				if ($hora >= $h_ini && $hora <= $h_fin) {
					$elemento = $products['rows'][$key];
					$elemento['especial'] = 1;
					unset($products['rows'][$key]);
					array_unshift($products['rows'], $elemento);
				}
			}
		}
		
	// Obtiene el listado de los empleados
		$empleados = $this -> comandasModel -> listar_empleados();
		
	// Obtiene las configuraciones
		$configuraciones = $this -> comandasModel -> listar_ajustes();
		$configuraciones = $configuraciones['rows'][0];
			
		require ('views/comandera/vista_comandera.php');
	}

///////////////// ******** ---- 			FIN vista_comandera				------ ************ //////////////////

///////////////// ******** ---- 			mandar_mesa_comandera			------ ************ //////////////////
//////// Consulta los datos de la mesa y los devuelve en un array
	// Como parametros recibe:
		// id -> ID de la mesa
		// tipo -> Tipo de mesa
		// id_comanda -> ID de la comanda

	function mandar_mesa_comandera($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
	// Valida si existe una comanda
		$objeto['id_comanda'] = (empty($objeto['id_comanda'])) ? $this -> comandasModel -> insertComanda($objeto['id_mesa'], 0) : $objeto['id_comanda'] ;
	
	// Obtiene la informacion de la mesa
		$objeto['info_mesa'] = $this -> comandasModel -> getComanda($objeto['id_mesa']);
		$objeto['info_mesa'] = $objeto['info_mesa'] -> fetch_array(MYSQLI_ASSOC);
		
	// Si viene el nombre y la direccion se le asignan a una variable de sesion
	// si no conserva su valor
		$_SESSION['nombre'] = (!empty($_GET['nombre'])) ? $_GET['nombre'] : $_SESSION['nombre'];
		$_SESSION['direccion'] = (!empty($_GET['direccion'])) ? $_GET['direccion'] : $_SESSION['direccion'];
		$_SESSION['tel'] = (!empty($_GET['tel'])) ? $_GET['tel'] : $_SESSION['tel'];
		
	// Consulta si existe una union de mesas
		$objeto['mesas_juntas'] = $this -> comandasModel -> mesas_juntas($objeto);

		$nombre = $_SESSION['nombre'];
		$objeto['nombre'] = str_replace('"', '', $nombre);
		$direccion = $_SESSION['direccion'];
		$objeto['direccion'] = str_replace('"', '', $direccion);
		$tel = $_SESSION['tel'];
		$objeto['tel'] = str_replace('"', '', $tel);
	
	// Obtiene el arreglo con las personas de la comanda
		$objeto['personas'] = $this -> comandasModel -> getPersons($objeto['id_comanda']);
		$objeto['personas'] = $objeto['personas']['rows'];
		$objeto['num_personas'] = $objeto['personas'][0]['num_personas'];
		
		session_start();
		$_SESSION['tables']['id']['idcomanda'] = $objeto['id_comanda'];
		$objeto['mesero'] = $_SESSION['mesero']['usuario'];
		$objeto['id_mesero'] = $_SESSION['mesero']['id'];
	
	// Asigna el mesero a la mesa
		$result = $this -> comandasModel -> actualizar_mesa($objeto);
		
		echo json_encode($objeto);
	}

///////////////// ******** ---- 			FIN mandar_mesa_comandera		------ ************ //////////////////

///////////////// ******** ---- 				vista_personas				------ ************ //////////////////
//////// Carga la vista de las personas de la comanda
	// Como parametros recibe:
		// div -> div donde se carga la vista
		// personas -> Numero de personas
		// personas -> Array con las personas de la comanda
		// id_comanda -> ID de la comanda

	function vista_personas($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		require ('views/comandera/vista_personas.php');
	}

///////////////// ******** ---- 			FIN vista_personas				------ ************ //////////////////

///////////////// ******** ---- 			listar_pedidos_persona			------ ************ //////////////////
//////// Carga la vista de los productos de la persona
	// Como parametros recibe:
		// div -> div donde se carga la vista
		// persona -> ID de la persona
		// id_comanda -> ID de la comanda

	function listar_pedidos_persona($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		$pedidos = $this -> comandasModel -> getItemsPerson($objeto['persona'], $objeto['id_comanda']);
		$pedidos = $pedidos['rows'];
		
		require ('views/comandera/listar_pedidos_persona.php');
	}

///////////////// ******** ---- 		FIN listar_pedidos_persona			------ ************ //////////////////

///////////////// ******** ---- 			detalles_producto				------ ************ //////////////////
//////// Consulta los detalles del producto, si tiene carga los opcionales, extras, etc. Si no agrega el producto
	// Como parametros recibe:
		// div -> div donde se carga la vista
		// persona -> ID de la persona
		// id_comanda -> ID de la comanda
		// id_producto -> ID del producto
		// departamento -> Departamento del producto
		// tipo -> Tipo de producto
		// Materiales -> 1 -> si tiene insumos, 0 -> si no

	function detalles_producto($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
	// Si tiene insumos carga la vista, si no agrega el producto directamente
		if (!empty($objeto['materiales'])) {
		// Consulta los insumos del producto
			$insumos = $this -> comandasModel -> getItemsProduct($objeto['id_producto']);
			$insumos = $insumos['rows'];
			
			foreach ($insumos as $key => $value) {
			// Sin
				if (strpos($value['opcionales'], "1") !== false) {
					$datos['sin'][$value['idProducto']] = $value;
				}
			
			// Extra
				if (strpos($value['opcionales'], "2") !== false) {
					$datos['extra'][$value['idProducto']] = $value;
				}
			
			// opcionales
				if (strpos($value['opcionales'], "3") !== false) {
					$datos['opcionales'][$value['idProducto']] = $value;
				}
			}
			
		// Formatea el contenido a json
			$objeto['btn'] = 'btn_guardar_detalles_pedido';
			$objeto['f'] = 'guardar_pedido';
			$objeto = json_encode($objeto);
			$objeto = str_replace('"', "'", $objeto);
			
		// Carga la vista
			require ('views/comandera/detalles_producto.php');
		} else {
		// Formatea el contenido a json
			$objeto['btn'] = 'persona_'.$objeto['persona'];
			$objeto['f'] = 'guardar_pedido';
			$pedido = json_encode($objeto);
			$pedido = str_replace('"', "'", $pedido); ?>
		
		<!-- Guarda el producto -->
			<script>
				comandera.guardar_pedido(<?php echo $pedido ?>);
			</script><?php
		}
	}

///////////////// ******** ---- 		FIN detalles_producto				------ ************ //////////////////

///////////////// ******** ---- 			guardar_pedido					------ ************ //////////////////
//////// Guarda el pedido de la persona y carga sus pedidos
	// Como parametros recibe:
		// persona -> ID de la persona
		// id_comanda -> ID de la comanda
		// id_producto -> ID del producto
		// departamento -> Departamento del producto
		// opcionales -> Cadena con los IDs de los productos opcionales
		// extras -> Cadena con los IDs de los productos extras
		// sin -> Cadena con los IDs de los productos sin
		// nota_opcional -> string con la nota de los productos opcionales
		// nota_extra -> string con la nota de los productos extras
		// nota_sin -> string con la nota de los productos sin

	function guardar_pedido($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		$result = $this -> comandasModel -> addProduct($objeto['id_producto'], $objeto['persona'], $objeto['id_comanda'], $objeto['opcionales'], $objeto['extras'], $objeto['sin'], $objeto['departamento'], $objeto['nota_opcional'], $objeto['nota_extra'], $objeto['nota_sin']);
		
		echo json_encode($result);
	}

///////////////// ******** ---- 			FIN guardar_pedido				------ ************ //////////////////

///////////////// ******** ----  				restar_pedido				------ ************ //////////////////
//////// Resta un pedido de la  persona
	// Como parametro puede recibi:
		// id -> ID del pedido
		// id_comanda -> ID de la comanda
		// persona -> numero de  persona

	function restar_pedido($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		$result = $this -> comandasModel -> lessProduct($objeto['id']);
		
		echo json_encode($result);

	}

///////////////// ******** ----  			FIN restar_pedido				------ ************ //////////////////

///////////////// ******** ----  			eliminar_pedido					------ ************ //////////////////
//////// Elimina un pedido de la  persona
	// Como parametro puede recibi:
		// id -> ID del pedido
		// id_comanda -> ID de la comanda
		// persona -> numero de  persona

	function eliminar_pedido($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
	
	// Elimina el pedido
		$result = $this -> comandasModel -> deleteProduct($objeto);
		
		echo json_encode($result);

	}

///////////////// ******** ----  			FIN eliminar_pedido				------ ************ //////////////////

///////////////// ******** ----  			agregar_persona					------ ************ //////////////////
//////// Agrega una persona y carga su vista
	// Como parametro puede recibi:
		// num_personas -> Numero de personas
		// id_comanda -> ID de la comanda

	function agregar_persona_comandera($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
	
	// Agrega la persona a la comanda
		$resp['result'] = $this -> comandasModel -> incrementPersons($objeto['id_comanda']);
	
	// Consulta las personas de la comanda
		$resp['personas'] = $this -> comandasModel -> getPersons($objeto['id_comanda']);
		$resp['personas'] = $resp['personas']['rows'];
		
		echo json_encode($resp);
	}

///////////////// ******** ----  			FIN agregar_persona				------ ************ //////////////////

///////////////// ******** ----  				pedir						------ ************ //////////////////
//////// Manda el pedido de la comanda a las areas correspondientes
	// Como parametro puede recibir:
		// cerrar_comanda -> 1 cierra la modal, 0 -> permanece en la modal 
		// id_comanda -> ID de la comanda

	function pedir($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
	
	// Procesa los pedidos
		$result = $this -> comandasModel -> process($objeto['id_comanda']);
		
		echo json_encode($result);
	}

///////////////// ******** ----  				FIN pedir					------ ************ //////////////////

///////////////// ******** ---- 				cerrar_comanda				------ ************ //////////////////
//////// Cierra la comanda e imprime el ticket
	// Como parametros recibe:
		// bandera -> 0 -> todo junto, 1 -> individual, 2 -> pagar directo en caja, 3 -> mandar a caja
		// nombre -> Nombre del cliente
		// idComanda -> ID de la comanda
		// idmesa -> ID de la mesa
		// tel -> Telefono
		// Tipo -> Tipo de mesa
		// id_reservacion -> ID de la reservacion

	function cerrar_comanda($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
	
	// Cerramos la comanda y regresamos el resultado
		$comanda = $this -> comandasModel -> closeComanda($objeto);

	// Optenemos el logo
		$logo = $this -> comandasModel -> logo($objeto);
	
	// Valida el logo
		$src = '../../netwarelog/archivos/1/organizaciones/' . $logo['rows'][0]['logo'];
		$comanda['logo'] = (file_exists($src)) ? $src : '';
		
	// Elimina la comanda de la mes aen la sesion
		session_start();
		$_SESSION['tables']['idmesa']['idcomanda'] = '';
		
	// Selecciona la vista que debe cargar al cerrar la comanda
		switch ($objeto['bandera']) {
		// Todo junto
			case 0:
				require ('views/comandera/cerrar_comanda_todo_junto.php');
			break;
		
		// Individual
			case 1:
				require ('views/comandera/cerrar_comanda_individual.php');
			break;
		}
	
	// Regresa un Json si se debe de mandar a caja
		if($objeto['bandera'] == 2 || $objeto['bandera'] == 3){
			echo json_encode($comanda);
		}
	}

///////////////// ******** ----  			FIN cerrar_comanda				------ ************ //////////////////

///////////////// ******** ---- 				listar_familias				------ ************ //////////////////
//////// Consulta la vista de las familias y las carga a la div, consulta los productos y los carga a la div
	// Como parametros recibe:
		// div -> div donde se carga la vista
		// div_productos -> div donde se cargan los productos
		// departamento -> ID del departamento
		
	function listar_familias($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
	
	// Consulta las familias y las devuelve en un array
		$familias = $this -> comandasModel -> getFamilies($objeto['departamento']);
		$familias = $familias['rows'];
		
	// Carga la vista
		require ('views/comandera/listar_familias.php');
	}
	
///////////////// ******** ---- 			FIN listar_familias				------ ************ //////////////////

///////////////// ******** ---- 				listar_lineas				------ ************ //////////////////
//////// Consulta la vista de las LINEAS y las carga a la div, consulta los productos y los carga a la div
	// Como parametros recibe:
		// div -> div donde se carga la vista
		// div_productos -> div donde se cargan los productos
		// familia -> ID de la familia
		
	function listar_lineas($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
	
	// Consulta las familias y las devuelve en un array
		$lineas = $this -> comandasModel -> getLines($objeto['familia']);
		$lineas = $lineas['rows'];
		
	// Carga la vista
		require ('views/comandera/listar_lineas.php');
	}
	
///////////////// ******** ---- 			FIN listar_lineas				------ ************ //////////////////

///////////////// ******** ---- 			borrar_persona					------ ************ //////////////////
//////// Elimina la persona de la comanda y sus pedidos
	// Como parametros recibe:
		// id_comanda -> ID de la comanda
		// persona -> ID de la persona
		// pass -> Contraseña de seguridad
		
	function borrar_persona($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		$pass = $this -> comandasModel -> listar_ajustes();
		$pass = $pass['rows'][0]['seguridad'];
		
		$resp['status'] = 1;
		
	// Valida que sea el pass
		if ($objeto['pass'] != $pass) {
			$resp['status'] = 2;
			echo json_encode($resp);
			
			return 0;
		}
		
		$resp['result'] = $this -> comandasModel -> deletePersons($objeto['id_comanda'], $objeto['persona']);
		
		echo json_encode($resp);
	}
	
///////////////// ******** ---- 			FIN listar_lineas				------ ************ //////////////////

///////////////// ******** ---- 			validar_cuenta					------ ************ //////////////////
//////// Valida que la cuenta tenga pedidos
	// Como parametros puede recibir:
		// 	id_comanda -> ID de la comanda
		
	function validar_cuenta($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$resp['status'] = 2;
		
		$pedidos = $this -> comandasModel -> listar_pedidos($objeto);
		$pedidos = $pedidos['rows'];
		
	// Todo bien :D
		if (!empty($pedidos)) {
			$resp['status'] = 1;
		}

		echo json_encode($resp);
	}
	
///////////////// ******** ---- 			FIN validar_cuenta				------ ************ //////////////////

///////////////// ******** ---- 			validar_pass					------ ************ //////////////////
//////// Valida el pass de seguridad
	// Como parametros recibe:
		// pass -> Contraseña de seguridad
		// json -> 1 -> devuelve un json
		
	function validar_pass($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		$pass = $this -> comandasModel -> listar_ajustes();
		$pass = $pass['rows'][0]['seguridad'];
		
		$resp['status'] = 1;
		
	// Pass incorrecto
		if ($objeto['pass'] != $pass) {
			$resp['status'] = 2;

		// Regresa un json o el status segun sea el caso
			if ($objeto['json'] == 1) {
				echo json_encode($resp);

				return 0;
			} else {
				return $resp['status'];
			}
		}
	
	// Regresa un json o el status segun sea el caso
		if ($objeto['json'] == 1) {
			echo json_encode($resp);

			return 0;
		} else {
			return $resp['status'];
		}
	}
///////////////// ******** ---- 			FIN validar_pass				------ ************ //////////////////

///////////////// ******** ---- 		actualizar_tiempo_pedidos			------ ************ //////////////////
//////// Actualiza el tiempo de los pedidos
	// Como parametros puede recibir:
		// id_comanda -> ID de la comanda
		// tiempo -> Tiempo del platillo
		
	function actualizar_tiempo_pedidos($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
	
	// Actualiza los pedidos
		$result = $this -> comandasModel -> actualizar_tiempo_pedidos($objeto);

		echo json_encode($result);
	}
	
///////////////// ******** ---- 		FIN actualizar_tiempo_pedidos		------ ************ //////////////////

///////////////// ******** ---- 			vista_eliminar_mesas			------ ************ //////////////////
//////// Carga la vista de las mesas a eliminar
	// Como parametros recibe:
		// div -> div donde se carga la vista de la comandera
	
	function vista_eliminar_mesas($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		session_start();
		$objeto['permisos'] = $_SESSION['mesero']['permisos'];
		
	// Consulta las mesas libres
		$mesas_libres = $this -> comandasModel -> mesas_libres($objeto);
		$mesas_libres = $mesas_libres['rows'];
		
		require ('views/comandas/vista_eliminar_mesas.php');
	}

///////////////// ******** ---- 			FIN vista_eliminar_mesas		------ ************ //////////////////

///////////////// ******** ---- 				eliminar_mesas				------ ************ //////////////////
//////// Elimina las mesas seleccionadas
	// Como parametros recibe:
		// pass -> Contraseña de seguridad
		// mesas_seleccionadas -> IDs de las mesas seleccionadas

	function eliminar_mesas($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		$pass = $this -> comandasModel -> listar_ajustes($value);
		$pass = $pass['rows'][0]['seguridad'];
		$resp['status'] = 1;
		
	// Valida que sea el pass
		if ($objeto['pass'] != $pass) {
			$resp['status'] = 2;
			echo json_encode($resp);
			
			return 0;
		}
		
		foreach ($objeto['mesas'] as $key => $value) {
			$resp['result'] = $this -> comandasModel -> removeTable($value);
		}
		
		session_start();
		$_SESSION['area'] = 0;
		
	// Regresa al ajax el mensaje
		echo json_encode($resp);
	}

///////////////// ******** ---- 			FIN eliminar_mesas				------ ************ //////////////////

///////////////// ******** ---- 			vista_juntar_mesas				------ ************ //////////////////
//////// Carga la vista de las mesas 
	// Como parametros recibe:
		// div -> div donde se carga la vista de la comandera
	
	function vista_juntar_mesas($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		session_start();
		$objeto['permisos'] = $_SESSION['mesero']['permisos'];
		
	// Consulta las mesas libres
		$mesas_libres = $this -> comandasModel -> mesas_libres($objeto);
		$mesas_libres = $mesas_libres['rows'];
		
		require ('views/comandas/vista_juntar_mesas.php');
	}

///////////////// ******** ---- 		FIN vista_juntar_mesas				------ ************ //////////////////

///////////////// ******** ---- 				juntar_mesas				------ ************ //////////////////
//////// Elimina las mesas seleccionadas
	// Como parametros recibe:
		// pass -> Contraseña de seguridad
		// mesas_seleccionadas -> IDs de las mesas seleccionadas

	function juntar_mesas($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$resp['status'] = 1;
		
	// Forma una cadena con los Ids de las mesas
		foreach ($objeto['mesas'] as $key => $value) {
			$ids_mesas .= $value.',';
		}
		$ids_mesas = substr($ids_mesas, 0, -1);
		
		$resp['result'] = $this -> comandasModel -> joinTables($ids_mesas);
		
		session_start();
		$_SESSION['area'] = 0;
		
	// Regresa al ajax el mensaje
		echo json_encode($resp);
	}

///////////////// ******** ---- 			FIN juntar_mesas				------ ************ //////////////////

} // Fin clase
?>