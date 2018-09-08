<?php
require ('common.php');
if(array_key_exists("api", $_REQUEST)){		
	require ("../webapp/modulos/restaurantes/models/comandas.php");
} else {
	require ("models/comandas.php");
}

class comandas extends Common {
	public $comandasModel; 

	function __construct() {
		$this -> comandasModel = new comandasModel();
	}

	function menuMesas() {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		// $objeto['permisos'] = $_SESSION['mesero']['permisos'];
		//$this->comandasModel-> arreglar();

		$_SESSION['area_princ'] = $this -> comandasModel -> first_area($objeto);
		
		$area_princ = $_SESSION['area_princ'];
		$objeto['f_ini'] = date('Y-m-d') . ' 00:01';
		$objeto['f_fin'] = date('Y-m-d') . ' 23:59';
		$result = $this -> comandasModel -> getTables($objeto);
		$result = $_SESSION['tables'] = $mesas = $result['rows'];
		
		foreach ($mesas as $key => $value) {
			if($value['id_tipo_mesa'] == 7){
				$value['individual'] = 1;
				$value['f_ini'] = date('Y-m-d') . ' 00:01';
				$value['f_fin'] = date('Y-m-d') . ' 23:59';
				$mesas[$key]['sillas'] =  $this -> comandasModel -> getSillas($value);
			}
		}

	// Consulta las areas
		$areas = $this -> comandasModel -> areas($objeto);

	// Consulta las comandas y las regresa en un array
		$objeto['ocultar_empleados'] = 1;
		$empleados = $this -> comandasModel -> listar_empleados($objeto);
		
	// Consulta los empleados y los regresa en un array
		$clientes = $this -> comandasModel -> listar_clientes_3($objeto);
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

	// Obtiene las configuraciones ch@
		//$configuraciones = $this -> comandasModel -> listar_ajustes();
		//$configuraciones = $configuraciones['rows'][0];
	// Obtiene las configuraciones ch@ fin

	//ch@
	// Consulta para repartidores disponibles	 CH
		$repartidores = $this -> comandasModel -> listar_repartidores($objeto);
		
		if($objeto['offline'] == 1){
			$datos['mesas'] = $_SESSION['tables'];
			$datos['areas'] = $areas;
			$datos['empleados'] = $empleados;
			$datos['clientes'] = $clientes;
			$datos['vias_contacto'] = $vias_contacto;
			$datos['configuracion'] = $configuracion;
			
			echo json_encode($datos);
			return json_encode($datos);
		} else {
			//Consulta el idioma seleccionado en configuración
			$idioma = $this -> comandasModel -> get_idioma($objeto);
			if($idioma == 1){
				require ('views/comandas/Gmesas.php');}
			else {
				require ('views/comandas/Gmesas-ingles.php');
			}
		}
	}

	function get_idioma() {
		// Obtiene el idioma seleccionado en configuracion
		$idioma =  $this -> comandasModel -> get_idioma($objeto);
		$resp["idioma"] = $idioma["idioma"];
		echo json_encode($idioma);
	}

	function addComanda() {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		$idmesa = $objeto['idmesa'];
		$iddeparment = $objeto['iddeparment'];

		if ($this -> comandasModel -> insertComanda($idmesa, $iddeparment)) {
			$this -> mesa();
		} else {
			echo "No se pudo crear la comanda!";
		}

		return;
	}

	function deleteComanda() {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		$idcomanda = $objeto['idcomanda'];
		$idmesa = $objeto['idmesa'];
		$id_reservacion = $objeto['id_reservacion'];

		$result = $this -> comandasModel -> deleteComanda($idcomanda, $idmesa, $id_reservacion);
		$this ->comandasModel -> removePassMesa($objeto);

		if ($result)
			echo "Comanda borrada correctamente";
		else
			echo "No se pudo borrar la comanda!";
	}
	function mesasDeLaSession(){
		$content = '';
		$objeto = '';
		session_start();
		

		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		session_start();
		$objeto['permisos'] = $_SESSION['mesero']['permisos'];
		$objeto['f_ini'] = date('Y-m-d') . ' 00:01';
		$objeto['f_fin'] = date('Y-m-d') . ' 23:59';
	// Consulta los datos de la mesa en la DB
		$result = $this -> comandasModel -> getTables($objeto);
		$result = $result['rows'];

		$_SESSION['tables'] = $result;
		$_SESSION['area'] = 1;
		//ch@
		foreach ($_SESSION['tables'] as $key => $value) {
			if($value['tipo'] == 0){
				if($value['idcomanda'] > 0){
					$content.= '<button class="btn btn-departamento" type="button" onclick="comandera.mandar_mesa_comandera({
						id_mesa:'.$value['mesa'].',
						tipo:'.$value['tipo'].',
						tipo_mesa:'.$value['tipo'].',
						nombre_mesa_2:\''.$value['nombre_mesa'].'\',												
						id_comanda:'.$value['idcomanda'].',
						tipo_operacion:3,
					})">'.$value['nombre_mesa'].'</button>';											
				}
				else{
					$content.= '<button class="btn btn-warning" type="button" onclick="comandera.mandar_mesa_comandera({
						id_mesa:'.$value['mesa'].',
						tipo:'.$value['tipo'].',
						tipo_mesa:'.$value['tipo'].',
						nombre_mesa_2:\''.$value['nombre_mesa'].'\',												
						id_comanda:'.$value['idcomanda'].',
						tipo_operacion:3,
					})">'.$value['nombre_mesa'].'</button>';				

				}
			}
		}							
		echo $content;
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
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		session_start();
		$idmesa = $objeto['idmesa'];
		$tipo = $objeto['tipo'];
		$id_reservacion = $objeto['id_reservacion'];
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
			$_SESSION['nombre'] = (!empty($objeto['nombre'])) ? $objeto['nombre'] : $_SESSION['nombre'];
			$_SESSION['direccion'] = (!empty($objeto['direccion'])) ? $objeto['direccion'] : $_SESSION['direccion'];
			$_SESSION['tel'] = (!empty($objeto['tel'])) ? $objeto['tel'] : $_SESSION['tel'];
			
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
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		if (isset($objeto['idperson']))
			$person = $objeto['idperson'];
		if (isset($objeto['idcomanda']))
			$comanda = $objeto['idcomanda'];
		if ($products = $this -> comandasModel -> getItemsPerson($person, $comanda)) {
			echo json_encode($products);
		}
	}

	function incrementPersons() {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		$comanda = $objeto['idcomanda'];
		$persons = $objeto['persons'];
		if ($idperson = $this -> comandasModel -> incrementPersons($comanda)) {
			echo json_encode(Array('idperson' => $idperson));
		}
	}

	function deletePersons() {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		$comanda = $objeto['idcomanda'];
		$persons = $objeto['idspersons'];
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
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		$idproduct = $objeto['idproduct'];
		$idperson = $objeto['idperson'];
		$idcomanda = $objeto['idcomanda'];
		$iddep = $objeto['iddep'];

		$data = $this -> comandasModel -> addProduct($idproduct, $idperson, $idcomanda, $opcionales, $extras, $sin, $iddep, $nota_opcional, $nota_extra, $nota_sin);

		if ((isset($data["status"]) && $data["status"] == false) || $data == false) {
			if (isset($objeto['idperson']))
				$person = $objeto['idperson'];
			if (isset($objeto['idcomanda']))
				$comanda = $objeto['idcomanda'];
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
	
			if(array_key_exists("api", $_REQUEST)){
				$datos['departamentos'] = $deparments;
				$datos['productos'] = $products;
				return json_encode($datos);
			}else{
				echo json_encode(Array('deparments' => $deparments, 'products' => $products));
			}
		}
	}

	function getFamilies($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		$idDeparment = $objeto['idDeparment'];
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
			if(array_key_exists("api", $_REQUEST)){

				$datos['familia'] = $family;
				$datos['productos'] = $products;
			
				return json_encode($datos);
			}else{
				echo json_encode(Array('families' => $family, 'products' => $products));
			}
		}
	}

	function getLines($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		$idFamily = $objeto['idFamily'];
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

			if(array_key_exists("api", $_REQUEST)){

				$datos['linea'] = $line;
				$datos['productos'] = $products;
			
				return json_encode($datos);
			}else{
				echo json_encode(Array('lines' => $line, 'products' => $products));
			}
		}
	}

	function getProducts() {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		$idLine = $objeto['idLine'];
		$idComanda = $objeto['idComanda'];
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
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		$idComanda = $objeto['idComanda'];
		$bandera = $objeto['bandera'];
		$idmesa = $objeto['idmesa'];
		$tipo = $objeto['tipo'];

		if ($comanda = $this -> comandasModel -> reImprimeComanda($idComanda, $bandera, $idmesa, $tipo)) {
			// Optenemos el logo
			$logo = $this -> comandasModel -> logo($objeto);
			$comanda['logo'] = $logo['rows'][0]['logo'];

			echo json_encode($comanda);

		}
	}

	function getItemsProduct($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		$idProduct = $objeto['idProduct'];

		if ($items = $this -> comandasModel -> getItemsProduct($idProduct)){
			//print_r($items['rows']);
		if(array_key_exists("api", $_REQUEST)){
			$datos['detalles'] = $items["rows"];
			return json_encode($datos);
		}else{
			echo json_encode($items);
		}
		}
	}

// Manda llamar a la funcion que agrega los productos a las personas
	function addItemsProduct() {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		$opcionales = $objeto['opcionales'];
		$extras = $objeto['extras'];
		$sin = $objeto['sin'];

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
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		$idmesa = $objeto['idmesa'];
		$this -> comandasModel -> removeTable($idmesa);
	}

	//** Junta las mensas
	function joinTables() {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		$jtables = $objeto['jtables'];

		$this -> comandasModel -> joinTables($jtables);
	}

