<?php
/**
 * @author Fer De La Cruz
 */
 
// require("models/connection_sqli.php"); // funciones mySQLi

class configuracionModel extends comandasModel{
///////////////// ******** ---- 		pass		------ ************ //////////////////
	//////// Consulta la contraseña de seguridad en la BD
		// Como parametros recibe:
		
	function pass($objeto) {
		$sql = "SELECT
					seguridad
				FROM
					com_configuracion";
        $result = $this->queryArray($sql);
        
        return $result;
	}

///////////////// ******** ---- 		FIN pass		------ ************ //////////////////

///////////////// ******** ---- 		guardar_pass		------ ************ //////////////////
	//////// Guarda la nueva contraseña en la BD
		// Como parametros recibe:
			// pass1 -> contraseña
			// pass2 -> debe coincidir con pass1
			
	function guardar_pass($objeto) {
	// Guarda la actividad
		$fecha=date('Y-m-d H:i:s');
		
		$sql="	INSERT INTO
					com_actividades
						(id, empleado, accion, fecha)
				VALUES
					('',".$_SESSION['accelog_idempleado'].",'Cambia contraseña', '".$fecha."')";
		$actividad=$this->query($sql);
			
		$sql="	UPDATE
					com_configuracion
				SET
					seguridad=".$objeto['pass1'];
        $result = $this->query($sql);
        
        return $result;
	}

///////////////// ******** ---- 		FIN guardar_pass		------ ************ //////////////////

///////////////// ******** ---- 		guardar_platillo		------ ************ //////////////////
//////// Guarda el horario del platillo en la BD
	// Como parametros recibe:
		// id -> ID del producto
		// dias -> cadena con los numeros de los dias (0,1,2,3,4,5,6) de domingo a lunes
		// inicio -> hora de inicio
		// fin -> hora final
		
	function guardar_platillo($objeto){
	// ** Evita el error de que no esten llenos los campos de foodware
		$sql="	INSERT IGNORE INTO
					app_campos_foodware(id_producto, vendible, rate)
				SELECT
					id, IF(tipo_producto = 3, 0, 1), 1
				FROM
					app_productos;";
		// return $sql;
        $productos = $this->query($sql);
        
		$sql="	UPDATE
					app_campos_foodware
				SET
					h_ini = '".$objeto['inicio']."',
					h_fin = '".$objeto['fin']."',
					dias = '".$objeto['dias']."'
				WHERE
					id_producto = ".$objeto['id'];
		// return $sql;
        $result = $this->query($sql);
        
	// Guarda la actividad
		date_default_timezone_set('America/Mexico_City');
		$fecha = date('Y-m-d H:i:s');
		
		session_start();
		$empleado = (!empty($_SESSION['accelog_idempleado'])) ? $_SESSION['accelog_idempleado'] : 0 ;
		$sql = "	INSERT INTO
						com_actividades
							(id, empleado, accion, fecha)
					VALUES
						('',".$empleado.",'Cambia horario del platillo', '".$fecha."')";
		$actividad=$this->query($sql);
		
        return $result;
	}

///////////////// ******** ---- 		FIN guardar_platillo		------ ************ //////////////////

///////////////// ******** ---- 		eliminar_platillo			------ ************ //////////////////
//////// Elimina los dias y el horario de un platillo
	// Como parametros recibe:
		// id -> ID del producto
		
	function eliminar_platillo($objeto){
		$sql="	UPDATE
					app_campos_foodware
				SET
					h_ini = '',
					h_fin = '',
					dias = ''
				WHERE
					id_producto = ".$objeto['id'];
		// return $sql;
        $result = $this->query($sql);
        
	// Guarda la actividad
		date_default_timezone_set('America/Mexico_City');
		$fecha = date('Y-m-d H:i:s');
		
		session_start();
		$empleado = (!empty($_SESSION['accelog_idempleado'])) ? $_SESSION['accelog_idempleado'] : 0 ;
		$sql = "	INSERT INTO
						com_actividades
							(id, empleado, accion, fecha)
					VALUES
						('',".$empleado.",'Elimina horario del platillo', '".$fecha."')";
		$actividad=$this->query($sql);
		
        return $result;
	}

///////////////// ******** ---- 		FIN eliminar_platillo		------ ************ ////////////////////////////////////

///////////////// ******** ---- 		listar_productos			------ ************ ///////////////////////////////////
//////// Consulta los productos y los regresa en un array
	// Como parametros puede recibir:
		// id -> ID del producto
		// tipo -> tipo de producto
		// orden -> orden de la consulta
			
