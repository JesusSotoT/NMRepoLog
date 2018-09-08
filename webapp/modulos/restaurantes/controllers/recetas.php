<?php
/**
 * @author Fer De La Cruz
 */
 
require('common.php');
require("models/recetas.php");

class recetas extends Common{
	public $recetasModel;

	function __construct(){
		$this->recetasModel = new recetasModel();
	}
	

///////////////// ******** ---- 		vista_recetas				------ ************ //////////////////
//////// Carga la vista en la que se consultan las recetas
		
	function vista_recetas($objeto){
	// Carga la vista de las recetas
		require('views/recetas/vista_recetas.php');
	}
			
///////////////// ******** ---- 		FIN	vista_recetas			------ ************ //////////////////

///////////////// ******** ---- 		vista_nueva			------ ************ //////////////////
//////// Consulta los productos, las recetas y las agrega a un div
	// Como parametros recibe:
		// div -> div donde se cargara el contenido html
		// btn -> boton del loader
		
	function vista_nueva($objeto){
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$panel = $objeto['panel'];

	// Consulta los insumos
		$objeto['tipo_producto'] = 3;
		$datos['insumos'] = $this -> recetasModel -> listar_insumos($objeto);
		$datos['insumos'] = $datos['insumos']['rows'];
		
	// Obtiene las unidades de medida
		$datos['unidades'] = $this -> recetasModel -> listar_unidades($objeto);
		$datos['unidades'] = $datos['unidades']['rows'];

	// Consulta los insumos preparados
		$objeto['tipo'] = 4;
		$datos['insumos_preparados'] = $this -> recetasModel -> listar($objeto);
		$datos['insumos_preparados'] = $datos['insumos_preparados']['rows'];

	// Consulta los servicios
		$objeto['tipo_producto'] = 2;
		$datos['servicios'] = $this -> recetasModel -> listar_servicios($objeto);
		$datos['servicios'] = $datos['servicios']['rows'];
	// Inicializa el array de los insumos agregados
		session_start();
		$_SESSION['insumos_agregados'] = '';
		
	// Carga la vista de las recetas
		require('views/recetas/nueva.php');
	}
			
///////////////// ******** ---- 	FIN	vista_nueva			------ ************ //////////////////

	function subeLayout()
    {
        $directorio = "importacion/";
        if (isset($_FILES["layout"])) 
        {
                if($_FILES['layout']['name'])
                {
                    if (move_uploaded_file($_FILES['layout']['tmp_name'], $directorio.basename("recetas_temp.xls" ) )) 
                    {
                        include($directorio."import_recetas.php");
                    } 
                    else 
                    {
                        echo "No se subio el archivo de Recetas <br/>";
                    }
                }
        }
    }

///////////////// ******** ---- 		guardar				------ ************ //////////////////
//////// Manda llamar a la funcion que Guarda la receta o insumo preparado en la BD
	// Como parametros recibe:
		// nombre -> nombre de la receta o insumo preparado
		// codigo -> codigo de la receta o insumo preparado
		// tipo -> 1(receta), 2(insumo preparado)
		// des -> comentarios sobre la receta o insumo preparado
		// precio_venta -> precio de venta
		// margen_ganancia -> margen de ganancia