// ** Procesa los pedidos
	function process() {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		$idcomanda = $objeto['idcomanda'];
		$result = $this -> comandasModel -> process($idcomanda);
		echo json_encode($result);
	}

	function checkProductos() {
		$resultado = $this -> comandasModel -> checkProducts();
		/*Reorganizamos el array para que los productos de la misma comanda queden en la misma posicion.*/
		$newArray = array();
		foreach ($resultado["rows"] as $key => $value) {
			$newArray["status"] = true;
			$newArray["comandas"][$value["idcomanda"]]["productos"] = $newArray["comandas"][$value["idcomanda"]]["productos"] . "|" . $value["idproducto"];
			$newArray["comandas"][$value["idcomanda"]]["lugar"] = $value["lugar"];
		}
		/*Reorganizamos el array para que los productos de la misma comanda queden en la misma posicion fin*/

		echo json_encode($newArray);
	}

	function checkMesas($objeto) {
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$resultado = $this -> comandasModel -> checkTables($objeto);
		echo json_encode($resultado['rows']);
	}

	function entregado() {
		$comanda = $_POST['comanda'];
		$ids = $_POST['ids'];
		$result = $this -> comandasModel -> entregado($comanda, $ids);

		echo $result;
	}

	function getNames() {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		$name = $objeto['name'];

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

///////////////// ******** ---- 		editar_cliente		------ ************ //////////////////
//////// Agrega un cliente a la base de datos en la tabla comun_cliente
	// Como parametros puede recibir:
	// Campos del formulario:
		// -> Nombre, Direccion, Numero interios, Numero Exterior
		// -> Colonia, CP, estado, Municipio, E-mail, Tel

	function editar_cliente($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Inserta un registro en la base de datos
		$result = $this -> comandasModel -> editar_cliente($objeto);

	// Regresa al ajax el mensaje
		echo json_encode($result);
	}

///////////////// ******** ---- 	FIN	editar_cliente		------ ************ //////////////////

///////////////// ******** ---- 		edit_client		------ ************ //////////////////
//////// Edita un cliente a la base de datos en la tabla comun_cliente
	// Como parametros puede recibir:
	// Campos del formulario:
		// -> id, Nombre, correo, Tel

	function edit_client($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Inserta un registro en la base de datos
		$result = $this -> comandasModel -> edit_client($objeto);

	// Regresa al ajax el mensaje
		echo json_encode($result);
	}

///////////////// ******** ---- 	FIN	edit_client		------ ************ //////////////////

///////////////// ******** ---- 		add_client		------ ************ //////////////////
//////// Edita un cliente a la base de datos en la tabla comun_cliente
	// Como parametros puede recibir:
	// Campos del formulario:
		// -> Nombre, correo, Tel

	function add_client($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Inserta un registro en la base de datos
		$result = $this -> comandasModel -> add_client($objeto);

	// Regresa al ajax el mensaje
		echo json_encode($result);
	}

///////////////// ******** ---- 	FIN	add_client		------ ************ //////////////////

///////////////// ******** ---- 		all_correos		------ ************ //////////////////
	// Como parametros puede recibir:
	// Campos del formulario:
		// -> ids cleintes

	function all_correos($objeto) {
		// Optenemos el logo
		$logo = $this -> comandasModel -> logo($objeto);
	
		// Valida el logo
		$src = '../../netwarelog/archivos/1/organizaciones/' . $logo['rows'][0]['logo'];
		$logo = (file_exists($src)) ? $src : '';
		

		$organizacion = $this -> comandasModel ->datos_organizacion();

        $datos_sucursal = $this -> comandasModel -> getSucursal();

        $img_correo = $this -> comandasModel -> getImgCorreo();

		$id = $_POST['id'];
		$objeto['id'] = $id;
		$objeto['logo'] = $logo;
		$objeto['organizacion'] = $organizacion;
		$objeto['datos_sucursal'] = $datos_sucursal;
		$objeto['img_correo'] = $img_correo;
        

        $res = $this->comandasModel->all_correos($objeto);
        echo json_encode($res);
	}

///////////////// ******** ---- 	FIN	all_correos		------ ************ //////////////////

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

///////////////// ******** ---- 	vista_promedio_comensal			------ ************ //////////////////
//////// Carga la vista en la que se consulta el promedio por comensal

	function editar_mapa_mesas($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
	$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
	// Consulta las sucursales
	$objeto['orden'] = 1;
	$sucursales = $this -> comandasModel -> listar_sucursales($objeto);
	$sucursales = $sucursales['rows'];
	// Consulta las areas que tiene
	$areas = $this -> comandasModel -> all_areas($objeto);

	// Consulta las comandas y las regresa en un array
	$empleados = $this -> comandasModel -> listar_empleados($objeto);

	// Consulta los tipos de mesa y las regresa en un array
	$tipo_mesas = $this -> comandasModel -> listar_tipo_mesas($objeto);

	if($objeto['area']){
		// Consulta el area actual
		$area = $this ->comandasModel -> area($objeto);
	} else {
		$area = $areas[0];
	}
	$objeto["id"] = $area["id"];
	$objeto["noJuntas"] = 1;
	$objeto['f_ini'] = date('Y-m-d') . ' 00:01';
	$objeto['f_fin'] = date('Y-m-d') . ' 23:59';
	$mesas = $this -> comandasModel -> getTables($objeto);
	$mesas = $mesas['rows'];

	$total_mesas = 0;
	$total_sillones = 0;
	$total_sillas = 0;
	$total_barras = 0;

	foreach ($mesas as $key => $value) {
		if($value['id_tipo_mesa'] == 6){
			$total_sillones = $total_sillones +1;
		}
		else if($value['id_tipo_mesa'] == 7){
			$total_barras = $total_barras +1;
			$value['f_ini'] = date('Y-m-d') . ' 00:01';
			$value['f_fin'] = date('Y-m-d') . ' 23:59';
			$mesas[$key]['sillas'] =  $this -> comandasModel -> getSillas($value);
		}
		else if($value['id_tipo_mesa'] == 9){
			$total_sillas = $total_sillas +1;
		}
		else{
			$total_mesas = $total_mesas + 1;
		}
	}

	// Carga la vista de editar mapa de mesas
		require ('views/comandas/vista_editar_mapa_mesas.php');
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
		$objeto['f_ini'] = date('Y-m-d') . ' 00:01';
		$objeto['f_fin'] = date('Y-m-d') . ' 23:59';
	// Consulta los datos de la mesa en la DB
		$result = $this -> comandasModel -> getTables($objeto);
		$result = $result['rows'];

		$_SESSION['tables'] = $result;
		$_SESSION['area'] = 1;

	// Regresa al ajax el mensaje
		if ($objeto['api'] == 1) {
			return json_encode($_SESSION['tables']);
		} else {
			echo json_encode($_SESSION['tables']);
		}
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
		
		if (!empty($objeto['texto'])) {
			// Consulta los productos
				$promociones = $this -> comandasModel -> listar_promociones($objeto);
				$promociones = $promociones['rows'];
			// Obtiene los productos de los combos y los agrega
				foreach ($promociones as $key => $value) {
					$value['tipo_promocion'] = $value['tipo'];
					$value['tipo'] = '';
					$productos2 = $this -> comandasModel -> listar_productos($value);
					$productos2 = $productos2['rows'];
					$productos2_ordenados = '';

				// Ordena los productos del combo
					foreach ($productos2 as $k => $v) {
					// Valida que exista la imagen
						if (!empty($v['imagen'])) {
							$src = '../pos/' . $v['imagen'];
							$v['imagen'] = (file_exists($src)) ? $src : '';
						}
						
						if($value['tipo_promocion'] == 1 || $value['tipo_promocion'] == 2 || $value['tipo_promocion'] == 4){
							$productos2_ordenados['productos'][$v['idProducto']] = $v;
						} else if($value['tipo_promocion'] == 3){
							$productos2_ordenados['mayor_price']['productos'][$v['idProducto']] = $v;
						} else if($value['tipo_promocion'] == 5){
							if($v['comprar'] == 1){
								$productos2_ordenados['comprar']['productos'][$v['idProducto']] = $v;
							} else{
								$productos2_ordenados['recibir']['productos'][$v['idProducto']] = $v;
							}
						}
					}
					 /*if($value['tipo_promocion'] == 5){
					echo "<pre>";
					print_r($productos2_ordenados);}*/
					$value['grupos'] = $productos2_ordenados;
					$value['id_comanda'] = $objeto['comanda'];
					$value['persona'] = $objeto['persona'];
					
				// Agrega el elemento al array
					$datos[$key] = $value;
				}
				
				session_start();
				$_SESSION['promociones'] = '';
		}
		// echo $productos2;

	// Calcula el dia en numero 0-6 Domingo-Sabado
		$fecha = date('Y-m-d');
		$dia = date('w', strtotime($fecha));

	// Obtiene la hora actual
		$hora = strtotime(date('H:i'));

	// Recorre los resgitros para ordenarlos
		foreach ($productos['rows'] as $key => $value) {

			if($value['totalimp']*1<=1){
				
				$objeto['id'] = $value['idProducto'];
				//$impuestos = $this -> comandasModel -> listar_impuestos($objeto);
				//if ($impuestos['total'] > 0) {
					$impuestos_comanda = 0;
					//foreach ($impuestos['rows'] as $k => $v) {
						if ($value["clave"] == 'IEPS') {
							$producto_impuesto = $ieps = (($value['precioventa']) * $value["valor"] / 100);
						} else {
							if ($ieps != 0) {
								$producto_impuesto = ((($value['precioventa'] + $ieps)) * $value["valor"] / 100);
							} else {
								$producto_impuesto = (($value['precioventa']) * $value["valor"] / 100);
							}
						}

					// Precio actualizado
						$productos['rows'][$key]['precioventa'] += $producto_impuesto;
						//$productos['rows'][$key]['precioventa'] = round($productos['rows'][$key]['precioventa'], 2); redondeo ch@
						$productos['rows'][$key]['precioventa'] = round($productos['rows'][$key]['precioventa']);
					//}
				//}

			}else{
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
					//$productos['rows'][$key]['precioventa'] = round($productos['rows'][$key]['precioventa'], 2); redondeo ch@
					$productos['rows'][$key]['precioventa'] = round($productos['rows'][$key]['precioventa']);
				}
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

		if(array_key_exists("api", $_REQUEST)){
			$datos['producto'] = $productos['rows'];
			return json_encode($datos);
		}else{
			$vista = (!empty($objeto['vista'])) ? $objeto['vista'] : 'vista_productos' ;
		}
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
		$objeto['f_ini'] = date('Y-m-d') . ' 00:01';
		$objeto['f_fin'] = date('Y-m-d') . ' 23:59';
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

///////////////// ******** ---- 		listar_comandas				------ ************ //////////////////
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

	// precio de complementos	
		foreach ($comandas as $k => $v) {
			$complementos = $v['complementos'];
			$precioC = $this -> comandasModel -> precio_complementos($complementos);
			$comandas[$k]['complementosp'] = $precioC;
			$comandas[$k]['total'] = $v['total'] + $precioC;
		}

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
	// pass -> contraseña a bsucar
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

		if ($objeto['json'] == 1) {
			return json_encode($respuesta);
		} else {
			echo json_encode($respuesta);
		}
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
			$objeto['f_ini'] = date('Y-m-d') . ' 00:01';
			$objeto['f_fin'] = date('Y-m-d') . ' 23:59';
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
				$objeto['f_ini'] = date('Y-m-d') . ' 00:01';
				$objeto['f_fin'] = date('Y-m-d') . ' 23:59';
				$mesas_asignacion = $this -> comandasModel -> getTables($objeto);
				$mesas_asignacion = $mesas_asignacion['rows'];
				$mesas_asignacion = array_column($mesas_asignacion, 'nombre_mesa');
				$mesas_asignacion = implode(',', $mesas_asignacion);

				$value['mesas_asignacion'] = $mesas_asignacion;
			}

			if (!empty($value['permisos'])) {
				$objeto['asignacion'] = $value['asignacion'];
				$objeto['f_ini'] = date('Y-m-d') . ' 00:01';
				$objeto['f_fin'] = date('Y-m-d') . ' 23:59';
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

		//Consulta el idioma seleccionado en configuración
		$idioma = $this -> comandasModel -> get_idioma($objeto);
			if($idioma == 1){
			require ('views/comandas/listar_mesas_mapa.php');}
			else {
				require ('views/comandas/listar_mesas_mapa-ingles.php');
			}
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

///////////////// ******** ---- 		listar_sub_comandas		------ ************ //////////////////
//////// Obtien las sub comandas y las carga en una div
	// Como parametros recibe:
		// Div -> div en donde se cargara el contenido
		// status -> el estatus por el que filtrara la comanda
		// id -> ID de la comanda

	function listar_sub_comandas_2($objeto) {
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
		require ('views/comandas/listar_sub_comandas_2.php');
	}

///////////////// ******** ---- 		FIN listar_sub_comandas_2		------ ************ //////////////////

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

///////////////// ******** ---- 		listar_personas_2		------ ************ //////////////////
//////// Obtienlas personas de la comanda y las carga en una div
	// Como parametros recibe:
		// Div -> div en donde se cargara el contenido

	function listar_personas_2($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Carga la vista
		require ('views/comandas/listar_personas_2.php');
	}

///////////////// ******** ---- 		FIN listar_personas_2		------ ************ //////////////////

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
			$objeto['mesa'] = $objeto['id'];
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
				/* Promociones
				================================================================================= */
					
					// Calcula el dia en numero 0-6 Domingo-Sabado
						$fecha = substr($value['fecha_comanda'], 0, 10);
						$dia = date("w", strtotime($fecha));
						
					// Obtiene la hora actual
						$hora = substr($value['fecha_comanda'], 11, 5);
						$hora =	strtotime($hora);
						
						$sql = 'SELECT 
				           			pro.* 
				          		FROM 
				          			com_promocionesXproductos p
				          		LEFT JOIN
				          				com_promociones pro
				          			ON
				           				pro.id = p.id_promocion
				           		WHERE 
				           			pro.status = 1 
				           		AND
				           			pro.tipo = 1
				           		AND 
				           			p.id_producto = '.$value['id'];
						$promocion = $this -> comandasModel -> queryArray($sql);
						$promocion = $promocion['rows'][0];

					// Si la promocion se encuentra en el horario la aplica
						if (strpos($promocion['dias'], $dia) !== false && !empty($promocion['dias'])) {
							$h_ini = strtotime($promocion['inicio']);
							$h_fin = strtotime($promocion['fin']);
							if ($hora >= $h_ini && $hora <= $h_fin) {
							// Porcentaje
								if($promocion['tipo'] == 1){
				                   	$objeto['pedidos'][$key]['nombre'] = $promocion['nombre'].' - '.$value['nombre'];
				                    $descuento = $value['precio_ticket'] * $promocion['descuento'] / 100;
									$objeto['pedidos'][$key]['precio_ticket'] -= $descuento;
								}
							}
						}
						
				/* FIN Promociones
				================================================================================= */
					
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

	//// ch@ new  ------------------------------------------------------------------------////////////////////////


///////////////// ******** ---- 		guardar_comanda_parcial2		------ ************ //////////////////
//////// Crear una comanda parcial, la guarda e imprime un Ticket
	// Como parametros recibe:

	function guardar_comanda_parcial2($objeto) {

	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		// Limpia el array
		$_SESSION['cerrar_personalizado']['finalizados'] = '';

		// pedidos
		//$pedidos = $_SESSION['cerrar_personalizado']['comanda'];
		$objeto['pedidos'] = $this-> comandasModel-> listar_pedidos($objeto);
		$objeto['pedidos'] =  $objeto['pedidos']['rows'];
	
		date_default_timezone_set('America/Mexico_City');

		$objeto['empleado'] = $_SESSION['mesero']['id'];
		$objeto['fecha'] = date('Y-m-d H:i:s');

			
		$objeto['ids_pedidos'] = '';
		//$objeto['total'] = 0;
		//$objeto['persona'] = $k;
			
		$pedi = "";
		
		foreach($objeto['pedidos'] as $key => $value) {
			$pedi .= $value['id'].',';
		}
		$pedi =substr ($pedi, 0, strlen($cad) - 1);

		$idpadre = $objeto['idpadre'];
		$mesa = $objeto['mesa'];

		// Recorre la comanda			
		foreach ($objeto['persona'] as $key => $value) {
			//$resp['id'] = $this -> comandasModel -> guardar_comanda_parcial2($idpadre,$mesa,$persona,$total,$objeto['fecha'],$objeto['empleado'],$divcant,$divporc);					
			

			if (!empty($objeto['pedidos'])) {

				if ($objeto['vista_estatus_comanda'] != 1) { // normal					
					$resp['id'] = $this -> comandasModel -> guardar_comanda_parcial2($idpadre,$mesa,$value['persona'],$value['pedidos'],$value['total'],$objeto['fecha'],$objeto['empleado'],$value['total'],$value['porce'],$value['cantidad']);					
					// Calcula el numero de 0 que debe de llevar el codigo
					$string = "";
					$size = 5 - strlen($resp['id']);
					for ($i = 0; $i < $size; $i++)
						$string .= "0";

					// Formatea el codigo
					$objeto['codigo'] = 'SUB' . $string . $resp['id'];
					$objeto['id'] = $resp['id'];

					$resp['actualizar_comanda'] = $this -> comandasModel -> actualizar_comanda_parcial2($objeto);

					// Asigna el status 2 a los pedidos
					$objeto['status'] = 2;
					$objeto['ids_pedidos'] = $pedi;

					$resp['actualizar_pedidos'] = $this -> comandasModel -> actualizar_pedidos($objeto);

					// Calcula la propina
					$objeto['propina'] = $value['total'] * 0.10;

					// Agrega los datos de la sub comanda
					$_SESSION['cerrar_personalizado']['finalizados'][$key]['pedidos'] = $objeto['pedidos'];
					$_SESSION['cerrar_personalizado']['finalizados'][$key]['cantidad'] = $value['cantidad'];
					$_SESSION['cerrar_personalizado']['finalizados'][$key]['propina'] = $objeto['propina'];
					$_SESSION['cerrar_personalizado']['finalizados'][$key]['total'] = $value['total'];
					$_SESSION['cerrar_personalizado']['finalizados'][$key]['codigo'] = $objeto['codigo'];
					$_SESSION['cerrar_personalizado']['finalizados'][$key]['id'] = $objeto['id'];

					//echo json_encode($_SESSION['cerrar_personalizado']['finalizados'][$idpadre]);
					unset($_SESSION['cerrar_personalizado']['comanda'][$key]);

				}else{

				}

			}
			
		}
		
		// Optenemos el logo
		$logo = $this -> comandasModel -> logo($objet);
		$objeto['logo'] = $logo['rows'][0]['logo'];

		// Consulta si se debe de mostrar la propina o no
		$propina = $this -> comandasModel -> listar_ajustes($objet);
		$objeto['mostrar'] = $propina['rows'][0]['propina'];
		
		// Carga la vista
		require ('views/comandas/imprime_comanda_parcial2.php');

		exit();
	
	}

///////////////// ******** ---- 		FIN guardar_comanda_parcial2		------ ************ //////////////////






//// ch@ new fin ------------------------------------------------------------------------////////////////////////

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
			$objeto2['id_comanda'] = $value['id'];
			$this -> listar_pedidos_persona_2($objeto2);		// Calcula el tiempo
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
		$objeto['f_ini'] = date('Y-m-d') . ' 00:01';
		$objeto['f_fin'] = date('Y-m-d') . ' 23:59';
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

///////////////// ******** ---- 	vista_reporte_consumo		------ ************ //////////////////
//////// Carga la vista del reporte de comensales por mesa

	function vista_reporte_consumo($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$objeto['f_ini'] = date('Y-m-d') . ' 00:01';
		$objeto['f_fin'] = date('Y-m-d') . ' 23:59';
	// Consulta las mesas y las regresa en un array
		$mesas = $this -> comandasModel -> getTables($objeto);
		$mesas = $mesas['rows'];

	// Consulta los empleado sy los regresa en un array
		$empleados = $this -> comandasModel -> listar_empleados($objeto);
		
	// Consulta las sucursales y las regresa en un array
		$sucursales = $this -> comandasModel -> listar_sucursales($objeto);
		$sucursales = $sucursales['rows'];
		
	// Carga la vista de los comensales
		require ('views/comandas/vista_reporte_consumo.php');
	}

///////////////// ******** ---- 		FIN	vista_reporte_consumo		------ ************ //////////////////

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
				$comandas[$key]['duracion'] = str_replace("-", '', $value["duracion"]);
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

	///////////////// ******** ---- 		listar_consumo		------ ************ //////////////////
//////// Obtien los registros de los comensales y los carga en la div
	// Como parametros puede recibir:
		// empleado -> id del empleado
		// f_ini -> fecha y hora inicial
		// f_fin -> Fecha y hora final
		// mesa -> ID de la mesa
		// sucursal -> ID de la sucursal

	function listar_consumo($objeto) {
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
				$comandas[$key]['duracion'] = str_replace("-", '', $value["duracion"]);
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
				$arrayConsu[] = $value['promedioComensal'];
				$arrayDur[] = $this->TimeToSec($value['duracion']);
			}
			$prom_con = array_sum($arrayConsu)/count($arrayConsu);
			$prom_dur = array_sum($arrayDur)/count($arrayDur);
			$prom_dur = $this->SecToHH_MM_SS($prom_dur);
			$dona = array();
			foreach ($datos as $key => $value) {
				array_push($dona, $value);
			}
		}

	// carga la vista para listar las comandas
		require ('views/comandas/listar_consumo.php');
	}

///////////////// ******** ---- 		FIN listar_consumo		------ ************ //////////////////

	function TimeToSec($time) {
	    $sec = 0;
	    foreach (array_reverse(explode(':', $time)) as $k => $v) $sec += pow(60, $k) * $v;
	    return $sec;
	}

	function SecToHH_MM_SS($seconds) {
	  $t = round($seconds);
	  return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
	}
///////////////// ******** ---- 		reiniciar_mesas		------ ************ //////////////////
//////// Manda llamar a la funcion que actualiza la pocision de las mesas
	// Como parametros recibe:

	function reiniciar_mesas($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$objeto['f_ini'] = date('Y-m-d') . ' 00:01';
		$objeto['f_fin'] = date('Y-m-d') . ' 23:59';
		$mesas = $this -> comandasModel -> getTables($objeto);
		$mesas = $mesas['rows'];
		
		$x = 0;
		$y = 0;
		
		foreach ($mesas as $key => $value) {
			if($value['tipo'] == 1 || $value['tipo'] == 2){
				if ($x > 9) {
					$x = 0;
					$y += 3;
				}
				
				$objeto['x'] = $x;
				$objeto['y'] = $y;
				$objeto['id_mesa'] = $value['mesa'];
				
				$resp['result'] = $this -> comandasModel -> reiniciar_mesas($objeto);
				
				$x += 3;
				$val[] = $objeto;
			}
		}
		// Guarda los permisos del mesero
		$resp['result'] = $val;
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

///////////////// ******** ---- 	agregar_area		------ ************ //////////////////
//////// Manda llamar a la funcion que inserta las mesas en la BD
	// Como parametros puede recibir:
		// nom_area -> nombre del area

	function agregar_area($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Guarda el area
		$resp['result'] = $this -> comandasModel -> agregar_area($objeto);
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0;
		
		echo json_encode($resp);
	}

///////////////// ******** ---- 		FIN agregar_area		------ ************ //////////////////

///////////////// ******** ---- 	delete_area		------ ************ //////////////////
//////// Manda llamar a la funcion que inserta las mesas en la BD
	// Como parametros puede recibir:
		// area -> id del area

	function delete_area($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Guarda el area
		$resp['result'] = $this -> comandasModel -> delete_area($objeto);
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0;
		
		echo json_encode($resp);
	}

///////////////// ******** ---- 		FIN delete_area		------ ************ //////////////////

///////////////// ******** ---- 	edit_area		------ ************ //////////////////
//////// Manda llamar a la funcion que inserta las mesas en la BD
	// Como parametros puede recibir:
		// nom_area -> nombre del area
		// area -> id del area

	function edit_area($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Guarda el area
		$resp['result'] = $this -> comandasModel -> edit_area($objeto);
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0;
		
		echo json_encode($resp);
	}

///////////////// ******** ---- 		FIN edit_area		------ ************ //////////////////


///////////////// ******** ---- 	agregar_mesas		------ ************ //////////////////
//////// Manda llamar a la funcion que inserta las mesas en la BD
	// Como parametros puede recibir:
			// tipo_mesa -> tipo de mesa a agregar
			// num_mesas -> numero de mesas a aagregar o numero de personas
			// empleado -> id empleado
			// idDep -> id del area a donde se va asignar la mesa
			// area -> id del area para crear
			// nombre_area -> nombre del area
			// total_barras -> numero total de barras
			// total_mesas -> numero total de mesas
			// total_sillones -> numero total de sillones

	function agregar_mesas($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Guarda las mesas
		if($objeto['tipo_mesa'] == 8){
			$resp['result'] = $this -> comandasModel -> agregar_mesas($objeto);
			$resp['status'] = (!empty($resp['result'])) ? 1 : 0;
		} else if($objeto['tipo_mesa'] == 7){
			//$objeto['nombre'] = $objeto['total_barras'];
			$objeto['nombre'] = $objeto['nombre_barra'];
			$resp['result'] = $this -> comandasModel -> agregar_mesas($objeto);
			$resp['status'] = (!empty($resp['result'])) ? 1 : 0;
			if($resp['status']){
				$objeto['id_dependencia'] = $resp['result'];
				for ($i = 0; $i < $objeto['num_mesas']; $i++) {
					$objeto['nombre'] = $i + 1;
					$objeto['tipo_mesa'] = 9;
					$resp['result'] = $this -> comandasModel -> agregar_mesas($objeto);
					$permisos .= $resp['result'] . ',';
					$resp['status'] = (!empty($resp['result'])) ? 1 : 0;
				}
			}
		} else {
			for ($i = 0; $i < $objeto['num_mesas']; $i++) {
				if($objeto['tipo_mesa'] == 6){
					$objeto['nombre'] = 'Sillon '.$objeto['total_sillones'];
					$objeto['total_sillones'] =  $objeto['total_sillones'] + 1;
				} else if($objeto['tipo_mesa'] == 9){
					$objeto['nombre'] = $objeto['total_sillas'];
					$objeto['total_sillas'] =  $objeto['total_sillas'] + 1;
				} else{
					$objeto['nombre'] = $objeto['total_mesas'];
					$objeto['total_mesas'] = $objeto['total_mesas'] + 1;
				}
				$resp['result'] = $this -> comandasModel -> agregar_mesas($objeto);
				$permisos .= $resp['result'] . ',';
				$resp['status'] = (!empty($resp['result'])) ? 1 : 0;
			}
		}
	
		session_start();
	// Agrega las mesas al mesero si existe
		if (!empty($objeto['empleado']) && $objeto['tipo_mesa'] != 8 && $objeto['tipo_mesa'] != 7) {
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

///////////////// ******** ---- 	editar_mesa		------ ************ //////////////////
//////// Manda llamar a la funcion que inserta las mesas en la BD
	// Como parametros puede recibir:
		// tipo_mesa -> tipo de mesa
		// nombre_mesa -> nombre de la mesa
		// mesa -> id mesa a editar
		// empleado -> id empleado

	function editar_mesa($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Edita la mesa
			$resp['result'] = $this -> comandasModel -> editar_mesa($objeto);
			if ($resp['result']) {
				$permisos .= $resp['result'] . ',';
			}
			$resp['status'] = (!empty($resp['result'])) ? 1 : 0;

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

///////////////// ******** ---- 		FIN editar_mesa		------ ************ //////////////////

///////////////// ******** ---- 	eliminar_mesa		------ ************ //////////////////
//////// Manda llamar a la funcion que inserta las mesas en la BD
	// Como parametros puede recibir:
		// mesa -> id mesa a eliminar

	function eliminar_mesa($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Elimina la mesa
		$resp['result'] = $this -> comandasModel -> eliminar_mesa($objeto);
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0;

		
		echo json_encode($resp);
	}

///////////////// ******** ---- 		FIN eliminar_mesa		------ ************ //////////////////

///////////////// ******** ---- 	vista_editar_mesa		------ ************ //////////////////
//////// Manda llamar a la funcion que inserta las mesas en la BD
	// Como parametros puede recibir:
		// mesa -> id mesa a eliminar

	function vista_editar_mesa($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Editar la mesa
		$resp['result'] = $this -> comandasModel -> vista_editar_mesa($objeto);
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0;

		
		echo json_encode($resp);
	}

///////////////// ******** ---- 		FIN vista_editar_mesa		------ ************ //////////////////

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

	function listar_utilidades2($objeto)
	{
		$utilidades = ($this->comandasModel->listar_utilidades2($_GET['f_ini'], $_GET['f_fin'], $_GET['sucursal'], $_GET['producto'])) ;
		$utilidades = $utilidades['rows'];
$dona = [];
			foreach ($utilidades as $key => $value) {
				$tmp['label'] = $value['producto'];
				$tmp['value'] = $value['utilidad'];
				array_push($dona, $tmp);
			}
		

		require ('views/comandas/listar_utilidades.php');


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
		$pedidos2 = $this -> comandasModel -> listar_pedidos_persona($objeto);
		$pedidos2 = $pedidos2['rows'];
		
		foreach ($pedidos2 as $key => $value) {
			$pedidos[] = $value;
			if($value['id_promocion'] != 0){
				
				$precio = 0;
				//$promocion = $this -> comandasModel -> get_promocion($value['id_promocion']);
				$promociones = $this -> comandasModel -> get_promociones($value['id'], $value['id_promocion']);
				$promociones = $promociones['rows'];
				foreach ($promociones as $key2 => $value2) {
					$pedidos[] = $value2;
				}
				
			}
		}
		//print_r($pedidos[0]); exit();
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
			//print_r($value);
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


///////////////// ******** ---- 				vista_comandera				------ ************ //////////////////
//////// Carga la vista de la comandera
	// Como parametros recibe:
		// div -> div donde se carga la vista de la comandera

	function vista_comandera($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		
		if($objeto['offline'] == 1){
			echo json_encode($objeto);
			return json_encode($objeto);
		}
		
	// Consulta las mesas libres
		foreach ($_SESSION['tables'] as $key => $value) {
			if($value["tipo"] == 0 && isset($value["mesa"])){
				$value["status"] = $this -> comandasModel -> mesas_ocupadas($value["mesa"]);
				$mesas_libres[] = $value;
			}
		}
		
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
		
		//Consulta el idioma seleccionado en configuración
		$idioma = $this -> comandasModel -> get_idioma($objeto);
		$formasDePago = $this->comandasModel->formasDePago();
			if($idioma == 1){
			require ('views/comandera/vista_comandera.php');}
			else {
				require ('views/comandera/vista_comandera-ingles.php');
			}
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

		/*var_dump(empty($objeto['id_comanda']));
		if(empty($objeto['id_comanda'])){
			echo 'esta vacia';
		}else{
			echo '('.$objeto['id_comanda'].')';
		}
		
		exit(); */
	// Valida si existe una comanda
		//print_r($objeto);
		//exit();
		$objeto['id_comanda'] = (empty($objeto['id_comanda'])) ? $this -> comandasModel -> insertComanda($objeto['id_mesa'], 0) : $objeto['id_comanda'] ;
	
	// Obtiene la informacion de la mesa
		$objeto['info_mesa'] = $this -> comandasModel -> getComanda($objeto['id_mesa']);
		$objeto['info_mesa'] = $objeto['info_mesa'] -> fetch_array(MYSQLI_ASSOC);
		
	// Si viene el nombre y la direccion se le asignan a una variable de sesion
	// si no conserva su valor
		$_SESSION['nombre'] = (!empty($objeto['nombre'])) ? $objeto['nombre'] : $_SESSION['nombre'];
		$_SESSION['direccion'] = (!empty($objeto['direccion'])) ? $objeto['direccion'] : $_SESSION['direccion'];
		$_SESSION['tel'] = (!empty($objeto['tel'])) ? $objeto['tel'] : $_SESSION['tel'];
		
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
		//print_r($objeto);
		//exit();

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
	
	// Consulta los pedidos de la persona
		$pedidos = $this -> comandasModel -> getItemsPerson($objeto['persona'], $objeto['id_comanda']);
		$pedidos = $pedidos['rows'];		
		
	// Lista los complementos si existen
		$total_precio = 0;
		foreach ($pedidos as $key => $value) {
			if (!empty($value['complementos'])) {
				$filtro['complementos'] = $value['complementos'];
				$complementos = $this -> comandasModel -> listar_complementos($filtro);
				
			/* Impuestos del producto
			============================================================================= */
			
				foreach ($complementos['rows'] as $kk => $vv) {
					$precio = $vv['precio'];
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
		
						// Precio actualizado
							$precio += $producto_impuesto;
							$precio = round($precio, 2);
						}
						
						$complementos['rows'][$kk]['precio'] = $precio;
					}
				}
				
			/* FIN Impuestos del producto
			============================================================================= */
			
				$pedidos[$key]['complementos'] = $complementos['rows'];
			}
			//print_r($value);
			if($value['id_promocion'] != 0){
				$precio = 0;
				$promocion = $this -> comandasModel -> get_promocion($value['id_promocion']);
				$pedidos[$key]['nombre'] = $promocion['nombre'];
				$pedidos[$key]['tipo'] = $promocion['tipo'];
				$pedidos[$key]['cantidad_to'] = $promocion['cantidad'];
				$pedidos[$key]['cantidad_descuento'] = $promocion['cantidad_descuento'];
				$pedidos[$key]['descuento'] = $promocion['descuento'];
				$pedidos[$key]['precio_fijo'] = $promocion['precio_fijo'];
				$promociones = $this -> comandasModel -> get_promociones($value['id'], $value['id_promocion']);
				$promociones = $promociones['rows'];
				if($promocion['tipo'] == 1){
					foreach ($promociones as $k => $v) {
						$precio += $v['precio'];
						$promociones[$k]['precio'] = 0;
					}
					$desc = (100 - $promocion['descuento']) / 100;
					$precio = $precio * $desc;
					$pedidos[$key]['precio'] = $precio;
					
				} else if($promocion['tipo'] == 2){
					foreach ($promociones as $k => $v) {
						if($k%2==0){
							$precio += $v['precio'];
						}
						$promociones[$k]['precio'] = 0;
					}
					$pedidos[$key]['precio'] = $precio;
				} else if($promocion['tipo'] == 4){
					foreach ($promociones as $k => $v) {
						$precio += $promocion['precio_fijo'] + $v['sumaExtras']*1;
						$promociones[$k]['precio'] = 0;
					}
					$pedidos[$key]['precio'] = $precio;
					
				} else if($promocion['tipo'] == 3){
					for ($x=0; $x < $promocion['cantidad_descuento']; $x++) { 
						$promociones[(count($promociones)-1) - $x]['precio'] = 0;
					}
					foreach ($promociones as $k => $v) {
						$precio += $v['precio'];
						$promociones[$k]['precio'] = 0;
					}
					$pedidos[$key]['precio'] = $precio;
				} else if($promocion['tipo'] == 5){
					//print_r($promociones);
					foreach ($promociones as $k => $v) {
						if($v['comprar'] == 1){
							$precio += $v['precio'];
						}
						$promociones[$k]['precio'] = 0;
					}
					$pedidos[$key]['precio'] = $precio;
				} 
				
				$pedidos[$key]['promociones'] = $promociones;
			}
			$total_precio += $pedidos[$key]['precio'];
		}		
		//$this -> comandasModel ->act_total_com($objeto['id_comanda'], $total_precio);
	//Consulta el idioma seleccionado en configuración
		$idioma = $this -> comandasModel -> get_idioma($objeto);


		/// actualizar total de comanda en base a pedidos
		$totalComanda = 0;

		foreach ($pedidos as $key => $value) {
			$totalComanda += $value['precio']*$value['cantidad'];
		}

		// ORO ch@
		$this -> comandasModel -> actualizarTotalComanda($objeto['id_comanda'],$totalComanda);
		//echo 'Total:'.$totalComanda;
		
		if($idioma == 1){
			require ('views/comandera/listar_pedidos_persona.php');}
		else {
			require ('views/comandera/listar_pedidos_persona-ingles.php');
		}
	}

///////////////// ******** ---- 		FIN listar_pedidos_persona			------ ************ //////////////////

///////////////// ******** ---- 			listar_pedidos_persona_2			------ ************ //////////////////
//////// Carga la vista de los productos de la persona
	// Como parametros recibe:
		// div -> div donde se carga la vista
		// persona -> ID de la persona
		// id_comanda -> ID de la comanda

	function listar_pedidos_persona_2($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
	

	// Consulta los pedidos de la persona
		$pedidos = $this -> comandasModel -> getItemsPerson_2($objeto['id_comanda']);
		$pedidos = $pedidos['rows'];
	
	// Lista los complementos si existen
		$total_precio = 0;
		foreach ($pedidos as $key => $value) {
			if (!empty($value['complementos'])) {
				$filtro['complementos'] = $value['complementos'];
				$complementos = $this -> comandasModel -> listar_complementos($filtro);
				
			/* Impuestos del producto
			============================================================================= */
			
				foreach ($complementos['rows'] as $kk => $vv) {
					$precio = $vv['precio'];
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
		
						// Precio actualizado
							$precio += $producto_impuesto;
							$precio = round($precio, 2);
						}
						
						$complementos['rows'][$kk]['precio'] = $precio;
					}
				}
				
			/* FIN Impuestos del producto
			============================================================================= */
			
				$pedidos[$key]['complementos'] = $complementos['rows'];
			}
			//print_r($value);
			if($value['id_promocion'] != 0){
				$precio = 0;
				$promocion = $this -> comandasModel -> get_promocion($value['id_promocion']);
				$pedidos[$key]['nombre'] = $promocion['nombre'];
				$pedidos[$key]['tipo'] = $promocion['tipo'];
				$pedidos[$key]['cantidad_to'] = $promocion['cantidad'];
				$pedidos[$key]['cantidad_descuento'] = $promocion['cantidad_descuento'];
				$pedidos[$key]['descuento'] = $promocion['descuento'];
				$pedidos[$key]['precio_fijo'] = $promocion['precio_fijo'];
				$promociones = $this -> comandasModel -> get_promociones($value['id'], $value['id_promocion']);
				$promociones = $promociones['rows'];
				if($promocion['tipo'] == 1){
					foreach ($promociones as $k => $v) {
						$precio += $v['precio'];
						$promociones[$k]['precio'] = 0;
					}
					$desc = (100 - $promocion['descuento']) / 100;
					$precio = $precio * $desc;
					$pedidos[$key]['precio'] = $precio;
					
				} else if($promocion['tipo'] == 2){
					foreach ($promociones as $k => $v) {
						if($k%2==0){
							$precio += $v['precio'];
						}
						$promociones[$k]['precio'] = 0;
					}
					$pedidos[$key]['precio'] = $precio;
				} else if($promocion['tipo'] == 4){
					foreach ($promociones as $k => $v) {
						$precio += $promocion['precio_fijo'];
						$promociones[$k]['precio'] = 0;
					}
					$pedidos[$key]['precio'] = $precio;
					
				} else if($promocion['tipo'] == 3){
					for ($x=0; $x < $promocion['cantidad_descuento']; $x++) { 
						$promociones[(count($promociones)-1) - $x]['precio'] = 0;
					}
					foreach ($promociones as $k => $v) {
						$precio += $v['precio'];
						$promociones[$k]['precio'] = 0;
					}
					$pedidos[$key]['precio'] = $precio;
				} else if($promocion['tipo'] == 5){
					//print_r($promociones);
					foreach ($promociones as $k => $v) {
						if($v['comprar'] == 1){
							$precio += $v['precio'];
						}
						$promociones[$k]['precio'] = 0;
					}
					$pedidos[$key]['precio'] = $precio;
				} 
				
				$pedidos[$key]['promociones'] = $promociones;
			}
			$total_precio += $pedidos[$key]['precio'];
		}

		//$this -> comandasModel ->act_total_com($objeto['id_comanda'], $total_precio);
	//Consulta el idioma seleccionado en configuración
		
	}

///////////////// ******** ---- 		FIN listar_pedidos_persona_2			------ ************ //////////////////

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
			//print_r($insumos);
			$insumos = $insumos['rows'];
			$si = 0;
			foreach ($insumos as $key => $value) {
			// Sin
				if (strpos($value['opcionales'], "1") !== false) {
					$datos['sin'][$value['idProducto']] = $value;
					$si = 1;
				}
			
			// Extra
				if (strpos($value['opcionales'], "2") !== false) {
					$datos['extra'][$value['idProducto']] = $value;
					$si = 1;
				}
			
			// opcionales
				if (strpos($value['opcionales'], "3") !== false) {
					$datos['opcionales'][$value['idProducto']] = $value;
					$si = 1;
				}
			}
			
			if ($si == 0) {
				// Formatea el contenido a json
				$objeto['btn'] = 'persona_'.$objeto['persona'];
				$objeto['f'] = 'guardar_pedido';
				$pedido = json_encode($objeto);
				$pedido = str_replace('"', "'", $pedido); 
				
				if($objeto['combo'] == 1){ ?>
					<script>
						if(!comandera.datos_combo.grupos[<?php echo $objeto['grupo'] ?>].num_seleccionados){
							comandera.datos_combo.grupos[<?php echo $objeto['grupo'] ?>].num_seleccionados = 0;
						}
						
						comandera.datos_combo.grupos[<?php echo $objeto['grupo'] ?>].num_seleccionados ++;
						comandera.seleccionar_pedido(<?php echo $pedido ?>);
						
						if(comandera.datos_combo.grupos[<?php echo $objeto['grupo'] ?>].num_seleccionados >= <?php echo $objeto['cantidad_grupo'] ?>){
							$("#<?php echo $objeto['div'] ?>").html('<i class="fa fa-cutlery"></i> <b>Grupo completo</b>');
						}
					</script><?php
			// Guarda el pedido de la persona normalmente
				} else if($objeto['promocion'] == 1){ ?>
					<?php if($objeto['tipo_promocion'] == 1 || $objeto['tipo_promocion'] == 2 || $objeto['tipo_promocion'] == 4) {?>
						<script>
							if(!comandera.datos_promocion.grupos['productos'][<?php echo $objeto['id_producto']?>].num_seleccionados){
								comandera.datos_promocion.grupos['productos'][<?php echo $objeto['id_producto']?>].num_seleccionados = 0;
							}
							
							comandera.datos_promocion.grupos['productos'][<?php echo $objeto['id_producto']?>].num_seleccionados ++;
							comandera.seleccionar_pedido(<?php echo $pedido ?>);
							console.log(comandera.datos_promocion);
							$("#div_promocion").html(comandera.htmlPromo);
						</script>
					<?php } else if($objeto['tipo_promocion'] == 3 || $objeto['tipo_promocion'] == 5) { ?>
						<script>
							if(!comandera.datos_promocion.grupos.<?php echo $objeto['grupo'] ?>.num_seleccionados){
								comandera.datos_promocion.grupos.<?php echo $objeto['grupo'] ?>.num_seleccionados = 0;
							}
							
							comandera.datos_promocion.grupos.<?php echo $objeto['grupo'] ?>.num_seleccionados ++;
							comandera.seleccionar_pedido(<?php echo $pedido ?>);
							
							if(comandera.datos_promocion.grupos.<?php echo $objeto['grupo'] ?>.num_seleccionados >= <?php echo $objeto['cantidad_grupo'] ?>){
								if(<?php echo $objeto['tipo_promocion'] ?> == 3){
									$("#<?php echo $objeto['div'] ?>").html('<i class="fa fa-cutlery"></i> <b>Promocion completa</b>');
								} else {
									$("#<?php echo $objeto['div'] ?>").html('<i class="fa fa-cutlery"></i> <b><?php echo ucfirst($objeto["grupo"])?></b>');
								}
							}
						</script>
					<?php }
			// Guarda el pedido de la persona normalmente
				} else{ ?>
					<script>
						comandera.guardar_pedido(<?php echo $pedido ?>);
					</script><?php
				}
			} else {
				// Formatea el contenido a json
					$objeto['btn'] = 'btn_guardar_detalles_pedido';
					$objeto['f'] = 'guardar_pedido';
					$objeto_json = json_encode($objeto);
					$objeto_json = str_replace('"', "'", $objeto_json);
					
					$idioma = $this -> comandasModel -> get_idioma($objeto);
				// Carga la vista
					if($idioma == 1){
						require ('views/comandera/detalles_producto.php');	
					} else {
						require ('views/comandera/detalles_producto-ingles.php');
					}
			}
		
		
		} else {
		// Formatea el contenido a json
			$objeto['btn'] = 'persona_'.$objeto['persona'];
			$objeto['f'] = 'guardar_pedido';
			$pedido = json_encode($objeto);
			$pedido = str_replace('"', "'", $pedido); 
			
			if($objeto['combo'] == 1){ ?>
				<script>
					if(!comandera.datos_combo.grupos[<?php echo $objeto['grupo'] ?>].num_seleccionados){
						comandera.datos_combo.grupos[<?php echo $objeto['grupo'] ?>].num_seleccionados = 0;
					}
					
					comandera.datos_combo.grupos[<?php echo $objeto['grupo'] ?>].num_seleccionados ++;
					comandera.seleccionar_pedido(<?php echo $pedido ?>);
					
					if(comandera.datos_combo.grupos[<?php echo $objeto['grupo'] ?>].num_seleccionados >= <?php echo $objeto['cantidad_grupo'] ?>){
						$("#<?php echo $objeto['div'] ?>").html('<i class="fa fa-cutlery"></i> <b>Grupo completo</b>');
					}
				</script><?php
		// Guarda el pedido de la persona normalmente
			} else if($objeto['promocion'] == 1){ ?>
				<?php if($objeto['tipo_promocion'] == 1 || $objeto['tipo_promocion'] == 2 || $objeto['tipo_promocion'] == 4) {?>
					<script>
						if(!comandera.datos_promocion.grupos['productos'][<?php echo $objeto['id_producto']?>].num_seleccionados){
							comandera.datos_promocion.grupos['productos'][<?php echo $objeto['id_producto']?>].num_seleccionados = 0;
						}
						
						comandera.datos_promocion.grupos['productos'][<?php echo $objeto['id_producto']?>].num_seleccionados ++;
						comandera.seleccionar_pedido(<?php echo $pedido ?>);
						console.log(comandera.datos_promocion);
						$("#div_promocion").html(comandera.htmlPromo);
					</script>
				<?php } else if($objeto['tipo_promocion'] == 3 || $objeto['tipo_promocion'] == 5) { ?>
					<script>
						if(!comandera.datos_promocion.grupos.<?php echo $objeto['grupo'] ?>.num_seleccionados){
							comandera.datos_promocion.grupos.<?php echo $objeto['grupo'] ?>.num_seleccionados = 0;
						}
						
						comandera.datos_promocion.grupos.<?php echo $objeto['grupo'] ?>.num_seleccionados ++;
						comandera.seleccionar_pedido(<?php echo $pedido ?>);
						
						if(comandera.datos_promocion.grupos.<?php echo $objeto['grupo'] ?>.num_seleccionados >= <?php echo $objeto['cantidad_grupo'] ?>){
							if(<?php echo $objeto['tipo_promocion'] ?> == 3){
								$("#<?php echo $objeto['div'] ?>").html('<i class="fa fa-cutlery"></i> <b>Promocion completa</b>');
							} else {
								$("#<?php echo $objeto['div'] ?>").html('<i class="fa fa-cutlery"></i> <b><?php echo ucfirst($objeto["grupo"])?></b>');
							}
						}
					</script>
				<?php }
		// Guarda el pedido de la persona normalmente
			} else{ ?>
				<script>
					comandera.guardar_pedido(<?php echo $pedido ?>);
				</script><?php
			}
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
		
		if(array_key_exists("api", $_REQUEST)){
			$objeto['productos'] = json_decode($objeto['productos'], true);

			foreach ($objeto['productos'] as $key => $value) {
				$result = $this -> comandasModel -> addProduct($value['id_producto'], $value['persona'], $value['id_comanda'], $value['opcionales'], $value['extras'], $value['sin'], $value['departamento'], $value['nota_opcional'], $value['nota_extra'], $value['nota_sin']);
			}
			print_r($result);exit();
			$datos['resultado'][] = $result;
		} 
		else {
			$result = $this -> comandasModel -> addProduct($objeto['id_producto'], $objeto['persona'], $objeto['id_comanda'], $objeto['opcionales'], $objeto['extras'], $objeto['sin'], $objeto['departamento'], $objeto['nota_opcional'], $objeto['nota_extra'], $objeto['nota_sin']);
		}

		if(array_key_exists("api", $_REQUEST)){
			return json_encode($datos);
		}else{
			echo json_encode($result);
		}
	}

///////////////// ******** ---- 			FIN guardar_pedido				------ ************ //////////////////

///////////////// ******** ----  				restar_pedido				------ ************ //////////////////
//////// Resta un pedido de la  persona
	// Como parametro puede recibi:
		// id -> ID del pedido
		// id_comanda -> ID de la comanda
		// persona -> numero de  persona
		// merma -> 1 Si sedebe de mandar a merma
		// comentario -> Comentario de la merma

	function restar_pedido($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		if($objeto['merma'] == 1){
			$result = $this -> comandasModel -> guardar_merma($objeto);
		}
		
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

///////////////// ******** ----  				guardar_info						------ ************ //////////////////
//////// guarda informacion adicional de correo
	// Como parametro puede recibir:
		// info adicional
	function guardar_info($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		//print_r($objeto);
	// Procesa los pedidos
		$result = $this -> comandasModel -> guardar_info($objeto);
		
		echo json_encode($result);
	}

///////////////// ******** ----  				FIN guardar_info					------ ************ //////////////////

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
		// personas -> numero de comensales
		// f_ini -> fecha inicio de la comanda
		// mesero -> Nombre del mesero

	function cerrar_comanda($objeto) {		
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Envia una notificacion a la aplicacion movil para cerrar la comanda.
		//$this -> comandasModel -> notificacionesMoviles($objeto);
		
		//  Tipo de cambio
		$tipocambio = $this -> comandasModel -> tipocambio();

	// Cerramos la comanda y regresamos el resultado
		$comanda = $this -> comandasModel -> closeComanda($objeto);
		//print_r($objeto); exit();
	

	// Optenemos el logo
		$logo = $this -> comandasModel -> logo($objeto);
	
	// remove password
		$this -> comandasModel -> removePassMesa($objeto);

	// Valida el logo
		$src = '../../netwarelog/archivos/1/organizaciones/' . $logo['rows'][0]['logo'];
		$comanda['logo'] = (file_exists($src)) ? $src : '';
		
	// Elimina la comanda de la mes aen la sesion
		session_start();
		$_SESSION['tables']['idmesa']['idcomanda'] = '';
		
	//Consulta el idioma seleccionado en configuración
		$idioma = $this -> comandasModel -> get_idioma($objeto);
		
	//Consulta los campos para mostrar en el tickect seleccionado en configuración
		$que_mostrar = $this -> comandasModel -> get_que_mostrar_ticket($objeto);

	//Consulta la organizacion
        $organizacion = $this -> comandasModel ->datos_organizacion();

    //Consulta datos de la sucursal
        $datos_sucursal =  $this -> comandasModel -> datos_sucursal($objeto['idmesa']);

    //Fecha fin de comanda
        $fecha_fin = $this -> formato_fecha($this -> comandasModel -> fecha_fin($objeto['idComanda']));

        $objeto['f_ini'] = $this -> formato_fecha($objeto['f_ini']);

        $tablet_browser = 0;
		$mobile_browser = 0;
		$body_class = 'desktop';

		if($objeto['banderita'] == 1){
			$mobile_browser = 1;
		}
		 
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
			if(file_exists($src)){
				$type = pathinfo($src, PATHINFO_EXTENSION);
				//$data_2 = file_get_contents($src);

				// Loading the image and getting the original dimensions
				$image = imagecreatefromjpeg($src);
				$orig_width = imagesx($image);
				$orig_height = imagesy($image);
				$width = 390;
				// Calc the new height
				$height = (($orig_height * $width) / $orig_width);

				// Create new image to display
				$new_image = imagecreatetruecolor($width, $height);

				// Create new image with change dimensions
				imagecopyresized($new_image, $image,
					0, 0, 0, 0,
					$width, $height,
					$orig_width, $orig_height);

				// Print image
				ob_start();
				imagejpeg($new_image);
				$data = ob_get_contents();
				ob_end_clean();
				//echo json_encode(array('image' => base64_encode($data)));
				$comanda['logo'] = base64_encode($data);
				$comanda['type'] = $type;
			} else {
				$comanda['logo'] = '';
				$comanda['type'] = '';
			}
		// Si es tablet has lo que necesites
			$dispositivo = 1;
			$datos['comanda'] = $comanda;
			$datos['que_mostrar'] = $que_mostrar;
			$datos['organizacion'] = $organizacion;
			$datos['datos_sucursal'] = $datos_sucursal;
			$datos['fecha_fin'] = $fecha_fin;
			$datos['objeto'] = $objeto;
		}
		else if ($mobile_browser > 0) {
			if(file_exists($src)){
				$type = pathinfo($src, PATHINFO_EXTENSION);
				//$data_2 = file_get_contents($src);

				// Loading the image and getting the original dimensions
				$image = imagecreatefromjpeg($src);
				$orig_width = imagesx($image);
				$orig_height = imagesy($image);
				$width = 390;
				// Calc the new height
				$height = (($orig_height * $width) / $orig_width);

				// Create new image to display
				$new_image = imagecreatetruecolor($width, $height);

				// Create new image with change dimensions
				imagecopyresized($new_image, $image,
					0, 0, 0, 0,
					$width, $height,
					$orig_width, $orig_height);

				// Print image
				ob_start();
				imagejpeg($new_image);
				$data = ob_get_contents();
				ob_end_clean();
				//echo json_encode(array('image' => base64_encode($data)));
				$comanda['logo'] = base64_encode($data);
				$comanda['type'] = $type;
			} else {
				$comanda['logo'] = '';
				$comanda['type'] = '';
			}
		// Si es dispositivo mobil has lo que necesites
		 	$dispositivo = 1;
		 	$datos['comanda'] = $comanda;
			$datos['que_mostrar'] = $que_mostrar;
			$datos['organizacion'] = $organizacion;
			$datos['datos_sucursal'] = $datos_sucursal;
			$datos['fecha_fin'] = $fecha_fin;
			$datos['objeto'] = $objeto;
		}
		else {
		// Si es ordenador de escritorio has lo que necesites
		  	$dispositivo = 2;
		} 
 	// Selecciona la vista que debe cargar al cerrar la comanda
		switch ($objeto['bandera']) {
		// Todo junto
			case 0:
				if ($tablet_browser > 0) {
					echo json_encode($datos);
				} else if ($mobile_browser > 0) {
					echo json_encode($datos);
				} else {
					if($idioma == 1){
						require ('views/comandera/cerrar_comanda_todo_junto.php');
					}
					else {
						require ('views/comandera/cerrar_comanda_todo_junto-ingles.php');
					}
				}
				
			break;
		
		// Individual
			case 1:
				if($idioma == 1){
					require ('views/comandera/cerrar_comanda_individual.php');
					}
					else {
						require ('views/comandera/cerrar_comanda_individual-ingles.php');
					}
				/*if ($tablet_browser > 0) {
					echo json_encode($datos);
				} else if ($mobile_browser > 0) {
					if($datos['comanda']['rows'][0]['nombre_mesa'] == null){
						$datos['comanda']['rows'][0]['nombre_mesa'] = " ";
					}
					echo json_encode($datos);
				} else {
					if($idioma == 1){
					require ('views/comandera/cerrar_comanda_individual.php');
					}
					else {
						require ('views/comandera/cerrar_comanda_individual-ingles.php');
					}
				}*/
				
			break;

			// para moduloprint LM
			case 15:
				echo json_encode($datos);
			break;
		}
	
	// Regresa un Json si se debe de mandar a caja
		if($objeto['bandera'] == 2 || $objeto['bandera'] == 3){
			echo json_encode($comanda);
		}
	}

///////////////// ******** ----  			FIN cerrar_comanda				------ ************ //////////////////

	function uploadfileProm() {
        $output_dir = "images/correo/";

        if (isset($_FILES["myfile"])) {

            //Filter the file types , if you want.
            if ($_FILES["myfile"]["error"] > 0) {
                echo "Error: " . $_FILES["file"]["error"] . "<br>";
            } else {
            	$type = pathinfo($output_dir . $_FILES["myfile"]["name"], PATHINFO_EXTENSION);
                //move the uploaded file to uploads folder;
                move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir . "promociones.".$type);
                $this -> comandasModel -> set_correo_archivos(1, $output_dir . "promociones.".$type);
                

            }
            echo json_encode( array('direccion' => $output_dir ."promociones.".$type, 'type' => $type, "nombre" => "promociones.".$type));
        }
    }

    function uploadfileImageFondo() {
        $output_dir = "../../../restaurantes_externo/imagenes/mesa_inteligente/";

        if (isset($_FILES["myfile"])) {
        	if ($_FILES["myfile"]["error"] > 0) {
                $data['status'] = 2;
                $data['mensaje'] = 'El archivo tiene un error.';
            } else {
	        	$mime = $_FILES['myfile']['type'];
	        	if($mime=="image/jpeg" || $mime=="image/pjpeg" || $mime=="image/gif" || $mime=="image/png"){
	        		$type = pathinfo($output_dir . $_FILES["myfile"]["name"], PATHINFO_EXTENSION);
                	//move the uploaded file to uploads folder;
                	move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir . $_FILES["myfile"]["name"]);
	            	$data['status'] = 1;
	            	$filehandle = opendir($output_dir); // Abrir archivos
	            	while ($file = readdir($filehandle)) {
				        if ($file != "." && $file != "..") {
				        		$finfo = finfo_open(FILEINFO_MIME_TYPE);
				        		$mime=finfo_file($finfo, $output_dir.$file);
							    if($mime=="image/jpeg" || $mime=="image/pjpeg" || $mime=="image/gif" || $mime=="image/png")
							    {
							        # guardamos las imagenes en un array
							        $posi = count($imagenes_fondo);
							        $imagenes_fondo[$posi]['ruta'] = $output_dir.$file;
							        $imagenes_fondo[$posi]['archivo'] = $file;
							    }
				            
					    } 
					} 
					closedir($filehandle);
					$data['imagenes'] = $imagenes_fondo;
	        	} else {
	        		$data['status'] = 2;
	        		$data['mensaje'] = 'Asegurese que el archivo sea una imagen.';
	        	}
        	}
        } else {
        	$data['status'] = 2;
            $data['mensaje'] = 'Seleccione un archivo por favor.';
        }
        echo json_encode($data);
    }

    function uploadfileLogoEmpresa() {
        $output_dir = "images/correo/";
        print_r($myfile);
        if (isset($_FILES["myfile"])) {

            //Filter the file types , if you want.
            if ($_FILES["myfile"]["error"] > 0) {
                echo "Error: " . $_FILES["file"]["error"] . "<br>";
            } else {
            	$type = pathinfo($output_dir . $_FILES["myfile"]["name"], PATHINFO_EXTENSION);
                //move the uploaded file to uploads folder;
                move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir . "logo_hibrido.".$type);
                $this -> comandasModel -> set_correo_archivos(4, $output_dir . "logo_hibrido.".$type);
                

            }
            echo json_encode( array('direccion' => $output_dir ."logo_hibrido.".$type, 'type' => $type, "nombre" => "logo_hibrido.".$type));
        }
    }

	function uploadfileFel() {
        $output_dir = "images/correo/";

        if (isset($_FILES["myfile"])) {

            //Filter the file types , if you want.
            if ($_FILES["myfile"]["error"] > 0) {
                echo "Error: " . $_FILES["file"]["error"] . "<br>";
            } else {
            	$type = pathinfo($output_dir . $_FILES["myfile"]["name"], PATHINFO_EXTENSION);
                //move the uploaded file to uploads folder;
                move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir . "felicitaciones.".$type);
                $this -> comandasModel -> set_correo_archivos(2, $output_dir . "felicitaciones.".$type);
                

            }
            echo json_encode( array('direccion' => $output_dir ."felicitaciones.".$type, 'type' => $type, "nombre" => "felicitaciones.".$type));
        }
    }

	function uploadfileMenu() {
        $output_dir = "images/correo/";

        if (isset($_FILES["myfile"])) {

            //Filter the file types , if you want.
            if ($_FILES["myfile"]["error"] > 0) {
                echo "Error: " . $_FILES["file"]["error"] . "<br>";
            } else {
            	$type = pathinfo($output_dir . $_FILES["myfile"]["name"], PATHINFO_EXTENSION);
                //move the uploaded file to uploads folder;
                move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir . "menu.".$type);
                $this -> comandasModel -> set_correo_archivos(3, $output_dir . "menu.".$type);
                

            }
            echo json_encode( array('direccion' => $output_dir ."menu.".$type, 'type' => $type, "nombre" => "menu.".$type));
        }
    }

