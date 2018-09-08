<?php
require('common.php');
require("../webapp/modulos/restaurantes/models/comandas.php");
require("../webapp/modulos/restaurantes/models/configuracion.php");

class externo extends Common{
	public $comandasModel;
	public $configuracionModel;

	function __construct() {
		$this -> configuracionModel = new configuracionModel();
		$this -> comandasModel = new comandasModel();
	}

///////////////// ******** ---- 		vista_principal			------ ************ //////////////////
//////// Carga la vista principal
		// Como parametros recibe:
		
	function vista_principal($objeto){
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
	// Consulta los datos de la mesa en la DB
		$datos_mesa = $this -> comandasModel -> detalles_mesa($objeto);
		$datos_mesa = $datos_mesa['rows'][0];
		$organizacion = $this -> comandasModel ->datos_organizacion()[0];
		$datos_qr = $this -> comandasModel -> getImgCorreo();
		//print_r($datos_qr);

		require('views/externo/vista_principal.php');
	}

///////////////// ******** ---- 		FIN vista_principal		------ ************ //////////////////

///////////////// ******** ---- 		detalles_producto			------ ************ //////////////////
//////// Carga la vista principal
		// Como parametros recibe:
		
	function detalles_producto($objeto){
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		//print_r($objeto);
	// Consulta los datos de la mesa en la DB
		if(!empty($objeto['id'])){
			$detalles_producto = $this -> comandasModel -> detalles_producto($objeto);
			$detalles_producto = $detalles_producto['rows'][0];
		}
		
		if(!empty($detalles_producto) && !empty($detalles_producto['link']) || !empty($detalles_producto) && !empty($detalles_producto['resena']))
			require('views/externo/detalles_producto.php');
		else
			require('views/externo/detalles_producto_error.php');
	}

///////////////// ******** ---- 		FIN detalles_producto		------ ************ //////////////////

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
		
	// Cerramos la comanda y regresamos el resultado
		$comanda = $this -> comandasModel -> closeComanda($objeto);

		//print_r($objeto); exit();
	// Optenemos el logo
		$logo = $this -> comandasModel -> logo($objeto);
	
	// Valida el logo
		$src = '../webapp/netwarelog/archivos/1/organizaciones/' . $logo['rows'][0]['logo'];
		$comanda['logo'] = (file_exists($src)) ? $src : '';
		
	// Elimina la comanda de la mes aen la sesion
		session_start();
		$_SESSION['tables']['idmesa']['idcomanda'] = '';
		
	//Consulta los campos para mostrar en el tickect seleccionado en configuración
		$que_mostrar = $this -> comandasModel -> get_que_mostrar_ticket($objeto);

	//Consulta la organizacion
        $organizacion = $this -> comandasModel ->datos_organizacion();

    //Consulta datos de la sucursal
        $datos_sucursal =  $this -> comandasModel -> datos_sucursal($objeto['idmesa']);

 		require ('views/externo/cerrar_comanda_todo_junto.php');
	
	}

///////////////// ******** ----  			FIN cerrar_comanda				------ ************ //////////////////

///////////////// ******** ---- 			ver_menu			------ ************ //////////////////
//////// Imprime el menu
	// Como parametros puede recibir:

	function ver_menu($objeto) {
	// Consulta los datos del menu
		$objeto['orden'] = ' id DESC';
		$objeto['menu'] = $this -> configuracionModel -> listar_menus($objeto);
		$objeto['menu'] = $objeto['menu']['rows'][0];
	
	// Consulta los productos del menu
		$productos_menu = $this -> configuracionModel -> listar_productos_menu($objeto['menu']);
		$objeto['menu']['productos'] = $productos_menu['rows'];
		
		
		$array = $objeto['menu']['productos'];
		$groupkey = 'id_padre';
		
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
		$logo = $this -> comandasModel -> logo($objeto);
		$logo = $logo['rows'][0]['logo'];
		
	// Carga la vista de las configuracion
		require('views/menu/menu_'.$objeto['menu']['estilo'].'.php');
		
	}

///////////////// ******** ---- 				FIN ver_menu				------ ************ //////////////////

///////////////// ******** ---- 				llamar_mesero				------ ************ //////////////////
//////// Modifica el campo de la mesa para crear una alerta
	// Como parametros puede recibir:
		// id_mesa -> ID de la mesa

	function llamar_mesero($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
	// Actualiza los campos de la mesa
		$resp['result'] = $this -> comandasModel -> actualizar_mesa($objeto);

		echo json_encode($resp);
	}

///////////////// ******** ---- 			FIN llamar_mesero				------ ************ //////////////////