	function guardar($objeto) {

	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
	
	// Guarda los ids de los insumos
		session_start();
		$objeto['ids'] = '';
		$objeto['ids_preparados'] = '';
		$objeto['costo_receta'] = 0;

		
		foreach ($_SESSION['insumos_agregados']['insumos_preparados'] as $key => $value) {
			if ($value['cantidad'] > 0) {
				$objeto['ids_preparados'] .= $key . ',';

				foreach ($value['select'] as $k => $v) { // normales (precio base para recerta)
					if($k == 0 and $v == 0){
						$objeto['normales'] .= $key . ',';
						$objeto['costo_receta'] += $value['sub_total'];
					}
				}

			}
		}
		
		foreach ($_SESSION['insumos_agregados']['insumos'] as $key => $value) {
			if ($value['cantidad'] > 0) {
				$objeto['ids'] .= $key . ',';
				$objeto['proveedores_insumos'] .= $value['proveedor_select'] . ',';

				foreach ($value['select'] as $k => $v) { // normales (precio base para recerta)
					if($k == 0 and $v == 0){
						$objeto['normales'] .= $key . ',';
						$objeto['costo_receta'] += $value['sub_total'];
					}
				}
				
			}
		}

	// Valida que existan insumos o insumos preparados
		if (!empty($objeto['ids'])||!empty($objeto['ids_preparados'])) {
			$objeto['ids'] = substr($objeto['ids'], 0, -1);
			$objeto['ids_preparados'] = substr($objeto['ids_preparados'], 0, -1);
			$objeto['proveedores_insumos'] = substr($objeto['proveedores_insumos'], 0, -1);

			$objeto['normales'] = substr($objeto['normales'], 0, -1);
						
			
		// Consulta si existe un producto con el mismo nombre o codigo
			$coincidencia = $this -> recetasModel -> listar_insumos($objeto);
			
		// Valida que no exista el producto
			if ($coincidencia['total'] < 1) {
				$objeto['margen_ganancia'] = (empty($objeto['margen_ganancia'])) ? 0 : $objeto['margen_ganancia'];
				
		// llama a la funcion que inserta la receta en la tabla de productos y obtiene el ID de la insercion
				$objeto['id_receta'] = $this -> recetasModel -> guardar_producto($objeto);
			
		// llama a la funcion que inserta la receta en la BD y obtiene el ID de la insercion
				$resp['result'] = $this -> recetasModel -> guardar_receta($objeto);
	
		// Guarda los insumos preparados en la BD
				foreach ($_SESSION['insumos_agregados']['insumos_preparados'] as $key => $value) {
					$value['id_receta'] = $objeto['id_receta'];
					$value['tipo'] = $objeto['tipo'];
					$resp['insumos'][$key] = $this -> recetasModel -> guardar_insumo($value);
				}

				$objeto['margen_ganancia'] = (empty($objeto['margen_ganancia'])) ? 0 : $objeto['margen_ganancia'];
				
		// Guarda los insumos en la BD
				foreach ($_SESSION['insumos_agregados']['insumos'] as $key => $value) {
					$value['id_receta'] = $objeto['id_receta'];
					$value['tipo']=$objeto['tipo'];
					$resp['insumos'][$key] = $this -> recetasModel -> guardar_insumo($value);
				}
			
		// 1 -> Todo bien :)
		// 2 -> Fallo la consulta :(
				$resp['status'] = (!empty($resp['result'])) ? 1 : 0;
			} else {
		// El prodcuto ya existe
				$resp['status'] = 3;
			}
		} else {
		// Sin insumos
			$resp['status'] = 2;
		}
	
		echo json_encode($resp);
	}
///////////////// ******** ---- 		FIN	guardar				------ ************ //////////////////

///////////////// ******** ---- 		agregar_insumo			------ ************ //////////////////
//////// Agrega un insumo al array de los insumos agregados y carga la vista donde aparecen
	// Como parametros recibe:
		// idProducto -> ID del insumo
		// div -> ID de la div donde se cargara la vista
		// idunidad -> ID de la unidad
		// idunidadCompra -> ID de la unidad de compra
		// nombre -> nombre del insumo
		// unidad -> nombre de la unidad
		
	function agregar_insumo($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto=(empty($objeto))?$_REQUEST:$objeto;

		
		session_start();
	// Valida si es prearado o no
		if ($objeto['preparado']==1) {

		// Si no existe el producto lo agrega al array,  si existe lo elimina
			if (!empty($_SESSION['insumos_agregados']['insumos_preparados'][$objeto['id']])) {
				unset($_SESSION['insumos_agregados']['insumos_preparados'][$objeto['id']]);
			}else{
				$_SESSION['insumos_agregados']['insumos_preparados'][$objeto['id']]=$objeto;
				//echo json_encode($objeto);
				//echo 'PREPARADO';
			}
		}elseif($objeto['preparado']==2) { // servicio

			if (!empty($_SESSION['insumos_agregados']['insumos_preparados'][$objeto['id']])) {
				unset($_SESSION['insumos_agregados']['insumos_preparados'][$objeto['id']]);
			}else{
				$_SESSION['insumos_agregados']['insumos_preparados'][$objeto['id']]=$objeto;
				//echo json_encode($objeto);
				//echo 'INSUMO';
			}
		
		}else{
			// Si no existe el producto lo agrega al array,  si existe lo elimina
			if (!empty($_SESSION['insumos_agregados']['insumos'][$objeto['id']])) {
				unset($_SESSION['insumos_agregados']['insumos'][$objeto['id']]);
			}else{
				$_SESSION['insumos_agregados']['insumos'][$objeto['id']]=$objeto;
				//echo json_encode($objeto);
				//echo 'INSUMO';
			}
		}
		//print_r($_SESSION['insumos_agregados']['insumos']);

		if ($objeto['index'] == $objeto['length']) {
			require('views/recetas/listar_insumos_agregados.php');
		} else {
			$resp['status'] = 1;
			$resp['session'] = $_SESSION['insumos_agregados']['insumos'];
			echo json_encode($resp);
		}
	// carga la vista para listar las reservaciones
		
	}

 ///////////////// ******** ---- 	FIN agregar_insumo			------ ************ /////////////////

 
 ////////////////// ******** ---- 		calcular_precio			------ ************ //////////////////
//////// Calcula el sub total del insumo, el total de la receta,
	// Como parametros recibe:
		// id -> ID del insumo
		// cantidad -> cantidad del insumo