///////////////// ******** ---- 				formato_fecha				------ ************ //////////////////
//////// Regresa formato correcto de fecha para ticket
	// Como parametros recibe:
		// fecha -> fecha a modificar
	function formato_fecha($fecha)
    {
        list($anio,$mes,$rest)=explode("-",$fecha);
        list($dia,$hora)=explode(" ",$rest);

        return $dia."/".$mes."/".$anio." ".$hora;
    }
///////////////// ******** ----  			FIN formato_fecha				------ ************ //////////////////

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

///////////////// ******** ---- 			repintar_mesas				------ ************ //////////////////
//////// Elimina la persona de la comanda y sus pedidos
	// Como parametros recibe:
		// ids_mesas -> ids de las mesas
		
	function repintar_mesas($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$resp = $this -> comandasModel -> repintar_mesas($objeto);
		echo json_encode($resp);
	}
	
///////////////// ******** ---- 			FIN repintar_mesas				------ ************ //////////////////

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

///////////////// ******** ---- 			vista_mudar_mesa			------ ************ //////////////////
//////// Carga la vista de las mesas a eliminar
	// Como parametros recibe:
		// div -> div donde se carga la vista de la comandera
	
	function vista_mudar_mesa($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal

		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		session_start();
		if (!empty($objeto['permisos'])) {
			$objeto['permisos'] = $objeto['permisos'];
			$objeto['permisos'] = str_replace(" ", '', $objeto['permisos']);
		}
		$objeto['f_ini'] = date('Y-m-d') . ' 00:01';
		$objeto['f_fin'] = date('Y-m-d') . ' 23:59';
		$mesas = $this -> comandasModel -> getTables($objeto);
		$mesas = $mesas['rows'];

		foreach ($mesas as $key => $value) {
			if($value['id_tipo_mesa'] == 7){
				$value['individual'] = 1;
				$value['f_ini'] = date('Y-m-d') . ' 00:01';
				$value['f_fin'] = date('Y-m-d') . ' 23:59';
				$mesas[$key]['sillas'] =  $this -> comandasModel -> getSillas($value);
			}
		}
	
		foreach ($mesas as $key => $value) {

			if($value["tipo"] == 0 && isset($value["mesa"]) && $value['mesa_status'] != 4  && $value['id_res'] == null && $objeto["id_mesa"] != $value["mesa"]){

				if($value['id_tipo_mesa'] == 7 ){

					foreach ($value['sillas'] as $key => $row) {
						if($row['mesa_status'] != 4  && $row['id_res'] == null){
							$row["status"] = $this -> comandasModel -> mesas_ocupadas($row["mesa"]);

							$row["junta"] = $this -> comandasModel -> mesa_junta($row["mesa"]);
							$mesas_libres[] = $row;
						}
					}
				} else if($value['id_tipo_mesa'] != 8) {

					$value["status"] = $this -> comandasModel -> mesas_ocupadas($value["mesa"]);
					$value["junta"] = $this -> comandasModel -> mesa_junta($value["mesa"]);
					$mesas_libres[] = $value;

				}	
			}
		}
		$areas = $this -> comandasModel -> areas($objeto);
		$_SESSION['area_princ'] = $this -> comandasModel -> first_area($objeto);
		$area_princ = $_SESSION['area_princ'];
		//Consulta el idioma seleccionado en configuración
			$idioma = $this -> comandasModel -> get_idioma($objeto);
		require ('views/comandera/vista_mudar_mesa.php');
	}