	function listar_productos($objeto){
	// Filtra por el ID del producto si existe
		$condicion .= (!empty($objeto['id']))?' AND p.id = '.$objeto['id']:'';
	// Filtra por el ID del kit si existe
		$condicion .= (!empty($objeto['id_kit']))?' AND k.id_kit = '.$objeto['id_kit']:'';
	// Filtra por el ID del combo si existe
		$condicion .= (!empty($objeto['id_combo']))?' AND c.id_combo = '.$objeto['id_combo']:'';
	// Filtra por promocion si existe
		$condicion .= (!empty($objeto['id_promocion']))?' AND pro.id_promocion = '.$objeto['id_promocion']:'';
	// Filtra por tipo
		$condicion .= (!empty($objeto['tipo']))?' AND p.tipo_producto = '.$objeto['tipo']:' AND (p.tipo_producto = 1 OR p.tipo_producto = 4 OR p.tipo_producto = 5 OR p.tipo_producto = 6 OR p.tipo_producto = 7)';
	// Agrupa la consulta si existe
		$condicion .= (!empty($objeto['agrupar']))?' GROUP BY '.$objeto['agrupar']:' GROUP BY p.id';
	// Ordena la consulta si existe
		$condicion .= (!empty($objeto['orden']))?' ORDER BY '.$objeto['orden']:' ORDER BY p.id DESC';
		
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
	
		$sql = "select * from app_producto_sucursal limit 1";
		$total = $this -> queryArray($sql);
		if($total['total'] > 0){
			$sql = "SELECT 
						p.id AS idProducto, p.nombre, p.costo_servicio AS costo, p.link, p.resena, 
						p.id_unidad_compra AS idunidadCompra, p.id_unidad_venta AS idunidad, 
						(SELECT
							nombre
						FROM
							app_unidades_medida uni
						WHERE
							uni.id=p.id_unidad_venta) AS unidad, u.factor, p.tipo_producto, 
						ROUND(p.precio, 2) AS precio, p.codigo, 
						IF(p.linea > 0, CONCAT('l-', p.linea), 
							IF(p.familia > 0, CONCAT('f-', p.familia), 
								IF(p.departamento > 0, CONCAT('d-', p.departamento), '#')
							)
						) AS parent,
						p.id AS id,  CONCAT(p.nombre, ' $', ROUND(p.precio, 2)) AS text, 'fa fa-cutlery' AS icon, k.cantidad, c.grupo,
						c.cantidad_grupo, pro.comprar, pro.recibir,d.nombre as departamento, fa.nombre as familia, l.nombre as linea
					FROM
						app_productos p
					LEFT JOIN
							app_campos_foodware f
						ON	
							p.id = f.id_producto
					LEFT JOIN
							com_promocionesXproductos pro
						ON
							pro.id_producto = p.id
					LEFT JOIN
							app_unidades_medida u
						ON
							u.id = p.id_unidad_compra
					LEFT JOIN 
							app_linea l
						ON 
							p.linea = l.id
					LEFT JOIN 
							app_familia fa
						ON 
							p.familia = fa.id
					LEFT JOIN 
							app_departamento d
						ON 
							p.departamento = d.id
					LEFT JOIN 
							com_kitsXproductos k
						ON 
							k.id_producto = p.id
					LEFT JOIN 
							com_combosXproductos c
						ON 
							c.id_producto = p.id
					INNER JOIN app_producto_sucursal aps ON aps.id_producto = p.id AND aps.id_sucursal = ".$sucursal."
					WHERE
						p.status = 1".
					$condicion;
		} else {
			$sql = "SELECT 
						p.id AS idProducto, p.nombre, p.costo_servicio AS costo, p.link, p.resena, 
						p.id_unidad_compra AS idunidadCompra, p.id_unidad_venta AS idunidad, 
						(SELECT
							nombre
						FROM
							app_unidades_medida uni
						WHERE
							uni.id=p.id_unidad_venta) AS unidad, u.factor, p.tipo_producto, 
						ROUND(p.precio, 2) AS precio, p.codigo, 
						IF(p.linea > 0, CONCAT('l-', p.linea), 
							IF(p.familia > 0, CONCAT('f-', p.familia), 
								IF(p.departamento > 0, CONCAT('d-', p.departamento), '#')
							)
						) AS parent,
						p.id AS id,  CONCAT(p.nombre, ' $', ROUND(p.precio, 2)) AS text, 'fa fa-cutlery' AS icon, k.cantidad, c.grupo,
						c.cantidad_grupo, pro.comprar, pro.recibir, d.nombre as departamento, fa.nombre as familia, l.nombre as linea
					FROM
						app_productos p
					LEFT JOIN
							app_campos_foodware f
						ON	
							p.id = f.id_producto
					LEFT JOIN
							com_promocionesXproductos pro
						ON
							pro.id_producto = p.id
					LEFT JOIN
							app_unidades_medida u
						ON
							u.id = p.id_unidad_compra
					LEFT JOIN 
							app_linea l
						ON 
							p.linea = l.id
					LEFT JOIN 
							app_familia fa
						ON 
							p.familia = fa.id
					LEFT JOIN 
							app_departamento d
						ON 
							p.departamento = d.id
					LEFT JOIN 
							com_kitsXproductos k
						ON 
							k.id_producto = p.id
					LEFT JOIN 
							com_combosXproductos c
						ON 
							c.id_producto = p.id
					WHERE
						p.status = 1".
					$condicion;
		}
		 //echo $sql.'--------------------------------';
		$result = $this->queryArray($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 		FIN listar_productos		------ ************ ///////////////////////////////////

///////////////// ******** ---- 		guardar_promocion			------ ************ ///////////////////////////////////
//////// Guarda el horario del platillo en la BD
	// Como parametros puede recibir:
		// nombre -> nombre de la promocion
		// dias -> string con los numeros de los dias
		// tipo -> 1 -> por descuento, 2 -> por cantidad
		// descuento(si es tipo 1) -> porcentaje de descuento
		// cantidad(si es tipo 2) -> cantidad de productos
		// cantidad_descuento(si es tipo 2) -> cantidad que se descuenta
		// inicio -> hora de inicio
		// fin -> hora final
		
	function guardar_promocion($objeto){
	// Anti hack
		foreach ($objeto as $key => $value) {
			$datos[$key] = $this -> escapalog($value);
		}
	
	// Guarda la promocion
		$sql = "INSERT INTO
					com_promociones(nombre, tipo, cantidad, cantidad_descuento, descuento, dias, inicio, fin, precio_fijo,idsuc)
				VALUES
					('".$datos['nombre']."', '".$datos['tipo']."', '".$datos['cantidad']."', '".$datos['cantidad_descuento']."', 
					'".$datos['descuento']."', '".$datos['dias']."', '".$datos['inicio']."', '".$datos['fin']."', '".$datos['precio_fijo']."' , '".$datos['sucursal']."' 
					)";
		// return $sql;
        $result = $this->insert_id($sql);
		
	// Guarda la actividad
		date_default_timezone_set('America/Mexico_City');
		$fecha = date('Y-m-d H:i:s');
		
		session_start();
		$empleado = (!empty($_SESSION['accelog_idempleado'])) ? $_SESSION['accelog_idempleado'] : 0 ;
		$sql = "INSERT INTO
					com_actividades
						(id, empleado, accion, descripcion, fecha)
				VALUES
					('',".$empleado.",'Crea promocion', 'Crea la promocion [".$result."] ".$datos['nombre']."', '".$fecha."')";
		$actividad = $this->query($sql);
		
        return $result;
	}

///////////////// ******** ---- 			FIN guardar_promocion				------ ************ ///////////////////////////////////

///////////////// ******** ---- 			actualizar_promocion				------ ************ ///////////////////////////////////
//////// Actualiza la informacion de la promocion en la BD
	// Como parametros puede recibir:
		// nombre -> nombre de la promocion
		// dias -> string con los numeros de los dias
		// tipo -> 1 -> por descuento, 2 -> por cantidad
		// descuento(si es tipo 1) -> porcentaje de descuento
		// cantidad(si es tipo 2) -> cantidad de productos
		// cantidad_descuento(si es tipo 2) -> cantidad que se descuenta
		// inicio -> hora de inicio
		// fin -> hora final
		
	function actualizar_promocion($objeto){
	// Anti hack
		foreach ($objeto as $key => $value) {
			$datos[$key] = $this -> escapalog($value);
		}
	
	// Guarda la promocion
		$sql = "UPDATE
					com_promociones
				SET
					nombre = '".$datos['nombre']."', tipo = '".$datos['tipo']."', cantidad = '".$datos['cantidad']."', 
					cantidad_descuento = '".$datos['cantidad_descuento']."', descuento = '".$datos['descuento']."', 
					dias = '".$datos['dias']."', inicio = '".$datos['inicio']."', fin = '".$datos['fin']."', precio_fijo = '".$datos['precio_fijo']."' ,
					idsuc = '".$datos['sucursal']."' 
				WHERE
					id = ".$datos['id_promocion'];
		// return $sql;
        $result = $this->query($sql);
		
	// Guarda la actividad
		date_default_timezone_set('America/Mexico_City');
		$fecha = date('Y-m-d H:i:s');
		
		session_start();
		$empleado = (!empty($_SESSION['accelog_idempleado'])) ? $_SESSION['accelog_idempleado'] : 0 ;
		$sql = "INSERT INTO
					com_actividades
						(id, empleado, accion, descripcion, fecha)
				VALUES
					('',".$empleado.",'Cambia promocion', 'Modifica la promocion [".$datos['id']."] ".$datos['nombre']."', '".$fecha."')";
		$actividad = $this->query($sql);
		
        return $result;
	}	

///////////////// ******** ---- 			FIN actualizar_promocion				------ ************ ///////////////////////////////////

///////////////// ******** ---- 			eliminar_productos						------ ************ ///////////////////////////////////
//////// Borra los productos de la promocion de la BD
	// Como parametros puede recibir:
		// id_promocion -> ID de la promocion
		
	function eliminar_productos($objeto){
	// Guarda la promocion
		$sql = "DELETE FROM
					com_promocionesXproductos
				WHERE
					id_promocion = ".$objeto['id_promocion'];
		// return $sql;
        $result = $this->query($sql);
		
        return $result;
	}

///////////////// ******** ---- 			FIN eliminar_productos					------ ************ ///////////////////////////////////

///////////////// ******** ---- 			guardar_producto_promocion				------ ************ ///////////////////////////////////
//////// Guarda el horario del platillo en la BD
	// Como parametros puede recibir:
		// id -> ID del producto
		// id_promocion -> ID de la promocion
		
	function guardar_producto_promocion($objeto){

	// Guarda la promocion
		if(empty($objeto['obtener']) && empty($objeto['comprar'])){
			if($objeto['obtener'] == 1  || $objeto['comprar'] == 1){
				return 0;
			}else{
				$sql = "INSERT INTO
					com_promocionesXproductos(id_promocion, id_producto)
				VALUES
					('".$objeto['id_promocion']."', '".$objeto['id']."')";
			}
			
		} else {
			if($objeto['comprar'] == 1) {
				$sql = "INSERT INTO
					com_promocionesXproductos(id_promocion, id_producto, comprar)
				VALUES
					('".$objeto['id_promocion']."', '".$objeto['id']."', 1)";
			} else {
				$sql = "INSERT INTO
					com_promocionesXproductos(id_promocion, id_producto, recibir)
				VALUES
					('".$objeto['id_promocion']."', '".$objeto['id']."', 1)";
			}
		}
		
		// return $sql;
        $result = $this->query($sql);
        //echo $sql;
		
        return $result;
	}

///////////////// ******** ---- 			FIN guardar_producto_promocion			------ ************ ///////////////////////////////////

///////////////// ******** ---- 			listar_pomociones						------ ************ ///////////////////////////////////
//////// Consulta las promociones y las regresa en un array
	// Como parametros puede recibir:
		// id -> ID del producto
		// orden -> orden de la consulta
			
	function listar_pomociones($objeto){
	// Filtra por el ID del producto si existe
		$condicion.=(!empty($objeto['id']))?' AND pro.id = '.$objeto['id']:'';
	// Filtra por el ID del producto si existe
		$condicion.=(!empty($objeto['status']))?' AND pro.status = '.$objeto['status']:' AND pro.status = 1';
		
	// Ordena  la consulta si existe
		$condicion.=(!empty($objeto['orden']))?' ORDER BY '.$objeto['orden']:'';
		
		$sql = "SELECT
					pro.id AS id_promocion, pro.nombre, pro.tipo AS tipo_promocion,
					IF(pro.cantidad > 0, pro.cantidad, '') AS cantidad, 
					IF(pro.precio_fijo > 0, pro.precio_fijo, '') AS precio_fijo, 
					IF(pro.cantidad_descuento > 0, pro.cantidad_descuento, '') AS cantidad_descuento, 
					IF(pro.descuento > 0, pro.descuento, '') AS descuento, pro.dias, pro.inicio, pro.fin, pro.idsuc
				FROM
					com_promociones pro
				WHERE
					1 = 1 ".
				$condicion;
		// return $sql;
		$result = $this->queryArray($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 			FIN listar_pomociones					------ ************ ///////////////////////////////////

///////////////// ******** ---- 				eliminar_promocion					------ ************ ///////////////////////////////////
//////// Cambia el status de la promocion a eliminado en la BD
	// Como parametros recibe:
		// id_promocion -> ID de la promocion
		
	function eliminar_promocion($objeto){
		$sql="	UPDATE
					com_promociones
				SET
					status = 2
				WHERE
					id = ".$objeto['id_promocion'];
		// return $sql;
		$result = $this->query($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 				FIN eliminar_promocion				------ ************ ///////////////////////////////////

///////////////// ******** ---- 				listar_categorias_menu				------ ************ ///////////////////////////////////
//////// Consulta las categoras de los menus y las devuelve en un array
	// Como parametros recibe:
		
	function listar_categorias_menu($objeto){
		$sql = "	SELECT 
						CONCAT('d-', d.id) AS id, nombre AS text, '#' AS parent, 'fa fa-building-o' AS icon
					FROM 
						app_departamento d
				UNION
					SELECT 
						CONCAT('f-', f.id) AS id, nombre AS text, CONCAT('d-', f.id_departamento) AS parent, 'fa fa-bank' AS icon
					FROM 
						app_familia f
				UNION
					SELECT 
						CONCAT('l-', l.id) AS id, nombre AS text, CONCAT('f-', l.id_familia) AS parent, 'fa fa-coffee' AS icon
					FROM 
						app_linea l";
		// return $sql;
		$result = $this->queryArray($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 				FIN listar_categorias_menu			------ ************ ///////////////////////////////////

///////////////// ******** ---- 						logo						------ ************ ///////////////////////////////////
	//** Consulta el logo de la empresa
	// Como parametro recibe:
	// id-> id de la empresa

	function logo($objet) {
		$condicion .= (!empty($objet['id'])) ? ' AND idorganizacion=\'' . $objet['id'] . '\'' : '';

		$sql = "SELECT 
					logoempresa as logo
				FROM 
					organizaciones
				WHERE 
					1=1" . $condicion;
		// return $sql;
		$result = $this -> queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 						FIN logo					------ ************ ///////////////////////////////////

///////////////// ******** ---- 						agregar_menu				------ ************ ///////////////////////////////////
//////// Agrega un nuevo menu digital
	// Como parametros recibe:
		// nombre -> nombre del menu
		// nombre_restaurante -> nombre del restaurante
		// estilo -> estilo seleccionado
		// btn -> boton del loader
		// productos -> array con los productos para el menu
		
	function agregar_menu($objeto){
		$sql = "INSERT INTO
					com_menus(nombre, nombre_restaurante, estilo)
				VALUES
					('".$objeto['nombre']."', '".$objeto['nombre_restaurante']."', '".$objeto['estilo']."')";
		// return $sql;
		$result = $this->insert_id($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 					FIN agregar_menu				------ ************ ///////////////////////////////////

///////////////// ******** ---- 				agregar_producto_menu				------ ************ ///////////////////////////////////
//////// Agrega un producto del menu
	// Como parametros recibe:
		// id -> ID del producto
		// parent -> ID del padre
		// id_menu -> ID del menu
		
	function agregar_producto_menu($objeto){
		$sql = "INSERT INTO
					com_productos_menu(id_producto, id_padre, id_menu)
				VALUES
					('".$objeto['id']."', '".$objeto['parent']."', '".$objeto['id_menu']."')";
		// return $sql;
		$result = $this->query($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 			FIN agregar_producto_menu				------ ************ ///////////////////////////////////

///////////////// ******** ---- 				listar_menus						------ ************ ///////////////////////////////////
//////// Consulta los menus y los devuelve en un array
	// Como parametros recibe:
		
	function listar_menus($objeto){
		$condicion .= (!empty($objeto['orden'])) ? ' ORDER BY '.$objeto['orden'] : '' ;
		
		$sql = "SELECT
					id AS id_menu, nombre, nombre_restaurante, estilo,
					(CASE estilo 
						WHEN 1 THEN
							'Alternativo'
						WHEN 2 THEN
							'Clasico'
						WHEN 3 THEN
							'Organico Vintage'
						WHEN 4 THEN
							'Tradicional'
						ELSE '---' END) AS texto_estilo
				FROM
					com_menus
				WHERE 
					1 = 1".
				$condicion;
		// return $sql;
		$result = $this->queryArray($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 				FIN listar_menus					------ ************ ///////////////////////////////////

///////////////// ******** ---- 			listar_productos_menu					------ ************ ///////////////////////////////////
//////// Consulta los menus y los devuelve en un array
	// Como parametros recibe:
		// id_menu -> ID del menu
		// activo -> (1 -> activa los productos... Sirve al momento de editar el menu)
		
	function listar_productos_menu($objeto){
		$condicion .= (!empty($objeto['id_menu'])) ? ' AND p.id_menu = \'' . $objeto['id_menu'] . '\'' : '';
		$select .= (!empty($objeto['activo'])) ? ' , true AS selected ' : '';
		
		$sql = "SELECT
					p.*, pro.nombre, pro.precio, pro.link, pro.resena,
					(CASE id_padre 
						WHEN p.id_padre LIKE '%d-%' THEN
							(SELECT
								nombre
							FROM
								app_departamento
							WHERE
								id = SUBSTRING(p.id_padre, 3))
						WHEN p.id_padre LIKE '%f-%' THEN
							(SELECT
								nombre
							FROM
								app_familia
							WHERE
								id = SUBSTRING(p.id_padre, 3))
						WHEN p.id_padre LIKE '%l-%' THEN
							(SELECT
								nombre
							FROM
								app_linea
							WHERE
								id = SUBSTRING(p.id_padre, 3))
						END) AS parent_text".
					$select."
				FROM
					com_productos_menu p
				LEFT JOIN
						app_productos pro
					ON
						pro.id = p.id_producto
				WHERE
					1 = 1 ".
				$condicion;
		// return $sql;
		$result = $this->queryArray($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 			FIN listar_productos_menu				------ ************ ///////////////////////////////////

///////////////// ******** ---- 			actualizar_menu							------ ************ //////////////////
//////// Actualiza los datos del menu
	// Como parametros recibe:
		// nombre -> nombre del menu
		// nombre_restaurante -> nombre del restaurante
		// estilo -> estilo seleccionado
		// btn -> boton del loader
		// productos -> array con los productos para el menu
		
	function actualizar_menu($objeto){
	// Anti hack
		foreach ($objeto as $key => $value) {
			$datos[$key] = $this -> escapalog($value);
		}
		
		$sql = "UPDATE
					com_menus
				SET
					nombre = '".$datos['nombre']."', 
					nombre_restaurante = '".$datos['nombre_restaurante']."', 
					estilo = '".$datos['estilo']."'
				WHERE
					id = ".$datos['id_menu'];
		// return $sql;
		$result = $this->query($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 			FIN actualizar_menu							------ ************ //////////////////

///////////////// ******** ---- 				eliminar_menu							------ ************ //////////////////
//////// Elimina el menu
	// Como parametros recibe:
		// id -> ID del menu
		
	function eliminar_menu($objeto){
		$sql = "DELETE FROM
					com_menus
				WHERE
					id = ".$objeto['id_menu'];
		// return $sql;
		$result = $this->query($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 				FIN eliminar_menu						------ ************ //////////////////

///////////////// ******** ---- 			eliminar_productos_menu						------ ************ //////////////////
//////// Elimina los productos viejos del menu
	// Como parametros recibe:
		// id_menu -> ID del menu
		
	function eliminar_productos_menu($objeto){
		$sql = "DELETE FROM
					com_productos_menu
				WHERE
					id_menu = ".$objeto['id_menu'];
		// return $sql;
		$result = $this->query($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 			FIN eliminar_productos_menu					------ ************ //////////////////

///////////////// ******** ---- 				guardar_producto					------ ************ //////////////////
//////// Manda llamar a la funcion que guarda el producto
	// Como parametros puede recibir:
		// nombre -> nombre de la promocion
		// precio -> Precio del kit
		// codigo -> Codigo del producto
		// costo -> Costo del producto
			
	function guardar_producto($objeto){
	// Anti hack
		foreach ($objeto as $key => $value) {
			$datos[$key] = $this->escapalog($value);
		}
		
	// Guarda la receta y regresa el ID
		$sql = "INSERT INTO
					app_productos
						(codigo, nombre, precio, linea, costo_servicio, tipo_producto)
				VALUES
					('".$datos['codigo']."','".$datos['nombre']."',".$datos['precio'].",
						1, ".$datos['costo'].", ".$datos['tipo']."
					)";
		// return $sql;
		$result = $this->insert_id($sql);
		
	// Guarda los campos de foodware
		$sql = "INSERT INTO
					app_campos_foodware
						(id_producto)
				VALUES
					('".$result."')";
		// return $sql;
		$result_foodware = $this->query($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 				FIN guardar_producto				------ ************ //////////////////

///////////////// ******** ---- 					guardar_kit						------ ************ //////////////////
//////// Manda llamar a la funcion que guarda el kit y sus productos
	// Como parametros puede recibir:
		// nombre -> nombre de la promocion
		// id_producto -> ID del producto
		// dias -> string con los numeros de los dias
		// inicio -> hora de inicio
		// fin -> hora final
			
	function guardar_kit($objeto){
		$sql = "INSERT INTO
					com_kits
						(nombre, id, dias, inicio, fin)
				VALUES
					('".$objeto['nombre']."', '".$objeto['id_producto']."', '".$objeto['dias']."', 
						'".$objeto['inicio']."', '".$objeto['fin']."')";
		// return $sql;
		$result = $this->query($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 				FIN guardar_kit					------ ************ //////////////////

///////////////// ******** ---- 			guardar_productos_kit				------ ************ //////////////////
//////// Manda llamar a la funcion que guarda el kit y sus productos
	// Como parametros puede recibir:
		// id_kit -> ID del kit
		// id -> ID del producto
			
	function guardar_productos_kit($objeto){
	// Anti hack
		foreach ($objeto as $key => $value) {
			$datos[$key] = $this->escapalog($value);
		}
		
	// Guarda la receta y regresa el ID
		$sql = "INSERT IGNORE INTO
					com_kitsXproductos
						(id_kit, id_producto, cantidad)
				VALUES
					('".$datos['id_kit']."', '".$datos['id']."', '".$datos['cantidad']."')";
		// return $sql;
		$result = $this->query($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 			FIN guardar_productos_kit			------ ************ //////////////////

///////////////// ******** ---- 					listar_kits					------ ************ //////////////////
//////// Consulta lOS KITS y las regresa en un array
	// Como parametros puede recibir:
		// id -> ID del producto
		// orden -> Orden de la consulta
			
	function listar_kits($objeto){
	// Filtra por el ID del producto si existe
		$condicion .= (!empty($objeto['id']))?' AND k.id = '.$objeto['id']:'';
	// Filtra por el status del kit
		$condicion.=(!empty($objeto['status']))?' AND k.status = '.$objeto['status']:' AND k.status = 1';
		
	// Ordena la consulta si existe
		$condicion .= (!empty($objeto['orden']))?' ORDER BY '.$objeto['orden']:' ORDER BY k.id DESC';
		
		$sql = "SELECT
					k.id AS id_kit, k.nombre, p.codigo, p.precio, k.dias, k.inicio, k.fin, p.costo_servicio AS costo
				FROM
					com_kits k
				LEFT JOIN
						app_productos p
					ON
						p.id = k.id
				WHERE
					1 = 1 ".
				$condicion;
		// return $sql;
		$result = $this->queryArray($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 				FIN listar_kits					------ ************ //////////////////

///////////////// ******** ---- 			actualizar_producto					------ ************ //////////////////
//////// Modifica los datos del producto
	// Como parametros recibe:
		// id_kit -> ID del kit
		// nombre -> Nombre del producto
		// codigo -> Codigo del producto
		// costo -> Costo del producto
		// precio -> Precio del producto
		// tipo -> Tipo de producto
			
	function actualizar_producto($objeto){
	// Anti hack
		foreach ($objeto as $key => $value) {
			$datos[$key]=$this->escapalog($value);
		}
		
		$id = (!empty($datos['id_combo'])) ? $datos['id_combo'] : '';
		$id = (!empty($datos['id_kit'])) ? $datos['id_kit'] : $id;
		
	// Actualiza la DB
		$sql = "UPDATE
					app_productos
				SET
					codigo = '".$datos['codigo']."', nombre = '".$datos['nombre']."',
					precio = ".$datos['precio'].", costo_servicio = ".$datos['costo'].",
					tipo_producto = ".$datos['tipo']."
				WHERE
					id = ".$id;
		// return $sql;
		$result = $this->query($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 			FIN actualizar_producto				------ ************ //////////////////

///////////////// ******** ---- 				actualizar_kit					------ ************ //////////////////
//////// Modifica los datos del kit
	// Como parametros recibe:
		// id_kit -> ID del kit
		// nombre -> Nombre del kit
		// dias -> String con los dias
		// inicio -> Hora de inicio
		// fin -> Hora final
			
	function actualizar_kit($objeto){
	// Anti hack
		foreach ($objeto as $key => $value) {
			$datos[$key]=$this->escapalog($value);
		}
	// Actualiza la DB
		$sql = "UPDATE
					com_kits
				SET
					dias = '".$datos['dias']."', nombre = '".$datos['nombre']."',
					inicio = '".$datos['inicio']."', fin = '".$datos['fin']."'
				WHERE
					id = ".$datos['id_kit'];
		// return $sql;
		$result = $this->query($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 				FIN actualizar_kit				------ ************ //////////////////

///////////////// ******** ---- 			eliminar_productos_kit				------ ************ //////////////////
//////// Elimina los productos del kit
	// Como parametros recibe:
		// id_kit -> ID del kit
			
	function eliminar_productos_kit($objeto){
	// Actualiza la DB
		$sql = "DELETE FROM
					com_kitsXproductos
				WHERE
					id_kit = ".$objeto['id_kit'];
		// return $sql;
		$result = $this->query($sql);
		
		return $result;
	}

///////////////// ******** ---- 			FIN eliminar_productos_kit			------ ************ //////////////////

///////////////// ******** ---- 				eliminar_kit					------ ************ //////////////////
//////// Cambia el status del kit a eliminado en la BD
	// Como parametros recibe:
		// id_kit -> ID del kit
		
	function eliminar_kit($objeto){
		$sql="	UPDATE
					com_kits
				SET
					status = 2
				WHERE
					id = ".$objeto['id_kit'];
		// return $sql;
		$result = $this->query($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 				FIN eliminar_kit				------ ************ //////////////////

///////////////// ******** ---- 				guardar_combo					------ ************ //////////////////
//////// Manda llamar a la funcion que guarda el combo y sus productos
	// Como parametros puede recibir:
		// nombre -> nombre de la promocion
		// id_producto -> ID del producto
		// dias -> string con los numeros de los dias
		// inicio -> hora de inicio
		// fin -> hora final
			
	function guardar_combo($objeto){
		$sql = "INSERT INTO
					com_combos
						(nombre, id, dias, inicio, fin, idsuc)
				VALUES
					('".$objeto['nombre']."', '".$objeto['id_producto']."', '".$objeto['dias']."', 
						'".$objeto['inicio']."', '".$objeto['fin']."', '".$objeto['sucursal']."')";
		// return $sql;
		$result = $this->query($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 				FIN guardar_combo				------ ************ //////////////////

///////////////// ******** ---- 			guardar_productos_combo				------ ************ //////////////////
//////// Manda llamar a la funcion que guarda el combo y sus productos
	// Como parametros puede recibir:
		// id_combo -> ID del combo
		// id -> ID del producto
			
	function guardar_productos_combo($objeto){
	// Anti hack
		foreach ($objeto as $key => $value) {
			$datos[$key] = $this->escapalog($value);
		}
		
	// Guarda la receta y regresa el ID
		$sql = "INSERT IGNORE INTO
					com_combosXproductos
						(id_combo, id_producto, grupo, cantidad_grupo)
				VALUES
					('".$datos['id_combo']."', '".$datos['id']."', '".$datos['grupo']."', '".$datos['cantidad']."')";
		// return $sql;
		$result = $this->query($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 			FIN guardar_productos_combo				------ ************ //////////////////

///////////////// ******** ---- 					listar_combos					------ ************ //////////////////
//////// Consulta lOS comboS y las regresa en un array
	// Como parametros puede recibir:
		// id -> ID del producto
		// orden -> Orden de la consulta
			
	function listar_combos($objeto){
	// Filtra por el ID del producto si existe
		$condicion .= (!empty($objeto['id']))?' AND c.id = '.$objeto['id']:'';
	// Filtra por el status del combo
		$condicion.=(!empty($objeto['status']))?' AND c.status = '.$objeto['status']:' AND c.status = 1';
		
	// Ordena la consulta si existe
		$condicion .= (!empty($objeto['orden']))?' ORDER BY '.$objeto['orden']:' ORDER BY c.id DESC';
		
		$sql = "SELECT
					c.id AS id_combo, c.nombre, p.codigo, p.precio, c.dias, c.inicio, c.fin, p.costo_servicio AS costo, idsuc
				FROM
					com_combos c
				LEFT JOIN
						app_productos p
					ON
						p.id = c.id
				WHERE
					1 = 1 ".
				$condicion;
		// return $sql;
		$result = $this->queryArray($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 				FIN listar_combos					------ ************ //////////////////

///////////////// ******** ---- 				actualizar_combo					------ ************ //////////////////
//////// Modifica los datos del combo
	// Como parametros recibe:
		// id_combo -> ID del combo
		// nombre -> Nombre del combo
		// dias -> String con los dias
		// inicio -> Hora de inicio
		// fin -> Hora final
			
	function actualizar_combo($objeto){
	// Anti hack
		foreach ($objeto as $key => $value) {
			$datos[$key]=$this->escapalog($value);
		}
	// Actualiza la DB
		$sql = "UPDATE
					com_combos
				SET
					dias = '".$datos['dias']."', nombre = '".$datos['nombre']."',
					inicio = '".$datos['inicio']."', fin = '".$datos['fin']."' , idsuc = '".$datos['sucursal']."'
				WHERE
					id = ".$datos['id_combo'].";";
		// return $sql;
		$result = $this->query($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 				FIN actualizar_combo				------ ************ //////////////////

///////////////// ******** ---- 			eliminar_productos_combo				------ ************ //////////////////
//////// Elimina los productos del combo
	// Como parametros recibe:
		// id_combo -> ID del combo
			
	function eliminar_productos_combo($objeto){
	// Actualiza la DB
		$sql = "DELETE FROM
					com_combosXproductos
				WHERE
					id_combo = ".$objeto['id_combo'];
		// return $sql;
		$result = $this->query($sql);
		
		return $result;
	}

///////////////// ******** ---- 			FIN eliminar_productos_combo			------ ************ //////////////////

///////////////// ******** ---- 				eliminar_combo						------ ************ //////////////////
//////// Cambia el status del combo a eliminado en la BD
	// Como parametros recibe:
		// id_combo -> ID del combo
		
	function eliminar_combo($objeto){
		$sql = "UPDATE
					com_combos
				SET
					status = 2
				WHERE
					id = ".$objeto['id_combo'].";
				
				UPDATE
					app_productos
				SET
					status = 2
				WHERE
					id = ".$objeto['id_combo'];
		// return $sql;
		$result = $this -> dataTransact($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 				FIN eliminar_combo					------ ************ //////////////////

///////////////// ******** ---- 				listar_insumos						------ ************ //////////////////
	//////// Consulta los insumos y los regresa en un array
		// Como parametros recibe:
			
	function listar_insumos($objeto){
	// Filtra por el ID del insumo si existe
		$condicion.=(!empty($objeto['id']))?' AND p.id='.$objeto['id']:'';
	// Filtra por el nombre y codigo de insumo del insumo si existe
		$condicion.=(!empty($objeto['nombre'])&&!empty($objeto['codigo']))?' 
				AND 
					(p.nombre=\''.$objeto['nombre'].'\'
						OR
					p.codigo=\''.$objeto['codigo'].'\')':'';
	// Filtra por el tipo de producto si existe
		$condicion.=(!empty($objeto['tipo_producto']))?' AND p.tipo_producto='.$objeto['tipo_producto']:'';

	// Si es insumo preparado toma "p.costo_servicio", si no toma el costo del proveedor y si es null lo cambia por 0
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
	
		$sql = "select * from app_producto_sucursal limit 1";
		$total = $this -> queryArray($sql);
		if($total['total'] > 0){
			$sql = "SELECT
         			p.id AS idProducto, p.nombre, IF(p.tipo_producto = 4, ROUND(p.costo_servicio, 2), IFNULL(pro.costo,0)) AS costo,
					p.id_unidad_compra AS idunidadCompra, p.id_unidad_venta AS idunidad, 
					(SELECT
						nombre
					FROM
						app_unidades_medida uni
					WHERE
						uni.id = p.id_unidad_venta) AS unidad, u.factor, if(co.idsuc is not null,if(co.idsuc = '',0,co.idsuc),0) idsuc
				FROM
					app_productos p
				LEFT JOIN
						app_campos_foodware f
					ON
						p.id = f.id_producto
				LEFT JOIN
						app_unidades_medida u
					ON
						u.id = p.id_unidad_compra
				LEFT JOIN
						app_costos_proveedor pro
				ON
						pro.id_producto = p.id
				LEFT JOIN com_complementos co on co.id_producto = p.id
				INNER JOIN app_producto_sucursal aps ON aps.id_producto = p.id AND aps.id_sucursal = ".$sucursal."
				WHERE
					status = 1".
					$condicion ." GROUP BY p.id";
		} else {
			$sql = "SELECT
         			p.id AS idProducto, p.nombre, IF(p.tipo_producto = 4, ROUND(p.costo_servicio, 2), IFNULL(pro.costo,0)) AS costo,
					p.id_unidad_compra AS idunidadCompra, p.id_unidad_venta AS idunidad, 
					(SELECT
						nombre
					FROM
						app_unidades_medida uni
					WHERE
						uni.id = p.id_unidad_venta) AS unidad, u.factor, if(co.idsuc is not null,if(co.idsuc = '',0,co.idsuc),0) idsuc
				FROM
					app_productos p
				LEFT JOIN
						app_campos_foodware f
					ON
						p.id = f.id_producto
				LEFT JOIN
						app_unidades_medida u
					ON
						u.id = p.id_unidad_compra
				LEFT JOIN
						app_costos_proveedor pro
				ON
						pro.id_producto = p.id
				LEFT JOIN com_complementos co on co.id_producto = p.id
				WHERE
					status = 1".
					$condicion ." GROUP BY p.id";
		}
		// return $sql;
		$result = $this->queryArray($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 				FIN listar_insumos					------ ************ //////////////////

///////////////// ******** ----             	guardar_complementos        	    ------ ************ //////////////////
/////// Guarda los complementos
// Como parametros recibe:
    // complementos -> Array con los datos de los complementos
    // btn -> Boton del loader
		
	function guardar_complementos($objeto){
		$sql = "TRUNCATE TABLE 
					com_complementos;
				
				INSERT INTO
					com_complementos(id_producto, cantidad, idsuc)
				VALUES
					".$objeto['string'];
		$result = $this -> dataTransact($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 				FIN guardar_complementos			------ ************ //////////////////

///////////////// ******** ----             	listar_complementos       	 	    ------ ************ //////////////////
/////// Consulta los complementos
// Como parametros recibe:
		
	function listar_complementos($objeto){
		$sql = "SELECT
					p.id, c.cantidad, p.nombre, IF(p.tipo_producto = 4, ROUND(p.costo_servicio, 2), IFNULL(pro.costo,0)) AS costo,
					p.tipo_producto, if(c.idsuc is not null,if(c.idsuc = '',0,c.idsuc),0) idsuc 
				FROM
					com_complementos c
				LEFT JOIN
						app_productos p
					ON
						p.id = c.id_producto
				LEFT JOIN
						app_costos_proveedor pro
					ON
						pro.id_producto = p.id GROUP BY p.id";
		// return $sql;
		$result = $this -> queryArray($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 				FIN guardar_complementos			------ ************ //////////////////

///////////////// ******** ----            			guardar_area_mapa             	------ ************ //////////////////
/////// Guarda los datos del area del mapa
// Como parametros recibe:
	// id_poligo -> ID del poligono padre
	// lat -> Latitud del vector
	// lng -> Longitud del vector

	function guardar_area_mapa($objeto) {
		$sql = "INSERT IGNORE INTO
					com_areas_mapa(id_poligono, lat, lng, id_area)
				VALUES
					(".$objeto['id_poligono'].", ".$objeto['lat'].", ".$objeto['lng'].", ".$objeto['id_area'].")";
		// return $sql;
		$result = $this -> query($sql);
		
		return $result;
	}

///////////////// ******** ----            		FIN guardar_area_mapa             	------ ************ //////////////////

///////////////// ******** ----            		eliminar_areas_mapa             	------ ************ //////////////////
/////// Elimina las areas del mapa de repartidores
// Como parametros recibe:

	function eliminar_areas_mapa($objeto) {
		$sql = "TRUNCATE 
					com_areas_mapa";
		// return $sql;
		$result = $this -> query($sql);
		
		return $result;
	}

///////////////// ******** ----            		FIN eliminar_areas_mapa            ------ ************ //////////////////

///////////////// ******** ----            			listar_areas_mapa              ------ ************ //////////////////
/////// Consulta las areas del mapa y las regresa en un array
// Como parametros recibe:
	// id_area -> ID del area a dibujar

	function listar_areas_mapa($objeto) {
	// Filtra por el ID del producto si existe
		$condicion .= (!empty($objeto['id_area']))?' AND a.id_area = '.$objeto['id_area']:'';
		$sql = "SELECT
					*
				FROM
					com_areas_mapa a
				WHERE
					1 = 1".
				$condicion."
				ORDER BY
					id_poligono";
		// return $sql;
		$result = $this -> queryArray($sql);
		
		return $result;
	}

///////////////// ******** ----            		FIN listar_areas_mapa            	------ ************ //////////////////

///////////////// ******** ----            			listar_areas       			    ------ ************ //////////////////
/////// Consulta las areas y las regresa en un array
// Como parametros recibe:

	function listar_areas($objeto) {
		$sql = "SELECT
					id, nombre
				FROM
					com_zonas_reparto";
		// return $sql;
		$result = $this -> queryArray($sql);
		
		return $result;
	}

///////////////// ******** ----            		FIN listar_areas            		------ ************ //////////////////

///////////////// ******** ----            			listar_sucursales       			    ------ ************ //////////////////
/////// Consulta las sucursales y las regresa en un array

	function listar_sucursales() {
		$sql = "SELECT
					idSuc, nombre
				FROM
					mrp_sucursal";		
		$result = $this -> queryArray($sql);
		
		return $result;
	}

///////////////// ******** ----            		FIN listar_sucursales            		------ ************ //////////////////


} ?>