	function calcular_precio($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		session_start();
		$_SESSION['insumos_agregados']['total'] = 0;

	// Valida la cantidad
		if (empty($objeto['cantidad'])) {
			$objeto['cantidad'] = 0;
		}

		$tipo = ($objeto['preparado'] == 1) ? 'insumos_preparados' : 'insumos';

		$_SESSION['insumos_agregados'][$tipo][$objeto['id']]['cantidad'] = $objeto['cantidad'];
		$_SESSION['insumos_agregados'][$tipo][$objeto['id']]['proveedor_select'] = $objeto['id_pro'];
		$_SESSION['insumos_agregados'][$tipo][$objeto['id']]['proveedor_select_costo'] = $objeto['sub_total']; // costo de la receta
		
		$id_unidad = $_SESSION['insumos_agregados'][$tipo][$objeto['id']]['id_unidad'];
		$unidad_compra = $_SESSION['insumos_agregados'][$tipo][$objeto['id']]['unidad_compra'];
		
		if ($id_unidad == $unidad_compra) {
			//print_r("if");
		// Calculamos el sub total del insumo
			if($objeto['id_pro'] == -1 ||  $objeto['preparado'] == 1){
				$sub_total = $_SESSION['insumos_agregados'][$tipo][$objeto['id']]['costo'] * $objeto['cantidad'];
				$_SESSION['insumos_agregados'][$tipo][$objeto['id']]['sub_total'] = $sub_total;
			} else {
				$ids_proveedor = explode( ',', $_SESSION['insumos_agregados'][$tipo][$objeto['id']]['ids_proveedor']);
				$costos = explode( ',', $_SESSION['insumos_agregados'][$tipo][$objeto['id']]['costos']);
				//print_r($costos);
				foreach ($ids_proveedor as $key => $value) {
					if ($value == $objeto['id_pro']) {
						$sub_total = $costos[$key] * $objeto['cantidad'];
						$_SESSION['insumos_agregados'][$tipo][$objeto['id']]['sub_total'] = $sub_total;
					}
				}
			}
		} else {
			//print_r("else");
		// - -- - -- - - --	-	-		**		 NOTA		**			- - - - - -- - - -- - 	//

		//** Dividimos el valor de compra entre el de venta para sacar la conversion
		// Ejem.
		// Kilo -> 1'000,000   // El valor de un kilo son 1'000,000 miligramos
		// Gramo -> 1,000   // El valor de un kilo son 1,000 miligramos

		// Para calcular la diferencia en miligramos dividimos  el valor de compra entre el de venta

		// 1000000/1000=1000	(kilo/gramo es igual a 1000 miligramos)

		// - -- - -- - - --	-	-		**		FIN NOTA		**			- - - - - -- - - -- - 	//
			if($objeto['id_pro'] == -1 ||  $objeto['preparado'] == 1){
			// Obtiene la conversion de la unidad de venta
				$objeto['unidad'] = $id_unidad;
				$conversion = $this -> recetasModel -> listar_conversion($objeto);
				$valor_venta = $conversion['rows'][0]['conversion'];
			
			// Obtiene la conversion de la unidad de compra
				$objeto['unidad'] = $unidad_compra;
				$conversion = $this -> recetasModel -> listar_conversion($objeto);
				$valor_compra = $conversion['rows'][0]['conversion'];

			// Calculamos el equivalente
				if ($unidad_compra == 21) {
					$sub_total = ($_SESSION['insumos_agregados'][$tipo][$objeto['id']]['costo'] / $valor_compra) * $objeto['cantidad'];
					$_SESSION['insumos_agregados'][$tipo][$objeto['id']]['sub_total'] = $sub_total;

			// Actualiza el total de la receta
					$_SESSION['insumos_agregados']['total'] += $sub_total;
				} else {
			// Calculamos el equivalente de la conversion
					$conversion = $valor_compra / $valor_venta;
					$sub_total = ($_SESSION['insumos_agregados'][$tipo][$objeto['id']]['costo'] / $conversion) * $objeto['cantidad'];
					$_SESSION['insumos_agregados'][$tipo][$objeto['id']]['sub_total'] = $sub_total;
				}
			} else {
				// Obtiene la conversion de la unidad de venta
				$objeto['unidad'] = $id_unidad;
				$conversion = $this -> recetasModel -> listar_conversion($objeto);
				$valor_venta = $conversion['rows'][0]['conversion'];
			
			// Obtiene la conversion de la unidad de compra
				$objeto['unidad'] = $unidad_compra;
				$conversion = $this -> recetasModel -> listar_conversion($objeto);
				$valor_compra = $conversion['rows'][0]['conversion'];

			// Calculamos el equivalente
				if ($unidad_compra == 21) {
					$ids_proveedor = explode( ',', $_SESSION['insumos_agregados'][$tipo][$objeto['id']]['ids_proveedor']);
					$costos = explode( ',', $_SESSION['insumos_agregados'][$tipo][$objeto['id']]['costos']);
					foreach ($ids_proveedor as $key => $value) {
						if ($value == $objeto['id_pro']) {
							$sub_total = ($costos[$key] / $valor_compra) * $objeto['cantidad'];
							$_SESSION['insumos_agregados'][$tipo][$objeto['id']]['sub_total'] = $sub_total;
						}
					}
					

			// Actualiza el total de la receta
					$_SESSION['insumos_agregados']['total'] += $sub_total;
				} else {
			// Calculamos el equivalente de la conversion
					$conversion = $valor_compra / $valor_venta;
					$ids_proveedor = explode( ',', $_SESSION['insumos_agregados'][$tipo][$objeto['id']]['ids_proveedor']);
					$costos = explode( ',', $_SESSION['insumos_agregados'][$tipo][$objeto['id']]['costos']);
					foreach ($ids_proveedor as $key => $value) {
						if ($value == $objeto['id_pro']) {
							$sub_total = ($costos[$key] / $conversion) * $objeto['cantidad'];
							$_SESSION['insumos_agregados'][$tipo][$objeto['id']]['sub_total'] = $sub_total;
						}
					}
					
				}
			}
		}
		
		echo json_encode($_SESSION['insumos_agregados']);
	}

///////////////// ******** ---- 		FIN	calcular_precio				------ ************ //////////////////

///////////////// ******** ---- 			costear					------ ************ //////////////////
//////// Valida si se debe de costear o no ese insumo
	// Como parametros recibe:
		// id -> ID del insumo
		// check -> Valor del check