///////////////// ******** ---- 			FIN vista_mudar_mesa		------ ************ //////////////////


///////////////// ******** ---- 			vista_eliminar_mesas			------ ************ //////////////////
//////// Carga la vista de las mesas a eliminar
	// Como parametros recibe:
		// div -> div donde se carga la vista de la comandera
	
	function vista_eliminar_mesas($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		session_start();
		foreach ($_SESSION['tables'] as $key => $value) {
			
			if($value["tipo"] == 0 && isset($value["mesa"])){
				if($this -> comandasModel -> mesas_ocupadas($value["mesa"]) == 0){
					if($this -> comandasModel -> mesa_junta($value["mesa"]) == 0){
						$mesas_libres[] = $value;	
					}
				}			
			}
		}
		
		//Consulta el idioma seleccionado en configuración
			$idioma = $this -> comandasModel -> get_idioma($objeto);
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
			$resp['idioma'] = $this -> comandasModel -> get_idioma($objeto);
			echo json_encode($resp);
			return 0;
		}

		//si es un llamado desde movil se convierte 
		if (array_key_exists("api", $_REQUEST)){
			$objeto['mesas'] = json_decode($objeto['mesas']);
		}
		
		//print_r($objeto['mesas']); exit();
		
		foreach ($objeto['mesas'] as $key => $value) {
			//echo $value; exit();
			$resp['result'] = $this -> comandasModel -> removeTable($value);
		}

		if (!empty($resp['result'])) {
			$resp['status'] = 1;
		} else {
			$resp['status'] = 9;
		}
		
		session_start();
		$_SESSION['area'] = 0;
		
	// Regresa al ajax el mensaje
		if(array_key_exists("api", $_REQUEST)){
			//$datos['status'] = $resp;
			return json_encode($resp);
		}else{
			echo json_encode($resp);
		}
		
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
		
		
		if (!empty($objeto['permisos'])) {
			$objeto['permisos'] = $objeto['permisos'];
			$objeto['permisos'] = str_replace(" ", '', $objeto['permisos']);
		}
		
		$objeto['f_ini'] = date('Y-m-d') . ' 00:01';
		$objeto['f_fin'] = date('Y-m-d') . ' 23:59';
		$mesas = $this -> comandasModel -> getTables($objeto);
		$mesas = $mesas['rows'];
		
		foreach ($mesas as $key => $value) {
			if($value["tipo"] == 0 && isset($value["mesa"]) && $value['mesa_status'] != 4 && $value['id_res'] == null){
				if ($value['id_tipo_mesa'] == 1 
					|| $value['id_tipo_mesa'] == 2 
					|| $value['id_tipo_mesa'] == 3 
					|| $value['id_tipo_mesa'] == 4 
					|| $value['id_tipo_mesa'] == 5) {
					$value["status"] = $this -> comandasModel -> mesas_ocupadas($value["mesa"]);
					$value["junta"] = $this -> comandasModel -> mesa_junta($value["mesa"]);
					$mesas_juntar[] = $value;				
				}
			}
		}
		$areas = $this -> comandasModel -> areas($objeto);
		$_SESSION['area_princ'] = $this -> comandasModel -> first_area($objeto);
		$area_princ = $_SESSION['area_princ'];
	//Consulta el idioma seleccionado en configuración
		$idioma = $this -> comandasModel -> get_idioma($objeto);
		require ('views/comandas/vista_juntar_mesas.php');
	}