///////////////// ******** ---- 		save_pass			------ ************ //////////////////
//////// Cierra la comanda, separa las mesas(si existen), elimina la mesa(si es temporal), Actualiza el inventario.
	// Como parametros puede recibir:
		// $idComanda -> ID de la comanda
		// $bandera -> si existen o no productos extra u opcionales
		// $idmesa -> ID de la mesa
		// $tipo -> si es mesa temporal(para llevar, servicio a domicilio) o normal
		// $id_reservacion -> ID de la reservacion(si existe)

	function save_pass($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		if($objeto['pass'] != $objeto['confirm']){
			$data['status'] = 2;
		}
		else {
			$resp = $this -> comandasModel -> save_pass($objeto);
			if ($resp) {
				$data['status'] = 1;
			} else {
				$data['status'] = 2;
			}
		}

		echo json_encode($data);
	}

///////////////// ******** ---- 		FIN save_pass		------ ************ //////////////////

///////////////// ******** ---- 		ordenar			------ ************ //////////////////
//////// Cierra la comanda, separa las mesas(si existen), elimina la mesa(si es temporal), Actualiza el inventario.
	// Como parametros puede recibir:
		// $idComanda -> ID de la comanda
		// $bandera -> si existen o no productos extra u opcionales
		// $idmesa -> ID de la mesa
		// $tipo -> si es mesa temporal(para llevar, servicio a domicilio) o normal
		// $id_reservacion -> ID de la reservacion(si existe)

	function ordenar($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		if($objeto['pass'] == ''){
			$data['status'] = 2;
		}
		else {
			$resp = $this -> comandasModel -> ordenar($objeto);
			if($resp['password'] === $objeto['pass']){
				$data['status'] = 1;
			} else {
				$data['status'] = 2;
			} 
		}

		echo json_encode($data);
	}

///////////////// ******** ---- 		FIN ordenar		------ ************ //////////////////

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
		
		$objeto['mesero'] = $_SESSION['mesero']['usuario'];
		$objeto['id_mesero'] = $_SESSION['mesero']['id'];
		//print_r($objeto);

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
		
		require ('views/externo/vista_personas.php');
	}

///////////////// ******** ---- 			FIN vista_personas				------ ************ //////////////////

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
				$src = '../webapp/modulos/pos/' . $value['imagen'];
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

		require ('views/externo/'.$vista.'.php');
	}

///////////////// ******** ---- 		FIN buscar_productos		------ ************ //////////////////

///////////////// ******** ---- 				vista_comandera				------ ************ //////////////////
//////// Carga la vista de la comandera
	// Como parametros recibe:
		// div -> div donde se carga la vista de la comandera

	function vista_comandera($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		
		
	// Consulta los productos
		$products = $this -> comandasModel -> getProducts(0, 0, 0, 0, $objeto['sucursal']);
		
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
				$src = '../webapp/modulos/pos/' . $value['imagen'];
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
		$objeto2 = $objeto;
		$objeto2['id'] = '';
		$empleados = $this -> comandasModel -> listar_empleados($objeto2);
		
	// Obtiene las configuraciones
		$configuraciones = $this -> comandasModel -> listar_ajustes();
		$configuraciones = $configuraciones['rows'][0];
		
		//Consulta el idioma seleccionado en configuración
			require ('views/externo/vista_comandera.php');
	}

///////////////// ******** ---- 			FIN vista_comandera				------ ************ //////////////////

