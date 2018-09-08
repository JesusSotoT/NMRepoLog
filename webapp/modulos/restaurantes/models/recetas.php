<?php
/**
 * @author Fer De La Cruz
 */
 
require("models/connection_sqli.php"); // funciones mySQLi

class recetasModel extends Connection{
///////////////// ******** ---- 			guardar_producto				------ ************ //////////////////
//////// Inserta un registro en la tabla app_productos con los datos de la receta
	// Como parametros recibe:
		// nombre -> Nombre de la receta o insumo preparado
		// codigo -> Codigo de la receta o insumo preparado
		// tipo -> 1(receta), 2(insumo preparado)
		// des -> Comentarios sobre la receta o insumo preparado
		// precio_venta -> Precio de venta
		// margen_ganancia -> Margen de ganancia
			
	function guardar_producto($objeto){
	// Anti hack
		foreach ($objeto as $key => $value) {
			$datos[$key]=$this->escapalog($value);
		}
		
	// Valida el tipo de producto: 5-> Receta, 4-> Insumo preparado
		$tipo = ($datos['tipo']==1) ? 5 : 4 ;
		
	// Guarda la receta y regresa el ID
		$sql="	INSERT INTO
					app_productos
						(codigo, nombre, precio, linea, costo_servicio, id_unidad_venta, id_unidad_compra, 
							tipo_producto
						)
				VALUES
					('".$datos['codigo']."','".$datos['nombre']."',".$datos['precio_venta'].",
						1, ".$datos['costo_receta'].", ".$datos['unidad_venta'].", ".$datos['unidad_compra'].", ".$tipo."
					)";
		// return $sql;
		$result =$this->insert_id($sql);
		
	// Guarda los campos de foodware
		$sql="	INSERT INTO
					app_campos_foodware
						(id_producto, ganancia)
				VALUES
					('".$result."', '".$datos['margen_ganancia']."')";
		// return $sql;
		$result_foodware =$this->query($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 		FIN guardar_producto		------ ************ //////////////////

///////////////// ******** ---- 		actualizar_producto			------ ************ //////////////////
//////// Modifa el registro en la tabla app_productos con los datos de la receta
	// Como parametros recibe:
		// id_receta -> ID de la receta
		// nombre -> nombre de la receta o insumo preparado
		// codigo -> codigo de la receta o insumo preparado
		// tipo -> 1(receta), 2(insumo preparado)
		// des -> comentarios sobre la receta o insumo preparado
		// precio_venta -> precio de venta
		// margen_ganancia -> margen de ganancia
			
	function actualizar_producto($objeto){
	// Anti hack
		foreach ($objeto as $key => $value) {
			$datos[$key]=$this->escapalog($value);
		}
		
	// Valida el tipo de producto: 5-> Receta, 4-> Insumo preparado
		$tipo = ($datos['tipo'] == 1) ? 5 : 4 ;
		
	// Guarda la receta y regresa el ID
		$sql="	UPDATE
					app_productos
				SET
					codigo = '".$datos['codigo']."', nombre = '".$datos['nombre']."',
					precio = ".$datos['precio_venta'].", costo_servicio = ".$datos['costo_receta'].",
					tipo_producto = ".$tipo.", id_unidad_venta = ".$datos['unidad_venta'].",
					id_unidad_compra = ".$datos['unidad_compra']."
				WHERE
					id=".$datos['id_receta'].";
					
				UPDATE
					app_campos_foodware
				SET
					ganancia = ".$datos['margen_ganancia']."
				WHERE
					id_producto = ".$datos['id_receta'].";";
		// return $sql;
		$result = $this->dataTransact($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 		FIN actualizar_producto		------ ************ //////////////////

///////////////// ******** ---- 			guardar_receta			------ ************ //////////////////
//////// Inserta un registro en la tabla com_recetas con los datos de la receta
	// Como parametros recibe:
		// nombre -> nombre de la receta o insumo preparado
		// tipo -> 1(receta), 2(insumo preparado)
		// des -> comentarios sobre la receta o insumo preparado
		// precio_venta -> precio de venta
		// margen_ganancia -> margen de ganancia
			
	function guardar_receta($objeto){
	// Anti hack
		foreach ($objeto as $key => $value) {
			$datos[$key]=$this->escapalog($value);
		}
		
	// Guarda la receta y regresa el ID
		$sql="	INSERT INTO
					com_recetas
						(id, nombre, precio, ganancia, ids_insumos, ids_insumos_preparados, preparacion, proveedores_insumos, ids_normales, costo_receta)
				VALUES
					(".$datos['id_receta'].", '".$datos['nombre']."',".$datos['precio_venta'].",
						".$datos['margen_ganancia'].",
						'".$datos['ids']."','".$datos['ids_preparados']."','".$datos['preparacion']."',
						'".$datos['proveedores_insumos']."','".$datos['normales']."','".$datos['costo_receta']."'
					)";
		// return $sql;
		$result =$this->insert_id($sql);
		
	// Guarda la actividad
		$fecha=date('Y-m-d H:i:s');
		
		$texto = ($datos['tipo']==1) ? 'receta' : 'insumo preparado' ;
	// Valida que exista el empleado si no agrega un cero como id
		$usuario = (!empty($_SESSION['accelog_idempleado'])) ?$_SESSION['accelog_idempleado'] : 0 ;
		$sql="	INSERT INTO
					com_actividades
						(id, empleado, accion, fecha)
				VALUES
					('',".$usuario.",'Agrega ".$texto."', '".$fecha."')";
		$actividad=$this->query($sql);
		
		return $result;
	}
			
///////////////// ******** ---- 		FIN guardar_receta			------ ************ //////////////////

///////////////// ******** ---- 				validar				------ ************ //////////////////
//////// Valida si existe la receta
	// Como parametros recibe:
		// id_receta ID de la receta
		
	function validar($objeto){
		$sql = "SELECT
					id
				FROM
					com_recetas
				WHERE
					id = ".$objeto['id_receta'];
		// return $sql;
		$result =$this->queryArray($sql);
		
		return $result;
	}
///////////////// ******** ---- 			FIN validar				------ ************ //////////////////

///////////////// ******** ---- 		actualizar_receta			------ ************ //////////////////
//////// Modifa el registro en la tabla com_recetas con los datos de la receta
	// Como parametros recibe:
		// id_receta -> ID de la receta
		// nombre -> nombre de la receta o insumo preparado
		// codigo -> codigo de la receta o insumo preparado
		// des -> comentarios sobre la receta o insumo preparado
		// precio_venta -> precio de venta
		// margen_ganancia -> margen de ganancia
		// ids -> ID´s de los insumos
		// ids_preparados -> ID´s de los insumos preparados
		
	function actualizar_receta($objeto){
	// Anti hack
		foreach ($objeto as $key => $value) {
			$datos[$key]=$this->escapalog($value);
		}
	
	// Valida el tipo de producto: 5-> Receta, 4-> Insumo preparado
		$tipo = ($datos['tipo']==1) ? 5 : 4 ;
		
	// Actualiza los datos de la receta
		$sql="	UPDATE
					com_recetas
				SET
					nombre = '".$datos['nombre']."', precio = ".$datos['precio_venta'].",
					ganancia = ".$datos['margen_ganancia'].",ids_insumos = '".$datos['ids']."',
					ids_insumos_preparados = '".$datos['ids_preparados']."',
					preparacion = '".$datos['preparacion']."',
					proveedores_insumos = '".$datos['proveedores_insumos']."',
					ids_normales = '".$datos['normales']."',
					costo_receta = '".$datos['costo_receta']."'
				WHERE
					id = ".$datos['id_receta'];
		// return $sql;
		$result =$this->query($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 		FIN actualizar_receta		------ ************ //////////////////

///////////////// ******** ---- 		guardar_insumo				------ ************ //////////////////
//////// Inserta un registro en la tabla com_recetas con los datos de la receta
	// Como parametros recibe:
		// nombre -> nombre de la receta o insumo preparado
		// tipo -> 1(receta), 2(insumo preparado)
		// des -> comentarios sobre la receta o insumo preparado
		// precio_venta -> precio de venta
		// margen_ganancia -> margen de ganancia
			
	function guardar_insumo($objeto){
	// Anti hack
		foreach ($objeto as $key => $value) {
			$datos[$key]=$this->escapalog($value);
		}
		
	// Valida el tipo de producto: 5-> Receta, 4-> Insumo preparado
		$tipo = ($datos['tipo']==1) ? 5 : 4 ;
		
	// Guarda la receta y regresa el ID
		$sql="	INSERT INTO
					app_producto_material
						(id_producto, cantidad, id_unidad, id_material, opcionales, costear)
				VALUES
					('".$datos['id_receta']."',".$datos['cantidad'].",".$datos['id_unidad'].",
						'".$datos['id']."','".$datos['opcionales']."', '".$datos['costear']."'
					)";
		// return $sql;
		$result =$this->insert_id($sql);
		
		return $result;
	}
			
///////////////// ******** ---- 	FIN	guardar_insumo			------ ************ ///////////////////////////////////

///////////////// ******** ---- 		listar_insumos			------ ************ ///////////////////////////////////
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
			$sql="	SELECT
						p.id AS idProducto, p.nombre, IF(p.tipo_producto = 4, ROUND(p.costo_servicio, 2), IFNULL(pro.costo,0)) AS costo,
						p.id_unidad_compra AS idunidadCompra, p.id_unidad_venta AS idunidad, p.tipo_producto,
						(SELECT
							nombre
						FROM
							app_unidades_medida uni
						WHERE
							uni.id = p.id_unidad_venta) AS unidad, u.factor, GROUP_CONCAT(pro.id_proveedor) as ids_proveedor, GROUP_CONCAT(pro.costo) as costos, pro.id_proveedor as proveedor_select
				FROM
						app_productos p
					LEFT JOIN
							app_campos_foodware f
						ON
							p.id=f.id_producto
					LEFT JOIN
							app_unidades_medida u
						ON
							u.id=p.id_unidad_compra
					LEFT JOIN
							app_costos_proveedor pro
						ON
							pro.id_producto=p.id
					INNER JOIN app_producto_sucursal aps ON aps.id_producto = p.id AND aps.id_sucursal = ".$sucursal."
					WHERE
						status=1".
					$condicion. ' GROUP BY idProducto';
		} else {
         $sql="	SELECT
						p.id AS idProducto, p.nombre, IF(p.tipo_producto = 4, ROUND(p.costo_servicio, 2), IFNULL(pro.costo,0)) AS costo,
						p.id_unidad_compra AS idunidadCompra, p.id_unidad_venta AS idunidad, p.tipo_producto,
						(SELECT
							nombre
						FROM
							app_unidades_medida uni
						WHERE
							uni.id = p.id_unidad_venta) AS unidad, u.factor, GROUP_CONCAT(pro.id_proveedor) as ids_proveedor, GROUP_CONCAT(pro.costo) as costos, pro.id_proveedor as proveedor_select
				FROM
						app_productos p
					LEFT JOIN
							app_campos_foodware f
						ON
							p.id=f.id_producto
					LEFT JOIN
							app_unidades_medida u
						ON
							u.id=p.id_unidad_compra
					LEFT JOIN
							app_costos_proveedor pro
						ON
							pro.id_producto=p.id
					WHERE
						status=1".
					$condicion. ' GROUP BY idProducto';
					//print_r($sql);
		}
		//print_r($sql);
		// return $sql;
		$result = $this->queryArray($sql);
		return $result;
	}
		
///////////////// ******** ---- 	FIN listar_insumos		------ ************ ///////////////////////////////////

///////////////// ******** ---- 		listar_servicios			------ ************ ///////////////////////////////////
	//////// Consulta los insumos y los regresa en un array
		// Como parametros recibe:
			
	function listar_servicios($objeto){
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
			$sql="	SELECT
						p.id AS idProducto, p.nombre, IF(p.tipo_producto = 2, ROUND(p.costo_servicio, 2), IFNULL(pro.costo,0)) AS costo,
						p.id_unidad_compra AS idunidadCompra, p.id_unidad_venta AS idunidad, p.tipo_producto,
						(SELECT
							nombre
						FROM
							app_unidades_medida uni
						WHERE
							uni.id = p.id_unidad_venta) AS unidad, u.factor, GROUP_CONCAT(pro.id_proveedor) as ids_proveedor, GROUP_CONCAT(pro.costo) as costos, pro.id_proveedor as proveedor_select
				FROM
						app_productos p
					LEFT JOIN
							app_campos_foodware f
						ON
							p.id=f.id_producto
					LEFT JOIN
							app_unidades_medida u
						ON
							u.id=p.id_unidad_compra
					LEFT JOIN
							app_costos_proveedor pro
						ON
							pro.id_producto=p.id
					INNER JOIN app_producto_sucursal aps ON aps.id_producto = p.id AND aps.id_sucursal = ".$sucursal."
					WHERE
						status=1".
					$condicion. ' GROUP BY idProducto';
		} else {
         $sql="	SELECT
						p.id AS idProducto, p.nombre, IF(p.tipo_producto = 2, ROUND(p.costo_servicio, 2), IFNULL(pro.costo,0)) AS costo,
						p.id_unidad_compra AS idunidadCompra, p.id_unidad_venta AS idunidad, p.tipo_producto,
						(SELECT
							nombre
						FROM
							app_unidades_medida uni
						WHERE
							uni.id = p.id_unidad_venta) AS unidad, u.factor, GROUP_CONCAT(pro.id_proveedor) as ids_proveedor, GROUP_CONCAT(pro.costo) as costos, pro.id_proveedor as proveedor_select
				FROM
						app_productos p
					LEFT JOIN
							app_campos_foodware f
						ON
							p.id=f.id_producto
					LEFT JOIN
							app_unidades_medida u
						ON
							u.id=p.id_unidad_compra
					LEFT JOIN
							app_costos_proveedor pro
						ON
							pro.id_producto=p.id
					WHERE
						status=1".
					$condicion. ' GROUP BY idProducto';
					//print_r($sql);
		}
		//print_r($sql);
		// return $sql;
		$result = $this->queryArray($sql);
		return $result;
	}
		
///////////////// ******** ---- 	FIN listar_servicios		------ ************ ///////////////////////////////////


///////////////// ******** ---- 		listar				------ ************ ///////////////////////////////////
//////// Consulta las recetas y los regresa en un array
	// Como parametros puede recibir:
		// id -> ID de receta
		// insumos_preparados -> IDs de los insumos preparados
		// tipo -> tipo de producto
		// orden -> orden de la consulta
			
	function listar($objeto){
	// Filtra por el ID de la receta si existe
		$condicion.=(!empty($objeto['id']))?' AND r.id='.$objeto['id']:'';
	// Filtra por los insumos preparados
		$condicion.=(!empty($objeto['insumos_preparados']))?' AND ids_insumos_preparados!=\'\'':'';
	// Filtra por tipo
		$condicion.=(!empty($objeto['tipo']))?' AND p.tipo_producto='.$objeto['tipo']:'';
		
	// Ordena  la consulta si existe
		$condicion.=(!empty($objeto['orden']))?' ORDER BY '.$objeto['orden']:'';
		
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
						p.id AS idProducto, p.nombre, p.costo_servicio AS costo, 
						p.id_unidad_compra AS idunidadCompra, p.id_unidad_venta AS idunidad, 
						(SELECT
							nombre
						FROM
							app_unidades_medida uni
						WHERE
							uni.id=p.id_unidad_venta) AS unidad, u.factor, p.tipo_producto, 
						r.ids_insumos_preparados AS insumos_preparados, r.ids_insumos AS insumos, 
						r.preparacion, r.ganancia, ROUND(p.precio, 2) AS precio, p.codigo,
						r.proveedores_insumos, p.tipo_producto
					FROM
						app_productos p
					LEFT JOIN
							app_campos_foodware f
						ON	
							p.id = f.id_producto
					LEFT JOIN
							com_recetas r
						ON
							r.id = p.id
					LEFT JOIN
							app_unidades_medida u
						ON
							u.id = p.id_unidad_compra
					INNER JOIN app_producto_sucursal aps ON aps.id_producto = p.id AND aps.id_sucursal = ".$sucursal."
					WHERE
						p.status = 1".
					$condicion;
		} else {
			$sql = "SELECT 
						p.id AS idProducto, p.nombre, p.costo_servicio AS costo, 
						p.id_unidad_compra AS idunidadCompra, p.id_unidad_venta AS idunidad, 
						(SELECT
							nombre
						FROM
							app_unidades_medida uni
						WHERE
							uni.id=p.id_unidad_venta) AS unidad, u.factor, p.tipo_producto, 
						r.ids_insumos_preparados AS insumos_preparados, r.ids_insumos AS insumos, 
						r.preparacion, r.ganancia, ROUND(p.precio, 2) AS precio, p.codigo,
						r.proveedores_insumos, p.tipo_producto
					FROM
						app_productos p
					LEFT JOIN
							app_campos_foodware f
						ON	
							p.id = f.id_producto
					LEFT JOIN
							com_recetas r
						ON
							r.id = p.id
					LEFT JOIN
							app_unidades_medida u
						ON
							u.id = p.id_unidad_compra
					WHERE
						p.status = 1".
					$condicion;
		}

		//print_r($sql);
		 //echo $sql;
		$result = $this->queryArray($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 		FIN listar			------ ************ ///////////////////////////////////

///////////////// ******** ---- 	listar_conversion		------ ************ ///////////////////////////////////
	//////// Consulta las conversiones y las regresa en un array
		// Como parametros recibe:
			// unidad -> id de la unidad
			
	function listar_conversion($objeto){
		$sql="	SELECT 
					factor AS conversion
				FROM 
					app_unidades_medida 
				WHERE 
					id =".$objeto['unidad'];
		// return $sql;
		$result = $this->queryArray($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 	FIN listar_conversion	------ ************ ///////////////////////////////////

///////////////// ******** ---- 		listar_materiales	------ ************ ///////////////////////////////////
	//////// Consulta los insumos y los regresa en un array
		// Como parametros recibe:
			
	function listar_materiales($objeto){
	// Filtra por el ID del insumo si existe
		$condicion.=(!empty($objeto['id']))?' AND p.id='.$objeto['id']:'';
	// Filtra por los IDs de los insumo si existe
		$condicion.=(!empty($objeto['ids']))?' AND p.id IN ('.$objeto['ids'].')':'';
		
	// Filtra por el nombre del insumo si existe
		$condicion.=(!empty($objeto['tipo_producto']))?' AND p.tipo_producto='.$objeto['tipo_producto']:'';
		
		$sql = "SELECT 
					p.id AS idProducto, p.nombre, 
					IF(p.tipo_producto IN (2,4), ROUND(p.costo_servicio, 2), IFNULL(pro.costo,0)) AS costo,
					p.id_unidad_compra AS idunidadCompra, p.id_unidad_venta AS idunidad, 
					(SELECT
						nombre
					FROM
						app_unidades_medida uni
					WHERE
						uni.id=p.id_unidad_venta) AS unidad, u.factor, m.cantidad, m.opcionales AS opcionales, m.costear,
						GROUP_CONCAT(pro.id_proveedor) as ids_proveedor, GROUP_CONCAT(pro.costo) as costos		
				FROM 
					app_productos p
				INNER JOIN
						app_producto_material m
					ON
						p.id=m.id_material
				LEFT JOIN
						app_unidades_medida u
					ON
						u.id=p.id_unidad_compra
				LEFT JOIN
						app_costos_proveedor pro
					ON
						pro.id_producto=p.id
				WHERE 
					p.status=1
				AND
					m.id_producto = ".$objeto['id_receta'].
				$condicion . " group by idProducto";
						// return $sql;
				
				
				
		$result = $this->queryArray($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 		FIN listar_materiales		------ ************ /////////////////////////////

///////////////// ******** ---- 		eliminar_insumos			------ ************ ////////////////////////////
	//////// Elimina los materiales de la receta o insumo preparado
		// Como parametros recibe:
			// id_receta -> id de la receta
			
	function eliminar_insumos($objeto){
		$sql="	DELETE FROM
					 app_producto_material
				WHERE 
					id_producto =".$objeto['id_receta'];
		// return $sql;
		$result = $this->query($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 		FIN eliminar_insumos		------ ************ ///////////////////////////

///////////////// ******** ---- 				eliminar			------ ************ //////////////////////////
//////// Elimina una receta o insumo preparado, el producto y sus materiales
	// Como parametros recibe:
		// id -> ID de la receta o insumo preparado
		
	function eliminar($objeto){
		$sql="	UPDATE
					com_recetas
				SET
					status = 2
				WHERE
					id = ".$objeto['id'].";
					
				UPDATE
					app_productos
				SET
					status = 0
				WHERE
					id = ".$objeto['id'].";
					
				UPDATE
					app_producto_material
				SET
					status = 0
				WHERE
					id_producto = ".$objeto['id'].";";
		// return $sql;
		$result = $this->dataTransact($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 			FIN eliminar		------ ************ ///////////////////////////

///////////////// ******** ---- 			restaurar_precio			------ ************ //////////////////
//////// Busca el precio actual del producto y lo agrega al campo precio_venta
	// Como parametros recibe:
		// id -> ID de la receta o insumo preparado
		// btn -> boton del loader
			
	function restaurar_precio($objeto){
		$sql="	SELECT
					ROUND(precio, 2) AS precio
				FROM
					app_productos
				WHERE
					id =".$objeto['id'];
		// return $sql;
		$result = $this->queryArray($sql);
		
		return $result;
	}
		
///////////////// ******** ---- 			FIN restaurar_precio		------ ************ ///////////////////////////////////

///////////////// ******** ---- 			listar_unidades				------ ************ ///////////////////
	//////// Consulta las unidades de medida y los regresa en un array
		// Como parametros recibe:
			
	function listar_unidades($objeto){
		$sql = "SELECT 
					*
				FROM
					app_unidades_medida";
		// return $sql;
		$result = $this->queryArray($sql);
		
		return $result;
	}

///////////////// ******** ---- 			FIN listar_unidades			------ ************ ///////////////////

///////////////// ******** ---- 			preparar_insumo				------ ************ //////////////////
//////// Guarda en la BD la preparacion del insumo
	// Como parametros recibe:
		// id -> ID del preparado
		// cantidad -> Cantidad que se debe preparar del insumo
		// f_ini -> Fecha inicial
		// f_fin -> Fecha final
			
	function preparar_insumo($objeto){
		$sql = "INSERT INTO 
					com_preparaciones(id_producto, cantidad, f_ini)
				VALUES
					(".$objeto['id_producto'].", ".$objeto['cantidad'].", '".$objeto['f_ini']."')";
		// return $sql;
		$result = $this->insert_id($sql);
		
		return $result;
	}

///////////////// ******** ---- 			FIN preparar_insumo			------ ************ //////////////////

///////////////// ******** ---- 			descontar_insumo			------ ************ //////////////////
//////// Descuenta del inventario el insumo del producto
	// Como parametros recibe:
		// id -> ID del insumo
		// id_producto -> ID del producto

	function descontar_insumo($objeto) {
		session_start();
	// Obtiene el almacen
		$almacen = "SELECT 
						a.id
					FROM 
						administracion_usuarios au
					LEFT JOIN 
							app_almacenes a
						ON 
							a.id_sucursal = au.idSuc
					WHERE 
						au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
					LIMIT 1";
		$almacen = $this -> queryArray($almacen);
		$almacen = $almacen['rows'][0]['id'];

	// Valida que exista el almacen
		$almacen = (empty($almacen)) ? 1 : $almacen;
		
		$sql = "INSERT INTO
					app_inventario_movimientos
						(id_producto, cantidad, importe, id_almacen_origen, fecha, id_empleado,
						tipo_traspaso, costo, referencia)
					VALUES
						('" . $objeto['idProducto'] . "', '" . $objeto['cantidad'] . "', '" . $objeto['importe'] . "', 
						'" . $almacen . "', '" . $objeto['fecha'] . "', '" . $_SESSION['accelog_idempleado'] . "', 0, 
						'" . $objeto['costo'] . "', 'Preparacion " . $objeto['id_preparacion'] . "')";
		$result = $this -> query($sql);
		
		return $result;
	}
			
///////////////// ******** ---- 			FIN descontar_insumo			------ ************ //////////////////

///////////////// ******** ---- 				terminar_insumo				------ ************ //////////////////
//////// Actualiza el inventario y el insumo preparado en la BD
	// Como parametros recibe:
		// id -> ID del insumo preparado
		// id_preparacion -> ID de la preparacion
		// cantidad -> Cantidad que se debe preparar del insumo
		
	function terminar_insumo($objeto){
		session_start();
	// Obtiene el almacen
		$almacen = "SELECT 
						a.id
					FROM 
						administracion_usuarios au
					LEFT JOIN 
							app_almacenes a
						ON 
							a.id_sucursal = au.idSuc
					WHERE 
						au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
					LIMIT 1";
		$almacen = $this -> queryArray($almacen);
		$almacen = $almacen['rows'][0]['id'];
		
	// Valida que exista el almacen
		$almacen = (empty($almacen)) ? 1 : $almacen;
		
	// Actualiza el inventario y el insumo preparado en la BD
		$sql = "UPDATE
					com_preparaciones
				SET
					f_fin = '".$objeto['f_fin']."'
				WHERE
					id = ".$objeto['id_preparacion'].";
					
				INSERT INTO
					app_inventario_movimientos
					(id_producto, cantidad, importe, id_almacen_destino, fecha, id_empleado,
					tipo_traspaso, costo, referencia)
				VALUES
					('" . $objeto['id'] . "', '" . $objeto['cantidad'] . "', '" . $objeto['importe'] . "', 
					'" . $almacen . "', '" . $objeto['f_fin'] . "', '" . $_SESSION['accelog_idempleado'] . "', 1, 
					'" . $objeto['precio'] . "', 'Preparacion " . $objeto['id_preparacion'] . "');";
		// return $sql;
		$result = $this->dataTransact($sql);
		
		return $result;
	}
			
///////////////// ******** ---- 			FIN	terminar_insumo					------ ************ //////////////////

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
	// Se filtra por fecha de inicio y fin si estas existen
		$condicion .= (!empty($objeto['f_ini']) && !empty($objeto['f_fin'])) ? 
			' AND fecha BETWEEN \'' . $objeto['f_ini'] . ' 00:00:01\' AND \'' . $objeto['f_fin'] . ' 23:59:59\'' : '';
	// Filtra por la sucursal si existe
		$condicion .= (!empty($objeto['sucursal'])) ? ' AND a.id_sucursal IN ('.$objeto['sucursal'].')' : '' ;
	// Filtra por el almacen si existe
		$condicion .= (!empty($objeto['alamacen'])) ? ' AND a.id IN ('.$objeto['almacen'].')' : '' ;
	// Filtra por los insumos si existen
		$condicion .= (!empty($objeto['insumos'])) ? ' AND p.id IN ('.$objeto['insumos'].')' : '' ;
	// Filtra por el tipo de producto
		$condicion .= (!empty($objeto['tipo'])) ? ' AND p.tipo_producto IN ('.$objeto['tipo'].')' : '' ;
			
		$sql = "SELECT
					p.id AS id_insumo, p.ruta_imagen, r.ids_insumos, r.ids_insumos_preparados, p.codigo, p.nombre, GROUP_CONCAT(DISTINCT s.nombre) AS sucursal, GROUP_CONCAT(DISTINCT a.nombre) AS almacen,
					IF(m.id_almacen_origen > 0, m.id_almacen_origen, m.id_almacen_destino) AS almacen, m.tipo_traspaso, 
					SUM(m.cantidad) AS cantidad, m.referencia, m.fecha, u.nombre AS unidad,
					CASE 
						WHEN  m.referencia LIKE 'Pedido%' THEN 
							(SELECT 
								pro.nombre
							FROM
								com_pedidos pe
							LEFT JOIN
									app_productos pro
								ON
									pe.idproducto = pro.id
							WHERE
								pe.id = (substr(m.referencia, 7)))
						WHEN m.referencia LIKE 'Combo%' THEN 
							(SELECT 
								pro.nombre
							FROM
								app_productos pro
							WHERE
								pro.id = (substr(m.referencia, 6)))
						WHEN m.referencia LIKE 'Preparacion%' THEN 
							CONCAT('Insumo preparado-', (SELECT 
								pro.nombre
							FROM
								com_preparaciones pre
							LEFT JOIN
									app_productos pro
								ON
									pre.id_producto = pro.id
							WHERE
								pre.id = (substr(m.referencia, 12))))
						WHEN m.referencia LIKE 'Venta%' THEN 
							'Venta directa'
						WHEN m.referencia LIKE 'Comanda%' THEN 
							'Comandas'
					END AS origen_text
				FROM
					app_inventario_movimientos m
				LEFT JOIN
						app_productos p
					ON
						p.id = m.id_producto
				LEFT JOIN 
						com_recetas r 
					ON 
						p.id = r.id
				LEFT JOIN
						app_unidades_medida u
					ON
						u.id = p.id_unidad_venta
				LEFT JOIN
						app_almacenes a
					ON
						a.id = IF(m.id_almacen_origen > 0, m.id_almacen_origen, m.id_almacen_destino)
				LEFT JOIN
						mrp_sucursal s
					ON
						s.idSuc = a.id_sucursal
				WHERE
					(m.tipo_traspaso = 0 OR m.tipo_traspaso = 1) ".
					$condicion."
				GROUP BY
					p.id, m.tipo_traspaso, origen_text
				HAVING
					origen_text != ''";
		// return $sql;
		$result = $this->queryArray($sql);
		
		return $result;
	}

///////////////// ******** ---- 		FIN listar_movimientos_inventario			------ ************ //////////////////

///////////////// ******** ---- 		listar_movimientos_inventario_barra			------ ************ //////////////////
//////// Consluta las entradas y las salidas de los productos
	// Como parametros recibe:
		// f_ini -> Fecha inicial
		// f_fin -> Fecha final
		// sucursal -> ID de la sucursal
		// almacen -> ID del almacen
		// grafica -> 1 -> Dia, 2 -> Semana, 3 -> Mes, 4 -> Año
		// insumos -> string con los ID's de los insumos
		// tipo -> 3 -> Insumo, 4 -> insumo preparado
			
	function listar_movimientos_inventario_barra($objeto){
	// Se filtra por fecha de inicio y fin si estas existen
		$condicion .= (!empty($objeto['f_ini']) && !empty($objeto['f_fin'])) ? 
			' AND fecha BETWEEN \'' . $objeto['f_ini'] . ' 00:00:01\' AND \'' . $objeto['f_fin'] . ' 23:59:59\'' : '';
	// Filtra por la sucursal si existe
		$condicion .= (!empty($objeto['sucursal'])) ? ' AND a.id_sucursal IN ('.$objeto['sucursal'].')' : '' ;
	// Filtra por el almacen si existe
		$condicion .= (!empty($objeto['alamacen'])) ? ' AND a.id IN ('.$objeto['almacen'].')' : '' ;
	// Filtra por los insumos si existen
		$condicion .= (!empty($objeto['insumos'])) ? ' AND p.id IN ('.$objeto['insumos'].')' : '' ;
	// Filtra por el tipo de producto
		$condicion .= (!empty($objeto['tipo'])) ? ' AND p.tipo_producto IN ('.$objeto['tipo'].')' : '' ;
			
		$sql = "SELECT p.nombre, SUM(m.cantidad) AS cantidad, CASE WHEN m.referencia LIKE 'Pedido%' THEN (SELECT pro.nombre FROM com_pedidos pe LEFT JOIN app_productos pro ON pe.idproducto = pro.id WHERE pe.id = (substr(m.referencia, 7))) WHEN m.referencia LIKE 'Combo%' THEN (SELECT pro.nombre FROM app_productos pro WHERE pro.id = (substr(m.referencia, 6))) WHEN m.referencia LIKE 'Preparacion%' THEN CONCAT('Insumo preparado-', (SELECT pro.nombre FROM com_preparaciones pre LEFT JOIN app_productos pro ON pre.id_producto = pro.id WHERE pre.id = (substr(m.referencia, 12)))) WHEN m.referencia LIKE 'Venta%' THEN 'Venta directa' WHEN m.referencia LIKE 'Comanda%' THEN 'Comandas' END AS origen_text FROM app_inventario_movimientos m 
				LEFT JOIN app_productos p ON p.id = m.id_producto 
				LEFT JOIN com_recetas r ON p.id = r.id
				LEFT JOIN app_unidades_medida u ON u.id = p.id_unidad_venta 
				LEFT JOIN app_almacenes a ON a.id = IF(m.id_almacen_origen > 0, m.id_almacen_origen, m.id_almacen_destino) 
				LEFT JOIN mrp_sucursal s ON s.idSuc = a.id_sucursal 
				WHERE (m.tipo_traspaso = 0 OR m.tipo_traspaso = 1) AND m.tipo_traspaso != 1 ".
					$condicion."
				GROUP BY
					p.id, m.tipo_traspaso, origen_text
				HAVING
					origen_text != '' ORDER BY cantidad desc LIMIT 10";
					//print_r($sql);
		// return $sql;
		$result = $this->queryArray($sql);
		
		return $result;
	}

///////////////// ******** ---- 		FIN listar_movimientos_inventario_barra			------ ************ //////////////////

///////////////// ******** ---- 					listar_sucursales				------ ************ //////////////////
//////// Consulta las sucursales y las regresa en un array
	// Como parametros recibe:
		// id -> id de la sucursal

	function listar_sucursales($objeto) {
	// Si viene el id del empleado Filtra por empleado
		$condicion .= (!empty($objeto['id'])) ? ' AND idSuc = \'' . $objeto['id'] . '\'' : '';

	// Ordena si existe, si no ordena por ID descendente
		$orden .= (!empty($objeto['orden'])) ? ' ' . $objeto['orden'] : ' idSuc DESC';

		$sql = "SELECT
					idSuc AS id, nombre
				FROM 
					mrp_sucursal
				WHERE
					1 = 1" . 
				$condicion . "
				ORDER BY " . 
					$orden;
		// return $sql;
		$result = $this -> queryArray($sql);
		return $result;
	}

///////////////// ******** ---- 					FIN listar_sucursales			------ ************ //////////////////

///////////////// ******** ---- 					listar_almacenes				------ ************ //////////////////
//////// Consulta los almacenes y las regresa en un array
	// Como parametros recibe:

	function listar_almacenes($objeto) {
		$sql = "SELECT
					id, nombre
				FROM 
					app_almacenes";
		// return $sql;
		$result = $this -> queryArray($sql);
		return $result;
	}

///////////////// ******** ---- 					FIN listar_almacenes			------ ************ //////////////////

	public function unidad_medida($clave)
    {
        $clave = strtoupper($clave);
        $res = $this->query("SELECT id FROM app_unidades_medida WHERE clave = '$clave'");
        $res = $res->fetch_assoc();
        return $res['id'];
    }

    public function id_producto_codigo($codigo)
    {
        $clave = strtoupper($clave);
        $res = $this->query("SELECT id FROM app_productos WHERE codigo = '$codigo'");
        $res = $res->fetch_assoc();
        return $res['id'];
    }

	public function borrar($val)
    {	
        $myQuery = "DELETE FROM app_producto_material WHERE status =".$val;
        $this->query($myQuery);
        $myQuery = "DELETE FROM com_recetas WHERE status =".$val;
        $this->query($myQuery);
        $myQuery = "DELETE FROM app_productos WHERE status =".$val;
        $this->query($myQuery);
    }

    public function activar_recetas($val)
    {
        $myQuery = "UPDATE app_producto_material SET status = 1 WHERE status = ".$val;
        $this->query($myQuery);
        $myQuery = "UPDATE app_productos SET status = 1 WHERE status = ".$val;
        $this->query($myQuery);
        $myQuery = "UPDATE com_recetas SET status = 1 WHERE status = ".$val;
        $this->query($myQuery);
    }

///////////////// ******** ---- 					guardarLay				------ ************ //////////////////
//////// Consulta los almacenes y las regresa en un array
	// Como parametros recibe:
    public function guardarLay($dato)
    {	//return $dato;
    	$resp['status'] = 1;
		$resp['mensaje'] = 'La insercion de las recetas fue un exito.';

        foreach ($dato as $key => $value) {

			$existe = 0;
        	$sql = "select id from app_productos where codigo = '".$value['codigo']."' or nombre = '".$value['nombre']."';";
			$result = $this -> queryArray($sql);			
			if (count($result['rows'])>0) {
				$existe = 1;
				//$resp['status'] = 2;
				//$resp['mensaje'] = 'La receta '.$value['codigo'].' '.$value['nombre'].' ya existe en la base de datos.';
				//break;
			}
		

			$sql = "select id from app_productos where codigo = '".$value['codigo']."' or nombre = '".$value['nombre']."';";
			$result = $this -> queryArray($sql);
			$id = $result['rows'][0]['id'];

			// Busca si existen insumos o insumos preparados
			$sql2 = "SELECT ids_insumos, ids_insumos_preparados FROM com_recetas where id = '$id';";
			$result2 = $this -> queryArray($sql2);
			$insumos = $result2['rows'][0]['ids_insumos'];
			$insumosP = $result2['rows'][0]['ids_insumos_preparados'];
			if($insumos != '' || $insumosP != ''){
				$resp['status'] = 2;
				$resp['mensaje'] =  'La receta '.$value['codigo'].' '.$value['nombre'] .' ya tiene insumos';
				break; 
			}

        	$id_receta = 0;
        	$id_receta_2 = 0;
        	if($value['precio'] == 0){
        		$value['precio'] = $value['precio_costeo'] + (($value['precio_costeo'] * $value['ganancia'])/100);
        	}
        	if($existe == 1){
        		$id_receta = $id;
        		$id_receta_2 = $id;

        		// update si se requiere para app_produc

        		// update para com_recetas (ids_insumos, ids_insumos_preparados)
        		if ($id_receta) {
        			$value['insumos_ids'] = substr($value['insumos_ids'], 0, -1);
					$value['insumos_elaborados_ids'] = substr($value['insumos_elaborados_ids'], 0, -1);
					
	        		$sql="UPDATE
						com_recetas
					SET
						ids_insumos = '".$value['insumos_ids']."', 
						ids_insumos_preparados = '".$value['insumos_elaborados_ids']."'
					WHERE
						id=".$id_receta.";";
					$this->query($sql);

					if($id_receta_2){
							foreach ($value['insumos'] as $key2 => $value2) {
								$id_material = 0;
								$sql="	INSERT INTO
											app_producto_material
												(id_producto, 
												cantidad, 
												id_unidad, 
												id_material, 
												opcionales, 
												costear, 
												status)
										VALUES
											('".$id_receta."',
											'".$value2['cantidad']."',
											'".$value2['unidad_venta']."',
												'".$value2['id_producto']."',
												'".$value2['tipo_modificador']."', 
												'".$value2['costeo']."',
												 99
											)";
								// return $sql;
								$id_material = $this->insert_id($sql);
								if (!$id_material) {
									$resp['status'] = 2;
									$resp['mensaje'] = 'Ocurrio un error al insertar un material a la receta en la base de datos.';
									break;
								}
							}
							foreach ($value['insumos_elaborados'] as $key2 => $value2) {
								$id_material = 0;
								$sql="	INSERT INTO
											app_producto_material
												(id_producto, cantidad, id_unidad, id_material, opcionales, costear, status)
										VALUES
											('".$id_receta."','".$value2['cantidad']."','".$value2['unidad_venta']."',
												'".$value2['id_producto']."','".$value2['tipo_modificador']."', '".$value2['costeo']."', 99
											)";
								// return $sql;
								$id_material = $this->insert_id($sql);
								if (!$id_material) {
									$resp['status'] = 2;
									$resp['mensaje'] = 'Ocurrio un error al insertar un material a la receta en la base de datos.';
									break;
								}
							}
						} else {
							$resp['status'] = 2;
							$resp['mensaje'] = 'Ocurrio un error al insertar una receta en la base de datos.';
							break;
						}						
        		}        				
				//return $result;
        	}else{

        		$sql="	INSERT INTO
						app_productos
							(codigo, nombre, precio, linea, costo_servicio, id_unidad_venta, id_unidad_compra, 
								tipo_producto, status
							)
					VALUES
						('".$value['codigo']."','".$value['nombre']."','".$value['precio']."',
							'1', '".$value['precio_costeo']."', '".$value['unidad_venta']."', '".$value['unidad_compra']."', 5, 99
						)";
				// return $sql;
				$id_receta =$this->insert_id($sql);

				if ($id_receta) {
						// Guarda la receta y regresa el ID
						$value['insumos_ids'] = substr($value['insumos_ids'], 0, -1);
						$value['insumos_elaborados_ids'] = substr($value['insumos_elaborados_ids'], 0, -1);
						$sql="	INSERT INTO
									com_recetas
										(id, nombre, precio, ganancia, ids_insumos, ids_insumos_preparados, preparacion, status)
								VALUES
									(".$id_receta.", '".$value['nombre']."','".$value['precio']."',
										'".$value['ganancia']."',
										'".$value['insumos_ids']."','".$value['insumos_elaborados_ids']."','".$value['preparacion']."', 99
									)";
						// return $sql;
						$id_receta_2 =$this->insert_id($sql);
						if($id_receta_2){
							foreach ($value['insumos'] as $key2 => $value2) {
								$id_material = 0;
								$sql="	INSERT INTO
											app_producto_material
												(id_producto, cantidad, id_unidad, id_material, opcionales, costear, status)
										VALUES
											('".$id_receta."','".$value2['cantidad']."','".$value2['unidad_venta']."',
												'".$value2['id_producto']."','".$value2['tipo_modificador']."', '".$value2['costeo']."', 99
											)";
								// return $sql;
								$id_material = $this->insert_id($sql);
								if (!$id_material) {
									$resp['status'] = 2;
									$resp['mensaje'] = 'Ocurrio un error al insertar un material a la receta en la base de datos.';
									break;
								}
							}
							foreach ($value['insumos_elaborados'] as $key2 => $value2) {
								$id_material = 0;
								$sql="	INSERT INTO
											app_producto_material
												(id_producto, cantidad, id_unidad, id_material, opcionales, costear, status)
										VALUES
											('".$id_receta."','".$value2['cantidad']."','".$value2['unidad_venta']."',
												'".$value2['id_producto']."','".$value2['tipo_modificador']."', '".$value2['costeo']."', 99
											)";
								// return $sql;
								$id_material = $this->insert_id($sql);
								if (!$id_material) {
									$resp['status'] = 2;
									$resp['mensaje'] = 'Ocurrio un error al insertar un material a la receta en la base de datos.';
									break;
								}
							}
						} else {
							$resp['status'] = 2;
							$resp['mensaje'] = 'Ocurrio un error al insertar una receta en la base de datos.';
							break;
						}
						
					} else {
						$resp['status'] = 2;
						$resp['mensaje'] = 'Ocurrio un error al insertar una receta en la base de datos.';
						break;
					}
        	}
			
        }
        return $resp; 
    }
///////////////// ******** ---- 					FIN guardar_lay			------ ************ //////////////////

} ?>