///////////////// ******** ---- 		FIN vista_juntar_mesas				------ ************ //////////////////


///////////////// ******** ---- 			vista_juntar_sillas				------ ************ //////////////////
//////// Carga la vista de las mesas 
	// Como parametros recibe:
		// div -> div donde se carga la vista de la comandera
	
	function vista_juntar_sillas($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		session_start();		
		
		
		if (!empty($objeto['permisos'])) {
			$objeto['permisos'] = $objeto['permisos'];
			$objeto['permisos'] = str_replace(" ", '', $objeto['permisos']);
		}
		
		$objeto['f_ini'] = date('Y-m-d') . ' 00:01';
		$objeto['f_fin'] = date('Y-m-d') . ' 23:59';
		$mesas = $this -> comandasModel -> getTables2($objeto);
		$mesas = $mesas['rows'];
		
		foreach ($mesas as $key => $value) {
			if($value["tipo"] == 0 && isset($value["mesa"]) && $value['mesa_status'] != 4 && $value['id_res'] == null){
				if ($value['id_tipo_mesa'] == 9) {
					$value["status"] = $this -> comandasModel -> mesas_ocupadas($value["mesa"]);
					$value["junta"] = $this -> comandasModel -> mesa_junta($value["mesa"]);
					$mesas_juntar[] = $value;				
				}
			}
		}
		$areas = $this -> comandasModel -> areas($objeto);
		$_SESSION['area_princ'] = $this -> comandasModel -> first_area($objeto);
		$area_princ = $_SESSION['area_princ'];
	//Consulta el idioma seleccionado en configuración
		$idioma = $this -> comandasModel -> get_idioma($objeto);
		require ('views/comandas/vista_juntar_sillas.php');
	}