	function costear($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

		session_start();
		$tipo = ($objeto['preparado'] == 1) ? 'insumos_preparados' : 'insumos';
		
		if($objeto['check'] == "true"){
			$_SESSION['insumos_agregados'][$tipo][$objeto['id']]['costear'] = 1;
		}else{
			$_SESSION['insumos_agregados'][$tipo][$objeto['id']]['costear'] = 2;
		}
		
		echo json_encode($_SESSION['insumos_agregados']);
	}

///////////////// ******** ---- 			FIN costear					------ ************ //////////////////

///////////////// ******** ---- 		guardar_opcionales				------ ************ //////////////////
//////// Guarda los opcionales del insumo
	// Como parametros recibe:
		// id -> ID del insumo
		// opcionales -> cadena con los IDS de los opcionales
		
	function guardar_opcionales($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto=(empty($objeto))?$_REQUEST:$objeto;
		
		session_start();
		
	// Se utiliza para cargar el select con los opcionales
		if ($objeto['preparado']==1) {
			$_SESSION['insumos_agregados']['insumos_preparados'][$objeto['id']]['select']=$objeto['opcionales'];
		}elseif($objeto['preparado']==2) {
			$_SESSION['insumos_agregados']['insumos_preparados'][$objeto['id']]['select']=$objeto['opcionales'];
		}else{
			$_SESSION['insumos_agregados']['insumos'][$objeto['id']]['select']=$objeto['opcionales'];
		}
		
	// Junta los ids de los opcionales en una cadena
		foreach ($objeto['opcionales'] as $key => $value) {
			$opcionales.=$value.',';
		}
	
	// Agrega los opcionales al insumo
		$opcionales=substr($opcionales, 0,-1);
		if ($objeto['preparado']==1) {
			$_SESSION['insumos_agregados']['insumos_preparados'][$objeto['id']]['opcionales']=$opcionales;
		}elseif($objeto['preparado']==2) {
			$_SESSION['insumos_agregados']['insumos_preparados'][$objeto['id']]['opcionales']=$opcionales;
		}else{
			$_SESSION['insumos_agregados']['insumos'][$objeto['id']]['opcionales']=$opcionales;
		}
		
		$resp['status'] = (!$opcionales) ? 1 : 0 ;
		$resp['insumos'] =$_SESSION['insumos_agregados'];
		
		echo json_encode($resp);
	}

///////////////// ******** ---- 	FIN guardar_opcionales		------ ************ //////////////////

///////////////// ******** ---- 		vista_copiar	------ ************ //////////////////
//////// Consulta las recetas y los insumos preparados y los carga en la div
	// Como parametros recibe:
		// div -> div donde se cargara el contenido html
		// btn -> boton del loader
		
	function vista_copiar($objeto){
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
 		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$panel = $objeto['panel'];

	// Consulta las recetas
		$objeto['orden'] = 'p.tipo_producto ASC';
		$recetas = $this -> recetasModel -> listar($objeto);
		$recetas = $recetas['rows'];

		foreach ($recetas as $key => $value) {
		// Optiene los insumos normales y los agrega al array
			if (!empty($value['insumos'])) {
				$objeto['ids'] = $value['insumos'];
				$objeto['id_receta'] = $value['idProducto'];
				$value['insumos'] = $this -> recetasModel -> listar_materiales($objeto);
				$value['insumos'] = $value['insumos']['rows'];
			}

		// Optiene los insumos preparados y los agrega al array
			if (!empty($value['insumos_preparados'])) {
				$objeto['ids'] = $value['insumos_preparados'];
				$objeto['id_receta'] = $value['idProducto'];
				$value['insumos_preparados'] = $this -> recetasModel -> listar_materiales($objeto);
				$value['insumos_preparados'] = $value['insumos_preparados']['rows'];
			}

		// Agrega el elemento al array
			$datos[$value['idProducto']] = $value;
		}
		
	// Inicializa el array de los insumos agregados
		session_start();
		$_SESSION['insumos_agregados'] = '';
		
	// Carga la vista de las recetas
		require('views/recetas/copiar.php');
	}
			
///////////////// ******** ---- 	FIN	vista_copiar		------ ************ //////////////////

///////////////// ******** ---- 		vista_editar		------ ************ //////////////////
//////// Consulta las recetas y los insumos preparados y los carga en la div
	// Como parametros recibe:
		// div -> div donde se cargara el contenido html
		// btn -> boton del loader
		