///////////////// ******** ---- 			detalles_producto_2				------ ************ //////////////////
//////// Consulta los detalles del producto, si tiene carga los opcionales, extras, etc. Si no agrega el producto
	// Como parametros recibe:
		// div -> div donde se carga la vista
		// persona -> ID de la persona
		// id_comanda -> ID de la comanda
		// id_producto -> ID del producto
		// departamento -> Departamento del producto
		// tipo -> Tipo de producto
		// Materiales -> 1 -> si tiene insumos, 0 -> si no

	function detalles_producto_2($objeto) {
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
			$objeto_json = json_encode($objeto);
			$objeto_json = str_replace('"', "'", $objeto_json);
				require ('views/externo/detalles_producto_2.php');	
		
		} else {
		// Formatea el contenido a json
			$objeto['btn'] = 'persona_'.$objeto['persona'];
			$objeto['f'] = 'guardar_pedido';
			$pedido = json_encode($objeto);
			$pedido = str_replace('"', "'", $pedido); 
			
			if($objeto['combo'] == 1){ ?>
				<script>
					if(!externo.datos_combo.grupos[<?php echo $objeto['grupo'] ?>].num_seleccionados){
						externo.datos_combo.grupos[<?php echo $objeto['grupo'] ?>].num_seleccionados = 0;
					}
					
					externo.datos_combo.grupos[<?php echo $objeto['grupo'] ?>].num_seleccionados ++;
					externo.seleccionar_pedido(<?php echo $pedido ?>);
					
					if(externo.datos_combo.grupos[<?php echo $objeto['grupo'] ?>].num_seleccionados >= <?php echo $objeto['cantidad_grupo'] ?>){
						$("#<?php echo $objeto['div'] ?>").html('<i class="fa fa-cutlery"></i> <b>Grupo completo</b>');
					}
				</script><?php
		// Guarda el pedido de la persona normalmente
			} else if($objeto['promocion'] == 1){ ?>
				<?php if($objeto['tipo_promocion'] == 1 || $objeto['tipo_promocion'] == 2 || $objeto['tipo_promocion'] == 4) {?>
					<script>
						if(!externo.datos_promocion.grupos['productos'][<?php echo $objeto['id_producto']?>].num_seleccionados){
							externo.datos_promocion.grupos['productos'][<?php echo $objeto['id_producto']?>].num_seleccionados = 0;
						}
						
						externo.datos_promocion.grupos['productos'][<?php echo $objeto['id_producto']?>].num_seleccionados ++;
						externo.seleccionar_pedido(<?php echo $pedido ?>);
						console.log(externo.datos_promocion);
						$("#div_promocion").html(externo.htmlPromo);
					</script>
				<?php } else if($objeto['tipo_promocion'] == 3 || $objeto['tipo_promocion'] == 5) { ?>
					<script>
						if(!externo.datos_promocion.grupos.<?php echo $objeto['grupo'] ?>.num_seleccionados){
							externo.datos_promocion.grupos.<?php echo $objeto['grupo'] ?>.num_seleccionados = 0;
						}
						
						externo.datos_promocion.grupos.<?php echo $objeto['grupo'] ?>.num_seleccionados ++;
						externo.seleccionar_pedido(<?php echo $pedido ?>);
						
						if(externo.datos_promocion.grupos.<?php echo $objeto['grupo'] ?>.num_seleccionados >= <?php echo $objeto['cantidad_grupo'] ?>){
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
					externo.guardar_pedido(<?php echo $pedido ?>);
				</script><?php
			}
		}
	}

///////////////// ******** ---- 		FIN detalles_producto_2			------ ************ //////////////////

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
		$pedidos = $this -> comandasModel -> getItemsPerson($objeto['persona'], $objeto['id_comanda'], $objeto['sucursal']);
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

		$this -> comandasModel ->act_total_com($objeto['id_comanda'], $total_precio);
	//Consulta el idioma seleccionado en configuración
		
			require ('views/externo/listar_pedidos_persona.php');
		
	}

///////////////// ******** ---- 		FIN listar_pedidos_persona			------ ************ //////////////////

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
					$src = '../webapp/modulos/pos/' . $v['imagen'];
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
			require ('views/externo/'.$vista.'.php');
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
		require ('views/externo/listar_productos_combo.php');
	}

///////////////// ******** ---- 		FIN listar_productos_combo			------ ************ //////////////////

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
				$src = '../webapp/modulos/pos/' . $value['imagen'];
				$complementos[$key]['imagen'] = (file_exists($src)) ? $src : '';
			} else {
				$complementos[$key]['imagen'] = '';
			}
		}
		$idioma = $this -> comandasModel -> get_idioma($objeto);
		require ('views/externo/listar_complementos.php');
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
		$objeto['complemento'] = "	CASE WHEN 
											complementos IS NULL
										THEN
											".$objeto['complemento']."
									ELSE
										CONCAT(complementos, ',".$objeto['complemento']."')
									END";
		$resp = $this -> comandasModel -> actualizar_pedido($objeto);
		
		echo json_encode($resp);
	}

///////////////// ******** ---- 			FIN listar_complementos					------ ************ //////////////////

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
					$src = '../webapp/modulos/pos/' . $v['imagen'];
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
			require ('views/externo/'.$vista.'.php');
		}
	}

///////////////// ******** ---- 			FIN listar_promociones				------ ************ //////////////////

///////////////// ******** ---- 			listar_productos_promociones			------ ************ //////////////////
//////// Carga la vista de los productos del combo
	// Como parametros recibe:
		// div -> Div donde se cargaron los combos
		// id_combo -> ID del combo
		
	function listar_productos_promociones($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
		session_start();
		$_SESSION['promociones'] = $objeto['promocion'];
		
	// Carga la vista
		$idioma = $this -> comandasModel -> get_idioma($objeto);
		require ('views/externo/listar_productos_promocion.php');
	}

///////////////// ******** ---- 		FIN listar_productos_promociones			------ ************ //////////////////

///////////////// ******** ---- 				guardar_promocion					------ ************ //////////////////
//////// Guarda el pedido del combo y los pedidos de sus productos
	// Como parametros recibe:
		
	function guardar_promocion($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
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
		require ('views/externo/listar_familias.php');
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
		require ('views/externo/listar_lineas.php');
	}
	
///////////////// ******** ---- 			FIN listar_lineas				------ ************ //////////////////

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
		$result = $this -> comandasModel -> process($objeto['id_comanda'], $objeto['sucursal']);
		
		echo json_encode($result);
	}

///////////////// ******** ----  				FIN pedir					------ ************ //////////////////

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
		
		$resp = $this -> comandasModel -> actualizar_pedido($objeto);
		
		echo json_encode($resp);
	}

///////////////// ******** ---- 			FIN eliminar_complemento				------ ************ //////////////////
} ?>