///////////////// ******** ---- 		FIN vista_juntar_sillas				------ ************ //////////////////

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

	//si es un llamado desde movil se convierte 
		if (array_key_exists("api", $_REQUEST)){
			$objeto['mesas'] = json_decode($objeto['mesas']);
		}
		
	// Forma una cadena con los Ids de las mesas
		foreach ($objeto['mesas'] as $key => $value) {
			$ids_mesas .= $value['id_mesa'].',';
		}
		$ids_mesas = substr($ids_mesas, 0, -1);
		$resp['result'] = $this -> comandasModel -> joinTables($ids_mesas);

		if (!empty($resp['result'])) {
			$resp['status'] = 1;
		} else {
			$resp['status'] = 9;
		}
		
		session_start();
		$_SESSION['area'] = 0;
		
	// Regresa al ajax el mensaje
		if(array_key_exists("api", $_REQUEST)){
			//$datos['status'] = $resp;
			return json_encode($resp);
		}else{
			echo json_encode($resp);
		}
		
	}

///////////////// ******** ---- 			FIN juntar_mesas				------ ************ //////////////////


///////////////// ******** ---- 				juntar_sillas				------ ************ //////////////////
//////// Elimina las mesas seleccionadas
	// Como parametros recibe:
		// pass -> Contraseña de seguridad
		// mesas_seleccionadas -> IDs de las mesas seleccionadas

	function juntar_sillas($objeto) {

		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$resp['status'] = 1;

		//si es un llamado desde movil se convierte 
			if (array_key_exists("api", $_REQUEST)){
				$objeto['mesas'] = json_decode($objeto['mesas']);
			}
		
		// Forma una cadena con los Ids de las mesas
			foreach ($objeto['mesas'] as $key => $value) {
				$ids_mesas .= $value['id_mesa'].',';
			}
			$ids_mesas = substr($ids_mesas, 0, -1);
			$resp['result'] = $this -> comandasModel -> joinTables($ids_mesas);

			if (!empty($resp['result'])) {
				$resp['status'] = 1;
			} else {
				$resp['status'] = 9;
			}
			
			session_start();
			$_SESSION['area'] = 0;
		
		// Regresa al ajax el mensaje
			if(array_key_exists("api", $_REQUEST)){
				//$datos['status'] = $resp;
				return json_encode($resp);
			}else{
				echo json_encode($resp);
			}
		
	}