	function vista_editar($objeto){
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
 		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		$panel = $objeto['panel'];

	// Consulta las recetas
		$objeto['orden'] = 'p.tipo_producto ASC';
		$recetas = $this -> recetasModel -> listar($objeto);
		$recetas = $recetas['rows'];

		foreach ($recetas as $key => $value) {
		// Optiene los insumos normales y los agrega al array
			if (!empty($value['insumos'])) {
				$objeto['ids'] = $value['insumos'];
				$objeto['id_receta'] = $value['idProducto'];
				$value['insumos'] = $this -> recetasModel -> listar_materiales($objeto);
				$value['insumos'] = $value['insumos']['rows'];
			}

		// Optiene los insumos preparados y los agrega al array
			if (!empty($value['insumos_preparados'])) {
				$objeto['ids'] = $value['insumos_preparados'];
				$objeto['id_receta'] = $value['idProducto'];
				$value['insumos_preparados'] = $this -> recetasModel -> listar_materiales($objeto);
				$value['insumos_preparados'] = $value['insumos_preparados']['rows'];
			}

		// Agrega el elemento al array
			$datos[$value['idProducto']] = $value;
		}
		
	// Inicializa el array de los insumos agregados
		session_start();
		$_SESSION['insumos_agregados'] = '';	
		
	// Carga la vista de las recetas
		require('views/recetas/editar.php');
	}
			
///////////////// ******** ---- 	FIN	vista_editar		------ ************ //////////////////

///////////////// ******** ---- 		actualizar			------ ************ //////////////////
//////// Manda llamar a la funcion que actualiza la receta o insumo preparado en la BD
	// Como parametros recibe:
		// nombre -> nombre de la receta o insumo preparado
		// codigo -> codigo de la receta o insumo preparado
		// tipo -> 1(receta), 2(insumo preparado)
		// des -> comentarios sobre la receta o insumo preparado
		// precio_venta -> precio de venta
		// margen_ganancia -> margen de ganancia

	function actualizar($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
	// Guarda los ids de los insumos
		session_start();
		$objeto['ids'] = '';
		$objeto['ids_preparados'] = '';
		$objeto['costo_receta'] =0;
	
	// Forma una cadena con los IDs de los insumos preparados
		foreach ($_SESSION['insumos_agregados']['insumos_preparados'] as $key => $value) {
			if ($value['cantidad'] > 0) {
				$objeto['ids_preparados'] .= $key . ',';

				foreach ($value['select'] as $k => $v) { // normales (precio base para recerta)
					if($k == 0 and $v == 0){
						$objeto['normales'] .= $key . ',';
						$objeto['costo_receta'] += $value['sub_total'];
					}
				}
			}
		}
		
	// Forma una cadena con los IDs de los insumos normales
		foreach ($_SESSION['insumos_agregados']['insumos'] as $key => $value) {
			if ($value['cantidad'] > 0) {
				$objeto['ids'] .= $key . ',';
				$objeto['proveedores_insumos'] .= $value['proveedor_select'] . ',';

				foreach ($value['select'] as $k => $v) { // normales (precio base para recerta)
					if($k == 0 and $v == 0){
						$objeto['normales'] .= $key . ',';
						$objeto['costo_receta'] += $value['sub_total'];
					}
				}
			}
		}
	
	// Valida que existan insumos o insumos preparados
		if (!empty($objeto['ids']) || !empty($objeto['ids_preparados'])) {
		// Formatea las cadenas de ID´s			
			$objeto['ids'] = substr($objeto['ids'], 0, -1);
			$objeto['ids_preparados'] = substr($objeto['ids_preparados'], 0, -1);
			$objeto['proveedores_insumos'] = substr($objeto['proveedores_insumos'], 0, -1);

			$objeto['normales'] = substr($objeto['normales'], 0, -1);

		// Valida el margen de ganancia
			$objeto['margen_ganancia'] = (empty($objeto['margen_ganancia'])) ? 0 : $objeto['margen_ganancia'];
		
		// Valida si existe la receta o no
			$valida = $this -> recetasModel -> validar($objeto);
		
		// Si no existe la receta agrega una nueva, si existe la actualiza
			if ($valida['total']<1) {
		// llama a la funcion que inserta la receta en la BD y obtiene el ID de la insercion
				$resp['result'] = $this -> recetasModel -> guardar_receta($objeto);
			} else {
		// llama a la funcion que actualiza la receta en la BD
				$resp['result'] = $this -> recetasModel -> actualizar_receta($objeto);
			}
			
		// llama a la funcion que actualiza el producto en la tabla de productos
			$resp['actualiza_producto'] = $this -> recetasModel -> actualizar_producto($objeto);
			
		//  Elimina los insumos de las receta de la BD
			$resp['eliminar_insumos'] = $this -> recetasModel -> eliminar_insumos($objeto);
			
		// Guarda los insumos preparados en la BD
			foreach ($_SESSION['insumos_agregados']['insumos_preparados'] as $key => $value) {
				$value['id_receta'] = $objeto['id_receta'];
				$value['tipo']=$objeto['tipo'];
				$resp['insumos_preparados'][$key] = $this -> recetasModel -> guardar_insumo($value);
			}
				
		// Guarda los insumos en la BD
			foreach ($_SESSION['insumos_agregados']['insumos'] as $key => $value) {
			$value['id_receta'] = $objeto['id_receta'];
				$value['tipo']=$objeto['tipo'];
				$resp['insumos'][$key] = $this -> recetasModel -> guardar_insumo($value);
			}
	
		// 1 -> Todo bien :)
		// 2 -> Fallo la consulta :(
			$resp['status'] = (!empty($resp['result'])) ? 1 : 0;
		} else {
		// Sin insumos
			$resp['status'] = 2;
		}
	
		echo json_encode($resp);
	}
///////////////// ******** ---- 		FIN	actualizar				------ ************ //////////////////

///////////////// ******** ---- 		vista_eliminar		------ ************ //////////////////
//////// Consulta las recetas y los insumos preparados y los carga en la div
	// Como parametros recibe:
		// div -> div donde se cargara el contenido html
		// btn -> boton del loader
		