///////////////// ******** ---- 			FIN juntar_sillas				------ ************ //////////////////	

///////////////// ******** ---- 			listar_promociones					------ ************ //////////////////
//////// Consulta los combos, sus productos y carga la vista de los combos
	// Como parametros recibe:
		// div -> Div donde se cargaron los combos

	function listar_promociones($objeto) {

	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
	// Consulta los productos
		$promociones = $this -> comandasModel -> listar_promociones($objeto);
		$promociones = $promociones['rows'];
	// Obtiene los productos de los combos y los agrega
		foreach ($promociones as $key => $value) {
			$value['tipo_promocion'] = $value['tipo'];
			$value['tipo'] = '';
			$productos = $this -> comandasModel -> listar_productos($value);
			$productos = $productos['rows'];
			$productos_ordenados = '';

		// Ordena los productos del combo
			foreach ($productos as $k => $v) {
			// Valida que exista la imagen
				if (!empty($v['imagen'])) {
					$src = '../pos/' . $v['imagen'];
					$v['imagen'] = (file_exists($src)) ? $src : '';
				}
				
				if($value['tipo_promocion'] == 1 || $value['tipo_promocion'] == 2 || $value['tipo_promocion'] == 4){
					$productos_ordenados['productos'][$v['idProducto']] = $v;
				} else if($value['tipo_promocion'] == 3){
					$productos_ordenados['mayor_price']['productos'][$v['idProducto']] = $v;
				} else if($value['tipo_promocion'] == 5){
					if($v['comprar'] == 1){
						$productos_ordenados['comprar']['productos'][$v['idProducto']] = $v;
					} else{
						$productos_ordenados['recibir']['productos'][$v['idProducto']] = $v;
					}
				}
			}
			 /*if($value['tipo_promocion'] == 5){
			echo "<pre>";
			print_r($productos_ordenados);}*/
			$value['grupos'] = $productos_ordenados;
			$value['id_comanda'] = $objeto['comanda'];
			$value['persona'] = $objeto['persona'];
			
		// Agrega el elemento al array
			$datos[$key] = $value;
		}
		
		session_start();
		$_SESSION['promociones'] = '';
		
	// Carga la vista de listado por default si no existe una vista
		$vista = (!empty($objeto['vista'])) ? $objeto['vista'] : 'listar_promociones';
	//Si corresponde a la App de móviles...
		if(array_key_exists("api", $_REQUEST)){

			foreach ($datos as $key => $value) {
				$info["combos"][$key] = $value;
			}

			return json_encode($info);
		} else {
		// Carga la vista para web
			$idioma = $this -> comandasModel -> get_idioma($objeto);
			require ('views/comandera/'.$vista.'.php');
		}
	}

///////////////// ******** ---- 			FIN listar_promociones				------ ************ //////////////////

///////////////// ******** ---- 			listar_combos					------ ************ //////////////////
//////// Consulta los combos, sus productos y carga la vista de los combos
	// Como parametros recibe:
		// div -> Div donde se cargaron los combos

	function listar_combos($objeto) {

	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
	// Consulta los productos
		$combos = $this -> comandasModel -> listar_combos($objeto);
		$combos = $combos['rows'];
	// Obtiene los productos de los combos y los agrega
		foreach ($combos as $key => $value) {
			$productos = $this -> comandasModel -> listar_productos($value);
			$productos = $productos['rows'];
			$productos_ordenados = '';
		
		// Ordena los productos del combo
			foreach ($productos as $k => $v) {
			// Valida que exista la imagen
				if (!empty($v['imagen'])) {
					$src = '../pos/' . $v['imagen'];
					$v['imagen'] = (file_exists($src)) ? $src : '';
				}
				
				$productos_ordenados[$v['grupo']]['productos'][$v['idProducto']] = $v;
				$productos_ordenados[$v['grupo']]['cantidad_grupo'] = $v['cantidad_grupo'];
			}
			
			$value['grupos'] = $productos_ordenados;
			
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
		$_SESSION['combo'] = '';
		
	// Carga la vista de listado por default si no existe una vista
		$vista = (!empty($objeto['vista'])) ? $objeto['vista'] : 'listar_combos';

	//Si corresponde a la App de móviles...
		if(array_key_exists("api", $_REQUEST)){

			foreach ($datos as $key => $value) {
				$info["combos"][$key] = $value;
			}

			return json_encode($info);
		} else {
		// Carga la vista para web
			$idioma = $this -> comandasModel -> get_idioma($objeto);
			require ('views/comandera/'.$vista.'.php');
		}
	}

///////////////// ******** ---- 			FIN listar_combos				------ ************ //////////////////
///////////////// ******** ---- 			listar_productos_combo			------ ************ //////////////////
//////// Carga la vista de los productos del combo
	// Como parametros recibe:
		// div -> Div donde se cargaron los combos
		// id_combo -> ID del combo
		
	function listar_productos_combo($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		session_start();
		$_SESSION['combo'] = $objeto['combo'];
		
	// Carga la vista
		$idioma = $this -> comandasModel -> get_idioma($objeto);
		require ('views/comandera/listar_productos_combo.php');
	}

///////////////// ******** ---- 		FIN listar_productos_combo			------ ************ //////////////////
///////////////// ******** ---- 			listar_productos_promociones			------ ************ //////////////////
//////// Carga la vista de los productos del combo
	// Como parametros recibe:
		// div -> Div donde se cargaron los combos
		// id_combo -> ID del combo
	function listar_productos_promociones($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		//$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$objeto = $_POST['obj'];
		$objeto =  json_decode($objeto,true);

		session_start();
		$objeto['promocion']['grupos']['productos']='';
		$objeto['id_promocion'] = $objeto['id'];
		$objeto['id'] = '';
		$productos_promo = $this -> comandasModel -> listar_productos($objeto);
		foreach ($productos_promo['rows'] as $key => $value) {
			$objeto['promocion']['grupos']['productos'][$key] = $value;
		}
		 

		$_SESSION['promociones'] = $objeto['promocion'];		 
		
	// Carga la vista
		$idioma = $this -> comandasModel -> get_idioma($objeto);
		require ('views/comandera/listar_productos_promocion.php');
	}

	/*
	function listar_productos_promociones2($objeto) {
		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		session_start();

			$objeto['id_promocion'] = $objeto['promocion']['id_promocion'];
			$objeto['div'] = 'div_productos_promociones';
			$objeto['boton'] = 'promocion_'.$objeto['id_promocion'];
			$objeto['id'] = '';

			$tipo_promocion = $objeto['promocion']['tipo_promocion'];

			$objeto['promocion']['grupos']['productos']='';

			if ($tipo_promocion == 5) {
				
				$objeto['promocion']['grupos']['comprar']['productos']='';
				$objeto['promocion']['grupos']['recibir']['productos']='';
			}
			
			$id_promocion = $_POST['id_promocion'];

			$productos_promo = $this -> comandasModel -> listar_productos($objeto);

			foreach ($productos_promo['rows'] as $key => $value) {
				//$objeto['promocion']['grupos']['productos'][$key] = $value;
				$arra[$value['idProducto']] = array(
					idProducto => $value['idProducto'],
					nombre => $value['nombre'],
					costo => $value['costo'],
					imagen => $value['imagen'],
					idunidadCompra => $value['idunidadCompra'],
					idunidad => $value['idunidad'],
					unidad => $value['unidad'],
					factor => $value['factor'],
					tipo_producto => $value['tipo_producto'],
					precio => $value['precio'],
					codigo => $value['codigo'],
					parent => $value['parent'],
					id => $value['id'],
					text => $value['text'],
					icon => $value['icon'],
					cantidad => $value['cantidad'],
					materiales => $value['materiales'],
					id_departamento => $value['id_departamento'],
					grupo => $value['grupo'],
					cantidad_grupo => $value['cantidad_grupo'],
					recibir => $value['recibir'],
					comprar => $value['comprar'],
					ins => $value['ins'],
					inse => $value['inse'],
					opc => $value['opc'],				
					);
				if($tipo_promocion == 5){
					if($value['comprar'] == 1){
						$comprar[$value['idProducto']] = array(
						idProducto => $value['idProducto'],
						nombre => $value['nombre'],
						costo => $value['costo'],
						imagen => $value['imagen'],
						idunidadCompra => $value['idunidadCompra'],
						idunidad => $value['idunidad'],
						unidad => $value['unidad'],
						factor => $value['factor'],
						tipo_producto => $value['tipo_producto'],
						precio => $value['precio'],
						codigo => $value['codigo'],
						parent => $value['parent'],
						id => $value['id'],
						text => $value['text'],
						icon => $value['icon'],
						cantidad => $value['cantidad'],
						materiales => $value['materiales'],
						id_departamento => $value['id_departamento'],
						grupo => $value['grupo'],
						cantidad_grupo => $value['cantidad_grupo'],
						recibir => $value['recibir'],
						comprar => $value['comprar'],
						ins => $value['ins'],
						inse => $value['inse'],
						opc => $value['opc'],				
						);
					}
					if($value['recibir'] == 1){
						$recibir[$value['idProducto']] = array(
						idProducto => $value['idProducto'],
						nombre => $value['nombre'],
						costo => $value['costo'],
						imagen => $value['imagen'],
						idunidadCompra => $value['idunidadCompra'],
						idunidad => $value['idunidad'],
						unidad => $value['unidad'],
						factor => $value['factor'],
						tipo_producto => $value['tipo_producto'],
						precio => $value['precio'],
						codigo => $value['codigo'],
						parent => $value['parent'],
						id => $value['id'],
						text => $value['text'],
						icon => $value['icon'],
						cantidad => $value['cantidad'],
						materiales => $value['materiales'],
						id_departamento => $value['id_departamento'],
						grupo => $value['grupo'],
						cantidad_grupo => $value['cantidad_grupo'],
						recibir => $value['recibir'],
						comprar => $value['comprar'],
						ins => $value['ins'],
						inse => $value['inse'],
						opc => $value['opc'],				
						);
					}
				}
				
			}
			
			//$objeto['id'] = $objeto['id_promocion'];
			$objeto['promocion']['grupos']['productos'] = $arra;

			if ($tipo_promocion == 5) {
				
				$objeto['promocion']['grupos']['comprar']['productos'] = $comprar;
				$objeto['promocion']['grupos']['recibir']['productos'] = $recibir;
			}

			
		$_SESSION['promociones'] = $objeto['promocion'];

		//echo json_encode($objeto);	 
	
		// Carga la vista
		$idioma = $this -> comandasModel -> get_idioma($objeto);
		require ('views/comandera/listar_productos_promocion.php');
	}
	*/

///////////////// ******** ---- 		FIN listar_productos_promociones			------ ************ //////////////////

///////////////// ******** ---- 				guardar_combo					------ ************ //////////////////
//////// Guarda el pedido del combo y los pedidos de sus productos
	// Como parametros recibe:
		
	function guardar_combo($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		$num_grupos = $objeto['datos_combo']['grupos'];
		$num_grupos = count($num_grupos);
		$num_pedidos = $objeto['pedidos'];
		$num_pedidos = count($num_pedidos);
		
	// Valida que se seleccionen los productos
		if ($num_grupos > $num_pedidos) {
			$resp['status'] = 2;
			echo json_encode($resp);
			
			return 0;
		}
	
	// Obtiene los extras de los pedidos
		$extras = '';
		foreach ($objeto['pedidos'] as $k => $v) {
			foreach ($v as $key => $value) {
				foreach ($value as $key2 => $value2) {
					if (!empty($value2['extras'])) {
						$extras .= (empty($extras)) ? $value2['extras'] : ','.$value2['extras'] ;
					}
				}
				
			}
		}
		
		$idproduct = $objeto['datos_combo']['id_combo'];
		$idperson = $objeto['persona'];
		$idcomanda = $resp['id_comanda'] = $objeto['datos_combo']['id_comanda'];
		$iddep = $objeto['datos_combo']['id_departamento'];
		
		$id_pedido = $this -> comandasModel -> addProduct($idproduct, $idperson, $idcomanda, $opcionales, $extras, $sin, $iddep, $nota_opcional, $nota_extra, $nota_sin);
		
		foreach ($objeto['pedidos'] as $k => $v) {

			foreach ($v as $key => $value) {
				foreach ($value as $key2 => $value2) {
						$value2['id_pedido'] = $id_pedido;
						$value2['id_comanda'] = $idcomanda;
						$value2['persona'] = $idperson;
						$resp['result'] = $this -> comandasModel -> guardar_pedido_combo($value2);
					
				}
			}
		}
		
		echo json_encode($resp);
	}

///////////////// ******** ---- 			FIN guardar_combo					------ ************ //////////////////

///////////////// ******** ---- 				guardar_promocion					------ ************ //////////////////
//////// Guarda el pedido del combo y los pedidos de sus productos
	// Como parametros recibe:
		
	function guardar_promocion($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		//$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		$objeto = $_POST['obj'];
		$objeto =  json_decode($objeto,true);		
		
		if($objeto['datos_promocion']['tipo_promocion'] == 1 || $objeto['datos_promocion']['tipo_promocion'] == 2 || $objeto['datos_promocion']['tipo_promocion'] == 4) {
			if ($objeto['pedidos'] == 0) {
				$resp['status'] = 2;
				echo json_encode($resp);
				
				return 0;
			}
		} else {
			if($objeto['datos_promocion']['tipo_promocion'] == 3){
				foreach ($objeto['pedidos']['mayor_price'] as $key => $value) {
					foreach ($value as $key2 => $value2) {
						$count ++;
					}
				}
				if ($objeto['datos_promocion']['cantidad'] > $count) {
					
					$resp['status'] = 2;
					echo json_encode($resp);
					
					return 0;
				}
				
			}
			if($objeto['datos_promocion']['tipo_promocion'] == 5){
				foreach ($objeto['pedidos']['comprar'] as $key => $value) {
					foreach ($value as $key2 => $value2) {
						$countC ++;
					}

				}
				foreach ($objeto['pedidos']['recibir'] as $key => $value) {
					foreach ($value as $key2 => $value2) {
						$countR ++;
					}
				}
				if ($objeto['datos_promocion']['cantidad'] > $countC || $objeto['datos_promocion']['cantidad_descuento'] > $countR) {
					$resp['countR'] = $countR;
					$resp['countC'] = $countC;
					$resp['status'] = 2;
					echo json_encode($resp);
					
					return 0;
				}
				
			}
		}
		// Obtiene los extras de los pedidos


		

		$is_promocion = 1;
		$id_promocion = $objeto['datos_promocion']['id_promocion'];
		$tipo_promocion = $objeto['datos_promocion']['tipo_promocion'];
		$cantidad = intval($objeto['datos_promocion']['cantidad']);
		$cantidad_descuento = intval($objeto['datos_promocion']['cantidad_descuento']);
		$idperson = $objeto['persona'];
		$idcomanda = $resp['id_comanda'] = $objeto['datos_promocion']['id_comanda'];
		
		$id_pedido = $this -> comandasModel -> addProduct($idproduct, $idperson, $idcomanda, $opcionales, $extras, $sin, $iddep, $nota_opcional, $nota_extra, $nota_sin, $id_promocion, $is_promocion);
	
		foreach ($objeto['pedidos'] as $k => $v) {
			foreach ($v as $key => $value) {
				foreach ($value as $key2 => $value2) {
					$value2['dependencia_promocion'] = $id_pedido;
					$value2['id_comanda'] = $idcomanda;
					$value2['persona'] = $idperson;


					//print_r($value2); exit();
					if ($tipo_promocion == 2) {
						for ($i=0; $i < $cantidad; $i++) { 
							
							//// EVITA LOS EXTRAS EN 2X1 3X2 ETC.
							if($i == 0){
								$value2['extras'] = $value2['extras'];
							}else{
								$value2['extras'] = '';
							}

							$resp['result'] = $this -> comandasModel -> guardar_pedido_promociones($value2);
						}
					} else {
						$resp['result'] = $this -> comandasModel -> guardar_pedido_promociones($value2);
					}
				}
			}
		}
		
		echo json_encode($resp);
	}

///////////////// ******** ---- 			FIN guardar_promocion					------ ************ //////////////////

///////////////// ******** ---- 			vista_propinas						------ ************ //////////////////
//////// Carga la vista en la que se consultan las propinas
	// Como parametros puede revibir:
	 
	function vista_propinas($objeto) {
		$objeto['f_ini'] = date('Y-m-d') . ' 00:01';
		$objeto['f_fin'] = date('Y-m-d') . ' 23:59';
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
		
	// Consulta los metodos de pago
		$metodos_pago = $this -> comandasModel -> listar_metodos_pago($objeto);
		$metodos_pago = $metodos_pago['rows'];

	// Consulta los empleado sy los regresa en un array
		$empleados = $this -> comandasModel -> listar_empleados($objeto);

		require ('views/comandas/vista_propinas.php');
	}

///////////////// ******** ---- 			FIN vista_propinas					------ ************ //////////////////

///////////////// ******** ---- 				listar_propinas					------ ************ //////////////////
//////// Consulta las propinas y lo agrega a la div
	// Como parametros recibe:
		// f_ini -> fecha y hora de inicio
		// F_fin -> fecha y hora final
		// div -> div donde se cargara el contenido html
		// empleado -> ID del empleado
		// mesa -> ID de la emsa
		// sucursal -> ID de la sucursal
		// metodo_pago -> Metodo de pago

	function listar_propinas($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
	// Formatea la fecha y la hora
		$objeto['f_ini'] = str_replace('T', ' ', $objeto['f_ini']).' 00:01';
		$objeto['f_fin'] = str_replace('T', ' ', $objeto['f_fin']).' 23:59';

	// Formatea la sucursal, el almacen y los productos
		$objeto['empleado'] = implode(",", $objeto['empleado']);
		$objeto['mesa'] = implode(",", $objeto['mesa']);
		$objeto['metodo_pago'] = implode(",", $objeto['metodo_pago']);
		$objeto['sucursal'] = implode(",", $objeto['sucursal']);
		$objeto['via_contacto'] = implode(",", $objeto['via_contacto']);
		
	// Consulta las propinas y las regresa en un array
		$objeto['agrupar'] = 'pro.id_venta';
		$propinas = $this -> comandasModel -> listar_propinas($objeto);
		$propinas = $propinas['rows'];
		
	// carga la vista para listar las propinas
		require ('views/comandas/listar_propinas.php');
	}

///////////////// ******** ---- 			FIN listar_propinas						------ ************ //////////////////

///////////////// ******** ---- 				listar_complementos					------ ************ //////////////////
//////// Carga la vista de los complementos
	// Como parametros recibe:
		// pedido -> Pedido seleccionado
	 
	function listar_complementos($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
	// Consulta las sucursales y las regresa en un array
		$complementos = $this -> comandasModel -> listar_complementos($objeto);
		$complementos = $complementos['rows'];
		
	// Valida las imagenes
		foreach ($complementos as $key => $value) {
		/* Impuestos del producto
		============================================================================= */
		
			$precio = $value['precio'];
			$objeto['id'] = $value['id'];	
			
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

				// Precio actualizado
					$precio += $producto_impuesto;
					$precio = round($precio, 2);
				}
				
				$complementos[$key]['precio'] = $precio;
			}
				
		/* FIN Impuestos del producto
		============================================================================= */
			
		// Valida que exista la imagen
			if (!empty($value['imagen'])) {
				$src = '../pos/' . $value['imagen'];
				$complementos[$key]['imagen'] = (file_exists($src)) ? $src : '';
			} else {
				$complementos[$key]['imagen'] = '';
			}
		}
		$idioma = $this -> comandasModel -> get_idioma($objeto);
		require ('views/comandera/listar_complementos.php');
	}

///////////////// ******** ---- 			FIN listar_complementos					------ ************ //////////////////

///////////////// ******** ---- 				agregar_complemento					------ ************ //////////////////
//////// Agregar un complemento
	// Como parametros recibe:
		// complemento -> ID del producto 
		// pedido -> ID del pedido
	 
	function agregar_complemento($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		$objeto['id'] = $objeto['pedido'];

		$this -> comandasModel -> is_promocion($objeto['id'],$objeto['complemento']);

		$this -> comandasModel -> is_combo($objeto['id'],$objeto['complemento']);

		$id_complemento = $objeto['complemento'];
		$objeto['complemento'] = "	CASE WHEN 
											complementos IS NULL
										THEN
											".$objeto['complemento']."
									ELSE
										CONCAT(complementos, ',".$objeto['complemento']."')
									END";
		$resp = $this -> comandasModel -> actualizar_pedido($objeto,$id_complemento);
		
		echo json_encode($resp);
	}

///////////////// ******** ---- 			FIN listar_complementos					------ ************ //////////////////

///////////////// ******** ---- 			eliminar_complemento					------ ************ //////////////////
//////// Elimina el complemento del pedido
	// Como parametros recibe:
		// id_pedido -> ID del pedido
		// id_complemento -> ID del complemento,
		// coplementos -> String con los IDs de los complementos
	 
	function eliminar_complemento($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;		
		$objeto['id'] = $objeto['id_pedido'];
		$id_complemento = $objeto['id_complemento'];
	// Forma un String con los Ids de los productos restantes
		$complementos = '';
		foreach ($objeto['complementos'] as $key => $value) {
			if ($value['id'] != $objeto['id_complemento']) {
				$complementos .= $value['id']. ',';
			}
		}
		$complementos = substr($complementos, 0, -1);
	
	// Valida si se debe de vaciar el campo o no el complemento
		if (!empty($complementos)) {
			$objeto['complemento_string'] = $complementos;
		} else {
			$objeto['borrar_complemento'] = 1;
		}
		
		$resp = $this -> comandasModel -> actualizar_pedido($objeto,$id_complemento);
		
		echo json_encode($resp);
	}

///////////////// ******** ---- 			FIN eliminar_complemento				------ ************ //////////////////

///////////////// ******** ---- 				editar_empleado						------ ************ //////////////////
//////// Edita los datos del empleado
	// Como parametros recibe:
		// id -> ID del emppleado
		// mostrar_comanda -> 1 -> Se debe mostrar en la comanda

	function editar_empleado($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Consulta los empleados y los regresa en un array
		$resp['result'] = $this -> comandasModel -> editar_empleado($objeto);
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0;

		echo json_encode($resp);
	}

///////////////// ******** ---- 			FIN editar_empleado						------ ************ //////////////////

///////////////// ******** ---- 				imprimir_propina					------ ************ //////////////////
//////// Imprime el ticket de la propina
	// Como parametros recibe:
		// f_ini -> Fecha inicial
		// f_fin -> Fecha final
		// mesero -> Nombre del mesero
		// total_propina -> Total de la propina
		
	function imprimir_propina($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
	
	// Obtenemos el logo
		$logo = $this -> comandasModel -> logo($objeto);
		$objeto['logo'] = $logo['rows'][0]['logo'];
	// Valida el logo
		$src = '../../netwarelog/archivos/1/organizaciones/' . $logo['rows'][0]['logo'];
		$objeto['logo'] = (file_exists($src)) ? $src : '';
		
	//Consulta la organizacion
        $organizacion = $this -> comandasModel ->datos_organizacion();

    //Consulta datos de la sucursal
        $datos_sucursal =  $this -> comandasModel -> datos_sucursal();
		
	// Carga la vista
		require ('views/comandas/imprimir_propina.php');
	}
	
///////////////// ******** ---- 				FIN imprimir_propina				------ ************ //////////////////

// ch@
	function statusComanda(){
		$id_comanda = $_POST['id'];
		$result = $this -> comandasModel -> statusComanda($id_comanda);
		echo json_encode($result);
	}
	function statusPedidos(){
		$id_comanda = $_POST['id'];
		$result = $this -> comandasModel -> statusPedidos($id_comanda);
		echo json_encode($result);
	}
	function porcentaje_pre(){

		// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
		// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		print_r($objeto);
		exit();

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
	function pedidosDiv(){
		$id_comanda = $_POST['id_comanda'];
		$result = $this -> comandasModel -> pedidosDiv($id_comanda);
		echo json_encode($result);
	}

	function savenota(){
		$id_pedido = $_POST['id_pedido'];
		$nota = $_POST['nota'];
		$result = $this -> comandasModel -> savenota($id_pedido,$nota);
		echo json_encode($result);
	}

	function aplidaDesc($objeto){	

		$id_pedido = $_POST['idorder'];
		$tipo_desc = $_POST['tipo_desc'];
		$monto_desc = $_POST['monto_desc'];

		$result = $this -> comandasModel -> aplidaDesc($id_pedido,$tipo_desc,$monto_desc);
		echo json_encode($result);

	}

	function newtable(){
		$nombreMesa = $_POST['nombreMesa'];
		$idsuc = $this -> comandasModel -> getSuc($_SESSION['accelog_idempleado']);
		//$_SESSION['idsuc'] = $idsuc;
		$mesero = $_SESSION['mesero']['id'];
		$idarea = $_SESSION['area_princ']['id'];
		$result = $this -> comandasModel -> newtable($nombreMesa,$idsuc,$mesero,$idarea);
		echo json_encode($result);
	}

	function eliminaMesa(){
		$id_mesa = $_POST['id_mesa'];
		$idempleado = $_POST['idempleado'];
		$result = $this -> comandasModel -> eliminaMesa($id_mesa,$idempleado);
		echo json_encode($result);
	}

} // Fin clase
?>