	function vista_eliminar($objeto){
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto=(empty($objeto))?$_REQUEST:$objeto;
		$panel=$objeto['panel'];
		
	// Consulta las recetas
		$objeto['orden']='	p.tipo_producto ASC';
		$recetas=$this->recetasModel->listar($objeto);
		$recetas=$recetas['rows'];
		
		foreach ($recetas as $key => $value) {
		// Optiene los insumos normales y ls agrega al array
			if (!empty($value['insumos'])) {
				$objeto['ids']=$value['insumos'];
				$objeto['id_receta']=$value['idProducto'];
				$value['insumos']=$this->recetasModel->listar_materiales($objeto);
				$value['insumos']=$value['insumos']['rows'];
			}
		
		// Optiene los insumos preparados y los agrega al array
			if (!empty($value['insumos_preparados'])) {
				$objeto['ids']=$value['insumos_preparados'];
				$value['insumos_preparados']=$this->recetasModel->listar_materiales($objeto);
				$value['insumos_preparados']=$value['insumos_preparados']['rows'];
			}
		
		// Agrega el elemento al array
			$datos[$value['idProducto']]=$value;
		}
		
	// Inicializa el array de los insumos agregados
		session_start();
		$_SESSION['insumos_agregados']='';	
		
	// Carga la vista de las recetas
		require('views/recetas/eliminar.php');
	}
			
///////////////// ******** ---- 		FIN	vista_eliminar			------ ************ //////////////////

///////////////// ******** ---- 				eliminar			------ ************ //////////////////
//////// Elimina una receta o insumo preparado, el producto y sus materiales
	// Como parametros recibe:
		// id -> ID de la receta o insumo preparado
		
	function eliminar($objeto){
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto=(empty($objeto))?$_REQUEST:$objeto;
		
	// Elimina la receta, el producto y sus materiales
		$resp['result']=$this->recetasModel->eliminar($objeto);
		
	// 1 -> Todo bien :)
	// 0 -> Fallo la consulta :(
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0;
		
		echo json_encode($resp);
	}
			
///////////////// ******** ---- 			FIN	eliminar			------ ************ //////////////////

///////////////// ******** ---- 		restaurar_precio			------ ************ //////////////////
//////// Busca el precio actual del producto y lo agrega al campo precio_venta
	// Como parametros recibe:
		// id -> ID de la receta o insumo preparado
		// btn -> boton del loader
		
	function restaurar_precio($objeto){
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto=(empty($objeto))?$_REQUEST:$objeto;
		
	// Elimina la receta, el producto y sus materiales
		$resp['result'] = $this->recetasModel->restaurar_precio($objeto);
		$resp['result'] = $resp['result']['rows'][0]['precio'];
		
	// 1 -> Todo bien :)
	// 0 -> Fallo la consulta :(
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0;
		
		echo json_encode($resp);
	}
			
///////////////// ******** ---- 			FIN	restaurar_precio		------ ************ //////////////////

///////////////// ******** ---- 			vista_preparacion			------ ************ //////////////////
//////// Carga la vista de los insumos preparados para producirlos
	// Como parametro puede recibir:

	function vista_preparacion($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Consulta los insumos preparados
		$objeto['tipo'] = 4;
		$insumos_preparados = $this -> recetasModel -> listar($objeto);
		$insumos_preparados = $insumos_preparados['rows'];
		
		foreach ($insumos_preparados as $key => $value) {
		// Optiene los insumos y los agrega al array
			if (!empty($value['insumos'])) {
				$objeto['ids'] = $value['insumos'];
				$objeto['id_receta'] = $value['idProducto'];
				$value['insumos'] = $this -> recetasModel -> listar_materiales($objeto);
				$value['insumos'] = $value['insumos']['rows'];
			}
			
			$insumos_preparados[$key] = $value;
		}
		
	// Carga la vista de los insumos preparados
		require ('views/recetas/vista_preparacion.php');
	}

///////////////// ******** ---- 			FIN vista_preparacion		------ ************ //////////////////

///////////////// ******** ---- 			preparar_insumo				------ ************ //////////////////
//////// Descuenta del inventario los insumos y prepara un insumo preparado
	// Como parametros recibe:
		// id -> ID del preparado
		// cantidad -> Cantidad que se debe preparar del insumo
		
	function preparar_insumo($objeto){
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		date_default_timezone_set('America/Mexico_City');
		$objeto['f_ini'] = date('Y-m-d H:i:s');
		
	// Guarda la preparacion
		$resp['id_preparacion'] = $this->recetasModel->preparar_insumo($objeto);
	
	// Descuenta los insumos del inventario
		foreach ($objeto['insumos'] as $key => $value) {
			$value['id_preparacion'] = $resp['id_preparacion'];
			$value['id_producto'] = $objeto['id_producto'];
			$value['importe'] = $value['costo'] * $objeto['cantidad'];
			$value['cantidad'] = $value['cantidad'] * $objeto['cantidad'];
			$value['fecha'] = $objeto['f_ini'];
			
			$resp['insumos'] = $this->recetasModel->descontar_insumo($value);
		}
		
	// 1 -> Todo bien :)
	// 0 -> Fallo la consulta :(
		$resp['status'] = (!empty($resp['insumos'])) ? 1 : 0;
		
		echo json_encode($resp);
	}

///////////////// ******** ---- 			FIN preparar_insumo			------ ************ //////////////////

///////////////// ******** ---- 				terminar_insumo			------ ************ //////////////////
//////// Actualiza el inventario y el insumo preparado
	// Como parametros recibe:
		// id -> ID del insumo preparado
		// id_preparacion -> ID de la preparacion
		// cantidad -> Cantidad que se debe preparar del insumo
		
	function terminar_insumo($objeto){
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		date_default_timezone_set('America/Mexico_City');
		$objeto['f_fin'] = date('Y-m-d H:i:s');
		
		
		$id_unidad = $objeto['unidad_venta'];
		$unidad_compra = $objeto['unidad_compra'];
		
		if ($id_unidad == $unidad_compra) {
		// Calculamos el importe
			$objeto['importe'] = $objeto['precio'] * $objeto['cantidad'];
		} else {
		// - -- - -- - - --	-	-		**		 NOTA		**			- - - - - -- - - -- - 	//

		//** Dividimos el valor de compra entre el de venta para sacar la conversion
		// Ejem.
		// Kilo -> 1'000,000   // El valor de un kilo son 1'000,000 miligramos
		// Gramo -> 1,000   // El valor de un kilo son 1,000 miligramos

		// Para calcular la diferencia en miligramos dividimos  el valor de compra entre el de venta

		// 1000000/1000=1000	(kilo/gramo es igual a 1000 miligramos)

		// - -- - -- - - --	-	-		**		FIN NOTA		**			- - - - - -- - - -- - 	//

		// Obtiene la conversion de la unidad de venta
			$objeto['unidad'] = $id_unidad;
			$conversion = $this -> recetasModel -> listar_conversion($objeto);
			$valor_venta = $conversion['rows'][0]['conversion'];
		
		// Obtiene la conversion de la unidad de compra
			$objeto['unidad'] = $unidad_compra;
			$conversion = $this -> recetasModel -> listar_conversion($objeto);
			$valor_compra = $conversion['rows'][0]['conversion'];

		// Calculamos el equivalente
			if ($unidad_compra == 21) {
				$objeto['precio'] = $objeto['precio'] / $valor_compra;
				$objeto['importe'] = $objeto['precio'] * $objeto['cantidad'];
				$objeto['cantidad'] = $objeto['cantidad'] * $valor_compra;
			} else {
		// Calculamos el equivalente de la conversion
				$conversion = $valor_compra / $valor_venta;
				$objeto['precio'] = $objeto['precio'] / $conversion;
				$objeto['importe'] = $objeto['precio'] * $objeto['cantidad'];
				$objeto['cantidad'] = $objeto['cantidad'] * $conversion;
			}
		}
			
	// Actualiza el inventario y el insumo preparado
		$resp['result'] = $this->recetasModel->terminar_insumo($objeto);
		
	// 1 -> Todo bien :)
	// 0 -> Fallo la consulta :(
		$resp['status'] = (!empty($resp['result'])) ? 1 : 0;
		
		echo json_encode($resp);
	}
			
///////////////// ******** ---- 			FIN	terminar_insumo			------ ************ //////////////////

///////////////// ******** ---- 		vista_control_insumos			------ ************ //////////////////
//////// Carga la vista del control de insumos
	// Como parametros recibe:
		// div -> div donde se cargara el contenido html
		// btn -> boton del loader
		
	function vista_control_insumos($objeto){
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
		
	// Consulta las sucursales y las regresa en un array
		$sucursales = $this -> recetasModel -> listar_sucursales($objeto);
		$sucursales = $sucursales['rows'];
		
	// Consulta los almacenes y los regresa en un array
		$almacenes = $this -> recetasModel -> listar_almacenes($objeto);
		$almacenes = $almacenes['rows'];
		
	// Consulta los insumos
		$objeto['tipo_producto'] = 3;
		$datos['insumos_normales'] = $this -> recetasModel -> listar_insumos($objeto);
		$datos['insumos_normales'] = $datos['insumos_normales']['rows'];
		
	// Consulta los insumos preparados
		$objeto['tipo'] = 4;
		$datos['insumos_preparados'] = $this -> recetasModel -> listar($objeto);
		$datos['insumos_preparados'] = $datos['insumos_preparados']['rows'];
		
	// Carga la vista de las recetas
		require('views/recetas/vista_control_insumos.php');
	}
			
///////////////// ******** ---- 		FIN	vista_control_insumos				------ ************ //////////////////

///////////////// ******** ---- 		listar_movimientos_inventario			------ ************ //////////////////
//////// Consluta las entradas y las salidas de los productos
	// Como parametros recibe:
		// f_ini -> Fecha inicial
		// f_fin -> Fecha final
		// sucursal -> ID de la sucursal
		// almacen -> ID del almacen
		// grafica -> 1 -> Dia, 2 -> Semana, 3 -> Mes, 4 -> Año
		// insumos -> string con los ID's de los insumos
		// tipo -> 3 -> Insumo, 4 -> insumo preparado
		
	function listar_movimientos_inventario($objeto){
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;
	
	// Formatea la sucursal, el almacen y los productos
		$objeto['almacen'] = implode(",", $objeto['almacen']);
		$objeto['sucursal'] = implode(",", $objeto['sucursal']);
		$objeto['insumos'] = implode(",", $objeto['insumos']);
		
		
	// Consulta los insumos
		$result = $this -> recetasModel -> listar_movimientos_inventario($objeto);
		$result = $result['rows'];
		
		$datos = Array();
		
		foreach ($result as $key => $value) {
			$datos[$value['id_insumo']]['nombre'] = $value['nombre'];
			$datos[$value['id_insumo']]['codigo'] = $value['codigo'];
			$datos[$value['id_insumo']]['unidad'] = $value['unidad'];
			$datos[$value['id_insumo']]['imagen'] = $value['ruta_imagen'];
			// Optiene los insumos normales y los agrega al array
			if (!empty($value['ids_insumos'])) {
				$objeto['ids'] = $value['ids_insumos'];
				$objeto['id_receta'] = $value['id_insumo'];
				$datos[$value['id_insumo']]['insumos'] = $this -> recetasModel -> listar_materiales($objeto);
				$datos[$value['id_insumo']]['insumos'] = $datos[$value['id_insumo']]['insumos']['rows'];
			}

		// Optiene los insumos preparados y los agrega al array
			if (!empty($value['ids_insumos_preparados'])) {
				$objeto['ids'] = $value['ids_insumos_preparados'];
				$objeto['id_receta'] = $value['id_insumo'];
				$datos[$value['id_insumo']]['insumos_preparados'] = $this -> recetasModel -> listar_materiales($objeto);
				$datos[$value['id_insumo']]['insumos_preparados'] = $datos[$value['id_insumo']]['insumos_preparados']['rows'];
			}
		// Entradas
			if ($value['tipo_traspaso'] == 1) {
				$datos[$value['id_insumo']]['total_entradas'] += $value['cantidad'];
				$datos[$value['id_insumo']]['origenes'][$value['origen_text']]['entradas'] = $value['cantidad'];
		// Salidas
			} else {
				$datos[$value['id_insumo']]['total_salidas'] += $value['cantidad'];
				$datos[$value['id_insumo']]['origenes'][$value['origen_text']]['salidas'] = $value['cantidad'];
			}
		}
		
		$actividades_2 = $this -> recetasModel -> listar_movimientos_inventario_barra($objeto);
		$actividades_2 = $actividades_2['rows'];
	
	// Arma el array para la grafica de barras
		foreach ($actividades_2 as $key => $value) {
			$barras[$key]['productos'] = $value['nombre'];
			$barras[$key]['cantidad'] = $value['cantidad'];
		}

	// Carga la vista de las recetas
		require('views/recetas/listar_movimientos_inventario.php');
	}
			
///////////////// ******** ---- 	FIN	listar_movimientos_inventario			------ ************ //////////////////


} ?>